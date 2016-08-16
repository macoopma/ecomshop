<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = RECHERCHER;
$addQuery55 = "";
if($displayOutOfStock=="non") {$addToQueryStock = " AND p.products_qt>'0'";} else {$addToQueryStock="";}

if(isset($_GET['search_query']) AND $_GET['search_query']!=='') {
    $search_queryModified = str_replace("'","’",$_GET['search_query']);
    $search_queryModified = str_replace("\\","",$search_queryModified);
    $_GET['search_query'] = str_replace("\\","",$_GET['search_query']);
}

$openUseSearch = "<a href='javascript:void(0);' onClick=\"window.open('gebruik_zoeken.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=215,width=490,toolbar=no,scrollbars=no,resizable=yes');\">";
$openUseSearch.= "<b><span class='darkBackground'>&nbsp;?&nbsp;</span></b>";
$openUseSearch.= "</a>";

 
function highlight_words($mot) {
    GLOBAL $_GET,$search_queryModified;
    if(preg_match("/!/", $search_queryModified)) {
        $splitMot = explode("!",strtolower($search_queryModified));
        if(isset($splitMot2)) unset($splitMot2);
        foreach($splitMot as $element) {
            if(strlen($element)>1) {
                $splitMot2[] = "<span style='BACKGROUND-COLOR:#FFCC00; COLOR:#000000'>".$element."</span>";
            }
            else {
                $splitMot2[] = $element;
            }
        }
        $resultat = str_replace($splitMot,$splitMot2, strtolower($mot));
    }
    else {
         if($search_queryModified !== ' ') {
            $resultat = str_replace(strtolower($search_queryModified),"<span style='BACKGROUND-COLOR:#FFCC00; COLOR:#000000'>".$search_queryModified."</span>", strtolower($mot));
         }
         else {
            $resultat = str_replace(strtolower($search_queryModified), $search_queryModified, strtolower($mot));
         }
    }
    return $resultat;
}

if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
    $addToLink = "&advCat=".$_GET['advCat'];
    $findNameQuery = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['advCat']."'");
    $findName = mysql_fetch_array($findNameQuery);
    $searchReq = $findName['categories_name_'.$_SESSION['lang'].'']." | ";
}
else {
    if(isset($_GET['advCat']) AND $_GET['advCat']=="all") $addToLink = "&advCat=all"; else $addToLink = "";
    if(isset($_GET['advCat']) AND $_GET['advCat']=="all") $searchReq = TOUTES_CAT." |"; else $searchReq = "";
}

if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
    $addToLink .= "&advComp=".$_GET['advComp'];
    $findCompQuery = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseurs_id = '".$_GET['advComp']."'");
    $findComp = mysql_fetch_array($findCompQuery);
    $searchReq .= $findComp['fournisseurs_company'];
}
else {
    if(isset($_GET['advComp']) AND $_GET['advComp']=="all") $addToLink .= "&advComp=all"; else $addToLink .= "";
    if(isset($_GET['advComp']) AND $_GET['advComp']=="all") $searchReq .= " Tous vendeurs"; else $searchReq .= "";
}

