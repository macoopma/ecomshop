<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");


if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}
 
if(isset($_POST['reset_banner']) AND $_POST['reset_banner'] == "Reset") {
      mysql_query("UPDATE banner SET banner_vue  = 0, banner_hit = 0");
}
 
if(isset($_POST['reset_search']) AND $_POST['reset_search'] == "Reset") {
      mysql_query("UPDATE admin SET search_engine  = ''");
}
 
if(isset($_POST['reset_viewed']) AND $_POST['reset_viewed'] == "Reset ".A113) {
      mysql_query("UPDATE products SET products_viewed  = 0");
}
 
if(isset($_POST['reset_added']) AND $_POST['reset_added'] == "Reset ".A126) {
      mysql_query("UPDATE products SET products_added = 0");
}
 
if(isset($_POST['reset_sold']) AND $_POST['reset_sold'] == "Reset ".strtolower(A125)) {
      mysql_query("UPDATE products SET products_sold  = 0");
}
 
if(isset($_POST['reset_code']) AND $_POST['reset_code'] == "Reset") {
      mysql_query("UPDATE code_promo SET code_promo_enter = 0 WHERE code_promo_stat = 'public'");
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1Z;?></p>

<?php
 
$resultCountProducts = mysql_query("SELECT COUNT(products_id) FROM products WHERE products_ref!='GC100'");
$resultCountProductsNum = mysql_fetch_array($resultCountProducts);
 
$optQueryZ = mysql_query("SELECT COUNT(DISTINCT products_options_stock_prod_id) FROM products_options_stock") or die (mysql_error());
$optionNb = mysql_fetch_row($optQueryZ) ;
 
$resultCountCat = mysql_query("SELECT COUNT(categories_id) FROM categories WHERE categories_noeud='B'");
$resultCountCatNum = mysql_fetch_array($resultCountCat);
 
$resultCountSubCat = mysql_query("SELECT COUNT(categories_id) FROM categories WHERE categories_noeud='L'");
$resultCountCatSubNum = mysql_fetch_array($resultCountSubCat);

 
print "<p align='center'>";
print " &bull; ";
	print ($resultCountProductsNum[0]>1)? $resultCountProductsNum[0]." ".A7."s" : $resultCountProductsNum[0]." ".A7;
print " &bull; ";
if(isset($optionNb[0]) AND $optionNb[0]>0) {
	print ($optionNb[0]>1)? $optionNb[0]." ".A7."s ".AVEC_DECLINAISONS : $optionNb[0]." ".strtolower(A7)." ".AVEC_DECLINAISONS; 
	print " &bull; ";
}

print $resultCountCatNum[0]." ".CAT;
print " &bull; ";
print $resultCountCatSubNum[0]." ".SUB_CAT;
print " &bull; ";
print "</p>";

 
$resultcs = mysql_query("SELECT * FROM banner");
$resultcsNum = mysql_num_rows($resultcs);
if($resultcsNum >0) {
print "<table width='700' border='0' cellpadding='0' cellspacing ='5' align='center'><tr><td align='center'>";
print "<p><table width='100%' border='0' cellpadding='5' cellspacing ='0' align='center' style='background:#FFFF99; border:#FFFFFF 2px solid;'><tr><td align='center' class='largeBold'>".A112."s</td></tr></table></p>";

print "<div align='center'>";
		print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
		print "<INPUT TYPE='submit' name='reset_banner' VALUE='Reset' class='knop'>";
		print "</form>";
print "</div>";
		
	$cs = "";
	print "<table width='700' border='0' cellpadding='4' cellspacing ='0' align='center' class='TABLE'>";
	print "<tr>";
	print "<td height='19' align='left'><b>".A112."</b></td>";
	print "<td height='19' align='left'><b>".A113."</b></td>";
	print "<td height='19' align='left'><b>".A114."</b></td>";
	print "<td height='19' align='left'><b>".VISIB."</b></td>";
	print "</tr>";
	while($banner = mysql_fetch_array($resultcs)) {
		if($cs=="#FFFFFF") {$cs="#F1F1F1";} else {$cs="#FFFFFF";}
		print "<tr bgcolor='".$cs."'>";
		print "<td align='left'>".$banner['banner_desc']."</td>";
		print "<td align='left'>".$banner['banner_vue']."</td>";
		print "<td align='left'>".$banner['banner_hit']."</td>";
		print "<td align='left'>".$banner['banner_visible']."</td>";
		print "</tr>";
	}
	print "</table>";
	print "</td></tr></table><br><br>";
}


$resultcs = mysql_query("SELECT * FROM code_promo");
$resultcsNum = mysql_num_rows($resultcs);
 
$resultcs2 = mysql_query("SELECT distinct(users_remise_coupon_name) 
                           FROM users_orders  
                           WHERE users_remise_coupon_name != '' 
                           AND users_payed = 'yes'
                           ORDER BY users_remise_coupon_name DESC");
$resultcsNum2 = mysql_num_rows($resultcs2);
print "<table width='700' border='0' cellpadding='0' cellspacing ='5' align='center'><tr><td align='center'>";
print "<p><table width='700' border='0' cellpadding='5' cellspacing ='0' align='center' style='background:#FFFF99; border:#FFFFFF 2px solid;'><tr><td align='center' class='largeBold'>".CODE."</td></tr></table></p>";

print "<div align='center'>
		<form action='".$_SERVER['PHP_SELF']."' method='post'>
		<INPUT TYPE='submit' name='reset_code' VALUE='Reset' class='knop'>
		</form> 
		</div>";

print "<table border='0' cellpadding='4' cellspacing ='0' align='center' class='TABLE' width='700'>";
print "<tr>";
print "<td align='center' valign='top' colspan='2'>";

if($resultcsNum >0 OR $resultcsNum2 >0) {
if($resultcsNum >0) {
	$cs = "";
	print "<table width='340' border='0' cellpadding='4' cellspacing ='0' align='center' class='TABLE'>";
	print "<tr>";
	print "<td height='19' align='left'><b>".CODE."</b></td>";
	print "<td height='19' align='left'><b>".STATU."</b></td>";
	print "<td height='19' align='left'><b>".A115."</b></td>";
	print "</tr>";
	while($coupon = mysql_fetch_array($resultcs)) {
		if($cs=="#FFFFFF") {$cs="#F1F1F1";} else {$cs="#FFFFFF";}
		print "<tr bgcolor='".$cs."'>";
		print "<td align='left'>".$coupon['code_promo']."</td>";
		print "<td align='left'>".$coupon['code_promo_stat']."</td>";
		print "<td align='left'>".$coupon['code_promo_enter']."</td>";
		print "</tr>";
	}
	print "</table>";
}

if($resultcsNum2 >0) {
	print "</td><td align='center' valign='top'>";
	$cs = "";
	print "<table width='340' border='0' cellpadding='4' cellspacing ='0' align='center' class='TABLE'>";
	print "<tr>";
	print "<td height='19' align='left'><b>".CODE."</b></td>";
	print "<td height='19' align='left'><b>".A115."</b></td>";
	print "<td height='19' align='left'><b>NIC</b></td>";
	print "</tr>";
	while($couponConf = mysql_fetch_array($resultcs2)) {
		unset($coupZ);
		$resultcsZ = mysql_query("SELECT users_nic, users_id FROM users_orders 
                                	WHERE users_remise_coupon_name = '".$couponConf['users_remise_coupon_name']."'
                                    AND users_payed = 'yes'");
        $resultcsZNum = mysql_num_rows($resultcsZ);
            
        while($couponZ = mysql_fetch_array($resultcsZ)) {
    		$coupZ[] = "<a href='detail.php?id=".$couponZ['users_id']."'>".$couponZ['users_nic']."</a>";
    	}
    	$coupZDisplay = implode('|',$coupZ);
    		   
		if($cs=="#FFFFFF") {$cs="#F1F1F1";} else {$cs="#FFFFFF";}
		print "<tr bgcolor='".$cs."'>";
		print "<td align='left'>".$couponConf['users_remise_coupon_name']."</td>";
		print "<td align='left'>".$resultcsZNum."</td>";
		if($resultcsZNum > 1) {
			print "<td align='left'>";
			if(!isset($_GET['action'])) {print "<a href='statistieken.php?coupon=".$couponConf['users_remise_coupon_name']."&action=view'>".SEE_LIST."</a>";}
			if(isset($_GET['coupon']) AND $_GET['coupon']!=="" AND $_GET['coupon']==$couponConf['users_remise_coupon_name']) {
				$resultcsZ2 = mysql_query("SELECT users_nic, users_id, users_date_payed, users_coupon_note FROM users_orders 
											WHERE users_remise_coupon_name = '".$_GET['coupon']."'
											AND users_payed = 'yes'
											ORDER BY users_date_payed DESC");
				
				while($couponZ2 = mysql_fetch_array($resultcsZ2)) {
					print "<br><a href='detail.php?id=".$couponZ2['users_id']."'><span class='fontrouge'>".$couponZ2['users_nic']."</span></a>";
					print " (".dateFr($couponZ2['users_date_payed'],$_SESSION['lang'])." - ".$couponZ2['users_coupon_note'].")";
				}
			}
			print "</td>";
		}
		else {
			print "<td align='left'>".$coupZDisplay."</td>";
		}
		print "</tr>";
	}
	print "</table>";
	print "</td></tr></table>";
}
else {
	print "</td></tr></table>";
}
print "</td></tr></table><br><br>";
}


print "<table width='700' border='0' cellpadding='0' cellspacing ='5' align='center'><tr><td align='center'>";
print "<p><table width='700' border='0' cellpadding='5' cellspacing ='0' align='center' style='background:#FFFF99; border:#FFFFFF 2px solid;'><tr><td align='center' class='largeBold'>".A1."</td></tr></table></p>";
$c="";
$querySearch = mysql_query("SELECT search_engine FROM admin");
$resultcsNum = mysql_num_rows($querySearch);

if($resultcsNum>0) {
   $enterNum = 1;
	$resultSearch = mysql_fetch_array($querySearch);
 	$mots = explode("|",$resultSearch['search_engine']);
	if(count($mots)>1) {
	     print "<div align='center'>".A4."</div></p>";
	     $motsNb = count($mots)-1;
         for($item=1; $item<=$motsNb; $item++) {
            $mots2 = explode("-", $mots[$item]);
            if($mots2[0]>$enterNum) {$mot[$mots2[1]] = $mots2[0];}
         }

         if(isset($mot)) {
         	print "<div align='center'>";
					print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
					print "<INPUT TYPE='submit' name='reset_search' VALUE='Reset' class='knop'>";
					print "</form>";
				print "</div>";
         	print "<table width='700' border='0' cellpadding='2' cellspacing='0' align='center' class='TABLE'>";
			print "<tr><td height='30'><b>".A2."</b></td><td><b>".A3."</b></td></tr>";
	            arsort($mot);
	            $keys=array_keys($mot);
	            $values=array_values($mot);
	 			$c="";
	 			$motNb = count($mot)-1;
	         	for($a=0; $a<=$motNb; $a++) {
	            		if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
	            		if($values[$a]>$enterNum) {
	            			print "<tr bgcolor='".$c."'><td>".str_replace("!"," ",$keys[$a])."</td><td>".$values[$a]."</td></tr>";
	            		}
	         	}
	         	print "</table><br>";
         }
         else {
		 	print "<div align='center'><b>".A120."</b></div>";
		 }
    }
    else {
	  print "<div align='center'><b>".A120."</b></div>";
    }
}
else {
	print "<div align='center'><b>".A120."</b></div>";
}
print "</td></tr></table><br><br>";


if(!isset($_GET['order'])) $_GET['order'] = "p.products_viewed";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if($_GET['c1']=="ASC") {$_GET['c1']="DESC";} else {$_GET['c1']="ASC";}

$hids = mysql_query("SELECT p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_sold, p.products_viewed, p.products_id, p.products_added, p.categories_id, c.categories_name_".$_SESSION['lang'].", s.specials_id,
                     IF(s.products_id<>'null'
                        AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                        AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day) , 'oui','non') as toto
                     FROM products AS p
                     LEFT JOIN categories AS c
                     ON(p.categories_id = c.categories_id)
                     LEFT JOIN specials AS s
                     ON(p.products_id = s.products_id)
                     WHERE c.categories_noeud != 'R'
                     AND (p.products_sold > 0 OR p.products_viewed > 0 OR p.products_added > 0)
                     ORDER BY ".$_GET['order']."
                     ".$_GET['c1']."");

if(mysql_num_rows($hids) > 0) {
		print "<center><table width='700' border='0' cellpadding='0' cellspacing ='5'><tr><td align='center'>";
		print "<p><table width='700' border='0' cellpadding='5' cellspacing ='0' align='center' style='background:#FFFF99; border:#FFFFFF 2px solid;'><tr><td align='center' class='largeBold'>".A5."</td></tr></table></p>";

		print "<div align='center'>";
		print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
		print "<INPUT TYPE='submit' name='reset_viewed' VALUE='Reset ".A113."' class='knop'>";
      	print "&nbsp;&nbsp;&nbsp;";
      	print "<INPUT TYPE='submit' name='reset_added' VALUE='Reset ".A126."' class='knop'>";
		print "&nbsp;&nbsp;&nbsp;";
      	print "<INPUT TYPE='submit' name='reset_sold' VALUE='Reset ".strtolower(A125)."' class='knop'>";
		print "</form> ";
		print "</div>";


		print "<table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
        print "<tr>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?order=categories_name_".$_SESSION['lang']."&c1=".$_GET['c1']."'>".A6."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?order=products_name_".$_SESSION['lang']."&c1=".$_GET['c1']."'>".A7."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?order=products_viewed&c1=".$_GET['c1']."'>".A8."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?order=p.products_added&c1=".$_GET['c1']."'>".A9."</a></b></td>";
        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?order=p.products_sold&c1=".$_GET['c1']."'>".A125."</a></b></td>";
        print "<td><b>Promo</b></td>";
        print "</tr>";

		while ($myhid = mysql_fetch_array($hids)) {
        	if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
        	if($myhid['toto'] == 'non') {
            	$titi = "<a href='promoties_details.php?id=".$myhid['products_id']."?'>".A11."</a>";
            	$titiZ = "";
        	}
        	else {
            	$titi = "<a href='promoties_wijzigen_details.php?id=".$myhid['specials_id']."' target='main'><span color='fontrouge'><b>".A10."</b></span></a>";
            	$titiZ = "OUI";
        	}
        	$stat[] = $myhid['categories_name_'.$_SESSION['lang']]
                  ."|||".$myhid['products_ref']
                  ."|||".$myhid['products_name_'.$_SESSION['lang']]
                  ."|||".$myhid['products_viewed']
                  ."|||".$myhid['products_added']
                  ."|||".$myhid['products_sold']
                  ."|||".$titiZ;
        print "<tr bgcolor='".$c."'><td width='15' align='left'>".$myhid['categories_name_'.$_SESSION['lang']]."</td>";
        print "<td><a href='artikel_wijzigen_details.php?id=".$myhid['products_id']."'>".$myhid['products_name_'.$_SESSION['lang']]."</a></td>";
        print "<td><span class='fontrouge'><b>".$myhid['products_viewed']."</b></span></td>";
        print "<td align='left'><span class='fontrouge'><b>".$myhid['products_added']."</b></span></td>";
        print "<td align='left'><span class='fontrouge'><b>".$myhid['products_sold']."</b></span></td>";
        print "<td>".$titi."</td>";
        print "</tr>";
}
		print "</table>";

	if(count($stat)>0) {
		print "<div align='center'>";
		print "<form action='x_exporteer_stat.php' method='post'>";
		print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
		foreach($stat AS $st) {
			print "<input type='hidden' name='val[]' value='".$st."'>";
		}
		print "<INPUT TYPE='submit' name='export' VALUE='".EXPORTER."' class='knop'>";
		print "</form> ";
		print "</div>";
	}
print "</td></tr></table><br><br><br>";
}
?>

  </body>
  </html>
