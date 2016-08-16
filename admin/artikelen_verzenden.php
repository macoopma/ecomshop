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


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
if(isset($_GET['de']) AND $_GET['de']==1) {
      $de = mysql_query("SELECT users_nic
                          FROM users_orders
                          WHERE users_payed = 'yes'
                          AND users_statut = 'no'
                          AND users_nic NOT LIKE 'TERUG%'
                          ORDER BY users_nic
                          DESC
                          ");
   if(mysql_num_rows($de) > 0) {
        while ($deNic = mysql_fetch_array($de)) {
            $_SESSION['check'][] = $deNic['users_nic'];
        }
   }
   else {
      $_SESSION['check'] = 'none';
   }
}
 
if(isset($_SESSION['check']) AND $_SESSION['check']!=='none') {
   $addQueryCheck[] = " AND (users_nic = '".$_SESSION['check'][0]."'";
   $checkNb = count($_SESSION['check'])-1;
   for($i=1; $i<=$checkNb; $i++) {
      $addQueryCheck[] = " OR users_nic = '".$_SESSION['check'][$i]."'";
   }
   $addQueryCheck[] = ")";
   if(isset($addQueryCheck)) $addQueryCheck = implode("",$addQueryCheck);
}
else {
  print '<html>';
  print '<head>';
  print '<title></title>';
  print '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
  print '<link rel="stylesheet" href="style.css">';
  print '</head>';
  print '<body leftmargin="0" topmargin="50" marginwidth="0" marginheight="0">';
  print '<p align="center" class="fontrouge"><b>Er werd niets gevonden</b></p>';
  print '</body></html>';
  exit;
}

 
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

	if(isset($_GET['yo']) and $_GET['yo']=="payedNotRefunded") {$addQuery = " AND users_payed = 'yes' AND users_refund = 'no' AND users_nic NOT LIKE 'TERUG%'";}
	if(isset($_GET['yo']) and $_GET['yo']=="litige") {$addQuery = " AND users_litige = 'yes'";}
	if(isset($_GET['yo']) and $_GET['yo']=="delete") {$addQuery = " AND users_customer_delete = 'yes'";}
	if(isset($_GET['yo']) and $_GET['yo']=="toDelete") {$addQuery = " AND users_payed='no' AND TO_DAYS(NOW())- TO_DAYS(users_date_added) > ".$pendingOrder;}
	if(isset($_GET['yo']) and $_GET['yo']=="shippednotpayed") {$addQuery = " AND users_payed='no' AND users_statut='yes' AND users_refund='no' AND users_nic NOT LIKE 'TERUG%' AND users_nic NOT LIKE 'REFUNDED'";}
	if(isset($_GET['yo']) and $_GET['yo']=="prep") {$addQuery = " AND users_ready = 'no' AND users_nic NOT LIKE 'TERUG%'";}
	if(isset($_GET['yo']) and $_GET['yo']=="payedToPrep") {$addQuery = " AND users_ready = 'no' AND users_payed = 'yes' AND users_nic NOT LIKE 'TERUG%'";}
	if(isset($_GET['yo']) and $_GET['yo']=="payedToPrepNotShipped") {$addQuery = " AND users_ready = 'yes' AND users_payed = 'yes' AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'";}
}
else {
	$addQueryDate="";
	$addQuery="";
}
 
function check_Virtual($ref) {
  include('../configuratie/configuratie.php');
  $query = mysql_query("SELECT products_download FROM products WHERE products_ref = '".$ref."'");
  $w1 = mysql_fetch_array($query);
  $down = $w1['products_download'];
  return $down;
}
 
function check_livraison($livId) {
  include('../configuratie/configuratie.php');
  $queryLiv = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$livId."'");
  $liv1 = mysql_fetch_array($queryLiv);
  ($livId==0)? $livNameS = "--" : $livNameS = $liv1['livraison_nom_'.$_SESSION['lang']];

  return $livNameS;
}
 

