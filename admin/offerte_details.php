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
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
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

if(isset($_POST['devisNumber'])) $_POST['id'] = $_POST['devisNumber'];
if(isset($_GET['id'])) $_POST['id'] = $_GET['id'];

 
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

 
function replace_ap($val) {
   $_val = str_replace("'","&#146;",$val);
   return $_val;
}

 
function voirCommande($numero) {
    $hidCom = mysql_query("SELECT users_id, users_devis FROM users_orders WHERE users_devis = '".$numero."'");
    $myhidComNum = mysql_num_rows($hidCom);
        if($myhidComNum > 0) {
            $myhidCom = mysql_fetch_array($hidCom);
            $voir = "&nbsp;&nbsp;[<a href='detail.php?id=".$myhidCom['users_id']."&from=devis&devisNo=".$myhidCom['users_devis']."' title='".A1503."'>".A1503."</a>]";
        return $voir;
        }
} 

$hid = mysql_query("SELECT *, TO_DAYS(NOW()) - TO_DAYS(devis_date_end) as diff FROM devis WHERE devis_number = '".$_POST['id']."'");
$myhid = mysql_fetch_array($hid);

$c="";
if($myhid['devis_shipping']==0) $messageAff = "<br><span style='padding:10px; background:#FFFFFF; border:#FF0000 2px solid;'><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle>Selecteer een leverings wijze<img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle></span><br><br>";

