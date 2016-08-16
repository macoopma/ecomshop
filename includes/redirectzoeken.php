<?php
session_start();

include('../configuratie/configuratie.php');

if(isset($_POST['search_query']) AND !empty($_POST['search_query'])) {
    $_POST['search_query'] = htmlspecialchars($_POST['search_query']);
    $_POST['search_query'] = trim(strtolower($_POST['search_query']));
    $_aa = array("|"," ","'","\\","<",">","%3C","%3E",";","alert(","(",")","sql","xss","%3c","%3e","&gt","&lt","script","exec","select","insert","drop","create","alter","update");
    $_bb = array("","!","’","","","","","","","","","","","","","","","","","","","","","","","");
    $_POST['search_query'] = str_replace($_aa,$_bb,$_POST['search_query']);
    $search_queryModified = str_replace("'","’",$_POST['search_query']);
    $search_queryModified = str_replace("\\","",$search_queryModified);

    $querySearch = mysql_query("SELECT search_engine FROM admin");
    $resultSearch = mysql_fetch_array($querySearch);
    
if(!empty($resultSearch['search_engine'])) {
    $mots = explode("|",$resultSearch['search_engine']);
    $numMots = count($mots);
             for($i=1;$i<=$numMots-1;$i++) {
                $mots2 = explode("-", $mots[$i]);
                $mot[$mots2[1]] = $mots2[0];
                $mot10[] = $mots2[1];
             }

            if(in_array($search_queryModified, array_keys($mot), true)) {
                 $u = $mot[$search_queryModified];
                 $i = $mot[$search_queryModified]+1;
                 $resultsSearch = str_replace($u."-".$search_queryModified, $i."-".$search_queryModified, $resultSearch['search_engine']);
             } 
             else {
                 $resultsSearch = $resultSearch['search_engine']."|1-".$search_queryModified;
             }
    }
    else {
        $resultsSearch = "|1-".$search_queryModified;
    }
     
    mysql_query("UPDATE admin SET search_engine='".$resultsSearch."'"); 
}

if(!isset($_POST['sep'])) $_POST['sep']="and";

 
$gotoo = "../zoeken.php?sep=".$_POST['sep']."&search_query=".$_POST['search_query'];
if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on") {$gotoo .= "&advCat=".$_POST['advCat']."&advComp=".$_POST['advComp'];}
header("Location: $gotoo");

?>
