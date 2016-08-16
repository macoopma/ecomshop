<?php
//session_start();
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');
 
 
        
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("includes/lang/lang_".$_SESSION['lang'].".php");

$dateHoy = date("d-m-Y H:i:s");

 
if($taxePosition=="Plus tax") {$tax="hors taxe";}
if($taxePosition=="Tax included") {$tax="taxes incluses";}
if($taxePosition=="No tax") {$tax="sans taxe";}

 
function recur3($tab,$pere,$rang3,$lang) {
global $symbolDevise,$displayOutOfStock,$actRes,$defaultOrder,$displayPriceInShop,$_SESSION;
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {

    if($tab[$x][1]==$pere) {
      
       if($tab[$x][1]=="0") $a="style='BACKGROUND-COLOR: #CCCCCC;'"; else $a="";
       if($tab[$x][4]=="B" AND $tab[$x][1]!=="0") $a="style='BACKGROUND-COLOR: #F1F1F1;'";
       print "</tr><tr>";
       print "<td colspan='3' height='20' $a>";
       print $tab[$x][2];
                if($defaultOrder == "Ref") $dOrder = "products_ref";
                if($defaultOrder == "Article") $dOrder = "products_name_".$_SESSION['lang'];
                if($defaultOrder == "Prix") $dOrder = "products_price";
                if($defaultOrder == "Entreprise") $dOrder = "fournisseurs_id";
                if($defaultOrder == "Les_plus_populaires") $dOrder = "products_viewed";
                if($defaultOrder == "Id") $dOrder = "products_id";
                if($defaultOrder == "id") $dOrder = "products_id";
                if(!isset($dOrder)) $dOrder = "products_id";

               $result3 = mysql_query("SELECT p.products_ref, p.products_name_".$lang.", p.products_image, p.products_desc_".$lang.", p.products_im, p.products_id, p.products_price, p.products_qt, p.products_visible, p.products_download, p.products_related, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible,
                                       IF(s.specials_new_price<p.products_price
                                       AND s.specials_last_day >= NOW()
                                       AND s.specials_first_day <= NOW(),
                                       s.specials_new_price, p.products_price) as ord
                                       FROM products as p
                                       LEFT JOIN specials as s
                                       ON (p.products_id = s.products_id)
                                       WHERE categories_id = '".$tab[$x][0]."'
                                       AND p.products_visible = 'yes'
                                       AND p.products_forsale = 'yes'
                      						OR (p.categories_id_bis LIKE '%|".$tab[$x][0]."|%'
                      						AND p.products_visible = 'yes')
                                       ORDER BY ".$dOrder."
                                       DESC");

               while($produit = mysql_fetch_array($result3)) {
                      
                     if($produit['products_qt'] <= 0) {
                                  if($displayOutOfStock=="oui") {
                                      if($actRes=="non") {
                                          $rupture = "<i><b>(".NOT_IN_STOCK.")</b></i>"; }
                                      else {
                                          $rupture = "<i><b>(".EN_COMMANDE.")</b></i>";
                                      }
                                  }
                                  else {
                                      $rupture = "<i><b>(".NOT_IN_STOCK.")</b></i>";
                                  }
                     }
                     else {
                        $rupture = "";
                     }
                      
                     if($produit['products_price'] == $produit['ord']) {
                          $prix = "<b>".$produit['products_price']."</b> ".$symbolDevise;
                          $priceOnly = $produit['products_price'];
                     }
                     else {
                     	if($produit['specials_visible']=="yes") {
                          $prix = "<s>".$produit['products_price']." ".$symbolDevise."</s><br><span class='fontrouge'><b>".$produit['specials_new_price']."</b></span> ".$symbolDevise;
                          $priceOnly = $produit['specials_new_price'];
                        }
                        else {
                          $prix = "<b>".$produit['products_price']."</b> ".$symbolDevise;
                          $priceOnly = $produit['products_price'];
						}
                     }

                        if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                            $prix2 = "<div align='right'>".$prix."<br><i>".VOTRE_PRIX.":</i></div><div align='right' style='BACKGROUND-COLOR: #FAFAFA; border:1px #CCCCCC solid; padding:2px;'>".newPrice($priceOnly,$_SESSION['reduc'])." ".$symbolDevise."</div></b>";
                        }
                        else {
                            $prix2 = $prix;
                        }
                     if($rupture=="<i><b>(".NOT_IN_STOCK.")</b></i>") $prix="";
                     if($produit['products_im']=="no") {$imm="im/no_image_small.gif";} else {$imm=$produit['products_image'];}
                        $yoZZ1 = @getimagesize($imm);
                        if(!$yoZZ1) $imm="im/zzz_gris.gif";
                     $imagePrint = resizeImage($imm,65,65);
                    if(!empty($produit['products_desc_'.$lang])) {$ProdPrint = $produit['products_desc_'.$lang];} else {$ProdPrint = "&nbsp;";}
                    $ProdPrint = strip_tags($ProdPrint);
                    $ProdPrint = adjust_text($ProdPrint,200,"..");
                     
                print "</td></tr><tr>";
                print "<td class='fontrouge'>";
                print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src='im/fleche_right.gif'>&nbsp;<a href='beschrijving.php?id=".$produit['products_id']."'><b>".$produit['products_name_'.$lang]."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$rupture";
                print "<br>";
                print "<table border='0' cellpadding='0' cellspacing ='0' width='100%'><tr>";
                print "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                print "<td>";
                print "<i>".$ProdPrint."</i>";
                print "<br>";
                  $RefPrint = strip_tags($produit['products_ref']);
                  $RefPrint = trim($RefPrint);
                print "<span class='fontrouge'><i>Ref: ".$RefPrint."</i></span>";
                print "</td>";
                print "</tr></table>";
                print "</td>";
                print "<td width='100' align='center'><img src='".$imm."' border='0' width='".$imagePrint[0]."' height='".$imagePrint[1]."' alt='".$produit['products_name_'.$_SESSION['lang']]."' title='".$produit['products_name_'.$_SESSION['lang']]."'></td>";
                if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") { print "<td width='80' align='right'>".$prix2;}
               }
       print "</td>";

       recur3($tab,$tab[$x][0],$rang3+1,$lang);
    }
  }

}
?>

