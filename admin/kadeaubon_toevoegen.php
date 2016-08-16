<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");
$year = date("Y")+1;
$date3 = $year."-".date("m")."-".date("d");
$message = "";


function replace_ap($val) {
   $_val = str_replace("'","&#146;",$val);
   return $_val;
}

 
$str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
$codeGc = '';
for( $i = 0; $i < 12 ; $i++ ) {
   $codeGc .= substr($str1, rand(0, strlen($str1) - 1), 1);
}

 
if(isset($_POST['gc']) AND $_POST['gc']=="ok") {
// European date format DD-MM-YYYY START
$_POST['gc_activation'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['gc_activation']);
$_POST['gc_expiration'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['gc_expiration']);
// European date format DD-MM-YYYY END
 
if(empty($_POST['gc_activation'])) {
     $_POST['gc_activation'] = date("Y-m-d");
     $checkDebut = "ok";
}
else {
      $toto2 = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['gc_activation']);
      if($toto2 == true) {
          $checkDate = explode("-",$_POST['gc_activation']);
          $verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
             if($verifDate == true) {
                 $checkDebut = "ok";
                 $message .= "";
                 }
                 else {
                  $message .= "<p align='center' class='fontrouge'>".DATE_ACTIVATION_NOT_VALID."</p>";
                  $checkDebut = "notok";
                  }
             }
             else {
              $message .= "<p align='center' class='fontrouge'>".DATE_ACTIVATION_NOT_VALID."</p>";
              $ckeckDebut = "notok";
             }
}
 
if(empty($_POST['gc_expiration'])) {
     $_POST['gc_expiration'] = "2020-01-01";
     $checkFin = "ok";
}
else {
      $toto = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['gc_expiration']);
      if($toto == true) {
          $checkDate = explode("-",$_POST['gc_expiration']);
          $verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
             if($verifDate == true) {
                 $checkFin = "ok";
                 $message .= "";
             }
             else {
               $message .= "<p align='center' class='fontrouge'>".DATE_EXPIRATION_NOT_VALID."</p>";
               $checkFin = "notok";
             }
             }
             else {
               $message .= "<p align='center' class='fontrouge'>".DATE_EXPIRATION_NOT_VALID."</p>";
               $checkFin = "notok";
             }
}
 
               $dateMaxCheck = explode("-",$_POST['gc_expiration']);
               $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
               $dateDebutCheck = explode("-",$_POST['gc_activation']);
               $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
               if($dateMax < $dateDebut) {
                  $message .= "<p align='center' class='fontrouge'>".DATE_EXPIRATION_INFERIEURE."</p>";
                  $OnYVa = "notok";
               }
               else {
                  $OnYVa = "ok";
                  $message .= "";
               }

