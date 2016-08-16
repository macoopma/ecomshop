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

// alles verwijderen
if(isset($_GET['del']) AND $_GET['del']=='all') {
	mysql_query("TRUNCATE TABLE discount_on_quantity") or die (mysql_error());
}

// verwijderen
if(isset($_GET['idZ']) AND $_GET['idZ']!=='') {
	mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_id='".$_GET['idZ']."'") or die (mysql_error());
}

// update
if(isset($_POST['action']) AND $_POST['action']=='update') {
	foreach($_POST['toto'] AS $itemId) {
		$dfjh[] = implode("|",$itemId);
	}
	for($i=0; $i<=count($dfjh)-1; $i++) {
		$jj = explode("|",$dfjh[$i]);
		// update
		mysql_query("UPDATE discount_on_quantity 
						SET 
						discount_qt_qt = '".$jj[1]."',
						discount_qt_discount = '".$jj[2]."',
						discount_qt_value = '".$jj[3]."'
						WHERE discount_qt_id = '".$jj[0]."'") or die (mysql_error());
	}
	$message = "<p align='center' style='color:#FF0000; font-size:12px;'><b>".PRIX_SAVED_OK."</b></p>";
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
<form action="korting_op_aantal_wijzigen.php" method="POST" name="formAdd" onsubmit="return check_form()">
<?php
if(isset($message) AND $message!=="") print $message;
$c="#e0e0e0";
$toto="";

$prods2 = mysql_query("SELECT d.*, p.products_ref, p.products_id, p.products_name_".$_SESSION['lang']."
						FROM discount_on_quantity AS d
						LEFT JOIN products AS p ON (p.products_id = d.discount_qt_prod_id)
						ORDER BY d.discount_qt_prod_id, d.discount_qt_qt ASC") or die (mysql_error());

if(mysql_num_rows($prods2) > 0) {
	print "<table border='0' cellpadding='5' cellspacing='0' width='700' align='center' class='TABLE'><tr bgcolor='#FFFFFF'>";
	print "<td width='1'>&nbsp;</td>";
	print "<td><b>ID #</b></td>";
	print "<td align='left'><b>".ARTICLE."</b></td>";
	print "<td><b>Hoeveelheid</b></td>";
	print "<td><b>".REDUC."</b></td>";
	print "<td>&nbsp;</td>";
	while($prods2Result = mysql_fetch_array($prods2)) {
		if($toto==$prods2Result['discount_qt_prod_id']) {$c="#e8e8e8"; $bull=''; $style="style='font-style:italic;'";} else {$c="#FFFF00"; $bull="<img src='im/fleche_right.gif' border='0'>"; $style="";}
		$toto = $prods2Result['discount_qt_prod_id'];
		print "<input type='hidden' name='toto[".$prods2Result['discount_qt_id']."][]' value='".$prods2Result['discount_qt_id']."'>";
			print "</tr><tr bgcolor='".$c."'>";
			print "<td align='left'>";
			print $bull;
			print "</td>";
			print "<td align='left' ".$style.">";
			print ($bull=='')? $prods2Result['discount_qt_prod_id'] : "<a href='artikel_wijzigen_details.php?id=".$prods2Result['discount_qt_prod_id']."'><b>".$prods2Result['discount_qt_prod_id']."</b></a>";
			print "</td>";
			print "<td align='left' ".$style.">";
			print $prods2Result['products_name_'.$_SESSION['lang']];
			print "</td>";
			print "<td align='left'>";
			print "<input type='text' class='vullen' size='4' name='toto[".$prods2Result['discount_qt_id']."][]' value='".$prods2Result['discount_qt_qt']."'>";
			print "</td>";
			print "<td align='left'>";
			print "<input type='text' class='vullen' size='4' name='toto[".$prods2Result['discount_qt_id']."][]' value='".$prods2Result['discount_qt_discount']."'>&nbsp;";
				if($prods2Result['discount_qt_value']=="%") $sel1="selected"; else $sel1="";
				if($prods2Result['discount_qt_value']=="euro") $sel2="selected"; else $sel2="";
			print "<select name='toto[".$prods2Result['discount_qt_id']."][]'>";
			print "<option value='%' ".$sel1.">%</option>";
			print "<option value='euro' ".$sel2.">".$symbolDevise."</option>";
			print "</select>";
			print "</td>";
			print "<td>";
			print "<a href='korting_op_aantal_wijzigen.php?idZ=".$prods2Result['discount_qt_id']."'><img src='im/no_stock.gif' alt='".SUPPRIMER."' title='".SUPPRIMER."' border='0'></a>";
			print "</td>";
	}
	print "</tr></table>";
	print "<input type='hidden' name='action' value='update'>";
	print "<p align='center'><input type='submit' class='knop' value='Bevestigen'></p>";
	print "<br><p align='center'><table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'><tr><td><center><a href='korting_op_aantal_wijzigen.php?del=all'>".TOUT_SUPPRIMER."</a></tr></td></table>";
}
?>
</form>
</body>
</html>
