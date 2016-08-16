<?php
if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
 
if($displayOutOfStock=="non") {$addToQuerycc = " AND p.products_qt>'0'";} else {$addToQuerycc="";}
$scrollHeightZ = $scrollHeight+30;
$selectNews = "SELECT c.parent_id, p.products_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_visible,p.products_qt, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, RAND() AS m_random
                       FROM products as p
                       LEFT JOIN categories as c ON (c.categories_id = p.categories_id)
                       LEFT JOIN specials as s ON (s.products_id = p.products_id)
                       WHERE c.categories_visible = 'yes'
                       AND p.products_ref != 'GC100'
                       AND p.products_visible='yes'
                       AND p.products_forsale='yes'
                       AND TO_DAYS(NOW()) - TO_DAYS(p.products_date_added) <= '".$nbre_jour_nouv."'
                       ".$addToQuerycc."
                       ";

if(isset($_GET['path']) AND $_GET['path'] !=0) $addQueryNews = "AND c.categories_id = ".$_GET['path'].""; else $addQueryNews = "";

$resultNews = mysql_query($selectNews." ".$addQueryNews." ORDER BY m_random");
$rowsNews = mysql_num_rows($resultNews) ;

if($rowsNews==0) {
 
if(isset($_GET['path'])) {
$wN = recurs3($_GET['path']);
    if(!isset($wN)) {$rand_keysN=0;} else {$rand_keysN = array_rand($wN, 1);}
    $w1N = $wN[$rand_keysN];
}
else {
    $w1N = recurs3("");
}

if(isset($wN)) {
  $rand_keysN = array_rand($wN, 1);
  $w1N = $wN[$rand_keysN];
}
else {
  if(isset($_GET['path'])) $w1N = $_GET['path']; else $w1N=1;
}

if(isset($_GET['path'])  and $_GET['path'] !=0) $addQueryNews = "AND c.categories_id = ".$w1N.""; else $addQueryNews = "";
$resultNews = mysql_query($selectNews." ".$addQueryNews." ORDER BY m_random");
$rowsNews = mysql_num_rows($resultNews);
}


if($rowsNews>0) {
$i=0;
$Hurltous = "list.php?target=new";
while($a_rowNews = mysql_fetch_array($resultNews)) {
	//NOM du PRODUIT
	$nameNews = strip_tags(adjust_text($a_rowNews['products_name_'.$_SESSION['lang']],$maxCarInfo,".."));
	
	
	// -N1 $nameNews = strip_tags($a_rowNews['products_name_'.$_SESSION['lang']]);
	$Hvalues[$i] = "<div align='center'>";
	$Hvalues[$i] .= "<a href='beschrijving.php?id=".$a_rowNews['products_id']."&target=new' ".display_title($a_rowNews['products_name_'.$_SESSION['lang']],$maxCarInfo).">".$nameNews."</a>";
 
	if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	    $todayNews = mktime(0,0,0,date("m"),date("d"),date("Y"));
	    $dateMaxCheckNews = explode("-",$a_rowNews['specials_last_day']);
	    if(count($dateMaxCheckNews) > 1) {
	         $dateMaxNews = mktime(0,0,0,$dateMaxCheckNews[1],$dateMaxCheckNews[2],$dateMaxCheckNews[0]);
	    }
	    $dateDebutCheck = explode("-",$a_rowNews['specials_first_day']);
	    if(count($dateDebutCheck) > 1) {
	         $dateDebutNews = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
	    }
	    if(isset($dateDebutNews) and isset($dateMaxNews) and $dateDebutNews <= $todayNews and $dateMaxNews >= $todayNews) {
	         $delayPassedNews = "no";
	    }
	    else {
	         $delayPassedNews = "yes";
	    }
	    
	    $new_price = $a_rowNews['specials_new_price'];
	    $old_price = $a_rowNews['products_price'];
	    
	    if(!empty($new_price) AND $delayPassedNews == "no") {
	    	if($a_rowNews['specials_visible']=="yes") {
	        	$price = $new_price;
	        	$Hvalues[$i].= "<br><b><s>".$old_price."</s> ".$symbolDevise."</b><br><span class='fontrouge'><b>".$price." ".$symbolDevise."</b></span>";
	        }
	        else {
	        	$price = $old_price;
	        	$Hvalues[$i].= "<br><b>".$price."</b> ".$symbolDevise;
			}
	    }
		else {
	    	$price = $old_price;
	    	$Hvalues[$i].= "<br><b>".$price."</b> ".$symbolDevise;
	    }
	}
	
	
	if($a_rowNews['products_im']=="yes" AND !empty($a_rowNews['products_image']) AND $a_rowNews['products_image']!=="im/no_image_small.gif") {
		$larg_rub21 = $larg_rub-30;
		$yoZZ = @getimagesize($a_rowNews['products_image']);
		if(!$yoZZ) $a_rowNews['products_image']="im/zzz_gris.gif";
		$resize_imageN = resizeImage($a_rowNews['products_image'],$hautImageMaxNews,$larg_rub21);
		$Hvalues[$i].= "<br><a href='beschrijving.php?id=".$a_rowNews['products_id']."&target=new'>";
		$Hvalues[$i].= "<div align='center' style='padding:5px;'>";
		// Is server GD open ?
		if($gdOpen == "non") {
			$Hvalues[$i].= "<img src='".$a_rowNews['products_image']."' width='".$resize_imageN[0]."' height='".$resize_imageN[1]."' border='0' alt='".$a_rowNews['products_name_'.$_SESSION['lang']]."'>";
		}
		else {
			$infoImage = infoImageFunction($a_rowNews['products_image'],$larg_rub,$hautImageMaxNews);
			$Hvalues[$i].= "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_rowNews['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$a_rowNews['products_name_'.$_SESSION['lang']]."'>";
		}
		$Hvalues[$i].= "</a>";
		$Hvalues[$i].= "</div>";
	}
	else {
	    $Hvalues[$i].= "<img src='im/zzz.gif' width='1' height='1'>";
	}
	$Hvalues[$i].= "</div>";
	$i++;
}
?>

