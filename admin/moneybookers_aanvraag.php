<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

if(isset($_POST['mb_action']) AND $_POST['mb_action']=="activate") {
      if($_POST['mb_firstname']=="" OR $_POST['mb_lastname']=="") {
        $message = "<table align='center' border='0' cellspacing='0' cellpadding='2'><tr>";
        $message.= "<td>";
        if($_POST['mb_firstname']=="") $message.= "<div style='color:#CC0000'>".CHAMPS_NON_VALIDE." ".PRENOM."</div>";
        if($_POST['mb_lastname']=="") $message.= "<div style='color:#CC0000'>".CHAMPS_NON_VALIDE." ".NOM."</div>";
        $message.= "</td>";
        $message.= "</tr></table>";
        $message.= "<br>";
        $message.= "<form method='POST' action='moneybookers_aanvraag.php'>";
        $message.= "<div align='center'><input type='submit' class='knop' value='".RETOUR."'></div>";
        $message.= "</form>";
        $messageExit = 1;
      }
      else {
	$to20 = "ecommerce@moneybookers.com";
      
      $subject20 = "Quick Checkout request activation";
      $message20 = "Platform Name: BoutikOne\r\n";
      $message20 .= "First Name, Last Name of the merchant: ".$_POST['mb_firstname'].", ".$_POST['mb_lastname']."\r\n";
      $message20 .= "Moneybookers Email Address of the merchant: ".$_POST['mb_email']."\r\n";
      $message20 .= "Moneybookers Customer ID of the merchant: ".$_POST['mb_id']."\r\n";
      $message20 .= "URL of merchant’s shop: ".$_POST['mb_url']."\r\n";
      $message20 .= "Language: ".$_POST['mb_lang']."\r\n";
      $from20 = $_POST['mb_email'];
      mail($to20, $subject20, $message20,
      "Return-Path: $from20\r\n"
      ."From: $from20\r\n"
      ."Reply-To: $from20\r\n"
      ."X-Mailer: PHP/" . phpversion());


      $mb_date = dateFr(date("Y-m-d"),$_SESSION['lang']);
      $message = "<table width='700' align='center' border='0' cellspacing='0' cellpadding='2' class='TABLE1'><tr>";
      $message.= "<td>";
      $message.= MERCI.",<br>";
      $message.= "<b>".VOUS_AVEZ_ENVOYE_LA_DEMANDE_ACTIVATION_MB_LE." ".$mb_date.".</b><br>";
      $message.= SOYEZ_CONSCIENT_QUE_LA_DEMANDE_DACTIVATION."<br>";
      $message.= VOUS_SEREZ_CONTACTE_PAR_MB;
      $message.= "</td>";
      $message.= "</tr></table>";
      $message.= "<form method='POST' action='site_config.php'>";
      $message.= "<p align='center'><input type='submit' class='knop' value='".RETOUR."'></p>";
      $message.= "</form>";
      }
}
?>
<html>
<head>
<script language="javascript">
function formmb() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var mb_firstname = document.form101.mb_firstname.value;
  var mb_lastname = document.form101.mb_lastname.value;

  if(document.form101.elements['mb_firstname'].type != "hidden") {
    if(mb_firstname == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['mb_lastname'].type != "hidden") {
    if(mb_lastname == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error11 = 1;
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
<title>MoneyBookers Quick Checkout Activation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>
<?php
if(isset($message) AND $message!=="") {
   print $message;
   if(isset($messageExit) AND $messageExit==1) exit;
}
else {
$currentUrl = $_SERVER['SERVER_NAME'];
if(!preg_match("#localhost#i", $currentUrl) AND !preg_match("#127.0.0.1#i", $currentUrl)) {

  if($mbId=="" OR $mbEmail=="") {
      print "<p class='fontrouge' align='center'>";
      if($mbId=="") {
         print "<b>".VEUILLEZ_ENTRER_ID."</b>.";
      }
      if($mbEmail=="") {
         if($mbId=="") print "<br>";
         print "<b>".VEUILLEZ_ENTRER_EMAIL."</b>.";
      }
      print "<form method='POST' action='site_config.php#".PAIEMENT." MONEYBOOKERS'>";
      print "<div align='center'><input type='submit' value='".RETOUR."'></div>";
      print "</form>";
      print "</p>";
      exit;
  }

  if($_SESSION['lang']==1) $mb_lang="FR";
  if($_SESSION['lang']==2) $mb_lang="EN";
  if($_SESSION['lang']==3) $mb_lang="NL";

  $pass = "f71dbe52628a3f83a77ab494817525c6";
  $fileName = "https://www.moneybookers.com/app/email_check.pl?email=".$mbEmail."&cust_id=3286296&password=".$pass;
  $tyty = @file_get_contents($fileName);
 
  $mb_result = explode(",",$tyty);
  if($mb_result[0]=="OK") {
        print "<p align='center'><span style='background:#CC0000; padding:5px; color:#FFFFFF; border:1px #FFFFFF solid'><b>RESULTAAT: ".$tyty."</b></span></p><br>";
        print "<table width='700' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr>";
        print "<td>";
        print "&bull;&nbsp;".APRES_ACTIVATION."<br>";
        print "&bull;&nbsp;".APRES_VALIDATION_INSCRIPTION."<br>";
        print "</td>";
        print "</tr></table>";
        print "<br>";
        // Activation valid
        print "<table align='center' border='0' cellspacing='0' cellpadding='2'><tr><td>";
        print "<form method='POST' action='moneybookers_aanvraag.php' name='form101' onsubmit='return formmb()';>";
        print "<input type='hidden' name='merchant_fields' value='referring_platform'>";
        print "<input type='hidden' name='referring_platform' value='boutikone'>";
        print "<input type='hidden' name='mb_email' value='".$mbEmail."'>";
        print "<input type='hidden' name='mb_id' value='".$mb_result[1]."'>";
        print "<input type='hidden' name='mb_url' value='http://".$www2.$domaineFull."'>";
        print "<input type='hidden' name='mb_lang' value='".$mb_lang."'>";
        print "<input type='hidden' name='mb_action' value='activate'>";
        print NOM_PRENOM." : <input type='text' name='mb_lastname' value=''>,&nbsp;<input type='text' name='mb_firstname' value=''>";
        print "<p align='center'><input type='submit' class='knop' value='".SEND_ACTIVATION_REQUEST."'></p>";
        print "</form>";
        print "</td></tr></table>";
  }
  else {
        // Activatie niet geldig 
        print "<p class='fontrouge' align='center'>";
        print "<b>".PARAMETRES_COMPTE_NOT_VALID."</b>";
        print "</p>";
        print "<table align='center' border='0' cellspacing='0' cellpadding='2' class='TABLE1'><tr>";
        print "<td>".EMAIL_COMPTE_MB."</td><td>&nbsp;".$mbEmail."</td></tr><tr>";
        print "<td>".ID_COMPTE_MB."</td><td>&nbsp;".$mbId."</td></tr><tr>";
        print "</tr></table>";
        print "<br>";
        print "<form method='POST' action='site_config.php#".PAIEMENT." MONEYBOOKERS'>";
        print "<div align='center'><input type='submit' value='".RETOUR."'></div>";
        print "</form>";
        print "</p>";
        exit;
  }
}
else {
        // Activatie geldig
        print "<p class='fontrouge' align='center'>";
        print "<b>".ACTIVATION_EN_LIGNE_ONLY."</b>";
        print "</p>";
        print "<form method='POST' action='site_config.php#".PAIEMENT." MONEYBOOKERS'>";
        print "<div align='center'><input type='submit' value='".RETOUR."'></div>";
        print "</form>";
        exit;
}
}
?>
</body>
</html>
