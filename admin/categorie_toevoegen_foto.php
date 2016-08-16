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

 
$noPass = array("\'","'");
$_POST['name_1'] = strip_tags($_POST['name_1']);
$_POST['name_2'] = strip_tags($_POST['name_2']);
$_POST['name_3'] = strip_tags($_POST['name_3']);
$_POST['meta_description_1'] = strip_tags($_POST['meta_description_1']);
$_POST['meta_description_2'] = strip_tags($_POST['meta_description_2']);
$_POST['meta_description_3'] = strip_tags($_POST['meta_description_3']);
$_POST['meta_title_1'] = strip_tags($_POST['meta_title_1']);
$_POST['meta_title_2'] = strip_tags($_POST['meta_title_2']);
$_POST['meta_title_3'] = strip_tags($_POST['meta_title_3']);

$_POST['name_1'] = str_replace($noPass,"&#8217;",$_POST['name_1']);
$_POST['name_2'] = str_replace($noPass,"&#8217;",$_POST['name_2']);
$_POST['name_3'] = str_replace($noPass,"&#8217;",$_POST['name_3']);
$_POST['meta_description_1'] = str_replace($noPass,"&#8217;",$_POST['meta_description_1']);
$_POST['meta_description_2'] = str_replace($noPass,"&#8217;",$_POST['meta_description_2']);
$_POST['meta_description_3'] = str_replace($noPass,"&#8217;",$_POST['meta_description_3']);
$_POST['meta_title_1'] = str_replace($noPass,"&#8217;",$_POST['meta_title_1']);
$_POST['meta_title_2'] = str_replace($noPass,"&#8217;",$_POST['meta_title_2']);
$_POST['meta_title_3'] = str_replace($noPass,"&#8217;",$_POST['meta_title_3']);
$_POST['name_1'] = str_replace("/","-",$_POST['name_1']);
$_POST['name_2'] = str_replace("/","-",$_POST['name_2']);
$_POST['name_3'] = str_replace("/","-",$_POST['name_3']);



if(isset($_POST['comment_1'])) {$_POST['comment_1'] = str_replace($noPass,"&#8217;",$_POST['comment_1']);} else {$_POST['comment_1']="";}
if(isset($_POST['comment_2'])) {$_POST['comment_2'] = str_replace($noPass,"&#8217;",$_POST['comment_2']);} else {$_POST['comment_2']="";}
if(isset($_POST['comment_3'])) {$_POST['comment_3'] = str_replace($noPass,"&#8217;",$_POST['comment_3']);} else {$_POST['comment_3']="";}








