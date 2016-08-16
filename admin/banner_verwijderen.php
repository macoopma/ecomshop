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
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
$query = mysql_query("SELECT banner_id, banner_desc FROM banner WHERE banner_id='".$_GET['id']."'");
$row = mysql_fetch_array($query);

if(!isset($_GET['page'])) {
	print "<div align='center'><table border='0' cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'><tr><td><center>";
	print A2." <b>".strtoupper($row['banner_desc'])."</b> ".A3."<br><br>";
	print "<a href='banner_verwijderen.php?page=delete&id=".$row['banner_id']."' target='main'><b>".A4."</b></a> &nbsp;&nbsp;&nbsp;";
	print "<a href='banner_wijzigen.php' target='main'><b>".A5."</b></a>";
	print "</div>";
}

if(isset($_GET['page']) and $_GET['page'] == "delete") {
	$delete = mysql_query("DELETE FROM banner WHERE banner_id='".$_GET['id']."'");
	print "<div align='center'><table border='0' cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'><tr><td><center>";
	print A2." <b>".strtoupper($row['banner_desc'])."</b> ".A6.".<br><br>";
	print "<form action='banner_wijzigen.php' method='post' target='main'><INPUT TYPE='submit' VALUE='".A7."' class='knop'></form>";
	print "</div>";
}
?>
</body>
</html>
