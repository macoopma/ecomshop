<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
include('includes/lijst_navigatie.php');


if((!isset($_GET['path']) OR $_GET['path']=='') AND !isset($_GET['target'])) {
    pathNotSet("L");
}
if(isset($_GET['path'])) {
    $catTitleMain = getSubCatId($_GET['path']);
    $cat_name = getSubCatName($catTitleMain);
}

if(!isset($_GET['page']) OR $_GET['page']=='') $_GET['page']=0;
if(!isset($_GET['sort'])) $_GET['sort'] = $defaultOrder;

 
function express_buy($priceZ) {
   GLOBAL $minimumOrder, $_SESSION;
   if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
     $priceZ = newPrice($priceZ,$_SESSION['reduc']);
   }
   if($priceZ > $minimumOrder) {
      print "<input type='image' src='im/lang".$_SESSION['lang']."/achat_express.png' value='".ACHAT_EXPRESS."' name='express' style='BACKGROUND:none; border:0px' title='".ACHAT_EXPRESS."' alt='".ACHAT_EXPRESS."'>";
   }
}

if(!isset($_GET['target'])) {
    $BarreMenuHautTitre = $cat_name;
    }
if(isset($_GET['target']) AND $_GET['target'] == "new") {
    $BarreMenuHautTitre = NOUVEAUTES;
    }
if(isset($_GET['target']) AND $_GET['target'] == "promo") {
    $BarreMenuHautTitre = PROMOTIONS;
    if($_GET['sort']==$defaultOrder) $_GET['sort'] = "specials_last_day";
    }
if(isset($_GET['target']) AND $_GET['target'] == "author") {
    $BarreMenuHautTitre = MENU_RAPIDE3;
    }
if(isset($_GET['target']) AND $_GET['target'] == "favorite") {
    $BarreMenuHautTitre = COEUR;
    }
if(isset($_GET['tow']) AND $_GET['tow'] == "flash") {
    $BarreMenuHautTitre = VENTES_FLASH;
    }

$asc = "ASC";
if(isset($_GET['sort']) AND $_GET['sort']=="id") {$_GET['sort']="products_id"; $order = "id"; $asc = "DESC";}
if(isset($_GET['sort']) AND $_GET['sort']=="Id") {$_GET['sort']="products_id"; $order = "Id"; $asc = "DESC";}
if(isset($_GET['sort']) AND $_GET['sort']=="Ref") {$_GET['sort']="products_ref"; $order = "Ref";}
if(isset($_GET['sort']) AND $_GET['sort']=="Artikel") {$_GET['sort']="products_name_".$_SESSION['lang']; $order = "Artikel";}
if(isset($_GET['sort']) AND $_GET['sort']=="Prix") {$_GET['sort']="ord"; $order = "Prix";}
if(isset($_GET['sort']) AND $_GET['sort']=="Compagnie") {$_GET['sort']="fournisseurs_id"; $order = "Compagnie";}
if(isset($_GET['sort']) AND $_GET['sort']=="Les_plus_populaires") {$_GET['sort']="products_viewed"; $asc = "DESC"; $order = "Les_plus_populaires";}
if(isset($_GET['sort']) AND $_GET['sort']=="specials_last_day") {$_GET['sort']="specials_last_day"; $asc = "ASC"; $order = "specials_last_day";}

if(!isset($_GET['sort']) OR $_GET['sort']=="") {
	$order = $defaultOrder;
	if($defaultOrder == "Id") $_GET['sort']="products_id";
	if($defaultOrder == "id") $_GET['sort']="products_id";
	if($defaultOrder == "Ref") $_GET['sort']="products_ref";
	if($defaultOrder == "Artikel") $_GET['sort']="products_name_".$_SESSION['lang'];
	if($defaultOrder == "Prix") {$_GET['sort']="ord";}
	if($defaultOrder == "Compagnie") $_GET['sort']="fournisseurs_id";
	if($defaultOrder == "Les_plus_populaires") $_GET['sort']="products_viewed";
	$asc = "DESC";
}

$title = $BarreMenuHautTitre;
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";

if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}

  
    function titleSort() {
            GLOBAL $order,$titre_order_1,$titre_order_2,$titre_order_3,$mode;
            $orderLang = $order;
            $uu = ${'titre_order_'.$_SESSION['lang']};
            $orderLang = explode("|",$uu);
            $sortTitle = $orderLang[$mode];
            if($order=="specials_last_day") {$sortTitle = DATE_FIN_PROMO;}
            if($order=="id") {$sortTitle = NOUVEAUTES;}
            return $sortTitle;
    }
   
    function titleSortDefault() {
            GLOBAL $defaultOrder,$titre_order_1,$titre_order_2,$titre_order_3;
            if($defaultOrder !== "Id") {
                $orderLang = $defaultOrder;
                $titreOrderLang1 = explode("|",$titre_order_1);
                $mode = array_search($defaultOrder,$titreOrderLang1);
                $uu = ${'titre_order_'.$_SESSION['lang']};
                $orderLang = explode("|",$uu);
                $sortTitle = $orderLang[$mode];
            }
            else {
                $sortTitle = "Id";
            }
            return $sortTitle;
    }

 
