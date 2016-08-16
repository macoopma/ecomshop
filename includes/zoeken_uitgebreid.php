<?php

 
$smenu_varq = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id
                         FROM categories
                         WHERE categories_visible = 'yes'
                         AND categories_noeud = 'L'
                         ORDER BY categories_name_".$_SESSION['lang']."");

while($smenuq = mysql_fetch_array($smenu_varq)) {
    $scatq[] = array($smenuq['categories_name_'.$_SESSION['lang']],$smenuq['categories_id']);
}

$smenu_var2q = mysql_query("SELECT fournisseurs_company, fournisseurs_id
                         FROM fournisseurs
                         WHERE fournisseur_ou_fabricant = 'fabricant'
                         ORDER BY fournisseurs_company
                         ASC");

while($smenu2q = mysql_fetch_array($smenu_var2q)) {
   $compq[] = array($smenu2q['fournisseurs_id'],$smenu2q['fournisseurs_company']);
}
                  
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'>";
 
print "<tr>";
print "<td>";
print "<img src='im/zzz.gif' width='1' height='1'><br><img src='im/fleche_right.gif'>&nbsp;";
print "<select style='width:90%;' name='advCat'>";
print "<option value='all'>".TOUTES_CAT2."</option>";
print "<option value='all'>----------</option>";
$scatqNb = count($scatq)-1;
for($ccc=0; $ccc<= $scatqNb; $ccc++) {
      $scatq[$ccc][0] = strtolower(adjust_text($scatq[$ccc][0],$maxCarQuick,".."));
      print "<option value='".$scatq[$ccc][1]."'>".$scatq[$ccc][0]."</option>";
}
print "</select>";
print "</td>";
print "</tr>";

 
print "<tr>";
print "<td>";
print "<img src='im/zzz.gif' width='1' height='5'><br><img src='im/fleche_right.gif'>&nbsp;";
print "<select style='width:90%;' name='advComp'>";
print "<option value='all'>".TOUTES_MARQUES."</option>";
print "<option value='all'>---</option>";
$compqNb = count($compq)-1;
for($ccc=0; $ccc<= $compqNb; $ccc++) {
      $compq[$ccc][1] = strtolower(adjust_text($compq[$ccc][1],$maxCarQuick,".."));
      print "<option value='".$compq[$ccc][0]."'>".$compq[$ccc][1]."</option>";
     }
print "</select>";
print "</td>";
print "</tr>";

 
print "<tr>";
print "<td>";
print "<img src='im/zzz.gif' width='1' height='5'><br><img src='im/fleche_right.gif'>&nbsp;";
print "<select style='width:90%;' name='sep'>";
print "<option value='and'>".TOUS_LES_MOTS."</option>";
print "<option value='or'>".NIMPORTE_QUEL_MOT."</option>";
print "<option value='is'>".PHRASE_EXACTE."</option>";
print "</select>";
print "</td>";
print "</tr>";

print "</table>";
print "<img src='im/zzz.gif' width='1' height='5'><br>";
?>
