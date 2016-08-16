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

$prods = mysql_query("SELECT products_ref, products_name_".$_SESSION['lang'].", products_id, products_options
                      FROM products
                      WHERE products_ref != 'GC100'
                      AND products_visible = 'yes'
                      AND products_price > '0'
                      AND products_options = 'yes'
                      ORDER BY products_name_".$_SESSION['lang']."");


function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3,$lang) {
global $c;
  if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {
    if($tab[$x][1]==$pere) {
       print "</tr><tr bgcolor='".$c."'>";
       print "<td>";
       print espace3($rang3).$tab[$x][2];
               $result3 = mysql_query("SELECT products_ref, products_forsale, products_name_".$lang.", products_id, products_qt, products_visible, products_options, products_download 
                                       FROM products
                                       WHERE categories_id = '".$tab[$x][0]."' OR categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
                                       ORDER BY products_name_".$lang."");
               while($produit = mysql_fetch_array($result3)) {
				$optQuery = mysql_query("SELECT products_options_stock_prod_id FROM products_options_stock WHERE products_options_stock_prod_id = '".$produit['products_id']."'");
				if(mysql_num_rows($optQuery)>0) {
					$opt="&nbsp;<a href='opties_voorraad.php?id=".$produit['products_id']."&del=tab_values'><img src='im/stock.png' w#idth='7' height='15' title='".GESTION_STOCK."' border='0' style='vertical-align:middle'></a>";
				}
				else {
					 $opt="&nbsp;&nbsp;&nbsp;";
				}
                $do = ($produit['products_download']=="yes")? "&nbsp;<img src='im/download.gif' title='".A8."'>" : "";
                $rupture = ($produit['products_qt']<=0)? "&nbsp;<img src='im/no_stock.gif' title='".A7."'>" : "";
                $vis = ($produit['products_visible']=="no")? "&nbsp;<img src='im/eye.gif' title='".A6."'>" : "";
                $noStock = ($produit['products_forsale']=="no")? "&nbsp;<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>" : "";
                $ProdNameProdList = (strlen($produit['products_name_'.$lang])>=40)? substr($produit['products_name_'.$lang],0,40)."..." : $produit['products_name_'.$lang];
                if($produit['products_options'] == "yes") {
                   print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$opt."&nbsp;<a href='opties_details.php?id=".$produit['products_id']."'><span style='color:red'>".$ProdNameProdList."</span></a> <span style='color:#999999'>- [ID:<b>".$produit['products_id']."</b> | Ref:<b>".$produit['products_ref']."</b>]</span>".$rupture.$vis.$do.$noStock;
                }
                else {
                   print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ProdNameProdList."&nbsp;".$rupture."&nbsp;".$vis."&nbsp;".$noStock;
                }

               }
       print "</td>";
       recur($tab,$tab[$x][0],$rang3+1,$lang);
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
$query0 = mysql_query("SELECT products_id FROM products WHERE products_ref!= 'GC100'");
$result = mysql_query("SELECT * FROM categories ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");

if(mysql_num_rows($query0) > 0 AND mysql_num_rows($prods) > 0) {
?>

<div align="center">-- <?php print A2;?> --</div>
<div align="center"><img src="im/zzz.gif" width="1" height="5"></div>

<table border="0" width="700" align="center" cellpadding="5" cellspacing="0" class="TABLE">
<tr>
<form action="opties_details.php" method="GET" target='main'>
<td align="center">
        <?php
        print  "<select name='id'>";
        while ($produit = mysql_fetch_array($prods)) {
                   print "<option name='id' value='".$produit['products_id']."'>";
 
                   print $produit['products_name_'.$_SESSION['lang']]." - [ID:".$produit['products_id']." | Ref:".$produit['products_ref']."]";
                   print "</option>";
        }
        print "</select>";
        ?>
</td>
</tr>
<tr>
<td align="center">
<input type="submit" class="knop" value="<?php print CONTINUER;?>">
</td>
</form>
</tr>
</table>

<?php
while($message = mysql_fetch_array($result)) {

$result2 = mysql_query("SELECT * FROM products
                       WHERE categories_id = '".$message['categories_id']."'
                       ORDER BY products_name_".$_SESSION['lang']."");
$productsNum = mysql_num_rows($result2);

$papa = $message['categories_id'];
$fils = $message['parent_id'];
$visible = $message['categories_visible'];
$noeud = $message['categories_noeud'];
if($message['categories_visible']=='yes') $visible=A3; else $visible="<span style='color:red'><b>".A4."</b></span>";
if($message['categories_noeud']=="B") $titre = "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>"; else $titre="<b>".$message['categories_name_'.$_SESSION['lang']]."</b> [$productsNum]";

$data[] = array($papa,$fils,$titre,$visible,$noeud);
}


if(isset($_GET['list']) AND $_GET['list']==1) {
   print "<br>";
   print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#FFCC00;'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."'><b>".NE_PAS_AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}
else {
   print "<br>";
   print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#CCFF99;'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?list=1'><b>".AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}


if(isset($_GET['list']) AND $_GET['list']==1) {
print "<br>";
print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'>";
print "<tr>";
print "<td height='19' align='left'><b>".A5."</b></td>";
recur($data,"0","0",$_SESSION['lang']);
print "</tr>";
print "</table>";

print "<p align='center'>";
print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td valign=left>";
print "<img src='im/no_stock.gif' align='absmiddle'> ".A7."<br>";
print "<img src='im/download.gif' align='absmiddle'> ".A8."<br>";
print "<img src='im/eye.gif' align='absmiddle'> : ".A6."<br>";
print "<img src='im/no_stock2.gif' align='absmiddle'> ".OUT_OF_STOCK."<br>";
print "<img src='im/zzz_noir.gif' width='8' height='8' align='absmiddle'> ".GESTION_STOCK;
print "</p></tr></td></table>";
}
}
else {
   print "<center><table border='0' align='center' cellpadding='5' cellspacing='0' width='700' class='TABLE'><tr><td><center><font color='red'><b>".A50."</b></tr></td></table>";
}
?>
<br><br><br>
  </body>
  </html>
