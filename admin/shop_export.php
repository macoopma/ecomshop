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

 
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}



if(isset($_POST['action']) AND $_POST['action']=="activerLeGuide" ) {
    
  
    $fileName = "marchand_infos_leguide";
    $ext = "txt";
   
    $file = "functions/".$fileName.".txt";
    $fp=fopen($file ,"wb");
    if($fp) {
    $sendToLeguide = "NOUVEAU MARCHAND BOUTIKONE\r\n";
    $sendToLeguide .= "---\r\n";
    $sendToLeguide .= $store_company."\r\n";
    $sendToLeguide .= $address_street."\r\n";
    $sendToLeguide .= $address_cp." - ".$address_city."\r\n";
    $sendToLeguide .= $address_country."\r\n";
    if(!empty($address_autre)) {
        $address_autre2 = str_replace("<br>","\r\n",$address_autre);
        $sendToLeguide .= $address_autre2."\r\n";
    }
    if(!empty($tel)) $sendToLeguide .= "Téléphone: ".$tel."\r\n";
    if(!empty($fax)) $sendToLeguide .= "Fax: ".$fax."\r\n";
    $sendToLeguide .= "Email : ".$mailInfo."\r\n";
    $sendToLeguide .= "---\r\n";
    $sendToLeguide .= "Solution e-commerce : ECM\r\n";
    $sendToLeguide .= "URL boutique : http://".$www2.$domaineFull."\r\n";
    $sendToLeguide .= "URL fichier export catalogue : http://".$www2.$domaineFull."/request/cron.php";
    
    $emailText = $sendToLeguide;
     
    fwrite($fp, $sendToLeguide);
    
     
      
       
        $to = "integration@leguide.com";
        $from = $mailInfo;
        $subject = "Nouveau marchand shop";
        
        $boundary = "-----=".md5(uniqid(rand()));
        $header = "MIME-Version: 1.0\r\n";
		$header.="Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
		$header.= "\r\n";
        $msg = "Je vous informe que ceci est un message au format MIME 1.0 multipart/mixed.\r\n";
        $msg.= "--$boundary\r\n";
        $msg.= "Content-Type: text/plain; charset='iso-8859-1'\r\n";
        $msg.= "Content-Transfer-Encoding:8bit\r\n";
        $msg.= "\r\n";
        $msg.= "-- Voir fichier joint --\r\n\r\n";
        $msg.= $emailText."\r\n";
        $msg.= "\r\n";
        $file = "functions/marchand_infos_leguide.txt";
        $fp = fopen($file, "rb");
        $attachment = fread($fp, filesize($file));
        fclose($fp);
        $attachment = chunk_split(base64_encode($attachment));
        $msg.= "--$boundary\r\n";
        $msg.= "Content-Type: application/txt; name=\"".$file."\"\r\n";
        $msg.= "Content-Transfer-Encoding: base64\r\n";
        $msg.= "Content-Disposition: inline; filename=\"".$file."\"\r\n";
        $msg.= "\r\n";
        $msg.= $attachment . "\r\n";
        $msg.= "\r\n\r\n";
        $msg.= "--".$boundary."--\r\n";
        
        $reponse = $from;
        
        mail($to, $subject, rep_slash($msg),
             "Return-Path: ".$from."\r\nReply-to: ".$reponse."\r\nFrom: ".$from."\r\n".$header);


     $endMessage = "<p align='center' class='title' style='color:#CC0000; background-color:#FFFFFF'><b>Email envoyé à integration@leguide.com</b></p>";
    echo $endMessage;
    }
    else {
    $to111 = $mailInfo;
  
    $subject111 = "Error in Ecom shop";
    $from111 = $mailInfo;
    $messageToSend1 = "Hallo administrator,\r\n\r\nVoor je de account op LeGuide.com kunt activeren, gelieve het bestand admin/functions/marchand_infos_leguide.txt op CHMOD 666 te plaatsen.\r\n\r\n---\r\n";
                
                mail($to111, $subject111, rep_slash($messageToSend1),
                     "Return-Path: $from111\r\n"
                     ."From: $from111\r\n"
                     ."Reply-To: $from111\r\n"
                     ."X-Mailer: PHP/" . phpversion());
    }
}



