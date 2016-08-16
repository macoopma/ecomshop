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
$messageLiv="";
if(isset($_GET['id'])) $_POST['id']=$_GET['id'];


function remApos($rem) {
    $_rem = str_replace("'","&#146;",$rem);
    return $_rem;
}

$tarifRequest2 = mysql_query("SELECT id FROM ship_price WHERE livraison_id = '".$_POST['id']."'");
$pricesNum = mysql_num_rows($tarifRequest2);

if(isset($_POST['action']) AND $_POST['action']=="updateShippingMode" AND isset($_POST['id']) AND $_POST['id']!=="") {
	if($_POST['name_1']=="" AND $_POST['name_2']=="" AND $_POST['name_3']=="") $messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_ENTRER_UN_MODE_DE_LIV."</b></div>";
	if(!isset($_POST['selectPays'])) $messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_SELECTIONNER_AU_MOINS_UN_PAYS."</b></div>";
	if($_POST['poid']=="" OR $_POST['poid']=="0" OR !is_numeric($_POST['poid'])) {$messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_SELECTIONNER_POIDS."</b></div>"; $poi=0;} else {$poi=1;}
	if(!is_numeric($_POST['day1']) OR !is_numeric($_POST['day2']) OR $_POST['day1']=="" OR $_POST['day2']=="" OR $_POST['day1']=="0" OR $_POST['day2']=="0") {$messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_ENTRER_DELAI_DE_LIVRAISON."</b></div>"; $delay=0;} else {$delay=1;}

	if(isset($_FILES["uploadShippingImage"]["name"]) AND !empty($_FILES["uploadShippingImage"]["name"])) {
 
		$nomFichier    = $_FILES["uploadShippingImage"]["name"];
 
		$nomTemporaire = $_FILES["uploadShippingImage"]["tmp_name"];
 
		$typeFichier   = $_FILES["uploadShippingImage"]["type"];
 
		$poidsFichier  = $_FILES["uploadShippingImage"]["size"];
 
		$codeErreur    = $_FILES["uploadShippingImage"]["error"];
 
		$chemin = "../im/";
		if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
			if(copy($nomTemporaire, $chemin.$nomFichier)) {
				$_POST['image'] = str_replace("../","",$chemin).$nomFichier;
				$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".</div>";
			}
			else {
				$_POST['image'] = "";
				$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
			}
		}
		else {
			$_POST['image'] = "";
			$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</div>";
		}
	}


	## update bdd
	if(isset($_POST['selectPays']) AND $poi==1 AND $delay==1 AND ($_POST['name_1']!=="" OR $_POST['name_2']!=="" OR $_POST['name_3']!=="")) {

	$pays = implode("|",$_POST['selectPays']);
	$pays = "|".$pays."|";
	if(isset($pricesNum) AND $pricesNum==0) $_POST['active']="no";
    
		mysql_query("UPDATE ship_mode
                     SET
                      livraison_nom_1 = '".remApos($_POST['name_1'])."',
                      livraison_nom_2 = '".remApos($_POST['name_2'])."',
                      livraison_nom_3 = '".remApos($_POST['name_3'])."',
                      livraison_country = '".$pays."',
                      livraison_image = '".$_POST['image']."',
                      livraison_active = '".$_POST['active']."',
                      livraison_max = '".$_POST['poid']."',
                      livraison_note_1 = '".remApos($_POST['note_1'])."',
                      livraison_note_2 = '".remApos($_POST['note_2'])."',
                      livraison_note_3 = '".remApos($_POST['note_3'])."',
                      livraison_delay_1 = '".$_POST['day1']."',
                      livraison_delay_2 = '".$_POST['day2']."'
                      WHERE livraison_id = '".$_POST['id']."'
                     ") or die (mysql_error());
                     
		$messageLiv.= "<p><b>".LE_MODE_DE_LIVRAISON." '".remApos($_POST['name_'.$_SESSION['lang']])."' werd succesvol gewijzigd</b></p>";
		$w = "ok";
	}
}


