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

 
if(isset($_GET['action']) and $_GET['action'] == "delete") {
   $idZ = $_GET['id']-1000;
   mysql_query("DELETE FROM page_added WHERE page_added_id='".$idZ."'");
   $mess = "<p align='center'><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td class='fontrouge'><center><b>".PAGE_SUPPRIME."</b></tr></td></table><br>";
   unset($_GET['id']);
}

 
function cleanVar($var) {
   $cleanVar = str_replace("\'","&#146;",$var);
   $cleanVar = str_replace("'","&#146;",$cleanVar);
   return $cleanVar;
}

 
$checkQuery = mysql_query("SELECT page_added_id FROM page_added");
if(mysql_num_rows($checkQuery) > 0) {

 
$carMax = 100;


if(isset($_GET['id'])) {
   $idZ = $_GET['id']-1000;
   $idUrl = "?id=".$_GET['id'];
}
else {
   $idZ = "";
   $idUrl = "";
}


if(isset($_POST['usage']) AND ($_POST['usage']=="link" OR $_POST['usage']=="image")) $_POST['message1'] = " ";
if((isset($_POST['message1']) AND $_POST['message1']!=="") AND isset($_GET['id']) AND $_GET['id']!=="" AND isset($_POST['submit']) AND $_POST['submit']==SAUVE) {
	$message = "";
	if(isset($_POST['usage']) AND ($_POST['usage']!=='link' OR $_POST['usage']=="image")) $_POST['url'] = "";
	
	
	if(isset($_FILES["uploadBannerImage"]["name"]) AND !empty($_FILES["uploadBannerImage"]["name"])) {
		
		$nomFichier    = $_FILES["uploadBannerImage"]["name"];
		
		$nomTemporaire = $_FILES["uploadBannerImage"]["tmp_name"];
		
		$typeFichier   = $_FILES["uploadBannerImage"]["type"];
		
		$poidsFichier  = $_FILES["uploadBannerImage"]["size"];
		
		$codeErreur    = $_FILES["uploadBannerImage"]["error"];
		
		$chemin = "../im/";
		if(preg_match("#.jpg$|.gif$|.png$|.swf$#", $nomFichier)) {
			if(copy($nomTemporaire, $chemin.$nomFichier)) {
				$_POST['image'] = str_replace("../","",$chemin).$nomFichier;
				$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_REUSSI.".</p>";
			}
			else {
				$_POST['image'] = "";
				$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</p>";
			}
		}
		else {
			$_POST['image'] = "";
			$message.= "<p align='center' style='color:#FF0000'>&bull;&nbsp;".UPLOAD_IMAGE." <span style='font-size:12px'>".$nomFichier."</span> ".A_ECHOUE."</p>";
		}
	}
	
	if(isset($_POST['message1']) AND $_POST['message1']==" ") $_POST['message1']="";
	if(isset($_POST['usage']) AND $_POST['usage']!=='link') $_POST['url'] = "";

	
   	mysql_query("UPDATE page_added SET 
                  page_added_use = '".$_POST['usage']."',
                  page_added_title_1 = '".cleanVar($_POST['sujet1'])."',
                  page_added_title_2 = '".cleanVar($_POST['sujet2'])."',
                  page_added_title_3 = '".cleanVar($_POST['sujet3'])."',
                  page_added_message_1 = '".cleanVar($_POST['message1'])."',
                  page_added_message_2 = '".cleanVar($_POST['message2'])."',
                  page_added_message_3 = '".cleanVar($_POST['message3'])."',
                  page_added_visible = '".$_POST['visible']."',
                  page_added_image = '".$_POST['image']."',
                  page_added_url = '".$_POST['url']."'
                  WHERE page_added_id = '".$idZ."'
                  ");
   $mess = "<p align='center'><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td class='fontrouge'><center><b>".PAGE_SAVED."</b></tr></td></table>";
}
else {
   if(!isset($_GET['id'])) {
   $mess = "<p align='center'><table border='0' width='700' cellspacing='0' cellpadding='5' class='TABLE'><tr><td class='fontrouge'><center><b>".VEUILLEZ."</b></tr></td></table>";
   }
}


if(isset($_GET['id'])) {
   $queryLoad = mysql_query("SELECT page_added_url, page_added_image, page_added_visible, page_added_use, page_added_id, page_added_title_1, page_added_title_2, page_added_title_3, page_added_message_1, page_added_message_2, page_added_message_3 FROM page_added WHERE page_added_id = '".$idZ."'");
   $queryLoadNum = mysql_num_rows($queryLoad);
   if($queryLoadNum > 0) {
      $laod = mysql_fetch_array($queryLoad);
      $subject1 = $laod['page_added_title_1'];
      $subject2 = $laod['page_added_title_2'];
      $subject3 = $laod['page_added_title_3'];
      $message1 = $laod['page_added_message_1'];
      $message2 = $laod['page_added_message_2'];
      $message3 = $laod['page_added_message_3'];
      if($laod['page_added_use']=='actu') $sel1="checked"; else $sel1="";
      if($laod['page_added_use']=='perso') $sel2="checked"; else $sel2="";
      if($laod['page_added_use']=='link') $sel3="checked"; else $sel3="";
      if($laod['page_added_use']=='image') $sel4="checked"; else $sel4="";
      if($laod['page_added_visible']=='yes') $sel1Z="checked"; else $sel1Z="";
      if($laod['page_added_visible']=='no') $sel2Z="checked"; else $sel2Z="";
      $image = $laod['page_added_image'];
      $url = $laod['page_added_url'];
   }
}
else {
   $message1 = "";
   $message2 = "";
   $message3 = "";
   $sel1 = "checked";
   $sel2 = "";
   $sel2 = "";
   $sel1Z = "checked";
   $sel2Z = "";
   $image = "";
   $url = "";
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
print "<p align='center' class='largeBold'><b>".EDITER_UNE_PAGE."</b></p>";


if(isset($mess)) print $mess;
if(isset($message)) print $message;

 
if(isset($_GET['id'])) {
print "<br>";
print "<table border='0' align='center' width='700' cellspacing='0' cellpadding='0'><tr><td>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='5' class='TABLE'>";
print "<tr>";

print "<form action='eigen_pagina_wijzigen.php".$idUrl."' method='POST'  enctype='multipart/form-data'>";

$datezz = date("Y-m-d");
if(!isset($subject1)) $subject1 = strtoupper($domaineFull)." - ".$datezz;
if(!isset($subject2)) $subject2 = strtoupper($domaineFull)." - ".$datezz;
if(!isset($subject3)) $subject3 = strtoupper($domaineFull)." - ".$datezz;
 
print "<td valign=top>".TITRE."</b><br>(Maximaal ".$carMax." tekens)</td>";
print "<td>";
print "<img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet3' value='".$subject3."' size='80%' class='vullen' maxlength='".$carMax."'>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet1' value='".$subject1."' size='80%' class='vullen' maxlength='".$carMax."'>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
print "<img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;&nbsp;<input type='text' name='sujet2' value='".$subject2."' size='80%' class='vullen' maxlength='".$carMax."'>";
print "</td>";
if(isset($_GET['id'])) {
   print "<td>";
   print "<a href='../bekijken.php?id=".$_GET['id']."&lang=3&do=1' target='_blank'><img src='im/voir.gif' border='0' title='".VOIR."'></a><br>";
   print "<a href='../bekijken.php?id=".$_GET['id']."&lang=1&do=1' target='_blank'><img src='im/voir.gif' border='0' title='".VOIR."'></a><br>";
   print "<a href='../bekijken.php?id=".$_GET['id']."&lang=2&do=1' target='_blank'><img src='im/voir.gif' border='0' title='".VOIR."'></a>";
   print "</td>";
}
print "</tr>";

 
print "<tr>";
print "<td valign=top>Afbeelding</td>";
print "<td colspan='2'>";

				print "&nbsp;&bull;&nbsp;".UPLOAD."<br>&nbsp;<input type='file' name='uploadBannerImage' class='vullen' size='40'>";
				print "&nbsp;<INPUT TYPE='reset' class='knop' NAME='nom' VALUE='".VIDER."'>";
				print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
				print "&nbsp;<input type='text' class='vullen' value='".$image."' name='image' size='40'>&nbsp;(.gif,.jpg,.png,.swf)";
				if($image!=='') print "<p align='center'><img src='../".$image."'></p>";
 
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>".USAGE."</td>";
print "<td colspan='2'>";
print "<input type='radio' name='usage' value='actu' ".$sel1.">&nbsp;".ACTU;
print "<br>";
print "<input type='radio' name='usage' value='perso' ".$sel2.">&nbsp;".PERSO;
print "&nbsp;(".LIEN_PERSO_AFFICHERA_DIVERS.")";
print "<br>";
print "<input type='radio' name='usage' value='link' ".$sel3.">&nbsp;".DIRECT_LINK;
print "&nbsp;(".LIEN_AFFICHERA_DIVERS.")";
print "<br>";
print "<input type='radio' name='usage' value='image' ".$sel4.">&nbsp;Image";
print "&nbsp;(".IMAGE_AFFICHERA_DIVERS.")";
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>URL</td>";
print "<td align='left' colspan='2'>";
print "<input type='text' class='vullen' name='url' value='".$url."' size='85%'>";
print "<br>".UNIQUEMENT_SI."";
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top>".VISIBLE."</td>";
print "<td colspan='2'>";
print "<input type='radio' name='visible' value='yes' ".$sel1Z.">&nbsp;".OUI;
print "&nbsp;|&nbsp;";
print "<input type='radio' name='visible' value='no' ".$sel2Z.">&nbsp;".NON;
print "</td>";
print "</tr>";

print "<tr>";
print "<td valign=top><b>".MESSAGE_HTML."</b><br>".LIRE_NOTE."</td>";
print "<td colspan='3'>";

print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message3'>".$message1."</textarea></td></tr></table>";
print "<br>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message1'>".$message2."</textarea></td></tr></table>";
print "<br>";
print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
print "<td valign=top><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;</td>";
print "<td><textarea cols='80' rows='8' name='message2'>".$message3."</textarea></td></tr></table>";
print "<br>";

print "</td>";

print "</tr>";

if(isset($_GET['id'])) {
print "<tr>";
print "<td valign=top>URL's</td>";
print "<td colspan='2'>";
print "<b>".LIEN_INTERNE."</b><br>";
print "<div style='padding:2px;'>http://".$www2.$domaineFull."/doc.php?id=".$_GET['id']."</div>";
print OU.": doc.php?id=".$_GET['id'];
print "<br><b>".LIEN_EXTERNE."</b><br>";
print "<div style='padding:2px;'><img src='im/nl.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$_GET['id']."&amp;lang=3</div>";
print "<div style='padding:2px;'><img src='im/fr.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$_GET['id']."&amp;lang=1</div>";
print "<div style='padding:2px;'><img src='im/uk.gif' align='absmiddle'>&nbsp;&nbsp;http://".$www2.$domaineFull."/doc.php?id=".$_GET['id']."&amp;lang=2</div>";
print "</td>";
print "</tr><tr>";
}

if(isset($_GET['id'])) $fond= "style='background:#999999'"; else $fond="style='background:#FFFFCC'";
print "<tr>";
print "<td align='center' colspan='3' height='40'><input type='submit' value='".SAUVE."' name='submit' class='knop'></td>";
 
print "</form>";
print "</tr>";

print "</table>";
}


if(isset($_POST['pageZ']) AND $_POST['pageZ']=='all') {$_addQuery="1"; $sel1="selected"; $sel2=""; $sel3=""; $sel4=""; $sel5="";}
if(isset($_POST['pageZ']) AND $_POST['pageZ']=='perso') {$_addQuery="page_added_use='perso'"; $sel1=""; $sel2="selected"; $sel3=""; $sel4=""; $sel5="";}
if(isset($_POST['pageZ']) AND $_POST['pageZ']=='actu') {$_addQuery="page_added_use='actu'"; $sel1=""; $sel2=""; $sel3="selected"; $sel4=""; $sel5="";}
if(isset($_POST['pageZ']) AND $_POST['pageZ']=='link') {$_addQuery="page_added_use='link'"; $sel1=""; $sel2=""; $sel3=""; $sel4="selected"; $sel5="";}
if(isset($_POST['pageZ']) AND $_POST['pageZ']=='image') {$_addQuery="page_added_use='image'"; $sel1=""; $sel2=""; $sel3=""; $sel4=""; $sel5="selected";}
if(!isset($_POST['pageZ'])) {$_addQuery="1"; $sel1="selected"; $sel2=""; $sel3=""; $sel4=""; $sel5="";}



print "<br>";
print "<br>";
print "<br>";
 
$querySent = mysql_query("SELECT * FROM page_added WHERE ".$_addQuery." ORDER BY page_added_use ASC, page_added_id DESC");
$message1Count = mysql_num_rows($querySent);

if($message1Count>0) {
print (isset($_GET['id']))? "<table border='0' width='100%' cellspacing='0' cellpadding='3' class='TABLE'>" : "<table border='0' align='center' width='700' cellspacing='0' cellpadding='3' class='TABLE'>";
print "<tr>";
print "<td align='center' height='30' colspan='5'>";
print "<b>".PAGE_ENREGISTRE."</b>";
print "</td></tr><tr>";
print "<td align='center' height='30' colspan='5' class='largeBold'>";
print "<div align='center'>";
print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<select name='pageZ'>";
print "<option value='all' ".$sel1.">".TOUTES."</option>";
print "<option value='perso' ".$sel2.">".PERSOS."</option>";
print "<option value='actu' ".$sel3.">".ACTUS."</option>";
print "<option value='link' ".$sel4.">".LINK."</option>";
print "<option value='image' ".$sel5.">Image</option>";
print "</select>";
print "&nbsp;<input type='submit' class='knop' value='  OK  '>";
print "</form>";
print "</div>";
print "</td>";


print "</tr>";
        $i=1;
        $c="";
        while($messageStock = mysql_fetch_array($querySent)) {
               if($c=="#FFFFFF") {$c="#FFFFFF";} else {$c="#FFFFFF";}
               $ids = $messageStock['page_added_id']+1000;
               if(isset($idZ) AND $idZ>0 AND $messageStock['page_added_id']==$idZ) $c="#FFFFFF";
               if($messageStock['page_added_message_3']=="") $frTitle = "--"; else $frTitle = $messageStock['page_added_title_3'];
               if($messageStock['page_added_message_1']=="") $enTitle = "--"; else $enTitle = $messageStock['page_added_title_1'];
               if($messageStock['page_added_message_2']=="") $esTitle = "--"; else $esTitle = $messageStock['page_added_title_2'];
               if($messageStock['page_added_message_3']!=="" AND $messageStock['page_added_title_3']=="") $frTitle = NOTITLE;
               if($messageStock['page_added_message_1']!=="" AND $messageStock['page_added_title_1']=="") $enTitle = NOTITLE;
               if($messageStock['page_added_message_2']!=="" AND $messageStock['page_added_title_2']=="") $esTitle = NOTITLE;
               if($messageStock['page_added_use']=="link") {
			   		$frTitle = $messageStock['page_added_title_3'];
			   		$enTitle = $messageStock['page_added_title_1'];
			   		$esTitle = $messageStock['page_added_title_2'];
			   	}
               if($messageStock['page_added_use']=="image") {
			   		$frTitle = "";
			   		$enTitle = "<img src='../".$messageStock['page_added_image']."' border='0'>";
			   		$esTitle = "";
			   	}
            print "<tr>";
                    print "<td height='45' width='110' bgcolor='".$c."'>";
                        print "Pagina ".$i++."<br>";
                        $visible = ($messageStock['page_added_visible']=='yes')? "<b>".ucfirst(strtolower(OUI))."</b>" : "<b><span style='color:#FF0000; padding:1px;'>".NON."</span></b>";
                        print VISIBLE.": ".$visible."<br>";
                        print "Datum: ".dateFr($messageStock['page_added_date'],$_SESSION['lang'])."<br>";
                        print "Pagina ID: <b>".$ids."</b><br>";
                        if($messageStock['page_added_use']=="actu") {
                           print USAGE.": <span style='background-color:#00CC00; color:#FFFFFF; padding:1px'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                        }
                        if($messageStock['page_added_use']=="perso") {
                           print USAGE.": <span style='background-color:#FFFF00; color:#000000; padding:1px'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                        }
                        if($messageStock['page_added_use']=="link") {
                           print USAGE.": <span style='background-color:#0000FF; color:#FFFFFF; padding:1px'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                        }
                    	if($messageStock['page_added_use']=="image") {
                       		print USAGE.": <span style='background-color:#FF00FF; color:#FFFFFF'><b>".strtoupper($messageStock['page_added_use'])."</b></span>";
                    	}
                    print "</td>";
                    print "<td bgcolor='".$c."'>";

                    print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
                    print "<td>";
                    print "<div align='left'><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'>&nbsp;<i>".$frTitle."</i></div>";
                    print "</td>";
                    print "</tr></table>";
                    print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
                    print "<td>";
                    print "<div align='left'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'>&nbsp;<i>".$enTitle."</i></div>";
                    print "</td>";
                    print "</tr></table>"; 
                    print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
                    print "<td>";
                    print "<div align='left'><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'>&nbsp;<i>".$esTitle."</i></div>";
                    print "</td>";
                    print "</tr></table>"; 
                    print "</td>";
                    print "<td align='right' width='1' bgcolor='".$c."'><a href='eigen_pagina_wijzigen.php?id=".$ids."' style='background:none; text-decoration:none'><img src='im/details.gif' border='0' title='".CHARGER."'></a></td>";
                    print "<td align='right' width='1' bgcolor='".$c."' width='1'><a href='".$_SERVER['PHP_SELF']."?id=".$ids."&action=delete' style='background:none; text-decoration:none'><img src='im/supprimer.gif' border='0' title='".SUPPRIMER."'></a></td>";
                  print "</tr>";
        }
print "</table>";
}

if(isset($_GET['id'])) {
print "<p>";
print "<table width='100%' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr><td>";
print CONSEILS;
print "</td></tr></table>";
print "</p>";
}
print "</table>";
print "</td></tr></table>";
}
else {
         $mess = "<center><table width='700' border='0' cellspacing='0' cellpadding='5' class='TABLE'><tr><td><p align='center' class='fontrouge'><b>".AUCUNE."</b></p>";
         $mess.= "<p align='center'><a href='eigen_pagina_toevoegen.php'>".AJOUTER."</a></p></tr></td>";
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php
print "<p align='center' class='largeBold'><b>".EDITER_UNE_PAGE."</b></p>";
   if(isset($mess)) print $mess;
}
?>
<br><br><br>
  </body>
  </html>
