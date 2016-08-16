<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');

include("../includes/lang/lang_".$_SESSION['lang'].".php");
$tt="99%";
$promoIs="";
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
    // current url
    if($_SERVER['REQUEST_URI']) {
        if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER['REQUEST_URI'];
    }
    else {
        if(!isset($_SESSION['ActiveUrl'])) $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
    }
   // Mise à jour bdd products_viewed
   mysql_query("UPDATE products SET products_viewed = products_viewed+1 WHERE products_id='".$_GET['id']."'");

   // Requetes
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
   
// article discontinué
if($rowp['products_forsale']=="no") $discontinue = "<div class='FontColorTotalPrice'><b>** ".OUT_OF_STOCK." **</b></div>"; else  $discontinue="";

if(isset($_GET['path'])) {
   $result3 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                         FROM categories
                         WHERE categories_id = '".$_GET['path']."'");
   $cat_name3 = mysql_fetch_array($result3);
   }
   else {
   $result3 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                         FROM categories
                         WHERE categories_id = '".$rowp['parent_id']."'");
   $cat_name3 = mysql_fetch_array($result3);
   $path = $rowp['parent_id'];
   }


if(!isset($_GET['page'])) $page=0; else $page=$_GET['page'];
if(!isset($_GET['sort'])) $sort=$defaultOrder; else $sort = $_GET['sort'];
if(!isset($_GET['sort']) AND isset($_GET['target']) AND $_GET['target'] == "promo") $sort = "specials_last_day";

if(!isset($_GET['target'])) {
    $categorie = $cat_name3['categories_name_'.$_SESSION['lang']];
    $BarreMenuHautTitre = $categorie." | ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) and $_GET['target'] == "new") {
    $categorie = NOUVEAUTESMAJ;
    $BarreMenuHautTitre = NOUVEAUTES." | ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) and $_GET['target'] == "promo") {
    $categorie = maj(PROMOTIONS);
    $BarreMenuHautTitre = PROMOTIONS." | ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) and $_GET['target'] == "author") {
    $categorie = $cat_name3['categories_name_'.$_SESSION['lang']];
    $BarreMenuHautTitre = $categorie." | ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['target']) and $_GET['target'] == "favorite") {
    $categorie = maj(COEUR);
    $BarreMenuHautTitre = COEUR." | ".$rowp['products_name_'.$_SESSION['lang']];
}
if(isset($_GET['tow']) AND $_GET['tow']=="flash") {
    $categorie = maj(VENTES_FLASH);
    $BarreMenuHautTitre = $categorie." | ".$rowp['products_name_'.$_SESSION['lang']];
}

$title = $BarreMenuHautTitre;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php print $_SESSION['css'];?>" type="text/css">
<?php if($lightbox=="oui") {?>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js?load=effects"></script>
<script type="text/javascript" src="../js/lightbox.js"></script>
<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen" />
<?php }?>

<style type="text/css">
<!--
.aff_div {display:block;}
.cache_div {display :none;}
-->
</style>

