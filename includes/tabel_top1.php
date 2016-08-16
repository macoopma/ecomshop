<tr height="<?php print $cellTop;?>" valign="top" >

<?php
 

if(isset($logo) AND $logo!=="noPath") {
     
    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize($logo);
        $logoWidth = $largeurLogo[0];
        $widthMaxLogo = 160;
         
        if($logoWidth>$widthMaxLogo) {
            $logoRezise = $largeurLogo[0]/$largeurLogo[1];
            $wwww=$widthMaxLogo;
            $hhhh=$widthMaxLogo/$logoRezise;
            $logoWidth = $wwww;
        }
        else {
            $wwww=0;
            $hhhh=0;
            $logoWidth = $largeurLogo[0];
        }
        print detectIm($logo,$wwww,$hhhh);
    print "</td>";
    

    print "<td valign='middle' align='center'>";
       if($bannerVisible=="oui" AND $bannerHeader=="oui") include('includes/banner.php');
    print "</td>";
    
 
    print "<td align='right' valign='middle'>";
       print "<img src='im/zzz.gif' width='".$logoWidth."' height='1'>";
    print "</td>";
}
else {
    if(isset($logo2) AND $logo2!=="noPath") {
  
    print "<td valign='middle' align='center'>";
      if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm($logo2,0,0)."</a>"; else print detectIm($logo2,0,0);
    print "</td>";
    }
    else {
   
    print "<td valign='middle' align='center'>";
       if($bannerVisible=="oui" AND $bannerHeader=="oui") include('includes/banner.php');
    print "</td>";
    }
}
?>
</tr>
<tr valign='top'>
