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
if(isset($_GET['page']) and $_GET['page'] == "delete") {
	$query1 = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$_GET['id']."'");
	$rowName = mysql_fetch_array($query1);

	$delete = mysql_query("DELETE FROM categories WHERE categories_id='".$_GET['id']."'");
	print "<center><table border='0' cellpadding='3' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td><center>";
	print A2." <b>".strtoupper($rowName['categories_name_'.$_SESSION['lang']])."</b> ".A3.".<br><br>";
	print "<form action='sub_categorie_wijzigen.php' method='POST'><INPUT TYPE='submit' class='knop' VALUE='".A4."'></form>";
	print "</tr></td></table>";
}
else {
	$query1 = mysql_query("SELECT products_id, products_name_".$_SESSION['lang']." FROM products WHERE categories_id='".$_GET['id']."'");
	$rowNum = mysql_num_rows($query1);
	if($rowNum>0) {
		print "<center><table border='0' cellpadding='3' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td><center><div align='center'><span style='color:red'>".A5." <b>".$rowNum."</b> ".A6."</span><br>".A7."</div><br>";
	    $i=1;
	    while($rowName = mysql_fetch_array($query1)) {
	    	print "<div align='left'>";
			print $i++." - ".$rowName['products_name_'.$_SESSION['lang']]."<br>";
	    	print "</div><center>";
		}
	    print "<form action='sub_categorie_wijzigen.php' method='POST'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A4."'></p></form></tr></td></table>";
	}
	else {
		$query = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$_GET['id']."'");
		$row = mysql_fetch_array($query);
		if(!isset($_GET['page'])) {
			print "<center><table border='0' cellpadding='3' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td><center>";
			print "<br>".A9." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b>?<br><br>";
			print "<a href='sub_categorie_verwijderen.php?page=delete&id=".$row['categories_id']."' target='main'><b>".A10."</b></a> &nbsp;&nbsp;&nbsp;";
			print "<a href='sub_categorie_wijzigen.php' target='main'><b>".A11."</b></a>";
			print "</tr></td></table>";
		}
	}
}
?>
</body>
</html>