if(!isset($_GET['target'])) {
	$select = "SELECT p.products_forsale, p.fabricant_id, p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_id, p.products_download, p.products_exclusive, p.categories_id, p.categories_id_bis, p.fournisseurs_id, p.products_desc_".$_SESSION['lang'].", p.products_options, p.products_price, p.products_ref, p.products_im, p.products_image, p.products_visible, p.products_tax, p.products_date_added, p.products_qt, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company,
					IF(s.specials_new_price < p.products_price
					AND s.specials_last_day >= NOW()
					AND s.specials_first_day <= NOW(),
					s.specials_new_price, p.products_price) as ord
				FROM products as p
				LEFT JOIN fournisseurs as f
				ON (p.fournisseurs_id = f.fournisseurs_id)
				LEFT JOIN specials as s
				ON (p.products_id = s.products_id)
				WHERE p.categories_id = '".$_GET['path']."'
				AND p.products_ref != 'GC100'
				AND p.products_visible = 'yes'
				".$addToQuery."
				OR (p.categories_id_bis LIKE '%|".$_GET['path']."|%' AND p.products_visible = 'yes')";

   $result = mysql_query($select);
   $tototal =  mysql_num_rows($result);
    
   if($tototal <= $nbre_ligne) {
      if(isset($_GET['page']) AND $_GET['page']!==0) {
         $nPage = "&page=".$_GET['page'];
      }
      else {
         $nPage = "";
      }
   }
   else {
      $nPage = "";
   }
    
   if($order !== $defaultOrder) $nSort = "&sort=".$order; else $nSort="";

   $select.= " ORDER BY ".$_GET['sort']." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;
   $result = mysql_query($select);

   $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['path']."&sort=".$order."&page=";
   $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['path'];
   $DescLink = "path=".$_GET['path'].$nSort.$nPage;
}
 
if(isset($_GET['target']) AND $_GET['target'] == "favorite") {
   if(isset($_GET['view'])) {
           $AddQuery = " AND p.categories_id = ".$_GET['view']."  ";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=favorite&sort=".$order."&view=".$_GET['view']."&page=";
           $DescLink = "path=".$_GET['view']."&target=favorite&view=".$_GET['view'];
           $ClassLink = $_SERVER['PHP_SELF']."?target=favorite&view=".$_GET['view'];
   }
   else {
           $AddQuery = "";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=favorite&sort=".$order."&page=";
           $DescLink = "target=favorite";
           $ClassLink = $_SERVER['PHP_SELF']."?target=favorite";
  }

	$selectP = "SELECT p.products_forsale,p.fabricant_id, p.products_name_".$_SESSION['lang'].",p.products_deee, p.products_download, p.products_exclusive, p.products_id, p.categories_id, p.fournisseurs_id, p.fabricant_id, p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
   
   $result = mysql_query($select);
   $resultP = mysql_query($selectP);
   $tototal = mysql_num_rows($result);
    
   if($tototal > $nbre_ligne AND (isset($_GET['page']) AND $_GET['page']!==0)) $DescLink = $DescLink."&page=".$_GET['page'];
   
   

   if($order !== $defaultOrder) $DescLink = $DescLink."&sort=".$order;
   
   $select.= " ORDER BY ".$_GET['sort']." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result=mysql_query($select);
}

if(isset($_GET['target']) AND $_GET['target'] == "author") {
    if(isset($_GET['authorid'])) {
           $AddQuery = " AND p.fabricant_id = ".$_GET['authorid']." "; 
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=author&sort=".$order."&authorid=".$_GET['authorid']."&page=";
           $DescLink = "target=author&authorid=".$_GET['authorid'];
           $ClassLink = $_SERVER['PHP_SELF']."?target=author&authorid=".$_GET['authorid'];
    }
    else {
           $AddQuery = "";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=author&sort=".$order."&page=";
           $DescLink = "target=author";
           $ClassLink = $_SERVER['PHP_SELF']."?target=author";    
    }
   $selectP = "SELECT p.products_forsale, p.fabricant_id, p.products_name_".$_SESSION['lang'].",p.products_deee, p.products_download, p.products_exclusive, p.products_id, p.categories_id, p.fournisseurs_id, p.fabricant_id, p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
   
   $result = mysql_query($select);
   $resultP = mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
   
    
   if($tototal > $nbre_ligne AND (isset($_GET['page']) AND $_GET['page']!==0)) $DescLink = $DescLink."&page=".$_GET['page'];
   
   
   
   if($order !== $defaultOrder) $DescLink = $DescLink."&sort=".$order;

   $select.= " ORDER BY ".$_GET['sort']." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result = mysql_query($select);
}
 

