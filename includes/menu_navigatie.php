<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleNavigate">
	<tr>
		<td height='<?php print $hauteurTitreModule;?>' class="moduleNavigateTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
			<img src="im/lang<?php print $_SESSION['lang'];?>/menu_navigate.png">
			<!--<span style='font-size:13px; color:#FFFFFF'><b>DIVERS</b></span>-->
		</td>
	</tr>
	<tr>
		<td>
			<div><img src='im/zzz.gif' height="1" width="1"></div>
		</td>
	</tr>
	<tr>
		<td>

<?php
 
$pagePersoQuery = mysql_query("SELECT page_added_use, page_added_url, page_added_image, page_added_id, page_added_title_".$_SESSION['lang'].", page_added_message_".$_SESSION['lang']." FROM page_added WHERE page_added_use='perso' OR page_added_use='link' OR page_added_use='image' AND page_added_visible='yes'") or die (mysql_error());
if(mysql_num_rows($pagePersoQuery) > 0) {
    while($pagesPersoResult = mysql_fetch_array($pagePersoQuery)) {
    	$commentLeg = ($pagesPersoResult['page_added_title_'.$_SESSION['lang']]!=="")? strip_tags($pagesPersoResult['page_added_title_'.$_SESSION['lang']]) : "";
		if($pagesPersoResult['page_added_use']!=='link') {
			if($pagesPersoResult['page_added_image']!=="") {
				if($pagesPersoResult['page_added_url']!=="") {
					print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
					print "<div align='center'><a href='doc.php?id=10".$pagesPersoResult['page_added_id']."' alt='".$commentLeg."' title='".$commentLeg."'><img src='".$pagesPersoResult['page_added_image']."' border='0'></a></div>";
	        		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				}
				else {
					if($commentLeg=="") {
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
						print "<div align='center'><img src='".$pagesPersoResult['page_added_image']."' border='0'></div>";	
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
					}
					else {
						if($pagesPersoResult['page_added_message_'.$_SESSION['lang']]!=="") {
							print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
							print "<div align='center'><a href='doc.php?id=10".$pagesPersoResult['page_added_id']."'><img src='".$pagesPersoResult['page_added_image']."' border='0' alt='".$commentLeg."' title='".$commentLeg."'></a></div>";	
							print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
						}
						else {
							print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
							print "<div align='center'><img src='".$pagesPersoResult['page_added_image']."' border='0' alt='".$commentLeg."' title='".$commentLeg."'></div>";	
							print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
						}
					}
				}
			}
	        else {
				print "<div><img src='im/fleche_right.gif'>&nbsp;<a href='doc.php?id=10".$pagesPersoResult['page_added_id']."' alt='".$commentLeg."' title='".$commentLeg."'>".adjust_text($commentLeg,$maxCarInfo,"..")."</a></div>";
	    	}
	    }
	    else {
			if($pagesPersoResult['page_added_image']=="") {
				if($commentLeg!=="") print "<div><img src='im/fleche_right.gif'>&nbsp;<a href='".$pagesPersoResult['page_added_url']."' alt='".$commentLeg."' title='".$commentLeg."' target='_blank'>".adjust_text($commentLeg,$maxCarInfo,"..")."</a></div>";
			}
			else {
				if($pagesPersoResult['page_added_url']!=="") {
					print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
					print "<div align='center'><a href='".$pagesPersoResult['page_added_url']."' alt='".$commentLeg."' title='".$commentLeg."' target='_blank'><img src='".$pagesPersoResult['page_added_image']."' border='0'></a></div>";	
					print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				}
				else {
					if($commentLeg=="") {
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
						print "<div align='center'><img src='".$pagesPersoResult['page_added_image']."' border='0'></div>";	
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
					}
					else {
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
						print "<div align='center'><img src='".$pagesPersoResult['page_added_image']."' border='0' alt='".$commentLeg."' title='".$commentLeg."'></div>";	
						print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
					}
				}
			}
		}
	}
}
?>
		</td>
	</tr>
</table>

</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>