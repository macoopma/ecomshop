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
 
if($mbSecretWord=="") $mot_secret = ""; else $mot_secret = strtoupper(md5($mbSecretWord));
$my_md5 = strtoupper(md5($mbId.$_POST['transaction_id'].$mot_secret.$_POST['mb_amount'].$_POST['mb_currency'].$_POST['status']));


 

if($_POST['status'] == "2" AND $_POST['md5sig'] == $my_md5) {


            $queryZ = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_POST['transaction_id']."' AND users_total_to_pay = '".$_POST['amount']."'");
            $queryZNum = mysql_num_rows($queryZ);
            if($queryZNum > 0) {
 
            $hoy = date("Y-m-d H:i:s");
  
            mysql_query("UPDATE users_orders SET users_payed = 'yes', users_confirm = 'yes', pp_statut = 'Completed.<br>ID MoneyBookers transaction ".$_POST['mb_transaction_id'].".', users_date_payed = '".$hoy."', users_payment = 'mb' WHERE users_nic = '".$_POST['transaction_id']."'");
            
   
            $resultZ = mysql_fetch_array($queryZ);
            
    
            include('../admin/lang/lang'.$resultZ['users_lang'].'/detail.php');
            
     
            if(!empty($resultZ['users_devis'])) {
            mysql_query("UPDATE devis SET devis_traite = 'yes', devis_client='".$resultZ['users_password']."' WHERE devis_number = '".$resultZ['users_devis']."'");
            }
            
      
            include('../includes/factuur_nummer_maken.php');
            invoice_number_generator($resultZ['users_fact_num'], $resultZ['users_nic']);

       
            $adress2 = explode("|",$resultZ['users_facture_adresse']);
            $adreesName = $adress2[0];
            
        
            $split = explode(",",$resultZ['users_products']);
                             foreach ($split as $item) {
                             $check = explode("+",$item);
                             if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contrôle cheque cadeau dans la commande
                             $query = mysql_query("SELECT products_qt, products_id, products_sold
                                                   FROM products
                                                   WHERE products_id = '".$check[0]."'");
                             $row = mysql_fetch_array($query);
                                    if($check[1] !== 0) {
                                       $aaa = $row['products_qt']-$check[1];
                                       $bbb = $row['products_sold']+$check[1];
                                       $updateStock = mysql_query("UPDATE products
                                                                   SET
                                                                   products_qt = ".$aaa.",
                                                                   products_sold = ".$bbb."
                                                                   WHERE products_id = '".$row['products_id']."'");
                                    }
 
									if(isset($check[6]) AND !empty($check[6])) {
										$_opt = explode("|",$check[6]);
						         		$lastArray = $_opt[count($_opt)-1];
						         		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) {
						         			$out = array_pop($_opt);
						         			$addReq = implode(' | ', $_opt);
						         		}
										if(isset($addReq) AND $addReq!=='') {
						         			$queryOpt = mysql_query("SELECT products_options_stock_stock FROM products_options_stock 
											 							WHERE products_options_stock_prod_id = '".$check[0]."'
																		AND products_options_stock_prod_name = '".$addReq."'");
						         			if(mysql_num_rows($queryOpt)>0) {
												$queryOptResult = mysql_fetch_array($queryOpt);
						         				$stockOptUpdate = $queryOptResult['products_options_stock_stock'] - $check[1];
												mysql_query("UPDATE products_options_stock
							                    				SET
																products_options_stock_stock = ".$stockOptUpdate."
																WHERE products_options_stock_prod_id = '".$check[0]."' 
																AND products_options_stock_prod_name = '".$addReq."'
																");
											}
										}
						         	}
                             }

			 
			if($affiliateAuto=='oui') {
			 
			$queryZAff = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$resultZ['users_password']."'");
			$queryZAffNum = mysql_num_rows($queryZAff);
			
				if($queryZAffNum>0) {
					$resultZAff = mysql_fetch_array($queryZAff);
					if($resultZAff['users_pro_aff']=='no') {
						$affAccount = 'no';
						 
						$str1a = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789ABCDEF';
						$affiliateNumber = '';
						for( $i=0; $i<10; $i++ ) {
							$affiliateNumber.= substr($str1a, rand(0, strlen($str1a) - 1), 1);
						}
						 
						$str1a = '123456789';
						$affiliatePass = '';
						for( $i=0; $i<6; $i++ ) {
							$affiliatePass.= substr($str1a, rand(0, strlen($str1a) - 1), 1);
						}
						
						 
						mysql_query("INSERT INTO affiliation (
						              aff_number,
						              aff_company,
						              aff_nom,
						              aff_prenom,
						              aff_email,
						              aff_adresse1,
						              aff_zip,
						              aff_ville,
						              aff_pays,
						              aff_telephone,
						              aff_pass,
						              aff_com,
						              aff_customer
						              )
						            VALUES ('".$affiliateNumber."',
						                    '".$resultZAff['users_pro_company']."',
						                    '".$resultZAff['users_pro_lastname']."',
						                    '".$resultZAff['users_pro_firstname']."',
						                    '".$resultZAff['users_pro_email']."',
						                    '".$resultZAff['users_pro_address']."',
						                    '".$resultZAff['users_pro_postcode']."',
						                    '".$resultZAff['users_pro_city']."',
						                    '".$resultZAff['users_pro_country']."',
						                    '".$resultZAff['users_pro_telephone']."',
						                    '".$affiliatePass."',
						                    '".$affiliateCom."',
						                    '".$resultZ['users_password']."'
						                    )");
						 
						mysql_query("UPDATE users_pro SET users_pro_aff = 'yes' WHERE users_pro_password = '".$resultZ['users_password']."'");
					}
					else {
						$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'");
						$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
						$affiliateNumber = $resultZAffFindNumber['aff_number'];
						$affiliatePass = $resultZAffFindNumber['aff_pass'];
						$affAccount = 'yes';
					}
				}
			}
			 

            if($resultZ['users_lang']== "1") {
             
			$_store = str_replace("&#146;","'",$store_company);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                  $message .= $address_autre2."\r\n";
                              }
                              if(!empty($tel)) $message.="Téléphone: ".$tel."\r\n";
                              if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
                             $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                             $message .= $address_city." le ".dateFr($resultZ['users_date_added'],$resultZ['users_lang'])."\r\n\r\n";
                             $message .= $adreesName.",\r\n";
                             $message .= "Votre paiement par carte de crédit a été accepté.\r\n";
                             $message .= "Paiement reçu, merci.\r\n";
                             $message .= "Votre commande est en traitement pour expédition.\r\n";
                             $message .= "Veuillez vous rendre sur votre interface de suivi client pour suivre votre commande et recevoir votre facture.\r\n";
                             $message .= $_store." vous remercie de votre commande.\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             $message .= "NIC (Numéro Identification Commande): ".$resultZ['users_nic']."\r\n";
                             $message .= "URL interface de suivi client: ".$urlAdminClient."\r\n";
                             $message .= "Numéro client: ".$resultZ['users_password']."\r\n";
                             $message .= "Adresse email: ".$resultZ['users_email']."\r\n";
                             $message .= "Votre interface de suivi client est aussi accessible sur http://".$www2.$domaineFull." via \"Votre compte\" avec votre numéro client et adresse email ou via \"Suivi commande\" après avoir saisi votre Numéro ID Commande (NIC).\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
                             		$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'") or die (mysql_error());
									$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
									$affiliateCom2 = $resultZAffCom['aff_com'];
	                             $message .= "\r\n";
								 $message .= $_store." vous rémunére à hauteur de ".$affiliateCom2."% sur les ventes générées par les clients que vous nous envoyez.\r\n";
								 $message .= "Cette offre ne vous engage à rien.\r\n";
	                             $message .= "Il vous suffit d'envoyer un email à vos connaissances contenant le lien ci-dessous:\r\n";
	                             $message .= "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\r\n";
	                             $message .= "Vous pouvez suivre à tout moment l'évolution de vos gains via \"Votre compte\" sur http://".$www2.$domaineFull."\r\n";
	                             $message .= "Voici ci-dessous les informations de votre compte d'affiliation:\r\n";
	                             $message .= "Numéro de compte d'affiliation: ".$affiliateNumber."\r\n";
	                             $message .= "Mot de passe: ".$affiliatePass."\r\n";
	                             $message .= "\r\n";
                             }
                             $message .= "Pour toute information, n'hésitez pas à communiquer avec nous.\r\n";
                             $message .= "Cordialement\r\n";
                             $message .= "Le service comptabilité\r\n";
                             $message .= $mailOrder;

                             $subject = "[RECEPTION PAIEMENT] - NIC#:".$resultZ['users_nic'];
            }
            if($resultZ['users_lang']== "2") {
 
                            $_store = str_replace("&#146;","'",$store_company);
                            $message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                  $message .= $address_autre2."\r\n";
                              }
                              if(!empty($tel)) $message.="Phone: ".$tel."\r\n";
                              if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
                             $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                             $message .= $address_city." on ".$resultZ['users_date_added']."\r\n\r\n";
                             $message .= $adreesName.",\r\n";
                             $message .= "Your payment has been processed and received successfuly.\r\n";
                             $message .= "Your order is now in treatment for shipping.\r\n";
                             $message .= "Go to your custom interface URL to follow your order and to receive your invoice.\r\n";
                             $message .= $_store." thank you for your order.\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             $message .= "NIC (Order ID): ".$resultZ['users_nic']."\r\n";
                             $message .= "Customer interface URL: ".$urlAdminClient."\r\n";
                             $message .= "Client Number: ".$resultZ['users_password']."\r\n";
                             $message .= "Email address: ".$resultZ['users_email']."\r\n";
                             $message .= "Your customer interface is also available to http://".$www2.$domaineFull." by login into 'Your Account' with your Client Number and your email address.\r\n";                             
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
                             		$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'");
									$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
									$affiliateCom2 = $resultZAffCom['aff_com'];
	                             $message .= "\r\n";
								 $message .= $_store." pay you back ".$affiliateCom2."% on sales done by customers you have sent to us.\r\n";
								 $message .= "This offer does not engages you.\r\n";
	                             $message .= "You just have to send an email to your friends containing the link below:\r\n";
	                             $message .= "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\r\n";
	                             $message .= "At any time you can control your earnings via \"Your account\" on http://".$www2.$domaineFull."\r\n";
	                             $message .= "Below, here are your affiliation account informations:\r\n";
	                             $message .= "Affiliate Account Number: ".$affiliateNumber."\r\n";
	                             $message .= "Password: ".$affiliatePass."\r\n";
	                             $message .= "\r\n";
                             }
                             $message .= "For more information, do not hesitate to contact us.\r\n";
                             $message .= "The accounting dpt.";
                             $message .= $mailOrder;

                             $subject = "[PAYMENT RECEPTION] - NIC#:".$resultZ['users_nic'];
            }
            
            if($resultZ['users_lang']== "3") {            

                            $_store = str_replace("&#146;","'",$store_company);
                            $message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                  $message .= $address_autre2."\r\n";
                              }
                              if(!empty($tel)) $message.="Phone: ".$tel."\r\n";
                              if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
                             $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                             $message .= $address_city." on ".$resultZ['users_date_added']."\r\n\r\n";
                             $message .= $adreesName.",\r\n";
                             $message .= "Your payment has been processed and received successfuly.\r\n";
                             $message .= "Your order is now in treatment for shipping.\r\n";
                             $message .= "Go to your custom interface URL to follow your order and to receive your invoice.\r\n";
                             $message .= $_store." thank you for your order.\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             $message .= "NIC (Order ID): ".$resultZ['users_nic']."\r\n";
                             $message .= "Customer interface URL: ".$urlAdminClient."\r\n";
                             $message .= "Client Number: ".$resultZ['users_password']."\r\n";
                             $message .= "Email address: ".$resultZ['users_email']."\r\n";
                             $message .= "Your customer interface is also available to http://".$www2.$domaineFull." by login into 'Your Account' with your Client Number and your email address.\r\n";                             
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
                             		$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'");
									$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
									$affiliateCom2 = $resultZAffCom['aff_com'];
	                             $message .= "\r\n";
								 $message .= $_store." pay you back ".$affiliateCom2."% on sales done by customers you have sent to us.\r\n";
								 $message .= "This offer does not engages you.\r\n";
	                             $message .= "You just have to send an email to your friends containing the link below:\r\n";
	                             $message .= "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\r\n";
	                             $message .= "At any time you can control your earnings via \"Your account\" on http://".$www2.$domaineFull."\r\n";
	                             $message .= "Below, here are your affiliation account informations:\r\n";
	                             $message .= "Affiliate Account Number: ".$affiliateNumber."\r\n";
	                             $message .= "Password: ".$affiliatePass."\r\n";
	                             $message .= "\r\n";
                             }
                             $message .= "For more information, do not hesitate to contact us.\r\n";
                             $message .= "The accounting dpt.";
                             $message .= $mailOrder;

                             $subject = "[PAYMENT RECEPTION] - NIC#:".$resultZ['users_nic'];
            }


                             $to = $resultZ['users_email'];
                             $from = $mailOrder;
                             mail($to, $subject, rep_slash($message),
                             "Return-Path: $from\r\n"
                             ."From: $from\r\n"
                             ."Reply-To: $from\r\n"
                             ."X-Mailer: PHP/" . phpversion());
 
  
   
                            $to2 = $mailOrder;
                            $from2 = $mailOrder;
                            $subject2 = "[Nieuwe bestelling - betaald met MONEYBOOKERS] - NIC #:".$resultZ['users_nic'];
                            $message2 = "Nieuwe bestelling op http://".$www2.$domaineFull."\r\n";
                            $message2 .= "Datum: ".date("Y-M-d H:i:s")."\r\n";
                            $message2 .= "-------------------------------------------------\r\n";
                            $message2 .= "Naam, voornaam: ".$resultZ['users_firstname']." ".$resultZ['users_lastname']."\r\n";
                            $message2 .= "E-mail: ".$resultZ['users_email']."\r\n";
                            $message2 .= "Land: ".$resultZ['users_country']."\r\n";
                            $message2 .= "-------------------------------------------------\r\n";
                            $message2 .= "Betaalwijze: MONEYBOOKERS\r\n";
                            $message2 .= "-------------------------------------------------\r\n";
                            $message2 .= "Bedrag: ".$resultZ['users_total_to_pay']."\r\n";
                            $message2 .= "NIC: ".$resultZ['users_nic']."\r\n";
                            $message2 .= "Klant nummer: ".$resultZ['users_password']."\r\n";
                            $message2 .= "-------------------------------------------------\r\n";
                            $message2 .= "Bestelling bekijken: http://".$www2.$domaineFull."/admin/index.php\r\n";
      
                             mail($to2, $subject2, rep_slash($message2),
                             "Return-Path: $from2\r\n"
                             ."From: $from2\r\n"
                             ."Reply-To: $from2\r\n"
                             ."X-Mailer: PHP/" . phpversion());

 
                $arrGc = array_sum($gc);
                if($arrGc> 0) {
  
                        $dateNow = date("Y-m-d H:i:s");
                        
 
                        $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
                        
  
                        mysql_query("UPDATE gc 
                                    SET 
                                    gc_payed = 1,
                                    gc_start = '".$dateNow."',
                                    gc_end = '".$nextYear."'
                                    WHERE gc_nic = '".$resultZ['users_nic']."'");
                                    
   
                        $queryUpGc = mysql_query("SELECT *
                                                FROM gc
                                                WHERE gc_nic = '".$resultZ['users_nic']."'
                                                ");
                        $rowUpGc = mysql_fetch_array($queryUpGc);
                                    
    
                        $domMaj11 = strtoupper($domaineFull);
                        $to11 =  $resultZ['users_email'];
                        $from11 = $mailOrder;
                        $subject11 = "[".CHEQUE_CADEAU."] - ".$domMaj11;
                        
                        $_store = str_replace("&#146;","'",$store_company);
                        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                        if(!empty($address_autre)) {
                            $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                            $messageToSend .= $address_autre2."\r\n";
                        }
                        if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
                        if(!empty($fax)) $messageToSend .= "Fax: ".$fax."\r\n";
                        $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                        $messageToSend .= "Date: ".$hoy."\r\n\r\n";
                        $messageToSend .= SUJET.": ".CHEQUE_CADEAU."\r\n";
                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                        $messageToSend .= VOUS_AVEZ_ACHETEEEE." ".$_store."\r\n";
                        $messageToSend .= $_store." ".VOUS_REMERCIE."\r\n";
                        $messageToSend .= CI_DESSOUS."\r\n";
                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                        $messageToSend .= NUMERO_CHEQUE." : ".$rowUpGc['gc_number']."\r\n";
                        $messageToSend .= MONTANT_DE." : ".sprintf("%0.2f",$rowUpGc['gc_amount'])." ".$devise."s\r\n";
                        $messageToSend .= URL_DE." : http://".$www2.$domaineFull."/geschenkbon/index.php?l=".$resultZ['users_lang']."\r\n";
                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                        $messageToSend .= POUR_PLUS_DINFORMATIONS." ".$mailOrder.".\r\n";
                        $messageToSend .= LE_SERVICE_CLIENT;
                
                        mail($to11, $subject11, rep_slash($messageToSend),
                             "Return-Path: $from11\r\n"
                             ."From: $from11\r\n"
                             ."Reply-To: $from11\r\n"
                             ."X-Mailer: PHP/" . phpversion());
                }
 
                if($resultZ['users_affiliate']!=='') {
                   $queryAff = mysql_query("SELECT aff_nom, aff_prenom, aff_email, aff_number FROM affiliation WHERE aff_number = '".$resultZ['users_affiliate']."'");
                   if(mysql_num_rows($queryAff) > 0) {
                      if($resultZ['users_lang']== "1") {
                        DEFINE("A3001Z","COMMISSION AFFILIATION");
                        DEFINE("A3002Z","Vous êtes membre affilié sur");
                        DEFINE("A3003Z","Votre numéro d'affilié est");
                        DEFINE("A3004Z","Une commande vient d'être confirmé pour un montant de");
                        DEFINE("A3005Z","Votre commission");
                        DEFINE("A3006Z","Pour connaître l'état de votre compte, veuillez vous rendre sur");
                      }
                      else {
                        DEFINE("A3001Z","AFFILIATION COMMISSION");
                        DEFINE("A3002Z","You are an affiliate member at");
                        DEFINE("A3003Z","Your affiliate number is");
                        DEFINE("A3004Z","An order has been confirmed for an amount of");
                        DEFINE("A3005Z","Your commission");
                        DEFINE("A3006Z","To know your account statement, go to");
                      }
                     $rowAff = mysql_fetch_array($queryAff);
                     $_storeAff = str_replace("&#146;","'",$store_company);
                		
                     $toAff = $rowAff['aff_email'];
                		$subjectAff = "[".A3001Z."] - http://".$www2.$domaineFull;
                		$fromAff = $mailOrder;
                
                		$scssAff = $rowAff['aff_nom']." ".$rowAff['aff_prenom'].",\r\n\r\n";
                		$scssAff .= A3002Z." http://".$www2.$domaineFull.".\r\n";
                		$scssAff .= A3003Z." : ".$rowAff['aff_number']."\r\n";
                		$scssAff .= A3004Z." ".$resultZ['users_products_ht']." ".$symbolDevise." HT.\r\n";
                		$scssAff .= A3005Z." : ".$resultZ['users_affiliate_amount']." ".$symbolDevise.".\r\n";
                		$scssAff .= A3006Z." http://".$www2.$domaineFull."/affiliation.php.\r\n";
                		$scssAff .= "-----\r\n";
                     $scssAff .= $_storeAff."\r\n";
                     $scssAff .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder;
                      
          				mail($toAff, $subjectAff, rep_slash($scssAff),
          				"Return-Path: $fromAff\r\n"
                        ."From: $fromAff\r\n"
          				."Reply-To: $fromAff\r\n"
          				."X-Mailer: PHP/" . phpversion());
                   }
                }
}
}
else {
 
   if($_POST['status'] == "0" AND $_POST['merchant_id'] == $mbId) {
      mysql_query("UPDATE users_orders SET users_confirm = 'yes', pp_statut = '<b><u>En attente/Pending</u></b><br>ID MoneyBookers transaction ".$_POST['mb_transaction_id']."', users_date_payed = '".$hoy."', users_payment = 'mb' WHERE users_nic = '".$_POST['transaction_id']."'");
   }
  
   elseif($_POST['status'] == "-1" AND $_POST['merchant_id'] == $mbId) {
      mysql_query("UPDATE users_orders SET users_confirm = 'no', pp_statut = '<b><u>En attente et annulée/Pending and canceled</u></b><br>ID MoneyBookers transaction ".$_POST['mb_transaction_id']."', users_date_payed = '".$hoy."', users_payment = 'mb' WHERE users_nic = '".$_POST['transaction_id']."'");
   }
 
   elseif($_POST['status'] == "-2" AND $_POST['merchant_id'] == $mbId) {
      mysql_query("UPDATE users_orders SET users_confirm = 'no', pp_statut = '<b><u>Refusée par la banque/Refused from bank</u></b><br>ID MoneyBookers transaction ".$_POST['mb_transaction_id']."', users_date_payed = '".$hoy."', users_payment = 'mb' WHERE users_nic = '".$_POST['transaction_id']."'");
   }
   else {
      mysql_query("UPDATE users_orders SET users_confirm = 'no', pp_statut = '<b><u>Échouée/Failed</u></b><br>Status:".$_POST['status']."<br>ID MoneyBookers transaction ".$_POST['mb_transaction_id']."', users_date_payed = '".$hoy."', users_payment = 'mb' WHERE users_nic = '".$_POST['transaction_id']."'");
   }
}
?>
