<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = "";
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td><td valign="top">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
// header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}
?>

    <td colspan="3" >

</td>
</tr>

<tr valign="top">
<td colspan="3">


      <table width="100%" border="0" cellpadding="0" cellspacing="5" align="center">
        <tr>
          <td valign="top">


            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top" class="titre">
                                  </td>
              </tr>
              <tr>
                <td valign="top" align="center">
<?php
         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         $zoneExplode = explode("|",$zoneZ['free_shipping_zone']);
         $zoneExplodeNb = count($zoneExplode);
         for($i=0; $i<=$zoneExplodeNb; $i++) {
            if(!empty($zoneExplode[$i])) $freeZone[] = $zoneExplode[$i];
         }
         
         $zoneNum = count($freeZone);

         for($i=0; $i<=$zoneNum-1; $i++) {
             $query = mysql_query("SELECT countries_name FROM countries WHERE countries_shipping = '".$freeZone[$i]."'");

             while($pays = mysql_fetch_array($query)) {
             $freeCountries[] = $pays['countries_name'];
             }
         }
         sort($freeCountries);
         $n=1;

         print "<table width='400' border='0' cellspacing='0' cellpadding='3' class='TABLE1'>";
         print "<tr bgcolor='#FFFFFF' height='30'><td colspan='2'>";
                print "<table width='400' border='0' cellspacing='0' cellpadding='3' class='TABLETopTitle'>";
                print "<td>";
                print "<b>".FREE_SHIPPING_COUNTRIES.":</b>";
                print "</td>";
                print "<td align='right'>";
                print "<b><a href='javascript:window.close()'>X</a></b>";
                print "</td>";
                print "</tr>";
                print "</table>";
$c="";
$freeCountriesNb = count($freeCountries)-1;
         for($i=0; $i<=$freeCountriesNb; $i++) {
             if($c=="#E8E8E8") $c = "#F6F6EB"; else $c = "#E8E8E8";
          print "<tr bgcolor='".$c."'><td>".$n++."</td><td>".$freeCountries[$i]."</td></tr>";
         }
         print "</table>";
?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>

</td>
</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</tr>
</table>
</body>
</html>
