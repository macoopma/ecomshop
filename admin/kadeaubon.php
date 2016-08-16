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
$auj = date("Y-m-d");
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));

if(empty ($_GET['orderf']))  $_GET['orderf'] = "gc_start";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
   $searchReq  = "AND (";
   $searchReq  .= "gc_number like '%".$_POST['searchClient']."%'
                  OR gc_nic like '%".$_POST['searchClient']."%'";
   $searchReq  .= ")";
}
else {
   $searchReq = "";
}


if(isset($_GET['action']) AND $_GET['action']=='addGc') {
	$hoy = date("Y-m-d H:i:s");
	mysql_query("INSERT INTO products (
					categories_id,
					categories_id_bis,
					fournisseurs_id,
					afficher_fournisseur,
					fabricant_id,
					afficher_fabricant,
					products_name_1,
					products_name_2,
					products_name_3,
					products_price,
					products_weight,
					products_options,
					products_ref,
					products_im,
					products_image,
					products_visible,
					products_taxable,
					products_tax,
					products_date_added,
					products_viewed,
					products_added,
					products_qt,
					products_download,
					products_exclusive,
					products_sold,
					products_deee,
					products_sup_shipping,
					products_caddie_display,
					products_forsale
				)
				VALUES (
					0,
					'',
					0,
					'no',
					0,
					'no',
					'Chèque cadeau',
					'Gift certificate',
					'Cheque de regalo',
					'100.00',
					0,
					'no',
					'GC100',
					'yes',
					'im/cheque_cadeau.gif',
					'yes',
					'no',
					'0.00',
					'".$hoy."',
					0,
					0,
					100000,
					'no',
					'no',
					0,
					'0.00',
					'0.00',
					'no',
					'yes'
				);") or die (mysql_error());
			$addGcMessage = "<div align='center' style='color:#FF0000'><b>".CHEQUE_CADEAU_ADDED."</b></div>";
}


$hids = mysql_query("SELECT * FROM gc WHERE 1 ".$searchReq." ORDER BY ".$_GET['orderf']." ".$_GET['c1']."");
$resultcsNum = mysql_num_rows($hids);

 
$isInBddRequest = mysql_query("SELECT products_id FROM products WHERE products_ref='GC100'");
$isInBddRequestNum = mysql_num_rows($isInBddRequest); 

 
if(isset($_GET['action2']) and $_GET['action2']=="maj") {
    $keys=array_keys($_POST['comPayed']);
    $values=array_values($_POST['comPayed']);
    $comPayedNb = count($_POST['comPayed'])-1;
    for($a=0; $a<=$comPayedNb; $a++) {
       mysql_query("UPDATE users_orders SET users_affiliate_payed = '".$values[$a]."' WHERE users_nic = '".$keys[$a]."'");
    }
}


