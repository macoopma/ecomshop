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
// Query
$query = mysql_query("SELECT * FROM categories WHERE categories_id = '".$_GET['id']."'");
$row = mysql_fetch_array($query);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1."<br>- ".$row['categories_name_'.$_SESSION['lang']];?> -</p>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center' class='TABLE'><tr><td>

<?php
print "<form action='maj_cat.php' method='POST' target='main' enctype='multipart/form-data'>";
print "<table width='100%' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr>";
// categorie_id
        print "<td>ID:</td>";
        print "<td>".$row['categories_id']."</td>";
        print "</tr>";
print "<tr>";
        print "<td valign=top>".A2."</td>";
        print "<td>";
        
        // categorie_name_1-2-3
        print "<img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;<input type='text' value='".$row['categories_name_3']."' name='name_3' size='90%' class='vullen'>";

        print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
        print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;<input type='text' value='".$row['categories_name_1']."' name='name_1' size='90%' class='vullen'";
        print "<br><img src='im/zzz.gif' width='1' height='3'><br>";

        print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;<input type='text' value='".$row['categories_name_2']."' name='name_2' size='90%' class='vullen'";
        print "<br><img src='im/zzz.gif' width='1' height='3'><br>";

        
        print "</td>";
        print "</tr>";
        print "<tr>";
// categories_id - parent_id
        print "<input type='hidden' value='".$row['categories_id']."' name='id'>";
        print "<input type='hidden' value='0' name='parent_id'>";
        print "</tr>";
        print "<tr>";

// categorie_visible
        if($row['categories_visible'] == "yes") {
        	$checkedyes = "checked";
        	$checkedno = "";
        }
		else {
        	$checkedyes = "";
        	$checkedno = "checked";
        }
print "<td>Zichtbaar: </td>";
print "<td> ".A3." <input type='radio' value='yes' ".$checkedyes." name='visible'>
            ".A4." <input type='radio' value='no' name='visible' ".$checkedno.">
       </td>";
print "</tr>";


?> 
<?php // products_meta_description_1-2-3 ?>
<tr>
<td valign=top><b><?php print "Meta Tag beschrijving";?></b><br><i>200 <?php print CAR;?> max</i></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td>
        <img src='im/be.gif' align='absmiddle'>&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_3" size="80%" maxlength="200" class='vullen' value="<?php print $row['categories_meta_description_3'];?>"></td>

        </tr><tr><td>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_1" size='80%' maxlength="200" class='vullen' value="<?php print $row['categories_meta_description_1'];?>"></td>
        </tr><tr><td>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_2" size="80%" maxlength="200" class='vullen' value="<?php print $row['categories_meta_description_2'];?>"></td>
        </tr><tr><td>

        </tr></table>        
        </td>
</tr>
<?php // products_meta_title_1-2-3 ?>
<tr>
<td valign=top><b><?php print "Meta Tag titel";?></b><br><i>100 <?php print CAR;?> max</i></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td>
        <img src='im/be.gif' align='absmiddle'>&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_3" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_3'];?>"></td>

        </tr><tr><td>

        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_1" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_1'];?>"></td>
        </tr><tr><td>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_2" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_2'];?>"></td>
        </tr><tr><td>

        </tr></table>        
        </td>
</tr>
<tr>
<td colspan="2" align="center" class="fontgris"><?php print LAISSER_LES_CHAMPS_VIDES;?></td>
</tr>
<?php




print "<tr>";
        print "<td valign=top>".A5."</td>";
        print "<td>";
        
        print "<table border='0' cellpadding='1' cellspacing='0'><tr>";
        // categorie_comments 1-2-3
        print "<td valign=top><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_3' ID='textarea1'>".$row['categories_comment_3']."</textarea></td></tr><tr>";
        print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_1' ID='textarea2'>".$row['categories_comment_1']."</textarea></td></tr><tr>";
        print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_2' ID='textarea3'>".$row['categories_comment_2']."</textarea></td>";
        print "</tr></table>";
        
        print "</td>";
print "</tr>";
print "<tr>";
// categorie_image
        print "<td valign=top>".A6."</td>";
		print "<td>";

				print "&nbsp;&bull;&nbsp;".UPLOAD."<br>&nbsp;<input type='file' name='uploadCatImage' style='background:#E1E1E1; border:#808080 1px solid;' class='vullen' size='40'>";
				
				print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				if(empty($row['categories_image']) OR $row['categories_image']=="im/zzz.gif") {
					print "&nbsp;<input type='text' class='vullen' value='Geen afbeelding' name='image' size='40'>";
					$_im = 0;
				}
				else {
					print "&nbsp;<input type='text' class='vullen' value=".$row['categories_image']." name='image' size='40'>";
					$_im = 1;
				}
				print "&nbsp;<i>(".A7.")</i>";
				print "</div>";
        print "</td>";
print "</tr>";
// categorie_voir image
if(isset($_im) AND $_im == 1) {
   $_order = "";
   print "<tr>";
   print "<td>".A8."</td>";
   		$popSize = @getimagesize("../".$row['categories_image']);
		$witdtZ = ($popSize AND $popSize[0]>200)? "width='200'" : "";
   print "<td><img src='../".$row['categories_image']."' ".$witdtZ."></td>";
   print "</tr>";
}
else {
   $_order = "bgcolor='#FFFFFF'";
}

// Order
if($row['parent_id'] == 0) {
print "<tr ".$_order.">";
        print "<td>Volgorder</td>";
        print "<td><input type='text' class='vullen' value=".$row['categories_order']." name='CatOrder' size='5'></td>";
print "</tr>";
}
print "</table>";

print "<br>";

print "<table width='100%' border='0' cellpadding='5'cellspacing='0' align='center' class='TABLE'>";
print "<tr>";
print "<td colspan='2' align='center' height='40'><input type='submit' value='".A9."' class='knop'></td>";
print "</tr>";
print "</table>";
print "</form>";

// Order table view
if($row['parent_id'] == 0) {
$c = "";
$result = mysql_query("SELECT * FROM categories WHERE parent_id = '".$row['parent_id']."' ORDER BY categories_order");

print "<br><br>";
print "<table border='0' width='100%' cellpadding='5' cellspacing ='5' align='center' class='TABLE2'";
print "<tr>";
print "<td height='19' width='1' align='left'><b>Volgorde</b></td>";
print "<td height='19' align='left'><b>Menu</b></td>";

while($catZero = mysql_fetch_array($result)) {
  	if($c=="#F1F1F1") $c = "#CCCCCC"; else $c = "#F1F1F1";
	print "</tr><tr bgcolor='".$c."'>";
	print "<td>";
	print $catZero['categories_order'];
	print "</td>";
	print "<td>";
	print $catZero['categories_name_'.$_SESSION['lang']];
	print "</td>";
}
print "</tr>";
print "</table>";
}
?>

<br><br><br>
<table align="center" width="100%"><tr><td>
        <?php print A10;?>
</td></tr></table>

</td></tr></table>
<br><br><br>
</body>
</html>




