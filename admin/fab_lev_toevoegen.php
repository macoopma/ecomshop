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
$c="";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript">
function checkTheForm() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var stat = document.yoyo.stat.value;
  var company = document.yoyo.company.value;
  var ref = document.yoyo.ref.value;

  if(document.yoyo.elements['ref'].type != "hidden") {
    if(ref == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE3;?>\n";
      error11 = 1;
    }
  }
  
  if(document.yoyo.elements['stat'].type != "hidden") {
    if(stat == '') {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE1;?>\n";
      error11 = 1;
    }
  }

  if(document.yoyo.elements['company'].type != "hidden") {
    if(company == '' || company.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE2;?>\n";
      error11 = 1;
    }
  }

  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//-->
</script>
<p align="center" class="largeBold"><?php print A1;?></p>


<form method="GET" action="fab_lev_bevestigen.php" name="yoyo" onsubmit="return checkTheForm()">

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>
    <tr bgcolor=<?php print $c;?>>
      <td width="200"><?php print A2;?>&nbsp;&nbsp;<font color=red>(*)</font></td>
      <td>
        <input type="text" class="vullen" name="ref" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>
    <tr bgcolor=<?php print $c;?>>
      <td width="200"><?php print FOURNISSEUR;?> / <?php print FABRICANT;?>&nbsp;&nbsp;<font color=red>(*)</font></td>
      <td>
        <select name="stat">
            <option value="" value="empty" selected>
            <option value="fournisseur"><?php print FOURNISSEUR;?></option>
            <option value="fabricant"><?php print FABRICANT;?></option>
        </select>
      </td>
    </tr>
    <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>
    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A3;?>&nbsp;&nbsp;<font color=red>(*)</font></td>
      <td>
        <input type="text" class="vullen" name="company" value="" size="30" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="200"><?php print A4;?></td>
      <td>
        <input type="text" class="vullen" name="contact_nom" value="" size="20" maxlength="255">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="200"><?php print A5;?></td>
      <td>
        <input type="text" class="vullen" name="contact_prenom" value="" size="20" maxlength="255">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A6;?></td>
      <td>
        <input type="text" class="vullen" name="adresse" value="" size="40" maxlength="255">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A7;?></td>
      <td>
        <input type="text" class="vullen"  name="cp" value="" size="15" maxlength="15">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A8;?></td>
      <td>
        <input type="text" class="vullen" name="ville" value="" size="20" maxlength="255">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A9;?></td>
      <td>
        <input type="text" class="vullen" name="pays" value="" size="20" maxlength="255">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A10;?> 1</td>
      <td>
        <input type="text" class="vullen" name="tel1" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A10;?> 2</td>
      <td>
        <input type="text" class="vullen" name="tel2" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A11;?> 1</td>
      <td>
        <input type="text" class="vullen" name="cel1" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A11;?> 2</td>
      <td>
        <input type="text" class="vullen" name="cel2" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A12;?></td>
      <td>
        <input type="text" class="vullen" name="fax" value="" size="15" maxlength="30">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>
    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A13;?></td>
      <td>
        <input type="text" class="vullen" name="website" value="" size="40" maxlength="100">
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>
    <tr bgcolor=<?php print $c;?>>
      <td width="100"><?php print A14;?></td>
      <td>
        <input type="text" class="vullen" name="email" value="" size="25" maxlength="50" >
      </td>
    </tr>
      <?php if($c=="#ffffff") $c = "#ffffff"; else $c = "#ffffff"; ?>

    <tr bgcolor=<?php print $c;?>>
      <td width="100" valign=top><?php print A15;?></td>
      <td>
        <textarea name="note" rows="7" cols="70" class="vullen"></textarea>
      </td>
    </tr>
  </table>

  <br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2"><center><input type="submit" class="knop"  value="<?php print A16;?>"></td>
</tr>
</table>
</form>

<br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2">(*) <?php print A17;?></td>
</tr>
</table>
<br><br><br><br>


  </body>
  </html>