if(isset($_GET['target']) AND $_GET['target'] == "new") {
   if(isset($_GET['view'])) {
           $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=new&sort=".$order."&view=".$_GET['view']."&page=";
           $DescLink = "path=".$_GET['view']."&target=new&view=".$_GET['view'];
           $ClassLink = $_SERVER['PHP_SELF']."?target=new&view=".$_GET['view'];
   }
   else {
           $AddQuery = "";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=new&sort=".$order."&page=";
           $DescLink = "target=new";
           $ClassLink = $_SERVER['PHP_SELF']."?target=new";
   }
   $selectP = "SELECT p.products_forsale, p.fabricant_id, p.products_name_".$_SESSION['lang'].",p.products_deee, p.products_id, p.products_download, p.products_exclusive, p.categories_id,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
   
   $result = mysql_query($select);
   $resultP = mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
    
   if($tototal > $nbre_ligne AND (isset($_GET['page']) AND $_GET['page']!==0)) $DescLink = $DescLink."&page=".$_GET['page'];
    

    
   if($order !== $defaultOrder) $DescLink = $DescLink."&sort=".$order;
   
   $select.= " ORDER BY ".$_GET['sort']." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result = mysql_query($select);
}


if(isset($_GET['target']) AND $_GET['target'] == "promo") {
   if(isset($_GET['view'])) {
                if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                     $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                     $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (p.categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view']."&tow=flash&page=";
                     $DescLink = "path=".$_GET['view']."&target=promo&view=".$_GET['view']."&tow=flash";
                     $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&view=".$_GET['view']."&tow=flash";
                    
                } else {
                     $addQueryFlash = "";
                     $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (p.categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view']."&page=";
                     $DescLink = "path=".$_GET['view']."&target=promo&view=".$_GET['view'];
                     $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&view=".$_GET['view'];
                }
    }
    else {
                if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                     $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                     $AddQuery = "";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=promo&sort=".$order."&tow=flash&page=";
                     $DescLink = "target=promo&tow=flash";
                     $ClassLink = $_SERVER['PHP_SELF']."?target=promo&tow=flash";
                } 
                else {
                     $addQueryFlash = "";
                     $AddQuery = "";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=promo&sort=".$order."&page=";
                     $DescLink = "target=promo";
                     $ClassLink = $_SERVER['PHP_SELF']."?target=promo";
                }
    }
    
    $selectP = "SELECT p.products_forsale,p.fabricant_id, p.products_name_".$_SESSION['lang'].",p.products_deee, p.products_id,p.products_download,p.products_exclusive,p.categories_id,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt, c.categories_id, f.fournisseurs_company, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, c.categories_name_".$_SESSION['lang'].",
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
                AND p.products_forsale='yes'
                ".$addToQuery."
                ".$addQueryFlash."
                AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)";
                        
   $select = $selectP." ".$AddQuery;
   $result = mysql_query($select);
   $resultP = mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
    
   if($tototal > $nbre_ligne AND (isset($_GET['page']) AND $_GET['page']!==0)) $DescLink = $DescLink."&page=".$_GET['page'];
    

    
   if($order !== "specials_last_day") $DescLink = $DescLink."&sort=".$order;
   $select.= " ORDER BY ".$_GET['sort'].", specials_first_day ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;
   $result = mysql_query($select);
}
?>
<html>

<?php
if(isset($_GET['target']) AND !isset($_GET['tow'])) $targetF = $_GET['target']; 
if(isset($_GET['target']) AND isset($_GET['tow'])) $targetF = $_GET['tow'];
if(!isset($_GET['target'])) $targetF = "";
if(isset($_GET['action'])) $actionF = $_GET['action']; else $actionF = "e";
if(!isset($_GET['path'])) $pathF = ""; else $pathF = $_GET['path'];

 
 
if(isset($_GET['path'])) {
    $titrePath = $cat_name;
    $keysFromTitle = str_replace(' | ',', ',$titrePath);
    $keysFromTitle = str_replace(' > ',', ',$keysFromTitle);
    $descFromTitle = str_replace(' | ',' ',$titrePath);
    $description = $descFromTitle;
    $keywords = $keysFromTitle;

	$findMetaQuery = mysql_query("SELECT categories_meta_title_".$_SESSION['lang'].", categories_meta_description_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['path']."'") or die (mysql_error());
	$findMetaResult = mysql_fetch_array($findMetaQuery);
	if($findMetaResult['categories_meta_title_'.$_SESSION['lang']]!=='') $title = $findMetaResult['categories_meta_title_'.$_SESSION['lang']];
	if($findMetaResult['categories_meta_description_'.$_SESSION['lang']]!=='') $description = $findMetaResult['categories_meta_description_'.$_SESSION['lang']];
}

if(isset($_GET['target'])) {
    if($_GET['target']=="promo" AND !isset($_GET['tow'])) {
        $firstWord = (isset($cat_name))? PROMOTIONS." | ".$cat_name : PROMOTIONS;
        if($activeRSS=="oui") {
            $rssTo = "&nbsp;&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_promoties.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".PROMOTIONS."' alt='RSS ".PROMOTIONS."'></a>";
        }
    }
    if($_GET['target']=="promo" AND isset($_GET['tow']) AND $_GET['tow']=="flash") {
        $firstWord = (isset($cat_name))? VENTES_FLASH." | ".$cat_name : VENTES_FLASH;
        if($activeRSS=="oui") {
            $rssTo = "&nbsp;&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_flash.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".VENTES_FLASH."' alt='RSS ".VENTES_FLASH."'></a>";
        }
    }
    if($_GET['target']=="new") {
        $firstWord = (isset($cat_name))? NOUVEAUTES." | ".$cat_name : NOUVEAUTES;
        if($activeRSS=="oui") {
            $rssTo = "&nbsp;&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_nieuws.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".NOUVEAUTES."' alt='RSS ".NOUVEAUTES."'></a>";
        }
    }
    if($_GET['target']=="favorite") {
        $firstWord = (isset($cat_name))? COEUR." | ".$cat_name : COEUR;
    }
    if($_GET['target']=="author") {
        $firstWord = (isset($cat_name))? COMPAGNIE2." | ".$cat_name : COMPAGNIE2;
    }
    
    $description = str_replace(" | "," ",$firstWord)." - ".$store_name." - ".$description;
    $keywords = str_replace(" | ",", ",$firstWord).", ".$store_name.", ".$keywords;
}
?>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php

include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

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
         <td>
         <b>
            <?php
             
            getPath2($pathF,"top",$catId=0,$_SESSION['tree2'],$actionF,$targetF,$_SESSION['lang']);
            if(isset($rssTo)) print $rssTo;
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


                  <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
                  <tr>
                  <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
               <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
               <tr>
               <td align="left">
                <?php
                     
                    getPath2($pathF,"bas",$catId=0,$_SESSION['tree2'],$actionF,$targetF,$_SESSION['lang']);
                    if(isset($rssTo)) print $rssTo;
                ?>
               </td>
               </tr>
               </table>
               <br>
<?php
}

