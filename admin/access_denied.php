<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('includes/access_control.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Access Denied - RansHotel</title>
    <meta name="description" content="Access Denied - RansHotel Admin">
    <meta name="author" content="RansHotel">
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Responsive Admin Styles-->
    <link href="assets/css/responsive-admin.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"> <?php echo $_SESSION["user"]; ?> </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="usersetting.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <?php
                    $menu = getNavigationMenu();
                    foreach ($menu as $item) {
                        echo '<li><a href="' . $item['url'] . '"><i class="fa ' . $item['icon'] . '"></i> ' . $item['text'] . '</a></li>';
                    }
                    ?>
                    <li>
                        <a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Access Denied <small>Insufficient Permissions</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-ban"></i> Access Denied
                                </h3>
                            </div>
                            <div class="panel-body text-center">
                                <div class="alert alert-danger">
                                    <h4><i class="fa fa-exclamation-triangle"></i> Insufficient Permissions</h4>
                                    <p>You do not have permission to access this feature.</p>
                                    <p><strong>Your Role:</strong> <?php echo ucfirst(getCurrentUserRole()); ?></p>
                                    <p><strong>Username:</strong> <?php echo $_SESSION['user']; ?></p>
                                </div>
                                
                                <div class="well">
                                    <h5>Available Features for <?php echo ucfirst(getCurrentUserRole()); ?>:</h5>
                                    <ul class="list-unstyled">
                                        <?php
                                        $permissions = [
                                            'admin' => ['Dashboard', 'Room Booking', 'Reservations', 'Notifications', 'Payment', 'Profit', 'Pricing', 'Messages', 'User Management', 'Room Management'],
                                            'manager' => ['Dashboard', 'Room Booking', 'Reservations', 'Notifications', 'Payment', 'Profit', 'Pricing', 'Messages', 'Room Management'],
                                            'staff' => ['Dashboard', 'Room Booking', 'Reservations', 'Notifications', 'Messages']
                                        ];
                                        
                                        $role = getCurrentUserRole();
                                        if (isset($permissions[$role])) {
                                            foreach ($permissions[$role] as $feature) {
                                                echo '<li><i class="fa fa-check text-success"></i> ' . $feature . '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                
                                <div class="btn-group">
                                    <a href="home.php" class="btn btn-primary">
                                        <i class="fa fa-home"></i> Return to Dashboard
                                    </a>
                                    <a href="logout.php" class="btn btn-default">
                                        <i class="fa fa-sign-out"></i> Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
</body>
</html>
