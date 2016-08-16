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

 
$query = mysql_query("SELECT * FROM categories WHERE categories_id = '".$_GET['id']."'");
$row = mysql_fetch_array($query);

 
function espace3($rang3) {
	$ch="";
	for($x=0;$x<$rang3;$x++) {
		$ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	return $ch;
}

 
function recur($tab,$pere,$rang3) {
	global $c;
	if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
	$tabNb = count($tab);
	for($x=0;$x<$tabNb;$x++) {
		if($tab[$x][1]==$pere) {
			print "</tr><tr bgcolor='".$c."'>";
			print "<td>";
			print espace3($rang3).$tab[$x][2];
			print "</td>";
			print "<td>";
			print "<div align='center'>".$tab[$x][3]."</center>";
			print "</td>";
			recur($tab,$tab[$x][0],$rang3+1);
		}
	}
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1?><p>

<table width='700' border='0' cellpadding='0' cellspacing='0' align='center'><tr><td>

<?php
$query2 = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_noeud = 'B'");
$queryNum = mysql_num_rows($query2);

print "<form action='sub_categorie_update_controle.php' method='POST' target='main' enctype='multipart/form-data'>";
print "<table width='100%' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr>";

        print "<td valign=top>ID</td>";
        print "<td><b>".$row['categories_id']." ".$row['categories_name_'.$_SESSION['lang']]."</b></td>";
        print "</tr>";
print "<tr bgcolor='#FFFFFF'>";

        print "<td valign=top>".A2."</td>";
        print "<td>";
         // categorie_naam_1-2-3
        print "<img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;<input type='text' class='vullen' value='".$row['categories_name_3']."' name='name_3' size='65%'>";
        print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
        print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;<input type='text' class='vullen' value='".$row['categories_name_1']."' name='name_1' size='65%'>";
        print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
        print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;<input type='text' class='vullen' value='".$row['categories_name_2']."' name='name_2' size='65%'>";
        print "</td>";
 
        print "</tr><tr>";
        print "<td valign=top>".A3." </td>";
        print "<td>";
        print "<select name='cat_id'>";

                while ($categoriesList = mysql_fetch_array($query2)) {
                       if($row['parent_id']==$categoriesList['categories_id']) $sel="selected"; else $sel="";
                       print "<option value='".$categoriesList['categories_id']."' ".$sel.">".strtoupper($categoriesList['categories_name_'.$_SESSION['lang']])." - ID:".$categoriesList['categories_id'];
                       print "</option>";
                }
        print "</select>";


        print "</td>";
 
        print "<input type='hidden' value='".$row['categories_id']."' name='id'>";
print "</tr><tr bgcolor='#FFFFFF'>";

 
        if($row['categories_visible'] == "yes") {
        	$checkedyes = "checked";
        	$checkedno = "";
        }
		else {
        	$checkedyes = "";
        	$checkedno = "checked";
        }
print "<td valign=top>Zichtbaar </td>";
print "<td>";
print A4." <input type='radio' value='yes' ".$checkedyes." name='visible'>";
print "&nbsp;";
print A5." <input type='radio' value='no' name='visible' ".$checkedno.">";
print "</td>";
print "</tr>";
?> 
<?php // meta_1-2-3 ?>
<tr>
<td valign=top><?php print "Meta tag beschrijving";?></b><br>Maximaal 200 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src='im/be.gif' align='absmiddle'>&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_3" size='80%' maxlength="200" value="<?php print $row['categories_meta_description_3'];?>"></td>
        </tr><tr><td valign=top>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_1" size="80%" maxlength="200" value="<?php print $row['categories_meta_description_1'];?>"></td>
        </tr><tr><td valign=top>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_description_2" size="80%" maxlength="200" value="<?php print $row['categories_meta_description_2'];?>"></td>
        </tr></table>        
        </td>
</tr>
<?php // meta_titel_1-2-3 ?>
<tr>
<td><b><?php print "Meta tag titel";?></b><br>Maximaal 100 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td valign=top>
        <img src='im/be.gif' align='absmiddle'>&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_3" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_3'];?>"></td>
        </tr><tr><td valign=top>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_1" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_1'];?>"></td>
        </tr><tr><td valign=top>
        <img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" class='vullen' name="meta_title_2" size="80%" maxlength="100" value="<?php print $row['categories_meta_title_2'];?>"></td>
        </tr></table>        
        </td>
</tr>
<tr>
<td colspan="2" align="center" class="fontrouge"><?php print LAISSER_LES_CHAMPS_VIDES;?></td>
</tr>
<tr bgcolor='#FFFFFF'>
<?php

        print "<td valign=top>".A6."</td>";
        print "<td>";
        print "<table border='0' cellpadding='1' cellspacing='0'><tr>";
         // categorie_comm 1-2-3
        print "<td valign=top><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_3'>".$row['categories_comment_3']."</textarea></td></tr><tr>";
        print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_1'>".$row['categories_comment_1']."</textarea></td></tr><tr>";
        print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;</td><td><textarea cols='70' rows='7' name='comment_2'>".$row['categories_comment_2']."</textarea></td>";
        print "</tr></table>";
        print "</td>";
print "</tr>";
print "<tr bgcolor='#FFFFFF'>";
 
        print "<td valign=top>".A7."</td>";
        print "<td>";
 
				print "&nbsp;&bull;&nbsp;".UPLOAD."<br>&nbsp;<input type='file' name='uploadSubCatImage' class='vullen' size='40'>";
				print "&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='".VIDER."'>";
				print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				if(empty($row['categories_image']) OR $row['categories_image']=="im/zzz.gif") {
					print "&nbsp;<input type='text' class='vullen' value='Geen afbeelding' name='image' size='40'>";
					$_im = 0;
				}
				else {
					print "&nbsp;<input type='text' class='vullen' value=".$row['categories_image']." name='image' size='40'>";
					$_im = 1;
				}
				print "&nbsp;(".A8.")";
 
        print "</td>";
print "</tr>";
 
if(isset($_im) AND $_im == 1) {
   print "<tr>";
   print "<td>".A9."</td>";
   		$popSize = @getimagesize("../".$row['categories_image']);
		$witdtZ = ($popSize AND $popSize[0]>200)? "width='200'" : "";
   print "<td><img src='../".$row['categories_image']."' ".$witdtZ."></td>";
   print "</tr>";
}

print "</table>";

print "<br>";

 
print "<table width='100%' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr>";
print "<td align='center' colspan='2' height='40'><input type='submit' value='".A10."' class='knop'></td>";
print "</tr></table>";
print "<br><br>";
print "</form>";


$result = mysql_query("SELECT * FROM categories ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {
	$papa = $message['categories_id'];
	$fils = $message['parent_id'];
	$visible = $message['categories_visible'];
	$visible = ($message['categories_visible']=='yes')? A4 : "<span style='color:red'><b>".A5."</b></span>";
	$titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b> [ID:".$message['categories_id']."]" : $message['categories_name_'.$_SESSION['lang']];
	$data[] = array($papa,$fils,$titre,$visible);
}
 
if($queryNum>0) {
print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'";
print "<tr>";
print "<td height='19' align='left'><b>".A15."</b></td>";
print "<td height='19' align='center'><b>".A16."</b></td>";
recur($data,"0","0");
print "</tr>";
print "</table>";
}
?>
<br><br><table align="center" width="100%"><tr><td>
        <!-- <?php print A11;?> -->
</td></tr></table>

</td></tr></table>
<br><br><br>
</body>
</html>
