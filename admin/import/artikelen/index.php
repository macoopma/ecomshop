<?php
session_start();
if(!isset($_SESSION['login'])) header("Location:../../../index.php");
include('../../../configuratie/mysql_connect.php');

 
function remApos($rem) {
    $_rem = str_replace("'","’",$rem);
 
 
 
    return $_rem;
}
?>
<html>
<head>
<title>Importeer functie</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='../../style.css'>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<p align="center" class="largeBold">Importeer artikelen</p>
    
<?php
//------
// LEEG
//------
if(isset($_POST['action2']) AND $_POST['action2']=="Maak de tabel leeg") {
    print "<form method='POST' action='".$_SERVER['PHP_SELF']."'>";
    print "<p align='center' style='color:#FF0000; font-size:13px;'>Ben je zeker om deze bewerking uit te voeren?</p>";
    print "<p align='center' style='font-size:13px;'>";
    print "<input type='submit' class='knop' name='val' value='JA'>";
    print "&nbsp;|&nbsp;";
    print "<a href='".$_SERVER['PHP_SELF']."'><b>NEEN</b></a>";
    print "<input type='hidden' name='name' value='".$_POST['name']."'>";
    print "<input type='hidden' name='table' value='".$_POST['table']."'>";
    print "<input type='hidden' name='action3' value='yes'>";
    print "</p>";
    print "</form>";
    exit;
}

if(isset($_POST['val']) AND $_POST['val']=="JA" AND isset($_POST['action3']) AND $_POST['action3']=="yes" AND isset($_POST['name']) AND isset($_POST['table'])) {
 
   $bdd = $_POST['name'];
   $table = $_POST['table'];

   mysql_connect($bddHost,$bddUser,$bddPass) or die("Geen connectie met de database");
   mysql_select_db($bdd);
    $query = mysql_query("SELECT products_id FROM ".$table) or die (mysql_error());
    $queryNum = mysql_num_rows($query);
    if($queryNum > 0) {
        while($idNum = mysql_fetch_array($query)) {
            mysql_query("DELETE FROM ".$table." WHERE products_id='".$idNum['products_id']."' AND products_ref!='GC100'");
            mysql_query("DELETE FROM specials WHERE products_id='".$idNum['products_id']."'");
            mysql_query("DELETE FROM products_id_to_products_options_id WHERE products_id='".$idNum['products_id']."'");
            mysql_query("DELETE FROM products_options_stock WHERE products_options_stock_prod_id='".$idNum['products_id']."'");
            mysql_query("DELETE FROM discount_on_quantity WHERE discount_qt_prod_id='".$idNum['products_id']."'");
        }
        print "<p align='center'><table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'>";
	  print "<p align='center'><b>De tabel is leeg</b></p>";
        print "<p align='center'><a href='index.php'><b>Ga terug</b></a></p></tr></td></table>";
        exit;
    }
    else {
        print "<p align='center'><table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'>";
	  print "<p align='center'><b>De tabel is leeg</b></p>";
        print "<p align='center'><a href='index.php'><b>Ga terug</b></a></p></tr></td></table>";
        exit;

    }
}


