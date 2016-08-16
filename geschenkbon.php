<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = CHEQUE_CADEAU;

if(isset($_POST['action']) AND $_POST['action'] == "controler") {
    $queryControl = mysql_query("SELECT gc_number, gc_amount, gc_start, gc_end, gc_enter FROM gc WHERE gc_number = '".$_POST['gift_number']."'") or die (mysql_error());
    $queryControlNum = mysql_num_rows($queryControl);
    
    if($queryControlNum > 0) {
        $resultControl = mysql_fetch_array($queryControl);
        
        if($taxePosition=="Tax included") $langMess = TAXE_INCLUSE;
        if($taxePosition=="Plus tax") $langMess = TAXE_NON_INCLUSE;
        if($taxePosition=="No tax") $langMess = PAS_DE_TAXE;
        
        $messageAffDetails = NUMERO_CHEQUE."  <b>".$resultControl['gc_number']."</b><br>";
        $messageAffDetails .= ENTRER_LE_MONTANT."  ".$resultControl['gc_amount']." ".$symbolDevise."<br>";
        $messageAffDetails .= DATE_ACTIVATION."  ".$resultControl['gc_start']."<br>";
        
        $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $dateEndCheck = explode("-",$resultControl['gc_end']);
        $dateEnd = mktime(0,0,0,$dateEndCheck[1],$dateEndCheck[2],$dateEndCheck[0]);
        if($dateEnd >= $today) {
            if($resultControl['gc_enter'] == '1') {
                $cons = "<span class='fontrouge'><b>".UTILISE."</b></span>";
            }
            else {
                 $cons = "<span class='fontrouge'><b>".NON_UTILISE."</b></span>";
            }
            $expir = $resultControl['gc_end'];
        }
        else {
            $expir = "<span class='fontrouge'><b>".EXPIRE."</b></span>";
            if($resultControl['gc_enter'] == '0') {
                $cons = "<span class='fontrouge'><b>".CADEAU_EXPIRE_NON_UTILISE."</b></span>";
            }
            else {
                $cons = "<span class='fontrouge'><b>".UTILISE."</b></span>";
            }
        }
        
        
        $messageAffDetails .= DATEEXP." : ".$expir."<br><br>";
        
        $messageAffDetails .= STATUT." : ".$cons;
        

    }
    else {
        $messageAff = GC_NON_ENREGISTRE;
    }

}

?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
 
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<?php
if($gcActivate=="oui") {
?>

<script type="text/javascript"><!--
function check_form55() {
  var error = 0;
  var error_message = "";
  var gift_amount = document.form10.gift_amount.value;
  
if(document.form10.elements['gift_amount'].type != "hidden") {
    if(gift_amount == '' || isNaN(gift_amount)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?> <?php print ENTRER_LE_MONTANT;?>\n";
      error = 1;
    }
    if(gift_amount < <?php print $seuilGc;?>) {
      error_message = error_message + "<?php print VALEUR_MIN;?>\n";
      error = 1;
    }
  }
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}


function check_form555() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var gift_number = document.form100.gift_number.value;

  if(document.form100.elements['gift_number'].type != "hidden") {
    if(gift_number == '' ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NUMERO_CHEQUE;?>\n";
      error11 = 1;
    }
  }

  if(error11 == 1) {
    alert(error_message11);
    return false;
  } else {
    return true;
  }
}
//--></script>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
			
<?php 
//   header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

//   header2
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

// Menu tab
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
}
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
// MENU HORIZONTAAL
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}

if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;
?>

      <?php if($tableDisplay=='oui') {?>
      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
      <tr height="32">
      <?php if($tableDisplayLeft=='oui') {?>
      <td>
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php"><?php print maj(HOME);?></a> | <?php print CHEQUE_CADEAU;?> |</b>
      </td>
      <?php 
      }
      if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
      </tr>
      </table>
      <?php }?>

      <?php include('includes/promo_afbeelden.php');?>

    </td>
  </tr>
</table>

      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // -----
		  // links 
		  // -----
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

