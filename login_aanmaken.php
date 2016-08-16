<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
// include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = IDVOUS;
$resaisirEmail = str_replace("<br>", " ", RESAISIR_EMAIL);
$date = date("Y-m-d H:i:s");

if(isset($_POST['actionPro']) AND $_POST['actionPro']=="proOk") {

if(isset($_POST['clientEmailPro']) AND !empty($_POST['clientEmailPro']) AND 
isset($_POST['clientGenderPro']) AND !empty($_POST['clientGenderPro']) AND  
isset($_POST['clientCityPro']) AND !empty($_POST['clientCityPro']) AND 
isset($_POST['clientPostCodePro']) AND !empty($_POST['clientPostCodePro']) AND 
isset($_POST['clientPaysPro']) AND !empty($_POST['clientPaysPro']) AND  
isset($_POST['clientLastnamePro']) AND !empty($_POST['clientLastnamePro']) AND 
isset($_POST['clientFirstnamePro']) AND !empty($_POST['clientFirstnamePro']) AND
$_POST['clientEmailPro'] == $_POST['clientEmailPro2']) 
{
// Verificatie e-mail
$stateCommentPro="";
$statePro1=0;
$queryPro1 = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_email= '".$_POST['clientEmailPro']."' ");
$queryPro1Num = mysql_num_rows($queryPro1);
  if($queryPro1Num > 0) {
     $statePro1 = 1; 
     $stateCommentPro = "<div align='center' class='fontrouge'><b>".EMAIL_ENR."</b></div>";
  }
  else {
     $statePro1 = 0;
     $stateCommentPro = "";
  }

// Verificatie BTW
$statePro2=0;
$intracom_array = array (
            '0'=>'AT', 
            '1'=>'BE',  
            '2'=>'DK',   
            '3'=>'FI',    
            '4'=>'FR',    
            '5'=>'DE',    
            '6'=>'EL',    
            '7'=>'IE',    
            '8'=>'IT',     
            '9'=>'LU',     
            '10'=>'NL',     
            '11'=>'PT',     
            '12'=>'ES',     
            '13'=>'SE',     
            '14'=>'GB',     
            '15'=>'CY',     
            '16'=>'EE',     
            '17'=>'HU',     
            '18'=>'LV',     
            '19'=>'LT',     
            '20'=>'MT',     
            '21'=>'PL',     
            '22'=>'SK',     
            '23'=>'CZ',     
            '24'=>'SI',     
            '25'=>'BG',
            '26'=>'RO');   

if(isset($_POST['clientTVAPro']) AND $_POST['clientTVAPro']!=='') {
// klaar BTW
$removeFromTva = array(" ", "-", ".", ",");
$_POST['clientTVAPro'] = str_replace($removeFromTva, "", $_POST['clientTVAPro']);

$queryPro2 = mysql_query("SELECT users_pro_tva FROM users_pro WHERE users_pro_tva= '".$_POST['clientTVAPro']."' ");
$queryPro2Num = mysql_num_rows($queryPro2);
  if($queryPro2Num > 0) {
     $statePro2 = 1;
     $stateCommentPro.= "<p align='center' class='styleAlert'>";
     $stateCommentPro.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".TVA_ENR;
     $stateCommentPro.= "</p>";
  }
  else {
     if(!in_array(trim(strtoupper(substr($_POST['clientTVAPro'], 0, 2))), $intracom_array) OR strlen($_POST['clientTVAPro'])<10 OR strlen($_POST['clientTVAPro'])>14) {
        $messageTVA = NO_TVA." ".NON_VALIDE;
        $statePro2 = 1;
        $stateCommentPro.= "<p align='center' class='styleAlert'>";
        $stateCommentPro.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".$messageTVA;
        $stateCommentPro.= "</p>";
     }
     else {
        $statePro2=0;
        $stateCommentPro.= "";
    }
  }
}

$resultPro = $statePro1 + $statePro2;
if($resultPro > 0) {
    $stateCommentPro = $stateCommentPro;
}
else {


$datePro = date("Y-m-d H:i:s");

 
    $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$proPassword = '';
		$proPassword = generate_account();

      mysql_query("INSERT INTO users_pro
                   SET
                   users_pro_email = '".$_POST['clientEmailPro']."',
                   users_pro_gender = '".$_POST['clientGenderPro']."',
                   users_pro_company = '".replace_ap($_POST['clientCompPro'])."',
                   users_pro_address = '".replace_ap($_POST['clientStreetAddressPro'])."',
                   users_pro_city = '".replace_ap($_POST['clientCityPro'])."',
                   users_pro_postcode = '".$_POST['clientPostCodePro']."',
                   users_pro_country = '".replace_ap($_POST['clientPaysPro'])."',
                   users_pro_activity = '".replace_ap($_POST['clientFactActivitePro'])."',
                   users_pro_telephone = '".$_POST['clientTelephonePro']."',
                   users_pro_fax = '".$_POST['clientFaxPro']."',
                   users_pro_tva = '".$_POST['clientTVAPro']."',
                   users_pro_tva_confirm = '??',
                   users_pro_lastname = '".replace_ap($_POST['clientLastnamePro'])."',
                   users_pro_firstname = '".replace_ap($_POST['clientFirstnamePro'])."',
                   users_pro_poste = '".replace_ap($_POST['clientPostePro'])."',
                   users_pro_comment = '".replace_ap($_POST['clientCommentPro'])."',
                   users_pro_password = '".$proPassword."',
                   users_pro_active = 'yes',
                   users_pro_date_added = '".$datePro."'
                   ");

// Redirect b2c b2b
if(isset($_POST['redir']) AND $_POST['redir']=='payment') {
    $_SESSION['openAccount'] = "yes";
    $_SESSION['account'] = $proPassword;
    $_SESSION['reduc'] = 0;
    header("Location: payment.php");
}
}
}
else {
    $stateCommentProWrong = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".UN_OU_DES_CHAMPS_NE_SONT_PAS_VALIDE."</p>";
    if($_POST['clientEmailPro']!==$_POST['clientEmailPro2']) {
      $stateCommentProWrong = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VERIF_EMAIL." (<i>".$_POST['clientEmailPro']." | ".$_POST['clientEmailPro2']."</i>)</p>";
    }
    print $stateCommentProWrong;
}
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

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
                 <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print CREER_VOTRE_COMPTE;?> |
                 </b>
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
		  // ---------------------------------------
		  // kolom links 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">


            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top">


<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td align="left">
                         <?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".CREER_VOTRE_COMPTE." |";?>
                      </td>
                    </tr>
                  </table>
<br>
<?php
}
?>
  
<script language="javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var clientFirstnamePro = document.form101.clientFirstnamePro.value;
  var clientLastnamePro = document.form101.clientLastnamePro.value;
  var clientEmailPro = document.form101.clientEmailPro.value;
  var clientStreetAddressPro = document.form101.clientStreetAddressPro.value;
  var clientPostCodePro = document.form101.clientPostCodePro.value;
  var clientCityPro = document.form101.clientCityPro.value;
  var clientPaysPro = document.form101.clientPaysPro.value;
  var clientTelephonePro = document.form101.clientTelephonePro.value;
  var clientTVAPro = document.form101.clientTVAPro.value;
  var clientFactActivitePro = document.form101.clientFactActivitePro.value;
  var clientPostePro = document.form101.clientPostePro.value;
  var clientEmailPro2 = document.form101.clientEmailPro2.value;
  /*var clientV = document.form101.clientV.value;*/
  
  if(document.form101.elements['clientEmailPro'].type != "hidden") {
    if(clientEmailPro == '' || clientEmailPro.length < 6 || clientEmailPro.indexOf ('@') == -1 || clientEmailPro.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientEmailPro2'].type != "hidden") {
    if(clientEmailPro2 == '' || clientEmailPro2.length < 6 || clientEmailPro2.indexOf ('@') == -1 || clientEmailPro2.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print $resaisirEmail;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientEmailPro'].type != "hidden") {
    if(clientEmailPro !== clientEmailPro2)  {
      error_message11 = error_message11 + "<?php print VERIF_EMAIL;?>.\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientStreetAddressPro'].type != "hidden") {
    if(clientStreetAddressPro == '' || clientStreetAddressPro.length < 5) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientCityPro'].type != "hidden") {
    if(clientCityPro == '' || clientCityPro.length < 3) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print VILLE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientPostCodePro'].type != "hidden") {
    if(clientPostCodePro == '' || clientPostCodePro.length < 4) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print CODE_POSTAL;?>\n";
      error11 = 1;
    }
  }  
  if(document.form101.elements['clientPaysPro'].type != "hidden") {
    if(document.form101.clientPaysPro.value == 'no') {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> <?php print PAYS;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientFactActivitePro'].type != "hidden") {
    if(clientFactActivitePro == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ACTVITE;?>\n";
      error11 = 1;
    }
  }
  <?php if($vb2b == 'oui') {?>
  if(document.form101.elements['clientTelephonePro'].type != "hidden") {
    if(clientTelephonePro == '' || clientTelephonePro.length < 6) {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> Telefoon\n";
      error11 = 1;
    }
  }
  <?php }?>
  
  if(document.form101.elements['clientGenderPro'].type != "hidden") {
    if(document.form101.clientGenderPro[0].checked || document.form101.clientGenderPro[1].checked) {
    } else {
      error_message11 = error_message11 + "<?php print VEUILLEZ_SELECTIONNER;?> <?php print CIVILITE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientLastnamePro'].type != "hidden") {
    if(clientLastnamePro == '' || clientLastnamePro.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientFirstnamePro'].type != "hidden") {
    if(clientFirstnamePro == '' || clientFirstnamePro.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientPostePro'].type != "hidden") {
    if(clientPostePro == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print POSTE;?>\n";
      error11 = 1;
    }
  }

function in_array(string, array) {
for(i = 0; i < array.length; i++) {
    if(array[i] == string) {
        return true;
    }
}
return false;
}
  if(document.form101.elements['clientTVAPro'].type != "hidden") {
    if(clientTVAPro !== '') {
    clientTVAPro = clientTVAPro.replace(' ','');
    clientTVAPro = clientTVAPro.replace('-','');
      var intracom_array = new Array ('AT','BE','DK','FI','FR','DE','EL','IE','IT','LU','NL','PT','ES','SE','GB','CY','EE','HU','LV','LT','MT','PL','SK','CZ','SI','BG','RO');
        var chaine = clientTVAPro.toUpperCase();
        var chaine = chaine.substring(0,2);        
        if(!in_array(chaine, intracom_array) || clientTVAPro.length < 10 || clientTVAPro.length > 14) {
            error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NO_TVA;?>\n";
            error11 = 1;
        }
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

<?php
if(isset($_SESSION['devisNumero'])) $seuil = 0; else $seuil = $minimumOrder;
if(!isset($_SESSION['list']) OR $_SESSION['list'] == "" OR empty($_SESSION['list']) or $_SESSION['tot_art']=="0" OR $_SESSION['totalTTC'] < $seuil OR $paymentsDesactive=="oui") {
   print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='0'>
            <tr>
             <td align='center'>";
             if(isset($_SESSION['totalTTC']) and $_SESSION['totalTTC'] < $minimumOrder) {
                $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".COMMANDE_MINIMUM.": ".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</p>";
             }
             else {
                $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE."</p>";
             }
             if($paymentsDesactive=="oui")  $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".COMMANDER_NO."</p>";
	print $displayMessage;
  print "</td>
            </tr>
           </table>";
}
else {
if(isset($stateCommentPro)) print $stateCommentPro;
?>

<form action="<?php print $_SERVER['PHP_SELF'];?>" method="POST" name="form101" onsubmit="return formu()"; id='formH'>
<input type="hidden" name="actionPro" value="proOk">
<input type="hidden" name="clientPostePro" value="">
<input type="hidden" name="clientFactActivitePro" value="">
<input type="hidden" name="redir" value="payment">
<input type="hidden" name="clientCommentPro" value="">

  <table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
    <tr>
      <td valign="top">
      
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td>&nbsp;<?php print ADRESSE_EMAIL;?></td>
            <td>&nbsp;
              <input type="text" name="clientEmailPro" size="35" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
         <tr>
            <td>&nbsp;<?php print RESAISIR_EMAIL;?></td>
            <td>&nbsp;
              <input type="text" name="clientEmailPro2" size="35" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
        </table>

        
<?php
// -----
// Info
// -----
?>
      </td>
    </tr>
    <tr>
    <tr>
      <td>
        <b><?php print RECPERS;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
        


        <tr>
            <td>&nbsp;<?php print CIVILITE;?></td>
            <td>&nbsp;
              <input style="BACKGROUND:none; border:none" type="radio" name="clientGenderPro" value="M">
              &nbsp;<?php print M;?>&nbsp;&nbsp;
              <input style="BACKGROUND:none; border:none" type="radio" name="clientGenderPro" value="Mme">
              &nbsp;<?php print MME;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print NOM;?></td>
            <td>&nbsp;<input type="text" name="clientLastnamePro" size="30" value="">&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print PRENOM;?></td>
            <td>&nbsp;<input type="text" name="clientFirstnamePro" size="30" value="">&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>



          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?></td>
            <td>
			&nbsp;<input type="text" name="clientCompPro" size="30" value="">&nbsp;&nbsp;<span class="fontrouge"></span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print ADRESSE;?></td>
            <td>
              &nbsp;<input type="text" name="clientStreetAddressPro" size="30" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print VILLE;?></td>
			<td>
              &nbsp;<input type="text" name="clientCityPro" size="30" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print CODE_POSTAL;?></td>
			<td>
              &nbsp;<input type="text" name="clientPostCodePro" size="10" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
           <tr>
            <td>&nbsp;<?php print PAYS;?></td>
			<td>
<?php
// landen
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
              &nbsp;<select name="clientPaysPro">
                <option value="no">----</option>
                <?php
                while ($countries = mysql_fetch_array($pays)) {
                  if($countries['countries_name'] == $row['users_pro_country']) $a = "selected"; else $a="";
                  print "<option value='".$countries['countries_name']."' $a>".$countries['countries_name']."</option>";
                }
                ?>
              </select>&nbsp;&nbsp;<span class="fontrouge">*</span>
              <!--&nbsp;<input type="text" name="clientPaysPro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>-->
			</td>
          </tr>
          <tr><td><img src="im/zzz.gif" width="1" height="1"></td></tr>
        </table>
        
        
      </td>

    </tr>
    <tr>
      <td valign="top">
      
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td width="100">&nbsp;<?php print NUMERO_DE_TELEPHONE;?></td>
            <td align="left">&nbsp;
              <input type="text" name="clientTelephonePro" value="">
              &nbsp;&nbsp;<span class="fontrouge"><?php ($vb2c == "oui")? print "" : print "*";?></span>
            </td>
          </tr>
          <tr>
            <td width="100">&nbsp;<?php print FAX;?></td>
            <td align="left">&nbsp;
              <input type="text" name="clientFaxPro" value="">
            </td>
          </tr>
          <tr><td><img src="im/zzz.gif" width="1" height="1"></td></tr>
        </table>
        
      </td>
      </tr>
      
<?php
if($noTva == "oui") {
?>
    <tr>
      <td align="top"><br><b><?php print NO_TVA;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td>
            <?php
            print ($tvaManuelValidation=="oui")? NO_TVA.":" : NO_TVA."";
            ?>
            </td>
            <td align="left">&nbsp;
              <input type="text" name="clientTVAPro" size="25" value=""><br>
              &nbsp;(<?php print LAISSER_VIDE;?>)
            </td>
          </tr>
<?php
          if($tvaManuelValidation=="oui") print "<tr><td colspan='2' ".LA_DETAXE_DE_VOTRE_COMMANDE_EST_SOUMISE_A_LA_VERIFICATION."</td></tr>";
?>
        </table>
      </td>
    </tr>
<?php 
}
else {
    print '<input type="hidden" name="clientTVAPro" value="">';
}
?>
<!--
      </td>
    </tr>
   <tr>
      <td><br><b><?php print COMMENTAIRES;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
          <tr>
            <td align="center">
              <textarea name="clientCommentPro" rows="4" cols="50"></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
-->
  </table>
  <br>
  
<table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td align="center"><INPUT style="BACKGROUND:none; border:0px; padding:5px;" TYPE="image" src="im/lang<?php print $_SESSION['lang'];?>/open_acc.gif" VALUE="<?php print CONTINUER;?>"></td>
  </tr>
  <tr>
    <td align="right"><span class="fontrouge">*</span> <?php print CHAMPS_OBLIGATOIRES;?></td>
  </tr>
</table>

</form>

<?php
}
?>
               
                </td>
              </tr>
            </table>



          </td>

         <?php 
		  // --------------------------------------
		  // kolom rechts
		  // --------------------------------------
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
