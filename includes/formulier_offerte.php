<?php


if(isset($_SESSION['clientGender'])) unset($_SESSION['clientGender']);
if(isset($_SESSION['clientFirstname'])) unset($_SESSION['clientFirstname']);
if(isset($_SESSION['clientLastname'])) unset($_SESSION['clientLastname']);
if(isset($_SESSION['clientCompany'])) unset($_SESSION['clientCompany']);
if(isset($_SESSION['clientEmail'])) unset($_SESSION['clientEmail']);
if(isset($_SESSION['clientStreetAddress'])) unset($_SESSION['clientStreetAddress']);
if(isset($_SESSION['clientSurburb'])) unset($_SESSION['clientSurburb']);
if(isset($_SESSION['clientPostCode'])) unset($_SESSION['clientPostCode']);
if(isset($_SESSION['clientProvince'])) unset($_SESSION['clientProvince']);
if(isset($_SESSION['clientCountry'])) unset($_SESSION['clientCountry']);
if(isset($_SESSION['clientCity'])) unset($_SESSION['clientCity']);
if(isset($_SESSION['clientTelephone'])) unset($_SESSION['clientTelephone']);
if(isset($_SESSION['clientFax'])) unset($_SESSION['clientFax']);

if(isset($_SESSION['clientFactComp'])) unset($_SESSION['clientFactComp']);
if(isset($_SESSION['clientFactAddress'])) unset($_SESSION['clientFactAddress']);
if(isset($_SESSION['clientFactCode'])) unset($_SESSION['clientFactCode']);
if(isset($_SESSION['clientFactVille'])) unset($_SESSION['clientFactVille']);
if(isset($_SESSION['clientFactPays'])) unset($_SESSION['clientFactPays']);
if(isset($_SESSION['clientFactEmail'])) unset($_SESSION['clientFactEmail']);

if(isset($_SESSION['paymentMode'])) unset($_SESSION['paymentMode']);
if(isset($_SESSION['clientPassword'])) unset($_SESSION['clientPassword']);
if(isset($_SESSION['clientNic'])) unset($_SESSION['clientNic']);
if(isset($_SESSION['clientComment'])) unset($_SESSION['clientComment']);
if(isset($_SESSION['clientTVA'])) unset($_SESSION['clientTVA']);
if(isset($_SESSION['livraisonhors'])) unset($_SESSION['livraisonhors']);
if(isset($_SESSION['shipTax'])) unset($_SESSION['shipTax']);
if(isset($_SESSION['totalHtFinal'])) unset($_SESSION['totalHtFinal']);
if(isset($_SESSION['shipPrice'])) unset($_SESSION['shipPrice']);
if(isset($_SESSION['montantRemise'])) unset($_SESSION['montantRemise']);
if(isset($_SESSION['montantRemise2'])) unset($_SESSION['montantRemise2']);
if(isset($_SESSION['saveDataFromForm'])) unset($_SESSION['saveDataFromForm']);
if(isset($_SESSION['fact_adresse'])) unset($_SESSION['fact_adresse']);
if(isset($_SESSION['priceEmballageTTC'])) unset($_SESSION['priceEmballageTTC']);
if(isset($_SESSION['totalEmballageHt'])) unset($_SESSION['totalEmballageHt']);
if(isset($_SESSION['totalEmballageTva'])) unset($_SESSION['totalEmballageTva']);
if(isset($_SESSION['shippingName'])) unset($_SESSION['shippingName']);
if(isset($_SESSION['shippingId'])) unset($_SESSION['shippingId']);

if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == 1) {
     $query = mysql_query("SELECT * FROM code_promo WHERE code_promo = '".$_SESSION['coupon_name']."'");
     $result = mysql_fetch_array($query);
}


 
$retrieve_data = mysql_query("SELECT * FROM devis WHERE devis_number  = '".$_SESSION['devisNumero']."' LIMIT 0 , 1");
$nomNum = mysql_num_rows($retrieve_data);
 if($nomNum > 0) {
      $nom = mysql_fetch_array($retrieve_data);
      $message = "<p align='left' class='fontrouge'><b>".BIENVENU2." ".$nom['devis_firstname']." !<b></p>";
      $ret_ok = "1";
    }
    else {
	  $ret_ok = "0";
	}
?>
  
