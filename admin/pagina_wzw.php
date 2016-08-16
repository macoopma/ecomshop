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

 
if(isset($_POST['action']) AND $_POST['action']=="ok") {
  $_POST['cond1'] = str_replace("\'","&#146;",$_POST['cond1']);
  $_POST['cond1'] = str_replace("'","&#146;",$_POST['cond1']);
  $_POST['cond2'] = str_replace("\'","&#146;",$_POST['cond2']);
  $_POST['cond2'] = str_replace("'","&#146;",$_POST['cond2']);
  $_POST['cond3'] = str_replace("\'","&#146;",$_POST['cond3']);
  $_POST['cond3'] = str_replace("'","&#146;",$_POST['cond3']);
  $update = mysql_query("UPDATE pages SET pages_qui1 = '".$_POST['cond1']."', pages_qui2 = '".$_POST['cond2']."', pages_qui3 = '".$_POST['cond3']."' WHERE pages_id='1'");
  $messaged = "<p align='center' class='fontrouge'><b>".UPDATE_OK."</b></p>";
}

 
$req = mysql_query("SELECT * FROM pages WHERE pages_id='1'");
$result = mysql_fetch_array($req);
?>
<a href="#jhjTop" name='noteTop'></a>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php if(isset($messaged)) print $messaged;?>

<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="action" value="ok">

 
<table border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>
<table width="100%" border="0" cellpadding="8" cellspacing="0"><tr>
<td>
<img src="im/zzz.gif" width="1" height="5"><br><img src="im/be.gif">&nbsp;<img src="im/nl.gif">&nbsp;<?php print A2;?><br><img src="im/zzz.gif" width="1" height="5"><br>
<textarea cols="90" rows="15" name="cond3" value=""><?php print $result['pages_qui3'];?></textarea>
</td>
<td align="right" valign=top">
<input type="submit" class="knop"value="<?php print A5;?>">
<br><a href="../bekijken.php?fromPage=qui3" target="_blank"><img src="im/voir.gif" border="0" title="<?php print VOIR;?>"></a>
</td>
</tr>

 
<tr>
<td>
<img src="im/zzz.gif" width="1" height="5"><br><img src="im/fr.gif">&nbsp;<?php print A3;?><br><img src="im/zzz.gif" width="1" height="5"><br>
<textarea cols="90" rows="15" name="cond1" value=""><?php print $result['pages_qui1'];?></textarea>
</td>
<td align="right" valign=top">
<input type="submit" class="knop"value="<?php print A5;?>">
<br><a href="../bekijken.php?fromPage=qui1" target="_blank"><img src="im/voir.gif" border="0" title="<?php print VOIR;?>"></a>
</td>
</tr>



<tr>
<td>
<img src="im/zzz.gif" width="1" height="5"><br><img src="im/uk.gif">&nbsp;<?php print A4;?><br><img src="im/zzz.gif" width="1" height="5"><br>
<textarea cols="90" rows="15" name="cond2" value=""><?php print $result['pages_qui2'];?></textarea>
</td>
<td align="right" valign=top">
<input type="submit" class="knop"value="<?php print A5;?>">
<br><a href="../bekijken.php?fromPage=qui2" target="_blank"><img src="im/voir.gif" border="0" title="<?php print VOIR;?>"></a>
</td>
</tr>
</table>
</form>
<br>

<a href="#jhj" name='note'></a>
<table border="0" width="100%" cellpadding="5" cellspacing="0"><tr>
<td align="left" style="background-color:#FFFFFF; border:#CCCCCC 1px solid;">
<?php print NOTES;?>
</td>
</tr>
</table>

</td></tr></table>
<br>
<br>
<br>
</body>
</html>
