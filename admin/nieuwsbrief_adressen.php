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

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
if(isset($_GET['action']) and $_GET['action'] == "deleteAll") {
   $mess = ETES_VOUS_SUR;
   if(isset($_GET['confirm']) and $_GET['confirm'] == "yes") {
      mysql_query("TRUNCATE TABLE newsletter");
      $mess = LIST_SUPPRIME;
   }
}

if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}

 
if(isset($_POST['action']) AND $_POST['action']=='desactiver' AND isset($_POST['checkCom'])) {
   foreach($_POST['checkCom'] AS $item) {
      $z = explode('|', $item);
      $clientZ[] = $z[1];
      $idZ[] = $z[0];
   }
   $ids = implode(', ', $idZ);
   $clients = implode(', ', $clientZ);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<span style='color:#FF0000'>".DESACTIVER." ID <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='desactivate'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<input type='hidden' name='who' value='".$clients."'>";
   $alertMessage.= "<div align='center'><input type='submit' name='confirm' class='knop' value='".OUI."'> | <input type='submit' name='confirm' class='knop' value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table>";
}
 
if(isset($_POST['do']) AND $_POST['do']=="desactivate" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayClient = explode(', ', $_POST['who']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("UPDATE newsletter SET newsletter_active='no', newsletter_statut='out' WHERE newsletter_id = '".$rrArray[$i]."'");
      if($rrArrayClient[$i]!=="--") mysql_query("UPDATE users_pro SET users_pro_news='no' WHERE users_pro_password = '".$rrArrayClient[$i]."'");
   }
}

 
if(isset($_POST['action']) AND $_POST['action']=='activer' AND isset($_POST['checkCom'])) {
   foreach($_POST['checkCom'] AS $item) {
      $z = explode('|', $item);
      $clientZ[] = $z[1];
      $idZ[] = $z[0];
   }
   $ids = implode(', ', $idZ);
   $clients = implode(', ', $clientZ);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<span style='color:#FF0000'>".ACTIVER." ID <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='activate'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<input type='hidden' name='who' value='".$clients."'>";
   $alertMessage.= "<div align='center'><input type='submit' name='confirm' class='knop' value='".OUI."'> | <input type='submit' name='confirm' class='knop' value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table>";
}
 
if(isset($_POST['do']) AND $_POST['do']=="activate" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayClient = explode(', ', $_POST['who']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("UPDATE newsletter SET newsletter_active='yes', newsletter_statut='active' WHERE newsletter_id = '".$rrArray[$i]."'");
      if($rrArrayClient[$i]!=="--") mysql_query("UPDATE users_pro SET users_pro_news='yes' WHERE users_pro_password = '".$rrArrayClient[$i]."'");
   }
}

 
if(isset($_POST['action']) AND $_POST['action']=='supprimer' AND isset($_POST['checkCom'])) {
   foreach($_POST['checkCom'] AS $item) {
      $z = explode('|', $item);
      $clientZ[] = $z[1];
      $idZ[] = $z[0];
   }
   $ids = implode(', ', $idZ);
   $clients = implode(', ', $clientZ);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td>";
   $alertMessage.= "<span style='color:#FF0000'>".A5." ID <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='delete'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<input type='hidden' name='who' value='".$clients."'>";
   $alertMessage.= "<div align='center'><input type='submit' name='confirm' class='knop' value='".OUI."'> | <input type='submit' name='confirm' class='knop'  value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table>";
}
 
if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayClient = explode(', ', $_POST['who']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("DELETE FROM newsletter WHERE newsletter_id='".$rrArray[$i]."'");
      if($rrArrayClient[$i]!=="--") mysql_query("UPDATE users_pro SET users_pro_news='no' WHERE users_pro_password = '".$rrArrayClient[$i]."'");
   }
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
<p align="center" class="largeBold"><?php print A1A;?><br><?php print A1;?></p>

<?php
if(isset($mess)) print "<p align='center' class='fontrouge'><b>".$mess."</b></p>";

