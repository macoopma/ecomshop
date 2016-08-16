<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$color = "#dddddd";


if(isset($_POST['action']) AND $_POST['action']=="dede") {
    $count = $_POST['num']-1;
    $message = "";

    for($i=0; $i<=$count-1; $i++) {
    

    $queryPromo = mysql_query("SELECT products_id, products_price, products_name_".$_SESSION['lang']." FROM products WHERE products_id = '".$_POST['att_id'][$i]."'");
    $rowPromo = mysql_fetch_array($queryPromo);
    if($rowPromo['products_price']!==$_POST['att_prix'][$i]) {
       $queryPromo2 = mysql_query("SELECT specials_id FROM specials WHERE products_id= '".$_POST['att_id'][$i]."'");
       if(mysql_num_rows($queryPromo2) > 0) {
             $rowPromo2Result = mysql_fetch_array($queryPromo2);
             $checkPromo = "notok";
             $message.= ATTENTION;
             $message.= "- <a href='promoties_wijzigen_invullen.php?id=".$rowPromo2Result['specials_id']."'>".$rowPromo['products_name_'.$_SESSION['lang']]."</a><br><br>";
       }
       else {
          $checkPromo = "ok";
       }
    }
    else {
       $checkPromo = "ok";
    }
     

    if(isset($_POST['att_nom1'][$i])) {
        $_POST['att_nom1'][$i] = str_replace("'","&#146;",$_POST['att_nom1'][$i]);
        $_POST['att_nom1'][$i] = str_replace("+","&#134;",$_POST['att_nom1'][$i]);
        $_POST['att_nom1'][$i] = str_replace(",","",$_POST['att_nom1'][$i]);
        $products_name_1 = "products_name_1 = '".$_POST['att_nom1'][$i]."',";
    } else {$products_name_1="";}
    
    if(isset($_POST['att_nom2'][$i])) {
        $_POST['att_nom2'][$i] = str_replace("'","&#146;",$_POST['att_nom2'][$i]);
        $_POST['att_nom2'][$i] = str_replace("+","&#134;",$_POST['att_nom2'][$i]);
        $_POST['att_nom2'][$i] = str_replace(",","",$_POST['att_nom2'][$i]);
        $products_name_2 = "products_name_2 = '".$_POST['att_nom2'][$i]."',";
    } else {$products_name_2="";}
    
    if(isset($_POST['att_nom3'][$i])) {
        $_POST['att_nom3'][$i] = str_replace("'","&#146;",$_POST['att_nom3'][$i]);
        $_POST['att_nom3'][$i] = str_replace("+","&#134;",$_POST['att_nom3'][$i]);
        $_POST['att_nom3'][$i] = str_replace(",","",$_POST['att_nom3'][$i]);
        $products_name_3 = "products_name_3 = '".$_POST['att_nom3'][$i]."',";
    } else {$products_name_3="";}
    
    if(isset($_POST['att_ref'][$i])) {
        $_POST['att_ref'][$i] = str_replace("+","&#134;",$_POST['att_ref'][$i]);
        $products_ref = "products_ref = '".$_POST['att_ref'][$i]."',";
    } else {$products_ref="";}
    
    if(isset($_POST['att_afficherFournisseur'][$i])) {$afficher_fournisseur = "afficher_fournisseur = '".$_POST['att_afficherFournisseur'][$i]."',";} else {$afficher_fournisseur="";}
    if(isset($_POST['att_afficherFabricant'][$i])) {$afficher_fabricant = "afficher_fabricant = '".$_POST['att_afficherFabricant'][$i]."',";} else {$afficher_fabricant="";}
    
    if(isset($_POST['att_description1'][$i])) {
        $_POST['att_description1'][$i] = str_replace("'","&#146;",$_POST['att_description1'][$i]);
        $products_desc_1 = "products_desc_1 = '".$_POST['att_description1'][$i]."',";
    } else {$products_desc_1="";}
    
    if(isset($_POST['att_description2'][$i])) {
        $_POST['att_description2'][$i] = str_replace("'","&#146;",$_POST['att_description2'][$i]);
        $products_desc_2 = "products_desc_2 = '".$_POST['att_description2'][$i]."',";
    } else {$products_desc_2="";}
    
    if(isset($_POST['att_description3'][$i])) {
        $_POST['att_description3'][$i] = str_replace("'","&#146;",$_POST['att_description3'][$i]);
        $products_desc_3 = "products_desc_3 = '".$_POST['att_description3'][$i]."',";
    } else {$products_desc_3="";}
    
    if(isset($_POST['att_category'][$i])) {$categories_id = "categories_id = '".$_POST['att_category'][$i]."',";} else {$categories_id="";}
    if(isset($_POST['att_visible'][$i])) {$products_visible = "products_visible = '".$_POST['att_visible'][$i]."',";} else {$products_visible="";}
    if(isset($_POST['att_garantie1'][$i])) {
        $_POST['att_garantie1'][$i] = str_replace("'","&#146;",$_POST['att_garantie1'][$i]);
        $products_garantie_1 = "products_garantie_1 = '".$_POST['att_garantie1'][$i]."',";
    } else {$products_garantie_1="";}
    
    if(isset($_POST['att_garantie2'][$i])) {
        $_POST['att_garantie2'][$i] = str_replace("'","&#146;",$_POST['att_garantie2'][$i]);
        $products_garantie_2 = "products_garantie_2 = '".$_POST['att_garantie2'][$i]."',";
    } else {$products_garantie_2="";}
    
    if(isset($_POST['att_garantie3'][$i])) {
        $_POST['att_garantie3'][$i] = str_replace("'","&#146;",$_POST['att_garantie3'][$i]);
        $products_garantie_3 = "products_garantie_3 = '".$_POST['att_garantie3'][$i]."',";
    } else {$products_garantie_3="";}
    
    if(isset($_POST['att_note1'][$i])) {
        $_POST['att_note1'][$i] = str_replace("'","&#146;",$_POST['att_note1'][$i]);
        $products_note_1 = "products_note_1 = '".$_POST['att_note1'][$i]."',";
    } else {$products_note_1="";}
    
    if(isset($_POST['att_note2'][$i])) {
        $_POST['att_note2'][$i] = str_replace("'","&#146;",$_POST['att_note2'][$i]);
        $products_note_2 = "products_note_2 = '".$_POST['att_note2'][$i]."',";
    } else {$products_note_2="";}
    
    if(isset($_POST['att_note3'][$i])) {
        $_POST['att_note3'][$i] = str_replace("'","&#146;",$_POST['att_note3'][$i]);
        $products_note_3 = "products_note_3 = '".$_POST['att_note3'][$i]."',";
    } else {$products_note_3="";}
    
    if(isset($_POST['att_prix'][$i])) {
        $_POST['att_prix'][$i] = str_replace(",",".",$_POST['att_prix'][$i]);
        $_POST['att_prix'][$i] = sprintf("%0.2f",$_POST['att_prix'][$i]);
        $products_price = "products_price = '".$_POST['att_prix'][$i]."',";
    } else {$products_price="";}
    
    if(isset($_POST['att_poids'][$i])) {
        $_POST['att_poids'][$i] = str_replace(",",".",$_POST['att_poids'][$i]);
        $_POST['att_poids'][$i] = sprintf("%0.2f",$_POST['att_poids'][$i]);
        $products_weight = "products_weight = '".$_POST['att_poids'][$i]."',";
    } else {$products_weight="";}
    
    if(isset($_POST['att_taxable'][$i])) {$products_taxable = "products_taxable = '".$_POST['att_taxable'][$i]."',";} else {$products_taxable="";}
    
    if(isset($_POST['att_taxe'][$i])) {
        $_POST['att_taxe'][$i] = str_replace(",",".",$_POST['att_taxe'][$i]);
        $_POST['att_taxe'][$i] = sprintf("%0.2f",$_POST['att_taxe'][$i]);
        $products_tax = "products_tax = '".$_POST['att_taxe'][$i]."',";
    } else {$products_tax="";}
    
    if(isset($_POST['att_delay_1'][$i])) {
    	$_POST['att_delay_1'][$i] = str_replace(",","",$_POST['att_delay_1'][$i]);
    	$_POST['att_delay_1'][$i] = str_replace(".","",$_POST['att_delay_1'][$i]);
    	if(!is_numeric($_POST['att_delay_1'][$i])) $_POST['att_delay_1'][$i] = "0";
		$products_delay_1 = "products_delay_1 = '".$_POST['att_delay_1'][$i]."',";
	}
	else {
		$products_delay_1="";
	}
    if(isset($_POST['att_delay_2'][$i])) {
    	$_POST['att_delay_2'][$i] = str_replace(",","",$_POST['att_delay_2'][$i]);
    	$_POST['att_delay_2'][$i] = str_replace(".","",$_POST['att_delay_2'][$i]);
    	if(!is_numeric($_POST['att_delay_2'][$i])) $_POST['att_delay_2'][$i] = "0";
		$products_delay_2 = "products_delay_2 = '".$_POST['att_delay_2'][$i]."',";
	}
	else {
		$products_delay_2="";
	}
    if(isset($_POST['att_delay_1a'][$i])) {
    	$_POST['att_delay_1a'][$i] = str_replace(",","",$_POST['att_delay_1a'][$i]);
    	$_POST['att_delay_1a'][$i] = str_replace(".","",$_POST['att_delay_1a'][$i]);
    	if(!is_numeric($_POST['att_delay_1a'][$i])) $_POST['att_delay_1a'][$i] = "0";
		$products_delay_1a = "products_delay_1a = '".$_POST['att_delay_1a'][$i]."',";
	}
	else {
		$products_delay_1a="";
	}
    if(isset($_POST['att_delay_2a'][$i])) {
    	$_POST['att_delay_2a'][$i] = str_replace(",","",$_POST['att_delay_2a'][$i]);
    	$_POST['att_delay_2a'][$i] = str_replace(".","",$_POST['att_delay_2a'][$i]);
    	if(!is_numeric($_POST['att_delay_2a'][$i])) $_POST['att_delay_2a'][$i] = "0";
		$products_delay_2a = "products_delay_2a = '".$_POST['att_delay_2a'][$i]."',";
	}
	else {
		$products_delay_2a="";
	}
    if(isset($_POST['att_delay_1b'][$i])) {
    	$_POST['att_delay_1b'][$i] = str_replace(",","",$_POST['att_delay_1b'][$i]);
    	$_POST['att_delay_1b'][$i] = str_replace(".","",$_POST['att_delay_1b'][$i]);
    	if(!is_numeric($_POST['att_delay_1b'][$i])) $_POST['att_delay_1b'][$i] = "0";
		$products_delay_1b = "products_delay_1b = '".$_POST['att_delay_1b'][$i]."',";
	}
	else {
		$products_delay_1b="";
	}
    if(isset($_POST['att_delay_2b'][$i])) {
    	$_POST['att_delay_2b'][$i] = str_replace(",","",$_POST['att_delay_2b'][$i]);
    	$_POST['att_delay_2b'][$i] = str_replace(".","",$_POST['att_delay_2b'][$i]);
    	if(!is_numeric($_POST['att_delay_2b'][$i])) $_POST['att_delay_2b'][$i] = "0";
		$products_delay_2b = "products_delay_2b = '".$_POST['att_delay_2b'][$i]."',";
	}
	else {
		$products_delay_2b="";
	}
	
    if(isset($_POST['att_dateAdded'][$i])) {$products_date_added = "products_date_added = '".$_POST['att_dateAdded'][$i]."',";} else {$products_date_added="";}
    if(isset($_POST['att_AfficherImage'][$i])) {$products_im = "products_im = '".$_POST['att_AfficherImage'][$i]."',";} else {$products_im="";}
    if(isset($_POST['att_image'][$i])) {$products_image = "products_image = '".$_POST['att_image'][$i]."',";} else {$products_image="";}
    if(isset($_POST['att_AfficherImage2'][$i])) {$products_image2 = "products_image2 = '".$_POST['att_AfficherImage2'][$i]."',";} else {$products_image2="";}
    if(isset($_POST['att_AfficherImage3'][$i])) {$products_image3 = "products_image3 = '".$_POST['att_AfficherImage3'][$i]."',";} else {$products_image3="";}
    if(isset($_POST['att_AfficherImage4'][$i])) {$products_image4 = "products_image4 = '".$_POST['att_AfficherImage4'][$i]."',";} else {$products_image4="";}
    if(isset($_POST['att_AfficherImage5'][$i])) {$products_image5 = "products_image5 = '".$_POST['att_AfficherImage5'][$i]."',";} else {$products_image5="";}
    if(isset($_POST['att_view'][$i])) {$products_viewed = "products_viewed = '".$_POST['att_view'][$i]."',";} else {$products_viewed="";}
    if(isset($_POST['att_added'][$i])) {$products_added = "products_added = '".$_POST['att_added'][$i]."',";} else {$products_added="";}
    if(isset($_POST['att_download'][$i])) {$products_download = "products_download = '".$_POST['att_download'][$i]."',";} else {$products_download="";}
    if(isset($_POST['att_url'][$i])) {$products_download_name = "products_download_name = '".$_POST['att_url'][$i]."',";} else {$products_download_name="";}
    if(isset($_POST['att_exclusive'][$i])) {$products_exclusive = "products_exclusive = '".$_POST['att_exclusive'][$i]."',";} else {$products_exclusive="";}
    if(isset($_POST['att_quantite'][$i])) {$products_qt = "products_qt = '".$_POST['att_quantite'][$i]."',";} else {$products_qt="";}
    if(isset($_POST['att_sold'][$i])) {$products_sold = "products_sold = '".$_POST['att_sold'][$i]."',";} else {$products_sold="";}
    if(isset($_POST['att_deee'][$i])) {$products_deee = "products_deee = '".$_POST['att_deee'][$i]."',";} else {$products_deee="";}
    if(isset($_POST['att_ean'][$i])) {$products_ean = "products_ean = '".$_POST['att_ean'][$i]."',";} else {$products_ean="";}
    if(isset($_POST['att_sup_shipping'][$i])) {$products_sup_shipping = "products_sup_shipping = '".$_POST['att_sup_shipping'][$i]."',";} else {$products_sup_shipping="";}
    if(isset($_POST['att_caddie_display'][$i])) {$products_caddie_display = "products_caddie_display = '".$_POST['att_caddie_display'][$i]."',";} else {$products_caddie_display="";}
    

        mysql_query("UPDATE products
                       SET
                       ".$products_name_1."
                       ".$products_name_2."
                       ".$products_name_3."
                       ".$products_ref."
                       ".$afficher_fournisseur."
                       ".$afficher_fabricant."
                       ".$products_desc_1."
                       ".$products_desc_2."
                       ".$products_desc_3."
                       ".$categories_id."
                       ".$products_visible."
                       ".$products_garantie_1."
                       ".$products_garantie_2."
                       ".$products_garantie_3."
                       ".$products_note_1."
                       ".$products_note_2."
                       ".$products_note_3."
                       ".$products_price."
                       ".$products_weight."
                       ".$products_taxable."
                       ".$products_tax."
                       ".$products_date_added."
                       ".$products_im."
                       ".$products_image."
                       ".$products_image2."
                       ".$products_image3."
                       ".$products_image4."
                       ".$products_image5."
                       ".$products_viewed."
                       ".$products_added."
                       ".$products_download."
                       ".$products_download_name."
                       ".$products_exclusive."
                       ".$products_qt."
                       ".$products_sold."
                       ".$products_deee."
                       ".$products_ean."
                       ".$products_sup_shipping."
                       ".$products_caddie_display."
                       ".$products_delay_1."
                       ".$products_delay_2."
                       ".$products_delay_1a."
                       ".$products_delay_2a."
                       ".$products_delay_1b."
                       ".$products_delay_2b."
                       products_id = '".$_POST['att_id'][$i]."'   
                       WHERE products_id = '".$_POST['att_id'][$i]."'");
    }
     if(isset($message) AND $message == '') print "<p align='center' class='fontrouge'><b>".A110."</b></p>";
}


if(isset($_POST['action']) AND $_POST['action']=="update" AND !isset($_POST['sel']) OR (isset($_POST['sel']) AND $_POST['sel']!=="nada")) {

    if(!empty($_POST['nom'])) {$nom = "AND products_name_1 LIKE '%".$_POST['nom']."%' OR products_name_2 LIKE '%".$_POST['nom']."%' OR products_name_3 LIKE '%".$_POST['nom']."%'";} else {$nom = "";}
    if($_POST['cat'] !== "no") {$cat = "AND categories_id = '".$_POST['cat']."'";} else {$cat = "";}
    if(!empty($_POST['ref'])) {$ref = "AND products_ref = '".$_POST['ref']."'";} else {$ref = "";}
    if($_POST['fournisseur'] !== "no") {$fournisseur = "AND fournisseurs_id = '".$_POST['fournisseur']."'";} else {$fournisseur = "";}
    if($_POST['fabricant'] !== "no") {$fabricant = "AND fabricant_id = '".$_POST['fabricant']."'";} else {$fabricant = "";}
    if((!empty($_POST['prix1']) OR $_POST['prix1']==0) AND !empty($_POST['prix2'])) {$prix = "AND products_price>='".$_POST['prix1']."' AND products_price<='".$_POST['prix2']."'";} else {$prix = "";}
    if(!empty($_POST['taxe'])) {$taxe = "AND products_tax = '".$_POST['taxe']."'";} else {$taxe = "";}
    if((!empty($_POST['poid1']) OR $_POST['poid1']==0) AND !empty($_POST['poid2'])) {$poids = "AND products_weight>=".$_POST['poid1']." AND products_weight<=".$_POST['poid2']."";} else {$poids = "";}
    if($_POST['visible'] !== "") {$visible = "AND products_visible = '".$_POST['visible']."'";} else {$visible = "";}
    if((!empty($_POST['quantite1']) OR $_POST['quantite1']==0) AND !empty($_POST['quantite2'])) {$quantite = "AND products_qt>='".$_POST['quantite1']."' AND products_qt<='".$_POST['quantite2']."'";} else {$quantite = "";}
    if($_POST['download'] !== "") {$download = "AND products_download = '".$_POST['download']."'";} else {$download = "";}
    if($_POST['exclusive'] !== "") {$exclusive = "AND products_exclusive = '".$_POST['exclusive']."'";} else {$exclusive = "";}
    if($_POST['deee'] !== "") {
        if($_POST['deee']=="yes") {
            $deee = "AND products_deee > 0";
        } 
        else {
            $deee = "AND products_deee = 0";
        }
    } 
    else {
        $deee = "";
    }
    if($_POST['sup_shipping'] !== "") {
        if($_POST['sup_shipping']=="yes") {
            $sup_shipping = "AND products_sup_shipping > 0";
        } 
        else {
            $sup_shipping = "AND products_sup_shipping = 0";
        }
    } 
    else {
        $sup_shipping = "";
    }
    if($_POST['caddie_display'] !== "") {$caddie_display = "AND products_caddie_display = '".$_POST['caddie_display']."'";} else {$caddie_display = "";}
    if(!empty($_POST['ean'])) {$ean = "AND products_ean = '".$_POST['ean']."'";} else {$ean = "";}
    if(!empty($_POST['sold']) AND $_POST['sold'] == "yes") {$sold = "AND products_sold > 0";} else {$sold = "AND products_sold = 0";}
    if(empty($_POST['sold'])) {$sold = "";}
    
    $sql = mysql_query("SELECT * FROM products 
                        WHERE products_ref != 'GC100'
                        ".$nom."
                        ".$ref."
                        ".$cat."
                        ".$fournisseur."
                        ".$fabricant."
                        ".$prix."
                        ".$taxe."
                        ".$poids."
                        ".$visible."
                        ".$quantite."
                        ".$download."
                        ".$exclusive."
                        ".$sold."
                        ".$deee."
                        ".$sup_shipping."
                        ".$caddie_display."
                        ");
    $sqlNum = mysql_num_rows($sql);
    $c="";
    if($sqlNum > 0 ) {
        print "<form action='artikel_zoeken.php' method='POST'>";
        print "<table border='0' align='center' cellpadding='3' cellspacing='0' class='TABLE'>";
        print "<tr height='30' align='center' bgcolor='#FFFFFF' class='FONTGRAS'>";
print "<td valign=top align=left valign=top>#</td>";
print "<td>&nbsp;</td>";
print "<td>&nbsp;</td>";
print "<td valign=top align=left valign=top>ID</td>";
if(isset($_POST['select']['categories_id'])) print "<td valign=top align=left>".A111."</td>";
if(isset($_POST['select']['afficher_fournisseur'])) print "<td valign=top align=left>".A112."</td>";
if(isset($_POST['select']['afficher_fabricant'])) print "<td valign=top align=left>".A113."</td>";
if(isset($_POST['select']['products_name_1'])) print "<td valign=top align=left>".A114."</td>";
if(isset($_POST['select']['products_desc_1'])) print "<td valign=top align=left>".A115."</td>";
if(isset($_POST['select']['products_garantie_1'])) print "<td valign=top align=left>".A116."</td>";
if(isset($_POST['select']['products_price'])) print "<td valign=top align=left>".A117."<font color=red><br>(promo)</font></td>";
if(isset($_POST['select']['products_weight'])) print "<td valign=top align=left>".A118."<br>(Gr.)</td>";
if(isset($_POST['select']['products_ref'])) print "<td valign=top align=left>".A119."</td>";
if(isset($_POST['select']['products_image'])) print "<td valign=top align=left>".A120."</td>";
if(isset($_POST['select']['products_im'])) print "<td valign=top align=left>".A121."</td>";
if(isset($_POST['select']['products_image2'])) print "<td valign=top align=left>".A122."</td>";
if(isset($_POST['select']['products_note_1'])) print "<td valign=top align=left>".A123."</td>";
if(isset($_POST['select']['products_visible'])) print "<td valign=top align=left>".A124."</td>";
if(isset($_POST['select']['products_taxable'])) print "<td valign=top align=left>".A125."</td>";
if(isset($_POST['select']['products_tax'])) print "<td valign=top align=left>".A126."<br>%</td>";
if(isset($_POST['select']['products_date_added'])) print "<td valign=top align=left>".A127."</td>";
if(isset($_POST['select']['products_viewed'])) print "<td valign=top align=left>".A128."</td>";
if(isset($_POST['select']['products_added'])) print "<td valign=top align=left>".A129."</td>";
if(isset($_POST['select']['products_qt'])) print "<td valign=top align=left>".A130."</td>";
if(isset($_POST['select']['products_download'])) print "<td valign=top align=left>".A131."</td>";
if(isset($_POST['select']['products_download_name'])) print "<td valign=top align=left>".A132."</td>";
if(isset($_POST['select']['products_exclusive'])) print "<td valign=top align=left>".A133."</td>";
if(isset($_POST['select']['products_sold'])) print "<td valign=top align=left>".VENDU."</td>";
if(isset($_POST['select']['products_deee'])) print "<td valign=top align=left>Eco-part</td>";
if(isset($_POST['select']['products_ean'])) print "<td valign=top align=left>EAN</td>";
if(isset($_POST['select']['products_sup_shipping'])) print "<td valign=top align=left>".SUPPLEMENT1."</td>";
if(isset($_POST['select']['products_caddie_display'])) print "<td valign=top align=left>".DISPLAY_CADDIE."</td>";
if(isset($_POST['select']['products_delay'])) print "<td valign=top align=left>".str_replace(" "," ",DELAI_DE_LIVRAISON)."<div><img src='im/zzz_noir.gif' width='270' height='1'></div></td>";
        print "</tr><tr>";
        
        $num=1;
        while ($sqlReq = mysql_fetch_array($sql)) {
        
        if($c=="#F1F1F1") $c = "#dddddd"; else $c = "#F1F1F1";
           print "<tr bgcolor='".$c."'>";
           $request = mysql_query("SELECT specials_new_price, specials_last_day FROM specials WHERE products_id = '".$sqlReq['products_id']."'");
           $promoNum = mysql_num_rows($request);
           
print "<input type='hidden' name='att_id[]' value='".$sqlReq['products_id']."'>";
print "<td align='left' valign='top'>".$num++.".</td>";
print "<td align='left' valign='top'><a href='artikel_wijzigen_details.php?id=".$sqlReq['products_id']."' title='".ALLER_A_LA_FICHE."' style='text-decoration:none; background:none'><img src='im/voir.gif' border='0'></a></td>";
print "<td align='left' valign='top'>";
if($promoNum==0) print "<a href='promoties_invullen.php?id=".$sqlReq['products_id']."?' title='".AJOUTER_PROMO."' style='text-decoration:none; background:none'><img src='im/promoPlus.gif' border='0'></a>"; else print "--";
print "</td>";

print "<td align='left' valign='top'>".$sqlReq['products_id']."</td>";
if(isset($_POST['select']['categories_id'])) {
        $query2 = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                       FROM categories
                       WHERE categories_noeud = 'L'
                       ORDER BY categories_name_".$_SESSION['lang']."");
        print "<td align='left' valign='top'>";
        print "<select name='att_category[]'>";
        while($a_row = mysql_fetch_array($query2)) {
            if($a_row['categories_id'] == $sqlReq['categories_id']) {$sel="selected";} else {$sel="";}
           print "<option value='".$a_row['categories_id']."' ".$sel.">[".$a_row['categories_id']."] - ".$a_row['categories_name_'.$_SESSION['lang']]."";
           print "</option>";
        }
        print "</select>";
        print "</td>";
}
if(isset($_POST['select']['afficher_fournisseur'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['afficher_fournisseur']=="no") {$selFournisseur="selected";} else {$selFournisseur="";}
            print "<select name='att_afficherFournisseur[]'>
                    <option value='yes' ".$selFournisseur.">".A140."</option>
                    <option value='no' ".$selFournisseur.">".A141."</option>";
            print "</td>";
}
if(isset($_POST['select']['afficher_fabricant'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['afficher_fabricant']=="no") {$selFabricant="selected";} else {$selFabricant="";}
            print "<select name='att_afficherFabricant[]'>
                    <option value='yes' ".$selFabricant.">".A140."</option>
                    <option value='no' ".$selFabricant.">".A141."</option>";
            print "</td>";
}
if(isset($_POST['select']['products_name_1'])) {
        print "<td align='left' valign='top'>
                    <input type='text' class='vullen' value='".$sqlReq['products_name_3']."' name='att_nom1[]' size='40'><br>
                    <input type='text' class='vullen' value='".$sqlReq['products_name_1']."' name='att_nom2[]' size='40'><br>
                    <input type='text' class='vullen' value='".$sqlReq['products_name_2']."' name='att_nom3[]' size='40'>
              </td>";
}
if(isset($_POST['select']['products_desc_1'])) {
        print "<td align='left' valign='top'>
                    <textarea name='att_description1[]' rows='4' cols='40'>".$sqlReq['products_desc_3']."</textarea><br>
                    <textarea name='att_description2[]' rows='4' cols='40'>".$sqlReq['products_desc_1']."</textarea><br>
                    <textarea name='att_description3[]' rows='4' cols='40'>".$sqlReq['products_desc_2']."</textarea>
               </td>";
}
if(isset($_POST['select']['products_garantie_1'])) {
        print "<td align='left' valign='top'>
                    <textarea name='att_garantie1[]' rows='4' cols='40'>".$sqlReq['products_garantie_3']."</textarea><br>
                    <textarea name='att_garantie2[]' rows='4' cols='40'>".$sqlReq['products_garantie_1']."</textarea><br>
                    <textarea name='att_garantie3[]' rows='4' cols='40'>".$sqlReq['products_garantie_2']."</textarea>
               </td>";
}
if(isset($_POST['select']['products_price'])) {
        if($promoNum > 0) {
                $result = mysql_fetch_array($request);
                $dateMaxCheck = explode("-",$result['specials_last_day']);
                $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
                $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                if($dateMax > $today) {$prod= "<span class='fontrouge'>(".$result['specials_new_price']." ".$symbolDevise.")</span>";} else {$prod="";}
        }
        else {
                $prod = "";
        }
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_price']."' name='att_prix[]' size='10'><br>".$prod."</td>";
}
if(isset($_POST['select']['products_weight'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_weight']."' name='att_poids[]' size='5'></td>";
}
if(isset($_POST['select']['products_ref'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_ref']."' name='att_ref[]' size='15'></td>";
}
if(isset($_POST['select']['products_image'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_image']."' name='att_image[]' size='15'></td>";
}
if(isset($_POST['select']['products_im'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['products_im']=="no") {$selImage="selected";} else {$selImage="";}
            print "<select name='att_AfficherImage[]'>
                    <option value='yes' ".$selImage.">".A140."</option>
                    <option value='no' ".$selImage.">".A141."</option>";
            print "</td>";
}
if(isset($_POST['select']['products_image2'])) {
        print "<td align='left' valign='top'>
                Mini 1<br><input type='text' class='vullen' value='".$sqlReq['products_image2']."' name='att_AfficherImage2[]' size='20'><br>
                Mini 2<br><input type='text' class='vullen' value='".$sqlReq['products_image3']."' name='att_AfficherImage3[]' size='20'><br>
                Mini 3<br><input type='text' class='vullen' value='".$sqlReq['products_image4']."' name='att_AfficherImage4[]' size='20'><br>
                Mini 4<br><input type='text' class='vullen' value='".$sqlReq['products_image5']."' name='att_AfficherImage5[]' size='20'>
              </td>";
}
if(isset($_POST['select']['products_note_1'])) {
        print "<td align='left' valign='top'>
                    <textarea name='att_note1[]' rows='4' cols='40'>".$sqlReq['products_note_3']."</textarea><br>
                    <textarea name='att_note2[]' rows='4' cols='40'>".$sqlReq['products_note_1']."</textarea><br>
                    <textarea name='att_note3[]' rows='4' cols='40'>".$sqlReq['products_note_2']."</textarea>
               </td>";
}
if(isset($_POST['select']['products_visible'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['products_visible']=="no") {$selVisible="selected";} else {$selVisible="";}
            print "<select name='att_visible[]'>
                    <option value='yes' ".$selVisible.">".A140."</option>
                    <option value='no' ".$selVisible.">".A141."</option>";
            print "</td>";
}
if(isset($_POST['select']['products_taxable'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['products_taxable']=="no") {$seltaxable="selected";} else {$seltaxable="";}
            print "<select name='att_taxable[]'>
                    <option value='yes' ".$seltaxable.">".A140."</option>
                    <option value='no' ".$seltaxable.">".A141."</option>";
            print "</td>";
}
if(isset($_POST['select']['products_tax'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_tax']."' name='att_taxe[]' size='5'></td>";
}
if(isset($_POST['select']['products_date_added'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_date_added']."' name='att_dateAdded[]' size='20'></td>";
}

if(isset($_POST['select']['products_viewed'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_viewed']."' name='att_view[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_added'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_added']."' name='att_added[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_qt'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_qt']."' name='att_quantite[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_download'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['products_download']=="no") {$seldownload="selected";} else {$seldownload="";}
            print "<select name='att_download[]'>
                    <option value='yes' ".$seldownload.">".A140."</option>
                    <option value='no' ".$seldownload.">".A141."</option>";
            print "</td>";
}

if(isset($_POST['select']['products_download_name'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_download_name']."' name='att_url[]' size='25'></td>";
}
 
if(isset($_POST['select']['products_exclusive'])) {
            print "<td align='left' valign='top'>";
                if($sqlReq['products_exclusive']=="no") {$selexclusive="selected";} else {$selexclusive="";}
            print "<select name='att_exclusive[]'>
                    <option value='yes' ".$selexclusive.">".A140."</option>
                    <option value='no' ".$selexclusive.">".A141."</option>";
            print "</td>";
}
 
if(isset($_POST['select']['products_sold'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_sold']."' name='att_sold[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_deee'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_deee']."' name='att_deee[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_ean'])) {
        print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_ean']."' name='att_ean[]' size='23'></td>";
}
 
if(isset($_POST['select']['products_sup_shipping'])) {
                print "<td align='left' valign='top'><input type='text' class='vullen' value='".$sqlReq['products_sup_shipping']."' name='att_sup_shipping[]' size='5'></td>";
}
 
if(isset($_POST['select']['products_caddie_display'])) {
        print "<td align='left' valign='top'>";
                if($sqlReq['products_caddie_display']=="no") {$selCaddieDisplay="selected";} else {$selCaddieDisplay="";}
            print "<select name='att_caddie_display[]'>
                    <option value='yes' ".$selCaddieDisplay.">".A140."</option>
                    <option value='no' ".$selCaddieDisplay.">".A141."</option>";
            print "</td>";
}
 
if(isset($_POST['select']['products_delay'])) {
        print "<td align='left' valign='top'>";
        $cS = "#FFFFCC";
        if($sqlReq['products_forsale']=='no') {
			$a = "<img src='im/no_stock2.gif' alt='".ARTICLE_EPUISE."' title='".ARTICLE_EPUISE."'>";
			$cS = "#DDDDDD";
		}
		else {
			$a="";
		}
        if($sqlReq['products_download']=='yes') {
			$b = "<img src='im/download.gif' alt='".ARTICLE_EN_TELECHARGEMENT."' title='".ARTICLE_EN_TELECHARGEMENT."'>";
			$cS = "#DDDDDD";
		}
		else {
			$b="";
		}
        if($a!=="" OR $b!=="") {
			print "<div style='background:#FFFF00; padding:2px; border:#999999 1px solid; border-bottom:0px;'>".$a."&nbsp;".$b."</div>";
		}
		print "<div style='background:".$cS."; padding:2px; border:#999999 1px solid;'>";
		print "In voorraad: ".ENTRE." <input type='text' value='".$sqlReq['products_delay_1']."' name='att_delay_1[]' size='2'>";
		print " ".JOURS_ET." ";
		print "<input type='text' class='vullen' value='".$sqlReq['products_delay_2']."' name='att_delay_2[]' size='2'> ".JOURS;
		print "<br>";
		print "Op bestelling: ".ENTRE." <input type='text' value='".$sqlReq['products_delay_1a']."' name='att_delay_1a[]' size='2'>";
		print " ".JOURS_ET." ";
		print "<input type='text' class='vullen' value='".$sqlReq['products_delay_2a']."' name='att_delay_2a[]' size='2'> ".JOURS;
		print "<br>";
		print "Aangepast: ".ENTRE." <input type='text' class='vullen' value='".$sqlReq['products_delay_1b']."' name='att_delay_1b[]' size='2'>";
		print " ".JOURS_ET." ";
		print "<input type='text' value='".$sqlReq['products_delay_2b']."' name='att_delay_2b[]' size='2'> ".JOURS;
		print "</div>";
		print "</td>";
}
            print "</tr>";
        }
        
         
        print "<tr><td colspan='50'>";
        print "<table border='0' width='100%' align='center' cellpadding='0' cellspacing='0' class='TABLE' width='700'><tr>";
        print "<td align='left'><input type='submit' class='knop' value='".A152."'></td>";
        print "<td align='center'><input type='submit' class='knop' value='".A152."'></td>";
        print "<td align='right'><input type='submit' class='knop' value='".A152."'></td>";
        print "</tr></table>";
        print "</td></tr>";
        
        print "</table>";
        print "<input type='hidden' name='action' value='dede'>";
        print "<input type='hidden' name='num' value='".$num."'>";
        print "<input type='hidden' name='att_id[]' value='".$sqlReq['products_id']."'>";
        print "</form>";
    }
    else {
        print "<p align='center' class='fontrouge'><b>".A145."</b></p>";
    }
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
if(isset($message) AND $message!=="") print "<p align='center' class='fontrouge'>".$message."</p>";
?>
<p align="center" class="largeBold"><?php print A1;?></p>

<form action="artikel_zoeken.php" method="POST">

<table width="700" border="0" align="center" cellpadding="10" cellspacing = "0" class="TABLE"><tr><td>
<?php
if(!isset($_GET['sel'])) {$ch=""; $defaut="checked";}
if(isset($_GET['sel']) AND $_GET['sel']=="all") {$ch="checked"; $defaut="";}
if(isset($_GET['sel']) AND $_GET['sel']=="nada") {$ch=""; $defaut="";}
if(isset($_GET['sel']) AND $_GET['sel']=="def") {$ch=""; $defaut="checked";}

 
?>
<table width="700" border="0" align="center" cellpadding="3" cellspacing = "0" class="TABLE">
<tr height="30" bgcolor="#FFFFFF">
<td colspan="2" align="center"><b><?php print A146;?></b></td>
</tr><tr>
        <td><?php print NAME1?></td><td><input type="text" class='vullen' name="nom" value="" size="30"></td>
        </tr><tr>
        <td width="150"><?php print A111;?></td>
        <td>
                <select name="cat">
                <option value="no">--</option>
                <?php
                        $query = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                                              FROM categories
                                              WHERE categories_noeud = 'L'
                                              AND categories_visible = 'yes'
                                              ORDER BY categories_name_".$_SESSION['lang']."");
                        $queryNum = mysql_num_rows($query);
                        if($queryNum > 0) {
                          while ($myrow = mysql_fetch_array($query)) {
                                 $query2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                                                        FROM categories
                                                        WHERE categories_id = '".$myrow['parent_id']."'");
                          $a =  mysql_fetch_array($query2);
                          if(!empty($a['categories_name_'.$_SESSION['lang']]) and $a['categories_name_'.$_SESSION['lang']] !== "Menu") $sous = "[".$a['categories_name_'.$_SESSION['lang']]."]"; else $sous = "";
                                  print "<option name='group' value='".$myrow['categories_id']."'>".$myrow['categories_name_'.$_SESSION['lang']]." $sous</option>";
                          }
                        }
                ?>
                </select>
        </td>
        </tr><tr>
        <td><?php print REFE;?></td><td><input type="text" class='vullen' name="ref" value="" size="25"></td>
        </tr><tr>
        
        <?php 
                    $queryFourn = mysql_query("SELECT fournisseurs_id, fournisseurs_company
                                              FROM fournisseurs
                                              WHERE fournisseur_ou_fabricant = 'fournisseur'
                                              ORDER BY fournisseurs_company");
                                              
                    print "<td>".FOUR11."</td><td>";
                    print "<select name='fournisseur'>";
                    print "<option value='no'>--</option>";
                        while ($queryFournResult = mysql_fetch_array($queryFourn)) {
                            print "<option value='".$queryFournResult['fournisseurs_id']."'>".$queryFournResult['fournisseurs_company']."</option>";
                        }
                    print "</td>";
        ?>
        </tr><tr>
        <?php  
                    $queryFab = mysql_query("SELECT fournisseurs_id, fournisseurs_company
                                              FROM fournisseurs
                                              WHERE fournisseur_ou_fabricant = 'fabricant'
                                              ORDER BY fournisseurs_company");
                                              
                    print "<td>".FAB11."</td><td>";
                    print "<select name='fabricant'>";
                    print "<option value='no'>--</option>";
                        while ($queryFournResult2 = mysql_fetch_array($queryFab)) {
                            print "<option value='".$queryFournResult2['fournisseurs_id']."'>".$queryFournResult2['fournisseurs_company']."</option>";
                        }
                    print "</td>";
        ?>
        </tr><tr>
        <td><?php print PRIXE?></td><td><?php print ENTRE1;?>&nbsp;&nbsp;<input type="text" class='vullen' name="prix1" value="" size="5">&nbsp;&nbsp;<?php print ET;?>&nbsp;&nbsp;<input type="text" class='vullen' name="prix2" value="" size="5"></td>
        </tr><tr>
        <td>BTW</td><td><input type="text" class='vullen' name="taxe" value="" size="5">&nbsp;&nbsp;%</td>
        </tr><tr>
        <td><?php print A118?> (gr)</td><td><?php print ENTRE1;?>&nbsp;&nbsp;<input type="text" class='vullen' name="poid1" value="" size="5">&nbsp;&nbsp;<?php print ET;?>&nbsp;&nbsp;<input type="text" class='vullen' name="poid2" value="" size="5"></td>
        </tr><tr>
        <td><?php print VISIB?></td>
            <td>
                <select name="visible">
                <option value="">--</option>
                <option value="yes"><?php print A140;?></option>
                <option value="no"><?php print A141;?></option>
            </td>
        </tr><tr>
        <td>Hoeveelheid</td><td><?php print ENTRE1;?>&nbsp;&nbsp;<input type="text" class='vullen' name="quantite1" value="" size="5">&nbsp;&nbsp;en&nbsp;&nbsp;<input type="text" class='vullen' name="quantite2" value="" size="5"></td>
        </tr><tr>
        <td><?php print DWL?></td>
            <td>
                <select name="download">
                <option value="">--</option>
                <option value="yes"><?php print A140;?></option>
                <option value="no"><?php print A141;?></option>            
            </td>
        </tr><tr>
        <td><?php print SELECTI?></td>
            <td>
                <select name="exclusive">
                <option value="">--</option>
                <option value="yes"><?php print A140;?></option>
                <option value="no"><?php print A141;?></option>             
            </td>
<tr>
        <td><?php print VENDU;?></td>
            <td>
                <select name="sold">
                <option value="">--</option>
                <option value="yes"><?php print A140;?></option>
                <option value="no"><?php print A141;?></option>             
            </td>
<tr>
        <td><?php print ECOP;?></td>
            <td>
                <select name="deee">
                <option value="">--</option>
                <option value="yes"><?php print A140;?> (Eco-part>0.00)</option>
                <option value="no"><?php print A141;?> (Eco-part=0.00)</option>             
            </td>

</tr>
<tr>
        <td>EAN</td><td><input type="text" class='vullen' name="ean value="" size="23"></td>

</tr>
<tr>
        <td><?php print SUPPLEMENT;?>
        </td>
            <td>
                <select name="sup_shipping">
                <option value="">--</option>
                <option value="yes"><?php print A140;?> (Extra>0.00)</option>
                <option value="no"><?php print A141;?> (Extra=0.00)</option>             
            </td>

</tr>
<tr>
        <td><?php print DISPLAY_CADDIE;?>
        </td>
            <td>
                <select name="caddie_display">
                <option value="">--</option>
                <option value="yes"><?php print A140;?></option>
                <option value="no"><?php print A141;?></option>          
            </td>

</tr>
</table>

<br>


<table border="0" width="700" align="center" cellpadding="2" cellspacing = "0" class="TABLE">
<tr bgcolor="#FFFFFF">
<td height="30" align="center" colspan="4"><b><?php print A148;?></b><br>
                                            <a href="artikel_zoeken.php?sel=all"><?php print A149;?></a> | <a href="artikel_zoeken.php?sel=nada"><?php print A150;?></a> | <a href="artikel_zoeken.php?sel=def"><?php print A151;?></a>
<br>
                                            </td></tr><tr>
<td align="left" valign="top"><input type="checkbox" name="select[afficher_fournisseur]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A112;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[afficher_fabricant]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A113;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_name_1]" value="yes" <?php echo $ch;?> <?php echo $defaut;?>>&nbsp;<?php print A114;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_desc_1]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A115;?></td>
</tr><tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_weight]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A118;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_ref]" value="yes" <?php echo $ch;?> <?php echo $defaut;?>>&nbsp;<?php print A119;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_image]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A120;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_im]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A121;?></td>
</tr><tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_visible]" value="yes" <?php echo $ch;?> <?php echo $defaut;?>>&nbsp;<?php print A124;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_taxable]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A125;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_tax]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A126;?><br></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_date_added]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A127;?></td>
</tr><tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_qt]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A130;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_download]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A131;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_download_name]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A132;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_exclusive]" value="yes" <?php echo $ch;?> >&nbsp;<?php print A133;?></td>
</tr><tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_price]" value="yes"  <?php echo $ch;?> <?php echo $defaut;?>>&nbsp;<?php print A117;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_sold]" value="yes" <?php echo $ch;?>>&nbsp;<?php print VENDU;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_added]" value="yes" <?php echo $ch;?> 
  >&nbsp;<?php print A129;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_note_1]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A123;?></td>

</tr>
<tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_garantie_1]" value="yes" <?php echo $ch;?>>&nbsp;<?php print A116;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_image2]" value="yes" <?php echo $ch;?> 
  >&nbsp;<?php print A122;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_viewed]" value="yes" <?php echo $ch;?> 
  >&nbsp;<?php print A128;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[categories_id]" value="yes" <?php echo $ch;?> 
  <?php echo $defaut;?>>&nbsp;<?php print A111;?></td>

</tr>
<tr>
<td align="left" valign="top"><input type="checkbox" name="select[products_deee]" value="yes" <?php echo $ch;?>>&nbsp;Eco-part</td>
<td align="left" valign="top"><input type="checkbox" name="select[products_delay]" value="yes" <?php echo $ch;?>>&nbsp;<?php
  print DELAI_DE_LIVRAISON;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_sup_shipping]" value="yes" <?php echo $ch;?>>&nbsp;<?php print SUPPLEMENT1;?></td>
<td align="left" valign="top"><input type="checkbox" name="select[products_caddie_display]" value="yes" <?php echo $ch;?>>&nbsp;<?php print DISPLAY_CADDIE;?></td>

</tr>
</table>


<br>



<?php

?>

<table width="300" border="0" align="center" cellpadding="5" cellspacing = "0">
<tr>
<td colspan="2">
<?php
if($queryNum == 0) {
    print "<div align='center'><b>".A147."</b></div>";
}
else {
    if(isset($_GET['sel'])) {print "<input type='hidden' name='sel' value='".$_GET['sel']."'>";}
    print "<div align='center'><input type='submit' class='knop' value='".A1Z."'></div>";
}
?>

</td>
</tr>
</table>

</td></tr></table>

<input type="hidden" name="action" value="update">
</form>



  </body>
  </html>
