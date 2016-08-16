<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
  
$resQuery = mysql_query("SELECT newsletter_id,
                          newsletter_email,
                          newsletter_password,
                          newsletter_langue,
                          newsletter_active,
                          newsletter_statut,
                          newsletter_date_added,
                          newsletter_nic
                          FROM newsletter
                          ORDER BY newsletter_id
                          ASC");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=export-email_adressen.txt");

if(mysql_num_rows($resQuery) > 0) {

  $fields = "Id\tE-mail\tWachtwoord\tTaal\tActief\tStatus\tToegevoegd\tKlant";
  echo $fields."\n";

 
  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {
    $split = array("&#146;");
    $arrSelect = str_replace($split, "'", $arrSelect);
    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
    $arrSelect = str_replace("\n", " - ", $arrSelect);   
    $arrSelect = str_replace("\t", " ", $arrSelect);
    $arrSelect = str_replace("\r", " - ", $arrSelect);
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
