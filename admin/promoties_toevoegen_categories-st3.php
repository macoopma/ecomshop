<?php
session_start();


if(!isset($_SESSION['login'])) header("Location:index.php");
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
include('../configuratie/configuratie.php');


if(empty($_GET['soldeDe'])) {
     $message = "<span class='fontrouge'>".A2."</span>";
     $prix = "notok";
}
else {
      if(is_numeric($_GET['soldeDe'])) {
         $prix = "ok";
      }
      else {
         $prix = "notok";
         $message = "<span class='fontrouge'>".A3."</span>";
      }
}

 
// European date format DD-MM-YYYY START
$_GET['dateFinPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateFinPromo']);
$_GET['dateDebutPromo'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_GET['dateDebutPromo']);
// European date format DD-MM-YYYY END


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
			$message .= "<br><span class='fontrouge'>".A4."</span>";
			$datePromo1 = "notok";
		}
	}
	else {
		$message .= "<br><span class='fontrouge'>".A4."</span>";
		$datePromo1 = "notok";
	}
}

 
if(empty($_GET['dateFinPromo'])) {
     $_GET['dateFinPromo'] = "2040-01-01";
     $datePromo = "ok";
}
else {
      $toto4 = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_GET['dateFinPromo']);
      if($toto4 == true)
         {
          $checkDate = explode("-",$_GET['dateFinPromo']);
          $verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);

             if($verifDate == true)
                {
                 $datePromo = "ok";
                 }
                 else
                 {
                  $message .= "<br><span class='fontrouge'>".A5."</span>";
                  $datePromo = "notok";
                  }
             }
             else
             {
              $message .= "<br><span class='fontrouge'>".A5."</span>";
              $datePromo = "notok";
             }
}

 
	$dateMaxCheck = explode("-",$_GET['dateFinPromo']);
	$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
	$dateDebutCheck = explode("-",$_GET['dateDebutPromo']);
	$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
	
	if($dateMax < $dateDebut) {
		$message .= "<br><span class='fontrouge'>".A6."</span>";
		$OnYVa = "notok";
	}
	else {
		$OnYVa = "ok";
	}

 

if($prix == "ok" and $datePromo1 == "ok" and $datePromo == "ok" and $OnYVa == "ok") {

  if($_GET['id'] !== "nul") $catId = $_GET['id'];

  $query1 = mysql_query("SELECT fournisseurs_company
                      FROM fournisseurs
                      WHERE fournisseurs_id = '".$_GET['fourn']."'");
  $fournisseur = mysql_fetch_array($query1);

  $query2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                      FROM categories
                      WHERE categories_id = '".$_GET['id']."'");
  $categorie = mysql_fetch_array($query2);



 
 
if(is_numeric($_GET['id'])) $toto = " AND p.categories_id = '".$_GET['id']."'"; else $toto = "";
if(is_numeric($_GET['fourn'])) $toto .= " AND p.fournisseurs_id = '".$_GET['fourn']."'"; else $toto .= "";

       $result3 = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id, p.categories_id, f.fournisseurs_company, f.fournisseurs_id, s.specials_id, s.specials_new_price
                                       FROM products as p
                                       LEFT JOIN fournisseurs as f
                                       ON (p.fournisseurs_id = f.fournisseurs_id)
                                       LEFT JOIN specials as s
                                       ON (p.products_id = s.products_id)
                                       WHERE 1
                                       $toto
                                       AND s.specials_id != 'null'
                                       ORDER BY p.products_name_".$_SESSION['lang']."");
       $result3Num = mysql_num_rows($result3);

       while($resultRequete = mysql_fetch_array($result3)) {
             if($result3Num>0) {
                  $delete = mysql_query("DELETE FROM specials WHERE products_id='".$resultRequete['products_id']."'");
             }
       }

 
$i=1;
if(is_numeric($_GET['id'])) { $titi = " AND (categories_id = '".$_GET['id']."' OR categories_id_bis LIKE '%|".$_GET['id']."|%') "; } else { $titi = "";}
if(is_numeric($_GET['fourn'])) { $titi .= " AND fournisseurs_id = '".$_GET['fourn']."'"; } else { $titi .= ""; }

	   $result4 = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_price, products_visible, products_deee
                               FROM  products
                               WHERE 1
                               ".$titi."
                               AND products_price > '0'
                               AND products_forsale = 'yes'
                               ORDER BY products_name_".$_SESSION['lang']."");

             print "<table border='0' align='center' cellpadding='3' cellspacing ='0'>";
             print "<tr><td>";

       while($resultRequete4 = mysql_fetch_array($result4)) {
             $prodId = $resultRequete4['products_id'];
             $productPrice = $resultRequete4['products_price'];
            if($resultRequete4['products_deee']>0) {
                $priceSelected = $productPrice-$resultRequete4['products_deee'];
            }
            else {
                $priceSelected = $productPrice;
            }
             $prixPromo = $priceSelected - (($priceSelected*$_GET['soldeDe'])/100);
             $visu = $resultRequete4['products_visible'];
             print "<tr><td>";

             print $i++.". ".A7.": <b>-".$_GET['soldeDe']."%</b> | ".A8." ".dateFr($_GET['dateDebutPromo'],$_SESSION['lang'])." ".A9." ".dateFr($_GET['dateFinPromo'],$_SESSION['lang'])." | <s>".$productPrice."</s> ".$symbolDevise."/<span style='color:red'><b>".sprintf("%0.2f",$prixPromo)." $symbolDevise</b></span> | <b><i>".$resultRequete4['products_name_'.$_SESSION['lang']]."</i></b>";
             print "</td></tr>";
             // Insérer les articles en promo dans la table specials 
             $insert = mysql_query("INSERT INTO specials (products_id, specials_new_price, specials_first_day, specials_visible, specials_last_day )
                                    VALUES ('".$prodId."', '".$prixPromo."','".$_GET['dateDebutPromo']."', '".$visu."', '".$_GET['dateFinPromo']."')");
       }
             print "</table>";



if(!is_numeric($_GET['id']) and !is_numeric($_GET['fourn'])) {
  $message = "<span class='fontrouge'>".A10."</span>";
  }

if(is_numeric($_GET['id']) and !is_numeric($_GET['fourn'])) {
  $message = "<span class='fontrouge'>".A11." <b>".strtoupper($categorie['categories_name_'.$_SESSION['lang']])."</b> ".A12."</span>";
  }

if(!is_numeric($_GET['id']) and is_numeric($_GET['fourn'])) {
  $message = "<span class='fontrouge'>".A13." <b>".strtoupper($fournisseur['fournisseurs_company'])."</b> ".A12."</span>";
  }

if(is_numeric($_GET['id']) and is_numeric($_GET['fourn'])) {
  $message = "<span class='fontrouge'>".A11." <b>".strtoupper($categorie['categories_name_'.$_SESSION['lang']])."</b> ".A14." <b>".strtoupper($fournisseur['fournisseurs_company'])."</b> ".A12."</span>";
  }
}

?>
<br>
<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><FORM action="promoties_toevoegen_categories.php" method="post"><INPUT TYPE="submit" class="knop" VALUE="<?php print A15;?>"></form></td>
       </tr>
</table>

</body>
</html>
