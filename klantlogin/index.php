<?php

include('../configuratie/configuratie.php');
$la_link = "../includes/lang/klantlogin/lang1/login.php";
include($la_link);
$modWidth="450";
?>
<html>
<head>

<title>:: <?php print $store_name;?> ::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/<?php print $colorInter;?>.css" type="text/css">
                 <STYLE TYPE="text/css">
                 <!--
                 .TABLE50           {BACKGROUND-COLOR:#f1f1f1; border: 1px #CCCCCC solid }
                 -->
                 </STYLE>


</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style='background-image:none; background:#none'>
<br>

   <table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE50"><tr><td align='center' style="padding:8px; FONT-SIZE:11px; border:1px #CCCCCC dotted">
   <b>KLANT - CLIENT - CUSTOMER INTERFACE</b>
   </td></tr></table>
   
   <p align="center">
      <a href="index2.php?l=3">Nederlands</a>&nbsp;|&nbsp;<a href="index2.php?l=1">Français</a>&nbsp;|&nbsp;<a href="index2.php?l=2">English</a>
   </p>
   
</body>
</html>
