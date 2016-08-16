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

 
function maj($texteZ) {
$texteZ = html_entity_decode($texteZ);
$texteZ = strtr(strtoupper($texteZ), "��������������������������������","�����������������������������ƌ��");
return $texteZ; 
}

$url_id100 = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
if($_SERVER["QUERY_STRING"]=="") $url_id100 = $_SERVER["SCRIPT_NAME"];
if(strstr($url_id100,"?")) $slash = "&"; else $slash = "?";

$url_id1001 = str_replace("checkComError=error","checkComError=redo",$url_id100);

if(isset($_GET['search']) AND $_GET['search']!=="" AND $_GET['search']!=="none") {$_POST['search'] = $_GET['search']; $_POST['action']="cherche";}

if(isset($_POST['search']) AND isset($_POST['action']) AND $_POST['action']=='cherche') {
   $url_id100 = $url_id100.$slash."action=cherche&search=".$_POST['search'];
   $searchVar = "&search=".$_POST['search'];
}
else {
   $searchVar = "";
}

 
function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

function incLang($u) {
  $fichier = explode("/",$u);
  $what = end($fichier);
  return $what;
}
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));

if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "none") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = $_POST['currentUrl'];
   header("Location: $goto");
}

if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "3") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "x_exporteer_verzendadres.php?yo=".$_POST['yo']."&date1=".$_POST['date1']."&date2=".$_POST['date2']."&search=".$_POST['search'];
   header("Location: $goto");
}
/*
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "4") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "export_commande.php?yo=".$_POST['yo']."&where=export&date1=".$_POST['date1']."&date2=".$_POST['date2']."&search=".$_POST['search'];
   header("Location: $goto");
}
*/
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "41") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "x_exporteer_bestelling_alles.php";
   if($_SESSION['check']!=='none') header("Location: $goto");
}


if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "5") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "artikelen_verzenden.php?yo=".$_POST['yo']."&where=export&date1=".$_POST['date1']."&date2=".$_POST['date2']."&search=".$_POST['search'];
   header("Location: $goto");
}

if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "6") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "artikelen_verzenden.php?yo=".$_POST['yo']."&where=print&date1=".$_POST['date1']."&date2=".$_POST['date2']."&search=".$_POST['search'];
   header("Location: $goto");
}

if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "7") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
   $goto = "verzend_adres.php?yo=".$_POST['yo']."&where=print&date1=".$_POST['date1']."&date2=".$_POST['date2']."&search=".$_POST['search'];
   header("Location: $goto");
}

 
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "25") {
   if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
      foreach($_POST['checkCom'] AS $items) {
      $queryd = mysql_query("SELECT users_password FROM users_orders WHERE users_nic='".$items."'");
      while ($rowd = mysql_fetch_array($queryd)) {
         $tt[] = $rowd['users_password'];
      }
      $tt = array_unique($tt);
   }
   $tt = implode(",",$tt);
   $goto = "korting_code_versturen.php?nic=".$tt;
   header("Location: $goto");
}

 
 
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "20") {
   if(isset($_POST['checkCom'])) {
      $_SESSION['check'] = $_POST['checkCom'];
      $resu = implode("|",$_POST['checkCom']);
   }
   else {
      $_SESSION['check'] = 'none';
      $resu = "";
   }
   $goto = "berekenen.php?jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&nico=".$resu."&Submit=Chercher";
   header("Location: $goto");
}

 
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "2") {
      if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
      if(isset($_POST['checkCom']) AND count($_POST['checkCom'])>0) {
         $implodeCheckCom = implode("|",$_POST['checkCom']);
         $goto = "klanten.php?checkCom2=".$implodeCheckCom."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
         header("Location: $goto");
      }
      else {
         $goto = "klanten.php?checkComError=error&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
         header("Location: $goto");
      }
}
 
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "203") {
      if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
      if(isset($_POST['checkCom']) AND count($_POST['checkCom'])>0) {
         $implodeCheckCom = implode("|",$_POST['checkCom']);
         $goto = "klanten.php?checkCom203=".$implodeCheckCom."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
         header("Location: $goto");
      }
      else {
         $goto = "klanten.php?checkComError=error&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
         header("Location: $goto");
      }
}
 
if(isset($_POST['bouton5']) AND $_POST['bouton5'] == "1") {
      if(isset($_POST['checkCom'])) $_SESSION['check'] = $_POST['checkCom']; else $_SESSION['check'] = 'none';
      if(isset($_POST['checkCom']) AND count($_POST['checkCom'])>0) {
      		foreach($_POST['checkCom'] AS $items) {
			  	$nics[] = str_replace("||","",$items);
			}
         $implodeCheckCom = implode("|",$nics);
         $goto = "klanten.php?checkCom20=".$implodeCheckCom."&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
		 header("Location: $goto");
      }
      else {
         $goto = "klanten.php?checkComError=error&yo=".$_POST['yo']."&jour1=".$_POST['jour1']."&mois1=".$_POST['mois1']."&an1=".$_POST['an1']."&jour2=".$_POST['jour2']."&mois2=".$_POST['mois2']."&an2=".$_POST['an2']."&search=".$_POST['search'];
         header("Location: $goto");
      }
}
 
if(isset($_GET['checkComError']) AND $_GET['checkComError']=="error") {
    $messageExp = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
    $messageExp .= "<td align='center' class='fontrouge'>".VEUILLEZ_SELECTIONER_AU_MOINS_UNE_COMMANDE."<br><a href='".$url_id1001."'>Recommencez</a></td></tr>";
    $messageExp .= "</table><br>";
}
 
if(isset($_GET['action']) AND $_GET['action']=="updateChecked") {
   $orderArray = explode("|",$_GET['checkCom3']);
   foreach($orderArray as $val) {
      mysql_query("UPDATE users_orders SET users_statut = 'yes', users_ready = 'yes' WHERE users_nic = '".$val."'");
   }
    $messageTxt = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
    $messageTxt .= "<td align='center' class='fontrouge'><b>".UPDATE_OK."</b></td></tr>";
    $messageTxt .= "</table><br>";
}
 
if(isset($_GET['action']) AND $_GET['action']=="updateChecked203") {
   $orderArray = explode("|",$_GET['checkCom3']);
   foreach($orderArray as $val) {
      mysql_query("UPDATE users_orders SET users_ready = 'yes' WHERE users_nic = '".$val."'");
   }
    $messageTxt = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
    $messageTxt .= "<td align='center' class='fontrouge'><b>".UPDATE_OK."</b></td></tr>";
    $messageTxt .= "</table><br>";
}
 
if(isset($_GET['action']) AND $_GET['action']=="updateChecked50") {
   $orderArray = explode("|",$_GET['checkCom3']);
   foreach($orderArray AS $val) {
		if(substr($val, 0, 5) == "TERUG") $val = str_replace("-","-||",$val);
    	mysql_query("DELETE FROM users_orders WHERE users_nic='".$val."'");
   }
    $messageTxt = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
    $messageTxt .= "<td align='center' class='fontrouge'><b>".SUP_OK."</b></td></tr>";
    $messageTxt .= "</table><br>";
}
 
