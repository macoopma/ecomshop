<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Configuration générale BluePaid</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../css/Blanc-White.css" rel="stylesheet" type="text/css">
</head>

<body>
<table border="0" align="center" cellpadding="15" cellspacing="0" class="TABLE1">
  <tr>
    <td><div align="center" class="titre">Configuration g&eacute;n&eacute;rale 
        de votre compte BLUEPAID</div></td>
  </tr>
</table>
<br>
<table border="0" cellpadding="5" cellspacing="0" class="TABLEBorderDotted">
  <tr>
    <td><strong>Coller/copier les URL suivantes dans &quot;Mon compte&quot; BLUEPAID.</strong></td>
  </tr>
</table>
<p><strong>Adresse site internet r&eacute;f&eacute;rant :</strong> <span class="fontrouge"><strong><br>
  <?php print $www2.$_GET['domaine'];?>;<?php print $_GET['domaine'];?></strong></span></p>
<p><strong>URL du Logo : </strong><br>
  <span class="fontrouge"><strong><?php print $www2.$_GET['url'];?>/im/logo.gif</strong></span></p>
<p><strong>URL de retour apres transaction :</strong><br>
  <span class="fontrouge"><strong><?php print $www2.$_GET['url'];?>/ok.php</strong></span></p>
<p><strong>URL de retour : </strong><br>
  <span class="fontrouge"><strong><?php print $www2.$_GET['url'];?>/index.php</strong></span></p>
<p><strong>URL de confirmation : <br>
  <span class="fontrouge"><strong><?php print $www2.$_GET['url'];?>/</strong>bluePaid/bluepaid_bddupdate.php</span> 
  </strong></p>
<p><strong>Retour automatique :</strong><br>
  Cliquer sur &quot;Activ&eacute;&quot;.</p>
</body>
</html>
