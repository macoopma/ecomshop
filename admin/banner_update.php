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

if(empty($_POST['image'])) $_POST['image'] = "";
if(empty($_POST['url'])) $_POST['url'] = "";
if(empty($_POST['sponsor'])) $_POST['sponsor'] = "";
$message = "";

 
if(isset($_FILES["uploadBannerImage"]["name"]) AND !empty($_FILES["uploadBannerImage"]["name"])) {
 
	$nomFichier    = $_FILES["uploadBannerImage"]["name"];
 
	$nomTemporaire = $_FILES["uploadBannerImage"]["tmp_name"];
 
	$typeFichier   = $_FILES["uploadBannerImage"]["type"];
 
	$poidsFichier  = $_FILES["uploadBannerImage"]["size"];
 
	$codeErreur    = $_FILES["uploadBannerImage"]["error"];
 
	$chemin = "../im/banner/";
	if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$_POST['image'] = str_replace("../","",$chemin).$nomFichier;
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
	}
}

$_POST['end'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['end']);

$update =mysql_query("UPDATE banner
                     SET
                     banner_desc='".$_POST['desc']."',
                     banner_image='".$_POST['image']."',
                     banner_url='".$_POST['url']."',
                     banner_sponsor='".$_POST['sponsor']."',
                     banner_date_end='".$_POST['end']."',
                     banner_vue='".$_POST['banView']."',
                     banner_hit='".$_POST['banHit']."',
                     banner_visible='".$_POST['visible']."'
                     WHERE banner_id='".$_POST['id']."'
                     ");
	$message.= "<p align='center' class='fontrouge' style='font-size:11px;'><b>".UPDATE_OK."</b></p>";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A5;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" width="700" class="TABLE">
       <tr>
        <td align="center" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
         <FORM action="banner_wijzigen.php" method="post">
          <INPUT type="submit" VALUE="<?php print A6;?>" class='knop'>
         </form>
        </td>
       </tr>
</table>
</body>
</html>