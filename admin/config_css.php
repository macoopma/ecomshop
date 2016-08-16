<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php
if(!isset($_GET['action'])) {
    print '<SCRIPT type="text/javascript" SRC="ColorPicker2.js"></SCRIPT>';
    print '<SCRIPT type="text/javascript">
            var cp = new ColorPicker("window");
var cp2 = new ColorPicker(); 
            </SCRIPT>';
}
?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>


<form action="config_css.php?action=write" method="POST" name="toto_1" enctype='multipart/form-data'>
<?php
if(!isset($_GET['action'])) {
     $query = mysql_query("SELECT my_css FROM admin");
     $result = mysql_fetch_array($query);
     $aa = $result['my_css'];
     $boum = explode("+",$aa);
 
     if(!isset($boum[48])) $boum[48]="";
     if(!isset($boum[49])) $boum[49]=10;
?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td colspan="2">
      <table align="center" width="100%" border="0" cellspacing="1" cellpadding="5" class="TABLE">


<tr bgcolor="#FFFFFF">
<td colspan="3"><?php print A6;?> <input type="text" name="a10000" size="2" maxlength="2" value="<?php print str_replace("#","",$boum[49]);?>">&nbsp;&nbsp;&nbsp;px</td>
</tr>

<tr bgcolor="#FFFFFF">
<td colspan="3">

<?php print AIMAGE;?>
			<div style='background:#FFFFFF; border:1px #CCCCCC solid; padding:5px;'>
			&nbsp;&bull;&nbsp;<?php print UPLOAD;?>&nbsp;&nbsp;&nbsp;<input type='file' name='uploadBackgroundImage' class='vullen' size='40'>
			&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='<?php print VIDER;?>'>
			<img src='im/zzz.gif' width='1' height='5'>
			<input type="text" size="40" name="a50000" value="<?php print str_replace("#","",$boum[48]);?>">&nbsp;(.gif,.jpg,.png)
			</div>




</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><?php print A7;?></td><td><input type="text" name="a21" size="11" maxlength="7" class="vullen" class="vullen" value="<?php print $boum[1];?>"></td>
<td bgcolor="<?php print $boum[1];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a21,'pick4');return false;" NAME="pick4" ID="pick4"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td><?php print A6;?></td><td><input type="text" name="a11" size="11" maxlength="7" class="vullen" value="<?php print $boum[0];?>"></td>
<td bgcolor="<?php print $boum[0];?>">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a11,'pick3');return false;" NAME="pick3" ID="pick3"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td><?php print COLOR_LINKS;?></td><td><input type="text" name="a31" size="11" maxlength="7" class="vullen" value="<?php print $boum[2];?>"></td>
<td bgcolor="<?php print $boum[2];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a31,'pick5');return false;" NAME="pick5" ID="pick5"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td>
<?php print A9;?></td><td><input type="text" name="a81" size="11" maxlength="7" class="vullen" value="<?php print $boum[3];?>"></td>
<td bgcolor="<?php print $boum[3];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a81,'pick10');return false;" NAME="pick10" ID="pick10"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td>
<?php print A9GRIS;?></td><td><input type="text" name="a91" size="11" maxlength="7" class="vullen" value="<?php print $boum[4];?>"></td>
<td bgcolor="<?php print $boum[4];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a91,'pick11');return false;" NAME="pick11" ID="pick11"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/1.gif" target="_blank"><?php print A15;?></a></td><td><input type="text" name="a151" size="11" maxlength="7" class="vullen" value="<?php print $boum[6];?>"></td>
<td bgcolor="<?php print $boum[6];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a151,'pick17');return false;" NAME="pick17" ID="pick17"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/1.gif" target="_blank"><?php print A16;?></a></td><td><input type="text" name="a161" size="11" maxlength="7" class="vullen" value="<?php print $boum[7];?>"></td>
<td bgcolor="<?php print $boum[7];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a161,'pick18');return false;" NAME="pick18" ID="pick18"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/2.gif" target="_blank"><?php print A17;?></a></td><td><input type="text" name="a171" size="11" maxlength="7" class="vullen" value="<?php print $boum[8];?>"></td>
<td bgcolor="<?php print $boum[8];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a171,'pick19');return false;" NAME="pick19" ID="pick19"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/3.gif" target="_blank"><?php print A17A;?></a></td><td><input type="text" name="a171a" size="11" maxlength="7" class="vullen" value="<?php print $boum[9];?>"></td>
<td bgcolor="<?php print $boum[9];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a171a,'pick75');return false;" NAME="pick75" ID="pick75"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/3.gif" target="_blank"><?php print A41;?></a></td><td><input type="text" name="a4113" size="11" maxlength="7" class="vullen" value="<?php print $boum[29];?>"></td>
<td bgcolor="<?php print $boum[29];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4113,'pick52');return false;" NAME="pick52" ID="pick52"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/3.gif" target="_blank"><?php print A42A;?></a></td><td><input type="text" name="a4114u" size="11" maxlength="7" class="vullen" value="<?php print $boum[38];?>"></td>
<td bgcolor="<?php print $boum[38];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4114u,'pick53u');return false;" NAME="pick53u" ID="pick53u"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/4.gif" target="_blank"><?php print A4207;?></a></td><td><input type="text" name="a41505" size="11" maxlength="7" class="vullen" value="<?php print $boum[45];?>"></td>
<td bgcolor="<?php print $boum[45];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41505,'pick500202');return false;" NAME="pick500202" ID="pick500202"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/5.gif" target="_blank"><?php print A18;?></a></td><td><input type="text" name="a181" size="11" maxlength="7" class="vullen" value="<?php print $boum[10];?>"></td>
<td bgcolor="<?php print $boum[10];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a181,'pick20');return false;" NAME="pick20" ID="pick20"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/5.gif" target="_blank"><?php print A18A;?></a></td><td><input type="text" name="a191" size="11" maxlength="7" class="vullen" value="<?php print $boum[11];?>"></td>
<td bgcolor="<?php print $boum[11];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a191,'pick21');return false;" NAME="pick21" ID="pick21"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/6.gif" target="_blank"><?php print A14;?></a></td><td><input type="text" name="a141" size="11" maxlength="7" class="vullen" value="<?php print $boum[5];?>"></td>
<td bgcolor="<?php print $boum[5];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a141,'pick16');return false;" NAME="pick16" ID="pick16"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/7.gif" target="_blank"><?php print A19;?></a></td><td><input type="text" name="a201" size="11" maxlength="7" class="vullen" value="<?php print $boum[12];?>"></td>
<td bgcolor="<?php print $boum[12];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a201,'pick22');return false;" NAME="pick22" ID="pick22"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/7.gif" target="_blank"><?php print A19A;?></a></td><td><input type="text" name="a281" size="11" maxlength="7" class="vullen" value="<?php print $boum[17];?>"></td>
<td bgcolor="<?php print $boum[17];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a281,'pick30');return false;" NAME="pick30" ID="pick30"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td><a href="im/configcss/8.gif" target="_blank"><?php print COLOR_BORD_ART;?></a></td><td><input type="text" name="a211" size="11" maxlength="7" class="vullen" value="<?php print $boum[13];?>"></td>
<td bgcolor="<?php print $boum[13];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a211,'pick23');return false;" NAME="pick23" ID="pick23"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/9.gif" target="_blank"><?php print A20;?></a></td><td><input type="text" name="a221" size="11" maxlength="7" class="vullen" value="<?php print $boum[14];?>"></td>
<td bgcolor="<?php print $boum[14];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a221,'pick24');return false;" NAME="pick24" ID="pick24"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/10.gif" target="_blank"><?php print A21;?></a></td><td><input type="text" name="a261" size="11" maxlength="7" class="vullen" value="<?php print $boum[15];?>"></td>
<td bgcolor="<?php print $boum[15];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a261,'pick28');return false;" NAME="pick28" ID="pick28"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/10.gif" target="_blank"><?php print A21A;?></a></td><td><input type="text" name="a271" size="11" maxlength="7" class="vullen" value="<?php print $boum[16];?>"></td>
<td bgcolor="<?php print $boum[16];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a271,'pick29');return false;" NAME="pick29" ID="pick29"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td><a href="im/configcss/11.gif" target="_blank"><?php print A22A;?></a></td><td><input type="text" name="a291" size="11" maxlength="7" class="vullen" value="<?php print $boum[18];?>"></td>
<td bgcolor="<?php print $boum[18];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a291,'pick31');return false;" NAME="pick31" ID="pick31"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/12.gif" target="_blank"><?php print A23;?></a></td><td><input type="text" name="a301" size="11" maxlength="7" class="vullen" value="<?php print $boum[19];?>"></td>
<td bgcolor="<?php print $boum[19];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a301,'pick32');return false;" NAME="pick32" ID="pick32"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/13.gif" target="_blank"><?php print A25;?></a></td><td><input type="text" name="a341" size="11" maxlength="7" class="vullen" value="<?php print $boum[20];?>"></td>
<td bgcolor="<?php print $boum[20];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a341,'pick37');return false;" NAME="pick37" ID="pick37"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/13.gif" target="_blank"><?php print A25A;?></a></td><td><input type="text" name="a351" size="11" maxlength="7" class="vullen" value="<?php print $boum[21];?>"></td>
<td bgcolor="<?php print $boum[21];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a351,'pick38');return false;" NAME="pick38" ID="pick38"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/14.gif" target="_blank"><?php print A26;?></a></td><td><input type="text" name="a361" size="11" maxlength="7" class="vullen" value="<?php print $boum[22];?>"></td>
<td bgcolor="<?php print $boum[22];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a361,'pick39');return false;" NAME="pick39" ID="pick39"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/14.gif" target="_blank"><?php print A26A;?></a></td><td><input type="text" name="a371" size="11" maxlength="7" class="vullen" value="<?php print $boum[23];?>"></td>
<td bgcolor="<?php print $boum[23];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a371,'pick40');return false;" NAME="pick40" ID="pick40"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/15.gif" target="_blank"><?php print A30;?></a></td><td><input type="text" name="a381" size="11" maxlength="7" class="vullen" value="<?php print $boum[24];?>"></td>
<td bgcolor="<?php print $boum[24];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a381,'pick41');return false;" NAME="pick41" ID="pick41"><img src="im/color.gif" border="0"></A></div>
</td>
</tr>

