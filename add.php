<?php
session_start();
include('configuratie/configuratie.php');
 
if(isset($_SESSION['devisNumero'])) {
    $goto100 = $_SESSION['ActiveUrl'];
    header("Location: $goto100");
    exit;
}

 
function adjust_cart() {
GLOBAL $_SESSION, $_GET;
  $split2 = explode(",", $_SESSION['list']);
  foreach($split2 as $item2) {
     $check2 = explode("+", $item2);
     if($check2[0]==$_GET['id'] AND $check2[6]==$_GET['options']) {
		if(!empty($check2[6])) {
	   		$_opt = explode("|",$check2[6]);
			## session update option price
			$lastArray = $_opt[count($_opt)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
				$explodeLastArray = explode("epz", $lastArray);
				$priceZ = $explodeLastArray[0];
			}
			else {
				$priceZ = 0;
			}
		}
		else {
			$priceZ = 0;
		}
	}
  }
  return $priceZ;
}

 
function discount_on_quantity() {
	GLOBAL $price, $_GET;
	$prodsCount = mysql_query("SELECT * FROM discount_on_quantity
								WHERE discount_qt_prod_id='".$_GET['id']."'
								AND discount_qt_qt <= '".$_GET['amount']."'
								ORDER BY discount_qt_discount ASC") or die (mysql_error());
	$prodsCountNum = mysql_num_rows($prodsCount);
	if($prodsCountNum > 0) {
		while($prodsCountResult = mysql_fetch_array($prodsCount)) {
				$prods[$prodsCountResult['discount_qt_qt']] = $prodsCountResult['discount_qt_discount'].$prodsCountResult['discount_qt_value'];
		}
		if(isset($prods) AND count($prods)>0) {
			$discountOnQtFinal = end($prods);
			$cutExt = explode('%',$discountOnQtFinal);
			if(isset($cutExt[1])) {
				$priceW = $price-($price*$cutExt[0]/100);
			}
			else {
				$discountOnQtFinalNumeric = str_replace('euro','',$discountOnQtFinal);
					if(is_numeric($discountOnQtFinalNumeric)) {
						$priceW = $price-$discountOnQtFinalNumeric;
					}
					else {
						$priceW = $price;
					}
			}
			return $priceW;
		}
		else {
			$priceW = $price;
		}
	}
	else {
		$priceW = $price;
	}
	return $priceW;
}







 
$_GET['amount'] = str_replace("+","-",$_GET['amount']);
$_GET['amount'] = str_replace(",","-",$_GET['amount']);
if(isset($_GET['options'])) {$_GET['options']  = str_replace("+","-",$_GET['options']);}
if(isset($_GET['options'])) {$_GET['options']  = str_replace(",","-",$_GET['options']);}
if(!isset($_GET['options'])) {$_GET['options'] = '';}
(is_numeric($_GET['amount']))? $_GET['amount'] = round($_GET['amount']) : $_GET['amount']=1;
$_GET['amount'] = abs($_GET['amount']);
if(!isset($_GET['deee']) OR empty($_GET['deee'])) {$_GET['deee'] = 0;}

 
if(!isset($_GET['statut'])) {
	$checkStok = mysql_query("SELECT products_qt FROM products WHERE products_id = '".$_GET['id']."'");
	$checkStokQt = mysql_fetch_array($checkStok);
	$checkStokTotal = $checkStokQt['products_qt'];
    if($_GET['amount'] > $checkStokTotal) {
    	$_GET['amount'] = $checkStokTotal;
    	$_SESSION['stockInf'] = 1;
    }
}

 

if(isset($_GET['optionNum']) AND $_GET['optionNum'] > 0) {
	for($i=1; $i<=$_GET['optionNum']; $i++) {
		$opt1 = explode("/",$_GET['option'][$i]);
		$optionList[] = $opt1[0];
		$priceMod[] = $opt1[1];
		$optionPoids[] = $opt1[2];
	}
	$updatePrice = array_sum($priceMod);
	$_GET['options'] = implode("|",$optionList);
	 
	$_GET['options'] = $_GET['options']."|".$updatePrice."epz";
	$optionPoids2 = implode("|",$optionPoids);
}
else {
	$updatePrice = 0;
	$optionPoids2 = 0;
}

 
if(isset($_GET['options']) AND $_GET['options']!=='') {
	$opt1 = explode("|", $_GET['options']);
	$opt2 = array_pop($opt1);
	$opt2 = implode(" | ", $opt1);
	$optQuery = mysql_query("SELECT products_options_stock_stock, products_options_stock_ref, products_options_stock_active FROM products_options_stock 
								WHERE products_options_stock_prod_id = '".$_GET['id']."'
								AND products_options_stock_prod_name = '".$opt2."'
							") or die (mysql_error());
	if(mysql_num_rows($optQuery)>0) {
		$optResult = mysql_fetch_array($optQuery);
		if($optResult['products_options_stock_active']=='no' OR ($_GET['amount']>$optResult['products_options_stock_stock'] AND $actRes=="non")) {
				$_SESSION['stockInf']=3;
			    $goto100 = $_SESSION['ActiveUrl'];
			    header("Location: $goto100");
			    exit;
		}
		else {
			if($optResult['products_options_stock_ref']!=='') $_GET['ref'] = $optResult['products_options_stock_ref'];
			if($optResult['products_options_stock_stock']>0 AND $_GET['amount'] > $optResult['products_options_stock_stock']) {
				$_GET['amount'] = $optResult['products_options_stock_stock'];
				$_SESSION['stockInf']=1;
			}
		}
	}
}

 
if($_GET['amount']>0) {
	$majProdViewed = mysql_query("UPDATE products SET products_added = products_added+1 WHERE products_id='".$_GET['id']."'");
}
 
if($_GET['ref'] !== "GC100") {
	$result = mysql_query("SELECT p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
							FROM products AS p
							LEFT JOIN specials AS s
							ON (s.products_id = p.products_id)
							WHERE p.products_id = '".$_GET['id']."'") or die (mysql_error());
	$a_row = mysql_fetch_array($result);
		if(empty($a_row['specials_new_price'])) {
			$price = $a_row['products_price'];
		}
		else {
			if($a_row['specials_visible']=="yes") {
				$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$dateMaxCheck = explode("-",$a_row['specials_last_day']);
				$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
				$dateDebutCheck = explode("-",$a_row['specials_first_day']);
				$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
				if($dateDebut <= $today  AND $dateMax >= $today) {
					$price = $a_row['specials_new_price'];
				}
				else {
					$price = $a_row['products_price'];
				}
			}
			else {
				$price = $a_row['products_price'];
			}
		}
		## Mise à jour prix article avec option
		$price = $updatePrice+$price;
		## Mise à jour prix article avec option form +|- cart mod and cart
		if(isset($_GET['adjust_cart']) AND $_GET['adjust_cart']==1) {
			$price = $price+adjust_cart();
		}
		 
		if(isset($_SESSION['reduc'])) {
			$price = sprintf("%0.2f",$price-($price*$_SESSION['reduc']/100));
		}
		else {
			$price = sprintf("%0.2f",$price);
		}
}
else {
	if(isset($_GET['gift_amount'])) {
		if($_GET['gift_amount'] > $seuilGc ) {
			$price = sprintf("%0.2f",$_GET['gift_amount']);
		}
		else {
			$price = sprintf("%0.2f",$seuilGc);
		}
	}
	else {
		$price = $seuilGc;
	}
}

 
$price = discount_on_quantity();

 
if(!isset($_SESSION['list'])) $_SESSION['list'] = "";

if($_SESSION['list']=="") {
	$_SESSION['list'] = $_GET['id']."+".$_GET['amount']."+".$price."+".$_GET['ref']."+".$_GET['name']."+".$_GET['productTax']."+".$_GET['options']."+".$_GET['deee']."+".$optionPoids2;
}
else {
	$split = explode(",", $_SESSION['list']);
	foreach ($split as $item) {
		$check = explode("+", $item);
		if($check[2]!==$price) $newPrice=$price; else $newPrice=$check[2];
		if($check[0]==$_GET['id'] AND $check[6]==$_GET['options']) {
			if($_GET['amount'] == 0) {$rem = "";} else {$rem = $check[0]."+".$_GET['amount']."+".$newPrice."+".$check[3]."+".$check[4]."+".$check[5]."+".$check[6]."+".$check[7]."+".$check[8];}
			$_SESSION['list'] = str_replace($check[0]."+".$check[1]."+".$check[2]."+".$check[3]."+".$check[4]."+".$check[5]."+".$check[6]."+".$check[7]."+".$check[8],$rem,$_SESSION['list']);
			
			$last = substr($_SESSION['list'],-1);
			if($last == ",") $_SESSION['list'] = substr_replace($_SESSION['list'],'',-1,1);
			
			$reste = substr($_SESSION['list'], 0, 1);
			if($reste == ",") $_SESSION['list'] = substr_replace($_SESSION['list'],'',0,1);
			
			$pos = strpos($_SESSION['list'], ",,");
			if($pos > 0) $_SESSION['list'] = str_replace(",,",",",$_SESSION['list']);
			$replace = "ok";
		}
	}
	if(!isset($replace)) {
		$_SESSION['list'] = $_SESSION['list'].",".$_GET['id']."+".$_GET['amount']."+".$price."+".$_GET['ref']."+".$_GET['name']."+".$_GET['productTax']."+".$_GET['options']."+".$_GET['deee']."+".$optionPoids2;
	}
}

 
$goto2 = $_SESSION['ActiveUrl'];
$url_id11 = $_SESSION['ActiveUrl'];
if(preg_match("/\bbeschrijving.php\b/i", $url_id11)) $url_id11=$url_id11."&a=b";
$removeFromUrl = array("&var=session_destroy", "?var=session_destroy", "&var=upd", "?var=upd");

$goto = str_replace($removeFromUrl, "", $url_id11);
$goto = str_replace(".php&", ".php?", $goto);

if(isset($_GET['express']) OR isset($_GET['express_x'])) {
   if(isset($_SESSION['account'])) {
      $goto = "payment.php";
   }
   else {
      $goto = "login.php";
   }
}
header("Location:".$goto);
?>
