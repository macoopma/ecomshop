<?php
session_start();
 
if(!isset($_SESSION['login'])) header("Location:index.php");
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold">Domeinnaam sleutel</p>


<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
<tr>
<td align="center">
<b>
<?php include("../versie.txt");?>
</b>
</td>
</tr>
</table>

  </body>
  </html>