if(isset($_GET['checkCom2'])) {
      $implodeExp = str_replace("|",", ", $_GET['checkCom2']);
      $messageExp = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
      $messageExp .= "<td align='center'>".LES_COMMANDES_NIC."<br><b>".$implodeExp."</b><br>".ONT_ELLES_ETE_EXPEDIEES."</td></tr>";
      $messageExp .= "<td align='center'>";
      $messageExp .= "<a href='klanten.php?action=updateChecked&checkCom3=".$_GET['checkCom2']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageExp .= "<b style='color:#CC0000'>".OUI."</b></a> | ";
      $messageExp .= "<a href='klanten.php?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageExp .= "<b style='color:#CC0000'>".NON."</b></a>";
      $messageExp .= "</td></tr></table><br>";
}
 
if(isset($_GET['checkCom203'])) {
      $implodeExp = str_replace("|",", ", $_GET['checkCom203']);
      $messageExp = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE3'><tr>";
      $messageExp .= "<td align='center'>".LES_COMMANDES_NIC."<br><b>".$implodeExp."</b><br>".ONT_ELLES_ETE_READY."</td></tr>";
      $messageExp .= "<td align='center'>";
      $messageExp .= "<a href='klanten.php?action=updateChecked203&checkCom3=".$_GET['checkCom203']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageExp .= "<b style='color:#CC0000'>".OUI."</b></a> | ";
      $messageExp .= "<a href='klanten.php?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageExp .= "<b style='color:#CC0000'>".NON."</b></a>";
      $messageExp .= "</td></tr></table><br>";
}
 
if(isset($_GET['checkCom20'])) {
      $implodeExp = str_replace("|",", ", $_GET['checkCom20']);
      $messageSup = "<table align='center' cellpadding='5' cellspacing='0' border='0' class='TABLE' width='700'><tr>";
      $messageSup .= "<td align='center'>".LES_COMMANDES_NIC." <b>".$implodeExp."</b><br>".ONT_ELLES_ETE_SUPPRIME."</td></tr>";
      $messageSup .= "<td align='center'>";
      $messageSup .= "<a href='klanten.php?action=updateChecked50&checkCom3=".$_GET['checkCom20']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageSup .= "<b style='color:#CC0000'>".OUI."</b></a> | ";
      $messageSup .= "<a href='klanten.php?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&search=".$_GET['search']."'>";
      $messageSup .= "<b style='color:#CC0000'>".NON."</b></a>";
      $messageSup .= "</td></tr></table><br>";
}

if(isset($_GET['mois1']) AND $_GET['mois1']==0) { $_GET['mois1'] = 12;}
if(isset($_GET['mois1']) AND $_GET['mois1']==13) { $_GET['mois1'] = 1;}

if(isset($_GET['mois2']) AND $_GET['mois2']==0) { $_GET['mois2'] = 12;}
if(isset($_GET['mois2']) AND $_GET['mois2']==13) { $_GET['mois2'] = 1;}

if(isset($_GET['jour1']) AND $_GET['jour1'] < 1) { 
    $_GET['mois1'] = $_GET['mois1']-1;
    $dayNum = date("t",$_GET['mois1']);
    $_GET['jour1'] = $dayNum-(abs($_GET['jour1']));
    if($_GET['mois1']==0) { $_GET['mois1'] = 12;}
}

if(isset($_GET['jour2']) AND $_GET['jour2'] > 31) { 
    $_GET['mois2'] = $_GET['mois2']+1;
    $dayNum2 = date("t",$_GET['mois2']);
    $_GET['jour2'] = (abs($_GET['jour2']-$dayNum2));
    if($_GET['mois2']==13) { $_GET['mois2'] = 1;}
}

