<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');

include("../includes/lang/lang_".$_SESSION['lang'].".php");
$title = RECHERCHER;
$addQuery55 = "";
if($displayOutOfStock=="non") {$addToQueryStock = " AND p.products_qt>'0'";} else {$addToQueryStock="";}

if(isset($_GET['search_query']) AND $_GET['search_query']!=='') {
    $search_queryModified = str_replace("'","’",$_GET['search_query']);
    $search_queryModified = str_replace("\\","",$search_queryModified);
    $_GET['search_query'] = str_replace("\\","",$_GET['search_query']);
}

if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
    $addToLink = "&advCat=".$_GET['advCat'];
    $findNameQuery = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                                  FROM categories
                                  WHERE categories_id = '".$_GET['advCat']."'");
    $findName = mysql_fetch_array($findNameQuery);
    $searchReq = $findName['categories_name_'.$_SESSION['lang'].'']." | ";
}
else {
    if(isset($_GET['advCat']) AND $_GET['advCat']=="all") $addToLink = "&advCat=all"; else $addToLink = "";
    if(isset($_GET['advCat']) AND $_GET['advCat']=="all") $searchReq = TOUTES_CAT." |"; else $searchReq = "";
}

if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
    $addToLink .= "&advComp=".$_GET['advComp'];
    $findCompQuery = mysql_query("SELECT fournisseurs_company
                                  FROM fournisseurs
                                  WHERE fournisseurs_id = '".$_GET['advComp']."'");
    $findComp = mysql_fetch_array($findCompQuery);
    $searchReq .= $findComp['fournisseurs_company'];
}
else {
    if(isset($_GET['advComp']) AND $_GET['advComp']=="all") $addToLink .= "&advComp=all"; else $addToLink .= "";
    if(isset($_GET['advComp']) AND $_GET['advComp']=="all") $searchReq .= " Tous vendeurs"; else $searchReq .= "";
}

$asc = "ASC";
if(isset($_GET['sort'])) {
    if(isset($_GET['sort']) AND $_GET['sort']=="id") {$_GET['sort']="products_id"; $order = "id"; $asc = "DESC";}
    if($_GET['sort']=="Id") {$sort="products_id"; $asc = "ASC"; $order = "Id"; $asc = "DESC";}
    if($_GET['sort']=="Ref") {$sort="products_ref"; $order = "Ref";}
    if($_GET['sort']=="Article") {$sort="products_name_".$_SESSION['lang'].""; $order = "Article";}
    if($_GET['sort']=="Prix") {$sort="ord"; $order = "Prix";}
    if($_GET['sort']=="Entreprise") {$sort="fournisseurs_company"; $order = "Entreprise";}
    if($_GET['sort']=="Les_plus_populaires") {$sort="products_viewed";  $order = "Les_plus_populaires"; $asc = "DESC";}
}
if(!isset($_GET['sort']) OR empty($_GET['sort'])) {$sort="products_id"; $order = "Id"; $asc = "DESC";}

 