if(isset($_POST['todo']) AND $_POST['todo']=='add') {

if(!isset($_POST['catOrder'])) {$_POST['catOrder'] = 0;}
if(!isset($_POST['group_1'])) {$_POST['group_1'] = $_POST['group'];}

      if(!empty($_POST['image'])) {
	     $_POST['image'] = str_replace("\"","",$_POST['image']);
         $explodeImageFile = explode("\\",$_POST['image']);
         $image2 = end($explodeImageFile);
            if(strlen($image2) > 4 AND substr($image2,-4) == ".gif" or substr($image2,-4) == ".jpg" or  substr($image2,-4) == ".png") {
               $image2 = "im/artikelen/".$image2;
               $toto1 = "ok";
            }
            else {
            	$message .= "<div align='center'><span class='fontrouge'>".A1."</span></div>";
            	$toto1 = "notok";
            }
      }
      else {
      	$image2 = "im/zzz.gif";
		$toto1 = "ok";
      }


if(isset($_FILES["uploadImage"]["name"]) AND !empty($_FILES["uploadImage"]["name"])) {
 
	$nomFichier1    = $_FILES["uploadImage"]["name"];
 
	$nomTemporaire1 = $_FILES["uploadImage"]["tmp_name"];
 
	$typeFichier1   = $_FILES["uploadImage"]["type"];
 
	$poidsFichier1  = $_FILES["uploadImage"]["size"];
 
	$codeErreur1    = $_FILES["uploadImage"]["error"];
 
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

 

if(empty($_POST['name_1']) AND empty($_POST['name_2']) AND empty($_POST['name_3'])) {
	$message .= "<div align='center'><span class='fontrouge'>".A2."</span></div>";
	$toto2 = "notok";
}
else {
      $query = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                            FROM categories
                            WHERE categories_name_".$_SESSION['lang']." = '".$_POST['name_1']."' and parent_id = '".$_POST['group_1']."'
							AND cat_or_subcat = '1'");
      $num = mysql_num_rows($query);
		if($num > 0) {
			$message = "<span class='fontrouge'>".A3."</span>";
			$toto2 = "notok";
		}
		else {
			$query = mysql_query("SELECT categories_id FROM categories ORDER BY categories_id");
			while($catNum = mysql_fetch_array($query)) {
				$catNumArray[] = $catNum['categories_id'];
				asort($catNumArray);
			}
			$catNum = end($catNumArray)+1;
			$catNoeud = "B";
			$toto2 = "ok";
		}
}
if($toto1=="ok" and $toto2=="ok") {
 
$insert = mysql_query("INSERT INTO categories (
                      tree,
                      parent_id,
                      categories_id,
                      categories_order,
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
                      VALUES (
                      'tree',
                      '".$_POST['group_1']."',
                      '".$catNum."',
                      '".$_POST['catOrder']."',
                      '".$catNoeud."',
                      '".$_POST['name_1']."',
                      '".$_POST['name_2']."',
                      '".$_POST['name_3']."',
                      '".$_POST['comment_1']."',
                      '".$_POST['comment_2']."',
                      '".$_POST['comment_3']."',
                      '".$image2."',
                      '1',
                      '".$_POST['visu']."',
                      '".$_POST['meta_title_1']."',
                      '".$_POST['meta_title_2']."',
                      '".$_POST['meta_title_3']."',
                      '".$_POST['meta_description_1']."',
                      '".$_POST['meta_description_2']."',
                      '".$_POST['meta_description_3']."'
                      )");

if($_SESSION['lang']=="1") $name = $_POST['name_1'];
if($_SESSION['lang']=="2") $name = $_POST['name_2'];
if($_SESSION['lang']=="3") $name = $_POST['name_3'];
if(isset($_SESSION['tree'])) unset($_SESSION['tree']);
if(isset($_SESSION['tree2'])) unset($_SESSION['tree2']);

$message.= "<p align='center'><span class='fontrouge'><b>".A4." ".strtoupper($name)." ".A5."</b></span></p>";
}
}
 

if(isset($_POST['group']) AND $_POST['group'] == 0) {
$message = A106;
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A6;?></p>

<FORM action="<?php print $_SERVER['PHP_SELF'];?>" method="post" enctype='multipart/form-data'>
<table border="0" width="700" align="center" cellpadding="5" cellspacing="0" class="TABLE">
       <tr bgcolor="#FFFFFF">
        <td align="left" colspan="2"><b><?php print CAT;?>: <?php print $_POST['name_'.$_SESSION['lang']];?></b></td>
       </tr>
       <tr bgcolor="#FFFFFF">
        <td valign=top><?php print $message;?></td>
        <td><input type="text" size="5" class='vullen' name="catOrder">
       </tr>
       <tr bgcolor="#FFFFFF">

      <td valign=top><?php print A8;?>:</td>
        <td>

		&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadImage' class='vullen' size='40'>
		&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='<?php print VIDER;?>'>
		<div><img src='im/zzz.gif' width='1' height='5'></div>
		<input type="READONLY" size="30" class='vullen' name="image">&nbsp;(.gif,.jpg,.png)
 
		<?php print "(".A9.")";?>
       </tr>
       <tr>
        <td align="center" colspan="2" height="40">
		<INPUT TYPE="submit" class='knop' VALUE="<?php print A107;?>"></td>
       </tr>
</table>

<br>
<table width="700" align="center" cellpadding="5" cellspacing="0"><tr>
<td><?php print A16;?></td>
</tr></table>
<?php
$group_1 = $_POST['group'];
print "<input type='hidden' name='name_1' value='".$_POST['name_1']."'>";
print "<input type='hidden' name='name_2' value='".$_POST['name_2']."'>";
print "<input type='hidden' name='name_3' value='".$_POST['name_3']."'>";
print "<input type='hidden' name='meta_title_1' value='".$_POST['meta_title_1']."'>";
print "<input type='hidden' name='meta_title_2' value='".$_POST['meta_title_2']."'>";
print "<input type='hidden' name='meta_title_3' value='".$_POST['meta_title_3']."'>";
print "<input type='hidden' name='meta_description_1' value='".$_POST['meta_description_1']."'>";
print "<input type='hidden' name='meta_description_2' value='".$_POST['meta_description_2']."'>";
print "<input type='hidden' name='meta_description_3' value='".$_POST['meta_description_3']."'>";
print "<input type='hidden' name='comment_1' value='".$_POST['comment_1']."'>";
print "<input type='hidden' name='comment_2' value='".$_POST['comment_2']."'>";
print "<input type='hidden' name='comment_3' value='".$_POST['comment_3']."'>";
print "<input type='hidden' name='visu' value='".$_POST['visu']."'>";
print "<input type='hidden' name='group_1' value='".$group_1."'>";
print "<input type='hidden' name='todo' value='add'>";
?>
</form>

<?php
$c = "";
$result = mysql_query("SELECT * FROM categories WHERE parent_id = '".$_POST['group']."' ORDER BY categories_order");
                       
print "<br>";
print "<table border='0' width='700' cellpadding='5' cellspacing ='0' align='center' class='TABLE'";
print "<tr>";
print "<td height='19' width='1' align='left'><b>Volgorde</b></td>";
print "<td height='19' align='left'><b>Categorie overzicht</b></td>";

while($catZero = mysql_fetch_array($result)) {
  	if($c=="#F1F1F1") $c = "#CCFF99"; else $c = "#F1F1F1";
	print "</tr><tr bgcolor='".$c."'>";
	print "<td>";
	print $catZero['categories_order'];
	print "</td>";
	print "<td align='left'>";
	print $catZero['categories_name_'.$_SESSION['lang']];
	print "</td>";
}
print "</tr>";
print "</table>";
?>

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
<p align="center" class="largeBold"><?php print A6;?></p>

<?php
if(!isset($_POST['todo'])) {
?>
<FORM action="<?php print $_SERVER['PHP_SELF'];?>" method="post" enctype='multipart/form-data'>
<table border="0" width="700" align="center" cellpadding="5" cellspacing="0" class="TABLE">
       <tr>
        <td align="left" colspan="2"><b><?php print CAT;?>: <?php print $_POST['name_'.$_SESSION['lang']];?></b></td>
       </tr>
       <tr>

      <td valign=top><?php print A8;?></td>
        <td>

		&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' class='vullen' name='uploadImage' size='40'>
		&nbsp;<INPUT TYPE='reset' NAME='nom' class='vullen' VALUE='<?php print VIDER;?>'>
		<div><img src='im/zzz.gif' width='1' height='5'></div>
		<input type="READONLY" size="30" class="vullen" name="image">&nbsp;(.gif,.jpg,.png)
 
		<?php print "&nbsp;".A9."&nbsp;";?>
       </tr>
       <tr>
        <td align="center" colspan="2" height="40">
		<INPUT TYPE="submit" VALUE="<?php print A107;?>" class="knop"></td>
       </tr>
</table>

<br>
<table width="700" align="center" cellpadding="5" cellspacing="0"><tr>
<td><?php print A16;?></td>
</tr>
</table>
<?php
$group_1 = $_POST['group'];
print "<input type='hidden' name='name_1' value='".$_POST['name_1']."'>";
print "<input type='hidden' name='name_2' value='".$_POST['name_2']."'>";
print "<input type='hidden' name='name_3' value='".$_POST['name_3']."'>";
print "<input type='hidden' name='comment_1' value='".$_POST['comment_1']."'>";
print "<input type='hidden' name='comment_2' value='".$_POST['comment_2']."'>";
print "<input type='hidden' name='comment_3' value='".$_POST['comment_3']."'>";
print "<input type='hidden' name='visu' value='".$_POST['visu']."'>";
print "<input type='hidden' name='group_1' value='".$group_1."'>";
print "<input type='hidden' name='todo' value='add'>";
?>
</form>
<?php
}
?>

<table border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE" width="700">
       <tr>
        <td align="center" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><FORM action="categorie_toevoegen.php" method="post"><INPUT TYPE="submit" class="knop" VALUE="<?php print A7;?>"></form></td>
       </tr>
</table>

</body>
</html>
<?php
}
?>
