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
$n = 520;
$message = "";


if(isset($_POST['selectProductsId']) and count($_POST['selectProductsId']) > 0) {
    $rr = implode("|",$_POST['selectProductsId']);
}
else {
    $rr = "";
}

 
if(isset($_POST['action2']) and $_POST['action2'] == "go") {

if(empty($_POST['code'])) { $message .= "<span style='color:red'>".A2."</span>"; $checkCode = "notok";} else {$checkCode = "ok";}
if(empty($_POST['reduction']) or !is_numeric($_POST['reduction'])) { 
  $message .= "<span style='color:red'>".A3."</span>"; $checkCode = "notok";
  } 
  else {
  $checkReduction = "ok";
  }
if(empty($_POST['seuil'])) { $seuil = 0;}
if(!is_numeric($_POST['seuil']) and !empty($_POST['seuil'])) { $message .= "<span style='color:red'>".A4."</span>"; $checkSeuil = "notok";} else {$checkSeuil = "ok";}

 
$_POST['debut'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['debut']);
$_POST['fin'] = ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$_POST['fin']);
 
if(empty($_POST['debut'])) {
     $_POST['debut'] = date("Y-m-d");
     $checkDebut = "ok";
}
else {
      $toto2 = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['debut']);
      if($toto2 == true) {
          $checkDate = explode("-",$_POST['debut']);
          $verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
             if($verifDate == true) {
                 $checkDebut = "ok";
                 $message .= "";
                 }
                 else {
                  $message .= "<span style='color:red'>".A5."</span>";
                  $checkDebut = "notok";
                  }
             }
             else {
              $message .= "<span style='color:red'>".A5."</span>";
              $ckeckDebut = "notok";
             }
}



if(empty($_POST['fin'])) {
	$_POST['fin'] = "2040-01-01";
	$checkFin = "ok";
}
else {
	$toto = preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $_POST['fin']);
	if($toto == true) {
		$checkDate = explode("-",$_POST['fin']);
		$verifDate = checkdate($checkDate[1],$checkDate[2],$checkDate[0]);
		if($verifDate == true) {
			$checkFin = "ok";
			$message .= "";
		}
		else {
			$message .= "<span style='color:red'>".A6."</span>";
			$checkFin = "notok";
		}
	}
	else {
		$message .= "<span style='color:red'>".A6."</span>";
		$checkFin = "notok";
	}
}


$dateMaxCheck = explode("-",$_POST['fin']);
$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
$dateDebutCheck = explode("-",$_POST['debut']);
$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
if($dateMax < $dateDebut) {
	$message .= "<span style='color:red'>".A7."</span>";
	$OnYVa = "notok";
}
else {
	$OnYVa = "ok";
	$message .= "";
}

