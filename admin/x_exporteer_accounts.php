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

$resQuery = mysql_query("SELECT users_pro_id,
                          users_pro_password,
                          users_pro_email,
                          users_pro_gender,
                          users_pro_company,
                          users_pro_address,
                          users_pro_city,
                          users_pro_postcode,
                          users_pro_country,
                          users_pro_activity,
                          users_pro_telephone,
                          users_pro_date_added,
                          users_pro_fax,
                          users_pro_tva,
                          users_pro_tva_confirm,
                          users_pro_lastname,
                          users_pro_firstname,
                          users_pro_poste,
                          users_pro_comment,
                          users_pro_active,
                          users_pro_reduc,
                          users_pro_payable,
                          users_pro_news
                          FROM users_pro");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: attachment; filename=accounts.txt");

if(mysql_num_rows($resQuery) !== 0) {
  // titels van de kolommen
  $fields = "Id\tKlant nummer\tE-mail\tTitel\tBedrijf\tAdres\tWoonplaats\tPostcode\tLand\tActiviteit\tTelefoon\tToegevoegd op\tFax\tBTW nummer\tBTW nummer bevestigd\tNaam\tVoornaam\tPost\tCommentaar\tActief\tKlant korting(%)\tBetaalbaar\tNieuwsbrief\tBetaald";
  echo $fields."\n";

  // tabel opmaak
  while ($arrSelect = mysql_fetch_array($resQuery, MYSQL_ASSOC)) {

                  // Totaal
	               $commandeRequest = mysql_query("SELECT users_total_to_pay FROM users_orders WHERE users_password='".$arrSelect['users_pro_password']."' AND users_payed='yes' AND users_nic NOT LIKE 'TERUG%'");
                  unset($arrSelect['payedTTC']);
                  unset($totalOrder);
                  if(mysql_num_rows($commandeRequest)>0) {
                        while ($commandeTotalPrice = mysql_fetch_array($commandeRequest)) {
                           $totalOrder[] = $commandeTotalPrice['users_total_to_pay'];
                        }
                        $totalOrder2 = sprintf("%0.2f",array_sum($totalOrder));
                  }
                  else {
                     $totalOrder2 = sprintf("%0.2f",0);
                  }
      $arrSelect['payedTTC'] = $totalOrder2;


      if(!empty($arrSelect['users_pro_payable'])) {
         $arrSelect['users_pro_payable'] = $arrSelect['users_pro_payable']." dagen";
      }
      if($arrSelect['users_pro_payable']==0) {
         $arrSelect['users_pro_payable'] = "Contant";
      }
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
	echo "Alle accounts werden succesvol ge-exporteerd";
}
?>
