<?php
session_start();

include('../configuratie/configuratie.php');

$la_link = "../includes/lang/klantlogin/lang".$_GET['l']."/login.php";
include($la_link);
$modWidth="440";
$dateZ = date("d-m-Y H:i:s");
$Cle = "jhsd813gsHGFsnsaShDg254sd";
if($_SERVER['REMOTE_ADDR']) {$ipZ=$_SERVER['REMOTE_ADDR'];} else {$ipZ='??';}


if($iscEmail=="oui" AND !isset($_POST['action']) AND !isset($_POST['facture']) AND !isset($_GET['confirm']) AND !isset($_GET['action']) AND !isset($_GET['adresse_facture'])) {
 
   $to30Z = $mailInfo;
    $subject30Z = "Connectie naar klant interface http://".$www2.$domaineFull."/klantlogin/login.php?nic=".$_GET['nic']."&l=".$_GET['l'];
    $from30Z = "no_response@".$domaine.".com";
    $message30Z = "Datum: ".$dateZ;
    $message30Z.= "\r\nIP adres: ".$ipZ;
    $message30Z.= "\r\nKlant NIC: ".$_GET['nic'];
    $message30Z.= "\r\nURL: http://".$www2.$domaineFull."/klantlogin/login.php?nic=".$_GET['nic']."&l=".$_GET['l'];
    $message30Z.= "\r\n------";
    $message30Z.= "\r\nAdmin ".$store_name;
    mail($to30Z, $subject30Z, $message30Z,
    "Return-Path: $from30Z\r\n"
    ."From: $from30Z\r\n"
    ."Reply-To: $from30Z\r\n"
    ."X-Mailer: PHP/" . phpversion());
}

 
if(isset($_POST['action']) AND $_POST['action']=="download" AND isset($_POST['prod'])) {
    $TexteClair = Decrypte($_POST['prod'],$Cle);
    $gotoZ = $TexteClair;

    $to20Z = $mailInfo;
 
    $subject20Z = "Download http://".$www2.$domaineFull." : ".$_POST['nicZ']." | ".$gotoZ;
    $from20Z = "no_response@".$domaine.".com";
    $message20Z = "Datum: ".$dateZ;
    $message20Z.= "\r\nIP Adres: ".$ipZ;
    $message20Z.= "\r\nKlant NIC: ".$_POST['nicZ'];
    $message20Z.= "\r\nBestand: ".$gotoZ;
    $message20Z.= "\r\nURL: http://".$www2.$domaineFull."/klantlogin/login.php?nic=".$_POST['nicZ']."&l=".$_POST['lZ'];
    $message20Z.= "\r\n------";
    $message20Z.= "\r\nAdmin ".$store_name;
    mail($to20Z, $subject20Z, $message20Z,
    "Return-Path: $from20Z\r\n"
    ."From: $from20Z\r\n"
    ."Reply-To: $from20Z\r\n"
    ."X-Mailer: PHP/" . phpversion());
    header("Location: ".$gotoZ);
}


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

 
function get_product_name($id,$l) {
   $queryName = mysql_query("SELECT products_name_".$l." FROM products WHERE products_id='".$id."'");
   $NameResult = mysql_fetch_array($queryName);
   $productName = $NameResult['products_name_'.$l];
   return $productName;
}

 
function GenerationCle($Texte,$CleDEncryptage) {
  $CleDEncryptage = md5($CleDEncryptage);
  $Compteur=0;
  $VariableTemp = "";
  for($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
    if($Compteur==strlen($CleDEncryptage))
      $Compteur=0;
    $VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1);
    $Compteur++;
    }
  return $VariableTemp;
}
function Crypte($Texte,$Cle) {
  srand((double)microtime()*1000000);
  $CleDEncryptage = md5(rand(0,32000) );
  $Compteur=0;
  $VariableTemp = "";
  for($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
    if($Compteur==strlen($CleDEncryptage))
      $Compteur=0;
    $VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) );
    $Compteur++;
    }
  return base64_encode(GenerationCle($VariableTemp,$Cle) );
}

 
function Decrypte($Texte,$Cle) {
  $Texte = GenerationCle(base64_decode($Texte),$Cle);
  $VariableTemp = "";
  for($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
    $md5 = substr($Texte,$Ctr,1);
    $Ctr++;
    $VariableTemp.= (substr($Texte,$Ctr,1) ^ $md5);
    }
  return $VariableTemp;
}
 
$query100 = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_GET['nic']."'");
$row = mysql_fetch_array($query100);
if($row['users_customer_delete']=='yes') {
   header("Location: index.php");
}

$emailZ = $row['users_email'];
$accountZ = $row['users_password'];
$payedZ = $row['users_payed'];
$confirmZ = $row['users_confirm'];
$productsZ = $row['users_products'];

 
if(isset($_GET['confirm']) AND $_GET['confirm'] == INT4) {
    mysql_query("UPDATE users_orders SET users_confirm = 'yes' WHERE users_nic='".$_GET['nic']."'");
    header("Location: ".$_SERVER['PHP_SELF']."?nic=".$_GET['nic']."&l=".$_GET['l']."&action=1");
}

 

