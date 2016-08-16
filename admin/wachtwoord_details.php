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

if(isset($_POST['admin1']) AND $_POST['admin1']!=="" AND isset($_POST['motdepasse1']) AND $_POST['motdepasse1']!=="" AND isset($_POST['admin1_2']) AND $_POST['admin1_2']!=="" AND isset($_POST['motdepasse1_2']) AND $_POST['motdepasse1_2']!=="") {
   $_POST['admin1_2'] = str_replace("|", "*", $_POST['admin1_2']);
   $_POST['motdepasse1_2'] = str_replace("|", "*", $_POST['motdepasse1_2']);
   $update = mysql_query("UPDATE admin 
   							SET 
							   admin='".$_POST['admin1']."', 
							   motdepasse='".$_POST['motdepasse1']."',
							   admin_2 = '".$_POST['admin1_2']."|".$_POST['motdepasse1_2']."'
							WHERE id='1'");
   $message = "[<u>".ACCES_ADMIN."</u>]<br>Login: <b>".$_POST['admin1']."</b><br>".A1.": <b>".$_POST['motdepasse1']."</b><br>".A2."<br><hr>";
   $message.= "[<u>".ACCES_LIMITE."</u>]<br>Login: <b>".$_POST['admin1_2']."</b><br>".A1.": <b>".$_POST['motdepasse1_2']."</b><br>".A2;
}
else {
   $message = "<b>".VOUS_DEVEZ_ENTRER_UN_LOGIN_ET_UN_MOTEDEPASSE."</b>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A3;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0">
       <tr>
        <td align="center" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
         <FORM action="wachtwoord_wijzigen.php">
          <INPUT type="submit" class="knop" VALUE=" OK ">
         </form>
        </td>
       </tr>
</table>
  </body>
  </html>

