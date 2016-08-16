<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');     
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="30" marginwidth="30" marginheight="30">

<?php

$requestCountry = mysql_query("SELECT iso, countries_name
                      FROM countries
                      WHERE country_state = 'country'
                      ORDER BY countries_name");
$o=1;
$c="";
 print "<p><table border='0' cellspacing='0' cellpadding='3' class='TABLE'><tr bgcolor='#FFFFFF'>
<td>#</td><td><b>PAYS/COUNTRIES</b></td><td align='center'><b>ISO code</b></td></tr>";

while ($countries = mysql_fetch_array($requestCountry)) {
        if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
       print "<tr bgcolor='".$c."'><td>".$o++."</td><td>".$countries['countries_name']."</td><td width='100' align='center'>".$countries['iso']."</td></tr>";
       }
print "</table></p>";
?>



  </body>
  </html>
