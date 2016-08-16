<style type="text/css"> 
ul#slid {padding:0; margin:4; list-style:none; width:165px; height:500px; border:1px solid #f1f1f1; position:relative; overflow:hidden;}
 
ul li div {position:absolute; left:-9999px; background:#f1f1f1;}
 
ul table {border-collapse:collapse; width:0; height:0; margin:-1px; padding:0;}
 
#slid li.top {height:25px; width:165px; float:left;}

#slid li.top a.outer {display:block; float:left; height:24px; line-height:24px; width:165px; text-indent:5px; color:#000000; background:#E2E2E2; text-decoration:none; font-family: verdana, sans-serif; f/ont-weight:bold; font-size:12px; border-bottom:1px solid #FFFFFF;}
 
#slid li.top a:hover {color:#FFFFFF; background:#000000; text-decoration:none;}
 
#slid li.top:hover > a {color:#FFFFFF; background:#000000; text-decoration:none;}
#slid li:hover {height:375px;}
#slid a:hover div, #slid li.current div {position:static; height:320px; width:165px; padding:5px; line-height:1.2em; font-family: verdana, sans-serif;}
#slid li:hover div {position:static; height:320px; width:165px; padding:5px; line-height:1.2em; font-family: verdana, sans-serif;}

#slid div h3 {font-size:13px; color:#000000; padding:5px 5px 0px 0px; margin:0; width:165px;}
 
#slid div p  {font-size:11px; color:#888888; padding:5px 5px 0px 0px; margin:0; width:165px;}

#slid :hover div a {color:#00c; text-decoration:none; font-size:11px;}
#slid :hover div a:hover {text-decoration:underline; background:#FF0000; color:#00c;}

#slid div dl {border-bottom:1px dotted #f1f1f1; margin:0; padding:0px;}
#slid div dt {font-size:11px; font-weight:bold; color:#000000;}
#slid div dd {padding:0; margin:0; line-height:1.5em; font-size:11px; color:#000000;}
.dz {font-size:12px; color:#CC0000}
.noDec {background:#f1f1f1; color:#f1f1f1;}
#slid :hover div dd a {color:#00c; text-decoration:none; font-size:15px;}
#slid :hover div dd a:hover {background:#fff; color:#00c; text-decoration:underline; font-size:15px;}
/*
#slid div img {display:block; margin:5px 0 8px 0;}
#slid div img.fLeft {clear:left; float:left; display:block; margin:8px; margin-right:8px;}
#slid div a.big, #slid :hover div a.big {display:block; height:30px; line-height:30px; border-top:1px solid #ddd; font-size:14px; color:#44a; text-decoration:none; text-indent:5px;}
#slid :hover div a.big:hover {background:#fff; color:#000; font-size:14px; text-decoration:none;} 
*/
</style>




<ul id="slid">
	<li class="top"><a class="outer" href="site_config.php" target="main"><?php print CONFIG;?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div>
        <p>
          <!--[if gte IE 17]><!--><img src="im/zzz.gif" width="1" height="20"><!--<![endif]-->
          <?php print PARAM_BOUTIQUE;?>
        </p>
<dl>
<dd><br><span class="dz">&#187;</span> <a href="site_config.php" target="main" style="padding:2px;"><?php print CONFIG_PAIEMENT;?></a></dd>
</dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li class="top"><a class="outer" href="#url"><?php print A1;?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div>
<dl>
<dd><span class="dz">&#187;</span> <a href="categorie_toevoegen.php" target="main" style="padding:2px;"><?php print A2;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="sub_categorie_toevoegen.php" target="main" style="padding:2px;"><?php print A3;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="artikel_toevoegen.php" target="main" style="padding:2px;"><?php print A4;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="opties_toevoegen.php" target="main" style="padding:2px;"><?php print A5;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="promoties_toevoegen.php" target="main" style="padding:2px;"><?php print A6;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="korting_code_toevoegen.php" target="main" style="padding:2px;"><?php print CPDE_DE;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="fab_lev_toevoegen.php" target="main" style="padding:2px;"><?php print A9;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="banner_toevoegen.php" target="main" style="padding:2px;"><?php print A10;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="land_toevoegen.php" target="main" style="padding:2px;"><?php print A28;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="klant_account_toevoegen.php" target="main" style="padding:2px;"><?php print PRO;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="eigen_pagina_toevoegen.php" target="main" style="padding:2px;"><?php print PAGE_INFO;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="verzendfirma_toevoegen.php" target="main" style="padding:2px;"><?php print MODE_LIVRAISON;?></a></dd>
</dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li class="top"><a class="outer" href="#url">Éditer | Modifier<!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div>
<dl>
<dd><span class="dz">&#187;</span> <a href="categorie_wijzigen.php" target="main" style="padding:2px;"><?php print A2;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="sub_categorie_wijzigen.php" target="main" style="padding:2px;"><?php print A3;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="artikel_wijzigen.php" target="main" style="padding:2px;"><?php print A4;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="opties_wijzigen.php" target="main" style="padding:2px;"><?php print A5;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="promoties_wijzigen.php" target="main" style="padding:2px;"><?php print A6;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="korting_code_wijzigen.php" target="main" style="padding:2px;"><?php print CPDE_DE;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="fab_lev_wijzigen.php" target="main" style="padding:2px;"><?php print A9;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="banner_wijzigen.php" target="main" style="padding:2px;"><?php print A10;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="landen_verwijderen.php" target="main" style="padding:2px;"><?php print A28;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="klant_accounts_beheren.php?page=0" target="main" style="padding:2px;"><?php print PRO;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="eigen_pagina_wijzigen.php" target="main" style="padding:2px;"><?php print PAGE_INFO;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="verzendfirma_wijzigen.php" target="main" style="padding:2px;"><?php print MODE_LIVRAISON;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="css_bestand.php" target="main" style="padding:2px;"><?php print EDITER_CSS;?></a></dd>
</dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li class="top"><a class="outer" href="#url"><?php print A12;?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div>
<dl>
<dd><span class="dz">&#187;</span> <a href="klanten.php" target="main" style="padding:2px;"><?php print A13;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="offertes.php" target="main" style="padding:2px;"><?php print DEVIS;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="voorraad_beheer.php?z" target="main" style="padding:2px;"><?php print A14;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="verkoopsoverzicht.php" target="main" style="padding:2px;"><?php print A16;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="terugbetaling.php" target="main" style="padding:2px;"><?php print A17;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="zoeken.php" target="main" style="padding:2px;"><?php print A15;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="nieuwsbrief.php" target="main" style="padding:2px;"><?php print A18;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="bewaarde_winkelmandjes.php" target="main" style="padding:2px;"><?php print A33;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="verzendzone.php" target="main" style="padding:2px;"><?php print A19;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="verzendprijzen.php?u" target="main" style="padding:2px;"><?php print A20;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="affiliate.php" target="main" style="padding:2px;"><?php print A34;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="klant_accounts_beheren.php?page=0" target="main" style="padding:2px;"><?php print PRO;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="kadeaubon.php" target="main" style="padding:2px;"><?php print A200;?></a></dd>
</dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li class="top"><a class="outer" href="#url"><?php print A21;?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div>
<dl>
<dd><span class="dz">&#187;</span> <a href="menu.php" target="main" style="padding:2px;"><?php print A31;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="shop_export.php" target="main" style="padding:2px;"><?php print GUIDE;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="../sitemap.php" target="main" style="padding:2px;">Google sitemap</a></dd>
<dd><span class="dz">&#187;</span> <a href="yahoo_google_sitemap.php" target="main" style="padding:2px;">Yahoo - Google URL lijst</a></dd>
<dd><span class="dz">&#187;</span> <a href="statistieken.php" target="main" style="padding:2px;"><?php print A23;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="css_interface.php" target="main" style="padding:2px;"><?php print A24;?></a></dd>
<dd><span class="dz">&#187;</span> <b><?php print PAGES;?></b></dd>
<dd>&nbsp; • <a href="pagina_voorwaarden.php" target="main" style="padding:2px;"><?php print COND;?></a></dd>
<dd>&nbsp; • <a href="pagina_wzw.php" target="main" style="padding:2px;"><?php print QUI;?></a></dd>
<dd>&nbsp; • <a href="pagina_partners.php" target="main" style="padding:2px;"><?php print PART;?></a></dd>
<dd>&nbsp; • <a href="pagina_betaling.php" target="main" style="padding:2px;"><?php print PAIE;?></a></dd>
<?php
if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {
?>
<dd><span class="dz">&#187;</span> <b><?php print EXPORT;?></b></dd>
<dd>&nbsp; • <a href="backup.php" target="main" style="padding:2px;">Format SQL</a></dd>
<dd>&nbsp; • <a href="x_exporteer_alle_artikelen.php" target="main" style="padding:2px;">Format CSV</a></dd>
<dd><span class="dz">&#187;</span> <a href="wachtwoord_wijzigen.php" target="main" style="padding:2px;"><?php print A25;?></a></dd>
<?php
}
?>
<dd><span class="dz">&#187;</span> <a href="import/artikelen/index.php" target="main" style="padding:2px;"><?php print IMPORT;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="sql.php" target="main" style="padding:2px;"><?php print SQL_REQ;?></a></dd>
<dd><span class="dz">&#187;</span> <a href="phpinfo.php" target="main" style="padding:2px;">PHP infos</a></dd>
<dd><span class="dz">&#187;</span> <a href="versie.php" target="main" style="padding:2px;">Version</a></dd>
</dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
	<li class="current top"><a class="outer" href="afmelden.php" target="main"><?php print A27;?><!--[if gte IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div style="padding:0">
		<dl>
		<dd><img src="im/zzz.gif" width="1" height="5"></dd>
		<dd style="text-decoration:none;"><div style="b/ackground:#FF0000; width:155px; height:25px; text-align:center; text-decoration:none;"><a href="afmelden.php" target="main"><img src="im/exit.gif" class="noDec" border="0" title="<?php print A27;?>"></a></div></dd>
		<dd></dd>
        </dl>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>
</ul>
