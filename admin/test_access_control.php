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
    <title>Access Control Test - RansHotel</title>
    <meta name="description" content="Test Access Control System for RansHotel">
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
                            Access Control Test <small>Role-Based Permissions</small>
                        </h1>
                    </div>
                </div>
                <!-- /. ROW  -->
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-user"></i> Current User Information
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>User Details</h4>
                                        <p><strong>Username:</strong> <?php echo $_SESSION['user']; ?></p>
                                        <p><strong>Full Name:</strong> <?php echo $_SESSION['full_name'] ?? 'N/A'; ?></p>
                                        <p><strong>Role:</strong> <span class="label label-info"><?php echo ucfirst(getCurrentUserRole()); ?></span></p>
                                        <p><strong>Email:</strong> <?php echo $_SESSION['email'] ?? 'N/A'; ?></p>
                                        <p><strong>User ID:</strong> <?php echo getCurrentUserId(); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Role Functions</h4>
                                        <p><strong>Is Admin:</strong> <?php echo isAdmin() ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>'; ?></p>
                                        <p><strong>Is Manager:</strong> <?php echo isManager() ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>'; ?></p>
                                        <p><strong>Is Staff:</strong> <?php echo isStaff() ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>'; ?></p>
                                        <p><strong>Is Admin or Manager:</strong> <?php echo isAdminOrManager() ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>'; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-shield"></i> Feature Access Permissions
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Feature</th>
                                                <th>Description</th>
                                                <th>Access Status</th>
                                                <th>Test Link</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $features = [
                                                'dashboard' => ['Dashboard', 'Main dashboard view'],
                                                'room_booking' => ['Room Booking', 'Manage room bookings'],
                                                'reservations' => ['Reservations', 'View and manage reservations'],
                                                'notifications' => ['Notifications', 'Send and manage notifications'],
                                                'payment' => ['Payment', 'View payment information'],
                                                'profit' => ['Profit', 'View profit and financial reports'],
                                                'pricing' => ['Pricing Management', 'Manage room pricing'],
                                                'messages' => ['Messages/Newsletter', 'Manage messages and newsletters'],
                                                'user_management' => ['User Management', 'Manage admin users and staff'],
                                                'room_management' => ['Room Management', 'Manage room settings'],
                                                'add_room' => ['Add Room', 'Add new rooms to the system'],
                                                'delete_room' => ['Delete Room', 'Remove rooms from the system'],
                                                'newsletter' => ['Newsletter', 'Manage newsletter subscriptions'],
                                                'system_settings' => ['System Settings', 'Configure system settings']
                                            ];
                                            
                                            $pageLinks = [
                                                'dashboard' => 'home.php',
                                                'room_booking' => 'roombook.php',
                                                'reservations' => 'reservation.php',
                                                'notifications' => 'notifications.php',
                                                'payment' => 'payment.php',
                                                'profit' => 'profit.php',
                                                'pricing' => 'pricing.php',
                                                'messages' => 'messages.php',
                                                'user_management' => 'user_management.php',
                                                'room_management' => 'settings.php',
                                                'add_room' => 'room.php',
                                                'delete_room' => 'roomdel.php',
                                                'newsletter' => 'newsletter.php',
                                                'system_settings' => 'settings.php'
                                            ];
                                            
                                            foreach ($features as $feature => $info) {
                                                $hasAccess = canAccess($feature);
                                                $statusClass = $hasAccess ? 'success' : 'danger';
                                                $statusText = $hasAccess ? 'Allowed' : 'Denied';
                                                $statusIcon = $hasAccess ? 'check' : 'times';
                                                
                                                echo '<tr>';
                                                echo '<td><strong>' . $info[0] . '</strong></td>';
                                                echo '<td>' . $info[1] . '</td>';
                                                echo '<td><span class="label label-' . $statusClass . '"><i class="fa fa-' . $statusIcon . '"></i> ' . $statusText . '</span></td>';
                                                
                                                if (isset($pageLinks[$feature])) {
                                                    if ($hasAccess) {
                                                        echo '<td><a href="' . $pageLinks[$feature] . '" class="btn btn-sm btn-primary">Test Access</a></td>';
                                                    } else {
                                                        echo '<td><a href="' . $pageLinks[$feature] . '" class="btn btn-sm btn-danger">Test (Will Deny)</a></td>';
                                                    }
                                                } else {
                                                    echo '<td><span class="text-muted">No direct page</span></td>';
                                                }
                                                echo '</tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-info-circle"></i> Role Summary
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5><span class="label label-primary">Admin</span></h5>
                                        <ul>
                                            <li>Full access to all features</li>
                                            <li>Can manage users and staff</li>
                                            <li>Can modify system settings</li>
                                            <li>Can view all financial data</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><span class="label label-warning">Manager</span></h5>
                                        <ul>
                                            <li>Access to most features</li>
                                            <li>Cannot manage users</li>
                                            <li>Cannot modify system settings</li>
                                            <li>Can view financial data</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <h5><span class="label label-default">Staff</span></h5>
                                        <ul>
                                            <li>Limited access to basic features</li>
                                            <li>Cannot access financial data</li>
                                            <li>Cannot modify pricing</li>
                                            <li>Cannot manage rooms or users</li>
                                        </ul>
                                    </div>
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
