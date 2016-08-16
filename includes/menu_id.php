<?php

?>
<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleId">
<tr>
<td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleIdTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
<a href="mijn_account.php"><img src="im/lang<?php print $_SESSION['lang'];?>/menu_id.png" border="0" title="<?php print VOTRE_COMPTE;?>" alt="<?php print VOTRE_COMPTE;?>"></a>
</td>
</tr>
<tr>
<td>
	<div><img src='im/zzz.gif' height="1" width="1"></div>
</td>
</tr><tr>
<?php
if(isset($_SESSION['openAccount'])) {
    print "<td align='center'>";
	print "<span class='fontrouge'>".VOUS_ETES_IDENTIFIE."</span>";
	print "</td>";
    print "</tr><tr>";
    print "<td align='center'><b><a href='mijn_account.php' >".VOTRE_COMPTE."</a></b></td>";
    print "</tr><tr>";
    print "<td align='center'>";
    if(!isset($_SESSION['devisNumero'])) {
            if(!isset($_SESSION['recup'])) {
         		$closeAccount = ($vb2c=="oui")? '' : '&var=session_destroy';
				print "<a href='mijn_account.php?act=closeAccount".$closeAccount."'><b>".FERMER_LE_COMPTE."</b></a>";
			}
			else {
				print "<span class='FontGris'><b>".FERMER_LE_COMPTE."</b></span>";
			}
    }
    else {
      print "<span class='FontGris'><b>".FERMER_LE_COMPTE."</b></span>";
    }
    print "<div><img src='im/zzz.gif' height='5'></div>";
    print "</td>";
}
else {
    print "<form action='mijn_account.php' method='POST'>";
    print "<td width='1'><img src='im/fleche_right.gif'></td>";
 
    print "<td>";
    print "<input type='text' name='account' style='width:95%;' value='".NUMERO_DE_CLIENT."' onblur=\"if(this.value=='') {this.value='".NUMERO_DE_CLIENT."'}\" onFocus=\"if(this.value=='".NUMERO_DE_CLIENT."') {this.value=''}\">";
    print "</td>";
    print "<td width='1'>&nbsp;</td>";
    print "<td>&nbsp;</td>";
    print "</tr><tr>";
  
    print "<td width='1'><img src='im/fleche_right.gif'></td>";
    print "<td><input type='text' name='email' style='width:95%' value='".ADRESSE_EMAIL."' onblur=\"if(this.value=='') {this.value='".ADRESSE_EMAIL."'}\" onFocus=\"if(this.value=='".ADRESSE_EMAIL."') {this.value=''}\"></td>";
            if(isset($_SESSION['devisNumero'])) {
               print "<td align='right'>";
               print "<img src='im/zzz_gris.gif' width='10' height='10'>";
               print "</td>";
            }
            else {
               print "<td align='right' width='1'>";
               print "<INPUT style='BACKGROUND:none; border:0px' align='absmiddle' type='image' src='im/ok.gif'>";
               print "</td>";
            }
    print "<td>&nbsp;</td>";
    print "</tr><tr>";
	print "<td colspan='4'>";
	print "<img src='im/zzz.gif' height='1' width='1'>";
	print "</td>";
    print "</form>";
}
?>


<!--
<table border="0" cellspacing="0" cellpadding="0" align="center" width="30"><tr>
	<td align='center'>
	<div id="upA"><a href="javascript:void(0);" onClick="javascript: menu_id.style.display='none'; downA.style.display='block'; upA.style.display='none';"><img src="im/_im_plus.gif" border="0"></a></div>
	<div id="downA"><a href="javascript:void(0);" onClick="javascript: menu_id.style.display='block'; upA.style.display='block'; downA.style.display='none';" style="display:none"><img src="im/_im_moins.gif" border="0"></a></div>
	</td>
</tr>
</table>
-->

</tr></table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
