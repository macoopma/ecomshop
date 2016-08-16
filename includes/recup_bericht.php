<?php
 
if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes") {
	print "<div align='center' style='color:#FFFFFF; background:#808080; border:#FFFF00 1px solid; padding:7px;'>";
	print "<b><span style='font-size:13px;'>** ".COMPTE_CLIENT_OUVERT_COMMANDE_RECUPEREE." **</span></b>";
	print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	print "<span style='font-size:13px;'>".VEUILLEZ_MODIFIER_LA_COMMANDE_PUIS_CLIQUEZ_CADDIE." ".$_SESSION['clientNicZ'].".</span>";
	print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	print "<i><a href='".$_SERVER['PHP_SELF']."?cancel=1'><span style='color:#FFFF00'>".CLIQUEZ_ICI_POUR_ANNULER."</span></a></i>";
	print "</div>";
}
?>
