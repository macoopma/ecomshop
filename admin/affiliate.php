<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

 
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Niet toegelaten</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c = "";
if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}

if(!isset($_GET['orderf']) OR empty($_GET['orderf'])) $_GET['orderf'] = "aff_id";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
   $searchReq  = "AND (";
   $searchReq  .= "aff_number like '%".$_POST['searchClient']."%'
                  OR aff_pass like '%".$_POST['searchClient']."%'
                  OR aff_nom like '%".$_POST['searchClient']."%'
                  OR aff_prenom like '%".$_POST['searchClient']."%'
                  OR aff_email like '%".$_POST['searchClient']."%'
                  OR aff_ville like '%".$_POST['searchClient']."%'
                  OR aff_zip like '%".$_POST['searchClient']."%'
                  OR aff_telephone like '%".$_POST['searchClient']."%'
                  OR aff_web like '%".$_POST['searchClient']."%'
                  OR aff_customer like '%".$_POST['searchClient']."%'
                  OR aff_company like '%".$_POST['searchClient']."%'";
   $searchReq  .= ")";
}
else {
   $searchReq = "";
}
 
function rep_slash($rem) {
	$rem = stripslashes($rem);
	$rem = str_replace("&#146;","'",$rem);
	return $rem;
}

if(isset($_GET['sendEmail']) AND $_GET['sendEmail']=='yes') {
	$emailToAffiliateQuery = mysql_query("SELECT * FROM affiliation WHERE aff_number = '".$_GET['id']."'");
	$emailToAffiliateResult = mysql_fetch_array($emailToAffiliateQuery);
 

	$tox = $emailToAffiliateResult['aff_email'];
	$subjectx = A1Z." - ".$domaineFull;
	$fromx = $mailInfo;
	$_store = str_replace("&#146;","'",$store_company);
	$messageToSendx = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
	if(!empty($address_autre)) {
		$address_autre2 = str_replace("<br>","\r\n",$address_autre);
	    $messageToSendx .= $address_autre2."\r\n";
	}
	if(!empty($tel)) $messageToSendx .= TELEPHONE.": ".$tel."\r\n";
	if(!empty($fax)) $messageToSendx .= FAX.": ".$fax."\r\n";
	$messageToSendx .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailInfo."\r\n\r\n";
	$messageToSendx .= $emailToAffiliateResult['aff_prenom'].",\r\n\r\n";
	$messageToSendx .= ENREGISTRER_AU_PROGRAMME." http://".$www2.$domaineFull.".\r\n";
	$messageToSendx .= VOTRE_NUMERO_AFFILIE_EST." : ".$_GET['id']."\r\n";
	$messageToSendx .= VOTRE_PASS_EST." : ".$emailToAffiliateResult['aff_pass']."\r\n";
	$messageToSendx .= VOTRE_COM." : ".$emailToAffiliateResult['aff_com']."%\r\n\r\n";
	$messageToSendx .= CLIQUER_SUR." :\r\n";
	$messageToSendx .= "http://".$www2.$domaineFull."/affiliate_linkweb.php?numAff=".$_GET['id']."\r\n\r\n";
	$messageToSendx .= POUR_TOUTE_INFORMATION."\r\n";
	$messageToSendx .= LE_SERVICE_CLIENT."\r\n";
	$messageToSendx .= $mailInfo;
	   
	mail($tox, $subjectx, rep_slash($messageToSendx),
	"Return-Path: $fromx\r\n"
	."From: $fromx\r\n"
	."Reply-To: $fromx\r\n"
	."X-Mailer: PHP/" . phpversion());
	
	$messageAff = MOT_DE_PASSE_ENVOYE." ".$tox;
}

 
function details_affiliate($affiliate) {
	GLOBAL $messageAff;
	$viewAffiliate = mysql_query("SELECT * FROM affiliation WHERE aff_number = '".$affiliate."'");
	$viewAffiliateDisplay = mysql_fetch_array($viewAffiliate);
	print "<p align='center'><a href='affiliate.php?action=view&id=".$affiliate."&sendEmail=yes'>Verstuur de inschrijf e-mail opnieuw</a><br>";
	if(isset($messageAff)) { print "<div align='center' class='fontrouge'><b>".$messageAff."</b></div>";}
	print "</p>";
	print "<p>";
	print "<table border='0' width='700' align='center' cellspacing='0' cellpadding='3' class='TABLE'><tr bgcolor='#FFFFFF' height='30'>";
	print "<td align='center' colspan='2'><b>".A100." ".$affiliate."</b></td></tr><tr>";
	print "<td width=200>".A22."</td><td>".$viewAffiliateDisplay['aff_nom']."</td></tr><tr>";
	print "<td>".A23."</td><td>".$viewAffiliateDisplay['aff_prenom']."</td></tr><tr>";
	print "<td>".PASS."</td><td>".$viewAffiliateDisplay['aff_pass']."</td></tr><tr>";
	print "<td>".CLIENT."</td><td>".$viewAffiliateDisplay['aff_customer']."</td></tr><tr>";
	print "<td>Commissie</td><td><input type='text' size='5' class='vullen' name='amount_aff_amount' value='".$viewAffiliateDisplay['aff_com']."'>&nbsp;&nbsp;%</td></tr><tr>";
	print "<td>".A24."</td><td>".$viewAffiliateDisplay['aff_company']."</td></tr><tr>";
	print "<td>E-mail</td><td><a href='mailto:".$viewAffiliateDisplay['aff_email']."'>".$viewAffiliateDisplay['aff_email']."</a></td></tr><tr>";
	print "<td>".A26."</td><td>".$viewAffiliateDisplay['aff_adresse1']."</td></tr><tr>";
//	print "<td>".A26."</td><td>".$viewAffiliateDisplay['aff_adresse2']."</td></tr><tr>";
	print "<td>".A27."</td><td>".$viewAffiliateDisplay['aff_zip']."</td></tr><tr>";
	print "<td>".A40."</td><td>".$viewAffiliateDisplay['aff_ville']."</td></tr><tr>";
	print "<td>".A4."</td><td>".$viewAffiliateDisplay['aff_pays']."</td></tr><tr>";
	print "<td>Tel</td><td>".$viewAffiliateDisplay['aff_telephone']."</td></tr><tr>";
	print "<td>Website</td><td><a href='".$viewAffiliateDisplay['aff_web']."' target='_blank'>".$viewAffiliateDisplay['aff_web']."</a></td></tr><tr>";
	
// 	print "<td>".CHEQUE."</td><td>".$viewAffiliateDisplay['aff_cheque']."</td></tr><tr>";
	print "<td>Paypal e-mail adres</td><td>".$viewAffiliateDisplay['aff_paypal']."</td></tr><tr>";
	
	print "<td>".A30."</td><td>".$viewAffiliateDisplay['aff_banque']."</td></tr><tr>";
	print "<td>".A31."</td><td>".$viewAffiliateDisplay['aff_addresse_banque']."</td></tr><tr>";
	print "<td>IBAN</td><td>".$viewAffiliateDisplay['aff_titulaire']."</td></tr><tr>";
	print "<td>BIC</td><td>".$viewAffiliateDisplay['aff_rib']."</td></tr><tr>";
	print "<td valign=top>Nota</td><td>".$viewAffiliateDisplay['aff_observation']."</td></tr>";
	print "</table>";
	print "</p><br><br><br>";
	print "<input type='hidden' name='id_aff_id' value='".$affiliate."'>";
}
 

