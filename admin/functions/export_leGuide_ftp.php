<?php
function build_leGuide_file($lang) {
GLOBAL $_POST;

if(!empty($_POST['host']) AND !empty($_POST['login']) AND !empty($_POST['pass'])) {

include('../configuratie/configuratie.php');

    $ftpAcces = $_POST['host']."||".$_POST['login']."||".$_POST['pass'];
    
    mysql_query("UPDATE admin SET export_auto_leguide = '".$ftpAcces."'");
    
    $ftp_server = $_POST['host'];
    $ftp_user_name = $_POST['login'];    
    $ftp_user_pass = $_POST['pass'];



if((@ftp_login(ftp_connect($ftp_server), $ftp_user_name, $ftp_user_pass))) {
 

    if($lang == "1") {$country="fr";}
    if($lang == "2") {$country="uk";}
    if($lang == "3") {$country="nl";}

 
    if(isset($_POST['categLeguide']) and count($_POST['categLeguide']) > 0) {
        $catToExport = "";
        $numm = count($_POST['categLeguide']);
    	    for($i=0; $i<$numm; $i++) {
    	        if($i == 0) {$catToExport .= " categories_id = '".$_POST['categLeguide'][$i]."' ";}
    	    	  else {$catToExport .= " OR categories_id = '".$_POST['categLeguide'][$i]."' ";}
    		}
    		$sumEl = "AND (".$catToExport.")";
    }
    else {
        $sumEl = "";
    }
    

    $fileName = "leguide_".$country;
    
 
    $ext = ".txt";
    
    if($displayOutOfStock=="oui" AND $actRes=="oui") {$addToQuery="";} else {$addToQuery = " AND products_qt>'0'";}

 
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
                        
    $pathReturn = implode(">", array_reverse($productPath[$product_name][$catId]));
    $pathReturn = $pathReturn.">".$cat_name;
    return $pathReturn;                   
}
 

 
function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise) {
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
         
         $_f = mysql_query("SELECT $_zone FROM ship_price WHERE weight = '".$_sp[0]."'");
         $_p = mysql_fetch_array($_f);

         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         if(preg_match ("/\b$_zone\b/i", $zoneZ['free_shipping_zone'])) $gratos = "yes"; else $gratos="no";

         $shipPrice = $_p[$_zone]/$_poids;
         if($activerPromoLivraison == "oui" and $totalHtFinal>=$livraisonComprise and $gratos=="yes") {$shipPrice = sprintf("%0.2f",0);}
         $livraisonhors = sprintf("%0.2f",$shipPrice*$_poids);
         
         if($_b['countries_shipping_tax_active'] == "yes") { $shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
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



    if($displayOutOfStock=="oui" AND $actRes=="oui") {$addToQuery="";} else {$addToQuery = " AND products_qt>'0'";}
    
    $Orderquery = mysql_query("SELECT products_name_".$lang.", products_desc_".$lang.", products_price, products_image, products_ref, products_id, categories_id, products_qt, fabricant_id, products_weight
                               FROM products
                               WHERE products_visible = 'yes'
                               AND products_ref != 'GC100'
                               AND products_forsale = 'yes'
                               ".$addToQuery."
                               ".$sumEl."
                               ORDER BY products_name_".$lang."");
    
    $OrderqueryNum = mysql_num_rows($Orderquery);
    
     
    $file = "functions/".$fileName.$ext;
    $fp=fopen($file ,"wb");
    
     
    $source_file = $file;
    $destination_file = "catalogues/".$fileName.$ext;
    
     
    $columns =  "manufacturer\tmerchant category\toffer_id\tname\tdescription\tregular price\tproduct url\timage url\tdiscount price\tcurrency\tprice discounted from\tsales\tavailability\tsf\r\n";
    fwrite($fp, $columns);

    if($OrderqueryNum > 0) {

            while ($arrSelect = mysql_fetch_array($Orderquery)) {

                     
                     
                    $marqueQuery = mysql_query("SELECT fournisseurs_company
                                               FROM fournisseurs
                                               WHERE fournisseurs_id = '".$arrSelect['fabricant_id']."'
                                            ");
                    $arrMarque = mysql_fetch_array($marqueQuery);
                    $marque = $arrMarque['fournisseurs_company'];
                     
                    $titre = strip_tags($arrSelect['products_name_'.$lang]);
                    $titre = trim($titre);
                    $titre = str_replace("\r\n", ". ", $titre);
                    $titre = str_replace("\n", ". ", $titre);
                    $titre = str_replace("/t", " ", $titre);
                    $titre = str_replace(">", "-", $titre);
                    $titre = str_replace(";", ",", $titre);
                    $titre = str_replace('"', '', $titre);
                    $titre = str_replace("'", "", $titre);
                    
                    $description = strip_tags($arrSelect['products_desc_'.$lang]);
                    $description = trim($description);
                    $description = str_replace("\t", " ", $description);
                    $description = str_replace("\r\n", ". ", $description);
                    $description = str_replace("\n", ". ", $description);
                    $description = str_replace("/t", " ", $description);
                    $description = str_replace(">", "-", $description);
                    $description = str_replace(";", ",", $description);
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
                    $category = searchPath($arrSelect['products_id'], $arrCategory['categories_name_'.$lang], $arrCategory['parent_id'], $lang, $arrSelect['categories_id']);
                    
                     
                    if(strlen($description) >= 249) {
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
                     
                    $prix = sprintf("%0.2f",$arrSelect['products_price']);
                     
                    $offerId = $arrSelect['products_ref'];
                     
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
                        $discount = sprintf("%0.2f",$arrDiscount['specials_new_price']);
                        $from = $arrDiscount['specials_first_day']." 00:00";
                        $sale = 2;
                    }
                    else {
                        $discount = "";
                        $from = "";
                        $sale = 0;
                    }
                     
                    if($discount == "") {$prixFinal = $prix;} else {$prixFinal = $discount;}
                    if($activerPromoLivraison == "oui" and $prixFinal>=$livraisonComprise) {
                        $sf = sprintf("%0.2f",0);
                    }
                    else {
                        $shipping = shipping_price("FRA","France",$arrSelect['products_weight'],$activerPromoLivraison,$prix,$livraisonComprise);
                        $sf = $shipping[1]+$shipping[2];
                    }
                     
                    if($arrSelect['products_qt']>0 ) $availability = 0;
                    if($arrSelect['products_qt']<=0 AND $displayOutOfStock=="oui" AND $actRes=="oui") $availability = 8; // disponibilité du produit (en jours) - ( 0 si disponible, sinon valeur numérique correspondant au nombre de jours maximum avant que le produit ne soit disponible ).
                    
                     
                    $arr[]  = $marque."\t".$category."\t".$offerId."\t".$title."\t".$desc."\t".$prix."\t".$url."\t".$image."\t".$discount."\t".strtolower($devise)."\t".$from."\t".$sale."\t".$availability."\t".$sf."\r\n";
            }
                     

                    foreach($arr as $elem) {
                        fwrite($fp, $elem);
                    }
                     

    }


 
    
    $conn_id = ftp_connect($ftp_server);
    
 
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    
     
    $upload = ftp_put($conn_id, $destination_file, $source_file, FTP_BINARY); 
    
     
    ftp_close($conn_id); 
    
        print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE3'><tr><td>
        <p align='center' class='fontrouge'><b>EXPORTATION RÉUSSI !<BR>VOTRE FICHIER EST À JOUR CHEZ LEGUIDE.COM S.A.</b></p>
        </td></tr></table>
        ";
}
else {
        print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE3'><tr><td>
        <p align='center' class='fontrouge'><b>ERREUR CONNEXION FTP !<BR>VEUILLEZ VÉRIFIER VOS ACCÈS ET RECOMMENCER.</b></p>
        </td></tr></table>
        ";
}
}
else {
        print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE3'><tr><td>
        <p align='center' class='fontrouge'><b>VEUILLEZ VÉRIFIER VOS ACCÈS ET RECOMMENCER.</b></p>
        </td></tr></table>
        ";
}
}
?>
