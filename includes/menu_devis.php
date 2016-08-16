<?php


?>
<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleDevis">
<tr>
<td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleDevisTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
<img src="im/lang<?php print $_SESSION['lang'];?>/menu_devis.png">
</td>
</tr><tr>
<td colspan="4"><div><img src='im/zzz.gif' height="1" width="1"></div></td>
</tr>
<tr>
<?php
if(isset($_SESSION['devisNumero'])) {
    print "<td align='center' class='fontrouge'>".DEVIS." N&deg; ".$_SESSION['devisNumero']."
            <tr>
            <td align='center'><a href='".$url_id10.$slash."var=session_destroy'><b>".CLOSE_DEVIS."</b></a>
            <br>
            <img src='im/zzz.gif' height='5'>";
    print "</td>";
}
else {
    print "<form action='caddie.php' method='POST'>";
    print "<td width='1'><img src='im/fleche_right.gif'></td>";
    print "<td><input type='text' name='devisNumero' style='width:95%' value='".NUMERO_DEVIS."' onblur=\"if(this.value=='') {this.value='".NUMERO_DEVIS."'}\" onFocus=\"if(this.value=='".NUMERO_DEVIS."') {this.value=''}\"></td>";
    print "<td align='left'><INPUT style='BACKGROUND:none; border:0px' type='image' src='im/ok.gif'></td>";
    print "<td>&nbsp;</td>";
    print "</form>";
}
?>
</tr>
</table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
