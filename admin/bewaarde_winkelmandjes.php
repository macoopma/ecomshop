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

$c = "";
$auj = date("Y-m-d");
if(empty ($_GET['orderf']))  $_GET['orderf'] = "users_caddie_date";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if($_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}

 
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


 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
$hids = mysql_query("SELECT * FROM users_caddie ORDER BY ".$_GET['orderf']." ".$_GET['c1']."");
 
if(isset($_GET['display']) AND $_GET['display']=='expire') {
	$hids = mysql_query("SELECT * FROM users_caddie 
						WHERE TO_DAYS(NOW()) - TO_DAYS(users_caddie_date) > '".$saveCart."'
						AND users_caddie_client_number = ''
						ORDER BY ".$_GET['orderf']." ".$_GET['c1']."");
}
$resultcsNum = mysql_num_rows($hids);

 

if(isset($_POST['action']) AND $_POST['action']=='supprimer' AND isset($_POST['checkCom'])) {
   $ids = implode(', ', $_POST['checkCom']);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<span style='color:#FF0000'>".SUPPRIMER." ID# <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='delete'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<div align='center'><input type='submit' class='knop' name='confirm' value='".OUI."'> | <input type='submit' class='knop' name='confirm' value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table>";
   $ids2 = $_POST['checkCom'];
}
  
if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("DELETE FROM users_caddie WHERE users_caddie_id='".$rrArray[$i]."'");
      $idz[] = $rrArray[$i];
   }
   $ids = implode(', ', $idz);
   (count($idz)>1 AND $_SESSION['lang']==1)? $s="s" : $s="";
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<center><span style='color:red'>".PANIER." ID <b>".$ids."</b> ".SUPPRIMEEEE.$s."</span>";
   $alertMessage.= "</td></tr></table>";
   header("Location: bewaarde_winkelmandjes.php?alertMessage=".$alertMessage);
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
var checkflag = "true";
function check(field) {
	if (checkflag == "false") {
		for (i = 0; i < field.length; i++) {
		field[i].checked = true;}
		checkflag = "true";
		return "Tout décocher";
	}
	else {
		for (i = 0; i < field.length; i++) {
		field[i].checked = false; }
		checkflag = "false";
		return "Tout cocher";
	}
}
//  End -->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div  align="center" class="largeBold"><?php print A1;?></div>
<br>

<?php
print "<div  align='center'> ".dateFr($auj,$_SESSION['lang'])."</div>";
print "<p align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
if(!isset($_GET['display'])) print "<center><a href='bewaarde_winkelmandjes.php?display=expire'>".AFFICHER_EXPIRES."</a>";
if(isset($_GET['display']) AND $_GET['display']=='expire') print "<center><a href='bewaarde_winkelmandjes.php'>".AFFICHER_TOUS."</a>";
print "</tr></td></table>";
 

if(isset($_GET['action']) AND $_GET['action'] == "delete") {
print "<br>";
print "<table border='0' cellpadding='10' cellspacing='0' align='center' width='700' class='TABLE'><tr><td>";
print "<div align='center'>".A13.": <b>".$_GET['id']."</b> ?</div>";
print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?id=".$_GET['id']."&action=delete&confirm=yes'><b>".A14."</b></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='".$_SERVER['PHP_SELF']."'><b>".A15."</b></a></div>";
print "<td></tr></table>";
print "<br>";
	if((isset($_GET['action']) and $_GET['action'] == "delete") and (isset($_GET['confirm']) and $_GET['confirm']=="yes")) {
		mysql_query("DELETE FROM users_caddie WHERE  users_caddie_number = '".$_GET['id']."'");
		print "<script type='text/javascript'>
				<!--
				document.location='bewaarde_winkelmandjes.php';
				//-->
				</script>";
	}
}

 
if(isset($_GET['action']) AND $_GET['action'] == "view") {

function displayOrder($id_session, $cadNum, $cadName, $dateAdded) {
	GLOBAL $_GET;
	$split = explode(",",$id_session);
	print "<center><table border='0' cellpadding='10' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align='center'>";
	print "<div style='background-color:#EEEEEE; padding:8px; border:#CCCCCC 1px double;'>";
	print "<b>".A40."</b> ".$cadNum." | <b>".A4."</b> ".$cadName." | <b>".A5."</b> ".dateFr($dateAdded,$_SESSION['lang']);
	print "</div>";
	
	print "<hr width='100%'>";
	foreach ($split as $item) {
    $check = explode("+",$item);
    
    if(!empty($check[6])) {

   		$_opt = explode("|",$check[6]);
		## session update option price
		$lastArray = $_opt[count($_opt)-1];
		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
		$ww = implode("|",$_opt);
		$adr = str_replace("|","<br>",$ww);
	}
	else {
		$adr = strtolower(A15);
	}
        print "<div align='left'>";
        print "<b>Ref</b> ".strtoupper($check[3])."<br>";
        print "<b>".A10."</b> ".$check[4]."<br>";
        print "<b>Opties</b> ".$adr."<br>";
        print "<b>".A11."</b> ".$check[2]."<br>";
        print "<b>".A12."</b> ".$check[1]."<br>";
        print "</div>";
        print "<hr width='50%'>";
    }
    print "<td></tr></table>";
}
	$viewOrder = mysql_query("SELECT  users_caddie_session, users_caddie_number, users_caddie_password, users_caddie_date FROM users_caddie WHERE  users_caddie_id = ".$_GET['id']."");
	$viewOrderDisplay = mysql_fetch_array($viewOrder);
	print "<br>";
	print displayOrder($viewOrderDisplay['users_caddie_session'], $viewOrderDisplay['users_caddie_number'], $viewOrderDisplay['users_caddie_password'],$viewOrderDisplay['users_caddie_date']);
}









