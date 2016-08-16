<?php

        if($taxePosition=="Tax included") $_SESSION['taxStatut'] = ACHAT_TTC;
        if($taxePosition=="Plus tax") $_SESSION['taxStatut'] = ACHAT_HT;
        if($taxePosition=="No tax") {
            $_SESSION['taxStatut'] = ACHAT_ST;
            $incl = D_ACHAT;
        }
        else {

            $incl = D_ACHAT_HORS_TAXE;
        }

            $textPromo =  POUR." <span class='fontrouge'><b>".$livraisonComprise." ".$symbolDevise."</b></span> ".$incl.".";
            if($livraisonComprise == 0) {
               $textPromo =  POUR." <span class='fontrouge'><b>".TOUT."</b></span> ".ACHAT_SUR.".";
            }

            $textPromoRemise =  POUR." <span class='fontrouge'><b>".$remiseOrderMax." ".$symbolDevise."</b></span> ".$incl.".";
            if($remiseOrderMax == 0) {
               $textPromoRemise =  POUR." <span class='fontrouge'><b>".TOUT."</b></span> ".ACHAT_SUR.".";
            }
        

   print "<table border='0' width='100%' cellspacing='0' cellpadding='1' class='TABLE'><tr>";

      // bericht btw berekening
           print "<td valign='middle'><img src='im/fleche_right.gif'></td>
                   <td>".PRIXEN." <b>".$_SESSION['taxStatut']."</b>.</td>";
           print "</tr><tr>";

      // bericht verzending
           print "<td valign='top'><img src='im/zzz.gif' width='1' height='3'><br><img src='im/fleche_right.gif'></td>
                   <td><b>".PAS_INCLU."</td>";
           print "</tr>";

      // promotie levering
      if($activerPromoLivraison == "oui") {
         print "<tr>";
         print "<td valign='middle'><img src='im/fleche_right.gif'></td>
                 <td>".PEND_GRAT." ".$textPromo."</td>";
         print "</tr>";
      }

      // Promo korting
      if($activerRemise == "oui") {
         print "<tr>";
         print "<td valign='middle'><img src='im/fleche_right.gif'></td>
                 <td>".PEND_REM." <span class='fontrouge'><b>".$remise." ".$remiseType."</b></span> ".$textPromoRemise."</td>";
         print "</tr>";
      }
   print "</table>";
?>
