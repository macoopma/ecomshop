<?php
session_start();

include('../configuratie/configuratie.php');
?>

<html>
    <head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel='stylesheet' href='style.css'>
    </head>
    <body leftmargin="10" topmargin="50" marginwidth="10" marginheight="10">

<?php
$categQuery = mysql_query("SELECT export_auto_leguide FROM admin");
$categ = mysql_fetch_array($categQuery);
            
if(!empty($categ['export_auto_leguide'])) {
            $cat = $categ['export_auto_leguide'];
            $cat = explode("||", $categ['export_auto_leguide']);
            $cats = "AND categories_id=".$cat[1]." ";
            $u = 1;

for($i=1; $i<=count($cat)-1; $i++) {
    $cats .= "OR categories_id = '".$cat[$i]."' ";
}


            $categQuery = mysql_query("SELECT categories_name_".$cat[0]."
                               FROM categories
                               WHERE 1
                               $cats
                               ORDER BY categories_name_1
                               ");
            $categQueryNum = mysql_num_rows($categQuery);
            if($categQueryNum > 0) {
                while($categ = mysql_fetch_array($categQuery)) {
                    print $u++." - ".$categ['categories_name_'.$cat[0]]."<br>";
                }
            }
}
else {
    print "Leeg...";
}
?>
</body></html>
