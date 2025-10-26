<?php
include('db.php');
include('includes/access_control.php');

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login_improved.php');
    exit();
}

// Get user info
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RansHotel Admin</title>
    <meta name="description" content="RansHotel Admin Dashboard - Manage your hotel operations">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Georgia:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Classic Design System -->
    <link href="../css/classic-design-system.css" rel="stylesheet">
    
    <style>
        /* Admin-specific styles */
        .admin-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
            background: var(--classic-cream);
        }
        
        .sidebar {
            background: var(--classic-navy);
            color: var(--classic-white);
            padding: var(--space-6) 0;
            box-shadow: var(--shadow-lg);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 0 var(--space-6) var(--space-8);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: var(--space-6);
        }
        
        .sidebar-brand {
            font-family: 'Playfair Display', serif;
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--classic-gold);
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .sidebar-nav {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .sidebar-nav li {
            margin: 0;
        }
        
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: var(--space-4) var(--space-6);
            color: var(--classic-white);
            text-decoration: none;
            transition: all var(--transition-fast);
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(212, 175, 55, 0.1);
            color: var(--classic-gold);
            border-left-color: var(--classic-gold);
        }
        
        .sidebar-nav i {
            width: 20px;
            margin-right: var(--space-3);
            text-align: center;
        }
        
        .main-content {
            padding: var(--space-8);
            overflow-y: auto;
        }
        
        .top-bar {
            background: var(--classic-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: var(--space-6);
            margin-bottom: var(--space-8);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: var(--font-size-3xl);
            color: var(--classic-navy);
            margin: 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: var(--space-4);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--classic-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--classic-navy);
            font-weight: 600;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--space-6);
            margin-bottom: var(--space-8);
        }
        
        .stat-card {
            background: var(--classic-white);
            border-radius: var(--radius-xl);
            padding: var(--space-8);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--classic-gray-light);
            transition: all var(--transition-normal);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: var(--font-size-2xl);
            margin-bottom: var(--space-4);
        }
        
        .stat-icon.primary {
            background: rgba(26, 54, 93, 0.1);
            color: var(--classic-navy);
        }
        
        .stat-icon.success {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .stat-icon.info {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .stat-value {
            font-size: var(--font-size-3xl);
            font-weight: 700;
            color: var(--classic-navy);
            margin-bottom: var(--space-2);
        }
        
        .stat-label {
            color: var(--classic-gray);
            font-weight: 500;
            text-transform: uppercase;
            font-size: var(--font-size-sm);
            letter-spacing: 0.05em;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: var(--space-8);
        }
        
        .recent-bookings {
            background: var(--classic-white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }
        
        .card-header {
            background: var(--classic-navy);
            color: var(--classic-white);
            padding: var(--space-6);
            border-bottom: 3px solid var(--classic-gold);
        }
        
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: var(--font-size-xl);
            margin: 0;
            color: var(--classic-gold);
        }
        
        .booking-item {
            padding: var(--space-5);
            border-bottom: 1px solid var(--classic-gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all var(--transition-fast);
        }
        
        .booking-item:hover {
            background: var(--classic-gray-light);
        }
        
        .booking-item:last-child {
            border-bottom: none;
        }
        
        .booking-info h4 {
            margin: 0 0 var(--space-1) 0;
            color: var(--classic-navy);
            font-size: var(--font-size-base);
        }
        
        .booking-info p {
            margin: 0;
            color: var(--classic-gray);
            font-size: var(--font-size-sm);
        }
        
        .booking-status {
            padding: var(--space-1) var(--space-3);
            border-radius: var(--radius-full);
            font-size: var(--font-size-xs);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-confirmed {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }
        
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }
        
        .quick-actions {
            background: var(--classic-white);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: var(--space-6);
        }
        
        .action-button {
            display: flex;
            align-items: center;
            width: 100%;
            padding: var(--space-4);
            margin-bottom: var(--space-3);
            background: var(--classic-white);
            border: 2px solid var(--classic-gray-light);
            border-radius: var(--radius-lg);
            color: var(--classic-navy);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-fast);
        }
        
        .action-button:hover {
            border-color: var(--classic-gold);
            color: var(--classic-gold);
            transform: translateX(4px);
        }
        
        .action-button i {
            margin-right: var(--space-3);
            width: 20px;
            text-align: center;
        }
        
        .action-button:last-child {
            margin-bottom: 0;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .admin-layout {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: -280px;
                z-index: var(--z-modal);
                transition: left var(--transition-normal);
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .main-content {
                padding: var(--space-4);
            }
            
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .top-bar {
                flex-direction: column;
                gap: var(--space-4);
                text-align: center;
            }
            
            .page-title {
                font-size: var(--font-size-2xl);
            }
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: var(--classic-navy);
            color: var(--classic-white);
            border: none;
            padding: var(--space-3);
            border-radius: var(--radius-md);
            cursor: pointer;
            position: fixed;
            top: var(--space-4);
            left: var(--space-4);
            z-index: var(--z-fixed);
        }
        
        @media (max-width: 1024px) {
            .mobile-menu-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fa fa-bars"></i>
    </button>
    
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="dashboard_classic.php" class="sidebar-brand">RansHotel</a>
            </div>
            
            <ul class="sidebar-nav">
                <li><a href="dashboard_classic.php" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="booking_details.php"><i class="fa fa-calendar"></i> Bookings</a></li>
                <li><a href="room.php"><i class="fa fa-bed"></i> Rooms</a></li>
                <li><a href="pricing.php"><i class="fa fa-tags"></i> Pricing</a></li>
                <li><a href="user_settings.php"><i class="fa fa-users"></i> Users</a></li>
                <li><a href="messages.php"><i class="fa fa-envelope"></i> Messages</a></li>
                <li><a href="newsletter.php"><i class="fa fa-newspaper-o"></i> Newsletter</a></li>
                <li><a href="settings.php"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <h1 class="page-title">Dashboard</h1>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--classic-navy);"><?php echo htmlspecialchars($user['username']); ?></div>
                        <div style="font-size: var(--font-size-sm); color: var(--classic-gray);">Administrator</div>
                    </div>
                </div>
            </div>
            
            <!-- Statistics Grid -->
            <div class="stats-grid">
                <?php
                // Get statistics
                $totalRooms = mysqli_query($con, "SELECT COUNT(*) as count FROM room")->fetch_assoc()['count'];
                $totalBookings = mysqli_query($con, "SELECT COUNT(*) as count FROM roombook")->fetch_assoc()['count'];
                $pendingBookings = mysqli_query($con, "SELECT COUNT(*) as count FROM roombook WHERE stat = 'Pending'")->fetch_assoc()['count'];
                $confirmedBookings = mysqli_query($con, "SELECT COUNT(*) as count FROM roombook WHERE stat = 'Conform'")->fetch_assoc()['count'];
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fa fa-bed"></i>
                    </div>
                    <div class="stat-value"><?php echo $totalRooms; ?></div>
                    <div class="stat-label">Total Rooms</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stat-value"><?php echo $confirmedBookings; ?></div>
                    <div class="stat-label">Confirmed Bookings</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon warning">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="stat-value"><?php echo $pendingBookings; ?></div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stat-value"><?php echo $totalBookings; ?></div>
                    <div class="stat-label">Total Bookings</div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Bookings -->
                <div class="recent-bookings">
                    <div class="card-header">
                        <h2 class="card-title">Recent Bookings</h2>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        <?php
                        $recentBookings = mysqli_query($con, "SELECT * FROM roombook ORDER BY id DESC LIMIT 5");
                        
                        if (mysqli_num_rows($recentBookings) > 0) {
                            while ($booking = mysqli_fetch_assoc($recentBookings)) {
                                $statusClass = $booking['stat'] == 'Conform' ? 'status-confirmed' : 'status-pending';
                                $statusText = $booking['stat'] == 'Conform' ? 'Confirmed' : 'Pending';
                                
                                echo "
                                <div class='booking-item'>
                                    <div class='booking-info'>
                                        <h4>{$booking['Title']} {$booking['FName']} {$booking['LName']}</h4>
                                        <p>{$booking['TRoom']} • {$booking['cin']} to {$booking['cout']}</p>
                                    </div>
                                    <span class='booking-status {$statusClass}'>{$statusText}</span>
                                </div>
                                ";
                            }
                        } else {
                            echo "<div style='padding: var(--space-8); text-align: center; color: var(--classic-gray);'>No recent bookings found.</div>";
                        }
                        ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3 style="margin-bottom: var(--space-6); color: var(--classic-navy); font-family: 'Playfair Display', serif;">Quick Actions</h3>
                    
                    <a href="reservation_classic.php" class="action-button">
                        <i class="fa fa-plus"></i>
                        New Reservation
                    </a>
                    
                    <a href="room.php" class="action-button">
                        <i class="fa fa-bed"></i>
                        Manage Rooms
                    </a>
                    
                    <a href="pricing.php" class="action-button">
                        <i class="fa fa-tags"></i>
                        Update Pricing
                    </a>
                    
                    <a href="messages.php" class="action-button">
                        <i class="fa fa-envelope"></i>
                        View Messages
                    </a>
                    
                    <a href="user_settings.php" class="action-button">
                        <i class="fa fa-users"></i>
                        Manage Users
                    </a>
                    
                    <a href="settings.php" class="action-button">
                        <i class="fa fa-cog"></i>
                        Settings
                    </a>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 1024 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
        
        // Auto-refresh statistics every 30 seconds
        setInterval(function() {
            // You can implement AJAX refresh here if needed
        }, 30000);
    </script>
</body>
</html>
