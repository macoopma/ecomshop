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
$message = "";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php

if(isset($_POST['selectProductsId']) and count($_POST['selectProductsId']) > 0) {
    $rr = implode("|",$_POST['selectProductsId']);
   }
   else {
    $rr = "";
   }

if($_POST['weight']=="0.00") $_POST['weight']="1.00";

// Controleer gewicht
if(isset($_POST['download']) and $_POST['download']=='yes') {
  $_POST['weight'] = 0;
}

// controileer ref
if(empty($_POST['ref'])) {
         $message .= A2A."<br>";
         $checkRef2 = "notok";
         $checkRef = "notok";
}
else {
    $_POST['ref'] = str_replace("\'","",$_POST['ref']);
    $_POST['ref'] = str_replace("'","",$_POST['ref']);
    $_POST['ref'] = str_replace(",","",$_POST['ref']);
    $_POST['ref'] = str_replace("+","",$_POST['ref']);
    $checkRef2 = "ok";
    $query = mysql_query("SELECT products_ref FROM products WHERE products_ref = '".$_POST['ref']."'");
    $result = mysql_num_rows($query);
    if($result>0) {
      $message .= A2."<br>";
      $checkRef = "notok";
    }
    else {
      $checkRef = "ok";
   }
}

 
if(empty($_POST['ImageChange'])) {
   $_POST['image']="no";
   $_POST['ImageChange'] = "im/no_image_small.gif";
   $img2 = "ok";
}
else {
   if(substr($_POST['ImageChange'], 0, 4)=="http") {
      if(substr($_POST['ImageChange'],-4)==".gif" OR substr($_POST['ImageChange'],-4)==".jpg" OR substr($_POST['ImageChange'],-4)==".png") {
         $_POST['ImageChange'] = $_POST['ImageChange'];
         $img2="ok";
      }
      else {
         $message.= A3."<br>";
         $img2="notok";
      }
   }
   else {
	     $_POST['ImageChange'] = str_replace("\"","",$_POST['ImageChange']);
         $explodeImageFile = explode("\\",$_POST['ImageChange']);
         $_POST['ImageChange'] = end($explodeImageFile);

      if(strlen($_POST['ImageChange']) > 4 and substr($_POST['ImageChange'],-4) == ".gif" or substr($_POST['ImageChange'],-4) == ".jpg" or  substr($_POST['ImageChange'],-4) == ".png") {
         $img2="ok";
         $_POST['ImageChange'] = "im/artikelen/".$_POST['ImageChange'];
      }
      else {
         $message.= A3."<br>";
         $img2="notok";
      }
   }
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
			$img2="ok";
			$_POST['image']="yes";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['ImageChange'] = "im/no_image_small.gif";
			$img2="notok";
			$_POST['image']="no";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
			$_POST['ImageChange'] = "im/no_image_small.gif";
			$img2="notok";
			$_POST['image']="no";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
	}
}


 
if(empty($_POST['image2'])) {
   $_POST['image2']="";
   $img3="ok";
}
else {
   if(substr($_POST['image2'], 0, 4)=="http") {
      if(substr($_POST['image2'],-4)==".gif" OR substr($_POST['image2'],-4)==".jpg" OR substr($_POST['image2'],-4)==".png") {
         $_POST['image2'] = $_POST['image2'];
         $img3="ok";
      }
      else {
         $message.= A4."<br>";
         $img3="notok";
      }
   }
   else {
	     $_POST['image2'] = str_replace("\"","",$_POST['image2']);
         $explodeImageFile2 = explode("\\",$_POST['image2']);
         $_POST['image2'] = end($explodeImageFile2);

      if(strlen($_POST['image2']) > 4 and substr($_POST['image2'],-4) == ".gif" or substr($_POST['image2'],-4) == ".jpg" or  substr($_POST['image2'],-4) == ".png") {
         $img3="ok";
         $_POST['image2'] = "im/artikelen/".$_POST['image2']."";
      }
      else {
         $message.= A4."<br>";
         $img3="notok";
      }
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
			$img3="ok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image2'] = "";
			$img3="notok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image2'] = "";
		$img3="notok";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
	}
}

 
if(empty($_POST['image3'])) {
   $_POST['image3']="";
   $img4 = "ok";
}
else {
   if(substr($_POST['image3'], 0, 4)=="http") {
      if(substr($_POST['image3'],-4)==".gif" OR substr($_POST['image3'],-4)==".jpg" OR substr($_POST['image3'],-4)==".png") {
         $_POST['image3'] = $_POST['image3'];
         $img4="ok";
      }
      else {
         $message .= A5."<br>";
         $img4="notok";
      }
   }
   else {
	     $_POST['image3'] = str_replace("\"","",$_POST['image3']);
         $explodeImageFile3 = explode("\\",$_POST['image3']);
         $_POST['image3'] = end($explodeImageFile3);

      if(strlen($_POST['image3']) > 4 and substr($_POST['image3'],-4) == ".gif" or substr($_POST['image3'],-4) == ".jpg" or  substr($_POST['image3'],-4) == ".png") {
         $img4 = "ok";
         $_POST['image3'] = "im/artikelen/".$_POST['image3']."";
      }
      else {
         $message .= A5."<br>";
         $img4="notok";
      }
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
			$img4="ok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image3'] = "";
			$img4="notok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image3'] = "";
		$img4="notok";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier2."</span> ".A_ECHOUE."</div>";
	}
}


 
if(empty($_POST['image4'])) {
   $_POST['image4']="";
   $img5 = "ok";
}
else {
   if(substr($_POST['image4'], 0, 4)=="http") {
      if(substr($_POST['image4'],-4)==".gif" OR substr($_POST['image4'],-4)==".jpg" OR substr($_POST['image4'],-4)==".png") {
         $_POST['image4'] = $_POST['image4'];
         $img5="ok";
      }
      else {
         $message .= A6."<br>";
         $img5="notok";
      }
   }
   else {
	     $_POST['image4'] = str_replace("\"","",$_POST['image4']);
         $explodeImageFile4 = explode("\\",$_POST['image4']);
         $_POST['image4'] = end($explodeImageFile4);

      if(strlen($_POST['image4']) > 4 and substr($_POST['image4'],-4) == ".gif" or substr($_POST['image4'],-4) == ".jpg" or  substr($_POST['image4'],-4) == ".png") {
         $img5 = "ok";
         $_POST['image4'] = "im/artikelen/".$_POST['image4']."";
      }
      else {
         $message .= A6."<br>";
         $img5="notok";
      }
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
			$img5="ok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image4'] = "";
			$img5="notok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image4'] = "";
		$img5="notok";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier3."</span> ".A_ECHOUE."</div>";
	}
}

 
if(empty($_POST['image5'])) {
   $_POST['image5']="";
   $img6 = "ok";
}
else {
   if(substr($_POST['image5'], 0, 4)=="http") {
      if(substr($_POST['image5'],-4)==".gif" OR substr($_POST['image5'],-4)==".jpg" OR substr($_POST['image5'],-4)==".png") {
         $_POST['image5'] = $_POST['image5'];
         $img6="ok";
      }
      else {
         $message .= A7."<br>";
         $img6="notok";
      }
   }
   else {
	     $_POST['image5'] = str_replace("\"","",$_POST['image5']);
         $explodeImageFile5 = explode("\\",$_POST['image5']);
         $_POST['image5'] = end($explodeImageFile5);

      if(strlen($_POST['image5']) > 4 and substr($_POST['image5'],-4) == ".gif" or substr($_POST['image5'],-4) == ".jpg" or substr($_POST['image5'],-4) == ".png") {
         $img6 = "ok";
         $_POST['image5'] = "im/artikelen/".$_POST['image5']."";
      }
      else {
         $message .= A7."<br>";
         $img6="notok";
      }
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
			$img6="ok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier4."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image5'] = "";
			$img6="notok";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier4."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image5'] = "";
		$img6="notok";
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

 
if($img2 == 'ok' and $img3 == 'ok' and $img4 == 'ok' and $img5 == 'ok' and $img6 == 'ok' and $checkRef == 'ok' and $checkRef2 == 'ok' and $delay1=="ok") {

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

$_POST['garantie_1'] = str_replace("'","&#146;",$_POST['garantie_1']);
$_POST['garantie_2'] = str_replace("'","&#146;",$_POST['garantie_2']);
$_POST['garantie_3'] = str_replace("'","&#146;",$_POST['garantie_3']);

$_POST['note_1'] = str_replace("\'","&#146;",$_POST['note_1']);
$_POST['note_2'] = str_replace("\'","&#146;",$_POST['note_2']);
$_POST['note_3'] = str_replace("\'","&#146;",$_POST['note_3']);

$_POST['note_1'] = str_replace("'","&#146;",$_POST['note_1']);
$_POST['note_2'] = str_replace("'","&#146;",$_POST['note_2']);
$_POST['note_3'] = str_replace("'","&#146;",$_POST['note_3']);

$_POST['spec_1'] = str_replace("\'","&#146;",$_POST['spec_1']);
$_POST['spec_2'] = str_replace("\'","&#146;",$_POST['spec_2']);
$_POST['spec_3'] = str_replace("\'","&#146;",$_POST['spec_3']);

$_POST['spec_1'] = str_replace("'","&#146;",$_POST['spec_1']);
$_POST['spec_2'] = str_replace("'","&#146;",$_POST['spec_2']);
$_POST['spec_3'] = str_replace("'","&#146;",$_POST['spec_3']);

$_POST['optionNote_1'] = str_replace("\'","&#146;",$_POST['optionNote_1']);
$_POST['optionNote_2'] = str_replace("\'","&#146;",$_POST['optionNote_2']);
$_POST['optionNote_3'] = str_replace("\'","&#146;",$_POST['optionNote_3']);

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

mysql_query('INSERT INTO products
             SET
             categories_id=\''.$_POST['subgroup'].'\',

             products_name_1=\''.$_POST['article_name_1'].'\',
             products_name_2=\''.$_POST['article_name_2'].'\',
             products_name_3=\''.$_POST['article_name_3'].'\',

             fournisseurs_id=\''.$_POST['company'].'\',
             afficher_fournisseur=\''.$_POST['aff_four'].'\',

             fabricant_id=\''.$_POST['marque'].'\',
             afficher_fabricant=\''.$_POST['aff_fab'].'\',

             products_ref=\''.$_POST['ref'].'\',

             products_visible=\''.$_POST['visu'].'\',

             products_desc_1=\''.$_POST['spec_1'].'\',
             products_desc_2=\''.$_POST['spec_2'].'\',
             products_desc_3=\''.$_POST['spec_3'].'\',

             products_garantie_1=\''.$_POST['garantie_1'].'\',
             products_garantie_2=\''.$_POST['garantie_2'].'\',
             products_garantie_3=\''.$_POST['garantie_3'].'\',


             products_note_1=\''.$_POST['note_1'].'\',
             products_note_2=\''.$_POST['note_2'].'\',
             products_note_3=\''.$_POST['note_3'].'\',

             products_price=\''.$_POST['price'].'\',

             products_weight=\''.$_POST['weight'].'\',

             products_options=\'no\',

             products_option_note_1=\''.$_POST['optionNote_1'].'\',
             products_option_note_2=\''.$_POST['optionNote_2'].'\',
             products_option_note_3=\''.$_POST['optionNote_3'].'\',

             products_taxable=\''.$_POST['taxable'].'\',

             products_tax=\''.$_POST['taxNum'].'\',

             products_image=\''.$_POST['ImageChange'].'\',

             products_image2=\''.$_POST['image2'].'\',
             products_image3=\''.$_POST['image3'].'\',
             products_image4=\''.$_POST['image4'].'\',
             products_image5=\''.$_POST['image5'].'\',

             products_im=\''.$_POST['image'].'\',

             products_date_added=\''.$_POST['date'].'\',
             
             products_download =\''.$_POST['download'].'\',
             
             products_related =\''.$rr.'\',
             products_exclusive =\''.$_POST['exclusive'].'\',
             
             products_download_name =\''.$_POST['file_name'].'\',
             products_deee =\''.$_POST['deee'].'\',
             products_ean =\''.$_POST['ean'].'\',
             products_sup_shipping =\''.$_POST['sup_shipping'].'\',
             products_caddie_display =\''.$_POST['display_caddie'].'\',
             products_forsale =\''.$_POST['forsale'].'\',
             
             products_delay_1 =\''.$_POST['day1'].'\',
             products_delay_2 =\''.$_POST['day2'].'\',
             products_delay_1a =\''.$_POST['day1a'].'\',
             products_delay_2a =\''.$_POST['day2a'].'\',
             products_delay_1b =\''.$_POST['day1b'].'\',
             products_delay_2b =\''.$_POST['day2b'].'\',

             products_meta_title_1 =\''.$_POST['meta_title_1'].'\',
             products_meta_title_2 =\''.$_POST['meta_title_2'].'\',
             products_meta_title_3 =\''.$_POST['meta_title_3'].'\',
             products_meta_description_1 =\''.$_POST['meta_description_1'].'\',
             products_meta_description_2 =\''.$_POST['meta_description_2'].'\',
             products_meta_description_3 =\''.$_POST['meta_description_3'].'\',

             products_qt=\''.$_POST['stock'].'\'
             ');
             
             if($_SESSION['lang']=="1") $name = $_POST['article_name_1'];
             if($_SESSION['lang']=="2") $name = $_POST['article_name_2'];
             if($_SESSION['lang']=="3") $name = $_POST['article_name_3'];

             $message.= "".A8." <b>".$name."</b> ".A9."";
?>
<table border="0" align="center" cellpadding="5" cellspacing = "0" width='700' class='TABLE'>
       <tr>
        <td align="center" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><b><?php print A10;?></b></td>
       </tr>
       <tr>
        <td align="center">
         <?php
         $query = mysql_query("SELECT products_id FROM products WHERE products_ref = '".$_POST['ref']."'");
         $result = mysql_fetch_array($query);
         print "<a href=\"opties_maken.php?id=".$result['products_id']."\"><b>".A11."</b></a>";

         ?>

        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="artikel_toevoegen.php"><b><?php print A12;?></b></a>
        </td>
       </tr>
</table>
<?php
}
else{
?>
<center><table border="0" align="center" cellpadding="5" cellspacing = "0" width="700" class="TABLE">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message; ?></td>
       </tr>
        <tr>
        <td align="center" colspan="2"><b><a href="artikel_toevoegen.php"><?php print BACK;?></a></b></td>
       </tr>
</table>
<?php
}
?>
  </body>
  </html>
