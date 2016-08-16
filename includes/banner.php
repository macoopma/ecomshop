<?php


$toDayDate = date('Y-m-d H:i:s');

$queryBanner = mysql_query("SELECT banner_id, banner_desc FROM banner WHERE banner_visible = 'yes' AND TO_DAYS(banner_date_end) >= TO_DAYS('".$toDayDate."')");
$bannerCount = mysql_num_rows($queryBanner);

if($bannerCount>0) {
    while($bannerRand = mysql_fetch_array($queryBanner)) {
        $bannerId[$bannerRand['banner_id']] = $bannerRand['banner_id'];
    }

    srand ((double) microtime() * 10000000);
    $idBanner = array_rand($bannerId,1);
    
    mysql_query("UPDATE banner SET banner_vue = banner_vue + 1 WHERE banner_id = '".$idBanner."'");
    
    $queryBanner2 = mysql_query("SELECT banner_url, banner_sponsor, banner_image, banner_id FROM banner WHERE banner_id = '".$idBanner."'");
    $banner = mysql_fetch_array($queryBanner2);
    
    if(empty($banner['banner_url']) AND empty($banner['banner_sponsor'])) {
        print detectIm($banner['banner_image'],0,0);
    }
    else {
      if(empty($banner['banner_url']) AND empty($banner['banner_image']) AND !empty($banner['banner_sponsor'])) {
        print $banner['banner_sponsor'];
      }
      else {
        $bannerUrl = str_replace('&','|||',$banner['banner_url']);
        print "<a href='bannerklik.php?id=".$banner['banner_id']."&url=".$bannerUrl."' target='_blank'>";
        print detectIm($banner['banner_image'],0,0);
        print "</a>";
      }
    }
}
?>
