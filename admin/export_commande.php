<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:index.php");

// Access Control
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
if($_SESSION['check']!=='none') {
   $addQueryCheck[] = " AND (users_nic = '".$_SESSION['check'][0]."'";
   $checkNb = count($_SESSION['check'])-1;
   for($i=1; $i<=$checkNb; $i++) {
      $addQueryCheck[] = " OR users_nic = '".$_SESSION['check'][$i]."'";
   }
   $addQueryCheck[] = ")";
   if(isset($addQueryCheck)) $addQueryCheck = implode("",$addQueryCheck);

// Function check livraison
function check_livraison($livId) {
  include('../configuratie/configuratie.php');
  $queryLiv = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$livId."'");
  $liv1 = mysql_fetch_array($queryLiv);
  ($livId==0)? $livNameS = "--" : $livNameS = $liv1['livraison_nom_'.$_SESSION['lang']];

  return $livNameS;
}

// moteur de recherche
if(isset($_GET['search']) AND $_GET['search']!=="none") {
   $addQuerySearch = " AND (users_nic = '".$_GET['search']."'";
   $addQuerySearch .= " OR users_password like '%".$_GET['search']."%'
                      OR users_id like '%".$_GET['search']."%'
                      OR users_city like '%".$_GET['search']."%'
                      OR users_country like '%".$_GET['search']."%'
                      OR users_email like '%".$_GET['search']."%'
                      OR users_telephone like '%".$_GET['search']."%'
                      OR users_save_data_from_form like '%".$_GET['search']."%'
                      OR users_lastname like '%".$_GET['search']."%'
                      OR users_firstname like '%".$_GET['search']."%'
                      OR users_company like '%".$_GET['search']."%'
                      OR users_payment like '%".$_GET['search']."%'
                      OR users_comment like '%".$_GET['search']."%'
                      ";
   $addQuerySearch .= ")";
}
else {
   $addQuerySearch = "";
}
if(isset($_GET['search']) AND $_GET['search']=="none") {
if(isset($_GET['date1'])) {$addQueryDate = " AND TO_DAYS(users_date_added) >= TO_DAYS('".$_GET['date1']."') AND TO_DAYS(users_date_added) <= TO_DAYS('".$_GET['date2']."')";} else {$addQueryDate="";}
if(!isset($_GET['yo']) or empty($_GET['yo'])) {$addQuery = "";}
if(isset($_GET['yo']) and $_GET['yo']=="all") {$addQuery = "";}
if(isset($_GET['yo']) and $_GET['yo']=="payed") {$addQuery = " AND users_payed = 'yes' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="nopayed") {$addQuery = " AND users_payed = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="shipped") {$addQuery = " AND users_statut = 'yes' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="noshipped") {$addQuery = " AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="set") {$addQuery = " AND users_payed = 'yes' AND users_statut = 'yes' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="eset") {$addQuery = " AND users_payed = 'yes' AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="noset") {$addQuery = " AND users_payed = 'no' AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="noconf") {$addQuery = " AND users_confirm = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="confnopayed") {$addQuery = " AND users_confirm = 'yes' AND users_payed = 'no' AND users_nic NOT LIKE 'TERUG%'";}
if(isset($_GET['yo']) and $_GET['yo']=="refund") {$addQuery = " AND users_nic LIKE 'TERUG%'";}
}
else {
$addQueryDate="";
$addQuery="";
}

