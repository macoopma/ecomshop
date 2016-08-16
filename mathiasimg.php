<?php
	header("Content-Type: image/PNG");

$imCreated = @imagecreatetruecolor (100,100);
imagepng ($imCreated);
imagedestroy($imCreated);
?>
