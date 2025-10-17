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
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .col-md-3 {
            flex: 0 0 25%;
            padding: 10px;
        }
        
        .col-md-6 {
            flex: 0 0 50%;
            padding: 10px;
        }
        
        .col-md-12 {
            flex: 0 0 100%;
            padding: 10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stats-card h3 {
            font-size: 36px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .stats-card p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        
        .card-header h3 {
            margin: 0;
            font-size: 18px;
            color: #2c3e50;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            margin: 5px;
        }
        
        .btn:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-success:hover {
            background-color: #1e7e34;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
            color: #212529;
        }
        
        .btn-secondary {
            background-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #545b62;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 4px;
            color: white;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-primary {
            background-color: #007bff;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .d-flex {
            display: flex;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .mb-3 {
            margin-bottom: 15px;
        }
        
        .mb-4 {
            margin-bottom: 20px;
        }
        
        .mr-2 {
            margin-right: 10px;
        }
        
        .w-100 {
            width: 100%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .border-bottom {
            border-bottom: 1px solid #dee2e6;
        }
        
        .pt-3 {
            padding-top: 15px;
        }
        
        .pb-2 {
            padding-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .col-md-3,
            .col-md-6 {
                flex: 0 0 100%;
            }
            
            .container {
                padding: 10px;
            }
            
            .stats-card h3 {
                font-size: 28px;
            }
            
            .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation -->
    <div class="navbar">
        <div class="d-flex justify-content-between align-items-center">
            <h1>RansHotel Admin Dashboard</h1>
            <div>
                <span class="badge badge-primary mr-2"><?php echo ucfirst(getCurrentUserRole()); ?></span>
                <span class="text-muted"><?php echo date('F j, Y'); ?></span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
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

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $c; ?></h3>
                    <p>Pending Bookings</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $r; ?></h3>
                    <p>Confirmed Bookings</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $f; ?></h3>
                    <p>Newsletter Subscribers</p>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="stats-card">
                    <h3><?php echo $c + $r; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <?php if (canAccess('room_booking')): ?>
                        <a href="roombook.php" class="btn">
                            <i class="fa fa-bed mr-2"></i>Manage Bookings
                        </a>
                        <?php endif; ?>
                        
                        <?php if (canAccess('notifications')): ?>
                        <a href="notifications.php" class="btn btn-success">
                            <i class="fa fa-bell mr-2"></i>Send Notifications
                        </a>
                        <?php endif; ?>
                        
                        <?php if (canAccess('messages')): ?>
                        <a href="messages.php" class="btn btn-warning">
                            <i class="fa fa-envelope mr-2"></i>Newsletter
                        </a>
                        <?php endif; ?>
                        
                        <?php if (canAccess('user_management')): ?>
                        <a href="user_management_pro.php" class="btn btn-secondary">
                            <i class="fa fa-users mr-2"></i>User Management
                        </a>
                        <?php endif; ?>
                        
                        <a href="logout.php" class="btn" style="background-color: #dc3545;">
                            <i class="fa fa-sign-out mr-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Bookings</h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
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
                                    echo "<td><a href='roombook.php?rid=" . $trow['id'] . "' class='btn btn-sm'>Manage</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
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

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
