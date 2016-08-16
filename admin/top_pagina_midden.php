<?php
session_start();

function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>
<html>
<head>
<title><?php print A2;?></title>
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table align="center" border='0' cellspacing='0' cellpadding='3' width='100%'><tr>
<td valign="top">
</td>
</tr>
</table>

</BODY>
</HTML>
