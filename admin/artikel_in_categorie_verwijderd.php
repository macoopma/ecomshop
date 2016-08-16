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
$query0 = mysql_query("SELECT p.products_image, p.products_image2, p.products_image3, p.products_image4, p.products_image5, p.products_options, p.products_id, p.products_name_".$_SESSION['lang'].", c.categories_id, c.categories_name_".$_SESSION['lang']."
                      FROM products as p
                      LEFT JOIN categories as c
                      ON(p.categories_id = c.categories_id)
                      WHERE p.categories_id='".$_GET['id']."'
                      AND p.products_ref!= 'GC100'
                      ");
$rowNum = mysql_num_rows($query0);

$query = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id='".$_GET['id']."'");
$row = mysql_fetch_array($query);

if(!isset($_GET['page'])) {
	if($rowNum>0) {
	    $i=1;

	    print "<table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr style='background:#FFFFFF'>";
	    print "<td colspan='3'>".A2.": <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b></td></tr><tr>";
	    while($nom = mysql_fetch_array($query0)) {
	          print "<td>".$i++." - </td><td>".$nom['products_name_'.$_SESSION['lang']]."</td><td>- ID ".$nom['products_id']."</td></tr><tr>";
	    }
	    print "</table>";

	    
	    
	      print "<p align='center'><br><br><table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td>";
            print "<center><span style='color:#FF0000;'>".A3."</span>";
		print "<form method='GET' action='artikel_in_categorie_verwijderd.php'>";
		print "<input type='hidden' name='page' value='delete'>";
		print "<input type='hidden' name='id' value='".$row['categories_id']."'>";
		print "<p align='center'>";
		print SUPPRIMER_IMAGES."<br>";
		print "<input type='radio' name='delImage' value='yes' checked> ".A4;
		print " | ";
		print "<input type='radio' name='delImage' value='no'> ".A5;
		print "<br><br>";
		
		print "<span style='color:#FF0000;'>".ETES_VOUS_SUR."</span>";
		print "<br><img src='zzz.gif' width='1' height='5'><br>";
		print "<input type='submit' class='knop' name='action' value='   ".strtoupper(A4)."   '>";
		print "&nbsp;&nbsp;&nbsp;";

		print "</tr></td></table>";
		print "</form>";
		 
        print "</p>";
	}
	else {
	    print "<p align='center'>";
          print "<p align='center'><br><br><table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td><center>";
	    print A6." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b>";
	    print "<form action='artikel_in_categorie_verwijderen.php' method='post'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A7."'></p></form>";
	    print "</tr></td></table>";
	}
}

 
if(isset($_GET['page']) and $_GET['page'] == "delete") {

	if(isset($_GET['action']) AND $_GET['action'] == strtoupper(CANCEL)) {
		header("location: artikel_in_categorie_verwijderen.php");
		exit;
	}
	while($nom = mysql_fetch_array($query0)) {
		if(isset($_GET['delImage']) AND $_GET['delImage']=='yes') {
 
			if($nom['products_image']!=="im/no_image_small.gif") @unlink("../".$nom['products_image']);
			if($nom['products_image2']!=="") @unlink("../".$nom['products_image2']);
			if($nom['products_image3']!=="") @unlink("../".$nom['products_image3']);
			if($nom['products_image4']!=="") @unlink("../".$nom['products_image4']);
			if($nom['products_image5']!=="") @unlink("../".$nom['products_image5']);
		}
 
		mysql_query("DELETE FROM products WHERE products_id='".$nom['products_id']."'");
		mysql_query("DELETE FROM specials WHERE products_id='".$nom['products_id']."'");
	    if($nom['products_options']=="yes") mysql_query("DELETE FROM products_id_to_products_options_id WHERE products_id='".$nom['products_id']."'");
	    mysql_query("DELETE FROM products_options_stock WHERE products_options_stock_prod_id='".$nom['products_id']."'");
	    mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_prod_id='".$nom['products_id']."'");
	}
	print "<p align='center'>";
      print "<p align='center'><br><br><table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td>";
	print "<center><span style='color:#FF0000;'>".A8." <b>".strtoupper($row['categories_name_'.$_SESSION['lang']])."</b> ".A9.".</span><br>";
	print "<form action='artikel_in_categorie_verwijderen.php' method='POST'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A7."'></p></form>";
	print "</tr></td></table>";

}
?>
</body>
</html>
