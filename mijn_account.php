<?php
include('configuratie/configuratie.php');
include('includes/plug.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = VOTRE_COMPTE;
$message112 = "";


if(isset($_GET['accountRec']) AND $_GET['accountRec']!=="" AND isset($_GET['emailRec']) AND $_GET['emailRec']!=="" AND isset($_GET['blg']) AND $_GET['blg']!=="") {
	$recoverOrderRequestZ = mysql_query("SELECT users_payed, users_confirm, users_statut, users_refund, users_nic, users_devis FROM users_orders WHERE users_nic = '".$_GET['blg']."'");
	if(mysql_num_rows($recoverOrderRequestZ)>0) {
		$rec = mysql_fetch_array($recoverOrderRequestZ);
		if($rec['users_payed']=="yes" OR ($rec['users_statut']=="yes" AND $rec['users_confirm']=="yes") OR $rec['users_refund']=="yes" OR substr($rec['users_nic'], 0, 5)=="TERUG" OR $rec['users_devis']!=="") {
			print "Not valid";
			exit;
		}
		else {
			$_POST['account']=$_GET['accountRec'];
			$_POST['email']=$_GET['emailRec'];
			$_SESSION['recup']="yes";
			$_SESSION['clientNicZ'] = $_GET['blg'];
		}
	}
}

 
if(isset($_GET['accountRec1']) AND $_GET['accountRec1']!=="" AND isset($_GET['emailRec1']) AND $_GET['emailRec1']!=="" AND isset($_GET['addOrder']) AND $_GET['addOrder']=="1") {
	$_POST['account']=$_GET['accountRec1'];
	$_POST['email']=$_GET['emailRec1'];
}

 
function discount_on_quantity($id, $qt, $price) {
	$prodsCount = mysql_query("SELECT * FROM discount_on_quantity
								WHERE discount_qt_prod_id='".$id."'
								AND discount_qt_qt <= '".$qt."'
								ORDER BY discount_qt_discount ASC") or die (mysql_error());
	$prodsCountNum = mysql_num_rows($prodsCount);
	if($prodsCountNum > 0) {
		while($prodsCountResult = mysql_fetch_array($prodsCount)) {
				$prods[$prodsCountResult['discount_qt_qt']] = $prodsCountResult['discount_qt_discount'].$prodsCountResult['discount_qt_value'];
		}
		
		if(isset($prods) AND count($prods)>0) {
			$discountOnQtFinal = end($prods);
			$cutExt = explode('%',$discountOnQtFinal);
			if(isset($cutExt[1])) {
				$priceW = $price-($price*$cutExt[0]/100);
			}
			else {
				$discountOnQtFinalNumeric = str_replace('euro','',$discountOnQtFinal);
					if(is_numeric($discountOnQtFinalNumeric)) {
						$priceW = $price-$discountOnQtFinalNumeric;
					}
					else {
						$priceW = $price;
					}
			}
			return $priceW;
		}
		else {
			$priceW = $price;
		}
	}
	else {
		$priceW = $price;
	}
	return $priceW;
}

 
function recup_commande($saveCartToOneZ) {
GLOBAL $session_article, $addToQuery, $_SESSION, $actRes;
	foreach ($session_article AS $item) {
			$addToQuery2 = ($actRes=="oui")? "" : " AND p.products_qt>'0'";
               $check = explode("+", $item);
               $requete = mysql_query("SELECT p.products_price, p.products_deee, p.products_ref, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                                           FROM products as p
                                           LEFT JOIN specials as s
                                           ON (p.products_id = s.products_id)
                                           WHERE p.products_id = '".$check[0]."'
                                           AND p.products_visible= 'yes'
                                           AND p.products_forsale= 'yes'
                                           ".$addToQuery." 
                                           ".$addToQuery2."
                                           ");
                                           $rowal = mysql_fetch_array($requete);
                                           $rowalNum = mysql_num_rows($requete);
               if($rowalNum==0) {
                  $check[1]=0;
                  $rowal['specials_new_price'] = "0.00";
                  $rowal['products_ref'] = "NULL";
               }
               else {
                	if(!empty($rowal['specials_new_price'])) {
                    	if($rowal['specials_visible']=="yes") {
                                  $today = mktime(0,0,0,date("m"),date("d"),date("Y"));

                                  $dateMaxCheck = explode("-",$rowal['specials_last_day']);
                                  $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
                                  $dateDebutCheck = explode("-",$rowal['specials_first_day']);
                                  $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);

                                  if($dateDebut <= $today  AND $dateMax >= $today) {
                                     $rowal['specials_new_price'] = $rowal['specials_new_price'];
                                  }
                                  else {
                                     $rowal['specials_new_price'] = $rowal['products_price'];
                                  }
                        }
                        else {
							$rowal['specials_new_price'] = $rowal['products_price'];
						}
                        if(isset($_SESSION['reduc'])) {
                        	$rowal['specials_new_price'] = sprintf("%0.2f",$rowal['specials_new_price']-($rowal['specials_new_price']*$_SESSION['reduc']/100));
                        }
                    }
                    else {
                    	$rowal['specials_new_price'] = $rowal['products_price'];
                        if(isset($_SESSION['reduc'])) {
                        	$rowal['specials_new_price'] = sprintf("%0.2f",$rowal['specials_new_price']-($rowal['specials_new_price']*$_SESSION['reduc']/100));
                        }
                    }
               }
                         
               
               if(isset($check[6]) AND $check[6]!=="") {
               $optionPrice=0;
                  $optCadExplode = explode("|", $check[6]);
                  $lastArray = $optCadExplode[count($optCadExplode)-1];
			         		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
			         			$out = array_pop($optCadExplode);
			         			$addReq = implode('|', $optCadExplode);
			         			$optCadExplode = explode("|", $addReq);
			         		}
                  foreach($optCadExplode as $ii) {
                     $reqOptQuery = mysql_query("SELECT products_option 
                                                   FROM products_id_to_products_options_id
                                                   WHERE products_option LIKE '%".$ii."%'
                                                   AND products_id = '".$check[0]."'");
                     if(mysql_num_rows($reqOptQuery) > 0) {
                        $reqOptResult = mysql_fetch_array($reqOptQuery);
                        $expl1 = explode(",", $reqOptResult['products_option']);
                        foreach($expl1 as $ii2) {
                          $lookForThis = strstr($ii2, $ii);
                          if(isset($lookForThis) AND !empty($lookForThis)) {
                              $ii3 = explode("::",$lookForThis);
                              $optionPrice = $optionPrice + ($ii3[1]);
                          }
                        }
                     }
                  }
                  $rowal['specials_new_price'] = sprintf("%0.2f",$rowal['specials_new_price'] + $optionPrice);
               }

                 
				$rowal['specials_new_price'] = discount_on_quantity($check[0], $check[1], $rowal['specials_new_price']);
				
                 
                if(isset($saveCartToOneZ) AND $saveCartToOneZ=="oui") $check[1]=1;
                $toto[] = $check[0]."+".$check[1]."+".$rowal['specials_new_price']."+".$check[3]."+".$check[4]."+".$check[5]."+".$check[6]."+".$check[7]."+".$check[8];
	}
    return $toto;
}

 
function pendingOrder($num) {
	GLOBAL $_SESSION, $pendingOrder, $directPayment, $paymentsDesactive;
	 
	function nbJours($orderAdded) {
		$date1 = date("Y-m-d H:i:s");
		$date2 = $orderAdded;
		$nbjours = abs(floor((strtotime($date1) - strtotime($date2))/(86400)));
		if($nbjours>0) {
			$jour = ($nbjours>1)? " ".JOURS."en" : " ".JOURS;
		}
		else {
			$nbjours = AUX_ABONNES_DE_LA_NEWSLETTER;
			$jour = "";
		}
		return array($nbjours, $jour);
	}
	
	if($pendingOrder==1000) $moreRequest=""; else $moreRequest="AND TO_DAYS(NOW()) - TO_DAYS(users_date_added) <= '".$pendingOrder."' ";
	$enCoursQuery = mysql_query("SELECT users_nic, users_date_added, users_confirm, users_statut
	                             FROM users_orders
	                             WHERE  users_password = '".$_SESSION['account']."'
	                             AND users_payed = 'no'
	                             AND users_nic NOT LIKE 'TERUG%' 
	                             AND users_devis = ''
	                             ".$moreRequest."
	                             AND users_customer_delete = 'no'
	                             ORDER  BY users_date_added
	                             DESC");
	$enCoursQueryNum = mysql_num_rows($enCoursQuery);
	if($enCoursQueryNum>0) {
		print "<br><span class='fontrouge'><b>".EN_ATTENTE."</b></span>";
		if($moreRequest!=="") {
	    	print "<br><img src='im/zzz.gif' height='5' width='1'><br>";
	    	print "".PENDANT_X_JOURS."";
	  	}
	  	print "<br><img src='im/zzz.gif' height='10' width='1'><br>";
	  	print "<table cellspacing='0' cellpadding='0' border='0'>";
	  
	  	while($enCours = mysql_fetch_array($enCoursQuery)) {
	    	print "<tr>";
	    	print "<td>&bull;&nbsp;".dateFr($enCours['users_date_added'], $_SESSION['lang'])."</td>";
	    	print "<td>&nbsp;&nbsp;&nbsp;<a href='klantlogin/login.php?nic=".$enCours['users_nic']."&l=".$_SESSION['lang']."' target='_blank'>".$enCours['users_nic']."</a></td>";
	    	 
	    	print "<td>&nbsp;&nbsp;&nbsp;<a href='mijn_account.php?blg=".$enCours['users_nic']."&c=add'><img src='im/cart2.gif' border='0' title='".RECUP_COMMANDE_PANIER."' alt='".RECUP_COMMANDE_PANIER."'></a></td>";
	    	 
	    	if($directPayment=="oui" AND $paymentsDesactive=="non") print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='direct_betalen.php?nic=".$enCours['users_nic']."'><img src='im/direct_payment.gif' border='0' title='".DIRECT_PAYMENT."' alt='".DIRECT_PAYMENT."'></a></td>";
	    	 
	    	if($enCours['users_statut']=="no") {
		    	print "<td>&nbsp;&nbsp;&nbsp;<a href='mijn_account.php?nic=".$enCours['users_nic']."&action=del'><img src='im/supprimer2.gif' border='0' title='".SUPPRIMER_CETTE_COMMANDE."' alt='".SUPPRIMER_CETTE_COMMANDE."'></a></td>";
		    	if($moreRequest!=="") {
		         	$nbreDeJours = nbJours($enCours['users_date_added']);
		         	$commandeEffectue = ($nbreDeJours[0]==AUX_ABONNES_DE_LA_NEWSLETTER)? COMMANDE_EFFECTUEE : COMMANDE_EFFECTUEE_IL_Y_A;
		         	print "<td>&nbsp;&nbsp;&nbsp;<a href='#' title='".$commandeEffectue." ".strtolower($nbreDeJours[0])." ".strtolower($nbreDeJours[1])."'><span class='FontGris'><i>(".$nbreDeJours[0].strtolower($nbreDeJours[1]).")</i></span></a></td>";
		    	}
	    	}
	    	 
	    	if($enCours['users_statut']=="yes") {print "<td colspan='2'>&nbsp;&nbsp;&nbsp;...".EXP.".</td>";}
	    	print "</tr>";
	  }
	  print "</table>";
	}
	else {
		if($num>0) {print "";} else {print "<div><b>".NO_ORDER_NADA."</b></div>";}
	}
}

 
if(isset($_GET['formId']) AND $_GET['formId']!=="") {
      if(!isset($_GET['resp'])) {
        $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
        $messageZZ .= SUPPRIMER." ".$_GET['formId']." ?";
        $messageZZ .= "<br>";
        $messageZZ .= "<b><a href='mijn_account.php?formId=".$_GET['formId']."&resp=yes'>";
        $messageZZ .= "<span>".OUI."</span>";
        $messageZZ .= "</a>";
        $messageZZ .= " | ";
        $messageZZ .= "<a href='mijn_account.php?formId=".$_GET['formId']."&resp=no'>";
        $messageZZ .= "<span>".NON."</span>";
        $messageZZ .= "</a>";
        $messageZZ .= "</b>";
        $messageZZ .= "</p>";
      }
      else {
         $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
         if($_GET['resp']=="yes") {
            
            mysql_query("UPDATE users_orders SET users_save_data_from_form = 'no' WHERE users_save_data_from_form = '".$_GET['formId']."'");
            $messageZZ .= $_GET['formId']." ".A_ETE_SUPPRIME." !";
            $messageZZ .= "</p>";
         } 
         else {
            unset($messageZZ);
         }
      }
}
if(isset($_GET['action']) AND $_GET['action'] == "del") {
        if(isset($_GET['resp']) AND $_GET['resp'] == "yes") {
            
             
                $toU = $mailOrder;
                $fromU = $mailOrder;
                $queryU = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password= '".$_SESSION['account']."'");
                $rowU = mysql_fetch_array($queryU);
                $emailU = $rowU['users_pro_email'];
                $sujetU = COMMANDE_SUPPRIMEE." NIC# ".$_GET['nic'];
                
                $messageU = COMMANDE_SUPPRIMEE_PAR."\r\n";
                $messageU.= "NIC nummer: ".$_GET['nic']."\r\n";
                $messageU.= COMPTE_CLIENT.": ".$_SESSION['account']."\r\n";
                $messageU.= EMAIL_CLIENT.": ".$emailU."\r\n";
                $messageU.= "----\r\n";
                $messageU.= "http://".$www2.$domaineFull."\r\n";
                
                mail($toU, $sujetU, $messageU,
                "Return-Path: $fromU\r\n"
                ."From: $fromU\r\n"
                ."Reply-To: $fromU\r\n"
                ."X-Mailer: PHP/" . phpversion());
             
            $note = COMMANDE_SUPPRIMEE_PAR.".";
            $dateNote = dateFr(date("Y-m-d H:i:s"), $_SESSION['lang']);
                $queryNote = mysql_query("SELECT users_note FROM users_orders WHERE users_nic= '".$_GET['nic']."'");
                $noteResult = mysql_fetch_array($queryNote);
                $addNote = ($noteResult['users_note']=="")? $dateNote." : ".$note : $dateNote." : ".$note."\r\n---\r\n".$noteResult['users_note']; 
             
            mysql_query("UPDATE users_orders SET users_customer_delete = 'yes', users_note = '".$addNote."' WHERE users_nic = '".$_GET['nic']."'");
             
            $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
            $messageZZ .= LA_COMMANDE." ".$_GET['nic']." ".A_ETE_SUPPRIMEE;
            $messageZZ .= "</p>";
        }
        else {
             
            $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
            $messageZZ .= ETES_SUR_DE_SUPPRIMER_COMMANDE." ".$_GET['nic']." ?";
            $messageZZ .= "<br>";
            $messageZZ .= "<b><a href='mijn_account.php?nic=".$_GET['nic']."&action=del&resp=yes'>";
            $messageZZ .= "<span>".OUI."</span>";
            $messageZZ .= "</a>";
            $messageZZ .= " | ";
            $messageZZ .= "<a href='mijn_account.php'>";
            $messageZZ .= "<span>".NON."</span>";
            $messageZZ .= "</a>";
            $messageZZ .= "</b>";
            $messageZZ .= "</p>";
        }
}

 
if(isset($_GET['act']) AND $_GET['act'] == "closeAccount") {
	unset($_SESSION['accountRemise']);
	unset($_SESSION['account']);
	unset($_SESSION['openAccount']);
    
   if(isset($_SESSION['list']) AND $_SESSION['list']!=="" AND isset($_SESSION['reduc']) AND $_SESSION['reduc']>0) {
            $split2W = explode(",",$_SESSION['list']);
            foreach ($split2W as $item2W) {
               if(isset($priceRecover)) unset($priceRecover);
               $check2W = explode("+",$item2W);
               if($check2W[3] !== "GC100") {
                   $priceRecover = sprintf("%0.2f",$check2W[2]/(1-($_SESSION['reduc']/100)));
                   $toto2W[] = $check2W[0]."+".$check2W[1]."+".$priceRecover."+".$check2W[3]."+".$check2W[4]."+".$check2W[5]."+".$check2W[6]."+".$check2W[7]."+".$check2W[8];
                   $updatePriceRecover = 1;
               }
               else {
                   $toto2W[] = $check2W[0]."+".$check2W[1]."+".$check2W[2]."+".$check2W[3]."+".$check2W[4]."+".$check2W[5]."+".$check2W[6]."+".$check2W[7]."+".$check2W[8];
               }
            }
            $_SESSION['list'] = implode(",",$toto2W);
   }
	unset($_SESSION['reduc']);
	if(isset($_SESSION['users_account_remise_note'])) unset($_SESSION['users_account_remise_note']);
	if(isset($_SESSION['accountRemiseEffec'])) unset($_SESSION['accountRemiseEffec']);
	if(isset($_SESSION['logAccountReducNum'])) unset($_SESSION['logAccountReducNum']);
	 
	if(isset($_SESSION['caddie_id'])) unset ($_SESSION['caddie_id']);
	if(isset($_SESSION['caddie_password'])) unset ($_SESSION['caddie_password']);
	$message112 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VOUS_VENEZ_DE_FERMER_VOTRE_COMPTE."</p>";
}

 
if(isset($_POST['account']) and !empty($_POST['account'])) {
   $accountRequest = mysql_query("SELECT * 
                                 FROM users_pro
   						            WHERE  users_pro_password = '".$_POST['account']."'
   						            AND users_pro_email = '".$_POST['email']."'
   						            AND users_pro_active = 'yes'");
   $accountRequestNum = mysql_num_rows($accountRequest);

   
if($accountRequestNum>0) {
    $reducResult = mysql_fetch_array($accountRequest);
   $_SESSION['openAccount'] = "yes";
   $_SESSION['account'] = $_POST['account'];
   $_SESSION['reduc'] = $reducResult['users_pro_reduc'];
    
   if(isset($_SESSION['list']) AND $_SESSION['list']!=="" AND $_SESSION['reduc']>0) {
          if(!isset($_SESSION['logAccountReducNum'])) {
            $split2q = explode(",",$_SESSION['list']);
            foreach ($split2q as $item2q) {
               $check2q = explode("+",$item2q);
               if($check2q[3] !== "GC100") {
                     $toto2q[] = $check2q[0]."+".$check2q[1]."+".newPrice($check2q[2],$_SESSION['reduc'])."+".$check2q[3]."+".$check2q[4]."+".$check2q[5]."+".$check2q[6]."+".$check2q[7]."+".$check2q[8];
               }
               else {
                     $toto2q[] = $check2q[0]."+".$check2q[1]."+".$check2q[2]."+".$check2q[3]."+".$check2q[4]."+".$check2q[5]."+".$check2q[6]."+".$check2q[7]."+".$check2q[8];
               }
            }
            $_SESSION['list'] = implode(",",$toto2q);
            $_SESSION['logAccountReducNum'] = 1;
          }
   }
}
else {
   	$message112 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".AUCUNE_COMMANDE."</p>";
}
}

 
if(isset($_GET['blg']) AND $_GET['blg']!=="") {
	$recoverOrderRequest = mysql_query("SELECT users_products, users_password FROM users_orders WHERE users_nic = '".$_GET['blg']."'");
	$recoverOrderNum = mysql_num_rows($recoverOrderRequest);
	if($recoverOrderNum>0) {
		$recoverOrder = mysql_fetch_array($recoverOrderRequest);
		$session_id = $recoverOrder["users_products"];
		$session_article = explode(",",$session_id);
		$nbre_article = count($session_article);
		if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
		if(isset($_SESSION['list'])) unset($_SESSION['list']);
		if(isset($_SESSION['caddie_id'])) unset($_SESSION['caddie_id']);
		if(isset($_SESSION['caddie_password'])) unset($_SESSION['caddie_password']);
		$_recup = recup_commande($saveCartToOneZ="non"); 
		$_SESSION['list'] = implode(",",$_recup);
		
		if(isset($_GET['c']) AND $_GET['c'] == "add") {
			$addComment = (isset($message112) AND $message112!=='')? "?comZ=1" : "";
			header("Location: mijn_account.php".$addComment);
			exit;
		}
	}
}

if(isset($_GET['a']) AND isset($_GET['b'])) {
	if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
	$verif_enr = mysql_query("SELECT users_caddie_session, users_caddie_date
								FROM users_caddie
								WHERE users_caddie_password = '".$_GET['a']."'
								AND users_caddie_number = '".$_GET['b']."'
								");
	$nodesession = mysql_fetch_array($verif_enr);
	$session_id = $nodesession["users_caddie_session"];
	$session_article = explode(",",$session_id);
	$nbre_article = count($session_article);
	
	if(isset($_SESSION['list'])) unset($_SESSION['list']);
	$_recupCart = recup_commande($saveCartToOneZ=$saveCartToOne); 
	$_SESSION['list'] = implode(",",$_recupCart);
	
	$_SESSION['caddie_id'] = $_GET['b'];
	$_SESSION['caddie_password'] = $_GET['a'];
	
	if(isset($_GET['c']) AND $_GET['c'] == "add") {
		header("Location: mijn_account.php");
		exit;
	}
}

if(isset($_POST['addCart']) AND !empty($_POST['addCart'])) {
  if(isset($_SESSION['list']) AND !empty($_SESSION['list'])) {
                          
                           $verif_password = mysql_query("SELECT *
                                                          FROM users_caddie
                                                          WHERE users_caddie_password = '".$_POST['addCart']."'
                                                          AND users_caddie_client_number = '".$_SESSION['account']."'
                                                          ");
                           $rows = mysql_num_rows($verif_password);
      if($rows > 0) {
          $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".NOM_DE_CADDIE_UTILISE."</p>";
      }
      else {
      $messageZZ = "";
      
      $str = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
      $caddie_number = '';
      for( $i = 0; $i < 6 ; $i++ ) {
          $caddie_number .= substr($str, rand(0, strlen($str) - 1), 1);
      }
      $_SESSION['caddie_id'] = $caddie_number;
      $_SESSION['caddie_password'] = $_POST['addCart'];
      $dateNowUser = date("Y-m-d");
      $clientCaddieNumber = $_SESSION['account'];
      mysql_query("INSERT INTO users_caddie 
                   (users_caddie_session, users_caddie_password, users_caddie_number, users_caddie_date, users_caddie_email, users_caddie_client_number) 
                   VALUES ('".$_SESSION['list']."', '".$_POST['addCart']."', '".$caddie_number."', '".$dateNowUser."', '','".$clientCaddieNumber."')");      
      }
  }
  else {
     $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VOTRE_CADDIE_EST_VIDE."</p>";
  }
}

if(isset($_POST['actionNow']) AND $_POST['actionNow']=="updZ") {
        $emailCheck = 1;
        $query = mysql_query("SELECT users_pro_id, users_pro_email FROM users_pro WHERE users_pro_password = '".$_POST['id']."'");
        $queryResult = mysql_fetch_array($query);
        $oldEmail = $queryResult['users_pro_email'];
        $rows = mysql_num_rows($query);
        
$intracom_array = array (
            '0'=>'AT',    
            '1'=>'BE',    
            '2'=>'DK',    
            '3'=>'FI',    
            '4'=>'FR',    
            '5'=>'DE',    
            '6'=>'EL',    
            '7'=>'IE',    
            '8'=>'IT',    
            '9'=>'LU',    
            '10'=>'NL',   
            '11'=>'PT',   
            '12'=>'ES',   
            '13'=>'SE',   
            '14'=>'GB',   
            '15'=>'CY',   
            '16'=>'EE',   
            '17'=>'HU',   
            '18'=>'LV',   
            '19'=>'LT',  
            '20'=>'MT',  
            '21'=>'PL',  
            '22'=>'SK',  
            '23'=>'CZ',  
            '24'=>'SI',  
            '25'=>'BG',  
            '26'=>'RO'); 

 
if(isset($_POST['users_pro_tva']) AND $_POST['users_pro_tva']!=='') {
    $removeFromTva = array(" ", "-", ".", ",");
    $_POST['users_pro_tva'] = str_replace($removeFromTva, "", $_POST['users_pro_tva']);
}

if(isset($_POST['users_pro_tva']) AND $_POST['users_pro_tva']!=='' AND in_array(trim(strtoupper(substr($_POST['users_pro_tva'], 0, 2))), $intracom_array) AND strlen($_POST['users_pro_tva'])>=10 AND strlen($_POST['users_pro_tva'])<15) {
    $_POST['users_pro_tva'] = $_POST['users_pro_tva'];
}
else {
    if(isset($_POST['users_pro_tva']) AND $_POST['users_pro_tva']=='') {
        $_POST['users_pro_tva']="";
    }
    else {
        $_POST['users_pro_tva']="";
        $messageTVA = NO_TVA." ".NON_VALIDE;
    }
}
                if($rows> 0) {
                   
                  $queryEmail = mysql_query("SELECT *
                                             FROM users_pro
                                             WHERE users_pro_password= '".$_SESSION['account']."'
                                             AND users_pro_email = '".$_POST['users_pro_email']."'
                                             ");
                  $queryEmailNum = mysql_num_rows($queryEmail);
                  if($queryEmailNum==0) {
                   
                  $queryEmail2 = mysql_query("SELECT *
                                             FROM users_pro
                                             WHERE users_pro_password != '".$_SESSION['account']."'
                                             AND users_pro_email = '".$_POST['users_pro_email']."'
                                             ");
                  $queryEmailNum2 = mysql_num_rows($queryEmail2);
                  if($queryEmailNum2==0) {
                             
                            $_store = str_replace("&#146;","'",$store_company);
                            $messageEmail = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                  $messageEmail .= $address_autre2."\r\n";
                              }
                              if(!empty($tel)) $messageEmail.= TELEPHONE." : ".$tel."\r\n";
                              if(!empty($fax)) $messageEmail.= FAX." : ".$fax."\r\n";
                             $messageEmail .= "URL: http://".$www2.$domaineFull."\r\nEmail : ".$mailOrder."\r\n\r\n";
                             $messageEmail .= "Op ".date("d-m-Y H:i:s")."\r\n\r\n";
                             $messageEmail .= $_POST['users_pro_firstname']." ".$_POST['users_pro_lastname'].",\r\n";
                             $messageEmail .= NOUVEAUX_ACCES." http://".$www2.$domaineFull.".\r\n";
                             $messageEmail .= "--------------------------------------------------------------------------------------------------\r\n";
                             $messageEmail .= NUMERO_DE_CLIENT." : ".$_SESSION['account']."\r\n";
                             $messageEmail .= "E-mail: ".$_POST['users_pro_email']."\r\n";
                             $messageEmail .= "--------------------------------------------------------------------------------------------------\r\n";
                             $messageEmail .= POUR_PLUS_DINFORMATIONS." ".$mailOrder.".\r\n";
                             $messageEmail .= LE_SERVICE_CLIENT;

                             $subject = "[VOTRE COMPTE ".$_SESSION['account']."] - ".$_store;
                             $to = $_POST['users_pro_email'];
                             $from = $mailInfo;
                             
                             mail($to, $subject, rep_slash($messageEmail),
                             "Return-Path: $from\r\n"
                             ."From: $from\r\n"
                             ."Reply-To: $from\r\n"
                             ."X-Mailer: PHP/" . phpversion());
                  }
                  else {
                      
                     $emailCheck = 0;
                     $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".EMAIL_ENR."</p>";
                  }
                  }
                  
                     if(isset($emailCheck) AND $emailCheck==1) {
                     if(isset($_POST['users_pro_tva']) AND $_POST['users_pro_tva']=="") $tvaConfirm = "--"; else $tvaConfirm = $_POST['users_pro_tva'];
                     if($tvaManuelValidation=="oui") {
                        $tvaValidationQueryZs = mysql_query("SELECT users_pro_tva, users_pro_tva_confirm FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
                        $tvaValidationZs = mysql_fetch_array($tvaValidationQueryZs);
                        if($tvaValidationZs['users_pro_tva']!== $_POST['users_pro_tva']) $tvaConfirm = "--"; else $tvaConfirm = $tvaValidationZs['users_pro_tva_confirm'];
                     }
                     else {
                        $tvaConfirm = "??";
                     }

                      
                     
                              mysql_query("UPDATE users_pro
                                           SET
                                           users_pro_email = '".$_POST['users_pro_email']."',
                                           users_pro_gender = '".$_POST['users_pro_gender']."',
                                           users_pro_company = '".replace_ap($_POST['users_pro_company'])."',
                                           users_pro_address = '".replace_ap($_POST['users_pro_address'])."',
                                           users_pro_city = '".replace_ap($_POST['users_pro_city'])."',
                                           users_pro_postcode = '".$_POST['users_pro_postcode']."',
                                           users_pro_country = '".replace_ap($_POST['users_pro_country'])."',
                                           users_pro_activity = '".replace_ap($_POST['users_pro_activity'])."',
                                           users_pro_telephone = '".$_POST['users_pro_telephone']."',
                                           users_pro_fax = '".$_POST['users_pro_fax']."',
                                           users_pro_tva = '".$_POST['users_pro_tva']."',
                                           users_pro_tva_confirm = '".$tvaConfirm."',
                                           users_pro_lastname = '".replace_ap($_POST['users_pro_lastname'])."',
                                           users_pro_firstname = '".replace_ap($_POST['users_pro_firstname'])."',
                                           users_pro_poste = '".replace_ap($_POST['users_pro_poste'])."'
                                           WHERE users_pro_password = '".$_POST['id']."'
                                           ");
                     
                              mysql_query("UPDATE users_orders
                                             SET
                                             users_email = '".$_POST['users_pro_email']."'
                                             WHERE users_password = '".$_SESSION['account']."'
                                          ");
                     
                              mysql_query("UPDATE newsletter
                                             SET
                                             newsletter_email = '".$_POST['users_pro_email']."'
                                             WHERE newsletter_email = '".$oldEmail."'
                                          ");
                                          
                     $messageZZ = "<p align='center' class='styleAlert'>";
                     $messageZZ.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".VOTRE_COMPTE." ".$_POST['id']." ".AETEMISAJOUR;
                     if(isset($messageTVA) AND $messageTVA!=='') $messageZZ.= "<br>-- ".$messageTVA." --";
                     $messageZZ.="</p>";
                     }
                }
                 else {
                  $messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".CECOMPTE."</p>";
                }
}

 
if(isset($_POST['action']) AND $_POST['action']=="newsLet") {
    if($_POST['newsLetter'] == 'yes') {
         
        $queryFindEmail = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
        $reaultFindEmail = mysql_fetch_array($queryFindEmail);
        $NewsEmail = $reaultFindEmail['users_pro_email'];
         
        $queryNewsletter = mysql_query("SELECT newsletter_id, newsletter_nic FROM newsletter WHERE newsletter_email='".$NewsEmail."'");
        $queryNewsletterCount = mysql_num_rows($queryNewsletter);
         
        if($queryNewsletterCount>0) {
            while($toto = mysql_fetch_array($queryNewsletter)) {
               $update1 = mysql_query("UPDATE newsletter
                               SET
                               newsletter_active='yes',
                               newsletter_statut='active'
                               WHERE newsletter_id = '".$toto['newsletter_id']."'
                            ");
               $update2 = mysql_query("UPDATE users_pro
                               SET
                               users_pro_news='yes'
                               WHERE users_pro_password = '".$_SESSION['account']."'
                            ");
            }
        }
        else {
         
                
                       $hoy = date("Y-m-d H:i:s");
                 
                       $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                       $activePassword = '';
                       for( $i = 0; $i < 7 ; $i++ ) {
                       $activePassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
                       }
                
                mysql_query("INSERT INTO newsletter
                            (newsletter_email,
                            newsletter_password,
                            newsletter_langue,
                            newsletter_active,
                            newsletter_statut,
                            newsletter_date_added,
                            newsletter_nic
                            )
                            VALUES
                            ('".$NewsEmail."',
                            '".$activePassword."',
                            '".$_SESSION['lang']."',
                            'yes',
                            'active',
                            '".$hoy."',
                            '".$_SESSION['account']."'
                            )");
                
               $update2 = mysql_query("UPDATE users_pro
                                        SET
                                        users_pro_news='yes'
                                        WHERE users_pro_password = '".$_SESSION['account']."'
                                        ");
        }
    }
    else {
    
        $queryFindEmail = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
        $reaultFindEmail = mysql_fetch_array($queryFindEmail);
        $NewsEmail = $reaultFindEmail['users_pro_email'];
        
        $queryNewsletter = mysql_query("SELECT newsletter_id, newsletter_nic FROM newsletter WHERE newsletter_email='".$NewsEmail."'");
        $queryNewsletterCount = mysql_num_rows($queryNewsletter);
        
                if($queryNewsletterCount>0) {
                    while($toto = mysql_fetch_array($queryNewsletter)) {
                       $update1 = mysql_query("UPDATE newsletter
                                       SET
                                       newsletter_active='no',
                                       newsletter_statut='out'
                                       WHERE newsletter_id = '".$toto['newsletter_id']."'
                                    ");
                       $update2 = mysql_query("UPDATE users_pro
                                       SET
                                       users_pro_news='no'
                                       WHERE users_pro_password = '".$_SESSION['account']."'
                                    ");

                    }
                }
                else {
                
                        
                               $hoy = date("Y-m-d H:i:s");
                        
                               $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                               $activePassword = '';
                               for( $i = 0; $i < 7 ; $i++ ) {
                               $activePassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
                               }
                        
                        mysql_query("INSERT INTO newsletter
                                (newsletter_email,
                                newsletter_password,
                                newsletter_langue,
                                newsletter_active,
                                newsletter_statut,
                                newsletter_date_added,
                                newsletter_nic
                                )
                                VALUES
                                ('".$NewsEmail."',
                                '".$activePassword."',
                                '".$_SESSION['lang']."',
                                'no',
                                'out',
                                '".$hoy."',
                                '".$_SESSION['account']."'
                                )");
                        
                       $update2 = mysql_query("UPDATE users_pro
                                               SET
                                               users_pro_news='no'
                                               WHERE users_pro_password = '".$_SESSION['account']."'
                                            ");
                }
}
$messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".NEWS_OK."</p>";
}

 
function cleanPassword($chaine) {
	$chaine = html_entity_decode($chaine);
	$chaine = trim($chaine);
	$chaine = strtr($chaine,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn");
	$chaine = strtr($chaine,"ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz");
	$chaine = preg_replace('#([^.a-z0-9]+)#i', '-', $chaine);
    $chaine = preg_replace('#-{2,}#','-',$chaine);
    $chaine = preg_replace('#-$#','',$chaine);
    $chaine = preg_replace('#^-#','',$chaine);
    $chaine = strtoupper($chaine);
	return $chaine;
}


if(isset($_POST['action']) AND $_POST['action']=="modifyAccount" AND $_POST['action']!=="") {
	$_POST['newPassword'] =  cleanPassword($_POST['newPassword']);
	$queryModify = mysql_query("SELECT users_pro_id FROM users_pro WHERE users_pro_password= '".$_POST['newPassword']."'");
    if(mysql_num_rows($queryModify)>0) {		
		$messageZZ = "<p align='center' class='styleAlert'>";
		$messageZZ.= "<img src='im/note.gif' align='absmiddle'><br>";
		$messageZZ.= "<b>".CE_COMPTE_EXISTE_DEJA."</b><br>";
		$messageZZ.= "<b>".VEUILLEZ_RECOMMENCER."</b>";
		$messageZZ.= "</p>";
	}
	else {
		 
		mysql_query("UPDATE users_pro
                   SET
                   users_pro_password = '".$_POST['newPassword']."'
                   WHERE users_pro_password = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		mysql_query("UPDATE users_orders
                   SET
                   users_password = '".$_POST['newPassword']."'
                   WHERE users_password = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		mysql_query("UPDATE devis
                   SET
                   devis_client = '".$_POST['newPassword']."'
                   WHERE devis_client = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		mysql_query("UPDATE affiliation
                   SET
                   aff_customer = '".$_POST['newPassword']."'
                   WHERE aff_customer = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		mysql_query("UPDATE newsletter
                   SET
                   newsletter_nic = '".$_POST['newPassword']."'
                   WHERE newsletter_nic = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		mysql_query("UPDATE users_caddie
                   SET
                   users_caddie_client_number = '".$_POST['newPassword']."'
                   WHERE users_caddie_client_number = '".$_POST['oldPassword']."'
                   ") or die (mysql_error());
		
		$queryCodePromo = mysql_query("SELECT code_promo_id, code_promo_note FROM code_promo");
		while($resultCodePromo = mysql_fetch_array($queryCodePromo)) {
			$code_promo_note = str_replace($_POST['oldPassword'],$_POST['newPassword'],$resultCodePromo['code_promo_note']);
			mysql_query("UPDATE code_promo
	                   SET
	                   code_promo_note = '".$code_promo_note."'
	                   WHERE code_promo_id = '".$resultCodePromo['code_promo_id']."'
	                   ") or die (mysql_error());
		}

		$_SESSION['openAccount'] = "yes";
		$_SESSION['account'] = $_POST['newPassword'];
		$messageZZ = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".MODIFICATION_EFFECTUEE."</p>";
	}
}

 
function displayInfos() {
GLOBAL $_SESSION, $_POST, $vb2b, $noTva, $tvaManuelValidation;
        $query = mysql_query("SELECT * FROM users_pro WHERE users_pro_password= '".$_SESSION['account']."'");
        $row = mysql_fetch_array($query);
        
      		print "<p>";
          print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
                 print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td>";
                 print "<b>".VOS_INFOS."</b><a name='mod'></a>";
                 print "</td>";
                 print "</tr>";
                 print "</table>";
          print "<p>";
?>

<form name="formz10" action="<?php print $_SERVER['PHP_SELF'];?>" method="post" onsubmit="return formzz()"; id='formH'>
<?php
?>
<input type="hidden" value="<?php print $row['users_pro_password'];?>" name="id">
<input type="hidden" value="updZ" name="actionNow">

<?php
?>
<table width="350" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLEBorderDotted">

<?php
?>
<tr>
	<td><b><?php print COMPAGNIE2;?></b></td>
	<td><input type="text" size="25" name="users_pro_company" value="<?php print $row['users_pro_company'];?>"></td>
</tr>
<?php
if($vb2b == "oui") {
?>
<tr>
	<td><b><?php print DOMAINE_ACTIVITY;?></b></td>
	<td><input type="text" size="25" name="users_pro_activity" value="<?php print $row['users_pro_activity'];?>"></td>
</tr>
<?php
}
else {
   print '<input type="hidden" name="users_pro_activity" value="">';
}
if($noTva == "oui") {
?>
<tr>
	<td><b><?php print NO_TVA;?></b></td>
	<td><input type="text" size="30" name="users_pro_tva" value="<?php print $row['users_pro_tva'];?>">
<?php 
    if($tvaManuelValidation=="oui") {
        $tvaValidationQueryZ = mysql_query("SELECT users_pro_tva, users_pro_tva_confirm FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
        $tvaValidationZ = mysql_fetch_array($tvaValidationQueryZ);
        if($tvaValidationZ['users_pro_tva_confirm']=='yes') {
            print "<img src='im/checked.gif' align='absmiddle' title='".NO_TVA." OK'>";
        }
        else {
            if($tvaValidationZ['users_pro_tva']!=='') {
                if($tvaValidationZ['users_pro_tva_confirm']=='??') print "<span style='background:#FF0000; border:#FFFF00 1px solid; color:#FFFFFF; padding:1px 2px 1px 2px;' title='".NO_TVA." ".EN_ATTENTE_DE_VALIDATION."'><b>??</b></span>";
                if($tvaValidationZ['users_pro_tva_confirm']=='no') print "<span style='background:#FF0000; border:#FFFF00 1px solid; color:#FFFFFF; padding:1px 2px 1px 2px;' title='".NO_TVA." ".NON_VALIDE."'><b>X</b></span>";
            }
        }
    }
?>
        <div class="FontGris"><i>&nbsp;(<?php print LAISSER_VIDE;?>)</i></div>
        </td>
</tr>
<?php
}
else {
   print "<input type='hidden' name='users_pro_tva' value=''>";
}
?>
<tr>
	<td><b><?php print ADRESSE;?></b></td>
	<td><input type="text" size="25" name="users_pro_address" value="<?php print $row['users_pro_address'];?>"></td>
</tr>
<?php
?>
<tr>
	<td><b><?php print CODE_POSTAL;?></b></td>
	<td><input type="text" size="25" name="users_pro_postcode" value="<?php print $row['users_pro_postcode'];?>"></td>
</tr>
<?php
?>
<tr>
	<td><b><?php print VILLE;?></b></td>
	<td><input type="text" size="25" name="users_pro_city" value="<?php print $row['users_pro_city'];?>"></td>
</tr>
<?php
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
<tr>
	<td><b><?php print PAYS;?></b></td>
	<td>
		<select name="users_pro_country">
		<option value="no">----</option>
		<?php
		while ($countries = mysql_fetch_array($pays)) {
			if($countries['countries_name'] == $row['users_pro_country']) $a = "selected"; else $a="";
			print "<option value='".$countries['countries_name']."' ".$a.">".$countries['countries_name']."</option>";
		}
		?>
		</select>
	</td>
</tr>
<?php
?>
<tr>
	<td><b><?php print NUMERO_DE_TELEPHONE;?></b></td>
	<td><input type="text" size="25" name="users_pro_telephone" value="<?php print $row['users_pro_telephone'];?>"></td>
</tr>
<?php
?>
<tr>
	<td><b><?php print FAX;?></b></td>
	<td><input type="text" size="25" name="users_pro_fax" value="<?php print $row['users_pro_fax'];?>"></td>
</tr>
<?php
?>
<tr>
	<td><b>E-mail</b></td>
	<td><input type="text" size="25" name="users_pro_email" value="<?php print $row['users_pro_email'];?>"></td>
</tr>
<?php
?>
<tr>
	<td colspan="2"><div align="center"><b>CONTACT</b></div></td>
</tr>
<?php
if($row['users_pro_gender']=="M") $selG1="selected"; else $selG1="";
if($row['users_pro_gender']=="Mme") $selG2="selected"; else $selG2="";
?>
<tr>
	<td><b><?php print CIVILITE;?></b></td>
	<td>
		<select name="users_pro_gender">
		<option value="M" <?php print $selG1;?>>M</option>
		<option value="Mme" <?php print $selG2;?>><?php print MME;?></option>
		</select>	
	</td>
	</tr>
<?php
?>
<tr>
	<td><b><?php print NOM;?></b></td>
	<td><input type="text" size="25" name="users_pro_lastname" value="<?php print $row['users_pro_lastname'];?>"></td>
</tr>
<?php
?>
<tr>
	<td><b><?php print PRENOM;?></b></td>
    <td><input type="text" size="25" name="users_pro_firstname" value="<?php print $row['users_pro_firstname'];?>"></td>
</tr>
<?php
if($vb2b == "oui") {
?>
<tr>
	<td><b><?php print POSTE;?></b></td>
	<td><input type="text" size="25" name="users_pro_poste" value="<?php print $row['users_pro_poste'];?>"></td>
</tr>
<?php
}
else {
   print '<input type="hidden" name="users_pro_poste" value="">';
}
?>
</table>
<br>
<?php
if(!isset($_SESSION['devisNumero'])) {
?>
<table border="0" cellpadding="5" cellspacing="0" align="center">
<tr>
<td align="center"><input type=Submit value="<?php print UPDATE;?>"></td>
</tr>
</table>
<?php
}
?>
</form>
<?php
}

function verif_devis() {
    GLOBAL $_SESSION;
    $retrieve_devis = mysql_query("SELECT *
                      FROM devis
                      WHERE devis_client  = '".$_SESSION['account']."'
                      AND devis_sent = 'yes'
                      ORDER BY devis_date_added
                      DESC 
                     ");
    if(mysql_num_rows($retrieve_devis) > 0) {
        print "<br>";
        print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
                print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".maj(VOS_DEVIS)."</b></td>";
                print "</tr>";
                print "</table>";
        print "<p>";
        print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr><td>";
        print "<table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLEBorderDotted'>";
        print "<tr class='TABLETopTitle'>";
        print "<td align='left'><b>".NUMERO_DEVIS."</b></td>";
        print "<td align='center'><b>".DATE."</b></td>";
        print "<td><b>".PAYE."</b></td>";
        while($devisNumber = mysql_fetch_array($retrieve_devis)) {
            $dateAddedDevis = explode(" ",$devisNumber['devis_date_added']);
            ($devisNumber['devis_traite'] == 'yes')? $traite=OUI : $traite=NON;
            print "<tr>";
            print "<td><a href='offertes/offerte_pdf.php?country=".$devisNumber['devis_country']."&id=".$devisNumber['devis_number']."' target='_blank'>".$devisNumber['devis_number']."</a></td>";
            print "<td>".$dateAddedDevis[0]."</td>";
            print "<td align='center'>".$traite."</td>";
            print "</tr>";
        }
        print "</table>";
        print "</td></tr></table>";
        print "</p>";
    }
}

function verif_shippingAddress() {
      GLOBAL $_SESSION;
     $recoverFormQuery2 = mysql_query("SELECT users_save_data_from_form, users_firstname, users_lastname, users_address, users_zip, users_city, users_country
         						            FROM users_orders
         						            WHERE users_password = '".$_SESSION['account']."'
         						            AND users_save_data_from_form !='no'
      						               ");
     $recoverFormQueryNum2 = mysql_num_rows($recoverFormQuery2);
        if($recoverFormQueryNum2>0) {
          print "<br>";
          print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='2'><tr>";
                  print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".maj(VOTRE_ADRESSE_D_EXPEDITION)."</b></td>";
                  print "</tr>";
                  print "</table>";
          print "<p>";
          print "<p>";
          print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr><td>";
          print "<table align='center' border='0' cellspacing='0' cellpadding='2' class='TABLEBorderDotted'>";
          print "<tr class='TABLETopTitle'>";
          print "<td align='center'><b>ID</b></td>";
          print "<td align='center'><b>".COMPAGNIE22."</b></td>";
          print "<td align='center'><b>".ADRESSE."</b></td>";
          print "<td align='center'>&nbsp;</td>";
          while($formNumber = mysql_fetch_array($recoverFormQuery2)) {
              print "<tr>";
              print "<td>".$formNumber['users_save_data_from_form']."</td>";
              print "<td>".$formNumber['users_lastname']." ".$formNumber['users_firstname']."</td>";
              print "<td>".$formNumber['users_address']." ".$formNumber['users_city']."<br>".$formNumber['users_zip']." ".$formNumber['users_country']."</td>";
              print "<td>&nbsp;&nbsp;<a href='mijn_account.php?formId=".$formNumber['users_save_data_from_form']."'><img src='im/supprimer2.gif' border='0' title='".SUPPRIMER." ".$formNumber['users_save_data_from_form']."' alt='".SUPPRIMER." ".$formNumber['users_save_data_from_form']."'></a></td>";
              print "</tr>";
          }
          print "</table>";
          print "</td></tr></table>";
          print "</p>";
      }
}

 
function affiliation() {
	GLOBAL $www2, $domaineFull, $store_company;
	$AffQuery = mysql_query("SELECT users_pro_aff FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'") or die (mysql_error());
	$aff = mysql_fetch_array($AffQuery);
	if($aff['users_pro_aff']=='yes') {
		$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass, aff_com FROM affiliation WHERE aff_customer = '".$_SESSION['account']."'");
		$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
		$affiliateNumber = $resultZAffFindNumber['aff_number'];
		$affiliatePass = $resultZAffFindNumber['aff_pass'];
		$_store = str_replace("&#146;","'",$store_company);
		$affiliateCom2 = $resultZAffFindNumber['aff_com'];
		
		print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
		print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".maj(AFFILIATION)."</b></td>";
		print "</tr></table>";
		print "<p>".VOUS_REMUNERE_A_HAUTEUR_DE." <b>".$affiliateCom2."%</b> ".SUR_LES_VENTES_GENEREES."</p>";
		print "<p>";
		print VOTRE_NUMERO_AFFILIE_EST.": <b>".$affiliateNumber."</b>";
		print "<br>";
		print VOTRE_PASS_EST.": <b>".$affiliatePass."</b>";
		print "<p>";
		print "&bull;&nbsp;".PLACER_UN_LIEN." ";
		print "<a href='javascript:void(0);' onClick=\"window.open('affiliate_link.php?aff=".$affiliateNumber."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=390,width=600,toolbar=no,scrollbars=no,resizable=yes');\"><b>Bekijk de link</b></a>.";
		print "<br>";
		print "&bull;&nbsp;".VOTRE_LIEN_SUR.":<br>";
		print "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber;
		print "</p>";
		print "</p>";
		
		print "<form action='affiliate_details.php' method='POST'>";
		print "<input type='hidden' name='aff_account' value='".$affiliateNumber."'>";
		print "<input type='hidden' name='aff_pass' value='".$affiliatePass."'>";
		print "<input type='submit' value='Uw affiliate account bekijken'>";
		print "</form>";
	}
}

function modifAccountNumber() {
	GLOBAL $modifCustomerNb;
	if($modifCustomerNb=="oui") {
      	print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
        print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".maj(MODIFIER_COMPTE_CLIENT)."</b></td>";
        print "</tr></table>";
        print "<p>";
		print "<div>".VOTRE_NUMERO_CLIENT_EST." <b>".$_SESSION['account']."</b></div>";
      		$emailQuery = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'");
		    $emailQueryResult = mysql_fetch_array($emailQuery);
		print "<div>".VOTRE_EMAIL_EST." <b>".$emailQueryResult['users_pro_email']."</b></div>";
		print "</p>";
		
            print "<form action=".$_SERVER['PHP_SELF']." method='POST'>";
            print "<input type='hidden' name='oldPassword' value='".$_SESSION["account"]."'>";
            print "<input type='hidden' name='action' value='modifyAccount'>";
            print NEW_NUMERO." ";
            print "<input type='text' name='newPassword' value='' size='20' maxlength='15'>&nbsp;";
            print "<input type='submit' value='Update'>&nbsp;*";
            print "<br>(*)&nbsp;".CAR_MAX."";
            print "</form>";
    }
}

$description = $title." ".$store_name;
$keywords = $title.", ".$store_name.", ".$keywords;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>

<script type="text/javascript"><!--
function check_form2() {
  var error = 0;
  var error_message = "";

  var addCart = document.form2.addCart.value;


  if(document.form2.elements['addCart'].type != "hidden") {
    if(addCart == '') {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NOM_DU_CADDIE;?>'.\n";
      error = 1;
    }
  }
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

function check_form3() {
  var error3 = 0;
  var error_message3 = "";
  var account = document.form3.account.value;
  if(document.form3.elements['account'].type != "hidden") {
    if(account == '') {
      error_message3 = error_message3 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NUMERO_DE_CLIENT;?>'.\n";
      error3 = 1;
    }
  }
  if(error3 == 1) {
    alert(error_message3);
    return false;
  } else {
    return true;
  }
}
//--></script>

<script language="javascript">
function formzz() {
<!--
  var error11 = 0;
  var error_message11 = "";
  var users_pro_tva = document.formz10.users_pro_tva.value;
  
function in_array(string, array) {
for(i = 0; i < array.length; i++) {
    if(array[i] == string) {
        return true;
    }
}
return false;
}
  if(document.formz10.elements['users_pro_tva'].type != "hidden") {
    if(users_pro_tva !== '') {
    users_pro_tva = users_pro_tva.replace(' ','');
    users_pro_tva = users_pro_tva.replace('-','');
      var intracom_array = new Array ('AT','BE','DK','FI','FR','DE','EL','IE','IT','LU','NL','PT','ES','SE','GB','CY','EE','HU','LV','LT','MT','PL','SK','CZ','SI','BG','RO');
        var chaine = users_pro_tva.toUpperCase();
        var chaine = chaine.substring(0,2);        
        if(!in_array(chaine, intracom_array) || users_pro_tva.length < 10 || users_pro_tva.length > 14 ) {
            error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NO_TVA;?>'.\n";
            error11 = 1;
        }
    }
  }
  
  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
 
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}

if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;
?>
        
      <?php if($tableDisplay=='oui') {?>
      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
      <tr height="32">
      <?php if($tableDisplayLeft=='oui') {?>
      <td>
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php"><?php print maj(HOME);?></a> | <?php print maj(IDENTIFIEZ_VOUS);?> |</b>
      </td>
      <?php 
      }
      if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
      </tr>
      </table>
      <?php }?>

      <?php include('includes/promo_afbeelden.php');?>

    </td>
  </tr>
</table>

      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // --------------------------------------
		  // linkse kolom
		  // --------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                      <td class="titre"><?php print maj(IDENTIFIEZ_VOUS);?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
<?php
}
if(isset($messageZZ)) print $messageZZ;
if(isset($_GET['comZ']) AND $_GET['comZ']==1) print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".AUCUNE_COMMANDE."</p>";

if(isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
   
     $accountRequest2 = mysql_query("SELECT users_ready, users_statut, users_refund, users_fact_num, users_id, users_nic, users_date_added, users_products_ht, users_facture_adresse, users_date_payed
                                    FROM users_orders
                                    WHERE  users_password = '".$_SESSION['account']."'
                                    AND users_confirm = 'yes'
                                    AND users_payed = 'yes'
                                    AND users_nic NOT LIKE 'TERUG%' 
                                    ORDER BY users_id
                                    DESC");
   $accountRequestNum2 = mysql_num_rows($accountRequest2);

   if($accountRequestNum2>0) {
      while($accountProp2 = mysql_fetch_array($accountRequest2)) {
		  $totalNic[] = $accountProp2['users_nic'];
		  $totalIdFact[] = str_replace("||","",$accountProp2['users_fact_num']);
		  $dateAdded[] =  dateFr($accountProp2['users_date_added'], $_SESSION['lang']);
		  $totalPurchase2[] = $accountProp2['users_products_ht'];
		  $firstnameB = $accountProp2['users_facture_adresse'];
		  $totalRefund[] = $accountProp2['users_refund'];
		  $totalSent[] = $accountProp2['users_statut'];
		  $totalReady[] = $accountProp2['users_ready'];
      }
      $TotalPurchaseSum2 = sprintf("%0.2f",array_sum($totalPurchase2));
      $pluriel = ($accountRequestNum2 > 1)? "" : "";
   }
   
   
   if($accountRequestNum2>0) {
      		$firstname = explode("|", $firstnameB);
      		print "<div align='left'>".$firstname[0].",</div>";
            
      		print "<div>".VOTRE_NUMERO_CLIENT_EST." <b>".$_SESSION['account']."</b></div>";
      		if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                print "<div>".VOTRE_REMISE_CLIENT." ".$store_name."  <b><span class='fontrouge' style='font-size:13px;'>".$_SESSION['reduc']."%</span></b></div>";
            }
            print "<div>".VOUS_AVEZ_PASSE." <b>".$accountRequestNum2."</b> ".COMMANDE.$pluriel." ".SUR_ECOM_POUR_UN_MONTANT_DE." <b>".$TotalPurchaseSum2."</b> $symbolDevise </div>";

           
          if($activerRemisePastOrder=="oui") {
               $accountRequest = mysql_query("SELECT users_account_remise_note, users_id, users_save_data_from_form, users_email,users_firstname, users_password, users_nic, users_products_ht, users_account_remise_app, users_date_added, users_date_payed
                                              FROM users_orders
                                              WHERE  users_password = '".$_SESSION['account']."'
                                              AND users_confirm = 'yes'
                                              AND users_payed = 'yes'
                                              AND users_nic NOT LIKE 'TERUG%' 
                                              AND users_refund = 'no'
                                              AND TO_DAYS(users_date_payed) >= TO_DAYS('".$dateActivationPdf."')
                                              ORDER BY users_date_payed
                                              ASC");

function extract_pastPdf() {
   GLOBAL $accountProp;
   if($accountProp['users_account_remise_note']!=="" AND $accountProp['users_account_remise_app']>0 AND preg_match("#".substr(REMISE_SUR_COMMANDES, 0, 22)."#i", $accountProp['users_account_remise_note'])) {
      if(preg_match( '!\(([^\)]+)\)!', $accountProp['users_account_remise_note'], $match))
      $toRemove = array(" ","-","%");
      $e = str_replace($toRemove,"",$match[1]);
      $e = $accountProp['users_account_remise_app']/($e/100);
   }
   else {
      $e = 0;
   }
   return $e;
}

			$accountRequestNum = mysql_num_rows($accountRequest);
			if($accountRequestNum>0) {
           		while($accountProp = mysql_fetch_array($accountRequest)) {
          			$totalPurchase[] = $accountProp['users_products_ht'];
          			
          			$totalPurchaseRemiseUsed[] = extract_pastPdf();
            	}
            	
                $totalPurchaseRemiseUsed[0]=0;
                $TotalPurchaseSum = sprintf("%0.2f",array_sum($totalPurchase));
                $TotalPurchaseSumRemiseUsed = sprintf("%0.2f",array_sum($totalPurchaseRemiseUsed));
                $plurielw = ($TotalPurchaseSum > 1)? "s" : "";
                $TotalPurchaseSumRemise = sprintf("%0.2f",$TotalPurchaseSum*$remisePastOrder/100);
                $pointRest = sprintf("%0.2f",$TotalPurchaseSum-$TotalPurchaseSumRemiseUsed);
                $remiseRest = sprintf("%0.2f",$pointRest*$remisePastOrder/100);
          	}
          	else {
	            $TotalPurchaseSum = sprintf("%0.2f",0);
	            $TotalPurchaseSumRemiseUsed = sprintf("%0.2f",0);
	            $plurielw="";
	            $TotalPurchaseSumRemise = sprintf("%0.2f",0);
	            $pointRest = sprintf("%0.2f",0);
	            $remiseRest = sprintf("%0.2f",0);
            }
            if(!isset($_SESSION['accountRemiseActive'])) $_SESSION['accountRemise'] = $remiseRest;
      		
      		print "<p>";
      		print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
            print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td>";
            print "<b>".VOTRE_REMISE."</b>";
            $openNote = "&nbsp;&nbsp;<a href='#' class='tooltip'><b><span class='darkBackground'>&nbsp;?&nbsp;</span></b><em style='width: 300px; left:30px'>".YOUR_ACCOUNT_NOTE." ". $TotalPurchaseSumRemise." ".YOUR_ACCOUNT_NOTE_2."</em></a>";
            print $openNote;
            print "</td>";
            print "</tr>";
            print "</table>";
            print "<p>";
          $dateActivationRemFr = dateFr($dateActivationPdf, $_SESSION['lang']);
          if($accountRequestNum>0) {
            print DEPUIS." ".$dateActivationRemFr.", ".VOUS_AVEZ_OBTENU." <b>".$TotalPurchaseSum."</b> ".POINTS_QUI_VOUS_DONNE_DROIT."<br>";
          }
          else {
            print DEPUIS." ".$dateActivationRemFr." <br>";
          }
          print "- ".POINTS_ACCU.": ".$TotalPurchaseSum."<br>";
          print "- ".POINTS_UT.": ".$TotalPurchaseSumRemiseUsed."<br>";
          print "- ".POINTS_REST.": <b>".$pointRest."</b><br>";
          print "<p align='center'>";
          
      		print "<table border='0' class='TABLEBorderDotted' cellspacing='0' cellpadding='3'><tr>";
            print "<td>";
            print maj("<b>".REMISE_SUR_VOTRE_PROCHAINE_COMMANDE." : <span class='fontrouge'>".$remiseRest." ".$symbolDevise."</span></b>");  
                
                if($devise2Visible=="oui" AND $remiseRest>0) {
                    $currency = sprintf("%0.2f",$remiseRest*$tauxDevise2)."&nbsp;".$symbolDevise2;
                    print " <span title='".A_TITRE_INDICATF."' class='FontGris tiny' align='right'><i>[".$currency."]</i></span>";
                }
            print "</td>";
            print "</tr>";
            print "</table>";

            print "</p>";
      		}
          
      		print "<p>";
      		
            print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
            print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td>";
            print "<b>".VOS_FACTURES."</b>";
            print "</td>";
            print "</tr>";
            print "</table>";
            
            print "<p align='center'>";
            
      		print "<table border='0' class='TABLEBorderDotted' cellspacing='0' cellpadding='4'><tr class='TABLETopTitle' height='25'>";
      		print "<td align='center'><b>".DATE."</b></td>";
            print "<td align='center'><b>NIC</b></td>";
            print "<td align='center'><b>".FACTURE."</b></td>";
            print "<td align='center'><b>".ETAT_COMMANDE."</b></td>";
            print "<td align='center'><b>".NOTE."</b></td>";
            print "<tr>";
            $totalNicNb = count($totalNic)-1;
          for($i=0; $i<=$totalNicNb; $i++) {
               $factid = $totalIdFact[$i];
               if($totalRefund[$i]=='yes') $factRefunded = '<span class="fontrouge">'.REMBOURSE.'</span>'; else $factRefunded = "--";
               
               if($totalSent[$i]=='no' AND $totalReady[$i]=='no') {
               		$totalSentD = EN_PREPARATION;
               }
               if($totalReady[$i]=='yes') {
               		$totalSentD = READY;
               }
			   if($totalSent[$i]=='yes' ) {
			   		$totalSentD = "<img src='im/cam.gif' title='".ucfirst(COMMANDE)." ".strtolower(EXP)."' alt='".ucfirst(COMMANDE)." ".strtolower(EXP)."'>";
				}

                  print "<td align='center'>".$dateAdded[$i]."</td>";
                  print "<td align='center'><a href='klantlogin/login.php?nic=".$totalNic[$i]."&l=".$_SESSION['lang']."' target='_blank'>".$totalNic[$i]."</a></td>";
                  print "<td align='center'><a href='klantlogin/factuur.php?nic=".$totalNic[$i]."&l=".$_SESSION['lang']."&target=impression' target='_blank'>".$factid."</a></td>";
                  print "<td align='center'>".$totalSentD."</td>";
                  print "<td align='center'>".$factRefunded."</td></tr>";
          }
          print "</table>";
          
          print "</p>";
          print "</p>";
         
            
            pendingOrder($accountRequestNum2);
          
            
            verif_devis();

            
            verif_shippingAddress();

      		
      		print "<p>";
      			print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
				print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td>";
				print "<b>".VOS_CADDIES."</b>";
				print "</td>";
				print "</tr>";
				print "</table>";
				$mesCaddieQuery = mysql_query("SELECT * FROM users_caddie WHERE users_caddie_client_number = '".$_SESSION['account']."'");
					$caddieRequestNum = mysql_num_rows($mesCaddieQuery);
					if($caddieRequestNum > 0) {
						print "<p>";
						print "<u>".RAPPEL."</u><br>";
	               		print "- ".EN_TANT_QUE_CLIENT."<br>";
						print "- ".CLIQUEZ_SUR_LE_CADDIE;
						if($saveCartToOne == "oui") {print "<br>- ".SET_TO_1;}
						print "<p>";
						print NBRE_DE_CADDIE.": <b>".$caddieRequestNum."</b><br>";
		                while($myCarts = mysql_fetch_array($mesCaddieQuery)) {
							print "<a href='".$_SERVER['PHP_SELF']."?var=deleteSavedCart'><img src='im/supprimer2.gif' align='absmiddle' border='0' alt='".SUPPRIMER." ".strtolower(CADDIE_ID).": ".$myCarts['users_caddie_password']."' title='".SUPPRIMER." ".strtolower(CADDIE_ID).": ".$myCarts['users_caddie_password']."'></a>";
							print " - <a href='".$_SERVER['PHP_SELF']."?a=".$myCarts['users_caddie_password']."&b=".$myCarts['users_caddie_number']."&c=add'><b>".$myCarts['users_caddie_password']."</b></a>";
							print "<br>";
		                }
	          			print "</p>";
	          			print "</p>";
      				}
		      		else {
		      		    print "<p>";
		              	print VOUS_N_AVEZ_AUCUN_CADDIE;
		              	print "</p>";
		          	}
      		print "</p>";

          
      		print "<table align='center' border='0' cellspacing='0' cellpadding='7' class='TABLEBorderDotted'>";
                 print "<form name='form2' method='post' onsubmit='return check_form2()'; action='".$_SERVER['PHP_SELF']."'>";
                 print "<tr>";
                 print "<td align='center'>";
                 print "<b>".ENREGISTRER_UN_NOUVEAU_CADDIE."</b>";
                 print "</td><tr>";
                 print "<td align='center'>".NOM_DU_CADDIE.": ";
                 print "<input type='text' name='addCart' maxlength='10' size='13' class='TABLE1'>";
                 print "</td>";
                 print "<tr>";
                 print "<td align='center'>";
                 print "<input style='BACKGROUND:none; border:0px' type='image' src='im/lang".$_SESSION['lang']."/enregistrer.gif' alt='".ENREGISTRER_CADDIE."' align='center' name='image'>";
                 print "</td>";
                 print "</tr>";
                 print "</form>";
                 print "</table>";

          
          displayInfos();

          
      	 $newsQuery = mysql_query("SELECT users_pro_news FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'");
               while($news = mysql_fetch_array($newsQuery)) {
                  if($news['users_pro_news']=='yes') $users_news = 'yes'; else $users_news = 'no';
               }
      		print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
            print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td>";
            print "<b>".maj(NEWSLETTER)."</b></td>";
            print "</tr></table>";
            print "<p>".ABONNE_NEWS."</p>";
            print '<form action='.$_SERVER['PHP_SELF'].' method="POST">';
            print '<input type="hidden" name="nc" value="'.$_SESSION['account'].'">';
            print '<input type="hidden" name="l" value="'.$_SESSION['lang'].'">';
            print '<input type="hidden" name="action" value="newsLet">';
            if($users_news == "yes") {
                    $seldYes = "checked";
                    $seldNo = "";
            } else {
                    $seldYes = "";
                    $seldNo = "checked";
            }
            print '<input style="BACKGROUND:none; border:none" type="radio" name="newsLetter" value="yes" '.$seldYes.'> '.OUI;
            print '<input style="BACKGROUND:none; border:none" type="radio" name="newsLetter" value="no" '.$seldNo.'> '.NON;
            print '&nbsp;&nbsp;&nbsp;<input type="submit" value="ok">';
            print '</form>';
            
		
		affiliation();

		
		modifAccountNumber();
			
		
		if(!isset($_SESSION['devisNumero'])) {
				if(!isset($_SESSION['recup'])) {
					$closeAccount = ($vb2c=="oui")? '' : '&var=session_destroy';
					print "<table border='0' width='100%' cellspacing='0' cellpadding='20'><tr>";
					print "<td align='center'>";
					print "<a href='mijn_account.php?act=closeAccount".$closeAccount."'>";
					print "<img src='im/lang".$_SESSION['lang']."/account_close.gif' border='0'>";
					print "</a>";
					print "</td>";
					print "</tr>";
					print "</table>";
				}
			}
	}
    else {
		$nameQuery = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'");
		$nameQueryResult = mysql_fetch_array($nameQuery);
		
		print "<div>".BIENVENU2." ".$nameQueryResult['users_pro_gender'].". ".$nameQueryResult['users_pro_lastname']." ".$nameQueryResult['users_pro_firstname'].".</div>";
		
		print "<div>".VOTRE_NUMERO_CLIENT_EST." <b>".$_SESSION['account']."</b></div>";
		if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
			print "<div>".VOTRE_REMISE_CLIENT." ".$store_name." : <b>".$nameQueryResult['users_pro_reduc']."%</b></div>";
		}

          
      	  print "<p>";
				print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
				print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".VOS_FACTURES."</b></td>";
				print "</tr>";
				print "</table>";

            
            pendingOrder($accountRequestNum2);

      		
      		print "<p>";
				print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
				print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".VOS_CADDIES."</b></td>";
				print "</tr>";
				print "</table>";
      			$mesCaddieQuery = mysql_query("SELECT * FROM users_caddie WHERE users_caddie_client_number = '".$_SESSION['account']."'");
					$caddieRequestNum = mysql_num_rows($mesCaddieQuery);
					if($caddieRequestNum > 0) {
						print "<p>";
						print "<u>".RAPPEL."</u><br>";
	          			print "- ".EN_TANT_QUE_CLIENT."<br>";
						print "- ".CLIQUEZ_SUR_LE_CADDIE;
						if($saveCartToOne == "oui") {print "<br>- ".SET_TO_1;}
						print "<p>";
						print NBRE_DE_CADDIE.": <b>".$caddieRequestNum."</b><br>";
	                	while($myCarts = mysql_fetch_array($mesCaddieQuery)) {
	                    	if(!isset($_SESSION['devisNumero'])) {
	                        	print "- <a href='".$_SERVER['PHP_SELF']."?a=".$myCarts['users_caddie_password']."&b=".$myCarts['users_caddie_number']."&c=add'><b>".$myCarts['users_caddie_password']."</b></a><br>";
	                     	}
	                     	else {
	                        	print "- <b>".$myCarts['users_caddie_password']."</b><br>";
	                     	}
	                	}
	          			print "</p>";
	          			print "</p>";
      				}
      				else {
      		    		print "<p align='center' class='fontrouge'><b>";
                		print VOUS_N_AVEZ_AUCUN_CADDIE;
                		print "</b></p>";
          			}
      		print "</p>";
            
            if(!isset($_SESSION['devisNumero'])) {
      		   print "<table align='center' border='0' cellspacing='0' cellpadding='7' class='TABLEBorderDotted'>";
                 print "<form name='form2' method='post' onsubmit='return check_form2()'; action='".$_SERVER['PHP_SELF']."'>";
                 print "<tr>";
                 print "<td align='center'>";
                 print "<b>".ENREGISTRER_UN_NOUVEAU_CADDIE."</b>";
                 print "</td><tr>";
                 print "<td align='center'>".NOM_DU_CADDIE.": ";
                 print "<input type='text' name='addCart' maxlength='10' size='13' class='TABLE1'>";
                 print "</td>";
                 print "<tr>";
                 print "<td align='center'>";
                 print "<input style='BACKGROUND:none; border:0px' type='image' src='im/lang".$_SESSION['lang']."/enregistrer.gif' alt='".ENREGISTRER_CADDIE."' align='center' name='image'>";
                 print "</td>";
                 print "</tr>";
                 print "</form>";
               print "</table>";
            }
        
        verif_devis();

        
        verif_shippingAddress();

        
        displayInfos();
        
        
      	 $newsQuery = mysql_query("SELECT users_pro_news FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'");
               while($news = mysql_fetch_array($newsQuery)) {
                  if($news['users_pro_news']=='yes') $users_news = 'yes'; else $users_news = 'no';
               }
      		print "<table border='0' width='100%' class='TABLEBottomPage' cellspacing='0' cellpadding='5'><tr>";
            print "<td width='1' valign='top'><img src='im/fleche_right_big.gif'></td><td><b>".maj(NEWSLETTER)."</b></td>";
            print "</tr></table>";
            print "<p>".ABONNE_NEWS."</p>";
            print '<form action='.$_SERVER['PHP_SELF'].' method="POST">';
            print '<input type="hidden" name="nc" value="'.$_SESSION['account'].'">';
            print '<input type="hidden" name="l" value="'.$_SESSION['lang'].'">';
            print '<input type="hidden" name="action" value="newsLet">';
            if($users_news == "yes") {
                    $seldYes = "checked";
                    $seldNo = "";
            } else {
                    $seldYes = "";
                    $seldNo = "checked";
            }
            print '<input style="BACKGROUND:none; border:none" type="radio" name="newsLetter" value="yes" '.$seldYes.'> '.OUI;
            print '<input style="BACKGROUND:none; border:none" type="radio" name="newsLetter" value="no" '.$seldNo.'> '.NON;
            print '&nbsp;&nbsp;&nbsp;<input type="submit" value="ok">';
            print '</form>';

		
		affiliation();

		
		modifAccountNumber();
		
		
		if(!isset($_SESSION['devisNumero'])) {
			if(!isset($_SESSION['recup'])) {
				$closeAccount = ($vb2c=="oui")? '' : '&var=session_destroy';
				print "<table border='0' width='100%' cellspacing='0' cellpadding='20'><tr>";
				print "<td align='center'>";
				print "<a href='mijn_account.php?act=closeAccount".$closeAccount."'>";
				print "<img src='im/lang".$_SESSION['lang']."/account_close.gif' border='0'>";
				print "</a>";
				print "</td>";
				print "</tr>";
				print "</table>";
			}
		}
    }
}
else {
    if(isset($message112)) print $message112;
    
print '<table width="100%" border="0" cellpadding="7" cellspacing="0"><tr>';
print '<td class="TABLESousMenuPageCategory">';
print '<img src="im/fleche_menu.gif" align="absmiddle">&nbsp;<b>'.IDVOUS.'</b>';
print '</td>';
print '</tr></table>';
print '<br>';
  
    print "<form action='mijn_account.php' method='POST' name='form3' onsubmit='return check_form3()' id='formH'>";
    print NUMERO_DE_CLIENT."&nbsp;&nbsp;";
    print "<input type='text' name='account' size='10'>";
    print "&nbsp;&nbsp;&nbsp;";
    print ADRESSE_EMAIL."&nbsp;&nbsp;"; 
    print "<input type='text' name='email' size='20'>&nbsp;&nbsp;<INPUT style='BACKGROUND:none; border:0px' align='absmiddle' type='image' src='im/ok.gif'>";
    print "</form>";


    print "<p>";
    print SI_VOUS_N_AVEZ_PAS2."<b><a href='javascript:void(0);' onClick=\"window.open('wachtwoord_vergeten.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=150,width=400,toolbar=no,scrollbars=no,resizable=yes');\">".ICI."</a></b>";
    print "</p>";

    print '<table width="100%" border="0" cellpadding="7" cellspacing="0"><tr>';
    print '<td class="TABLESousMenuPageCategory">';
    print '<img src="im/fleche_menu.gif">&nbsp;<b>'.maj(ENREG).'</b>';
    print '</td>';
    print '</tr></table>';
    print '<br>';

  if($vb2c == "oui") {
     include('includes/formulier_account_b2c.php');
  }
  else {
     include('includes/formulier_account_b2b.php');
  }
}
?>

                  </td>
              </tr>
            </table>

          </td>
         <?php 
		  // ---------------------------------------
		  // rechts
		  // ---------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
        </tr>
      </table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr>
</table>

</body>
</html>
