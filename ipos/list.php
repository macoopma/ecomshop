<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');
include('../includes/lijst_navigatie.php');
$nbre_ligne = 5;
$haut_im = 40;

include("../includes/lang/lang_".$_SESSION['lang'].".php");
if(isset($_GET['path'])) {
   $result2 = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
      FROM categories
      WHERE categories_id = '".$_GET['path']."'");
   $cat_name = mysql_fetch_array($result2);
}
if(!isset($_GET['page'])) $_GET['page']=0;
$asc = "ASC";
if(isset($_GET['sort']) AND $_GET['sort']=="id") {$_GET['sort']="products_id"; $order = "id"; $asc = "DESC";}
if(isset($_GET['sort']) AND $_GET['sort']=="Id") {$_GET['sort']="products_id"; $order = "Id"; $asc = "DESC";}
if(isset($_GET['sort']) AND $_GET['sort']=="Ref") {$_GET['sort']="products_ref"; $order = "Ref";}
if(isset($_GET['sort']) AND $_GET['sort']=="Article") {$_GET['sort']="products_name_".$_SESSION['lang']; $order = "Article";}
if(isset($_GET['sort']) AND $_GET['sort']=="Prix") {$_GET['sort']="ord"; $order = "Prix";}
if(isset($_GET['sort']) AND $_GET['sort']=="Entreprise") {$_GET['sort']="fournisseurs_id"; $order = "Entreprise";}
if(isset($_GET['sort']) AND $_GET['sort']=="Les_plus_populaires") {$_GET['sort']="products_viewed"; $asc = "DESC"; $order = "Les_plus_populaires";}
if(isset($_GET['sort']) AND $_GET['sort']=="specials_last_day") {$_GET['sort']="specials_last_day"; $asc = "ASC"; $order = "specials_last_day";}

if(!isset($_GET['sort']) OR $_GET['sort']=="") {
   $order = $defaultOrder;
   if($order == "Id") $_GET['sort']="products_id";
   if($order == "id") $_GET['sort']="products_id";
   if($order == "Ref") $_GET['sort']="products_ref";
   if($order == "Article") $_GET['sort']="products_name_".$_SESSION['lang'];
   if($order == "Prix") $_GET['sort']="ord";
   if($order == "fournisseurs_id") $_GET['sort']="Compagnie";
   if($order == "products_viewed") $_GET['sort']="Les_plus_populaires";
   $asc = "DESC";
}

if(!isset($_GET['target'])) {
    $BarreMenuHautTitre = $cat_name['categories_name_'.$_SESSION['lang']];
    }
if(isset($_GET['target']) and $_GET['target'] == "new") {
    $BarreMenuHautTitre = NOUVEAUTES;
    }
if(isset($_GET['target']) and $_GET['target'] == "promo") {
    $BarreMenuHautTitre = PROMOTIONS;
    }
if(isset($_GET['target']) and $_GET['target'] == "author") {
    $BarreMenuHautTitre = MENU_RAPIDE3;
    }
if(isset($_GET['target']) and $_GET['target'] == "favorite") {
    $BarreMenuHautTitre = COEUR;
    }
if(isset($_GET['tow']) and $_GET['tow'] == "flash") {
    $BarreMenuHautTitre = VENTES_FLASH;
    } 

$title = $BarreMenuHautTitre;
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}

    // function sort title
    function titleSort() {
            GLOBAL $order,$titre_order_1,$titre_order_2,$titre_order_3,$mode;
            $orderLang = $order;
            $uu = ${'titre_order_'.$_SESSION['lang']};
            $orderLang = explode("|",$uu);
            $sortTitle = $orderLang[$mode];
            if($order=="specials_last_day") {$sortTitle = DATE_FIN_PROMO;}
            return $sortTitle;
    }
    // function sort title default
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

