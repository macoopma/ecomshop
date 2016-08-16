<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

if($_SESSION['check']!=='none') {
   $addQueryCheck[] = " AND (users_nic = '".$_SESSION['check'][0]."'";
   $checkNb = count($_SESSION['check'])-1;
   for($i=1; $i<=$checkNb; $i++) {
      $addQueryCheck[] = " OR users_nic = '".$_SESSION['check'][$i]."'";
   }
   $addQueryCheck[] = ")";
   if(isset($addQueryCheck)) $addQueryCheck = implode("",$addQueryCheck);

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

$query = mysql_query("SELECT users_shipping,users_nic,users_gender,users_firstname,users_lastname,users_address,users_surburb,users_zip,users_city,users_province,users_country
                      FROM users_orders
                      WHERE 1
                      ".$addQuery."
                      ".$addQueryDate."
                      ".$addQueryCheck."
                      ".$addQuerySearch."
                      ORDER BY users_date_added
                      ASC
                      ");
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="50" marginwidth="0" marginheight="0">

<?php

$countQuery = mysql_num_rows($query);
if($countQuery > 0) {
    while ($row = mysql_fetch_array($query)) {
        if($row['users_shipping']!=='') {
            $requestLiv = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$row['users_shipping']."'");
            $resultLiv = mysql_fetch_array($requestLiv);
            $livraison = $resultLiv['livraison_nom_'.$_SESSION['lang']];
        }
        else {
            $livraison = "";
        }
        
        if($livraison!=="") print "<div align='center' style='color:#CCCCCC'>[".$livraison."]</div>";
        print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
        print "<table width='300' align='center' border='0' cellpadding='5' cellspacing='0'>";
        print "<tr><td class='large'>";
        print "<div align='left'>Id: ".str_replace("||","",$row['users_nic'])."</div>";
        print "<br>";
        print "<b>".$row['users_gender'].". ".$row['users_firstname']." ".$row['users_lastname']."</b>";
        print "<br>";
        print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['users_address']."<br>";
        print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['users_surburb']."<br>";
        if($row['users_province']!=="autre") {
            print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['users_province']."<br>";
        }
        print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>".$row['users_zip']."</b> ".$row['users_city']."<br>";
        print "<div align='right' class='large'>".strtoupper($row['users_country'])."</div>";
        print "</td></tr></table>";
        print "<hr style='height:1px; BACKGROUND-COLOR:#BBBBBB;'>";
    }
}
else {
    print "<br><br><p align='center' class='fontrouge'><b>Toutes les commandes ont été expédiées.<br>All orders has been shipped.</b></p>";
}
?>

</body>
</html>
<?php
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
