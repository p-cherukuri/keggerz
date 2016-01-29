<?php
	define("DB_SERVER","rutgers-sci.domains");
	define("DB_USER","itifall1_group2");
	define("DB_PASS","fall15group2");
	define("DB_NAME","itifall1_group2db");
	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	if(mysqli_connect_errno())
	{
		die("Database connection failed: ".mysqli_connect_error()." (".mysqli_connect_errno().")");
	}
?>
