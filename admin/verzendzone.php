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

if(isset($_POST['update']) and $_POST['update'] == 'update') {
            $keysZone = array_keys($_POST['yo']);
            $valuesZone = array_values($_POST['yo']);
            $valuesShipTax = array_values($_POST['tax_ship']);
            $valuesPackTax = array_values($_POST['tax_pack']);
            $valuesShipTaxActive = array_values($_POST['tax_ship_active']);
            $valuesPackTaxActive = array_values($_POST['tax_pack_active']);
            $valuesProductTaxActive = array_values($_POST['tax_prod_active']);
            $valuesProdTax = array_values($_POST['tax_prod']);
            $valuesIso = array_values($_POST['isoSelect']);

/*
print_r($_POST['yo']);
print "<br><br>"; 
print count($keysZone); 
print "<br><br>";
print count($valuesIso);  
print "<br><br>";

print "<pre>";
print_r($valuesZone);
print "</pre>";
*/
        $yoNb = count($_POST['yo'])-1;
        for($a=0; $a<=$yoNb; $a++) {
             mysql_query("UPDATE countries
                          SET countries_shipping = '".$valuesZone[$a]."',
                              countries_shipping_tax_active = '".$valuesShipTaxActive[$a]."',
                              countries_shipping_tax = '".$valuesShipTax[$a]."',
                              countries_packing_tax_active = '".$valuesPackTaxActive[$a]."',
                              countries_packing_tax = '".$valuesPackTax[$a]."',
                              countries_product_tax = '".$valuesProdTax[$a]."',
                              countries_product_tax_active = '".$valuesProductTaxActive[$a]."',
                              iso = '".$valuesIso[$a]."'
                          WHERE countries_id = '".$keysZone[$a]."'");
            }
}


if(!isset($_GET['order'])) $_GET['order'] = "countries_name";
if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

$query = mysql_query("SELECT *
                      FROM countries
                      ORDER BY ".$_GET['order']."
                      ".$_GET['c1']."");
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>



<p align="center">
<a href="verzendprijzen.php"><?php print A2;?></a><br>
<b>*</b> <?php print A3;?>

</p>

<table align="center" border="0" cellpadding="1" cellspacing="2" class="TABLE"><tr>
<td width="50" align="center" bgcolor="#CCCCCC">Zone 1</td>
<td width="50" align="center" bgcolor="#CCCC00">Zone 2</td>
<td width="50" align="center" bgcolor="#FFFFFF">Zone 3</td>
<td width="50" align="center" bgcolor="#FFFF00">Zone 4</td>
<td width="50" align="center" bgcolor="#FFC600">Zone 5</td>
<td width="50" align="center" bgcolor="#12FF00">Zone 6</td>
<td width="50" align="center" bgcolor="#7683FF">Zone 7</td>
<td width="50" align="center" bgcolor="#FFB0EF">Zone 8</td>
<td width="50" align="center" bgcolor="#996699">Zone 9</td>
<td width="50" align="center" bgcolor="#0000FF">Zone 10</td>
<td width="50" align="center" bgcolor="#990000">Zone 11</td>
<td width="50" align="center" bgcolor="#f1f1f1">Zone 12</td>
<td width="50" align="center" bgcolor="#FF0000"><?php print A4;?></td>
</tr>
</table>
<br>
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">

<?php
if(isset($_GET['pays']) AND $_GET['pays']!=="") print "<p align='center' style='background:#888888; padding:10px; color:#FFFFFF; border:#FFFF00 2px solid; font-size:13px';><b>".EXCLURE." '".$_GET['pays']."' ".DANS_LA_LISTE."</b><p>";
print "<table border='0' align='center' cellpadding='3' cellspacing ='0' class='TABLE'>";
print "<tr>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_name&c1=".$_GET['c1']."'>".A6."</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=iso&c1=".$_GET['c1']."'>ISO</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z1</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z2</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z3</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z4</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z5</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z6</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z7</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z8</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z9</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z10</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z11</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>Z12</a></b></td>";
print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=countries_shipping&c1=".$_GET['c1']."'>".A7."</a></b></td>";
print "<td align='center'><b>".A9."</b></td>";
print "<td align='center'><b>".A8."</b></td>";
print "<td align='center'><b>".A8A."</b></td>";


print "</tr>";
          while($result = mysql_fetch_array($query)) {

                $id = $result['countries_id'];

                if($result['countries_shipping'] == "zone1") {
                   $c = "#CCCCC";
                   $sel1 = "checked";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                if($result['countries_shipping'] == "zone2") {
                   $c = "#CCCC00";
                   $sel1 = "";
                   $sel2 = "checked";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                if($result['countries_shipping'] == "zone3") {
                   $c = "#FFFFFF";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "checked";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                if($result['countries_shipping'] == "zone4") {
                   $c = "#FFFF00";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "checked";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                if($result['countries_shipping'] == "zone5") {
                   $c = "#FFC600";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "checked";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone6") {
                   $c = "#12FF00";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "checked";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone7") {
                   $c = "#7683FF";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "checked";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone8") {
                   $c = "#FFB0EF";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "checked";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone9") {
                   $c = "#996699";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "checked";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone10") {
                   $c = "#0000FF";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "checked";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone11") {
                   $c = "#990000";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "checked";
                   $sel12 = "";
                   $selE = "";
                   }
                   if($result['countries_shipping'] == "zone12") {
                   $c = "#f1f1f1";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "checked";
                   $selE = "";
                   }
                if($result['countries_shipping'] == A10) {
                   $c = "#FF0000";
                   $sel1 = "";
                   $sel2 = "";
                   $sel3 = "";
                   $sel4 = "";
                   $sel5 = "";
                   $sel6 = "";
                   $sel7 = "";
                   $sel8 = "";
                   $sel9 = "";
                   $sel10 = "";
                   $sel11 = "";
                   $sel12 = "";
                   $selE = "checked";
                   }
                print "<tr bgcolor=$c><td>";
                print $result['countries_name'];
                print "<td align='center'>";
                
                  print "<input type='text' value='".$result['iso']."' size='2' class='vullen' name='isoSelect[".$result['countries_id']."]' maxlength='3'>";
                
                
                
                
                
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone1' name='yo[".$id."]' ".$sel1.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone2' name='yo[".$id."]' ".$sel2.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone3' name='yo[".$id."]' ".$sel3.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone4' name='yo[".$id."]' ".$sel4.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone5' name='yo[".$id."]' ".$sel5.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone6' name='yo[".$id."]' ".$sel6.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone7' name='yo[".$id."]' ".$sel7.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone8' name='yo[".$id."]' ".$sel8.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone9' name='yo[".$id."]' ".$sel9.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone10' name='yo[".$id."]' ".$sel10.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone11' name='yo[".$id."]' ".$sel11.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='zone12' name='yo[".$id."]' ".$sel12.">";
                print "</td>";
                print "<td align='center'>";
                print "<input type='radio' value='".A10."' name='yo[".$id."]' ".$selE.">";
                print "</td>";
                
                print "<td align='center'>";
                        print "<select name='tax_prod_active[".$result['countries_id']."]'>";
                        if($result['countries_product_tax_active'] == "no") $selshipNo1= "selected"; else $selshipNo1="";
                        if($result['countries_product_tax_active'] == "yes") $selshipYes1= "selected"; else $selshipYes1="";
                        print "<option value='yes' ".$selshipYes1.">".A11."</option>";
                        print "<option value='no' ".$selshipNo1.">".A12."</option>";
                        print "</select>";
                print " <input type='text' class='vullen' size='2' value='".$result['countries_product_tax']."' name='tax_prod[".$result['countries_id']."]'>%";
                print "</td>";


                print "<td align='center'>";
                        print "<select name='tax_ship_active[".$result['countries_id']."]'>";
                        if($result['countries_shipping_tax_active'] == "no") $selshipNo= "selected"; else $selshipNo="";
                        if($result['countries_shipping_tax_active'] == "yes") $selshipYes= "selected"; else $selshipYes="";
                        print "<option value='yes' ".$selshipYes.">".A11."</option>";
                        print "<option value='no' ".$selshipNo.">".A12."</option>";
                        print "</select>";
                print " <input type='text' class='vullen' size='2' value='".$result['countries_shipping_tax']."' name='tax_ship[".$result['countries_id']."]'>%";
                print "</td>";


                print "<td align='center'>";
                        print "<select name='tax_pack_active[".$result['countries_id']."]'>";
                        if($result['countries_packing_tax_active'] == "no") $selPackNo= "selected"; else $selPackNo="";
                        if($result['countries_packing_tax_active'] == "yes") $selPackYes= "selected"; else $selPackYes="";
                        print "<option value='yes' ".$selPackYes.">".A11."</option>";
                        print "<option value='no' ".$selPackNo.">".A12."</option>";
                        print "</select>";
                print " <input type='text' class='vullen' size='2' value='".$result['countries_packing_tax']."' name='tax_pack[".$result['countries_id']."]'>%";
                print "</td>";

/*
                
                print "<td align='center'>";
					$requestMode = mysql_query("SELECT livraison_id, livraison_country, livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$result['countries_id']."|%' AND livraison_active='yes'") or die (mysql_error());
					$pays1 = $result['countries_id'];
					if(isset($livraison)) unset($livraison);
					if(mysql_num_rows($requestMode) > 0) {
						while($requestModeResult = mysql_fetch_array($requestMode)) {
							##$livraison[] = ($result['countries_shipping']=='exclude')? "" : $requestModeResult['livraison_nom_'.$_SESSION['lang']];
							$livraison[] = $requestModeResult['livraison_nom_'.$_SESSION['lang']];
						}
					}
					if(isset($livraison)) {
						foreach($livraison AS $itt) {
							print (count($livraison)>1)? $itt."<br>" : $itt;
						}
					}
					else {
						print ($result['countries_shipping']=='exclude')? "<img src='im/no_stock2.gif'>" : "<span style='background:#000000; color:#FFFFFF; padding:2px; font-size:11px'><b>??</b></span>";
					}
                print "</td>";
*/

/*
                
                print "<td align='center'>";
                        print "<select name='country_state_select[".$result['countries_id']."]'>";
                        if($result['country_state'] == "country") $selPays= "selected"; else $selpays="";
                        if($result['country_state'] == "state") $selEtat= "selected"; else $selEtat="";
                        print "<option value='country' $selPays>Pays</option>";
                        print "<option value='state' $selEtat>Province</option>"; 
                        print "</select>";
                print "</td>";
*/
                print "</tr>";
          }
print "</table>";
         print "<br>";
         print "<table border='0' align='center' cellpadding='3' cellspacing ='0'><tr><td>";
         print "<input type='hidden' value='update' name='update'>";
         print "<input type='submit' value='".A5."' class='knop'>";
         print "</td></tr></table>";
?>
</form>
  </body>
  </html>
