<?php

include("../configuratie/configuratie.php");
 
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
       $query = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_POST['item_name']."'");
       $row = mysql_fetch_array($query);
       $num = mysql_num_rows($query);
       
       $to = $row['users_email'];
       $users_products = $row['users_products'];
       $users_comment = $row['users_comment'];
       $users_fact = $row['users_fact_num'];
       $users_lang = $row['users_lang'];
            // billing name
            $adress2 = explode("|",$row['users_facture_adresse']);
            $adreesName = $adress2[0];
       
 
        
         
        $date = date("Y-M-d H:i:s");
        $users_comment = "<br>".$item_name." | REFUNDED : PAYPAL on ".$date."<br>---<br>".$users_comment;
        mysql_query("UPDATE 
                     users_orders SET 
                     users_nic = 'TERUG-".$users_fact."',
                     users_payed='yes', 
                     users_confirm='yes',
                     pp_statut='Refunded'
                     users_comment = '".$users_comment."' 
                     WHERE users_nic='".$item_name."'");


		$_store = str_replace("&#146;","'",$store_company);
		$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
        if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $message .= $address_autre2."\r\n";
        }
        if(!empty($tel)) $message .= "Tel: ".$tel."\r\n";
        if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
        $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        if($users_lang==1) {
	        $message .= $address_city." le ".dateFr($date, $users_lang)."\r\n\r\n";
	        $message .= $adreesName.",\r\n";
	        $message .= "Paiement remboursé à : $payer_email.\r\n";
	        $message .= "Pour plus d'informations, contactez nous à ".$mailOrder."\r\n";
	        $message .= "--------------------------------------------------------------------------------------------------\r\n";
	        $message .= "Paypal email: $payer_email\r\n";
	        $message .= "Transaction Id: ".$_POST['txn_id']."\r\n";    
	        $message .= "--------------------------------------------------------------------------------------------------\r\n";
	        $message .= $_store."\r\n";
	        $message .= "Le service comptable.";
		}
		else {
	        $message .= $address_city." on ".$date."\r\n\r\n";
	        $message .= $adreesName.",\r\n";
	        $message .= "Your payment has been refunded to the following PAYPAL account email address: $payer_email.\r\n";
	        $message .= "For more information, contact us to ".$mailOrder."\r\n";
	        $message .= "--------------------------------------------------------------------------------------------------\r\n";
	        $message .= "Paypal email: $payer_email\r\n";
	        $message .= "Transaction Id: ".$_POST['txn_id']."\r\n";    
	        $message .= "--------------------------------------------------------------------------------------------------\r\n";
	        $message .= $_store."\r\n";
	        $message .= "The accounting dpt.";
        }

         $subject = "[PAIEMENT] - PAYPAL statut: ".$_POST['payment_status'];
         $from = $mailOrder;

         mail($to, $subject, rep_slash($message),
         "Return-Path: $from\r\n"
         ."From: $from\r\n"
         ."Reply-To: $from\r\n"
         ."X-Mailer: PHP/" . phpversion());
?>
