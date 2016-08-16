<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

if(isset($_GET['id']) AND $_GET['id']!=='') {
  $idZ = $_GET['id']-1000;
  
  $querySentZ = mysql_query("SELECT page_added_use, page_added_title_".$_SESSION['lang'].", page_added_message_".$_SESSION['lang']." FROM page_added WHERE page_added_id = '".$idZ."' AND page_added_visible='yes'");
  $message1CountZ = mysql_num_rows($querySentZ);
  if($message1CountZ>0) {
     $messageStockZ = mysql_fetch_array($querySentZ);
     if($messageStockZ['page_added_use']=='actu') {
       $title = ACTU;
       $title2 = "<a href='nieuws.php'>".ACTU_MAJ."</a>";
       $messageT1 = "<p>";
       $messageT1.= "<b>".$messageStockZ['page_added_title_'.$_SESSION['lang']]."</b>";
       $messageT1.= "<br><br>";
       $messageT1.= $messageStockZ['page_added_message_'.$_SESSION['lang']];
       $messageT1.= "</p>";
       $messageT1.= "<p align='center'>";
	   $messageT1.= "<div align='center'><a href='nieuws.php'><u>".VOIR_ALL_ACTUS."</u></a></p></div>";
	   $messageT1.= "<br>";
       $messageT1.= "<div align='center'><a href='javascript:history.back()'><img src='im/lang".$_SESSION['lang']."/articles.gif' border='0'></a></div>";
	   $messageT1.= "</p>";
     }
     else {
       $title = strip_tags($messageStockZ['page_added_title_'.$_SESSION['lang']]);
       $title2 = $title;
       $messageT1 = "<p>";
       $messageT1.= $messageStockZ['page_added_message_'.$_SESSION['lang']];
       $messageT1.= "</p>";
       $messageT1.= "<p align='center'>";
       $messageT1.= "<br>";
	   $messageT1.= "<div align='center'><a href='javascript:history.back()'><img src='im/lang".$_SESSION['lang']."/articles.gif' border='0'></a></div>";
	   $messageT1.= "</p>";
     }
  }
  else {
     $title = NO_DOC;
     $title2 = NO_DOC;
     $messageT1 = "<p align='center' class='fontrouge'><b>".NO_DOC."</b></p>";
  }
}
else {
     $title = NO_DOC;
     $title2 = NO_DOC;
     $messageT1 = "<p align='center' class='fontrouge'><b>".NO_DOC."</b></p>";
}

// meta Tags
$description = $title." ".$store_name;
$keywords = $title.", ".$store_name.", ".$keywords;
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
//   no JS
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php
//   header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

//   header2
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
          <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print $title2;?></b>
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





<?php
if($addNavCenterPage=="oui") {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
<tr>
<td>
<?php 
print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".$title2;
?>
</td>
</tr>
</table>
<?php
}
?>












<?php
if(isset($messageT1)) print $messageT1;
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
