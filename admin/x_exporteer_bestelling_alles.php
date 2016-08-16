<?php
include('../configuratie/configuratie.php');
$dir="../";
include('../includes/plug.php');

if(!isset($_SESSION['login'])) header("Location:index.php");
$i=0;
if(isset($affiche)) unset($affiche);
if(isset($afficheZ)) unset($afficheZ);
if(isset($afficheX)) unset($afficheX);
if(isset($totalHTArticle)) unset($totalHTArticle);
if(isset($totalTVA)) unset($totalTVA);
if(isset($taxeZ)) unset($taxeZ);
if(isset($taxZ)) unset($taxZ);
if(isset($TotalEcoTaxHT)) unset($TotalEcoTaxHT);
if(isset($TotalTVAEcoTax)) unset($TotalTVAEcoTax);
if(isset($TotalPortHT)) unset($TotalPortHT);
if(isset($TotalTVAPort)) unset($TotalTVAPort);
if(isset($TotalEmballageHT)) unset($TotalEmballageHT);
if(isset($TotalTVAEmballage)) unset($TotalTVAEmballage);
if(isset($TotalRemise)) unset($TotalRemise);
if(isset($TotalRemisePdf)) unset($TotalRemisePdf);
if(isset($TotalRemiseCode)) unset($TotalRemiseCode);


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


