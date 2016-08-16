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


function searchPath($product_name, $cat_name, $parent_id, $lang, $catId) {
    global $productPath;
    $subCatQuery = mysql_query("SELECT categories_name_".$lang.", parent_id FROM categories WHERE categories_id = '".$parent_id."'");
    $arrSubCategory = mysql_fetch_array($subCatQuery);
    $subCatParentId = $arrSubCategory['parent_id'];
    $subCatName = $arrSubCategory['categories_name_'.$lang];
    if($subCatParentId == '0') {
    	$productPath[$product_name][$catId][] = $subCatName;
    }
    else {
        $productPath[$product_name][$catId][] = $subCatName;
        searchPath($product_name, $subCatName, $subCatParentId, $lang, $catId);
    }
    $pathReturn = implode(" > ", array_reverse($productPath[$product_name][$catId]));
    $pathReturn = $pathReturn." > ".$cat_name;
    return $pathReturn;                   
}

 
 
function recurs3($origin) {
	global $jj;
	$findOriginQuery = mysql_query("SELECT categories_id, parent_id, categories_noeud, categories_visible FROM categories WHERE parent_id = '".$origin."'");
	if(mysql_num_rows($findOriginQuery)>0) {
		while($findOrigin = mysql_fetch_array($findOriginQuery)) {
			if($findOrigin['categories_noeud']=="L" AND $findOrigin['categories_visible']=='yes') $jj[] = $findOrigin['categories_id'];
			recurs3($findOrigin['categories_id']);
		}
		return $jj;
	}
}

function processCat() {
	GLOBAL $_POST, $_SESSION;
	$reqZ = mysql_query("SELECT categories_id FROM categories WHERE categories_name_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'") or die (mysql_error());
	if(mysql_num_rows($reqZ)>0) {
		while($resZ = mysql_fetch_array($reqZ)) {
			$catId[] = $resZ['categories_id'];
		}
		if(count($catId)>0) {
			foreach($catId AS $items) {
				$SubCatFromCat = recurs3($items);
			}
		}
		else {
			$addToReq = "c.categories_name_".$_SESSION['lang']." LIKE '%jkfdsbadsbadbg%'";
		}
		if(count($SubCatFromCat)>0) {
			foreach($SubCatFromCat AS $it) {
				$catNameReq = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$it."'") or die (mysql_error());
				if(mysql_num_rows($catNameReq)>0) {
					$catName = mysql_fetch_array($catNameReq);
					$addToReqArray[] = "c.categories_name_".$_SESSION['lang']."='".$catName['categories_name_'.$_SESSION['lang']]."'";
				}
				else {
					$addToReq = "c.categories_name_".$_SESSION['lang']." LIKE '%jkfdsbadsbadbg%'";
				}
			}
		}
		else {
			$addToReq = "c.categories_name_".$_SESSION['lang']." LIKE '%jkfdsbadsbadbg%'";
		}
		if(isset($addToReqArray)) $addToReq = implode(" OR ",$addToReqArray);
	}
	else {
		$addToReq = "c.categories_name_".$_SESSION['lang']." LIKE '%jkfdsbadsbadbg%'";
	}
	return $addToReq;
}
 
