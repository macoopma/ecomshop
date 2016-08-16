<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

$menu_top2 = "Offerte vragen";
$title = $menu_top2;


if(isset($_POST['action']) AND $_POST['action']=="send") {
        $state2 = 0;

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

        if(isset($_POST['clientTVA']) AND $_POST['clientTVA']!=='') {
 
        $removeFromTva = array(" ", "-", ".", ",");
        $_POST['clientTVA'] = str_replace($removeFromTva, "", $_POST['clientTVA']);
             if(!in_array(trim(strtoupper(substr($_POST['clientTVA'], 0, 2))), $intracom_array) OR strlen($_POST['clientTVA'])<10 OR strlen($_POST['clientTVA'])>14) {
                $state2 = 1;
                $messageTVA = NO_TVA." ".NON_VALIDE;
                $stateComment = "<p align='center' class='styleAlert'>";
                $stateComment.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".$messageTVA;
                $stateComment.= "</p>";
             }
             else {
                $state2 = 0;
                $stateComment = "";
            }
          }
          else {
            $state2 = 0;
          }
 
        if(isset($_POST['devisV']) AND $_POST['devisV']=="") {
            $state3 = 0;
        }
        else {
            $state3 = 1;
            $stateComment3 = "<p align='center' class='styleAlert'>";
            $stateComment3.= "<img src='im/note.gif' align='absmiddle'>&nbsp;".CHAMPS_NON_VALIDE." ".LEAVE_CHAMPS_EMPTY;
            $stateComment3.= "</p>";
        }
if(isset($state2) AND $state2==0 AND isset($state3) AND $state3==0) {
 
        $str1a = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789123456789';
        $str1b = '123456789';
		$devisNumber1 = '';
		$devisNumber2 = '';
    	for( $i = 0; $i < 5 ; $i++ ) {
        	$devisNumber1 .= substr($str1a, rand(0, strlen($str1a) - 1), 1);
        }
    	for( $i = 0; $i < 3 ; $i++ ) {
        	$devisNumber2 .= substr($str1b, rand(0, strlen($str1b) - 1), 1);
        }
        $devisNumber = $devisNumber1."-".$devisNumber2;
        
 
        $dateAdded = date("Y-m-d H:i:s");
  
        $dateEnd = strftime("%Y-%m-%d %H:%M:%S",mktime(0,0,0,date("m"),date("d")+$devisValid,date("Y")));
   
        $products = $_SESSION['list']; 
    
        if(isset($_SESSION['account'])) {
            $client=$_SESSION['account'];
        }
        else {
            $client="";
        }
        
mysql_query("INSERT INTO devis (
                devis_number,
                devis_firstname,
                devis_lastname,
                devis_company,
                devis_email,
                devis_address,
                devis_cp,
                devis_city,
                devis_country,
                devis_tel,
                devis_fax,
                devis_activity,
                devis_comment,
                devis_date_added,
                devis_sent,
                devis_products,
                devis_client,
                devis_tva,
                devis_remise_commande,
                devis_remise_coupon
              )
            VALUES ('".$devisNumber."',
                    '".replace_ap($_POST['nom'])."',
                    '".replace_ap($_POST['prenom'])."',
                    '".replace_ap($_POST['entreprise'])."',
                    '".$_POST['email']."',
                    '".replace_ap($_POST['adresse'])."',
                    '".replace_ap($_POST['cp'])."',
                    '".replace_ap($_POST['ville'])."',
                    '".replace_ap($_POST['pays'])."',
                    '".$_POST['tel']."',
                    '".$_POST['fax']."',
                    '".replace_ap($_POST['activite'])."',
                    '".replace_ap($_POST['commentaire'])."',
                    '".$dateAdded."',
                    'no',
                    '".$products."',
                    '".$client."',
                    '".$_POST['clientTVA']."',
                    '".$_POST['remise_commande']."',
                    '".$_POST['remise_coupon']."'
                    )");


        $to = $_POST['email'];
        $subject = DEVIS." ".$devisNumber." - ".$domaineFull;
        $from = $mailInfo;
        $_store = str_replace("&#146;","'",$store_company);
        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
        $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nE-mail: ".$mailInfo."\r\n\r\n";
        $messageToSend .= $_POST['prenom'].",\r\n\r\n";
        $messageToSend .= ENREGISTRER_AU_DEVIS." http://".$www2.$domaineFull.".\r\n";
        $messageToSend .= VOTRE_DEMANDE_EMAIL."\r\n";
		$messageToSend .= POUR_TOUTE_INFORMATION.".\r\n";
		$messageToSend .= LE_SERVICE_CLIENT.".\r\n";
		$messageToSend .= $mailOrder;
        
	   mail($to, $subject, rep_slash($messageToSend),
       "Return-Path: $from\r\n"
       ."From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());

            // e-mail naar de administrator
             $to2 = $mailInfo;
             $from2 = $mailInfo;
             $subject2 = DEMANDE_DEVIS." nummer ".$devisNumber;
            $message2 = "Datum: ".date("d-m-Y H:i:s")."\r\n";
            $message2 .= "-------------------------------------------------\r\n";
            $message2 .= COMPAGNIE22." : ".$_POST['nom']." ".$_POST['prenom']."\r\n";
            $message2 .= COMPAGNIE2." : ".$_POST['entreprise']."\r\n";
            $message2 .= "E-mail: ".$_POST['email']."\r\n";
            $message2 .= "-------------------------------------------------\r\n";
            $message2 .= NUMERO_DEVIS." : ".$devisNumber."\r\n";
            $message2 .= "http://".$www2.$domaineFull."/admin/index.php\r\n";
    
             mail($to2, $subject2, rep_slash($message2),
             "Return-Path: $from2\r\n"
             ."From: $from2\r\n"
             ."Reply-To: $from2\r\n"
             ."X-Mailer: PHP/" . phpversion());

		$messageAff = "<table border='0' cellspacing='0' cellpadding='0' align='center'><tr><td style='font-weight:bold'>".MOT_DE_PASSE_ENVOYE." ".$to."<br>".VOTRE_DEMANDE."</tr></td></table>";
}
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php include('includes/geen_script.php');?>

<script type="text/javascript"><!--
function check_form10() {
  var error = 0;
  var error_message = "";

  var nom = document.form10.nom.value;
  var email = document.form10.email.value;
  var adresse = document.form10.adresse.value;
  var cp = document.form10.cp.value;
  var ville = document.form10.ville.value;
  var tel = document.form10.tel.value;
  var devisV = document.form10.devisV.value;
  var clientTVA = document.form10.clientTVA.value;
      
  if(document.form10.elements['nom'].type != "hidden") {
    if(nom == '' || nom.length < 2) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error = 1;
    }
  }

  if(document.form10.elements['email'].type != "hidden") {
    if(email == '' || email.length < 6 || email.indexOf ('@') == -1 || email.indexOf ('.') == -1 ) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> E-mail\n";
      error = 1;
    }
  }
  if(document.form10.elements['adresse'].type != "hidden") {
    if(adresse == '' || adresse.length < 5) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE;?>\n";
      error = 1;
    }
  }
  if(document.form10.elements['cp'].type != "hidden") {
    if(cp == '' || cp.length < 4) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print CODE_POSTAL;?>\n";
      error = 1;
    }
  }
  if(document.form10.elements['ville'].type != "hidden") {
    if(ville == '' || ville.length < 3) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print VILLE;?>\n";
      error = 1;
    }
  }
  if(document.form10.elements['tel'].type != "hidden") {
    if(tel == '' || tel.length < 6) {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print TELEPHONE;?>\n";
      error = 1;
    }
  }
  if(document.form10.elements['devisV'].type != "hidden") {
    if(devisV !== '') {
      error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print LEAVE_CHAMPS_EMPTY;?>\n";
      error = 1;
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
  if(document.form10.elements['clientTVA'].type != "hidden") {
    if(clientTVA !== '') {
    clientTVA = clientTVA.replace(' ','');
    clientTVA = clientTVA.replace('-','');
      var intracom_array = new Array ('AT','BE','DK','FI','FR','DE','EL','IE','IT','LU','NL','PT','ES','SE','GB','CY','EE','HU','LV','LT','MT','PL','SK','CZ','SI','BG','RO');
        var chaine = clientTVA.toUpperCase();
        var chaine = chaine.substring(0,2);        
        if(!in_array(chaine, intracom_array) || clientTVA.length < 10 || clientTVA.length > 14) {
            error_message = error_message + "<?php print CHAMPS_NON_VALIDE;?> <?php print NO_TVA;?>\n";
            error = 1;
        }
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

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 

if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

 
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

 
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
$styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
 
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
                      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php?" ><?php print maj(HOME);?></a> | <?php print $menu_top2;?> |</b>
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

    <table width="100%" height="100%" border="0" cellpadding="3" cellspacing="5">
    <tr>
          <?php
		  // -----------
		  // kolom links 
		  // -----------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
    <td valign="top" class="TABLEPageCentreProducts">
                  <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100%">
                  <tr>
                  <td valign="top">
<?php
if($addNavCenterPage=="oui") {
?>
                          <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                          <tr>
                          <td>
					   		<?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".$menu_top2." |";?>
						         </td>
                          </tr>
                          </table>
<?php
}

if(isset($stateComment) AND $stateComment!=="") print $stateComment;
if(isset($stateComment3) AND $stateComment3!=="") print $stateComment3;
?>
                  <form action="offerte.php" method="POST" name="form10" onsubmit="return check_form10()">
                  <?php
                  include('includes/invul_offerte.php');
                  ?>
                  </form>
                  
                  </td>
                  </tr>
                  <tr>
                  <td colspan="2" valign="bottom">
                  <?php
                  print "<b>".$store_company."</b><br>";
                  print $address_street."<br>";
                  print $address_cp." - ".$address_city."<br>";
                  print $address_country;
                  if(!empty($address_autre)) print "<br>".$address_autre;
                  if(!empty($tel)) print "<br>".TELEPHONE." ".$tel;
                  if(!empty($fax)) print "<br>".FAX." ".$fax;
                  ?>
                  </td>
                  </tr>
                  </table>
     </td>
         <?php 
		  // ------------
		  // kolom rechts
		  // ------------
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