function display_search() {
    GLOBAL $_GET, $_SESSION, $url_id10, $slash;
    $colspan = 2;
    include('../configuratie/configuratie.php');
    print '<table border="0" cellspacing="0" cellpadding="10" align="center" >
              <tr>
                <td align="center" valign="top">';
    print "<p align='center'>";
    
          print "<table border='0' align='center' cellspacing='0' class='TABLE1' cellpadding='5'><tr>";
          print "<form action='redirectzoeken.php' method='post'>";
              if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on") {
                  print "<td colspan='2'>";
                  include('../includes/zoeken_uitgebreid.php');
                  print "</td></tr>";
              }
          print "<td align='left' height='1'>";
              if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on") {
                  print "<img src='im/fleche_right.gif'><img src='im/zzz.gif' width='4' height='1'>";
              }
          print "<input type='text' name='search_query' maxlength='100' size='18'></td>";
          print "<td align='left'><input type='image' src='im/search.gif' alt='".RECHERCHER."'></td>";
          print "</tr><tr>";

          print "<td align='right' height='1' valign='bottom' colspan='".$colspan."' style='background-color:#f5f5f5'>";
          if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "off") {
          print "<a href='".$url_id10.$slash."AdSearch=on'><img src='../includes/menu/plus.gif' border='0'></a>";
          }
          if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "on") {
          print "<a href='".$url_id10.$slash."AdSearch=off'><img src='../includes/menu/minus.gif' border='0'></a>";
          }
          if(!isset($_SESSION['AdSearch'])) {
          print "<a href='".$url_id10.$slash."AdSearch=on'><img src='../includes/menu/plus.gif' border='0'></a>";
          }
          print "</td>";
          print "</form>";
          print "</tr></table>";
          
            print "<a href='javascript:void(0);' onClick=\"window.open('../gebruik_zoeken.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=215,width=490,toolbar=no,scrollbars=no,resizable=yes');\">";
            print MODE_EMPLOI;
            print "</a>";
            
    print "</p>";
    print '</td></tr></table>';
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php print $_SESSION['css'];?>" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <?php include('top.php');?>

        <table border="0" width="450" cellspacing="0" cellpadding="0" align="center">
            <tr>
                <td valign="top" class="TABLEMenuPathTopPage">



      <table width="100%" border="0" cellspacing="0" cellpadding="5" class="">
      <tr>
      <td>
                          <?php
                          $pour = empty($search_queryModified) ? $searchReq : $searchReq." | ".$search_queryModified;
                          print "<b><img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='index.php'>".maj(HOME)."</a> | ".STRTOUPPER(RECHERCHER)." ".str_replace("!"," ",$pour)."</b>";
                          ?>
      </td>
      </tr>
      </table>


      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>

          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">

      <?php

