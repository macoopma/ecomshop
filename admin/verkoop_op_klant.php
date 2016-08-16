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
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Niet toegelaten</p>";
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
$c = "";
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script language="javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var id = document.form101.id.value;

  if(document.form101.elements['id'].type != "hidden") {
    if(id == 'none' ) {
      error_message11 = error_message11 + "<?php print VEUILLEZ;?>.\n";
      error11 = 1;
    }
  }

  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>


<?php


$prods = mysql_query("SELECT * FROM users_pro WHERE users_pro_active='yes' ORDER BY users_pro_password");
$prodsNum = mysql_num_rows($prods);

if($prodsNum > 0) {
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' width='700' class='TABLE'>";
	print "<tr>";
	print "<td>";
	print "<form action='verkoop_op_klant_details.php' method='GET' name='form101' onsubmit='return formu()';>";
	print "<center>".FOU."&nbsp;&nbsp;<select name=id>";
		print "<option value='none'>---</option>";
		while ($produit = mysql_fetch_array($prods)) {
		        if(empty($produit['users_pro_company'])) {
		           $nomR = $produit['users_pro_lastname']." ".$produit['users_pro_firstname'];
		        }
		        else {
		           $nomR = $produit['users_pro_company'];
		        }
		       print "<option value='".$produit['users_pro_password']."'>".$produit['users_pro_password']." - ".$nomR;
		       print "</option>";
		}
	print "</select>";
	print "&nbsp;&nbsp;<input type='Submit' class='knop' value='".A2."'></div>";
	print "</td>";
	print "</tr>";
	print "</table>";
	
	print "<br>";
	$prodsdd = mysql_query("SELECT * FROM users_pro WHERE users_pro_active='yes' ORDER BY users_pro_password");
	
	print "<table border='0' cellpadding='4' cellspacing='1' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<td height='30' align='center' valign='middle' colspan='4'><b>".FOU."</b></td>";
	print "</tr><tr bgcolor='#CCFF99'>";
	print "<td>Klant</td>";
	print "<td>Bedrijf</td>";
	print "<td>Bestellingen</td>";
	print "<td>Totaal</td>";
	print "</tr><tr>";
	while($produita = mysql_fetch_array($prodsdd)) {
	        if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
	        if(empty($produita['users_pro_company'])) {
	           $nomR = $produita['users_pro_lastname']." ".$produita['users_pro_firstname'];
	        }
	        else {
	           $nomR = $produita['users_pro_company'];
	        }
	        unset($totalSales);
	        $clientSalesQuery = mysql_query("SELECT users_total_to_pay, users_refund
	                                         FROM users_orders 
	                                         WHERE users_password = '".$produita['users_pro_password']."'
	                                         AND users_payed='yes'
	                                         AND users_nic NOT LIKE 'TERUG%'
	                                         ");
	        $clientSalesQueryNum = mysql_num_rows($clientSalesQuery);
	        if(mysql_num_rows($clientSalesQuery) > 0) {
	           while($clientSalesResult = mysql_fetch_array($clientSalesQuery)) {
	              if($clientSalesResult['users_refund'] == "yes") $totalSales[] = 0; else $totalSales[] = $clientSalesResult['users_total_to_pay'];
	           }
	           
	           $totalSalesFinal = "<span style='color:#CC0000'>".sprintf("%0.2f",array_sum($totalSales))." ".$symbolDevise."</span>";
	           $totalOrd = count($totalSales);
	        }
	        else {
	           $totalSalesFinal= "0.00 ".$symbolDevise;
	           $totalOrd = 0;
	        }
	       print "<td bgcolor='".$c."'><a href='verkoop_op_klant_details.php?id=".$produita['users_pro_password']."'>".$produita['users_pro_password']."</b></td>";
	       print "<td bgcolor='".$c."'>".$nomR."</td>";
	       print "<td bgcolor='".$c."' align='left'>".$totalOrd."</td>";
	       print "<td bgcolor='".$c."'>".$totalSalesFinal."</td>";
	       print "</tr><tr>";
	}
	print "</tr>";
	print "</table>";
	
	print "<br><br><table border='0' cellpadding='4' cellspacing='1' align='center' class='TABLE' width='700'><tr><td><center>";
	print "<a href='resume_client_all.php?id=all'>".TOUS."</a>";
	print "</tr></td></table>";
}
else {
    print "<center><br><br><table border='0' cellpadding='4' cellspacing='1' align='center' class='TABLE' width='700'><tr><td><p align='center' class='fontrouge'><b>".NO_SUPPLIER."</b></tr></td></table>";
}
?>
</body>
</html>
