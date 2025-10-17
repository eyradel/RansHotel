<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
initAccessControl('dashboard');
?> 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Dashboard - RansHotel Admin</title>
    <meta name="description" content="RansHotel Admin Dashboard - Professional hotel management system">
    <meta name="author" content="RansHotel">
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Professional Admin Styles -->
    <link href="assets/css/professional-admin.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet' />
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include('includes/navigation.php'); ?>

        <?php
        include('db.php');
        
        // Get booking statistics
        $sql = "select * from roombook";
        $re = mysqli_query($con, $sql);
        $c = 0;
        while($row = mysqli_fetch_array($re)) {
            $new = $row['stat'];
            if($new == "Not Confirm") {
                $c = $c + 1;
            }
        }
        
        // Get confirmed bookings count
        $rsql = "SELECT * FROM `roombook`";
        $rre = mysqli_query($con, $rsql);
        $r = 0;
        while($row = mysqli_fetch_array($rre)) {
            $br = $row['stat'];
            if($br == "Confirm") {
                $r = $r + 1;
            }
        }
        
        // Get newsletter subscribers count
        $fsql = "SELECT * FROM `contact`";
        $fre = mysqli_query($con, $fsql);
        $f = 0;
        while($row = mysqli_fetch_array($fre)) {
            $f = $f + 1;
        }
        ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">Dashboard</h1>
                <p class="text-muted mb-0">Hotel Management Overview</p>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge badge-primary mr-2"><?php echo ucfirst(getCurrentUserRole()); ?></span>
                <span class="text-muted"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $c; ?></h3>
                            <p class="mb-0">Pending Bookings</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-clock-o fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $r; ?></h3>
                            <p class="mb-0">Confirmed Bookings</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $f; ?></h3>
                            <p class="mb-0">Newsletter Subscribers</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $c + $r; ?></h3>
                            <p class="mb-0">Total Bookings</p>
                        </div>
                        <div class="stats-icon">
                            <i class="fa fa-calendar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (canAccess('room_booking')): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="roombook.php" class="btn btn-primary btn-lg w-100">
                                    <i class="fa fa-bed mr-2"></i>Manage Bookings
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (canAccess('notifications')): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="notifications.php" class="btn btn-success btn-lg w-100">
                                    <i class="fa fa-bell mr-2"></i>Send Notifications
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (canAccess('messages')): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="messages.php" class="btn btn-warning btn-lg w-100">
                                    <i class="fa fa-envelope mr-2"></i>Newsletter
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (canAccess('user_management')): ?>
                            <div class="col-md-3 col-sm-6 mb-3">
                                <a href="user_management_pro.php" class="btn btn-secondary btn-lg w-100">
                                    <i class="fa fa-users mr-2"></i>User Management
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Bookings</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Guest Name</th>
                                        <th>Room Type</th>
                                        <th>Check-in</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tsql = "select * from roombook ORDER BY id DESC LIMIT 10";
                                    $tre = mysqli_query($con, $tsql);
                                    while($trow = mysqli_fetch_array($tre)) {
                                        $statusClass = ($trow['stat'] == 'Confirm') ? 'badge-success' : 'badge-warning';
                                        echo "<tr>";
                                        echo "<td>#" . $trow['id'] . "</td>";
                                        echo "<td>" . $trow['FName'] . " " . $trow['LName'] . "</td>";
                                        echo "<td>" . $trow['TRoom'] . "</td>";
                                        echo "<td>" . $trow['cin'] . "</td>";
                                        echo "<td><span class='badge " . $statusClass . "'>" . $trow['stat'] . "</span></td>";
                                        echo "<td><a href='roombook.php?rid=" . $trow['id'] . "' class='btn btn-primary btn-sm'>Manage</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3>System Status</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Database Connection</span>
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Email System</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>SMS System</span>
                            <span class="badge badge-success">Active</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Access Control</span>
                            <span class="badge badge-success">Enabled</span>
                        </div>
                        <hr>
                        <div class="text-center">
                            <small class="text-muted">
                                Last updated: <?php echo date('H:i:s'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
</body>
</html>
