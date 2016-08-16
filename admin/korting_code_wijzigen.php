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
$n = 650;
$c = "";
$message = "";

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

if(isset($_POST['action2_x'])) {
  header("Location: korting_code_verwijderen.php?id=".$_POST['id']);
}

 
if(isset($_POST['action_x'])) {

if(empty($_POST['code'])) {
	$message .= "<br><span style='color:#CC0000'>".A1."</span>"; $checkCode = "notok";
} 
else {
	$checkCode = "ok";
}
if(empty($_POST['reduction']) or !is_numeric($_POST['reduction'])) {
	$message .= "<br><span style='color:#CC0000'>".A2."</span>"; $checkCode = "notok";
}
else {
	$checkReduction = "ok";
}
if(empty($_POST['seuil'])) {
	$_POST['seuil'] = 0;
}
if(!is_numeric($_POST['seuil']) and !empty($_POST['seuil'])) {
	$message .= "<br><span style='color:#CC0000'>".A3."</span>"; $checkSeuil = "notok";}
else {
	$checkSeuil = "ok";
}

 
 
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
                 }
                 else {
                  $message .= "<br><span style='color:#CC0000'>".A4."</span>";
                  $checkDebut = "notok";
                  }
             }
             else {
              $message .= "<br><span style='color:#CC0000'>".A4."</span>";
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
                 }
                 else {
                  $message .= "<br><span style='color:#CC0000'>".A5."</span>";
                  $checkFin = "notok";
                  }
             }
             else {
              $message .= "<br><span style='color:#CC0000'>".A5."</span>";
              $checkFin = "notok";
             }
}

 
               $dateMaxCheck = explode("-",$_POST['fin']);
               $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
               $dateDebutCheck = explode("-",$_POST['debut']);
               $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
               if($dateMax < $dateDebut) {
                  $message .= "<br><span style='color:#CC0000'>".A6."</span>";
                  $OnYVa = "notok";
               }
                else {
               $OnYVa = "ok";
               }
 
