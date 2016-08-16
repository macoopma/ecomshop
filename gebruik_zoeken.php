<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = MODE_EMPLOI;
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
    <table class="TABLESortByCentre" align="center" border="0" cellpadding="5" cellspacing="0">
      <tr><td>
        <?php print AIDE;?>
      </td></tr>
    </table>
</body>
</html>
