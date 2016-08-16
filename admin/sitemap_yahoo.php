<?php

 
function build_yahoo_file($lang) {
    include('../configuratie/configuratie.php');
    if($displayOutOfStock=="oui" AND $actRes=="oui") {$addToQuery="";} else {$addToQuery = " AND products_qt>'0'";}
    // QUERY MAIN
    $Orderquery = mysql_query("SELECT p.products_id, p.categories_id, c.categories_visible, c.categories_noeud
                               FROM products as p
                               LEFT JOIN categories as c
                               ON (p.categories_id = c.categories_id)
                               WHERE p.products_visible = 'yes'
                               AND c.categories_visible = 'yes'
                               AND p.products_ref != 'GC100'
                               ".$addToQuery."
                               ORDER BY products_name_".$lang."");

$OrderqueryNum = mysql_num_rows($Orderquery);
 
$arr[] = "http://".$www2.$domaineFull."/index.php?lang=".$lang."\r\n";
$arr[] = "http://".$www2.$domaineFull."/infos.php?lang=".$lang."&info=3\r\n";
$arr[] = "http://".$www2.$domaineFull."/infos.php?lang=".$lang."&info=4\r\n";
$arr[] = "http://".$www2.$domaineFull."/infos.php?lang=".$lang."&info=5\r\n";
$arr[] = "http://".$www2.$domaineFull."/infos.php?lang=".$lang."&info=6\r\n";
$arr[] = "http://".$www2.$domaineFull."/infos.php?lang=".$lang."&info=9\r\n";
$arr[] = "http://".$www2.$domaineFull."/cataloog.php?lang=".$lang."\r\n";
$arr[] = "http://".$www2.$domaineFull."/affiliation.php?lang=".$lang."\r\n";
$arr[] = "http://".$www2.$domaineFull."/top10.php?lang=".$lang."\r\n";
$arr[] = "http://".$www2.$domaineFull."/rss.php?lang=".$lang."\r\n";


 
  $resultDoc = mysql_query("SELECT page_added_id FROM page_added WHERE page_added_visible = 'yes' ORDER BY page_added_id ASC, page_added_use ASC");
  $resultDocNum = mysql_num_rows($resultDoc);
  if($resultDocNum>0) {
     while($docGoogle = mysql_fetch_array($resultDoc)) {
        $docId = $docGoogle['page_added_id']+1000;
        $arr[] = "http://".$www2.$domaineFull."/doc.php?id=".$docId."&lang=".$lang."\r\n";
     }
  }

    if($OrderqueryNum > 0) {

            $Orderquery2 = mysql_query("SELECT categories_id, categories_noeud
                                       FROM categories
                                       WHERE categories_visible = 'yes'");
            if(mysql_num_rows($Orderquery2)>0) {
                while ($arrSelect2 = mysql_fetch_array($Orderquery2)) {
                    if($arrSelect2['categories_noeud']=="B") $arrCat[] = $arrSelect2['categories_id']; // categories
                    if($arrSelect2['categories_noeud']=="L") $arrCat3[] = $arrSelect2['categories_id']; // list
                }
            }

            while ($arrSelect = mysql_fetch_array($Orderquery)) {
 
                    $url = "http://".$www2.$domaineFull."/beschrijving.php?lang=".$lang."&id=".$arrSelect['products_id'];
                    $arr[] = $url."\r\n";
            }
            
  
            foreach($arrCat as $elemCat) {
                $arr[] = "http://".$www2.$domaineFull."/categories.php?lang=".$lang."&path=".$elemCat."&num=1&action=n&sort=".$defaultOrder."\r\n";
            }
   
            foreach($arrCat3 as $elemCat3) {
                $arr[] = "http://".$www2.$domaineFull."/list.php?lang=".$lang."&path=".$elemCat3."&num=1&action=n&sort=".$defaultOrder."\r\n";
            }

                    $file = "../url_lijst.txt";
                    $fp=fopen($file ,"w+");
                    if($fp) {
                        // display url beschrijving.php
                        foreach($arr as $elem) {
                            fwrite($fp, $elem);
                        }

                    }
                    fclose($fp);
    }
}
?>
