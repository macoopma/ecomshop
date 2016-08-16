<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$tt="100%";
$promoIs="";
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
$largeBoutonCaddie = 60;

 
$activeRoundedCornersDesc = 'no';


function express_buy($priceZ,$esp) {
   GLOBAL $minimumOrder, $_SESSION;
   print "<div><img src='im/zzz.gif' width='1' height='".$esp."'></div>";
   if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
     $priceZ = newPrice($priceZ,$_SESSION['reduc']);
   }
   if($priceZ > $minimumOrder) {
      print "<input type='image' src='im/lang".$_SESSION['lang']."/achat_express.png' value='".ACHAT_EXPRESS."' name='express' style='BACKGROUND:none; border:0px;' title='".ACHAT_EXPRESS."' alt='".ACHAT_EXPRESS."'>";
   }
}

 
function rand_color() {
	$a = dechex(mt_rand(175,240));
	$hexa = $a.$a.$a;
	return $hexa;
}

 
function afficher_addToFavori__sendToFriend() {
	GLOBAL $_GET, $ww, $addBookmark, $store_name, $rowp, $www2, $domaineFull, $defaultOrder;
 
	print "<a href='javascript:void(0);' onClick=\"window.open('verstuur_kennis.php?fromUrl=".$ww."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=420,width=500,toolbar=no,scrollbars=no,resizable=yes');\">";
	print "<img src='im/sendToFriend.png' border='0' alt='".ENVOYER_A_UN_AMI."' title='".ENVOYER_A_UN_AMI."'>";
	print "</a>";
	 
	if($addBookmark=="oui") {
		print "&nbsp;<a href='javascript:void(0);' onClick=\"add_favoris('".$store_name."','".$rowp['products_name_'.$_SESSION['lang']]."','http://".$www2.$domaineFull."/beschrijving.php?lang=".$_SESSION['lang']."&sort=".$defaultOrder."&id=".$_GET['id']."')\">";
		print "<img src='im/addToFavoris.png' border='0' alt='".AJOUTER_FAVORIS."' title='".AJOUTER_FAVORIS."'>";
		print "</a>";
	}
}

 
if(!isset($_GET['id']) OR $_GET['id']=='') {
   $pathQuery = mysql_query("SELECT products_id, categories_id
                              FROM products 
                              WHERE products_ref != 'GC100'
                              AND products_visible = 'yes'
                              AND products_forsale = 'yes'
                              ORDER BY rand() limit 1") or die (mysql_error());
   if(mysql_num_rows($pathQuery)>0) {
      $pathResult = mysql_fetch_array($pathQuery);
         $_GET['id'] = $pathResult['products_id'];
         $_GET['path'] = $pathResult['categories_id'];
         $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?id=".$_GET['id']."&path=".$_GET['path'];
   }
   else {
      header("Location: cataloog.php");
   }
}

 
if(!isset($_GET['path'])) {
    $queryGetPath = mysql_query("SELECT categories_id FROM products WHERE products_id = '".$_GET['id']."'");
    $getPathw = mysql_fetch_array($queryGetPath);
    $_GET['path'] = $getPathw['categories_id'];
}

 
if($_SERVER['REQUEST_URI']) {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER['REQUEST_URI'];
}
else {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
}

if(!isset($_GET['a']) AND isset($_GET['id']) AND ($lastViewVisible=="oui" OR $lastViewCartVisible=="oui")) {
   mysql_query("UPDATE products SET products_viewed = products_viewed+1 WHERE products_id='".$_GET['id']."'");
}

 
if($lastViewVisible=="oui" OR $lastViewCartVisible=="oui") {
	if(isset($_SESSION['lastView'])) {
		if(!preg_match("#z".$_GET['id']."z#",$_SESSION['lastView'])) {
			$_SESSION['lastView'].= "z".$_GET['id']."z";
		}
		else {
			$_SESSION['lastView'] = str_replace($_GET['id']."z", "", $_SESSION['lastView']);
			$_SESSION['lastView'].= "z".$_GET['id'];
		}
	}
	else {
		$_SESSION['lastView'] = "z".$_GET['id']."z";
	}
	$_SESSION['lastView'] = str_replace("zz","z",$_SESSION['lastView']);
}

  
        if(isset($_GET['sort'])) {
            if($_GET['sort']=="id") {$classBy="products_id";}
            if($_GET['sort']=="Id") {$classBy="products_id";}
            if($_GET['sort']=="Ref") {$classBy="products_ref";}
            if($_GET['sort']=="Artikel") {$classBy="products_name_".$_SESSION['lang'];}
            if($_GET['sort']=="Prix") {$classBy="products_price";}
            if($_GET['sort']=="Compagnie") {$classBy="fournisseurs_id";}
            if($_GET['sort']=="Les_plus_populaires") {$classBy="products_viewed";}
            if($_GET['sort']=="specials_last_day") {$classBy="specials_last_day";}
        }
        else {
            $_GET['sort'] = ($defaultOrder == "Artikel") ? "Artikel" : $defaultOrder;
            if($_GET['sort']=="id") {$classBy="products_id";}
            if($_GET['sort']=="Id") {$classBy="products_id";}
            if($_GET['sort']=="Ref") {$classBy="products_ref";}
            if($_GET['sort']=="Artikel") {$classBy="products_name_".$_SESSION['lang'];}
            if($_GET['sort']=="Prix") {$classBy="products_price";}
            if($_GET['sort']=="Compagnie") {$classBy="fournisseurs_id";}
            if($_GET['sort']=="Meest gezien") {$classBy="products_viewed";}
            if($_GET['sort']=="specials_last_day") {$classBy="specials_last_day";}
            
            if(isset($_GET['target']) AND $_GET['target'] == "promo") {
               $_GET['sort'] = "specials_last_day";
               $classBy = "specials_last_day";
            }
        }
        
        $asc = "DESC";
        if($classBy == "products_name_".$_SESSION['lang']) $asc = "ASC";
        if($classBy == "products_viewed") $asc = "ASC";
        
        if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
        if(isset($productsIntoCategory)) unset($productsIntoCategory);
        if(isset($select)) unset($select);
        
   if(isset($_GET['path'])) {
        $queryPath = mysql_query("SELECT p.products_id
                                  FROM products as p
                                  LEFT JOIN specials as s
                                  ON (p.products_id = s.products_id)
                                  WHERE p.categories_id = '".$_GET['path']."'
                                  AND p.products_visible = 'yes'
                                  OR p.categories_id_bis LIKE '%|".$_GET['path']."|%'
                                  ".$addToQuery."
                                  ORDER BY ".$classBy." ".$asc);
        $queryPathNum = mysql_num_rows($queryPath);
    }

   if(isset($_GET['target'])) {
         
        if($_GET['target']=="promo") {
               if(isset($_GET['view'])) {
                            if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                                 $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                                 $AddQuery = " AND p.categories_id = ".$_GET['view']." OR p.categories_id_bis LIKE '%|".$_GET['view']."|%'";                                 
                            } else {
                                 $addQueryFlash = "";
                                 $AddQuery = " AND p.categories_id = ".$_GET['view']." OR p.categories_id_bis LIKE '%|".$_GET['view']."|%'";
                            }
                }
                else {
                            if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                                 $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                                 $AddQuery = "";
                            } 
                            else {
                                 $addQueryFlash = "";
                                 $AddQuery = "";
                            }
                }
                $selectP = "SELECT p.products_id,
                                           IF(s.specials_new_price<p.products_price
                                           AND s.specials_last_day >= NOW()
                                           AND s.specials_first_day <= NOW(),
                                           s.specials_new_price, p.products_price) as ord
                                    FROM products as p
                                    INNER JOIN specials as s
                                    ON (p.products_id = s.products_id)
                                    LEFT JOIN categories as c
                                    ON (p.categories_id = c.categories_id)
                                    LEFT JOIN fournisseurs as f
                                    ON (p.fournisseurs_id = f.fournisseurs_id)
                                    WHERE s.specials_visible='yes'
                                    AND p.products_visible='yes'
                                    AND c.categories_visible='yes'
                                    AND p.products_ref != 'GC100'
                                    ".$addToQuery."
                                    ".$addQueryFlash."
                                    AND p.products_forsale='yes'
                                    AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                    AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)";
            
                $select = $selectP." ".$AddQuery;
                $asc = "ASC";
                $amb="p.";
                if($classBy == "products_id") $asc = "DESC";
                if($classBy == "products_ref") $asc = "DESC";
                if($classBy == "products_name_".$_SESSION['lang']) $asc = "DESC";
                if($classBy == "products_price") $asc = "ASC";
                if($classBy == "fournisseurs_id") $asc = "DESC";
                if($classBy == "products_viewed") $asc = "DESC";
                if($classBy == "specials_last_day") {$asc = "ASC"; $amb="s.";}
                
                $select.= " ORDER BY ".$amb.$classBy.", s.specials_first_day ".$asc;
                $queryPath = mysql_query($select) or die (mysql_error());
                $queryPathNum = mysql_num_rows($queryPath);
        }
         
        if($_GET['target']=="new") {
               if(isset($_GET['view'])) {
                       $AddQuery = " AND p.categories_id = ".$_GET['view']." OR categories_id_bis LIKE '%|".$_GET['view']."|%'";
                }
                else {
                       $AddQuery = "";
                }
                $selectP = "SELECT p.products_id,
                                           IF(s.specials_new_price<p.products_price
                                           AND s.specials_last_day >= NOW()
                                           AND s.specials_first_day <= NOW(),
                                           s.specials_new_price, p.products_price) as ord
                                    FROM products as p
                                    LEFT JOIN specials as s
                                    ON (p.products_id = s.products_id)
                                    LEFT JOIN fournisseurs as f
                                    ON (p.fournisseurs_id = f.fournisseurs_id)
                                    LEFT JOIN categories as c
                                    ON (p.categories_id = c.categories_id)
                                    WHERE TO_DAYS(NOW()) - TO_DAYS(p.products_date_added) <= '".$nbre_jour_nouv."'
                                    AND p.products_visible='yes'
                                    AND p.products_ref != 'GC100'
                                    ".$addToQuery."
                                    AND c.categories_visible='yes'
                                    ";
                $select = $selectP." ".$AddQuery;
                $asc = "ASC";
                if($classBy == "products_id") $asc = "DESC";
                if($classBy == "products_ref") $asc = "DESC";
                if($classBy == "products_name_".$_SESSION['lang']) $asc = "DESC";
                if($classBy == "products_price") $asc = "DESC";
                if($classBy == "fournisseurs_id") $asc = "DESC";
                if($classBy == "products_viewed") $asc = "ASC";
                if($classBy == "specials_last_day") $asc = "ASC";
                
                $select .= " ORDER BY ".$classBy." ".$asc;
                $queryPath = mysql_query($select);
                $queryPathNum = mysql_num_rows($queryPath);
        }
         
        if($_GET['target']=="favorite") {
               if(isset($_GET['view'])) {
                       $AddQuery = " AND p.categories_id = ".$_GET['view']."  ";
               }
               else {
                       $AddQuery = "";
              }
               $selectP = "SELECT p.products_id,
                                           IF(s.specials_new_price<p.products_price
                                           AND s.specials_last_day >= NOW()
                                           AND s.specials_first_day <= NOW(),
                                           s.specials_new_price, p.products_price) as ord
                                    FROM products as p
                                    LEFT JOIN specials as s
                                    ON (p.products_id = s.products_id)
                                    LEFT JOIN fournisseurs as f
                                    ON (p.fournisseurs_id = f.fournisseurs_id)
                                    LEFT JOIN categories as c
                                    ON (p.categories_id = c.categories_id)
                                    WHERE p.products_visible='yes'
                                    ".$addToQuery."
                                    AND c.categories_visible='yes'
                                    AND p.products_exclusive = 'yes'
                                    AND p.products_ref != 'GC100'
                                    ";
                $select = $selectP." ".$AddQuery;
                $asc = "ASC";
                if($classBy == "products_id") $asc = "DESC";
                if($classBy == "products_ref") $asc = "DESC";
                if($classBy == "products_name_".$_SESSION['lang']) $asc = "ASC";
                if($classBy == "products_price") $asc = "DESC";
                if($classBy == "fournisseurs_id") $asc = "DESC";
                if($classBy == "products_viewed") $asc = "ASC";
                if($classBy == "specials_last_day") $asc = "ASC";
                
                $select .= " ORDER BY p.".$classBy." ".$asc;
                $queryPath = mysql_query($select);
                $queryPathNum=mysql_num_rows($queryPath);
        }
         
        if($_GET['target'] == "author") {
                $AddQuery = " AND p.fabricant_id = ".$_GET['authorid']." "; 
                $selectP = "SELECT p.products_id,
                                           IF(s.specials_new_price<p.products_price
                                           AND s.specials_last_day >= NOW()
                                           AND s.specials_first_day <= NOW(),
                                           s.specials_new_price, p.products_price) as ord
                                    FROM products as p
                                    LEFT JOIN specials as s
                                    ON (p.products_id = s.products_id)
                                    LEFT JOIN fournisseurs as f
                                    ON (p.fournisseurs_id = f.fournisseurs_id)
                                    LEFT JOIN categories as c
                                    ON (p.categories_id = c.categories_id)
                                    WHERE p.products_visible='yes'
                                    ".$addToQuery."
                                    AND c.categories_visible='yes'
                                    AND p.products_ref != 'GC100'
                                    ";
                $select = $selectP." ".$AddQuery;
                $asc = "ASC";
                if($classBy == "products_id") $asc = "DESC";
                if($classBy == "products_ref") $asc = "DESC";
                if($classBy == "products_name_".$_SESSION['lang']) $asc = "ASC";
                if($classBy == "products_price") $asc = "DESC";
                if($classBy == "fournisseurs_id") $asc = "DESC";
                if($classBy == "products_viewed") $asc = "ASC";
                if($classBy == "specials_last_day") $asc = "ASC";
                
                $select .= " ORDER BY ".$classBy." ".$asc;
                $queryPath = mysql_query($select);
                $queryPathNum=mysql_num_rows($queryPath);
        }
   }
   
         
        if(isset($_GET['advCat']) OR isset($_GET['advComp']) OR isset($_GET['search_query'])) {
        $addQuery55 = "";
        if(isset($_GET['search_query'])) {
            $ChercherSeparateur = explode("!",$_GET['search_query']);
            $countChercherSeparateur = count($ChercherSeparateur);
                   if($countChercherSeparateur > 1) {
                        for($i=0; $i<=$countChercherSeparateur-1; $i++) {
                          $addQuery55 .=" AND (
                                      p.products_desc_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_ref like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_name_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR f.fournisseurs_company like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_ean like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_garantie_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_note_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'";
                          $addQuery55 .=")";
                        }
                   }
                   else {
                         $addQuery55 = " AND (
                                      p.products_desc_".$_SESSION['lang']." like '%".$_GET['search_query']."%'
                                      OR p.products_ref like '%".$_GET['search_query']."%%'
                                      OR p.products_name_".$_SESSION['lang']." like '%".$_GET['search_query']."%%'
                                      OR f.fournisseurs_company like '%".$_GET['search_query']."%%'
                                      OR p.products_ean like '%".$_GET['search_query']."%%'
                                      OR p.products_garantie_".$_SESSION['lang']." like '%".$_GET['search_query']."%%'
                                      OR p.products_note_".$_SESSION['lang']." like '%".$_GET['search_query']."%%'";
                         $addQuery55 .=")";
                   }
            }
                    if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
                        $addQuery55 .= " AND p.categories_id = ".$_GET['advCat'];;
                    }
                    if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
                        $addQuery55 .= " AND p.fabricant_id = ".$_GET['advComp'];
                    }
                    
                    if(isset($_GET['advCat'])) {
                        $addQuery55p = " OR p.categories_id_bis LIKE '%|".$_GET['advCat']."|%'";
                        if(isset($_GET['search_query']) AND !empty($_GET['search_query'])) $addQuery55p = '';
                        $addQuery55 .= $addQuery55p;
                    }
                    
            $queryVar55 = "SELECT p.products_id,
                                           IF(s.specials_new_price < p.products_price
                                           AND s.specials_last_day >= NOW()
                                           AND s.specials_first_day <= NOW(),
                                           s.specials_new_price, p.products_price) as ord
                                 FROM products as p
                                 LEFT JOIN categories as c
                                 ON (p.categories_id = c.categories_id)
                                 LEFT JOIN fournisseurs as f
                                 ON (p.fournisseurs_id = f.fournisseurs_id)
                                 LEFT JOIN specials as s
                                 ON (p.products_id = s.products_id)
                                 WHERE p.products_visible = 'yes'
                                 ".$addQuery55;

            $queryVar55 .= " ORDER BY ".$classBy." DESC";
            $queryPath = mysql_query($queryVar55);
            $queryPathNum = mysql_num_rows($queryPath);
        }

         
        if(isset($queryPathNum) AND $queryPathNum>0) {
            while($resultPath = mysql_fetch_array($queryPath)) {
                $productsIntoCategory[] = $resultPath['products_id'];
            }
            if($_GET['sort']!==$defaultOrder AND $_GET['sort']!=="specials_last_day") {
				$productsIntoCategory = array_reverse($productsIntoCategory);
				if(isset($_GET['target']) AND $_GET['target']=="promo") $productsIntoCategory = array_reverse($productsIntoCategory);
			}
        }


 
    if($displayNextProduct=="oui" AND isset($productsIntoCategory) AND in_array($_GET['id'],$productsIntoCategory)) {
        $productsIntoCategoryNum = count($productsIntoCategory);
        if($productsIntoCategoryNum>1) {
            $displayNav = "yes";
            $firstProduct = $productsIntoCategory[0];
            $lastProduct = end($productsIntoCategory);
            $firstArray = 0;
            $lastArray = $productsIntoCategoryNum;
            $currentKey = array_search($_GET['id'],$productsIntoCategory);
            
            
            if(isset($_GET['id']) AND $_GET['id']!==$firstProduct) $prevArray = $productsIntoCategory[$currentKey-1]; else $prevArray="";
            if(isset($_GET['id']) AND $_GET['id']!==$lastProduct) $nextArray = $productsIntoCategory[$currentKey+1]; else $nextArray="";

            if(isset($_GET['id']) AND $_GET['id']!==$firstProduct) {
                $prevUrl = str_replace("id=".$_GET['id'],"id=".$prevArray,$_SESSION['ActiveUrl']);
                $prev = "<a href='".$prevUrl."'><img src='im/f_prev.png' alt='".PRECEDENT."' title='".PRECEDENT."' border='0'></a>";
            }
            else {
                $prev="<img src='im/f_stop_left.png'>";
            }
            
            if(isset($_GET['id']) AND $_GET['id']!==$lastProduct) {
                $nextUrl = str_replace("id=".$_GET['id'],"id=".$nextArray,$_SESSION['ActiveUrl']);
                $next = "<a href='".$nextUrl."'><img src='im/f_next.png' alt='".SUIVANTTT."' title='".SUIVANTTT."' border='0'></a>";
            }
            else {
                $next="<img src='im/f_stop_right.png'>";
            }
            
            function displayNavBetweenProducts() {
                GLOBAL $prev, $next;
                print $prev."<img src='im/zzz.gif' width='3' height='1'>".$next;
            }
        }
    }
 

   
   $result = mysql_query("SELECT p.products_delay_1, p.products_delay_2, p.products_delay_1a, p.products_delay_2a, p.products_delay_1b, p.products_delay_2b, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_exclusive, p.fabricant_id, p.afficher_fabricant, p.products_garantie_".$_SESSION['lang'].", p.products_options, p.products_id, p.categories_id, p.fournisseurs_id, p.afficher_fournisseur, p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_weight, p.products_note_".$_SESSION['lang'].", p.products_ref, p.products_im, p.products_image, p.products_image2, p.products_image3, p.products_image4, p.products_image5, p.products_option_note_".$_SESSION['lang'].", p.products_visible, p.products_taxable, p.products_tax, p.products_date_added, p.products_qt, p.products_viewed, p.products_qt, p.products_download, p.products_related, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, f.fournisseurs_link, c.categories_name_".$_SESSION['lang'].", c.categories_id, c.parent_id
                          FROM products as p
                          LEFT JOIN specials as s
                          ON (p.products_id = s.products_id)
                          LEFT JOIN fournisseurs as f
                          ON (p.fournisseurs_id = f.fournisseurs_id)
                          LEFT JOIN categories as c
                          ON (p.categories_id = c.categories_id)
                          WHERE p.products_id = '".$_GET['id']."'
                          AND products_visible = 'yes'");
   $resultNum = mysql_num_rows($result);
   $rowp = mysql_fetch_array($result);
   
 
if($rowp['products_forsale']=="no") $discontinue = "<div class='PromoFontColorNumber'><b>".OUT_OF_STOCK."</b></div>"; else  $discontinue="";

if(isset($_GET['path'])) {
   $result3 = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['path']."'");
   $cat_name3 = mysql_fetch_array($result3);
}
else {
   $result3 = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$rowp['parent_id']."'");
   $cat_name3 = mysql_fetch_array($result3);
   $path = $rowp['parent_id'];
}