//--------------------------
// Requete sous-categorie --
//--------------------------
if(!isset($_GET['target'])) {
   $select = "SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].",p.products_deee,p.products_id,p.products_download,p.products_exclusive,p.categories_id,p.categories_id_bis,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,s.specials_new_price,s.specials_last_day,s.specials_first_day,s.specials_visible,f.fournisseurs_company,
                               IF(s.specials_new_price < p.products_price
                               AND s.specials_last_day >= NOW()
                               AND s.specials_first_day <= NOW(),
                               s.specials_new_price, p.products_price) as ord
                        FROM products as p
                        LEFT JOIN specials as s
                        ON (p.products_id = s.products_id)
                        LEFT JOIN fournisseurs as f
                        ON (p.fournisseurs_id = f.fournisseurs_id)
                        WHERE 
                        p.products_visible = 'yes'
                        ".$addToQuery."
						AND p.categories_id = '".$_GET['path']."' 
						AND p.products_ref != 'GC100'
						OR (p.categories_id_bis LIKE '%|".$_GET['path']."|%'
						AND p.products_visible = 'yes')
						";

   $result=mysql_query($select);
   $tototal =  mysql_num_rows($result);
   if($tototal <= $nbre_ligne) $nPage = ""; else $nPage = "&page=".$_GET['page'];

   $select .=  " ORDER BY ".$_GET['sort']."
                 $asc
                 LIMIT ".$_GET['page'].",".$nbre_ligne;
   $result=mysql_query($select);

   $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['path']."&sort=".$order."&page=";
   $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['path'];
   $DescLink = "beschrijving.php?path=".$_GET['path']."&sort=".$order.$nPage;
   }
//------------------------
// Requete FAVORITE-------
//------------------------
if(isset($_GET['target']) and $_GET['target'] == "favorite") {
   if(isset($_GET['view'])) {
           $AddQuery = " AND p.categories_id = ".$_GET['view']."  ";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=favorite&sort=".$order."&view=".$_GET['view']."&page=";
           $DescLink = "beschrijving.php?path=".$_GET['view']."&view=".$_GET['view']."&sort=".$order."&target=favorite";
           $ClassLink = $_SERVER['PHP_SELF']."?view=".$_GET['view']."&target=favorite";
   }
   else {
           $AddQuery = "";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=favorite&sort=".$order."&page=";
           $DescLink = "beschrijving.php?target=favorite&sort=".$order;
           $ClassLink = $_SERVER['PHP_SELF']."?target=favorite";
  }

   $selectP = "SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_download, p.products_exclusive, p.products_id, p.categories_id, p.fournisseurs_id, p.fabricant_id, p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
   
   $result=mysql_query($select);
   $resultP=mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
   if($tototal > $nbre_ligne) $DescLink = $DescLink."&page=".$_GET['page'];

   $select .= " ORDER BY ".$_GET['sort']."
                $asc
                LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result=mysql_query($select);
   }
//----------------------
// Requete AUTHOR-------
//----------------------
if(isset($_GET['target']) and $_GET['target'] == "author") {

           $AddQuery = " AND p.fabricant_id = ".$_GET['authorid']." "; 
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=author&sort=".$order."&authorid=".$_GET['authorid']."&page=";
           $DescLink = "beschrijving.php?sort=".$order."&target=author";
           $ClassLink = $_SERVER['PHP_SELF']."?authorid=".$_GET['authorid']."&target=author";

   $selectP = "SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_download, p.products_exclusive, p.products_id, p.categories_id, p.fournisseurs_id, p.fabricant_id, p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
                        AND p.products_ref != 'GC100'
                        ".$addToQuery."
                        AND c.categories_visible='yes'
                        ";
                        
   $select = $selectP." ".$AddQuery;
   
   $result=mysql_query($select);
   $resultP=mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
   if($tototal > $nbre_ligne) $DescLink = $DescLink."&page=".$_GET['page'];

   $select .= " ORDER BY ".$_GET['sort']."
                $asc
                LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result=mysql_query($select);
   }
//----------------------
// Requete NEWS---------
//----------------------
if(isset($_GET['target']) and $_GET['target'] == "new") {
   if(isset($_GET['view'])) {
           $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=new&sort=".$order."&view=".$_GET['view']."&page=";
           $DescLink = "beschrijving.php?path=".$_GET['view']."&target=new&view=".$_GET['view']."&sort=".$order;
           $ClassLink = $_SERVER['PHP_SELF']."?target=new&view=".$_GET['view'];}
   else {
           $AddQuery = "";
           $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?target=new&sort=".$order."&page=";
           $DescLink = "beschrijving.php?target=new&sort=".$order;
           $ClassLink = $_SERVER['PHP_SELF']."?target=new";
   }
   $selectP = "SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].",p.products_deee,p.products_id, p.products_download, p.products_exclusive, p.categories_id,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt,c.categories_name_".$_SESSION['lang'].", s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, now(),
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
   
   $result=mysql_query($select);
   $resultP=mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
   if($tototal > $nbre_ligne) $DescLink = $DescLink."&page=".$_GET['page'];

   $select .= " ORDER BY ".$_GET['sort']."
                $asc
                LIMIT ".$_GET['page'].",".$nbre_ligne;

   $result=mysql_query($select);
   }
