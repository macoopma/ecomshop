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


if(isset($_GET['do']) AND $_GET['do']=="delete") {
      $selectCatQueryToDelete = mysql_query("SELECT categories_id FROM categories WHERE categories_noeud='L'");
   if(mysql_num_rows($selectCatQueryToDelete)) {
      $message = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
      $message.= "<div align='center' class='fontrouge'><b>".ETES_VOUS_SUR."</b></div>";
      $message.= "<br>";
      $message.= "<div align='center'><a href='sub_categorie_wijzigen.php?do=confirm'>".OUI."</a> | <a href='sub_categorie_wijzigen.php'>".NON."</a></div>";
      $message.= "</td></tr></table>";
      $message.= "<br>";
   }
   else {
      $message = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
      $message.= "<div align='center' class='fontrouge'><b>".A11."</b></div>";
      $message.= "</td></tr></table>";
      $message.= "<br>";
   }
}

if(isset($_GET['do']) AND $_GET['do']=="confirm") {
      $selectProdcuts = mysql_query("SELECT products_id FROM products WHERE products_ref!='GC100'");
      $selectProdcutsNum = mysql_num_rows($selectProdcuts);
      if($selectProdcutsNum>0) {
        $message = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
        $message.= "<div align='center' class='fontrouge'><b>".VEUILLEZ_SUPPRIMER_ARTICLES."</b></div>";
        $message.= "<br>";
        $message.= "<div align='center'><a href='artikel_wijzigen.php'><span class='fontrouge'>".SUPPRIMER_ARTICLES."</span></a></div>";
        $message.= "</td></tr></table>";
        $message.= "<br>";      
      }
      else {
         mysql_query("DELETE FROM categories WHERE categories_noeud='L'");
        $message = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
        $message.= "<div align='center' class='fontrouge'><b>".TOUTES_LES_CAT_SUP."</b></div>";
        $message.= "<br>";
        $message.= "<div align='center'><a href='categorie_wijzigen.php'><span class='fontrouge'>".VOIR_CAT."</span></a></div>";
        $message.= "</td></tr></table>";
        $message.= "<br>";
      }
}

function resizeImage($imageToResize,$haut) {
global $haut_im;
                 $size = getimagesize($imageToResize);
                 if($size[1] >= $haut)
                     {
                      $hauteur = $haut;
                      $reduction_hauteur = $hauteur/$size[1];
                      $largeur = ceil($size[0]*$reduction_hauteur);
                     }
                     else
                     {
                      $hauteur = $size[1];
                      $largeur = $size[0];
                     }
                     return "width='".$largeur."' height='".$hauteur."'";
}


