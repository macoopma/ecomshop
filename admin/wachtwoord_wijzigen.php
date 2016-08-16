<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");


if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Geen toegang</title>";
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

$query = mysql_query("SELECT admin, motdepasse, admin_2 FROM admin") or die (mysql_error());
$myrow = mysql_fetch_array($query);

if(isset($myrow['admin_2']) AND $myrow['admin_2']!=="") {
	$limitedPsw = explode("|",$myrow['admin_2']);
}
else {
	$limitedPsw[0] = "admin";
	$limitedPsw[1] = "admin";
}
$action = "wachtwoord_wijzigen.php";
$bouton = "OK";
$message= array();
$titreTop = " ".ENTREZ;
if(!isset($_POST['admin1']) OR $_POST['admin1']=="") $_POST['admin1']="";
if(!isset($_POST['motdepasse1']) OR $_POST['motdepasse1']=="") $_POST['motdepasse1']="";

 
if(isset($_GET['sendAccess']) AND $_GET['sendAccess']==1 AND $_SESSION['user']=="admin") {
	$subj = RECUPERATION_ACCESS." - ".$store_name;
	$messageToSend = ACCES_ADMIN." : ".$myrow['admin']."|".$myrow['motdepasse']."\r\n".ACCES_LIMITE." : ".$myrow['admin_2'];

	   mail($mailPerso, $subj, $messageToSend,
	   "Return-Path: $mailPerso\r\n"
       ."From: $mailPerso\r\n"
       ."Reply-To: $mailPerso\r\n"
       ."X-Mailer: PHP/" . phpversion());
  
		$messageRecupAccess = ACCES_SENT_TO." ".$mailPerso." ".AVEC_SUCESSS;
}


 
	if((!isset($_POST['admin1']) OR $_POST['admin1']=="") OR (!isset($_POST['admin1_2']) OR $_POST['admin1_2']=="")) {
		if(!isset($_POST['admin1']) OR $_POST['admin1']=="") $message[] = "<div class='fontrouge'><span style='color:#000000'>1.&nbsp;</span>[ ".ACCES_ADMIN." ] - ".VOTRE_LOGIN."</div>";
		if(!isset($_POST['admin1_2']) OR $_POST['admin1_2']=="") $message[] = "<div class='fontrouge'><span style='color:#000000'>3.&nbsp;</span>[ ".ACCES_LIMITE." ] - ".VOTRE_LOGIN."</div>";
		$checkLogin = "notok";
	}
	else {
		$checkLogin = "ok";
	}
	if((!isset($_POST['motdepasse1']) OR $_POST['motdepasse1']=="") OR (!isset($_POST['motdepasse1_2']) OR $_POST['motdepasse1_2']=="")) {
		if(!isset($_POST['motdepasse1']) OR $_POST['motdepasse1']=="") $message[] = "<div class='fontrouge'><span style='color:#000000'>2.&nbsp;</span>[ ".ACCES_ADMIN." ] - ".VOTRE_MDP."</div>";
		if(!isset($_POST['motdepasse1_2']) OR $_POST['motdepasse1_2']=="") $message[] = "<div class='fontrouge'><span style='color:#000000'>4.&nbsp;</span>[ ".ACCES_LIMITE." ] - ".VOTRE_MDP."</div>";
		$checkMotdepasse = "notok";
	}
	else {
		$checkMotdepasse = "ok";
	}

 
	if($checkLogin == "ok" AND $checkMotdepasse == "ok") {
		if($_POST['admin1'] !== $myrow['admin']) {
			$message[] = "<div>1.&nbsp;<span class='fontrouge'>[".ACCES_ADMIN."] - <b>".LOGIN_INCORRECT."</b></span></div>";
			$loginOk = "notok";
		}
		else {
			$message[] = "<div>1.&nbsp;[".ACCES_ADMIN."] - ".LOGIN_CORRECT."</div>";
			$loginOk = "ok";
		}

		if($_POST['admin1_2'] !== $limitedPsw[0]) {
			$message[] = "<div>3.&nbsp;<span class='fontrouge'>[".ACCES_LIMITE."] - <b>".LOGIN_INCORRECT."</b></span></div>";
			$login_2Ok = "notok";
		}
		else {
			$message[] = "<div>3&nbsp;[".ACCES_LIMITE."] - ".LOGIN_CORRECT."</div>";
			$login_2Ok = "ok";
		}
		
		if($_POST['motdepasse1'] !== $myrow['motdepasse']) {
			$message[] = "<div>2.&nbsp;<span class='fontrouge'>[".ACCES_ADMIN."] - <b>".MDP_INCORRECT."</b></span></div>";
			$motdepasseOk = "notok";
		}
		else {
			$message[] = "<div>2.&nbsp;[".ACCES_ADMIN."] - ".MDP_CORRECT."</div>";
			$motdepasseOk = "ok";
		}

		if($_POST['motdepasse1_2'] !== $limitedPsw[1]) {
			$message[] = "<div>4.&nbsp;<span class='fontrouge'>[".ACCES_LIMITE."] - <b>".MDP_INCORRECT."</b></span></div>";
			$motdepasse_2Ok = "notok";
		}
		else {
			$message[] = "<div>4.&nbsp;[".ACCES_LIMITE."] - ".MDP_CORRECT."</div>";
			$motdepasse_2Ok = "ok";
		}
	}
	
	if((isset($loginOk) AND $loginOk == "ok" AND isset($motdepasseOk) AND $motdepasseOk == "ok") AND (isset($login_2Ok) AND $login_2Ok == "ok" AND isset($motdepasse_2Ok) AND $motdepasse_2Ok == "ok")) {
		$message2Z = "<p align='center'><span style='font-size:13px; color:#FF6600; background:#ffffff; padding:3px; border:#FF6600 1px solid;'><img src='im/note.gif' align='absmiddle'>&nbsp;".VEUILLEZ_ENTRER_UN_NOUVEAU_LOGIN_ET_MDP."</span></p>";
		$action = "wachtwoord_details.php";
		$bouton = METTRE_A_JOUR;
		$titreTop = "&nbsp;".MODIFIEZ;
	}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1Z;?></p>
