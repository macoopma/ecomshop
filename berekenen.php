<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
 
 
if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes") {
	$countryQuery = mysql_query("SELECT users_country FROM users_orders WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());
	$countryResult = mysql_fetch_array($countryQuery);
	$_POST['country'] = $countryResult['users_country'];
}

if(!isset($_POST['country'])) $_POST['country']="vide";
$title = $_POST['country'];
$messageEr="";
?>
<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title><?php print $title." | ".$store_name;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include('includes/stijl.inc');?>

<?php
if($euroPayment == "oui" AND $displayGraphics == "oui") {
    print '<script type="text/javascript" src="http://partenaires.1euro.com/partenaires/js/popup.js"></script>';
}
?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
include('includes/geen_script.php');
  
if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes") {
	print "<div align='center' style='color:#FFFFFF; background:#808080; border:#FFFF00 1px solid; padding:7px;'>";
	print "<span style='font-size:13px;'>** ".CLIQUEZ_BOUTON_UPDATE." ".$_SESSION['clientNicZ']." **</span>";
	print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	print "<i><a href='".$_SERVER['PHP_SELF']."?cancel=1'><span style='color:#FFFF00'>".CLIQUEZ_ICI_POUR_ANNULER."</span></a></i>";
	print "</div>";
}

if($_POST['country']=="vide") {
    print "<p align='center'><b>".VEUILLEZ_SELECTIONNER." ".PAYS."!</b></p>";
    exit;
}

