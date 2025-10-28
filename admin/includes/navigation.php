<?php
/**
 * Modern Professional Navigation Component
 * RansHotel Admin System
 */

if (!function_exists('getNavigationMenu')) {
    include('access_control.php');
}
?>

<!-- Modern Sidebar Navigation -->
<div class="admin-layout">
    <!-- Sidebar -->
    <nav class="modern-sidebar" id="modernSidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="brand-section">
                <div class="brand-logo">
                    <i class="fa fa-hotel"></i>
                </div>
                <div class="brand-info">
                    <h4 class="brand-name">RansHotel</h4>
                    <span class="brand-subtitle">Admin Panel</span>
                </div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <!-- User Profile Section -->
        <div class="user-profile-section">
            <div class="user-avatar">
                <div class="avatar-circle">
                    <?php 
                    $userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
                    echo strtoupper(substr($userName, 0, 1)); 
                    ?>
                </div>
            </div>
            <div class="user-details">
                <div class="user-name"><?php echo htmlspecialchars($userName); ?></div>
                <div class="user-role"><?php echo ucfirst(getCurrentUserRole()); ?></div>
            </div>
            <div class="user-status">
                <span class="status-indicator online"></span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="sidebar-menu">
            <ul class="nav-menu">
                <?php
                $menu = getNavigationMenu();
                $currentPage = basename($_SERVER['PHP_SELF']);
                
                foreach ($menu as $item) {
                    $isActive = ($currentPage == basename($item['url'])) ? 'active' : '';
                    $icon = $item['icon'];
                    $text = $item['text'];
                    $url = $item['url'];
                    
                    echo "<li class='nav-item $isActive'>";
                    echo "<a class='nav-link' href='$url'>";
                    echo "<div class='nav-icon'>";
                    echo "<i class='fa $icon'></i>";
                    echo "</div>";
                    echo "<span class='nav-text'>$text</span>";
                    echo "<div class='nav-indicator'></div>";
                    echo "</a>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="footer-actions">
                <a href="user_settings.php" class="footer-action" title="Profile">
                    <i class="fa fa-user"></i>
                </a>
                <a href="settings.php" class="footer-action" title="Settings">
                    <i class="fa fa-cog"></i>
                </a>
                <a href="logout.php" class="footer-action logout" title="Logout">
                    <i class="fa fa-sign-out"></i>
                </a>
            </div>
            <div class="footer-info">
                <div class="version-info">
                    <span class="version">v2.0</span>
                    <span class="status online">Online</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="main-content-wrapper">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="page-title">
                    <h1 class="current-page-title">
                        <?php
                        $currentPage = basename($_SERVER['PHP_SELF'], '.php');
                        $pageTitles = [
                            'dashboard_classic' => 'Dashboard',
                            'reservation_classic' => 'Reservations',
                            'booking_details' => 'Booking Details',
                            'room' => 'Room Management',
                            'pricing' => 'Pricing Management',
                            'notifications' => 'Notifications',
                            'user_management' => 'User Management',
                            'payment' => 'Payment Management',
                            'profit' => 'Profit Reports',
                            'settings' => 'Settings',
                            'user_settings' => 'User Settings',
                            'room_delete' => 'Delete Room',
                            'access_denied' => 'Access Denied'
                        ];
                        echo $pageTitles[$currentPage] ?? ucfirst(str_replace('_', ' ', $currentPage));
                        ?>
                    </h1>
                    <p class="page-subtitle">Manage your hotel operations efficiently</p>
                </div>
            </div>
            
            <div class="top-bar-right">
                <div class="top-bar-actions">
                    <div class="notification-bell">
                        <i class="fa fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="user-menu">
                        <div class="user-avatar-small">
                            <?php echo strtoupper(substr($userName, 0, 1)); ?>
                        </div>
                        <div class="user-info">
                            <span class="user-name-small"><?php echo htmlspecialchars($userName); ?></span>
                            <span class="user-role-small"><?php echo ucfirst(getCurrentUserRole()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
