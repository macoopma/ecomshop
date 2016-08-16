<?php

?>

<?php
if(!isset($displayPayments) OR $displayPayments !== 0) {
?>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEBottomPage">
	<tr height="32">
		<td align="center">
 
          |&nbsp;<a href="<?php echo seoUrlConvert("cataloog.php"); ?>"><?php print HOME;?></a>&nbsp;
  
          |&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=10"); ?>"><?php print QUI_SOMMES_NOUS;?></a>&nbsp;
   
          <?php if($activeActu=="oui") {?>
          |&nbsp;<a href="<?php echo seoUrlConvert("nieuws.php"); ?>"><?php print ACTUS;?></a>&nbsp;
          <?php }?>
    
          |&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=5"); ?>"><?php print NOUS_CONTACTER;?></a>&nbsp;
     
          |&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=9"); ?>"><?php print PARTENAIRES;?></a>&nbsp;
      
          |&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=4"); ?>"><?php print CONDITIONS_DE_VENTE;?></a>&nbsp;
       
          |&nbsp;<a href="<?php echo seoUrlConvert("sitemap_html.php"); ?>"><?php print SITEMAP;?></a>&nbsp;
          |
          <br>
        
          &bull;&nbsp;<a href="<?php echo seoUrlConvert("caddie.php"); ?>"><?php print VOTRE_CADDIE;?></a>&nbsp;
         
          &bull;&nbsp;<a href="<?php echo seoUrlConvert("infos.php?info=11"); ?>"><?php print SUIVI_COMMANDE;?></a>&nbsp;
          <?php
            if($affiliateVisible=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
          
               print "&bull;&nbsp;<a href='".seoUrlConvert("affiliation.php")."'>".AFFILIATION."</a>&nbsp;";
            }
            if($devisModule == 'oui' AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
           
               print "&bull;&nbsp;<a href='pagina_box.php?module=menu_devis'>".VOS_DEVIS."</a>&nbsp;";
            }
          ?>
           
          &bull;&nbsp;<a href="<?php echo seoUrlConvert("printcataloog.php"); ?>" target="_blank"><?php print IMPRIMER_CATALOGUE;?></a>&nbsp;&bull;
          

          <?php 
            if($activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
               print "<div align='center' style='padding-top:3px;'><a href='".seoUrlConvert("rss.php")."'><img align='absmiddle' src='im/rss_small.gif' border='0' title='".DIFFUSEZ_CONTENU."' alt='".DIFFUSEZ_CONTENU."'></a></div>";
            }
          ?>
		</td>
	</tr>
</table>
<?php 

if($bannerVisible=="oui" AND $bannerFooter=="oui") {
      print "<img src='im/zzz.gif' width='1' height='5'><br>";
      print "<table width='99%' align='center' border='0' cellspacing='0' cellpadding='5' class='tablesFooter'>";
      print "<tr><td align='center'>";
         include('includes/banner.php');
      print "</td></tr></table>";
      if($catFooter=='non') {print "<img src='im/zzz.gif' width='1' height='5'><br>";}
}
 
if($catFooter=='oui') {
      print "<img src='im/zzz.gif' width='1' height='5'><br>";
      print "<table width='99%' align='center' border='0' cellspacing='0' cellpadding='5' class='tablesFooter'>";
      print "<tr><td align='center'>";
   unset($_SESSION['catInFooter']);
   if(!isset($_SESSION['catInFooter']) OR isset($_GET['lang'])) {
      $_SESSION['catInFooter']='';
      if($_SESSION['lang'] == "1") $LangMenu = 5;
      if($_SESSION['lang'] == "2") $LangMenu = 9;
      if($_SESSION['lang'] == "3") $LangMenu = 10;
      
      foreach($_SESSION['tree'] AS $key=>$value) {
         if($value[$LangMenu] == 'Menu') $value[$LangMenu] = "";
         if($value[3]=='B') {
            $_SESSION['catInFooter'].= "<a href='".seoUrlConvert("categories.php?path=".$value[4])."'><span class='PromoFont'>".$value[$LangMenu]."</span></a> &bull; ";
         }
         else {
            if($value[4]!=='0') $_SESSION['catInFooter'].= "<a href='".seoUrlConvert("list.php?path=".$value[4])."'><span class='fontrouge'>".$value[$LangMenu]."</span></a> &bull; ";
         }
      }
      print $_SESSION['catInFooter'];
   }
   else {
      print $_SESSION['catInFooter'];
   }

      print "</td></tr></table>";
      //print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
}

 
?>
<img src='im/zzz.gif' width='1' height='5'><br>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	
	<tr>
	<td align='left' valign='top' width='100'>
		<a href='<?php echo seoUrlConvert("infos.php?info=5&from=web"); ?>'><b><?php print COMMENTAIRES;?></b></a>
	</td>

	</tr>
</table>
<br><br>
<?php
}

 
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==1 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'OPGELET:\nDe gevraagde hoeveelheid is niet in voorraad.\nHet maximum in voorraad wordt toegevoegd aan uw winkelmandje.';
alert (messagePerso);
//-->
</script>
<?php
}


 
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==2 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'Opgelet:\nEr is geen vast leverings tarief voor dit artikel.\nContacteer ons voor meer informatie.';
alert (messagePerso);
//-->
</script>
<?php
}


 
if(isset($_SESSION['stockInf']) AND $_SESSION['stockInf']==3 ) {
?>
<script type='text/javascript'>
<!--
      var messagePerso = "";
      messagePerso = messagePerso + 'Opgelet:\nDeze optie is niet beschikbaar.\nMaak een andere selectie.';
alert (messagePerso);
//-->
</script>
<?php
}
if(isset($_SESSION['stockInf'])) unset($_SESSION['stockInf']);
?>

