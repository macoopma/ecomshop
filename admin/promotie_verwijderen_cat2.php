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

if(($_GET['id'] == "nul" OR $_GET['fourn'] == "nul")) {
   $message = "<span class='fontrouge'>".A1."</span>"
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A2;?></p>
<table border="0" align="center" cellpadding="5" cellspacing = "5" width="700" class="TABLE">
       <tr>
        <td align="center" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
            <FORM action="promotie_verwijderen_cat1.php" method="post">
            <INPUT TYPE="submit" class="knop "VALUE="<?php print A3;?>">
            </form>
        </td>
       </tr>
</table>

</body>
</html>

<?php
}
else {
$categories = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                      FROM categories
                      WHERE categories_id = '".$_GET['id']."'");
$categorie = mysql_fetch_array($categories);


$fournisseurs = mysql_query("SELECT fournisseurs_company
                      FROM fournisseurs
                      WHERE fournisseurs_id = '".$_GET['fourn']."'");
$fournisseur = mysql_fetch_array($fournisseurs);
 
if($_GET['id'] == "all") {
   $categorie1 = A5;
   $req1 = "";
}
else {
   $categorie1 = strtoupper($categorie['categories_name_'.$_SESSION['lang']]);
   $req1 = "AND categories_id = '".$_GET['id']."'";
}

 
if($_GET['fourn'] == "all") {
   $fournisseur1 = A6;
   $req2 = "";
}
else {
   $fournisseur1 = strtoupper($fournisseur['fournisseurs_company']);
   $req2 = "AND fournisseurs_id = '".$_GET['fourn']."'";
}

 
$result3Z = mysql_query("SELECT products_id
                       FROM products
                       WHERE 1
                       ".$req1."
                       ".$req2."");
$result3ZNum = mysql_num_rows($result3Z);
if($result3ZNum==0) {
   $message2 = "<p align='center'>";
   $message2 = "<table border='0' align='center' cellpadding='3' cellspacing = '0' width='700' class='TABLE'><tr><td>";
   $message2.= A8.": <b>".$categorie1."</b><br>";
   $message2.= A9.": <b>".$fournisseur1."</b><br><br>";
   $message2.= "<span class='fontrouge'><b>".A100."</b></span>";
   $message2.= "</p>";
   $message2.= "<FORM action='promotie_verwijderen_cat1.php' method='post'>";
   $message2.= "<p align='center'>";
   $message2.= "<INPUT TYPE='submit' class='knop' VALUE='".A3."'>";
   $message2.= "</tr></table>";
   $message2.= "</form>";
}

$c1 = "#F1F1F1";
$c2 = "#E8E8E8";
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A2;?></p>

<?php
if($result3ZNum > 0) {
?>
	<form action="promotie_verwijderen_cat3.php" method="GET" target='main'>
	
	<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
	
	<?php print A7;?><br>
	        <?php if($_GET['id'] !== "nul") {?>
	        <tr>
	        <td><?php print A8;?></td>
	        <td>
	        <?php print "<b>".$categorie1."</b>";?>
	        </td>
	        </tr>
	        <?php }?>
	
	        <?php if($_GET['fourn'] !== "nul") {?>
	        <tr>
	        <td width = "120"><?php print A9;?></td>
	        <td width = "230"><?php print "<b>$fournisseur1</b>";?></td>
	        </tr>
	        <?php }?>
	       <input type="hidden" value="<?php print $_GET['id'];?>" name="id">
	       <input type="hidden" value="<?php print $_GET['fourn'];?>" name="fourn">
	</table>
	<br>
	<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
	<tr>
	<td align="center"><input type="submit" class="knop" value="<?php print A10;?>"></td>
	</tr>
	</table>
	
	</form>
<?php
}
else {
   if(isset($message2)) print $message2;
}
?>
</body>
</html>
<?php }?>