if(!isset($_GET['ord'])) $_GET['ord']="newsletter_active";
if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if(isset($_GET['c1']) and $_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

$querySent = mysql_query("SELECT * FROM newsletter ORDER BY ".$_GET['ord']." ".$_GET['c1']."");
$message1Count = mysql_num_rows($querySent);

if($message1Count>0) {

if(isset($selChecked) AND $selChecked=='checked') {
   $imChecked = "<img src='im/checked.gif' border='0'>";
}
else {
   $imChecked = "<img src='im/checkedno.gif' border='0'>";
}

print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";

print "<table border='0' align='center'  cellspacing='0' cellpadding='3' class='TABLE' width='700'>";
print "<tr height='35' style='background-color:#FFFFFF'>";
        print "<td align='center'><b>#</b></td>";
        print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_id&c1=".$_GET['c1']."'><b>ID</b></a></td>";
        ## <a href='".$_SERVER['PHP_SELF']."?sel=".$selChecked."'>".$imChecked."</a>
        print "<td align='left' class='fontgris'><input name='tout' type='checkbox' checked onClick='this.value=check(this.form);'></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_email&c1=".$_GET['c1']."'><b>E-mail</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_password&c1=".$_GET['c1']."'><b>Wachtwoord</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_langue&c1=".$_GET['c1']."'><b>".A2."</b></a></td>";
        print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_active&c1=".$_GET['c1']."'><b>".ACTIV."</b></a></td>";
        print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_statut&c1=".$_GET['c1']."'><b>Status</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_date_added&c1=".$_GET['c1']."'><b>".A4."</b></a></td>";
        print "<td align='left'><a href='".$_SERVER['PHP_SELF']."?ord=newsletter_nic&c1=".$_GET['c1']."'><b>".A7."</b></a></td>";
        print "</tr>";
        $i=1;
        $c="";
        while($messageStock = mysql_fetch_array($querySent)) {
               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
                $queryFindIdOrder = mysql_query("SELECT users_id FROM users_orders WHERE users_nic='".$messageStock['newsletter_nic']."'");
                $reaultFindIdOrder = mysql_fetch_array($queryFindIdOrder);
                $idNic = $reaultFindIdOrder['users_id'];
               
               
                    if(!empty($messageStock['newsletter_nic'])) {
                        $customer = $messageStock['newsletter_nic'];
                    }
                    else {
                        $customer = '--';
                    }

               if($messageStock['newsletter_active'] == "yes") {
                  $newsletter_email = "<b>".$messageStock['newsletter_email']."</b>";
                  $newsletter_active = "<span class='fontrouge'><b>".OUI."</b></span>";
                  $c="#CCFF66";
               }
               else {
                  $newsletter_email = $messageStock['newsletter_email'];
                  $newsletter_active = NON;
               }

            print "<tr>";
            print "<td bgcolor='".$c."' align='center'><i>".$i++."</i></td>";
            print "<td bgcolor='".$c."' align='center'>".$messageStock['newsletter_id']."</td>";
            print "<td bgcolor='".$c."' align='center'><input type='checkbox' name='checkCom[]' value='".$messageStock['newsletter_id']."|".$customer."' ".$selChecked."></td>";
            print "<td bgcolor='".$c."' align='left'>".$newsletter_email."</td>";
            print "<td bgcolor='".$c."' align='left'>".$messageStock['newsletter_password']."</td>";
            print "<td bgcolor='".$c."' align='center'>".$messageStock['newsletter_langue']."</td>";
            print "<td bgcolor='".$c."' align='center'>".$newsletter_active."</td>";
            print "<td bgcolor='".$c."' align='center'>".$messageStock['newsletter_statut']."</td>";
            print "<td bgcolor='".$c."' align='left'>".dateFr($messageStock['newsletter_date_added'],$_SESSION['lang'])."</td>";
            if($customer == '--') {
                print "<td bgcolor='".$c."' align='left'>".$customer."</td>";
            }
            else {
                print "<td bgcolor='".$c."' align='left'><a href='klant_accounts_beheren.php?idPass=".$customer."&from=newsletterTable&who=Newsletter'>".$customer."</a></td>";
            }
            print "</tr>";
        }
print "</table>";

if(isset($alertMessage)) print $alertMessage;

if(!isset($hide) OR $hide!=='yes') {
print "<p align='center'>";
print "<select name='action'>";
print "<option value='activer'>".ACTIVER."</option>";
print "<option value='desactiver'>".DESACTIVER."</option>";
print "<option value='supprimer'>".A5."</option>";
print "</select>";
print "&nbsp;&nbsp;<input type='submit' class='knop' value='   OK   '>";
print "</p>";
}
print "</form>";


print "<p align='center'><br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><center>";
print "| <a href='export_nieuwsbrief.php'><b>".EXPORT."</b></a> | ";
print "<a href='nieuwsbrief.php?todo=import'><b>".IMPORT."</b></a> | ";
if($message1Count>0) {print "<a href='nieuwsbrief_adressen.php?action=deleteAll'><b>".DELETE."</b></a> | ";}
print "</tr></td></table>";

print "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><left>".A6."</tr></td></table>";
}
else {
   print "<p align='center' class='fontrouge'><b>".AUCUN."</b></p>";
}
?>
<br><br><br>
  </body>
  </html>
