<?php
//take in reservation form fields
//insert data into reservation table
//read the ID of the customer that was just inserted
//use customer ID and skID to insert into reservation table
//update the availability of keg in sk table
//redirect to confirmation page
//js-iUKww4gmAlsnp1ZuQkgX7y2QXhpP4XunRfTnjaKUvciUehp4BF2Jd4AamZ0rWuOO --AJAX call key
//mysql_query("INSERT INTO mytable (product) values ('kossu')");
//printf("Last inserted record has id %d\n", mysql_insert_id());
require_once('db_connection.php');
if(isset($_POST['submit'])){
  $fname = mysqli_real_escape_string($connection,$_POST['fname']);
  $lname = mysqli_real_escape_string($connection,$_POST['lname']);
  $tel = mysqli_real_escape_string($connection,$_POST['tel']);
  $skid = mysqli_real_escape_string($connection,$_POST['skid']);
  $insert_customer = "INSERT INTO customers(fname,lname,tel) VALUES('$fname','$lname','$tel')";
  $result = mysqli_query($connection, $insert_customer);
  if($result){
    $customerID = mysqli_insert_id($connection);
    $insert_reservation = "INSERT INTO reservations(customerID, storeKegID) VALUES($customerID,$skid)";
    $reservation_result = mysqli_query($connection, $insert_reservation);
    if ($reservation_result) {
      $update_availability = "UPDATE liq_storeKeg SET available = 0 WHERE id=$skid";
      $update_result = mysqli_query($connection, $update_availability);
      if($update_result){
        header("Location: confirmation.html");
      }
      else{
        header("Location: error.html");
      }
    }
  }
}
?>
