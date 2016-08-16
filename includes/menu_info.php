<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

         <table border="0" cellspacing="0" cellpadding="2" class="moduleInfo">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="moduleInfoTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <img src="im/lang<?php print $_SESSION['lang'];?>/menu_infos.png">
         </td>
         </tr>
         <tr>
         <td>










            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=4"); ?>" <?php print display_title(CONDITIONS_D_USAGE,$maxCarInfo);?>><?php print adjust_text(CONDITIONS_D_USAGE,$maxCarInfo,"..");?></a></div>
            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=3"); ?>"><?php print PAIEMENTS;?></a></div>
            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=5"); ?>"><?php print NOUS_CONTACTER;?></a></div>
                        
            <hr width='75%'>
            
            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("printcataloog.php"); ?>" target="_blank"><?php print IMPRIMER_CATALOGUE;?></a></div>
            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=10"); ?>"><?php print QUI_SOMMES_NOUS;?></a></div>
            <div><img src="im/fleche_right.gif">&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=9"); ?>"><?php print PARTENAIRES;?></a></div>
            <?php if($activeActu=="oui") print "<div><img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("nieuws.php")."'>".ACTUS."</a></div>";?>


<?php 
 
if(isset($_SESSION['account']) OR $activeEcom=="oui") {
    if(isset($_SESSION['list'])) {
        print '<br><img src="im/fleche_right.gif">&nbsp;<a href="'.seoUrlConvert("infos.php?info=6").'">'.UTILISATION_CADDIE.'</a>';
    }
}

 
   if($activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
      print "<hr width='75%'>";
      print "<div align='center'><a href='rss.php'><img align='absmiddle' src='im/rss_small.gif' border='0' title='".DIFFUSEZ_CONTENU."' alt='".DIFFUSEZ_CONTENU."'></a></div>";
   }

 
print "<hr width='75%'>";
print "<div align='left'><img src='im/fleche_right.gif'>&nbsp;".PAIEMENT_SECURISE."</div>";
print "<div align='center'><a href='".seoUrlConvert("infos.php?info=3")."' border='0'><img src='im/payment.gif' border='0' title='".PAIEMENT_SECURISE."' alt='".PAIEMENT_SECURISE."'></a></div>";

 
if($paypalPayment == "oui") {
   print '<div align="center"><a href="'.seoUrlConvert("infos.php?info=3").'" border="0"><img alt="'.PAIEMENT.'Paypal" title="'.PAIEMENT.' Paypal" src="im/betaal-logos/paypal_g.gif" border="0"></a><br><img src="im/zzz.gif" width="1" height="5"><br></div>';
}

 
if($euroPayment == "oui" AND $id_partenaire!=="" AND $displayGraphics == "oui") {
   print '';
}
if($euroPayment == "oui" AND ($displayGraphics == "non" OR $id_partenaire=="")) {
   print '<div align="center"><a href="'.seoUrlConvert("infos.php?info=3").'" border="0"><img alt="'.PAIEMENT.' 1euro.com" title="'.PAIEMENT.' 1euro.com" src="im/betaal-logos/1euro_g.gif" border="0"></a><br><img src="im/zzz.gif" width="1" height="5"><br></div>';
}

?>









        </td>
        </tr>
        </table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
