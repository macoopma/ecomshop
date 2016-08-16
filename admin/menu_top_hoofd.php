<style type="text/css">
 
.tableDynMenuH {
    background-color:#f1f1f1;  
    padding-bottom:0px; 
    padding-top:0px;
    border:0px #CCCCCC solid; 
    border-bottom:3px #CCCCCC double; 
    margin-bottom:-1px;
}
/* center menu */
.center1 {
text-align: center;
}
.center2 {
margin-left: auto;
margin-right: auto;
}
#borderTopMainMenu {background:#CCCCCC; padding:0px; margin:0px;}
#borderBottomMainMenu {background:#none; padding:0px; margin:0px;}
#test3 {background:#none; padding:0px; margin:-1px;}
#borderTopSousmenu {background:#FFFFFF; padding:0px; margin:0px;}
#borderBottomSousmenu {background:#CCCCCC; padding:0px; margin:0px;}

div#menu45 {margin-left:0px;}
div#menu45 a {color:#000000; padding:0px; border-left:1px #CCCCCC solid; h/eight:15px;}
div#menu45 a:hover {color:#CC0000; FONT-WEIGHT: bold; background:#FFFFFF;}
div#menu45 ul {padding:0px; margin:0px; text-align:center; top:24px; left:0px;}
div#menu45 ul li {position:relative; list-style:none; margin-right:5px; float:left;}
div#menu45 ul ul {position: absolute; display:none; padding:0px 0px 0px 0px;}

div#menu45 li {background:#e1e1e1; top:0px; padding:0px; border-right:1px #CCCCCC solid;}
div#menu45 li:hover {background:#FFFFFF;}
div#menu45 li.sousmenuA {background:#e1e1e1; top:0px; padding:0px; border-left:0px #CCCCCC solid;}
div#menu45 li.sousmenuA:hover {background:#f1f1f1;}

div#menu45 li.sousmenu {background: url(../im/fleche_bottom.gif) 95% 50% no-repeat #e1e1e1; padding:0px;}
div#menu45 li.sousmenu:hover {background:#FFFFCC;}
div#menu45 li.sousmenu.plop {background:url(../im/fleche_right.gif) 95% 50% no-repeat #e1e1e1; padding:0px;}
div#menu45 li.sousmenu.plop:hover {background:url(../im/fleche_right.gif) 95% 50% no-repeat #CCCCCC;}

div#menu45 li a {text-decoration:none; padding:5px 0px 5px 0px; display:block; margin:0px; left:100px;}

div#menu45 ul.niveau1 li.sousmenu:hover ul.niveau2 {display:block;}
div#menu45 ul.niveau2 li.sousmenu:hover ul.niveau3 {display:block;}
div#menu45 ul.niveau3 li.sousmenu:hover ul.niveau4 {display:block;}

div#menu45 ul.niveau3 {top:0px; left:100px;}
div#menu45 ul.niveau4 {top:0px; left:100px;}

div#menu45 ul.niveau3 li { background: #e1e1e1}
div#menu45 ul.niveau3 li:hover { background: #FFFFFF}
div#menu45 ul.niveau4 li { background: #e1e1e1}
div#menu45 ul.niveau4 li:hover { background: #FFFFFF}

div#menu45 ul.niveau1 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat #e1e1e1; padding:0px;}
div#menu45 ul.niveau2 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat #e1e1e1; padding:0px;}
div#menu45 ul.niveau3 li.sousmenu.plop {background: url(../im/fleche_right.gif) 95% 50% no-repeat #e1e1e1; padding:0px;}

div#menu45 ul.niveau1 li.sousmenu.plop:hover {background:#FFFFCC;}
div#menu45 ul.niveau2 li.sousmenu.plop:hover {background:#FFFFCC;}
div#menu45 ul.niveau3 li.sousmenu.plop:hover {background:#FFFFCC;}
</style>


<style type="text/css"></style>