if(!isset($_GET['page']) OR $_GET['page']=="") $page=0; else $page=$_GET['page'];
if(!isset($_GET['sort'])) $sort=$defaultOrder; else $sort = $_GET['sort'];

if(!isset($_GET['target'])) {
    $categorie = $cat_name3['categories_name_'.$_SESSION['lang']];
    $catTitle = getSubCatId($_GET['path']);
    $BarreMenuHautTitre = getSubCatName($catTitle)." > ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) AND $_GET['target'] == "new") {
    $categorie = NOUVEAUTESMAJ;
    $BarreMenuHautTitre = NOUVEAUTES. " > ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) AND $_GET['target'] == "promo") {
	$categorie = maj(PROMOTIONS);
    $BarreMenuHautTitre = PROMOTIONS." > ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) AND $_GET['target'] == "author") {
    $categorie = $cat_name3['categories_name_'.$_SESSION['lang']];
    $BarreMenuHautTitre = $categorie." > ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) AND $_GET['target'] == "favorite") {
    $categorie = maj(COEUR);
    $BarreMenuHautTitre = COEUR." > ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
    $categorie = maj(VENTES_FLASH);
    $BarreMenuHautTitre = $categorie." > ".$rowp['products_name_'.$_SESSION['lang']];
}

$title = strip_tags($BarreMenuHautTitre);

 
if(isset($_GET['id'])) {
    $findCatFromProductQuery = mysql_query("SELECT products_meta_title_".$_SESSION['lang'].", products_meta_description_".$_SESSION['lang'].", products_desc_".$_SESSION['lang'].", categories_id, products_name_".$_SESSION['lang']." 
											FROM products 
											WHERE products_id = '".$_GET['id']."'");
    $findCatFromProductResult = mysql_fetch_array($findCatFromProductQuery);
    
    $idPath = $findCatFromProductResult['categories_id'];
    $idName = strip_tags($findCatFromProductResult['products_name_'.$_SESSION['lang']]);
    if($findCatFromProductResult['products_desc_'.$_SESSION['lang']] !== "") {
         $carToDel = array("\t","\r\n","\n","/t",'"',"'");
         $metaDesc = strip_tags($findCatFromProductResult['products_desc_'.$_SESSION['lang']]);
         $metaDesc = str_replace($carToDel, " ", $metaDesc);
         $metaDesc = (strlen($metaDesc) >= 200)? " | ".trim(substr($metaDesc, 0, 199)) : " | ".trim($metaDesc);
    }
    else {
         $metaDesc = "";
    }
    
    $catTitle20 = getSubCatId($idPath);
    $titrePath = getSubCatName($catTitle20);

    $keysFromTitle = $idName.", ".str_replace(" | ",", ",$titrePath).", ".$store_name;
    $descFromTitle = $idName." ".str_replace(' | ',' ',$titrePath).$metaDesc;

    $description = strip_tags($descFromTitle);
    $keywords = strip_tags($keysFromTitle);

	if($findCatFromProductResult['products_meta_title_'.$_SESSION['lang']] !=="") $title = $findCatFromProductResult['products_meta_title_'.$_SESSION['lang']];
	if($findCatFromProductResult['products_meta_description_'.$_SESSION['lang']] !=="") $description = $findCatFromProductResult['products_meta_description_'.$_SESSION['lang']];
}
?>

<html>

<head>
<?php
include('includes/hoofding.php');
print "<script type='text/javascript' src='js/prototype.js' mce_src='js/prototype.js'></script>";
print "<script type='text/javascript' src='js/scriptaculous.js?load=effects'></script>";

if($lightbox=="oui") {
	print "<script type='text/javascript' src='js/lightbox.js' mce_src='js/lightbox.js'></script>";
	print "<link rel='stylesheet' href='css/lightbox.css' mce_href='css/lightbox.css' type='text/css' media='screen' />";
}
?>

<script type="text/javascript">  
	function showSpan(div) {
		if(document.getElementById(div).style.display == 'none') {
			document.getElementById(div).style.display = 'block';
		}
		else {
			document.getElementById(div).style.display = 'none';
		}
	}
</script>

<script type="text/javascript"><!--
	function displayImage(im_aj, prodName_aj) {
		if(typeof(im_aj)!="undefined" && im_aj!=="") {
			var lightbox = "<?php print $lightbox;?>";
			var lang = "<?php print $_SESSION['lang'];?>";
			var xhr = null; 
			if (window.XMLHttpRequest) {
				xhr=new XMLHttpRequest();
			}
			else {
				xhr=new ActiveXObject("Microsoft.XMLHTTP");
			}

			xhr.onreadystatechange=function() {
				if (xhr.readyState==4 && xhr.status==200) {
					document.getElementById('div_im').style.display='block';
					document.getElementById('div_im1').style.display='none';
					document.getElementById('div_miniIm').style.display='none';
					document.getElementById("div_im").innerHTML = xhr.responseText; 
					if(lightbox=='oui') initLightbox();
				}
			}
			xhr.open('GET', "includes/ajax1.php?ajax_im="+im_aj+"&ajax_name="+prodName_aj+"&lang="+lang, true);
			xhr.send(null);

		}
		else {
			document.getElementById('div_im').style.display='none';
			document.getElementById('div_im1').style.display='block';
			document.getElementById('div_miniIm').style.display='block';
		}
	}
--></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">




<?php
 
include('includes/geen_script.php');
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}

if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;
?>

      <?php if($tableDisplay=='oui') {?>
      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
      <tr height="32">
      <?php if($tableDisplayLeft=='oui') {?>
       <td align="left">
       <b>
       <?php
          if(isset($_GET['target']) AND !isset($_GET['tow'])) $targetF = $_GET['target']; 
          if(isset($_GET['target']) AND isset($_GET['tow'])) $targetF = $_GET['tow'];
          if(!isset($_GET['target'])) $targetF = "";
          if(isset($_GET['action'])) $actionF = $_GET['action']; else $actionF = "e";
          if(!isset($_GET['path'])) $pathF = ""; else $pathF = $_GET['path'];
          getPath2($pathF,"top",$catId=0,$_SESSION['tree2'],$actionF,$targetF,$_SESSION['lang']);
          print maj($rowp['products_name_'.$_SESSION['lang']]);
       ?>
       </b>
       </td>
       <?php 
       }
       if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
       </tr>
       </table>
      <?php }?>

<?php include('includes/promo_afbeelden.php');?>

</td>
</tr>
</table>


<table width="100%" align="center" border="0" cellpadding="3" cellspacing="5">
<tr>
          <?php
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

