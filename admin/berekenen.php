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

function displayNegative($value) {
   if($value<0) $valueDiplay = "<span style='color:#CC0000'>".$value."</span>"; else $valueDiplay = $value;
   return $valueDiplay;
}

 
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
<p align="center" class="largeBold"><?php print GENERAL;?></p>

<?php
if(!isset($_GET['nico'])) {

$nowDay = date("d");
$nowMonth = date("m");
$nowYear = date("Y");

if($nowMonth=="1") $nowMonth = A2;
if($nowMonth=="2") $nowMonth = A3;
if($nowMonth=="3") $nowMonth = A4;
if($nowMonth=="4") $nowMonth = A5;
if($nowMonth=="5") $nowMonth = A6;
if($nowMonth=="6") $nowMonth = A7;
if($nowMonth=="7") $nowMonth = A8;
if($nowMonth=="8") $nowMonth = A9;
if($nowMonth=="9") $nowMonth = A10;
if($nowMonth=="10") $nowMonth= A11;
if($nowMonth=="11") $nowMonth= A12;
if($nowMonth=="12") $nowMonth= A13;

if(isset($_GET['mois1']) and $_GET['mois1'] ==A2) $_GET['mois1']=1;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A3) $_GET['mois1']=2;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A4) $_GET['mois1']=3;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A5) $_GET['mois1']=4;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A6) $_GET['mois1']=5;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A7) $_GET['mois1']=6;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A8) $_GET['mois1']=7;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A9) $_GET['mois1']=8;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A10) $_GET['mois1']=9;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A11) $_GET['mois1']=10;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A12) $_GET['mois1']=11;
if(isset($_GET['mois1']) and $_GET['mois1'] ==A13) $_GET['mois1']=12;

if(isset($_GET['mois2']) and $_GET['mois2'] ==A2) $_GET['mois2']=1;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A3) $_GET['mois2']=2;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A4) $_GET['mois2']=3;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A5) $_GET['mois2']=4;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A6) $_GET['mois2']=5;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A7) $_GET['mois2']=6;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A8) $_GET['mois2']=7;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A9) $_GET['mois2']=8;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A10) $_GET['mois2']=9;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A11) $_GET['mois2']=10;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A12) $_GET['mois2']=11;
if(isset($_GET['mois2']) and $_GET['mois2'] ==A13) $_GET['mois2']=12;


$days1 = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
$months1 = array("1"=>A2,"2"=>A3,"3"=>A4,"4"=>A5,"5"=>A6,"6"=>A7,"7"=>A8,"8"=>A9,"9"=>A10,"10"=>A11,"11"=>A12,"12"=>A13);
$years1 = array("2011","2012","2013","2014","2015","2016","2017","2018","2019","2020","2021");

 
print "<div align='center'>".SELECT_DATES."</div><br>";
print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
print "<table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'>";
print "<form method='GET' action='".$_SERVER['PHP_SELF']."'>";
print "<tr>";
print "<td align='center'>";
    print "<b>".A15."</b> ";

    print "<select name='jour1' class='site'>";
    for($c=0; $c<= count($days1)-1; $c++)
    {
    $a=$c+1;
    if(isset($_GET['jour1'])) {
       if($days1[$c]== $_GET['jour1']) $sel1 = "selected"; else $sel1="";
    }
    else {
       if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
    }
    print "<option value=".$a." ".$sel1.">".$days1[$c]."</option>";
    }
    print "</select>&nbsp;&nbsp;";
 
    print "<select name='mois1' class='site'>";
    $keys = array_keys($months1);
    for($x1=1; $x1 <= count($months1); $x1++)
    {
    $p=$x1-1;
    if(isset($_GET['mois1'])) {
       if($keys[$p]== $_GET['mois1']) $sel2 = "selected"; else $sel2="";
    }
    else {
       if($months1[$x1]==$nowMonth) $sel2 = "selected"; else $sel2="";
    }
    print "<option value=".$keys[$p]." ".$sel2.">".$months1[$x1]."</option>";
    }
     print "</select>&nbsp;&nbsp;";

    print "<select name='an1' class='site'>";
    for($x3=0; $x3 <= count($years1)-1; $x3++) {
    if(isset($_GET['an1'])) {
       if($years1[$x3]== $_GET['an1']) $sel3 = "selected"; else $sel3="";
    }
    else {
       if($years1[$x3]==$nowYear) $sel3 = "selected"; else $sel3="";
    }
    print "<option value=".$years1[$x3]." ".$sel3.">".$years1[$x3]."</option>";
    }
    print "</select>";
      print "</td></tr><tr>";
      print "<td align='center'>";
      print "<b>".A16."</b> ";
 
