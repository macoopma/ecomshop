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
 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
$c = "";
if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}

// aantal lijnen
$nbre_ligne_pro = 30;
if(!isset($_GET['page'])) $_GET['page']=0;

// Sorteren op
if(isset($_GET['orderf'])) $_SESSION['orderf'] = $_GET['orderf'];
if(!isset($_GET['orderf'])) $_SESSION['orderf'] = "users_pro_id";
if(isset($_SESSION['orderf']) AND $_SESSION['orderf']!=="") $orderUU = "&orderf=".$_SESSION['orderf']; else $orderUU = "";

 
if(!isset($_GET['c1'])) {
   if(isset($_GET['c2'])) {
      $_SESSION['c1'] = $_GET['c2'];
   }
   else {
      $_SESSION['c1']="DESC";
   }
}
if(isset($_GET['c1']) AND $_GET['c1']=="DESC" AND !isset($_GET['c2'])) $_SESSION['c1']="ASC";
if(isset($_GET['c1']) AND $_GET['c1']=="ASC" AND !isset($_GET['c2'])) $_SESSION['c1']="DESC";
$_SESSION['c2'] = $_SESSION['c1'];

// zoeken
if(isset($_GET['searchClient']) AND !empty($_GET['searchClient'])) $_POST['searchClient'] = $_GET['searchClient'];
if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
   $searchReq  = "AND (";
   $searchReq  .= "users_pro_password like '%".$_POST['searchClient']."%' 
                  OR users_pro_company like '%".$_POST['searchClient']."%'
                  OR users_pro_city like '%".$_POST['searchClient']."%'
                  OR users_pro_postcode like '%".$_POST['searchClient']."%'
                  OR users_pro_telephone like '%".$_POST['searchClient']."%'
                  OR users_pro_tva like '%".$_POST['searchClient']."%'
                  OR users_pro_firstname like '%".$_POST['searchClient']."%'
                  OR users_pro_email like '%".$_POST['searchClient']."%'
                  OR users_pro_fax like '%".$_POST['searchClient']."%'
                  OR users_pro_lastname like '%".$_POST['searchClient']."%'";
   $searchReq  .= ")";
}
else {
   $searchReq = "";
}
 
if(isset($_GET['idPass']) AND !empty($_GET['idPass'])) {
$searchReq  = "AND (";
   $searchReq  .= "users_pro_password like '%".$_GET['idPass']."%'";
   $searchReq  .= ")";
}

// ------------------
// verwijderen
// ------------------
if(isset($_POST['action']) AND $_POST['action']=='supprimer' AND isset($_POST['checkCom'])) {
   $ids = implode(', ', $_POST['checkCom']);
   $hide = 'yes';
   $alertMessage = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align=center>";
   $alertMessage.= "<span style='color:#FF0000'>".SUPPRIMER." ID# <b>".$ids."</b>?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='delete'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<div align='center'><input type='submit' class='knop' name='confirm' value='".OUI."'> | <input type='submit' name='confirm' class='knop' value='".NON."'></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table><br>";
   $ids2 = $_POST['checkCom'];
}
// verwijderen 2 
if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("DELETE FROM users_pro WHERE users_pro_id='".$rrArray[$i]."'");
      $idz[] = $rrArray[$i];
   }
   $ids = implode(', ', $idz);
   (count($idz)>1 AND $_SESSION['lang']==1)? $s="s" : $s="";
   $alertMessage = "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td align='center'>";
   $alertMessage.= "<center><span style='color=red'>".PANIER." ID <b>".$ids."</b> ".SUPPRIMEEEE."</span><br><br>";
   $alertMessage.= "</td></tr></table><br>";
   header("Location: klant_accounts_beheren.php?alertMessage=".$alertMessage);
}

 
$hidsQuery = "SELECT * FROM users_pro WHERE 1 ".$searchReq." ORDER BY ".$_SESSION['orderf']." ".$_SESSION['c1'];
$hids = mysql_query($hidsQuery);
$resultcsNum = mysql_num_rows($hids);