<?php 
if($resultNum>0) {
 
?>
            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr height="10">
                <td valign="top">
<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td align="left">
<?php
							if(isset($_GET['target']) AND !isset($_GET['tow'])) $targetF = $_GET['target']; 
							if(isset($_GET['target']) AND isset($_GET['tow'])) $targetF = $_GET['tow'];
							if(!isset($_GET['target'])) $targetF = "";
							if(isset($_GET['action'])) $actionF = $_GET['action']; else $actionF = "e";
							if(!isset($_GET['path'])) $pathF = ""; else $pathF = $_GET['path'];
							getPath2($pathF,"bas",$catId=0,$_SESSION['tree2'],$actionF,$targetF,$_SESSION['lang']);
							print maj($rowp['products_name_'.$_SESSION['lang']]);
?>
                      </td>
                    </tr>
                  </table>
                  <br>
<?php
}
$refdet = maj($rowp['products_ref']);

 
		if(!empty($rowp['specials_new_price'])) {
			if($rowp['specials_visible']=="yes") {
				$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				
				$dateMaxCheck = (!empty($rowp['specials_last_day']))? explode("-",$rowp['specials_last_day']) : explode("-","0-0-0");
				$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
				$dateDebutCheck = (!empty($rowp['specials_first_day']))? explode("-",$rowp['specials_first_day']) : explode("-","0-0-0");
				$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
				
				if($dateDebut <= $today  AND $dateMax >= $today) {
					$delayPassed = "no";
					$itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
					$promoIs = "yes";
				}
				else {
					$delayPassed = "yes";
					$EnPromo = "";
					$promoIs = "no";
				}
			}
			else {
				$EnPromo = "";
				$promoIs = "no";			
			}
		}
		else {
			$EnPromo = "";
			$promoIs = "no";
		}

		 
		if(isset($delayPassed) AND $delayPassed == "no" AND $rowp['products_forsale']=="yes") {
			$itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
			$jour = ($itMiss>1)? JOURS." ": JOURS;                      
			$addDay = $dateMaxCheck[2]+1;
			$endPromotion = $dateMaxCheck[1]."/".$addDay."/".$dateMaxCheck[0]." 00:00 AM";
			$econ = $rowp['products_price'] - $rowp['specials_new_price'];
			$econ = sprintf("%0.2f",$econ);
			$econPourcent = (1-($rowp['specials_new_price']/$rowp['products_price']))*100;
			$econPourcent = sprintf("%0.2f",$econPourcent)."%";
?>

<script type="text/javascript">
TargetDate = "<?php print $endPromotion;?>";
BackColor = "";
ForeColor = "#000000";
CountActive = true;
DisplayFormat = "%%D%% <?php print strtolower($jour);?> %%H%%:%%M%%:%%S%%";
FinishMessage = "Stop !";
</script>

<?php
			$aa = '<script type="text/javascript" src="includes/aftellen.js"></script>';
			$EnPromoBasic = "<br><table border='0' width='250' cellpadding='5' cellspacing='0' class='TABLE1' align='center'>";
			$EnPromoBasic.= "<tr>";
			$EnPromoBasic.= "<td align='center'>";
			$EnPromoBasic.= "<span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise." ".DE_REDUCTION." **</b></span>";
			$EnPromoBasic.= "</td>";
			$EnPromoBasic.= "</tr>";
			$EnPromoBasic.= "</table>";
        
	        if($itMiss > $seuilPromo) {
	                $EnPromo = $EnPromoBasic;   
	        }
	        else {
	            if($activeSeuilPromo == "oui") {
	                $EnPromo = "<br>".EN_PROMOTION_JUSQU_AU." ".date("d M Y",mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]))."<br>";
	                $EnPromo.= "<table border='0' width='250' cellpadding='5' cellspacing='0' class='TABLE1' align='center'>";
	                $EnPromo.= "<tr>";
	                $EnPromo.= "<td align='center'>";
	                $EnPromo.= "<span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise."s ".DE_REDUCTION." **</b></span>";
	                $EnPromo.= "<br><br>";
	                $EnPromo.= FIN_DE_PROMOTION_DANS."<br>".$aa;
	                $EnPromo.= "</td>";
	                $EnPromo.= "</tr>";
	                $EnPromo.= "</table>";
	            }
	            else {
	                $EnPromo = $EnPromoBasic;
	            }
	        }
        }


       
       if(!empty($rowp['specials_new_price']) AND isset($delayPassed) AND $delayPassed == "no") {
           $price = $rowp['specials_new_price'];
           $displayPrice = "<div id='div_price'>";
           $displayPrice.= "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
		   $displayPrice.= "<td align='right' style='FONT-SIZE:12px'>";
           $displayPrice.= "<b>".PRIX.":&nbsp;<s>".$rowp['products_price']."&nbsp;".$symbolDevise."</s></b>";
		   $displayPrice.= "<br>";
		   $displayPrice.= "<span class='PromoFontColorNumber'><b>".$price."&nbsp;".$symbolDevise."</b></span>";
		   $displayPrice.= "</td></tr></table>";
		   $displayPrice.= "</div>";
           $sautLigne = "<br>";
       }
       else {
           $price = $rowp['products_price'];
           $displayPrice = "<div id='div_price'>";
           $displayPrice.= "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
		   $displayPrice.= "<td align='right' style='FONT-SIZE:12px'>";
		   $displayPrice.= "<b>".PRIX.": ".$price."&nbsp;".$symbolDevise."</b>";
		   $displayPrice.= "</td></tr></table>";
		   $displayPrice.= "</div>";
           $sautLigne = "";
       }
       if($price=="0.00") $displayPrice="";

        
        if(isset($_SESSION['list']) AND strstr($_SESSION['list'], "+".$rowp['products_ref']."+")) {
           $caddie_yes = "<a href='caddie.php'><img src='im/cart.gif' align='absmiddle' border='0' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."' alt=title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'></a>";
           $isThere = "yes";
        }
        else {
           $caddie_yes = "&nbsp;";
           $isThere = "no";
        }









 
if($displayProductsList=="oui" AND $displayProductsListNum>0) {
	$productsIntoCategory2 = $productsIntoCategory;
	if(!isset($_GET['i'])) $_GET['i']=0;
	if(isset($_GET['i']) AND $_GET['i']<0) $_GET['i']=0;
	 
     
     
    
	$addToQuery2Z = "";
	sort($productsIntoCategory2);
	$productsNum = count($productsIntoCategory2);
	if($productsNum > 0) {
		$addToQuery2Z.=" pr.products_id IN(";
		$uu = 1;
		foreach($productsIntoCategory2 AS $elem) {
			$addToQuery2Z.= "'".$elem."',";
			$prodId[$uu++] = $elem;
		}
		$addToQuery2Z.= ") ";
	}
	else {
		$addToQuery2Z= 1;
	}
	$addToQuery2Z = str_replace(",)",")",$addToQuery2Z);
	## Carousel request
	$CarQuery = mysql_query("SELECT pr.products_id, pr.products_image, pr.products_name_".$_SESSION['lang'].", pr.products_forsale, pr.products_price, sp.specials_new_price, sp.specials_last_day, sp.specials_first_day, sp.specials_visible,
							IF(sp.products_id<>'null'
                        	AND TO_DAYS(sp.specials_first_day) <= TO_DAYS(NOW())
                        	AND TO_DAYS(NOW()) <= TO_DAYS(sp.specials_last_day) , 'oui','non') as toto
							FROM products as pr
							LEFT JOIN specials as sp
                          	ON (pr.products_id = sp.products_id)
							WHERE ".$addToQuery2Z." 
							AND pr.products_visible='yes'
							ORDER BY pr.products_id DESC LIMIT ".$_GET['i'].",".$displayProductsListNum."") or die (mysql_error());
	$tototalZ = mysql_num_rows($CarQuery);
	if($tototalZ>0) {
		$productList="";
		$i=100;
		$theme = array("C0C0C0", $backGdColorListLine);
		$e = 0;
		while($carResult = mysql_fetch_array($CarQuery)) {
      		if(count($productsIntoCategory) > 1) {
      			## products IDs
				$productsIds[] = $carResult['products_id'];
				## Display product image
				$displayProductInfo = "<div align='center'><img src='im/zzz.gif' width='1' height='15' border='0'></div>";
				$displayProductInfo.= "<div align='center'>- ".array_search($carResult['products_id'], $prodId)." ".strtolower(DE)." ".$productsNum." -</div>";
				$displayProductInfo.= "<div align='center'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
				$displayProductInfo.= "<div align='center'>";
				if($gdOpen == "non") {
      			    $images_widthDesc2 = abs($ImageSizeDesc-70);
        			$hZ = @getimagesize($carResult['products_image']);
        			if(!$hZ) $carResult['products_image']="im/zzz_gris.gif";
        			$image_desc2 = resizeImage($carResult['products_image'],$ImageSizeDesc,$images_widthDesc2);
					$displayProductInfo.= "<img src='".$carResult['products_image']."' border='0' width='".$image_desc2[0]."' height='".$image_desc2[1]."' alt='".$carResult['products_name_'.$_SESSION['lang']]."'>";
				}
				else {
					$images_widthDesc2 = abs($ImageSizeDesc-70);
					$hZ = @getimagesize($carResult['products_image']);
					if(!$hZ) $carResult['products_image']="im/zzz_gris.gif";
					$image_desc2 = infoImageFunction($carResult['products_image'],$images_widthDesc2,$ImageSizeDesc);
					$displayProductInfo.= "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$image_desc2[0]."&imageSource=".$carResult['products_image']."&largeurOrigin=".$image_desc2[1]."&hauteurOrigin=".$image_desc2[2]."&largeur=".$image_desc2[3]."&hauteur=".$image_desc2[4]."' alt='".$carResult['products_name_'.$_SESSION['lang']]."' title='".$carResult['products_name_'.$_SESSION['lang']]."' border='0'>";
				}
				$displayProductInfo.= "</div>";
      			$displayProductInfo.= "<div align='center'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
  
      			$displayProductInfo.= "<div align='center'><u>".$carResult['products_name_'.$_SESSION['lang']]."</u></div>";
 
				if($carResult['toto']=='oui') {
					$priceZ = $carResult['specials_new_price'];
					$displayPriceZ = "<div align='center'><img src='im/zzz.gif' width='1' height='10' border='0'></div>";
					$displayPriceZ.= "<div style='font-size:11px'><b><s>".$carResult['products_price']." ".$symbolDevise."</s></b></div>";
					$displayPriceZ.= "<div class='PromoFontColorNumber' style='font-size:11px'><b>".$priceZ." ".$symbolDevise."</b></div>";
				}
				else {
					$priceZ = $carResult['products_price'];
					$displayPriceZ = "<div align='center'><img src='im/zzz.gif' width='1' height='10' border='0'></div>";
					$displayPriceZ.= "<div style='font-size:11px'><b>".$priceZ." ".$symbolDevise."</b></div>";
				}
				if($priceZ=="0.00") $displayPriceZ="";
				if((isset($_SESSION['account']) OR $displayPriceInShop=='oui') AND $carResult['products_forsale']=="yes") {
					$displayProductInfo.= "<div align='center'>".$displayPriceZ."</div>";
					if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
						$displayProductInfo.= ($priceZ!=="0.00")? "<div align='center'>".VOTRE_PRIX.": <span class='PromoFontColorNumber' style='font-size:11px'><b>".newPrice($priceZ,$_SESSION['reduc'])."&nbsp;".$symbolDevise."</b></span></div>" : "";
					}
					if($devise2Visible=="oui" AND $priceZ>0) $displayProductInfo.= curPrice($priceZ,$symbolDevise2,"center");
				}
				$displayProductInfo.= "<div align='center'><img src='im/zzz.gif' width='1' height='10' border='0'></div>";
 
				$boxWidth = ($gdOpen == "non")?  $boxWidth = $image_desc2[0]+35 : $boxWidth = $image_desc2[3]+35;
 
				$boxposition = $boxWidth/2;
  
				$urlBox = str_replace("id=".$_GET['id'], "id=".$carResult['products_id'] ,$_SERVER["QUERY_STRING"]);
 
				$top = "top:10px; ?top:10px;"; 
 
				$productList.= "<a href='beschrijving.php?".$urlBox."' class='tooltip2'>";
				if(isset($_GET['id']) AND $_GET['id']==$carResult['products_id']) {
					$productList.= "<b><span class='fondSelectedProduct'>";
				}
				else {
					$e = $e+1;
					if(($e%2) == 0) $ooo=1; else $ooo=0;
 
					$productList.= "<b><span style='background-color:#".$theme[$ooo]."'>";
				}
				$productList.= "<img src='im/zzz.gif' width='8' height='10' border='0'>";
				$productList.= "</b></span>";
				$productList.= "<em style='width:".$boxWidth."px; left:-".$boxposition."px; ".$top.";'><div align='left'>".$displayProductInfo."</div></em>";
				$productList.= "</a>";
			}
    	}
    	
    	if(isset($productsIds) AND count($productsIds)>0 AND isset($productsIntoCategory2)) {
			sort($productsIds); 
    		$_SESSION['ActiveUrl'] = redirectionZ("i",$_GET['i']);
    		if(isset($_SERVER["QUERY_STRING"])  AND $_SERVER["QUERY_STRING"]!=="" AND strstr($_SESSION['ActiveUrl'],"?")) $slash2 = "&"; else $slash2 = "?";

 
				$lastProductsIds = $displayProductsListNum;
				if(isset($_GET['i']) AND $_GET['i']>0) $lastProductsIds = $lastProductsIds+$_GET['i'];
				$previousLastProductsIds = $lastProductsIds-$displayProductsListNum;
                $nextCarousel = "<img src='im/zzz.gif' width='5' height='1' border='0'><a href='".$_SESSION['ActiveUrl'].$slash2."i=".$lastProductsIds."'>";
				$nextCarousel.= "<img src='im/next.png' alt='".SUIVANTTT."' title='".SUIVANTTT."' border='0'>";
				$nextCarousel.= "</a>";
                if($lastProductsIds>=$productsNum) $nextCarousel="";
  
                $numProductsInList = end(array_keys($productsIds))+1;
                $previousProductsIds = $previousLastProductsIds-$displayProductsListNum;
                if($nextCarousel=="") $previousProductsIds = $productsNum-$displayProductsListNum-$numProductsInList;
                if($previousProductsIds<0) $previousProductsIds=0;
                $prevCarousel = "<a href='".$_SESSION['ActiveUrl'].$slash2."i=".$previousProductsIds."'>";
				$prevCarousel.= "<img src='im/prev.png' alt='".PRECEDENT."' title='".PRECEDENT."' border='0'>";
				$prevCarousel.= "</a><img src='im/zzz.gif' width='5' height='1' border='0'>";
                if($previousLastProductsIds<=0) $prevCarousel="";
    	}
    	
    	 
    	if(isset($productList) AND $productList!=="") {
    		if($activeRoundedCornersDesc=='yes') $tableCenter="center"; else $tableCenter="right";
	    	print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td align='".$tableCenter."' valign='bottom'>";
			print $prevCarousel.$productList.$nextCarousel;
			print "</td></tr></table>";
		}
	}
}
 








    if($rowp['products_qt']>0) {
		$qty = $openLeg."<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' alt='".EN_STOCK."' title='".EN_STOCK."' align='absmiddle'></a>";
        $messageExpedition = "<b>".DELAI_EXPEDITION.":</b><br>".ENTRE." ".$rowp['products_delay_1']." ".ET." ".$rowp['products_delay_2']." ".JOURS_OUVRES;
        if($rowp['products_delay_1']==0 AND $rowp['products_delay_2'] > $rowp['products_delay_1']) {
        	$sous = $rowp['products_delay_2']*24;
		 	$messageExpedition = "<b>".DELAI_EXPEDITION."</b>:<br>".SOUS." ".$sous."H.";
		}
        $messageLivraison = "<b>".DATE_LIVRAISON.":</b><br>".ESTIMATION_FIN_COMMANDE;
    }
    else {
        if($actRes=="oui") {
            $qty2 = ARTICLE_EN_COMMANDE;
            $qty = $openLeg."<img src='im/stockin.gif' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."' align='absmiddle'></a>";
         	$messageExpedition = "<b>".DELAI_EXPEDITION.":</b><br>".ENTRE." ".$rowp['products_delay_1b']." ".ET." ".$rowp['products_delay_2b']." ".JOURS_OUVRES;
        	if($rowp['products_delay_1b']==0 AND $rowp['products_delay_2b'] > $rowp['products_delay_1b']) {
        		$sous = $rowp['products_delay_2b']*24;
		 		$messageExpedition = "<b>".DELAI_EXPEDITION."</b>:<br>".SOUS." ".$sous."H.";
			}
         	$messageLivraison = "<b>".DATE_LIVRAISON.":</b><br>".ESTIMATION_FIN_COMMANDE;
        }
        else {
            $qty = $openLeg."<img src='im/stockno.gif' border='0' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."' align='absmiddle'></a>";
         	$messageExpedition = "<b>".DELAI_EXPEDITION.":</b><br>".ENTRE." ".$rowp['products_delay_1a']." ".ET." ".$rowp['products_delay_2a']." ".JOURS_OUVRES;
        	if($rowp['products_delay_1a']==0 AND $rowp['products_delay_2a'] > $rowp['products_delay_1a']) {
        		$sous = $rowp['products_delay_2a']*24;
		 		$messageExpedition = "<b>".DELAI_EXPEDITION."</b>:<br>".SOUS." ".$sous."H.";
			}
         	$messageLivraison = "";
        }
    }
 
        if($rowp['products_download'] == "yes") {
            $prodDown = $openLeg."&nbsp;<img src='im/download.gif' border='0' alt='".ARTICLE_TELE."' title='".ARTICLE_TELE."' align='absmiddle'></a>";
         	$messageExpedition = "<b>".DATE_LIVRAISON.":</b><br>".EN_TELECHARGEMENT_A_LA_CONFIRMATION_DU_PAIEMENT;
         	$messageLivraison = "";
         }
         else {
            $prodDown = "";
        }







 
