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
$n = 60;
if(isset($_GET['majUrl']) AND $_GET['majUrl']!=="") $_GET['majUrl'] = str_replace("/","=",$_GET['majUrl']);
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='style.css'>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript"><!--
function check_add() {

  rejet = false;
  rejet1 = false;
  falsechar="";
  var non = "+";
  var non1 = "'";
  var error = 0;
  var error_message = "";
  
  for(i=0 ; i <= form1.AddValue.value.length ; i++) { if((form1.AddValue.value.charAt(i)==non)) {rejet=true; falsechar= non;}}
  if(rejet==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  for(i=0 ; i <= form1.AddValue.value.length ; i++) { if((form1.AddValue.value.charAt(i)==non1)) {rejet1=true; falsechar= non1;}}
  if(rejet1==true) {
    error_message = error_message + falsechar+" <?php print A330;?>\n";
    error = 1;
  }
  
  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<p align="center" class="largeBold"><?php print A1;?></p>

<?php
$ProductNameQuery = mysql_query("SELECT products_id, products_name_".$_SESSION['lang'].", products_download FROM products WHERE products_id = '".$_GET['productId']."'");
$productName1 = mysql_fetch_array($ProductNameQuery);
$productName = $productName1['products_name_'.$_SESSION['lang']];
$productDownload = $productName1['products_download'];
$productId = $productName1['products_id'];

$optionNameQuery = mysql_query("SELECT * FROM products_options WHERE products_options_id = '".$_GET['optionId']."'");
$optionName = mysql_fetch_array($optionNameQuery);
$optionName = $optionName['products_options_name'];

print "<p align='center'><b>".A2."</b>: <a href='artikel_wijzigen_details.php?id=".$productId."'>".strtoupper($productName)."</a> - <b>".A3."</b>: ".strtoupper($optionName);
// Afficher instructions
print "<p align='center'>";
print "<table border='0' width='700' cellpadding='0' cellspacing ='5' align='center' class='TABLE'><tr><td align='left'>";
print "<b>[2]</b></span> - ".A10A;
print "</td></tr></table>";
print "</p>";
print "</p>";
$message = "<p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A4." ".strtoupper($optionName)."</b></p>";

 
if(!empty($_GET['noteOption1']) OR !empty($_GET['noteOption2']) OR !empty($_GET['noteOption3'])) {
    $_SESSION['noteOption'] = $_GET['noteOption1']."!!!!".$_GET['noteOption2']."!!!!".$_GET['noteOption3'];
}

 
if(isset($_GET['action']) AND $_GET['action'] == A25) {
    // add values
    if(isset($_GET['do']) AND $_GET['do']=="update") {
         $queryUpdate = mysql_query("SELECT products_id
                                      FROM products_id_to_products_options_id
                                      WHERE products_id = '".$_GET['productId']."'
                                      AND products_option = '".$_SESSION['ajouterValue']."'");
        $queryUpdateNum = mysql_num_rows($queryUpdate);
         if($queryUpdateNum>0) {
             $message = "<b>**".A5."!**</b><p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A6."</b></span></p>";
         }
         else {
            $queryUpdate2 = mysql_query("SELECT products_option
                                        FROM products_id_to_products_options_id
                                        WHERE products_id = '".$_GET['productId']."'
                                        AND products_options_id = '".$_GET['optionId']."'");      
            $addOp = mysql_fetch_array($queryUpdate2);
            $newVar = $addOp['products_option'].$_SESSION['ajouterValue'];
             
            mysql_query("UPDATE products_id_to_products_options_id
                          SET products_option = '".$newVar."'
                          WHERE products_id = '".$_GET['productId']."'
                          AND products_options_id = '".$_GET['optionId']."'");   

             $message = "<p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A7."</b></span></p>";
             $message.= "<a href='opties_maken.php?id=".$_GET['productId']."'>".A8."</a>";
             unset($_SESSION['ajouterValue']);
         }

    }
    else {
       $confirmQuery = mysql_query("SELECT products_id
                                  FROM products_id_to_products_options_id
                                  WHERE products_id = '".$_GET['productId']."'
                                  AND products_option = '".$_SESSION['ajouterValue']."'");
       $confirmResult = mysql_num_rows($confirmQuery);
       if($confirmResult>0) {
           $message = "<b>**".A5."!**</b><p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A6."</b></span></p>";
       }
       else {
         $ra = explode(",",$_SESSION['ajouterValue']);
         asort($ra);
         $ra = implode(",",$ra).",";
         $ra = substr($ra, 1);
         $_SESSION['ajouterValue'] = $ra;
         if(isset($_SESSION['noteOption'])) {
            $note = explode("!!!!", $_SESSION['noteOption']);
            $note_1 = str_replace("'","&#146;",$note[0]);
            $note_2 = str_replace("'","&#146;",$note[1]);
            $note_3 = str_replace("'","&#146;",$note[2]);
         }
         elseif(isset($_GET['noteOption1']) OR isset($_GET['noteOption2']) OR isset($_GET['noteOption2'])) {
            $note_1 = str_replace("'","&#146;",$_GET['noteOption1']);
            $note_2 = str_replace("'","&#146;",$_GET['noteOption2']);
            $note_3 = str_replace("'","&#146;",$_GET['noteOption3']);
         }
         else {
            $note_1 = '';
            $note_2 = '';
            $note_3 = '';
         }
         mysql_query("INSERT INTO products_id_to_products_options_id
                      (products_id,
                       products_options_id,
                       products_option,
                       note_option_1,
                       note_option_2,
                       note_option_3)
                      VALUES
                      ('".$_GET['productId']."',
                       '".$_GET['optionId']."',
                       '".$_SESSION['ajouterValue']."',
                       '".$note_1."',
                       '".$note_2."',
                       '".$note_3."')
                      ");
         $message = "<p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A7."</b></span></p>";
         $message.= "<a href='opties_maken.php?id=".$_GET['productId']."'>".A8."</a>";
         unset($_SESSION['ajouterValue']);
         if(isset($_SESSION['noteOption'])) unset($_SESSION['noteOption']);
         mysql_query("UPDATE products SET products_options = 'yes' WHERE products_id = '".$_GET['productId']."'");
       }
    }
}

 
if(isset($_GET['deleteVal'])) {
   $yiu = str_replace(":: ","::+",$_GET['deleteVal']);
   $_SESSION['ajouterValue'] = str_replace($yiu.",","",$_SESSION['ajouterValue']);
}

 
if(isset($_GET['action']) AND $_GET['action'] == A9) {
  if(isset($_SESSION['ajouterValue'])) {
  	$_SESSION['ajouterValue'] = $_SESSION['ajouterValue'];
  }
  else {
  	$_SESSION['ajouterValue'] = "";
  }
  
  if(!isset($_GET['optionValue'])) {
       $aj = mysql_query("SELECT * FROM products_options
                          WHERE products_options_id = '".$_GET['optionId']."'");
       $ajName = mysql_fetch_array($aj);
       $message = "<span class='fontrouge'><b>".A27." ".strtoupper($ajName['products_options_name'])."!</b></span>";
  }
  else {
	if(isset($_GET['do']) AND $_GET['do']=="update") {
              $queryUpdate2 = mysql_query("SELECT products_option
                                        FROM products_id_to_products_options_id
                                        WHERE products_id = '".$_GET['productId']."'
                                        AND products_options_id = '".$_GET['optionId']."'");      
              $addOp = mysql_fetch_array($queryUpdate2);
              
              $aj = mysql_query("SELECT * FROM products_options_values WHERE products_options_values_id = '".$_GET['optionValue']."'");
              $ajName = mysql_fetch_array($aj);
   
                   $pos = strstr($addOp['products_option'], $ajName['products_options_values_name']."::");
                   if(!empty($pos)) {
                       $message = "<b>**".A5."!**</b><p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A10."</b></span></p>";
                   }
                   else {
                      if($_GET['majPrix']=="" OR !is_numeric($_GET['majPrix'])) {$_GET['majPrix']=0;}
                      $_GET['majPrix'] = sprintf("%0.2f",$_GET['majPrix']);
                      if($_GET['majPoids']=="" OR !is_numeric($_GET['majPoids'])) {$_GET['majPoids']=0;}
                      if($_GET['majUrl']=="") {$_GET['majUrl']="";}
                      $_SESSION['ajouterValue'] = $ajName['products_options_values_name']."::".$_GET['maj'].$_GET['majPrix']."::".$_GET['majPoids']."::".$_GET['majUrl'].",".$_SESSION['ajouterValue'];
                   }
              
    }
    else {
       $aj = mysql_query("SELECT * FROM products_options_values WHERE products_options_values_id = '".$_GET['optionValue']."'");
       $ajName = mysql_fetch_array($aj);
  
       $pos = strstr($_SESSION['ajouterValue'], $ajName['products_options_values_name']."::");
       if(!empty($pos)) {
           $message = "<b>**".A5."!**</b><p><img src='im/note.gif' align='absmiddle'>&nbsp;<span class='fontrouge'><b>".A10."</b></span></p>";
       }
       else {
          if($_GET['majPrix']=="" or !is_numeric($_GET['majPrix'])) {$_GET['majPrix']=0;}
          $_GET['majPrix'] = sprintf("%0.2f",$_GET['majPrix']);
          if($_GET['majPoids']=="" or !is_numeric($_GET['majPoids'])) {$_GET['majPoids']=0;}
          if($_GET['majUrl']=="") {$_GET['majUrl']="";}
          $_SESSION['ajouterValue'] = $ajName['products_options_values_name']."::".$_GET['maj'].$_GET['majPrix']."::".$_GET['majPoids']."::".$_GET['majUrl'].",".$_SESSION['ajouterValue'];
       }
    }
  }
}

 
if(isset($_GET['action']) and $_GET['action']==A11 AND isset($_GET['optionValue']) AND !empty($_GET['optionValue'])) {

     $yo = mysql_query("SELECT * FROM products_options_values WHERE products_options_values_id = '".$_GET['optionValue']."'");
     if(mysql_num_rows($yo)>0) {
         $yoName = mysql_fetch_array($yo);
         mysql_query("DELETE FROM products_options_values WHERE products_options_values_id='".$_GET['optionValue']."'");
         $message = "<span class='fontrouge'>".A12." <b>".strtoupper($yoName['products_options_values_name'])."</b> ".A13."</span>";
     }
}
 
if(isset($_GET['action']) and $_GET['action']==A14) {
     if(empty($_GET['AddValue'])) {
        $message = "<span class='fontrouge'>".A15."</span>";
     }
     else {
     	$_GET['AddValue'] = str_replace("'","&#146;",$_GET['AddValue']);
        if(strlen($_GET['AddValue'])>$n-3) {$AddValue = substr($AddValue,0,$n)."...";} else {$AddValue = $_GET['AddValue'];}

           $vQuery = mysql_query("SELECT *
                                  FROM products_options_values
                                  WHERE products_options_values_name = '".$AddValue."'
								  AND products_options_id = '".$_GET['optionId']."'");

           $vResult = mysql_num_rows($vQuery);
           if($vResult > 0) {
              $message = "<span class='fontrouge'>".A12." <b>".$_GET['AddValue']."</b> ".A16."</span>";
           }
           else {
				$_GET['AddValue'] = str_replace(",",".",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("/","-",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("®","",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("|","-",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("\"","",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("\'\'","",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("+","&#134;",$_GET['AddValue']);
				$_GET['AddValue'] = str_replace("'","&#146;",$_GET['AddValue']);

				mysql_query("INSERT INTO products_options_values (products_options_values_name, products_options_id) VALUES ('".$_GET['AddValue']."', '".$_GET['optionId']."')");
				$message = "<span class='fontrouge'>".A17." <b>".strtoupper($_GET['AddValue'])."</b> ".A18."</span>";
           }
     }
}

 
$optionNameQuery = mysql_query("SELECT products_options_values_name, products_options_values_id
                                FROM products_options_values
                                WHERE products_options_id = '".$_GET['optionId']."'
                                ORDER BY products_options_values_name");

print "<form action='".$_SERVER['PHP_SELF']."' name='form1' method='GET' onsubmit='return check_add()';>";
print "<input type='hidden' name='productId' value='".$_GET['productId']."'>";
print "<input type='hidden' name='optionId' value='".$_GET['optionId']."'>";
    if(isset($_GET['do']) AND $_GET['do']=="update") {
        print "<input type='hidden' name='do' value='update'>";
    }

print "<table border='0'  width='700' cellpadding='5' cellspacing ='0' align='center' class='TABLE'";
print "<tr height='50' >";
 
print "<td align='left' valign='middle'>";
print "<input type='text' class='vullen' name='AddValue' size='30' maxlength='".$n."'>";
print "&nbsp;&nbsp;&nbsp;<INPUT TYPE='submit' class='knop' name='action' VALUE='".A14."'>";
// 60 tekens print "<br>".$n." ".A20;
print "</td>";

print "</td>";
print "</tr>";
print "<TR>";



print "<td colspan='3' height='35'>";
    print "<b>".A26." '".$optionName."'</b>&nbsp;&nbsp;&nbsp; ";
    print "<select name='optionValue'>";
            while($optionName1 = mysql_fetch_array($optionNameQuery)) {
                print "<option name='optionValue' value='".$optionName1['products_options_values_id']."'>".$optionName1['products_options_values_name'];
                print "</option>";
            }
    print "</select>&nbsp;&nbsp;<INPUT TYPE='submit' class='knop' name='action' VALUE='".A11."'>";
print "</td>";
print "</tr>";
 
print "<tr>";
print "<td colspan='3'><br><br><br>";
        print A19."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        print "<select name='maj'>";
        print "<option name='maj' value='+'>+</option>";
        print "<option name='maj' value='-'>-</option>";
        print "</select>";
        print "&nbsp;&nbsp;&nbsp;";
        print "<INPUT TYPE='text' class='vullen' size='10' name='majPrix'>";
        print "&nbsp;&nbsp;".$devise;
print "</td>";
print "</tr>";

 
print "<tr>";
print "<td colspan='3'>";
        print A500."&nbsp;&nbsp;&nbsp;";
        print "&nbsp;&nbsp;";
        print "&nbsp;&nbsp;&nbsp;<INPUT TYPE='text' size='7' class='vullen' value='0' name='majPoids'>";
        print "&nbsp; gram";
print "</td>";
print "</tr>";

 
if($productDownload=='yes') {
    print "<tr>";
    print "<td colspan='3'>";
            print URL_DE_TELECHARGEMENT."&nbsp;";
            print "&nbsp;&nbsp;";
            print "<INPUT TYPE='text' size='45' class='vullen' value='' name='majUrl'>";
            print "<div align='left'>".URL_COMPLET."<br>Voorbeeld: http://".$www2.$domaineFull."/download/bestand.zip<br>".NE_PAS_OUBLIER.".</div>";
    print "</td>";
    print "</tr>";
}
else {
    print "<INPUT TYPE='hidden' value='' name='majUrl'>";
}
// ga verder
print "<tr>";
print "<td colspan='3'>";
print "<img src='im/arrows.gif' align='absmiddle'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='submit' name='action' VALUE='".A9."' class='knop'>";
print "</td>";

print "</tr>";
print "<tr bgcolor='#7AC52D'>";
 
print "</tr>";
 
if(isset($message) AND $message!=='') {
    print "<tr>";
    print "<td height='30' colspan='3' style='background:#FFFFFF'>";
    print "<div align='center'>".$message."</div>";
    print "</td>";
    print "</tr>";
}
print "</table>";
print "</form>";
 
if(!empty($_SESSION['ajouterValue'])) {
    $ajout = explode(",",$_SESSION['ajouterValue']);
    print "<table border='0'  cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'>";
    print "<tr><td align='left'><b>".A2."</b></td>";
    print "<td align='left'><b>".A3."</b></td>";
    print "<td align='left'><b>".A21."</b></td>";
    print "<td>&nbsp;</td>";
    print "</tr>";
    foreach($ajout as $item) {
            if(!empty($item)) {
              print "<tr>";
              print "<td>".$productName."</td>";
              print "<td>".$optionName."</td>";
              $item2 = str_replace("=","/",$item);
              print "<td>".$item2."</td>";
                if(isset($_GET['do']) AND $_GET['do']=="update") {$doIt = "&do=update";} else {$doIt = "";}                  
              print "<td><a href='".$_SERVER['PHP_SELF']."?productId=".$_GET['productId']."&optionId=".$_GET['optionId']."".$doIt."&deleteVal=".$item."'>".A22."</a></td>";
              print "</tr>";
            }
    }
    print "</table>";
    
    
    
    
    print "<br><br>";
    print "<form action='".$_SERVER['PHP_SELF']."' method='GET'>";
    print "<input type='hidden' name='productId' value='".$_GET['productId']."'>";
    print "<input type='hidden' name='optionId' value='".$_GET['optionId']."'>";
    if(isset($_GET['do']) AND $_GET['do']=="update") {
        print "<input type='hidden' name='do' value='update'>";
    }
    
    print "<table border='0' width='700' cellpadding='0' cellspacing ='0' class='TABLE' align='center'><tr><td>";
	print "<table border='0' width='100%' cellpadding='0' cellspacing ='0' align='center'><tr><td align='left'>";
	print "<b>[3]</b></span> - ".A23." <b>".$optionName."</b> ".A24." <b>".$productName."</b>";
	print "</td></tr><tr>";
	print "<td align='left'>".A28."</td>";
	print "</tr></table>";


 
    if(!isset($_GET['do'])) {
        print "<br>";
		print "<table border='0' align='center' width='100%' cellpadding='5' cellspacing='0'><tr>";
        print "<td colspan='2' align='center' height='35'><b>".ADD_NOTE." '".strtoupper($optionName)."'</b></td></tr><tr>";
        print "<td><img src='im/be.gif' align='absmiddle'>&nbsp;<img src='im/nl.gif' align='absmiddle'></td><td><input type='text' class='vullen' size='90%' name='noteOption3'></td></tr><tr>";
        print "<td><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/fr.gif' align='absmiddle'></td><td><input type='text' class='vullen' size='90%' name='noteOption1'></td></tr><tr>";
        print "<td><img src='im/leeg.gif' align='absmiddle'>&nbsp;<img src='im/uk.gif' align='absmiddle'></td><td><input type='text' class='vullen' size='90%' name='noteOption2'></td></tr>";
        print "</table><br>";
    }
    print "<center><INPUT TYPE='submit' name='action' VALUE='".A25."' class='knop'><br><br>";
    print "</td></tr></table><br><br>";
    print "</form>";
}
?>
<br><br><br>
</body>
</html>
