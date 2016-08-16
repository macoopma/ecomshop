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
 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c = "";

if(empty ($_GET['orderf']))  $_GET['orderf'] = "devis_date_added";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
   $searchReq  = "AND (";
   $searchReq  .= "devis_number like '%".$_POST['searchClient']."%' 
                  OR devis_client like '%".$_POST['searchClient']."%'
                  OR devis_lastname like '%".$_POST['searchClient']."%'
                  OR devis_firstname like '%".$_POST['searchClient']."%'
                  OR devis_company like '%".$_POST['searchClient']."%'
                  OR devis_tva like '%".$_POST['searchClient']."%'
                  OR devis_email like '%".$_POST['searchClient']."%'
                  OR devis_cp like '%".$_POST['searchClient']."%'
                  OR devis_tel like '%".$_POST['searchClient']."%'
                  OR devis_fax like '%".$_POST['searchClient']."%'";
   $searchReq  .= ")";
}
else {
   $searchReq = "";
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div  align="center" class="largeBold"><?php print A1;?></div>
<br>
<?php
$auj = date("Y-m-d");
print "<div align='center'>".A2.": ".dateFr($auj, $_SESSION['lang'])."</div>";


print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<p align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
print "<center><input type='text' size='30' name='searchClient' class='vullen' value=''>";
print "&nbsp;";
print "<input type='submit' class='knop' value='".CHERCHER."'>";
print "&nbsp;&nbsp;&nbsp;";
print "<a style='background:none' href='javascript:void(0);' onClick=\"window.open('infos.php?from=devis','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=200,width=360,toolbar=no,scrollbars=no,resizable=yes');\">";
print "<img border=0 src=im/help.png align=absmiddle>";
print "</a>";
print "</center>";
print "</td></tr></table></p>";
print "</form>";


if(isset($_GET['action']) AND $_GET['action'] == "delete") {
    if(!isset($_GET['confirm']) OR (isset($_GET['confirm']) AND $_GET['confirm']!=="yes")) {
        print "<table align='center' cellspacing='0' cellpadding='10' class='TABLE' width='700'><tr><td align='center'>";
        print "<p align='center'>".A13." <b>".$_GET['id']."</b> ?</p>";
        print "<a href='".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&action=delete&confirm=yes'><b>".A14."</b></a>";
        print "&nbsp;&nbsp;|&nbsp;&nbsp;";
        print "<a href='".$_SERVER['PHP_SELF']."'><b>".A15."</b></a>";
        print "</td></tr></table><br>";
    }

    if((isset($_GET['action']) AND $_GET['action'] == "delete") AND (isset($_GET['confirm']) AND $_GET['confirm']=="yes")) {
	     mysql_query("DELETE FROM devis WHERE devis_number = '".$_GET['id']."'");
         print "<table align='center' cellspacing='0' cellpadding='10' class='TABLE' width='700'><tr><td align='center'>";
         print "<div align='center'>";
         print A40." <b>".$_GET['id']."</b> ".A_ETE_SUPPRIME."<br><br>";
         print "<a href='offertes.php'><b>".BACK."</b></a>";
         print "</div>";
         print "</td></tr></table><br><br>";
    }
}
 
$hids = mysql_query("SELECT *, TO_DAYS(NOW()) - TO_DAYS(devis_date_end) as diff
                     FROM devis
                     WHERE 1
                     ".$searchReq."
                     ORDER BY ".$_GET['orderf']."
					 ".$_GET['c1']."");
$resultcsNum = mysql_num_rows($hids);

if($resultcsNum >0) {
	print "<br><table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr bgcolor='#FFFFFF' height='35'>";
	        print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_sent&c1=".$_GET['c1']."'>".A100."</a></b></td>";
	        print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_sent&c1=".$_GET['c1']."'>".A150."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_number&c1=".$_GET['c1']."'>".A40."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_lastname&c1=".$_GET['c1']."'>".A11."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_company&c1=".$_GET['c1']."'>".A11B."</a></b></td>";
	        print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_client&c1=".$_GET['c1']."'>".A10."</a></b></td>";
	        print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=devis_date_added&c1=".$_GET['c1']."'>".A5."</a></b></td>";
	        print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=diff&c1=".$_GET['c1']."'>".A6."</a></b></td>";
	        print "<td align='center'><b>".A900."</b></td>";
	        print "<td align='center'><b>&nbsp;</b></td>";
	        print "<td align='center'><b>&nbsp;</b></td>";
	print "</tr>";
	
	while ($myhid = mysql_fetch_array($hids)) {
	               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
                if($myhid['devis_date_end'] !== "0000-00-00 00:00:00") {	               
	               if($myhid['diff'] > 0) {
				   		$statut = "<span style='color:#CC0000'><b>".EXPIRE."</b></span>";
				   		$dans = "<img src='im/passed.gif' title='".EXPIRE."'>";
					}
					if($myhid['diff'] < 0) {
				   		$statut = "<b>".EN_COURS."</b>";
				   		$pl = (abs($myhid['diff']) > 1)? A1002."en" : A1002;
                        $dans = "<b>".A1001." ".abs($myhid['diff'])." ".$pl."</b>";			
					}
					if($myhid['diff'] == 0) {
				   		$statut = "<span style='color:#CC0000'><b>--</b></span>";
                        $dans = "<span style='color:#CC0000'><b>".A2."</b></span>";
					}
				}
				else {
				   		$statut = "--";
                        $dans = "--";                
                }
                
                if($myhid['devis_traite'] == 'yes') {
				   		$statut = "<i>".A150."</i>";
                        $dans = "<i>".A150."</i>";
                }

        $yoyoyo = "<a href='offerte_details.php?id=".$myhid['devis_number']."'>".$myhid['devis_number']."</a>";

        $bouton = ($myhid['devis_sent']=="yes")? "<img src='im/val.gif' title='".A14."'>" : "<img src='im/no_stock.gif' title='".A15."'>";
 
        $traite = ($myhid['devis_traite']=="yes")? "<img src='im/val.gif' title='".A14."'>" : "<img src='im/no_stock.gif' title='".A15."'>";
        $compagnie = (empty($myhid['devis_company']))? "--" : $myhid['devis_company'];
        $client = (empty($myhid['devis_client']))? "--" : "<a href='mijnklant.php?id=".$myhid['devis_client']."'>".$myhid['devis_client']."</a>";
        
        print "<tr bgcolor='".$c."'>";
            print "<td align='center'>".$bouton."</td>";
            print "<td align='center'>".$traite."</td>";
            print "<td align='left'>".$yoyoyo."</td>";
            print "<td align='left'>".$myhid['devis_firstname']." ".$myhid['devis_lastname']."</td>";
            print "<td align='left'>".$compagnie."</td>";
            print "<td align='center'>".$client."</td>";
            print "<td align='center'>".dateFr($myhid['devis_date_added'],$_SESSION['lang'])."</td>";
            print "<td align='center'>".$dans."</td>";
            print "<td align='center'>".$statut."</td>";
            print "<td align='center'><a href='offerte_details.php?id=".$myhid['devis_number']."' style='background:none; text-decoration:none'><img src='im/details.gif' border='0' title='".VOIR."'></a></td>";
            print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?action=delete&id=".$myhid['devis_number']."' style='background:none; text-decoration:none'><img src='im/supprimer.gif' border='0' title='".A8."'></a></td>";
        print "</tr>";
	}
	print "</table><br><br><br>";
}
else {
    print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
    print "<tr bgcolor='#FFFFFF'>";
    print "<td align='center'><p align='center' class='fontrouge'><b>".A9."</b></td>";
    print "</tr>";
    print "</table><br><br><br>";
    if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
        print "<p align='center'><a href='offertes.php'><b>".BACK."</b></a></p>";
    }
}
?>
</body>
</html>
