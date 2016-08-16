<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = "NEWSLETTER";
$backgroundTitle = "background-color:#999999; color:#FFFFFF; padding:7px; border:#FFFF00 2px solid;";
 
$messageResult = "";
if(isset($_GET['action']) AND $_GET['action']=="activer") {
if(isset($_GET['activeNumber']) AND $_GET['activeNumber']!=="") {
    $query = mysql_query("SELECT *
                         FROM newsletter
                         WHERE newsletter_password='".$_GET['activeNumber']."'
                         AND newsletter_email='".$_GET['email']."'");
    $resultNum = mysql_num_rows($query);
        if($resultNum > 0) {
            $result = mysql_fetch_array($query);
            $update = mysql_query("UPDATE newsletter
                                   SET newsletter_active='yes', newsletter_statut = 'active'
                                   WHERE newsletter_password='".$_GET['activeNumber']."'
                                   AND newsletter_email='".$_GET['email']."'");
            $messageResult = "<p style='background-color:#FFFF00; padding:5px;'>";
            $messageResult.= "<img src='im/fleche_menu.gif' align='absmiddle' border='0'>&nbsp;<b><span style='font-size:13px;'>Uw nieuwsbrief account</span>";
            $messageResult.= "<br>U bent nu ingeschreven op ons nieuwsbrief systeem</b><br><br>";
            $messageResult.= "U kunt dit venster nu sluiten of bezoek onze shop <a href='http://".$www2.$domaineFull."'><b>".$store_name."</b></a></i>";
            $messageResult.= "</p>";
        }
        else {
          $messageResult = "<p align='center' style='background-color:#FFFF00; padding:5px; font-size:13px;'><b><span class='fontrouge'>Ongeldig activatie nummer</span></b></p>";
        }
    }
    else {
        $messageResult = "<p lign='center' style='background-color:#FFFF00; padding:5px; font-size:13px;'><b><span class='fontrouge'>Geef uw activatie nummer in...</span> </b></p>";
    }
}

 
$messageResult2 = "";
if(isset($_GET['action']) and $_GET['action']=="annuler") {
    $query = mysql_query("SELECT * FROM newsletter WHERE newsletter_email='".$_GET['email']."' AND newsletter_active='yes'");
    $resultNum = mysql_num_rows($query);
    if($resultNum > 0) {
       $result = mysql_fetch_array($query);
       $update = mysql_query("UPDATE newsletter 
                              SET newsletter_active='no', newsletter_statut = 'out'
                              WHERE newsletter_email='".$_GET['email']."'");
                              
       $messageResult2 = "<p style='background-color:#FFFF00; padding:5px;'>";
       $messageResult2.= "<img src='im/fleche_menu.gif' align='absmiddle' border='0'>&nbsp;<b><span style='font-size:13px;'>Uw nieuwsbrief inschrijving</b></span></b>";
       $messageResult2.= "<br>U onvangt vanaf heden geen nieuwsbrief meer van <b>".strtoupper($domaineFull)."";
       $messageResult2.= "</p>";
    }
    else {
       $messageResult2 = "<p style='background-color:#FFFF00; padding:5px; font-size:13px;'>";
       $messageResult2.= "<img src='im/fleche_menu.gif' align='absmiddle' border='0'>&nbsp;<span style='font-size:13px;'><b>Uw account is nog niet actief.</b></span><br>";
       $messageResult2.= "<span style='font-size:10px;'>U moet uw account eerst activeren voor u zich kunt uitschrijven</span>";
       $messageResult2.= "</p>";
    }
}

 
if(isset($_GET['rem']) AND $_GET['rem']=="1" AND isset($_GET['email']) AND $_GET['email']!=="") {
    $queryWRec = mysql_query("SELECT newsletter_password FROM newsletter WHERE newsletter_email='".$_GET['email']."'");
    $resultWRec = mysql_fetch_array($queryWRec);
    $activationNum = $resultWRec['newsletter_password'];
     
    $messageToSend = rep_slash($store_company)."\r\n".rep_slash($address_street)."\r\n".$address_cp." - ".rep_slash($address_city)."\r\n".$address_country."\r\n";
    if(!empty($address_autre)) {
        $address_autre2= str_replace("<br>","\r\n",$address_autre);
        $messageToSend.= $address_autre2."\r\n";
    }
    if(!empty($tel)) $messageToSend.= TELEPHONE." : ".$tel."\r\n";
    if(!empty($fax)) $messageToSend.= FAX.": ".$fax."\r\n";
    $messageToSend.= "URL: http://".$www2.$domaineFull."\r\nEmail: ".$mailOrder."\r\n\r\n";
    $messageToSend.= "Hallo,\r\n";
    $messageToSend.= "U heeft een nieuwe activatie code gevraagd voor de nieuwsbrief inschrijving op ".rep_slash($store_name).".\r\n";
    $messageToSend.= "----------------------------------------------\r\n";
    $messageToSend.= "Uw nieuwe activatie code is: ".$activationNum."\r\n";
    $messageToSend.= "----------------------------------------------\r\n";
    $messageToSend.= POUR_PLUS_DINFORMATIONS." ".$mailInfo.".\r\n";
    $messageToSend.= LE_SERVICE_CLIENT;
    
    mail($_GET['email'], "[Activatie nummer nieuwsbrief] - ".rep_slash($store_name), rep_slash($messageToSend),
         "Return-Path: $mailInfo\r\n"
         ."From: $mailInfo\r\n"
         ."Reply-To: $mailInfo\r\n"
         ."X-Mailer: PHP/" . phpversion());
    $messageToDisplay = "<p align='center' style='background-color:#FFFF00; padding:8px; color:#CC0000; font-size:11px;'><b>Er werd een re-activatie code verstuurd naar ".$_GET['email']."</b></p>";
}
?>

<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PHPEd 1.80">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title><?php print $title." | ".$store_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php include('includes/stijl.inc');?>
<?php include('includes/pagina_f5_js.inc');?>

<STYLE TYPE='text/css'>
<!--
BODY             {FONT-FAMILY:Verdana,Helvetica; FONT-SIZE:10px; BACKGROUND-COLOR:#FFFFFF; BACKGROUND-IMAGE:none; }
.fontrouge       {COLOR: #FF0000}
-->
</STYLE>
</head>
<body leftmargin="10" topmargin="10" marginwidth="10" marginheight=10">

<?php
if(isset($logo) AND $logo!=="noPath") print "<p align='center'><img src='".$logo."'></p>";


if(isset($_GET['email']) AND $_GET['email']!=="") {

$verifEmail = mysql_query("SELECT * FROM newsletter WHERE newsletter_email='".$_GET['email']."'");
$verifEmailNum = mysql_num_rows($verifEmail);

if($verifEmailNum == 0) {
    print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
    print "<p align='center'>";
    print "<span class='fontrouge' style='font-size:15px'><b>Adresse email inconnue!</span><br><br>Veuillez saisir une adresse email enregistrée.</b><br><br>";
    print "Email: <input type='text' name='email' value='' size='25'><br><br>";
    print "<INPUT TYPE='submit' VALUE='Continuer'>";
    print "</p>";
    print "</form>";
    exit;
}


print "<table border='0' align='center' cellspacing='0' cellpadding='3' class='TABLEBorderDotted'>";
print "<tr>";
print "<td colspan='2'>";

if(isset($messageResult)) print $messageResult;
if(isset($messageResult2)) print $messageResult2;
if(isset($messageToDisplay)) print $messageToDisplay;

print "<table width='100%' border='0' cellspacing='0' cellpadding='5'>";
print "<tr>";

$queryW = mysql_query("SELECT * FROM newsletter WHERE newsletter_email='".$_GET['email']."'");
$queryWNum = mysql_num_rows($queryW);
if($queryWNum > 0) {
 
$resultW = mysql_fetch_array($queryW);
    if($resultW['newsletter_active']=="no") {
        print "<td colspan='2'>";
        print "<br>";
        print "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='TABLEMenuPathCenter'><tr>";
        print "<td style='".$backgroundTitle."'><b>:::&nbsp;Ik schrijf me in op de nieuwsbrief van ".STRTOUPPER($store_name)."</b></td>";
        print "</tr></table>";
        print "</td>";
        print "</tr><tr>";
        
        print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
        print "<input type='hidden' name='email' value='".$_GET['email']."'>";
        print "<input type='hidden' name='action' value='activer'>";
        print "<td colspan='2'>Hallo,</td></tr><tr>";
        print "<td colspan='2'>Wat zijn uw voordelen om onze nieuwsbrief te ontvangen...?</td></tr><tr>";
        print "<td colspan='2'>";
        print "<div><img src='im/fleche_right.gif'>&nbsp;U ontdekt als eerste alle nieuwigheden en selecties</div>";
        print "<div><img src='im/fleche_right.gif'>&nbsp;U geniet van uitzonderlijk aanbiedingen en kortingen</div>";
        print "<div><img src='im/fleche_right.gif'>&nbsp;Als geabonneerd lid op deze nieuwsbrief, geniet u van de unieke promoties
</div>";
        print "</td></tr><tr>";
        print "<td colspan='2'>Uw e-mail adres is: <b>".$_GET['email']."</b></td></tr><tr>";
        print "<td colspan='2'>Geef uw activatie nummer in: <input type='text' name='activeNumber' size='12'>&nbsp;(<a href='nieuwsbrief_systeem.php?email=".$_GET['email']."&rem=1'><u>Uw activatie nummer opnieuw opvragen</a></u>)</td></tr><tr>";
        print "<td colspan='2'><INPUT TYPE='submit' VALUE='Activeer mijn inschrijving'><br><br></td></tr><tr>";
        print "</form>";
    }
}

print "<td colspan='2'>";




// uitschrijven
print "<br>";
print "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='TABLEMenuPathCenter'><tr>";
print "<td style='".$backgroundTitle."'><b>:::&nbsp;Ik wil geen nieuwsbrief meer ontvangen van ".STRTOUPPER($store_name)."</b></td>";
print "</tr></table>";

print "</td>";
print "</tr><tr>";

print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
print "<input type='hidden' name='email' value='".$_GET['email']."'>";
print "<input type='hidden' name='action' value='annuler'>";
print "<td colspan='2'>Hallo,</td></tr><tr>";
print "<td colspan='2'>U wilt geen nieuwsbrief meer ontvangen...?</td></tr><tr>";
print "<td colspan='2'>Uw e-mail adres is: <b>".$_GET['email']."</b></td></tr><tr>";

print "<td colspan='2'><INPUT TYPE='submit' VALUE='Ik wil me nu uitschrijven'></td></tr><tr>";
print "</form>";

print "</table>";

print "</td></tr></table>";
}
else {
    print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
    print "<p align='center'>";
    print "<b>Veuillez saisir une adresse email enregistrée.</b><br><br>";
    print "Email: <input type='text' name='email' value='' size='25'><br><br>";
    print "<INPUT TYPE='submit' VALUE='Ga verder'>";
    print "</p>";
    print "</form>";
}
?>


</body>
</html>