$asc = "ASC";
if(isset($_GET['sort'])) {
    if($_GET['sort']=="id") {$sort="products_id"; $_GET['sort']="products_id"; $order = "id"; $asc = "DESC";}
    if($_GET['sort']=="Id") {$sort="products_id"; $asc = "ASC"; $order = "Id"; $asc = "DESC";}
    if($_GET['sort']=="Ref") {$sort="products_ref"; $order = "Ref";}
    if($_GET['sort']=="Article" or $_GET['sort'] == "Artikel") {$sort="products_name_".$_SESSION['lang'].""; $order = "Artikel";}
    if($_GET['sort']=="Prix") {$sort="ord"; $order = "Prijs";}
    if($_GET['sort']=="Compagnie") {$sort="fournisseurs_company"; $order = "Compagnie";}
    if($_GET['sort']=="Les_plus_populaires") {$sort="products_viewed";  $order = "Meest gezien"; $asc = "DESC";}
}
if(!isset($_GET['sort']) OR $_GET['sort']=="") {
   $order = $defaultOrder;
   if($order == "Id") $_GET['sort'] = $sort = "products_id";
   if($order == "id") $_GET['sort'] = $sort = "products_id";
   if($order == "Ref") $_GET['sort'] = $sort = "products_ref";
   if($order == "Artikel" or $order == "Artikel") $_GET['sort'] = $sort = "products_name_".$_SESSION['lang'];
   if($order == "Prix") $_GET['sort'] = $sort = "ord";
   if($order == "fournisseurs_company") $_GET['sort'] = $sort = "Compagnie";
   if($order == "Meest gezien") $_GET['sort'] = $sort = "products_viewed";
   $asc = "DESC";
}

 
function display_search() {
    GLOBAL $_GET, $_SESSION, $url_id10, $slash, $openUseSearch;
    include('configuratie/configuratie.php');
    print '<table width="80%" border="0" cellspacing="0" cellpadding="5" align="center"><tr>';
    print '<td align="center" valign="top">';

          print "<table width='300' border='0' align='center' cellspacing='0' class='TABLE1' cellpadding='5'>";
          print "<tr>";
          print "<form action='includes/redirectzoeken.php' method='post'>";
          print "<td align='left' valign='top' colspan='2'>";
              if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on") {
                  include('includes/zoeken_uitgebreid.php');
                  print "<img src='im/fleche_right.gif'><img src='im/zzz.gif' width='4' height='1'>";
              }
          print "<input type='text' name='search_query' maxlength='100' size='44'>&nbsp;<input style='background:none' type='image' src='im/search.gif' alt='".RECHERCHER."' align='absmiddle'>";
          print "</td>";
          print "</tr><tr>";
          
          print "<td align='left' valign='bottom' style='background-color:#none'>".$openUseSearch."</td>";
          if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "off") {
             print "<td align='right' valign='bottom'><a href='".$url_id10.$slash."AdSearch=on'><span class='tiny'>".RECHERCHE_AVANCEE."</span></a></td>";
          }
          if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "on") {
             print "<td align='right' valign='bottom'><a href='".$url_id10.$slash."AdSearch=off'><img src='includes/menu/minus.gif' border='0'></a></td>";
          }
          if(!isset($_SESSION['AdSearch'])) {
             print "<td align='right' valign='bottom'><a href='".$url_id10.$slash."AdSearch=on'><span class='tiny'>".RECHERCHE_AVANCEE."</span></a></td>";
          }          
          print "</form>";
          print "</tr>";
          print "</table>";
    
    print '</td>';
    print '</tr></table>';
}
?>
<html>

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
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a>  | <?php print strtoupper(RECHERCHER);?> |</b>
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


      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
                          <?php
                          if(empty($search_queryModified)) {
                                $pour = $searchReq;
                          }
                          else {
                                if(empty($searchReq)) {
                                    $pour = $search_queryModified;
                                }
                                else {
                                    $pour = $searchReq." | ".$search_queryModified;
                                }
                          }
                          print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".STRTOUPPER(RECHERCHER)." | ".str_replace("!"," ",$pour);
                          ?>
                      </td>
                    </tr>
                  </table>

<?php
}
       
      if($addCommuniqueHome=="oui") {include('includes/communicatie.php');}
      print "<br>";

