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

$sql_connect_serveur = mysql_connect($bddHost, $bddUser, $bddPass);

function gestionErreurSql() {
   
   global $sql_connect_serveur;
   
   $exp_sql_erreur = explode('\'', mysql_error($sql_connect_serveur) );
   

   switch(mysql_errno($sql_connect_serveur) ) {
      case 1040:  
         return 'Er zijn  teveel connecties';
         break;
      case 1044:  
         return 'La base de données « '.$exp_sql_erreur[3].' » n\'a pas été trouvée.';
         break;
      case 1045:  
         return 'L\'utilisateur désigné « '.$exp_sql_erreur[1].' » n\'a pas été trouvé.';
         break;         
      case 1046:  
         return 'Geen database geselecteerd';
         break;         
      case 1052:  
         return 'WHERE is ongeldig voor kolom '.$exp_sql_erreur[1].'.';
         break;         
      case 1053: 
         return 'Server is uitgeschakeld';
         break;
      case 1054:
          
         switch($exp_sql_erreur[3]) {
            case 'field list':
               return 'Het commando « '.$exp_sql_erreur[1].' » is niet geldig';
               break;
            case 'where clause': 
               return 'Het commando « '.$exp_sql_erreur[1].' » is ongeldig voor WHERE';
               break;
            default:
               return mysql_error($sql_connect_serveur);
         }
         break;         
      case 1062:
         return 'Duplicate « '.$exp_sql_erreur[1].' ».';
         break;
      case 1064:
         return 'Er is een error opgetreden in de SQL syntax « '.$exp_sql_erreur[1].' ».';         
         break;
      case 1065:
         return 'Lege query';         
         break;
      case 1109: 
      case 1146: 
         return 'Ongeldige tabel « '.$exp_sql_erreur[1].' ';         
         break;         
      case 2002:
         return 'Geen connectie naar de database';
         break;         
      case 2003:
         return 'Geen connectie naar de database « '.$exp_sql_erreur[1].' ».';
         break;
      case 2005: 
         return 'Ongeldige sserver host « '.$exp_sql_erreur[1].'';
         break;
      case 2013: 
         return 'De connectie is vreloren gegaan tijdens de uitvoer';
         break;
      default:
         return mysql_error($sql_connect_serveur);
   }
}

if(isset($_POST['request']) AND $_POST['request']!=='') {
 
    $_POST['request'] = str_replace("\'","'", $_POST['request']);
    $_POST['request'] = str_replace('\"','"', $_POST['request']);
  
    $explodeRequest = explode(';',$_POST['request']);
   
    foreach ($explodeRequest as $key => $value) {
          if(is_null($value) OR trim($value)=="" OR empty($value)) {
            unset($explodeRequest[$key]); 
          }
    }
    
        foreach($explodeRequest AS $item) { 
        $sql_result = mysql_query($item);
          if($sql_result === FALSE ) {
                $messageOk = '<table width=700 border=0 align=center cellpadding=8 cellspacing=0 class=TABLE><tr><td><p align="left" class="fontrouge">';
                $messageOk .= 'Error SQL: '.mysql_errno($sql_connect_serveur)."<br>";
                $messageOk .= 'Database: '.$bddName."<br>";         
                $messageOk .= 'Commando: '.$item."<br>";
                $messageOk .= 'Bericht: '.gestionErreurSql()."<br>";
                $messageOk .= '</p>';
             }
             else {
                 $messageOk = "<p align='center' class='fontrouge'><b>Het commando werd succesvol uitgevoerd</b></p>";
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
<br>
<p align="center" class="largeBold">Database update - commando in database <?php print $bddName;?></p>

<?php if(isset($messageOk)) print $messageOk;?>

<form action="sql.php" method="POST">
<p align="center">
<textarea name="request" cols="100" rows="8"></textarea>
<br><br>
<input type="submit" class="knop" value="Uitvoeren">
</p>
</form>


  </body>
  </html>
