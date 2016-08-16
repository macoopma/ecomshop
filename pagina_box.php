<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$message33 = "";
$votreEmail = VOTRE." Email";

if(isset($_GET['module']) AND $_GET['module']=="newsletter") {
    $pour = NEWSLETTER; 
    $messageUU = newsletterProcess($votreEmail);
}
if(isset($_GET['module']) AND $_GET['module']=="menu_interface") {
    $pour = INTERFACEW; 
    $messageUU = MESSAGE1;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_languages") {
    $pour = LANGUES; 
    $messageUU = MESSAGE2;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_converter") {
    $pour = "CONVERTER";
    $messageUU = MESSAGE3;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_message") {
    $pour = "COMMUNIQUÉ";
    $messageUU = MESSAGE4;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_quick") {
    $pour = MENU_RAPIDE;
    $messageUU = MESSAGE5;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_subscribe") {
    $pour = NEWSLETTER;
    $messageUU = MESSAGE6;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_cart") {
    $pour = VOTRE_CADDIE;
    $messageUU = MESSAGE7;
}
if(isset($_GET['module']) AND $_GET['module']=="menu_devis") {
    $pour = VOS_DEVIS;
    $messageUU = MESSAGE8;
}
// meta Tags
$title = $pour;
$description = $title." ".$store_name;
$keywords = $title.", ".$store_name.", ".$keywords;

 
function newsletterProcess($votreEmailLang) {
    GLOBAL $_POST;
    GLOBAL $_SESSION;
    include('configuratie/configuratie.php');
    
if(isset($_POST['email']) AND $_POST['email']!=="" AND $_POST['email'] !== $votreEmailLang) {

 
        if(!@preg_match("/^[a-z0-9._-]{1,}@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i", $_POST['email'])) {
              $message33 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
              $message33.= "<span class='fontrouge'><b>".ADRESSE_EMAIL_INVALIDE."</b></span>";
              $message33.= "</p>";
              return $message33;
         }
         else {
               if($_POST['quoi'] == "ok") {
                            // Géneration alléatoire du Numéro d'activation
                               $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                               $str2 = '123456789';
                               $activePassword = '';
                               for( $i = 0; $i < 7 ; $i++ ) {
                               $activePassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
                               } 
                            $query = mysql_query("SELECT *
                                                 FROM newsletter
                                                 WHERE newsletter_email='".$_POST['email']."'");
                            $queryCount = mysql_num_rows($query);
                            $result = mysql_fetch_array($query);
                            if($queryCount > 0) {
                               $message33 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
                               $message33.= "<span class='fontrouge'><b>".EMAIL_DEJA_ENREGISTRE."</b></span>";
                               if($result['newsletter_statut'] == "never") $message33 .= "<br>".COMPTE_NON_ACTIVE;
                               if($result['newsletter_statut'] == "active") $message33 .= "<br>".USAGER_DEJA_ABONNE;
                               $message33.= "</p>";
                                  return $message33;
                            }
                            else {
                            $hoy = date("Y-m-d H:i:s");

                            mysql_query("INSERT INTO newsletter
                                         (newsletter_email,
                                          newsletter_password,
                                          newsletter_langue,
                                          newsletter_active,
                                          newsletter_statut,
                                          newsletter_date_added
                                          )
                                         VALUES
                                         ('".$_POST['email']."',
                                          '".$activePassword."',
                                          '".$_SESSION['lang']."',
                                          'no',
                                          'never',
                                          '".$hoy."'
                                          )");


                            $domMaj = strtoupper($domaineFull);
                            $to =  $_POST['email'];
                            $from = $mailInfo;
                            $subject = "[".ACTIVATION_COMPTE_NEWSLETTER."] - $domMaj";
                            $_store = str_replace("&#146;","'",$store_company);
                            $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                            if(!empty($address_autre)) {
                                $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                $messageToSend .= $address_autre2."\r\n";
                            }
                            if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
                            if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
                            $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                            $messageToSend .= "Datum: ".date("Y-M-d H:i:s")."\r\n\r\n";
                            $messageToSend .= SUJET.": ".ACTIVATION_COMPTE_NEWSLETTER."\r\n";
                            $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                            $messageToSend .= POUR_RECEVOIR_LA_LETTRE."\r\n";
                            $messageToSend .= A_L_ADRESSE_CI_DESSOUS."\r\n";
                            $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                            $messageToSend .= VOTRE_NUMERO_ACTIVATION.": ".$activePassword."\r\n";
                            $messageToSend .= "URL: ".$urlNewsletter."?email=".$_POST['email']."\r\n";
                            $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                            $messageToSend .= POUR_PLUS_DINFORMATIONS." ".$mailOrder.".\r\n";
                            $messageToSend .= LE_SERVICE_CLIENT;
 
                            mail($to, $subject, rep_slash($messageToSend),
                                 "Return-Path: $from\r\n"
                                 ."From: $from\r\n"
                                 ."Reply-To: $from\r\n"
                                 ."X-Mailer: PHP/" . phpversion());

                            $message33 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'><br><br>";
                            $message33.= "<span class='fontrouge'><b>".VEUILLEZ_VERIFIER_VOTRE_EMAIL."</b></span>";
                            $message33.= "</p>";
                            return $message33;
                            }
               }
         }
}
if(isset($_POST['email']) and (empty($_POST['email']) or $_POST['email']== $votreEmailLang)) {
      $message33 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
      $message33.= "<span class='fontrouge'><b>".VEUILLEZ_ENTRER_UN_EMAIL."</b></span>";
      $message33.= "</p>";
      return $message33;
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
// header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

// header2
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
// Menu horizontaal
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
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a>  | <?php print maj($pour);?> |</b>
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
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">

                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
                          <img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php"><?php print maj(HOME);?></a> | <?php print maj($pour);?>
                      </td>
                    </tr>
                  </table>
                  
                  
<?php 
if(isset($messageUU) AND $messageUU!=="") print $messageUU;
?>
                  
     <p align="center">
            <table  border="0" cellspacing="0" cellpadding="10" align="center" class="TABLEBoxesProductsDisplayedCentrePage">
              <tr>
                <td align="center" valign="top">
     <?php 
     if($_GET['module'] =="newsletter") { $_GET['module'] = "menu_inschrijven";}
          //include('includes/'.$_GET['module'].'.php');
          $filenameBoutik = "includes/".$_GET['module'].".php";
          
            if(isset($filenameBoutik) AND file_exists($filenameBoutik)) {
                include($filenameBoutik);
            }
            else {
                include('../index.php');
            }
          
     ?>
                </td>
              </tr>
            </table>
    </p>




                </td>
              </tr>
            </table>
          </td>
         <?php 
		  // ---------------------------------------
		  // rechtse kolom 
		  // ---------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
        </tr>
      </table>
<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
