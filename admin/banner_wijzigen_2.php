<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

 
function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src='".$image."' border='0'>";    
    }
    else {
       $returnImage = "<img src='".$image."' border='0' width='".$wi."' height='".$he."'>";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}

 
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<table width='700' border='0' cellpadding='0'cellspacing='0' align='center' class='TABLE'><tr><td>

<?php
$query = mysql_query("SELECT * FROM banner WHERE banner_id = '".$_GET['id']."'");
$row = mysql_fetch_array($query);

print "<form action='banner_update.php' method='POST' target='main' enctype='multipart/form-data'>";

print "<table width='100%' border='0' cellpadding='5'cellspacing='0' align='center'>";
print "<tr>";


        print "<td colspan='2' align='center' bgcolor='#FFFFFF'>";
        if(!empty($row['banner_image'])) {
            if(preg_match('/^http:/i', $row['banner_image'])) print "<img src='".$row['banner_image']."'>"; else print detectIm("../".$row['banner_image'],0,0);
        }
        else {
            if(!empty($row['banner_sponsor'])) {
                print $row['banner_sponsor'];
            }
            else {
            print "--";
            }
        }
        print "</td></tr>";
        print "<tr>";

 
        print "<td>Id</td>";
        print "<td>".$row['banner_id']."</td>";
        print "</tr>";
        print "<tr>";


        print "<td>".A2."</td>";
        print "<td><input type='text' class='vullen' size='50' name='desc' value='".$row['banner_desc']."'></td>";
        print "</tr>";
        print "<tr>";


if(empty($row['banner_sponsor'])) {
 
        print "<td valign=top>".A3."</td>";
        print "<td>";


				print "&nbsp;&bull;&nbsp;".UPLOAD."<br>&nbsp;<input type='file' class='vullen' name='uploadBannerImage' size='40'>";
				print "&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='".VIDER."'>";
				print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				print "&nbsp;<input type='text' value='".$row['banner_image']."' class='vullen' name='image' size='40'>&nbsp;(.gif,.jpg,.png,.swf)";
 

		print "</td>";
        print "</tr>";
        print "<tr>";


        print "<td valign=top>URL</td>";
        print "<td><input type='text' class='vullen' size='50' value='".$row['banner_url']."' name='url'></td>";
        print "</tr>";
        print "<tr>";
}

if(!empty($row['banner_sponsor'])) {

        print "<td valign=top>".A20."</td>";
        print "<td><textarea cols='60' rows='4' class='vullen' name='sponsor'>".$row['banner_sponsor']."</textarea></td>";
        print "</tr>";
        print "<tr>";
}


        print "<td valign=top>".A4."</td>";
        print "<td>".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['banner_date_added'])."</td>";
        print "</tr>";
        print "<tr>";


        print "<td valign=top>".A5."</td>";
        print "<td><input type='text' size='25' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['banner_date_end'])."' name='end'></td>";
        print "</tr>";
        print "<tr>";


        print "<td valign=top>".A6."</td>";
        print "<td><input type='text' class='vullen' size='6' value='".$row['banner_vue']."' name='banView'></td>";
        print "</tr>";
        print "<tr>";


        print "<td valign=top>".A7."</td>";
        print "<td><input type='text' class='vullen' size='6' value='".$row['banner_hit']."' name='banHit'></td>";
        print "</tr>";
        print "<tr>";


        if($row['banner_visible'] == "yes") {
        $checkedyes = "checked";
        $checkedno = "";
        } else {
        $checkedyes = "";
        $checkedno = "checked";
        }
print "<td>Zichtbaar</b></td>";
print "<td> ".A8." <input type='radio' value='yes' name='visible' ".$checkedyes.">
            ".A9." <input type='radio' value='no' name='visible' ".$checkedno.">
       </td>";


print "<input type='hidden' value='".$_GET['id']."' name='id'>";
print "</tr>";
print "</table>";

print "<br>";

print "<table width='100%' border='0' cellpadding='5'cellspacing='0' align='center'>";
print "<tr>";
print "<td colspan='2' align='center' height='40'><input type='submit' value='".A10."' class='knop'></td>";
print "</tr>";
print "</table>";
print "</form>";
?>
</td></tr></table>
</body>
</html>
