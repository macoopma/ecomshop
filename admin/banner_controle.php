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

if(empty($_POST['desc'])) {
   $check_desc = "notok";
   $message .= "<br>".A1;
}
else {
   $check_desc = "ok";
}
 
if(empty($_POST['image']) AND empty($_POST['url']) AND empty($_POST['sponsor'])) {
   $check_image = "notok";
   $message.= "<br>".A21;
}
else {
   $check_image = "ok";
}
 

if(isset($_FILES["uploadBanner"]["name"]) AND !empty($_FILES["uploadBanner"]["name"])) {
 
	$nomFichier1    = $_FILES["uploadBanner"]["name"];
 
	$nomTemporaire1 = $_FILES["uploadBanner"]["tmp_name"];
 
	$typeFichier1   = $_FILES["uploadBanner"]["type"];
 
	$poidsFichier1  = $_FILES["uploadBanner"]["size"];
 
	$codeErreur1    = $_FILES["uploadBanner"]["error"];
 
	$chemin1 = "../im/banner/";
	if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier1)) {
		if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
			$_POST['image'] = str_replace("../","",$chemin1).$nomFichier1;
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</div>";
		}
		else {
			$_POST['image'] = "";
			$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
		}
	}
	else {
		$_POST['image'] = "";
		$message.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
	}
}

$_POST['end'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['end']);
if(empty($_POST['end'])) {
     $_POST['end'] = "2040-01-01";
     $dateEnd = "ok";
}
else {
      $toto = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['end']);
      if($toto == true) {
          $checkDate = explode("-",$_POST['end']);
          $verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);

             if($verifDate == true) {
                 $dateEnd = "ok";
             }
             else {
              $message .= "<br><span class='fontrouge'>".A3."</span>";
              $dateEnd = "notok";
             }
      }
      else {
        $message .= "<br><span class='fontrouge'>".A4."</span>";
        $dateEnd = "notok";
      }
}
##print $_POST['image']."<br>";

if($check_image == "ok" AND $dateEnd == "ok" AND $check_desc == "ok") {
	$date = date("Y-m-d H:i:s");
	if(!empty($_POST['image'])) {
    	if(preg_match('/^http:/i', $_POST['image'])) {
			$banIm = $_POST['image'];
		}
		else {
			##print "<b>".$_POST['image']."</b><br>";
			$banIm = (preg_match('#^im/banner#i', $_POST['image']))? $_POST['image'] : "im/banner/".$_POST['image'];
		}
	}
	else {
    	$banIm = "";
	}
##print $banIm;
##exit;
	mysql_query("INSERT INTO banner
	             (
	              banner_desc,
	              banner_image,
	              banner_url,
	              banner_sponsor,
	              banner_date_added,
	              banner_date_end
	              )
	             VALUES ('".$_POST['desc']."',
	                     '".$banIm."',
	                     '".$_POST['url']."',
	                     '".$_POST['sponsor']."',
	                     '".$date."',
	                     '".$_POST['end']."'
	                     )
	             ");
	$message.= "<p align='center'><b>".A5."</b></p>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A7;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
         <FORM action="banner_toevoegen.php" method="post">
          <INPUT TYPE="submit" VALUE="<?php print A6;?>" class="knop">
          </form>
         </td>
       </tr>
</table>

</body>
</html>