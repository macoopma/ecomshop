<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = RECUPERER_MOT_DE_PASSE;
$messagePass = "";
$myPass = "";

if(isset($_GET['action']) and $_GET['action'] == "clientNumber") {}

 
if(isset($_POST['retrieve_pass']) and !empty($_POST['retrieve_pass'])) {
  
   $testEmailRequest = mysql_query("SELECT users_pro_password, users_pro_firstname
   						               FROM users_pro
   						               WHERE users_pro_email = '".$_POST['retrieve_pass']."'
						                  ");
   if(mysql_num_rows($testEmailRequest)>0) {
      $clientPassResult = mysql_fetch_array($testEmailRequest);
      $clientPass = $clientPassResult['users_pro_password'];
   
   $recover = mysql_query("SELECT users_save_data_from_form, users_email,users_firstname, users_password, users_nic
       						   FROM users_orders
       						   WHERE  users_password = '".$clientPass."'
    						      AND users_nic NOT LIKE 'TERUG%'
    						      ");
   $RecoverNum = mysql_num_rows($recover);
   
   if($RecoverNum>0) {
   		while($pass = mysql_fetch_array($recover)) {
	   		$passFromFormRecover[] = $pass['users_save_data_from_form'];
	   		$clientNumberRecover[] = $pass['users_password'];
	   		$clientNicRecover[] = $pass['users_nic'];
	   		$passName = $pass['users_firstname'];
		}
	   		$passFromFormRecover = array_unique($passFromFormRecover);
	   		$clientNumberRecover = array_unique($clientNumberRecover);
	   		$clientNicRecover = array_unique($clientNicRecover);
 
		$NumFormRecover = count($passFromFormRecover);
			if($NumFormRecover>1) {
				 for($i=0; $i<$NumFormRecover; $i++) {
				 	if($passFromFormRecover[$i] == "no") {
				    	$myPassToto[] = "";
					}
					else {
				    	$myPassToto[] = $passFromFormRecover[$i];					
					}
				}
				$myPassFromForm = implode(" | ",$myPassToto);
				if($myPassFromForm==" | ") $myPassFromForm = "";
			}
			else {
				$myPassFromForm = $passFromFormRecover[0];
			}
 
		$NumNicRecover = count($clientNicRecover);
			if($NumNicRecover>1) {
				for($i=0; $i<$NumNicRecover; $i++) {
				    $myNicToto[] = $clientNicRecover[$i];
				}
				$myNic = implode(" | ",$myNicToto);
			}
			else {
				$myNic = $clientNicRecover[0];
			}

 
		$NumClientRecover = count($clientNumberRecover);
			if($NumClientRecover>1) {
				foreach($clientNumberRecover as $elem) {
				    $myClientToto[] = $elem;
				}
				$myClient = implode(" | ",$myClientToto);
			}
			else {
				$myClient = $clientNumberRecover[0];
			}
 
	   $to = $_POST['retrieve_pass'];
	   $subject = RECUPERER_MOT_DE_PASSE." - ".$domaineFull;
	   $from = $mailOrder;
        $_store = str_replace("&#146;","'",$store_company);
        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
	   $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
	   $messageToSend .= $passName.",\r\n\r\n";
	   $messageToSend .= NUMERO_DE_CLIENT.": ".$myClient."\r\n";
	   $messageToSend .= ADRESSE_EMAIL.": ".$_POST['retrieve_pass']."\r\n";
	   $messageToSend .= "NIC (".NICO."):  ".$myNic."\r\n";
	   (empty($myPassFromForm))? $messageToSend .= "\r\n" : $messageToSend .= "Wachtwoord formulier: ".$myPassFromForm."\r\n";
		$messageToSend .= "---\r\n";
	   $messageToSend .= LE_SERVICE_CLIENT;
	   
 
 
	   mail($to, $subject, rep_slash($messageToSend),
       "Return-Path: $from\r\n"
       ."From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());

		$messagePass = MOT_DE_PASSE_ENVOYE." ".$to;
   }
   else {
  
   $recover = mysql_query("SELECT users_pro_password, users_pro_firstname
   						      FROM users_pro
   						      WHERE  users_pro_email = '".$_POST['retrieve_pass']."'
						         ");
   $RecoverNum = mysql_num_rows($recover);
   if($RecoverNum>0) {
      while($pass = mysql_fetch_array($recover)) {
	   		$clientNumberRecover[] = $pass['users_pro_password'];
	   		$passName = $pass['users_pro_firstname'];
		}
	   		$clientNumberRecover = array_unique($clientNumberRecover);
 
			if(count($clientNumberRecover)>1) {
				foreach($clientNumberRecover as $elem) {
				    $myClientToto[] = $elem;
				}
				$myClient = implode(" | ",$myClientToto);
			}
			else {
				$myClient = $clientNumberRecover[0];
			}
 
	   $to = $_POST['retrieve_pass'];
	   $subject = RECUPERER_NO_CLIENT." - ".$domaineFull;
	   $from = $mailOrder;
        $_store = str_replace("&#146;","'",$store_company);
        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
	   $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
	   $messageToSend .= $passName.",\r\n\r\n";
	   $messageToSend .= NUMERO_DE_CLIENT." : ".$myClient."\r\n";
	   $messageToSend .= ADRESSE_EMAIL." : ".$_POST['retrieve_pass']."\r\n";
	   $messageToSend .= "---\r\n";
	   $messageToSend .= LE_SERVICE_CLIENT;
	   
 
	   mail($to, $subject, rep_slash($messageToSend),
       "Return-Path: $from\r\n"
       ."From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());

		$messagePass = MOT_DE_PASSE_ENVOYE." ".$to;			
			
	}
	else {
   	$messagePass = NO_MOT_DE_PASSE;
   }
   }
   }
   else {
   	$messagePass = NO_MOT_DE_PASSE;
   }
}
else {
    $messagePass = "";
}
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
  <table border="0" cellspacing="0" cellpadding="2" align="center">
    <tr>
      <form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
      <td>
      <p align="center"><b><?php print RECUPERER_MOT_DE_PASSE;?></b></p>
      <input type="text" size="30" name="retrieve_pass" value="<?php print ADRESSE_EMAIL;?>" onblur="if(this.value=='') {this.value='<?php print ADRESSE_EMAIL;?>'}" onFocus="if(this.value=='<?php print ADRESSE_EMAIL;?>') {this.value=''}">
      <input type="hidden" name="sent" value="oui">
      <input type="submit" value="   OK   ">
      </td>
      </form>
      </tr>
  </table>
  <p align="center" class="fontrouge"><b><?php print $messagePass;?></b></p>

</body>
</html>
