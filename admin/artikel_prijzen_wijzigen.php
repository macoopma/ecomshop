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
$c = "";


$alertMessage="";
$next="";
 
if(isset($_POST['selectCat'][0]) AND count($_POST['selectCat'])==1 AND $_POST['selectCat'][0]=='nul') {
   unset($_POST['selectCat']);
}
 
if(isset($_POST['selectCat']) AND count($_POST['selectCat'])>1 AND $_POST['selectCat'][0]=='all') {
   $alertMessage.= SELECT_CAT_OU_ALL."<br>";
}
 
if(isset($_POST['prix']) AND $_POST['prix']=="") $_POST['prix']="0.00";
 
if(isset($_POST['prix']) AND !is_numeric($_POST['prix'])) $_POST['prix']="0.00";
 
if(isset($_POST['prix']) AND $_POST['prix']=="0.00") {
   $alertMessage.= SAISIR_MONTANT."<br>";
}
 
if(isset($_POST['maj']) AND !isset($_POST['selectCat'])) {
   $alertMessage.= SELECT_AU_MOINS_UNE_CAT."<br>";
}

if(isset($_POST['maj']) AND $alertMessage=="") {
   $req="";
   if(isset($productsId)) unset($productsId);
   if(isset($checkPromo)) unset($checkPromo);
   if(isset($articles)) unset($articles);
   
   if($_POST['prix']>0) {
      $prixFinal=$_POST['prix'];
      $majFinal=$_POST['maj'];
      if($_POST['devpou']=="P") $dev="%"; else $dev="";
   }
   if($dev=="%") {
      $products_p = "products_price".$majFinal."(products_price*".$prixFinal."/100)";
   }
   else {
      $products_p = "products_price".$majFinal.$prixFinal;
   }

 
foreach($_POST['selectCat'] AS $item) {
       if($item!=='nul') {
            if($item=="all") {
  
               $req.= "UPDATE products SET products_price = ".$products_p." WHERE products_ref!='GC100'";
               $req.= ";";
               $result3 = mysql_query("SELECT p.products_id, s.specials_id, p.products_price, s.specials_new_price
                                       FROM products AS p
                                       LEFT JOIN specials AS s ON ( p.products_id = s.products_id )
                                       ORDER BY p.products_id ASC");
               if(mysql_num_rows($result3)>0) {
                 while($produit = mysql_fetch_array($result3)) {
                    if($produit['specials_new_price']!=='') {
                           $checkPromo[]=$produit['specials_id']."|".$produit['products_id']."|".$produit['specials_new_price'];
                     }
                  }
                  $doRequest="yesAll";
               }
            }
            else {
               $result3 = mysql_query("SELECT p.products_id, s.specials_id, p.products_price, s.specials_new_price
                                       FROM products AS p
                                       LEFT JOIN specials AS s ON ( p.products_id = s.products_id )
                                       WHERE p.categories_id = '".$item."' 
                                       OR p.categories_id_bis LIKE '%|".$item."|%'
                                       ORDER BY p.products_id ASC");
               if(mysql_num_rows($result3)>0) {
                 while($produit = mysql_fetch_array($result3)) {
                    $articles[]=$produit['products_id'];
                    if($produit['specials_new_price']!='') {
                           $checkPromo[]=$produit['specials_id']."|".$produit['products_id']."|".$produit['specials_new_price'];
                     }
                     $doRequest="yes";
                 }
               }
               else {
                  $alertMessage2 = A50;
                  $doRequest="no";
               }
            }
       }
}

 
	if(isset($doRequest) AND $doRequest=="yes") {
	  $req.= "UPDATE products SET products_price=".$products_p." WHERE ";
	  foreach($articles AS $art) {
	     $req .= "products_id='".$art."' OR ";
	  }
	  $req = substr($req, 0, -4);
	  $req.= ";";
	  mysql_query($req);
	  $alertMessage3 = UPDATE_OK;
	}
	if(isset($doRequest) AND $doRequest=="yesAll") {
	  mysql_query($req);
	  $alertMessage3 = UPDATE_OK;
	}

 
if(isset($checkPromo) AND count($checkPromo)>0) {
      $i=1;
      $alertMessage2 = IMPORTANT."<br><br>";
      $alertMessage2.= "<table cellpadding='2' cellspacing='1' border='0' align='center' class='TABLE' width='700'><tr>";
      $alertMessage2.= "<td align='left'><b>#</b></td><td align='left'><b>ID</b></td><td align='left'><b>".ARTICLE_EN_PROMO."</b></td><td align='left'><b>".PRIX_NORMAL."</b></td><td align='left'><b>".PRIX_PROMO."</b></td>";
      foreach($checkPromo AS $MyPromo) {
               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
               $explodeSpecials = explode("|",$MyPromo);
               $query4 = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_price FROM products WHERE products_id='".$explodeSpecials[1]."'");
               $result4 = mysql_fetch_array($query4);
               $alertMessage2.= "<tr bgcolor='".$c."'>";
               $alertMessage2.= "<td>".$i++."</td>";
               $alertMessage2.= "<td>".$explodeSpecials[1]."</td>";
               $alertMessage2.= "<td><a href='promoties_wijzigen_details.php?id=".$explodeSpecials[0]."'>".$result4['products_name_'.$_SESSION['lang']]."</a></td>";
               $alertMessage2.= "<td><strike><span style='color:#000000'>".$result4['products_price']."</span></strike></td>";
               $alertMessage2.= "<td>".$explodeSpecials[2]."</td>";
               $alertMessage2.= "</tr>";
      }
      $alertMessage2.= "</table>";
}
}


$cats = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id
                      FROM categories
                      WHERE categories_noeud = 'L'
                      ORDER BY categories_name_".$_SESSION['lang']."");
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
$result = mysql_query("SELECT products_id FROM products WHERE products_ref !='GC100'");
if(mysql_num_rows($result) > 0) {
?>
<p align="center"><?php print AJUSTEMENT_A_TOUS;?></p>
<?php 
if(isset($alertMessage)) print "<div align='center' class='fontrouge'><b>".$alertMessage."</b></div><br>";
if(isset($alertMessage3)) print "<table cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><div align='center' class='fontrouge'><b>".$alertMessage3."</b></div></td></tr></table><br>";
if(isset($alertMessage2)) print "<div align='center' class='fontrouge'>".$alertMessage2."</div><br>";
?>
<table width="700" border="0" align="center" cellpadding="8" cellspacing = "0" class="TABLE" width="700">
        <tr bgcolor="#FFFFFF">
<form action="artikel_prijzen_wijzigen.php" method="POST">
        <td width = "120" valign="top"><?php print SELECT_CAT;?></td>
        <td width = "200" valign="top">
                <?php
 
                print "<select name=\"selectCat[]\" size=\"10\" multiple>";
                print "<option value=\"all\">".TOUTES."</option>";
                print "<option value=\"nul\">----</option>";

                while ($categories = mysql_fetch_array($cats)) {
                       print "<option value=\"".$categories['categories_id']."\">".$categories['categories_name_'.$_SESSION['lang']]." [ID:".$categories['categories_id']."]";
                       print "</option>";
                }
                print "</select>";
                
                // Ouvrir arbo des categorie
                $openLeg = "<a href=\"javascript:void(0);\" onClick=\"window.open('uitleg.php?open=categorie','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=520,toolbar=no,scrollbars=yes,resizable=yes');\">";
                $stockLev = $openLeg."<img border=0 src=im/help.png align=bottom></a>";
                print "&nbsp;&nbsp;".$stockLev;
                ?>
                
         </td>
        </tr>
        
        <tr>
        <td width = "120"><?php print AJUSTEMENT;?></td>
        <td width = "200">
            <select name="maj">
               <option value="+" selected>+</option>
               <option value="-">-</option>
            </select>
            &nbsp;
            <input type="text" size="5" class="vullen" name="prix" value="0.00">
            &nbsp;
            <select name="devpou">
               <option value="E" selected><?php print $devise;?></option>
               <option value="P">%</option>
            </select>
            </td>
        </tr>
</table>

<br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td align="center"><input type="submit" class='knop' value="<?php print METTRE_A_JOUR;?>"></td>
</form>
</tr>
</table>
<br>
<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr><td>
<p align="center"><?php print NOTE;?></p>
</tr></td></table>
<?php
}
else {
   print "<p align='center' class='fontrouge'><b>".A50."</b></p>";
}
?>
</body>
</html>
