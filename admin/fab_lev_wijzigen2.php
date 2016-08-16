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

if(isset($_GET['id1']) AND $_GET['id1']!=="none") {$idtoto = $_GET['id1'];}
if(isset($_GET['id2']) AND $_GET['id2']!=="none") {$idtoto = $_GET['id2'];}

if(isset($_GET['id1']) AND $_GET['id1'] == "none" AND isset($_GET['id2']) AND $_GET['id2'] == "none") {
    $result=0;
    $message = "<p align='center' class='fontrouge'><b>Selecteer leverancier of fabrikant</b></p>";
}

if(isset($_GET['id1']) AND $_GET['id1'] !== "none" AND isset($_GET['id2']) AND $_GET['id2'] !== "none") {
    $result=0; $message = "<p align='center' class='fontrouge'><b>Selecteer leverancier of fabrikant</b></p>";
}

if(isset($_GET['id2']) AND $_GET['id2'] == "none" AND isset($_GET['id1']) AND $_GET['id1'] !== "none") {
    $ids = $_GET['id1']; $reket = "fournisseurs_id";
}

if(isset($_GET['id2']) AND $_GET['id2'] !== "none" AND isset($_GET['id1']) AND $_GET['id1'] == "none") {
    $ids = $_GET['id2']; $reket = "fabricant_id";
}

