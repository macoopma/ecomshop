<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');

$imageSizeCatalog = 70;
$largTableCatalog = 130;
$NbreProduitAfficheCatalog = 3;
$nbre_col_catalog = 3;

include("../includes/lang/lang_".$_SESSION['lang'].".php");


if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {$tableMaxHeight = $imageSizeCatalog+110;} else {$tableMaxHeight = $imageSizeCatalog+90;}
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
                  
               <br>
            
<?php
if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}

$randNum = rand(0,10);
$countReq = 0;
//-----> Quelques article en promo <------
if($catDisplayPromo=="on") {
$resultcPromo = "SELECT p.products_forsale, p.products_qt,p.products_deee, p.products_im, p.products_exclusive, p.products_download, p.products_image, p.products_name_".$_SESSION['lang'].", p.products_ref, p.products_id, p.products_price,c.*, s.specials_last_day, s.specials_first_day, s.specials_new_price, s.specials_visible, RAND() AS m_random
                                     FROM products as p
                                     INNER JOIN specials as s
                                     ON (p.products_id = s.products_id)
                                     LEFT JOIN categories as c
                                     ON (p.categories_id = c.categories_id)
                                     WHERE s.specials_visible='yes'
                                     AND p.products_visible='yes'
                                     AND p.products_forsale='yes'
                                     AND c.categories_visible = 'yes'
                                     AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                     AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)
                                     AND p.products_ref != 'GC100'
                                     ".$addToQuery."
                                     ORDER BY specials_last_day";
