<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$n = 650;
$c = "";
$c2 = "";


// function Format date
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

// function remove slash
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}


if(isset($_POST['ToAction']) AND $_POST['ToAction']=='send') {
   if(isset($_POST['what']) AND $_POST['what']==ANNULER) {
      header("Location: korting_code_versturen.php");
      exit;
   }

    $_store = str_replace("&#146;","'",$store_company);
    $messageToSent2 = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
    if(!empty($address_autre)) $messageToSent2.= str_replace("<br>","\r\n",$address_autre)."\r\n";
    $messageToSent2.= BONJOUR.",\r\n\r\n";
    
   if(isset($_POST['messagePerso']) AND $_POST['messagePerso']!=="") {
      $messageToSent2.= $_POST['messagePerso']."\r\n\r\n";
      $mainMessage = BONJOUR.",\r\n\r\n".$_POST['messagePerso']."\r\n\r\n".$_POST['ToMessage'];
   }
   else {
      $mainMessage = BONJOUR.",\r\n\r\n".$_POST['ToMessage'];
   }

   $_POST['ToMessage'] = $messageToSent2.$_POST['ToMessage'];
   
   $subjectW = PROMO_A_NE_PAS_MANQUER." ".$store_name." - http://".$www2.$domaineFull;
   $messageW = $_POST['ToMessage'];
   $fromW = $mailInfo;
 
  
   $explodeEmails = explode(',', $_POST['ToEmail']);
   $_i = count($explodeEmails)+1;
   foreach($explodeEmails AS $items) {
        mail($items, $subjectW, rep_slash($messageW),
        "Return-Path: $fromW\r\n"
        ."From: $fromW\r\n"
        ."Reply-To: $fromW\r\n"
        ."X-Mailer: PHP/" . phpversion());
   
       $searchClientQuery = mysql_query("SELECT users_pro_password FROM users_pro WHERE users_pro_email='".$items."'") or die (mysql_error());
       if(mysql_num_rows($searchClientQuery) > 0) {
          $searchClientResult = mysql_fetch_array($searchClientQuery);
          $clientAccount = $searchClientResult['users_pro_password'];
       }
       else {
          $clientAccount = "..";
       }
    
       $_i = $_i-1;
       $upateBddQuery1 = mysql_query("SELECT code_promo_note FROM code_promo WHERE code_promo='".$_POST['ToCode']."'") or die (mysql_error());
       $upateBddResult1 = mysql_fetch_array($upateBddQuery1);
       
       $insertDate = dateFr(date("Y-m-d"),$_SESSION['lang']);
       if($upateBddResult1['code_promo_note']=='') $saut=""; else $saut="\r\n\r\n";
       $insertSend = $_i.". ".$insertDate."\r\n".ENVOYE_A." : ".str_replace(',',', ',$items)."\r\n".COMPTE_CLIENT." : ".$clientAccount.$saut.$upateBddResult1['code_promo_note'];
       $upateBddQuery2 = mysql_query("UPDATE code_promo SET code_promo_note='".$insertSend."' WHERE code_promo='".$_POST['ToCode']."'") or die (mysql_error());
   }

	 
	$toW1 = $mailInfo;
	$fromW1 = $mailInfo;
	$subjectW1 = CODE_ENVOYE_VIA_ADMIN." - http://".$www2.$domaineFull;
	$messageW1 = LE_MESSAGE_CI_DESSOUS_A_ETE_ENVOYE_A." : ".str_replace(",",", ",$_POST['ToEmail'])."\r\n\r\n";;
	$messageW1.= "--------------------------\r\n";
	$messageW1.= $mainMessage."\r\n";
	$messageW1.= "--------------------------\r\n";
   
	mail($toW1, $subjectW1, rep_slash($messageW1),
	"Return-Path: $fromW1\r\n"
	."From: $fromW1\r\n"
	."Reply-To: $fromW1\r\n"
	."X-Mailer: PHP/" . phpversion());
      
	$message = "<b>".CODE_DE_REDUCTION_A_ETE_ENVOYE_AVEC_SUCCES."</b></span>";
}

