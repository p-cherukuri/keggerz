<?php 
    date_default_timezone_set("America/New_York");
    echo date("h:i:sa")."<br />";

    foreach ($_POST as $key => $value) 
    {
        echo "HTML Item: $key; Value: $value <br />";
    }
?>
