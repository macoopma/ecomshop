<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = AFFILIATION;
$xxxxxx = (isset($_GET['aff']) AND $_GET['aff']!=="")? $_GET['aff'] : "XXXXX";
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="10" topmargin="10" marginwidth="10" marginheight="10">
     
<TABLE WIDTH="575" BORDER="0" CELLSPACING="1" CELLPADDING="5" class="TABLEBorderDotted"><TR class="TABLETopTitle">
  <TD width="200" align="center"><b>&nbsp;</b></TD><TD align="center"><b><?php print CODE;?></b><br><i>...<?php print A_INCLURE;?>.</i></TD></TR>
  <TR>
  <TD width="200"><b><?php print UN_LIEN_DANS_EMAIL;?></b></TD><TD>
  	<?php print "http://".$www2.$domaineFull."/index.php?eko=".$xxxxxx;?>
  </TD>
  </TR>
  <TR>
  <TD width="200"><b><?php print UN_LIEN;?></b></TD><TD>
  	<?php print "&lt;a href=&quot;http://".$www2.$domaineFull."/index.php?eko=".$xxxxxx."&amp;lang=".$_SESSION['lang']."&quot; target=&quot;_blank&quot;&gt;".$store_name."&lt;/a&gt;";?>
  </TD>
  </TR>
  <TR style="background-color:#FAFAFA">
  <TD width="200"><b><?php print UNE_IMAGE;?></b></TD><TD><?php print "&lt;a href=&quot;http://".$www2.$domaineFull."/index.php?eko=".$xxxxxx."&amp;lang=".$_SESSION['lang']."&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;http://".$www2.$domaineFull."/im/logo.gif&quot; border=0&gt;&lt;/a&gt;";?></TD></TR>
  <TR>
  <TD width="200"><b><?php print UNE_BANNIERE;?></b></TD><TD><?php print "&lt;a href=&quot;http://".$www2.$domaineFull."/index.php?eko=".$xxxxxx."&amp;lang=".$_SESSION['lang']."&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;http://".$www2.$domaineFull."/im/banner/banner1.gif&quot; border=0&gt;&lt;/a&gt;";?></TD></TR>
  <TR style="background-color:#FAFAFA">
  <TD width="200"><b>Verkoop de artikelen van <?php print $store_name;?> op uw eigen website</b>
    <br>
    <a href="http://www.ecomshop.be/help/hoe-affiliate.htm" target="_blank">Klik voor meer informatie</a>
  </TD>
    <TD>
			&lt;p align=&quot;center&quot;&gt;<br>
                &lt;iframe src = &quot;http://<?php print $www2.$domaineFull;?>/ipos/index.php?eko=<?php print $xxxxxx;?>&amp;lang=<?php print $_SESSION['lang'];?>&amp;css=scss&quot; align=&quot;center&quot; scrolling=&quot;auto&quot; width=&quot;480&quot; height=&quot;550&quot; hspace=&quot;0&quot; vspace=&quot;0&quot; frameborder=&quot;0&quot; marginheight=&quot;0&quot; marginwidth=&quot;0&quot;&gt;<br>
                &lt;/iframe&gt;<br>
              &lt;/p&gt;
    </TD></TR>
</TABLE>

<?php if(!isset($_GET['aff'])) {
	print "<p align='center'>".REMPLACER."</p>";
}
?>
     
</body>
</html>
