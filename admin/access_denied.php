<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('includes/access_control.php');
include('includes/unified_layout.php');

// Start admin page with components
startUnifiedAdminPage('Access Denied', 'Access Denied - RansHotel Admin');
?>
    <div class="container">
            <!-- Top Bar -->
            <div class="top-bar">
                <h1 class="page-title">
                    <i class="fa fa-ban"></i> Access Denied
                </h1>
                <p class="page-subtitle">Insufficient Permissions</p>
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
<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
