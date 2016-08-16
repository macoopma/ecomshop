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

       $query = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_POST['item_name']."' AND users_total_to_pay = '".$payment_amount."'");
       $num = mysql_num_rows($query);
       
       $row = mysql_fetch_array($query);
       
       $to = $row['users_email'];
       $users_products = $row['users_products'];
       $dateAdded = $row['users_date_added'];
       $clientNumber = $row['users_password'];
       $payLang = $row['users_lang'];
       $nic = $row['users_nic'];
       $devis = $row['users_devis'];
       $factNumber = $row['users_fact_num'];
       $userAff = $row['users_affiliate'];
       $userProdHt= $row['users_products_ht'];
       $userAffAmount = $row['users_affiliate_amount'];
       $pp_stat = $row['pp_statut'];
       $pays = $row['users_country'];
       $prenom = $row['users_firstname'];
       $nom = $row['users_lastname'];

            
            $adress2 = explode("|",$row['users_facture_adresse']);
            $adreesName = $adress2[0];


if($num == 1 AND $pp_stat == '') {

 
        $hoy = date("Y-m-d H:i:s");
        
 
        mysql_query("UPDATE users_orders SET users_payed = 'yes', users_confirm = 'yes', pp_statut = '".$_POST['payment_status']."', trans_id = '".$_POST['txn_id']."', users_date_payed = '".$hoy."', users_payment = 'pp' WHERE users_nic = '".$_POST['item_name']."'");
        
 
        include('../admin/lang/lang'.$payLang.'/detail.php');
		
 
        if(!empty($devis)) {
            mysql_query("UPDATE devis SET devis_traite = 'yes', devis_client='".$clientNumber."' WHERE devis_number = '".$devis."'");
        }
        
 
        include('../includes/factuur_nummer_maken.php');
        invoice_number_generator($factNumber, $nic);
        
 
        $split = explode(",",$users_products);
        foreach ($split as $item) {
	        $check = explode("+",$item);
	        if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contrôle cheque cadeau dans la commande
	        $query2 = mysql_query("SELECT products_qt, products_id, products_sold
	                              FROM products
	                              WHERE products_id = '".$check[0]."'");
	        $row2 = mysql_fetch_array($query2);
	        if($check[1]!=="0") {
	           $stockUpdate = $row2['products_qt']-$check[1];
	           $productsSoldUpdate = $row2['products_sold']+$check[1];
	           mysql_query("UPDATE products 
	                          SET 
	                          products_qt = ".$stockUpdate.", 
	                          products_sold = ".$productsSoldUpdate." 
	                          WHERE products_id = '".$row2['products_id']."'");
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
 
			$queryZAff = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$clientNumber."'");
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
						                    '".$clientNumber."'
						                    )");
 
						mysql_query("UPDATE users_pro SET users_pro_aff = 'yes' WHERE users_pro_password = '".$clientNumber."'");
					}
					else {
						$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
						$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
						$affiliateNumber = $resultZAffFindNumber['aff_number'];
						$affiliatePass = $resultZAffFindNumber['aff_pass'];
						$affAccount = 'yes';
					}
				}
			}
 

  
		if($payLang == "1") {
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
			if(!empty($address_autre)) {
				$address_autre2 = str_replace("<br>","\r\n",$address_autre);
			    $message .= $address_autre2."\r\n";
			}
			if(!empty($tel)) $message.="Tel: $tel\r\n";
			if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
			$message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
			$message .= $address_city." le ".dateFr($dateAdded, $payLang)."\r\n\r\n";
			$message .= $adreesName.",\r\n";
			$message .= "Votre paiement par PAYPAL a été accepté.\r\n";
			$message .= "Paiement reçu, merci.\r\n";
			$message .= "Votre commande est en traitement pour expédition.\r\n";
			$message .= "Veuillez vous rendre sur votre interface de suivi client pour suivre votre commande et recevoir votre facture.\r\n";
			$message .= $_store." vous remercie de votre commande.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			$message .= "NIC (Numéro Identification Commande): ".$_POST['item_name']."\r\n";
			$message .= "URL interface de suivi client: ".$urlAdminClient."\r\n";
			$message .= "Numéro client: ".$clientNumber."\r\n";
			$message .= "Adresse email: ".$to."\r\n";
			$message .= "Votre interface de suivi client est aussi accessible sur http://".$www2.$domaineFull." dans 'Votre compte' après vous être identifié avec votre numéro client  et votre adresse email.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
				$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'") or die (mysql_error());
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
			$subject = "[RECEPTION PAIEMENT] - NIC#:".$_POST['item_name'];
         }
