<?php

$hauteurTableauHeader2 = 1;
$hauteurTableauHeader3 = 136;
$largeurMenuRondTop = "99%";
$hauteurMenuRondTop = "27";
$backgroundImage = "im/banner/inov.gif";
$borderLeftRightMenuRondTop = "3";
$hauteurPointilleVertical = 115;
$largeurMenuGauche = $larg_rub;
$margeGaucheMenuGaucheEnPixels = 10;

$itemNum = cart_Item();

if(isset($_SESSION['account']) OR $activeEcom=="oui") {
    if(!isset($_SESSION['list']) or $_SESSION['list'] == "" or empty($_SESSION['list'])) {
        $cady2 =  "<img src='im/cad.gif' align='absmiddle'><span style='vertical-align:middle;'>&nbsp;<a href='caddie.php'><span class='PromoFont'>".VOTRE_CADDIE."</span></a>:&nbsp;";
        $cady2 .= "<a href='caddie.php'><b><span class='PromoFont'>0 item</span></b></a></span>";
    }
    else {
        $sss = ($itemNum>1)? "en" : "";
        $cady2 =  "<img src='im/cart2.gif' align='absmiddle'><span style='vertical-align:middle;'>&nbsp;<a href='caddie.php'><span class='PromoFont'>".VOTRE_CADDIE."</span></a>:&nbsp;";
        $cady2.= "<a href='caddie.php'><b><span class='PromoFont'>".$itemNum." item".$sss."</span></b></a></span>";
    }
}
else {
   $cady2='';
}
?>

<!-- TABLE TOTAL -->
<table align='center' width='100%' border='0' cellspacing='0' cellspadding='0'>
<tr>
<?php if($header1Display=="oui") $pad=0; else $pad=5;?>
<td valign='top' class='backgroundHeader2' style="padding-top:<?php print $pad;?>px" align='center'>
          
          


