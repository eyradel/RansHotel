<?php
/**
 * Modern Navbar Navigation Component
 * RansHotel Admin System
 */

if (!function_exists('getNavigationMenu')) {
    include('access_control.php');
}

$userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();
?>

<!-- Modern Navbar -->
<nav class="navbar">
    <!-- Brand -->
    <a href="dashboard_classic.php" class="navbar-brand">
        <div class="navbar-logo">
            <i class="fa fa-hotel"></i>
        </div>
        <span>RansHotel</span>
    </a>

    <!-- Desktop Navigation -->
    <div class="navbar-nav">
        <?php
        $menu = getNavigationMenu();
        $currentPage = basename($_SERVER['PHP_SELF']);
        
        foreach ($menu as $item) {
            $isActive = ($currentPage == basename($item['url'])) ? 'active' : '';
            $icon = $item['icon'];
            $text = $item['text'];
            $url = $item['url'];
            
            echo "<div class='navbar-item'>";
            echo "<a href='$url' class='navbar-link $isActive'>";
            echo "<i class='fa $icon'></i>";
            echo "<span class='d-none d-md-inline'>$text</span>";
            echo "</a>";
            echo "</div>";
        }
        ?>
    </div>

    <!-- Right Side Actions -->
    <div class="navbar-nav">
        <!-- Notifications -->
        <div class="navbar-notification">
            <i class="fa fa-bell"></i>
            <span class="navbar-notification-badge">3</span>
        </div>

        <!-- User Menu -->
        <div class="navbar-user">
            <div class="navbar-user-avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>
            <div class="navbar-user-info d-none d-md-block">
                <div class="navbar-user-name"><?php echo htmlspecialchars($userName); ?></div>
                <div class="navbar-user-role"><?php echo ucfirst($userRole); ?></div>
            </div>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggle">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Mobile Navigation -->
<div class="navbar-mobile">
    <div class="navbar-mobile-nav">
        <?php
        foreach ($menu as $item) {
            $isActive = ($currentPage == basename($item['url'])) ? 'active' : '';
            $icon = $item['icon'];
            $text = $item['text'];
            $url = $item['url'];
            
            echo "<a href='$url' class='navbar-mobile-link $isActive'>";
            echo "<i class='fa $icon'></i>";
            echo "<span>$text</span>";
            echo "</a>";
        }
        ?>
        
        <!-- Mobile User Actions -->
        <div class="navbar-mobile-nav">
            <a href="user_settings.php" class="navbar-mobile-link">
                <i class="fa fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="settings.php" class="navbar-mobile-link">
                <i class="fa fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="logout.php" class="navbar-mobile-link">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<!-- Page Content Wrapper -->
<div class="page-content">