//----------------------
// Requete PROMO--------
//----------------------

if(isset($_GET['target']) and $_GET['target'] == "promo") {
   if(isset($_GET['view'])) {
                if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                     $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                     $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (p.categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view']."&tow=flash&page=";
                     $DescLink = "beschrijving.php?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view']."&tow=flash";
                     $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&view=".$_GET['view']."&tow=flash";
                    
                } else {
                     $addQueryFlash = "";
                     $AddQuery = " AND p.categories_id = ".$_GET['view']." OR (p.categories_id_bis LIKE '%|".$_GET['view']."|%' AND p.products_visible = 'yes')";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view']."&page=";
                     $DescLink = "beschrijving.php?path=".$_GET['view']."&target=promo&sort=".$order."&view=".$_GET['view'];
                     $ClassLink = $_SERVER['PHP_SELF']."?path=".$_GET['view']."&target=promo&view=".$_GET['view'];
                }
    }
    else {
                if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
                     $addQueryFlash = " AND (TO_DAYS(s.specials_last_day)-TO_DAYS(NOW())) <= '".$seuilPromo."'";
                     $AddQuery = "";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?sort=".$order."&target=promo&tow=flash&page=";
                     $DescLink = "beschrijving.php?sort=".$order."&target=promo&tow=flash";
                     $ClassLink = $_SERVER['PHP_SELF']."?target=promo&tow=flash";
                } 
                else {
                     $addQueryFlash = "";
                     $AddQuery = "";
                     $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?sort=".$order."&target=promo&page=";
                     $DescLink = "beschrijving.php?sort=".$order."&target=promo";
                     $ClassLink = $_SERVER['PHP_SELF']."?target=promo";
                }
    }
    
    $selectP = "SELECT p.products_forsale, p.products_name_".$_SESSION['lang'].",p.products_deee,p.products_id,p.products_download,p.products_exclusive,p.categories_id,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_options,p.products_price,p.products_ref,p.products_im,p.products_image,p.products_visible,p.products_tax,p.products_date_added,p.products_qt, c.categories_id, f.fournisseurs_company, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, c.categories_name_".$_SESSION['lang'].",
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
                        AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                        AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)";
                        
   $select = $selectP." ".$AddQuery;
   $result=mysql_query($select);
   $resultP=mysql_query($selectP);
   $tototal =  mysql_num_rows($result);
   if($tototal > $nbre_ligne) $DescLink = $DescLink."&page=".$_GET['page'];
      
   $select .= " ORDER BY ".$_GET['sort']."
                $asc
                LIMIT ".$_GET['page'].",".$nbre_ligne;
   $result=mysql_query($select);
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link rel="stylesheet" href="<?php echo $_SESSION['css'];?>" type="text/css">
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php include('top.php');?>
        <table border="0" width="450" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td valign="top" class="TABLEMenuPathTopPage">
                    <table width="100%" border="0" cellspacing="0" cellpadding="5" class="">
                        <tr>
                            <td align="left">
<?php
                if(isset($_GET['target']) AND !isset($_GET['tow'])) $targetF = $_GET['target']; 
                if(isset($_GET['target']) AND isset($_GET['tow'])) $targetF = $_GET['tow'];
                if(!isset($_GET['target'])) $targetF = "";
                if(isset($_GET['action'])) $actionF = $_GET['action']; else $actionF = "e";
                if(!isset($_GET['path'])) $pathF = ""; else $pathF = $_GET['path'];
                getPath20($pathF,"bas",$catId=0,$_SESSION['tree2'],$actionF,$targetF,$_SESSION['lang']);
?>
                            </td>
                        </tr>
                    </table>
<?php
   print "<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'><tr><td>";
// Afficher page haut de page
if($tototal > $nbre_ligne) {
   print "<table border='0' width='100%' cellspacing='0' cellpadding='2' align='center'>";
   print "<tr>";
   print "<td  valign='bottom'>";
           displayPageNum($nbre_ligne);
   print "</td></tr>";
   print "</table>";
} // fin page
else {
    print "<img src='im/zzz.gif' width='1' height='4'>";
}

