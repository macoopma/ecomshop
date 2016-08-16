<?php
session_start();

if(!isset($_SESSION['login'])) header("Location:index.php");


function dateFr($fromDate,$langId) {
     $_qq = explode(" ",$fromDate);
   	 $_qq1 = explode("-",$_qq[0]);
   	 if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
   	 if($langId==2) $_qq3 = $_qq[0];
   	 return $_qq3;
}

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
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));



function backupDatabase($link , $db_name , $structure , $donnees , $format , $insertComplet="") {
  global $bddName;
  if(!is_resource($link))
  return false;
  mysql_select_db($db_name);

  $format = strtoupper($format);

  $filename = "mysql-backup/backup_".$db_name."_".date("d_m_Y").".sql";
  $fp = fopen($filename, "w");
  if(!is_resource($fp))
  return false;

  $res = mysql_query( 'SHOW TABLES' );
  $num_rows = mysql_num_rows($res);
  $i = 0;
  while ($i < $num_rows) {
    $tablename = mysql_tablename($res, $i);
    
    if($structure === true) {
      fwrite($fp, "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\nDROP TABLE IF EXISTS `$tablename`;\n");
 
      $query = "SHOW CREATE TABLE ".$tablename;
      $resCreate = mysql_query($query);
      $row = mysql_fetch_array($resCreate);
      $schema = $row[1].";";
      fwrite($fp, "$schema\n\n");
    }
    
    if($donnees === true) {
  
      $query = "SELECT * FROM $tablename";
      $resData = mysql_query($query);
      if(mysql_num_rows($resData) > 0) {
        $sFieldnames = "";
        if($insertComplet === true) {
        $num_fields = mysql_num_fields($resData);
          for($j=0; $j < $num_fields; $j++) {
            $sFieldnames .= "`".mysql_field_name($resData, $j)."`, ";
          }
        $sFieldnames = "(".substr($sFieldnames, 0, -1).")";
        }
        $sInsert = "INSERT INTO `$tablename` $sFieldnames values ";
      
        while($rowdata = mysql_fetch_assoc($resData)) {
        $lesDonnees = "<guillemet>".implode("<guillemet>, <guillemet>", $rowdata)."<guillemet>";
        $lesDonnees = str_replace("<guillemet>", "'", addslashes($lesDonnees));
      
        if($format == "INSERT") {
        $lesDonnees = "$sInsert($lesDonnees);";
        }
        fwrite($fp, "$lesDonnees\n");
        }
      }
    }
    $i++;
  }
  fclose($fp);
  	print "<p align='center' style='color:#CC0000; font-size:12px'>".A1." <b>".strtoupper($bddName)."</b> ".A2."<br>";
	print "<br><br><div align='center'><span style='padding:5px; border:1px #CCCCCC solid; background:#FFFFFF'>".A3." <b><a href='".$filename."' target='blank' style='text-decoration:none'>admin/".$filename."</a></b></span></div><br>";
    print "</p>";
}
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A4;?> <?php print strtoupper($bddName);?></p>
<?php
if(isset($_POST['action2']) AND $_POST['action2']=='go') {
	$link = mysql_connect($bddHost,$bddUser,$bddPass);
	backupDatabase($link , $bddName , true , true , 'INSERT' , $insertComplet="true");
}

print "<div align='center'>";
print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
print "<input type='hidden' name='action2' value='go'>";
print "<input type='submit' class='knop' value='".SAVE_DATABASE."'>";
print "</form>";
print "</div>";


		// Display backup
		$dir = "mysql-backup";
		if (@is_dir($dir)) {
			print "<p align='center'>";
		    if ($dh = @opendir($dir)) {
		        if(isset($dh)) {
		        	$notInArray = array("index.php", "..", ".");
					while (($file = @readdir($dh)) !== false) {
			        	if(isset($file) AND $file!=='' AND !in_array($file, $notInArray)) {
			        	    $fileDate = str_replace(".sql","",$file);
			        		$fileDate =  substr($fileDate, -10);
			        		$fileDate = str_replace("_","-",$fileDate);
			        		$fileTaille = @round(filesize("mysql-backup/".$file)/1000);
		        			print "<hr width='500px'>";
							print "<div align='center' style='padding:5px'><u>".HISTORIQUE.date($fileDate, $_SESSION['lang'])."</u></div>";
							print "<div align='center' style='padding:3px'>";
							print "&bull; <a href='mysql-backup/".$file."' target='blank' style='text-decoration:none; background:none'>admin/mysql-backup/".$file."</a>";
			        		if(isset($fileTaille) AND $fileTaille!=="")  print "&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;".TAILLE.": ".$fileTaille." Kb";
			        		print "</div>";
						}
					}
			        @closedir($dh);
			    }
		    }
		    print "</p>";
		}
?>
</body>
</html>
