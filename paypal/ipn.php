<?php

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];
$pending_reason = $_POST['pending_reason'];
$txn_type = $_POST['txn_type'];

if(!$fp) {
    // ERROR - Send alert email to admin
    include("../configuratie/configuratie.php");
    $subjectP = "fsockopen() function error - Paypal";
    $messageP = "fsockopen() function error - Paypal";
    mail($mailPerso, $subjectP, $messageP,
    "Return-Path: $mailPerso\r\n"
    ."From: $mailPerso\r\n"
    ."Reply-To: $mailPerso\r\n"
    ."X-Mailer: PHP/" . phpversion());
} 
else {
fputs ($fp, $header . $req);
      while (!feof($fp)) {
      $res = fgets ($fp, 1024);
            if(strcmp ($res, "VERIFIED") == 0) {
                if($payment_status == "Completed") include("email-complete-inc.php");
                if($payment_status == "Pending") include("email-pending-inc.php");
                if($payment_status == "Failed") include("email-failed-inc.php");
                if($payment_status == "Denied") include("email-denied-inc.php");
                if($payment_status == "Refunded") include("email-refunded-inc.php");
            }
            else if(strcmp ($res, "INVALID") == 0) {
            // log for manual investigation
            }
      }
fclose ($fp);
}
?>
