<?PHP
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

// Toegangs controle
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='../admin/style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');





// phpinfo();

$hoy = date("d M Y");
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
DEFINE("A2","Vandaag is het"); 
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



<?PHP
DEFINE("A12","Wachtende bestellingen - betaald maar niet verzonden");
DEFINE("A13","Betaald");
DEFINE("A14","Compleet");
DEFINE ("M1","Voornaam");
DEFINE ("M2","Achternaam");
DEFINE("LITIGE","Klacht");
DEFINE("FACTURE","Factuur nr");
DEFINE("PAYE_LE","Betaald op");
DEFINE("MODE_DE_LIVRAISON","Verzend firma");
DEFINE("B14","Ja");
DEFINE("B15","Neen");
DEFINE("READY","Klaar om te verzenden");
DEFINE("A3","Vandaag");
DEFINE("A18","Alle bestellingen zijn compleet");
DEFINE("A130","<b>Te verzenden artikelen</b>");
DEFINE("A32","Update");


// Formaat datum
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

// omdat we voor updates dit script zelf terug oproepen, gaan we eerst kijken of dit zo'n 'tweede' oproep is 

 if(isset($_POST['action']) and $_POST['action']=="updateFactNum") {
  $update = mysql_query("UPDATE users_orders SET users_fact_num = '".$_POST['fact_num']."' WHERE users_nic = '".$_POST['nic']."' and users_id = '".$_POST['id'] ."'");
	 header("Location: ".$_SERVER['PHP_SELF']	); 

//       $upd ="UPDATE users_orders SET users_fact_num = '".$_POST['fact_num']."' WHERE users_nic = '".$_POST['nic']."' and users_id = '".$_POST['id'] ."'" ;
// echo $upd . "<HR>";

	 
   //  header("Location: ".$_SERVER['PHP_SELF']."?id=".$_POST['id']."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1'] ."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."");
// 
}


