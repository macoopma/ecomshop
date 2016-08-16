<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
 
if(isset($_POST['action']) AND $_POST['action']=="update26") {
  
    mysql_query("UPDATE products
                SET products_exclusive='no'");
   
    if(isset($_POST['select'])) {
        $ExclusiveNum = count($_POST['select']);
        if($ExclusiveNum>0) {
            for($i=0; $i<=$ExclusiveNum-1; $i++) {
                mysql_query("UPDATE products
                            SET products_exclusive='yes'
                            WHERE products_id='".$_POST['select'][$i]."'");
            }
        }
    }
}

if(empty ($_GET['orderf']))  $_GET['orderf'] = "products_exclusive";
if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(!isset($c)) $c="";

if(isset($_GET['orderf']) and $_GET['orderf'] == "products_qt") $editOrder = "Voorraad";
if(isset($_GET['orderf']) and $_GET['orderf'] == "products_name_".$_SESSION['lang']."") $editOrder = A1;
if(isset($_GET['orderf']) and $_GET['orderf'] == "products_ref") $editOrder = "Ref";
if(isset($_GET['orderf']) and $_GET['orderf'] == "fournisseurs_company") $editOrder = A2;
if(isset($_GET['orderf']) and $_GET['orderf'] == "products_exclusive") $editOrder = A8;

$hid = mysql_query("SELECT fournisseurs_company, fournisseurs_id
                     FROM fournisseurs
                     WHERE fournisseur_ou_fabricant = 'fournisseur'
                     ORDER BY fournisseurs_company");

$hids = mysql_query("SELECT p.products_id, p.fabricant_id, p.products_exclusive, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_qt, p.fournisseurs_id, f.fournisseurs_company, f.fournisseurs_id
                     FROM fournisseurs as f
                     INNER JOIN products as p
                     ON(p.fournisseurs_id = f.fournisseurs_id)
                     WHERE 1
                     ORDER BY ".$_GET['orderf']."
                     ".$_GET['c1']."");
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div  align="center" class="largeBold"><?php print A10;?></div>
<p align="center"><?php print A3;?> <b><?php print $editOrder;?></b></p>

<?php

print "<br>";


print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";

print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr bgcolor='#FFFFFF' height='35'>";
print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_name_".$_SESSION['lang']."&c1=".$_GET['c1']."'><b>".A1."</b></a></td>";
print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_ref&c1=".$_GET['c1']."'><b>Ref</b></a></td>";
print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_qt&c1=".$_GET['c1']."'><b>Voorraad</b></a></td>";
print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=fournisseurs_company&c1=".$_GET['c1']."'><b>".A40."</b></a></td>";
print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=fournisseurs_company&c1=".$_GET['c1']."'><b>".A4."</b></a></td>";
print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?orderf=products_exclusive&c1=".$_GET['c1']."'><b>".A7."</b></a></td>";
print "</tr>";
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));

while ($myhid = mysql_fetch_array($hids)) {
               if($c=="#FFFFFF") {$c="#FFFFFFF";} else {$c="#FFFFFF";}
               
               $hidMarque = mysql_query("SELECT fournisseurs_company, fournisseurs_id FROM fournisseurs WHERE fournisseurs_id=".$myhid['fabricant_id']."");
               $myhidMarque = mysql_fetch_array($hidMarque);
               $fabricant = $myhidMarque['fournisseurs_company'];
               
               if($myhid['products_qt'] == "0") {
                  $comFour = "<b><span style='color:red'>".$myhid['products_qt']."</b></span>";
                  $note = "<b>".A7."</b>";
               }

               if($myhid['products_qt'] < "0") {
                  $comFour = "<b><span style='color:red'>".$myhid['products_qt']."</span></b>";
                  $note = "<b><span style='color=:red'>".A8."</span></b>";
               }

               if($myhid['products_qt'] > "0") {
                  $comFour = $myhid['products_qt'];
               }

               if($myhid['products_qt'] > "0" and $myhid['products_qt'] <= $seuilStock) {
                  $comFour = "<b>".$myhid['products_qt']."</b>";
               }
               
               if($myhid['products_exclusive']=="yes") {
			   		$note = "checked";
					$backG="style='background:#CCFF99'";
				}
				else {
					$note = "";
					$backG="";
				}
               
                 	print "<tr bgcolor='".$c."'><td align='left'><a href='artikel_wijzigen_details.php?id=".$myhid['products_id']."'>".$myhid['products_name_'.$_SESSION['lang']]."</a></td>";
                    print "<td align='left'>".strtoupper($myhid['products_ref'])."</td>";
                    print "<td align='left'>".$comFour."</td>";
                    print "<td align='left'><a href='fab_lev_wijzigen2.php?idStock=".$myhid['fabricant_id']."'>".$fabricant."</a></td>";
                    print "<td align='left'><a href='fab_lev_wijzigen2.php?idStock=".$myhid['fournisseurs_id']."'>".$myhid['fournisseurs_company']."</a></td>";
                    print "<td align='center' ".$backG."><input type='checkbox' name='select[]' value='".$myhid['products_id']."' ".$note."></td>";
                	print "</tr>";
}
print "<tr height='35'>";
print "<td align='center' colspan='6' bgcolor='#FFFFFF'>";
print "<input type='hidden' name='action' value='update26'>";
print "<input type='submit' name='Submit' class='knop' value='".A400."'>";
print "</td>";
print "</tr>";
print "</table>";
print "</form>";
?>
<br><br><br>
</body>
</html>
