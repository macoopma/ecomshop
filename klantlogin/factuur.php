<?php
session_start();


include('../configuratie/configuratie.php');

$la_link = "../includes/lang/klantlogin/lang".$_GET['l']."/factuur.php";
include($la_link);

// logo
if($logo=="noPath") $logo="im/zzz.gif";

function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}


function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
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

 
$query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_GET['nic']."'");
$row = mysql_fetch_array($query);

$yoyo = tax_price($row['users_country']);
$numFact = str_replace("||","",$row['users_fact_num']);
if($numFact=="") $numFact="XXXX";
$adress2 = explode("|",$row['users_facture_adresse']);

if($row['users_payment'] == "BO") $payment = A1;
if($row['users_payment'] == "ch") $payment = A2;
if($row['users_payment'] == "cc") $payment = A3;
if($row['users_payment'] == "pp") $payment = "Paypal";
if($row['users_payment'] == "mb") $payment = "MoneyBookers";
if($row['users_payment'] == "ma") $payment = A4;
if($row['users_payment'] == "BL") $payment = A22A;
if($row['users_payment'] == "tb") $payment = TRAITE_BANCAIRE;
if($row['users_payment'] == "wu") $payment = "Western Union";
if($row['users_payment'] == "ss") $payment = "Liaison-SSL";
if($row['users_payment'] == "eu") $payment = "1euro.com";
if($row['users_payment'] == "pn") $payment = "Pay.nl";
 

