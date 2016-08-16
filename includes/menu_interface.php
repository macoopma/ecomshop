<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

                 <table border="0" cellspacing="0" cellpadding="2" class="moduleInterface">
                 <tr>
                 <td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleInterfaceTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
                     <img src="im/lang<?php print $_SESSION['lang'];?>/menu_interface.png">
                 </td>
                 </tr>
  <tr>
  <td><div><img src='im/zzz.gif' height="1" width="1"></div></td></tr><tr>
  <form action="<?php print $url_id10;?>" method="post">
    <td>


 <?php

  if(empty($_SESSION['userInterface']) and $activerCouleurPerso=="oui") {$sel17a = "selected";}
  if(empty($_SESSION['userInterface']) and $activerCouleurPerso=="non") {$_SESSION['userInterface'] = $colorInter;}
  if($_SESSION['userInterface'] == "Noir-Black") $sel11a = "selected"; else $sel11a = "";
  if($_SESSION['userInterface'] == "Jaune-Yellow") $sel13a = "selected"; else $sel13a = "";
  if($_SESSION['userInterface'] == "Blanc-White") $sel14a = "selected"; else $sel14a = "";
  if($_SESSION['userInterface'] == "Bleu-Blue") $sel15a = "selected"; else $sel15a = "";
  if($_SESSION['userInterface'] == "Rose-Pink") $sel16a = "selected"; else $sel16a = "";
  if($_SESSION['userInterface'] == "perso") $sel17a = "selected"; else $sel17a = "";
  if($_SESSION['userInterface'] == "scss") $sel18a = "selected"; else $sel18a = "";
  if($_SESSION['userInterface'] == "scss2") $sel20a = "selected"; else $sel20a = "";
  if($_SESSION['userInterface'] == "Gris-Grey") $sel19a = "selected"; else $sel19a = "";

  print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
  print "<td width='1'><img src='im/fleche_right.gif'></td>";
  print "<td>";
  print "<select name='userInterface1' style='width:100%;'>";
  print "<option class='grey2' name='userInterface1' value='scss' ".$sel18a.">SCSS</option>";
  print "<option class='yellow' name='userInterface1' value='Jaune-Yellow' ".$sel13a.">".JAUNE."</option>";
  print "<option class='grey2' name='userInterface1' value='scss2' ".$sel20a.">SCSS 2</option>";
  print "<option class='white' name='userInterface1' value='Blanc-White' ".$sel14a.">".BLANC."</option>";
  print "<option class='blue' name='userInterface1' value='Bleu-Blue' ".$sel15a.">".BLEU."</option>";
  print "<option class='grey' name='userInterface1' value='Gris-Grey' ".$sel19a.">".GRIS."</option>";
  print "<option class='black' name='userInterface1' value='Noir-Black' ".$sel11a.">".NOIR."</option>";
  print "<option class='pink' name='userInterface1' value='Rose-Pink' ".$sel16a.">".ROSE."</option>";
  if($activerCouleurPerso=="oui") {
  print "<option class='red' name='userInterface1' value='perso' ".$sel17a.">".PERSO."</option>";
  }
  print "</select>";
  print "</td>";
  print "<td width='1' align='right'><INPUT style='BACKGROUND:none; border:0px' align='absmiddle' type='image' src='im/ok.gif'></td>";
  print "<td width='1'>&nbsp;</td>";
  print "</tr></table>";

  print "<div><img src='im/zzz.gif' height='1' width='1'></div>";

 
  print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
  print "<td width='1'><img src='im/fleche_right.gif'></td>";
  print "<td width='40'><input type='text' name='widthUser' size='5' value='".$_SESSION['storeWidthUser']."'></td>";
  print "<td align='left'><INPUT style='BACKGROUND:none; border:0px' align='absmiddle' type='image' src='im/ok.gif'></td>";
   $aaa = $_SESSION['storeWidthUser']+100;
   $aaa2 = $_SESSION['storeWidthUser']-100;
  print "<td align='center' valign='middle'>";
  print "<a href='".$url_id10.$slash."widthUser=".$aaa."'><img src='im/width_up.gif' border='0'></a>";
  print "&nbsp;";
  print "<a href='".$url_id10.$slash."widthUser=".$aaa2."'><img src='im/width_down.gif' border='0'></a>";
  print "</td>";
  
  print "<td width='1'>&nbsp;</td>";
  print "</tr></table>";
  print "<div><img src='im/zzz.gif' height='3' width='1'></div>";
  ?>
  
    </td>
    </form>
  </tr>
</table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
