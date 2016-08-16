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
$message2 = "";
$message = "";


if(!empty($_POST['image'])) {
	$_POST['image'] = str_replace("\"","",$_POST['image']);
	$explodeImageFile = explode("\\",$_POST['image']);
	$image2 = end($explodeImageFile);
	if(strlen($image2) > 4 AND substr($image2,-4) == ".gif" OR substr($image2,-4) == ".jpg" OR substr($image2,-4) == ".png") {
		$image2 = "im/artikelen/$image2";
		$toto1 = "ok";
	}
	else {
		$message.= "<div align='center'><span class='fontrouge'>".A1."</span></div>";
		$toto1 = "notok";
	}
}
else {
	$image2 = "im/zzz.gif";
	$toto1 = "ok";
}

 
if(isset($_FILES["uploadSubcatImage"]["name"]) AND !empty($_FILES["uploadSubcatImage"]["name"])) {
 
	$nomFichier1    = $_FILES["uploadSubcatImage"]["name"];
 
	$nomTemporaire1 = $_FILES["uploadSubcatImage"]["tmp_name"];
 
	$typeFichier1   = $_FILES["uploadSubcatImage"]["type"];
 
	$poidsFichier1  = $_FILES["uploadSubcatImage"]["size"];
 
	$codeErreur1    = $_FILES["uploadSubcatImage"]["error"];
 
	$chemin1 = "../im/artikelen/";
	if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier1)) {
		if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
			$image2 = str_replace("../","",$chemin1).$nomFichier1;
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</div>";
		}
		else {
			$image2 = "im/zzz.gif";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$image2 = "im/zzz.gif";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
	}
}


if(!empty($_POST['name_1']) OR !empty($_POST['name_2']) OR !empty($_POST['name_3'])) {
 
	$noPass = array("\'","'");
	$_POST['name_1'] = strip_tags($_POST['name_1']);
	$_POST['name_2'] = strip_tags($_POST['name_2']);
	$_POST['name_3'] = strip_tags($_POST['name_3']);
	$_POST['name_1'] = str_replace($noPass,"&#8217;",$_POST['name_1']);
	$_POST['name_2'] = str_replace($noPass,"&#8217;",$_POST['name_2']);
	$_POST['name_3'] = str_replace($noPass,"&#8217;",$_POST['name_3']);
	$_POST['name_1'] = str_replace("/","-",$_POST['name_1']);
	$_POST['name_2'] = str_replace("/","-",$_POST['name_2']);
	$_POST['name_3'] = str_replace("/","-",$_POST['name_3']);
	if(isset($_POST['comment_1'])) {$_POST['comment_1'] = str_replace($noPass,"&#8217;",$_POST['comment_1']);} else {$_POST['comment_1']="";}
	if(isset($_POST['comment_2'])) {$_POST['comment_2'] = str_replace($noPass,"&#8217;",$_POST['comment_2']);} else {$_POST['comment_2']="";}
	if(isset($_POST['comment_3'])) {$_POST['comment_3'] = str_replace($noPass,"&#8217;",$_POST['comment_3']);} else {$_POST['comment_3']="";}

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


	$query = mysql_query("SELECT * FROM categories WHERE categories_name_".$_SESSION['lang']." = '".$_POST['name_1']."' AND parent_id = '".$_POST['group']."' AND cat_or_subcat = '2'");
	$num = mysql_num_rows($query);
	
	if($num == 0) {
		$name_check = "ok";
	}
	else {
		$message.= "<div align='center'>";
		$message.= "<span class='fontrouge'>".A2." ".strtoupper($_POST['name_1'])." ".A3."</span>";
		$message.= "</div>".A4.".";
		$name_check = "notok";
	}
}
else {
	$message .= "<div align='center'><span class='fontrouge'>".A5."</span></div>";
	$name_check = "notok";
}

 
if($_POST['group'] == "no") {
	$message .= "<div align='center'><span class='fontrouge'>".A6."</span></div>";
    $field_group = "notok";
}
else {
    $message .= "";
    $field_group = "ok";
}

 
if($name_check == "ok" AND $field_group == "ok" AND $toto1=="ok") {
	$catNoeud = "L";
	$query = mysql_query("SELECT categories_id FROM categories ORDER BY categories_id");
	while($catNum = mysql_fetch_array($query)) {
		$catNumArray[] = $catNum['categories_id'];
		asort($catNumArray);
	}
	$catNum = end($catNumArray)+1;

	$insert = mysql_query("INSERT INTO categories (
	                        tree,
	                        parent_id,
	                        categories_id,
	                        categories_noeud,
	                        categories_name_1,
	                        categories_name_2,
	                        categories_name_3,
	                        categories_comment_1,
	                        categories_comment_2,
	                        categories_comment_3,
	                        categories_image,
	                        cat_or_subcat,
	                        categories_visible,
							categories_meta_title_1,
							categories_meta_title_2,
							categories_meta_title_3,
							categories_meta_description_1,
							categories_meta_description_2,
							categories_meta_description_3
							)
	                        VALUES ('tree',
	                                '".$_POST['group']."',
	                                '".$catNum."',
	                                '".$catNoeud."',
	                                '".$_POST['name_1']."',
	                                '".$_POST['name_2']."',
	                                '".$_POST['name_3']."',
	                                '".$_POST['comment_1']."',
	                                '".$_POST['comment_2']."',
	                                '".$_POST['comment_3']."',
	                                '".$image2."',
	                                '2',
	                                '".$_POST['visu']."',
	                                '".$_POST['meta_title_1']."',
	                                '".$_POST['meta_title_2']."',
	                                '".$_POST['meta_title_3']."',
	                                '".$_POST['meta_description_1']."',
	                                '".$_POST['meta_description_2']."',
	                                '".$_POST['meta_description_3']."'
									)") or die (mysql_error());
									
	if($_SESSION['lang']=="1") $name = $_POST['name_1'];
	if($_SESSION['lang']=="2") $name = $_POST['name_2'];
	if($_SESSION['lang']=="3") $name = $_POST['name_3'];
	if(isset($_SESSION['tree'])) unset($_SESSION['tree']);
	if(isset($_SESSION['tree2'])) unset($_SESSION['tree2']);
	$message.= "<p align='center'><b>".A2." ". strtoupper($name)." ".A7."</b></p>";
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A8;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><FORM action = "sub_categorie_toevoegen.php"><INPUT TYPE="submit" class="knop" VALUE="<?php print A9;?>"></form></td>
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
<p align="center" class="largeBold"><?php print A8;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message."<br>".$message2; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><FORM><INPUT TYPE=BUTTON class=knop VALUE=Retour onClick=history.go(-1)></form></td>
       </tr>
</table>
  </body>
  </html>

<?php
}
?>
