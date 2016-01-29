<?php
//Now just create the reservation form, the Store Keg ID is passed in the URL parameters.
require_once('db_connection.php');
$brands_query = "SELECT DISTINCT(vendor) FROM keg";
$brands_list = "";
$display_Brand_options = false;
$display_final_results = false;
$noresults=false;
$invalidzip = false;
$brands_result= mysqli_query($connection,$brands_query);
//single page submit, checks for two submissions and acts according to the type of submission
if (isset($_POST['get_brand_opt_btn'])||isset($_POST['submit_btn'])) {//checks to see if any submission was made
  $zipcode = mysqli_real_escape_string($connection,$_POST['zip_code']);
  $selected_vendor = $_POST['brand_options'];
  $selected_size = intval($_POST['size']);
  if(isset($_POST['radius'])){
    $selected_radius = intval($_POST['radius']);
  }
  else{
    $selected_radius = 5;
  }
  if (isset($_POST['submit_btn'])) {//checks to see if the submission was the final search submission
    $brew_selected = $_POST['brew_options'];
    $zip_codes = [];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://www.zipcodeapi.com/rest/dpfNMqb7kNM1WAesgr5EhPyBYz8DotQBS5pEvBMQoyRl96OqyT4PJDqHczlA2bcV/radius.json/{$zipcode}/{$selected_radius}/mile",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: 0f536d06-80cb-f738-ad7e-4427227d1276"
      ),
    ));
    $response = curl_exec($curl);//gets other zip codes within a certain radius
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
      echo "cURL Error #:" . $err;
    }
    else{
      $response = json_decode($response, true);
      //print_r($response['zip_codes'][0]['zip_code']);
      if(!isset($response['error_code'])){//if there are zip codes, then they are stored in an array
        $zip_query = "SELECT DISTINCT(zipcode) FROM liq_store";
        $zip_index = [];
        $known_zips = mysqli_query($connection,$zip_query);
        if($known_zips && mysqli_num_rows($known_zips)>0){
          while($row = mysqli_fetch_assoc($known_zips)){
            $zip_index[$row['zipcode']] = 1;
          }
        }
        for ($i=0; $i < count($response['zip_codes']); $i++) {
          if($zip_index[$response['zip_codes'][$i]['zip_code']]){
            $zip_codes[] = $response['zip_codes'][$i]['zip_code'];
          }
           //."<br />"; check against hasmap of zipcodes
        }
        //future improvements: need to create an index of zip codes known to have liquor stores from our database
        //this will ensure maximum search range, but will not exhaust the search query
        $storeData = [];
          //get results based on brand and flavor
        for ($i=0; $i < count($zip_codes); $i++) {//for every zipcode in the array, a query is executed.
          $final_query = "SELECT liq_storeKeg.id AS skID, storeName, streetAddress, qty, kegID, vendor, brew, size, liq_storeKeg.price AS price FROM
          liq_store JOIN liq_storeKeg ON
          storeID = storeIDFK
          JOIN keg ON
          kegIDFK = kegID
          WHERE zipcode = '$zip_codes[$i]'
          AND vendor = '$selected_vendor'
          AND brew = '$brew_selected'
          AND size = '$selected_size'
          AND available = 1";
          $final_result = mysqli_query($connection,$final_query);
          //echo mysqli_error($connection);
          while($row = mysqli_fetch_assoc($final_result)){//assign each record to the array
            $storeData[] = $row;
          }
        }
        if(count($storeData)>0){
          $display_final_results = true;
        }
        else{
          $noresults=true;
        }
      }//end if good response
      else{
        $invalidzip=true;
      }
    }
        //sample api results
  }//end final submission check
  //if it is just a request for brews, then the brews are queried based on the selected vendor
    $brand_options_query = "SELECT DISTINCT(brew) FROM keg WHERE vendor = '{$selected_vendor}'";
    $brand_options_result = mysqli_query($connection,$brand_options_query);
    $display_Brand_options=true;//
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
 <title>Keggers</title>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
 <script src="//code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
 <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
 <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
 <!-- Latest compiled and minified JavaScript -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>
 <script type="text/javascript" src="search_validation.js"></script>
 <link rel="stylesheet" href="custom.css"/>