if($addCommuniqueList=="oui") {
   include('includes/communicatie.php');
}


 

    if(isset($_GET['path'])) {
      $resultm = mysql_query("SELECT categories_comment_".$_SESSION['lang'].", categories_image FROM categories WHERE categories_id = '".$_GET['path']."'");
      $catm = mysql_fetch_array($resultm);
      $h = 60;
        if(($catm['categories_image']) !== "im/zzz.gif") {
            $image_resize_cat_top = resizeImage($catm['categories_image'],$h,$h);
            print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
            print "<td width='100%' align='left' valign='top'>";
            print "<img src='".$catm['categories_image']."' width='".$image_resize_cat_top[0]."' height='".$image_resize_cat_top[1]."' border='0' align='left' style='padding:3px'>";
        }
        else {
            print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr><td align='left'>";
        }
        
        if(!empty($catm['categories_comment_'.$_SESSION['lang']])) {
            print $catm['categories_comment_'.$_SESSION['lang']];
            print "</td>";
            print "</tr>";
            print "</table><br>";

        }
        else {
            print "</td>";
            print "</tr>";
            print "</table>";
        }
    }
 
      $result2 = mysql_query("SELECT text_list_".$_SESSION['lang']." FROM admin");
      $rowpNum = mysql_num_rows($result2);
      if($rowpNum>0) {
      	$rowp = mysql_fetch_array($result2);
      	print "<div align='left'>".$rowp['text_list_'.$_SESSION['lang']]."</div>";
      }


 
   print "<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'><tr><td>";

 
