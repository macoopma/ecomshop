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

 
if(!function_exists('checkdnsrr')) {
	function checkdnsrr($host, $type = '') {
		if(!empty($host)) {
			if($type == '') $type = "MX";
			@exec("nslookup -type=$type $host", $output);
			while(list($k, $line) = each($output)) {
			    $pattern = "/$host/i";
			    if(@preg_match($pattern, $line)) {
 
					return true;
				}
			}
			return false;
		}
	}
}
function check_email_mx($email) {
   GLOBAL $safeMode;
	if((preg_match('/(@.*@)|(\.\.)|(@\.)|(\.@)|(^\.)/', $email)) || (preg_match('/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/',$email)) ) {
	$domOk = array("bluewin.ch");
		if($safeMode=="off") {
         	$host = explode('@', $email);
         	if(in_array($host[1],$domOk)) {
			 	return true;
			}
			else {
    			if(checkdnsrr($host[1].'.', 'MX') ) return true;
    			if(checkdnsrr($host[1].'.', 'A') ) return true;
    			if(checkdnsrr($host[1].'.', 'CNAME') ) return true;
    		}
		}
		else {
         return true;
      }
	}
	return false;
}

 
function rep_slash($rem) {
  $rem = stripslashes($rem);
  $rem = str_replace("&#146;","'",$rem);
return $rem;
}

 
if(isset($_GET['todo']) and $_GET['todo'] == "import") {
    $importOk = "yes";
    $imp = "<p align='center' class='fontrouge'>".IMPORT."</p>";
}

 
if(isset($_GET['do']) AND $_GET['do'] == "rec" AND isset($_GET['idZ'])) {
    $newsletterRetriveQuery = mysql_query("SELECT newsletter_sent_message FROM newsletter_sent WHERE newsletter_sent_id='".$_GET['idZ']."'");
    if(mysql_num_rows($newsletterRetriveQuery)>0) {
        $newsletterRetriveResult = mysql_fetch_array($newsletterRetriveQuery);
        $newsletterRetrive = $newsletterRetriveResult['newsletter_sent_message'];
        $newsletterRetriveExplode = explode("**|||||**",$newsletterRetrive);
        $newsletterRetriveMessage = $newsletterRetriveExplode[1];
    }
}
 
