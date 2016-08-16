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

$prods = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id, IF(s.specials_id<>'null', 'oui','non') as toto
                      FROM products as p
                      LEFT JOIN specials as s
                      USING (products_id)
                      WHERE p.products_ref != 'GC100'
                      AND p.products_visible = 'yes'
                      AND p.products_price > '0'
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

               $result3 = mysql_query("SELECT p.products_forsale, p.products_price, p.products_visible, p.products_name_".$lang.", p.products_id, IF(s.specials_id<>'null', 'oui','non') as toto
                                       FROM products as p
                                       LEFT JOIN specials as s
                                       USING (products_id)
                                       WHERE p.categories_id = '".$tab[$x][0]."' 
									            OR p.categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
                                       ORDER BY p.products_name_".$lang."");
               while($produit = mysql_fetch_array($result3)) {
                
                if($produit['products_forsale'] == "no") $noStock="&nbsp;<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>"; else $noStock="";
                  if(strlen($produit['products_name_'.$lang])>=40) {$ProdNameProdList = substr($produit['products_name_'.$lang],0,40)."...";} else {$ProdNameProdList = $produit['products_name_'.$lang];}
                
                if($produit['toto'] == 'oui') {
                  $result = "&nbsp;<img src='im/promo.gif' title='".ENPROMO."'>";
                  print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#0000FF'>".$ProdNameProdList."</span>".$result.$noStock;
                }
                else {
                     if($produit['products_price'] > 0) {
                        print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='promoties_details.php?id=".$produit['products_id']."?'><span style='color:red'>".$ProdNameProdList."</span></a>".$noStock;
                     }
                     else {
                        print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:#0000FF'>".$ProdNameProdList."</span>".$noStock;
                     }
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
if(mysql_num_rows($prods) > 0) {
?>
<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700">
<tr>
<td align="center">&bull;&nbsp;<a href="promoties_toevoegen_categories.php" target="main"><?php print A2;?></a></td>
</tr>
</table>
<br>

<form action="promoties_details.php" method="GET" target='main'>
        <table border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE" width="700">
        <tr>
        <td align="center">
                <?php
                print  "<select name='id'>";
                while ($produit = mysql_fetch_array($prods)) {
                       if($produit['toto'] !== 'oui') {
                           print "<option name='id' value='".$produit['products_id']."?'>".$produit['products_name_'.$_SESSION['lang']];
                           print "</option>";
                       }
                }
                print "</select>";
                ?>
        </td>
        </tr>
        <tr>
        <td align="center">
        <input type="submit" class="knop"  value="<?php print A3;?>">
        </td>
        </tr>
        </table>
</form>


<?php
$result = mysql_query("SELECT * FROM categories
                       ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result))
{

$result2 = mysql_query("SELECT * FROM products
                       WHERE categories_id = '".$message['categories_id']."' 
                       ORDER BY products_name_".$_SESSION['lang']."");
$productsNum = mysql_num_rows($result2);
$papa = $message['categories_id'];
$fils = $message['parent_id'];
$visible = $message['categories_visible'];
$noeud = $message['categories_noeud'];
if($message['categories_visible']=='yes') $visible=A5; else $visible="<span style='color:red'><b>".A6."</b></span>";
if($message['categories_noeud']=="B") $titre = "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>"; else $titre=$message['categories_name_'.$_SESSION['lang']]." [$productsNum]";

$data[] = array($papa,$fils,$titre,$visible,$noeud);
}

 
if(isset($_GET['list']) AND $_GET['list']==1) {
   print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#FFCC00'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."'><b>".NE_PAS_AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}
else {
   print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td style='background-color:#CCFF99'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?list=1'><b>".AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}

 
if(isset($_GET['list']) AND $_GET['list']==1) {
  print "<br>";
  print "<table border='0' cellpadding='2' cellspacing ='0' align='center' class='TABLE' width='700'>";
  print "<tr>";
  print "<td height='19' align='center'><b>".A7."</b></td>";
  recur($data,"0","0",$_SESSION['lang']);
  print "</tr>";
  print "</table>";
  
  print "<p align='center'>";
  print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td>";
  print A4;
  print "<br>";
  print "<img src='im/promo.gif' align='absmiddle'> Promotie<br>";
  print "<img src='im/no_stock2.gif' align='absmiddle'> ".OUT_OF_STOCK;
  print "</tr></table><br><br><br>";
}
}
else {
   print "<p align='center' class='fontrouge'><b>".A50."</b></p>";
}
?>


  </body>
  </html>
