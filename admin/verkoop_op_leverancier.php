<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

 
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c = "";
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var id = document.form101.id.value;

  if(document.form101.elements['id'].type != "hidden") {
    if(id == 'none' ) {
      error_message11 = error_message11 + "<?php print VEUILLEZ;?>.\n";
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
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>


<?php
 

$prods = mysql_query("SELECT * FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fournisseur' ORDER BY fournisseurs_company");
$prodsNum = mysql_num_rows($prods);

if($prodsNum > 0) {
	print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr>";
    print "<form action='verkoop_op_leverancier_details.php' method='GET' name='form101' onsubmit='return formu()';>";
    print "<td>";
    print "<center><p>";
    print "<select name=id>";
    print "<option value='none'>Selecteer een leverancier</option>";
    while ($produit = mysql_fetch_array($prods)) {
           print "<option value='".$produit['fournisseurs_id']."'>".strtoupper($produit['fournisseurs_company']);
           print "</option>";
    }
	print "</select>&nbsp;&nbsp;<input type='Submit' class='knop' value='".A2."'>";
	print "</td>";
	print "</tr>";
	print "<tr>";
	print "<td cospan='2' align='center'>";
	print "</td>";
	print "</form>";
	print "</tr>";
	print "</table>";
	
	print "<br><br>";
	
	$prodsdd = mysql_query("SELECT * FROM fournisseurs WHERE fournisseur_ou_fabricant = 'fournisseur' ORDER BY fournisseurs_company");
	print "<table border='0' cellpadding='4' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr>";
	print "<td height='30' align='left' valign='middle'><b>".FOURNISSEUR."</b></td>";
	print "<td height='30' align='center' valign='middle'><b>Verkocht</b></td></tr><tr>";
    while ($produita = mysql_fetch_array($prodsdd)) {
            if($c=="#CCFF99") $c = "#FFFFFF"; else $c = "#CCFF99";
            $venduF="";
            $venduArray="";
            $r1 = mysql_query("SELECT products_sold
                                    FROM products
                                    WHERE fournisseurs_id = '".$produita['fournisseurs_id']."'");
            $r1Num = mysql_num_rows($r1);
            if($r1Num > 0) {
                while($r1Query = mysql_fetch_array($r1)) {
                    $venduArray[] = $r1Query['products_sold'];
                 }
                 $venduF = array_sum($venduArray);
            }
            else {
                $venduF = 0;
            }
            if($venduF > 0) {$venduDisplay = "<span class='fontrouge'><b>".$venduF."</b></span>";} else {$venduDisplay = $venduF;}
           print "<td bgcolor='".$c."'><a href='verkoop_op_leverancier_details.php?id=".$produita['fournisseurs_id']."'>".strtoupper($produita['fournisseurs_company'])."</a></td>";
           print "<td bgcolor='".$c."' align='center'>".$venduDisplay."</td>";
           print "</tr><tr>";
    }
	print "</tr>";
	print "</table><br><br><br>";
}
else {
    print "<p align='center' class='fontrouge'><b>".NO_SUPPLIER."</b></p>";
}
?>
<br><br><br>
</body>
</html>