if(isset($_GET['action']) and $_GET['action'] == "delete") {
$deleteGcMessage = "<table border='0' cellpadding='7' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
$deleteGcMessage.= "<td align='center' class='fontrouge'>";
$deleteGcMessage.= A13." <b>".$_GET['id']."</b> ?";
$deleteGcMessage.= "<br><br>";
$deleteGcMessage.= "<a href='".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&action=delete&confirm=yes'><b>".A14."</b></a>";
$deleteGcMessage.= "&nbsp;&nbsp;|&nbsp;&nbsp;";
$deleteGcMessage.= "<a href='".$_SERVER['PHP_SELF']."'><b>".A15."</b></a>";
$deleteGcMessage.= "</td></tr></table>";

if((isset($_GET['action']) AND $_GET['action'] == "delete") AND (isset($_GET['confirm']) AND $_GET['confirm']=="yes")) {
  mysql_query("DELETE FROM gc WHERE  gc_number = '".$_GET['id']."'");
  header("Location:kadeaubon.php");
}
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
print '<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr>';
print '<td align="center">';
print '&bull;&nbsp;<a href="kadeaubon_toevoegen.php" target="main">'.AJOUTER_CHEQUE_CADEAU.'</a>';
print '</td></tr>';
print '</table>';




print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<table align=center border=0 cellpadding=2 cellspacing=0 class=TABLE width=700><tr><td>";
print "<p align='center'>";
print "<input type='text' size='30' name='searchClient' value=''>";
print "&nbsp;";
print "<input type='submit' class='knop' value='".CHERCHER."'>";

print "&nbsp;<a style='background:none' href='javascript:void(0);' onClick=\"window.open('infos.php?from=cheque','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=180,width=360,toolbar=no,scrollbars=no,resizable=yes');\">";
print "<img border=0 src=im/help.png align=absmiddle></a>";
print "</form></tr></td></table>";



if($isInBddRequestNum==0) {
	print "<p align='center' style='background:#888888; border:#FFFF00 2px solid; padding:10px;'>";
	print "<span style='color:#FFFFFF; font-size:13px; font-weight:bold;'>".AUCUN_CHEQUE_CADEAU."</span>";
	print "<br>";
	print "<a href='kadeaubon.php?action=addGc' style='text-decoration:none'><span style='color:#CCCCCC'><i>".ClIQUEZ_ICI_TO_ADD."</i></span></a>";
	print "</p>";
}

if($resultcsNum >0) {
print "<div align='center'>".A2.": ".dateFr($auj,$_SESSION['lang'])."</div>";
print "<br>";


if(isset($deleteGcMessage)) print $deleteGcMessage."<br>";
 
if(isset($addGcMessage)) print $addGcMessage."<br>";

print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
        print "<tr bgcolor='#FFFFFF' height='35'>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_number&c1=".$_GET['c1']."'>".A100."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_nic&c1=".$_GET['c1']."'>".A3."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_start&c1=".$_GET['c1']."'>".A40."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_end&c1=".$_GET['c1']."'>".A4."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_amount&c1=".$_GET['c1']."'>".A114."</a></b></td>";
        print "<td align='left'><b>".C15."</b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_payed&c1=".$_GET['c1']."'>".A120."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=gc_enter&c1=".$_GET['c1']."'>".A7."</a></b></td>";
        print "<td align='center'><b>".A7A."</b></td>";
        print "<td align='center'>&nbsp;</td>";
        print "<td align='center'>&nbsp;</td>";
        print "<td align='center'>&nbsp;</td>";
        print "</tr>";

	while ($myhid = mysql_fetch_array($hids)) {
	               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
	               $hidz = mysql_query("SELECT users_id FROM users_orders WHERE users_nic='".$myhid['gc_nic2']."'");
                   $myhidz = mysql_fetch_array($hidz);
	               $hidzz = mysql_query("SELECT users_id, users_lang FROM users_orders WHERE users_nic='".$myhid['gc_nic']."'");
                   $myhidzz = mysql_fetch_array($hidzz);
	               $payed = ($myhid['gc_payed']==1)? "<img src='im/val.gif' title='".A14."'>" : "<b>".A15."</b>";
	               if($myhid['gc_enter']==1) {
                        $deduit="<img src='im/val.gif' title='".A14."'>";
                        $benef = "<a href='detail.php?id=".$myhidz['users_id']."&from=gc'>".$myhid['gc_nic2']."</a>";
                   } 
                   else {
                        $deduit = "<b>".A15."</b>";
                        $benef = "--";
                   }
	               
                   $dateMaxCheck = explode("-",$myhid['gc_end']);
                   $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
                   $statut = ($dateMax < $today)? "<img src='im/passed.gif' title='".C13."'>" : "<img src='im/noPassed.gif' title='ok'>";
	               
	                 print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
					 	   print "<td align='left' class='fontrouge'><b>".$myhid['gc_number']."</b></td>";
					 	   if($myhid['gc_nic']!=="ADMIN") {
                              print "<td align='left'><a href='detail.php?id=".$myhidzz['users_id']."&from=gc'>".$myhid['gc_nic']."</a></td>";
                           }
                           else {
                              print "<td align='left'>".$myhid['gc_nic']."</td>";
                           }
	                        print "<td align='left'>".dateFr($myhid['gc_start'],$_SESSION['lang'])."</td>";
	                        print "<td align='left'>".dateFr($myhid['gc_end'],$_SESSION['lang'])."</td>";
	                        print "<td align='left'>".$myhid['gc_amount']."</td>";
	                        print "<td align='center'>".$statut."</td>";
	                        print "<td align='center'>".$payed."</td>";
	                        print "<td align='center'>".$deduit."</td>";
	                        print "<td align='center'>".$benef."</td>";
	                        if(isset($myhidzz['users_lang']) AND $myhidzz['users_lang']!=="") $lZ = $myhidzz['users_lang']; else $lZ = $_SESSION['lang'];
	                        print "<td align='center'><a style='TEXT-DECORATION:none; background:none' href='../geschenkbon/bon_maken.php?cert=".$myhid['gc_number']."&l=".$lZ."' target='_blank'><img src='../im/print.gif' border='0' title='".IMP."'></a></td>";
	                        if($myhid['gc_nic']!=="ADMIN") {
                              	print "<td align='center'><a style='TEXT-DECORATION:none; background:none' href='detail.php?id=".$myhidzz['users_id']."&from=gc'><img src='im/details.gif' border='0' title='".VOIR."'></a></td>";
	                        }
	                        else {
	                        	$openLeg = "<a style='TEXT-DECORATION:none; background:none' href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=gc&id=".$myhid['gc_number']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=520,toolbar=no,scrollbars=yes,resizable=yes');\">";
                              	$commentView = ($myhid['gc_comment']=="")? NO_COMMENTAIRE : COMMENTAIRE.": ".$myhid['gc_comment'];
                              	print "<td align='center'>".$openLeg."<img src='im/i.gif' border='0' title='".$commentView."'></a></td>";
                           }
                           print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?action=delete&id=".$myhid['gc_number']."'><img src='im/supprimer.gif' border='0' title='".A8."'></a></td>";
                     print "</tr>";
	                }
	print "</table>";
}
else {
print "<br><br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr bgcolor='#FFFFFF'>";
print "<td align='center'><p align='center' class='fontrouge'><b>".A9."</b></td>";
print "</tr>";
print "</table>";

if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
   print "<p align='center'><a href='kadeaubon.php'><b>".BACK."</b></a></p>";
}
}
?>
<br><br><br><br>
</body>
</html>

