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
include("lang/lang".$_SESSION['lang']."/".incLang($_SERVER['PHP_SELF']));
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
if(!IsSet($_GET['page'])) {
        echo "<p align='center'><table border='0' cellpadding='5' cellspacing='0' align='center' class='TABLE' width='700'><tr><td><center>";
        echo A2."<br><br>";
        echo "<a href='vraag_bestelling_verwijderen.php?page=delete&id=".$_GET['id']."&yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'><b>".A3."</b></a>";
        echo "&nbsp;|&nbsp;";
        echo "<a href='klanten.php?yo=".$_GET['yo']."&jour1=".$_GET['jour1']."&mois1=".$_GET['mois1']."&an1=".$_GET['an1']."&jour2=".$_GET['jour2']."&mois2=".$_GET['mois2']."&an2=".$_GET['an2']."'><b>".A4."</b></a>";
        echo "</tr></table>";
}

if(isset($_GET['page']) and $_GET['page'] == "delete") {
        $query = mysql_query("DELETE FROM users_orders WHERE users_id='".$_GET['id']."'");
?>
<script type="text/javascript">
<!--
document.location='klanten.php?yo=<?php print $_GET['yo'];?>&jour1=<?php print $_GET['jour1'];?>&mois1=<?php print $_GET['mois1'];?>&an1=<?php print $_GET['an1'];?>&jour2=<?php print $_GET['jour2'];?>&mois2=<?php print $_GET['mois2'];?>&an2=<?php print $_GET['an2'];?>';
//-->
</script>
<?php
}
?>
</body>
</html>
