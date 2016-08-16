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

$query = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                      FROM categories
                      WHERE categories_noeud = 'L'
                      ORDER BY categories_name_".$_SESSION['lang']."");
$queryNum = mysql_num_rows($query);

 
function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3) {
global $c;
  if($c=="#FFFFFF") $c = "#FFFFFF"; else $c = "#FFFFFF";
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {
    if($tab[$x][1]==$pere) {
       print "</tr><tr bgcolor='".$c."'>";
       print "<td>";
       print espace3($rang3).$tab[$x][2];
       print "</td>";
       print "<td>";
       print "<div align='center'>".$tab[$x][3]."</div>";
       print "</td>";
       recur($tab,$tab[$x][0],$rang3+1);
    }
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
<p align="center" class="largeBold"><?php print A1;?></p>
<table border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700" ><tr>
   <td><center>
      &bull;&nbsp;<a href='import/artikelen/index.php'><?php print IMPORT;?></a>.
   </td>
</table>

<p align="center"><span align="center" style="color:#CC0000"><b><?php print SELECT_CAT;?></b></span></p>

<form action="artikel_toevoegen_stap2.php" method="GET">
<table width="700" border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE">
<tr>
        <td></td>
        <td align=center>
                <select name="group">
                <option name="group" value="no"><?php print A2;?>...</option>
                <option name="group" value="no">---</option>
                <?php
                        if($queryNum > 0) {
                          while ($myrow = mysql_fetch_array($query)) {
                                 $query2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                                                        FROM categories
                                                        WHERE categories_id = '".$myrow['parent_id']."'");
                          $a =  mysql_fetch_array($query2);
                          if(!empty($a['categories_name_'.$_SESSION['lang']]) and $a['categories_name_'.$_SESSION['lang']] !== "Menu") $sous = "[".$a['categories_name_'.$_SESSION['lang']]."]"; else $sous = "";
                                  print "<option name='group' value='".$myrow['categories_id']."'>".strtoupper($myrow['categories_name_'.$_SESSION['lang']])." $sous</option>";
                          }
                        }
                ?>
                </select>&nbsp;&nbsp<input type='submit' class='knop' value='<?php print A3;?>'>
        </td>
</tr>
<tr>
<td colspan="2">
<?php
if($queryNum == 0) {
    print "<div align='center' class='fontrouge'><b>Er zijn geen sub-categories aanwezig</b></div>";
}
else {
    print "";
}
?>
</td>
</tr>
</table>
</form>

<p align="center"><a href="categorie_toevoegen.php"><?php print A4;?></a></p>
<?php
 
$result = mysql_query("SELECT * FROM categories
                       ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($result)) {
	$papa = $message['categories_id'];
	$fils = $message['parent_id'];
	$visible = $message['categories_visible'];
	if($message['categories_visible']=='yes') $visible=A6; else $visible="<span style='color:red'><b>".A7."</b></span>";
	if($message['categories_noeud']=="B") $titre = "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>"; else $titre="<a href='artikel_toevoegen_stap2.php?group=".$message['categories_id']."'>".$message['categories_name_'.$_SESSION['lang']]."</a>";
	$data[] = array($papa,$fils,$titre,$visible);
}
if($queryNum>0) {
print "<table border='0' cellpadding='2' cellspacing ='5' align='center' class='TABLE' width='700'";
print "<tr>";
print "<td height='19' align='left'><b>".A8."</b></td>";
print "<td height='19' align='center'><b>".A9."</b></td>";
recur($data,"0","0");
print "</tr>";
print "</table>";
print "<br>";
}
?>
<br><br><br>
  </body>
  </html>
