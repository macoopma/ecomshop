<?php

if(isset($_SESSION['lastView'])) {
	$explodeLastView = explode("z",$_SESSION['lastView']);
	if(count($explodeLastView)>0) {
		foreach($explodeLastView as $key => $value) {if($value=="") unset($explodeLastView[$key]);}  // remove empty value
		if(isset($explodeLastView) AND count($explodeLastView)>0) {
			$explodeLastView = array_reverse($explodeLastView);
			$explodeLastView = implode(",",$explodeLastView);
			$hidsZZ = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", categories_id FROM products WHERE products_id IN (".$explodeLastView.") ORDER BY FIELD(products_id,".$explodeLastView.") LIMIT 0,5") or die (mysql_error());
?>
<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">


	<table border="0" cellspacing="0" cellpadding="2" class="moduleLastView">
		<tr>
			<td height='<?php print $hauteurTitreModule;?>' class="moduleLastViewTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
		    	<?php print "<a href='".seoUrlConvert("laatst_gezien.php")."'>";?><img src="im/lang<?php print $_SESSION['lang'];?>/menu_lastView.png" border="0" title="<?php print LAST_VIEWED;?>" alt="<?php print LAST_VIEWED;?>"></a>
			</td>
		</tr>
	<tr>
		<td>
	<?php
	if(mysql_num_rows($hidsZZ)>0) {
		while($myhidZZ = mysql_fetch_array($hidsZZ)) {
			print "<tr><td>";
			print "<img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$myhidZZ['products_id']."&path=".$myhidZZ['categories_id'])."' ".display_title($myhidZZ['products_name_'.$_SESSION['lang']],$maxCarInfo).">".adjust_text($myhidZZ['products_name_'.$_SESSION['lang']],$maxCarInfo,"..")."</a>";
			print "</td></tr>";
	    }
	}
	?>
		</td>
	</tr>
	    <tr>
			<td>
				<?php print "<div align='center'><a href='".seoUrlConvert("laatst_gezien.php")."'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a></div>";?>
	        </td>
	    </tr>
	</table>


</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
<?php
		}
	}
}
?>
