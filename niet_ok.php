<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');
 
if(!isset($_GET['lang'])) $_GET['lang'] = $langue;
if($_GET['lang']==1) $_SESSION['lang']=1;
if($_GET['lang']==2) $_SESSION['lang']=2;
if($_GET['lang']==3) $_SESSION['lang']=3;

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = CONFIRMATION_DE_PAIEMENT;
$displayPayments = 0;
$_SESSION['userInterface'] = $colorInter;
$_SESSION['storeWidthUser'] = $storeWidth;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table height="100%" width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<?php
//   header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}
//   header2
if($header2Display=='oui') {
   print "<td colspan='3' height='1'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3' valign='top' height='28'>";
}
else {
   print "<td colspan='3' valign='top' height='28'>";
}
?>


      <table height="32" width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPage">
      <tr>
      <td><b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php?lang=<?php print $_SESSION['lang'];?>"><?php print strtoupper(HOME);?></a></b></td>
      </tr>
      </table>


</td>
</tr>
<tr>
<td valign="top" colspan="3">
<?php
print "<table width='100%' align='center' border='0' cellpadding='10' cellspacing='0' class='TABLEPageCentreProducts'><tr>";
print "<td valign='top'>";
print BONJOUR.",<br><br>";
print VOTRE_PAIEMENT_REFUSE."<br><br>";
print "<a href='cataloog.php'><img src='im/fleche_menu2.gif' border='0' align='absmiddle'>&nbsp;<b>".CLOSE_DEVIS."</b></a>";
print "</td>";
print "</tr><tr>";
print "<td valign='bottom' align='left'>";
print "<div><img src='im/zzz.gif' width='1' height='20'></div>";
print "<b>".$store_company."</b><br>";
print $address_street."<br>";
print $address_cp." - ".$address_city."<br>";
print $address_country;
if(!empty($address_autre)) print "<br>".$address_autre;
if(!empty($tel)) print "<br>".TELEPHONE." : ".$tel;
if(!empty($fax)) print "<br>".FAX." : ".$fax;
print "</td>";
print "</tr></table>";
?>
</td>
</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>