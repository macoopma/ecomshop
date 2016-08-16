<?php
include('../configuratie/configuratie.php');
##$_POST['cart_order_id'] = "RVCJW-12";

$title = "Confirmation paiement";
$modeSelect = "Carte de cr&eacute;dit";



function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

 
function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src='".$image."' border='0'>";    
    }
    else {
       $returnImage = "<img src='".$image."' border='0' width='".$wi."' height='".$he."'>";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}

  
                $hoy = date("Y-m-d H:i:s");

   
                mysql_query("UPDATE users_orders SET users_payed = 'yes', users_confirm = 'yes', users_date_payed = '".$hoy."', users_payment = 'cc' WHERE users_nic = '".$_POST['cart_order_id']."'");
    
                $query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_POST['cart_order_id']."'");
                $resultZ = mysql_fetch_array($query);
                
     
                include("../includes/lang/lang_".$resultZ['users_lang'].".php");
            
      
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
                         $query = mysql_query("SELECT products_qt, products_sold, products_id
                                               FROM products
                                               WHERE products_id = '".$check[0]."'");
                         $row = mysql_fetch_array($query);
                         if($check[1]!=="0") {
                         	$aaa = $row['products_qt']-$check[1];
                         	$bbb = $row['products_sold'] + $check[1];
                                   $updateStock = mysql_query("UPDATE products
                                                SET
                                                products_qt = $aaa,
                                                products_sold = $bbb
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
	 

	 
							$_store = str_replace("&#146;","'",$store_company);
							$message = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
							if(!empty($address_autre)) {
								$address_autre2 = str_replace("<br>","\r\n",$address_autre);
								$message .= $address_autre2."\r\n";
							}
							if(!empty($tel)) $message .= "Phone: ".$tel."\r\n";
							if(!empty($fax)) $message .= "Fax: ".$fax."\r\n";
							$message .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
							$message .= $address_city." on ".$resultZ['users_date_added']."\r\n\r\n";
							$message .= $adreesName.",\r\n";
							$message .= "Your payment has been processed and received with success.\r\n";
							$message .= "Your order is now in treatment for shipping.\r\n";
							$message .= "Go to your custom interface URL to follow your order and to receive your invoice.\r\n";
							$message .= $_store." thank you for your order.\r\n";
							$message .= "--------------------------------------------------------------------------------------------------\r\n";
							$message .= "Customer Interface URL: ".$urlAdminClient."\r\n";
							$message .= "NIC (Order ID): ".$resultZ['users_nic']."\r\n";
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
							
							$to = $resultZ['users_email'];
							$from = $mailOrder;
							
							mail($to, $subject, rep_slash($message),
							"Return-Path: $from\r\n"
							."From: $from\r\n"
							."Reply-To: $from\r\n"
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
                    
   
        $queryUpGc = mysql_query("SELECT * FROM gc WHERE gc_nic = '".$resultZ['users_nic']."'");
        $rowUpGc = mysql_fetch_array($queryUpGc);
                    
    
        $domMaj11 = strtoupper($domaineFull);
        $to11 =  $rowUp['users_email'];
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
        $messageToSend .= "Datum: ".$hoy."\r\n\r\n";
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
?>
<html>

<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title><?php print $title." | ".$store_name;?></title>
<link rel="stylesheet" href="../css/<?php print $colorInter;?>.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" width="<?php print $storeWidth;?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="backGroundTop" height="100%">
<tr height="<?php print $cellTop;?>" valign="top" >

<?php
 

if(isset($logo) AND $logo!=="noPath") {
  
    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize("../".$logo);
        $logoWidth = $largeurLogo[0];
        $widthMaxLogo = 160;
   
        if($logoWidth>$widthMaxLogo) {
            $logoRezise = $largeurLogo[0]/$largeurLogo[1];
            $wwww=$widthMaxLogo;
            $hhhh=$widthMaxLogo/$logoRezise;
            $logoWidth = $wwww;
        }
        else {
            $wwww=0;
            $hhhh=0;
            $logoWidth = $largeurLogo[0];
        }
        print detectIm("../".$logo,$wwww,$hhhh);
    print "</td>";
}
else {
    if(isset($logo2) AND $logo2!=="noPath") {
    
    print "<td valign='middle' align='center'>";
       if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm("../".$logo2,0,0)."</a>"; else print detectIm("../".$logo2,0,0);
    print "</td>";
    }
}
?>
</tr>
<tr>



    <td colspan="3" valign="top">


</td>
</tr>

<tr valign="top">
<td colspan="3" valign="top">
             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td><b><img src="../im/accueil.gif" align="TEXTTOP">&nbsp;<a href="../cataloog.php?lang=1" ><?php print strtoupper(HOME);?></a> | 2CO.COM |</b>
               </td>
              </tr>
             </table>


      <table width="80%" border="0" cellpadding="0" cellspacing="5" align="center">
        <tr>
          <td valign="top">


      <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">

              <tr>
                <td valign="top" align="center"><br>
<?php
if($resultZ['users_lang']==1) {
   print "<table border='0' cellpadding='0' cellspacing='0' width='95%'><tr><td>";
   print "Aujourd'hui, ".date("Y-m-d H:i:s")."<br><br>";
   print $adreesName.",<br>";
   print "Votre paiement a &eacute;t&eacute; confirm&eacute; par <a href='http://www.2checkout.com' target='_blank'><b>2checkout.com</b></a>, merci.<br>";
   print "Un courrier &eacute;lectronique a &eacute;t&eacute; envoy&eacute; <b>".$resultZ['users_email']."</b>, veuillez en prendre connaissance.<br>";
   print "<p><img src='../im/fleche_menu2.gif' align='absmiddle'> <a href='../cataloog.php?lang1=".$resultZ['users_lang']."&var=session_destroy'>Retour boutique</a></p>";
   print "</td></tr></table>";
}
if($resultZ['users_lang']==2) {
  print "<table border='0' cellpadding='0' cellspacing='0' width='95%'><tr><td>";
  print "Today, ".date("Y-m-d H:i:s")."<br><br>";
  print $adreesName.",<br>";
  print "Your payment has been accepted by <a href='http://www.2checkout.com' target='_blank'><b>2checkout.com</b></a>, thank you.<br>";
  print "An email has been sent to <b>".$resultZ['users_email']."</b>, Please check your mail in order to follow your purchase.<br>";
  print "<p><img src='../im/fleche_menu2.gif' align='absmiddle'> <a href='../cataloog.php?lang=".$resultZ['users_lang']."&var=session_destroy'>Back to store</a></p>";
print "</td></tr></table>";
}

print "<br>";
print "<table border='0' cellpadding='0' cellspacing='0' width='95%'><tr><td>";
print "<b>".$store_company."</b><br>";
print $address_street."<br>";
print $address_cp." - ".$address_city."<br>";
print $address_country;
if(!empty($address_autre)) print "<br>".$address_autre;
if(!empty($tel)) print "<br>".TELEPHONE.": ".$tel;
if(!empty($fax)) print "<br>Fax: $fax";
print "</td></tr></table>";
?>
                </td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>
</td>
</tr>
<tr>
<td colspan="3" valign = "bottom">

<table width="99%" border="0" align="center" cellpadding="5" cellspacing="0" class="TABLEBottomPage">
<tr height="32">
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
|&nbsp;<a href="../cataloog.php?lang=1"><?php print HOME;?></a>&nbsp;|&nbsp;<a href="../infos.php?lang=1&info=5"><?php print NOUS_CONTACTER;?></a>&nbsp;|
</td>
</tr>
</table>
</td>
</tr>
</table>

<br>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
	
	</tr>
</table>

</td>
</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</td></tr></table>
</body>
</html>
