<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

 
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

// function remove-replace slash
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

// function Format date
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

$hoy = date("Y-m-d H:i:s");
$hoy2 = date("Y-M-d H:i:s");
if(!isset($_GET['jour1'])) $_GET['jour1']=1;
if(!isset($_GET['mois1'])) $_GET['mois1']=1;
if(!isset($_GET['an1'])) $_GET['an1']=2005;
if(!isset($_GET['jour2'])) $_GET['jour2']=date("d");
if(!isset($_GET['mois2'])) $_GET['mois2']=date("m");
if(!isset($_GET['an2'])) $_GET['an2']=date("Y");
if(!isset($_GET['yo'])) $_GET['yo']="all";


// door Mathias 
// update faktuurnummer - eigenlijk een copy van een stuk uit /mac/factuur_nummer.php 

 if(isset($_POST['action']) and $_POST['action']=="updateFactNum") {
  $update = mysql_query("UPDATE users_orders SET users_fact_num = '".$_POST['fact_num']."' WHERE users_nic = '".$_POST['nic']."' and users_id = '".$_POST['id'] ."'");
//	 header("Location: ".$_SERVER['PHP_SELF']	); 

	header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");


 // $upd = "UPDATE users_orders SET users_fact_num = '".$_POST['fact_num']."' WHERE users_nic = '".$_POST['nic']."' and users_id = '".$_POST['id'] ."'";
// echo "<h2>" . $upd . "</h2>" ;
}