///////////////
// START TABLE/
///////////////
if($tototal == 0) print "<br>";

// Si champs vide
      if($tototal == 0) { // pas article dans le tableau
          print "</td></tr></table>";
          if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
            print "<div align='center' class='fontrouge'><b>".PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE_FLASH."</b></div>";
          }
          else {
            print "<p align='center' class='fontrouge'><b>".PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE."</b></p>";
          }
         }
         else { // presenter article dans le tableau
         
// current url
if($_SERVER['REQUEST_URI']) {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER['REQUEST_URI'];
}
else {
    if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
}

   print "<table width='100%' border='0' cellspacing='0' cellpadding='2' align='center' class=''>";
   print "<tr>";
if($tototal > 2 OR (isset($_GET['target']) and ($_GET['target'] == "new" OR $_GET['target'] == "promo" OR $_GET['target'] == "favorite"))) {   
   print "<td colspan='4' align='left' valign='bottom' style='padding: 5px; BORDER-BOTTOM: 1px solid #cccccc;'>";
   print "<table width='100%' height='1' border='0' cellspacing='0' cellpadding='0' align='center'>";
   print "<tr>";
// Boite de classement
if($tototal > 2) {
   if($_GET['page'] == 0) {
        // Afficher classement
        $titreOrderLang = explode("|",$titreOrder);
        $titreOrderLang1 = explode("|",$titre_order_1);
        $mode = array_search($order,$titreOrderLang1);
        $iMax = count($titreOrderLang)-1;
        $a2 = 1;
        //if(isset($order) AND $order=="Id") {$selSort="selected";} else {$selSort="";}
        print "<form action= ''>";
        print "<td>";
        print "<select name='path' onChange='sendIt(this.options[selectedIndex].value)' >";
        print "<option value=''>* ".CLASSER_PAR." *</option>";
        print "<option value=''>------------------</option>";
                    
                for($i=0; $i<=$iMax; $i++) {
                    if($titreOrderLang[$i] !== "") {
                      print "<option value='".$ClassLink."&sort=".$titreOrderLang1[$i]."' >".$titreOrderLang[$i]."</option>";
                    }
                }
        print "</select>";
        
    // display sort title
    if(isset($order) AND $order !== $defaultOrder) {
        print "&nbsp;".titleSort();
    }
    else {
        print "&nbsp;".titleSortDefault();
    }
    // End display sort title
    
    print "</td></form>";
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
    } // End if page == 0
//print "</td></form>";
}
else {
print "<td>&nbsp;</td>";
}
// Classement par categories
if(isset($_GET['target']) and ($_GET['target'] == "new" OR $_GET['target'] == "promo" OR $_GET['target'] == "favorite")) {
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
                                if($exp[1] == $_GET['view']) {$selSort2="selected";} else {$selSort2="";}
                                if(isset($_GET['tow']) AND $_GET['tow']=="flash") { $addLinkFlash="tow=flash&";} else {$addLinkFlash="";}
                                print "<option $selSort2 value='list.php?sort=".$order."&".$addLinkFlash."target=".$_GET['target']."&path=".$exp[1]."&view=".$exp[1]."'>".$exp[0]."</option>";
                                $ti[$exp[1]] = array($exp[0]);
                          }
                  print "</select>";
                  
                  if(isset($_GET['view'])) $titleF = $ti[$_GET['view']][0]; else $titleF = TOUTES;
                  
                  //print "<img src='../im/fleche_right.gif'>&nbsp;<b>".$titleF."</b>";
                  print "</td>";
                  print "</tr>";
                  print "</form>";
                  print "</table>";
                  print "</td></tr></table>";
                  }
                  else {
                  print "</tr></table>";
                  }
        print "</td>";
}
print "</tr><tr>";
   while($a_row = mysql_fetch_array($result)) {
   
if(isset($d) and $d==2) $d = 1; else $d = 2;
// Si champs pas vide

		// Verfier si l'article est en promotion
		$new_price = $a_row['specials_new_price'];
		$old_price = $a_row['products_price'];
		$promoIs="";
		if(empty($new_price)) {
			$price = "<b>".$old_price." ".$symbolDevise."</b>";
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
					$price = "<b><s>".$old_price."</s> ".$symbolDevise."</b> | <b><span class='fontrouge'>".$new_price." ".$symbolDevise."</span></b>";
				}
				else {
					$price = "<b>".$old_price." ".$symbolDevise."</b>";
				}
			}
			else {
				$price = "<b>".$old_price." ".$symbolDevise."</b>";
			}
		}
         // Verifier si cet article est dans le panier
        if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$a_row['products_ref']."+")) {
           $caddie_yes = "<img src='../im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
           $isThere = "yes";
        }
        else {
           $caddie_yes = "&nbsp;";
           $isThere = "no";
        }
      print "<tr>";
      print "<td width='1' rowspan='3' valign='middle' class='TDTableListLine".$d."'>";
      // Image article dans le panier
      print "<div align='left'>".$caddie_yes."</div>";
      print "</td>";
      // Afficher Image
              if($a_row['products_im']=="yes" AND !empty($a_row['products_image']) AND $a_row['products_image']!=="im/no_image_small.gif") {
                  if(substr($a_row['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
                  $images_widthList = $haut_im+10;
                    $yoZ1 = @getimagesize($dirr.$a_row['products_image']);
                    if(!$yoZ1) $a_row['products_image']="im/zzz_gris.gif";
                  $image_resizeList = resizeImageMini($dirr.$a_row['products_image'],$haut_im,$images_widthList);
                  
                  print "<td rowspan='3' width='".$images_widthList."' valign='middle' align='left' class='TDTableListLine".$d."'>";
                  print "<a href='".$DescLink."&path=".$a_row['categories_id']."&id=".$a_row['products_id']."'>";
                       // Check server GD library
                       if($gdOpen == "non") {
                        	print "<img src='".$dirr.$a_row['products_image']."' width='".$image_resizeList[0]."' height='".$image_resizeList[1]."' alt='".$a_row['products_name_'.$_SESSION['lang']]."' border='0'>";
                       }
                       else {
                        	$infoImage = infoImageFunction($dirr.$a_row['products_image'],$images_widthList,$haut_im);
                            print "<img src='../mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$a_row['products_name_'.$_SESSION['lang']]."'>";                  
                       }
                  print "</a>";
                  print "</td>";
               }
               else {
               	  $images_widthList = $haut_im+10;
               	  $image_resizeList = resizeImage("../im/lang".$_SESSION['lang']."/no_image.png",$haut_im,$images_widthList);
                  print "<td width='1' rowspan='3' valign='middle' align='left' class='TDTableListLine".$d."'>";
                  print "<a href='".$DescLink."&path=".$a_row['categories_id']."&id=".$a_row['products_id']."'>";
                  print "<img src='../im/lang".$_SESSION['lang']."/no_image.png' border='0' width='".$image_resizeList[0]."' height='".$image_resizeList[1]."'>";
                  print "</a>";
                  print "</td>";
               }
        // Afficher dispo
        if($a_row['products_qt']>0) {
            $dispo = $openLeg."&nbsp;<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
        }
        else {
            if($actRes=="oui") {$dispo = $openLeg."&nbsp;<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>";} else {$dispo = $openLeg."<img src='../im/stockno.gif' border='0' title='".NOT_IN_STOCK."' align='absmiddle'></a>";}
        }
        // product download
        if($a_row['products_download'] == "yes") {
            $prodDown = $openLeg."&nbsp;<img src='../im/download.gif' border='0' title='".ARTICLE_TELE."' alt='".ARTICLE_TELE."' align='absmiddle'></a>";
        } else {
            $prodDown = "";
        }
        // Afficher coup de coeur!
        if($a_row['products_exclusive']=="yes") {
            if(isset($_GET['target']) and $_GET['target'] == "favorite") {
                $getHart = "&nbsp;<img src='../im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."' align='absmiddle'>";
            } else {
                $getHart = "&nbsp;<a href='list.php?target=favorite'><img src='../im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."' align='absmiddle'></a>";
            }
        }
        else {
        	$getHart="";
        }
		// Prix dégressif
		if(in_array($a_row['products_id'], $_SESSION['discountQt']) AND $a_row['products_forsale']=='yes') {
			$prodDegressif = $openLeg."<img src='../im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
		} else {
			$prodDegressif = "";
		}
        // Afficher horloge ventes flash
        if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo AND $a_row['products_forsale']=="yes") {
            if(isset($_GET['tow']) and $_GET['tow'] == "flash") {
                $vFlash = "&nbsp;<img src='../im/time_anim.gif' border='0' title='".VENTES_FLASH."' alt='".VENTES_FLASH."' align='absmiddle'>";
            }
            else {
                $vFlash = "&nbsp;<a href='list.php?target=promo&tow=flash'><img src='../im/time_anim.gif' border='0' title='".VENTES_FLASH."' alt='".VENTES_FLASH."' align='absmiddle'></a>";
            }
        }
        else {
                $vFlash = "";
        }
        // Out of stock
        if($a_row['products_forsale']=="no") {
            $prodOut = $openLeg."&nbsp;<img src='../im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
        } else {
            $prodOut = "";
        }
      print "<td class='TDTableListLine".$d."' valign='middle'>";
      print "<a href='".$DescLink."&path=".$a_row['categories_id']."&id=".$a_row['products_id']."'><b>".$a_row['products_name_'.$_SESSION['lang']]."</b></a>";
      print "</td>";
      if($prodOut=="") {
    		print "<td class='TDTableListLine".$d."' valign='middle' align='right'>";
    		print $prodDegressif;
         	print $prodDown;
         	print $getHart;
         	print $vFlash;
         	print $dispo."&nbsp;&nbsp;";
         	print "</td>";
      }
      else {
      	print "<td class='TDTableListLine".$d."' valign='middle' align='right'>";
         print "";
         print "</td>";
      }
      print "</td>";
      // bouton
      // si l'article a des options
if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $a_row['products_forsale']=="yes") {
if($a_row['products_qt'] > 0) {
    if($isThere=="yes") {
       if($a_row['products_options'] == 'no') {
          print "<td rowspan='3' class='TDTableListLine".$d."' align='right'>
    	  		 <a href='../add.php?amount=0&ref=".$a_row['products_ref']."&id=".$a_row['products_id']."&name=".$a_row['products_name_'.$_SESSION['lang']]."&productTax=".$a_row['products_tax']."&deee=".$a_row['products_deee']."'><img src='../im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."'>
    			 </a></td>";
       }
       else {
          print "<td rowspan='3' class='TDTableListLine".$d."'>
    	  		 <div align='right'>
    	  		 <a href='".$DescLink."&id=".$a_row['products_id']."'>
    			  	<img src='../im/cart_opt.png' border='0'>
    		     </a>
    			 </div>
    			 </td>";
       }
    }
    else {
          if($a_row['products_options'] == 'no') {
             print "<td rowspan='3' class='TDTableListLine".$d."'><div align='right'>
                    <form action='../add.php' method='get'>";
                    print "<input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='../im/zzz.gif' width='1' height='3'><br>
                    <input style='BACKGROUND: none; border:0px' type='image' src='../im/cart_add.png' alt='".AJOUTER_AU_CADDIE."'>
                    <input type='hidden' value='".$a_row['products_id']."' name='id'>
                    <input type='hidden' value='".$a_row['products_ref']."' name='ref'>
                    <input type='hidden' value='".$a_row['products_name_'.$_SESSION['lang']]."' name='name'>
                    <input type='hidden' value='".$a_row['products_tax']."' name='productTax'>
                    <input type='hidden' value='".$a_row['products_deee']."' name='deee'>
                    </form>
                    </div>
                    </td>";
          }
          else {
             print "<td rowspan='3' class='TDTableListLine".$d."' align='right'><a href='".$DescLink."&path=".$a_row['categories_id']."&id=".$a_row['products_id']."'><img src='../im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."'></a></td>";
          }
    }
}
else {
    if($actRes=="non") {
        print "<td align='right' rowspan='3' class='TDTableListLine".$d."'><img src='../im/cart_out.png' alt='".NOT_IN_STOCK."'></td>";
    }
    else {
        print "<td align='right' rowspan='3' class='TDTableListLine".$d."'><a href='".$DescLink."&id=".$a_row['products_id']."'><img src='../im/cart_out.png' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a></td>";
    }
}
}
if($a_row['products_forsale']=="no") print "<td align='right' rowspan='3' class='TDTableListLine".$d."'><img src='../im/cart_no.png' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></td>";
      print "</tr>";
      // Description
      $ProdNameProdList = $a_row['products_desc_'.$_SESSION['lang']];
      $ProdNameProdList = strip_tags($ProdNameProdList);
      print "<tr>";
      $maxCarDesc = 100;
      print "<td colspan='2' class='TDTableListLine".$d."' align='left'>".adjust_text($ProdNameProdList,$maxCarDesc,"..<a href='".$DescLink."&id=".$a_row['products_id']."'><img src='../im/next.gif' border='0'></a>")."</td>";
      print "</tr>";
      // Ref

      // Prix
      print "<tr>";
      print "<td colspan='2' class='TDTableListLine".$d."'>";
      
      
      print "<table border='0' align='left' cellspacing='0' cellpadding='0'><tr>";
		if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $a_row['products_forsale']=="yes") {
      		print "<td>".$price."</td>";
		}
      // afficher ventes flash 
      if(isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo AND $a_row['products_forsale']=="yes") {
            print "<td>, <img src='../im/star.gif'></td>";
            if(isset($itMiss) AND $itMiss>0) {
                print "<td>&nbsp;-<b>".$itMiss."</b> ";
                ($itMiss>1)? print strtolower(JOURS)."s" : print strtolower(JOURS);
                print "&nbsp;</td>";
            }
            else {
                print "<td>&nbsp;<b>".AUX_ABONNES_DE_LA_NEWSLETTER." !</b>&nbsp;</td>";
            }
            print "<td align='left'><img src='../im/star.gif'></td>";
      }

