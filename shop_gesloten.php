<?php
session_start();
include('configuratie/configuratie.php');
include('includes/doctype.php');

$_SESSION['lang'] = $langue;
$_SESSION['storeWidthUser']=$storeWidth;
$_SESSION['userInterface'] = $colorInter;


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = $store_name;


function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

 
function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src='".$image."' border='0'>";    
    }
    else {
       $returnImage = "<img src='".$image."' border='0' width='".$wi."' height='".$he."'>";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}

if(isset($_POST['actions']) and $_POST['actions'] == "send") {

if(function_exists("gd_info") AND !ini_get("safe_mode")) {
   if(isset($_POST['code']) AND !empty($_POST['code'])) {
   $_POST['codeGen'] = substr($_POST['codeGen'], 0, 5);
      if($_POST['code'] == $_POST['codeGen']) {
       $message = "Onderwerp: CONTACT\r\n---------------------------------------------------\r\n";
       $message .= "Naam: ".$_POST['firstname']."\r\n";
       $message .= "Voornaam: ".$_POST['name']."\r\n\r\n";
       $message .= "E-mail: ".$_POST['email']."\r\n\r\n";
       $message .= "Commentaar: ".$_POST['comments']."";
    
       $to = $mailPerso;
       $subject = "CONTACT | ".$domaineFull;
       mail($to, $subject, rep_slash($message),
          "Return-Path: ".$_POST['email']."\r\n"
          ."From: ".$_POST['email']."\r\n"
          ."Reply-To: ".$_POST['email']."\r\n"
          ."X-Mailer: PHP/" . phpversion());
?>
<script type="text/javascript">
<!--
 alert ('<?php print VOTRE_COURRIER_A_ETE_ENVOYE_AVEC_SUCCES;?>');
//-->
</script>
<?php
         $messageSendEmail = VOTRE_COURRIER_A_ETE_ENVOYE_AVEC_SUCCES." !";
      }
      else {
         $messageSendEmail = CODE_NOT_VALID;
      }
   }
}
else {
   $message = "Onderwerp: CONTACT\r\n---------------------------------------------------\r\n";
   $message .= "Naam: ".$_POST['firstname']."\r\n";
   $message .= "Voornaam: ".$_POST['name']."\r\n\r\n";
   $message .= "E-mail: ".$_POST['email']."\r\n\r\n";
   $message .= "Commentaar: ".$_POST['comments']."";

   $to = $mailPerso;
   $subject = "CONTACT | ".$domaineFull;
   mail($to, $subject, rep_slash($message),
       "Return-Path: ".$_POST['email']."\r\n"
       ."From: ".$_POST['email']."\r\n"
       ."Reply-To: ".$_POST['email']."\r\n"
       ."X-Mailer: PHP/" . phpversion());
?>
<script type="text/javascript">
<!--
 alert ('<?php print VOTRE_COURRIER_A_ETE_ENVOYE_AVEC_SUCCES;?>');
//-->
</script>
<?php
}
}

 
function generer_code($car) {
  $string = "";
  $chaine = "adefghkmnuvwxyzwxyz23458";
  for($i=0; $i<$car; $i++) {
    $string .= substr($chaine, rand(0, strlen($chaine) - 1), 1);;
  }
  return $string;
}
$code = generer_code(5);
$code2 = generer_code(5);
?>

<script type="text/javascript"><!--
function check_form() {

  var error = 0;
  var error_message = "";

  var firstname = document.toto.firstname.value;
  var name = document.toto.name.value;
  var email = document.toto.email.value;
  var comments = document.toto.comments.value;
<?php
if(function_exists("gd_info") AND !ini_get("safe_mode")) {
?>
  var code = document.toto.code.value;
<?php 
} else {
?>
  var clientV = document.toto.clientV.value;
<?php
} 
?>

  if(document.toto.elements['firstname'].type != "hidden") {
    if(firstname == '' || firstname.length < 2) {
      error_message = error_message + "* <?php print CHAMPS_NON_VALIDE;?> '<?php print PRENOM;?>'\n";
      error = 1;
    }
  }

  if(document.toto.elements['name'].type != "hidden") {
    if(name == '' || name.length < 2) {
      error_message = error_message + "* <?php print CHAMPS_NON_VALIDE;?> '<?php print NOM;?>'\n";
      error = 1;
    }
  }


  if(document.toto.elements['email'].type != "hidden") {
    if(email == '' || email.length < 6 || email.indexOf ('@') == -1 || email.indexOf ('.') == -1 ) {
      error_message = error_message + "* <?php print CHAMPS_NON_VALIDE;?> '<?php print ADRESSE_EMAIL;?>'\n";
      error = 1;
    }
  }

  if(document.toto.elements['comments'].type != "hidden") {
    if(comments == '' || comments.length < 2) {
      error_message = error_message + "* <?php print VEUILLEZ_LAISSER_UN_MESSAGE;?>.\n";
      error = 1;
    }
  }
<?php
if(function_exists("gd_info") AND !ini_get("safe_mode")) {
?>
  if(document.toto.elements['code'].type != "hidden") {
    if(code == '' || code.length < 5) {
      error_message = error_message + "* <?php print CHAMPS_NON_VALIDE;?> '<?php print CODE_ANTISPAM;?>'\n";
      error = 1;
    }
  }
<?php 
}
else {
?>
  if(document.toto.elements['clientV'].type != "hidden") {
    if(clientV !== '') {
      error_message = error_message + "* <?php print CHAMPS_NON_VALIDE;?> '<?php print LEAVE_CHAMPS_EMPTY;?>'.\n";
      error = 1;
    }
  }
<?php
}
?>

  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="<?php print $_SESSION['storeWidthUser'];?>" height="100%" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
    <tr>
    <td width="1" class="borderLeft"></td>
    <td valign="top">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
 
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}
?>
    

    <td colspan="3" valign="top" height="78%">
    
      <table width="300" align="center" height="70%" border="0" cellpadding="3" cellspacing="5">
        <?php
            $resultClosed = mysql_query("SELECT shop_closed FROM admin");
            $rowpClosed = mysql_fetch_array($resultClosed);
        if(!empty($rowpClosed['shop_closed'])) {
        ?>
        <tr>
          <td height="100" valign="middle" align="center" class="PromoFont">
                <?php print $rowpClosed['shop_closed'];?>
          </td>
          </tr>
        <?php
        }
        ?>
          <tr>
          <td valign="top" class="TABLEPageCentreProducts" style="padding-top:20px">
