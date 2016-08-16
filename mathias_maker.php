<?php
$backCol = $_GET['backColor'];
$imageSource = $_GET['imageSource'];
$largeur = $_GET['largeur'];
$hauteur = $_GET['hauteur'];
$extension = $_GET['extension'];
$largeurOrigin = $_GET['largeurOrigin'];
$hauteurOrigin = $_GET['hauteurOrigin'];
$border=0; // oui = 1 | non = 0
$co = explode(",",$backCol);

// $extension="bla";

if($extension=='.JPG' OR $extension=='.JPEG') {
	header("Content-Type: image/JPEG");
	$imCreatedIni=@imagecreatefromjpeg($imageSource);
}
if($extension=='.GIF') {
	header("Content-Type: image/PNG");
	$imCreatedIni=@imagecreatefromgif($imageSource);
}
if($extension=='.PNG') {
	header("Content-Type: image/PNG");
	$imCreatedIni=@imagecreatefrompng($imageSource);
}

$imCreated = @imagecreatetruecolor ($largeur, $hauteur);
imagefill ($imCreated, 0, 0, imagecolorallocate($imCreated,$co[0],$co[1],$co[2]));
if($border=='1') {
	$largeur=$largeur-2;
	$hauteur=$hauteur-2;
	$x='1';
	$y='1';
}
else{
	$x='0';
	$y='0';
}
ImageCopyResampled($imCreated,$imCreatedIni,$x,$y,0,0,$largeur,$hauteur,$largeurOrigin,$hauteurOrigin);
 
if($extension=='.JPG' OR $extension=='.JPEG') {
//	imagejpeg($imCreated,'',75); // compression jpg a 75%
//	imagejpeg($imCreated); // compression jpg a 75%
	imagejpeg($imCreated,null,75); // compression jpg a 75%

    
}
if($extension=='.GIF' OR $extension=='.PNG') {
	imagepng ($imCreated);
}
imagedestroy($imCreated);
?>
