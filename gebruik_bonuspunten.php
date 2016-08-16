<?php
session_start();
include('configuratie/configuratie.php');
include('includes/doctype.php');

if(!isset($_SESSION['lang'])) $_SESSION['lang']=1;
include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = POINTS_FIDELITE;
$euro = $remisePastOrder/100;
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
    <table class="TABLE1" align="center" border="0" cellpadding="5" cellspacing="0">
      <tr><td>

<?php 
if(!isset($_GET['viewFid'])) {
if($_SESSION['lang']==1) {;?>
  	<div align="left">Notre programme de fidélité vous permet de gagner des points après chaque achat effectué sur <?php print $store_name;?>.</div>    
    <p align="center">
        <b>1 <?php print $devise;?> d'achat = 1 Point de fidélité<br>
        1 Point = Remise de <?php print sprintf("%0.2f",$euro)." ".$symbolDevise;?><br>
        100 Points = Remise de <?php print sprintf("%0.2f",$remisePastOrder)." ".$symbolDevise;?></b>
    </p>
    <p>
        <u>COMMENT OBTENIR DES POINTS FIDELITE ?</u>
        <li>Chaque achat dans la boutique  vous donne droit à des points fidélité.</li>
        <li>"Votre compte" garde en mémoire vos points fidélité.</li>
        <li>Les points gardés en mémoire sont convertibles en remise dès votre prochaine commande.</li>
    </p>
<?php }?>

<?php if($_SESSION['lang']==2) {;?>
  	<div align="left">Our program enables you to earn points for each purchase made from the shop <?php print $store_name;?>.</div>    
    <p align="center">
        <b>1 <?php print $devise;?> of purchase = 1 Point<br>
        1 Point = <?php print sprintf("%0.2f",$euro)." ".$symbolDevise;?><br>
        100 Points = <?php print sprintf("%0.2f",$remisePastOrder)." ".$symbolDevise;?></b>
    </p>
    <p>
        <u>HOW TO WIN POINTS ?</u>
        <li>Each purchase in <?php print $store_name;?> gives you points.</li>
        <li>"Your account" keeps your points in memory.</li>
        <li>Theses points gives you your discount amount for the next purchase.</li>
    </p>
<?php }?>

<?php if($_SESSION['lang']==3) {;?>
  	<div align="left">Verdien extra bonus punten bij elke bestelling die u doet op <?php print $store_name;?>.</div>    
    <p align="center">
        <b>1 <?php print $devise;?> = 1 punt<br>
        1 punt = <?php print sprintf("%0.2f",$euro)." ".$symbolDevise;?><br>
        100 punten = <?php print sprintf("%0.2f",$remisePastOrder)." ".$symbolDevise;?></b>
    </p>
    <p>
        <u>Hoe kunt u punten winnen?</u>
        <li>Bij elke aankoop op <?php print $store_name;?> verdient u punten.</li>
        <li>In "Uw account" worden de punten bij gehouden.</li>
        <li>U kunt deze verdiende punten bij een volgende aankoop gebruiken of verder sparen.</li>
    </p>
<?php 
}
}
else {
?>
<div align='left'>
<?php print SUP_FID;?>

</div>
<?php
}
?>

    </td></tr></table>
</body>
</html>
