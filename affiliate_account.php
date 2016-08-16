<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = COMPTE_AFF;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
<script type="text/javascript"><!--
function check_formt() {
  var error = 0;
  var error_message = "";
  var clientV = document.form1.clientV.value;

  if(document.form1.elements['clientV'].type != "hidden") {
    if(clientV !== '') {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> '<?php print LEAVE_CHAMPS_EMPTY;?>'.\n";
      error = 1;
    }
  }
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
//   header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

//  header2
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

// Menu tab
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
// MENU HORIZONTAAL
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}
if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;

 
function display_form() {
	GLOBAl $_SESSION, $_POST;
		$aff2 = mysql_query("SELECT aff_nom, aff_prenom, aff_paypal, aff_banque, aff_addresse_banque, aff_titulaire, aff_rib, aff_cheque
	                     FROM affiliation
	                     WHERE aff_number='".$_POST['aff_account']."'
	                     AND aff_pass = '".$_POST['aff_pass']."'
	                     ORDER BY aff_id
	                     DESC
	                    ") or die (mysql_error());
	    $affDiplay2 = mysql_fetch_array($aff2);
	print "<form name='form1' action='affiliate_details.php' method='post' onsubmit='return check_formt()'>";
			print "<input type='hidden' name='aff_account' value='".$_POST['aff_account']."'>";
			print "<input type='hidden' name='aff_pass' value='".$_POST['aff_pass']."'>";
			
			
			print "<table border='0' cellpadding='2' cellspacing='0' align='center' class='TABLE1' style='padding:5px;'>";
				print "<tr class='backgroundCategorySelected'>";
			  		print "<td colspan='2' height='25' align='center'>";
					print "<b>".VOUS_RECEVREZ_VOTRE_ARGENT."</b>";
			  		print "</td>";
			  	print "</tr>";
			  	print "<tr>";
			    print "<td colspan='2'><img src='im/zzz.gif' width='1' height='4'></td>";
				print "</tr>";
			  	print "<tr><input name='aff_cheque' type='hidden' size='30' value='".$affDiplay2['aff_cheque']."'>";

			  	print "<tr>";
			    print "<td colspan='2'><img src='im/zzz.gif' width='1' height='4'></td>";
				print "</tr>";
			  print "<tr>";
			      print "<td align='left' valign='middle'>&bull;&nbsp;".PAYPAL_EMAIL."</td><td>";
				  	print "<input name='aff_paypal' type='text' size='30' value='".$affDiplay2['aff_paypal']."'>";
				  print "</td></tr>";
			  print "<tr>";
			    print "<td align='center' colspan='2'><img src='im/zzz.gif' width='1' height='4'></td></tr>";
			  print "<tr c/lass='backgroundMenuSousCategory'>";
			    print "<td align='left' valign='middle' colspan='2'>&bull;&nbsp;".VIREMENT_BANCAIRE."</td></tr>";
			  print "<tr class='backgroundMenuSousCategory'>";
			    print "<td align='right' valign='middle'>".BANQUE." </td><td><input name='aff_banque' type='text' size='30' value='".$affDiplay2['aff_banque']."'></td></tr>";
			  print "<tr class='backgroundMenuSousCategory'>";
			    print "<td align='right' valign='middle'>".ADRESSE.' '.strtolower(BANQUE)." </td><td><input name='aff_adressebanque' type='text' size='30' value='".$affDiplay2['aff_addresse_banque']."'></td></tr>";
			  print "<tr class='backgroundMenuSousCategory'>";
			    print "<td align='right' valign='middle'>".TITULAIRE_DU_COMPTE."</td><td><input name='aff_titulaire' type='text' size='30' value='".$affDiplay2['aff_titulaire']."'></td></tr>";
			  print "<tr class='backgroundMenuSousCategory'>";
			    print "<td align='right' valign='middle'>SWIFT/IBAN </td><td><input name='aff_RIB_IBAN' type='text' size='30' value='".$affDiplay2['aff_rib']."'></td></tr>";
			  print "<tr>";
			    print "<td align='left' colspan='2' height='10' valign='middle'>&nbsp;</td></tr>";
			print "<tr><td colspan='2'>";

			print "<input type='hidden' name='clientV' size='5' value=''>";

			
			print "<input type='hidden' name='affiliate_form' value='yes'>";
			print "<center><input type='submit' value='".UPDATE."' name='submit'>";
			print "</p>";
			print "</td></tr>";
			print "</table>";
			print "</form>";
}

if(isset($_POST['affiliate_form']) AND $_POST['affiliate_form']=="yes" AND isset($_POST['clientV']) AND $_POST['clientV']=="") {
	$_POST['aff_cheque'] = str_replace("'","&#146;",$_POST['aff_cheque']);
	$_POST['aff_banque'] = str_replace("'","&#146;",$_POST['aff_banque']);
	$_POST['aff_adressebanque'] = str_replace("'","&#146;",$_POST['aff_adressebanque']);
	$_POST['aff_titulaire'] = str_replace("'","&#146;",$_POST['aff_titulaire']);
	$_POST['aff_RIB_IBAN'] = str_replace("'","&#146;",$_POST['aff_RIB_IBAN']);
	
	mysql_query("UPDATE affiliation 
				SET 
				aff_cheque = '".$_POST['aff_cheque']."',
				aff_banque = '".$_POST['aff_banque']."',
				aff_addresse_banque = '".$_POST['aff_adressebanque']."',
				aff_titulaire = '".$_POST['aff_titulaire']."',
				aff_rib = '".$_POST['aff_RIB_IBAN']."',
				aff_paypal = '".$_POST['aff_paypal']."'
				WHERE aff_number = '".$_POST['aff_account']."'") or die (mysql_error());
	$messageAff = COMPTE_A_JOUR;
}
?>

      <?php if($tableDisplay=='oui') {?>
      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
      <tr height="32">
      <?php if($tableDisplayLeft=='oui') {?>
      <td>
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print COMPTE_AFF;?> |</b>
      </td>
      <?php 
      }
      if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
      </tr>
      </table>
      <?php }?>

      <?php include('includes/promo_afbeelden.php');?>

    </td>
  </tr>
  <tr valign="top">
    <td colspan="3">




      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // -----
		  // links
		  // -----
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
					  <?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".COMPTE_AFF." |";?>
					  </td>
                    </tr>
                  </table>

<?php
if(isset($messageAff)) {print "<p align='center' class='styleAlert' style='padding:5px;'>".$messageAff."</p>";}
if(isset($_POST['aff_account'])) {
	$aff = mysql_query("SELECT aff_nom, aff_prenom, aff_paypal, aff_number, aff_pass
	                     FROM affiliation
	                     WHERE aff_number='".$_POST['aff_account']."'
	                     AND aff_pass = '".$_POST['aff_pass']."'
	                     ORDER BY aff_id
	                     DESC
	                    ") or die (mysql_error());
 
	$rowsAff = mysql_num_rows($aff);
	if($rowsAff>0) {
			$affDiplay = mysql_fetch_array($aff);
			print "<p>";
			print $affDiplay['aff_prenom']." ".$affDiplay['aff_nom'].",<br>";
			print BIENVENU2." ".SUR_VOTRE_COMPTE." <b>".$_POST['aff_account']."</b>.";
			print "</p>";
			
 
			$affFact = mysql_query("SELECT users_affiliate_amount, users_date_added, users_products_ht, users_affiliate_payed, users_refund, users_nic
			                        FROM users_orders
			                        WHERE users_affiliate = '".$_POST['aff_account']."'
			                        AND users_confirm = 'yes'
			                        AND users_payed = 'yes'
			                        AND users_nic NOT LIKE 'TERUG%' 
			                        AND users_refund = 'no'
			                        ORDER BY users_id
			                        DESC
			                    	") or die (mysql_error());
			$rowsAffFact = mysql_num_rows($affFact);
			if($rowsAffFact>0) {
			    $totalAffFact = array(0);
			    $totalAffFactPayed = array(0);
			    print "<b><u>".DETAILS_COMPTE."</u></b>:";
			    $ii = 1;
			    print "<p>";
			    print "<table border='0' cellspacing='1' cellpadding='3' align='center' class='TABLEBorderDotted'>";
			    print "<tr class='TABLETopTitle' height='25'>";
			    print "<td><b>ID</b></td>";
			    print "<td align='center'><b>".DATE."</b></td>";
			    print "<td><b>".MONTANT_HT."</b></td>";
			    print "<td><b>".COMMISSION."</b></td>";
			    print "<td><b>".PAYE."</b></td>";
			    print "<td align='center'><b>".COMMENTAIRES."</b></td>";
			    print "</tr><tr>";
 
			    while($affDiplay = mysql_fetch_array($affFact)) {
					$totalCom[] = $affDiplay['users_affiliate_amount'];
					
					print "<td>".$ii++."</td>";      
					print "<td>".dateFr($affDiplay['users_date_added'],$_SESSION['lang'])."</td>";
					print "<td align='center'>".$affDiplay['users_products_ht']."</td>";
					print "<td align='center'>".$affDiplay['users_affiliate_amount']."</td>";
					
					if($affDiplay['users_affiliate_payed'] == 'no') {
						print "<td align='center' class='fontrouge'><b>".strtoupper(NON)."</b></td>";
						$totalAffFact[] = $affDiplay['users_affiliate_amount'];
					}
					else {
						print "<td align='center'>".strtoupper(OUI)."</td>";
						$totalAffFactPayed[] = $affDiplay['users_affiliate_amount'];
					}
 
					if($affDiplay['users_refund']=="yes" OR preg_match("/\bTERUG\b/i", $affDiplay['users_nic'])) {
						$cc = "class='fontrouge'";
						$commentRef = "<b>".COMMANDE_REMBOURSE."</b>";
					}
					else {
						$cc = "";
						$commentRef = "--";
					}
					print "<td align='center' ".$cc.">".$commentRef."</td>";
					print "</tr>";
			    }
			    print "</table>";
			    print "</p>";
			
			    $totalCom = sprintf("%0.2f",array_sum($totalCom));
			    $totalAffFactt = sprintf("%0.2f",array_sum($totalAffFact));
			    $totalAffFactPayedd = sprintf("%0.2f",array_sum($totalAffFactPayed));

			        print "<u>".MONTANT_COM."</u> <b>".$totalCom." ".$symbolDevise."</b>";
 
			        print "<br><u>".TOTAL_PAYE."</u> <b>".$totalAffFactPayedd." ".$symbolDevise."</b>";
 
			        print "<br><u>".MONTANT_DU."</u> <b>".$totalAffFactt." ".$symbolDevise."</b>";
 
			        print "<br><u>".SEUIL_DE_PAIEMENT."</u> <b>".sprintf("%0.2f",$affiliateTop)." ".$symbolDevise."</b>";
			        
			        if($totalAffFactt >= $affiliateTop) {
			            $toPay = "<p>";
						$toPay.= "<div>".SEUIL_DE_PAIEMENT_A_ETE_DEPASSE."</div>";
						$toPay.= "<div class='fontrouge'><b>".MONTANT_A_PAYER.": ".sprintf("%0.2f",$totalAffFactt)." ".$symbolDevise."</b></div>";
						$toPay.= "<div>".CE_MONTANT_VOUS_SERA_ENVOYE."</div>";
						$toPay.= "</p>";
			        }
			        else {
			            $toPay = "<p class='fontrouge'><b>".SEUIL_DE_PAIEMENT." ".NON_ATTEINT."</p>";
			        }
			        print $toPay;
			}
			else {
				print "<p class='fontrouge'><b>".EN_DATE_AUCUNE."</b></p>";
				print "<p>";
				print PLACER_UN_LIEN." ";
				print "<a href='javascript:void(0);' onClick=\"window.open('affiliate_link.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=390,width=600,toolbar=no,scrollbars=no,resizable=yes');\"><b>".ICI."</b></a>";
				print "</p>";
			}
			display_form();
	}
	else {
	  print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".AUCUN_COMPTE_AFF."</p>";
	}
}
?>
<br><br>";
          </td>
          </tr>
          </table>
          </td>

         <?php 
 // ------
 // rechts
 // ------
 if($colomnRight=='oui') include("includes/kolom_rechts.php");
?>
          
 </tr>
 </table>

    </td>
  </tr>
</table>      

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>
