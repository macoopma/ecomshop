<?php
if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {

if($displayOutOfStock=="non") {$addToQueryccpDef = " AND p.products_qt>'0'";} else {$addToQueryccpDef="";}
$scrollHeightZqDef = $scrollHeight+35;

$selectPromoDef = "SELECT c.*, s.specials_last_day, s.specials_first_day, p.products_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_qt, s.specials_new_price, s.specials_visible, RAND() AS m_random
                                     FROM products as p
                                     LEFT JOIN categories as c ON (c.categories_id = p.categories_id)
                                     INNER JOIN specials as s ON (s.products_id = p.products_id)
                                     WHERE c.categories_visible = 'yes'
                                     AND p.products_ref != 'GC100'
                                     AND p.products_visible='yes'
                                     AND p.products_forsale='yes'
                                     ".$addToQueryccpDef."
                                     AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                     AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)
                                     AND s.specials_visible='yes'
                                     ";

if(isset($_GET['path']) and $_GET['path'] !==0) $addQueryPromoDef = " AND c.categories_id = '".$_GET['path']."'"; else $addQueryPromoDef = "";

$resultPromo2Def = mysql_query($selectPromoDef." ".$addQueryPromoDef." ORDER BY m_random");
$rowsPromoDef = mysql_num_rows($resultPromo2Def);

if($rowsPromoDef == 0) {

if(isset($_GET['path'])) {
	$wPDef = recurs3($_GET['path']);
}
else {
    $wPDef = recurs3("");
}

if(isset($wPDef)) {
  $rand_keysPDef = array_rand($wPDef, 1);
  $w1PDef = $wPDef[$rand_keysPDef];
}
else {
  if(isset($_GET['path'])) $w1PDef = $_GET['path']; else $w1PDef=1;
}

if(isset($_GET['path']) and $_GET['path'] !==0) $addQueryPromoDef = " AND c.categories_id = '".$w1PDef."'"; else $addQueryPromoDef = "";
$resultPromo2Def = mysql_query($selectPromoDef.$addQueryPromoDef." ORDER BY m_random LIMIT 0,".$nbre_promo);
$rowsPromoDef =  mysql_num_rows($resultPromo2Def);

 
if($rowsPromoDef==0 AND $forcePromo=='oui') {
    $resultPromo2Def = mysql_query($selectPromoDef." ORDER BY m_random LIMIT 0,".$nbre_promo);
    $rowsPromoDef =  mysql_num_rows($resultPromo2Def);
}
}


if($rowsPromoDef>0) {
$iPr = 0;
$Hurltous = seoUrlConvert("list.php?target=promo");
while($a_rowPromoDef = mysql_fetch_array($resultPromo2Def)) {
 
$namePromo = strip_tags(adjust_text($a_rowPromoDef['products_name_'.$_SESSION['lang']],$maxCarInfo,".."));


 
$HvaluesP[$iPr] = "<div align='center'>";
$HvaluesP[$iPr].= "<a href='".seoUrlConvert("beschrijving.php?id=".$a_rowPromoDef['products_id']."&target=promo")."' ".display_title($a_rowPromoDef['products_name_'.$_SESSION['lang']],$maxCarInfo).">".$namePromo."</a>";

 
$new_pricePromoDef = $a_rowPromoDef['specials_new_price'];
$old_pricePromoDef = $a_rowPromoDef['products_price'] ;

if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	if(!empty($new_pricePromoDef)) {
		if($a_rowPromoDef['specials_visible']=="yes") {
			$pricePromoDef = $new_pricePromoDef;
			$HvaluesP[$iPr].= "<br><b><s>".$old_pricePromoDef."</s> ".$symbolDevise."<br><span class='fontrouge'>".$pricePromoDef." ".$symbolDevise."</span></b>";
			$clientPriceDef = $new_pricePromoDef;
		}
		else {
			$price = $old_pricePromoDef;
			$HvaluesP[$iPr].= "<br><b>".$pricePromoDef." ".$symbolDevise."</b>";
			$clientPriceDef = $old_pricePromoDef;
		}
	}
	else {
		$price = $old_pricePromoDef;
		$HvaluesP[$iPr].= "<br><b>".$pricePromoDef." ".$symbolDevise."</b>";
		$clientPriceDef = $old_pricePromoDef;
	}
	
	if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
		$HvaluesP[$iPr].= "<br>".VOTRE_PRIX." : <b><span class='fontrouge'>".newPrice($clientPriceDef,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	}
}

if($a_rowPromoDef['products_im']=="yes" AND !empty($a_rowPromoDef['products_image']) AND $a_rowPromoDef['products_image']!=="im/no_image_small.gif") {
  $larg_rub21 = $larg_rub-30;
  $yoZZP = @getimagesize($a_rowPromoDef['products_image']);
  if(!$yoZZP) $a_rowPromoDef['products_image']="im/zzz_gris.gif";
  $resize_imageP = resizeImage($a_rowPromoDef['products_image'],$hautImageMaxPromo,$larg_rub21);
  $HvaluesP[$iPr].= "<br><a href='".seoUrlConvert("beschrijving.php?id=".$a_rowPromoDef['products_id']."&target=promo")."'>";
  $HvaluesP[$iPr].= "<div align='center' style='padding:5px;'>";
  
  if($gdOpen == "non") {
      $HvaluesP[$iPr].= "<img src='".$a_rowPromoDef['products_image']."' width='".$resize_imageP[0]."' height='".$resize_imageP[1]."' border='0' alt='".$a_rowPromoDef['products_name_'.$_SESSION['lang']]."'>";
  }
  else {
      $infoImage = infoImageFunction($a_rowPromoDef['products_image'],$larg_rub,$hautImageMaxPromo);
      $HvaluesP[$iPr].= "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_rowPromoDef['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$a_rowPromoDef['products_name_'.$_SESSION['lang']]."'>";
  }
  $HvaluesP[$iPr].= "</a>";
  $HvaluesP[$iPr].= "</div>";
}
else {
    $HvaluesP[$iPr].= "<img src='im/zzz.gif' width='1' height='1'>";
}
$HvaluesP[$iPr].= "</div>";
$iPr++;
}
?>

