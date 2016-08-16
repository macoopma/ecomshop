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
$query = mysql_query("SELECT code_promo FROM code_promo WHERE code_promo_id ='".$_GET['id']."'");
$row = mysql_fetch_array($query);

if(!IsSet($_GET['page'])) {
print "<center><table width='700' border='0' align='center' cellpadding='5' cellspacing = '0' class='TABLE'><tr><td><center>";
print A2." <b>".strtoupper($row['code_promo'])."</b> ".A3."<br><br>";
print "<a href='korting_code_verwijderen.php?page=delete&id=".$_GET['id']."' target='main'><b>".A4."</b></a> &nbsp;&nbsp;&nbsp;";
print "<a href='korting_code_wijzigen.php' target='main'><b>".A5."</b></a></tr></td></table>";


}

if(isset($_GET['page']) and $_GET['page'] == "delete") {
       mysql_query("DELETE FROM code_promo WHERE code_promo_id='".$_GET['id']."'");

        print "<center><table width='700' border='0' align='center' cellpadding='5' cellspacing = '0' class='TABLE'><tr><td><center>";
        print A2." <b>".strtoupper($row['code_promo'])."</b> ".A6.".<br><br>";
        print "<form action='korting_code_wijzigen.php' method='post'><INPUT TYPE='submit' VALUE='".A7."' class='knop'></form>";
        print "</tr></td></table>";

}
?>

  </body>
  </html>