if(isset($_GET['idStock']) AND !empty($_GET['idStock'])) {
    $ids = $_GET['idStock']; $reket = "fournisseurs_id";
}
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
if(isset($result) AND $result == 0) {
    print $message;
}
else {
$query = mysql_query("SELECT * FROM fournisseurs WHERE fournisseurs_id= '".$ids."'");
$row = mysql_fetch_array($query);


?>
<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<tr>
<td height="30" colspan="2" align="center"><b><?php print $row['fournisseurs_company'];?></b><br></td>
</tr>
</table>

<form action="fab_lev_update.php" method="GET" enctype="multipart/form-data" target='main'>
 
<input type="hidden" value="<?php print $row['fournisseurs_id'];?>" name="id">

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">

<tr>
<td><?php print A2;?></td>
<td><input type="text" size="25" class="vullen" name="company" value="<?php print $row['fournisseurs_company'];?>"></td>
</tr>
 
<tr>
<td><?php print A3;?></td>
<td><input type="text" class="vullen" size="20" maxlength="30" name="ref" value="<?php print $row['fournisseurs_ref'];?>">&nbsp;<?php print A4;?></td>
</tr>


<tr>
<td><?php print A5;?></td>
<td><input type="text" class="vullen" name="contact1" value="<?php print $row['fournisseurs_firstname'];?>"></td>
</tr>

<tr>
<td><?php print A55;?></td>
<td><input type="text" class="vullen" name="contact2" value="<?php print $row['fournisseurs_name'];?>"></td>
</tr>

<tr>
<td><?php print A6;?></td>
<td><input type="text" class="vullen" name="address" value="<?php print $row['fournisseurs_address'];?>"></td>
</tr>
 
<tr>
<td><?php print A7;?></td>
<td><input type="text" class="vullen" size="25" name="zip" value="<?php print $row['fournisseurs_zip'];?>"></td>
</tr>
 
<tr>
<td><?php print A8;?></td>
<td><input type="text" class="vullen" size="25" name="city" value="<?php print $row['fournisseurs_city'];?>"></td>
</tr>
 
<tr>
<td><?php print A9;?></td>
<td><input type="text" class="vullen" size="25" name="country" value="<?php print $row['fournisseurs_pays'];?>"></td>
</tr>

<tr>
<td><?php print A10;?> 1</td>
<td><input type="text" size="25" class="vullen" name="tel1" value="<?php print $row['fournisseurs_tel1'];?>"></td>
</tr>

<tr>
<td><?php print A10;?> 2</td>
<td><input type="text" class="vullen" size="25" name="tel2" value="<?php print $row['fournisseurs_tel2'];?>"></td>
</tr>

<tr>
<td><?php print A11;?> 1</td>
<td><input type="text" class="vullen" size="25" name="cel1" value="<?php print $row['fournisseurs_cel1'];?>"></td>
</tr>
 
<tr>
<td><?php print A11;?> 2</td>
<td><input type="text" class="vullen" size="25" name="cel2" value="<?php print $row['fournisseurs_cel2'];?>"></td>
</tr>
 
<tr>
<td><?php print FAX;?></td>
<td><input type="text" class="vullen" size="25" name="fax" value="<?php print $row['fournisseurs_fax'];?>"></td>
</tr>
 
<tr>
<td><?php print A12;?></td>
<td><input type="text" class="vullen" size="40" name="website" value="<?php print $row['fournisseurs_link'];?>"></td>
</tr>
 
<tr>
<td>E-mail</td>
<td><input type="text" class="vullen" size="25" name="email" value="<?php print $row['fournisseurs_email'];?>"></td>
</tr>

<tr>
<td valign=top><?php print A13;?></td>
<td><textarea cols="60" rows="4" class="vullen" name="divers"><?php print $row['fournisseurs_divers'];?></textarea></td>
</tr>
</table>

<br>

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<tr>
<td align="left" height="40"><input type=Submit class='knop' value="<?php print A14;?>"'></td>
</form>
<form action="fab_lev_verwijderen.php?id=<?php print $idtoto;?>" method="post">
<td align="right" height="40">
<input type="submit" class="knop" value="<?php print A15;?>"'>
</td>
</form>
</tr>
</table>

<br>

<?php
$queryP = mysql_query("SELECT p.products_id, p.products_name_".$_SESSION['lang'].", p.products_qt, o.*
						FROM products AS p
						LEFT JOIN products_options_stock AS o
						ON (p.products_id = o.products_options_stock_prod_id)
						WHERE p.".$reket." = '".$ids."'") or die (mysql_error());
$queryPNum = mysql_num_rows($queryP);
if($queryPNum>0) {
	$c="";
	$i=1;
    print "<table width='700' border='0' cellpadding='4' cellspacing='0' align='center' class='TABLE'>";
    print "<tr height='30'>";
    print "<td>#</td>";
    print "<td><b>".A17."</b></td>";
	print "<td ><b>".A18."</b></td>";
    print "</tr>";
    while($rowP = mysql_fetch_array($queryP)) {
    	if($c == "#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
    	print "<tr bgcolor='".$c."'>";
    	print "<td width='1'>".$i++.".</td>";
		print "<td align='left'>";
		print "<a href='artikel_wijzigen_details.php?id=".$rowP['products_id']."'>".$rowP['products_name_'.$_SESSION['lang']]."</a>";
			if(!empty($rowP['products_options_stock_prod_name'])) print "<div><a href='opties_voorraad.php?id=".$rowP['products_id']."&del=tab_values'>".$rowP['products_options_stock_prod_name']."</a></div>";
		print "</td>";
		print "<td>";
			if(!empty($rowP['products_options_stock_stock']) OR $rowP['products_options_stock_stock']=="0") $rowP['products_qt'] = $rowP['products_options_stock_stock'];
			$totalStock[]= $rowP['products_qt'];
		print $rowP['products_qt'];
		print "</td>";
		print "</tr>";
    }
		print "<tr height='30'>";
		print "<td align='center' colspan='3'>Totaal voorraad: <b>".array_sum($totalStock)."</b></td>";
		print "</tr>";
    print "</table>";
}

print "<p align='center'>";
if(isset($_GET['from']) AND $_GET['from'] == "fou") print "<a href='verkoop_op_leverancier_details.php?id=".$ids."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'> ".A501."</a>";
print "</p>";
}
?><br><br><br>
</body>
</html>
