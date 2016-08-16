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


function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3) {
	global $c, $_SESSION;
  	$c = ($c=="#FFFFFF")? "#FFFFFF" : "#FFFFFF";
  	$tabNb = count($tab);
	for($x=0;$x<$tabNb;$x++) {
		if($tab[$x][1]==$pere) {
			print "</tr><tr bgcolor='".$c."'>";
			print "<td>";
			if($tab[$x][4]=="L") {
		    	print espace3($rang3)."<a href='artikel_in_categorie_verwijderd.php?id=".$tab[$x][0]."'>".$tab[$x][2]."</a>";
		  	}
		  	else {
		    	print espace3($rang3).$tab[$x][2];
		  	}
			$result3 = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_qt, products_visible
			                       FROM products
			                       WHERE categories_id = '".$tab[$x][0]."'
			                       ORDER BY products_name_".$_SESSION['lang']."");
			
			while($produit = mysql_fetch_array($result3)) {
				$rupture = ($produit['products_qt'] <= 0)? "<img src='im/no_stock.gif' title='".RUPTURE_STOCK."'>" : "";
				$vis = ($produit['products_visible']=="no")? "<img src='im/eye.gif' title='".NO_VISIBLE."'>" : "";
				
				$ProdNameProdList = (strlen($produit['products_name_'.$_SESSION['lang']])>=40)? substr($produit['products_name_'.$_SESSION['lang']],0,40)."..." : $produit['products_name_'.$_SESSION['lang']];
				print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:red'>".$ProdNameProdList."</span>&nbsp;".$rupture."&nbsp;".$vis;
			}
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
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
$id = 0;
$i = 0;
$result = mysql_query("SELECT * FROM categories WHERE categories_noeud != 'R' ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
while($message = mysql_fetch_array($result)) {
	$result2 = mysql_query("SELECT products_id FROM products WHERE categories_id = '".$message['categories_id']."'");
	$productsNum = mysql_num_rows($result2);
	$papa = $message['categories_id'];
	$fils = $message['parent_id'];
	$visible = $message['categories_visible'];
	$noeud = $message['categories_noeud'];
	$visible = ($message['categories_visible']=='yes')? A4 : "<span style='color:red'><b>".A5."</b></span>";
	$titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>" : $message['categories_name_'.$_SESSION['lang']]." [".$productsNum."]";
	$data[] = array($papa,$fils,$titre,$visible,$noeud);
}

 
$query0 = mysql_query("SELECT products_id  FROM products");
if(mysql_num_rows($query0) > 1) {
	$prods = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id
	                      FROM categories
	                      WHERE categories_noeud != 'R'
	                      AND categories_noeud = 'L'
	                      ORDER BY categories_name_".$_SESSION['lang']."");
	

	
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<form action='artikel_in_categorie_verwijderd.php' method='GET'>";
	print "<td align='center'>".A100."&nbsp;&nbsp;&nbsp;";
	print "<select name=id>";
	while ($produit = mysql_fetch_array($prods)) {
		print "<option name='id' value='".$produit['categories_id']."'>".$produit['categories_name_'.$_SESSION['lang']];
		print "</option>";
	}
	print "</select>";
	print "&nbsp;&nbsp;&nbsp;<input type='Submit' class='knop' value='".A2."'>";
	print "</td>";
	print "</form>";
	print "</tr>";
	print "</table>";
	
	print "<br>";
	
	print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<td height='19' align='left'><b>".A6."</b></td>";
	recur($data,"0","0");
	print "</tr>";
	print "</table>";
	

	print "<br><br><table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<td>";
	print "<div align='left'>";
	print A3;
	print "</div></tr></td></table>";
}
else {
	print "<p align='center' class='fontrouge'><b>".A50."</b></p>";
}
?>
<br><br><br>
</body>
</html>
