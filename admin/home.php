<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
initAccessControl('dashboard');

// Redirect to the new consistent dashboard
header("location:dashboard_simple.php");
exit();
?>