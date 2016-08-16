<?php
session_start();
session_destroy();


include("../configuratie/configuratie.php");

function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}
 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}
if(!isset($_GET['id'])) $_GET['id']="jhajdhjhdlkhja";

            $queryZ = mysql_query("SELECT * FROM users_orders WHERE users_nic = '".$_GET['id']."'");
            $queryZNum = mysql_num_rows($queryZ);
            if($queryZNum > 0) {


            $hoy = date("Y-m-d H:i:s");
 
            $resultZ = mysql_fetch_array($queryZ);
            
 
            include("../includes/lang/lang_".$resultZ['users_lang'].".php");
            
$title = "MONEYBOOKERS";
 
function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src=\"".$image."\" border=\"0\">";    
    }
    else {
       $returnImage = "<img src=\"".$image."\" border=\"0\" width=\"".$wi."\" height=\"".$he."\">";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}
?>
<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title><?php print $title." | ".$store_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../css/<?php print $colorInter;?>.css" type="text/css">
<?php
if(isset($backgroundImageHeader) AND $backgroundImageHeader!=="noPath") {
print '<style type="text/css">.backGroundTop {background-color: #none; background-image: url(../im/'.$backgroundImageHeader.'); background-repeat: no-repeat; background-position: right top}</style>';
}
?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" width="<?php print $storeWidth;?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="backGroundTop" height="100%">
<tr height="<?php print $cellTop;?>" valign="top" >

<?php
 

if(isset($logo) AND $logo!=="noPath") {
     
    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize("../".$logo);
        $logoWidth = $largeurLogo[0];
        $widthMaxLogo = 160;

        if($logoWidth>$widthMaxLogo) {
            $logoRezise = $largeurLogo[0]/$largeurLogo[1];
            $wwww=$widthMaxLogo;
            $hhhh=$widthMaxLogo/$logoRezise;
            $logoWidth = $wwww;
        }
        else {
            $wwww=0;
            $hhhh=0;
            $logoWidth = $largeurLogo[0];
        }
        print detectIm("../".$logo,$wwww,$hhhh);
    print "</td>";
}
else {
    if(isset($logo2) AND $logo2!=="noPath") {
 
    	print "<td valign='middle' align='center'>";
       	if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm("../".$logo2,0,0)."</a>"; else print detectIm("../".$logo2,0,0);
    	print "</td>";
    }
	else {
		print "<td valign='middle' align='center'>";
		print "&nbsp;";
		print "</td>";
	} 
}
?>
</tr>
<tr>   
    <td colspan="3" valign="top">


</td>
</tr>

<tr valign="top">
<td colspan="3" valign="top">
             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td><b><img src="../im/accueil.gif" align="TEXTTOP">&nbsp;<a href="../cataloog.php?lang=<?php print $resultZ['users_lang'];?>" ><?php print strtoupper(HOME);?></a> | MONEYBOOKERS |</b>
               </td>
              </tr>
             </table>


      <table width="80%" border="0" cellpadding="0" cellspacing="5" align="center">
        <tr>
          <td valign="top">


      <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">

              <tr>
                <td valign="top" align="center"><br>
<?php
print "<table border=\"0\" cellpadding=\"10\" cellspacing=\"0\"><tr><td>";
print BONJOUR.",<br><br>";
print VOTRE_PAIEMENT_A."<br><br>";
print "<a href=\"cataloog.php\"><img src='../im/fleche_menu2.gif' border='0' align='absmiddle'>&nbsp;<b>".CLOSE_DEVIS."</b></a><br><br>";
print "---<br>";
print LE_SERVICE_CLIENT;

print "<p align='left'>";
print "<b>".$store_company."</b><br>";
print $address_street."<br>";
print $address_cp." - ".$address_city."<br>";
print $address_country;
if(!empty($address_autre)) print "<br>".$address_autre;
if(!empty($tel)) print "<br>".TELEPHONE." : ".$tel;
if(!empty($fax)) print "<br>".FAX." : ".$fax;
print "</p>";

print "</td></tr></table>";
?>
                </td>
              </tr>
            </table>
            
          </td>
        </tr>
      </table>
</td>
</tr>
<tr>
<td colspan="3" valign = "bottom">

<table width="99%" border="0" align="center" cellpadding="5" cellspacing="0" class="TABLEBottomPage">
<tr height="32">
<td align="center">
<table border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
<td>
|&nbsp;<a href="../cataloog.php?lang=<?php print $resultZ['users_lang'];?>"><?php print HOME;?></a>&nbsp;|&nbsp;<a href="../infos.php?lang=<?php print $resultZ['users_lang'];?>&info=5"><?php print NOUS_CONTACTER;?></a>&nbsp;|
</td>
</tr>
</table>
</td>
</tr>
</table>


<br>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align='right' valign='bottom'>Powered by <a href="http://www.ecomshop.be" target="_blank"><b>Webhouse.be</b></a></td>
	</tr>
</table>

</td>
</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</td></tr></table>
</body>
</html>
<?
}
else {
   header("Location: ../cataloog.php");
}
?>

