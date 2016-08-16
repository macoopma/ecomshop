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

$query = mysql_query("SELECT * FROM products WHERE products_id= '".$_GET['id']."'");
$row = mysql_fetch_array($query);

$query3 = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$row['categories_id']."'");
$row3 = mysql_fetch_array($query3);

$query2 = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_noeud = 'L' ORDER BY categories_name_".$_SESSION['lang']."");

$query5 = mysql_query("SELECT fournisseurs_id, fournisseurs_company FROM fournisseurs WHERE fournisseurs_id = '".$row['fournisseurs_id']."'");
$row5 = mysql_fetch_array($query5);

$query4 = mysql_query("SELECT fournisseurs_id, fournisseurs_company FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fournisseur' ORDER BY fournisseurs_company");

$query7 = mysql_query("SELECT fournisseurs_id, fournisseurs_company FROM fournisseurs WHERE fournisseurs_id = '".$row['fabricant_id']."'");
$row7 = mysql_fetch_array($query7);

$query6 = mysql_query("SELECT fournisseurs_id, fournisseurs_company FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fabricant' ORDER BY fournisseurs_company");
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=300,height=240,screenX=100,screenY=100,top=100,left=100')
}
//--></script>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<a href="#zulTop" name="zultop"></a>
<p align="center" class="largeBold"><?php print A1." - ".$row['products_name_'.$_SESSION['lang']];?> -</p>

<?php
// Option request
$optQuery = mysql_query("SELECT products_options_stock_id FROM products_options_stock WHERE products_options_stock_prod_id = '".$_GET['id']."'");
$optQueryNum = mysql_num_rows($optQuery);

// afbeelden options
if($row['products_options']=="yes") {
    print "<p align='center'>";
	if(isset($optQueryNum) AND $optQueryNum>0) print "<div align='center'>".CET_ARTICLE_A." ".$optQueryNum." ".DECLINAISONS.".</div>";
	print "<div align='center'><img src='im/zzz.gif' width='1' height='8'></div>";
    print "<div align='center'>";
	print "<table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' bgcolor='#FFFF00'><tr><td>";
	print "<center><a href='opties_details.php?id=".$row['products_id']."' style='text-decoration:none'><b>".VOIR_OPTIONS."</b></a>";
	print "</tr";
	print "</td>";
    print "</table>";
}
?>

<form action="artikel_gewijzigd_update.php" method="POST" enctype='multipart/form-data'>
<?php // zichtbaar ?>
<input type="hidden" value="<?php print $row['products_id'];?>" name="id">
<input type="hidden" value="<?php print $row['products_date_added'];?>" name="article_date">

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD>


			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="30">
			<tr>
			<td align='center' width='100' style="background-image:url(im/1.gif); background-repeat: no-repeat; background-position: left top"><span style="color:#FFFFFF"><b><?php print ARTICLE;?></b></span></td>
			<td>&nbsp;</td>
			<td align='right'><a href=''><a href="#bibi"><img src="im/down.gif" border="0" align="absmiddle" title='Images'></a>&nbsp;&nbsp;</a></td>
			</tr><tr>
			<td colspan="3"><img src="im/zzz_noir.gif" height="2" width="100%"></td>
			</tr>
			</table>
			

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">

 
<tr bgcolor="#CCFF00" height="25">
        <td><?php print A3;?>&nbsp;<font color=#000000>ID nr.</b></td>
        <!-- <td colspan='1'> -->
<td>
	  <b><?php print $row['products_id'];?></b></td>
        <!-- <td rowspan='1'>&nbsp;</td> -->
        <td>&nbsp;</td>
</tr>


<tr>
        <td valign=top><?php print NOM_DESIGNATION;?></td>
        <td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="article_name_3" class="vullen" value="<?php print $row['products_name_3'];?>">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="article_name_1" class="vullen" value="<?php print $row['products_name_1'];?>">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="article_name_2" class="vullen" value="<?php print $row['products_name_2'];?>">
        </td>
        <td style= rowspan='1' width='1' valign=top><input type=Submit class="knop"  value="<?php print A31;?>"></td>

