<?php


 
if(isset($_SESSION['account'])) {$gototTo = "payment";} else {$gototTo = "login";}

 
if(isset($_SESSION['list']) and $_SESSION['list'] !== "") {
	print "<div class='raised' style='width:".$larg_rub."'>";
	print "<b class='top'><b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b></b>";
	print "<div class='boxcontent'>";

	print "<table border='0' cellspacing='0' cellpadding='2' class='caddie-fond'>";
	print "<tr>";
	print "<td height='".$hauteurTitreModule."' class='moduleCaddieTitre contentTop' align='center' style='width:".$larg_rub."'>";
	print "<a href='caddie.php'><img src='im/lang".$_SESSION['lang']."/menu_caddie.png' border='0' title='".VOTRE_CADDIE."' alt='".VOTRE_CADDIE."'></a>";
	print "</td>";
	print "</tr>";
	print "<tr>";
	print "<td class='caddie-box'>";

	$art = explode(",",$_SESSION['list']);
	$nb_article = count($art);
	$nb_article_1 = $nb_article-1;
	$tot_ref = 0;

	for($q=0; $q<=$nb_article_1; $q++) {
		$reference[$q] = explode("+",$art[$q]);
		$tot_ref = $reference[$q][4];
		if($reference[$q][1] == "0" OR $reference[$q][1] == "" ) {
			print "";
		}
		else {
			if($reference[$q][2] > 0) {$plus = $reference[$q][1]+1;} else {$plus = 1;}
			if($reference[$q][1] > 0) {$moins = $reference[$q][1]-1;} else {$moins = 0;}
			$quit = 0;
			$refdet = $tot_ref;
			
			$query = mysql_query("SELECT categories_id, products_qt, products_ref FROM products WHERE products_id = '".$reference[$q][0]."'");
			$pathId = mysql_fetch_array($query);
			
			print "<table border='0' width='100%' cellspacing='0' cellpadding='2' class='cartItem'>";
			print "<tr>";
			print "<td colspan='2' class='tiny'>";
			
			 
			print "<img src='im/fleche_right.gif'>";
			if($pathId['products_ref']=="GC100") {
				print "&nbsp;<span class='cartItemFont'>".adjust_text($refdet,16,"..")."</span>";
			}
			else {
				print "&nbsp;<a href='beschrijving.php?id=".$reference[$q][0]."&path=".$pathId['categories_id']."' ".display_title($refdet,16).">";
				print "<span class='cartItemFont'>".adjust_text($refdet,16,"..")."</span>";
				print "</a>";
			}
			 
			if(!empty($reference[$q][6])) {
				$_opt = explode("|",$reference[$q][6]);
				## session update option price
				$lastArray = $_opt[count($_opt)-1];
				if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
				print "<br>";
				foreach($_opt AS $items) {
					print "<div ".display_title($items,18).">- <i>".adjust_text($items,18,"..")."</i></div>";
				}
			}
			print "</td>";
			print "</tr><tr>";
			 
			print "<td class='tiny'>";
			print "&nbsp;[".$symbolDevise." ".sprintf("%0.2f",$reference[$q][2])."x<span class='fontrouge'><b>".$reference[$q][1]."</b></span>]</td>";
			$res = ($actRes=="oui" AND $pathId['products_qt'] <= 0)? "&statut=reserve" : "";

			print "<td valign='bottom' width='25' align='right'>";
			if(isset($_SESSION['activerCadeau']) OR $reference[$q][3]=="GC100" OR isset($_SESSION['devisNumero'])) {
				print "...";
			}
			else {
 
				print "<a href='add.php?adjust_cart=1&id=".$reference[$q][0]."&amount=".$plus."&ref=".$reference[$q][3]."&deee=".$reference[$q][7]."&options=".$reference[$q][6].$res."'><img src='im/_plus.gif' border='0' alt='".AJOUTER_1_ITEM_AU_CADDIE."' title='".AJOUTER_1_ITEM_AU_CADDIE."'></a>";
				print "|";
 
				print "<a href='add.php?adjust_cart=1&id=".$reference[$q][0]."&amount=".$moins."&ref=".$reference[$q][3]."&deee=".$reference[$q][7]."&options=".$reference[$q][6].$res."'><img src='im/_moins.gif' border='0' alt='".ENLEVER_1_ITEM_AU_CADDIE."' title='".ENLEVER_1_ITEM_AU_CADDIE."'></a>";
				print "|";
 
				print "<a href='add.php?id=".$reference[$q][0]."&amount=".$quit."&ref=".$reference[$q][3]."&deee=".$reference[$q][7]."&options=".$reference[$q][6].$res."'><img src='im/_quit.gif' border='0' alt='".SUPPRIMER_L_ARTICLE_REF."' title='".SUPPRIMER_L_ARTICLE_REF."'></a>";
			}
		print "</td>";
		print "</tr></table>";
		}
	}

	$_SESSION['tot_art'] = 0;
	$_SESSION['totalTTC'] = 0;

	for($n=0; $n<=$nb_article_1; $n++) {
		$article = explode("+",$art[$n]);
		$_SESSION['tot_art'] = $_SESSION['tot_art'] + $article[1];
		$_SESSION['totalTTC'] = $_SESSION['totalTTC'] + ($article[2]*$article[1]);
		$_SESSION['totalTTC'] = sprintf("%0.2f", $_SESSION['totalTTC']);
	}

	print "<table width='100%' border='0' cellspacing='3' cellpadding='0' class='caddie-art-tot'>";
      print "<tr>";
   
      print "<td>".ARTICLES.":</td>";
      print "<td class='TABLE1' style='padding:1px' width='70'><b>".$_SESSION['tot_art']."</b></td>";
      print "</tr>";
      print "<tr>";
  
      print "<td>".TOTAL.":</td>";
      print "<td class='TABLE1' style='padding:1px' width='70'><b>".$_SESSION['totalTTC']."</b> ".$symbolDevise."</td>";
      print "</tr>";
      print "<tr>";
      print "<td colspan='2'><div class='tiny'>";

    
      if($taxePosition=="Tax included") $langMess = TAXE_INCLUSE;
      if($taxePosition=="Plus tax") $langMess = TAXE_NON_INCLUSE;
      if($taxePosition=="No tax") $langMess = PAS_DE_TAXE;
      print "<i>".$langMess."</i><br>";

     
      if(isset($livraisonsht) and $livraisonht>0) {
      	print "<i>".LIVRAISON_NON_INCLUSE."</i><br>";
      }
      print "</div></td>
      </tr>
      </table>";
	 
	if(isset($_SESSION['caddie_password'])) {
		print " <table class='TABLEBoxUpdateCart' width='100%' border='0' cellspacing='1' cellpadding='0'>";
		print "<tr>";
		print "<td colspan='2' align='center' class='fontrouge'><b>".CADDIE_ID.": ".$_SESSION['caddie_password']."</b></td>";
		print "</tr>";
		print "<tr>";
		print "<td colspan='2' align='center'>";
		print "- <a href='".$url_id10.$slash."var=upd'>".maj(ACTUALISER)."</a> -<br>";
		print "- <a href='".$url_id10.$slash."var=deleteSavedCart'>".maj(SUPPRIMER)."</a> -<br>";
		print "- <a href='".$url_id10.$slash."var=closeSavedCart'>".maj(FERMER)."</a> -";
		print $message2;
		print "</td>";
		print "</tr>";
		print "</table>";
	}

	print "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='TABLEInfoCaddie'><tr>";
	print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href='caddie.php'><b><span class='PromoFont'>".VOTRE_CADDIE."</span></b></a></td>";
	print "</tr>";
	print "<tr>";
	print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("infos.php?info=11")."'>".SUIVI_COMMANDE."</a></td>";
	if($paymentsDesactive=="non") {
		print "</tr>";
		print "<tr>";
		print "<td colspan='2'>";
		print "<img src='im/fleche_right.gif'>&nbsp;<a href='".$gototTo.".php'>".PAIEMENT_DIRECT."</a>";
		print "</td>";
	}
	print "</tr>";
	print "<tr>";
	if($activeSaveCart=="oui" AND isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
		if(!isset($_SESSION['devisNumero'])) {
			print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href= 'mijn_account.php'>".RECUPERER_CADDIE."</a></td>";        
		}
	}
	else {
		if($activeSaveCart=="oui" AND !isset($_SESSION['devisNumero'])) {
			print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href= 'bewaar_winkelmandje.php'>".RECUPERER_CADDIE."</a></td>";        
		}
	}
	print "</tr>";
	print "<tr>";
	print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href= '".$url_id10.$slash."var=session_destroy'>".VIDER_CADDIE."</a></td>";
	print "</tr>";
	print "</table>";
	
	print '</div><b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b></div>';
	        
	print "</td>";
	print "</tr>";
	print "</table>";
}
else {
 
	if(isset($_SESSION['tot_art'])) unset($_SESSION['tot_art']);
	if(isset($_SESSION['totalTTC'])) unset($_SESSION['totalTTC']);
	if(isset($_SESSION['list'])) unset($_SESSION['list']);
	
	print "<div class='raised' style='width:".$larg_rub."'>";
	print "<b class='top'><b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b></b>";
	print "<div class='boxcontent'>";
	
	print "<table border='0' cellspacing='0' cellpadding='2' class='caddieBoxVide'>";
	print "<tr>";
	print "<td height='".$hauteurTitreModule."' class='moduleCaddieTitre contentTop' align='center' style='width:".$larg_rub."'> ";
	print "<a href='caddie.php'><img src='im/lang".$_SESSION['lang']."/menu_caddie.png' border='0' title='".VOTRE_CADDIE."' alt='".VOTRE_CADDIE."'></a>";
	print "</td>";
	print "</tr>";
	print "<tr>";
	print "<td>";
	
	print "<table width='100%' border='0' cellspacing='3' cellpadding='2'>";
	print "<tr>";
	print "<td>".ARTICLES.":</td>";
	print "<td width='70'><b>0</b></td>";
	print "</tr>";
	print "<tr>";
	print "<td>".TOTAL.":</td>";
	print "<td width='70'><b>0.00</b> ".$symbolDevise."</td>";
	print "</tr>";
	print "</table>";
	
	print "<table width='100%' border='0' cellspacing='0' cellpadding='2' class='caddieInfoVide'>";
	print "<tr>";
	print "<td colspan='2' class='PromoFont TDotted'><img src='im/fleche_right.gif'>&nbsp;<b>".CADDIE_VIDE."</b></td>";
	print "</tr>";
	print "<tr>";
	print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("infos.php?info=11")."'>".SUIVI_COMMANDE."</a></td>";
	print "</tr>";
	print "<tr>";
	if($activeSaveCart=="oui" AND isset($_SESSION['openAccount']) AND $_SESSION['openAccount']=="yes") {
		print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href= 'mijn_account.php'>".RECUPERER_CADDIE."</a></td>";        
	}
	else {
		if($activeSaveCart=="oui") print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("bewaar_winkelmandje.php")."'>".RECUPERER_CADDIE."</a></td>";        
	}
	print "</tr>";
	print "<tr>";
	print "<td colspan='2'><img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("infos.php?info=6")."'>".UTILISATION_CADDIE."</a></td>";
	print "</tr>";
	print "</table>";
        
	print "</td>";
	print "</tr>";
	print "</table>";
}
?>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
