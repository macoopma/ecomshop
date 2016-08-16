<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
$toto = $_POST['val'];
/*
print "<pre>";
print_r($toto);
print "</pre>";
*/
                          
header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=voorraad.txt");

if(count($toto) > 0) {

  $fields = "Categorie\tRef\tArtikel\tGezien\tToegevoegd winkelmandje\tVerkocht\tPromo";
  echo $fields."\n";

 
  foreach ($toto AS $items) {
    $items = strip_tags($items);
    $items = str_replace("&#146;", "'", $items);
    $items = str_replace("\r\n", " - ", $items);
    $items = str_replace("\n", " - ", $items);
    $items = str_replace("\t", " ", $items);
    $items = str_replace("\r", " - ", $items);
    $items = str_replace(";", ",", $items);
   $toto1 = explode("|||",$items);
    foreach ($toto1 AS $elem) {
     echo $elem."\t";
   }
   echo "\n";
  }
}
else {
	echo "De artikelen werden succesvol ge-exporteerd";
}
?>
