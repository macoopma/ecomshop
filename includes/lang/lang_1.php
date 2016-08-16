<?php
// Opgelet: om errors te vermijden - waar je zelf de schuld aan heeft - werd de code geschreven met 
// 'enkele aanhalings tekens (voorbeemd: href='http://www.google.com') binnen de dubbele aabhaling tekens
// voorbeeld define('COMMUNIQUE',"HTML code en <font color='red'>in rood</font>");)
define('COMMUNIQUE1',"<div align='center'><b>BESOIN D'ARGENT ?</b><br><a href='affiliation.php'>AFFILIEZ-VOUS <u>ICI</u></a><br> et recevez <span class='fontrouge'><b>".$affiliateCom."%</b></span> sur les ventes générées par les visiteurs provenant de votre site.</div>");
define('COMMUNIQUE2',"<div align='center'><b>Paiment sécurisé<br>Payez en toute confiance</b><br><img src='im/payment.gif'><br></div>");
define('COMMUNIQUE3',"<div align='center'><b>BESOIN D'UN CONSEIL</b><br>CONTACTEZ NOUS AU :<br><div class='fontrouge'><b>Tel: ".$tel."</b></div><div><b>OU</b><br><a href='infos.php?info=5'><u>Cliquez ICI</u></a></div></div>");

define('MEILLEURES_VENTES',"Quelques articles... Les plus populaires !");
define('LAST_NEWS',"Quelques nouveautés... en exclusivité !");
define('FEW_PRODUCTS',"Quelques articles... Au hasard !");
define('EN_PROMO',"Nos offres spéciales... à ne pas manquer !");
define('EN_EXCLUSIVITE',"Coup de coeur !");
define('DIX_MEILLEURES_VENTES',"Nos 10 meilleures ventes");
define('PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE',"-- Pas d'article dans cette cat&eacute;gorie --");
define('PAS_DE_PROMOTIONS_DANS_CETTE_CATEGORIE','Pas de promotions dans cette cat&eacute;gorie');
define('PAS_DE_NOUVEAUTES_DANS_CETTE_CATEGORIE','Pas de nouveaut&eacute;s dans cette cat&eacute;gorie');
define('QUELQUES_UN_DES_PLUS_RECENTS_ARTICLES','Quelques un des plus r&eacute;cents articles.');
define('PROMOTION','*PROMO*');
define('REF','N/R&eacute;f');
define('BIENVENU_SUR','Bienvenue sur ');
define('MENU_RAPIDE','Menu rapide');
define('MENU_RAPIDE2','Catégories');
define('MENU_RAPIDE3','Marque');
define('TOUTES_MARQUES','Par marques');
define('TOUTES_CAT','Par produits');
define('AUTRES','Autres');
define('NOUVEAUTES','Nouveautés');
define('NOUVEAUTESMAJ','NOUVEAUT&Eacute;S');
define('PROMOTIONS','Promotions');
define('COMMENTAIRES','Commentaires');
define('PRODUITS','Produits');
define('LIVRAISON','Frais de livraison');
define('TOTAL','Total');
define('VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE','Vous n\'avez pas d\'article dans votre panier');
define('ARTICLES','Articles');
define('ARTICLE','Article');
define('PRIX','Prix');
define('PRIXEN','Prix');
define('PRIX_UNITAIRE','P.U.');
define('QTE','Qte');
define('COMMANDER','Commander');
define('TVA','TVA');
define('PRIX_HT','Prix HT');
define('SOUS_TOTAL','Sous-total');
define('TOTAL_POIDS','Poids total');
define('TAXE','Taxe');
define('TOTAL_TAXE','Total taxe');
define('MONTANT_A_PAYER','Montant &agrave; payer');
define('VOICI_QUELQUES_PRODUITS_DANS_CETTE_CATEGORIE','Quelques articles dans cette cat&eacute;gorie...');
define('VOICI_QUELQUES_PRODUITS_DANS_CETTE_CATEGORIE2','Quelques articles dans les sous-cat&eacute;gories...');
define('CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE','Article dans votre panier');
define('CET_ARTICLE_N_EST_PAS_PRESENT_DANS_VOTRE_CADDIE','Cet article n\'est pas dans votre panier');
define('A_ETE_AJOUTE_DANS_VOTRE_CADDIE','a &eacute;t&eacute; ajout&eacute; dans votre panier');
define('A_ETE_SUPPRIME_DE_VOTRE_CADDIE','a &eacute;t&eacute; supprim&eacute; de votre panier');
define('AJOUTER_AU_CADDIE','Ajouter au panier');
define('RETIRER_DU_CADDIE','Retirer du panier');
define('NON','Non');
define('OUI','Oui');
define('POIDS','Poids');
define('EN_PROMOTION_JUSQU_AU','En promotion jusqu\'au');
define('FIN_DE_PROMOTION_DANS','Fin de promotion dans');
define('JOURS','Jour');
define('SOIT','Soit');
define('EN_PROMOTION_JUSQUE_A_NOUVEL_ORDRE','En promotion pour un temps indéterminé.');
define('VOIR_LA_LISTE_DES','Voir la iste des');
define('EN_STOCK','En stock');
define('NOT_IN_STOCK','En commande');
define('IN_ORDER_MESSAGE','Disponible sous 8 jours.');
define('PAGE','Page');
define('DE','De');
define('A','&agrave;');
define('TOUTES','Toutes');
define('TOUT','Tout');
define('IMAGE','Image');
define('ARTICLE_TAXABLE','Taxe');
define('ARTICLE_TELE','Téléchargement');
define('CLASSER_PAR','Classer par');
define('CLASSER_PAR2','Classé par');
define('ARTICLE_PRESENT_DANS_LE_CADDIE','Article PR&Eacute;SENT dans le panier');
define('ARTICLE_ABSENT_DU_CADDIE',"Cet article n'est pas dans le panier");
define('LIVRAISON_GRATUITE','LIVRAISON GRATUITE');
define('D_ACHAT_HORS_TAXE','d\'achat HT');
define('D_ACHAT_TTC','d\'achat TTC');
define('ACHAT_SUR',"achat sur <b>".$store_name."</b>");
define('ACHAT_SUR_DOMAINE',"achat sur ".$store_name."");
define('REMISE_DE','<B>REMISE</B> de');
define('REMISE','Remise');
define('REMISE_SUR_COMMANDES','Remise sur points de fidélité');
define('POUR','pour');
define('RECHERCHER','Rechercher');

// panier
define('VOTRE_CADDIE','Votre panier');
define('RECUPERER_CADDIE','R&eacute;cup&eacute;rer panier');
define('ENREGISTRER_CADDIE','Enregistrer panier');
define('VIDER_CADDIE','Vider panier');
define('TELECHARGER','Télécharger');
define('DEVISE','Devise');
define('CADDIE_VIDE','Panier vide');
define('MOT_DE_PASSE','Mot de passe');
define('METTRE_A_JOUR_LE_CADDIE',"Mettre &agrave; jour le panier");
define('CADDIE_MIS_A_JOUR','Panier mis &agrave; jour');
define('ENLEVER_1_ITEM_AU_CADDIE','Retirer 1 article');
define('AJOUTER_1_ITEM_AU_CADDIE','Ajouter 1 article');
define('SUPPRIMER_L_ARTICLE_REF','Supprimer cet article du panier');
define('DANS_LE_CADDIE','Dans le panier');
define('VISUALISE_FACTURE','Voir facture');
define('SELECTIONNE_PAYS','S&eacute;lectionner pays');
define('AUX_ABONNES_DE_LA_NEWSLETTER',"Aujourd’hui");
define('VEUILLEZ_SAISIR_VOTRE_CODE','Date limite:');
define('SEUIL_DE_LA_COMMANDE','Seuil de la commande');
define('AVANT_TAXE','avant taxes');
define('ACTIVE','Activer');
define('COUPON_CODE','Code de réduction');
define('CODE_ERRONE','Code de réduction erron&eacute;!');
define('BRAVO','Remise activ&eacute;e!');
define('DATE_DEPASSE','Date limite d&eacute;pass&eacute;e!');

// INFORMATIONS
define('UTILISATION_CADDIE','Utilisation panier');
define('PAIEMENTS','Paiements');
define('EXPEDITIONS_ET_RETOURS','Exp&eacute;ditions & retours');
define('IMPRIMER_CATALOGUE','Imprimer catalogue');
define('EXPEDITIONS_ET_RETOURS_MAJ','EXP&Eacute;DITIONS & RETOURS');
define('CONDITIONS_D_USAGE','Conditions générales de vente');
define('NOUS_CONTACTER','Nous contacter');
define('CONDITIONS_DE_VENTE','Conditions générales de vente ');
		define('LINK1','Lien externe');
// converter
define('CONVERTIR','Convertir');
define('HOME','Accueil');
define('LIVRAISON_NON_INCLUSE','Livraison non incluse');
define('TAXE_INCLUSE','Taxe incluse');
define('TAXE_NON_INCLUSE','Taxe non incluse');
define('PAS_DE_TAXE','Pas de taxe');

