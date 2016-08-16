<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

         <table border="0" cellspacing="0" cellpadding="2" class="moduleMessage">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="moduleMessageTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <img src="im/lang<?php print $_SESSION['lang'];?>/menu_com.png">
         </td>
         </tr>
         <tr>
         <td><div><img src='im/zzz.gif' height="1" width="1"></div></td></tr><tr>
         <td>

<?php
if($nowVisible == 'oui') {
     
    if($activeSeuilPromo=="oui" AND $seuilPromo > 0 AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
        print '<div align="center">';
        print '<a href="'.seoUrlConvert("list.php?target=promo&tow=flash").'">';
        print '<img src="im/lang'.$_SESSION['lang'].'/flash.png" border="0" title="'.VENTES_FLASH.'" alt="'.VENTES_FLASH.'">';
        print '</a>';
        print '</div>';
        print "</td>";
        print "</tr>";
        print "<tr>";
        print "<td>";
    }

     
    if($activerRemisePastOrder=="oui") {
        print '<div align="center">';
        print "<a href='javascript:void(0);' onClick=\"window.open('gebruik_bonuspunten.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=190,width=500,toolbar=no,scrollbars=no,resizable=yes');\">";
        print '<img src="im/lang'.$_SESSION['lang'].'/points_fidelite.png" border="0" title="'.POINTS_FIDELITE.'" alt="'.POINTS_FIDELITE.'">';
        print "</a>";
        print '</div>';
        print "</td>";
        print "</tr>";
        print "<tr>";
        print "<td>";
    }

 
    if($gcActivate=="oui" AND !isset($_SESSION['devisNumero']) AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
        print '<div align="center">';
        print '<a href="'.seoUrlConvert("geschenkbon.php").'">';
        print '<img src="im/lang'.$_SESSION['lang'].'/gc.png" border="0" title="'.CHEQUE_CADEAU_MIN.'" alt="'.CHEQUE_CADEAU_MIN.'">';
        print '</a>';
        print '</div>';
        print "</td>";
        print "</tr>";
        print "<tr>";
        print "<td>";
    }
    
     
    if($activeActu=="oui") {
        print '<div align="center">';
        print '<a href="'.seoUrlConvert("nieuws.php").'">';
        print '<img src="im/lang'.$_SESSION['lang'].'/actu.png" border="0" title="'.ACTU.'" alt="'.ACTU.'">';
        print '</a>';
        print '</div>';
        print "</td>";
        print "</tr>";
        print "<tr>";
        print "<td>";
    }

     
        print '<div align="center">';
        print '<a href="'.seoUrlConvert("list.php?target=favorite").'">';
        print '<img src="im/lang'.$_SESSION['lang'].'/selection.png" border="0" title="'.NOS_SELECTIONS.'" alt="'.NOS_SELECTIONS.'">';
        print '</a>';
        print '</div>';
        print "</td>";
        print "</tr>";
        print "<tr>";
        print "<td>";
}

