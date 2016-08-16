<?php
session_start();

include('../configuratie/configuratie.php');
function incLang($u) {
    $fichier = explode("/",$u);
    $what = end($fichier);
    return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
if(isset($_POST['action']) AND $_POST['action']=="update") {
	mysql_query("UPDATE gc  SET gc_comment = '".str_replace("'","&#146;",$_POST['gc_comment'])."' WHERE gc_number = '".$_GET['id']."'");
	$messageZ = "<p class='fontrouge'>&nbsp;&nbsp;<b>".UPDATE_OK."</b></p>";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php print A1;?></title>
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="10" topmargin="10" leftmargin="10" marginwidth="10" marginheight="10">

<?php

// Categories
if(isset($_GET['open']) AND $_GET['open']=='categorie') {
$result = mysql_query("SELECT * FROM categories ORDER BY categories_order");
$id = 0;
$i = 0;

while($message = mysql_fetch_array($result)) {
    $papa = $message['categories_id'];
    $fils = $message['parent_id'];
    $visible = $message['categories_visible'];
    if($message['categories_visible']=='yes') $visible=A2; else $visible="<span class='fontrouge'><b>".A3."</b></span>";
    if($message['categories_noeud']=="B") $titre = "<b>".strtoupper($message['categories_name_'.$_SESSION['lang']])."</b>"; else $titre=$message['categories_name_'.$_SESSION['lang']];
    $data[] = array($papa,$fils,$titre,$visible);
}

function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3) {
global $c;
  if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {
    if($tab[$x][1]==$pere) {
       print "</tr><tr bgcolor=".$c.">";
       print "<td>";
       print espace3($rang3).$tab[$x][2];
       print "</td>";
       print "<td>";
       print $tab[$x][0];
       print "</td>";
       print "<td>";
       print $tab[$x][3];
       print "</td>";
       print "</tr>";
       recur($tab,$tab[$x][0],$rang3+1);
    }
  }
}

print "<table border=0 width=490 cellpadding=0 cellspacing =2>";
print "<tr>";
print "<td bgcolor='#FFFFFF' height='25' align='left'><b>".A4."</b></td>";
print "<td bgcolor='#FFFFFF' height='25' align='left' width='40'><b>ID</b></td>";
print "<td bgcolor='#FFFFFF' height='25' align='left' width='40'><b>".A6."</b></td>";
recur($data,"0","0");
print "</table>";
}

// Artikelen
 if(isset($_GET['open']) AND $_GET['open']=='article') {
 
if(isset($_GET['prod']) AND $_GET['prod']!=="") {
	$prod = explode("|",$_GET['prod']);
	foreach($prod AS $prodZ) {
		$prodRequest = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_id = '".$prodZ."'") or die (mysql_error());
        if(mysql_num_rows($prodRequest)>0) {
			$prodResult = mysql_fetch_array($prodRequest);
			print "<div align='center'>&bull;&nbsp;[".$prodZ."]&nbsp;".$prodResult['products_name_'.$_SESSION['lang']]."</div>";
		}
	}
	print "<hr>";
}
$prods = mysql_query("SELECT p.*, c.categories_name_".$_SESSION['lang']."
                      FROM products as p
                      INNER JOIN categories as c
                      ON(p.categories_id = c.categories_id)
                      WHERE c.categories_noeud != 'R'
                      AND p.products_ref != 'GC100'
                      ORDER BY categories_name_".$_SESSION['lang']."");

$result = mysql_query("SELECT * FROM categories ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {

$result2 = mysql_query("SELECT * FROM products
                       WHERE categories_id = '".$message['categories_id']."'
                       AND products_ref != 'GC100'
                       ORDER BY products_name_".$_SESSION['lang']."");
$productsNum = mysql_num_rows($result2);

$papa = $message['categories_id'];
$fils = $message['parent_id'];
$visible = $message['categories_visible'];
$noeud = $message['categories_noeud'];

if($message['categories_visible']=='yes') {
   $visible=A2; 
   $visible2 = '';
} 
else {
   $visible="<span color=red><b>".A3."</b></span>";
   $visible2 = " <img src='im/eye.gif' title='".A7."'> ";
}

if($message['categories_noeud']=="B") {
   $titre = "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>".$visible2;
}
else {
   $titre=$message['categories_name_'.$_SESSION['lang']]." ".$visible2;
}

$data[] = array($papa,$fils,$titre,$visible,$noeud);
}


function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}

print "<table border=0 cellpadding=2 cellspacing =0 align=center class=TABLE2";
print "<tr>";


function recur($tab,$pere,$rang3,$lang) {
global $c;

  if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {

    if($tab[$x][1]==$pere) {

       print "</tr><tr bgcolor=".$c.">";
       print "<td>";
       print espace3($rang3).$tab[$x][2];

               $result3 = mysql_query("SELECT products_name_".$lang.", products_deee, products_exclusive, products_id, products_qt, products_visible, products_download, products_related
                                       FROM products
                                       WHERE categories_id = '".$tab[$x][0]."' 
                                       OR categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
                                       AND products_ref != 'GC100'
                                       ORDER BY products_name_".$lang."");

               while($produit = mysql_fetch_array($result3)) {
                if(strlen($produit['products_name_'.$lang])>=50) {
                    $displayTitle = "title=".$produit['products_name_'.$lang].""; 
                    $ProdNameProdList = substr($produit['products_name_'.$lang],0,80)."...";
                } else {
                    $displayTitle = "";
                    $ProdNameProdList = $produit['products_name_'.$lang];
                }
                print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#CC0000'>[".$produit['products_id']."]</span>&nbsp;".$ProdNameProdList;
               }
       print "</td>";

       recur($tab,$tab[$x][0],$rang3+1,$lang);
    }
  }

}

recur($data,"0","0",$_SESSION['lang']);

print "</tr>";
print "</table>";

}

// korting code
if(isset($_GET['open']) AND $_GET['open']=='codeNote') {
   $codeQueryA = mysql_query("SELECT code_promo_note FROM code_promo WHERE code_promo = '".$_GET['code']."'");
   $codeNote = mysql_fetch_array($codeQueryA);
   print str_replace("\r\n","<br>",$codeNote['code_promo_note']);
}


// interne nota
if(isset($_GET['open']) AND $_GET['open']=='noteInterne') {
   $codeQueryA = mysql_query("SELECT users_note FROM users_orders WHERE users_nic = '".$_GET['nic']."'");
   $codeNote = mysql_fetch_array($codeQueryA);
   print "<p><b><u>".NOTES_INTERNES."</u></b> :</p>";
   print str_replace("\r\n","<br>",$codeNote['users_note']);
}


// klant nota
if(isset($_GET['open']) AND $_GET['open']=='noteClient') {
   $codeQueryA = mysql_query("SELECT users_comment FROM users_orders WHERE users_nic = '".$_GET['nic']."'");
   $codeNote = mysql_fetch_array($codeQueryA);
   print "<p><b><u>".NOTES_CLIENT."</u></b>:</p>";
   print str_replace("\r\n","<br>",$codeNote['users_comment']);
}

 
// klacht
if(isset($_GET['open']) AND $_GET['open']=='noteLitige') {
   $codeQueryA = mysql_query("SELECT users_litige_comment FROM users_orders WHERE users_nic = '".$_GET['nic']."'");
   $codeNote = mysql_fetch_array($codeQueryA);
   if(isset($_GET['status']) AND $_GET['status']==1) print "<p><b><u>".NOTES_LITIGE."</u></b> :</p>"; else print "<p><b><u>".LITIGE_CLOS."</u></b> :</p><u>".HISTORIQUE."</u>:<br>";
   if($codeNote['users_litige_comment']=="") print NOTES_LITIGE_EMPTY; else print str_replace("\r\n","<br>",$codeNote['users_litige_comment']);
}


// nota klacht
if(isset($_GET['open']) AND $_GET['open']=='noteShare') {
   $codeQueryA = mysql_query("SELECT users_share_note FROM users_orders WHERE users_nic = '".$_GET['nic']."'");
   $codeNote = mysql_fetch_array($codeQueryA);
   print "<p><b><u>".NOTES_SHARE."</u></b> :</p>";
   if($codeNote['users_share_note']=="") print NOTES_LITIGE_EMPTY; else print str_replace("\r\n","<br>",$codeNote['users_share_note']);
}


// geschenkbon
if(isset($_GET['open']) AND $_GET['open']=='gc') {
   $gcQueryA = mysql_query("SELECT * FROM gc WHERE gc_number = '".$_GET['id']."'");
   $gcNote = mysql_fetch_array($gcQueryA);
   print "<p><b><u>".CHEQUE_CADEAU." # <span class='fontrouge'>".$_GET['id']."</span></u></b> :</p>";
   if(isset($messageZ)) print $messageZ;
   print "<form action='uitleg.php?id=".$_GET['id']."&open=gc' method='POST'>";
   print "&nbsp;&nbsp;Nota:<br>";
   print "<input type='hidden' name='action' value='update'>";
   print "<input type='hidden' name='open' value='gc'>";
   print "&nbsp;&nbsp;<textarea name='gc_comment' rows='12' cols='80'>".$gcNote['gc_comment']."</textarea>";
   print "<p>&nbsp;&nbsp;<input type='submit' class='knop' value='".UPDATE."'></p>";
   print "</form>";
}

// landen levering
if(isset($_GET['open']) AND $_GET['open']=='liv') {
    $requestLiv2 = mysql_query("SELECT livraison_nom_".$_SESSION['lang'].", livraison_country FROM ship_mode WHERE livraison_id = '".$_GET['id']."'") or die (mysql_error());
    $resultLiv2 = mysql_fetch_array($requestLiv2);
    $paysZ = $resultLiv2['livraison_nom_'.$_SESSION['lang']];
    print "<p><b><u>".strtoupper($paysZ)."</u></b></p>";
    if($resultLiv2['livraison_country']!=="") {
      $payLiv2 = explode("|",$resultLiv2['livraison_country']);
      foreach($payLiv2 AS $itemsLiv2) {
         if($itemsLiv2!=='') {
           $requestPays2 = mysql_query("SELECT countries_shipping, countries_name, iso FROM countries WHERE countries_id = '".$itemsLiv2."'") or die (mysql_error());
           $resultPays2 = mysql_fetch_array($requestPays2);
           $_zonne[] = $resultPays2['countries_shipping'];
           print "&nbsp;- ".$resultPays2['countries_name']." [".$resultPays2['iso']." - ".$resultPays2['countries_shipping']."]<br>";
         }
      }
  
      if(isset($_zonne) AND count($_zonne)>0) {
   
          $_zonne = array_unique($_zonne);
          sort($_zonne);
          print "<br><br>";
		  print "<table border='0' cellpadding='5' cellspacing='0' style='background:#FFFFFF; border:#CCCCCC 1px solid; color:#666666'><tr><td>";
		  print "&bull;&nbsp;".COMPLETER_LA_GRILLE_TARIFAIRE." '<b>".$paysZ."</b>' ".POUR_LES_ZONES_CI_DESSOUS;
		  print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
          foreach($_zonne AS $items) {
              print "<div>- ".$items."</div>";
          }
          print "</td></tr></table>";
      }
    }
    else {
        print NO_COUNTRY;
    }
}

 
// levering
if(isset($_GET['open']) AND $_GET['open']=='shippingMode') {
    $requestShipRequestZ = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$_GET['ship']."' ") or die (mysql_error());
    $resultShipNameZ = mysql_fetch_array($requestShipRequestZ);
    if(mysql_num_rows($requestShipRequestZ) > 0) {
        print "<p>".MODE_DE_LIVRAISON.": <b>".$resultShipNameZ['livraison_nom_'.$_SESSION['lang']]."</b></p><br>";
    }
    else {
        print "<p><i>(".CE_MODE_DE_LIVRAISON_A_ETE_SUUPRIME_DANS_ADMIN.").</i><br>".MODE_DE_LIVRAISON." ID: <b>".$_GET['ship']."</b></p><br>";
    }
}

// alert
if(isset($_GET['open']) AND $_GET['open']=='price') {
        $tarifRequestZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$_GET['id']."'");
        if(mysql_num_rows($tarifRequestZ)>0) {
            $tarifResultZ = mysql_fetch_array($tarifRequestZ);
            print "<p>".AUCUN_TARIF_DEFINI." <b>".$tarifResultZ['livraison_nom_'.$_SESSION['lang']]."</b>.<br>".RENDEZ_VOUS_DANS_ADMIN."</p>";
        }
}

 
// interne nota bekijken
if(isset($_GET['open']) AND $_GET['open']=='note') {
        $seeNoteRequest = mysql_query("SELECT users_note FROM users_orders WHERE users_id = '".$_GET['id']."'");
        if(mysql_num_rows($seeNoteRequest)>0) {
            $seeNoteResult = mysql_fetch_array($seeNoteRequest);
            print "<p>".$seeNoteResult['users_note']."</p>";
        }
}

// afbeelding artikel met optie
if(isset($_GET['idOpt']) AND $_GET['idOpt']!=='') {
		$optQuery = mysql_query("SELECT products_options_stock_im FROM products_options_stock WHERE products_options_stock_id = '".$_GET['idOpt']."'");
        if(mysql_num_rows($optQuery)>0) {
            $seeIm = mysql_fetch_array($optQuery);
			$_imageZ = explode("|",$seeIm['products_options_stock_im']);
			if(count($_imageZ)>0) {
				foreach($_imageZ AS $item) {
 
					print "<div align='center' style='font-size:14px; font-weight:bold; color:#666666; background:#FFFFCC; border:1px #CCCCCC solid; padding:5px;'>".$item."</div>";
					print "<div align='center'><img src='im/zzz.gif' width='1' height='8' border='0'></div>";
					print "<div align='center'><img src='../".$item."' border='0'></div>";
 
					print "<br>";
				}
			}
        }
}
?>
</body>
</html>
