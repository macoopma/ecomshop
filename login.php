<?php
include('configuratie/configuratie.php');
include('includes/plug.php');

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = IDVOUS;


if(isset($_SESSION['list']) AND !empty($_SESSION['list']) AND !isset($_SESSION['totalTTC'])) {
    $art = explode(",",$_SESSION['list']);
    $nb_article = count($art)-1;
    
    $_SESSION['tot_art'] = 0;
    $_SESSION['totalTTC'] = 0;
    
    for($n=0; $n <= $nb_article; $n++) {
        $article = explode("+",$art[$n]);
        $_SESSION['tot_art'] = $_SESSION['tot_art'] + $article[1];
        $_SESSION['totalTTC'] = $_SESSION['totalTTC'] + ($article[2]*$article[1]);
    }
}
 
if(isset($_POST['account']) and !empty($_POST['account'])) {
   $accountRequest = mysql_query("SELECT users_pro_reduc 
                                    FROM users_pro
   						            WHERE  users_pro_password = '".$_POST['account']."'
   						            AND users_pro_email = '".$_POST['email']."'
   						            AND users_pro_active = 'yes'");
   $accountRequestNum = mysql_num_rows($accountRequest);
   
if($accountRequestNum > 0) {
    $reducResult = mysql_fetch_array($accountRequest);
   $_SESSION['openAccount'] = "yes";
   $_SESSION['account'] = $_POST['account'];
   $_SESSION['reduc'] = $reducResult['users_pro_reduc'];
  
   if(isset($_SESSION['list']) AND $_SESSION['list']!=="" AND $_SESSION['reduc']>0) {
            $split2q = explode(",",$_SESSION['list']);
            foreach ($split2q as $item2q) {
               $check2q = explode("+",$item2q);
               if($check2q[3] !== "GC100") {
                     $toto2q[] = $check2q[0]."+".$check2q[1]."+".newPrice($check2q[2],$_SESSION['reduc'])."+".$check2q[3]."+".$check2q[4]."+".$check2q[5]."+".$check2q[6]."+".$check2q[7]."+".$check2q[8];
               }
               else {
                     $toto2q[] = $check2q[0]."+".$check2q[1]."+".$check2q[2]."+".$check2q[3]."+".$check2q[4]."+".$check2q[5]."+".$check2q[6]."+".$check2q[7]."+".$check2q[8];
               }
            }
            $_SESSION['list'] = implode(",",$toto2q);
   }
   header("Location: payment.php");
   exit;
}
else {
   	$message112 = "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".AUCUNE_COMMANDE."</p>";
}
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<script type="text/javascript"><!--

function check_form3() {
  var error3 = 0;
  var error_message3 = "";
  var account = document.form3.account.value;
  var email = document.form3.email.value;
  
  if(document.form3.elements['account'].type != "hidden") {
    if(account == '') {
      error_message3 = error_message3 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print NUMERO_DE_CLIENT;?>'.\n";
      error3 = 1;
    }
  }
  if(document.form3.elements['email'].type != "hidden") {
    if(email == '' || email.length < 6 || email.indexOf ('@') == -1 || email.indexOf ('.') == -1 ) {
      error_message3 = error_message3 + "<?php print CHAMPS_NON_VALIDE;?> '<?php print ADRESSE_EMAIL;?>'.\n";
      error3 = 1;
    }
  }
  if(error3 == 1) {
    alert(error_message3);
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
 
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}
 
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

 
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
                 <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print IDVOUS;?> |
                 </b>
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

        <tr valign="top">
        <td colspan="3">


                     <table width="100%" border="0" cellpadding="3" cellspacing="5">
                     <tr>
          <?php
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">


            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top">


<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td align="left">
                         <?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".IDVOUS." |";?>
                      </td>
                    </tr>
                  </table>
<br>
<?php
}
if(isset($message112)) print $message112;

if(isset($_SESSION['devisNumero'])) $seuil = 0; else $seuil = $minimumOrder;

if(!isset($_SESSION['list']) OR $_SESSION['list'] == "" OR empty($_SESSION['list']) OR $_SESSION['tot_art']=="0" OR $_SESSION['totalTTC'] < $seuil OR $paymentsDesactive=="oui") {
   print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='0'>
            <tr>
             <td class='titre' align='center'>";
             if(isset($_SESSION['totalTTC']) and $_SESSION['totalTTC'] < $minimumOrder) {
                $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".COMMANDE_MINIMUM.": ".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</p>";
             }
             else {
                $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE."</p>";
             }
             if($paymentsDesactive=="oui")  $displayMessage = "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".COMMANDER_NO."</p>";
	print $displayMessage;
  print "</td>
            </tr>
           </table>";
}
else {
?>
<p align='center'><?php print VOUS_DEVEZ_VOUS_IDENTIFIER;?></p>
               <table align="center" width="450" border="0" cellspacing="5" cellpadding="0">
               <tr>
               <td class="TABLEPaymentProcess" style='padding:6px' align='center' width='50%'><b><?php print PREM." ".$store_name;?>...</b></td>
               <td class="TABLEPaymentProcess" style='padding:6px' align='center'><b><?php print DEJA_CLIENT;?></b></td>
               </tr>
               <tr>
               <td valign="top">
                      <table width="100%" border="0" height="250" cellspacing="0" cellpadding="5" class="TABLE1"><tr>
                      <td valign="top">
                      <img src="im/fleche_right.gif">&nbsp;<?php print FIRST_ORDER;?>
                      </td>
                      </tr>
                      <tr>
                      <td valign="middle" align="center" height="35">
                        <a href='login_aanmaken.php'><img src='im/lang<?php print $_SESSION['lang'];?>/open_acc.gif' border='0'></a>
                      </td>
                      </tr>
                      </table>
               </td>
               <td valign="top">
                      <table width="100%" border="0" height="250" cellspacing="0" cellpadding="5" class="TABLE1"><tr>
                      <form action="login.php" method="POST" name="form3" onsubmit="return check_form3()">
                      <td valign="top">
                      <img src="im/fleche_right.gif">&nbsp;<b><?php print IDVOUS;?></b><br><br>
                      
                      <table width="100%" border="0" cellspacing="0" cellpadding="2"><tr>
                      <td><?php print NUMERO_DE_CLIENT;?></td><td><input type="text" name="account" size="14"></td></tr>
                      <td><?php print ADRESSE_EMAIL;?></td><td><input type="text" name="email" size="14"></td></tr>
                      </table>
                      
                      <br>
                      <div>
 
                      <?php print SI_VOUS_N_AVEZ_PAS2;?><b><a href="javascript:void(0);" onClick="window.open('wachtwoord_vergeten.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=150,width=400,toolbar=no,scrollbars=no,resizable=yes');"><?php print ICI;?></a></b>
  
                      </div>
                      </td>
                      </tr><tr>
                      <td valign="middle" align="center" height="35">
                        <INPUT style="BACKGROUND:none; border:0px" align="absmiddle" type="image" src="im/lang<?php print $_SESSION['lang'];?>/idfier.gif">
                      </td>
                      </form>
                      </tr>
                      </table>
               </td>
               </tr>
               </table>

               <p><?php print FIRST_ORDER_NOTE;?></p>
<?php
}
?>
                </td>
              </tr>
            </table>



          </td>

         <?php 
		  // --------------------------------------
		  // kolom rechts
		  // --------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
</tr>
</table>

</td>
</tr>
</table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