if(isset($_POST['action']) AND $_POST['action']=="exportLeGuide" ) {
$langCat = $_POST['id_langLeguide'];

    // Alle categories
    if(empty($_POST['categLeguide'])) {
            $categQuery = mysql_query("SELECT categories_id
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);

        if($categQueryNum > 0) {
            while($categ = mysql_fetch_array($categQuery)) {
                $insertBddArray[] = $categ['categories_id'];
            }
            $insertBdd = implode("||",$insertBddArray);
            $insertBdd = $langCat."||".$insertBdd;
            mysql_query("UPDATE admin SET export_auto_leguide = '".$insertBdd."'");
            $catSave = "<br><span align='left' class='fontrouge'><b>".$categQueryNum." bewaarde categories</b></span>";
        }
    }
    else {
    // categories selected
        $insertBdd = implode("||",$_POST['categLeguide']);
        $insertBdd = $langCat."||".$insertBdd;
        $catNum = count($_POST['categLeguide']);
        mysql_query("UPDATE admin SET export_auto_leguide = '".$insertBdd."'");
        $catSave = "<br><span align='left' class='fontrouge'><b>".$catNum." bewaarde categories</b></span>";
    }
}



if(isset($_POST['action']) AND $_POST['action']=="exportShopping" ) {
$langCat = $_POST['id_langShopping'];


    if(empty($_POST['categShopping'])) {
            $categQuery = mysql_query("SELECT categories_id
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);

        if($categQueryNum > 0) {
            while($categ = mysql_fetch_array($categQuery)) {
                $insertBddArray[] = $categ['categories_id'];
            }
            $insertBdd = implode("||",$insertBddArray);
            $insertBdd = $langCat."||".$insertBdd;
            mysql_query("UPDATE admin SET export_auto_shopping = '".$insertBdd."'");
            $catSave2 = "<br><span align='left' class='fontrouge'><b>".$categQueryNum." bewaarde categories</b></span>";
        }
    }
    else {

        $insertBdd = implode("||",$_POST['categShopping']);
        $insertBdd = $langCat."||".$insertBdd;
        $catNum = count($_POST['categShopping']);
        mysql_query("UPDATE admin SET export_auto_shopping = '".$insertBdd."'");
        $catSave2 = "<br><span align='left' class='fontrouge'><b>".$catNum." bewaarde categories</b></span>";
    }
}



