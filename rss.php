<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

$menu_top2 = maj(DIFFUSEZ_CONTENU);
$title = DIFFUSEZ_CONTENU;
$description = DIFFUSEZ_CONTENU." ".strip_tags($store_name)." - ".strip_tags(RSS_TEXT);
$keywords = DIFFUSEZ_CONTENU." ".ACTU.", ".DIFFUSEZ_CONTENU." ".AJOUTE_BOUT.", ".DIFFUSEZ_CONTENU." ".PROMOTIONS.", ".DIFFUSEZ_CONTENU." ".VENTES_FLASH.", ".DIFFUSEZ_CONTENU." ".DIX_MEILLEURES_VENTES;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
include('includes/geen_script.php');

include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

 
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

 
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
 
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}

if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;
?>

      <?php if($tableDisplay=='oui') {?>
          <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
          <tr height="32">
          <?php if($tableDisplayLeft=='oui') {?>
          <td>
          <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print $menu_top2;?> |</b>
          </td>
          <?php 
          }
          if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
          </tr>
          </table>
      <?php }?>
                      
          <?php include('includes/promo_afbeelden.php');?>
</td>
</tr>
<tr valign="top">
<td colspan="3">

    <table width="100%" height="100%" border="0" cellpadding="3" cellspacing="5">
    <tr>
          <?php
		  
		  // linkse kolom 
		  
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
    <td valign="top" class="TABLEPageCentreProducts">
                  <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100%">
                  <tr>
                  <td valign="top">
<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                  <tr>
                  <td style="padding-left:0px">
                  <?php 
                  print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".$menu_top2." | ";
                  ?>
                  </td>
                  </tr>
                  </table>
<?php }?>











<?php
if($activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 
<?php if($activeActu=="oui") {?>
  <tr>
    <td>&diams;&nbsp;<b><?php print ACTU;?></b></td>
  </tr>
  <tr>
    <td valign="middle">
      &nbsp;&nbsp;<input name="textfield" type="text" value="http://<?php print $www2.$domaineFull;?>/rss/rss_actueel.php?lang=<?php print $_SESSION['lang'];?>" size="70">
      <a href="http://<?php print $www2.$domaineFull;?>/rss/rss_actueel.php?lang=<?php print $_SESSION['lang'];?>" target="_blank"><img src="im/rss_rss.gif" align="absmiddle" border="0" alt="Flux RSS - <?php print AJOUTE_BOUT;?>" title="Flux RSS - <?php print ACTU;?>"></a>
   </td>
   <tr></tr><td>&nbsp;</td>
  </tr>
<?php }?>
 
<?php if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {?>
  <tr>
    <td>&diams;&nbsp;<b><?php print AJOUTE_BOUT;?></b></td>
  </tr>
  <tr>
    <td valign="middle">
      &nbsp;&nbsp;<input name="textfield" type="text" value="http://<?php print $www2.$domaineFull;?>/rss/rss_nieuws.php?lang=<?php print $_SESSION['lang'];?>" size="70">
      <a href="http://<?php print $www2.$domaineFull;?>/rss/rss_nieuws.php?lang=<?php print $_SESSION['lang'];?>" target="_blank"><img src="im/rss_rss.gif" align="absmiddle" border="0" alt="Flux RSS - <?php print AJOUTE_BOUT;?>" title="Flux RSS - <?php print AJOUTE_BOUT;?>"></a>
   <tr></tr><td>&nbsp;</td>
   </td>
  </tr>
<?php }?>

 
<?php if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {?>
  <tr>
    <td>&diams;&nbsp;<b><?php print PROMOTIONS;?></b></td>
  </tr>
  <tr>
    <td valign="middle">
      &nbsp;&nbsp;<input name="textfield" type="text" value="http://<?php print $www2.$domaineFull;?>/rss/rss_promoties.php?lang=<?php print $_SESSION['lang'];?>" size="70">
      <a href="http://<?php print $www2.$domaineFull;?>/rss/rss_promoties.php?lang=<?php print $_SESSION['lang'];?>" target="_blank"><img src="im/rss_rss.gif" align="absmiddle" border="0" alt="Flux RSS - <?php print PROMOTIONS;?>" title="Flux RSS - <?php print PROMOTIONS;?>"></a>
   <tr></tr><td>&nbsp;</td>
   </td>
  </tr>
<?php }?>
  
 
<?php if($activeSeuilPromo=="oui" AND $seuilPromo > 0) {?>
  <tr>
    <td>&diams;&nbsp;<b><?php print VENTES_FLASH;?></b></td>
  </tr>
  <tr>
    <td valign="middle">
      &nbsp;&nbsp;<input name="textfield" type="text" value="http://<?php print $www2.$domaineFull;?>/rss/rss_flash.php?lang=<?php print $_SESSION['lang'];?>" size="70">
      <a href="http://<?php print $www2.$domaineFull;?>/rss/rss_flash.php?lang=<?php print $_SESSION['lang'];?>" target="_blank"><img src="im/rss_rss.gif" align="absmiddle" border="0" alt="Flux RSS - <?php print VENTES_FLASH;?>" title="Flux RSS - <?php print VENTES_FLASH;?>"></a>
   <tr></tr><td>&nbsp;</td>
   </td>
  </tr>
<?php }?>

 
  <tr>
    <td>&diams;&nbsp;<b><?php print DIX_MEILLEURES_VENTES;?></b></td>
  </tr>
  <tr>
    <td valign="middle">
      &nbsp;&nbsp;<input name="textfield" type="text" value="http://<?php print $www2.$domaineFull;?>/rss/rss_top10.php?lang=<?php print $_SESSION['lang'];?>" size="70">
      <a href="http://<?php print $www2.$domaineFull;?>/rss/rss_top10.php?lang=<?php print $_SESSION['lang'];?>" target="_blank"><img src="im/rss_rss.gif" align="absmiddle" border="0" alt="Flux RSS - <?php print DIX_MEILLEURES_VENTES;?>" title="Flux RSS - <?php print DIX_MEILLEURES_VENTES;?>"></a>
   <tr></tr><td>&nbsp;</td>
   </td>
  </tr>
<tr>
   <td><?php print RSS_TEXT.RSS_TEXT2;?></td>
</tr>
</table>

<?php
}
else {
print '<table width="100%" border="0" cellspacing="0">
  <tr>
    <td align="center">
    <br>
    <p align="center">
      <b>'.NO_RSS.'</b>
    </p>
    </td>
  </tr>
  </table>';
}
?>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  </td>
                  </tr>
                  <tr>
                  <td colspan="2" valign="bottom" align='center'>
                  <?php 
                  if(isset($_GET['info']) AND ($_GET['info']!=="11" AND $_GET['info']!=="12")) {
                    print "<b>".$store_company."</b><br>";
                    print $address_street."<br>";
                    print $address_cp." - ".$address_city."<br>";
                    print $address_country;
                    if(!empty($address_autre)) print "<br>".$address_autre;
                    if(!empty($tel)) print "<br>".TELEPHONE." : ".$tel;
                    if(!empty($fax)) print "<br>".FAX." : ".$fax;
                  }
                  ?>
                  </td>
                  </tr>
                  </table>
     </td>
         <?php 
		  
	 
		  
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
     </tr>
     </table>

</td>
</tr>
</table>
<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
