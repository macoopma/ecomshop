<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");

 
if(isset($_SESSION['user']) AND $_SESSION['user']=='user') {
	print "<html>";
	print "<head>";
	print "<title>Niet toegelaten</title>";
	print "<link rel='stylesheet' href='style.css'>";
	print "</head>";
	print "<body>";
	print "<p align='center' style='FONT-SIZE: 15px; color:#FF0000;'>Beperkte toegang</p>";
	print "</body>";
	print "</html>";
	exit;
}

include('../configuratie/configuratie.php');
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
if(empty($_GET['orderf']))  $_GET['orderf'] = "products_name_".$_SESSION['lang'];

if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if(isset($_GET['c1']) and $_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(isset($_GET['action']) and $_GET['action']=="reset") {
   $message = "<p align='center'>";
   $message.= "<span style='color:#CC0000'>".VENDU_ZERO."</span>";
   $message.= "<br><a href='verkoop_op_artikel.php?action=reset&confirm=yes'>".OUI."</a> | <a href='verkoop_op_artikel.php'>".NON."</a>";
   $message.= "</p>";
}
if(isset($_GET['confirm']) and $_GET['confirm']=="yes") {
   mysql_query("UPDATE products SET products_sold='0'");
   $message = "<p align='center' style='color:#CC0000'>".UPDATE_OK."</p>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<script type="text/javascript">
function formu() {
<!--
  var error11 = 0;
  var error_message11 = "";

  var id = document.form101.id.value;

  if(document.form101.elements['id'].type != "hidden") {
    if(id == 'none' ) {
      error_message11 = error_message11 + "<?php print VEUILLEZ;?>.\n";
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
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
print '<table border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700"><tr><td align="center">';
   print '&bull;&nbsp;<a href="verkoop_op_artikel.php?action=reset">'.RESET_ITEMS.'</a>';
   if(isset($message)) print $message;
   if(!isset($message)) print '<br><img src="im/zzz.gif" width="1" height="3"><br>';
print '&bull;&nbsp;<a href="verkoop_op_artikel_details.php?id=TOUS">'.RESUME_TOUT_LES_ARTICLES.'</a>';
print '</td></tr>';
print '</table>';


$prods = mysql_query("SELECT products_id, products_name_".$_SESSION['lang']."
                      FROM products
                      WHERE products_ref!='GC100'
                      AND products_sold > '0'
                      ORDER BY products_name_".$_SESSION['lang']."
                      ASC");
$prodsNum = mysql_num_rows($prods);
if($prodsNum > 0) {
print "<br><br><center><table border='0' cellpadding='3' cellspacing='0' width='700' class='TABLE'>";
print "<tr>";
        print "<form action='verkoop_op_artikel_details.php' method='GET' name='form101' onsubmit='return formu()';>";
        print "<td>";
        print "<center>";
        print FOU.": <select name=id>";
                print "<option value='TOUS'>Alles</option>";
                print "<option value='none'>---</option>";
                while ($produit = mysql_fetch_array($prods)) {
                       print "<option value='".$produit['products_id']."'>".strtoupper($produit['products_name_'.$_SESSION['lang']]);
                       print "</option>";
                }
print "</select>&nbsp;&nbsp;<input type='Submit' value='".A2."' class='knop'>";
print "";
print "</td>";
       print "</tr>";
       print "<tr>";
       print "<td cospan='2' align='center'>";
 
       print "</td>";
       print "</form>";
       print "</tr>";
       print "</table>";

print "<br>";

 
function vendu($itemId) {
    $queryVendu = mysql_query("SELECT users_products FROM users_orders WHERE users_payed = 'yes'");
   $queryVenduNum = mysql_num_rows($queryVendu);
   if($queryVenduNum>0) {
        while($rowUp = mysql_fetch_array($queryVendu)) {
            $splitUp = explode(",",$rowUp['users_products']);
				foreach ($splitUp as $item) {
				    $check = explode("+",$item);
				    if($check[0]==$itemId) {
                        $products[]=$check[1];
                    }
                    else {
                        $products[]=0;
                    }
                }
                $sum = array_sum($products);
        }
   }
   return $sum;
}
 

$prodsdd = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", products_ref, products_sold
                      FROM products
                      WHERE products_ref!='GC100'
                      AND products_sold > '0'
                      ORDER BY ".$_GET['orderf']."
                      ".$_GET['c1']."");

print "<table border='0' cellpadding='2' cellspacing='0' align='center' class='TABLE' width='700'>";
print "<tr>";
print "<td></td></tr><tr>";
print "<td class='TD' width='80'><b><a href='".$_SERVER['PHP_SELF']."?orderf=products_ref&c1=".$_GET['c1']."'>Referentie</a></b></td>";
print "<td class='TD'><b><a href='".$_SERVER['PHP_SELF']."?orderf=products_name_".$_SESSION['lang']."&c1=".$_GET['c1']."'>".FOU."</a></b></td>";
print "<td class='TD' align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=products_sold&c1=".$_GET['c1']."'>".VENDU."</a></b></td></tr><tr>";

$c="#F1F1F1";
while ($produita = mysql_fetch_array($prodsdd)) {
       if($c=="#CCFF99") $c = "#FFFFFF"; else $c = "#CCFF99";
       print "<td bgcolor='".$c."'>".$produita['products_ref']."</td>";
       print "<td bgcolor='".$c."'><a href='verkoop_op_artikel_details.php?id=".$produita['products_id']."'>".$produita['products_name_'.$_SESSION['lang']]."</a></td>";
       print "<td bgcolor='".$c."' align='center'>";
       $totalArtSold[] = $produita['products_sold'];
       ($produita['products_sold']>0)? print "<span class='fontrouge'><b>".$produita['products_sold']."</b></span>" : print $produita['products_sold'];
       print "</td>";
       print "</tr>";
}

print "</td>";
print "</tr>";
print "</table>";

    print "<p align='center'><b>".array_sum($totalArtSold)."</b> verkochte artikelen</p>";
}
else {
    print "<p align='center' class='fontrouge'><b>".NO_SUPPLIER."</b></p>";
}

?>
<br><br><br><br>
  </body>
  </html>
