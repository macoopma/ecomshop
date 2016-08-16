<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleRss">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="moduleRssTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <a href="rss.php?lang=<?php print $_SESSION['lang'];?>" alt="<?php print DIFFUSEZ_CONTENU;?>" title="<?php print DIFFUSEZ_CONTENU;?>"><img src="im/lang<?php print $_SESSION['lang'];?>/menu_rss.png" border="0"></a>
         </td>
         </tr><tr>
         <td><div><img src='im/zzz.gif' height="1" width="1"></div></td>
         </tr><tr>
         <td>

<?php
 


if($activeActu=="oui") {print "<div><img src='im/rss.png'>&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_actueel.php?lang=".$_SESSION['lang']."' target='_blank' alt='".ACTUS."' title='".ACTUS."'>".ACTUS."</a></div>";}
 
if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {print "<div><img src='im/rss.png'>&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_nieuws.php?lang=".$_SESSION['lang']."' target='_blank' alt='".NOUVEAUTES."' title='".NOUVEAUTES."'>".NOUVEAUTES."</a></div>";}
 
if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {print "<div><img src='im/rss.png'>&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_promoties.php?lang=".$_SESSION['lang']."' target='_blank' alt='".PROMOTIONS."' title='".PROMOTIONS."'>".PROMOTIONS."</a></div>";}
 
if($activeSeuilPromo=="oui" AND $seuilPromo > 0) {print "<div><img src='im/rss.png'>&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_flash.php?lang=".$_SESSION['lang']."' target='_blank' alt='".VENTES_FLASH."' title='".VENTES_FLASH."'>".VENTES_FLASH."</a></div>";}
 
print "<div><img src='im/rss.png'>&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_top10.php?lang=".$_SESSION['lang']."' target='_blank' alt='Top 10' title='Top 10'>Top 10</a></div>";
?>

</td>
</tr>
</table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
