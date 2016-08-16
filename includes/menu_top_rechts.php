<?php 


if(isset($_SESSION['account']) OR $activeEcom=="oui") {
	if(!isset($_SESSION['list']) OR $_SESSION['list'] == "" OR empty($_SESSION['list'])) {
		$cady =  "<td>&nbsp;<a href='caddie.php'><b>".VOTRE_CADDIE."</b></a>&nbsp;|</td>";
	}
	else {
		$cady = "<td>&nbsp;<a href='caddie.php'><b>".VOTRE_CADDIE."</b></a></td>";
		$cady.= "<td><a href='caddie.php'><img src='im/cart2.gif' border='0'></a></td><td>&nbsp;|</td>";
	}
}

print ($tableDisplayLeft=='oui')? "<td width='340' align='right' valign='middle'>" : "<td align='right' valign='middle'>";
	print "<div align='right'>";
	if(isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes" OR $activeEcom=="oui") {
	      print "<table border='0' align='right' cellspacing='0' cellpadding='0'>";
		  print "<tr valign='middle'>";
	      print "<td>|&nbsp;<a href='mijn_account.php'><b>".IDENTIFIEZ_VOUS."</b></a>&nbsp;|</td>";
	      if($affiliateVisible=="oui") print "<td>&nbsp;<a href='affiliation.php'><b>".AFFILIATION."</b></a>&nbsp;|</td>";

		  print $cady;
 
	      if($activeRSS=="oui") print "<td>&nbsp;<a href='rss.php'><img align='absmiddle' src='im/rss_small.gif' border='0' title='".DIFFUSEZ_CONTENU."' alt='".DIFFUSEZ_CONTENU."'></a></td>";
 
 
 
	      print "</tr></table>";
	}
	else {
	      print "<table border='0' align='right' cellspacing='0' cellpadding='0'>";
		  print "<tr valign='middle'>";
	      print "<td>|&nbsp;<a href='mijn_account.php'><b>".IDENTIFIEZ_VOUS."</b></a>&nbsp;|</td>";
	      print "<td>&nbsp;<a href='infos.php?info=5'><b>".NOUS_CONTACTER."</b></a>&nbsp;|</td>";
 
 
 
	      print "</tr></table>";
	}
	print "</div>";
print "</td>";
?>
