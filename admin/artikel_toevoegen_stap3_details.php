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
$DateHoy = date("Y-m-d")." ".date("H:i:s");

if($_GET['group']=="no") {
print "<html>";
        print "<head>";
        print "<title></title>";
        print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
        print "<link rel='stylesheet' href='style.css'>";
        print "</head>";
        print "<body leftmargin='0' topmargin='30' marginwidth='0' marginheight='0'>";
        print "<p align='center' class='title'>Een artikel toevoegen</p>";
        print "<table border='0' align='center' cellpadding='5' cellspacing = '0'>";
        print "<tr>";
        print "<td align='center' class='fontrouge' colspan='2'>".A1."</td>";
        print "</tr>";
        print "<tr>";
        print "<td align='center' colspan='2'>";
            print "<FORM action='artikel_toevoegen.php' method='post'><INPUT TYPE='submit' 'class='knop' VALUE='".A2."'>";
            print "</form>";
        print "</td>";
        print "</tr>";
        print "</table>";
        print "</body>";
print "</html>";

}
else {

$sids = mysql_query("SELECT *
                     FROM categories
                     WHERE parent_id ='".$_GET['group']."'");

$sids2 = mysql_query("SELECT *
                     FROM categories
                     WHERE categories_id ='".$_GET['group']."'");
$myrow3 = mysql_fetch_array($sids2);

$query4 = mysql_query("SELECT fournisseurs_id, fournisseurs_company
                       FROM fournisseurs
                       WHERE fournisseur_ou_fabricant = 'fournisseur'
                       ORDER BY fournisseurs_company");

$query5 = mysql_query("SELECT fournisseurs_id, fournisseurs_company
                       FROM fournisseurs
                       WHERE fournisseur_ou_fabricant = 'fabricant'
                       ORDER BY fournisseurs_company");
                       
$doubleRef = mysql_query("SELECT products_ref FROM products");
    while($doubleRefResult = mysql_fetch_array($doubleRef)) {
      $dRef[] = strtolower($doubleRefResult['products_ref']);
    }
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A3;?></p>

 

<script type="text/javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=300,height=240,screenX=100,screenY=100,top=100,left=100')
}
//--></script>

<script type="text/javascript">
<!--
function in_array(string, array) {
   for(i = 0; i < array.length; i++) {
      if(array[i] == string) {  
         return true;
      }
   }
return false;
}

function check_form() {
  rejet = false;
  rejet1 = false;
  rejet2 = false;
  rejet3 = false;
  falsechar="";
  var non = "+";
  var error = 0;
  var error_message = "";
  var subgroup = document.form1.subgroup.value;
  var article_name_1 = document.form1.article_name_1.value;
  var article_name_2 = document.form1.article_name_2.value;
  var article_name_3 = document.form1.article_name_3.value;
  var ref = document.form1.ref.value;
  var company = document.form1.company.value;
  var price = document.form1.price.value;
  var weight = document.form1.weight.value;
  var marque = document.form1.marque.value;
  var day1 = document.form1.day1.value;
  var day2 = document.form1.day2.value;
  var day1a = document.form1.day1a.value;
  var day2a = document.form1.day2a.value;
  var day1b = document.form1.day1b.value;
  var day2b = document.form1.day2b.value;
  var js_array = new Array ();
  
<?php
for($i=0;$i<count($dRef); $i++) {
echo "js_array[$i]='".$dRef[$i]."';";
}
?>


  for(i=0 ; i <= form1.article_name_3.value.length ; i++) { if((form1.article_name_3.value.charAt(i)==non)) {rejet=true; falsechar= non;}}
  if(rejet==true) {
    error_message = error_message + "- '"+falsechar+"' <?php print A330;?> '<?php print A11;?> BE'\n";
    error = 1;
  }

  for(i=0 ; i <= form1.article_name_1.value.length ; i++) { if((form1.article_name_1.value.charAt(i)==non)) {rejet1=true; falsechar= non;}}
  if(rejet1==true) {
    error_message = error_message + "- '"+falsechar+"' <?php print A330;?> '<?php print A11;?> FR'\n";
    error = 1;
  }
  
  for(i=0 ; i <= form1.article_name_2.value.length ; i++) { if((form1.article_name_2.value.charAt(i)==non)) {rejet2=true; falsechar= non;}}
  if(rejet2==true) {
    error_message = error_message + "- '"+falsechar+"' <?php print A330;?> '<?php print A11;?> UK' \n";
    error = 1;
  }

  for(i=0 ; i <= form1.ref.value.length ; i++) { if((form1.ref.value.charAt(i)==non)) {rejet3=true; falsechar= non;}}
  if(rejet3==true) {
    error_message = error_message + "- '"+falsechar+"' <?php print A330;?> '<?php print A12;?>'\n";
    error = 1;
  }
  if(document.form1.elements['marque'].type != "hidden") {
    if(marque == 'no') {
      error_message = error_message + "- <?php print A4A;?>\n";
      error = 1;
    }
  }  
  if(document.form1.elements['subgroup'].type != "hidden") {
    if(subgroup == 'no') {
      error_message = error_message + "- <?php print A4;?>\n";
      error = 1;
    }
  }

  if(document.form1.elements['article_name_1'].type != "hidden") {
    if(article_name_1 == '' && article_name_2 == '' && article_name_3 == '') {
      error_message = error_message + "- <?php print A5;?>\n";
      error = 1;
    }
  }
      
  if(document.form1.elements['ref'].type != "hidden") {
    if(ref == '' ) {
      error_message = error_message + "- <?php print A6;?>\n";
      error = 1;
    }
    else {
      if(in_array(ref.toLowerCase(), js_array)) {
         error_message = error_message + "- <?php print A12;?> " + ref + " <?php print EST_DEJA;?>\n";
         error = 1;
      }
    }
  }

  if(document.form1.elements['company'].type != "hidden") {
    if(company == 'no') {
      error_message = error_message + "- <?php print A7;?>\n";
      error = 1;
    }
  }

  if(document.form1.elements['price'].type != "hidden") {
    if(price == '00.00' || price == '') {
      error_message = error_message + "- <?php print A8;?>\n";
      error = 1;
    }
  }

  if(document.form1.elements['weight'].type != "hidden") {
    if(weight == '' ) {
      error_message = error_message + "- <?php print A9;?>\n";
      error = 1;
    }
  }

    if(day1==' ' || day1=='' || isNaN(day1)==true || day2==' ' || day2=='' || isNaN(day2)==true || day1a==' ' || day1a=='' || isNaN(day1a)==true || day2a==' ' || day2a=='' || isNaN(day2a)==true || day1b==' ' || day1b=='' || isNaN(day1b)==true || day2b==' ' || day2b=='' || isNaN(day2b)==true) {
      error_message = error_message + "- <?php print VEUILLEZ_ENTRER_DELAI_DE_LIVRAISON;?>\n";
      error = 1;
    }


  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
<form action="artikel_toegevoegd.php" method="post" name="form1" enctype='multipart/form-data' onsubmit="return check_form()">

<?php
// artikel_catégories
$subgroup = $myrow3['categories_id'];
$current_url = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
$current_url_image = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"]."&image=1";
?>

<table border="0" width="700" align="center" cellpadding="0" cellspacing="0" class="TABLE"><tr><td>

			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="30">
			<tr>
			<td align='center' width='100' style="background-image:url(im/1.gif); background-repeat: no-repeat; background-position: left top"><span style="color:#FFFFFF"><b><?php print ARTICLE;?></b></span></td>
			<td>&nbsp;</td>
			</tr><tr>
			<td colspan="3"><img src="im/zzz_noir.gif" height="2" width="100%"></td>
			</tr>
			</table>





<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center">
<tr height="45">
        <td align=left><b><?php print A10;?></td>
        <td align=left><b><?php print strtoupper($myrow3['categories_name_'.$_SESSION['lang']]); ?></b></td>
</tr>

<?php // product_naam_1-2-3 ?>
<tr>
        <td valign=top><?php print A11;?></td>
        <td>
        
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" class='vullen' size="80%" name="article_name_3">&nbsp; *
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" class='vullen' size="80%" name="article_name_1">&nbsp; *
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" class='vullen' size="80%" name="article_name_2">&nbsp; *
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        
        </td>
</tr>
<?php ref ?>
<tr>
        <td valign=top><?php print A12;?></td>
        <td><input type="text" size="30" class='vullen' name="ref"></td>
</tr>
<?php // EAN ?>
<tr>
<td valign=top><?php print EAN_PRODUCT;?></td>
        <td><input type="text" size="30" class='vullen' name="ean" value="">
        <br><?php print EAN_NOTE;?>
        </td>
</tr>
<?php // zichtbaar ?>
<tr>
        <td valign=top><?php print A18;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" checked name="visu">
             <?php print A17;?> <input type="radio" value="no" name="visu"></td>
</tr>
<?php // verkoop ?>
<tr>
        <td valign=top><?php print "Te koop";?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" checked name="forsale">
             <?php print A17;?> <input type="radio" value="no" name="forsale"></td>
</tr>
<?php // product_mandje ?>
<tr>
        <td valign><?php print AJOUTER_CADDIE;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" name="display_caddie">
             <?php print A17;?> <input type="radio" value="no" name="display_caddie" checked></td>
</tr>
<?php // prijs ?>
<tr>
<td valign=top><?php print A22;?></td>
        <td><input type="text" size="10" class='vullen' name="price"  value="0.00">&nbsp;&nbsp;*&nbsp;&nbsp;<?php print A24;?> <?php print $devise;?></td>
</tr>
<?php // gewicht ?>
<tr>
<td valign=top><?php print A25;?></td>
        <td><input type="text" size="10" class='vullen' name="weight" value="0">&nbsp;&nbsp;<?php print A26;?>.&nbsp;<?php print A26a;?></td>
</tr>
<?php // stock ?>
<tr>
<td valign><?php print A27;?></td>
        <td><input type="text" class='vullen' size="10" name="stock" value="10000"></td>
</tr>
<?php // btw ?>
<tr>
<td valign=top><?php print A31;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" name="taxable" checked>
             <?php print A17;?> <input type="radio" value="no" name="taxable">
        </td>
</tr>

<?php
// btw
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
               <br>".TAXED.": <b>".DEFINIRISO." <a href='site_config.php#".A160."'>".ICI."</a></b>";
}
?>

<tr>
<td valign=top><?php print A32;?></td>
<td><input type="text" size="7" class="vullen" name="taxNum">
<br><?php print A32A;?>
<?php print $defTax;?>
</td>
</tr>
<?php // fab - lev ?>
<tr>
<td valign=top><?php print A13;?></td>
        <td>
        <?php
        print "<select name='company'>";
        print "<option name='company' value='no'>".A14."</option>";

        while($a1_row = mysql_fetch_array($query4)) {
            if($a1_row['fournisseurs_company'] == $_GET['compagnie1']) $sel="selected"; else $sel="";
            print "<option name='company' value='".$a1_row['fournisseurs_id']."' ".$sel.">".strtoupper($a1_row['fournisseurs_company'])."";
            print "</option>";
        }
        print "</select>";

        ?>
        </td>
</tr>
<?php // afbeelden fab - lev ?>
<tr>
        <td valign=top><?php print A15;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" name="aff_four">
             <?php print A17;?> <input type="radio" value="no" name="aff_four" checked></td>
</tr>
<?php // artikel fabrikant ?>
<tr>
        <td valign=top><?php print A13A;?></td>
        <td>
        <?php
        print "<select name='marque'>";
        print "<option name='marque' value='no'>".A14."</option>";

        while($a1_row2 = mysql_fetch_array($query5)) {
            if($a1_row2['fournisseurs_company'] == $_GET['compagnie2']) $sel2="selected"; else $sel2="";
            print "<option name='marque' value='".$a1_row2['fournisseurs_id']."' ".$sel2.">".strtoupper($a1_row2['fournisseurs_company'])."";
            print "</option>";
        }
        print "</select>";

        ?>
        
        </td>
</tr>
<?php // afbeelden fabrikant ?>
<tr>
        <td valign=top><?php print A15A;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" name="aff_fab">
             <?php print A17;?> <input type="radio" value="no" checked name="aff_fab"></td>
</tr>
</tr>
<tr>
<td valign=top><?php print A19;?></b><br>(<?php print QUELQUES_PHRASES_OPTIMISEES;?>)</td>
        <td>
<?php // products_description courte 1-2-3 ?>
        <table border="0" align="center" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="spec_3" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="spec_1" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="spec_2" class="text"></textarea><br>
        </td></tr></table>
        
        </td>
</tr>
<tr>
<td valign=top><?php print A21;?><br>(<?php print DESCRIPTION_DETAILLEE;?>)</td>
        <td>
<?php // beschrijving 1-2-3 ?>
        <table border="0" align="center" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="note_3" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="note_1" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="7" name="note_2" class="text"></textarea><br></td>
        </tr></table>
   </td>
</tr>



<?php // optie nota_1-2-3 ?>
<tr>
<td valign=top><?php print A29;?></td>
        <td>
        <table border="0" align="center" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="3" name="optionNote_3" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="3" name="optionNote_1" class="text"></textarea><br></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="65" rows="3" name="optionNote_2" class="text"></textarea></td>
        </tr></table>        
        </td>
</tr>

<?php // Garantie 1-2-3 ?>
<tr>
<td valign=top><?php print A36;?></b><br></td>
      <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' size="85%" name="garantie_3"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' size="85%" name="garantie_1"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' size="85%" name="garantie_2"></td>
        </tr></table> 
      </td>
</tr>

<?php // download ?>
<tr>
    <td valign=top><?php print A49;?></td>
    <td>
    <select name="download">
    <option value="yes" selected><?php print A16;?></option>
    <option value="no"><?php print A17;?></option>
    </select>&nbsp;&nbsp;<?php print A26a;?>
    </td>
</tr>

<?php // product download naam ?>
<tr>
    <td valign=top><?php print A50;?></td>
        <td><input type="text" class='vullen' size="90%" name="file_name">
        <br><?php print A51;?>
    </td>
</tr>

<?php // relaties ?>
<tr>
    <td valign=top><?php print A52;?></td>
        <td>
        <?php
        
        $query = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_ref, products_related
                      FROM products
                      WHERE products_ref != 'GC100'
                      ORDER BY products_name_".$_SESSION['lang']."");
        
        print "<select name='selectProductsId[]' style='width:100%' size='10' multiple>";
                while($name2 = mysql_fetch_array($query)) {
                 print "<option value='".$name2['products_id']."'>".$name2['products_name_'.$_SESSION['lang']]." [REF: ".$name2['products_ref']."]</option>";
                }
        print "</select>";
        ?>
        <br><?php print A53;?>
    </td>
</tr>
<?php // exclusief artikel ?>
<tr>
<td valign=top><?php print ARTICLE_PRESENTE_EXCLUSIVITE;?></td>
        <td valign=top><?php print A16;?> <input type="radio" value="yes" name="exclusive" >
             <?php print A17;?> <input type="radio" value="no" name="exclusive" checked>
             <br><?php print SI_EXCLUSIVE_ACTIVE;?>
        </td>
</tr>

<?php // DEEE ?>
<tr>
<td valign=top><?php print DEEE;?></td>
        <td><input type="text" class='vullen' size="6" name="deee" value="0.00">&nbsp;<?php print $symbolDevise;?>&nbsp;<?php print DEEE_NOTE;?>
        </td>
</tr>

<?php // extra kost verzending ?>
<tr>
<td valign=top><?php print SUPPLEMENT;?></td>
        <td><input type="text" size="6" class='vullen' name="sup_shipping" value="0.00">&nbsp;<?php print $symbolDevise;?>
        </td>
</tr>

<?php // verzending ?>
<tr>
<td valign=top>
<?php print DELAI_DE_LIVRAISON;?>&nbsp;*
</td>
<td colspan="2">
<?php print EN_STOCK;?>:&nbsp;&nbsp;<?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class='vullen' name="day1" value="0" size="3">&nbsp;&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class='vullen' name="day2" value="0" size="3">&nbsp;&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print EN_COMMANDE;?>: <?php print ENTRE;?> <input type="text" class='vullen' name="day1a" value="0" size="3">&nbsp;&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class='vullen' name="day2a" value="0" size="3">&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print SUR_COMMANDE;?>: <?php print ENTRE;?> <input type="text" class='vullen' name="day1b" value="0" size="3">&nbsp;&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;&nbsp;<input type="text" class='vullen' name="day2b" value="0" size="3">&nbsp;&nbsp;&nbsp;<?php print JOURS;?>
<br><img src='im/zzz.gif' width='1' height='3'><br>
<?php print LAISSER_VIDE_SI_PRODUIT_TELECHARGEABLE;?>
</td>
</tr>



<?php // meta 1-2-3 ?>
<tr>
<td valign=top><?php print "Meta tag beschrijving";?><br>Maximaal 200 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_3" size="85%" maxlength="200"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_1" size="85%" maxlength="200"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_2" size="85%" maxlength="200"></td>
        </tr></table>        
        </td>
</tr>
<?php // meta titel 1-2-3 ?>
<tr>
<td valign=top><?php print "Meta tag titel";?><br>Maximaal 100 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_3" size="85%" maxlength="100"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_1" size="85%" maxlength="100"></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_2" size="85%" maxlength="100"></td>
        </tr></table>        
        </td>
</tr>
<tr>
<td colspan="2" align="center" style='color:#FFFFFF'><i><?php print LAISSER_LES_CHAMPS_VIDES;?></i></td>
</tr>
</table>



<br>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" height="30">
			<tr>
			<td align='center' width='100' style="background-image:url(im/2.gif); background-repeat: no-repeat; background-position: left top"><span style="color:#FFFFFF"><b><?php print IMAGES;?></b></span></td>
			<td>&nbsp;</td>
			</tr><tr>
			<td colspan="3"><img src="im/zzz_noir.gif" height="2" width="100%"></td>
			</tr>
			</table>



<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center">
<?php // Ajouter image principale ?>
<tr>
<td valign=top><?php print A33;?><br><?php print A20;?>(1)</td>
<td>
 
	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadMainImage' class='vullen'  size='40'>
	
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;&nbsp;<input type="text" size="60" class="vullen" name="ImageChange">
 
</td>
</tr>
<?php // afbeelding zichtbaar ?>
<tr>
<td valign=top><?php print A34;?></td>
        <td> <?php print A16;?> <input type="radio" value="yes" name="image" checked>
             <?php print A17;?> <input type="radio" value="no" name="image">
        </td>
</tr>
<?php // extra afbeelding 1 ?>
<tr>
<td valign=top><?php print A35;?> 1<br><?php print A20;?>(2)</td>
<td>
 
	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadExtraImage1' class='vullen'  size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;&nbsp;<input type="text" size="60" class="vullen" name="image2">
 
</td>
</tr>

<?php // extra afbeelding 2 ?>
<tr>
<td valign=top><?php print A35;?> 2<br><?php print A20;?>(1)</td>
<td>
 
	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadExtraImage2' class='vullen' size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;&nbsp;<input type="text" size="60" class="vullen" name="image3">
 
</td>
</tr>

<?php // extra afbeelding 3 ?>
<tr>
<td valign=top><?php print A35;?> 3<br><?php print A20;?>(1)</td>
<td>
 
	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadExtraImage3' class='vullen'  size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;&nbsp;<input type="text" size="60" class="vullen" name="image4">
 
</td>
</tr>

<?php // extra afbeelding 4 ?>
<tr>
<td valign=top><?php print A35;?> 4<br><?php print A20;?>(1)</td>
<td>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadExtraImage4' class='vullen'  size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	Ofwel een URL&nbsp;&nbsp;<input type="text" size="60" class="vullen" name="image5">
 
</td>
</tr>


<tr>
<td></td>
</tr>


<?php // knop toevoegen ?>
<tr >
        <td colspan="2" align="center"><input type="submit" name="toto" value="<?php print A39;?>" class='knop'></td>
</tr>

<?php // Tabel einde ?>
</table>

<?php // zichtbaar ?>
<input type="hidden" value="<?php print $subgroup;?>" name="subgroup">
<input type="hidden" value="<?php print $_GET['group'];?>" name="group">
<input type="hidden" value="<?php print $DateHoy;?>" name="date">
</form>
<br>
<br>

<table width="90%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
        <td colspan="2">
        * <?php print A40;?><br><br>
        <b><?php print A41;?> (1)</b><br>
        - <?php print A45;?>.<br>
        - <?php print A47;?><br>
        - <?php print A48;?>
        </td>
</tr>

</table><br>&nbsp;
</td></tr></table><br><br><br>
  </body>
  </html>
<?php
}
?>
