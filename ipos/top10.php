<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');


include("../includes/lang/lang_".$_SESSION['lang'].".php");
$title = "TOP 10";
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
?>
<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="<?php echo $_SESSION['css'];?>" type="text/css">
    </head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php include('top.php');?>
<table width="450" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td valign="top" class="TABLEMenuPathTopPage">
                
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" class="TABLEPageCentreProducts">



            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
              <tr>
                <td valign="top">



                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
					       <?php print "<b><img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='index.php?path=0&num=1&action=c'>".maj(HOME)."</a> | TOP 10 |</b>";?>
					  </td>
                    </tr>
                  </table>

                 <br>


<?php
print "<p class='titre'>".DIX_MEILLEURES_VENTES."</p>";

$hids = mysql_query("SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_qt, p.products_viewed, p.categories_id, c.categories_name_".$_SESSION['lang'].", c.parent_id, p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, IF(s.products_id<>'null', 'oui','non') as toto
                     FROM products AS p
                     LEFT JOIN categories AS c
                     ON(p.categories_id = c.categories_id)
                     LEFT JOIN specials AS s
                     ON(p.products_id = s.products_id)
                     WHERE p.products_visible='yes'
                     AND p.products_ref != 'GC100'
                     ORDER BY p.products_viewed
                     DESC
                     LIMIT 0,10");

print "<table border='0' width='95%' cellpadding='4' cellspacing='0' align='center' class='TABLESortByCentre'>";
print "<tr height='30' class='TABLE1' align='center'>";
print "<td width='1'><b>#</b></td>";
print "<td><b>".CATEGORIE."</b></td>";
print "<td><b>".ARTICLE."</b></td>";

if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") print "<td width='1'><b>".PRIX."</b></td>";
print "<td width='1'><b>".DISPO."</b></td>
      </tr>";
$i=0;
while ($myhid = mysql_fetch_array($hids)) {
               // Couleurs ligne tableaux
               if(isset($d) and $d==1) $d=2; else $d=1;
               $i=$i+1;
               
// Vérifier si l'article est en promotion
$new_price = $myhid['specials_new_price'] ;
$old_price = $myhid['products_price'] ;

if($myhid['products_forsale']=="yes") {
	if(empty($new_price)) {
		$comment_start = "";
		$price = $old_price;
		$comment_end = "";
	}
	else {
		if($myhid['specials_visible']=="yes") {
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$dateMaxCheck = explode("-",$myhid['specials_last_day']);
			$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
			$dateDebutCheck = explode("-",$myhid['specials_first_day']);
			$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
			
			if($dateDebut <= $today  AND $dateMax >= $today) {
				$comment_start = "<s>".$old_price."</s> ".$symbolDevise."<br><span class='fontrouge'>";
				$price = $new_price;
				$comment_end = "</span>";
			}
			else {
				$comment_start = "";
				$price = $old_price;
				$comment_end = "";
			}
		}
		else {
			$comment_start = "";
			$price = $old_price;
			$comment_end = "";
		}
	}
}
// end verif


// Check Price
   $productLink = "<a href='beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id']."'>".$myhid['products_name_'.$_SESSION['lang']]."</a>";
   if($myhid['products_forsale']=="yes") { 
      $priceTop10 = $comment_start.$price." ".$symbolDevise.$comment_end;
   }
   else {
      $priceTop10 = "<span class='fontrouge'><b>".OUT_OF_STOCK."</b></span>";
   }

// Check stock   
    if($myhid['products_qt']>0) {
    	$dispo = $openLeg."<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."'></a>";
    }
    else {
    	if($actRes=="oui") {$dispo = $openLeg."<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."'></a>";} else {$dispo = $openLeg."<img src='../im/stockno.gif' border='0' title='".NOT_IN_STOCK."'></a>";}
    }
    if($myhid['products_forsale']=="no") {
         $dispo = $openLeg."<img src='../im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
    }
	// Prix dégressif
	if(in_array($myhid['products_id'], $_SESSION['discountQt']) AND $myhid['products_forsale']=='yes') {
		$prodDegressif = $openLeg."<img src='../im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
	} else {
		$prodDegressif = "";
	}
// Afficher tableau
                 print "<tr height='25' class='TDTableListLine".$d."'>
                         <td>$i</td>
                         <td align='left'><a href='list.php?path=".$myhid['categories_id']."'>".strtolower($myhid['categories_name_'.$_SESSION['lang']])."</a>".$prodDegressif."</td>
                         <td>".$productLink."</td>";
                if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") print "<td width='60'><div align='center'>".$priceTop10."</div></td>";
                print "<td align='center'>".$dispo."</td>
                       </tr>";
                }
print "</table>";
?>

                  </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

<br>

</td>
</tr>
</table>
</body>
</html>
