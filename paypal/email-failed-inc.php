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
       
       $to = $row['users_email'];
       $users_products = $row['users_products'];
             
            $adress2 = explode("|",$row['users_facture_adresse']);
            $adreesName = $adress2[0];
       
 
        
        $date = date("Y-M-d H:i:s");
$_store = str_replace("&#146;","'",$store_company);
$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
        if(!empty($address_autre)) {
            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
            $message .= $address_autre2."\r\n";
        }
        if(!empty($tel)) $message .= "Téléphone: ".$tel."\r\n";
        if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
        $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
        $message .= $address_city." le ".$date."\r\n\r\n";
        $message .= $adreesName.",\r\n";
        $message .= "Your payment has not been processed correctly.\r\n";
        $message .= "We invite you to go back to http://".$www2.$domaineFull." and try again.\r\n";
        $message .= "For more information, contact us to ".$mailOrder."\r\n";
        $message .= "--------------------------------------------------------------------------------------------------\r\n";
        $message .= "Paypal email: $payer_email";
        $message .= "Transaction Id: ".$_POST['txn_id']."";    
        $message .= "--------------------------------------------------------------------------------------------------\r\n";
        $message .= $_store."\r\n";
        $message .= "The accounting dpt.";

         $subject = "[PAIEMENT] - PAYPAL statut: ".$_POST['payment_status']."";
         $from = $mailOrder;

         mail($to, $subject, rep_slash($message),
         "Return-Path: $from\r\n"
         ."From: $from\r\n"
         ."Reply-To: $from\r\n"
         ."X-Mailer: PHP/" . phpversion());
?>