<html>
<head>
<title>Price List</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="30" marginwidth="0" marginheight="0">

<?php

print "<table border='0' width='650' cellpadding='2' cellspacing ='0' align='center' style='background-color:#FFFFFF'>";
print "<tr>";
print "<td colspan='3' align='left'>";

print "<table border='0' width='100%' cellpadding='0' cellspacing ='0'><tr>";
print "<td align='left' valign='top'>";
print ($logo=='noPath')? "&nbsp;" : "<a href='http://".$www2.$domaineFull."'><img src='im/logo.gif' border='0'></a>";
print "</td><td align='right'>";
print "<b>".$store_name."</b><br>";
print $address_street."<br>";
print $address_cp." - ".$address_city."<br>";
print $address_country;
if(!empty($address_autre)) print "<br>".$address_autre;
if(!empty($tel)) print "<br>".TELEPHONE.": ".$tel;
if(!empty($fax)) print "<br>".FAX.": ".$fax;
print "</td></tr></table>";

print "</td>";
print "</tr><tr>";
       print "<td height='50' valign='bottom' align='left'>".CATALOG_CONTENTS." $dateHoy<br>";
       print "</td>";
       print "<td colspan='2' valign='bottom' align='right'>";
       print "<a href='javascript:window.print()'><img src='im/print.gif' border='0'></a>";
       print "</td>";
       
$result = mysql_query("SELECT * FROM categories WHERE categories_visible = 'yes' ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {
	$papa = $message['categories_id'];
	$fils = $message['parent_id'];
	$noeud = $message['categories_noeud'];
	if($noeud=="B") {
		$titre = "<div align='left'><b>".maj($message['categories_name_'.$_SESSION['lang']])."</b></div>";} 
	else {
		$titre="&nbsp;&nbsp;&nbsp;&nbsp;<b>".$message['categories_name_'.$_SESSION['lang']]."</b>";
	}
	$dataPA[] = array($papa,$fils,$titre,'yes',$noeud);
}
recur3($dataPA,"0","0",$_SESSION['lang']);

print "</tr>";
print "<tr><td colspan='3' align='center'>";
print "<i>".LES_PRIX_FIGURANT." ".maj($store_name)."</i>";
print "</td></tr>";
print "</table>";
?>

</body>
</html>
