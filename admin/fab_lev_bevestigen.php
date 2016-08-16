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
$message = "";
if(empty($_GET['company'])) {
   $check_company = "notok";
   $message .= "<span style='color:red'>".A1."</span>";
}
else {
   $check_company = "ok";
}

if(empty($_GET['ref'])) {
   $check_ref1 = "notok";
   $message .= "<span style='color:red'>".A2."</span>";
}
else {
   $check_ref1 = "ok";
}

if(empty($_GET['stat'])) {
   $check_stat1 = "notok";
   $message .= "<span style='color:red'>".A2A."</span>";
}
else {
   $check_stat1 = "ok";
}

if(!empty($_GET['ref'])) {
    $query2 = mysql_query("SELECT fournisseurs_ref
                          FROM fournisseurs
                          WHERE fournisseurs_ref = '".$_GET['ref']."'");
                          $rows2 = mysql_num_rows($query2);
              if($rows2>0) {
                 $check_ref2 = "notok";
                 $message .= "<span style='color:red'>".A3."</span>";
              }
              else {
              $check_ref2 = "ok";
              }
}

 
if($check_company == "ok" and $check_ref1=="ok" and $check_ref2=="ok" AND $check_stat1=="ok") {
$query = mysql_query("SELECT fournisseurs_company FROM fournisseurs WHERE fournisseurs_company = '".$_GET['company']."'");
$rows = mysql_num_rows($query);

if($rows==0) {
	$_GET['company'] = str_replace("'","&#146;",$_GET['company']);
	$_GET['contact_nom'] = str_replace("'","&#146;",$_GET['contact_nom']);
	$_GET['contact_prenom'] = str_replace("'","&#146;",$_GET['contact_prenom']);
	$_GET['adresse'] = str_replace("'","&#146;",$_GET['adresse']);
	$_GET['ville'] = str_replace("'","&#146;",$_GET['ville']);
	$_GET['pays'] = str_replace("'","&#146;",$_GET['pays']);
	$_GET['note'] = str_replace("'","&#146;",$_GET['note']);

	mysql_query("INSERT INTO fournisseurs
	             (fournisseurs_ref,
	              fournisseurs_company,
	              fournisseurs_firstname,
	              fournisseurs_name,
	              fournisseurs_address,
	              fournisseurs_zip,
	              fournisseurs_city,
	              fournisseurs_pays,
	              fournisseurs_tel1,
	              fournisseurs_tel2,
	              fournisseurs_cel1,
	              fournisseurs_cel2,
	              fournisseurs_fax,
	              fournisseurs_link,
	              fournisseurs_email,
	              fournisseurs_divers,
	              fournisseur_ou_fabricant)
	             VALUES ('".$_GET['ref']."',
	                     '".$_GET['company']."',
	                     '".$_GET['contact_nom']."',
	                     '".$_GET['contact_prenom']."',
	                     '".$_GET['adresse']."',
	                     '".$_GET['cp']."',
	                     '".$_GET['ville']."',
	                     '".$_GET['pays']."',
	                     '".$_GET['tel1']."',
	                     '".$_GET['tel2']."',
	                     '".$_GET['cel1']."',
	                     '".$_GET['cel2']."',
	                     '".$_GET['fax']."',
	                     '".$_GET['website']."',
	                     '".$_GET['email']."',
	                     '".$_GET['note']."',
	                     '".$_GET['stat']."')");

	$message = "<span style='color:red'><b>".$_GET['company']."</b> ".A4."</span>";
}
else {
	$message = "<span style='color:red'>".A5."</span>";
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
<p align="center" class="largeBold"><?php print A6;?></p>

<table border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE" width="700">
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2"><form action="fab_lev_toevoegen.php"><INPUT TYPE="submit" VALUE="<?php print A7;?>" class="knop"></form></td>
       </tr>
</table>
</body>
</html>



