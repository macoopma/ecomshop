<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = AFFILIATION;
 
$description = $title." ".$store_name;
$keywords = $title.", ".$store_name.", ".$keywords;

if(isset($_POST['affiliate_form']) AND $_POST['affiliate_form']=="yes" AND isset($_POST['clientV']) AND $_POST['clientV']=="") {

 
    $str1a = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789ABCDEF';
		$affiliateNumber = '';
    	for( $i = 0; $i < 10 ; $i++ ) {
        	$affiliateNumber .= substr($str1a, rand(0, strlen($str1a) - 1), 1);
        }
 
    $str1a = '123456789';
		$affiliatePass = '';
    	for( $i = 0; $i < 6 ; $i++ ) {
        	$affiliatePass .= substr($str1a, rand(0, strlen($str1a) - 1), 1);
        }

$affPrenom = str_replace("\'","'",$_POST['aff_prenom']);
$_POST['societe'] = str_replace("'","&#146;",$_POST['societe']);
$_POST['aff_nom'] = str_replace("'","&#146;",$_POST['aff_nom']);
$_POST['aff_prenom'] = str_replace("'","&#146;",$_POST['aff_prenom']);
$_POST['aff_adresse1'] = str_replace("'","&#146;",$_POST['aff_adresse1']);
$_POST['aff_adresse2'] = str_replace("'","&#146;",$_POST['aff_adresse2']);
$_POST['aff_ville'] = str_replace("'","&#146;",$_POST['aff_ville']);
$_POST['aff_pays'] = str_replace("'","&#146;",$_POST['aff_pays']);
$_POST['aff_banque'] = str_replace("'","&#146;",$_POST['aff_banque']);
$_POST['aff_adressebanque'] = str_replace("'","&#146;",$_POST['aff_adressebanque']);
$_POST['aff_observations'] = str_replace("'","&#146;",$_POST['aff_observations']);
$_POST['aff_titulaire'] = str_replace("'","&#146;",$_POST['aff_titulaire']);

mysql_query("INSERT INTO affiliation (
              aff_number,
              aff_company,
              aff_nom,
              aff_prenom,
              aff_email,
              aff_adresse1,
              aff_adresse2,
              aff_zip,
              aff_ville,
              aff_pays,
              aff_telephone,
              aff_web,
              aff_banque,
              aff_addresse_banque,
              aff_rib,
              aff_observation,
              aff_cheque,
              aff_paypal,
              aff_titulaire,
              aff_pass,
              aff_com
              )
            VALUES ('".$affiliateNumber."',
                    '".$_POST['societe']."',
                    '".$_POST['aff_nom']."',
                    '".$_POST['aff_prenom']."',
                    '".$_POST['aff_email']."',
                    '".$_POST['aff_adresse1']."',
                    '".$_POST['aff_adresse2']."',
                    '".$_POST['aff_codepostal']."',
                    '".$_POST['aff_ville']."',
                    '".$_POST['aff_pays']."',
                    '".$_POST['aff_telephone']."',
                    '".$_POST['aff_web']."',
                    '".$_POST['aff_banque']."',
                    '".$_POST['aff_adressebanque']."',
                    '".$_POST['aff_RIB_IBAN']."',
                    '".$_POST['aff_observations']."',
                    '".$_POST['aff_cheque']."',
                    '".$_POST['aff_paypal']."',
                    '".$_POST['aff_titulaire']."',
                    '".$affiliatePass."',
                    '".$affiliateCom."'
                    )");


	   	$to = $_POST['aff_email'];
	   	$subject = AFFILIATION." - ".$domaineFull;
	   	$from = $mailInfo;
		$_store = str_replace("&#146;","'",$store_company);
		$messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
	   $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailInfo."\r\n\r\n";
	   $messageToSend .= $affPrenom.",\r\n\r\n";
	   $messageToSend .= ENREGISTRER_AU_PROGRAMME." http://".$www2.$domaineFull.".\r\n";
	   $messageToSend .= VOTRE_NUMERO_AFFILIE_EST." : ".$affiliateNumber."\r\n";
	   $messageToSend .= VOTRE_PASS_EST." : ".$affiliatePass."\r\n";
	   $messageToSend .= VOTRE_COM." : ".$affiliateCom."%\r\n\r\n";
	   $messageToSend .= CLIQUER_SUR." :\r\n";
       $messageToSend .= "http://".$www2.$domaineFull."/affiliate_linkweb.php?numAff=".$affiliateNumber."\r\n\r\n";
	   $messageToSend .= POUR_TOUTE_INFORMATION."\r\n";
	   $messageToSend .= LE_SERVICE_CLIENT."\r\n";
	   $messageToSend .= $mailInfo;
	   
	   mail($to, $subject, rep_slash($messageToSend),
       "Return-Path: $from\r\n"
       ."From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());

		$messageAff = MOT_DE_PASSE_ENVOYE." ".$to;
}
?>

<html>

<head>
<?php include('includes/hoofding.php');?>

<script type="text/javascript"><!--
function check_formt() {
  var error = 0;
  var error_message = "";

  var aff_nom = document.form1.aff_nom.value;
  var aff_prenom = document.form1.aff_prenom.value;
  var aff_email = document.form1.aff_email.value;
  var aff_adresse1 = document.form1.aff_adresse1.value;
  var aff_codepostal = document.form1.aff_codepostal.value;
  var aff_ville = document.form1.aff_ville.value;
  var aff_pays = document.form1.aff_pays.value;
  var aff_web = document.form1.aff_web.value;
  var clientV = document.form1.clientV.value;

  if(document.form1.elements['aff_nom'].type != "hidden") {
    if(aff_nom == '' || aff_nom.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
      error = 1;
    }
  }

  if(document.form1.elements['aff_prenom'].type != "hidden") {
    if(aff_prenom == '' || aff_prenom.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_email'].type != "hidden") {
    if(aff_email == '' || aff_email.length < 6 || aff_email.indexOf ('@') == -1 || aff_email.indexOf ('.') == -1 ) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_adresse1'].type != "hidden") {
    if(aff_adresse1 == '' || aff_adresse1.length < 5) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE."1";?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_codepostal'].type != "hidden") {
    if(aff_codepostal == '' || aff_codepostal.length < 4) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print CODE_POSTAL;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_ville'].type != "hidden") {
    if(aff_ville == '' || aff_ville.length < 3) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print VILLE;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_pays'].type != "hidden") {
    if(aff_pays == '' || aff_pays.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print PAYS;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_web'].type != "hidden") {
    if(aff_web == '' || aff_web.length < 16 || aff_web.indexOf ('.') == -1) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> Website\n";
      error = 1;
    }
  }
  if(document.form1.elements['aff_toto'].type != "hidden") {
    if(document.form1.aff_toto.value == 'no') {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> Ik ga akkoord met de voorwaarden\n";
      error = 1;
    }
  }
  if(document.form1.elements['clientV'].type != "hidden") {
    if(clientV !== '') {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print LEAVE_CHAMPS_EMPTY;?>'n";
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
<?php 
 
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<?php if($affiliateVisible=="oui" AND $activeEcom=="oui") {?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
			
<?php 
// header 1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

// header 2
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
?>

      <?php if($tableDisplay=='oui') {?>
      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
      <tr height="32">
      <?php if($tableDisplayLeft=='oui') {?>
      <td>
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php"><?php print maj(HOME);?></a> | <?php print maj(AFFILIATION);?> |</b>
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
</table>

      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // -----------
		  // links kolom
		  // -----------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                      <td class="titre"><?php print maj(AFFILIATION);?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
<?php
}
?>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td>
  
  <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td class="TABLESousMenuPageCategory">
  <img src="im/fleche_right.gif">&nbsp;<b><?php print VOUS_ETES_AFFILIE;?></b>
  </td></tr></table>
  
  <br>
  <?php
      print '<form action="affiliate_details.php" method="POST">';
      print VOTRE_NUMERO_AFFILIE_EST.'&nbsp;&nbsp;<input type="text" class="vullen" name="aff_account" size="10" value="">';
      print "&nbsp;&nbsp;&nbsp;&nbsp;";
      print VOTRE_PASS_EST.'&nbsp;&nbsp;<input type="text" class="vullen" name="aff_pass" size="10" value="">';
      print "&nbsp;&nbsp;";
      print '<INPUT TYPE="submit" VALUE="ok">';
      print '</form>';
  ?>


  </td>
  </tr>
    <td>
    <br>
      <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td class="TABLESousMenuPageCategory">
      <img src="im/fleche_right.gif">&nbsp;<b><?php print VOUS_ETES_PAS_AFFILIE;?></b></td></tr></table>
      <br>
<?php 
print NOUS_REMUNERONS;
?>



<table border="0" cellpadding="0" cellspacing="0" width="100%"> 
<tr>
<td align="center">


<p align='center'><br><?php print POUR_ADHERER;?></p>
<?php
if(isset($messageAff)) { print "<p align='center' class='styleAlert'>".$messageAff."<br></p>";}
?>


<form name="form1" action="<?php print $url_id10;?>" method="post" onsubmit="return check_formt()"> 
<table border="0" cellpadding="2" cellspacing="0">
  <tr>
    <td align="left">Bedrijf</td><td><input name="societe" class="vullen" type="text" size="30"></td></tr> 
  <tr>
    <td align="left"><?php print NOM;?> </td><td><input class="vullen" name="aff_nom" type="text" size="30">&nbsp;*</td></tr>
  <tr>
    <td align="left"><?php print PRENOM;?> </td><td><input class="vullen" name="aff_prenom" type="text" size="30">&nbsp;*</td></tr>
  <tr>
    <td align="left">E-mail</td><td><input name="aff_email" class="vullen" type="text" size="30">&nbsp;*</td></tr>
  <tr>
    <td align="left" valign="top"><?php print ADRESSE;?> </td><td><input class="vullen" name="aff_adresse1" type="text" size="30">&nbsp;*</td></tr>
  <tr>
    <td align="left" valign="top"><?php print ADRESSE;?> </td><td><input class="vullen" name="aff_adresse2" type="text" size="30"></td></tr>
  <tr>
    <td align="left" valign="top"><?php print CODE_POSTAL;?> </td><td><input class="vullen" name="aff_codepostal" type="text" size="6">&nbsp;*</td></tr>
  <tr>
    <td align="left" valign="top"><?php print VILLE;?> </td><td><input name="aff_ville" class="vullen" type="text" size="30">&nbsp;*</td></tr>
  <tr>
    <td align="left" valign="top"><?php print PAYS;?> </td><td><input name="aff_pays" class="vullen" type="text" size="20">&nbsp;*</td></tr>
  <tr>
    <td align="left" valign="top"><?php print NUMERO_DE_TELEPHONE;?> </td><td><input class="vullen" name="aff_telephone" type="text" size="20"></td></tr>
  <tr>
    <td align="left" valign="top">Website</td><td><input name="aff_web" type="text" class="vullen" value="http://www." size="30">&nbsp;*</td></tr>
  <tr>
    <td align="center" colspan="2" height="25" valign="top"><b><?php print VOUS_RECEVREZ_VOTRE_ARGENT;?></b></td></tr>

    <td align="left" valign="top"></td><td><input name="aff_cheque" type="hidden" size="30"></td></tr>
 
  <tr>
      <td align="left" valign="top"><?php print PAYPAL_EMAIL;?></td><td><input class="vullen" name="aff_paypal" type="text" size="30"></td></tr>
   
    <td align="left" colspan="2" valign="middle">&bull;&nbsp;<?php print VIREMENT_BANCAIRE;?></td></tr>
  <tr>
    <td align="left" valign="top"><?php print BANQUE;?> </td><td><input class="vullen" name="aff_banque" type="text" size="30"></td></tr>
  <tr>
    <td align="left" valign="top">Rekening nummer </td><td><input class="vullen" name="aff_adressebanque" type="text" size="30"></td></tr>
  <tr>
    <td align="left" valign="top">IBAN</td><td><input class="vullen" name="aff_titulaire" type="text" size="30"></td></tr>
  <tr>
    <td align="left" valign="top">BIC</td><td><input name="aff_RIB_IBAN" class="vullen" type="text" size="30"></td></tr>
  <tr>
    <td align="left" colspan="2" height="10" valign="top">&nbsp;</td></tr>
  <tr>
    <td align="right" valign="top"><?php print AI_LU2;?> </td><td>
<?php
        print "<select name='aff_toto'>
        <option value='no' selected>".NON."</option>
        <option value='yes'>".OUI."</option>
        </select>";
?>
    </td></tr>
<tr>
    <td align="left" valign="top"><?php print COMMENTAIRES;?> </td><td><textarea name="aff_observations" rows="4" cols="50"></textarea></td>
</tr>
</table>

<!--
<p align="center">
<img src="im/noSpam.png"><br>
<img src="im/lang<?php print $_SESSION['lang'];?>/laisser.gif" align="absmiddle">&nbsp;:&nbsp;
-->

<input type="hidden" name="clientV" size="5" value="">
</p>

<p align="center">
<input type="hidden" name="affiliate_form" value="yes">
<input type="submit" value="<?php print ENVOYER;?>" name="submit">
</p>
</form>

</td>
</tr>
</table>
    </td>
  </tr>
</table>

<?php
if(isset($messageAff)) { print "<p align='center' class='styleAlert'>".$messageAff."<br><br></p>";}
?>

                  </td>
              </tr>
            </table>

          </td>

         <?php 
		  // ---------------------------------------
		  // kolom rechts 
		  // ---------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>

        </tr>
      </table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr>
</table>

<?php }?>
</body>
</html>
