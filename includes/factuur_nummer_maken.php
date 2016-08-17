<?php
function invoice_number_generator($invoice_number,$nicFact) {
   GLOBAL $factNum, $factPrefixe;
  if($invoice_number=="") {
    $queryUpFact = mysql_query("SELECT users_fact_num FROM users_orders ORDER BY users_fact_num ASC");
      if(mysql_num_rows($queryUpFact)>0) {
         while($rowUpFact = mysql_fetch_array($queryUpFact)) {
         	if($rowUpFact['users_fact_num']!=="") {
				$factNumExplode = explode("||",$rowUpFact['users_fact_num']);
            	$usersFactArray[] = (isset($factNumExplode[1]))? $factNumExplode[1] : $factNumExplode[0];
            }
         }
            
            sort($usersFactArray);
            $lastFact = end($usersFactArray);
            if(!isset($lastFact) OR $lastFact=='') {
               $usersFact = $factNum+1;
               $usersFact = $factPrefixe.$usersFact;
            }
            else {
               $usersFact = $lastFact+1;
               $usersFact = $factPrefixe.$usersFact;
            }
      }
      else {
         $usersFact = "";
      }
  }
  else {
   $usersFact = $invoice_number;
  }


//  mysql_query("UPDATE users_orders SET users_fact_num = '".$usersFact."' WHERE users_nic = '".$nicFact."'");
}


?>
