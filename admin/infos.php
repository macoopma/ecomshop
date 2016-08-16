<?php
session_start();

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" cellpadding="5" cellspacing="0"><tr><td>
<?php 
if(isset($_GET['from']) AND $_GET['from']=="client") {
   print TRANSMIS;
}
if(isset($_GET['from']) AND $_GET['from']=="devis") {
   print DEVIS;
}
if(isset($_GET['from']) AND $_GET['from']=="affiliate") {
   print AFFILIATE;
}
if(isset($_GET['from']) AND $_GET['from']=="cheque") {
   print CHEQUE;
}
if(isset($_GET['from']) AND $_GET['from']=="com") {
   print COM;
}
if(isset($_GET['from']) AND $_GET['from']=="products") {
   print PROD;
}
?>
</tr></td></table>
</body>
</html>
