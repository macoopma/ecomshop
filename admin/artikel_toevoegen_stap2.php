<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

if($_GET['group']=="no") {
print "<html>";
        print "<head>";
        print "<title></title>";
        print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
        print "<link rel='stylesheet' href='style.css'>";
        print "</head>";
        print "<body leftmargin='0' topmargin='30' marginwidth='0' marginheight='0'>";
        print "<p align='center' class='largeBold'>".A1."</p>";
        print "<table border='0' align='center' cellpadding='5' cellspacing = '0' width='700' class='TABLE'>";
        print "<tr>";
        print "<td align='center' class='fontrouge' colspan='2'>".A2."</td>";
        print "</tr>";
        print "<tr>";
        print "<td align='center' colspan='2'><FORM action='artikel_toevoegen.php' method='post'>";
            print "<INPUT TYPE='submit' class='knop' VALUE='".A3."'></form></td>";
        print "</tr>";
        print "</table>";
        print "</body>";
        print "</html>";
        exit;

}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>
<p align="center"><span align="center" style="color:#CC0000"><b><?php print A4;?></b></span></p>

<?php
include('../configuratie/configuratie.php');

$prods = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fournisseur' ORDER BY fournisseurs_company");
$prods2 = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fabricant' ORDER BY fournisseurs_company");

print "<form action='artikel_toevoegen_stap3_details.php' method='GET' target='main'>";
	print "<input type='hidden' name='group' value='".$_GET['group']."'>";
	print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700' style='padding:5px'>";
	print "<tr>";
	print "<td>";
			print FOURNISSEUR."</td><td>";
			print "<select name='compagnie1'>";
		        while ($produit1 = mysql_fetch_array($prods)) {
		               print "<option value='".$produit1['fournisseurs_company']."'>".strtoupper($produit1['fournisseurs_company']);
		               print "</option>";
		        }
			print "</select>";
	print "</td>";
	print "</tr>";
	print "<tr>";
	print "<td>";
			print FABRIQUANT."</td><td>";
			print "<select name='compagnie2'>";
		        while ($produit2 = mysql_fetch_array($prods2)) {
		               print "<option value='".$produit2['fournisseurs_company']."'>".strtoupper($produit2['fournisseurs_company']);
		               print "</option>";
		        }
			print "</select>";       
	print "</td>";
	print "</tr>";
	print "<tr>";
	print "<td colspan='2' align='center'>";
	print "<input type='Submit' value='".A5."' class='knop'>";
	print "</td>";
	print "</tr>";
	print "</table>";
print "</form>";
?>
<br>
<table border="0" align="center" cellpadding="5" cellspacing = "0" width="700" class="TABLE"><tr><td>
<p align="center"><a href="fab_lev_toevoegen.php"><b><?php print A6;?></b></a></tr></td></table>

</body>
</html>
