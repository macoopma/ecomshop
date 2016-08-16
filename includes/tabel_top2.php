<?php
$backgroundImage = $header2Image;
if($backgroundImage=='noPath') $backgroundImage="im/zzz.gif";
 
$sizeBackgroundImage = @getimagesize($backgroundImage);
if(!isset($sizeBackgroundImage) OR $sizeBackgroundImage=="") {
   $backgroundImage = "im/zzz.gif";
   $sizeBackgroundImage[1]=1;
}
$itemNum = cart_Item();


if($langVisible=="oui") {
	$langDispoZ = mysql_query("SELECT languages_id FROM languages WHERE visible='yes'");
	while($arowZ = mysql_fetch_array($langDispoZ)) {
		$langD[] = $arowZ['languages_id'];
	}
	if(count($langD) > 1) {
		$lannngg =1;
	}
	else {
		$lannngg = 0;
	}
}
else {
	$lannngg = 0;
}
 
if(isset($_SESSION['account']) OR $activeEcom=="oui") {
        if(isset($_SESSION['account'])) {
                $qU = mysql_query("SELECT users_pro_firstname FROM users_pro WHERE users_pro_password= '".$_SESSION['account']."'");
                $rU = mysql_fetch_array($qU);
                $nU = $rU['users_pro_firstname'];
          $bienvenuZ = BIENVENU2." <a href='mijn_account.php'>".$nU."</a>";
        }
        else {
          $bienvenuZ = "<a href='mijn_account.php'>".VOTRE_COMPTE."</a>";
        }
    if(!isset($_SESSION['list']) or $_SESSION['list'] == "" or empty($_SESSION['list'])) {
        $cady2 = "<table border='0' cellpadding='0' cellspacing='0'><tr><td>";
        $cady2.= "<img src='im/votre_compte.gif' align='absmiddle'></td><td>&nbsp;".$bienvenuZ."&nbsp;|&nbsp;";
        $cady2.= "</td><td>";
        $cady2.= "<img src='im/panier.png' border='0' align='absmiddle'>";
        $cady2.= "</td>";
        $cady2.= "<td>";
        $cady2.= "&nbsp;<b>".VOTRE_CADDIE."</b>:&nbsp;";
        $cady2.= "<a href='caddie.php'><b><span class='fontrouge'>".CADDIE_VIDE."</span></b></a>&nbsp;";
        $cady2.= "</td>";
        $cady2.= "</tr></table>";
    }
    else {
        $sss = ($itemNum>1)? "en" : "";
        $cady2 = "<table border='0' cellpadding='0' cellspacing='0'><tr><td>";
        $cady2.= "<img src='im/votre_compte.gif' align='absmiddle'></td><td>&nbsp;".$bienvenuZ."&nbsp;|&nbsp;";
        $cady2.= "</td><td>";
        $cady2.= "<img src='im/panier.png' border='0' align='absmiddle'>";
        $cady2.= "</td>";
        $cady2.= "<td>";
        $cady2.= "&nbsp;<a href='caddie.php'><b>".VOTRE_CADDIE."</b></a>:&nbsp;";
        $cady2.= "<a href='caddie.php'><span class='fontrouge'>".$itemNum." ".ARTICLE.$sss."</span></a>&nbsp;";
  
   
    
        $cady2.= "</td>";
        $cady2.= "</tr></table>";
    }
}
else {
   $cady2='';  
}
 
if($nouvVisible == 'oui' AND isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
    $nouv = "<a href='".seoUrlConvert("list.php?target=new")."'>".NOUVEAUTES."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
}
else {
    $nouv = "";
}
 
if($promoVisible == 'oui' AND isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
   $prom = "<a href='".seoUrlConvert("list.php?target=promo")."'>".PROMOTIONS."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
}
else {
   $prom = "";
}
 
   $contact = "<a href='".seoUrlConvert("infos.php?info=5")."'>".NOUS_CONTACTER."</a>";
?>


<!-- TABLE TOTAL -->
<table align='center' width='100%' border='0' cellspacing='0' cellspadding='0'>
<tr>
<td align='center' valign='top'>
<?php
 
if(substr(trim($backgroundImage), -3) !== "swf") {
?>
<table align='center' width='99%' border='0' cellspacing='0' cellspadding='0' style='background:url(<?php print $backgroundImage;?>) top left no-repeat;' height='<?php print $sizeBackgroundImage[1];?>'>
<tr>
<?php
if($bannerVisible=="oui" AND $bannerHeader2=="oui") {
   print "<td align='center' valign='middle' colspan='2'>";
}
else {
   print "<td align='center' valign='top' colspan='2'>";
}
?>

<table align='center' width='100%' border='0' cellspacing='0' cellpadding='0'>
<tr>
<td align='left' valign='top' height='1'>
<?php
if($lannngg==1) {
   add_flags();
   print "&nbsp;&nbsp;|";
}
print "&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".HOME."</a>&nbsp;|&nbsp;&nbsp;";;
print $nouv;
print $prom;
print $contact."&nbsp;&nbsp;|";
?>
</td>
<td align='right'>

 
<table border='0' cellspacing='0' cellpadding='0'>
<tr>
<form action='includes/redirectzoeken.php' method='post'>
<td align='right' valign='bottom' width='1'>
<input class="searchEngineHeader" s/tyle="border:1px #CCCCCC solid; background:url(im/loupe.gif) top left no-repeat; width:140px; height:18px; padding-left:17px;" name="search_query" maxlength='100' value="" type="text">
</td>
<td><img src='im/zzz.gif' width='1' height='5'></td>
</tr><tr>
<td align='right' valign='bottom'>
<a href='zoeken.php?action=search&AdSearch=on'><span class='FontGris' style='font-size:9px;'><?php print RECHERCHE_AVANCEE;?></span></a>
</td>
<td><img src='im/zzz.gif' width='1' height='5'></td>
</form>
</tr>
</table>


</td>
</tr>
</table>







<?php 
if($bannerVisible=="oui" AND $bannerHeader2=="oui") {
  include('includes/banner.php');
}
else {
  print "<div><img src='im/zzz.gif' width='1' height='8'></div>";
}
?>



</td>
</tr>
<tr>
<td align='right' valign='bottom' style='padding:3px;' height='1'>
<?php 
print $cady2;
?>
</td>
</tr>
</table>


<?php
}
else {
 
print '<embed src="'.$backgroundImage.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeBackgroundImage[3].'></embed>';
}
?>

</td>
</tr>
</table>
