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

if($_POST['action'] == "add" OR $_POST['action'] == "new_ppt") {


        $queryZ = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_POST['extra1']."' AND users_total_to_pay = '".$_POST['amount']."'");
        $queryZNum = mysql_num_rows($queryZ);
        if($queryZNum > 0) {
 
            $hoy = date("Y-m-d H:i:s");
  
            mysql_query("UPDATE users_orders SET users_payed = 'yes', users_confirm = 'yes', pp_statut = 'Ref Pay.nl: ".$_POST['payment_session_id']."', users_date_payed = '".$hoy."', users_payment = 'pn' WHERE users_nic = '".$_POST['extra1']."'");
            
   
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
                             if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contr�le cheque cadeau dans la commande
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
            
			$_store = str_replace("&#146;","'",$store_name);
			$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                                  $message .= $address_autre2."\r\n";
                              }
                              if(!empty($tel)) $message.="T�l�phone: ".$tel."\r\n";
                              if(!empty($fax)) $message.="Fax: ".$fax."\r\n";
                             $message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
                             $message .= $address_city." le ".dateFr($resultZ['users_date_added'],$resultZ['users_lang'])."\r\n\r\n";
                             $message .= $adreesName.",\r\n";
                             $message .= "Votre paiement par carte de cr�dit a �t� accept�.\r\n";
                             $message .= "Paiement re�u, merci.\r\n";
                             $message .= "Votre commande est en traitement pour exp�dition.\r\n";
                             $message .= "Veuillez vous rendre sur votre interface de suivi client pour suivre votre commande et recevoir votre facture.\r\n";
                             $message .= $_store." vous remercie de votre commande.\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             $message .= "NIC (Num�ro Identification Commande): ".$resultZ['users_nic']."\r\n";
                             $message .= "URL interface de suivi client: ".$urlAdminClient."\r\n";
                             $message .= "Num�ro client: ".$resultZ['users_password']."\r\n";
                             $message .= "Adresse email: ".$resultZ['users_email']."\r\n";
                             $message .= "Votre interface de suivi client est aussi accessible sur http://".$www2.$domaineFull." via \"Votre compte\" avec votre num�ro client et adresse email ou via \"Suivi commande\" apr�s avoir saisi votre Num�ro ID Commande (NIC).\r\n";
                             $message .= "--------------------------------------------------------------------------------------------------\r\n";
                             if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
                             		$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'") or die (mysql_error());
									$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
									$affiliateCom2 = $resultZAffCom['aff_com'];
	                             $message .= "\r\n";
								 $message .= $_store." vous r�mun�re � hauteur de ".$affiliateCom2."% sur les ventes g�n�r�es par les clients que vous nous envoyez.\r\n";
								 $message .= "Cette offre ne vous engage � rien.\r\n";
	                             $message .= "Il vous suffit d'envoyer un email � vos connaissances contenant le lien ci-dessous:\r\n";
	                             $message .= "http://".$www2.$domaineFull."/index.php?eko=".$affiliateNumber."\r\n";
	                             $message .= "Vous pouvez suivre a tout moment l'evolution de vos gains via \"Votre compte\" sur http://".$www2.$domaineFull."\r\n";
	                             $message .= "Voici ci-dessous les informations de votre compte d'affiliation:\r\n";
	                             $message .= "Num�ro de compte d'affiliation: ".$affiliateNumber."\r\n";
	                             $message .= "Mot de passe: ".$affiliatePass."\r\n";
	                             $message .= "\r\n";
                             }
                             $message .= "Pour toute information, communiquer avec nous.\r\n";
                             $message .= "Cordialement\r\n";
                             $message .= "Le service\r\n";
                             $message .= $mailOrder;

                             $subject = "[RECEPTION PAIEMENT] - NIC#:".$resultZ['users_nic'];
            }
            if($resultZ['users_lang']== "2") {
            // alert client confirmation du paiement EN
                            $_store = str_replace("&#146;","'",$store_name);
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
            // alert client confirmation du paiement BE_NL
                             $_store = str_replace("&#146;","'",$store_name);
                            $message = $_store."<font face=arial><br>".$address_street."<br>".$address_country." - ".$address_cp." - ".$address_city."<br><br>";
                              if(!empty($address_autre)) {
                                  $address_autre2 = str_replace("<br>","<br>",$address_autre);
                                  $message .= $address_autre2."<br>";
                              }
                              if(!empty($tel)) $message.="Tel: ".$tel."<br>";
                              if(!empty($fax)) $message.="Fax: ".$fax."<br>";
                             $message .= "URL: <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>E-mail: ".$mailOrder."<br>";
                             $message .= $address_city.", op ".$resultZ['users_date_added']."<br>";
                             $message .= $adreesName.",<br>";
                             $message .= "Uw betaling werd uitgevoerd en succesvol ontvangen.<br>";
                             $message .= "Uw bestelling wordt nu behandeld om verzonden te worden.<br>";
                             $message .= "Ga naar uw klant interface, en volg uw bestelling op...<br>";
                             $message .= $_store." dankt u voor uw bestelling.<br>";
                             $message .= "<br>";
                             $message .= "NIC (bestel nummer): ".$resultZ['users_nic']."<br>";




                             $message .= "Klant interface URL: <a href=".$urlAdminClient.">".$urlAdminClient."</a><br>";
//----------------------------------------------------------------------------------------------

                             $message .= "Klant nummer: ".$resultZ['users_password']."<br>";
                             $message .= "E-mail adres: ".$resultZ['users_email']."<br>";
                             $message .= "U kunt zich ook rechtstreeks aanmelden op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a> en ga daarna naar 'Uw account' en meldt u aan met uw account nummer en e-mail adres.<br>";                             
                             $message .= "<br>";
                             if($affiliateAuto=='oui' AND $queryZAffNum>0 AND isset($affAccount)) {
                             		$queryZAffFindCom = mysql_query("SELECT aff_com  FROM affiliation WHERE aff_customer = '".$resultZ['users_password']."'");
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


                             $subject = "Uw bestelling met nummer - NIC:".$resultZ['users_nic'];
            }
 
                             $to = $resultZ['users_email'];
                             $from = $mailOrder;
                             mail($to, $subject, rep_slash($message),
                             "Return-Path: $from\r\n"
                             ."From: $from\r\n"
                             ."Reply-To: $from\r\n"
     ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
                             ."X-Mailer: PHP/" . phpversion());
            //-----------------------
            // admin e-mail
            //-----------------------
                            $to2 = $mailOrder;
                            $from2 = $mailOrder;
                            $subject2 = "Er is een nieuwe bestelling - BETAALD MET PAY.NL - NIC:".$resultZ['users_nic'];
                            $message2 = "<font face=arial>Een nieuwe bestelling op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>";
                            $message2 .= "Datum: ".date("d-m-Y H:i:s")."<br>";
                            $message2 .= "<br>";
                            $message2 .= "Naan en voornaam: ".$resultZ['users_firstname']." ".$resultZ['users_lastname']."<br>";
                            $message2 .= "E-mail: ".$resultZ['users_email']."<br>";
                            $message2 .= "Land: ".$resultZ['users_country']."<br>";
                            $message2 .= "<br>";
                            $message2 .= "Betaalwijze: PAY.NL<br>";
                            $message2 .= "Bedrag: ".$resultZ['users_total_to_pay']."<br>";
                            $message2 .= "NIC nummer: ".$resultZ['users_nic']."<br>";
                            $message2 .= "Klant nummer: ".$resultZ['users_password']."<br>";

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
                        // vandaag
                        $dateNow = date("Y-m-d H:i:s");
                        
                        // vandaag + 1 jaar
                        $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
                        
                        // toevoegen database
                        mysql_query("UPDATE gc 
                                    SET 
                                    gc_payed = 1,
                                    gc_start = '".$dateNow."',
                                    gc_end = '".$nextYear."'
                                    WHERE gc_nic = '".$resultZ['users_nic']."'");
                                    
                        // selecteer bon
                       $queryUpGc = mysql_query("SELECT *
                                                FROM gc
                                                WHERE gc_nic = '".$resultZ['users_nic']."'
                                                ");
                        $rowUpGc = mysql_fetch_array($queryUpGc);
                                    
                        // mail bon
                        $domMaj11 = strtoupper($domaineFull);
                        $to11 =  $resultZ['users_email'];
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
                        $messageToSend .= URL_DE." : http://".$www2.$domaineFull."/geschenkbon/index.php?l=".$resultZ['users_lang']."\r\n";
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
                //--------------------------
                // Send message to affiliate
                //--------------------------
                if($resultZ['users_affiliate']!=='') {
                   $queryAff = mysql_query("SELECT aff_nom, aff_prenom, aff_email, aff_number FROM affiliation WHERE aff_number = '".$resultZ['users_affiliate']."'");
                   if(mysql_num_rows($queryAff) > 0) {
                      if($resultZ['users_lang']== "1") {
                        DEFINE("A3001Z","COMMISSION AFFILIATION");
                        DEFINE("A3002Z","Vous �tes membre affili� sur");
                        DEFINE("A3003Z","Votre num�ro d'affili� est");
                        DEFINE("A3004Z","Une commande vient d'�tre confirm� pour un montant de");
                        DEFINE("A3005Z","Votre commission");
                        DEFINE("A3006Z","Pour conna�tre l'�tat de votre compte, veuillez vous rendre sur");
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
$amountTotal = $resultZ['users_total_to_pay']*100;
}

echo "TRUE|".$_POST['extra1'];
?>