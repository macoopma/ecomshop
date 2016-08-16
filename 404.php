<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

$title = NO_DOC;
$title2 = NO_DOC;
$message1 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".PAGE400_TITRE."</p>";
$message2 = PAGE404;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
// Display header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

// Display header2
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

// Menu tab
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
// Menu horizontaal
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
          <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print STRTOUPPER(HOME);?></a> | <?php print $title2;?></b>
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
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
    <td valign="top" class="TABLEPageCentreProducts">
                  <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100%">
                  <tr>
                  <td valign="top">






<table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
<tr>
<td>
<?php 
print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php?path=0&num=1&action=c'>".strtoupper(HOME)."</a> | ".$title2;
?>
</td>
</tr>
</table>






<?php
if(isset($message1)) print $message1;
if(isset($message2)) print $message2;
?>









                  </td>
                  </tr>
                  </table>
     </td>
         <?php 
		  // ---------------------------------------
		  // rechtse kolom 
		  // ---------------------------------------
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