$Orderquery = mysql_query("SELECT
                            users_nic,
                            users_password,
                            users_products,
                            users_confirm,
                            users_payed,
                            users_statut,
                            users_fact_num,
                            users_shipping,
                            users_litige,
                            users_date_payed,
                            users_password,
                            users_facture_adresse,
                            users_ready
                            
                          FROM users_orders
                          WHERE 1
                          ".$addQuery."
                          ".$addQueryDate."
                          ".$addQueryCheck."
                          ".$addQuerySearch."
                          AND users_nic NOT LIKE 'TERUG%'
                          AND users_refund = 'no'
                          ORDER BY users_nic
                          DESC
                          ") or die (mysql_error());
unset($_SESSION['check']);

if(mysql_num_rows($Orderquery) > 0) {
        while ($arrSelect = mysql_fetch_array($Orderquery, MYSQL_ASSOC)) {

            $arrProduct = explode(",",$arrSelect['users_products']);
            $arrProductNum = count($arrProduct);
            
            if($arrProductNum > 1) {
                for($i=0; $i<=$arrProductNum-1; $i++) {
                    $arr[] = $arrSelect['users_nic'].'+'.$arrSelect['users_confirm'].'+'.$arrSelect['users_payed'].'+'.$arrSelect['users_statut'].'+'.str_replace("||","",$arrSelect['users_fact_num']).'+'.$arrSelect['users_shipping'].'+'.$arrSelect['users_litige'].'+'.$arrSelect['users_date_payed'].'+'.$arrSelect['users_password'].'+'.$arrSelect['users_facture_adresse'].'+'.$arrSelect['users_ready'].'+'.$arrProduct[$i];
                }
            }
            else {
                    $arr[] = $arrSelect['users_nic'].'+'.$arrSelect['users_confirm'].'+'.$arrSelect['users_payed'].'+'.$arrSelect['users_statut'].'+'.str_replace("||","",$arrSelect['users_fact_num']).'+'.$arrSelect['users_shipping'].'+'.$arrSelect['users_litige'].'+'.$arrSelect['users_date_payed'].'+'.$arrSelect['users_password'].'+'.$arrSelect['users_facture_adresse'].'+'.$arrSelect['users_ready'].'+'.$arrProduct[0];
            }
        }

  
               $arrNb = count($arr)-1;
                for($i=0; $i<=$arrNb; $i++) {
                    $arrProd[] = $arr[$i];
                }
   
               $arrProdNb = count($arrProd)-1;
                for($i=0; $i<=$arrProdNb; $i++) {
                    $arrExplode = explode("+",$arr[$i]);
    
                    $checkProd = check_Virtual($arrExplode[14]);
                    $livName = check_livraison($arrExplode[5]);
                    $shipAdress = str_replace("||",", ",$arrExplode[9]);
                    $shipAdress = str_replace("|",", ",$shipAdress);
                    if($checkProd == "no" AND $arrExplode[14] !== "GC100") {
                        $arrUsers[] = $arrExplode[0]; // nic
                        $arrUsers[] = strtoupper($arrExplode[14]);  
                        $arrUsers[] = $arrExplode[12]; 
                        $arrUsers[] = $arrExplode[15]; 
                        $arrUsers[] = $arrExplode[17]; 
                        $arrUsers[] = $arrExplode[2];  
                        $arrUsers[] = $arrExplode[3];  
                        if(isset($_GET['where']) AND $_GET['where']== "export") {
                            $arrUsers[] = $arrExplode[4]; 
                        }
                        else {
                            $arrUsers[] = "<a href='factuur_pdf.php?id=".$arrExplode[0]."' target='_blank'>".$arrExplode[4]."</a>";
                        }
                        $arrUsers[] = $livName; 
                        $arrUsers[] = $arrExplode[6];  
                        ($arrExplode[7] == "0000-00-00 00:00:00")? $arrUsers[] = "--" : $arrUsers[] = dateFr($arrExplode[7],$_SESSION['lang']); 
                        $arrUsers[] = $arrExplode[8];  
                        $arrUsers[] = $shipAdress;  
                        $arrUsers[] = $arrExplode[10];  
                        $arrUsers[] = "//";  
                        $arrUsersOption[$i] = $arrExplode[17];
                        $arrUsersReady[$i] = $arrExplode[10];
                    }
                }
}
else {

print "<html>";
print "<head>";
print "<title></title>";
print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
print "<link rel='stylesheet' href='style.css'>";
print "</head>";
print "<body leftmargin='0' topmargin='50' marginwidth='0' marginheight='0'>";

print "<p align='center' class='fontrouge'><b>Er werd niets gevonden</b></p>";
print "</body></html>";
	exit;
}
 
if($_GET['where']== "export") {

header("Content-Type: application/csv-tab-delimited-table");
header("Content-disposition:attachment; filename=artikelen.txt");

if(mysql_num_rows($Orderquery) != 0 AND isset($arrUsers)) {
  
  $fields = "NIC\tRef. article\tQt\tArticle\tOptions\tArticle payé\tArticle expédié\tFacture\tLivraison\tLitige\tPayé le\tNo.client\tAdresse de livraison\tArticle prêt à expédier";
  echo $fields."\n";
    $split = array("&#146;");
    $arrUsers = str_replace($split, "'", $arrUsers);
    $arrSelect = str_replace("\r\n", " - ", $arrSelect);
    $arrSelect = str_replace("\n", " - ", $arrSelect);
    $arrSelect = str_replace("\t", " ", $arrSelect);
    $arrSelect = str_replace("\r", " - ", $arrSelect);
 
   foreach($arrUsers as $elem) {
    if($elem=="//") echo "\n"; else echo $elem."\t";
   }
   echo "\n";
  }
  else {
    print "no";
  }
}
else {

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="50" marginwidth="0" marginheight="0">

<?php
print "<p align='center' class='title'><b>Artikelen om te verzenden</b></p>";
$c="#F1F1F1";
if(mysql_num_rows($Orderquery) > 0) {

    print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'>";
    print "<tr height='35' class='boxtitle2' align='center'>";
    print "<td valign='top'><b>NIC</b></td>";
    print "<td valign='top'><b>Ref</b></td>";
    print "<td valign='top'><b>Aantal</b></td>";
    print "<td valign='top'><b>Artikel</b></td>";
    print "<td valign='top'><b>Opties</b></td>";
    print "<td valign='top'><b>Betaald</b></td>";
    print "<td valign='top'><b>Verzonden</b></td>";
    print "<td valign='top'><b>Factuur</b></td>";
    print "<td valign='top'><b>Levering</b></td>";
    print "<td valign='top'><b>Klacht</b></td>";
    print "<td valign='top'><b>Betaald op</b></td>";
    print "<td valign='top'><b>Klant</b></td>";
    print "<td valign='top'><b>Adres</b></td>";
    print "<td valign='top'><b>Klaar</b></td>";
    print "</tr><tr>";
 

   if(isset($arrUsers)) {
   $arrUsersNb = count($arrUsers)-1;
     for($i=0; $i<=$arrUsersNb; $i++) {
          if(empty($arrUsers[$i])) {$arrUsers[$i] = "--";}
          if(in_array($arrUsers[$i],$arrUsersOption)) {$arrUsers[$i] = str_replace("|","<br><img src='im/zzz.gif' width='1' height='4'><br>",$arrUsers[$i]);} else {$arrUsers[$i] = $arrUsers[$i];}
		  if(isset($arrUsers[$i+1]) AND $arrUsers[$i+1]=="//") {
		  		$arrUsers[$i] = str_replace("yes","<img src='im/noPassed.gif' title='OUI'>",$arrUsers[$i]);
		  }
		  if($arrUsers[$i]=="//") {
          if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
              print "</tr><tr bgcolor='".$c."'>";
          }
          else {
              print "<td align='center' valign='top'>";
              print $arrUsers[$i];
              print "</td>";
          }
     }
   }
   else {
      print "<p align='center' class='fontrouge'><b>Geen artikelen om te verzenden</b></p>";
   }
    print "</tr></table>";
}
else {
    print "<p align='center' class='fontrouge'><b>Er werd niets gevonden</b></p>";
}
?>

</body>
</html>
<?php
}
?>
