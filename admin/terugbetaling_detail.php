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
$stock = "";
$hoy = date("Y-m-d H:i:s");


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}



function duplicate_field($idOrderId) {
    GLOBAL $bddName, $factPrefixe;
 
    $hoy = date("Y-m-d H:i:s");
    
     
	$fieldsName = mysql_query("SHOW COLUMNS FROM users_orders");
	if (mysql_num_rows($fieldsName) > 0) {
	   while ($row = mysql_fetch_assoc($fieldsName)) {
	      $champs[] = $row['Field'];
	   }
	}
	
     
    $statTable = mysql_fetch_array(mysql_query("SHOW TABLE STATUS LIKE 'users_orders'"));
    $nextId = $statTable['Auto_increment'];

     
    $queryToDuplicate = mysql_query("SELECT * FROM users_orders WHERE users_id='".$idOrderId."'");
    $resultToDuplicate = mysql_fetch_array($queryToDuplicate);
    
     
    $factNum = $resultToDuplicate['users_fact_num'];
    
     
    $queryUpFact = mysql_query("SELECT users_fact_num FROM users_orders WHERE users_payed = 'yes' ORDER BY users_fact_num ASC");
      if(mysql_num_rows($queryUpFact)>0) {
         while($rowUpFact = mysql_fetch_array($queryUpFact)) {
         	$factNumExplode = explode("||",$rowUpFact['users_fact_num']);
            $usersFactArray[] = (isset($factNumExplode[1]))? $factNumExplode[1] : $factNumExplode[0];
         }
            sort($usersFactArray);
            $lastFact = end($usersFactArray);
            if($lastFact=='') {
               $nextFactNum = $factNum+1;
               $nextFactNum = $factPrefixe.$nextFactNum;
            }
            else {
               $nextFactNum = $lastFact+1;
               $nextFactNum = $factPrefixe.$nextFactNum;
            }
      }
      else {
         $nextFactNum = "";
      }

    
    for($i=0; $i<=sizeof($champs)-1; $i++) {
		$signe = ($resultToDuplicate[$champs[$i]]>0)? "-" : "";
		if($champs[$i]=='users_id') $resultToDuplicate[$champs[$i]] = $nextId;
		if($champs[$i]=='users_nic') $resultToDuplicate[$champs[$i]] = "TERUG-".$factNum;
		if($champs[$i]=='users_fact_num') $resultToDuplicate[$champs[$i]] = $nextFactNum;
		if($champs[$i]=="users_multiple_tax") {
			$explodeTax = explode("|", $resultToDuplicate[$champs[$i]]);
			foreach($explodeTax AS $item) {
				$explodeTax2 = explode('>', $item);
				$yo3[] = ($explodeTax2[1]>0)? $explodeTax2[0].">-".$explodeTax2[1] : $explodeTax2[0].">".$explodeTax2[1];
			}
			$multipleTax = implode("|", $yo3);
			$resultToDuplicate[$champs[$i]] = $multipleTax;
		}
		if($champs[$i]=='users_date_added') $resultToDuplicate[$champs[$i]] = $hoy;
		if($champs[$i]=='users_date_payed') $resultToDuplicate[$champs[$i]] = $hoy;
		if($champs[$i]=='users_total_to_pay') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_shipping_price') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_ship_ht') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_ship_tax') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_sup_ttc') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_sup_ht') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_sup_tax') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_products_ht') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_products_tax') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_remise') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_account_remise_app') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_remise_coupon') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_contre_remboursement') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_affiliate_amount') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_remise_gc') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_deee_ht') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		if($champs[$i]=='users_deee_tax') $resultToDuplicate[$champs[$i]] = $signe.$resultToDuplicate[$champs[$i]];
		$yo[] = $champs[$i]." = '".$resultToDuplicate[$champs[$i]]."',";
	}
     
    $yo2 = implode(" ",$yo);
    
     
    $yo2 = substr(trim($yo2), 0, -1);
    
     
    mysql_query("INSERT INTO users_orders SET ".$yo2."");
    
     
    $users_commentA = '<span style="color:#CC0000"><b>'.CORRECTION_FACTURE.' #<a href="detail.php?id='.$idOrderId.'">'.str_replace("||","",$factNum).'</a></b></span>';
    $users_commentA.= ($resultToDuplicate['users_comment']=="")? '' : '<br>---<br>'.$resultToDuplicate['users_comment'];
    mysql_query("UPDATE users_orders SET users_comment = '".$users_commentA."' WHERE users_id='".$nextId."'") or die (mysql_error());
    
    $_SESSION['nextFactNum'] = $nextFactNum;
    $_SESSION['nextId'] = $nextId;
}




 
 
