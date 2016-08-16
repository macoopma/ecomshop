<?php
include("../configuratie/configuratie.php");
$store_name = str_replace('&','&amp;',$store_name);
$dir="../";
if($storeClosed == "oui") {$RSSClosed = 1;}
include('../includes/plug.php');
header('Content-Type: text/xml; charset=iso-8859-1');


function purge_iso88591($str) {
	$cp1252_map = array(
	   "\x80" => "&#8364;", 
	   "\x82" => "&#8218;", 
	   "\x83" => "&#402;",   
	   "\x84" => "&#8222;",  
	   "\x85" => "&#8230;",  
	   "\x86" => "&#8224;",  
	   "\x87" => "&#8225;",  
	   "\x88" => "&#710;",   
	   "\x89" => "&#8240;",  
	   "\x8a" => "&#352;",   
	   "\x8b" => "&#8249;",  
	   "\x8c" => "&#338;",   
	   "\x8e" => "&#381;",   
	   "\x91" => "&#8216;", 
	   "\x92" => "&#8217;", 
	   "\x93" => "&#8220;", 
	   "\x94" => "&#8221;", 
	   "\x95" => "&#8226;", 
	   "\x96" => "&#8211;", 
	   "\x97" => "&#8212;", 
	   "\x98" => "&#732;",  
	   "\x99" => "&#8482;", 
	   "\x9a" => "&#353;",  
	   "\x9b" => "&#8250;", 
	   "\x9c" => "&#339;",  
	   "\x9e" => "&#382;",  
	   "\x9f" => "&#376;"   
	);
	return strtr($str, $cp1252_map);
}

 
function setText($text, $max, $comet) {
   $max2 = $max-4;
   if(strlen($text) >= $max) $textR = substr($text, 0, $max2).$comet; else $textR = $text;
   return $textR;
}

 
if(isset($_SESSION['lang'])) {
  $lang = $_SESSION['lang'];
}
else {
  (isset($_REQUEST['lang']))? $lang = $_REQUEST['lang'] : $lang=$langue;
}

if($lang==1) {$titleZ = $store_name." : Nouveautés"; $language="fr-fr"; $noPromo ="Pas de nouveautés pour le moment...";}
if($lang==2) {$titleZ = $store_name." : New Products"; $language="en-us"; $noPromo ="No new products for now...";}
if($lang==3) {$titleZ = $store_name." : Nieuwe artikelen"; $language="nl"; $noPromo ="Geen nieuwe artikelen..";}

$domaineURL = "http://".$www2.$domaineFull;