//----------
// IMPORTEER
//----------
if(isset($_POST['action1']) AND $_POST['action1']=="Importeer het bestand") {


	$separateur = "\t";
	$bdd = $_POST['name'];
	$table = $_POST['table'];
	$hoy = date("Y-m-d H:i:s");

	// Connexion bdd
	mysql_connect($bddHost,$bddUser,$bddPass) or die("Geen connectie met de database");
	mysql_select_db($bdd);
	$fichier = $_POST['fichier'];

 
	print "<p align='center'>Bestand: <b>".$fichier."</b></p>";
 
         $queryGC = mysql_query("SELECT products_id FROM products WHERE products_ref='GC100'");
         if(mysql_num_rows($queryGC) > 0) {
            mysql_query("DELETE from products WHERE products_ref ='GC100'");
         }
  
         if(file_exists($fichier)) {
         $noPass = array("\t", ",", "'", "+");
         $noPass2 = array("\t", "'", "+");
         $noPass3 = array("\t", "+");
         
             if($fp = fopen($fichier, "r")) {
                        while (!feof($fp)) {
                               
                               $ligne = fgets($fp,filesize($fichier));
                               $liste = explode($separateur ,$ligne);
   
                               $liste = str_replace("'","’",$liste);
                               if(empty($liste[0]) OR $liste[0]=='') {break;}
                               if(!isset($liste[35]) OR $liste[35] == "0000-00-00 00:00:00") {$liste[35] = $hoy;}
    
                               $queryId = mysql_query("SELECT products_id FROM products WHERE products_id ='".$liste[0]."'");
                               if(mysql_num_rows($queryId) > 0) {
							   		$dupId[]=$liste[0];
							   		$liste[0] = "";
								}
     
                               $queryImport = mysql_query("SELECT categories_noeud, categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id ='".$liste[1]."'");
                               if(mysql_num_rows($queryImport) == 0) {
                                    print "<div align='center'>Artikel ID ".$liste[0]." - ".str_replace($noPass," ",$liste[7]).".</div>";
                                    print "<div align='center'>---------</div>";
                                    print "<div align='center' style='color:#FF0000'>De categorie ID ".$liste[1]." bestaat niet</div>";
                                    print "<div align='center' style='color:#FF0000'>Controleer de categories / sub-categories</div>";
                                    print "<br>";
                                    print "<form action='index.php'><div align='center'><input type='submit' class='knop' value='Ga terug'></div></form>";
                                    exit;
                               }
                               else {
                                 $resutlImport = mysql_fetch_array($queryImport);
                                 if($resutlImport['categories_noeud']!=="L") {
                                    print "<div align='center'>Artikel: ".str_replace($noPass," ",$liste[7])."</div>";
                                    print "<div align='center'>Sub-categorie ID ".$liste[1]." - ".$resutlImport['categories_name_'.$_SESSION['lang']].".</div>";
                                    print "<div align='center'>---------</div>";
                                    print "<div align='center' style='color:#FF0000'>De categories ID ".$liste[1]." is geen sub-categorie</div>";
                                    print "<div align='center' style='color:#FF0000'>Controleer de catégories / sous-categories</div>";
                                    print "<br>";
                                    print "<form action='index.php'><div align='center'><input type='submit' class='knop' value='Ga terug'></div></form>";
                                    exit;
                                 }
                               }
                               
                              $query = 'INSERT INTO '.$table.' SET
                                        products_id = \''.$liste[0].'\',
                                        categories_id = \''.$liste[1].'\',
                                        categories_id_bis = \''.$liste[2].'\',
                                        fournisseurs_id = \''.$liste[3].'\',
                                        afficher_fournisseur = \''.trim($liste[4]).'\',
                                        fabricant_id = \''.$liste[5].'\',
                                        afficher_fabricant = \''.trim($liste[6]).'\',
                                        products_name_1 = \''.str_replace($noPass," ",$liste[7]).'\',
                                        products_name_2 = \''.str_replace($noPass," ",$liste[8]).'\',
                                        products_name_3 = \''.str_replace($noPass," ",$liste[9]).'\',
                                        products_desc_1 = \''.remApos(str_replace($noPass2," ",$liste[10])).'\',
                                        products_desc_2 = \''.remApos(str_replace($noPass2," ",$liste[11])).'\',
                                        products_desc_3 = \''.remApos(str_replace($noPass2," ",$liste[12])).'\',
                                        products_garantie_1 = \''.remApos(str_replace($noPass2," ",$liste[13])).'\',
                                        products_garantie_2 = \''.remApos(str_replace($noPass2," ",$liste[14])).'\',
                                        products_garantie_3 = \''.remApos(str_replace($noPass2," ",$liste[15])).'\',
                                        products_price = \''.str_replace(",",".",$liste[16]).'\',
                                        products_weight = \''.str_replace(",",".",$liste[17]).'\',
                                        products_options = \''.trim($liste[18]).'\',
                                        products_option_note_1 = \''.remApos(str_replace($noPass2," ",$liste[19])).'\',
                                        products_option_note_2 = \''.remApos(str_replace($noPass2," ",$liste[20])).'\',
                                        products_option_note_3 = \''.remApos(str_replace($noPass2," ",$liste[21])).'\',
                                        products_ref = \''.remApos(str_replace($noPass," ",$liste[22])).'\',
                                        products_im = \''.trim($liste[23]).'\',
                                        products_image = \''.$liste[24].'\',
                                        products_image2 = \''.$liste[25].'\',
                                        products_image3 = \''.$liste[26].'\',
                                        products_image4 = \''.$liste[27].'\',
                                        products_image5 = \''.$liste[28].'\',
                                        products_note_1 = \''.remApos(str_replace($noPass2," ",$liste[29])).'\',
                                        products_note_2 = \''.remApos(str_replace($noPass2," ",$liste[30])).'\',
                                        products_note_3 = \''.remApos(str_replace($noPass2," ",$liste[31])).'\',
                                        products_visible = \''.trim($liste[32]).'\',
                                        products_taxable = \''.trim($liste[33]).'\',
                                        products_tax = \''.str_replace(",",".",$liste[34]).'\',
                                        products_date_added = \''.$liste[35].'\',
                                        products_viewed = \''.$liste[36].'\',
                                        products_added = \''.$liste[37].'\',
                                        products_qt = \''.$liste[38].'\',
                                        products_download = \''.trim($liste[39]).'\',
                                        products_download_name = \''.$liste[40].'\',
                                        products_related = \''.$liste[41].'\',
                                        products_exclusive = \''.trim($liste[42]).'\',
                                        products_sold = \''.$liste[43].'\',
                                        products_deee = \''.str_replace(",",".",$liste[44]).'\',
                                        products_ean = \''.$liste[45].'\',
                                        products_sup_shipping = \''.str_replace(",",".",$liste[46]).'\',
                                        products_caddie_display = \''.trim($liste[47]).'\',
                                        products_forsale = \''.trim($liste[48]).'\',
                                        
                                        products_delay_1 = \''.trim($liste[49]).'\',
                                        products_delay_2 = \''.trim($liste[50]).'\',
                                        products_delay_1a = \''.trim($liste[51]).'\',
                                        products_delay_2a = \''.trim($liste[52]).'\',
                                        products_delay_1b = \''.trim($liste[53]).'\',
                                        products_delay_2b = \''.trim($liste[54]).'\',

		                                products_meta_title_1 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[55]))).'\',
		                                products_meta_title_2 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[56]))).'\',
		                                products_meta_title_3 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[57]))).'\',
		                                products_meta_description_1 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[58]))).'\',
		                                products_meta_description_2 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[59]))).'\',
		                                products_meta_description_3 = \''.remApos(str_replace($noPass3," ",strip_tags($liste[60]))).'\'';
		                                
                                   $result= mysql_query($query);

                               if(mysql_error()) {
                                   print "<p align='center' class='fontrouge'>Error in de database: ".mysql_error();
                                   print "<br>De import functie is gestopt!</p>";
                                   exit();
                                }
                        }
      
                  $queryS = mysql_query("SELECT products_id FROM products WHERE products_ref = 'GC100'");
                  if(mysql_num_rows($queryS) == 0) {
       
                     $querySZ = mysql_query("SELECT products_id FROM products WHERE products_id = '1'");
                     if(mysql_num_rows($querySZ) == 0) $_id="1"; else $_id="";
                     
        
                                        $query2 = 'INSERT INTO '.$table.' SET
                                        products_id = \''.$_id.'\',
                                        categories_id = \'0\',
                                        categories_id_bis = \'\',
                                        fournisseurs_id = \'0\',
                                        afficher_fournisseur = \'no\',
                                        fabricant_id = \'0\',
                                        afficher_fabricant = \'no\',
                                        products_name_1 = \'Chèque cadeau\',
                                        products_name_2 = \'Gift certificate\',
                                        products_name_3 = \'Geschenkbon\',
                                        products_desc_1 = \'\',
                                        products_desc_2 = \'\',
                                        products_desc_3 = \'\',
                                        products_garantie_1 = \'\',
                                        products_garantie_2 = \'\',
                                        products_garantie_3 = \'\',
                                        products_price = \'100.00\',
                                        products_weight = \'0\',
                                        products_options = \'no\',
                                        products_option_note_1 = \'\',
                                        products_option_note_2 = \'\',
                                        products_option_note_3 = \'\',
                                        products_ref = \'GC100\',
                                        products_im = \'yes\',
                                        products_image = \'im/cheque_cadeau.gif\',
                                        products_image2 = \'\',
                                        products_image3 = \'\',
                                        products_image4 = \'\',
                                        products_image5 = \'\',
                                        products_note_1 = \'\',
                                        products_note_2 = \'\',
                                        products_note_3 = \'\',
                                        products_visible = \'yes\',
                                        products_taxable = \'yes\',
                                        products_tax = \'0.00\',
                                        products_date_added = \''.$hoy.'\',
                                        products_viewed = \'0\',
                                        products_added = \'0\',
                                        products_qt = \'1000000\',
                                        products_download = \'no\',
                                        products_download_name = \'\',
                                        products_related = \'\',
                                        products_exclusive = \'no\',
                                        products_sold = \'0\',
                                        products_deee = \'0\',
                                        products_ean = \'\',
                                        products_sup_shipping = \'0\',
                                        products_caddie_display = \'no\',
                                        products_forsale = \'yes\',
										
										products_delay_1 = \'0\',
                                        products_delay_2 = \'0\',
                                        products_delay_1a = \'0\',
                                        products_delay_2a = \'0\',
                                        products_delay_1b = \'0\',
                                        products_delay_2b = \'0\'';
                           $result2 = mysql_query($query2);
                        }
                    }
                    else {
                        print "<p align='center' class='fontrouge'>Openen van het bestand <b>".$fichier."</b> is onmogelijk</p></font>";
                        exit;
                    }
         }
         else {
               print "<p align='center'><table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'  class='fontrouge'><b>Het bestand werd niet gevonden. De import functie is gestopt...</b></tr></td></table>";
               print "<p align='center'><a href='index.php'><b>Ga terug</b></a></p>";
               exit();
         }
         
	print "<p align='center'><table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'  class='fontrouge'><b>Het importeren werd succesvol uitgevoerd</b></tr></td></table>";

	if(isset($dupId) AND count($dupId)>0) {
		$dupItems = implode(", ", $dupId);
		print "<p align='center' style='color:#000000; background:#FFFF00; padding:8px; border:1px #CCCCCC solid;'>De artikelen met het ID nummer <b>".$dupItems."</b> is reeds in gebruik door een ander artikel.<br>De ID is gewijzigd om het valideren in de database toe te laten.<br></p>";
	}
	fclose($fp);
}
?>
    
   <form method="post" action="<?php print $_SERVER['PHP_SELF'];?>">
     <table align="center" border="0" cellspacing="0" cellpadding="5" class="TABLE" width="700">
      <tr>
       <td>Naam van de database</td>
       <td>
       <b><?php print $bddName;?></b>
       
       <input type="hidden" name="name" value="<?php print $bddName;?>">
       </td>
      </tr>
      <tr>
       <td>Naam van de tabel</td>
       <td>
       <b>products</b>
       <input type="hidden" name="table" value="products"</td>
      </tr>
      <tr>
       <td>Bestand om te importeren</td>
       <td align="left">admin/import/artikelen/<input type="text" class="vullen" size="50" name="fichier" value="" ></td>
      </tr>
      <tr>
       <td colspan="2" align="center">
       <span class='fontrouge'>Controleer of het bestand op CHMOD 666 staat</span><br><br>
            <input type="submit" name="action1" value="Importeer het bestand" style='border:#FF0000 1px solid; -moz-border-radius : 1px 1px 1px 1px;'>
