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
$cats = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id
                      FROM categories
                      WHERE categories_noeud != 'R'
                      AND categories_noeud = 'L'
                      ORDER BY categories_name_".$_SESSION['lang']."");

$fourn = mysql_query("SELECT fournisseurs_company, fournisseurs_id
                      FROM fournisseurs
                      WHERE fournisseur_ou_fabricant = 'fournisseur'
                      ORDER BY fournisseurs_company");

 
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
       $result3 = mysql_query("SELECT p.products_name_".$lang.", p.products_id, p.categories_id, f.fournisseurs_company, f.fournisseurs_id, IF(s.specials_id<>'null', 'oui','non') as toto
                                       FROM products as p
                                       LEFT JOIN fournisseurs as f
                                       ON (p.fournisseurs_id = f.fournisseurs_id)
                                       LEFT JOIN specials as s
                                       ON (p.products_id = s.products_id)
                                       WHERE p.categories_id = '".$tab[$x][0]."' 
									            OR p.categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
                                       ORDER BY p.products_name_".$lang."");
               while($produit = mysql_fetch_array($result3)) {
                if($produit['toto'] == 'oui') $result = "<img src='im/promo.gif' title='".EN_PROMO."'>"; else $result = "";
                if(strlen($produit['products_name_'.$lang])>=40) {$ProdNameProdList = substr($produit['products_name_'.$lang],0,40)."...";} else {$ProdNameProdList = $produit['products_name_'.$lang];}
                print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:red'>".$ProdNameProdList."</span>&nbsp;[".$produit['fournisseurs_company']."]&nbsp;".$result;
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

<table border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr><td><?php print A3;?></td></tr></table>

<form action="promoties_toevoegen_categories-st2.php" method="GET" target='main'>

<table border="0" align="center" cellpadding="3" cellspacing = "0" class="TABLE" width="700">
       <tr>
       <td align="center">
        <u><?php print A4;?></u>
       </td>
       <td align="center">
        <u><?php print A5;?></u>
       </td>
       </tr>

       <tr>
        <td align="center">
                <?php
                print  "<select name='id'>";
                print "<option name='id' value='nul'>".A6;
                print "<option name='id' value='all'>".A7;

                while ($categories = mysql_fetch_array($cats)) {
                       print "<option name='id' value='".$categories['categories_id']."'>".$categories['categories_name_'.$_SESSION['lang']];
                       print "</option>";
                }
                print "</select>";
                ?>
        </td>
        <td align="center">
                <?php
                print  "<select name='fourn'>";
                print "<option name='fourn' value='nul'>".A6A;
                print "<option name='fourn' value='all'>".A7A;

                while ($fournisseurs = mysql_fetch_array($fourn)) {
                       print "<option name='fourn' value='".$fournisseurs['fournisseurs_id']."'>".$fournisseurs['fournisseurs_company'];
                       print "</option>";
                }
                print "</select>";
                ?>
        </td>
        <tr>
        <td colspan="2" align="center"><input type="submit" class="knop" value="<?php print A8;?>"></td>
        </tr>

</table>
</form>


<?php
$result = mysql_query("SELECT * FROM categories ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {
    $result2 = mysql_query("SELECT * FROM products
                           WHERE categories_id = '".$message['categories_id']."'
                           ORDER BY products_name_".$_SESSION['lang']."");
    $productsNum = mysql_num_rows($result2);
    $papa = $message['categories_id'];
    $fils = $message['parent_id'];
    $visible = $message['categories_visible'];
    $noeud = $message['categories_noeud'];
    if($message['categories_visible']=='yes') $visible=A12; else $visible="<span style='color:red'><b>".A13."</b></span>";
    if($message['categories_noeud']=="B") $titre = "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>"; else $titre=$message['categories_name_'.$_SESSION['lang']]." [$productsNum]";
    $data[] = array($papa,$fils,$titre,$visible,$noeud);
}


 
if(isset($_GET['list']) AND $_GET['list']==1) {
   print "<table border='0' align='center' cellpadding='5' cellspacing='0'  width='700'><tr><td style='background-color:#FFCC00'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."'><b>".NE_PAS_AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}
else {
   print "<table border='0' align='center' cellpadding='5' cellspacing='0' width='700'><tr><td style='background-color:#CCFF99'>";
   print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?list=1'><b>".AFFICHER."</b></a></div>";
   print "</td></tr></table>";
}

 
if(isset($_GET['list']) AND $_GET['list']==1) {
print "<br>";

print "<table border='0' cellpadding='2' cellspacing ='0' align='center' class='TABLE' width='700'>";
print "<tr>";
print "<td height='19' align='center'><b>".A14."</b></td>";
recur($data,"0","0",$_SESSION['lang']);
print "</tr>";
print "</table>";


	print "<p align='center'>";
	print "<table border='0' align='center' cellpadding='5' cellspacing='0' width='700'><tr><td>";
	print "<b>".A9."</b><br>
       ".A10."<br>
       <span style='color:red'>".A11."</span>&nbsp;[".A5."]<br>
       <img src='im/promo.gif'>: Promotie";
	print "</tr></table><br><br><br>";
}
?>
  </body>
  </html>
