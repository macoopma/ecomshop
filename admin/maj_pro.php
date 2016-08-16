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
$message = "";


$query = mysql_query("SELECT users_pro_id FROM users_pro WHERE users_pro_password = '".$_POST['id']."'");

if(mysql_num_rows($query)> 0) {
 
function replace_ap($val) {
   $_val = str_replace("'","&#146;",$val);
   return $_val;
}
 
$removeFromTva = array(" ", "-", ".", ",");
$_POST['users_pro_tva'] = str_replace($removeFromTva, "", $_POST['users_pro_tva']);

if(isset($_POST['users_pro_tva']) AND $_POST['users_pro_tva']=="") $_POST['users_pro_tva_active'] = "??";

  
      mysql_query("UPDATE users_pro
                   SET
                   users_pro_password = '".$_POST['users_pro_password']."',
                   users_pro_email = '".$_POST['users_pro_email']."',
                   users_pro_company = '".replace_ap($_POST['users_pro_company'])."',
                   users_pro_address = '".replace_ap($_POST['users_pro_address'])."',
                   users_pro_city = '".replace_ap($_POST['users_pro_city'])."',
                   users_pro_postcode = '".$_POST['users_pro_postcode']."',
                   users_pro_country = '".replace_ap($_POST['users_pro_country'])."',
                   users_pro_activity = '".replace_ap($_POST['users_pro_activity'])."',
                   users_pro_telephone = '".$_POST['users_pro_telephone']."',
                   users_pro_fax = '".$_POST['users_pro_fax']."',
                   users_pro_tva = '".$_POST['users_pro_tva']."',
                   users_pro_tva_confirm = '".$_POST['users_pro_tva_active']."',
                   users_pro_lastname = '".replace_ap($_POST['users_pro_lastname'])."',
                   users_pro_firstname = '".replace_ap($_POST['users_pro_firstname'])."',
                   users_pro_poste = '".replace_ap($_POST['users_pro_poste'])."',
                   users_pro_comment = '".replace_ap($_POST['users_pro_comment'])."',
                   users_pro_active = '".$_POST['users_pro_active']."',
                   users_pro_reduc = '".$_POST['users_pro_reduc']."',
                   users_pro_payable = '".$_POST['clientPayablePro']."'
                   WHERE users_pro_password = '".$_POST['id']."'
                   ");
   
      mysql_query("UPDATE users_orders
                   SET
                   users_password = '".$_POST['users_pro_password']."',
                   users_email = '".$_POST['users_pro_email']."'
                   WHERE users_password = '".$_POST['id']."'
                   ");
    
      mysql_query("UPDATE devis
                   SET
                   devis_client = '".$_POST['users_pro_password']."'
                   WHERE devis_client = '".$_POST['id']."'
                   ");
     
      mysql_query("UPDATE affiliation
                   SET
                   aff_customer = '".$_POST['users_pro_password']."'
                   WHERE aff_customer = '".$_POST['id']."'
                   ");
      
      mysql_query("UPDATE newsletter
                   SET
                   newsletter_nic = '".$_POST['users_pro_password']."'
                   WHERE newsletter_nic = '".$_POST['id']."'
                   ");
       
      mysql_query("UPDATE users_caddie
                   SET
                   users_caddie_client_number = '".$_POST['users_pro_password']."'
                   WHERE users_caddie_client_number = '".$_POST['id']."'
                   ");
	 
		$queryCodePromo = mysql_query("SELECT code_promo_id, code_promo_note FROM code_promo");
		while($resultCodePromo = mysql_fetch_array($queryCodePromo)) {
			$code_promo_note = str_replace($_POST['id'],$_POST['users_pro_password'],$resultCodePromo['code_promo_note']);
			mysql_query("UPDATE code_promo
	                   SET
	                   code_promo_note = '".$code_promo_note."'
	                   WHERE code_promo_id = '".$resultCodePromo['code_promo_id']."'
	                   ") or die (mysql_error());
		}
       
      mysql_query("UPDATE newsletter
                   SET
                   newsletter_email = '".$_POST['users_pro_email']."'
                   WHERE newsletter_email = '".$_POST['oldEmailFromAdmin']."'
                   ");
        $message = "<p class='fontrouge'>".A2." <b>".$_POST['id']."</b> ".A4."</p>";
}
else {
   $message = "<span style='color:red'>".A5."</span>";
}
 

if(isset($_POST['action']) AND $_POST['action']=="newsLet") {
    if($_POST['newsLetter'] == 'yes') {  
  
        $queryFindEmail = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password='".$_POST['id']."'");
        $reaultFindEmail = mysql_fetch_array($queryFindEmail);
        $NewsEmail = $reaultFindEmail['users_pro_email'];
   
        $queryNewsletter = mysql_query("SELECT newsletter_id, newsletter_nic FROM newsletter WHERE newsletter_email='".$NewsEmail."'");
        $queryNewsletterCount = mysql_num_rows($queryNewsletter);
    
        if($queryNewsletterCount>0) {
            while($toto = mysql_fetch_array($queryNewsletter)) {
               $update1 = mysql_query("UPDATE newsletter
                               SET
                               newsletter_active='yes',
                               newsletter_statut='active'
                               WHERE newsletter_id = '".$toto['newsletter_id']."'
                            ");
               $update2 = mysql_query("UPDATE users_pro
                               SET
                               users_pro_news='yes'
                               WHERE users_pro_password = '".$_POST['id']."'
                            ");
            }
        }
        else {
     
      
                       $hoy = date("Y-m-d H:i:s");
       
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
                        ('".$NewsEmail."',
                        '".$activePassword."',
                        '".$_SESSION['lang']."',
                        'yes',
                        'active',
                        '".$hoy."',
                        '".$_POST['id']."'
                        )");
         
               $update2 = mysql_query("UPDATE users_pro
                                        SET
                                        users_pro_news='yes'
                                        WHERE users_pro_password = '".$_POST['id']."'
                                        ");
        }
    }
    else {
    
        $queryFindEmail = mysql_query("SELECT users_pro_email FROM users_pro WHERE users_pro_password='".$_POST['id']."'");
        $reaultFindEmail = mysql_fetch_array($queryFindEmail);
        $NewsEmail = $reaultFindEmail['users_pro_email'];
     
        $queryNewsletter = mysql_query("SELECT newsletter_id, newsletter_nic FROM newsletter WHERE newsletter_email='".$NewsEmail."'");
        $queryNewsletterCount = mysql_num_rows($queryNewsletter);
      
                if($queryNewsletterCount>0) {
                    while($toto = mysql_fetch_array($queryNewsletter)) {
                       $update1 = mysql_query("UPDATE newsletter
                                       SET
                                       newsletter_active='no',
                                       newsletter_statut='out'
                                       WHERE newsletter_id = '".$toto['newsletter_id']."'
                                    ");
                       $update2 = mysql_query("UPDATE users_pro
                                       SET
                                       users_pro_news='no'
                                       WHERE users_pro_password = '".$_POST['id']."'
                                    ");

                    }
                }
                else {
       
        
                               $hoy = date("Y-m-d H:i:s");
         
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
                                ('".$NewsEmail."',
                                '".$activePassword."',
                                '".$_SESSION['lang']."',
                                'no',
                                'out',
                                '".$hoy."',
                                '".$_POST['id']."'
                                )");
           
                       $update2 = mysql_query("UPDATE users_pro
                                       SET
                                       users_pro_news='no'
                                       WHERE users_pro_password = '".$_POST['id']."'
                                    ");
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

<table border="0" align="center" cellpadding="5" cellspacing = "0" class='TABLE' width='700'>
       <tr>
        <td align="center" class="fontrouge" colspan="2"><?php print $message; ?></td>
       </tr>
       <tr>
        <td align="center" colspan="2">
		<form action="klant_accounts_beheren.php?page=0" method="GET">
			<input type="hidden" name="page" value="0">
			<input type="submit" class='knop' value="<?php print A7;?>">
		</form>
		</td>
       </tr>
</table>
  </body>
  </html>



