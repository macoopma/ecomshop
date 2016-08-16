<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");

if(!isset($_GET['page'])) $_GET['page']=0;

$varQuery = "SELECT page_added_title_".$_SESSION['lang'].", page_added_id, page_added_date, page_added_message_".$_SESSION['lang']." FROM page_added WHERE page_added_use='actu' AND page_added_visible='yes' ORDER BY page_added_id DESC";
$querySentZZ = mysql_query($varQuery) or die (mysql_error());
$tototal = mysql_num_rows($querySentZZ);
$querySentZZ = mysql_query($varQuery." LIMIT ".$_GET['page'].",".$nbreLigneActu)  or die (mysql_error());

$NavNum = "<a href=\"nieuws.php?page=";


if($tototal==0) {
   $title = NO_DOC;
   $message = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".NO_DOC."</p>";
   $title2 = NO_DOC;
}
else {
   $title = ACTU;
   $title2 = ACTU_MAJ;
}

 
function setText($text, $max, $comet) {
   $max2 = $max-4;
   if(strlen($text) >= $max) $textR = substr($text, 0, $max2).$comet; else $textR = $text;
   return $textR;
}
 
function removeHtmlTags($var, $id) {
   GLOBAL $maxCarActu;
                  $desc = strip_tags($var);
                  $desc = str_replace("\t", " ", $desc);
                  $desc = str_replace("\r\n", ". ", $desc);
                  $desc = str_replace("\n", ". ", $desc);
                  $desc = str_replace("/t", " ", $desc);
                  $desc = str_replace("'", "", $desc);
                  $desc = trim($desc);
                  $desc = setText($desc,$maxCarActu,"...<a href='doc.php?id=".$id."'><img src='im/next.gif' border='0'></a>");
                  return $desc;
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
  <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print maj($title2);?></b>
  <?php
        if($activeActu=="oui" AND $activeRSS=="oui") {
            print "&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_actueel.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".$title2."' alt='RSS ".$title2."'></a>";
        }
  ?>
  
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
<td style="padding-left:0px">
<?php 
print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".maj($title2);
?>
</td>
</tr>
</table>
<?php
}
?>


















<?php
 
if($tototal > $nbreLigneActu) {
   print "<table border='0' width='100%' cellspacing='0' cellpadding='2' align='center'>";
   print "<tr>";
   print "<td  valign='bottom'>";
           include('includes/lijst_navigatie.php');
           displayPageNum($nbreLigneActu);
   print "</td></tr>";
   print "</table>";
}  

if($tototal > 0) {
   print "<table border='0' width='100%' cellspacing='0' cellpadding='2' align='center'>";
   while($actu = mysql_fetch_array($querySentZZ)) {
      if($actu['page_added_title_'.$_SESSION['lang']]!=="") {
	      $idZA = $actu['page_added_id']+1000;
	      print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
		  print "<td>";
	      print "<img src='im/newNews.gif' align='absmiddle'>&nbsp;";
	      print "&nbsp;<span class='FontGris'>[<i>".dateFr($actu['page_added_date'], $_SESSION['lang'])."</i>]</span><br>";
	      print "<a href='doc.php?id=".$idZA."'><b>".$actu['page_added_title_'.$_SESSION['lang']]."</b></a><br>";
	      print removeHtmlTags($actu['page_added_message_'.$_SESSION['lang']], $idZA);
	      print "<div><img src='im/zzz.gif' width='1' height='8'></div>";
		  print "</td>";
		  print "</tr>";
      }
   }
   print "</table>";
}
else {
   if(isset($message)) print $message;
}
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
