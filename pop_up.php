<?php

$im1 = explode("/",$_GET['im']);
?>
<html>
<head>
<title>
<?php print $im1[1];?>
</title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" OnClick="javascript:window.close()">
<?php
print "<img src='".$_GET['im']."' border='0'>";
?>
</body>
</html>
