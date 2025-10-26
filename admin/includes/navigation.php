<?php
/**
 * Professional Navigation Component
 * RansHotel Admin System
 */

if (!function_exists('getNavigationMenu')) {
    include('access_control.php');
}
?>

<!-- Professional Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color); box-shadow: var(--shadow);">
    <div class="container-fluid">
        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Brand -->
        <a class="navbar-brand font-weight-bold" href="dashboard.php">
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
                    <a class="dropdown-item" href="user_settings.php">
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

<!-- Professional Sidebar -->
<div class="container-fluid">
    <div class="row">
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

        <!-- Main Content Area -->
        <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
