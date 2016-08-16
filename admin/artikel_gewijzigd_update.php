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
$message="";

$queryZZ = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_id= '".$_POST['id']."'");
$rowZZ = mysql_fetch_array($queryZZ);
$nameP = $rowZZ['products_name_'.$_SESSION['lang']];

 
if(empty($_POST['sold'])) {$_POST['sold'] = 0;}

 
if(isset($_POST['selectProductsId']) and count($_POST['selectProductsId']) > 0) {
   $rr = implode("|",$_POST['selectProductsId']);
}
else {
   $rr = "";
}

if(isset($_POST['selectCatId']) and count($_POST['selectCatId']) > 0) {
     
    $rrCat = "";
    $numm = count($_POST['selectCatId']);
	    for($i=0; $i<$numm; $i++) {
	    	$rrCat .= "|".$_POST['selectCatId'][$i]."|";
		}
}
else {
   $rrCat = "";
}


$queryPromo = mysql_query("SELECT products_id, products_price, products_name_".$_SESSION['lang']." FROM products WHERE products_id= '".$_POST['id']."'");
$rowPromo = mysql_fetch_array($queryPromo);
if($rowPromo['products_price']!==$_POST['price']) {
   $queryPromo2 = mysql_query("SELECT specials_id FROM specials WHERE products_id= '".$_POST['id']."'");
   if(mysql_num_rows($queryPromo2) > 0) {
         $rowPromo2Result = mysql_fetch_array($queryPromo2);
         $message .= ARTICLE_EN_PROMO;
         $message.= "- ".$rowPromo['products_name_'.$_SESSION['lang']]."<br><br>";
         $message .= "<a href='promoties_wijzigen_details.php?id=".$rowPromo2Result['specials_id']."'>".MODIFIEZ_OU_SUPPRIMER_PROMO."</a><br>";
         $checkPromo = $rowPromo['products_id'];
   }
}


if(isset($_POST['download']) AND $_POST['download']=='yes') $_POST['weight'] = 0;


if(empty($_POST['ref'])) {
         $message .= A2A."<br>";
         $checkRef2 = "notok";
         $checkRef = "notok";
 }
 else {
     $checkRef2 = "ok";
            $_POST['ref'] = str_replace("\'","",$_POST['ref']);
            $_POST['ref'] = str_replace("'","",$_POST['ref']);
            $_POST['ref'] = str_replace(",","",$_POST['ref']);
            $_POST['ref'] = str_replace("+","",$_POST['ref']);
            
     $query = mysql_query("SELECT products_ref
                           FROM products
                           WHERE products_ref = '".$_POST['ref']."'
                           AND products_id != '".$_POST['id']."'");
     $result = mysql_num_rows($query);
	 if($result>0) {
     	$message .= A2."<br>";
     	$checkRef = "notok";
     }
     else {
     	$checkRef = "ok";
     }
 }


if($_POST['ImageChange']=="") {
   $_POST['image'] = "no";
   $_POST['ImageChange'] = "im/no_image_small.gif";
}


if(isset($_FILES["uploadMainImage"]["name"]) AND !empty($_FILES["uploadMainImage"]["name"])) {
	 
	$nomFichier    = $_FILES["uploadMainImage"]["name"];
	 
	$nomTemporaire = $_FILES["uploadMainImage"]["tmp_name"];
	 
	$typeFichier   = $_FILES["uploadMainImage"]["type"];
	 
	$poidsFichier  = $_FILES["uploadMainImage"]["size"];
	 
	$codeErreur    = $_FILES["uploadMainImage"]["error"];
	 
	$chemin = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", strtolower($nomFichier))) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			if(preg_match("#.JPG$|.GIF$|.PNG$|.SWF$#", $nomFichier))
				rename($chemin.$nomFichier,$chemin.strtolower($nomFichier));
			$_POST['ImageChange'] = str_replace("../","",$chemin).strtolower($nomFichier);
			$_POST['image']="yes";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['ImageChange'] = "im/no_image_small.gif";
			$_POST['image']="no";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['ImageChange'] = "im/no_image_small.gif";
		$_POST['image']="no";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
	}
}

