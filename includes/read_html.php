<?php
include('../configuratie/configuratie.php');
?>
<html>
<head>
<title>Lees in HTML</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="http://<?php print $www2.$domaineFull;?>/css/<?php print $colorInter;?>.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000">

<?php
if(strlen($_GET['url'])!=0) 
{
 
    $contenu = file( $_GET['url'] );
    while ( list( $numero_ligne, $ligne ) = each( $contenu ) ) 
    {
        echo htmlspecialchars( $ligne ) . "<br>";
    }
}
?>

</body>
</html>
