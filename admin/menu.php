<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

// Toegangs controle
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
$hoy = date("d M Y");
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));

// Formaat datum
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

// functie teller dagen
function nbJours($orderAdded) {
	$date1 = date("Y-m-d H:i:s");
	$date2 = $orderAdded;
	$nbjours = abs(floor((strtotime($date1) - strtotime($date2))/(86400)));
	if($nbjours>0) {
		$jour = ($nbjours>1)? " ".B1002."en" : " ".B1002;
	}
	else {
		$nbjours = A3;
		$jour = "";
	}
	return array($nbjours, $jour);
}
	
// landen
if(isset($_POST['action']) AND $_POST['action']=="exclure") {
        foreach($_POST['exclureId'] AS $key => $value) {
             mysql_query("UPDATE countries SET countries_shipping = 'exclude' WHERE countries_id = '".$value."'");
        }
        // bericht
        if(isset($_POST['exclureName'])) {
            if(count($_POST['exclureName'])>1) {
                $countriesListMessage = LES_PAYS_SUIVANTS.":<br>";
                $pl=2;
            }
            else {
                $countriesListMessage = LE_PAYS_SUIVANT.": ";
                $pl=1;
            }
            foreach($_POST['exclureName'] AS $countriesList) {
                $countriesListMessage.= ($pl>1)? "- ".$countriesList."<br>" : $countriesList;
            }
        }
}


