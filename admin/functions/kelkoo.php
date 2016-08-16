<?php

function build_kelkoo_file($lang,$currency,$update) {
GLOBAL $_POST;

function searchPath($product_name, $cat_name, $parent_id, $lang, $catId) { 
    global $productPath;
    $subCatQuery = mysql_query("SELECT categories_name_".$lang.", parent_id
                                FROM categories
                                WHERE categories_id = '".$parent_id."'
                               ");
    $arrSubCategory = mysql_fetch_array($subCatQuery);
    $subCatParentId = $arrSubCategory['parent_id'];
    $subCatName = $arrSubCategory['categories_name_'.$lang];
            if($subCatParentId == '0') {
                $productPath[$product_name][$catId][] = $subCatName;
            } 
            else {
                $productPath[$product_name][$catId][] = $subCatName;
                searchPath($product_name, $subCatName, $subCatParentId, $lang, $catId);
            }
                        
    $pathReturn = implode(" - ", array_reverse($productPath[$product_name][$catId]));
    $pathReturn = $pathReturn." - ".$cat_name;
    return $pathReturn;                   
} 


function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise) { 
    GLOBAL $_SESSION;
    if($_poids>0) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_zone = "zone1";
         $_tax = $_b['countries_shipping_tax'];
         $_iso = $_b['iso'];

         $_c = mysql_query("SELECT * FROM ship_price
                          WHERE weight >= $_poids
						  ORDER BY weight");
         while ($_d = mysql_fetch_array($_c)) {
                $_sp[] = $_d['weight'];
         }
         
         $_f = mysql_query("SELECT $_zone FROM ship_price
                          WHERE weight = '".$_sp[0]."'");
         $_p = mysql_fetch_array($_f);

         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         if(preg_match ("/\b$_zone\b/i", $zoneZ['free_shipping_zone'])) $gratos = "yes"; else $gratos="no";

         $shipPrice = $_p[$_zone]/$_poids;
         if($activerPromoLivraison == "oui" and $totalHtFinal>=$livraisonComprise and $gratos=="yes") {$shipPrice = sprintf("%0.2f",0);}
         $livraisonhors = sprintf("%0.2f",$shipPrice*$_poids);
         
         if($_b['countries_shipping_tax_active'] == "yes") { $shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
         if(isset($_SESSION['clientTVA']) AND !empty($_SESSION['clientTVA']) AND $_iso !== $originIso) { $shipTax = sprintf("%0.2f",0);}
         return array($shipPrice,$livraisonhors,$shipTax,$gratos);
    }
    else {
         $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",0);
         $shipTax = sprintf("%0.2f",0);
         $gratos="no";
         return array($shipPrice,$livraisonhors,$shipTax,$gratos);
    }
} 



    include('../configuratie/configuratie.php');

    if($lang == "1") {$country="fr";}
    if($lang == "2") {$country="uk";}
    if($lang == "3") {$country="nl";}
    if($displayOutOfStock=="oui" AND $actRes=="oui") {$addToQuery="";} else {$addToQuery = " AND products_qt>'0'";}
    
                        
    
    if(isset($_POST['categKelkoo']) and count($_POST['categKelkoo']) > 0) {
        $catToExport = "";
        $numm = count($_POST['categKelkoo']);
    	    for($i=0; $i<$numm; $i++) {
    	        if($i == 0) {$catToExport .= " categories_id = '".$_POST['categKelkoo'][$i]."' ";}
    	    	  else {$catToExport .= " OR categories_id = '".$_POST['categKelkoo'][$i]."' ";}
    		}
    		$sumEl = "AND (".$catToExport.")";
    }
    else {
        $sumEl = "";
    }

    $Orderquery = mysql_query("SELECT products_garantie_".$lang.", products_name_".$lang.", products_qt, products_desc_".$lang.", products_price, products_image, products_ref, products_id, categories_id, products_weight
                               FROM products
                               WHERE products_visible = 'yes'
                               AND products_ref != 'GC100'
                               AND products_forsale = 'yes'
                               ".$addToQuery."
                               ".$sumEl."
                               ORDER BY products_name_".$lang."");
    
    $OrderqueryNum = mysql_num_rows($Orderquery);
    
    if($OrderqueryNum > 0) {

            while ($arrSelect = mysql_fetch_array($Orderquery)) {

                    
    
                    
                    $titre = strip_tags($arrSelect['products_name_'.$lang]);
                    $titre = trim($titre);
                    $titre = str_replace("\r\n", ". ", $titre);
                    $titre = str_replace("\n", ". ", $titre);
                    $titre = str_replace('"', '', $titre);
                    $titre = str_replace("'", "", $titre);
                    
                    
                    $description = strip_tags($arrSelect['products_desc_'.$lang]);
                    $description = trim($description);
                    $description = str_replace("\t", " ", $description);
                    $description = str_replace("\r\n", " ", $description);
                    $description = str_replace("\n", " ", $description);
                    $description = str_replace('"', '', $description);
                    $description = str_replace("'", "", $description);
                    
                    if(strlen($titre) >= 80) {
                        $title = substr($titre, 0, 79); 
                        $title = substr_replace($title,'...',-3);
                    } 
                    else {
                        $title=$titre;
                    }
                    
                    
                    $catQuery = mysql_query("SELECT categories_name_".$lang.", parent_id
                                               FROM categories
                                               WHERE categories_id = '".$arrSelect['categories_id']."'
                                            ");
                    $arrCategory = mysql_fetch_array($catQuery);
                    $category = searchPath($arrSelect['products_id'], str_replace("-",",",$arrCategory['categories_name_'.$lang]), $arrCategory['parent_id'], $lang, $arrSelect['categories_id']);

                    
                    if(strlen($description) >= 160) {
                        $desc = substr($description, 0, 159); 
                        $desc = substr_replace($desc,'...',-3);
                    } 
                    else {
                        $desc=$description;
                    }
                    
                    $url = "http://".$www2.$domaineFull."/beschrijving.php?lang=".$lang."&id=".$arrSelect['products_id']."&path=".$arrSelect['categories_id'];
                    
                    if(substr($arrSelect['products_image'], 0, 4)=="http") {
                        $image = $arrSelect['products_image'];
                    }
                    else {
                        $image = "http://".$www2.$domaineFull."/".$arrSelect['products_image'];
                    }
                    
                    
                    
                    $discountQuery = mysql_query("SELECT p.products_price, s.specials_new_price, s.specials_first_day
                                     FROM products as p
                                     INNER JOIN specials as s
                                     ON (p.products_id = s.products_id)
                                     WHERE s.products_id = '".$arrSelect['products_id']."'
                                     AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                     AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)
                                     AND s.specials_visible = 'yes'
                                     ");
                    $arrDiscountNum = mysql_num_rows($discountQuery);
                    if($arrDiscountNum > 0) {
                        $arrDiscount = mysql_fetch_array($discountQuery);
                        $prix = sprintf("%0.2f",$arrDiscount['specials_new_price']);
                        $prix = str_replace(".",",",$arrDiscount['specials_new_price']);
                    }
                    else {
                        $prix = sprintf("%0.2f",$arrSelect['products_price']);
                        $prix = str_replace(".",",",$arrSelect['products_price']);
                    }
                    
                        if($activerPromoLivraison == "oui" and $prix>=$livraisonComprise) {
                            $deliveryCost = sprintf("%0.2f",0);
                        }
                        else {
                            $shipping = shipping_price("FRA","France",$arrSelect['products_weight'],$activerPromoLivraison,$prix,$livraisonComprise);
                            $deliveryCost = $shipping[1]+$shipping[2];
                        }
                    
                    $granti = strip_tags($arrSelect['products_garantie_'.$lang]);
                    
                    $offerId = $arrSelect['products_ref'];
                    
                    if($arrSelect['products_qt']>0 ) $availability = '001';
                    if($arrSelect['products_qt']<=0 AND $displayOutOfStock=="oui" AND $actRes=="oui") $availability = '005'; 
                    
                    if($update=="YES") {
                        $arr[] = $url."\t".$title."\t".$desc."\t".$category."\t".$prix."\t".$offerId."\t".$image."\t".$availability."\t".$deliveryCost."\t".$granti."\tYES";
                    }
                    else {
                    
                    
                        $arr[] = $url."\t".$title."\t".$desc."\t".$category."\t".$prix."\t".$offerId."\t".$image."\t".$availability."\t".$deliveryCost."\t".$granti;
            }
            }

                    
                    header("Content-Type: application/csv-tab-delimited-table");
                    header("Content-disposition:attachment; filename=kelkoo_".$country.".txt");
                    echo "#country=".$country."\r\n#type=BASIC\r\n#currency=".$currency."\r\n#update=".$update."\r\n#quoted=NO\r\n";
                    if($update=="YES") {
                        echo "url\ttitle\tdescription\tCategory\tprice\tofferid\timage\tavailability\tdeliverycost\twarranty\tdelete\r\n";
                    }
                    else {
                        echo "url\ttitle\tdescription\tCategory\tprice\tofferid\timage\tavailability\tdeliverycost\twarranty\r\n";
                    }
                    
                    
                    foreach($arr as $elem) {
                        echo "$elem\r\n";
                    }
                    exit;
    }
    else {
    print "<html>
    <head>
    <title></title>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link rel='stylesheet' href='style.css'>
    </head>
    <body leftmargin='0' topmargin='50' marginwidth='0' marginheight='0'>";
    
    print "<p align='center' class='fontrouge'><b>Er zijn geen artikelen aanwezig</b></p>";
    print "</body></html>";
    }
}
?>
