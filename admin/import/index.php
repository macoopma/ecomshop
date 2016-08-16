<?php
session_start();
if(!isset($_SESSION['login'])) { header("Location:../index.php");}
include('../../configuratie/mysql_connect.php');



function noClient() {
    $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZWXYZ123456789';
    $str2 = '123456789123456789';
    
    $clientNo = '';
  	 for( $i = 0; $i < 7 ; $i++ ) {
      	$clientNo .= substr($str1, rand(0, strlen($str1) - 1), 1);
    }
    return $clientNo;
}
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' href='../style.css'>
</head>

<?php
//------
// LEEG
//------
if(isset($_POST['action']) AND $_POST['action']=="Maak de tabel leeg") {

   $bdd = $_POST['name'];
   $table = $_POST['table'];

 
   mysql_connect($bddHost,$bddUser,$bddPass) or die("Geen connectie met de database");
   mysql_select_db($bdd);
    $query = mysql_query("SELECT users_pro_id FROM ".$table);
    $queryNum = mysql_num_rows($query);
    if($queryNum > 0) {
        while($idNum = mysql_fetch_array($query)) {
            mysql_query("DELETE from ".$table." WHERE users_pro_id='".$idNum['users_pro_id']."'");
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


//-------
// Import
//-------
if(isset($_POST['action']) AND $_POST['action']=="Importeer het bestand") {


   $separateur = "\t";
   $bdd = $_POST['name'];
   $table = $_POST['table'];
   $datet = date("Y-m-d H:i:s");

    mysql_connect($bddHost,$bddUser,$bddPass) or die("Geen connectie met de database");

    mysql_select_db($bdd);
    $fichier = $_POST['fichier'];

 
 print "<p align='center'>Bestand: <b>".$fichier."</b></p>";


         if(file_exists($fichier)) {
             if($fp = fopen($fichier, "r")) {
             
                        while (!feof($fp)) {
                               
                               $ligne = fgets($fp,filesize($fichier));
                               $liste = explode($separateur ,$ligne);
                               if(empty($liste[0])) {break;}
                               if($liste[16]=='no' OR $liste[16]=='') { $liste[16] = noClient();}
                               
                                    $users_pro_email        =   $liste[0];
                                    $users_pro_gender       =   $liste[1];
                                    $users_pro_company      =   $liste[2];
                                    $users_pro_address      =   $liste[3];
                                    $users_pro_city         =   $liste[4];
                                    $users_pro_postcode     =   $liste[5];
                                    $users_pro_country      =   $liste[6];
                                    $users_pro_activity     =   $liste[7];
                                    $users_pro_telephone    =   $liste[8];
                                    $users_pro_fax          =   $liste[9];
                                    $users_pro_tva          =   $liste[10];
                                    $users_pro_tva_confirm  =   $liste[11];
                                    $users_pro_lastname     =   $liste[12];
                                    $users_pro_firstname    =   $liste[13];
                                    $users_pro_poste        =   $liste[14];
                                    $users_pro_comment      =   $liste[15];
                                    $users_pro_password     =   $liste[16];
                                    $users_pro_active       =   $liste[17];
                                    $users_pro_reduc        =   $liste[18];
                                    $users_pro_payable      =   $liste[19];
                                    
                                    $query = 'INSERT INTO '.$table.' SET
                                    users_pro_email       =\''.$liste[0].'\',
                                    users_pro_gender      =\''.$liste[1].'\',
                                    users_pro_company     =\''.$liste[2].'\',
                                    users_pro_address     =\''.$liste[3].'\',
                                    users_pro_city        =\''.$liste[4].'\',
                                    users_pro_postcode    =\''.$liste[5].'\',
                                    users_pro_country     =\''.$liste[6].'\',
                                    users_pro_activity    =\''.$liste[7].'\',
                                    users_pro_telephone   =\''.$liste[8].'\',
                                    users_pro_date_added  =\''.$datet.'\',
                                    users_pro_fax         =\''.$liste[9].'\',
                                    users_pro_tva         =\''.$liste[10].'\',
                                    users_pro_tva_confirm =\''.$liste[11].'\',
                                    users_pro_lastname    =\''.$liste[12].'\',
                                    users_pro_firstname   =\''.$liste[13].'\',
                                    users_pro_poste       =\''.$liste[14].'\',
                                    users_pro_comment     =\''.$liste[15].'\',
                                    users_pro_password    =\''.$liste[16].'\',
                                    users_pro_active      =\''.$liste[17].'\',
                                    users_pro_reduc       =\''.$liste[18].'\',
                                    users_pro_payable     =\''.$liste[19].'\'';
                                    
                                   $result= mysql_query($query);
                         
                               if(mysql_error()) {
                                   print "<p align='center' class='fontrouge'><b>Error in de database: ".mysql_error();
                                   print "<br>De import functie is gestopt</b></p>";
                                   exit();
                                }
                        }
                    }
                    else {
                        print "<p align='center' class='fontrouge'><b>Openen van het bestand <b>".$fichier." is niet gelukt</b></p>";
                        exit;
                    }
         }
         else {
               print "<p align='center'><table align='center' border='0' cellspacing='0' cellpadding='5' class='TABLE' width='700'><tr><td align='center'  class='fontrouge'><b>Het bestand werd niet gevonden. De import functie is gestopt...</b></tr></td></table>";
               print "<p align='center'><a href='index.php'><b>Ga terug</b></a></p>";
               exit();
         }
         
print "<p align='center' class='fontrouge'><b>De import werd succesvol uitgevoerd</b></p>";
fclose($fp);
}
?>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

    <p align="center" class="largeBold">Klant accounts importeren</p>
    
   <?php print "<form method='post' action='".$_SERVER['PHP_SELF']."'>";?>
     <table align="center" border="0" cellspacing="0" cellpadding="5" width="700" class="TABLE">
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
       <b>users_pro</b>
       <input type="hidden" name="table" value="users_pro"</td>
      </tr>
      <tr>
       <td>Bestand om te importeren</td>
       <td>admin/import/<input type="text" name="fichier"  size="50" class="vullen" ></td>
      </tr>
      <tr>
       <td colspan="2" align="center">
       <span class='fontrouge'>Controleer of het bestand op CHMOD 666 staat<b></span><br><br>
            <input type="submit" name="action" value="Importeer het bestand" style='border:#FF0000 1px solid; -moz-border-radius : 1px 1px 1px 1px;'>
            <br>
            <br>
            <input type="submit" name="action" class="knop" value="Maak de tabel leeg">
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
1. De tabel 'users_pro" van de database moet leeg zijn.
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
Nadat je een naam gegeven hebt aan het bestand (voorbeeld: klanten.txt), bewaar je het bestand in de directory <b>admin/import/</b> van de shop. 
<br>
Selecteer het opgeslagen bestand om te importeren
<br>
<br><br><center>
        <div><a href="kolommen.txt" target="_blank"><b>Bekijk een voorbeeld</b></a></div><br>
        <div><a href="structuur.txt" target="_blank"><b>Bekijk de structuur van de tabel namen</b></a></div>
        
        </p>
       </td>
      </tr>
     </table>
<br><br><br>
</body>
</html>
