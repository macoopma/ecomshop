<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
include("sitemap_yahoo.php");

if(isset($_GET['action']) AND $_GET['action']=="yahoo") {
    build_yahoo_file($_SESSION['lang']);
    $mess = "<p class=fontrouge align=center><b>Het bestand werd succesvol aangemaakt</b></p>";
}

?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold">Maak een Yahoo sitemap bestand aan</p>


<table class="TABLE" align="center" border="0" cellpadding="10" cellspacing="0" width="700"><tr><td align="center">
<a href="yahoo_google_sitemap.php?action=yahoo"><?php print A1;?></span></a>
</td></tr></table>

<br>
<div align="center">
<?php print A2;?><br><br>
<?php print A3;?> : <a href="http://<?php print $www2.$domaineFull;?>/url_lijst.txt" target="_blank">http://<?php print $www2.$domaineFull;?>/url_lijst.txt</a>
</div>

<?php
if(isset($mess)) print $mess;
?>
<br>

<br>

<p align="center">
<b>Info Google</b>   
<a href="http://www.google.be/support/webmasters/bin/answer.py?answer=34657" target="_blank"><img src="im/be.gif" border="0"></a>&nbsp;&nbsp;
<a href="http://www.google.nl/support/webmasters/bin/answer.py?answer=34657" target="_blank"><img src="im/nl.gif" border="0"></a>&nbsp;&nbsp;
<a href="http://www.google.fr/support/webmasters/bin/answer.py?answer=34657" target="_blank"><img src="im/fr.gif" border="0"></a>&nbsp;&nbsp;

</p>

<p align="center">
<b>Info Yahoo!</b>   
<a href="http://siteexplorer.search.yahoo.com/free/request" target="_blank"><img src="im/be.gif" border="0"></a>&nbsp;&nbsp;<a href="http://siteexplorer.search.yahoo.com/free/request" target="_blank"><img src="im/nl.gif" border="0"></a>&nbsp;&nbsp;
<a href="http://fr.search.yahoo.com/free/request" target="_blank"><img src="im/fr.gif" border="0"></a>&nbsp;
</p>

  </body>
  </html>
