<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");
include('../configuratie/configuratie.php');

$_GET['company'] = str_replace("'","&#146;",$_GET['company']);
$_GET['contact2'] = str_replace("'","&#146;",$_GET['contact2']);
$_GET['contact1'] = str_replace("'","&#146;",$_GET['contact1']);
$_GET['address'] = str_replace("'","&#146;",$_GET['address']);
$_GET['city'] = str_replace("'","&#146;",$_GET['city']);
$_GET['country'] = str_replace("'","&#146;",$_GET['country']);
$_GET['divers'] = str_replace("'","&#146;",$_GET['divers']);

mysql_query("UPDATE fournisseurs
             SET
              fournisseurs_ref = '".$_GET['ref']."',
              fournisseurs_company = '".$_GET['company']."',
              fournisseurs_firstname = '".$_GET['contact1']."',
              fournisseurs_name = '".$_GET['contact2']."',
              fournisseurs_address = '".$_GET['address']."',
              fournisseurs_zip = '".$_GET['zip']."',
              fournisseurs_city = '".$_GET['city']."',
              fournisseurs_pays = '".$_GET['country']."',
              fournisseurs_tel1 = '".$_GET['tel1']."',
              fournisseurs_tel2 = '".$_GET['tel2']."',
              fournisseurs_cel1 = '".$_GET['cel1']."',
              fournisseurs_cel2 = '".$_GET['cel2']."',
              fournisseurs_fax = '".$_GET['fax']."',
              fournisseurs_link = '".$_GET['website']."',
              fournisseurs_email = '".$_GET['email']."',
              fournisseurs_divers = '".$_GET['divers']."'
              WHERE fournisseurs_id = '".$_GET['id']."'
            ");
print "<script language=\"javascript\">document.location='fab_lev_wijzigen.php';</script>";
?>