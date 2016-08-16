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

if(isset($_GET['z'])) $_GET['qty'] = $seuilStock;


function updateAllStock() {
	$optQueryZ = mysql_query("SELECT products_options_stock_stock, products_options_stock_prod_id FROM products_options_stock ORDER BY products_options_stock_prod_id ASC");
	if(mysql_num_rows($optQueryZ)>0) {
		while($optResultZ = mysql_fetch_array($optQueryZ)) {
			$optResultZArray[$optResultZ['products_options_stock_prod_id']][] = $optResultZ['products_options_stock_stock'];
		}
		foreach($optResultZArray AS $key => $value) {
			mysql_query("UPDATE products SET products_qt = ".array_sum($value)." WHERE products_id = '".$key."'");
		}
	}
}

 
if(isset($_POST['stock'])) {
   foreach($_POST['stock'] AS $key => $value) {
  
      $explodeKey = explode("->",$key);
		if(isset($explodeKey[1])) {
 
			if(is_numeric($explodeKey[1])) mysql_query("UPDATE products_options_stock SET products_options_stock_stock = '".$value."' WHERE products_options_stock_id = '".$explodeKey[1]."'");
		}
		else {
 
			mysql_query("UPDATE products SET products_qt = '".$value."' WHERE products_id = '".$key."'");
		}
   }
   foreach($_POST['stockPrice'] AS $key => $value) {
  
      if(is_numeric($value)) {
         mysql_query("UPDATE products SET products_price = '".$value."' WHERE products_id = '".$key."'");
      }
   }
 
	updateAllStock();
	
	$messageS = MAJ_OK;
}

 
if($_SERVER["QUERY_STRING"]!=="") {
   $urlNow = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
   $slashS = "&";
}
else {
   $urlNow = $_SERVER["SCRIPT_NAME"];
   $slashS = "?";
}

 
if(isset($_GET['act']) AND $_GET['act']=='importPrice') {
     $messageS = CETTE_ACTION3;
     $messageS .= "<a href='".$urlNow."&confirm=yes'>".CETTE_ACTION4."</a>";
}
if(isset($_GET['act']) AND $_GET['act']=='importStock') {
     $messageS = CETTE_ACTION1;
     $messageS .= "<a href='".$urlNow."&confirm=yes'>".CETTE_ACTION2."</a>";
}

 
if(isset($_GET['act']) AND isset($_GET['confirm']) AND $_GET['confirm']=='yes') {
 
	$queryPromo = mysql_query("SELECT s.products_id, s.specials_id, p.products_ref FROM specials AS s LEFT JOIN products as p ON (s.products_id = p.products_id)");
	if(mysql_num_rows($queryPromo) > 0) {
		while($rowPromo = mysql_fetch_array($queryPromo)) {
			$inPromo[] = $rowPromo['products_ref'];
			$inPromoId[$rowPromo['products_ref']] = $rowPromo['specials_id'];
		}
	}
 
	$saveRefQuery = mysql_query("SELECT products_ref FROM products");
	while($saveRef = mysql_fetch_array($saveRefQuery)) {
		$refContener[] = $saveRef["products_ref"];
	}
 
	$saveRefQuery2 = mysql_query("SELECT products_options_stock_ref FROM products_options_stock");
	while($saveRef2 = mysql_fetch_array($saveRefQuery2)) {
		if($saveRef2["products_options_stock_ref"]!=="") $refContener[] = $saveRef2["products_options_stock_ref"];
	}

	$file = "import/voorraad.txt";
	$check = filesize($file);
	if($check!==0) {
		if($_GET['act']=="importStock") {
			$field = "products_qt";
			$fp = fopen("import/voorraad.txt","r");
		}
		else {
			$field = "products_price";
			$fp = fopen("import/prijs.txt","r");
		}
		while ( $line = fgets($fp, 1000) ) {
			if(isset($line)) $stockToImport[]=$line; else $stockToImport[]='';
		}
		fclose($fp);
		
		if(count($stockToImport) > 0) {
			foreach($stockToImport as $item) {
				$explodeLine = explode("\t", $item);

 
				if(isset($explodeLine[0]) AND $explodeLine[0]!=="" AND in_array($explodeLine[0],$refContener)) {$st1 = 1;} else {$st1 = 0; $erreur[]=$explodeLine[0];}
 
				if(isset($explodeLine[1]) AND $explodeLine[1]!=="" AND is_numeric(abs($explodeLine[1]))) {$st2 = 1;} else {$st2 = 0; $erreur[]=$explodeLine[0];}
 
				if(isset($inPromo) AND count($inPromo) > 0) {
					if(in_array($explodeLine[0], $inPromo)) $promoIn[] = $explodeLine[0];
				}
 
				$st = $st1+$st2;
				if(isset($st) AND $st==2) {
					if($field=="products_qt") {
						$searchRefQuery = mysql_query("SELECT products_ref FROM products WHERE products_ref = '".$explodeLine[0]."'") or die (mysql_error());
						if(mysql_num_rows($searchRefQuery)>0) {
							mysql_query("UPDATE products SET products_qt = '".$explodeLine[1]."' WHERE products_ref = '".$explodeLine[0]."'");
						}
						else {
							$searchRefQuery2 = mysql_query("SELECT products_options_stock_ref FROM products_options_stock WHERE products_options_stock_ref = '".$explodeLine[0]."'") or die (mysql_error());
							if(mysql_num_rows($searchRefQuery2)>0) {
								mysql_query("UPDATE products_options_stock SET products_options_stock_stock = '".$explodeLine[1]."' WHERE products_options_stock_ref = '".$explodeLine[0]."'") or die (mysql_error());
							}
						}
 
						updateAllStock();
					}
					else {
						$searchRefQuery3 = mysql_query("SELECT products_ref FROM products WHERE products_ref = '".$explodeLine[0]."'") or die (mysql_error());
						if(mysql_num_rows($searchRefQuery3)>0) {
							mysql_query("UPDATE products SET products_price = '".$explodeLine[1]."' WHERE products_ref = '".$explodeLine[0]."'");
						}
					}
				}
			}

			$messageS = MAJ_OK;
 
			if(isset($erreur)) {
				$erreur = array_unique($erreur);
				$erreurNum = count($erreur);
				$messageS .= "<br><img src='im/zzz.gif' width='1' height='5'><br>".ERREUR." : ".$erreurNum;
				if($erreurNum>0) $messageS .= "<br>| ";
				foreach($erreur AS $er) {
					$messageS .= $er." | ";
				}
			}
 
			if(isset($promoIn) AND count($promoIn) > 0) {
	            $messageS.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	            $messageS.= "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align='center'>";
	            $messageS.= ATTENTION."<br><img src='im/zzz.gif' width='1' height='5'><br>";
	            $messageS.="<table border='0' cellpadding='3' cellspacing='0' align='center'>";
	            foreach($promoIn AS $items) {
	            	$queryR = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_ref='".$items."'");
	            	if(mysql_num_rows($queryR) > 0) {
	            		$promoNameResult = mysql_fetch_array($queryR);
	                	$promoName = $promoNameResult['products_name_'.$_SESSION['lang']];
	            	}
	            	else {
	                	$promoName = "??";
	            	}
					$messageS.= "<tr>";
					$messageS.= "<td>&bull; <b>".$items."</b>";
					$messageS.= "</td><td>";
					$messageS.= ($promoName!=="??")? "<a href='promoties_wijzigen_details.php?id=".$inPromoId[$items]."'>".$promoName."</a><br>" : $promoName."</td>";
					$messageS.= "</tr>";
	            }
	            $messageS.="</tr></table>";
	            $messageS.="</tr></table>";
			}
        }
    }
    else {
        $stockToImport[] = '';
        $messageS = "<b>".$file." ".EST_VIDE."</b>";
    }
    
	$urlNow = $_SERVER["SCRIPT_NAME"];
	$slashS = "?";
}