if(!isset($_GET['sel'])) {$selChecked='checked';}
if(isset($_GET['sel']) AND $_GET['sel']=="checked") {$selChecked='';}
if(isset($_GET['sel']) AND $_GET['sel']=="") {$selChecked='checked';}

 
if(isset($_GET['search']) AND $_GET['search']!=='' AND $_GET['search']!=="none") {
   $_POST['search']=$_GET['search']; 
   $_POST['action']="cherche";
}
if(isset($_POST['action']) AND !empty($_POST['search']) AND $_POST['action']=="cherche") {
$search = 1;
}

 
function printDate() {
        GLOBAL $_SERVER, $_GET, $nowMonth;
        $nowDay = date("d");
        $nowMonth = date("m");
        $nowYear = date("Y");
        
        if($nowMonth=="1") $nowMonth = C2;
        if($nowMonth=="2") $nowMonth = C3;
        if($nowMonth=="3") $nowMonth = C4;
        if($nowMonth=="4") $nowMonth = C5;
        if($nowMonth=="5") $nowMonth = C6;
        if($nowMonth=="6") $nowMonth = C7;
        if($nowMonth=="7") $nowMonth = C8;
        if($nowMonth=="8") $nowMonth = C9;
        if($nowMonth=="9") $nowMonth = C10;
        if($nowMonth=="10") $nowMonth= C11;
        if($nowMonth=="11") $nowMonth= C12;
        if($nowMonth=="12") $nowMonth= C13;
        
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C2) $_GET['mois1']=1;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==A3) $_GET['mois1']=2;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C4) $_GET['mois1']=3;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C5) $_GET['mois1']=4;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C6) $_GET['mois1']=5;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C7) $_GET['mois1']=6;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C8) $_GET['mois1']=7;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C9) $_GET['mois1']=8;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C10) $_GET['mois1']=9;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C11) $_GET['mois1']=10;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C12) $_GET['mois1']=11;
        if(isset($_GET['mois1']) and $_GET['mois1'] ==C13) $_GET['mois1']=12;
        
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C2) $_GET['mois2']=1;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==A3) $_GET['mois2']=2;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C4) $_GET['mois2']=3;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C5) $_GET['mois2']=4;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C6) $_GET['mois2']=5;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C7) $_GET['mois2']=6;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C8) $_GET['mois2']=7;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C9) $_GET['mois2']=8;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C10) $_GET['mois2']=9;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C11) $_GET['mois2']=10;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C12) $_GET['mois2']=11;
        if(isset($_GET['mois2']) and $_GET['mois2'] ==C13) $_GET['mois2']=12;
        
        
        $days1 = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
        $months1 = array("1"=>C2,"2"=>C3,"3"=>C4,"4"=>C5,"5"=>C6,"6"=>C7,"7"=>C8,"8"=>C9,"9"=>C10,"10"=>C11,"11"=>C12,"12"=>C13);
        $years1 = array("2011","2012","2013","2014","2015","2016","2017","2018","2019","2020");
        
              print "<table widthw='500' align='center' border='0' cellspacing='0' cellpadding='5'>";
              print "<tr>";
              print "<td>";
              print "<b>".C15."</b> ";
        
  
        print "<select name='jour1' class='site'>";
        $days1Nb = count($days1)-1;
        for($c=0; $c<= $days1Nb; $c++)
        {
        $a=$c+1;
        if(isset($_GET['jour1'])) {
           if($days1[$c]== $_GET['jour1']) $sel1 = "selected"; else $sel1="";
        }
        else {
           if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
        }
        print "<option value=$a $sel1>$days1[$c]</option>";
        }
        print "</select>&nbsp;&nbsp;";
 
        print "<select name='mois1' class='site'>";
        $keys = array_keys($months1);
        $months1Nb = count($months1);
        for($x1=1; $x1 <= $months1Nb; $x1++)
        {
        $p=$x1-1;
        if(isset($_GET['mois1'])) {
           if($keys[$p]== $_GET['mois1']) $sel2 = "selected"; else $sel2="";
        }
        else {
           if($months1[$x1]==$nowMonth) $sel2 = "selected"; else $sel2="";
        }
        print "<option value=".$keys[$p]." $sel2>$months1[$x1]</option>";
        }
        print "</select>&nbsp;&nbsp;";
 
        print "<select name='an1' class='site'>";
        $years1Nb = count($years1)-1;
        for($x3=0; $x3 <= $years1Nb; $x3++)
        {
        if(isset($_GET['an1'])) {
           if($years1[$x3]== $_GET['an1']) $sel3 = "selected"; else $sel3="";
        }
        else {
           if($years1[$x3]==$nowYear) $sel3 = "selected"; else $sel3="";
        }
        print "<option value=".$years1[$x3]." ".$sel3.">".$years1[$x3]."</option>";
        }
        print "</select>";
        print "</td>";
        print "</tr><tr>";

              print "<td align=right>";
              print "<b>".C16."</b> ";
  
        print "<select name='jour2' class='site'>";
        $days1Nb = count($days1)-1;
        for($c=0; $c<= $days1Nb; $c++)
        {
        $a=$c+1;
        if(isset($_GET['jour2'])) {
           if($days1[$c]== $_GET['jour2']) $sel1 = "selected"; else $sel1="";
        }
        else {
           if($days1[$c]==$nowDay) $sel1 = "selected"; else $sel1="";
        }
        print "<option value=".$a." ".$sel1.">".$days1[$c]."</option>";
        }
        print "</select>&nbsp;&nbsp;";
 
        print "<select name='mois2' class='site'>";
        $keys = array_keys($months1);
        $months1Nb = count($months1);
        for($x=1; $x <= $months1Nb; $x++)
        {
        $p=$x-1;
        if(isset($_GET['mois2'])) {
           if($keys[$p]== $_GET['mois2']) $sel2 = "selected"; else $sel2="";
        }
        else {
           if($months1[$x]==$nowMonth) $sel2 = "selected"; else $sel2="";
        }
        print "<option value=".$keys[$p]." ".$sel2.">".$months1[$x]."</option>";
        }
        print "</select>&nbsp;&nbsp;";
        
 
        print "<select name='an2' class='site'>";
        $years1Nb = count($years1)-1;
        for($x=0; $x <= $years1Nb; $x++)
        {
        if(isset($_GET['an2'])) {
           if($years1[$x]== $_GET['an2']) $sel3 = "selected"; else $sel3="";
        }
        else {
        $n=$x3+1;
           if($years1[$x]==$nowYear) $sel3 = "selected"; else $sel3="";
        }
        print "<option ".$sel3.">".$years1[$x]."</option>";
        }
        print "</select>";
        
                 print "</td>";
                 print "</tr>";
                 print "</table>";
}
/* End function display date */
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
		return "Tout d�cocher";
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
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
if(empty($_GET['orderf']))  $_GET['orderf'] = "users_id";
if(!isset($_GET['c1'])) $_GET['c1']="ASC";
if(isset($_GET['c1']) and $_GET['c1']=="DESC") {$_GET['c1']="ASC";} else {$_GET['c1']="DESC";}