$TABLETitreProductDescription = ($activeRoundedCornersDesc=="yes")? "" : "TABLETitreProductDescription";
if($activeRoundedCornersDesc=='yes') {
	$TABLETitreProductDescription = "";
	round_top('yes',$tt,'raised3');
}
else {
	$TABLETitreProductDescription = "TABLETitreProductDescription";
}

print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='5' class='".$TABLETitreProductDescription."' align='center'>";
print "<tr>";
        print "<td valign='middle' height='35'>";
         
        print "&nbsp;<b class='titre'>".$rowp['products_name_'.$_SESSION['lang']]."</b>";
        print "</td>";
        if($rowp['products_forsale']=="yes") {
	         
	        if($rowp['products_exclusive']=="yes") {
	            print "<td width='1' align='right'>";
	            print "<a href='list.php?target=favorite'><img src='im/coeur.gif' border='0' alt='".COEUR."' title='".COEUR."'></a>";
	            print "</td>";
	        }
			 
			if(in_array($rowp['products_id'], $_SESSION['discountQt'])) {
				print "<td width='1' align='right'>";
				print $openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."'></a>";
				print "</td>";
			}
	        
	        if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo) {
	            print "<td width='1' align='right'>";
	            print "<a href='list.php?target=promo&tow=flash'><img src='im/time_anim.gif' border='0' alt='".VENTES_FLASH."' title='".VENTES_FLASH."'></a>";
	            print "</td>";
	        }
	         
	        if($isThere=='yes') {
	            print "<td width='1' align='right'>";
	            print $caddie_yes;
	            print "</td>";
	        }
        }
        else {
        
            print "<td width='1' align='right'>";
            print "&nbsp;";
            print "</td>";
        }
        
        
        if(isset($displayNav)) {
            print "<td width='33' valign='middle' align='right'>";
            displayNavBetweenProducts();
            print "</td>";
        }
print "</tr>";
print "</table>";
if($activeRoundedCornersDesc=='yes') round_bottom('no');
print "<br>";

 
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='0' align='center'><tr>";
print "<form action='add.php' name='addCadd' method='GET'>";
print "<td>";

print "<table width='".$tt."' border='0' cellspacing='3' cellpadding='0' align='center'>";
print "<tr>";

