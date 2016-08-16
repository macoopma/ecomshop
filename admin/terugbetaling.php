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
$rr="";
if(isset($_GET['action']) and $_GET['action'] == "go") {

      if(empty($_GET['nic1']) and empty($_GET['pass1']) and empty($_GET['facture1'])) {
         header("Location: ".$_SERVER['PHP_SELF']."");
      }
      else {

        if(isset($_GET['pass1']) and !empty($_GET['pass1'])) {
           $_GET['pass1'] = strtoupper($_GET['pass1']);
           $rr .= "AND users_id='".$_GET['pass1']."'";
        }
 
        if(isset($_GET['nic1']) and !empty($_GET['nic1'])) {
        	$FactPrefix = str_replace("||","",$factPrefixe);
        	$_GET['nic1'] = str_replace($FactPrefix, $factPrefixe, $_GET['nic1']);
           	$rr .= "AND users_nic='".$_GET['nic1']."'";
        }
  
           $query66 = mysql_query("SELECT users_id, users_payed FROM users_orders WHERE 1 ".$rr."");
          if(mysql_num_rows($query66) > 0) {
             $row66 = mysql_fetch_array($query66);
             if($row66['users_payed']=="no") {
             	$valPassedw = COMMANDE_NON_PAYEE;
			 	$message = "?message=".$valPassedw;
			 	header("Location: terugbetaling.php".$message);
			 	exit;
			 }
			 else {
			 	$id = $row66['users_id'];
			}
          }
          else {
             if(isset($_GET['nic1']) and !empty($_GET['nic1'])) $valPassed = A61;
             if(isset($_GET['pass1']) and !empty($_GET['pass1'])) $valPassed = A62;
             $message = "?message=".$valPassed;
             header("Location: terugbetaling.php".$message);
             exit;
          }
          
   
        if(isset($_GET['facture1']) and !empty($_GET['facture1'])) {
        	##$FactPrefix = str_replace("||","",$factPrefixe);
        	##$_GET['facture1'] = str_replace($FactPrefix, $factPrefixe, $_GET['facture1']);
	        $FactPrefix = str_replace("||","",$factPrefixe);
	        if(isset($FactPrefix) AND $FactPrefix!=="") $_explodeFact = explode($FactPrefix, $_GET['facture1']);
	        if(isset($_explodeFact[1]) AND !empty($_explodeFact[1])) {
				$_GET['facture1'] = $_explodeFact[1];
				$_request = "LIKE '%||".$_GET['facture1']."'";
			}
			else {
				$_request = "='||".$_GET['facture1']."'";
			}
			
           	$query666 = mysql_query("SELECT users_id, users_payed FROM users_orders WHERE users_fact_num ".$_request."");
            if(mysql_num_rows($query666) > 0) {
               $row666 = mysql_fetch_array($query666);
	             if($row666['users_payed']=="no") {
	             	$valPassedw = COMMANDE_NON_PAYEE;
				 	$message = "?message=".$valPassedw;
				 	header("Location: terugbetaling.php".$message);
				 	exit;
				 }
				 else {
				 	$id = $row666['users_id'];
				}
            }
            else {
               $message = "?message=".A60;
               header("Location: terugbetaling.php".$message);
               exit;
            }
        }
         header("Location: terugbetaling_detail.php?id=".$id);
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
<p align="center" class="largeBold"><?php print A6;?></p>

<p align="center"><?php print A1;?></p>

<?php
if(isset($_GET['message'])) print "<p align='center' style='color:#CC0000'><b>".$_GET['message']."</b></p>";
?>

<form action='<?php print $_SERVER['PHP_SELF'];?>' method='GET' target='main'>
<input type="hidden" name="action" value="go">


<table border="0" width="700" align="center" cellpadding="3" cellspacing = "5" class="TABLE" style="padding:5px;">
<tr>
        <td>NIC</td><td><input type="text" class="vullen" size="10" name="nic1" value=""></td>
</tr>
<tr>
        <td colspan="2" align="center"><b><?php print A2;?></b></td>
</tr>
<tr>
        <td><?php print A3;?></td><td><input type="text" class="vullen" size="10" name="pass1" value=""></td>
</tr>
<tr>
        <td colspan="2" align="center"><b><?php print A2;?></b></td>
</tr>
<tr>
        <td><?php print A4;?></td><td><input type="text" class="vullen" size="10" name="facture1" value=""></td>
</tr>
</table>

<br>

<table border="0" width="700" align="center" cellpadding="5" cellspacing ="0" class="TABLE">
<tr>
        <td colspan="2" align="center"><input type="Submit" value="<?php print A5;?>" class="knop">
</td>
</tr>
</table>
</form>

</body>
</html>