if(isset($_FILES["uploadExtraImage1"]["name"]) AND !empty($_FILES["uploadExtraImage1"]["name"])) {
	 
	$nomFichier1    = $_FILES["uploadExtraImage1"]["name"];
	 
	$nomTemporaire1 = $_FILES["uploadExtraImage1"]["tmp_name"];
	 
	$typeFichier1   = $_FILES["uploadExtraImage1"]["type"];
	 
	$poidsFichier1  = $_FILES["uploadExtraImage1"]["size"];
	 
	$codeErreur1    = $_FILES["uploadExtraImage1"]["error"];
	 
	$chemin1 = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", strtolower($nomFichier1))) {
		if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
			if(preg_match("#.JPG$|.GIF$|.PNG$|.SWF$#", $nomFichier1))
				rename($chemin1.$nomFichier1,$chemin1.strtolower($nomFichier1));
			$_POST['image2'] = str_replace("../","",$chemin1).strtolower($nomFichier1);
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image2'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image2'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
	}
}

if(isset($_FILES["uploadExtraImage2"]["name"]) AND !empty($_FILES["uploadExtraImage2"]["name"])) {
	 
	$nomFichier2    = $_FILES["uploadExtraImage2"]["name"];
	 
	$nomTemporaire2 = $_FILES["uploadExtraImage2"]["tmp_name"];
	 
	$typeFichier2   = $_FILES["uploadExtraImage2"]["type"];
	 
	$poidsFichier2  = $_FILES["uploadExtraImage2"]["size"];
	 
	$codeErreur2    = $_FILES["uploadExtraImage2"]["error"];
	 
	$chemin2 = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", strtolower($nomFichier2))) {
		if(copy($nomTemporaire2, $chemin2.$nomFichier2)) {
			if(preg_match("#.JPG$|.GIF$|.PNG$|.SWF$#", $nomFichier2))
				rename($chemin2.$nomFichier2,$chemin2.strtolower($nomFichier2));
			$_POST['image3'] = str_replace("../","",$chemin2).strtolower($nomFichier2);
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image3'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image3'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_ECHOUE."</div>";
	}
}

if(isset($_FILES["uploadExtraImage3"]["name"]) AND !empty($_FILES["uploadExtraImage3"]["name"])) {
	 
	$nomFichier3    = $_FILES["uploadExtraImage3"]["name"];
	 
	$nomTemporaire3 = $_FILES["uploadExtraImage3"]["tmp_name"];
	 
	$typeFichier3   = $_FILES["uploadExtraImage3"]["type"];
	 
	$poidsFichier3  = $_FILES["uploadExtraImage3"]["size"];
	 
	$codeErreur3    = $_FILES["uploadExtraImage3"]["error"];
	 
	$chemin3 = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", strtolower($nomFichier3))) {
		if(copy($nomTemporaire3, $chemin3.$nomFichier3)) {
			if(preg_match("#.JPG$|.GIF$|.PNG$|.SWF$#", $nomFichier3))
				rename($chemin3.$nomFichier3,$chemin3.strtolower($nomFichier3));
			$_POST['image4'] = str_replace("../","",$chemin3).strtolower($nomFichier3);
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image4'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image4'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_ECHOUE."</div>";
	}
}

if(isset($_FILES["uploadExtraImage4"]["name"]) AND !empty($_FILES["uploadExtraImage4"]["name"])) {
	 
	$nomFichier4    = $_FILES["uploadExtraImage4"]["name"];
	 
	$nomTemporaire4 = $_FILES["uploadExtraImage4"]["tmp_name"];
	 
	$typeFichier4   = $_FILES["uploadExtraImage4"]["type"];
	 
	$poidsFichier4  = $_FILES["uploadExtraImage4"]["size"];
	 
	$codeErreur4    = $_FILES["uploadExtraImage4"]["error"];
	 
	$chemin4 = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", strtolower($nomFichier4))) {
		if(copy($nomTemporaire4, $chemin4.$nomFichier4)) {
			if(preg_match("#.JPG$|.GIF$|.PNG$|.SWF$#", $nomFichier4))
				rename($chemin4.$nomFichier4,$chemin4.strtolower($nomFichier4));
			$_POST['image5'] = str_replace("../","",$chemin4).strtolower($nomFichier4);
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier4."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image5'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier4."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image5'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier4."</span> ".A_ECHOUE."</div>";
	}
}