function espace3($rang3) {
	$ch="";
	for($x=0;$x<$rang3;$x++) {
    	$ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	return $ch;
}
 
function recur($tab,$pere,$rang3,$lang) {
	global $c;
	if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
	$tabNb = count($tab);
	for($x=0;$x<$tabNb;$x++) {
	    if($tab[$x][1]==$pere) {
	       print "</tr><tr bgcolor='".$c."'>";
	       print "<td>";
	       print espace3($rang3).$tab[$x][2];
	               $result3 = mysql_query("SELECT products_options, products_ref, products_sup_shipping, products_caddie_display, products_forsale, products_name_".$lang.", products_deee, products_exclusive, products_id, products_qt, products_visible, products_download, products_related
	                                       FROM products
	                                       WHERE categories_id = '".$tab[$x][0]."' 
	                                       OR categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
	                                       AND products_ref != 'GC100'
	                                       ORDER BY products_name_".$lang."");
					while($produit = mysql_fetch_array($result3)) {
						$_ref = $produit['products_ref'];
						$opt = ($produit['products_options'] == 'yes')? "&nbsp;<a href='opties_details.php?id=".$produit['products_id']."'><img src='im/opt.png' title='".OPT."' border='0'></a>" : "";
						$do = ($produit['products_download'] == "yes")? "&nbsp;<img src='im/download.gif' title='".A41."'>" : $do="";
						$doEx = ($produit['products_exclusive'] == "yes")? "&nbsp;<img src='im/coeur.gif' title='".A44."'>" : "";
						$rupture = ($produit['products_qt'] <= 0)? "&nbsp;<img src='im/no_stock.gif' title='".A40."'>" : "";
						$vis = ($produit['products_visible'] == "no")? "&nbsp;<img src='im/eye.gif' title='".A7."'>" : "";
						$af = (!empty($produit['products_related']))? "&nbsp;<img src='im/affiliation_ic.gif' title='".A43."'>" : "";
						$deee = ($produit['products_deee']!=="0.00")? "&nbsp;<img src='im/deee.gif' title='EcoTax'>" : "";
						$noStock = ($produit['products_forsale'] == "no")? "&nbsp;<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>" : "";
						$toCart = ($produit['products_caddie_display'] == "yes")? "&nbsp;<img src='im/cart_2.gif' title='".TO_CART."'>" : "";
						$emb = ($produit['products_sup_shipping'] > 0)? "&nbsp;<img src='im/emball.gif' title='".EMBALLAGE."'>" : "";
						$ProdNameProdList = (strlen($produit['products_name_'.$lang])>=100)? substr($produit['products_name_'.$lang],0,90)."..." : $produit['products_name_'.$lang];
						
						print "<div style='padding:3px' onmouseover=\"this.style.backgroundColor='#FFFFCC';\" onmouseout=\"this.style.backgroundColor='';\">";
						print espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						print "<a href='artikel_verwijderen.php?id=".$produit['products_id']."'><img src='im/supprimer2.gif' align='absmiddle' border='0' title='Supprimer ".$produit['products_name_'.$lang]."' alt='Supprimer ".$produit['products_name_'.$lang]."'></a>&nbsp;";
						print "<a href='artikel_wijzigen_details.php?id=".$produit['products_id']."' title='".$produit['products_name_'.$lang]."' style='text-decoration:none;'><span style='padding:2px 1px 2px 1px'>".$ProdNameProdList."</span></a>";
						print "&nbsp;&nbsp;<span style='color:#CC0000'><i>[REF: ".$_ref." | ID: ".$produit['products_id']."]</i></span>&nbsp;&nbsp;".$rupture.$vis.$do.$af.$doEx.$deee.$noStock.$toCart.$emb.$opt;
						print "</div>";
					}
	       	print "</td>";
	       	recur($tab,$tab[$x][0],$rang3+1,$lang);
	    }
	}
}

## zoeken
if(isset($_POST['search']) AND $_POST['search']=='yes' AND isset($_POST['searchReq']) AND !empty($_POST['searchReq']) AND strtolower($_POST['searchReq'])!=='menu' AND isset($_POST['cat']) AND $_POST['cat']!=='---' AND $_POST['cat']!=='----') {
	$i=1;
	if($_POST['cat']=="all") {
		$addToReq = "p.products_name_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_ref LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_image LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_image2 LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_image3 LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_image4 LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_image5 LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_id LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_desc_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'
			                 OR p.products_note_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'
			                 OR c.categories_name_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'
							 ";
		$addToReq = $addToReq." OR ".processCat();
	}
	if($_POST['cat']=="name") $addToReq = "p.products_name_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'";
	if($_POST['cat']=="ref") $addToReq = "p.products_ref LIKE '%".$_POST['searchReq']."%' OR o.products_options_stock_ref LIKE '%".$_POST['searchReq']."%'";
	if($_POST['cat']=="descShort") $addToReq = "p.products_desc_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'";
	if($_POST['cat']=="descLong") $addToReq = "p.products_note_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'";
	if($_POST['cat']=="categ") $addToReq = "c.categories_name_".$_SESSION['lang']." LIKE '%".$_POST['searchReq']."%'";
	if($_POST['cat']=="categ_main") {
		$addToReq = processCat();
	}
	if($_POST['cat']=="im") $addToReq = "p.products_image LIKE '%".$_POST['searchReq']."%' 
											OR p.products_image2 LIKE '%".$_POST['searchReq']."%'
											OR p.products_image3 LIKE '%".$_POST['searchReq']."%'
											OR p.products_image4 LIKE '%".$_POST['searchReq']."%'
											OR p.products_image5 LIKE '%".$_POST['searchReq']."%'";
	
		$req = mysql_query("SELECT p.products_id, p.products_ref, p.products_name_".$_SESSION['lang'].", c.categories_name_".$_SESSION['lang'].", p.categories_id, c.parent_id
			                 FROM products AS p
			                 LEFT JOIN categories AS c ON (p.categories_id = c.categories_id)
			                 WHERE products_ref != 'GC100'
			                 AND (".$addToReq.")
			                 ORDER BY products_id ASC
							 ");
		if($_POST['cat']=="ref") {
			$req = mysql_query("SELECT p.products_id, p.products_ref, p.products_name_".$_SESSION['lang'].", c.categories_name_".$_SESSION['lang'].", p.categories_id, c.parent_id, o.products_options_stock_ref, o.products_options_stock_prod_name, o.products_options_stock_active
				                 FROM products AS p
				                 LEFT JOIN categories AS c ON (p.categories_id = c.categories_id)
				                 LEFT JOIN products_options_stock AS o ON (p.products_id = o.products_options_stock_prod_id)
				                 WHERE products_ref != 'GC100'
				                 AND (".$addToReq.")
				                 ORDER BY products_id ASC
								 ");
		}
		if( mysql_num_rows($req)>0) {
			$idArray = array();
			while($res = mysql_fetch_array($req)) {
		    		if(isset($res['products_options_stock_ref']) AND !empty($res['products_options_stock_ref'])) {
						$res['products_ref'] = $res['products_options_stock_ref'];
						$nameToDisplay = ($res['products_options_stock_active']=='yes')? $res['products_options_stock_prod_name'] : "<s>".$res['products_options_stock_prod_name']."</s>";
						$cat_name = "<span style='color:#FF0000'>".$nameToDisplay."</span>";
					}
					else {
						$cat_name = searchPath($res['products_id'], $res['categories_name_'.$_SESSION['lang']], $res['parent_id'], $_SESSION['lang'], $res['categories_id']);
					}
					$resultSearch[] = "<td>".$i++.".</td>
										<td><a href='artikel_wijzigen_details.php?id=".$res['products_id']."'>".$res['products_id']."</a></td>
										<td><a href='artikel_wijzigen_details.php?id=".$res['products_id']."'>".$res['products_name_'.$_SESSION['lang']]."</a></td>
										<td>".$cat_name."</td>
										<td>".$res['products_ref']."</td>";
			}
		}
		else {
			$resultSearchNada = "<div align='center' class='fontrouge'><b>Er werden geen artikelen gevonden</b></div>";
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
<p align="center" class="largeBold"><?php print MOD;?></p>

<?php
$id = 0;
$i = 0;
$prods = mysql_query("SELECT p.*, c.categories_name_".$_SESSION['lang']."
                      FROM products as p
                      INNER JOIN categories as c
                      ON(p.categories_id = c.categories_id)
                      WHERE c.categories_noeud != 'R'
                      AND p.products_ref != 'GC100'
                      ORDER BY categories_name_".$_SESSION['lang']."");
$result = mysql_query("SELECT * FROM categories ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$prodTotalNum = mysql_num_rows($prods);

if($prodTotalNum > 0) {
	print "<p align='center'>".VOUS_AVEZ." <b>".$prodTotalNum."</b> ".ARTICLES_ENREGISTRES."</p>";
	
	print '<table border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700"><tr><td align="center">';
	print '&bull;&nbsp;<a href="artikel_alles_verwijderen.php">'.DELETEALL.'</a><br><img src="im/zzz.gif" width="1" height="5"><br>';
	print '&bull;&nbsp;<a href="artikel_in_categorie_verwijderen.php">'.A1.'</a><br><img src="im/zzz.gif" width="1" height="5"><br>';
	print '&bull;&nbsp;<a href="artikel_prijzen_wijzigen.php">'.MODIFIER_PRIX.'</a><br><img src="im/zzz.gif" width="1" height="5"><br>';
	print '&bull;&nbsp;<a href="artikelen_onze_selectie.php">'.A70.'</a><br><img src="im/zzz.gif" width="1" height="5"><br>';
	print '&bull;&nbsp;<a href="exporteer_alleen_artikelen.php">'.EXPORT_CSV.'</a>';
	print '</td></tr>';
	print '</table>';
	print '<br>';

	while($message = mysql_fetch_array($result)) {
		$result2 = mysql_query("SELECT * FROM products
		                       WHERE categories_id = '".$message['categories_id']."'
		                       AND products_ref != 'GC100'
		                       ORDER BY products_name_".$_SESSION['lang']."");
		$productsNum = mysql_num_rows($result2);
		$papa = $message['categories_id'];
		$fils = $message['parent_id'];
		$visible = $message['categories_visible'];
		$noeud = $message['categories_noeud'];
		if($message['categories_visible']=='yes') {
		   $visible = A4; 
		   $visible2 = '';
		}
		else {
		   $visible = "<span color=red><b>".A5."</b></span>";
		   $visible2 = " <img src='im/eye.gif' title='".A7."'> ";
		}
		$titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>".$visible2 : "<span style='color:green; text-decoration:underline'>".$message['categories_name_'.$_SESSION['lang']]."</span>".$visible2." [".$productsNum."]";
		$data[] = array($papa,$fils,$titre,$visible,$noeud);
	}
	## -----------------
	## zoeken
	## -----------------
	if(!isset($_POST['searchReq'])) $widthTable = '300';
	if(!isset($resultSearch)) $widthTable = '300';
	if(!isset($widthTable)) $widthTable = '';
	print "<table border='0' width='".$widthTable."' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	print "<td align='center'>";
	if(isset($_POST['searchReq'])) $val=$_POST['searchReq']; else $val="";
	if(isset($_POST['cat']) AND $_POST['cat']=="all") $sel="selected"; else $sel="";
	if(isset($_POST['cat']) AND $_POST['cat']=="name") $sel1="selected"; else $sel1="";
	if(isset($_POST['cat']) AND $_POST['cat']=="ref") $sel2="selected"; else $sel2="";
	if(isset($_POST['cat']) AND $_POST['cat']=="descShort") $sel3="selected"; else $sel3="";
	if(isset($_POST['cat']) AND $_POST['cat']=="descLong") $sel4="selected"; else $sel4="";
	if(isset($_POST['cat']) AND $_POST['cat']=="im") $sel5="selected"; else $sel5="";
	if(isset($_POST['cat']) AND $_POST['cat']=="---") $sel6="selected"; else $sel6="";
	if(isset($_POST['cat']) AND $_POST['cat']=="----") $sel7="selected"; else $sel7="";
	if(isset($_POST['cat']) AND $_POST['cat']=="categ") $sel15="selected"; else $sel15="";
	if(isset($_POST['cat']) AND $_POST['cat']=="categ_main") $sel16="selected"; else $sel16="";
	

	print "<input type='text' class='vullen' name='searchReq' value='".$val."' style='width:100%; background:#FFFFCC'>";
	print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	print "<select name='cat' style='width:100%; background:#EEEEEE'>";
	print "<option value='all' ".$sel.">".TOUS."</option>";
	print "<option value='---' ".$sel6.">---</option>";
	print "<option value='name' ".$sel1.">".ARTICLE."</option>";
	print "<option value='ref' ".$sel2.">".REFERENCE."</option>";
	print "<option value='descShort' ".$sel3.">".DESCRIPTION_COURTE."</option>";
	print "<option value='descLong' ".$sel4.">".DESCRIPTION_LONGUE."</option>";
	print "<option value='im' ".$sel5.">".IMAGE."</option>";
		print "<option value='----' ".$sel7.">----</option>";
	print "<option value='categ_main' ".$sel16.">".CAT_MAIN."</option>";
	print "<option value='categ' ".$sel15.">".CAT."</option>";
	print "</select>";
	print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	print "<input type='hidden' name='search' value='yes'>";
	print "&nbsp;";
	print "<input type='submit' class='knop' value='".SEARCH."'>";
	print "</td>";
	print "</form>";
	print "</tr>";
	
	## Afficher resultat recherche
	if(isset($resultSearch) AND count($resultSearch)>0) {
		print "<tr><td align='left'>";
		print "<table border='0' width='100%' cellpadding='4' cellspacing='1' align='center' class='TABLE'><tr style='background:#CCCCCC' height='23'>";
		print "<td width='1'><b>#</b></td><td width='30'><b>ID</b></td><td align='center'><b>".ARTICLE."</b></td><td align='center'><b>".CAT_MAIN." > ".CAT."</b></td><td width='50'><b>REF</b></td></tr><tr>";
		foreach($resultSearch AS $item) {
			print $item."</tr></tr>";
		}
		print "</table>";
		print "</td></tr>";
	}
	if(isset($resultSearchNada) AND !empty($resultSearchNada)) {
		print "<tr><td align='left'>";
		print $resultSearchNada;
		print "</td></tr>";
	}
	
	print "</table>";
	print "<br>";

	## Chercher/Modifier
	print '<table border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700"><tr><td align="center">';
	print '<img src="im/_im2.gif" align="absmiddle">&nbsp;&nbsp;<a href="artikel_zoeken.php"><b>'.A71.'</b></a>';
	print '</td></tr>';
	print '</table>';
	print "<br>";
	
 
	if(isset($_GET['list']) AND $_GET['list']==1) {
		print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#FFCC00; align='center'>";
	   	print "<div align='center'><a href='".$_SERVER['PHP_SELF']."'><b>".NE_PAS_AFFICHER."</b></a></div>";
	   	print "</td></tr></table>";
	}
	else {
	  	print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#CCFF99;  align='center'>";
	   	print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?list=1'><b>".AFFICHER."</b></a></div>";
	   	print "</td></tr></table>";
	}


	if(isset($_GET['list']) AND $_GET['list']==1) {
		print "<br>";
		print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'>";
		print "<tr>";
		print "<td height='30' align='left'><b>".A6."</b></td>";
		recur($data,"0","0",$_SESSION['lang']);
		print "</tr>";
		print "</table>";
		
		print "<br>";
		print "<div align='left'>";
		print A3;
		print "</div>";
		print "<br>";
	}
}
else {
		print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'>";
		print "<tr>";
		print "<td>";
		print "<p align='center' class='fontrouge'><b>".A50."</b>";
		print "</tr>";
		print "</td></table>";
}
?>
<br><br><br>
</body>
</html>