<?php include('includes/doctype.php');?>
<html>
<head>
<title>HTML Code</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/scss.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000">

<?php
$_GET['url'] = str_replace("__lang=","&lang=",$_GET['url']);

if(strlen($_GET['url'])!==0) {
print "<table border='0' align='center' width='500' cellpadding='0' cellspacing='5' class='TABLEBackgroundBoutiqueCentre'><tr><td>";
	include('includes/doctype.php');
    $contenu = file( $_GET['url'] );
    while (list($numero_ligne, $ligne) = each($contenu )) {
        echo htmlspecialchars( $ligne ) . "<br>";
    }
    print "</td></tr></table>";
}
?>
</body>
</html>
