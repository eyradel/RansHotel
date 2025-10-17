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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <title>Dashboard - RansHotel Admin</title>
    <meta name="description" content="RansHotel Admin Dashboard - Professional hotel management system">
    <meta name="author" content="RansHotel">
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet' />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: var(--shadow);
            padding: 0.75rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 56px;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: white !important;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .sidebar {
            background-color: var(--secondary-color);
            position: fixed;
            top: 56px;
            left: 0;
            width: 250px;
            height: calc(100vh - 56px);
            z-index: 1020;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link {
            color: #bdc3c7;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius);
            margin: 0.25rem 0.5rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
        }

        .sidebar .nav-link.active {
            background-color: var(--accent-color);
            color: white;
        }

        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 56px;
            padding: 2rem;
            min-height: calc(100vh - 56px);
            background-color: #f8f9fa;
        }

        .stats-card {
            background: linear-gradient(135deg, var(--accent-color), #2980b9);
            color: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stats-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }

        .stats-card p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }

        .stats-card .stats-icon {
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.3;
            font-size: 3rem;
        }

        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }

        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--dark-color);
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-name {
            font-weight: 500;
            color: white;
        }

        .user-role {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.7);
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .stats-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .stats-card h3 {
                font-size: 2rem;
            }
            
            .stats-card .stats-icon {
                display: none;
            }
            
            .btn-lg {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .table-responsive {
                border: none;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                align-items: flex-start !important;
            }
            
            .d-flex.justify-content-between > div:last-child {
                margin-top: 1rem;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 0.5rem;
            }
            
            .stats-card {
                padding: 1rem;
            }
            
            .stats-card h3 {
                font-size: 1.5rem;
            }
            
            .card-header {
                padding: 0.75rem 1rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                width: auto;
                margin-bottom: 0;
            }
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            background-color: var(--secondary-color);
        }

        .sidebar-footer small {
            color: rgba(255,255,255,0.7);
        }

        .nav-item {
            list-style: none;
        }

        .nav {
            padding-left: 0;
        }

        .flex-column {
            flex-direction: column !important;
        }

        .d-flex {
            display: flex !important;
        }

        .justify-content-between {
            justify-content: space-between !important;
        }

        .align-items-center {
            align-items: center !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .border-bottom {
            border-bottom: 1px solid #dee2e6 !important;
        }

        .pt-3 {
            padding-top: 1rem !important;
        }

        .pb-2 {
            padding-bottom: 0.5rem !important;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        .h2 {
            font-size: 1.5rem;
            font-weight: 500;
        }

        .w-100 {
            width: 100% !important;
        }

        .mr-2 {
            margin-right: 0.5rem !important;
        }

        .ml-auto {
            margin-left: auto !important;
        }

        .position-relative {
            position: relative !important;
        }

        .overflow-hidden {
            overflow: hidden !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-weight-bold {
            font-weight: 700 !important;
        }

        .font-weight-500 {
            font-weight: 500 !important;
        }

        .font-weight-600 {
            font-weight: 600 !important;
        }

        .font-weight-700 {
            font-weight: 700 !important;
        }
    </style>
</head>

<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Mobile Menu Toggle -->
            <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Brand -->
            <a class="navbar-brand" href="dashboard_final.php">
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
                            <div class="user-role"><?php echo ucfirst(getCurrentUserRole()); ?></div>
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

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="position-relative" style="height: 100%;">
            <div class="pt-3" style="padding-bottom: 80px;">
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
                        echo "<i class='fa $icon'></i>";
                        echo "$text";
                        echo "</a>";
                        echo "</li>";
                    }
                    ?>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fa fa-sign-out"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <small>
                    RansHotel Admin v2.0<br>
                    <span class="badge badge-success">Online</span>
                </small>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
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
            <div>
                <h1 class="h2">Dashboard</h1>
                <p class="text-muted">Hotel Management Overview</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <span class="badge badge-primary mr-2"><?php echo ucfirst(getCurrentUserRole()); ?></span>
                    <span class="text-muted"><?php echo date('F j, Y'); ?></span>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card">
                    <h3><?php echo $c; ?></h3>
                    <p>Pending Bookings</p>
                    <div class="stats-icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <h3><?php echo $r; ?></h3>
                    <p>Confirmed Bookings</p>
                    <div class="stats-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <h3><?php echo $f; ?></h3>
                    <p>Newsletter Subscribers</p>
                    <div class="stats-icon">
                        <i class="fa fa-envelope"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <h3><?php echo $c + $r; ?></h3>
                    <p>Total Bookings</p>
                    <div class="stats-icon">
                        <i class="fa fa-calendar"></i>
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

        <!-- Recent Bookings and System Status -->
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
    </main>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
    
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('show');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const navbarToggler = document.querySelector('.navbar-toggler');
        
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(event.target) && !navbarToggler.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
        }
    });
    </script>
</body>
</html>
