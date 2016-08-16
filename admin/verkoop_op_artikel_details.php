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
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
if(isset($_GET['id']) AND $_GET['id']=="TOUS") {
   $titrel = RESUME_TOUT_LES_ARTICLES;
}
else {
   $titrel = A1;
}
print '<p align="center" class="largeBold">'.$titrel.'</p>';
print "<p align='center'>".SELECT_DATES."</b></p>";

$nowDay = date("d");
$nowMonth = date("m");
$nowYear = date("Y");

if($nowMonth=="1") $nowMonth = B2;
if($nowMonth=="2") $nowMonth = B3;
if($nowMonth=="3") $nowMonth = B4;
if($nowMonth=="4") $nowMonth = B5;
if($nowMonth=="5") $nowMonth = B6;
if($nowMonth=="6") $nowMonth = B7;
if($nowMonth=="7") $nowMonth = B8;
if($nowMonth=="8") $nowMonth = B9;
if($nowMonth=="9") $nowMonth = B10;
if($nowMonth=="10") $nowMonth= B11;
if($nowMonth=="11") $nowMonth= B12;
if($nowMonth=="12") $nowMonth= B13;

if(isset($_GET['mois1']) and $_GET['mois1'] ==B2) $_GET['mois1']=1;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B3) $_GET['mois1']=2;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B4) $_GET['mois1']=3;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B5) $_GET['mois1']=4;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B6) $_GET['mois1']=5;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B7) $_GET['mois1']=6;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B8) $_GET['mois1']=7;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B9) $_GET['mois1']=8;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B10) $_GET['mois1']=9;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B11) $_GET['mois1']=10;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B12) $_GET['mois1']=11;
if(isset($_GET['mois1']) and $_GET['mois1'] ==B13) $_GET['mois1']=12;

if(isset($_GET['mois2']) and $_GET['mois2'] ==B2) $_GET['mois2']=1;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B3) $_GET['mois2']=2;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B4) $_GET['mois2']=3;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B5) $_GET['mois2']=4;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B6) $_GET['mois2']=5;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B7) $_GET['mois2']=6;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B8) $_GET['mois2']=7;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B9) $_GET['mois2']=8;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B10) $_GET['mois2']=9;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B11) $_GET['mois2']=10;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B12) $_GET['mois2']=11;
if(isset($_GET['mois2']) and $_GET['mois2'] ==B13) $_GET['mois2']=12;


