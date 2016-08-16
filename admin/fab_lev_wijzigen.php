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

 
if(isset($_GET['action1']) AND $_GET['action1']=="delete") {

   $quer = mysql_query("SELECT products_id FROM products WHERE products_ref!='GC100'");
   $querNum = mysql_num_rows($quer);
   if($querNum > 0) $go = 0; else $go = 1;
   if($go==1) {
       if(!isset($_GET['action2'])) {
         $voulez = "<table border='0' align='center' class='TABLE' width='700' cellspacing='0' cellpadding='5'><tr><td class='fontrouge'>";
         $voulez .= "<p><b>".ETES_VOUS."</b></p>";
         $voulez .= "<p align='center'><a href='fab_lev_wijzigen.php?action1=delete&action2=ok'><b>".OUI."</b></a> | <a href='fab_lev_wijzigen.php'><b>".NON."</b></a></p>";
         $voulez .= "</td></tr></table>";
       }
       else {
         $del = mysql_query("DELETE FROM fournisseurs");
         $sup = "<table border='0' align='center' class='TABLE' width='700' cellspacing='0' cellpadding='5'><tr><td class='fontrouge'>";
         $sup .= "<center><b>".TOUS."</b>";
         $sup .= "</td></tr></table>";
       }
   }
   else {
         $sup = "<table border='0' align='center' class='TABLE' width='700' cellspacing='0' cellpadding='10'><tr>";
         $sup.= "<form action='fab_lev_wijzigen.php' method='post'>";
         $sup.= "<td align='center'>";
         $sup.= DESF;
         $sup.= "<br><img src='im/zzz.gif' width='1' height='3'><br>";
         $sup.= "<span class='fontrouge'><b>".VEUI."</b></span>";
         $sup.= "<p align='center'>";
         $sup.= "<INPUT TYPE='submit' class='knop' VALUE='".RETO."'>";
         $sup.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
         $sup.= "<a href='artikel_wijzigen.php'>".MODI."</a>";
         $sup.= "</p>";
         $sup.= "</td>";
         $sup.= "</form>";
         $sup.= "</tr></table>";
   }
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

<?php
if(!isset($sup)) {
    print '<p align="center">';
    print '<table border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700"><tr><td align="center">';
    print '&bull;&nbsp;<a href="fab_lev_wijzigen.php?action1=delete">'.SUP.'</a>';
    print '</td></tr></table>';
    print '</p>';
}

if(isset($voulez)) print $voulez;
if(isset($sup)) print $sup;

$prodsdd = mysql_query("SELECT * FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fournisseur' ORDER BY fournisseurs_company");
$prods2dd = mysql_query("SELECT * FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fabricant' ORDER BY fournisseurs_company");

print "<table border='0' cellpadding='2' cellspacing='0' align='center'  width='700'>";
print "<tr>";
print "<td valign='top'>";

// Lev
print "<table width='340' border='0' cellpadding='3' cellspacing='1' align='center' class='TABLE2'>";
print "<tr>";
print "<td align='center' valign='middle' colspan='4' height='30'><b>".strtoupper(FOU)."</b></td></tr><tr>";
print "<td class='TD' width='30'><b>ID#</b></td><td class='TD'><b>".FOU."</b></td><td class='TD'><b>".ARTICLE."</b></td><td class='TD' align='center'><b>Stock</b></td>";
print "</tr></tr>";
while($produita = mysql_fetch_array($prodsdd)) {
	print "<tr onmouseover=\"this.style.backgroundColor='#FFFF00';\" onmouseout=\"this.style.backgroundColor='';\">";
	print "<td>".$produita['fournisseurs_id']."</td>";
	print "<td><a href='fab_lev_wijzigen2.php?id1=".$produita['fournisseurs_id']."&id2=none'>".strtoupper($produita['fournisseurs_company'])."</a></td>";
	
	$queryFournisseur = mysql_query("SELECT p.products_id, p.products_qt, o.products_options_stock_stock
									FROM products AS p
									LEFT JOIN products_options_stock AS o
									ON (p.products_id = o.products_options_stock_prod_id)
									WHERE p.fournisseurs_id = '".$produita['fournisseurs_id']."'");
	$queryFournisseurNum = mysql_num_rows($queryFournisseur);
	print "<td align='left'>".$queryFournisseurNum."</td>";
	$stockFour = array();
	while($queryFournisseurResult = mysql_fetch_array($queryFournisseur)) {
		if(!empty($queryFournisseurResult['products_options_stock_stock']) OR $queryFournisseurResult['products_options_stock_stock']=="0") {
			$stockFour[] = $queryFournisseurResult['products_options_stock_stock'];
		}
		else {
			$stockFour[] = $queryFournisseurResult['products_qt'];
		}
	}
	$totalStockFourn = array_sum($stockFour);
	$displayStockFour = $totalStockFourn;
	if($totalStockFourn<=$seuilStock) $displayStockFour = "<span style='background:#00CC00; padding:2px; color:#FFFFFF; border:1px #FFFFFF solid;'><b>".$totalStockFourn."</b></span>";
	if($totalStockFourn<=0) $displayStockFour = "<span style='background:#FF0000; padding:2px; color:#FFFFFF; border:1px #FFFFFF solid;'><b>".$totalStockFourn."</b></span>";
	
	print "<td align='center'>".$displayStockFour."</td>";
	if(isset($stockFour)) unset($stockFour);
	
	print "</tr>";
}
print "</table>";

print "</td><td valign='top'>";


// Fab
print "<table width='340' border='0' cellpadding='3' cellspacing='1' align='center' class='TABLE'>";
print "<tr>";
print "<td align='center' valign='middle' colspan='4' height='30'><b>".strtoupper(FA)."</b></td></tr><tr>";
print "<td class='TD' width='30'><b>ID#</b></td><td class='TD'><b>".FA."</b></td><td class='TD'><b>".ARTICLE."</b></td><td class='TD' align='center'><b>Stock</b></td>";
while($produitb = mysql_fetch_array($prods2dd)) {
	print "</tr><tr onmouseover=\"this.style.backgroundColor='#FFFF00';\" onmouseout=\"this.style.backgroundColor='';\">";
	print "<td>".$produitb['fournisseurs_id']."</td>";
	print "<td><a href='fab_lev_wijzigen2.php?id1=none&id2=".$produitb['fournisseurs_id']."'>".strtoupper($produitb['fournisseurs_company'])."</a></td>";
	
	$queryFabricant = mysql_query("SELECT products_id, products_qt, o.products_options_stock_stock
									FROM products AS p
									LEFT JOIN products_options_stock AS o
									ON (p.products_id = o.products_options_stock_prod_id)
									WHERE fabricant_id = '".$produitb['fournisseurs_id']."'");
	$queryFabricantNum = mysql_num_rows($queryFabricant);
	print "<td align='left'>".$queryFabricantNum."</td>";
	$stockFab = array();
	while($queryFabricantResult = mysql_fetch_array($queryFabricant)) {
		if(!empty($queryFabricantResult['products_options_stock_stock']) OR $queryFabricantResult['products_options_stock_stock']=="0") {
			$stockFab[] = $queryFabricantResult['products_options_stock_stock'];
		}
		else {
			$stockFab[] = $queryFabricantResult['products_qt'];
		}
	}
	$totalStock = array_sum($stockFab);
	$displayStockFab = $totalStock;
	if($totalStock<=$seuilStock) $displayStockFab = "<span style='background:#00CC00; padding:2px; color:#FFFFFF; border:1px #FFFFFF solid;'><b>".$totalStock."</b></span>";
	if($totalStock<=0) $displayStockFab = "<span style='background:#FF0000; padding:2px; color:#FFFFFF; border:1px #FFFFFF solid;'><b>".$totalStock."</b></span>";
	
	print "<td align='center'>".$displayStockFab."</td>";
	print "</tr>";
	if(isset($stockFab)) unset($stockFab);
}
print "</table>";

print "</td></tr></table>";
?><br><br><br>
</body>
</html>
