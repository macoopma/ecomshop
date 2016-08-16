<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

$title = ENREGISTRER_RECUPERER_CADDIE_MAJ;
?>

<script type="text/javascript"><!--
function check_form1() {
  var error = 0;
  var error_message = "";

  var password = document.form1.password.value;
  var password2 = document.form1.password2.value;
  var password3 = document.form1.password3.value;


  if(document.form1.elements['password'].type != "hidden") {
    if(password == '' || password.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM_DU_CADDIE;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['password2'].type != "hidden") {
    if(password2 == '' || password2.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print CONFIRMEZ_MOT_DE_PASSE;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['password3'].type != "hidden") {
    if(password3 == '' || password3.length < 6 || password3.indexOf ('@') == -1 || password3.indexOf ('.') == -1 ) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
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
//--></script>

<script type="text/javascript"><!--
function check_form2() {
  var error = 0;
  var error_message = "";

  var motdepasse = document.form2.motdepasse.value;
  var caddieNumber = document.form2.caddieNumber.value;


  if(document.form2.elements['motdepasse'].type != "hidden") {
    if(motdepasse == '' || motdepasse.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM_DU_CADDIE;?>\n";
      error = 1;
    }
  }
  if(document.form2.elements['caddieNumber'].type != "hidden") {
    if(caddieNumber == '' || caddieNumber.length < 6) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NUMERO_DE_PANIER;?>\n";
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
//--></script>

<?php


if(isset($_POST['action']) AND $_POST['action']=="add") {
              if($_POST['password'] != $_POST['password2']) {
                $err = "<p>".NOM_DU_CADDIE." 1: <b>".$_POST['password']."</b><br>".NOM_DU_CADDIE." 2: <b>".$_POST['password2']."</b><br>".MOT_DE_PASSE_NON_IDENTIQUE."</p>";
              }
              else {
                    if(empty($_SESSION['list'])) {
                          $err = VEUIILEZ_SELECTIONNER_AU_MOINS_1_ARTICLE;
                    }
                    else {
                           
                           $verif_password = mysql_query("SELECT *
                                                          FROM users_caddie
                                                          WHERE users_caddie_password = '".$_POST['password']."'
                                                          AND users_caddie_email = '".$_POST['password3']."'
                                                          ");
                           $rows = mysql_num_rows($verif_password);
                                   if($rows == "1") {
                                      $err = "<p>".NOM_DU_CADDIE.": <b>".$_POST['password']."</b><br>".MOT_DE_PASSE_DEJA_UTILISE."</p>";
                                   }
                                   else {
                                        
                                          $str = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                                          $caddie_number = '';
                                          for( $i = 0; $i < 6 ; $i++ ) {
                                              $caddie_number .= substr($str, rand(0, strlen($str) - 1), 1);
                                          }
                                         $_SESSION['caddie_id'] = $caddie_number;
                                         $_SESSION['caddie_password'] = $_POST['password'];
                                         $dateNowUser = date("Y-m-d");
                                         
                                         
                                         if(isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
                                            $clientCaddieNumber = $_SESSION['account'];
                                         }
                                         else {
                                            $clientCaddieNumber = "";
                                         }
                                         $result = mysql_query("INSERT INTO users_caddie 
                                                                (users_caddie_session, users_caddie_password, users_caddie_number, users_caddie_date, users_caddie_email, users_caddie_client_number) 
                                        						 VALUES ('".$_SESSION['list']."', '".$_POST['password']."', '".$caddie_number."', '".$dateNowUser."', '".$_POST['password3']."','".$clientCaddieNumber."')");
                                          
                                         $to = $_POST['password3'];
                                         $from = $mailInfo;
                                         $subject = VOTRE_CADDIE_SUR." [".strtoupper($domaineFull)."]";

                                         $_store = str_replace("&#146;","'",$store_company);
                                         $messageToSend = $_store."\r\n";
                                         $messageToSend .= $address_street."\r\n";
                                         $messageToSend .= $address_cp." - ".$address_city."\r\n";
                                         $messageToSend .= $address_country."\r\n";
                                         if(!empty($address_autre)) {
                                              $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                              $messageToSend .= $address_autre2."\r\n";
                                         }
                                         if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
                                         if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
                                         $messageToSend .= "URL: http://".$www2.$domaineFull."\r\n";
                                         $messageToSend .= "E-mail: ".$mailInfo."\r\n";
                                         $messageToSend .= "Datum: ".date("d-m-Y H:i:s")."\r\n\r\n";
                                         $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                                         $messageToSend .= BONJOUR.",\r\n";
                                         $messageToSend .= VOUS_VENEZ_DE_SAUVEGARDER_UN_CADDIE." http://".$www2.$domaineFull."\r\n";
                                         $messageToSend .= VOTRE_NOM_DE_CADDIE_EST.": ".$_POST['password']."\r\n";
                                         $messageToSend .= VOTRE_NUMERO_DE_CADDIE_EST.": ".$caddie_number."\r\n";
                                         $messageToSend .= ADRESSE_EMAIL.": ".$_POST['password3']."\r\n";
                                         $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                                         $messageToSend .= POUR_PLUS_DINFORMATIONS." ".$mailInfo.".\r\n";
                                         $messageToSend .= LE_SERVICE_CLIENT;

                                         mail($to, $subject, rep_slash($messageToSend),
                                         "Return-Path: $from\r\n"
                                         ."From: $from\r\n"
                                         ."Reply-To: $from\r\n"
                                         ."X-Mailer: PHP/" . phpversion());
                                          
                                         $err = "<p><b>".CADDIE_ENREGISTRE_AVEC_SUCCES.".</b><br></p>";
                                   }
                    }
              }
}
else {
$err = "";
}
if(empty($_SESSION['list']))
 {
 $err = "<p>".VEUIILEZ_SELECTIONNER_AU_MOINS_1_ARTICLE."</p>";
 }


if(isset($_POST['action']) AND $_POST['action']=="recup") {
	$addToQuery = ($displayOutOfStock=="non")? " AND p.products_qt>'0'" : ""; 
	
	$verif_enr = mysql_query("SELECT users_caddie_session, users_caddie_date, NOW()
								FROM users_caddie
								WHERE users_caddie_password = '".$_POST['motdepasse']."'
								AND users_caddie_number = '".$_POST['caddieNumber']."'
								AND TO_DAYS(NOW()) - TO_DAYS(users_caddie_date) <= '".$saveCart."'");
	$rows = mysql_num_rows($verif_enr);
	if($rows == 0) {
		$messageSaveCart = NOM_DU_CADDIE.": <b>".$_POST['motdepasse']."</b><br>".NUMERO_DE_PANIER.": <b>".$_POST['caddieNumber']."</b><br><br>".VOTRE_CADDIE_N_EST_PAS_ENREGISTRE_OU;
	}
	else {
		$nodesession = mysql_fetch_array($verif_enr);
		$session_id = $nodesession["users_caddie_session"];
		$session_article = explode(",",$session_id);
		$nbre_article = count($session_article);
			foreach ($session_article as $item) {
			$addToQuery2Z = ($actRes=="oui")? "" : " AND p.products_qt>'0'";
			$check = explode("+", $item);
			$requete = mysql_query("SELECT p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, p.products_ref
									FROM products as p
									LEFT JOIN specials as s
									ON (p.products_id = s.products_id)
									WHERE p.products_id = '".$check[0]."'
									AND p.products_visible= 'yes'
									".$addToQuery." 
									".$addToQuery2Z."
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
				}
				else {
					$rowal['specials_new_price'] = $rowal['products_price'];
				}
			}
			
			
			if(isset($check[6]) AND $check[6]!=="") {
				$optionPrice=0;
				$optCadExplode = explode("|", $check[6]);
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
			
			
			if($saveCartToOne == "oui") {$check[1] = 1;}
			$toto[] = $check[0]."+".$check[1]."+".$rowal['specials_new_price']."+".$rowal['products_ref']."+".$check[4]."+".$check[5]."+".$check[6]."+".$check[7]."+".$check[8];
		}
		
		$_SESSION['list'] = implode(",",$toto);
		$_SESSION['caddie_id'] = $_POST['caddieNumber'];
		$_SESSION['caddie_password'] = $_POST['motdepasse'];
		$messageSaveCart = CADDIE_RECUPERE_ET_ACTUALISE;
	}
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
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
// header 1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

// header 2
if($header2Display=='oui') {
	print "<td colspan='3'>";
	include('includes/tabel_top2.php');
	print "</td></tr><tr>";
	print "<td colspan='3'>";
}
else {
	print "<td colspan='3'>";
}

// Menu tab
if($menuVisibleTab=="oui") {
	include('includes/menu_tab.php'); 
	$styleClass1 = "TABLEMenuPathTopPage";
} 
else {
	$styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
// MENU HORIZONTAAL
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
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print $title;?> |</b>
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
  <tr valign="top">
    <td colspan="3">

      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // -----------
		  // kolom links
		  // -----------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>

          <td valign="top" class="TABLEPageCentreProducts">

<?php
if($activeSaveCart=="oui") {
?>

            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="3" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
                         <?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".$title." |";?>
                      </td>
                    </tr>
                  </table>
                  <br>
<?php
}
?>

<?php if(isset($messageSaveCart)) print "<div align='center' class='styleAlert'>".$messageSaveCart."</div>";?>
<?php if(isset($err)) print "<div align='center' class='fontrouge'>".$err."</div>";?>

<p>
<?php print TEXT_SAVECART_1;?>
</p>

                    <table width="400" border="0" align="center" cellspacing="0" cellpadding="5" class="TABLEBorderDotted">
                      <form name="form1" method="post" onsubmit="return check_form1()"; action="<?php print $_SERVER['PHP_SELF'];?>">
                        <input type="hidden" name="action" value="add">
                      <tr>
                        <td align="left">* <?php print NOM_DU_CADDIE;?></td>
                        <td><input type="text" name="password" size="12" maxlength="10" class="TABLE1"></td>
                      </tr>
                      <tr>
                        <td align="left">* <?php print CONFIRMEZ_MOT_DE_PASSE;?></td>
                        <td><input type="text" name="password2" maxlength="10" size="12" class="TABLE1"></td>
                      </tr>
                      <tr>
                        <td align="left">* E-mail</td>
                        <td><input type="text" name="password3" size="20" class="TABLE1"></td>
                      </tr>
                      <tr>
                        <td align="center" colspan="2">
                           <?php
                           if(isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
                           ?>
                              <a href="mijn_account.php"><span style="BACKGROUND:none; border:0px"><img src="im/lang<?php print $_SESSION['lang'];?>/enregistrer.gif" border="0" alt="<?php print ENREGISTRER_CADDIE;?>"></span></a>
                           <?php
                           }
                           else {
                           ?>
                               <input style="BACKGROUND:none; border:0px" type="image" src="im/lang<?php print $_SESSION['lang'];?>/enregistrer.gif" alt="<?php print ENREGISTRER_CADDIE;?>" align="center" name="image">
                           <?php
                           }
                           ?>
                        </td>
                      </tr>
                      </form>
                    </table>
                    
                  <?php if(isset($err)) print "<div align='center' class='fontrouge'>".$err."</div>";?>

<p><?php print TEXT_SAVECART_2;?></p>

                    <table width="400" align="center" border="0" cellspacing="0" cellpadding="5"  class="TABLEBorderDotted">
                     <form name="form2" method="post" onsubmit="return check_form2()"; action="<?php print $_SERVER['PHP_SELF'];?>">
                      <input type="hidden" name="action" value="recup">
                      <tr>
                        <td align="left">* <?php print NOM_DU_CADDIE;?></td>
                        <td><input type="text" name="motdepasse" maxlength="10" size="12" class="TABLE1"></td>
                      </tr>
                      <tr>
                        <td align="left">* <?php print NUMERO_DE_PANIER;?></td>
                        <td><input type="text" name="caddieNumber" maxlength="6" size="8" class="TABLE1"></td>
                      </tr>
                      <tr>
                        <td align="center" colspan="2">
                          <div align="center">
                          <?php
                           if(isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
                           ?>
                              <a href="mijn_account.php"><span style="BACKGROUND:none; border:0px"><img src="im/lang<?php print $_SESSION['lang'];?>/recuperer.gif" border="0" alt="<?php print RECUPERER_CADDIE;?>"></span></a>
                           <?php
                           }
                           else {
                          ?>
                              <input style="BACKGROUND:none; border:0px" type="image" src="im/lang<?php print $_SESSION['lang'];?>/recuperer.gif" alt="<?php print RECUPERER_CADDIE;?>" align="center" name="image2">
                          <?php 
                           }
                          ?>
                          </div>
                        </td>
                      </tr>
                     </form>
                    </table> 
                  <br>
                  <p>
                   <?php print TEXT_SAVECART_3;?>
                  </p>
                </td>
              </tr>
            </table>
<?php
}
else {
print "<br>";
print "<p align='center'>";
print "<a href='javascript:history.back()'><img src='im/lang".$_SESSION['lang']."/articles.gif' border='0'></a>";
print "</p>";
}
?>
          </td>

         <?php 
		  // ---------------------------------------
		  // rechts
		  // ---------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
        </tr>
      </table>

    </td>
  </tr>
</table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