if(
  (isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on" AND !isset($search_queryModified) AND !isset($_GET['advCat']) AND !isset($_GET['advComp']))
    OR
  (isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="off" AND empty($search_queryModified))
  )
  {
        
        if(isset($_GET['action']) AND $_GET['action']=="search") {
          display_search();
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
                        for($i=0; $i<=count($ChercherSeparateur)-1; $i++) {
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
             $addQuery55 .=")";
       }
       }
       
        if(isset($_GET['advCat']) AND $_GET['advCat']!=="all") {
            $addQuery55 .= " AND p.categories_id = '".$_GET['advCat']."'";
        }
        if(isset($_GET['advComp']) AND $_GET['advComp']!=="all") {
            $addQuery55 .= " AND p.fabricant_id = '".$_GET['advComp']."'";
        }

        if(isset($_GET['advCat'])) {


            $addQuery55p = " OR (p.categories_id_bis LIKE '%|".$_GET['advCat']."|%' AND p.products_visible = 'yes')";
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

$nbre_ligne = 4;  
if(!isset($_GET['page'])) {$_GET['page']=0;}

$queryVar55 .= " ORDER BY ".$sort." ".$asc." LIMIT ".$_GET['page'].",".$nbre_ligne;
$query55 = mysql_query($queryVar55);

  if($tototal == 0) {
     print "<table border='0' width='300' align='center' cellspacing='0' cellpadding='8' class='TABLE1'><tr><td>
            <div align='center'>".AUCUN_RESULTAT."</div>
            </td></tr></table>";
  }
  else {
 
   if($_GET['page'] == 0 and $tototal>1) {

       print "<table width='98%' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLESortByCentre'><tr><td>";
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
               print "<img src='im/fleche_right.gif'>&nbsp;".$order."";
         }
          else {
               print "<img src='im/fleche_right.gif'>&nbsp;Id";
         }
         print "</td></tr></table>";
    }
    else {
          if($tototal > 1) {

          if(isset($order)) {
             print "<table width='98%' align='center' border='0' cellspacing='0' cellpadding='8' class='TABLESortByCentre'><tr><td>";
             print "<img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR." <b>".$order."</b>";
             print "</td></tr></table>";
           }
          else {
          	 print "<table width='98%' align='center' border='0' cellspacing='0' cellpadding='8' class='TABLESortByCentre'><tr><td>";
             print "<img src='im/fleche_right.gif'>&nbsp;".CLASSER_PAR." <b>Id</b>";
             print "</td></tr></table>";
          }
          }
          else {
             print "<table width='98%' align='center' border='0' cellspacing='0' cellpadding='0'><tr><td>";
             print "";
             print "</td></tr></table>";
          }

    } 

 

     
     print "<table border='0' width='98%' align='center' cellspacing='0' cellpadding='2' align='center'><tr>";
     print "<td>";
     print "<div align='right'>".RESULTAT.": <span class='fontrouge'><b>".$tototal."</b></span></div>";
     print "</td>";
     print "</tr><tr>";
      
     if($tototal > $nbre_ligne) {
        print "<td>";
        $NavNum = "<a href='".$_SERVER['PHP_SELF']."?sort=".$order."&sep=".$_GET['sep']."&search_query=".$_GET['search_query']."".$addToLink."&page=";
        include('../includes/lijst_navigatie.php');
        displayPageNum($nbre_ligne);
        print "</td>";
     }
  print "</tr></table>";


  print "<table width='98%' align='center' border='0' cellspacing='0' cellpadding='3' class='TABLESortByCentre'>";
  $n=$_GET['page']+1;

  while ($row= mysql_fetch_array($query55)) {

         if(isset($d) and $d==2) $d = 1; else $d = 2;
         
          
         $prodDescClean = $row['products_desc_'.$_SESSION['lang']];
         $prodDescClean = strip_tags($prodDescClean);

          
         if($row['products_forsale']=="yes") {
           if($row['products_price'] == $row['ord']) {
                $prix1="";
                $prix = "- ".PRIX." : <b>".$row['products_price']." ".$symbolDevise."</b>";
           }
           else {
           		if($row['specials_visible']=="yes") {
					$prix = ""; 
					$prix1 = "<span class='fontrouge'><b>".PROMOTION."</b></span>";
				}
				else {
	                $prix1=""; 
	                $prix = "- ".PRIX." : <b>".$row['products_price']." ".$symbolDevise."</b>";
				}
           }
         }
         else {
             $prix = ""; 
             $prix1 = "";       
         }
		 
		        $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
		if(in_array($row['products_id'], $_SESSION['discountQt']) AND $row['products_forsale']=='yes') {
			$prodDegressif = $openLeg."<img src='../im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
		} else {
			$prodDegressif = "";
		}

         
        $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
        
        if($row['products_qt']>0) {
            $stockState = $openLeg."<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
        } 
        else {
          if($actRes=="oui") {
            $stockState = $openLeg."<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>";
          }
          else {
            $stockState = $openLeg."<img src='../im/stockno.gif' border='0' title='".NOT_IN_STOCK."' alt='".NOT_IN_STOCK."' align='absmiddle'></a>";
          }
        }
        if($row['products_forsale']=="no") {
            $stockState = $openLeg."<img src='../im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
        }
    print "<tr>";
    print "<td class='TDTableListLine".$d."' valign='top'>".$n++."</td>";
    print "<td class='TDTableListLine".$d."'>";
    print "<a href='beschrijving.php?id=".$row['products_id']."&path=".$row['categories_id']."&sort=".$defaultOrder."'><b>".$row['products_name_'.$_SESSION['lang']]."</b></a> ";
    print $prix1."<br>";
    print adjust_text($prodDescClean,120," [<a href='beschrijving.php?id=".$row['products_id']."&path=".$row['categories_id']."&sort=".$defaultOrder."'>..</a>]");
    print "<br>";
            print "- ".CATEGORIE.": [<a href='list.php?path=".$row['categories_id']."'>".$row['categories_name_'.$_SESSION['lang']]."</a>]<br>";
            print "- ".DISPO.": ".$stockState.$prodDegressif;
    if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $row['products_forsale']=="yes") {
        print "<br>".$prix;
    }
    print "</td>";
    print "</tr>";
  }
  print "</table><br>";
  }

}


/*
if(!isset($_GET['action']) OR $_GET['action']!=="search") {
  print "<p align='center'>";
  display_search();
  print "</p>";
}
*/
      ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
</td>
</tr></table>
</body>
</html>
