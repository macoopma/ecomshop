<?php
function request_headers()
{
    if(function_exists("apache_request_headers")) 
    {
        if($headers = apache_request_headers())  
        {
            return $headers; // Use it
        }
    }

    $headers = array();

    foreach(array_keys($_SERVER) as $skey)
    {
        if(substr($skey, 0, 5) == "HTTP_")
        {
            $headername = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($skey, 0, 5)))));
            $headers[$headername] = $_SERVER[$skey];
        }
    }

    return $headers;
}

 

if (empty($_GET[p])) {
header("Location: images/notfound.gif");
die();
}

if (!file_exists($_REQUEST[p]))echo "NO FILE";

 
$headers = request_headers();
if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($_REQUEST[p]))) {
 
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($_REQUEST[p])).' GMT', true, 304);
	die();
}

preg_match("/\.([^\.]*?)$/",$_REQUEST[p],$match);
$match=strtoupper($match[1]);
switch ($match)
{
	case "JPEG" :
	case "JPG" :
	{
	$im=imagecreatefromjpeg($_REQUEST[p]);
	break;
	}
	case "GIF" :
	{	
	$im=imagecreatefromgif($_REQUEST[p]);
	break;
	}
	case "PNG" :
	{	
	$im=imagecreatefrompng($_REQUEST[p]);
	break;
	}

}
if(isset($_REQUEST[bg]))
{
	$r = $g = $b = 255;
	$bg_arr = explode(",",$_REQUEST[bg]);
	if(isset($bg_arr[0])) $r = $bg_arr[0];
	if(isset($bg_arr[1])) $g = $bg_arr[1];
	if(isset($bg_arr[2])) $b = $bg_arr[2];
}
header("Content-type: image/jpeg");
$newim=imagecreatetruecolor(intval($_REQUEST[h]*(imagesx($im)/imagesy($im))), intval($_REQUEST[h]));
$bg = imagecolorallocate ( $newim, $r, $g, $b );
imagefill ( $newim, 0, 0, $bg );
imagecopyresampled($newim,$im,0,0,0,0,$_REQUEST[h]*(imagesx($im)/imagesy($im)),$_REQUEST[h],imagesx($im),imagesy($im));
header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($_REQUEST[p])) . " GMT");
imagejpeg($newim);
?>