$days1 = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
$months1 = array("1"=>B2,"2"=>B3,"3"=>B4,"4"=>B5,"5"=>B6,"6"=>B7,"7"=>B8,"8"=>B9,"9"=>B10,"10"=>B11,"11"=>B12,"12"=>B13);
$years1 = array("2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015");

		print "<table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'>";
		print "<form method='GET' action='".$_SERVER['PHP_SELF']."'>";
		print "<tr>";
		print "<td align='center'>";
		print "<b>".B15."</b> ";

			print "<select name='jour1' class='site'>";
			$days1Nb = count($days1)-1;
			for($c=0; $c<= $days1Nb; $c++) {
				$a=$c+1;
				if(isset($_GET['jour1'])) {
					if($days1[$c]== $_GET['jour1']) $sel1 = "selected"; else $sel1="";
				}
				else {
					if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
				}
				print "<option value=".$a." ".$sel1.">".$days1[$c]."</option>";
			}
			print "</select>&nbsp;&nbsp;";
 
			print "<select name='mois1' class='site'>";
			$keys = array_keys($months1);
			$months1Nb = count($months1);
			for($x1=1; $x1 <= $months1Nb; $x1++) {
				$p=$x1-1;
				if(isset($_GET['mois1'])) {
					if($keys[$p]== $_GET['mois1']) $sel2 = "selected"; else $sel2="";
				}
				else {
					if($months1[$x1]==$nowMonth) $sel2 = "selected"; else $sel2="";
				}
				print "<option value=".$keys[$p]." ".$sel2.">".$months1[$x1]."</option>";
			}
			print "</select>&nbsp;&nbsp;";
 
			print "<select name='an1' class='site'>";
			$years1Nb = count($years1)-1;
			for($x3=0; $x3 <= $years1Nb; $x3++) {
				if(isset($_GET['an1'])) {
					if($years1[$x3]== $_GET['an1']) $sel3 = "selected"; else $sel3="";
				}
				else {
					if($years1[$x3]==$nowYear) $sel3 = "selected"; else $sel3="";
				}
				print "<option value=".$years1[$x3]." ".$sel3.">".$years1[$x3]."</option>";
			}
			print "</select>";
		print "</td></tr><tr>";
		print "<td align='center'>";
		
		print "<b>".B16."</b> ";
 
			print "<select name='jour2' class='site'>";
			$days1Nb = count($days1)-1;
			for($c=0; $c<= $days1Nb; $c++) {
				$a=$c+1;
				if(isset($_GET['jour2'])) {
					if($days1[$c]== $_GET['jour2']) $sel1 = "selected"; else $sel1="";
				}
				else {
					if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
				}
				print "<option value=".$a." ".$sel1.">".$days1[$c]."</option>";
			}
			print "</select>&nbsp;&nbsp;";
 
			print "<select name='mois2' class='site'>";
			$keys = array_keys($months1);
			$months1Nb = count($months1);
			for($x=1; $x <= $months1Nb; $x++) {
				$p=$x-1;
				if(isset($_GET['mois2'])) {
					if($keys[$p]== $_GET['mois2']) $sel2 = "selected"; else $sel2="";
				}
				else {
					if($months1[$x]==$nowMonth) $sel2 = "selected"; else $sel2="";
				}
				print "<option value=".$keys[$p]." ".$sel2.">".$months1[$x]."</option>";
			}
			print "</select>&nbsp;&nbsp;";
 
			print "<select name='an2' class='site'>";
			$years1Nb = count($years1)-1;
			for($x=0; $x <= $years1Nb; $x++) {
				if(isset($_GET['an2'])) {
					if($years1[$x]== $_GET['an2']) $sel3 = "selected"; else $sel3="";
				}
				else {
					$n=$x3+1;
					if($years1[$x]==$nowYear) $sel3 = "selected"; else $sel3="";
				}
				print "<option ".$sel3.">".$years1[$x]."</option>";
			}
			print "</select>";

		print "</td>";
		print "</tr>";
		print "<tr>";
		print "<td colspan='2' align='center'>";
		print "<input type='submit' name='Submit' value='".B17."' class='knop'>";
		print "<input type='hidden' name='id' value='".$_GET['id']."'>";
		
		
		print "</td>";
		print "</tr>";
		print "</form>";
		print "</table>";

 