if($rowp['products_im'] == "yes" AND !empty($rowp['products_image']) AND $rowp['products_image']!=="im/no_image_small.gif") {
		$images_widthDesc = $ImageSizeDesc+20;
		$h = @getimagesize($rowp['products_image']);
		if(!$h) $rowp['products_image']="im/zzz_gris.gif";
		$image_desc = resizeImage($rowp['products_image'],$ImageSizeDesc,$images_widthDesc);
				
        print "<td width='".$image_desc[0]."' align='left' valign='top'>";
        
		print "<table border='0' cellspacing='0' cellpadding='0' align='left'>";
		print "<tr>";
        print "<td width='".$image_desc[0]."' align='left' valign='top'>";

			 
			print "<div id='div_im'>";
			print "</div>";

			print "<div id='div_im1'>";
	            
	            if($lightbox == "oui") {
	            	print "<a href='".$rowp['products_image']."' rel='lightbox[roadtrip]' alt='".$rowp['products_name_'.$_SESSION['lang']]."' title='".$rowp['products_name_'.$_SESSION['lang']]."' target='_blank'>";
	            }
	            else {
	            	print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$rowp['products_image']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$h[1].",width=".$h[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
	            }
				
				if($gdOpen == "non") {
					print "<img src='".$rowp['products_image']."' border='0' width='".$image_desc[0]."' height='".$image_desc[1]."' alt='".$rowp['products_name_'.$_SESSION['lang']]."'>";
				}
				else {
					$infoImage = infoImageFunction($rowp['products_image'],200,$ImageSizeDesc);
					print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowp['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$rowp['products_name_'.$_SESSION['lang']]."' border='0'>";                  
				}
	        	print "</a>";
        	print "</div>";
        	
        print "</td>";
        print "</tr><tr>";
        	
        	
	    if(!empty($rowp['products_image2']) OR !empty($rowp['products_image3']) OR !empty($rowp['products_image4']) OR !empty($rowp['products_image5'])) {
				print "<td valign='top' align='left'>";
				print "<div id='div_miniIm'>";
				print "<table border='0' cellspacing='0' cellpadding='3' align='left'><tr>";
           		
    			for($n=2; $n<=5; $n++) {
    			$yy = $n-2;
              	if(!empty($rowp['products_image'.$n.''])) {
	                
	                if(substr($rowp['products_image'.$n.''],-3) == "gif" OR substr($rowp['products_image'.$n.''],-3) == "jpg" OR substr($rowp['products_image'.$n.''],-3) == "png") {
	                	$h1 = @getimagesize($rowp['products_image'.$n.'']);
	                    if(!$h1) $rowp['products_image'.$n.'']="im/zzz_gris.gif";
	                    $image_desc2 = resizeImage($rowp['products_image'.$n],$SecImageSizeDesc,$SecImageWidthDesc);
						print "<td width='".$image_desc2[0]."' height='".$image_desc2[1]."' valign='top'>";
	                    	
	                    	if($lightbox == "oui") {
	                    		print "<a href='".$rowp['products_image'.$n]."' rel='lightbox[roadtrip]' alt='".$rowp['products_name_'.$_SESSION['lang']]."' title='".$rowp['products_name_'.$_SESSION['lang']]."' target='_blank'>";
	                    	}
	                    	else {
	                    		print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$rowp['products_image'.$n.'']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$h1[1].",width=".$h1[0].",toolbar=no,scrollbars=no,resizable=no');\">";
	                    	}
	                    
	                    if($gdOpen == "non") {
	                    	print "<img src='".$rowp['products_image'.$n]."' width='".$image_desc2[0]."' height='".$image_desc2[1]."' border='0'>";
	                    }
						else {
	                    	$infoImage = infoImageFunction($rowp['products_image'.$n],$SecImageWidthDesc,$SecImageSizeDesc);
	                    	print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowp['products_image'.$n.'']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0'>";                  
						}
						print "</a>";
						print "</td>";
					}

	                
	                if(substr($rowp['products_image'.$n.''],-3) == "ram" OR
	                	substr($rowp['products_image'.$n.''],-3) == "mp3" OR 
	                	substr($rowp['products_image'.$n.''],-3) == "wav" OR
	                	substr($rowp['products_image'.$n.''],-2) == "au"  OR
	                	substr($rowp['products_image'.$n.''],-3) == "aif" OR
	                	substr($rowp['products_image'.$n.''],-3) == "wma") {
	                    	$explodeSoundFile = explode("/",$rowp['products_image'.$n.'']);
	                    	$namez = substr(end($explodeSoundFile), 0, -4);
							print "<td width='1' valign='top'>";
	                               print "<table cellspacing='0' cellpadding='0'><tr>";
	                               print "<td valign='middle'>";
	                               print "<a href='".$rowp['products_image'.$n.'']."'><img src='im/notes.gif' align='absmiddle' border='0' alt='Title: ".$namez."' title='Title: ".$namez."'></a>&nbsp;";
	                               print "</td>";
	                               print "</tr></table>";
	                        print "</td>";
	            	}
				}
			}
			print "</tr></table>";
			print "</div>";
			print "</td>";
		}
		else {
			print "<td>";
			print "<div id='div_miniIm'></div>";
			print "</td>";
		}
		print "</tr></table>";
            
        print "</td>";
}
else {
	$noImage_resizeDesc = resizeImage("im/lang".$_SESSION['lang']."/no_image.png",$ImageSizeDesc,200);
    print "<td rowspan='3' valign='top' align='left'>";
	
	print "<div id='div_im'></div>";
    print "<div id='div_im1'>";
	print "<img src='im/lang".$_SESSION['lang']."/no_image.png' width='".$noImage_resizeDesc[0]."' height='".$noImage_resizeDesc[1]."'>";
	print "</div>";
	print "<div id='div_miniIm'></div>";
	print "</td>";
}




        print "<td align='right' valign='top'>";

		print "<div id='displayPrix'>";
		print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
		print "<td align='right'>";
		if((isset($_SESSION['account']) OR $displayPriceInShop=='oui') AND $rowp['products_forsale']=="yes") {
			## Afficher prix
			print $displayPrice;
			## Afficher prix + reduc client
			if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
				if($price!=="0.00") {
					print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
					print "<td>";
					$newPrice = newPrice($price,$_SESSION['reduc']);
					print "<div align='right' style='FONT-SIZE:12px'><b>".VOTRE_PRIX.":&nbsp;<span class='PromoFontColorNumber' style='FONT-SIZE:12px'>".$newPrice." ".$symbolDevise."</span></b></div>";
					print "</td>";
					print "</tr></table>";
				}
			}
?>
<script type="text/javascript"><!--
	function displayPrice(p, pDiscount, fp, ref, state, downl) {
		var fp=fp.toFixed(2);
		var p=p.toFixed(2);
		var pDiscount=pDiscount.toFixed(2);
		var symbolDevise2 = "<?php print $symbolDevise2;?>";
		var devise2Visible = "<?php print $devise2Visible;?>";
		var tauxDevise2 = "<?php print $tauxDevise2;?>";
		var reference = ref;
		var affiche_prixFinal = "";
		
		if(pDiscount>0) {
			fp1 = p-(p*pDiscount/100);
			fp1 = fp1.toFixed(2);
		}
		else {
			fp1 = p;
		}

		
		if(reference!='') {
			afficher_ref(reference);
		}
		
		
		if(state!='') {
			afficher_state(state, downl);
		}

		
		if(state!='') {
			afficher_exped(state, downl);
		}
		
		if(fp1!=fp) {
				
				affiche_prixFinal = "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr>";
				affiche_prixFinal = affiche_prixFinal + "<td align='right' style='FONT-SIZE:12px'>";
				affiche_prixFinal = affiche_prixFinal + "<b><?php print PRIX;?>:&nbsp;<s>" + fp1 + "</b>&nbsp;<?php print $symbolDevise;?></s><br>";
				affiche_prixFinal = affiche_prixFinal + "<b>" + fp + "&nbsp;<?php print $symbolDevise;?></b>";
				affiche_prixFinal = affiche_prixFinal + "</td></tr></table>";
				
				
				if(devise2Visible == "oui" && fp>0) {
					currencyPrice = fp*tauxDevise2;
					affiche_prixFinal = affiche_prixFinal + "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
					affiche_prixFinal = affiche_prixFinal + "<td align='right'>";
					affiche_prixFinal = affiche_prixFinal + "<div class='FontGris tiny'><i>[" + currencyPrice.toFixed(2) + " " + symbolDevise2 + "]</i></div>";
					affiche_prixFinal = affiche_prixFinal + "</td></tr></table>";
					document.getElementById('div_currency').style.display='none';
				}
				else {
					document.getElementById('div_currency').style.display='block';
				}
				
				
				var div=document.getElementById("affiche_prix");
				while (div.childNodes[0]) {
					div.removeChild(div.childNodes[0]);
				}
				
				var div = document.createElement('div');
				div.innerHTML = affiche_prixFinal;
				var passagesortieZ = document.getElementById("affiche_prix");
				passagesortieZ.appendChild(div);
				document.getElementById('affiche_prix').style.display='block';
				document.getElementById('div_price').style.display='none';
		}
		else {
			document.getElementById('affiche_prix').style.display='none';
			document.getElementById('div_price').style.display='block';
		}
	}
--></script>
<?php
			## Afficher prix article avec options
			print "<div id='affiche_prix'></div>";
			
			## Afficher devise 2
			if($devise2Visible=="oui" AND $price>0) {
				print "<div id='div_currency'>";
				print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
				print "<td align='right'>";
				print curPrice($price,$symbolDevise2,"right");
				print "</td>";
				print "</tr></table>";
				print "</div>";
			}
			else {
				print "<div id='div_currency'></div>";
			}
		}
		print "</td>";
		print "</tr></table>";
		
		print "<div id='displayPrixOnlyNew' style='display:none'></div>";

		if($rowp['products_deee']>0 AND $rowp['products_forsale']=="yes" AND (isset($_SESSION['account']) OR $displayPriceInShop=='oui')) {
			$openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
			print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
			print "<td align='right'>";
			print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$rowp['products_deee']." ".$symbolDevise."</i>";
			print "</td>";
			print "</tr></table>";
		}

?>
<script type="text/javascript"><!--
	function afficher_ref(ref) {
		if(ref!='') {
			var affiche_ref = "";
			affiche_ref = affiche_ref + "<div><img src='im/zzz.gif' width='1' height='5'></div>";
			affiche_ref = affiche_ref + "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
			affiche_ref = affiche_ref + "<td align='right' class='fontgris' style='font-size:10px'>";
			affiche_ref = affiche_ref + "<b><?php print REF;?></b>:&nbsp;" + ref;
			affiche_ref = affiche_ref + "</td></tr></table>";
			affiche_ref = affiche_ref + "</div>";
			document.getElementById('div_ref1').style.display='none';
			
		}
		else {
			document.getElementById('div_ref1').style.display='block';
		}
				
				var div = document.getElementById("div_ref");
				while (div.childNodes[0]) {
					div.removeChild(div.childNodes[0]);
				}
				
				var div = document.createElement('div');
				div.innerHTML = affiche_ref;
				var passagesortieZ = document.getElementById("div_ref");
				passagesortieZ.appendChild(div);
				document.getElementById('div_ref1').style.display='none';
	}
--></script>
<?php
		## Afficher reference article avec options
		print "<div id='div_ref'></div>";
			
		print "<div id='div_ref1'>";
		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
		print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
		print "<td align='right' class='fontgris' style='font-size:10px'>";
		print "<b>".REF."</b>:&nbsp;".$refdet;
		print "</td>";
		print "</tr></table>";
		print "</div>";
		
		print "</div>";

?>
<script type="text/javascript"><!--
	function afficher_state(state, downl) {
		if(state!='') {
			var affiche_state = "";
			if(state=="<?php print EN_STOCK;?>") affState = "<img src='im/lang<?php print $_SESSION['lang'];?>/stockok.png' border='0' alt='<?php print EN_STOCK;?>' title='<?php print EN_STOCK;?>' align='absmiddle'>";
			if(state=="<?php print EN_COMMANDE;?>") affState = "<img src='im/stockin.gif' border='0' alt='<?php print EN_COMMANDE;?>' title='<?php print EN_COMMANDE;?>' align='absmiddle'>";
			if(state=="<?php print DECLINAISON_EPUISEE;?>") affState = "<img src='im/stockno.gif' border='0' alt='<?php print DECLINAISON_EPUISEE;?>' title='<?php print DECLINAISON_EPUISEE;?>' align='absmiddle'>";
			if(state=="no exists") affState = "<img src='im/no_stock.gif' border='0' alt='<?php print DECLINAISON_NON_REPERTORIEE;?>' title='<?php print DECLINAISON_NON_REPERTORIEE;?>' align='absmiddle'>";
			if(downl=="download") affState = "<img src='im/download.gif' border='0' alt='<?php print ARTICLE_TELE;?>' title='<?php print ARTICLE_TELE;?>' align='absmiddle'>";

			affiche_state = affiche_state + "<b><?php print DISPO;?></b>:&nbsp;" + affState;
			document.getElementById('div_state1').style.display='none';
		}
		else {
			document.getElementById('div_state1').style.display='block';
		}

				
				var div = document.getElementById("div_state");
				while (div.childNodes[0]) {
					div.removeChild(div.childNodes[0]);
				}
				
				var div = document.createElement('div');
				div.innerHTML = affiche_state;
				var passagesortieZ = document.getElementById("div_state");
				passagesortieZ.appendChild(div);
				document.getElementById('div_state1').style.display='none';
	}
--></script>
<?php
        if($rowp['products_forsale'] == "no") {
            $prodDown = $openLeg."&nbsp;<img src='im/no_stock.gif' border='0' alt='".ITEMS_OUT_OF_STOCK."' title='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
            $qty="";
         	$messageExpedition = "";
         	$messageLivraison = "";
        }
         print "<div id='display_stock_bouton'>";
         print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
         print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
         print "<td align='right'>";

		## Afficher stock article avec options
		print "<div id='div_state'></div>";
		
		print "<div id='div_state1'>";
         	print "<b>".DISPO."</b>: ";
		 	if($prodDown=='') print $qty;
		 	print $prodDown;
		print "</div>";
		print "</td>";
		print "</tr></table>";
		print "</div>";

		 print "<div id='display_no_stock' style='display:none'>";
         print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
         print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
         print "<td align='right' valign='middle'><b>".DISPO."</b>: </td>";
         print "<td align='right' width='1'><img src='im/cart_no.png' alt='".NON_DISPONIBLE."' title='".NON_DISPONIBLE."'></td>";
         print "</tr></table>";
		 print "</div>";
		 
if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['products_forsale']=="yes" AND !isset($_SESSION['devisNumero'])) {
	print "<input type='hidden' value='".$rowp['products_id']."' name='id'>";
	print "<input type='hidden' value='".$rowp['products_ref']."' name='ref'>";
	print "<input type='hidden' value='".$rowp['products_name_'.$_SESSION['lang']]."' name='name'>";
	print "<input type='hidden' value='".$rowp['products_tax']."' name='productTax'>";
	print "<input type='hidden' value='".$rowp['products_deee']."' name='deee'>";
	
	if($rowp['products_qt'] > 0) {
		if($isThere=="no") {
			print "<div id='display_cart'>";
			print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
			print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
			print "<td align='right' valign='middle'>";
			print "<input type='text' size='3' maxlength='3' name='amount' value='1'>&nbsp;";
			print "</td>";
			print "<td align='right' valign='middle' width='1'>";
			print "<input type='image' src='im/cart_add.png' style='BACKGROUND:none; border:0px' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
			##print "<input style='BACKGROUND:#666666; color:#FFFFFF; padding:2px; border:3px #CCCCCC double; font-weight:bold; font-size:11px; width:140px;' type='submit' size='100' value='".AJOUTER_AU_CADDIE."' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
			print "</td>";
			print "</tr></table>";
			
			if($expressBuy=="oui") {express_buy($price,8);}
			print "</div>";
		}
		else {
			if($rowp['products_options'] == "no") {
				print "<a href='add.php?amount=0&ref=".$rowp['products_ref']."&id=".$rowp['products_id']."&name=".$rowp['products_name_'.$_SESSION['lang']]."&productTax=".$rowp['products_tax']."&deee=".$rowp['products_deee']."'><img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'></a>";
			}
			else {
				print "<div id='display_cart'>";
				print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
				print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
				print "<td align='right' valign='middle' width='100%'>";
				print "<input type='text' size='3' maxlength='3' name='amount' value='1'>&nbsp;";
				print "</td><td align='right' valign='middle' width='1'>";
				print "<input style='BACKGROUND:none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
				print "</td></tr><tr>";
				print "<td colspan='2' align='right' valign='top'>";
				print AUTRES_OPTIONS;
				print "</td>";
				print "</tr></table>";
				
				if($expressBuy=="oui") {express_buy($price,8);}
				print "</div>";
			}
		}
	}
	else {
		if($actRes=="oui") {
			print "<span class='fontrouge'><b>".EN_COMMANDE."</b></span>"; 
		}
		else {
			print "<span class='fontrouge'><b>".NOT_IN_STOCK."</b></span>";
		}
	}
}

if($rowp['products_forsale']=="no") print $discontinue;

if($displayDelivery=="oui") {
?>
<script type="text/javascript"><!--
	function afficher_exped(state, downl) {
		if(state!='') {
			var affiche_exp = "";
			var products_delay_1 = <?php print $rowp['products_delay_1'];?>;
			var products_delay_2 = <?php print $rowp['products_delay_2'];?>;
			var products_delay_1b = <?php print $rowp['products_delay_1b'];?>;
			var products_delay_2b = <?php print $rowp['products_delay_2b'];?>;
			var products_delay_1a = <?php print $rowp['products_delay_1a'];?>;
			var products_delay_2a = <?php print $rowp['products_delay_2a'];?>;
			
			if(state=="<?php print EN_STOCK;?>") {
				affExped = "<b><?php print DELAI_EXPEDITION;?>:</b><br><?php print ENTRE;?> <?php print $rowp['products_delay_1'];?> <?php print ET;?> <?php print $rowp['products_delay_2'];?> <?php print JOURS_OUVRES;?>";
        		if(products_delay_1==0 && products_delay_2 > products_delay_1) {
        			sous = products_delay_2*24;
		 			affExped = "<b><?php print DELAI_EXPEDITION;?></b>:<br><?php print SOUS;?> " + sous + "H.";
				}
				affLiv = "<b><?php print DATE_LIVRAISON;?>:</b><br><?php print ESTIMATION_FIN_COMMANDE;?>";
			}
			if(state=="<?php print EN_COMMANDE;?>") {
				affExped = "<b><?php print DELAI_EXPEDITION;?>:</b><br><?php print ENTRE;?> <?php print $rowp['products_delay_1b'];?> <?php print ET;?> <?php print $rowp['products_delay_2b'];?> <?php print JOURS_OUVRES;?>";
	        	if(products_delay_1b==0 && products_delay_2b > products_delay_1b) {
	        		sous = products_delay_2b*24;
			 		affExped = "<b><?php print DELAI_EXPEDITION;?></b>:<br><?php print SOUS;?> " + sous + "H.";
				}
				affLiv = "<b><?php print DATE_LIVRAISON;?>:</b><br><?php print ESTIMATION_FIN_COMMANDE;?>";
			}
			if(state=="<?php print DECLINAISON_EPUISEE;?>") {
				affExped = "<b><?php print DELAI_EXPEDITION;?>:</b><br><?php print ENTRE;?> <?php print $rowp['products_delay_1a'];?> <?php print ET;?> <?php print $rowp['products_delay_2a'];?> <?php print JOURS_OUVRES;?>";
	        	if(products_delay_1a==0 && products_delay_2a > products_delay_1a) {
	        		sous = products_delay_2a*24;
			 		affExped = "<b><?php print DELAI_EXPEDITION;?></b>:<br><?php print SOUS;?> " + sous + "H.";
				}
				affLiv = "";
			}
			if(state=="no exists") {
				affExped = "";
				affLiv = "";
			}
			if(downl=="download") {
				affExped = "";
				affLiv = "<b><?php print DATE_LIVRAISON;?>:</b><br><?php print EN_TELECHARGEMENT_A_LA_CONFIRMATION_DU_PAIEMENT;?>";
			}
			
			affiche_exp = affiche_exp + "<div><img src='im/zzz.gif' width='1' height='5'></div>";
			affiche_exp = affiche_exp + "<table width='100%' border='0' cellspacing='0' cellpadding='3'><tr>";
			affiche_exp = affiche_exp + "<td align='right'>";
			affiche_exp = affiche_exp + affExped;
			if(affLiv!="") {
				affiche_exp = affiche_exp + "</td></tr><tr>";
				affiche_exp = affiche_exp + "<td align='right'>";
				affiche_exp = affiche_exp + affLiv;
			}
			affiche_exp = affiche_exp + "</td></tr></table>";
			document.getElementById('display_liv').style.display='none';
		}
		else {
			document.getElementById('display_liv').style.display='block';
		}

				
				var div = document.getElementById("div_exped");
				while (div.childNodes[0]) {
					div.removeChild(div.childNodes[0]);
				}
				
				var div = document.createElement('div');
				div.innerHTML = affiche_exp;
				var passagesortieZ = document.getElementById("div_exped");
				passagesortieZ.appendChild(div);
				document.getElementById('display_liv').style.display='none';
	}
--></script>
<?php
	## Afficher delais expedition/livraison article avec options
	print "<div id='div_exped'></div>";
	
	if(isset($messageExpedition) AND $messageExpedition!=="") {
		print "<div id='display_liv'>";
		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
		print "<table width='100%' border='0' cellspacing='0' cellpadding='3'><tr>";
		print "<td align='right'>";
		print $messageExpedition;
		if(isset($messageLivraison) AND $messageLivraison!=="") {
			print "</td>";
			print "</tr><tr>";
			print "<td align='right'>";
			print $messageLivraison;
		}
		print "</td>";
		print "</tr></table>";
		print "</div>";
	}
}

        print "</td>";
        print "</tr>";
        print "</table>";













