<?php

$date = date("Y-m-d H:i:s");
 
$resaisirEmail = str_replace("<br>", " ", RESAISIR_EMAIL);

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

$stateCommentPro="";
$statePro1=0;
$queryPro1 = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_email= '".$_POST['clientEmailPro']."' ");
$queryPro1Num = mysql_num_rows($queryPro1);
  if($queryPro1Num > 0) {
     $statePro1 = 1; 
     $stateCommentPro = "<p align='center' class='styleAlert'>";
     $stateCommentPro.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".EMAIL_ENR." (<i>".$_POST['clientEmailPro']."</i>)";
     $stateCommentPro.= "</p>";
  }
  else {
     $statePro1 = 0;
     $stateCommentPro = "";
  }


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
 
$statePro3 = 0;
if(isset($_POST['clientV']) AND $_POST['clientV']=='') {
    $statePro3 = 0;
    $stateCommentPro .= "";
}
else {
    $statePro3 = 1;
    $stateCommentPro.= "<p align='center' class='styleAlert'>";
    $stateCommentPro.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".CHAMP_NOT_EMPY;
    $stateCommentPro.= "</p>";
}

$resultPro = $statePro1 + $statePro2 + $statePro3;
if($resultPro > 0) {
    print $stateCommentPro;
}
else {

 
$datePro = date("Y-m-d H:i:s");
 
    $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$proPassword = '';
    	for( $i = 0; $i < 7 ; $i++ ) {
        	$proPassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
        }

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
                   users_pro_news = '".$_POST['news']."',
                   users_pro_password = '".$proPassword."',
                   users_pro_active = 'yes',
                   users_pro_date_added = '".$datePro."'
                   ");

 
if(isset($_POST['news']) AND $_POST['news']=='yes') {
                      
                      $queryNewsletter = mysql_query("SELECT newsletter_id FROM newsletter WHERE newsletter_email='".$_POST['clientEmailPro']."'");
                      $queryNewsletterNum = mysql_num_rows($queryNewsletter);
                      if(mysql_num_rows($queryNewsletter) > 0) {
                         $message = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".EMAIL_DEJA_ENREGISTRE." (<i>".$_POST['clientEmailPro']."</i>)</p>";                       
                      }
                      else {
 
                      mysql_query("INSERT INTO newsletter
                                  (newsletter_email,
                                  newsletter_password,
                                  newsletter_langue,
                                  newsletter_active,
                                  newsletter_statut,
                                  newsletter_date_added,
                                  newsletter_nic
                                  )
                                  VALUES
                                  ('".$_POST['clientEmailPro']."',
                                  '',
                                  '".$_SESSION['lang']."',
                                  'yes',
                                  'active',
                                  '".$datePro."',
                                  '".$proPassword."'
                                  )");
                     }
}
 
      $scss = "[".ACTIVATION_COMPTE_PRO." ALERT]\r\n";
      $scss .= DATE.": ".date("Y-M-d H:i:s")."\r\n";
      $scss .= "ID #: ".$proPassword."\r\n";
      if($_POST['clientCommentPro']!=='') $scss.= MESSAGE_DE." ".replace_ap($_POST['clientFirstnamePro'])." ".replace_ap($_POST['clientLastnamePro'])."\r\n-----------------------------------\r\n".replace_ap($_POST['clientCommentPro'])."\r\n-----------------------------------";

      $toMe = $mailInfo;
      $subjectMe = "[".ACTIVATION_COMPTE_PRO." ALERT] - ID #:".$proPassword;
      $fromMe = $mailInfo;
       
      mail($toMe, $subjectMe, $scss,
      "Return-Path: $fromMe\r\n"
      ."From: $fromMe\r\n"
      ."Reply-To: $fromMe\r\n"
      ."X-Mailer: PHP/" . phpversion());

 
        $scss = "Datum: ".date("d-m-Y H:i:s")."\r\n\r\n";
        $scss .= $_POST['clientGenderPro'].". ".$_POST['clientLastnamePro']." ".$_POST['clientFirstnamePro'].",\r\n";
        $scss .= COMPTE_ACTIVE." http://".$www2.$domaineFull.".\r\n";
        $scss .= VOTRE_NUMERO_CLIENT_EST.": ".$proPassword."\r\n";
        $scss .= "E-mail: ".$_POST['clientEmailPro']."\r\n";
        $scss .= "---\r\n";
		$scss .= POUR_TOUTE_INFORMATION.".\r\n";
		$scss .= LE_SERVICE_CLIENT.".\r\n";
		$scss .= $mailOrder;
      
      $to = $_POST['clientEmailPro'];
      $subject = "[".ACTIVATION_COMPTE_PRO."] - http://".$www2.$domaineFull;
      $from = $mailInfo;
       
      mail($to, $subject, $scss,
      "Return-Path: $from\r\n"
      ."From: $from\r\n"
      ."Reply-To: $from\r\n"
      ."X-Mailer: PHP/" . phpversion());

if(isset($_POST['redir']) AND $_POST['redir']=='payment') {
    $_SESSION['openAccount'] = "yes";
    $_SESSION['account'] = $proPassword;
    $_SESSION['reduc'] = 0;
    header("Location : payment.php");
}

    $_SESSION['openAccount'] = "yes";
    $_SESSION['account'] = $proPassword;
    $_SESSION['reduc'] = 0;

if($vb2b == "oui") {
  print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
  print APRES_VERIFICATION;
  print "</p>";
}
else {
  print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
  print VOS_INFOS_ONT_ETE_ENVOYE." <b>".$to."</b>";
  print "<br><br><a href='mijn_account.php'><span style='color:#FFFFFF'>".CLIK_ICI."</span></a>";
  print "</p>";
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
  var clientV = document.form101.clientV.value;

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
  if(document.form101.elements['clientV'].type != "hidden") {
    if(clientV !== '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print LEAVE_CHAMPS_EMPTY;?>\n";
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
            error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NO_TVA;?>'.\n";
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

<form action="<?php print $_SERVER['PHP_SELF'];?>" method="POST" name="form101" onsubmit="return formu()"; id='formH'>
<input type="hidden" name="actionPro" value="proOk">
<input type="hidden" name="clientPostePro" value="">
<input type="hidden" name="clientFactActivitePro" value="">
<input type="hidden" name="clientCommentPro" value="">
<?php
if(isset($createAccountFrom) AND $createAccountFrom==1) {
   print '<input type="hidden" name="redir" value="payment">';
}
?>

  <table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
    <tr>
      <td valign="top">
      
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td>&nbsp;<?php print ADRESSE_EMAIL;?>&nbsp;</td>
            <td>&nbsp;
              <input type="text" name="clientEmailPro" size="35" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
         <tr>
            <td>&nbsp;<?php print RESAISIR_EMAIL;?>&nbsp;</td>
            <td>&nbsp;
              <input type="text" name="clientEmailPro2" size="35" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
        </table>

        
<?php

?>
      </td>
    </tr>
    <tr>
    <tr>
      <td>
        <b><?php print COO;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
        


        <tr>
            <td>&nbsp;<?php print CIVILITE;?>&nbsp;</td>
            <td>&nbsp;
              <input style="BACKGROUND:none; border:none" type="radio" name="clientGenderPro" value="M">
              &nbsp;<?php print M;?>&nbsp;&nbsp;
              <input style="BACKGROUND:none; border:none" type="radio" name="clientGenderPro" value="Mme">
              &nbsp;<?php print MME;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print NOM;?></td>
            <td>&nbsp;<input type="text" name="clientLastnamePro" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print PRENOM;?>&nbsp;</td>
            <td>&nbsp;<input type="text" name="clientFirstnamePro" size="20" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>



          <tr>
            <td>&nbsp;<?php print COMPAGNIE2;?>&nbsp;</td>
            <td>
			&nbsp;<input type="text" name="clientCompPro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge"></span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print ADRESSE;?>&nbsp;</td>
            <td>
              &nbsp;<input type="text" name="clientStreetAddressPro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print VILLE;?>&nbsp;</td>
			<td>
              &nbsp;<input type="text" name="clientCityPro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print CODE_POSTAL;?>&nbsp;</td>
			<td>
              &nbsp;<input type="text" name="clientPostCodePro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
           <tr>
            <td>&nbsp;<?php print PAYS;?>&nbsp;</td>
			<td>
<?php

$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
              &nbsp;<select name="clientPaysPro">
                <option value="no">----</option>
<?php
                while ($countries = mysql_fetch_array($pays)) {
                  print "<option value='".$countries['countries_name']."'>".$countries['countries_name']."</option>";
                }
?>
              </select>&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
        </table>
        
        
      </td>

    </tr>
    <tr>
      <td valign="top">
      
        <table border="0" width="400" cellspacing="0" cellpadding="3" class="TABLE1">
          <tr>
            <td width="100">&nbsp;<?php print NUMERO_DE_TELEPHONE;?>&nbsp;</td>
            <td align="left">&nbsp;
              <input type="text" name="clientTelephonePro" value="">
              &nbsp;&nbsp;<span class="fontrouge"><?php ($vb2c == "oui")? print "" : print "*";?></span>
            </td>
          </tr>
          <tr>
            <td width="100">&nbsp;<?php print FAX;?>&nbsp;</td>
            <td align="left">&nbsp;
              <input type="text" name="clientFaxPro" value="">
              &nbsp;</td>
          </tr>
        </table>
        
      </td>
      </tr>
      <tr>
      <td align="top">
<?php 
          if($noTva == "oui") {
?>
        <table border="0" width="100%" cellspacing="0" cellpadding="5" class="TABLE1">
         <tr>
            <td>
            <?php
            print ($tvaManuelValidation=="oui")? NO_TVA."<span style='font-size:9px; color:#FF0000'><sup><b>1</b></sup></span>" : NO_TVA."";
            ?>
            </td>
            <td align="left">&nbsp;
              <input type="text" name="clientTVAPro" size="25" value="">
              <div class="FontGris">&nbsp;(<?php print LAISSER_VIDE;?>)</div>
            </td>
          </tr>
          
          <?php
          if($tvaManuelValidation=="oui") print "<tr><td colspan='2' class='FontGris'>(1) ".LA_DETAXE_DE_VOTRE_COMMANDE_EST_SOUMISE_A_LA_VERIFICATION."</td></tr>";
          ?>
        </table>
<?php 
           }
           else {
                print '<input type="hidden" name="clientTVAPro" value="">';
           }
?>
      </td>
    </tr>

 

    <tr>
      <td> 
      	<br><b><?php print NEWSLETTER;?></b>
	  </td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="400" cellspacing="0" cellpadding="5" class="TABLE1">
          <tr>
            <td align="left">
            <?php print ABONNE_NEWS;?>&nbsp;
            <select name="news">
               <option value="yes"><?php print OUI;?></option>
               <option value="no"><?php print NON;?></option>
            </select>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    
<tr>
      <td></td>
    </tr>
    <tr>
      <td valign="top">
       
<input type="hidden" name="clientV" size="5" value="">
       
      </td>
    </tr>
    
  </table>
<table border="0" width="400" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td align="center"><INPUT TYPE="submit" VALUE="<?php print ENVOYER;?>"></td>
  </tr>
  <tr>
    <td align="right"><span class="fontrouge">*</span> <?php print CHAMPS_OBLIGATOIRES;?></td>
  </tr>
</table>

</form>

