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

function check_livraison($livId) {
  include('../configuratie/configuratie.php');
  $queryLiv = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$livId."'");
  $liv1 = mysql_fetch_array($queryLiv);
  ($livId==0)? $livNameS = "--" : $livNameS = $liv1['livraison_nom_'.$_SESSION['lang']];

  return $livNameS;
}

$resQuery = mysql_query("SELECT users_nic,users_password,users_products_weight,users_gender,users_firstname,users_lastname,users_company,users_address,users_surburb,users_zip,users_city,users_province,users_country,users_shipping
                        FROM users_orders
                        WHERE users_nic='".$_GET['nic']."'
                        ");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=verzendadressen.txt");

if(mysql_num_rows($resQuery) !== 0) {
 
  $fields = "NIC\tKlant nummer\tGewicht (gr)\tM-Mevr\tVoornaam\tNaam\tBedrijf\tAdres 1\tAdres 2\tPostcode\tWoonplaats\tProvincie\tLand\tLevering";
  echo $fields."\n";

  
  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
   
    $arrSelect['users_shipping'] = check_livraison($arrSelect['users_shipping']);
    $split = array("&#146;");
    $arrSelect = str_replace($split, "'", $arrSelect);
    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
    $arrSelect = str_replace("\n", " - ", $arrSelect);
    $arrSelect = str_replace("\t", " ", $arrSelect);
    $arrSelect = str_replace("\r", " - ", $arrSelect);
    $arrSelect = str_replace("&#8217;", "’", $arrSelect);
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