<?php
if(isset($_SESSION['user']) AND $_SESSION['user']=='admin') {
?>
<br>
<br>
<input type="submit" name="action2" value="Maak de tabel leeg" class="knop">
<?php
}
?>
        </td>
      </tr>
     </table>
    </form>
    
    <br>
    
    <table align="center" width="700" border="0" cellspacing="5" cellpadding="5" style="BACKGROUND-COLOR: #FFFFFF; border: 1px #CCCCCC solid">
      <tr>
       <td>
        <p>Het is mogelijk om uw opgeslagen gegevens in een spreadsheet (bijvoorbeeld Excel) te importeren naar een MySQL database.
<br>
Controleer het volgende:
<br><br>
1. De tabel 'product" van de database moet leeg zijn.
<br>
2. Elke kolom van uw Excel rekenblad moet overeen komen met een veld,
<br>
3. De kolommen van het rekenblad moeten in dezelfde volgorde zijn en hetzelfde aantal hebben als de tabellen in de database.
<br>
4. Het Excel rekenblad bevat enkel gegevens (geen hoofding noch titel).
<br>
5. Tussen de gegevens in de cellen van uw Excel rekenblad staan geen tabulaties.
<br>
6. In de gegevens in de cellen van uw Excel rekenblad staat geen aanhalingstekens (‘).
<br>
Selecteer optie "opslaan als" onder het menu "bestand".
<br>
Kies extensie ".txt" (tab is scheiding steken).
<br>
Nadat je een naam gegeven hebt aan het bestand (voorbeeld: artikelen.txt), bewaar je het bestand in de directory <b>admin/import/artikelen</b> van de shop. 
<br>
Selecteer het opgeslagen bestand om te importeren
<br>




</p>
        
        <p align="center"><a href="items_voorbeeld.txt" target="_blank"><b>Bekijk een voorbeeld</b></a></p>
        <p align="center"><a href="structuur.txt" target="_blank"><b>Bekijk de structuur van de tabel namen</b></a></p>
       </td>
      </tr>
     </table>
<br><br><br>

</body>
</html>