$Orderquery = mysql_query("SELECT
                            users_date_added,
                            users_fact_num,
                            users_nic,
                            users_password,
                            users_ip,
                            
                            users_firstname,
                            users_lastname,
                            users_company,
                            users_address,
                            users_surburb,
                            users_zip,
                            users_city,
                            users_province,
                            users_country,
                            users_email,
                            users_telephone,
                            users_fax,
                            users_payment,
                            users_contre_remboursement,
                            users_symbol_devise,
                            
                            users_products,
                            
                            users_total_to_pay,
                            users_products_ht,
                            users_products_tax,
                            users_multiple_tax,
                            users_shipping_price,
                            users_ship_ht,
                            users_ship_tax,
                            users_shipping,

                            users_remise,
                            users_account_remise_app,
                            users_affiliate_amount,
                            users_remise_coupon,
                            users_remise_coupon_name,
                            users_deee_ht,
                            users_deee_tax,

                            users_facture_adresse,
                            
                            users_confirm,
                            users_payed,
                            users_statut,
                            
                            users_comment,
                            users_devis,
                            
                            users_litige,
                            users_refund
                            
                          FROM users_orders
                          WHERE 1
                          ".$addQuery."
                          ".$addQueryDate."
                          ".$addQueryCheck."
                          ".$addQuerySearch."
                          ORDER BY users_date_added
                          DESC
                          ");

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: attachment; filename=orders.txt");

unset($_SESSION['check']);

if(mysql_num_rows($Orderquery) !== 0) {
  // titre des colonnes
  $fields = "Date\tFacture No\tNIC\tNo Client\tIP\tNom\tPrenom\tEntreprise\tAdresse1\tAdresse2\tCode postal\tVille\tProvince\tPays\tEmail\tTelephone\tFax\tMode de paiement\tContre remboursement\tDevise\tCommande\tTotal commande TTC\tTotal Article HT\tTotal Articles Taxe\tTotal Details Taxes(%=Montant)\tTotal Livraison TTC\tTotal Livraison HT\tTotal Livraison Taxe\tMode de livraison\tRemise\tRemise sur points\tAffiliation\tRemise sur coupon\tNom coupon\tTotal DEEE HT\tTotal Taxe DEEE\tAdresse de facturation\tCommande confirmé\tCommande payé\tCommande expédiée\tNote client\tDevis\tLitige\tCommande remboursée\tTVA IntraCom\tItem ID\tItem quantity\tItem Price\tItem Reference\tItem Name\tItem Tax(%)\tItem Option\tItem DEEE";
  echo $fields."\n";

  // données de la table
  while ($arrSelect = mysql_fetch_array($Orderquery, MYSQL_ASSOC)) {
  
   if(!empty($arrSelect['users_facture_adresse'])) {
        $ex = explode("|", $arrSelect['users_facture_adresse']);
        if(isset($ex[7]) AND !empty($ex[7])) {$arrSelect['userstva'] = $ex[7];} else {$arrSelect['userstva'] = '';}
        $arrSelect['users_facture_adresse'] = str_replace("|","   |   ",$arrSelect['users_facture_adresse']);
    }
    $split = array("&#146;");
    $arrSelect = str_replace($split, "'", $arrSelect);
    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
    $arrSelect = str_replace("\n", " - ", $arrSelect);
    $arrSelect = str_replace("\t", " ", $arrSelect);
    $arrSelect = str_replace("\r", " - ", $arrSelect);
    $arrSelect = str_replace("&#8217;", "’", $arrSelect);
    
    $explodeProducts = explode(",", $arrSelect['users_products']);
    foreach($explodeProducts as $prodId) {
      $explodeProductsArray = explode("+", $prodId);
      $productsId[] = $explodeProductsArray[0];
      $productsQt[] = $explodeProductsArray[1];
      $productsPrice[] = $explodeProductsArray[2];
      $productsRef[] = $explodeProductsArray[3];
      $productsName[] = $explodeProductsArray[4];
      $productsTax[] = $explodeProductsArray[5];
      if(!empty($explodeProductsArray[6])) $productsOption[] = $explodeProductsArray[6]; else $productsOption[] = '--';
      $productsDeee[] = $explodeProductsArray[7];
    }
    
    // Montant Item Tax
    $arrSelect['users_multiple_tax'] = str_replace('>','%=',$arrSelect['users_multiple_tax']);
    // Fact number
    $arrSelect['users_fact_num'] = str_replace("||","",$arrSelect['users_fact_num']);
    // NIC
    $arrSelect['users_nic'] = str_replace("||","",$arrSelect['users_nic']);
    // Mode de livraison
    $arrSelect['users_shipping'] = check_livraison($arrSelect['users_shipping']);
    // Item Id
    if(count($productsId) > 0) {
      $productsIdFinal = implode('+',$productsId);
      $arrSelect['usersProductsId'] = $productsIdFinal;
      if(isset($productsId)) unset($productsId);
    }
    // Item quantity
    if(count($productsQt) > 0) {
      $productsQtFinal = implode('+',$productsQt);
      $arrSelect['usersProductsQt'] = $productsQtFinal;
      if(isset($productsQt)) unset($productsQt);
    }
    // Item price
    if(count($productsPrice) > 0) {
      $productsPriceFinal = implode('+',$productsPrice);
      $arrSelect['usersProductsPrice'] = $productsPriceFinal;
      if(isset($productsPrice)) unset($productsPrice);
    }
    // Item Ref
    if(count($productsRef) > 0) {
      $productsRefFinal = implode('+',$productsRef);
      $arrSelect['usersProductsRef'] = $productsRefFinal;
      if(isset($productsRef)) unset($productsRef);
    }
    // Item Name
    if(count($productsName) > 0) {
      $productsNameFinal = implode('+',$productsName);
      $arrSelect['usersProductsName'] = $productsNameFinal;
      if(isset($productsName)) unset($productsName);
    }
    // Item Tax
    if(count($productsTax) > 0) {
      $productsTaxFinal = implode('%+',$productsTax);
      $arrSelect['usersProductsTax'] = $productsTaxFinal."%";
      if(isset($productsTax)) unset($productsTax);
    }
    // Item Option
    if(count($productsOption) > 0) {
      $productsOptionFinal = implode('+',$productsOption);
      $arrSelect['usersProductsOption'] = $productsOptionFinal;
      if(isset($productsOption)) unset($productsOption);
    }
    // Item Deee
    if(count($productsDeee) > 0) {
      $productsDeeeFinal = implode('+',$productsDeee);
      $arrSelect['usersProductsDeee'] = $productsDeeeFinal;
      if(isset($productsDeee)) unset($productsDeee);
    }
   foreach($arrSelect as $elem) {
      echo $elem."\t";
   }
   echo "\n";
  }
}
else {
	echo "Aucune commande pour cette période";
}
}
else {
  print '<html>
         <head>
         <title></title>
         <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
         <link rel="stylesheet" href="style.css">
         </head>
         <body leftmargin="0" topmargin="50" marginwidth="0" marginheight="0">';
	print "<p align='center' class='fontrouge'><b>Aucune commande n'a été sélectionnée !</b></p>";
	print '</body></html>';
	exit;
}
?>