if(empty ($_GET['orderf']))  $_GET['orderf'] = "p.products_qt";
if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(isset($_GET['qty']) AND $_GET['qty']!=="" AND is_numeric($_GET['qty'])) {
   $addQuery1 = "AND (p.products_qt <= ".$_GET['qty']." OR o.products_options_stock_stock <= ".$_GET['qty'].")";
}
else {
   $addQuery1 = "";
}

if(isset($_GET['comp']) AND !empty($_GET['comp'])) {
   if($_GET['comp']=="all") $addQuery2 = ""; else $addQuery2 = "AND p.fournisseurs_id = ".$_GET['comp']."";
}
else {
   $addQuery2 = "";
}
if(!isset($_GET['comp'])) $_GET['comp']="";
if(!isset($_GET['qty'])) $_GET['qty']="";
if(!isset($c)) $c="";

if(isset($_GET['opt_active']) AND $_GET['opt_active']=='yes') {
	$addQuery3 = "AND o.products_options_stock_active = 'yes'";
}
else {
	$addQuery3 = "";
}

if(isset($_GET['orderf']) AND $_GET['orderf'] == "p.products_qt") $editOrder = "Stock";
if(isset($_GET['orderf']) AND $_GET['orderf'] == "p.products_price") $editOrder = PRIX;
if(isset($_GET['orderf']) AND $_GET['orderf'] == "p.products_name_".$_SESSION['lang']."") $editOrder = A1;
if(isset($_GET['orderf']) AND $_GET['orderf'] == "p.products_ref") $editOrder = "Ref";
if(isset($_GET['orderf']) AND $_GET['orderf'] == "p.fournisseurs_company") $editOrder = A2;


 
$hid = mysql_query("SELECT fournisseurs_company, fournisseurs_id
                     FROM fournisseurs
                     WHERE fournisseur_ou_fabricant = 'fournisseur'
                     ORDER BY fournisseurs_company");


 
$hids = mysql_query("SELECT p.products_price, p.products_forsale, p.products_id, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_qt, p.fournisseurs_id, f.fournisseurs_company, f.fournisseurs_id, o . *
						FROM products AS p
						LEFT JOIN fournisseurs AS f ON ( p.fournisseurs_id = f.fournisseurs_id )
						LEFT JOIN products_options_stock AS o ON ( o.products_options_stock_prod_id = p.products_id )
						WHERE p.products_ref != 'GC100'
						".$addQuery1."
						".$addQuery2."
						".$addQuery3."
						ORDER BY ".$_GET['orderf']."
						".$_GET['c1']."") or die (mysql_error());
if(mysql_num_rows($hids)>0) {
	while($hids2Result = mysql_fetch_array($hids)) {
		$prod[$hids2Result['products_id']][] = array("products_id"=>$hids2Result['products_id'],
														"products_options_stock_prod_id"=>$hids2Result['products_options_stock_prod_id'],
														"products_options_stock_id"=>$hids2Result['products_options_stock_id'],
														"products_name"=>$hids2Result['products_name_'.$_SESSION['lang']],
														"products_options_stock_prod_name"=>$hids2Result['products_options_stock_prod_name'],
														"products_ref"=>$hids2Result['products_ref'],
														"products_options_stock_ref"=>$hids2Result['products_options_stock_ref'],
														"products_qt"=>$hids2Result['products_qt'],
														"products_options_stock_stock"=>$hids2Result['products_options_stock_stock'],
														"products_price"=>$hids2Result['products_price'],
														"fournisseurs_id"=>$hids2Result['fournisseurs_id'],
														"fournisseurs_company"=>$hids2Result['fournisseurs_company'],
														"products_options_stock_active"=>$hids2Result['products_options_stock_active'],
														"products_forsale"=>$hids2Result['products_forsale']
														);
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
<div align="center" class="largeBold"><?php print A199;?></div>

<?php
if(mysql_num_rows($hids) > 0) {
 
print "<br>";
print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
print "<td align='center'>";
print "&bull;&nbsp;<a href='x_exporteer_voorraad.php'>".EXPORT."</a>&nbsp;&nbsp;&nbsp;";
print "<img src='im/zzz.gif' width='1' height='5'>";
print "&bull;&nbsp;<a href='x_exporteer_voorraad.php?act=toOrder&seuil=".$_GET['qty']."'>".EXPORTER_ARTICLES_A_COMMANDER."</a>&nbsp;&nbsp;&nbsp;";
print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
print "&bull;&nbsp;<a href='voorraad_beheer.php?act=importStock'>".IMPORTER_FICHIER_CSV."</a>&nbsp;&nbsp;&nbsp;";
print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
print "&bull;&nbsp;<a href='voorraad_beheer.php?act=importPrice'>".IMPORTER_FICHIER_CSV_PRICE."</a>";
print "</td></tr></table>";
print "<br>";


print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
print "<td>Voorraad </td>";
print "<td><input type='text' class='vullen' size='3' name='qty' value='".$_GET['qty']."'>&nbsp;&nbsp;<a href='voorraad_beheer.php'>".TOUT."</a></td>";
print "</tr><tr>";
print "<td>".A4."</td>";
print "<td>";
       print "<select name='comp'>";
       print "<option value='all'>".A5."</option>";
       print "<option value='all'>------------</option>";

       while ($myhidComp = mysql_fetch_array($hid)) {
         if($myhidComp['fournisseurs_id'] == $_GET['comp']) $sel="selected"; else $sel="";
         print "<option value='".$myhidComp['fournisseurs_id']."' ".$sel.">".$myhidComp['fournisseurs_company']."</option>";
       }
       print "</select>";
print "</td>";
print "</tr><tr>";
print "<td colspan='2' align='center'>";
print "<a href='site_config.php#Stock'>".SEUIL_STOCK."</a>: ".$seuilStock;
print "</td>";
print "</tr><tr>";
print "<td colspan='2' align='center'><input type='submit' value='".A6."' class='knop'></td>";
print "</form>";
print "</tr></table>";
print "<br>";

 
if(isset($messageS) AND $messageS!=="") print "<p><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align='center'>".$messageS."</td></tr></table></p>";

print "<form action='".$urlNow."' method='POST'>";
print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='100%'>";
print "<tr bgcolor='#FFFFFF' height='35'>";
        print "<td align='left'>#</td>";
        print "<td align='left'><b>ID</b></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_name_".$_SESSION['lang']."&c1=".$_GET['c1']."&qty=".$_GET['qty']."&comp=".$_GET['comp']."'><b>".A1."</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_ref&c1=".$_GET['c1']."&qty=".$_GET['qty']."&comp=".$_GET['comp']."'><b>Ref</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_qt&c1=".$_GET['c1']."&qty=".$_GET['qty']."&comp=".$_GET['comp']."'><b>Stock</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=products_price&c1=".$_GET['c1']."&qty=".$_GET['qty']."&comp=".$_GET['comp']."'><b>".PRIX."</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?orderf=fournisseurs_company&c1=".$_GET['c1']."&qty=".$_GET['qty']."&comp=".$_GET['comp']."'><b>".A4."</b></a></td>";
        print "<td align='center'><b>Nota</b></td>";
        print "<td align='center'>&nbsp;</td>";
print "</tr>";
$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
$i=1;










foreach($prod AS $item) {
	if($item[0]['products_qt'] == "0") {
		$comFour = "<b><span style='color:red'>".$item[0]['products_qt']."</b></span>";
		$note = "<b>".A7."</b>";
	}
	if($item[0]['products_qt'] < "0") {
		$comFour = "<b><span style='color:red'>".$item[0]['products_qt']."</span></b>";
		$note = "<b><span style='color:red'>".A8."</span></b>";
	}
	if($item[0]['products_qt'] > "0") {
		$comFour = $item[0]['products_qt'];
		$note = "";
	}
	if($item[0]['products_qt'] > "0" AND $item[0]['products_qt'] <= $seuilStock) {
		$comFour = "<b>".$item[0]['products_qt']."</b>";
		$note = A9;
	}
	if($item[0]['products_forsale'] == "no") $noStock="<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>"; else $noStock="";	
				
				if($c=="#E0E0E0") {$c="#F1F1F1";} else {$c="#E0E0E0";}
				print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#CCCCCC';\" onmouseout=\"this.style.backgroundColor='';\">";
 
				print "<td align='left'>".$i++.".</td>";
 
				print "<td align='left'>".$item[0]['products_id']."</td>";
 
				print "<td align='left'>";
				print "<a href='artikel_wijzigen_details.php?id=".$item[0]['products_id']."'>".$item[0]['products_name']."</a>&nbsp;".$noStock;
				print "</td>";
 
				print "<td align='left'>".$item[0]['products_ref']."</td>";
 
				if($item[0]['products_options_stock_prod_name']=="") {
					print "<td align='left'>";
					print "<input type='text' class='vullen' name='stock[".$item[0]['products_id']."]' value='".$item[0]['products_qt']."' size='5'>";
					print "</td>";
				}
				else {
					print "<td align='left'>";
					print "--<span style='font-size:13px; color:#FF0000;'><b>".$item[0]['products_qt']."</b></span>--";
					print "<input type='hidden' name='stock[".$item[0]['products_id']."]' value='".$item[0]['products_qt']."'>";
					print "</td>";
				}
 
				print "<td align='left'><input type='text'  class='vullen' name='stockPrice[".$item[0]['products_id']."]' value='".$item[0]['products_price']."' size='8'></td>";
 
				print "<td align='left'><a href='fab_lev_wijzigen2.php?idStock=".$item[0]['fournisseurs_id']."'>".$item[0]['fournisseurs_company']."</a></td>";
 
				print "<td align='left'>".$note."</td>";
 
				print "<td align='left'><input type='image' src='im/update.gif' alt='".UPDA."' title='".UPDA."'></td>";
				print "</tr>";

				 
					if(!empty($item[0]['products_options_stock_prod_name'])) {
					$d = $c;
					
						foreach($item AS $toto) {
							if($toto['products_options_stock_active']=='no') $ce = "#FFFFCC"; else $ce = "#FFFFCC";
							if($toto['products_options_stock_active']=='yes') {
								$decName = $toto['products_options_stock_prod_name'];
								$decTitle = '';
								$mouseOverColor = "FFEE00";
							}
							else {
								$decName = "<s>".$toto['products_options_stock_prod_name']."</s>";
								$decTitle = "title='".$toto['products_options_stock_prod_name']."'";
								$mouseOverColor = "FF0000";
							}
							print "<tr bgcolor='".$ce."' onmouseover=\"this.style.backgroundColor='#".$mouseOverColor."';\" onmouseout=\"this.style.backgroundColor='';\">";
							print "<td b#gcolor='".$d."'>&nbsp;</td>";
							print "<td b#gcolor='".$d."'>&nbsp;</td>";
							print "<td>";
							print "<img src='im/fleche_right.gif' border='0'>&nbsp;<a href='opties_voorraad.php?id=".$toto['products_id']."&del=tab_values' ".$decTitle."><i>".$decName."</i></a>";
							print "</td>";
 
							print "<td align='left'>";
							if($toto['products_options_stock_active']=='yes') {
								print $toto['products_options_stock_ref'];
							}
							else {
								print "<div align='left' style='font-size:20px;'>--</div>";
							}
							print "</td>";
 
							print "<td align='left'>";
							if($toto['products_options_stock_active']=='yes') {
								print "<input type='text'  class='vullen' name='stock[".$toto['products_id']."->".$toto['products_options_stock_id']."]' value='".$toto['products_options_stock_stock']."' size='5'>";
							}
							else {
								print "<input type='hidden' name='stock[".$toto['products_id']."->".$toto['products_options_stock_id']."]' value='0'>";
								print "<div align='left' style='font-size:20px;'>--</div>";
							}
							print "</td>";
 
							print "<td align='left'>";
							print "&nbsp;";
							print "</td>";
 
							print "<td align='left'>";
							print "&nbsp;";
							print "</td>";
 
							print "<td align='left'>";
					             if($toto['products_options_stock_stock'] == 0) {
					                $note2 = "<b>".A7."</b>";
					             }
					             if($toto['products_options_stock_stock'] < 0) {
					                $note2 = "<b><span style='color:red'>".A8."</span></b>";
					             }
					             if($toto['products_options_stock_stock'] > 0) {
					                $note2 = "";
					             }
					             if($toto['products_options_stock_stock'] > 0 AND $toto['products_options_stock_stock'] <= $seuilStock) {
					                $note2 = A9;
					             }
					            if($toto['products_forsale'] == "no") $noStock="<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>"; else $noStock="";
								if($toto['products_options_stock_active']=='yes') print $note2; else print "<div align='center' style='font-size:20px;'>--</div>";
								
							print "</td>";

							print "<td align='left'><input type='image' src='im/update.gif' alt='".UPDA."' title='".UPDA."'></td>";
							print "</tr>";
						}
					}

}
print "</table>";
print "</form>";
}
else {
   print "<p align='center' class='fontrouge'><b>".A50."</b></p>";
}
?>
<br><br><br>

  </body>
  </html>
