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
$c = "";
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
$result = mysql_query("SELECT * FROM banner");

if(mysql_num_rows($result) > 0) {
    $toDay = date('Y-M-d H:i:s');
    $hoy = mktime (date('H'),date('m'),date('s'),date('m'),date('d'),date('Y'));
    

    print "<table border='0' cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'>";
    print "<tr height='35'>";
    print "<td align='left'><b>Id</b></td>";
    print "<td align='left'><b>".A2."</b></td>";
    print "<td align='left'><b>".A3."</b></td>";
    print "<td align='left'><b>".A4."</b></td>";
    print "<td align='center'><b>".VISIBLE."</b></td>";
    print "<td align='center'><b>".ETAT."</b></td>";
    print "<td align='center'>&nbsp;</td>";
    print "<td align='center'>&nbsp;</td>";
    
    while($banner = mysql_fetch_array($result)) {
        if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
        $oubah = explode(" ",$banner['banner_date_end']);
        $explodeDateEnd = explode("-",$oubah[0]);
        $explodeTimeEnd = explode(":",$oubah[1]);
        $endDate = mktime ($explodeTimeEnd[0],$explodeTimeEnd[1],$explodeTimeEnd[2],$explodeDateEnd[1],$explodeDateEnd[2],$explodeDateEnd[0]);
        $visible = ($banner['banner_visible']=='yes')? "<img src='im/val.gif' title='".OUI."'>" : "<img src='im/no_stock.gif' title='".NON."'>";
        $st = ($endDate >= $hoy)? "<img src='im/noPassed.gif' title='".EN_COURS."'>" : "<img src='im/passed.gif' title='".EXPIRE."'>";
        
        print "</tr><tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td>".$banner['banner_id']."</td>";
        print "<td>".$banner['banner_desc']."</td>";
        print "<td>".$banner['banner_vue']."</td>";
        print "<td>".$banner['banner_hit']."</td>";
        print "<td align='center'>".$visible."</td>";
        print "<td>";
        print "<div align='center'>".$st."</div>";
        print "</td>";
        print "<td>";
        print "<div align='center'><a href='banner_wijzigen_2.php?id=".$banner['banner_id']."' style='background:none; decoration:none'><img src='im/details.gif' border='0' title='".A5."'></a></div>";
        print "</td>";
        print "<td>";
        print "<div align='center'><a href='banner_verwijderen.php?id=".$banner['banner_id']."' style='background:none; decoration:none'><img src='im/supprimer.gif' border='0' title='".A6."'></a></div>";
        print "</td>";
    }
    print "</tr>";
    print "</table>";
}
else {
   print "<table border='0' cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'><tr><td><p align='center' class='fontrouge'><b>".A50."</b></tr></td></table>";
}
?>
</body>
</html>