<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="modulePromo">
<tr>
<td height='<?php print $hauteurTitreModule;?>' class="modulePromoTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
   <img src="im/lang<?php print $_SESSION['lang'];?>/menu_promo.png">
</td>
</tr>
<tr>
<td>

<script type="text/javascript">
var HEN_scrollerdelayP='<?php print $scrollDelay;?>'
var HEN_scrollerwidthP='<?php print $scrollWidth;?>'
var HEN_scrollerheightP='<?php print $scrollHeightZqDef;?>'
var HEN_scrollerbgcolorP='<?php print $scrollColor;?>'
 
var HEN_scrollerbackgroundP=''

 
 
var HEN_messagesP=new Array()
<?php
for($myincP=0; $myincP < $rowsPromoDef; $myincP++) {
$HvaluesP[$myincP] = str_replace("\"", "\\\"", $HvaluesP[$myincP]);
print "HEN_messagesP[".$myincP."]=\"".$HvaluesP[$myincP]."\"\n";
}
?>
 

var HEN_ieP=document.all
var HEN_domP=document.getElementById

if(HEN_messagesP.length>2)
HEN_iP=2
else
HEN_iP=0

function HEN_move1P(whichlayer) {
  HEN_tlayerP=eval(whichlayer)
  if(HEN_tlayerP.top>0&&HEN_tlayerP.top<=5) {
    HEN_tlayerP.top=0
    setTimeout("HEN_move1P(HEN_tlayerP)",HEN_scrollerdelayP)
    setTimeout("HEN_move2P(document.HEN_mainP.document.HEN_second)",HEN_scrollerdelayP)
    return
  }
  if(HEN_tlayerP.top >= HEN_tlayerP.document.height* - 1) {
    HEN_tlayerP.top -= 5
    setTimeout("HEN_move1P(HEN_tlayerP)",50)
  }
  else{
    HEN_tlayerP.top=parseInt(HEN_scrollerheightP)
    HEN_tlayerP.document.write(HEN_messagesP[HEN_iP])
    HEN_tlayerP.document.close()
    if(HEN_iP==HEN_messagesP.length-1)
    HEN_iP=0
    else
    HEN_iP++
  }
}

function HEN_move2P(whichlayer) {
  HEN_tlayer2P=eval(whichlayer)
  if(HEN_tlayer2P.top > 0 && HEN_tlayer2P.top <= 5) {
    HEN_tlayer2P.top=0
    setTimeout("HEN_move2P(HEN_tlayer2P)",HEN_scrollerdelayP)
    setTimeout("HEN_move1P(document.HEN_mainP.document.HEN_firstP)",HEN_scrollerdelayP)
    return
  }
  if(HEN_tlayer2P.top >= HEN_tlayer2P.document.height* - 1) {
    HEN_tlayer2P.top-=5
    setTimeout("HEN_move2P(HEN_tlayer2P)",50)
  }
  else{
    HEN_tlayer2P.top=parseInt(HEN_scrollerheightP)
    HEN_tlayer2P.document.write(HEN_messagesP[HEN_iP])
    HEN_tlayer2P.document.close()
    if(HEN_iP==HEN_messagesP.length-1)
    HEN_iP=0
    else
    HEN_iP++
  }
}

function HEN_move3P(whichdivP) {
  HEN_tdivP=eval(whichdivP)
  if(parseInt(HEN_tdivP.style.top) > 0 && parseInt(HEN_tdivP.style.top) <= 5) {
    HEN_tdivP.style.top=0+"px"
    setTimeout("HEN_move3P(HEN_tdivP)",HEN_scrollerdelayP)
    setTimeout("HEN_move4P(HEN_second2P_obj)",HEN_scrollerdelayP)
    return
  }
  if(parseInt(HEN_tdivP.style.top) >= HEN_tdivP.offsetHeight*-1) {
    HEN_tdivP.style.top=parseInt(HEN_tdivP.style.top)-5+"px"
    setTimeout("HEN_move3P(HEN_tdivP)",50)
  }
  else {
    HEN_tdivP.style.top=parseInt(HEN_scrollerheightP)
    HEN_tdivP.innerHTML=HEN_messagesP[HEN_iP]
    if(HEN_iP==HEN_messagesP.length-1)
    HEN_iP=0
    else
    HEN_iP++
  }
}

