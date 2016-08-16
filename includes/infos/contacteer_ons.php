<?php

if(isset($_POST['submit']) and $_POST['submit'] == ENVOYER) {

	 include('configuratie/configuratie.php');
	 
	 //adjust $_POST values
	 $_POST['name'] = str_replace(NOM.": ", "", $_POST['name']);
	 $_POST['firstname'] = str_replace(PRENOM.": ", "", $_POST['name']);
	 $_POST['email'] = str_replace(VOTRE_EMAIL.": ", "", $_POST['email']);
	 $_POST['comments'] = str_replace(COMMENTAIRES.": ", "", $_POST['comments']);
	 
	 $message = "Onderwerp: CONTACT\r\n---------------------------------------------------\r\n";
	 $message .= PRENOM.": ".$_POST['firstname']."\r\n";
	 $message .= NOM.": ".$_POST['name']."\r\n";
	 if(isset($_POST['entrepriseC'])) $message .= COMPAGNIE2." : ".$_POST['entrepriseC']."\r\n";
	 if(isset($_POST['telephoneC'])) $message .= TELEPHONE." : ".$_POST['telephoneC']."\r\n";
	 $message .= "E-mail: ".$_POST['email']."\r\n\r\n";
	 $message .= COMMENTAIRES.": ".$_POST['comments']."";
	 
	 if(isset($_GET['from']) AND $_GET['from']=='web') {$to = $mailWebmaster;} else {$to = $mailPerso;}
	 if(isset($_SESSION['account'])) {$clientIdNumber = $_SESSION['account']." ";} else {$clientIdNumber = '';}
	 $subject = "CONTACT ".$clientIdNumber."| ".$domaineFull;
	 mail($to, strip_tags($subject), strip_tags(rep_slash($message)),
		 "Return-Path: ".$_POST['email']."\r\n"
		 ."From: ".$_POST['email']."\r\n"
		."Reply-To: ".$_POST['email']."\r\n"
		."X-Mailer: PHP/" . phpversion());

	 $messageSendEmail = VOTRE_COURRIER_A_ETE_ENVOYE_AVEC_SUCCES." !";
	 $uuuu='true';
}
?>

<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";
  var firstname = document.toto.firstname.value;
  var name = document.toto.name.value;
  var email = document.toto.email.value;
  var comments = document.toto.comments.value;
  var firstnameName = "<?php print PRENOM;?>";
  var nameName = "<?php print NOM;?>";
  var emailName = "<?php print VOTRE_EMAIL;?>";
  var messageName = "<?php print MESSAGE;?>";
  var compagnieName = "<?php print COMPAGNIE2;?>";
  var telName = "<?php print TELEPHONE;?>";
  
