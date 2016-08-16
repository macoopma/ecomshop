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

if(empty($_GET['orderf']))  $_GET['orderf'] = "weight";
if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}
$color = "#FFFFFF";
if(isset($_GET['shipId'])) $_POST['shipId']=$_GET['shipId'];
 
if(isset($_POST['update']) and $_POST['update'] == A1) {
        $num = count($_POST['yo'][1]);
        $yoNb = count($_POST['yo']);
      for($i=1; $i<=$yoNb; $i++) {
          for($i1=1; $i1<=$num; $i1++) {
              $update = mysql_query("UPDATE ship_price
                           SET
                           zone1='".$_POST['yo'][$i][2]."',
                           zone2='".$_POST['yo'][$i][3]."',
                           zone3='".$_POST['yo'][$i][4]."',
                           zone4='".$_POST['yo'][$i][5]."',
                           zone5='".$_POST['yo'][$i][6]."',
                           zone6='".$_POST['yo'][$i][7]."',
                           zone7='".$_POST['yo'][$i][8]."',
                           zone8='".$_POST['yo'][$i][9]."',
                           zone9='".$_POST['yo'][$i][10]."',
                           zone10='".$_POST['yo'][$i][11]."',
                           zone11='".$_POST['yo'][$i][12]."',
                           zone12='".$_POST['yo'][$i][13]."',
                           weight='".$_POST['yo'][$i][1]."'
                           WHERE id='".$_POST['yo'][$i][0]."'
                           AND livraison_id='".$_POST['shipId']."'
                           ");
          }
      }
      $message = "<div align='center'><span class='fontrouge'><b>".A2."</b></span></div><br>";
}

if(isset($_POST['add']) and $_POST['add'] == A3) {
   $query = mysql_query("SELECT * FROM ship_price WHERE weight='".$_POST['weight']."' AND livraison_id='".$_POST['shipId']."'");
   $ResultNum = mysql_num_rows($query);
   if($ResultNum>0) {
      $message2 = "<div align='center'><span class='fontrouge'><b>".A4."</b></span></div>";
      $ok = "notok";
   }
   else {
      $ok = "ok";
   }
   if(empty($_POST['weight'])) {
      $message2 = "<div align='center'><span class='fontrouge'><b>".A5."</b></span></div>";
      $ok1 = "notok";
   }
   else {
      $ok1 = "ok";
   }

   if($ok == "ok" AND $ok1=="ok") {
      mysql_query("INSERT INTO ship_price (weight,zone1,zone2,zone3,zone4,zone5,zone6,zone7,zone8,zone9,zone10,zone11,zone12,livraison_id )
                   VALUES ('".$_POST['weight']."','".$_POST['zone1']."','".$_POST['zone2']."','".$_POST['zone3']."','".$_POST['zone4']."','".$_POST['zone5']."','".$_POST['zone6']."','".$_POST['zone7']."','".$_POST['zone8']."','".$_POST['zone9']."','".$_POST['zone10']."','".$_POST['zone11']."','".$_POST['zone12']."','".$_POST['shipId']."')");
      $message2 = "<div align='center'><span class='fontrouge'><b>De update werd succesvol toegevoegd</b></span></div>";
   }
}

 
if(isset($_GET['delete_id']) AND !empty($_GET['delete_id'])) {
        mysql_query("DELETE FROM ship_price WHERE id='".$_GET['delete_id']."' AND livraison_id='".$_GET['shipId']."'");
        $message3 = "<div align='center'><span class='fontrouge'><b>".A6." ".$_GET['delete_id']." ".A7."</b></span></div><br>";
}


if(isset($_GET['action']) AND $_GET['action']=="delete") {
   if(!isset($_GET['confirm'])) {
     $shipNameRequestZ = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$_GET['shipId']."'");
     $shipNameZ = mysql_fetch_array($shipNameRequestZ);
     $nomSh =  strtoupper($shipNameZ['livraison_nom_'.$_SESSION['lang']]);
     $messageZ = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align='center'>";
     $messageZ.= "<span style='color:#CC0000'><b>".ETES_VOUS_SUR." ".$nomSh." ?</b></span>";
     $messageZ.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
     $messageZ.= "<a href='verzendprijzen.php?action=delete&confirm=yes&shipId=".$_GET['shipId']."'><b>".OUI."</b></a> | <a href='verzendprijzen.php?shipId=".$_GET['shipId']."'><b>".NON."</b></a>";
     $messageZ.= "</td></tr></table>";
   }
   else {
  
      mysql_query("DELETE FROM ship_price WHERE livraison_id='".$_GET['shipId']."'") or die (mysql_error());
      $messageZ = "<span style='color:#CC0000'><b>".TARIFS_SUPPRIMES."</b></span>";
   }
}


if(isset($_GET['action']) AND $_GET['action']=="import") {
   if(!isset($_GET['confirm'])) {
     $messageZ = "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align='center'>";
     $messageZ.= "<b>".FICHIER_CSV." : <a href='import/importeer_verzendprijzen.txt' target='_blank'>admin/import/importeer_verzendprijzen.txt</a></b>";
     $messageZ.= "<br>[".A9."(gr) zone1 zone2 zone3 zone4 zone5 zone6 zone7 zone8 zone9 zone10 zone11 zone12] - ".SEPARATEUR." TAB";
     $messageZ.= "<br><br>".ATTENTION;
     $messageZ.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
     $messageZ.= "<span style='color:#CC0000'>".SOUHAITEZ_VOUS_POURSUIVRE."</span>";
     $messageZ.= "<br><img src='im/zzz.gif' width='1' height='5'><br>";
     $messageZ.= "<a href='verzendprijzen.php?action=import&confirm=yes&shipId=".$_GET['shipId']."'><b>".OUI."</b></a> | <a href='verzendprijzen.php?shipId=".$_GET['shipId']."'><b>".NON."</b></a>";
     $messageZ.= "</td></tr></table>";
   }
   else {
                      
                      mysql_query("DELETE FROM ship_price WHERE livraison_id='".$_GET['shipId']."'");
                      
                      $separateur = "\t";
                      $bdd = $bddName;
                      $table = "ship_price";
                      $fichier = "import/importeer_verzendprijzen.txt";
                      
                      
                      if(file_exists($fichier)) {
                      if($fp = fopen($fichier, "r")) {
                      
                                while (!feof($fp)) {
                                       
                                       $ligne = fgets($fp,filesize($fichier));
                                       $liste = explode($separateur ,$ligne);
                                       $liste = str_replace(",",".",$liste);
                                       if(empty($liste[0])) {break;}
                                       
                                            $weight  =   $liste[0];
                                            $zone1   =   $liste[1];
                                            $zone2   =   $liste[2];
                                            $zone3   =   $liste[3];
                                            $zone4   =   $liste[4];
                                            $zone5   =   $liste[5];
                                            $zone6   =   $liste[6];
                                            $zone7   =   $liste[7];
                                            $zone8   =   $liste[8];
                                            $zone9   =   $liste[9];
                                            $zone10  =   $liste[10];
                                            $zone11  =   $liste[11];
                                            $zone12  =   $liste[12];
                                            
                                            $query  = 'INSERT INTO '.$table.' SET
                                            weight  =\''.$liste[0].'\',
                                            zone1   =\''.$liste[1].'\',
                                            zone2   =\''.$liste[2].'\',
                                            zone3   =\''.$liste[3].'\',
                                            zone4   =\''.$liste[4].'\',
                                            zone5   =\''.$liste[5].'\',
                                            zone6   =\''.$liste[6].'\',
                                            zone7   =\''.$liste[7].'\',
                                            zone8   =\''.$liste[8].'\',
                                            zone9   =\''.$liste[9].'\',
                                            zone10  =\''.$liste[10].'\',
                                            zone11  =\''.$liste[11].'\',
                                            zone12  =\''.$liste[12].'\',
                                            livraison_id  =\''.$_GET['shipId'].'\'';
                                            
                                           $result= mysql_query($query);
                                 
                                       if(mysql_error()) {
                                           print "<p align='center'>Erreur dans la base de données : ".mysql_error();
                                           print "<br>".IMPORTATION_STOPPEE."</p>";
                                           exit();
                                        }
                                }
                            }
                            else {
                                print "<p align='center'>".OUVERTURE_FICHIER." <b>".$fichier."</b> ".IMPOSSIBLE." !</p>";
                                print "<p align='center'>".VOIR_CHMOD." <b>".$fichier."</b><br><a href='verzendprijzen.php'><b>".RETOUR."</b></a></p>";
                                exit;
                            }
                      }
                      else {
                       print "<p align='center'>".FICHIER." <b>".$fichier."</b> ".INTROUVABLE."</b></p>";
                       print "<p align='center'><a href='verzendprijzen.php'><b>".RETOUR."</b></a></p>";
                       exit();
                      }
                      fclose($fp);



      $messageZ = "<span style='color:#CC0000'><b>".IMPORT_OK."</b></span>";
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

<p align="center" class="largeBold"><?php print A8;?></p>
<?php
$shipCheck = mysql_query("SELECT COUNT(livraison_id) FROM ship_mode") or die (mysql_error());
$nb_ship = mysql_fetch_array($shipCheck); 

if($nb_ship[0] > 0) {

if(isset($_GET['u'])) print "<p align='center'>".VEUILLEZ_SELECTIONNER_MODE_DE_LIVRAISON."</p>";

if(isset($_POST['shipId'])) {
?>
<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr>
<td align="center">
<a href="verzendprijzen.php?action=delete&shipId=<?php print $_POST['shipId'];?>" target="main"><?php print SUPPRIMER_TOUS_LES_TARIFS;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="x_exporteer_verzendprijzen.php?shipId=<?php print $_POST['shipId'];?>" target="main"><?php print EXPORTER_LES_TARIFS;?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
<a href="verzendprijzen.php?action=import&shipId=<?php print $_POST['shipId'];?>" target="main"><?php print IMPORTER_LES_TARIFS;?></a>
</td>
</tr></table>
<?php
}
if(isset($messageZ)) print "<p align='center'>".$messageZ."</p>";

$ship = mysql_query("SELECT * FROM ship_mode ORDER BY livraison_nom_".$_SESSION['lang']." ASC");
if(mysql_num_rows($ship) > 0) {
    print "<form method='POST' action='verzendprijzen.php'>";
    print "<p align='center'><table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align='center'>";
    print "<select name='shipId'>";
    while($liv = mysql_fetch_array($ship)) {
        $livraisonArray[] = $liv['livraison_id'];
        if(isset($_POST['shipId']) AND $_POST['shipId']==$liv['livraison_id']) $sel='selected'; else $sel='';
        print "<option value='".$liv['livraison_id']."' ".$sel.">".$liv['livraison_nom_'.$_SESSION['lang']]."</option>";
    }
    print "</select>";
    print "&nbsp;<input type='submit' value='OK' class='knop'>";
    print "</tr></table>";
    print "</form>";
}

if(isset($_POST['shipId'])) {

$shipNameRequest = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$_POST['shipId']."'");
$shipName = mysql_fetch_array($shipNameRequest);
print "<table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align='center'>";
print "<div align='center' class='fontrouge'><b>-- ".strtoupper($shipName['livraison_nom_'.$_SESSION['lang']])." --</b></div>";
print "<br>";
print "<div align='center'><a href='verzendfirma_details.php?id=".$_POST['shipId']."'><b>".EDITER_MODIFIER." '".strtoupper($shipName['livraison_nom_'.$_SESSION['lang']])."'</b></a></div>";
print "<div align='center'><img src='im/zzz.gif' width='1' height='3'></div>";
## Voir liste des pays
$openLeg2 = "<div align='center' style='font-weight:bold'><a href=\"javascript:void(0);\" onClick=\"window.open('uitleg.php?open=liv&id=".$_POST['shipId']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
print $openLeg2.VOIR_LISTE_DES_PAYS." '".strtoupper($shipName['livraison_nom_'.$_SESSION['lang']])."'</a></div>";
print "</tr></table><br>";

$query = mysql_query("SELECT * FROM ship_price WHERE livraison_id = '".$_POST['shipId']."' ORDER BY ".$_GET['orderf']." ".$_GET['c1']."");
if(mysql_num_rows($query) > 0) {

if(isset($_GET['actionZone']) AND $_GET['actionZone']!=="") {
	$requestZZZ1 = mysql_query("SELECT countries_id, countries_name FROM countries WHERE countries_shipping='".$_GET['actionZone']."'") or die (mysql_error());
	if(mysql_num_rows($requestZZZ1)>0) {
		while($paysZZZ1 = mysql_fetch_array($requestZZZ1)) {
			$requestZZZ2 = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$paysZZZ1['countries_id']."|%' AND livraison_id='".$_POST['shipId']."'") or die (mysql_error());
			if(mysql_num_rows($requestZZZ2)>0) {
				$messageZZZZ1[] = "<div align='center' style='font-size:13px; color:#FFFFFF;'><span style='color:#FFFF00'><b>".$paysZZZ1['countries_name']."</b></span> ".EST_EN." <b>".$_GET['actionZone']."</b> -> ".AUCUN_TARIF_POUR_CETTE_ZONE."</div>";
			}
		}
		if(isset($messageZZZZ1) AND count($messageZZZZ1)>0) {
			print "<div align='center' style='background:#888888; padding:10px; border:#FFFF00 2px solid';>";
			foreach($messageZZZZ1 AS $items) {
				print $items;
			}
						print "<div align='center'><br><i><span style='color:#FFFF00'>".AJOUTEZ_TARIFS_DANS_ZONE." <u><b>".OU."</b></u> </span><a href='verzendfirma_details.php?id=".$_POST['shipId']."' style='text-decoration:none; background:none'><span style='color:#FFFF00'>".RETIREZ_LES_PAYS."</span></a></i></div>";
			print "</div>";
		}
		print "<br>";
	}
}
?>

<form action="<?php print $_SERVER['PHP_SELF'];?>" method="POST">
<input type="hidden" name="shipId" value="<?php print $_POST['shipId'];?>">

<table align="center" border="0" cellpadding="4" cellspacing="0" class="TABLE">
<tr height="30" bgcolor="<?php print $color;?>" height='35'>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=weight&c1=".$_GET['c1']."&shipId=".$_POST['shipId']."";?>"><?php print A9;?> (Gr)</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone1&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone1</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone2&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone2</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone3&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone3</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone4&c1=".$_GET['c1']."&shipId=".$_POST['shipId']."";?>">Zone4</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone5&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone5</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone6&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone6</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone7&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone7</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone8&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone8</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone9&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone9</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone10&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone10</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone11&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone11</a></b></td>
    <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=zone12&c1=".$_GET['c1']."&shipId=".$_POST['shipId'].""; ?>">Zone12</a></b></td>
    <td width="25" align="center">&nbsp;</td>
</tr>
<?php
$u=0;
$c="";
while ($row = mysql_fetch_array($query)) {
    if($c=="#CCCC00") $c = "#FFFFCC"; else $c = "#FFFFCC";

    if($row['zone1']==0) $row['zone1']="";
    if($row['zone2']==0) $row['zone2']="";
    if($row['zone3']==0) $row['zone3']="";
    if($row['zone4']==0) $row['zone4']="";
    if($row['zone5']==0) $row['zone5']="";
    if($row['zone6']==0) $row['zone6']="";
    if($row['zone7']==0) $row['zone7']="";
    if($row['zone8']==0) $row['zone8']="";
    if($row['zone9']==0) $row['zone9']="";
    if($row['zone10']==0) $row['zone10']="";
    if($row['zone11']==0) $row['zone11']="";
    if($row['zone12']==0) $row['zone12']="";

    $u = $u+1;
    print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FF9900';\" onmouseout=\"this.style.backgroundColor='';\">";
    print "<input type='hidden' name='yo[".$u."][]' value='".$row['id']."'>";
    print "<td bgcolor='#CCFF99' align='center'><input type='text' size='7' class='vullen' name='yo[$u][]' value='".$row['weight']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' class='vullen' name='yo[$u][]' value='".$row['zone1']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone2']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone3']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone4']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone5']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone6']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone7']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone8']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone9']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone10']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone11']."'></td>";
    print "<td align='left'><input type='text' size='3' class='vullen' name='yo[$u][]' value='".$row['zone12']."'></td>";
    print "<td bgcolor='".$color."' align='center'><a href='".$_SERVER['PHP_SELF']."?delete_id=".$row['id']."&shipId=".$_POST['shipId']."'><img src='im/supprimer.gif' border='0' title='".A10."'></a></td>";
	print "</tr>";
}
?>
</table>
<br>
<table align="center" border="0" cellpadding="4" cellspacing="0">
<tr>
<td><input type="submit" name="update" value="<?php print A1;?>" class='knop'></td>
</tr>
</table>
</form>

<?php
}
else {
    print "<table align='center'><tr>";
    print "<td align='center' style='background-color:#CC0000; color:#FFFFFF; font-size:13px; padding:8px;'>";
    print "<b>".AUCUN_TARIF."</b>";
    print "</td></tr></table>";
    print "<br>";
}
if(isset($message) and !empty($message)) print "<br>".$message;
if(isset($message3) and !empty($message3)) print "<br>".$message3;
?>



<form action="<?php print $_SERVER['PHP_SELF'];?>" method="POST">
<input type="hidden" name="shipId" value="<?php print $_POST['shipId'];?>">

<table align="center" border="0" cellpadding="3" cellspacing="0" class="TABLE">
<tr height="30" bgcolor="#FFFFFF">
    <td align="center"><b><?php print A9;?> (Gr)</b></td>
    <td align="center"><b>Zone 1</b></td>
    <td align="center"><b>Zone 2</b></td>
    <td align="center"><b>Zone 3</b></td>
    <td align="center"><b>Zone 4</b></td>
    <td align="center"><b>Zone 5</b></td>
    <td align="center"><b>Zone 6</b></td>
    <td align="center"><b>Zone 7</b></td>
    <td align="center"><b>Zone 8</b></td>
    <td align="center"><b>Zone 9</b></td>
    <td align="center"><b>Zone 10</b></td>
    <td align="center"><b>Zone 11</b></td>
    <td align="center"><b>Zone 12</b></td>
        </tr>
<tr  bgcolor="#00FFFF">

    <td align="center"><input type="text" class="vullen" size="3" name="weight"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone1"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone2"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone3"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone4"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone5"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone6"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone7"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone8"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone9"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone10"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone11"></td>
    <td align="center"><input type="text" class="vullen" size="3" name="zone12"></td>

</tr>
<tr bgcolor="#CCFFFF">
<td colspan="13" align="center" height="40" style="padding:5px">
    <input type="submit" name="add" value="<?php print A3;?>" class="knop">
</td>
</tr>
</table><br><br><br>

<?php
if(isset($message2) AND !empty($message2)) print "<br>".$message2;
?>
</form>

<?php
}
}
else {
    print AUCUN_MODE;
}
?>
</body>
</html>
