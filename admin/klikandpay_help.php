<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include("../configuratie/configuratie.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Configuration g�n�rale KLIK&PAY</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/Blanc-White.css" rel="stylesheet" type="text/css">
</head>

<body>
<table border="0" align="center" cellpadding="15" cellspacing="0" class="TABLE1">
  <tr>
    <td><div align="center" class="titre">Configuration g&eacute;n&eacute;rale de votre compte KLIK&PAY</div></td>
  </tr>
</table>
<br>
<table border="0" cellpadding="5" cellspacing="0" class="TABLEBorderDotted">
  <tr>
    <td><strong>Coller/copier les URL suivantes dans votre compte KLIK&PAY.</strong></td>
  </tr>
</table>
<br>

&bull;&nbsp;<span style="font-size:12px;"><u><i>Votre compte KLIK&PAY > Param�trage du compte > Param�trage</i></u></span><br><br>

<table border="0" cellpadding="2" cellspacing="0" style="background:#3366FF"><tr><td><span style="color:#FFFFFF"><b>Param�trage des URL de retour :</b></span></td></tr></table>
<p><strong>URL transaction accept�e :</strong> <span class="fontrouge"><strong><br>
  http://<?php print $www2.$domaineFull;?>/ok.php?lang=</strong></span></p>
<p><strong>URL transaction refus�e : </strong><br>
  <span class="fontrouge"><strong>http://<?php print $www2.$domaineFull;?>/index.php</strong></span></p>

<table border="0" cellpadding="2" cellspacing="0" style="background:#3366FF"><tr><td><span style="color:#FFFFFF"><b>URL autoris�es :</b></span></td></tr></table>
<p><strong>Demander l'ajout de cette URL :</strong><br>
  <span class="fontrouge"><strong>http://<?php print $www2.$domaineFull;?></strong></span></p>
  

&bull;&nbsp;<span style="font-size:12px;"><u><i>Votre compte KLIK&PAY > Param�trage du compte > Retour Dynamique</i></u></span><br><br>

<table border="0" cellpadding="2" cellspacing="0" style="background:#3366FF"><tr><td><span style="color:#FFFFFF"><b>URL de votre script de validation :</b></span></td></tr></table>
<p><strong>URL de Retour dynamique : </strong><br>
  <span class="fontrouge"><strong>http://<?php print $www2.$domaineFull;?>/kandp/result.php?p=ok&nic=</strong></span></p>

<table border="0" cellpadding="2" cellspacing="0" style="background:#3366FF"><tr><td><span style="color:#FFFFFF"><b>Informations � retourn�es :</b></span></td></tr></table>
<p><strong>Cocher </strong> "Montant de la transaction"</p>

<br>
<br>
<b><u><span style="font-size:12px;">NOTES</span></u></b><br><br>
&bull;&nbsp;<span style="font-size:12px;"><u>Pour passer en mode production et recevoir des paiements r�els :</u></span><br>
- Dans la partie administration de votre boutique, activez le paiement Klik&Pay et Mode test = Non<br>
- Dans votre compte KLIK&PAY > Param�trage du compte > TEST / PRODUCTION, cliquez sur "Passer en mode PRODUCTION".<br>
<br>
&bull;&nbsp;<span style="font-size:12px;"><u>Votre compte KLIK&PAY > Param�trage du compte > Param�trage :</u></span><br>
- Ajoutez le num�ro de t�l�phone du service client dans "Service client�le".<br>
- Ajoutez emails dans "R�ception des notifications de transaction" et "E-mail service Client".<br> 
</body>
</html>.