<table align='center' width='99%' border='0' height='<?php print $hauteurTableauHeader2;?>' class='TABLEMenuPathTopPageMenuTabOff'>
<tr>
          
          
          <?php 
          if($langVisible=="oui" AND $_SESSION['langDispoZ']>1) {
          ?>
          <td width='20%' align='left' valign='bottom'>
                          <table width='<?php print $largeurMenuRondTop;?>' cellspacing='0' cellpadding='0' border='0'><tr><td>
                            <table height='<?php print $hauteurMenuRondTop;?>' align='center' cellspacing='0' cellpadding='1' border='0'><tr><td>
                                <?php add_flags();?>
                            </td></tr></table>
                            </td></tr></table>
          </td>
          <td><img src='im/zzz_gris.gif' width='1' height='25' align="absmiddle"></td>
          <?php
          }
          ?>
          
 
          <td width='20%' align='center' valign='bottom'>
                          <table width='<?php print $largeurMenuRondTop;?>' cellspacing='0' cellpadding='0' border='0'><tr><td>
                          <table height='<?php print $hauteurMenuRondTop;?>' align='center' cellspacing='0' cellpadding='1' border='0'>
                              <tr>
                              <td><a href='zoeken.php?action=search'><img src='im/_im2.gif' align='absmiddle' border='0' alt='<?php print RECHERCHER;?>' title='<?php print RECHERCHER;?>'></a></td>
                              <td>&nbsp;<a href='zoeken.php?action=search&AdSearch=off'><span class='PromoFont'><?php print RECHERCHER;?></span></a></td>
                              </tr>
                          </table>
                          </td></tr></table>
          </td>

 
          <td><img src='im/zzz_gris.gif' width='1' height='25' align="absmiddle"></td>
          <td width='20%' align='center' valign='bottom'>
                          <table width='<?php print $largeurMenuRondTop;?>' cellspacing='0' cellpadding='0' border='0'><tr><td>
                          <table height='<?php print $hauteurMenuRondTop;?>' align='center' cellspacing='0' cellpadding='1' border='0'>
                              <tr>
                              <td><a href='infos.php?info=11'><img src='im/i06.gif' align='absmiddle' border='0' alt='<?php print SUIVI_COMMANDE;?>' title='<?php print SUIVI_COMMANDE;?>'></a></td>
                              <td>&nbsp;<a href='infos.php?info=11'><span class='PromoFont'><?php print SUIVI_COMMANDE;?></span></a></td>
                              </tr>
                          </table>
                          </td></tr></table>
          </td>
         
 
          <td><img src='im/zzz_gris.gif' width='1' height='25' align="absmiddle"></td>
          <td width='20%' align='center' valign='bottom'>
                          <table width='<?php print $largeurMenuRondTop;?>' cellspacing='0' cellpadding='0' border='0'><tr><td>
                          <table height='<?php print $hauteurMenuRondTop;?>' align='center' cellspacing='0' cellpadding='1' border='0'>
                              <tr>
                              <td>&nbsp;<img src='im/aff_logo_top.gif' align='absmiddle'>&nbsp;</td>
                              <td align='left' valign='middle'>

                                <select name="path" onChange="sendIt(this.options[selectedIndex].value)" style="BACKGROUND-COLOR: #F1F1F1; BORDER-TOP-COLOR: #000000; BORDER-LEFT-COLOR: #000000; BORDER-RIGHT-COLOR: #000000; BORDER-BOTTOM-COLOR: #000000; BORDER-TOP-WIDTH: 1px; BORDER-LEFT-WIDTH: 1px; FONT-SIZE: 9px; BORDER-BOTTOM-WIDTH: 1px; FONT-FAMILY: Verdana,Helvetica; BORDER-RIGHT-WIDTH: 1px;">
                                <option class="grey2" style="height:12px;" value="">Index boutique</option>
                                <option style="background:#FFFFFF;" value="">&nbsp;</option>
 
                                      <option class="yellow" value="mijn_account.php">&diams;&nbsp;<?php print VOTRE_COMPTE;?></option>

                                      <option class="yellow" value="affiliation.php">&diams;&nbsp;<?php print AFFILIATION;?></option>
                                      <?php 
                                      
                                      if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
                                          print '<option class="yellow" value="list.php?target=promo">&diams;&nbsp;'.PROMOTIONS.'</option>';
                                      }
                                      
                                      if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
                                          print '<option class="yellow" value="list.php?target=new">&diams;&nbsp;'.NOUVEAUTES.'</option>';
                                      }
                                      ?>
                                      <?php 
                                      if($devis == "oui") print "<option class='yellow' value='pagina_box.php?module=menu_devis'>&diams;&nbsp;".VOS_DEVIS."</option>";
                                      ?>

                                      <option class="yellow" value="top10.php">&diams;&nbsp;Top 10</option>
 
                                      <option class="yellow" value="pagina_box.php?module=menu_subscribe">&diams;&nbsp;<?php print NEWSLETTER;?></option>
  
                                      <option class="yellow" value="pagina_box.php?module=menu_interface">&diams;&nbsp;<?php print INTERFACEW;?></option>
   
                                      <option class="yellow" value="pagina_box.php?module=menu_quick">&diams;&nbsp;<?php print MENU_RAPIDE;?></option>
    
                                      <option class="yellow" value="pagina_box.php?module=menu_converter">&diams;&nbsp;<?php print CONVERTISSEUR;?></option>
     
                                      <option style="background:#FFFFFF;" value="">&nbsp;</option>      
      
                                      <option style="background:#666666; color:#FFFFFF" value="list.php?target=favorite">&hearts;&nbsp;<?php print NOS_SELECTIONS;?></option>
                                      <?php
       
                                      if($activeSeuilPromo=="oui" AND $seuilPromo > 0 AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
                                          print '<option style="background:#666666; color:#FFFFFF" value="list.php?target=promo&tow=flash">&hearts;&nbsp;'.VENTES_FLASH.'</option>';
                                      }
        
                                      if($gcActivate=="oui" AND !isset($_SESSION['devisNumero']) AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
                                          print '<option style="background:#666666; color:#FFFFFF" value="geschenkbon.php">&hearts;&nbsp;'.CHEQUES_CADEAUX.'</option>';
                                      }
         
                                      if($activerRemisePastOrder=="oui") {
                                          print "<option style='background:#666666; color:#FFFFFF' value='gebruik_bonuspunten.php'>&hearts;&nbsp;".POINTS_FIDELITE."</option>";
                                      }
          
                                      if($activeActu=="oui") {
                                          print '<option style="background:#666666; color:#FFFFFF" value="nieuws.php">&hearts;&nbsp;'.ACTUS.'</option>';
                                      }
           
                                      if($activeRSS=="oui" AND (isset($_SESSION['account']) OR $activeEcom=="oui")) {
                                          print '<option style="background:#666666; color:#FFFFFF" value="rss.php">&hearts;&nbsp;'.DIFFUSEZ_CONTENU.'</option>';
                                      }
                                      ?>
            
                                      <option style="background:#FFFFFF;" value="">&nbsp;</option>
             
                                      <option class="red" value="infos.php?info=11">&diams;&nbsp;<?php print SUIVI_COMMANDE;?></option>
                                </select>
                              </td>
                              <td>&nbsp;</td>
                              </tr>
                          </table>
                          </td></tr></table>
          </td>
          
          <?php 
          if(isset($_SESSION['account']) OR $activeEcom=="oui") {
          ?>
          <td><img src='im/zzz_gris.gif' width='1' height='25'></td>
          <td width='180' align='right' valign='bottom'>
                          <table width='180' cellspacing='0' cellpadding='0' border='0'>
                          <tr>
                          <td>
                          <table height='<?php print $hauteurMenuRondTop;?>' align='center' cellspacing='0' cellpadding='0' border='0'>
                              <tr>
                              <td valign='middle'><?php print $cady2;?></td>
                              </tr>
                          </table>
                          </td>
                          </tr>
                          </table>
          </td>
          <?php }?>