</tr>

<?php // products_date ?>
<tr>
        <td valign=top><?php print A4;?></td>
        <td><?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['products_date_added']);?></td>
</tr>


<tr>
        <td valign=top>Download link</td>
        <td>
<?
echo '<a href="'.$row['products_download_name'].'">'.$row['products_download_name'].'</a>';
?>
</td>
</tr>


<tr>
        <td valign=top><?php print A5;?></td>
        <td>
		<input type="text" size="20" class="vullen" name="ref" value="<?php print $row['products_ref'];?>">
		<?php
		if($row['products_options']=="yes" AND $optQueryNum>0) {
			print "&nbsp;<a href='opties_voorraad.php?id=".$_GET['id']."&del=tab_values'><b>".REF_DECLINAISONS."</b></a>";
		}
		?>
		</td>
</tr>



<tr>
<td valign=top><?php print EAN_PRODUCT;?></td>
        <td><input type="text" size="50" class="vullen" name="ean" value="<?php print $row['products_ean'];?>">
        <br><?php print EAN_NOTE;?>
        </td>
</tr>


<tr>
<?php
        if($row['products_visible'] == "yes") {
                $checkedyes = "checked";
                $checkedno = "";
        } else {
                $checkedyes = "";
                $checkedno = "checked";
        }
?>
        <td valign=top><?php print A13;?></td>
        <td> <?php print A9;?>  <input type="radio" value="yes" <?php print $checkedyes;?> name="visu">
             <?php print A10;?> <input type="radio" value="no" name="visu" <?php print $checkedno;?>></td>
</tr>
 
<tr>
<?php
        if($row['products_forsale'] == "yes") {
                $checkedyesQ = "checked";
                $checkednoQ = "";
        } else {
                $checkedyesQ = "";
                $checkednoQ = "checked";
        }
?>
        <td valign=top><?php print "Te koop";?></td>
        <td> <?php print A9;?>  <input type="radio" value="yes" <?php print $checkedyesQ;?> name="forsale">
             <?php print A10;?> <input type="radio" value="no" name="forsale" <?php print $checkednoQ;?>></td>
</tr>
 
<tr>
<?php
        if($row['products_caddie_display'] == "yes") {
                $checkedyesZ = "checked";
                $checkednoZ = "";
        } else {
                $checkedyesZ = "";
                $checkednoZ = "checked";
        }
?>
        <td valign=top><?php print AJOUTER_CADDIE;?></td>
        <td> <?php print A9;?> <input type="radio" value="yes" <?php print $checkedyesZ;?> name="display_caddie">
             <?php print A10;?> <input type="radio" value="no" name="display_caddie" <?php print $checkednoZ;?>></td>
             <td style= rowspan='3' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>

<tr>
<td valign=top><?php print A16;?></td>
        <td>
		<input type="text" class="vullen" size="10" name="price" value="<?php print $row['products_price'];?>">&nbsp;<?php print $symbolDevise;?>
		</td>
</tr>
 
<tr>
<td valign=top><?php print A17;?></td>
        <td><input type="text" class="vullen" size="10" name="weight" value="<?php print $row['products_weight'];?>">&nbsp;<?php print A18;?>. <?php print A17a;?></td>
</tr>
 
<tr>
<td valign=top><?php print A19;?></td>
        <td>
		<?php
		if($row['products_options']=="yes" AND $optQueryNum>0) {
			print "<input type='text' class='vullen' size='10' name='stock' value=".$row['products_qt']." disabled>";
			print "<input type='hidden' name='stock' value=".$row['products_qt'].">";
			print "&nbsp;<a href='opties_voorraad.php?id=".$_GET['id']."&del=tab_values'><b>".STOCK_DECLINAISONS."</b></a>";
		}
		else {
			print "<input type='text' class='vullen' size='10' name='stock' value=".$row['products_qt'].">";
		}
		?>
		</td>
        <td style= rowspan='3' valign=top><input type=Submit class=knop value="<?php print A31;?>">
		</td>