<?php
if(!isset($_SESSION['devisNumero'])) {
?>
            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">


                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                      <td><div class="titre"><?php print CHEQUE_CADEAU;?></div><p><?php print FAITE_PLAISIR;?></p></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table>


<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td>
  
                <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td class="TABLESousMenuPageCategory">
                <img src="im/fleche_menu.gif" align="absmiddle">&nbsp;<b><?php print maj(ACHETER_C);?></b>
                </td></tr></table>
  
  <br>
  <?php
if(isset($_SESSION['account']) OR $activeEcom=="oui") {
      print "<br>";
      print '<form action="add.php" method="GET" name="form10" onsubmit="return check_form55()">';
   //  
   $resultGc = mysql_query("SELECT products_id, products_ref, products_name_".$_SESSION['lang'].", products_tax, products_deee FROM products WHERE products_ref = 'GC100'");
   $resultNum = mysql_num_rows($resultGc);
   
   if($resultNum > 0) {
      $row_1 = mysql_fetch_array($resultGc);
        
      print "<input type='hidden' value='".$row_1['products_id']."' name='id'>";
      print "<input type='hidden' value='".$row_1['products_ref']."' name='ref'>";
      print "<input type='hidden' value='".$row_1['products_name_'.$_SESSION['lang']]."' name='name'>";
      print "<input type='hidden' value='".$row_1['products_tax']."' name='productTax'>";
      print "<input type='hidden' value='".$row_1['products_deee']."' name='deee'>";
      print "<input type='hidden' value='1' name='amount'>";
              
      print "<table border='0' cellpadding='3' cellspacing='0'>";
      print "<tr><td>".ENTRER_LE_MONTANT."&nbsp;</td><td align='left'><input type='text' name='gift_amount' maxlength='3' size='8' value='".sprintf("%0.2f",$seuilGc)."'>&nbsp;".$symbolDevise."</td></tr>";
      print "<tr><td>".AJOUTER_AU_CADDIE."&nbsp;</td><td align='left'><input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'></td></tr>";
      print "</table>";
      print '</form>';
    }
}
else {
      print "<table border='0' height='50' width='100%' cellpadding='1' cellspacing='0'>";
      print "<tr><td><a href='mijn_account.php'>".ENREG."</a></td></tr>";
      print "</table>";
}
  ?>


  </td>
  </tr>
  <tr>
    <td>
    
                <table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td class="TABLESousMenuPageCategory">
                <img src="im/fleche_menu.gif" align="absmiddle">&nbsp;<b><?php print maj(CONTROLE_CHEQUE);?></b>
                </td></tr></table>

<br>
  <?php
        print '<form action="geschenkbon.php" method="POST" name="form100" onsubmit="return check_form555()">';
        print "<input type='hidden' name='action' value='controler'>";
        print "<table border='0' cellpadding='1' cellspacing='0'>";
        print "<tr>";
        print "<td>".NUMERO_CHEQUE."&nbsp;&nbsp;</td><td align='left'><input type='text' name='gift_number' size='15' value=''></td>";
        print "<td>&nbsp;<input type='submit' size='10' value='".CONTROLER."'></td>";
        print "</tr>";
        print "</table>";
        print '</form>';
  ?>

    </td>
  </tr>

</table>

<?php
if(isset($messageAffDetails)) { print "<p align='center'>".$messageAffDetails."</p>";}
if(isset($messageAff)) { print "<p align='center' class='fontrouge'><b>".$messageAff."</b><br><br></p>";}
?>

                  </td>
              </tr>
            </table>

<?php
}
else {
        print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;";
        print "<b>";
        print NON_DISPONIBLE." !";
        print "</b>";
        print "</p>";
}
?>

          </td>

         <?php
		  // ------
		  // rechts
		  // ------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>

        </tr>
      </table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr>
</table>
<?php
}
else {
    print "<p align='center'>";
    print "<span class='TABLE1' style='padding:5px; FONT-SIZE:11px; FONT-WEIGHT:bold; letter-spacing:3px'>".$store_name."</span>";
    print "<br><br>";
    print "<a href='cataloog.php'><b><u>".maj(HOME)."</u></b></a>";
    print "</p>";
}
?>

</body>
</html>
