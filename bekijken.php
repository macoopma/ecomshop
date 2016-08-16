<?php
session_start();
include('configuratie/configuratie.php');
include('includes/doctype.php');

$title = "Bekijken - Voir";
if(isset($_GET['do']) OR isset($_GET['fromPage'])) {
   if(isset($_GET['do'])) {
    $idZ = $_GET['id']-1000;
    $querySent = mysql_query("SELECT * FROM page_added WHERE page_added_id = '".$idZ."'");
    $messageStock = mysql_fetch_array($querySent);
   }
   else {

         $querySent = mysql_query("SELECT pages_".$_GET['fromPage']." FROM pages");
         $messageStock = mysql_fetch_array($querySent);
   }
?>

<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<title><?php print $title." | ". $store_name; ?></title>
<link rel="shortcut icon" href="http://<?php print $www2.$domaineFull;?>/favicon.ico" type="image/x-icon">
<?php
 
if(empty($_SESSION['userInterface'])) $_SESSION['userInterface'] = $colorInter;
         if(!empty($_SESSION['userInterface'])) {
                   print "<link rel='stylesheet' href='css/".$_SESSION['userInterface'].".css' type='text/css'>";
         }
         else {
               if($activerCouleurPerso=="oui") {
                  print "<link rel='stylesheet' href='css/perso.css' type='text/css'>";
               }
               else {
                  print "<link rel='stylesheet' href='css/".$colorInter.".css' type='text/css'>";
               }
         }
if(isset($backgroundImageHeader) AND $backgroundImageHeader!=="noPath") {
	print '<style type="text/css">.backGroundTop {background-color: #none; background-image: url(im/'.$backgroundImageHeader.'); background-repeat: no-repeat; background-position: right top}</style>';
}
include('includes/pagina_f5_js.inc');
?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="<?php print $storeWidth;?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">
 
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<?php 
 
if($header1Display=='oui') {
   print '<tr height="'.$cellTop.'" valign="top" >';
   print '<td>&nbsp;</td>';
   print '</tr>';
   print '<tr valign="top">';
}
else {
   print '<tr valign="top">';
}


if($header2Display=='oui') {
   print "<td colspan='3'>";
   print "<table align='center' width='100%' border='0' cellspacing='0' cellspadding='0'>";
   print "<tr>";
   print "<td class='backgroundHeader2' align='center'>&nbsp;</td>";
   print "</tr>";
   print "</table>";
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

if($menuVisibleTab=="oui") {
   print '<table width="99%" border="0" align="center" border="0" cellspacing="0" cellpadding="0" class="colorBackgroundTableMenuTab">';
   print '<tr>';
   print '<td align="center" valign="middle">&nbsp;</td>';
   print '</tr></table>'; 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
 
if($menuCssVisibleHorizon=="oui") {
   print "<table align='center' border='0' width='99%' cellspacing='0' cellpadding='0' class='tableDynMenuH'><tr><td height='25'>";
   print "&nbsp;";
   print "</td></tr></table>";
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
          <img src="im/accueil.gif" align="TEXTTOP">
          </td>
          <?php 
          }
          if($tableDisplayRight=='oui') {
            print '<td width="340" align="right" valign="middle">';
            print '<div align="right">';
            print "&nbsp;";
            print "</div>";
            print '</td>';
         }
         ?>
          </tr>
          </table>
      <?php }?>
      
</td>
</tr>
<tr valign="top">
<td colspan="3">

    <table width="100%" height="100%" border="0" cellpadding="3" cellspacing="5">
    <tr>

		  <td width="1" valign="top" class="backgroundTDColonneModuleLeft">
                <div class="raised" style="width:<?php print $larg_rub;?>">
                <b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
                <div class="boxcontent">
                
                <table border="0" cellspacing="0" cellpadding="2" class="moduleId">
                <tr>
                <td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleIdTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
                &nbsp;
                </td>
                </tr>
                <tr>
                <td><div><img src='im/zzz.gif' height="1" width="1"></div></td></tr><tr>
                <td align="center">&nbsp;</td>

                </tr>
                </table>
                </div>
                <b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
                </div>
         </td>
         
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
print "<img src='im/zzz_gris.gif' width='6' height='6'>";
?>
</td>
</tr>
</table>
<?php
}
?>


<?php 
if(isset($_GET['do'])) {
     $message = "<p>";
     $message.= "<b>".$messageStock['page_added_title_'.$_GET['lang']]."</b>";
     $message.= "<br><br>";
     $message.= str_replace('\r\n','',$messageStock['page_added_message_'.$_GET['lang']]);
     $message.= "</p>";
      print $message;
}
else {
      print "<p>".str_replace('\r\n','',$messageStock['pages_'.$_GET['fromPage']])."</p>";
}
?>
                  </td>
                  </tr>
                  </table>
     </td>
		  <td width="1" valign="top" class="backgroundTDColonneModuleLeft">
                <div class="raised" style="width:<?php print $larg_rub;?>">
                <b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
                <div class="boxcontent">
                
                <table border="0" cellspacing="0" cellpadding="2" class="moduleId">
                <tr>
                <td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleIdTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
                &nbsp;
                </td>
                </tr>
                <tr>
                <td><div><img src='im/zzz.gif' height="1" width="1"></div></td></tr><tr>
                <td align="center">&nbsp;</td>

                </tr>
                </table>
                </div>
                <b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
                </div>
         </td>
     </tr>
     </table>

</td>
</tr>
</table>

<?php
if(!isset($displayPayments) OR $displayPayments !== 0) {
?>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEBottomPage">
	<tr height="32">
		<td align="center">
        &nbsp;
		</td>
	</tr>
</table>
<?php
}
?>



<?php
if(!isset($displayPayments) OR $displayPayments !== 0) {

if($bannerVisible=="oui" AND $bannerFooter=="oui") {
      print "<img src='im/zzz.gif' width='1' height='5'><br>";
      print "<table width='99%' align='center' border='0' cellspacing='0' cellpadding='5' class='backgroundTDColonneModuleLeft'>";
      print "<tr><td align='center'>";
      print "&nbsp;";
      print "</td></tr></table>";
      if($catFooter=='non') {print "<img src='im/zzz.gif' width='1' height='5'><br>";}
}

if($catFooter=='oui') {

      print "<img src='im/zzz.gif' width='1' height='5'><br>";
      print "<table width='99%' align='center' border='0' cellspacing='0' cellpadding='5' class='backgroundTDColonneModuleLeft'>";
      print "<tr><td align='center'>";
      print "&nbsp;";
      print "</td></tr></table>";
      print "<img src='im/zzz.gif' width='1' height='5'><br>";
}
?>


<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align='left' width='75'>
	<?php print "<a href='infos.php?lang=1&info=5&from=web'><b>&nbsp;</b></a>";?>
	</td>
	<td align="center">
	<?php
if($activeEcom=="oui") {
   print "&nbsp;";
}
else {
    print "<img src='zzz.gif' width='1' height='1'>";
}
?>
 
	</tr>
</table>


<?php
}
?>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>

<?php
}
else {
 
  $querySent = mysql_query("SELECT * FROM newsletter_sent WHERE newsletter_sent_id = '".$_GET['id']."'");
  
  $messageStock = mysql_fetch_array($querySent);
  $newsletterSent = explode("**|||||**",$messageStock['newsletter_sent_message']);
  if($messageStock['newsletter_format']=='text') {
     $newsletterSent[1] = str_replace("\r\n","<br>",$newsletterSent[1]);
  }
  if(isset($newsletterSent[0])) print "<p>".$newsletterSent[0]."</p>";
  if(isset($newsletterSent[1])) print "<p>".$newsletterSent[1]."</p>";
}
?>
