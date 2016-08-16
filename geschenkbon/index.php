<?php

 include('../configuratie/configuratie.php');

$la_link = "../includes/lang/geschenkbon/lang".$_GET['l']."/index.php";
include($la_link);


 if(isset($_GET['cert'])) {
    if(empty($_GET['cert'])) {
       $message = INT1."!";
    }
    else {
       $query = mysql_query("SELECT * 
                            FROM gc 
                            WHERE gc_number = '".$_GET['cert']."' 
                            AND gc_payed='1' AND gc_enter='0'
                            AND TO_DAYS(NOW()) - TO_DAYS(gc_end) <= '0'");
       $num = mysql_num_rows($query);
       if($num > 0) {
          header("Location: gen.php?cert=".$_GET['cert']."&l=".$_GET['l']."");
       }
       else {
          $message = INT2." <b>".strtoupper($_GET['cert'])." </b>".INT500;
       }
    }
 }
?>
<html>
<head>

<title>Interface login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/Blanc-White.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="90%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="middle" align="center">
    <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <form action="<?php print $_SERVER['PHP_SELF'];?>" method="GET">
    
        <input type="hidden" name="l" value="<?php print $_GET['l'];?>">
    <td>
     <b><?php print INT5000;?></b> : <input type="text" name="cert">
     <input type="submit" value="=>">
     <?php if(isset($message) and !empty($message)) print "<p align=\"center\" class=\"fontrouge\">".$message."</p>";?>
    </td>
    </form>
    </tr></table>
    <br><br><br>




    </td>
  </tr>
</table>
</body>
</html>
