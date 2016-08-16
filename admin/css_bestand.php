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
$c = "";
if(isset($_POST['action']) AND $_POST['action']=='update' AND $_POST['cssContent']!=='') {
  $fiche = fopen("../css/".$colorInter.".css","w");
  fputs($fiche, $_POST['cssContent']);
  fclose($fiche);
  $messge = "<center><table width='700' border='0' align='center' cellpadding='5' cellspacing = '0' class='TABLE'><tr><td><center><span style='color:#FF0000; font-weight:bold;'>".UPDATE_OK."</tr></td></table>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print EDITER_MODIFIER;?></p>

<?php
$fichier="../css/".$colorInter.".css";
$cssContent=file_get_contents($fichier);

if(isset($messge)) print $messge;

print "<p align='center'>";
print "<a href='site_config.php#Fichier%20CSS%20par%20d%C3%A9faut' style='text-decoration:none'><span style='font-size:12px; color:#CC0000'>".FICHIER_CSS_BY_DEFAULT."</span></a><span style='font-size:12px; color:#CC0000'>: css/".$colorInter.".css</span>";
print "</p>";

print "<form method='POST' action='css_bestand.php'>";
print "<div align='center'>";
print "<textarea name='cssContent' value='".$cssContent."' rows='70' style='border:1px #c0c0c0 solid; width:80%'>".$cssContent."</textarea>";
print "<input type='hidden' name='action' value='update'>";
print "<p align='center'><input type='submit' class='knop' value='".UPDATE."'></p>";
print "</div>";
print "</form>";
?>
<br><br><br>
</body>
</html>





