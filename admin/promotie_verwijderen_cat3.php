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
$query1 = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseurs_id = '".$_GET['fourn']."'");
$fournisseur = mysql_fetch_array($query1);

$query2 = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['id']."'");
$categorie = mysql_fetch_array($query2);
 
if(is_numeric($_GET['id'])) $toto = " AND p.categories_id = '".$_GET['id']."'"; else $toto = "";
if(is_numeric($_GET['fourn'])) $toto .= " AND p.fournisseurs_id = '".$_GET['fourn']."'"; else $toto .= "";

$result3 = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id, p.categories_id, f.fournisseurs_company, f.fournisseurs_id, s.specials_id, s.specials_new_price
                       FROM products as p
                       LEFT JOIN fournisseurs as f
                       ON (p.fournisseurs_id = f.fournisseurs_id)
                       LEFT JOIN specials as s
                       ON (p.products_id = s.products_id)
                       WHERE 1
                       ".$toto."
                       AND s.specials_id != 'null'
                       ORDER BY p.products_name_".$_SESSION['lang']."");
$result3Num = mysql_num_rows($result3);

if($result3Num > 0) {
	while($resultRequete = mysql_fetch_array($result3)) {
		print "<div align='center'><b>".$resultRequete['products_name_'.$_SESSION['lang']]."</b> ".A2.".</div>";

	    $delete = mysql_query("DELETE FROM specials WHERE products_id='".$resultRequete['products_id']."'");
	}
	if(!is_numeric($_GET['id']) and !is_numeric($_GET['fourn'])) {
		$message = "<span class='fontrouge'>".A3."</span>";
	}
	if(is_numeric($_GET['id']) and !is_numeric($_GET['fourn'])) {
		$message = "<span class='fontrouge'>".A4." <b>".strtoupper($categorie['categories_name_'.$_SESSION['lang']])."</b> ".A5.".</span>";
	}
	if(!is_numeric($_GET['id']) and is_numeric($_GET['fourn'])) {
		$message = "<span class='fontrouge'>".A6." <b>".strtoupper($fournisseur['fournisseurs_company'])."</b> ".A5.".</span>";
	}
	if(is_numeric($_GET['id']) and is_numeric($_GET['fourn'])) {
		$message = "<span class='fontrouge'>".A4." <b>".strtoupper($categorie['categories_name_'.$_SESSION['lang']])."</b> ".A7." <b>".strtoupper($fournisseur['fournisseurs_company'])."</b> ".A5.".</span>";
	}
}
else {
	$message = A8;
}
?>

<br>
<table border="0" align="center" cellpadding="5" cellspacing = "0">
<tr>
<td align="center" colspan="2"><?php print $message;?></td>
</tr>
<tr>
<td align="center" colspan="2"><FORM action="promotie_verwijderen_cat1.php" method="post"><INPUT TYPE="submit" class="knop" VALUE="  OK   "></form></td>
</tr>
</table>

</body>
</html>