if(isset($_GET['target']) and $_GET['target'] == "impression") {
$n = 750;
 
$splitEmail = explode("|",$row['users_facture_adresse']);
  if(empty($splitEmail[3])) {$surburb = "";} else {$surburb = $splitEmail[3]."<br>";}
  if(empty($splitEmail[1])) {$company = "";} else {$company = $splitEmail[1]."<br>";}
$adresse = $splitEmail[0]."<br>".$company."".$splitEmail[2]."<br>".$surburb."".$splitEmail[4]."<br>".$splitEmail[5]."<br>".$splitEmail[6];
 
    $prov = ($row['users_province']=="autre")? "": $row['users_province']."<br>";
    $company2 = (empty($row['users_company']))? "": $row['users_company']."<br>";
    $surburb2 = (empty($row['users_surburb']))? "": $row['users_surburb']."<br>";
	$adreesShipping	= $row['users_gender']." ".$row['users_lastname']." ".$row['users_firstname'].",<br>"
				.$company2
            .$row['users_address']."<br>"
				.$surburb2
				.$row['users_zip']."<br>"
				.$row['users_city']."<br>"
				.$prov
				.$row['users_country'];

     
	mysql_query("UPDATE users_orders SET users_facture = users_facture+1 WHERE users_nic='".$row['users_nic']."'");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='../admin/style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table border="0" width="700" align="center" cellpadding="10" cellspacing="0"><tr><td>


<table width="<?php print $n;?>"  align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" colspan="2"><img src="../<?php print $logo;?>"></td>
</tr>
<tr>
<td colspan="2">
<br><br>

<?php
$comRemb = ($row['users_refund']=='yes')? " (".COMMANDE_REMBOURSEE.")" : "";
?>
<table border="0" width="100%" cellpadding="0" cellspacing="0" class="TABLE25"><tr><td class="large"><b><?php print A5.$comRemb;?></b></td></tr></table>
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
print "<br>URL: <a href='' target='_blank'>http://".$www2.$domaineFull."</a><br>
       Email: <a href='mailto:".$mailOrder."'>".$mailOrder."</a><br>";
if(!empty($address_autre)) print "<br>".$address_autre;
?>
</td>

<td align="right" style="padding: 5px">
<b><?php print A7;?></b>: <?php print "<span color='red'><b>".$numFact."</b></span>"; ?>
<br>
<b><?php print A8;?></b>: <?php print dateFr($row['users_date_payed'],$_GET['l']); ?>

<?php
if($row['users_refund']=='yes') {
    print "<br><br>";
    print "<p align='right'>";
    print "<table border='0' align='right' cellspacing='0' cellpadding='5' style='BACKGROUND-COLOR:#000000; border:1px #FFFFFF dotted;'><tr>";
    print "<td style='font-size:22px; color:#f1f1f1' align='center'>";
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
 



<table border="0" width="100%" cellpadding="4" cellspacing="0"><tr>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9;?>:</b></td>
    <td>&nbsp;</td>
    <td class="TABLE4" align="left" width="49%" class="large"><b><?php print A9A;?>:</b></td>
    </tr><tr>
    <td class="TABLE6" valign="top"><?php print $adresse;?></td>
    <td>&nbsp;</td>
    <td class="TABLE6" valign="top"><?php print $adreesShipping;?></td>
</tr></table>




</td>
</tr>
<tr valign="top">
<td align="left" colspan="2">
<?php
if(isset($adress2[7]) AND !empty($adress2[7])) {
    $notvainter = $adress2[7];
}
else {
    $notvainter = "--";
}
?>

<br>
<table border="0" width="100%" cellpadding="4" cellspacing="0" class="FontGris"><tr>
    <td colspan="5" class="TABLE40"><b><div class="FontGris"><?php print VOS_REFERENCES;?></div></b></td></tr><tr>
    <td class="TABLE7" align="center"><div class="FontGris"><u><?php print A11;?></u><br><?php print dateFr($row['users_date_added'],$_GET['l']);?></div></td>
    <td class="TABLE9" align="center"><div class="FontGris"><u>NIC</u><br><?php print "<b>".$row['users_nic']."</b>";?></div></td>
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
print "<tr bgcolor='#CCCCCC'>";
print "<td class='TABLE9' align='center'><b>Ref/".A15."</b></td>";
print "<td class='TABLE9' width='50' align='center'><b>Qt</b></td>";
print "<td class='TABLE9' width='50' align='center'><b>".A45."</b></td>";
if($row['users_products_tax_statut']!=="") print "<td class='TABLE9' width='50' align='center'><b>".TVA."</b></td>";
print "<td class='TABLE9' width='80' align='right'><b>".A24."</b></td>";

$split = explode(",",$row['users_products']);
foreach ($split as $item) {
$check = explode("+",$item);
    if($check[1]!=="0") {
        print "</tr><tr>";
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
        
        print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4].$q."</td>";
        // Eco part
        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
        // hoveelheid
        print "<td width='50' align='center'>".$check[1]."</td>";
        // eenheidsprijs
        $priceU = ($row['users_products_tax_statut']=="TTC")? $check[2]/(1+($check[5]/100)) : $check[2];
        print "<td width='50' align='center'>".sprintf("%0.2f",$priceU)."</td>";
        // BTW
        if($row['users_products_tax_statut']!=="") print "<td width='50' align='center'>".$check[5]."%</td>";
        // prijs
        $priceTTC = ($row['users_products_tax_statut']=="TTC")? ($check[2]*$check[1])/(1+($check[5]/100)) : ($check[2] * $check[1]);
        print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
    }
}
print "</tr></table>";

print "<br>";

$la = 120;

//-------
// totaal
//-------
$totalDiscount = abs($row['users_account_remise_app'] + $row['users_remise'] + $row['users_remise_coupon']);

print "<table border='0' style='BACKGROUND-COLOR: #FFFFCC; border: 1px #d8d8c4 solid; padding:5px' align='right' cellspacing='0' cellpadding='2'><tr>";
// artikelen HT
if(isset($totalDiscount) AND $totalDiscount>0) {
	$totalHtAvantRemise = sprintf("%0.2f",$row['users_products_ht']+$totalDiscount);
    print ($row['users_products_tax_statut']=="")? "<td align='right'>".A46."</td>" : "<td align='right'>".TOTAL_HT_AVANT_REMISE."</td>";    
    print "<td align='right' width='".$la."'>".$totalHtAvantRemise."</td>";
    
    
print "</tr><tr>";
if(abs($row['users_account_remise_app'])>0) {
    $sig = (substr($row['users_nic'], 0, 5) == "TERUG")? "" : "-";
	print "<td align='right'>".A51."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".$sig.sprintf("%0.2f",abs($row['users_account_remise_app']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_remise'])>0) {
    $sig1 = ($row['users_remise']<0)? "" : "-";
	print "<td align='right'>".A21."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".$sig1.sprintf("%0.2f",abs($row['users_remise']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_remise_coupon'])>0) {
    $sig2 = ($row['users_remise_coupon']<0)? "" : "-";
	print "<td align='right'>".A22."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".$sig2.sprintf("%0.2f",abs($row['users_remise_coupon']))."</span></td>";
	print "</tr><tr>";
}
    print ($row['users_products_tax_statut']=="")? "<td align='right'><br>".A46."</td>" : "<td align='right'><br>".TOTAL_HT."</td>";
    print "<td align='right' width='".$la."'><br>".$row['users_products_ht']."</td>";
}
else {
    print ($row['users_products_tax_statut']=="")? "<td align='right'>".A46."</td>" : "<td align='right'>".TOTAL_HT."</td>";
    print "<td align='right' width='".$la."'>".$row['users_products_ht']."</td>";
}

// btw artikelen
if($row['users_products_tax_statut']!=="") {
print "</tr><tr>";
print "<td align='right'>".$taxeName."</td>";
print "<td align='right' width='100'>";
     
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

// print "<td align='right'>".$row['users_products_tax']."</td>";
// levering
print "</tr><tr>";
print "<td align='right'>".A19."</td>";
print "<td align='right'>".$row['users_ship_ht']."</td>";
// taxe livraison
if($row['users_products_tax_statut']!=="") {
   print "</tr><tr>";
   print "<td align='right'>".$taxeName." ".$yoyo[1]."%</td>";
   print "<td align='right'>".$row['users_ship_tax']."</td>";
}

// verpakking
if(abs($row['users_sup_ttc']) > 0) {
    print "</tr><tr>";
    print "<td align='right'>".EMBALLAGE."</td>";
    print "<td align='right'>".$row['users_sup_ht']."</td>";
    // taxe emballage
    if($row['users_products_tax_statut']!=="") {
      print "</tr><tr>";
      print "<td align='right'>".$taxeName." ".$taxe."%</td>";
      print "<td align='right'>".$row['users_sup_tax']."</td>";
   }
}   
print "</tr><tr>";
$la = 90;
if(abs($row['users_remise_gc'])>0) {
    $sig3 = ($row['users_remise_gc']<0)? "" : "-";
	print "<td align='right'>".A22C."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>".$sig3.sprintf("%0.2f",abs($row['users_remise_gc']))."</span></td>";
	print "</tr><tr>";
}
if(abs($row['users_contre_remboursement'])>0) {
    $sig4 = ($row['users_contre_remboursement']<0)? "-" : "";
	print "<td align='right'>".A22A."</td>";
	print "<td align='right' width='".$la."'>".$sig4.sprintf("%0.2f",abs($row['users_contre_remboursement']))."</td>";
	print "</tr><tr>";
}
/*
print "<td align='left'>".A21."</td>";
print "<td align='right'>-".$row['users_remise']."</td>";
print "</tr><tr>";
print "<td align='left'>".A22." (<i>".$row['users_remise_coupon_name']."</i>)</td>";
print "<td align='right'>-".$row['users_remise_coupon']."</td>";
*/

print "<td align='right' class='large'><b>".TOTALPRI."</b>:</td>"    ;
print "<td align='right'class='large'><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
print "</tr>";
print "</table>";

//print "<br>".A23.": <b>".$row['users_products_weight']."</b> gr<br>".$row['users_products_weight_price']." ".$row['users_symbol_devise']."/gr HT";
$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
if($ecoTaxFactFinal>0) {
    print "<div><i><span style='color:#00CC00'>".DONT." Eco part : ".$ecoTaxFactFinal." ".$symbolDevise."</span></i></div>";
}
/*
// bericht bij bestelling
if($activerRemisePastOrder=="oui" AND $remisePastOrder>0) {
$avoir = sprintf("%0.2f",$row['users_products_ht']*$remisePastOrder/100);
print "<i>Avoir sur la prochaine commande : <b>".$avoir."</b> ".$symbolDevise."</i>";
}
*/
if($mlFact!=="") {
print "<div><i><span style='color:#00CC00'>".$mlFact."</span></i></div>";
}
print "</td></tr>
<tr>
<td>";
print "</td></tr></table>";

print "
</td>
</tr>
</table>";
?>
</td></tr></table>
</body>
</html>
<?php
}
/*---
E-mail
---*/

if(isset($_GET['target']) and $_GET['target']=="mail") {
$splitEmail = explode("|",$row['users_facture_adresse']);
  if(empty($splitEmail[3])) {$surburb = "";} else {$surburb = $splitEmail[3]."<br>";}
  if(empty($splitEmail[1])) {$company = "";} else {$company = $splitEmail[1]."<br>";}
$adresse = $splitEmail[0]."<br>".$company."".$splitEmail[2]."<br>".$surburb."".$splitEmail[4]."<br>".$splitEmail[5]."<br>".$splitEmail[6];

$message = "<table width='500' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLEMenuPathCenter'><tr>";
$message.= "<td align='left'>";
$message.= "<img src='http://".$www2.$domaineFull."/".$logo."'></td>";
$message.= "<td align='right' valign='top'>";
$message.= "<b><span class='large'>".A5."</span></b><br>";
$message.= A7.": ".$numFact."<br>";
$message.= A8.": ".dateFr($row['users_date_payed'],$_GET['l']);
$message.= "</td>";
$message.= "</tr><tr><td colspan='2'>";

$message.= "<table width='500' align='center' border='0' cellspacing='0' cellpadding='0'><tr>";
$message.= "<td colspan='2'>";
$message.= "<div align='left'>";
$message.= "<b>".$store_name."</b><br>";
$message.= "URL: <a href='' target='_blank'>http://".$www2.$domaineFull."</a><br>";
$message.= "E-mail: <a href='mailto:".$mailOrder."'>".$mailOrder."</a><br>";
$message.= $address_street."<br>";
$message.= $address_cp." - ".$address_city."<br>";
$message.= $address_country;
if(!empty($address_autre)) $message .= "<br>".$address_autre;
if(!empty($tel)) $message.="<br>".A6.": ".$tel;
if(!empty($fax)) $message.="<br>".FAX.": ".$fax;
$message.= "</div>";
$message.= "</td>";
$message.= "<td valign='middle'>";
if($row['users_refund']=='yes') {
    $message.= "<p align='right'>";
    $message.= "<table border='0' align='right' cellspacing='0' cellpadding='5' style='BACKGROUND-COLOR:#000000; border:1px #FFFFFF dotted;'><tr>";
    $message.= "<td style='font-size:18px; color:#f1f1f1' align='center'>";
    $message.= "<i>-- ".COMMANDE_REMBOURSEE." --</i>";
    $message.= "</td>";
    $message.= "</tr></table>";
    $message.= "</p>";
}
$message.= "</td>";

$message.= "</tr>
<td colspan='3'>
--------------------------------------------------------------------------------------------------
</td>
</tr>
<td>
<b>".A9."</b><br>
$adresse
</td>
<td valign='top' align='right' colspan='2'>
<b>NIC</b>: <span class='fontrouge'>".$row['users_nic']."</span><br>
<b>".A10."</b>: <span class='fontrouge'>".$row['users_password']."</span><br>
<b>".A11."</b>: ".dateFr($row['users_date_added'],$_GET['l']);

if(isset($splitEmail[7]) AND !empty($splitEmail[7])) {
    $message .= "<br><b>".NO_TVA.": </b>".$splitEmail[7];
}

$message.= "</td>";
$message.= "</tr>";
$message.= "<td colspan='3'>";
$message.= "--------------------------------------------------------------------------------------------------";
$message.= "</td>";
$message.= "</tr>";
$message.= "</table>";

$message.= "<b>".A13."</b><br>";
$message.= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td>";
$message.= "<table border='0' width='100%' align='center' cellspacing='1' cellpadding='1'>";
$message.= "<tr bgcolor='#F6F6EB'>";
$message.= "<td align='center'><b>Ref/".A15."</b></td>";
$message.= "<td width='50' align='center'><b>Qt</b></td>";
$message.= "<td width='50' align='center'><b>".A45."</b></td>";
if($row['users_products_tax_statut']!=="") $message.= "<td width='50' align='center'><b>".TVA."</b></td>";
$message.= "<td width='80' align='right'><b>".A24."</b></td>";
$split = explode(",",$row['users_products']);
foreach ($split as $item) {
    $check = explode("+",$item);
    if($check[1]!=="0") {
        $message.="</tr><tr>";
		// Opties
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

        $message.="<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4].$q."</td>";
 
        if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
  
        $message.="<td width='50' align='center'>".$check[1]."</td>";
   
        $priceU = ($row['users_products_tax_statut']=="TTC")? $check[2]/(1+($check[5]/100)) : $check[2];
        $message.= "<td width='50' align='center'>".sprintf("%0.2f",$priceU)."</td>";
    
        if($row['users_products_tax_statut']!=="") $message.= "<td width='50' align='center'>".$check[5]."%</td>";
     
        $priceTTC = ($row['users_products_tax_statut']=="TTC")? ($check[2]*$check[1])/(1+($check[5]/100)) : ($check[2] * $check[1]);
        $message.= "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
    }
}

$ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
$message.="</tr></table><br>";

//<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>
//<td>".A23.": <b>".$row['users_products_weight']."</b> gr | ".$row['users_products_weight_price']." ".$row['users_symbol_devise']."/gr</td>
//</tr></table>

 

 

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

// totaal btw
if($row['users_products_tax_statut']!=="") {
$message.="</tr><tr>";
$message.="<td align='right'>".$taxeName."</td>";
$message.="<td width='110' align='right'>";

                // display multiple tax
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
   $message.="<td align='right'>".$taxeName."</td>";
   $message.="<td align='right'>".$row['users_ship_tax']."</td>";
}
// verpakking
if(abs($row['users_sup_ttc']) > 0) {
                $message .= "</tr><tr>";
                $message .= "<td align='right'>".EMBALLAGE."</td>";
                $message .= "<td align='right'>".$row['users_sup_ht']."</td>";
                if($row['users_products_tax_statut']!=="") {
                  $message .= "</tr><tr>";
                  $message .= "<td align='right'>".$taxeName." ".$taxe."%</td>";
                  $message .= "<td align='right'>".$row['users_sup_tax']."</td>";
                }
}

$message.= "</tr><tr>";

$la=70;
if(abs($row['users_remise_gc'])>0) {
    $sig3 = ($row['users_remise_gc']<0)? "" : "-";
	$message.= "<td align='right'>".A22C."</td>";
	$message.= "<td align='right' width='".$la."'><span class='fontrouge'>".$sig3.sprintf("%0.2f",abs($row['users_remise_gc']))."</span></td>";
	$message.= "</tr><tr>";
}
if(abs($row['users_contre_remboursement'])>0) {
    $sig4 = ($row['users_contre_remboursement']<0)? "-" : "";
	$message.="</tr><tr>";
	$message.="<td align='right'>".A22A."</td>";
	$message.="<td align='right'>".$sig4.sprintf("%0.2f",abs($row['users_contre_remboursement']))."</td>";
	$message.= "</tr><tr>";
}

$message.="<td align='right' class='large'><br><b>TOTAL</b>:</td>";
$message.="<td align='right' class='large'><br><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
$message.="</tr>";
$message.="</table>";
$message.="</td></tr>";
$message.="<tr>";
$message.="<td>";
if($ecoTaxFactFinal>0) {
$message .= "<div><i><span style='color:#00CC00'>".DONT." Eco-part : ".$ecoTaxFactFinal." ".$symbolDevise."</span></i></div>";
}
if($mlFact!=="") {
$message .= "<div><i><span style='color:#00CC00'>".$mlFact."</span></i></div>";
}
$message.= "<div align='center'>--------------------------------------------------------------------------------------------------</div>";
$message.= "<div align='left'>$store_company ".A25.".</div>";
$message.= "<div align='left'>".A26."  ".$row['users_email']." ".A27."</div>";
$message.= "</td></tr></table>";
$message.= "</td></tr></table>";

$message = "<html>
<head>
<title>".$store_name."</title>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
<STYLE TYPE='text/css'>
<!--
BODY             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px; BACKGROUND COLOR: #F6F6EB}
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
print ">><a href='factuur.php?nic=".$_GET['nic']."&target=mail&action=send&l=".$_GET['l']."'><b>".A28."</b></a><<";
print "</p>";

if(isset($_GET['action']) and $_GET['action']=="send") {
$to = $row['users_email'];
$subject = "[".A5." $numFact] - NIC#: ".$row['users_nic'];
$from = $mailOrder;
print "<p align='center'>Verstuurd naar $to...</p>";

mail($to, $subject, rep_slash($message),
     "Return-Path: $from\r\n"
     ."From: $from\r \n"
     ."Reply-To: $from\r \n"
     ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
     ."X-Mailer: PHP/" . phpversion());
    // factuur nummer
	mysql_query("UPDATE users_orders SET users_facture = users_facture+1 WHERE users_nic='".$row['users_nic']."'");
print "<div align='center'><span class='fontrouge'><b>".A29."</b></span></div>";
}
}

?>
