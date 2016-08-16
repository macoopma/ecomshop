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

 
function recurs30($origin) {
	global $jj;
	$findOriginQuery = mysql_query("SELECT categories_id, parent_id, categories_noeud, categories_visible FROM categories WHERE parent_id = '".$origin."'");
	$NbreOrigin = mysql_num_rows($findOriginQuery);
	if($NbreOrigin>0) {
    	while($findOrigin = mysql_fetch_array($findOriginQuery)) {
        	$jj[] = $findOrigin['categories_id'];
        	recurs30($findOrigin['categories_id']);
    	}
		return $jj;
    }
}

 
if(isset($catTo)) unset($catTo);
$catTo = recurs30($_POST['id']);
if(isset($catTo)) {$catTo = array_unique($catTo);}

 
$noPass = array("\'","'");
$_POST['name_1'] = strip_tags($_POST['name_1']);
$_POST['name_2'] = strip_tags($_POST['name_2']);
$_POST['name_3'] = strip_tags($_POST['name_3']);
$_POST['name_1'] = str_replace($noPass,"&#8217;",$_POST['name_1']);
$_POST['name_2'] = str_replace($noPass,"&#8217;",$_POST['name_2']);
$_POST['name_3'] = str_replace($noPass,"&#8217;",$_POST['name_3']);
if(isset($_POST['comment_1'])) {$_POST['comment_1'] = str_replace($noPass,"&#8217;",$_POST['comment_1']);} else {$_POST['comment_1']="";}
if(isset($_POST['comment_2'])) {$_POST['comment_2'] = str_replace($noPass,"&#8217;",$_POST['comment_2']);} else {$_POST['comment_2']="";}
if(isset($_POST['comment_3'])) {$_POST['comment_3'] = str_replace($noPass,"&#8217;",$_POST['comment_3']);} else {$_POST['comment_3']="";}
if(isset($_POST['CatOrder']) AND $_POST['CatOrder']=="0") $_POST['CatOrder']="0.1";

$_POST['meta_description_1'] = strip_tags($_POST['meta_description_1']);
$_POST['meta_description_2'] = strip_tags($_POST['meta_description_2']);
$_POST['meta_description_3'] = strip_tags($_POST['meta_description_3']);
$_POST['meta_title_1'] = strip_tags($_POST['meta_title_1']);
$_POST['meta_title_2'] = strip_tags($_POST['meta_title_2']);
$_POST['meta_title_3'] = strip_tags($_POST['meta_title_3']);
$_POST['meta_description_1'] = str_replace($noPass,"&#8217;",$_POST['meta_description_1']);
$_POST['meta_description_2'] = str_replace($noPass,"&#8217;",$_POST['meta_description_2']);
$_POST['meta_description_3'] = str_replace($noPass,"&#8217;",$_POST['meta_description_3']);
$_POST['meta_title_1'] = str_replace($noPass,"&#8217;",$_POST['meta_title_1']);
$_POST['meta_title_2'] = str_replace($noPass,"&#8217;",$_POST['meta_title_2']);
$_POST['meta_title_3'] = str_replace($noPass,"&#8217;",$_POST['meta_title_3']);


if(isset($_POST['CatOrder'])) {
	$CatOrder = mysql_query("SELECT categories_order, categories_id FROM categories WHERE categories_order = '".$_POST['CatOrder']."' AND cat_or_subcat='1'");
	$CatOrderResult = mysql_num_rows($CatOrder);

 
	if($CatOrderResult > 0) {
		$catOrderResultFinal = mysql_fetch_array($CatOrder);
		if($catOrderResultFinal['categories_id'] == $_POST['id']) {
			$orderOk = "ok";
		}
		else {
			$message .= "<div align='center'>".A7."</div>";
			$orderOk = "notok";		
		}
	}
	else {
		$orderOk = "ok";
	}
}
else {
	$orderOk = "ok";
	$_POST['CatOrder'] = 0;
}
 
if($_POST['image']=="Geen afbeelding" OR empty($_POST['image'])) $_POST['image']="im/zzz.gif";

