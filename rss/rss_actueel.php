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

if($lang==1) {$titleZ = $store_name." : Actualités"; $language="fr-fr"; $noActu ="Pas d'actualités..."; $lesActu = "Les actualités"; $lire="Lire cet article";}
if($lang==2) {$titleZ = $store_name." : News"; $language="en-us"; $noActu ="No News..."; $lesActu = "The News"; $lire="Read this news";}
if($lang==3) {$titleZ = $store_name." : Nieuws"; $language="nl"; $noActu ="Geen nieuws..."; $lesActu = "Lees dit nieuws";}

$domaineURL = "http://".$www2.$domaineFull;


if($storeClosed=="non") {
 
$query = "SELECT page_added_id, ";
$query.= "page_added_date, ";
$query.= "page_added_use, ";
$query.= "page_added_title_".$lang.", ";
$query.= "page_added_message_".$lang."\n";
$query.= "FROM page_added\n";
$query.= "WHERE page_added_use = 'actu' AND page_added_visible = 'yes'\n";
$query.= "ORDER BY page_added_id DESC";

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
    $xml.= "<title>".$noActu."</title>\r\n";
    $xml.= "<link>".$domaineURL."</link>\r\n";
    $xml.= "<guid>".$domaineURL."</guid>\r\n";
    $xml.= "<description>".$noActu."</description>\r\n";
    $xml.= "</item>\r\n";
}
else {

while ($myResult = mysql_fetch_array($result)) {
   $idZ = $myResult['page_added_id']+1000;

  
      $dateDesc = "[".dateFr($myResult['page_added_date'],$lang)."] ";
                  $desc = strip_tags($myResult['page_added_message_'.$lang]);
                  $desc = str_replace("\t", " ", $desc);
                  $desc = str_replace("\r\n", ". ", $desc);
                  $desc = str_replace("\n", ". ", $desc);
                  $desc = str_replace("/t", " ", $desc);
                  $desc = str_replace(">", "-", $desc);
                  $desc = str_replace(";", ",", $desc);
                  $desc = str_replace('"', '', $desc);
                  $desc = str_replace("'", "", $desc);
                  $desc = setText($desc,200,"...");
                  $desc = trim($desc);
                  $RSSdesc = purge_iso88591($desc);
                  $RSSdesc = $dateDesc."<br>".$RSSdesc;
                  
                  $RSSdesc.= "<br><a href='".$domaineURL."'>".$store_name."</a> > <a href='".$domaineURL."/nieuws.php?lang=".$lang."'>".$lesActu."</a> > <a href='".$domaineURL."/doc.php?id=".$idZ."&lang=".$lang."'>".$lire."</a><br>";
                  $RSSdesc = htmlspecialchars($RSSdesc);

   

                  $nam = strip_tags($myResult['page_added_title_'.$lang]);
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
    
                  $explodeDate = explode("-",$myResult['page_added_date']);
                  $RSSdateMk = mktime (0,0,0,$explodeDate[1],$explodeDate[2],$explodeDate[0]);
                  $RSSdate = date("D, d M Y H:i:s +0100", $RSSdateMk);
    
    $xml.= "<item>\r\n";
    $xml.= "  <title>".$RSSname."</title>\r\n";
    $xml.= "  <link>".$domaineURL."/doc.php?id=".$idZ."&amp;lang=".$lang."</link>\r\n";
    $xml.= "  <guid>".$domaineURL."/doc.php?id=".$idZ."&amp;lang=".$lang."</guid>\r\n";
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
   if($lang==3) $shopClosed = $domaine." is tijdelijk gesloten...";
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
