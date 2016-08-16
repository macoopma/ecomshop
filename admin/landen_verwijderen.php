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
$message = "";
$c = "";
$selChecked='';


if(isset($_POST['action']) AND $_POST['action']==A1) {
	if(isset($_POST['checkCom']) AND count($_POST['checkCom'])>0) {
		$ids = implode(', ', $_POST['checkCom']);
		foreach($_POST['checkCom'] AS $it) {
			$query2Z = mysql_query("SELECT countries_name FROM countries WHERE countries_id='".$it."'") or die (mysql_error());
			$result2Z = mysql_fetch_array($query2Z);
			$paysName[] = $result2Z['countries_name'];
		}
		$ids2 = implode(', ', $paysName);
		$message = "<br><table border='0' cellpadding='5' cellspacing='0' align='center'><tr><td>";
		$message.= "<span style='color:#FF0000'>".A1." <b>".$ids2."</b> ?</span><br><br>";
		$message.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
		$message.= "<input type='hidden' name='do' value='delete'>";
		$i=-1;
		foreach($_POST['checkCom'] AS $it) {
			$i++;
			$message.= "<input type='hidden' name='what[".$i."]' value='".$it."'>";
		}
		$message.= "<div align='center'><input type='submit' name='confirm' class='knop' value='".OUI."'> | <input type='submit' class='knop'name='confirm' value='".NON."'></div>";
		$message.= "</form>";
		$message.= "</td></tr></table><br>";
		$ids2 = $_POST['checkCom'];
		$hide = 1;
    }
    else {
    	$message = "<table border='0' cellpadding='7' width='700' cellspacing='0' align='center' class='TABLE'><tr><td><b>".A4."</b></td></tr></table>";
    }
}

if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI AND isset($_POST['what']) AND count($_POST['what'])>0) {
   foreach($_POST['what'] AS $itemW) {
      mysql_query("DELETE FROM countries WHERE countries_id='".$itemW."'");
      $idz[] = $itemW;
   }
   (count($idz)>1 AND $_SESSION['lang']==1)? $s="en" : $s="";
 
   $message.= "<center><br><b><center>".PAYS.$s." ".SUPPRIMEEEE."</b></font><br><br>&nbsp;";

   $hide=1;
   header("Location: landen_verwijderen.php?hide=1&message=".$message);
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
var checkflag = "false";
function check(field) {
	if (checkflag == "false") {
		for (i = 0; i < field.length; i++) {
		field[i].checked = true;}
		checkflag = "true";
		return "Tout décocher";
	}
	else {
		for (i = 0; i < field.length; i++) {
		field[i].checked = false; }
		checkflag = "false";
		return "Tout cocher";
	}
}
//  End -->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A2;?></p>

<?php

if(isset($message) AND !empty($message) OR (isset($_GET['message']) AND !empty($_GET['message']))) {
	if(isset($_GET['message'])) $message = $_GET['message'];
	print "<table border='0' width='700' class='TABLE' cellpadding='0' cellspacing='0' align='center'><tr><td><b>".$message."</b></td></tr></table>";
}

$query2 = mysql_query("SELECT countries_id, countries_name FROM countries ORDER BY countries_name");
if(mysql_num_rows($query2) > 0) {
	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	if(!isset($_GET['hide']) AND !isset($hide)) print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td><p align='center'>".A3."</p>";
	if(!isset($_GET['hide']) AND !isset($hide)) print "<p align='center'>".A4."</tr></td></table><br>";
	print "<table align='center' border='0' cellpadding='5' cellspacing='0' width='700' class='TABLE'>";
	print "<tr bgcolor='#FFFFFF'>";
	print "<td align='center'>";
	
		print "<table width='700' align='center' border='0' cellpadding='3' cellspacing='0'><tr height='35'>";
		print "<td align='center' class='fontgris'><input name='tout' type='checkbox' onClick='this.value=check(this.form);'></td>";

		print "<td align='center'><b>".PAYS."</b></td></tr>";
			while ($result2 = mysql_fetch_array($query2)) {
				if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
				if(isset($ids2) AND in_array($result2['countries_id'], $ids2)) $c="#FFFFFF";
				print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
				print "<td align='center'><input type='checkbox' name='checkCom[]' value='".$result2['countries_id']."' ".$selChecked."></td>";

				print "<td align='center'>".$result2['countries_name']."</td>";
				print "</tr>";
			}
		print "</table>";
		
	print "</td></tr></table>";
	
	
	if(!isset($hide)) {
		print "<br>";
		print "<table width='700' border='0' align='center' cellpadding='5' cellspacing = '0'>";
		print "<tr>";
		print "<td colspan='2' align='center'><input type='submit' name='action' value='".A1."' class='knop'></td>";
		print "</tr>";
		print "</table><br><br><br>";
	}
	print "</form>";
}
else {
	print "<br><br><table border='0' cellpadding='7' cellspacing='0' align='center' width='700' class='TABLE'><tr><td align='center'>";
	print "<p align='center' class='fontrouge'><b>".NO_COUNTRY."</b>";
	print "</td></tr></table>";
}
?>

</body>
</html>
