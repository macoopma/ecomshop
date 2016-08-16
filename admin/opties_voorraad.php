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
if(isset($_GET['id'])) $_POST['id'] = $_GET['id'];
$message="";

 
function combine_all($tab_values, $result = '') {
	GLOBAL $combinaison;
	$tab0 = array_shift($tab_values);
	foreach ($tab0 as $value) {
	    $res2 = $result ? $result." | ".$value : $value;
	    if (count($tab_values) == 0) {
	     $combinaison[] = $res2;
	    }
		else {
	     combine_all($tab_values, $res2);
	    }
	}
}

 
if(isset($_POST['action']) AND $_POST['action']=="updateStock") {
 
	for($i=0; $i<=count($_POST['ref'])-1; $i++) {
	
 
	if(isset($_FILES["uploadDeclinaisonImage"]["name"][$i]) AND !empty($_FILES["uploadDeclinaisonImage"]["name"][$i])) {
 
		$nomFichier    = $_FILES["uploadDeclinaisonImage"]["name"][$i];
 
		$nomTemporaire = $_FILES["uploadDeclinaisonImage"]["tmp_name"][$i];
 
		$typeFichier   = $_FILES["uploadDeclinaisonImage"]["type"][$i];
 
		$poidsFichier  = $_FILES["uploadDeclinaisonImage"]["size"][$i];
 
		$codeErreur    = $_FILES["uploadDeclinaisonImage"]["error"][$i];
 
		$chemin = "../im/artikelen/";
		if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
			if(copy($nomTemporaire, $chemin.$nomFichier)) {
				$_POST['im'][$i] = str_replace("../","",$chemin).$nomFichier;
				$message.= "<center><font style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier." ".A_REUSSI.".<br></font>";
			}
			else {
				$_POST['im'][$i] = "";
				$message.= "<center><font style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier." ".A_ECHOUE."<br></font>";
			}
		}
		else {
			$_POST['im'][$i] = "";
			$message.= "<center><font style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier." ".A_ECHOUE."<br></font>";
		}
	}

		$totalStockUpdated[] = $_POST['stock'][$i];
 
		if(isset($_POST['active'][$i])) {
			$checkedResponse = "yes";
			$stockResponse = $_POST['stock'][$i];
		}
		else {
			$checkedResponse = "no";
			$stockResponse = 0;
		}
 
		$notAllowed = array("\'","'",",","+");
		$_POST['ref'][$i] = str_replace($notAllowed, "", $_POST['ref'][$i]);
 
		mysql_query("INSERT INTO products_options_stock
		             (
		              products_options_stock_prod_id,
		              products_options_stock_prod_name,
		              products_options_stock_ref,
		              products_options_stock_ean,
		              products_options_stock_im,
		              products_options_stock_stock,
		              products_options_stock_active
		              )
		             VALUES ('".$_POST['id']."',
		                     '".$_POST['name'][$i]."',
		                     '".$_POST['ref'][$i]."',
		                     '".$_POST['ean'][$i]."',
		                     '".$_POST['im'][$i]."',
		                     '".$stockResponse."',
		                     '".$checkedResponse."'
		                     )
		             ") or die (mysql_error());
	}
 
	mysql_query("UPDATE products SET products_qt = ".array_sum($totalStockUpdated)." WHERE products_id = '".$_POST['id']."'");
}

 
if(isset($_POST['action']) AND $_POST['action']=="updateStock2") {
 
	for($i=0; $i<=count($_POST['ref'])-1; $i++) {
 
	if(isset($_FILES["uploadDeclinaisonImage"]["name"][$i]) AND !empty($_FILES["uploadDeclinaisonImage"]["name"][$i])) {
 
		$nomFichier    = $_FILES["uploadDeclinaisonImage"]["name"][$i];
 
		$nomTemporaire = $_FILES["uploadDeclinaisonImage"]["tmp_name"][$i];
 
		$typeFichier   = $_FILES["uploadDeclinaisonImage"]["type"][$i];
 
		$poidsFichier  = $_FILES["uploadDeclinaisonImage"]["size"][$i];
 
		$codeErreur    = $_FILES["uploadDeclinaisonImage"]["error"][$i];
 
		$chemin = "../im/artikelen/";
		if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
			if(copy($nomTemporaire, $chemin.$nomFichier)) {
				$_POST['im'][$i] = str_replace("../","",$chemin).$nomFichier;
				$message.= "<center><font style= 'color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".<br></font>";
			}
			else {
				$_POST['im'][$i] = "";
				$message.= "<center><font style=='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."<br></font>";
			}
		}
		else {
			$_POST['im'][$i] = "";
			$message.= "<center><font style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."<br></font>";
		}
	}

 
		$totalStockUpdated[] = $_POST['stock'][$i];
 
		if(isset($_POST['active'][$i])) {
			$checkedResponse = "yes";
			$stockResponse = $_POST['stock'][$i];
		}
		else {
			$checkedResponse = "no";
			$stockResponse = 0;
		}
 
		$notAllowed = array("\'","'",",","+");
		$_POST['ref'][$i] = str_replace($notAllowed, "", $_POST['ref'][$i]);
 
		mysql_query("UPDATE products_options_stock 
						SET 
						products_options_stock_prod_id = '".$_POST['id']."',
						products_options_stock_prod_name = '".$_POST['name'][$i]."',
						products_options_stock_ref = '".$_POST['ref'][$i]."',
						products_options_stock_ean = '".$_POST['ean'][$i]."',
						products_options_stock_im = '".$_POST['im'][$i]."',
						products_options_stock_stock = '".$stockResponse."',
						products_options_stock_active = '".$checkedResponse."'
						WHERE products_options_stock_id = '".$_POST['stockId'][$i]."'
					") or die (mysql_error());						
	}
 
	mysql_query("UPDATE products SET products_qt = ".array_sum($totalStockUpdated)." WHERE products_id = '".$_POST['id']."'");
}

 
if(isset($_POST['action']) AND $_POST['action']=="add") {
	foreach($_POST['valuesToAdd'] AS $item) {
		
		mysql_query("INSERT INTO products_options_stock
		             (
		              products_options_stock_prod_id,
		              products_options_stock_prod_name,
		              products_options_stock_stock
		              )
		             VALUES ('".$_POST['id']."',
		                     '".$item."',
		                     '0'
		                     )
		             ") or die (mysql_error());
	}
}

 
if(isset($_POST['action']) AND $_POST['action']=="delete") {
	foreach($_POST['valuesToDelete'] AS $item) {
		
		mysql_query("DELETE FROM products_options_stock 
						WHERE products_options_stock_prod_name = '".$item."'
						AND products_options_stock_prod_id = '".$_POST['id']."'
					") or die (mysql_error());
	}
}

 
if(isset($_GET['action']) AND $_GET['action']=="deleteAll") {
 
		mysql_query("DELETE FROM products_options_stock 
						WHERE products_options_stock_prod_id = '".$_GET['id']."'
					") or die (mysql_error());
}

 
if(isset($_POST['nb']) AND $_POST['nb']!=="" AND is_numeric($_POST['nb'])) {
 
	mysql_query("UPDATE products_options_stock 
					SET 
					products_options_stock_stock = '".$_POST['nb']."'
					WHERE products_options_stock_prod_id = '".$_POST['id']."'
				") or die (mysql_error());
}

 
if(isset($_GET['action']) AND $_GET['action']=="importStock") {
	$importOk = "yes";
    $imp = "<p align='center' class='fontrouge'>";
    
	$imp = "<br><table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'>";
	$imp.= '<div align="center"><img src="im/zzz.gif" height="4" width="1"></div>';
    $imp.= CETTE_ACTION_IMPORTERA;
	$imp.= "<p align='center'>";
    $imp.= "<a href='opties_voorraad.php?id=".$_GET['id']."&action=go'><b><span class='fontrouge'>".OUI."</span></b></a> | <a href='opties_voorraad.php?id=".$_GET['id']."'><b><span class='fontrouge'>".NON."</span></b></a>";
    $imp.= "</p>";
	$imp.= "</p>";
	$imp.= "</td></tr></table>";
}

 
if(isset($_GET['action']) and $_GET['action'] == "go") {
    $file = "import/import_options.txt";
    $check = filesize($file);
    if($check!==0) {
    $fp = fopen("import/import_options.txt","r");
        while ($line = fgets($fp, 1000) ) {
            if(isset($line)) {
            	$explodeLine = explode("\t", $line);
				$stock[$explodeLine[0]]=$explodeLine[1]."*!*!*".$explodeLine[2]."*!*!*".$explodeLine[3]."*!*!*".$explodeLine[4]."*!*!*".$explodeLine[5];
			}
			else {
				$stock[]='';
			}
        }
    fclose($fp);
    }
    else {
        $stock[] = '';
        $stockImp = "<p align='center' class='fontrouge'><b><a href='".$file."' target='_blank'><span class='fontrouge'>".$file."</span></a> ".VIDE."</b></p>";
    }
 
    if(count($stock) > 0) {
        foreach($stock as $key => $value) {
          if($value == "") {
            unset($stock[$key]);
          }
        }
    }
 
    if(count($stock) > 0) {
        $stock = array_unique($stock);
    }

    if(count($stock) > 0) {
 
	    foreach($stock AS $key => $value) {
 
	        $stockCheck = mysql_query("SELECT products_options_stock_prod_name 
										FROM products_options_stock 
										WHERE products_options_stock_prod_name='".trim($key)."' 
										AND products_options_stock_prod_id = '".$_GET['id']."'");
	        if(mysql_num_rows($stockCheck) > 0) {
	        	$explodeValue = explode('*!*!*',$value);
 
	        	mysql_query("UPDATE products_options_stock 
								SET products_options_stock_ref = '".$explodeValue[0]."',
								products_options_stock_ean = '".$explodeValue[1]."',
								products_options_stock_im = '".$explodeValue[2]."',
								products_options_stock_stock = '".$explodeValue[3]."',
								products_options_stock_active = '".trim($explodeValue[4])."'
								WHERE products_options_stock_prod_name = '".trim($key)."'
								AND products_options_stock_prod_id = '".$_GET['id']."'");
								
	        	$stockImp = "<p align='center' class='fontrouge'><b>".STOCK_IMPORTE_AVEC_SUCCES."</b></p>";
	        }
	    }
  
	    $optQueryUpdate = mysql_query("SELECT products_options_stock_stock FROM products_options_stock WHERE products_options_stock_prod_id = '".$_GET['id']."'");
	    if(mysql_num_rows($optQueryUpdate)>0) {
	    	while($optQueryUpdateResult = mysql_fetch_array($optQueryUpdate)) {
	    		$totalStockUpdateZ[] = $optQueryUpdateResult['products_options_stock_stock'];
	    	}
	    }
	    if(isset($totalStockUpdateZ) AND count($totalStockUpdateZ)>0) {
			mysql_query("UPDATE products SET products_qt = ".array_sum($totalStockUpdateZ)." WHERE products_id = '".$_GET['id']."'");
		}
    }
}

 
if(isset($_GET['del']) AND $_GET['del']=='tab_values' AND isset($_SESSION['tab_values'])) unset($_SESSION['tab_values']);
if(!isset($_SESSION['tab_values'])) {
	$optionNameQueryS = mysql_query("SELECT a.products_options_id, a.products_option, z.products_options_name, a.note_option_1, a.note_option_2, a.note_option_3
										FROM products_id_to_products_options_id as a
										LEFT JOIN products_options as z
										ON(a.products_options_id = z.products_options_id)
										WHERE products_id = '".$_POST['id']."'
										ORDER BY z.products_options_name");
	if(mysql_num_rows($optionNameQueryS) > 0) {
		while($optionNameS = mysql_fetch_array($optionNameQueryS)) {
			$listValue = explode(",",$optionNameS['products_option']);
			$listValueNum = count($listValue)-1;
			for($i=0; $i<=$listValueNum-1; $i++) {
				$val = explode("::", $listValue[$i]);
				$tab_stock[$optionNameS['products_options_name']][] = $val[0];
			}
		}
		if(isset($tab_stock) AND count($tab_stock)>0) $_SESSION['tab_values'] = array_values($tab_stock);
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
<p align="center" class="largeBold"><?php print GESTION_STOCK_DECLINAISONS;?></p>

<?php
 
$ProductNameQuery = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", products_qt, products_ref, products_download FROM products WHERE products_id = '".$_POST['id']."'");
$productName = mysql_fetch_array($ProductNameQuery);


print "<div align='center'>";
print "<a href='artikel_wijzigen_details.php?id=".$productName['products_id']."'><span style='font-size:12px;'><b>".$productName['products_name_'.$_SESSION['lang']]."</b></span></a> <span style='font-size:12px;'> - Ref: ".$productName['products_ref']."</span>";
if($productName['products_download']=='yes') {
	print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
	print "<table border='0' cellpadding='3' cellspacing='0'><tr>";
	print "<td style='background:#FFFFFF;'><img src='im/download.gif' border='0'></td>";
	print "<td style='background:#FFFFFF; color:#FF0000;'><i>".TELECHARGEMENT."</i></td>";
	print "</tr></table>";
}
print "</div>";



$optQuery = mysql_query("SELECT * FROM products_options_stock WHERE products_options_stock_prod_id = '".$_POST['id']."' ORDER BY products_options_stock_prod_name ASC");
if(mysql_num_rows($optQuery)>0) {

	while($optResultZ = mysql_fetch_row($optQuery)) {
		$optResultArray[]=$optResultZ[2];
	}

	$default_order="products_options_stock_prod_name";
	if(!isset($_GET['dir'])) $_GET['dir'] = "ASC";
	if(isset($_GET['sort']) AND $_GET['sort']) $default_order = $_GET['sort'];
	
	$optQuery = mysql_query("SELECT * FROM products_options_stock WHERE products_options_stock_prod_id = '".$_POST['id']."' ORDER BY ".$default_order." ".$_GET['dir']."");
	if($_GET['dir']=="ASC") $_GET['dir']="DESC"; else $_GET['dir']="ASC";
	
	print "<p>";
	print "<div align='center'><table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td><center>";
	print "<a href='opties_wijzigen.php?list=1'>".CHANGER_ARTICLE."</a>";
	print " | ";
	print "<a href='opties_details.php?id=".$_POST['id']."'>".RETOUR_OPTIONS."</a>";
	print " | ";
	print "<a href='opties_voorraad.php?id=".$_POST['id']."&action=deleteAll'>".TOUT_SUPPRIMER."</a>";
	print " | ";
	print "<a href='x_exporteer_opties_voorraad.php?id=".$_POST['id']."&action=exportStock'>".EXPORTER."</a>";
	print " | ";
	print "<a href='opties_voorraad.php?id=".$_POST['id']."&action=importStock'>".IMPORTER."</a>";
	print "</div>";
	print "</tr></td></table>";

	if(isset($imp)) print $imp;
	if(isset($stockImp) AND $stockImp!=='') print $stockImp;
	if(isset($message)) print $message;
	
	
	print "<form method='POST' action='opties_voorraad.php'>";
	print "<p align='center'>";
	print METTRE_LE_STOCK_A." <input type='text' class='vullen' size='3' name='nb' value=''>";
	print "&nbsp;<input type='submit' class='knop' value='  OK  '>";
	print "<input type='hidden' name='id' value='".$_POST['id']."'>";
	print "</p>";
	print "</form>";


	print "<div align='center'><a href='site_config.php#Stock'>".SEUIL_STOCK."</a> ".$seuilStock."</div>";

	print "</p>";
	$i=0;
	$c="#FFFFFF";
	$theme = array("#FFFFFF", "#FFFFFF");
	$e = 0;

 
 
	print "<form method='POST' action='opties_voorraad.php' enctype='multipart/form-data'>";
	print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE'><tr bgcolor='#FFFFFF' height='35'>";
	print "<td><b>#</b></td>";
	print "<td align='left'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_prod_name&dir=".$_GET['dir']."'><b>".DECLINAISONS_ARTICLE."</b></a></td>";
	print "<td align='left'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_ref&dir=".$_GET['dir']."'><b>".REFEENCES."</b></a></td>";
	print "<td align='left'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_ean&dir=".$_GET['dir']."'><b>".REF_FOURNISSEUR."</b></a></td>";
	print "<td align='left'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_im&dir=".$_GET['dir']."'><b>".IMAGE."</b></a></td>";
	print "<td align='left'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_stock&dir=".$_GET['dir']."'><b>".STOCK."</b></a></td>";
	print "<td align='center'><a href='opties_voorraad.php?id=".$_POST['id']."&sort=products_options_stock_active&dir=".$_GET['dir']."'><b>".ACTIVE."</b></a></td>";
	print "</tr><tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
	
	while($optResult = mysql_fetch_array($optQuery)) {
		$i=$i+1;
		$ii = $i-1;
		$combinaisonBdd[] = $optResult['products_options_stock_prod_name'];
		if($optResult['products_options_stock_active']=='yes') $totalStock[] = $optResult['products_options_stock_stock'];		
		$explodeName = explode(" | ",$optResult['products_options_stock_prod_name']);
		if($i<sizeof($optResultArray)) $explodeArray = explode(" | ",$optResultArray[$i]);
		if($explodeName[0]!==$explodeArray[0]) {
			$e = $e+1;
			if(($e%2) == 0) $o=1; else $o=0;
			$c = $theme[$o];
		}

		if($optResult['products_options_stock_active']=='no') {
			$prodName = "<s>".$optResult['products_options_stock_prod_name']."</s>";
			$va="color:#FF0000; padding:3px; border:0px #000000 dotted;";
			$_ref = "<input type='hidden' name='ref[]' value=''>";
			$_ref.= "<div align='left' style='font-size:20px; color:#FF0000;'>--</div>";
			$_ean = "<input type='hidden' name='ean[]' value=''>";
			$_ean.= "<div align='left' style='font-size:20px; color:#FF0000;'>--</div>";
		}
		else {
			$prodName = $optResult['products_options_stock_prod_name'];
			$va="color:#000000; padding:3px; border:1px #000000 dotted;";
			$_ref = "<input type='text' class='vullen' size='15' name='ref[]' value='".$optResult['products_options_stock_ref']."'>";
			$_ean = "<input type='text' class='vullen' size='15' name='ean[]' value='".$optResult['products_options_stock_ean']."'>";
		}
		print "<input type='hidden' name='name[]' value='".$optResult['products_options_stock_prod_name']."'>";
		print "<input type='hidden' name='stockId[]' value='".$optResult['products_options_stock_id']."'>";
		
		print "<td valign=top align=left>".$i."&nbsp;-&nbsp;</td>";
		
		print "<td valign=top align=left><div style='".$va."'>".$prodName."</div></td>";
		
		print "<td valign=top align=left>".$_ref."</td>";
		
		print "<td valign=top align=left>".$_ean."</td>";
		
		print "<td valign=top align=left>";
		if($optResult['products_options_stock_active']=='yes') {
?>
	<input type='file' name='uploadDeclinaisonImage[]' clas='vullen' size='30'>

	<div><img src='im/zzz.gif' width='1' height='1'></div>
	<input type="text" size="50" clas='vullen' name="im[]" value="<?php print $optResult['products_options_stock_im'];?>">
	<?php
	
	if(!empty($optResult['products_options_stock_im'])) {
		$_image = explode("|",$optResult['products_options_stock_im']);
		if(count($_image)>1) {
			print "<br><a href='uitleg.php?idOpt=".$optResult['products_options_stock_id']."' target='_blank'><b>".A23."</b></a>";
		}
		else {
			print "<br><a href='../".$optResult['products_options_stock_im']."' target='_blank'><b>".A23."</b></a>";
		}
	}

	?>

<?php
		}
		else {
			print "<input type='hidden' name='im[]' value=''>";
			print "<input type='hidden' name='uploadDeclinaisonImage[]' value=''>";
			print "<div align='left' style='font-size:20px; color:#FF0000;'>--</div>";
		}
		
		print "</td>";
		
		$styleStock="";
		if($optResult['products_options_stock_stock']<=$seuilStock) $styleStock="style='background:#FFFF00'";
		if($optResult['products_options_stock_stock']<=0) $styleStock="style='background:#FF0000'";
		if($optResult['products_options_stock_active']=='yes') {
			print "<td ".$styleStock." ".$va."  valign=top align=left>";
			print "<input type='text' class='vullen' size='3' name='stock[]' value='".$optResult['products_options_stock_stock']."'>";
		}
		else {
			print "<td>";
			print "<input type='hidden' name='stock[]' value='0'>";
			print "<div align='left' style='font-size:20px; color:#FF0000;'>--</div>";
		}
		print "</td>";
		
		$selStock = ($optResult['products_options_stock_active']=='yes')? 'checked' : '';
		print "<td align='center' ".$va."><input type='checkbox' name='active[".$ii."]' ".$selStock."></td>";
		
		print "</tr><tr height='35' bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
	}
	
	print "<td colspan='7' bgcolor='#FFFFFF'>";
	
	if(isset($totalStock) AND count($totalStock>0)) {
		print "<p align='center' style='font-size:12px;'><b>".TOTAL_STOCK.": ".array_sum($totalStock)."</b></p>";
	}
	print "</td>";
	print "</tr>";
	print "</table>";
	print "<input type='hidden' name='from' value='bdd'>";
	print "<input type='hidden' name='id' value='".$_POST['id']."'>";
	print "<input type='hidden' name='action' value='updateStock2'>";
	print "<p align='center'><input type='submit' value='".METTRE_A_JOUR."'class='knop'></p>";
	print "</form>";


	
	if(isset($_SESSION['tab_values'])) {
		combine_all($_SESSION['tab_values']);
		foreach($combinaison AS $item) {
			if(!in_array($item, $combinaisonBdd)) $missing[] = $item;
		}
		if(isset($missing) AND count($missing)>0) {
			print "<p align='center'>";
			print "<table border='0' align='center' cellpadding='3' cellspacing='0' class='TABLE' width='700'><tr bgcolor='#FFFFFF'>";
			print "<form method='POST' action='opties_voorraad.php'>";
			print "<td align='center'>";
			print "<div align='center'>".DECLINAISONS_AJOUTEES.":</div>";
			print "<div align='center'><img src='im/zzz.gif' width='1' height='8' border='0'></div>";
			foreach($missing AS $item) {
				print "<div align='center'>".$item."</div>";
				print "<input type='hidden' name=valuesToAdd[] value='".$item."'>";
			}
			print "<div align='center'><img src='im/zzz.gif' width='1' height='8' border='0'></div>";
			print "<input type='hidden' name='id' value='".$_POST['id']."'>";
			print "<input type='hidden' name='action' value='add'>";
			print "<input type='submit' class='knop' value='".DECLINAISONS_MANQUANTES."' style='border:#FF6600 3px solid; -moz-border-radius : 5px 5px 5px 5px;'>";
			print "</td>";
			print "</form>";
			print "</tr></table>";
			print "</p>";
		}
		
		$diff = array_diff($combinaisonBdd, $combinaison);
	
		if(isset($diff) AND count($diff)>0) {
			print "<p align='center'>";
			print "<table border='0' align='center' cellpadding='3' cellspacing='0' class='TABLE' width='700'><tr bgcolor='#FFFFFF'>";
			print "<form method='POST' action='opties_voorraad.php'>";
			print "<td align='center'>";
			print "<div align='center'>".DECLINAISONS_SUPPRIMEES.":</div>";
			print "<div align='center'><img src='im/zzz.gif' width='1' height='8' border='0'></div>";
			foreach($diff AS $item) {
				print "<div align='center'>".$item."</div>";
				print "<input type='hidden' name=valuesToDelete[] value='".$item."'>";
			}
			print "<div align='center'><img src='im/zzz.gif' width='1' height='8' border='0'></div>";
			print "<input type='hidden' name='id' value='".$_POST['id']."'>";
			print "<input type='hidden' name='action' value='delete'>";
			print "<input type='submit' value='".SUPPRIMER_DECLINAISONS."' class='knop'>";
			print "</td>";
			print "</form>";
			print "</tr></table>";
			print "</p>";
		}
	}
	
	

}
else {
	if(isset($_SESSION['tab_values'])) $tab_values = $_SESSION['tab_values'];
	combine_all($tab_values);
	$i=0;
	$c="";
 
	print "<form method='POST' action='opties_voorraad.php' enctype='multipart/form-data'>";
	print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE'><tr bgcolor='#FFFFFF'>";
	print "<td><b>#</b></td>";
	print "<td align='left'><b>".DECLINAISONS_ARTICLE."</b></td>";
	print "<td align='left'><b>".REFEENCES."</b></td>";
	print "<td align='left'><b>".REF_FOURNISSEUR."</b></td>";
	print "<td align='left'><b>".IMAGE."</b></td>";
	print "<td align='left'><b>".STOCK."</b></td>";
	print "<td align='left'><b>".ACTIVE."</b></td>";
	print "</tr><tr>";
	

	$theme = array("#C0C0C0", "#F1F1F1");
	$e = 0;
	foreach($combinaison AS $combi) {
		$i=$i+1;
		$ii = $i-1;
		if($i<sizeof($combinaison)) $explodeCombinaison = explode(" | ",$combinaison[$i]);
		$explodeCombi = explode(" | ",$combi);
		if($explodeCombi[0]!==$explodeCombinaison[0]) {
			$e = $e+1;
			if(($e%2) == 0) $o=1; else $o=0;
			$c = $theme[$o];
		}
		print "<td valign=top>".$i."&nbsp;-&nbsp;</td>";
		print "<td valign=top>".$combi."</td>";
		
		print "<input type='hidden' name='name[]' value='".$combi."'>";
		
		print "<td valign=top><input type='text' class='vullen' size='15' name='ref[]' value=''></td>";
		
		print "<td valign=top><input type='text' class='vullen' size='15' name='ean[]' value=''></td>";
		
		print "<td>";
?>	
	 
	<input type='file' name='uploadDeclinaisonImage[]' class='vullen' size='30'>
	 
	<div><img src='im/zzz.gif' width='1' height='1'></div>
	<input type="text" size="20" class='vullen' name="im[]" value="">
	 
<?php
		print "</td>";
		
		print "<td valign=top><input type='text' size='4' class='vullen' name='stock[]' value=''></td>";
		
		print "<td valign=top><input type='checkbox' name='active[".$ii."]' checked></td>";
		print "</tr><tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
	}
	print "</table>";
	print "<input type='hidden' name='from' value='new'>";
	print "<input type='hidden' name='action' value='updateStock'>";
	print "<input type='hidden' name='id' value='".$_POST['id']."'>";
	print "<p align='center'><input type='submit' value='".ENREGISTRER."' class='knop'></p>";
	print "</form>";
}
?>
<br><br><br>
</body>
</html>
