<td width="1" valign="top" class="backgroundTDColonneModuleRight">
<?php


// Module AddThis
if($addThisVisible == "oui") {
include('includes/menu_dit_toevoegen.php');
}

// module account
if($idVisible == 'oui') {
include('includes/menu_id.php');
}

// module mandje
if($cartVisible == "oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
include('includes/menu_winkelmandje.php');
}

// module snel menu
if($menuRapideVisible == "oui") {
include('includes/menu_snel.php'); 
}

/*
// module promotie
if($promoVisible == 'oui') {
include('includes/menu_promoties_def.php');
}
*/

// module Promotie
if($promoVisible == 'oui') {
include('includes/menu_promoties.php');
}

// module Laatst gezien 
if($lastViewVisible=="oui") {
include('includes/menu_laatst_gezien.php');
}

// module Top 10
if($topVisible == 'oui') {
include('includes/menu_top10.php');
}

// module taal
if($langVisible=="oui" AND $_SESSION['langDispoZ']>1) {
include('includes/menu_talen.php');
}

// module Interface
if($interfaceVisible=="oui") {
include('includes/menu_interface.php');
}

// module valuta
if($converter=="oui") {
include('includes/menu_valuta_berekenen.php');
}

// module Informatie
if($information == 'oui') {
include('includes/menu_info.php');
}
?>
</td>