if($_POST['name_1'] == "" AND $_POST['name_2'] == "" AND $_POST['name_3'] == "") {
    $message .= "<div align='center'>".A1."</div>";
    $toto2 = "notok";
}
else {
     $toto2 = "ok";
}

if(!empty($_POST['image']) AND $_POST['image']!=="im/zzz.gif") {
	$explodeImageFile2 = explode("/",$_POST['image']);
    $image2 = end($explodeImageFile2);
  	if(strlen($image2) > 4 AND substr($image2,-4) == ".gif" OR substr($image2,-4) == ".jpg" OR  substr($image2,-4) == ".png") {
      	$image2 = "im/artikelen/$image2";
      	$toto1 = "ok";
      }
      else {
      	$message.= "<div align='center'>".A2."</div>";
      	$toto1 = "notok";
	}
}
else {
	$image2 = "im/zzz.gif";
	$toto1 = "ok";
}

 
if(isset($_FILES["uploadCatImage"]["name"]) AND !empty($_FILES["uploadCatImage"]["name"])) {
 
	$nomFichier    = $_FILES["uploadCatImage"]["name"];
 
	$nomTemporaire = $_FILES["uploadCatImage"]["tmp_name"];
 
	$typeFichier   = $_FILES["uploadCatImage"]["type"];
 
	$poidsFichier  = $_FILES["uploadCatImage"]["size"];
 
	$codeErreur    = $_FILES["uploadCatImage"]["error"];
 
	$chemin = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$image2 = str_replace("../","",$chemin).$nomFichier;
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <b>".$nomFichier."</b> ".A_REUSSI.".</div>";
		}
		else {
			$image2 = "im/zzz.gif";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <b>".$nomFichier."</b> ".A_ECHOUE."</div>";
		}
	}
	else {
		$image2 = "im/zzz.gif";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <b>".$nomFichier."</b> ".A_ECHOUE."</div>";
	}
}

 
if($toto1=="ok" AND $toto2=="ok" AND $orderOk=="ok") {
	$update = mysql_query("UPDATE categories
							SET
							categories_order='".$_POST['CatOrder']."',
							categories_name_1='".$_POST['name_1']."',
							categories_name_2='".$_POST['name_2']."',
							categories_name_3='".$_POST['name_3']."',
							categories_visible='".$_POST['visible']."',
							categories_comment_1='".$_POST['comment_1']."',
							categories_comment_2='".$_POST['comment_2']."',
							categories_comment_3='".$_POST['comment_3']."',
							categories_image='".$image2."',
							categories_meta_description_1='".$_POST['meta_description_1']."',
							categories_meta_description_2='".$_POST['meta_description_2']."',
							categories_meta_description_3='".$_POST['meta_description_3']."',
							categories_meta_title_1='".$_POST['meta_title_1']."',
							categories_meta_title_2='".$_POST['meta_title_2']."',
							categories_meta_title_3='".$_POST['meta_title_3']."'
							WHERE categories_id='".$_POST['id']."'");

	if($_POST['visible']=='no') {
		$update2 = mysql_query("UPDATE categories SET categories_visible='".$_POST['visible']."' WHERE parent_id='".$_POST['id']."'");
		// visible or not
		if(isset($catTo) AND sizeof($catTo) > 0) {
			foreach($catTo AS $elem) {
				$update3 = mysql_query("UPDATE categories SET categories_visible='".$_POST['visible']."' WHERE parent_id='".$elem."'");
			}
		}
	}
	if($_SESSION['lang']=="1") $name = $_POST['name_1'];
	if($_SESSION['lang']=="2") $name = $_POST['name_2'];
	if($_SESSION['lang']=="3") $name = $_POST['name_3'];
	if(isset($_SESSION['tree'])) unset($_SESSION['tree']);
	if(isset($_SESSION['tree2'])) unset($_SESSION['tree2']);
	$message.= "<p align='center'>".A3." <b>".strtoupper($name)."</b> ".A4."</p>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A5;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
         <FORM action="categorie_wijzigen.php" method="post">
          <INPUT type="submit" class="knop" VALUE="<?php print A6;?>">
         </form>
        </td>
       </tr>
</table>
  </body>
  </html>
