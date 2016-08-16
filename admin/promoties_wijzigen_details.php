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
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
if(isset($_GET['action']) and $_GET['action'] == A11) {
// European date format DD-MM-YYYY START
$_GET['dateFinPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateFinPromo']);
$_GET['dateDebutPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateDebutPromo']);
// European date format DD-MM-YYYY END
if(empty($_GET['dateFinPromo'])) {
     $_GET['dateFinPromo'] = "2040-01-01";
}

mysql_query("UPDATE specials
             SET specials_new_price='".$_GET['newPrice']."',
                 specials_visible='".$_GET['visible']."',
                 specials_first_day='".$_GET['dateDebutPromo']."',
                 specials_last_day='".$_GET['dateFinPromo']."'
             WHERE specials_id='".$_GET['id']."'");
             
             $retour = "<table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr>";
             $retour.= "<td align='center'><b><span class='fontrouge'>".UPDATE_OK."</span></b></td>";
             $retour.= "</tr>";
             $retour.= "<tr>";
             $retour.= "<form action='promoties_wijzigen.php' method='POST'>";
             $retour.= "<td align='center'><input class='knop' type='submit' value='".strtoupper(A14)."'></td>";
             $retour.= "</tr>";
             $retour.= "</form>";
             $retour.= "</table><br>";
}

$query = mysql_query("SELECT p.products_id, p.products_price, p.products_name_".$_SESSION['lang'].", s.*
                      FROM specials as s
                      INNER JOIN products as p
                      ON(s.products_id = p.products_id)
                      WHERE specials_id = '".$_GET['id']."'");
$row = mysql_fetch_array($query);

if(isset($retour)) print $retour;

print "<form action='".$_SERVER['PHP_SELF']."' method='GET' target='main'>";
print "<table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr bgcolor='#FFFFFF'>";
print "<td colspan='2' align='center'><b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b></td>";
$c="";
  if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";


print "</tr><tr>";
        print "<td>".A3." </td>";
        print "<td>".$row['products_name_'.$_SESSION['lang']]."</td>";
 
print "</tr><tr>";
        print "<td>".A4." </td>";
        print "<td>".$row['products_price']."</td>";
 
print "</tr><tr>";
        print "<td>".A5." </td>";
        print "<td><input type='text' class='vullen' value='".$row['specials_new_price']."' name='newPrice' size='10'></td>";
 
print "</tr><tr>";
        print "<td>".A6.": </td>";
        print "<td><input type='text' class='vullen' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['specials_first_day'])."' name='dateDebutPromo' size='15'><br>";
        print "".A7.": ".date("d-m-Y")."</td>";
 
print "</tr><tr>";
        print "<td>".A8." </td>";
        print "<td><input type='text' class='vullen' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['specials_last_day'])."' name='dateFinPromo' size='15'><br>";
        print "".A7.": ".date("d-m-Y")."</td>";
 
        print "<input type='hidden' value='".$_GET['id']."' name='id'>";
print "</tr><tr>";


        if($row['specials_visible'] == "yes") {
        	$checkedyes = "checked";
        	$checkedno = "";
        }
		else {
        	$checkedyes = "";
        	$checkedno = "checked";
        }
print "<td>Zichtbaar </td>";
print "<td> ".A9." <input type='radio' value='yes' ".$checkedyes." name='visible'>";
print A10." <input type='radio' value='no' name='visible' ".$checkedno.">";
print "</td>";
print "</tr></table>";

print "<br>";

print "<table width='700' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr>";
print "<td colspan='2' align='center' height='40'><input type='submit' class='knop 'name='action' value='".A11."'></td>";
print "</tr></table>";


print "</form>";
?>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class='TABLE' width='700'>
<tr>
<td>
(1) <?php print A12;?>: <b>DD-MM-JJJJ</b><br>
<div class="fontrouge"><?php print A13;?></div>
</td>
</tr>
</table>

</body>
</html>