if($payLang == "2") {
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
			if(!empty($address_autre)) {
				$address_autre2 = str_replace("<br>","\r\n",$address_autre);
			    $message .= $address_autre2."\r\n";
			}
			if(!empty($tel)) $message.="Tel: $tel\r\n";
			if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
			$message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
			$message .= $address_city." on ".$dateAdded."\r\n\r\n";
			$message .= $adreesName.",\r\n";
			$message .= "Your payment through PAYPAL has been confirmed, Thank you.\r\n";
			$message .= "Your order is now treated for shipping.\r\n";
			$message .= "Go the your personal customer interface to follow the order and take your invoice.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			$message .= "PAYPAL EMAIL: ".$payer_email."\r\n";
			$message .= "Customer Interface URL: ".$urlAdminClient."\r\n";
			$message .= "NIC (Order ID): ".$_POST['item_name']."\r\n";
			$message .= "Client Number: ".$clientNumber."\r\n";
			$message .= "Email address: ".$to."\r\n";
			$message .= "Your customer interface is also available from http://".$www2.$domaineFull." by login into 'Your Account' with your Client Number and your email address.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
				$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
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
			$message .= "For more information, contact us.\r\n";
			$message .= "The accounting dpt.";
			$message .= $mailOrder;
			
			$subject = "[PAYMENT RECEIVED] - NIC#:".$_POST['item_name']."";
         }