$resultcCountPromo = mysql_num_rows(mysql_query($resultcPromo));
  if($resultcCountPromo>0) {
  
    $maxRandProm = $resultcCountPromo - $NbreProduitAfficheCatalog;
    $randNumPromo = rand(0,$maxRandProm);
    $resultcPromo .= " LIMIT ".$randNumPromo.",".$NbreProduitAfficheCatalog."";
    $resultc['Promo'] = $resultcPromo;
    $countReq = $countReq + 1;
  }
  else {
  $resultcPromo = "SELECT *
           FROM products
           WHERE products_price='12532251444.22'";
  $resultc['Promo'] = $resultcPromo;
    $countReq = $countReq + 1;
  }
}
//-----> Quelques article de la boutique <------
if($catDisplayFew=="on") {
$resultcFew = "SELECT p.products_forsale, p.products_id, p.products_deee, p.products_download, p.products_exclusive, p.products_image, p.categories_id, p.products_qt, p.products_name_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_viewed, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, c.categories_visible, c.parent_id, RAND() AS m_random
                         FROM products as p
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         LEFT JOIN categories as c 
                         ON (p.categories_id = c.categories_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_visible='yes'
                         AND p.products_forsale='yes'
                         AND p.products_ref != 'GC100'
                         ".$addToQuery."
						       ORDER BY products_viewed
                         DESC
                         ";
$resultcCountFew = mysql_num_rows(mysql_query($resultcFew));                   
$maxRandFew = $resultcCountFew - $NbreProduitAfficheCatalog;
$randNumFew = rand(0,$maxRandFew);
$resultcFew .= " LIMIT ".$randNumFew.",".$NbreProduitAfficheCatalog."";
$resultc['Few'] = $resultcFew;
    $countReq = $countReq + 1;
}
if($catDisplayBest=="on") {
//-----> nos meilleures ventes <------
$resultcBest = "SELECT p.products_forsale, p.products_id, p.products_deee, p.products_download, p.products_exclusive, p.products_image, p.products_qt, p.categories_id, p.products_name_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_viewed, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, c.categories_visible, c.parent_id
                         FROM products as p
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         LEFT JOIN categories as c
                         ON (p.categories_id = c.categories_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_visible='yes'
                         AND p.products_ref != 'GC100'
                         AND p.products_forsale='yes'
                         ".$addToQuery."
                         ORDER BY products_viewed
                         DESC
                         LIMIT ".$randNum.",".$NbreProduitAfficheCatalog;
    $resultc['Best'] = $resultcBest;
    $countReq = $countReq + 1;
}
if($catDisplayNews=="on") {
// -----> nos dernieres nouveautes <------
 $resultcNews = "SELECT p.products_forsale, p.categories_id, p.products_deee, p.products_exclusive, p.products_download, p.products_qt, p.products_visible, p.products_name_".$_SESSION['lang'].", p.products_image, p.products_id, c.parent_id, p.products_price, p.products_im, p.products_ref, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                       FROM products as p
                       LEFT JOIN specials as s
                       ON (p.products_id = s.products_id)
                       LEFT JOIN categories as c
                       ON (c.categories_id = p.categories_id)
                       WHERE TO_DAYS(NOW()) - TO_DAYS(p.products_date_added) <= '".$nbre_jour_nouv."'
                       AND c.categories_visible = 'yes'
                       AND p.products_visible='yes'
                       AND p.products_ref != 'GC100'
                       AND p.products_forsale='yes'
                       ".$addToQuery."
                       ORDER BY specials_last_day
                       ASC
                       LIMIT ".$randNum.",".$NbreProduitAfficheCatalog;
    $resultc['News'] = $resultcNews;
    $countReq = $countReq + 1;
}
if($catDisplayExc=="on") {
//-----> nos exclusités <------
$resultcExc = "SELECT p.products_forsale, p.products_id, p.products_deee, p.products_exclusive, p.products_download, p.products_image, p.products_qt, p.categories_id, p.products_name_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_viewed, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, c.categories_visible, c.parent_id
                         FROM products as p
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         LEFT JOIN categories as c
                         ON (p.categories_id = c.categories_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_visible='yes'
                         AND products_exclusive='yes'
                         AND p.products_ref != 'GC100'
                         AND p.products_forsale='yes'
                         ".$addToQuery."";
$resultcCountExc = mysql_num_rows(mysql_query($resultcExc));
$maxRandExc = $resultcCountExc - $NbreProduitAfficheCatalog;
$randNumExc = rand(0,$maxRandExc);

$resultcExc .= " ORDER BY products_exclusive DESC LIMIT ".$randNumExc.",".$NbreProduitAfficheCatalog."";
    $resultc['Exc'] = $resultcExc;
    $countReq = $countReq + 1;
}


      // Set variables
            $q=0;
            $countResultc = count($resultc);
            $keys[] = array_keys($resultc);

            // Affichage aléatoire des tableaux
            if($catDisplayRandOne=="on") { shuffle($keys[0]); $countResultc = 1; }
            if($catDisplayRandAll=="on") { shuffle($keys[0]);}
            
            

            
            
            
            

if($countResultc>1) {$aa=1;} else {$aa=$countResultc-1;}
for($i=0; $i<=$aa; $i++) {

        //$keys[] = array_keys($resultc);
        
        $resultVar = ${'resultc'.$keys[0][$i]};
        $resultVarQuery = mysql_query($resultVar);
        $resultVarNum = mysql_num_rows($resultVarQuery);
        if($resultVarNum>0) {
      // Afficher titre
      print "<table class='TABLE1' align='center' border='0' width='98%' cellspacing='0' cellpadding='4'>";
      print "<tr height='30'><td class='titre'>";
                  if($keys[0][$i] == "Best") { print "&nbsp;".MEILLEURES_VENTES; $linkTo = "<td align='right' width='1'><a href='top10.php'><img src='im/arrow.gif' border='0' title='".PLUS_INFOS."' alt='".PLUS_INFOS."'></a></td>";}
                  if($keys[0][$i] == "Few") { print "&nbsp;".FEW_PRODUCTS; $linkTo = "<td align='right' width='1'>&nbsp;</td>";}
                  if($keys[0][$i] == "News") { print "&nbsp;".LAST_NEWS; $linkTo = "<td align='right' width='1'><a href='list.php?target=new'><img src='im/arrow.gif' border='0' title='".PLUS_INFOS."' alt='".PLUS_INFOS."'></a></td>";}
                  if($keys[0][$i] == "Promo") { print "&nbsp;".EN_PROMO; $linkTo = "<td align='right' width='1'><a href='list.php?target=promo'><img src='im/arrow.gif' border='0' title='".PLUS_INFOS."' alt='".PLUS_INFOS."'></a></td>";}
                  if($keys[0][$i] == "Exc") { print "&nbsp;".EN_EXCLUSIVITE."&nbsp;<img src='../im/coeur.gif' align='absmiddle'>"; $linkTo = "<td align='right' width='1'><a href='list.php?target=favorite'><img src='im/arrow.gif' border='0' title='".PLUS_INFOS."' alt='".PLUS_INFOS."'></a></td>";}
      print "</td>";
      print $linkTo;
      print "</tr></table>";
      print "<br>";
      }








print '<table border="0" cellspacing="0" cellpadding="2" align="center">
              <tr>';


   while($c_row = mysql_fetch_array($resultVarQuery)) {
// display price

       if(!empty($c_row['specials_new_price'])) {
       		if($c_row['specials_visible']=="yes") {
               $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
               $dateMaxCheck = explode("-",$c_row['specials_last_day']);
               $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
               $dateDebutCheck = explode("-",$c_row['specials_first_day']);
               $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);

               if($dateDebut <= $today  and $dateMax >= $today) {
                  $price = $c_row['specials_new_price'] ;
                  $old_price = $c_row['products_price'] ;
                  $display_price = "<b><s>".$old_price."</s> ".$symbolDevise."<br>".$price." ".$symbolDevise."</b>";
                  $saveNum = (1-($price/$old_price))*100;
                  $saveNum = sprintf("%0.2f",$saveNum);
                  //$displaySave = "<i><b><span class='fontrouge'>-".$saveNum."%</span></b></i>";                  
                  $displaySave = "";
               }
               else {
                     $display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
                     $displaySave = "";
               }
            }
            else {
	           $display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
	           $displaySave = "";
			}
       }
       else {
             $display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
             $displaySave = "";
       }
// display DEEE
if($c_row['products_deee']>0) {
    $openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('../includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
    $displayDeee = "<br>".$openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a>: ".$c_row['products_deee']." ".$symbolDevise."</i>";
}
else {
    $displayDeee = "";
}
// display image
if($c_row['products_im']=="yes" AND !empty($c_row['products_image']) AND $c_row['products_image']!=="im/no_image_small.gif") {
    $largImageMax = $largTableCatalog;
    if(substr($c_row['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
              $yoZ1 = @getimagesize($dirr.$c_row['products_image']);
              if(!$yoZ1) $c_row['products_image']="im/zzz_gris.gif";
    $ImageSizze = resizeImageMini($dirr.$c_row['products_image'],$imageSizeCatalog,$largImageMax);
    $display_image = "<a href='beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id']."'>";
    
           // Is server GD open ? 
           if($gdOpen == "non") {
                    $display_image .= "<img src='".$dirr.$c_row['products_image']."' border='0' width='".$ImageSizze[0]."' height='".$ImageSizze[1]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";                       
           }
           else {
                    $infoImage = infoImageFunction("".$dirr.$c_row['products_image'],$largImageMax,$imageSizeCatalog);
                    $display_image .= "<img src='../mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";
           }
    $display_image .= "</a>";
}
else {
        $largImageMax = $largTableCatalog;
        $noImage_resizeCat = resizeImage("../im/lang".$_SESSION['lang']."/no_image.png",$imageSizeCatalog,$largImageMax);
        $display_image = "<a href='beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id']."'>";
        $display_image .= "<img src='../im/lang".$_SESSION['lang']."/no_image.png' width='".$noImage_resizeCat[0]."' height='".$noImage_resizeCat[1]."' border='0'>";
        $display_image .= "</a>";
}          

// lien legende
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";

// Verifier si cet article est dans le panier
   if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$c_row['products_ref']."+")) {
      $bouton = $openLeg."<img src='../im/cart.gif' border='0' title='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."' alt='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."'></a>&nbsp;";
   }
   else {
       $bouton = "";
   }

// product download
    if($c_row['products_download'] == "yes") {
      $prodDown = $openLeg."<img src='../im/download.gif' border='0' title='".ARTICLE_TELE."' alt='".ARTICLE_TELE."' align='absmiddle'></a>&nbsp;";
    } else {
      $prodDown = "";
    }

// Disponibilite
    if($c_row['products_qt']>0) {
      $stockLev = $openLeg."<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
    } else {
      if($actRes=="oui") $stockLev = $openLeg."<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>"; else $stockLev = $openLeg."<img src='../im/stockno.gif' border='0' title='".NOT_IN_STOCK."' align='absmiddle'></a>";
    }

// Coup de coeur
    if($c_row['products_exclusive'] == "yes") {
      $prodHart = $openLeg."<img src='../im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."' align='absmiddle'></a>&nbsp;";
    } else {
      $prodHart = "";
    }
// Prix dégressif
	if(in_array($c_row['products_id'], $_SESSION['discountQt']) AND $c_row['products_forsale']=='yes') {
		$prodDegressif = $openLeg."<img src='../im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>&nbsp;";
	} else {
		$prodDegressif = "";
	}
// Afficher la table //

                    $q = $q+1;
                    if(($q % $nbre_col_catalog) == 1) { print "</tr><tr valign='top'>"; }
                    if($nbre_col_catalog == 1) { print "</tr><tr valign='top'>"; }
                     print "<td>";
                     
                     print "<table border='0' align='center' width='".$largTableCatalog."' height='".$tableMaxHeight."' class='TABLEBoxesProductsDisplayedCentrePage' cellspacing='0' cellpadding='2'>
                             <tr height='34'>";
                     print "<td height='1' align='center' valign='top' class='TABLEBoxProductsDisplayedTop'>";
                     print "<a href='beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id']."' ".display_title($c_row['products_name_'.$_SESSION['lang']],29).">";
                     print "<b>".adjust_text($c_row['products_name_'.$_SESSION['lang']],29,"..")."</b>";
                     print "</a>";
                     print "</td>";
                     print "</tr><tr>";
                     print "<td align='center' valign='top' class='TABLEBoxProductsDisplayedMiddle'>";
                     if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	                     print "<div>";
	                     print $display_price;
	                     print $displayDeee;
	                     if(!empty($displaySave)) print "<br>".$displaySave;
	                     print "</div>";
                     }
                     print "<br>";
                     print $display_image;
                     print "</td>";
                     print "</tr><tr>";
                     print "<td height='1' align='right' valign='bottom' class='TABLEBoxesProductsDisplayedBottom'>";
                     print $prodDegressif;
                     print $prodHart;
                     print $prodDown;
                     print $bouton;
                     print $stockLev;
                     print "</td>";
                     print "</tr></table>";
                     print "<img src='im/zzz.gif' width='1' height='10'>";
                     print "</td>";
}
print "</tr></table>";
}
?>
                  </td>
              </tr>
            </table>
                  </td>
              </tr>
            </table>
<?php
/*
---------------------------------------------------------
NE PAS SUPPRIMER - NE PAS MODIFIER LE MARQUEUR CI-DESSOUS
---------------------------------------------------------
Ce marqueur est obligatoire. Il permet d'identifier le titulaire de la licence et la version de cette boutique.
Toute suppression ou modification du code ci-dessous ou du fichier versie.txt devra être justifié et pourrait être considéré comme une offense par l'auteur et/ou le service juridique de BoutikOne.
Merci de votre compréhension.
L'équipe BoutikOne.
*/
$crypte = Crypte($nic2,"boutikone66");
print "<!--";
print $crypte;
include("../versie.txt");
print "-->";
?>
</body>
</html>