function return_total_com($affUser) {
	global $affiliateTop;
	$queryBalance = mysql_query("SELECT *
                              FROM users_orders
                              WHERE users_affiliate = '".$affUser."'
                              AND users_affiliate_payed = 'no'
                              AND users_confirm = 'yes'
                              AND users_payed = 'yes'
                              AND users_nic NOT LIKE 'TERUG%' 
                              AND users_refund = 'no'
                              ORDER BY users_date_added
                              ASC
                              ");
	$queryBalanceNum = mysql_num_rows($queryBalance);
	if($queryBalanceNum > 0) {
		while ($yoyo = mysql_fetch_array($queryBalance)) {
			$totalComBalance[] = $yoyo['users_affiliate_amount'];
		}
		$totalCom = array_sum($totalComBalance);
		if($totalCom >= $affiliateTop) {
			return array("<b><span class='fontrouge'>".sprintf("%0.2f",$totalCom)."</span></b>",sprintf("%0.2f",$totalCom));
		}
		else {
			return array("<b>".sprintf("%0.2f",$totalCom)."</b>",sprintf("%0.2f",$totalCom));
		}
	}
	else {
		return sprintf("%0.2f",0);
	}
}
 


if(isset($_GET['action2']) AND $_GET['action2']=="maj") {
	if(isset($_GET['com']) AND $_GET['com']=='no') {
		mysql_query("UPDATE affiliation SET aff_com = '".$_POST['amount_aff_amount']."' WHERE aff_number = '".$_POST['id_aff_id']."'");
	}
	else {
		mysql_query("UPDATE affiliation SET aff_com = '".$_POST['amount_aff_amount']."' WHERE aff_number = '".$_POST['id_aff_id']."'");
		$keys = array_keys($_POST['comPayed']);
		$values = array_values($_POST['comPayed']);
		$comPayedNb = count($_POST['comPayed']);
    	for($a=0; $a<=$comPayedNb-1; $a++) {
        	mysql_query("UPDATE users_orders SET users_affiliate_payed = '".$values[$a]."' WHERE users_nic = '".$keys[$a]."'");
		}
    }
}
 


if(isset($_POST['action']) AND $_POST['action']=='supprimer' AND isset($_POST['checkCom'])) {
   $ids = implode(', ', $_POST['checkCom']);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' width='700' class='TABLE'><tr><td>";
   $alertMessage.= "<center><span style='color:#FF0000'>".SUPPRIMER." ID# <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='delete'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<div align='center'><input type='submit' name='confirm' class='knop' value='".OUI."'> | <input type='submit' name='confirm' class='knop' value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table><br>";
   $ids2 = $_POST['checkCom'];
}
 
if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("DELETE FROM affiliation WHERE aff_id='".$rrArray[$i]."'");
      $idz[] = $rrArray[$i];
   }
   $ids = implode(', ', $idz);
   $s = (count($idz)>1 AND $_SESSION['lang']==1)? "s" : "";
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<span style='color:red'>".PANIER." ID <b>".$ids."</b> ".SUPPRIMEEEE.$s."</span><br><br>";
   $alertMessage.= "</td></tr></table><br>";
   header("Location: affiliate.php?alertMessage=".$alertMessage);
}

 
$hids = mysql_query("SELECT * FROM affiliation WHERE 1 ".$searchReq." ORDER BY ".$_GET['orderf']." ".$_GET['c1']."");			           
$resultcsNum = mysql_num_rows($hids);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
var checkflag = "true";
function check(field) {
	if (checkflag == "false") {
		for (i = 0; i < field.length; i++) {
		field[i].checked = true;}
		checkflag = "true";
		return "Tout décocher";
	}
	else {
		for (i = 0; i < field.length; i++) {
		field[i].checked = false; }
		checkflag = "false";
		return "Tout cocher";
	}
}
//  End -->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div  align="center" class="largeBold"><?php print A1;?></div>
<br>