if(isset($_POST['action']) AND $_POST['action']=="Export") {

    if(isset($_POST['categLeguide'])) {
    include('functions/leguide.php');
    build_leGuide_file($_POST['id_langLeguide']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren</b></p>
        </td></tr></table>
        ";
    }
}

if(isset($_POST['action']) AND $_POST['action']=="Export ftp") {
    if(isset($_POST['categLeguide'])) {
    include('functions/export_leGuide_ftp.php'); 
    build_leGuide_file($_POST['id_langLeguide']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren</b></p>
        </td></tr></table>
        ";
    }
}

 
if(isset($_POST['action']) AND $_POST['action']=="shopping") {
    if(isset($_POST['categShopping'])) {
        include('functions/shopping.php'); 
        build_shopping_file($_POST['id_langShopping']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren</b></p>
        </td></tr></table>
        ";
    }
}

// kelkoo
//print $_POST['action'];
if(isset($_POST['action']) AND $_POST['action']=="kelkoo") { 
    if(isset($_POST['categKelkoo'])) {
        include('functions/kelkoo.php'); 
        build_kelkoo_file($_POST['id_langKelkoo'], $_POST['id_currencyKelkoo'], $_POST['updateKelkoo']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren</b></p>
        </td></tr></table>
        ";
    }
}
 
// pricerunner
if(isset($_POST['action']) AND $_POST['action']=="pricerunner") {
    if(isset($_POST['categPricerunner'])) {
    include('functions/pricerunner.php'); 
    build_pricerunner_file($_POST['id_langPricerunner']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren</b></p>
        </td></tr></table>
        ";
    }
}
 

// Export auto Twenga
if(isset($_POST['action']) AND $_POST['action']=="exportTwenga" ) {
$langCat = $_POST['id_langTwenga'];

    // All categories
    if(empty($_POST['categTwenga'])) {
            $categQuery = mysql_query("SELECT categories_id
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);

        if($categQueryNum > 0) {
            while($categ = mysql_fetch_array($categQuery)) {
                $insertBddArray[] = $categ['categories_id'];
            }
            $insertBdd = implode("||",$insertBddArray);
            $insertBdd = $langCat."||".$insertBdd;
            mysql_query("UPDATE admin SET export_auto_twenga = '".$insertBdd."'");
            $catSave3 = "<br><span align='left' class='fontrouge'><b>".$categQueryNum." catégories sauvegardées !</b></span>";
        }
    }
    else {
  
        $insertBdd = implode("||",$_POST['categTwenga']);
        $insertBdd = $langCat."||".$insertBdd;
        $catNum = count($_POST['categTwenga']);
        mysql_query("UPDATE admin SET export_auto_twenga = '".$insertBdd."'");
        $catSave3 = "<br><span align='left' class='fontrouge'><b>".$catNum." catégories sauvegardées !</b></span>";
    }
}

// Twenga
if(isset($_POST['action']) AND $_POST['action']=="twenga") {
    if(isset($_POST['categTwenga'])) {
        include('functions/twenga.php'); 
        build_twenga_file($_POST['id_langTwenga']);
    }
    else {
        $message = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE'><tr><td>
        <p align='center' class='fontrouge'><b>Gelieve minstens één categorie te selecteren !</b></p>
        </td></tr></table>
        ";
    }
}
?>
<html>
    <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel='stylesheet' href='style.css'>
<script type="text/javascript"><!--
function confirmSubmit() {
    choix = confirm("Er wordt nu een e-mail verstuurd naar integration@leguide.com\r\nVoor je deze e-mail verstuurt, controleer het volgende:\r\n\r\nUw shop staat online en alles is klaar om online te verkopen?\r\nZijn de categories bewaard die je wenst te exporteren?\r\nHet bestand admin/functions/marchand_infos_leguide.txt staat op CHMOD 666?\r\nDe URl naar Ecomshop is:\r\nhttp://<?php print $www2.$domaineFull;?>\r\nIs de bovenstaande URL correct?\r\n\r\nZijn alle antwoorden JA, klik dan op OK...\r\n");




    if(choix == true) {
      document.formulaire.submit();
    }
    else {
      return false;
      }
  }
//--></script>
    </head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print "Exporteer de shop naar andere websites";?></p>

<table width="100%" border="0" cellpadding="0" cellspacing="3"><tr><td>


<table width="700" class="TABLE" align="center" border="0" cellpadding="5" cellspacing="0"><tr><td>
<p align="center">
&bull;&nbsp;<a href="#leguide" name="a1">Antag</a>&nbsp;
&bull;&nbsp;<a href="#leguide" name="a1">Koopkeus</a>&nbsp;
&bull;&nbsp;<a href="#kelkoo" name="a3">Kelkoo</a>&nbsp;
&bull;&nbsp;<a href="#leguide" name="a1">LeGuide</a>&nbsp;
&bull;&nbsp;<a href="#shopping" name="a2">Shopping.com</a>&nbsp;
&bull;&nbsp;<a href="#pricerunner" name="a5">Price Runner</a>
&bull;&nbsp;<a href="#twenga" name="a7">Twenga</a>
</p>
</td></tr></table>
<table width="700" align="center" class="boxtitle1" border="0" cellpadding="4" cellspacing="0"><tr><td>
<div align="center"><b>Geef een boost aan uw webshop</b></div>

<br>
Exporteer uw catalogus naar het CSV-formaat en verwijs uw geselecteerde producten naar de aankoopgidsen en gespecialiseerde zoekmotoren voor online shopping.
<br><b>OPGELET:<br></b>Als u met een (of meerdere) aankoopgidsen werkt, is het van belang dat u het geëxporteerde bestand na elke wijziging actualiseert, want anders zullen uw aanbiedingen niet up-to-date zijn.
<br><br>


<?php
if(isset($message)) {print "<p>".$message."</p>";}
?>


<?php
//---------
// Le Guide
//---------
?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<td style="padding-right:0px;">
<div align="left"><a href="#a1" name="<?php print "leguide";?>"><img src="im/up.gif" border="0"></a></div>
</td>
</tr></table>

<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<form method="POST" action="shop_export.php">
<td align="center" height="60" colspan="3">
<a href="http://www.leguide.com" target="_blank"><img src="im/antag.jpg" border="0"  align='absmiddle'></a>
&nbsp;&nbsp;&nbsp;
<a href="http://www.leguide.com" target="_blank"><img src="im/koopkeus.gif" border="0"  align='absmiddle'></a>
&nbsp;&nbsp;&nbsp;<a href="http://www.leguide.com" target="_blank"><img src="im/leguide.gif" border="0" align='absmiddle'></a>
<br>

 
<div><img src="im/zzz.gif" width="1" height="5"></div>

</td></tr>
<tr class="TD">
<td colspan="2" align="center">-- <b>Voeg uw shop automatisch toe aan Antag - Koopkeus en LeGuide</b> --<br>

</td>
</tr>
<tr class="TD">
<td align="left" valign="top">
<div align="left">Selecteer de gewenste categories</div>
<div align="left"><img src="im/zzz.gif" width="1" height="4"></div>
<div align="left">
Kies hieronder de categories met de artikels die u wilt verwijzen naar de prijsvergelijkers Leguide.com S.A.<br>
- Als er geen enkele categorie geselecteerd werd, zal uw volledige shop opgenomen worden.<br>
- De artikels van de gekozen categories zullen geactualiseerd worden en worden vervolgens iedere dag bijgewerkt, 7 dagen op 7.
</div><br>


<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);
        if($categQueryNum > 0) {
           print "<select name='categLeguide[]' size='7' multiple>";
           while($categ = mysql_fetch_array($categQuery)) {
                print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
           }
           print "</select>";
       }
       else {
        print "<div class='fontrouge'>Er werden geen categories gevonden</div>";
       }
?>
<br>
<br>
</td>
</tr>
<tr class="TD">
<td align="left" valign="top">
<div align="left">Selecteer de gewenste taal</div>
<br>
    <select name="id_langLeguide">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
<br>
<br>
<?php
    $catQuery = mysql_query("SELECT export_auto_leguide
                             FROM admin");
    $catQueryNum = mysql_num_rows($catQuery);
    
    if($catQueryNum > 0) {
        $catResult = mysql_fetch_array($catQuery);
        $catToExport = $catResult['export_auto_leguide'];
    }
    else {
        $catToExport = "";
    }
    //print $catToExport."<br>";
?>
</td>
</tr>
<tr>
<td align="left" valign="top">
<p align="left">Klik op de knop "Bewaar de categories" hier onder om de product categories te bewaren waar je naar wenst te verwijzen.<br>
<br>
<input type="hidden" name="action" value="exportLeGuide">
<input type="submit" value="Bewaar de categories" class="knop">
<?php 
if(isset($catSave)) print $catSave;
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0);" onClick="window.open('bekijk_export_leguide.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=200,toolbar=no,scrollbars=no,resizable=yes')">
Bekijk de bewaarde categories</a>
</p>

</td>
</form>

</tr>
<tr class="TD">


<td align="left" valign="top">
<form method="POST" action="shop_export.php">
<div align="left">Activeer je account bij LeGuide.com</div>
<div align="left" class="fontrouge">Plaats het bestand admin/functions/marchand_infos_leguide.txt = <b>CHMOD 666</b>.</div>
<div align="left"><img src="im/zzz.gif" width="1" height="4"></div>
<div align="left">
Klik op de knop "Account activeren", er zal een e-mail met de URL van de shop en de informatie betreffende het bestand met de beschrijving naar LeGuide.com S.A. gestuurd worden, die deze zal opnemen (gewoonlijk in 24-48 uur).
<br>U zult hiervan op de hoogte gebracht worden.<br>
Neem indien nodig contact op met: <a href="mailto:integration@leguide.com">integration@leguide.com</a>
</div>
<br>
<div align="left" class="fontrouge"><b>BELANGRIJK</b>: Alvorens op de onderstaande knop te klikken, moet u controleren of uw winkel online is, volledig is en operationeel is.</div>
<br>
<input type="hidden" name="action" value="activerLeGuide">
<input type="submit" value="Account activeren" name="formulaire" onclick="return confirmSubmit();" class="knop">
    
<?php
if(isset($endMessage)) print $endMessage;
?>
</form>




<hr>

<p align="center"><b>-- Exporteer de shop in CSV - Leguide.com --</b></p>
<form method="POST" action="shop_export.php">

<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);
        if($categQueryNum > 0) {
           print "<select name='categLeguide[]' size='7' multiple>";
           while($categ = mysql_fetch_array($categQuery)) {
                print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
           }
           print "</select>";
       }
       else {
        print "<div class='fontrouge'>Er werden geen categories gevonden</div>";
       }
?>
&nbsp;&nbsp;
    <select name="id_langLeguide">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
&nbsp;&nbsp;

    <input type="hidden" name="action" value="Export">
    <input type="submit" value="Exporteer" class="knop">

&nbsp;&nbsp;
Scheidingsteken: "[TAB]"
</form>


</td>


</tr>
</table>
<br>




<?php
//-------------
// Shopping.com
//-------------
?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<td style="padding-right:0px;">
<div align="left"><a href="#a2" name="<?php print "shopping";?>"><img src="im/up.gif" border="0"></a></div>
</td>
</tr></table>

<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<form method="POST" action="shop_export.php">
<td align="center" height="60" colspan="3">

<a href="https://ukmerchant.shopping.com/enroll/app?service=page/PartnerWelcome" target="_blank"><img src="im/shopping.gif" border="0"></a>
<br>Klik op het bovenstaande logo om de website te bezoeken<br>
</td>
</tr>
<tr class="TD">
</tr>
<tr class="TD">
<td align="left" valign="top">
<div align="left">Selecteer de categories</div>
<div align="left"><img src="im/zzz.gif" width="1" height="4"></div>
<div align="left">
Kies hieronder de categories met de artikels die u wilt verwijzen naar de prijsvergelijker Shopping.com<br>
- Als er geen enkele categorie geselecteerd werd, zal uw volledige shop opgenomen worden.<br>
- De artikels van de gekozen categorieën zullen geactualiseerd worden en worden vervolgens iedere dag bijgewerkt, 7 dagen op 7.
</div><br>
</div><br>
<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);
        if($categQueryNum > 0) {
           print "<select name='categShopping[]' size='7' multiple>";
           while($categ = mysql_fetch_array($categQuery)) {
                print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
           }
           print "</select>";
       }
       else {
        print "<div class='fontrouge'>Er werden geen categories gevonden</div>";
       }
?>
<br>
<br>
</td>
</tr>
<tr class="TD">
<td align="left" valign="top">
<div align="left">Selecteer de gewenste taal</div>
<br>
    <select name="id_langShopping">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
<br>
<br>
<?php
    $catQuery = mysql_query("SELECT export_auto_shopping
                             FROM admin");
    $catQueryNum = mysql_num_rows($catQuery);
    
    if($catQueryNum > 0) {
        $catResult = mysql_fetch_array($catQuery);
        $catToExport2 = $catResult['export_auto_shopping'];
    }
    else {
        $catToExport2 = "";
    }

?>
</td>
</tr>
<tr class="TD">
<td align="left" valign="top">
<p align="left">Klik op de knop "Bewaar de categories" hier onder om de product categories te bewaren waar je naar wenst te verwijzen.
<br>
<br>
<input type="hidden" name="action" value="exportShopping">
<input type="submit" value="Bewaar de categories" class="knop"'>
<?php 
if(isset($catSave2)) print $catSave2;
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0);" onClick="window.open('bekijk_export_shopping.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=200,toolbar=no,scrollbars=no,resizable=yes')">
Bekijk de bewaarde categories</a>
</p>

</td>
</form>
</tr><tr>
<td>

<?php
$url_shop = "http://".$www2.$domaineFull."/request/cronwinkel.php";
?>
<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<td>
<b>Transfer via HTTP</b><br>
Volledige URL:<br>
<input type="text" value="<?php print $url_shop;?>" size="85" class="vullen"><br>
(Indicatie voor shopping.com)<br><br>
- Na je inschrijving bij shopping.com wordt deze URL benodigd om uw artikelen bij hen zichtbaar te maken.<br>
- Ecomshop kan niet verantwoordelijk gesteld worden voor een of meerdere artikelen die door shopping.com worden geweigerd.
</td></tr></table>


<p align="center"><b>-- Exporteer de shop in CSV - shopping.com --</b></p>
<form method="POST" action="shop_export.php">


<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
       print "<select name='categShopping[]' size='7' multiple>";
       while($categ = mysql_fetch_array($categQuery)) {
            print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
       }
       print "</select>";
?>
&nbsp;&nbsp;

    <select name="id_langShopping">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
&nbsp;&nbsp;

    <input type="hidden" name="action" value="shopping">
    <input type="submit" value="Exporteer" class="knop">
&nbsp;&nbsp;
Scheidingsteken: "[TAB]"

</form>
<br>
</td>

</tr>
</tr>
</table>
<br>




<?php
//-------
// Kelkoo
//-------
?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<td style="padding-right:0px;">
<div align="left"><a href="#a3" name="<?php print "kelkoo";?>"><img src="im/up.gif" border="0"></a></div>
</td>
</tr></table>

<table width="700" class="TABLE" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<form method="POST" action="shop_export.php">
<td align="center" height="60" colspan="3">
<a href="http://www.kelkoo.nl/co_15157-kelkoo-word-partner-van-kelkoo-kelkoo.html" target="_blank"><img src="im/kelkoo.gif" border="0"></a>
 <br>Klik op het bovenstaande logo om de website te bezoeken<br>
</td></tr>
<tr>
<td align="left" valign="top" rowspan="3">
<div align="left">Selecteer categories</div><br>
<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
       print "<select name='categKelkoo[]' size='7' multiple>";
       while($categ = mysql_fetch_array($categQuery)) {
            print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
       }
       print "</select>";
?>
</td>
<td align="left" valign="top">
Selecteer de gewenste taal<br>
    <select name="id_langKelkoo">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
</td>
<td align="right" valign="top" rowspan="3">
<div align="right">Exporteer artikelen</div><br>
    <input type="hidden" name="action" value="kelkoo">
    <input type="submit" value="Exporteer" class="knop">
</td>
</tr><tr>
<td>
Valuta<br>
    <select name="id_currencyKelkoo">
    <option value="EUR" selected>EURO</option>
    </select>
</td>
</tr><tr>
<td>
Verwijderen | Toevoegen - Lees (2)<br>
    <select name="updateKelkoo">
    <option value="YES">Ja</option>
    <option value="NO" selected>Neen</option>
    </select>
</td>
</tr><tr>
<td align="right" colspan="3">
Scheidingsteken: "[TAB]"
</td>
</form>
</tr>
</table>
<br>






<?php
//------------
// PriceRunner
//------------
?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<td style="padding-right:0px;">
<div align="left"><a href="#a5" name="<?php print "pricerunner";?>"><img src="im/up.gif" border="0"></a></div>
</td>
</tr></table>

<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<form method="POST" action="shop_export.php">
<td align="center" height="60" colspan="3">
<a href="http://www.pricerunner.com" target="_blank"><img src="im/pricerunner.gif" border="0"></a>
<br>Klik op het bovenstaande logo om de website te bezoeken<br>
</td></tr><tr>
<td align="left" valign="top">
<div align="left">Selecteer categories</div><br>
<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
       print "<select name='categPricerunner[]' size='7' multiple>";
       while($categ = mysql_fetch_array($categQuery)) {
            print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
       }
       print "</select>";
?>
</td>
<td align="left" valign="top">
<div align="left">Selecteer de gewenste taal</div><br>
    <select name="id_langPricerunner">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
</td>

<td align="right" valign="top">
    <div align="right">Exporteer artikelen</div><br>
    <input type="hidden" name="action" value="pricerunner">
    <input type="submit" value="Exporteer" class="knop">
</td>
</tr><tr>
<td align="right" colspan="3">
Scheidingsteken: "[TAB]"
</td>
</form>
</tr></table>
<br>


<?php
//----------------------
// Twenga
//----------------------
?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0"><tr>
<td style="padding-right:0px;">
<div align="left"><a href="#a7" name="<?php print "twenga";?>"><img src="im/up.gif" border="0"></a></div>
</td>
</tr></table>

<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<form method="POST" action="shop_export.php">
<td align="center" height="60" colspan="3">

<a href="http://www.twenga.nl" target="_blank"><img src="im/twenga.gif" border="0"></a>
</td>
</tr>
<tr class="TD">
<td colspan="2" align="center" >-- <b>Voeg uw shoop automatisch toe aan Twenga</b> --<br>

</td>
</tr>
<tr>
<td align="left" valign="top">
<div align="left">Selecteer de gewenste categories</div>
<div align="left"><img src="im/zzz.gif" width="1" height="4"></div>
<div align="left">

Kies hieronder de categories met de artikels die u wilt verwijzen naar de prijsvergelijkers Leguide.com S.A.<br>
- Als er geen enkele categorie geselecteerd werd, zal uw volledige shop opgenomen worden.<br>
- De artikels van de gekozen categories zullen geactualiseerd worden en worden vervolgens iedere dag bijgewerkt, 7 dagen op 7.
<br><br>

<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
        $categQueryNum = mysql_num_rows($categQuery);
        if($categQueryNum > 0) {
           print "<select name='categTwenga[]' size='7' multiple>";
           while($categ = mysql_fetch_array($categQuery)) {
                print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
           }
           print "</select>";
       }
       else {
        print "<div class='fontrouge'>Er zijn geen ctegories beschikbaar</div>";
       }
?>
<br>
<br>
</td>
</tr>
<tr class="TD">
<td align="left" valign="top">
<div align="left">Selecteer de gewenste taal</div>
<br>
    <select name="id_langTwenga">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
<br>
<br>
<?php
    $catQuery = mysql_query("SELECT export_auto_twenga FROM admin");
    $catQueryNum = mysql_num_rows($catQuery);
    
    if($catQueryNum > 0) {
        $catResult = mysql_fetch_array($catQuery);
        $catToExport2 = $catResult['export_auto_twenga'];
    }
    else {
        $catToExport2 = "";
    }
 
?>
</td>
</tr>
<tr>
<td align="left" valign="top">
<br>
<input type="hidden" name="action" value="exportTwenga">
<input type="submit" value="Bewaar de categories" class="knop">
<?php 
if(isset($catSave3)) print $catSave3;
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:void(0);" onClick="window.open('bekijk_export_twenga.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=200,toolbar=no,scrollbars=no,resizable=yes')">
Bekijk de bewaarde categories</a>.
</p>

</td>
</form>
</tr><tr>
<td>

<?php
$url_shop = "http://".$www2.$domaineFull."/request/crontwenga.php";
?>
<table width="700" align="center" class="TABLE" border="0" cellpadding="4" cellspacing="0"><tr>
<td>
<b>Transfer via HTTP</b><br>
URL:<br>
<input type="text" class="vullen" value="<?php print $url_shop;?>" size="85"><br>
<i>(indicatie voor Twenga.nl)</i><br><br>
- Na je inschrijving bij Tenga.nl wordt deze URL benodigd om uw artikelen bij hen zichtbaar te maken.<br>
- Ecomshop kan niet verantwoordelijk gesteld worden voor een of meerdere artikelen die door Twenga worden geweigerd.
</td></tr></table>


<p align="center"><b>-- Exporteer de shop in CSV - twenga.nl --</b></p>
<form method="POST" action="shop_export.php">


<?php
    $categQuery = mysql_query("SELECT categories_id, categories_name_".$_SESSION['lang']."
                               FROM categories
                               WHERE categories_noeud = 'L'
                               AND  categories_visible = 'yes'
                               ORDER BY parent_id
                               DESC");
       print "<select name='categTwenga[]' size='7' multiple>";
       while($categ = mysql_fetch_array($categQuery)) {
            print "<option value='".$categ['categories_id']."'>".$categ['categories_name_'.$_SESSION['lang']]."</option>";
       }
       print "</select>";
?>
&nbsp;&nbsp;

    <select name="id_langTwenga">
    <option value="3">Nederlands</option>
    <option value="1">Français</option>
    <option value="2">English</option>

    </select>
&nbsp;&nbsp;

    <input type="hidden" name="action" value="twenga">
    <input type="submit" value="Exporteer" class="knop">
&nbsp;&nbsp;
Scheidingsteken: "[TAB]"

</form>
<br>
</td>

</tr>
</tr>
</table>
<br>





<table width="700" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE"><tr>
<td>
<br>
ALGEMENE NOTA<br>
- De export zal een een tekst (txt) bestand aanmaken in csv-formaat (door tabs gescheiden) genaamd xxxx_XX.txt (waarbij XX de taal van de export is). 
<br><br>
<br>

Gebruik van de categorie lijsten<br>
- Gebruik de Controle toets (Ctrl) om de diverse categories te selecteren of te deselecteren
<br>
- Gebruik Shift (of Alt of Cap) om een vervolgkeuzelijst van categories te selecteren.
<br>
- De valuta moet deze van het land zijn waar het bestand gedeponeerd wordt.
<br>
- Als de waarde YES is, zal de voorgaande informatie betreffende de aanbiedingen met dezelfde aanbod-ID gewist worden.
<br>
- Als de waarde NO is, zal de informatie betreffende de aanbiedingen met dezelfde aanbod-ID geactualiseerd worden.
<br>
</p>
</td>
</tr>
</table>
</td></tr></table><br><br><br>



</body></html>
