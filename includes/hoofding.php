<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<title><?php print $title." - ". $store_name; ?></title>
<link rel="shortcut icon" href="http://<?php print $www2.$domaineFull;?>/favicon.ico" type="image/x-icon">


<?php
// ------------ Include flux RSS
if($activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
   // Actus RSS
   if($activeActu=="oui") echo "<link alt='".$domaine." - ACTUALITES' title='".$domaine." - ACTUALITES' rel='alternate' type='application/rss+xml' href='rss/rss_actueel.php'>";
   // Nouveautes RSS
   if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) echo "<link alt='".$domaine." - NOUVEAUTES' title='".$domaine." - NOUVEAUTES' rel='alternate' type='application/rss+xml' href='rss/rss_nieuws.php'>";
   // Promo RSS
   if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) echo "<link alt='".$domaine." - PROMOTIONS' title='".$domaine." - PROMOTIONS' rel='alternate' type='application/rss+xml' href='rss/rss_promoties.php'>";
   // Ventes Flash RSS
   if($activeSeuilPromo=="oui" AND $seuilPromo > 0) echo "<link alt='".$domaine." - VENTES FLASH' title='".$domaine." - VENTES FLASH' rel='alternate' type='application/rss+xml' href='rss/rss_flash.php'>";
   // Top 10 RSS
   echo "<link alt='".$domaine." - NOS MEILLEURES VENTES' title='".$domaine." - NOS MEILLEURES VENTES' rel='alternate' type='application/rss+xml' href='rss/rss_top10.php'>";
}
?>

<?php
// ------------ Include style sheet
include('includes/stijl.inc');
?>

<?php
// ------------ Include header background image
if(isset($backgroundImageHeader) AND $backgroundImageHeader!=="noPath") {
print '<style type="text/css">.backGroundTop {background-color: #none; background-image: url('.$backgroundImageHeader.'); background-repeat: no-repeat; background-position: right top}</style>';
}

//print '<style type="text/css">BODY {BACKGROUND-IMAGE: url(im/background/bg5.gif); background-position: top center; }</style>';
?>

<?php
// ------------ Include IE CSS hack
if($menuCssVisibleVertical=="oui" OR $menuCssVisibleHorizon=="oui") {
print '<!--[if lte IE 8]>
<style type="text/css">
body {behavior: url(csshover.htc);}
</style>
<![endif]-->
';
}
?>

<?php
// ------------ Include JS Popup 1euro
if($euroPayment == "oui" AND $displayGraphics == "oui") {
	print '<script type="text/javascript" src="http://partenaires.1euro.com/partenaires/js/popup.js"></script>';
}
?>

<script language=JavaScript src='includes/popImage.js'></script>

<script type="text/javascript"><!--
function add_favoris(mag,art,url) {
if( navigator.appName != 'Microsoft Internet Explorer' ) { 
    window.sidebar.addPanel(mag+' | '+art,url,""); 
}
else { 
    window.external.AddFavorite(url,mag+' - '+art); 
}
}
//--> </script>

<script type="text/javascript">
   function sendIt(fileName) {
      if(fileName != "") {
         (fileName != "gebruik_bonuspunten.php") ? location.href=fileName : window.open('gebruik_bonuspunten.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=190,width=500,toolbar=no,scrollbars=no,resizable=yes')
      }
   }
</script>

<?php
include('includes/pagina_f5_js.inc');
?>