<?php


function displayPageNum($numLine) {
   GLOBAL $tototal, $NavNum, $numNumList, $_GET;
 
    $divOpen = "<div class='pagination'>";
    $divClose = "</div>";
    $ligne0 = '';    
    $ligne = '';
    $ligne1 = '';
    $ligne2 = '';
    $ligne3 = '';
    $spacer1 = "<img src='im/zzz.gif' width='3' height='1'>";
    $spacer3 = "<img src='im/zzz.gif' width='2' height='1'>";
    $page = $_GET['page'];
    if($page==0) $page=1; else $page = ($page/$numLine)+1;
    $pageNext = $page+1;
    $pagePrev = $page-1;
    unset($toto);
    for($e = 0; $e<=$tototal; $e+=$numLine) {
      $toto[] = $e;
    }
    if(end($toto)+$numLine <= $tototal) {
      $toto[]= end($toto)+$numLine;
    }
    $nbr=count($toto);
    
    if($tototal%$numLine == 0) $nbr = $nbr-1;


print "<table border=\"0\" width=\"100%\" height=\"15\"  cellspacing=\"0\" cellpadding=\"0\">";
print "<tr>";
print "<td valign=\"middle\" align=\"left\">";
print "<span class='FontGris'>".PAGE." <b>".$page."</b> ".PAGE_DE." ".$nbr."</span>";
print "</td>";
print "<td valign=\"middle\" align=\"right\">";

if($tototal>$numLine) {
 
    $nb_pages = ceil($tototal/$numLine);
  
    $nb_grp_page_total = ceil($nb_pages/$numNumList);
   
    $groupe_actu = ($page==0)? ceil($page/$numNumList)+1 : ceil($page/$numNumList);
    
    $page = (empty($page) OR $page == 0 OR $page > $nb_pages)? 1 : $page;
    
    $init = ($groupe_actu-1)*$numNumList+1;
    
    if($groupe_actu == $nb_grp_page_total) $limit=$nb_pages; else $limit=$groupe_actu*$numNumList;
   


    
    if($groupe_actu > 1) {
        $ligne3 .= "<span class='disabled'>".$NavNum;
        $ligne3 .= 0;
        $ligne3 .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".PREMIERE_PAGE."\" title=\"".PREMIERE_PAGE."\" border=\"0\" src=\"im/f_prev_end.gif\"></a>";
        $ligne3 .= "</span>";
                
     
        
        $ligne .= "<span class='disabled'>".$NavNum;
        $ligne .= ($page*$numLine)-(2*$numLine);
        $ligne .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".PRECEDENT." - ".strtolower(PAGE)." ".$pagePrev."\" title=\"".PRECEDENT." - ".strtolower(PAGE)." ".$pagePrev."\" border=\"0\" src=\"im/f_prev_1.gif\"></a>";
        $ligne .= "</span>".$spacer1;

        if($groupe_actu <= $nb_grp_page_total) {
            $ligne1 .= "<span class='disabled'>".$NavNum;
            if($groupe_actu == $nb_grp_page_total) {
               $prevGroup = ($numNumList*$groupe_actu)-(2*$numNumList)+1;
               $ligne1 .= (($numNumList*$groupe_actu*$numLine))-(2*$numNumList*$numLine);
            }
            else {
               $prevGroup = $limit-(2*$numNumList)+1;
               $ligne1 .= ($limit*$numLine)-(2*$numNumList*$numLine);
            }
            $ligne1 .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".strtolower(PAGE)." ".$prevGroup."\" title=\"".strtolower(PAGE)." ".$prevGroup."\" border=\"0\" src=\"im/f_prev.gif\"></a>";
            $ligne1 .= "</span>";
        }
        else {
            $ligne1 .= "";
        }
    }
    else {
       if($page==1) {
          $ligne .= "...";
          $ligne1 = "";
      }
       else {
          $ligne .= "<span class='disabled'>".$NavNum;
          $ligne .= ($page*$numLine)-(2*$numLine);
          $ligne .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".PRECEDENT." - ".strtolower(PAGE)." ".$pagePrev."\" title=\"".PRECEDENT." - ".strtolower(PAGE)." ".$pagePrev."\" border=\"0\" src=\"im/f_prev.gif\"></a>";
          $ligne .= "</span>".$spacer1;
       }
    }
    
     
    for($i=$init; $i<=$limit; $i++) {
       $iVal = ($i==1)? 0 : ($i-1)*$numLine;
           if($i == $page) {
             $ligne .= "<span class='current'>".$i."</span>";
           }
           else {
             $ligne .= $NavNum.$iVal."\">".$i."</a>";
           }
    }
   
     
    if($limit < $nb_pages) {
          $nextGroup = $limit+1;
          $ligne .= $spacer3."<span class='disabled'>".$NavNum;
          $ligne .= ($page*$numLine);
          $ligne .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".SUIVANTTT." - ".strtolower(PAGE)." ".$pageNext."\" title=\"".SUIVANTTT." - ".strtolower(PAGE)." ".$pageNext."\" border=\"0\" src=\"im/f_next_1.gif\"></a>";
          $yo2 = ($limit*$numLine);
          if($yo2 > $limit) {
               $ligne2 .= "<span class='disabled'>".$NavNum;
               $ligne2 .= ($limit*$numLine);
               $ligne2 .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".strtolower(PAGE)." ".$nextGroup."\" title=\"".strtolower(PAGE)." ".$nextGroup."\" border=\"0\" src=\"im/f_next.gif\"></a>";
               $ligne2 .= "</span>";
          }
          else {
               $ligne2 .= "";
          }
          $ligne .= "</span>";
    }
    else {
       if($page==$nb_pages) {
          $ligne .= "...";
          $ligne2 .= "";
       }
       else {
          $ligne .= "<span class='disabled'>".$NavNum;
          $ligne .= ($page*$numLine);
          $ligne .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".SUIVANTTT." - ".strtolower(PAGE)." ".$pageNext."\" title=\"".SUIVANTTT." - ".strtolower(PAGE)." ".$pageNext."\" border=\"0\" src=\"im/f_next.gif\"></a>";
          $ligne .= "</span>";
       }
    }

      
        if($groupe_actu < $nb_grp_page_total) {
        $ligne0 .= "<span class='disabled'>".$NavNum;
        $ligne0 .= ($nb_grp_page_total-1)*($numNumList*$numLine);
        $ligne0 .= "\" style=\"text-decoration:none\"><img align='absmiddle' alt=\"".DERNIERE_PAGE."\" title=\"".DERNIERE_PAGE."\" border=\"0\" src=\"im/f_next_end.gif\"></a>";
        $ligne0 .= "</span>";
        }
        
	print $divOpen;
    print $ligne3;
    print $ligne1;
    print $ligne;
    print $ligne2;
    print $ligne0;
    print $divClose;
}
print "</td>";
print "</tr></table>";
}
?>