<script language="JavaScript" type="text/JavaScript">
function deroul_div(id_div) {
	if(document.getElementById(id_div).className=='aff_div') {
			document.getElementById(id_div).className='cache_div';
	}
	else if(document.getElementById(id_div).className=='cache_div') {
		document.getElementById(id_div).className='aff_div';
	}
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include('top.php');?>

<table border="0" width="450" cellspacing="0" cellpadding="0" align="center">
<tr>
<td valign="top" class="TABLEMenuPathTopPage">

<table width="100%" border="0" cellpadding="0" cellspacing="0">
</tr>
<td>


<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="1" valign="top">



<?php 
if($resultNum>0) {
?>
            <table width="440" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr height="10">
                <td valign="top">

<?php
$refdet = maj($rowp['products_ref']);

			// Verification date & prix de promo
			if(!empty($rowp['specials_new_price'])) {
				if($rowp['specials_visible']=="yes") {
					$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
					if(!empty($rowp['specials_last_day'])) $dateMaxCheck = explode("-",$rowp['specials_last_day']); else $dateMaxCheck = explode("-","0-0-0");
					$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
					if(!empty($rowp['specials_first_day'])) $dateDebutCheck = explode("-",$rowp['specials_first_day']); else $dateDebutCheck = explode("-","0-0-0");
					$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
					
					if($dateDebut <= $today  and $dateMax >= $today) {
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

       // Verification prix promo
       if(isset($delayPassed) AND $delayPassed == "no" AND $rowp['products_forsale']=="yes") {
	        $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
	        $jour = ($itMiss>1)? JOURS."s": JOURS;                      
	        
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
            $aa = '<script type="text/javascript" src="../includes/aftellen.js"></script>';
  
            $EnPromo = "<p align='center'>".EN_PROMOTION_JUSQU_AU." ".date("d M Y",mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]))."</p>";
            $EnPromo.= "<table border='0' width='250' cellpadding='2' cellspacing='0' class='TABLE1' align='center'>";
            $EnPromo.= "<tr>";
            $EnPromo.= "<td align='center'>";
            $EnPromo.= "<span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise."s ".DE_REDUCTION." **</b></span>";
            $EnPromo.= "<br>";
            $EnPromo.= FIN_DE_PROMOTION_DANS." ".$aa;
            $EnPromo.= "</td>";
            $EnPromo.= "</tr>";
            $EnPromo.= "</table>";
        }

       if(isset($delayPassed) AND $delayPassed == "no" AND $rowp['specials_last_day'] == "2020-01-01" AND $rowp['products_forsale']=="yes") {
          $EnPromo = "<br>";
          $EnPromo.= "<table border='0' width='250' cellpadding='5' cellspacing='0' class='TABLE1' align='center'>";
          $EnPromo.= "<tr>";
          $EnPromo.= "<td align='center'>".EN_PROMOTION_JUSQUE_A_NOUVEL_ORDRE."<br><span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise."s ".DE_REDUCTION." **</b></span>";
          $EnPromo.= "</td>";
          $EnPromo.= "</tr>";
          $EnPromo.= "</table>";
       }

       // verifier prix
       if(!empty($rowp['specials_new_price']) AND isset($delayPassed) AND $delayPassed == "no") {
           $price = $rowp['specials_new_price'];
           $displayPrice = "<span style='FONT-SIZE: 11px'><b><s>".$rowp['products_price']." ".$symbolDevise."</s></b></span><br><span class='PromoFontColorNumber'><b>".$price." ".$symbolDevise."</b></span>";
           $sautLigne = "<br>";
       }
       else {
           $price = $rowp['products_price'];
           $displayPrice = "<span style='FONT-SIZE: 11px'><b>".$price." ".$symbolDevise."</b></span>";
           $sautLigne = "";
       }

       // Verifier si cet article est dans le panier

        if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowp['products_ref']."+")) {
           $caddie_yes = "<img src='../im/cart.gif'>&nbsp;<a href='../caddie.php' target='_blank'>".ARTICLE_PRESENT_DANS_LE_CADDIE."</a>";
           $isThere = "yes";
        }
        else {
           $caddie_yes = "<img src='../im/fleche_menu.gif' align='absmiddle'>&nbsp;".ARTICLE_ABSENT_DU_CADDIE;
           $isThere = "no";
        }

// Afficher table titre

// ************//
// *** FORM ***//
// ************//
print "<form action='../add.php' method='GET'>";

print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' class='titre' align='center'>
        <tr>
        <td valign='middle' height='25'>";
        // Afficher nom de l'article
        print "<b class='titre'>".$rowp['products_name_'.$_SESSION['lang']]."</b>";
        print "</td>";
        if($rowp['products_forsale']=="yes") {
        // Afficher coup de coeur!
        if($rowp['products_exclusive']=="yes") {
            print "<td width='1' align='right'>";
            print "<a href='list.php?target=favorite'><img src='../im/coeur.gif' border='0' title='".COEUR."' alt='".COEUR."'></a>";
            print "</td>";
        }
		// Prix dégressif
		if(in_array($rowp['products_id'], $_SESSION['discountQt'])) {
			print "<td width='1' align='right'>";
			print $openLeg."<img src='../im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."'></a>";
			print "</td>";
		}
        // Afficher ventes flash
        if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND isset($promoIs) AND $promoIs=='yes' AND $activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo) {
            print "<td width='1' align='right'>";
            print "<a href='list.php?target=promo&tow=flash'><img src='../im/time_anim.gif' border='0' title='".VENTES_FLASH."' alt='".VENTES_FLASH."'></a>";
            print "</td>";        
        }
        }
        else {
        // Afficher article no stock
            print "<td width='1' align='right'>";
            print "&nbsp;";
            print "</td>";
        }
print "</tr>
        </table>";

print "<table width='".$tt."' border='0' cellspacing='3' cellpadding='0' align='center'>";
print "<tr>";

