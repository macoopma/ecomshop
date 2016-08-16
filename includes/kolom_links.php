<td width="1" valign="top" class="backgroundTDColonneModuleLeft">
<?php


// module artikels
if($menuCssVisibleVertical=="oui") {
include('includes/menu_categories_layer_verticaal.php');
}

// module cats
if($menuVisiblePhp == "oui") {
include('includes/menu_categories.php');
}

/*
// module aanbied
if($nouvVisible == 'oui') {
include('includes/menu_nieuws_def.php');
}
*/

// module zoeken
if($moteurVisible=="oui") {
include('includes/menu_zoeken.php');
}

// module nieuw
if($nouvVisible == 'oui') {
include('includes/menu_nieuws.php');
}

// module offertes
if($devisModule == 'oui' AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
include('includes/menu_valuta.php');
}

// module RSS
if($activeRSSMod == "oui" AND $activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
include('includes/menu_rss.php');
}

// module Newsletter
if($newsletterVisible=="oui") {
include('includes/menu_inschrijven.php');
}

// module Affiliate
if($affiliateVisible=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
include('includes/menu_affiliate.php');
}

// module navigatie 
if($menuNavCom == "oui") {
include('includes/menu_bericht.php');
}

// module diverse
if($menuNavVisible=="oui") {
include('includes/menu_navigatie.php'); 
}
?>
</td>

