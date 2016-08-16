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
$query = mysql_query("SELECT p.products_id, p.products_price, s.specials_new_price, p.products_name_".$_SESSION['lang'].", s.specials_visible
                     FROM specials as s
                     INNER JOIN products as p
                     ON(s.products_id = p.products_id)
                     WHERE specials_id='".$_GET['id']."'
                     ORDER BY p.products_name_".$_SESSION['lang']."");
$row = mysql_fetch_array($query);

if(!IsSet($_GET['page'])) {
  print "<div align='center'>";
  print A2." <b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b> ".A3."<br><br>";
  print "<a href='promotie_verwijderen.php?page=delete&id=".$_GET['id']."' target='main'><b>".A4."</b></a> &nbsp;&nbsp;&nbsp;";
  print "<a href='promoties_wijzigen.php' target='main'><b>".A5."</b></a>";
  print "</div>";
}

if(isset($_GET['page']) and $_GET['page'] == "delete") {
   $delete = mysql_query("DELETE FROM specials WHERE specials_id='".$_GET['id']."'");
        print "<div align='center'>";
        print A2." <b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b> ".A6.".<br><br>";
        print "<form action='promoties_wijzigen.php' method='post'><INPUT TYPE='submit' class='knop' VALUE='".A7."'></form>";
        print "</div>";
}
?>
</body>
</html>
