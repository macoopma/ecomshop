<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

$promo = explode("?",$_GET['id']);
$_GET['id'] = $promo[0];

if($promo[1] !== "") {
   $message = "<span class='fontrouge'>".A1."</span>"
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A2;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" colspan="2"><?php print $message;?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
            <FORM action="promoties_toevoegen.php" method="GET">
            <INPUT TYPE="submit" class="knop" class="knop" VALUE="<?php print A3;?>"></form>
        </td>
       </tr>
</table>

</body>
</html>




<?php
}
else {
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A2;?></p>

<form action="promoties_details_ok.php" method="GET" target='main'>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<?php
include('../configuratie/configuratie.php');

$prods = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_price, products_deee FROM products WHERE products_id = '".$_GET['id']."'");
$prod = mysql_fetch_array($prods);
$c1 = "#FFFFFF";
$c2 = "#FFFFFF";
?>

       <tr bgcolor=<?php print $c2;?>>
        <td width="200"><?php print A4;?></td>
        <td>
                <?php print "<b>".$prod['products_name_'.$_SESSION['lang']]."</b>";
                ?>
        </td>
        </tr>
        <tr bgcolor=<?php print $c1;?>>
        <td width = "200"><?php print A5;?></td>
        <td>
        <?php print $prod['products_price'];?>&nbsp;&nbsp;<?php print $devise;?>
        <?php
 
        if($prod['products_deee']>0) {print "<br><span style='color:#00CC00'>Eco-taks: ".$prod['products_deee']." ".$devise."</span>";}
        ?>
        </td>
        </tr>

        <tr bgcolor=<?php print $c2;?>>
        <td width = "200"><?php print A6;?></td>
        <td ><input type="text" class="vullen" size="3" name="soldeDe">&nbsp;%</td>
        </tr>

        <tr bgcolor=<?php print $c2;?>>
        <td width = "200"><?php print A7;?></td>
        <td><input type="text" class="vullen" size="10" name="prixPromo">&nbsp;&nbsp;<?php print $devise;?></td>
        </tr>

        <tr bgcolor=<?php print $c1;?>>
        <td width = "200"><?php print A8;?>(1)</td>
        <td><input type="text" class="vullen" size="15" name="dateDebutPromo"><br>
        <?php print A9;?>: <?php print date("d-m-Y");?></td>
        </tr>

        <tr bgcolor=<?php print $c1;?>>
        <td width = "200"><?php print A10;?>(2)</td>
        <td><input type="text" class="vullen" size="15" name="dateFinPromo"><br>
        <?php print A9;?>: <?php print date("d-m-Y");?></td>
        </tr>

        <tr bgcolor=<?php print $c2;?>>
        <td width = "200"><?php print A11;?></td>
        <td> <?php print A12;?> <input type="radio" value="yes" name="visu" checked>
                           <?php print A13;?> <input type="radio" value="no" name="visu"></td>
       </tr>
       <input type="hidden" value="<?php print $prod['products_price'];?>" name="productPrice">
       <input type="hidden" value="<?php print $_GET['id'];?>" name="id">
       <input type="hidden" value="<?php print $prod['products_name_'.$_SESSION['lang']];?>" name="productName">
</table>

<br>

<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr>
<td align="center" height="40"><input type="submit" class="knop" value="<?php print A14;?>"></td>
</tr>
</table>

</form>


<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
<tr><td>
        <div class="fontrouge">
        <b>(1)</b>: <?php print A15;?>
        </div>
        <div>
        <br>
        <b>(2)</b>: <?php print A16;?>
        </div>
        </td>
</tr>
</table>


  </body>
  </html>

<?php }?>