<table align='center' border='0' width='99%' cellspacing='0' cellpadding='0' class='tableDynMenuH'>
  <tr>
    <td><div align='center'>
        <table border='0' cellspacing='0' cellpadding='0' align=''>
          <tr>
            <td><div ID='menu45'>
                <ul class='niveau1'>                  
                  
                  <?php //CONFIGURATION ?>
                  <li class='sousmenu plop2' style='width:155px;'>
                    <div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div style="background-color:#FFCC00"><a href='#'>CONFIGURATION</a></div>
                    <div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>
                    
                    <ul class='niveau2'>
					<li class='sousmenu plop' style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='#'><b>[<?php print SHOP;?>]</b></a>
                                  
                                  <ul class='niveau3' style='left: 155px;'>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A7;?>'><?php print A7;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print ACHAT_EXPRESS;?>'><?php print ACHAT_EXPRESS;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print CHEQUE_CADEAU;?>'><?php print CHEQUE_CADEAU;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A1505;?>'><?php print A1505;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A66;?>'><?php print A66;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print "Email";?>'>Emails</a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print FERMETURE_BOUTIQUE;?>'><?php print FERMETURE_BOUTIQUE;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A69;?>'><?php print A69;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A10;?>'><?php print A10;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A5;?>'><?php print A5;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A16;?>'><?php print A16;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                  </ul>
                                  
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                        <li class='sousmenu plop' style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='#'><b>[DESIGN]</b></a>
                                  
                                  <ul class='niveau3' style='left: 155px;'>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A500;?>'><?php print A500;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A82;?>'><?php print A82;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A73;?>'><?php print A73;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print OPTIMISATION_IMAGES;?>'><?php print OPTIMISATION_IMAGES;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:155px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A2;?>'><?php print A2;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                  </ul>
                                  
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A1520;?>' name='a100z'><?php print A1520;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print RESERVATION_ARTICLE;?>'><?php print RESERVATION_ARTICLE;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>

                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A95;?>'><?php print A95;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A44;?>'><?php print A44;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A510A;?>'><?php print A510A;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print "RSS";?>'>RSS</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A76;?>'><?php print A76;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print "Stock";?>'>Stock</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:155px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A37;?>'><?php print A37;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                    </ul>
                  </li>
                  
                  
                  
                  
                  
                  <?php //PAIEMENTS ?>
                  <li class='sousmenu plop2' style='width:170px;'>
                    <div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div style="background-color:#999900"><a href='#'><?php print PAYMENT;?></a></div>
                    <div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>
                    
                    <ul class='niveau2' >
                      <li class='sousmenu plop' style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='#'><b>[<?php print PAYMENTS_MANUELS;?>]</b></a>
                                  
                                  <ul class='niveau3' style='left: 170px;'>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A111;?>'><?php print A111;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A1250;?>'><?php print A1250;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A125;?>'><?php print A125;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A1250A;?>'><?php print A1250A;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print A115A;?>'><?php print A115A;?></a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                    <li style='width:165px;'>
                                      <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                      <a href='site_config.php#<?php print "Western Union";?>'>Western Union</a>
                                      <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                                    </li>
                                  </ul>
                                  
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print "1EURO.COM";?>'>1EURO.COM</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." 2CHECKOUT";?>'>2CHECKOUT</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." BLUEPAID";?>'>BLUEPAID</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." EUROWEBPAYMENT";?>'>EUROWEBPAYMENT</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." KLIK&PAY";?>'>KLIK&PAY</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." LIAISON SSL";?>'>LIAISON-SSL</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." MONEYBOOKERS";?>'>MONEYBOOKERS</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." OGONE";?>'>OGONE</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." PAYPAL";?>'>PAYPAL</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." PAYSITE-CASH";?>'>PAYSITE-CASH</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A87." POSTFINANCE";?>'>POSTFINANCE</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:170px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print DESACTIVE_PAYMENTS;?>'><?php print DESACTIVE_PAYMENTS;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                    </ul>
                  </li>
                  
                  
                  
                  
                  
                  <?php //MODULES
                    function cleanTitles($r) {
                      $rr = preg_match("#'(.*)'#", $r, $titles);  
                      return $titles[1];
                    }
                  ?>
                  <li class='sousmenu plop2' style='width:131px;'>
                    <div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div style="background-color:#CC00CC"><a href='#'>MODULES</a></div>
                    <div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>
                    <ul class='niveau2' >
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A29A;?>"><?php print cleanTitles(A29A);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print MODULE_ADDTHIS;?>"><?php print cleanTitles(MODULE_ADDTHIS);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A1500;?>"><?php print cleanTitles(A1500);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A32;?>"><?php print cleanTitles(A32);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print MODULE_DEJA_VU;?>"><?php print cleanTitles(MODULE_DEJA_VU);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                     <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print DEVIS;?>"><?php print cleanTitles(DEVIS);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A31;?>"><?php print cleanTitles(A31);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>

                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A73;?>"><?php print INTERFACEZ;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A201;?>"><?php print cleanTitles(A201);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href='site_config.php#<?php print A69;?>'><?php print A69;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A29;?>"><?php print cleanTitles(A29);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A81;?>"><?php print cleanTitles(A81);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A28;?>"><?php print cleanTitles(A28);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A84;?>"><?php print cleanTitles(A84);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A33;?>"><?php print cleanTitles(A33);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A23;?>"><?php print cleanTitles(A23);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A30;?>"><?php print cleanTitles(A30);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A130;?>"><b>[<?php print A130;?>]</b></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A84A;?>"><?php print cleanTitles(A84A);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:131px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A33A;?>"><?php print cleanTitles(A33A);?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                    </ul>
                  </li>
                  
                  
                  
                  
                  
                  
                  <?php //PAGES ?>
                  <li class='sousmenu plop2' style='width:115px;'>
                    <div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div style="background-color:#9999FF"><a href='#'>PAGES</a></div>
                    <div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>
                    <div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>
                    <ul class='niveau2' >
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A106;?>">caddie.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A64;?>">cataloog.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A57;?>">categories.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A65;?>">beschrijving.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print PAGEINDEX;?>">index.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A49;?>">list.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print A1060;?>">bewaar_winkelmandje.php</a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                      <li style='width:115px;'>
                        <div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                        <a href="site_config.php#<?php print CETTE_PAGE;?>"><?php print CETTE_PAGE;?></a>
                        <div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
<br>