<?php
session_start();
header("Content-Type: image/png");
$text = $_GET['tur'];
$imageSource = "../im/secret.jpg";
$im=@imagecreatefromjpeg($imageSource);
$black = imagecolorallocate($im, 0, 0, 0);
$font = 'AVGARDM.TTF';


for($i=0;$i<5;$i++) {
   $angle=mt_rand(10,30);
   if(mt_rand(0,1)==1) $angle=-$angle;
   imagettftext($im, 18, $angle, 10+(20*$i), 20, $black, $font, substr($text,$i,1));
}
imagepng($im);
imagedestroy($im);
exit();
?>
