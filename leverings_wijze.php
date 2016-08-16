<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');
include("includes/lang/lang_".$_SESSION['lang'].".php");

$title = MODE_DE_LIVRAISON;
?>
<html>
<head>
<?php include('includes/hoofding.php');?>
</head>
<body leftmargin="0" topmargin="10" marginwidth="10" marginheight="10">
<table width="99%" height="99%" border="0" cellpadding="0" cellspacing="5" class="TABLEBackgroundBoutiqueCentre"><tr><td valign="top">

<?php
if(isset($_GET['id'])) {
  $requestShipping = mysql_query("SELECT livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$_GET['id']."'") or die (mysql_error());
  if(mysql_num_rows($requestShipping) > 0) {
     $resultShipping = mysql_fetch_array($requestShipping);
     if($resultShipping['livraison_note_'.$_SESSION['lang']]!=="") {
        print $resultShipping['livraison_note_'.$_SESSION['lang']];
     }
  }
}
else {
   print "<p>".VEUILLEZ_CHOISIR_MODE_DE_LIVRAISON."</p>";
   $requestShipping = mysql_query("SELECT * FROM ship_mode WHERE livraison_active='yes'") or die (mysql_error());
   if(mysql_num_rows($requestShipping) > 0) {
      while($resultShipping = mysql_fetch_array($requestShipping)) {
         print "<div>- <a href='leverings_wijze.php?id=".$resultShipping['livraison_id']."'>".$resultShipping['livraison_nom_'.$_SESSION['lang']]."</a></div>";
      }
   }
}
?>
</td></tr></table>
</body>
</html>
