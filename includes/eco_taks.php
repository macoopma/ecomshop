<?php 
include('../configuratie/configuratie.php');
$_SESSION['userInterface'] = $colorInter;


$title = "Eco-tax";

?>
<html>
<head>
<META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="author" content="<?php print $auteur;?>">
<meta name="generator" content="PsPad">
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title><?php print $title." | ".$store_name; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="shortcut icon" href="http://<?php print $www2.$domaineFull;?>/favicon.ico" type="image/x-icon">
<?php
         if(!empty($_SESSION['userInterface'])) {
                   print "<link rel='stylesheet' href='../css/".$_SESSION['userInterface'].".css' type='text/css'>";
         }
         else {
               if($activerCouleurPerso=="oui") {
                  print "<link rel='stylesheet' href='../css/perso.css' type='text/css'>";
               }
               else {
                  print "<link rel='stylesheet' href='../css/".$colorInter.".css' type='text/css'>";
               }
         }
?>
<?php include('pagina_f5_js.inc');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="10">
  <tr> 
    <td valign="top"> <div align="center"> 
        <table width="350" border="0" cellpadding="5" cellspacing="0" bordercolor="#000000" bgcolor="#CCCCCC">
          <tr> 
            <td><div align="center"><strong>L'Eco-participation qu&#8217;est ce 
                que c&#8217;est ?</strong></div></td>
          </tr>
        </table>
      </div>
      <p>A partir du 15 novembre 2006, les consommateurs participeront au financement 
        de la fili&egrave;re de recyclage gr&acirc;ce &agrave; une &eacute;co 
        participation . Cette contribution environnementale per&ccedil;ue pour 
        le compte d'un organisme charg&eacute; de la r&eacute;cup&eacute;ration 
        du recyclage s'ajoute au prix de vente du produit.</p>
      <p align="center"><strong>Montant du produit + &Eacute;co-participation 
        = Prix du produit</strong></p>
      <p align="left">La fili&egrave;re D&eacute;chets d&#8217;Equipements Electriques 
        et Electroniques (DEEE) sera op&eacute;rationnelle et les d&eacute;chets 
        de type &eacute;lectrom&eacute;nager, &eacute;lectrique ou &eacute;lectronique 
        seront pris en charge. La cr&eacute;ation d&#8217;une &laquo; &eacute;co-participation 
        &raquo;, inclue dans le prix de chaque appareil permettra la mise en place 
        d&#8217;une fili&egrave;re prenant en charge ces types de d&eacute;chets 
        aux impacts environnementaux importants.</p>
      <p align="left">Qu&#8217;est-ce que cela va changer pour le consommateur 
        ? <br>
        Jusqu&#8217;&agrave; pr&eacute;sent, ce dernier n&#8217;avait, &agrave; 
        sa disposition, aucune solution &eacute;cologiquement acceptable, pour 
        se d&eacute;barrasser d&#8217;un appareil &eacute;lectrique et &eacute;lectronique.<br>
        D&eacute;sormais, le consommateur peut choisir parmi plusieurs fili&egrave;res 
        :</p>
      <div align="left">
        <ul>
          <li>apporter l&#8217;appareil &agrave; une association d&#8217;insertion 
            (type Emma&uuml;s) qui pourra le r&eacute;parer et le revendre, participant 
            ainsi &agrave; la r&eacute;duction des d&eacute;chets ;</li>
          <li>le rapporter &agrave; sa collectivit&eacute; quand celle-ci accepte 
            de mettre &agrave; sa disposition des conteneurs sp&eacute;ciaux ;</li>
          <li>le rapporter au magasin o&ugrave; il ach&egrave;tera un nouvel appareil 
            &eacute;quivalent</li>
        </ul>
      </div>
      <p align="left">Cette fili&egrave;re a un co&ucirc;t. Pour la financer, 
        l&#8217;Europe a choisi de rendre le fabricant responsable de la fin de 
        vie de ses produits. Ainsi, depuis le 15 novembre, les consommateurs participe 
        au financement de cette fili&egrave;re gr&acirc;ce &agrave; cette contribution 
        environnementale.<br>
        Au final, cette fili&egrave;re permettra de mieux g&eacute;rer ces d&eacute;chets 
        ayant une dur&eacute;e de vie toujours plus courte. Il est urgent de promouvoir 
        la r&eacute;paration-r&eacute;utilisation et de traiter/recycler nos DEEE.</p>
      <p>Cette contribution correspond donc &agrave; votre participation financi&egrave;re 
        &agrave; la collecte, &agrave; la r&eacute;utilisation, au recyclage d&#8217;un 
        produit usag&eacute;. <br>
        Aucune marge n&#8217;est faite sur ce montant par aucun des protagonistes.</p></td>
  </tr>
</table>
</body>
</html>
