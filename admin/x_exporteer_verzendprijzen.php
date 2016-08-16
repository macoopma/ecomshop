<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

$resQuery = mysql_query("SELECT id,
                          weight,
                          zone1,
                          zone2,
                          zone3,
                          zone4,
                          zone5,
                          zone6,
                          zone7,
                          zone8,
                          zone9,
                          zone10,
                          zone11,
                          zone12
                          FROM ship_price
                          WHERE livraison_id = '".$_GET['shipId']."'");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: attachment; filename=verzendprijzen.txt");

if(mysql_num_rows($resQuery) > 0) {
  // titels van de kolommen
  $fields = "Id\tGewicht\tZone1\tZone2\tZone3\tZone4\tZone5\tZone6\tZone7\tZone8\tZone9\tZone10\tZone11\tZone12";
  echo $fields."\n";

  // tabel opmaak
  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
   foreach($arrSelect as $elem) {
    echo $elem."\t";
   }
   echo "\n";
  }
}
else {
	echo "De export wers succesvol uitgevoerd";
}
?>