if($checkDebut == "ok" AND $checkFin == "ok" AND $OnYVa == "ok") {
   $query = mysql_query("SELECT gc_number FROM gc WHERE gc_number = '".$_POST['gc_code']."'");
   $queryNum = mysql_num_rows($query);
   if($queryNum > 0) {
      $result = mysql_fetch_array($query);
      $message = "<p align='center' class='fontrouge'><b>".$result['gc_code']. " ".DEJA_UTILISE."</b></p>";
   }
   else {
      mysql_query("INSERT INTO gc
                   SET
                   gc_number = '".trim(strtoupper($_POST['gc_code']))."',
                   gc_nic = 'ADMIN',
                   gc_start = '".$_POST['gc_activation']."',
                   gc_end = '".$_POST['gc_expiration']."',
                   gc_amount = '".abs($_POST['gc_amount'])."',
                   gc_payed = '1',
                   gc_comment = '".replace_ap($_POST['gc_comment'])."'
                   ");
      $message = "<center><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td><p align='center' class='fontrouge'><b>".CHEQUE_CADEAU_AJOUTE."</b></p>";
      $message.= "<form action='kadeaubon.php'><p align='center'><input type='submit' class='knop' value='Ga terug'></form></tr></td></table>";
      $suite=0;
   }
}
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print AJOUETER_CHEQUE_CADEAU;?></p>

<?php
if(isset($message) AND $message!=='') print $message;
if(isset($suite) AND $suite==0) exit;
?>

<script language="javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var gc_code = document.form101.gc_code.value;
  var gc_amount = document.form101.gc_amount.value;

  if(document.form101.elements['gc_code'].type != "hidden") {
    if(gc_code == '' || gc_code.length < 5) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NUMERO;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['gc_amount'].type != "hidden") {
    if(gc_amount == '' || isNaN(gc_amount)== true) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print MONTANT;?>\n";
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
//-->
</script>

<form action="kadeaubon_toevoegen.php" method="POST" name="form101" onSubmit="return formu()";>
<input type="hidden" name="gc" value="ok">
  <table border="0" cellspacing="5" cellpadding="0" align="center" width="700">
    <tr>
      <td><br>
      
        <b><?php print CHEQUE_CADEAU;?></b></td>
    </tr>
    <tr>
      <td valign="top">

        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE">
          <tr>
            <td valign=top>&nbsp;<?php print NUMERO;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
            <td>&nbsp;<input type="text" class="vullen" name="gc_code" value="<?php print $codeGc;?>">
              <br>&nbsp;(<?php print OU_5_CARACTERES_NIMINUM;?>)</td>
          </tr>
          <tr>
            <td>&nbsp;<?php print MONTANT;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
            <td>&nbsp;<input type="text" class="vullen" size="10" name="gc_amount" value="50.00">
              </td>
          </tr>
          <tr>
            <td valign=top>&nbsp;<?php print DATE_ACTIVATION;?></td>
            <!--// European date format DD-MM-YYYY START-->
            <!--<td>&nbsp;<input type="text" class="vullen" name="gc_activation" size="20" value="<?php print $date2;?>">
            <br>&nbsp;(<?php print EXEMPLE;?> : <?php print date("Y-m-d", mktime(0, 0, 0,date("m"),date("d"),date("Y")));?>)
            </td>-->
            <td>&nbsp;<input type="text" class="vullen" name="gc_activation" size="20" value="<?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$date2);?>">
            <br>&nbsp;(<?php print EXEMPLE;?> : <?php print date("d-m-Y", mktime(0, 0, 0,date("m"),date("d"),date("Y")));?>)
            </td>
            <!--// European date format DD-MM-YYYY END-->
          </tr>
          <tr>
            <td valign=top>&nbsp;<?php print DATE_EXPIRATION;?></td>
            <!--// European date format DD-MM-YYYY START-->
            <!--<td>&nbsp;<input type="text" class="vullen" name="gc_expiration" size="20" value="<?php print $date3;?>">
            <br>&nbsp;(<?php print EXEMPLE;?>: <?php print date("Y-m-d", mktime(0, 0, 0,date("m"),date("d"),date("Y")+1));?>)
            </td>-->
            <td>&nbsp;<input type="text" class="vullen" name="gc_expiration" size="20" value="<?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$date3);?>">
            <br>&nbsp;(<?php print EXEMPLE;?>: <?php print date("d-m-Y", mktime(0, 0, 0,date("m"),date("d"),date("Y")+1));?>)
            </td>
            <!--// European date format DD-MM-YYYY END-->
          </tr>
        </table>
</td>
</tr>
<tr>
   
      <td valign=top> <br>
        <b><?php print COMMENTAIRE;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="700" cellspacing="0" cellpadding="5" class="TABLE">
          <tr>
            <td align="center">
              <textarea name="gc_comment" rows="5" cols="60" class="vullen"></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<table border="0" width="700" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td align="center"><INPUT TYPE="submit" class="knop" VALUE="<?php print AJOUTER;?>"></td>
  </tr>
  <tr>
    <td align="left"><span class="fontrouge">*</span> <?php print CHAMPS_OBLIGATOIRES;?></td>
  </tr>
</table>

</form>


  </body>
  </html>