if(isset($_GET['jour1']) or isset($dateAdded1)) {
	$control1 = checkdate($_GET['mois1'],$_GET['jour1'],$_GET['an1']);
	$control2 = checkdate($_GET['mois2'],$_GET['jour2'],$_GET['an2']);
		
	if($control1 == true and $control2 == true) {
		$control11 = mktime(0,0,0,$_GET['mois1'],$_GET['jour1'],$_GET['an1']);
		$control21 = mktime(0,0,0,$_GET['mois2'],$_GET['jour2'],$_GET['an2']);
		
		if($control21 >= $control11) {
			$dateAdded1 = "".$_GET['an1']."-".$_GET['mois1']."-".$_GET['jour1']." 00:00:00";
			$dateAdded2 = "".$_GET['an2']."-".$_GET['mois2']."-".$_GET['jour2']." 00:00:00";
			$queryP = mysql_query("SELECT *
		                          FROM users_orders
		                          WHERE TO_DAYS(users_date_payed) >= TO_DAYS('".$dateAdded1."')
		                          AND TO_DAYS(users_date_payed) <= TO_DAYS('".$dateAdded2."')
		                          AND users_payed = 'yes'
		                          ORDER BY users_date_payed
		                          DESC");
			$queryPNum = mysql_num_rows($queryP);
		   
 
		if(isset($_GET['id']) AND $_GET['id']!=="TOUS") {
			$addQueryAll = "WHERE products_id = '".$_GET['id']."'";
			$gotoAll = "<a href='artikel_wijzigen_details.php?id=".$_GET['id']."&from=items&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>";
		}
		else {
			$addQueryAll = "";
			$gotoAll = "<a href='#'>";
		}
		
		 
		$queryP100 = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products ".$addQueryAll."");
		$queryP100Num = mysql_num_rows($queryP100);
		if($queryP100Num>0) {
			while($rowP100 = mysql_fetch_array($queryP100)) {
		    	$productName = $rowP100['products_name_'.$_SESSION['lang']];
		    }
		    if(isset($_GET['id']) AND $_GET['id']=="TOUS") $productName = TOUS_LES_ARTICLES;
		}
		
	 
		$queryP1 = mysql_query("SELECT products_id FROM products ".$addQueryAll."");
		$queryP1Num = mysql_num_rows($queryP1);
		if($queryP1Num>0) {
			while($rowP1 = mysql_fetch_array($queryP1)) {
		    	$idProducts[] = $rowP1['products_id'];
			}
		 
		
		

		    $colspan = 12;
		    $queryPNum = mysql_num_rows($queryP);
		    if($queryPNum>0) {
		
		    print "<br><table border='0' cellpadding='4' cellspacing='2' align='center' class='TABLE'>";
		    print "<tr height='30'>";
		    print "<td align='center' valign='middle' colspan='".$colspan."'><b>".$gotoAll."".strtoupper($productName)."</a></b></td></tr><tr>";
		    print "<td align='left' class='TD'><b>".FACT."</b></td>";
		    print "<td align='left' class='TD'><b>NIC</b></td>";
		            print "<td align='left' class='TD'><b>".CLIENT."</b></td>";
		            print "<td align='left' class='TD'><b>Datum</b></td>";
		            print "<td align='left' class='TD'><b>Aantal</b></td>";
		            print "<td align='left' class='TD'><b>Voorraad</b></td>";
		            print "<td align='left' class='TD'><b>REF</b></td>";
		            print "<td align='left' class='TD'><b>E.P</b></td>";
		            print "<td align='left' class='TD'><b>Totaal</b></td>";
		            print "<td align='left' class='TD'><b>Opties</b></td>";
		            if(isset($_GET['id']) AND $_GET['id']=="TOUS") {
		            	print "<td align='center' class='TD'><b>".ARTICLE."</b></td>";
		            	print "<td align='center' class='TD'><b>".FOURNISSEUR."</b></td>";
		            }
		            print "</tr><tr valign='top'>";
		        while($rowUp = mysql_fetch_array($queryP)) {
		        	$splitUp = explode(",",$rowUp['users_products']);
		        	foreach ($splitUp as $item) {
							$check = explode("+",$item);
							$queryP12 = mysql_query("SELECT products_name_".$_SESSION['lang'].", fournisseurs_id, products_qt FROM products WHERE products_ref = '".$check[3]."'");
							$rowP12 = mysql_fetch_array($queryP12);
							$productName2 = $rowP12['products_name_'.$_SESSION['lang']];
							$fournisseurId = $rowP12['fournisseurs_id'];
							$stockRestant = $rowP12['products_qt'];
		                       
							
							if(isset($_GET['id']) AND $_GET['id']=="TOUS") {
								$queryP12X = mysql_query("SELECT * FROM fournisseurs WHERE fournisseurs_id = '".$fournisseurId."'");
								$rowP12X = mysql_fetch_array($queryP12X);
								$forunisseurName = $rowP12X['fournisseurs_company'];
								$forunisseurId = $rowP12X['fournisseurs_id'];
								
								
								if($check[3]!=="GC100") {
									if($rowP12X['fournisseurs_firstname']!=="") $forunisseurCoord1 = $rowP12X['fournisseurs_firstname']; else $forunisseurCoord1="";
									if($rowP12X['fournisseurs_name']!=="") $forunisseurCoord2 = $rowP12X['fournisseurs_name']; else $forunisseurCoord2="";
									if($rowP12X['fournisseurs_address']!=="") $forunisseurCoordA= $rowP12X['fournisseurs_address']; else $forunisseurCoordA="";
									if($rowP12X['fournisseurs_zip']!=="") $forunisseurCoord3= $rowP12X['fournisseurs_zip']; else $forunisseurCoord3="";
									if($rowP12X['fournisseurs_city']!=="") $forunisseurCoord4= $rowP12X['fournisseurs_city']; else $forunisseurCoord4="";
									if($rowP12X['fournisseurs_pays']!=="") $forunisseurCoord5= $rowP12X['fournisseurs_pays']; else $forunisseurCoord5="";
									if($rowP12X['fournisseurs_tel1']!=="") $forunisseurCoord6= $rowP12X['fournisseurs_tel1']; else $forunisseurCoord6="";
									if($rowP12X['fournisseurs_tel2']!=="") $forunisseurCoord7= $rowP12X['fournisseurs_tel2']; else $forunisseurCoord7="";
									if($rowP12X['fournisseurs_cel1']!=="") $forunisseurCoord8= $rowP12X['fournisseurs_cel1']; else $forunisseurCoord8="";
									if($rowP12X['fournisseurs_cel2']!=="") $forunisseurCoord9= $rowP12X['fournisseurs_cel2']; else $forunisseurCoord9="";
									if($rowP12X['fournisseurs_fax']!=="") $forunisseurCoord10= $rowP12X['fournisseurs_fax']; else $forunisseurCoord10="";
									if($rowP12X['fournisseurs_email']!=="") $forunisseurCoord11= $rowP12X['fournisseurs_email']; else $forunisseurCoord11="";
								}
								else {
									$forunisseurCoord1="";
									$forunisseurCoord2="";
									$forunisseurCoord3="";
									$forunisseurCoord4="";
									$forunisseurCoord5="";
									$forunisseurCoord6="";
									$forunisseurCoord7="";
									$forunisseurCoord8="";
									$forunisseurCoord9="";
									$forunisseurCoord10="";
									$forunisseurCoord11="";
									$forunisseurCoordA="";
								}
							}
		                    if(in_array($check[0], $idProducts)) {
		                                  $products[]=$item;
		                                  
		                                  $factRef = ($rowUp['users_refund']=="yes" OR preg_match("/\bTERUG\b/i", $rowUp['users_nic']))? "<span style='background:#000000; color:#FFFFFF'>".str_replace("||","",$rowUp['users_fact_num'])."</span>" : str_replace("||","",$rowUp['users_fact_num']);
		                                  print "<td><a href='factuur_scherm.php?id=".$rowUp['users_id']."&target=impression' target='_blank'>".$factRef."</a></td>";
		                                  
		                                  print "<td><a href='detail.php?id=".$rowUp['users_id']."&from=items&id2=".$_GET['id']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>".str_replace("||","",$rowUp['users_nic'])."</a></td>";
		                                  
		                                  print "<td>".$rowUp['users_password']."</td>";
		                                  
		                                  print "<td>".dateFr($rowUp['users_date_added'],$_SESSION['lang'])."</td>";
		                                  
		                                  print "<td>".$check[1]."</td>";
		                                  
		                                  print "<td>".$stockRestant."</td>";
		                                  
		                                  print "<td align='center'><a href='artikel_wijzigen_details.php?id=".$check[0]."'>".$check[3]."</a></td>";
		                                  
		                                  print "<td align='center'>".$check[2]."</td>";
		                                  
		                                  $vendu = sprintf("%0.2f",$check[2]*$check[1]);
		                                  if(substr($rowUp['users_nic'], 0, 5) !== "TERUG") {
		                                     $totalPriceSales[] = $vendu;
		                                     $vendu2 = $vendu;
		                                  }
		                                  else {
		                                     $totalPriceSales[] = 0;
		                                     $vendu2 = 0;
		                                  }
		                                  if(substr($rowUp['users_nic'], 0, 5) == "TERUG") {$venduDisplay = "<s>".$vendu."</s>";} else {$venduDisplay = $vendu;}
		                                  print "<td align='center'>".$venduDisplay."</td>";
		                                  
		                                  if(empty($check[6])) {
		                                		print "<td align='center'>X</td>";
		                                      	$optDisplay2 = "--";
		                                  }
		                                  else {
										   		$_opt = explode("|",$check[6]);
												## session update option price
												$lastArray = $_opt[count($_opt)-1];
												if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
												$ww = implode("|",$_opt);
												$optDisplay = str_replace("|","<br>",$ww);
		                                      	$optDisplay2 = $check[6];
		                                     	print "<td align='center'>".$optDisplay."</td>";
		                                  }
		                                  
		                                  if(isset($_GET['id']) AND $_GET['id']=="TOUS") {
		                                     print "<td align='center'><a href='artikel_wijzigen_details.php?id=".$check[0]."'>".$productName2."</a></td>";
		                                     print "<td align='center'><a href='fab_lev_wijzigen2.php?id1=".$forunisseurId."&id2=none'>".$forunisseurName."</a></td>";
		                                  }
		                                  else {
		                                     $productName2="";
		                                     $forunisseurName="";
		                                  }
		                                  print "</tr>";
		                                  
		                                  if(isset($_GET['id']) AND $_GET['id']=="TOUS") {
		                                  $val[] = $check[3]
		                                  ."|||".$productName2
		                                  ."|||".$forunisseurName
		                                  ."|||".$rowUp['users_fact_num']
		                                  ."|||".$rowUp['users_nic']
		                                  ."|||".$rowUp['users_password']
		                                  ."|||".dateFr($rowUp['users_date_added'],$_SESSION['lang'])
		                                  ."|||".$check[1]
		                                  ."|||".$stockRestant
		                                  ."|||".$check[2]
		                                  ."|||".$vendu2
		                                  ."|||".$optDisplay2
		                                  ."|||".$forunisseurCoord1
		                                  ."|||".$forunisseurCoord2
		                                  ."|||".$forunisseurCoordA
		                                  ."|||".$forunisseurCoord3
		                                  ."|||".$forunisseurCoord4
		                                  ."|||".$forunisseurCoord5
		                                  ."|||".$forunisseurCoord6
		                                  ."|||".$forunisseurCoord7
		                                  ."|||".$forunisseurCoord8
		                                  ."|||".$forunisseurCoord9
		                                  ."|||".$forunisseurCoord10
		                                  ."|||".$forunisseurCoord11;
		                                  }
		                                  
		                }
		            }
		        }
		        print "</tr>";
		        print "</table>";
		
		
		        if(!isset($products)) {
		            print "<p align='center'>";
		            print B15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
		            print "&nbsp;";
		            print B16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
		            print "<p align='center' class='fontrouge'><b>".B35."</b></p>";
		            print "</p>";
		        }
		        
		        if(isset($_GET['id']) AND $_GET['id']=="TOUS" AND isset($val) AND count($val)>0) {
		                print '<br><table align="center" border="0" cellpadding="2" cellspacing="0" c/lass="TABLE">';
		                print '<tr>';
		                print "<form method='POST' action='export_items.php'>";
		                print '<td align="center" bgcolor="#FFFFCC">';
		                foreach($val AS $it) {
		                	print "<input type='hidden' name='val[]' value='".$it."'>";
		                }
		                print "<input type='submit' class='knop' value='".EXPORT." (CSV)'>";
		                print '</td>';
		                print "</form>";
		                print '</tr>';
		                print '</table>';
		        }
		    }
		    else {
		            print "<p align='center'>";
		            print B15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
		            print "&nbsp;";
		            print B16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
		            print "<p align='center' class='fontrouge'><b>".B35."</b></p>";
		            print "</p>";
		    }
		}
		else {
		    print "<p align='center' class='fontrouge'><b>".B350." '".$productName."'.</b></p>";
		}
		}
		else {
		      print "<p align='center'>";
		      print B15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
		      print "&nbsp;";
		      print B16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
		      print "</p>";
		      print "<div align='center' class='fontrouge'><b>".B36."</b></div>";
		}
		}
		else {
		      print "<p align='center'>";
		      print B15.": ".$_GET['jour1']."-".$_GET['mois1']."-".$_GET['an1']."";
		      print "&nbsp;";
		      print B16.": ".$_GET['jour2']."-".$_GET['mois2']."-".$_GET['an2']."";
		      print "</p>";
		      print "<div align='center' class='fontrouge'><b>".B37."</b></div>";
		}
}
        
    if(isset($products)) {
        if($taxePosition == "Tax included") $priceStat = "TTC";
        if($taxePosition == "Plus tax") $priceStat = "HT";
        if($taxePosition == "No tax") $priceStat = "";
        $t = sprintf("%0.2f",array_sum($totalPriceSales));
        print "<p align='center'>Total ".$productName." ".$priceStat." : <b>".$t." ".$symbolDevise."</b></p>";
    }

    print "<form action='verkoop_op_artikel.php'><p align='center'><input type='submit' class='knop' value='".BACK."'></p></form>";
?>

  </body>
  </html>