$requestLiv = mysql_query("SELECT * FROM ship_mode WHERE livraison_id = '".$_POST['id']."'");
$resultLiv = mysql_fetch_array($requestLiv);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold">Wijzigen van een verzend firma</p>
<p align="center" class="large">-- <b><?php print $resultLiv['livraison_nom_'.$_SESSION['lang']];?></b> --</p>
<p align="center"><table align='center' cellspacing='0' cellpadding='6' class='TABLE' width='700'><tr><td align=center><a href='verzendprijzen.php?shipId=<?php print $_POST['id'];?>'><b><?php print VOIR_TARIF;?> - <?php print strtoupper($resultLiv['livraison_nom_'.$_SESSION['lang']]);?></b></a></tr></td></table>
<?php

if(isset($messageLiv) AND $messageLiv!=="") {
   if(isset($w) AND $w=="ok") $couleur="#66CC00"; else $couleur="#FF0000";
   print "<p>";
   print "<table align='center' cellspacing='0' cellpadding='8' class='TABLE' width='700'><tr>";
   print "<td align='center'>";
   print $messageLiv;
   print "</td>";
   print "</tr></table>";
   print "</p>";
   if(isset($w) AND $w=="ok") {
      print "<form method='GET' action='verzendfirma_wijzigen.php'><p align='center'><input type='submit' class='knop' value='".RETOUR."'></p></form>";
   }
   else {
      print "<form method='GET' action='verzendfirma_details.php'><p align='center'><input type='hidden' name='id' value='".$_POST['id']."'><input type='submit' value='".RETOUR."'></p></form>";
   }
   exit;
}
?>

<form method="POST" action="verzendfirma_details.php?id=<?php print $_POST['id'];?>" enctype='multipart/form-data'>
<input type="hidden" name="action" value="updateShippingMode">
<input type="hidden" name="id" value="<?php print $_POST['id'];?>">

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>

<table width="100%" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<?php
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
    
if(isset($pricesNum) AND $pricesNum==0) {
    print "<tr bgcolor='#000000'>";
    print "<td colspan='3' align='center' style='color:#FFFFFF; font-size:13px; padding:8px;'>";
    print "<b>".TARIFS_NOT_DEFINIS."</b>";
    print "</td>";
    print "</tr>";
}
    
?>
    <tr>
      <td width='200' valign='top'><?php print MODE_DE_LIVRAISON;?>&nbsp;*
      <div><img src="ìm/zzz.gif" width="110" height="1"></div>
      </td>
      <td colspan="2">
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_3" value="<?php print $resultLiv['livraison_nom_3'];?>">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_1" value="<?php print $resultLiv['livraison_nom_1'];?>">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_2" value="<?php print $resultLiv['livraison_nom_2'];?>">
      </td>
    </tr>

<?php 
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
if($resultLiv['livraison_active'] == "yes") {
    $checkLivYes = "checked";
    $checkLivNo = "";
    $c = "#CCFFCC";
} else {
    $checkLivYes = "";
    $checkLivNo = "checked";
    $c = "#FF0000";
}
?>
    <tr bgcolor="<?php print $c;?>">
      <td width='200' valign='top'><?php print ACTIVE;?></td>
      <td colspan="2">
      <?php print OUI;?> <input type="radio" value="yes" name="active" <?php print $checkLivYes;?>> <?php print NON;?> <input type="radio" value="no" name="active" <?php print $checkLivNo;?>>
      </td>
    </tr>
  
<?php 
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width='200' valign='top'>Logo</td>
      <td colspan="2">


			&nbsp;&bull;&nbsp;Upload<br>&nbsp;<input type='file' name='uploadShippingImage' class='vullen' size='40'>

			<div><img src='im/zzz.gif' width='1' height='5'></div>
			&nbsp;<input type="text" class="vullen" size="30" name="image" value="<?php print $resultLiv['livraison_image'];?>">&nbsp(gif, jpg, png)<br>
			&nbsp;Maximale breedte 100 pixels. Voorbeeld http://www.domeinnaam.com/logo.gif 
  
      </td>
    </tr>

<?php // logo
if($resultLiv['livraison_image']!=="") {
    print "<tr>";
    print "<td width='200' valign='top'>Logo</td>";
    print "<td colspan='2'>";
    print (preg_match("#http:#i",$resultLiv['livraison_image']))? "<img src='".$resultLiv['livraison_image']."'>" : "<img src='../".$resultLiv['livraison_image']."'>";
    print "</td>";
    print "</tr>";
}
?>