<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleNews">
<tr>
<td height='<?php print $hauteurTitreModule;?>' class="moduleNewsTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
   <img src="im/lang<?php print $_SESSION['lang'];?>/menu_nouveautes.png">
</td>
</tr>
<tr>
<td>

<script type="text/javascript">
 
var HEN_scrollerdelay='<?php print $scrollDelay;?>'
var HEN_scrollerwidth='<?php print $scrollWidth;?>' 
var HEN_scrollerheight='<?php print $scrollHeightZ;?>' 
var HEN_scrollerbgcolor='<?php print $scrollColor;?>' 
 
var HEN_scrollerbackground=''

 
var HEN_messages=new Array()
<?php
	for($myinc=0; $myinc < $rowsNews; $myinc++) {
	$Hvalues[$myinc] = str_replace("\"", "\\\"", $Hvalues[$myinc]);
	print "HEN_messages[".$myinc."]=\"".$Hvalues[$myinc]."\"\n";
}
?>
 

var HEN_ie=document.all
var HEN_dom=document.getElementById

if(HEN_messages.length>2)
HEN_i=2
else
HEN_i=0

function HEN_move1(whichlayer) {
  HEN_tlayer=eval(whichlayer)
  if(HEN_tlayer.top>0&&HEN_tlayer.top<=5) {
    HEN_tlayer.top=0
    setTimeout("HEN_move1(HEN_tlayer)",HEN_scrollerdelay)
    setTimeout("HEN_move2(document.HEN_main.document.HEN_second)",HEN_scrollerdelay)
    return
  }
  if(HEN_tlayer.top >= HEN_tlayer.document.height* - 1) {
    HEN_tlayer.top -= 5
    setTimeout("HEN_move1(HEN_tlayer)",50)
  }
  else{
    HEN_tlayer.top=parseInt(HEN_scrollerheight)
    HEN_tlayer.document.write(HEN_messages[HEN_i])
    HEN_tlayer.document.close()
    if(HEN_i==HEN_messages.length-1)
    HEN_i=0
    else
    HEN_i++
  }
}

function HEN_move2(whichlayer) {
  HEN_tlayer2=eval(whichlayer)
  if(HEN_tlayer2.top > 0 && HEN_tlayer2.top <= 5) {
    HEN_tlayer2.top=0
    setTimeout("HEN_move2(HEN_tlayer2)",HEN_scrollerdelay)
    setTimeout("HEN_move1(document.HEN_main.document.HEN_first)",HEN_scrollerdelay)
    return
  }
  if(HEN_tlayer2.top >= HEN_tlayer2.document.height* - 1) {
    HEN_tlayer2.top-=5
    setTimeout("HEN_move2(HEN_tlayer2)",50)
  }
  else{
    HEN_tlayer2.top=parseInt(HEN_scrollerheight)
    HEN_tlayer2.document.write(HEN_messages[HEN_i])
    HEN_tlayer2.document.close()
    if(HEN_i==HEN_messages.length-1)
    HEN_i=0
    else
    HEN_i++
  }
}