if((isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on" AND !isset($search_queryModified) AND !isset($_GET['advCat']) AND !isset($_GET['advComp']))
    OR
  (isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="off" AND empty($search_queryModified))) {
        
        if(isset($_GET['action']) AND $_GET['action']=="search") {
          display_search();
          print "<p align='left'>".AIDE."</p>";
        }
        else {
          print "<table border='0' width='300' align='center' cellspacing='0' cellpadding='3' align='center' class='TABLE1'><tr><td>
                  <div align='center'>".RECHERCHE_NON_DEFINIE."</div>";
          print "</td></tr></table>";
        }
    }
    else {
 
if(!isset($search_queryModified)) $search_queryModified = " ";

       if($_GET['sep']=="is") {
                  $search_queryModified = str_replace("!"," ",$search_queryModified);
                  print $search_queryModified;
                   
                     $addQuery55 .= " AND (
                                  p.products_desc_".$_SESSION['lang']." like '%".$search_queryModified."%'
                                  OR p.products_ref like '%".$search_queryModified."%%'
                                  OR p.products_name_".$_SESSION['lang']." like '%".$search_queryModified."%%'
                                  OR f.fournisseurs_company like '%".$search_queryModified."%%'
                                  OR p.products_ean like '%".$search_queryModified."%%'
                                  OR p.products_garantie_".$_SESSION['lang']." like '%".$search_queryModified."%%'
                                  OR p.products_note_".$_SESSION['lang']." like '%".$search_queryModified."%%'
                                  ";
                     $addQuery55 .=")";
       }
       else {
                   
                  if($_GET['sep']=="and") {
                  $ChercherSeparateur = explode("!",$search_queryModified);
                  $ChercherSeparateurNb = count($ChercherSeparateur)-1;
                        for($i=0; $i<=$ChercherSeparateurNb; $i++) {
                          $addQuery55 .=" AND (
                                      p.products_desc_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_ref like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_name_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR f.fournisseurs_company like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_ean like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_garantie_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      OR p.products_note_".$_SESSION['lang']." like '%".$ChercherSeparateur[$i]."%'
                                      ";
                          $addQuery55 .=")";
                        }
                  }
                   
                  if($_GET['sep']=="or") {
                      $ChercherSeparateur = explode("!",$search_queryModified);
                      
                       
                      $products_desc="";
                      $products_ref="";
                      $products_name="";
                      $fournisseurs_company="";
                      $products_ean="";
                      $products_garantie="";
                      $products_note="";
                      
                       
                      foreach($ChercherSeparateur AS $word) {
                          $products_desc.= "p.products_desc_".$_SESSION['lang']." like '%".$word."%' OR ";
                          $products_ref.= "p.products_ref like '%".$word."%' OR ";
                          $products_name.= "p.products_name_".$_SESSION['lang']." like '%".$word."%' OR ";
                          $fournisseurs_company.= "f.fournisseurs_company like '%".$word."%' OR ";
                          $products_ean.= "p.products_ean like '%".$word."%' OR ";
                          $products_garantie.= "p.products_garantie_".$_SESSION['lang']." like '%".$word."%' OR ";
                          $products_note.= "p.products_note_".$_SESSION['lang']." like '%".$word."%' OR ";
                      }
                      
                       
                      $addQuery55.= " AND ((".substr($products_desc, 0, -3).")";
                      $addQuery55.= " OR (".substr($products_ref, 0, -3).")";
                      $addQuery55.= " OR (".substr($products_name, 0, -3).")";
                      $addQuery55.= " OR (".substr($fournisseurs_company, 0, -3).")";
                      $addQuery55.= " OR (".substr($products_ean, 0, -3).")";
                      $addQuery55.= " OR (".substr($products_garantie, 0, -3).")";
                      $addQuery55.= " OR (".substr($products_note, 0, -3).")";
                      $addQuery55.= ")";
                  }
       }
       
        if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
            $addQuery55 .= " AND p.categories_id = '".$_GET['advCat']."'";
        }
        if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
            $addQuery55 .= " AND p.fabricant_id = '".$_GET['advComp']."'";
        }
        
        if(isset($_GET['advCat'])) {

            $addQuery55p = " OR p.categories_id_bis LIKE '%|".$_GET['advCat']."|%' AND p.products_visible = 'yes')";
            if(isset($search_queryModified) AND !empty($search_queryModified)) {$addQuery55p = "";}
            $addQuery55 .= $addQuery55p;
        }
       
$queryVar55 = "SELECT p.products_forsale, p.products_ean, p.products_id,p.products_image, p.products_qt, p.categories_id, p.fournisseurs_id, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", c.categories_name_".$_SESSION['lang'].", p.products_price, f.fournisseurs_company, c.categories_id, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible,
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
                     AND c.categories_visible = 'yes'
                     AND p.products_ref!= 'GC100'
                     ".$addQuery55;

 
$queryVar55 .= $addToQueryStock;
$query55 = mysql_query($queryVar55);
$tototal= mysql_num_rows($query55);