<tr>
<td><a href="im/configcss/15.gif" target="_blank"><?php print A300;?></a></td><td><input type="text" name="a431" size="11" maxlength="7" class="vullen" value="<?php print $boum[25];?>"></td>
<td bgcolor="<?php print $boum[25];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a431,'pick46');return false;" NAME="pick46" ID="pick46"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/16.gif" target="_blank"><?php print A33;?></a></td><td><input type="text" name="a441" size="11" maxlength="7" class="vullen" value="<?php print $boum[26];?>"></td>
<td bgcolor="<?php print $boum[26];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a441,'pick47');return false;" NAME="pick47" ID="pick47"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/16.gif" target="_blank"><?php print A33A;?></a></td><td><input type="text" name="a451" size="11" maxlength="7" class="vullen" value="<?php print $boum[27];?>"></td>
<td bgcolor="<?php print $boum[27];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a451,'pick48');return false;" NAME="pick48" ID="pick48"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/17.gif" target="_blank"><?php print A40;?></a></td><td><input type="text" name="a4112" size="11" maxlength="7" class="vullen" value="<?php print $boum[28];?>"></td>
<td bgcolor="<?php print $boum[28];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4112,'pick51');return false;" NAME="pick51" ID="pick51"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr>
<td><a href="im/configcss/18.gif" target="_blank"><?php print A50;?></a></td><td><input type="text" name="a4123" size="11" maxlength="7" class="vullen" value="<?php print $boum[30];?>"></td>
<td bgcolor="<?php print $boum[30];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4123,'pick61');return false;" NAME="pick61" ID="pick61"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/19.gif" target="_blank"><?php print A50A;?></a></td><td><input type="text" name="a4124" size="11" maxlength="7" class="vullen" value="<?php print $boum[31];?>"></td>
<td bgcolor="<?php print $boum[31];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4124,'pick62');return false;" NAME="pick62" ID="pick62"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr>
<td><a href="im/configcss/19.gif" target="_blank"><?php print A51;?></a></td><td><input type="text" name="a4125" size="11" maxlength="7" class="vullen" value="<?php print $boum[32];?>"></td>
<td bgcolor="<?php print $boum[32];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4125,'pick63');return false;" NAME="pick63" ID="pick63"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><?php print A54;?></td><td><input type="text" name="a4129" size="11" maxlength="7" class="vullen" value="<?php print $boum[33];?>"></td>
<td bgcolor="<?php print $boum[33];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4129,'pick67');return false;" NAME="pick67" ID="pick67"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><?php print A54A;?></td><td><input type="text" name="a4130" size="11" maxlength="7" class="vullen" value="<?php print $boum[34];?>"></td>
<td bgcolor="<?php print $boum[34];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4130,'pick68');return false;" NAME="pick68" ID="pick68"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/20.gif" target="_blank"><?php print A56;?></a></td><td><input type="text" name="a4133" size="11" maxlength="7" class="vullen" value="<?php print $boum[36];?>"></td>
<td bgcolor="<?php print $boum[36];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4133,'pick71');return false;" NAME="pick71" ID="pick71"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/20.gif" target="_blank"><?php print A56A;?></a></td><td><input type="text" name="a4134" size="11" maxlength="7" class="vullen" value="<?php print $boum[37];?>"></td>
<td bgcolor="<?php print $boum[37];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4134,'pick72');return false;" NAME="pick72" ID="pick72"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/21.gif" target="_blank"><?php print A4200;?></a></td><td><input type="text" name="a42000" size="11" maxlength="7" class="vullen" value="<?php print $boum[39];?>"></td>
<td bgcolor="<?php print $boum[39];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a42000,'pick501');return false;" NAME="pick501" ID="pick501"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/22.gif" target="_blank"><?php print A4204;?></a></td><td><input type="text" name="a41501" size="11" maxlength="7" class="vullen" value="<?php print $boum[41];?>"></td>
<td bgcolor="<?php print $boum[41];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41501,'pick5000');return false;" NAME="pick5000" ID="pick5000"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/23.gif" target="_blank"><?php print A4205;?></a></td><td><input type="text" name="a41502" size="11" maxlength="7" class="vullen" value="<?php print $boum[42];?>"></td>
<td bgcolor="<?php print $boum[42];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41502,'pick5001');return false;" NAME="pick5001" ID="pick5001"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/24.gif" target="_blank"><?php print A4201;?></a></td><td><input type="text" name="a41500" size="11" maxlength="7" class="vullen" value="<?php print $boum[40];?>"></td>
<td bgcolor="<?php print $boum[40];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41500,'pick500');return false;" NAME="pick500" ID="pick500"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/25.gif" target="_blank"><?php print A4202;?></a></td><td><input type="text" name="a41504" size="11" maxlength="7" class="vullen" value="<?php print $boum[44];?>"></td>
<td bgcolor="<?php print $boum[44];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41504,'pick500201');return false;" NAME="pick500201" ID="pick500201"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/26.gif" target="_blank"><?php print A4203;?></a></td><td><input type="text" name="a41503" size="11" maxlength="7" class="vullen" value="<?php print $boum[43];?>"></td>
<td bgcolor="<?php print $boum[43];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41503,'pick50020');return false;" NAME="pick50020" ID="pick50020"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/27.gif" target="_blank"><?php print A4206;?></a></td><td><input type="text" name="a4132" size="11" maxlength="7" class="vullen" value="<?php print $boum[35];?>"></td>
<td bgcolor="<?php print $boum[35];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a4132,'pick70');return false;" NAME="pick70" ID="pick70"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/28.gif" target="_blank"><?php print AAAW;?></a></td><td><input type="text" name="a41506" size="11" maxlength="7" class="vullen" value="<?php print $boum[46];?>"></td>
<td bgcolor="<?php print $boum[46];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41506,'pick5002028');return false;" NAME="pick5002028" ID="pick5002028"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td><a href="im/configcss/29.gif" target="_blank"><?php print AAAWW;?></a></td><td><input type="text" name="a41507" size="11" maxlength="7" class="vullen" value="<?php print $boum[47];?>"></td>
<td bgcolor="<?php print $boum[47];?>" width="75">
<div align="center"><A HREF="#" onClick="cp2.select(document.forms['toto_1'].a41507,'pick5002029');return false;" NAME="pick5002029" ID="pick5002029"><img src="im/color.gif" border="0"></A></div></td>
</tr>