print "<select name='jour2' class='site'>";
for($c=0; $c<= count($days1)-1; $c++)
{
$a=$c+1;
if(isset($_GET['jour2'])) {
   if($days1[$c]== $_GET['jour2']) $sel1 = "selected"; else $sel1="";
}
else {
   if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
}
print "<option value=".$a." ".$sel1.">".$days1[$c]."</option>";
}
    print "</select>&nbsp;&nbsp;";
 
print "<select name='mois2' class='site'>";
$keys = array_keys($months1);
for($x=1; $x <= count($months1); $x++)
{
$p=$x-1;
if(isset($_GET['mois2'])) {
   if($keys[$p]== $_GET['mois2']) $sel2 = "selected"; else $sel2="";
}
else {
   if($months1[$x]==$nowMonth) $sel2 = "selected"; else $sel2="";
}
print "<option value=".$keys[$p]." ".$sel2.">".$months1[$x]."</option>";
}
    print "</select>&nbsp;&nbsp;";

 
print "<select name='an2' class='site'>";
for($x=0; $x <= count($years1)-1; $x++)
{
if(isset($_GET['an2'])) {
   if($years1[$x]== $_GET['an2']) $sel3 = "selected"; else $sel3="";
}
else {
	$n=$x3+1;
   if($years1[$x]==$nowYear) $sel3 = "selected"; else $sel3="";
}
print "<option ".$sel3.">".$years1[$x]."</option>";
}
print "</select>";

print "</td>";
print "</tr>";
print "<tr>";
print "<td colspan='2' align='center'>";
print "<input type='submit' name='Submit' value='".A17."' class='knop'>";
print "</td>";
print "</tr>";
print "</form>";
print "</table>";
}
 

