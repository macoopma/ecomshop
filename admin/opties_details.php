<?php
session_start();


if(!isset($_SESSION['login'])) header("Location:index.php");
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
include('../configuratie/configuratie.php');
$message = "";
$c = "";
if(isset($_GET['id'])) $_POST['id'] = $_GET['id'];


 
if(isset($_POST['action']) and $_POST['action'] == A700) {
    $goto = "opties_maken_details.php?optionId=".$_POST['optionId']."&productId=".$_POST['id']."&do=update";
    header("Location: $goto");
}


if(isset($_GET['action']) AND $_GET['action'] == "effacer") {
$queryText = mysql_query("SELECT products_option
                                 FROM products_id_to_products_options_id
                                 WHERE products_id = '".$_GET['productId']."'
                                 AND products_options_id = '".$_GET['optionId']."'
                                 ") or die (mysql_error());
$optionNameee = mysql_fetch_array($queryText);
    $exp = explode(",",$optionNameee['products_option']);
    unset($exp[$_GET['num']]);
    $rec = implode(",",$exp);
	mysql_query("UPDATE products_id_to_products_options_id
                SET
                products_option = '".$rec."'
                WHERE products_id = '".$_GET['productId']."'
                AND products_options_id = '".$_GET['optionId']."'
                ") or die (mysql_error());
}

 
if(isset($_POST['action']) AND $_POST['action'] == A3) {
	$q = mysql_query("SELECT products_option FROM products_id_to_products_options_id WHERE products_options_id = '".$_POST['optionId']."' AND products_id = '".$_POST['id']."'");
	if(mysql_num_rows($q)>0) {
		$qResult = mysql_fetch_array($q);
		$explodeqResult = explode(",",$qResult['products_option']);
	
		foreach($explodeqResult AS $items) {
			$explodeItems = explode('::', $items);
			$valueZ[] = $explodeItems[0];
		}
		foreach($valueZ as $key => $value) {
			if($value == "") unset($valueZ[$key]);
		}
 
		foreach($valueZ AS $item) {
			mysql_query("DELETE FROM products_options_stock
	                		WHERE products_options_stock_prod_name = '".$item."'
	                		AND products_options_stock_prod_id = '".$_POST['id']."'") or die (mysql_error());
		}
	}
 
    mysql_query("DELETE FROM products_id_to_products_options_id
                	WHERE products_options_id = '".$_POST['optionId']."'
                	AND products_id = '".$_POST['id']."'") or die (mysql_error());
 
	$message.= "<font color=red>".A1." <b>".$_POST['optionName']."</b> ".A2."</font><br>";
}


if(isset($_POST['action']) AND $_POST['action'] == A4 AND isset($_POST['optionName']) AND !empty($_POST['optionName'])) {
    $_POST['optionName'] = str_replace("+","",$_POST['optionName']);
    $_POST['optionName'] = str_replace("'","&#146;",$_POST['optionName']);
    $_POST['option'] = str_replace(",",".",$_POST['option']);
    $_POST['option'] = str_replace("/","-",$_POST['option']);
    $_POST['option'] = str_replace("®","",$_POST['option']);
    $_POST['option'] = str_replace("\"","",$_POST['option']);
    $_POST['option'] = str_replace("\'\'","",$_POST['option']);
    $_POST['option'] = str_replace("'","&#146;",$_POST['option']);
    
	$_option = array_chunk($_POST['option'], 4);
	
	foreach($_option AS $items) {
		for($i=0; $i<=count($items)-1; $i++) {
			if(isset($items[3]) AND $items[3]!=="") $items[3] = str_replace("-","=",$items[3]);
	    }
	    $_option2[] = $items;
    }
	if(isset($_option2)) $_option = $_option2;

    foreach($_option AS $items) {
		$options55[] = implode("::",$items);
	}
	$options56 = implode(",",$options55);
	$options = $options56.",";

 
	mysql_query("UPDATE products_options
                SET
                products_options_name = '".$_POST['optionName']."'
                WHERE products_options_id = '".$_POST['optionId']."'");

   $noPass = array("\'","'");
   $note1 = str_replace($noPass,"&#8217;",$_POST['note_option_1']);
   $note2 = str_replace($noPass,"&#8217;",$_POST['note_option_2']);
   $note3 = str_replace($noPass,"&#8217;",$_POST['note_option_3']);

   mysql_query("UPDATE products_id_to_products_options_id
                SET
                products_option = '".$options."',
                note_option_1 = '".$note1."',
                note_option_2 = '".$note2."',
                note_option_3 = '".$note3."'
                WHERE products_options_id = '".$_POST['optionId']."'
                AND products_id = '".$_POST['id']."'");
   $message .= "<span class='large' style='color:red'>".A1." <b>".$_POST['optionName']."</b> ".A5."</span><br>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript"><!--
function check_add() {

  rejet = false;
  rejet1 = false;
  falsechar="";
  var non = "+";
  var non1 = "'";
  var error = 0;
  var error_message = "";
  
  for(i=0 ; i <= form1.optionName.value.length ; i++) {
      if((form1.optionName.value.charAt(i)==non)) {rejet=true; falsechar= non;}
  }
  if(rejet==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  for(i=0 ; i <= form1.optionName.value.length ; i++) {
      if((form1.optionName.value.charAt(i)==non1)) {rejet1=true; falsechar= non1;}
  }
  if(rejet1==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>

<p align="center" class="largeBold"><?php print A6;?></p>

<?php
 
$ProductNameQuery = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", products_qt, products_ref, products_download FROM products WHERE products_id = '".$_POST['id']."'");
$productName = mysql_fetch_array($ProductNameQuery);

 
$optionNameQuery = mysql_query("SELECT a.products_options_id, a.products_option, z.products_options_name, a.note_option_1, a.note_option_2, a.note_option_3
                                 FROM products_id_to_products_options_id as a
                                 LEFT JOIN products_options as z
                                 ON(a.products_options_id = z.products_options_id)
                                 WHERE products_id = '".$_POST['id']."'
                                 ORDER BY z.products_options_name");
                                 
                                 $num = mysql_num_rows($optionNameQuery);
                                 $s = ($num>1)? "s" : "";
                                 $message .= A8." <b>".$num."</b> opties";
                                 

print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td><center><a href='artikel_wijzigen_details.php?id=".$productName['products_id']."'><b>".strtoupper($productName['products_name_'.$_SESSION['lang']])."</b></a>&nbsp;&nbsp;&nbsp;";
print "Referentie: ".$productName['products_ref']." - ID# ".$productName['products_id']."&nbsp;";
print "&nbsp;<img src='im/zzz.gif' width='1' height='3'>";
print "<div align='center'>".$message."</div></center></tr></td></table><br>";


if($num>0) {
	print"<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td><center>";
	print "<a href='opties_wijzigen.php?list=1'>".CHANGER_ARTICLE."</a>";
	print " | ";
 	print "<a href='opties_maken.php?id=".$productName['products_id']."'>".AJOUTER_OPTIONS."</a>";
	print " | ";
 	print "<a href='opties_dupliceren.php?id=".$_POST['id']."'>".A111."</a>";
 	print "</tr></td></table>";



 
	$optQuery = mysql_query("SELECT * FROM products_options_stock WHERE products_options_stock_prod_id = '".$_POST['id']."'");
	if(mysql_num_rows($optQuery)==0) {
		print "<div align='center'><img src='im/zzz.gif' width='1' height='10'></div>";
		print "<table border='0' cellpadding='0' cellspacing='0' align='center'><tr>";
		print "<form action='opties_voorraad.php' method='POST'>";
		print "<td>";
		print "<div align='center'>";
		print "<input type='hidden' name='id' value='".$_POST['id']."'>";
		print "<input type='submit' value='".GESTION_STOCK_OPTIONS."'  style='border:#FF6600 3px solid; -moz-border-radius : 5px 5px 5px 5px;'>";
		print "</div>";
		print "</td>";
		print "</form>";
		print "</tr></table>";
		print "<div align='center'><img src='im/zzz.gif' width='1' height='10'></div>";
	}
	else {
		print "<div align='center'><img src='im/zzz.gif' width='1' height='10'></div>";
		print "<table border='0' cellpadding='0' cellspacing='0' align='center'><tr>";
		print "<form action='opties_voorraad.php' method='GET'>";
		print "<td>";
		print "<div align='center'>";
		print "<input type='hidden' name='id' value='".$_POST['id']."'>";
		print "<input type='submit' value='".STOCK_DECLINAISONS."' class='knop'>";
		print "</div>";
		print "</td>";
		print "</form>";
		print "</tr></table>";
		print "<div align='center'><img src='im/zzz.gif' width='1' height='10'></div>";
	}
}

if($num == 0) {
   mysql_query("UPDATE products SET products_options = 'no' WHERE products_id = '".$_POST['id']."'");
   exit;
}
 
print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'><tr height='30' bgcolor='#FFFFCC'>";
        print "<td><div align='left' valign='top'><b>".A9."</b></div></td>";
        print "<td><div align='left' valign='top'>".A10."</div></td>";
        print "<td>&nbsp;</td>";
        print "<td>&nbsp;</td>";
        print "<td>&nbsp;</td>";
        print "</tr>";
              while($optionName = mysql_fetch_array($optionNameQuery)) {
                    if($c=="#E8E8E8") {$c="#CCCCCC";} else {$c="#E8E8E8";}
                    $listValue = explode(",",$optionName['products_option']);
                    $listValueNum = count($listValue)-1;
                    
                    print "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='POST' onsubmit='return check_add()';>";
                    print "<tr bgcolor='".$c."'>";
                    print "<td valign=top align=left>";
                    print "<input type='text' class='vullen' name='optionName' value='".$optionName['products_options_name']."'>";
                    print "</td>";
                    print "<td>";
                    
                    print "<table border='0' cellpadding='0' cellspacing='0'><tr>";
                    print "<td valign=top align=left>".NOM_VALEUR."</td><td align='left' valign=top>".PRIX."</td><td align='left' valign=top>".POIDS."</td><td align='left' valign=top>".URL_TELECHARGEMENT."</td><td>&nbsp;</td></tr><tr>";
                            for($i=0; $i<=$listValueNum-1; $i++) {
                                $val = explode("::", $listValue[$i]);
                                ##print_r($val);
                                $ii = 0;
                                foreach($val AS $items) {
                                	$ii = $ii+1;
                                	$widthField1=40;
                                	$widthField2=9;
                                	$widthField3=5;
                                	$widthField4=40;
                                	
                                	if(preg_match("#http:==#i", $items) OR preg_match("#https:==#i", $items)) $items = str_replace("=","/",$items);
                                	$width = ${'widthField'.$ii};
                                	print "<td valign=top align=left>";
									print "<input type='text' class='vullen' size='".$width."' maxlength='100' name='option[]' value='".$items."'>&nbsp;";
									print "</td>";
								}
								/*
                               	$listValue[$i] = str_replace("::"," | ",$listValue[$i]);
                                if(preg_match("#http:==#i", $listValue[$i]) OR preg_match("#https:==#i", $listValue[$i])) $listValueInput = str_replace("=","/",$listValue[$i]); else $listValueInput = $listValue[$i];
                                print "<input type='text' class='vullen' size='100' maxlength='100' name='option[]' value='".$listValueInput."'>";
                                */
                                ## bouton supprimer individuellement
                                print "<td>";
                                print "&nbsp;<a href='".$_SERVER['PHP_SELF']."?num=".$i."&id=".$_POST['id']."&action=effacer&optionId=".$optionName['products_options_id']."&productId=".$_POST['id']."' title='".A3."' style='background:none; decoration:none'><img src='im/supprimer.gif' border='0'></a>";
                                print "</td>";
                                print "</tr><tr>";
                                ## Extraire values from options
                                $tab_stock[$optionName['products_options_name']][] = $val[0];
                            }
                    print "</tr></table>";
                            
                            // Notes sur l'option selectionne
                            print "<br>";
                            print NOTES." '<b>". $optionName['products_options_name']."</b>'";
                            print "<br>";
                            print "<img src='im/be.gif' class='absmiddle'>&nbsp;<img src='im/nl.gif' class='absmiddle'>&nbsp;<input type='text' size='70' class='vullen' name='note_option_3' value='".$optionName['note_option_3']."'>";
                            print "<br>";
                            print "<img src='im/leeg.gif' class='absmidle'>&nbsp;<img src='im/fr.gif' class='absmidle'>&nbsp;<input type='text' size='70' class='vullen' name='note_option_1' value='".$optionName['note_option_1']."'>";
                            print "<br>";
                            print "<img src='im/leeg.gif' class='absmidle'>&nbsp;<img src='im/uk.gif' class='absmiddle'>&nbsp;<input type='text' size='70' class='vullen' name='note_option_2' value='".$optionName['note_option_2']."'>";
                    // print "</td>";
                   // print "<td colspan='3'>";
                    print "<br>&nbsp;<center><input type='submit' name='action' value='".A4."' class='knop'>";
                    print "&nbsp;&nbsp;<input type='submit' name='action' value='".A3."' class='knop'>";
                    print "&nbsp;&nbsp;<input type='submit' name='action' value='".A700."' class='knop'>";
                    print "</td>";
                     print "</td>";
                   print "<td colspan='3'>";
                    print "<input type='hidden' name='id' value='".$_POST['id']."'>";
                    print "<input type='hidden' name='optionId' value='".$optionName['products_options_id']."'>";
                    print "</tr>";
              		print "</form>";
              }
print "</table>";
print "<br>";
$_SESSION['tab_values'] = array_values($tab_stock);

 

//------
// STOCK
//------
$optQuery = mysql_query("SELECT * FROM products_options_stock WHERE products_options_stock_prod_id = '".$_POST['id']."' ORDER BY products_options_stock_prod_name ASC");
if(mysql_num_rows($optQuery)>0) {
		print "<form action='opties_voorraad.php' method='GET'>";
		print "<p align='center'>";
		print "<input type='hidden' name='id' value='".$_POST['id']."'>";
		print "<input type='submit' value='".STOCK_DECLINAISONS."' class='knop'>";
		print "</p>";
		print "</form>";
		
		print "<div align='center'><a href='site_config.php#Stock'>".SEUIL_STOCK."</a> ".$seuilStock."</div>";
		print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
	
	$i=0;
	$c="";
	 
	print "<table border='0' align='center' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr bgcolor='#FFFFFF' height='30'>";
	print "<td><b>#</b></td>";
	print "<td align='left'><b>".DECLINAISON_ARTICLES."</b></td>";
	print "<td align='left'><b>".REFERENCES."</b></td>";
	print "<td align='left'><b>".REF_FOURNISSEUR."</b></td>";
	print "<td align='left'><b>".IMAGE."</b></td>";
	print "<td align='left'><b>".STOCKS."</b></td>";
	print "<td align='center'><b>".ACTIVE."</b></td>";
	print "<td align='center'>&nbsp;</td>";
	print "</tr><tr onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
	while($optResult = mysql_fetch_array($optQuery)) {
		$i=$i+1;
		if($c=="#E8E8E8") $c="#F1F1F1"; else $c="#E8E8E8";
		$totalStock[] = $optResult['products_options_stock_stock'];

		print "<td valign=top align=left>".$i."&nbsp;-&nbsp;</td>";
 
		$prodName = ($optResult['products_options_stock_active']=='no')? "<s>".$optResult['products_options_stock_prod_name']."</s>" : $optResult['products_options_stock_prod_name'];
		print "<td valign=top align=left>".$prodName."</td>";
 
		print ($optResult['products_options_stock_ref']=="")? "<td valign=top align=left'>--</td>" : "<td>".$optResult['products_options_stock_ref']."</td>";
 
		print ($optResult['products_options_stock_ean']=="")? "<td valign=top align=left'>--</td>" : "<td>".$optResult['products_options_stock_ean']."</td>";
 
		if($optResult['products_options_stock_im']=="") {
			print "<td  valign=top align=left'>--</td>";
		}
		else {
			$_image = explode("|",$optResult['products_options_stock_im']);
			if(count($_image)>1) {
				print "<td  valign=top align=left><a href='uitleg.php?idOpt=".$optResult['products_options_stock_id']."' target='_blank'>".VOIR_IMAGE."</a></td>";
			}
			else {
				print "<td valign=top align=left><img src='../".$optResult['products_options_stock_im']."' border='0' height='30'></td>";
			}
		}
 
		$styleStock="";
		if($optResult['products_options_stock_stock']<=$seuilStock) $styleStock="style='background:#FFBB00'";
		if($optResult['products_options_stock_stock']<=0) $styleStock="style='background:#FF0000'";
		if($optResult['products_options_stock_active']=='no') {
			$stockZ = "<div align='left'><img src='im/no_stock.gif'></div>" ;
			$styleStock = "";
		}
		else {
			$stockZ = $optResult['products_options_stock_stock'];
		}
		print "<td align='left' ".$styleStock.">".$stockZ."</td>";
 
		$activeDisplay = ($optResult['products_options_stock_active']=='yes')? "OK" : "<b>".NON."</b>";
		print "<td align='center'>".$activeDisplay."</td>";
 
		print "<td align='center'><a href='opties_voorraad.php?id=".$_POST['id']."'><img src='im/modify.gif' border='0' title='".MODIFIER."'></a></td>";
		print "</tr><tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
	}
	print "</table>";
	

	if(isset($totalStock) AND count($totalStock>0)) {
		print "<p align='center' style='font-size:12px;'><b>".TOTAL_STOCK.": ".array_sum($totalStock)."</b></p>";
	}
}
?>

<br><br><br>
</body>
</html>
