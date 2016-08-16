<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

                 <table border="0" cellspacing="0" cellpadding="2" class="moduleConverter">
                 <tr>
                 <td height='<?php print $hauteurTitreModule;?>' class="moduleConverterTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
                  <img src="im/lang<?php print $_SESSION['lang'];?>/menu_converter.png">
                 </td>
                 </tr>

<form onsubmit="return false;">
                <tr>
                <td><div><img src='im/zzz.gif' height="1" width="1"></div></td></tr><tr>
                <td  valign="top">


  &nbsp;&nbsp;&nbsp;<select name="currency" style="width:90%" onChange="calc(this.form)" onFocus="calc(this.form)" onClick="calc(this.form)">
    <option value="13.7603">Ostenreich ATS</option>
    <option value="40.3399">Belgie BEF</option>
    <option value="1.95583">Deutschland DEM</option>
    <option value="166.386">Espana ESP</option>
    <option value="5.94573">Finlandi FIM</option>
    <option value="6.55957">France FRF</option>
    <option value="0.787564">Ireland IEP</option>
    <option value="1936.27">Italia ITL</option>
    <option value="40.3399">Luxemburg LUF</option>
    <option value="2.20371">Nederland NLG</option>
    <option value="200.482">Portugal PTE</option>
    <option value="340.750">Greece GRD</option>
  </select>

  <div><img src='im/zzz.gif' height="2" width="1"></div>
  <img src="im/fleche_right.gif">&nbsp;
  <input name="euro" size="8" onChange="isEuro=true;calc(this.form)">&nbsp;Euro
  <div><img src='im/zzz.gif' height="2" width="1"></div>
  <img src="im/fleche_right.gif">&nbsp;
  <input name="otherCurrency" size="8" onChange="isEuro=false;calc(this.form)">&nbsp;<?php print DEVISE;?>
  <div><img src='im/zzz.gif' height="2" width="1"></div>
  &nbsp;&nbsp;&nbsp;<input align="right" type="submit" value="<?php print CONVERTIR;?>" onClick="calc(this.form)" name="submit">
  <div><img src='im/zzz.gif' height="5" width="1"></div>

                </td>
                </tr>
                </form>
                </table>

<script type="text/javascript">
var isEuro = false;
function calc(frm) {
   var e = frm.euro.value;
   var c = frm.currency[frm.currency.selectedIndex].value;
   var o = frm.otherCurrency.value;

   if(isEuro)
      frm.otherCurrency.value=pad(e * c);
   else
      frm.euro.value=pad(o / c);
}
function pad(n) {
    return Math.round(n * 100) / 100;
}
</script>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
