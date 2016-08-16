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
         // NIC
        if(isset($_GET['nic1']) and !empty($_GET['nic1'])) {
        	$FactPrefix = str_replace("||","",$factPrefixe);
        	$_GET['nic1'] = str_replace($FactPrefix, $factPrefixe, $_GET['nic1']);
           	$rr .= "AND users_nic='".$_GET['nic1']."'";
        }

             $query66 = mysql_query("SELECT users_id FROM users_orders WHERE 1 ".$rr."");
          if(mysql_num_rows($query66) > 0) {
             $row66 = mysql_fetch_array($query66);
             $id = $row66['users_id'];
          }
          else {
             if(isset($_GET['nic1']) and !empty($_GET['nic1'])) $valPassed = A61;
             $message = "?message=".$valPassed;
             header("Location: zoeken.php".$message);
             exit;
          }
        
 
        if(isset($_GET['facture1']) and !empty($_GET['facture1'])) {
  
    
			$query66FactNumRequest = mysql_query("SELECT users_fact_num FROM users_orders WHERE users_fact_num!=''");
			if(mysql_num_rows($query66FactNumRequest) > 0) {
				while($_facturesNumResult = mysql_fetch_array($query66FactNumRequest)) {
					$explodeOnce = explode("||", $_facturesNumResult['users_fact_num']);
					if(!isset($explodeOnce[1])) $explodeOnce[1] = $explodeOnce[0];
					$_facturesNums = $explodeOnce[1];
					if(preg_match("#".$_facturesNums."$#i", $_GET['facture1']) AND preg_match("#||".$_facturesNums."$#i", $_facturesNumResult['users_fact_num'])) {
						$_facturesNum[]=$_facturesNumResult['users_fact_num'];
					}
					else {
						$_request = "='1656444'";
					}
				}
 
				if(isset($_facturesNum)) {
        			foreach($_facturesNum as $key => $value) {
            			if($value == "") unset($_facturesNum[$key]);
        			}
				}
				else {
					$_request = "='1656444'";
				}

				if(isset($_facturesNum) AND count($_facturesNum)>0) {
					$newArray = array_merge(array(),$_facturesNum);
					$_request = "LIKE '%".$newArray[0]."'";
				}
				else {
					$_request = "='||".$_GET['facture1']."'";
				}
			}
			else {
				$_request = "='1656444'";
			}

           	$query66Fact = mysql_query("SELECT users_id FROM users_orders WHERE users_fact_num ".$_request."");
           	if(mysql_num_rows($query66Fact) > 0) {
               $row66Fact = mysql_fetch_array($query66Fact);
               $id = $row66Fact['users_id'];
            }
            else {
               $message = "?message=".A60;
               header("Location: zoeken.php".$message);
               exit;
            }
        }
        
		if((isset($_GET['nic1']) AND !empty($_GET['nic1'])) OR (isset($_GET['facture1']) AND !empty($_GET['facture1']))) {
           header("Location: detail.php?id=".$id."&from=facture");
		}

		if(isset($_GET['pass1']) AND !empty($_GET['pass1'])) {
                   $query666 = mysql_query("SELECT users_id FROM users_orders WHERE users_password = '".$_GET['pass1']."'");
                  if(mysql_num_rows($query666) > 0) {
                     header("Location: mijnklant.php?id=".$_GET['pass1']."");
                  }
                  else {
                     $message = "?message=".A62;
                     header("Location: zoeken.php".$message);
                     exit;
                  }
		}
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
<p align="center" class="largeBold"><?php print A1;?></p>

<p align="center">&nbsp;</p>

<?php
if(isset($_GET['message'])) print "<p align='center' style='color:#CC0000'><b>".$_GET['message']."</b></p>";
?>

<form action='<?php print $_SERVER['PHP_SELF'];?>' method='GET' target='main'>
<input type="hidden" name="action" value="go">


<table border="0" width="700" align="center" cellpadding="3" cellspacing = "5" class="TABLE" style="padding:5px;">
<tr>
        <td><?php print AID;?> (NIC)</td><td><input type="text" class="vullen" size="10" name="nic1" value=""></td>
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