// gegevens
if(!isset($_POST['qty'])) $_POST['qty'] = $seuilStock;
$hids = mysql_query("SELECT p.products_id, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_qt, p.fournisseurs_id, f.fournisseurs_company, f.fournisseurs_id, o.*
                     FROM fournisseurs as f
                     LEFT JOIN products as p
                     ON(p.fournisseurs_id = f.fournisseurs_id)
                     LEFT JOIN products_options_stock AS o
                     ON(p.products_id = o.products_options_stock_prod_id)
                     WHERE p.products_qt <= ".$_POST['qty']." OR o.products_options_stock_stock <= ".$_POST['qty']."
                     AND products_options_stock_active='yes'
                     ORDER BY o.products_options_stock_stock ASC, p.products_qt ASC
                     ");

$query2 = mysql_query("SELECT *
                      FROM users_orders
                      WHERE users_statut = 'no'
                      AND users_payed = 'yes'
                      AND users_nic NOT LIKE 'TERUG%'
                      AND users_nic NOT LIKE 'REFUNDED'
                      AND users_refund = 'no'
                      ORDER BY users_date_added
                      ASC
                      ");

$query5 = mysql_query("SELECT *
                      FROM users_orders
                      WHERE users_litige = 'yes'
                      ORDER BY users_date_added
                      ASC
                      ");

$query500 = mysql_query("SELECT *
                      FROM users_orders
                      WHERE users_statut = 'yes'
                      AND users_payed = 'no'
                      AND users_nic NOT LIKE 'TERUG%'
                      AND users_nic NOT LIKE 'REFUNDED'
                      AND users_refund = 'no'
                      ORDER BY users_date_payed
                      ASC
                      ");
// NB artikelen
$nbQuery = mysql_query("SELECT products_id FROM products");
$nb = mysql_num_rows($nbQuery);
?>
<html>
<head>
<title>Admin</title>
<link rel='stylesheet' href='style.css'>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center"><span style='font-size:13px'><b><?php print A2." ".$hoy;?></b></span></p>

<?php
if(isset($countriesListMessage)) print "<table border='0' width='700' align='center' cellpadding='8' cellspacing='0' class='TABLE'><tr><td align='center'><font color=red>
<b>".$countriesListMessage."</b></td></td></table><br>";
include("lang/lang".$_SESSION['lang']."/geen_script.php");
include("geen_script.php");

//////////////////////////////////////
// bestellingen betaald, niet verzonden
//////////////////////////////////////

if(mysql_num_rows($query2)>0) {
    print '<table align="center" border="0" cellpadding="8" cellspacing="0" class="TABLE" width="95%">';
    print '<tr bgcolor="#CCFFCC">';
    print '<td height="35" colspan="11" align="center" style="font-size:12px"><b>'.A12.'</b></td>';
    print '</tr>';
    print '<tr bgcolor="#FFFFFF">';
    print '<td align="center"><b>ID</b></td>';
    print '<td align="center"><img src="im/payed.gif" title="'.A13.'"></td>';
    print '<td align="center"><img src="im/ship.gif" title="'.A14.'"></td>';
    print '<td align="center"><b>'.PAYE_LE.'</b></td>';
    print '<td align="center"><b>NIC</b></td>';
    print '<td align="center"><b>Totaal</b></td>';
    print '<td align="center"><b>'.FACTURE.'</b></td>';
    print '<td align="center">Betaling</td>';
    print '<td align="center"><b>'.LITIGE.'</b></td>';
    print '<td align="left"><b>'.MODE_DE_LIVRAISON.'</b></td>';
    print '<td align="center"><b>'.READY.'</b></td>';
    print '</tr>';

    $c2="";
    while ($row2 = mysql_fetch_array($query2)) {
        if($c2=="#F1F1F1") $c2 = "#E8E8E8"; else $c2 = "#F1F1F1"; 
        $checkCom[] = $row2['users_nic'];
        $_payed = ($row2['users_payed']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        $_exp = ($row2['users_statut']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        $readyImage = ($row2['users_ready']=='yes')? "<img src='im/noPassed.gif' title='".B14."'>" : "<img src='im/passed.gif' title='".B15."'>";
        
        if($row2['users_litige']=="yes") {
			$c2 = "#FF9900";
			$c3 = "#FF0000";
		}
		else {
			$c3 = "#FFFFAA";
		}
        print "<tr bgcolor='".$c2."' onmouseover=\"this.style.backgroundColor='".$c3."';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td align='left'>".$row2['users_id']."</td>";

        print "<td align='left'><b><span style='color:red'>".$_payed."</span></b></td>";
 
        print "<td align='left'><b><span style='color:red'>".$_exp."</span></b></td>";
  
        print "<td align='left'>".dateFr($row2['users_date_payed'], $_SESSION['lang'])."</td>";
   
        print "<td align='left'><a href='detail.php?id=".$row2['users_id']."&from=todo'>".$row2['users_nic']."</a></td>";
    
        print "<td align='left'><b>".$row2['users_total_to_pay'].$symbolDevise."</b></td>";
     
        print "<td align='center'><a href='factuur_scherm.php?id=".$row2['users_id']."&target=impression' target='_blank'>".str_replace("||","",$row2['users_fact_num'])."</a></td>"; 
        if($row2['users_payment'] == "pn")
        {
          print "<td align='center'>Pay.nl</td>";
        }
        else
        {
          print "<td align='center'>".strtoupper($row2['users_payment'])."</td>";
        }
        
        print "<td align='center'>";
        print ($row2['users_litige']=="yes")? "<span class='fontrouge'><b>".B14."</b></span>" : B15;
        print "</td>";
        
        $requestLivZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$row2['users_shipping']."'");
        $resultLivZ = mysql_fetch_array($requestLivZ);
        print "<td align='left'>".$resultLivZ['livraison_nom_'.$_SESSION['lang']]."</td>";
         
        print "<td align='center'>".$readyImage."</td>";
        print "</tr>";
    }
    print "</tr>";
    $dateAdded1 = "2000-01-01 00:00:00";
    $dateAdded2 = date("Y-m-d 00:00:00");
    print "<td bgcolor='#FFFFFF' align='center' colspan='11'><a href='artikelen_verzenden.php?de=1&yo=eset&where=print&date1=".$dateAdded1."&date2=".$dateAdded2."&search=none'>".A130."</a></td></tr>";
    print "</table>";
}
else {
    print '<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700">';
    print '<tr>';
    print '<td colspan="9"><center>'.A3.': '.$hoy.' - <b>'.A12.'</b></td>';
    print '</tr>';
    print '<tr>';
    print '<td align="center" colspan="9">';
    print '<b><span class="fontrouge">'.A18.'</span></b> </td>';
    print '</tr>';
    print '</table>';
}

/////////////////////////
// Verstuurd niet betaald
/////////////////////////

if(mysql_num_rows($query500)>0) {
	print "<br><br>";
    print '<table align="center" border="0" cellpadding="8" cellspacing="0" class="TABLE">';
    print '<tr bgcolor="#CCFFCC"><td height="35" colspan="12" align="center" style="font-size:12px">';
    print '<b>'.COMMANDES_EXPEDIEES_NON_PAYEES.'</b></td>';
    print '</tr>';
    print '<tr bgcolor="#FFFFFF">';
    print '<td align="center"><b>ID</b></td>';
    print '<td align="center"><img src="im/payed.gif" title="'.A13.'"></td>';
    print '<td align="center"><img src="im/ship.gif" title="'.A14.'"></td>';
    print '<td align="center"><b>'.SHIPPED_ON.'</b></td>';
    print '<td align="center"><b>NIC</b></td>';
    print '<td align="center"><b>Totaal</b></td>';
    print '<td align="center"><b>'.FACTURE.'</b></td>';
    print '<td align="center"><b>'.N_CLIENT.'</b></td>';
    print '<td align="center"><b>'.PAYABLE.'</b></td>';
    print '<td align="center"><b>'.LITIGE.'</b></td>';
    print '<td align="left"><b>'.MODE_DE_LIVRAISON.'</b></td>';
    print '<td align="center"><b>'.NOTE_INTERNE.'</b></td>';
    print '</tr>';
    $c2="";
    while ($row2 = mysql_fetch_array($query500)) {
        if($c2=="#F1F1F1") $c2 = "#E8E8E8"; else $c2 = "#F1F1F1"; 
        $checkCom[] = $row2['users_nic'];
        $_payed = ($row2['users_payed']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        $_exp = ($row2['users_statut']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        $nbreDeJours = nbJours($row2['users_date_payed']);
        $ilYa =  "".$nbreDeJours[0]." ".$nbreDeJours[1]."";
        $ilYa = preg_replace("# ]$#","]",$ilYa);

		$hidsss = mysql_query("SELECT users_pro_payable FROM users_pro WHERE users_pro_password = '".$row2['users_password']."'");
		$myhidss = mysql_fetch_array($hidsss);
		if($myhidss['users_pro_payable']==0) $payable = CASH;
		if($myhidss['users_pro_payable']==30) $payable = _30_DAYS;
		if($myhidss['users_pro_payable']==60) $payable = _60_DAYS;
		if($myhidss['users_pro_payable']==90) $payable = _90_DAYS;
        
        if($row2['users_litige']=="yes") $c2="#FF9900";
        print "<tr bgcolor='".$c2."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td align='left'>".$row2['users_id']."</td>";

        print "<td align='left'><b><span style='color:red'>".$_payed."</span></b></td>";
 
        print "<td align='left'><b><span style='color:red'>".$_exp."</span></b></td>";
  
        print "<td align='left'>".dateFr($row2['users_date_payed'], $_SESSION['lang'])."<br><span style='color:#FF0000'>".$ilYa."</span></td>";
   
        print "<td align='left'><a href='detail.php?id=".$row2['users_id']."&from=todo'>".$row2['users_nic']."</a></td>";
    
        print "<td align='left'><b>".$row2['users_total_to_pay'].$symbolDevise."</b></td>";
     
        print "<td align='center'><a href='factuur_scherm.php?id=".$row2['users_id']."&target=impression' target='_blank'>".str_replace("||","",$row2['users_fact_num'])."</a></td>"; 
      
        print "<td align='left'><a href='klant_fiche.php?action=view&id=".$row2['users_password']."'>".$row2['users_password']."</a></td>";
       
        print "<td align='left'><span style='background:#ffff00; padding:1px; border:#FFFFFF 1px solid;'>".$payable."</span></td>";
        
        print "<td align='center'>";
        print ($row2['users_litige']=="yes")? "<span class='fontrouge'><b>".B14."</b></span>" : B15;
        print "</td>";
         
        $requestLivZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$row2['users_shipping']."'");
        $resultLivZ = mysql_fetch_array($requestLivZ);
        print "<td align='left'>".$resultLivZ['livraison_nom_'.$_SESSION['lang']]."</td>";
         
        print "<td align='center'>";
        $openLeg230ss = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=note&id=".$row2['users_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
        print ($row2['users_note']!=="")? "<a ".$openLeg230ss." href='' title='".$row2['users_note']."'>".VOIR_NOTE."</a>" : "--";
        print "</td>";
        print "</tr>";
    }
    print "</table>";
}

//////////////////////////
// bestellling met klacht
//////////////////////////

if(mysql_num_rows($query5)>0) {
    print "<br><br>";
    print '<table align="center" border="0" cellpadding="8" cellspacing="0" class="TABLE" width="95%">';
    print '<tr bgcolor="#FF9900"><td height="35" colspan="10" align="center" style="font-size:12px">';
    print '<b>'.COMMANDE_EN_LITIGE.'</b></td>';
    print '</tr>';
    print '<tr bgcolor="#FFFFFF">';
    print '<td align="left"><b>ID</b></td>';
    print '<td align="left"><img src="im/payed.gif" title="'.A13.'"></td>';
    print '<td align="left"><img src="im/ship.gif" title="'.A14.'"></td>';
    print '<td align="left"><b>Datum</b></td>';
    print '<td align="left"><b>NIC</b></td>';
    print '<td align="left"><b>Totaal</b></td>';
    print '<td align="left"><b>Betaalwijze</b></td>';
	print '</tr>';
    $c2="";
    while ($row5 = mysql_fetch_array($query5)) {
        if($c2=="#F1F1F1") $c2 = "#E8E8E8"; else $c2 = "#F1F1F1"; 
        $checkCom[] = $row5['users_nic'];
        $_payed2 = ($row5['users_payed']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        $_exp2 = ($row5['users_statut']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
        
        print "<tr bgcolor='".$c2."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td align='left'>".$row5['users_id']."</td>";
        print "<td align='left'><b><span style='color:red'>".$_payed2."</span></b></td>";
        print "<td align='left'><b><span style='color:red'>".$_exp2."</span></b></td>";
        print "<td align='left'>".dateFr($row5['users_date_added'], $_SESSION['lang'])."</td>";
        print "<td align='left'><a href='detail.php?id=".$row5['users_id']."&from=todo'>".$row5['users_nic']."</a></td>";
        print "<td align='left'><b>".$row5['users_total_to_pay'].$symbolDevise."</b></td>"; 


        if($row2['users_payment'] == "pn")
        {
          print "<td align='left'>Pay.nl</td>";
        }
        else
        {
          print "<td align='left'>".strtoupper($row5['users_payment'])."</td>";
        }


		print "</tr>";
    }
    print "</table>";
}

///////////
// Stock //
///////////
if(mysql_num_rows($hids)>0) {
    print "<br><br>";
    print "<table border='0'cellpadding='5' cellspacing='0' align='center' class='TABLE' width='95%'>";
    print "<tr>";
    print "<td colspan='6' align='center' style='font-size:12px'><b>".A4."</b></td>";
    print "</tr>";
    print "<tr>";
    print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
    print "<td colspan='4'>Voorraad&nbsp;&nbsp;";
    print "<input type='text' size='3' class='vullen' name='qty' value='".$_POST['qty']."'>";
    print "&nbsp;<input type='submit' class='knop' value='".A5."'>";
    print "</td>";
    print "<td colspan='2' align='right'>";
    print A6."en: <b>".$nb."</b>&nbsp;&nbsp;";
    print "<a href='voorraad_beheer.php'>".UPDATE_STOCK."</a>";
    print "</td></td></table><br>";
    print "</form>";
    print "<table border='0'cellpadding='5' cellspacing='0' align='center' class='TABLE' width='95%'>";
    print "<tr bgcolor='#FFFFFF'>";
    	print "<td align='center'><b>#</b></td>";
        print "<td align='left'><b>".A6."</b></td>";
        print "<td align='left'><b>Referentie</b></td>";
        print "<td align='left'><b>Voorraad</b></td>";
        print "<td align='left'><b>".A7."</b></td>";
        print "<td align='center'><b>".A8."</b></td>";
    print "</tr>";
      $c="";
      $i=1;
      while ($myhid = mysql_fetch_array($hids)) {
                      if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
                      $stock = (!empty($myhid['products_options_stock_stock']) OR $myhid['products_options_stock_stock']=="0")? $myhid['products_options_stock_stock'] : $myhid['products_qt'];
                      if($stock == 0) {
                         $comFour = "<b><span style='color:red'>".$stock."</b></span>";
                         $note = "<b>".A9."</b>";
                      }
                      if($stock < 0) {
                         $comFour = "<b><span style='color:red'>".$stock."</span></b>";
                         $note = "<b><span style='color:red'>".A10."</span></b>";
                      }
                       if($stock > 0) {
                          $comFour = $stock;
                          $note = "";
                       }
                       if($stock > 0 AND $stock <= $seuilStock) {
                          $comFour = "<b>".$stock."</b>";
                          $note = A11;
                       }
                    print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
                    	 print "<td align='center'>".$i++.".</td>";
                         print "<td align='left'>";
						 print "<a href='artikel_wijzigen_details.php?id=".$myhid['products_id']."'>".$myhid['products_name_'.$_SESSION['lang']]."</a>";
						 if(!empty($myhid['products_options_stock_prod_name'])) print "<div>&bull;&nbsp;<a href='opties_voorraad.php?id=".$myhid['products_id']."&del=tab_values'>".$myhid['products_options_stock_prod_name']."</a></div>";
						 print "</td>";
                         print "<td align='left'>";
						 print (!empty($myhid['products_options_stock_ref']))? $myhid['products_options_stock_ref'] : $myhid['products_ref'];
						 print "</td>";
                         print "<td align='left'>";
						 print $comFour;
						 print "</td>";
                         print "<td align='left'>";
						 print "<a href='fab_lev_wijzigen2.php?id1=".$myhid['fournisseurs_id']."&id2=none'>".$myhid['fournisseurs_company']."</a>";
						 print "</td>";
                         print "<td align='center'>";
						 print $note;
						 print "</td>";
                    print "</tr>";
    }
    print "</table>";
}

/////////////
// Affiliate
////////////

// function add com
function return_total_com($affUser) {
  global $affiliateTop;
	$queryBalance = mysql_query("SELECT *
                                FROM users_orders
                                WHERE users_affiliate = '".$affUser."'
                                AND users_affiliate_payed = 'no'
                                AND users_confirm = 'yes'
                                AND users_payed = 'yes'
                                AND users_nic NOT LIKE 'TERUG%'
                                ORDER BY users_date_added
                                ASC");
    $queryBalanceNum = mysql_num_rows($queryBalance);
    if($queryBalanceNum > 0) {
      	while ($yoyo = mysql_fetch_array($queryBalance)) {
      	$totalComBalance[] = $yoyo['users_affiliate_amount'];
      	}
      	$totalCom = array_sum($totalComBalance);
      	if($totalCom >= $affiliateTop) {
      	   return array(sprintf("%0.2f",$totalCom),sprintf("%0.2f",$totalCom));
      	}
      	else {
           return array(sprintf("%0.2f",$totalCom),sprintf("%0.2f",$totalCom));
        }
    }
    else {
        return sprintf("%0.2f",0);
    }
}
// end function

$hids = mysql_query("SELECT * FROM affiliation ORDER BY aff_id");
$resultcsNum = mysql_num_rows($hids);
if($resultcsNum > 0) {

	while ($myhid3 = mysql_fetch_array($hids)) {
        $totalToPay = return_total_com($myhid3['aff_number']);
        if($totalToPay[1] < $affiliateTop) {$opt[] = 0;} else {$opt[] = 1;}
	}
	$sumOpt = array_sum($opt);

	if($sumOpt > 0) {
        $hids = mysql_query("SELECT * FROM affiliation ORDER BY aff_id");
        print "<br><br>";
        print "<table align='center' border='0' cellpadding='8' cellspacing='0' class='TABLE' width='95%'>";
            print "<tr bgcolor='#CCFFCC'>";
            print "<td height='35' colspan='10' align='center' style='font-size:12px'><b>".A121."</b></td>";
            print "</tr>";
            print "<tr bgcolor='#FFFFFF'>";
            print "<td align='left'><b>".A100."</b></td>";
            print "<td align='left'><b>Commissie</b></td>";
            print "<td align='left'><b>".A120."</b></td>";
            print "</tr>";
      	while ($myhid = mysql_fetch_array($hids)) {
            $totalToPay = return_total_com($myhid['aff_number']);
            if($totalToPay[1] < $affiliateTop) {$op = "OK";} else {$op = "<span class='fontrouge'><b>".A122."</b></span>";}
                if($op !== "OK") {
                    if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
                    print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
                    print "<td align='left'><a href='affiliate.php?action=view&id=".$myhid['aff_number']."'>".$myhid['aff_number']."</a></td>";
                    print "<td align='left'>".$totalToPay[0].$symbolDevise."</td>";
                    print "<td align='left'>".$op."</td>";
                    print "</tr>";
                }
      	}
      	print "</table>";
  }
}
///////////
// offertes
///////////

$hids = mysql_query("SELECT * FROM devis WHERE devis_sent = 'no' ORDER BY devis_date_added DESC");
$resultcsNum = mysql_num_rows($hids);
if($resultcsNum > 0) {
	print "<br><br>";
	print "<table border='0' cellpadding='8' cellspacing='0' align='center' class='TABLE' width='95%'>";
	print "<tr bgcolor='#CCFFCC'>";
    print "<td height='35' colspan='10' align='center' style='font-size:12px'><b>".B1."</b></td>";
    print "</tr>";
    print "<tr bgcolor='#FFFFFF'>";
        print "<td align='center'><b>".B100."</b></td>";
        print "<td align='center'><b>".B150."</b></td>";
        print "<td align='center'><b>".B40."</b></td>";
        print "<td align='left'><b>".B11."</b></td>";

        print "<td align='left'><b>".B11B."</b></td>";
        print "<td align='left'><b>".B10."</b></td>";
        print "<td align='center'><b>".B5."</b></td>";
	print "</tr>";
	$c="";
	while ($myhid = mysql_fetch_array($hids)) {
        if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
        $dateFrom = explode(" ",$myhid['devis_date_added']);
        $yoyoyo = "<a href='offerte_details.php?id=".$myhid['devis_number']."'>".$myhid['devis_number']."</a>";
        $compagnie = (empty($myhid['devis_company']))? "--" : $myhid['devis_company'];
        $client = (empty($myhid['devis_client']))? "--" : "<a href='mijnklant.php?id=".$myhid['devis_client']."'>".$myhid['devis_client']."</a>";
 
        $bouton = ($myhid['devis_sent']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
  
        $traite = ($myhid['devis_traite']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
  
        print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
          print "<td align='center'>".$bouton."</td>";
          print "<td align='center'>".$traite."</td>";
          print "<td align='center'>".$yoyoyo."</td>";
          print "<td align='left'>".$myhid['devis_firstname']." ".$myhid['devis_lastname']."</td>";

          print "<td align='left'>".$compagnie."</td>";
          print "<td align='left'>".$client."</td>";
          print "<td align='center'>".dateFr($dateFrom[0], $_SESSION['lang'])."</td>";
        print "</tr>";
	}
	print "</table>";
	print "<br>";
}

////////////////////////
// offertes niet betaald
////////////////////////

$hids = mysql_query("SELECT *, TO_DAYS(NOW()) - TO_DAYS(devis_date_end) as diff FROM devis WHERE devis_sent='yes' AND devis_traite='no' ORDER BY devis_date_sent DESC");
$resultcsNum = mysql_num_rows($hids);
if($resultcsNum > 0) {
	print "<br><br>";
	print "<table border='0' cellpadding='8' cellspacing='0' align='center' class='TABLE' width='95%'>";
	print "<tr bgcolor='#CCFFCC'>";
    print "<td height='35' colspan='10' align='center' style='font-size:12px'><b>".DEVIS_ENVOYES_NON_PAYES."</b></td>";
    print "</tr>";
    print "<tr bgcolor='#FFFFFF'>";
        print "<td align='center'><b>".B100."</b></td>";
        print "<td align='center'><b>".B150."</b></td>";
        print "<td align='center'><b>".B40."</b></td>";
        print "<td align='left'><b>".B11."</b></td>";
        print "<td align='left'><b>".B11A."</b></td>";
        print "<td align='left'><b>".B11B."</b></td>";
        print "<td align='left'><b>".B10."</b></td>";
        print "<td align='left'><b>".ENVOYE_LE."</b></td>";
        print "<td align='center'><b>".EXPIRATION."</b></td>";
        print "<td align='center'><b>".A120."</b></td>";
	print "</tr>";
	$c="";
	while ($myhid = mysql_fetch_array($hids)) {
        if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
        $dateSent = explode(" ",$myhid['devis_date_sent']);
        if($myhid['devis_date_end'] !== "0000-00-00 00:00:00") {
            if($myhid['diff'] > 0) {
                $statut = "<img src='im/passed.gif' title='".EXPIRE."'>";
                $dans = "--";
            }
            if($myhid['diff'] < 0) {
                $statut = "<img src='im/noPassed.gif' title='".EN_COURS."'>";
                $pl = (abs($myhid['diff']) > 1)? B1002."s" : B1002;
                $dans = "<b>".B1001." ".abs($myhid['diff'])." ".$pl."</b>";			
            }
            if($myhid['diff'] == 0) {
                $statut = "<span style='color:#CC0000'><b>--</b></span>";
                $dans = "<span style='color:#CC0000'><b>".A3."</b></span>";
            }
        }
        else {
            $statut = "--";
            $dans = "--";                
        }

        $yoyoyo = "<a href='offerte_details.php?id=".$myhid['devis_number']."'>".$myhid['devis_number']."</a>";
        $compagnie = (empty($myhid['devis_company']))? "--" : $myhid['devis_company'];
        $client = (empty($myhid['devis_client']))? "--" : "<a href='mijnklant.php?id=".$myhid['devis_client']."'>".$myhid['devis_client']."</a>";
   
        $bouton = ($myhid['devis_sent']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
    
        $traite = ($myhid['devis_traite']=="yes")? "<img src='im/val.gif' title='".B14."'>" : "<img src='im/no_stock.gif' title='".B15."'>";
  
        print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
          print "<td align='center'>".$bouton."</td>";
          print "<td align='center'>".$traite."</td>";
          print "<td align='center'>".$yoyoyo."</td>";
          print "<td align='left'>".$myhid['devis_lastname']."</td>";
          print "<td align='left'>".$myhid['devis_firstname']."</td>";
          print "<td align='left'>".$compagnie."</td>";
          print "<td align='left'>".$client."</td>";
          print "<td align='center'>".dateFr($dateSent[0], $_SESSION['lang'])."</td>";
          print "<td align='center'>".$dans."</td>";
          print "<td align='center'>".$statut."</td>";
        print "</tr>";
	}
	print "</table>";
	print "<br>";
}

///////////////
// BTW controle
///////////////

if($tvaManuelValidation=="oui") {
    $tvaQuery = mysql_query("SELECT * FROM users_pro WHERE users_pro_tva != '' AND users_pro_tva_confirm = '??' ORDER BY users_pro_id") or die (mysql_error());
    $tvaQueryNum = mysql_num_rows($tvaQuery);
    if($tvaQueryNum > 0) {
        print "<br><br>";
    	print "<table border='0' cellpadding='8' cellspacing='0' align='center' class='TABLE' width='700'>";
    	print "<tr bgcolor='#7AC52D'>";
        print "<td height='35' colspan='10' align='center' style='font-size:12px'><b>".TVA_INTRACOM_A_VALIDER."</b></td>";
        print "</tr>";
        print "<tr bgcolor='#FFFFFF'>";
        print "<td align='center'><b>".COMPTE_CLIENT."</b></td>";
        print "<td align='center'><b>".TVA_INTRACOM."</b></td>";
        print "<td align='center'><b>".VALIDE."</b></td>";
        print "</tr>";
        $c="";
    	while ($tvaResult = mysql_fetch_array($tvaQuery)) {
            if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
            print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
            print "<td align='center'><a href='klant_fiche.php?action=view&id=".$tvaResult['users_pro_password']."'>".$tvaResult['users_pro_password']."</a></td>";
            print "<td align='center'>".$tvaResult['users_pro_tva']."</td>";
            print "<td align='center' style='color:#FF0000'><b>".B15."</b></td>";
            print "</tr>";
    	}
    	print "</table>";
    	print "<br>";
    }
}

/////////////////////////
// landen zonder levering
/////////////////////////

$contriesQuery = mysql_query("SELECT countries_id FROM countries WHERE countries_shipping != 'exclude'") or die (mysql_error());
$contriesQueryNum = mysql_num_rows($contriesQuery);
$livQuery = mysql_query("SELECT livraison_country FROM ship_mode WHERE livraison_country != '' AND livraison_active='yes'") or die (mysql_error());
$livQueryNum = mysql_num_rows($livQuery);

if($contriesQueryNum > 0 AND $livQueryNum > 0) {
    while ($contriesResult = mysql_fetch_array($contriesQuery)) {
        $countries[] = $contriesResult['countries_id'];
    }
    while ($livResult = mysql_fetch_array($livQuery)) {
        $liv[] = $livResult['livraison_country'];
    }
    if(count($liv) >0) {
        $liv = implode('|', $liv);
        $liv = str_replace('|||','|',$liv);
        $liv = str_replace('||','|',$liv);
        $livArray = explode('|', $liv);
        // remove empty value
        foreach($livArray as $key => $value) {
            if($value == "") unset($livArray[$key]);
        }
    }
    if(count($countries) > 0 AND count($livArray) > 0) {
        foreach($countries AS $pays) {
            if(!in_array($pays, $livArray)) $countriesNo[] = $pays;
        }
    }

    if(isset($countriesNo) AND count($countriesNo) >0) {
        foreach($countriesNo AS $paysId) {
            $contriesIdQuery = mysql_query("SELECT countries_name, countries_id FROM countries WHERE countries_id='".$paysId."'") or die (mysql_error());
            $contriesIdResult = mysql_fetch_array($contriesIdQuery);
            $contriesName[] = $contriesIdResult['countries_name']."|".$contriesIdResult['countries_id'];
        }
    }
    //-------------------------------------
    // landen zonder verzend firma
    //-------------------------------------
    if(isset($contriesName) AND count($contriesName) > 0) {
        print "<br>";
        
        print "<table border='0' align='center' cellpadding='0' cellspacing='0' width='100%'><tr>";
      	print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
      	print "<input type='hidden' name='action' value='exclure'>";
      	print "<td>";
        
      	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='95%'>";
      	print "<tr bgcolor='#CCFFCC'>";
      	
          print "<td height='35' colspan='10' align='center' style=padding:10px;'>";
          print "<div align='center' style='font-size:12px'><b>".PAYS_ACTIFS."</b><div>";
          print "<div align='center'>(".EXCLURE_PAYS.")</div>";
          print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";          
          print "</td>";
          
          print "</tr>";
          print "<tr bgcolor='#FFFFFF'>";
          print "<td align='left'><b>".PAYS."</b></td>";
          print "<td align='center'><b>".EXCLURE."</b></td>";
          print "<td align='center'><b>".SELECTIONNER_MODE_BR."</b></td>";
          print "</tr>";
          $c="";
      	foreach($contriesName AS $contriesNameDisplay) {
      	      $explodeContryResult = explode("|", $contriesNameDisplay);
              if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
              print "<input type='hidden' name='exclureId[]' value='".$explodeContryResult[1]."'>";
              print "<input type='hidden' name='exclureName[]' value='".$explodeContryResult[0]."'>";
              
              print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
              print "<td align='left'>".$explodeContryResult[0]."</td>";
              print "<td align='center'><a href='verzendzone.php?pays=".$explodeContryResult[0]."' style='background:none'><img src='im/exclu.gif' border='0' title='".EXCLURE."'></a></td>";
              print "<td align='center'><a href='verzendfirma_wijzigen.php?pays=".$explodeContryResult[0]."' style='background:none'><img src='im/ajouter.gif' border='0' title='".SELECTIONNER_MODE."'></a></td>";
              print "</tr>";
      	}
      	print "</table>";
      	
      	print "<br><div align='center'><input type='submit' class='knop' value='".EXCLURE_TOUS_PAYS."'></div>";
      	print "</td>";
      	print "</form>";
      	print "<tr></table>";
      	print "<br><br><br>";
    }
}
?>
</BODY>
</HTML>
