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

if(isset($_SESSION['ajouterValue'])) $_SESSION['ajouterValue'] = "";
$message = "";

if(isset($_GET['action']) and $_GET['action'] == A13) {
   $optionNameQuery = mysql_query("SELECT q.products_options_id, z.products_options_name
                                   FROM products_id_to_products_options_id as q
                                   LEFT JOIN products_options as z
                                   ON(q.products_options_id = z.products_options_id)
                                   WHERE q.products_options_id = '".$_GET['optionName']."'
                                   AND q.products_id = ".$_GET['id']."");
   if(mysql_num_rows($optionNameQuery) > 0) {
      $message = "<span class='fontrouge'>**<b>".A1."</b>**</span>";
   }
   else {
      $gogo = "opties_maken_details.php?productId=".$_GET['id']."&optionId=".$_GET['optionName']."";
      header("Location: $gogo");
   }
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript"><!--
function check_add() {

  rejet = false;
  rejet1 = false;
  falsechar="";
  var non = "+";
  var non1 = "'";
  var error = 0;
  var error_message = "";
  
  for(i=0 ; i <= form1.AddName.value.length ; i++) { if((form1.AddName.value.charAt(i)==non)) {rejet=true; falsechar= non;}}
  if(rejet==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  for(i=0 ; i <= form1.AddName.value.length ; i++) { if((form1.AddName.value.charAt(i)==non1)) {rejet1=true; falsechar= non1;}}
  if(rejet1==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<p align="center" class="largeBold"><?php print A2;?></p>
<?php
// optie verwijderen
if(isset($_GET['action']) and $_GET['action']==A14) {

     $yo1 = mysql_query("SELECT products_options_values_id FROM products_options_values
                        WHERE products_options_id = '".$_GET['optionName']."'");
     $yoVerif = mysql_num_rows($yo1);

     $yo = mysql_query("SELECT products_options_name FROM products_options
                        WHERE products_options_id = '".$_GET['optionName']."'");
     $yoName = mysql_fetch_array($yo);

     if($yoVerif > 0) {
        $message = "<span class='fontrouge'>".A3." <b>".strtoupper($yoName['products_options_name'])."</b> ".A4."<br>".A5."</span>";
     }
     else {
     mysql_query("DELETE FROM products_options WHERE products_options_id='".$_GET['optionName']."'");
     $message = "<span class='fontrouge'>".A3." <b>".strtoupper($yoName['products_options_name'])."</b> ".A6."</span>";
     }
}

// toevoegen
if(isset($_GET['action']) and $_GET['action']==A15) {
     if(empty($_GET['AddName'])) {
        $message = "<span class='fontrouge'><b>".A7."</span>";
     }
     else {
	 	    $_GET['AddName'] = str_replace("+","&#134;",$_GET['AddName']);
        $_GET['AddName'] = str_replace("'","&#146;",$_GET['AddName']);
           $vQuery = mysql_query("SELECT *
                             FROM products_options
                             WHERE products_options_name = '".$_GET['AddName']."'");
           $vResult = mysql_num_rows($vQuery);
           if($vResult > 0) {
              $message = "<span class='fontrouge'>".A3." <b>".$_GET['AddName']."</b> ".A8."</span>";
           }
           else {
           $AddName = str_replace(",",".",$_GET['AddName']);
              mysql_query("INSERT INTO products_options (products_options_name)
                           VALUES ('".$_GET['AddName']."')");
              $message = "<span class='fontrouge'>".A3." <b>".strtoupper($_GET['AddName'])."</b> ".A9."</span>";
           }
     }
}


// Afbeelden
$ProductNameQuery = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", products_qt
                                 FROM products
                                 WHERE products_id = '".$_GET['id']."'");
$productName = mysql_fetch_array($ProductNameQuery);
print "<p align='center'>";
print "<b>".A11."</b>: <a href='artikel_wijzigen_details.php?id=".$productName['products_id']."'>".strtoupper($productName['products_name_'.$_SESSION['lang']])."</a>";
// stock
print " - ".STOCK_G.": <b>".$productName['products_qt']."</b>";
print "</p>";

// instructies
print "<p align='center'>";
print "<table width='700' border='0' cellpadding='0' cellspacing ='0' align='center' width='700' class='TABLE'><tr><td align='center'>";
print "<span style='font-size:13px;'><b>[1]</b></span> - ".A10;
print "</td></tr></table>";
print "</p>";

// Selecteer optie naam
$optionNameQuery = mysql_query("SELECT * FROM products_options ORDER BY products_options_name");

print "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='GET' onsubmit='return check_add()';>";
print "<input type='hidden' name='id' value='".$_GET['id']."'>";

print "<table border='0' width='700' cellpadding='10' cellspacing ='0' align='center' class='TABLE'>";
print "<tr'>";
print "<td valign=top>";
        print "<b>".A12."</b> ";
        print "<select name='optionName'>";
                while($optionName = mysql_fetch_array($optionNameQuery)) {
                      print "<option name='optionName' value='".$optionName['products_options_id']."'>".$optionName['products_options_name'];
                      print "</option>";
                }
        print "</select>&nbsp;&nbsp;<img src='im/arrows.gif' align='absmiddle'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='submit' class='knop' name='action' VALUE='".A13."' >";


// verder
 
   //     print "<img src='im/arrows.gif' align='absmiddle'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='submit' class='knop' name='action' VALUE='".A13."' >";
print "&nbsp;&nbsp;&nbsp;<INPUT TYPE='submit' class='knop' name='action' VALUE='".A14."'></td>";

print "</td>";

print "</tr>";
print "<tr height='50' bgcolor='#CCFF00'>";
// toevoegen
print "<td align='center' valign=middle>Naam van de nieuwe optie&nbsp;&nbsp;&nbsp;";
print "<input type='text' class='vullen' size='30' name='AddName'>";
print "&nbsp;<INPUT TYPE='submit' name='action' class='knop' VALUE='".A15."'>";
print "</td>";
print "</tr>";
// bericht
if(isset($message) AND $message!=='') {
print "<tr>";
print "<td colspan='2' height='30'>";
print "<div align='center'><b>".$message."</b></div>";
print "</td>";
print "</tr>";
}
print "</table>";
print "</form>";



print "<img src='im/zzz.gif' width='1' height='25'><br><div align='center'>";
print A20;
print "</div>";
?>

  </body>
  </html>