if($searchReq=="" AND !isset($_GET['qt'])) {
$hidsQuery .=  " LIMIT ".$_GET['page'].",".$nbre_ligne_pro;
$hids=mysql_query($hidsQuery);
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>

<STYLE type="text/css">
<!--
A:link          {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; TEXT-DECORATION: none}
A:active        {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; TEXT-DECORATION: none}
A:visited       {BACKGROUND: none; COLOR: #000000; FONT-SIZE: 10px; TEXT-DECORATION: none}
A:hover         {BACKGROUND: #FFFFFF; COLOR: #000000; FONT-SIZE: 10px; TEXT-DECORATION: underline}
-->
</STYLE>
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
<p  align="center" class="largeBold"><?php print A1;?></p>

<?php
	print "<table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr>";
	print "<td align='center'>";
    print "&bull;&nbsp;<a href='x_exporteer_accounts.php'>".EXPORT."</a>";
    print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	print "&bull;&nbsp;<a href='import/index.php'>".IMPORT."</a>";
    print "<br><img src='im/zzz.gif' width='1' height='5'><br>";
	print "&bull;&nbsp;<a href='import/index.php'>".SUPRIMER."</a>";
	print "</td></tr></table>";

// zoeken
print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<p align='center'>";
print "<table border='0' width='700' align='center' cellpadding='5' align='center' cellspacing='0' class='TABLE'>";
print "<tr><td align=center>";
print "<input type='text' class='vullen' size='30' name='searchClient' value=''>";
print "&nbsp;";
print "<input type='submit' class='knop' value='".CHERCHER."'>";
print "&nbsp;";
print "<a style='background:none' href='javascript:void(0);' onClick=\"window.open('infos.php?from=client','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=180,width=360,toolbar=no,scrollbars=no,resizable=yes');\">";
print "<img border=0 src=im/help.png align=absmiddle>";
print "</a>";
print "</tr></td></table>";
print "</form>";

 
if(isset($alertMessage) OR (isset($_GET['alertMessage']) AND !empty($_GET['alertMessage']))) {
	if(isset($_GET['alertMessage'])) $alertMessage = $_GET['alertMessage'];
	print $alertMessage;
}

// account verwijderen
if(isset($_GET['action']) and $_GET['action'] == "delete") {

if(isset($_GET['qt']) AND $_GET['qt']=="all") $pageCl = "?qt=all"; else $pageCl="?page=".$_GET['page'];
if(isset($_GET['searchClient']) AND !empty($_GET['searchClient'])) $_searchAccount = "&searchClient=".$_GET['searchClient']; else $_searchAccount = "";
if(isset($_GET['c1'])) $searchC11 = "&c1=".$_GET['c1']; else $searchC11 = "";
if(isset($_GET['c2'])) $searchC2 = "&c2=".$_GET['c2']; else $searchC2 = "";
if(isset($_SESSION['orderf']) AND $_SESSION['orderf']!=="") $orderUU = "&orderf=".$_SESSION['orderf']; else $orderUU = "";

print "<table border='0' width='700' align='center' cellpadding='5' cellspacing='0' class='TABLE'><tr><td align=center>
<div align='center' style='color:#CC0000'>".A13." <b>".$_GET['id']."</b> ?</div>";
print "<p align='center' style='color:#CC0000'>";
print "<a href='klant_accounts_beheren.php".$pageCl."&id=".$_GET['id']."&action=delete&confirm=yes".$_searchAccount.$searchC11.$orderUU.$searchC2."'><b>".OUI."</b></a>
		   &nbsp;|&nbsp;
	     <a href='klant_accounts_beheren.php".$pageCl.$_searchAccount.$searchC11.$orderUU.$searchC2."'><b>".NON."</b></a>";
print "</tr></td></table><br><br>";

	if((isset($_GET['action']) and $_GET['action'] == "delete") and (isset($_GET['confirm']) and $_GET['confirm']=="yes")) {
       mysql_query("DELETE FROM users_pro WHERE  users_pro_password = '".$_GET['id']."'");
       $queryNewsletter = mysql_query("SELECT newsletter_id FROM newsletter WHERE newsletter_nic='".$_GET['id']."'");
       if(mysql_num_rows($queryNewsletter) > 0) {mysql_query("DELETE FROM newsletter WHERE newsletter_id='".$_GET['id']."'");}
?>
<script language="javascript">
<!--
        document.location='klant_accounts_beheren.php<?php print $pageCl.$_searchAccount.$searchC11.$orderUU.$searchC2;?>';
//-->
</script>
<?php	}
}

function translateOrder($t) {
                                    $tt = "";
   if($t == "users_pro_id")         $tt = "ID";
   if($t == "users_pro_date_added") $tt = DATE;
   if($t == "users_pro_password")   $tt = A220;
   if($t == "users_pro_email")      $tt = "Email";
   if($t == "users_pro_lastname")   $tt = NOM;
   if($t == "users_pro_firstname")  $tt = PRENOM;
   if($t == "users_pro_company")    $tt = A3;
   if($t == "users_pro_active")     $tt = A21;
   if($t == "users_pro_reduc")      $tt = A22;
   if($t == "users_pro_payable")    $tt = PAYABLE;
   return $tt;
}

// aantal gevonden
print "<b>".A220."</b>: ".$resultcsNum;
if($resultcsNum > $nbre_ligne_pro AND $searchReq=="" AND !isset($_GET['qt'])) {
   print "<br><b>Sorteren op</b>: ".translateOrder($_SESSION['orderf'])." | ".$_SESSION['c1'];
}
print "</div><br>";

 
	print "<table border='0' width='700' cellpadding='0' align='center' cellspacing='0'>";
	print "<tr><td>";
//-----------
// navigatie
//-----------
if($resultcsNum > $nbre_ligne_pro AND $searchReq=="" AND !isset($_GET['qt'])) {
   $myLine=155;
   $NavNum = "<a href=\"".$_SERVER['PHP_SELF']."?c2=".$_SESSION['c2'].$orderUU."&page=";
   unset($toto);
   print "<table width='100%' border='0' cellspacing='0' cellpadding='3'>";
   print "<tr><td align='left'>";
 
         for($e = 0; $e<=$resultcsNum; $e+=$nbre_ligne_pro) {
           $toto[] = $e;
         }
         if(end($toto)+$nbre_ligne_pro <= $resultcsNum) {
            $toto[]= end($toto)+$nbre_ligne_pro;
         }
  
          $nbr = count($toto);
          if($resultcsNum%$nbre_ligne_pro == 0) $nbr = $nbr-1;
            print "<div>";
               for($z=0; $z<$nbr; $z++) {
                 if($z>0 AND ($z % $myLine) == 0) print "<br>";
                 $debut = $toto[$z];
                 $fin = $debut+$nbre_ligne_pro-1;
                 $debut1 = $debut+1;
                 $fin1 = $fin+1;
                 
                         if($_GET['page']==$debut1-1) {
                           print " <span style='color:#FF0000'>&bull;</span> <b><span class='fontrouge' style='background-color:#000000; color:#FFFF00'>".$debut1."-".$fin1."</span></b>";
                         }
                         else {
                           print " <span style='color:#FF0000'>&bull;</span> ".$NavNum."".$debut."\" class='yo'>".$debut1."-".$fin1."</a>";
                         }
                }
                 print " <span style='color:#FF0000'>&bull;</span> <a href='klant_accounts_beheren.php?qt=all'><b>".TOUS."</b></a>";
            print "</div>";
   print "</td></tr></table>";
}
//---------------
// einde navigatie
//---------------

if($resultcsNum >0) {
if(isset($_GET['qt']) AND $_GET['qt']=="all") $pageCl = "?qt=all"; else $pageCl="?page=".$_GET['page'];
if(isset($_POST['searchClient'])) $searchReq2 = "&searchClient=".$_POST['searchClient']; else $searchReq2 = "";
if(isset($_GET['c1'])) $searchC1 = "&c1=".$_GET['c1']; else $searchC1 = "";
	print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
	print "<table border='0' width='' cellpadding='3' cellspacing='0' align='center' class='TABLE'>";
	print "<tr bgcolor='#FFFFFF'>";
		print "<td align='center' valign='top' class='fontgris'><input name='tout' type='checkbox' checked onClick='this.value=check(this.form);'></td>";
        print "<td align='center' valign='top'><img src='im/zzz.gif'  height='1'><br><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_id&c1=".$_SESSION['c1'].$searchReq2."'>ID</a></b></td>";
        print "<td align='center' valign='top'><img src='im/zzz.gif'  height='1'><br><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_date_added&c1=".$_SESSION['c1'].$searchReq2."'>".DATE."</a></b></td>";
        print "<td align='center' valign='top'><img src='im/zzz.gif'  height='1'><br><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_password&c1=".$_SESSION['c1'].$searchReq2."'>".A100."</a></b></td>";
        print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_email&c1=".$_SESSION['c1'].$searchReq2."'>Email</a></b></td>";
        print "<td align='left' valign='top' width='300'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_lastname&c1=".$_SESSION['c1'].$searchReq2."'>".NOM."</a></b></td>";
//        print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_firstname&c1=".$_SESSION['c1'].$searchReq2."'>".PRENOM."</a></b></td>";
//        print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_company&c1=".$_SESSION['c1'].$searchReq2."'>".A3."</a></b></td>";
        print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_active&c1=".$_SESSION['c1'].$searchReq2."'>".A21."</a></b></td>";
        print "<td align='center' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_tva&c1=".$_SESSION['c1'].$searchReq2."'>".TVA."</a></b></td>";
        print "<td align='center' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_tva_confirm&c1=".$_SESSION['c1'].$searchReq2."'>".TVA_ACTIVATED."</a></b></td>";
        print "<td align='center' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_reduc&c1=".$_SESSION['c1'].$searchReq2."'>".A22."</a></b></td>";
        print "<td align='center' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_aff&c1=".$_SESSION['c1'].$searchReq2."'>".AFFILIE."</a></b></td>";
        print "<td align='center' valign='top'><b><a href='".$_SERVER['PHP_SELF'].$pageCl."&orderf=users_pro_payable&c1=".$_SESSION['c1'].$searchReq2."'>".PAYABLE."</a></b></td>";
        if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {print "<td align='center' valign='top'><img src='im/payed.gif' title='".A40."'></td>";}
        if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {print "<td align='center' valign='top'><img src='im/zzz.gif' width='50' height='1'><br><img src='im/payed2.gif' title='".TOTAL_COMMANDES_PAYEES."'></td>";}
        print "<td align='center' valign='top'>&nbsp;</td>";
        print "<td align='center' valign='top'>&nbsp;</td>";
        print "<td align='center' valign='top'>&nbsp;</td>";
	print "</tr>";
	
	while ($myhid = mysql_fetch_array($hids)) {
	unset($totalOrder);
	               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
	               if($myhid['users_pro_tva']!=="") {$tva = "<img src='im/val.gif' title='".$myhid['users_pro_tva']."'>";} else {$tva = "--";}
                   if($myhid['users_pro_tva']!=="" AND $myhid['users_pro_tva_confirm']=="yes") $tvaConfirm = "<img src='im/val.gif' title='".OUI."'>";
                   if($myhid['users_pro_tva']!=="" AND $myhid['users_pro_tva_confirm']=="no") $tvaConfirm = NON;
                   if($myhid['users_pro_tva']!=="" AND $myhid['users_pro_tva_confirm']=="??") $tvaConfirm = "<span class='fontrouge'><b>??</b></span>";
	               if($myhid['users_pro_tva']=="") $tvaConfirm="--";
	               if($tvaManuelValidation=="non" AND $myhid['users_pro_tva_confirm']!=="no") $tvaConfirm="--";
	               
                   $act = ($myhid['users_pro_active']=="yes")? "<img src='im/noPassed.gif' title='".ACTIVE."'>" : "<img src='im/passed.gif' title='".DESACTIVE."'>";
                   if(isset($_GET['qt']) AND $_GET['qt']=="all") $pageCl = "&qt=all"; else $pageCl="&page=".$_GET['page'];
	               if($myhid['users_pro_payable'] == 0) {$payable = CASH;}
	               if($myhid['users_pro_payable'] == 30) {$payable = _30_DAYS;}
	               if($myhid['users_pro_payable'] == 60) {$payable = _60_DAYS;}
	               if($myhid['users_pro_payable'] == 90) {$payable = _90_DAYS;}
	               if(empty($myhid['users_pro_company'])) $entrep = "--"; else $entrep = $myhid['users_pro_company'];
	               $commandeRequest = mysql_query("SELECT users_total_to_pay FROM users_orders WHERE users_password='".$myhid['users_pro_password']."' AND users_payed='yes' AND users_nic NOT LIKE 'TERUG%'");
                   $commande = mysql_num_rows($commandeRequest);
                   if($commande>0) {
                        while ($commandeTotalPrice = mysql_fetch_array($commandeRequest)) {
                           $totalOrder[] = $commandeTotalPrice['users_total_to_pay'];
                        }
                        $totalOrder2 = sprintf("%0.2f",array_sum($totalOrder));
                   }
                   else {
                     $totalOrder2 = sprintf("%0.2f",0);
                   }
                   if(isset($_POST['action']) AND $_POST['action']!=='' AND isset($_POST['checkCom']) AND in_array($myhid['users_pro_id'], $_POST['checkCom'])) $c="#FFCC00";
	               
                     print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
                     	  print "<td bgcolor='#FFFFFF' align='center'><input type='checkbox' name='checkCom[]' value='".$myhid['users_pro_id']."' ".$selChecked."></td>";
                          print "<td align='left'>".$myhid['users_pro_id']."</td>";
                          print "<td align='left'>".dateFr($myhid['users_pro_date_added'],$_SESSION['lang'])."</td>";
                          print "<td align='left'><a href='mijnklant.php?id=".$myhid['users_pro_password']."&from=pro'><b>".$myhid['users_pro_password']."</b></a></td>";
                          print "<td align='left'>".$myhid['users_pro_email']."</td>";
                          print "<td align='left'>".$myhid['users_pro_lastname']."</td>";
//                          print "<td align='left'>".$myhid['users_pro_firstname']."</td>";
//                          print "<td align='left'>".$entrep."</td>";
                          print "<td align='center'>".$act."</td>";
                          print "<td align='center'>".$tva."</td>";
                          print "<td align='center'>".$tvaConfirm."</td>";
                          $userReduc = ($myhid['users_pro_reduc']>0)? "<span style='color:#CC0000'><b>".$myhid['users_pro_reduc']."%</b></span>" : $myhid['users_pro_reduc']."%";
                          print "<td align='center'>".$userReduc."</td>";
                          if($myhid['users_pro_aff']=='yes') {
								$queryZAffFindCom = mysql_query("SELECT aff_number  FROM affiliation WHERE aff_customer = '".$myhid['users_pro_password']."'") or die (mysql_error());
								$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
								$affiliateNumber = $resultZAffCom['aff_number'];
						  }
                          print ($myhid['users_pro_aff']=='yes')? "<td align='center'><img src='im/val.gif' title='".$affiliateNumber."'></td>" : "<td align='center'>--</td>";
                          print "<td align='center'>".$payable."</td>";
                          if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {print "<td align='center'>".$commande."</td>";}
                          if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {print "<td align='center'>".$totalOrder2."</td>";}
                    	   
                		  print "<td align='center'><a style='BACKGROUND:none;' href='../mijn_account.php?accountRec1=".$myhid['users_pro_password']."&emailRec1=".$myhid['users_pro_email']."&addOrder=1&var=session_destroy' target='_blank'><img src='im/addOrder.gif' border='0' title='".ADD_ORDER." - ".$myhid['users_pro_password']."'></a></td>";
                          print "<td align='center'><a href='klant_fiche.php?action=view&id=".$myhid['users_pro_password']."'><img style='background-color:".$c."' src='im/details.gif' border='0' title='".A7."'></a></td>";
                          print "<td align='center'><a href='".$_SERVER['PHP_SELF']."?action=delete&c2=".$_SESSION['c2'].$pageCl."&id=".$myhid['users_pro_password'].$searchReq2.$searchC1.$orderUU."'><img style='background-color:".$c."' src='im/supprimer.gif' border='0' title='".A8."'></a></td>";
	                  print "</tr>";
	}
	print "</table>";

	if(!isset($hide) OR $hide!=='yes') {
	print "<p align='center'>";
	print "<select name='action'>";
	print "<option value=''>** ".SELECT." **</option>";
	print "<option value=''>--</option>";
	print "<option value='supprimer'>".SUPPRIMER."</option>";
	print "</select>";
	print "&nbsp;&nbsp;<input type='submit' value='   OK   ' class='knop'>";
	print "</p>";
	}
	print "</form>";
}
else {
   print "<br><p align='center' class='fontrouge'><b>".A9."</b></p>";
   if(isset($_POST['searchClient']) AND !empty($_POST['searchClient'])) {
      print "<form method='GET' action='klant_accounts_beheren.php'><p align='center'><input type='submit' value='".BACK."'></div></form>";
   }
}
print "</td></tr></table>";
?>
</body>
</html>