function check_livraison($livId) {
	include('../configuratie/configuratie.php');
	$queryLiv = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$livId."'");
	$liv1 = mysql_fetch_array($queryLiv);
	($livId==0)? $livNameS = "--" : $livNameS = $liv1['livraison_nom_'.$_SESSION['lang']];
	return $livNameS;
}

 
if(isset($_GET['nic'])) $_SESSION['check'] = array($_GET['nic']);
if(isset($_GET['gg1']) AND isset($_GET['gg2']) AND !isset($_GET['nico'])) {
	$resQuery = mysql_query("SELECT users_nic FROM users_orders
                         	WHERE TO_DAYS(users_date_payed) >= TO_DAYS('".$_GET['gg1']."')
                         	AND TO_DAYS(users_date_payed) <= TO_DAYS('".$_GET['gg2']."')
                         	AND users_payed = 'yes'
                         	ORDER BY users_date_added");
	if(mysql_num_rows($resQuery) > 0) {
		while($arrSelectW = mysql_fetch_array($resQuery)) {
			$_SESSION['check'][] = $arrSelectW['users_nic'];
		}
	}
}
if(isset($_GET['nico']) AND $_GET['nico']!=="") {
     $nico = explode("|",$_GET['nico']);
     foreach($nico AS $item) {
        $_SESSION['check'][] = $item;
     }
}

 
$addToQuery2 = "";
if(isset($_SESSION['check']) AND sizeof($_SESSION['check']) > 0) {
	$addToQuery2.=" users_nic IN(";
	foreach($_SESSION['check'] AS $elem) {
		$addToQuery2.= "'".$elem."',";
	}
	$addToQuery2.= ") ";
}
else {
	$addToQuery2= 1;
}
$addToQuery2 = str_replace(",)",")",$addToQuery2);




$Orderquery = mysql_query("SELECT * FROM users_orders WHERE ".$addToQuery2);
 

if(mysql_num_rows($Orderquery) > 0) {
   
  while($arrSelect = mysql_fetch_array($Orderquery, MYSQL_ASSOC)) {
	
	 
	$pdf = $arrSelect['users_account_remise_app'];
	 
	$usersProductsHT = $arrSelect['users_products_ht'];
     
    $affiche['dateFacture'] = ($arrSelect['users_date_payed']=="0000-00-00 00:00:00")? "--" : dateFr($arrSelect['users_date_payed'], $_SESSION['lang']);
     
    $affiche['dateCommande'] = dateFr($arrSelect['users_date_added'], $_SESSION['lang']);
     
    $affiche['NoFacture'] = str_replace("||","",$arrSelect['users_fact_num']);
     
    $affiche['NoClient'] = $arrSelect['users_password'];
     
    $removeCar = array("M ", "Mme ");
    $explode_users_facture_adresse = explode("|", $arrSelect['users_facture_adresse']);
    $affiche['NomClient'] = str_replace($removeCar,"",$explode_users_facture_adresse[0]);
     
    $affiche['societe']  = $explode_users_facture_adresse[1];
     
    $affiche['typePaiement'] = $arrSelect['users_payment'];
     
    $montantRemise = $arrSelect['users_remise'];
     
    $montantCodeReduction = $arrSelect['users_remise_coupon'];
     
    $gc = $arrSelect['users_gc'];
     
    $affiche['nic'] = $arrSelect['users_nic'];
     
    $affiche['email'] = $arrSelect['users_email'];
	 
    $tro = explode("|",$arrSelect['users_facture_adresse']);
	$affiche['intracom'] = ($tro[7]=="")? "" : $tro[7];
	 
	$requestCountryZ = mysql_query("SELECT iso FROM countries WHERE countries_name = '".$arrSelect['users_country']."'");
	$countriesZ = mysql_fetch_array($requestCountryZ);
	$affiche['iso'] = $countriesZ['iso'];
	 
    $requestShipMode = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$arrSelect['users_shipping']."'") or die (mysql_error());
    if(mysql_num_rows($requestShipMode) > 0) {
        $resultShipMode = mysql_fetch_array($requestShipMode);
    	$affiche['users_shipping'] = $resultShipMode['livraison_nom_'.$_SESSION['lang']];
    }
    else {
    	$affiche['users_shipping'] = "Download";
	}
	 
	$affiche['users_address'] = $arrSelect['users_address'];
	 
	$affiche['users_surburb'] = $arrSelect['users_surburb'];
	 
	$affiche['users_zip'] = $arrSelect['users_zip'];
	 
	$affiche['users_city'] = $arrSelect['users_city'];
	
	$affiche['users_province'] = $arrSelect['users_province'];
	 
	$affiche['users_country'] = $arrSelect['users_country'];
	 
	$affiche['users_telephone'] = $arrSelect['users_telephone'];
	 
	$affiche['users_contre_remboursement'] = $arrSelect['users_contre_remboursement'];
	 
	$affiche['users_fax'] = $arrSelect['users_fax'];
	 
	$affiche['users_payed'] = ($arrSelect['users_payed']=='yes')? "OUI" : "NON";
	 
	$affiche['users_ready'] = ($arrSelect['users_ready']=='yes')? "OUI" : "NON";
	 
	$affiche['users_statut'] = ($arrSelect['users_statut']=='yes')? "OUI" : "NON";
	
	
 
    $explodeProducts = explode(",", $arrSelect['users_products']);

    foreach($explodeProducts AS $prodId) {
    	$i++;
      	$explodeProductsArray = explode("+", $prodId);
  
		$affiche['ref'][$i-1] = $explodeProductsArray[3];
   
		$affiche['Qt'][$i-1] = $explodeProductsArray[1];
    
		$affiche['nomArticle'][$i-1] = $explodeProductsArray[4];
	 
		$productId[] = $explodeProductsArray[0];
	 
		$qt = $explodeProductsArray[1];
	 
		$productsTax = $explodeProductsArray[5];
	 
		$productPrice = $explodeProductsArray[2];
	 
		$productPriceQt = $qt*$explodeProductsArray[2];

		foreach($productId AS $item) {
			$productQuery = mysql_query("SELECT * FROM products WHERE products_id = '".$item."'");
			$productResult = mysql_fetch_array($productQuery);
	 
			$affiche['tauxTva'][$i-1] = $productsTax;
 
			if($arrSelect['users_products_tax_statut']=="TTC") {
				$affiche['montantHtArticle'][$i-1] = sprintf("%0.2f",$qt*($productPrice*100/($productsTax+100)));
			}
			else {
				$affiche['montantHtArticle'][$i-1] = sprintf("%0.2f",$productPriceQt);
			}
 
			$pourcentRemise = ($montantRemise > 0)? $montantRemise/$usersProductsHT : 0;
			$affiche['montantRemise'][$i-1] = sprintf("%0.2f",$productPriceQt*$pourcentRemise);
 
			$pourcentRemiseCodeReduction = ($montantCodeReduction > 0)? $montantCodeReduction/$usersProductsHT : 0;
			$affiche['montantRemiseCodeReduction'][$i-1] = sprintf("%0.2f",$productPriceQt*$pourcentRemiseCodeReduction);
 
			$montantPdf = ($pdf > 0)? $pdf/$usersProductsHT : 0;
			$affiche['montantPdf'][$i-1] = sprintf("%0.2f",$productPriceQt*$montantPdf);
 
			if($gc !== "") {
				$queryGcMain = mysql_query("SELECT users_gc FROM users_orders WHERE users_nic = '".$arrSelect['users_nic']."' AND users_payed='yes'");
				if(mysql_num_rows($queryGcMain) > 0) {
					$resultGcMain = mysql_fetch_array($queryGcMain);
					if($resultGcMain['users_gc']!== "") {
						$queryGc = mysql_query("SELECT gc_amount FROM gc WHERE gc_number = '".$gc."'");
						if(mysql_num_rows($queryGc) > 0) {
							$resultGc = mysql_fetch_array($queryGc);
							$gcAmount = $resultGc['gc_amount'];
						}
						else {
							$gcAmount = 0;
						}
					}
					else {
						$gcAmount = 0;
					}
				}
				else {
					$gcAmount = 0;
				}
			}
			else {
				$gcAmount = 0;
			}

			$montantGc = ($gcAmount !== 0)? $gcAmount/$usersProductsHT : 0;
			$affiche['montantGc'][$i-1] = sprintf("%0.2f",$productPriceQt*$montantGc);
			$gcAmountZ[$arrSelect['users_nic']] = -$gcAmount;

 
			if(isset($totalTaxPayedArray)) unset($totalTaxPayedArray);
			$explodeTva = explode("|", $arrSelect['users_multiple_tax']);
			for($u=0; $u<=count($explodeTva)-1; $u++) {
				$explodeAmountTax = explode(">",$explodeTva[$u]);
				$totalTaxPayedArray[$explodeAmountTax[0]] = $explodeAmountTax[1];
			}
			$iii = 0;
			foreach($totalTaxPayedArray AS $key => $value) {
				$iii = $iii+1;
				$affiche['montantTva'.$iii.'Article'][$i-1] = $value;
			}
			if(!isset($affiche['montantTva1Article'][$i-1])) $affiche['montantTva1Article'][$i-1] = 0;
			if(!isset($affiche['montantTva2Article'][$i-1])) $affiche['montantTva2Article'][$i-1] = 0;
			if(!isset($affiche['montantTva3Article'][$i-1])) $affiche['montantTva3Article'][$i-1] = 0;
			if(!isset($affiche['montantTva4Article'][$i-1])) $affiche['montantTva4Article'][$i-1] = 0;
 
			if($remiseOnTax=="TTC") {
				$affiche['tva'][$i-1] = sprintf("%0.2f",($affiche['montantHtArticle'][$i-1]*($affiche['tauxTva'][$i-1]/100)));
			}
			else {
				$affiche['tva'][$i-1] = sprintf("%0.2f",(($affiche['montantHtArticle'][$i-1]-$affiche['montantRemise'][$i-1]-$affiche['montantRemiseCodeReduction'][$i-1]-$affiche['montantPdf'][$i-1])*($affiche['tauxTva'][$i-1]/100)));
			}
			
 
			if($arrSelect['users_products_tax_statut']=="TTC") {
				$affiche['MontantEcoTaxHT'][$i-1] = sprintf("%0.2f",$qt*($explodeProductsArray[7]*100/($productsTax+100)));
			}
			else {
				$affiche['MontantEcoTaxHT'][$i-1] = sprintf("%0.2f",$qt*($explodeProductsArray[7]));
			}
 
			$affiche['MontantTVAEcoTax'][$i-1] = sprintf("%0.2f",$affiche['MontantEcoTaxHT'][$i-1]*($productsTax/100));
 
            $pourcentPort = $productPriceQt/$usersProductsHT;
			$affiche['TotalPortHT'][$i-1] = sprintf("%0.2f",$arrSelect['users_ship_ht']*$pourcentPort);
			$affiche['TotalTVAPort'][$i-1] = sprintf("%0.2f",$arrSelect['users_ship_tax']*$pourcentPort);
			$affiche['TotalEmballageHT'][$i-1] = sprintf("%0.2f",$arrSelect['users_sup_ht']*$pourcentPort);
			$affiche['TotalTVAEmballage'][$i-1] = sprintf("%0.2f",$arrSelect['users_sup_tax']*$pourcentPort);
		}
		
		$afficheZ[] = array("0"=>$affiche['dateCommande'],
							"1"=>$affiche['dateFacture'],
							"2"=>$affiche['NoFacture'],
							"3"=>$affiche['NoClient'],
							"4"=>$affiche['nic'],
							"5"=>$affiche['NomClient'],
							"6"=>$affiche['societe'],
							"7"=>$affiche['typePaiement'],
							"8"=>$affiche['ref'][$i-1],
							"9"=>$affiche['Qt'][$i-1],
							"10"=>$affiche['nomArticle'][$i-1],
							"11"=>$affiche['montantHtArticle'][$i-1],
							"12"=>$affiche['tauxTva'][$i-1],
							"13"=>$affiche['tva'][$i-1],
							"14"=>$affiche['montantTva1Article'][$i-1],
							"15"=>$affiche['montantTva2Article'][$i-1],
							"16"=>$affiche['montantTva3Article'][$i-1],
							"17"=>$affiche['montantTva4Article'][$i-1],
							"18"=>$affiche['MontantEcoTaxHT'][$i-1],
							"19"=>$affiche['MontantTVAEcoTax'][$i-1],
							"20"=>$affiche['TotalPortHT'][$i-1],
							"21"=>$affiche['TotalTVAPort'][$i-1],
							"22"=>$affiche['TotalEmballageHT'][$i-1],
							"23"=>$affiche['TotalTVAEmballage'][$i-1],
							"24"=>-$affiche['montantRemise'][$i-1],
							"25"=>-$affiche['montantPdf'][$i-1],
							"26"=>-$affiche['montantRemiseCodeReduction'][$i-1],
							"27"=>-$affiche['montantGc'][$i-1],
							"28"=>$affiche['users_contre_remboursement'],
							"29"=>$affiche['intracom'],
							"30"=>$affiche['email'],
							"31"=>$affiche['users_telephone'],
							"32"=>$affiche['users_fax'],
							"33"=>$affiche['users_address'],
							"34"=>$affiche['users_surburb'],
							"35"=>$affiche['users_zip'],
							"36"=>$affiche['users_city'],
							"37"=>$affiche['users_province'],
							"38"=>$affiche['users_country'],
							"39"=>$affiche['iso'],
							"40"=>$affiche['users_shipping'],
							"41"=>$affiche['users_payed'],
							"42"=>$affiche['users_ready'],
							"43"=>$affiche['users_statut'],
							);
		$taxeZ[$affiche['tauxTva'][$i-1]][] = $affiche['tva'][$i-1];
	}
  }




	$num = count($affiche['ref'])-1;
	for($c=0; $c<=$num; $c++) {
		for($b=0; $b<=count($affiche)-1; $b++) {
			switch($b) {
				case 14:
					$afficheX[] = "--";
				break;
				case 15:
					$afficheX[] = "--";
				break;
				case 16:
					$afficheX[] = "--";
				break;
				case 17:
					$afficheX[] = "--";
				break;
				case 27:
    				$queryGcMain2 = mysql_query("SELECT users_gc FROM users_orders WHERE users_nic = '".$afficheZ[$c][4]."' AND users_payed='yes'") or die (mysql_error());
    				$resultGcMain2 = mysql_fetch_array($queryGcMain2);
    				if($resultGcMain2['users_gc']=='') $afficheX[] = "--"; else $afficheX[] = $resultGcMain2['users_gc'];
				break;
				default:
				$afficheX[] = $afficheZ[$c][$b];
			}
		}
	}




 

	// Titels kolommen
	$fields = "Datum bestelling\tDatum factuur\tFactuur nummer\tKlant nummer\tNIC\tNaam\tBedrijf\tBetaaltype\tReferenties\tHoeveelheid\tNaam artikel\tBedrag\tBTW (%)\tBTW\tBedrag BTW1 artikel\tBedrag BTW 2 artikel\tBedrag BTW 3 artikel\tBedrag BTW 4 artikel\tBedrag Eco taks\tBTW Eco taks\tTotaal verzending\tTotaal BTW verzending\tTotaal verpakking\tTotaal BTW verpakking\tBedrag korting\tBonus punten\tKorting code\tGeschenkbon\tOnder rembours\tBTW nummer\tE-mail\tTelefoon\tFax\tAdres1\tAdres2\tPostcode\tWoonplaats\tProvincie\tLand\tISO\tLevering\tBestelling betaald\tBestelling klaar\tBestelling verstuurd";
  	// totalen
	  $fields2 = "\t\t\t\t\t\t\t\t\t\t\tTOTAAL ARTIKELEN\t\tTotaal BTW\tTotaal BTW 1 artikelen\tTotaal BTW 2 artikelen\tTotaal BTW 3 artikelen\tTotaal BTW 4 artikelen\tTotaal Eco taks\tTotaal BTW Eco taks\tTotal verzending\tTotaal BTW verzending\tTotaal verpakking\tTotaal BTW verpakking\tTotaal korting\tTotaal korting bonus punten\tTotaal korting code\tTotaal geschenkbon";

	$uuu=0;
	foreach($taxeZ AS $key => $value) {
		$uuu = $uuu+1;
		$taxZ[$key] = array_sum($value);
		$fields2 = str_replace("Totaal BTW".$uuu." Artikel","Totaal BTW".$uuu." Artikel (".$key."%)",$fields2);
		$fields = str_replace("Bedrag BTW".$uuu." Artikel","Montant BTW".$uuu." Artikel (".$key."%)",$fields);
		${'totalTVA'.$uuu} = array_sum($value);
	}
	if(!isset($totalTVA1)) $totalTVA1=0;
	if(!isset($totalTVA2)) $totalTVA2=0;
	if(!isset($totalTVA3)) $totalTVA3=0;
	if(!isset($totalTVA4)) $totalTVA4=0;
	
	
  	for($c=0; $c<=count($afficheZ)-1; $c++) {
  		$totalHTArticle[] = $afficheZ[$c][11];
  		$totalTVA[] = $afficheZ[$c][13];
  		$TotalEcoTaxHT[] = $afficheZ[$c][18];
  		$TotalTVAEcoTax[] = $afficheZ[$c][19];
  		$TotalPortHT[] = $afficheZ[$c][20];
  		$TotalTVAPort[] = $afficheZ[$c][21];
  		$TotalEmballageHT[] = $afficheZ[$c][22];
  		$TotalTVAEmballage[] = $afficheZ[$c][23];
  		$TotalRemise[] = $afficheZ[$c][24];
  		$TotalRemisePdf[] = $afficheZ[$c][25];
  		$TotalRemiseCode[] = $afficheZ[$c][26];
  	}

  	$totalPayed = array_sum($totalHTArticle)+array_sum($totalTVA)+array_sum($TotalPortHT)+array_sum($TotalTVAPort)+array_sum($TotalEmballageHT)+array_sum($TotalTVAEmballage)+array_sum($TotalRemise)+array_sum($TotalRemisePdf)+array_sum($TotalRemiseCode)+array_sum($gcAmountZ);
  	

 
header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition: attachment; filename=orders.txt");
 
	echo $fields."\n";
  	for($c=0; $c<=count($afficheX)-1; $c++) {
  		echo $afficheX[$c]."\t";
		if((($c+2) % 44)==1) echo "\n";
  	}
  
  	echo "\n";
  	echo "\n";
  	echo "\n";
  	echo $fields2."\n";
  	echo "\t\t\t\t\t\t\t\t\t\t\t".array_sum($totalHTArticle)."\t\t".array_sum($totalTVA)."\t".$totalTVA1."\t".$totalTVA2."\t".$totalTVA3."\t".$totalTVA4."\t".array_sum($TotalEcoTaxHT)."\t".array_sum($TotalTVAEcoTax)."\t".array_sum($TotalPortHT)."\t".array_sum($TotalTVAPort)."\t".array_sum($TotalEmballageHT)."\t".array_sum($TotalTVAEmballage)."\t".array_sum($TotalRemise)."\t".array_sum($TotalRemisePdf)."\t".array_sum($TotalRemiseCode)."\t".array_sum($gcAmountZ);
  	echo "\n";
  	echo "\n";
  
  	echo "\t\t\t\t\t\t\t\t\t\t\tPAIEMENT REÇU\n\t\t\t\t\t\t\t\t\t\t\t".$totalPayed;
  	echo "\n";

	if(isset($_SESSION['check'])) unset($_SESSION['check']);

 
}
else {
	echo "Geen bestelling";
	if(isset($_SESSION['check'])) unset($_SESSION['check']);
}
?>
