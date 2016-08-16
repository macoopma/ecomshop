<?php

$activeRoundedNewsPromos = "yes";
if($activeRoundedNewsPromos=="yes") $TABLEPromoNewsBottomPageZ = ""; else $TABLEPromoNewsBottomPageZ="TABLEPromoNewsBottomPage";

if($nouvVisible == 'oui' AND $promoVisible == 'oui') {
	if($activeRoundedNewsPromos=='yes') round_top('yes',"100%",'raised3');
	print "<table border='0' width='100%' cellspacing='0' cellpadding='5' align='center' class='".$TABLEPromoNewsBottomPageZ."'>";
	print "<tr height='25'>";
	print "<td align='center'>";
	print "| <a href='list.php?target=new'><b>".NOUVEAUTESMAJ."</b></a> | ";
	print "<a href='list.php?target=promo'><b>".maj(PROMOTIONS)."</b></a> |";
	print "</td>";
	print "</tr>";
	print "</table>";
	if($activeRoundedNewsPromos=='yes') round_bottom('yes');
}

if($nouvVisible == 'non' AND $promoVisible == 'oui' AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
	if($activeRoundedNewsPromos=='yes') round_top('yes',"100%",'raised3');
	print "<table border='0' width='100%' cellspacing='0' cellpadding='5' align='center' class='".$TABLEPromoNewsBottomPageZ."'>";
	print "<tr>";
	print "<td align='center'>::<a href='list.php?target=promo'><b>".maj(PROMOTIONS)."</b></a>::</td>";
	print "</tr>";
	print "</table>";
	if($activeRoundedNewsPromos=='yes') round_bottom('yes');
}

if($nouvVisible == 'oui' AND $promoVisible == 'non') {
	if($activeRoundedNewsPromos=='yes') round_top('yes',"100%",'raised3');
	print "<table border='0' width='100%' cellspacing='0' cellpadding='5' align='center' class='".$TABLEPromoNewsBottomPageZ."'>";
	print "<tr>";
	print "<td align='center'>::<a href='list.php?target=new'><b>".NOUVEAUTESMAJ."</b></a>::</td>";
	print "</tr>";
	print "</table>";
	if($activeRoundedNewsPromos=='yes') round_bottom('yes');
}
?>
