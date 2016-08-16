<?php
if(isset($_SESSION['userInterface']) AND !empty($_SESSION['userInterface'])){
   print "<link rel='stylesheet' href='css/".$_SESSION['userInterface'].".css' type='text/css'>";
}
else {
   if($activerCouleurPerso=="oui") {
      print "<link rel='stylesheet' href='css/perso.css' type='text/css'>";
   }
   else {
      print "<link rel='stylesheet' href='css/".$colorInter.".css' type='text/css'>";
   }
}
?>