function HEN_move3(whichdiv) {
  HEN_tdiv=eval(whichdiv)
  if(parseInt(HEN_tdiv.style.top) > 0 && parseInt(HEN_tdiv.style.top) <= 5) {
    HEN_tdiv.style.top=0+"px"
    setTimeout("HEN_move3(HEN_tdiv)",HEN_scrollerdelay)
    setTimeout("HEN_move4(HEN_second2_obj)",HEN_scrollerdelay)
    return
  }
  if(parseInt(HEN_tdiv.style.top) >= HEN_tdiv.offsetHeight*-1) {
    HEN_tdiv.style.top=parseInt(HEN_tdiv.style.top)-5+"px"
    setTimeout("HEN_move3(HEN_tdiv)",50)
  }
  else {
    HEN_tdiv.style.top=parseInt(HEN_scrollerheight)
    HEN_tdiv.innerHTML=HEN_messages[HEN_i]
    if(HEN_i==HEN_messages.length-1)
    HEN_i=0
    else
    HEN_i++
  }
}

function HEN_move4(whichdiv) {
HEN_tdiv2=eval(whichdiv)
if(parseInt(HEN_tdiv2.style.top) > 0 && parseInt(HEN_tdiv2.style.top)<=5) {
  HEN_tdiv2.style.top=0+"px"
  setTimeout("HEN_move4(HEN_tdiv2)",HEN_scrollerdelay)
  setTimeout("HEN_move3(HEN_first2_obj)",HEN_scrollerdelay)
  return
}
  if(parseInt(HEN_tdiv2.style.top) >= HEN_tdiv2.offsetHeight*-1) {
    HEN_tdiv2.style.top=parseInt(HEN_tdiv2.style.top)-5+"px"
    setTimeout("HEN_move4(HEN_second2_obj)",50)
  }
  else {
    HEN_tdiv2.style.top=parseInt(HEN_scrollerheight)
    HEN_tdiv2.innerHTML=HEN_messages[HEN_i]
    if(HEN_i==HEN_messages.length-1)
    HEN_i=0
    else
    HEN_i++
  }
}

function HEN_startscroll() {
  if(HEN_ie||HEN_dom) {
    HEN_first2_obj=HEN_ie? HEN_first2 : document.getElementById("HEN_first2")
    HEN_second2_obj=HEN_ie? HEN_second2 : document.getElementById("HEN_second2")
    HEN_move3(HEN_first2_obj)
    HEN_second2_obj.style.top=HEN_scrollerheight
    HEN_second2_obj.style.visibility='visible'
  }
  else if(document.layers) {
    document.HEN_main.visibility='show'
    HEN_move1(document.HEN_main.document.HEN_first)
    document.HEN_main.document.HEN_second.top=parseInt(HEN_scrollerheight)+5
    document.HEN_main.document.HEN_second.visibility='show'
  }
}
</script>


<ilayer id="HEN_main" width=&{HEN_scrollerwidth}; height=&{HEN_scrollerheight}; bgColor=&{HEN_scrollerbgcolor}; background=&{HEN_scrollerbackground}; visibility=hide>
<layer id="HEN_first" left=0 top=1 width=&{HEN_scrollerwidth};>
<script language="JavaScript1.2">
if(document.layers)
document.write(HEN_messages[0])
</script>
</layer>
<layer id="HEN_second" left=0 top=0 width=&{HEN_scrollerwidth}; visibility=hide>
<script language="JavaScript1.2">
if(document.layers)
document.write(HEN_messages[dyndetermine=(HEN_messages.length==1)? 0 : 1])
</script>
</layer>
</ilayer>
<script language="JavaScript1.2">
if(HEN_ie||HEN_dom) {
  document.writeln('<div id="HEN_main2" style="position:relative;width:'+HEN_scrollerwidth+';height:'+HEN_scrollerheight+';overflow:hidden;background-color:'+HEN_scrollerbgcolor+' ;background-image:url('+HEN_scrollerbackground+')">')
  document.writeln('<div style="position:absolute;width:'+HEN_scrollerwidth+';height:'+HEN_scrollerheight+';clip:rect(0 '+HEN_scrollerwidth+' '+HEN_scrollerheight+' 0);left:0px;top:0px">')
  document.writeln('<div id="HEN_first2" style="position:absolute;width:'+HEN_scrollerwidth+';left:0px;top:1px;">')
  document.write(HEN_messages[0])
  document.writeln('</div>')
  document.writeln('<div id="HEN_second2" style="position:absolute;width:'+HEN_scrollerwidth+';left:0px;top:0px;visibility:hidden">')
  document.write(HEN_messages[dyndetermine=(HEN_messages.length==1)? 0 : 1])
  document.writeln('</div>')
  document.writeln('</div>')
  document.writeln('</div>')
}

<?php
if($rowsNews>1) { 
?>
HEN_startscroll()
<?php 
}
?>

</script>
<?php
if($rowsNews > 0) print "<div align='center'><a href='list.php?target=new'><img src='im/plus.gif' align='absmiddle' border='0' alt=".maj(AUTRES)." title=".maj(AUTRES)."></a></div>";
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
