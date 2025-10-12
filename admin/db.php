<?php
// Create a consistent database connection using mysqli
$con = mysqli_connect("localhost","root","","hotel");

// Check connection and use mysqli_error instead of mysql_error
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
?>