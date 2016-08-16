<?php
include('../configuratie/configuratie.php');
$dir="../";
include('functions.php');

$_SESSION['lang'] = $_GET['lang'];



include("../includes/lang/lang_".$_SESSION['lang'].".php");
$title = $store_name;

 
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}
 
if(isset($_POST['actions']) and $_POST['actions'] == "send") {
   include('../configuratie/configuratie.php');
   
   $message = "Onderwerp: CONTACT\r\n---------------------------------------------------\r\n";
   $message .= "Naam: ".$_POST['firstname']."\r\n";
   $message .= "Voornaam: ".$_POST['name']."\r\n\r\n";
   $message .= "E-mail: ".$_POST['email']."\r\n\r\n";
   $message .= "Commentaai: ".$_POST['comments']."";

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
?>

<script type="text/javascript"><!--
function check_form() {

  var error = 0;
  var error_message = "";

  var firstname = document.toto.firstname.value;
  var name = document.toto.name.value;
  var email = document.toto.email.value;
  var comments = document.toto.comments.value;


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
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="<?php echo $_SESSION['css'];?>" type="text/css">
    </head>
    
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="450" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
    <td colspan="3" valign="top" height="78%">
    
      <table width="300" align="center" height="70%" border="0" cellpadding="3" cellspacing="5">
        <?php
            $resultClosed = mysql_query("SELECT shop_closed FROM admin");
            $rowpClosed = mysql_fetch_array($resultClosed);
        if(!empty($rowpClosed['shop_closed'])) {
        ?>
        <tr>
          <td height="100" valign="middle" align="center" class="PromoFontColorNumber">
                <?php print $rowpClosed['shop_closed'];?>
          </td>
          </tr>
        <?php
        }
        ?>
          <tr>
          <td valign="top" class="TABLEPageCentreProducts" style="padding-top:20px">
<p align="center"><?php print REMPLICEZ_CE_FORMULAIRE;?></p>
                    <form action="shop_gesloten.php?lang=<?php print $_SESSION['lang'];?>&css=scss" method="POST" name="toto" onsubmit="return check_form()">
                      <table border="0" cellpadding="0" cellspacing="8" align="center" width="400">
                    
                      <tr valign="middle">
                              <td width="17%"><?php print NOM;?>:</td>
                              <td width="83%" colspan="2">
                                <div align="left">
                                  <input type="text" size="20" maxlength="75" name="name">
                                  <b>*</b></div>
                              </td>
                            </tr>
                            <tr valign="middle">
                              <td width="17%"><?php print PRENOM;?>:</td>
                              <td width="83%" colspan="2">
                                <input type="text" size="20" maxlength="75" name="firstname">
                                <b> *</b></td>
                            </tr>
                            <tr valign="middle">
                              <td bccolor="FFFF99" width="17%" ><span><?php print ADRESSE_EMAIL;?>:</b></td>
                              <td width="83%" colspan="2">
                                <div align="left">
                                  <input type="text" size="20" maxlength="256" name="email" rows="1">
                                  <b>*</b></div>
                              </td>
                            </tr>
                            <tr valign="middle">
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
</table>      

</body>
</html>
