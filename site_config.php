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
if(isset($_SESSION['recup'])) unset($_SESSION['recup']);




function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src='".$image."' border='0'>";
    }
    else {
       $returnImage = "<img src='".$image."' border='0' width='".$wi."' height='".$he."'>";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}

 
if(isset($_GET['do']) AND $_GET['do']=='sendConfig') {
   $fichier="../configuratie/configuratie.php";
   $fichier2="../configuratie/mysql_connect.php";
   $ligne=file($fichier);
   $ligne2=file($fichier2);
   $displayLigne = implode("\n",$ligne);
   $displayLigne2 = implode("\n",$ligne2);
   $displayLigne = COPIER_CONFIG_MYSQL." configuratie/configuratie.php\n".$displayLigne; 
   $displayLigne2 = COPIER_CONFIG_MYSQL." configuratie/mysql_connect.php\n".$displayLigne2; 
   $hoy = date("d-m-Y H:i:s");
  
      $to20 = $mailOrder;
      $subject20 = "Backup configuratie/configuratie.php - ".$hoy;
      $message20 = $displayLigne;
      $from20 = $mailOrder;
      mail($to20, $subject20, $message20,
      "Return-Path: $from20\r\n"
      ."From: $from20\r\n"
      ."Reply-To: $from20\r\n"
      ."X-Mailer: PHP/" . phpversion());
   
      $to20 = $mailOrder;
      $subject202 = "Backup configuratie/mysql_connect.php - ".$hoy;
      $message202 = $displayLigne2;
      $from20 = $mailOrder;
      mail($to20, $subject202, $message202,
      "Return-Path: $from20\r\n"
      ."From: $from20\r\n"
      ."Reply-To: $from20\r\n"
      ."X-Mailer: PHP/" . phpversion());
      
   $emailMessage = "<div align='center' class='fontrouge'><b>".EMAIL_A_ETE_ENVOYE." ".$mailOrder."</b></div><br>";
}

$c="";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php
if(!isset($_GET['action'])) {
    print "<SCRIPT type='text/javascript' SRC='ColorPicker2.js'></SCRIPT>";
    print "<SCRIPT type='text/javascript'><!--
			var cp = new ColorPicker('window'); // Popup window
			var cp2 = new ColorPicker(); // DIV style
			//--></SCRIPT>";
}
?>

