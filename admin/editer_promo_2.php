<?php
session_start();
 
if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuration/configuration.php');
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
     $_GET['dateFinPromo'] = "2020-01-01";
}
mysql_query("UPDATE specials
             SET specials_new_price='".$_GET['newPrice']."',
                 specials_visible='".$_GET['visible']."',
                 specials_first_day='".$_GET['dateDebutPromo']."',
                 specials_last_day='".$_GET['dateFinPromo']."'
             WHERE specials_id='".$_GET['id']."'");
             
             $retour = "<table border='0' align='center' cellspacing='0' cellpadding='5'><tr>";
             $retour.= "<td align='center'><b><span class='fontrouge'>".UPDATE_OK."</span></b></td>";
             $retour.= "</tr>";
             $retour.= "<tr>";
             $retour.= "<form action='editer_promo.php' method='POST'>";
             $retour.= "<td align='center'><input type='submit' value='".strtoupper(A14)."'></td>";
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
print "<table width='370' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr bgcolor='#FFFFFF'>";
print "<td colspan='2' align='center'><b>".strtoupper($row['products_name_'.$_SESSION['lang']])."</b></td>";
$c="";
  if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";

// products_name
print "</tr><tr>";
        print "<td>".A3.": </td>";
        print "<td>".$row['products_name_'.$_SESSION['lang']]."</td>";
// products_price
print "</tr><tr bgcolor='#E8E8E8'>";
        print "<td>".A4.": </td>";
        print "<td>".$row['products_price']."</td>";
// prix promo
print "</tr><tr>";
        print "<td>".A5.": </td>";
        print "<td><input type='text' value='".$row['specials_new_price']."' name='newPrice' size='10'></td>";
// date debut de promo
print "</tr><tr bgcolor='#E8E8E8'>";
        print "<td>".A6.": </td>";
		// European date format DD-MM-YYYY START
        //print "<td><input type='text' value='".$row['specials_first_day']."' name='dateDebutPromo' size='15'> AAAA-MM-JJ<br>";
		//print "<i><span class='FontGris'>".A7.": ".date("Y-m-d")."</span></i></td>";
		print "<td><input type='text' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['specials_first_day'])."' name='dateDebutPromo' size='15'> JJ-MM-AAAA<br>";
		print "<i><span class='FontGris'>".A7.": ".date("d-m-Y")."</span></i></td>";
		// European date format DD-MM-YYYY END
// date max de promo
print "</tr><tr>";
        print "<td>".A8.": </td>";
		// European date format DD-MM-YYYY START
        //print "<td><input type='text' value='".$row['specials_last_day']."' name='dateFinPromo' size='15'> AAAA-MM-JJ<br>";
		// print "<i><span class='FontGris'>".A7.": ".date("Y-m-d")."</span></i></td>";
		print "<td><input type='text' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['specials_last_day'])."' name='dateFinPromo' size='15'> AAAA-MM-JJ<br>";
		print "<i><span class='FontGris'>".A7.": ".date("d-m-Y")."</span></i></td>";
		// European date format DD-MM-YYYY END
// Transmission de la variable id
        print "<input type='hidden' value='".$_GET['id']."' name='id'>";
print "</tr><tr bgcolor='#E8E8E8'>";

// categorie_visible
        if($row['specials_visible'] == "yes") {
        	$checkedyes = "checked";
        	$checkedno = "";
        }
		else {
        	$checkedyes = "";
        	$checkedno = "checked";
        }
print "<td>Visible: </td>";
print "<td> ".A9." <input type='radio' value='yes' ".$checkedyes." name='visible'>";
print A10." <input type='radio' value='no' name='visible' ".$checkedno.">";
print "</td>";
print "</tr></table>";

print "<br>";

print "<table width='370' border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE'>";
print "<tr>";
print "<td colspan='2' align='center' height='40'><input type='submit' name='action' value='".A11."' style='border:#000000 3px solid; -moz-border-radius : 5px 5px 5px 5px;'></td>";
print "</tr></table>";


print "</form>";
?>

<table width="350" border="0" align="center" cellpadding="5" cellspacing = "0">
<tr>
<td>
(1) <?php print A12;?>: <b>AAAA-MM-JJ</b><br>
<div class="fontrouge"><?php print A13;?></div>
</td>
</tr>
</table>

</body>
</html>
