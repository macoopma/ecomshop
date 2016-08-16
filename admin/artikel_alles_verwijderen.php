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
$query0 = mysql_query("SELECT products_image, products_image2, products_image3, products_image4, products_image5, products_options, products_id, products_name_".$_SESSION['lang']." FROM products WHERE products_ref!= 'GC100'");
$rowNum = mysql_num_rows($query0);

if(!isset($_GET['page'])) {
        if($rowNum>0) {
            print "<p align='center'><p align='center'><table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td>";
            print "<center><span style='color:#FF0000;'>".A3."</span><br>";
 
            print "<form method='GET' action='artikel_alles_verwijderen.php'>";
            print "<input type='hidden' name='page' value='delete'>";
            print "<p align='center'>";
			print SUPPRIMER_IMAGES."<br>";
            print "<input type='radio' name='delImage' value='yes' checked> ".A4;
			print " | ";
			print "<input type='radio' name='delImage' value='no'> ".A5;
			print "<br><br>";

			print "<span style='color:#FF0000;'>".ETES_VOUS_SUR."</span>";
			print "<br><img src='zzz.gif' width='1' height='5'><br>";
			print "<input type='submit' class='knop' name='action' value='".A4."'>";
			print "</tr></td></table>";
            print "</form>";
  
            print "</p>";
        }
        else {
            print "<p align='center'>";
            print "<span class='fontrouge'><b>".A6." </b></span>";
            print "<br>";
            print "<form action='artikel_wijzigen.php' method='post'><div align='center'><INPUT TYPE='submit' VALUE='".A7."'></div></form>";
            print "</p>";
        }
}

 
if(isset($_GET['page']) AND $_GET['page'] == "delete") {
 
	if(isset($_GET['action']) AND $_GET['action'] == strtoupper(CANCEL)) {
		header("location: menu.php");
		exit;
	}
    while($nom = mysql_fetch_array($query0)) {
		if(isset($_GET['delImage']) AND $_GET['delImage']=='yes') {
			## Supprimer images
			if($nom['products_image']!=="im/no_image_small.gif") @unlink("../".$nom['products_image']);
			if($nom['products_image2']!=="") @unlink("../".$nom['products_image2']);
			if($nom['products_image3']!=="") @unlink("../".$nom['products_image3']);
			if($nom['products_image4']!=="") @unlink("../".$nom['products_image4']);
			if($nom['products_image5']!=="") @unlink("../".$nom['products_image5']);
		}
 
	if($nom['products_options']=="yes") {mysql_query("DELETE FROM products_id_to_products_options_id WHERE products_id='".$nom['products_id']."'");}
	mysql_query("DELETE FROM specials WHERE products_id='".$nom['products_id']."'");
	mysql_query("DELETE FROM products WHERE products_id='".$nom['products_id']."'");
	mysql_query("DELETE FROM products_options_stock WHERE products_options_stock_prod_id='".$nom['products_id']."'");
	mysql_query("DELETE FROM products_id_to_products_options_id WHERE products_id='".$nom['products_id']."'");
	mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_prod_id='".$nom['products_id']."'");
    }
    print "<p align='center'><table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td>";
    print "<center><span style='color:#FF0000;'>".A8."</span><br>";
    print "<form action='artikel_wijzigen.php' method='post'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A7."'></tr></td></table></form>";
    print "</p>";
}
?>
</body>
</html>