<?php
 
print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<p align='center'><table border='0' cellpadding='3' cellspacing='0' align='center' width='700' class='TABLE'><tr><td>";
print "<center><input type='text' size='30' name='searchClient' class='vullen' value=''>";
print "&nbsp;&nbsp;&nbsp;<input type='submit' class='knop' value='".CHERCHER."'>";
print "&nbsp;&nbsp;&nbsp;";
print "<a href='javascript:void(0);' onClick=\"window.open('infos.php?from=affiliate','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=200,width=360,toolbar=no,scrollbars=no,resizable=yes');\">";
print "<img border=0 src=im/help.png align=absmiddle>";
print "</a>";
print "</tr></td></table>";
print "</form>";

if($resultcsNum >0) {
	print "<p><table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
	print "<div  align='center'>".DEFAUT_COM.": <b>".sprintf("%0.2f",$affiliateCom)."%</b>";
	print "&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;".A20.": <b>".sprintf("%0.2f",$affiliateTop)." ".$symbolDevise."</b></div>";
	print "</tr></td></table>";
	
 
if(isset($_GET['action']) AND $_GET['action'] == "delete") {
	print "<br><table border='0' cellpadding='3' cellspacing='0' align='center' width='700' class='TABLE'><tr><td><div align='center'>".A13." <b>".$_GET['id']."</b> ?</div>";
	print "<p align='center'>";
	print "<a href='".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&action=delete&confirm=yes'><b>".OUI."</b></a>";
	print "&nbsp;|&nbsp;";
	print "<a href='".$_SERVER['PHP_SELF']."'><b>".NON."</b></a>";
	print "</p></tr></td></table>";
	
	if((isset($_GET['action']) AND $_GET['action'] == "delete") AND (isset($_GET['confirm']) AND $_GET['confirm']=="yes")) {
		mysql_query("DELETE FROM affiliation WHERE  aff_number = '".$_GET['id']."'");
?>
	<script type="text/javascript">
	<!--
	document.location='affiliate.php';
	//-->
	</script>
<?php
	}
}

    if(isset($_GET['action2']) AND $_GET['action2']=="maj") {
        print "<p align='center' class='fontrouge'><b>".A34." ".$_GET['id']." ".A35."</b></p>";
    }

 
if(isset($alertMessage) OR (isset($_GET['alertMessage']) AND !empty($_GET['alertMessage']))) {
	if(isset($_GET['alertMessage'])) $alertMessage = $_GET['alertMessage'];
	print $alertMessage;
}

	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE'>";
	print "<tr bgcolor='#FFFFFF' height='35'>";
	print "<td align='center' class='fontgris'><input name='tout' type='checkbox' checked onClick='this.value=check(this.form);'></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_id&c1=".$_GET['c1']."'>ID</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_number&c1=".$_GET['c1']."'>".A100."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_pass&c1=".$_GET['c1']."'>".PASS."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_customer&c1=".$_GET['c1']."'>".CLIENT."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf= aff_web&c1=".$_GET['c1']."'>".A3."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_ville&c1=".$_GET['c1']."'>".A40."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=aff_pays&c1=".$_GET['c1']."'>".A4."</a></b></td>";
	print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf= aff_com&c1=".$_GET['c1']."'>COM</a></b></td>";
	print "<td align='left'><b>".MONTANT_DU."</b></td>";
	print "<td align='center'><b>".A120."</b></td>";
	print "<td width='25' align='center'>&nbsp;</td>";
	print "<td width='25' align='center'>&nbsp;</td>";
	print "</tr>";
	
	while ($myhid = mysql_fetch_array($hids)) {
		if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
		$totalToPay = return_total_com($myhid['aff_number']);
		$op = ($totalToPay[1] < $affiliateTop)? "<img src='im/noPassed.gif' title='OK'>" : "<span class='fontrouge'><b>".A21."</b></span>";
		if(isset($_POST['action']) AND $_POST['action']!=='' AND isset($_POST['checkCom']) AND in_array($myhid['aff_id'], $_POST['checkCom'])) $c="#FFCC00";
		print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
		print "<td bgcolor='#FFFFFF' align='center'><input type='checkbox' name='checkCom[]' value='".$myhid['aff_id']."' ".$selChecked."></td>";
		print "<td align='center'>".$myhid['aff_id']."</td>";
		print "<td align='left'>".$myhid['aff_number']."</td>";
		print "<td align='left'>".$myhid['aff_pass']."</td>";
		print "<td align='left'><a href='klant_fiche.php?action=view&id=".$myhid['aff_customer']."'>".$myhid['aff_customer']."</a></td>";
		print "<td align='left'>".$myhid['aff_web']."</td>";
		print "<td align='left'>".$myhid['aff_ville']."</td>";
		print "<td align='left'>".$myhid['aff_pays']."</td>";
		print "<td align='left' style='color:#000000'>".$myhid['aff_com']." %</td>";
		print "<td align='center'>".$totalToPay[0]."</td>";
		print "<td align='center'>".$op."</td>";
		print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?action=view&id=".$myhid['aff_number']."' style='background:none; text-decoration:none'><img src='im/details.gif' border='0' alt='".A7."' title='".A7."'></a></td>";
		print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?action=delete&id=".$myhid['aff_number']."' style='background:none; text-decoration:none'><img src='im/supprimer.gif' border='0' alt='".A8."' title='".A8."'></a></td>";
		print "</tr>";
	}
	print "</table>";
	
	if(!isset($hide) OR $hide!=='yes') {
		print "<p align='center'>";
		print "<select name='action'>";
		print "<option value=''> ".SELECT." </option>";
		print "<option value=''>--</option>";
		print "<option value='supprimer'>".SUPPRIMER."</option>";
		print "</select>";
		print "&nbsp;&nbsp;<input type='submit' value='  OK  ' class='knop'>";
		print "</p>";
	}
	print "</form>";
}
else {
	print "<br><br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr bgcolor='#FFFFFF'><td>";
	print "<p align='center' class='fontrouge'><b>".A9."</b></td>";
	print "</tr>";
	print "</table>";
	
	if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
		print "<p align='center'><a href='affiliate.php'><b>".BACK."</b></a></p>";
	}
}

 
if(isset($_GET['action']) and $_GET['action'] == "view") {

	$viewOrder = mysql_query("SELECT *
                              FROM users_orders
                              WHERE users_affiliate = '".$_GET['id']."'
                              AND users_confirm = 'yes'
                              AND users_payed = 'yes'
                              ORDER BY users_date_added
                              ASC
                              ");

	if(mysql_num_rows($viewOrder) > 0) {
	   	 
	   	print "<form name='form1' method='POST' action='affiliate.php?action=view&id=".$_GET['id']."&action2=maj'>";
	   	details_affiliate($_GET['id']);
		print "<table border='0' width='700' align='center' cellspacing='0' cellpadding='3' class='TABLE'><tr bgcolor='#FFFFFF'>";
		print "<td align='left'><b>NIC</b></td>";
		print "<td align='left'><b>".MONTANT."</b></td>";
		print "<td align='left'><b>COM</b></td>";
		print "<td align='left'><b>".A32."</b></td>";
		print "<td align='left'><b>Extra info</b></td>";
		print "</tr>";
		 
		while($viewOrderDisplay = mysql_fetch_array($viewOrder)) {
			if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFF";}
			$totalCom[] = $viewOrderDisplay['users_affiliate_amount'];
			if($viewOrderDisplay['users_affiliate_payed'] == "yes") {$totalComPayed[] = $viewOrderDisplay['users_affiliate_amount'];} else {$totalComPayed[] = 0;}
			
			$selPaye = ($viewOrderDisplay['users_affiliate_payed']=="yes")? "selected" : "";
			$selPaye1 = ($viewOrderDisplay['users_affiliate_payed']=="no")? "selected" : "";
			
			print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
			print "<td align='left'><a href='detail.php?id=".$viewOrderDisplay['users_id']."&from=aff&who=".$_GET['id']."'>".$viewOrderDisplay['users_nic']."</a></td>";
			print "<td align='left'>".$viewOrderDisplay['users_total_to_pay']."</td>";
			print "<td align='left'>".$viewOrderDisplay['users_affiliate_amount']."</td>";
			print "<td align='left'>";
				print "<select name='comPayed[".$viewOrderDisplay['users_nic']."]'>";
				print "<option value='yes' $selPaye>".OUI."</option>";
				print "<option value='no' $selPaye1>".NON."</option>";
				print "</select>";
			print "</td>";
			print ($viewOrderDisplay['users_refund']=="yes" OR preg_match("/\bTERUG\b/i", $viewOrderDisplay['users_nic']))? "<td align='center' class='fontrouge'>".COMMANDE_REMBOURSE."</td>" : "<td align='left'>--</td>";
			print "</tr>";
		}
	    print "<tr><td colspan='5' align='center'>";
	    print "<br><INPUT TYPE='submit' NAME='Submit' class='knop' VALUE='".A33."'>";
	    print "</td></tr>";
	    print "</table>";
	    print "</form>";
	    
	    if(isset($_GET['action2']) AND $_GET['action2']=="maj") {
	        print "<p align='center' class='fontrouge'><b>".A34." ".$_GET['id']." ".A35."</b></p>";
	    }
	
		$comDu = sprintf("%0.2f",array_sum($totalCom) - array_sum($totalComPayed));
		print "<p align='center'>";
		print A36.": ".sprintf("%0.2f",array_sum($totalCom))." ".$symbolDevise."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
		print A37.": ".sprintf("%0.2f",array_sum($totalComPayed))." ".$symbolDevise."&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		print A38.": ".$comDu." ".$symbolDevise;
	      
		$toPay = ($comDu >= $affiliateTop)? sprintf("%0.2f",$comDu)." $symbolDevise" : A39;
		print "<p align='center'>".A21." <b>".$toPay."</b></p>";
		print "</p>";
	}
	else {
 
		print "<form name='form1' method='POST' action='affiliate.php?action=view&id=".$_GET['id']."&action2=maj&com=no'>";
		details_affiliate($_GET['id'] );
		print "<p align='center'><b>".A200."</b></p>";
		print "<p align='center'><INPUT TYPE='submit' NAME='Submit' VALUE='".A33."' class='knop'></p>";
		print "</form>";
	}
}
?>
<br><br><br>
</body>
</html>