// formulaire
define('FORMULAIRE_D_EXPEDITION',"BON DE COMMANDE");
define('VOUS','Vous');
define('CIVILITE','Civilité');
define('M','M.');
define('MME','Mme/Mlle');
define('NOM','Nom');
define('PRENOM','Prénom');
define('ADRESSE_EMAIL','Adresse Email');
define('ADRESSE','Adresse');
define('CODE_POSTAL','Code postal');
define('VOTRE_ADRESSE_D_EXPEDITION','Adresse de livraison');
define('VOTRE_ADRESSE_DE_FACTURATION22',"<b>Adresse de facturation</b>");
define('COMPAGNIE22','Nom, Prénom');
define('VILLE','Ville');
define('PAYS','Pays');
define('PROVINCE','Province');
define('VOS_INFORMATIONS_PERSONNELLE','<b>Information de contact</b>');
define('NUMERO_DE_TELEPHONE','Num&eacute;ro de t&eacute;l&eacute;phone');
define('SI_VOUS_AVEZ_DES_COMMENTAIRES','Commentaire');
define('VOTRE_COMMANDE','Votre commande');
define('SELECTIONNER_UN_MODE_DE_PAIEMENT','S&eacute;lectionner le mode de paiement');
define('CARTE_DE_CREDIT','Carte bancaire');
define('CHEQUE_OU_TRANSFERT_BANCAIRE_OU_MANDAT_POSTAL','Paiement par');
define('CONTINUER','Continuer');
define('CHAMPS_OBLIGATOIRES','Champs obligatoires');
define('HT','HT');
define('VEUILLEZ_SELECTIONNER','Veuillez sélectionner');
define('VEUILLEZ_PRENDRE_CONNAISSANCE','Veuillez prendre connaissance des conditions générales de vente.');
define('AI_LU',"J'ai lu et accepté les <b>conditions générales de vente</b> : ");
define('SAVE_INFO',"Sauvegarder l'adresse de livraison : ");
define('BIENVENU2','Bienvenue');
define('COMPTE_NON_VALIDE','Compte non valide !');
define('VEUILLEZ_SAISIT_VOTRE_MOT_DE_PASSE','Veuillez saisir votre mot de passe !');
define('VOUS_ETES_ENREGISTRE_ET_VOTRE_MOT_DE_PASSE_EST',"Récupérer une adresse de livraison sauvegardée :");
define('POUR_PLUS_DINFORMATIONS',"Pour plus d'informations, contactez nous à");
define('VOTRE_COMPTE','Votre compte');
define('LE','le');

