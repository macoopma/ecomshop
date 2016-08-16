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
function incLang($u) {
	$fichier = explode("/",$u);
	$what = end($fichier);
	return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
if(isset($_POST['id'])) $_GET['id'] = $_POST['id'];
if(isset($_POST['target'])) $_GET['target'] = $_POST['target'];

 
if($logo=="noPath") $logo="im/zzz.gif";

$dhoy = (date("d-m-Y H:i:s"));


 
function sortProducts() {
	GLOBAL $split;
	foreach($split AS $item) {
		$checkZZ = explode("+",$item);
		$queryZZ = mysql_query("SELECT categories_id FROM products WHERE products_id='".$checkZZ[0]."'");
		$rowZZ = mysql_fetch_array($queryZZ);
		##$grrrrr = (isset($splitZ[$rowZZ['categories_id']][$checkZZ[0]]))? $checkZZ[0]+10000 : $checkZZ[0];
		$splitZ[$rowZZ['categories_id']][] = $item."+".$rowZZ['categories_id'];
	}
	sort($splitZ);
	$splitZNum = count($splitZ)-1;
	for($i=0; $i<=$splitZNum; $i++) {
		foreach($splitZ[$i] AS $tyy) {
			$splitR[] = $tyy;
		}
	}
	return $splitR;
}

 
function rep_slash($rem) {
	$rem = stripslashes($rem);
	$rem = str_replace("&#146;","'",$rem);
	return $rem;
}

 
function tax_price($_pays) {
	$_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
	$_b = mysql_fetch_array($_a);
	$_tax = $_b['countries_product_tax'];
	$_tax2 = $_b['countries_shipping_tax'];
	$montant_taxe_prod = sprintf("%0.2f",$_tax);
	$montant_taxe_ship = sprintf("%0.2f",$_tax2);
	return array($montant_taxe_prod, $montant_taxe_ship);
}

 
function dateFr($fromDate,$langId) {
	$_qq = explode(" ",$fromDate);
	$_qq1 = explode("-",$_qq[0]);
	if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
	if($langId==2) $_qq3 = $_qq[0];
	$return = ereg_replace("([0-9]+)/([0-9]+)/([0-9]+)","\\3-\\2-\\1",$_qq3);
	return $return;
	//return $_qq3;
}

 
$query = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
$row = mysql_fetch_array($query);

 
$numFact = str_replace("||","",$row['users_fact_num']);
if($numFact=="") $numFact="XXXX";

 
if(empty($row['users_facture_adresse'])) {
	$row['users_province']=="autre"? $prov="": $prov=$row['users_province']."<br>";
    $surburb = (empty($row['users_surburb']))? "" : $row['users_surburb']."<br>";
    $company = (empty($row['users_company']))? "" : $row['users_company']."<br>";
	$adrees	= A50."<br>".$row['users_gender']." ".$row['users_lastname']." ".$row['users_firstname']."<br>"
				.$company
				.$row['users_address']."<br>"
				.$surburb
				.$row['users_zip']."<br>"
				.$row['users_city']."<br>"
				.$prov
				.$row['users_country'];
	$adreesName = $row['users_gender']." ".$row['users_firstname']." ".$row['users_lastname'];
}
else {
	$adress2 = explode("|",$row['users_facture_adresse']);
	$surburb = (empty($adress2[3]))? "" : $adress2[3]."<br>";
	$company2 = (empty($adress2[1]))? "" : $adress2[1]."<br>";
	$adrees = $adress2[0]."<br>".$company2."".$adress2[2]."<br>".$surburb."".$adress2[4]."<br>".$adress2[5]."<br>".$adress2[6];
	
	
	$adreesName = $adress2[0];
}

 
    $row['users_province']=="autre"? $prov="": $prov=$row['users_province']."<br>";
    $surburb = (empty($row['users_surburb']))? "" : $row['users_surburb']."<br>";
    $company3 = (empty($row['users_company']))? "" : $row['users_company']."<br>";
	$adreesShipping = $row['users_gender']." ".$row['users_lastname']." ".$row['users_firstname']."<br>"
				.$company3
				.$row['users_address']."<br>"
				.$surburb
				.$row['users_zip']."<br>"
				.$row['users_city']."<br>"
				.$prov
				.$row['users_country'];

$date = explode(" ", $row['users_date_added']);
if($row['users_payment'] == "BO") $payment = A1;
if($row['users_payment'] == "ch") $payment = A2;
if($row['users_payment'] == "cc") $payment = A3;
if($row['users_payment'] == "pp") $payment = "Paypal";
if($row['users_payment'] == "mb") $payment = "Moneybookers";
if($row['users_payment'] == "ma") $payment = A4;
if($row['users_payment'] == "BL") $payment = A4A;
if($row['users_payment'] == "wu") $payment = "Western Union";
if($row['users_payment'] == "tb") $payment = TRAITE_BANCAIRE;
if($row['users_payment'] == "ss") $payment = "Liaison-SSL";
if($row['users_payment'] == "eu") $payment = "1euro.com";
if($row['users_payment'] == "pn") $payment = "Pay.nl";
 

if(isset($_GET['target']) and $_GET['target'] == "impression") {
$n = 600;
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="10" topmargin="10" marginwidth="0" marginheight="0">

<table border="0" width="700" align="center" cellpadding="10" cellspacing="0"><tr><td>


<table width="<?php print $n;?>"  align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" colspan="2"><img src="../<?php print $logo;?>"></td>
</tr>
<tr>
<td colspan="2">
<br><br>
<?php if(substr($row['users_nic'], 0, 5) == "TERUG") $displayFactName = A5." D'".str_replace("||","",$row['users_nic']); else $displayFactName = A5;?>
<table border="0" width="100%" cellpadding="10" cellspacing="10" class="TABLE25"><tr><td class="large"><b><?php print $displayFactName;?></b></td></tr></table>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px">
<?php print "<b>".$store_name."</b><br>";
print $address_street."<br>".
$address_cp." - ".$address_city."<br>".
$address_country;
if(!empty($tel)) print "<br><br>".A6.": ".$tel;
if(!empty($fax)) print "<br>".FAX.": ".$fax;
print "<br>Web: <a href='' target='_blank'>http://".$www2.$domaineFull."</a><br>
       E-mail: <a href='mailto:".$mailOrder."'>".$mailOrder."</a><br>";
if(!empty($address_autre)) print "<br>".$address_autre;
?>
</td>

<td align="right"  style="padding: 5px">
<b><?php print A7;?></b>: <?php print "<span style='color:red'><b>".$numFact."</b></span>"; ?>
<br>
<b><?php print A8;?></b>: <?php print dateFr($row['users_date_payed'], $row['users_lang']);?>

<?php
if(substr($row['users_nic'], 0, 5) == "TERUG") {
   $_1 = str_replace("TERUG-","",str_replace("||","",$row['users_nic']));
   print "<br><br><b><span style='color:#CC0000'>".CORRECTION_FACTURE." #".$_1."</span></b>";
}
?>

<?php
if($row['users_refund']=='yes') {
    print "<br><br>";
    print "<p align='right'>";
    print "<table border='0' align='right' cellspacing='0' cellpadding='5' style='BACKGROUND-COLOR:#000000; border:1px #FFFFFF dotted;'><tr>";
    print "<td style='font-size:18px; color:#f1f1f1' align='center'>";
    print "<i>-- ".COMMANDE_REMBOURSEE." --</i>";
    print "</td>";
    print "</tr></table>";
    print "</p>";
}
?>
</td>

</tr>
<tr valign="top">
<td colspan="2">
<br>







<?php
if(isset($adress2[7]) AND !empty($adress2[7])) {
    $notvainter = $adress2[7];
}
else {
    $notvainter = "--";
}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"><tr>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9;?>:</b></td>
    <td>&nbsp;</td>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9A;?>:</b></td>
    </tr><tr>
    <td class="TABLE6" valign="top"><?php print $adrees;?></td>
    <td>&nbsp;</td>
    <td class="TABLE6" valign="top"><?php print $adreesShipping;?></td>
</tr></table>

<br>

<table border="0" width="100%" cellpadding="4" cellspacing="0"><tr>
    <td colspan="5" class="TABLE40"><b><div class="FontGris"><?php print A100;?></div></b></td></tr><tr>
    <td class="TABLE7" align="center"><div class="FontGris"><u><?php print A11;?></u><br><?php print $date[0];?></div></td>
    <td class="TABLE9" align="center"><div class="FontGris"><u>NIC</u><br><?php print "<b>".str_replace("||","",$row['users_nic'])."</b>";?></div></td>
    <td class="TABLE7" align="center"><div class="FontGris"><u><?php print A10;?></u><br><?php print "<b>".$row['users_password']."</b>";?></div></td>
    <td class="TABLE9" align="center"><div class="FontGris"><u><?php print A12;?></u><br><?php print $payment;?></div></td>
    <td class="TABLE7" align="center"><div class="FontGris"><u><?php print NO_TVA;?></u><br><?php print $notvainter;?></div></td>
</tr></table>










</td>
</tr>
<tr>
<td colspan="2">
<br><br><br>

<table border="0" width="100%" cellpadding="0" cellspacing="0" class="TABLE25"><tr><td class="large"><b><?php print A13;?></b></td></tr></table>

</td>
</tr>
</table>

<?php
print "<table width='".$n."' align='center' border='0' cellspacing='0' cellpadding='0'><tr><td>";

print "<table border='0' class='TABLE6' width='100%' align='center' cellspacing='0' cellpadding='2'><tr><td>";
 
print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='2'>";
print "<tr>";
print "<td class='TABLE9' align='center'><b>Ref/".A15."</b></td>";
print "<td class='TABLE9' width='50' align='center'><b>".QUT."</b></td>";
print "<td class='TABLE9' width='50' align='center'><b>".A45."</b></td>";
if($row['users_products_tax_statut']!=="") print "<td class='TABLE9' width='50' align='center'><b>".TVA."</b></td>";
print "<td class='TABLE9' width='80' align='right'><b>".A24."</b></td>";

$split = explode(",",$row['users_products']);

foreach ($split as $item) {
    $check = explode("+",$item);
    if($check[1]!=="0") {
		// Opties
        if(!empty($check[6])) {
            $_optZ = explode("|",$check[6]);
			## session update option price
			$lastArray = $_optZ[count($_optZ)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
			$_optZ = implode("|",$_optZ);
			$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
		}
		else {
			$q="";
		}
        print "</tr><tr>";

        print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4].$q."</td>";
 
        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
  
        print "<td width='50' align='center'>".$check[1]."</td>";
   
        $priceU = ($row['users_products_tax_statut']=="TTC")? $check[2]/(1+($check[5]/100)) : $check[2];
        print "<td width='50' align='center'>".sprintf("%0.2f",$priceU)."</td>";
    
        if($row['users_products_tax_statut']!=="") print "<td width='50' align='center'>".$check[5]."%</td>";
     
        $priceTTC = ($row['users_products_tax_statut']=="TTC")? ($check[2]*$check[1])/(1+($check[5]/100)) : ($check[2] * $check[1]);
        print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
	}
}
$yoyo = tax_price($row['users_country']);
print "</tr></table>";

print "<br>";

$la = 120;
  
$totalDiscount = abs($row['users_account_remise_app'] + $row['users_remise'] + $row['users_remise_coupon']);

print "<table border='0' style='BACKGROUND-COLOR: #FFFFCC; border: 1px #d8d8c4 solid; padding:5px' align='right' cellspacing='0' cellpadding='2'><tr>";
 
if(isset($totalDiscount) AND $totalDiscount>0) {
	$totalHtAvantRemise = sprintf("%0.2f",$row['users_products_ht']+$totalDiscount);
    print ($row['users_products_tax_statut']=="")? "<td align='right'>".A46."</td>" : "<td align='right'>".TOTAL_HT_AVANT_REMISE."</td>";    
    print "<td align='right' width='".$la."'>".$totalHtAvantRemise."</td>";
    
    
print "</tr><tr>";
if(abs($row['users_account_remise_app'])>0) {
    $sig = (substr($row['users_nic'], 0, 5) == "TERUG")? "" : "-";
	print "<td align='right'>".A51."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".sprintf("%0.2f",$sig.abs($row['users_account_remise_app']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_remise'])>0) {
    $sig1 = ($row['users_remise']<0)? "" : "-";
	print "<td align='right'>".A21."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".sprintf("%0.2f",$sig1.abs($row['users_remise']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_remise_coupon'])>0) {
    $sig2 = ($row['users_remise_coupon']<0)? "" : "-";
	print "<td align='right'>".A22."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".sprintf("%0.2f",$sig2.abs($row['users_remise_coupon']))."</span></td>";
	print "</tr><tr>";
}
    print ($row['users_products_tax_statut']=="")? "<td align='right'><br>".A46."</td>" : "<td align='right'><br>".TOTAL_HT."</td>";
    print "<td align='right' width='".$la."'><br>".$row['users_products_ht']."</td>";
}
else {
    print ($row['users_products_tax_statut']=="")? "<td align='right'>".A46."</td>" : "<td align='right'>".TOTAL_HT."</td>";
    print "<td align='right' width='".$la."'>".$row['users_products_ht']."</td>";
}


if($row['users_products_tax_statut']!=="") {
   print "</tr><tr>";
   print "<td align='right'>".$taxeName."</td>";
   print "<td align='right' width='".$la."'>";
 
    $explodMultiple = explode("|",$row['users_multiple_tax']);
    $explodMultipleNum = count($explodMultiple);
        foreach ($explodMultiple as $item) {
            if($item == "0.00>0.00") {
                $itemDisplay = "";
            }
            else {
                if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                $itemDisplay = str_replace(">", "%: ", $item).$br;
            }
            print $itemDisplay;
        }
    print "</td>";
}

 
print "</tr><tr>";
print "<td align='right'>".A19."</td>";
print "<td align='right' width='".$la."'>".$row['users_ship_ht']."</td>";
if($row['users_products_tax_statut']!=="") {
   print "</tr><tr>";
   print "<td align='right'>".$taxeName." ".$yoyo[1]."%</td>";
   print "<td align='right' width='".$la."'>".$row['users_ship_tax']."</td>";
}

 
if(abs($row['users_sup_ttc'])>0) {
	print "</tr><tr>";
	print "<td align='right'>".EMBALLAGE."</td>";
	print "<td align='right'>".$row['users_sup_ht']."</td>";
	if($row['users_products_tax_statut']!=="") {
		print "</tr><tr>";
		print "<td align='right'>".$taxeName." ".$taxe."%</td>";
		print "<td align='right'>".$row['users_sup_tax']."</td>";
	}
}
print "</tr><tr>";

if(abs($row['users_remise_gc'])>0) {
    $sig3 = ($row['users_remise_gc']<0)? "" : "-";
	print "<td align='right'>".A22C."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".$sig3.sprintf("%0.2f",abs($row['users_remise_gc']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_contre_remboursement'])>0) {
    $sig4 = ($row['users_contre_remboursement']<0)? "-" : "";
	print "<td align='right'>".A4A."</td>";
	print "<td align='right' width='".$la."'>".$sig4.sprintf("%0.2f",abs($row['users_contre_remboursement']))."</td>";
	print "</tr><tr>";
}

print "<td align='right' class='large'><b>".TOUSTAL."</b>:</td>";
print "<td align='right'class='large' width='".$la."'><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
print "</tr>";
print "</table>";
 
$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
if($ecoTaxFactFinal>0) {
    print "<div><i><span style='color:#00CC00'>".DONT." Eco-part: ".$ecoTaxFactFinal." ".$symbolDevise."</span></i></div>";
}
if($mlFact!=="") {
	print "<div><i><span style='color:#00CC00'>".$mlFact."</span></i></div>";
}
print "</td></tr>";
print "<tr>";
print "<td>";
print "</td></tr></table>";

print "</td>";
print "</tr>";
print "</table>";
?>
</td></tr></table>
</body>
</html>

<?php
}



/*---
Email
---*/

if(isset($_GET['target']) and $_GET['target']=="mail") {
$yoyo = tax_price($row['users_country']);
$adress2 = explode("|",$row['users_facture_adresse']);

$message = "<table width='500' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLEMenuPathCenter'><tr><td>";
$message.= "<table width='500' align='center' border='0' cellspacing='0' cellpadding='0'><tr>";
$message.= "<td align='left' colspan='2'>";
$message.= "<img src='http://".$www2.$domaineFull."/".$logo."'></td></tr><tr>";
$message.= "<td>";
$message.= "<div align='left'>";
$message.= "<b>".$store_name."</b><br>";
$message.= "URL: <a href='' target='_blank'>http://".$www2.$domaineFull."</a><br>";
$message.= "E-mail: <a href='mailto:".$mailOrder."'>".$mailOrder."</a><br>".$address_street."<br>".$address_cp." - ".$address_city."<br>".$address_country;
if(!empty($address_autre)) $message .= "<br>".$address_autre;
if(!empty($tel)) $message.="<br>".A6.": ".$tel;
if(!empty($fax)) $message.="<br>".FAX.": ".$fax;
$message.= "</div>
</td>";
if(substr($row['users_nic'], 0, 5) == "TERUG") $displayFactName = A5." D'".str_replace("||","",$row['users_nic']); else $displayFactName = A5;
$message.= "<td valign='top' align='right'><b><span class='large'>".$displayFactName."</span></b>";
$message.= "<br>".A7.": ".$numFact;
$message.= "<br>".A8.": ".dateFr($row['users_date_payed'], $row['users_lang']);
if(substr($row['users_nic'], 0, 5) == "TERUG") {
	$_1 = str_replace("TERUG-","",str_replace("||","",$row['users_nic']));
   	$message.= "<br><br><b><span style='color:#CC0000'>".CORRECTION_FACTURE." #".$_1."</span></b>";
}

if($row['users_refund']=='yes') {
    $message.= "<p align='right'>";
    $message.= "<table border='0' align='right' cellspacing='0' cellpadding='5' style='BACKGROUND-COLOR:#000000; border:1px #FFFFFF dotted;'><tr>";
    $message.= "<td style='font-size:15px; color:#f1f1f1' align='center'>";
    $message.= "<i>-- ".COMMANDE_REMBOURSEE." --</i>";
    $message.= "</td>";
    $message.= "</tr></table>";
    $message.= "</p>";
}

$message.= "</td>";
$message.= "</tr>";
$message.= "<td colspan='2'>";
$message.= "--------------------------------------------------------------------------------------------------";
$message.= "</td>";
$message.= "</tr>";
$message.= "<td align='left'>";
$message.= "<b>".A9."</b><br>".$adrees;
$message.= "</td>";
$message.= "<td  valign='top' align='right'>";
$message.= "<b>NIC</b>: <span class='fontrouge'>".str_replace("||","",$row['users_nic'])."</span><br>";
$message.= "<b>".A10."</b>: <span class='fontrouge'>".$row['users_password']."</span><br>";
$message.= "<b>".A11."</b>: ".$date[0];

if(isset($adress2[7]) AND !empty($adress2[7])) {
    $message .= "<br><b>".NO_TVA.": </b>".$adress2[7];
}
$message.= "</td>";
$message.= "</tr>";
$message.= "<td colspan='2'>";
$message.= "--------------------------------------------------------------------------------------------------";
$message.= "</td>";
$message.= "</tr>";
$message.= "</table>";
 
$message.= "<b>".A13."</b><br>";
$message.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td>";
$message.= "<table border='0' width='100%' align='center' cellspacing='1' cellpadding='1'>";
$message.= "<tr bgcolor='#F1F1F1'>";
$message.= "<td align='center'><b>Ref - ".A15."</b></td>";
$message.= "<td width='50' align='center'><b>".QUT."</b></td>";
$message.= "<td width='50' align='center'><b>".A45."</b></td>";
if($row['users_products_tax_statut']!=="") $message.= "<td width='50' align='center'><b>".TVA."</b></td>";
$message.= "<td width='80' align='right'><b>".A24."</b></td>";
$split = explode(",",$row['users_products']);
foreach ($split as $item) {
    $check = explode("+",$item);
    if($check[1]!=="0") {
		// Options
        if(!empty($check[6])) {
            $_optZ = explode("|",$check[6]);
			## session update option price
			$lastArray = $_optZ[count($_optZ)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
			$_optZ = implode("|",$_optZ);
			$q = "<br><i>".$_optZ."</i>";
		}
		else {
			$q="";
		}
        $message.= "</tr><tr>";
  
        $message.= "<td>- <b>".strtoupper($check[3])."</b><br>".$check[4].$q."</td>";
   
        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
    
        $message.= "<td width='50' align='center'>".$check[1]."</td>";
     
        $priceU = ($row['users_products_tax_statut']=="TTC")? $check[2]/(1+($check[5]/100)) : $check[2];
        $message.= "<td width='50' align='center'>".sprintf("%0.2f",$priceU)."</td>";
      
        if($row['users_products_tax_statut']!=="") $message.= "<td width='50' align='center'>".$check[5]."%</td>";
       
        $priceTTC = ($row['users_products_tax_statut']=="TTC")? ($check[2]*$check[1])/(1+($check[5]/100)) : ($check[2] * $check[1]);
        $message.= "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
    }
}
$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));

$message.="</tr></table><br>";

 
$totalDiscount = abs($row['users_account_remise_app'] + $row['users_remise'] + $row['users_remise_coupon']);

$message.="<table border='0' width='300' align='right' cellspacing='0' cellpadding='0'><tr>";
if(isset($totalDiscount) AND $totalDiscount>0) {
	$totalHtAvantRemise = sprintf("%0.2f",$row['users_products_ht']+$totalDiscount);
    $message.= ($row['users_products_tax_statut']=="")? "<td align='right'>".A46."</td>" : "<td align='right'>".TOTAL_HT_AVANT_REMISE."</td>";    
    $message.= "<td align='right'>".$totalHtAvantRemise."</td>";

    $message.= "</tr><tr>";
    $la=70;
    if(abs($row['users_account_remise_app']) > 0) {
        $sig = (substr($row['users_nic'], 0, 5) == "TERUG")? "" : "-";
    	$message.= "<td align='right'>".A51."</td>";
    	$message.= "<td align='right' width='".$la."'><span class='fontrouge'>".$sig.sprintf("%0.2f",abs($row['users_account_remise_app']))."</span></td>";
    	$message.= "</tr><tr>";
    }
    if(abs($row['users_remise'])>0) {
        $sig1 = ($row['users_remise']<0)? "" : "-";
    	$message.= "<td align='right'>".A21."</td>";
    	$message.= "<td align='right' width='".$la."'><span class='fontrouge'>".$sig1.sprintf("%0.2f",abs($row['users_remise']))."</span></td>";
    	$message.= "</tr><tr>";
    }
    if(abs($row['users_remise_coupon'])>0) {
        $sig2 = ($row['users_remise_coupon']<0)? "" : "-";
    	$message.= "<td align='right'>".A22."</td>";
    	$message.= "<td align='right' width='".$la."'><span class='fontrouge'>".$sig2.sprintf("%0.2f",abs($row['users_remise_coupon']))."</span></td>";
    	$message.= "</tr><tr>";
    }
    if($row['users_products_tax_statut']!=="") {
        $message.= ($row['users_products_tax_statut']=="")? "<td align='right'><br>".A46."</td>" : "<td align='right'><br>".TOTAL_HT."</td>";
        $message.= "<td align='right' width='".$la."'><br>".$row['users_products_ht']."</td>";
    }
}
else {
    $message.="<td align='right'>".A46."</td>";
    $message.="<td align='right'><b>".$row['users_products_ht']."</b></td>";
}

 
if($row['users_products_tax_statut']!=="") {
$message.="</tr><tr>";
$message.="<td align='right'>".$taxeName."</td>";
$message.="<td width='110' align='right'>";

  
                $explodMultiple = explode("|",$row['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                            $itemDisplay = "";
                        }
                        else {
                            if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                            $itemDisplay = str_replace(">", "%: ", $item).$br;
                        }
                        $message.= $itemDisplay;
                    }
                $message.= "</td>";

}
$message.="</tr><tr>";
$message.="<td align='right'>".A19."</td>";
$message.="<td align='right'>".$row['users_ship_ht']."</td>";
if($row['users_products_tax_statut']!=="") {
   $message.="</tr><tr>";
   $message.="<td align='right'>".$taxeName." ".$yoyo[0]."%</td>";
   $message.="<td align='right'>".$row['users_ship_tax']."</td>";
}

 
if(abs($row['users_sup_ttc']) > 0) {
                $message.= "</tr><tr>";
                $message.= "<td align='right'>".EMBALLAGE."</td>";
                $message.= "<td align='right'>".$row['users_sup_ht']."</td>";
                if($row['users_products_tax_statut']!=="") {
                  $message.= "</tr><tr>";
                  $message.= "<td align='right'>".$taxeName." ".$taxe."%</td>";
                  $message.= "<td align='right'>".$row['users_sup_tax']."</td>";
                }
}

$message.= "</tr><tr>";
$la=70;
if(abs($row['users_remise_gc'])>0) {
    $sig3 = ($row['users_remise_gc']<0)? "" : "-";
	$message.= "<td align='right'>".A22C."</td>";
	$message.= "<td align='right' width='".$la."'><span class='fontrouge'>".$sig3.abs($row['users_remise_gc'])."</span></td>";
	$message.= "</tr><tr>";
}
if(abs($row['users_contre_remboursement'])>0) {
    $sig4 = ($row['users_contre_remboursement']<0)? "-" : "";
	$message.="</tr><tr>";
	$message.="<td align='right'>".A4A."</td>";
	$message.="<td align='right'>".$sig4.sprintf("%0.2f",abs($row['users_contre_remboursement']))."</td>";
	$message.= "</tr><tr>";
}

$message.= "<td align='right' class='large'><br><b>TOTAL</b>:</td>";
$message.= "<td align='right' class='large'><br><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
$message.= "</tr>";
$message.= "</table>";
$message.= "</td></tr>";
$message.= "<tr>";
$message.= "<td>";
if($ecoTaxFactFinal>0) {
$message .= "<div><i><span style='color:#00CC00'>".DONT." Eco-part : ".$ecoTaxFactFinal." ".$symbolDevise."</span></i></div>";
}
if($mlFact!=="") {
$message .= "<div><i><span style='color:#00CC00'>".$mlFact."</span></i></div>";
}
$message .= "<div align='center'>--------------------------------------------------------------------------------------------------</div>";
$message .= "<div align='left'>$store_name ".A25."</div>";
$message .= "<div align='left'>".A26." <b>".$row['users_email']."</b> ".A27."</div>";
$message .= "</td></tr></table>";
$message .= "</td></tr></table>";

 
$message = "<html>
<head>
<title>".$store_name."</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<STYLE TYPE='text/css'>
<!--
BODY             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px; BACKGROUND COLOR: #F1F1F1}
FONT             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
.TABLEMenuPathCenter {BACKGROUND COLOR: #FFFFFF; border: 1px #000000 solid}
.fontrouge       {COLOR: #FF0000}
.large           {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 12px}
DIV              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
P                {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
TD               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
TR               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
A:link           {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
A:active         {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
A:visited        {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
A:hover          {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline }
-->
</STYLE>
</head>
<body leftmargin='10' topmargin='10' marginwidth='0' marginheight='0'>
".$message."
</body>
</html>";

print $message;

print "<p align='center'>";
print ">><a href='factuur_scherm.php?id=".$_GET['id']."&target=mail&action=send'><b>".A28."</b></a><<";
print "</p>";

if(isset($_GET['action']) and $_GET['action']=="send") {
$domMaj = strtoupper($domaineFull);

$to = $row['users_email'];
$subject = "[".A5." $numFact] - NIC#: ".$row['users_nic'];
$from = $mailOrder;
mail($to, $subject, rep_slash($message),
"Return-Path: $from\r\n"
."From: $from\r \n"
."Reply-To: $from\r \n"
."MIME-Version: 1.0\r \n"
."Content-Type: text/html; charset='iso-8859-1'\r \n"
."X-Mailer: PHP/" . phpversion());
print "<br><div align='center'><span class='fontrouge'><b>".A29."</b></span></div>";
}
}

 

if(isset($_GET['target']) AND $_GET['target']=="alert") {
		$stock = "";
		$hoy = dateFr(date("d-m-Y H:i:s"), $row['users_lang']);
		$queryUpdate = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
		$row = mysql_fetch_array($queryUpdate);
		$domMaj = strtoupper($domaineFull);
		$to = $row['users_email'];
		$subject = "Uw betaling werd ontvangen op $domMaj";
		$from = $mailOrder;


 
      $_store = str_replace("&#146;","'",$store_name);
      $scss = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
      if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $scss .= $address_autre2."\r\n";
      }
		if(!empty($tel)) $scss.= A6.": ".$tel."\r\n";
		if(!empty($fax)) $scss.= FAX.": ".$fax."\r\n";
		$scss .= "URL: http://".$www2.$domaineFull."\r\nE-mail: ".$mailOrder."\r\n\r\n";
		$scss .= "Datum: ".$dhoy."\r\n\r\n";
		$scss .= $adreesName.",\r\n";
		$scss .= A30." ".strtolower($payment)." ".A31."\r\n";
		$scss .= A32."\r\n";
		$scss .= A33."\r\n";
		$scss .= "--------------------------------------------------------------------------------------\r\n";
		$scss .= A35.": http://".$www2.$domaineFull."/klantlogin\r\n";
		$scss .= "NIC (".NICO."): ".$row['users_nic']."\r\n";
		$scss .= A10.": ".$row['users_password']."\r\n";
		$scss .= A_EMAIL.": ".$row['users_email']."\r\n";
		$scss .= A55."\r\n";
		$scss .= "--------------------------------------------------------------------------------------\r\n";
			
		if($affiliateAuto=='oui') {
			$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass, aff_com FROM affiliation WHERE aff_customer = '".$row['users_password']."'");
			if(mysql_num_rows($queryZAffFindNumber)>0) {
				$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
				$affiliateNumber = $resultZAffFindNumber['aff_number'];
				$affiliatePass = $resultZAffFindNumber['aff_pass'];
				$affiliateCom2 = $resultZAffFindNumber['aff_com'];
				$scss .= "\r\n";
				$scss .= $_store." ".VOUS_REMUNERE_A_HAUTEUR_DE." ".$affiliateCom2."% ".SUR_LES_VENTES_GENEREES."\r\n";
				$scss .= CETTE_OFFRE_NE_VOUS_ENGAGE_A_RIEN."\r\n";
				$scss .= IL_VOUS_SUFFIT_ENVOYER_EMAIL_A.":\r\n";
				$scss .= "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\r\n";
				$scss .= VOUS_POUVEZ_SUIVRE_A_TOUT_MOMENT." http://".$www2.$domaineFull."\r\n";
				$scss .= VOICI_CI_DESSOUS_LES_INFOS_COMPTE_AFF.":\r\n";
				$scss .= NUMERO_DE_COMPTE_AFF.": ".$affiliateNumber."\r\n";
				$scss .= MOT_DE_PASSE_AFF.": ".$affiliatePass."\r\n";
				$scss .= "\r\n";
			}
		}
		$scss .= A36."\r\n";
		$scss .= A38."\r\n\r\n";
		$scss .= A37."\r\n";
		$scss .= $mailOrder."\r\n";

  
      if($row['users_affiliate']!=='') {
         $queryAff = mysql_query("SELECT aff_nom, aff_prenom, aff_email, aff_number FROM affiliation WHERE aff_number = '".$row['users_affiliate']."'");
         if(mysql_num_rows($queryAff) > 0) {
           $rowAff = mysql_fetch_array($queryAff);
           $affActive = 'yes';
            $_storeAff = str_replace("&#146;","'",$store_name);
      		
            $toAff = $rowAff['aff_email'];
      		$subjectAff = "[".A3001."] - http://".$www2.$domaineFull;
      		$fromAff = $mailOrder;
      
      		$scssAff = $rowAff['aff_nom']." ".$rowAff['aff_prenom'].",\r\n\r\n";
      		$scssAff .= A3002." http://".$www2.$domaineFull.".\r\n";
      		$scssAff .= A3003." : ".$rowAff['aff_number']."\r\n";
      		$scssAff .= A3004." ".$row['users_products_ht']." ".$symbolDevise." HT.\r\n";
      		$scssAff .= A3005." : ".$row['users_affiliate_amount']." ".$symbolDevise.".\r\n";
      		$scssAff .= A3006." http://".$www2.$domaineFull."/affiliation.php.\r\n";
      		$scssAff .= "-----\r\n";
            $scssAff .= $_storeAff."\r\n";
            $scssAff .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder;
         }
      }

		print "<html>";
		print "<head>";
		print "<title></title>";
		print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
		print "<link rel='stylesheet' href='style.css'>";
		print "</head>";
		print "<body leftmargin='10' topmargin='10' marginwidth='0' marginheight='0'>";
		$scss = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$scss);
		$ff = str_replace("\r\n","<br>",$scss);
		if(isset($affActive) AND $affActive=='yes') {
         $ff .= "<br><br>&bull;&bull;&bull;<br>".A3008."<br>&bull;&bull;&bull;<br>".str_replace("\r\n","<br>",$scssAff);
         $sendAff = "&sendAff=yes";
      }
      else {
         $sendAff = "";
      }
			if($stock!=="ok") {
				print $ff;
 
				print "<p align='center'>";
				print ">><a href='factuur_scherm.php?id=".$_GET['id']."&target=alert&action=send".$sendAff."'><b>".A28."</b></a><<";
				print "</p>";
			}
		print "</body></html>";
		
			if(isset($_GET['action']) AND $_GET['action']=="send") {
				mail($to, $subject, rep_slash($scss),
				"Return-Path: $from\r\n"
                ."From: $from\r\n"
				."Reply-To: $from\r\n"
				."X-Mailer: PHP/" . phpversion());
            
            if(isset($_GET['sendAff']) AND $_GET['sendAff']=="yes") {
    				mail($toAff, $subjectAff, rep_slash($scssAff),
    				"Return-Path: $fromAff\r\n"
                    ."From: $fromAff\r\n"
    				."Reply-To: $fromAff\r\n"
    				."X-Mailer: PHP/" . phpversion());
			}
				
				print "<p align='center'><span class='fontrouge'><b>".A39."</b></span></p>";
				print "<p align='center'><a href='javascript:window.close()'><b>".A43."</b></a></p>";
			}
}
 

if(isset($_GET['target']) and $_GET['target']=="alertShipping") {
		$phase1 = "";
		$hoy = dateFr(date("d-m-Y H:i:s"), $row['users_lang']);
		$queryUpdate = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
		$row = mysql_fetch_array($queryUpdate);
		$domMaj = strtoupper($domaineFull);
		$to = $row['users_email'];
		$subject = "[".AVIS_EXPEDITION_COMMANDE."] ".$domMaj;
		$from = $mailOrder;

		print "<html>";
		print "<head>";
		print "<title></title>";
		print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
		print "<link rel='stylesheet' href='style.css'>";
		print "</head>";
		print "<body leftmargin='10' topmargin='10' marginwidth='10' marginheight='10'>";
		
			if(!isset($_POST['state']) and !isset($_GET['action'])) {
			print "<table border='0' align='center' width='98%' class='TABLE2'><tr><td>";
      		print "<form method='POST' action='factuur_scherm.php'>";
      		
          print $store_name."<br>".$address_street."<br>".$address_cp." - ".$address_city."<br>".$address_country."<br>";
                              if(!empty($address_autre)) {
                                  print $address_autre."<br>";
                              }
      		if(!empty($tel)) print A6.": ".$tel."<br>";
      		if(!empty($fax)) print FAX.": ".$fax."<br>";
      		print "URL: http://".$www2.$domaineFull."<br>E-mail: ".$mailOrder."<br><br>";
      		print "Datum: ".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$dhoy)."<br><br>";
      		print $adreesName.",<br>";
      		print A32A." ".$row['users_nic']."<br>";
      		print A33A."<br><br>";
      		print "<table border='0' cellspacing='0' cellpadding='3'><tr>";
      		print "<td valign='top'>".A34A."</td><td><textarea cols='40' rows='3' name='transporteur'></textarea></td></tr><tr>";
      		print "<td valign='top'>".A35A."</td><td><textarea cols='40' rows='3' name='colis'></textarea></td></tr><tr>";
      		print "<td valign='top'>".A36A."</td><td><textarea cols='40' rows='5' name='note'></textarea><br></td></tr>";
      		print "</table>";
      		print "---------------------------------------------<br>";
      		print A35.": http://".$www2.$domaineFull."/klantlogin<br>";
      		print "NIC: ".$row['users_nic']."<br>";
      		print A10.": ".$row['users_password']."<br>";
      		print A36." ".$mailOrder.".<br>";
      		print "---------------------------------------------<br><br>";
      		print A37."<br>";
      		
      		
      		print "<input type='hidden' name='target' value='alertShipping'>";
      		print "<input type='hidden' name='state' value='view'>";
      		print "<input type='hidden' name='id' value='".$_GET['id']."'>";
      		
      		print "<p align='center'>";
      		print "<input type='submit' value='>> Bekijken <<'>";
				  print "</p>";
      		print "</form>";
      		print "</td></tr></table>";
			
			}
			if(isset($_POST['state']) and $_POST['state'] == "view") {
            $_store = str_replace("&#146;","'",$store_name);
            $_store = str_replace("<br>","\r\n",$store_name);
            $scss = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
        		if(!empty($address_autre)) {
                $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                $scss .= $address_autre2."\r\n";
            }
        		if(!empty($tel)) $scss.= A6.": ".$tel."\r\n";
        		if(!empty($fax)) $scss.= FAX.": ".$fax."\r\n";
        		$scss .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        		$scss .= "Datum: ".$hoy."\r\n\r\n";
        		$scss .= $adreesName.",\r\n";
        		$scss .= A32A." ".$row['users_nic']." ".A32B."\r\n";
        		if((isset($_POST['transporteur']) OR isset($_POST['colis']) OR isset($_POST['note'])) AND (!empty($_POST['transporteur']) OR !empty($_POST['colis']) OR !empty($_POST['note']))) {
                    $scss .= A33A."\r\n\r\n";
            	}
        		if(isset($_POST['transporteur']) AND !empty($_POST['transporteur'])) $scss .= A34A." ".$_POST['transporteur']."\r\n";
        		if(isset($_POST['colis']) AND !empty($_POST['colis'])) $scss .= A35A." ".$_POST['colis']."\r\n";
        		if(isset($_POST['note']) AND !empty($_POST['note'])) $scss .= A36A." ".$_POST['note']."\r\n";
        		$scss .= "---------------------------------------------\r\n\r\n";
        		$scss .= A35.": http://".$www2.$domaineFull."/klantlogin\r\n";
        		$scss .= "NIC : ".$row['users_nic']."\r\n";
        		$scss .= A10.": ".$row['users_password']."\r\n";
        		$scss .= A36." ".$mailOrder.".\r\n";
        		$scss .= "---------------------------------------------\r\n\r\n";
        		$scss .= A38.",\r\n";
        		$scss .= $_store."\r\n";
        		
  
            $_SESSION['sendMessage'] = $scss;
   
        		$_SESSION['scssStore'] = A32A." ".$row['users_nic']." ".A32B;
        		if(isset($_POST['transporteur']) AND !empty($_POST['transporteur'])) $_SESSION['scssStore'] .= "\r\n".A34A." ".$_POST['transporteur'];
        		if(isset($_POST['colis']) AND !empty($_POST['colis'])) $_SESSION['scssStore'] .= "\r\n".A35A." ".$_POST['colis'];
        		if(isset($_POST['note']) AND !empty($_POST['note'])) $_SESSION['scssStore'] .= "\r\n".A36A." ".$_POST['note'];
        		$_SESSION['scssStore'] = str_replace("'","&#146;",$_SESSION['scssStore']);
        		
    
        		$ff = str_replace("\r\n","<br>",$scss);
        		print "<table border='0' align='center' width='98%' class='TABLE2'><tr><td>";
              print $ff;
            print "</td></tr></table>";
        		
     
      
       

				print "<p align='center'>";
				print ">><a href='factuur_scherm.php?id=".$_GET['id']."&target=alertShipping&action=send'><b>".A28."</b></a><<";
				print "</p>";
				print "<p align='center'>";
				print "<a href='javascript:history.back()'><b>".A300."</b></a>";
				print "</p>";
			}

		print "</body></html>";
		
			if(isset($_GET['action']) and $_GET['action']=="send") {
			    print "<table border='0' align='center' width='98%' class='TABLE2'><tr><td>";
			    $ff2 = str_replace("\r\n","<br>",$_SESSION['sendMessage']);
             print $ff2;
             print "</td></tr></table>";
	 
             mail($to, $subject, rep_slash($_SESSION['sendMessage']),
    				 "Return-Path: $from\r\n"
                     ."From: $from\r\n"
    				 ."Reply-To: $from\r\n"
    				 ."X-Mailer: PHP/" . phpversion());
				 
       
                    mysql_query("UPDATE users_orders SET users_statut = 'yes', users_ready = 'yes' WHERE users_nic = '".$row['users_nic']."'");
                    
                    $queryNote = mysql_query("SELECT users_note, users_share_note FROM users_orders WHERE users_nic='".$row['users_nic']."'");
                    $rowNote = mysql_fetch_array($queryNote);
                    $internNote = (empty($rowNote['users_note']) OR $rowNote['users_note']=='')? $hoy."\r\n".$_SESSION['scssStore']."\r\n---" : $hoy."\r\n".$_SESSION['scssStore']."\r\n---\r\n".$rowNote['users_note'];
                    $internNote2 = (empty($rowNote['users_share_note']) OR $rowNote['users_share_note']=='')? $hoy."\r\n".$_SESSION['scssStore']."\r\n---" : $hoy."\r\n".$_SESSION['scssStore']."\r\n---\r\n".$rowNote['users_share_note'];
        
                    mysql_query("UPDATE users_orders SET users_share_note = '".$internNote2."' WHERE users_nic = '".$row['users_nic']."'");
         
                    mysql_query("UPDATE users_orders SET users_note = '".$internNote."' WHERE users_nic = '".$row['users_nic']."'");
          
                    print "<p align='center'><span class='fontrouge'><b>".A39."</b></span></p>";
                    print "<p align='center'><a href='javascript:window.close()'><b>".A43."</b></a></p>";
           
                    if(isset($_SESSION['sendMessage'])) unset($_SESSION['sendMessage']);
                    if(isset($_SESSION['scssStore'])) unset($_SESSION['scssStore']);
			}
}
 

if(isset($_GET['target']) and $_GET['target']=="rappel") {
		$phase1 = "";
		$hoy = dateFr(date("d-m-Y H:i:s"), $row['users_lang']);
		$queryUpdate = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
		$row = mysql_fetch_array($queryUpdate);
		$domMaj = strtoupper($domaineFull);
		$to = $row['users_email'];
		$subject = "[".RAPPEL_PAIEMENT_COMMANDE."] ".$domMaj;
		$from = $mailOrder;

		print "<html>";
		print "<head>";
		print "<title></title>";
		print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
		print "<link rel='stylesheet' href='style.css'>";
		print "</head>";
		print "<body leftmargin='10' topmargin='10' marginwidth='10' marginheight='10'>";
		
			if(!isset($_POST['stateRappel']) and !isset($_GET['action'])) {
      		print "<form method='POST' action='factuur_scherm.php'>";
      		
            $_SESSION['sendMessageRappel'] = $store_name."<br>".$address_street."<br>".$address_cp." - ".$address_city."<br>".$address_country."<br>";
            if(!empty($address_autre)) $_SESSION['sendMessageRappel'].= $address_autre."<br>";
      		if(!empty($tel)) $_SESSION['sendMessageRappel'].= A6.": ".$tel."<br>";
      		if(!empty($fax)) $_SESSION['sendMessageRappel'].= FAX.": ".$fax."<br>";
      		$_SESSION['sendMessageRappel'].= "URL: http://".$www2.$domaineFull."<br>Email: ".$mailOrder."<br><br>";
      		$_SESSION['sendMessageRappel'].= $adreesName.",<br><br>";
      		$_SESSION['sendMessageRappel'].= LE_PAIEMENT_DE_VOTRE_COMMANDE." ".$row['users_nic']." ".DU." ".dateFr($row['users_date_added'], $row['users_lang'])." ".SUR." http://".$www2.$domaineFull." ".A_PAS_ETE_FINALISE."<br>";
      		$_SESSION['sendMessageRappel'].= SI_VOUS_SOUHAITE_EFFECTER_VOTRE_PAIEMENT."<br><br>";
      		$_SESSION['sendMessageRappel'].= "http://".$www2.$domaineFull."/direct_betalen.php?lang=".$row['users_lang']."&nic=".$row['users_nic']."<br><br>";
      		$_SESSION['sendMessageRappel'].= VOUS_POUVEZ_EGALEMENT." :<br>";
            $_SESSION['sendMessageRappel'].= "- ".VOUS_CONNECTER_A_VOTRE_COMPTE_SUR." http://".$www2.$domaineFull.", ".PUIS_REGLER_VOTRE_COMMANDE.".<br>";
      		$_SESSION['sendMessageRappel'].= "- ".VOUS_RENDRE_SUR_VOTRE_INTERFACE." http://".$www2.$domaineFull."/klantlogin/index.php ".OU." http://".$www2.$domaineFull."/infos.php?lang=".$row['users_lang']."&info=11  ".PUIS_VOUS_CONNECTER_AVEC_VOTRE." NIC # ".$row['users_nic'].".<br><br>";
            $_SESSION['sendMessageRappel'].= "-----------<br>";
            $_SESSION['sendMessageRappel'].= A10.": ".$row['users_password']."<br>";
            $_SESSION['sendMessageRappel'].= "NIC (".NICO."): ".$row['users_nic']."<br>";
      		$_SESSION['sendMessageRappel'].= A_EMAIL.": ".$row['users_email']."<br>";
            $_SESSION['sendMessageRappel'].= "-----------<br><br>";
      		$_SESSION['sendMessageRappel'].= A38.",<br><br>".A37;
      		
      		if(!isset($_POST['action'])) {
               print "<table border='0' align='center' width='98%' class='TABLE2'><tr><td>";
               print $_SESSION['sendMessageRappel'];
               print "</td></tr></table>";
      		}
      		
      		print "<input type='hidden' name='target' value='rappel'>";
      		print "<input type='hidden' name='action' value='sendRappel'>";
      		print "<input type='hidden' name='id' value='".$_GET['id']."'>";
      		
            if(!isset($_POST['action'])) {
      		  print "<p align='center'>";
      		  print "<input type='submit' value='".ENVOYER_EMAIL_A." ".$row['users_email']."'>";
				  print "</p>";
				}
      		print "</form>";
			}
			
		print "</body></html>";
		
			if(isset($_POST['action']) and $_POST['action']=="sendRappel") {
			    print "<table border='0' align='center' width='98%' class='TABLE2'><tr><td>";
             print $_SESSION['sendMessageRappel'];
             print "</td></tr></table>";
 
             mail($to, $subject, rep_slash(str_replace("<br>","\r\n",$_SESSION['sendMessageRappel'])),
    				 "Return-Path: $from\r\n"
                     ."From: $from\r\n"
    				 ."Reply-To: $from\r\n"
    				 ."X-Mailer: PHP/" . phpversion());
 
                    $queryNote = mysql_query("SELECT users_note FROM users_orders WHERE users_nic='".$row['users_nic']."'");
                    $rowNote = mysql_fetch_array($queryNote);
                    $_SESSION['scssStoreRappel'] = EMAIL_DE_RAPPEL_PAIEMENT_ENVOYE_LE." ".$hoy;
                    if(empty($rowNote['users_note']) OR $rowNote['users_note']=='') {$internNote = $_SESSION['scssStoreRappel'];} else {$internNote = $_SESSION['scssStoreRappel']."\r\n---\r\n".$rowNote['users_note'];}
  
                    mysql_query("UPDATE users_orders SET users_note = '".$internNote."' WHERE users_nic = '".$row['users_nic']."'");
   
                    print "<p align='center'><span class='fontrouge'><b>".EMAIL_DE_RAPPEL_ENVOYE_AVEC_SUCCES.".</b></span></p>";
                    print "<p align='center'><a href='javascript:window.close()'><b>".A43."</b></a></p>";
    
                    if(isset($_SESSION['sendMessageRappel'])) unset($_SESSION['sendMessageRappel']);
                    if(isset($_SESSION['scssStoreRappel'])) unset($_SESSION['scssStoreRappel']);
			}
}
 

if(isset($_GET['target']) and $_GET['target'] == "deliveryOrder") {
$n = 600;
$hoy = dateFr(date("d-m-Y H:i:s"), $row['users_lang']);
$shipNum = str_replace("||","",$row['users_fact_num']);
if($shipNum=="") $shipNum="XXXX";
$shipNum = "BL-".$shipNum;
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="10" topmargin="10" marginwidth="0" marginheight="0">

<table border="1" width="700" align="center" cellpadding="10" cellspacing="0" class="TABLE4"><tr><td>


<table width="<?php print $n;?>"  align="center" border="0" cellpadding="2" cellspacing="0">
<tr>
<td align="left" colspan="2" valign="middle"><img src="../<?php print $logo;?>"></td>
</tr>
<tr>
<td colspan="2">

<table border="0" width="100%" cellpadding="0" cellspacing="0" class="TABLE25"><tr><td class="large"><b><?php print BON_LIVRAISON;?></b></td></tr></table>
</td>
</tr>
<tr valign="top">
<td style="padding: 5px">
<?php print "<b>".$store_name."</b><br>";
print $address_street."<br>".
$address_cp." - ".$address_city."<br>".
$address_country;
if(!empty($tel)) print "<br>".A6.": ".$tel;
if(!empty($fax)) print "<br>".FAX.": ".$fax;
if(!empty($address_autre)) print "<br>".$address_autre;
?>
</td>

<td align="right"  style="padding: 5px">
<b><?php print BON_LIVRAISON;?></b>: <?php print "<span style='color:red'><b>".$shipNum."</b></span>"; ?>
<br>
<b><?php print A8;?></b>: <?php print $hoy; ?>
</td>

</tr>
<tr valign="top">
<td colspan="2">
<br>

<?php
if(isset($adress2[7]) AND !empty($adress2[7])) {
    $notvainter = $adress2[7];
}
else {
    $notvainter = "--";
}
?>

<table border="0" width="100%" cellpadding="4" cellspacing="0"><tr>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9;?>:</b></td>
    <td>&nbsp;</td>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9A;?>:</b></td>
    </tr><tr>
    <td class="TABLE6" valign="top">
    <?php
    print $adrees;
    ?>
    </td>
    <td>&nbsp;</td>
    <td class="TABLE6" valign="top">
    <?php
    print $adreesShipping;
    ?></td>
</tr></table>

<br>
<table border="0" width="100%" cellpadding="4" cellspacing="0"><tr>
    <td colspan="5" class="TABLE40"><b><div class="FontGris"><?php print REFERENCE;?></div></b></td></tr><tr>
    <td class="TABLE7" align="center"><div class="FontGris"><u>ggg<?php print A11;?></u><br><?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$date[0]);?></div></td>
    <td class="TABLE9" align="center"><div class="FontGris"><u>NIC</u><br><?php print "<b>".$row['users_nic']."</b>";?></div></td>
    <td class="TABLE7" align="center"><div class="FontGris"><u><?php print A10;?></u><br><?php print "<b>".$row['users_password']."</b>";?></div></td>
</tr></table>

</td>
</tr>
<tr>
<td colspan="2">
<br>

<table border="0" width="100%" cellpadding="0" cellspacing="0" class="TABLE25"><tr><td class="large"><b><?php print A13;?></b></td></tr></table>

</td>
</tr>
</table>

<?php
print "<table width='".$n."' align='center' border='0' cellspacing='0' cellpadding='2'><tr><td>";

print "<table border='0' class='TABLE6' width='100%' align='center' cellspacing='0' cellpadding='2'><tr><td>";

print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='2'>";
print "<tr>";
print "<td class='TABLE7' align='center' width='20'><b>X</b></td>";
print "<td class='TABLE7'><b>".A14."/".A15."</b></td>";
print "<td class='TABLE7' width='100' align='center'><b>Categories</b></td>";
print "<td class='TABLE7' width='50' align='center'><b>".QUT."</b></td>";

$split = explode(",",$row['users_products']);
$split = sortProducts();
foreach ($split as $item) {
$check = explode("+",$item);
  if($check[1]!=="0" AND $check[3]!=="GC100") {
    $queryCat = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$check[9]."'");
    $rowCat = mysql_fetch_array($queryCat);
 
        if(!empty($check[6])) {
            $_optZ = explode("|",$check[6]);
			## session update option price
			$lastArray = $_optZ[count($_optZ)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
			$_optZ = implode("|",$_optZ);
			$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
		}
		else {
			$q="";
		}

    print "</tr><tr>";
    print "<td width='20' align='center'><img src='im/case.gif'></td>";
  
    print "<td>".A14.": <i>".strtoupper($check[3])."</i><br>".$check[4].$q."</td>";
   
    print "<td align='center'>".$rowCat['categories_name_'.$_SESSION['lang']]."</td>";
    
    print "<td width='50' align='center'>".$check[1]."</td>";
  }
}
print "</tr></table>";
print "</td></tr>";
print "<tr>";
print "<td>";
print "</td></tr></table>";
?>

<br>

<?php
print "<table border='0' align='right' width='100%' height='75' cellpadding='0' cellspacing='0' class='TABLE15'>";
print "<tr valign='top'>";
print "<td>&nbsp;</td>";
print "</tr>";
print "</table>";

print "</td>";
print "</tr>";
print "</table>";
?>
</td></tr></table>
</body>
</html>
<?php
}
?>
