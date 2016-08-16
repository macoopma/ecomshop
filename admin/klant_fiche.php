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

// ------------
// e-mail
// ------------
if(isset($_GET['action']) AND $_GET['action']=="sendmail") {
$query2 = mysql_query("SELECT * FROM users_pro WHERE users_pro_password= '".$_GET['id']."'");
$row2 = mysql_fetch_array($query2);

        // nieuwe inschrijving naar de klant
        $scss = "Datum: ".date("d-m-Y H:i:s")."\r\n\r\n";
        $scss .= $row2['users_pro_gender'].". ".$row2['users_pro_lastname']." ".$row2['users_pro_firstname'].",\r\n";
        $scss .= COMPTE_ACTIVE." http://".$www2.$domaineFull.".\r\n";
        $scss .= NUMERO_CLIENT.": ".$row2['users_pro_password']."\r\n";
        $scss .= "E-mail: ".$row2['users_pro_email']."\r\n";
        $scss .= "---\r\n";
        $scss .= POUR_INFO." ".$mailInfo.".\r\n";
        $scss .= MERCI."\r\n";
        $scss .= SERVICE;
      
      
      $to = $row2['users_pro_email'];
      $subject = "[".ACTIVATION_COMPTE_PRO."] - http://".$www2.$domaineFull;
      $from = $mailInfo;
      // mail sturen
      mail($to, $subject, $scss,
      "Return-Path: $from\r\n"
      ."From: $from\r\n"
      ."Reply-To: $from\r\n"
      ."X-Mailer: PHP/" . phpversion());
      
      $message2 = "<p align='center' class='fontrouge'><b>".A2200." ".$row2['users_pro_email']."</a></p>";
      

      mysql_query("UPDATE users_pro SET users_pro_active = 'yes' WHERE users_pro_password = '".$row2['users_pro_password']."'");
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

<?php
if(isset($message2)) {print $message2;}

if(isset($result) AND $result == 0) {
    print $message;
}
else {
$query = mysql_query("SELECT * FROM users_pro WHERE users_pro_password= '".$_GET['id']."'");
$row = mysql_fetch_array($query);
?>


<?php
print '<table align="center" border="0" cellpadding="10" cellspacing="0" class="TABLE" width="700"><tr>';
print '<td align="center">';
 
print "&bull;&nbsp;<a href='klant_fiche.php?id=".$row['users_pro_password']."&action=sendmail'>".A1000."</a>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
 print "&bull;&nbsp;<a href='korting_code_versturen.php?usersEmail=".$row['users_pro_email']."'>".ENVOYER_CODE."</a>";
print "<br><img src='im/zzz.gif' width='1' height='3'><br>";
 print "&bull;&nbsp;<a href='../mijn_account.php?accountRec1=".$row['users_pro_password']."&emailRec1=".$row['users_pro_email']."&addOrder=1&var=session_destroy' target='_blank'>".ADD_ORDER."</a>";
print "</td></tr></table>";
?>
<br>

<form action="maj_pro.php" method="POST" enctype="multipart/form-data" target='main'>

<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<tr>
         <td colspan="2" align="center">
         <b>
         Klant account <input type="text" class="vullen" size="15" name="users_pro_password" value="<?php print $row['users_pro_password'];?>">
         </b>
         </td>
</tr>
</table>
<br>

 
<input type="hidden" value="<?php print $row['users_pro_password'];?>" name="id">
<input type="hidden" value="<?php print $row['users_pro_email'];?>" name="oldEmailFromAdmin">

 
<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE" width="700">
 
<tr>
        <td><?php print A2;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_company" value="<?php print $row['users_pro_company'];?>"></td>

</tr>

<tr>
        <td>Datum</td>
	  <!-- // oud formaat -->	
        <!-- <td><?php print $row['users_pro_date_added'];?></td> -->
	  <!-- // euro formaat -->
        <td><?php print ereg_replace("([0-9]+)-([0-9]+)-([0-9]+)","\\3-\\2-\\1",$row['users_pro_date_added']);?></td>

</tr>

<tr>
<?php
        if($row['users_pro_active'] == "yes") {
                $checkedyes = "checked";
                $checkedno = "";
        } else {
                $checkedyes = "";
                $checkedno = "checked";
        }
?>
        <td><?php print A3;?></td>
        <td> <?php print A140;?> <input type="radio" value="yes" <?php print $checkedyes;?> name="users_pro_active">
             <?php print A150;?> <input type="radio" value="no" name="users_pro_active" <?php print $checkedno;?>></td>
</tr>
 
<tr>
<td><?php print A5;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_activity" value="<?php print $row['users_pro_activity'];?>"></td>
</tr>

<tr>
<td><?php print A22;?></td>
        <td><input type="text" class="vullen" size="6" name="users_pro_reduc" value="<?php print $row['users_pro_reduc'];?>">%</td>
</tr>
 
<tr>
<td><?php print AFFILIE;?></td>
        <td>
		<?php 
			if($row['users_pro_aff']=='yes') {
				$queryZAffFindCom = mysql_query("SELECT aff_number  FROM affiliation WHERE aff_customer = '".$row['users_pro_password']."'") or die (mysql_error());
				$resultZAffCom = mysql_fetch_array($queryZAffFindCom);
				$affiliateNumber = $resultZAffCom['aff_number'];
				print "<a href='affiliate.php?action=view&id=".$affiliateNumber."'><b>".$affiliateNumber."</b></a>";
			}
			else {
				print "--";
			}
		?>
		</td>
</tr>

<?php

if($row['users_pro_payable']=="0") {$selzz1 = "selected";} else {$selzz1 = "";}
if($row['users_pro_payable']=="30") {$selzz2 = "selected";} else {$selzz2 = "";}
if($row['users_pro_payable']=="60") {$selzz3 = "selected";} else {$selzz3 = "";}
if($row['users_pro_payable']=="90") {$selzz4 = "selected";} else {$selzz4 = "";}
?>
<tr>
<td><?php print PAYABLE;?></td>
        <td>
              <select name="clientPayablePro">
              <option value="0" <?php print $selzz1;?>><?php print CASH;?></option>
              <option value="30" <?php print $selzz2;?>><?php print _30_DAYS;?></option>
              <option value="60" <?php print $selzz3;?>><?php print _60_DAYS;?></option>
              <option value="90" <?php print $selzz4;?>><?php print _90_DAYS;?></option>
              </select>
        </td>
</tr>
 
<tr>
<td><?php print A11;?></td>
        <td><input type="text" class="vullen" size="20" name="users_pro_tva" value="<?php print $row['users_pro_tva'];?>"></td>
</tr>
 
<tr>
<td><?php print TVA_VALID;?></td>
        <td>
<?php
        if($tvaManuelValidation=="oui") {
            if($row['users_pro_tva_confirm']=="yes") $selT1="selected"; else $selT1="";
            if($row['users_pro_tva_confirm']=="no") $selT2="selected"; else $selT2="";
            if($row['users_pro_tva_confirm']=="??") $selT3="selected"; else $selT3="";
    
            print "<select name='users_pro_tva_active'>";
            print "<option value='yes' ".$selT1.">".A140."</option>";
            print "<option value='no' ".$selT2.">".A150."</option>";
            print "<option value='??' ".$selT3.">??</option>";
            print "</select>";
        }
        else {
            if($row['users_pro_tva_confirm']=="no") $selT2="selected"; else $selT2="";
            if($row['users_pro_tva_confirm']=="??") $selT3="selected"; else $selT3="";
    
            print "<select name='users_pro_tva_active'>";
            print "<option value='no' ".$selT2.">".A150."</option>";
            print "<option value='??' ".$selT3.">--</option>";
            print "</select>";
        }
?>
        
        </td>
</tr>
 
<tr>
<td><?php print A6;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_address" value="<?php print $row['users_pro_address'];?>"></td>

</tr>

<?php
// zip
?>
<tr>
<td><?php print A7;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_postcode" value="<?php print $row['users_pro_postcode'];?>"></td>
</tr>
 
<tr>
<td><?php print A8;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_city" value="<?php print $row['users_pro_city'];?>"></td>
</tr> 

<tr>
<td><?php print A9;?></td>
        <td>
<?php
$pays = mysql_query("SELECT countries_name, iso
                      FROM countries
                      WHERE country_state = 'country' AND countries_shipping != 'exclude'
                      ORDER BY countries_name");
?>
              <select name="users_pro_country">
<?php
                while ($countries = mysql_fetch_array($pays)) {
                  if($countries['countries_name'] == $row['users_pro_country']) $ad = "selected"; else $ad="";
                  print "<option value='".$countries['countries_name']."' ".$ad.">".$countries['countries_name']."</option>";
                }
?>
              </select>
        </td>
</tr> 

<tr>
<td><?php print A10;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_telephone" value="<?php print $row['users_pro_telephone'];?>"></td>
</tr> 

<tr>
<td><?php print FAX;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_fax" value="<?php print $row['users_pro_fax'];?>"></td>
</tr>

 
<tr>
<td>E-mail</b></td>
        <td><input type="text" class="vullen" size="40" name="users_pro_email" value="<?php print $row['users_pro_email'];?>"></td>
</tr>

  
<tr>
<td colspan="2"><div align="center"><b>CONTACT</b></div></td>
</tr>
 
<tr>
<td><?php print A111;?></td>
        <td><input type="text" class="vullen" size="25" name="users_pro_lastname" value="<?php print $row['users_pro_lastname'];?>"></td>
</tr>
 
<tr>
<td><?php print A12;?></td>
        <td><input type="text" class="vullen" size="40" name="users_pro_firstname" value="<?php print $row['users_pro_firstname'];?>"></td>
</tr>
 
<tr>
<td><?php print A120;?></td>
        <td><input type="text" class="vullen" size="40" name="users_pro_poste" value="<?php print $row['users_pro_poste'];?>"></td>
</tr>
 
<tr>
<td valign=top><?php print A13;?></td>
        <td><textarea cols="60" rows="4" class="vullen" name="users_pro_comment"><?php print $row['users_pro_comment'];?></textarea></td>
</tr>
 
<tr>
<?php
        if($row['users_pro_news'] == "yes") {
                $checkedyes = "checked";
                $checkedno = "";
        } else {
                $checkedyes = "";
                $checkedno = "checked";
        }
?>
        <td><?php print ABONNE_NEWS;?></td>
        <td>
            <input type="hidden" name="nc" value="<?php print $_GET['id'];?>">
            <input type="hidden" name="l" value="<?php print $_SESSION['lang'];?>">
            <input type="hidden" name="action" value="newsLet">
         <?php print A140;?> <input type="radio" value="yes" <?php print $checkedyes;?> name="newsLetter">
         <?php print A150;?> <input type="radio" value="no" <?php print $checkedno;?> name="newsLetter">
         </td>
</tr>
</table>
<br>


<table width="700" border="0" cellpadding="5" cellspacing="0" align="center" class="TABLE">
<tr>
<td align="center"><input type=Submit value="<?php print A14;?>" class='knop' ></td></form>
</tr>
</table><br><br><br>

<?php
if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {
 
$queryLiv = mysql_query("SELECT * FROM users_orders WHERE users_password = '".$_GET['id']."' AND users_save_data_from_form !='no'");
$queryLivNum = mysql_num_rows($queryLiv);
if($queryLivNum>0) {
$i=1;
$pl1 = "";
$pl2 = "";
if($_SESSION['lang']==1 AND $queryLivNum>1) {$pl1="s"; $pl2="s";}
if($_SESSION['lang']==2 AND $queryLivNum>1) {$pl1="es"; $pl2="";}
?>
   <br><br>
  <table border="0" width="700" cellspacing="0" cellpadding="0" align="center" class="TABLE">
    <tr>
      <td>
<?php 
print "<div style='background-color:#FFFFCC; padding:5px;'><b>".A6.$pl1." ".DE_FACTURATION.$pl2." :</b></div><hr>";
while($recoverForm = mysql_fetch_array($queryLiv)) {
   $num = $i++;
   $datas = "<div align='left'><b>".$num."</b>.&nbsp;";
   $datas.= $recoverForm['users_gender'].". ".$recoverForm['users_firstname']." ".$recoverForm['users_lastname'].",<br>";
   $datas.= ($recoverForm['users_company']!=="")? $recoverForm['users_company']."<br>" : "";
   $datas.= $recoverForm['users_address']."<br>";
   $datas.= ($recoverForm['users_surburb']!=="")? $recoverForm['users_surburb']."<br>" : "";
   $datas.= $recoverForm['users_city']."<br>";
   $datas.= $recoverForm['users_zip']." ";
   $datas.= ($recoverForm['users_province']!=="")? $recoverForm['users_province']."<br>" : "";
   $datas.= $recoverForm['users_country'];
   $datas.= "</div>";
   if($queryLivNum !== $num) $datas.= "<hr>";
   print $datas;
}
?>
      </td>
      </tr>
  </table>
<?php
}
// bestellingen
$queryCom = mysql_query("SELECT * FROM users_orders WHERE users_password = '".$_GET['id']."' AND users_nic NOT LIKE 'TERUG%' ORDER BY users_payed DESC");
$queryComNum = mysql_num_rows($queryCom);
if($queryComNum>0) {
	$i=1;
	$c = "";
	($queryComNum>1)? $pl="" : $pl="";
?>
	   <br><br>
	   
<?php
	print "<table border='0' width='700' cellspacing='0' cellpadding='3' align='center' class='TABLE'><tr>";
		print "<td colspan='8' style='background-color:#FFFFCC; padding:5px;'><b>".COMMANDE.$pl." :</b></td></tr><tr>";
		print "<td><b>#</td>";
		print "<td><b>NIC</td>";
		print "<td><b>".MONTANT."</td>";
		print "<td align='center'><b>".FACTURE."</td>";
		print "<td align='center'><b>".PAYEE."</td>";
		print "<td align='center'><b>".READY."</td>";
		print "<td align='center'><b>".EXPEDIEE."</td>";
		print "<td align='center'><b>".REMBOURSEE."</td>";
		print "</tr>";
		
		while($recoverCom = mysql_fetch_array($queryCom)) {
			$num = $i++;
			$payedd = ($recoverCom['users_payed']=='yes')? "<img src='im/val.gif' title='".A140."'>" : "<img src='im/no_stock.gif' title='".A150."'>";
			$shipped = ($recoverCom['users_statut']=='yes')? "<img src='im/val.gif' title='".A140."'>" : "<img src='im/no_stock.gif' title='".A150."'>";
			$ready = ($recoverCom['users_ready']=='yes')? "<img src='im/val.gif' title='".A140."'>" : "<img src='im/no_stock.gif' title='".A150."'>";
			$refundd = ($recoverCom['users_refund']=='yes')? "<img src='im/val.gif' title='".A140."'>" : "--";
			$factured = ($recoverCom['users_fact_num']!=='')? "<a href='factuur_scherm.php?id=".$recoverCom['users_id']."&target=impression' target='_blank'>".str_replace("||","",$recoverCom['users_fact_num'])."</a>" : "--";
			if($c=="#E8E8E8") {$c="#F1F1F1";} else {$c="#E8E8E8";}
			if($recoverCom['users_litige']=='yes') $c="#FF9900";
			if($recoverCom['users_customer_delete']=='yes') $c="#CCFF00";
			print "<tr bgcolor='".$c."'>";
			print "<td width='1'><b>".$num."</b>.</td>";
			print "<td><a href='detail.php?id=".$recoverCom['users_id']."&yo=&from=pro&account=".$_GET['id']."&jour1=".date('d')."&mois1=".date('m')."&an1=".date('Y')."&jour2=".date('d')."&mois2=".date('m')."&an2=".date('Y')."'>".$recoverCom['users_nic']."</a></td>";
			print "<td>".$recoverCom['users_total_to_pay']." ".$symbolDevise."</td>";
			print "<td align='center'>".$factured."</td>";
			print "<td align='center'>".$payedd."</td>";
			print "<td align='center'>".$ready."</td>";
			print "<td align='center'>".$shipped."</td>";
			print "<td align='center'>".$refundd."</td>";
			print "</tr>";
		}
	print "</table><br<br><br>";
}
}
}
?>
<br<br><br>
  </body>
  </html>
