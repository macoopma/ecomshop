<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: attachment; filename=artikelen.txt");


$resQuery = mysql_query("SELECT * FROM products") or die (mysql_error());
 
$fieldsName = mysql_query("SHOW COLUMNS FROM products");
if (mysql_num_rows($fieldsName) > 0) {
   while ($row = mysql_fetch_assoc($fieldsName)) {
      $fieldsNameZ[] = $row['Field'];
   }
	$fields = implode("\t", $fieldsNameZ);
	echo $fields."\n";
}

if(mysql_num_rows($resQuery) > 0) {
 
	while($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
    	$arrSelect = str_replace("\r\n", "", $arrSelect);
		$arrSelect = str_replace("\n", "", $arrSelect);
		$arrSelect = str_replace("\t", " ", $arrSelect);
		$arrSelect = str_replace("\r", "", $arrSelect);
		foreach($arrSelect as $elem) {
    		echo $elem."\t";
		}
		echo "\n";
		echo "\n";
		echo "\n";
	}
	if(isset($fields)) {
		$note = "DÉLAIS D'EXPÉDITION\n";
		$note.= "Artikelen in voorraad: tussen {products_delay_1} en {products_delay_2} dag(en)\n";
		$note.= "Artikelen in bestelling: tussen {products_delay_1a} en {products_delay_2a} dag(en)\n";
		$note.= "Artiklen op bestelling: tussen {products_delay_1b} en {products_delay_2b} dag(en)\n";
		echo $note."\n";
	}
}
?>
