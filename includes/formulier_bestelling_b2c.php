<?php


if(isset($_SESSION['devisNumero'])) $seuil = 0; else $seuil = $minimumOrder;


if(isset($_SESSION['list']) AND !empty($_SESSION['list']) AND !isset($_SESSION['totalTTC'])) {
$art = explode(",",$_SESSION['list']);
$nb_article = count($art);

$_SESSION['tot_art'] = 0;
$_SESSION['totalTTC'] = 0;

for($n=0; $n <= $nb_article-1; $n++) {
$article = explode("+",$art[$n]);
$_SESSION['tot_art'] = $_SESSION['tot_art'] + $article[1];
$_SESSION['totalTTC'] = $_SESSION['totalTTC'] + ($article[2]*$article[1]);
}
}

if(!isset($_SESSION['list']) or $_SESSION['list'] == "" or empty($_SESSION['list']) or $_SESSION['tot_art']=="0" or $_SESSION['totalTTC'] < $seuil ) {
	print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='7' class='TABLE1'>";
	print "<tr>";
	print "<td class='titre' align='center'>";
	if(isset($_SESSION['totalTTC']) and $_SESSION['totalTTC'] < $minimumOrder) {
    	print "<b>".COMMANDE_MINIMUM.": ".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</b>";
    }
	else {
    	print VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE;
    }
	print "</td>";
	print "</tr>";
	print "</table>";
}
else {

if(isset($_SESSION['devisNumero'])) {
    include("includes/formulier_offerte.php");
}
else {
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

$message = "";
$a = "";
$b = "";
$ret_ok = "0";
if(!isset($queryExt)) $queryExt = "users_pro_";

 
if(isset($_SESSION['account'])) {
$retrieve_data = mysql_query("SELECT *
                      FROM users_pro
                      WHERE users_pro_password  = '".$_SESSION['account']."'
                      ORDER BY users_pro_id
                      DESC 
                      LIMIT 0 , 1
                     ");
$nomNum = mysql_num_rows($retrieve_data);
 if($nomNum > 0) {
      $nom = mysql_fetch_array($retrieve_data);
      $queryExt = "users_pro_";
      $queryExtZip = "users_pro_postcode";
      $ret_ok = "1";
    }
    else {
	  $ret_ok = "0";
	  $queryExt = "users_pro_";
     $queryExtZip = "users_pro_postcode";
	}
}

if(isset($_GET['retrieve_info']) and !empty($_GET['retrieve_info']) AND $_GET['retrieve_info'] !== 'no') {
$retrieve_data = mysql_query("SELECT *
                              FROM users_orders
                              WHERE users_save_data_from_form  = '".$_GET['retrieve_info']."'
                              LIMIT 0 , 1
                             ");
$nomNum = mysql_num_rows($retrieve_data);
    if($nomNum > 0) {
      $nom = mysql_fetch_array($retrieve_data);
      $queryExt = "users_";
      $queryExtZip = "users_zip";
      $ret_ok = "1";
    }
    else {
      $message = "<br><br><b><span class='fontrouge'>".COMPTE_NON_VALIDE."</span><b>";
      $queryExt = "users_";
      $queryExtZip = "users_zip";
      $ret_ok = "0";
    }
}

    display_payment_process(2,"");

if(isset($_GET['cond']) AND $_GET['cond']==0) {
    print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
    print VEUILLEZ_PRENDRE_CONNAISSANCE;
    print "</p>";
}

if(isset($message)) print $message;
    
 
   $recoverFormQuery = mysql_query("SELECT users_save_data_from_form, users_firstname, users_lastname, users_gender, users_firstname, users_lastname, users_company, users_address, users_zip, users_city, users_surburb, users_province, users_country
       						   FROM users_orders
       						   WHERE users_password = '".$_SESSION['account']."'
       						   AND users_save_data_from_form !='no'
    						      ");
   $recoverFormQueryNum = mysql_num_rows($recoverFormQuery);
?>

<?php
if($recoverFormQueryNum>0) {
?>
  <table border="0" width="400" cellspacing="5" cellpadding="0" align="center" class="TABLE1">
    <tr>
      <td>
      <?php print VOUS_ETES_ENREGISTRE_ET_VOTRE_MOT_DE_PASSE_EST;?>
      <br>
      <?php
      $i=1;
      while($recoverForm = mysql_fetch_array($recoverFormQuery)) {
         $imageStock = "im/lang".$_SESSION['lang']."/stockok.png";
         $datas = "<div align='left' style='width: 200px;'>";
         $datas.= $recoverForm['users_gender'].". ".$recoverForm['users_firstname']." ".$recoverForm['users_lastname'].",<br>";
         ($recoverForm['users_company']!=="")? $datas.= $recoverForm['users_company']."<br>" : $datas.= "";
         $datas.= $recoverForm['users_address']."<br>";
         ($recoverForm['users_surburb']!=="")? $datas.= $recoverForm['users_surburb']."<br>" : $datas.= "";
         $datas.= $recoverForm['users_city']."<br>";
         $datas.= $recoverForm['users_zip']." ";
         ($recoverForm['users_province']!=="")? $datas.= $recoverForm['users_province']."<br>" : $datas.= "";
         $datas.= $recoverForm['users_country'];
         $datas.= "</div>";
         
         print "<div align='left'>";
         print "&bull; <a href='payment.php?retrieve_info=".$recoverForm['users_save_data_from_form']."' class='tooltip'>".$recoverForm['users_save_data_from_form']."<em><b>".AUTRE_ADRESSE_DE_LIVRAISON." :</b>".$datas."</em></a>";
         print "&nbsp;<i><span class='fontgris'>(".$recoverForm['users_lastname']." ".$recoverForm['users_firstname'].")</span></i>";
         print "</div>";
      
      }
      ?>
      </td>
      </tr>
  </table>
<?php
}
?>

<script type="text/javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var clientFirstname = document.form101.clientFirstname.value;
  var clientLastname = document.form101.clientLastname.value;
  var clientEmail = document.form101.clientEmail.value;
  var clientStreetAddress = document.form101.clientStreetAddress.value;
  var clientPostCode = document.form101.clientPostCode.value;
  var clientCity = document.form101.clientCity.value;
  var clientCountry = document.form101.clientCountry.value;
  var clientTelephone = document.form101.clientTelephone.value;

  if(document.form101.elements['clientGender'].type != "hidden") {
    if(document.form101.clientGender[0].checked || document.form101.clientGender[1].checked) {
    } else {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> <?php print CIVILITE;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientFirstname'].type != "hidden") {
    if(clientFirstname == '' || clientFirstname.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientLastname'].type != "hidden") {
    if(clientLastname == '' || clientLastname.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientEmail'].type != "hidden") {
    if(clientEmail == '' || clientEmail.length < 6 || clientEmail.indexOf ('@') == -1 || clientEmail.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientStreetAddress'].type != "hidden") {
    if(clientStreetAddress == '' || clientStreetAddress.length < 5) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientPostCode'].type != "hidden") {
    if(clientPostCode == '' || clientPostCode.length < 4) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print CODE_POSTAL;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientCity'].type != "hidden") {
    if(clientCity == '' || clientCity.length < 3) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print VILLE;?>\n";
      error11 = 1;
    }
  }

  if(document.form101.elements['clientCountry'].type != "hidden") {
    if(document.form101.clientCountry.value == 'no') {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> <?php print PAYS;?>\n";
      error11 = 1;
    }
  }

<?php if($vb2b == 'oui') {?>
  if(document.form101.elements['clientTelephone'].type != "hidden") {
    if(clientTelephone == '' || clientTelephone.length < 6) {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> Telefoon\n";
      error11 = 1;
    }
  }
<?php }?>

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

<form action="selecteer_verzending.php" method="POST" name="form101" onsubmit="return formu()"; id='formH'>
<?php
 
        $queryExp = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$_SESSION['account']."'");
        $rowExp = mysql_fetch_array($queryExp);
?>
        <input type="hidden" name="clientFactComp" value="<?php print $rowExp['users_pro_gender']." ".$rowExp['users_pro_lastname']." ".$rowExp['users_pro_firstname'];?>">
        <input type="hidden" name="clientFactCompany" value="<?php print $rowExp['users_pro_company'];?>">
        <input type="hidden" name="clientFactAddress" value="<?php print $rowExp['users_pro_address'];?>">
        <input type="hidden" name="clientFactSurburb" value="<?php print "";?>">
        <input type="hidden" name="clientFactCode" value="<?php print $rowExp['users_pro_postcode'];?>">
        <input type="hidden" name="clientFactVille" value="<?php print $rowExp['users_pro_city'];?>">
        <input type="hidden" name="clientFactPays" value="<?php print $rowExp['users_pro_country'];?>">
        <input type="hidden" name="clientEmail" value="<?php print $rowExp['users_pro_email'];?>">



  <table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
    <tr>
      <td>
        <b><?php print VOTRE_ADRESSE_D_EXPEDITION;?></b></td>
    </tr>
    <tr>
      <td valign="top">
<?php

?>
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
        <tr>
            <td>&nbsp;<?php print CIVILITE;?>&nbsp;</td>
            <?php
            if($ret_ok == "1") {
                if($nom[$queryExt.'gender'] == "M") $a = "checked"; else $a="";
                if($nom[$queryExt.'gender'] == "Mme") $b = "checked"; else $b="";
                }
            ?>
            <td>&nbsp;
              <input type="radio" name="clientGender" value="M" <?php print $a;?> style='BACKGROUND:none; border:none'>
              &nbsp;<?php print M;?>&nbsp;&nbsp;
              <input type="radio" name="clientGender" value="Mme" <?php print $b;?> style='BACKGROUND:none; border:none'>
              &nbsp;<?php print MME;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print NOM;?></td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'lastname']; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientLastname" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print PRENOM;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'firstname']; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientFirstname" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'company']; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientCompany" value="<?php print $a;?>">
            </td>
          </tr>
          <tr>
            <td>&nbsp;<?php print ADRESSE;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'address']; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientStreetAddress" size="40" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;
              <input type="text" name="clientSurburb" size="40" value="">
              &nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print VILLE;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'city']; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientCity" size="20" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print CODE_POSTAL;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExtZip]; else $a="";?>
            <td>&nbsp;
              <input type="text" name="clientPostCode" size="10" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
<?php
 
$prov = mysql_query("SELECT countries_name
                      FROM countries
                      WHERE country_state = 'state' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
$provNum = mysql_num_rows($prov);
if($provNum>1) {
?>
            <tr>
            <td>&nbsp;Provincie</td>
            <td>&nbsp;
              <select name="clientProvince">
                <option value="autre"><?php print maj(AUTRES);?></option>
                <option value="no">----</option>
<?php
                while ($provinces = mysql_fetch_array($prov)) {
                       print "<option value='".$provinces['countries_name']."'>".$provinces['countries_name']."</option>";
                       }
?>
              </select>
              </td>
          </tr>
<?php 
}
?>

          <tr>
<?php
 
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
            <td>&nbsp;<?php print PAYS;?>&nbsp;</td>
            <td>&nbsp;
              <select name="clientCountry">
                <option value="no">----</option>
                <?php
                while ($countries = mysql_fetch_array($pays)) {
                    if($countries['iso'] == $iso) $a = "selected"; else $a="";
                        $countryName = $countries['countries_name'];
                        $countryValue = $countries['countries_name'];
                        if($ret_ok == "1" AND $countries['countries_name'] == $nom[$queryExt.'country']) $a = "selected"; else $a="";
                        print "<option value='".$countryValue."' ".$a.">".$countryName."</option>";
                }
?>
              </select>
            </td>
          </tr>
        </table>
        
        

      </td>
    </tr>

<?php

?>

    <tr>
      <td valign="top">
         <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
          <tr>
            <td>&nbsp;<?php print ADRESSE_EMAIL;?> </td>
            <td><b><?php print $rowExp['users_pro_email'];?></b></td>
          </tr>
<?php 
if($noTva == "oui" AND $rowExp['users_pro_tva']!=="") {
?>
            <tr>
            <td width="180">&nbsp;<?php print NO_TVA;?>&nbsp;:</td>
            <td>&nbsp;<?php print $rowExp['users_pro_tva'];?>&nbsp;
<?php
if($tvaManuelValidation=="oui") {
$tvaValidationQueryZww = mysql_query("SELECT users_pro_tva_confirm FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
$tvaValidationZww = mysql_fetch_array($tvaValidationQueryZww);
if($tvaValidationZww['users_pro_tva_confirm']=='yes') {
print "<img src='im/checked.gif' align='absmiddle' title='".NO_TVA." OK'>";
}
else {
if($tvaValidationZww['users_pro_tva_confirm']=='??') print "<span style='background:#FF0000; color:#FFFFFF; border:#FFFF00 1px solid; padding:1px 2px 1px 2px;' title='".NO_TVA." ".EN_ATTENTE_DE_VALIDATION."'><b>??</b></span>";
if($tvaValidationZww['users_pro_tva_confirm']=='no') print "<span style='background:#FF0000; color:#FFFFFF; border:#FFFF00 1px solid; padding:1px 2px 1px 2px;' title='".NO_TVA." ".NON_VALIDE."'><b>X</b></span>";
}
}              
?>
            </td>
            <input type="hidden" name="clientTVA" value="<?php print $rowExp['users_pro_tva'];?>">
            </tr>
<?php 
}
else {
 print '<input type="hidden" name="clientTVA" value="">';
}
?>
			<tr>
			<td colspan='2'>
			<div class='FontGris' align='right'>[<a href='mijn_account.php#mod' alt='<?php print MODIFIER;?>' title='<?php print MODIFIER;?>'><span class='FontGris'><?php print MODIFIER;?></span></a>]</div>
			</td>
			</tr>
         </table>
      </td>
    </tr>


<?php
 
?>
    <tr>
      <td>
        <?php print VOTRE_ADRESSE_DE_FACTURATION22;?></td>
    </tr>
    <tr>
      <td valign="top">
          <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td width="120">&nbsp;<?php print $rowExp['users_pro_gender'];?> </td>
            <td width="280">&nbsp;<?php print $rowExp['users_pro_firstname']." ".$rowExp['users_pro_lastname'];?></td>
          </tr>
          <?php 
          if(!empty($rowExp['users_pro_company'])) {
          ?>
          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?> </td>
            <td>&nbsp;<?php print $rowExp['users_pro_company'];?></td>
          </tr>
          <?php
          }
          ?>
          <tr>        
            <td>&nbsp;<?php print ADRESSE;?> </td>
            <td>&nbsp;<?php print $rowExp['users_pro_address'];?></td>
          </tr>       
          <tr>        
            <td>&nbsp;<?php print VILLE;?> </td>
            <td>&nbsp;<?php print $rowExp['users_pro_city'];?></td>
          </tr>       
          <tr>        
            <td>&nbsp;<?php print CODE_POSTAL;?> </td>
            <td>&nbsp;<?php print $rowExp['users_pro_postcode'];?></td>
          </tr>       
          <tr>        
            <td>&nbsp;<?php print PAYS;?> </td>
            <td>&nbsp;<?php print $rowExp['users_pro_country'];?></td>
          </tr>
			<tr>
			<td colspan='2'>
			<div class='FontGris' align='right'>[<a href='mijn_account.php#mod' alt='<?php print MODIFIER;?>' title='<?php print MODIFIER;?>'><span class='FontGris'><?php print MODIFIER;?></span></a>]</div>
			</td>
			</tr>
          </table>
        
        
      </td>
    </tr>
    <tr>
      <td><br>
        <?php print VOS_INFORMATIONS_PERSONNELLE;?></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td width="100">&nbsp;<?php print NUMERO_DE_TELEPHONE;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'telephone']; else $a="";?>
            <td align="left">&nbsp;
              <input type="text" name="clientTelephone" value="<?php print $a;?>">
              &nbsp;&nbsp;<span class="fontrouge"><?php ($vb2c == "oui")? print "" : print "*";?></span>
            </td>
          </tr>
          <tr>
            <td width="100">&nbsp;<?php print FAX;?>&nbsp;</td>
            <?php if($ret_ok == "1") $a=$nom[$queryExt.'fax']; else $a="";?>
            <td align="left">&nbsp;
              <input type="text" name="clientFax" value="<?php print $a;?>">
              &nbsp;</td>
          </tr>
			<tr>
			<td colspan='2'>
			<div class='FontGris' align='right'>[<a href='mijn_account.php#mod' alt='<?php print MODIFIER;?>' title='<?php print MODIFIER;?>'><span class='FontGris'><?php print MODIFIER;?></span></a>]</div>
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
              <textarea name="clientComment" rows="4" cols="60"></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td valign="top">
<?php
print "<p align='left'>";
        print "<table border='0' cellspacing='0' cellpadding='0'><tr><td>";
        print "<img src='im/fleche_right.gif'>&nbsp;".SAVE_INFO;
        print "</td><td>";
        print "<input type='checkbox' name='save_info' value='yes' style='background:#none'>";
        print "</td></tr></table>";
        
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
    <td align="center"><INPUT TYPE="submit" VALUE="<?php print CONTINUER;?>"></td>
  </tr>
  <tr>
    <td align="right"><span class="fontrouge">*</span> <?php print CHAMPS_OBLIGATOIRES;?></td>
  </tr>
</table>

</form>
<?php
}
}
?>