print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' align='center'>";

		$ww = str_replace("&","|",$url_id10);
		$ww = str_replace("description","descXXX",$ww);
		print "<tr>";
		print "<td>";





			
			
			$_poidZ = $rowp['products_weight'];
			
			$requestShipCountry = mysql_query("SELECT countries_name, countries_id FROM countries WHERE iso = '".$iso."'");
			$oountryIdResult = mysql_fetch_array($requestShipCountry);
			$oountryName = $oountryIdResult['countries_name'];
			$oountryId = $oountryIdResult['countries_id'];
			$messageEr = "";
			
			if($_poidZ > 0 AND $rowp['products_forsale']=="yes") {
				
				$requestpays = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes'") or die (mysql_error());    
				$requestpaysNum = mysql_num_rows($requestpays);
				if($_poidZ>=999999999) {
					$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max='999999999'") or die (mysql_error());
					$requestWeightNum = mysql_num_rows($requestWeight);
				}
				else {
					$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."'") or die (mysql_error());
					$requestWeightNum = mysql_num_rows($requestWeight);
				}
				$messageEr.= ($requestpaysNum==0)? AUCUNE_LIVRAISON_DANS_CE_PAYS."<br>" : "";
				$messageEr.= ($requestpaysNum > 0 AND $requestWeightNum==0)? POIDS_TOTAL_DEPASSE_NOS_CAPACITES : "";
				
				
				if($_poidZ>=999999999) {
					$requestShip = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max ='999999999' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
				}
				else {
					$requestShip = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
				}
			
				if(mysql_num_rows($requestShip) > 0) {
						print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
						print "<td>";
						if($displayShippingLogoDesc=="oui") {
							print "<div id='mode_liv'>";
				            print "<table border='0' align='left' cellspacing='0' cellpadding='3'><tr>";
							
				            while($resultShip = mysql_fetch_array($requestShip)) {
				                $tarifRequestZ = mysql_query("SELECT id FROM ship_price WHERE livraison_id = '".$resultShip['livraison_id']."'") or die (mysql_error());
				                if(mysql_num_rows($tarifRequestZ)>0) {
				                   print "<td>";
				                   		$logoHeight = 25;
										$disp = ($resultShip['livraison_image']!=='')? "<img src='".$resultShip['livraison_image']."' border='0' height='".$logoHeight."'>" : "<img src='im/liv.png' border='0' height='".$logoHeight."'>";
										if($resultShip['livraison_note_'.$_SESSION['lang']]=="") {
											$openUseSearch22 = "<a href='#' class='tooltip'>".$disp."<em style='width:150px; left:30px'>".$resultShip['livraison_nom_'.$_SESSION['lang']]."</em></a>";
										}
										else {
											$openUseSearch22 = "<a href='#' class='tooltip'>".$disp."<em style='width:275px; left:30px'>".$resultShip['livraison_note_'.$_SESSION['lang']]."</em></a>";
										}
				                   print $openUseSearch22;
				                   print "</td>";
				                }
				            }
	
				            print "</tr></table>";
				            print "</div>";
			            }
			            print "</td>";
						print "<td align='right'>";
							
							afficher_addToFavori__sendToFriend();
						print "</td>";
						print "</tr></table>";
				}
			}
			else {
				print "<div id='mode_liv'></div>";
				print "<table border='0' width='100%' cellspacing='0' cellpadding='3'><tr>";
				print "<td align='right'>";
					
					afficher_addToFavori__sendToFriend();
				print "</td>";
				print "</tr></table>";
			}
		
		
		
		
		
		
		


		
		
		if($displayPaymentsLogoDesc=="oui" AND $rowp['products_forsale']=="yes") {
			print "<div id='payment_logo'>";
			print "<table width='100%' border='0' cellspacing='0' cellpadding='3'><tr>";
			print "<td align='left' valign='middle'>";
			if($co=="oui" OR $EuroWebPayment=="oui" OR $liaisonssl=="oui" OR $klikandpayActive=="oui" OR $bluepaid=="oui" OR $ogonePayment=="oui" OR $paySiteCash=="oui") {print "<a href='infos.php?info=3'><img src='im/betaal-logos/cb.gif' width='90' border='0' alt='".CARTE_DE_CREDIT."' title='".CARTE_DE_CREDIT."'></a>&nbsp;";}
			if($paypalPayment=="oui" OR $co=="oui" OR $EuroWebPayment=="oui" OR $liaisonssl=="oui" OR $klikandpayActive=="oui" OR $bluepaid=="oui" OR $ogonePayment=="oui" OR $paySiteCash=="oui" OR $pfPayment=="oui") {print "";}
			if($paypalPayment=="oui") {print "<a href='infos.php?info=3'><img src='im/betaal-logos/paypal.gif' border='0' alt='Paypal' title='Paypal'></a>&nbsp;";}
			if($paynlPayment=="yes") {print "<a href='infos.php?info=3'><img src='im/betaal-logos/ideal.gif' border='0' alt='Ideal' title='IDeal betalen'></a>&nbsp;";}
			if($mbPayment=="oui") {print "<a href='infos.php?info=3'><img src='im/betaal-logos/mb_small.png' border='0' alt='MoneyBookers' title='MoneyBookers'></a>&nbsp;";}
			if($activerCheq=="oui") {print "<a href='infos.php?info=3'><img src='im/betaal-logos/cheque.gif' border='0' alt='".CHEQUE_BANCAIRE."' title='".CHEQUE_BANCAIRE."'></a>&nbsp;";}
			
			
			if($euroPayment == "oui" AND $displayGraphics == "oui" AND $id_partenaire!=="" AND $price > 60 AND $price < 5000 AND $rowp['products_forsale']=="yes" ) {
			      if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
					print "<a alt='1euro.com - ".$rowp['products_name_'.$_SESSION['lang']]."' title='1euro.com - ".$rowp['products_name_'.$_SESSION['lang']]."' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".newPrice($price,$_SESSION['reduc'])."\")'><img src='im/betaal-logos/calc_bt_noir.gif' border='0'></a>";
			      }
			      else {
					print "<a alt='1euro.com - ".$rowp['products_name_'.$_SESSION['lang']]."' title='1euro.com - ".$rowp['products_name_'.$_SESSION['lang']]."' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".$price."\")'><img src='im/betaal-logos/calc_bt_noir.gif' border='0'></a>";
			      }
			      print "&nbsp;";
			}
			/*
			print "<div><img src='im/zzz.gif' width='1' height='10'></div>";
			if($activerVirement=="oui") {print "<img src='im/betaal-logos/virement.gif' alt='".VIREMENT_BANCAIRE."' title='".VIREMENT_BANCAIRE."'>&nbsp;";}
			if($activerWestern=="oui") {print "<img src='im/betaal-logos/western.gif' alt='Western Union' title='Western Union'>&nbsp;";}
			if($activerContre=="oui") {print "<img src='im/betaal-logos/contre.gif' alt='".CONTRE_REMBOURSEMENT."' title='".CONTRE_REMBOURSEMENT."'>&nbsp;";}
			if($activerMandat=="oui") {print "<img src='im/betaal-logos/mandat.gif' alt='".MANDAT_POSTAL."' title='".MANDAT_POSTAL."'>&nbsp;";}
			*/
			print "</td>";
			print "</tr></table>";
			print "</div>";
		}

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		print "</td></tr>";

$fabricantQuery = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseurs_id = '".$rowp['fabricant_id']."'");
$fab = mysql_fetch_array($fabricantQuery);
$fabricant = $fab['fournisseurs_company'];
if($rowp['afficher_fabricant']=='yes' AND !empty($fabricant)) {
      print "<tr>";
      print "<td colspan='2' width='100%' valign='bottom' align='left'>";
      print COMPAGNIE." ".$fabricant;
      print "</td>";
      print "</tr>";
}

if($rowp['afficher_fournisseur']=='yes') {
	if(!empty($rowp['fournisseurs_link'])) {
   	print "<tr>";
          print "<td c#olspan='2' width='100%' valign='bottom' align='left'>";
          print "<b>".FOURNISSEUR."</b>: <a href='".$rowp['fournisseurs_link']."' target='_blank'>".$rowp['fournisseurs_company']."</a>";
          print "</td>";
          print "</tr>";
	}
	else {
   	print "<tr>";
          print "<td c#olspan='2' width='100%' valign='bottom' align='left'>";
          print "<b>".FOURNISSEUR."</b>: ".$rowp['fournisseurs_company'];
          print "</td>";
          print "</tr>";
	}
}