if(isset($_GET['adresse_facture']) AND $_GET['adresse_facture']== INT301) {
	if(!isset($_GET['emailE']) or empty($_GET['emailE'])) {$_GET['emailE'] = $row['users_email'];}
$fact_adresse = $_GET['nomE']."|".
				$_GET['adresseE']."|".
				$_GET['codeE']."|".
				$_GET['villeE']."|".
				$_GET['paysE']."|".
				$_GET['emailE'];
        mysql_query("UPDATE users_orders SET users_facture_adresse = '".$fact_adresse."' WHERE users_nic = '".$_GET['nic']."'");   
        header("Location: login.php?nic=".$_GET['nic']."&l=".$_GET['l']."");
}
?>
<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";

  var nomE = document.form1.nomE.value;
  var adresseE = document.form1.adresseE.value;
  var codeE = document.form1.codeE.value;
  var villeE = document.form1.villeE.value;
  var paysE = document.form1.paysE.value;


  if(document.form1.elements['nomE'].type != "hidden") {
    if(nomE == '' || nomE.length < 2) {
      error_message = error_message + "<?php print INT1001;?>.\n";
      error = 1;
    }
  }

  if(document.form1.elements['adresseE'].type != "hidden") {
    if(adresseE == '' || adresseE.length < 2) {
      error_message = error_message + "<?php print INT1002;?>.\n";
      error = 1;
    }
  }

  if(document.form1.elements['codeE'].type != "hidden") {
    if(codeE == '' || codeE.length < 4) {
      error_message = error_message + "<?php print INT1003;?>.\n";
      error = 1;
    }
  }

  if(document.form1.elements['villeE'].type != "hidden") {
    if(villeE == '' || villeE.length < 2) {
      error_message = error_message + "<?php print INT1004;?>.\n";
      error = 1;
    }
  }

  if(document.form1.elements['paysE'].type != "hidden") {
    if(paysE == '' || paysE.length < 3) {
      error_message = error_message + "<?php print INT1005;?>.\n";
      error = 1;
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

<?php
$message20 = "<table width='".$modWidth."' align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE100' style='background:#F1F1F1'><tr>";
$message20.="<td><div align='left'><b>".INT26."</b></div></td></tr><td>";
$message20.="<table border='0' width='100%' align='center' cellspacing='2' cellpadding='0'>";
$message20.="<tr bgcolor='#F1F1F1'>";
$message20.="<td align='center' style='padding:5px'><b>Ref/".INT60."</b></td>";
$message20.="<td width='20' align='center' style='padding:5px'><b>Aantal</b></td>";
$message20.="<td width='50' align='center' style='padding:5px'><b>E.P</b></td>";
$message20.="<td width='50' align='right' style='padding:5px'><b>".INT62."</b></td>";
                $split = explode(",",$row['users_products']);
                foreach ($split as $item) {
                          $check = explode("+",$item);
  
                          if($check[1]!=="0")
                          {
  
                         $ref_product[] = $check[3];
                          $id_product[] = $check[0];
                          $message20.="</tr><tr>";
                          
 
	                        if(!empty($check[6])) {
	                        	$_optZ = explode("|",$check[6]);
 
								$lastArray = $_optZ[count($_optZ)-1];
								if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
								$_optZ = implode("|",$_optZ);
								$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
							}
							else {
								$q="";
							}
  
                          $message20.="<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$check[4]."</u>".$q."<br><img src='im/zzz.gif' width='1' height='3'><br></td>";
   
                          $message20.="<td width='20' align='center'>".$check[1]."</td>";
    
                          $message20.="<td width='50' align='center'>".$check[2]."</td>";
     
                          $priceTTC = ($check[2] * $check[1]);
                          $message20.="<td width='50' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
                          }
                  }

$message20.="</tr></table>";
$message20.="<br>";
$message20.="<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
$message20.="<td>";
$message20.="&nbsp;";
//$message20.="Totaal gewicht: <b>".$row['users_products_weight']."</b> gr | ".$row['users_products_weight_price']." ".$row['users_symbol_devise']."/gr HT";
$message20.="</td>";
$message20.="</tr></table>";

$message20.="<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
$message20.="<td>";
$message20.="<table border='0' align='right' cellspacing='0' cellpadding='0'><tr>";
$message20.="<td align='right'>".INT63."</td>";
$message20.="<td align='right'><b>".$row['users_products_ht']."</b></td>";
$message20.="</tr><tr>";
 
if($row['users_products_tax_statut']!=="") {
   $message20.="<td align='right'>".$taxeName."</td>";
   $message20.="<td align='right' width='100'>";
   
  
                $explodMultiple = explode("|",$row['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                            $message20.= "";
                        }
                        else {
                            if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                            $message20.= str_replace(">", "%: ", $item).$br;
                        }
                    }
                $message20.= "</td>";
}           
           
   
           $message20.= "</tr><tr>";
           $message20.= "<td align='right'>".INT302."</td>";
           $message20.= "<td align='right'>".$row['users_ship_ht']."</td>";
           $message20.= "</tr><tr>";
    
           if($row['users_products_tax_statut']!=="") {
               $message20.= "<td align='right'>".INT303."</td>";
               $message20.= "<td align='right'>".$row['users_ship_tax']."</td>";
           }

     
            if($row['users_sup_ttc'] > 0) {
                            $message20 .= "</tr><tr>";
                            $message20 .= "<td align='right'>".EMBALLAGE."</td>";
                            $message20 .= "<td align='right'>".$row['users_sup_ht']."</td>";
                            $message20 .= "</tr><tr>";
      
                            if($row['users_products_tax_statut']!=="") {
                              $message20 .= "<td align='right'>".$taxeName." ".$taxe."%</td>";
                              $message20 .= "<td align='right'>".$row['users_sup_tax']."</td>";
                            }
            }

$message20.= "</tr><tr>";

$la=70;
if($row['users_account_remise_app'] > 0) {
	$message20.= "<td align='right'>".INT2010."</td>";
	$message20.= "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_account_remise_app']."</span></td>";
	$message20.= "</tr><tr>";
}
if($row['users_remise']>0) {
	$message20.= "<td align='right'>".INT304."</td>";
	$message20.= "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise']."</span></td>";
	$message20.= "</tr><tr>";
}
if($row['users_remise_coupon']>0) {
	$message20.= "<td align='right'>".INT305."</td>";
	$message20.= "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise_coupon']."</span></td>";
	$message20.= "</tr><tr>";
}         
if($row['users_contre_remboursement'] > 0) {
	$message20.= "<td align='right'>".INT305A."</td>";
    $message20.= "<td align='right'>".$row['users_contre_remboursement']."</td>";
	$message20.= "</tr><tr>";
}
if($row['users_remise_gc'] > 0) {
	$message20.= "<td align='right'>".CHEQUE_CADEAU."</td>";
	$message20.= "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise_gc']."</span></td>";
	$message20.= "</tr><tr>";
}       
            
$message20.="<td align='right'><br><b>TOTAAL</b>:</td>";
$message20.="<td align='right'><br><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
$message20.="</tr>";
$message20.="</table>";
$message20.="</td>";
$message20.="</tr>";
$message20.="</table>";
$message20.="</td></tr></table>";

 
 if(isset($_POST['facture']) AND $_POST['facture'] == INT306) {

        mysql_query("UPDATE users_orders SET users_facture = users_facture+1
                     WHERE users_nic='".$_GET['nic']."'");


        $query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_GET['nic']."'");
        $row = mysql_fetch_array($query);
        $numFact = str_replace("||","",$row['users_fact_num']);
        if($numFact=="") $numFact="XXXX";

        $messageToSend = "
            <html>
            <head>
            <title>".$store_name."</title>
            <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
            <STYLE TYPE='text/css'>
            <!--
                BODY             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px; BACKGROUND-COLOR: #F6F6EB}
                FONT             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                .TABLEMenuPathCenter {BACKGROUND-COLOR: #FFFFFF; border: 1px #000000 solid}
                .fontrouge       {COLOR: #FF0000}
                .large           {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 12px}
                DIV              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
                P                {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
                TD               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                TR               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                A:link           {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                A:active         {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                A:visited        {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                A:hover          {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline }
            -->
            </STYLE>
            </head>
           <body leftmargin='10' topmargin='10' marginwidth='0' marginheight='0'>
           <table width='".$modWidth."' align='center' border='0' cellspacing='0' cellpadding='5' class='TABLEMenuPathCenter'><tr><td>
           <table width='".$modWidth."' align='center' border='0' cellspacing='0' cellpadding='0'><tr><td>
           <div align='left'>
           <b>".$store_company."</b><br>
           URL: <a href='' target='_blank'>http://".$www2.$domaineFull."</a><br>
           E-mail: <a href='mailto:".$mailOrder."'>".$mailOrder."</a><br>
           ".$address_street."<br>
           ".$address_cp." - ".$address_city."<br>
           ".$address_country;
           if(!empty($address_autre)) $messageToSend .= "<br>".$address_autre;
           if(!empty($tel)) $messageToSend.="<br>".TEL.": ".$tel;
           if(!empty($fax)) $messageToSend.="<br>".FAX.": ".$fax."
           </div>
           </td>
           <td valign='top' align='right'><b><span class='large'>".INT42."</span></b><br>#: ".$numFact."<br>Date: ".date("d/m/Y")."
           </td>
           </tr>
           <td colspan='2'>
            --------------------------------------------------------------------------------------------------
           </td>
           </tr>
           <td>
           <b>".INT57."</b><br>
            ".$row['users_lastname']." ".$row['users_firstname'].",<br>
            ".$row['users_address']."<br>
            ".$row['users_city']."<br>
            ".$row['users_zip']."<br>
            ".$row['users_country']."<br>
            E-mail: ".$row['users_email']."
            </td>
            <td  valign='top' align='right'>
            <b>NIC</b>: <span class='fontrouge'>".$row['users_nic']."</span><br>
            <b>".INT307."</b>: <span class='fontrouge'>".$row['users_password']."</span><br>
            <b>".INT58."</b>: <span class='fontrouge'>".dateFr($row['users_date_added'],$_GET['l'])."</span>
            </td>
            </tr>
           <td colspan='2'>
            --------------------------------------------------------------------------------------------------
           </td>
           </tr>
           </table>";

$messageToSend .= $message20."
           <div align='center'>--------------------------------------------------------------------------------------------------</div>
           <div align='left'>
           ".INT45." ".$row['users_email']." ".INT70."
           </div>
           </td></tr></table>
           </td></tr></table>";
           $messageToSend.="</body></html>";
           $to = $row['users_email'];
           $subject = INT42." ".$domaineFull." - NIC: ".$_GET['nic']."";
           $from = $mailOrder;

           mail($to, $subject, rep_slash($messageToSend),
                "Return-Path: $from\r\n"
                ."From: $from\r \n"
                ."Reply-To: $from\r \n"
                ."MIME-Version: 1.0\r \n"
                ."Content-Type: text/html; charset='iso-8859-1'\r \n"
                ."X-Mailer: PHP/" . phpversion());

           $message = "<span class='fontrouge'>".INT74." <b>".$row['users_email']."</b>.</span>";
 }

       $commande100 = explode("+",$row['users_products']);
       $commandeNum100 = count($commande100);
          if($row['users_payment']=="pp") {$modePayment = "Paypal"; $bancaireOuPostale = ""; $infoBancaire = "";}
          if($row['users_payment']=="mb") {$modePayment = "MoneyBookers"; $bancaireOuPostale = ""; $infoBancaire = "";}
          if($row['users_payment']=="eu") {$modePayment = "1euro.com"; $bancaireOuPostale = ""; $infoBancaire = "";}
          if($row['users_payment']=="cc") {$modePayment = INT17; $bancaireOuPostale = ""; $infoBancaire = "";}
          if($row['users_payment']=="BL") {$modePayment = INT6a;$bancaireOuPostale = ""; $infoBancaire = "";}
          if($row['users_payment']=="pn") {$modePayment = "Pay.nl";$bancaireOuPostale = ""; $infoBancaire = "";}
          
		  if($row['users_payment']=="wu") {
		   	  $modePayment = "Western Union";
			     $bancaireOuPostale = "";
                  $infoBancaire = "<b>".INT8."</b> :<br><br>";
                  $infoBancaire.= "<table border='0' width='100%' cellpadding='0' cellspacing = '2' class='TABLE4'>";
                  $infoBancaire.= "<tr><td><b>".$western."</b></td></tr>";
                  $infoBancaire.= "<tr><td>".PREP4."</td></tr>";
                  $infoBancaire.= "</table>";
		}
		   
          if($row['users_payment']=="ch" or $row['users_payment']=="ma" or $row['users_payment']=="tb" ) {
				if($row['users_payment']=="ch") {
					$modePayment = INT5; 
					$ordre = ($chequeOrdre=="")? $store_company : $chequeOrdre;
				}
				if($row['users_payment']=="ma") {
					$modePayment = INT6;
					$ordre = ($mandatOrdre=="")? $store_company : $mandatOrdre;
				}
				if($row['users_payment']=="tb") {
					$modePayment = TRAITE_BANCAIRE;
					$ordre = ($traiteOrdre=="")? $store_company : $traiteOrdre;
				}
	          $bancaireOuPostale = INT7;
                  $infoBancaire = "<b>".INT8."</b> :<br><br>";
                  $infoBancaire.= "<table border='0' width='100%' cellpadding='0' cellspacing = '2' class='TABLE4'>";
                  $infoBancaire.= "<tr><td>".A_ORDRE_DE.": <b>".$ordre."</b></td></tr>";
                  $infoBancaire.= "<tr><td>".MONTANT.": <b>".$row['users_total_to_pay']." ".$row['users_symbol_devise']."</b></td></tr>";
                  $infoBancaire.= "<tr><td>".$address_street."</td></tr>";
                  $infoBancaire.= "<tr><td>".$address_cp."</td></tr>";
                  $infoBancaire.= "<tr><td>".$address_city.", ".$address_country."</td></tr>";
                  $infoBancaire.= "</table>";
       }
       
       if($row['users_payment']=="BO") {
          $query200 = mysql_query("SELECT * FROM admin");
          $row200 = mysql_fetch_array($query200);
          $modePayment = INT9;
          $bancaireOuPostale = INT10;
                  $infoBancaire = "<b>".INT11."</b>:<br>";
                  $infoBancaire.= "<table border='0' width='100%' cellpadding='0' cellspacing='2' class='TABLE4'>";
                  $infoBancaire.= "<tr><td><b>".INT12.":</b></td><td>".$row200['banqueTitulaireCompte']."</td></tr>";
                  $infoBancaire.= "<tr><td><b>".INT13.":</b></td><td>".$row200['banqueNom']."</td></tr>";
                  if($row200['banqueAdresse']!=="") $infoBancaire.= "<tr><td><b>".INT14.":</b></td><td>".$row200['banqueAdresse']."</td></tr>";
                  if($row200['banqueNumeroCompte']!=="") $infoBancaire.= "<tr><td><b>".INT15.":</b></td><td>".$row200['banqueNumeroCompte']."</td></tr>";
                  if($row200['banqueRib']!=="") $infoBancaire.= "<tr><td><b>".CLE_RIB.":</b></td><td>".$row200['banqueRib']."</td></tr>";
                  if($row200['banqueCodeSwift']!=="") $infoBancaire.= "<tr><td><b>".INT16.":</b></td><td>".$row200['banqueCodeSwift']."</td>";
                  if($row200['banqueIban']!=="") $infoBancaire.= "<tr><td><b>".INT16A.":</b></td><td>".$row200['banqueIban']."</td>";
                  $infoBancaire.= "</tr></table>";
       }
?>
<html>
<head>

<title><?php print $store_name;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/<?php print $colorInter;?>.css" type="text/css">
                 <STYLE TYPE="text/css">
                 <!--
                 BODY              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px; BACKGROUND-COLOR: #none; BACKGROUND-IMAGE: none}
                 FONT              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                 .TABLEMenuPathCenter  {BACKGROUND-COLOR: #FFFFFF; border: 1px #000000 solid}
                 .TABLE100         {BACKGROUND-COLOR: #FFFFFF; border: 1px #CCCCCC dotted}
                 .TABLE4           {BACKGROUND-COLOR: #F6F6EB; border: 1px #CCCCCC solid }
                 .TABLE5           {BACKGROUND-COLOR: #FFFFCC; border: 3px #000000 double }
                 .TABLE50           {BACKGROUND-COLOR:#f1f1f1; border: 1px #CCCCCC solid }
                 .fontrouge        {COLOR: #FF0000}
                 DIV               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
                 P                 {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
                 TD                {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                 TR                {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px;  COLOR: #000000}
                 FORM              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 10px}
                 INPUT             {BORDER-TOP-COLOR: #000000; BORDER-LEFT-COLOR: #000000; BORDER-RIGHT-COLOR: #000000; BORDER-BOTTOM-COLOR: #000000; BORDER-TOP-WIDTH: 1px; BORDER-LEFT-WIDTH: 1px; FONT-SIZE: 10px; BORDER-BOTTOM-WIDTH: 1px; FONT-FAMILY: Verdana,Helvetica; BORDER-RIGHT-WIDTH: 1px}
                 A:link            {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                 A:active          {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                 A:visited         {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline}
                 A:hover           {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline }
                 -->
                 </STYLE>
</head>

<body leftmargin="0" topmargin="10" marginwidth="0" marginheight="0">
<br>

   <table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE50"><tr><td align='center' style="padding:8px; FONT-SIZE:11px; border:1px #CCCCCC dotted">
   <b><?php print strtoupper(INTERFACEZ);?></b>
   </td></tr></table>

<br>

<table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE100"><tr><td>
<div align="left">
<?php print INT53;?><br>
<img src="../im/fleche_right.gif"> <?php print INT19;?>.<br>
<img src="../im/fleche_right.gif"> <?php print INT20;?>.<br>
<img src="../im/fleche_right.gif"> <?php print INT21;?><br>
<img src="../im/fleche_right.gif"> <?php print INT309;?><br>
<img src="../im/fleche_right.gif"> <?php print INT321;?>.<br>
<img src="../im/fleche_right.gif"> <?php print INT23;?>
</div>
</td></tr></table>

<br>

<?php
if(!empty($row['users_share_note'])) {
$messageFrom = str_replace("\r\n","<br>",$row['users_share_note']);
?>
    <table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE5"><tr><td>
    <div align="left">
    <?php print "<div align='left' class='fontrouge'><b>".strtoupper(MESSAGE_FROM)."</b></div><br>";?>
    <?php print "<div align='left'>".$messageFrom."</div>";?>
    </div>
    </td></tr>
    </table>
    <br>
<?php 
}
?>

<?php print $message20;?>

<?php 
if($payedZ=='no' AND $row['users_devis']=="") {
    print '<br>';
    print '<div align="center">';
    print '<form target="_top" method="POST" action="../mijn_account.php?blg='.$_GET['nic'].'&c=add">';
    print '<input type="hidden" value="'.$emailZ.'" name="email">';
    print '<input type="hidden" value="'.$accountZ.'" name="account">';
//    print '<input type="submit" value="'.ADD_ORDER.'">';
    print '</form>';
    print '</div>';
    
    if($paymentsDesactive=="non") {
    	print '<div align="center">';
    	print '<form target="_top" method="POST" action="../direct_betalen.php?nic='.$_GET['nic'].'">';
    	print '<input type="submit" value="'.DIRECT_PAYMENT.'">';
    	print '</form>';
    	print '</div>';
    }
}
?>
<br>

<table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="10" cellspacing="0" class="TABLE100">
  <tr>
    <td valign="top" align="center">
     <table width="100%" border="0" cellpadding="2" cellspacing="0">
     <tr>
     <td><?php print INT54;?>: <span class="fontrouge"><?php print $_GET['nic'];?></span></td>
     </tr>
      <tr>
     <td><b><?php print INT310;?></b>: <span class="fontrouge"><?php print $row['users_password'];?></span></td>
     </tr>
     <tr>
     <td><b>E-mail</b>: <?php print $row['users_email'];?></td>
     </tr>
     <tr>
     <td>
      <?php 
       
		print "<div class='TABLE50' style='padding:5px'>";
			print "<b>".INT311."</b><br>";
			($row['users_province']=="autre" OR $row['users_province']=="")? $prov="" : $prov=$row['users_province']."<br>";
			print $row['users_lastname']." ".$row['users_firstname']."<br>";
			print $row['users_address']."<br>";
			if(!empty($row['users_surburb'])) {print $row['users_surburb']."<br>";}
			print $row['users_zip'].", ".$row['users_city']."<br>";
			print $prov;
			print $row['users_country']."<br>";
			if(!empty($row['users_telephone'])) print TEL.": ".$row['users_telephone']."<br>";
			if(!empty($row['users_fax'])) print FAX.": ".$row['users_fax'];
		print "</div>";
		print "<br>";
	  
	   
		print "<div class='TABLE50' style='padding:5px'>";
			print "<b>".INT57."</b><br>";
			$Adrfact = explode("|",$row['users_facture_adresse']);
			for($i=0; $i<7; $i++) {
			     if($Adrfact[$i]!=="") {
			        print ($i==4)? $Adrfact[$i].", " : $Adrfact[$i]."<br>";
			     }
			}
     		 
     		if($Adrfact[7]!=="") print NO_TVA." : ".$Adrfact[7];
	 	print "</div>";
	 	print "<br>";
     ?>
     </td>
     </tr>
     <tr>
     <td valign="top"><img src="../im/zzz_noir.gif" width="100%" height="1"></td>
     </tr>
     <tr>
     <td>
     <b><?php print INT29;?></b>: <?php print $modePayment;?>
     </td>
     </tr>
     <tr>
     <td>
      <b><?php print INT27;?></b>: <?php ($row['users_payed'] == "no")? print $row['users_total_to_pay'].$symbolDevise : print "<s>".$row['users_total_to_pay']."</s>$symbolDevise (<i>".INT28."</i>)";?>
     </td>
     </tr>
     <tr>
     <td>
<?php
      
     if($row['users_payed'] == 'no') {
        print "<b>".INT30."</b>: <span class='fontrouge'><b>".strtoupper(INT56)."</b></span>";
     }
     else {
            print "<b>".INT30."</b>: <span class='fontrouge'><b>".strtoupper(INT55)."</b></span>";
     }
?>
     </td>
     </tr>

     <tr>
     <td>
     <?php
      
           ($row['users_payed'] == "no"  AND $row['users_confirm'] == "yes")?
           print $infoBancaire
           :
           print "";
     ?>
     </td>
     </tr>
     <tr>
     <td valign="top"><img src="../im/zzz_noir.gif" width="100%" height="1"></td>
     </tr>
     <tr>
     <td>
     <?php
      
     if($row['users_confirm'] == 'no') {
        print "<b>".INT33."</b>:<br>";
        print INT34.": <span class='fontrouge'><b>".INT312."</b></span><br>";
        if($row['users_payment']!=="cc" AND $row['users_payment']!=="mb" AND $row['users_payment']!=="pp" AND $row['users_payment']!=="eu") {
            print "<br><div style='background:#000000; color:#FFFFFF; padding:5px;'><b>".INT35."</b></div><br>";
            print "<table border='0' cellpadding='2' cellspacing='0'>";
            print "<tr>";
            print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
            print "<td>";
            print "<input type='submit' name='confirm' value='".INT4."'>";
            print "<input type='hidden' name='nic' value='".$_GET['nic']."'>";
            print "<input type='hidden' name='l' value='".$_GET['l']."'>";
            print "</td>";
            print "</form>";
            print "</tr>";
            print "</table>";
         }
     }
     else {
           	if($row['users_payed'] == "no") {
				$rappelCheque = "<img src='../im/fleche_right.gif'> ".INT100;
				$rappelMandat = "<img src='../im/fleche_right.gif'> ".INT100;
				$rappelPaypal = "<img src='../im/fleche_right.gif'> ".INT315;
				$rappelMb = "<img src='../im/fleche_right.gif'> ".INT315;
				$rappelCarte = "<img src='../im/fleche_right.gif'> ".INT315;
				$rappelVirement = "<img src='../im/fleche_right.gif'> ".INT200;
				$rappelTraite = "<img src='../im/fleche_right.gif'> ".INT100;
				
				$rappelWestern = "<table border='0' cellpadding='0' cellspacing = '4' class='TABLE4'>";
				$rappelWestern.= "<tr><td>".INT1500."</td></tr>";
				$rappelWestern.= "</table>";
				
				print "<b>".INT33."</b>:<br>";
				print INT34.": <span class='fontrouge'><b>".INT313."</b><br>";
				print INT36."</span><br>";
				if($row['users_payment']!=="BL") {
				print INT36a." ".strtolower($bancaireOuPostale)." ".INT36b."<br>";
				}
				print "<br><b><u>".IMPORTANT."</u></b> :<div style='padding:3px'></div>";
				if($row['users_payment']=="ch") print $rappelCheque;
				if($row['users_payment']=="vi") print $rappelVirement;
				if($row['users_payment']=="ma") print $rappelMandat;
				if($row['users_payment']=="pp") print $rappelPaypal;
				if($row['users_payment']=="mb") print $rappelMb;
				if($row['users_payment']=="cc") print $rappelCarte;
				if($row['users_payment']=="wu") print $rappelWestern;
				if($row['users_payment']=="ss") print "";
				if($row['users_payment']=="ts") print "";
				if($row['users_payment']=="tb") print $rappelTraite;
				if($row['users_payment']=="eu") print "";
        if($row['users_payment']=="pn") print "Pay.nl";
				
				print "<br><img src='../im/fleche_right.gif'> ".INT37." ".$modePayment." ".INT37a."<br>";
				print "<img src='../im/fleche_right.gif'> ".INT39."<br>";
				print "<img src='../im/fleche_right.gif'> ".INT40;
           	}
           	else {
                        print "<b>".INT33."</b>:<br>";
                        print "<img src='../im/fleche_right.gif'> ".INT316.": <span class='fontrouge'><b>".INT313."</b></span><br>";
                        ##print "<img src='../im/fleche_right.gif'> ".INT317.": <span class='fontrouge'><b>".EN_TRAITEMENT."</b></span>";
                        print "<br>";
                        
                        if($row['users_ready']=="no" AND $row['users_ready']="no") {
							$backColor1="style='background:#FFCC00; border:3px #CC0000 double;'";
							$backColor2="style='background:#f1f1f1;'";
							$backColor3="style='background:#f1f1f1;'";
							$fontColor1="style='color:#000000; font-weight:bold;'";
							$fontColor2="style='color:#999999;'";
							$fontColor3="style='color:#999999;'";
							$smiley1="<div style='padding:3px'><img src='../im/inProcess.gif' height='20'></div>";
							$smiley2="";
							$smiley3="";
						}
                        if($row['users_ready']=="yes") {
							$backColor2="style='background:#FFCC00; border:3px #CC0000 double;'";
							$backColor1="style='background:#f1f1f1;'";
							$backColor3="style='background:#f1f1f1;'";
							$fontColor1="style='color:#999999;'";
							$fontColor2="style='color:#000000; font-weight:bold;'";
							$fontColor3="style='color:#999999;'";
							$smiley1="";
							$smiley2="<div style='padding:3px'><img src='../im/inOk.gif' height='20'></div>";
							$smiley3="";
						}
                        if($row['users_statut']=="yes") {
							$backColor3="style='background:#FFCC00; border:3px #CC0000 double;'";
							$backColor1="style='background:#f1f1f1;'";
							$backColor2="style='background:#f1f1f1;'";
							$fontColor1="style='color:#999999;'";
							$fontColor2="style='color:#999999;'";
							$fontColor3="style='color:#000000; font-weight:bold;'";
							$smiley1="";
							$smiley2="";
							$smiley3="<div style='padding:3px'><img src='../im/inReady.gif' height='20'></div>";
						}

                        
						print "<table border='0' width='100%' cellpadding='7' cellspacing='0' style='border:1px #CCCCCC solid'><tr>";
                        print "<td colspan='3' align='center' style='background:#f1f1f1'>";
                        print "<div style='font-size:12px;'><b>".INT317."</b></div>";
                        print "</td>";
                        print "</tr><tr>";
                        print "<td width='33%' align='center' valign='top' ".$backColor1.">";
                        print "<div ".$fontColor1.">".PREP1."</div>";
						print $smiley1;
                        print "</td>";
                        print "<td align='center' valign='top' ".$backColor2.">";
                        print "<div ".$fontColor2.">".PREP2."</div>";
						print $smiley2;
                        print "</td>";
                        print "<td width='33%' align='center' valign='top' ".$backColor3.">";
                        print "<div ".$fontColor3.">".PREP3."</div>";
						print $smiley3;
                        print "</td>";
                        print "</tr></table>";
           }
     }
     ?>
     </td>
     </tr>

<?php
      
     if($row['users_payed'] == 'yes' AND !empty($row['users_facture_adresse'])) {
   
   
   
     
        $FactureNum = str_replace("||","",$row['users_fact_num']);
        print "<tr><td valign='top'><img src='../im/zzz_noir.gif' width='100%' height='1'></td></tr>";
        print "<tr><td>";
        print "<b>".strtoupper(INT67)." $FactureNum</b><br>";
         
         
         
        print "<table border='0' width='100%' cellpadding='2' cellspacing='0'><tr>";
        print "<form action='factuur.php' method='GET' target='_blank'>";
        print "<input type='hidden' name='nic' value='".$_GET['nic']."'>";
        print "<input type='hidden' name='l' value='".$_GET['l']."'>";
        print "<input type='hidden' name='target' value='impression'>";
        print "<td width='170'>
                <input type='submit' value='".INT319."'>";
        print "</td>";
        print "<td align='left'>";
        print "<a href='factuur_pdf.php?id=".$_GET['nic']."&l=".$_GET['l']."' target='_blank'><img src='logo_pdf.gif' border='0' title='PDF factuur - facture - invoice'></a>";
        print "</td>";
        print "</form></tr></table>";
        
        print "".INT45." ".$row['users_email']."<br>";

        print "<table border='0' cellpadding='2' cellspacing='0'><tr>";
        print "<form action='factuur.php' method='GET' target='_blank'>";
        print "<input type='hidden' name='nic' value='".$_GET['nic']."'>";
        print "<input type='hidden' name='target' value='mail'>";
        print "<input type='hidden' name='l' value='".$_GET['l']."'>";
        print "<td>";
        print "<input type='submit' value='".INT46."'>";
        print "</td>";
        print "</form>";
        print "</tr></table>";
         
        print "</td></tr>";
     }
     if($row['users_payed'] == 'yes' AND empty($row['users_facture_adresse'])) {
        print "<tr><td valign='top'><img src='../im/zzz_noir.gif' width='100%' height='1'></td></tr>";
        print "<tr><td>";
        print "<b>FACTURE</b>:<br>";
        print INT48;
         
        print "<table border='0' cellpadding='1' cellspacing='0'><tr>";
        print "<form action='".$_SERVER['PHP_SELF']."' method='GET' name='form1' onsubmit='return check_form()'>";
        print "<td>".INT318."&nbsp;</td><td><input type='text' name='nomE' size='40'></td></tr><tr>";
        print "<td>".INT49."&nbsp;</td><td><input type='text' name='adresseE' size='40'></td></tr><tr>";
        print "<td>".INT50."&nbsp;</td><td><input type='text' name='codeE' size='10'></td></tr><tr>";
        print "<td>".INT51."&nbsp;</td><td><input type='text' name='villeE' size='40'></td></tr><tr>";
        print "<td>".INT52."&nbsp;</td><td><input type='text' name='paysE' size='40'></td></tr><tr>";
        print "<td>E-mail&nbsp;</td><td><input type='text' name='emailE' size='40'>";
        print "<br><i>".INT2000." E-mail = ".$row['users_email']."</i></td></tr><tr>";
        print "<td colspan='2'><input type='submit' name='adresse_facture' value='".INT301."'></td>";
        print "<input type='hidden' name='nic' value='".$row['users_nic']."'>";
        print "<input type='hidden' name='l' value='".$_GET['l']."'>";
        print "</form>";
        print "</tr></table>";

        print "</td></tr>";
     }

?>
     </table>
    </td>
  </tr>
</table>

<?php

if($row['users_payed'] == 'yes') {
$a = 0;
	foreach ($id_product as $item) {
		$queryRef2 = mysql_query("SELECT * FROM products WHERE products_id = '".$item."'");
		$rowRef2 = mysql_fetch_array($queryRef2);
		if($rowRef2['products_download'] == "yes") {
			$a=$a+1;
		}
	}

if($a>0) {
print "<br>";
print "<table width='".$modWidth."' align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE4'><tr><td>";
print "<div align='center' style='font-size:15px;'><b>".strtoupper(INT32A)."</b>";

 
$split = explode(",",$row['users_products']);
foreach ($split as $item) {
   $check2 = explode("+",$item);
   $query100ww = mysql_query("SELECT products_download 
   								FROM products 
								WHERE products_id='".$check2[0]."' 
								AND products_download='yes'");
   if(mysql_num_rows($query100ww)>0) {
   
      if(isset($check2[6]) AND $check2[6]!=="") {
          
        					$_optZ = explode("|",$check2[6]);
								$lastArray = $_optZ[count($_optZ)-1];
								if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
								$_optZ = implode("|",$_optZ);
								$check2[6] = $_optZ;
		 
        $optionsRequest = mysql_query("SELECT products_option 
		 								FROM products_id_to_products_options_id 
										WHERE products_id = '".$check2[0]."' 
										AND products_option LIKE '%".$check2[6]."::%'
										");
          
         if(mysql_num_rows($optionsRequest)>0) {
            while($optionsResult = mysql_fetch_array($optionsRequest)) {
               $explodeProductsOption = explode(",", $optionsResult['products_option']);
               foreach($explodeProductsOption AS $item2) {
                  $OptionUrl = explode("::",$item2);
                   
                  if($check2[6] == $OptionUrl[0]) {
                   
                     if(isset($OptionUrl[3]) AND $OptionUrl[3]!=="") {
                        $url = str_replace("=","/", $OptionUrl[3]);
                        $idOption[] = array("_ID_"=>$check2[0], "_NAME_"=>get_product_name($check2[0],$_GET['l']), "_OPTION_"=>$check2[6], "_URL_"=>$url);
                     }
                     else {
                        $queryRefs = mysql_query("SELECT products_download_name FROM products WHERE products_id = '".$check2[0]."'");
                        $rowRefs = mysql_fetch_array($queryRefs);
                        if($rowRefs['products_download_name']=="") $url = ""; else $url = $rowRefs['products_download_name'];
                        $idOption[] = array("_ID_"=>$check2[0], "_NAME_"=>get_product_name($check2[0],$_GET['l']), "_OPTION_"=>$check2[6], "_URL_"=>$url);
                     }
                  }
               }
            }
         }
         else {
                     $queryRefs = mysql_query("SELECT products_download_name FROM products WHERE products_id = '".$check2[0]."'");
                     $rowRefs = mysql_fetch_array($queryRefs);
                     if($rowRefs['products_download_name']=="") $url = ""; else $url = $rowRefs['products_download_name'];
                     $idOption[] = array("_ID_"=>$check2[0], "_NAME_"=>get_product_name($check2[0],$_GET['l']), "_OPTION_"=>$check2[6], "_URL_"=>$url);
         }
      }
      else {
                     $queryRefs = mysql_query("SELECT products_download_name FROM products WHERE products_id = '".$check2[0]."'");
                     $rowRefs = mysql_fetch_array($queryRefs);
                     if($rowRefs['products_download_name']=="") $url = ""; else $url = $rowRefs['products_download_name'];
                     $idOption[] = array("_ID_"=>$check2[0], "_NAME_"=>get_product_name($check2[0],$_GET['l']), "_OPTION_"=>$check2[6], "_URL_"=>$url);
      }
   }
}

print "<br><br>";

foreach($idOption as $item) {
   ##print "<a href='".$item['_URL_']."'>".$item['_NAME_']."</a><br>";
   ##print "<a href='login.php?nic=".$_GET['nic']."&l=".$_GET['l']."'>".$item['_NAME_']."</a><br>";
   
    
   if(isset($item['_URL_']) AND $item['_URL_']!=="") {
   $crypte = Crypte($item['_URL_'],$Cle);
       print "<form method='POST' action='login.php?nic=".$_GET['nic']."&l=".$_GET['l']."' target='_blank'>";
       print "<input type='hidden' name='action' value='download'>";
       print "<input type='hidden' name='prod' value='".$crypte."'>";
       print "<input type='hidden' name='nicZ' value='".$_GET['nic']."'>";
       print "<input type='hidden' name='lZ' value='".$_GET['l']."'>";
       print "<div align='center'>".$item['_NAME_']."&nbsp;-&nbsp;<input type='submit' value='".TELECHARGER."'></div>";
       print "</form>";
   }
}

/*
print "<br><br>";
         foreach($ref_product as $item) {
            $queryRef = mysql_query("SELECT * FROM products WHERE products_ref = '".$item."'");
            $rowRef = mysql_fetch_array($queryRef);
            if($rowRef['products_download'] == "yes" AND !empty($rowRef['products_download_name'])) {
              print "<a href='".$rowRef['products_download_name']."'>".$rowRef['products_name_'.$_GET['l']]."</a><br>";
            }
         }
*/
print "</div>
</td></tr></table>";
}
}

?>

<br>
</body>
</html>