<tr bgcolor="#FFFFFF">
<td align='left' colspan='3'><input type="submit" value="<?php print A3;?>" class="knop"></td>
</tr>

</table>
<SCRIPT type="text/javascript">cp.writeDiv()</SCRIPT>

    </td>
  </tr>
  </table>


<table align="center" width="700" border="0" cellspacing="0" cellpadding="10">
  <tr>
    <td><?php print A2;?></td>
  </tr>
</table>
<br><br><br>

<?php } else if($_GET['action'] == "write") {
// # check
foreach($_POST AS $key => $value) {
	$_POST[$key] = (preg_match('/^#/', $_POST[$key]))? $value : "#".$value;
}
$_POST['a10000'] = str_replace('#','',$_POST['a10000']);
$_POST['a10000'] = str_replace('-','',$_POST['a10000']);
if(!isset($_POST['a10000']) OR $_POST['a10000']=="" OR !is_numeric($_POST['a10000'])) $_POST['a10000']=10;
$taille1 = $_POST['a10000']+2;
$taille2 = $_POST['a10000']-1;
	// ------------------------
	// Upload background image 
	// ------------------------
	if(isset($_FILES["uploadBackgroundImage"]["name"]) AND !empty($_FILES["uploadBackgroundImage"]["name"])) {
		//nom du fichier choisi:
		$nomFichier1    =  $_FILES["uploadBackgroundImage"]["name"];
		//nom temporaire sur le serveur:
		$nomTemporaire1 = $_FILES["uploadBackgroundImage"]["tmp_name"];
		//type du fichier choisi:
		$typeFichier1   = $_FILES["uploadBackgroundImage"]["type"];
		//poids en octets du fichier choisit:
		$poidsFichier1  = $_FILES["uploadBackgroundImage"]["size"];
		//code de l'erreur si jamais il y en a une:
		$codeErreur1    = $_FILES["uploadBackgroundImage"]["error"];
		//chemin qui mène au dossier qui va contenir les fichiers uplaod:
		$chemin1 = "../im/";
		if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier1)) {
			if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
				$_POST['a50000'] = str_replace("../im/","",$chemin1).$nomFichier1;
				$messageLiv = "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</div>";
			}
			else {
				$_POST['a50000'] = "";
				$messageLiv = "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
			}
		}
		else {
			$_POST['a50000'] = "";
			$messageLiv = "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</div>";
		}
	}







$config="FONT             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$_POST['a10000']."px;  COLOR: ".$_POST['a11']."}\r\n";
$config.="TD               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$_POST['a10000']."px;  COLOR: ".$_POST['a11']."}\r\n";
$config.="TR               {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$_POST['a10000']."px;  COLOR: ".$_POST['a11']."}\r\n";
$config.="BODY             {\r\n";
$config.="FONT-FAMILY: Verdana,Helvetica;\r\n"; 
   $config.="FONT-SIZE: ".$_POST['a10000']."px;\r\n"; 
   $config.="BACKGROUND-COLOR: ".$_POST['a21'].";\r\n";
   if($_POST['a50000']!=="#") {
   		$config.="BACKGROUND-IMAGE: url(../im/".str_replace('#','',$_POST['a50000']).");\r\n";
   		$config.="BACKGROUND-POSITION: top center;\r\n";
	}
   
   $config.="SCROLLBAR-FACE-COLOR: ".$_POST['a181'].";\r\n"; 
   $config.="SCROLLBAR-HIGHLIGHT-COLOR: #FFFFFF;\r\n"; 
   $config.="SCROLLBAR-SHADOW-COLOR: #FFFFFF;\r\n"; 
   $config.="SCROLLBAR-3DLIGHT-COLOR: #FFFFFF;\r\n"; 
   $config.="SCROLLBAR-ARROW-COLOR: ".$_POST['a11'].";\r\n"; 
   $config.="SCROLLBAR-TRACK-COLOR: #F6F6EB;\r\n"; 
   $config.="SCROLLBAR-DARKSHADOW-COLOR: ".$_POST['a11']." }\r\n";
$config.="P                {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$_POST['a10000']."px}\r\n";
$config.="DIV              {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$_POST['a10000']."px}\r\n";
$config.="INPUT            {BACKGROUND-COLOR: #f1f1f1; border:2px #CCCCCC solid; FONT-SIZE:".$_POST['a10000']."px; FONT-FAMILY:Verdana,Helvetica; }\r\n";
$config.="TEXTAREA         {BACKGROUND-COLOR: #f1f1f1; BORDER:2px ".$_POST['a211']." solid; FONT-SIZE:".$_POST['a10000']."px; FONT-FAMILY:Verdana,Helvetica;}\r\n";
$config.="SELECT           {BACKGROUND-COLOR: #f1f1f1; BORDER:2px ".$_POST['a211']." solid; FONT-SIZE: ".$taille2."px; FONT-FAMILY: Verdana,Helvetica;}\r\n";
$config.="FORM             {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE:".$_POST['a10000']."px}\r\n";
$config.="HR 			   {COLOR ".$_POST['a4129']."; BORDER:0; BACKGROUND-COLOR: ".$_POST['a4130']."; HEIGHT:1}\r\n";
$config.="OPTION           {BACKGROUND-COLOR: #f1f1f1;}\r\n";
$config.="A:link           {BACKGROUND: none; COLOR: ".$_POST['a31']."; FONT-SIZE: ".$_POST['a10000']."px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}\r\n";
$config.="A:active         {BACKGROUND: none; COLOR: ".$_POST['a31']."; FONT-SIZE: ".$_POST['a10000']."px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}\r\n";
$config.="A:visited        {BACKGROUND: none; COLOR: ".$_POST['a31']."; FONT-SIZE: ".$_POST['a10000']."px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: none}\r\n";
$config.="A:hover          {BACKGROUND: none; COLOR: ".$_POST['a31']."; FONT-SIZE: ".$_POST['a10000']."px; FONT-FAMILY: Verdana, Helvetica; TEXT-DECORATION: underline }\r\n\r\n";

$config.="#tab A{\r\n";
$config.="background-color: ".$_POST['a42000'].";\r\n"; 
	$config.="border-left:1px solid ".$_POST['a4132'].";\r\n";
	$config.="border-top:1px solid ".$_POST['a4132'].";\r\n";
	$config.="border-right:1px solid ".$_POST['a4132'].";\r\n";
	$config.="border-bottom:1px solid ".$_POST['a4132'].";\r\n";
