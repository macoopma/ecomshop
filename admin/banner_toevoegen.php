<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c="";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<form method="POST" action="banner_controle.php" enctype='multipart/form-data'>

<table width="700 border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
      <?php 
      if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF"; ?>
    <tr bgcolor=<?php print $c; ?>>
      <td valign=top><?php print A2;?></td>
      <td> 
		<input type="text" class='vullen' name="desc" value="" size="50"><br>
         <?php print A3;?>
      </td>
    </tr>
      <?php if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td valign=top><?php print A5;?></td>
      <td>


	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadBanner' class='vullen' size='40'>
	&nbsp;<INPUT TYPE='reset' NAME='nom' VALUE='<?php print VIDER;?>' class='knop'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	im/banner/<input type="text" class='vullen' size="30" name="image">&nbsp;(.gif,.jpg,.png,.swf)
 
	

<br>
 

               <b>Ofwel</b><br>
               - <?php print A8;?><br>
               <u>Voorbeeld</u>: http://www.domeinnaam.com/banner.gif
      </td>
    </tr>
      <?php if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td valign=top><?php print A9;?></td>
      <td>
        <input type="text" name="url" value="" class='vullen' size="50"><br>
               <?php print A10;?><br>
               <u>Voorbeeld</u>: http://www.domeinnaam.com/banner.gif
      </td>
    </tr>




      <?php if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1"; ?>
    <tr>
      <td valign=top><?php print A20;?></td>
      <td>
      <textarea cols="70" rows="3" name="sponsor" class='vullen'></textarea><br>
               <?php print A21;?>
      </td>
    </tr>
    
    
    
    
      <?php if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1"; ?>

    <tr>
      <td ><?php print A11;?></td>
      <td>
        <input type="text" name="end" size="20" maxlength="255" class='vullen'> DD-MM-JJJJ<br>
               <?php print A12;?>
      </td>
    </tr>
  </table>

  <br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2" height="40" align="center"><input type="submit" value="<?php print A1Z;?>" class="knop"></td>
</tr>
</table>
</form>

<br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2">(*) <?php print A13;?></td>
</tr>
</table>


</body>
</html>