function HEN_move4P(whichdivP) {
HEN_tdiv2P=eval(whichdivP)
if(parseInt(HEN_tdiv2P.style.top) > 0 && parseInt(HEN_tdiv2P.style.top)<=5) {
  HEN_tdiv2P.style.top=0+"px"
  setTimeout("HEN_move4P(HEN_tdiv2P)",HEN_scrollerdelayP)
  setTimeout("HEN_move3P(HEN_first2P_obj)",HEN_scrollerdelayP)
  return
}
  if(parseInt(HEN_tdiv2P.style.top) >= HEN_tdiv2P.offsetHeight*-1) {
    HEN_tdiv2P.style.top=parseInt(HEN_tdiv2P.style.top)-5+"px"
    setTimeout("HEN_move4P(HEN_second2P_obj)",50)
  }
  else {
    HEN_tdiv2P.style.top=parseInt(HEN_scrollerheightP)
    HEN_tdiv2P.innerHTML=HEN_messagesP[HEN_iP]
    if(HEN_iP==HEN_messagesP.length-1)
    HEN_iP=0
    else
    HEN_iP++
  }
}

function HEN_startscrollP() {
  if(HEN_ieP||HEN_domP) {
    HEN_first2P_obj=HEN_ieP? HEN_first2P : document.getElementById("HEN_first2P")
    HEN_second2P_obj=HEN_ieP? HEN_second2P : document.getElementById("HEN_second2P")
    HEN_move3P(HEN_first2P_obj)
    HEN_second2P_obj.style.top=HEN_scrollerheightP
    HEN_second2P_obj.style.visibility='visible'
  }
  else if(document.layers) {
    document.HEN_mainP.visibility='show'
    HEN_move1P(document.HEN_mainP.document.HEN_firstP)
    document.HEN_mainP.document.HEN_second.top=parseInt(HEN_scrollerheightP)+5
    document.HEN_mainP.document.HEN_second.visibility='show'
  }
}



</script>


<ilayer id="HEN_mainP" width=&{HEN_scrollerwidthP}; height=&{HEN_scrollerheightP}; bgColor=&{HEN_scrollerbgcolorP}; background=&{HEN_scrollerbackgroundP}; visibility=hide>
<layer id="HEN_firstP" left=0 top=1 width=&{HEN_scrollerwidthP};>
<script language="JavaScript1.2">
if(document.layers)
document.write(HEN_messagesP[0])
</script>
</layer>
<layer id="HEN_second" left=0 top=0 width=&{HEN_scrollerwidthP}; visibility=hide>
<script language="JavaScript1.2">
if(document.layers)
document.write(HEN_messagesP[dyndetermine=(HEN_messagesP.length==1)? 0 : 1])
</script>
</layer>
</ilayer>
<script language="JavaScript1.2">
if(HEN_ieP||HEN_domP) {
  document.writeln('<div id="HEN_main2P" style="position:relative;width:'+HEN_scrollerwidthP+';height:'+HEN_scrollerheightP+';overflow:hidden;background-color:'+HEN_scrollerbgcolorP+' ;background-image:url('+HEN_scrollerbackgroundP+')">')
  document.writeln('<div style="position:absolute;width:'+HEN_scrollerwidthP+';height:'+HEN_scrollerheightP+';clip:rect(0 '+HEN_scrollerwidthP+' '+HEN_scrollerheightP+' 0);left:0px;top:0px">')
  document.writeln('<div id="HEN_first2P" style="position:absolute;width:'+HEN_scrollerwidthP+';left:0px;top:1px;">')
  document.write(HEN_messagesP[0])
  document.writeln('</div>')
  document.writeln('<div id="HEN_second2P" style="position:absolute;width:'+HEN_scrollerwidthP+';left:0px;top:0px;visibility:hidden">')
  document.write(HEN_messagesP[dyndetermine=(HEN_messagesP.length==1)? 0 : 1])
  document.writeln('</div>')
  document.writeln('</div>')
  document.writeln('</div>')
}

<?php
if($rowsPromoDef>1) {
?>
HEN_startscrollP()
<?php 
}
?>

</script>
<?php
if($rowsPromoDef > 0) print "<div align='center'><a href='".seoUrlConvert("list.php?target=promo")."'><img src='im/plus.gif' align='absmiddle' border='0' alt=".maj(AUTRES)." title=".maj(AUTRES)."></a></div>";
?>

</td>
</tr>
</table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
<?php
}
}
?>
