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


$carMax = 100;

 
function cleanVar($var) {
   $cleanVar = str_replace("\'","&#146;",$var);
   $cleanVar = str_replace("'","&#146;",$cleanVar);
   return $cleanVar;
}

 
$table = mysql_fetch_array(mysql_query("SHOW TABLE STATUS LIKE 'page_added'"));
$pageNextId = $table['Auto_increment']+1000;

 
if(isset($_POST['usage']) AND ($_POST['usage']=="link" OR $_POST['usage']=="image")) $_POST['message1'] = " ";
if((isset($_POST['message1']) AND $_POST['message1']!=='' AND isset($_POST['submit']) AND $_POST['submit']==ENREGISTRER)) {
	$message = "";
	if(isset($_POST['usage']) AND ($_POST['usage']!=='link' OR $_POST['usage']=="image")) $_POST['url'] = "";
	
 
	if(isset($_FILES["uploadBanner"]["name"]) AND !empty($_FILES["uploadBanner"]["name"])) {
 
		$nomFichier1    = $_FILES["uploadBanner"]["name"];
 
		$nomTemporaire1 = $_FILES["uploadBanner"]["tmp_name"];
 
		$typeFichier1   = $_FILES["uploadBanner"]["type"];
 
		$poidsFichier1  = $_FILES["uploadBanner"]["size"];
 
		$codeErreur1    = $_FILES["uploadBanner"]["error"];
 
		$chemin1 = "../im/";
		if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier1)) {
			if(copy($nomTemporaire1, $chemin1.$nomFichier1)) {
				$_POST['image'] = str_replace("../","",$chemin1).$nomFichier1;
				$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_REUSSI.".</p>";
			}
			else {
				$_POST['image'] = "";
				$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</p>";
			}
		}
		else {
			$_POST['image'] = "";
			$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier1."</span> ".A_ECHOUE."</p>";
		}
}
	if(isset($_POST['message1']) AND $_POST['message1']==" ") $_POST['message1']="";
  
   	$date = date("Y-m-d H:i:s");
   	mysql_query("INSERT INTO page_added (
   							page_added_use, 
							page_added_date, 
							page_added_title_1, 
							page_added_title_2, 
							page_added_title_3, 
							page_added_message_1, 
							page_added_message_2, 
							page_added_message_3, 
							page_added_visible,
							page_added_image,
							page_added_url)
               VALUES ('".$_POST['usage']."', 
					   '".$date."',
					   '".cleanVar($_POST['sujet1'])."',
					   '".cleanVar($_POST['sujet2'])."',
					   '".cleanVar($_POST['sujet3'])."',
					   '".cleanVar($_POST['message1'])."',
					   '".cleanVar($_POST['message2'])."',
					   '".cleanVar($_POST['message3'])."',
					   '".$_POST['visible']."',
					   '".$_POST['image']."',
					   '".$_POST['url']."')
               ") or die (mysql_error());
   $mess = "<p align='center'><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td class='fontrouge'><center><b>".PAGE_SAVED."</b></tr></td></table><br>";

}

 
if(isset($_GET['action']) and $_GET['action'] == "delete") {
   $idZ = $_GET['id']-1000;
   mysql_query("DELETE FROM page_added WHERE page_added_id='".$idZ."'");
   $mess = "<p align='center'><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td class='fontrouge'><center><b>".PAGE_SUPPRIME."</b></tr></td></table><br>";
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
<?php if($activerTiny=="oui") include("tiny-inc.php");?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
print "<p align='center' class='largeBold'><b>".AJOUTER_UNE_PAGE."</b></p>";

 
if(isset($mess)) print $mess;
if(isset($message)) print $message;

print "<table border='0' align='center' width='700' cellspacing='0' cellpadding='0'><tr><td>";

print "<table border='0' width='100%' cellspacing='0' cellpadding='5' class='TABLE' width='100%'>";
print "<tr>";
print "<form action='eigen_pagina_toevoegen.php' method='POST' enctype='multipart/form-data'>";

$datezz = date("Y-m-d");
if(!isset($subject1)) $subject1 = "";
if(!isset($subject2)) $subject2 = "";
if(!isset($subject3)) $subject3 = "";
 
print "<td valign=top>".TITRE."<br>Maximaal ".$carMax." tekens</td>";
print "<td>";
print "<img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet3' value='".$subject3."' class='vullen' size='80%' maxlength='".$carMax."'>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet1' value='".$subject1."' class='vullen' size='80%' maxlength='".$carMax."'>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet2' value='".$subject2."' class='vullen' size='80%' maxlength='".$carMax."'>";
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>Afbeelding</td>";
print "<td>";

?>

	&nbsp;&bull;&nbsp;<?php print UPLOAD;?><br>&nbsp;<input type='file' name='uploadBanner' class='vullen' size='40'>
	&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='<?php print VIDER;?>'>
	<div><img src='im/zzz.gif' width='1' height='5'></div>
	<input type="text" size="30" class='vullen' name="image">&nbsp;(.gif,.jpg,.png,.swf)
 
<?php
print "</td>";
print "</tr>";


print "<tr>";
print "<td valign=top>".USAGE."</td>";
print "<td align='left'>";
print "<input type='radio' name='usage' value='actu' checked>&nbsp;".ACTU;
print "<br>";
print "<input type='radio' name='usage' value='perso'>&nbsp;".PERSO;
print "&nbsp;(".LIEN_PERSO_AFFICHERA_DIVERS.")";
print "<br>";
print "<input type='radio' name='usage' value='link'>&nbsp;".DIRECT_LINK;
print "&nbsp;(".LIEN_AFFICHERA_DIVERS.")";
print "<br>";
print "<input type='radio' name='usage' value='image'>&nbsp;Afbeelding";
print "&nbsp;(".IMAGE_AFFICHERA_DIVERS.")";
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>URL</td>";
print "<td align='left'>";
print "<input type='text' class='vullen' name='url' value='' size='85%'>";
print "<br>".UNIQUEMENT_SI."";
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign>".VISIBLE."</td>";
print "<td align='left'>";
print "<input type='radio' name='visible' value='yes' checked>&nbsp;".OUI;
print "&nbsp;|&nbsp;";
print "<input type='radio' name='visible' value='no'>&nbsp;".NON;
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>".MESSAGE_HTML."<br>(".LIRE_NOTE.")</td>";
print "<td>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/be.gif' align='TEXTTOP'>&nbsp;<img src='im/nl.gif' align='TEXTTOP'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message3'></textarea></td></tr></table>";
print "<br>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/leeg.gif' align='TEXTTOP'>&nbsp;<img src='im/fr.gif' align='TEXTTOP'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message1'></textarea><td></tr></table>";
print "<br>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/leeg.gif' align='TEXTTOP'>&nbsp;<img src='im/uk.gif' align='TEXTTOP'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message2'></textarea><td></tr></table>";
print "</td>";
print "</tr>";
 
if($pageNextId > 0) {
print "<tr>";
print "<td valign=top><b>URL's</b></td>";
print "<td>";
print "".LIEN_INTERNE."<br>";
print "<div style='padding:2px;'>http://".$www2.$domaineFull."/doc.php?id=".$pageNextId."</div>";
print OU." : doc.php?id=".$pageNextId;
print "<br><b>".LIEN_EXTERNE."</b><br>";
print "<div style='padding:2px;'><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$pageNextId."&amp;lang=3</div>";
print "<div style='padding:2px;'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$pageNextId."&amp;lang=1</div>";
print "<div style='padding:2px;'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$pageNextId."&amp;lang=2</div>";
print "</td>";
print "</tr><tr>";
}
 
print "<td align='center' colspan='2' height='40'><input type='submit' value='".ENREGISTRER."' name='submit' class='knop'></td>";
 
print "</form>";
print "</tr>";
print "</table>";

print "<br>";
print "<br>";
print "<br>";


$querySent = mysql_query("SELECT * FROM page_added ORDER BY page_added_use ASC, page_added_id DESC");
$message1Count = mysql_num_rows($querySent);

if($message1Count>0) {
print "<table border='0' width='100%' cellspacing='1' cellpadding='4' class='TABLE1'>";
print "<tr><td align='center' colspan='5' height='30' class='largeBold'><b>".PAGE_ENREGISTRE."</b></td></tr>";
        $i=1;
        $c="";
        while($messageStock = mysql_fetch_array($querySent)) {
               if($c=="#FFFFFF") {$c="#F1F1F1";} else {$c="#FFFFFF";}
               $ids = $messageStock['page_added_id']+1000;
               if($messageStock['page_added_message_3']=="") $frTitle = "--"; else $frTitle = $messageStock['page_added_title_3'];
               if($messageStock['page_added_message_1']=="") $enTitle = "--"; else $enTitle = $messageStock['page_added_title_1'];
               if($messageStock['page_added_message_2']=="") $esTitle = "--"; else $esTitle = $messageStock['page_added_title_2'];
               if($messageStock['page_added_message_3']!=="" AND $messageStock['page_added_title_3']=="") $frTitle = NOTITLE;
               if($messageStock['page_added_message_1']!=="" AND $messageStock['page_added_title_1']=="") $enTitle = NOTITLE;
               if($messageStock['page_added_message_2']!=="" AND $messageStock['page_added_title_2']=="") $esTitle = NOTITLE;
               if($messageStock['page_added_use']=="link") {
			   		$frTitle = $messageStock['page_added_title_1'];
			   		$enTitle = $messageStock['page_added_title_2'];
			   		$esTitle = $messageStock['page_added_title_3'];
			   	}
               if($messageStock['page_added_use']=="image") {
			   		$frTitle = "";
			   		$enTitle = "<img src='../".$messageStock['page_added_image']."' border='0'>";
			   		$esTitle = "";
			   	}
               
            print "<tr>";
                    print "<td height='45' width='110' bgcolor='".$c."'>";
                    print "Pagina ".$i++."<br>";
                        $visible = ($messageStock['page_added_visible']=='yes')? ucfirst(strtolower(OUI)) : "<b><span style='color:#CC0000'>".NON."</span></b>";
                    print VISIBLE.": ".$visible."<br>";
                    print "Datum: ".dateFr($messageStock['page_added_date'],$_SESSION['lang'])."<br>";
                    print "Pagina ID: <b>".$ids."</b><br>";
                    if($messageStock['page_added_use']=="actu") {
                       print USAGE.": <span style='background-color:#00CC00; color:#FFFFFF'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                    }
                    if($messageStock['page_added_use']=="perso") {
                       print USAGE.": <span style='background-color:#FFFF00; color:#000000'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                    }
                    if($messageStock['page_added_use']=="link") {
                       print USAGE.": <span style='background-color:#0000FF; color:#FFFFFF'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                    }
                    if($messageStock['page_added_use']=="image") {
                       print USAGE.": <span style='background-color:#FF00FF; color:#FFFFFF'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                    }
                    print "</td>";
                    print "<td bgcolor='".$c."'>";

                    print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
                    print "<td>";
                    print "<i><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'> ".$frTitle."</i>";
                    print "</td>";
                    print "<td align='right' bgcolor='".$c."'>";
                    print "<a href='../bekijken.php?id=".$ids."&lang=3&do=1' target='_blank' style='background:none; text-decoration:none'><img src='im/voir.gif' border='0' title='".VOIR."'></a>";
                    print "</td>";
                    print "</tr><tr>";
                    print "<td align='left'>";
                    print "<i><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'> ".$enTitle."</i>";
                    print "</td>";
                    print "<td align='right' bgcolor='".$c."'>";
                    print "<a href='../bekijken.php?id=".$ids."&lang=1&do=1' target='_blank' style='background:none; text-decoration:none'><img src='im/voir.gif' border='0' title='".VOIR."'></a>";
                    print "</td>";
                    print "</tr><tr>";
                    print "<td align='left'>";
                    print "<i><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'> ".$esTitle."</i>";
                    print "</td>";
                    print "<td align='right' bgcolor='".$c."'>";
                    print "<a href='../bekijken.php?id=".$ids."&lang=2&do=1' target='_blank' style='text-decoration:none; background:none'><img src='im/voir.gif' border='0' title='".VOIR."'></a>";
                    print "</td>";
                    print "</tr></table>";
                    
                    print "</td>";
                    print "<td align='right' width='1' bgcolor='".$c."'><a href='eigen_pagina_wijzigen.php?id=".$ids."' style='text-decoration:none; background:none'><img src='im/details.gif' border='0' title='".MODIFIER."'></a></td>";
                    print "<td align='right' width='1' bgcolor='".$c."' width='1'><a href='".$_SERVER['PHP_SELF']."?id=".$ids."&action=delete' style='text-decoration:none; background:none'><img src='im/supprimer.gif' border='0' title='".SUPPRIMER."'></a></td>";
                  print "</tr>";
        }
print "</table>";
}

print "<p>";
print "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr><td>";
print CONSEILS;
print "</td></tr></table>";
print "</p>";
print "</td></tr></table>";
?>
<br><br><br>
  </body>
  </html>