$prods2Z = mysql_query("SELECT * FROM discount_on_quantity WHERE discount_qt_prod_id = '".$_GET['id']."' ORDER BY discount_qt_qt ASC") or die (mysql_error());
$prods2ZNum = mysql_num_rows($prods2Z);
if($prods2ZNum > 0) {
	$c="TDTableListLine1";
	print "<tr>";
	print "<td>";
	print "<div id='displayPrixDegressif'>";
	print "<hr width='".$tt."'>";
	print "<div align='left' class='PromoFont'><b>".PRODUIT_A_PRIX_DEGRESSIF."</b></div>";
	print "<div align='left'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
	print "<table w/idth='100%' border='0' cellpadding='3' cellspacing='1' align='left' class='TABLEPaymentProcessSelected'><tr height='20'>";
	print "<td align='center'><b>&nbsp;&nbsp;&nbsp;&nbsp;".QUANTITY."&nbsp;&nbsp;&nbsp;&nbsp;</b></td>";
	print "<td align='center'><b>&nbsp;&nbsp;&nbsp;&nbsp;".REMISE."&nbsp;&nbsp;&nbsp;&nbsp;</b></td>";
	if((isset($_SESSION['account']) OR $displayPriceInShop=='oui') AND $rowp['products_forsale']=="yes" AND $rowp['products_options']=='no') print "<td align='center'><b>&nbsp;&nbsp;&nbsp;&nbsp;".PRIX__UNITAIRE."&nbsp;&nbsp;&nbsp;&nbsp;</b></td>";
	
	while($prods2Result = mysql_fetch_array($prods2Z)) {
		if($c=="TDTableListLine1") {$c="TDTableListLine2"; } else {$c="TDTableListLine1";}
		$toto = $prods2Result['discount_qt_prod_id'];
			print "</tr><tr class='".$c."'>";
			print "<td align='center'>";
			
			print "<img src='im/sup.png' border='0'>".$prods2Result['discount_qt_qt'];
			print "</td>";
			
			print "<td align='center'>";
			if($prods2Result['discount_qt_value']=="euro") {
				$s = ($prods2Result['discount_qt_discount']>1)? "s" : "";
				$valueZ = "- ".sprintf("%0.2f",$prods2Result['discount_qt_discount'])." ".strtolower($devise).$s;
			}
			else {
				$valueZ = "- ".$prods2Result['discount_qt_discount'].$prods2Result['discount_qt_value'];
			}
			print $valueZ;
			print "</td>";
			if((isset($_SESSION['account']) OR $displayPriceInShop=='oui') AND $rowp['products_forsale']=="yes") {
			
				if($rowp['products_options']=='no') {
					print "<td align='center'>";
						$discountOnQtFinal = $prods2Result['discount_qt_discount']."".$prods2Result['discount_qt_value'];
						$cutExt = explode('%',$discountOnQtFinal);
						if(isset($cutExt[1])) {
							$price_degressif = $price-($price*$cutExt[0]/100);
							if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
								$price_degressif = newPrice($price_degressif,$_SESSION['reduc']);
							}
						}
						else {
							$discountOnQtFinalNumeric = str_replace('euro','',$discountOnQtFinal);
							$price_degressif = $price-$discountOnQtFinalNumeric;
							if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
								$price_degressif = newPrice($price_degressif,$_SESSION['reduc']);
							}
						}
						
						print sprintf("%0.2f",$price_degressif)." ".$symbolDevise;
					print "</td>";
				}
			}
	}
	print "</tr></table>";
	print "</div>";
	print "</td>";
	print "</tr>";
}




        print "<tr>";
		print "<td colspan='2'>";
 

        if(isset($qty2) AND $qty2==ARTICLE_EN_COMMANDE AND ((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['products_forsale']=="yes")) {
            ##print "<br>".IN_ORDER_MESSAGE;
            if($actRes=="oui"  AND !isset($_SESSION['devisNumero'])) {
                 print "<input type='hidden' value='".$rowp['products_id']."' name='id'>";
                        print "<input type='hidden' value='".$rowp['products_ref']."' name='ref'>";
                        print "<input type='hidden' value='".$rowp['products_name_'.$_SESSION['lang']]."' name='name'>";
                        print "<input type='hidden' value='".$rowp['products_tax']."' name='productTax'>";
                        print "<input type='hidden' value='".$rowp['products_deee']."' name='deeee'>";
                        print "<input type='hidden' value='reserve' name='statut'>";
               
               	 print "<div id='display_cart'>";
                 print "<br><table width='100%' border='0' cellspacing='2' cellpadding='0' class='backgroundTDColonneModuleLeft'><tr>";
                       if($isThere=="no") {
                          print "<td align='center'>";
                          print BUY_NOW;
                          print "</td>";
                          print "</tr><tr><td align='center'>";
                          print "<input type='text' size='3' maxlength='3' name='amount' value='1'>";
                          print "&nbsp;<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."' align='absmiddle'>";
                          if($expressBuy=="oui") {express_buy($price,15);}
                          print "</td>";
                       }
                       else {
                            if($rowp['products_options'] == "no") {
                                print "<td align='center'>";
                                print BUYED1;
                                print "</td>";
                                print "</tr><tr><td align='center'><b>".RETIRER_DU_CADDIE."</b><br><a href='add.php?amount=0&statut=reserve&ref=".$rowp['products_ref']."&id=".$rowp['products_id']."&name=".$rowp['products_name_'.$_SESSION['lang']]."&productTax=".$rowp['products_tax']."&deee=".$rowp['products_deee']."'><img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'></a></td>";
                             }
                             else {
                                   print "<td align='center'>";
                                   print BUYED1;
                                   print "</td>";
                                   print "</tr><tr><td align='center'>";
                                   print AUTRES_OPTIONS."<br>";
                                   print "<input type='text' size='3' maxlength='3' name='amount' value='1'>";
                                   print "&nbsp;<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' align='absmiddle'>";
                                   if($expressBuy=="oui") {express_buy($price,15);}
                                   print "</td>";
                             }
                       }
                 print "</tr></table>";
                 print "</div>";
            }
        }
       print "</td>";


if($rowp['products_options']=='yes' AND $rowp['products_forsale']=='yes') {

 
       print "</tr><tr>";
	   print "<td colspan='2' class='PromoFont'>";
       print "<hr width='".$tt."'>";
	   print "<div class='PromoFont'><b>".maj(PRODUIT_OPTIONS)."</b>:</div>";
	   print "<div align='left'><img src='im/zzz.gif' width='1' height='3' border='0'></div>";
		
	
	print "<noscript>";
		if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==3 ) {
			print "<div align='center' style='color:#FF0000; font-weight:bold; font-size:13px; border:3px #CC0000 double; background:#FFFFFF; padding:5px;'>".DECLINAISON_NON_REPERTORIEE."</div>";
		}
	print "</noscript>";

$optQuery = mysql_query("SELECT * FROM products_options_stock WHERE products_options_stock_prod_id = '".$_GET['id']."' ORDER BY products_options_stock_prod_name ASC");
if(mysql_num_rows($optQuery)>0) {
	while($optResult = mysql_fetch_array($optQuery)) {
		if($rowp['products_download']=='yes') $downl = "download"; else $downl = "";
		$variations[$optResult['products_options_stock_prod_name']] = trim($optResult['products_options_stock_stock'].",".$optResult['products_options_stock_active'].",".$optResult['products_options_stock_ref'].",".$downl.",".$optResult['products_options_stock_im']);
	}
	if(isset($variations) AND count($variations)>0) {
		foreach($variations AS $key => $value) {
			$passVariationsToJs[] = trim($key).":>".trim($value);
		}
		$toPass = implode('!*!', $passVariationsToJs);
	}
	else {
		$toPass = "";
	}
}

?>
<script type="text/javascript"><!--
function display_stock(x) {
	var actRes = '<?php print $actRes;?>';
	var message = '';
	var variation = '<?php print $toPass;?>';
	var boutikPrice = <?php print $price;?>;
	var price = 0;
	var symbolDevise = '<?php print $symbolDevise;?>';
	var sessionReduc = <?php print (isset($_SESSION['reduc']))? $_SESSION['reduc'] : 0;?>;
	var prixDegressifTable = <?php print (isset($prods2ZNum))? $prods2ZNum : 0;?>;
	var displayPaymentsLogoDesc = <?php print ($displayPaymentsLogoDesc=="oui")? 1 : 0;?>;
	var displayDelivery = <?php print ($displayDelivery=="oui")? 1 : 0;?>;
	var displayShippingLogoDesc = <?php print ($displayShippingLogoDesc=="oui")? 1 : 0;?>;
	var activeEcom = <?php print ($activeEcom=="oui")? 1 : 0;?>;
	var sessionAccount = <?php print (isset($_SESSION['account']))? 1 : 0;?>;
	var displayPriceInShop = <?php print ($displayPriceInShop=='oui')? 1 : 0;?>;
	var state = "";
	var prodName = "<?php print $rowp['products_name_'.$_SESSION['lang']];?>";
	var imageActive = "<?php print $rowp['products_im'];?>";

	for (var i=1; i<=x; i++) {
		valueZ = document.getElementById(i).value;
		option = valueZ.split('/');
		optionZ = option[0].split(',');
		if(message=='') message = optionZ[0]; else message = message + ' | ' + optionZ[0];
		price = price + parseFloat(option[1]);
	}

	priceFinal = price + boutikPrice;
	if(typeof(sessionReduc)!="undefined" && sessionReduc>0) {
		finalDiscount = sessionReduc;
		priceFinal = priceFinal-(priceFinal*sessionReduc/100);
	}
	else {
		finalDiscount = 0;
	}

	var explodeVariation = variation.split('!*!');
	
	for(var i=0; i<explodeVariation.length; i++) {
		result = explodeVariation[i].split(':>');
		result2 = result[1].split(',');
		if(result[0]==message) {
			var ref = result2[2];
			var downl = result2[3];
			var image = result2[4];
			if(result2[0]<=0) {
				message = "<div align='center' class='TABLETitreProductDescription' style='padding:5px;'>";
				message = message + result[0];
				message = message + "<br><img src='im/zzz.gif' width='1' height='7'><br>";
				message = message + "<span style='font-size:12px; color:red'><?php print DECLINAISON_EPUISEE;?></span>";
				message = message + "</div>";
				if(activeEcom==1 || sessionAccount==1) document.getElementById('display_cart').style.display='none';
				document.getElementById('display_stock_bouton').style.display='none';
				if(displayDelivery==1) document.getElementById('display_liv').style.display='none';
				if(displayShippingLogoDesc==1) document.getElementById('mode_liv').style.display='block';
				if(displayPaymentsLogoDesc==1) document.getElementById('payment_logo').style.display='block';
				document.getElementById('displayPrix').style.display='block';
				if(prixDegressifTable!=0) document.getElementById('displayPrixDegressif').style.display='none';
				document.getElementById('display_no_stock').style.display='none';
				state = "<?php print DECLINAISON_EPUISEE;?>";
				
				if(actRes == 'oui') {
					message = "<div align='center' class='TABLETitreProductDescription' style='padding:5px;'>";
					message = message + result[0];
					message = message + "<br><img src='im/zzz.gif' width='1' height='5'><br>";
					message = message + "<span class='fontrouge' style='font-size:13px; font-weight:bold'><?php print '-&nbsp;'.EN_COMMANDE.'&nbsp;-';?></span>";
					message = message + "<br><img src='im/zzz.gif' width='1' height='3'><br>";
					message = message + "<i><?php print EXPEDI;?> <?php print ENTRE;?> <?php print $rowp['products_delay_1b'];?> <?php print ET;?> <?php print $rowp['products_delay_2b'];?> <?php print JOURS_OUVRES;?></i>";
					if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<br><img src='im/zzz.gif' width='1' height='5'><br>";
					if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<span style='font-weight:bold; font-size:11px; color:black'><?php print strtoupper(PRIX);?>: " + priceFinal.toFixed(2) + " " + symbolDevise + "</span>";
					message = message + "</div>";
					if(activeEcom==1 || sessionAccount==1) document.getElementById('display_cart').style.display='block';
					document.getElementById('display_stock_bouton').style.display='block';
					if(displayDelivery==1) document.getElementById('display_liv').style.display='block';
					if(displayShippingLogoDesc==1) document.getElementById('mode_liv').style.display='block';
					if(displayPaymentsLogoDesc==1) document.getElementById('payment_logo').style.display='block';
					document.getElementById('displayPrix').style.display='block';
					if(prixDegressifTable!=0) document.getElementById('displayPrixDegressif').style.display='block';
					document.getElementById('display_no_stock').style.display='none';
					state = "<?php print EN_COMMANDE;?>";
				}
				if(imageActive=='no') image='im/lang' + <?php print $_SESSION['lang'];?> + '/no_image.png';
				displayPrice(boutikPrice, finalDiscount, priceFinal, ref, state, downl);
				displayImage(image, prodName);
			}
			else {
				message = "<div align='center' class='TABLETitreProductDescription' style='padding:5px;'>";
				message = message + result[0];
				message = message + "<br><img src='im/zzz.gif' width='1' height='3'><br>";
				message = message + "<img src='im/lang<?php print $_SESSION['lang'];?>/stockok.png' title='<?php print EN_STOCK;?>' alt='<?php print EN_STOCK;?>'>";
				if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<br><img src='im/zzz.gif' width='1' height='3'><br>";
				if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<span style='font-weight:bold; font-size:11px; color:black'><?php print strtoupper(PRIX);?>: " + priceFinal.toFixed(2) + " " + symbolDevise + "</span><br>";
				message = message + "</div>";
				if(activeEcom==1 || sessionAccount==1) document.getElementById('display_cart').style.display='block';
				document.getElementById('display_stock_bouton').style.display='block';
				if(displayDelivery==1) document.getElementById('display_liv').style.display='block';
				if(displayShippingLogoDesc==1) document.getElementById('mode_liv').style.display='block';
				if(displayPaymentsLogoDesc==1) document.getElementById('payment_logo').style.display='block';
				document.getElementById('displayPrix').style.display='block';
				if(prixDegressifTable!=0) document.getElementById('displayPrixDegressif').style.display='block';
				document.getElementById('display_no_stock').style.display='none';
				state = "<?php print EN_STOCK;?>";
				displayPrice(boutikPrice, finalDiscount, priceFinal, ref, state, downl);
				if(imageActive=='no') image='im/lang' + <?php print $_SESSION['lang'];?> + '/no_image.png';
				displayImage(image, prodName);
			}
			
			if(result2[1]=='no') {
				message = "<div align='center' class='TABLETitreProductDescription' style='padding:5px;'>";
				message = message + result[0];
				message = message + "<br><img src='im/zzz.gif' width='1' height='5'><br>";
				message = message + "<span style='font-size:12px; color:red; font-weight:normal;'><?php print str_replace(". ", ".<br><img src='im/zzz.gif' width='1' height='3'><br>", DECLINAISON_NON_REPERTORIEE);?></span>";
				message = message + "</div>";
				if(activeEcom==1 || sessionAccount==1) document.getElementById('display_cart').style.display='none';
				document.getElementById('display_stock_bouton').style.display='none';
				if(displayDelivery==1) document.getElementById('display_liv').style.display='none';
				if(displayShippingLogoDesc==1) document.getElementById('mode_liv').style.display='none';
				if(displayPaymentsLogoDesc==1) document.getElementById('payment_logo').style.display='none';
				document.getElementById('displayPrix').style.display='none';
				if(prixDegressifTable!=0) document.getElementById('displayPrixDegressif').style.display='none';
				document.getElementById('display_no_stock').style.display='block';
				state = "no exists";
				displayPrice(boutikPrice, finalDiscount, priceFinal, ref, state, downl);
				image='im/lang' + <?php print $_SESSION['lang'];?> + '/no_stock.png';
				displayImage(image, prodName);
			}
		}
	}

	
    var div=document.getElementById("passage");
    while (div.childNodes[0]) {
      div.removeChild(div.childNodes[0]);
    }
    
	var div = document.createElement('div');
    div.innerHTML = message;
    var passagesortie = document.getElementById("passage");
	passagesortie.appendChild(div);
}
--></script>

<div id='passage'></div>

<?php
	   print "</td></tr>";
	   

	$optionQuery = mysql_query("SELECT pr.products_id, pr.products_option, po.products_options_name, pr.note_option_".$_SESSION['lang']."
                                  FROM  products_id_to_products_options_id as pr
                                  LEFT JOIN products_options as po
                                  ON(pr.products_options_id = po.products_options_id)
                                  WHERE pr.products_id = '".$rowp['products_id']."'
                                  ORDER BY po.products_options_name
                                  ASC");
	$optionNum = mysql_num_rows($optionQuery);
	while($optionResult = mysql_fetch_array($optionQuery)) {
		$o[$optionResult['products_options_name']] = $optionResult['products_option']."|";
		$noteOpt[] = $optionResult['note_option_'.$_SESSION['lang']];
	}
	$optionNum = count($o);
	print "<input type='hidden' name='optionNum' value='".$optionNum."'>";
		$keys = array_keys($o);
		$values = array_values($o);
		
		for($x=1; $x<=$optionNum; $x++) {
			print "<tr>";
			print "<td colspan='2'>";
			 
			print "<div align='left'><b>&bull;&nbsp;".$keys[$x-1]."</b>:</div>";
			print "<div align='left'><img src='im/zzz.gif' width='1' height='3' border='0'></div>";
			 
			if(!empty($noteOpt[$x-1])) {
				print "<div align='left'>".$noteOpt[$x-1]."</div>";
				print "<div align='left'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
			}
			
			 
			if(isset($passVariationsToJs) AND count($passVariationsToJs)>0) {
				print "<select name='option[".$x."]' id=".$x." onChange=\"display_stock(".count($keys).");\">";
			}
			else {
				print "<select name='option[".$x."]' id=".$x.">";
			}
			$opt = explode(",",$values[$x-1]);
			$aa = count($opt);
			if(isset($totalOptionPrice)) unset($totalOptionPrice);
			for($xx=1; $xx<=$aa-1; $xx++) {
				$optionFinal = explode("::",$opt[$xx-1]);
				$deleteCar = array("+","-"," ");
				$totalOptionPrice[] = str_replace($deleteCar,"",$optionFinal[1]);
				if(isset($_SESSION['account']) OR $displayPriceInShop=='oui') {
					$euro = (!empty($optionFinal[1]) AND $optionFinal[1]!=='+0.00')? " | ".$optionFinal[1].$symbolDevise : "";
				}
				else {
					$euro = "";
				}
				if($optionFinal[1] == '+0.00') $sel="selected"; else $sel="";
				if(array_sum($totalOptionPrice)==0) $sel="";
				if(!isset($optionFinal[2]) OR $optionFinal[2]=='') $optionFinal[2]=0;
				print "<option value='".$optionFinal[0]."/".$optionFinal[1]."/".$optionFinal[2]."' ".$sel.">".$optionFinal[0].$euro."</option>";
			}
			print "</select>";
			print "<div align='left'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
			print "</td>";
			print "</tr>";
		}
}

 
 
	if(!empty($rowp['products_option_note_'.$_SESSION['lang']]) AND $rowp['products_options'] == 'yes' AND $rowp['products_forsale']=="yes") {
		print "<tr><td colspan='2'>".$rowp['products_option_note_'.$_SESSION['lang']]."</td></tr>";
	}

 
 
if(isset($variations) AND count($variations)>0 AND $rowp['products_download']=='no') {
	 
	$color="TDTableListLine2";
	$i=-1;
	$uu = "<table border='0' cellpadding='3' cellspacing='1' class='TABLEPaymentProcessSelected'><tr height='20'>";
	$uu.= "<td align='center'><b>".PRODUIT_OPTIONS."</b></td>";
	$uu.= "<td align='center' width='60'><b>".VIE_STOCK_DES."</b></td>";
	$uu.= "<td align='center' width='60'><b>".EXPEDI."</b></td>";
	foreach($variations AS $key => $value) {
		$explodeVariations = explode(' | ', $key);
		$keyArray[] = $explodeVariations[0];
	}
	foreach($variations AS $key => $value) {
		$explodeKey = explode(' | ', $key);
		$explodeValue = explode(',', $value);
		$i++;
		if(isset($keyArray[$i-1]) AND $explodeKey[0]==$keyArray[$i-1]) $color="TDTableListLine2"; else $color="TDTableListLine1";
			 
			if($explodeValue[0]>0) $stock = "<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."'>";
			if($explodeValue[0]<=0 AND $actRes=='oui') $stock = "<img src='im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."'>";
			if($explodeValue[0]<=0 AND $actRes=='non') $stock = "<img src='im/stockin.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'>";
			if($explodeValue[1]=='no') {$stock = "<img src='im/no_stock.gif' border='0' title='".NON_DISPONIBLE."' alt='".NON_DISPONIBLE."'>"; $key="<s>".$key."</s>";}
			 
			if($explodeValue[0]>0) $exped = "<img src='im/bull_green.gif' border='0' title='".ENTRE." ".$rowp['products_delay_1']." ".ET." ".$rowp['products_delay_2']." ".JOURS_OUVRES."'>";
			if($explodeValue[0]<=0 AND $actRes=='oui') $exped = "<img src='im/bull_blue.gif' border='0' title='".ENTRE." ".$rowp['products_delay_1b']." ".ET." ".$rowp['products_delay_2b']." ".JOURS_OUVRES."'>";
			if($explodeValue[0]<=0 AND $actRes=='non') $exped = "<img src='im/bull_red.gif' border='0' title='".ITEMS_OUT_OF_STOCK."'>";
			if($explodeValue[1]=='no') {$exped = "<img src='im/bull_red.gif' border='0' title='".NON_DISPONIBLE."' alt='".NON_DISPONIBLE."'>"; $key="<strike>".$key."</strike>";}
			$uu.= "</tr><tr class='".$color."'>";
			$uu.= "<td align='left'>";
			$uu.= $key;
			$uu.= "</td>";
			$uu.= "<td align='center'>";
			$uu.= $stock;
			$uu.= "</td>";
			$uu.= "<td align='center'>";
			$uu.= $exped;
			$uu.= "</td>";
	}
	$uu.= "</tr><tr>";
	$uu.= "<td colspan='3'>";
	$uu.= "<div><img src='im/zzz.gif' width='1' height='1'></div>";
	$uu.= "</td>";
	$uu.= "</tr></table>";

	
	print "<tr>";
	print "<td>";
	
	print "<div align='left'><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
	print "<div align='left'>";
	print "<a href='javascript:void(0)' onclick='Effect.toggle(\"d2\",\"BLIND\"); return false;'>";
	print "<span id='view' onclick='javascript:showSpan(\"hide\"); document.getElementById(\"view\").style.display=\"none\";' class='PromoFont'><img src='im/arrow-down.png' border='0' title='".VOIR_STOCK."' alt='".VOIR_STOCK."' align='absmiddle'>&nbsp;".VOIR_STOCK."</span>";
	print "<span id='hide' onclick='javascript:showSpan(\"view\"); document.getElementById(\"hide\").style.display=\"none\";' style='display:none' class='PromoFont'><img src='im/arrow-up.png' border='0' title='".CACHER_STOCK."' alt='".CACHER_STOCK."' align='absmiddle'>&nbsp;".CACHER_STOCK."</span>";
	print "</a>";
	print "<div id='d2' style='display:none;'>".$uu."</div>";
	print "</div>";
	
	print "<noscript>";
	print $uu;
	print "</noscript>";
	print "</td>";
	print "</tr>"; 
}

	
	
	print "<tr><td colspan='2'>";
	print "<hr width='".$tt."'>";
	print "</td>";
	print "</tr>";

	print "<tr><td colspan='2' class='PromoFont'><b>".DESCRIPTIONMAJ."</b>:</td>";
	print "</tr>";

	print "<tr>";
	print "<td colspan='2' valign='top'>";
	print "<div align='justify'>".$rowp['products_desc_'.$_SESSION['lang']]."</div>";
	print "</td>";
	print "</tr>";
 
	if(!empty($rowp['products_note_'.$_SESSION['lang']])) {
		print "<tr>";
		print "<td colspan='2'>";
		print "<div align='justify'>".$rowp['products_note_'.$_SESSION['lang']]."</div>";
		print "</td>";
		print "</tr>";
	}
 
if(!empty($rowp['products_garantie_'.$_SESSION['lang']])) {
	print "<tr>";
	print "<td colspan='2'>";
	print "<div align='left'><b>".GARANTIE."</b> : ".$rowp['products_garantie_'.$_SESSION['lang']]."</div>";
	print "</td>";
	print "</tr>";
}
	print "</table>";

        



print "</td>";
print "</form>";
print "</tr></table>";

       $dt = explode(" ",date($rowp['products_date_added']));
       $dt = explode("-",$dt[0]);
       print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' align='center'>";
       print "<tr align='center'>";
       if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $rowp['products_forsale']=="yes" AND isset($EnPromo)) {
         print "<td colspan='2' class='FontGris'>".$EnPromo."</td>";
       }
       else {
         print "<td colspan='2'><img src='im/zzz.gif' width='1' height='1'></td>";
       }
       print "</tr>";
       print "</table>";