if(!isset($_POST['day1']) OR $_POST['day1']==""  OR $_POST['day1']==" " OR !is_numeric($_POST['day1']) 
	OR !isset($_POST['day2']) OR $_POST['day2']=="" OR $_POST['day2']==" " OR !is_numeric($_POST['day2'])
	OR !isset($_POST['day1a']) OR $_POST['day1a']=="" OR $_POST['day1a']==" " OR !is_numeric($_POST['day1a'])
	OR !isset($_POST['day2a']) OR $_POST['day2a']=="" OR $_POST['day2a']==" " OR !is_numeric($_POST['day2a'])
	OR !isset($_POST['day1b']) OR $_POST['day1b']=="" OR $_POST['day1b']==" " OR !is_numeric($_POST['day1b'])
	OR !isset($_POST['day2b']) OR $_POST['day2b']=="" OR $_POST['day2b']==" " OR !is_numeric($_POST['day2b'])) {
	$delay1 = "notok";
	$message .= ENTRER_DELAI_EXPEDITION."<br>";
}
else {
	$delay1 = "ok";
}


if($checkRef == 'ok' AND $checkRef2 == 'ok' AND $delay1=='ok') {
$_POST['price'] = str_replace(",",".",$_POST['price']);
$_POST['price'] = sprintf("%0.2f",$_POST['price']);

$_POST['deee'] = str_replace(",",".",$_POST['deee']);
$_POST['deee'] = sprintf("%0.2f",$_POST['deee']);

$_POST['sup_shipping'] = str_replace(",",".",$_POST['sup_shipping']);
$_POST['sup_shipping'] = sprintf("%0.2f",$_POST['sup_shipping']);

$_POST['weight'] = str_replace(",",".",$_POST['weight']);
$_POST['weight'] = sprintf("%0.2f",$_POST['weight']);

$_POST['taxNum'] = str_replace(",",".",$_POST['taxNum']);
$_POST['taxNum'] = sprintf("%0.2f",$_POST['taxNum']);

$_POST['article_name_1'] = str_replace("\'","&#146;",$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace("\'","&#146;",$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace("\'","&#146;",$_POST['article_name_3']);

$_POST['article_name_1'] = str_replace("'","&#146;",$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace("'","&#146;",$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace("'","&#146;",$_POST['article_name_3']);

$_POST['article_name_1'] = str_replace('\"','',$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace('\"','',$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace('\"','',$_POST['article_name_3']);

$_POST['article_name_1'] = str_replace('"','',$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace('"','',$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace('"','',$_POST['article_name_3']);

$_POST['ref'] = str_replace("+","&#134;",$_POST['ref']);

$_POST['article_name_1'] = str_replace("+","&#134;",$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace("+","&#134;",$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace("+","&#134;",$_POST['article_name_3']);

$_POST['article_name_1'] = str_replace(",","",$_POST['article_name_1']);
$_POST['article_name_2'] = str_replace(",","",$_POST['article_name_2']);
$_POST['article_name_3'] = str_replace(",","",$_POST['article_name_3']);

$_POST['garantie_1'] = str_replace("\'","&#146;",$_POST['garantie_1']);
$_POST['garantie_2'] = str_replace("\'","&#146;",$_POST['garantie_2']);
$_POST['garantie_3'] = str_replace("\'","&#146;",$_POST['garantie_3']);

$_POST['note_1'] = str_replace("\'","&#146;",$_POST['note_1']);
$_POST['note_2'] = str_replace("\'","&#146;",$_POST['note_2']);
$_POST['note_3'] = str_replace("\'","&#146;",$_POST['note_3']);

$_POST['spec_1'] = str_replace("\'","&#146;",$_POST['spec_1']);
$_POST['spec_2'] = str_replace("\'","&#146;",$_POST['spec_2']);
$_POST['spec_3'] = str_replace("\'","&#146;",$_POST['spec_3']);

$_POST['optionNote_1'] = str_replace("\'","&#146;",$_POST['optionNote_1']);
$_POST['optionNote_2'] = str_replace("\'","&#146;",$_POST['optionNote_2']);
$_POST['optionNote_3'] = str_replace("\'","&#146;",$_POST['optionNote_3']);

$_POST['garantie_1'] = str_replace("'","&#146;",$_POST['garantie_1']);
$_POST['garantie_2'] = str_replace("'","&#146;",$_POST['garantie_2']);
$_POST['garantie_3'] = str_replace("'","&#146;",$_POST['garantie_3']);

$_POST['note_1'] = str_replace("'","&#146;",$_POST['note_1']);
$_POST['note_2'] = str_replace("'","&#146;",$_POST['note_2']);
$_POST['note_3'] = str_replace("'","&#146;",$_POST['note_3']);

$_POST['spec_1'] = str_replace("'","&#146;",$_POST['spec_1']);
$_POST['spec_2'] = str_replace("'","&#146;",$_POST['spec_2']);
$_POST['spec_3'] = str_replace("'","&#146;",$_POST['spec_3']);

$_POST['optionNote_1'] = str_replace("'","&#146;",$_POST['optionNote_1']);
$_POST['optionNote_2'] = str_replace("'","&#146;",$_POST['optionNote_2']);
$_POST['optionNote_3'] = str_replace("'","&#146;",$_POST['optionNote_3']);

$excludeCar = array(" ",",",".");
$_POST['day1'] = str_replace($excludeCar,"",$_POST['day1']);
$_POST['day2'] = str_replace($excludeCar,"",$_POST['day2']);
$_POST['day1a'] = str_replace($excludeCar,"",$_POST['day1a']);
$_POST['day2a'] = str_replace($excludeCar,"",$_POST['day2a']);
$_POST['day1b'] = str_replace($excludeCar,"",$_POST['day1b']);
$_POST['day2b'] = str_replace($excludeCar,"",$_POST['day2b']);

$_POST['meta_title_1'] = strip_tags($_POST['meta_title_1']);
$_POST['meta_title_2'] = strip_tags($_POST['meta_title_2']);
$_POST['meta_title_3'] = strip_tags($_POST['meta_title_3']);
$_POST['meta_description_1'] = strip_tags($_POST['meta_description_1']);
$_POST['meta_description_2'] = strip_tags($_POST['meta_description_2']);
$_POST['meta_description_3'] = strip_tags($_POST['meta_description_3']);

$excludeCar2 = array("'","\'");
$_POST['meta_title_1'] = str_replace($excludeCar2,"&#146;",$_POST['meta_title_1']);
$_POST['meta_title_2'] = str_replace($excludeCar2,"&#146;",$_POST['meta_title_2']);
$_POST['meta_title_3'] = str_replace($excludeCar2,"&#146;",$_POST['meta_title_3']);
$_POST['meta_description_1'] = str_replace($excludeCar2,"&#146;",$_POST['meta_description_1']);
$_POST['meta_description_2'] = str_replace($excludeCar2,"&#146;",$_POST['meta_description_2']);
$_POST['meta_description_3'] = str_replace($excludeCar2,"&#146;",$_POST['meta_description_3']);

mysql_query("UPDATE products
				SET
				products_name_1 = '".$_POST['article_name_1']."',
				products_name_2 = '".$_POST['article_name_2']."',
				products_name_3 = '".$_POST['article_name_3']."',
				
				products_ref = '".$_POST['ref']."',
				
				fournisseurs_id = '".$_POST['company']."',
				afficher_fournisseur = '".$_POST['aff_four']."',
				
				fabricant_id = '".$_POST['marque']."',
				afficher_fabricant = '".$_POST['aff_fab']."',
				
				products_desc_1 = '".$_POST['spec_1']."',
				products_desc_2 = '".$_POST['spec_2']."',
				products_desc_3 = '".$_POST['spec_3']."',
				
				categories_id = '".$_POST['category']."',
				categories_id_bis = '".$rrCat."',
				products_visible = '".$_POST['visu']."',
				
				products_garantie_1 ='".$_POST['garantie_1']."',
				products_garantie_2 ='".$_POST['garantie_2']."',
				products_garantie_3 ='".$_POST['garantie_3']."',
				
				products_note_1 ='".$_POST['note_1']."',
				products_note_2 ='".$_POST['note_2']."',
				products_note_3 ='".$_POST['note_3']."',
				
				products_price ='".$_POST['price']."',
				products_weight ='".$_POST['weight']."',
				
				products_option_note_1 ='".$_POST['optionNote_1']."',
				products_option_note_2 ='".$_POST['optionNote_2']."',
				products_option_note_3 ='".$_POST['optionNote_3']."',
				
				products_taxable ='".$_POST['taxable']."',
				products_tax ='".$_POST['taxNum']."',
				products_date_added ='".$_POST['article_date']."',
				products_im ='".$_POST['image']."',
				products_image ='".$_POST['ImageChange']."',
				products_image2 ='".$_POST['image2']."',
				products_image3 ='".$_POST['image3']."',
				products_image4 ='".$_POST['image4']."',
				products_image5 ='".$_POST['image5']."',
				products_viewed ='".$_POST['article_viewed']."',
				products_added ='".$_POST['article_added']."',
				
				products_download ='".$_POST['download']."',
				
				products_download_name ='".$_POST['file_name']."',
				products_related ='".$rr."',
				products_exclusive ='".$_POST['exclusive']."',
				products_sold = '".$_POST['sold']."',
				products_deee = '".$_POST['deee']."',
				products_ean = '".$_POST['ean']."',
				
				products_sup_shipping = '".$_POST['sup_shipping']."',
				products_caddie_display = '".$_POST['display_caddie']."',
				products_forsale = '".$_POST['forsale']."',
				
				products_delay_1 = '".$_POST['day1']."',
				products_delay_2 = '".$_POST['day2']."',
				products_delay_1a = '".$_POST['day1a']."',
				products_delay_2a = '".$_POST['day2a']."',
				products_delay_1b = '".$_POST['day1b']."',
				products_delay_2b = '".$_POST['day2b']."',                           
				
				products_meta_title_1 ='".$_POST['meta_title_1']."',
				products_meta_title_2 ='".$_POST['meta_title_2']."',
				products_meta_title_3 ='".$_POST['meta_title_3']."',
				products_meta_description_1 ='".$_POST['meta_description_1']."',
				products_meta_description_2 ='".$_POST['meta_description_2']."',
				products_meta_description_3 ='".$_POST['meta_description_3']."',
				
				products_qt ='".$_POST['stock']."'
				
				WHERE products_id = '".$_POST['id']."'");
				
$message.= "<p align='center'>".UPDATE_OK."</p>";

if(isset($checkPromo)) {
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table border="0" align="center" cellpadding="5" cellspacing = "0">
	<tr>
	<td align="center" class="fontrouge" colspan="2"><?php print $message; ?></td>
	</tr>
	<tr>
	<td align="center" colspan="2"><b><a href="artikel_wijzigen_details.php?id=<?php print $checkPromo;?>"><?php print BACK;?></a></b></td>
	</tr>
	</table>
</body>
</html>

<?php
}
else {

?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?> - <?php print $nameP;?> -</p>

<table border="0" align="center" cellpadding="5" cellspacing="0" class='TABLE' width='700'>
       <tr>
        <td align="center" class="fontrouge" colspan="2">
		<table border="0" align="center" cellpadding="8" cellspacing="0" width='100%'>
			<tr><td align="center">
			<?php print $message;?>
			</td>
			</tr>
		</table>
		</td>
       </tr>
        <tr>
        <td align="center" colspan="2">
        <form action="artikel_wijzigen_details.php?id=<?php print $_POST['id'];?>" method="GET">
			<input type="submit" class='knop' value="<?php print BACK;?>">
			<input type="hidden" name="id" value="<?php print $_POST['id'];?>">
		</form>
		</td>
       </tr>
</table>
</body>
</html>
<?php
}
?>

<?php
}
else {
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?> - <?php print $nameP;?> -</p>

<table border="0" align="center" cellpadding="5" cellspacing="0" class='TABLE' width='700'>
       <tr>
        <td align="center" class="fontrouge" colspan="2">
		<table border="0" align="center" cellpadding="8" cellspacing="0" width='100%'>
			<tr><td align="center">

			<?php print $message;?>
			</td>
			</tr>
		</table>
		</td>
       </tr>
        <tr>
        <td align="center" colspan="2">
        <form action="artikel_wijzigen_details.php?id=<?php print $_POST['id'];?>" method="GET">
			<input type="submit" value="<?php print BACK;?>">
			<input type="hidden" name="id" value="<?php print $_POST['id'];?>">
		</form>
		</td>
       </tr>
</table>
</body>
</html>
<?php
}
?>
