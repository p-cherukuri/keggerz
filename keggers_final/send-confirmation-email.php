

<?


$Email=$_POST['email'];

$reservationNumber = rand(11, 99999);

    $body .= "Keg Reservation Number: " . $reservationNumber . "\n";

    //replace with your email
    mail($Email,"Keggers: Your Reservation Confirmation",$body);

  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script>alert("Your keg reservation number has been e-mailed to your given address. Please print the e-mail out and bring to the store to verify purchase.");</script>
<meta HTTP-EQUIV="REFRESH" content="0; url=index.html"> 

</head>