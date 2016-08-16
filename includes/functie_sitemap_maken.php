<?php

include('configuratie/configuratie.php');
$dateHoyGoogle = date("Y-m-d");


 
define('LIMITLIENPARFICHIER',50000); 
define('LIMITLIENINDEXE',50000); 
 
$racine= 'http://'.$www2.$domaineFull;

$compressionGZ = false;
 
$Goption=0;
 

$fichiersMoins = array();
if($affiliateVisible=='non') $fichiersMoins[]="affiliation.php";
if($gcActivate=='non') $fichiersMoins[]="geschenkbon.php";
if($activeActu=="non") $fichiersMoins[]="nieuws.php";
if($activeRSS=="non") $fichiersMoins[]="rss.php";

$ExtensionsAutorises = array('php','html','htm');
$DossiersInterdits = array('moneybookers','rss','kandp','js','1euro','geschenkbon','bluePaid','admin','configuration','includes','2co','klantlogin','css','devis','download','ewp','im','import','installation','ipos','liaison-ssl','ogone','paysitecash','paypal','request');
$FichiersInterdits = array('selecteer_verzending.php','clean.php','404.php','leverings_wijze.php','doc.php','niet_ok.php','direct_betalen.php','pop_uitleg.php','gebruik_bonuspunten.php','bekijken.php','login.php','login_aanmaken.php','ok.php','artikel_fiche.php','artikel_fiche_html.php','sendToFriend.php','affiliate_link.php','gebruik_zoeken.php','shop_gesloten.php','infos.php','wachtwoord_vergeten.php','offerte.php','selecteer_betaling.php','payment.php','trans_payment.php','pop_up.php','pagina_box.php','bevestigd.php','voorwaarden.php','berekenen.php','bannerklik.php','affiliate_details.php','affiliate_linksWeb.php','sitemap.php','betaling_met_paypal.php','liaison_ssl_payment.php','mini_maker.php','configuratie.php','mysql_connect.php','add.php','nieuwsbrief_systeem.php');

if(count($fichiersMoins)>0) $FichiersInterdits = array_merge($fichiersMoins, $FichiersInterdits);

