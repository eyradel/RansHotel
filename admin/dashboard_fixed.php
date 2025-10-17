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
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color); box-shadow: var(--shadow);">
        <div class="container-fluid">
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Brand -->
            <a class="navbar-brand font-weight-bold" href="dashboard_fixed.php">
                RansHotel Admin
            </a>
            
            <!-- User Menu -->
            <div class="navbar-nav ml-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-avatar mr-2">
                            <i class="fa fa-user-circle fa-lg"></i>
                        </div>
                        <div class="user-info d-none d-md-block">
                            <div class="user-name"><?php echo $_SESSION['full_name'] ?? $_SESSION['user']; ?></div>
                            <div class="user-role text-muted small"><?php echo ucfirst(getCurrentUserRole()); ?></div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="usersetting.php">
                            <i class="fa fa-user mr-2"></i>Profile
                        </a>
                        <a class="dropdown-item" href="settings.php">
                            <i class="fa fa-cog mr-2"></i>Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">
                            <i class="fa fa-sign-out mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <?php
                        $menu = getNavigationMenu();
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        
                        foreach ($menu as $item) {
                            $isActive = ($currentPage == basename($item['url'])) ? 'active' : '';
                            $icon = $item['icon'];
                            $text = $item['text'];
                            $url = $item['url'];
                            
                            echo "<li class='nav-item'>";
                            echo "<a class='nav-link $isActive' href='$url'>";
                            echo "<i class='fa $icon mr-2'></i>";
                            echo "$text";
                            echo "</a>";
                            echo "</li>";
                        }
                        ?>
                        
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fa fa-sign-out mr-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Sidebar Footer -->
                    <div class="sidebar-footer mt-auto p-3">
                        <div class="text-center">
                            <small class="text-muted">
                                RansHotel Admin v2.0<br>
                                <span class="badge badge-success">Online</span>
                            </small>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="pt-3 pb-2 mb-3">
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
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">Dashboard</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group mr-2">
                                <span class="badge badge-primary mr-2"><?php echo ucfirst(getCurrentUserRole()); ?></span>
                                <span class="text-muted"><?php echo date('F j, Y'); ?></span>
                            </div>
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
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
    
    <script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.querySelector('.navbar-toggler');
        const sidebar = document.querySelector('#sidebarMenu');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }
    });
    </script>
</body>
</html>