// Afficher DEEE
if($a_row['products_deee']>0 AND $a_row['products_forsale']=="yes" AND (isset($_SESSION['account']) OR $displayPriceInShop=="oui")) {
$openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('../includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
    print "</tr><tr>";
    print "<td colspan='2' valign='bottom' align='left'>";
    print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$a_row['products_deee']." ".$symbolDevise."</i>";
    print "</td>";
}
else {
    print "<td><img src='im/zzz.gif' width='1' height='1'></td>";
}
      print "</tr></table>";
      
      
      print "</td>";
      print "</tr>";
}
print "</table>";

// END TABLE



////////////////////////
//Afficher navigation //
////////////////////////

 print "<table border='0' width = '100%' cellspacing='0' cellpadding='2' align='center'>";
         print "<tr>";
        if($tototal > $nbre_ligne) {
                 print "<td colspan='2' valign='top' style='padding: 2px; BORDER-TOP: 1px solid #cccccc;'>";
                  // include navigation Bas de page
                     displayPageNum($nbre_ligne);
                 print "</td>";
        }
         print "</tr><tr>";
        
        print "<td valign='top'>";
            $PageNum = ceil($_GET['page']/$nbre_ligne)+1;
            ($tototal>1)? $article = STRTOLOWER(ARTICLES) : $article = maj(ARTICLE);
            if(empty($nbr)) $nbr = 1;
         print "<div align='right'><b>".$tototal."</b> ".$article." ".EN_VENTE." - ".PAGE." <b>".$PageNum."</b> ".DE." <b>".$nbr."</b></div>";
         print "</td>";
         print "</tr>";
         print "</table>";
/*
          if(isset($_SESSION['account']) OR $activeEcom=="oui") {
          print "<table width='100%' align='center' border='0' cellspacing='0' cellpadding='3'>";
          print "<tr>";
          print "<td width='50%' valign='top' align='left'>";
          print "<img src='../im/cart.gif'> : ".ARTICLE_PRESENT_DANS_LE_CADDIE."<br>";
          print "<img src='../im/cart_add.png'> : ".AJOUTER_AU_CADDIE."<br>";
          print "<img src='../im/cart_rem.png'> : ".RETIRER_DU_CADDIE."<br>";
          print "</td><td valign='top' align='left'>";
          
          print "<img src='../im/cart_opt.png'> : ".VOIR_OPTIONS."<br>";
          if($displayOutOfStock=="oui") {
              if($actRes=="non") {
                  print "<img src='../im/cart_out.png'> : ".NOT_IN_STOCK; }
              else {
                  print "<img src='../im/cart_out.png'> : ".EN_COMMANDE;
              }
          }
          print "</td>";
          print "</tr>";
          print "</table>";
          }
*/
print "</td></tr></table>";
}
                    ?>
                </td>
            </tr>
        </table>
        </td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
    </body>
</html>
