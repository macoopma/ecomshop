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
$c="";

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A19;?> - <?php print A20;?>: <a href='klant_fiche.php?action=view&id=<?php print $_GET['id'];?>'><span class="largeBold"><?php print $_GET['id'];?></span></a> - </p>

<?php
$hids = mysql_query("SELECT users_customer_delete, users_litige, users_nic, users_id, users_payment, users_date_added, users_email, users_total_to_pay, users_payed, users_confirm, users_statut, users_country
                      FROM users_orders
                      WHERE users_password = '".$_GET['id']."'
                      ORDER BY users_date_added
                      DESC");
$nicNum = mysql_num_rows($hids);

if($nicNum > 0) {
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
	
	print "<tr bgcolor='#FFFFFF'>";
	print "<td align='center'><b>".A4."</b></td>";
	print "<td align='center'><b>Land</b></td>";
	print "<td align='center'><b>".A3."</b></td>";
	print "<td align='center'><b>".A100."</b></td>";
	print "<td align='center'><b>Betaalwijze</b></td>";
	print "<td align='center'><b>".A31."</b></td>";
	print "<td align='center'><b>".A32."</b></td>";
	print "</tr>";
	
	while ($myhid = mysql_fetch_array($hids)) {
	                $emails[] = $myhid['users_email'];
	                
	               if(substr($myhid['users_nic'], 0, 5) !== "TERUG" AND $myhid['users_confirm']=="yes" AND $myhid['users_payed']=="yes") {
	                  $totalPayed[] = $myhid['users_total_to_pay'];
	               }
	               else {
	                  $totalNotPayed[] = 0;
	                  $totalPayed[] = 0;
	               }
	               
	               if(substr($myhid['users_nic'], 0, 5) == "TERUG") {
	                     $totalRefunded[] = $myhid['users_total_to_pay'];
	               }
	               else {
	                  $totalRefunded[] = 0;
	               }
	
	               if($myhid['users_statut'] == "no") { $traite = "<span class='fontrouge'><b>".NON."</b></span>";} else {$traite = OUI;}
	               if($myhid['users_payed'] == "no") {
	                     $totalNotPayed[] = $myhid['users_total_to_pay'];
	                     $pay = "<span class='fontrouge'><b>".NON."</b></span>";
	               }
	               else {
	                     $totalNotPayed[] = 0;
	                     $pay = OUI;
	               }
	               
	               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
	               if($myhid['users_litige']=="yes") {
	                  $c="#FF9900";
	                  $totalLitige[] = 1;
	               }
	               else {
	                  $totalLitige[] = 0;
	               }
	               
	               if($myhid['users_customer_delete'] == "yes") $c="#CCFF00";
	
	                    print "<tr bgcolor='".$c."'>";
	                    print "<td align='center'>".dateFr($myhid['users_date_added'],$_SESSION['lang'])."</td>";
	                    print "<td align='center'>".$myhid['users_country']."</td>";
	                    print "<td  align='center'><a href='detail.php?id=".$myhid['users_id']."&from=account&acc=".$_GET['id']."'><span style='color:#CC0000'><b>".str_replace("||","",$myhid['users_nic'])."</b></span></a></td>";
	                    print "<td  align='center'><b>".$myhid['users_total_to_pay']."</b></td>";
	                    print "<td  align='center'><b>".strtoupper($myhid['users_payment'])."</b></td>";
	                    print "<td  align='center'>".$pay."</td>";
	                    print "<td  align='center'>".$traite."</td>";
	                    print "</tr>";
	}
	print "</table>";
	
	$totalPayedClient = sprintf("%0.2f",array_sum($totalPayed));
	$totalNotPayedClient = sprintf("%0.2f",array_sum($totalNotPayed));
	$totalRefundedClient = sprintf("%0.2f",array_sum($totalRefunded));
	$totalClient = sprintf("%0.2f",$totalPayedClient+$totalRefundedClient);
	$totalLitigeClient = array_sum($totalLitige);
	
	print "<p align='center'>";
	if($nicNum>0) {
	   $s = ($nicNum>1)? "en" : "";
	   print "<b>".$nicNum."</b> ".COMMANDE.$s." ".POUR_CE_CLIENT;  
	}
	if($totalLitigeClient>0) {
	   $s = ($totalLitigeClient>1)? "en" : "";
	   print "<br><b>".$totalLitigeClient."</b> ".COMMANDE.$s." ".EN_LITIGE;
	}
	print "</p>";
	
	print "<p align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
	print "<b>".A33."</b>: ".$totalPayedClient." ".$symbolDevise."<br>";
	print "<b>".A33A."</b>: ".$totalNotPayedClient." ".$symbolDevise."<br>";
	print "<b>".A34."</b>: ".$totalRefundedClient." ".$symbolDevise."<br>";
	print "<br><b>".A190." <a href='klant_fiche.php?action=view&id=".$_GET['id']."'>".$_GET['id']."</a></b>: ".$totalClient." ".$symbolDevise."<br>";
	print "</tr></table><br><br><br>";
}
else {
	print "<p align='center'>".A2A."<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
	if(isset($_GET['from']) AND $_GET['from']=="pro") {
		print "<p align='center'><a href='klant_accounts_beheren.php?page=0'><b>".A4A."</b></a></p>";
	}
	else if(isset($_GET['from']) AND $_GET['from']=="devis") {
		print "<p align='center'><a href='offertes.php'><b>".A4A."</b></a></p>";
	}
	else {
		print "<p align='center'><a href='zoeken.php'><b>".A4A."</b></a></p>";
	}
	print "</tr></table><br><br><br>";
}
?>
</body>
</html>