</head>
<body>
 <div class="container">
   <div class="row">
     <div class="page-header">
       <h1>Keggers Inc.</h1>
     </div>
   </div>
   <div class="row">
     <div class="col-sm-4"><img src="img/beer.png" class="img-responsive beericon"></img></div>
     <div class="col-sm-4">
       <form id="search" method="post" action="keggers_dev_wZRiv2.php">
         <div class="form-group">
            <input type="text" name="zip_code" id="usr_zip_code" class="form-control"
            <?php if(isset($zipcode)){?> value="<?php echo htmlentities($zipcode); //sets the zip code to user value on submission?>"/>
            <?php
            } else { ?>
                placeholder="Enter Zip Code"/>
              <?php }?>
        </div>

        <div class="form-group">
          <label for="radius">Search Radius:</label>
          <select class="c-select" name="radius" id="radius">
            <?php
            for($r = 5; $r<45;$r+=5){
                if($selected_radius==$r){//restores the users selected size after initial submission?>
                  <option value="<?php echo $r?>" selected><?php echo $r?></option>
                <?php }//end if selected_size check
                else{?>
                  <option value="<?php echo $r?>"><?php echo $r?></option>
                <?php }//end else
            }//end for loop
            ?>
          </select>&nbsp;Mi
        </div>

        <div class="form-group">
          <label for="keg_size">Select Keg size:</label>
          <select class="form-control" name="size" id="keg_size">
            <?php
            $j=1;
            for($i = 4; $i>0;$i--){
                if($selected_size==$j){//restores the users selected size after initial submission?>
                  <option value="<?php echo $j?>" selected><?php echo "1/".$i?></option>
                <?php }//end if selected_size check
                else{?>
                  <option value="<?php echo $j?>"><?php echo "1/".$i?></option>
                <?php }//end else
              $j++;
            }//end for loop
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="brand">Select a brand:</label>
          <select class="form-control" id="brand" name="brand_options">
            <option value="">(Choose a brand)</option>
            <?php if($brands_result && mysqli_num_rows($brands_result)>0){
              while($row = mysqli_fetch_assoc($brands_result)){//creates a dropdown menu with the available vendors
                $vendor = $row['vendor'];
                if($selected_vendor==$vendor){ //restores the selected vendor on initial submission?>
                <option selected value="<?php echo $vendor; ?>"><?php echo $vendor;?></option>
                <?php }//end if check for selected_vendor
                else{ ?>
                  <option value="<?php echo $vendor; ?>"><?php echo $vendor;?></option>
                <?php }// else block
              }//end of while loop
              }//end of if check for brands_result ?>
          </select>
        </div>
        <button type="submit" class="btn btn-default" value="Submitted" name="get_brand_opt_btn" id="gbo">Get Brand Options</button>
        <!--DISPLAY ONCE BRAND FLAVOR SET IS RETURNED <button type="submit" class="btn btn-default" value="Submitted" name="submit_btn">Search</button>-->
        <?php
          if ($display_Brand_options) {//displays the vendor's options if successful vendor query?>
          <div class="form-group">
            <label for="brew">Select a Brew:</label>
            <select class="form-control" id="brew" name="brew_options">
              <option selected value="">(Choose a brew)</option>
              <?php if($brand_options_result && mysqli_num_rows($brand_options_result)>0){//if the query was successful, it displays a list
                while($row = mysqli_fetch_assoc($brand_options_result)){
                  $brew = $row['brew'];?>
                  <option value="<?php echo $brew;?>"><?php echo $brew;?></option>
              <?php }//end while loop?>
              </select>
          </div>
          <button type="submit" class="btn btn-default" value="Submitted" name="submit_btn">Search</button>
          <?php }//end if check for brand_options_result and mysqli_num_rows
        }//end if check for display_Brand_options?>
      </form>
     </div>
     <div class="col-sm-4"></div>
   </div>
   <div class="row voff">
     <div class="col-sm-4"></div>
     <div class="col-sm-4">
       <?php

       if($display_final_results)
       {?>
           <ul class="list-group">
           <?php for($i=0;$i<count($storeData);$i++)
           {
             ?>
              <li class="list-group-item">
                <div><?php echo $storeData[$i]['brew']." @ ".$storeData[$i]['storeName']." on ".$storeData[$i]['streetAddress'].", price: $".$storeData[$i]['price'];?></div>
                <div><a href="reserve.php?skid=<?php echo $storeData[$i]['skID']; ?>" class="btn btn-info" role="button">Reserve Now</a></div>
              </li>
           <?php }//end while loop?>
           </ul>
          <?php
       }
       else if($invalidzip){
         echo "Invalid Zip Code, Try Again.";
       }
       else if($noresults){
         echo "No kegs found...";
       }//end if display final results
       ?>
     </div>
     <div class="col-sm-4">
       <div id="errors"></div>
    </div>
   </div>
 </div>

</body>
</html>