<p align="center"><?php print REMPLICEZ_CE_FORMULAIRE;?></p>
<?php
if(isset($messageSendEmail) AND !empty($messageSendEmail)) print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".$messageSendEmail."</p>";
?>
<form action="shop_gesloten.php" method="POST" name="toto" onsubmit="return check_form()">
                      <table border="0" cellpadding="0" cellspacing="8" align="center" width="600">
                    
                      <tr valign="middle">
                              <td width="25%"><?php print NOM;?>:</td>
                              <td width="75%" colspan="2">
                                <div align="left">
                                  <input type="text" size="20" maxlength="75" name="name">
                                  <b>*</b></div>
                              </td>
                            </tr>
                            <tr valign="middle">
                              <td width="25%"><?php print PRENOM;?>:</td>
                              <td width="75%" colspan="2">
                                <input type="text" size="20" maxlength="75" name="firstname">
                                <b> *</b></td>
                            </tr>
                            <tr valign="middle">
                              <td bccolor="FFFF99" width="17%" ><span><?php print ADRESSE_EMAIL;?>:</b></td>
                              <td width="75%" colspan="2">
                                <div align="left">
                                  <input type="text" size="20" maxlength="256" name="email" rows="1">
                                  <b>*</b></div>
                              </td>
                            </tr>
                            <tr valign="top">
                              <td height="92" width="17%"><?php print MESSAGE;?>:</td>
                              <td height="92" width="41%">
                                <div align="left">
                                  <textarea name="comments" rows="7" cols="40"></textarea>
                                  <input type="hidden" name="title" value="Communication client">
                                </div>
                              </td>
                              <td height="92" width="42%">
                                <div align="left"><b>*</b></div>
                              </td>
                            </tr>
         <?php
         if(function_exists("gd_info") AND !ini_get("safe_mode")) {
         ?>
        <tr valign="middle">
          <td bccolor="FFFF99" width="20%" >&nbsp;</td>
          <td valign="middle" width="80%" colspan="2">
               <table border="0" cellpadding="5" cellspacing="0" align="left" w/idth="400">
               <tr>
               <td><img src="includes/toeren.php?tur=<?php print $code;?>">
                  <br><a href='shop_gesloten.php'><?php print CODE_ILLISIBLE;?></a>
                  <br><i><?php print RECOPIER;?></i>
               </td>
               </tr><tr>
               <input type="hidden" name="codeGen" value="<?php print $code.$code2;?>">
              <td valign='center'><input type="text" size="10" maxlength="5" name="code"> <b>*</b></td>
              </tr>
              </table>
          </td>
        </tr>
        <?php
        }
        else {
        ?>
        <tr valign="middle">
          <td>&nbsp;</td><td align="left" colspan="2"><input type="hidden" name="clientV" size="5" value=""></td>
        </tr>
        <?php
        }
        ?>
                            <tr valign="top">
                              <td colspan="3">
                                <div align="center">
                            <input type="hidden" name="actions" value="send">
                            <input type="submit" value="<?php print ENVOYER;?>">
                    
                            <input type="reset" value="<?php print ANNULER;?>" name="reset">
                                </div>
                              </td>
                            </tr>
                    
                          </table>
</form>
          </td>
        </tr>
      </table>

    </td>
    </tr>
    <td colspan="3" valign="bottom">
    </td>
  </tr>
</table>      



</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>
