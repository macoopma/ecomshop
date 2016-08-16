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

$prods = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id
                      FROM products as p
                      LEFT JOIN specials as s
                      USING (products_id)
                      WHERE p.products_ref != 'GC100'
                      AND p.products_visible = 'yes'
                      AND p.products_forsale = 'yes'
                      AND p.products_price > '0'
                      ORDER BY products_name_".$_SESSION['lang']."");

$cats = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                      FROM categories
                      WHERE categories_noeud='L' 
					  AND categories_visible='yes'
                      ORDER BY parent_id DESC") or die (mysql_error());


function searchPath($cat_name, $parent_id, $lang, $catId) {
    global $productPath;
    $subCatQuery = mysql_query("SELECT categories_name_".$lang.", parent_id FROM categories WHERE categories_id = '".$parent_id."'");
    $arrSubCategory = mysql_fetch_array($subCatQuery);
    $subCatParentId = $arrSubCategory['parent_id'];
    $subCatName = $arrSubCategory['categories_name_'.$lang];
    if($subCatParentId == '0') {
    	$productPath[1][$catId][] = $subCatName;
    }
    else {
        $productPath[1][$catId][] = $subCatName;
        searchPath($subCatName, $subCatParentId, $lang, $catId);
    }
    $pathReturn = implode(" > ", array_reverse($productPath[1][$catId]));
    $pathReturn = $pathReturn." > ".$cat_name;
    return $pathReturn;                   
}

 
if(isset($_GET['del']) AND $_GET['del']=='all') {
	mysql_query("TRUNCATE TABLE discount_on_quantity") or die (mysql_error());
}

 
if(isset($_GET['idZ']) AND $_GET['idZ']!=='') {
	mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_id='".$_GET['idZ']."'") or die (mysql_error());
}


 
if(isset($_POST['action']) AND $_POST['action']=="ok") {
	if(isset($_POST['prod_id']) OR isset($_POST['cat_id']) AND isset($_POST['qt']) AND isset($_POST['val'])) {
		if(($_POST['prod_id']!=='no' OR $_POST['cat_id']!=='no') AND is_numeric($_POST['qt']) AND $_POST['qt']>0 AND is_numeric($_POST['val']) AND $_POST['val']>0) {
			if($_POST['prod_id']!=='no' AND $_POST['cat_id']!=='no') {
				$message = "<p align='center' style='color:#FF0000; font-size:12px;'>".SELECTIONNER_ARTICLE_OU_CATEGORIE."</p>";
			}
			else {
				if($_POST['prod_id']!=='no') {
					// check if already active
					$productsQueryNum = mysql_query("SELECT discount_qt_id
													FROM discount_on_quantity 
													WHERE discount_qt_prod_id = '".$_POST['prod_id']."' 
													AND discount_qt_qt = '".$_POST['qt']."' 
													AND discount_qt_discount = '".$_POST['val']."'
													AND discount_qt_value = '".$_POST['ext']."'
													") or die (mysql_error());
					if(mysql_num_rows($productsQueryNum)>0) {
						$message = "<p align='center' style='color:#FFFFFF; font-size:12px; background:#000000; padding:8px;'><b>".PRIX_SAVED_NOT_OK."</b></p>";
					}
					else {
 
						mysql_query("INSERT INTO discount_on_quantity
									SET
									discount_qt_prod_id = '".$_POST['prod_id']."',
									discount_qt_qt = '".$_POST['qt']."',
									discount_qt_discount = '".$_POST['val']."',
									discount_qt_value = '".$_POST['ext']."'
									") or die (mysql_error());
						$message = "<p align='center' style='color:#FF0000; font-size:12px;'><b>".PRIX_SAVED_OK."</b></p>";
					}
				}
				else {
				
 
					if($_POST['cat_id']=="all") $addToSelect = ""; else $addToSelect = " AND categories_id = '".$_POST['cat_id']."'";
					$productsQuery = mysql_query("SELECT products_id, products_name_".$_SESSION['lang']." 
													FROM products 
													WHERE products_ref!='GC100' 
													AND products_price > '0' 
													AND products_visible = 'yes' 
													AND products_forsale = 'yes'
													".$addToSelect." 
													") or die (mysql_error());
					while($productsResult = mysql_fetch_array($productsQuery)) {
    
						$productsQueryNum = mysql_query("SELECT discount_qt_id
														FROM discount_on_quantity 
														WHERE discount_qt_prod_id = '".$productsResult['products_id']."' 
														AND discount_qt_qt = '".$_POST['qt']."' 
														AND discount_qt_discount = '".$_POST['val']."'
														AND discount_qt_value = '".$_POST['ext']."'
														") or die (mysql_error());
						if(mysql_num_rows($productsQueryNum)>0) {
 
							mysql_query("DELETE FROM discount_on_quantity 
											WHERE 
											discount_qt_prod_id = '".$productsResult['products_id']."' 
											AND discount_qt_qt = '".$_POST['qt']."' 
											AND discount_qt_discount = '".$_POST['val']."'
											AND discount_qt_value = '".$_POST['ext']."'
											") or die (mysql_error());
						}
 
						mysql_query("INSERT INTO discount_on_quantity
									SET
									discount_qt_prod_id = '".$productsResult['products_id']."',
									discount_qt_qt = '".$_POST['qt']."',
									discount_qt_discount = '".$_POST['val']."',
									discount_qt_value = '".$_POST['ext']."'
									") or die (mysql_error());
						$message = "<p align='center' style='color:#FF0000; font-size:12px;'><b>".PRIX_SAVED_OK."</b></p>";
					}
				}
			}
		}
		else {
			$message = "<p align='center' style='color:#FF0000; font-size:12px;'><b>".VEUILLEZ_RECOMMENCER."</b></p>";
			if($_POST['prod_id']=='no' AND $_POST['cat_id']=='no') $message = "<p align='center' style='color:#FF0000; font-size:12px;'>".SELECTIONNER_ARTICLE_OU_CATEGORIE."</p>";
		}
	}
	else {
		$message = "<p align='center' style='color:#FF0000; font-size:12px;'><b>".VEUILLEZ_RECOMMENCER."</b></p>";
	}
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";
    var prod_id = document.formAdd.prod_id.value;
    var cat_id = document.formAdd.cat_id.value;
    var qt = document.formAdd.qt.value;
    var val = document.formAdd.val.value;
    
if(prod_id == 'no' && cat_id == 'no') {
      error_message = error_message + "<?php print SELECTIONNER_ARTICLE_OU_CATEGORIE_SHORT;?>\n";
      error = 1;
}
if(prod_id !== 'no' && cat_id !== 'no') {
      error_message = error_message + "<?php print SELECTIONNER_ARTICLE_OU_CATEGORIE_SHORT;?>\n";
      error = 1;
}
if(qt == '' || isNaN(qt)== true) {
      error_message = error_message + "<?php print CHAMP_NON_VALIDE.": ".QT_ARTICLE;?>\n";
      error = 1;
}
if(val == '' || isNaN(val)== true) {
      error_message = error_message + "<?php print CHAMP_NON_VALIDE.": ".VALEUR_REDUC;?>\n";
      error = 1;
}

  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print AJOUTER_PRIX_DEGRESSIF;?></p>
<?php
if(isset($message)) print $message;

if(mysql_num_rows($prods) > 0) {
?>
<form action="korting_op_aantal_toevoegen.php" method="POST" name="formAdd" onsubmit="return check_form()">
<input type="hidden" value="ok" name="action">

        <table border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE" width="700">
        <tr>
         <td><?php print SELECTIONNER_ARTICLE;?></td>
        <td align="left">
		<?php
		print  "<select name='prod_id'>";
			print "<option value='no'>---------</option>";
			while ($produit = mysql_fetch_array($prods)) {
				print "<option name='id' value='".$produit['products_id']."'>";
				print $produit['products_name_'.$_SESSION['lang']]."&nbsp;&nbsp;&nbsp;&nbsp;[ID# ".$produit['products_id']."]";
				print "</option>";
			}
		print "</select>";
		?>
        </td>
        </tr>
		<tr>
        <td colspan='2' align='center'><b><?php print "<u>".OU."</u>";?></b></td>
		</tr>
        <tr>
         <td><?php print SELECTIONNER_CAT;?></td>
        <td align="left">
<?php
		print  "<select name='cat_id'>";
			print "<option value='no'>---------</option>";
			print "<option value='all'>".TOUTES_CAT."</option>";
			print "<option value='no'>---------</option>";
			while($categorie = mysql_fetch_array($cats)) {
				print "<option value='".$categorie['categories_id']."'>";
				##print $categorieParent['categories_name_'.$_SESSION['lang']]."&nbsp;&nbsp;>&nbsp;&nbsp;".$categorie['categories_name_'.$_SESSION['lang']];
				print searchPath($categorie['categories_name_'.$_SESSION['lang']], $categorie['parent_id'], $_SESSION['lang'], $categorie['categories_id']);
				print "</option>";
			}
		print "</select>";
?>
        </td>
        </tr><tr>
        <td><?php print QT_ARTICLE;?> (1)</td>
		<td><input type="text" size="4" value="" class="vullen" name="qt">
		</td>
		</tr><tr>
        <td><?php print VALEUR_REDUC;?></td>
		<td><input type="text" size="4" value="" class="vullen" name="val">
		<select name="ext">
		<option value="%">%</option>
		<option value="euro"><?php print $symbolDevise;?></option>
		</select>
		&nbsp;<?php print PAR_ARTICLE;?>
		</td>
		</tr><tr>
        <td height="40" colspan="2" align="center">
        	<input type="submit" class="knop" value="<?php print AJOUTER;?>"">
        </td>
        </tr>
        </table>
        <br>
        <table border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr><td>
        <center>(1) <?php print QT_OBLIGATOIRE_POUR_REDUC;?>
        </tr></td></table>
</form>
<?php

$prods2 = mysql_query("SELECT d.*, p.products_ref, p.products_id, p.products_name_".$_SESSION['lang']."
						FROM discount_on_quantity AS d
						LEFT JOIN products AS p ON (p.products_id = d.discount_qt_prod_id)
						ORDER BY d.discount_qt_prod_id, d.discount_qt_qt ASC") or die (mysql_error());

if(mysql_num_rows($prods2) > 0) {
	$c="#e0e0e0";
	$toto="";
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr bgcolor='#FFFFFF'>";
	print "<td width='1'>&nbsp;</td>";
	print "<td><b>ID #</b></td>";
	print "<td align='left'><b>".ARTICLE."</b></td>";
	print "<td><b>Hoeveelheid</b></td>";
	print "<td><b>".REDUC."</b></td>";
	print "<td>&nbsp;</td>";
	while($prods2Result = mysql_fetch_array($prods2)) {
		if($toto==$prods2Result['discount_qt_prod_id']) {$c="#F1F1F1"; $bull='';} else {$c="#FFFF00"; $bull="<img src='im/fleche_right.gif' border='0'>";}
		$toto = $prods2Result['discount_qt_prod_id'];
			print "</tr><tr bgcolor='".$c."'>";
			print "<td align='left'>";
			print $bull;
			print "</td>";
			print "<td align='left'>";
			print ($bull=='')? $prods2Result['discount_qt_prod_id'] : "<a href='artikel_wijzigen_details.php?id=".$prods2Result['discount_qt_prod_id']."'><b>".$prods2Result['discount_qt_prod_id']."</b></a>";
			print "</td>";
			print "<td align='left'>";
			print $prods2Result['products_name_'.$_SESSION['lang']];
			print "</td>";
			print "<td align='left'>";
			print $prods2Result['discount_qt_qt'];
			print "</td>";
			print "<td align='left'>";
			$s = ($prods2Result['discount_qt_value']=="euro" AND $prods2Result['discount_qt_discount']>1)? "s" : "";
			print $prods2Result['discount_qt_discount']." ".$prods2Result['discount_qt_value'].$s;
			print "</td>";
			print "<td>";
			print "<a href='korting_op_aantal_toevoegen.php?idZ=".$prods2Result['discount_qt_id']."'><img src='im/no_stock.gif' alt='".SUPPRIMER."' title='".SUPPRIMER."' border='0'></a>";
			print "</td>";
	}
	print "</tr></table>";
	print "<br><p align='center'><table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'><tr><td><center><a href='korting_op_aantal_wijzigen.php?del=all'>".TOUT_SUPPRIMER."</a></tr></td></table>";
}
}
?>
</body>
</html>
