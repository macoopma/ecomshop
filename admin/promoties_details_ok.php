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

// European date format DD-MM-YYYY START
$_GET['dateFinPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateFinPromo']);
$_GET['dateDebutPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateDebutPromo']);
// European date format DD-MM-YYYY END

if((empty($_GET['prixPromo']) and empty($_GET['soldeDe'])) or (!empty($_GET['prixPromo']) and !empty($_GET['soldeDe']))) {
     $message = "<span class='fontrouge'>".A1."</span>";
     $prix = "notok";
}
else {
      $queryVerif = mysql_query("SELECT products_price FROM products WHERE products_id = '".$_GET['id']."'");
      $toto = mysql_fetch_array($queryVerif);

      if(is_numeric($_GET['soldeDe']) or is_numeric($_GET['prixPromo']) and $_GET['prixPromo'] < $toto['products_price']) {
         $prix = "ok";
      }
      else {
         $prix = "notok";
         $message = "<span class='fontrouge'>".A2."</span>";
      }
}
 
if(empty($_GET['dateDebutPromo'])) {
     $_GET['dateDebutPromo'] = date("Y-m-d");
     $datePromo1 = "ok";
}
else {
	$toto2 = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_GET['dateDebutPromo']);
	if($toto2 == true) {
		$checkDate = explode("-",$_GET['dateDebutPromo']);
		$verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
		if($verifDate == true) {
			$datePromo1 = "ok";
		}
		else {
			$message .= "<br><span class='fontrouge'>".A3."</span>";
			$datePromo1 = "notok";
		}
	}
	else {
		$message .= "<br><span class='fontrouge'>".A3."</span>";
		$datePromo1 = "notok";
	}
}


// datum einde promotie
if(empty($_GET['dateFinPromo'])) {
     $_GET['dateFinPromo'] = "2040-01-01";
     $datePromo = "ok";
}
else {
	$toto = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_GET['dateFinPromo']);
	if($toto == true) {
		$checkDate = explode("-",$_GET['dateFinPromo']);
		$verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
		if($verifDate == true) {
			$datePromo = "ok";
		}
		else {
			$message .= "<br><span class='fontrouge'>".A4."</span>";
			$datePromo = "notok";
		}
	}
	else {
		$message .= "<br><span class='fontrouge'>".A4."</span>";
		$datePromo = "notok";
	}
}

// Controle datum
$dateMaxCheck = explode("-",$_GET['dateFinPromo']);
$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
$dateDebutCheck = explode("-",$_GET['dateDebutPromo']);
$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);

if($dateMax < $dateDebut) {
	$message .= "<br><span class='fontrouge'>".A5."</span>";
	$OnYVa = "notok";
}
else {
	$OnYVa = "ok";
}


if($prix == "ok" and $datePromo1 == "ok" and $datePromo == "ok" and $OnYVa == "ok") {

    if(!empty($_GET['soldeDe']) AND empty($_GET['prixPromo'])) {
        $prodsSelectedQuery = mysql_query("SELECT products_deee, products_price FROM products WHERE products_id = '".$_GET['id']."'");
        $prodSelected = mysql_fetch_array($prodsSelectedQuery);

        if($prodSelected['products_deee']>0) {
            $priceSelected = $_GET['productPrice']-$prodSelected['products_deee'];
        }
        else {
            $priceSelected = $_GET['productPrice'];
        }
        
        $_GET['prixPromo'] = $priceSelected - (($priceSelected *$_GET['soldeDe'])/100);
    }
	$insert = mysql_query("INSERT INTO specials (products_id, specials_new_price, specials_first_day, specials_visible, specials_last_day )
                         VALUES ('".$_GET['id']."', '".$_GET['prixPromo']."','".$_GET['dateDebutPromo']."', '".$_GET['visu']."', '".$_GET['dateFinPromo']."')");
	$message = "<span class='fontrouge'><b>".A6." ".strtoupper($_GET['productName'])." ".A7."</b></span>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A8;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" width="700" class="TABLE">
       <tr>
        <td align="center" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><FORM action="promoties_toevoegen.php" method="post"><INPUT TYPE="submit" class="knop" VALUE="<?php print A9;?>"></form></td>
       </tr>
</table>

</body>
</html>
