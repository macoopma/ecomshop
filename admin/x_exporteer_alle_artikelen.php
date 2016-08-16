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

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=formaat_csv.txt");


$resultZ = mysql_query( 'SHOW TABLES' );
if(!$resultZ) {
   echo "Error: niet mogelijk om de lijst in de database te vinden</p>";
   echo 'Error MySQL: ' . mysql_error();
   exit;
}
while ($rowZ = mysql_fetch_row($resultZ)) {
   $table[] = $rowZ[0];
}

// START LOOP
foreach($table AS $items) {
// QUERY
$resQuery = mysql_query("SELECT * FROM ".$items."");

if(mysql_num_rows($resQuery) > 0) {
  // titre des colonnes
   $result = mysql_query("SHOW COLUMNS FROM ".$items."");
   if(mysql_num_rows($result) > 0) {
      $fields = "";
      while ($row = mysql_fetch_assoc($result)) {
         $fields .= $row['Field']."\t";
      }
   }
echo "TABLE : ".strtoupper($items)."\n";
echo $fields."\n";


  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {

    $split = array("&#146;");
    $arrSelect = str_replace($split, "'", $arrSelect);
    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
    $arrSelect = str_replace("\n", " - ", $arrSelect);
    $arrSelect = str_replace("\t", " ", $arrSelect);
    $arrSelect = str_replace("\r", " - ", $arrSelect);
    $arrSelect = str_replace(";", ",", $arrSelect);
   foreach($arrSelect as $elem) echo $elem."\t";
   echo "\n";
  }
}
echo "\n\n\n";
}
// END LOOP
?>