<p align="center" class="largeBold"><?php print $titreTop." ".A9;?></p>

<?php
if(isset($message) AND count($message)>0) {
	sort($message);
	print "<p align='center'>";
		print "<table width='700' border='0' align='center' cellpadding='8' cellspacing='0'><tr>";
		print "<td style='background:#FFFFFF; border:#CCCCCC 1px solid;'>";
		foreach($message AS $m) {
			print $m;
		}
		print "</td>";
		print "</tr></table>";
		if(isset($message2Z)) print $message2Z;
	print "</p>";
}


if(isset($messageRecupAccess)) { print "<p align='center' class='fontrouge'><b>".$messageRecupAccess."</b></p>";}
?>


<form action="<?php print $action;?>" method="POST">

	<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
	<tr>

	<td colspan="2"><b>&bull;&nbsp;<span style='color:#FF0000; font-size:12px;'><?php print ACCES_ADMIN;?></span></b></td>
	</tr>
	<tr>
	<td>Login</td>
<?php
if(isset($_POST['admin1']) AND isset($loginOk) AND $loginOk=="ok" AND $bouton=="OK") $log1=$_POST['admin1']; else $log1="";
?>
	<td><input type="text" name="admin1" value="<?php print $log1;?>"></td>
	</tr>
	<tr>
	<td><?php print A10;?></td>
<?php
if(isset($_POST['motdepasse1']) AND isset($motdepasseOk) AND $motdepasseOk=="ok" AND $bouton=="OK") $mdp1=$_POST['motdepasse1']; else $mdp1="";
?>
	<td><input type="password" name="motdepasse1" value="<?php print $mdp1;?>"></td>
	</tr>
	<tr>
	
 
	<td colspan="2"><b>&bull;&nbsp;<span style='color:#FF0000; font-size:12px;'><?php print ACCES_LIMITE;?></span></b></td>
	</tr>
	<tr>
	<td>Login</td>
<?php
if(isset($_POST['admin1_2']) AND isset($login_2Ok) AND $login_2Ok=="ok" AND $bouton=="OK") $log1_2=$_POST['admin1_2']; else $log1_2="";
?>
	<td><input type="text" name="admin1_2" value="<?php print $log1_2;?>"></td>
	</tr>
	<tr>
	<td><?php print A10;?></td>
<?php
if(isset($_POST['motdepasse1_2']) AND isset($motdepasse_2Ok) AND $motdepasse_2Ok=="ok" AND $bouton=="OK") $mdp1_2=$_POST['motdepasse1_2']; else $mdp1_2="";
?>
	<td><input type="password" name="motdepasse1_2" value="<?php print $mdp1_2;?>"></td>
	</tr>
	<tr>
	<td colspan="2"><input type="submit" class="knop" value="<?php print $bouton;?>"></td>
	</tr>
	</table>
</form>


<div align="center"><a href="wachtwoord_wijzigen.php?sendAccess=1"><?php print RECUPERATION_ACCESS;?></a><br><?php print UN_EMAIL_SERA_ENVOYE_A;?> <?php print $mailPerso;?></div><br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing ="0" style='background:#ffffff; border:#CCCCCC 1px dotted;'><tr>
<td></td>
</tr>
<tr>
<td><?php print ACCES_NOTE;?></td>
</tr>
</table>
<br><br><br>
</body>
</html>

