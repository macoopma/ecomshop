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
$queryName = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$_GET['id']."'");
$row = mysql_fetch_array($queryName);

$queryCheck = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE parent_id='".$_GET['id']."'");
$num = mysql_num_rows($queryCheck);

if($num > 0) {
	print "<div align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><center>";
	print A2." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b> ".A3." $num ".A4."<br><br>";
	print "<form action='categorie_wijzigen.php' method='POST'><INPUT TYPE='submit' class='knop' VALUE='".A5."'></form></tr></td></table>";
	print "</div>";

}
else {
	$query = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$_GET['id']."'");
	$row = mysql_fetch_array($query);
	if(!isset($_GET['page'])) {
		print "<div align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><center>";
		print A6." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b>. ".A7."<br><br>";
		print "<a href='categorie_verwijderen.php?page=delete&id=".$row['categories_id']."' target='main'><b>".A8."</b></a> &nbsp;&nbsp;&nbsp;";
		print "<a href='categorie_wijzigen.php' target='main'><b>".A9."</b></a>";
		print "</div></tr></td></table>";
	}

	if(isset($_GET['page']) and $_GET['page'] == "delete") {
   		$delete = mysql_query("DELETE FROM categories WHERE categories_id='".$_GET['id']."'");
        print "<div align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><center>";
        print A10." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b> ".A11.".<br><br>";
        print "<form action='categorie_wijzigen.php' method='post'><INPUT TYPE='submit' class='knop' VALUE='".A12."'></form></tr></td></table>";
        print "</div>";
	}
}
?>
</body>
</html>
