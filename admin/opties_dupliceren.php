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
$c="";
$message = "";

 
if(isset($_POST['action']) and $_POST['action'] == A10 AND isset($_POST['selectProductsId'])) {
   if(count($_POST['selectProductsId'])==0) {
      $message = "<span class='fontrouge'><b>".A1."</b></span>";
   }
   else {
               $selectProductsIdNum = count($_POST['selectProductsId']);

               $optionsName = explode("|",$_POST['options']);
               $optionsNameNum = count($optionsName);

               $optionsIdName = explode("|",$_POST['optionsId']);
               $optionsIdNameNum = count($optionsIdName);

               $optionsNote1 = explode("|",$_POST['optionNote1']);
               $optionsNote1Num = count($optionsNote1);

               $optionsNote2 = explode("|",$_POST['optionNote2']);
               $optionsNote2Num = count($optionsNote2);

               $optionsNote3 = explode("|",$_POST['optionNote3']);
               $optionsNote3Num = count($optionsNote3);

               for($i=0; $i<=$optionsNameNum-1; $i++) {

                   for($i2=1; $i2<=$selectProductsIdNum; $i2++) {
                       $check = mysql_query("SELECT *
                                             FROM products_id_to_products_options_id
                                             WHERE products_id = '".$_POST['selectProductsId'][$i2-1]."'
                                             AND products_options_id = '".$optionsIdName[$i]."'
                                             ");
                       $checkNum = mysql_num_rows($check);
                       if($checkNum > 0) {
                          $query2 = mysql_query("SELECT products_name_".$_SESSION['lang']." FROM products WHERE products_id='".$_POST['selectProductsId'][$i2-1]."'");
                          $query2Name = mysql_fetch_array($query2);

                          $message .= "<span class='fontrouge'>".A2." <b>".strtoupper($query2Name['products_name_'.$_SESSION['lang']])."</b></span><br>";
                       }
                       else {
                       		mysql_query('INSERT INTO products_id_to_products_options_id
                                    (products_id, products_options_id, products_option, note_option_1, note_option_2, note_option_3)
                                    VALUES(
                                    \''.$_POST['selectProductsId'][$i2-1].'\',
                                    \''.$optionsIdName[$i].'\',
                                    \''.$optionsName[$i].'\',
                                    \''.$optionsNote1[$i].'\',
                                    \''.$optionsNote2[$i].'\',
                                    \''.$optionsNote3[$i].'\'
                                    )');
                                    
                        	mysql_query("UPDATE products
                                     	SET products_options = 'yes'
                                     	WHERE products_id = '".$_POST['selectProductsId'][$i2-1]."'");

                        	$query = mysql_query("SELECT *
                                              	FROM products_options
                                              	WHERE products_options_id = '".$optionsIdName[$i]."'");
                        	$yoName = mysql_fetch_array($query);

                        	$query2 = mysql_query("SELECT products_name_".$_SESSION['lang']."
                                               	FROM products
                                               	WHERE products_id='".$_POST['selectProductsId'][$i2-1]."'");
                        	$query2Name = mysql_fetch_array($query2);

                        	$message .= "<span class='fontrouge'>".A3." <b>".strtoupper($yoName['products_options_name'])."</b> ".A4." <b>".strtoupper($query2Name['products_name_'.$_SESSION['lang']])."</b></span><br>";
                    	}
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
<p align="center" class="largeBold"><?php print A5;?></p>

<?php
if(isset($_GET['id'])) $pId = $_GET['id'];
if(isset($_POST['id'])) $pId = $_POST['id'];
// Product name
$ProductNameQuery = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_visible
                                 FROM products
                                 WHERE products_id = '".$pId."'
                                 AND products_visible = 'yes'
                                 AND products_ref != 'GC100'
                                 AND products_qt>'0' ");
$productName = mysql_fetch_array($ProductNameQuery);

 
$optionNameQuery = mysql_query("SELECT a.products_options_id, a.products_option, z.products_options_name, a.note_option_1, a.note_option_2, a.note_option_3
                                 FROM products_id_to_products_options_id as a
                                 LEFT JOIN products_options as z
                                 ON(a.products_options_id = z.products_options_id)
                                 WHERE products_id = '".$pId."'
                                 ORDER BY z.products_options_name");
                                 $num = mysql_num_rows($optionNameQuery);
                                 $s=($num>1)? "s" : "";
                                 $messageTitre = A7.$s." ".A6;
print "<table border='0' cellpadding='5' cellspacing ='5' align='center' class='TABLE' width='700'><tr><td><center>";
print "&nbsp;".$messageTitre."</tr></td></table><br>";
                  if($num == 0) {
                     mysql_query("UPDATE products
                                  SET products_options='no'
                                  WHERE products_id='".$pId."'");
                     exit;
                  }

print "<table border='0' cellpadding='5' cellspacing ='0' align='center' class='TABLE' width='700'><tr>";
        print "<td><div align='left'><b>".A7."</b></div></td>";
        print "<td><div align='left'><b>".A8."</b></div></td>";
        print "</tr>";

              while($optionName = mysql_fetch_array($optionNameQuery)) {

                    $noteOption1[] = $optionName['note_option_1'];
                    $noteOption2[] = $optionName['note_option_2'];
                    $noteOption3[] = $optionName['note_option_3'];
                    if($c=="#E8E8E8") {$c="#CCCCCC";} else {$c="#E8E8E8";}
                    $listValue = explode(",",$optionName['products_option']);
                    $listValueNum = count($listValue)-1;
                    $listOption[] = $optionName['products_option'];
                    $listOptionId[] = $optionName['products_options_id'];

                    print "<tr bgcolor='".$c."'>";
                    print "<td valign=top align=left>";
                    print $optionName['products_options_name'];
                    print "</td>";

                    print "<td>";
                            for($i=0; $i<=$listValueNum-1; $i++) {

                                //$listValue[$i] = str_replace("::+0.00","",$listValue[$i]);
                                $listValue[$i] = str_replace("::"," | ",$listValue[$i]);
                                $listValue[$i] = str_replace("=","/",$listValue[$i]);
                                print $listValue[$i]."<br>";
                            }
                            print "<br>";
                            print $optionName['note_option_3'];
                            print "<br>";
                            print $optionName['note_option_1'];
                            print "<br>";
                            print $optionName['note_option_2'];
                    print "</td>";
                    print "</tr>";
              }
print "</table>";
$yy = implode("|",$listOption);
$tt = implode("|",$listOptionId);
$nn1 = implode("|",$noteOption1);
$nn2 = implode("|",$noteOption2);
$nn3 = implode("|",$noteOption3);

print "<p align='center'>".A9."</p>";

$query = mysql_query("SELECT products_name_".$_SESSION['lang'].", products_id, products_ref
                      FROM products
                      WHERE products_visible = 'yes'
                      AND products_ref != 'GC100'
                      ORDER BY products_name_".$_SESSION['lang']."");

print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
print "<input type='hidden' name='id' value='".$pId."'>";
print "<input type='hidden' name='options' value='".$yy."'>";
print "<input type='hidden' name='optionsId' value='".$tt."'>";
print "<input type='hidden' name='optionNote1' value='".$nn1."'>";
print "<input type='hidden' name='optionNote2' value='".$nn2."'>";
print "<input type='hidden' name='optionNote3' value='".$nn3."'>";

print "<table border='0' width='700' cellpadding='5' cellspacing ='0' align='center' class='TABLE'><tr>";
print "<td align='center'>";
        print "<select name='selectProductsId[]' size='10' multiple>";
                while($name2 = mysql_fetch_array($query)) {
                print "<option value='".$name2['products_id']."'>".$name2['products_name_'.$_SESSION['lang']]." [REF: ".$name2['products_ref']."]</option>";
                }
        print "</select>";
print "</td>";
print "</tr>";
print "</table>";

print "<br>";

print "<table border='0'  width='700' cellpadding='5' cellspacing ='0' align='center' class='TABLE'><tr>";
print "<tr>";
print "<td align='center'>";
        print "<input type='submit' class='knop' name='action' value='".A10."'>";
print "</td>";
print "</tr>";
print "</table>";
print "</form>";

print "<p align='center'>".$message."</p>";
print "<p align='center'>".A11."</p>";
?>
<br><br><br>
</body>
</html>