if($tototal > $nbre_ligne) {
   print "<table border='0' width='100%' cellspacing='0' cellpadding='2' align='center'>";
   print "<tr>";
   print "<td valign='bottom'>";
           displayPageNum($nbre_ligne);
   print "</td></tr>";
   print "</table>";
}
else {
    print "<br>";
}

 
if($tototal == 0) print "<br>";

 
    if($tototal == 0) { 
        print "</td></tr></table>";
        if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
            print "<div align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE_FLASH."</div>";
        }
        else {
            print "<p align='center' class='fontrouge'><b>".PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE."</b></p>";
        }
    }
    else {
    
    
    
	
	

if($_SERVER['REQUEST_URI']) {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER['REQUEST_URI'];
}
else {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
}


print "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='TABLESortByCentre'><tr><td>";

if($tototal > 2 OR (isset($_GET['target']) AND ($_GET['target'] == "new" OR $_GET['target'] == "promo" OR $_GET['target'] == "favorite" OR $_GET['target'] == "author"))) {
	print "<table width='100%' border='0' cellspacing='0' cellpadding='5' style='BORDER-BOTTOM:1px dotted #CCCCCC; background:#F1F1F1'>";
	print "<tr>";
	print "<td colspan='4' align='left' valign='bottom'>";
	
	print "<table width='100%' height='20' border='0' cellspacing='0' cellpadding='0' align='center'>";
	print "<tr>";
	if($tototal > 2) {
		if($titre_order_1 !== "") {
	   		if($_GET['page'] == 0) {
		        
		        $titreOrderLang = explode("|",$titreOrder);
		        $titreOrderLang1 = explode("|",$titre_order_1);
		        $mode = array_search($order,$titreOrderLang1);
		        $iMax = count($titreOrderLang)-1;
		        $a2 = 1;
		        print "<form action=''>";
		        print "<td>";
		        
		        
		        
		        
		        
		        print "<select name='path' onChange='sendIt(this.options[selectedIndex].value)'>";
		        print "<option value=''>* ".CLASSER_PAR." *</option>";
		        print "<option value=''>------------------</option>";
		        	for($i=0; $i<=$iMax; $i++) {
		            	if($titreOrderLang[$i] !== "") {
		                	print "<option value='".$ClassLink."&sort=".$titreOrderLang1[$i]."'>".$titreOrderLang[$i]."</option>";
		                }
		            }
		        print "</select>";
		    	
		    	if(isset($order) AND $order !== $defaultOrder) {
		        	print "&nbsp;".titleSort();
		    	}
		    	else {
		        	print "&nbsp;".titleSortDefault();
		    	}
		    	
		    	print "</td>";
		    	print "</form>";
			}
			else {
	        	if(isset($order)) {
		            if($order!== $defaultOrder) {
		            	$titreOrderLang1 = explode("|",$titre_order_1);
		            	$mode = array_search($order,$titreOrderLang1);
		            	print "<td><img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR2." <b>".titleSort()."</b></td>";
		            }
		            else {
		            	if($order=="specials_last_day") {$order2 = DATE_FIN_PROMO;} else {$order2 = titleSortDefault();}
		            	print "<td><img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR2." <b>".$order2."</b></td>";
		        	}
	        	}
	        	else {
	            	print "<td><img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR2." <b>".titleSortDefault()."</b></td>";
	       		}
	    	} 
	    }
	}
	else {
		print "<td>&nbsp;</td>";
	}
	
	
	if(isset($_GET['target']) AND ($_GET['target'] == "new" OR $_GET['target'] == "promo" OR $_GET['target'] == "favorite")) {
    	print "<td valign='top'>";
    	while($to = mysql_fetch_array($resultP)) {
    		$categ[] = $to['categories_name_'.$_SESSION['lang']]."|".$to['categories_id'];
    	}
		$categ = array_unique($categ);
		asort($categ);
		$categ = array_values($categ);   
			print "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
			print "<form action= ''>";
			print "<tr>";
			print "<td align='right'>";
			print "<select name='path' onChange='sendIt(this.options[selectedIndex].value)' >";
			print "<option value=''>* $BarreMenuHautTitre *</option>";
			print "<option value=''>------------------</option>";
			if(isset($_GET['tow']) AND $_GET['tow']=="flash") { $addLinkFlash="tow=flash&";} else {$addLinkFlash="";}
			print "<option value='list.php?sort=".$order."&".$addLinkFlash."target=".$_GET['target']."'>".maj(TOUTES)."</option>";
			print "<option value=''>------------------</option>";
		        for($ccc=0; $ccc<=sizeof($categ)-1; $ccc++) {
		        	$exp = explode("|",$categ[$ccc]);
		        	if(isset($_GET['view']) AND $exp[1] == $_GET['view']) {$selSort2="selected";} else {$selSort2="";}
		        	if(isset($_GET['tow']) AND $_GET['tow']=="flash") { $addLinkFlash="tow=flash&";} else {$addLinkFlash="";}
		        	print "<option $selSort2 value='list.php?path=".$exp[1]."&target=".$_GET['target']."&sort=".$order."&".$addLinkFlash."view=".$exp[1]."'>".$exp[0]."</option>";
		        	$ti[$exp[1]] = array($exp[0]);
		        }
			print "</select>";
			if(isset($_GET['view'])) $titleF = $ti[$_GET['view']][0]; else $titleF = TOUTES;
			print "</td>";
			print "</tr>";
			print "</form>";
			print "</table>";
			print "</td>";
	}
	print "</tr>";
	print "</table>";
	
	print "</td></tr>";
	print "</table>";
}






























while($a_row = mysql_fetch_array($result)) {
		$d = (isset($d) AND $d==2)? 1 : 2;
		

		 
		$new_price = $a_row['specials_new_price'];
		$old_price = $a_row['products_price'];
		$promoIs="";

		if(empty($new_price)) {
            $price = "<b>".$old_price." ".$symbolDevise."</b>";
            $new_price = $old_price;
        }
        else {
        	if($a_row['specials_visible']=="yes") {
	        	$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$dateMaxCheck = explode("-",$a_row['specials_last_day']);
				$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
				$dateDebutCheck = explode("-",$a_row['specials_first_day']);
				$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
			
				if($dateDebut <= $today  AND $dateMax >= $today) {
					$econPourcent = (1-($a_row['specials_new_price']/$a_row['products_price']))*100;
					$econPourcent = sprintf("%0.2f",$econPourcent)."%";
					$itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
					$promoIs = 'yes';
					$price = "<b><span class='PromoFont'><s>".$old_price."</s> ".$symbolDevise."</span></b><br><b><span class='PromoFontColorNumber'>".$new_price." ".$symbolDevise."</span></b>";
				}
				else {
					$price = "<b>".$old_price." ".$symbolDevise."</b>";
					$new_price = $old_price;
				}
			}
			else {
            	$price = "<b>".$old_price." ".$symbolDevise."</b>";
            	$new_price = $old_price;
			}
        }
        if($old_price=="0.00") $price="";

         
        if(isset($_SESSION['list']) AND strstr($_SESSION['list'], "+".$a_row['products_ref']."+")) {
        	$caddie_yes = "<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
        	$isThere = "yes";
        }
        else {
        	$caddie_yes = "&nbsp;";
        	$isThere = "no";
        }
        
        

		print "<table width='100%' border='0' cellspacing='0' cellpadding='2'>";
      	print "<form action='add.php' method='get'>";
		print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='TDTableListLine".$d."'>";
		
		if($a_row['products_im']=="yes" AND !empty($a_row['products_image']) AND $a_row['products_image']!=="im/no_image_small.gif") {
			$images_widthList = $haut_im+20;
			$image_resizeList = resizeImage($a_row['products_image'],$haut_im,$images_widthList);
			
			print "<td rowspan='3' width='".$images_widthList."' valign='middle' align='center'>";
			print "<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'>";
			
			if($gdOpen == "non") {
				if($listPop=="oui") {
					$popSize = @getimagesize($a_row['products_image']);
					if(!$popSize) $a_row['products_image']="im/zzz_gris.gif";
					if($popSize[1] > $haut_im) {
						print "<img src='".$a_row['products_image']."' onmouseover=\"trailOn('".$a_row['products_image']."','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" border=\"0\" width=\"".$image_resizeList[0]."\" height=\"".$image_resizeList[1]."\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";                                           
					}
					else {
						print "<img src='".$a_row['products_image']."' onmouseover=\"trailOn('im/zzz.gif','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" border=\"0\" width=\"".$image_resizeList[0]."\" height=\"".$image_resizeList[1]."\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";
					}
				}
				else {
					print "<img src='".$a_row['products_image']."' width='".$image_resizeList[0]."' height='".$image_resizeList[1]."' alt='".$a_row['products_name_'.$_SESSION['lang']]."' border='0'>";
				}
			}
			else {
				$popSize = @getimagesize($a_row['products_image']);
				if(!$popSize) $a_row['products_image']="im/zzz_gris.gif";
				$infoImage = infoImageFunction($a_row['products_image'],$images_widthList,$haut_im);
				if($listPop=="oui") {
					if($popSize[1] > $haut_im) {
						print "<img onmouseover=\"trailOn('".$a_row['products_image']."','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" src=\"mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."\" border=\"0\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";
					}
					else {
						if($popSize[0]>$images_widthList) {
							$rapport2 = $popSize[0]/$popSize[1];
							$width2 = $images_widthList;
							$height2 = $width2/$rapport2;
							print "<img onmouseover=\"trailOn('im/zzz.gif','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" src=\"mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$width2."&hauteur=".$height2."\" border=\"0\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";
						}
						else {
							print "<img onmouseover=\"trailOn('im/zzz.gif','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" src=\"mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[1]."&hauteur=".$infoImage[2]."\" border=\"0\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";
						}
					}
				}
				else {
					print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border=\"0\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\" title=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";                  
				}
			}
			print "</a>";
			print "</td>";
		}
        else {
			$images_widthList = $haut_im+20;
			print "<td width='".$images_widthList."' rowspan='3' valign='middle' align='center'>";
			print "<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'>";
			if($listPop=="oui") {
		   		$image_resizeList = resizeImage("im/lang".$_SESSION['lang']."/no_image.png",$haut_im,$images_widthList);
		  		print "<img src='im/lang".$_SESSION['lang']."/no_image.png' onmouseover=\"trailOn('im/zzz.gif','".$a_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" border=\"0\" width=\"".$image_resizeList[0]."\" height=\"".$image_resizeList[1]."\" alt=\"".$a_row['products_name_'.$_SESSION['lang']]."\">";
			}
			else {
				$image_resizeList = resizeImage("im/lang".$_SESSION['lang']."/no_image.png",$haut_im,$images_widthList);
		  		print "<img src='im/lang".$_SESSION['lang']."/no_image.png' border='0' width='".$image_resizeList[0]."' height='".$image_resizeList[1]."'>";
			}
			print "</a>";
			print "</td>";
		}

        
        if($a_row['products_qt']>0) {
            $dispo = $openLeg."&nbsp;<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
        }
        else {
            if($actRes=="oui") {$dispo = $openLeg."&nbsp;<img src='im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>";} else {$dispo = $openLeg."&nbsp;<img src='im/stockno.gif' border='0' title='".NOT_IN_STOCK."' align='absmiddle'></a>";}
        }
        
        if($a_row['products_download'] == "yes") {
            $prodDown = $openLeg."&nbsp;<img src='im/download.gif' border='0' title='".ARTICLE_TELE."' alt='".ARTICLE_TELE."' align='absmiddle'></a>";
        } else {
            $prodDown = "";
        }
      
      if($a_row['products_exclusive']=="yes") {
            if(isset($_GET['target']) AND $_GET['target'] == "favorite") {
                $getHart = "&nbsp;<img src='im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."' align='absmiddle'>";
            } else {
                $getHart = "&nbsp;<a href='list.php?target=favorite'><img src='im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."' align='absmiddle'></a>";
            }
        }
        else {
            $getHart="";
        }
      	
		if(in_array($a_row['products_id'], $_SESSION['getNewsId'])) {
			$prodNew = "&nbsp;<img src='im/lang".$_SESSION['lang']."/new.gif' border='0' title='".NOUVEAUTES."' alt='".NOUVEAUTES."' align='absmiddle'>";
		} else {
			$prodNew = "";
		}
		
		if(in_array($a_row['products_id'], $_SESSION['discountQt']) AND $a_row['products_forsale']=='yes') {
			$prodDegressif = $openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
		} else {
			$prodDegressif = "";
		}
      	
        if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo AND $a_row['products_forsale']=="yes") {
            if(isset($_GET['tow']) AND $_GET['tow'] == "flash") {
                $vFlash = "&nbsp;<img src='im/time_anim.gif' border='0' title='".VENTES_FLASH."' alt='".VENTES_FLASH."' align='absmiddle'>";
            }
            else {
                $vFlash = "&nbsp;<a href='list.php?target=promo&tow=flash'><img src='im/time_anim.gif' border='0' title='".VENTES_FLASH."' alt='".VENTES_FLASH."' align='absmiddle'></a>";
            }
        }
        else {
        	$vFlash = "";
        }
        
        if($a_row['products_forsale'] == "no") {
            $prodOut = $openLeg."&nbsp;<img src='im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
        } else {
            $prodOut = "";
        }

		print "<td>";
	    	print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
			print "<td align='left' valign='bottom'>";
		    	print "<div align='left'>";
				print "<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'><b>".$a_row['products_name_'.$_SESSION['lang']]."</b></a>";
				print "&nbsp;";
				if($prodOut=="") {
					print $prodNew;
					print $caddie_yes;
					print "<br>";
					if($prodDown=='') print $dispo;
					print $prodDown;
					print $getHart;
					print $vFlash;
					print $prodDegressif;
		      	}
		      	else {
		      		print "<br>";
		         	print $prodOut;
		      	}
		     	 
		      	if(isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo AND $a_row['products_forsale']=="yes") {
		        	if(isset($itMiss) AND $itMiss>0) {
		            	print "&nbsp;(<b>".$itMiss."</b> ";
		                ($itMiss>1)? print strtolower(JOURS)." " : print strtolower(JOURS);
		                print ")&nbsp;";
		            }
		            else {
		                print "&nbsp;<span class='fontrouge'>".AUX_ABONNES_DE_LA_NEWSLETTER." !";
		            }
		    	}
				print "</div>";
			print "</td>";
			
			print "<td align='right' valign='top'>";
			print "&nbsp;<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'><img src='im/lang".$_SESSION['lang']."/more.gif' border='0'></a>&nbsp;";
			print "</td>";
			print "</tr><tr>";
			print "<td colspan='10' style='padding:2px 0px 2px 0px'>";
			
			$ProdNameProdList = $a_row['products_desc_'.$_SESSION['lang']];
			$ProdNameProdList = strip_tags($ProdNameProdList);
			print adjust_text($ProdNameProdList,$maxCarDesc,"..<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'><img src='im/next.gif' border='0' align='absmiddle'></a>");
			print "</td>";
			print "</tr><tr>";
			
			print "<td colspan='10' style='padding:2px 0px 2px 0px'>";
			print "<table border='0' width='100%' align='left' cellspacing='0' cellpadding='0'><tr>";
			if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $a_row['products_forsale']=="yes" AND $price!=="") {
				if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
					print "<td align='left'>";
					print "<table border='0' width='100%' align='left' cellspacing='0' cellpadding='3'><tr>";
					print "<td width='50%'>";
					print "<span class='PromoFont'>".$price."</span>";
					if($new_price>0) {
						print "<br>";
						print "<b>".VOTRE_PRIX.": <span class='FontColorTotalPrice'>".newPrice($new_price,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
					}
					if($devise2Visible=="oui" AND $new_price>0) print curPrice($new_price,$symbolDevise2,"left");
					print "</td>";
					
					if($expressBuy=="oui" AND $a_row['products_options'] == 'no' AND $a_row['products_qt'] > 0 AND $isThere=="no" AND !isset($_SESSION['devisNumero'])) {
						print "<td valign='middle' align='right'>";
						express_buy($new_price);
						print "</td>";
					}
					print "</tr></table>";
					print "</td>";
				}
				else {
					print "<td align='left'>";
					print "<table border='0' width='100%' align='left' cellspacing='0' cellpadding='3'><tr>";
					print "<td class='PromoFont' width='50%'>";
					print $price;
					if($devise2Visible=="oui" AND $new_price>0) print curPrice($new_price,$symbolDevise2,"left");
					
					if($expressBuy=="oui" AND $a_row['products_options'] == 'no' AND $a_row['products_qt'] > 0 AND $isThere=="no" AND !isset($_SESSION['devisNumero'])) {
						print "</td>";
						print "<td valign='middle' align='right'>";
						express_buy($new_price);
					}
					print "</td>";
					print "</tr></table>";
					print "</td>";
				}
			}
			
			
			if($a_row['products_deee']>0 AND $a_row['products_forsale']=="yes" AND (isset($_SESSION['account']) OR $displayPriceInShop=="oui")) {
				$openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
				print "</tr><tr>";
				print "<td align='left' colspan='2' valign='bottom' align='left'>";
				print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$a_row['products_deee']." ".$symbolDevise."</i>";
				print "</td>";
			}
			print "</tr></table>";
			print "</td>";
			print "</tr></table>";
		print "</td>";

		
		if($addCartList=='oui') {
			$tdWidthArray = @getimagesize("im/cart_add.png");
      		if(!$tdWidthArray) $tdWidth=1; else $tdWidth=$tdWidthArray[0];
			if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $a_row['products_forsale']=="yes" AND !isset($_SESSION['devisNumero'])) {
				if($a_row['products_qt'] > 0) {
					if($isThere=="yes") {
						if($a_row['products_options'] == 'no') {
							print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
							print "<a href='add.php?amount=0&ref=".$a_row['products_ref']."&id=".$a_row['products_id']."&name=".$a_row['products_name_'.$_SESSION['lang']]."&productTax=".$a_row['products_tax']."&deee=".$a_row['products_deee']."'><img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."'>";
							print " </a>";
							print "</td>";
						}
						else {
							print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
							print "<div align='right'>";
							print "<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'>";
							print "<img src='im/cart_opt.png' border='0'>";
							print "</a>";
							print "</div>";
							print "</td>";
						}
					}
					else {
						if($a_row['products_options'] == 'no') {
							print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
							print "<div align='right'>";
							
							print "<input type='text' size='3' maxlength='3' name='amount' value='1'>";
							print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
							print "<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' title='".AJOUTER_AU_CADDIE."' alt='".AJOUTER_AU_CADDIE."'>";
							print "<input type='hidden' value='".$a_row['products_id']."' name='id'>";
							print "<input type='hidden' value='".$a_row['products_ref']."' name='ref'>";
							print "<input type='hidden' value='".$a_row['products_name_'.$_SESSION['lang']]."' name='name'>";
							print "<input type='hidden' value='".$a_row['products_tax']."' name='productTax'>";
							print "<input type='hidden' value='".$a_row['products_deee']."' name='deee'>";
							print "</div>";
							print "</td>";
						}
						else {
							print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
							print "<a href='beschrijving.php?id=".$a_row['products_id']."&path=".$a_row['categories_id']."&".$DescLink."'><img src='im/cart_opt.png' border='0' title='".VOIR_OPTIONS."' alt='".VOIR_OPTIONS."'></a>";
							print "</td>";
						}
					}
				}
				else {
					if($actRes=="non") {
						print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
						print "<img src='im/cart_out.png' title='".NOT_IN_STOCK."' alt='".NOT_IN_STOCK."'>";
						print "</td>";
					}
					else {
						print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'>";
						print "<a href='beschrijving.php?id=".$a_row['products_id']."&".$DescLink."'><img src='im/cart_out.png' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."'></a>";
						print "</td>";
					}
				}
			}
		if($a_row['products_forsale']=="no" AND !isset($_SESSION['devisNumero'])) print "<td rowspan='3' align='right' valign='middle' width='".$tdWidth."'><img src='im/cart_no.png' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></td>";
		}
      print "</tr>";
		print "</form>";
		print "</table>";
}
print "</td></tr></table>";

