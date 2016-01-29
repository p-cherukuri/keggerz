<?php
require_once('db_connection.php');

$report_query = "SELECT fname, lname, storeName, vendor, brew, size FROM customers
JOIN reservations ON customers.id = reservations.customerID
JOIN liq_storeKeg ON liq_storeKeg.id = reservations.storeKegID
JOIN keg ON keg.kegID = liq_storeKeg.kegIDFK
JOIN liq_store ON liq_storeKeg.storeIDFK = liq_store.storeID
WHERE liq_store.storeID =4";
$report_result = mysqli_query($connection,$report_query);
echo mysqli_error($connection);
while($report_row = mysqli_fetch_assoc($report_result)){
    //$storeName = $row['storeName'];
    //$storeID = $row['storeID'];
    echo var_dump($report_row);
    echo $report_row['fname'];
  }//end while loop

?>