if($displayRelated == "oui" and !empty($rowp['products_related'])) {
   
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' align='center'>";  
       print "<tr><td colspan='2' height='20'><hr width='".$tt."'></td></tr>";
       print "<tr><td colspan='2'><img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<b>".PRODUITS_AFFILIES."</b></td></tr>";
       print "<tr><td colspan='2'>";
           if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
           
           $expRel = explode("|", $rowp['products_related']);
           $expRelNb = count($expRel)-1;
           for($i=0; $i<=$expRelNb; $i++) {
              $queryRel = mysql_query("SELECT p.products_tax, p.products_deee, p.products_options, p.products_qt, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_image, p.categories_id, p.products_desc_".$_SESSION['lang'].", p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                                        FROM products as p
                                        LEFT JOIN specials as s
                                        ON (p.products_id = s.products_id)
                                       	WHERE p.products_id = '".$expRel[$i]."'
                                       	".$addToQuery."
                                       	AND p.products_visible = 'yes'
                                       	AND p.products_forsale = 'yes'
                                       	ORDER BY p.products_name_".$_SESSION['lang']."
                                       ");
           if(mysql_num_rows($queryRel)>0) {
           $rowRel = mysql_fetch_array($queryRel);

           print "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='TABLE1'><tr>";
			if($displayRelatedImage=="oui") {
				if(!empty($rowRel['products_image'])) {
					 
					if(isset($_SESSION['list']) AND strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
						$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
						$isThere="yes";
					}
					else {
						$intoCart = "";
						$isThere="no";
					}
					$images_width = $ImageSizeDescRelated+20;
					$image_resize_related = resizeImage($rowRel['products_image'],$ImageSizeDescRelated,$images_width);
					
					print "<td width='".$images_width."' align='center'>";
					print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
					
					if($gdOpen == "non") {
						print "<img border='0' src='".$rowRel['products_image']."' width='".$image_resize_related[0]."' height='".$image_resize_related[1]."' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";
					}
					else {
						$infoImage = infoImageFunction($rowRel['products_image'],135,$ImageSizeDescRelated);
						print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowRel['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";                  
					}
					print "</a>";
					print "</td>";
				}
				else {
					
					if(isset($_SESSION['list']) AND strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
						$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
						$isThere="yes";
					}
					else {
						$intoCart = "";
						$isThere="no";
					}
					print "<td>&nbsp;</td>";
				}
		}
		else {
        	print "<td>&nbsp;</td>";
 
            if(isset($_SESSION['list']) AND strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
                $intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
                $isThere="yes";
            }
            else {
               $intoCart = "";
               $isThere="no";
            }
		}

            		   print "<td>";
            		    
                       print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><b>".$rowRel['products_name_'.$_SESSION['lang']]."</b></a>".$intoCart;
                       print "<br>";
                        
                    	$ProdNameProdDesc = $rowRel['products_desc_'.$_SESSION['lang']];
                        $ProdNameProdDesc = strip_tags($ProdNameProdDesc);
                        $maxCarDescAff = $maxCarDesc-60;
                       print adjust_text($ProdNameProdDesc,$maxCarDescAff,"..<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/next.gif' border='0'></a>");
                       print "<br><img src='im/zzz.gif' width='1' height='10'><br>";
                       print "</td>";
                        
                       if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
                       print "<td width='".$largeBoutonCaddie."' align='center'>";
                            $new_price = $rowRel['specials_new_price'];
                            $old_price = $rowRel['products_price'];
                            $promoIs="";
                            
                            if(empty($new_price) OR $new_price=='') {
                                $price = "<b>".$old_price." ".$symbolDevise."</b>";
                                $clientPrice = $old_price;
                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($old_price,$_SESSION['reduc'])."&nbsp;".$symbolDevise."</span></b>";
                                }
                            }
                            else {
                            	if($rowRel['specials_visible']=="yes") {
	                                $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
	                                
	                                $dateMaxCheck = explode("-",$rowRel['specials_last_day']);
	                                $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
	                                $dateDebutCheck = explode("-",$rowRel['specials_first_day']);
	                                $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
	                                
	                                if($dateDebut <= $today  AND $dateMax >= $today) {
	                                    $econPourcent = (1-($rowRel['specials_new_price']/$rowRel['products_price']))*100;
	                                    $econPourcent = sprintf("%0.2f",$econPourcent)."%";
	                                    $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
	                                    $promoIs = 'yes';
	                                    $price = "<b><s>".$old_price."</s> ".$symbolDevise."</b><br><b><span class='fontrouge'>".$new_price."&nbsp;".$symbolDevise."</span></b>";
	                                    $clientPrice = $new_price;
	                                }
	                                else {
	                                    $price = "<b>".$old_price."&nbsp;".$symbolDevise."</b>";
	                                    $clientPrice = $old_price;
	                                }
	                            }
	                            else {
                                	$price = "<b>".$old_price."&nbsp;".$symbolDevise."</b>";
                                	$clientPrice = $old_price;
								}
                                 
                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($clientPrice,$_SESSION['reduc'])."&nbsp;".$symbolDevise."</span></b>";
                                }
                            }
                        print $price;
                        if(isset($price2)) {
                            print "<br>".$price2;
                        }
                        print "</td>";



             
            if((isset($_SESSION['account']) OR $activeEcom=="oui") AND !isset($_SESSION['devisNumero'])) {
            if($rowRel['products_qt'] > 0) {
                if($isThere=="yes") {
                   if($rowRel['products_options'] == 'no') {
                      print "<td width='".$largeBoutonCaddie."' align='center'>";
                	  	 print "<a href='add.php?amount=0&ref=".$rowRel['products_ref']."&id=".$rowRel['products_id']."&name=".$rowRel['products_name_'.$_SESSION['lang']]."&productTax=".$rowRel['products_tax']."&deee=".$rowRel['products_deee']."'><img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'>";
                		 print "</a></td>";
                   }
                   else {
                      print "<td width='".$largeBoutonCaddie."'>";
                	  		 print "<div align='center'>";
                	  		 print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
                			 print "<img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'>";
                		    print "</a>";
                			 print "</div>";
                			 print "</td>";
                   }
                }
                else {
                      if($rowRel['products_options'] == 'no') {
                         print "<td width='".$largeBoutonCaddie."' align='center' valign='middle'>";
                         print "<form action='add.php' method='get'>";
                         print "<br><input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='im/zzz.gif' width='1' height='3'><br>";
                         print "<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
                         print "<input type='hidden' value='".$rowRel['products_id']."' name='id'>";
                         print "<input type='hidden' value='".$rowRel['products_ref']."' name='ref'>";
                         print "<input type='hidden' value='".$rowRel['products_name_'.$_SESSION['lang']]."' name='name'>";
                         print "<input type='hidden' value='".$rowRel['products_tax']."' name='productTax'>";
                         print "<input type='hidden' value='".$rowRel['products_deee']."' name='deee'>";
                         print "</form>";
                         print "</td>";
                      }
                      else {
                         print "<td width='".$largeBoutonCaddie."' align='center'>";
                         print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'></a>";
                         print "</td>";
                      }
                }
            }
            else {
                if($actRes=="non") {
                    print "<td width='".$largeBoutonCaddie."' align='center'>";
                    print "<img src='im/cart_out.png' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."'>";
                    print "</td>";
                }
                else {
                    print "<td width='".$largeBoutonCaddie."' align='center'>";
                    print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_out.png' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a>";
                    print "</td>";
                }
            }
            }
                       }
		   print "</tr></table>";
            		   print "<img src='im/zzz.gif' width='1' height='8'>";
            		}
           }
       print "</td></tr>";
print "</table>";
}

if(isset($refererUrl)) {
	print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='5' align='center'><tr>";
	print "<td>";
	print "<div align='center'>";

	print "</div>";
	print "</td>";
	print "</tr></table>";
}

?>
            </table>

<?php 
}
else {
	print "<p align='center' class='fontrouge'><b>".DESOLE."</b></p>";
}
?>
</td>

<?php 
		  
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
?>
</tr>
</table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
