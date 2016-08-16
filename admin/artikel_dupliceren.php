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

if(empty($_POST['sold'])) {$_POST['sold'] = 0;}

 
$dateDup = date("Y-m-d H:i:s");
$message = '';

 
if(isset($_GET['nb']) AND is_numeric($_GET['nb']) AND $_GET['nb']>0) {
	$queryDup = mysql_query("SELECT * FROM products WHERE products_id = '".$_GET['id']."'");
	$rowDup = mysql_fetch_array($queryDup);
		
	for($i=1; $i<=$_GET['nb']; $i++) {
 
	    $statTable = mysql_fetch_array(mysql_query("SHOW TABLE STATUS LIKE 'products'"));
	    $prodNum = $statTable['Auto_increment'];
		$refDup = $rowDup['products_ref']."-".$prodNum;
 
		mysql_query("INSERT INTO products SET 
						products_id = '".$prodNum."',
						categories_id = '".$rowDup['categories_id']."',
						categories_id_bis = '".$rowDup['categories_id_bis']."',
						
						fournisseurs_id = '".$rowDup['fournisseurs_id']."',
						afficher_fournisseur = '".$rowDup['afficher_fournisseur']."',
						
						fabricant_id = '".$rowDup['fabricant_id']."',
						afficher_fabricant = '".$rowDup['afficher_fabricant']."',
						
						products_name_1 = '".$rowDup['products_name_1']."',
						products_name_2 = '".$rowDup['products_name_2']."',
						products_name_3 = '".$rowDup['products_name_3']."',
						
						products_desc_1 = '".$rowDup['products_desc_1']."',
						products_desc_2 = '".$rowDup['products_desc_2']."',
						products_desc_3 = '".$rowDup['products_desc_3']."',
						
						products_price = '".$rowDup['products_price']."',
						products_weight = '".$rowDup['products_weight']."',
						products_options = 'no',
						
						products_garantie_1 = '".$rowDup['products_garantie_1']."',
						products_garantie_2 = '".$rowDup['products_garantie_2']."',
						products_garantie_3 = '".$rowDup['products_garantie_3']."',
						
						products_option_note_1 = '".$rowDup['products_option_note_1']."',
						products_option_note_2 = '".$rowDup['products_option_note_2']."',
						products_option_note_3 = '".$rowDup['products_option_note_3']."',
						
						products_ref = '".$refDup."',
						products_im = '".$rowDup['products_im']."',
						products_image = '".$rowDup['products_image']."',
						products_image2 = '".$rowDup['products_image2']."',
						products_image3 = '".$rowDup['products_image3']."',
						products_image4 = '".$rowDup['products_image4']."',
						products_image5 = '".$rowDup['products_image5']."',
						
						products_note_1 = '".$rowDup['products_note_1']."',
						products_note_2 = '".$rowDup['products_note_2']."',
						products_note_3 = '".$rowDup['products_note_3']."',
						
						products_visible = '".$rowDup['products_visible']."',
						products_taxable = '".$rowDup['products_taxable']."',
						products_tax = '".$rowDup['products_tax']."',
						products_date_added = '".$dateDup."',
						products_viewed = '0',
						products_added = '0',
						products_download = '".$rowDup['products_download']."',
						products_download_name = '".$rowDup['products_download_name']."',
						products_related = '".$rowDup['products_related']."',
						products_exclusive = '".$rowDup['products_exclusive']."',
						products_sold = '".$rowDup['products_sold']."',
						products_qt = '".$rowDup['products_qt']."',
						products_deee = '".$rowDup['products_deee']."',
						products_sup_shipping = '".$rowDup['products_sup_shipping']."',
						products_caddie_display = '".$rowDup['products_caddie_display']."',
						products_forsale = '".$rowDup['products_forsale']."',
						
						products_delay_1 = '".$rowDup['products_delay_1']."',
						products_delay_2 = '".$rowDup['products_delay_2']."',
						products_delay_1a = '".$rowDup['products_delay_1a']."',
						products_delay_2a = '".$rowDup['products_delay_2a']."',
						products_delay_1b = '".$rowDup['products_delay_1b']."',
						products_delay_2b = '".$rowDup['products_delay_2b']."',
						
						products_meta_title_1 ='".$rowDup['products_meta_title_1']."',
						products_meta_title_2 ='".$rowDup['products_meta_title_2']."',
						products_meta_title_3 ='".$rowDup['products_meta_title_3']."',
						products_meta_description_1 ='".$rowDup['products_meta_description_1']."',
						products_meta_description_2 ='".$rowDup['products_meta_description_2']."',
						products_meta_description_3 ='".$rowDup['products_meta_description_3']."',
						
						products_ean = '".$rowDup['products_ean']."'
						");
						
		$message.= "<div align='center'>".A4.": <b>".$refDup."</b></div>";
		$end=1;
	}
 
	$message2 = "<b>".$rowDup['products_name_'.$_SESSION['lang']]."</b> ".A1;
	$message2.= $message;
	
 
	if($rowDup['products_options'] == "yes") {
		$message.= "<br><br>".A5." ".$rowDup['products_name_'.$_SESSION['lang']]." ".A6.".";
		$message.= "<br>".A7." ".$rowDup['products_name_'.$_SESSION['lang']].", ".A8." <a href=\"opties_dupliceren.php?id=".$_GET['id']."\">".A9."</a> ".A10.": ".$rowDup['products_name_'.$_SESSION['lang']]." [REF: ".$refDup."].";
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
<p align="center" class="largeBold"><?php print A2;?></p>

<?php
if(isset($message2) AND $message2!=='') print "<p align='center'>".$message2."</p>";
if(!isset($end)) {
?>
<FORM action="artikel_dupliceren.php" method="GET">
<center>
<table width="90%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE"><tr><td><center>
Aantal duplicaties <INPUT type="text" size="3" class="vullen" VALUE="" NAME="nb">
	<INPUT type="hidden" VALUE="<?php print $_GET['id'];?>" NAME="id">
	<INPUT type="submit" class="knop" VALUE="<?php print DUPLICATE;?>">

</form>
<?php
}
?>


<FORM action="artikel_wijzigen.php?list=1" method="POST">
<br><center><INPUT type="submit" class="knop" VALUE="<?php print A3;?>">
</tr></td></table>


</body>
</html>




