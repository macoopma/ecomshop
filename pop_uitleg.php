<?php
session_start();
include('configuratie/configuratie.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = MODE_EMPLOI;
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">

<table width="300" class="TABLESortByCentre" align="center" border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td width="1"><img src="im/download.gif"></td>
    <td><?php print ARTICLE_TELE;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/cart.gif"></td>
    <td><?php print CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/lang<?php print $_SESSION['lang'];?>/stockok.png"></td>
    <td><?php print EN_STOCK;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/stockin.gif"></td>
    <td><?php print EN_COMMANDE;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/stockno.gif"></td>
    <td><?php print NOT_IN_STOCK;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/no_stock.gif"></td>
    <td><?php print ITEMS_OUT_OF_STOCK;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/coeur.gif"></td>
    <td><?php print COEUR;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/time_anim.gif"></td>
    <td><?php print VENTES_FLASH;?></td>
  </tr>
  <tr>
    <td width="1"><img src="im/degressif_logo.png"></td>
    <td><?php print PRODUIT_A_PRIX_DEGRESSIF;?></td>
  </tr>
</table>
</body>
</html>
