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
 
function remApos($rem) {
    $_rem = str_replace("'","&#146;",$rem);
    return $_rem;
}

 
if(isset($_POST['action']) AND $_POST['action']=="addShippingMode") {
	if($_POST['name_1']=="" AND $_POST['name_2']=="" AND $_POST['name_3']=="") $messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_ENTRER_UN_MODE_DE_LIV."</b></div>";
	if(!isset($_POST['selectPays'])) $messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_SELECTIONNER_AU_MOINS_UN_PAYS."</b></div>";
	if($_POST['poid']=="" OR $_POST['poid']=="0" OR !is_numeric($_POST['poid'])) {$messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_SELECTIONNER_POIDS."</b></div>"; $poi=0;} else {$poi=1;}
	if(!is_numeric($_POST['day1']) OR !is_numeric($_POST['day2']) OR $_POST['day1']=="" OR $_POST['day2']=="") {$messageLiv.= "<div><b>&bull;&nbsp;".VEUILLEZ_ENTRER_DELAI_DE_LIVRAISON."</b></div>"; $delay=0;} else {$delay=1;}

	 
	if(isset($_FILES["uploadShippingImage"]["name"]) AND !empty($_FILES["uploadShippingImage"]["name"])) {
 
		$nomFichier1    =  $_FILES["uploadShippingImage"]["name"];
 
		$nomTemporaire1 = $_FILES["uploadShippingImage"]["tmp_name"];
 
		$typeFichier1   = $_FILES["uploadShippingImage"]["type"];
 
		$poidsFichier1  = $_FILES["uploadShippingImage"]["size"];
 
		$codeErreur1    = $_FILES["uploadShippingImage"]["error"];
 
		$chemin1 = "../im/";
		if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier1)) {
			if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
				$_POST['image'] = str_replace("../","",$chemin1).$nomFichier1;
				$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier1." ".A_REUSSI.".</div>";
			}
			else {
				$_POST['image'] = "";
				$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier1." ".A_ECHOUE."</div>";
			}
		}
		else {
			$_POST['image'] = "";
			$messageLiv.= "<div align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." ".$nomFichier1." ".A_ECHOUE."</div>";
		}
	}

	
 
   if(isset($_POST['selectPays']) AND $poi==1 AND $delay==1 AND ($_POST['name_1']!=="" OR $_POST['name_2']!=="" OR $_POST['name_3']!=="")) {

   $pays = implode("|",$_POST['selectPays']);
   $pays = "|".$pays."|";
        mysql_query("INSERT INTO ship_mode
                     (
                      livraison_nom_1,
                      livraison_nom_2,
                      livraison_nom_3,
                      livraison_country,
                      livraison_image,
                      livraison_active,
                      livraison_max,
                      livraison_note_1,
                      livraison_note_2,
                      livraison_note_3,
                      livraison_delay_1,
                      livraison_delay_2
                      )
                     VALUES ('".remApos($_POST['name_1'])."',
                             '".remApos($_POST['name_2'])."',
                             '".remApos($_POST['name_3'])."',
                             '".$pays."',
                             '".$_POST['image']."',
                             '".$_POST['active']."',
                             '".$_POST['poid']."',
                             '".remApos($_POST['note_1'])."',
                             '".remApos($_POST['note_2'])."',
                             '".remApos($_POST['note_3'])."',
                             '".$_POST['day1']."',
                             '".$_POST['day2']."'
                             )
                     ");
        $messageLiv.= "<p><b>".LE_MODE_DE_LIVRAISON." '".remApos($_POST['name_'.$_SESSION['lang']])."' ".A_ETE_AJOUTE."</b></p>";
        $w="ok";
   }
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print AJOUTE_MODE_DE_LIVRAISON;?></p>

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center"><tr><td>

<?php
 
if(isset($messageLiv) AND $messageLiv!=="") {
	if(isset($w) AND $w=="ok") $couleur="#66CC00"; else $couleur="#FF0000";
	print "<p>";
	print "<table align='center' cellspacing='0' cellpadding='8' class='TABLE' width='700'><tr>";
	print "<td align='center'><font color=red>";
	print $messageLiv;
	print "</font></td>";
	print "</tr></table>";
	print "</p>";
	print "<form action='verzendfirma_toevoegen.php'><p align='center'><input type='submit' class='knop' value='".RETOUR."'></p></form>";
	exit;
}
?>

<form method="POST" action="verzendfirma_toevoegen.php" enctype='multipart/form-data'>
<input type="hidden" name="active" value="1">
<input type="hidden" name="action" value="addShippingMode">

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<?php // levering
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td valign=top width=200><?php print MODE_DE_LIVRAISON;?> *
      <div><img src="ìm/zzz.gif" width="110" height="1"></div>
      </td>
      <td colspan="2">
        <img src="im/be.gif" align="absmiddle">&nbsp;<img src="im/nl.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_3">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/fr.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_1">
        <br><img src='im/zzz.gif' width='1' height='3'><br>
        <img src="im/leeg.gif" align="absmiddle">&nbsp;<img src="im/uk.gif" align="absmiddle">&nbsp;<input type="text" size="60" class="vullen" name="name_2">
      </td>
    </tr>

<?php // activeeer
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr bgcolor="#ccffcc">
      <td><b><font color=red><?php print ACTIVE;?></b></font></td>
      <td colspan="2">
      <?php print OUI;?> <input type="radio" value="1" name="active" checked> <?php print NON;?> <input type="radio" value="0" name="active">
      </td>
    </tr>

<?php // logo
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td valign=top width=200><?php print "Logo ".STRTOLOWER(MODE_DE_LIVRAISON);?></td>
      <td colspan="2">


			&nbsp;&bull;&nbsp;Upload<br>&nbsp;<input type='file' name='uploadShippingImage' class='vullen'  size='40'>

			<div><img src='im/zzz.gif' width='1' height='5'></div>
			&nbsp;ofwel een URL&nbsp;&nbsp;<input type="text" size="30" class='vullen' name="image">&nbsp;(gif, jpg, png)<br>
			&nbsp;Maximale breedte 100 pixels.&nbsp;Voorbeeld http://www.domeinnaam.com/logo.gif
 
      </td>
    </tr>

<?php // landen
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>   
    <tr>
      <td valign=top width=200>
      <?php print "".PAYS." *<br>(".LIRE_NOTE." 1)";?>
      </td>
      <td width="1">
      <?php
      $queryPays = mysql_query("SELECT * FROM countries WHERE countries_shipping!='exclude' ORDER BY countries_name");
      print "<select name='selectPays[]' size='10' multiple>";
      while ($resultPays = mysql_fetch_array($queryPays)) {
         print "<option value='".$resultPays['countries_id']."'>".$resultPays['countries_name']."</option>";
      }
      print "</select>";
      ?>
      </td>
    </tr>

<?php // gewicht
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td valign=top width=200>
      <?php print POIDS_MAXIMUM;?>&nbsp;*
      </td>
      <td colspan="2">
        <input type="text" name="poid" class="vullen" value="" size="10"> gram
        <br>(<?php print ENTREZ_999999999;?>)
      </td>
    </tr>

<?php // leveren
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
    <tr>
      <td width=200 valign=top>
      <?php print DELAI_DE_LIVRAISON;?>&nbsp;*
      </td>
      <td colspan="2">
        <?php print ENTRE;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day1" value="" size="3">&nbsp;&nbsp;<?php print JOURS_ET;?>&nbsp;&nbsp;<input type="text" class="vullen" name="day2" value="" size="3">&nbsp;&nbsp;<?php print JOURS;?>
        <br>(<?php print ENTREZ_100_SI_PAS_DE_DELAI;?>)
      </td>
    </tr>
</table>
<br>


<?php // Nota
if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
?>
	<table align='center' cellspacing='0' cellpadding='8' class='TABLE' width='700'><tr>
    	<tr>
      <td valign=top width=100>
      <?php print LIRE_NOTE;?>
      </td>
      <td colspan="2">
        <table border="0" align="center" cellpadding="1" cellspacing="0">
        <tr><td valign='top'><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;</td><td valign='top'>
           <textarea name="note_3" rows="7" cols="70"></textarea>
        </td></tr>
        <tr><td valign='top'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;</td><td valign='top'>
           <textarea name="note_1" rows="7" cols="70"></textarea>
        </td></tr>
        <tr><td valign='top'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'></td><td valign='top'>
           <textarea name="note_2" rows="7" cols="70"></textarea>

        </td></tr>
        </table>
    </td>
    </tr>
  </table>

  <br>

<table width="100%" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td colspan="2" height="40" align="center"><input type="submit" class="knop" value="<?php print AJOUTE_MODE_DE_LIVRAISONZ;?>" ></td>
</tr>
</table>
</form>

<br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
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
