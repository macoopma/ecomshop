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

if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}
$c="";


function espace3($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}
 
function recur($tab,$pere,$rang3,$lang) {
global $c;
  if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {
    if($tab[$x][1]==$pere) {
       print "</tr><tr bgcolor='".$c."'>";
       print "<td>";
       print espace3($rang3).$tab[$x][2];
               $result3 = mysql_query("SELECT p.products_name_".$lang.", p.products_id, s.specials_id, IF(s.specials_id<>'null', 'oui','non') AS toto
                                       FROM products as p
                                       INNER JOIN specials as s
                                       ON (p.products_id = s.products_id)
                                       WHERE p.categories_id = '".$tab[$x][0]."' 
									            OR p.categories_id_bis LIKE '%|".$tab[$x][0]."|%' 
                                       ORDER BY p.products_name_".$lang."");
               while($produit = mysql_fetch_array($result3)) {
                  if(strlen($produit['products_name_'.$lang])>=40) {$ProdNameProdList = substr($produit['products_name_'.$lang],0,40)."...";} else {$ProdNameProdList = $produit['products_name_'.$lang];}
                  if($produit['toto'] == 'oui') {
                     $result = "";
                     print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='promoties_wijzigen_details.php?id=".$produit['specials_id']."'><span style='color:red'>".$ProdNameProdList."</span></a>&nbsp;".$result;
                  }
                  else {
                     print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$ProdNameProdList;
                  }
               }
       print "</td>";
       recur($tab,$tab[$x][0],$rang3+1,$lang);
    }
  }
}


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

 
if(isset($_POST['action']) AND $_POST['action']=='supprimer' AND isset($_POST['checkCom'])) {
   $ids = implode(', ', $_POST['checkCom']);
   $hide = 'yes';
   $alertMessage = "<br><table border='0' width='700' class='TABLE' cellpadding='5' cellspacing='0' align='center' style='background-color:#FFFFFF; border:#CCCCCC 2px solid;'><tr><td>";
   $alertMessage.= "<span style='color:#FF0000'>".SUPPRIMER." Promo ID# <b>".$ids."</b> ?</span><br><br>";
   $alertMessage.= "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
   $alertMessage.= "<input type='hidden' name='do' value='delete'>";
   $alertMessage.= "<input type='hidden' name='what' value='".$ids."'>";
   $alertMessage.= "<div align='center'><input type='submit' class='knop' name='confirm' value='  ".OUI."  '> | <input type='submit' class='knop' name='confirm' value='  ".NON."  '></div>";
   $alertMessage.= "</form>";
   $alertMessage.= "</td></tr></table>";
   $alertMessage.= "<br>";
   $ids2 = $_POST['checkCom'];
}

if(isset($_POST['do']) AND $_POST['do']=="delete" AND $_POST['confirm']==OUI) {
   $rrArray = explode(', ', $_POST['what']);
   $rrArrayNb = count($rrArray)-1;
   for($i=0; $i<=$rrArrayNb; $i++) {
      mysql_query("DELETE FROM specials WHERE specials_id ='".$rrArray[$i]."'");
      $idz[] = $rrArray[$i];
   }
   $ids = implode(', ', $idz);
   (count($idz)>1 AND $_SESSION['lang']==1)? $s="s" : $s="";
   $alertMessage = "<br><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE25'><tr><td>";
   $alertMessage.= "<span style='color:red'>".PANIER." ID <b>".$ids."</b> ".SUPPRIMEEEE.$s."</span><br><br>";
   $alertMessage.= "</td></tr></table>";
   $alertMessage.= "<br>";
   header("Location: promoties_wijzigen.php?alertMessage=".$alertMessage);
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
<p align="center" class="largeBold"><?php print A19;?></p>


<?php
if(!isset($_GET['order'])) $order = "s.specials_last_day";
if(isset($_GET['order']) AND $_GET['order']=="p.products_name_".$_SESSION['lang']) $order = "p.products_name_".$_SESSION['lang'];
if(isset($_GET['order']) AND $_GET['order']=="p.products_price") $order = "p.products_price";
if(isset($_GET['order']) AND $_GET['order']=="s.specials_new_price") $order = "s.specials_new_price";
if(isset($_GET['order']) AND $_GET['order']=="s.specials_first_day") $order = "s.specials_first_day";
if(isset($_GET['order']) AND $_GET['order']=="s.specials_last_day") $order = "s.specials_last_day";
if(isset($_GET['order']) AND $_GET['order']=="p.products_visible") $order = "p.products_visible";
if(isset($_GET['order']) AND $_GET['order']=="p.categories_id") $order = "p.categories_id";
if(isset($_GET['order']) AND $_GET['order']=="s.specials_id") $order = "s.specials_id";

if(!isset($_GET['c1'])) $_GET['c1']="DESC";
if($_GET['c1']=="ASC") {$_GET['c1']="DESC";} else {$_GET['c1']="ASC";}

if(isset($_POST['display']) AND $_POST['display']=="expire") {$_GET['display']='expire';}

$hids = mysql_query("SELECT p.products_forsale, p.products_id, p.products_price, s.specials_last_day, s.specials_first_day, s.specials_new_price, p.products_name_".$_SESSION['lang'].", s.specials_visible, s.specials_id, c.categories_name_".$_SESSION['lang']."
                     FROM specials as s
                     INNER JOIN products as p
                     ON(s.products_id = p.products_id)
                     INNER JOIN categories as c
                     ON(c.categories_id = p.categories_id)
                     ORDER BY ".$order." ".$_GET['c1']."
                     ") or die (mysql_error());

 
if(isset($_GET['display']) AND $_GET['display']=='expire') {
$hids = mysql_query("SELECT p.products_forsale, p.products_id, p.products_price, s.specials_last_day, s.specials_first_day, s.specials_new_price, p.products_name_".$_SESSION['lang'].", s.specials_visible, s.specials_id, c.categories_name_".$_SESSION['lang']."
                     FROM specials as s
                     INNER JOIN products as p
                     ON(s.products_id = p.products_id)
                     INNER JOIN categories as c
                     ON(c.categories_id = p.categories_id)
                     WHERE TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                    	AND TO_DAYS(NOW()) >= TO_DAYS(s.specials_last_day)
                     ORDER BY ".$order." ".$_GET['c1']."
                     ") or die (mysql_error());
}

if(isset($alertMessage) OR (isset($_GET['alertMessage']) AND !empty($_GET['alertMessage']))) {
	if(isset($_GET['alertMessage'])) $alertMessage = $_GET['alertMessage'];
	print $alertMessage;
}

if(mysql_num_rows($hids) > 0) {
    print "<table align='center' border='0' width='700' cellpadding='5' cellspacing='0' class='TABLE'><tr>";
    print "<td align='center'>";
    print "<div>&bull;&nbsp;<a href='promotie_verwijderen_cat1.php' target='main'>".A1."</a></div>";
    print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	print "<div>";
	if(!isset($_GET['display'])) print "&bull;&nbsp;<a href='promoties_wijzigen.php?display=expire'>".AFFICHER_EXPIRES."</a>";
	if(isset($_GET['display']) AND $_GET['display']=='expire') print "&bull;&nbsp;<a href='promoties_wijzigen.php'>".AFFICHER_TOUS."</a>";
	print "</div>";
    print "</td></tr>";
    print "</table>";
    print "<br>";
    
    print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
    print "<table border='0' cellpadding='3' cellspacing='0' align='center' class='TABLE'>";
    
    print "<tr bgcolor='#FFFFFF' height='35'>";
    		print "<td align='center' class='fontgris'><input name='tout' type='checkbox' checked onClick='this.value=check(this.form);'></td>";
    		print "<td align='center'><b><a href='".$_SERVER['PHP_SELF']."?order=s.specials_id&c1=".$_GET['c1']."'>ID</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=p.products_name_".$_SESSION['lang']."&c1=".$_GET['c1']."'>".A2."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=p.categories_id&c1=".$_GET['c1']."'>".CAT."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=p.products_price&c1=".$_GET['c1']."'>".A3."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=s.specials_new_price&c1=".$_GET['c1']."'>".A4."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=s.specials_first_day&c1=".$_GET['c1']."'>".A5."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=s.specials_last_day&c1=".$_GET['c1']."'>".A6."</a></b></td>";
            print "<td align='left' valign='top'><b><a href='".$_SERVER['PHP_SELF']."?order=p.products_visible&c1=".$_GET['c1']."'>".A7."</a></b></td>";
            print "<td align='left' valign='top'><b>".A8."</b></td>";
            print "<td width='20' align='center'>&nbsp;</td>";
            print "<td width='20' align='center'>&nbsp;</td>";
    print "</tr>";
    $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
    
    while ($myhid = mysql_fetch_array($hids)) {
        if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
        $view = ($myhid['specials_visible']=='yes')? "<img src='im/val.gif' title='".A9."'>" : "<img src='im/no_stock.gif' title='".A10."'>";
        $dateMaxCheck = explode("-",$myhid['specials_last_day']);
        $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
        $dateDebutCheck = explode("-",$myhid['specials_first_day']);
        $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
        if($dateDebut > $today) $statut = "<img src='im/sleep.gif' title='".A11."'>";
        if($dateDebut <= $today  AND $dateMax >= $today) $statut = "<img src='im/noPassed.gif' title='".A12."'>";
        if($dateMax < $today) $statut = "<img src='im/passed.gif' title='".A13."'>";
        $dateFin = ($myhid['specials_last_day'] == "2040-01-01")? "--" : dateFr($myhid['specials_last_day'],$_SESSION['lang']);
        $noStock = ($myhid['products_forsale'] == "no")? "<img src='im/no_stock2.gif' title='".OUT_OF_STOCK."'>" : "";

		if(isset($_GET['id']) AND $myhid['specials_id']==$_GET['id']) $c="#FFFF00";
		if(isset($ids2) AND in_array($myhid['specials_id'], $ids2)) $c="#FFCC00";
		if(isset($_GET['id']) AND $myhid['specials_id']==$_GET['id'] AND isset($_GET['action']) AND !empty($_GET['action'])) $c="#FFCC00";

        print "<tr bgcolor='".$c."' onmouseover=\"this.style.backgroundColor='#FFFFAA';\" onmouseout=\"this.style.backgroundColor='';\">";
        print "<td align='center'><input type='checkbox' name='checkCom[]' value='".$myhid['specials_id']."' ".$selChecked."></td>";
        print "<td width='50' align='left' valign='top'>".$myhid['specials_id']."</td>";
		print "<td width='200' align='left' valign='top'><a href='promoties_wijzigen_details.php?id=".$myhid['specials_id']."'>".$myhid['products_name_'.$_SESSION['lang']]."</a> ".$noStock."</td>";
		print "<td width='50' align='left' valign='top'>".$myhid['categories_name_'.$_SESSION['lang']]."</td>";
        print "<td width='50' align='left' valign='top'>".$myhid['products_price']."</td>";
        print "<td width='50' align='left' valign='top' class='fontrouge'><b>".$myhid['specials_new_price']."</b></td>";
        print "<td width='70' align='left' valign='top'>".dateFr($myhid['specials_first_day'],$_SESSION['lang'])."</td>";
        print "<td width='70' align='left' valign='top'>".$dateFin."</td>";
        print "<td width='30' align='center'>".$view."</td>";
        print "<td width='30' align='center'>".$statut."</td>";
        print "<td><a href='promoties_wijzigen_details.php?id=".$myhid['specials_id']."' style='background:none; decoration:none'><img src='im/details.gif' border='0' title='".A15."'></a></td>";
        print "<td><a href='promotie_verwijderen.php?id=".$myhid['specials_id']."'><img src='im/supprimer.gif' border='0' title='".A16."'></a></td>";
        print "</tr>";
    }
    print "</table>";

if(!isset($hide) OR $hide!=='yes') {
if(isset($_GET['display']) AND $_GET['display']=='expire') $addToForm="?<input type='hidden' name='display' value='expire'>"; else $addToForm="";
print $addToForm;
print "<p align='center'>";
print "<select name='action' action='promoties_wijzigen.php'>";
print "<option value=''>** ".SELECT." **</option>";
print "<option value=''>--</option>";
print "<option value='supprimer'>".SUPPRIMER."</option>";
print "</select>";
print "&nbsp;&nbsp;<input type='submit' value='  OK  ' class='knop'>";
print "</p>";
}
	print "</form>";
    
    $result = mysql_query("SELECT * FROM categories ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
    $id = 0;
    $i = 0;
    while($message = mysql_fetch_array($result)) {
        $result2 = mysql_query("SELECT * FROM products
                               WHERE categories_id = ".$message['categories_id']."
                               ORDER BY products_name_".$_SESSION['lang']."");
        $productsNum = mysql_num_rows($result2);
        
        $papa = $message['categories_id'];
        $fils = $message['parent_id'];
        $visible = $message['categories_visible'];
        $noeud = $message['categories_noeud'];
        $visible = ($message['categories_visible']=='yes')? A9 : "<span style='color:red'><b>".A10."</b></span>";
        $titre = ($message['categories_noeud']=="B")? "<b>".$message['categories_name_'.$_SESSION['lang']]."</b>" : "<b>".$message['categories_name_'.$_SESSION['lang']]."</b> [".$productsNum."]";
        $data[] = array($papa,$fils,$titre,$visible,$noeud);
    }
    
     
    if(isset($_GET['list']) AND $_GET['list']==1) {
       print "<br>";
       print "<table border='0' align='center' cellpadding='5' cellspacing='0' width='700' class='TABLE'><tr><td style='background-color:#FFCC00'>";
       print "<div align='center'><a href='".$_SERVER['PHP_SELF']."'><b>".NE_PAS_AFFICHER."</b></a></div>";
       print "</td></tr></table><br><br>";
    }
    else {
       print "<br>";
       print "<table border='0' align='center' cellpadding='5' cellspacing='0' width='700' class='TABLE'><tr><td style='background-color:#CCFF99'>";
       print "<div align='center'><a href='".$_SERVER['PHP_SELF']."?list=1'><b>".AFFICHER."</b></a></div>";
       print "</td></tr></table><br><br>";
    }
    
 
    if(isset($_GET['list']) AND $_GET['list']==1) {
      print "<br>"; 
    }
}
else {
	if(isset($_GET['display']) AND $_GET['display']=='expire') $addMessage="<b>".NO_PROMO_EXPIRED."</b><br><br><a href='promoties_wijzigen.php'><b>".BACK."</b></a>"; else $addMessage="<b>".A50."</b>";
   print "<p align='center' class='fontrouge'>".$addMessage."</p>";
}
?>
</body>
</html>
