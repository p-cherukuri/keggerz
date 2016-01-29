<?php
require_once('db_connection.php');
$storenames_query = "SELECT storeID, storeName FROM liq_store";
$storenames_result = mysqli_query($connection,$storenames_query);
$display_stores_dropdown=false;
$no_reservation=false;
$display_report=false;
if ($storenames_result && mysqli_num_rows($storenames_result)>0) {
  $display_stores_dropdown=true;
}
if(isset($_POST['submit_btn'])){
  //get reservations for store
  $selected_storeID = $_POST['storeID'];
  $report_query = "SELECT liq_storeKeg.id AS skid, reservations.id AS resID, fname, lname, storeName, vendor, brew, size FROM customers
    JOIN reservations ON customers.id = reservations.customerID
    JOIN liq_storeKeg ON liq_storeKeg.id = reservations.storeKegID
    JOIN keg ON keg.kegID = liq_storeKeg.kegIDFK
    JOIN liq_store ON liq_storeKeg.storeIDFK = liq_store.storeID
    WHERE liq_store.storeID ={$selected_storeID}";
    $report_result = mysqli_query($connection,$report_query);
    echo mysqli_error($connection);
    if ($report_result && mysqli_num_rows($report_result)>0) {
      $display_report=true;
    }
    else{
      $no_reservation=true;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title>Keggers Report</title>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
 <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
 <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
 <!-- Latest compiled and minified JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 <link rel="stylesheet" href="custom.css">
</head>
<body>
 <div class="container">
   <div class="row">
     <div class="page-header">
       <h1>Keggers Inc.</h1>
     </div>
   </div>
   <div class="row">
     <div class="col-sm-4">
       <form id="search" method="post" action="report.php">  <!--DISPLAY ONCE BRAND FLAVOR SET IS RETURNED <button type="submit" class="btn btn-default" value="Submitted" name="submit_btn">Search</button>-->
       <?php
         if ($display_stores_dropdown) {//displays the vendor's options if successful vendor query?>
         <div class="form-group">
           <label for="storeNames">Select a Store:</label>
           <select class="form-control" id="storeName" name="storeID">

               <?php while($row = mysqli_fetch_assoc($storenames_result)){
                 $storeName = $row['storeName'];
                 $storeID = $row['storeID'];
                 $reservation_list_item = "<option ";
                 if($_POST['storeID']==$row['storeID']){
                  $reservation_list_item .= "selected";
                }
                  $reservation_list_item .=" value={$storeID}>{$storeName}</option>";
                  echo $reservation_list_item;
                 ?>
             <?php
           }//end while loop?>
             </select>
         </div>
         <button type="submit" class="btn btn-default" value="Submitted" name="submit_btn">Search</button>
         <?php }//end if check for brand_options_result and mysqli_num_rows?>
       </form>
     </div>
     <div class="col-sm-8">

     </div>
   </div>
   <div class="row">

     <?php
      if($display_report==true){?>
       <ul class="list-group voff">
         <?php while($report_row = mysqli_fetch_assoc($report_result)){
           //$storeName = $row['storeName'];
           //$storeID = $row['storeID'];
           $display_size="1/4";
           if($report_row['size']==2){
             $display_size="1/3";
           }
           else if($report_row['size']==3){
             $display_size="1/2";
           }
           else{
             $display_size="Full";
           }
           ?>
           <li class="list-group-item" value="<?php ?>">
          <?php echo "Name: ".$report_row['fname']." ". $report_row['lname']." | ".$display_size." ".$report_row['vendor'].", ".$report_row['brew'];?>
          <a href="remove_reservation.php?res=<?php echo $report_row['resID']?>&skid=<?php echo $report_row['skid']?>">Remove Reservation</a>
        </li>
       <?php }//end while loop?>
       </ul>
     <?php }
     if($no_reservation==true){?>
       <p>No reservation found</p>
     <?php }//display reservations for the store?>
   </div>

 </div>
</body>
</html>
