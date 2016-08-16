<?php
 
include("../configuratie/configuratie.php");

if(isset($_POST['orderID'])) {$orderid = $_POST['orderID'];}
if(isset($_GET['orderID'])) {$orderid = $_GET['orderID'];}

$query = mysql_query("SELECT *
                      FROM users_orders
                      WHERE users_nic = '".$orderid."'");
$queryNum = mysql_num_rows($query);

if($queryNum > 0) {
      $row = mysql_fetch_array($query);
      $amountTotal = $row['users_total_to_pay']*100;
      ?>
      <orderid="<?php print $orderid;?>" currency="EUR" amount="<?php print $amountTotal;?>">
      <?php 
}
else {
?>
    <unknown orderID>
<?php
}
?>
