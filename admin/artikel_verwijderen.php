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
$query = mysql_query("SELECT products_image, products_image2, products_image3, products_image4, products_image5, products_id, products_name_".$_SESSION['lang'].", products_options FROM products WHERE products_id = '".$_GET['id']."'");
$row = mysql_fetch_array($query);

 
$image[] = $row['products_image'];
$image[] = $row['products_image2'];
$image[] = $row['products_image3'];
$image[] = $row['products_image4'];
$image[] = $row['products_image5'];
 
foreach($image as $key => $value) {
	if($value == "" OR $value=="im/no_image_small.gif") {
		unset($image[$key]);
	}
}
$image = array_values($image);

if($row['products_options'] == "yes") {
	print "<table align='center' cellspacing='0' cellpadding='0'></tr><td align='center'>";
	print "<span style='color:red'>".A2." <b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b> ".A3."</span><br><br>";
	print "<form action='opties_details.php' method='GET'>";
	print "<input type='hidden' name='id' value='".$row['products_id']."'>";
	print "<INPUT TYPE='submit' class='knop' VALUE='".A4."'>";
	print "</form>";
	print "<b>".OU."</b><br><br>";
	print "<form action='artikel_wijzigen.php' method='GET'>";
	print "<INPUT TYPE='submit' class='knop' VALUE='".A5."'>";
	print "</form>";
	print "</td></tr></table>";
}
else {
	if(!isset($_GET['page'])) {
		print "<form method='GET' action='artikel_verwijderen.php'>";
		print "<input type='hidden' name='id' value='".$_GET['id']."'>";
		print "<p align='center'>";
		print "<span style='color:#FF0000; font-size:13px;'>".A2." <b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b> ".A6."</span><br><br>";
		if(isset($image) AND count($image)>0) {
			print SUPPRIMER_IMAGES."<br>";
			print "<input type='radio' name='delImage' value='yes' checked> ".A7." | <input type='radio' name='delImage' value='no'> ".A8."<br><br>";
		}
		print "<span style='color:#FF0000; font-size:12px;'>".ETES_VOUS_SUR."</span>";
		print "<br><img src='zzz.gif' width='1' height='5'><br>";
		print "<input type='submit' name='page' class='knop' value='".strtoupper(A7)."'>&nbsp;&nbsp;&nbsp;";
		print "<input type='submit' name='page' class='knop' value='".strtoupper(CANCEL)."'>";
		print "</p>";
		print "</form>";
	}

      if(isset($_GET['page']) AND $_GET['page'] == strtoupper(CANCEL)) header("location: artikel_wijzigen.php");
      if(isset($_GET['page']) AND $_GET['page'] == strtoupper(A7)) {
              $delete = mysql_query("DELETE FROM products WHERE products_id='".$_GET['id']."'");
              $delete = mysql_query("DELETE FROM specials WHERE products_id='".$_GET['id']."'");
              $delete = mysql_query("DELETE FROM products_id_to_products_options_id WHERE products_id='".$_GET['id']."'");
			  $delete = mysql_query("DELETE FROM products_options_stock WHERE products_options_stock_prod_id='".$_GET['id']."'");
			  $delete = mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_prod_id='".$_GET['id']."'");
 
              if(isset($_GET['delImage']) AND $_GET['delImage']=="yes") {
                  foreach($image AS $items) {
                    if($items!=="" AND $items!=="im/no_image_small.gif") @unlink("../".$items);
                  }
              }
              print "<p align='center'>";
              print A2." <b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b> ".A9."<br>";
              if(isset($_GET['delImage']) AND $_GET['delImage']=="yes") print IMAGES_SUPPRIMEES."<br>";
              print "<form action='artikel_wijzigen.php' method='GET'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A5."'></p></form>";
              print "</p>";
      }
}
?>
</body>
</html>
