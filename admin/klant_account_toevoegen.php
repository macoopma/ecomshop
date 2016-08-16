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
$c="";
$date = date("Y-m-d H:i:s");

if(isset($_POST['actionPro']) AND $_POST['actionPro']=="proOk") {

if(isset($_POST['clientEmailPro']) AND !empty($_POST['clientEmailPro']) AND 
isset($_POST['clientGenderPro']) AND !empty($_POST['clientGenderPro']) AND  
isset($_POST['clientCityPro']) AND !empty($_POST['clientCityPro']) AND 
isset($_POST['clientPostCodePro']) AND !empty($_POST['clientPostCodePro']) AND 
isset($_POST['clientPaysPro']) AND !empty($_POST['clientPaysPro']) AND  
isset($_POST['clientTelephonePro']) AND !empty($_POST['clientTelephonePro']) AND  
isset($_POST['clientLastnamePro']) AND !empty($_POST['clientLastnamePro']) AND 
isset($_POST['clientFirstnamePro']) AND !empty($_POST['clientFirstnamePro'])) {


$stateCommentPro="";
$statePro1=0;
$queryPro1 = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_email= '".$_POST['clientEmailPro']."' ");
$queryPro1Num = mysql_num_rows($queryPro1);
if($queryPro1Num > 0) {$statePro1 = 1; $stateCommentPro = "<div align='center' class='fontrouge'><b>".EMAIL_ENR."</b></div>";} else {$statePro1 =0; $stateCommentPro = "";}

if(isset($_POST['clientTVAPro']) AND !empty($_POST['clientTVAPro'])) {
$statePro2=0;
$queryPro2 = mysql_query("SELECT users_pro_tva FROM users_pro WHERE users_pro_tva= '".$_POST['clientTVAPro']."' ");
$queryPro2Num = mysql_num_rows($queryPro2);
  if($queryPro2Num > 0) {
     $statePro2 = 1; 
     $stateCommentPro .= "<div align='center' class='fontrouge'><b>".TVA_ENR."</b></div>";
  } else {
     $statePro2 =0; 
     $stateCommentPro .= "";
  }
}
else {
  $statePro2 = 0;
  $stateCommentPro .= "";
}


$resultPro = $statePro1 + $statePro2;
if($resultPro > 0) {
    print $stateCommentPro;
}
else {

 
$datePro = date("Y-m-d H:i:s");


if(isset($_POST['clientNumberPro']) AND !empty($_POST['clientNumberPro'])) {
    $proPassword = $_POST['clientNumberPro'];
}
else {
     
    $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
		$proPassword = '';
    	for( $i = 0; $i < 10 ; $i++ ) {
        	$proPassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
        }
}
 
function replace_ap($val) {
   $_val = str_replace("'","&#146;",$val);
   return $_val;
}
 
$removeFromTva = array(" ", "-", ".", ",");
$_POST['clientTVAPro'] = str_replace($removeFromTva, "", $_POST['clientTVAPro']);

 
if(isset($_POST['clientTVAPro']) AND $_POST['clientTVAPro']=="") $_POST['users_pro_tva_active'] = "--";

      mysql_query("INSERT INTO users_pro
                   SET
                   users_pro_email = '".$_POST['clientEmailPro']."',
                   users_pro_gender = '".$_POST['clientGenderPro']."',
                   users_pro_company = '".replace_ap($_POST['clientCompPro'])."',
                   users_pro_address = '".replace_ap($_POST['clientStreetAddressPro'])."',
                   users_pro_city = '".replace_ap($_POST['clientCityPro'])."',
                   users_pro_postcode = '".$_POST['clientPostCodePro']."',
                   users_pro_country = '".replace_ap($_POST['clientPaysPro'])."',
                   users_pro_activity = '".replace_ap($_POST['clientFactActivitePro'])."',
                   users_pro_telephone = '".$_POST['clientTelephonePro']."',
                   users_pro_fax = '".$_POST['clientFaxPro']."',
                   users_pro_tva = '".$_POST['clientTVAPro']."',
                   users_pro_tva_confirm = '".$_POST['users_pro_tva_active']."',
                   users_pro_lastname = '".replace_ap($_POST['clientLastnamePro'])."',
                   users_pro_firstname = '".replace_ap($_POST['clientFirstnamePro'])."',
                   users_pro_poste = '".replace_ap($_POST['clientPostePro'])."',
                   users_pro_comment = '".replace_ap($_POST['clientCommentPro'])."',
                   users_pro_password = '".$proPassword."',
                   users_pro_date_added = '".$datePro."',
                   users_pro_payable = '".$_POST['clientPayablePro']."'
                   ");
$message = "<p align='center' class='fontrouge'><b>".AJOUTE."</b></p>";
}
}
else {
$message = "<p align='center' class='fontrouge'><b>".CHAMP_NOT_VALID."</b></p>";
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
<p align="center" class="largeBold"><?php print A1;?></p>

<table border="0" cellpadding="5" cellspacing="5" align="center" class="TABLE" width="700"><tr>
<td align="center">
&bull;&nbsp;<a href="import/index.php"><?php print IMPORTER;?></a>
</tr></td></table>
<br>

<?php
if(isset($message) AND $message!=='') print $message;
?>

<script language="javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var clientFirstnamePro = document.form101.clientFirstnamePro.value;
  var clientLastnamePro = document.form101.clientLastnamePro.value;
  var clientEmailPro = document.form101.clientEmailPro.value;
  var clientStreetAddressPro = document.form101.clientStreetAddressPro.value;
  var clientPostCodePro = document.form101.clientPostCodePro.value;
  var clientCityPro = document.form101.clientCityPro.value;
  var clientPaysPro = document.form101.clientPaysPro.value;
  var clientTelephonePro = document.form101.clientTelephonePro.value;

  if(document.form101.elements['clientEmailPro'].type != "hidden") {
    if(clientEmailPro == '' || clientEmailPro.length < 6 || clientEmailPro.indexOf ('@') == -1 || clientEmailPro.indexOf ('.') == -1 ) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientStreetAddressPro'].type != "hidden") {
    if(clientStreetAddressPro == '' || clientStreetAddressPro.length < 5) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientCityPro'].type != "hidden") {
    if(clientCityPro == '' || clientCityPro.length < 3) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print VILLE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientPostCodePro'].type != "hidden") {
    if(clientPostCodePro == '' || clientPostCodePro.length < 4) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print CODE_POSTAL;?>\n";
      error11 = 1;
    }
  }  
  if(document.form101.elements['clientPaysPro'].type != "hidden") {
    if(clientPaysPro == '' || clientPaysPro.length < 3) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print PAYS;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientTelephonePro'].type != "hidden") {
    if(clientTelephonePro == '' || clientTelephonePro.length < 4) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> Telefoon\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientGenderPro'].type != "hidden") {
    if(document.form101.clientGenderPro[0].checked || document.form101.clientGenderPro[1].checked) {
    } else {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print CIVILITE;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientLastnamePro'].type != "hidden") {
    if(clientLastnamePro == '' || clientLastnamePro.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error11 = 1;
    }
  }
  if(document.form101.elements['clientFirstnamePro'].type != "hidden") {
    if(clientFirstnamePro == '' || clientFirstnamePro.length < 2) {
      error_message11 = error_message11 + "<?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
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

<form action="klant_account_toevoegen.php" method="POST" name="form101" onsubmit="return formu()";>
<input type="hidden" name="actionPro" value="proOk">

  <table border="0" width="700" cellspacing="5" cellpadding="0" align="center">
    <tr>
      <td valign="top">
      
        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE1">
         <tr>
            <td width="150" valign=top><?php print NUM_CLIENT." ";?>
            </td>
            <td align="left">
              <input type="text" name="clientNumberPro"  class="vullen" size="20" value=""><br>
              <?php print CONTROL." ";?>
            </td>
          </tr>
        </table>
        
        </td>
        </tr>
    <tr>
      <td valign="top">
      
      
        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE">
         <tr>
            <td width="150"><?php print ADRESSE_EMAIL;?>&nbsp;</td>
            <td>&nbsp;
              <input type="text"  class="vullen" name="clientEmailPro" size="40" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
        </table>
        
        </td>
        </tr>
    <tr>
      <td valign="top">
      
      
        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE">
         <tr>
            <td width="150"><?php print PAYABLE;?>&nbsp;</td>
            <td>&nbsp;
              <select name="clientPayablePro">
              <option value="0" selected><?php print CASH;?></option>
              <option value="30"><?php print _30_DAYS;?></option>
              <option value="60"><?php print _60_DAYS;?></option>
              <option value="90"><?php print _90_DAYS;?></option>
              </select>
            </td>
          </tr>
        </table>

        
 
      </td>
    </tr>
    <tr>
    <tr>
      <td>
        <b><?php print COO_PRO;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE">
          <tr>
            <td><?php print COMPAGNIE2;?>&nbsp;</td>
            <td>
			&nbsp;<input type="text" class="vullen" name="clientCompPro" size="30" value="">
			</td>
          </tr>
          <tr>
            <td valign=top><?php print ADRESSE;?>&nbsp;</td>
            <td>
              &nbsp;<input type="text" class="vullen" name="clientStreetAddressPro" size="30" class="vullen"" value=""></textarea>&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td><?php print VILLE;?>&nbsp;</td>
			<td>
              &nbsp;<input type="text" class="vullen" name="clientCityPro" size="20" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
          <tr>
            <td><?php print CODE_POSTAL;?>&nbsp;</td>
			<td>
              &nbsp;<input type="text"  class="vullen" name="clientPostCodePro" size="10" value="">&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
           <tr>
            <td><?php print PAYS;?>&nbsp;</td>
			<td>
<?php
 
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
              &nbsp;<select name="clientPaysPro">
                <option value="no">----</option>
                <?php
                while ($countries = mysql_fetch_array($pays)) {
                  print "<option value='".$countries['countries_name']."'>".$countries['countries_name']."</option>";
                }
                ?>
              </select>&nbsp;&nbsp;<span class="fontrouge">*</span>
			</td>
          </tr>
            <tr>
            <td width=150><?php print DOMAINE_ACTIVITY;?>&nbsp;</td>
			<td>
              &nbsp;<input type="text" class="vullen"  name="clientFactActivitePro" size="20" value="">
			</td>
          </tr>
        </table>
        
        
      </td>

    </tr>
    <tr>
      <td valign="top">
      
        <table border="0" width="100%" cellspacing="0" cellpadding="3" class="TABLE">
          <tr>
            <td width="150"><?php print NUMERO_DE_TELEPHONE;?>&nbsp;</td>
            <td align="left">&nbsp;
              <input type="text"  class="vullen" name="clientTelephonePro" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span>
            </td>
          </tr>
          <tr>
            <td width="150"><?php print NUMERO_DE_FAX;?>&nbsp;</td>
            <td align="left">&nbsp;
              <input type="text" class="vullen"  name="clientFaxPro" value="">
              &nbsp;</td>
          </tr>
        </table>
        
      </td>
      </tr>
      <tr>
      <td align="top">


        <table border="0" width="100%" cellspacing="0" cellpadding="3" class="TABLE">
         <tr>
            <td width="150" valign=top><?php print NO_TVA;?>
            </td>
            <td align="left">&nbsp;
              <input type="text" name="clientTVAPro"  class="vullen"  size="30" value="">
              (<?php print LAISSER_VIDE;?>)
            </td>
          </tr>
         <tr>
            <td width="150"><?php print NO_TVA." ".VALLIDE;?>
            </td>
            <td align="left">&nbsp;
              <select name="users_pro_tva_active">
              <option value="yes"><?php print OUI;?></option>
              <option value="no"><?php print NON;?></option>
              <option value="??" selected>??</option>
              </select>
            </td>
          </tr>
        </table>
        
        
        </td>
      </tr>
      <tr>
      <td align="top">
      
              
        <table border="0" width="700" cellspacing="0" cellpadding="5" class="TABLE">
         <tr>
            <td width="150"><?php print REMISE." ";?>
            </td>
            <td align="left"><input type="text" size="6"  class="vullen" name="clientReducPro" size="30" value="0.00">&nbsp;%
            </td>
          </tr>
        </table>

      </td>
    </tr>
    <tr>
      <td><br>
      
        <b><?php print COO_PERSO;?></b></td>
    </tr>
    <tr>
      <td valign="top">

        <table border="0" width="700" cellspacing="0" cellpadding="3" class="TABLE">
        <tr>
            <td>&nbsp;<?php print CIVILITE;?>&nbsp;</td>
            <td>&nbsp;
              <input type="radio" name="clientGenderPro" value="M">
              &nbsp;<?php print M;?>&nbsp;&nbsp;
              <input type="radio" name="clientGenderPro" value="Mme">
              &nbsp;<?php print MME;?>&nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print NOM;?></td>
            <td>&nbsp;<input type="text" class="vullen" name="clientLastnamePro" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print PRENOM;?>&nbsp;</td>
            <td>&nbsp;<input type="text" class="vullen"  name="clientFirstnamePro" size="20" value="">
              &nbsp;&nbsp;<span class="fontrouge">*</span></td>
          </tr>
          <tr>
            <td>&nbsp;<?php print POSTE;?>&nbsp;</td>
            <td>&nbsp;<input type="text" class="vullen"  name="clientPostePro" size="30" value=""></td>
          </tr>
        </table>
</td>
</tr><tr>
    
    
    
    
      <td> <br>
        <b><?php print COMMENTAIRES;?></b></td>
    </tr>
    <tr>
      <td valign="top">
        <table border="0" width="700" cellspacing="0" cellpadding="5" class="TABLE">
          <tr>
            <td align="left">
              <textarea name="clientCommentPro" rows="6" cols="80" class="vullen" ></textarea>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
<table border="0" width="700" cellspacing="5" cellpadding="0" align="center">
  <tr>
    <td align="center"><INPUT TYPE="submit" class="knop" VALUE="<?php print UPDATE;?>"></td>
  </tr>
  <tr>
    <td align="left"><span class="fontrouge">*</span> <?php print A13;?></td>
  </tr>
</table>

</form>

<br><br><br>
  </body>
  </html>