if(isset($_SESSION['list'])) {

print "<p align='center'><span style='background:#000000; color:#FFFFFF; border:#CCCCCC 1px solid; padding:5px;'>".LIV.": ".maj($_POST['country'])."</span></p>";
  

 
$splitZ = explode(",",$_SESSION['list']);
foreach ($splitZ as $item) {
    $check = explode("+",$item);
    $requestShipDownload = mysql_query("SELECT products_download, products_tax, products_weight, products_taxable FROM products WHERE products_id = '".$check[0]."'") or die (mysql_error());
    $resultShipDownload = mysql_fetch_array($requestShipDownload);
    if($resultShipDownload['products_download']=="yes") $down[]=1;
  
                        $p_ = $resultShipDownload['products_weight'];
                        if($resultShipDownload['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
}
$_poidZ = sprintf("%0.2f",array_sum($poid));

if($_poidZ > 0) {

 
if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes" AND !isset($_POST['shipping'])) {
	$recupLivQuery = mysql_query("SELECT users_shipping FROM users_orders WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());
	$recupLivResult = mysql_fetch_array($recupLivQuery);
	if($recupLivResult['users_shipping']!=='0') $_POST['shipping'] = $recupLivResult['users_shipping'];
}

  
  $requestShipCountry = mysql_query("SELECT countries_id FROM countries WHERE countries_name = '".$_POST['country']."'");
  $oountryIdResult = mysql_fetch_array($requestShipCountry);
  $oountryId = $oountryIdResult['countries_id'];

 
	$requestpays = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes'") or die (mysql_error());    
	$requestpaysNum = mysql_num_rows($requestpays);
	if($_poidZ>=999999999) {
		$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max='999999999'") or die (mysql_error());
		$requestWeightNum = mysql_num_rows($requestWeight);
	}
	else {
		$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."'") or die (mysql_error());
		$requestWeightNum = mysql_num_rows($requestWeight);
	}
	$messageEr.= ($requestpaysNum==0)? AUCUNE_LIVRAISON_DANS_CE_PAYS."<br>" : "";
	$messageEr.= ($requestpaysNum > 0 AND $requestWeightNum==0)? POIDS_TOTAL_DEPASSE_NOS_CAPACITES : "";
	
 
	if($_poidZ>=999999999) {
		$requestShip = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max ='999999999' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
	}
	else {
		$requestShip = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
	}

	if(mysql_num_rows($requestShip) > 0) {
            print "<p align='center'><b>".VEUILLEZ_CHOISIR_MODE_DE_LIVRAISON."</b></p>";
            print "<form method='POST' action='berekenen.php' name='formZ' onsubmit='return formShip()'>";
            print "<input type='hidden' name='actionLiv' value='liv'>";
            print "<input type='hidden' name='country' value='".$_POST['country']."'>";
            print "<table width='500' border='0' align='center' cellspacing='0' cellpadding='3' class='TABLEBorderDotted' style='padding:5px;'><tr><td>";
            print "<table border='0' align='center' cellspacing='0' cellpadding='3' style='padding:5px;'>";
			
            while($resultShip = mysql_fetch_array($requestShip)) {
                $tarifRequestZ = mysql_query("SELECT id FROM ship_price WHERE livraison_id = '".$resultShip['livraison_id']."'") or die (mysql_error());
                if(mysql_num_rows($tarifRequestZ)>0) {
                   print "<tr>";
                   print "<td width='1'>";
                   if(!isset($_POST['shipping'])) $_POST['shipping']=$resultShip['livraison_id'];
                   if($_POST['shipping']==$resultShip['livraison_id']) $selLiv="checked"; else $selLiv="";
                   print "<input type='radio' ".$selLiv." name='shipping' value='".$resultShip['livraison_id']."' style='BACKGROUND:none; border:none' onclick='submit();'>";
                   print "</td>";
                   print "<td>";
                   print $resultShip['livraison_nom_'.$_SESSION['lang']];
                   print "</td>";
                   print "<td align='left' width='100'>";
                     if($resultShip['livraison_image']!=="") {
                        ## Resize image
                        $sizeImShip = @getimagesize($resultShip['livraison_image']);
                        if($sizeImShip) {
                            $widthShipIm = ($sizeImShip[0]>100)? "width='100'" : "";
                        }
                        else {
                            $widthShipIm = "";
                        }
                        print "<img ".$widthShipIm." src='".$resultShip['livraison_image']."' title='".$resultShip['livraison_nom_'.$_SESSION['lang']]."' alt='".$resultShip['livraison_nom_'.$_SESSION['lang']]."'>";
                     }
                     else {
                        print "&nbsp;";
                     }
                   print "</td>";
                   print "<td align='left'>";
                      $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('leverings_wijze.php?id=".$resultShip['livraison_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=400,toolbar=no,scrollbars=no,resizable=yes');\">";
                      if($resultShip['livraison_note_'.$_SESSION['lang']]!=="") print $openLeg."<img src='im/info_plus.gif' border='0' title='".PLUS_INFOS."' align='absmiddle'></a>"; else print "&nbsp;";
                      ## NO Javascript
                      print "<noscript>";
                      $openUseSearch22 = "<a href='#' class='tooltip'><b><span class='darkBackground' style='padding:2px;'>&nbsp;?&nbsp;</span></b><em style='width: 375px; left:30px'>".$resultShip['livraison_note_'.$_SESSION['lang']]."</em></a>";
                      if($resultShip['livraison_note_'.$_SESSION['lang']]!=="") print "&nbsp;".$openUseSearch22; else print "&nbsp;";
                      print "</noscript>";
                   print "</td>";
                   print "</tr>";
                }
            }
            print "<tr><td colspan='4'>";
  
            print "<noscript>";
            print "<div><img src='im/zzz.gif' width='1' height='10'></div>";
            print "<div align='center'><input type='submit' value='OK'></div>";
            print "</noscript>";
            
            print "</td></tr>";
            print "</table>";
            print "</td></tr></table>";
            print "</form>";
	}
	else  {
		print (isset($messageEr) AND $messageEr!=="")? "<p align='center'>".$messageEr."</p>" : "";
		print "<table border='0' cellpadding='0' cellspacing='0' align='center'><tr><td class='styleAlert' style='padding:10px'><img src='im/note.gif' align='absmiddle'>&nbsp;".PARAMS_LIVRAISON_NON_DEFINIS."</td></tr></table>";
		$param = 1;
	}
}
else {
    $_POST['shipping'] = 0;
}
 
if(isset($_POST['shipping'])) {
  print "<br>";
  include('includes/plug.inc.php');
  print invoice_display($_POST['country'],"TABLEBorderDotted","",$_POST['shipping']);
  
  if($euroPayment == "oui" AND $displayGraphics == "oui" AND $id_partenaire!=="" AND $_SESSION['totalToPayTTC'] > 60 AND $_SESSION['totalToPayTTC'] < 8000) {
     print "<p align='center'><a title='Financement 1Euro.com' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".$_SESSION['totalToPayTTC']."\")'><img src='im/betaal-logos/calc_bt_total.gif' border='0'></a></p>";
  }
}
else {
    if(!isset($param)) print "<table border='0' cellpadding='0' cellspacing='0' align='center'><tr><td class='styleAlert' style='padding:10px'><img src='im/note.gif' align='absmiddle'>&nbsp;".PARAMS_LIVRAISON_NON_DEFINIS."</td></tr></table>";
}
$_SESSION['ActiveUrl'] = "caddie.php";
?>


<p align="center">
<b><a href="javascript:window.close()">X</a></b>
</p>
<?php
}
?>
</body>
</html>
