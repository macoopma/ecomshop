<?php


/* $domain = "kittdys.be";

$url = "http://www.ecomshop.be/niet-toegelaten.htm";

$regex = "#(?:[^\.]+\.)?".$domain."#";

preg_match($regex, $_SERVER['HTTP_HOST'], $matches);

// print_r($matches);
if(!$matches[0])
	header("Location: $url");
*/


session_start();


if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

$query = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                      FROM categories
                      WHERE categories_noeud = 'B'
                      ORDER BY categories_name_".$_SESSION['lang']."");
$queryNum = mysql_num_rows($query);


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
	       	if($pere == 0) {
		   		$CatOrder = mysql_query("SELECT categories_order FROM categories WHERE categories_id = '".$tab[$x][0]."'");
		   		$CatOrderResult = mysql_fetch_array($CatOrder);
		   		print "<b>".$CatOrderResult['categories_order']."</b>";
			}
		   	print "</td>";
		   	print "<td>"; 
	       	print espace3($rang3).$tab[$x][2];
	       	print "</td>";
	       	print "<td>";
	       	print "<div align='center'>".$tab[$x][3]."</div>";
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
<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";
    var name_1 = document.formAdd.name_1.value;
    var name_2 = document.formAdd.name_2.value;
    var name_3 = document.formAdd.name_3.value;
    
if(document.formAdd.elements['name_1'].type != "hidden") {
    if(name_1 == '' && name_2 == '' && name_3 == '') {
      error_message = error_message + "<?php print CHAMPS_VIDE;?>: <?php print A2;?>\n";
      error = 1;
    }
  }
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<form action="categorie_toevoegen_foto.php" method="post" name="formAdd" onsubmit="return check_form()">

<table border="0" width="700" align="center" cellpadding="0" cellspacing="0"><tr><td>
<table border="0" width="100%" cellpadding="5" cellspacing="0" class="TABLE">
       <tr bgcolor="#FFFFFF">
        <td valign=top><?php print A2;?></td>
        <td>

        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" size="65%" class="vullen" name="name_3">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" size="65%" class="vullen" name="name_1">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" size="65%" class="vullen" name="name_2">
        </td>
       </tr>


<tr>
        <td><?php print A4;?></td>
        <td>
			<select name="group">
<?php
			print "<option value='0'>".A3."</option>";
			while ($myrow = mysql_fetch_array($query)) {
				$query2 = mysql_query("SELECT categories_noeud, categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$myrow['parent_id']."'");
				$a =  mysql_fetch_array($query2);
				
				if(!empty($a['categories_name_'.$_SESSION['lang']]) AND $a['categories_name_'.$_SESSION['lang']] !== "Menu") {
					$sous = "[".$a['categories_name_'.$_SESSION['lang']]."] - ID:".$myrow['categories_id'];
				}
				else {
					$sous = " - ID:".$myrow['categories_id'];
				}
				print "<option value='".$myrow['categories_id']."'>".strtoupper($myrow['categories_name_'.$_SESSION['lang']])." ".$sous."</option>";
			}
?>
			</select>
        </td>
</tr>

       <tr bgcolor="#FFFFFF">
        <td>Zichtbaar </td>
        <td> <?php print A5;?> <input type="radio" value="yes"  checked name="visu">
             <?php print A6;?> <input type="radio" value="no" name="visu"></td>
       </tr>
       
       
       
       
       
       
       
<?php 
 

// meta_beschrijving ?>
<tr>
<td valign=top><?php print "Meta tag beschrijving";?><br>Maximaal 200 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_3" size='80%' class='vullen' maxlength="200"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_1" size="80%" class='vullen' maxlength="200"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_description_2" size="80%" class='vullen' maxlength="200"></td>
        </tr></table>        
        </td>
</tr>
<?php // meta ?>
<tr>
<td valign=top><?php print "Meta titel";?><br>Maximaal 100 <?php print CAR;?></td>
        <td>
        <table border="0" align="left" cellpadding="1" cellspacing="0"><tr><td>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_3" size="80%" class='vullen' maxlength="100"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_1" size="80%" class='vullen' maxlength="100"></td>
        </tr><tr><td>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><input type="text" name="meta_title_2" size="80%" class='vullen' maxlength="100"></td>
        </tr></table>        
        </td>
</tr>
<tr>
<td colspan="2" align="center"><font color="red"><?php print LAISSER_LES_CHAMPS_VIDES;?></font></td>
</tr>
       
       
       
       
       
       
       
       
       
       
       
       
       
<tr bgcolor="#FFFFFF">
        <td valign=top>
        <?php print A7;?></td>
        <td>
        <?php // categorie_com 1-2-3 ?>
        <table border="0" align="center" cellpadding="1" cellspacing="0" width="100%"><tr><td valign=top>
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;</td><td><textarea cols="70" rows="7" name="comment_3"></textarea></td>
        </tr><tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;</td><td><textarea cols="70" rows="7" name="comment_1"></textarea></td>
        </tr></tr><td valign=top>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;</td><td><textarea cols="70" rows="7" name="comment_2"></textarea>
        </td><tr></table>
        </td>
       </tr>
</table>

<br>

<table width="100%" border="0" cellpadding="5" cellspacing="0" class="TABLE">
<tr>
<td align="center" height="40">
	<input type="submit" value="<?php print A10;?>" class="knop"></td>
</tr>
</table>

</form>

<?php
$result = mysql_query("SELECT * FROM categories ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {
	$papa = $message['categories_id'];
	$fils = $message['parent_id'];
	$visible = $message['categories_visible'];
	$visible = ($message['categories_visible']=='yes')? A5 : "<span color=red><b>".A6."</b></span>";
	$titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b> [ID:".$message['categories_id']."]" : $message['categories_name_'.$_SESSION['lang']];
	$data[] = array($papa,$fils,$titre,$visible);
}

 
if($queryNum>0) { 
print "<br><p align='center'><u>".A11.":</u></p>";
print "<table border='0' cellpadding='2' cellspacing ='3' align='center' class='TABLE' width='700'";
print "<tr>";
print "<td height='19' width='100' align='left'><b>Volgorde</b></td>";
print "<td height='19' align='left'><b>".A14."</b></td>";
print "<td height='19' align='center'><b>".A15."</b></td>";
recur($data,"0","0");
print "</tr>";
print "</table>";
}
?>
</td></tr></table><br><br><br>
  </body>
  </html>
