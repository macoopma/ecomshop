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
$query = mysql_query("SELECT * FROM fournisseurs WHERE fournisseurs_id='".$_GET['id']."'");
$row = mysql_fetch_array($query);

if(!isset($_GET['page'])) {
   $quer = mysql_query("SELECT products_id, products_name_".$_SESSION['lang']." 
                        FROM products 
                        WHERE fournisseurs_id='".$_GET['id']."'
                        OR fabricant_id='".$_GET['id']."'
                        ");
   $querNum = mysql_num_rows($quer);
   if($querNum>0) {
      while($fourni = mysql_fetch_array($quer)) {
         $articlesCorArray[] = "- <a href='artikel_wijzigen_details.php?id=".$fourni['products_id']."'>".$fourni['products_name_'.$_SESSION['lang']]."</a>";
      }
      $nn=0;
   }
   else {
      $nn=1;
   }

if($nn==1) {
    print "<div align='center'><table border='0' align='center' class='TABLE' cellspacing='0' cellpadding='10' width='700'><tr><td><center>";
    print A2." <b>".strtoupper($row['fournisseurs_company'])."</b> ".A3."<br><br>";
    print "<a href='fab_lev_verwijderen.php?page=delete&id=".$_GET['id']."' target='main'><b>".A4."</b></a> &nbsp;&nbsp;&nbsp;";
    print "<a href='fab_lev_wijzigen.php' target='main'><b>".A5."</b></a>";
    print "</div>";
}
else {
    $sup = "<table border='0' align='center' class='TABLE' cellspacing='0' cellpadding='10' width='700'><tr>";
    $sup.= "<form action='fab_lev_wijzigen.php' method='post'>";
    $sup.= "<td align='center'>";
    if($row['fournisseur_ou_fabricant']=="fournisseur") $supplierName = FOURN; else $supplierName = FABR;
    $sup.= DESF1." ".$supplierName." <b>".$row['fournisseurs_company']."</b>";
    $sup.= "<br><img src='im/zzz.gif' width='1' height='3'><br>";
    $sup.= "<span class='fontrouge'><b>".VEUI1."</b></span>";
    $sup.= "<p align='center'>";
    $sup.= "<INPUT TYPE='submit' class='knop' VALUE='".RETO1."'>";
    $sup.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
    $sup.= "<a href='artikel_wijzigen.php'>".MODI1."</a>";
    $sup.= "</p>";
    $sup.= "</td>";
    $sup.= "</form>";
    $sup.= "</tr></table>";
    print $sup;
    print "<p align='center'>";
    print "<table border='0' align='center' class='TABLE' cellspacing='0' cellpadding='10' width='700'><tr><td><b>".ARTI." ".$row['fournisseurs_company']."</b> :<br><br>";
    $articlesCor = implode("|",$articlesCorArray);
    $articlesCor = str_replace("|", "<br>", $articlesCor);
    print $articlesCor;
    print "</p></tr></td></table>";
}
}

if(isset($_GET['page']) and $_GET['page'] == "delete") {
   	$delete = mysql_query("DELETE FROM fournisseurs WHERE fournisseurs_id='".$_GET['id']."'");
	print "<p align='center'><table border='0' align='center' class='TABLE' cellspacing='0' cellpadding='10' width='700'><tr><td><center>";
	print A2." <b>".strtoupper($row['fournisseurs_company'])."</b> ".A6.".<br>";
	print "<form action='fab_lev_wijzigen.php' method='post'><p align='center'><INPUT TYPE='submit' class='knop' VALUE='".A7."'></p></form>";
      print "</p></tr></td></table>";
}
?>
</body>
</html>
