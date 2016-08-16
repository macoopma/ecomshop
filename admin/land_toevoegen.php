<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
$message="";
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

if(isset($_POST['action']) AND $_POST['action'] == A1) {
  if(!empty($_POST['c_name'])) {
	$_POST['c_name'] = str_replace("'"," ",$_POST['c_name']);

   mysql_query("INSERT INTO countries (
                                      countries_name,
                                      iso,
                                      countries_shipping,
                                      countries_shipping_tax,
                                      countries_shipping_tax_active,
                                      countries_packing_tax,
                                      countries_packing_tax_active,
                                      countries_product_tax,
                                      countries_product_tax_active,
                                      country_state
                                      )
                          VALUES (
                                  '".$_POST['c_name']."',
                                  '".strtoupper($_POST['c_iso'])."',
                                  '".$_POST['c_zone']."',
                                  '".$_POST['c_tax_value']."',
                                  '".$_POST['c_ship_tax_apply']."',
                                  '".$_POST['c_pack_value']."',
                                  '".$_POST['c_pack_tax_apply']."',
                                  '".$_POST['prod_tax_value']."',
                                  '".$_POST['c_product_tax_apply']."',
                                  '".$_POST['c_select']."'
                                  )") or die (mysql_error());
    $message = $_POST['c_name']." ".A2."";
  }
  else {
    $message = A3;
  }
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";

  var c_iso = document.form1.c_iso.value;
  var c_name = document.form1.c_name.value;

  if(document.form1.elements['c_iso'].type != "hidden") {
    if(c_iso == '' || c_iso.length < 2) {
      error_message = error_message + "<?php print A18;?> 'ISO'\n";
      error = 1;
    }
  }
  
  if(document.form1.elements['c_name'].type != "hidden") {
    if(c_name == '' || c_name.length < 2) {
      error_message = error_message + "<?php print A18;?> <?php print A11;?>\n";
      error = 1;
    }
  }

  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A4;?></p>
<p align="center"><table width="700" border="0" cellspacing="0" cellpadding="5" class="TABLE"><tr><td><center><?php print A5;?></tr></td></table>
</p>
<?php
if($_SESSION['lang']==1) $langWik="fr"; else $langWik="en";
?>
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>" name="form1" onsubmit="return check_form()">

<table align="center" border="0" cellpadding="5" cellspacing="0"  class="TABLE" width="800"><tr>
<td align="left" valign="top"><?php print A6;?></td>
<td align="left" valign="top">ISO 3 <a href='http://nl.wikipedia.org/wiki/ISO_3166-1' target='_blank'><img border=0 src=im/help.png align=absmiddle></a></td>
<td align="center" valign="top"><?php print A7;?></td>
<td align="center" valign="top"><?php print A8;?></td>
<td align="center" valign="top"><?php print A9;?></td>
<td align="center" valign="top"><?php print A8A;?></td>
<td align="center" valign="top"><?php print A9A;?></td>
<td align="center" valign="top"><?php print A10;?></td>
<td align="center" valign="top"><?php print A17;?></td>
<td align="center" valign="top"><?php print A11;?></td>
</tr>
<tr>
<td align="left"><input type="text" class="vullen" name="c_name" size="8"></td>
<td align="left"><input type="text" class="vullen" name="c_iso" size="2" maxlength="3"></td>
<td center">
  <select name="c_zone">
  <option value="zone1">zone1</option>
  <option value="zone2">zone2</option>
  <option value="zone3">zone3</option>
  <option value="zone4">zone4</option>
  <option value="zone5">zone5</option>
  <option value="zone6">zone6</option>
  <option value="zone7">zone7</option>
  <option value="zone8">zone8</option>
  <option value="zone9">zone9</option>
  <option value="zone10">zone10</option>
  <option value="zone11">zone11</option>
  <option value="zone12">zone12</option>
    <option value="exclude"><?php print A12;?></option>
  </select>
  </td>
<td align="center">
  <select name="c_ship_tax_apply">
  <option value="yes"><?php print A13;?></option>
  <option value="no"><?php print A14;?></option>
  </select>
</td>
<td align="center"><input type="text" class="vullen" name="c_tax_value" size="2">%</td>

<td align="center">
  <select name="c_pack_tax_apply">
  <option value="yes"><?php print A13;?></option>
  <option value="no"><?php print A14;?></option>
  </select>
</td>
<td align="center"><input type="text" class="vullen" name="c_pack_value" size="2">%</td>

<td align="center">
  <select name="c_product_tax_apply">
  <option value="yes"><?php print A13;?></option>
  <option value="no"><?php print A14;?></option>
  </select>
</td>
<td align="center"><input type="text" class="vullen" name="prod_tax_value" size="2">%</td>
<td align="center">
  <select name="c_select">
  <option value="country"><?php print A15;?></option>
  <!--<option value="state"><?php print A16;?></option>-->
  </select>
</td>
</tr>

</table>

  <br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0">
<tr>
<td colspan="2" align="center"><input type="submit" name="action" value="<?php print A1;?>" class='knop'</td>
</tr>
</table>
</form>
<center><table width="700" border="0" cellspacing="0" cellpadding="5" class="TABLE"><tr><td>
<p align="center" class="fontrouge"><b><?php print $message;?></b></tr></td></table>

  </body>
  </html>
