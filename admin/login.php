<?php
session_start();

include('../configuratie/configuratie.php');

//################################
// Anti XSS control
$chaine = strtolower($_SERVER["QUERY_STRING"]);
$searchXSS = array("<",">","'","{","","%3C","%3E",";","alert(","(",")","sql","xss","%3c","%3e","&gt","&lt","exec","select","insert","drop","create","alter","update","frame","script","function");
$replaceXSS = array("","","","","","","","","","","","","","","","","","","","","","","","","","","");
$chaine2 = str_replace($searchXSS,$replaceXSS,strip_tags($chaine));
if(preg_match("/http:/i", $chaine) OR $chaine!==$chaine2) {
    header("Location: ../404.php");
    exit;
}
foreach($_POST AS $key=>$value) $_POST[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value)); //--- Check all POST var
foreach($_GET AS $key=>$value) $_GET[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value));  //--- Check all GET var
//################################

if(isset($_SESSION['user'])) unset($_SESSION['user']);
// QUERY
$query = mysql_query("SELECT admin, motdepasse, admin_2 FROM admin");
$row = mysql_fetch_array($query);

$limitedPsw = explode("|",$row['admin_2']);
if($_POST['admin']==$limitedPsw[0] AND $_POST['motdepasse']==$limitedPsw[1]) {$check1="ok"; $_SESSION['user']="user";} else {$check1="notok";}
if($_POST['admin']==$row['admin'] AND $_POST['motdepasse']==$row['motdepasse']) {$check="ok"; $_SESSION['user']="admin";} else {$check="notok";}

// Redirection
if((isset($check) AND $check=="ok") OR (isset($check1) AND $check1=="ok")) {
	$_SESSION['login'] = $_POST['admin'];
	include("menu_navigatie.php");
	exit;
}
else {
	header("Location:index.php");
}
?>