$nbre_ligne = $moteurLigneNum;
if(!isset($_GET['page']) OR $_GET['page']=="") {$_GET['page']=0;}

$queryVar55 .= " ORDER BY ".$sort." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;
$query55 = mysql_query($queryVar55);

  if($tototal == 0) {
     print "<table border='0' width='300' align='center' cellspacing='0' cellpadding='8' class='TABLE1'><tr><td>
            <div align='center'>".AUCUN_RESULTAT."</div>
            </td></tr></table>";
  }
  else {
 
   if($_GET['page'] == 0 and $tototal>1) {

       print "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLESortByCentre'><tr><td>";
        $titreOrderLang = explode("|",$titreOrder);
        $titreOrderLang1 = explode("|",$titre_order_1);
        $iMax = count($titreOrderLang)-1;
        print "<select name='path' onChange='sendIt(this.options[selectedIndex].value)' >";
        print "<option>* ".CLASSER_PAR." *</option>";
        print "<option>------------------</option>";
                for($i=0; $i<=$iMax; $i++) {
                    $a2=$a2+1;
                    if($titreOrderLang[$i] !== "") {
                    print "<option value='".$_SERVER['PHP_SELF']."?order=".$order."&sort=".$titreOrderLang1[$i]."&sep=".$_GET['sep']."&search_query=".$_GET['search_query']."".$addToLink."'>".$a2." - ".$titreOrderLang[$i]."</option>";
                    }
                }
        print "</select>";

  
         if(isset($order)) {
               print "&nbsp;<img src='im/fleche_right.gif'>&nbsp;".$order."";
         }
          else {
               print "&nbsp;<img src='im/fleche_right.gif'>&nbsp;Id";
         }
         print "</td></tr></table>";
    }
    else {
          if($tototal > 1) {

          if(isset($order)) {
             print "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='8' class='TABLESortByCentre'><tr><td>";
             print "<img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR." <b>".$order."</b>";
             print "</td></tr></table>";
           }
          else {
          	 print "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='8' class='TABLESortByCentre'><tr><td>";
             print "<img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR." <b>Id</b>";
             print "</td></tr></table>";
          }
          }
          else {
             print "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'><tr><td>";
             print "";
             print "</td></tr></table>";
          }

    } 

 

      print "<table border='0' width='95%' align='center' cellspacing='0' cellpadding='2' align='center'><tr>";
     print "<td>";
     print RESULTAT.": <span class='fontrouge'><b>".$tototal."</b></span>";
 
     if($tototal > $nbre_ligne) {
        print "<br>";
        $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?sort=".$order."&sep=".$_GET['sep']."&search_query=".$_GET['search_query']."".$addToLink."&page=";
        include('includes/lijst_navigatie.php');
        displayPageNum($nbre_ligne);
     }
        print "</td>";
  print "</tr></table>";

 
  print "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='3' class='TABLESortByCentre'>";
  $n=$_GET['page']+1;
  $descLink = "";

while($row= mysql_fetch_array($query55)) {

         if(isset($d) and $d==2) $d = 1; else $d = 2;
         
  
         $prodDescClean = $row['products_desc_'.$_SESSION['lang']];
         $prodDescClean = strip_tags($prodDescClean);

   
         if($row['products_forsale']=="yes") {
           if($row['products_price'] == $row['ord']) {
                $prix1=""; 
                $prix = "- ".PRIX." : <b>".$row['products_price']." ".$symbolDevise."</b>";
                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                    $prix2 = "- ".VOTRE_PRIX." : <b><span class='fontrouge'>".newPrice($row['products_price'],$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
                }
           }
           else {
           		if($row['specials_visible']=="yes") {
					$prix = ""; 
					$prix1 = "<span class='fontrouge'><b>".PROMOTION."</b></span>";
				}
				else {
	                $prix1=""; 
	                $prix = "- ".PRIX." : <b>".$row['products_price']." ".$symbolDevise."</b>";
	                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
	                    $prix2 = "- ".VOTRE_PRIX." : <b><span class='fontrouge'>".newPrice($row['products_price'],$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	                }
				}
           }
         }
         else {
             $prix = ""; 
             $prix1 = "";
             $prix2 = "";       
         }
           
    
        $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
        
        if($row['products_qt']>0) {
            $stockState = $openLeg."<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
        } 
        else {
          if($actRes=="oui") {
            $stockState = $openLeg."<img src='im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>";
            } 
            else {
               $stockState = $openLeg."<img src='im/stockno.gif' border='0' title='".NOT_IN_STOCK."' alt='".NOT_IN_STOCK."' align='absmiddle'></a>";
            }
        }
        if($row['products_forsale']=="no") {
            $stockState = $openLeg."<img src='im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
        }
	 
		if(in_array($row['products_id'], $_SESSION['discountQt']) AND $row['products_forsale']=='yes') {
			$prodDegressif = $openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
		} else {
			$prodDegressif = "";
		}

    print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='TDTableListLine".$d."'>";
  

 
    if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
        $catCat = $_GET['advCat'];
        $catCatQuery = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories
                                    WHERE categories_id = '".$_GET['advCat']."'");
        $catCatResult = mysql_fetch_array($catCatQuery);
        $catCatName = $catCatResult['categories_name_'.$_SESSION['lang']];
        $descLink = "&advCat=".$_GET['advCat'];
    }
    else {
        $catCat = $row['categories_id'];
        $catCatName = $row['categories_name_'.$_SESSION['lang']];
    }
    
   
    if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
        $descLink = "&advComp=".$_GET['advComp'];
    }

    
    if(isset($search_queryModified) AND !empty($search_queryModified)) {
        $descLink .= "&search_query=".$search_queryModified;
    }

    
    
     
    $images_widthSearch = $haut_im+20;
      $yoZZ1 = @getimagesize($row['products_image']);
      if(!$yoZZ1) $row['products_image']="im/zzz_gris.gif";
    $image_resizeSearch = resizeImage($row['products_image'],$haut_im,$images_widthSearch);
    print "<td width='".$images_widthSearch."' align='left'>";
    print "<a href='beschrijving.php?id=".$row['products_id']."&path=".$catCat."&sort=".$order."".$descLink."'>";
    print "<img src='".$row['products_image']."' border='0' width='".$image_resizeSearch[0]."' height='".$image_resizeSearch[1]."'>";
    print "</a>";
    print "</td>";
    
    print "<td>";
    print "<b>".$n++."</b> - <b>";
    print "<a href='beschrijving.php?id=".$row['products_id']."&path=".$catCat."&sort=".$order."".$descLink."'>";
    print highlight_words($row['products_name_'.$_SESSION['lang']]);
    print "</a></b> ".$prix1;
    print "<br>";
     
    $longMax = 220;
    $endW = "<a href='beschrijving.php?id=".$row['products_id']."&path=".$catCat."&sort=".$order."".$descLink."'>..<img src='im/next.gif' border='0'></a>";
    $cutDesc = adjust_text($prodDescClean,$longMax,"");
    print highlight_words($cutDesc);
    if(strlen($prodDescClean) > $longMax) print $endW;
    print "<br>";
     
     
    print "- Ref : ".highlight_words($row['products_ref']);
    print "<br>";
    
 
    if(isset($search_queryModified) AND preg_match("/".strtolower($search_queryModified)."/", strtolower($row['products_ean']))) {
        print "- Code : ".highlight_words($row['products_ean']);
        print "<br>";
    }
    
  
    print "- ".CATEGORIE.": [<a href='list.php?path=".$catCat."'>".$catCatName."</a>]";
    print "<br>";
   
    print "- ".DISPO.": ".$stockState.$prodDegressif;
    if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $row['products_forsale']=="yes") {
        print "<br>".$prix;
        if(isset($prix2)) {
            print "<br>".$prix2;
            unset($prix2);
        }
    }
    print "</td>";
    print "</tr>";
  }
  print "</table><br>";
  }
}


if(!isset($_GET['action']) OR $_GET['action']!=="search") {
  print "<p align='center'>";
  display_search();
  print "</p>";
}
      ?>
                </td>
              </tr>
            </table>
          </td>

         <?php 
		  // --------------------------------------
		  // rechtse kolom
		  // --------------------------------------
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