<?php // landen
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width='200' valign='top'>
      <?php print "".PAYS."&nbsp;*<br>".LIRE_NOTE." 1";?>
      </td>
      <td width="1">
      <?php
      if($resultLiv['livraison_country']!=="") {
         $pays2 = explode("|", $resultLiv['livraison_country']);
      }
      $queryPays = mysql_query("SELECT * FROM countries WHERE countries_shipping!='exclude' ORDER BY countries_name");
      print "<select name='selectPays[]' size='10' multiple>";
      while ($resultPays = mysql_fetch_array($queryPays)) {
         if(in_array($resultPays['countries_id'], $pays2))  {$sel="selected";} else {$sel="";}
         print "<option value='".$resultPays['countries_id']."' ".$sel.">".$resultPays['countries_name']."</option>";
      }
      print "</select>";
      ?>
      </td>
      <td width='200' valign='top'>
      <?php
 
      $openLeg2 = "<a href=\"javascript:void(0);\" onClick=\"window.open('uitleg.php?open=liv&id=".$resultLiv['livraison_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
      print $openLeg2.VOIR_LISTE_DES_PAYS."</a>";
      ?>
      </td>
    </tr>

<?php // gewicht
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width='200' valign='top'>
      <?php print POIDS_MAXIMUM;?>&nbsp;*
      </td>
      <td colspan="2">
        <input type="text" class="vullen" name="poid" maxlength="9" value="<?php print $resultLiv['livraison_max'];?>" size="11">&nbsp;Gr.
        <br>(<?php print ENTREZ_999999999;?>)
      </td>
    </tr>

<?php // levering
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width='200' valign='top'>
      <?php print DELAI_DE_LIVRAISON;?>&nbsp;*
      </td>
      <td colspan="2">
        <?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day1" value="<?php print $resultLiv['livraison_delay_1'];?>" size="3"> <?php print JOURS_ET;?>&nbsp;&nbsp;<input type="text" name="day2" value="<?php print $resultLiv['livraison_delay_2'];?>" size="3" class="vullen" > <?php print JOURS;?>
      	<br>(<?php print ENTREZ_100_SI_PAS_DE_DELAI;?>)
	  </td>
    </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">


<?php 

if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width='100' valign='top'>
      Lees de nota onderaan
       </td>
      <td colspan="2">
          <table border="0" align="center" cellpadding="1" cellspacing="0">
          <tr><td valign='top'><img src='im/be.gif' align='absmiddle'><img src='im/nl.gif' align='absmiddle'>&nbsp;</td><td>
            <textarea name="note_3" rows="7" cols="70"><?php print $resultLiv['livraison_note_3'];?></textarea>
	      </td></tr>
            <tr><td valign='top'><img src='im/leeg.gif' align='absmiddle'><img src='im/fr.gif' align='absmiddle'>&nbsp;</td><td>
            <textarea name="note_1" rows="7" cols="70"><?php print $resultLiv['livraison_note_1'];?></textarea>
            </td></tr>
            <tr><td valign='top'><img src='im/leeg.gif' align='absmiddle'><img src='im/uk.gif' align='absmiddle'>&nbsp;</td><td>
            <textarea name="note_2" rows="7" cols="70"><?php print $resultLiv['livraison_note_2'];?></textarea>



          </td></tr>
          </table>
      </td>
    </tr>

  </table>

  <br>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2" height="40" align="center"><input type="submit" value="<?php print UPDATE;?>" class='knop'></td>
</tr>
</table>
</form>

<br>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2">(*) <b><?php print CHAMPS_OBLIGATOIRES;?></b></td>
</tr>
<tr>
<td>
<b><u><?php print NOTE;?> 1</u></b>: 
<?php print UTILISEZ_LA_TOUCHE_SHIFT_OU_MAJ_POUR;?>
</td>
</tr>
<tr>
<td>
<b><u><?php print NOTE;?> 2</u></b>: 
<?php print LES_GUILLEMTS_SIMPLES_SONT_INTERDITS;?>
</td>
</tr>
</table>

</td></tr></table>
<br><br><br>

</body>
</html>
