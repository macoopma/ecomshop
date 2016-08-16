<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<?php

$message = "";
?>

<table border="0" cellspacing="0" cellpadding="2" class="moduleSubscribe">
<form action= "pagina_box.php?module=newsletter" method="POST">
<tr>
<td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleSubscribeTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
   <img src="im/lang<?php print $_SESSION['lang'];?>/menu_subscribe.png">
</td>
</tr>
<tr>
    <td colspan="4">
        <div><img src='im/zzz.gif' height="1" width="1"></div>
    </td>
</tr>
<tr>
    <td width="1"><img src="im/fleche_right.gif"></td>
    <td>
      <input type="text" name="email" style="width:95%" value="<?php print VOTRE;?> e-mail" onblur="if(this.value=='') {this.value='<?php print VOTRE;?> e-mail'}" onFocus="if(this.value=='<?php print VOTRE;?> e-mail') {this.value=''}">
      <INPUT TYPE="hidden" name="quoi" VALUE="ok">
    </td>
    <td align="left"><INPUT style="BACKGROUND:none; border:0px" align="absmiddle" type="image" src="im/ok.gif"></td>
    <td>&nbsp;</td>
</tr>
</form>
</table>



</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>

<br>