if($storeClosed=="non") {
$query = "SELECT p.products_desc_".$lang." AS products_desc, ";
$query.= "p.products_im AS products_im, ";
$query.= "p.products_image AS products_image, ";
$query.= "p.products_name_".$lang." AS products_name, ";
$query.= "p.products_id AS products_id, ";
$query.= "p.products_price AS products_price, ";
$query.= "s.specials_new_price AS specials_new_price, ";
$query.= "s.specials_visible AS specials_visible, ";
$query.= "p.products_date_added AS products_date_added, ";
$query.= "CONCAT_WS(' > ', cp.categories_name_".$lang.", c.categories_name_".$lang.") AS categories_name, ";
$query.= "p.categories_id AS categories_id \n";
$query.= "FROM products AS p \n";
$query.= "LEFT JOIN categories AS c ON (p.categories_id = c.categories_id) \n";
$query.= "LEFT JOIN categories AS cp ON (c.parent_id = cp.categories_id) \n";
$query.= "LEFT JOIN specials AS s ON (p.products_id = s.products_id) \n";
$query.= "WHERE TO_DAYS(NOW()) - TO_DAYS(p.products_date_added) <= '".$nbre_jour_nouv."' \n";
if($displayOutOfStock=="non") {$query.= " AND p.products_qt>'0' ";}
$query.= "AND c.categories_visible = 'yes' ";
$query.= "AND p.products_ref != 'GC100' ";
$query.= "AND p.products_visible='yes' ";
$query.= "AND p.products_forsale = 'yes' \n";
$query.= "ORDER BY p.products_date_added DESC";

$result = mysql_query($query) or die (mysql_error());
$resultNum =  mysql_num_rows($result);

$xml = "<?xml version='1.0' encoding='iso-8859-1'?>\r\n";
$xml.= "<rss version='2.0'>\r\n";
$xml.= "<channel>\r\n";
$xml.= "<title>".setText($store_name,100,"...")."</title>\r\n";
$xml.= "<link>".$domaineURL."</link>\r\n";
$xml.= "<description>".$titleZ."</description>\r\n";
		$xml.= "<image>\r\n";
			$xml.= "<title>".setText($store_name,100,"...")."</title>\r\n";
			$xml.= "<url>".$domaineURL."/im/logo.gif</url>\r\n";
			$xml.= "<link>".$domaineURL."</link>\r\n";
		$xml.= "</image>\r\n";
$xml.= "<language>".$language."</language>\r\n\r\n";

if($resultNum <= 0) {
    $xml.= "<item>\r\n";
    $xml.= "<title>".$noPromo."</title>\r\n";
    $xml.= "<link>".$domaineURL."</link>\r\n";
    $xml.= "<guid>".$domaineURL."</guid>\r\n";
    $xml.= "<description>".$noPromo."</description>\r\n";
    $xml.= "</item>\r\n";
} 
else {

while ($myResult = mysql_fetch_array($result)) {

    
                if($myResult['specials_new_price'] > 0) {
                	if($myResult['specials_visible']=="yes") {
                  		$RSSdesc = "<strike><b>".$myResult['products_price']."</strike> | ".$myResult['specials_new_price']." &#8364;</b><br>";
                  	}
                  	else {
						$RSSdesc = "<b>".$myResult['products_price']." &#8364;</b><br>";
					}
                }
                else {
                  	$RSSdesc = "<b>".$myResult['products_price']." &#8364;</b><br>";
                }


                if($myResult['products_im'] =="yes") {
                    $URLimRelative = "../".$myResult['products_image'];
                    if(substr($myResult['products_image'], 0, 4)=="http") {
                        $URLimRelative = $myResult['products_image'];
                        $URLim = $myResult['products_image'];
                    }
                    else {
                        $URLim = $domaineURL."/".$myResult['products_image'];
                    }
                    
                $RSSdesc.= "<a href='".$domaineURL."/beschrijving.php?lang=".$lang."&amp;id=".$myResult['products_id']."&amp;path=".$myResult['categories_id']."'>";

                if($gdOpen=="oui") {
                  $popSize = @getimagesize($URLimRelative);
                  if(!$popSize) $URLimRelative="im/zzz_gris.gif";
                  $infoImage = infoImageFunction($URLimRelative,100,150);
                  if($popSize[1] > 100) {
                     $RSSdesc.= "<img src='".$domaineURL."/mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$URLim."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0'>";
                  }
                  else {
                     $RSSdesc.= "<img src='".$domaineURL."/mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$URLim."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[1]."&hauteur=".$infoImage[2]."' border='0'>";
                  }
                }
                else {
                   $popSize = @getimagesize($URLimRelative);
                   if(!$popSize) $URLimRelative="im/zzz_gris.gif";
                   if($popSize[1] > 100) {
                      $image_resizeRSS = resizeImage($URLimRelative,100,150);
                      $RSSdesc.= "<img src='".$URLim."' width='".$image_resizeRSS[0]."' height='".$image_resizeRSS[1]."' border='0'>";
                   }
                   else {
                      $RSSdesc.= "<img src='".$URLim."' border='0'>";
                   }
                }
                  $RSSdesc.= "</a><br>";
                }

   
                  $desc = strip_tags($myResult['products_desc']);
                  $desc = str_replace("\t", " ", $desc);
                  $desc = str_replace("\r\n", ". ", $desc);
                  $desc = str_replace("\n", ". ", $desc);
                  $desc = str_replace("/t", " ", $desc);
                  $desc = str_replace(">", "-", $desc);
                  $desc = str_replace(";", ",", $desc);
                  $desc = str_replace('"', '', $desc);
                  $desc = str_replace("'", "", $desc);
                  $desc = setText($desc,350,"...");
                  $desc = trim($desc);
                  $RSSdesc .= purge_iso88591($desc);
                  
                  $RSSdesc.= "<br><a href='".$domaineURL."/list.php?lang=".$lang."&path=".$myResult['categories_id']."'>".$store_name." > ".$myResult['categories_name']."</a><br>";
                  $RSSdesc = htmlspecialchars($RSSdesc);

   
                  $nam = strip_tags($myResult['products_name']);
                  $nam = str_replace("\r\n", ". ", $nam);
                  $nam = str_replace("\n", ". ", $nam);
                  $nam = str_replace("/t", " ", $nam);
                  $nam = str_replace(">", "-", $nam);
                  $nam = str_replace(";", ",", $nam);
                  $nam = str_replace('"', '', $nam);
                  $nam = str_replace("'", " ", $nam);
                  $nam = str_replace("&", "&amp;", $nam);
                  $nam = trim($nam);
                  $RSSname = setText($nam,100,"...");
                  $RSSname = purge_iso88591($nam);
   
                  $oubah = explode(" ",$myResult['products_date_added']);
                  $explodeDate = explode("-",$oubah[0]);
                  $explodeTime = explode(":",$oubah[1]);
                  $RSSdateMk = mktime ($explodeTime[0],$explodeTime[1],$explodeTime[2],$explodeDate[1],$explodeDate[2],$explodeDate[0]);
                  $RSSdate = date("D, d M Y H:i:s +0100", $RSSdateMk);
    
    $xml.= "<item>\r\n";
    $xml.= "  <title>".$RSSname."</title>\r\n";
    $xml.= "  <link>".$domaineURL."/beschrijving.php?lang=".$lang."&amp;id=".$myResult['products_id']."&amp;path=".$myResult['categories_id']."</link>\r\n";
    $xml.= "  <guid>".$domaineURL."/beschrijving.php?lang=".$lang."&amp;id=".$myResult['products_id']."&amp;path=".$myResult['categories_id']."</guid>\r\n";
    $xml.= "  <pubDate>".$RSSdate."</pubDate>\r\n";
    $xml.= "  <description>".$RSSdesc."</description>\r\n";
    $xml.= "</item>\r\n";
}
}
$xml.= "\r\n</channel>\r\n</rss>\r\n";

echo $xml;


}
else {
$query = mysql_query("SELECT shop_closed FROM admin");
$rowpClosed = mysql_fetch_array($query);
if($rowpClosed['shop_closed']!=="") {
   $shopClosed = $rowpClosed['shop_closed'];
}
else {
   if($lang==1) $shopClosed = $domaine." est provisoirement fermé...";
   if($lang==2) $shopClosed = $domaine." is temporarily closed...";
}

                  $shopClosed2 = strip_tags($shopClosed);
                  $shopClosed2 = str_replace("\t", " ", $shopClosed2);
                  $shopClosed2 = str_replace("\r\n", " ", $shopClosed2);
                  $shopClosed2 = str_replace("\n", ". ", $shopClosed2);
                  $shopClosed2 = str_replace("/t", " ", $shopClosed2);
                  $shopClosed2 = str_replace(">", "-", $shopClosed2);
                  $shopClosed2 = str_replace(";", ",", $shopClosed2);
                  $shopClosed2 = str_replace('"', '', $shopClosed2);
                  $shopClosed2 = str_replace("'", "", $shopClosed2);
                  $shopClosed2 = setText($shopClosed2,400,"...");
                  $shopClosed2 = trim($shopClosed2);
                  $shopClosed2 = purge_iso88591($shopClosed2);
                  
                  
    $xml = "<?xml version='1.0' encoding='iso-8859-1'?>\r\n";
    $xml.= "<rss version='2.0'>\r\n";
    $xml.= "<channel>\r\n";
    $xml.= "<title>".setText($store_name,100,"...")."</title>\r\n";
    $xml.= "<link>".$domaineURL."</link>\r\n";
    $xml.= "<description>".$titleZ."</description>\r\n";
    $xml.= "<language>".$language."</language>\r\n\r\n";
    $xml.= "<item>\r\n";
    $xml.= "<title>".$shopClosed2."</title>\r\n";
    $xml.= "<link>".$domaineURL."/shop_gesloten.php?lang=".$lang."</link>\r\n";
    $xml.= "<guid>".$domaineURL."/shop_gesloten.php?lang=".$lang."</guid>\r\n";
    $xml.= "<description>".$shopClosed2."</description>\r\n";
    $xml.= "</item>\r\n";
    $xml.= "\r\n</channel>\r\n</rss>\r\n";
    echo $xml;
}
?>
