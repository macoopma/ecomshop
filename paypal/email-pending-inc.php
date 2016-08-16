<?php

include("../configuratie/configuratie.php");


function rep_slash($rem) {
	$rem = stripslashes($rem);
	$rem = str_replace("&#146;","'",$rem);
	return $rem;
}

 
		$query = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_POST['item_name']."'");
		$row = mysql_fetch_array($query);
		$num = mysql_num_rows($query);
	      $payLang = $row['users_lang'];

 

		$to = $row['users_email'];
		$users_products = $row['users_products'];
		$dateAdded = $row['users_date_added'];
		$devis = $row['users_devis'];
		$clientNumber = $row['users_password'];
		$factNumber = $row['users_fact_num'];
    	 
        $adress2 = explode("|",$row['users_facture_adresse']);
        $adreesName = $adress2[0];
       
 
if($num == 1 and $row['pp_statut'] == '') {
  
        mysql_query("UPDATE users_orders SET users_payed='no', users_confirm='no', pp_statut='".$_POST['payment_status']."', trans_id='".$_POST['txn_id']."', users_payment = 'pp' WHERE users_nic='".$_POST['item_name']."'");
   
        if(!empty($devis)) {
        	mysql_query("UPDATE devis SET devis_traite = 'yes', devis_client='".$clientNumber."' WHERE devis_number = '".$devis."'");
        }
    
        include('../includes/factuur_nummer_maken.php');
        invoice_number_generator($factNumber, $_POST['item_name']);
     
        $date = date("d-m-Y H:i:s");


		if($payLang == "1") {
		$_store = str_replace("&#146;","'",$store_company);
		$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
            if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $message .= $address_autre2."\r\n";
        }
        if(!empty($tel)) $message .= "Tel: $tel\r\n";
        if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
        $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        $message .= $address_city." - ".$date."\r\n\r\n";
        $message .= $adreesName.",\r\n";
        $message .= "Uw PAYPAL betaling werd nog niet bevestigd door ons, dit wordt manueel gedaan.\r\n";
        $message .= "Eenmaal de bevestiging werd voltooid, wordt uw bestelling verder verwerkt.\r\n";
        $message .= "Werd de bevestiging eenmaal uitgevoerd, dan wordt er een e-mail verstuurd naar $payer_email om uw bestelling op te volgen.\r\n";
        $message .= "Voor verdere informatie kunt u ons steeds contacteren op ".$mailOrder."\r\n";
        $message .= "--------------------------------------------------------------------------------------------------\r\n";
        $message .= "Your PAYPAL Payment is not confirmed yet. It will be manualy verified.\r\n";
        $message .= "We are waiting for confirmation to follow your order.\r\n";
        $message .= "As soon as funds available, your payment will be confirmed and an email will be sent to $payer_email in order to follow up your order.\r\n";
        $message .= "For more information, contact us to ".$mailOrder."\r\n";
        $message .= "--------------------------------------------------------------------------------------------------\r\n";

        $message .= $_store."\r\n";
        $message .= "Het verkoopsteam.";

         $subject = "[PAIEMENT] - PAYPAL status: ".$_POST['payment_status']."";



            }

		if($payLang == "2") {
		$_store = str_replace("&#146;","'",$store_company);
		$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
      	if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $message .= $address_autre2."\r\n";
        }
        if(!empty($tel)) $message .= "Tel: $tel\r\n";
        if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
        $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        $message .= $address_city." - ".$date."\r\n\r\n";
        $message .= $adreesName.",\r\n";
        $message .= "Your PAYPAL Payment is not confirmed yet. It will be manualy verified.\r\n";
        $message .= "We are waiting for confirmation to follow your order.\r\n";
        $message .= "As soon as funds available, your payment will be confirmed and an email will be sent to $payer_email in order to follow up your order.\r\n";
        $message .= "For more information, contact us to ".$mailOrder."\r\n";
        $message .= "--------------------------------------------------------------------------------------------------\r\n";

        $message .= $_store."\r\n";
        $message .= "Het verkoopsteam.";

         $subject = "Your order is payd by PAYPAL - status: ".$_POST['payment_status']."";

            }

		if($payLang == "3") {


		$_store = str_replace("&#146;","'",$store_company);
		$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
            if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $message .= $address_autre2."\r\n";
        }
        if(!empty($tel)) $message .= "Tel: $tel\r\n";
        if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
        $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        $message .= $address_city." - ".$date."\r\n\r\n";
        $message .= $adreesName.",\r\n";
        $message .= "Uw PAYPAL betaling werd nog niet bevestigd door ons, dit wordt manueel gedaan.\r\n";
        $message .= "Eenmaal de bevestiging werd voltooid, wordt uw bestelling verder verwerkt.\r\n";
        $message .= "Werd de bevestiging eenmaal uitgevoerd, dan wordt er een e-mail verstuurd naar $payer_email om uw bestelling op te volgen.\r\n";
        $message .= "Voor verdere informatie kunt u ons steeds contacteren op ".$mailOrder."\r\n";
        $message .= "--------------------------------------------------------------------------------------------------\r\n";
        $message .= $_store."\r\n";
        $message .= "Het verkoopsteam.";

         $subject = "Uw bestelling werd betaald met PAYPAL -  status: ".$_POST['payment_status']."";
            }

         $from = $mailOrder;

         mail($to, $subject, rep_slash($message),
         "Return-Path: $from\r\n"
         ."From: $from\r\n"
         ."Reply-To: $from\r\n"
         ."X-Mailer: PHP/" . phpversion());
}
?>