<?php if($vb2b=='oui') { ?>
  var entrepriseC = document.toto.entrepriseC.value;
  var telephoneC = document.toto.telephoneC.value;

  if(document.toto.elements['entrepriseC'].type != "hidden") {
  varToCheck = entrepriseC.length - compagnieName.length - 2;
    if(entrepriseC == '' || varToCheck < 1) {
      error_message = error_message + " <?php print CHAMPS_NON_VALIDE;?> <?php print COMPAGNIE2;?>\n";
      error = 1;
    }
  }
  
   if(document.toto.elements['telephoneC'].type != "hidden") {
   	varToCheck = telephoneC.length - telName.length - 2;
    if(telephoneC == '' || varToCheck < 1) {
      error_message = error_message + " <?php print CHAMPS_NON_VALIDE;?> <?php print TELEPHONE;?>\n";
      error = 1;
    }
  }
<?php } ?>

  if(document.toto.elements['firstname'].type != "hidden") {
  	varToCheck = firstname.length - firstnameName.length - 2;
    if(firstname == '' || varToCheck < 1) {
      error_message = error_message + " <?php print CHAMPS_NON_VALIDE;?> <?php print PRENOM;?>\n";
      error = 1;
    }
  }

  if(document.toto.elements['name'].type != "hidden") {
  	varToCheck = name.length - nameName.length - 2;
    if(name == '' || varToCheck < 1) {
      error_message = error_message + " <?php print CHAMPS_NON_VALIDE;?> <?php print NOM;?>\n";
      error = 1;
    }
  }


  if(document.toto.elements['email'].type != "hidden") {
  	varToCheck = email.length - emailName.length - 2;
    if(email == '' || varToCheck < 1 || email.indexOf ('@') == -1 || email.indexOf ('.') == -1 ) {
      error_message = error_message + " <?php print CHAMPS_NON_VALIDE;?> <?php print ADRESSE_EMAIL;?>\n";
      error = 1;
    }
  }

  if(document.toto.elements['comments'].type != "hidden") {
  	varToCheck = comments.length - messageName.length - 2;
    if(comments == '' || varToCheck < 1) {
      error_message = error_message + " <?php print VEUILLEZ_LAISSER_UN_MESSAGE;?>\n";
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
//--></script>

<?php
if(isset($messageSendEmail) AND !empty($messageSendEmail)) print "<p align='center' class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".$messageSendEmail."</p>";
if(isset($state) AND $state!=="") print $state;

if(isset($_SESSION['account'])) {
    $queryAccount = mysql_query("SELECT users_pro_lastname, users_pro_firstname, users_pro_email, users_pro_company, users_pro_telephone FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
    $queryAccountResult = mysql_fetch_array($queryAccount);
    $name1 = $queryAccountResult['users_pro_lastname'];
    $firstname1 = $queryAccountResult['users_pro_firstname'];
    $email1 = $queryAccountResult['users_pro_email'];
    $entrepriseC1 = $queryAccountResult['users_pro_company'];
    $telephoneC1 = $queryAccountResult['users_pro_telephone'];
}
?>

<form action="<?php print $url_id10;?>" method="POST" name="toto" onsubmit="return check_form()" id="formG">
<input type="hidden" name="title" value="Communication client">

  <table border="0" cellpadding="0" cellspacing="6" align="center" width='300'>
  		<tr>
			<td align="center">
            	<input type="text" style='width:99%' maxlength="75" name="name" value="<?php print NOM;?>: <?php if(isset($name1)) print $name1;?>">
        	</td>
    		<td>
            	<div align="left"><b>*</b></div>
        	</td>
    	</tr>
        
    	<tr>
        	<td align="center">
            	<input type="text" style='width:99%' maxlength="75" name="firstname" value="<?php print PRENOM;?>: <?php if(isset($firstname1)) print $firstname1;?>">
        	</td>
        	<td>
        		<div align="left"><b>*</b></div>
        	</td>
        </tr>
        
        <tr>
        	<td align="center">
            	<input type="text" style='width:99%' maxlength="256" name="email" rows="1" value="<?php print VOTRE_EMAIL;?>: <?php if(isset($email1)) print $email1;?>">
        	</td>
        	<td>
        		<div align="left"><b>*</b></div>
        	</td>
    	</tr>
        
        <tr>
        	<td align="center">
            	<textarea name="comments" rows="7" cols="50" style='width:99%'><?php print MESSAGE;?>: <?php if(isset($comments1)) print $comments1;?></textarea>
        	</td>
        	<td>
    			<div align="left"><b>*</b></div>
        	</td>
    	</tr>

        <?php 
        if($vb2b=='oui') {
        ?>
        <tr>
        	<td align="center">
            	<input type="text" style='width:99%' maxlength="256" name="entrepriseC" rows="1" value="<?php print COMPAGNIE2;?>: <?php if(isset($entrepriseC1)) print $entrepriseC1;?>">
        	</td>
        	<td>
    			<div align="left"><b>*</b></div>
        	</td>
        </tr>
        
        <tr valign="middle">
        	<td>
            	<input type="text" style='width:99%' maxlength="256" name="telephoneC" rows="1" value="<?php print TELEPHONE;?>: <?php if(isset($telephoneC1)) print $telephoneC1;?>">
        	</td>
			<td>
    			<div align="left"><b>*</b></div>
        	</td>
        </tr>  
        <?php 
        }
         ?>

        <tr valign="top">
          <td>
          	<div><img src="im/zzz.gif" width="1" height="10"></div>
            <div align="center">
				<input type="submit" value="<?php print ENVOYER;?>" name="submit">
				<input type="reset" value="<?php print ANNULER;?>" name="reset">
            </div>
          </td>
        </tr>

      </table>
                </form>
