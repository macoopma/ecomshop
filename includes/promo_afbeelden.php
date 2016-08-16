<?php

if(isset($_SESSION['account']) OR $activeEcom=="oui") {
if($remiseOnTax == "TTC") {$textTax = D_ACHAT_TTC;} else {$textTax = D_ACHAT_HORS_TAXE;}
   $incl = "(<a href='voorwaarden.php' target='_blank'>*</a>)";
if($taxePosition == "No tax") {
   $textTax = D_ACHAT;
   $incl = "";
}

            $textPromo =  POUR." <span class='PromoFont'><b>".$livraisonComprise." ".$symbolDevise."</b></span> ".$textTax;
            if($activerPromoLivraison == "oui" AND $livraisonComprise == 0) {
               $textPromo =  POUR." <span class='fontrouge'><b>".maj(TOUT)."</b></span> ".ACHAT_SUR_DOMAINE;
            }

            $textPromoRemise =  POUR." <span class='PromoFont'><b>".$remiseOrderMax." ".$symbolDevise."</b></span> ".$textTax;
            if($activerRemise == "oui" and $remiseOrderMax == 0) {
               $textPromoRemise =  POUR." <span class='PromoFont'><b>".maj(TOUT)."</b></span> ".ACHAT_SUR_DOMAINE;
            }


           if($displayPromoShipping == "oui" AND $displayPromoRemise == "non") {
                print "<table border='0' align='center' width='99%' cellspacing='0' cellpadding='0' class='TABLEPromoBannerTop'><tr>";
                print "<td  align='center' class='PromoFont'>";
                print "<b>".LIVRAISON_GRATUITE."</b> ".$textPromo." ".$incl;
                print "</td></tr></table>";
            }

           if($displayPromoRemise == "oui" AND $displayPromoShipping == "non") {
                print "<table border='0' align='center' width='99%' cellspacing='0' cellpadding='0' class='TABLEPromoBannerTop'><tr>";
                print "<td align='center'  class='PromoFont'>";
                print REMISE_DE." <b>".$remise." ".$remiseType."</b> ".$textPromoRemise;
                print "</td></tr></table>";
            }

           if($displayPromoShipping == "oui" AND $displayPromoRemise == "oui") {
                print "<table border='0' align='center' width='99%' cellspacing='0' cellpadding='0' class='TABLEPromoBannerTop'><tr>";
                print "<td align='left' class='PromoFont'>";
                print "<b>".LIVRAISON_GRATUITE."</b> ".$textPromo." ".$incl;
                print "</td>";
    
                print "<td align='right' class='PromoFont'>";
                print REMISE_DE." <b>".$remise." ".$remiseType."</b> ". $textPromoRemise;
                print "</td></tr></table>";
            }
}
?>
