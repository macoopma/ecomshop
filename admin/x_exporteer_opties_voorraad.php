<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

$resQuery = mysql_query("SELECT o.products_options_stock_prod_id,
							p.products_name_".$_SESSION['lang'].",
							o.products_options_stock_prod_name,
							o.products_options_stock_ref,
							o.products_options_stock_ean,
							o.products_options_stock_im,
							o.products_options_stock_stock,
							o.products_options_stock_active
							FROM products_options_stock AS o
							INNER JOIN products AS p
							ON (p.products_id = o.products_options_stock_prod_id)
							WHERE products_options_stock_prod_id = '".$_GET['id']."'
							ORDER BY products_options_stock_prod_name
							ASC
						");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=voorraad.txt");

if(mysql_num_rows($resQuery) > 0) {


  $fields = "Articles ID\tArticle\tDéclinaisons\tReferences déclinaisons\tReferences fabricant déclinaisons\tImage\tStock\tActive";
  echo $fields."\n";

 
  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
  
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
	echo "Stock exporté.";
}
?>