if($checkCode == "ok" and $checkReduction == "ok" and $checkDebut == "ok" and $checkFin == "ok" and $OnYVa == "ok") {

      mysql_query("UPDATE code_promo
                   SET
                   code_promo = '".$_POST['code']."',
                   code_promo_start = '".$_POST['debut']."',
                   code_promo_end = '".$_POST['fin']."',
                   code_promo_reduction = '".$_POST['reduction']."',
                   code_promo_type = '".$_POST['type']."',
                   code_promo_enter = '".$_POST['enter']."',
                   code_promo_seuil = '".$_POST['seuil']."',
                   code_promo_items = '".$_POST['idProd']."',
                   code_promo_note = '".addslashes(str_replace("'","´",$_POST['note']))."'
                   WHERE code_promo_id = '".$_POST['id']."'
                   ");
     $message = "<span style='color:#CC0000'><b>".A7."</b></span>";
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
<p align="center" class="largeBold"><?php print A8;?></p>

<?php
$codeQuery = mysql_query("SELECT * FROM code_promo ORDER BY code_promo ASC");
$codeQueryNum = mysql_num_rows($codeQuery);

if($codeQueryNum > 0) {
 
	print "<div align='center'>".A9."</div>";
 
	print "<br>";
	print '<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr>';
	print '<td align="center">';
	print '&bull;&nbsp;<a href="korting_code_versturen.php" target="main"><b>'.ENVOYER_CODE.'</b></a>';
	print '</td></tr>';
	print '</table>';
	
	if(isset($message)) print "<p align='center'>".$message."</p>";
	
	print "<table border='0'  cellpadding='3' cellspacing='0' align='center' class='TABLE'>";
	print "<tr bgcolor='#FFFFFF'>";
	        print "<td align='center' valign='top'><b>Code</b></td>";
	        print "<td width='80' align='left' valign='top'><b>".A10."</b></td>";
	        print "<td align='center' valign='top'><b>".A11."</b> (3)</td>";
 
 
 
			print "<td align='center' valign='top'><b>".A12."</b> (1)</td>";
	        print "<td align='center' valign='top'><b>".A13."</b> (2)</td>";
 
	        print "<td align='left' valign='top'><b>".A20."</b></td>";
	        print "<td align='center' valign='top'><b>".A21."</b></td>";
	        print "<td align='center' valign='top'><b>".IDARTICLE."</b> (4)</td>";
	        print "<td align='center'valign='top'><b>".NOTE_INTERNE."</b></td>";
	        print "<td align='center'>&nbsp;</td>";
	        print "<td align='center'>&nbsp;</td>";
	        print "<td align='center'>&nbsp;</td>";
	print "</tr>";
	
	$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
	
	while ($code = mysql_fetch_array($codeQuery)) {
		if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
 
			unset($vv);
			$dateMaxE = explode("-",$code['code_promo_end']);
			if(count($dateMaxE) > 1) $dateMax = mktime(0,0,0,$dateMaxE[1],$dateMaxE[2],$dateMaxE[0]);
			$v = ($dateMax < $today)? 0 : 1;
			$v1 = ($code['code_promo_stat']=="prive" AND $code['code_promo_enter']>0)? 0 : 1;
			$vv = $v+$v1;
			if($vv!==2) {
				$status3 = "&nbsp;<img src='im/passed.gif' title='".EXPIRE."'>";
				$s1 = "<span style='color:#CC0000'>--</span>";
			}
			else {
				$status3 = "&nbsp;<img src='im/noPassed.gif' title='".ACTIVEV."'>";
				$s1 = "<a href='kortingcode_print.php?cert=".$code['code_promo']."&l=".$_SESSION['lang']."' target='_blank' style='background:none; decoration:none'><img src='../im/print.gif' title='".IMPRIMER."' border='0'></a>";
			}
			$openLeg2 = "<a href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=codeNote&code=".$code['code_promo']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
			$stockLev2 = $openLeg2.LIRE."</a>";
			
		 
			print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
			print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";                          
		 
			print "<td align='left'>";
			print "<input type='text' size='8' class='vullen' name='code' value='".$code['code_promo']."'>";
			print "</td>";
		 
			print "<td align='left'>";
			print "<input type='text' size='4' class='vullen' name='reduction' value='".$code['code_promo_reduction']."'>&nbsp;";
			print "<select name='type'>";
			$sel = ($code['code_promo_type'] == "%")? "selected" : "";
			$sel1 = ($code['code_promo_type'] == $symbolDevise)? "selected" : $sel1="";
			print "<option value='%' ".$sel.">%</option>";
			print "<option value='".$symbolDevise."' ".$sel1.">".$symbolDevise."</option>";
			print "</select>";
			print "</td>";
			print "<input type='hidden' name='id' value='".$code['code_promo_id']."'>";
		 	print "<td align='left'><input type='text' class='vullen' size='6' name='seuil' value='".$code['code_promo_seuil']."'> $symbolDevise</td>";
		  	print "<td align='left'><input type='text' class='vullen' size='12' name='debut' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$code['code_promo_start'])."'></td>";
		  	print "<td align='left'><input type='text' class='vullen' size='12' name='fin' value='".ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$code['code_promo_end'])."'>".$status3."</td>";
 			print "<td align='left'><b>".strtoupper($code['code_promo_stat'])."</b></td>";
 			print "<td align='left'><input type='text' size='3' name='enter' value='".$code['code_promo_enter']."'></td>";
 			$afficheProduits = ($code['code_promo_items']!=="")? "&prod=".$code['code_promo_items'] : "";
			$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=article".$afficheProduits."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
			$stockLev = $openLeg."<img border=0 src=im/help.png align=absmiddle></a>";
			print "<td align='left'><input type='text' size='20' name='idProd' value='".$code['code_promo_items']."'> ".$stockLev."</td>";
 
			print "<td align='left'>";
			// print "<textarea cols='30' rows='2' name='note'>".stripslashes($code['code_promo_note'])."</textarea>";
 
			if($code['code_promo_note']!=="") print "<div align='center'>".$stockLev2."</div>";
			print "</td>";
 
			print "<td align='center'>".$s1."</td>";
 
			print "<td align='center'><input type='image' src='im/update.gif' title='".A15."' name='action' value='".A15."'></td>";
 
			print "<td align='center'><input type='image' src='im/supprimer.gif' title='".A19."' name='action2' value='".A19."'></td>";
			print "</form>";
			print "</tr>";
	}
	print "</table>";
?>
<br>
	<table width="700" border="0" align="center" cellpadding="5" cellspacing = "0" class="TABLE">
	<tr><td><div><?php print A17;?></div></td>
	</tr>
	</table>
	<br>
	
<?php
}
else {
	$message2 = "<span style='color:#CC0000'><b>-- ".AUCUNCODE." --</b></span>";
}

if(isset($message2)) print "<p align='center'><table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align=center>".$message2."</tr></td></table>";
?>
<br><br><br>
</body>
</html>