if($checkCode == "ok" AND $checkReduction == "ok" AND $checkDebut == "ok" AND $checkFin == "ok" AND $OnYVa == "ok" AND $checkSeuil == "ok") {
   $query = mysql_query("SELECT code_promo FROM code_promo WHERE code_promo = '".$_POST['code']."'");
   $queryNum = mysql_num_rows($query);
   if($queryNum > 0) {
      $result = mysql_fetch_array($query);
      $message = "<span style='color:red'><b>".$result['code_promo']. " ".A110."</b></span>";
   }
   else {
      mysql_query("INSERT INTO code_promo
                   SET
                   code_promo = '".$_POST['code']."',
                   code_promo_start = '".$_POST['debut']."',
                   code_promo_end = '".$_POST['fin']."',
                   code_promo_reduction = '".$_POST['reduction']."',
                   code_promo_type = '".$_POST['type']."',
                   code_promo_stat = '".$_POST['stat']."',
                   code_promo_seuil = '".$_POST['seuil']."',
                   code_promo_items = '".$rr."',
                   code_promo_note = '".addslashes(str_replace("'","´",$_POST['note']))."'
                   ");
     $message = "<span style='color:red'><b>".A8."</b></span>";
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
<p align="center" class="largeBold"><?php print A1;?></p>
<table border="0" width="700" cellpadding="5" cellspacing ="0" align="center" class="TABLE"><tr><td>
<div align="left"><?php print A10;?></div>
</tr></td></table>

<p align="center"><b><?php print $message;?></b></p>

<?php
$productsQuery = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id, p.products_ref, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, now(),
                               IF(s.specials_new_price < p.products_price
                               AND s.specials_visible = 'yes'
                               AND s.specials_last_day >= NOW()
                               AND s.specials_first_day <= NOW(),
                               'YES', 'NO') as ord
                              FROM products as p
                              LEFT JOIN specials as s
                              ON (p.products_id = s.products_id)
                              WHERE p.products_ref != 'GC100'
                              AND p.products_visible = 'yes'
                              AND p.products_forsale = 'yes'
                              ORDER BY p.products_name_".$_SESSION['lang']."");
$productsQueryNum = mysql_num_rows($productsQuery);

$categoriesQuery = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_noeud, categories_id
                                FROM categories
                                WHERE categories_visible = 'yes'
                                AND categories_noeud = 'L'");

print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<input type='hidden' name='action2' value='go'>";

 

        print "<table border='0' width='700' cellpadding='5' align='center' cellspacing ='0' class='TABLE'><tr>";
 
        print "<td>".A11."</td>";
        print "<td width=250><input type='text' size='20' class='vullen' name='code'></td>";

        print "</tr><tr>";
        print "<td valign=top>".A12."</td><td><input type='text' size='6' class='vullen' name='reduction'>&nbsp;&nbsp;";
        print "<select name='type'>";
                print "<option value='%'>%</option>";
                print "<option value='".$symbolDevise."'>".$symbolDevise."</option>";
        print "</select></td>";
 
        print "</tr><tr>";
        print "<td valign=top>".A13."&nbsp;(3)</td><td><input type='text' class='vullen' size='6' name='seuil'>&nbsp;".$symbolDevise."</td>";
        print "</tr><tr>";
        print "<td>".A14."&nbsp;(1)</td>";
        print "<td valign=top><input type='text' size='20' class='vullen' name='debut'><br>";
        print "".A15.": ".date("d-m-Y")."</span></td>";
        print "</tr><tr>";
        print "<td valign=top>".A16."&nbsp;(2)</td><td><input type='text' class='vullen' size='20' name='fin'><br>";
        print "".A15.": ".date("d-m-Y")."</span></td>";
 
  
        print "</tr><tr>";
        print "<td valign=top>".A20."&nbsp;(4)</td>";
            print "<td>";
				 print "<select name='stat'>";
                 print "<option name='stat' value='public'>Publiek</option>";
                 print "<option name='stat' value='prive'>".A21."</option>";
				 print "</select>";
			   print "</td>";
        print "</tr>";
 
        if($productsQueryNum>1) {
			print "<tr>";
			print "<td valign=top>";
			print A201;
			print "</td><td>";
			$hoy = date("Y-m-d");
			print "<select name='selectProductsId[]' size='12' multiple>";
			while($name2 = mysql_fetch_array($productsQuery)) {
				if($name2['ord']=="NO") {
					print "<option value='".$name2['products_id']."'>".$name2['products_name_'.$_SESSION['lang']]." [REF: ".$name2['products_ref']."]</option>";
				}
			}
			print "</select>";
			print "<br>";
			print A200;
			print "</td></tr>";
			// note
			print "<tr>";
			print "<td valign=top>".NOTE_INTERNE."</td>";
			print "<td><textarea cols='70' rows='4' name='note' class='vullen' ></textarea></td>";
			print "</tr>";
        }
        print "</table>";
print "<br>";


print "<table border='0'  width='700' cellpadding='5' cellspacing ='0' align='center' class='TABLE'><tr>";
print "<tr>";
print "<td align='center' height='40'>";
print "<input type='submit' name='action' value='".A1Z."' class='knop'>";
print "</td>";
print "</tr>";
print "</table>";

print "</form>";
?>
<br>
<table width="700" border="0" align="center" cellpadding="5" cellspacing="0" class="TABLE"><tr>
<td><?php print A17;?></td>
</tr></table>
<br><br><br>
</body>
</html>