if(isset($alertMessage) OR (isset($_GET['alertMessage']) AND !empty($_GET['alertMessage']))) {
	if(isset($_GET['alertMessage'])) $alertMessage = $_GET['alertMessage'];
	print $alertMessage;
}

if($resultcsNum >0) {
print "<br>";
	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE' width='700'>";
	print "<tr bgcolor='#FFFFFF' height='35'>";
			print "<td align='center' class='fontgris'><input name='tout' type='checkbox' checked onClick='this.value=check(this.form);'></td>";
			print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?orderf=users_caddie_id&c1=".$_GET['c1']."'>ID</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=users_caddie_client_number&c1=".$_GET['c1']."'>".A100."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf= users_caddie_email&c1=".$_GET['c1']."'>".A3."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=users_caddie_number&c1=".$_GET['c1']."'>".A40."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf=users_caddie_password&c1=".$_GET['c1']."'>".A4."</a></b></td>";
	        print "<td align='left'><b><a href='".$_SERVER['PHP_SELF']."?orderf= users_caddie_date&c1=".$_GET['c1']."'>".A5."</a></b></td>";
	        print "<td align='left'><b>".A6."</b></td>";
	        print "<td align='center'><b>".A900."</b></td>";
	        print "<td align='center'>&nbsp;</td>";
	        print "<td align='center'>&nbsp;</td>";
	print "</tr>";
	
	while($myhid = mysql_fetch_array($hids)) {
		if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
		$expExplode = explode("-",$myhid['users_caddie_date']);
		$dateExp = mktime(0,0,0,$expExplode[1],$expExplode[2]+$saveCart,$expExplode[0]);
		$dateExpiration = date("Y-m-d",$dateExp);
		$dateExp3 = dateFr($dateExpiration,$_SESSION['lang']);
	         
		if($dateExpiration < $auj) {
			$dateExp3 = "<span style='color:red'>".$dateExp3."</span>";
			$statut = "<img src='im/passed.gif' title='".EXP."'>";
		}
		else {
			$statut = "<img src='im/noPassed.gif' title='".ACT."'>";					
		}
		if(empty($myhid['users_caddie_client_number'])) {
			$clientNumb =  '--';
			$yoyoyo = "--";
		}
		else {
			$clientNumb = $myhid['users_caddie_client_number'];
			$yoyoyo = "<a href='mijnklant.php?id=".$clientNumb."'>".$clientNumb."</a>";
			$dateExp3 = "--";
			$statut = "<img src='im/noPassed.gif' title='".ACT."'>";
		}
		if(isset($_GET['id']) AND $myhid['users_caddie_id']==$_GET['id']) $c="#FFFF00";
		if(isset($ids2) AND in_array($myhid['users_caddie_id'], $ids2)) $c="#FFCC00";
		if(isset($_GET['id']) AND $myhid['users_caddie_number']==$_GET['id'] AND isset($_GET['action']) AND !empty($_GET['action'])) $c="#FFCC00";
		
                    print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
                    print "<td bgcolor='".$c."' align='center'><input type='checkbox' name='checkCom[]' value='".$myhid['users_caddie_id']."' ".$selChecked."></td>";
                    print "<td align='center'>".$myhid['users_caddie_id']."</td>";
                    print "<td align='left'>".$yoyoyo."</td>";
                    print "<td align='left'><a href='mailto:".$myhid['users_caddie_email']."'>".$myhid['users_caddie_email']."<a></td>";
                    print "<td align='left'>".$myhid['users_caddie_number']."</td>";
                    print "<td align='left'>".$myhid['users_caddie_password']."</td>";
                    print "<td align='left'>".dateFr($myhid['users_caddie_date'],$_SESSION['lang'])."</td>";
                    print "<td align='left'>".$dateExp3."</td>";
                    print "<td align='center'>".$statut."</td>";
                    print "<td align='center'><a style='background:none; text-decoration:none;' href='".$_SERVER['PHP_SELF']."?action=view&id=".$myhid['users_caddie_id']."'><img src='im/details.gif' border='0' title='".A7."'></a></td>";
                    print "<td align='center'><a style='background:none; text-decoration:none;' href='".$_SERVER['PHP_SELF']."?action=delete&id=".$myhid['users_caddie_number']."'><img src='im/supprimer.gif' border='0' title='".A8."'></a></td>";
                    print "</tr>";
	}
	print "</table>";

if(!isset($hide) OR $hide!=='yes') {
print "<p align='center'>";
print "<select name='action'>";
print "<option value=''> ".SELECT." </option>";
print "<option value=''>--</option>";
print "<option value='supprimer'>".SUPPRIMER."</option>";
print "</select>";
print "&nbsp;&nbsp;<input type='submit' value='  OK  ' class='knop'>";
print "</p>";
}
	print "</form>";
}
else {
    print "<br><br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
    print "<tr bgcolor='#FFFFFF'>";
    print "<td align='center'><b><p align='center' class='fontrouge'>".A9."</b></td>";
    print "</tr>";
    print "</table><br><br><br>";
}
?>
</body>
</html>