print "</td></tr></table>";

































		print "<table border='0' width='100%' cellspacing='0' cellpadding='2'>";
		print "<tr>";
        if($tototal > $nbre_ligne) {
        	print "<td colspan='2' valign='top'>";
            
            displayPageNum($nbre_ligne);
            print "</td>";
        }
        print "</tr><tr>";
    	print "<td valign='top'>";
        $article = ($tototal>1)? STRTOLOWER(ARTICLES) : STRTOLOWER(ARTICLE);
		print "<div align='right'><b>".$tototal."</b> ".$article." ".EN_VENTE."</div>";
		print "</td>";
		print "</tr>";
		print "</table>";
		
		if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $addCartList=='oui') {
			print "<table border='0' width='100%' cellspacing='0' cellpadding='2'>";
			print "<tr>";
			print "<td align='left' valign='top'>";
			print "<img src='im/cart_rem.png' align='absmiddle'> ".RETIRER_DU_CADDIE.".<br>";
			print "<img src='im/cart_opt.png' align='absmiddle'> ".VOIR_OPTIONS.".<br>";
			if($displayOutOfStock=="oui") {
				if($actRes=="non") {
					print "<img src='im/cart_out.png' align='absmiddle'> ".NOT_IN_STOCK.".<br>"; }
				else {
					print "<img src='im/cart_out.png' align='absmiddle'> ".EN_COMMANDE.".<br>";
				}
			}
			print "<img src='im/cart_no.png' align='absmiddle'> ".OUT_OF_STOCK.".";
			print "</td>";
			print "</tr>";
			print "</table>";
		}
}
?>
</td>
</tr>
</table>
</td>

         <?php 
		  // ---------------------------------------
		  // rechtse kolom 
		  // ---------------------------------------
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