// update delivery
//----------------
if(isset($_POST['action']) and $_POST['action']=="updateDelivery") {
	$update = mysql_query("UPDATE users_orders SET 
							users_gender = '".$_POST['users_gender']."',
							users_lastname = '".$_POST['users_lastname']."',
							users_firstname = '".$_POST['users_firstname']."',
							users_company = '".$_POST['users_company']."',
							users_address = '".$_POST['users_address']."',
							users_surburb = '".$_POST['users_surburb']."',
							users_zip = '".$_POST['users_zip']."',
							users_city = '".$_POST['users_city']."',
							users_province = '".$_POST['users_province']."',
							users_telephone = '".$_POST['users_telephone']."',
							users_fax = '".$_POST['users_fax']."'
							WHERE users_id = '".$_POST['id']."'") or die (mysql_error());
	header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

// update payment
//---------------
if(isset($_POST['action']) and $_POST['action']=="updatePayment") {
        $update = mysql_query("UPDATE users_orders SET users_payment = '".$_POST['modeDePaiement']."' WHERE users_id = '".$_POST['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

// update nic
//-----------
if(isset($_POST['action']) and $_POST['action']=="updateNic") {
        $update = mysql_query("UPDATE users_orders SET users_nic = '".$_POST['modifNic']."' WHERE users_nic = '".$_POST['oldNic']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

// Ajouter une note � l'interface de suivi client
//-----------------------------------------------
if(isset($_POST['action']) AND $_POST['action']=="addShareNote") {
        $text = str_replace("'","�",$_POST['shareNote']);
        $querySharedNote = mysql_query("SELECT users_share_note FROM users_orders WHERE users_id= '".$_POST['id']."'");
        $sharedNoteResult = mysql_fetch_array($querySharedNote);
        $sharedNoteOld = $sharedNoteResult['users_share_note'];
        $update = mysql_query("UPDATE users_orders SET users_share_note = '' WHERE users_id = '".$_POST['id']."'");
        $update = mysql_query("UPDATE users_orders SET users_share_note = '".$text."' WHERE users_id = '".$_POST['id']."'");
        // Envoyer note au client par email
        if($_POST['shareNote']!=="" AND $_POST['shareNote']!==$sharedNoteOld) {
          $tox11 = $_POST['emailNote'];
          $fromx11 = $mailOrder;
          $subjectx11 = "[".VOTRE_COMMANDE." ".strtoupper($_POST['nicNote'])."] - ".strtoupper($domaineFull);
          $_1 = array("&#146;", "'", "<br>");
          $_2   = array("�", "�", "\r\n");
          $_store = str_replace($_1, $_2, $store_company);
          $messageToSendx = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
          if(!empty($address_autre)) {
              $address_autre2 = str_replace($_1, $_2, $address_autre);
              $messageToSendx.= $address_autre2."\r\n";
          }
          if(!empty($tel)) $messageToSendx.= TELEPHONE.": ".$tel."\r\n";
          if(!empty($fax)) $messageToSendx.= FAX.": ".$fax."\r\n";
          $messageToSendx.= "URL: http://".$www2.$domaineFull."\r\n";
          $messageToSendx.= "E-mail: ".$mailOrder."\r\n\r\n";
          $messageToSendx.= BONJOUR.",\r\n\r\n";
          $messageToSendx.= UN_MESSAGE_A."\r\n";
          $messageToSendx.= "--------------------------------------------------------------------------------------------------\r\n";
          $messageToSendx.= UR_INTERF.": ".$urlAdminClient."\r\n";
          $messageToSendx.= NIC_NUM.": ".$_POST['nicNote']."\r\n";
          $messageToSendx.= "--------------------------------------------------------------------------------------------------\r\n";
          $messageToSendx.= LE_SERVICE_CLIENT."\r\n";
          $messageToSendx.= $mailOrder;
          
          mail($tox11, $subjectx11, rep_slash($messageToSendx),
               "Return-Path: $fromx11\r\n"
               ."From: $fromx11\r\n"
               ."Reply-To: $fromx11\r\n"
               ."X-Mailer: PHP/" . phpversion());
         }
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

if(isset($_POST['action']) and $_POST['action']=="addnote") {
        $text = str_replace("'","�",$_POST['note']);
        $update = mysql_query("UPDATE users_orders SET users_note = '' WHERE users_id = '".$_POST['id']."'");
        $update = mysql_query("UPDATE users_orders SET users_note = '".$text."' WHERE users_id = '".$_POST['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

// Update commande expediee
//-------------------------
if(isset($_GET['action']) AND $_GET['action']=="update") {
		 $queryExped = mysql_query("SELECT users_payed, users_fact_num, users_nic, users_note FROM users_orders WHERE users_id = '".$_GET['id']."'");
		 $expedResult = mysql_fetch_array($queryExped);
		 $addNoteOnReceipt = ($_GET['processed']=="yes")? "\r\n".COMMANDE_EXPEDIEE_LE." ".dateFr($hoy, $_SESSION['lang']).".\r\n".PAIEMENT_SUR_RECEPTION."\r\n---\r\n".$expedResult['users_note'] : $expedResult['users_note'];
		 $addNoteI = ($_GET['processed']=="yes")? "\r\n".COMMANDE_EXPEDIEE_LE." ".dateFr($hoy, $_SESSION['lang']).".\r\n---\r\n".$expedResult['users_note'] : $expedResult['users_note'];
		 if($expedResult['users_payed']=='no' AND $expedResult['users_fact_num']=='' AND $_GET['processed']=='yes') {
			$invMessage = "<p align='center' style='background:#888888; border:#FFFF00 2px solid; padding:10px'>";
			$invMessage.= "<span style='color:#FFFF00; font-size:15px'>".GENERER_FACTURE."</span>";
			$invMessage.= "<br><br>";
			$invMessage.= "<a href='detail.php?action=update&fact=yes&processed=".$_GET['processed']."&id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."' style='text-decoration:none'><span style='color:#FFFF00; font-size:13px'><b>".strtoupper(A30)."</b></span></a>";
			$invMessage.= " | ";
			$invMessage.= "<a href='detail.php?action=update&fact=no&&processed=".$_GET['processed']."&id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."' style='text-decoration:none'><span style='color:#FFFF00; font-size:13px'><b>".strtoupper(A31)."</b></span></a>";
			$invMessage.= " | ";
			$invMessage.= "<a href='detail.php?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."' style='text-decoration:none'><span style='color:#FFFF00; font-size:13px'><b>".strtoupper(CANCEL)."</b></span></a>";
			$invMessage.= "</p>";
		 }
		 else {
         	$update = mysql_query("UPDATE users_orders SET users_statut='".$_GET['processed']."', users_ready='yes', users_note='".$addNoteI."' WHERE users_id='".$_GET['id']."'");
         	header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
		 }
		 if(isset($_GET['fact']) AND $_GET['fact']=='yes') {
			// G�n�rer num�ro de facture
			include('../includes/factuur_nummer_maken.php');
			invoice_number_generator($expedResult['users_fact_num'], $expedResult['users_nic']);
         	$update = mysql_query("UPDATE users_orders SET users_statut='".$_GET['processed']."', users_ready='yes', users_confirm='yes', users_date_payed='".$hoy."', users_note='".$addNoteOnReceipt."' WHERE users_id='".$_GET['id']."'") or die (mysql_error());
         	header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
		 }
		 if(isset($_GET['fact']) AND $_GET['fact']=='no') {
         	$update = mysql_query("UPDATE users_orders SET users_statut='".$_GET['processed']."', users_ready='yes', users_note='".$addNoteI."' WHERE users_id='".$_GET['id']."'") or die (mysql_error());
         	header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
		 }
}
// Update commande pr�te
//----------------------
if(isset($_GET['action']) AND $_GET['action']=="updateReady") {
         $update = mysql_query("UPDATE users_orders SET users_ready = '".$_GET['processedReady']."' WHERE users_id = '".$_GET['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
}
// Update mode de paiement confirme
//---------------------------------
if(isset($_GET['action']) AND $_GET['action']=="updateConfirm") {
         $update = mysql_query("UPDATE users_orders SET users_confirm = '".$_GET['paymentConfirmed']."' WHERE users_id = '".$_GET['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
}
if(isset($_POST['action']) AND $_POST['action']=="remb") {
         $update = mysql_query("UPDATE users_orders SET users_comment='', users_refund = '".$_POST['remboursee']."' WHERE users_id = '".$_POST['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}
if(isset($_GET['action']) AND $_GET['action']=="litige") {
         $update = mysql_query("UPDATE users_orders SET users_litige = '".$_GET['litige']."' WHERE users_id = '".$_GET['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
}
if(isset($_POST['action']) AND $_POST['action']=="addnoteLitige") {
        $text2 = str_replace("'","�",$_POST['noteLitige']);
        $update = mysql_query("UPDATE users_orders SET users_litige_comment = '".$text2."' WHERE users_id = '".$_POST['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}
if(isset($_POST['action']) AND $_POST['action']=="recupereCommande") {
                $note = COMMANDE_RECUP;
                $dateNote = dateFr(date("Y-m-d H:i:s"), $_SESSION['lang']);
                $queryNote = mysql_query("SELECT users_note FROM users_orders WHERE users_id= '".$_POST['id']."'");
                $noteResult = mysql_fetch_array($queryNote);
                $addNote = ($noteResult['users_note']=="")? $dateNote." : ".$note : $dateNote." : ".$note."\r\n---\r\n".$noteResult['users_note']; 
        $update = mysql_query("UPDATE users_orders SET users_customer_delete = 'no', users_note = '".$addNote."' WHERE users_id = '".$_POST['id']."'");
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
}

/*------------------------------
Confirmation paiement par ch�que
------------------------------*/
if(isset($_GET['action']) and $_GET['action']=="updatePayed") {

						if($_GET['processedPayment'] == "yes") {
                        //--------------
                        // Update order 
                        $update = mysql_query("UPDATE users_orders SET users_payed = 'yes', users_date_payed = '".$hoy."' WHERE users_id = '".$_GET['id']."'");
					    //---------------------
                        // Mise � jour Stock -1
					    $queryUp = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
						$rowUp = mysql_fetch_array($queryUp);
                        //-------------
                        // update devis
                        if(!empty($rowUp['users_devis'])) {
                            mysql_query("UPDATE devis SET devis_traite = 'yes', devis_client='".$rowUp['users_password']."' WHERE devis_number = '".$rowUp['users_devis']."'");
                        }
                        //--------------------------
						// G�n�rer num�ro de facture
                        	include('../includes/factuur_nummer_maken.php');
                        	invoice_number_generator($rowUp['users_fact_num'], $rowUp['users_nic']);
                        //-------------
                        // update stock
                        
                        ##	24+1+1959.00+tosh183034+Toshiba Satellite 1410-604+19.60+2.5 GHz|30 Go|16.1 pouces|460epz+0.50+0|0|0,
						##	24+1+1829.00+tosh183030+Toshiba Satellite 1410-604+19.60+2 GHz|80 Go|15 pouces|330epz+0.50+0|0|0
						
								$splitUp = explode(",",$rowUp['users_products']);
								foreach($splitUp as $item) {
					         		$check = explode("+",$item);
					         		if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contr�le cheque cadeau dans la commande
					         		
					         		$query = mysql_query("SELECT products_id, products_qt, products_sold
					                               		  FROM products
					                                      WHERE products_id = '".$check[0]."'");
					         	 	$row = mysql_fetch_array($query);
						         	if($check[1]!=="0") {
						         		   $stockUpdateQt = $row['products_qt']-$check[1];
						         		   $bbb = $row['products_sold'] + $check[1];
						                   $updateStock = mysql_query("UPDATE products
						                               SET
						                               products_qt = ".$stockUpdateQt.",
						                               products_sold = ".$bbb."
						                               WHERE products_id = '".$row['products_id']."'");
						         	}
						         	
						         	// Update stock options
									if(isset($check[6]) AND !empty($check[6])) {
										$_opt = explode("|",$check[6]);
						         		$lastArray = $_opt[count($_opt)-1];
						         		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
						         			$out = array_pop($_opt);
						         			$addReq = implode(' | ', $_opt);
						         		}
										if(isset($addReq) AND $addReq!=='') {
						         			$queryOpt = mysql_query("SELECT products_options_stock_stock FROM products_options_stock 
											 							WHERE products_options_stock_prod_id = '".$check[0]."'
																		AND products_options_stock_prod_name = '".$addReq."'");
						         			if(mysql_num_rows($queryOpt)>0) {
												$queryOptResult = mysql_fetch_array($queryOpt);
						         				$stockOptUpdate = $queryOptResult['products_options_stock_stock'] - $check[1];
												mysql_query("UPDATE products_options_stock
							                    				SET
																products_options_stock_stock = ".$stockOptUpdate."
																WHERE products_options_stock_prod_id = '".$check[0]."' 
																AND products_options_stock_prod_name = '".$addReq."'
																");
											}
										}
						         	}
								}
								
							    //////////////////////////
					          	// AFFILIATION AUTOMATIQUE
							    //////////////////////////
								if($affiliateAuto=='oui') {
									## Chercher compte client
									$queryZAff = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$rowUp['users_password']."'");
									$queryZAffNum = mysql_num_rows($queryZAff);
									
										if($queryZAffNum>0) {
											$resultZAff = mysql_fetch_array($queryZAff);
											if($resultZAff['users_pro_aff']=='no') {
												// Generation alleatoire du Numero d'affili�
												$str1a = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789ABCDEF';
												$affiliateNumber = '';
												for( $i=0; $i<10; $i++ ) {
													$affiliateNumber.= substr($str1a, rand(0, strlen($str1a) - 1), 1);
												}
												// Generation alleatoire du mot de passe
												$str1a = '123456789';
												$affiliatePass = '';
												for( $i=0; $i<6; $i++ ) {
													$affiliatePass.= substr($str1a, rand(0, strlen($str1a) - 1), 1);
												}
												// Ajouter affili� dans la table affiliation
												mysql_query("INSERT INTO affiliation (
												              aff_number,
												              aff_company,
												              aff_nom,
												              aff_prenom,
												              aff_email,
												              aff_adresse1,
												              aff_zip,
												              aff_ville,
												              aff_pays,
												              aff_telephone,
												              aff_pass,
												              aff_com,
												              aff_customer
												              )
												            VALUES ('".$affiliateNumber."',
												                    '".$resultZAff['users_pro_company']."',
												                    '".$resultZAff['users_pro_lastname']."',
												                    '".$resultZAff['users_pro_firstname']."',
												                    '".$resultZAff['users_pro_email']."',
												                    '".$resultZAff['users_pro_address']."',
												                    '".$resultZAff['users_pro_postcode']."',
												                    '".$resultZAff['users_pro_city']."',
												                    '".$resultZAff['users_pro_country']."',
												                    '".$resultZAff['users_pro_telephone']."',
												                    '".$affiliatePass."',
												                    '".$affiliateCom."',
												                    '".$rowUp['users_password']."'
												                    )");
												// Mettre � jour le compte client
												mysql_query("UPDATE users_pro SET users_pro_aff = 'yes' WHERE users_pro_password = '".$rowUp['users_password']."'");
											}
											else {
												$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass FROM affiliation WHERE aff_customer = '".$rowUp['users_password']."'");
												$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
												$affiliateNumber = $resultZAffFindNumber['aff_number'];
												$affiliatePass = $resultZAffFindNumber['aff_pass'];
											}
										}
								}
								
							    ////////////////////////
					          	// Envoyer Ch�que cadeau
							    ////////////////////////
					            $arrGc = array_sum($gc);
					            if($arrGc> 0) {
					                    //------------
					                    // Aujourd'hui
					                    $dateNow = date("Y-m-d H:i:s");
					                    
                                        // Aujourd'hui + 1 an
                                        $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
                                        //---------------------------------------------------
                                        // Mettre a jour  le Ch�que cadeau dans la bdd : Pay�
                                        mysql_query("UPDATE gc 
                                                    SET 
                                                    gc_payed = 1,
                                                    gc_start = '".$dateNow."',
                                                    gc_end = '".$nextYear."'
                                                    WHERE gc_nic = '".$rowUp['users_nic']."'");
                                        //---------------------
                                        // select cheque cadeau
                                        $queryUpGc = mysql_query("SELECT * FROM gc WHERE gc_nic = '".$rowUp['users_nic']."'");
                                        $rowUpGc = mysql_fetch_array($queryUpGc);
                                        //---------------------------
                                        // Envoyer mail cheque cadeau
                                        $domMaj11 = strtoupper($domaineFull);
                                        $to11 =  $rowUp['users_email'];
                                        $from11 = $mailOrder;
                                        $subject11 = "[".CHEQUE_CADEAU."] - ".$domMaj11;
                                        
                                        $_store = str_replace("&#146;","�",$store_company);
                                        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                                        if(!empty($address_autre)) {
                                            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                            $messageToSend .= $address_autre2."\r\n";
                                        }
                                        if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
                                        if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
                                        $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                                        $messageToSend .= "Datum: ".$hoy2."\r\n\r\n";
                                        $messageToSend .= SUJET.": ".CHEQUE_CADEAU."\r\n";
                                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                                        $messageToSend .= VOUS_AVEZ_ACHETEEEE." ".$_store."\r\n";
                                        $messageToSend .= $_store." ".VOUS_REMERCIE."\r\n";
                                        $messageToSend .= CI_DESSOUS."\r\n";
                                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                                        $messageToSend .= NUMERO_CHEQUE.": ".$rowUpGc['gc_number']."\r\n";
                                        $messageToSend .= MONTANT_DE.": ".sprintf("%0.2f",$rowUpGc['gc_amount'])." ".$devise."s\r\n";
                                        $messageToSend .= URL_DE.": http://".$www2.$domaineFull."/geschenkbon/index.php?l=".$rowUp['users_lang']."\r\n";
                                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                                        $messageToSend .= POUR_PLUS_DINFORMATIONS." ".$mailOrder.".\r\n";
                                        $messageToSend .= LE_SERVICE_CLIENT;
                        				
                                        mail($to11, $subject11, rep_slash($messageToSend),
                                             "Return-Path: $from11\r\n"
                                             ."From: $from11\r\n"
                                             ."Reply-To: $from11\r\n"
                                             ."X-Mailer: PHP/" . phpversion());
                                }
                            //--------------
                            // update gc bdd
                            if($rowUp['users_remise_gc'] > 0) {
                            	mysql_query("UPDATE gc SET gc_enter=1, gc_nic2='".$rowUp['users_nic']."' WHERE gc_number = '".$rowUp['users_gc']."'");
                            }
						}
						
						
						if($_GET['processedPayment'] == "no") {
                        	$update = mysql_query("UPDATE users_orders SET 
                                                    users_payed = 'no',
                                                    users_date_payed = '0000-00-00 00:00:00'
                                                    WHERE users_id = '".$_GET['id']."'");
					        // Mise � jour Stock +1
					        $queryUp = mysql_query("SELECT users_products, users_devis, users_nic
								                    FROM users_orders
								                    WHERE users_id='".$_GET['id']."'");
					        $rowUp = mysql_fetch_array($queryUp);
                        	// update devis
                        	if(!empty($rowUp['users_devis'])) {
                           		mysql_query("UPDATE devis SET devis_traite = 'no', devis_client='' WHERE devis_number = '".$rowUp['users_devis']."'");
                        	}
                        	// Update stock
								$splitUp = explode(",",$rowUp['users_products']);
								foreach ($splitUp as $item) {
					         		$check = explode("+",$item);
					         		if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contr�le cheque cadeau dans la commande
					         		$query = mysql_query("SELECT products_id, products_qt, products_sold
					                               		  FROM products
					                                      WHERE products_id = '".$check[0]."'");
					         	 	$row = mysql_fetch_array($query);
						         	if($check[1]!=="0") {
						         		$stockUpdateQt = $row['products_qt'] + $check[1];
						         		$ProductsUpdateSold = $row['products_sold'] - $check[1];
						                $updateStock = mysql_query("UPDATE products
						                               				SET
						                               				products_qt = ".$stockUpdateQt.",
						                               				products_sold = ".$ProductsUpdateSold."
						                               				WHERE products_id = '".$row['products_id']."'");
						         	}
						         	
						         	// Update stock options
									if(isset($check[6]) AND !empty($check[6])) {
										$_opt = explode("|",$check[6]);
						         		$lastArray = $_opt[count($_opt)-1];
						         		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
						         			$out = array_pop($_opt);
						         			$addReq = implode(' | ', $_opt);
						         		}
										if(isset($addReq) AND $addReq!=='') {
						         			$queryOpt = mysql_query("SELECT products_options_stock_stock FROM products_options_stock 
											 							WHERE products_options_stock_prod_id = '".$check[0]."'
																		AND products_options_stock_prod_name = '".$addReq."'");
						         			if(mysql_num_rows($queryOpt)>0) {
												$queryOptResult = mysql_fetch_array($queryOpt);
						         				$stockOptUpdate = $queryOptResult['products_options_stock_stock'] + $check[1];
												mysql_query("UPDATE products_options_stock
							                    				SET
																products_options_stock_stock = ".$stockOptUpdate."
																WHERE products_options_stock_prod_id = '".$check[0]."' 
																AND products_options_stock_prod_name = '".$addReq."'
																") or die (mysql_error());
											}
										}
						         	}
								}
					        // Update Ch�que cadeau
					        $arrGc = array_sum($gc);
					        if($arrGc> 0) {
					        	// Ch�que cadeau: Non Pay�
                            	mysql_query("UPDATE gc SET gc_payed = 0 WHERE gc_nic = '".$rowUp['users_nic']."'");
					        }
						}
         header("Location: ".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."");
}

/*----------------------------------
Fin confirmation paiement par ch�que
----------------------------------*/

if(empty($_GET['id'])) {
   print "<html>";
           print "<head>";
           print "<title></title>";
           print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
           print "<link rel='stylesheet' href='style.css'>";
           print "</head>";
           print "<body leftmargin='0' topmargin='30' marginwidth='0' marginheight='0'>";
           print "<p align='center' class='largeBold'>".A1."</p>";
           print "<p align='center'>".A2;
                               print "<br><br>";
                               print "<span style='color:red'>".A3."</span>";
                               print "<br>";
                               print "<p align='center'><a href='zoeken.php'><b>".A4."</b></a></p>";
          print "</p>";
          print "</body>";
          print "</html>";
          exit;
}
else {
      $query = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
      $hh = mysql_num_rows($query);
      if($hh == 0) header("Location:".$_SERVER['PHP_SELF']."");
      $row = mysql_fetch_array($query);
      $adress2 = explode("|",$row['users_facture_adresse']);
      $date = explode(" ", $row['users_date_added']);
      $datePayed = explode(" ", $row['users_date_payed']);
      // Litiges
      if($row['users_litige']=='yes') {
         $col="#FF9900";
         $col2="#FF9900";
         $messageZ = COMMANDE_EN_LITIGE;
      }

      // Commande supprim�e par le client via le compte client
      if($row['users_customer_delete']=='yes') {
         ($row['users_litige']=='yes')? $col="#FF9900" : $col="#FFFF00";
         $col2="#CCFF00";
         $messageZ = COMMANDE_SUPPRIME_VIA;
      }

if(!isset($col)) $col="#FFFF00";
if(!isset($col2)) $col="#FFFF00";
      
$pays = mysql_query("SELECT * FROM countries WHERE countries_name = '".$row['users_country']."'");
$p = mysql_fetch_array($pays);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if(isset($invMessage) AND $invMessage!=="") print $invMessage;
?>
<p align="center" class="largeBold">
<?php 
print A5." ".str_replace("||","",$row['users_nic']);
if($row['users_payed']=="yes") print " - ".FACT.": ".str_replace("||","",$row['users_fact_num']);
?>
</p>

<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr>
<td align="center">

&bull;&nbsp;<a href="<?php print "factuur_scherm.php?id=".$_GET['id']."&target=impression";?>" target="_blank"><?php print A6;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
&bull;&nbsp;<a href="<?php print "factuur_scherm.php?id=".$_GET['id']."&target=mail";?>" target="_blank"><?php print A7;?></a><br><br>
&bull;&nbsp;<a href="<?php print "factuur_scherm.php?id=".$_GET['id']."&target=alert";?>" target="_blank"><b><?php print A8;?></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
&bull;&nbsp;<a href="<?php print "factuur_scherm.php?id=".$_GET['id']."&target=alertShipping";?>" target="_blank"><b><?php print AVERTISSEMENT_LIVRAISON;?></b></a><br><img src='im/zzz.gif' width='1' height='5'><br><br>

&bull;&nbsp;<a href="<?php print "factuur_scherm.php?id=".$_GET['id']."&target=deliveryOrder";?>" target="_blank"><b><?php print BON_LIVRAISON;?></b></a>&nbsp;&nbsp;|&nbsp;&nbsp;
&bull;&nbsp;<a href="<?php print "x_exporteer_verzendadres.php?nic=".$row['users_nic']."";?>" target="_blank"><?php print EXPORT;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<?php
if($row['users_confirm']=='no' OR $row['users_payed']=='no') {
	print "&bull;&nbsp;<a href='factuur_scherm.php?id=".$_GET['id']."&target=rappel' target='_blank'>".EMAIL_NOT_PAYED."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
}
?>
&bull;&nbsp;<a href="<?php print "x_exporteer_bestelling_alles.php?nic=".$row['users_nic']."";?>" target="_blank"><?php print "Exporteer bestelling [CSV]";?></a><br><img src='im/zzz.gif' width='1' height='5'><br>

<table align="center" border="0" cellpadding="0" cellspacing="5">
	<tr>
    	<td><a href="factuur_pdf.php?id=<?php print $row['users_nic'];?>" target="_blank"><img src="im/logo_pdf.gif" border="0" title="Factuur PDF"></a></td>
    	<td><a href="bl_pdf.php?id=<?php print $row['users_nic'];?>" target="_blank"><img src="im/logo_pdf.gif" border="0" title="Leverings bon PDF"></a></td>
	</tr>
</table>

</td>
</tr>
</table>

<br>

<table align="center" width="700" border="0" cellpadding="4" cellspacing="0" class="TABLE">
<?php
// Remoursement - Envoyer un code de reduction
if(substr($row['users_nic'], 0, 5)!=="TERUG") {
	print "<tr bgcolor='#FFFFFF' height='30'>";
	print "<td colspan='2' style='padding:7px;'>";
	// Afficher facture d'avoir si facture remboursee
	if($row['users_refund']=='yes') {
		$queryfactR1 = mysql_query("SELECT users_id, users_nic FROM users_orders WHERE users_nic='TERUG-".$row['users_fact_num']."'") or die (mysql_error());
		if(mysql_num_rows($queryfactR1)>0) {
			$resultFactR1 = mysql_fetch_array($queryfactR1);
			print "<div align='left'>&bull;&nbsp;<a href='detail.php?id=".$resultFactR1['users_id']."'>".VOIR_COMMANDE_AVOIR." n&deg; ".$resultFactR1['users_nic']."</a></div>";
		}
	}
	// Afficher Rembourser cette commande
	if($row['users_payed']=='yes' AND $row['users_refund']=='no') print "<div align='left'>&bull;&nbsp;<a href='terugbetaling_detail.php?id=".$row['users_id']."'>".REMBOURSER."</a></div>";
	// Afficher Envoyer un code de r�duction|bon d'achat
	print "<div align='left'>&bull;&nbsp;<a href='korting_code_versturen.php?usersEmail=".$row['users_email']."'>".ENVOYER_CODE."</a></div>";
	// Afficher R�cup�rer et Modifier cette commande
	if($row['users_payed']=='no' AND $row['users_refund']=='no' AND substr($row['users_nic'], 0, 5)!=="TERUG" AND $row['users_devis']=='') print "<div align='left'>&bull;&nbsp;<a href='../mijn_account.php?emailRec=".$row['users_email']."&accountRec=".$row['users_password']."&blg=".$row['users_nic']."&c=add&var=session_destroy' target='_blank'>".RECUP_COMMANDE_PANIER."</a></div>";
	// Afficher Ajouter une commande
	print "<div align='left'>&bull;&nbsp;<a href='../mijn_account.php?accountRec1=".$row['users_password']."&emailRec1=".$row['users_email']."&addOrder=1&var=session_destroy' target='_blank'>".ADD_ORDER."</a></div>";	
	print "</td></tr>";
}
else {
	// Voir commande rembours�e
	$factRefunded = explode("-",$row['users_nic']);
	$queryfactR = mysql_query("SELECT users_id FROM users_orders WHERE users_fact_num='".$factRefunded[1]."'") or die (mysql_error());
	if(mysql_num_rows($queryfactR)>0) {
		$resultFactR = mysql_fetch_array($queryfactR);
		print "<tr bgcolor='#FFFFFF' height='30'>";
		print "<td colspan='2' style='padding:7px;'>";
		print "<div align='left'>&bull;&nbsp;<a href='detail.php?id=".$resultFactR['users_id']."'>".VOIR_COMMANDE_REFUNDED." n&deg;".$factRefunded[1]."</a></div>";
		print "</td></tr>";
	}
}

// Commandes en litige - Commandes supprim�e
if(isset($messageZ)) {
	print "<tr>";
	print "<td colspan='2' align='center' style='background:".$col2."; padding:8px; font-size:13px;'><b>".$messageZ."</b></td>";
	print "</tr>";
}

// Commande avoir
if(substr($row['users_nic'], 0, 5) == "TERUG") {
	print "<tr>";
	print "<td colspan='2' align='center' style='background:#000000; padding:8px; color:#FFFFFF; font-size:13px;'><b>".COMMANDE_AVOIR."</b></td>";
	print "</tr>";
}

// Commande rembours�e
if($row['users_refund']=="yes") {
	print "<tr>";
	print "<td colspan='2' align='center' style='background:#000000; padding:8px; color:#FFFFFF; font-size:13px;'><b>".COMMANDE_REMBOURSE."</b></td>";
	print "</tr>";
}

// Recuperer commande supprime par le client
if($row['users_customer_delete']=='yes') {
	print "<tr>";
	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	print "<td colspan='2' align='center' style='background:".$col2.";'>";
		print "<input type='hidden' name='id' value='".$row['users_id']."'>";
		print "<input type='hidden' name='yo' value='".$_GET['yo']."'>";
		print "<input type='hidden' name='jour1' value='".$_GET['jour1']."'>";
		print "<input type='hidden' name='mois1' value='".$_GET['mois1']."'>";
		print "<input type='hidden' name='an1' value='".$_GET['an1']."'>";
		print "<input type='hidden' name='jour2' value='".$_GET['jour2']."'>";
		print "<input type='hidden' name='mois2' value='".$_GET['mois2']."'>";
		print "<input type='hidden' name='an2' value='".$_GET['an2']."'>";
		print "<input type='hidden' name='action' value='recupereCommande'>";
		print "<input type='submit' value='".RETABLIR_COMMANDE."' name='submit'>";
		print "<br><img src='im/zzz.gif' width='1' height='10'><br>";
	print "</td>";
	print "</form>";
	print "</tr>";
}
?>
<tr>
        <td width="200">ID</td>
        <td><b><?php print "<span style='color:red'>".$row['users_id']."</span>"; ?></b></td>
</tr>
<tr>
        <td width="200"><?php print A9;?></td>
        <td><b><?php print dateFr($row['users_date_added'],$_SESSION['lang'])." ".$date[1];?></b></td>
</tr>
<tr>
        <td width="200"><?php print A9A;?></td>
        <td><b><?php ($datePayed[0]=="0000-00-00")? print "--" : print dateFr($row['users_date_payed'],$_SESSION['lang']);?></b></td>
</tr>













<tr>
        <td colspan="2"><img src="im/zzz.gif" width="1" height="1"></td>
</tr>
<tr>
<td width="200" valign="top">
<?php print ADRESSE_LIVRAISON;?>
</td>
<td>

<div style='background:#EAFDD7; border:1px #CCCCCC dotted; padding:7px; margin-right:10px;'>
<table border="0" cellpadding="5" cellspacing="0">
<tr bgcolor="#EAFDD7">
<form method="POST" action="detail.php">
<input type="hidden" name="action" value="updateDelivery">
<input type="hidden" name="id" value="<?php print $row['users_id'];?>">
<input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
<input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
<input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
<input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
<input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
<input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
<input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
        <td><?php print A10;?></td>
        <td>
        <?php 
		if($row['users_gender']=="M") $selc1="selected"; else $selc1="";
		if($row['users_gender']=="Mme") $selc2="selected"; else $selc2="";
		?>
		<select name="users_gender">
		<option value="M" <?php print $selc1;?>>Mr</option>
		<option value="Mme" <?php print $selc2;?>>Mevr</option>
		</select>
		</td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A11;?></td>
        <td><input type="text" size="30" class="vullen" name="users_lastname" value="<?php print $row['users_lastname'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A12;?></td>
        <td><input type="text" size="30" class="vullen" name="users_firstname" value="<?php print $row['users_firstname'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A5010;?></td>
        <td><input type="text" size="30" class="vullen" name="users_company" value="<?php print $row['users_company'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A13;?></td>
        <td><input type="text" size="30" class="vullen" name="users_address" value="<?php print $row['users_address'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td>&nbsp;</td>
        <td><input type="text" size="30" class="vullen" name="users_surburb" value="<?php print $row['users_surburb'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A14;?></td>
        <td><input type="text" size="10" class="vullen" name="users_zip" value="<?php print $row['users_zip'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A15;?></td>
        <td><input type="text" size="30" class="vullen" name="users_city" value="<?php print $row['users_city'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A16A;?></td>
        <td><input type="text" size="30" class="vullen" name="users_province" value="<?php print $row['users_province'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td valign="top" ><?php print A16;?></td>
        <td><b><?php print strtoupper($row['users_country']);?></b><br>
        <?php
        if($p['countries_shipping_tax_active'] == 'yes') {
        	print A17." | ".$p['countries_shipping_tax']."%";
        }
        else {
        	print A18;
        }
        print "<br>";
        if($p['countries_shipping_tax_active'] == 'yes') print A19; else print A20;

        ?>
        </td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print A21;?></td>
        <td><input type="text" size="20" class="vullen" name="users_telephone" value="<?php print $row['users_telephone'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
        <td><?php print FAX;?></td>
        <td><input type="text" size="20" class="vullen" name="users_fax" value="<?php print $row['users_fax'];?>"></td>
</tr>
<tr bgcolor="#EAFDD7">
<td colspan="3"><div align="center"><input type="submit" class="knop" value="<?php print UPDATEL;?>"></div></td>
</form>
</tr>
</table>
</div>




</td>
</tr>





<?php
// Adresse de facturation
if(!empty($row['users_facture_adresse'])) {
	print "<tr><td width='200' valign='top'>".A62."</td><td>";
	$adress21 = $adress2[0]."<br>".$adress2[1]."<br>".$adress2[2]."<br>".$adress2[3]."<br>".$adress2[4]."<br>".$adress2[5]."<br>".$adress2[6]."<br>";
	print "<div style='background:#EAFDD7; border:1px #CCCCCC dotted; padding:7px; margin-right:10px;'>".$adress21."</div>"; 
	print "</td></tr>";
}
?>









<tr>
        <td width="200">E-mail adres</td>
        <td><b><?php print "<a href='mailto:".$row['users_email']."'>".$row['users_email']."</a>"; ?></b>&nbsp;&nbsp;(<a href='klant_fiche.php?action=view&id=<?php print $row['users_password'];?>'><?php print MODIFIER;?></a>)</td>
</tr>
<tr>
        <td width="100"><?php print A22;?></td>
        <td><b>
			<?php 
			if($row['users_lang'] == 1) {
			    print "FR";
			} elseif($row['users_lang'] == 2) {
			    print "EN";
			} elseif($row['users_lang'] == 3) {
			    print "BE - NL";
			}
			?>
		</b></td>
</tr>
<tr>
        <td width="200">IP</td>
        <td><b><?php print ($row['users_ip']=="")? "--" : $row['users_ip'];?></b></td>
</tr>
<tr>
        <td width="200"><?php print A36;?></td>
        <td><span style='color:#CC0000'><?php (empty($row['users_comment']))? print "--" : print str_replace("\r\n","<br>",str_replace("||","",$row['users_comment'])); ?></span></td>
</tr>









<tr height="25" bgcolor="#CCFFCC">
<?php
$ship = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$row['users_shipping']."'") or die (mysql_error());
$resultShip = mysql_fetch_array($ship);
?>
        <td width="100"><?php print MODE_LIVRAISON;?></td>
        <td><b><?php print ($resultShip['livraison_nom_'.$_SESSION['lang']]=="")? "--" : "<span style='font-size:12px'>".$resultShip['livraison_nom_'.$_SESSION['lang']]."</span>";?></b></td>
</tr>









<tr>
        <td width="200">NIC</td>
        <td>
<?php
		 	if(substr($row['users_nic'], 0, 5) == "TERUG") {
		 		print "<b>".str_replace("||","",$row['users_nic'])."</b>";
			}
			else {
				print "<form method='POST' action='detail.php'>";
				print "<input type='text' class='vullen' size='20' name='modifNic' value='".$row['users_nic']."'>";
				print "<input type='hidden' name='action' value='updateNic'>";
				print "<input type='hidden' name='id' value='".$row['users_id']."'>";
				print "<input type='hidden' name='oldNic' value='".$row['users_nic']."'>";
				print "<input type='hidden' name='yo' value='".$_GET['yo']."'>";
				print "<input type='hidden' name='jour1' value='".$_GET['jour1']."'>";
				print "<input type='hidden' name='mois1' value='".$_GET['mois1']."'>";
				print "<input type='hidden' name='an1' value='".$_GET['an1']."'>";
				print "<input type='hidden' name='jour2' value='".$_GET['jour2']."'>";
				print "<input type='hidden' name='mois2' value='".$_GET['mois2']."'>";
				print "<input type='hidden' name='an2' value='".$_GET['an2']."'>";
				print "&nbsp;<input type='submit' value='".A32."' class='knop'>";
				print "</form>";
			}
?>
        </td>
</tr>
<tr bgcolor="#FFFFFF">
        <td width="200"><?php print FACT;?> </td>

<?PHP 
		// door Mathias: hier gaan we een form maken met editeerbaar veld voor factuurnummer 

 print "<TD>" . PHP_EOL;
				print "<form method='POST' action='detail.php'>";
				print "<input type='text' class='vullen' size='20' name='fact_num' value='". str_replace("||","",$row['users_fact_num']) ."'>";
				print "<input type='hidden' name='action' value='updateFactNum'>";
				print "<input type='hidden' name='id' value='".$row['users_id']."'>";
				print "<input type='hidden' name='nic' value='".$row['users_nic']."'>";
				print "<input type='hidden' name='yo' value='".$_GET['yo']."'>";
				print "<input type='hidden' name='jour1' value='".$_GET['jour1']."'>";
				print "<input type='hidden' name='mois1' value='".$_GET['mois1']."'>";
				print "<input type='hidden' name='an1' value='".$_GET['an1']."'>";
				print "<input type='hidden' name='jour2' value='".$_GET['jour2']."'>";
				print "<input type='hidden' name='mois2' value='".$_GET['mois2']."'>";
				print "<input type='hidden' name='an2' value='".$_GET['an2']."'>";
				print "&nbsp;<input type='submit' value='".A32."' class='knop'>";
				print "</form>";
	
		
		
		print "</TD>" . PHP_EOL ;
		
		
/*      <td><?php ($row['users_fact_num']=='')? print "<b><span style='color:red'>--</span></b>" : print "<b><span style='color:red'>".str_replace("||","",$row['users_fact_num'])." </span></b>";?></td> 
*/
?> 

		
		
</tr>
<tr bgcolor="#FFFFFF">
        <td width="200"><?php print NO_TVA;?></td>
        <td><?php empty($adress2[7])? print "<b><span style='color:red'>--</span></b>" : print "<b><span style='color:red'>".$adress2[7]."</span></b>";?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td width="200"><?php print A23;?></td>
        <td><b><?php print "<a href='mijnklant.php?id=".$row['users_password']."'><span color='red'>".$row['users_password']."</span></a>"; ?></b></td>
</tr>
</tr>
<tr bgcolor="#FFFFFF">
<?php
                $hidsss = mysql_query("SELECT users_pro_payable
                                     FROM users_pro
                                     WHERE users_pro_password = '".$row['users_password']."'
                                     ");
			     $myhidss = mysql_fetch_array($hidsss);
                if($myhidss['users_pro_payable']==0) {$payable = CASH;}
                if($myhidss['users_pro_payable']==30) {$payable = _30_DAYS;}
                if($myhidss['users_pro_payable']==60) {$payable = _60_DAYS;}
                if($myhidss['users_pro_payable']==90) {$payable = _90_DAYS;}  
?>
        <td width="200"><?php print PAYABLE;?></td>
        <td><b><?php print "** ".$payable." **"; ?></b></td>
</tr>
<tr bgcolor="#FFFFFF">
<?php
// Affiliation
$aff2 = mysql_query("SELECT aff_number FROM affiliation WHERE aff_customer='".$row['users_password']."'") or die (mysql_error());
if(mysql_num_rows($aff2)>0) {
	$resultAff2 = mysql_fetch_array($aff2);
	print "<tr bgcolor='#FFFFFF'>";
	print "<td width='200'>".A111."</td>";
    print "<td><a href='affiliate.php?action=view&id=".$resultAff2['aff_number']."'><b>".$resultAff2['aff_number']."</b></a></td>";
	print "</tr>";
}
?>
<tr bgcolor="#FFFFFF">
        <td width="200"><?php print A800;?></td>
        <td><b><?php empty($row['users_devis'])? print "--" : print "<a href='offerte_details.php?id=".$row['users_devis']."'><span style='color:red'>".$row['users_devis']."</span></a>";?></b></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td width="200"><?php print A80;?></td>
        <td><b><?php print $row['users_save_data_from_form'];?></b></td>
</tr>

<tr bgcolor="#FFFFFF">
<form method='POST' action='detail.php'>
<input type="hidden" name="action" value="updatePayment">
<input type="hidden" name="id" value="<?php print $row['users_id'];?>">
<input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
<input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
<input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
<input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
<input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
<input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
<input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
        <td width="200" valign="top"><?php print A24;?></td>
        <td>
        <?php
        if($row['users_payment'] == "pp") $payMode1 = "selected"; else $payMode1 = "";
        if($row['users_payment'] == "mb") $payMode2 = "selected"; else $payMode2 = "";
        if($row['users_payment'] == "cc") $payMode3 = "selected"; else $payMode3 = "";
        if($row['users_payment'] == "BO") $payMode4 = "selected"; else $payMode4 = "";
        if($row['users_payment'] == "ch") $payMode5 = "selected"; else $payMode5 = "";
        if($row['users_payment'] == "ma") $payMode6 = "selected"; else $payMode6 = "";
        if($row['users_payment'] == "BL") $payMode7 = "selected"; else $payMode7 = "";
        if($row['users_payment'] == "wu") $payMode8 = "selected"; else $payMode8 = "";
        if($row['users_payment'] == "tb") $payMode9 = "selected"; else $payMode9 = "";
        if($row['users_payment'] == "ss") $payMode10 = "selected"; else $payMode10 = "";
        if($row['users_payment'] == "eu") $payMode11 = "selected"; else $payMode11 = "";
        if($row['users_payment'] == "pn") $payMode12 = "selected"; else $payMode12 = "";
        if($row['users_payment'] == "ml") $payMode13 = "selected"; else $payMode13 = "";
        
        
        print "<select name='word'>";
        print "<option value='eu' ".$payMode11.">1euro.com</option>";
        print "<option value='cc' ".$payMode3.">".A25."</option>";
        print "<option value='ch' ".$payMode5.">".A27."</option>";
        print "<option value='BL' ".$payMode7.">".A28b."</option>";
        print "<option value='ss' ".$payMode10.">Liaison SSL</option>";
        print "<option value='ma' ".$payMode6.">".A28."</option>";
        print "<option value='mb' ".$payMode2.">Moneybookers</option>";
        print "<option value='pp' ".$payMode1.">Paypal</option>";
        print "<option value='tb' ".$payMode9.">".TRAITE_BANCAIRE."</option>";
        print "<option value='BO' ".$payMode4.">".A26."</option>";
        print "<option value='wu' ".$payMode8.">Western Union</option>";
        print "<option value='pn' ".$payMode12.">Pay.nl</option>";
        print "<option value='ml' ".$payMode13.">Mollie</option>"; 
        print "</select>";
        print "&nbsp;<input type='submit' value='".UPDATEL."' class='knop'>";
        
        if(!empty($row['pp_statut'])) print "<br>Status: ".$row['pp_statut'];
        if(!empty($row['trans_id'])) print "<br>Transactie Id: ".$row['trans_id'];
        if($row['users_payment']=="BL") print "<br>Tel: ".$row['users_telephone'];
        ?>
        </td>
</form>
</tr>
  <tr bgcolor="#FFFFFF">
    <td width="200"><?php print A35;?></td>
    <?php
        if($row['users_facture'] > 0) {
            $factured = "<b>".A30."</b> (".$row['users_facture']." ".A60.")";
        } else {
            $factured = "<b>".A31."</b>";
        }

?>
    <td><?php print $factured;?></td>
  </tr>
<?php
if(substr($row['users_nic'], 0, 5) !== "TERUG") {
?>
      <tr bgcolor="#FFFFFF">
      <form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
        <td width="100"><?php print REMBOURSEE;?></td>
        <td>
<?php
         if($row['users_refund'] == "yes") {
            if($row['users_refund'] == "yes") {$sel10 = "selected";} else {$sel10 = "";}
            if($row['users_refund'] == "no") {$sel11 = "selected"; } else {$sel11 = "";}
?>
          	<select name='remboursee'>
          	<option value="yes" <?php print $sel10;?>><?php print A30;?></option>
          	<option value="no" <?php print $sel11;?>><?php print A31;?></option>
          	</select>
			<input type="submit" value="<?php print A32;?>" name="submit" class="knop">
			<input type="hidden" name="id" value="<?php print $row['users_id'];?>">
			<input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
			<input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
			<input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
			<input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
			<input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
			<input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
			<input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
			&nbsp;<input type="hidden" name="action" value="remb">
<?php 
        }
        else {
            print "<b>".A31."</b>";
        }
?>
        </td>
      </form>
      </tr></table><br>

<?php
}
?>

<table align="center" width="700" border="0" cellpadding="4" cellspacing="0" class="TABLE">
<tr bgcolor="#FFF99">
<form method="GET" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200"><?php print A29;?></td>
<?php
        if($row['users_confirm'] == "yes") {
            $checkedyes2 = "checked";
            $checkedno2 = "";
        } else {
          $checkedyes2 = "";
          $checkedno2 = "checked";
        }
?>
    <td> <?php print A30;?>
      <input type="radio" value="yes" name="paymentConfirmed" <?php print $checkedyes2;?> >
      <?php print A31;?>
      <input type="radio" value="no" name="paymentConfirmed" <?php print $checkedno2;?> >
		&nbsp;
      <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
      <input type="hidden" name="action" value="updateConfirm">
      <input type="submit" value="Wijzigen" name="submit"  class='knop'>
    </td>
</form>
</tr>



<tr bgcolor="#CCFFCC">
<form method="GET" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200"><?php print A33;?></td>
<?php
        if($row['users_payed'] == "yes") {
            $checkedyes1 = "checked";
            $checkedno1 = "";
        } else {
          $checkedyes1 = "";
          $checkedno1 = "checked";
        }
?>
    <td> <?php print A30;?>
      <input type="radio" value="yes" name="processedPayment" <?php print $checkedyes1;?> >
      <?php print A31;?>
      <input type="radio" value="no" name="processedPayment" <?php print $checkedno1;?> >
      &nbsp;
      <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
      <input type="hidden" name="action" value="updatePayed">
      <input type="submit" value="Wijzigen" name="submit"  class='knop'>
    </td>
</form>
</tr>

<tr bgcolor="#99FF99">
  <form method="GET" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200"><?php print READY;?></td>
    <?php // Commande pr�te a �tre exp�di�e
        if($row['users_ready'] == "yes") {
            $checkedyes9d = "checked";
            $checkedno9d = "";
        } else {
          $checkedyes9d = "";
          $checkedno9d = "checked";
        }
?>
    <td> <?php print A30;?>
      <input type="radio" value="yes" name="processedReady" <?php print $checkedyes9d;?> >
      <?php print A31;?>
      <input type="radio" value="no" name="processedReady" <?php print $checkedno9d;?> >
      &nbsp;
      <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
      <input type="hidden" name="action" value="updateReady">
      <input type="submit" value="Wijzigen" name="submit"  class='knop'>
    </td>
  </form>
  </tr>

<tr bgcolor="#FFCC66">
  <form method="GET" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200"><?php print A34;?></td>
    <?php // Commande exp�di�e
        if($row['users_statut'] == "yes") {
            $checkedyes9 = "checked";
            $checkedno9 = "";
        } else {
          $checkedyes9 = "";
          $checkedno9 = "checked";
        }
?>
    <td> <?php print A30;?>
      <input type="radio" value="yes" name="processed" <?php print $checkedyes9;?> >
      <?php print A31;?>
      <input type="radio" value="no" name="processed" <?php print $checkedno9;?> >
      &nbsp;
      <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
      <input type="hidden" name="action" value="update">
      <input type="submit" value="Wijzigen" name="submit"  class='knop'>
    </td>
  </form>
  </tr>


</table>
<br>
<table align="center" width="700" border="0" cellpadding="4" cellspacing="0" class="TABLE">










<tr>
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
        <td width="200" valign="top"><?php print A61;?></td>
        <td>
        <textarea cols="70" rows="10" name="note" value=""><?php print $row['users_note'];?></textarea>
        </td>
</tr>
<tr>
        <td width="200" align="center">&nbsp;</td><td>
        <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
    	<input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      	<input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      	<input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      	<input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      	<input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      	<input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      	<input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
        <input type="hidden" name="action" value="addnote">
        <input type="submit" value="<?php print A32;?>" name="submit" class="knop">
        </td>
</form>
</tr>

<?php // Ajouter une note � l'interface de suivi client ?>
      <tr>
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
        <td width="200" valign="top"><?php print A61A." <a href='../klantlogin/login.php?nic=".$row['users_nic']."&l=".$_SESSION['lang']."' target='_blank'>".$row['users_nic']."</a>";?></td>
        <td>
        <textarea cols="70" rows="10" name="shareNote" value=""><?php print $row['users_share_note'];?></textarea>
        </td>
        </tr>
        <tr>
        <td width="100"align="center">&nbsp;</td>
		<td>
        <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
	    <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
	    <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
	    <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
	    <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
	    <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
	    <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
	    <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
        <input type="hidden" name="action" value="addShareNote">
        <input type="hidden" name="emailNote" value="<?php print $row['users_email'];?>">
        <input type="hidden" name="nicNote" value="<?php print $row['users_nic'];?>">
        <input type="submit" value="<?php print A32A;?>" name="submit" class="knop">
        <div><img src="im/zzz.gif" width="1" height="8"></div>
        </td>
</form>
      </tr>
      
      
</table><br>
<table align="center" width="700" border="0" cellpadding="4" cellspacing="0" class="TABLE">


<?php // klacht venster ?>
<tr bgcolor="<?php print $col;?>">
<form method="GET" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200" valign="top"><?php print COMMANDE_EN_LITIGE;?></td>
    <?php
        if($row['users_litige'] == "yes") {
            $checkedyes1 = "checked";
            $checkedno1 = "";
        } else {
          $checkedyes1 = "";
          $checkedno1 = "checked";
        }
?>
    <td width="200" valign="top"><?php print A30;?>
      <input type="radio" value="yes" name="litige" <?php print $checkedyes1;?> >
      <?php print A31;?>
      <input type="radio" value="no" name="litige" <?php print $checkedno1;?> > 
      <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      <input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      <input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      <input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      <input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      <input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      <input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      <input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
      <input type="hidden" name="action" value="litige">
      <input type="submit" value="Wijzigen" name="submit" class="knop"
    </td>
</form>
</tr>

<?php  // klacht ja

if($row['users_litige']=="yes") {
?>
<tr bgcolor="<?php print $col;?>">
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
    <td width="200" valign="top"><?php print NOTES_LITIGE;?></td>
    <td><textarea cols="70" rows="10" name="noteLitige" value=""><?php print $row['users_litige_comment'];?></textarea>
    </td>
    </tr>
<tr bgcolor="<?php print $col;?>">
        <td width="200" align="top"></td>
        <td>
        <input type="hidden" name="id" value="<?php print $row['users_id'];?>">
      	<input type="hidden" name="yo" value="<?php print $_GET['yo'];?>">
      	<input type="hidden" name="jour1" value="<?php print $_GET['jour1'];?>">
      	<input type="hidden" name="mois1" value="<?php print $_GET['mois1'];?>">
      	<input type="hidden" name="an1" value="<?php print $_GET['an1'];?>">
      	<input type="hidden" name="jour2" value="<?php print $_GET['jour2'];?>">
      	<input type="hidden" name="mois2" value="<?php print $_GET['mois2'];?>">
      	<input type="hidden" name="an2" value="<?php print $_GET['an2'];?>">
        <input type="hidden" name="action" value="addnoteLitige">
        <input type="submit" value="<?php print A32;?>" name="submit" class="knop">
        </td>
</form>
</tr>
<?php
}
if($row['users_litige']=="no" AND $row['users_litige_comment']!=="") {
?>

<tr bgcolor="<?php print $col;?>">
    <td width="200" align="top"><?php print NOTES_LITIGE;?></td>
    <td>
<?php 
      print "<div style='background:#FFFFFF; border:#CC0000 1px dotted; padding:5px;'>";
      print LITIGE_CLOS;
      print str_replace("\r\n","<br>",$row['users_litige_comment']);
      print "</div>";
?>
    </td>
</tr>
<?php
}
?>

</table>





<br>
<br>
<br>
<br>






<?php
//--------------------------------
// bestel overzicht
//--------------------------------
print "<table width='700' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE'><tr>";
print "<td>";
print "<div align='center' style='background:#CCCCCC; padding:10px; border:#FFFFFF 2px solid; color:#000000; font-weight:bold; font-size:15px;'><b>-- ".strtoupper(COMMANDE)." --</b></div>";
print "</td></tr><tr><td>";
$tr = $row['users_remise']+$row['users_remise_coupon'];
$tp = $row['users_products_ht']+$row['users_products_tax'];
print "&nbsp;".A37.": ".$row['users_symbol_devise']." <b>".sprintf("%0.2f",$tp)."</b><br>";
print "&nbsp;".A38.": ".$row['users_symbol_devise']." <b>".$row['users_shipping_price']."</b><br>";
print "&nbsp;".A39.": ".$row['users_symbol_devise']." <b>".sprintf("%0.2f",$tr)."</b>";

if(!empty($row['users_shipping_note']) or !empty($row['users_remise_note']) or !empty($row['users_coupon_note'])) {
	print "<br><br>".A65;
}
if(!empty($row['users_shipping_note'])) {print "<br>&nbsp;- ".$row['users_shipping_note'];}
if(!empty($row['users_remise_note'])) {print "<br>&nbsp;- ".$row['users_remise_note'];}
if(!empty($row['users_coupon_note'])) {print "<br>&nbsp;- ".$row['users_coupon_note'];}
if(!empty($row['users_account_remise_note'])) {print "<br>&nbsp;- ".$row['users_account_remise_note'];}
if($row['users_remise_gc'] > 0) {print "<br>&nbsp;- ".NUMERO_CHEQUE." : <span color=red><b><a href='kadeaubon.php'>".$row['users_gc']."</a></b></span>";}
if(!empty($row['users_devis'])) print "<br>&nbsp;- ".A800." : <a href='offerte_details.php?id=".$row['users_devis']."'><span style='color:#CC0000'><b>".$row['users_devis']."</b></span></a>";
print "</td>";
print "</tr></table>";                

if(!empty($row['users_affiliate']) AND $row['users_confirm'] == "yes" AND $row['users_payed'] == 'yes') {
	print "<br><table bgcolor='FFFF00' width='700' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE'><tr>";
	print "<td>65665456";
	print A110."<br>";
	print "&nbsp;- ".A111.": <a href='affiliate.php?action=view&id=".$row['users_affiliate']."'><b>".$row['users_affiliate']."</b></a><br>";
	print "&nbsp;- ".A112.": <b>".$row['users_affiliate_amount']."".$row['users_symbol_devise']."</b><br>";
	print "&nbsp;- ".A113.": <b>".$row['users_affiliate_payed'];
	print "</td>";
	print "</tr></table>";
	print "<br>";
}
else {
	print "<br>";
}
                
//------------------
// bestel tabel onderaan de pagina
//------------------
print "<table width='700' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE'><tr><td>";

print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='2'>";
print "<tr>";
print "<td><b><u>Ref/".A41."</u></b></td>";
print "<td width='50' align='center'><b><u>".A42."</u></b></td>";
print "<td width='50' align='center'><b><u>".A43."</u></b></td>";
print "<td width='80' align='right'><b><u>".A44." ".$row['users_products_tax_statut']."</u></b></td>";

                $split = explode(",",$row['users_products']);
                foreach ($split as $item) {
                        $check = explode("+",$item);

						if($check[1]!=="0") {
	                        print "</tr><tr>";
	                        // Reference/nom article
	                        if($check[3]=="GC100") {
	                        	$hidz = mysql_query("SELECT gc_number FROM gc WHERE gc_nic='".$row['users_nic']."'");
	                            $myhidz = mysql_fetch_array($hidz);
	                            $qqq = "<br><a href='kadeaubon.php'>".$row['users_gc']."</a>";
	                        }
	                        else {
	                            $qqq="";
	                        }
							// Options
	                        if(!empty($check[6])) {
	                        	$_optZ = explode("|",$check[6]);
								## session update option price
								$lastArray = $_optZ[count($_optZ)-1];
								if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
								$_optZ = implode("|",$_optZ);
								$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
							}
							else {
								$q="";
							}
	                        // Afficher Reference/nom article + Options
	                        print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4]." ".$qqq." ".$q;
	                        // Eco participation
	                        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
	                        // quantit�
	                        print "<td width='50' align='center'>".$check[1];
	                        // Taxe
	                        print "<td width='50' align='center'>".$check[5]."%";
	                        // Prix HT
	                        $priceTTC = ($check[2] * $check[1]);
	                        print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC);
						}
                        print "</td>";
                }

                print "</tr></table><br>";


                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td>";
                print A45.": <b>".$row['users_products_weight']."</b> gr <br>".$row['users_products_weight_price']." ".$row['users_symbol_devise']."/gr ".A46;
                $ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
                if($ecoTaxFactFinal>0) {
                    print "<br><i><span style='color:#00CC00'>".DONT." Eco taks: ".$ecoTaxFactFinal." ".$symbolDevise."</span></i>";
                }
                print "</td>";
                print "</tr></table>";

                print "<table border='0' align='right' cellspacing='0' cellpadding='0'>";
                print "<td align='right'>".A47."</td>";
                print "<td width='100' align='right'><b>".$row['users_products_ht']."</b></td>";
                print "</tr><tr>";
                print "<td align='right'>".$taxeName."</td>";
                // display multiple tax
                print "<td align='right'>";
                $explodMultiple = explode("|",$row['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                            $itemDisplay = "";
                        }
                        else {
                            if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                            $itemDisplay = str_replace(">", "%: ", $item).$br;
                        }
                        print $itemDisplay;
                    }
                print "</td>";
                  
                // Display Livaison
                print "</tr><tr>";
                print "<td align='right'>".A49."</td>";
                print "<td align='right'>".$row['users_ship_ht']."</td>";
                print "</tr><tr>";
                print "<td align='right'>".$taxeName."</td>";
                print "<td align='right'>".$row['users_ship_tax']."</td>";
                
                // Display Emballage
                if(abs($row['users_sup_ttc']) > 0) {
	                print "</tr><tr>";
	                print "<td align='right'>".EMBALLAGE."</td>";
	                print "<td align='right'>".$row['users_sup_ht']."</td>";
	                print "</tr><tr>";
	                print "<td align='right'>".$taxeName."</td>";
	                print "<td align='right'>".$row['users_sup_tax']."</td>";
                }
                
                if(abs($row['users_remise']) > 0) {
	                ($row['users_remise']<0)? $sig1="" : $sig1="-";
	                print "</tr><tr>";
	                print "<td align='right'>".A51."</td>";
	                print "<td align='right'><span style='color:red'><b>".$sig1.sprintf("%0.2f",abs($row['users_remise']))."</b></span></td>";
                }
                if(abs($row['users_account_remise_app']) > 0) {
	                (substr($row['users_nic'], 0, 5) == "TERUG")? $sig="" : $sig="-";
					print "</tr><tr>";
	                print "<td align='right'>".A51A."</td>";
	                print "<td align='right'><span style='color:red'><b>".$sig.sprintf("%0.2f",abs($row['users_account_remise_app']))."</b></span></td>";
                }
                if(abs($row['users_remise_gc']) > 0) {
	                ($row['users_remise_gc']<0)? $sig3="" : $sig3="-";
					print "</tr><tr>";
	                print "<td align='right'>".CHEQUE_CADEAU_MIN."</td>";
	                print "<td align='right'><span style='color:red'><b>".$sig3.sprintf("%0.2f",abs($row['users_remise_gc']))."</b></span></td>";
                }
                if(abs($row['users_remise_coupon']) > 0) {
	                ($row['users_remise_coupon']<0)? $sig2="" : $sig2="-";
	                print "</tr><tr>";
	                print "<td align='right'>".A52." <i><b>".$row['users_remise_coupon_name']."</b></i></td>";
	                print "<td align='right'><span style='color:red'><b>".$sig2.sprintf("%0.2f",abs($row['users_remise_coupon']))."</b></span></td>";
                }
                if(abs($row['users_contre_remboursement']) > 0) {
	                ($row['users_contre_remboursement']<0)? $sig4="-" : $sig4="";
					print "</tr><tr>";
	                print "<td align='right'>".A28b."</td>";
	                print "<td align='right'>".$sig4.sprintf("%0.2f",abs($row['users_contre_remboursement']))."</td>";
                }
                
                print "</tr><tr>";
                print "<td align='right'>".A53.":</td>"    ;
                print "<td align='right'><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
                print "</tr>";
                print "</table>";

print "</td></tr></table>";

if(isset($_GET['yo'])) {
print "<p align='center'>";
        if(!isset($_GET['from']) or empty($_GET['from'])) print "<a href='klanten.php?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>".A54."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "items") print "<a href='resume_items_2.php?id=".$_GET['id2']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A502."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "cli") print "<a href='verkoop_op_klant_details.php?id=".$_GET['id2']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A522."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "fou") print "<a href='verkoop_op_leverancier_details.php?id=".$_GET['id2']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A501."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "tax") print "<a href='berekenen.php?jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>".A55."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "facture") print "<a href='zoeken.php'>".A56."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "account") print "<a href='mijnklant.php?id=".$_GET['acc']."'> ".A100."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "aff") print "<a href='affiliate.php?action=view&id=".$_GET['who']."'> ".A120." # ".$_GET['who']."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "newsletterTable") print "<a href='nieuwsbrief_adressen.php'> ".A56A."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "todo") print "<a href='menu.php'> ".A500."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "devis") print "<a href='offerte_details.php?id=".$_GET['devisNo']."'> ".A503."</a>";
        if(isset($_GET['from']) and $_GET['from'] == "pro") print "<a href='klant_fiche.php?action=view&id=".$_GET['account']."'>".COMPTE_CLIENT."</a>";
print "</p>";
}
?>
<br>
</body>
</html>
<?php
}
?>
