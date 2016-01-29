<?php
require_once('db_connection.php');
if(isset($_GET['res'])){
    $reservationID = $_GET['res'];
    $skid = $_GET['skid'];
    $remove_query = "DELETE FROM reservations WHERE id = {$reservationID}";
    $remove_result = mysqli_query($connection, $remove_query);
    if($remove_result){
      $update_availability = "UPDATE liq_storeKeg SET available = 1 WHERE id = $skid";
      $update_result = mysqli_query($connection, $update_availability);
      if($update_result){
          header("Location: report.php");
      }
    }
    else{
      header("Location: google.com");
    }
}


?>