$myfiles=GetDirContents('.');
$myfiles = $myfiles[0];

 
if($displayOutOfStock=="non") {$addToQueryGoogle = " AND products_qt>'0'";} else {$addToQueryGoogle="";}
$resultGoogle = mysql_query("SELECT products_id
                              FROM products
                              WHERE products_visible = 'yes'
                              ".$addToQueryGoogle."
                              AND products_id != '1'
                              ORDER BY products_name_".$_SESSION['lang']."");
                                       
$resultGoogleNum = mysql_num_rows($resultGoogle);
 
while($produitGoogle = mysql_fetch_array($resultGoogle)) {
    $AddToMyfile[] = array("lien"=>$racine.'/beschrijving.php?id='.$produitGoogle['products_id'],'date'=>$dateHoyGoogle);
} 

$AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=3','date'=>$dateHoyGoogle);
$AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=4','date'=>$dateHoyGoogle);
$AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=5','date'=>$dateHoyGoogle);
if(isset($_SESSION['list'])) {
   $AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=6','date'=>$dateHoyGoogle);
}
$AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=9','date'=>$dateHoyGoogle);
$AddToMyfile[] = array("lien"=>$racine.'/infos.php?info=10','date'=>$dateHoyGoogle);
 
  $resultDoc = mysql_query("SELECT page_added_id FROM page_added WHERE page_added_visible = 'yes' ORDER BY page_added_id ASC, page_added_use ASC");
  $resultDocNum = mysql_num_rows($resultDoc);
  if($resultDocNum>0) {
     while($docGoogle = mysql_fetch_array($resultDoc)) {
        $docId = $docGoogle['page_added_id']+1000;
        $AddToMyfile[] = array("lien"=>$racine.'/doc.php?id='.$docId,'date'=>$dateHoyGoogle);
     }
  }

$myfiles = array_merge($myfiles, $AddToMyfile);
if(sizeof($myfiles)==0) $myfiles = $AddToMyfile;

 
 
function Dossier_Autorisé($DossierCourant) {
   global $DossiersInterdits;
   return Est_Autorisé($DossierCourant, $DossiersInterdits);
}
 
 
function Fichier_Autorisé($FichierCourant) {
   global $FichiersInterdits;
   return Est_Autorisé($FichierCourant, $FichiersInterdits);
}
 
 
function Extension_Autorisé($ExtensionCourante) {
   global $ExtensionsAutorises;
   return !Est_Autorisé($ExtensionCourante,$ExtensionsAutorises);
}
 
 
function Est_Autorisé($DossierCourant,$Interdits) {
  global $Goption;
  $drapeau = true;
  while ($drapeau && list(,$Dossier)=each($Interdits) ) {
  if(ComparaisonFichier($DossierCourant,$Dossier,$Goption)) $drapeau = false;
  }
  reset($Interdits);
  return $drapeau;
}
 
function ComparaisonFichier($DossierCourant,$Dossier,$option=0) {
  switch ($option) {
  case 0:

  return ($DossierCourant == $Dossier);
  break;
   
  case 1:
 
  $pos = strpos($mystring, $findme);
  if($pos === false) {
   return false;
  }
  else {
   return true;
  }
  break;
   
  case 2:
   return @preg_match("/".$Dossier."/",$DossierCourant);
  break;
  }
}
 
function getextension($fichier) {
   $bouts = explode('.', $fichier);
   return array_pop($bouts);
}
 
 
 
function GetDirContents($dir) {
  global $racine;
  $i=0;
  
  if(!is_dir($dir)) {die ('PROBLEME: '.$dir.'!');}
  if($root=@opendir($dir)) {
  while ($file=readdir($root)) {
    if($file=='.' || $file=='..') {continue;}
    if(is_dir($dir.'/'.$file) && Dossier_Autorisé($file)) {
       if(!IsSet($files)) {$files[] = NULL;}
       $files=array_merge($files,GetDirContents($dir.'/'.$file));
    }
    else {
       $extension=getextension($file);
       if(Extension_Autorisé($extension) && Fichier_Autorisé($file)) {
   
       $files[$i]['lien']=utf8_encode($racine.substr($dir,1).'/'.$file);
    
     
       $modi_fich=filemtime($dir.'/'.$file);
       $files[$i]['date']=date('Y-m-d', $modi_fich);
       $i++;
       }
    }
  }
  }
  if(!IsSet($files)) {$files[] = NULL;}
  return array($files);
}

$nbliens=count($myfiles);
$page = $nbliens-$resultGoogleNum+7;
$resultGoogleNum = $resultGoogleNum-7;


if($nbliens>LIMITLIENPARFICHIER) {
 
  
   $numfichier=1;
   
}
else {
   $numfichier='';
    
}
 
if($compressionGZ) {
 
  $open='gzopen';
  $write='gzwrite';
  $close='gzclose';
  $GZ='.gz';
}
else {
  $open='fopen';
  $write='fwrite';
  $close='fclose';
  $GZ='';
}
 
$CurLiens=0;
while ($CurLiens<$nbliens && $CurLiens < LIMITLIENINDEXE ) {
  if($fp = $open('sitemap'.$numfichier.'.xml'.$GZ, 'w')) {
  $write($fp,'<?xml version="1.0" encoding="UTF-8"?>'."\r\n");
  $write($fp,'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\r\n");
  $Limite = $CurLiens + LIMITLIENPARFICHIER; 
  
   
          while ($CurLiens< $Limite && $CurLiens<LIMITLIENINDEXE && list(,$file)=each($myfiles)) {
              $write($fp,'<url> '."\r\n".' <loc>'.$file['lien'].'</loc> '."\r\n \r\n");
              $write($fp,"\t\t".'<lastmod>'.$file['date'].'</lastmod>'."\n");
              $write($fp,'<changefreq>monthly</changefreq>');
              $write($fp,'<priority>0.5</priority></url>');
              $CurLiens ++;
          }
          $write($fp, '</urlset>');
          $close($fp);
   
      }
      else {
          echo 'sitemap'.$numfichier.'.xml',"\r\n"
          ,'<br /><br /><textarea rows="30" cols="100">',"\r\n"
          ,'<?xml version="1.0" encoding="UTF-8"?>',"\r\n"
          ,'<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">',"\r\n";
   
       $Limite = $CurLiens + LIMITLIENPARFICHIER;
      while ($CurLiens< $Limite && $CurLiens<LIMITLIENINDEXE && list(,$file)=each($myfiles)) {
          echo '<url> '."\r\n".' <loc>'.$file['lien'].'</loc> '."\r\n ";
          echo '<lastmod>'.$file['date'].'</lastmod>'."\r\n";
          echo '<changefreq>monthly</changefreq>'."\r\n";
          echo '<priority>0.5</priority></url>'."\r\n";
          $CurLiens ++;
      }
      echo '</urlset></textarea><br />';
   }
   $numfichier++;
}
 
if($numfichier!=1) {
    echo '<h2>Génération du fichier sitemap index file.xml...</h2>';
     
    if($fp = fopen('sitemap.xml', 'w+')) {
 
        fwrite($fp, '<?xml version="1.0" encoding="UTF-8"?>'."\r\n");
        fwrite($fp, '<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">'."\r\n");
        $date=date('Y-m-d');
 
        for($k=1;$k<$numfichier;$k++) {
            fwrite($fp, '<sitemap>'."\r\n");
            fwrite($fp,'<loc>'.$racine.'/sitemap'.$k.'.xml'.$GZ.'</loc>'."\r\n");
            fwrite($fp, '<lastmod>'.$date.'</lastmod>'."\r\n");
            fwrite($fp,'</sitemap>'."\r\n");
        }
        fwrite($fp, '</sitemapindex>'."\r\n");
        fclose($fp);

    }
    else {
        echo '<br /><br /><textarea rows="30" cols="100">',"\r\n"
        ,'<?xml version="1.0" encoding="UTF-8"?>',"\r\n"
        ,'<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">',"\r\n";
        $date=date("Y-m-d");
        for($k=1;$k<$numfichier;$k++) {
        echo '<sitemap>',"\r\n"
        ,'<loc>',$racine,'/sitemap',$k,'.xml</loc>',"\r\n"
        ,'<lastmod>',$date,'</lastmod>',"\r\n"
        ,'</sitemap>',"\r\n";
        }
        echo '</sitemapindex>',"\r\n";
    }
}
?>