<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">



         <table border="0" cellspacing="0" cellpadding="2" class="moduleAffiliate">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' colspan="4" class="moduleAffiliateTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <a href="affiliation.php"><img src="im/lang<?php print $_SESSION['lang'];?>/menu_affiliate.png" border="0" title="<?php print AFFILIATION;?>" alt="<?php print AFFILIATION;?>"></a>
         </td>
         </tr>
         <tr>
         <form action="affiliate_details.php" method="POST">
<?php

                print "<td colspan='4'>";
                print "<div><img src='im/zzz.gif' height='1' width='1'></div>";
                print "</td>";
                print "</tr><tr>";
                print "<td width='1'><img src='im/fleche_right.gif'></td>";
                print "<td>";
                print "<input type='text' name='aff_account' style='width:100%' value='".VOTRE_COMPTE."' onblur=\"if(this.value=='') {this.value='".VOTRE_COMPTE."'}\" onFocus=\"if(this.value=='".VOTRE_COMPTE."') {this.value=''}\">";
                print "</td>";
                print "<td align='left'>&nbsp;</td>";
                print "<td>&nbsp;</td>";
                
                print "</tr><tr>";
                print "<td width='1'><img src='im/fleche_right.gif'></td>";
                print "<td>";
                print "<input type='text' name='aff_pass' style='width:100%' value='".MOT_DE_PASSE."' onblur=\"if(this.value=='') {this.value='".MOT_DE_PASSE."'}\" onFocus=\"if(this.value=='".MOT_DE_PASSE."') {this.value=''}\">";
                print "</td>";
                print "<td align='right'><INPUT style='BACKGROUND:none; border:0px' type='image' src='im/ok.gif'></td>";
                print "<td>&nbsp;</td>";
                
                print "</tr><tr>";
                print "<td colspan='4'>";

                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td valign='middle'>";
                print "<img src='im/fleche_right.gif'>&nbsp;<a href='affiliation.php'>".DEVENIR_AFF."</a>";
                print "</td>";
                print "<td align='right'>";
                print "<a href='../ipos/index.htm' target='_blank'><img src='im/ipos.gif' border='0' alt='Internet Point Of Sale' title='Internet Point Of Sale'></a>&nbsp;&nbsp;";
                print "</td>";
                print "</tr></table>";
                
                print "<div><img src='im/zzz.gif' height='1' width='1'></div>";
                print "</td>";
?>
        </form>
        </tr>
        </table>


</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