<script type="text/javascript"><!--
function check_form() {
  var error = 0;
  var error_message = "";
    var iso = document.form1.iso.value;
    var nbre_promo1 = document.form1.nbre_promo1.value;
    var nbre_nouv1 = document.form1.nbre_nouv1.value;
    var nbre_jour_nouv1 = document.form1.nbre_jour_nouv1.value;
    var taxe1 = document.form1.taxe1.value;
    var livraisonComprise1 = document.form1.livraisonComprise1.value;
    var nbre_ligne1 = document.form1.nbre_ligne1.value;
    var haut_im1 = document.form1.haut_im1.value;
    var NbreProduitAffiche1 = document.form1.NbreProduitAffiche1.value;
    var NbreProduitAfficheCatalog1 = document.form1.NbreProduitAfficheCatalog1.value;
    var imageSizeCatalog1 = document.form1.imageSizeCatalog1.value;
    var nbre_col1 = document.form1.nbre_col1.value;
    var nbre_col_catalog1 = document.form1.nbre_col_catalog1.value;
    var ImageSizeDesc1 = document.form1.ImageSizeDesc1.value;
    var SecImageSizeDesc1 = document.form1.SecImageSizeDesc1.value;
    var SecImageWidthDesc1 = document.form1.SecImageWidthDesc1.value;
    var ImageSizeCart1 = document.form1.ImageSizeCart1.value;
    var imageSizeCat1 = document.form1.imageSizeCat1.value;
    var remiseOrderMax1 = document.form1.remiseOrderMax1.value;
    var remise1 = document.form1.remise1.value;
    var moteurLigneNum1 = document.form1.moteurLigneNum1.value;
    var seuilStock1 = document.form1.seuilStock1.value;
    var seuilContre1 = document.form1.seuilContre1.value;
    var larg_rub1 = document.form1.larg_rub1.value;
    var hautImageMaxPromo1 = document.form1.hautImageMaxPromo1.value;
    var hautImageMaxNews1 = document.form1.hautImageMaxNews1.value;
    var largTableCatalog1 = document.form1.largTableCatalog1.value;
    var largTableCategories1 = document.form1.largTableCategories1.value;
    var nbreCarCat1 = document.form1.nbreCarCat1.value;
    var nbreCarSubCat1 = document.form1.nbreCarSubCat1.value;
    var maxCarQuick1 = document.form1.maxCarQuick1.value;
    var maxCarInfo1 = document.form1.maxCarInfo1.value;
    var maxCarDesc1 = document.form1.maxCarDesc1.value;
    var cellpad1 = document.form1.cellpad1.value;
    var ImageSizeDescRelated1 = document.form1.ImageSizeDescRelated1.value;
    var remisePastOrder1 = document.form1.remisePastOrder1.value;
    var saveCart1 = document.form1.saveCart1.value;
    var affiliateCom1 = document.form1.affiliateCom1.value;
    var affiliateTop1 = document.form1.affiliateTop1.value;
    var minimumOrder1 = document.form1.minimumOrder1.value;
    var nbre_col_sm1 = document.form1.nbre_col_sm1.value;
    var storeWidth1 = document.form1.storeWidth1.value;
    var cellTop1 = document.form1.cellTop1.value;
    var seuilPromo1 = document.form1.seuilPromo1.value;
    var nbreMenuTab1 = document.form1.nbreMenuTab1.value;
    var devisValid1 = document.form1.devisValid1.value;    
    var seuilGc1 = document.form1.seuilGc1.value;    
    var scrollHeight1 = document.form1.scrollHeight1.value;
    var scrollWidth1 = document.form1.scrollWidth1.value;
    var scrollDelay1 = document.form1.scrollDelay1.value;
    var nic1 = document.form1.nic1.value;
    var im_size_sm1 = document.form1.im_size_sm1.value;
    var hauteurTitreModule1 = document.form1.hauteurTitreModule1.value;
    var numNumList1 = document.form1.numNumList1.value;
    var maxCarActu1 = document.form1.maxCarActu1.value;
    var nbreLigneActu1 = document.form1.nbreLigneActu1.value;
    var seuilCadeau1 = document.form1.seuilCadeau1.value;
    var imZoomMax1 = document.form1.imZoomMax1.value;
    var tauxDevise21 = document.form1.tauxDevise21.value;
    
if(document.form1.elements['nic1'].type != "hidden") {
    if(nic1 == '') {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print VOTRE_NIC;?>\n";
      error = 1;
    }
  }
  if(document.form1.elements['iso'].type != "hidden") {
    if(document.form1.iso.value == 'no') {
      error_message = error_message + "<?php print SEL;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['scrollHeight1'].type != "hidden") {
    if(scrollHeight1 == '' || isNaN(scrollHeight1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print H;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['scrollWidth1'].type != "hidden") {
    if(scrollWidth1 == '' || isNaN(scrollWidth1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print L;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['scrollDelay1'].type != "hidden") {
    if(scrollDelay1 == '' || isNaN(scrollDelay1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print PAUSE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_promo1'].type != "hidden") {
    if(nbre_promo1 == '' || isNaN(nbre_promo1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A24;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_nouv1'].type != "hidden") {
    if(nbre_nouv1 == '' || isNaN(nbre_nouv1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A34;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_jour_nouv1'].type != "hidden") {
    if(nbre_jour_nouv1 == '' || isNaN(nbre_jour_nouv1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A151;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['taxe1'].type != "hidden") {
    if(taxe1 == '' || isNaN(taxe1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A38;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['livraisonComprise1'].type != "hidden") {
    if(livraisonComprise1 == '' || isNaN(livraisonComprise1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A45;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_ligne1'].type != "hidden") {
    if(nbre_ligne1 == '' || isNaN(nbre_ligne1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A50;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['haut_im1'].type != "hidden") {
    if(haut_im1 == '' || isNaN(haut_im1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A51;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['NbreProduitAffiche1'].type != "hidden") {
    if(NbreProduitAffiche1 == '' || isNaN(NbreProduitAffiche1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A58;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['NbreProduitAfficheCatalog1'].type != "hidden") {
    if(NbreProduitAfficheCatalog1 == '' || isNaN(NbreProduitAfficheCatalog1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A58;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['imageSizeCatalog1'].type != "hidden") {
    if(imageSizeCatalog1 == '' || isNaN(imageSizeCatalog1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_col1'].type != "hidden") {
    if(nbre_col1 == '' || isNaN(nbre_col1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A59;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_col_catalog1'].type != "hidden") {
    if(nbre_col_catalog1 == '' || isNaN(nbre_col_catalog1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A59;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['ImageSizeDesc1'].type != "hidden") {
    if(ImageSizeDesc1 == '' || isNaN(ImageSizeDesc1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['SecImageSizeDesc1'].type != "hidden") {
    if(SecImageSizeDesc1 == '' || isNaN(SecImageSizeDesc1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60A;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['SecImageWidthDesc1'].type != "hidden") {
    if(SecImageWidthDesc1 == '' || isNaN(SecImageWidthDesc1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60B;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['ImageSizeCart1'].type != "hidden") {
    if(ImageSizeCart1 == '' || isNaN(ImageSizeCart1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A107;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['imageSizeCat1'].type != "hidden") {
    if(imageSizeCat1 == '' || isNaN(imageSizeCat1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['remiseOrderMax1'].type != "hidden") {
    if(remiseOrderMax1 == '' || isNaN(remiseOrderMax1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A77;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['remise1'].type != "hidden") {
    if(remise1 == '' || isNaN(remise1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A78;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['moteurLigneNum1'].type != "hidden") {
    if(moteurLigneNum1 == '' || isNaN(moteurLigneNum1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A50U;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['seuilStock1'].type != "hidden") {
    if(seuilStock1 == '' || isNaN(seuilStock1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A86;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['seuilContre1'].type != "hidden") {
    if(seuilContre1 == '' || isNaN(seuilContre1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A113;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['larg_rub1'].type != "hidden") {
    if(larg_rub1 == '' || isNaN(larg_rub1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A131;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['hautImageMaxPromo1'].type != "hidden") {
    if(hautImageMaxPromo1 == '' || isNaN(hautImageMaxPromo1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['hautImageMaxNews1'].type != "hidden") {
    if(hautImageMaxNews1 == '' || isNaN(hautImageMaxNews1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A60;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['largTableCatalog1'].type != "hidden") {
    if(largTableCatalog1 == '' || isNaN(largTableCatalog1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A150;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['largTableCategories1'].type != "hidden") {
    if(largTableCategories1 == '' || isNaN(largTableCategories1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A150;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbreCarCat1'].type != "hidden") {
    if(nbreCarCat1 == '' || isNaN(nbreCarCat1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A202;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbreCarSubCat1'].type != "hidden") {
    if(nbreCarSubCat1 == '' || isNaN(nbreCarSubCat1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A203;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['maxCarQuick1'].type != "hidden") {
    if(maxCarQuick1 == '' || isNaN(maxCarQuick1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A205;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['maxCarInfo1'].type != "hidden") {
    if(maxCarInfo1 == '' || isNaN(maxCarInfo1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A205;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['maxCarDesc1'].type != "hidden") {
    if(maxCarDesc1 == '' || isNaN(maxCarDesc1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print MAX_CAR_LIST;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['cellpad1'].type != "hidden") {
    if(cellpad1 == '' || isNaN(cellpad1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A502;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['cellTop1'].type != "hidden") {
    if(cellTop1 == '' || isNaN(cellTop1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print HAUTEUR;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['ImageSizeDescRelated1'].type != "hidden") {
    if(ImageSizeDescRelated1 == '' || isNaN(ImageSizeDescRelated1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A110B;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['remisePastOrder1'].type != "hidden") {
    if(remisePastOrder1 == '' || isNaN(remisePastOrder1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A512;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['saveCart1'].type != "hidden") {
    if(saveCart1 == '' || isNaN(saveCart1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A1061;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['affiliateCom1'].type != "hidden") {
    if(affiliateCom1 == '' || isNaN(affiliateCom1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A1501;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['affiliateTop1'].type != "hidden") {
    if(affiliateTop1 == '' || isNaN(affiliateTop1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A1502;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['minimumOrder1'].type != "hidden") {
    if(minimumOrder1 == '' || isNaN(minimumOrder1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A1505;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbre_col_sm1'].type != "hidden") {
    if(nbre_col_sm1 == '' || isNaN(nbre_col_sm1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A59;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['storeWidth1'].type != "hidden") {
    if(storeWidth1 == '' || isNaN(storeWidth1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A501;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['seuilPromo1'].type != "hidden") {
    if(seuilPromo1 == '' || isNaN(seuilPromo1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print SEUIL_ALERTE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbreMenuTab1'].type != "hidden") {
    if(nbreMenuTab1 == '' || isNaN(nbreMenuTab1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print NUM_TAB;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['devisValid1'].type != "hidden") {
    if(devisValid1 == '' || isNaN(devisValid1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print DEVIS."-".VALID;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['seuilGc1'].type != "hidden") {
    if(seuilGc1 == '' || isNaN(seuilGc1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print CHEQUE_CADEAU." - ".MONTANT_MINI;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['im_size_sm1'].type != "hidden") {
    if(im_size_sm1 == '' || isNaN(im_size_sm1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print A59SIZE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['hauteurTitreModule1'].type != "hidden") {
    if(hauteurTitreModule1 == '' || isNaN(hauteurTitreModule1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print HAUTMOD;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['numNumList1'].type != "hidden") {
    if(numNumList1 == '' || isNaN(numNumList1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print NUMPAGE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['maxCarActu1'].type != "hidden") {
    if(maxCarActu1 == '' || isNaN(maxCarActu1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print CARMAX_ACTU2;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['nbreLigneActu1'].type != "hidden") {
    if(nbreLigneActu1 == '' || isNaN(nbreLigneActu1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print NBRE_ACTU_PAGE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['seuilCadeau1'].type != "hidden") {
    if(seuilCadeau1 == '' || isNaN(seuilCadeau1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print SEUIL_EXTRA;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['imZoomMax1'].type != "hidden") {
    if(imZoomMax1 == '' || isNaN(imZoomMax1)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print IM_ZOOM_SIZE;?>\n";
      error = 1;
    }
  }
if(document.form1.elements['tauxDevise21'].type != "hidden") {
    if(imZoomMax1 == '' || isNaN(tauxDevise21)== true) {
      error_message = error_message + "<?php print CHAMPS_VIDE;?> <?php print NOT_NUMBER;?>  <?php print TAUX_DEVISE2;?>\n";
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
$site_config=1;
if($activerTiny=="oui") include("tiny-inc.php");
?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<a href="#zulTop" name="zultop"></a>
<p align="center" class="largeBold"><?php print A1;?></p>



<div>
<?php 
if(!isset($_GET['action'])) {
    if($adminMenu=="deroulant" OR $adminMenu=="both") include('menu_top_hoofd.php');
}
?>
</div>

<!-- <form method="POST" action="site_config.php?action=write" name="form1" onsubmit="return check_form()"> -->
<form method='POST' action='site_config.php?action=write' name='form1' enctype='multipart/form-data' onSubmit="return check_form()">

<?php
if(!isset($_GET['action'])) {
print "<div align='center'><a href='site_config.php#".CETTE_PAGE."' style='text-decoration:none'><span style='color:#999999'>[Menu selectie]</span></a></div>";
if($adminMenu=="fixe" OR $adminMenu=="both") {
?>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="20" class="TABLE"><tr><td>
<table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">

<tr>
<td><div style="font-size:13px"><b>Configuratie</b></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr b/gcolor="#efefef">
<td width="33%">&bull;&nbsp;<a href="#<?php print A2;?>" name="a2z"><?php print A2;?></a></td>
<td width="33%">&bull;&nbsp;<a href="#<?php print A5;?>" name="a5z"><?php print A5;?></a></td>
<td width="33%">&bull;&nbsp;<a href="#<?php print A7;?>" name="a7z"><?php print A7;?></a></td>
</tr>
<tr>
<td width="33%">&bull;&nbsp;<a href="#<?php print A10;?>" name="a10z"><?php print A10;?></a></td>
<td width="33%">&bull;&nbsp;<a href="#<?php print A16;?>" name="a16z"><?php print A16;?></a></td>
<td width="33%">&bull;&nbsp;<a href="#Email" name="aEmailz">E-mail</a></td>
</tr> 
<tr b/gcolor="#efefef">
<td width="33%">&bull;&nbsp;<a href="#<?php print A500;?>" name="a500z"><?php print A500;?></a></td>
<td><img src="im/zzz.gif" border="0" height="10"></td>
<td><img src="im/zzz.gif" border="0" height="10"></td>
</tr>

<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr b/gcolor="#efefef">
<td>&bull;&nbsp;<a href="#<?php print A37;?>" name="a37z"><?php print A37;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A44;?>" name="a44z"><?php print A44;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print OPTIMISATION_IMAGES;?>" name="a449z"><?php print OPTIMISATION_IMAGES;?></a></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A66;?>" name="a66z"><?php print A66;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A69;?>" name="a69z"><?php print A69;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A73;?>" name="a73z"><?php print A73;?></a></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A76;?>" name="a76z"><?php print A76;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A510A;?>" name="a76zz"><?php print A510A;?></a></td>
<td>&bull;&nbsp;<A HREF="#<?php print A82;?>" NAME="a82z"><?php print A82;?></A></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A95;?>" name="a95z"><?php print A95;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A1505;?>" name="a99z"><?php print A1505;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print A1520;?>" name="a100z"><?php print A1520;?></a></td>
</tr>
<tr b/gcolor="#efefef">
<td width="33%">&bull;&nbsp;<a href="#<?php print CHEQUE_CADEAU;?>" name="a999zc"><?php print CHEQUE_CADEAU;?></a></td>
<td>&bull;&nbsp;<A HREF="#Stock" NAME="aStockz">Voorraad</A></td>
<td>&bull;&nbsp;<a href="#<?php print RESERVATION_ARTICLE;?>" name="a999zz"><?php print RESERVATION_ARTICLE;?></a></td>
</tr> 
<tr b/gcolor="#efefef">
<td>&bull;&nbsp;<a href="#<?php print FERMETURE_BOUTIQUE;?>" name="a999Closed"><?php print FERMETURE_BOUTIQUE;?></a></td>
<td>&bull;&nbsp;<a href="#<?php print ACHAT_EXPRESS;?>" name="a999express"><?php print ACHAT_EXPRESS;?></a></td>
<td>&bull;&nbsp;<a href="#RSS" name="a999RSS">RSS</a></td>
</tr>
<tr b/gcolor="#efefef">

<td><img src="im/zzz.gif" border="0" height="10"></td>
<td><img src="im/zzz.gif" border="0" height="10"></td>
</tr>


<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><div style="font-size:13px"><b>Modules</b></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A23;?>" name="a23z"><FONT COLOR="#CC0000"><?php print A23;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A29A;?>" name="a29az"><FONT COLOR="#CC0000"><?php print A29A;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A28;?>" name="a28z"><FONT COLOR="#CC0000"><?php print A28;?></FONT></a></td>
</tr> 
<tr b/gcolor="#efefef">
<td>&bull;&nbsp;<a href="#<?php print A29;?>" name="a29z"><FONT COLOR="#CC0000"><?php print A29;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A30;?>" name="a30z"><FONT COLOR="#CC0000"><?php print A30;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A31;?>" name="a31z"><FONT COLOR="#CC0000"><?php print A31;?></FONT></a></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A32;?>" name="a32z"><FONT COLOR="#CC0000"><?php print A32;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A33;?>" name="a33z"><FONT COLOR="#CC0000"><?php print A33;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A201;?>" name="a201z"><FONT COLOR="#CC0000"><?php print A201;?></FONT></a></td>
</tr>
<tr b/gcolor="#efefef">
<td>&bull;&nbsp;<A HREF="#<?php print A84;?>" NAME="a84z"><FONT COLOR="#CC0000"><?php print A84;?></FONT></A></td>
<td>&bull;&nbsp;<A HREF="#<?php print A81;?>" NAME="a81z"><FONT COLOR="#CC0000"><?php print A81;?></FONT></A></td>
<td>&bull;&nbsp;<A HREF="#<?php print A33A;?>" NAME="a201zc"><FONT COLOR="#CC0000"><?php print A33A;?></FONT></A></td>
</tr>
<tr>
<td>&bull;&nbsp;<A HREF="#<?php print A1500;?>" NAME="a1500z"><FONT COLOR="#CC0000"><?php print A1500;?></FONT></A></td>
<td>&bull;&nbsp;<A HREF="#<?php print A84A;?>" NAME="a84Az"><FONT COLOR="#CC0000"><?php print A84A;?></FONT></A></td>
<td>&bull;&nbsp;<A HREF="#<?php print MODULE_DEJA_VU;?>" NAME="a130zss"><FONT COLOR="#CC0000"><?php print MODULE_DEJA_VU;?></FONT></A></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print DEVIS;?>" name="a1999z"><FONT COLOR="#CC0000"><?php print DEVIS;?></font></a></td>
<td>&bull;&nbsp;<A HREF="#<?php print MODULE_ADDTHIS;?>" NAME="a135zss"><FONT COLOR="#CC0000"><?php print MODULE_ADDTHIS;?></FONT></A></td>
<td>&bull;&nbsp;<A HREF="#<?php print A130;?>" NAME="a130z"><FONT COLOR="#CC0000"><?php print A130;?></FONT></A></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td><div style="font-size:13px"><b>Paginas</b></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td>&bull;&nbsp;<a href="#<?php print A57;?>" name="a57z"><FONT COLOR="#0000FF"><?php print A57;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A49;?>" name="a49z"><FONT COLOR="#0000FF"><?php print A49;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A106;?>" name="a106z"><FONT COLOR="#0000FF"><?php print A106;?></FONT></a></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print A64;?>" name="a64z"><FONT COLOR="#0000FF"><?php print A64;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A65;?>" name="a65z"><FONT COLOR="#0000FF"><?php print A65;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A1060;?>" name="a1060A"><FONT COLOR="#0000FF"><?php print A1060;?></FONT></a></td>
</tr>
<tr>
<td>&bull;&nbsp;<a href="#<?php print PAGEINDEX;?>" name="a65z1"><FONT COLOR="#0000FF"><?php print PAGEINDEX;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print CETTE_PAGE;?>" name="a65z1xx"><FONT COLOR="#0000FF"><?php print CETTE_PAGE;?></FONT></a></td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr>
<td><div style="font-size:13px"><b>Betalingen</b></div></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>

<tr bgcolor="#FFFFFF">
<td>&bull;&nbsp;<a href="#<?php print A115A;?>" name="a115z"><FONT COLOR="#CC0000"><?php print A115A;?></FONT></a></td>
<td>&bull;&nbsp;<a href="#Western Union" name="aWesternz"><FONT COLOR="#CC0000">Western Union</FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A111;?>" name="a111z"><FONT COLOR="#CC0000"><?php print A111;?></FONT></a></td>
</tr>
<tr bgcolor="#FFFFFF">
<td>&bull;&nbsp;<a href="#<?php print A87;?> PAYPAL" name="aPaypalz"><FONT COLOR="#CC0000">Paypal</FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A87;?> 2CHECKOUT" name="aCheckz"><FONT COLOR="#CC0000">2Checkout</FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A87;?> OGONE" name="aOgonez"><FONT COLOR="#CC0000">Ogone</FONT></a></td>

</tr>
<tr bgcolor="#FFFFFF">
<td>&bull;&nbsp;<a href="#<?php print A87;?> MONEYBOOKERS" name="aMbz"><FONT COLOR="#CC0000">Money bookers</FONT></a></td>
<td>&bull;&nbsp;<a href="#<?php print A87;?> PAYNL" name="paynederland"><FONT COLOR="#CC0000">Pay Nederland</FONT></a></td>


</tr>
<tr bgcolor="#FFFFFF">
<td>&bull;&nbsp;<a href="#<?php print DESACTIVE_PAYMENTS;?>" name="desactivez"><FONT COLOR="#CC0000"><?php print DESACTIVE_PAYMENTS;?></FONT></a></td>
<td colspan="3"><img src="im/zzz.gif" width="1" height="5"></td>
</tr>
</table>
</td></tr></table>
<?php 
}

?>
<p align='center'>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="7" class="TABLE"><tr>
<td align='center'>
<?php
if($mailOrder!=="") {
   print "<a href='site_config.php?do=sendConfig'><b>".SAUVEGARDER_LA_CONFIG."</b></a>";
}
else {
   print "<b>".SAUVEGARDER_LA_CONFIG."</b>";
}
?>
<br>
<?php print EMAIL_ENVOYE_A." <a href='#".$mailOrder."' name='zul'> ".$mailOrder."</a>";?>
</td>
</tr>
</table>
</p>

<?php if(isset($emailMessage)) print $emailMessage;?>


<?php // Versie ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><?php print VERSION;?></b>
    </td>
  </tr>

  <tr>
    <td valign="top" width="200"><?php print "<b>B2C</b> ".VB2C."";?></td>
    <?php
        if($vb2c == "oui") {
            $checkedyes2c = "checked";
            $checkedno2c = "";
        } else {
          $checkedyes2c = "";
          $checkedno2c = "checked";
        }
?>
    <td valign="top" width="200"> <?php print A26;?>
      <input type="radio" value="oui" name="vb2c1" <?php print $checkedyes2c;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="vb2c1" <?php print $checkedno2c;?> >
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit">
    </td>
    </td>
  </tr>
  
  <tr bgcolor='#FFFFFF'><td align='left' colspan='2' class='fontrouge'>** <b><?php print OU;?></b> **</td></tr>

  <tr>
    <td width="200" valign="top">
    <?php
    print "<b>B2B</b> ".VB2B."";
    ?>
    </td>
    <?php
        if($vb2b == "oui") {
            $checkedyes2b = "checked";
            $checkedno2b = "";
        } else {
          $checkedyes2b = "";
          $checkedno2b = "checked";
        }
?>
    <td valign=top"> <?php print A26;?>
      <input type="radio" value="oui" name="vb2b1" <?php print $checkedyes2b;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="vb2b1" <?php print $checkedno2b;?> >
    </td>
  </tr>
  
  <tr>
   <td  width="200"><?php print AFFICHER_PRIX;?></td>
    <?php
        if($displayPriceInShop == "oui") {
            $checkedyes2p = "checked";
            $checkedno2p = "";
        } else {
          $checkedyes2p = "";
          $checkedno2p = "checked";
        }
?>
    <td valign="top"> <?php print A26;?>
      <input type="radio" value="oui" name="displayPriceInShop1" <?php print $checkedyes2p;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayPriceInShop1" <?php print $checkedno2p;?> >
    </td>
  </tr>  
</table>
<br>



<?php // logo ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a2z" name="<?php print A2;?>"><?php print A2;?></a></b>
    </td>
  </tr>
    <tr>
     <td valign="top" width="200"><?php print CHEMIN_VERS_LOGO;?> <br><?php print A4;?></td>
     <?php
         if($logo!=="noPath") {
            $logoPathZ = explode("/",$logo);
            $logoPath = str_replace($logoPathZ[0]."/","", $logo);
         }
         else {
            $logoPath = $logo;
         }
     ?>
     <td>


	&nbsp;&bull;&nbsp;<?php print UPLOAD;?> logo<br>&nbsp;<input type='file' name='uploadLogo' class="vullen" size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	&nbsp<input type="text" class="vullen" size="50" value="<?php print $logoPath;?>" name="logo2">&nbsp;(gif, jpg, png, swf)<br>&nbsp;<?php print L.' maximaal';?> 160 px
	</div>
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit"></td>
    </tr>
  <tr>
    <td width="200" valign="top"><?php print A3;?></td>
    <td><?php if($logo=="noPath") print "<b>".PAS_DE_LOGO."</b>"; else print detectIm("../".$logo,0,0);?></td>
  </tr>

<!--
<?php // banner ?>
  <tr>
    <td align="center" colspan="2" height="30">
    --- <span style="color:#FFFFFF; background-color:#000000; padding:3px;"><b>OFWEL</b></span> ---
    </td>
  </tr>
    <tr>
     <td valign="top" width="200" ><?php print CHEMIN_BANDEAU;?> - <?php print LIRE_NOTE9;?></td>
     <?php
         if($logo2!=="noPath") {
            $logoPathZ2 = explode("/",$logo2);
            $logoPath2 = str_replace($logoPathZ2[0]."/","", $logo2);
         }
         else {
            $logoPath2 = $logo2;
         }
     ?>
     <td>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?> <?php print BANDEAU;?><br>&nbsp;<input type='file' name='uploadBandeau' class='vullen' size='40'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	&nbsp;<input type="text" size="50" class="vullen" value="<?php print $logoPath2;?>" name="logo22">&nbsp;(gif, jpg, png, swf)
 
    </td>
    </tr>
  <tr>
    <td width="200" valign="top"><?php print URL_BANDEAU;?></td>
    <td>
    http://www.<input type="text" class="vullen" size="40" value="<?php print $urlLogo2;?>" name="urlLogo22">
    <br>Voorbeeld: <?php print $domaineFull;?>.<br><?php print LAISSER_VIDE_SI_PAS_DE_BANDEAU;?>
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print iMAGE_BANDEAU;?></td>
    <td>
    <?php
    if($logo2=="noPath") print "<b>".NO_BANDEAU."</b>"; else print "<a href='../".$logo2."' target='_blank'>".VOIR_BANDEAU."</a>";
    ?>
    </td>
  </tr>
<?php // header ?>

-->

  <tr>
    <td align="center" colspan="2" height="30" style="background-color:#FFFFFF">&nbsp;</td>
  </tr>
    <tr>
     <td valign="top" width="200"><?php print CHEMIN_VERS_HEADER_IMAGE;?><br><?php print A4;?></td>
     <td>
     <?php $imageFondHeader = str_replace("im/background/","",$backgroundImageHeader);?>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?> <?php print IMNAGE_DE_FOND;?><br>&nbsp;<input type='file' name='uploadHeader' class='vullen' size='40'>
 
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	&nbsp;<input type="text" size="50" class="vullen" value="<?php print $imageFondHeader;?>" name="backgroundImageHeader1">&nbsp;(gif, jpg, png)
	</div>
    </td>
    </tr>
  <tr>
    <td width="200"><?php print IMNAGE_DE_FOND;?></td>
    <td>
    <?php
    if($backgroundImageHeader=="noPath") print "<b>".NO_HEADER_IMAGE."</b>"; else print "<a href='../".$backgroundImageHeader."' target='_blank'>".VOIR_IMAGE."</a>";
    ?>
    </td>
  </tr>
</table>
<br>



<?php // domeinnaam ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a5z" name="<?php print A5;?>"><?php print A5;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A5;?></td>
    <td>
      <b>http://</b><input type="text" name="www1" class="vullen" size="5" value="<?php print $www;?>">.<input type="text" name="domaine1" size="50%" class="vullen" value="<?php print $domaine;?>">
      <p><?php print A6;?></p>
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
      
    <tr>
	<td width="200" valign="top"><?php print A212;?></td>
    <td>
      <input type="text" class="vullen" name="folder1" size="50%" value="<?php print $folder;?>"><br><?php print A213;?>
    </td>
  </tr>
  
      <tr>
	<td width="200"><?php print A214;?></td>
    <td>
     <a href="http://<?php print $www2.$domaineFull;?>" target="_blank"><span color="fontrouge">http://<?php print $www2.$domaineFull;?></span></a>
    </td>
  </tr>
</table>
<br>


<?php // database toegang ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a7z" name="<?php print A7;?>"><?php print A7;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200">Host</td>
    <td><?php print $bddHost;?>
      <input type="hidden" name="bddHost1" size="30" value="<?php print $bddHost;?>">
   
    </td>
    <td rowspan="5" valign="top"  class="boxtitle3">&nbsp;</td>
  </tr>
  <tr>
    <td width="200">User</td>
    <td><?php print $bddUser;?>
      <input type="hidden" name="bddUser1" size="30" value="<?php print $bddUser;?>">

    </td>
  </tr>
  <tr>
    <td width="200"><?php print A8;?></td>
    <td><?php print $bddPass;?>
      <input type="hidden" name="bddPass1" size="30" value="<?php print $bddPass;?>">
 
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A9;?></td>
    <td><?php print $bddName;?>
      <input type="hidden" name="bddName1" size="30" value="<?php print $bddName;?>">
  
    </td>
  </tr>
</table>
<br>


<?php // Meta tags ?>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a10z" name="<?php print A10;?>"><?php print A10;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A11;?></td>
    <td>
      <input type="text" class="vullen" name="keywords1" size="60" value="<?php print $keywords;?>">
<br><?php print A12;?>
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A13;?></td>
    <td>
      <input type="text" class="vullen" name="description1" size="60" value="<?php print $description;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A15;?></td>
    <td>
      <input type="text" class="vullen" name="auteur1" size="30" value="<?php print $auteur;?>">
    </td>
  </tr>
</table>
<br>

<?php // bedrijfs gegevens ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a16z" name="<?php print A16;?>"><?php print A16;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A17;?></td>
    <td>
      <input type="text" name="store_name1" class="vullen" size="50%" value="<?php print $store_name;?>">
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print ENTREPRISE;?></td>
    <td>
      <input type="text" class="vullen" name="store_company1" size="50%" value="<?php print $store_company;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A18;?></td>
    <td>
      <input type="text" class="vullen" name="tel1" size="25" value="<?php print $tel;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print FAX;?></td>
    <td>
      <input type="text" class="vullen" name="fax1" size="25" value="<?php print $fax;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A19;?></td>
    <td>
      <input type="text" class="vullen" name="address_street1" size="50%" value="<?php print $address_street;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A20;?></td>
    <td>
      <input type="text" class="vullen" name="address_cp1" size="10" value="<?php print $address_cp;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A21;?></td>
    <td>
      <input type="text" class="vullen" name="address_city1" size="50%" value="<?php print $address_city;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A22;?></td>
    <td>

<?php
$requestCountry2 = mysql_query("SELECT iso, countries_name
                              FROM countries
                              WHERE country_state = 'country'
                              ORDER BY countries_name");
                      
print "<select name='address_country1'>";
while ($countries2 = mysql_fetch_array($requestCountry2)) {
       if($countries2['countries_name'] == $address_country) $ab = "selected"; else $ab="";
       print "<option value='".$countries2['countries_name']."' $ab>".$countries2['countries_name']."</option>";
       }
print "</select>";
?>
    </td
  ></tr>

  <tr>
    <td width="200" valign="top"><?php print AUTRE;?></td>
    <td>
      <textarea cols="50" rows="4" class="vullen" name="address_autre1" style="width:50%"><?php print $address_autre;?></textarea>
    </td>
  </tr>


<?php
 
if(!ini_get("safe_mode")) {
if(getenv('TZ')) {
?>
    <tr>
    <td width="200"><?php print A22N;?></td>
    <td>
<?php
if(!isset($timeZone)) {$timeZone = "Europe/Paris";}

print "<select name='timeZone1'>";
  ?>
    </td>
  </tr>
<?php
}
}
 
?>

<tr>
        <td><?php print A22;?> ISO</td>
        <td>
<?php

$requestCountry = mysql_query("SELECT iso, countries_name FROM countries WHERE country_state = 'country' ORDER BY countries_name");
print "<select name='iso'>
      <option value='no'>----</option>";
while ($countries = mysql_fetch_array($requestCountry)) {
       if($countries['iso'] == $iso) $a = "selected"; else $a="";
       print "<option value='".$countries['iso']."' $a>".$countries['iso']."</option>";
       }
print "</select>";
?>
              
&nbsp;&nbsp;<a href="iso.php" target="_blank"><?php print A22;?> ISO</a>
    </td>
</tr>

  <tr>
    <td width="200" valign="top"><?php print FACT_NUM;?></td>
    <td>
      <input type="text" class="vullen" name="factNum1" size="8" value="<?php print $factNum;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print PREFIXE;?></td>
    <td>
      <input type="text" class="vullen" name="factPrefixe1" size="8" value="<?php print str_replace("||","",$factPrefixe);?>">
    </td>
  </tr>

  <tr>
    <td colspan="2" valign="top"><?php print AUTO_CONFIRM;?></td>
</tr>
    <?php
        if($autoConfirm == "oui") {
            $checkyesbgjer = "checked";
            $checknobgjer = "";
        } else {
          $checkyesbgjer = "";
          $checknobgjer = "checked";
        }
    ?>
<tr><td></td>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="autoConfirm1" <?php print $checkyesbgjer;?>>
    <?php print A27;?> <input type="radio" value="non" name="autoConfirm1" <?php print $checknobgjer;?>>
    </td>
  </tr>


  <tr>
    <td width="200"  valign="top"><?php print MENTION_LEGALE;?></td>
    <td>
      <input type="text" class="vullen" name="mlFact1" size="60" value="<?php print $mlFact;?>">

    </td>
  </tr>
</table>
<br>




<?php // E-mail?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aEmailz" name="Email">E-mail</a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><img src='im/zzz.gif' height='20' width='1' align='absmiddle'>Webmaster</td>
    <td>
      <input type="text" class="vullen" name="mailWebmaster1" size="50%" value="<?php print $mailWebmaster;?>">
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
  <tr>
    <td width="200"><img src='im/zzz.gif' height='20' width='1' align='absmiddle'>Info</td>
    <td>
      <input type="text" class="vullen" name="mailInfo1" size="50%" value="<?php print $mailInfo;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><img src='im/zzz.gif' height='20' width='1' align='absmiddle'>Persoonlijk</td>
    <td>
      <input type="text" class="vullen" name="mailPerso1" size="50%" value="<?php print $mailPerso;?>">
    </td>
  </tr>
  <tr>
    <td valign='middle'>
      <a href="#zul" name="<?php print $mailOrder;?>"></a>
      <img src='im/zzz.gif' height='20' width='1' align='absmiddle'>Bestellingen</td>
    <td>
      <input type="text" class="vullen" name="mailOrder1" size="50%" value="<?php print $mailOrder;?>">
    </td>
  </tr>
</table>
<br>


<?php // layout ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a500z" name="<?php print A500;?>"><?php print A500;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A501;?></td>
    <td>
    <?php
        if(preg_match("/%/", $storeWidth)) {
            $toyo= explode("%", $storeWidth);
            $valeurNum = $toyo[0];
            $valuePourcent = "selected";
            $valuePixel = "";
        }
        else {
            $valeurNum = $storeWidth;
            $valuePourcent = "";
            $valuePixel = "selected";
        }
    ?>
      <input type="text" class="vullen" name="storeWidth1" size="5" value="<?php print $valeurNum;?>">&nbsp;
      <select name="boutiqueWidth">
        <option value="" <?php print $valuePixel;?>>pixels</option>
        <option value="%" <?php print $valuePourcent;?>>%</option>
        </select>
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
    <tr>
    <td width="200"><?php print A502;?></td>
    <td>
      <input type="text" class="vullen" name="cellpad1" size="5" value="<?php print $cellpad;?>">
    </td>
  </tr>
  
  
  <tr>
    <td width="200"><?php print "<a href='im/copyHeaderTop.jpg' target='_blank'>".HAUTEUR."</a>";?></td>
    <td>
      <input type="text" class="vullen" name="cellTop1" size="5" value="<?php print $cellTop;?>">
    </td>
  </tr>

  <tr>
    <td width="200"><?php print DISPLAY_HEADER1;?></td>
    <?php
        if($header1Display == "oui") {
            $checkyesbgjez = "checked";
            $checknobgjez = "";
        } else {
          $checkyesbgjez = "";
          $checknobgjez = "checked";
        }
    ?>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="header1Display1" <?php print $checkyesbgjez;?>>
    <?php print A27;?> <input type="radio" value="non" name="header1Display1" <?php print $checknobgjez;?>>
    </td>
  </tr>


  <tr>
    <td width="200"><?php print "<a href='im/copyHeader2.jpg' target='_blank'>".DISPLAY_HEADER2."</a>";?></td>
    <?php
        if($header2Display == "oui") {
            $checkyesbgje = "checked";
            $checknobgje = "";
        } else {
          $checkyesbgje = "";
          $checknobgje = "checked";
        }
    ?>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="header2Display1" <?php print $checkyesbgje;?>>
    <?php print A27;?> <input type="radio" value="non" name="header2Display1" <?php print $checknobgje;?>>
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print HEADER2_BACKGROUND_IMAGE;?></td>
    <td>
    <?php $imageFond = str_replace("im/banner/","",$header2Image);?>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?> <?php print IMNAGE_DE_FOND;?><br>&nbsp;<input type='file' class='vullen' size='40'>

	<div><img src='im/zzz.gif' width='1' height='5'></div>
      &nbsp;<input type="text" class="vullen" size="50" value="<?php print $imageFond;?>" name="header2Image1" >&nbsp;(gif, jpg, png)
 
    <br><?php print FICHIERS_ACCETPES;?>: gif, jpg, png
    </td>
  </tr>
  <?php
  if($header2Image!=="" AND $header2Image!=="noPath") {
  ?>
  	<tr>
	<td><?php print IMNAGE_DE_FOND;?>
	</td>
	<td>
	<?php 
		print "<a href='../".$header2Image."' target='_blank'>".VOIR_IMAGE."</a>";
	?>
	</td>
	</tr>
	<?php
	}
	?>
  <tr>
    <td valign="top"><?php print "<a href='im/copyNavTop.jpg' target='_blank'>".AFFICHE_NAV_TOP."</a>";?></td>

    <?php
        if($tableDisplay == "oui") {
            $checkyestop = "checked";
            $checknotop = "";
        } else {
          $checkyestop = "";
          $checknotop = "checked";
        }
    ?>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="tableDisplay1" <?php print $checkyestop;?>>
    <?php print A27;?> <input type="radio" value="non" name="tableDisplay1" <?php print $checknotop;?>>
    </td>
  </tr>
  
  <tr>
    <td colspan="2" valign="top"><?php print "<a href='im/copyNavTopRight.jpg' target='_blank'>".AFFICHE_NAV_TOP_RIGHT."</a>";?></td>
</tr>
    <?php
        if($tableDisplayRight == "oui") {
            $checkyesRight = "checked";
            $checknoRight = "";
        } else {
          $checkyesRight = "";
          $checknoRight = "checked";
        }
    ?>
<tr>
<td></td>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="tableDisplayRight1" <?php print $checkyesRight;?>>
    <?php print A27;?> <input type="radio" value="non" name="tableDisplayRight1" <?php print $checknoRight;?>>
    </td>
  </tr>

  <tr>
<td colspan="2" valign="top"><?php print "<a href='im/copyNavTopLeft.jpg' target='_blank'>".AFFICHE_NAV_TOP_LEFT."</a>";?></td>
</tr>
    <?php
        if($tableDisplayLeft == "oui") {
            $checkyesLeft = "checked";
            $checknoLeft = "";
        } else {
          $checkyesLeft = "";
          $checknoLeft = "checked";
        }
    ?>
<tr>
<td></td>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="tableDisplayLeft1" <?php print $checkyesLeft;?>>
    <?php print A27;?> <input type="radio" value="non" name="tableDisplayLeft1" <?php print $checknoLeft;?>>
    </td>
  </tr>

  <tr>
<td colspan="2" valign="top"><?php print "<a href='im/copyNavTopCenter.jpg' target='_blank'>".AFFICHER_CENTRE_NAV."</a>";?></td>
</tr>
    <?php
        if($addNavCenterPage == "oui") {
            $checkyesRightc = "checked";
            $checknoRightc = "";
        } else {
          $checkyesRightc = "";
          $checknoRightc = "checked";
        }
    ?>
<tr>
<td></td>

    <td>
    <?php print A26;?> <input type="radio" value="oui" name="addNavCenterPage1" <?php print $checkyesRightc;?>>
    <?php print A27;?> <input type="radio" value="non" name="addNavCenterPage1" <?php print $checknoRightc;?>>
    </td>
  </tr> 

   <tr>
<td colspan="2" valign="top"><a href="im/copyCategFooter.jpg" target="_blank"><?php print DISPLAY_CAT;?></a></td>
</tr>
      <?php
          // Display category list
              if($catFooter == "oui") {
                  $checkedyes10Cat = "checked";
                  $checkedno10Cat = "";
              } else {
                $checkedyes10Cat = "";
                $checkedno10Cat = "checked";
              }
      ?>
<tr>
<td></td>

    <td> <?php print A26;?>
      <input type="radio" value="oui" name="catFooter1" <?php print $checkedyes10Cat;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="catFooter1" <?php print $checkedno10Cat;?> >
    </td>
  </tr>
  </table>
<br>


<?php // btw?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a37z" name="<?php print A37;?>"><?php print A37;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A38;?></td>
    <td>
      <input type="text" class="vullen" name="taxe1" size="5" value="<?php print $taxe;?>">&nbsp;&nbsp;%</td>
      <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print A39;?></td>
    <td>
      <input type="text" class="vullen" name="taxeName1" size="5" value="<?php print $taxeName;?>">
    </td>
  </tr>
  <tr>
    <td><?php print A40;?></td>
    <td>
      <?php
  if($taxePosition == "Tax included") $sel1 = "selected"; else $sel1 = "";
  if($taxePosition == "Plus tax") $sel2 = "selected"; else $sel2 = "";
  if($taxePosition == "No tax") $sel3 = "selected"; else $sel3 = "";

  print "<select name='taxePosition1'>";
  print "<option name='taxePosition1' value='Tax included' $sel1>De BTW is in de prijs inbegrepen</option>";
  print "<option name='taxePosition1' value='Plus tax' $sel2>De BTW wordt extra op de prijs gerekend</option>";
  print "<option name='taxePosition1' value='No tax' $sel3>Geen BTW berekening</option>";
  print "</select>";

  ?>
    </td>
  </tr>


  <tr>
    <td width="200" valign="top"><?php print AFFICHER_PAR_DEFAUT_NOTVA;?></td>
      <?php
              if($noTva == "oui") {
                  $checkedyes20q = "checked";
                  $checkedno20q = "";
              } else {
                $checkedyes20q = "";
                $checkedno20q = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="noTva1" <?php print $checkedyes20q;?>>
      <?php print A27;?>
      <input type="radio" value="non" name="noTva1" <?php print $checkedno20q;?>>
      <br><?php print DEMANDE_DANS_LE_FORMULAIRE;?>
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print VAL_MAN_TVA;?></td>
      <?php
              if($tvaManuelValidation == "oui") {
                  $checkedyes20qr = "checked";
                  $checkedno20qr = "";
              } else {
                $checkedyes20qr = "";
                $checkedno20qr = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="tvaManuelValidation1" <?php print $checkedyes20qr;?>>
      <?php print A27;?>
      <input type="radio" value="non" name="tvaManuelValidation1" <?php print $checkedno20qr;?>>
    </td>
  </tr>
</table>
<br>


<?php // verzending ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a44z" name="<?php print A44;?>"><?php print A44;?></a></b>
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print A45;?></td>
    <td>
      <input type="text" class="vullen" name="livraisonComprise1" size="5" value="<?php print $livraisonComprise;?>">
      <?php print $symbolDevise;?> <?php print A46;?></b>
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A47;?></td>
    <td>
<?php
  $query = mysql_query("select * from admin");
  $zone = mysql_fetch_array($query);
$zoneExplode = explode("|", $zone['free_shipping_zone']);
?>
<table border="0" width="200" cellspacing="0" cellpadding="2" class="TABLE">
  <tr bgcolor="#FFFFFF">
    <td>Zone1</td>
    <?php
        if(isset($zoneExplode[0]) and $zoneExplode[0] == "zone1") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone1" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone1" <?php print $checkno;?>></td>
  </tr>
  <tr>
    <td>Zone2</td>
    <?php
        if(isset($zoneExplode[1]) and $zoneExplode[1] == "zone2") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone2" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone2" <?php print $checkno;?>></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>Zone3</td>
    <?php
        if(isset($zoneExplode[2]) and $zoneExplode[2] == "zone3") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone3" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone3" <?php print $checkno;?>></td>
  </tr>
  <tr>
    <td>Zone4</td>
    <?php
        if(isset($zoneExplode[3]) and $zoneExplode[3] == "zone4") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone4" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone4" <?php print $checkno;?>></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>Zone5</td>
    <?php
        if(isset($zoneExplode[4]) and $zoneExplode[4] == "zone5") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone5" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone5" <?php print $checkno;?>></td>
  </tr>
  <tr>
    <td>Zone6</td>
    <?php
        if(isset($zoneExplode[5]) and $zoneExplode[5] == "zone6") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone6" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone6" <?php print $checkno;?>></td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td>Zone7</td>
    <?php
        if(isset($zoneExplode[6]) and $zoneExplode[6] == "zone7") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone7" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone7" <?php print $checkno;?>></td>
  </tr>
  <tr>
    <td>Zone8</td>
    <?php
        if(isset($zoneExplode[7]) and $zoneExplode[7] == "zone8") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone8" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone8" <?php print $checkno;?>></td>
  </tr>
    <tr bgcolor="#FFFFFF">
    <td>Zone9</td>
    <?php
        if(isset($zoneExplode[8]) and $zoneExplode[8] == "zone9") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone9" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone9" <?php print $checkno;?>></td>
  </tr>
    <tr>
    <td>Zone10</td>
    <?php
        if(isset($zoneExplode[9]) and $zoneExplode[9] == "zone10") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone10" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone10" <?php print $checkno;?>></td>
  </tr>
    <tr bgcolor="#FFFFFF">
    <td>Zone11</td>
    <?php
        if(isset($zoneExplode[10]) and $zoneExplode[10] == "zone11") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
    ?>
    <td><?php print A26;?> <input type="radio" value="oui" name="zone11" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone11" <?php print $checkno;?>></td>
  </tr>
    <tr>
    <td>Zone12</td>
<?php
        if(isset($zoneExplode[11]) and $zoneExplode[11] == "zone12") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
   print "<td>".A26." <input type='radio' value='oui' name='zone12' ".$checkyes."></td>";
   print "<td>".A27." <input type='radio' value='non' name='zone12' ".$checkno."></td>";
?>
    <!--
    <td><?php print A26;?> <input type="radio" value="oui" name="zone12" <?php print $checkyes;?>></td>
    <td><?php print A27;?> <input type="radio" value="non" name="zone12" <?php print $checkno;?>></td>
    -->
  </tr>
</table>

    </td>
  </tr>
<tr>
    <td width="200">Activeer gratis</td>
    <?php
        if($activerPromoLivraison == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activerPromoLivraison1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerPromoLivraison1" <?php print $checkno;?> >
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A48;?></td>
    <?php
        if($displayPromoShipping == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayPromoShipping1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayPromoShipping1" <?php print $checkno;?> >
    </td>
  </tr>
</table>
<br>


<?php // optimalisatie ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a449z" name="<?php print OPTIMISATION_IMAGES;?>"><?php print OPTIMISATION_IMAGES;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print GD;?></td>
    <?php
    // image GD
        if($gdOpen == "oui") {
            $checkyesk = "checked";
            $checknok = "";
        } else {
          $checkyesk = "";
          $checknok = "checked";
        }
?>
    <td><?php print A26;?>
      <input type="radio" value="oui" name="gdOpen1" <?php print $checkyesk;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="gdOpen1" <?php print $checknok;?> >
    </td>

    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" class="knop" value="<?php print A96;?>" class="knop"  name="submit">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print COULEUR_DE_FOND;?></td>
    <td>
     
     <table border="0"  cellspacing="0" cellpadding="2"><tr>
        <td valign='middle'>Hex: #&nbsp;<input type="text" name="backGdColor1" size="8" class="vullen" value="<?php print $backGdColorAdmin;?>"></td> 
        <td valign='top'><a href="#" onClick="cp2.select(document.forms['form1'].backGdColor1,'pick423'); return false;" NAME="pick423" ID="pick423" title="<?php print SELECT_COLOR;?>"><img src='im/zzz.gif' border='0' width='14' height='14' style='background:#<?php print $backGdColorAdmin;?>; border:#000000 1px solid;' align='middle'></a>
     </td></tr></table>
     <div>RGB: <?php print $backGdColor;?></div>
     
    </td>
  </tr>
</table>
<br>


<?php // valuta ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">


  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a66z" name="<?php print A66;?>"><?php print A66;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A67;?></td>
    <td>
      <input type="text" class="vullen" name="devise1" size="10" value="<?php print $devise;?>">
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print A68;?></td>
    <td>
      <input type="text" class="vullen" name="symbolDevise1" size="3" value="<?php print $symbolDevise;?>">
    </td>
  </tr>

  <tr>
    <td width="200"><?php print AFICHAGE_DEVISE2;?></td>
    <?php
        if($devise2Visible == "oui") {
            $checkedyes1c = "checked";
            $checkedno1c = "";
        } else {
          $checkedyes1c = "";
          $checkedno1c = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="devise2Visible1" <?php print $checkedyes1c;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="devise2Visible1" <?php print $checkedno1c;?> >
    </td>
  </tr>
  <tr>
    <td width="200"><?php print DEVISE2;?></td>
    <td>
      <input type="text" class="vullen" name="symbolDevise21" size="6" value="<?php print $symbolDevise2;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print TAUX_DEVISE2;?></td>
    <td>
      1 <?php print $symbolDevise2;?> = <input type="text" class="vullen" name="tauxDevise21" size="6" value="<?php print $tauxDevise2;?>"> <?php print $devise;?>
    </td>
  </tr>
</table>
<br>


<?php // talen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a69z" name="<?php print A69;?>"><?php print A69;?></a></b>
    </td>
  </tr>
  <tr>
  <td valign="top" width="200"><?php print A70;?></td>
  <td>
  <?php
$langDispo = mysql_query("SELECT * FROM languages");
   print "<table align='left' border='0' cellspacing='0' cellpadding='2' class='TABLE'><tr>";
  $i=0;
  $f="";
while($brow = mysql_fetch_array($langDispo)) {
      if($f=="#FFFFFF") $f = "#F1F1F1"; else $f = "#FFFFFF";
      $i=$i+1;
      $a = $i-1;
      $lan[]= $brow['name'];
      print "</tr><tr bgcolor='".$f."'><td>".$i." - <input type='text' name='langueName[".$a."]' size='10' class='vullen' value='".$brow['name']."'></td>";
        if($brow['visible'] == "yes") {
          $checkedyesx = "checked";
          $checkednox = "";
        } else {
          $checkedyesx = "";
          $checkednox = "checked";
        }
         print "<td>";
         print "&nbsp;&nbsp;&nbsp;".A26." <input type='radio' value='yes' name='idioma[".$a."]' ".$checkedyesx.">";
         print "&nbsp;&nbsp;".A27." <input type='radio' value='no' name='idioma[".$a."]' ".$checkednox.">";
         print "<input type='hidden' value='".$brow['name']."' name='lan[".$a."]'>";
         print "<input type='hidden' value='".$brow['languages_id']."' name='lanId[".$a."]'>";
         print "</td>";
}
   print "</tr></table>";
   $lanNum = count($lan);
         print "<input type='hidden' value='".$lanNum."' name='lanNum'>";
   ?>
  </td>
  <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>


  <tr>
    <td width="200"><?php print A71;?></td>
    <td>
      <?php
$langDispo2 = mysql_query("SELECT * FROM languages WHERE visible='yes'");
print "<select name='langue1' class='site'>";
while($a_row = mysql_fetch_array($langDispo2)) {
      if($a_row['defaut'] == "yes" ) $sel="selected"; else $sel="";
      print "<option value='".$a_row['code']."' name='langue1' $sel>".$a_row['name']."-".$a_row['code']."</option>";
}
print "</select>";
  ?>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A72;?></td>
    <?php
        if($langVisible == "oui") {
            $checkedyes1 = "checked";
            $checkedno1 = "";
        } else {
          $checkedyes1 = "";
          $checkedno1 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="langVisible1" <?php print $checkedyes1;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="langVisible1" <?php print $checkedno1;?> >
    </td>
  </tr>
</table>
<br>


<?php // css ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td colspan="3" class="boxtitleYellow">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a73z" name="<?php print A73;?>"><?php print A73;?></a>: <?php print strtoupper($colorInter);?>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A74;?></td>
    <td>css/
 <?php
  if($colorInter == "Noir-Black") $sel11a = "selected"; else $sel11a="";
  if($colorInter == "Gris-Grey") $sel12a = "selected"; else $sel12a="";
  if($colorInter == "Jaune-Yellow") $sel13a = "selected"; else $sel13a="";
  if($colorInter == "Blanc-White") $sel14a = "selected"; else $sel14a="";
  if($colorInter == "Bleu-Blue") $sel15a = "selected"; else $sel15a="";
  if($colorInter == "Rose-Pink") $sel16a = "selected"; else $sel16a="";
  if($colorInter == "perso") $sel17a = "selected"; else $sel17a="";
  if($colorInter == "scss") $sel18a = "selected"; else $sel18a="";
  if($colorInter == "scss2") $sel20a = "selected"; else $sel20a="";
  if($colorInter == "Gris-Grey2") $sel19a = "selected"; else $sel19a="";

  print "<select name='colorInter1'>";
  print "<option name='colorInter1' value='scss' $sel18a>scss</option>";
  print "<option name='colorInter1' value='Jaune-Yellow' $sel13a>Jaune-Yellow</option>";
  print "<option name='colorInter1' value='scss2' $sel20a>scss2</option>";
  print "<option name='colorInter1' value='Gris-Grey' $sel12a>Gris-Grey</option>";
  print "<option name='colorInter1' value='Rose-Pink' $sel16a>Rose-Pink</option>";
  print "<option name='colorInter1' value='Noir-Black' $sel11a>Noir-Black</option>";
  print "<option name='colorInter1' value='Blanc-White' $sel14a>Blanc-White</option>";
  print "<option name='colorInter1' value='Bleu-Blue' $sel15a>Bleu-Blue</option>";
  print "<option name='colorInter1' value='perso' $sel17a>Perso</option>";
  print "</select>.css";
  print "&nbsp;&nbsp;&nbsp;<a href='css_bestand.php'>".EDITER_MODIFIER."</a>";
  ?>
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
 <tr>
    <td width="200"><?php print A72;?></td>
    <?php
        if($interfaceVisible == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="interfaceVisible1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="interfaceVisible1" <?php print $checkno;?> >
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A75;?></td>
    <?php
        if($activerCouleurPerso == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activerCouleurPerso1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerCouleurPerso1" <?php print $checkedno20;?> >
    </td>
  </tr>
</table>
<br>





<?php // kortingen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td colspan="3" class="boxtitleYellow">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a76z" name="<?php print A76;?>"><?php print A76;?></a>
    </td>
  </tr>
 <tr>
  <td><?php print A77;?></td>
  <td><input type=text class="vullen" name="remiseOrderMax1" size="5" value="<?php print $remiseOrderMax;?>"> <?php print $symbolDevise;?> <b><?php print A55;?> (3)</b></td>
  <td rowspan="6" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td><?php print A78;?></td>
    <td><input type=text class="vullen" name="remise1" size="10" value="<?php print $remise;?>">
      <?php
      if($remiseType == "%") $sel100 = "selected"; else $sel100 = "";
      if($remiseType == $symbolDevise) $sel200 = "selected"; else $sel200 = "";
        print "<select name='remiseType1'>";
        print "<option name='remiseType1' value='%' ".$sel100.">%</option>";
        print "<option name='remiseType1' value='".$symbolDevise."' ".$sel200.">".$symbolDevise."</option>";
        print "</select>";
      ?>
      </td>
  </tr>
<tr>
    <td width="200"><?php print A79;?></td>
    <?php
        if($activerRemise == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activerRemise1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerRemise1" <?php print $checkno;?> >
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A80;?></td>
<?php
        if($displayPromoRemise == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayPromoRemise1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayPromoRemise1" <?php print $checkno;?> >
    </td>
  </tr>

<tr>
    <td width="200"><?php print REMISE_TTC_HT;?></td>
    <td>      
    <?php
    // Remise TTC ou HT
      if($remiseOnTax == "TTC") $sel100 = "selected"; else $sel100 = "";
      if($remiseOnTax == "HT") $sel101 = "selected"; else $sel101 = "";
        print "<select name='remiseOnTax1'>";
        print "<option value='TTC' ".$sel100.">TTC</option>";
        print "<option value='HT' ".$sel101.">HT</option>";
        print "</select>";
    ?>
    </td>
  </tr>
 <tr>
  <td><?php print SEUIL_EXTRA."<br>(winkelmandje)";?></td>
  <td><input type=text class="vullen" name="seuilCadeau1" size="5" value="<?php print $seuilCadeau;?>">&nbsp;&nbsp<?php print $symbolDevise;?></td>
  </tr>
</table>
<br>


<?php // korting vorige aankopen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td colspan="3" class="boxtitleYellow">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a76zz" name="<?php print A510A;?>"><?php print A510A;?></a>
    </td>
  </tr>

<tr>
    <td width="200"><?php print A510;?></td>

    <?php
        if($activerRemisePastOrder == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activerRemisePastOrder1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerRemisePastOrder1" <?php print $checkno;?> >
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

 <tr>
  <td><?php print DATE_ACTIV;?>
  </td>
  <td>
  <!--// European date format DD-MM-YYYY START-->
  <!--<input type=text class="vullen" name="dateActivationPdf1" size="15" value="<?php print $dateActivationPdf;?>">&nbsp;<?php print FORMAT;?>: YYYY-mm-dd<br><?php print AUJOURD;?>: <?php print date('Y-m-d');?>-->
  <input type=text class="vullen" name="dateActivationPdf1" size="15" value="<?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$dateActivationPdf);?>">&nbsp;<?php print FORMAT;?>: DD-MM-JJJJ<br><?php print AUJOURD;?>: <?php print date('d-m-Y');?>
  <!--// European date format DD-MM-YYYY END-->
  </td>
  </tr>

 <tr>
  <td><?php print A512;?></td>
  <td><input type=text class="vullen" name="remisePastOrder1" size="5" value="<?php print $remisePastOrder;?>"> %</td>
  </tr>
</table>
<br>



<?php // banners ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a82z" name="<?php print A82;?>"><?php print A82;?></a></b>
    </td>
  </tr>
  <tr>
    <td valign="top" width="200"><?php print A92A;?> banners</td>

    <?php
        if($bannerVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>

    <td> <?php print A26;?>
      <input type="radio" value="oui" name="bannerVisible1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="bannerVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

   <tr>
    <td width="200" valign="top"><a href="im/copyHeaderTop.jpg" target="_blank"><?php print DISPLAY_BANNER_TOP;?></a></td>

      <?php
          // Display banners top
              if($bannerHeader == "oui") {
                  $checkedyes10BanTop = "checked";
                  $checkedno10BanTop = "";
              } else {
                $checkedyes10BanTop = "";
                $checkedno10BanTop = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="bannerHeader1" <?php print $checkedyes10BanTop;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="bannerHeader1" <?php print $checkedno10BanTop;?> >
    </td>
  </tr>  

   <tr>
	<td  width="200" valign="top"><a href="im/copyHeader2.jpg" target="_blank"><?php print DISPLAY_BANNER_TOP;?>2</a></td>
 
      <?php
          // Display banners top 2
              if($bannerHeader2 == "oui") {
                  $checkedyes10BanTop2 = "checked";
                  $checkedno10BanTop2 = "";
              } else {
                $checkedyes10BanTop2 = "";
                $checkedno10BanTop2 = "checked";
              }
      ?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="bannerHeader21" <?php print $checkedyes10BanTop2;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="bannerHeader21" <?php print $checkedno10BanTop2;?> >
    </td>
  </tr>  


   <tr>
    <td valign="top" width="200"><a href="im/copyBannerFooter.jpg" target="_blank"><?php print DISPLAY_BANNER;?></a></td>
 
      <?php
          // Display banners bottom
              if($bannerFooter == "oui") {
                  $checkedyes10Ban = "checked";
                  $checkedno10Ban = "";
              } else {
                $checkedyes10Ban = "";
                $checkedno10Ban = "checked";
              }
      ?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="bannerFooter1" <?php print $checkedyes10Ban;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="bannerFooter1" <?php print $checkedno10Ban;?> >
    </td>
  </tr>  
</table>
<br>



<?php // voorraad ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aStockz" name="Stock">Voorraad</a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A86;?></td>
    <td>
      <input type="text" class="vullen" name="seuilStock1" size="3" value="<?php print $seuilStock;?>">
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>



<?php // URL interface ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a95z" name="<?php print A95;?>"><?php print A95;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200">URL</td>
    <td>
      <input type="text" class="vullen" name="urlAdminClient1" size="60" value="<?php print $urlAdminClient;?>">
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

  <tr>
    <td colspan="2" valign="top"><?php print ENVOYER_EMAIL_CONNEXION_ISC;?></td>
</tr>
      <?php
              if($iscEmail == "oui") {
                  $checkedyes20qb = "checked";
                  $checkedno20qb = "";
              } else {
                $checkedyes20qb = "";
                $checkedno20qb = "checked";
              }
      ?>
<tr><td></td>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="iscEmail1" <?php print $checkedyes20qb;?>>
      <?php print A27;?>
      <input type="radio" value="non" name="iscEmail1" <?php print $checkedno20qb;?>>
    </td>
  </tr>
</table>
<br>


<?php // minimum bestelling?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a99z" name="<?php print A1505;?>"><?php print A1505;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top" ><?php print A1505;?></td>
    <td>
      <input type="text" class="vullen" name="minimumOrder1" size="4" value="<?php print $minimumOrder;?>">&nbsp;&nbsp;<?php print $symbolDevise;?>
      <br>
      <?php print A1506;?>
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>

<?php // Artikels affiliate ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td colspan="3" class="boxtitleYellow">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a100z1" name="<?php print A1520;?>"><?php print A1520;?></a>
    </td>

  </tr>
   <tr>
    <td width="200" valign="top"><?php print A110A;?></td>
 
    <?php
        if($displayRelatedImage == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayRelatedImage1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayRelatedImage1" <?php print $checkno;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

 <tr>
    <td width="100" valign="top"><?php print A110B;?></td>
    <td>
      <input type="text" class="vullen" name="ImageSizeDescRelated1" size="3" value="<?php print $ImageSizeDescRelated;?>"> <?php print A52." ".A110C;?>
    </td>
  </tr>
</table>
<br>


<?php // artikel op 0 ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a999zz" name="<?php print RESERVATION_ARTICLE;?>"><?php print RESERVATION_ARTICLE;?></a></b>
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print AFF_ART_OUT;?></td>

      <?php
              if($displayOutOfStock == "oui") {
                  $checkedyes20u = "checked";
                  $checkedno20u = "";
              } else {
                $checkedyes20u = "";
                $checkedno20u = "checked";
              }
      ?>
 
    <td valign="top"><?php print A26;?>
      <input type="radio" value="oui" name="displayOut1" <?php print $checkedyes20u;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayOut1" <?php print $checkedno20u;?> >
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>  
  
  <tr>
    <td width="200" valign="top"><?php print AFFICHER_PAR_DEFAUT;?></td>
      <?php // activation option achat d'article en commande
              if($actRes == "oui") {
                  $checkedyes20r = "checked";
                  $checkedno20r = "";
              } else {
                $checkedyes20r = "";
                $checkedno20r = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="actRes1" <?php print $checkedyes20r;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="actRes1" <?php print $checkedno20r;?> >
      <br><?php print POUR_ARTICLE;?>
    </td>
    </tr>
</table>
<br>


<?php // cadeau?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a999zc" name="<?php print CHEQUE_CADEAU;?>"><?php print CHEQUE_CADEAU;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print ACTVGC;?></td>
      <?php
              if($gcActivate == "oui") {
                  $checkedyes20qf = "checked";
                  $checkedno20qf = "";
              } else {
                $checkedyes20qf = "";
                $checkedno20qf = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="gcActivate1" <?php print $checkedyes20qf;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="gcActivate1" <?php print $checkedno20qf;?> >
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print MONTANT_MINI;?></td>
    <td>
      <input type="text" class="vullen" name="seuilGc1" size="10" value="<?php print $seuilGc;?>">
    </td>
  </tr>
</table>
<br>


<?php // sluiten ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a999Closed" name="<?php print FERMETURE_BOUTIQUE;?>"><?php print FERMETURE_BOUTIQUE;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print FERMER_BOUTIQUE;?></td>
      <?php
              if($storeClosed == "oui") {
                  $checkedyes20qfu = "checked";
                  $checkedno20qfu = "";
              } else {
                $checkedyes20qfu = "";
                $checkedno20qfu = "checked";
              }
   $resultClosed = mysql_query("SELECT shop_closed FROM admin");
   $rowpClosed = mysql_fetch_array($resultClosed);
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="storeOpen1" <?php print $checkedyes20qfu;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="storeOpen1" <?php print $checkedno20qfu;?> >
    </td>
    <td rowspan="2" valign="top" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print MESSAGE_BOUTIQUE_FERMEE;?></td>
    <td>
        <textarea cols="100%" rows="6" name="storeClosedMessage1"><?php print $rowpClosed['shop_closed'];?></textarea>
    </td>
  </tr>
</table>
<br>


<?php // snel kopen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a999express" name="<?php print ACHAT_EXPRESS;?>"><?php print ACHAT_EXPRESS;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A92A;?></td>
      <?php
              if($expressBuy == "oui") {
                  $checkedyes20q = "checked";
                  $checkedno20q = "";
              } else {
                $checkedyes20q = "";
                $checkedno20q = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="expressBuy1" <?php print $checkedyes20q;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="expressBuy1" <?php print $checkedno20q;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>

<?php // RSS ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleYellow" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a999RSS" name="RSS">RSS</a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A92A;?> RSS</td>
      <?php
              if($activeRSS == "oui") {
                  $checkedyes20qSS = "checked";
                  $checkedno20qSS = "";
              } else {
                $checkedyes20qSS = "";
                $checkedno20qSS = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activeRSS1" <?php print $checkedyes20qSS;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activeRSS1" <?php print $checkedno20qSS;?> >
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
    <tr>
    <td width="200" valign="top"><?php print A92A;?> RSS module</td>
      <?php
              if($activeRSSMod == "oui") {
                  $checkedyes20qSSk = "checked";
                  $checkedno20qSSk = "";
              } else {
                $checkedyes20qSSk = "";
                $checkedno20qSSk = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activeRSSMod1" <?php print $checkedyes20qSSk;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activeRSSMod1" <?php print $checkedno20qSSk;?> >
    </td>
  </tr>
</table>
<br>
  
<?php // module breedte ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
   <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a130z" name="<?php print A130;?>"><?php print A130;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A131;?></td>
    <td>
      <input type="text" class="vullen" name="larg_rub1" size="5" value="<?php print $larg_rub;?>">
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print HAUTMOD;?></td>
    <td>
      <input type="text" class="vullen" name="hauteurTitreModule1" size="5" value="<?php print $hauteurTitreModule;?>">
    </td>
  </tr>
    <tr>
    <td width="200"><?php print A205;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarInfo1" size="5" value="<?php print $maxCarInfo;?>">
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print COLONNE_GAUCHE;?></td>

<?php
 
        if($colomnLeft == "oui") {
            $checkedyes10Hierr = "checked";
            $checkedno10Hierr = "";
        } else {
          $checkedyes10Hierr = "";
          $checkedno10Hierr = "checked";
        }
?>

    <td> <?php print A26;?>
      <input type="radio" value="oui" name="colomnLeft1" <?php print $checkedyes10Hierr;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="colomnLeft1" <?php print $checkedno10Hierr;?> >
    </td>
  </tr>
  
    <tr>
    <td width="200" valign="top"><?php print COLONNE_DROITE;?></td>
	<?php
 
        if($colomnRight == "oui") {
            $checkedyes10Hierx = "checked";
            $checkedno10Hierx = "";
        } else {
          $checkedyes10Hierx = "";
          $checkedno10Hierx = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="colomnRight1" <?php print $checkedyes10Hierx;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="colomnRight1" <?php print $checkedno10Hierx;?> >
    </td>
  </tr>
</table>
<br>


<?php // de shop module ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a201z" name="<?php print A201;?>"><?php print A201;?></a></b>
    </td>
  </tr>


  <tr>
    <td width="200" valign="top"><?php print MENU_HIER;?></td>

<?php
    // menu hierarchique php
        if($menuVisiblePhp == "oui") {
            $checkedyes10Hier = "checked";
            $checkedno10Hier = "";
        } else {
          $checkedyes10Hier = "";
          $checkedno10Hier = "checked";
        }
?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuVisiblePhp1" <?php print $checkedyes10Hier;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuVisiblePhp1" <?php print $checkedno10Hier;?> >
    </td>
    <td rowspan="21" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

  <tr>
    <td valign="top"><?php print EXPAND_MENU;?></td>

<?php
    // menu hierarchique expand/collapse
        if($expandMenu == "oui") {
            $checkedyes10Hiera = "checked";
            $checkedno10Hiera = "";
        } else {
          $checkedyes10Hiera = "";
          $checkedno10Hiera = "checked";
        }
?>
 
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="expandMenu1" <?php print $checkedyes10Hiera;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="expandMenu1" <?php print $checkedno10Hiera;?> >
    </td>
  </tr>
  <tr>
    <td colspan="2" align=top"><?php print QT_MENU;?></td>
</tr>
<?php
    // menu hierarchique Afficher quantit�
        if($qtMenu == "oui") {
            $checkedyes10Hierae = "checked";
            $checkedno10Hierae = "";
        } else {
          $checkedyes10Hierae = "";
          $checkedno10Hierae = "checked";
        }
?>
<tr><td></td>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="qtMenu1" <?php print $checkedyes10Hierae;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="qtMenu1" <?php print $checkedno10Hierae;?> >
    </td>
  </tr>

  <tr>
    <td valign="top"><?php print COULEUR_FOND_SURVOL_CATEGORIES;?></td>

     
        <td valign='top'>Hex: #&nbsp;&nbsp;<input type="text" class="vullen" name="backGdColorCatLine1" size="8" value="<?php print $backGdColorCatLine;?>">&nbsp;&nbsp;&nbsp;
        <a href="#" onClick="cp2.select(document.forms['form1'].backGdColorCatLine1,'pick425'); return false;" NAME="pick425" ID="pick425" title="<?php print SELECT_COLOR;?>"><img src='im/zzz.gif' border='0' width='14' height='14' style='background:#<?php print $backGdColorCatLine;?>; border:#000000 1px solid;' align='middle'></a></td>
 
    </td>
  </tr>
  <tr>
    <td width="200"><?php print DISPLAY_SEARCH;?></td>
<?php
        if($moteurVisibleMenuPhp == "oui") {
            $checkedyes10cssrd = "checked";
            $checkedno10cssrd = "";
        } else {
          $checkedyes10cssrd = "";
          $checkedno10cssrd = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="moteurVisibleMenuPhp1" <?php print $checkedyes10cssrd;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="moteurVisibleMenuPhp1" <?php print $checkedno10cssrd;?> >
   </td>
   </tr>
  <tr>
    <td width="200"><?php print MENU_TAB;?></td>
<?php
    // MENU TAB
        if($menuVisibleTab == "oui") {
            $checkedyes10Tab = "checked";
            $checkedno10Tab = "";
        } else {
          $checkedyes10Tab = "";
          $checkedno10Tab = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuVisibleTab1" <?php print $checkedyes10Tab;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuVisibleTab1" <?php print $checkedno10Tab;?> >
    </td>
  </tr>

    <tr>
    <td width="200"><?php print NUM_TAB;?></td>
    <td>
      <input type="text" class="vullen" name="nbreMenuTab1" size="5" value="<?php print $nbreMenuTab;?>">
    </td>
  </tr>

  <tr>
    <td width="200"><?php print CENTRER_MENU_TAB;?></td>
<?php
        if($menuTabCenter == "oui") {
            $checkedyes10Tabg = "checked";
            $checkedno10Tabg = "";
        } else {
          $checkedyes10Tabg = "";
          $checkedno10Tabg = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuTabCenter1" <?php print $checkedyes10Tabg;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuTabCenter1" <?php print $checkedno10Tabg;?> >
    </td>
  </tr>



  <tr>
    <td width="200"><?php print MENU_H_DISPLAY;?></td>
<?php
    // menu deroulant horizontal CSS
        if($menuCssVisibleHorizon == "oui") {
            $checkedyes10cssh = "checked";
            $checkedno10cssh = "";
        } else {
          $checkedyes10cssh = "";
          $checkedno10cssh = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuCssVisibleh1" <?php print $checkedyes10cssh;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuCssVisibleh1" <?php print $checkedno10cssh;?> >
    </td>
  </tr>

    <tr>
    <td width="200"><?php print LARGEUR_MENU_H;?></td>
    <td>
      <input type="text" class="vullen" name="menuWidthCSSH1" size="5" value="<?php print $menuWidthCSSH;?>">
    </td>
  </tr>

    <tr>
    <td width="200"><?php print LARGEUR_MENU_H2;?></td>
    <td>
      <input type="text" class="vullen" name="menuWidthCSSH21" size="5" value="<?php print $menuWidthCSSH2;?>">
    </td>
  </tr>  

  <tr>
    <td width="200"><?php print CENTRER_MENU_H;?></td>
<?php
        if($menuCssHorizonCenter == "oui") {
            $checkedyes10csshc = "checked";
            $checkedno10csshc = "";
        } else {
          $checkedyes10csshc = "";
          $checkedno10csshc = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuCssHorizonCenter1" <?php print $checkedyes10csshc;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuCssHorizonCenter1" <?php print $checkedno10csshc;?> >
    </td>
  </tr>

  <tr>
    <td width="200"><?php print MENU_V_DISPLAY;?></td>
<?php
    // menu deroulant vertical CSS
        if($menuCssVisibleVertical == "oui") {
            $checkedyes10css = "checked";
            $checkedno10css = "";
        } else {
          $checkedyes10css = "";
          $checkedno10css = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuCssVisible1" <?php print $checkedyes10css;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuCssVisible1" <?php print $checkedno10css;?> >
   </td>
   </tr>
   <tr>
   <td>
         Visueel verticaal menu
   </td>
   <td>
<?php
              if($menuV == "fixe") $sel11V = "selected"; else $sel11V = "";
              if($menuV == "deroulant") $sel21V = "selected"; else $sel21V = "";
            ?>
              <select name="menu1V">
                <option value="fixe" <?php print $sel11V;?>>Vast</option>
                <option value="deroulant" <?php print $sel21V;?>>Naar beneden</option>
      </select>
    </td>
  </tr>

    <tr>
    <td width="200"><?php print LARGEUR_MENU_V;?></td>
    <td>
      <input type="text" class="vullen" name="menuWidthCSSVSub1" size="5" value="<?php print $menuWidthCSSVSub;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print DISPLAY_SEARCH;?></td>
<?php
        if($moteurVisibleMenuV == "oui") {
            $checkedyes10cssr = "checked";
            $checkedno10cssr = "";
        } else {
          $checkedyes10cssr = "";
          $checkedno10cssr = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="moteurVisibleMenuV1" <?php print $checkedyes10cssr;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="moteurVisibleMenuV1" <?php print $checkedno10cssr;?> >
   </td>
   </tr>

  <tr>
    <td width="200"><?php print AFF_NEWS;?></td>
<?php
    // afficher nouveaut�
        if($menuNewsVisible == "oui") {
            $checkedyes10cssw = "checked";
            $checkedno10cssw = "";
        } else {
          $checkedyes10cssw = "";
          $checkedno10cssw = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuNewsVisible1" <?php print $checkedyes10cssw;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuNewsVisible1" <?php print $checkedno10cssw;?> >
   </td>
  </tr>  
  
  <tr>
    <td width="200"><?php print AFF_PROMO;?></td>
<?php
    // Afficher Promotions
        if($menuPromoVisible == "oui") {
            $checkedyes10cssz = "checked";
            $checkedno10cssz = "";
        } else {
          $checkedyes10cssz = "";
          $checkedno10cssz = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuPromoVisible1" <?php print $checkedyes10cssz;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuPromoVisible1" <?php print $checkedno10cssz;?> >
   </td>
  </tr>  

  <tr>
    <td width="200"><?php print AFFICHER_ACCUEIL;?></td>
<?php
    // Afficher Accueil
        if($menuAccueilVisible == "oui") {
            $checkedyes10csszr = "checked";
            $checkedno10csszr = "";
        } else {
            $checkedyes10csszr = "";
            $checkedno10csszr = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuAccueilVisible1" <?php print $checkedyes10csszr;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuAccueilVisible1" <?php print $checkedno10csszr;?> >
   </td>
  </tr> 

  <tr>
    <td width="200"><?php print A202;?></td>
    <td>
      <input type="text" class="vullen" name="nbreCarCat1" size="5" value="<?php print $nbreCarCat;?>">
    </td>
  </tr>
    <tr>
    <td width="200"><?php print A203;?></td>
    <td>
      <input type="text" class="vullen" name="nbreCarSubCat1" size="5" value="<?php print $nbreCarSubCat;?>">
    </td>
  </tr>
</table>
<br>


<?php // Module winkel mandje ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a201zc" name="<?php print A33A;?>"><?php print A33A;?></a></b>
    </td>
  </tr>
  <tr>

    <td width="200"><?php print A83;?></td>
    <?php
        if($cartVisible == "oui") {
            $checkedyes10c = "checked";
            $checkedno10c = "";
        } else {
          $checkedyes10c = "";
          $checkedno10c = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="cartVisible1" <?php print $checkedyes10c;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="cartVisible1" <?php print $checkedno10c;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>


<?php // Module offerte ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a1999z" name="<?php print DEVIS;?>"><?php print DEVIS;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php // DEVIS
        if($devisModule == "oui") {
            $checkedyes209 = "checked";
            $checkedno209 = "";
        } else {
          $checkedyes209 = "";
          $checkedno209 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="devisModule1" <?php print $checkedyes209;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="devisModule1" <?php print $checkedno209;?> >
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
<tr>
    <td width="200"><?php print A92100;?></td>
    <?php
        if($devis == "oui") {
            $checkedyes2091 = "checked";
            $checkedno2091 = "";
        } else {
          $checkedyes2091 = "";
          $checkedno2091 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="devis1" <?php print $checkedyes2091;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="devis1" <?php print $checkedno2091;?> >
    </td>
  </tr>
  <tr>
    <td width="200"><?php print VALID;?></td>
    <td>
      <input type="text" class="vullen" name="devisValid1" size="2" value="<?php print $devisValid;?>"> <?php print A36;?>.
    </td>
  </tr>
</table>
<br>


<?php // Module laatst gezien ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a130zss" name="<?php print MODULE_DEJA_VU;?>"><?php print MODULE_DEJA_VU;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($lastViewVisible == "oui") {
            $checkedyes10sc = "checked";
            $checkedno10sc = "";
        } else {
          $checkedyes10sc = "";
          $checkedno10sc = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="lastViewVisible1" <?php print $checkedyes10sc;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="lastViewVisible1" <?php print $checkedno10sc;?> >
    </td>
    <td valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>

<?php // Module dit toevoegen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a135zss" name="<?php print MODULE_ADDTHIS;?>"><?php print MODULE_ADDTHIS;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($addThisVisible == "oui") {
            $checkedyes10scr = "checked";
            $checkedno10scr = "";
        } else {
          $checkedyes10scr = "";
          $checkedno10scr = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addThisVisible1" <?php print $checkedyes10scr;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addThisVisible1" <?php print $checkedno10scr;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>

  
<?php // Module promoties ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a23z" name="<?php print A23;?>"><?php print A23;?></a></b>
    </td>
  </tr>
  
<tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($promoVisible == "oui") {
            $checkedyes10 = "checked";
            $checkedno10 = "";
        } else {
          $checkedyes10 = "";
          $checkedno10 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="promoVisible1" <?php print $checkedyes10;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="promoVisible1" <?php print $checkedno10;?> >
    </td>
    <td rowspan="6" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print A24;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_promo1" size="2" value="<?php print $nbre_promo;?>"> <?php print A25;?>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A60;?></td>
    <td>
      <input type="text" class="vullen" name="hautImageMaxPromo1" size="2" value="<?php print $hautImageMaxPromo;?>">
    </td>
  </tr>
<tr>
    <td width="200"><?php print FORCE_PROMO;?></td>
    <?php
        if($forcePromo == "oui") {
            $checkedyes10v = "checked";
            $checkedno10v = "";
        } else {
          $checkedyes10v = "";
          $checkedno10v = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="forcePromo1" <?php print $checkedyes10v;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="forcePromo1" <?php print $checkedno10v;?> >
    </td>
  </tr>
<tr>
    <td width="200"><?php print SEUIL_ALERTE_ACTIVE;?></td>
    <?php // ventes flash
        if($activeSeuilPromo == "oui") {
            $checkedyes10xx = "checked";
            $checkedno10xx = "";
        } else {
          $checkedyes10xx = "";
          $checkedno10xx = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activeSeuilPromo1" <?php print $checkedyes10xx;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activeSeuilPromo1" <?php print $checkedno10xx;?> >
    </td>
  </tr>
<tr>
    <td width="200" valign="top" ><?php print SEUIL_ALERTE;?></td>
    <td>
      <input type="text" class="vullen" name="seuilPromo1" size="3" value="<?php print $seuilPromo;?>"> <?php print A36;?>
      <br><?php print SEUIL_ALERTE_INFO;?>
    </td>
  </tr>
</table>
<br>

  
<?php // Module snel menu ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a28z" name="<?php print A28;?>"><?php print A28;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($menuRapideVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuRapideVisible1" <?php print $checkedyes20;?> >
      Neen
      <input type="radio" value="non" name="menuRapideVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print A205;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarQuick1" size="5" value="<?php print $maxCarQuick;?>">
    </td>
  </tr>
</table>
<br>


<?php // Module link  menu ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a29z" name="<?php print A29;?>"><?php print A29;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($menuNavVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuNavVisible1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuNavVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>


<?php // Module notificatie?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a29az" name="<?php print A29A;?>"><?php print A29A;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($menuNavCom == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="menuNavCom1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="menuNavCom1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="10" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>

  <tr>
    <td width="200"><?php print ACTIVE_ACTU;?></td>
    <?php
        if($activeActu == "oui") {
            $checkedyes20AC = "checked";
            $checkedno20AC = "";
        } else {
          $checkedyes20AC = "";
          $checkedno20AC = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activeActu1" <?php print $checkedyes20AC;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activeActu1" <?php print $checkedno20AC;?> >
    </td>
  </tr>

<tr>
    <td width="200"><?php print CARMAX_ACTU;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarActu1" size="5" value="<?php print $maxCarActu;?>">
    </td>
</tr>

<tr>
    <td width="200"><?php print NBRE_ACTU_PAGE;?></td>
    <td>
      <input type="text" class="vullen"  name="nbreLigneActu1" size="5" value="<?php print $nbreLigneActu;?>">
    </td>
</tr>

<tr>
    <td width="200"><?php print AFF_IMAGE;?></td>
    <?php
        if($nowVisible == "oui") {
            $checkedyes10cN = "checked";
            $checkedno10cN = "";
        } else {
          $checkedyes10cN = "";
          $checkedno10cN = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="nowVisible1" <?php print $checkedyes10cN;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="nowVisible1" <?php print $checkedno10cN;?> >
    </td>
  </tr>





<tr>
    <td width="200"><?php print AFF_COMMENT;?></td>
    <?php
        if($comVisible == "oui") {
            $checkedyes10cNn = "checked";
            $checkedno10cNn = "";
        } else {
          $checkedyes10cNn = "";
          $checkedno10cNn = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="comVisible1" <?php print $checkedyes10cNn;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="comVisible1" <?php print $checkedno10cNn;?> >
    </td>
  </tr>
  
<tr>
    <td width="200" valign="top" ><?php print COULEUR_BACK;?></td>
    <td>
      <input type="text" class="vullen" name="scrollColor1" size="9" value="<?php print $scrollColor;?>">&nbsp;<a href="#" onClick="cp2.select(document.forms['form1'].scrollColor1,'pick422');return false;" NAME="pick422" ID="pick422">Hexadecimal</a>
      <br><?php print NONE_SI_TRANSPARENT;?>
    </td>
</tr>

<tr>
    <td width="200"><?php print PAUSE;?></td>
    <td>
      <input type="text" class="vullen" name="scrollDelay1" size="5" value="<?php print $scrollDelay;?>">&nbsp;(3000 = 3 sec)
    </td>
</tr>
<tr>
    <td width="200"><?php print H;?></td>
    <td>
      <input type="text" class="vullen" name="scrollHeight1" size="5" value="<?php print $scrollHeight;?>">&nbsp;pixels
    </td>
</tr>
<tr>
    <td width="200"><?php print L;?></td>
    <td>
      <input type="text" class="vullen" name="scrollWidth1" size="5" value="<?php print $scrollWidth;?>">&nbsp;pixels
    </td>
</tr>

</table>
<br>




<?php // Module Top 10?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a30z" name="<?php print A30;?>"><?php print A30;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($topVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="topVisible1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="topVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  class="knop" name="submit"></td>
  </tr>
</table>
<br>


<?php // Module informatie ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a31z" name="<?php print A31;?>"><?php print A31;?></a></b>
    </td>
  </tr>

  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($information == "oui") {
            $checkedyes = "checked";
            $checkedno = "";
        } else {
          $checkedyes = "";
          $checkedno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="information1" <?php print $checkedyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="information1" <?php print $checkedno;?> >
    </td>
    <td rowspan="1" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
</table>
<br>


<?php // Module valuta berekenen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a32z" name="<?php print A32;?>"><?php print A32;?></a></b>
    </td>
  </tr>

  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($converter == "oui") {
            $checkedyes = "checked";
            $checkedno = "";
        } else {
          $checkedyes = "";
          $checkedno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="converter1" <?php print $checkedyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="converter1" <?php print $checkedno;?> >
    </td>
    <td rowspan="1" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
</table>
<br>


<?php // Module nieuws ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a33z" name="<?php print A33;?>"><?php print A33;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($nouvVisible == "oui") {
            $checkedyes100 = "checked";
            $checkedno100 = "";
        } else {
          $checkedyes100 = "";
          $checkedno100 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="nouvVisible1" <?php print $checkedyes100;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="nouvVisible1" <?php print $checkedno100;?> >
    </td>
        <td rowspan="3" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
  <tr>
    <td width="200"><?php print A34;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_nouv1" size="3" value="<?php print $nbre_nouv;?>"> <?php print A25;?>
    </td>
  </tr>
  <tr>
    <td><?php print A151;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_jour_nouv1" size="3" value="<?php print $nbre_jour_nouv;?>"> <?php print A36;?>
    </td>
  </tr>
  
  <tr>
    <td width="200"><?php print A60;?></td>
    <td>
      <input type="text" class="vullen" name="hautImageMaxNews1" size="3" value="<?php print $hautImageMaxNews;?>">
    </td>
  </tr>
  <tr>
    <td width="200"><?php print FORCE_NOUV;?></td>
    <?php
        if($forceNouv == "oui") {
            $checkedyes100p = "checked";
            $checkedno100p = "";
        } else {
          $checkedyes100p = "";
          $checkedno100p = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="forceNouv1" <?php print $checkedyes100p;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="forceNouv1" <?php print $checkedno100p;?> >
    </td>
  </tr>
</table>
<br>



<?php // Module zoeken ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a81z" name="<?php print A81;?>"><?php print A81;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
<?php
        if($moteurVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="moteurVisible1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="moteurVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="2" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
  
  
  <tr>
    <td width="200"><?php print A50U;?></td>
    <td>
      <input type="text" class="vullen" name="moteurLigneNum1" size="5" value="<?php print $moteurLigneNum;?>">
</td>
  </tr>
</table>
<br>

<?php // module nieuwsbrief ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a84z" name="<?php print A84;?>"><?php print A84;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($newsletterVisible == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="newsletterVisible1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="newsletterVisible1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="2" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
 <tr>
    <td width="200"><?php print A85;?></td>
    <td>
      <input type="text" class="vullen" name="urlNewsletter1" size="60" value="<?php print $urlNewsletter;?>">
    </td>
  </tr>
</table>
<br>


<?php // Module affiliate ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a1500z" name="<?php print A1500;?>"><?php print A1500;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print A83;?></td>
    <?php
        if($affiliateVisible == "oui") {
            $checkedyes20A = "checked";
            $checkedno20A = "";
        } else {
          $checkedyes20A = "";
          $checkedno20A = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="affVisible1" <?php print $checkedyes20A;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="affVisible1" <?php print $checkedno20A;?> >
    </td>
    <td rowspan="4" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>
 <tr>
    <td width="200"><?php print A1501;?></td>
    <td>
      <input type="text" class="vullen" name="affiliateCom1" size="2" value="<?php print $affiliateCom;?>"> %
    </td>
  </tr>
 <tr>
    <td width="200"><?php print A1502;?></td>
    <td>
      <input type="text" class="vullen" name="affiliateTop1" size="6" value="<?php print $affiliateTop;?>"> <?php print $symbolDevise;?>
    </td>
  </tr>
  <tr>
    <td width="200"><?php print AFF_AUTO;?></td>
    <?php
        if($affiliateAuto == "oui") {
            $checkedyes20An = "checked";
            $checkedno20An = "";
        } else {
          $checkedyes20An = "";
          $checkedno20An = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="affiliateAuto1" <?php print $checkedyes20An;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="affiliateAuto1" <?php print $checkedno20An;?> >
    </td>
  </tr>
</table>
<br>

<?php // Module uw account ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleRed" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a84Az" name="<?php print A84A;?>"><?php print A84A;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A83;?></td>
    <?php
        if($idVisible == "oui") {
            $checkedyes201 = "checked";
            $checkedno201 = "";
        } else {
          $checkedyes201 = "";
          $checkedno201 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="idVisible1" <?php print $checkedyes201;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="idVisible1" <?php print $checkedno201;?> >
    </td>
    <td rowspan="4" valign="top"  align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop"  name="submit"></td>
  </tr>

  <tr>
    <td width="200"  valign="top"><?php print ACTIVER_PAIEMENT_DIRECT;?></td>
    <?php
        if($directPayment == "oui") {
            $checkedyes201t = "checked";
            $checkedno201t = "";
        } else {
          $checkedyes201t = "";
          $checkedno201t = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="directPayment1" <?php print $checkedyes201t;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="directPayment1" <?php print $checkedno201t;?> >
    </td>
  </tr>

 <tr>
    <td width="200"  valign="top">
      <?php (isset($_GET['v']) AND $_GET['v']==1)? print "<span style='color:#CC0000; background-color:#FFFFFF; font-weight:bold'>".COMMANDE_EN_ATTENTE."</span>" : print COMMANDE_EN_ATTENTE;?>
    </td>
    <td>
      <input type="text" class="vullen" name="pendingOrder1" size="4" value="<?php print $pendingOrder;?>"> <?php print A36;?>
      <br><?php print NO_LIMIT;?>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print PERMETTRE_MODIF_ACCOUNT;?></td>
    <?php
        if($modifCustomerNb == "oui") {
            $checkedyes201e = "checked";
            $checkedno201e = "";
        } else {
          $checkedyes201e = "";
          $checkedno201e = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="modifCustomerNb1" <?php print $checkedyes201e;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="modifCustomerNb1" <?php print $checkedno201e;?> >
    </td>
  </tr>
</table> 
<br> 
 



<?php // Pagina lijst.php  - list.php ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleBlue" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a49z" name="<?php print A49;?>"><?php print A49;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A50;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_ligne1" size="5" value="<?php print $nbre_ligne;?>">
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  
  
  <tr>
    <td width="200" valign="top"><?php print POPUP;?></td>
    <?php
        if($listPop == "oui") {
            $checkyesbgj = "checked";
            $checknobgj = "";
        } else {
          $checkyesbgj = "";
          $checknobgj = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="listPop1" <?php print $checkyesbgj;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="listPop1" <?php print $checknobgj;?> >
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print IM_ZOOM_SIZE;?></td>
    <td>
      <input type="text" class="vullen" name="imZoomMax1" size="4" value="<?php print $imZoomMax; ?>"> pixels
      <?php if(isset($_GET['mod'])) print "&nbsp;<span class='fontrouge'><-- <b>".MODIFIER_ICI."</b></span>";?>
    </td>
  </tr>
  
  <tr>
    <td width="200"><?php print A51;?></td>
    <td>
      <input type="text" class="vullen" name="haut_im1" size="5" value="<?php print $haut_im; ?>"> <?php print A51;?>
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print MAX_CAR_LIST;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarDesc1" size="5" value="<?php print $maxCarDesc; ?>">
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print A53;?></td>
    <td>
     <?php
     $ordered = mysql_query("SELECT * FROM ordered");
     $ordNum = mysql_num_rows($ordered);

     print "<input type='hidden' value='".$ordNum."' name='ordNum'>";

     if($ordNum > 0) {
     $titre_order_1="";
     $titre_order_2="";
     $titre_order_3="";
     $i2="";
     print "<table border='0' cellspacing='0' cellpadding='2' class='TABLE'>";
        while($prod = mysql_fetch_array($ordered)) {
              if($c=="#FFFFFF") $c = "#F1F1F1"; else $c = "#FFFFFF";
              $i = $prod['order_id'];
              $i2 = $i2+1;
              print "<tr bgcolor='".$c."'><td width='150'>";
              print $i2."- ".$prod['order_'.$_SESSION['lang']];
              print "</td><td>";
              if($prod['order_view'] == 'yes') {
                     $titre_order_1 .= $prod['order_1']."|";
                     $titre_order_2 .= $prod['order_2']."|";
                     $titre_order_3 .= $prod['order_3']."|";

              print A26." <input type='radio' value='yes' name='order_id[".$i."]' checked>";
              print A27." <input type='radio' value='no' name='order_id[".$i."]'>";
              }
              else {
              print A26." <input type='radio' value='yes' name='order_id[".$i."]'>";
              print A27." <input type='radio' value='no' name='order_id[".$i."]' checked>";
              }
              print "</td></tr>";
        }
        print "</table>";
              print "<input type='hidden' value='".$titre_order_1."' name='titre_order_11'>";
              print "<input type='hidden' value='".$titre_order_2."' name='titre_order_21'>";
              print "<input type='hidden' value='".$titre_order_3."' name='titre_order_31'>";
     }
     else {
       print "nada";
     }
     ?>
    </td>
  </tr>

        <?php // Classement par d�faut
            $orderedDefault = mysql_query("SELECT * FROM ordered WHERE order_view='yes'");
            $orderedDefaultNum = mysql_num_rows($orderedDefault);
            if($orderedDefaultNum > 0) {
            print "<tr><td width='200'>";
            print A53A;
            print "</td>";
            print "<td>";
            print "<select name='orderByDefault'>";
                print "<option value='0'>Id</option>";
                while($prodDefault = mysql_fetch_array($orderedDefault)) {
                    if(strtolower($prodDefault['order_'.$_SESSION['lang']]) == strtolower($defaultOrder)) $selz="selected"; else $selz="";
                    print "<option value='".$prodDefault['order_id']."' ".$selz.">".$prodDefault['order_'.$_SESSION['lang']]."</option>";
                }
            print "</select>";
            print "</td>";
            print "</tr>";
            }
            else {
                print "<input type='hidden' value='0' name='orderByDefault'>";
            }
        ?>
  
  <tr>
</tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <td valign="top"><?php print A54;?><br><b><?php print A55;?> (1)</b></td>
<?php
   $result2 = mysql_query("SELECT text_list_1,text_list_2,text_list_3 FROM admin");
   $rowp = mysql_fetch_array($result2);
?>
        <td>
      <table border="0" cellspacing="0" cellpadding="1"><tr><td valign=top>

		<img src='im/be.gif' class='absmiddle'>&nbsp;<img src='im/nl.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textList_3"><?php print $rowp['text_list_3'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif' class='absmiddle'>&nbsp;<img src='im/fr.gif' class='absmiddle'>&nbsp;</td><td><textarea cols="70" rows="6" name="textList"><?php print $rowp['text_list_1'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif' class='absmiddle'>&nbsp;<img src='im/uk.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textList_2"><?php print $rowp['text_list_2'];?></textarea></td>
		</tr></table>
		</td>
  </tr>
</table>
<br>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td width="200" valign="top"><?php print A62;?></td>
    <?php
        if($addCommuniqueList == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addCommuniqueList1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addCommuniqueList1" <?php print $checkno;?> >
    </td>
  </tr>
<tr>
    <td width="200" valign="top"><a href="im/configcss/_3.png" target="_blank"><?php print NUMPAGE;?></a></td>
    <td>
      <input type="text" class="vullen" name="numNumList1" size="5" value="<?php print $numNumList; ?>"> <?php print PARPAGE;?>
    </td>
  </tr>
<tr>
    <td width="200" valign="top"><?php print ADD_CART_LIST;?></td>
    <?php
        if($addCartList == "oui") {
            $checkyesk = "checked";
            $checknok = "";
        } else {
          $checkyesk = "";
          $checknok = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addCartList1" <?php print $checkyesk;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addCartList1" <?php print $checknok;?> >
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print BACKGROUND_CELLS_LIST;?></td>
    <td>
     
     <table border="0"  cellspacing="0" cellpadding="2"><tr>
        <td valign='middle'>Hex: #&nbsp;&nbsp;<input type="text" name="backGdColorListLine1" class="vullen"  size="8" value="<?php print $backGdColorListLine;?>"></td> 
        <td valign='top'><a href="#" onClick="cp2.select(document.forms['form1'].backGdColorListLine1,'pick424'); return false;" NAME="pick424" ID="pick424" title="<?php print SELECT_COLOR;?>"><img src='im/zzz.gif' border='0' width='14' height='14' style='background:#<?php print $backGdColorListLine;?>; border:#000000 1px solid;' align='middle'></a>
     </td></tr></table>
     
    </td>
  </tr>
</table>
<br>

  
  
  
  
  
<?php // categories.php?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleBlue" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a57z" name="<?php print A57;?>"><?php print A57;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A58;?></td>
    <td>
      <input type="text" class="vullen" name="NbreProduitAffiche1" size="3" value="<?php print $NbreProduitAffiche;?>"> <?php print A25;?>
    </td>
    <td rowspan="14" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  
<tr>
    <td width="200" valign="top"><?php print POPUP;?></td>
    <?php
        if($categoriesPop == "oui") {
            $checkyesbg = "checked";
            $checknobg = "";
        } else {
          $checkyesbg = "";
          $checknobg = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="categoriesPop1" <?php print $checkyesbg;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="categoriesPop1" <?php print $checknobg;?> >
    </td>
  </tr>
  
  <tr>
    <td width="200" valign=top><?php print IM_ZOOM_SIZE;?></td>
    <td>
      <b><?php print $imZoomMax;?></b> pixels (<?php print "<span class='fontrouge'>".ALLER_A."</span>";?> <a href="site_config.php?mod=1#<?php print A49;?>"><span class='fontrouge'><?php print A49;?></span></a>)
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print A59;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_col1" size="3" value="<?php print $nbre_col;?>">
    </td>
  </tr>
  
    <tr>
    <td width="200" valign="top"><?php print A60;?></td>
    <td>
      <input type="text" class="vullen" name="imageSizeCat1" size="3" value="<?php print $imageSizeCat;?>">
    </td>
  </tr>

    <td width="200" valign="top"><?php print NBR_CAR_TITLE;?></td>
    <td>
      <input type="text" class="vullen" name="carSizeTitleCat1" size="3" value="<?php print $carSizeTitleCat;?>">
    </td>
  </tr>
  
     <tr>
    <td width="200" valign="top"><?php print A150;?></td>
    <td>
      <input type="text" class="vullen" name="largTableCategories1" size="3" value="<?php print $largTableCategories;?>">
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print A63;?></td>
    <?php
        if($addSubMenu == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addSubMenu1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addSubMenu1" <?php print $checkno;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print A59Z;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_col_sm1" size="3" value="<?php print $nbre_col_sm;?>">
    </td>
  </tr>
  
     <tr>
    <td width="200" valign="top"><?php print A59SIZE;?></td>
    <td>
      <input type="text" class="vullen" name="im_size_sm1" size="3" value="<?php print $im_size_sm;?>">
    </td>
  </tr>
  
<tr>
    <td width="200" valign="top"><?php print AFFICHE_CAT_NAME;?></td>
    <?php
        if($displaySubCategoryName == "oui") {
            $checkyesbgs = "checked";
            $checknobgs = "";
        } else {
          $checkyesbgs = "";
          $checknobgs = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displaySubCategoryName1" <?php print $checkyesbgs;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displaySubCategoryName1" <?php print $checknobgs;?> >
    </td>
  </tr>

<tr>
    <td width="200" valign="top"><?php print AFFICHE_CAT_NAME_UNDER;?></td>
    <?php
        if($displaySubCategoryNameUnder == "oui") {
            $checkyesbgsf = "checked";
            $checknobgsf = "";
        } else {
          $checkyesbgsf = "";
          $checknobgsf = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displaySubCategoryNameUnder1" <?php print $checkyesbgsf;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displaySubCategoryNameUnder1" <?php print $checknobgsf;?> >
      &nbsp;Indien "<?php print AFFICHE_CAT_NAME;?>" = <?php print A26;?>
    </td>
</tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  
  <tr>
    <td valign="top"><?php print A61;?><br><b><?php print A55;?> (1)</b></td>


<?php
   $result2 = mysql_query("SELECT text_categories_1,text_categories_2,text_categories_3 FROM admin");
   $rowp = mysql_fetch_array($result2);
?>
        <td>



      <table border="0" cellspacing="0" cellpadding="1"><tr><td valign=top>
		<img src='im/be.gif'>&nbsp;<img src='im/nl.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textCategories_3"><?php print $rowp['text_categories_3'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif'>&nbsp;<img src='im/fr.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textCategories"><?php print $rowp['text_categories_1'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif'>&nbsp;<img src='im/uk.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textCategories_2"><?php print $rowp['text_categories_2'];?></textarea></td>
		</tr></table>
		
		</td>
</tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td width="200" valign="top"><?php print A62;?></td>
    <?php
        if($addCommuniqueCategories == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addCommuniqueCategories1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addCommuniqueCategories1" <?php print $checkno;?> >
    </td>
  </tr>
</table>
<br>  
  
<?php // cataloog.php'?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td colspan="3" class="boxtitleBlue">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a64z" name="<?php print A64;?>"><?php print A64;?></a>
    </td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print A58;?></td>
    <td>
      <input type="text" class="vullen" name="NbreProduitAfficheCatalog1" size="3" value="<?php print $NbreProduitAfficheCatalog;?>">
    </td>
    <td rowspan="13" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  
<tr>
    <td width="200" valign="top"><?php print POPUP;?></td>
    <?php
        if($catalogPop == "oui") {
            $checkyesb = "checked";
            $checknob = "";
        } else {
          $checkyesb = "";
          $checknob = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="catalogPop1" <?php print $checkyesb;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="catalogPop1" <?php print $checknob;?> >
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print IM_ZOOM_SIZE;?></td>
    <td>
      <b><?php print $imZoomMax;?></b> pixels (<?php print "<span class='fontrouge'>".ALLER_A."</span>";?> <a href="site_config.php?mod=1#<?php print A49;?>"><span class='fontrouge'><?php print A49;?></span></a>)
    </td>
  </tr>
  
    <tr>
    <td width="200" valign="top"><?php print A59;?></td>
    <td>
      <input type="text" class="vullen" name="nbre_col_catalog1" size="3" value="<?php print $nbre_col_catalog;?>">
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print A60;?></td>
    <td>
      <input type="text" class="vullen" name="imageSizeCatalog1" size="3" value="<?php print $imageSizeCatalog;?>">
    </td>
  </tr>
  
   <tr>
    <td width="200" valign="top"><?php print A150;?></td>
    <td>
      <input type="text" class="vullen" name="largTableCatalog1" size="3" value="<?php print $largTableCatalog;?>">
    </td>
  </tr>
    
     <tr>
    <td width="200" valign="top"><?php print AFFICHER;?></td>
    <td>

<?php
  if($catDisplayPromo == "on") $sel110 = "checked"; else $sel110 = "";
  if($catDisplayNews == "on") $sel111 = "checked"; else $sel111 = "";
  if($catDisplayBest == "on") $sel112 = "checked"; else $sel112 = "";
  if($catDisplayFew == "on") $sel114 = "checked"; else $sel114 = "";
  if($catDisplayExc == "on") $sel116 = "checked"; else $sel116 = "";
  if($catDisplayRandOne == "on") $sel113 = "checked"; else $sel113 = "";
  if($catDisplayRandAll == "on") $sel115 = "checked"; else $sel115 = "";
?>    
    <input name="catDisplayPromo1" type="checkbox" <?php print $sel110;?>> Promo<br>
    <input name="catDisplayNews1" type="checkbox" <?php print $sel111;?>> <?php print A210;?><br>
    <input name="catDisplayBest1" type="checkbox" <?php print $sel112;?>> <?php print A211;?><br>
    <input name="catDisplayFew1" type="checkbox" <?php print $sel114;?>> <?php print A411;?><br>
    <input name="catDisplayExc1" type="checkbox" <?php print $sel116;?>> <?php print EXCLUSIVITE;?><br>
    -----------------<br>
    <input name="catDisplayRandOne1" type="checkbox" <?php print $sel113;?>> <?php print A311;?><br>
    -- ofwel --<br>
    <input name="catDisplayRandAll1" type="checkbox" <?php print $sel115;?>> <?php print A311A;?><br>
    </td>
  </tr>

   <tr>
    <td width="200" valign="top"><?php print AFF_DESC_TABLE;?></td>
<?php
        if($displayDescCatalog == "oui") {
            $checkyesbsa = "checked";
            $checknobsa = "";
        } else {
          $checkyesbsa = "";
          $checknobsa = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayDescCatalog1" <?php print $checkyesbsa;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayDescCatalog1" <?php print $checknobsa;?> >
    </td>
  </tr>

   <tr>
    <td width="200" valign="top"><?php print DISPLAY_UNDER;?></td>
<?php
        if($displayUnderDescCatalog == "oui") {
            $checkyesbsau = "checked";
            $checknobsau = "";
        } else {
          $checkyesbsau = "";
          $checknobsau = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayUnderDescCatalog1" <?php print $checkyesbsau;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayUnderDescCatalog1" <?php print $checknobsau;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print NBR_CAR_TITLE;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarTitleAff1" size="3" value="<?php print $maxCarTitleAff;?>">
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print NBR_CAR_DESC;?></td>
    <td>
      <input type="text" class="vullen" name="maxCarCatAff1" size="3" value="<?php print $maxCarCatAff;?>">
    </td>
  </tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

  <tr>
    <td valign="top"><?php print A54;?><br><b><?php print A55;?> (1)</b></td>
<?php
   $result2H = mysql_query("SELECT text_home_1,text_home_2,text_home_3 FROM admin");
   $rowpH = mysql_fetch_array($result2H);
?>
      <td>
		
      <table border="0" cellspacing="0" cellpadding="1"><tr><td valign=top>
		<img src='im/be.gif'>&nbsp;<img src='im/nl.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textHome_3"><?php print $rowpH['text_home_3'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif'>&nbsp;<img src='im/fr.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textHome"><?php print $rowpH['text_home_1'];?></textarea></td>
		</tr><tr><td valign=top>
		<img src='im/leeg.gif'>&nbsp;<img src='im/uk.gif'>&nbsp;</td><td><textarea cols="70" rows="6" name="textHome_2"><?php print $rowpH['text_home_2'];?></textarea></td>
		</tr></table>
		
		</td>
  </tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
 <tr>
    <td width="200" valign="top"><?php print A62;?></td>
    <?php
        if($addCommuniqueHome == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addCommuniqueHome1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addCommuniqueHome1" <?php print $checkno;?> >
    </td>
  </tr>
</table>
<br>


<?php // beschrijving.php beschrijving.php ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

 <tr>
    <td colspan="3" class="boxtitleBlue">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a65z" name="<?php print A65;?>"><?php print A65;?></a>
    </td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print A60;?></td>
    <td>
      <input type="text" class="vullen" name="ImageSizeDesc1" size="3" value="<?php print $ImageSizeDesc;?>"> <?php print A52;?>
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print A60A;?></td>
    <td>
      <input type="text" class="vullen" name="SecImageSizeDesc1" size="3" value="<?php print $SecImageSizeDesc;?>"> <?php print A52;?>
    </td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print A60B;?></td>
    <td>
      <input type="text" class="vullen" name="SecImageWidthDesc1" size="3" value="<?php print $SecImageWidthDesc;?>"> <?php print A52;?>
    </td>
  </tr>

   <tr>
    <td width="200" valign="top"><?php print A110;?></td>
    <?php
        if($displayRelated == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayRelated1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayRelated1" <?php print $checkno;?> >
    </td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print BOOKMARK;?></td>
<?php
        if($addBookmark == "oui") {
            $checkyesads = "checked";
            $checknoads = "";
        } else {
          $checkyesads = "";
          $checknoads = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addBookmark1" <?php print $checkyesads;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addBookmark1" <?php print $checknoads;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print A92A." LightBox";?></td>
<?php
        if($lightbox == "oui") {
            $checkyesadst = "checked";
            $checknoadst = "";
        } else {
          $checkyesadst = "";
          $checknoadst = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="lightbox1" <?php print $checkyesadst;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="lightbox1" <?php print $checknoadst;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print DISPLAY_SHIPPING_DELIVERY;?></td>
<?php
        if($displayDelivery == "oui") {
            $checkyesadst2 = "checked";
            $checknoadst2 = "";
        } else {
          $checkyesadst2 = "";
          $checknoadst2 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayDelivery1" <?php print $checkyesadst2;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayDelivery1" <?php print $checknoadst2;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><a href="im/configcss/_2.png" target="_blank"><?php print DISPLAY_NAV_PRODUCT;?></a></td>
<?php
        if($displayNextProduct == "oui") {
            $checkyesadst2sx = "checked";
            $checknoadst2sx = "";
        } else {
          $checkyesadst2sx = "";
          $checknoadst2sx = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayNextProduct1" <?php print $checkyesadst2sx;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayNextProduct1" <?php print $checknoadst2sx;?> >
    </td>
  </tr>


 <tr>
    <td width="200" valign="top"><a href="im/configcss/_1.png" target="_blank"><?php print DISPLAY_PRODUCTS_LIST;?></a></td>
<?php
        if($displayProductsList == "oui") {
            $checkyesadst2x = "checked";
            $checknoadst2x = "";
        } else {
          $checkyesadst2x = "";
          $checknoadst2x = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayProductsList1" <?php print $checkyesadst2x;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayProductsList1" <?php print $checknoadst2x;?> >
    </td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print NUM_PRODUCTS_LIST;?></td>
    <td>
      <input type="text" class="vullen" name="displayProductsListNum1" size="3" value="<?php print $displayProductsListNum;?>">
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print AFFICHE_LOGO_PAYMENT_DESC;?></td>
<?php
        if($displayPaymentsLogoDesc == "oui") {
            $checkyesadst2x1 = "checked";
            $checknoadst2x1 = "";
        } else {
          $checkyesadst2x1 = "";
          $checknoadst2x1 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayPaymentsLogoDesc1" <?php print $checkyesadst2x1;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayPaymentsLogoDesc1" <?php print $checknoadst2x1;?> >
    </td>
  </tr>

 <tr>
    <td width="200" valign="top"><?php print AFFICHE_LOGO_LIV_DESC;?></td>
<?php
        if($displayShippingLogoDesc == "oui") {
            $checkyesadst2x1r = "checked";
            $checknoadst2x1r = "";
        } else {
          $checkyesadst2x1r = "";
          $checknoadst2x1r = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayShippingLogoDesc1" <?php print $checkyesadst2x1r;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayShippingLogoDesc1" <?php print $checknoadst2x1r;?> >
    </td>
  </tr>
</table>
<br>




<?php // pagina index.php ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

 <tr>
    <td colspan="3" class="boxtitleBlue">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a65z1" name="<?php print PAGEINDEX;?>"><?php print PAGEINDEX;?></a>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print AFFICHE_LANG_INDEX;?></td>
    <?php
        if($selectLang == "oui") {
            $checkyesLang = "checked";
            $checknoLang = "";
        } else {
          $checkyesLang = "";
          $checknoLang = "checked";
        }
    ?>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="selectLang1" <?php print $checkyesLang;?>>
    <?php print A27;?> <input type="radio" value="non" name="selectLang1" <?php print $checknoLang;?>>
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print REDIRECTION;?></td>
    <?php
        if($redirectToShop == "oui") {
            $checkyesLangss = "checked";
            $checknoLangss = "";
        } else {
          $checkyesLangss = "";
          $checknoLangss = "checked";
        }
    ?>
    <td>
    <?php print A26;?> <input type="radio" value="oui" name="redirectToShop1" <?php print $checkyesLangss;?>>
    <?php print A27;?> <input type="radio" value="non" name="redirectToShop1" <?php print $checknoLangss;?>>
    </td>
  </tr>
</table>
<br>

  
<?php // caddie.php winkelmandje ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td colspan="3" class="boxtitleBlue">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a106z" name="<?php print A106;?>"><?php print A106;?></a>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A108;?></td>
        <?php
        if($addImageCart == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="addImageCart1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="addImageCart1" <?php print $checkno;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
 <tr>
    <td width="200" valign="top"><?php print A107;?></td>
    <td>
      <input type="text" class="vullen" name="ImageSizeCart1" size="3" value="<?php print $ImageSizeCart;?>"> <?php print A52;?>
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print ACTIVERCD;?></td>
        <?php
        if($codeReductionActive == "oui") {
            $checkyescd = "checked";
            $checknocd = "";
        } else {
          $checkyescd = "";
          $checknocd = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="codeReductionActive1" <?php print $checkyescd;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="codeReductionActive1" <?php print $checknocd;?> >
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print A110." ".DES_ARTICLES_DANS_LE_CADDIE;?></td>
        <?php
        if($displayAffInCart == "oui") {
            $checkyescdw = "checked";
            $checknocdw = "";
        } else {
          $checkyescdw = "";
          $checknocdw = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayAffInCart1" <?php print $checkyescdw;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayAffInCart1" <?php print $checknocdw;?> >
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print AFFICHER_DANS_CADDIE;?></td>
    <?php
        if($lastViewCartVisible == "oui") {
            $checkedyes10scs = "checked";
            $checkedno10scs = "";
        } else {
          $checkedyes10scs = "";
          $checkedno10scs = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="lastViewCartVisible1" <?php print $checkedyes10scs;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="lastViewCartVisible1" <?php print $checkedno10scs;?> >
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print NOMBRE_ARTICLE_AFFICHES_DANS_CADDIE;?></td>
    <?php
        if($lastViewCartNum == "oui") {
            $checkedyes10scse = "checked";
            $checkedno10scse = "";
        } else {
          $checkedyes10scse = "";
          $checkedno10scse = "checked";
        }
?>
    <td>
    <input type="text" class="vullen" name="lastViewCartNum1" size="5" value="<?php print $lastViewCartNum;?>">
    </td>
  </tr>
</table>
<br>


<?php // Pagina bewaar winkelmandje'?>  
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

<tr>
    <td colspan="3" class="boxtitleBlue">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a1060A" name="<?php print A1060;?>"><?php print A1060;?></a>
    </td>
  </tr>

  <tr>
    <td width="200" valign="top"><?php print ACTIVE_SAVECART;?></td>
    <?php
        if($activeSaveCart == "oui") {
            $checkedyes10co = "checked";
            $checkedno10co = "";
        } else {
          $checkedyes10co = "";
          $checkedno10co = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="activeSaveCart1" <?php print $checkedyes10co;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activeSaveCart1" <?php print $checkedno10co;?> >
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
   <tr>
    <td width="200" valign="top"><?php print A1061;?></td>
    <td>
      <input type="text" class="vullen" name="saveCart1" size="3" value="<?php print $saveCart;?>"> <?php print A36;?>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A1065;?><br><b><?php print A55;?> (8)</td>
        <?php
        if($saveCartToOne == "oui") {
            $checkyes = "checked";
            $checkno = "";
        } else {
          $checkyes = "";
          $checkno = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="saveCartToOne1" <?php print $checkyes;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="saveCartToOne1" <?php print $checkno;?> >
    </td>
  </tr>
</table>
<br>


<?php // deze pagina this page ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <tr>
    <td class="boxtitleBlue" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a65z1xx" name="<?php print CETTE_PAGE;?>"><?php print CETTE_PAGE;?></a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print MENU_TOP;?></td>
    <td>
    <?php // Menu top
    if($adminMenu == "deroulant") $sel1m="selected"; else $sel1m="";
    if($adminMenu == "fixe") $sel2m="selected"; else $sel2m="";
    if($adminMenu == "both") $sel3m="selected"; else $sel3m="";
    ?>
        <select name="adminMenu1">
        <option value="fixe" <?php print $sel2m;?>><?php print HTML_MENU;?></option>

        </select>
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print ACTIVE_TINYCME;?></td>
    <td>
      <?php // tinyMCE
        if($activerTiny == "oui") {
            $checkedyes20ase = "checked";
            $checkedno20ase = "";
        } else {
          $checkedyes20ase = "";
          $checkedno20ase = "checked";
        }
       ?>
      <?php print A26;?>
      <input type="radio" value="oui" name="activerTiny1" <?php print $checkedyes20ase;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerTiny1" <?php print $checkedno20ase;?> >
    </td>
  </tr>
</table>
<br>


<?php // contant bij levering ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
  <tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#a111z" name="<?php print A111;?>"><?php print A111;?></a></b>
    </td> 
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A112;?></td>
    <td>
      <?php // contre remboursement
        if($activerContre == "oui") {
            $checkedyes20a = "checked";
            $checkedno20a = "";
        } else {
          $checkedyes20a = "";
          $checkedno20a = "checked";
        }
       ?>
      <?php print A26;?>
      <input type="radio" value="oui" name="activerContre1" <?php print $checkedyes20a;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerContre1" <?php print $checkedno20a;?> >
    </td>
    <td rowspan="2" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  
    <tr>
    <td width="200" valign="top"><?php print A113;?></td>
    <td>
      <input type="text" class="vullen" name="seuilContre1" size="3" value="<?php print $seuilContre;?>"> <?php print $symbolDevise;?>
    </td>
  </tr>
</table>
<br>


<?php // bank gegevens ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

<?php // Informations bancaire (pour paiement par virement bancaire)
   $query2a = mysql_query("SELECT * FROM admin");
   $result2a = mysql_fetch_array($query2a);
?>
    <tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <a href="#a115z" name="<?php print A115A;?>"><?php print A115A;?></a>
    </td>
  </tr>
    <tr>
    <td width="200" valign="top"><?php print A122;?></td>
    <td>
      <?php
        if($activerVirement == "oui") {
            $checkedyes20a = "checked";
            $checkedno20a = "";
        } else {
          $checkedyes20a = "";
          $checkedno20a = "checked";
        }
       ?>
      <?php print A26;?>
      <input type="radio" value="oui" name="activerVirement1" <?php print $checkedyes20a;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerVirement1" <?php print $checkedno20a;?> >
    </td>
    <td rowspan="8" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
      <tr>
    <td width="200" valign="top">Uw naam of bedrijfsnaam</td>
    <td><input type="text" name="titulaireBanque" class="vullen" size="60" value="<?php print $result2a['banqueTitulaireCompte'];?>"></td>
  </tr>
  <tr>
    <td width="200" valign="top">Naam van de bank</td>
    <td><input type="text" name="nomBanque" size="60" class="vullen" value="<?php print $result2a['banqueNom'];?>"></td>
  </tr>
    <tr>
    <td width="200" valign="top">Adres</td>
    <td><input type="text" name="adresseBanque" size="60" class="vullen" value="<?php print $result2a['banqueAdresse'];?>"></td>
  </tr>
      <tr>
    <td width="200" valign="top">Rekening nummer</td>
    <td><input type="text" name="numeroBanque" size="60" class="vullen" value="<?php print $result2a['banqueNumeroCompte'];?>"></td>
  </tr>
      <tr>
    <td width="200" valign="top">IBAN code</td>
    <td><input type="text" name="ibanBanque" size="60" class="vullen" value="<?php print $result2a['banqueIban'];?>"></td>
  </tr>
      <tr>
   <td width="200" valign="top">BIC code</td>
    <td><input type="text" name="swiftBanque" size="60" class="vullen" value="<?php print $result2a['banqueCodeSwift'];?>"></td>
  </tr>
      <tr>
    <td width="200" valign="top">RIB sleutel</td>
    <td><input type="text" name="rib" size="5" class="vullen" value="<?php print $result2a['banqueRib'];?>"></td>
  </tr>  
<tr>
</table>
<br>

<? // western union ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aWesternz" name="Western Union">Western Union</a></b>
    </td>
  </tr>
      <tr>
    <td width="200" valign="top"><?php print A121;?></td>
    <td>
      <?php
        if($activerWestern == "oui") {
            $checkedyes20a = "checked";
            $checkedno20a = "";
        } else {
          $checkedyes20a = "";
          $checkedno20a = "checked";
        }
       ?>
      <?php print A26;?>
      <input type="radio" value="oui" name="activerWestern1" <?php print $checkedyes20a;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="activerWestern1" <?php print $checkedno20a;?> >
    </td>
    <td rowspan="2" valign="top" class="boxtitle3" align="right"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print A120;?></td>
    <td>
      <input type="text" name="western1" size="50%" class="vullen"  value="<?php print $western;?>">
    </td>
  </tr>
</table>
<br>

  

  
<?php // Paypal?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aPaypalz" name="<?php print A87;?> PAYPAL"><?php print A87;?></a> via <a href="https://www.paypal.com/" target="_blank">PAYPAL</a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A90;?></td>
    <?php
        if($paypalPayment == "oui") {
            $checkedyes20 = "checked";
            $checkedno20 = "";
        } else {
          $checkedyes20 = "";
          $checkedno20 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="paypalPayment1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="paypalPayment1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="6" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top">Paypal E-mail</td>
    <td>
      <input type="text" class="vullen" name="paypalEmail1" size="80" value="<?php print $paypalEmail;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A88;?></td>
    <td>
      <input type="text" class="vullen" name="paypalReturn1" size="80" value="<?php print $paypalReturn;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">URL IPN/NIP</td>
    <td>
      <input type="text" class="vullen"  size="80%" value="http://<?php print $www2.$domaineFull;?>/paypal/ipn.php">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A89;?></td>
    <td>
      <input type="text" class="vullen" name="paypalDevise1" size="5" value="<?php print $paypalDevise;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print INSCRIVEZVOUS;?></td>
    <td>
      <a href="https://www.paypal.com/" target="_blank"><img src="im/paypal.gif" border="0"></a>
    </td>
  </tr>
</table>
<br>

<!--- NOT IN USE - NIET IN GEBRUIK 



<?php // 1EURO?>
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#tbz1euro" name="1EURO.COM"><?php print A87;?></a> via <a href="https://www.1euro.com/1-euro/cms/" target="_blank">1EURO.COM</a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A92A;?> 1EURO.COM</td>
    <?php
        if($euroPayment == "oui") {
            $checkedyes20neu = "checked";
            $checkedno20neu = "";
        } else {
          $checkedyes20neu = "";
          $checkedno20neu = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="euroPayment1" <?php print $checkedyes20neu;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="euroPayment1" <?php print $checkedno20neu;?> >
    </td>
    <td rowspan="7" valign="middle" class="boxtitle3"><input type="submit" value="<?php print A96;?>" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top">merchant_id</td>
    <td>
      <input type="text" name="merchant_id1" size="22" value="<?php print $merchant_id;?>">&nbsp;(<?php print FOURNI_PAR_EURO;?>)
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">ID Partenaire 1euro.com</td>
    <td>
      <input type="text" name="id_partenaire1" size="10" value="<?php print $id_partenaire;?>">&nbsp;(<?php print FOURNI_PAR_EURO;?>)
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">Plugin Version</td>
    <td>
    <?php
          if($plugin == "5") $sel1u = "selected"; else $sel1u="";
          if($plugin == "6") $sel2u = "selected"; else $sel2u="";
        
          print "<select name='plugin1'>";
          print "<option value='5' ".$sel1u.">5</option>";
          print "<option value='6' ".$sel2u.">6</option>";
          print "</select>";
    ?>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print "Afficher �l�ments graphiques dans la boutique";?></td>
    <?php
        if($displayGraphics == "oui") {
            $checkedyes20neud = "checked";
            $checkedno20neud = "";
        } else {
          $checkedyes20neud = "";
          $checkedno20neud = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="displayGraphics1" <?php print $checkedyes20neud;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="displayGraphics1" <?php print $checkedno20neud;?> >
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top"><?php print AIDE;?></td>
    <td>
      <a href="pathfileGen.php" target="_blank"><?php print COMPTE_1EURO_CONFIG;?>.</a>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="pathfileGen.php" target="_blank"><img src='../im/1euro.gif' border="0"></a></td>
  </tr>  
  
-- NOT IN USE - NIET IN GEBRUIK ---->
  
<?php // Ogone ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aOgonez" name="<?php print A87;?> OGONE"><?php print A87;?></a> via <a href="http://www.ogone.fr" target="_blank">OGONE</a></b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A1090;?></td>
    <?php
        if($ogonePayment == "oui") {
            $checkedyes20n = "checked";
            $checkedno20n = "";
        } else {
          $checkedyes20n = "";
          $checkedno20n = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="ogonePayment1" <?php print $checkedyes20n;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="ogonePayment1" <?php print $checkedno20n;?> >
    </td>
    <td rowspan="5" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top">PSPID</td>
    <td>
      <input type="text" class="vullen" name="ogonePspid1" size="8" value="<?php print $ogonePspid;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">Signature SHA-1-IN</td>
    <td>
      <input type="text" class="vullen" name="ogoneSha1" size="80%" value="<?php print $ogoneSha;?>">
      <br>(<?php print GUILLEMETS_NO;?>)
    </td>
  </tr>
 <tr>
    <td width="200" valign="top">Mode</td>
    <?php
        if($ogoneMode == "test") {
            $checkedyes20uu = "checked";
            $checkedno20uu = "";
        } else {
          $checkedyes20uu = "";
          $checkedno20uu = "checked";
        }
?>
    <td>Test:<input type="radio" value="test" name="ogoneMode1" <?php print $checkedyes20uu;?> >
        &nbsp;-&nbsp;
        Actief plaatsen:<input type="radio" value="prod" name="ogoneMode1" <?php print $checkedno20uu;?> >
    </td>
    </tr>
  <tr>
    <td width="200" valign="top"><?php print AIDE;?></td>
    <td>
      <a href="http://www.ecomshop.be/help/ogone.htm" target="_blank"><?php print COMPTE_CONFIG;?> Ogone.</a>
    </td>
  </tr>
</table>
<br>




<?php // 2CHECKOUT?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aCheckz" name="<?php print A87;?> 2CHECKOUT"><?php print A87;?></a> via <a href="http://www.2checkout.com" target="_blank">2CHECKOUT</a> (<?php print A55;?> 5)</b>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print A94;?></td>
      <?php
              if($co == "oui") {
                  $checkedyes20 = "checked";
                  $checkedno20 = "";
              } else {
                $checkedyes20 = "";
                $checkedno20 = "checked";
              }
      ?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="co1" <?php print $checkedyes20;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="co1" <?php print $checkedno20;?> >
    </td>
    <td rowspan="3" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>  
   <tr>
    <td width="200" valign="top"><?php print A93;?></td>
    <td>
    <?php
      if(isset($coDefaultDevise) and $coDefaultDevise == "AUD") $sel1 = "selected"; else $sel1="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "CAD") $sel2 = "selected"; else $sel2="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "CHF") $sel3 = "selected"; else $sel3="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "DKK") $sel4 = "selected"; else $sel4="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "EUR") $sel5 = "selected"; else $sel5="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "GBP") $sel6 = "selected"; else $sel6="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "HDK") $sel7 = "selected"; else $sel7="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "JPY") $sel8 = "selected"; else $sel8="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "NOK") $sel9 = "selected"; else $sel9="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "NZD") $sel10 = "selected"; else $sel10="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "SEK") $sel11 = "selected"; else $sel11="";
      if(isset($coDefaultDevise) and $coDefaultDevise == "USD") $sel12 = "selected"; else $sel12="";
      
    ?>
      <select name = "coDefaultDevise1">
      <option value="AUD" <?php print $sel1;?>>Australian Dollars</option>
      <option value="CAD" <?php print $sel2;?>>Canadian Dollars</option>
      <option value="CHF" <?php print $sel3;?>>Swiss Francs</option>
      <option value="DKK" <?php print $sel4;?>>Danish Kroner</option>
      <option value="EUR" <?php print $sel5;?>>Euros</option>
      <option value="GBP" <?php print $sel6;?>>British Pounds Sterling</option>
      <option value="HDK" <?php print $sel7;?>>Hong Kong Dollars</option>
      <option value="JPY" <?php print $sel8;?>>Japanese Yen</option>
      <option value="NOK" <?php print $sel9;?>>Norwegian Kroner</option>
      <option value="NZD" <?php print $sel10;?>>New Zealand Dollars</option>
      <option value="SEK" <?php print $sel11;?>>Swedish Kronor</option>
      <option value="USD" <?php print $sel12;?>>US Dollars</option>
      </select>
      
    </td>
  </tr>
  
  <tr>
    <td width="200" valign="top">Username</td>
    <td>
      <input type="text" class="vullen" name="storeId1" size="10" value="<?php print $storeId;?>">
    </td>
  </tr>
</table>
<br>



<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<?php // MoneyBookers?>
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#aMbz" name="<?php print A87;?> MONEYBOOKERS"><?php print A87;?></a> via <a href="http://www.moneybookers.com" target="_blank">MONEYBOOKERS</a></b>
    </td>
  </tr>
  <tr>
    <td colspan="2"></td>
    <td rowspan="8" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print ACT_MONEYB;?></td>
<?php
        if($mbPayment == "oui") {
            $checkedyes20nd = "checked";
            $checkedno20nd = "";
        } else {
          $checkedyes20nd = "";
          $checkedno20nd = "checked";
        }
?>
    <td width="200"> <?php print A26;?>
      <input type="radio" value="oui" name="mbPayment1" <?php print $checkedyes20nd;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="mbPayment1" <?php print $checkedno20nd;?> >
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">MoneyBookers <?php print ID_CLIENT;?></td>
    <td>
      <input type="text" class="vullen" name="mbId1" size="10" value="<?php print $mbId;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print "MoneyBookers e-mail";?></td>
    <td>
      <input type="text" class="vullen" name="mbEmail1" size="40" value="<?php print $mbEmail;?>">
    </td>
  </tr>
 <tr>
    <td width="200" valign="top">MoneyBookers valuta</td>
    <td>
<?php
if(!isset($mbDevise) OR $mbDevise=="") {$mbDevise = "EUR";}
if($mbDevise == "AUD") $AUD="selected"; else $AUD="";
if($mbDevise == "BGN") $BGN="selected"; else $BGN="";
if($mbDevise == "CAD") $CAD="selected"; else $CAD="";
if($mbDevise == "CHF") $CHF="selected"; else $CHF="";
if($mbDevise == "CZK") $CZK="selected"; else $CZK="";
if($mbDevise == "DKK") $DKK="selected"; else $DKK="";
if($mbDevise == "EEK") $EEK="selected"; else $EEK="";
if($mbDevise == "EUR") $EUR="selected"; else $EUR="";
if($mbDevise == "GBP") $GBP="selected"; else $GBP="";
if($mbDevise == "HKD") $HKD="selected"; else $HKD="";
if($mbDevise == "HUF") $HUF="selected"; else $HUF="";
if($mbDevise == "ILS") $ILS="selected"; else $ILS="";
if($mbDevise == "INR") $INR="selected"; else $INR="";
if($mbDevise == "ISK") $ISK="selected"; else $ISK="";
if($mbDevise == "HRK") $HRK="selected"; else $HRK="";
if($mbDevise == "JPY") $JPY="selected"; else $JPY="";
if($mbDevise == "KRW") $KRW="selected"; else $KRW="";
if($mbDevise == "LTL") $LTL="selected"; else $LTL="";
if($mbDevise == "LVL") $LVL="selected"; else $LVL="";
if($mbDevise == "MYR") $MYR="selected"; else $MYR="";
if($mbDevise == "NOK") $NOK="selected"; else $NOK="";
if($mbDevise == "NZD") $NZD="selected"; else $NZD="";
if($mbDevise == "PLN") $PLN="selected"; else $PLN="";
if($mbDevise == "RON") $RON="selected"; else $RON="";
if($mbDevise == "SEK") $SEK="selected"; else $SEK="";
if($mbDevise == "SGD") $SGD="selected"; else $SGD="";
if($mbDevise == "SKK") $SKK="selected"; else $SKK="";
if($mbDevise == "THB") $THB="selected"; else $THB="";
if($mbDevise == "TRY") $TRY="selected"; else $TRY="";
if($mbDevise == "TWD") $TWD="selected"; else $TWD="";
if($mbDevise == "USD") $USD="selected"; else $USD="";
if($mbDevise == "ZAR") $ZAR="selected"; else $ZAR="";
?>
          <select name="mbDevise1">
              <option value="AUD" <?php print $AUD;?>>Dollar Australien</option>
              <option value="BGN" <?php print $BGN;?>>Lev Bulgare Lev</option>
              <option value="CAD" <?php print $CAD;?>>Dollar Canadien</option>
              <option value="CHF" <?php print $CHF;?>>Franc Suisse</option>
              <option value="CZK" <?php print $CZK;?>>Couronne Tch�que</option>
              <option value="DKK" <?php print $DKK;?>>Couronne Danoise</option>
              <option value="EEK" <?php print $EEK;?>>Couronne Estonienne</option>
              <option value="EUR" <?php print $EUR;?>>Euro</option>
              <option value="GBP" <?php print $GBP;?>>Pound Sterling</option>
              <option value="HKD" <?php print $HKD;?>>HongKongDollar</option>
              <option value="HUF" <?php print $HUF;?>>Forint</option>
              <option value="ILS" <?php print $ILS;?>>Shekel</option>
              <option value="INR" <?php print $INR;?>>Rupee Indien</option>
              <option value="ISK" <?php print $ISK;?>>Couronne Islandaise</option>
              <option value="HRK" <?php print $HRK;?>>Kuna Croate</option>
              <option value="JPY" <?php print $JPY;?>>Yen</option>
              <option value="KRW" <?php print $KRW;?>>Won Cor�e du Sud</option>
              <option value="LTL" <?php print $LTL;?>>Litas lituanienne</option>
              <option value="LVL" <?php print $LVL;?>>Lat Latvien</option>
              <option value="MYR" <?php print $MYR;?>>Ringgit Malais</option>
              <option value="NOK" <?php print $NOK;?>>Couronne Norv�gienne</option>
              <option value="NZD" <?php print $NZD;?>>Dollar Nouvelle Z�lande</option>
              <option value="PLN" <?php print $PLN;?>>ZlotyPolonais</option>
              <option value="RON" <?php print $RON;?>>Nouveau Leu roumain</option>
              <option value="SEK" <?php print $SEK;?>>Couronne Su�doise</option>
              <option value="SGD" <?php print $SGD;?>>Dollar Singapour</option>
              <option value="SKK" <?php print $SKK;?>>Couronne Slovaque</option>
              <option value="THB" <?php print $THB;?>>Baht Tha�landais</option>
              <option value="TRY" <?php print $TRY;?>>Nouvelle Livre turque</option>
              <option value="TWD" <?php print $TWD;?>>Dollar New Taiwan</option>
              <option value="USD" <?php print $USD;?>>Dollar US</option>
              <option value="ZAR" <?php print $ZAR;?>>Rand Afrique du Sud</option>
          </select>
    </td>
    </tr>
  <tr>
    
<?php 
      if($_SESSION['lang']==1) $mb_lang="FR";
      if($_SESSION['lang']==2) $mb_lang="EN";
      if($_SESSION['lang']==3) $mb_lang="COM";
      print "<td colspan='2'>";
      print MB_VOUS_PERMET."<br>";
      print RATES." <a href='http://www.moneybookers.com/app/help.pl?s=m_fees&l=".$mb_lang."' target='_blank'>www.moneybookers.com</a>.";
      print "<br><img src='im/zzz.gif' width='1' height='10'><br>";

         // actiavtie moneybookers
         print "<table border='0' width='100%' cellpadding='0' cellspacing='0'><tr>";
         print "<td align='center'>";
         print "<a href='moneybookers_aanvraag.php'><img border='0' src='im/activ_mb_".$_SESSION['lang'].".gif'></a>";
         print "</td>";
         print "</tr></table>";
      // Mot secret
      print "</td></tr><tr>";
      print "<td width='200'>".SECRET_WORD."</td>";
      print "<td>";
      print '<input type="text" name="mbSecretWord1" size="15" class="vullen" maxlength="10" value="'.$mbSecretWord.'">';
      print '&nbsp;<a href="#mbTop" name="mbtop">'.LIRE_NOTE10.'</a>';
      print "</td>";
?>
  </tr>
</table>
<br>

<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">
<?php // Pay.nl?>
<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#paynederland" name="<?php print A87;?> PAYNL"><?php print A87;?></a> via <a href="http://www.pay.nl" target="_blank">PAY.NL</a></b>
    </td>
  </tr>
  <tr>
    <td colspan="2"></td>
    <td rowspan="8" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
  <tr>
    <td width="200" valign="top">Activeer Pay NL</td>
<?php
        if($paynlPayment == "yes") {
            $paynlcheckedyes20nd = "checked";
            $paynlcheckedno20nd = "";
        } else {
          $paynlcheckedyes20nd = "";
          $paynlcheckedno20nd = "checked";
        }
?>
    <td width="200"> <?php print A26;?>
      <input type="radio" value="yes" name="paynlPayment1" <?php print $paynlcheckedyes20nd;?> >
      <?php print A27;?>
      <input type="radio" value="no" name="paynlPayment1" <?php print $paynlcheckedno20nd;?> >
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">Program ID</td>
    <td>
      <input type="text" class="vullen" name="paynlProgramId1" size="10" value="<?php print $paynlProgramId;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">Website ID</td>
    <td>
      <input type="text" class="vullen" name="paynlWebsiteId1" size="40" value="<?php print $paynlWebsiteId;?>">
    </td>
  </tr>
  <tr>
    <td width="200" valign="top">Website Location ID</td>
    <td>
      <input type="text" class="vullen" name="paynlWebsiteLocationId1" size="40" value="<?php print $paynlWebsiteLocationId;?>">
    </td>
  </tr>
 
</table>
<br>

<?php // betalingen uitschakelen ?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE">

<tr>
    <td class="boxtitleGreen" colspan="3">
    <a href="#zultop" name="zultop"><img src="im/up.gif" border="0" align="absmiddle"></a>&nbsp;
    <b><a href="#desactivez" name="<?php print DESACTIVE_PAYMENTS;?>"><?php print DESACTIVE_PAYMENTS;?></a>
    </td>
  </tr>
  <tr>
    <td width="200" valign="top"><?php print DESACTIVE_PAYMENTS_ALL;?></td>
    <?php
        if($paymentsDesactive == "oui") {
            $checkedyes20ns7 = "checked";
            $checkedno20ns7 = "";
        } else {
          $checkedyes20ns7 = "";
          $checkedno20ns7 = "checked";
        }
?>
    <td> <?php print A26;?>
      <input type="radio" value="oui" name="paymentsDesactive1" <?php print $checkedyes20ns7;?> >
      <?php print A27;?>
      <input type="radio" value="non" name="paymentsDesactive1" <?php print $checkedno20ns7;?> >
    </td>
    <td rowspan="1" valign="top" align="right" class="boxtitle3"><input type="submit" value="<?php print A96;?>" class="knop" name="submit"></td>
  </tr>
<tr>
<td colspan="3">
    <table align="center" width="100%" border="0" cellspacing="0" cellpadding="15" class="boxtitle3">
    <tr>
    <td align="center"><input type="submit" class="knop" value="<?php print A96;?>" name="submit"></td>
    </tr>
    </table>
</td>
  </tr>  
</table>
<SCRIPT type="text/javascript">cp.writeDiv()</SCRIPT>
<br>

<table align="center" width="600" border="0" cellpadding="5" cellspacing="0">
<tr>
<td colspan="2">
<?php print A97;?>
        </td>
        </tr>
        <tr>
        <td>
<?php print A98;?>
        </td>
        </tr>
        <tr>
        <td>
<?php print A99;?>
        </td>
        </tr>
        <tr>
        <td>
<?php print A100;?>
        </td>
        </tr>
        <tr>
        <td>
<?php print A101;?>
        </td>
        </tr>
        <tr>
        </tr>
        <tr>
        <td>
<?php print A220;?>
        </td>
        </tr>
        <tr>
        <td>
<?php print NOTE9;?>
        </td>
        </tr>
        <tr>
        <td>
        <a href='#mbtop' name='mbTop'></a>
<?php print NOTE10;?>
        </td>
        </tr>
</table>


<?php 
} else if(isset($_GET['action']) AND $_GET['action'] == "write") {
include('../configuratie/configuratie.php');

// ------------
// Upload logo 
// ------------
if(isset($_FILES["uploadLogo"]["name"]) AND !empty($_FILES["uploadLogo"]["name"])) {
	//nom du fichier choisi:
	$nomFichier    = $_FILES["uploadLogo"]["name"];
	//nom temporaire sur le serveur:
	$nomTemporaire = $_FILES["uploadLogo"]["tmp_name"];
	//type du fichier choisi:
	$typeFichier   = $_FILES["uploadLogo"]["type"];
	//poids en octets du fichier choisit:
	$poidsFichier  = $_FILES["uploadLogo"]["size"];
	//code de l'erreur si jamais il y en a une:
	$codeErreur    = $_FILES["uploadLogo"]["error"];
	//chemin qui m�ne au dossier qui va contenir les fichiers uplaod:
	$chemin = "../im/";
	if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$_POST['logo2'] = $nomFichier;
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_LOGO." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_REUSSI.".</b></div><br>";
		}
		else {
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_LOGO." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
		}
	}
	else {
		print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_LOGO." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
	}
}

// --------------
// Upload bandeau
// --------------
if(isset($_FILES["uploadBandeau"]["name"]) AND !empty($_FILES["uploadBandeau"]["name"])) {
	//nom du fichier choisi:
	$nomFichier    = $_FILES["uploadBandeau"]["name"];
	//nom temporaire sur le serveur:
	$nomTemporaire = $_FILES["uploadBandeau"]["tmp_name"];
	//type du fichier choisi:
	$typeFichier   = $_FILES["uploadBandeau"]["type"];
	//poids en octets du fichier choisit:
	$poidsFichier  = $_FILES["uploadBandeau"]["size"];
	//code de l'erreur si jamais il y en a une:
	$codeErreur    = $_FILES["uploadBandeau"]["error"];
	//chemin qui m�ne au dossier qui va contenir les fichiers uplaod:
	$chemin = "../im/";
	if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$_POST['logo22'] = $nomFichier;
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_BANDEAU." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_REUSSI.".</b></div><br>";
		}
		else {
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_BANDEAU." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
		}
	}
	else {
		print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_BANDEAU." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
	}
}

// -------------
// Upload Header
// -------------
if(isset($_FILES["uploadHeader"]["name"]) AND !empty($_FILES["uploadHeader"]["name"])) {
	//nom du fichier choisi:
	$nomFichier    = $_FILES["uploadHeader"]["name"];
	//nom temporaire sur le serveur:
	$nomTemporaire = $_FILES["uploadHeader"]["tmp_name"];
	//type du fichier choisi:
	$typeFichier   = $_FILES["uploadHeader"]["type"];
	//poids en octets du fichier choisit:
	$poidsFichier  = $_FILES["uploadHeader"]["size"];
	//code de l'erreur si jamais il y en a une:
	$codeErreur    = $_FILES["uploadHeader"]["error"];
	//chemin qui m�ne au dossier qui va contenir les fichiers uplaod:
	$chemin = "../im/background/";
	if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$_POST['backgroundImageHeader1'] = $nomFichier;
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_REUSSI.".</b></div><br>";
		}
		else {
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
		}
	}
	else {
		print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
	}
}

// ---------------
// Upload Header 2
// ---------------
if(isset($_FILES["uploadHeader2"]["name"]) AND !empty($_FILES["uploadHeader2"]["name"])) {
	//nom du fichier choisi:
	$nomFichier    = $_FILES["uploadHeader2"]["name"];
	//nom temporaire sur le serveur:
	$nomTemporaire = $_FILES["uploadHeader2"]["tmp_name"];
	//type du fichier choisi:
	$typeFichier   = $_FILES["uploadHeader2"]["type"];
	//poids en octets du fichier choisit:
	$poidsFichier  = $_FILES["uploadHeader2"]["size"];
	//code de l'erreur si jamais il y en a une:
	$codeErreur    = $_FILES["uploadHeader2"]["error"];
	//chemin qui m�ne au dossier qui va contenir les fichiers uplaod:
	$chemin = "../im/banner/";
	// banner/inov3.png
	if(preg_match("#.jpg$|.gif$|.png$#", $nomFichier)) {
		if(copy($nomTemporaire, $chemin.$nomFichier)) {
			$_POST['header2Image1'] = $nomFichier;
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_REUSSI.".</b></div><br>";
		}
		else {
			print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
		}
	}
	else {
		print "<div align='center' style='color:#FF0000'><b>".UPLOAD_DE_HEADER." <span style='font-size:12px; background-color:#FFFF00; padding:2px'>".$nomFichier."</span> ".A_ECHOUE."</b></div><br>";
	}
}

// Set TVA_confirm=no if TVA_confim=yes
if(isset($_POST['tvaManuelValidation1']) AND $_POST['tvaManuelValidation1']=="non") {
    mysql_query("UPDATE users_pro SET users_pro_tva_confirm='??' WHERE users_pro_tva_confirm ='yes'");
}

if($_POST['www1']=="") {$wwwZ="";} else {$wwwZ = $_POST['www1'].".";}
// MoneyBookers Secret Word check
if($_POST['mbSecretWord1']!=="" AND $_POST['mbEmail1']!=="" AND $_POST['mbId1']!=="" AND $_POST['mbSecretWord1']!==$mbSecretWord) {
$currentUrl = $_SERVER['SERVER_NAME'];

if(!preg_match("/localhost/i", $currentUrl) AND !preg_match("/127.0.0.1/i", $currentUrl)) {
$secretWordMerchant = strtolower(md5($_POST['mbSecretWord1']));
$secretWordPlatform = "f71dbe52628a3f83a77ab494817525c6";
$combineSecretWords = md5($secretWordMerchant.$secretWordPlatform);
$mbFileName = "https://www.moneybookers.com/app/secret_word_check.pl?email=".$_POST['mbEmail1']."&secret=".$combineSecretWords."&cust_id=3286296";
$mb_result = @file_get_contents($mbFileName);


  if($mb_result!=="OK") {
      $messageMB = $_POST['mbSecretWord1'].": ".SECRET_WORD." ".NON_VALIDE."!";
      print "<table border='0' align='center' cellpadding='10' cellspacing='0' class='TABLE2'><tr><td align='center'>";
      print "<div align='center' style='color:#FF0000; FONT-SIZE: 14px;'><b>".$messageMB."</b></div>";
      print "</td></tr></table><br>";
      print "<div align='center' style='color:#F1F1F1; FONT-SIZE:8px;'>".$mb_result."</div>";
      $_POST['mbSecretWord1']="";
  }
}
else {
      print "<table border='0' align='center' cellpadding='10' cellspacing='0' class='TABLE2'><tr><td align='center'>";
      print "<div align='center' style='color:#FF0000;'><b>".SECRET_WORD_ONLINE."</b></div>";
      print "</td></tr></table><br>";
      $_POST['mbSecretWord1']="";
}
}

// Mise � jour affili�s
mysql_query("UPDATE affiliation SET aff_com ='".$_POST['affiliateCom1']."' WHERE aff_com='".$affiliateCom."'");

// Set ISO
/*
$requestCountryZ = mysql_query("SELECT iso FROM countries WHERE countries_name = '".$_POST['address_country1']."'");
$countriesZ = mysql_fetch_array($requestCountryZ);
$_POST['iso'] = $countriesZ['iso'];
*/

// Store width
if($_POST['storeWidth1']=='') {$large="790";} else {$large=$_POST['storeWidth1'];}
$aaaaaa = $large.$_POST['boutiqueWidth'];

// item default sort
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="1") $defaultOrder = "Ref";
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="20") $defaultOrder = "Article";
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="3") $defaultOrder = "Prix";
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="4") $defaultOrder = "Compagnie";
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="5") $defaultOrder = "Les_plus_populaires";
if(isset($_POST["orderByDefault"]) AND $_POST["orderByDefault"]=="0") $defaultOrder = "Id";

// logo
if(empty($_POST['logo2']) OR $_POST['logo2']=="") {
   $_POST['logo2']="noPath";
}
else {
      $endExtLogo = substr($_POST['logo2'],-4);
      if(strlen($_POST['logo2']) > 4 AND ($endExtLogo==".gif" OR $endExtLogo==".jpg" OR $endExtLogo==".swf" OR $endExtLogo==".png")) {
         $_POST['logo2'] = "im/".$_POST['logo2'];
      }
      else {
        $_POST['logo2']="noPath";
      }
}
// bandeau
if(empty($_POST['logo22']) OR $_POST['logo22']=="") {
   $_POST['logo22'] = "noPath";
}
else {
      $endExtBandeau = substr($_POST['logo22'],-4);
      if(strlen($_POST['logo22']) > 4 AND ($endExtBandeau==".gif" OR $endExtBandeau==".jpg" OR $endExtBandeau==".swf" OR $endExtBandeau==".png")) {
         
         $_POST['logo22'] = "im/".$_POST['logo22'];
      }
      else {
        $_POST['logo22'] = "noPath";
      }
}
// no logo
if($_POST['logo2'] == "noPath") {
   $_POST['logo2'] = "noPath";
}
// no bandeau
if($_POST['logo22'] == "noPath") {
   $_POST['logo22'] = "noPath";
}

if($_POST['logo2']!=="noPath" AND $_POST['logo22']!=="noPath") {
   $_POST['logo2'] = "noPath";
   $_POST['logo22'] = "noPath";
}
// no header background image 
if(empty($_POST['backgroundImageHeader1']) OR $_POST['backgroundImageHeader1']=="") {
   $_POST['backgroundImageHeader1'] = "noPath";
}
else {
    $endExt = substr($_POST['backgroundImageHeader1'],-4);
    if(strlen($_POST['backgroundImageHeader1']) > 4 AND ($endExt==".gif" OR $endExt==".jpg" OR $endExt==".png" OR $endExt==".swf")) {
            $_POST['backgroundImageHeader1'] = $_POST['backgroundImageHeader1'];
        }
        else {
            $_POST['backgroundImageHeader1'] = "noPath";
        }
}
// Minimum order
if($_POST['minimumOrder1']=='') {$_POST['minimumOrder1'] = 0;}

if((isset($_POST['lang1']) and $_POST['lang1'] == "Changer devise") or (isset($_POST['lang1']) and $_POST['lang1'] == "----")) $_POST['lang1'] = $_POST['lang'];

if($_POST['zone1']=="oui") $_POST['zone1'] = "zone1"; else $_POST['zone1'] = "";
if($_POST['zone2']=="oui") $_POST['zone2'] = "zone2"; else $_POST['zone2'] = "";
if($_POST['zone3']=="oui") $_POST['zone3'] = "zone3"; else $_POST['zone3'] = "";
if($_POST['zone4']=="oui") $_POST['zone4'] = "zone4"; else $_POST['zone4'] = "";
if($_POST['zone5']=="oui") $_POST['zone5'] = "zone5"; else $_POST['zone5'] = "";
if($_POST['zone6']=="oui") $_POST['zone6'] = "zone6"; else $_POST['zone6'] = "";
if($_POST['zone7']=="oui") $_POST['zone7'] = "zone7"; else $_POST['zone7'] = "";
if($_POST['zone8']=="oui") $_POST['zone8'] = "zone8"; else $_POST['zone8'] = "";
if($_POST['zone9']=="oui") $_POST['zone9'] = "zone9"; else $_POST['zone9'] = "";
if($_POST['zone10']=="oui") $_POST['zone10'] = "zone10"; else $_POST['zone10'] = "";
if($_POST['zone11']=="oui") $_POST['zone11'] = "zone11"; else $_POST['zone11'] = "";
if($_POST['zone12']=="oui") $_POST['zone12'] = "zone12"; else $_POST['zone12'] = "";

$zones = $_POST['zone1']."|".$_POST['zone2']."|".$_POST['zone3']."|".$_POST['zone4']."|".$_POST['zone5']."|".$_POST['zone6']."|".$_POST['zone7']."|".$_POST['zone8']."|".$_POST['zone9']."|".$_POST['zone10']."|".$_POST['zone11']."|".$_POST['zone12'];
mysql_query("UPDATE admin SET free_shipping_zone = '".$zones."'");


mysql_query("UPDATE admin SET interface = '".$_POST['colorInter1']."'");

$_POST['textHome']  = str_replace("\'","&#146;",$_POST['textHome']);
$_POST['textHome_2']  = str_replace("\'","&#146;",$_POST['textHome_2']);
$_POST['textHome_3']  = str_replace("\'","&#146;",$_POST['textHome_3']);

	mysql_query("UPDATE admin SET text_home_1 = '".$_POST['textHome']."',
								  text_home_2 = '".$_POST['textHome_2']."',
								  text_home_3 = '".$_POST['textHome_3']."'
								  ");

$_POST['textCategories']  = str_replace("\'","&#146;",$_POST['textCategories']);
$_POST['textCategories_2']  = str_replace("\'","&#146;",$_POST['textCategories_2']);
$_POST['textCategories_3']  = str_replace("\'","&#146;",$_POST['textCategories_3']);
	mysql_query("UPDATE admin SET text_categories_1 = '".$_POST['textCategories']."',
								  text_categories_2 = '".$_POST['textCategories_2']."',
								  text_categories_3 = '".$_POST['textCategories_3']."'
								  ");

$_POST['textList']  = str_replace("\'","&#146;",$_POST['textList']);
$_POST['textList_2']  = str_replace("\'","&#146;",$_POST['textList_2']);
$_POST['textList_3']  = str_replace("\'","&#146;",$_POST['textList_3']);
	mysql_query("UPDATE admin SET text_list_1 = '".$_POST['textList']."',
								  text_list_2 = '".$_POST['textList_2']."',
								  text_list_3 = '".$_POST['textList_3']."'
								  ");

// Insert Infos Virement bancaires into bdd
mysql_query("UPDATE admin SET 
				banqueNom ='".$_POST['nomBanque']."',
				banqueAdresse ='".$_POST['adresseBanque']."',
				banqueNumeroCompte ='".$_POST['numeroBanque']."',
				banqueTitulaireCompte ='".$_POST['titulaireBanque']."',
				banqueCodeSwift ='".$_POST['swiftBanque']."',
				banqueIban ='".$_POST['ibanBanque']."',
				banqueRib ='".$_POST['rib']."'
			");
// Insert fermeture boutique into bdd.
mysql_query("UPDATE admin SET shop_closed ='".$_POST['storeClosedMessage1']."'");

// bdd update
for(reset ($_POST['order_id']); $key = key ($_POST['order_id']); next ($_POST['order_id'])) {
     mysql_query("UPDATE ordered SET order_view ='".$_POST['order_id'][$key]."' WHERE order_id = '".$key."'");
}
// configuratie.php update
     $ordereda = mysql_query("SELECT * FROM ordered");
     $ordNuma = mysql_num_rows($ordereda);
     if($ordNuma > 0) {
	     $titre_order_1a="";
	     $titre_order_2a="";
	     $titre_order_3a="";
	     while($proda = mysql_fetch_array($ordereda)) {
			if($proda['order_view'] == 'yes') {
			     $titre_order_1a .= $proda['order_1']."|";
			     $titre_order_2a .= $proda['order_2']."|";
			     $titre_order_3a .= $proda['order_3']."|";
			}
	     }
    }
// langues site
if(isset($_SESSION['langDispoZ'])) unset($_SESSION['langDispoZ']);
// Mettre � jour nom des langues
for($z=0; $z<$_POST['lanNum']; $z++) {
$update = mysql_query("UPDATE languages SET name='".$_POST['langueName'][$z]."' WHERE languages_id='".$_POST['lanId'][$z]."'");
}
// Set langue par defaut
if(!isset($_POST['langue1']) OR empty($_POST['langue1'])) $_POST['langue1']="1";
$update = mysql_query("UPDATE languages SET defaut='no'");
$update = mysql_query("UPDATE languages SET defaut='yes' WHERE code='".$_POST['langue1']."'");
// set langue visible
if(empty($idiomaNum)) {$_POST['idioma'][2]="yes";}
for($z=0; $z<$_POST['lanNum']; $z++) {
$update = mysql_query("UPDATE languages
                       SET visible='".$_POST['idioma'][$z]."'
                       WHERE name='".$_POST['lan'][$z]."'");
  }

if($_POST['topVisible1'] == 'non' and 
	$_POST['promoVisible1'] == 'non' and 
	$_POST['nouvVisible1'] == 'non' and 
	$_POST['information1'] == 'non' and 
	$_POST['langVisible1']=="non" and 
	$_POST['interfaceVisible1']=="non") {
		$_POST['larg1'] = 0;
	}
	else { 
	$_POST['larg1'] = $_POST['larg_rub1'];
	}
// find root
	if($_POST['folder1']!=='') {
		$urlRoot = trim($_POST['domaine1'])."/".trim($_POST['folder1']);
	}
	else {
		$urlRoot = trim($_POST['domaine1']);
	}

    // URL activation newsletter
    $urlNewsletterZ = "http://".$wwwZ.$urlRoot."/nieuwsbrief_systeem.php";
    // URL page retour succes paiement paypal
    $paypalReturnZ = "http://".$wwwZ.$urlRoot."/ok.php";
    // URL interface de suivi client
    $urlAdminClientZ = "http://".$wwwZ.$urlRoot."/klantlogin/index.php";

// couleur converter
function hex2rgb($hex) {
  //if(!ereg("[0-9a-fA-F]{6}", $hex)) {
  if(!@preg_match("/^[a-f0-9]{1,}$/is", $hex)) {
    $rgb[0]= 255;
    $rgb[1]= 255;
    $rgb[2]= 255;
    $rgb[3]= 0;
    return $rgb; 
  }
  
  for($i=0; $i<3; $i++) { 
  $temp = substr($hex, 2*$i, 2); 
  $rgb[$i] = 16 * hexdec(substr($temp, 0, 1)) + 
  hexdec(substr($temp, 1, 1)); 
  } 
  return $rgb; 
}

if(isset($_POST['backGdColor1']) AND $_POST['backGdColor1']!=='') {
    $_POST['backGdColor1'] = str_replace('#','',$_POST['backGdColor1']);
    $_a = hex2rgb($_POST['backGdColor1']);
    if(isset($_a[3]) AND $_a[3]==0) {
        $backGdColorAdmin = "FFFFFF";
        $backGdColor = "255,255,255";
    }
    else {
        $backGdColorAdmin = $_POST['backGdColor1'];
        $backGdColor = implode($_a,",");
    }
}
else {
    $backGdColorAdmin = "FFFFFF";
    $backGdColor = "255,255,255";
}

if(isset($_POST['backGdColorListLine1']) AND $_POST['backGdColorListLine1']!=='') {
    $_POST['backGdColorListLine1'] = str_replace('#','',$_POST['backGdColorListLine1']);
    $_aZ = hex2rgb($_POST['backGdColorListLine1']);
    if(isset($_aZ[3]) AND $_aZ[3]==0) {
        $backGdColorListLine = "";
    }
    else {
        $backGdColorListLine = $_POST['backGdColorListLine1'];
    }
}
else {
    $backGdColorListLine = "";
}

if(isset($_POST['backGdColorCatLine1']) AND $_POST['backGdColorCatLine1']!=='') {
    $_POST['backGdColorCatLine1'] = str_replace('#','',$_POST['backGdColorCatLine1']);
    $_aZ = hex2rgb($_POST['backGdColorCatLine1']);
    if(isset($_aZ[3]) AND $_aZ[3]==0) {
        $backGdColorCatLine = "";
    }
    else {
        $backGdColorCatLine = $_POST['backGdColorCatLine1'];
    }
}
else {
    $backGdColorCatLine = "";
}

if(isset($_POST['catDisplayPromo1'])) $_POST['catDisplayPromo1']="on"; else $_POST['catDisplayPromo1']="off";
if(isset($_POST['catDisplayNews1'])) $_POST['catDisplayNews1']="on"; else $_POST['catDisplayNews1']="off";
if(isset($_POST['catDisplayBest1'])) $_POST['catDisplayBest1']="on"; else $_POST['catDisplayBest1']="off";
if(isset($_POST['catDisplayFew1'])) $_POST['catDisplayFew1']="on"; else $_POST['catDisplayFew1']="off";
if(isset($_POST['catDisplayExc1'])) $_POST['catDisplayExc1']="on"; else $_POST['catDisplayExc1']="off";
if(isset($_POST['catDisplayRandOne1'])) $_POST['catDisplayRandOne1']="on"; else $_POST['catDisplayRandOne1']="off";
if(isset($_POST['catDisplayRandAll1'])) $_POST['catDisplayRandAll1']="on"; else $_POST['catDisplayRandAll1']="off";

// fonction suppression/remplacer apostrophe
function removeAp($value) {
    $valueNew = str_replace("\'","&#146;",$value);
    $valueNew = str_replace("'","&#146;",$valueNew);
    return $valueNew;
}

$_POST['address_street1'] = str_replace("\'","&#146;",$_POST['address_street1']);
$_POST['address_city1'] = str_replace("\'","&#146;",$_POST['address_city1']);
$_POST['address_autre1'] = str_replace("\'","&#146;",$_POST['address_autre1']);

// Affichage de la remise articles et livraison
if($_POST['activerRemise1']=="non") $_POST['displayPromoRemise1']="non";
if($_POST['activerPromoLivraison1']=="non") $_POST['displayPromoShipping1']="non";


  $config="<?php\n";
  $config.="include('mysql_connect.php');\n";
  $config.="\$domaine=\"".trim($_POST['domaine1'])."\";\n";
  $config.="\$folder=\"".$_POST['folder1']."\";\n";
  $config.="\$domaineFull=\"".$urlRoot."\";\n";
  $config.="\$keywords=\"".strip_tags(removeAp($_POST['keywords1']))."\";\n";
  $config.="\$description=\"".strip_tags(removeAp($_POST['description1']))."\";\n";
  $config.="\$auteur=\"".strip_tags(removeAp($_POST['auteur1']))."\";\n";
  $config.="\$store_name=\"".removeAp(str_replace('&','&amp;',$_POST['store_name1']))."\";\n";
  $config.="\$tel=\"".$_POST['tel1']."\";\n";
  $config.="\$fax=\"".$_POST['fax1']."\";\n";
  $config.="\$address_street=\"".removeAp($_POST['address_street1'])."\";\n";
  $config.="\$address_cp=\"".$_POST['address_cp1']."\";\n";
  $config.="\$address_city=\"".removeAp($_POST['address_city1'])."\";\n";
  $config.="\$address_country=\"".removeAp($_POST['address_country1'])."\";\n";
  $config.="\$address_autre=\"".removeAp($_POST['address_autre1'])."\";\n";  
  $config.="\$mailWebmaster=\"".$_POST['mailWebmaster1']."\";\n";
  $config.="\$mailInfo=\"".$_POST['mailInfo1']."\";\n";
  $config.="\$mailPerso=\"".$_POST['mailPerso1']."\";\n";
  $config.="\$mailOrder=\"".$_POST['mailOrder1']."\";\n";
  if($_POST['nbre_promo1']=='' OR !is_numeric($_POST['nbre_promo1'])) {$config.="\$nbre_promo=1;\n";} else {$config.="\$nbre_promo=".abs($_POST['nbre_promo1']).";\n";}
  $config.="\$promoVisible=\"".$_POST['promoVisible1']."\";\n";
  $config.="\$menuRapideVisible=\"".$_POST['menuRapideVisible1']."\";\n";
  $config.="\$topVisible=\"".$_POST['topVisible1']."\";\n";
  if($_POST['nbre_nouv1']=='' OR !is_numeric($_POST['nbre_nouv1'])) {$config.="\$nbre_nouv=1;\n";} else { $config.="\$nbre_nouv=".abs($_POST['nbre_nouv1']).";\n";}
  $config.="\$nouvVisible=\"".$_POST['nouvVisible1']."\";\n";
  if($_POST['nbre_jour_nouv1']!=='' AND is_numeric($_POST['nbre_jour_nouv1'])) { $config.="\$nbre_jour_nouv=".abs($_POST['nbre_jour_nouv1']).";\n";} else {$config.="\$nbre_jour_nouv=1;\n";}
  
  if($_POST['taxe1']=='' OR !is_numeric($_POST['taxe1'])) {$config.="\$taxe=0;\n";} else {$config.="\$taxe=".abs($_POST['taxe1']).";\n";}
    
  $config.="\$taxeName=\"".$_POST['taxeName1']."\";\n";
  $config.="\$taxePosition=\"".$_POST['taxePosition1']."\";\n";

  if($_POST['livraisonComprise1']!=='' AND is_numeric($_POST['livraisonComprise1'])) { $config.="\$livraisonComprise=".abs($_POST['livraisonComprise1']).";\n";} else {$config.="\$livraisonComprise=0;\n";}
  $config.="\$activerPromoLivraison=\"".$_POST['activerPromoLivraison1']."\";\n";
  $config.="\$displayPromoShipping=\"".$_POST['displayPromoShipping1']."\";\n";
  if($_POST['nbre_ligne1']!=='' AND is_numeric($_POST['nbre_ligne1'])) { $config.="\$nbre_ligne=".abs($_POST['nbre_ligne1']).";\n";} else {$config.="\$nbre_ligne=5;\n";}
  if($_POST['haut_im1']!=='' AND is_numeric($_POST['haut_im1'])) { $config.="\$haut_im=".abs($_POST['haut_im1']).";\n";} else {$config.="\$haut_im=45;\n";}
  $config.="\$titre_order_1=\"".$titre_order_1a."\";\n";
  $config.="\$titre_order_2=\"".$titre_order_2a."\";\n";
  $config.="\$titre_order_3=\"".$titre_order_3a."\";\n";
  if($_POST['langue1']!=='' AND is_numeric($_POST['langue1'])) {$config.="\$langue=\"".abs($_POST['langue1'])."\";\n";} else {$config.="\$langue=\"1\";\n";}
  $config.="\$information=\"".$_POST['information1']."\";\n";
  if($_POST['devise1']!=='') { $config.="\$devise=\"".$_POST['devise1']."\";\n";} else {$config.="\$devise=\"Euro\";\n";}
  if($_POST['symbolDevise1']!=='') { $config.="\$symbolDevise=\"".$_POST['symbolDevise1']."\";\n";} else {$config.="\$symbolDevise=\"�\";\n";}
  $config.="\$converter=\"".$_POST['converter1']."\";\n";
  if($_POST['NbreProduitAffiche1']!=='' AND is_numeric($_POST['NbreProduitAffiche1'])) { $config.="\$NbreProduitAffiche=".abs($_POST['NbreProduitAffiche1']).";\n";} else {$config.="\$NbreProduitAffiche=0;\n";}
  if($_POST['NbreProduitAfficheCatalog1']!=='' AND is_numeric($_POST['NbreProduitAfficheCatalog1'])) { $config.="\$NbreProduitAfficheCatalog=".abs($_POST['NbreProduitAfficheCatalog1']).";\n";} else {$config.="\$NbreProduitAfficheCatalog=0;\n";}
  if($_POST['imageSizeCatalog1']!=='' AND is_numeric($_POST['imageSizeCatalog1'])) { $config.="\$imageSizeCatalog=".abs($_POST['imageSizeCatalog1']).";\n";} else {$config.="\$imageSizeCatalog=75;\n";}
  if($_POST['nbre_col1']!=='' AND is_numeric($_POST['nbre_col1'])) { $config.="\$nbre_col=".abs($_POST['nbre_col1']).";\n";} else {$config.="\$nbre_col=3;\n";}
  if($_POST['nbre_col_catalog1']!=='' AND is_numeric($_POST['nbre_col_catalog1'])) { $config.="\$nbre_col_catalog=".abs($_POST['nbre_col_catalog1']).";\n";} else {$config.="\$nbre_col_catalog=2;\n";}
  
  if($_POST['ImageSizeDesc1']!=='' AND is_numeric($_POST['ImageSizeDesc1'])) { $config.="\$ImageSizeDesc=".abs($_POST['ImageSizeDesc1']).";\n";} else {$config.="\$ImageSizeDesc=150;\n";}
  if($_POST['SecImageSizeDesc1']!=='' AND is_numeric($_POST['SecImageSizeDesc1'])) { $config.="\$SecImageSizeDesc=".abs($_POST['SecImageSizeDesc1']).";\n";} else {$config.="\$SecImageSizeDesc=50;\n";}
  if($_POST['SecImageWidthDesc1']!=='' AND is_numeric($_POST['SecImageWidthDesc1'])) { $config.="\$SecImageWidthDesc=".abs($_POST['SecImageWidthDesc1']).";\n"; } else {$config.="\$SecImageWidthDesc=100;\n";}
  
  $config.="\$addImageCart=\"".$_POST['addImageCart1']."\";\n";
  if($_POST['ImageSizeCart1']!=='' AND is_numeric($_POST['ImageSizeCart1'])) { $config.="\$ImageSizeCart=".abs($_POST['ImageSizeCart1']).";\n";} else {$config.="\$ImageSizeCart=35;\n";}
    
  if($_POST['imageSizeCat1']!=='' AND is_numeric($_POST['imageSizeCat1'])) { $config.="\$imageSizeCat=".abs($_POST['imageSizeCat1']).";\n";} else {$config.="\$imageSizeCat=75;\n";}
  $config.="\$langVisible=\"".$_POST['langVisible1']."\";\n";
  $config.="\$colorInter=\"".$_POST['colorInter1']."\";\n";
  $config.="\$addCommuniqueHome=\"".$_POST['addCommuniqueHome1']."\";\n";
  $config.="\$addCommuniqueCategories=\"".$_POST['addCommuniqueCategories1']."\";\n";
  $config.="\$addCommuniqueList=\"".$_POST['addCommuniqueList1']."\";\n";
  if($_POST['remiseOrderMax1']!=='' AND is_numeric($_POST['remiseOrderMax1'])) { $config.="\$remiseOrderMax=".abs($_POST['remiseOrderMax1']).";\n";} else {$config.="\$remiseOrderMax=0;\n";}
  if($_POST['remise1']!=='' AND is_numeric($_POST['remise1'])) { $config.="\$remise=".abs($_POST['remise1']).";\n";} else {$config.="\$remise=0;\n";}
  $config.="\$remiseType=\"".$_POST['remiseType1']."\";\n";
  $config.="\$activerRemise=\"".$_POST['activerRemise1']."\";\n";
  $config.="\$displayPromoRemise=\"".$_POST['displayPromoRemise1']."\";\n";
  $config.="\$interfaceVisible=\"".$_POST['interfaceVisible1']."\";\n";
  $config.="\$moteurVisible=\"".$_POST['moteurVisible1']."\";\n";
  $config.="\$menuNavVisible=\"".$_POST['menuNavVisible1']."\";\n";
  if($_POST['moteurLigneNum1']!=='' AND is_numeric($_POST['moteurLigneNum1'])) { $config.="\$moteurLigneNum=".abs($_POST['moteurLigneNum1']).";\n";} else {$config.="\$moteurLigneNum=7;\n";}
  $config.="\$activerCouleurPerso=\"".$_POST['activerCouleurPerso1']."\";\n";
  $config.="\$bannerVisible=\"".$_POST['bannerVisible1']."\";\n";
  $config.="\$newsletterVisible=\"".$_POST['newsletterVisible1']."\";\n";
  $config.="\$urlNewsletter=\"".$urlNewsletterZ."\";\n";
  if($_POST['seuilStock1']!=='' AND is_numeric($_POST['seuilStock1'])) { $config.="\$seuilStock=".abs($_POST['seuilStock1']).";\n";} else {$config.="\$seuilStock=10;\n";}
  $config.="\$paypalEmail=\"".$_POST['paypalEmail1']."\";\n";  
  $config.="\$paypalReturn=\"".$paypalReturnZ."\";\n";    
  $config.="\$co=\"".$_POST['co1']."\";\n";  
  $config.="\$storeId=\"".$_POST['storeId1']."\";\n";
  $config.="\$paypalDevise=\"".strtoupper($_POST['paypalDevise1'])."\";\n";
  $config.="\$paypalPayment=\"".$_POST['paypalPayment1']."\";\n";
  $config.="\$myKey=\"".strtoupper($_POST['myKey1'])."\";\n";
  $config.="\$EuroWebPayment=\"".$_POST['EuroWebPayment1']."\";\n";
  $config.="\$urlAdminClient=\"".$urlAdminClientZ."\";\n";
  $config.="\$addSubMenu=\"".$_POST['addSubMenu1']."\";\n";
  $config.="\$logo=\"".$_POST['logo2']."\";\n";
  if($_POST['larg1']!=='' AND is_numeric($_POST['larg1'])) { $config.="\$larg=".abs($_POST['larg1']).";\n";} else {$config.="\$larg=140;\n";}
  $config.="\$coDefaultDevise=\"".$_POST['coDefaultDevise1']."\";\n";
  $config.="\$displayRelated=\"".$_POST['displayRelated1']."\";\n";
  $config.="\$activerContre=\"".$_POST['activerContre1']."\";\n";
  if($_POST['seuilContre1']!=='' AND is_numeric($_POST['seuilContre1'])) { $config.="\$seuilContre=".abs($_POST['seuilContre1']).";\n";} else {$config.="\$seuilContre=15;\n";}
  if($_POST['larg_rub1']!=='' AND is_numeric($_POST['larg_rub1'])) { $config.="\$larg_rub=".abs($_POST['larg_rub1']).";\n";} else {$config.="\$larg_rub=140;\n";}
  if($_POST['hautImageMaxPromo1']!=='' AND is_numeric($_POST['hautImageMaxPromo1'])) { $config.="\$hautImageMaxPromo=".abs($_POST['hautImageMaxPromo1']).";\n";} else {$config.="\$hautImageMaxPromo=50;\n";}
  if($_POST['hautImageMaxNews1']!=='' AND is_numeric($_POST['hautImageMaxNews1'])) { $config.="\$hautImageMaxNews=".abs($_POST['hautImageMaxNews1']).";\n";} else {$config.="\$hautImageMaxNews=50;\n";}
  if($_POST['largTableCatalog1']!=='' AND is_numeric($_POST['largTableCatalog1'])) { $config.="\$largTableCatalog=".abs($_POST['largTableCatalog1']).";\n";} else {$config.="\$largTableCatalog=135;\n";}
  if($_POST['largTableCategories1']!=='' AND is_numeric($_POST['largTableCategories1'])) { $config.="\$largTableCategories=".abs($_POST['largTableCategories1']).";\n";} else {$config.="\$largTableCategories=135;\n";}
  $config.="\$western=\"".$_POST['western1']."\";\n";
  $config.="\$activerWestern=\"".$_POST['activerWestern1']."\";\n";
  $config.="\$activerVirement=\"".$_POST['activerVirement1']."\";\n";
  $config.="\$activerMandat=\"".$_POST['activerMandat1']."\";\n";
  $config.="\$activerCheq=\"".$_POST['activerCheq1']."\";\n";  
  if($_POST['nbreCarCat1']!=='' AND is_numeric($_POST['nbreCarCat1'])) { $config.="\$nbreCarCat=".abs($_POST['nbreCarCat1']).";\n";} else {$config.="\$nbreCarCat=14;\n";}
  if($_POST['nbreCarSubCat1']!=='' AND is_numeric($_POST['nbreCarSubCat1'])) { $config.="\$nbreCarSubCat=".abs($_POST['nbreCarSubCat1']).";\n";} else {$config.="\$nbreCarSubCat=13;\n";}
  if($_POST['maxCarQuick1']!=='' AND is_numeric($_POST['maxCarQuick1'])) { $config.="\$maxCarQuick=".abs($_POST['maxCarQuick1']).";\n";} else {$config.="\$maxCarQuick=18;\n";}
  if($_POST['maxCarInfo1']!=='' AND is_numeric($_POST['maxCarInfo1'])) { $config.="\$maxCarInfo=".abs($_POST['maxCarInfo1']).";\n";} else {$config.="\$maxCarInfo=25;\n";}
  if($_POST['maxCarDesc1']!=='' AND is_numeric($_POST['maxCarDesc1'])) { $config.="\$maxCarDesc=".abs($_POST['maxCarDesc1']).";\n";} else {$config.="\$maxCarDesc=190;\n";}
  $config.="\$menuNavCom=\"".$_POST['menuNavCom1']."\";\n";
  $config.="\$storeWidth=\"".$aaaaaa."\";\n";
  if($_POST['cellpad1']!=='' AND is_numeric($_POST['cellpad1'])) { $config.="\$cellpad=".abs($_POST['cellpad1']).";\n";} else {$config.="\$cellpad=0;\n";}
  if($_POST['cellTop1']!=='' AND is_numeric($_POST['cellTop1'])) { $config.="\$cellTop=".abs($_POST['cellTop1']).";\n";} else {$config.="\$cellTop=120;\n";}
  if(!ini_get("safe_mode")) {
  if(getenv('TZ')) {
      $config.="\$timeZone=\"".$_POST['timeZone1']."\";\n";  
      $config.="if(getenv('TZ')) @putenv(\"TZ=".$_POST['timeZone1']."\");\n";
  }
  else {
      $config.="\$timeZone=\"Europe/Paris\";\n";  
      $config.="if(getenv('TZ')) @putenv(\"TZ=Europe/Paris\");\n";  
  }
  }
  $config.="\$displayRelatedImage=\"".$_POST['displayRelatedImage1']."\";\n";  
  if($_POST['ImageSizeDescRelated1']!=='' AND is_numeric($_POST['ImageSizeDescRelated1'])) { $config.="\$ImageSizeDescRelated=".abs($_POST['ImageSizeDescRelated1']).";\n";} else {$config.="\$ImageSizeDescRelated=20;\n";}
  $config.="\$activerRemisePastOrder=\"".$_POST['activerRemisePastOrder1']."\";\n"; 
  if($_POST['remisePastOrder1']!=='' AND is_numeric($_POST['remisePastOrder1'])) { $config.="\$remisePastOrder=".abs($_POST['remisePastOrder1']).";\n";} else {$config.="\$remisePastOrder=3;\n";}
  if($_POST['saveCart1']!=='' AND is_numeric($_POST['saveCart1'])) { $config.="\$saveCart=".abs($_POST['saveCart1']).";\n";} else {$config.="\$saveCart=30;\n";}
  $config.="\$saveCartToOne=\"".$_POST['saveCartToOne1']."\";\n";  
  $config.="\$idVisible=\"".$_POST['idVisible1']."\";\n";  
  $config.="\$affiliateVisible=\"".$_POST['affVisible1']."\";\n";  
  if($_POST['affiliateCom1']!=='' AND is_numeric($_POST['affiliateCom1'])) { $config.="\$affiliateCom=".abs($_POST['affiliateCom1']).";\n";} else {$config.="\$affiliateCom=6;\n";}
  if($_POST['affiliateTop1']!=='' AND is_numeric($_POST['affiliateTop1'])) { $config.="\$affiliateTop=".abs($_POST['affiliateTop1']).";\n";} else {$config.="\$affiliateTop=30;\n";}
  if($_POST['minimumOrder1']!=='' AND is_numeric($_POST['minimumOrder1'])) { $config.="\$minimumOrder=".abs($_POST['minimumOrder1']).";\n";} else {$config.="\$minimumOrder=0;\n";}
  if($_POST['nbre_col_sm1']!=='' AND is_numeric($_POST['nbre_col_sm1'])) { $config.="\$nbre_col_sm=".abs($_POST['nbre_col_sm1']).";\n";} else {$config.="\$nbre_col_sm=2;\n";}
  $config.="\$iso=\"".$_POST['iso']."\";\n";
  $config.="\$cartVisible=\"".$_POST['cartVisible1']."\";\n";
  $config.="\$liaisonssl=\"".$_POST['liaisonssl1']."\";\n";
  $config.="\$urlLiaisonssl=\"".$_POST['urlLiaisonssl1']."\";\n";
  $config.="\$noTva=\"".$_POST['noTva1']."\";\n";
  $config.="\$actRes=\"".$_POST['actRes1']."\";\n";
  $config.="\$displayOutOfStock=\"".$_POST['displayOut1']."\";\n";
  $config.="\$gdOpen=\"".$_POST['gdOpen1']."\";\n";
  $config.="\$backGdColorAdmin=\"".$backGdColorAdmin."\";\n";
  $config.="\$backGdColor=\"".$backGdColor."\";\n";

  $config.="\$catDisplayPromo=\"".$_POST['catDisplayPromo1']."\";\n";
  $config.="\$catDisplayNews=\"".$_POST['catDisplayNews1']."\";\n";
  $config.="\$catDisplayBest=\"".$_POST['catDisplayBest1']."\";\n";
  $config.="\$catDisplayFew=\"".$_POST['catDisplayFew1']."\";\n";
  $config.="\$catDisplayExc=\"".$_POST['catDisplayExc1']."\";\n";
  $config.="\$catDisplayRandOne=\"".$_POST['catDisplayRandOne1']."\";\n";
  $config.="\$catDisplayRandAll=\"".$_POST['catDisplayRandAll1']."\";\n";
  if($_POST['seuilPromo1']!=='' AND is_numeric($_POST['seuilPromo1'])) { $config.="\$seuilPromo=".abs($_POST['seuilPromo1']).";\n";} else {$config.="\$seuilPromo=0;\n";}
  $config.="\$activeSeuilPromo=\"".$_POST['activeSeuilPromo1']."\";\n";
  $config.="\$ogonePayment=\"".$_POST['ogonePayment1']."\";\n";
  $config.="\$ogonePspid=\"".$_POST['ogonePspid1']."\";\n";
  $config.="\$ogoneMode=\"".$_POST['ogoneMode1']."\";\n";
  if($_POST['nbreMenuTab1']!=='' AND is_numeric($_POST['nbreMenuTab1'])) { $config.="\$nbreMenuTab=".abs($_POST['nbreMenuTab1']).";\n";} else {$config.="\$nbreMenuTab=10;\n";}
  $config.="\$devis=\"".$_POST['devis1']."\";\n";
  if($_POST['devisValid1']!=='' AND is_numeric($_POST['devisValid1'])) { $config.="\$devisValid=".abs($_POST['devisValid1']).";\n";} else {$config.="\$devisValid=45;\n";}
  $config.="\$devisModule=\"".$_POST['devisModule1']."\";\n";
  $config.="\$defaultOrder=\"".$defaultOrder."\";\n";
  $config.="\$bluepaid=\"".$_POST['bluepaid1']."\";\n";
  $config.="\$bluepaidId=\"".$_POST['bluepaidId1']."\";\n";
  $config.="\$bpDevise=\"".$_POST['bpDevise1']."\";\n";
  $config.="\$gcActivate=\"".$_POST['gcActivate1']."\";\n";
    if($_POST['seuilGc1']!=='' AND is_numeric($_POST['seuilGc1'])) { $config.="\$seuilGc=".abs($_POST['seuilGc1']).";\n";} else {$config.="\$seuilGc=0;\n";}
  $config.="\$remiseOnTax=\"".$_POST['remiseOnTax1']."\";\n";
  $config.="\$activerTraite=\"".$_POST['activerTraite1']."\";\n"; 
  $config.="\$paySiteCash=\"".$_POST['paySiteCash1']."\";\n";
  $config.="\$paySiteCashSite=\"".$_POST['paySiteCashSite1']."\";\n";
  $config.="\$paysiteCashDevise=\"".$_POST['paysiteCashDevise1']."\";\n";
  $config.="\$paySiteCashTest=\"".$_POST['paySiteCashTest1']."\";\n";
  $config.="\$paySiteCashVal=\"".$_POST['paySiteCashVal1']."\";\n";
  $config.="\$storeClosed=\"".$_POST['storeOpen1']."\";\n";
  $config.="\$catalogPop=\"".$_POST['catalogPop1']."\";\n";
  $config.="\$categoriesPop=\"".$_POST['categoriesPop1']."\";\n";
  $config.="\$listPop=\"".$_POST['listPop1']."\";\n";
  $config.="\$menuCssVisibleVertical=\"".$_POST['menuCssVisible1']."\";\n";
  $config.="\$menuCssVisibleHorizon=\"".$_POST['menuCssVisibleh1']."\";\n";
  $config.="\$menuV=\"".$_POST['menu1V']."\";\n";
  $config.="\$menuVisibleTab=\"".$_POST['menuVisibleTab1']."\";\n";
  $config.="\$menuVisiblePhp=\"".$_POST['menuVisiblePhp1']."\";\n";
  $config.="\$nowVisible=\"".$_POST['nowVisible1']."\";\n";
  $config.="\$comVisible=\"".$_POST['comVisible1']."\";\n";
  $config.="\$addBookmark=\"".$_POST['addBookmark1']."\";\n";
  
  $_POST['scrollColor1']=str_replace("#","",$_POST['scrollColor1']);
  if($_POST['scrollColor1']=='') {$config.="\$scrollColor=\"none\";\n";} else {$config.="\$scrollColor=\"".$_POST['scrollColor1']."\";\n";}
  if($_POST['scrollHeight1']=='' OR !is_numeric($_POST['scrollHeight1'])) {$config.="\$scrollHeight=60;\n";} else {$config.="\$scrollHeight=".abs($_POST['scrollHeight1']).";\n";}
  if($_POST['scrollWidth1']=='' OR !is_numeric($_POST['scrollWidth1'])) {$config.="\$scrollWidth=135;\n";} else {$config.="\$scrollWidth=".abs($_POST['scrollWidth1']).";\n";}
  if($_POST['scrollDelay1']=='' OR !is_numeric($_POST['scrollDelay1'])) {$config.="\$scrollDelay=6000;\n";} else {$config.="\$scrollDelay=".abs($_POST['scrollDelay1']).";\n";}
  if($_POST['nic1']=='') {$config.="\$nic2=\"none\";\n";} else {$config.="\$nic2=\"".$_POST['nic1']."\";\n";}
  $config.="\$menuNewsVisible=\"".$_POST['menuNewsVisible1']."\";\n";
  $config.="\$menuPromoVisible=\"".$_POST['menuPromoVisible1']."\";\n";
  $config.="\$displayDescCatalog=\"".$_POST['displayDescCatalog1']."\";\n";
  if($_POST['im_size_sm1']!=='' AND is_numeric($_POST['im_size_sm1'])) { $config.="\$im_size_sm=".abs($_POST['im_size_sm1']).";\n";} else {$config.="\$im_size_sm=1;\n";}


  if($_POST['vb2b1']=="oui") $_POST['activeEcom1'] = "non";
  if($_POST['vb2c1']=="oui") $_POST['activeEcom1'] = "oui";
  if($_POST['vb2b1'] == $_POST['vb2c1']) {$_POST['vb2b1']="non"; $_POST['vb2c1']="oui"; $_POST['activeEcom1'] = "oui";}
  if($_POST['vb2c1'] == "oui") {$_POST['vb2b1']="non"; $_POST['displayPriceInShop1']="oui"; $_POST['activeEcom1']="oui";}
  if($_POST['activeEcom1']=="oui" AND $_POST['displayPriceInShop1']=="non") {$_POST['activeEcom1']="oui"; $_POST['displayPriceInShop1']="oui";}
  if($_POST['activeEcom1']=="oui" AND $_POST['displayPriceInShop1']=="oui" AND $_POST['vb2b1']=="oui") {$_POST['vb2c1']="oui"; $_POST['vb2b1']="non"; $_POST['displayPriceInShop1']="oui"; $_POST['activeEcom1']="oui";}
  $config.="\$vb2b=\"".$_POST['vb2b1']."\";\n";
  $config.="\$vb2c=\"".$_POST['vb2c1']."\";\n";
  $config.="\$displayPriceInShop=\"".$_POST['displayPriceInShop1']."\";\n";  
  $config.="\$activeEcom=\"".$_POST['activeEcom1']."\";\n";
  if($_POST['dateActivationPdf1']=='') {$config.="\$dateActivationPdf=\"2010-01-01\";\n";} else {
  // European date format DD-MM-YYYY START
  $_POST['dateActivationPdf1'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['dateActivationPdf1']);
  // European date format DD-MM-YYYY END
  $config.="\$dateActivationPdf=\"".$_POST['dateActivationPdf1']."\";\n";
  }
  $config.="\$catFooter=\"".$_POST['catFooter1']."\";\n";
  $config.="\$bannerHeader=\"".$_POST['bannerHeader1']."\";\n";
  $config.="\$bannerFooter=\"".$_POST['bannerFooter1']."\";\n";  
  $config.="\$euroPayment=\"".$_POST['euroPayment1']."\";\n";
  if($_POST['merchant_id1']!=='') {$config.="\$merchant_id=\"".$_POST['merchant_id1']."\";\n";} else {$config.="\$merchant_id=\"032877638277777\";\n";}
  if($_POST['factNum1']!=='') {$config.="\$factNum=\"".$_POST['factNum1']."\";\n";} else {$config.="\$factNum=\"99999\";\n";}
  $config.="\$plugin=\"".$_POST['plugin1']."\";\n";
  if($_POST['hauteurTitreModule1']!=='') {$config.="\$hauteurTitreModule=\"".$_POST['hauteurTitreModule1']."\";\n";} else {$config.="\$hauteurTitreModule=\"1\";\n";}  
  $config.="\$header2Display=\"".$_POST['header2Display1']."\";\n";
  $config.="\$header1Display=\"".$_POST['header1Display1']."\";\n";
  if($_POST['menuWidthCSSH1']!=='' AND is_numeric($_POST['menuWidthCSSH1'])) {$config.="\$menuWidthCSSH=".abs($_POST['menuWidthCSSH1']).";\n";} else {$config.="\$menuWidthCSSH=109;\n";}
  $config.="\$tableDisplay=\"".$_POST['tableDisplay1']."\";\n";
  $config.="\$tableDisplayRight=\"".$_POST['tableDisplayRight1']."\";\n";
  $config.="\$expressBuy=\"".$_POST['expressBuy1']."\";\n";
  if($_POST['imZoomMax1']!=='' AND is_numeric($_POST['imZoomMax1'])) {$config.="\$imZoomMax=".abs($_POST['imZoomMax1']).";\n";} else {$config.="\$imZoomMax=175;\n";}
  if($_POST['numNumList1']!=='' AND is_numeric($_POST['numNumList1'])) {$config.="\$numNumList=".abs($_POST['numNumList1']).";\n";} else {$config.="\$numNumList=10;\n";}
  $config.="\$codeReductionActive=\"".$_POST['codeReductionActive1']."\";\n";
  $config.="\$mlFact=\"".$_POST['mlFact1']."\";\n";
  $config.="\$activeRSS=\"".$_POST['activeRSS1']."\";\n";
  $config.="\$lightbox=\"".$_POST['lightbox1']."\";\n";
  $config.="\$autoConfirm=\"".$_POST['autoConfirm1']."\";\n";
  $config.="\$activeActu=\"".$_POST['activeActu1']."\";\n";
  if($_POST['maxCarActu1']!=='' AND $_POST['maxCarActu1']>0 AND is_numeric($_POST['maxCarActu1'])) {$config.="\$maxCarActu=".abs($_POST['maxCarActu1']).";\n";} else {$config.="\$maxCarActu=150;\n";}
  if($_POST['nbreLigneActu1']!=='' AND $_POST['nbreLigneActu1']>0 AND is_numeric($_POST['nbreLigneActu1'])) {$config.="\$nbreLigneActu=".abs($_POST['nbreLigneActu1']).";\n";} else {$config.="\$nbreLigneActu=10;\n";}
  if($_POST['seuilCadeau1']!=='' AND is_numeric($_POST['seuilCadeau1'])) {$config.="\$seuilCadeau=".abs($_POST['seuilCadeau1']).";\n";} else {$config.="\$seuilCadeau=0;\n";}
  
  $config.="\$activerFiaNet=\"".$_POST['activerFiaNet1']."\";\n";
  $config.="\$fiaNetNum=\"".$_POST['fiaNetNum1']."\";\n";
  $config.="\$displayUnderDescCatalog=\"".$_POST['displayUnderDescCatalog1']."\";\n";
  $config.="\$forceNouv=\"".$_POST['forceNouv1']."\";\n";
  $config.="\$forcePromo=\"".$_POST['forcePromo1']."\";\n";
  $config.="\$klikandpayActive=\"".$_POST['klikandpayActive1']."\";\n";
  $config.="\$klikandpayId=\"".$_POST['klikandpayId1']."\";\n";
  $config.="\$klilandpayTest=\"".$_POST['klilandpayTest1']."\";\n";
  $config.="\$expandMenu=\"".$_POST['expandMenu1']."\";\n";
  if($_POST['id_partenaire1']!=='') {$config.="\$id_partenaire=\"".$_POST['id_partenaire1']."\";\n";} else {$config.="\$id_partenaire=\"\";\n";}
  $config.="\$displayGraphics=\"".$_POST['displayGraphics1']."\";\n";
  $config.="\$displayAffInCart=\"".$_POST['displayAffInCart1']."\";\n";
  $config.="\$addNavCenterPage=\"".$_POST['addNavCenterPage1']."\";\n";
  if($_POST['pendingOrder1']!=='' AND is_numeric($_POST['pendingOrder1'])) {$config.="\$pendingOrder=".abs($_POST['pendingOrder1']).";\n";} else {$config.="\$pendingOrder=30;\n";}
  if($_POST['store_company1']!=='') {$config.="\$store_company=\"".removeAp(str_replace('&','&amp;',$_POST['store_company1']))."\";\n";} else {$config.="\$store_company=\"Mijn bedrijf\";\n";} 
  $config.="\$tableDisplayLeft=\"".$_POST['tableDisplayLeft1']."\";\n";
  $config.="\$selectLang=\"".$_POST['selectLang1']."\";\n";
  $config.="\$displaySubCategoryName=\"".$_POST['displaySubCategoryName1']."\";\n";
  $config.="\$displaySubCategoryNameUnder=\"".$_POST['displaySubCategoryNameUnder1']."\";\n";  
  if($_POST['maxCarCatAff1']!=='' AND is_numeric($_POST['maxCarCatAff1'])) {$config.="\$maxCarCatAff=".abs($_POST['maxCarCatAff1']).";\n";} else {$config.="\$maxCarCatAff=80;\n";}
  if($_POST['carSizeTitleCat1']!=='' AND is_numeric($_POST['carSizeTitleCat1'])) {$config.="\$carSizeTitleCat=".abs($_POST['carSizeTitleCat1']).";\n";} else {$config.="\$carSizeTitleCat=29;\n";}
  if($_POST['maxCarTitleAff1']!=='' AND is_numeric($_POST['maxCarTitleAff1'])) {$config.="\$maxCarTitleAff=".abs($_POST['maxCarTitleAff1']).";\n";} else {$config.="\$maxCarTitleAff=29;\n";}
  if($_POST['header2Display1']=='non') {$config.="\$bannerHeader2=\"non\";\n";} else {$config.="\$bannerHeader2=\"".$_POST['bannerHeader21']."\";\n";}
  $config.="\$logo2=\"".$_POST['logo22']."\";\n";
	if(empty($_POST['backgroundImageHeader1']) OR $_POST['backgroundImageHeader1']=="" OR $_POST['backgroundImageHeader1']=='noPath') {
		$config.="\$backgroundImageHeader=\"".$_POST['backgroundImageHeader1']."\";\n";
	}
	else {
		$config.="\$backgroundImageHeader=\"im/background/".$_POST['backgroundImageHeader1']."\";\n";
	}
  if($_POST['logo22']=='noPath') $config.="\$urlLogo2=\"\";\n"; else $config.="\$urlLogo2=\"".$_POST['urlLogo22']."\";\n";
  $config.="\$www=\"".$_POST['www1']."\";\n";
  $config.="\$www2=\"".$wwwZ."\";\n";
  $config.="\$directPayment=\"".$_POST['directPayment1']."\";\n";
  $config.="\$mbPayment=\"".$_POST['mbPayment1']."\";\n";
  $config.="\$mbId=\"".trim($_POST['mbId1'])."\";\n";
  $config.="\$mbEmail=\"".trim($_POST['mbEmail1'])."\";\n";
  $config.="\$mbSecretWord=\"".strtolower(trim($_POST['mbSecretWord1']))."\";\n";
  $config.="\$mbDevise=\"".$_POST['mbDevise1']."\";\n";
  $config.="\$paynlPayment=\"".$_POST['paynlPayment1']."\";\n";
  $config.="\$paynlProgramId=\"".$_POST['paynlProgramId1']."\";\n";
  $config.="\$paynlWebsiteId=\"".$_POST['paynlWebsiteId1']."\";\n";
  $config.="\$paynlWebsiteLocationId=\"".$_POST['paynlWebsiteLocationId1']."\";\n";
  $config.="\$devise2Visible=\"".$_POST['devise2Visible1']."\";\n";
  $config.="\$symbolDevise2=\"".$_POST['symbolDevise21']."\";\n";
  if($_POST['tauxDevise21']=='' OR !is_numeric($_POST['tauxDevise21'])) $config.="\$tauxDevise2=\"1\";\n"; else $config.="\$tauxDevise2=\"".str_replace(",",".",abs($_POST['tauxDevise21']))."\";\n";
  if(ini_get("safe_mode")) $config.="\$safeMode=\"on\";\n"; else $config.="\$safeMode=\"off\";\n";
	
	if(empty($_POST['header2Image1']) OR $_POST['header2Image1']=="" OR $_POST['header2Image1']=="noPath") {
		$config.="\$header2Image=\"noPath\";\n";
	}
	else {
		$config.="\$header2Image=\"im/banner/".$_POST['header2Image1']."\";\n";
	}
  
  //$config.="\$header2Image=\"im/banner/".$_POST['header2Image1']."\";\n";
  $config.="\$menuCssHorizonCenter=\"".$_POST['menuCssHorizonCenter1']."\";\n";
  $config.="\$menuTabCenter=\"".$_POST['menuTabCenter1']."\";\n";
  $config.="\$iscEmail=\"".$_POST['iscEmail1']."\";\n";
  $config.="\$tvaManuelValidation=\"".$_POST['tvaManuelValidation1']."\";\n";
  $config.="\$menuAccueilVisible=\"".$_POST['menuAccueilVisible1']."\";\n";
  if($_POST['menuWidthCSSH21']!=='' AND is_numeric($_POST['menuWidthCSSH21'])) {$config.="\$menuWidthCSSH2=".abs($_POST['menuWidthCSSH21']).";\n";} else {$config.="\$menuWidthCSSH2=109;\n";}
  if($_POST['menuWidthCSSVSub1']!=='' AND is_numeric($_POST['menuWidthCSSVSub1'])) {
        $config.="\$menuWidthCSSVSub=".abs($_POST['menuWidthCSSVSub1']).";\n";
  } 
  else {
        if($_POST['larg_rub1']!=='' AND is_numeric($_POST['larg_rub1'])) {
            $config.="\$menuWidthCSSVSub=".abs($_POST['larg_rub1']).";\n";
        }
        else {
            $config.="\$menuWidthCSSVSub=140;\n";
        }
  }
  $config.="\$redirectToShop=\"".$_POST['redirectToShop1']."\";\n";
  $config.="\$adminMenu=\"".$_POST['adminMenu1']."\";\n";
  $config.="\$factPrefixe=\"".str_replace("|","",$_POST['factPrefixe1'])."||\";\n";
  $config.="\$displayDelivery=\"".$_POST['displayDelivery1']."\";\n";
  $config.="\$chequeOrdre=\"".removeAp($_POST['chequeOrdre1'])."\";\n";
  $config.="\$mandatOrdre=\"".removeAp($_POST['mandatOrdre1'])."\";\n";
  $config.="\$traiteOrdre=\"".removeAp($_POST['traiteOrdre1'])."\";\n";
  $config.="\$lastViewVisible=\"".$_POST['lastViewVisible1']."\";\n";
  $config.="\$lastViewCartVisible=\"".$_POST['lastViewCartVisible1']."\";\n";
  if($_POST['lastViewCartNum1']!=='' AND is_numeric($_POST['lastViewCartNum1'])) {$config.="\$lastViewCartNum=".$_POST['lastViewCartNum1'].";\n";} else {$config.="\$lastViewCartNum=5;\n";}
  $config.="\$ogoneSha=\"".removeAp($_POST['ogoneSha1'])."\";\n";
  $config.="\$displayProductsList=\"".$_POST['displayProductsList1']."\";\n";
  if($_POST['displayProductsListNum1']!=='' AND is_numeric($_POST['displayProductsListNum1'])) {$config.="\$displayProductsListNum=".abs($_POST['displayProductsListNum1']).";\n";} else {$config.="\$displayProductsListNum=0;\n";}
  $config.="\$displayNextProduct=\"".$_POST['displayNextProduct1']."\";\n";
  $config.="\$pfPayment=\"".$_POST['pfPayment1']."\";\n";
  $config.="\$pfPspid=\"".$_POST['pfPspid1']."\";\n";
  $config.="\$pfSha=\"".removeAp($_POST['pfSha1'])."\";\n";
  $config.="\$pfMode=\"".removeAp($_POST['pfMode1'])."\";\n";
  $config.="\$pfCurrency=\"".$_POST['pfCurrency1']."\";\n";
  $config.="\$activerTiny=\"".$_POST['activerTiny1']."\";\n";
  $config.="\$addCartList=\"".$_POST['addCartList1']."\";\n";
  $config.="\$backGdColorListLine=\"".$backGdColorListLine."\";\n";
  $config.="\$qtMenu=\"".$_POST['qtMenu1']."\";\n";
  $config.="\$affiliateAuto=\"".$_POST['affiliateAuto1']."\";\n";
  $config.="\$addThisVisible=\"".$_POST['addThisVisible1']."\";\n";
  $config.="\$paymentsDesactive=\"".$_POST['paymentsDesactive1']."\";\n";
  $config.="\$modifCustomerNb=\"".$_POST['modifCustomerNb1']."\";\n";
  $config.="\$backGdColorCatLine=\"".$backGdColorCatLine."\";\n";
  $config.="\$moteurVisibleMenuV=\"".$_POST['moteurVisibleMenuV1']."\";\n";
  $config.="\$moteurVisibleMenuPhp=\"".$_POST['moteurVisibleMenuPhp1']."\";\n";
  $config.="\$activeSaveCart=\"".$_POST['activeSaveCart1']."\";\n";
  $config.="\$activeRSSMod=\"".$_POST['activeRSSMod1']."\";\n";
  $config.="\$displayPaymentsLogoDesc=\"".$_POST['displayPaymentsLogoDesc1']."\";\n";
  $config.="\$displayShippingLogoDesc=\"".$_POST['displayShippingLogoDesc1']."\";\n";
  $config.="\$colomnLeft=\"".$_POST['colomnLeft1']."\";\n";
  $config.="\$colomnRight=\"".$_POST['colomnRight1']."\";\n";

  $config.="?>";



  $topo = fopen("../configuratie/configuratie.php","w");
  fputs($topo, $config);
  fclose($topo);
  
$fichier=fopen("../configuratie/mysql_connect.php","w");
  fputs($fichier, "<?php\n");
  fputs($fichier, "$"."bddHost=\"".$_POST['bddHost1']."\";\n");
  fputs($fichier, "$"."bddUser=\"".$_POST['bddUser1']."\";\n");
  fputs($fichier, "$"."bddPass=\"".$_POST['bddPass1']."\";\n");
  fputs($fichier, "$"."bddName=\"".$_POST['bddName1']."\";\n");
  fputs($fichier, "mysql_connect(\"".$_POST['bddHost1']."\",\"".$_POST['bddUser1']."\",\"".$_POST['bddPass1']."\");\n");
  fputs($fichier, "mysql_select_db(\"".$_POST['bddName1']."\");\n");
  fputs($fichier, "?>");
  fclose($fichier);
?>
<table align="center" width="700" border="0" cellspacing="0" cellpadding="3" class="TABLE"><tr>
    <td colspan="2"><div align="center" class="fontrouge"><b><?php print A104;?></b></div></td>
  </tr>
  <tr>
    <td colspan="2"><br><div align="center"><b><a href="site_config.php" target='main'><?php print strtoupper(A105);?></a></b></div></td>
  </tr>
  </table>
<?php
}
?>

</form>
  </body>
  </html>
