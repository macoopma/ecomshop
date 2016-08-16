<?php
if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
?>
<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

         <table border="0" cellspacing="0" cellpadding="2" class="moduleNews">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="moduleNewsTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <?php print "<a href='list.php?target=new'>";?><img src="im/lang<?php print $_SESSION['lang'];?>/menu_nouveautes.png" border="0" title="<?php print NOUVEAUTES;?>" alt="<?php print NOUVEAUTES;?>"></a>
         </td>
         </tr>
         <tr>
         <td>

<?php

if($displayOutOfStock=="non") {$addToQuerycc = " AND p.products_qt>'0'";} else {$addToQuerycc="";}

$selectNews = "SELECT c.parent_id, p.products_id, p.categories_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_visible,p.products_qt, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, RAND() AS m_random
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

if(isset($_GET['path']) and $_GET['path'] !==0) $addQueryNews = " AND c.categories_id = ".$_GET['path'].""; else $addQueryNews = "";

$resultNews = mysql_query($selectNews.$addQueryNews." ORDER BY m_random LIMIT 0,".$nbre_nouv);

$rowsNews = mysql_num_rows($resultNews);

if($rowsNews==0) {
 
	if(isset($_GET['path'])) {
	   $wN = recurs3($_GET['path']);
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
	
	if(isset($_GET['path'])  and $_GET['path'] !==0) $addQueryNews = " AND c.categories_id = '".$w1N."'"; else $addQueryNews = "";
	$resultNews = mysql_query($selectNews.$addQueryNews." ORDER BY m_random LIMIT 0,".$nbre_nouv);
	$rowsNews = mysql_num_rows($resultNews);
	
 
	if($rowsNews==0 AND $forceNouv=='oui') {
	   $resultNews = mysql_query($selectNews." ORDER BY m_random LIMIT 0,".$nbre_nouv);
	   $rowsNews = mysql_num_rows($resultNews);
	}
}

if($rowsNews==0) {
	print "<div align='center'>".
    PAS_DE_NOUVEAUTES_DANS_CETTE_CATEGORIE."<br>
    <a href='list.php?target=new'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a></div>";
}
else {
	while($a_rowNews = mysql_fetch_array($resultNews)) {
      	print "<img src='im/fleche_right.gif'>";
      	$nameNews = adjust_text($a_rowNews['products_name_'.$_SESSION['lang']],$maxCarInfo,"..");
      	print "&nbsp;<a href='beschrijving.php?id=".$a_rowNews['products_id']."&target=new' ".display_title($a_rowNews['products_name_'.$_SESSION['lang']],$maxCarInfo).">".$nameNews."</a><br>";
      	//print "<div align='left' class='fontgris'>".adjust_text(strip_tags($a_rowNews['products_desc_'.$_SESSION['lang']]),58,"..<a href='beschrijving.php?id=".$a_rowNews['products_id']."'><img src='im/next.gif' width='7' height='5' border='0'></a>")."</div>";

 
		$todayNews = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$dateMaxCheckNews = explode("-",$a_rowNews['specials_last_day']);
		if(count($dateMaxCheckNews) > 1) {
			$dateMaxNews = mktime(0,0,0,$dateMaxCheckNews[1],$dateMaxCheckNews[2],$dateMaxCheckNews[0]);
		}
		$dateDebutCheck = explode("-",$a_rowNews['specials_first_day']);
			if(count($dateDebutCheck) > 1) {
		$dateDebutNews = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
		}
		if(isset($dateDebutNews) and isset($dateMaxNews) and $dateDebutNews <= $todayNews  and $dateMaxNews >= $todayNews) {
			$delayPassedNews = "no";
		}
		else {
			$delayPassedNews = "yes";
		}

		$new_price = $a_rowNews['specials_new_price'];
		$old_price = $a_rowNews['products_price'] ;
	
		if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
			if(!empty($new_price) AND $delayPassedNews == "no") {
				if($a_rowNews['specials_visible']=="yes") {
					$price = $new_price;
					print "<div align='center'><b><s>".$old_price."</s> ".$symbolDevise."/<span class='fontrouge'>".$price." ".$symbolDevise."</span></b></div>";
					$clientPrice = $new_price;
				}
				else {
					$price = $old_price;
					print "<div align='center'><b>".$price." ".$symbolDevise."</b></div>";
					$clientPrice = $old_price;
				}
			}
			else {
				$price = $old_price;
				print "<div align='center'><b>".$price." ".$symbolDevise."</b></div>";
				$clientPrice = $old_price;
			}
			if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
				print "<div align='center'>".VOTRE_PRIX." : <b><span class='fontrouge'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b></div>";
			}
		}
      
  
      if($a_rowNews['products_im']=="yes" AND $a_rowNews['products_image']!=="" AND $a_rowNews['products_image']!=="im/no_image_small.gif") {
          $larg_rub21 = $larg_rub-30;
            $yoZZ = @getimagesize($a_rowNews['products_image']);
            if(!$yoZZ) $a_rowNews['products_image']="im/zzz_gris.gif";
          $resize_imageN = resizeImage($a_rowNews['products_image'],$hautImageMaxNews,$larg_rub21);
          print "<div align='center' style='padding:5px;'>";
          print "<a href='beschrijving.php?id=".$a_rowNews['products_id']."&target=new'>";
   
             if($gdOpen == "non") {
               print "<img src='".$a_rowNews['products_image']."' width='".$resize_imageN[0]."' height='".$resize_imageN[1]."' border='0' alt='".$a_rowNews['products_name_'.$_SESSION['lang']]."'>";
             }
             else {
               $infoImage = infoImageFunction($a_rowNews['products_image'],$larg_rub21,$hautImageMaxNews);
               print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_rowNews['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$a_rowNews['products_name_'.$_SESSION['lang']]."'>";                  
             }
      
          print "</a>";
          print "</div>";
      }
      else {
         print "<div align='center'><img src='im/zzz.gif' width='1' height='1'></div>";
      }
    }
	if($rowsNews > 0) print "<div align='center'><a href='list.php?target=new'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a></div>";
}
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
?>