$query2 = mysql_query("SELECT *
                      FROM users_orders
                      WHERE 1 
                      AND users_payed = 'yes'
                      AND users_nic NOT LIKE 'TERUG%'
                      AND users_nic NOT LIKE 'REFUNDED'
                      AND users_refund = 'no'
                      ORDER BY users_date_added
                      DESC
                      ");

/* $query2 = mysql_query("SELECT *
                      FROM users_orders
                      WHERE users_statut = 'no'
                      AND users_payed = 'yes'
                      AND users_nic NOT LIKE 'TERUG%'
                      AND users_nic NOT LIKE 'REFUNDED'
                      AND users_refund = 'no'
                      ORDER BY users_date_added
                      ASC
                      ");
*/


if(mysql_num_rows($query2)>0) {
    print '<table align="center" border="0" cellpadding="8" cellspacing="0" class="TABLE" width="95%">';
    print '<tr bgcolor="#CCFFCC">';
    print '<td height="35" colspan="11" align="center" style="font-size:12px"><b>'.A12.'</b></td>';
    print '</tr>' . PHP_EOL;
    print '<tr bgcolor="#FFFFFF">';
    print '<td align="center"><b>ID</b></td>';
	print '<td align="center"><b>' .M1. '</b></td>';
	print '<td align="center"><b>' .M2. '</b></td>';
    print '<td align="center"><img src="../admin/im/payed.gif" title="'.A13.'"></td>';
    print '<td align="center"><img src="../admin/im/ship.gif" title="'.A14.'"></td>';
    print '<td align="center"><b>'.PAYE_LE.'</b></td>';
    print '<td align="center"><b>NIC</b></td>';
    print '<td align="center"><b>Totaal</b></td>';
    print '<td align="center"><b>'.FACTURE.'</b></td>';
    print '<td align="center">Betaling</td>';
    print '<td align="center"><b>'.LITIGE.'</b></td>';
    print '<td align="left"><b>'.MODE_DE_LIVRAISON.'</b></td>';
    print '<td align="center"><b>'.READY.'</b></td>';
    print '</tr>'. PHP_EOL;

    $c2="";
    while ($row2 = mysql_fetch_array($query2)) {
        if($c2=="#F1F1F1") $c2 = "#E8E8E8"; else $c2 = "#F1F1F1"; 
        $checkCom[] = $row2['users_nic'];
        $_payed = ($row2['users_payed']=="yes")? "<img src='../admin/im/val.gif' title='".B14."'>" : "<img src='../admin/im/no_stock.gif' title='".B15."'>";
        $_exp = ($row2['users_statut']=="yes")? "<img src='../admin/im/val.gif' title='".B14."'>" : "<img src='../admin/im/no_stock.gif' title='".B15."'>";
        $readyImage = ($row2['users_ready']=='yes')? "<img src='../admin/im/noPassed.gif' title='".B14."'>" : "<img src='../admin/im/passed.gif' title='".B15."'>";
        
        if($row2['users_litige']=="yes") {
			$c2 = "#FF9900";
			$c3 = "#FF0000";
		}
		else {
			$c3 = "#FFFFAA";
		}
        print "<tr bgcolor='".$c2."' onmouseover=\"this.style.backgroundColor='".$c3."';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td align='center'>".$row2['users_id']."</td>";
        print "<td align='center'>".$row2['users_firstname']."</td>";
		print "<td align='center'>".$row2['users_lastname']."</td>";
		
		
		
//PRINT "<TD>ZIT IK HIER </TD>" . PHP_EOL ;
        print "<td align='center'><b><span style='color:red'>".$_payed."</span></b></td>";
 
        print "<td align='center'><b><span style='color:red'>".$_exp."</span></b></td>";
  
        print "<td align='center'>".dateFr($row2['users_date_payed'], $_SESSION['lang'])."</td>";
   
        print "<td align='left'><a href='../admin/detail.php?id=".$row2['users_id']."&from=todo'>".$row2['users_nic']."</a></td>";
    
        print "<td align='left'><b>".$row2['users_total_to_pay'].$symbolDevise."</b></td>";
 // hier gaan we een form maken per lijn met een eigen naam en action 
 // print "<td align='center'><a href='../admin/factuur_scherm.php?id=".$row2['users_id']."&target=impression' target='_blank'>".str_replace("||","",$row2['users_fact_num'])."</a></td>"; 
	print "<TD align='center'>" . PHP_EOL;
				print "<form method='POST' action='factuur_nummer.php'>";
				print "<input type='text' class='vullen' size='20' name='fact_num' value='". str_replace("||","",$row2['users_fact_num']) ."'>";
				print "<input type='hidden' name='action' value='updateFactNum'>";
				print "<input type='hidden' name='id' value='".$row2['users_id']."'>";
				print "<input type='hidden' name='nic' value='".$row2['users_nic']."'>";
//				print "<input type='hidden' name='yo' value='".$_GET['yo']."'>";
//				print "<input type='hidden' name='jour1' value='".$_GET['jour1']."'>";
//				print "<input type='hidden' name='mois1' value='".$_GET['mois1']."'>";
//				print "<input type='hidden' name='an1' value='".$_GET['an1']."'>";
//				print "<input type='hidden' name='jour2' value='".$_GET['jour2']."'>";
//				print "<input type='hidden' name='mois2' value='".$_GET['mois2']."'>";
//				print "<input type='hidden' name='an2' value='".$_GET['an2']."'>";
				print "&nbsp;<input type='submit' value='".A32."' class='knop'>";
				print "</form>";
	
		
		
		print "</TD>" . PHP_EOL ;
		
		
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
 //   print "<td bgcolor='#FFFFFF' align='center' colspan='11'><a href='artikelen_verzenden.php?de=1&yo=eset&where=print&date1=".$dateAdded1."&date2=".$dateAdded2."&search=none'>".A130."</a></td></tr>";
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
?>
</BODY>
</HTML>
