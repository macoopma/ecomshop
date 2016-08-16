<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

if(isset($_GET['act']) AND $_GET['act']=='toOrder') {
	$addToQuery = " AND p.products_qt <= '".$_GET['seuil']."' OR o.products_options_stock_stock <= '".$_GET['seuil']."' ";
	if($_GET['seuil']=="") $addToQuery = "";
}
else {
	$addToQuery = "";
}

$hids = mysql_query("SELECT p.products_id,
                          	p.products_name_".$_SESSION['lang'].",
                          	o.products_options_stock_prod_name,
							p.products_qt,
							o.products_options_stock_stock,
							p.products_forsale,
							o.products_options_stock_active,
							p.products_ref,
							o.products_options_stock_ref,
							p.products_price,
                          	f.fournisseurs_company,
                          	f.fournisseurs_firstname,
                          	f.fournisseurs_name,
                          	f.fournisseurs_address,
                          	f.fournisseurs_zip,
                          	f.fournisseurs_city,
                          	f.fournisseurs_pays,
                          	f.fournisseurs_tel1,
                          	f.fournisseurs_tel2,
                          	f.fournisseurs_cel1,
                          	f.fournisseurs_cel2,
                          	f.fournisseurs_fax,
                          	f.fournisseurs_email
						FROM products AS p
						LEFT JOIN fournisseurs AS f ON (p.fournisseurs_id = f.fournisseurs_id)
						LEFT JOIN products_options_stock AS o ON (o.products_options_stock_prod_id = p.products_id)
						WHERE p.products_ref != 'GC100'
						".$addToQuery."
						ORDER BY p.products_name_".$_SESSION['lang']."
						ASC
					") or die (mysql_error());
					
					
header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=exporteer_stock.txt");

if(mysql_num_rows($hids)>0) {

	$fields = "Id\tArticle\tOption_nom\tStock\t\tEn vente\t\tRef\t\tPrix\tFournisseur_compagnie\tFournisseur_Prenom\tFournisseur_Nom\tFournisseur_Adresse\tFournisseur_ZIP\tFournisseur_Ville\tFournisseur_Pays\tFournisseur_Tel1\tFournisseur_Tel2\tFournisseur_Cel1\tFournisseur_Cel2\tFournisseur_Fax\tFournisseur_Email";
	echo $fields."\n";

 
	while($arrSelect = mysql_fetch_array($hids, MYSQL_ASSOC)) {
 
		if(!empty($arrSelect['products_options_stock_stock']) OR $arrSelect['products_options_stock_stock']=="0") {
			$arrSelect['products_qt'] = $arrSelect['products_options_stock_stock'];
			$arrSelect['products_options_stock_stock'] = "";
		}
 
	    if($arrSelect['products_forsale'] == 'no') {
			$arrSelect['products_options_stock_active'] = 'no';
		}
	    if($arrSelect['products_options_stock_active'] == 'no') {
			$arrSelect['products_forsale'] = 'no';
		}
		$arrSelect['products_options_stock_active'] = "";
 
		if(!empty($arrSelect['products_options_stock_ref'])) {
			$arrSelect['products_ref'] = $arrSelect['products_options_stock_ref'];
			$arrSelect['products_options_stock_ref'] = "";
		}
	    $split = array("&#146;");
	    $arrSelect = str_replace($split, "'", $arrSelect);
	    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
	    $arrSelect = str_replace("\n", " - ", $arrSelect);
	    $arrSelect = str_replace("\t", " ", $arrSelect);
	    $arrSelect = str_replace("\r", " - ", $arrSelect);
	    $arrSelect = str_replace(";", ",", $arrSelect);
		foreach($arrSelect as $elem) {
			echo $elem."\t";
		}
		echo "\n";
	}
}
else {
	echo "De export werd succesvol uitgevoerd";
}
?>
