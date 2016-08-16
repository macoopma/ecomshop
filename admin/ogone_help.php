<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Ogone</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/Blanc-White.css" rel="stylesheet" type="text/css">
</head>

<body>
<table border="0" align="center" cellpadding="15" cellspacing="0" class="TABLE1">
  <tr>
    <td><div align="center" class="titre">Configuration g&eacute;n&eacute;rale 
        de votre compte OGONE</div></td>
  </tr>
</table>
<table border="0" cellpadding="5" cellspacing="0" class="TABLEBorderDotted">
  <tr>
    <td><strong>Param&egrave;tres d'int&eacute;gration - Menu &quot;Information 
      technique&quot;.</strong></td>
  </tr>
</table>
<p><strong>Abonnement</strong> : Ogone e-Commerce Pro
</p>
<p>
<strong>Information technique > Affichage de la page de paiement</strong><br>
URL de la page Web à afficher au client quand il clique sur le bouton "retour" sur notre page de paiement sécurisée. :<br>
<span class="fontrouge">
<strong>
<?php 
if($www2=="www." OR $www2=="") print "http://www.".$_GET['url']."/cataloog.php";
if($www2!=="" AND $www2!=="www.") print "http://".$www2.$_GET['url']."/cataloog.php";
?>
</strong>
</span>
</p>


<p>
<strong>Information technique > Contrôle de données et d'origine</strong><br>
URL de la page du marchand contenant le formulaire de paiement qui appellera la page:orderstandard.asp :<br>
<span class="fontrouge">
<strong>
<?php 
if($www2=="www." OR $www2=="") print "http://www.".$_GET['url']."/ogone/ogone_payment.php;http://".$_GET['url']."/ogone/ogone_payment.php";
if($www2!=="" AND $www2!=="www.") print "http://".$www2.$_GET['url']."/ogone/ogone_payment.php";
?>
</strong>
</span>
</p>


<p>
<strong>Information technique > Retour d'information sur la transaction</strong><br>
Si le statut du paiement est "accepté", "en attente" ou "incertain" : <span class="fontrouge"><strong>http://<?php print $www2.$_GET['url'];?>/ogone/ogone.php</strong></span>
<br>
Si le statut du paiement est "annulé par le client" ou "trop de rejets par l'acquéreur" : <span class="fontrouge"><strong>http://<?php print $www2.$_GET['url'];?>/index.php</strong></span>
</p>

<br><br>
<table border="0" cellpadding="5" cellspacing="0" class="TABLEBorderDotted" style="background-color:#CCCCCC">
  <tr> 
    <td><strong>Ci-dessous, voici un exemple de copie d'&eacute;cran de l'interface de configuration Ogone, menu 
  &quot;Information technique&quot;.</strong></td>
  </tr>
</table>
<p><img src="im/ogone_config.gif"> </p>
</body>
</html>