if(isset($_GET['nic']) AND $_GET['nic']=="") {
	$message = "<p>";
	$message.= "<div align='center' style='color:#CC0000; font-size:13px;'><b>".AUCUNE_COMMANDE."</b></div>";
	$message.= "<div align='center'><a href='javascript:history.back()'><span style='color:#CC0000; font-size:13px;'>".RETOUR."</span></a></div>";
	$message.= "</p><br>";
	$displayMainScreen = 1;
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A8;?></p>

<?php
 
$codeQuery = mysql_query("SELECT *, IF(code_promo_start <= CURDATE() AND code_promo_end >= CURDATE(), 'yes', 'no') as ord,
                           IF(code_promo_stat = 'prive' AND code_promo_enter > '0', 'no', 'yes') as ord2
                           FROM code_promo ORDER BY code_promo ASC") or die (mysql_error());

if(isset($message)) print "<table border='0' cellpadding='10' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align='center'>".$message."</td></tr></table><br>";

 
if(isset($_POST['email']) AND $_POST['email']!=="" AND isset($_POST['codeId']) AND count($_POST['codeId'])==1) {
   $displayMainScreen = 1;
   $_a = array(" ", "\r\n");
   $_b = array("", "");
   $_POST['email'] = trim(str_replace($_a,$_b,$_POST['email']));
   foreach($_POST['codeId'] AS $items) {
      $codeToSent[] = $items;
   }

   $codeToSentZ = implode(",",$codeToSent);
  

   print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
   print "<table border='0' cellpadding='10' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
   print "<td align='center'>";
   print ENVOYER_CODE." <b>".str_replace(",",", ",$codeToSentZ)."</b> >>> ".str_replace(",",", ",$_POST['email'])." ?<br>";
   print "<input type='hidden' name='emailToSentZ' value='".$_POST['email']."'>";
   print "<input type='hidden' name='codeToSentZ' value='".$codeToSentZ."'>";
   print "<br>";
   print "<input type='submit' class='knop' name='sent' value='".OUI."'>"; 
   print "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   print "<input type='submit' class='knop' name='sent' value='".NON."'>";
   print "</td>";
   print "</tr></table>";
   print "</form>";
}
 
if(isset($_POST['sent']) AND $_POST['sent']==OUI) {
  $displayMainScreen = 1;
   $code2Query = mysql_query("SELECT * FROM code_promo WHERE code_promo='".$_POST['codeToSentZ']."'") or die (mysql_error());
   $code2 = mysql_fetch_array($code2Query);
   
                                                $messageToSent = BENEFICIEZ." ".$code2['code_promo_reduction']." ".$code2['code_promo_type']." ".DE_REDUCTION_SUR." ".$store_name."\r\n";
                                                $messageToSent.= CODE_DE_REDUCTION." : ".$code2['code_promo']."\r\n";
                                                $messageToSent.= CETTE_OFFRE_EST_VALABLE_UNIQUEMENT." http://".$www2.$domaineFull;
   // 
   if($code2['code_promo_seuil'] > 0) {
                                                $messageToSent.= " ".POUR_TOUT_ACHAT_SUPERIEUR." ".$code2['code_promo_seuil']." ".$symbolDevise."\r\n";
   }
   else {
      if($code2['code_promo_items']=='') {
                                                $messageToSent.= " ".POUR_TOUT_ACHAT_DANS_LA_BOUTIQUE.".\r\n";
      }
      else {
                                                $messageToSent.= " ".POUR_LES_ARTICLES_INDIQUES.".\r\n";
      }
   }
 
   if($code2['code_promo_items']!=='') {
      $articlesSoumis = explode("|",$code2['code_promo_items']);
 
      $messageToSent.= "\r\n".ARTICLE." ".SOUMIS_A_LA_REMISE.":\r\n";
      foreach($articlesSoumis AS $itmes) {
         $articlesSoumisQuery = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_ref FROM products WHERE products_id='".$itmes."'") or die (mysql_error());
         $articlesSoumisResult = mysql_fetch_array($articlesSoumisQuery);
         $messageToSent.= "- ".$articlesSoumisResult['products_name_'.$_SESSION['lang']]."\r\n";
      }
      $messageToSent.= "\r\n";
   }
   if($code2['code_promo_stat']=='prive') {
      $messageToSent.= CE_CODE_EST_A_USAGE_UNIQUE.".\r\n";
   }
   // niet cumuleerbaar
                                                $messageToSent.= OFFRE_NON_CUMULABLE.".\r\n\r\n";
   // geldig tot
   if($code2['code_promo_end']!=='2020-01-01') {
      $messageToSent.= VALIDE_JUSQUAU." ".dateFr($code2['code_promo_end'],$_SESSION['lang'])."\r\n\r\n";
      $messageToSent.= CORDIALEMENT.".";
   }


  print "<br>";
  print "<table border='0' cellpadding='10' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
  print "<b>".ENVOYER_CODE."</b> ".$code2['code_promo']."<br>";
  print "<b>".A."</b> ".str_replace(",",", ",$_POST['emailToSentZ'])."<br>";
  print "<b>".MESSAGE."</b><br><br>";
  print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
  print "<input type='hidden' name='ToEmail' value='".$_POST['emailToSentZ']."'>";
  print "<input type='hidden' name='ToCode' value='".$code2['code_promo']."'>";
  print "<input type='hidden' name='ToMessage' value='".$messageToSent."'>";
  print "<input type='hidden' name='ToAction' value='send'>";
  print "<div style='background-color:#FFFFFF; padding:5px; border:#CCCCCC 1px solid;'>";
       $_store = str_replace("&#146;","'",$store_company);
       print $_store."<br>".$address_street."<br>".$address_cp." - ".$address_city."<br>".$address_country."<br>";
       if(!empty($address_autre)) print $address_autre."<br>";
       print BONJOUR.",<br><br>";
  print str_replace("\r\n","<br>",$messageToSent);
  print "</div>";
  print "<br>";
  print MESSAGE_PERSO."&nbsp(1)<br>";
  print "<textarea cols='100' rows='6' class='vullen' name='messagePerso' value=''></textarea>";
  print "<br>(1)<br>";

  print "- ".FORMAY_TEXTE;
  print "<br>";
  print "- ".CE_MESSAGE_PERSO_SERA_AJOUTE;
 
  print "<br><br>";
  print "<div align='center'>";
  print "<input type='submit' class='knop' name='what' value='".ENVOYEZ."'>";
  print "&nbsp;";
  print "<input type='submit' class='knop' name='what' value='".ANNULER."'>";
  print "</div>";
  print "</form>";
  print "</td></tr></table><br><br><br><br>";
}

 

//----------
// 1 - 2 - 3
//----------
if(!isset($displayMainScreen)) {
//print_r($_POST);
if($_POST AND !isset($_POST['ToMessage']) AND !isset($_POST['sent'])) {
   if(!isset($_POST["codeId"])) print "<div align='center' style='background-color:#ffff; color:#cc0000; padding:3px'>&bull;&nbsp;<b>".CHOISIR_CODE."</b></div>";
   if(!isset($_POST["email"]) OR $_POST["email"]=="") print "<div align='center' style='background-color:#ffffff; color:#CC0000; padding:3px'>&bull;&nbsp;<b>".PLEASE_ENTRER_EMAIL."</b></div>";
   if(isset($_POST['codeId']) AND count($_POST['codeId']) > 1) print "<div align='center' style='background-color:#FFFFFF; color:#CC0000; padding:3px'>&bull;&nbsp;<b>".SELECT_UNSEUL_CODE."</b></div>";
}


print "<center><table border='0' cellpadding='5' cellspacing='0' width='700' class='TABLE'><tr><td><p align='center'>&bull;&nbsp;<a href='korting_code_toevoegen.php'>".AJOUTER_CODE_DE_REDUCTION."</a></p></td></tr></table>";
print "<br>";
print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<table border='0' cellpadding='0' cellspacing='0' align='center' width='700'><tr><td>";

 
print "<p style='background-color:#FFFF00; border:1px #FFFFFF solid;'><b>&nbsp;1&nbsp;</span></b> - ".SELECTIONNER_CODE."</span></p>";

print "<table border='0'  cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr bgcolor='#FFFFFF'>";
        print "<td align='center'>&nbsp;</td>";
        print "<td align='center' valign=top><b>".CODE."</b></td>";
        print "<td width='80' align='left' valign=top><b>".A10."</b></td>";
        print "<td align='left' valign=top><b>".A11."</b></td>";
        print "<td align='left' valign=top><b>".A12."</b></td>";
        print "<td align='left' valign=top><b>".A13."</b></td>";
        print "<td align='left' valign=top><b>".A20."</b></td>";
        print "<td align='left' valign=top><b>".A21."</b></td>";
        print "<td align='left' valign=top><b>".IDARTICLE."</b></td>";
        print "<td align='left' valign=top>&nbsp;</td>";

print "</tr>";

$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=article','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
$stockLev = $openLeg."<img border=0 src=im/help.png align=absmiddle></a>";

$i = 0;
while ($code = mysql_fetch_array($codeQuery)) {
               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
               $i = $i+1;

 
               unset($vv);
               $dateMaxE = explode("-",$code['code_promo_end']);
               if(count($dateMaxE) > 1) $dateMax = mktime(0,0,0,$dateMaxE[1],$dateMaxE[2],$dateMaxE[0]);
               
               $v = ($dateMax < $today)? 0 : 1;
               $v1 = ($code['code_promo_stat']=="prive" AND $code['code_promo_enter']>0)? 0 : 1;
               
               $vv = $v+$v1;
               if($vv!==2) {
                  $status3 = "&nbsp;<img src='im/no_stock.gif' title='".EXPIRE."'>";
               }
               else {
                  $status3 = "&nbsp;<img src='im/val.gif' title='".ACTIVEV."'>";
               }
  
              if(isset($_POST['codeId']) AND count($_POST['codeId']>0)) {
                     if(in_array($code['code_promo'],$_POST['codeId'])) $chek="checked"; else $chek="";
               }
               else {
                  $chek = "";
               }
   
              $openLeg2 = "<a href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=codeNote&code=".$code['code_promo']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
              $stockLev2 = $openLeg2.LIRE."</a>";

    
                 print "<tr bgcolor='".$c."'>";
                           if($code['ord']=='yes' AND $code['ord2']=='yes') {
	                        print "<td>";
                           print "<input type='checkbox' name='codeId[".$i."]' value='".$code['code_promo']."' ".$chek.">";
                           print "</td>";
 
	                        print "<td align='left'>".$code['code_promo']."</td>";
  
	                        print "<td align='left'>";
	                        $selZ = ($code['code_promo_type'] == "%")? "%" : $symbolDevise;
	                        print $code['code_promo_reduction']." ".$selZ;
                           print "</td>";
   
	                        print "<td align='left'>".$code['code_promo_seuil']." ".$symbolDevise."</td>";
 
	                        print "<td align='left'>".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$code['code_promo_start'])."</td>";
 
	                        print "<td align='left'>".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$code['code_promo_end'])." ".$status3."</td>";
 
	                        print "<td align='left'><b>".strtoupper($code['code_promo_stat'])."</b></td>";
 
	                        print "<td align='center'>".$code['code_promo_enter']."</td>";
 
	                        $stockLevZ = ($code['code_promo_items']=="")? "" : $stockLev;
	                        print "<td align='left'>".$code['code_promo_items']." ".$stockLevZ."</td>";
 
                           print "<td align='left'><a href='korting_code_printen.php?cert=".$code['code_promo']."&l=".$_SESSION['lang']."' target='_blank'><img src='../im/print.gif' title='".IMPRIMER."' border='0'></a></td>";
 
                           if($code['code_promo_note']=="") {
	                          print "<td align='center'>--</td>"; 
                           }
                           else {
	                          print "<td align='left'>";

                             print "<div align='right'>".$stockLev2."</div>";
                             print "</td>";
	                        }
                           }
                       print "</tr>";
                }
print "</table>";

 
$emailU="";

 
if(isset($_GET['nic']) AND $_GET['nic']!=="") {
     $val = explode(",",$_GET['nic']);
     foreach($val AS $items) {
        $queryd = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password='".$items."'");
        while ($rowd = mysql_fetch_array($queryd)) {
           $emals[] = $rowd['users_pro_email'];
        }
     }
     $emailU = implode(", ",$emals);
}

 
if(isset($_GET['usersEmail']) AND $_GET['usersEmail']!=="") $emailU=$_GET['usersEmail'];
print "<p style='background-color:#FFFF00; border:1px #FFFFFF solid;'><b>&nbsp;2&nbsp;</span></b> - ".ENTRER_EMAIL."<span></p>";
print "<table border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'>";
print "<tr bgcolor='#FFFFFF'>";
print "<td align='left'><b>E-mail(s)</b></td>";
print "</tr><tr>";
if(isset($_POST['email']) AND isset($_POST['email'])!=="") $emailZ = $_POST['email']; else $emailZ="";
print "<td align='left'><textarea cols='80' rows='8' name='email' value='".$emailZ."'>".$emailU."</textarea></td>";
print "</tr></table>";
print "<p>".NOTE."</p>";


 
print "<p style='background-color:#FFFF00; border:1px #FFFFFF solid;'><b>&nbsp;3&nbsp;</span></b> - ".CLIQUEZ_SUR."<span></p>";
print "</td></tr></table>";
print "<br>";
print "<table border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td><center><input type='submit' class='knop' value='".CONTINUER."'></tr></td></table>";
print "</form>";
}
?>
<br><br><br>
</body>
</html>

