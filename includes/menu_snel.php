<?php
$resultSSS = mysql_query("SELECT categories_id, parent_id, categories_visible, categories_noeud, categories_name_".$_SESSION['lang']." 
                            FROM categories 
                            WHERE categories_visible = 'yes' 
                            AND categories_id != '0' 
                            ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC") or die (mysql_error());
$id = 0;
$i = 0;
$resultSSSNum = mysql_num_rows($resultSSS);

if($resultSSSNum>0) {
	while($message = mysql_fetch_array($resultSSS)) {
	    $papa = $message['categories_id'];
	    $fils = $message['parent_id'];
	    $visible = $message['categories_visible'];
	    $noeud = $message['categories_noeud'];
	    if($message['categories_visible']=='yes') $visible="yes"; else $visible="<span color=red><b>No</b></span>";
	    if($message['categories_noeud']=="B") $titre = $message['categories_name_'.$_SESSION['lang']]; else $titre=$message['categories_name_'.$_SESSION['lang']];
	    $data[] = array($papa,$fils,$titre,$visible,$noeud);
	}
}

 
$smenu_var2 = mysql_query("SELECT fournisseurs_company, fournisseurs_id
                         FROM fournisseurs
                         WHERE fournisseur_ou_fabricant = 'fabricant'
                         ORDER BY fournisseurs_company
                         ASC");

while($smenu2 = mysql_fetch_array($smenu_var2)) {
   $comp[] = array($smenu2['fournisseurs_id'],$smenu2['fournisseurs_company']);
}

                         





print "<div class='raised' style='width:".$larg_rub."'>
<b class='top'><b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b></b>
<div class='boxcontent'>";
               
print "<table border='0' cellspacing='0' cellpadding='2' class='moduleQuick'>";
print "<tr>";

// Image top menu rapide
print "<td height='".$hauteurTitreModule."' class='moduleQuickTitre contentTop' align='center' style='width:".$larg_rub."'>";
print "<img src='im/lang".$_SESSION['lang']."/menu_rapide.png'>";
print "</td>";
print "</tr>";


print "<tr>";
// Categories

print "<form action= ''>";
print "<td><div><img src='im/zzz.gif' height='1' width='1'></div></td></tr><tr>";
print "<td>";
print "&nbsp;";

print "<select style='width:95%;' wrap='virtual' name='path' onChange='sendIt(this.options[selectedIndex].value)' >";
print "<option value='cataloog.php'>".LA_BOUTIQUE."</option>";
print "<option value=''>&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</option>";

recur($data,"0","0",$_SESSION['lang']);
print "</select>";

print "</td>";
print "</form>";	
print "</tr>";

print "<tr>";
// Marques
print "<form action=''>";
print "<td>";
print "&nbsp;";
print "<select style='width:95%;' name='path' onChange='sendIt(this.options[selectedIndex].value)' >";
print "<option value=''>".TOUTES_MARQUES."</option>";
print "<option value=''>&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</option>";
$compNb = count($comp)-1;
for($ccc=0; $ccc<= $compNb; $ccc++) {
      $comp[$ccc][1] = adjust_text($comp[$ccc][1],$maxCarQuick,"..");
      if(isset($_GET['authorid']) AND $comp[$ccc][0] == $_GET['authorid']) {$ddd="selected";} else {$ddd="";}
      print "<option value='list.php?authorid=".$comp[$ccc][0]."&action=e&target=author' ".$ddd.">".$comp[$ccc][1]."</option>";
     }
print "</select>";
print "<div><img src='im/zzz.gif' height='5' width='1'></div>";
print "</td>";
print "</form>";	
print "</tr>";
print "</table>";

print "</div>
<b class='bottom'><b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b></b>
</div>";
?>


<br>