if(isset($_GET['action']) and $_GET['action'] == "go") {
    $file = "import/import_email.txt";
    $check = filesize($file);
    if($check!==0) {
    $fp = fopen("import/import_email.txt","r");
        while ( $line = fgets($fp, 1000) ) {
            if(isset($line)) $imel[]=$line; else $imel[]='';
        }
    fclose($fp);
    }
    else {
        $imel[] = '';
        $messageVide = "<p align='center' class='fontrouge'><b><a href='".$file."' target='_blank'><span class='fontrouge'>".$file."</span></a> ".VIDE."</b></p>";
    }

    if(count($imel) > 0) {
     
        foreach($imel as $key => $value) {
          if($value == "") {
            unset($imel[$key]);
          }
        }
    }
    if(count($imel) > 0) {
     
        $imel = array_unique($imel);
    }
    if(count($imel) > 0) {
     
    $hoy = date("Y-m-d H:i:s");
     
    foreach($imel as $element) {
         
        $emailCheck = mysql_query("SELECT newsletter_id FROM newsletter WHERE newsletter_email='".trim($element)."'");
        if(mysql_num_rows($emailCheck)=='0' AND $element!=="") {

         
        $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $activePassword = '';
        for( $i = 0; $i < 7 ; $i++ ) {
            $activePassword .= substr($str1, rand(0, strlen($str1) - 1), 1);
        }
         
            mysql_query("INSERT INTO newsletter
                        (newsletter_email,
                        newsletter_password,
                        newsletter_langue,
                        newsletter_active,
                        newsletter_statut,
                        newsletter_date_added,
                        newsletter_nic
                        )
                        VALUES
                        ('".trim($element)."',
                        '".$activePassword."',
                        '1',
                        'yes',
                        'active',
                        '".$hoy."',
                        ''
                        )");
        $emailImp = "<p align='center' class='fontrouge'><b>".IMPORT_OK."</b></p>";
        }
    }
    }
}
 
if(isset($_GET['action']) AND $_GET['action'] == "delete") {
   mysql_query("DELETE FROM newsletter_sent WHERE newsletter_sent_id='".$_GET['id']."'");
}
 
if(isset($_GET['action']) AND $_GET['action'] == "deleteNotValidEmails") {
   foreach($_SESSION['notValidEmails'] AS $emailsZ) {
      mysql_query("DELETE FROM newsletter WHERE newsletter_email='".$emailsZ."'");
   }
   if(isset($_SESSION['notValidEmails'])) unset($_SESSION['notValidEmails']);
   $messageSent = "<p align='center'><b><span class='fontrouge'>".EMAILS_SUPPRIME."</span></b></p>";
}
if(isset($_GET['action']) AND $_GET['action'] == "none") {
   if(isset($_SESSION['notValidEmails'])) unset($_SESSION['notValidEmails']);
}

 
if(isset($_GET['action']) AND $_GET['action'] == "Affiliation") {
    $queryAff = mysql_query("SELECT distinct(aff_email) FROM affiliation");
    $queryAffNum = mysql_num_rows($queryAff);
    if($queryAffNum>0) {
        while($aff = mysql_fetch_array($queryAff)) {
                $affArray[] = $aff['aff_email'];
        }
        $affOk = "yes";
   }
   else {
        $messageSent = "<p align='center'><b><span class='fontrouge'>".A120."</span></b></p>";
   }
}

 
if(isset($_GET['action']) AND $_GET['action'] == "Client") {
   $cl = "<a href='nieuwsbrief.php?action=Client&req=n'><span class='fontrouge'>".AA11."</span></a>";
   $cl .= "<br>";
   $cl .= "<a href='nieuwsbrief.php?action=Client&req=y'><span class='fontrouge'>".AA10."</span></a>";
   $cl .= "<br>";
   $cl .= "<a href='nieuwsbrief.php?action=Client&req=all'><span class='fontrouge'>".AA12."</span></a>";
    
   if(isset($_GET['req']) AND $_GET['req']=='y') {
      $queryClient = mysql_query("SELECT distinct(users_email) FROM users_orders WHERE users_confirm='yes' AND users_payed='yes'");
      $clientVar = 'users_email';
   }
    
   if(isset($_GET['req']) AND $_GET['req']=='all') {
      $queryClient = mysql_query("SELECT distinct(users_pro_email) FROM users_pro");
      $clientVar = 'users_pro_email';
   }
    
   if(isset($_GET['req']) AND $_GET['req']=='n') {
      $queryClient = mysql_query("SELECT distinct(users_pro_email) FROM users_pro WHERE users_pro_active='yes'");
      $clientVar = 'users_pro_email';
      if(mysql_num_rows($queryClient)>0) {
          while ($totalEmailRequest = mysql_fetch_array($queryClient)) {
             $totalEmail[] = $totalEmailRequest['users_pro_email'];
          }
      }
      else {
             $totalEmail[] = '';
      }
      if(count($totalEmail) > 0) {
            foreach($totalEmail AS $emailVal) {
                  $emailRequest = mysql_query("SELECT users_email FROM users_orders WHERE users_email='".$emailVal."' AND users_payed='yes' AND users_nic NOT LIKE 'TERUG%'");
                  if(mysql_num_rows($emailRequest)==0) {
                     $totalEmail2[] = $emailVal;
                  }
                  else {
                     $totalEmail2[] = '';
                  }
            }
             
            $totalEmail2 = array_unique($totalEmail2);
            
            foreach($totalEmail2 AS $key => $value) {
               if($value == "") {
                  unset($totalEmail2[$key]);
               }
            }
            $totalEmail2 = array_values($totalEmail2);
      }
      else {
         $totalEmail2[] = '';
      }
      
      if(count($totalEmail2)>0) {
            $emailReq = "users_pro_email='".$totalEmail2[0]."'";
            $totalEmail2Nb = count($totalEmail2)-1;
         for($i=1; $i<=$totalEmail2Nb; $i++) {
            $emailReq .= " OR users_pro_email='".$totalEmail2[$i]."'";
         }
      }
      else {
         $emailReq = "users_pro_email='hht'";
      }
      
      $queryClient = mysql_query("SELECT distinct(users_pro_email) FROM users_pro WHERE ".$emailReq);
   }
    
   if(!isset($_GET['req'])) {
      $queryClient = mysql_query("SELECT distinct(users_email) FROM users_orders WHERE users_confirm='yes' AND users_payed='yes'");
      $clientVar = 'users_email';
   }
   
    $queryClientNum = mysql_num_rows($queryClient);
    if($queryClientNum>0) {
        while($client = mysql_fetch_array($queryClient)) {
                $clientArray[] = $client[$clientVar];
        }
        $clientOk = "yes";
   }
   else {
        $messageSent = "<p align='center'><b><span class='fontrouge'>".A120."</span></b></p>";
   }
}

if(isset($_POST['message']) AND $_POST['message']=="") $messageSent = "<p align='center'><b><span class='fontrouge'>".MESSAGE_VIDE."</span></b></p>";
if(isset($_POST['message']) AND $_POST['message']!=="") {
$explodeEmail = explode(",\r\n",$_POST['emails']);
$explodeEmailCount = count($explodeEmail);

$messageSent = "<p align='center'><b><span class='fontrouge'>".A1."</span></b></p>";
if(isset($_POST['submit']) AND $_POST['submit']==SAUVEGARDER) $messageSent = "<p align='center'><b><span class='fontrouge'>".SAVED."</span></b></p>";
$date = date("Y-m-d");
$from = $mailInfo;
 
$_POST['message']  = str_replace("\'","&#146;",$_POST['message']);
$_POST['message']  = str_replace("'","&#146;",$_POST['message']);
 
$_POST['sujet']  = str_replace("\'","&#146;",$_POST['sujet']);
$_POST['sujet']  = str_replace("'","&#146;",$_POST['sujet']);
$subject = $_POST['sujet'];

if(isset($_POST['submit']) AND $_POST['submit']==ENVOYER) $sts='sent'; else $sts='saved';
 
mysql_query("INSERT INTO newsletter_sent (newsletter_stat, newsletter_format, newsletter_sent_date, newsletter_sent_message)
             VALUES ('".$sts."','".$_POST['select']."','".$date."','".$subject."**|||||**".$_POST['message']."')");

 
if($_POST['select'] == "text" AND isset($_POST['submit']) AND $_POST['submit']==ENVOYER) {
    $emailNotSent = array();
    for($i=0; $i<= $explodeEmailCount-1; $i++) {
     
      if(check_email_mx($explodeEmail[$i])) {
      mail($explodeEmail[$i], $subject, rep_slash($_POST['message']),
           "Return-Path: $from\r\n"
           ."From: $from\r\n"
           ."Reply-To: $from\r\n"
           ."X-Mailer: PHP/" . phpversion());
      }
      else {
         $emailNotSent[] = $explodeEmail[$i];
      }
    }
}

if($_POST['select'] == "html" AND isset($_POST['submit']) AND $_POST['submit']==ENVOYER) {
    $emailNotSent = array();
    for($i=0; $i<= $explodeEmailCount-1; $i++) {
 
    if(check_email_mx($explodeEmail[$i])) {
      mail($explodeEmail[$i], $subject, rep_slash($_POST['message']),
          "Return-Path: $from\r\n"
          ."From: $from\r \n"
          ."Reply-To: $from\r \n"
          ."MIME-Version: 1.0\r \n"
          ."Content-Type: text/html; charset='iso-8859-1'\r \n"
          ."X-Mailer: PHP/" . phpversion());
      }
      else {
         $emailNotSent[] = $explodeEmail[$i];
      }
     }
}
}
 
if(isset($_POST['message']) AND !empty($_POST['message'])) {
    $explodeEmail = explode(",\r\n",$_POST['emails']);
    $explodeEmailCount = count($explodeEmail);
}

$table = mysql_fetch_array(mysql_query("SHOW TABLE STATUS LIKE 'newsletter_sent'"));
$newsletterNextId = $table['Auto_increment'];
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if(isset($clientOk) AND $clientOk=='yes') {
    print "<p align='center' class='largeBold'>".A2A."</p>";
    print "<p align='center' class='fontgris'>".A30."</p>";
    $url = "nieuwsbrief.php?action=Client";
}
else if(isset($affOk) AND $affOk=='yes') {
    print "<p align='center' class='largeBold'>".A2AF."</p>";
    print "<p align='center' class='fontgris'>".A30F."</p>";
    $url = "nieuwsbrief.php?action=Affiliation";
}
else if(isset($importOk) AND $importOk=='yes') {
    print "<p align='center' class='largeBold'>".A2AFF."</p>";
    print "<p align='center' class='fontgris'>".A300."</p>";
   $url = "nieuwsbrief.php";
}
else {
    print "<p align='center' class='largeBold'>".A2."</p>";
    print "<p align='center' class='fontgris'>".A3."</p>";
    $url = "nieuwsbrief.php";
}
?>


<?php
 
if(isset($messageSent)) print $messageSent;

if(isset($emailNotSent) AND count($emailNotSent)>0) {
$_SESSION['notValidEmails'] = $emailNotSent;
print "<table border='0' width='300' align='center' cellspacing='0' cellpadding='5' class='TABLE31'><tr><td style='background:#CC0000'>";
print "<div align='center'><b><u>".EMAILS_NOT_VALID."</u></b></div>";
   foreach($emailNotSent AS $badEmails) {
      print "<div align='center'><span style='color:#FFFFFF'>- ".$badEmails."</span></div>";
   }
print "<p align='center' style='color:#FFFFFF'>";
print SUPPRIMER_EMAILS_NOT_VALID;
print "<br>";
print "<a href='nieuwsbrief.php?action=deleteNotValidEmails'><b>".OUI."</b></a> | <a href='nieuwsbrief.php?action=none'><b>".NON."</b></a>";
print "</p>";
print "</td></tr></table>";
print "<br>";
}


$query = mysql_query("SELECT * FROM newsletter WHERE newsletter_active='yes' AND newsletter_statut='active'");
$queryNum = mysql_num_rows($query);


print "<table border='0' align='center' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td>";
print '<div align="center">&bull;&nbsp;<a href="nieuwsbrief.php?todo=import"><b>'.IMPORTER_EMAILS.'</b></a></div>';
print '<div align="center"><img src="im/zzz.gif" height="8" width="1"></div>';
if(isset($imp)) print $imp;
if($queryNum==0) print "</td></tr></table>";

if($queryNum>0) {
    print '<div align="center">&bull;&nbsp;<a href="nieuwsbrief.php?action=Newsletter"><b>'.A401.'</b></a></div>';
    print '<div align="center"><img src="im/zzz.gif" height="4" width="1"></div>';
    print '<div align="center">&bull;&nbsp;<a href="nieuwsbrief.php?action=Client"><b>'.A400.'</b></a></div>';
    print '<div align="center"><img src="im/zzz.gif" height="4" width="1"></div>';
    if(isset($cl)) print "<p align='center'>".$cl."</p>";
    print '<div align="center">&bull;&nbsp;<a href="nieuwsbrief.php?action=Affiliation"><b>'.A400F.'</b></a></div>';
print "</td></tr></table>";

print '<p align="center"><table border="0" align="center" cellspacing="0" cellpadding="5" class="TABLE" width="700"><tr><td>
<center><a href="nieuwsbrief_adressen.php"><b>'.A4.'</b></a>
</tr></td></table>';



if(isset($messageVide)) print $messageVide;
if(isset($emailImp)) print $emailImp;
while($user = mysql_fetch_array($query)) {
   $userInscrit[] = $user['newsletter_email'];
}

        
        if(isset($clientOk) AND $clientOk=='yes') {
            $userOnBdd = implode(",\r\n",$clientArray);
        }
        else if(isset($affOk) AND $affOk=='yes') {
            $userOnBdd = implode(",\r\n",$affArray);        
        }
        else {
            $userOnBdd = implode(",\r\n",$userInscrit);
        }

print "<form action='".$url."' method='POST'>";

print "<table border='0' align='center' width='700' cellspacing='0' cellpadding='3' class='TABLE'>";
print "<tr>";

$datezz = dateFr(date("Y-m-d"),$_SESSION['lang']);
$subject = "Newsletter ".strtoupper($domaineFull)." - ".$datezz;
print "<td width='150'><b>".SUJET."</b></td>";
print "<td><input type='text' name='sujet' value='".$subject."' size='60'></td>";
print "</tr>";

print "<tr>";
print "<td width='150'><b>".ENVOYE_A."</b></td>";
print "<td><textarea cols='60' rows='5' name='emails'>".$userOnBdd."</textarea></td>";
print "</tr>";

print "<tr>";
print "<td width='150' valign='top'><b>Bericht</b><br>(".A6.")</td>";
print "<td>";
$message = (isset($newsletterRetriveMessage))? $newsletterRetriveMessage : "";

print "<textarea cols='80' rows='20' name='message'>".$message."[-- ".ANNULER_ABONNEMENT." : http://".$www2.$domaineFull."/nieuwsbrief_systeem.php --]</textarea>";

print "<br>";
print "<select name='select'>
        <option value='text' selected>".A7."</option>
        <option value='html'>HTML</option>
      </select>";
print "</td>";
print "</tr>";

if($newsletterNextId > 0) {
  print "<tr>";
  print "<td width='80'><b>URL</b></td>";
  print "<td>http://".$www2.$domaineFull."/bekijken.php?id=".$newsletterNextId."</td>";
  print "</tr>";
}
print "</table>";

print "<br>";

print "<table align='center' width='700' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr height='40'>";
print "<td align='left'><input type='submit' value='".ENVOYER."' name='submit' class='knop'></td>";
print "<td align='right'><input type='submit' value='".SAUVEGARDER."' name='submit' class='knop'></td>";
print "</tr></table>";
print "</form>";

print "<br>";

$querySent = mysql_query("SELECT * FROM newsletter_sent ORDER BY newsletter_sent_date DESC");
$message1Count = mysql_num_rows($querySent);

if(isset($_GET['action']) AND $_GET['action']!=="delete") $yo1="&action=".$_GET['action']; else $yo1="";
if(isset($_GET['todo'])) $yo2="&todo=".$_GET['todo']; else $yo2="";
if(isset($_GET['req'])) $yo3="&req=".$_GET['req']; else $yo3="";
$urlFull = $_SERVER['PHP_SELF']."?do=rec".$yo1.$yo2.$yo3;

if($message1Count>0) {
print "<table border='0' align='center' width='700' cellspacing='0' cellpadding='3' class='TABLE'>";
print "<tr><td colspan='7'><b>".A8."</b></td></tr>";
        $i=1;
        $c="";
        while($messageStock = mysql_fetch_array($querySent)) {
        		$titleNewsletterSent = explode("**|||||**",$messageStock['newsletter_sent_message']);
               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
               $doIm="im/sent.gif";
               $doIm2="im/html.gif";
               if($messageStock['newsletter_format']=="text") {$doIm2="im/txt.gif";}
               if($messageStock['newsletter_format']=="html") {$doIm2="im/html.gif";}
               if($messageStock['newsletter_stat']=="sent") {$doTitle=ENVOYER2; $doIm="im/sent.gif";}
               if($messageStock['newsletter_stat']=="saved") {$doTitle=SAUVEGARDER2; $doIm="im/download.gif";}
            print "<tr>";
                    print "<td valign=top bgcolor='".$c."'>".$i++." - ".dateFr($messageStock['newsletter_sent_date'],$_SESSION['lang'])."</td>";
                    print "<td align='top' bgcolor='".$c."'>";
					print "<a href='#' title='http://".$www2.$domaineFull."/bekijken.php?id=".$messageStock['newsletter_sent_id']."'>URL</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' size='47' class='vullen'  value='http://".$www2.$domaineFull."/bekijken.php?id=".$messageStock['newsletter_sent_id']."'>";
					print "<div><img src='im/zzz.gif' width='1' height='1'></div>";
					print "<a href='#' title='".$titleNewsletterSent[0]."'>Onderwerp</a> <input type='text' class='vullen' size='47' value='".$titleNewsletterSent[0]."'></td>";
                    print "<td align='center' valign='top' bgcolor='".$c."'><img src='".$doIm2."' border='0'></td>";
                    print "<td align='center' valign='top' bgcolor='".$c."'><img src='".$doIm."' border='0' title='".$doTitle."' height='13'></td>";
                    print "<td align='right' valign='top' bgcolor='".$c."'><a href='../bekijken.php?id=".$messageStock['newsletter_sent_id']."' target='_blank'><img src='im/voir.gif' border='0' title='Bekijken'></a></td>";
                    print "<td align='right' valign='top' bgcolor='".$c."'><a href='".$urlFull."&idZ=".$messageStock['newsletter_sent_id']."&stat=".$messageStock['newsletter_stat']."' style='background:none; decoration:none'><img src='im/update.gif' border='0' title='".RECUP."'></a></td>";
                    print "<td align='right' valign='top' bgcolor='".$c."' width='1'><a href='".$_SERVER['PHP_SELF']."?id=".$messageStock['newsletter_sent_id']."&action=delete'><img src='im/supprimer.gif' border='0' title='Verwijderen'></a></td>";
                  print "</tr>";
        }
print "</table>";
print "<br>";
}

print "<table align='center' width='700' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr><td>";
print A11;
print "</td></tr></table>";
}
else {
print "<p align='center'><b>".A12."</b></p>";
}
?>
<br><br><br>
  </body>
  </html>
