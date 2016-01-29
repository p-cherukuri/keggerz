<?php
if (isset($_GET['skid'])) {
  $skid = ($_GET['skid']);
}
else{
  header('Location: keggersTest.php');
  exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
    <script src="//code.jquery.com/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"/>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="res_validation.js"></script>
  <title>Reservation</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="page-header">
          <h1>Keggers Inc.</h1>
        </div>
      </div>
        <div class="row">
            <div class="col-xs-4">

            </div>
            <div class="col-xs-4">
                <h1>Reservation</h1>
                <form action="process_reservation.php" id="reservation" method="post">
                  <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" placeholder="First Name" name="fname">
                  </div>
                  <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" placeholder="Last Name" name="lname">
                  </div>
                  <div class="form-group">
                    <label for="tel">Telephone</label>
                    <input type="text" class="form-control" id="tel" placeholder="Telephone" name="tel">
                  </div>
                  <input type="text" name="skid" value="<?php echo htmlentities($skid); ?>" hidden>
                  <button type="submit" class="btn btn-default" name='submit' value="Subitted">Submit</button>
                </form>
            </div>
            <div class="col-xs-4">
              <div id="error"></div>
            </div>
        </div>
    </div>
  </body>
</html>