$config.="display: block;\r\n";
$config.="text-decoration: none;\r\n";
$config.="font: ".$taille2."px Verdana,Helvetica;\r\n";
$config.="color: ".$_POST['a41502'].";\r\n";
$config.="height:13px;\r\n";
$config.="float: left;\r\n";
$config.="margin-right: 0px;\r\n";
$config.="margin-left: 0px;\r\n";
$config.="margin-bottom: 0px;\r\n";
$config.="padding-top: 5px;\r\n";
$config.="padding-bottom: 5px;\r\n";
$config.="padding-right: 7px;\r\n";
$config.="padding-left: 7px;\r\n";
$config.="/*background-image: url(../im/background/menutab_background1.gif);*/\r\n";
$config.="text-align: center;\r\n";
$config.="white-space:nowrap;\r\n";
$config.="}\r\n";
$config.="#tab A:hover, #tab A.current{\r\n";
$config.="background-color: #FFCC00;\r\n";
$config.="border-top:1px solid ".$_POST['a42000'].";\r\n";
$config.="background: url(../im/background/menutab_background1.gif) no-repeat bottom center ".$_POST['a41503'].";\r\n";
$config.="color: ".$_POST['a11'].";\r\n";
$config.="}\r\n";
$config.="#tab A.here:visited {\r\n";
  $config.="background: url(../im/background/menutab_background3.gif) no-repeat bottom center ".$_POST['a41500'].";\r\n";
  $config.="border-top:1px solid ".$_POST['a42000'].";\r\n";
  $config.="color: ".$_POST['a41504'].";\r\n";
$config.="}\r\n\r\n";

$config.=".borderHeightBottomMenuTabYes      {BACKGROUND-COLOR: ".$_POST['a42000']."; border:0px #d8d8c4; border-right-style:solid; border-left-style:solid; border-top-style:solid; border-bottom-style:none;}\r\n\r\n";


$config.=".titre                          {BACKGROUND: none; COLOR: ".$_POST['a11']."; FONT-SIZE: ".$_POST['a10000']."px; FONT-WEIGHT: bold; FONT-FAMILY: Verdana, Helvetica; letter-spacing: 2px}\r\n";
$config.=".tiny                           {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: ".$taille2."px}\r\n";
$config.=".fontrouge                      {COLOR: ".$_POST['a81']."}\r\n";
$config.=".FontGris                       {COLOR: ".$_POST['a91']."}\r\n";
$config.=".fontMenuCategory               {COLOR: ".$_POST['a4113']."}\r\n";
$config.=".fontMenuSubCategory            {COLOR: ".$_POST['a4114u']."}\r\n";
$config.=".fontMenuSubCategorySelected    {COLOR: ".$_POST['a81']."; FONT-WEIGHT: bold}\r\n\r\n";

$config.=".PromoFont             {COLOR: ".$_POST['a11']."; FONT-SIZE: ".$taille1."px;}\r\n";
$config.=".PromoFontColorNumber  {COLOR: ".$_POST['a81']."; FONT-SIZE: ".$taille1."px;}\r\n";
$config.=".FontColorTotalPrice   {COLOR: ".$_POST['a81']."; FONT-SIZE: ".$taille1."px;}\r\n\r\n";

$config.=".caddieInfoVide    	 {BACKGROUND-COLOR: ".$_POST['a181'].";}\r\n";
$config.=".caddieBoxVide         {BACKGROUND-COLOR: ".$_POST['a181'].";}\r\n";
$config.=".caddie-fond   		 {BACKGROUND-COLOR: #none; border-width:0px; padding:0px; }\r\n";
$config.=".caddie-box            {BACKGROUND-COLOR: ".$_POST['a141']."}\r\n";			
$config.=".cartItem			     {BACKGROUND-COLOR: ".$_POST['a4123']."; border-width:1px; border-color:".$_POST['a4124']."; border-top-style:dotted; border-bottom-style:none; border-right-style:none; border-left-style:none;}\r\n";
$config.=".cartItemFont          {COLOR: ".$_POST['a4125']."}\r\n";
$config.=".caddie-art-tot        {BACKGROUND-COLOR: ".$_POST['a151']."; border:".$_POST['a161']." 1px solid;}\r\n";
$config.=".TABLEInfoCaddie       {BACKGROUND-COLOR: ".$_POST['a141']."; border:2px ".$_POST['a191']." solid; border-bottom:0px}\r\n\r\n";