if($comVisible == 'oui') {
print "</td></tr><tr><td align='center'>";
?>

<script type="text/javascript">
 
var scrollerdelay='<?php print $scrollDelay;?>'
var scrollerwidth='<?php print $scrollWidth;?>px' 
var scrollerheight='<?php print $scrollHeight;?>px' 
var scrollerbgcolor='<?php print $scrollColor;?>' 



var messages=new Array()
messages[0]="<?php print COMMUNIQUE1;?>"
messages[1]="<?php print COMMUNIQUE2;?>"
messages[2]="<?php print COMMUNIQUE3;?>"

 

var ie=document.all
var dom=document.getElementById

if(messages.length>2)
i=2
else
i=0

function move1(whichlayer) {
tlayer=eval(whichlayer)
if(tlayer.top>0&&tlayer.top<=5) {
tlayer.top=0
setTimeout("move1(tlayer)",scrollerdelay)
setTimeout("move2(document.main.document.second)",scrollerdelay)
return
}
if(tlayer.top>=tlayer.document.height*-1) {
tlayer.top-=5
setTimeout("move1(tlayer)",50)
}
else{
tlayer.top=parseInt(scrollerheight)
tlayer.document.write(messages[i])
tlayer.document.close()
if(i==messages.length-1)
i=0
else
i++
}
}

function move2(whichlayer) {
tlayer2=eval(whichlayer)
if(tlayer2.top>0&&tlayer2.top<=5) {
tlayer2.top=0
setTimeout("move2(tlayer2)",scrollerdelay)
setTimeout("move1(document.main.document.first)",scrollerdelay)
return
}
if(tlayer2.top>=tlayer2.document.height*-1) {
tlayer2.top-=5
setTimeout("move2(tlayer2)",50)
}
else{
tlayer2.top=parseInt(scrollerheight)
tlayer2.document.write(messages[i])
tlayer2.document.close()
if(i==messages.length-1)
i=0
else
i++
}
}

function move3(whichdiv) {
tdiv=eval(whichdiv)
if(parseInt(tdiv.style.top)>0&&parseInt(tdiv.style.top)<=5) {
tdiv.style.top=0+"px"
setTimeout("move3(tdiv)",scrollerdelay)
setTimeout("move4(second2_obj)",scrollerdelay)
return
}
if(parseInt(tdiv.style.top)>=tdiv.offsetHeight*-1) {
tdiv.style.top=parseInt(tdiv.style.top)-5+"px"
setTimeout("move3(tdiv)",50)
}
else{
tdiv.style.top=parseInt(scrollerheight)
tdiv.innerHTML=messages[i]
if(i==messages.length-1)
i=0
else
i++
}
}

function move4(whichdiv) {
tdiv2=eval(whichdiv)
if(parseInt(tdiv2.style.top)>0&&parseInt(tdiv2.style.top)<=5) {
tdiv2.style.top=0+"px"
setTimeout("move4(tdiv2)",scrollerdelay)
setTimeout("move3(first2_obj)",scrollerdelay)
return
}
if(parseInt(tdiv2.style.top)>=tdiv2.offsetHeight*-1) {
tdiv2.style.top=parseInt(tdiv2.style.top)-5+"px"
setTimeout("move4(second2_obj)",50)
}
else{
tdiv2.style.top=parseInt(scrollerheight)
tdiv2.innerHTML=messages[i]
if(i==messages.length-1)
i=0
else
i++
}
}

function startscroll() {
if(ie||dom) {
first2_obj=ie? first2 : document.getElementById("first2")
second2_obj=ie? second2 : document.getElementById("second2")
move3(first2_obj)
second2_obj.style.top=scrollerheight
second2_obj.style.visibility='visible'
}
else if(document.layers) {
document.main.visibility='show'
move1(document.main.document.first)
document.main.document.second.top=parseInt(scrollerheight)+5
document.main.document.second.visibility='show'
}
}

window.onload=startscroll

</script>


<ilayer id="main" width=&{scrollerwidth}; height=&{scrollerheight}; bgColor=&{scrollerbgcolor}; visibility=hide>
<layer id="first" left=0 top=1 width=&{scrollerwidth};>
<script language="JavaScript1.2">
if(document.layers)
document.write(messages[0])
</script>
</layer>
<layer id="second" left=0 top=0 width=&{scrollerwidth}; visibility=hide>
<script language="JavaScript1.2">
if(document.layers)
document.write(messages[dyndetermine=(messages.length==1)? 0 : 1])
</script>
</layer>
</ilayer>
<script language="JavaScript1.2">
if(ie||dom) {
document.writeln('<div id="main2" style="position:relative;width:'+scrollerwidth+';height:'+scrollerheight+';overflow:hidden;background-color:'+scrollerbgcolor+'">')
document.writeln('<div style="position:absolute;width:'+scrollerwidth+';height:'+scrollerheight+';clip:rect(0 '+scrollerwidth+' '+scrollerheight+' 0);left:0px;top:0px">')
document.writeln('<div id="first2" style="position:absolute;width:'+scrollerwidth+';left:0px;top:1px;">')
document.write(messages[0])
document.writeln('</div>')
document.writeln('<div id="second2" style="position:absolute;width:'+scrollerwidth+';left:0px;top:0px;visibility:hidden">')
document.write(messages[dyndetermine=(messages.length==1)? 0 : 1])
document.writeln('</div>')
document.writeln('</div>')
document.writeln('</div>')
}
</script>

<?php
}
?>
		</td>
        </tr>
        </table>

</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
