<?php
session_start();


$_SESSION['lang'] = $_POST['lang1'];
                $url_id11 = $_SESSION['ActiveUrl'];
                $check_var10 = strpos($url_id11,"lang=1");
                $check_var11 = strpos($url_id11,"lang=3");
                $check_var12 = strpos($url_id11,"lang=2");
                $check_var1 = strpos($url_id11,"&var=session_destroy");
                $check_var2 = strpos($url_id11,"?var=session_destroy");
                $check_var3 = strpos($url_id11,"&var=upd");
                $check_var4 = strpos($url_id11,"?var=upd");
                
                if(empty($check_var1) or empty($check_var2) or empty($check_var3) or empty($check_var4)) {
                    $goto = $url_id11;
                }
                if($check_var1 > 0) {
                    $goto = str_replace("&var=session_destroy", "", $url_id11);
                }
                if($check_var2 > 0) {
                    $goto = str_replace("?var=session_destroy", "", $url_id11);
                }
                if($check_var3 > 0) {
                    $goto = str_replace("&var=upd", "", $url_id11);
                }
                if($check_var4 > 0) {
                    $goto = str_replace("?var=upd", "", $url_id11);
                }
                if($check_var10 > 0) {
                    $goto = str_replace("lang=1", "lang=".$_POST['lang1'], $url_id11);
                }
                if($check_var11 > 0) {
                    $goto = str_replace("lang=3", "lang=".$_POST['lang1'], $url_id11);
                }
                if($check_var12 > 0) {
                    $goto = str_replace("lang=2", "lang=".$_POST['lang1'], $url_id11);
                }

header("Location: $goto");
?>