if(isset($_GET['jour1']) or isset($dateAdded1)) {

    $control1 = checkdate($_GET['mois1'],$_GET['jour1'],$_GET['an1']);
    $control2 = checkdate($_GET['mois2'],$_GET['jour2'],$_GET['an2']);

if($control1 == true and $control2 == true) {

   $control11 = mktime(0,0,0,$_GET['mois1'],$_GET['jour1'],$_GET['an1']);
   $control21 = mktime(0,0,0,$_GET['mois2'],$_GET['jour2'],$_GET['an2']);

   if($control21 >= $control11) {

   $dateAdded1 = "".$_GET['an1']."-".$_GET['mois1']."-".$_GET['jour1']." 00:00:00";
   $dateAdded2 = "".$_GET['an2']."-".$_GET['mois2']."-".$_GET['jour2']." 00:00:00";

   
   $query = mysql_query("SELECT *
                         FROM users_orders
                         WHERE TO_DAYS(users_date_payed) >= TO_DAYS('".$dateAdded1."')
                         AND TO_DAYS(users_date_payed) <= TO_DAYS('".$dateAdded2."')
                         AND users_payed = 'yes'
                         ORDER BY users_date_payed");

   if(isset($_GET['nico'])) {
     $nico = explode("|",$_GET['nico']);
     $addThis="";
     foreach($nico AS $item) {
        $addThis .= "users_nic='".$item."' OR ";
     }
     $addThis = substr($addThis, 0, -3);
     $query = mysql_query("SELECT *
                           FROM users_orders
                           WHERE 1
                           AND (".$addThis.")
                           ORDER BY users_id");
      $exportLink = "&nico=".$_GET['nico'];
   }
   else {
      $exportLink = "";
   }

   $resultNum = mysql_num_rows($query);

   if($resultNum > 0) {
   print "<p align='center' class='titre'>".A18." ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']." ".A19." ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."</p>";
 

                print "<table align='center' border='0' cellspacing='1' cellpadding='3' class='TABLE'>";
                print "<tr bgcolor='#FFFFFF'>";
                print "<td align='left'><b>Datum</b></td>";
                print "<td align='left'><b>".FACTURE."</b></td>";
                print "<td align='left'><b>Klant</b></td>";
                print "<td align='left'><b>".COMMANDE."</b></td>";
                print "<td align='left'><b>Betaald</b></td>";
                // Produits HT
                print "<td align='left'><b>".A21."</b></td>";
                // Produits TVA
                print "<td align='center'><b>".A23."</b></td>";
                // Livraison HT
                print "<td align='center'><b>".A22."</b></td>";
                print "<td align='center'><b>".A24."</b></td>";
                print "<td align='center' style='color:#00CC00'><b>".ECOHT."</b></td>";
                print "<td align='center' style='color:#00CC00'><b>".ECOTAX."</b></td>";
                print "<td align='center'><b>".CBRC."</b></td>";
                print "<td align='center'><b>".A42."</b></td>";
                print "<td align='center'><b>".A26A."</b></td>";
                print "<td align='center'><b>".CODE_DE_REDUC."</b></td>";
                print "<td><b>".A25."</b></td>";
                print "<td><span style='color:#bcbcbc'>".AFF1."</span></td>";
                print "<td align='center'><b>".DIVERS."</b></td>";
                print "</tr>";
               
               $multipleTax = array();
               $c="";

   while($result = mysql_fetch_array($query)) {
         if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
         if($result['users_litige']=="yes") $c="#FF9900";

         $articleHt[] = $result['users_products_ht'];
         $shipHt[] = $result['users_ship_ht'];
         $taxePercuProduit[] = $result['users_products_tax'];
         $taxePercuShipping[] = $result['users_ship_tax'];
         $remise = $result['users_remise'];
         $totalReduc = sprintf("%0.2f",$result['users_remise_coupon']);
         $remiseP[] = $result['users_remise'];
         $remiseCoupon[] = $result['users_remise_coupon'];
         $remiseFacture[] = $result['users_account_remise_app'];
         $remiseAll[] = $remise;
         $venteTTC[] = $result['users_total_to_pay'];
         $deeeHt[] = $result['users_deee_ht'];
         $deeeTax[] = $result['users_deee_tax'];
         $embHt[] = $result['users_sup_ht'];
         $embTax[] = $result['users_sup_tax'];
         $cc[] = $result['users_remise_gc'];
         $aff[] = $result['users_affiliate_amount'];
         if(!empty($result['users_multiple_tax'])) $multipleTax[] = $result['users_multiple_tax'];

         // users_fact_num
         if($result['users_fact_num']=="") {
            $users_fact_numZ = "...";
         }
         else {
            if($result['users_refund']=="yes" OR preg_match("/\bTERUG\b/i", $result['users_nic'])) {
               $users_fact_numZ = "<a href='factuur_scherm.php?id=".$result['users_id']."&target=impression' target='_blank'><span style='background:#000000; color:#FFFFFF'>".str_replace("||","",$result['users_fact_num'])."</span></a>";
            }
            else {
               $users_fact_numZ = "<a href='factuur_scherm.php?id=".$result['users_id']."&target=impression' target='_blank'>".str_replace("||","",$result['users_fact_num'])."</a>";
            }
         }
         // Client
         $users_companyZ = ($result['users_company']!=="")? "<br><i>(".$result['users_company'].")</i>" : "";
         // users_multiple_tax
         $_w="";
         $_q = explode("|", $result['users_multiple_tax']);
         foreach($_q AS $item) {
            $_z = explode(">",$item);
            if($_z[0]>0) $_w.= str_replace(">","%:&nbsp;",$item)."<br>";
         }
         // Livraison + Emballage ht et tva
         $users_sup_htZ = ($result['users_sup_ht']!==0)? sprintf("%0.2f",$result['users_ship_ht']+$result['users_sup_ht']) : sprintf("%0.2f",$result['users_ship_ht']);
         $users_sup_taxZ = ($result['users_sup_tax']!==0)? sprintf("%0.2f",$result['users_ship_tax']+$result['users_sup_tax']) : sprintf("%0.2f",$result['users_ship_tax']);         
         // users_fact_num
         $users_remise_gcZ = ($result['users_remise_gc']==0)? "..." : "(".$result['users_remise_gc'].")<br><i>".$result['users_gc']."</i>";
         // Points de fidélité
         if($result['users_account_remise_app']==0) {
            $users_account_remise_appZ = "...";
         }
         else {
            $toDelete = array("(",")","<b>","</b>","\r\n"," ","-");
            $users_remise_noteZZ = explode("|", $result['users_account_remise_note']);
            $users_remise_noteZZ = str_replace($toDelete,"",trim($users_remise_noteZZ[2]));
            
            $users_account_remise_appZ = "(".$result['users_account_remise_app'].")<br><i>".A26.":&nbsp;".$users_remise_noteZZ."</i>";
         }
         // users_remise
         if($result['users_remise']==0) {
            $users_remiseZ="..."; 
         }
         else {
            $toDelete = array("(",")","<b>","</b>","\r\n"," ","-");
            $users_remise_noteZ = explode("|", $result['users_remise_note']);
            $users_remise_noteZ = str_replace($toDelete,"",trim($users_remise_noteZ[2]));
            
            $users_remiseZ="(".$result['users_remise'].")<br><i>".A26.":&nbsp;".$users_remise_noteZ."</i>"; 
         }
         // users_affiliate_amount
         if($result['users_affiliate_amount']==0) {
            $users_affiliate_amountZ="...";
         }
         else {
            $users_affiliate_amountZ="(".$result['users_affiliate_amount'].")<br><i>".$result['users_affiliate']."</i>";
         }

         print "<tr bgcolor='".$c."'>";
         // Date
         print "<td align='top' valign='left'>".dateFr($result['users_date_payed'],$_SESSION['lang'])."</td>";
 
         print "<td align='top' valign='left''><b>".$users_fact_numZ."</b></td>";
  
         print "<td align='top' valign='left'>".$result['users_password']."</td>";
         // N°Commande
         print "<td align='top' valign='left'><a href='detail.php?id=".$result['users_id']."&from=tax&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>".str_replace("||","",$result['users_nic'])."</a></td>";
         // Pay
         print "<td align='top' valign='left'>".strtoupper($result['users_payment'])."</td>";
         // Produits HT
         print "<td align='top' valign='left'>".displayNegative($result['users_products_ht'])."</td>";
         // Produits TVA
         print "<td align='top' valign='left'>".displayNegative($result['users_products_tax'])."<br><i>".$_w."</i></td>";
         // Livraison HT
         print "<td align='top' valign='left'>".displayNegative($users_sup_htZ)."</td>";
         // Livraison TVA
         print "<td align='top' valign='left'>".displayNegative($users_sup_taxZ)."</td>";
         // EcoTax HT
         print "<td align='top' valign='left' style='color:#00CC00'>".displayNegative($result['users_deee_ht'])."</td>";
         // EcoTax TVA
         print "<td align='top' valign='left' style='color:#00CC00'>".displayNegative($result['users_deee_tax'])."</td>";
         // Chèques cadeaux
         print "<td align='top' valign='left'>".$users_remise_gcZ."</td>";
         // Points de fidélité
         print "<td>".$users_account_remise_appZ."</td>";
         // Remise boutique
		 print "<td align='top' valign='left'>".$users_remiseZ."</td>";
		 // Code de réduction
         print "<td align='top' valign='left'>";
         	 if($result['users_remise_coupon'] > 0) {
                  $toDelete = array("(",")","<b>","</b>","\r\n"," ","-");
                  $users_remise_couponZZ = explode("|", $result['users_coupon_note']);
                  $users_remise_couponZZ = str_replace($toDelete,"",trim($users_remise_couponZZ[2]));
				  	print "(".$result['users_remise_coupon'].")";
				 }
				 else {
				 	print "...";
				 }
				 if(($result['users_remise_coupon_name'])!=="") {
				 	print "<br><i>Code:&nbsp;".$result['users_remise_coupon_name']."<br>".A26.":&nbsp;".$users_remise_couponZZ."</i>";
				 }
				 else {
				 	print "";
				 }
		 print "</td>";
		 // Total reçu TTC
		 print "<td align='top' valign='left'><i><b>".displayNegative($result['users_total_to_pay'])."</b></i></td>";
		 // Affiliation (À payer)
		 print "<td align='top' valign='left'><span style='color:#bcbcbc'>".$users_affiliate_amountZ."</span></td>";
		 // Divers
		 $tro = explode("|",$result['users_facture_adresse']);
		 ## Pays de facturation
         $trot = "Facturatie: <span style='color:#0000FF'>".$tro[6]."</span>";
		 ## Pays de livraison
         $trot.= "<br>Levering: <span style='color:#0000FF'>".$result['users_country']."</span>";
		 ## TVA intracom
		 $trot.= ($tro[7]=="")? "" : "<br><span style='color:#000000'>".INTRACOM.": ".$tro[7]."</span>";
        ## Mode de livraison
        $requestShipModeZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$result['users_shipping']."'") or die (mysql_error());
        if(mysql_num_rows($requestShipModeZ) > 0) {
            $resultShipModeZ = mysql_fetch_array($requestShipModeZ);
            $trot.= "<br><span style='color:#9900CC'>[".$resultShipModeZ['livraison_nom_'.$_SESSION['lang']]."]</span>";
        }
		 print "<td align='top' valign='left'><i>".$trot."</i></td>";
       print "</tr>";
   }
                print "<tr>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td align='left' valign='top'><img src='im/zzz_noir.gif' width='100%' height='1'></td>";
                print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
                print "</tr>";
                
                print "<tr>";
                print "<td>&nbsp;</td>";
                print "<td>&nbsp;</td>";
                print "<td>&nbsp;</td>";
                print "<td>&nbsp;</td>";
                print "<td>&nbsp;</td>";
                
                $totalProduitht = sprintf("%0.2f",array_sum($articleHt));
                $totalShipHt = sprintf("%0.2f",array_sum($shipHt)+array_sum($embHt));
                $totalTaxeProduit = sprintf("%0.2f",array_sum($taxePercuProduit));
                $totalTaxeShipping = sprintf("%0.2f",array_sum($taxePercuShipping)+array_sum($embTax));
                $totalRemiseCoupon = sprintf("%0.2f",array_sum($remiseCoupon));
                $totalRemiseFacture = sprintf("%0.2f",array_sum($remiseFacture));
                $totalRemiseP = sprintf("%0.2f",array_sum($remiseP));
                $totalRemiseAll = sprintf("%0.2f",array_sum($remiseAll));
                $totalTTCZ = sprintf("%0.2f",array_sum($venteTTC));
                $totalRemiseFull = sprintf("%0.2f",$totalRemiseP+$totalRemiseCoupon);
                $deeeTaxFinal = sprintf("%0.2f",array_sum($deeeTax));
                $deeeHtFinal = sprintf("%0.2f",array_sum($deeeHt));
                $ccTotal = sprintf("%0.2f",array_sum($cc));
                $affToPay = sprintf("%0.2f",array_sum($aff));

                $total = sprintf("%0.2f",$totalTaxeProduit+$totalTaxeShipping);
                $totalVenteHt = sprintf("%0.2f",$totalProduitht+$totalShipHt);
                $totalVenteTtc = sprintf("%0.2f",$totalVenteHt+$total);
                $VenteTTCRemises = sprintf("%0.2f",$totalVenteTtc - $totalRemiseFull);

                $totalDeee = sprintf("%0.2f",$deeeHtFinal + $deeeTaxFinal);
                $totalDeeeTva = sprintf("%0.2f",$deeeTax);


// Sommes
                print "<td><b>".$totalProduitht."</b></td>";
                print "<td><b>".$totalTaxeProduit."</b></td>";
                print "<td><b>".$totalShipHt."</b></td>";
                print "<td><b>".$totalTaxeShipping."</b></td>";
                
                print "<td><b>".$deeeHtFinal."</b></td>";
                print "<td><b>".$deeeTaxFinal."</b></td>";
                
                print "<td><span style='color:#CC0000'><b>-".$ccTotal."</b></span></td>";
                print "<td><span style='color:#CC0000'><b>-".$totalRemiseFacture."</b></span></td>";
                print "<td><span style='color:#CC0000'><b>-".$totalRemiseP."</b></span></td>";
                print "<td align='center'><span style='color:#CC0000'><b>-".$totalRemiseCoupon."</b></span></td>";
                print "<td><span style='color:#FFFFFF; background:#000000; padding:3px;'><i><b>".$totalTTCZ."</b></i></span></td>";
                print "<td><span style='color:#bcbcbc;'><b>".$affToPay."</b></span></td>";
                print "<td>&nbsp;</td>";
                print "</tr>";
                print "</table>";

//-----------------------------
// AFFICHER TABLEAXU DES TOTAUX
//-----------------------------
/*
                        print "<br>";
                        print "<table align='center' width='400' border='0' cellspacing='0' cellpadding='1' class='TABLE4'><tr>";
                        // Grand totaux
                        print "<td colspan='2' align='center' style='padding:5px; background-color:#CCCCCC'><b>".GTOTAUX."</b></td></tr><tr>";
                        //
                        print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td></tr><tr>";
                        // Total vente HT
                        print "<td align='right'>".A28."&nbsp;:&nbsp;</td><td><b>".$totalVenteHt." ".$symbolDevise."</b></<td></tr><tr>";
                        // Total vente TTC
                        print "<td align='right'>".A29."&nbsp;:&nbsp;</td><td><b>".$totalVenteTtc." ".$symbolDevise."</b></td></tr><tr>";
                        //
                        print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td></tr><tr>";
                        // Remises
                        print "<td align='right'>".A30."&nbsp;:&nbsp;</td><td><b><span class='fontrouge'>- ".$totalRemiseP." ".$symbolDevise."</span></b></td></tr><tr>";
                        // Remises code de réduction
                        print "<td align='right'>".A31."&nbsp;:&nbsp;</td><td><b><span class='fontrouge'>- ".$totalRemiseCoupon." ".$symbolDevise."</span></b></td></tr><tr>";
                        // Remise points de fidélité
                        print "<td align='right'>".A41."&nbsp;:&nbsp;</td><td><b><span class='fontrouge'>- ".$totalRemiseFacture." ".$symbolDevise."</span></b></td></tr><tr>";
                        // Chèques cadeaux
                        print "<td align='right'>".CC."&nbsp;:&nbsp;</td><td><b><span class='fontrouge'>- ".$ccTotal." ".$symbolDevise."</span></b></td></tr><tr>";
                        // details TVA produits
                        if(isset($multipleTax)) {
                                $multipleTaxVar = implode("|",$multipleTax);
                                $multipleTaxVarExp = explode("|",$multipleTaxVar);
                                
                                $split = explode("|",$multipleTaxVar);
                                    foreach ($split as $item) {
                                        $toto = explode(">",$item);
                                        if(!isset($toto[0]) OR $toto[0]=="0.00") $toto[0]=0;
                                        if(!isset($toto[1]) OR $toto[1]=="0.00") $toto[1]=0;
                                        $tax[]=$toto[0];
                                        $montant[]=$toto[1];
                                    }

                                        $exist = array();
                                        $montant2 = array();
                                        
                                        for($uu=0; $uu<=count($tax)-1; $uu++) {
                                            if(in_array($tax[$uu],$exist)) {
                                                $montant2[$tax[$uu]] = $montant2[$tax[$uu]] + $montant[$uu];
                                            }
                                            else {
                                                $exist[] = $tax[$uu];
                                                $montant2[$tax[$uu]] = $montant[$uu];
                                            }
                                        }
                            //
                            print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td></tr><tr>";
                            // TVA produits
                            while (list($key, $val) = each($montant2)) {
                               if($val>0) print "<td align='right'>".A23A." <b><span style='color:#0000FF'>".sprintf("%0.2f",$key)."%</span></b>&nbsp;:&nbsp;</td><td>".sprintf("%0.2f",$val)." ".$symbolDevise."</td></tr><tr>";
                            }
                            // TVA livraison
                            print "<td align='right'>".A24A." <b><span style='color:#0000FF'>".sprintf("%0.2f",$taxe)."%</span></b>&nbsp;:&nbsp;</td><td>".$totalTaxeShipping." ".$symbolDevise."</td></tr><tr>";
                            // Total TVA perçu
                            print "<td align='right'>".A27."&nbsp;:&nbsp;</td><td><b><span class='fontrouge'>".$total." ".$symbolDevise."</span></b></td></tr><tr>";
                            //
                            print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td></tr><tr style='color:#00CC00'>";
                            // Total EcoTax TTC
                            print "<td align='right'>Totaal Eco&nbsp;:&nbsp;</td><td>".$totalDeee." ".$symbolDevise."</td></tr><tr style='color:#00CC00'>";
                            // Total EcoTax HT
                            print "<td align='right'>Totaal Eco&nbsp;:&nbsp;</td><td>".$deeeHtFinal." ".$symbolDevise."</td></tr><tr style='color:#00CC00'>";
                            // EcoTax TVA
                            print "<td align='right'>".A24AA."&nbsp;:&nbsp;</td><td>".$deeeTaxFinal." ".$symbolDevise."</td></tr><tr>";
                            //
                            print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td></tr><tr style='color:#bcbcbc'>";
                            // Affiliation (À payer)
                            print "<td align='right'>".AFF2."&nbsp;:&nbsp;</td><td>".$affToPay." ".$symbolDevise."</td></tr><tr>";
                            //
                            print "<td colspan='2'><img src='im/zzz.gif' width='1' height='3'></td>";
                        }
                        print "</tr></table>";
                        // FIN TABLEAUX DES TOTAUX
                if(!isset($_GET['nico'])) print "<p align='center'>* ".A34."</p>"; else print "<br>";
*/
                //-------------------------------
                // AFFICHER TABLEAU EXPORTER CSV
                //-------------------------------
                print '<br><table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE">';
                print '<tr><td align="center" bgcolor="#FFFFCC">';
                print '<a href="export_vente.php?gg1='.$dateAdded1.'&gg2='.$dateAdded2.$exportLink.'"><b>'.A39.' 1 (CSV)</b></a><br>';
                print '<a href="x_exporteer_bestelling_alles.php?gg1='.$dateAdded1.'&gg2='.$dateAdded2.$exportLink.'"><b>'.A39.' 2 (CSV)</b></a><br>';
                if(!isset($_GET['nico'])) print '<i>'.A40.'</i>';
                print '</td></tr>';
                print '</table>';
                
}
else {
      print "<p align='center'>";
      print A15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
      print "&nbsp;";
      print A16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
      print "</p>";
      print "<div align='center' class='fontrouge'><b>".A35."</b></div>";
}

}
else {
      print "<p align='center'>";
      print A15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
      print "&nbsp;";
      print A16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
      print "</p>";
      print "<div align='center' class='fontrouge'><b>".A36."</b></div>";
}
}
else {
      print "<p align='center'>";
      print A15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
      print "&nbsp;";
      print A16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
      print "</p>";
      print "<div align='center' class='fontrouge'><b>".A37."</b></div>";
}
}

?>
</body>
</html>