</tr>
 
<tr>
<td valign=top><?php print A21;?></td>
<?php
        if($row['products_taxable'] == "yes") {
                $checkedyes2 = "checked";
                $checkedno2 = "";
        }
        else {
                $checkedyes2 = "";
                $checkedno2 = "checked";
        }
?>
        <td> 
            <?php print A9;?>  <input type="radio" value="yes" name="taxable" <?php print $checkedyes2;?>>
            <?php print A10;?> <input type="radio" value="no" name="taxable" <?php print $checkedno2;?>>
        </td>
</tr>

<?php
 
if(isset($iso) AND !empty($iso)) {
    $query23 = mysql_query("SELECT countries_name, countries_product_tax, countries_product_tax_active
                       FROM countries
                       WHERE iso = '".$iso."'");
    $row23 = mysql_fetch_array($query23);
    if($row23['countries_product_tax_active'] == 'yes' ) {
        $defTax = "<br>".PAYSISO.": ".$row23['countries_name']."
                   <br>".TAXED.": ".$row23['countries_product_tax']."%";
    }
    else{
        $defTax = "<br>".PAYSISO.": ".$row23['countries_name']."
                   <br>".TAXED.": ".TAXENOTACTIVE."</b>";
    }
}
else {
    $defTax = "<br>".PAYSISO.": <b>".PAYSNOTDEFINED."</b>
               <br>".TAXED.": <b>".DEFINIRISO." <a href='site_config.php#".A160."'>".ICI."</a>!</b>";
}
?>
<tr>
<td valign=top><?php print A22;?></td>
        <td><input type="text" class="vullen" size="7" name="taxNum" value="<?php print $row['products_tax'];?>">&nbsp;%
        <br><?php print A22A;?>
        <?php print $defTax;?> 
</td>
</tr>

 
<tr>
<td valign=top><?php print A6;?></td>
        <td>
        <?php
        print "[".$row['fournisseurs_id']."] - ".$row5['fournisseurs_company']."&nbsp&nbsp";
        print "<select name='company'>";
        print "<option name='company' value='".$row['fournisseurs_id']."'>".A7."</option>";

        while($a1_row = mysql_fetch_array($query4)) {
               print "<option name='company' value='".$a1_row['fournisseurs_id']."'>".$a1_row['fournisseurs_company'];
               print "</option>";
        }
        print "</select>";
        ?>
        </td>
        <td style= rowspan='2' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>
 
<tr>
<?php
        if($row['afficher_fournisseur'] == "yes") {
                $checkedyes = "checked";
                $checkedno = "";
        } else {
                $checkedyes = "";
                $checkedno = "checked";
        }
?>
        <td valign=top><?php print A8;?></td>
        <td> <?php print A9;?> <input type="radio" value="yes" <?php print $checkedyes;?> name="aff_four">
             <?php print A10;?> <input type="radio" value="no" name="aff_four" <?php print $checkedno;?>></td>
</tr>
 
<tr> 
        <td valign=top><?php print A60;?></td>
        <td>
                <?php
        print "[".$row['fabricant_id']."] - ".$row7['fournisseurs_company']."&nbsp;&nbsp";
        print "<select name='marque'>";
        print "<option name='marque' value='".$row['fabricant_id']."'>".A7."</option>";

        while($a1_row2 = mysql_fetch_array($query6)) {
               print "<option name='marque' value='".$a1_row2['fournisseurs_id']."'>".$a1_row2['fournisseurs_company'];
               print "</option>";
        }
        print "</select>";
        ?>
        </td>
        <td style= rowspan='2' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>

</tr>
 
<tr>
<?php
        if($row['afficher_fabricant'] == "yes") {
                $checkedyes = "checked";
                $checkedno = "";
        } else {
                $checkedyes = "";
                $checkedno = "checked";
        }
?>
        <td valign=top><?php print A8A;?></td>
        <td> <?php print A9;?> <input type="radio" value="yes" <?php print $checkedyes;?> name="aff_fab">
             <?php print A10;?> <input type="radio" value="no" name="aff_fab" <?php print $checkedno;?>></td>
</tr>
  
<tr>
<td valign=top><?php print A11;?></td>
        <td>
        <?php
        print "&nbsp[".$row['categories_id']."] - <b>".$row3['categories_name_'.$_SESSION['lang']]."</b>&nbsp&nbsp";
        print "<select name='category'>";
        print "<option name='category' value='".$row['categories_id']."'>".A12."</option>";
        while($a_row = mysql_fetch_array($query2)) {
           print "<option name='category' value='".$a_row['categories_id']."'>".$a_row['categories_name_'.$_SESSION['lang']]." - [ID:".$a_row['categories_id']."]";
           print "</option>";
        }
        print "</select>";

$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=categorie','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=550,toolbar=no,scrollbars=yes,resizable=yes');\">";
$stockLev = $openLeg."<img border=0 src=im/help.png align=absmiddle></a>";
print "&nbsp;&nbsp;".$stockLev;
        ?>
</td>
<td style= rowspan='2' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>
 
<tr>
<td valign=top><?php print A101;?><br><?php print A102;?></td>
        <td>
        <?php
                if(!empty($row['categories_id_bis'])) {
                  $explodeCat = explode("|", $row['categories_id_bis']);
  
                  foreach($explodeCat as $key => $value) {
                  if($value == "") {
                        unset($explodeCat[$key]);
                      }
                    }
                    $explodeCatNew = array_values($explodeCat); 
                    $countCat = count($explodeCatNew);
                  print "[".A101.": <b>".$countCat."</b>]<br>";
                }
                else {
                $explodeCat[] = "";
                $countCat = 0;
                print "[".A101.": <b>0</b>]<br>";               
                }

        
$query = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                         FROM categories
                         WHERE categories_visible = 'yes'
                         AND categories_noeud = 'L'
                         ORDER BY categories_name_".$_SESSION['lang']."");
        
        print "<select name='selectCatId[]' style='width:95%' size='10' multiple>";
                while($name2 = mysql_fetch_array($query)) {
                
						$query2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
					                         FROM categories
					                         WHERE categories_id = ".$name2['parent_id']."");
					    $name3 = mysql_fetch_array($query2);
					    
	                 if($name2['categories_id'] !== $row['categories_id']) {
	                 if(in_array($name2['categories_id'], $explodeCat)) { $sel="selected";} else {$sel="";}
	                 print "<option value='".$name2['categories_id']."' $sel>".$name2['categories_name_'.$_SESSION['lang']]." -- [".$name3['categories_name_'.$_SESSION['lang']]."]</option>";
	                 }
                }
        print "</select>";
        print "<input type='hidden' name='relatedCat' value='".$explodeCat."'>";
        ?>
        <br><?php print A53;?>
</td>
</tr>
<tr>
<td valign=top><?php print A14;?><br><?php print QUELQUES_PHRASES_OPTIMISEES;?></td>
        <td>
 
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="spec_3"><?php print $row['products_desc_3'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="spec_1"><?php print $row['products_desc_1'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="spec_2"><?php print $row['products_desc_2'];?></textarea></td>
        </tr></table>
        
        </td>
        <td valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
        
</tr>

 
<tr>
<td valign=top><?php print A15;?></b><br>(<?php print DESCRIPTION_DETAILLEE;?>)</td>
        <td>

        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="note_3"><?php print $row['products_note_3'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="note_1"><?php print $row['products_note_1'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="7" name="note_2"><?php print $row['products_note_2'];?></textarea></td>
        </tr></table>
        
        </td>
        <td style= rowspan='1' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>


 
<tr>
<td valign=top><?php print A20;?></td>
        <td>

        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="3" name="optionNote_3"><?php print $row['products_option_note_3'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="3" name="optionNote_1"><?php print $row['products_option_note_1'];?></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65%" rows="3" name="optionNote_2"><?php print $row['products_option_note_2'];?></textarea></td>
        </tr></table>
        
        </td>
        <td style= rowspan='1' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>


 
<tr>
        <td valign=top><?php print A27;?></td>
        <td valign=top>
        
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="garantie_3" class="vullen" value='<?php print $row['products_garantie_3'];?>'>
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="garantie_1" class="vullen" value='<?php print $row['products_garantie_1'];?>'>
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" size="65%" name="garantie_2" class="vullen" value='<?php print $row['products_garantie_2'];?>'>
        
        </td>
        <td style= rowspan='1' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>

 
<tr>
        <td valign=top><?php print A28;?></td>
        <td><input type="text" class="vullen" size="5" name="article_viewed" value="<?php print $row['products_viewed'];?>">&nbsp;&nbsp;<?php print A39;?></td>
        <td style= rowspan='4' valign=top><input type=Submit class= knop value="<?php print A31;?>"></td>
</tr>
 
<tr>
        <td valign=top><?php print A30;?></td>
        <td><input type="text" class="vullen" size="5" name="article_added" value="<?php print $row['products_added'];?>"></td>
</tr>

<?php
 
if($row['products_download'] == "yes") $aa1 = "selected"; else $aa1 = "";
if($row['products_download'] == "no") $aa2 = "selected"; else $aa2 = "";
?>
<tr>
        <td valign=top><?php print A34;?></td>
        <td>
        <select name="download">
        <option value="yes" <?php print $aa1;?>><?php print A9;?></option>
        <option value="no" <?php print $aa2;?>><?php print A10;?></option>
        </select>
        <br><?php print A17a;?>
        </td>
</tr>

 
<tr>
    <td valign=top><?php print A50;?></td>
        <td><input type="text" class="vullen" size="95%" name="file_name" value="<?php print $row['products_download_name'];?>">
        <br><?php print A51;?><br>




<?
echo '<a href="'.$row['products_download_name'].'">'.$row['products_download_name'].'</a>';
?>









    </td>
</tr>

 
<tr>
    <td valign=top><?php print A52;?></td>
        <td>
        <?php
                if(!empty($row['products_related'])) {
                  $explodeRelated = explode("|", $row['products_related']);
                  $countRelated = count($explodeRelated);
                  print "[<b>".$countRelated."</b> ".A52."]<br>";
                }
                else {
                  $explodeRelated[] = "";
                  $countRelated = 0;
                  print "[<b>0</b> ".A52."]<br>";               
                }
        
        $query = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_ref, products_related
                      FROM products
                      WHERE products_id != ".$row['products_id']."
                      AND products_ref != 'GC100'
                      ORDER BY products_name_".$_SESSION['lang']."");
        
        print "<select name='selectProductsId[]' style='width:95%' size='20' multiple>";
            while($name2 = mysql_fetch_array($query)) {
                if(in_array($name2['products_id'], $explodeRelated)) {
					$sel="selected";
					$prod[] = $name2['products_id'];
				}
				else {
					$sel="";
				}
                 print "<option value='".$name2['products_id']."' $sel>".$name2['products_name_'.$_SESSION['lang']]." [REF: ".$name2['products_ref']."]</option>";
            }
        print "</select>";
        print "<input type='hidden' name='relatedProducts' value='".$explodeRelated."'>";
  
        if(isset($prod) AND count($prod)>0) {
        	print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
			print "<div style='border:1px #FFFFFF solid; padding:1px;'>";
			foreach($prod AS $items) {
				 $affDiplayRequest = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_id = '".$items."'");
				 $affDiplayResult = mysql_fetch_array($affDiplayRequest);
				 print "<div align='left' style='background:#FFFFCC;'>&nbsp;&nbsp;&nbsp;&bull;&nbsp;".$affDiplayResult["products_name_".$_SESSION['lang']]."</div>";
			}
			print "</div>";
		}
        ?>
        <br><?php print A53;?>
    </td>
    <td style= rowspan='2' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>

<?php
 
if($row['products_exclusive'] == "yes") $aa11 = "selected"; else $aa11 = "";
if($row['products_exclusive'] == "no") $aa21 = "selected"; else $aa21 = "";
?>
<tr>
        <td valign=top><?php print ARTICLE_PRESENTE_EXCLUSIVITE;?></td>
        <td>
        <select name="exclusive">
        <option value="yes" <?php print $aa11;?>><?php print A9;?></option>
        <option value="no" <?php print $aa21;?>><?php print A10;?></option>
        </select>
        <br><?php print SI_EXCLUSIVE_ACTIVE;?>
        </td>
</tr>

 
<tr>
        <td valign=top><?php print A100;?></td>
        <td><u>Nederlands</u><br>http://<?php print $www2.$domaineFull;?>/beschrijving.php?id=<?php print $row['products_id'];?>&path=<?php print $row['categories_id'];?>&amp;lang=3<br>
            <u>Frans</u><br>http://<?php print $www2.$domaineFull;?>/beschrijving.php?id=<?php print $row['products_id'];?>&path=<?php print $row['categories_id'];?>&amp;lang=1<br>
            <u>Engels</u><br>http://<?php print $www2.$domaineFull;?>/beschrijving.php?id=<?php print $row['products_id'];?>&path=<?php print $row['categories_id'];?>&amp;lang=2<br><br>
            
            <u>Arikel fiche bekijken</u><br>
            <b>Selecteer</b> 
            <a href="../artikel_fiche.php?id=<?php print $row['products_ref'];?>&lang=3" target="_blank"><img src="im/be.gif" border="0" align="absmiddle"></a>&nbsp;
            <a href="../artikel_fiche.php?id=<?php print $row['products_ref'];?>&lang=3" target="_blank"><img src="im/nl.gif" border="0" align="absmiddle"></a>&nbsp;
            <a href="../artikel_fiche.php?id=<?php print $row['products_ref'];?>&lang=1" target="_blank"><img src="im/fr.gif" border="0" align="absmiddle"></a>&nbsp;
            <a href="../artikel_fiche.php?id=<?php print $row['products_ref'];?>&lang=2" target="_blank"><img src="im/uk.gif" border="0" align="absmiddle"></a>&nbsp;
            <br>
            <b>URL</b><br>
            NL: http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>&amp;lang=3<br>
            FR: http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>&amp;lang=1<br>
            UK: http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>&amp;lang=2
            <br>
            <b>HTML code</b> 
            <a href="../artikel_fiche_html.php?url=http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>__lang=3" target="_blank"><img src="im/be.gif" border="0" align="absmiddle"></a>&nbsp;
            <a href="../artikel_fiche_html.php?url=http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>__lang=3" target="_blank"><img src="im/nl.gif" border="0" align="absmiddle"></a>&nbsp;
		<a href="../artikel_fiche_html.php?url=http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>__lang=1" target="_blank"><img src="im/fr.gif" border="0" align="absmiddle"></a>&nbsp;
            <a href="../artikel_fiche_html.php?url=http://<?php print $www2.$domaineFull;?>/artikel_fiche.php?id=<?php print $row['products_ref'];?>__lang=2" target="_blank"><img src="im/uk.gif" border="0" align="absmiddle"></a>&nbsp;
            
        </td>
        <td style= rowspan='2' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>
 
<tr>
        <td valign><?php print VENDU;?></td>
        <td><input type="text" size="6" class="vullen" name="sold" value="<?php print $row['products_sold'];?>"></td>
</tr>

 

<tr>
        <td valign><?php print DEEE;?></td>
        <td><input type="text" size="6" class="vullen" name="deee" value="<?php print $row['products_deee'];?>">&nbsp;&nbsp;<?php print $symbolDevise;?>&nbsp;&nbsp;<?php print DEEEE;?>
        <br><?php print DEEE_NOTE;?>
        </td>
        <td style= rowspan='3' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>

 

<tr>
        <td valign=top><?php print SUPPLEMENT;?></td>
        <td><input type="text" class="vullen" size="6" name="sup_shipping" value="<?php print $row['products_sup_shipping'];?>">&nbsp;&nbsp;<?php print $symbolDevise;?>
        </td>
</tr>

 
<tr>
<td valign=top><?php print DELAI_DE_LIVRAISON;?>&nbsp;*
</td>
<td colspan="1">
<?php print EN_STOCK;?>&nbsp;<?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day1" value="<?php print $row['products_delay_1'];?>" size="1">&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class="vullen" name="day2" value="<?php print $row['products_delay_2'];?>" size="1">&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print EN_COMMANDE;?>&nbsp;<?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day1a" value="<?php print $row['products_delay_1a'];?>" size="1">&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class="vullen" name="day2a" value="<?php print $row['products_delay_2a'];?>" size="1">&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print SUR_COMMANDE;?>&nbsp;<?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day1b" value="<?php print $row['products_delay_1b'];?>" size="1">&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class="vullen" name="day2b" value="<?php print $row['products_delay_2b'];?>" size="1">&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print LAISSER_VIDE_SI_PRODUIT_TELECHARGEABLE;?>
</td>
</tr>


<tr>
<td valign><?php print "Meta tag beschrijving";?><br>Maximaal 200 <?php print CAR;?></td>
        <td>
        <table border="0" align="center" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_3" value="<?php print $row['products_meta_description_3'];?>" size="85%" class="vullen" maxlength="200"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_1" value="<?php print $row['products_meta_description_1'];?>" size="85%" class="vullen" maxlength="200"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_2" value="<?php print $row['products_meta_description_2'];?>" size="85%" class="vullen" maxlength="200"></td>
        </tr></table>        
        </td>
        <td style= rowspan='3' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>
 
<tr>
<td valign=top><?php print "Meta tag titel";?></b><br>Maximaam 100 <?php print CAR;?></td>
        <td>
        <table border="0" align="center" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_3" value="<?php print $row['products_meta_title_3'];?>" size="85%" class="vullen" maxlength="100"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_1" value="<?php print $row['products_meta_title_1'];?>" size="85%" class="vullen" maxlength="100"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_2" value="<?php print $row['products_meta_title_2'];?>" size="85%" class="vullen" maxlength="100"></td>
        </tr></table>        
        </td>
</tr>
<tr>
<td colspan="2" align="center" style='color:#FFFFFF'><i><?php print LAISSER_LES_CHAMPS_VIDES;?></i></td>
</tr>
</table>
<br>
<br>

<a href="#" name="bibi"></a>

			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="30">
			<tr>
			<td align='center' width='100' style="background-image:url(im/2.gif); background-repeat: no-repeat; background-position: left top"><span style="color:#FFFFFF"><b><?php print IMAGES;?></b></span></td>
			<td>&nbsp;</td>
			<td align='right'>
				<a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle" title='Top'></a>&nbsp;&nbsp;
			</td>
			</tr><tr>
			<td colspan="3"><img src="im/zzz_noir.gif" height="2" width="100%"></td>
			</tr>
			</table>

<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">

<tr>
<td valign=top>
<?php print A24;?></b>
</td>
<td valign=top>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadMainImage' class='vullen' size='40'>
 	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;<input type="text" size="60" class="vullen" name="ImageChange" value="<?php print $row['products_image'];?>">
 
<br><br>
</td>
<td style= rowspan='3' width='1' valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>

 
<tr>
<td valign=top><?php print A22a;?></td>
<?php
        if($row['products_im'] == "yes") {
                $checkedyes3 = "checked";
                $checkedno3 = "";
        } else {
                $checkedyes3 = "";
                $checkedno3 = "checked";
        }
?>
        <td> <?php print A9;?>  <input type="radio" value="yes" <?php print $checkedyes3;?> name="image">
             <?php print A10;?> <input type="radio" value="no" name="image" <?php print $checkedno3;?>>
        </td>
</tr>
<?php
	print "<tr>";
	print "<td valign=top>".A23."</b>";
    if(substr($row['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
	if(!empty($row['products_image'])) {
		$popSize = @getimagesize($dirr.$row['products_image']);
		$witdtZ = ($popSize AND $popSize[0]>200)? "width='200'" : "";
		print "<td><img src='".$dirr.$row['products_image']."' ".$witdtZ."></td>";
	}
	else {
		print "<td valign=top><img src='../im/no_image_small.gif'></td>";
	}
	print "</tr>";
?>

 
<tr>
<td valign=top><?php print A26;?></td>
<td>


	&nbsp;&bull;&nbsp;<?php print UPLOAD_EXTRA;?> 1<br>&nbsp;<input type='file' name='uploadExtraImage1' class='vullen' size='40'>
 	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;<input type="text" size="60" class="vullen" name="image2" value="<?php print $row['products_image2'];?>">
	<br><?php
	if(!empty($row['products_image2'])) print "<br>&nbsp;<a href='../".$row['products_image2']."' target='_blank'><b>".A23."</b></a>";
	?>

<br><br>



	&nbsp;&bull;&nbsp;<?php print UPLOAD_EXTRA;?> 2<br>&nbsp;<input type='file' name='uploadExtraImage2' class='vullen' size='40'>
 	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;<input type="text" size="60" class="vullen" name="image3" value="<?php print $row['products_image3'];?>">
	<br><?php
	if(!empty($row['products_image3'])) print "<br>&nbsp;<a href='../".$row['products_image3']."' target='_blank'><b>".A23."</b></a>";
	?>

<br><br>

 
	&nbsp;&bull;&nbsp;<?php print UPLOAD_EXTRA;?> 3<br>&nbsp;<input type='file' name='uploadExtraImage3' class='vullen' size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;<input type="text" size="60" class="vullen" name="image4" value="<?php print $row['products_image4'];?>">
<?php
	if(!empty($row['products_image4'])) print "<br>&nbsp;<a href='../".$row['products_image4']."' target='_blank'><b>".A23."</b></a>";
	?>
<br><br>


 


	&nbsp;&bull;&nbsp;<?php print UPLOAD_EXTRA;?> 4<br>&nbsp;<input type='file' name='uploadExtraImage4' class='vullen' size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;<input type="text" size="60" class="vullen" name="image5" value="<?php print $row['products_image5'];?>">
	<?php
	if(!empty($row['products_image5'])) print "<br>&nbsp;<a href='../".$row['products_image5']."' target='_blank'><b>".A23."</b></a>";
	?>
 <br><br>

 

</td>
<td valign=top><input type=Submit class=knop value="<?php print A31;?>"></td>
</tr>
</table>
<br><br>

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" >
<tr>
<td colspan='3' height='35' align="center">
<input type=Submit value="<?php print A31;?>" class='knop'>
</form>

<form action="artikel_verwijderen.php" method="GET">
<input type="hidden" value="<?php print $row['products_id'];?>" name="id">
<input type="submit" value="<?php print A32;?>" class='knop'>
</form>
<form action="artikel_dupliceren.php" method="GET">
 
<input type="hidden" value="<?php print $row['products_id'];?>" name="id">
<input type="submit" value="<?php print A33;?>" class='knop'>
</form>
</tr></td></table>


<?php
if(isset($_GET['from']) and $_GET['from'] == "items") {
print "<p align='center'>";
print "<a href='resume_items_2.php?id=".$row['products_id']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A502."</a>";
print "</p>";
}
if(isset($_GET['from']) and $_GET['from'] == "fou") {
print "<p align='center'>";
print "<a href='verkoop_op_leverancier_details.php?id=".$row['fournisseurs_id']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A501."</a>";
print "</p>";
}
if(isset($_GET['from']) and $_GET['from'] == "cli") {
print "<p align='center'>";
print "<a href='verkoop_op_klant_details.php?id=".$_GET['obj']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A522."</a>";
print "</p>";
}
?>
<br><br><br>
</body>
</html>
