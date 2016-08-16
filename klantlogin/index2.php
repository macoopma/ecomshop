<?php

 include('../configuratie/configuratie.php');

$la_link = "../includes/lang/klantlogin/lang".$_GET['l']."/login.php";
include($la_link);
$modWidth="450";

 if(isset($_GET['nic'])) {
    if(empty($_GET['nic'])) {
       $message = INT1."!";
    }
    else {
       $query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_GET['nic']."'");
       $num = mysql_num_rows($query);
       if($num > 0) {
         $row = mysql_fetch_array($query);
          if($row['users_customer_delete']=='yes') {
               $message = INT2." <b>".strtoupper($_GET['nic'])."</b>!<br>".INT500;
         }
         else {
               header("Location: login.php?nic=".$_GET['nic']."&l=".$_GET['l']."");
         }
       }
       else {
          $message = INT2." <b>".strtoupper($_GET['nic'])."</b>!<br>".INT500;
       }
    }
 }
?>
<html>
                 <STYLE TYPE="text/css">
                 <!--
                 .TABLE50           {BACKGROUND-COLOR:#f1f1f1; border: 1px #CCCCCC solid }
                 -->
                 </STYLE>
<head>

<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/<?php print $colorInter;?>.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style='background-image:none; background:#none'>
<br>

   <table width="<?php print $modWidth;?>" align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE50"><tr><td align='center' style="padding:8px; FONT-SIZE:11px; border:1px #CCCCCC dotted">
   <b><?php print strtoupper(INTERFACEZ);?></b>
   </td></tr></table>
   <br>
   
    <table border="0" align="center" cellpadding="0" cellspacing="0">
    <tr><form action="<?php print $_SERVER['PHP_SELF'];?>" method="GET">
        <input type="hidden" name="l" value="<?php print $_GET['l'];?>">
    <td>
     <b>NIC</b> <i><?php print NICOZ;?></i>: <input type="text" name="nic">
     <input type="submit" value="=>">
     <?php if(isset($message) and !empty($message)) print "<br><span class='fontrouge'>".$message."</span>";?>
    </td>
    </form>
    </tr></table>

</body>
</html>
