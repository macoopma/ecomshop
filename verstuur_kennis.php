<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = ENVOYER_A_UN_AMI;

if(isset($_POST['url']) AND $_POST['url']!=="") $_POST['url'] = str_replace("deion.php","beschrijving.php",$_POST['url']);

if(isset($_GET['fromUrl'])) {
    $wwqq = str_replace("|","&",$_GET['fromUrl']);
    $wwqq = str_replace(" ","%20",$wwqq);
    $wwqq = str_replace("descXXX","description",$wwqq);
    $wwqq = "http://".$www2.$domaine.$wwqq;
 
	$_SESSION['ActiveUrl'] = $_GET['fromUrl'];
 
}
else {
   if(isset($_POST['url']) AND ($_POST['url'])!=="") {
      $wwqq = $_POST['url'];
   }
}


if(isset($_POST['action']) AND $_POST['action']=="send") {

  if(function_exists("gd_info") AND !ini_get("safe_mode")) {
      if(isset($_POST['code']) AND !empty($_POST['code'])) {
          $_POST['codeGen'] = substr($_POST['codeGen'], 0, 5);
          if($_POST['code'] == $_POST['codeGen']) {
              $to= $_POST['emailTo'];
              $from= $_POST['emailFrom']."\r\n";
              $subject = MESSAGE_DE." ".$_POST['nom'];
              $message = BONJOUR.",\r\n\r\n";
              $message .= CET_ARTICLE_EST_SUSCEPTIBLE_DE_VOUS_INTERESSER."\r\n";
              $message .= $_POST['nom']." ".VOUS_INVITE_A_VOUS_RENDRE."\r\n";
              $message .= $_POST['url']."\r\n\r\n";
              if(!empty($_POST['comment'])) {
                  $message .= LE_MESSAGE_SUIVANT_A_ETE_AJOUTE."\r\n";
                  $message .= strip_tags($_POST['comment'])."\r\n";
              }
      
              mail($to, strip_tags($subject), rep_slash($message),
              "Return-Path: $from\r\n"
              ."From: $from\r\n"
              ."Reply-To: ".$_POST['emailFrom']."\r\n"
              ."X-Mailer: PHP/" . phpversion());
              
              $meesssage = PAGE_ENVOYE." !";
              $uuuu='true';
          }
          else {
             $meesssage = CODE_NOT_VALID;
             $uuuu='false';
          }
      }
  }
  else {
          $to= $_POST['emailTo'];
          $from= $_POST['emailFrom']."\r\n";
          $subject = MESSAGE_DE." ".$_POST['nom'];
          $message = BONJOUR.",\r\n\r\n";
          $message .= CET_ARTICLE_EST_SUSCEPTIBLE_DE_VOUS_INTERESSER."\r\n";
          $message .= $_POST['nom']." ".VOUS_INVITE_A_VOUS_RENDRE."\r\n";
          $message .= $_POST['url']."\r\n\r\n";
          if(!empty($_POST['comment'])) {
               $message .= LE_MESSAGE_SUIVANT_A_ETE_AJOUTE."\r\n";
               $message .= strip_tags($_POST['comment'])."\r\n";
          }
  
          mail($to, strip_tags($subject), rep_slash($message),
          "Return-Path: $from\r\n"
          ."From: $from\r\n"
          ."Reply-To: ".$_POST['emailFrom']."\r\n"
          ."X-Mailer: PHP/" . phpversion());
          
          $meesssage = PAGE_ENVOYE." !";
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
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="10" marginwidth="10" marginheight="10">
<script type="text/javascript">
function formk() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var nom = document.friend.nom.value;
  var emailFrom = document.friend.emailFrom.value;
  var emailTo = document.friend.emailTo.value;
<?php
if(function_exists("gd_info") AND !ini_get("safe_mode")) {
?>
  var code = document.friend.code.value;
<?php 
} else {
?>
  var clientV = document.friend.clientV.value;
<?php
}
?>

  if(document.friend.elements['nom'].type != "hidden") {
    if(nom == '' || nom.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NOM;?>'.\n";
      error11 = 1;
    }
  }

  if(document.friend.elements['emailFrom'].type != "hidden") {
    if(emailFrom == '' || emailFrom.length < 6 || emailFrom.indexOf ('@') == -1 || emailFrom.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error11 = 1;
    }
  }
  
  if(document.friend.elements['emailTo'].type != "hidden") {
    if(emailTo == '' || emailTo.length < 6 || emailTo.indexOf ('@') == -1 || emailTo.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error11 = 1;
    }
  }

<?php
if(function_exists("gd_info") AND !ini_get("safe_mode")) {
?>
  if(document.friend.elements['code'].type != "hidden") {
    if(code == '' || code.length < 5) {
      error_message11 = error_message11 + "* <?php print CHAMPS_NON_VALIDE;?> <?php print CODE_ANTISPAM;?>\n";
      error11 = 1;
    }
  }
<?php 
}
else {
?>
  if(document.friend.elements['clientV'].type != "hidden") {
    if(clientV !== '') {
      error_message11 = error_message11 + "* <?php print CHAMPS_NON_VALIDE;?> <?php print LEAVE_CHAMPS_EMPTY;?>\n";
      error11 = 1;
    }
  }
<?php
}
?>

  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//-->
</script>

<?php 
if(isset($meesssage)) print "<div align='center' style='background:#FFFFFF; color:#FF0000; padding:5px'><b>".$meesssage."</b></div><br>";
if(isset($_POST['nom'])) $nom1=$_POST['nom']; else $nom1="";
if(isset($_POST['emailFrom'])) $emailFrom1=$_POST['emailFrom']; else $emailFrom1="";
if(isset($_POST['emailTo'])) $emailTo1=$_POST['emailTo']; else $emailTo1="";
if(isset($_POST['comment'])) $comment1=$_POST['comment']; else $comment1="";
?>

<table width="80%" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLESousMenuPageCategory">
<form action="verstuur_kennis.php?fromUrl=<?php print $_GET['fromUrl'];?>" method="POST" name="friend" onSubmit="return formk()">
<input type="hidden" name="action" value="send">

<input type="hidden" name="url" value="<?php print $wwqq;?>">
  
  
  <tr> 
    <td height="20" colspan="2" align="center" style="BACKGROUND-COLOR: #f1f1f1; border-bottom:3px #2661A9 double"><b><?php print maj(ENVOYER_CETTE_UN_AMI);?></b></td>
  </tr>
  <tr> 
    <td width="200"><?php print SEFRIENNOM;?></td>
    <td><input type="text" name="nom" value="<?php print $nom1;?>">
      * </td>
  </tr>
  <tr> 
    <td width="200"><?php print VOTRE_EMAIL;?></td>
    <td><input type="text" name="emailFrom" value="<?php print $emailFrom1;?>">
      * </td>
  </tr>
  <tr> 
    <td width="200"><?php print EMAIL_AMI;?></td>
    <td><input type="text" name="emailTo" value="<?php print $emailTo1;?>">
      * </td>
  </tr>
  <tr> 
    <td width="200" valign="top"><?php print COMMENTAIRES;?></td>
    <td><textarea name="comment" rows="4"><?php print $comment1;?></textarea></td>
  </tr>
         <?php
         if(function_exists("gd_info") AND !ini_get("safe_mode")) {
         ?>
        <tr valign="middle">
          <td bccolor="FFFF99" width="50%" >&nbsp;</td>
          <td valign="middle" width="80%" colspan="2">111
               <table border="0" cellpadding="5" cellspacing="0" align="left" width="400">
               <tr>
               <td><img src="includes/toeren.php?tur=<?php print $code;?>">
                  <br><a href='verstuur_kennis.php?fromUrl=<?php print $_GET['fromUrl'];?>'><?php print CODE_ILLISIBLE;?></a>
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
         <input type="hidden" name="clientV" size="5" value=""></td>
        </tr>
        <?php
        }
        ?>
  <tr> 
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="<?php print ENVOYER;?>"></td>
  </tr>
</form>
  <tr>
  </tr>
</table>

</body>
</html>