if($payLang == "3") {
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."<font face=arial><br>".$address_street."<br>".$address_country." - ".$address_cp." - ".$address_city."<br><br>";
			if(!empty($address_autre)) {
				$address_autre2 = str_replace("<br>","<br>",$address_autre);
			    $message .= $address_autre2."<br>";
			}
                  if(!empty($tel)) $message.="Tel: ".$tel."<br>";
                  if(!empty($fax)) $message.="Fax: ".$fax."<br>";
			$message .= "URL: <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>E-mail: ".$mailOrder."<br>";
			$message .= $address_city.", op ".dateFr($dateAdded, $payLang)."<br>";
			$message .= $adreesName.",<br>";
			$message .= "Uw betaling via Paypal werd ontvangen, waarvoor dank.<br>";
			$message .= "Uw bestelling wordt nu verder behandeld voor verzending.<br>";
			$message .= "Ga naar uw klant interface om uw bestelling te bekijken en op te volgen. U vindt er eveneens uw factuur terug.<br>";
			$message .= "<br>";
			$message .= "Paypal e-mail adres: ".$payer_email."<br>";
			$message .= "Klant interface URL: <a href=".$urlAdminClient.">".$urlAdminClient."</a><br>";
			$message .= "NIC (bestel nummmer): ".$_POST['item_name']."<br>";
			$message .= "Klant nummer: ".$clientNumber."<br>";
			$message .= "E-mail adres: ".$to."<br>";
			$message .= "Uw klant interface is ook beschikbaar op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a> onder 'Uw account' en login met uw klant nummer en e-mail adres.<br>";
			$message .= "<br>";
			if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
				$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
				$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
				$affiliateCom2 = $resultZAffCom['aff_com'];
	                             $message .= "<br>";
								 $message .= $_store." geeft je ".$affiliateCom2." % commissie op al onze artikelen die klanten kopen via u.<br>";
								 $message .= "Dit geldt niet voor uw eigen aankopen.<br>";
	                             $message .= "Je hoeft enkel de onderstaande link te sturen naar uw vrienden en kennissen:<br>";
	                             $message .= "<a href=\"http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\">http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."</a><br>";
	                             $message .= "U kunt op elk ogenblik uw verdiensten zien via \"Uw account\" op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>";
	                             $message .= "Dit zijn uw login gegevens:<br>";
	                             $message .= "Affiliate nummer: ".$affiliateNumber."<br>";
	                             $message .= "Wachtwoord: ".$affiliatePass."<br>";
	                             $message .= "<br>";
                             }
                             $message .= "Indien u nog vragen heeft dan kunt u ons steeds contacteren...<br>";
                             $message .= "Het verkoopsteam.<br>";
                             $message .= $mailOrder;
			$subject = "Uw bestelling met nummer - NIC#:".$_POST['item_name'];
         }

        
         $from = $mailOrder;

         mail($to, $subject, rep_slash($message),
         "Return-Path: $from\r\n"
         ."From: $from\r\n"
         ."Reply-To: $from\r\n"
  ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
         ."X-Mailer: PHP/" . phpversion());
         
            //-----------------------
            // e-mail naar de administrator
            //-----------------------
                             $to2 = $mailOrder;
                             $from2 = $mailOrder;
                             $subject2 = "Er is een nieuwe bestelling - BETAALD MET PAYPAL - NIC:".$nic;
                            $message2 = "<font face=arial>Een nieuwe bestelling op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>";
                            $message2 .= "Datum: ".date("d-m-Y H:i:s")."<br>";
                            $message2 .= "<br>";
                            $message2 .= "Naam en voornaam: ".$nom." ".$prenom."<br>";
                            $message2 .= "E-mail: ".$to."<br>";
                            $message2 .= "Land: ".$pays."<br>";
                            $message2 .= "<br>";
                            $message2 .= "Betaalwijze: PAYPAL<br>";
                            $message2 .= "Bedrag: ".$payment_amount."<br>";
                            $message2 .= "NIC nummer: ".$nic."<br>";
                            $message2 .= "Klant nummer: ".$clientNumber."<br>";
                            $message2 .= "<br>";
                            $message2 .= "Bekijk deze bestelling: <a href=\"http://".$www2.$domaineFull."/admin\">Administrator</a>";

      
                             mail($to2, $subject2, rep_slash($message2),
                             "Return-Path: $from2\r\n"
                             ."From: $from2\r\n"
                             ."Reply-To: $from2\r\n"
  ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
                             ."X-Mailer: PHP/" . phpversion());

                //////////////
                // geschenkbon
                //////////////
                $arrGc = array_sum($gc);
                if($arrGc> 0) {
 
                        $dateNow = date("Y-m-d H:i:s");
                        
  
                        $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
                        
   
                        mysql_query("UPDATE gc 
                                    SET 
                                    gc_payed = 1,
                                    gc_start = '".$dateNow."',
                                    gc_end = '".$nextYear."'
                                    WHERE gc_nic = '".$nic."'");
                                    
    
                        $queryUpGc = mysql_query("SELECT * FROM gc WHERE gc_nic = '".$nic."'");
                        $rowUpGc = mysql_fetch_array($queryUpGc);
                                    
     
                        $domMaj11 = strtoupper($domaineFull);
                        $to11 =  $to;
                        $from11 = $mailOrder;
                        $subject11 = "[".CHEQUE_CADEAU."] - ".$domMaj11;
                        
                        $_store = str_replace("&#146;","'",$store_name);
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
                        $messageToSend .= URL_DE." : http://".$www2.$domaineFull."/geschenkbon/index.php?l=".$payLang."\r\n";
                        $messageToSend .= "----------------------------------------------------------------------------------------\r\n";
                        $messageToSend .= POUR_PLUS_DINFORMATIONS." ".$mailOrder.".\r\n";
                        $messageToSend .= LE_SERVICE_CLIENT;
                
                        mail($to11, $subject11, rep_slash($messageToSend),
                             "Return-Path: $from11\r\n"
                             ."From: $from11\r\n"
                             ."Reply-To: $from11\r\n"
  ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
                             ."X-Mailer: PHP/" . phpversion());
                }

                //-------------------
                // affliliate bericht
                //-------------------
                if($userAff !== '') {
                   $queryAff = mysql_query("SELECT aff_nom, aff_prenom, aff_email, aff_number FROM affiliation WHERE aff_number = '".$userAff."'");
                   if(mysql_num_rows($queryAff) > 0) {
                      if($payLang== "1") {
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
                     $_storeAff = str_replace("&#146;","'",$store_name);
                		
                     $toAff = $rowAff['aff_email'];
                		$subjectAff = "[".A3001Z."] - http://".$www2.$domaineFull;
                		$fromAff = $mailOrder;
                		$scssAff = $rowAff['aff_nom']." ".$rowAff['aff_prenom'].",\r\n\r\n";
                		$scssAff .= A3002Z." http://".$www2.$domaineFull.".\r\n";
                		$scssAff .= A3003Z." : ".$rowAff['aff_number']."\r\n";
                		$scssAff .= A3004Z." ".$userProdHt." ".$symbolDevise." HT.\r\n";
                		$scssAff .= A3005Z." : ".$userAffAmount." ".$symbolDevise.".\r\n";
                		$scssAff .= A3006Z." http://".$www2.$domaineFull."/affiliation.php.\r\n";
                		$scssAff .= "-----\r\n";
                     	$scssAff .= $_storeAff."\r\n";
                     	$scssAff .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder;
                      
          				mail($toAff, $subjectAff, rep_slash($scssAff),
          				"Return-Path: $fromAff\r\n"
                        ."From: $fromAff\r\n"
          				."Reply-To: $fromAff\r\n"
  ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
          				."X-Mailer: PHP/" . phpversion());
                   }
                }
}



/*-----------
PENIDNG
-----------*/

if($num == 1 AND $pp_stat == 'Pending') {
  
        mysql_query("UPDATE users_orders SET users_payed = 'yes', users_confirm = 'yes', pp_statut = '".$_POST['payment_status']."', trans_id = '".$_POST['txn_id']."', users_payment = 'pp' WHERE users_nic = '".$_POST['item_name']."'");
 
        if(!empty($devis)) {
        mysql_query("UPDATE devis SET devis_traite = 'yes', devis_client='".$clientNumber."' WHERE devis_number = '".$devis."'");
        }
   
        include('../includes/factuur_nummer_maken.php');
        invoice_number_generator($factNumber, $nic);
    
        $split = explode(",",$users_products);
        foreach ($split as $item) {
	        $check = explode("+",$item);
	        if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contrôle cheque cadeau dans la commande
	        $query2 = mysql_query("SELECT products_qt, products_id, products_sold
	                               FROM products
	                               WHERE products_id = '".$check[0]."'");
	        $row2 = mysql_fetch_array($query2);
	        if($check[1]!=="0") {
	 
	           $updateStock1 = $row2['products_qt']- $check[1];
	           $updateProductsSold = $row2['products_sold']+$check[1];
	           mysql_query("UPDATE products 
	                          SET 
	                          products_qt = ".$updateStock1.",
	                          products_sold = ".$updateProductsSold." 
	                          WHERE products_id = '".$row2['products_id']."'");
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
	 
			$queryZAff = mysql_query("SELECT * FROM users_pro WHERE users_pro_password = '".$clientNumber."'");
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
						                    '".$clientNumber."'
						                    )");
	 
						mysql_query("UPDATE users_pro SET users_pro_aff = 'yes' WHERE users_pro_password = '".$clientNumber."'");
					}
					else {
						$queryZAffFindNumber = mysql_query("SELECT aff_number, aff_pass  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
						$resultZAffFindNumber = mysql_fetch_array($queryZAffFindNumber);
						$affiliateNumber = $resultZAffFindNumber['aff_number'];
						$affiliatePass = $resultZAffFindNumber['aff_pass'];
						$affAccount = 'yes';
					}
				}
			}
	 

        
            if($payLang == "1") {
                $_store = str_replace("&#146;","'",$store_name); 
                $message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n"; 
                if(!empty($address_autre)) { 
                $address_autre2 = str_replace("<br>","\r\n",$address_autre); 
                $message .= $address_autre2."\r\n"; 
                } 
                if(!empty($tel)) $message.="Tel: $tel\r\n"; 
                if(!empty($fax)) $message.="Fax: ".$fax."\r\n"; 
                $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n"; 
                $message .= $address_city." le ".$dateAdded."\r\n\r\n"; 
                $message .= $adreesName.",\r\n"; 
                $message .= "Votre paiement par PAYPAL a été accepté.\r\n"; 
                $message .= "Paiement reçu, merci.\r\n"; 
                $message .= "Votre commande est en traitement pour expédition.\r\n"; 
                $message .= "Veuillez vous rendre sur votre interface de suivi client pour suivre votre commande et recevoir votre facture.\r\n"; 
                $message .= $_store." vous remercie de votre commande.\r\n"; 
                $message .= "--------------------------------------------------------------------------------------------------\r\n"; 
                $message .= "NIC (Numéro Identification Commande): ".$_POST['item_name']."\r\n"; 
                $message .= "URL interface de suivi client: ".$urlAdminClient."\r\n"; 
                $message .= "Numéro client: ".$clientNumber."\r\n"; 
                $message .= "Adresse email: ".$to."\r\n";
                $message .= "Votre interface de suivi client est aussi accessible sur http://".$www2.$domaineFull." dans 'Votre compte' après vous être identifié avec votre numéro client et votre adresse email.\r\n"; 
                $message .= "--------------------------------------------------------------------------------------------------\r\n";
				if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
					$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'") or die (mysql_error());
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
                
                $subject = "[RECEPTION PAIEMENT] - NIC#:".$_POST['item_name']; 
            }

            if($payLang == "2") {
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
          	if(!empty($address_autre)) {
            	$address_autre2 = str_replace("<br>","\r\n",$address_autre);
            	$message .= $address_autre2."\r\n";
          	}
          	if(!empty($tel)) $message.="Tel: $tel\r\n";
          	if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
			$message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
			$message .= $address_city." le ".$dateAdded."\r\n\r\n";
			$message .= $adreesName.",\r\n";
			$message .= "Your payment through PAYPAL has been confirmed, Thank you.\r\n"; 
			$message .= "Your order is now treated for shipping.\r\n";
			$message .= "Go the your personal customer interface to follow the order and take your invoice.\r\n"; 
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			$message .= "PAYPAL EMAIL: ".$payer_email."\r\n";
			$message .= "Customer Interface URL: ".$urlAdminClient."\r\n";
			$message .= "NIC (Order ID): ".$_POST['item_name']."\r\n";
			$message .= "Client Number: ".$clientNumber."\r\n";
			$message .= "Email address: ".$to."\r\n";
			$message .= "Your customer interface is also available from http://".$www2.$domaineFull." by login into 'Your Account' with your Client Number and your email address.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
				$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
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
			
			$subject = "[PAYMENT RECEIVED] - NIC#:".$_POST['item_name']."";
			  }
			  

  if($payLang == "3") {
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
          	if(!empty($address_autre)) {
            	$address_autre2 = str_replace("<br>","\r\n",$address_autre);
            	$message .= $address_autre2."\r\n";
          	}
          	if(!empty($tel)) $message.="Tel: $tel\r\n";
          	if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
			$message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
			$message .= $address_city." le ".$dateAdded."\r\n\r\n";
			$message .= $adreesName.",\r\n";
			$message .= "Your payment through PAYPAL has been confirmed, Thank you.\r\n"; 
			$message .= "Your order is now treated for shipping.\r\n";
			$message .= "Go the your personal customer interface to follow the order and take your invoice.\r\n"; 
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			$message .= "Pypal e-mail adres: ".$payer_email."\r\n";
			$message .= "Klant interface: ".$urlAdminClient."\r\n";
			$message .= "NIC (bestelnummer): ".$_POST['item_name']."\r\n";
			$message .= "Klant nummer: ".$clientNumber."\r\n";
			$message .= "E-mail adres: ".$to."\r\n";
			$message .= "Your customer interface is also available from http://".$www2.$domaineFull." by login into 'Your Account' with your Client Number and your email address.\r\n";
			$message .= "--------------------------------------------------------------------------------------------------\r\n";
			if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
				$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$clientNumber."'");
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
			$message .= "Voor verdere informateFor more information, do not hesitate to contact us.\r\n";
			$message .= "Het verkooopsteam.";
			$message .= $mailOrder;
			
			$subject = "[PAYMENT RECEIVED] - NIC#:".$_POST['item_name']."";
			  }




			$from = $mailOrder;
			
			mail($to, $subject, rep_slash($message),
			"Return-Path: $from\r\n"
			."From: $from\r\n"
			."Reply-To: $from\r\n"
  ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
			."X-Mailer: PHP/" . phpversion());
}
?>