if(isset($_POST['action']) AND $_POST['action']=='update') {
   foreach($_POST['order'] AS $key => $value) {
      if(is_numeric($value) AND $value!=='') {
         mysql_query("UPDATE categories SET categories_order = '".$value."' WHERE categories_id = '".$key."'");
      }
      else {
         mysql_query("UPDATE categories SET categories_order = '0' WHERE categories_id = '".$key."'");
      }
   }
}

 
function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3) {
	global $c;
	if($c=="") $c = ""; else $c = "";
	$tabNb = count($tab);
	for($x=0;$x<$tabNb;$x++) {
	    if($tab[$x][1]==$pere) {
	    	$im = "../".$tab[$x][5];
	       	print "</tr><tr onmouseover=\"this.style.backgroundColor='#FFFFCC';\" onmouseout=\"this.style.backgroundColor='';\">";
	       	print "<td>";
	        	if($tab[$x][4]=="L") {
	            	print "<input type='text' class='vullen' name='order[".$tab[$x][0]."]' value='".$tab[$x][6]."' size='4'>";
	    		}
	       	print "</td>";
	       	print "<td>";
	       		if($tab[$x][4]=="B") {
				   	$u = "&bull;&nbsp;";
				   	$htmlComDisplay = "";
				}
				else {
					$u = "";
					$htmlComDisplay = ($tab[$x][7]=="")? "" : "&nbsp;<img src='im/modify.gif' title='' align='absbottom'>";
				}
	       		print espace3($rang3).$u.$tab[$x][2].$htmlComDisplay;
	       	print "</td>";
	       	print "<td>";
	       		print "<div align='center'>".$tab[$x][3]."</div>";
	       	print "</td>";
	       	print "<td>";
	        	if($im == "../im/zzz.gif") {
	            	print "<div align='center'>--</div>";
	            }
	            else {
	            	print "<div align='center'><img src='".$im."' ".resizeImage("../".$tab[$x][5],30)." title='".$tab[$x][5]."' alt='".$tab[$x][5]."'></div>";
	            }
	       	print "</td>";
	       	print "<td>";
	       	if($tab[$x][4]=="B") {
	    		print "";
	       	}
	       	else {
	            print "<div align='center'><a href='sub_categorie_wijzigen_detail.php?id=".$tab[$x][0]."' style='background:none; decoration:none'><img src='im/details.gif' border='0' title='".A8."'></a></div>";
	       	}
	       	print "</td>";
	       	print "<td>";
	       	if($tab[$x][4]=="B") {
	    		print "";
	       	}
	       	else {
	        	print "<div align='center'><a href='sub_categorie_verwijderen.php?id=".$tab[$x][0]."' style='background:none; decoration:none'><img src='im/supprimer.gif' border='0' title='".A9."'></a></div>";
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
 
$selectCatQuery = mysql_query("SELECT categories_id FROM categories WHERE categories_noeud='L'");
$selectCatQueryNum = mysql_num_rows($selectCatQuery);
 
if(!isset($message) AND $selectCatQueryNum>0) {
   print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
   print "<td align='center'>";
   print "&bull;&nbsp;<a href='sub_categorie_wijzigen.php?do=delete'>".SUPPRIMER_ALL_SOUSCAT."</a>";
   print "</tr></td></table><br>";
}
 
if(isset($message)) print $message;

$result = mysql_query("SELECT * FROM categories ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;

if($selectCatQueryNum > 0) {
	while($message = mysql_fetch_array($result)) {
	    $result2 = mysql_query("SELECT * FROM products
	                           WHERE categories_id = '".$message['categories_id']."' OR categories_id_bis LIKE '%|".$message['categories_id']."|%'
	                           ORDER BY products_name_".$_SESSION['lang']."");
	    $productsNum = mysql_num_rows($result2);
	    $papa = $message['categories_id'];
	    $fils = $message['parent_id'];
	    $visible = $message['categories_visible'];
	    $noeud = $message['categories_noeud'];
	    $image = $message['categories_image'];
	    $visible = ($message['categories_visible']=='yes')? "<img src='im/val.gif' title='".A3."'>" : "<img src='im/no_stock.gif' title='".A4."'>";
	    $titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>" : "<a href='sub_categorie_wijzigen_detail.php?id=".$message['categories_id']."'><span style='background:none; padding:2px;'>".$message['categories_name_'.$_SESSION['lang']]."</span></a> [".$productsNum."]";
	    $_order = $message['categories_order'];
	    $htmlCom = $message['categories_comment_'.$_SESSION['lang']];
	    $data[] = array($papa,$fils,$titre,$visible,$noeud,$image,$_order,$htmlCom);
	}
	
 
	print "<table border='0' cellpadding='3' cellspacing ='5' align='center' class='TABLE' width='700'><tr>";
	print "<form action='sub_categorie_wijzigen.php' method='POST'>";
	print "<input type='hidden' name='action' value='update'>";
	print "<td>";
	print "<table border='0' cellpadding='2' cellspacing ='0' align='center' width='100%'>";
	print "<tr>";
	print "<td height='25' align='left' width='100'><b>Volgorde</b></td>";
	print "<td height='25' align='left'><b>".A5."</b></td>";
	print "<td height='25' align='center'><b>".A6."</b></td>";
	print "<td height='25' align='center'><b>".A7."</b></td>";
	print "<td width='25' height='19' align='center'>&nbsp;</td>";
	print "<td width='25' height='19' align='center'>&nbsp;</td>";
	recur($data,"0","0");
	print "</tr>";
	print "</table>";
	
	print "<br>";
	print "<table border='0' cellpadding='0' cellspacing ='0' align='center'>";
	print "<tr>";
	print "<td align='center'>";
	if($selectCatQueryNum>0) print "<input type='submit' value='".UPDATE."' class='knop'>";
	print "<br><img src='im/zzz.gif' width='1' height='7'><br>";
	print "</td>";
	print "</tr>";
	print "</table>";
	print "</td>";
	print "</form>";
	print "</tr></table>";
}
else {
   print "<center><table border='0' cellpadding='3' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td><p align='center' class='fontrouge'><b>".A11."</b></p>";
   print "<p align='center'><a href='sub_categorie_toevoegen.php'>".AJOUTER_CAT."</a></p></tr></td></table>";
}
?>
<br><br><br>
  </body>
  </html>
