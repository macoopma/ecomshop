<?php
session_start();
session_destroy();

Header("X-Powered-by: safe_http");

include('configuratie/configuratie.php');

$chaine = strtolower($_SERVER["QUERY_STRING"]);
$searchXSS = array("<",">","'","{","","%3C","%3E",";","alert(","(",")","sql","xss","%3c","%3e","&gt","&lt","exec","select","insert","drop","create","alter","update","frame","script","function");
$replaceXSS = array("","","","","","","","","","","","","","","","","","","","","","","","","","","");
$chaine2 = str_replace($searchXSS,$replaceXSS,strip_tags($chaine));
if(preg_match("/http:/i", $chaine) OR $chaine!==$chaine2) {
    header("Location: 404.php");
    exit;
}

//--- Controleer - Check all POST var
foreach($_POST AS $key=>$value) $_POST[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value)); 

//--- Controleer - Check all GET var
foreach($_GET AS $key=>$value) $_GET[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value));  

// controleer affiliate
$affiliationCheck = (isset($_GET['eko']))? "?eko=".$_GET['eko'] : "";

// Redirect uitvoeren
if(isset($redirectToShop) AND $redirectToShop=="oui") header("Location: cataloog.php".$affiliationCheck);

// interface kleur
if(isset($_POST['userInterface1'])) $_SESSION['userInterface'] = $_POST['userInterface1'];
if(empty($_SESSION['userInterface'])) $_SESSION['userInterface'] = $colorInter;
$title = "";

include('includes/doctype.php');
?>

<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="250" border="0" cellspacing="0" cellpadding="0" align="center" height="90%">
  <tr>
    <td valign="middle">

<!-- start code -->
<p align="center">
<?php if($logo!=="noPath") print '<img src="'.$logo.'">';?>
<p align="center"><a href="cataloog.php"><img src="im/shop-binnen.png" border="0" alt="" title=""></a></p>
</p>
<!-- einde code -->
    
<?php
if($selectLang=="oui") {
?>
      <p>
      <table border="0" cellspacing="5" cellpadding="0" align="center"><tr>
      <form action="cataloog.php<?php print $affiliationCheck;?>" method="POST">
      <td align="center">
      <p align="center">Nederlands - Fran&ccedil;ais - English</p>
      <select name="lang1" size="3">
      <option class="grey" value="3">Nederlands</option>
      <option class="grey" value="1">Fran&ccedil;ais</option>
      <option class="grey" value="2">English</option>

      </select>
      </td></tr><tr><td align="center">
      <input type="submit" value="Ga verder - Entrez - Enter the shop">
      </td>
      </form>
      </tr>
      </table>
      </p>
<?php }?>
</td>
</tr>
</table>
</body>
</html>
