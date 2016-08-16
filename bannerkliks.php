<?php
include('configuratie/configuratie.php');


mysql_query("UPDATE banner SET banner_hit = banner_hit+1 WHERE banner_id = '".$_GET['id']."'");
$bannerUrl = str_replace('|||','&',$_GET['url']);
header("Location: ".$bannerUrl);
?>