if(isset($_GET['action']) AND $_GET['action']==A1) {

	if($_GET['todo']== "rembourser") {
		
		duplicate_field($_GET['id']);
		$commande = A10A;
		 
		$query = mysql_query("SELECT users_comment, users_id FROM users_orders WHERE users_id='".$_GET['id']."'");
		$result = mysql_fetch_array($query);
		$users_comment = '<b>'.A10.' '.dateFr($hoy,$_SESSION['lang']).'</b>';
		$users_comment.= '<br><b>'.VOIR_TERUG.' n&deg; <a href="detail.php?id='.$_SESSION['nextId'].'">'.str_replace("||","",$_SESSION['nextFactNum']).'</a></b>';
		$users_comment.= ($_GET['comment']!=="")? '<br>'.addslashes($_GET['comment']) : '';
		$users_comment.= ($result['users_comment']=="")? '' : '<br>---<br>'.$result['users_comment'];
		mysql_query("UPDATE users_orders SET users_refund='yes', users_comment='".$users_comment."' WHERE users_id='".$_GET['id']."'");
		if(isset($_SESSION['nextFactNum'])) unset($_SESSION['nextFactNum']);
		if(isset($_SESSION['nextId'])) unset($_SESSION['nextId']);
	
		 
		$query = mysql_query("SELECT users_products, users_id FROM users_orders WHERE users_id='".$_GET['id']."'");
		$result = mysql_fetch_array($query);
		$split = explode(",",$result['users_products']);
	
 
		foreach ($split AS $item) {
			$check = explode("+",$item);
			$query = mysql_query("SELECT p.products_qt, p.products_id, s.specials_new_price, s.specials_last_day, products_sold
		                        FROM products as p
		                        LEFT JOIN specials as s
		                        ON (p.products_id = s.products_id)
		                        WHERE p.products_id = '".$check[0]."'");
			$row = mysql_fetch_array($query);
			if($check[1]!=="0") {
		    	$updateStockNow = $row['products_qt']+$check[1];
		    	$productsStockNow = $row['products_sold']-$check[1];
		    	mysql_query("UPDATE products 
		        			SET 
		                    products_qt = ".$updateStockNow.",
		                	products_sold = ".$productsStockNow."
		                	WHERE products_id = '".$row['products_id']."'");
		    	$stock = "ok";
			}
			
         	 
			if(isset($check[6]) AND !empty($check[6])) {
				$_opt = explode("|",$check[6]);
         		$lastArray = $_opt[count($_opt)-1];
         		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
         			$out = array_pop($_opt);
         			$addReq = implode(' | ', $_opt);
         		}
				if(isset($addReq) AND $addReq!=='') {
         			$queryOpt = mysql_query("SELECT products_options_stock_stock FROM products_options_stock 
					 							WHERE products_options_stock_prod_id = '".$check[0]."'
												AND products_options_stock_prod_name = '".$addReq."'");
         			if(mysql_num_rows($queryOpt)>0) {
						$queryOptResult = mysql_fetch_array($queryOpt);
         				$stockOptUpdate = $queryOptResult['products_options_stock_stock'] + $check[1];
						mysql_query("UPDATE products_options_stock
	                    				SET
										products_options_stock_stock = ".$stockOptUpdate."
										WHERE products_options_stock_prod_id = '".$check[0]."' 
										AND products_options_stock_prod_name = '".$addReq."'
										");
					}
				}
         	}
		}
	}
}

if(empty($_GET['id'])) {
   print "<html>";
      print "<head>";
      print "<title></title>";
      print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
      print "<link rel='stylesheet' href='style.css'>";
      print "</head>";
      print "<body leftmargin='0' topmargin='30' marginwidth='0' marginheight='0'>";
      print "<p align='center' class='largeBold'>".A2."</p>";
      print "<p align='center'>".A3."";
      print "<br><br>";
      print "<span style='color:red'>".A4."</span>";
      print "<br>";
      print "<p align='center'><a href='terugbetaling.php'><b>".A5."</b></a></p>";
      print "</p>";
      print "</body>";
   print "</html>";
   exit;
}
else {
      $query = mysql_query("SELECT * FROM users_orders WHERE users_id='".$_GET['id']."'");
      $hh = mysql_num_rows($query);
      if($hh == 0) header("Location:".$_SERVER['PHP_SELF']."");
      $row = mysql_fetch_array($query);
$pays = mysql_query("SELECT * FROM countries WHERE countries_name = '".$row['users_country']."'");
$p = mysql_fetch_array($pays);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"> <?php print A6;?> <?php print str_replace("||","",$row['users_nic']);?></p>

<?php
if(substr($row['users_nic'], 0, 5)!== "TERUG" AND $row['users_refund']=='no') {
?>
<table width="700" align="center" border="0" cellpadding="5" cellspacing="3" class="TABLE">
<tr>
<form action="<?php print $_SERVER['PHP_SELF'];?>" method="GET">
<td align="left">Actie</td>

<td align="left">
<select name='todo'>
<option value='rembourser'><?php print TERUGBETALING;?></option>
</select>
</td>
</tr><tr>

<td align="left" valign="top">Nota</td>

<td align="left">
   <textarea cols="60" rows="5" name="comment" class="text"></textarea>
</td>
</tr><tr>

<td align="center" colspan="2">
   <input type="hidden" name="id" value="<?php print $_GET['id'];?>">
   <input type="hidden" name="nic" value="<?php print $row['users_nic'];?>">
   <input type="submit" class="knop" name="action" value="<?php print A1;?>">
   
<?php if(isset($commande) AND $commande!=="") { print "<p align='center' class='fontrouge'>".$commande."<br>".A520."</p>";} ?>
</td>
</form>
</tr>
</table>
<br>
<br>
<table align="center" width="700" border="0" cellpadding="1" cellspacing="0" class="TABLE">
<tr>
        <td width="250"><?php print A12;?></td>
        <td><?php print dateFr($row['users_date_added'],$_SESSION['lang']); ?></td>
</tr>
<tr>
        <td width="250">Id</td>
        <td><?php print "<span style='color:#CC0000'>".$row['users_id']."</span>";?></td>
</tr>
<tr>
        <td width="250"><?php print A13;?></td>
        <td><?php print $row['users_gender'];?></td>
</tr>
<tr>
        <td width="250"><?php print A14;?></td>
        <td><?php print $row['users_lastname'];?></td>
</tr>
<tr>
        <td width="250"><?php print A15;?></td>
        <td><?php print $row['users_firstname'];?></td>
</tr>
<tr>
        <td width="250"><?php print A16;?></td>
        <td><?php print $row['users_address'];?></td>
</tr>
<tr>
        <td width="250">&nbsp;</td>
        <td><?php print $row['users_surburb'];?></td>
</tr>
<tr>
        <td width="250"><?php print A17;?></td>
        <td><?php print $row['users_zip'];?></td>
</tr>
<tr>
        <td width="250"><?php print A18;?>:</td>
        <td><?php print $row['users_city'];?></td>
</tr>
<tr>
        <td  width="250" valign="top"><?php print A19;?></td>
        <td><?php print strtoupper($row['users_country']);?><br>
        <?php
        if($p['countries_shipping_tax_active'] == 'yes') {
          print A20." | ".$p['countries_shipping_tax']."%";
          print "<br>";
          print A22;
        }
        else {
          print A21;
          print "<br>";
          print A23;
        }
        ?>
        </td>
</tr>
<tr>
        <td width="250">E-mail</td>
        <td><?php print "<a href='mailto:".$row['users_email']."'>".$row['users_email']."</a>";?></td>
</tr>
<tr>
        <td width="250"><?php print A24;?></td>
        <td><?php print $row['users_telephone'];?></td>
</tr>
<tr>
        <td width="250"><?php print FAX;?></td>
        <td><?php print $row['users_fax'];?></td>
</tr>
<tr>
        <td width="250"><?php print A25;?></td>
        <td><?php print $row['users_lang'];?></td>
</tr>
<tr>
        <td width="250"><?php print NUM_FACT;?></td>
        <td><?php print "<span style='color:red'>".str_replace("||","",$row['users_fact_num'])."</span>";?></td>
</tr>
<tr>
         <td width="250"><?php print A26;?></td>
         <td><?php print "<span style='color:red'>".$row['users_password']."</span>";?></td>
</tr>
<tr>
         <td width="250">NIC</td>
         <td><?php print "<span style='color:red'>".$row['users_nic']."</span>";?></td>
</tr>
<tr>
         <td width="250"><?php print A27;?></td>
         <td>
         <?php
        if($row['users_payment'] == "pp") $payMode = "PAYPAL";
        if($row['users_payment'] == "mb") $payMode = "MONEYBOOKERS";
        if($row['users_payment'] == "cc") $payMode = A28;
        if($row['users_payment'] == "BO") $payMode = A29;
        if($row['users_payment'] == "ch") $payMode = A30;
        if($row['users_payment'] == "ma") $payMode = A31;
        if($row['users_payment'] == "tb") $payMode = TRAITE_BANCAIRE;
        if($row['users_payment'] == "wu") $payMode = "Western Union";
        if($row['users_payment'] == "ss") $payMode = "Liaison-SSL";
        if($row['users_payment'] == "eu") $payMode = "1euro.com";
		        
        print $payMode;
        if(!empty($row['pp_statut'])) print "<br>".A32.": ".$row['pp_statut'];
        if(!empty($row['trans_id'])) print "<br>Transaction Id: ".$row['trans_id'];
         ?>
         </td>
</tr>
<tr>
         <td width="250"><?php print A33;?></td>
         <td><?php print ($row['users_confirm'] == "yes")? A34 : A35;?></td>
</tr>
<tr>
         <td width="250"><?php print A5201;?></td>
         <td><?php print ($row['users_payed'] == "yes")? A34 : A35;?></td>
</tr>
<tr>
         <td width="250"><?php print READY;?></td>
         <td><?php print ($row['users_ready'] == "yes")? A34 : A35;?></td>
</tr>
<tr>
         <td width="250"><?php print A5200;?></td>
         <td><?php print ($row['users_statut'] == "yes")? A34 : A35;?></td>
</tr>
<tr>
         <td width="250"><?php print A36;?></td>
         <?php
        if($row['users_facture'] > 0) {
            $factured = A34." (".$row['users_facture']." ".A37.")";
        } else {
            $factured = A35;
        }
?>
         <td><?php print $factured;?></td>
</tr>
<tr>
        <td width="250"><?php print A38;?></td>
        <td><?php print $row['users_comment'];?></td>
</tr>
</table>

<br>

<?php
print "<table width='700' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE'><tr><td>";

                print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='2'>";
                print "<tr>";
                print "<td><b><u>Ref/".A42."</u></b><br><img src='im/zzz.gif' width='1' height='5'><br></td>";
                print "<td width='50' align='center'><b><u>Aantal</u></b></td>";
                print "<td width='50' align='center'><b><u>".A43."</u></b></td>";
                print "<td width='80' align='right'><b><u>".A44." ".$row['users_products_tax_statut']."</u></b></td>";

                $split = explode(",",$row['users_products']);
                foreach ($split as $item) {
                        $check = explode("+",$item);

                        if($check[1]!=="0") {
                          print "</tr><tr>";
                           
                          if($check[3]=="GC100") {
                                 $hidz = mysql_query("SELECT gc_number FROM gc WHERE gc_nic='".$row['users_nic']."'");
                                 $myhidz = mysql_fetch_array($hidz);
                                 $qqq = "<br><a href='kadeaubon.php'>".$row['users_gc']."</a>";
                          }
                          else {
                                 $qqq="";
                          }
                          
							 
	                        if(!empty($check[6])) {
	                        	$_optZ = explode("|",$check[6]);
								## session update option price
								$lastArray = $_optZ[count($_optZ)-1];
								if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
								$_optZ = implode("|",$_optZ);
								$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
							}
							else {
								$q="";
							}
                           
                          print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4]." ".$qqq." ".$q;
                           
                          if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
                           
                          print "<td width='50' align='center'>".$check[1];
                           
                          print "<td width='50' align='center'>".$check[5]."%";
                           
                          $priceTTC = ($check[2] * $check[1]);
                          print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC);
                        }
                        print "</td>";
                }
                print "</tr></table><br>";

                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td>";
                print A45.": <b>".$row['users_products_weight']."</b> gr <br>".$row['users_products_weight_price']." ".$row['users_symbol_devise']."/gr";
                $ecoTaxFactFinal = sprintf("%0.2f",array_sum($ecoTaxFact));
                if($ecoTaxFactFinal>0) {
                    print "<br><i><span style='color:#00CC00'>Eco part : ".$ecoTaxFactFinal." ".$symbolDevise."</span></i>";
                }
                print "</td>";
                print "</tr></table>";

                print "<table border='0' align='right' cellspacing='0' cellpadding='0'>";
                print "<td align='right'>".A46."</td>";
                print "<td width='120' align='right'><b>".$row['users_products_ht']."</b></td>";
                print "</tr><tr>";
                print "<td align='right'>".A47."</td>";
                 
                print "<td align='right'>";
                $explodMultiple = explode("|",$row['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                            $itemDisplay = "";
                        }
                        else {
                            if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                            $itemDisplay = str_replace(">", "%: ", $item).$br;
                        }
                        print $itemDisplay;
                    }
                print "</td>";
                  
                 
                print "</tr><tr>";
                print "<td align='right'>".A48."</td>";
                print "<td align='right'>".$row['users_ship_ht']."</td>";
                print "</tr><tr>";
                print "<td align='right'>".$taxeName."</td>";
                print "<td align='right'>".$row['users_ship_tax']."</td>";
                
                 
                if($row['users_sup_ttc'] > 0) {
                print "</tr><tr>";
                print "<td align='right'>".EMBALLAGE."</td>";
                print "<td align='right'>".$row['users_sup_ht']."</td>";
                print "</tr><tr>";
                print "<td align='right'>".$taxeName."</td>";
                print "<td align='right'>".$row['users_sup_tax']."</td>";
                }
                
                if($row['users_remise'] > 0) {
                print "</tr><tr>";
                print "<td align='right'>".A50."</td>";
                print "<td align='right'><span style='color:red'><b>".$row['users_remise']."</b></span></td>";
                }
                if($row['users_account_remise_app'] > 0) {
				print "</tr><tr>";
                print "<td align='right'>".POINT_DE_FIDELITE."</td>";
                print "<td align='right'><span style='color:red'><b>".$row['users_account_remise_app']."</b></span></td>";
                }
                if($row['users_remise_gc'] > 0) {
				print "</tr><tr>";
                print "<td align='right'>".CHEQUE_CADEAU_MIN."</td>";
                print "<td align='right'><span style='color:red'><b>-".$row['users_remise_gc']."</b></span></td>";
                }
                if($row['users_remise_coupon'] > 0) {
                print "</tr><tr>";
                print "<td align='right'>".A51." <i><b>".$row['users_remise_coupon_name']."</b></i></td>";
                print "<td align='right'><span style='color:red'><b>".$row['users_remise_coupon']."</b></span></td>";
                }
                if($row['users_contre_remboursement'] > 0) {
				print "</tr><tr>";
                print "<td align='right'>".A28b."</td>";
                print "<td align='right'>".$row['users_contre_remboursement']."</td>";
                }
                
                print "</tr><tr>";
                print "<td align='right'>".A52."</td>"    ;
                print "<td align='right'><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
                print "</tr>";
                print "</table>";

print "</td></tr></table>";

}
else {
   if(isset($_GET['todo']) AND $_GET['todo']="rembourser") {
      print "<table align='center' border='0' cellpadding='5' cellspacing='3' class='TABLE' width='700'>";
      print "<tr><td align='center'>";
      print "<span style='color:#CC0000'><b>".A10A."</b></span>";
      print "<br><br>";
      print "<form method='GET' action='klanten.php'><input type='submit' class='knop' value='".A5."'></form>";
      print "</td></tr></table>";
   }
   else {
      print "<table align='center' border='0' cellpadding='5' cellspacing='3' class='TABLE' width='700'>";
      print "<tr><td align='center'>";
      print "<span style='color:#CC0000'><b>".COMMANDE_ANN_REM."</b></span>";
      print "</td></tr></table>";
   }
}
?>
<br><br><br>
</body>
</html>

<?php
}
?>