<script type="text/javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var clientFirstname = document.form101.clientFirstname.value;
  var clientLastname = document.form101.clientLastname.value;
  var clientStreetAddress = document.form101.clientStreetAddress.value;
  var clientPostCode = document.form101.clientPostCode.value;
  var clientCity = document.form101.clientCity.value;
  var clientCountry = document.form101.clientCountry.value;

  if(document.form101.elements['clientFirstname'].type != "hidden") {
    if(clientFirstname == '' || clientFirstname.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print PRENOM;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientLastname'].type != "hidden") {
    if(clientLastname == '' || clientLastname.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NOM;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientStreetAddress'].type != "hidden") {
    if(clientStreetAddress == '' || clientStreetAddress.length < 5) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print ADRESSE;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientPostCode'].type != "hidden") {
    if(clientPostCode == '' || clientPostCode.length < 4) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print CODE_POSTAL;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientCity'].type != "hidden") {
    if(clientCity == '' || clientCity.length < 3) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print VILLE;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientCountry'].type != "hidden") {
    if(document.form101.clientCountry.value == 'no') {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> '<?php print PAYS;?>'.\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['toto11'].type != "hidden") {
    if(document.form101.toto11.checked == false) {
      error_message11 = error_message11 + "<?php print VEUILLEZ_PRENDRE_CONNAISSANCE;?>\n";
      error11 = 1;
    }
  }

  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//-->
</script>

<?php if(isset($message)) print $message;?>

<form action="selecteer_verzending.php" method="POST" name="form101" onsubmit="return formu()">

  <table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td width="105">&nbsp;<?php print ADRESSE_EMAIL;?>&nbsp;:</td>
            <?php if($ret_ok == "1") $a=$nom['devis_email']; else $a="";?>
            <td>&nbsp;<?php print $a;?>
              <input type="hidden" name="clientEmail" size="40" value="<?php print $a;?>">
            </td>
          </tr>
        </table>
        <?php 
          if($noTva == "oui") {
        ?>
        <br>
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td width="175">&nbsp;<?php print NO_TVA;?>&nbsp;:
            </td>
            <?php 
              if($ret_ok == "1") {
                  $a=$nom['devis_tva'];
               }
               else {
                  $a="";
               }
            ?>
            <td>&nbsp;<?php if(empty($a)) print '--'; else print $a;?>
              <input type="hidden" name="clientTVA" size="30" value="<?php print $a;?>">
            </td>
          </tr>
        </table>
        <?php 
           }
           else {
                print '<input type="hidden" name="clientTVA" value="">';
           }
        ?>
      </td>
    </tr>
    <tr>
    <tr>
      <td>
        <b><?php print VOTRE_ADRESSE_D_EXPEDITION;?></b></td>
    </tr>
    <tr>
      <td valign="top">
<?php
 
$explodeFactAd = array($nom['devis_lastname']." ".$nom['devis_firstname'],$nom['devis_company'],$nom['devis_address'],"",$nom['devis_city'],$nom['devis_cp'],$nom['devis_country'],$nom['devis_tva']);
?>
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td>&nbsp;<?php print NOM;?>:</td>
            <td>&nbsp;
              <input type="text" name="clientLastname" value="<?php print $nom['devis_lastname'];?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print PRENOM;?>&nbsp;:</td>
            <td>&nbsp;
              <input type="text" name="clientFirstname" value="<?php print $nom['devis_firstname'];?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?>&nbsp;:</td>
            <td>&nbsp;
              <input type="text" name="clientCompany" value="<?php print $nom['devis_company'];?>">
            </td>
          </tr>
          <tr>
            <td>&nbsp;<?php print ADRESSE;?>&nbsp;:</td>
            <td>&nbsp;
              <textarea name="clientStreetAddress" cols="40" rows="1"><?php print $nom['devis_address'];?></textarea>
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print VILLE;?>&nbsp;:</td>
            <td>&nbsp;
              <input type="text" name="clientCity" size="20" value="<?php print $nom['devis_city'];?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print CODE_POSTAL;?>&nbsp;:</td>
            <td>&nbsp;
              <input type="text" name="clientPostCode" size="10" value="<?php print $nom['devis_cp'];?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
            <tr>
            <td>&nbsp;<?php print PAYS;?>&nbsp;:</td>
            <td>
              <?php if($ret_ok == "1") $uu=$nom['devis_country']; else $uu="";?>
              &nbsp;<?php print $uu;?>
              <input type="hidden" name="clientCountry" value="<?php print $uu;?>">
              </td>
          </tr>
        </table>
        
        
 
      </td>
    </tr>
    <tr>
    <tr>
      <td>
        <?php print VOTRE_ADRESSE_DE_FACTURATION22;?></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td>&nbsp;<?php print COMPAGNIE22;?>&nbsp;:</td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[0]; else $uu="";?>
            <td>
			&nbsp;<?php print $uu;?>
            <input type="hidden" name="clientFactComp" size="30" value="<?php print $uu;?>">
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?>&nbsp;:</td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[1]; else $uu="";?>
            <td>
			&nbsp;<?php print $uu;?>
            <input type="hidden" name="clientFactCompany" size="30" value="<?php print $uu;?>">
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print ADRESSE;?>&nbsp;:</td>
            <td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[2]; else $uu="";?>
              &nbsp;<?php print $uu;?>
              <input type="hidden" name="clientFactAddress" size="40" value="<?php print $uu;?>">
              <input type="hidden" name="clientFactSurburb" size="40" value="">
              <input type="hidden" name="clientTelephone" value="<?php print $nom['devis_tel'];?>">
              <input type="hidden" name="clientFax" value="<?php print $nom['devis_fax'];?>">
              <input type="hidden" name="clientGender" value="M">
              <input type="hidden" name="news" value="no">
              <input type="hidden" name="save_info" value="no">
              <input type="hidden" name="clientSurburb" value="">
              <input type="hidden" name="clientProvince" value="">
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print VILLE;?>&nbsp;:</td>
			<td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[4]; else $uu="";?>
              &nbsp;<?php print $uu;?>
              <input type="hidden" name="clientFactCode" size="20" value="<?php print $uu;?>">
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print CODE_POSTAL;?>&nbsp;:</td>
			<td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[5]; else $uu="";?>
              &nbsp;<?php print $uu;?>
              <input type="hidden" name="clientFactVille" size="10" value="<?php print $uu;?>">
			</td>
          </tr>
           <tr>
            <td>&nbsp;<?php print PAYS;?>&nbsp;:</td>
			<td>
              <?php if($ret_ok == "1") $uu=$explodeFactAd[6]; else $uu="";?>
              &nbsp;<?php print $uu;?>
              <input type="hidden" name="clientFactPays" size="20" value="<?php print $uu;?>">
			</td>
          </tr>
        </table>
        
        
      </td>
    </tr>
    
    
    <tr>
      <td> <br>
        <b><?php print SI_VOUS_AVEZ_DES_COMMENTAIRES;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
          <tr>
            <td align="center">
              <textarea name="clientComment" rows="4" cols="50"></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td valign="top"><br>
        <b><?php print VOTRE_COMMANDE;?></b> </td>
    </tr>
    <tr>
      <td valign="top">
<?php
print "<table width='400' border='0' cellspacing='0' cellpadding='5' align='center' class='TABLE1'><tr><td>";

print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>";
print "<tr>";
print "<td width='50'><b>".REF."</b></td>";
print "<td><b>".ARTICLES."</b></td>";
print "<td width='50' align='center'><b>".QTE."</b></td>";


print "<td width='80' align='right'><b>".PRIX."</b></td>";
$split = explode(",",$_SESSION['list']);
foreach ($split as $item) {
	 
	$check = explode("+",$item);
	$query = mysql_query("SELECT p.*, s.specials_new_price,s.specials_last_day, s.specials_first_day, s.specials_visible
	                      FROM products as p
	                      LEFT JOIN specials as s
	                      ON (p.products_id = s.products_id)
	                      WHERE p.products_id = '".$check[0]."'");
	$row = mysql_fetch_array($query);
	if($check[1]!=="0") {
		print "</tr><tr>";
		 
		print "<td width='50'>".strtoupper($row['products_ref'])."</td>";
		 
		print "<td>";
		print $row['products_name_'.$_SESSION['lang']]." ";

		// Options
        if(!empty($check[6])) {
        	$_optZ = explode("|",$check[6]);
			## session update option price
			$lastArray = $_optZ[count($_optZ)-1];
			if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
			$_optZ = implode("|",$_optZ);
			print "<br><i>".$_optZ."</i>";
		}

		print "</td>";
 
		$new_price = $row['specials_new_price'];
		$old_price = $row['products_price'];
		if(empty($new_price)) {
			//$price = $old_price;
			$price = $check[2];
		}
		else {
			if($row['specials_visible']=="yes") {
				$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$dateMaxCheck = explode("-",$row['specials_last_day']);
				$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
				$dateDebutCheck = explode("-",$row['specials_first_day']);
				$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
				
				if($dateDebut <= $today  and $dateMax >= $today) {
					$price = $check[2];
				}
				else {
					$price = $check[2];
				}
			}
			else {
				$price = $check[2];
			}
		}
		
		 
		print "<td width='50' align='center'>".$check[1]."</td>";
		
 
		$p_ = $row['products_weight'];
		if($row['products_download'] == "yes") {$p_=0;}
		$poidsOptionsArray = explode('|',$check[8]);
		$poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
		$poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
		$_SESSION['poids'] = sprintf("%0.2f",array_sum($poid));
		
	 
		$priceTTC = ($price * $check[1]);
		$totalht = $priceTTC;
		$total_taxe = 0;
		print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
		
		if($taxePosition == "Tax included") $_SESSION['taxStatut'] = "TTC";
		if($taxePosition == "Plus tax") $_SESSION['taxStatut'] = "HT";
		if($taxePosition == "No tax") $_SESSION['taxStatut'] = "";
		
		$totalTaxeArray[] = $total_taxe;
		$totalTaxeFinal = array_sum($totalTaxeArray);
		$_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxeFinal);
		
		$totalHtArray[] = $totalht;
		$_SESSION['totalHtFinal'] = array_sum($totalHtArray);
		$_SESSION['totalHtFinal'] = sprintf("%0.2f",$_SESSION['totalHtFinal']);
		
		$priceHt[] = DisplayProductPrice($iso,$row['products_tax'],$totalht);
		$_SESSION['totalHtFinalPromo'] = array_sum($priceHt);
		
		$totalTTCArray[] = $priceTTC;
		$totalTTCFinal = array_sum($totalTTCArray);
	}
}
	$totht = $_SESSION['totalHtFinal'];
	$_SESSION['totalTax'] = $totalTaxeFinal;
	$_SESSION['totalTax'] = sprintf("%0.2f",$_SESSION['totalTax']);
	$totttc = $totht+$_SESSION['totalTax'];
	$_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);

                print "</tr></table>";


                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td>";

 
                if($taxePosition == "Plus tax") {
	                print "<div align='left'><span class='fontrouge'>".TAXE_NON_INCLUSE."</span></div>";
				}
                if($taxePosition == "Tax included") {
	                print "<div align='left'><span class='fontrouge'>".TAXE_INCLUSE."</span></div>";
				}
                if($taxePosition == "No tax") {
	                print "<div align='left'><span class='fontrouge'>".PAS_DE_TAXE."</span></div>";
				}
 
                if($activerPromoLivraison == "oui" and $_SESSION['totalHtFinalPromo']>=$livraisonComprise) print "<div align='left'><span class='fontrouge'>".strtolower(LIVRAISON_GRATUITE)." (<a href='voorwaarden.php' target='_blank'>*</a>)</span></div>"; else print "<div align='left'><span class='fontrouge'>".LIVRAISON_NON_INCLUSE."</span></div>";
                print "</td>";
                print "</tr></table>";
				if($taxePosition == "Tax included") {$taxStat = "TTC";}
				if($taxePosition == "Plus tax") {$taxStat = strtoupper(HT);}
				if($taxePosition == "No tax") {$taxStat = "";}
                print "<table border='0' align='right' cellspacing='0' cellpadding='1'><tr>";
                print "<td align='left'>".PRODUITS." ".$taxStat."</td>";
                
                print "<td align='right'><b>".$_SESSION['totalHtFinal']."</b></td>";
                print "</tr><tr>";

 
                if($activerRemise == "oui" and $_SESSION['totalHtFinalPromo']>=$remiseOrderMax) {

                       if($remiseType == "%") $_SESSION['montantRemise'] = $_SESSION['totalHtFinalPromo']*($remise/100);
                       if($remiseType == $symbolDevise) $_SESSION['montantRemise'] = $remise;
                       $_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
                       
	                if($taxePosition == "Tax included") {
	                    print "<td align='left'>".REMISE." (-$remise$remiseType)</td>: ";
	                    print "<td align='right'><span class='fontrouge'>Active</span>";
	                	print "</td></tr><tr>";
	                	$discount[] = 0;
	                }
	                else {
	                    print "<td align='left'>".REMISE." (-$remise$remiseType)</td> ";
						print "<td align='right'><span class='fontrouge'>-".$_SESSION['montantRemise']."</span>";
						print "</td></tr><tr>";
						$discount[] = $_SESSION['montantRemise'];
					}
                }
                else {
                        $totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
                        $_SESSION['montantRemise'] = sprintf("%0.2f",0);
                        $discount[] = 0;
                }

 
                if(isset($_SESSION['openAccount']) and isset($_SESSION['accountRemise']) AND $_SESSION['accountRemise'] > 0) {
	                    if($_SESSION['totalHtFinalPromo'] >= $_SESSION['accountRemise']) {
						  $discount[] = $_SESSION['accountRemise'];
						  $_SESSION['accountRemiseEffec'] = $_SESSION['accountRemise'];
						}
						else {
						  $discount[] = $_SESSION['totalHtFinal'];
						  $_SESSION['accountRemiseEffec'] = $_SESSION['totalHtFinal'];
						}
						print "<td align='left'>".REMISE_SUR_COMMANDES."</td>";
						print "<td align='right'><span class='fontrouge'>-".$_SESSION['accountRemiseEffec']."</span></td></tr><tr>";
				}

 
				if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == 1) {
					$seuilPromoCode = $result['code_promo_seuil'];
					
					if($_SESSION['totalHtFinalPromo'] > $seuilPromoCode) {
						$remiseCoupon = $result['code_promo_reduction'];
						$remiseCouponType = $result['code_promo_type'];
						$_SESSION['coupon_name'] = $result['code_promo'];
						if($remiseCouponType == "%") {$_SESSION['montantRemise2'] = $_SESSION['totalHtFinalPromo']*($remiseCoupon/100);}
						if($remiseCouponType == $symbolDevise) {$_SESSION['montantRemise2'] = $remiseCoupon;}
						$_SESSION['montantRemise2'] = sprintf("%0.2f",$_SESSION['montantRemise2']);
						$discount[] = $_SESSION['montantRemise2'];
						
						if($taxePosition == "Tax included") {
							print "<td align='left'>";
							print COUPON_CODE." (-$remiseCoupon$remiseCouponType)";
							print "<br><i>Coupon: ".$result['code_promo']."</i>";
							print "</td>";
							print "<td align='right' valign='top'><span class='fontrouge'>Active</span>";
							print "</td></tr><tr>";							
						}
						else {
							print "<td align='left'>";
							print COUPON_CODE." (-$remiseCoupon$remiseCouponType)";
							print "<br><i>Coupon: ".$result['code_promo']."</i>";
							print "</td>";
							print "<td align='right' valign='top'><span class='fontrouge'>-".$_SESSION['montantRemise2']."</span></b>";
							print "</td></tr><tr>";							
						}
					}
					else {
						$totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
						$_SESSION['montantRemise2'] = sprintf("%0.2f",0);
						$discount[] = 0;
					}
				}

 
                $totalDiscount = array_sum($discount);
                $totttc = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount));

                if($taxePosition == "Tax included") {
                	print "<td align='right' colspan='2'>";
					print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>".sprintf("%0.2f",$_SESSION['totalHtFinal'])." ".$symbolDevise."</b></span>";
					print "</td>";
                }
                else {
	                print "<td align='right' colspan='2'>";
					print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>".sprintf("%0.2f",$totttc)." ".$symbolDevise."</b></span>";
					print "</td>";
				}
                print "</tr>";
                print "</table>";

print "</td></tr></table>";



print "<p align='left'>";        
        print "<table border='0' cellspacing='0' cellpadding='0'><tr><td>";
        print "<img src='im/fleche_right.gif'>&nbsp;<a href='infos.php?info=4' target='_blank'>".AI_LU."</a>";
        print "</td><td>";
        print "<input type='checkbox' name='toto11' style='background:#none'>";
        print "</td></tr></table>";
print "</p>";
?>
      </td>
    </tr>
  </table>
<br>
<table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td><INPUT TYPE="submit" VALUE="<?php print CONTINUER;?>"></td>
  </tr>
  <tr>
    <td align="right"><span class="fontrouge">*</span> <?php print CHAMPS_OBLIGATOIRES;?></td>
  </tr>
</table>

</form>
