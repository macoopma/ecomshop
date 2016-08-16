<?php

function findInfoName($urlProduct) {
        GLOBAL $_SESSION;
        if($urlProduct=="infos.php?info=3") $returnInfos = PAIEMENTS;
        if($urlProduct=="infos.php?info=4") $returnInfos = CONDITIONS_D_USAGE;
        if($urlProduct=="infos.php?info=5") $returnInfos = NOUS_CONTACTER;
        if(isset($_SESSION['list'])) {
            if($urlProduct=="infos.php?info=6") $returnInfos = UTILISATION_CADDIE;
        }
        if($urlProduct=="infos.php?info=9") $returnInfos = PARTENAIRES;
        if($urlProduct=="infos.php?info=10") $returnInfos = QUI_SOMMES_NOUS;
        return $returnInfos;
}

 
function findProducName($urlProduct) {
  GLOBAL $_SESSION,$displayOutOfStock;
  $search1 = explode("?",$urlProduct);
  $searchId = explode("=",$search1[1]);
  if($displayOutOfStock=="non") {$sitemapQuery = " AND products_qt>'0'";} else {$sitemapQuery="";}
  $resultGoogle = mysql_query("SELECT products_id, products_name_".$_SESSION['lang']."
                                FROM products
                                WHERE products_visible = 'yes'
                                AND products_id = '".$searchId[1]."'
                                ".$sitemapQuery."");
  while ($nameSitemap = mysql_fetch_array($resultGoogle)) {
     $nameS = $nameSitemap['products_name_'.$_SESSION['lang']];
  }
  return $nameS;
}

 
function findDocName($urlProduct) {
  GLOBAL $_SESSION;
  $searchDoc = explode("?",$urlProduct);
  if(count($searchDoc)>1) {
    $searchIdDoc = explode("=",$searchDoc[1]);
    $searchIdDoc[1] = $searchIdDoc[1]-1000;
    $resultDoc = mysql_query("SELECT page_added_title_".$_SESSION['lang']." 
                              FROM page_added 
                              WHERE page_added_visible = 'yes' 
                              AND page_added_id = '".$searchIdDoc[1]."'");
    while($docSitemap = mysql_fetch_array($resultDoc)) {
       $nameDoc = strip_tags($docSitemap['page_added_title_'.$_SESSION['lang']]);
       if(strlen($nameDoc) > 70) {
          $nameDoc = substr($nameDoc, 0, 70); 
          $nameDoc = substr_replace($nameDoc,'...',-3);
       }
    }
  }
  else {
    $nameDoc="";
  }
    return $nameDoc;
}

 
 
function lit_xml($fichier,$item,$champs) {
   if($chaine = implode("",file($fichier))) {
      $tmp = preg_split("/<\/?".$item.">/",$chaine);
      for($i=1;$i<sizeof($tmp)-1;$i+=2)
         foreach($champs as $champ) {
            $tmp2 = preg_split("/<\/?".$champ.">/",$tmp[$i]);
            $tmp3[$i-1][] = $tmp2[1];
         }
      return $tmp3;
   }
}

 
$xml = lit_xml("sitemap.xml","url",array("loc","lastmod","changefreq","priority"));

foreach($xml as $row) {
      $urlProduct = $row[0];
      $urlProduct = str_replace("http://".$www2.$domaineFull."/","",$urlProduct);

  
    if(preg_match ("/\bbeschrijving.php\b/i", $urlProduct) AND $urlProduct!=='beschrijving.php') {
        $displayProducts = "<img src='im/fleche_right.gif'> <a href='".$urlProduct."'>".findProducName($urlProduct)."</a><br>";
    }
    else {
        $displayProducts="";
    }
    
   
    if(preg_match ("/\binfos.php\b/i", $urlProduct)) {
        $inf = findInfoName($urlProduct);
        if(!empty($inf)) {
            $displayInfos = "&nbsp;&nbsp;&bull;&nbsp;<a href='".$urlProduct."'>".$inf."</a><br>";
        }
        else {
            $displayInfos="";
        }
    }
    else {
        $displayInfos="";
    }

    
      if(preg_match ("/\bdoc.php\b/i", $urlProduct)) {
          $doc = findDocName($urlProduct);
          if(!empty($doc)) {
              $displayDoc = "&nbsp;&nbsp;&bull;&nbsp;<a href='".$urlProduct."'>".$doc."</a><br>";
          }
          else {
              $displayDoc="";
          }
      }
      else {
          $displayDoc="";
      }

     
    if($displayProducts=="" AND $displayInfos=="" AND $displayDoc=="" AND $row[0]!=="") {
      $displayAll = "&nbsp;&nbsp;&bull;&nbsp;<a href='".$urlProduct."'>".$row[0]."</a><br>";
    }
    else {
      $displayAll="";
    }
    
    if(isset($displayProducts) AND $displayProducts!=="") $displayProductsArray[]=$displayProducts;
    if(isset($displayInfos) AND $displayInfos!=="") $displayInfosArray[]=$displayInfos;
    if(isset($displayAll) AND $displayAll!=="") $displayAllArray[]=$displayAll;
    if(isset($displayDoc) AND $displayDoc!=="") $displayDocArray[]=$displayDoc;
        
    
    
    
}






print "<table border='0' align='center' width='100%' cellspacing='0' cellpadding='5'><tr><td>";

$result5 = mysql_query("SELECT * FROM categories WHERE categories_visible = 'yes' ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
if(isset($dataS)) unset($dataS);
$id = 0;
$i = 0;

while($messaget = mysql_fetch_array($result5)) {
    $papa = $messaget['categories_id'];
    $fils = $messaget['parent_id'];
    $noeud = $messaget['categories_noeud'];
    $menuNum = $_SESSION['tree2'][$messaget['categories_name_'.$_SESSION['lang']]];
    if($messaget['categories_noeud']=="B") {
        $titre = "<img src='im/fleche_right.gif'> <a href='categories.php?path=".$papa."&action=e&num=".$menuNum."'><b><span style='font-size:12px'>".$messaget['categories_name_'.$_SESSION['lang']]."</span></b></a>";
    }
    else {
        $titre = "<a href='list.php?path=".$papa."'><b><i><span class='fontrouge' style='font-size:11px'>".$messaget['categories_name_'.$_SESSION['lang']]."</span></i></b></a>";
    }
    $dataS[] = array($papa,$fils,$titre,$noeud);
}


 
function espace3t($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}

 
print "<table border='0' align='center' width='95%' cellspacing='0' cellpadding='0'>";

function recurt($tab,$pere,$rang3,$lang) {
GLOBAL $displayOutOfStock,$defaultOrder;
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {
    if($tab[$x][1]==$pere) {
       print "<tr>";
       print "<td>";
       print espace3($rang3).$tab[$x][2];
       if($displayOutOfStock=="non") {$addToQuery = " AND products_qt>'0'";} else {$addToQuery="";}
                if($defaultOrder == "Ref") $dOrder = "products_ref";
                if($defaultOrder == "Article") $dOrder = "products_name_".$_SESSION['lang'];
                if($defaultOrder == "Prix") $dOrder = "products_price";
                if($defaultOrder == "Entreprise") $dOrder = "fournisseurs_id";
                if($defaultOrder == "Les_plus_populaires") $dOrder = "products_viewed";
                if($defaultOrder == "Id") $dOrder = "products_id";
                if($defaultOrder == "id") $dOrder = "products_id";
                if(!isset($dOrder)) $dOrder = "products_id";
                
               $result3 = mysql_query("SELECT products_name_".$lang.", products_id
                                       FROM products
                                       WHERE categories_id = '".$tab[$x][0]."' 
                                       AND products_visible = 'yes'
                                       ".$addToQuery."
                                       OR (categories_id_bis LIKE '%|".$tab[$x][0]."|%'
                      						AND products_visible = 'yes')
                                       ORDER BY ".$dOrder."
                                       DESC");
               while($produit = mysql_fetch_array($result3)) {
                    if(strlen($produit['products_name_'.$lang])>=100) {
                        $displayTitle = "title='".$produit['products_name_'.$lang]."'"; 
                        $ProdNameProdList = substr($produit['products_name_'.$lang],0,100)."...";
                    } else {
                        $displayTitle = "";
                        $ProdNameProdList = $produit['products_name_'.$lang];
                    }
                print "<br>".espace3($rang3)."&nbsp;&nbsp;&nbsp;&nbsp;<img src='im/fleche_right_red.gif'> <a href='beschrijving.php?id=".$produit['products_id']."&path=".$tab[$x][0]."' ".$displayTitle.">".$ProdNameProdList."</a>";
               }
       print "</td>";
       recurt($tab,$tab[$x][0],$rang3+1,$lang);
    }
  }
}

recurt($dataS,"0","0",$_SESSION['lang']);
print "</tr>";
print "</table>";
print "<br>";

 
if(sizeof($displayInfosArray)>0) {
    print "<table border='0' align='center' width='95%' cellspacing='0' cellpadding='0'>";
    print "<tr>";
    print "<td height='20'><img src='im/fleche_right.gif'> <b><span style='font-size:12px'>".INFORMATIONS."</span></b></td></tr><tr>";
    print "<td>";

foreach ($displayInfosArray as $i => $value) {
    print $displayInfosArray[$i];
}
    print "</td>";
    print "</tr></table>";
}

 
if(sizeof($displayAllArray)>0 OR sizeof($displayDocArray)>0) {
    print "<table border='0' align='center' width='95%' cellspacing='0' cellpadding='0'>";
    print "<tr>";
    print "<td height='20'><img src='im/fleche_right.gif'> <b><span style='font-size:12px'>".DIVERS."</span></b></td></tr><tr>";
    print "<td>";

 
if(isset($displayDocArray) AND sizeof($displayDocArray)>0) {
foreach ($displayDocArray as $i => $value) {
    print $displayDocArray[$i];
}
}
 
foreach ($displayAllArray as $i => $value) {
 
}
    print "</td>";
    print "</tr></table>";
}

print "</td></tr></table>";
?>