if(isset($_POST['action']) AND $_POST['action'] == "update") {
		if(isset($_POST['livraisonId']) AND $_POST['livraisonId']!=="") {
	        if(isset($_POST['formDateEnd'])) {$addThisQuery = "devis_date_end = '".$_POST['formDateEnd']."',";} else {$addThisQuery = "";}
	        mysql_query("UPDATE devis 
	                    SET 
	                    devis_note = '".replace_ap($_POST['note'])."',
	                    devis_note_client = '".strip_tags(replace_ap($_POST['note_client']))."',
	                    devis_email = '".$_POST['emailChange']."',
	                    devis_shipping = '".$_POST['livraisonId']."',
	                    ".$addThisQuery."
	                    devis_traite='".$_POST['traite']."', 
	                    devis_sent='".$_POST['sent']."'
	                    WHERE devis_number = '".$_POST['devisNumber']."'");
	        
	        if($_POST['sent']=="yes" AND $myhid['devis_date_sent']=="0000-00-00 00:00:00" AND $myhid['devis_date_end']=="0000-00-00 00:00:00") {
	            $dateStart = date("Y-m-d H:i:s");
	            $dateEnd = strftime("%Y-%m-%d %H:%M:%S",mktime(0,0,0,date("m"),date("d")+$devisValid,date("Y")));
	            mysql_query("UPDATE devis SET devis_sent = 'yes', devis_date_end = '".$dateEnd."', devis_date_sent = '".$dateStart."' WHERE devis_number = '".$_POST['devisNumber']."'");
	        }
	        
	        if($_POST['sent']=="no") {
	            mysql_query("UPDATE devis SET devis_sent = 'no', devis_date_end = '', devis_date_sent = '' WHERE devis_number = '".$_POST['devisNumber']."'");
	        }
	        $messageAff = UPDATE_DONE;
        }
        else {
			$messageAff = "<br><span style='padding:10px; background:#FFFFFF; border:#FF0000 2px solid;'><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle>Selecteer een leverings wijze<img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle><img border=0 src=im/leeg.gif align=absmiddle></span><br><br>";
		}
}

 
if(isset($_POST['action']) AND $_POST['action'] == "sendEmail") {

       $dateStart = date("Y-m-d H:i:s");
       $dateEnd = strftime("%Y-%m-%d %H:%M:%S",mktime(0,0,0,date("m"),date("d")+$devisValid,date("Y")));
       $dateEndMail = mktime(0, 0, 0, date("m")  , date("d")+$devisValid, date("Y"));
       $dateEndMail = date("d m Y",$dateEndMail);
       
        // envoyer mail
        $to = $_POST['email'];
        $subject = A90." ".$_POST['devisNumber']." - ".$domaineFull;
        $from = $mailInfo;
        $_store = str_replace("&#146;","'",$store_company);
        $messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."\r\n".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= A1008.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= A1009.": ".$fax."\r\n";
        $messageToSend .= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailInfo."\r\n\r\n";
        $messageToSend .= $_POST['prenom']." ".$_POST['nom'].",\r\n\r\n";
        $messageToSend .= INTRO2."\r\n";
        $messageToSend .= "---\r\n";
        $messageToSend .= VOTRE_NUMERO_DEVIS_EST." : ".$_POST['devisNumber']."\r\n";
        $messageToSend .= DEVIS_VALIDE.": ".$dateEndMail."\r\n";
        $messageToSend .= "---\r\n";
        $messageToSend .= VOUS_POUVEZ_CONSULTER." :\r\n";
        $_POST['country'] = str_replace(" ","%20",$_POST['country']);

        
        $messageToSend .= VOUS_POUVEZ_DESORMAIS_LIEN."\r\nhttp://".$www2.$domaineFull."/caddie.php?dvn=".$_POST['devisNumber']."&lang=".$_SESSION['lang']."\r\n";
        if($devisModule=="oui") {
        $messageToSend .= VOUS_POUVEZ_DESORMAIS."\r\n\r\n";
        }
        $messageToSend .= "NOTA:\r\n";
        $messageToSend .= POUR_TOUTE_INFORMATION."\r\n";
        $messageToSend .= LE_SERVICE_CLIENT."\r\n";
        $messageToSend .= $mailInfo;
 

      mail($to, $subject, rep_slash($messageToSend),
       "Return-Path: $from\r\n"
       ."From: $from\r\n"
       ."Reply-To: $from\r\n"
       ."X-Mailer: PHP/" . phpversion());
       
  
       mysql_query("UPDATE devis SET devis_sent = 'yes', devis_date_end = '".$dateEnd."', devis_date_sent = '".$dateStart."' WHERE devis_number = '".$_POST['devisNumber']."'");

		$messageAff = MOT_DE_PASSE_ENVOYE." ".$to;
}

 
if(isset($_POST['action']) AND $_POST['action'] == "offre") {
    $session_article = explode(",",$_POST['products']);
    $nbre_article = count($session_article);
    
    for($i=0; $i<=$nbre_article-1; $i++) {
        $check = explode("+", $session_article[$i]);
        $newQt = $_POST['newQt'][$i];
        $newPrice = ($newQt>0)? $_POST['newPrice'][$i]/$newQt : 0;
        $toto[] = $check[0]."+".$newQt."+".$newPrice."+".$check[3]."+".$check[4]."+".$check[5]."+".$check[6]."+".$check[7]."+".$check[8];
    }
    $newList = implode(",",$toto);
    
    mysql_query("UPDATE devis SET devis_products_new = '".$newList."' WHERE devis_number = '".$_POST['devisNumber']."'");
}

$hid = mysql_query("SELECT *, TO_DAYS(NOW()) - TO_DAYS(devis_date_end) as diff FROM devis WHERE devis_number = '".$_POST['id']."'");
$myhid = mysql_fetch_array($hid);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript"><!--
function confirmSubmit() {
    choix = confirm("Ben je zeker om deze offerte te versturen?");
    if(choix == true)
      {
      document.formulaire.submit();
      }
    else
      {
      return false;
      }

  }
//--></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div  align="center" class="largeBold"><?php print A90;?> nr. <?php print $_POST['id'];?></div>
<br>&nbsp;<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>
<div align="center"><a href="offertes.php?action=delete&id=<?php print $_POST['id'];?>"><?php print SUPPRIMER_DEVIS;?></a></div>
</tr></td></table><br>
<?php
print (isset($messageAff))? "<p align='center' class='fontrouge'><b>".$messageAff."</b></p>" : "<br>";
?>
<form method="POST" action="offerte_details.php">
<input type="hidden" name="action" value="update">
<input type="hidden" name="devisNumber" value="<?php print $_POST['id'];?>">
<table align="center" width="700" border="0" cellpadding="2" cellspacing="4" class="TABLE">
<tr>
        <td width='200'><?php print A1000;?></td>
        <td><?php print $myhid['devis_lastname'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1001;?></td>
        <td><?php print $myhid['devis_firstname'];?></td>
</tr>
<tr>
        <td><?php print A1003;?></td>
        <td><?php print $myhid['devis_company'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td width="120"><?php print A9;?></td>
        <td><?php (empty($myhid['devis_client']))?  print "--" : print "<span style='color:#000000'>".$myhid['devis_client']."</span>";?></td>
</tr>
<tr>
        <td><?php print TVA;?></td>
        <td><?php (empty($myhid['devis_tva']))? print "--" : print "<span style='color:#000000'>".$myhid['devis_tva']."</span>";?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td>E-mail</td>
        <td><input type="text" size="30" class="vullen" name="emailChange" value="<?php print $myhid['devis_email'];?>"></td>
</tr>
<tr>
        <td><?php print A1004;?></td>
        <td><?php print $myhid['devis_address'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1005;?></td>
        <td><?php print $myhid['devis_cp'];?></td>
</tr>
<tr>
        <td><?php print A1006;?></td>
        <td><?php print $myhid['devis_city'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1007;?></td>
        <td><?php print $myhid['devis_country'];?></td>
</tr>


<?php


## ALTER TABLE `devis` ADD `devis_shipping` INT NOT NULL
## Dans offertes/offerte_details.php, au-dessous de 
## devis_email = '".$_POST['emailChange']."',
## ajoutez
## devis_shipping = '".$_POST['livraisonId']."',
## Dans admin/offerte_details.php, remplacer
## 1 - 
## $ola = shipping_price($iso,$_GET['country'],$_SESSION['poids'],$activerPromoLivraison,$_SESSION['totalHtFinal']);
## par
## $ola = shipping_price($iso,$_GET['country'],$_SESSION['poids'],$activerPromoLivraison,$_SESSION['totalHtFinal'],$livraisonComprise,$devis['devis_shipping']);
## 2 - 
## function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise) {
## par
## function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise,$livraisonId) {
## 3 - 
## $_c = mysql_query("SELECT * FROM ship_price WHERE weight >= ".$_poids." ORDER BY weight");
## par
## $_c = mysql_query("SELECT * FROM ship_price WHERE weight >= ".$_poids." AND livraison_id='".$livraisonId."' ORDER BY weight");
## 4 - 
## $_f = mysql_query("SELECT ".$_zone." FROM ship_price WHERE weight = '".$_sp[0]."'");
## par
## $_f = mysql_query("SELECT ".$_zone." FROM ship_price WHERE weight = '".$_sp[0]."' AND livraison_id='".$livraisonId."'");

$requestZZZ = mysql_query("SELECT countries_id, countries_name, countries_shipping FROM countries WHERE countries_name='".$myhid['devis_country']."'") or die (mysql_error());
while($paysZZZ = mysql_fetch_array($requestZZZ)) {
	$requestMode = mysql_query("SELECT livraison_id, livraison_country, livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$paysZZZ['countries_id']."|%' AND livraison_active='yes'") or die (mysql_error());
	$pays1 = $paysZZZ['countries_id'];
	if(mysql_num_rows($requestMode) > 0) {
		while($requestModeResult = mysql_fetch_array($requestMode)) {
			$livraison[] = $requestModeResult['livraison_id']."||".$paysZZZ['countries_name']."||".$paysZZZ['countries_shipping']."||".$requestModeResult['livraison_nom_'.$_SESSION['lang']];
		}
	}
}
if(isset($livraison) AND count($livraison)>0) {
	print "<tr>";
	        print "<td valign=top>Levering door</td>";
	        print "<td>";
			print "<select name='livraisonId'>";
			print "<option value=''>Maak uw selectie</option>";
			print "<option value=''>---</option>";
				foreach($livraison AS $liv) {
					$livArray = explode("||",$liv);
					$selZ = ($livArray[0]==$myhid['devis_shipping'])? "selected" : "";
					print "<option value='".$livArray[0]."' ".$selZ.">".$livArray[3]."</option>";
				}
			print "</select>";
			print "</td>";
	print "</tr>";
}
?>
<tr>
        <td><?php print A1008;?></td>
        <td><?php print $myhid['devis_tel'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1009;?></td>
        <td><?php print $myhid['devis_fax'];?></td>
</tr>
<tr>
        <td><?php print A1010;?></td>
        <td><?php print $myhid['devis_activity'];?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1011;?></td>
        <td><span style="color:#CC0000"><?php print $myhid['devis_comment'];?></span></td>
</tr>
<tr>
        <td><?php print A1012;?></td>
        <!-- <td><?php print $myhid['devis_date_added'];?></td> -->
        <td><?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$myhid['devis_date_added']);?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1014;?></td>
        <?php //if($myhid['devis_sent']=='no') $sent="<span style='color:#CC0000'>".A15."</span>"; else $sent=A14;?>
        <?php 
            if($myhid['devis_sent']=='no') {
                $sel1 = "";
                $sel2 = "selected";
            } 
            else {
                $sel2 = "";
                $sel1 = "selected";
            }
        ?>
        <td>
            <select name="sent">
                <option value="yes" <?php print $sel1;?>><?php print A14;?></option>
                <option value="no" <?php print $sel2;?>><?php print A15;?></option>
            </select>
        </td>        

</tr>
<tr>
        <td><?php print A1500;?></td>
            <?php 
		if($myhid['devis_date_sent']=='0000-00-00 00:00:00') 
			$dateSent = "--"; 
		else 
 
 
			$dateSent = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$myhid['devis_date_sent']);
 
		?>

        <td><?php print $dateSent;?></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td><?php print A1013;?></td>
        <?php 
        if($myhid['devis_traite']=="no") {
                    if($myhid['devis_date_end']=='0000-00-00 00:00:00') {
                        $dateEnd = "--";
                        $statut = "";
                    }
                    else {
                        $dateEnd = $myhid['devis_date_end'];
                        $dateEnd = "<input type='text' size='25' name='formDateEnd' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$myhid['devis_date_end'])."'>";

                        $statut = "";
                    }
            	    if($myhid['diff'] > 0 AND $myhid['devis_traite'] !== 'yes') {
				   		$statut = "| <span style='color:#CC0000'><b>".EXPIRE."</b></span>";
					}
					if($myhid['diff'] < 0) {
				   		$statut = "| <b>".EN_COURS."</b>";		
					}
					if($myhid['diff'] == 0) {
				   		$statut = "| <span style='color:#CC0000'><b>".A2000."</b></span>";
					}
                    if(empty($myhid['diff'])) $statut = "";
		}
		else {
            $dateEnd = "";
            $statut = "<span style='color:#CC0000'><b>** ".A100101." **</b></span>";
        }
        ?>
        <td><b><?php print $dateEnd." ".$statut;?></b></td>
</tr>
<tr>
        <td><?php print A150;?></td>
        <?php 
            if($myhid['devis_traite']=='no') {
                $sel1 = "";
                $sel2 = "selected";
            } 
            else {
                $sel2 = "";
                $sel1 = "selected";
            }
        ?>
        <td>
            <select name="traite">
                <option value="yes" <?php print $sel1;?>><?php print A14;?></option>
                <option value="no" <?php print $sel2;?>><?php print A15;?></option>
            </select>
            <?php if($myhid['devis_traite'] == 'yes') { print voirCommande($_POST['id']);}?>
        </td>
</tr>
<tr bgcolor="#FFFFFF">
        <td valign=top><?php print A1501;?></td>

        <td><textarea name="note" cols="50" rows="5" class='vullen'><?php print $myhid['devis_note'];?></textarea></td>
</tr>
<tr bgcolor="#FFFFFF">
        <td valign=top><?php print AJOUTER_NOTE_CLIENT;?></td>
        <td>
<?php 
if($myhid['devis_sent']=="yes") {
	print ($myhid['devis_note_client']=="")? "--" : $myhid['devis_note_client'];
	print "<input type='hidden' name='note_client' value='".$myhid['devis_note_client']."'>";
}
else {
	print "<textarea name='note_client' cols='50' rows='5' class='vullen'>".$myhid['devis_note_client']."</textarea>";
}
?>
		</td>
</tr>
<tr height="50">
        <td colspan="2" align="center">
			<input type="submit" value="<?php print A1502;?>" class="knop">
		</td>
</tr>
</table>
</form>

<br>

<form method="POST" action=""offerte_details.php>
<table align="center" width="550" border="0" cellpadding="5" cellspacing="0" class="TABLE">
<tr bgcolor="#FFFFFF">
<td valign="middle" align="center"><b><?php print A1015;?></b></td>
</tr>
<tr>
<td>
    <table align="center" width="700" border="0" cellpadding="2" cellspacing="0">
    <tr>
    <td><b><?php print A1;?></b></td>
    <td><b>Opties</b></td>
    <td align="left"><b>Ref</b></td>
    <td align="left"><b>Hoeveel</b></td>
    <td align="left"><b>E.P</b></td>
    <td align="left"><b>Prijs</b></td>
    <td><b>Offerte</b></td>
    </tr>
    <?php
    $productsArray = explode(",",$myhid['devis_products']);
    if(!empty($myhid['devis_products_new'])) {
        $productsArrayNew = explode(",",$myhid['devis_products_new']);
        $new=1;
    }

    $productsArrayNb = count($productsArray)-1;
    for($i=0; $i<=$productsArrayNb; $i++) {
        
            $productsDetail = explode("+",$productsArray[$i]);
            $name = $productsDetail[4];
            if(empty($productsDetail[6])) {$opt="&nbsp;";} else {$opt=$productsDetail[6];}
            $ref = $productsDetail[3];
            $qt = $productsDetail[1];
            $pu = $productsDetail[2]; 	
            $qtOffre = $productsDetail[1];
            $prixTotal =  $pu*$qt;
            $prixOffre = sprintf("%0.2f",$prixTotal);

        if(isset($new) AND $new==1) {
            $productsDetailNew = explode("+",$productsArrayNew[$i]);
            $qtOffre = $productsDetailNew[1];
            $prixTotal = $productsDetail[2]*$qtOffre;
            $prixOffre = $productsDetailNew[2]*$qtOffre;
        }
        
        $fond = ($prixTotal != $prixOffre)? "bgcolor='#CC0000'" : "";
        $fond2 = ($qt !==$qtOffre)? "bgcolor='#CC0000'" : "";
        $totalPriceDevis[] = $prixOffre;
        $totalPrice[] = $prixTotal;
        
            print "<tr>";
            print "<td>".$name."</td>";
            print "<td>".$opt."</td>";
            print "<td align='left'>".$ref."</td>";
            print "<td align='left' ".$fond2."><input type='text' class='vullen' name='newQt[]' value='".$qtOffre."' size='2'></td>";
            print "<td align='left'>".$pu."</td>";
            print "<td align='left'>".$prixTotal."</td>";
            print "<td ".$fond."><input type='text' name='newPrice[]' class='vullen' value='".$prixOffre."' size='8'></td>";
            print "</tr>";
    }
            $total = sprintf("%0.2f",array_sum($totalPriceDevis));
            $total1 = sprintf("%0.2f",array_sum($totalPrice));
    ?>
    <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" style="border-width:1px; border-color:#666666; border-top-style:solid;"><?php print $total1;?></td>
    <td align="left" style="border-width:1px; border-color:#666666; border-top-style:solid;"><?php print $total;?></td>
    </tr>
    </table>


        <input type="hidden" name="devisNumber" value="<?php print $_POST['id'];?>">
        <input type="hidden" name="products" value="<?php print $myhid['devis_products'];?>">
        <input type="hidden" name="country" value="<?php print $myhid['devis_country'];?>">
        <input type="hidden" name="action" value="offre">
        

<?php
    print "<p align='center'>";
    if($myhid['devis_sent'] == 'yes') {
    print "[<a href='../offertes/offerte_factuur.php?country=".$myhid['devis_country']."&id=".$myhid['devis_number']."' target='_blank' title='".A1507."'>".A1507."</a>]";
    print "&nbsp;|&nbsp;";
    }
    print "[<a href='../offertes/offerte_pdf.php?country=".$myhid['devis_country']."&id=".$myhid['devis_number']."' target='_blank' title='".A1508."'>".A1508."</a>]";
    print "</p>";
?>

        <?php 
        if($myhid['devis_sent'] == 'no') {
        ?>
        <table align="center" width="100%" border="0" cellpadding="5" cellspacing="0" class="TABLE"><tr>
        <td>
        <p style="color:#CC0000" align="center"><b><?php print A1505;?></b></p>
        <div align="center"><input type="submit" value="<?php print A1506;?>" class='knop'></div>
        </td>
        </tr>
        </table>
        <br>
        <?php
        }
        ?>
        


    
</td>
</tr>
</table>

</form>

<?php 
if($myhid['devis_sent'] == 'no') {
?>
<table align="center" width="700" border="0" cellpadding="5" cellspacing="0" class="TABLE">
    <tr bgcolor="#FFFFFF">
    <form method="POST" action="offerte_details.php">
    <td>
        <p align="center">

        <input type="hidden" name="devisNumber" value="<?php print $_POST['id'];?>">
        <input type="hidden" name="country" value="<?php print $myhid['devis_country'];?>">
        <input type="hidden" name="email" value="<?php print $myhid['devis_email'];?>">
        <input type="hidden" name="nom" value="<?php print $myhid['devis_lastname'];?>">
        <input type="hidden" name="prenom" value="<?php print $myhid['devis_firstname'];?>">
        <input type="hidden" name="action" value="sendEmail">
        <input type="submit" value="Verstuur deze offerte via e-mail naar <?php print $myhid['devis_email'];?>" name="formulaire" onclick="return confirmSubmit();" class="knop">
        </p>
    </td>
    </form>
    </tr>
</table>
<?php
}
?>
<br><br><br>
  </body>
  </html>
