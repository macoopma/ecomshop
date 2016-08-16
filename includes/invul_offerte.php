<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td colspan="2"><div align="center"><?php print DEVIS_TOP;?></div></td>
  </tr>
  <tr> 
    <td width="30">&nbsp;</td>
    <td width="500" height="25"><br>
<?php
if(isset($messageAff)) print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'><br>".$messageAff."</p>";

 
if(isset($_SESSION['account'])) {
    $retrieve_data = mysql_query("SELECT *
                          FROM users_pro
                          WHERE users_pro_password  = '".$_SESSION['account']."'
                          ORDER  BY  'users_pro_id'
                          DESC 
                          LIMIT 0 , 1
                         ");
    $nomNum = mysql_num_rows($retrieve_data);
    if($nomNum > 0) {
        $nom = mysql_fetch_array($retrieve_data);
        $ret_ok = "1";
    }
    else {
        $ret_ok = "0";
    }
}
else {
    $ret_ok = "0";
}
?>
      <table border="0" width="100%" align="center" cellspacing="4" cellpadding="0">
        <tr> 
          <td><?php print NOM;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_lastname']; else $a="";?>
          <input type="text" name="nom" size="30" value="<?php print $a;?>">&nbsp;<span class="fontrouge">*</span>
          </td>
        </tr>
        <tr> 
          <td><?php print PRENOM;?></td>
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_firstname']; else $a="";?>
          <td colspan="2"><input type="text" name="prenom" size="30" value="<?php print $a;?>">
          </td>
        </tr>
        <tr> 
          <td><?php print COMPAGNIE2;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_company']; else $a="";?>
          <input type="text" name="entreprise" size="30" value="<?php print $a;?>">
          </td>
        </tr>
            <tr>
            <td>
            <?php print ($tvaManuelValidation=="oui")? NO_TVA.":<span style='font-size:9px; color:#FF0000'><sup><b>1</b></sup></span>" : NO_TVA."";?>
            </td>
            <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_tva']; else $a="";?>
            <td>
            <input type="text" name="clientTVA" size="30" value="<?php print $a;?>">
            <div class="FontGris">&nbsp;(<?php print LAISSER_VIDE;?>)</div>
            </td>
          </tr>
        <tr> 
          <td>E-mail</td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_email']; else $a="";?>
          <input type="text" name="email" size="30" value="<?php print $a;?>">&nbsp;<span class="fontrouge">*</span>
          </td>
        </tr>
        <tr> 
          <td valign=top><?php print ADRESSE;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $c=$nom['users_pro_address']; else $c = "";?>

                   <input type="text" name="adresse" size="30" value="<?php print $c;?>">&nbsp;<span class="fontrouge">*</span>


          </td>
        </tr>
        <tr> 
          <td><?php print CODE_POSTAL;?>, <?php print VILLE;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_postcode']; else $a="";?>
          <?php if(isset($ret_ok) AND $ret_ok == "1") $b=$nom['users_pro_city']; else $b="";?>
          <input type="text" name="cp" size="6" value="<?php print $a;?>">&nbsp;&nbsp;
          <input type="text" name="ville" size="18" value="<?php print $b;?>">&nbsp;<span class="fontrouge">*</span>
          </td>
        </tr>
        
        
          <tr>
<?php
// LAND
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
            <td><?php print PAYS;?></td>
            <td>
              <select name="pays">
<?php
                while ($countries = mysql_fetch_array($pays)) {
                    if($countries['iso'] == $iso) $a = "selected"; else $a="";
                        $countryName = $countries['countries_name'];
                        $countryValue = $countries['countries_name'];
                    if($ret_ok == "1" AND $countries['countries_name'] == $nom['users_pro_country']) $a = "selected"; else $a="";
                    print "<option value='".$countryValue."' ".$a.">".$countryName."</option>";
                 }
?>
              </select>
            </td>
          </tr>
          
        <tr> 
          <td><?php print TELEPHONE;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_telephone']; else $a="";?>
          <input type="text" name="tel" size="30" value="<?php print $a;?>">&nbsp;<span class="fontrouge">*</span>
          </td>
        </tr>
        <tr> 
          <td><?php print FAX;?></td>
          <td colspan="2">
          <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_fax']; else $a="";?>
          <input type="text" name="fax" size="30" value="<?php print $a;?>">
          </td>
        </tr>
        <tr>
          <td valign=top><?php print DOMAINE_ACTIVITY;?></td>
           <?php if(isset($ret_ok) AND $ret_ok == "1") $a=$nom['users_pro_activity']; else $a="";?>
          <td colspan="2"><textarea name="activite" cols="30" rows="2"><?php print $a;?></textarea></td></td>
        </tr>
        <tr>
          <td valign=top><?php print COMMENTAIRES;?></td>
          <td colspan="2"><textarea name="commentaire" cols="30" rows="4"></textarea></td>
        </tr>
      </table>

<p align="center">
<input type="hidden" name="devisV" size="5" value="">
 

      <br> 
      <table border="0" width="100%" align="center" cellspacing="4" cellpadding="0">
        <tr> 
          <td><div align="center">
              <input type="submit" name="submit3" value="<?php print ENVOYER_FORMULAIRE;?>">
              <input type="hidden" name="action" value="send">
<?php
                if(isset($_SESSION['accountRemise'])) $remise1 = $_SESSION['accountRemise']; else $remise1 = "";
                if(isset($_SESSION['montantRemise2'])) $remise2 = $_SESSION['coupon_name']; else $remise2 = "";
?>
              <input type="hidden" name="remise_commande" value="<?php print $remise1;?>">
              <input type="hidden" name="remise_coupon" value="<?php print $remise2;?>">
              
            </div></td>
        </tr>
        <tr> 
          <td height="25">&nbsp;</td>
        </tr>
        <tr>
        <td>
<?php
          if($tvaManuelValidation=="oui") print "<p class='FontGris'><i>(1) ".LA_DETAXE_DE_VOTRE_COMMANDE_EST_SOUMISE_A_LA_VERIFICATION."</i></p>";
?>
            <?php print DEVIS_BOTTOM;?>
        </td>
        </tr>
      </table></td>
  </tr>
</table>