</tr></table>
 

         
<table align='center' width='100%' border='0' height='<?php print $hauteurTableauHeader3;?>' style='margin-top:0px;' cellspacing='0' cellpadding='0'>        
<tr>

          <td valign='middle' align='center' colspan='12'>
                    <table border='0' width='99%' align='center' style='background-color:#none; B/ACKGROUND-IMAGE: url(im/banner/grid.gif)' cellspacing='0' cellpadding='0'>
                    <tr>
                    <td valign='middle' width="<?php print $largeurMenuGauche;?>">

 
                              <img src='im/zzz.gif' width='4' height='18' style='background:#CC0000; margin-left:<?php echo $margeGaucheMenuGaucheEnPixels;?>px;' align='absmiddle'>&nbsp;<img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a href='cataloog.php'><span style='font-size:12px;'><?php print HOME;?></span></a>
                                 <div style='margin:2px; margin-left:3px; width:<?php print $largeurMenuGauche;?>px; background: url(im/zzz_spacer.gif); BACKGROUND-REPEAT:repeat-x'><img src='im/zzz.gif' width='1' height='1'></div>

                              <?php

                              if($nouvVisible == 'oui') {
                                 print "<img src='im/zzz.gif' width='4' height='18' style='background:#0000FF; margin-left:".$margeGaucheMenuGaucheEnPixels."px;' align='absmiddle'>&nbsp;<img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a href='list.php?target=new'><span style='font-size:12px;'>".NOUVEAUTES."</span></a>";
                                 print "<div style='margin:2px; margin-left:3px; width:".$largeurMenuGauche."px; background: url(im/zzz_spacer.gif); BACKGROUND-REPEAT:repeat-x'><img src='im/zzz.gif' width='1' height='1'></div>";
                              } 
                                 
                              if($promoVisible == 'oui') {
                                 print "<img src='im/zzz.gif' width='4' height='18' style='background:#00FF00; margin-left:".$margeGaucheMenuGaucheEnPixels."px;' align='absmiddle'>&nbsp;<img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a href='list.php?target=promo'><span style='font-size:12px;'>".PROMOTIONS."</span></a>";
                                 print "<div style='margin:2px; margin-left:3px; width:".$largeurMenuGauche."px; background: url(im/zzz_spacer.gif); BACKGROUND-REPEAT:repeat-x'><img src='im/zzz.gif' width='1' height='1'></div>";
                              }
                              ?>


                              <img src='im/zzz.gif' width='4' height='18' style='background:#FFFF00; margin-left:<?php echo $margeGaucheMenuGaucheEnPixels;?>px;' align='absmiddle'>&nbsp;<img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a href='mijn_account.php'><span style='font-size:12px;'><?php print VOTRE_COMPTE;?></span></a>
                                 <div style='margin:2px; margin-left:3px; width:<?php print $largeurMenuGauche;?>px; background: url(im/zzz_spacer.gif); BACKGROUND-REPEAT:repeat-x'><img src='im/zzz.gif' width='1' height='1'></div>


                              <img src='im/zzz.gif' width='4' height='18' style='background:#000000; margin-left:<?php echo $margeGaucheMenuGaucheEnPixels;?>px;' align='absmiddle'>&nbsp;<img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a href='infos.php?info=5'><span style='font-size:12px;'><?php print NOUS_CONTACTER;?></span></a>


                     </td>

                     <td width='1'>
                        <table border='0' cellspacing='0' cellpadding='0'>
                           <tr>
                              <td height='<?php print $hauteurPointilleVertical;?>' style='BACKGROUND-IMAGE:url(im/zzz_spacer_v.gif); BACKGROUND-REPEAT:repeat-y;'><img src='im/zzz.gif' width='1' height='1'>
                              </td>
                           </tr>
                        </table>
                     </td>
                     <?php
                     if($bannerVisible=="oui" AND $bannerHeader2=="oui") {
                        print "<td valign='middle' align='center'>";
                        include('includes/banner.php');
                        print "</td>";
                     }
                     else {
                        print "<td valign='middle' align='right'>";
                        print "<img border='0' src='".$backgroundImage."'>";
                        print "</td>";
                     }
                     ?>
                  </tr>
                  </table>


          </td>
</tr>
</table>




</td>
</tr>
</table>