// Afficher image principale
        if($rowp['products_im'] == "yes" AND !empty($rowp['products_image']) AND $rowp['products_image']!=="im/no_image_small.gif") {
        if(substr($rowp['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
        $images_widthDesc = $ImageSizeDesc+10;
        $h = @getimagesize($dirr.$rowp['products_image']);
        if(!$h) $rowp['products_image']="../im/zzz_gris.gif";
        $image_desc = resizeImageMini($dirr.$rowp['products_image'],$ImageSizeDesc,$images_widthDesc);
        print "<td width='".$image_desc[0]."' align='left' valign='top'>";

            // LightBox
            if($lightbox == "oui") {
               print "<a href='".$dirr.$rowp['products_image']."' rel='lightbox[roadtrip]' alt='".$rowp['products_name_'.$_SESSION['lang']]."' title='".$rowp['products_name_'.$_SESSION['lang']]."' target='_blank'>";
            }
            else {
               print "<a href='javascript:void(0);' onClick=\"window.open('../pop_up.php?im=".$rowp['products_image']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$h[1].",width=".$h[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
            }
           // Is server GD open ? 
           if($gdOpen == "non") {
               print "<img src='".$dirr.$rowp['products_image']."' border='0' width='".$image_desc[0]."' height='".$image_desc[1]."' alt='".$rowp['products_name_'.$_SESSION['lang']]."'>";
           }
           else {
               $infoImage = infoImageFunction($dirr.$rowp['products_image'],$images_widthDesc,$ImageSizeDesc);
               print "<img src='../mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowp['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$rowp['products_name_'.$_SESSION['lang']]."' border='0'>";                  
           }
        print "</a>";
        print "</td>";
              
            // Afficher mini image ou son
    			for($n=2; $n<=5; $n++) {
              if(!empty($rowp['products_image'.$n.''])) {
                  // image
                  if(substr($rowp['products_image'.$n.''],-3) == "gif" OR 
                     substr($rowp['products_image'.$n.''],-3) == "jpg" OR 
                     substr($rowp['products_image'.$n.''],-3) == "png") {
                      $h1 = @getimagesize($dirr.$rowp['products_image'.$n.'']);
                      if(!$h1) $rowp['products_image'.$n]="../im/zzz_gris.gif";
                      $image_desc2 = resizeImageMini($dirr.$rowp['products_image'.$n],$SecImageSizeDesc,50);
                        print "<td width='".$image_desc2[0]."' height='".$image_desc2[1]."' valign='top'>";
                               
                        print "<table cellspacing='0' cellpadding='0' style='border: 1px #000000 solid; padding: 1px;'><tr>";
                        print "<td>";
                                   
                        // LightBox
                        if($lightbox == "oui") {
                           print "<a href='".$dirr.$rowp['products_image'.$n]."' rel='lightbox[roadtrip]' alt='".$rowp['products_name_'.$_SESSION['lang']]."' title='".$rowp['products_name_'.$_SESSION['lang']]."' target='_blank'>";
                        }
                        else {
                           print "<a href='javascript:void(0);' onClick=\"window.open('../pop_up.php?im=".$rowp['products_image'.$n.'']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$h1[1].",width=".$h1[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
                        }
                         // Is server GD open ? 
                         if($gdOpen == "non") {
                                  print "<img src='".$dirr.$rowp['products_image'.$n.'']."' width='".$image_desc2[0]."' height='".$image_desc2[1]."' border='0'>";
                         }
                         else {
                                  $infoImage = infoImageFunction($dirr.$rowp['products_image'.$n.''],$SecImageWidthDesc,$SecImageSizeDesc);
                                  print "<img src='../mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowp['products_image'.$n.'']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0'>";                  
                         }
                        print "</a>
                               </td></tr></table>
                               </td>";                
                  }
                  // video, music...
                    if(substr($rowp['products_image'.$n.''],-3) == "ram" OR 
                       substr($rowp['products_image'.$n.''],-3) == "mp3" OR 
                       substr($rowp['products_image'.$n.''],-3) == "wav" OR
                       substr($rowp['products_image'.$n.''],-2) == "au"  OR
                       substr($rowp['products_image'.$n.''],-3) == "aif" OR
                       substr($rowp['products_image'.$n.''],-3) == "wma" 
                       ) {
                        $explodeSoundFile = explode("/",$rowp['products_image'.$n.'']);
                        $namez = substr(end($explodeSoundFile), 0, -4);
                        print "<td width='1' valign='top'>";
                               print "<table cellspacing='0' cellpadding='0'><tr>";
                               print "<td>";
                               print "<a href='".$dirr.$rowp['products_image'.$n.'']."'><img src='../im/notes.gif' border='0' title='Title: ".$namez."' alt='Title: ".$namez."'></a>";
                               print "</td>";
                               print "</tr></table>";
                        print "</td>";
                  }
              }
            }
        }
        else {
        	$noImage_resizeDesc = resizeImageMini("../im/lang".$_SESSION['lang']."/no_image.png",$ImageSizeDesc,100);
        	print "<td rowspan='3' valign='middle' align='left'><img src='../im/lang".$_SESSION['lang']."/no_image.png' width='".$noImage_resizeDesc[0]."' height='".$noImage_resizeDesc[1]."'></td>";
        }

        print "<td align='right' valign='top'>";
// Afficher bouton ajouter au panier

		print "<div id='displayPrix'>";
		print "<table width='100%' border='0' cellspacing='2' cellpadding='0'><tr>";
		print "<td>";
		// Afficher prix
		if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $rowp['products_forsale']=="yes") {
			print "<div align='right'>".$displayPrice."</div>";
		}
		print "</td></tr></table>";
		print "</div>";
if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['products_forsale']=="yes") {
       		  print "<input type='hidden' value='".$rowp['products_id']."' name='id'>";
              print "<input type='hidden' value='".$rowp['products_ref']."' name='ref'>";
              print "<input type='hidden' value='".$rowp['products_name_'.$_SESSION['lang']]."' name='name'>";
              print "<input type='hidden' value='".$rowp['products_tax']."' name='productTax'>";
              print "<input type='hidden' value='".$rowp['products_deee']."' name='deeee'>";

             if($rowp['products_qt'] > 0) {
             if($isThere=="no") {
             	print "<div id='display_cart'>";
                print ACHETER."<br><input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='../im/zzz.gif' width='1' height='3'><br>";
                print "<input style='BACKGROUND: none; border:0px' type='image' src='../im/cart_add.png' title='".AJOUTER_AU_CADDIE."' alt='".AJOUTER_AU_CADDIE."'>";
                print "</div>";
             }
             else {

                   if($rowp['products_options'] == "no") {
                      print "<a href='../add.php?amount=0&ref=".$rowp['products_ref']."&id=".$rowp['products_id']."&name=".$rowp['products_name_'.$_SESSION['lang']]."&productTax=".$rowp['products_tax']."&deee=".$rowp['products_deee']."'><img src='../im/cart_rem.png' border='0' title='".RETIRER_DU_CADDIE."' alt='".RETIRER_DU_CADDIE."'></a>";
                   }
                   else {
                         print ACHETER."<br>";
                         print "<input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='../im/zzz.gif' width='1' height='3'><br>";
                         print "<input style='BACKGROUND: none; border:0px' type='image' src='../im/cart_add.png' title='".AJOUTER_AU_CADDIE."' alt='".AJOUTER_AU_CADDIE."'>";
                         print "<br>".AUTRES_OPTIONS;
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

        print "</td>";
        print "</tr>";
        print "</table>";

// Extraire fabricant et fournisseur
$fabricantQuery = mysql_query("SELECT fournisseurs_company
                               FROM fournisseurs
                               WHERE fournisseurs_id = '".$rowp['fabricant_id']."'");
$fab = mysql_fetch_array($fabricantQuery);
$fabricant = $fab['fournisseurs_company'];

// Afficher table texte details article
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' align='center'>";

// Afficher DEEE
if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $rowp['products_deee']>0 AND $rowp['products_forsale']=="yes") {
$openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('../includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
    print "<tr>";
    print "<td colspan='2' valign='bottom' align='left'>";
    print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$rowp['products_deee']." ".$symbolDevise."</i>";
    print "</td>";
    print "</tr>";
}

// Afficher fabricant
   if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['afficher_fabricant']=='yes' AND !empty($fabricant)) {
       print "<tr>
              <td width='100%' valign='bottom' align='left'>
              ".COMPAGNIE." ".$fabricant."
              </td>
              </tr>";
   }
// Afficher ref
print "<tr>";
print "<td colspan='2' valign='bottom' align='left'>";

// Afficher Référence
?>
<script type="text/javascript"><!--
	function afficher_ref(ref) {
		if(ref!='') {
			var affiche_ref = "";
			affiche_ref = affiche_ref + "<b><?php print REF;?></b>:&nbsp;" + ref;
			document.getElementById('div_ref1').style.display='none';
			document.getElementById('div_ref').style.display='block';
			
		}
		else {
			document.getElementById('div_ref1').style.display='block';
			document.getElementById('div_ref').style.display='none';
		}
				// Écrase le resultat précédent
				var div = document.getElementById("div_ref");
				while (div.childNodes[0]) {
					div.removeChild(div.childNodes[0]);
				}
				// Afficher resultat message
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
print "<b>".REF."</b>: ".$refdet;
print "</div>";
print "</td>";
print "</tr>";

// Afficher fournisseur
   if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['afficher_fournisseur']=='yes') {
      if(!empty($rowp['fournisseurs_link'])) {
       print "<tr>
              <td width='100%' valign='bottom' align='left'>
              <b>".FOURNISSEUR."</b>: <a href='".$rowp['fournisseurs_link']."' target='_blank'>".$rowp['fournisseurs_company']."</a>
              </td>
              </tr>";
      }
      else {
       print "<tr>
              <td colspan='2' width='100%' valign='bottom' align='left'>
              <b>".FOURNISSEUR."</b>: ".$rowp['fournisseurs_company']."
              </td>
              </tr>";
      }
   }

// Afficher garantie
      if(!empty($rowp['products_garantie_'.$_SESSION['lang']])) {
         print "<tr>";
         print "<td colspan='2' width='100%' valign='bottom' align='left'><b>".GARANTIE."</b>: ".$rowp['products_garantie_'.$_SESSION['lang']]."</td>";
         print "</tr>";
      }

// Afficher disponibilite
        if($rowp['products_qt']>0) {
            $qty = $openLeg."<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."' align='absmiddle'></a>";
        }
        else {
            if($actRes=="oui") {
               $qty2 = ARTICLE_EN_COMMANDE;
               $qty = $openLeg."<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."' align='absmiddle'></a>";
            } 
            else {
               $qty = $openLeg."<img src='../im/stockno.gif' border='0' title='".NOT_IN_STOCK."' alt='".NOT_IN_STOCK."' align='absmiddle'></a>";
            }
        }
// Afficher product download
        if($rowp['products_download'] == "yes") {
            $prodDown = $openLeg."&nbsp;<img src='../im/download.gif' border='0' title='".ARTICLE_TELE."' alt='".ARTICLE_TELE."' align='absmiddle'></a>";
        } else {
            $prodDown = "";
        }
// No Stock
        if($rowp['products_forsale'] == "no") {
            $prodDown = $openLeg."&nbsp;<img src='../im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."' align='absmiddle'></a>";
            $qty="";
        }
        
    print "<tr><td valign='middle'>";
    
	print "<div id='display_stock_bouton'>";
	print "<b>".DISPO."</b>: ".$qty.$prodDown;
	print "</div>";

// Envoyer à un ami

$ww = str_replace("&","|",$url_id10);
$ww = str_replace("description","descXXX",$ww);
        print "</td>";
        print "<td align='right'>";
        print "<img src='../im/zzz_noir.gif' width='5' height='5'>&nbsp;";
        print "<a href='javascript:void(0);' onClick=\"window.open('../sendToFriend.php?fromUrl=".$ww."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=420,width=340,toolbar=no,scrollbars=no,resizable=yes');\">";
        print "".ENVOYER_A_UN_AMI."";
        print "</a>";
        print "<br><img src='../im/zzz.gif' width='130' height='1'>";
        print "</td>";
        print "</tr><tr>";



//------------------------
// Afficher prix dégressif
//------------------------
$prods2Z = mysql_query("SELECT * FROM discount_on_quantity WHERE discount_qt_prod_id = '".$_GET['id']."' ORDER BY discount_qt_qt ASC") or die (mysql_error());
if(mysql_num_rows($prods2Z) > 0) {
	$c="TDTableListLine2";
	print "<td colspan='2'>";
	print "<div id='displayPrixDegressif'>";
	print "<hr width='".$tt."'>";
	print "<div align='left' class='PromoFont'><b>".PRODUIT_A_PRIX_DEGRESSIF."</b></div>";
	print "<div align='left'><img src='../im/zzz.gif' width='1' height='5' border='0'></div>";
	print "<table w/idth='100%' border='0' cellpadding='3' cellspacing='1' align='left' class='TABLE1'><tr height='30'>";
	print "<td align='center'><b>".QUANTITY."</b></td>";
	print "<td align='center'><b>".REMISE."</b></td>";
	print "<td align='center'><b>".PRIX__UNITAIRE."</b></td>";
	while($prods2Result = mysql_fetch_array($prods2Z)) {
		if($c=="TDTableListLine1") {$c="TDTableListLine2"; } else {$c="TDTableListLine1";}
		$toto = $prods2Result['discount_qt_prod_id'];
			print "</tr><tr class='".$c."'>";
			print "<td align='center'>";
			print "<img src='../im/sup.png' border='0'>".$prods2Result['discount_qt_qt'];
			print "</td>";
			print "<td align='center'>";
			$s = ($prods2Result['discount_qt_value']=="euro" AND $prods2Result['discount_qt_discount']>1)? "s" : "";
			print "-".$prods2Result['discount_qt_discount']."".$prods2Result['discount_qt_value'].$s;
			print "</td>";
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
	
				print $price_degressif." ".$symbolDevise;
			print "</td>";
	}
	print "</tr></table>";
	print "</div>";
	print "</td>";
	print "</tr>";
}



		print "<td colspan='2'>";
        
        
        if(isset($qty2) AND $qty2 == ARTICLE_EN_COMMANDE AND ((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowp['products_forsale']=="yes")) {
            // print "<br>".IN_ORDER_MESSAGE;
            // Réservation d'un article en commande
            if($actRes=="oui") {
                 print "<input type='hidden' value='".$rowp['products_id']."' name='id'>
                        <input type='hidden' value='".$rowp['products_ref']."' name='ref'>
                        <input type='hidden' value='".$rowp['products_name_'.$_SESSION['lang']]."' name='name'>
                        <input type='hidden' value='".$rowp['products_tax']."' name='productTax'>
                        <input type='hidden' value='".$rowp['products_deee']."' name='deeee'>
                        <input type='hidden' value='reserve' name='statut'>";
				 
				 print "<div id='display_cart'>";
                 print "<table width='100%' border='0' cellspacing='2' cellpadding='0' class='TABLE1'><tr>";
                       if($isThere=="no") {
                          print "<td align='center'>";
                          print BUY_NOW;
                          print "</td>";
                          print "</tr><tr><td align='center'><input type='text' size='3' maxlength='3' name='amount' value='1'>";
                          print "&nbsp;<input style='BACKGROUND: none; border:0px' type='image' src='../im/cart_add.png' title='".AJOUTER_AU_CADDIE."' alt='".AJOUTER_AU_CADDIE."' align='absmiddle'></td>";
                       }
                       else {
                             if($rowp['products_options'] == "no") {
                                print "<td align='center'>";
                                print BUYED1;
                                print "</td>";
                                print "</tr><tr><td align='center'><b>".RETIRER_DU_CADDIE."</b><br><a href='../add.php?amount=0&statut=reserve&ref=".$rowp['products_ref']."&id=".$rowp['products_id']."&name=".$rowp['products_name_'.$_SESSION['lang']]."&productTax=".$rowp['products_tax']."&deee=".$rowp['products_deee']."'><img src='../im/cart_rem.png' border='0' title='".RETIRER_DU_CADDIE."' alt='".RETIRER_DU_CADDIE."'></a></td>";
                             }
                             else {
                                   print "<td align='center'>";
                                   print BUYED1;
                                   print "</td>";
                                   print "</tr><tr><td align='center'>";
                                   print AUTRES_OPTIONS."<br>";
                                   print "<input type='text' size='3' maxlength='3' name='amount' value='1'>";
                                   print "&nbsp;<input style='BACKGROUND: none; border:0px' type='image' src='../im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' align='absmiddle'>";
                                   print "</td>";
                             }
                       }
                 print "</tr></table>";
                 print "</div>";
            }
        }
       print "</td>";

if($rowp['products_options'] == 'yes' AND $rowp['products_forsale']=="yes") {
// Afficher ligne + envoyer à un ami
       print "<tr>";
	   print "<td colspan='2'>";
       print "<hr width='".$tt."'>";
       print "</td></tr>";

// Afficher titre options
       print "<tr><td colspan='2'>";
	   print "<div class='PromoFont'><b>".maj(PRODUIT_OPTIONS)."</b>:</div>";
	   print "<div align='left'><img src='../im/zzz.gif' width='1' height='8' border='0'></div>";

// Mettre toutes les combinaisons+stock dans un array
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

// Afficher Stock options
// ----------------------
?>
<script type="text/javascript"><!--
function display_stock(x,variationZ) {
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

	for (var i=1; i<=x; i++) {
		valueZ = document.getElementById(i).value;
		option = valueZ.split('/');
		optionZ = option[0].split(',');
		if(message=='') message = optionZ[0]; else message = message + ' | ' + optionZ[0];
		price = price + parseFloat(option[1]);
	}
	
	priceFinal = price + boutikPrice;

	var explodeVariation = variation.split('!*!');
	
	for(var i=0; i<explodeVariation.length; i++) {
		result = explodeVariation[i].split(':>');
		result2 = result[1].split(',');
		if(result[0]==message) {
			var ref = result2[2];
			// Reference
			if(ref!='') {
				afficher_ref(ref);
			}
			if(result2[0]==0) {
				message = "<center>";
				message = message + result[0];
				message = message + "<br><img src='../im/zzz.gif' width='1' height='7'><br>";
				message = message + "<span style='font-size:12px; color:red'><?php print DECLINAISON_EPUISEE;?></span>";
				message = message + "</center>";
				if(activeEcom==1 || sessionAccount==1) display_cart.style.display = 'none';
				display_stock_bouton.style.display = 'none';
				displayPrix.style.display = 'block';
				if(prixDegressifTable!=0) displayPrixDegressif.style.display = 'none';
				
				if(actRes == 'oui') {
					message = "<center>";
					message = message + result[0];
					message = message + "<br><img src='../im/zzz.gif' width='1' height='7'><br>";
					message = message + "<span style='font-size:12px; color:#666666; padding:2px; border:1px #000000 solid; background:#FFFFFF'><?php print EN_COMMANDE;?></span>";
					message = message + "<br><img src='../im/zzz.gif' width='1' height='7'><br>";
					message = message + "- <?php print EXPEDI;?> <?php print ENTRE;?> <?php print $rowp['products_delay_1b'];?> <?php print ET;?> <?php print $rowp['products_delay_2b'];?> <?php print JOURS_OUVRES;?> -";
					if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<br><img src='../im/zzz.gif' width='1' height='5'><br>";
					if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<span style='font-size:11px;'><?php print strtoupper(PRIX);?>: " + priceFinal.toFixed(2) + " " + symbolDevise + "</span>";
					message = message + "</center>";
					if(activeEcom==1 || sessionAccount==1) display_cart.style.display = 'block';
					display_stock_bouton.style.display = 'block';
					displayPrix.style.display = 'block';
					if(prixDegressifTable!=0) displayPrixDegressif.style.display = 'block';
				}
			}
			else {
				message = "<center>";
				message = message + result[0];
				message = message + "<br><img src='../im/zzz.gif' width='1' height='3'><br>";
				message = message + "<img src='../im/lang<?php print $_SESSION['lang'];?>/stockok.png' title='<?php print EN_STOCK;?>' alt='<?php print EN_STOCK;?>'>";
				if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<br><img src='../im/zzz.gif' width='1' height='3'><br>";
				if(price!=0 && (displayPriceInShop==1 || sessionAccount==1)) message = message + "<span style='font-size:11px;'><?php print strtoupper(PRIX);?>: " + priceFinal.toFixed(2) + " " + symbolDevise + "</span><br>";
				message = message + "</center>";
				if(activeEcom==1 || sessionAccount==1) display_cart.style.display = 'block';
				display_stock_bouton.style.display = 'block';
				displayPrix.style.display = 'block';
				if(prixDegressifTable!=0) displayPrixDegressif.style.display = 'block';
			}
			
			if(result2[1]=='no') {
				message = "<center>";
				message = message + result[0];
				message = message + "<br><img src='../im/zzz.gif' width='1' height='3'><br>";
				message = message + "<span style='font-size:12px; color:red'><?php print str_replace(". ", "<br><img src='../im/zzz.gif' width='1' height='3'><br>", DECLINAISON_NON_REPERTORIEE);?></span>";
				message = message + "</center>";
				if(activeEcom==1 || sessionAccount==1) display_cart.style.display = 'none';
				display_stock_bouton.style.display = 'none';
				displayPrix.style.display = 'none';
				if(prixDegressifTable!=0) displayPrixDegressif.style.display = 'none';
			}
		}
	}

	// Écrase le resultat précédent
    var div=document.getElementById("passage");
    while (div.childNodes[0]) {
      div.removeChild(div.childNodes[0]);
    }
    // Afficher resultat message
	var div = document.createElement('div');
	div.style.backgroundColor = '#F1F1F1';
	div.style.padding = '7px';
	div.style.color = '#666666';
	div.style.fontSize = '10px';
	div.style.fontWeight = 'bold';
	div.style.border = '2px';
	div.style.borderColor = '#CCCCCC';
	div.style.borderStyle = 'solid';
    div.innerHTML = message;
    var passagesortie = document.getElementById("passage");
	passagesortie.appendChild(div);
}
--></script>

<div id='passage'></div>

<?php
	print "</td></tr>";

//-----------------
// Afficher options
// ----------------

// afficher les champs d'options
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
			// Afficher Option name
			print "<div align='left'><b>&bull;&nbsp;".$keys[$x-1]."</b>:</div>";
			print "<div align='left'><img src='../im/zzz.gif' width='1' height='3' border='0'></div>";
			// Afficher option note
			if(!empty($noteOpt[$x-1])) {
				print "<div align='left'>".$noteOpt[$x-1]."</div>";
				print "<div align='left'><img src='../im/zzz.gif' width='1' height='5' border='0'></div>";
			}
			// Afficher options
			if(isset($passVariationsToJs) AND count($passVariationsToJs)>0) {
				print "<select name='option[".$x."]' id=".$x." onChange=display_stock(".count($keys).");>";
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
					if(!empty($optionFinal[1]) AND $optionFinal[1]!=='+0.00') $euro = " | ".$optionFinal[1].$symbolDevise; else $euro = "";
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
			print "</td></tr>";
		}
}

// Afficher note sur options
		if(!empty($rowp['products_option_note_'.$_SESSION['lang']]) AND $rowp['products_options'] == 'yes' AND $rowp['products_forsale']=="yes") {
       		print "<tr><td colspan='2'>".$rowp['products_option_note_'.$_SESSION['lang']]."</td></tr>";
		}

// Afficher tableau stock options
//-------------------------------
if(isset($variations) AND count($variations)>0) {
	// Set variable
	$color="TDTableListLine2";
	$i=-1;
	$uu = "<table w/idth='100%' border='0' cellpadding='3' cellspacing='1' align='left' class='TABLEPaymentProcessSelected'><tr height='20'>";
	$uu.= "<td align='center'><b>".PRODUIT_OPTIONS."</b></td>";
	$uu.= "<td align='center' width='60'><b>Stock</b></td>";
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
			// Stock
			if($explodeValue[0]>0) $stock = "<img src='../im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."'>";
			if($explodeValue[0]<=0 AND $actRes=='oui') $stock = "<img src='../im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."'>";
			if($explodeValue[0]<=0 AND $actRes=='non') $stock = "<img src='../im/stockin.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'>";
			if($explodeValue[1]=='no') {$stock = "<img src='../im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'>"; $key="<s>".$key."</s>";}
			// Expédition
			if($explodeValue[0]>0) $exped = "<img src='../im/bull_green.gif' border='0' title='".ENTRE." ".$rowp['products_delay_1']." ".ET." ".$rowp['products_delay_2']." ".JOURS_OUVRES."'>";
			if($explodeValue[0]<=0 AND $actRes=='oui') $exped = "<img src='../im/bull_blue.gif' border='0' title='".ENTRE." ".$rowp['products_delay_1b']." ".ET." ".$rowp['products_delay_2b']." ".JOURS_OUVRES."'>";
			if($explodeValue[0]<=0 AND $actRes=='non') $exped = "<img src='../im/bull_red.gif' border='0' title='".ITEMS_OUT_OF_STOCK."'>";
			if($explodeValue[1]=='no') {$exped = "<img src='../im/bull_red.gif' border='0' title='".ITEMS_OUT_OF_STOCK."'>"; $key="<strike>".$key."</strike>";}
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
	$uu.= "<div><img src='../im/zzz.gif' width='1' height='1'></div>";
	$uu.= "</td>";
	$uu.= "</tr></table>";
	
	// Afficher tableau
	print "<tr>";
	print "<td>";
	// javascript activé
	print "<div align='left'><img src='../im/zzz.gif' width='1' height='5' border='0'></div>";
	print "<div align='left' class='PromoFont'>";
	print "<a id='click_div' href='javascript:void(0)'' onClick=\"deroul_div('displayTable')\"><img src='../im/stock.png' border='0' title='".VOIR_STOCK."' alt='".VOIR_STOCK."' align='absmiddle'>&nbsp;".VOIR_STOCK."</a>";
	print "</div>";
	print "<div align='left'><img src='../im/zzz.gif' width='1' height='5' border='0'></div>";
	print "<div class='cache_div' id='displayTable'>".$uu."</div>";
	// javascript désactivé
	print "<noscript>";
	print $uu;
	print "</noscript>";
	print "</td>";
	print "</tr>"; 
}

// Afficher ligne
       print "<tr><td colspan='2'>";
       print "<hr width='".$tt."'>";
        print "</td>
              </tr>";
// Afficher description titre
       print "<tr><td colspan='2'><b>".DESCRIPTIONMAJ."</b> :</td>";
        print "</tr>";

// Afficher description
        print "<tr>";
        print "<td colspan='2' valign='top'><div align='justify'>".$rowp['products_desc_'.$_SESSION['lang']]."</div></td></tr>";

// Afficher note sur description
        if(!empty($rowp['products_note_'.$_SESSION['lang']])) {
        print "<tr><td colspan='2'><div align='left'>".$rowp['products_note_'.$_SESSION['lang']]."</div>";
        print "</td></tr>";
        }
//---------------------------
// Afficher produits affiliés
//---------------------------
if($displayRelated == "oui" AND !empty($rowp['products_related'])) {
   
       print "<tr><td colspan='2'><hr width='".$tt."'></td></tr>";
       print "<tr><td colspan='2'><b>".PRODUITS_AFFILIES."</b> :</td></tr>";
       print "<tr><td colspan='2'>";
       if($displayOutOfStock=="non") {$addToQuery = " AND products_qt>'0'";} else {$addToQuery="";}
           
       $expRel = explode("|", $rowp['products_related']);
       $expRelNb = count($expRel)-1;
       for($i=0; $i<=$expRelNb; $i++) {
          $queryRel = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_image, categories_id
                                   FROM products
                                   WHERE products_id = '".$expRel[$i]."'
                                   ".$addToQuery."
                                   AND products_visible = 'yes'
                                   AND products_forsale = 'yes'
                                   ORDER BY products_name_".$_SESSION['lang']."
                                   ");
      if(mysql_num_rows($queryRel)>0) {
           $rowRel = mysql_fetch_array($queryRel);

           print "<table border='0' cellspacing='0' cellpadding='2'><tr>";
		   if($displayRelatedImage=="oui") {
  		    if(!empty($rowRel['products_image'])) {
  		       if(substr($rowRel['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
  		       $images_widthIpos = $ImageSizeDescRelated+10;
              $yoZ1 = @getimagesize($dirr.$rowRel['products_image']);
              if(!$yoZ1) $rowRel['products_image']="../im/zzz_gris.gif";
             $image_resize_related = resizeImageMini("".$dirr.$rowRel['products_image'],$ImageSizeDescRelated,$images_widthIpos);
      		   print "<td width='".$images_widthIpos."'>";
               print "<a href='beschrijving.php?id=".$rowRel['products_id']."'>";
               
                   // Is server GD open ? 
                   if($gdOpen == "non") {
                            print "<img border='0' src='".$dirr.$rowRel['products_image']."' width='".$image_resize_related[0]."' height='".$image_resize_related[1]."' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";
                   }
                   else {
                            $infoImage = infoImageFunction($dirr.$rowRel['products_image'],$images_widthIpos,$ImageSizeDescRelated);
                            print "<img src='../mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowRel['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";                  
                   }
               print "</a>";
               print "</td>";
  				}
  				else {
               print "<td>&nbsp;</td>";
            }
		   }
		   else {
             print "<td>&nbsp;</td>";
         }

		   print "<td>&nbsp;<a href='beschrijving.php?id=".$rowRel['products_id']."'>".$rowRel['products_name_'.$_SESSION['lang']]."</a></td>"; 
		   print "</tr></table>";
		}
      }
      print "</td></tr>";
}
// Afficher article ajoute ....
       $dt = explode(" ",date($rowp['products_date_added']));
       $dt = explode("-",$dt[0]);
       print "<tr align='center'>";
       if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $rowp['products_forsale']=="yes") {
         print "<td colspan='2' class='FontGris'>".$EnPromo."</td>";
       } 
       else {
         print "<td colspan='2'><img src='zzz.gif' width='1' height='1'></td>";
       }
      print "</tr>";
      print "</table>";

// Afficher bouton retour
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='5' align='center'><tr>";
print "<td>
        <div align = 'center'>
         <a href='".$refererUrl."'><img src='../im/lang".$_SESSION['lang']."/articles.gif' border='0'></a>
        </div>
      </td>";
print "<tr>";
print "<td colspan=2>".$caddie_yes."</td>";
print "</tr></table>";

 print "</form>";
// *************** //
// *** END FROM ***//
// *************** //
?>
            </table>
            
            
<?php 
}
else {
print "<p align='center' class='fontrouge'><b>".DESOLE."</b></p>";
}
?>
</td>

</tr>
</table>

</td>
</tr>
</table>
</td>
</tr></table>
<?php
// Javascript Alert stock < add into cart
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==1 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'ATTENTION :\nLa quantité saisie est supérieure au stock disponible.\nVeuillez nous contacter si vous souhaitez valider cette quantité.';
alert (messagePerso);
//-->
</script>
<?php
}


// Javascript Alert weight max < add into cart
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==2 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'ATTENTION :\nAucun tarif de livraison est fixé pour cet article, nous consulter pour tarif de livraison.';
alert (messagePerso);
//-->
</script>
<?php
}


// Javascript Alert declinaison non dispo ou > amount
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==3 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'ATTENTION :\nCette déclinaison est épuisée. Faites une autre sélection.';
alert (messagePerso);
//-->
</script>
<?php
}
if(isset($_SESSION['stockInf'])) unset($_SESSION['stockInf']);
?>
</body>
</html>
