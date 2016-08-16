<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

 
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/berekenen.php");
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></span></p>

<br>
<table align="center" border="0" cellpadding="10" cellspacing="0" class="TABLE" width="700"><tr><td align='center'>
&bull;&nbsp;<a href="berekenen.php"><?php print GENERAL;?></span></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
&bull;&nbsp;<a href="verkoop_op_artikel.php"><?php print A201;?></span></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
&bull;&nbsp;<a href="verkoop_op_klant.php"><?php print A210;?></span></a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
&bull;&nbsp;<a href="verkoop_op_leverancier.php"><?php print A200;?></span></a>
</td></tr></table>

</body>
</html>