// CONTACTEZ NOUS
define('MESSAGE','Message');
define('REMPLICEZ_CE_FORMULAIRE','<div style="font-size:12px;">Remplissez le formulaire ci-dessous pour communiquer avec nous.</div>
			<div><img src="im/zzz.gif" width="1" height="5"></div>
          	<i>Les champs marqu&eacute;s d&acute;une ast&eacute;risque (*) sont obligatoires.</i>');
define('ENVOYER','Envoyer');
define('ANNULER','Annuler');
define('VEUILLEZ_LAISSER_UN_MESSAGE','Veuillez laisser un message');
define('CHAMPS_NON_VALIDE','Champs non valide:');
define('VOTRE_COURRIER_A_ETE_ENVOYE_AVEC_SUCCES','Votre courrier a été envoyé avec succès');
define('NREF','N.R&eacute;f');

// bewaar_winkelmandje.php
define('NOM_DU_CADDIE','Nom du panier');
define('VOTRE','Votre');
define('ACTUALISER','Actualiser');
define('SUPPRIMER','Supprimer');
define('FERMER','Fermer');
define('NUMERO_DE_PANIER','Numéro de panier');
define('VEUILLEZ_ENTRER_UN','Veuillez saisir un');
define('ET_VOTRE','et un');
define('ENREGISTRER_RECUPERER_CADDIE','Enregistrer/r&eacute;cup&eacute;rer panier');
define('ENREGISTRER_RECUPERER_CADDIE_MAJ','ENREGISTER & RECUP&Eacute;RER PANIER');
define('MOT_DE_PASSE_NON_IDENTIQUE','Noms de panier non identiques.<br>Veuiller recommencer.<br>Merci.');
define('VEUIILEZ_SELECTIONNER_AU_MOINS_1_ARTICLE','<b>Votre panier est vide.</b><br>Pour enregistrer votre panier, veuillez s&eacute;lectionner au moins 1 article.<br>Merci.');
define('MOT_DE_PASSE_DEJA_UTILISE','Nom de panier d&eacute;j&agrave; utilis&eacute;, veuillez recommencer.<br>Merci.');
define('CADDIE_ENREGISTRE_AVEC_SUCCES','PANIER ENREGISTR&Eacute; AVEC SUCC&Eacute;S');
define('CONFIRMEZ_MOT_DE_PASSE','Confirmez Nom du panier');
define('VOTRE_CADDIE_N_EST_PAS_ENREGISTRE_OU','Votre panier n\'est pas enregistr&eacute; <b>OU</b> n\'est plus valide.<br>
          Veuillez recommencer ou enregistrez votre panier de nouveau.');
define('CADDIE_RECUPERE_ET_ACTUALISE','<b>PANIER R&Eacute;CUP&Eacute;R&Eacute; ET ACTUALIS&Eacute; AVEC SUCC&Eacute;S.</b><br>
          Vous pouvez continuer vos achats.');
define('TEXT_SAVECART_1',"<div><b>ENREGISTRER VOTRE PANIER</b> **</div>
          <ul>
          <li>Une fois enregistr&eacute;, nous gardons vos articles en m&eacute;moire et vous pourrez acc&eacute;der &agrave; votre panier pendant <b>une p&eacute;riode de <span style='font-size:12px;'>".$saveCart." jours</span></b>.
		  <br><b>En aucun cas, ne vous devez considérer la sauvegarde d'un panier comme une réservation</b>.</li>
          <li>Pass&eacute; ce d&eacute;lais, nous remettons vos articles en rayon et vous perdrez le contenu de votre panier. Vous devrez l'enregistrer de nouveau.</li>
          <li>Apr&eacute;s avoir entr&eacute; un nom de panier et votre email<b> un numéro de panier vous sera envoyé.</b></li>
          <li>Le <b>numéro de panier</b> vous permettra de r&eacute;cup&eacute;rer votre panier.</li>
          <li>Vous pouvez enregistrer autant de panier que vous souhaitez.</li>
          </ul>");
define('TEXT_SAVECART_2',"<div><b>R&Eacute;CUP&Eacute;RER VOTRE PANIER</b> **</div>
          <ul>
          <li>Votre panier est sauvegard&eacute; pendant <b>une p&eacute;riode de ".$saveCart." jours.</b> </li>
          <li>Pass&eacute; ce d&eacute;lais, vous perdez le contenu de votre panier et vous devrez l'enregistrer de nouveau.</li>
          <li>Le panier r&eacute;cup&eacute;r&eacute; <b>effacera</b> le contenu du panier actuel.</li>
          <li>Une fois votre panier r&eacute;cup&eacute;r&eacute;, vous pourrez poursuivre vos achats de fa&ccedil;on normale.</li>
          <li>Si vous d&eacute;sirez sauvegarder de nouveau votre panier, faire la mise &agrave; jour du panier en cliquant sur le bouton <b>'ACTUALISER'</b> (dans 'Votre panier').
            <br><b>&Agrave; chaque mise &agrave;&nbsp;jour la p&eacute;riode de ".$saveCart." jours sera prolong&eacute;e.</b></li>
          <li>$store_company ajustera automatiquement les prix des produits du panier r&eacute;cup&eacute;r&eacute;.<br>
          </li>
          </ul>");
define('TEXT_SAVECART_3',"<b>* $store_company</b> se r&eacute;serve le droit de modifier les prix, les taux de taxes ou les sp&eacute;cifications de tout article compris dans votre panier.<br>
          <b>**</b> Sauvegarder ou r&eacute;cup&eacute;rer votre panier ne garantie pas la disponibilit&eacute; du produit, le prix ou l'offre.");
define('VEUILLEZ_SAISIR_VOTRE_MOT','Veuillez saisir un mot de passe et votre numéro de panier');
define('CADDIE_ID','PANIER');
define('BONJOUR','Bonjour');
define('VOUS_VENEZ_DE_SAUVEGARDER_UN_CADDIE','Vous venez de sauvegarder un panier sur');
define('VOTRE_NOM_DE_CADDIE_EST','Votre nom de panier est');
define('VOTRE_NUMERO_DE_CADDIE_EST','Votre numéro de panier est');
define('VOTRE_CADDIE_SUR','Votre panier sur');

// SEARCH
define("MODE_EMPLOI","Mode d'emploi");
define('AIDE',"<b>La recherche est étendue aux champs Nom de l'article, Description, Référence, Fournisseur, Fabricant, EAN (European Article Numbering), Note et Garantie.</b>
			<br><br>
            <u>Tous les mots</u><br>
            'toto titi' => Chercher tous les articles contenant 'toto' <b>ET</b> 'titi'.<br>
            <u>N'importe quel mot</u><br>
            'toto titi' => Chercher tous les articles contenant 'toto' <b>OU</b> 'titi'.<br>
            <u>Phrase exacte</u><br>
            'toto titi' => Chercher tous les articles contenant 'toto titi'.<br><br>
            L'opérateur par défaut est <b>ET</b>.<br>
            Le moteur de recherche ne différencie pas les accents et les majuscules.");
define('RESULTAT_DE_LA_RECHERCHE','R&eacute;sultat de la recherche');
define('RECHERCHE_NON_DEFINIE','<b>Recherche non d&eacute;finie !</b><br>Veuillez recommencer.');
define('AUCUN_RESULTAT','Aucun r&eacute;sultat n\'a &eacute;t&eacute; trouv&eacute; pour cette recherche');
define('RESULTAT','R&eacute;sultat');
// top10.php
define('NON_DISPONIBLE','Non disponible');
define('GRIS','Gris');
define('ROSE','Rose');
define('NOIR','Noir');
define('JAUNE','Jaune');
define('BLANC','Blanc');
define('BLEU','Bleu');
define('PERSO','Perso');
define('CATEGORIE','Cat&eacute;gorie');
define('ACHETER','Acheter');
define('FOURNISSEUR','Fournisseur');
define('COMPAGNIE','Un produit');
define('COMPAGNIE2','Entreprise');
define('LES_PLUS_VISITES','Les plus populaires');
define('PRODUITS_DANS_CETTE_CATEGORIE','produits dans cette cat&eacute;gories');
define('VOIR_ARTICLES_DE_LA_CATEGOIRE','Voir cat&eacute;gorie');
define('DESCRIPTION','Description');
define('DESCRIPTIONMAJ',"DESCRIPTION");
define('VOIR_OPTIONS','Choisir options');
define('PRODUIT_OPTIONS','Déclinaisons');
define('LIRE_CONDITIONS','Lire conditions');
define('FREE_SHIPPING_COUNTRIES','Pays profitant de la gratuit&eacute;');
define('PRODUITS_AFFILIES','NOS CLIENTS ONT AUSSI ACHETÉ');
// selecteer_betaling.php
define('PAIEMENT','Paiement');
define('PAYPAL_EST_UN_COMPTE_BANCAIRE','Paypal est un compte bancaire virtuel le plus utilis&eacute; qui permet d\'effectuer des <b>paiements par email <u>OU</u> par carte bancaire</b>.<br>Le paiement avec Paypal est enti&eacute;rement s&eacute;curis&eacute;.');

define('UNE_FOIS_VOTRE_PAIEMENT_CONFIRME',"
(1)&nbsp;Après confirmation de votre paiement, vous recevrez un email avec votre num&eacute;ro d'ID commande (NIC), votre num&eacute;ro client ainsi que l'adresse URL de votre <b>interface de suivi client</b>(*) accessible avec votre NIC.<br>Votre commande sera immédiatement pris en compte pour être expédiée.<br><br>
(2)&nbsp;Après avoir sélectionné votre mode de paiement, nous enregistrons votre commande et vous recevrez un email avec votre num&eacute;ro d'ID commande (NIC), votre num&eacute;ro client ainsi que l'adresse URL de votre <b>interface de suivi client</b>(*) accessible avec votre NIC.<br>Aprés réception de votre paiement, votre commande sera pris en compte pour être expédiée.<br><br>
 (*)&nbsp;<u>L'interface de suivi client vous permet</u>:<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De confirmer votre mode de paiement (**).<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De lire nos informations bancaire ou postale (**).<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De v&eacute;rifier le statut de votre paiement (re&ccedil;u | non re&ccedil;u).<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De v&eacute;rifier le statut de votre commande (exp&eacute;di&eacute;e oui | non).<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De t&eacute;l&eacute;charger votre commande.<br>
 <img src=\"im/fleche_right.gif\">&nbsp;D'imprimer ou/et recevoir votre facture.<br>
 <img src=\"im/fleche_right.gif\">&nbsp;De vous abonner/désabonner à notre Newsletter.<br><br>
 (**)&nbsp;Si paiement par ch&eacute;que, virement bancaire ou mandat postal.");
define('LE_PAIEMENT_PAR_CARTE_DE_CREDIT',"Le paiement par carte de cr&eacute;dit est enti&eacute;rement s&eacute;curis&eacute; et est trait&eacute; sur le serveur de paiement s&eacute;curis&eacute;");

define('LE_MONTANT_ENVOYE_DOIT_ETRE',"Le montant envoy&eacute; doit &ecirc;tre en");
define('LE_MONTANT_ENVOYE_DOIT_ETRE2',"Montant &agrave; envoyer:");
                  
define('SELECTIONNER_MODE_DE_PAIEMENT','S&eacute;lectionner mode de paiement');
define('CHEQUE_BANCAIRE','Chèque bancaire');
define('TRAITE_BANCAIRE','Traite bancaire');
define('MANDAT_POSTAL','Mandat postal');
define('VIREMENT_BANCAIRE','Virement bancaire');
define('MODE_DE_PAIEMENT','Sélectionner un mode de paiement');
define('CONTRE_REMBOURSEMENT','Contre remboursement');
define('PAIEMENT_AVEC_CO','Paiement par contre remboursement');

define('VOUS_AVEZ_CHOISI',"Aussit&ocirc;t la confirmation de votre paiement, un email sera envoy&eacute; &agrave;");
define('VOUS_AVEZ_CHOISI2',"Veuillez en prendre connaissance afin de poursuivre votre commande.<br><br>&bull;&nbsp;<i>Cliquez sur le bouton ci-dessous pour effectuer votre paiement.</i>");       
define('VOUS_AVEZ_CHOISIA',"Vous avez choisi le paiement par contre remboursement.<br>Aussit&ocirc;t apr&eacute;s la confirmation de votre mode de paiement, votre commande sera enregistrée et un email sera envoy&eacute; &agrave;");
define('VOUS_AVEZ_CHOISI2A',"Veuillez en prendre connaissance afin de poursuivre votre commande.<br><br>
					<b>Votre commande sera exp&eacute;di&eacute;e apr&eacute;s v&eacute;rification par contact t&eacute;l&eacute;phonique au num&eacute;ro demand&eacute; ci-dessous</b>.<br><br>&bull;&nbsp;<i>Cliquez sur le bouton ci-dessous pour confirmer votre mode de paiement et enregistrer votre commande.</i>");
// page bevestigd.php
define('CONFIRMATION_DE_PAIEMENT','Confirmation de paiement');
define('VOTRE_COMMANDE_EST_DEJA_ENREGISTREE',"Votre commande est d&eacute;j&agrave; enregistr&eacute;e.<br><br>
       - Pour sélectionner un autre mode de paiement, cliquez <a href='payment.php'><b>ICI</b></a>.<br>
       - Pour modifier votre commande, veuillez communiquer avec nous par <a href=\"mailto:$mailOrder\">email</a> dans les plus brefs d&eacute;lais.<br>
       Afin d'identifier votre commande, n'oubliez pas de préciser votre Numéro ID Client (NIC).<br><br>
       Merci de votre compr&eacute;hension.");
define('RETOUR_BOUTIQUE','Retour boutique');


define('UN_EMAIL_A_ETE_ENVOYE1',"Un email a &eacute;t&eacute; envoy&eacute; &agrave;");
define('UN_EMAIL_A_ETE_ENVOYE2',"Vous avez s&eacute;lectionn&eacute; le mode de paiement par");

define('VOTRE_COMMANDE_A_ETE_ENREGISTREE',"Veuillez vous rendre sur votre <b>interface de suivi client</b> indiquée dans l'email qui vient de vous être envoyé.<br><br>Votre commande a &eacute;t&eacute; enregistr&eacute;e. Aussit&ocirc;t la confirmation de votre paiement, votre commande sera exp&eacute;di&eacute;e.<br><br>Merci de votre compréhension.");
define('VOTRE_COMMANDE_A_ETE_ENREGISTREE_CONTRE',"Votre commande a &eacute;t&eacute; enregistr&eacute;e.<br>Après vérification, Aussit&ocirc;t que votre commande sera confirm&eacute; sur votre interface de suivi client, votre commande sera exp&eacute;di&eacute;e.");

define('AFIN_D_IDENTIFIER_VOTRE_COMMANDE',"Afin d'identifier votre commande, n'oubliez pas de préciser votre Numéro ID Commande (NIC) et votre numéro client au dos du chèque ou joindre cet email avec votre paiement.");
define('AFIN_D_IDENTIFIER_VOTRE_COMMANDE_TRAITE',"Afin d'identifier votre commande, n'oubliez pas de joindre votre Numéro ID Commande (NIC) et votre numéro client ou joindre une copie de cet email.");
define('POUR_TOUTE_MODIFICATION',"Pour toute modification de votre commande, veuillez communiquer avec nous par email ".$mailOrder." dans les plus brefs délais.\r\nToujours indiquer votre numéro client et votre NIC.");
define('AFIN_D_IDENTIFIER_VOTRE_COMMANDE_MANDAT',"Afin d'identifier votre commande, n'oubliez pas de préciser votre Numéro ID Commande (NIC) et votre Numéro Client sur votre mandat postal.<br>Les frais relatifs au paiement sont à votre charge.");

define('AFIN_D_IDENTIFIER_VOTRE_COMMANDE_WESTERN',"Faire parvenir le paiement à: ".strtoupper($western)."\r\n
IMPORTANT:\r\n Afin que $western puisse retirer le montant demandé, veuillez envoyer à ".$mailOrder." les informations suivantes:\r\n1 - Le numéro MTCN (sous la forme de 10 chiffres).\r\n2 - Le nom et prénom de la personne ou compagnie qui fait l'envoi tel que écrit sur le bordereau.\r\n3 - La ville et pays d'expédition.\r\n4 - Toutes les informations qui vous semblerons nécessaires et pertinentes.\r\n
Les frais relatifs au paiement sont à votre charge.");

define('AUSSITOT_CONFIRMATION_DU_PAIEMENT','Aussitôt confirmation du paiement votre commande sera expédiée.');
define('AUSSITOT_CONFIRMATION_DU_PAIEMENTA','Votre commande va être expediée dans les plus bref délais.');
define('OUBLIEZ_PAS_DE_DEMANDER',"Noubliez pas de demander à votre banque d'indiquer votre Numéro ID Commande (NIC) et votre Numéro Client en référence sur le bordereaux de virement.");
define('FAITES_NOUS_PARVENIR_PAR_EMAIL',"2 - Faites nous parvenir par email ".$mailOrder." l'avis d'émission de virement tamponné par votre banque en y indiquant distinctement votre Numéro ID Commande (NIC) et votre Numéro Client.\r\n");
define('TELEPHONE','Téléphone');
define('DATE','Date');
define('VOUS_VENEZ_DE_FAIRE_UNE_COMMANDE','Vous venez de faire une commande et vous avez sélectionné le mode de paiement par');
define('VOTRE_COMMANDE_A_ETE_ENREGISTREE_MAIL','Votre commande a été enregistrée.');
define('POUR_SUIVRE_VOTRE_COMMANDE','Pour trouver nos informations bancaire ou postale et suivre votre commande, veuillez vous rendre sur votre interface de suivi client.');
define('POUR_SUIVRE_VOTRE_COMMANDE_IDENTIFIEZ_VOUS',"Votre interface de suivi client est aussi accessible sur http://".$www2.$domaineFull." via \"Votre compte\" avec votre Numéro Client et adresse email ou via \"Suivi commande\" après avoir saisi votre Numéro ID Commande (NIC).");
define('POUR_SUIVRE_VOTRE_COMMANDEA','Pour suivre votre commande, veuillez vous rendre sur votre interface de suivi client.');
define('RAPPEL','RAPPEL');
define('NUMERO_DE_CLIENT','Numéro client');
define('INTERFACE_DE_SUIVIT_CLIENT','Interface de suivi client');
define('POUR_TOUTE_INFORMATION',"Pour toute information, n'hésitez pas à communiquer avec nous.");
define('LE_SERVICE_CLIENT','Le service client');
define('CODE_REMISE_ACTIVE','Code de réduction activ&eacute;!');
define('CODE_REMISE_UTILISE','Code de réduction d&eacute;j&agrave; utilis&eacute;!');
define('TELEPHONE_DE_CONTACT','Votre t&eacute;l&eacute;phone de contact pour v&eacute;rification');
define('CONFIRMER_MODE_DE_PAIEMENT','Confirmer mode de paiement');
$titreOrder = $titre_order_1;

// page menu_inschrijven.php
define('ENREGISTRER','Enregistrer');
define('ADRESSE_EMAIL_INVALIDE','Adresse email invalide !');
define('COMPTE_NON_ACTIVE','Compte non activ&eacute; !');
define('USAGER_DEJA_ABONNE','Usager d&eacute;j&agrave; abonn&eacute; !');
define('ACTIVATION_COMPTE_NEWSLETTER','Activation compte newsletter');
define('SUJET','Sujet:');
define('EMAIL_DEJA_ENREGISTRE','Email d&eacute;j&agrave; enregistr&eacute; à notre newsletter!');
define('POUR_RECEVOIR_LA_LETTRE','Pour recevoir la lettre d\'information, votre compte doit être activé.');
define('POUR_RECEVOIR_LA_LETTRE2','Votre compte a été activé et vous êtes abonné à notre lettre d\'information.');
define('A_L_ADRESSE_CI_DESSOUS','À l\'adresse ci-dessous, vous pourrez vous abonner et vous désabonner.');
define('A_L_ADRESSE_CI_DESSOUS2','À l\'adresse ci-dessous, vous pourrez vous désabonner si vous ne souhaitez plus recevoir notre lettre d\information.');
define('VOTRE_NUMERO_ACTIVATION','Votre numéro d\'activation/Désactivation');
define('VEUILLEZ_ENTRER_UN_EMAIL','Veuillez entrer un email !');
define('VEUILLEZ_VERIFIER_VOTRE_EMAIL',"Un e-mail contenant un URL d'activation de votre compte a été envoyé à l'adresse e-mail que vous avez fournie.
                                        <br>Merci de suivre les instructions de ce mail pour activer votre compte.");
define('MES_PRODUITS','Quelques articles...');

define('VEUILLEZ_SAISIR','Veuillez saisir votre téléphone de contact!');
define('VOTRE_COMMANDE_EST_ENREGISTREE_ET',"Votre commande est enregistrée et votre mode de paiement est confirmé. Merci.");
DEFINE("MOT_DE_PASSE_OUBLIE","Mot de passe oublié, cliquer <a href='wachtwoord_vergeten.php' target='blank'>ICI</a>.");
DEFINE("RECUPERER_MOT_DE_PASSE","Récupération mot de passe");
DEFINE("NO_MOT_DE_PASSE","Aucun numéro client correspond à cette adresse email !");
DEFINE("MOT_DE_PASSE_ENVOYE","Un email a été envoyé à");

// Module identifiez-vous
define('FERMER_LE_COMPTE','Fermer votre compte');
define('VOUS_ETES_IDENTIFIE','Vous êtes identifié.');
define('IDENTIFIEZ_VOUS','Votre compte');

// Page mijn_account.php
define('YOUR_ACCOUNT_NOTE','<b>1</b> - Si le montant hors taxe de votre prochaine commande est inférieure à <b>');
define('YOUR_ACCOUNT_NOTE_2',$symbolDevise.'</b>, la remise s\'appliquera sur le montant de la commande.<br> 
       <b>2</b> - Les points de fidélité non utilisés seront reportés à la commande suivante<br>
		 <b>3</b> - La remise ne s\'applique pas sur les frais de livraison.');
define('VOUS_VENEZ_DE_FERMER_VOTRE_COMPTE','Vous venez de fermer votre compte !');
define('AUCUNE_COMMANDE','Numéro client et/ou adresse E-mail non valide !');
define('AUCUNE_COMMANDE_VIDE','Veuillez saisir votre numéro client !');
define('NOM_DE_CADDIE_UTILISE','<b>Nom de panier déjà utilisé !</b><br> Veuillez recommencer.');
define('VOTRE_CADDIE_EST_VIDE','<b>Votre panier est vide!</b><br>Votre panier doit contenir, au moins 1 article !');
define('VOUS_SOUHAITE_UNE','vous souhaite une Cordialement.');
define('VOTRE_NUMERO_CLIENT_EST','Votre numéro client est');
define('VOUS_AVEZ_PASSE','Vous avez déja passé');
define('COMMANDE','commande');
define('SUR_ECOM_POUR_UN_MONTANT_DE','sur '.$store_name.' pour un montant total de');
define('VOTRE_REMISE','VOS POINTS DE FIDÉLITÉ');
define('LIRE_NOTE','Lire note');
define('VOUS_AVEZ_OBTENU','vous avez obtenu');
define('POINTS_QUI_VOUS_DONNE_DROIT','points de fidélité.');
define('DE_REDUCTION_SUR',' sur votre prochaine commande.');
define('POINTS_ACCU','Points accumulés');
define('POINTS_UT','Points utilisés');
define('POINTS_REST','Points restants');
define('REMISE_SUR_VOTRE_PROCHAINE_COMMANDE','Remise sur votre prochaine commande');
define('VOS_FACTURES','VOS FACTURES');
define('FACTURE','Facture');
define('VOS_CADDIES','VOS CADDIES');
define('EN_TANT_QUE_CLIENT','En tant que client enregistré, les paniers sauvegardés ne sont pas limités dans le temps.');
define('CLIQUEZ_SUR_LE_CADDIE','Cliquez sur le panier ci-dessous pour le récupérer, le supprimer ou l\'actualiser.');
define('SET_TO_1','Toutes les quantités des articles récupérés seront automatiquement mis à 1.');
define('NBRE_DE_CADDIE','Nombre de panier enregistré');
define('VOUS_N_AVEZ_AUCUN_CADDIE','Vous n\'avez aucun panier enregistré.');
define('ENREGISTRER_UN_NOUVEAU_CADDIE','ENREGISTRER UN NOUVEAU PANIER');
define('SI_VOUS_N_AVEZ_PAS2',"Numéro de client perdu, cliquez ");
// Affiliation
define('AFFILIATION','Affiliation');
define('BANQUE','Banque');
define('AI_LU2','J\'ai lu les <a href="infos.php?info=12"><u>conditions</u></a> d\'affiliation');
define('DEVENIR_AFF',"S'inscrire");
define('CONDITIONS_AFF','Conditions d\'affiliation');
define('ICI','ICI');
define('APRES_VOUS_ETRE','Après vous être enregistré, un email vous sera envoyé avec votre numéro d\'affilation. Vous placerez ensuite le lien de votre choix sur votre site Web comme indiqué ');
define('POUR_ADHERER','Pour adh&eacute;rer à notre programme d\'affiliation, lire les <a href="infos.php?info=12"><u>conditions</u></a> et remplissez le formulaire ci-dessous...');
define('REMPLACER','<b><u>IMPORTANT</u></b>: Remplacer XXXXX par votre numéro d\'affilié envoy&eacute; par email.');

if($colorInter=="scss") {$linkIpos="ipos/index.php?lang=1&css=scss";}
if($colorInter=="scss2") {$linkIpos="ipos/index.php?lang=1&css=scss2";}
if($colorInter=="Gris-Grey") {$linkIpos="ipos/index.php?lang=1&css=grey";}
if($colorInter=="Rose-Pink") {$linkIpos="ipos/index.php?lang=1&css=pink";}
if($colorInter=="Noir-Black") {$linkIpos="ipos/index.php?lang=1&css=black";}
if($colorInter=="Jaune-Yellow") {$linkIpos="ipos/index.php?lang=1&css=yellow";}
if($colorInter=="Blanc-White") {$linkIpos="ipos/index.php?lang=1&css=white";}
if($colorInter=="Bleu-Blue") {$linkIpos="ipos/index.php?lang=1&css=blue";}
if($colorInter=="perso") {$linkIpos="ipos/index.php?lang=1&css=perso";}

define('NOUS_REMUNERONS',"Nous r&eacute;mun&eacute;rons nos affili&eacute;s &agrave; hauteur de <b>".$affiliateCom."%</b> sur les ventes générées sur <b>".$domaine."</b> par les visiteurs provenant de votre site.<br>
                        Pour cela il suffit d'intégrer un <b>lien</b>, une <b>bannière</b> ou <a href=\"".$linkIpos."\" target=\"_blank\"><u>cette boutique</u></a> (en <b>marque blanche</b>) sur votre site Web.<br>
                        <br>Une fois enregistré nous vous ferons parvenir par email votre numéro d'affilation et tous les codes HTML spécifiques qu'il vous suffira de coller sur votre site Web comme indiqué ");
define('ENREGISTRER_AU_PROGRAMME',"Vous venez de vous enregistrer au programme d'affiliation de");
define('VOTRE_NUMERO_AFFILIE_EST',"Votre numéro d'affiliation est");
define('VOTRE_PASS_EST',"Votre mot de passe est");
define('UN_LIEN','Un lien dans votre page web');
define('UNE_IMAGE','Une image');
define('NO_IMAGE',"Pas d'image");
define('UNE_BANNIERE','Une bannière');
define('CODE','CODE HTML');
define('A_INCLURE','&agrave; inclure dans votre site internet ou votre email');
define('VOUS_ETES_AFFILIE','VOUS ÊTES AFFILIÉ.');
define('VOUS_ETES_PAS_AFFILIE','VOUS N\'ÊTES PAS AFFILIÉ.');

define('CHEQUE_PAYABLE','Chèque bancaire payable à :');
define('PAYPAL_EMAIL','Email compte Paypal :');
define('TITULAIRE_DU_COMPTE','Titulaire du compte :');
define('VOUS_RECEVREZ_VOTRE_ARGENT','Vous recevrez votre argent par :');


define('SEUIL_DE_PAIEMENT','Seuil de paiement');
define('SEUIL_DE_PAIEMENT_A_ETE_DEPASSE', 'Le seuil de paiement a été dépassé.');
define('CE_MONTANT_VOUS_SERA_ENVOYE','Ce montant vous sera envoyé trés prochainement.');
define('NON_ATTEINT','non atteint.');
define('COMPTE_AFF','COMPTE AFFILIÉ');
define('SUR_VOTRE_COMPTE','sur votre compte affilié numéro');
define('DETAILS_COMPTE','Détail de votre rémunération');
define('COMMISSION','Commission');
define('PAYE','Payé');
define('MONTANT_HT','Montant facture HT');
define('MONTANT_COM','Montant commission');
define('TOTAL_PAYE','Total payé');
define('MONTANT_DU','Montant dû');
define('EN_DATE_AUCUNE','En date d\'aujourd\'hui, aucune rémunération n\'a été enregistrée sur votre compte.');
define('PLACER_UN_LIEN','Placer le lien de votre choix sur votre site Web ou email comme indiqué');
define('AUCUN_COMPTE_AFF','Compte affilié et/ou mot de passe non valide !');
define('COMMANDE_MINIMUM','Commande minimum');
define('AVANT_LIVRAISON','Avant réduction et livraison');
define('VOIR_FACTURE','Afficher');
define('MES_LIENS','Quelques liens...');

define('INTERFACEW','Interface');
define('LANGUES','Langues');
define('NEWSLETTER','Newsletter');
define('CONVERTISSEUR','Convertisseur');
define('MESSAGE1','Sélectionnez la couleur de l\'interface de notre boutique.');
define('MESSAGE2','Sélectionnez votre langue en cliquant sur un drapeau ci-dessous.');
define('MESSAGE3','Convertisseur '.$devise.' -> Votre monnaie.');
define('MESSAGE4','[Votre texte ici] - Message');
define('MESSAGE5','Rendez-vous rapidement vers les articles de votre choix.');
define('MESSAGE6','Abonnez-vous à notre newsletter.<br>Recevez votre code d\'activation en entrant votre email dans le champ ci-dessous.');
define('MESSAGE7','Votre panier');
define('MESSAGE8','Vous pouvez désormais payer votre commande en ligne en saisissant votre numéro de devis dans le module ci-dessous.');

define('NO_TVA','No. de TVA Intracommunautaire');
define('LAISSER_VIDE','Laissez vide si pas de numéro');
define('PERMET','Permet de récolter et de transmettre, via une ligne sécurisée SSL, les informations de paiement par Carte Bancaire.<br>
                Les informations transmises seront traitées manuellement à partir de notre terminal de paiement de carte de crédit.');
define('PERMET2','Permet de récolter et de transmettre, via une ligne sécurisée SSL cryptant en <b>2048 bits</b>, les informations de paiement par Carte Bancaire.<br>
                Les informations transmises seront décryptées et traitées manuellement à partir de notre terminal de paiement de carte de crédit.');
define('VOUS_AVEZ_CHOISI_LIAISON','Vous avez choisi Liaison-SSL pour effectuer votre paiement.<br>
                  <br><u>Votre paiement sera traité manuellement sur notre terminal de paiement.</u><br><br>
                  Aussit&ocirc;t la confirmation de votre paiement, un email sera envoy&eacute; &agrave; ');
define('VEUILLEZ_EN_PRENDRE_CONNAISSANCE','Veuillez en prendre connaissance afin de poursuivre votre commande.<br>
                  // <i>Cliquez sur le bouton ci-dessous pour effectuer votre paiement.</i>');
define('EN_VENTE','en vente');
define('DISPO','Disponibilité');
define('EN_COMMANDE','Sur commande');
define('ARTICLE_EN_COMMANDE','Commande en cours...');
define('BUY_NOW',"<b>Commandez maintenant !</b><br>... et nous vous enverrons l'article aussitôt qu'il sera disponible.");
define('BUYED1',"<b>Cet article est dans votre panier !</b><br>Nous vous l'enverrons aussitôt qu'il sera disponible.");
define('AUTRES_OPTIONS',"<i>(Autres options disponibles)</i>");
define('ENVOYER_A_UN_AMI','Envoyer à un ami');
define('ENVOYER_CETTE_UN_AMI','Envoyer cette page à un ami');
define('VOTRE_NOM','Votre nom');
define('VOTRE_EMAIL','Votre email');
define('EMAIL_AMI','E-mail de votre ami');
define('MESSAGE_DE','Message de');
define('CET_ARTICLE_EST_SUSCEPTIBLE_DE_VOUS_INTERESSER','Cet article est susceptible de vous intéresser.');
define('VOUS_INVITE_A_VOUS_RENDRE','vous invite à vous rendre à l\'adresse suivante:');
define('LE_MESSAGE_SUIVANT_A_ETE_AJOUTE','Le message suivant a été ajouté:');
define('PAGE_ENVOYE','Cette page a été envoyée');
define('DESOLE','Désolé, cet article est épuisé.');
define('ABONNE_NEWS',"<i>Recevez des offres attractives et des informations destinées exclusivement à nos membres.</i><br>S'abonner à la Newsletter ?");
define('PAIEMENT_SECURISE','Paiement sécurisé');
define('COEUR','Coup de coeur !');
define('POUR_CONNAITRE','<p>Pour connaître le <b>montant total de votre commande</b>, selectionnez le lieu de livraison.</p>');
define('SAISISSEZ_ICI','<p>Saisissez ici le Code de votre <b>Offre Spéciale</b> afin de bénéficier de votre remise - <span class="fontrouge"> <b>Offre non cumulable avec toute autre promotion en cours</b>.</span></p>');
define('PLUS_INFOS',"En savoir plus...");
define('DATE_FIN_PROMO',"Date fin promo");
define('VENTES_FLASH',"Ventes flash");
define('VOTRE_PAIEMENT_A','Votre paiement a été confirmé.<br>
                            <b>'.$store_company.'</b> vous remercie pour votre commande.<br><br>
                            <b>Un email vous a été envoyé</b>.
                            <br>Veuillez en prendre connaissance afin de suivre votre commande et avoir votre facture.');
define('ACTVITE',"Activité");
define('POSTE',"Poste");
define('NO_ORDER',"Aucune commande dont le paiement a été confirmé, a été enregistrée!");
define('PARTENAIRES','Partenaires');
define('DOMAINE_ACTIVITY',"Domaine d'activité");
define('DEVIS','Devis');
define('DEMANDE_DEVIS','Demander un devis');
define('DEVIS_TOP',"Merci de renseigner le formulaire suivant, nous vous contacterons dans les<br>
                meilleurs d&eacute;lais pour faire le point sur votre projet.<br>
                (Les champs suivis d'une astérisque <span class=\"fontrouge\">*</span> sont obligatoires)");
define('DEVIS_BOTTOM',"<b>IMPORTANT :</b><br>
            Nous ne donnerons pas de suite à votre demande si le formulaire est incomplet.<br>
            Un contact humain est nécessaire pour &eacute;tablir un devis r&eacute;aliste, aussi, veuillez pr&eacute;ciser votre nom, votre adresse postale et votre numéro de t&eacute;l&eacute;phone afin de vous contacter.");
define('ENVOYER_FORMULAIRE','Envoyer le formulaire');
define('ENREGISTRER_AU_DEVIS',"Vous venez de demander un devis sur");
define('VOTRE_NUMERO_DEVIS_EST',"Votre numéro de devis est");
define('VOTRE_DEMANDE','Votre demande sera trait&eacute;e dans les plus brefs d&eacute;lais.');
define('VOTRE_DEMANDE_EMAIL','Votre demande sera traité dans les plus brefs délais.');
define('REFERENCES','RÉFÉRENCES');
define('DATEEXP',"Date d'expiration");
define('A3',"À");
define('INTRO1',"Madame, Monsieur,");
define('INTRO2',"Nous vous remercions de nous avoir consulté et vous prions de trouver ci-dessous, notre proposition de devis conformément à votre demande.");
define('NUMERO_DEVIS',"Numéro devis");
define('NO_DEVIS_FOUND',"Aucun devis trouv&eacute;.<br>Veuillez recommencer!");
define('CLOSE_DEVIS',"Retour à la boutique");
define('ACTIVER_DEVIS',"Activer devis");
define('NOS_SELECTIONS',"Nos s&eacute;lections");
define('LA_BOUTIQUE',"LA BOUTIQUE");

define('CHEQUE_CADEAU',"CH&Egrave;QUE CADEAU");
define('CHEQUE_CADEAU_MIN',"Chèque cadeau");
define('ACHETER_C',"ACHETER UN CHÈQUE CADEAU");
define('CONTROLE_CHEQUE',"MON CHÈQUE CADEAU");
define('FAITE_PLAISIR',"<span class=\"fontrouge\"><b>Fa&icirc;tes plaisir sans vous tromper !</b></span>
                        <br><br>
                        Le ch&egrave;que cadeau est une <b>ID&Eacute;E CADEAU</b> originale dont vous fixez librement le montant.<br>
                        Le bénéficiaire l'utilise à son gré sur tous les produits et les services présentés sur ".$store_name.", y compris sur les promotions.<br>
                        La valeur du Chèque cadeau sera déduite sur le montant total de la commande.<br><br>
                        &bull;&nbsp;<b>Si le montant de votre commande est supérieur à la valeur du chèque cadeau</b>, la totalité de la valeur de votre chèque cadeau sera déduite du montant total de votre commande. Continuer le processus de paiement pour payer le montant restant.<br><br>
                        &bull;&nbsp;<b>Si le montant de votre commande est égal à la valeur du chèque cadeau</b>, la totalité du montant de votre commande est payée avec le chèque cadeau.<br><br>
                        &bull;&nbsp;<b>Si le montant de votre commande est inférieur à la valeur du chèque cadeau</b>, la valeur du chèque cadeau NE SERA PAS DÉDUIT du montant de votre commande.<br><br>
                        &bull;&nbsp;Un chèque cadeau est valide 1 an, non cumulable, non-remboursable, non échangeable et n'est utilisable qu'une seule fois dans sa totalité, uniquement sur http://".$www2.$domaineFull.".<br>
                        &bull;&nbsp;L'achat d'un chèque cadeau n'est soumit à aucune remise.<br>
                        &bull;&nbsp;".$store_company." ne saurait être tenu responsable de la perte ou du vol du chèque cadeau ou du code qui y est attaché.<br>
                        &bull;&nbsp;Valeur minimum d'un chèque cadeau : <b>".sprintf("%0.2f",$seuilGc)."&nbsp;".$symbolDevise."</b><br>
                        ");
define('ENTRER_LE_MONTANT',"Valeur");
DEFINE("CHAMPS_VIDE","- Champs vide");
DEFINE("NOT_NUMBER","ou champs non numérique:");
DEFINE("NUMERO_CHEQUE","Numéro chèque cadeau");
define('SAISISSEZ_ICI2','<p>Saisissez ici le Code de votre <b>chèque cadeau</b> afin de bénéficier de sa déduction sur le montant de votre commande.</p>');
define('CADEAU_ACTIVE','Chèque cadeau activ&eacute;!');
define('CADEAU_UTILISE','Chèque cadeau d&eacute;j&agrave; utilis&eacute;!');
define('CADEAU_ERRONE','Code du chèque cadeau erron&eacute; !');
define('CADEAU_ERRONE2','Code du chèque cadeau non valid&eacute; !');
define('CADEAU','La déduction sur votre commande est de');
define('MONTANT_PAS_SUFFISANT','<p align="center">Le montant de la commande est inférieur à la valeur du Chèque cadeau !</p>');
define('NON_CUMULABLE','<p align="center">Les codes de réduction ne sont pas cumulable avec les Chèques cadeaux.</p>');
define('IS_DESACTIVATE',"Chèque cadeau désactivé.");
define('DESACTIVATE',"Désactiver le code du Chèque cadeau.");
define('DESACTIVATE_COUPON',"Désactiver le code de réduction");
define('IS_DESACTIVATE_COUPON',"Le code de réduction est désactivé.");
define('VALEUR_MIN',"Valeur minimum d'un chèque cadeau : ".sprintf("%0.2f",$seuilGc)." ".$symbolDevise);
define('PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE_FLASH',"Vente flash terminée !<br><br>Revenez nous voir trés bientôt...");
define('CONTROLER',"Contrôler");
define('NON_UTILISE',"NON UTILISÉ");
define('UTILISE',"UTILISÉ");
define('EXPIRE',"Expiré !");
define('CADEAU_EXPIRE_NON_UTILISE',"Ce Chèque cadeau a expiré et n'a pas été utilisé !");
define('DATE_ACTIVATION',"Date d'activation");
define('STATUT',"Statut");
define('GC_NON_ENREGISTRE',"Chèque cadeau non enregistré !<br>Veuillez recommencer.");
define('VOS_DEVIS',"Vos devis");
define('SANS_LIVRAISON',"S&eacute;lectionner 'SANS LIVRAISON' si retrait en magasin");
define('SUIVANT',"Suivant lieu de livraison");
define('DONT',"Dont");
define('AJOUTER_FAVORIS',"Ajouter à vos favoris");
define('POINTS_FIDELITE',"Points de fidélité");
define('SUIVI_COMMANDE',"Suivi commande");
define('TRAITE',"Trait&eacute;");
define('VOTRE_PRIX',"Votre&nbsp;prix");
define('UPDATE',"Mettre à jour");
define('VOS_INFOS',"VOS INFOS");
DEFINE("AETEMISAJOUR","a été mis à jour");
DEFINE("CECOMPTE","Ce compte client n'existe pas!");
DEFINE("ENREG","Enregistrez-vous");
define('ACHAT_TTC',"taxes incluses");
define('ACHAT_HT',"hors taxe");
define('ACHAT_ST',"sans taxes");
define('PAS_INCLU',"Les frais de livraison ne sont pas inclus</b>.<br>Ils seront calculés puis ajoutés aussitôt le lieu d'expédition sélectionné.");
define('PEND_GRAT',"Pendant une période limitée, **<b><span class=\"fontrouge\"> LIVRAISON GRATUITE </span></b>**");
define('PEND_REM',"Pendant une période limitée, **<b><span class=\"fontrouge\"> REMISE </span></b>** de");
define("LIV", "Livraison");
define("CONF", "Confirmation");
define("CLIQUER_SUR", "Cliquer sur l'URL ci-dessous pour voir les codes html à coller sur votre site web");
define("PREM", "Première commande sur");
define("IDVOUS", "IDENTIFIEZ-VOUS");

define("EMAIL_ENR", "<b>1 Compte Client = 1 E-mail</b><br><br>Email correspondant à un compte client déjà enregistré: ");
define("TVA_ENR", "<b>Numéro de TVA Intracommunautaire déjà enregistré !</b>");
define("VOS_INFOS_ONT_ETE_ENVOYE", "<b>Votre compte a été crée avec succès!</b><br><br>Les accés à votre compte ont été envoyés à");
define("APRES_VERIFICATION", "<b>Les information de votre compte ont été envoyées!</b><br>Après vérification vous recevrez un email avec l'accés unique à votre compte client.<br>Merci !");
define("UN_OU_DES_CHAMPS_NE_SONT_PAS_VALIDE", "<b>Un ou des champs obligatoire ne sont pas valide.</b><br>Recommencez!");
define("CREER_VOTRE_COMPTE", "CRÉER VOTRE COMPTE");
define("DEJA_CLIENT", "Je suis déjà enregistré...");
define("FIRST_ORDER", "<b>Prenez quelques minutes pour créer votre compte.</b><br><br>
                        Ouvrir un compte, c'est :<br>
                      &bull;&nbsp;Effectuer vos prochains achats en quelques clics.<br>
                      &bull;&nbsp;Suivre l'évolution et l'historique de vos commandes et/ou devis.<br>
                      &bull;&nbsp;Gérer vos caddies.<span style='FONT-SIZE:8px;'><sup>1</sup></span><br>
                      &bull;&nbsp;Profiter d'offres exclusives.<span style='FONT-SIZE:8px;'><sup>2</sup></span><br>
                      &bull;&nbsp;Modifier vos informations.<br>
                      &bull;&nbsp;Récupérer/Modifier/Payer vos commandes non finalisées.<br>
                      &bull;&nbsp;Vous abonner/désabonner à la newsletter.");
define("FIRST_ORDER_NOTE", "<i>(1) Sauvegarde et récupération de vos caddies pour un temps illimité.<br>
                  (2) Points de fidélités, remise individuelle en activant votre compte client.</i>");
define("NOUVEAUX_ACCES", "Veuillez prendre connaissance de vos nouveaux accés pour vous connecter à votre compte sur");

DEFINE("ACTIVATION_COMPTE_PRO","ACTIVATION COMPTE CLIENT");
DEFINE("COMPTE_ACTIVE","Votre compte a été activé sur");
DEFINE("MERCI","Merci de votre interêt.");
DEFINE("RECUPERER_NO_CLIENT","Récupération de votre numéro de client");
DEFINE("NICO","Numéro d'Identification Commande");
define("VOUS_DEVEZ_VOUS_IDENTIFIER", "Vous devez maintenant vous identifier pour procéder au paiement.");
define("DEPUIS", "Depuis le");
define("HORS_LIV", "hors livraison");
define("NEWS_OK", "La newsletter a été mis à jour");
define("DESTINATION", "Destination");
define("POIDS_MAX_DEPASSE", "Le poids maximum de la commande a été dépassé.");
define("EXPED_IMPOSSIBLE", "Expédition impossible vers cette destination !<br>Veuillez nous contacter.");
define("COO_PRO", "Coordonnées professionnelles");
define("COO_PERSO", "Coordonnées personnelles");
define("CLIK_ICI", "Cliquer ICI pour vous rendre dans votre compte.");
define("CODE_NOT_VALID", "Code Anti-Spam invalide !");
define("CODE_ILLISIBLE", "Générez un nouveau code");
define("RECOPIER", "Recopiez le code anti-spam indiqué ci-dessous");
define("CODE_ANTISPAM", "Code anti-spam");
define("SITEMAP", "Plan du site");
define("EN_ATTENTE", "Commandes en attente de paiement et de facturation...");
define("NO_ORDER_NADA", "Aucune commande enregistrée.");
define("PAKING_COST", "Frais d'emballage");
define("A_ETE_SUPPRIME", "a été supprimé");
define("QUI_SOMMES_NOUS", "Qui sommes-nous ?");
define("ACHAT_EXPRESS", "Achat Express");
define("PAIEMENT_DIRECT", "Paiement direct");
define("VOTRE_COM", "Votre commission");
define('OFFERT','Offert');
define('EXTRA','EN EXTRA...');
define('SUP_FID',"En supprimant cette remise, vous avez la possibilité de <b>cumuler</b> vos points de fidélités.<br>
Le montant de cette remise sera <b>ajouté</b> aux points de fidélité de votre commande actuelle <b>et sera reporté</b> sur votre prochaine commande.<br><br>
---<br>
L'équipe ".$domaine);
define('ACTIVE_REMISE','Activer la remise');
define('DESACTIVE_REMISE','Désactiver la remise');
define('IPADRESS','Votre adresse IP');
define('SUIVANTTT','Suivant');
define('PRECEDENT','Précédent');

define('DIFFUSEZ_CONTENU','Flux RSS');
define('AJOUTE_BOUT','Nouveaux articles ajoutés dans la boutique');
define('NO_RSS','Aucun flux RSS trouvé.');
define('RSS_TEXT',"<b>RSS</b> est une technologie Internet qui vous permet de rester informé à distance et en direct de l'actualité de notre boutique.<br>");
define('RSS_TEXT2',"En vous abonnant gratuitement à un flux RSS comme ceux que nous proposons ci-dessus, vous recevrez les informations en temps réel dans votre lecteur/agrégateur de flux RSS sans que vous ayez à venir chercher l'information dans notre boutique.<br>
   <br>
   Dans la liste ci-dessus, choisissez le ou les fils de votre choix, copiez l'URL du service qui vous intéresse et collez l'URL dans l'application de votre choix.<br>
   Parmi les applications dites lecteur/agrégateur RSS, nous vous recommandons <a href='http://www.netvibes.com/' target='_blank'><u>Netvibes</u></a> ou <a href='http://www.google.com/reader' target='_blank'><u>Google Reader</u></a>.<br>
   <br>
   &bull;&nbsp;<b>Lecteurs/Agrégateurs RSS en ligne (accessible via tout ordinateur)</b> :<br>
   - <a href='http://www.netvibes.com/' target='_blank'><u>NetVibes</u></a><br>
   - <a href='http://reader.feedshow.com/home.fr.html' target='_blank'><u>FeedShow</u></a><br>
   - <a href='http://www.webwag.com/' target='_blank'><u>WebWag</u></a><br>
   - <a href='http://www.bloglines.com/' target='_blank'><u>Bloglines</u></a><br>
   - <a href='http://www.google.com/reader' target='_blank'><u>Google Reader</u></a><br>
   - <a href='http://fr.my.yahoo.com/' target='_blank'><u>MyYahoo</u></a><br>
   etc...<br><br>
   &bull;&nbsp;<b>Lecteurs/Agrégateurs RSS à intaller sur votre ordinateur</b> :<br> 
   - <a href='http://www.geste.fr/alertinfo/home.html' target='_blank'><u>AlertInfo</u></a><br>
   - <a href='http://www.feedreader.com' target='_blank'><u>FeedReader</u></a><br>
   et beaucoup d'autres applications référencées sur <a href='http://www.lamoooche.com/telechargement.php?apptyp=1&os=1' target='_blank'><u>lamoooche.com</u></a>...<br>
   <br>
   <i>NOTE : Pour l'utilisation de ces lecteurs/agrégateurs RSS, veuillez vous référer à la documentation et à l'aide en ligne de ces applications.</i>
   <br><br>");
define('NO_DOC','Aucun document trouvé !');
define('ACTU','Actualités de la boutique');
define('ACTU_MAJ','ACTUALITÉS DE LA BOUTIQUE');
define('ACTUS','Actualités');
DEFINE("CHEQUES_CADEAUX","Chèques cadeaux");
define('RECHERCHE_AVANCEE','Recherche&nbsp;avancée');
define("TOUS_LES_MOTS", "Tous les mots");
define("NIMPORTE_QUEL_MOT", "N'importe quel mot");
define("PHRASE_EXACTE", "Phrase exacte");
define('PAGE400_TITRE','Page introuvable !');
define('PAGE404',"<p align='left'>
<b>Que faire ?</b><br><i>Solutions avec soutien psychologique:</i><br><br>
&bull; <b>Si vous êtes un obsessionnels-compulsifs</b> : Actualisez cette page de façon répétée jusqu'à trouver la page.<br><br>
&bull; <b>Si vous êtes psycho-dépendant</b> : Demandez à un proche de chercher l'erreur dans l'URL pour vous.<br><br>
&bull; <b>Si vous êtes paranoiaque psychotique</b> : Nous savons où vous êtes et ce que vous voulez. Restez en ligne jusqu'à ce que nous localisions votre ordinateur.<br><br>
&bull; <b>Si vous êtes schizo-affectif</b> : Écoutez attentivement la petite voix qui vous donnera l'URL correcte.<br><br>
&bull; <b>Si vous êtes maniaco-dépressif</b> : Peu importe l'erreur dans URL.  Tout le monde s'en fout et personne ne vous donnera la solution.<br><br>
Si vous ne souffrez d'aucun des symptomes ci-dessus, cliquez sur n'importe quel lien pour sortir de cette page.</p>");
define('OUT_OF_STOCK','Épuisé');
define('ITEMS_OUT_OF_STOCK','Article épuisé');
define('NO_ITEM_FOUND','Aucun article trouvé.');
define('PREMIERE_PAGE','première page');
define('DERNIERE_PAGE','Dernière page');
define('VOIR_CERTIFICAT','Voir le certificat délivré par FIA-NET');
define('INFORMATIONS','Informations');
define('DIVERS','Divers');
define('NOTE','Note');
define('REMBOURSE','Remboursée');
define('PAGE_DE','de');
define('CLIQUEZ_SUR_LE_LOGO','&bull;&nbsp;Cliquez sur le logo ci-dessous pour initier votre paiement.');
define('D_ACHAT','d\'achat');
define('COMMANDE_REMBOURSE','Commande remboursée');
define('RECUP_COMMANDE_PANIER','Récupérer/Modifier cette commande dans le panier');
define('DIRECT_PAYMENT','Payer cette commande');
define('RESAISIR_EMAIL','Re-saisir votre<br>adresse E-mail');
define('VERIF_EMAIL','Vérifier votre adresse email');
define('PAIEMENT_CARTE','Paiement par carte bancaire');
define('PAIEMENT_AUTRE','Autre mode de paiement');
define('FINANCEMENT','Financement du paiement de votre commande');
define('FIANCEMENT_A_PARTIR_DE','Le financement de votre commande est disponible à partir de');
define('VOTRE_COMMANDE_EST_DE','Le montant de votre commande est de');
define('ART_AFFIL',"ARTICLES AFFILIÉS");
define('AUTRE_ADRESSE_DE_LIVRAISON','Autre adresse de livraison');
define('ETES_SUR_DE_SUPPRIMER_COMMANDE',"Êtes-vous de vouloir supprimer la commande");
define("LA_COMMANDE", "La commande");
define("A_ETE_SUPPRIMEE", "a été supprimée!");
define("SUPPRIMER_CETTE_COMMANDE", "Supprimer cette commande");
define("PENDANT_X_JOURS", "Pendant <b>".$pendingOrder." jours</b> après la date de votre commande, vous avez la possibilité de payer, de supprimer, de récupérer et/ou de modifier votre commande.<br>Passé ce délai, elle sera supprimée.");
define("COMMANDE_EFFECTUEE_IL_Y_A", "Commande effectuée il y a");

define("COMMANDE_SUPPRIMEE", "Commande supprimée");
define("COMMANDE_SUPPRIMEE_PAR", "Commande supprimée par le client via son compte client");
define("COMPTE_CLIENT", "Compte client");
define("EMAIL_CLIENT", "Email client");
define('TOUTES_CAT2','Par catégories');
define('EXP','Expédiée');
define('LES_PRIX_FIGURANT','<b><u>IMPORTANT</u></b>:<br>Les prix figurant sur ce site sont modifiables sans préavis et ne constituent en aucun cas un engagement de la part de');
define('CATALOG_CONTENTS','Contenu du catalogue le');
DEFINE("VOUS_AVEZ_ACHETEEEE","Vous avez acheté un Chèque cadeau sur");
define('VOUS_REMERCIE',"vous remercie pour votre commande.");
define('CI_DESSOUS',"Ci-dessous les informations concernant votre Chèque cadeau.");
define('MONTANT_DE',"Montant du Chèque cadeau");
define('URL_DE',"URL de votre Chèque cadeau pour impression");
define('DE_REDUCTION','de réduction');
define('VOTRE_REMISE_CLIENT','Votre remise client sur tous les articles de');
define("COMMANDE_EFFECTUEE", "Commande effectuée");
define('CETTE_COMMANDE','Cette commande');
define('A_DEJA_ETE_TRAITEE','a déjà été traitée par Moneybookers.<br>Veuillez sélectionner un autre mode de paiement<br><b>OU</b><br>Ajoutez votre commande dans le panier et effectuez votre paiement via le processus normal de la boutique.');
define('A_TITRE_INDICATF','Ce prix est donné à titre indicatif');
define('MB_EST_UN_COMPTE_BANCAIRE','MoneyBookers est un compte bancaire virtuel qui permet d\'effectuer des <b>paiements par email <u>OU</u> par carte bancaire</b>.<br>Le paiement avec MoneyBookers est enti&eacute;rement s&eacute;curis&eacute;.');
define('VOTRE_PAIEMENT_REFUSE',"Votre paiement a été refusé par le serveur sécurisé.<br>
                              Pour toute question concernant les raisons de ce refus, veuillez vérifier auprès de votre organisme de paiement ou votre institution financière.<br>
                              N'hésitez pas à repasser votre commande ou effectuer votre règlement via un autre mode de paiement.<br>
                            <b>".$store_company."</b> vous remercie de votre intérêt.<br>");
define('PAIEMENT_EMAIL','Paiement par email');
define('PAIEMENT_DE_CETTE_COMMANDE','Paiement de cette commande');
define('MODE_DE_LIVRAISON','Mode de livraison');
define('VEUILLEZ_CHOISIR_MODE_DE_LIVRAISON','Sélectionnez un mode de livraison');
define('SELECTIONNER_CE_MODE_LIVRAISON','Sélectionner ce mode de livraison');
define('PARAMS_LIVRAISON_NON_DEFINIS','Paramètres de livraison non définis.');
define('AVANT_REMISE','avant remise');
define('LEAVE_CHAMPS_EMPTY','Laissez ce champ vide pour valider le formulaire');
define("CHAMP_NOT_EMPY", "Le champ de formulaire doit rester vide !");
define('NON_VALIDE','non valide');
define('EN_ATTENTE_DE_VALIDATION','en attente de validation');
define('LA_DETAXE_DE_VOTRE_COMMANDE_EST_SOUMISE_A_LA_VERIFICATION','Le calcul de la TVA de votre commande est soumis à la vérification et la validation de votre numéro de TVA Intra-communautaire par notre service comptable.');
define('VOUS_AVEZ_DESACTIVE_JS','Javascript est désactivé sur votre navigateur');
define('VOUS_POUVEZ_POURSUIVRE_MAIS','<b><u>Vous pouvez poursuivre vos achats</u></b> mais certaines actions ne seront pas fonctionnelles.');
define('CLIQUEZ_ICI_POUR_ACTIVER_JS','Cliquez ici pour activer javascript sur votre navigateur.');
define('MODIFIER','Modifier');
define('SAVE_THIS_ORDER','Mettre à jour cette commande');
define('COMMANDE_MODIFIEE','Commande modifiée');
define('COMPTE_CLIENT_OUVERT_COMMANDE_RECUPEREE',"Compte client ouvert et commande récupérée");
define('VEUILLEZ_MODIFIER_LA_COMMANDE_PUIS_CLIQUEZ_CADDIE',"Veuillez modifier la commande en cours puis cliquez <a href='berekenen.php'><b><span style='color:#FFFFFF'>ICI</span></b></a> pour actualiser la commande");
define('CLIQUEZ_ICI_POUR_ANNULER',"Cliquez ici pour annuler cette opération.");
define('CLIQUEZ_BOUTON_UPDATE',"Cliquez sur le bouton '<b>Mettre à jour cette commande</b>' pour actualiser la commande");
define('AUCUNE_LIVRAISON_DANS_CE_PAYS',"Aucune livraison dans le pays sélectionné.");
define('POIDS_TOTAL_DEPASSE_NOS_CAPACITES',"Le poids total de votre commande dépasse nos capacités de livraison.");

define('DELAI_EXPEDITION',"Délai d'expédition");
define('ENTRE',"Entre");
define('ET',"et");
define('JOURS_OUVRES',"jour(s) ouvré(s).");
define('DATE_LIVRAISON',"Date de livraison");
define('ESTIMATION_FIN_COMMANDE',"Estimation en fin de commande.");
define('EN_TELECHARGEMENT_A_LA_CONFIRMATION_DU_PAIEMENT',"Aprés confirmation du paiement.");
define('SOUS',"Sous");
define('DELAI_LIVRAISON',"Délai de livraison");
define('ESTIMATION',"Estimation");
define('ENTRE_LE',"Entre le&nbsp;");
define('ET_LE',"et le&nbsp;");
define('INFOS_LIVRAISON',"INFOS LIVRAISON");
define("COO", "Coordonnées");
define("AJOUTER_COMMANDE_A_LA_BDD", "Ajouter cette commande dans la base de données");
define("AJOUTER_COMMANDE", "Ajouter commande");
define("LAST_VIEWED", "Derniers articles vus !");
define("TOUT_SUPPRIMER", "Tout supprimer");
define("GARANTIE", "Garantie");
define("VOIR_ALL_ACTUS", "Voir toutes les actualités de la boutique");
define("LES", "LES");
define('CLIQUEZ_SUR_LE_LOGO2','&bull;&nbsp;Cliquez sur le logo ci-dessous pour effectuer votre paiement.');
define("ETAT_COMMANDE", "État commande");
define("READY", "Prête");
define("EN_PREPARATION", "En préparation");
define("VOTRE_LIEN_SUR", "Ou transmettez le lien ci-dessous à vos connaissances");
define('UN_LIEN_DANS_EMAIL','Un lien envoyé par email');
define('COMPTE_A_JOUR','Le compte a été mis à jour.');

define('VOUS_REMUNERE_A_HAUTEUR_DE','vous rémunére à hauteur de');
define('SUR_LES_VENTES_GENEREES','sur les ventes générées par les clients que vous nous envoyez.');
define('CADDIE_HTM',"<p>
					Votre panier contient les articles que vous envisagez d&#146;acheter. <br>
					</p>
					<table width='100%' border='0' cellpadding='0' cellspacing='0'>
					<tr>
					<td><img src='im/lang1/use_caddie.gif'></td>
					<td valign='top'>
					<ul>
					<li>Pour ajouter des articles dans votre panier, veuillez naviguer jusqu'&agrave; l'article d&eacute;sir&eacute;. Saisissez la quantit&eacute; souhait&eacute;e puis appuyez sur <img src='im/cart_add.png'><br><br></li>
					<li>Grace aux boutons <img src='im/_plus.gif' width='5' height='5'>|<img src='im/_moins.gif' width='5' height='5'>|<img src='im/_quit.gif' width='5' height='5'> qui apparaitra en face de chaque article ajout&eacute; dans le panier, vous pourrez &agrave; tout moment modifier la quantit&eacute; de l'article d&eacute;sir&eacute; ou le supprimer du panier.<br><br></li>
					<li>Vous pouvez aussi, &agrave; tout moment, cliquer sur le bouton &quot;Votre panier&quot; pour faire les modifications n&eacute;c&eacute;ssaires et/ou voir le d&eacute;tail de vos achats.<br><br></li>
					<li>Cliquez sur &quot;Vider panier&quot; pour supprimer tous les articles du panier.<br><br></li>
					<li>Les options &quot;Enregistrer et r&eacute;cup&eacute;rer votre panier&quot; vous permet d'enregistrer et de r&eacute;cup&eacute;rer le contenu de votre panier.<br><br></li>
					<li>Un fois votre panier compl&eacute;t&eacute;, dans &quot;Votre panier&quot;, cliquer sur &quot;Paiement&quot; afin d'initier le processus de paiement.</li>
					</ul>
					</td>
					</tr>
					</table>");
define('COMMANDER_NO','Paiements désactivés.');
define("CE_COMPTE_EXISTE_DEJA", "Ce compte client existe déjà!");
define("VEUILLEZ_RECOMMENCER", "Veuillez recommencer.");
define("MODIFICATION_EFFECTUEE", "Modification effectuée.");
define("VOTRE_EMAIL_EST", "Votre email est");
define("CAR_MAX", "15 caractères maximum");
define("MODIFIER_COMPTE_CLIENT", "Modifier numéro de compte client");
DEFINE("FAX","Fax");
DEFINE("NEW_NUMERO","Votre nouveau numéro de compte client");
DEFINE("QUANTITY","Quantité");
DEFINE("PRODUIT_A_PRIX_DEGRESSIF","Produit à prix dégressif");
DEFINE("PRIX__UNITAIRE","Prix unitaire");
DEFINE("EXPEDI"," Expédition ");
DEFINE("DECLINAISON_EPUISEE","Cette déclinaison est épuisée");
DEFINE("DECLINAISON_NON_REPERTORIEE","Cette déclinaison n'est pas disponible. Faire une autre sélection.");
DEFINE("VOIR_STOCK","Voir tout le stock");
DEFINE("CACHER_STOCK","Cacher le stock");
DEFINE("VIE_STOCK_DES","Stock");
DEFINE("RECPERS","Coordonnées");
DEFINE("ECOTS","Eco-taxe");
DEFINE("SEFRIENNOM","Votre nom");
DEFINE("IPOST","Vendez les articles sur votre site web grace au systéme IPOS - Internet Point of sale");
define('COMMANDEMAIL','Votre commande');
DEFINE("NICEMAIL","NIC - Numéro Identification Commande");
DEFINE("ETOUM","et - ou");
DEFINE("PAYALERT","Selectionner un mode de paiement");
DEFINE("PAIEMENTOGONE","Paiement via Ogone");
DEFINE("MODELIVRI","Mode de livraison");
?>


 


