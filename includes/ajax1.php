<?php
include('../configuratie/configuratie.php');


function resizeImageZ($imageToResize,$haut,$largeurMax) {
	$size = @getimagesize($imageToResize);
	if($size[1] >= $haut) {
		$hauteur = $haut;
		$reduction_hauteur = $hauteur/$size[1];
		$largeur = $size[0]*$reduction_hauteur;
	}
	else {
		$hauteur = $size[1];
		$largeur = $size[0];
	}
	
	if($largeurMax > 0) {
		if($largeur > $largeurMax) {
			$largeur = $largeurMax;
			$reduction_largeur = $largeur/$size[0];
			$hauteur = $size[1]*$reduction_largeur;
		}
	}
	return array($largeur,$hauteur);
}


function infoImageFunction($image, $largMax, $hautMax) {
	$sizeImOrigin = @getimagesize($image);
	if(!$sizeImOrigin) $image="im/zzz_gris.gif";

	$ext2 = explode(".", $image);
	$ext2 = end($ext2);
	$ext = ".$ext2";
	$extension=strtoupper($ext);
	$largImOrigin=$sizeImOrigin[0];
	$hautImOrigin=$sizeImOrigin[1];
	if($largImOrigin>$largMax) {
		$hautImDisplayed=$hautImOrigin*$largMax/$largImOrigin;
		$hautImDisplayed=round($hautImDisplayed);
		$largImDisplayed=$largMax;
	}
	else {
		$largImDisplayed=$largImOrigin;
		$hautImDisplayed=$hautImOrigin;
	}
	if($hautImDisplayed>$hautMax) {
		$largImDisplayed=$largImDisplayed*$hautMax/$hautImDisplayed;
		$largImDisplayed=round($largImDisplayed);
		$hautImDisplayed=$hautMax;
	}
	return array($extension, $largImOrigin, $hautImOrigin, $largImDisplayed, $hautImDisplayed);
}
 
$ajax_im = explode("|",$_GET['ajax_im']);
$countAjax_im = count($ajax_im);
if($countAjax_im>1) {
 
        $images_widthDescZ = $ImageSizeDesc+20;
        $hz = @getimagesize("../".$ajax_im[0]);
        if(!$hz) $ajax_im[0]="../im/zzz_gris.gif";
        $image_descz = resizeImageZ("../".$ajax_im[0],$ImageSizeDesc,$images_widthDescZ);
  
        if($lightbox == "oui") {
            print "<a href='".$ajax_im[0]."' rel='lightbox[roadtrip1]' alt='".$_GET['ajax_name']."' title='".$_GET['ajax_name']."' target='_blank'>";
        }
        else {
        	print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$ajax_im[0]."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$hz[1].",width=".$hz[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
        }
 
		if($gdOpen == "non") {
			print "<img src='".$ajax_im[0]."' border='0' width='".$image_descz[0]."' height='".$image_descz[1]."' alt='".$_GET['ajax_name']."'>";
		}
		else {
			$infoImageZ = infoImageFunction("../".$ajax_im[0],200,$ImageSizeDesc);
			print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImageZ[0]."&imageSource=".$ajax_im[0]."&largeurOrigin=".$infoImageZ[1]."&hauteurOrigin=".$infoImageZ[2]."&largeur=".$infoImageZ[3]."&hauteur=".$infoImageZ[4]."' alt='".$_GET['ajax_name']."' border='0'>";                  
		}
      	print "</a>";

 
	for($i=1; $i<=$countAjax_im-1; $i++) {
		$hz = @getimagesize("../".$ajax_im[$i]);
		if(!$hz) $ajax_im[$i]="../im/zzz_gris.gif";
		$image_descz = resizeImageZ("../".$ajax_im[$i],$SecImageSizeDesc,$SecImageWidthDesc);
 
        if($lightbox == "oui") {
        	print "<a href='".$ajax_im[$i]."' rel='lightbox[roadtrip1]' alt='".$_GET['ajax_name']."' title='".$_GET['ajax_name']."' target='_blank'>";
        }
        else {
        	print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$ajax_im[$i]."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$hz[1].",width=".$hz[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
        }
 
		if($gdOpen == "non") {
			print "<img src='".$ajax_im[$i]."' border='0' width='".$image_descz[0]."' height='".$image_descz[1]."' alt='".$_GET['ajax_name']."'>";
		}
		else {
			$infoImageZ = infoImageFunction("../".$ajax_im[$i],200,$ImageSizeDesc);
			print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImageZ[0]."&imageSource=".$ajax_im[$i]."&largeurOrigin=".$infoImageZ[1]."&hauteurOrigin=".$infoImageZ[2]."&largeur=".$infoImageZ[3]."&hauteur=".$infoImageZ[4]."' alt='".$_GET['ajax_name']."' border='0'>";                  
		}
      	print "</a>";
      	print "<img src='im/zzz.gif' width='4' height='1'>";
	}
}
else {
	if($_GET['ajax_im']!=='im/lang'.$_GET['lang'].'/no_stock.png') {
		$images_widthDescZ = $ImageSizeDesc+20;
		$hz = @getimagesize("../".$_GET['ajax_im']);
		if(!$hz) $_GET['ajax_im']="../im/zzz_gris.gif";
		$image_descz = resizeImageZ("../".$_GET['ajax_im'],$ImageSizeDesc,$images_widthDescZ);
 
		if($lightbox == "oui") {
			print "<a href='".$_GET['ajax_im']."' rel='lightbox' alt='".$_GET['ajax_name']."' title='".$_GET['ajax_name']."' target='_blank'>";
		}
		else {
			print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$_GET['ajax_im']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$hz[1].",width=".$hz[0].",toolbar=no,scrollbars=no,resizable=yes');\">";
		}
 
		if($gdOpen == "non") {
			print "<img src='".$_GET['ajax_im']."' border='0' width='".$image_descz[0]."' height='".$image_descz[1]."' alt='".$_GET['ajax_name']."'>";
		}
		else {
			$infoImageZ = infoImageFunction("../".$_GET['ajax_im'],200,$ImageSizeDesc);
			print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImageZ[0]."&imageSource=".$_GET['ajax_im']."&largeurOrigin=".$infoImageZ[1]."&hauteurOrigin=".$infoImageZ[2]."&largeur=".$infoImageZ[3]."&hauteur=".$infoImageZ[4]."' alt='".$_GET['ajax_name']."' border='0'>";                  
		}
		print "</a>";
	}
	else {
		print "<img src='".$_GET['ajax_im']."' border='0'>";
	}
}
?>