if(!isset($_GET['yo']) or empty($_GET['yo']) OR $_GET['yo']=="--------------") {$addQuery = ""; $_GET['yo']="";}
if(isset($_GET['yo']) and $_GET['yo']=="all") {$addQuery = ""; $sel1="selected";} else { $sel1=""; }
if(isset($_GET['yo']) and $_GET['yo']=="payed") {$addQuery = " AND users_payed = 'yes' AND users_nic NOT LIKE 'TERUG%'"; $sel2="selected";} else { $sel2=""; }
if(isset($_GET['yo']) and $_GET['yo']=="payedNotRefunded") {$addQuery = " AND users_payed = 'yes' AND users_refund = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel2x="selected";} else { $sel2x=""; }
if(isset($_GET['yo']) and $_GET['yo']=="nopayed") {$addQuery = " AND users_payed = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel3="selected";} else { $sel3=""; }
if(isset($_GET['yo']) and $_GET['yo']=="shipped") {$addQuery = " AND users_statut = 'yes' AND users_nic NOT LIKE 'TERUG%'"; $sel4="selected";} else { $sel4=""; }
if(isset($_GET['yo']) and $_GET['yo']=="noshipped") {$addQuery = " AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel5="selected";} else { $sel5=""; }
if(isset($_GET['yo']) and $_GET['yo']=="set") {$addQuery = " AND users_payed = 'yes' AND users_statut = 'yes' AND users_nic NOT LIKE 'TERUG%'"; $sel6="selected";} else { $sel6=""; }
if(isset($_GET['yo']) and $_GET['yo']=="eset") {$addQuery = " AND users_payed = 'yes' AND users_statut = 'no' AND users_refund = 'no' AND users_nic NOT LIKE 'TERUG%' AND users_nic NOT LIKE 'REFUNDED'"; $sel7="selected";} else { $sel7=""; }
if(isset($_GET['yo']) and $_GET['yo']=="noset") {$addQuery = " AND users_payed = 'no' AND users_statut = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel8="selected";} else { $sel8=""; }
if(isset($_GET['yo']) and $_GET['yo']=="noconf") {$addQuery = " AND users_confirm = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel9="selected";} else { $sel9=""; }
if(isset($_GET['yo']) and $_GET['yo']=="confnopayed") {$addQuery = " AND users_confirm = 'yes' AND users_payed = 'no' AND users_nic NOT LIKE 'TERUG%'"; $sel11="selected";} else { $sel11=""; }
if(isset($_GET['yo']) and $_GET['yo']=="refund") {$addQuery = " AND users_refund = 'yes'"; $sel10="selected";} else { $sel10=""; }
if(isset($_GET['yo']) and $_GET['yo']=="litige") {$addQuery = " AND users_litige = 'yes'"; $sel15="selected";} else { $sel15=""; }
if(isset($_GET['yo']) and $_GET['yo']=="delete") {$addQuery = " AND users_customer_delete = 'yes'"; $sel16="selected";} else { $sel16=""; }
if(isset($_GET['yo']) and $_GET['yo']=="toDelete") {$addQuery = " AND users_payed='no' AND TO_DAYS(NOW())- TO_DAYS(users_date_added) > ".$pendingOrder; $sel17="selected";} else { $sel17=""; }
if(isset($_GET['yo']) and $_GET['yo']=="shippednotpayed") {$addQuery = " AND users_payed='no' AND users_statut='yes' AND users_refund='no' AND users_nic NOT LIKE 'TERUG%' AND users_nic NOT LIKE 'REFUNDED'"; $sel18="selected";} else { $sel18=""; }
if(isset($_GET['yo']) and $_GET['yo']=="prep") {$addQuery = " AND users_ready='no' AND users_nic NOT LIKE 'TERUG%'"; $sel160="selected";} else { $sel160=""; }
if(isset($_GET['yo']) and $_GET['yo']=="payedToPrep") {$addQuery = " AND users_ready='no' AND users_payed='yes' AND users_nic NOT LIKE 'TERUG%'"; $sel165="selected";} else { $sel165=""; }
if(isset($_GET['yo']) and $_GET['yo']=="payedToPrepNotShipped") {$addQuery = " AND users_ready='yes' AND users_payed='yes' AND users_statut='no' AND users_nic NOT LIKE 'TERUG%'"; $sel170="selected";} else { $sel170=""; }

// $pendingOrder
if(isset($_GET['jour1']) or isset($dateAdded1)) {
   $dateAdded1 = "".$_GET['an1']."-".$_GET['mois1']."-".$_GET['jour1']." 00:00:00";
   $dateAdded2 = "".$_GET['an2']."-".$_GET['mois2']."-".$_GET['jour2']." 00:00:00";
}
else {
   $dateAdded1 = date("Y-m-d 00:00:00");
   $dateAdded2 = date("Y-m-d 00:00:00");
}
// QUERY
if(isset($search) AND $search==1 AND isset($_POST['search'])) {
$query = mysql_query("SELECT *
                      FROM users_orders
                      WHERE 1
                      AND (
                      users_nic like '%".$_POST['search']."%'
                      OR users_password like '%".$_POST['search']."%'
                      OR users_id like '%".$_POST['search']."%'
                      OR users_city like '%".$_POST['search']."%'
                      OR users_country like '%".$_POST['search']."%'
                      OR users_email like '%".$_POST['search']."%'
                      OR users_telephone like '%".$_POST['search']."%'
                      OR users_save_data_from_form like '%".$_POST['search']."%'
                      OR users_lastname like '%".$_POST['search']."%'
                      OR users_firstname like '%".$_POST['search']."%'
                      OR users_company like '%".$_POST['search']."%'
                      OR users_payment like '%".$_POST['search']."%'
                      OR users_fact_num like '%".$_POST['search']."%'
                      OR users_comment like '%".$_POST['search']."%'
                      OR users_devis like '%".$_POST['search']."%'
                      OR users_facture_adresse like '%".$_POST['search']."%'
                      OR users_affiliate like '%".$_POST['search']."%'
                      )
                      ORDER BY ".$_GET['orderf']."
                      ".$_GET['c1']."
                      ");
}
else {
$query = mysql_query("SELECT *
                      FROM users_orders
                      WHERE 1
                      AND TO_DAYS(users_date_added) >= TO_DAYS('".$dateAdded1."')
                      AND TO_DAYS(users_date_added) <= TO_DAYS('".$dateAdded2."')
                      $addQuery
                      ORDER BY ".$_GET['orderf']."
                      ".$_GET['c1']."
                      ");
}
$queryNum = mysql_num_rows($query);

if(!isset($_GET['jour1'])) $_GET['jour1'] = date("d");
if(!isset($_GET['mois1'])) $_GET['mois1'] = date("m");
if(!isset($_GET['an1'])) $_GET['an1'] = date("Y");
if(!isset($_GET['jour2'])) $_GET['jour2'] = date("d");
if(!isset($_GET['mois2'])) $_GET['mois2'] = date("m");
if(!isset($_GET['an2'])) $_GET['an2'] = date("Y");
if(!isset($_GET['yo'])) $_GET['yo'] = "all";

// precedent
if(isset($_GET['jour1'])) $jourMoins1 = $_GET['jour1']-1;
if(isset($_GET['jour1'])) $weekMoins1 = $_GET['jour1']-7;
if(isset($_GET['mois1'])) $moisMoins1 = $_GET['mois1']-1;
if(isset($_GET['an1'])) $anMoins1 = $_GET['an1']-1;
// suivant
if(isset($_GET['jour2'])) $jourPlus1 = $_GET['jour2']+1;
if(isset($_GET['jour2'])) $weekPlus1 = $_GET['jour2']+7;
if(isset($_GET['mois2'])) $moisPlus1 = $_GET['mois2']+1;
if(isset($_GET['an2'])) $anPlus1 = $_GET['an2']+1;
?>

<?php // menu ?>
<table align="center" border="0" cellpadding="5" cellspacing="0" class="TABLE" width="700"><tr>
<td align="center">
&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php"><?php print TODAY;?></a>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
&bull;&nbsp;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=10&yo=all&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print ALLORDER;?></a>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
&nbsp;&nbsp;&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=22&yo=payedToPrep&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print ORDRE_TO_PREP;?></a>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
&nbsp;&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=1&yo=eset&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print TODO;?></a>
<br><br>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
&nbsp;&nbsp;&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=15&yo=litige&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print COMMANDE_EN_LITIGE;?></a>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
<?php
if($pendingOrder!==1000) {
?>
&nbsp;&nbsp;&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=20&yo=toDelete&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print COMMANDE_A_SUPPRIMER;?></a> <i>(<a style="text-decoration:none;" href="site_config.php?v=1#<?php print MOD_COMPTE;?>"><b>*</b></a>)</i>
&nbsp;&nbsp;<img src='im/zzz.gif' width='1' height='3'>
<?php
}
?>
&nbsp;&nbsp;&bull;&nbsp;<a style="text-decoration:none;" href="klanten.php?todo=16&yo=delete&jour1=1&mois1=1&an1=2006&jour2=<?php echo date("d");?>&mois2=<?php echo date("m");?>&an2=<?php echo date("Y");?>&action=Chercher"><?php print COMMANDE_SUPPRIME_VIA_ACCOUNT;?></a>
</td></tr></table>
<br>

<?php // datums ?>
<table align="center" border="0" cellpadding="1" cellspacing="0" class='TABLE' width="700">
<tr center>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $jourMoins1;?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C150;?>-1</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $weekMoins1;?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C150;?>-7</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $moisMoins1;?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C151;?>-1</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $anMoins1;?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C152;?>-1</a></td>
</tr><tr>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $jourPlus1;?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C150;?>+1</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $weekPlus1;?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>"><?php print C150;?>+7</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $moisPlus1;?>&an2=<?php print $_GET['an2'];?>"><?php print C151;?>+1</a></td><td>|</td>
<td align=center><a href="klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $anPlus1;?>"><?php print C152;?>+1</a></td>
</tr>
</table>
<br>

<table width="700" align="center" border="0" cellpadding="4" cellspacing="0" class="TABLE">
<tr>
<form action="<?php print $_SERVER['PHP_SELF'];?>">

<td align="center">
     <select name="yo">
      <option value="all" <?php print $sel1;?>><?php print A2;?></option>
      <option value="all">--------------</option>
      <option value="payed" <?php print $sel2;?>><?php print A3;?></option>
      <option value="payedNotRefunded" <?php print $sel2x;?>><?php print PAYE_NO_REMB;?></option>
      <option value="nopayed" <?php print $sel3;?>><?php print A4;?></option>
      <option value="all">--------------</option>
      <option value="shipped" <?php print $sel4;?>><?php print A5;?></option>
      <option value="noshipped" <?php print $sel5;?>><?php print A6;?></option>
      <option value="prep" <?php print $sel160;?>><?php print ORDERS_TO_PREPARED;?></option>
      <option value="all">--------------</option>
      <option value="eset" <?php print $sel7;?>><?php print A8;?></option>
      <option value="payedToPrepNotShipped" <?php print $sel170;?>><?php print PAYED_TO_PREPARED_NOT_SHIPPED;?></option>
      <option value="payedToPrep" <?php print $sel165;?>><?php print PAYED_TO_PREPARED;?></option>
      <option value="all">--------------</option>
      <option value="noset" <?php print $sel8;?>><?php print A9;?></option>
      <option value="shippednotpayed" <?php print $sel18;?>><?php print SHIPPED_NOPAYED;?></option>
      <option value="all">--------------</option>
      <option value="noconf" <?php print $sel9;?>><?php print A10;?></option>
      <option value="confnopayed" <?php print $sel11;?>><?php print A10A;?></option>
      <option value="all">--------------</option>
      <option value="set" <?php print $sel6;?>><?php print A7;?></option>
      <option value="all">--------------</option>
      <option value="refund" <?php print $sel10;?>><?php print A11;?></option>
      <option value="all">--------------</option>
      <option value="litige" <?php print $sel15;?>><?php print COMMANDE_EN_LITIGE;?></option>
      <option value="all">--------------</option>
      <option value="delete" <?php print $sel16;?>><?php print COMMANDE_SUPPRIME_VIA_ACCOUNT;?></option>
      <option value="all">--------------</option>
     </select>
</td>
</tr><tr><td>

<?php
print printDate();
?>
<img src='im/zzz.gif' width='1' height='3'>
<div align="center"><input type="submit" class='knop' name="action" value="<?php print A12;?>"></div>
</td>
</form>
</tr>
</table>
<br>

<?php // zoeken ?>
<table width="700" align="center" border="0" cellpadding="4" cellspacing="0" class="TABLE">
<tr>
<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">
<td align="center">
      <input type="hidden" name="action" value="cherche">
      <input type="text" size="28" class='vullen' name="search">&nbsp;&nbsp;<input type="submit" class='knop' value="<?php print A12;?>">&nbsp;<a style='TEXT-DECORATION:none; background:none' href="javascript:void(0);" onClick="window.open('infos.php?from=com','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=360,toolbar=no,scrollbars=no,resizable=yes');"><img border=0 src=im/help.png align=absmiddle></a>
</td>
</form>
</tr>
</table>

<?php
//
if(isset($_GET['todo'])) {
   if($_GET['todo']==1) $afaire = TODO;
   if($_GET['todo']==10) $afaire = ALLORDER;
   if($_GET['todo']==15) $afaire = COMMANDE_EN_LITIGE;
   if($_GET['todo']==16) $afaire = COMMANDE_SUPPRIME_VIA_ACCOUNT;
   if($_GET['todo']==20) $afaire = COMMANDE_A_SUPPRIMER;
   if($_GET['todo']==22) $afaire = ORDRE_TO_PREP;
   
    print '<br>';
    print '<table align="center" border="0" cellpadding="2" cellspacing="0" width="700" class="TABLE"><tr style="BACKGROUND: #CCFF00"><td>';
    print "<p align='center' class='title'>".$afaire."</p>";
    print '</td></tr></table>';
}


print "<br>";
if($queryNum>0) {

if(isset($selChecked) AND $selChecked=='checked') {
   $imChecked = "<img src='im/checked.gif' border='0' title='Clear all'>";
}
else {
   $imChecked = "<img src='im/checkedno.gif' border='0' title='Check all'>";
}
?>

<form method="POST" action="<?php print $_SERVER['PHP_SELF'];?>">

<?php
if(isset($messageExp)) print $messageExp;
if(isset($messageSup)) print $messageSup;
if(isset($messageTxt)) print $messageTxt;
?>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="700" class="TABLE">
<tr>
<td align="center" height="20"><img src="im/arrow_prep.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print TO_PREP;?></td>
<td align="center" height="20"><img src="im/arrow_red.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print TO_PAY;?></td>
<td align="center" height="20"><img src="im/no_stock.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print ORDER_TO_REMOVE;?></td>
</tr>
<tr>
<td align="center"><img src="im/sleep.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print ATTENTE;?></td>
<td align="center" height="20"><img src="im/arrow_done.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print TO_SHIP;?></td>
<td align="center" height="20"><img src="im/arrow_green.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print FINALISEE;?></td>
</tr>
<tr>
<td align="center"><img src="im/conf.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print PAR_CONFRM;?></td>
<td align="center" height="20"><img src="im/payed.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print PAR_MONEYOK;?></td>
<td align="center" height="20"><img src="im/ready.png" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print PAR_SHIPPEDOK;?></td>
</tr>
<tr>
<td align="center"><img src="im/ship.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print PAR_ORDOK;?></td>
<td align="center" height="20"><img src="im/rembAnn.gif" border="0" align="absmiddle"></td>
<td align="left" height="20"><?php print PAR_REMB;?></td>
<td align="center" height="20"><img src="im/download2.gif" border="0" align="absmiddle"></td>
<td align="left" height="20">Download</td>
</tr></table><br>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="center"><font color="#FF0000"><b>CC</b> = kredietkaart | <b>WU</b>
      = Western Union | <b>BO</b> = Bank overschrijving | <b>MB</b> =
      Moneybookers | <b>PP</b> = Paypal | <b>PN</b> = Pay NL |&nbsp; |&nbsp;<b>BL</b> Bij levering</font></td>
  </tr>
</table>
<br>

<table align="center" border="0" cellpadding="3" cellspacing="0" class="TABLE">
<tr class="boxtitle2" height='35'>
		<td align="center" width="1" style='background-color:#FFFFFF'>&nbsp;</td>
        <td align="center" class="fontgris">#</td>
        <!-- <a href='<?php print $_SERVER['PHP_SELF']."?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."&sel=".$selChecked.$searchVar.""; ?>'><?php print $imChecked;?></a> -->
        <td align="center" class="fontgris"><input name="tout" type="checkbox" checked onClick="this.value=check(this.form);"></td>
        <td align="center"><img src='im/zzz.gif' width='30' height='1'><br><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_id&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>">ID</a></b></td>
		<td align="center"><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_confirm&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print A13;?>"><img src="im/conf.gif" border="0"></a></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_payed&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print A14;?>"><img src="im/payed.gif" border="0"></a></b></td>
        <td align="center"><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_ready&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print READY;?>"><img src="im/ready.png" border="0"></a></td>
		<td align="center"><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_statut&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print A15;?>"><img src="im/ship.gif" border="0"></a></td>        
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_date_added&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>"><?php print A16;?></a></b></td>
        <td align="center"><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_shipping&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print MODE_DE_LIVRAISON;?>"><img src="im/i5.gif" border="0"></a></td>
		<td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_password&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar."";?>"><?php print A17;?></a></b></td>

<? // dit is de nic ?>
        <td align="left"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_nic&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>">NIC</a></b></td>
<? // einde nic ?>

		<td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_total_to_pay&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>"><?php print A19;?></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_payment&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>"><img src='im/cccard.gif' border='0' align='absmiddle'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_lastname&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>"><?php print NOM;?></a></b></td>
 
 
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_devis&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title='<?php print DEVIS;?>'><img src='im/devis.gif' border='0'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_comment&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title='<?php print A21;?>'><img src='im/i.gif' border='0'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_note&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title='<?php print A21A;?>'><img src='im/i2.gif' border='0'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_share_note&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title='<?php print NOTE_INTERFACE;?>'><img src='im/i4.gif' border='0'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_litige_comment&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title='<?php print COMMANDE_EN_LITIGE;?>'><img src='im/i3.gif' border='0'></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_payed&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print RECUP_COMMANDE_PANIER;?>"><img src="im/cart2.gif" border="0"></a></b></td>
        <td align="center"><b><a href="<?php print $_SERVER['PHP_SELF']."?orderf=users_fact_num&c1=".$_GET['c1']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2'].$searchVar.""; ?>" title="<?php print A23;?> #"><img src="im/invoice.gif" border="0"></a></b></td>
        
        <td width='20' align="left"><b>&nbsp;</b></td>
        <td width='20' align="left"><b>&nbsp;</b></td>
        <td width='20' align="left"><b>&nbsp;</b></td>
        <td width='20' align="left"><b>&nbsp;</b></td>


<?php
$c="";
$num=1;
$dateZ = date("Y-m-d H:i:s");


function NbJours($debut, $fin) {
  $debutf = explode(" ",$debut);
  $finf = explode(" ",$fin);
  $debut = $debutf[0];
  $fin = $finf[0];
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}


$requestShipRequestW = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang']." FROM ship_mode ORDER BY livraison_id ASC") or die (mysql_error());
$colorArray = array("SteelBlue","gold","Chocolate","Purple","olive","LimeGreen","yellow","silver","black","red","cyan","BlueViolet","Brown","BurlyWood","CadetBlue","PaleVioletRed","Chocolate","Coral","CornflowerBlue","Cornsilk","Crimson","DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange","DarkOrchid","DarkRed","DarkSalmon");
while($resultShipNameW = mysql_fetch_array($requestShipRequestW)) {
    $shipColor[] = $resultShipNameW['livraison_id'];
    $shipNom[] = $resultShipNameW['livraison_nom_'.$_SESSION['lang']];
}

function color_gen($shipId) {
    GLOBAL $colorArray, $shipColor;
    foreach($shipColor AS $key => $value) {
        if($value == $shipId) $colorVal = $key;
    }
    if(!isset($colorVal)) {
        $colorVal = $shipId;
    }
    return $colorArray[$colorVal];
}

 

while ($row = mysql_fetch_array($query)) {
                if($c=="#F1F1F1") $c = "#E8E8E8"; else $c = "#F1F1F1";
 
                if($row['users_litige']=='yes') $c = "#FF9900";
  
                if($row['users_customer_delete']=='yes') $c = "#CCFF00";
   
                if($pendingOrder!==1000 AND $row['users_payed']=='no' AND NbJours($row['users_date_added'],$dateZ) > $pendingOrder) $c = "#9999FF";
                if(preg_match("#\b".str_replace("||","",$row['users_nic'])."\b#i", $url_id100)) $c = "#FFFF00";
    
                $openLeg230 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=noteClient&nic=".$row['users_nic']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                $comment = ($row['users_comment']=="")? "&nbsp;" : "<a ".$openLeg230." style='text-decoration:none; background:none' href='' title='".strtoupper(A21)." : ".strip_tags(trim(str_replace("'","�",$row['users_comment'])))."'><span style='font-size:17px; color:#0000FF'><b>&diams;</b></span></a>";
     
                $openLeg23 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=noteInterne&nic=".$row['users_nic']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                $comment2 = ($row['users_note']=="")? "&nbsp;" : "<a ".$openLeg23." style='text-decoration:none; background:none' href='' title='".strtoupper(A21A)." : ".strip_tags(trim(str_replace("'","�",$row['users_note'])))."'><span style='font-size:17px; color:#00E936'><b>&diams;</b></span></a>";
      
                $openLeg500 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=noteShare&nic=".$row['users_nic']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                $litNote10 = ($row['users_share_note']=="")? "&nbsp;" : maj(NOTE_INTERFACE)." : ".strip_tags(trim(str_replace("'","�",$row['users_share_note'])));
                $comment10 = ($row['users_share_note']=="")? "&nbsp;" : "<a ".$openLeg500." style='text-decoration:none; background:none' href='' title='".$litNote10."'><span style='font-size:17px; color:#7C7969'><b>&diams;</b></span></a>";
       
                if($row['users_litige']=="no" AND $row['users_litige_comment']=="") {
                  $comment3 =  "&nbsp;";
                }
                else {
                  if($row['users_litige']=="yes") {
                     $openLeg500 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=noteLitige&status=1&nic=".$row['users_nic']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                     $litNote = ($row['users_litige_comment']=="")? strtoupper(COMMANDE_EN_LITIGE) : strtoupper(COMMANDE_EN_LITIGE)." : ".strip_tags(trim(str_replace("'","�",$row['users_litige_comment'])));
                     $comment3 = "<a ".$openLeg500." style='text-decoration:none; background:none' href='' title='".$litNote."'><span style='font-size:17px; color:#FF0000'><b>&diams;</b></span></a>";
                  }
                  else {
                     $openLeg500 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=noteLitige&status=0&nic=".$row['users_nic']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                     $litNote = ($row['users_litige_comment']=="")? strtoupper(LITIGE_CLOS) : strtoupper(LITIGE_CLOS)." : ".strip_tags(trim(str_replace("'","�",$row['users_litige_comment'])));
                     $comment3 = "<a ".$openLeg500." style='text-decoration:none; background:none' href='' title='".$litNote."'><span style='font-size:17px; color:#FF0000'><b>&diams;</b></span></a>";
                  }
                }
        
                $checkRefundedOrder = ($row['users_refund']=='yes')? "yes" : "no";
         
                $ready = ($row['users_ready']=='no')? "<img src='im/passed.gif' title='".ORDERS_TO_PREPARED."'>" : "<img src='im/noPassed.gif' title='".READY."'>";
          
                $statut = ($row['users_statut']=='no')? "<img src='im/passed.gif' title='".NOT_SHIPPED."'>" : "<img src='im/noPassed.gif' title='".A15."'>";
           
                    $requestShipRequest = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$row['users_shipping']."' ") or die (mysql_error());
                    $resultShipName = mysql_fetch_array($requestShipRequest);
                    $shippingName = $resultShipName['livraison_nom_'.$_SESSION['lang']];
                    
                $openLeg230 = "href='javascript:void(0);' onClick=\"window.open('uitleg.php?open=shippingMode&ship=".$row['users_shipping']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=510,toolbar=no,scrollbars=yes,resizable=yes');\"";
                $shippingMode = ($row['users_shipping']==0)? "<img src='im/download2.gif' title='download'>" : "<a ".$openLeg230." style='text-decoration:none; background:none; border:0px; padding:0px;' title='".$shippingName."'><span style='background-color:".color_gen($row['users_shipping'])."; border:0px; padding:0px;'><img src='im/zzz.gif' width='12' height='12' border='0'></span></a>";
            
                $payed = ($row['users_payed']=='no')? "<img src='im/passed.gif'>" : "<img src='im/noPassed.gif' title='".A14."'>";
             
                $confirmed = ($row['users_confirm']=='no')? "<img src='im/passed.gif'>" : "<img src='im/noPassed.gif' title='".A13."'>";
              
                $nicf = (substr($row['users_nic'], 0, 5) == "TERUG")? "<b><span style='color:#CC0000'>".str_replace("||","",$row['users_nic'])."</span></b>" : "<b>".$row['users_nic']."</b>";
               
                $devis = ($row['users_devis']=='')? "&nbsp;" : "<a style='text-decoration:none; background:none' href='offerte_details.php?id=".$row['users_devis']."' title='".$row['users_devis']."'><img src='im/devis.gif' border='0'></a>";
                
			       $totalCommande = ($row['users_total_to_pay']>0 AND $row['users_refund']=='no')? $row['users_total_to_pay'].$symbolDevise : "<span style='color:#CC0000'>".$row['users_total_to_pay'].$symbolDevise."</span>";

                $hidsss = mysql_query("SELECT users_pro_payable, users_pro_company FROM users_pro WHERE users_pro_password = '".$row['users_password']."'");
                $myhidss = mysql_fetch_array($hidsss);
                if($myhidss['users_pro_payable']==0) $payable = CASH;
                if($myhidss['users_pro_payable']==30) $payable = _30_DAYS;
                if($myhidss['users_pro_payable']==60) $payable = _60_DAYS;
                if($myhidss['users_pro_payable']==90) $payable = _90_DAYS;
			    $entrep = (empty($myhidss['users_pro_company']))? "--" : $myhidss['users_pro_company'];
			    // Numero de fature
                if($row['users_fact_num']=='') {
                  $factNumber = "-";
                  $displayImage = "-";
                }
                else {
                  $factNumber = (substr($row['users_nic'], 0, 5)=="TERUG" OR $checkRefundedOrder=="yes")? "<a href='factuur_scherm.php?id=".$row['users_id']."&target=impression' target='_blank'><span style='background:#000000; color:#FFFFFF'>".str_replace("||","",$row['users_fact_num'])."</span></a>" : "<a href='factuur_scherm.php?id=".$row['users_id']."&target=impression' target='_blank'>".str_replace("||","",$row['users_fact_num'])."</a>";
                  $displayImage = "<a style='BACKGROUND:none;' href='terugbetaling_detail.php?id=".$row[0]."'><img src='im/rembAnn.gif' border='0' title='".REM."'></a>";
                }
                
                $recupOrder = ($row['users_payed']=='no' AND $row['users_statut']=='no' AND $row['users_refund']=='no' AND substr($row['users_nic'], 0, 5)!=="TERUG" AND $row['users_devis']=='')? "<a style='BACKGROUND:none;' href='../mijn_account.php?emailRec=".$row['users_email']."&accountRec=".$row['users_password']."&blg=".$row['users_nic']."&c=add&var=session_destroy' target='_blank'><img src='im/cart2.gif' border='0' title='".RECUP_COMMANDE_PANIER."'></a>" : "-";
                 
                $toPayIm6 = ($row['users_confirm']=='yes' AND $row['users_payed']=='no' AND $row['users_statut']=='yes')? "<img src='im/arrow_red_blink.gif' title='".TO_PAY."' alt='".TO_PAY."'>" : "";
                 
                $toPayIm = ($row['users_confirm']=='yes' AND $row['users_payed']=='no' AND $row['users_statut']=='no')? "<img src='im/arrow_red.gif' title='".TO_PAY."' alt='".TO_PAY."'>" : "";
                 
                $toPayIm5 = ($row['users_payed']=='yes' AND $row['users_ready']=='no' AND $row['users_statut']=='no')? "<img src='im/arrow_prep.gif' title='".TO_PREP."' alt='".TO_PREP."'>" : "";
			 
                $toPayIm2 = ($row['users_payed']=='yes' AND $row['users_ready']=='yes' AND $row['users_statut']=='no')? "<img src='im/arrow_done.gif' title='".TO_SHIP."' alt='".TO_SHIP."'>" : "";
                
                $toPayIm3 = ($row['users_confirm']=='yes' AND $row['users_payed']=='yes' AND $row['users_statut']=='yes')? "<img src='im/arrow_green.gif' title='".FINALISEE."' alt='".FINALISEE."'>" : "";
			 
                $toPayIm201 = ($row['users_confirm']=='no' AND $row['users_payed']=='no' AND $row['users_ready']=='no' AND $row['users_statut']=='no')? "<img src='im/sleep.gif' width='14' title='".ATTENTE."' alt='".ATTENTE."'>" : "";	
			 
				$toPayIm30 = ($pendingOrder!==1000 AND $row['users_payed']=='no' AND NbJours($row['users_date_added'],$dateZ) > $pendingOrder)? "<img src='im/no_stock.gif' title='".ORDER_TO_REMOVE."' alt='".ORDER_TO_REMOVE."'>" : "";	
				if($toPayIm30!=="") {$toPayIm=""; $toPayIm201="";}





                print "</tr><tr bgcolor='".$c."'>";
 
                print "<td style='background-color:#FFFFFF'>".$toPayIm.$toPayIm2.@$toPayIm3.$toPayIm5.$toPayIm6.$toPayIm30.$toPayIm201."</td>";
  
                print "<td align='left' style='color:#888888'>".$num++."</td>";
   
                print "<td align='center'><input type='checkbox' name='checkCom[]' value='".$row['users_nic']."' ".$selChecked."></td>";
    
                print "<td align='left'>".$row['users_id']."</td>";
     
                print "<td align='center'>".$confirmed."</td>";
      
                print "<td align='center'>".$payed."</td>";
       
                print "<td align='center'>".$ready."</td>";
        
                print "<td align='center'>".$statut."</td>";
         
                print "<td align='left'>".dateFr($row['users_date_added'],$_SESSION['lang'])."</td>";
          
                print "<td align='center'>".$shippingMode."</td>";
           
                print "<td align='left'><a href='mijnklant.php?id=".$row['users_password']."'>".$row['users_password']."</a></td>";

            
                print "<td align='left' width='350'><a href='detail.php?id=".$row[0]."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'>".$nicf."</a></td>";
		 



		 
 
                print "<td align='left'><b>".$totalCommande."</b></td>";

                if($row['users_payment'] == 'pn')
                  print "<td align='center'>Pay.nl</td>";
                else
                  print "<td align='center'>".strtoupper($row['users_payment'])."</td>";
   
                print "<td align='center'><input style='background:".$c."; border:none' value='".$row['users_lastname']."' size='15'></td>";
    
	 
    
            
                 
                

                print "<td align='center'>".$devis."</td>";
 
                print "<td align='center'>".$comment."</td>";
  
                print "<td align='center'>".$comment2."</td>";
   
                print "<td align='center'>".$comment10."</td>";
    
                print "<td align='center'>".$comment3."</td>";
     
                print "<td align='center'>".$recupOrder."</td>";
      
                print "<td align='center'>".$factNumber."</td>";
       
				if(substr($row['users_nic'], 0, 5) == "TERUG" OR $checkRefundedOrder=="yes") {
                    if(substr($row['users_nic'], 0, 5) == "TERUG") {
                        $ajustFact = str_replace("TERUG-","",$row['users_nic']);
                        print "<td align='center'><img src='im/no_stock3.gif' title='".AJUSTEMENT." #".str_replace("||","",$ajustFact)."'></td>";
                    }
                    if($checkRefundedOrder=="yes") print "<td align='center'><img src='im/no_stock2.gif' title='Terug betaald'></td>";
                }
                else {
                    print "<td align='center'>".$displayImage."</td>";
               }
        
                print "<td align='center'><a style='BACKGROUND:none;' href='../mijn_account.php?accountRec1=".$row['users_password']."&emailRec1=".$row['users_email']."&addOrder=1&var=session_destroy' target='_blank'><img src='im/addOrder.gif' border='0' title='".ADD_ORDER." - ".$row['users_password']."'></a></td>";
         
                print "<td align='center'><a style='BACKGROUND:none;' href='detail.php?id=".$row[0]."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'><img src='im/details.gif' border='0' title='".A24."'></a></td>";
	 
                print "<td width='center' align='center'><a style='BACKGROUND:none;' href='bestelling_verwijderen.php?id=".$row['users_id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'><img src='im/supprimer.gif' border='0' title='".A25."'></a></td>";
        
         
        }
?>
</tr></table>
</td>
</tr></table>

<br>

<input type='hidden' name='yo' value='<?php echo $_GET['yo'];?>'>
<input type='hidden' name='date1' value='<?php echo $dateAdded1;?>'>
<input type='hidden' name='date2' value='<?php echo $dateAdded2;?>'>
<input type='hidden' name='where' value='export'>
<?php if(isset($_POST['search'])) $ch=$_POST['search']; else $ch="none";?>
<input type='hidden' name='search' value='<?php echo $ch;?>'>
<input type='hidden' name='jour1' value='<?php print $_GET['jour1'];?>'>
<input type='hidden' name='mois1' value='<?php print $_GET['mois1'];?>'>
<input type='hidden' name='an1' value='<?php print $_GET['an1'];?>'>
<input type='hidden' name='jour2' value='<?php print $_GET['jour2'];?>'>
<input type='hidden' name='mois2' value='<?php print $_GET['mois2'];?>'>
<input type='hidden' name='an2' value='<?php print $_GET['an2'];?>'>

<?php


print "<input type='hidden' name='currentUrl' value='".$url_id100."'>";
if(!isset($messageExp) AND !isset($messageSup)) {
    print "<table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td><center>";
    print "<select name='bouton5'>";
    print "<option value='none' selected>".EXP."</option>";
    print "<option value='none'>--------------------</option>";
    print "<option value='1'>".COM_SUP."</option>";
    print "<option value='2'>".COM_SENT."</option>";
    print "<option value='203'>".MAJ_READY."</option>";
    print "<option value='20'>".RESUME_DES_VENTES."</option>";
    print "<option value='25'>".ENVOYER_CODE."</option>";
    print "<option value='none'></option>";
    print "<option value='41'>".EXPORTER.' '.A1Z." [CSV]</option>";
    print "<option value='5'>".EXPORTER.' '.ARTICLE_A_EXPEDIER." [CSV]</option>";
    print "<option value='none'></option>";
    print "<option value='6'>".IMPRIMER.' '.ARTICLE_A_EXPEDIER."</option>";
    print "<option value='7'>".IMPRIMER.' '.ADRESSE_EXPEDITION."</option>";
    print "</select>&nbsp;<input type='submit' class='knop' value='OK'>";
    print "</td></tr></table>";
}
else {
    if(isset($messageExp)) print $messageExp;
    if(isset($messageSup)) print $messageSup;
}
?>
<div align="center">Maak je selectie uit het bovenstaande menu</div>

</form><br><br><br>

<?php
}
else {

$moisA = explode("-",$dateAdded1);
$jourA = explode(" ",$moisA[2]);
$moisB = explode("-",$dateAdded2);
$jourB = explode(" ",$moisB[2]);
function findMonth($mois) {
        if($mois=="1")  $nowM = C2;
        if($mois=="2")  $nowM = C3;
        if($mois=="3")  $nowM = C4;
        if($mois=="4")  $nowM = C5;
        if($mois=="5")  $nowM = C6;
        if($mois=="6")  $nowM = C7;
        if($mois=="7")  $nowM = C8;
        if($mois=="8")  $nowM = C9;
        if($mois=="9")  $nowM = C10;
        if($mois=="10") $nowM= C11;
        if($mois=="11") $nowM= C12;
        if($mois=="12") $nowM= C13;
        return $nowM;
}
$dateAdded1F = $jourA[0]." ".findMonth($moisA[1])." ".$moisA[0];
$dateAdded2F = $jourB[0]." ".findMonth($moisB[1])." ".$moisB[0];


print "<p align='center'><table align='center' border='0' cellpadding='5' cellspacing='0' class='TABLE' width='700'><tr><td align=center>".C15."<b> ".$dateAdded1F." </b>".C16." <b>".$dateAdded2F."</b><br><div align='center' class='fontrouge'><b>".AUCUNE_COMMANDE_A_ETE_TROUVE."</b></div></tr></td></table>";
}
?>
<br><br><br>
</body>
</html>