$config.=".moduleMenu          {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; border-color:#CC9933; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:solid; padding:0px; }\r\n";
$config.=".moduleMenuVertical  {BACKGROUND-COLOR: #none; padding:0px; }\r\n";
$config.=".moduleInfo          {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleInterface     {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleLangue        {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleMessage       {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleNavigate      {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleNews          {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".modulePromo         {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleQuick         {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleSearch        {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleSubscribe     {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleTop10         {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleConverter     {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleAffiliate     {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleId            {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleLastView      {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleRss           {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n";
$config.=".moduleDevis         {BACKGROUND-COLOR: ".$_POST['a181']."; border-width:0px; padding:0px;}\r\n\r\n";

$config.=".moduleIdTitre		          {/*none*/}\r\n";
$config.=".moduleCaddieTitre		      {/*none*/}\r\n";
$config.=".moduleMenuTitre                {/*none*/}\r\n";
$config.=".moduleInfoTitre                {/*none*/}\r\n";
$config.=".moduleInterfaceTitre           {/*none*/}\r\n";
$config.=".moduleLangueTitre      	      {/*none*/}\r\n";
$config.=".moduleMessageTitre             {/*none*/}\r\n";
$config.=".moduleNavigateTitre            {/*none*/}\r\n";
$config.=".moduleNewsTitre                {/*none*/}\r\n";
$config.=".modulePromoTitre               {/*none*/}\r\n";
$config.=".moduleQuickTitre               {/*none*/}\r\n";
$config.=".moduleSearchTitre              {/*none*/}\r\n";
$config.=".moduleSubscribeTitre           {/*none*/}\r\n";
$config.=".moduleTop10Titre               {/*none*/}\r\n";
$config.=".moduleConverterTitre           {/*none*/}\r\n";
$config.=".moduleAffiliateTitre           {/*none*/}\r\n";
$config.=".moduleLastViewTitre            {/*none*/}\r\n";
$config.=".moduleRssTitre                 {/*none*/}\r\n";
$config.=".moduleDevisTitre               {/*none*/}\r\n\r\n";

$config.=".TABLEBorderDotted                      {BACKGROUND-COLOR: ".$_POST['a181']."; border:1px ".$_POST['a351']." solid }\r\n";
$config.=".backgroundCategorySelected             {BACKGROUND-COLOR: ".$_POST['a171']."}\r\n";
$config.=".backgroundMenuSousCategory             {BACKGROUND-COLOR: ".$_POST['a171a']."}\r\n";
$config.=".TABLEMenuPathCenter                    {BACKGROUND-COLOR: ".$_POST['a221']."; FONT-WEIGHT:bold; border:1px ".$_POST['a211']." dotted; padding:3px;}\r\n";
$config.=".TABLEMenuPathTopPageMenuH              {BACKGROUND-COLOR: ".$_POST['a221']."; border:2px ".$_POST['a191']."; border-right-style:solid; border-left-style:solid; border-top-style:none; border-bottom-style:solid}\r\n";
$config.=".TABLEMenuPathTopPage                   {BACKGROUND-COLOR: ".$_POST['a221']."; border-width:2px; border-color:".$_POST['a191']."; border-left-style:solid; border-right-style:solid; border-bottom-style:solid; border-top-style:none}\r\n";
$config.=".TABLEMenuPathTopPageMenuTabOff         {BACKGROUND-COLOR: ".$_POST['a221']."; border-width:2px; border-color:".$_POST['a191']."; border-left-style:solid; border-right-style:solid; border-bottom-style:solid; border-top-style:solid}\r\n";
$config.=".TABLEBottomPage                        {BACKGROUND-COLOR: ".$_POST['a221']."; border:3px ".$_POST['a191']." solid}\r\n";
$config.=".TABLEPageCentreProducts                {BACKGROUND-COLOR: ".$_POST['a261']."; border:0px ".$_POST['a271']." solid }\r\n";
$config.=".TABLESortByCentre                      {BACKGROUND-COLOR: ".$_POST['a281']."; border:1px ".$_POST['a291']." solid}\r\n";
$config.=".TABLEPromoBannerTop                    {BACKGROUND-COLOR: ".$_POST['a301']."; border:3px ".$_POST['a21']." solid; padding:10px;}\r\n";
$config.=".TABLE1                                 {BACKGROUND-COLOR: ".$_POST['a341']."; border:2px ".$_POST['a351']." solid }\r\n";
$config.=".TABLEBoxUpdateCart                     {BACKGROUND-COLOR: ".$_POST['a361']."; border:2px ".$_POST['a371']." solid }\r\n";
$config.=".TABLEBoxesProductsDisplayedCentrePage  {BACKGROUND-COLOR: ".$_POST['a221']."; border:3px ".$_POST['a211']." solid}\r\n";
$config.=".TABLEBoxProductsDisplayedTop           {BACKGROUND-COLOR: ".$_POST['a381']."; border:0px; padding:4px}\r\n";
$config.=".TABLEBoxProductsDisplayedMiddle        {BACKGROUND-COLOR: ".$_POST['a381']."; border:0px;}\r\n";
$config.=".TABLEBoxProductsDisplayedMiddlePrice   {BACKGROUND-COLOR: ".$_POST['a381']."; border:0px; padding:4px}\r\n";
$config.=".TABLEBoxesProductsDisplayedBottom      {BACKGROUND-COLOR: ".$_POST['a381']."; border-width:0px; border-color:#CC9933; border-left-style:none; border-right-style:none; border-bottom-style:none; border-top-style:solid}\r\n";
$config.=".TABLETitreProductDescription           {BACKGROUND-COLOR: ".$_POST['a181']."; border:2px ".$_POST['a431']." solid; padding-top: 5px; padding-bottom: 5px}\r\n";
$config.=".TDTableListLine1                       {BACKGROUND-COLOR: ".$_POST['a4133']."}\r\n";
$config.=".TDTableListLine2                       {BACKGROUND-COLOR: ".$_POST['a4134']."}\r\n";
$config.=".TABLESousMenuPageCategory              {BACKGROUND-COLOR: ".$_POST['a441']."; border:2px ".$_POST['a451']." solid}\r\n";
$config.=".TABLETopTitle                          {BACKGROUND-COLOR: ".$_POST['a4112']."; border:1px ".$_POST['a11']." solid }\r\n";
$config.=".backgroundTDColonneModuleLeft          {BACKGROUND-COLOR: ".$_POST['a201']."; border:0px ".$_POST['a281']." solid;}\r\n";
$config.=".backgroundTDColonneModuleRight         {BACKGROUND-COLOR: ".$_POST['a201']."; border:0px ".$_POST['a281']." solid;}\r\n";
$config.=".TABLEBackgroundBoutiqueCentre          {BACKGROUND-COLOR: ".$_POST['a21']."; border:0px;}\r\n";
$config.=".TABLEPromoNewsBottomPage               {BACKGROUND-COLOR: ".$_POST['a441']."; border:2px ".$_POST['a451']." solid; padding:5px;}\r\n";
$config.=".TABLEPaymentProcess                    {BACKGROUND-COLOR: #F1F1F1; border:1px #CCCCCC solid}\r\n";
$config.=".TABLEPaymentProcessSelected            {BACKGROUND-COLOR: ".$_POST['a361'].";}\r\n";
$config.=".darkBackground                         {BACKGROUND-COLOR: ".$_POST['a191']."; color:#FFFFFF; border:0px #FAFAFA solid}\r\n";
$config.=".styleAlert                             {padding:5px; border:1px #FF0000 dotted; FONT-WEIGHT:bold}\r\n";
$config.=".tablesFooter                           {BACKGROUND-COLOR: ".$_POST['a41506']."; border: 1px ".$_POST['a41507']." solid }\r\n\r\n";

$config.="OPTION.grey{background-color:#CCCCCC; color:#FFFFFF}\r\n";
$config.="OPTION.black{background-color:#000000; color:#CCCCCC}\r\n";
$config.="OPTION.pink{background-color:#FFCCCC; color:#000000}\r\n";
$config.="OPTION.yellow{background-color:#FFDC37; color:#000000}\r\n";
$config.="OPTION.white{background-color:#FFFFFF; color:#000000}\r\n";
$config.="OPTION.Blue{background-color:#0000FF; color:#FFFFFF}\r\n";
$config.="OPTION.red{background-color:#CC0000; color:#FFFFFF}\r\n";
$config.="OPTION.grey2{background-color:#F1F1F1; color:#FF0000}\r\n\r\n";

$config.=".backGroundTop                            {background-color: ".$_POST['a21'].";\r\n";
$config.="                                           /*background-image: url(../im/background/bannerTop2.gif);*/\r\n";
$config.="                                           background-repeat: no-repeat;\r\n";
$config.="                                           background-position: right top\r\n";
$config.="                                           }\r\n";
$config.=".optionCaddieTop                {BACKGROUND-COLOR: ".$_POST['a221']."; border-width:0px; border-color:".$_POST['a81']."; border-top-style:solid; border-left-style:none; border-right-style:solid; border-bottom-style:none; padding:0px}\r\n";
$config.=".optionCaddieBottom             {BACKGROUND-COLOR: #FFFFFF; border:2px #CCCCCC solid}\r\n";
$config.=".border                         {border:1px #CCCCCC solid}\r\n";
$config.=".borderLeft                     {background-color: none}\r\n";
$config.=".colorBackgroundTableMenuTab    {BACKGROUND-COLOR: ".$_POST['a42000']."; border-width:1px; border-color:".$_POST['a41501']."; border-top-style:solid; border-left-style:solid; border-right-style:solid; border-bottom-style:none;}\r\n";
$config.=".dotMenu                        {/*none*/)}\r\n\r\n";


$config.="/* Paginatie */\r\n";
$config.="div.pagination {\r\n";
$config.="	padding: 1px 1px 1px 1px;\r\n";
$config.="	margin: 1px 1px 1px 0px;\r\n";
$config.="   text-align:right;\r\n";
$config.="}\r\n";
$config.="div.pagination a {\r\n";
$config.="	padding: 0px 3px 1px 3px;\r\n";
$config.="	margin: 1px 1px 1px 0px;\r\n";
$config.="	border: 1px solid ".$_POST['a261'].";\r\n";
$config.="	text-decoration: none; /* no underline */\r\n";
$config.="	color: ".$_POST['a11'].";\r\n";
$config.="}\r\n";
$config.="div.pagination a:hover {\r\n";
$config.="	border: 1px solid ".$_POST['a191'].";\r\n";
$config.="	background-color: ".$_POST['a261'].";\r\n";
$config.="	color: ".$_POST['a11'].";\r\n";
$config.="	font-weight:bold;\r\n";
$config.="}\r\n";
$config.="div.pagination span.current {\r\n";
$config.="	padding: 0px 3px 1px 3px;\r\n";
$config.="	margin: 1px 1px 1px 0px;\r\n";
$config.="	border: 2px solid ".$_POST['a191'].";\r\n";
$config.="	background-color: ".$_POST['a181'].";\r\n";
$config.="	color: ".$_POST['a11'].";\r\n";
$config.="	font-weight:bold;\r\n";
$config.="}\r\n";
$config.="/* Pagination fleches navigation*/\r\n"; 
$config.="div.pagination span.disabled a {\r\n";
$config.="	padding: 0px 0px 0px 0px;\r\n";
$config.="	margin: 0px 0px 0px 0px;\r\n";
$config.="	border: 0px solid #000000;\r\n";
$config.="	background:".$_POST['a261'].";\r\n";
$config.="}\r\n";
$config.="div.pagination span.disabled a:hover {\r\n";
$config.="	padding: 0px 0px 0px 0px;\r\n";
$config.="	margin: 0px 0px 0px 0px;\r\n";
$config.="	border: 0px solid #000000;\r\n";
$config.="	background:".$_POST['a261'].";\r\n";
$config.="}\r\n\r\n";


$config.="/*-------------\r\n";
$config.="///////////////\r\n";
$config.="MENU HORIZONTAAL\r\n";
$config.="///////////////\r\n";
$config.="-------------*/\r\n";
$config.=".tableDynMenuH {\r\n";
    $config.="background-color:".$_POST['a21'].";\r\n";  
    $config.="padding-bottom:1px;\r\n"; 
    $config.="padding-top:0px;\r\n";
    $config.="border-top-width:1px; border-color:".$_POST['a191']."; border-top-style:solid;\r\n";
    $config.="border-right-width:1px; border-color:".$_POST['a191']."; border-right-style:solid;\r\n";
    $config.="border-left-width:1px; border-color:".$_POST['a191']."; border-left-style:solid;\r\n";
    $config.="border-bottom-width:3px; border-color:".$_POST['a191']."; border-bottom-style:solid;\r\n";
    $config.="margin-bottom:0px;\r\n";
$config.="}\r\n";

if(isset($_POST['center']) AND $_POST['center']=='oui') {
  $config.="/* center menu */\r\n";
  $config.=".center1 {\r\n";
  $config.="text-align: center;\r\n";
  $config.="}\r\n";
  $config.=".center2 {\r\n";
  $config.="margin-left: auto;\r\n";
  $config.="margin-right: auto;\r\n";
  $config.="}\r\n";
}
$config.="/* center menu */\r\n";
$config.=".center1 {\r\n";
$config.="text-align: center;\r\n";
$config.="}\r\n";
$config.=".center2 {\r\n";
$config.="margin-left: auto;\r\n";
$config.="margin-right: auto;\r\n";
$config.="}\r\n";
$config.="#borderTopMainMenu {background:#none; padding:0px; margin:0px;}\r\n";
$config.="#borderBottomMainMenu {background:#none; padding:0px; margin:0px;}\r\n";
$config.="#test3 {background:#none; padding:0px; margin:0px;}\r\n";
$config.="#borderTopSousmenu {background:#none; padding:0px; margin:0px;}\r\n";
$config.="#borderBottomSousmenu {background:".$_POST['a191']."; padding:0px; margin:0px;}\r\n\r\n";

$config.="div#menu45 {margin-left:0px;}\r\n";
$config.="div#menu45 a {color:#666666; padding:0px;}\r\n";
$config.="div#menu45 a:hover {color:".$_POST['a11']."; FONT-WEIGHT: bold}\r\n";
$config.="div#menu45 ul {padding:0px; margin:0px; text-align:center; top:24px; left:0px;}\r\n";
$config.="div#menu45 ul li {position:relative; list-style:none; margin-right:0px; float:left;}\r\n";
$config.="div#menu45 ul ul {position: absolute; display:none; padding:0px 0px 0px 0px; left:0px;}\r\n\r\n";

$config.="div#menu45 li {background:".$_POST['a21']."; top:1px; left:0; padding:0px;}\r\n";
$config.="div#menu45 li:hover {background:".$_POST['a21']."; border-right:0px ".$_POST['a191']." solid;}\r\n";
$config.="div#menu45 li.sousmenuA {background:".$_POST['a21']."; top:1px; left:0; padding:0px;}\r\n";
$config.="div#menu45 li.sousmenuA:hover {background:".$_POST['a21'].";}\r\n\r\n";

$config.="div#menu45 li.sousmenu {background: url(../im/fleche_bottom.gif) 95% 50% no-repeat ".$_POST['a21']."; padding:0px;}\r\n";
$config.="div#menu45 li.sousmenu:hover {background:url(../im/fleche_bottom_grey.gif) 95% 50% no-repeat;}\r\n";
$config.="div#menu45 li.sousmenu.plop {background:url(../im/fleche_right.gif) 95% 50% no-repeat ".$_POST['a191']."; padding:0px;}\r\n";
$config.="div#menu45 li.sousmenu.plop:hover {background:url(../im/fleche_right.gif) 95% 50% no-repeat #FAFAFA;}\r\n\r\n";

$config.="div#menu45 li a {text-decoration:none; padding:5px 0px 5px 0px; display:block; margin:0px; left:101px;}\r\n\r\n";

$config.="div#menu45 ul.niveau1 li.sousmenu:hover ul.niveau2 {display:block; border-right:1px ".$_POST['a191']." solid; border-left:1px ".$_POST['a191']." solid;}\r\n";
$config.="div#menu45 ul.niveau2 li.sousmenu:hover ul.niveau3 {display:block; border-top:1px ".$_POST['a191']." solid; border-right:1px ".$_POST['a191']." solid; border-left:1px ".$_POST['a191']." solid; top:-2px;}\r\n";
$config.="div#menu45 ul.niveau3 li.sousmenu:hover ul.niveau4 {display:block; border-top:1px ".$_POST['a191']." solid; border-right:1px ".$_POST['a191']." solid; border-left:1px ".$_POST['a191']." solid; top:-2px;}\r\n\r\n";
 
$config.="div#menu45 ul.niveau3 {top:-1px; left: 101px;}\r\n";
$config.="div#menu45 ul.niveau4 {top:-1px; left: 101px;}\r\n\r\n";

$config.="div#menu45 ul.niveau3 li { background: ".$_POST['a21']."}\r\n";
$config.="div#menu45 ul.niveau3 li:hover { background: ".$_POST['a21']."}\r\n";
$config.="div#menu45 ul.niveau4 li { background: ".$_POST['a21']."}\r\n";
$config.="div#menu45 ul.niveau4 li:hover { background: ".$_POST['a21']."}\r\n\r\n";

$config.="div#menu45 ul.niveau1 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat ".$_POST['a21']."; padding:0px;}\r\n";
$config.="div#menu45 ul.niveau2 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat ".$_POST['a21']."; padding:0px;}\r\n";
$config.="div#menu45 ul.niveau3 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat ".$_POST['a21']."; padding:0px;}\r\n\r\n";

$config.="div#menu45 ul.niveau1 li.sousmenu.plop:hover {background:".$_POST['a21'].";}\r\n";
$config.="div#menu45 ul.niveau2 li.sousmenu.plop:hover {background:".$_POST['a21'].";}\r\n";
$config.="div#menu45 ul.niveau3 li.sousmenu.plop:hover {background:".$_POST['a21'].";}\r\n\r\n";


$config.="/*----------\r\n";
$config.="////////////\r\n";
$config.="MENU VERTICAAL\r\n";
$config.="////////////\r\n";
$config.="----------*/\r\n";
$config.="div#menu44 {w/idth:160px;}\r\n";
$config.="div#menu44 a {color:".$_POST['a11'].";}\r\n";
$config.="div#menu44 ul {padding:0px; border:3px ".$_POST['a191']." solid; margin:0px; background:".$_POST['a181'].";}\r\n";
$config.="div#menu44 li:hover {BACKGROUND:#FFFFFF;}\r\n\r\n";

$config.="div#menu44 li.sousmenu {BACKGROUND: url(../im/fleche_right.gif) 94% 50% no-repeat;}\r\n";
$config.="div#menu44 li.sousmenu:hover {BACKGROUND: url(http://www.ecomshop.be/im/fleche_right_grey.gif) 94% 50% no-repeat #FFFFFF; margin:0px;}\r\n";
$config.="div#menu44 li.sousmenu.plop2 { background:url(../im/fleche_bottom.gif) 95% 50% no-repeat ".$_POST['a41505'].";}\r\n";
$config.="div#menu44 li.sousmenu.plop2:hover {/*background:url(../im/fleche_bottom_red.gif) 94% 50% no-repeat #FFFFCC;*/}\r\n\r\n";

$config.="div#menu44 ul li {position:relative; list-style:none; border-bottom:1px ".$_POST['a181']." solid; }\r\n";
$config.="div#menu44 ul ul {position:absolute; top:-1px; left:120px; display:none}\r\n\r\n";

$config.="div#menu44 li a {text-decoration: none; padding:5px 0px 7px 5px; display:block; border-left:4px solid ".$_POST['a181'].";}\r\n";
$config.="div#menu44 ul.niveau0 li.sousmenu:hover ul.niveau1 {display:block; position:absolute; top:24px; margin-top:1px; left:-3; height:auto;}\r\n";
$config.="div#menu44 ul.niveau1 li.sousmenu:hover ul.niveau2 {display:block;}\r\n"; 
$config.="div#menu44 ul.niveau2 li.sousmenu:hover ul.niveau3 {display:block;}\r\n";
$config.="div#menu44 ul.niveau3 li.sousmenu:hover ul.niveau4  {display:block;}\r\n\r\n";

$config.="div#menu44 li a:hover {border-left-color: #0000FF;}\r\n";
$config.="div#menu44 ul ul li a:hover {border-left-color: ".$_POST['a81'].";}\r\n";
$config.="div#menu44 ul ul ul li a:hover {border-left-color: #00CC00;}\r\n";
$config.="div#menu44 ul ul ul ul li a:hover {border-left-color: ".$_POST['a11'].";}\r\n";
$config.="div#menu44 ul ul ul ul ul li a:hover {border-left-color: #FF0000;}\r\n\r\n";

$config.="/*----------------------\r\n";
$config.="////////////////////////\r\n";
$config.="MODULES BORDURES ARRONDIES\r\n";
$config.="////////////////////////\r\n";
$config.="----------------------*/\r\n";
$config.=".raised {background: #none; margin:0 auto;}\r\n";
$config.=".raised p {margin:0 10px; padding:0px;}\r\n";
$config.=".raised .top, .raised .bottom {display:block; background:transparent; font-size:1px;}\r\n";
$config.=".raised .b1, .raised .b2, .raised .b3, .raised .b4, .raised .b1b, .raised .b2b, .raised .b3b, .raised .b4b {display:block; overflow:hidden;}\r\n";
$config.=".raised .b1, .raised .b2, .raised .b3, .raised .b1b, .raised .b2b, .raised .b3b {height:1px;}\r\n";
$config.=".raised .b2 {BACKGROUND:".$_POST['a141']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b3 {BACKGROUND:".$_POST['a141']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b4 {BACKGROUND:".$_POST['a141']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b4b {BACKGROUND:".$_POST['a181']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b3b {BACKGROUND:".$_POST['a181']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b2b {BACKGROUND:".$_POST['a181']."; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .b1 {margin:0 5px; height:2px; background:".$_POST['a191'].";}\r\n";
$config.=".raised .b2, .raised .b2b {margin:0 3px; border-width:0 3px;}\r\n";
$config.=".raised .b3, .raised .b3b {margin:0 2px;}\r\n";
$config.=".raised .b4, .raised .b4b {height:2px; margin:0 1px;}\r\n";
$config.=".raised .b1b {margin:0 5px; height:2px; background:".$_POST['a191'].";}\r\n";
$config.=".raised .boxcontent {display:block; BACKGROUND-IMAGE: url(../im/background/module_left.gif);  BACKGROUND-REPEAT: repeat-y; border-left:3px solid ".$_POST['a191']."; border-right:3px solid ".$_POST['a191'].";}\r\n";
$config.=".raised .contentTop {BACKGROUND:".$_POST['a141']."; border-bottom:0px #CCCCCC solid;}\r\n\r\n";

$config.="/*----------------------\r\n";
$config.="////////////////////////\r\n";
$config.="ARRONDIES CADRE BORDURES\r\n";
$config.="////////////////////////\r\n";
$config.="----------------------*/\r\n";
$config.=".raised2 {background: #none; margin:-1 auto;}\r\n";
$config.=".raised2 p {margin:0 10px; padding:0px;}\r\n";
$config.=".raised2 .top2, .raised2 .bottom2 {display:block; background:transparent; font-size:1px;}\r\n";
$config.=".raised2 .b1, .raised2 .b2, .raised2 .b3, .raised2 .b4, .raised2 .b1b, .raised2 .b2b, .raised2 .b3b, .raised2 .b4b, .raised2 .b1b2 {display:block; overflow:hidden;}\r\n";
$config.=".raised2 .b1, .raised2 .b2, .raised2 .b3, .raised2 .b1b, .raised2 .b2b, .raised2 .b3b, .raised2 .b1b2 {height:1px;}\r\n";
$config.=".raised2 .b2 {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b3 {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b4 {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b4b {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b3b {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b2b {BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .b1 {margin:0 5px; height:2px; background:".$_POST['a191'].";}\r\n";
$config.=".raised2 .b2, .raised2 .b2b {margin:0 3px; border-width:0 2px;}\r\n";
$config.=".raised2 .b3, .raised2 .b3b {margin:0 2px;}\r\n";
$config.=".raised2 .b4, .raised2 .b4b {height:2px; margin:0 1px;}\r\n";
$config.=".raised2 .b1b {margin:0 5px; height:2px; background:".$_POST['a191'].";}\r\n";
$config.=".raised2 .b1b2 {margin:0 0px; height:2px; background:".$_POST['a191'].";}\r\n";
$config.=".raised2 .boxcontent2 {display:block;  BACKGROUND:".$_POST['a181']."; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191'].";}\r\n";
$config.=".raised2 .boxcontent2No {display:block; border-left:2px solid ".$_POST['a191']."; border-right:2px solid ".$_POST['a191']."; BACKGROUND:".$_POST['a181']."; border-top:2px ".$_POST['a191']." solid;}\r\n";
$config.=".raised2 .contentTop2 {BACKGROUND:".$_POST['a181']."; border-bottom:2px ".$_POST['a191']." solid;}\r\n\r\n";

$config.=".backgroundHeader2 {BACKGROUND-COLOR: ".$_POST['a21'].";}\r\n\r\n";

$config.="/* Info bulle*/\r\n";
$config.="a.tooltip em {\r\n";
$config.="display:none;\r\n";
$config.="}\r\n";
$config.="a.tooltip:hover {\r\n";
$config.="border: 0;\r\n";
$config.="position: relative;\r\n";
$config.="z-index: 500;\r\n";
$config.="text-decoration:none;\r\n";
$config.="}\r\n";
$config.="a.tooltip:hover em {\r\n";
$config.="font-style: normal;\r\n";
$config.="display: block;\r\n";
$config.="position: absolute;\r\n";
$config.="top: 2px;\r\n";
$config.="left: 63px;\r\n";
$config.="padding: 5px;\r\n";
$config.="color: #000000;\r\n";
$config.="border: 1px solid #CCCCCC;\r\n";
$config.="background: #FFFFCC;\r\n";
$config.="}\r\n\r\n";

$config.="/*Moteur de recherche header 2*/\r\n";
$config.=".searchEngineHeader {border:1px ".$_POST['a191']." solid; background:url(../im/loupe.gif) top left no-repeat ".$_POST['a171']."; width:140px; height:18px; padding-left:17px;}\r\n\r\n";

$config.="/*\r\n";
$config.="----------------\r\n";
$config.="GAMMA-OL VERSION\r\n";
$config.="----------------\r\n";
$config.="*/\r\n";

$config.="/* Info carousel*/\r\n";
$config.="a.tooltip2 em {\r\n";
	$config.="display:none;\r\n";
$config.="}\r\n";
$config.="a.tooltip2:hover {\r\n";
	$config.="border: 0; \r\n";
	$config.="position: relative; \r\n";
	$config.="z-index: 500;\r\n";
$config.="}\r\n";
$config.="a.tooltip2:hover span {\r\n";
    $config.="BACKGROUND-IMAGE:url(../im/zzz_red.gif);\r\n";
$config.="}\r\n";
$config.="a.tooltip2 span {\r\n";
    $config.="padding:0px 0px 0px 0px;\r\n";
$config.="}\r\n";
$config.="a.tooltip2:hover em {\r\n";
    $config.="font-style: normal;\r\n";
    $config.="display: block;\r\n";
    $config.="position: absolute; \r\n";
    $config.="padding:5px 0px 0px 0px;  /* padding Box */\r\n";
    $config.="color: #000000;  /* font Box */\r\n";
    $config.="border: 1px solid #666666;  /* border Box */\r\n";
    $config.="background-color: #FFFFFF;  /* background Box */\r\n";
	$config.="}\r\n";
    $config.=".fondSelectedProduct {background-color: #FF0000;}\r\n";



$config.="a.tooltip3 em {\r\n";
    $config.="display:none;\r\n";
$config.="}\r\n";
$config.="a.tooltip3:hover {\r\n";
    $config.="border: 0;\r\n";
    $config.="position: relative;\r\n";
    $config.="z-index: 500;\r\n";
    $config.="text-decoration:none;\r\n";
$config.="}\r\n";
$config.="a.tooltip3:hover em {\r\n";
    $config.="font-style: normal;\r\n";
    $config.="display: block;\r\n";
    $config.="position: absolute;\r\n";
    $config.="top: 14px;\r\n";
    $config.="left: 63px;\r\n";
    $config.="padding: 5px;\r\n";
    $config.="color: #000000;\r\n";
    $config.="border: 0px solid #CCCCCC;\r\n";
    $config.="background: #none;\r\n";
$config.="}\r\n";

$config.="/*\r\n";
$config.="Couleur de fond par défaut des tableaux articles avec bordures arrondis\r\n";
$config.="*/\r\n";
$config.=".roundCornerTableDefaultBackground	{BACKGROUND-COLOR:".$_POST['a221'].";}\r\n";


$config.=".raised3 {background: #none; margin:0 auto;}\r\n";
$config.=".raised3 p {margin:0 10px; padding:0px;}\r\n";
$config.=".raised3 .top2, .raised3 .bottom2 {display:block; background:transparent; font-size:1px;}\r\n";
$config.=".raised3 .b1, .raised3 .b2, .raised3 .b3, .raised3 .b4, .raised3 .b1b, .raised3 .b2b, .raised3 .b3b, .raised3 .b4b, .raised3 .b1b2 {display:block; overflow:hidden;}\r\n";
$config.=".raised3 .b1, .raised3 .b2, .raised3 .b3, .raised3 .b1b, .raised3 .b2b, .raised3 .b3b, .raised3 .b1b2 {height:1px;}\r\n";
$config.=".raised3 .b2 {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b3 {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b4 {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b4b {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b3b {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b2b {BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .b1 {margin:0 5px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised3 .b2, .raised3 .b2b {margin:0 3px; border-width:0 2px;}\r\n";
$config.=".raised3 .b3, .raised3 .b3b {margin:0 2px;}\r\n";
$config.=".raised3 .b4, .raised3 .b4b {height:2px; margin:0 1px;}\r\n";
$config.=".raised3 .b1b {margin:0 5px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised3 .b1b2 {margin:0 0px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised3 .boxcontent2 {display:block; BACKGROUND:".$_POST['a221']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised3 .boxcontent2No {display:block; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191']."; BACKGROUND:".$_POST['a221']."; border-top:1px ".$_POST['a191']." solid;}\r\n";


$config.="/*\r\n";
$config.="Menu css horizontal avec bordures arrondis si activé\r\n";
$config.="*/\r\n";
$config.=".raised4 {background: #none; margin:0 auto;}\r\n";
$config.=".raised4 p {margin:0 10px; padding:0px;}\r\n";
$config.=".raised4 .top2, .raised4 .bottom2 {display:block; background:transparent; font-size:1px;}\r\n";
$config.=".raised4 .b1, .raised4 .b2, .raised4 .b3, .raised4 .b4, .raised4 .b1b, .raised4 .b2b, .raised4 .b3b, .raised4 .b4b, .raised4 .b1b2 {display:block; overflow:hidden;}\r\n";
$config.=".raised4 .b1, .raised4 .b2, .raised4 .b3, .raised4 .b1b, .raised4 .b2b, .raised4 .b3b, .raised4 .b1b2 {height:1px;}\r\n";
$config.=".raised4 .b2 {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b3 {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b4 {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b4b {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b3b {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b2b {BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .b1 {margin:0 5px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised4 .b2, .raised4 .b2b {margin:0 3px; border-width:0 2px;}\r\n";
$config.=".raised4 .b3, .raised4 .b3b {margin:0 2px;}\r\n";
$config.=".raised4 .b4, .raised4 .b4b {height:2px; margin:0 1px;}\r\n";
$config.=".raised4 .b1b {margin:0 5px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised4 .b1b2 {margin:0 0px; background:".$_POST['a191']."; height:1px;}\r\n";
$config.=".raised4 .boxcontent2 {display:block; BACKGROUND:".$_POST['a181']."; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191'].";}\r\n";
$config.=".raised4 .boxcontent2No {display:block; border-left:1px solid ".$_POST['a191']."; border-right:1px solid ".$_POST['a191']."; BACKGROUND:".$_POST['a181']."; border-top:1px ".$_POST['a191']." solid;}\r\n";
$config.="\r\n";
$config.=".tableDynMenuHRounded {\r\n";
$config.="background-color:#none;\r\n";
$config.="padding-bottom:2px;\r\n";
$config.="padding-top:0px;\r\n";
$config.="margin-bottom:0px;\r\n";
$config.="}\r\n";

$config.="#formG input {\r\n";
$config.="border:2px solid ".$_POST['a211'].";\r\n";
$config.="color:".$_POST['a4129'].";\r\n";
$config.="height:23px;\r\n";
$config.="}\r\n";
$config.="#formG textarea {\r\n";
$config.="border:2px solid ".$_POST['a211'].";\r\n";
$config.="color:".$_POST['a4129'].";\r\n";
$config.="}\r\n";

  $myCss = $_POST['a11']."+".$_POST['a21']."+".$_POST['a31']."+".$_POST['a81'].
  "+".$_POST['a91'].
  "+".$_POST['a141']."+".$_POST['a151']."+".$_POST['a161'].
  "+".$_POST['a171']."+".$_POST['a171a']."+".$_POST['a181']."+".$_POST['a191']."+".$_POST['a201'].
  "+".$_POST['a211']."+".$_POST['a221'].
  "+".$_POST['a261']."+".$_POST['a271']."+".$_POST['a281'].
  "+".$_POST['a291']."+".$_POST['a301'].
  "+".$_POST['a341']."+".$_POST['a351']."+".$_POST['a361'].
  "+".$_POST['a371']."+".$_POST['a381'].
  "+".$_POST['a431']."+".$_POST['a441'].
  "+".$_POST['a451'].
  "+".$_POST['a4112']."+".$_POST['a4113']."+".$_POST['a4123']."+".$_POST['a4124']."+".$_POST['a4125'].
  "+".$_POST['a4129']."+".$_POST['a4130'].
  "+".$_POST['a4132']."+".$_POST['a4133']."+".$_POST['a4134']."+".$_POST['a4114u']."+".$_POST['a42000'].
  "+".$_POST['a41500']."+".$_POST['a41501']."+".$_POST['a41502']."+".$_POST['a41503']."+".$_POST['a41504'].
  "+".$_POST['a41505']."+".$_POST['a41506']."+".$_POST['a41507']."+".$_POST['a50000']."+".$_POST['a10000']."+";
  mysql_query("UPDATE admin SET my_css='".$myCss."'");

  $topo = fopen("../css/perso.css","w");
  fputs($topo, $config);
  fclose($topo);


?>
    <table align="center" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td colspan="2">
    	<?php if(isset($messageLiv)) print $messageLiv;?>
		<p align="center" class="fontrouge"><b><?php print A4;?></b></p>
	</td>
  </tr>
  <tr>
    <td colspan="2"><br><div align="center"><b><a href="config_css.php" target='main'><?php print A5;?></a></b></div></td>
  </tr>
  </table><br><br><br>
<?php
}
?>
<br><br><br>
</form>

  </body>
  </html>
