<?php
/**
 * Access Control System for RansHotel Admin
 * Manages role-based permissions for admin, manager, and staff users
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user']) && !empty($_SESSION['user']);
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['role'] ?? 'staff';
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if current user has admin privileges
 */
function isAdmin() {
    return getCurrentUserRole() === 'admin';
}

/**
 * Check if current user has manager privileges
 */
function isManager() {
    return getCurrentUserRole() === 'manager';
}

/**
 * Check if current user has staff privileges
 */
function isStaff() {
    return getCurrentUserRole() === 'staff';
}

/**
 * Check if current user has admin or manager privileges
 */
function isAdminOrManager() {
    $role = getCurrentUserRole();
    return $role === 'admin' || $role === 'manager';
}

/**
 * Check if user can access a specific feature
 */
function canAccess($feature) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $role = getCurrentUserRole();
    
    // Define permissions for each role
    $permissions = [
        'admin' => [
            'dashboard' => true,
            'room_booking' => true,
            'reservations' => true,
            'notifications' => true,
            'payment' => true,
            'profit' => true,
            'pricing' => true,
            'messages' => true,
            'user_management' => true,
            'settings' => true,
            'room_management' => true,
            'add_room' => true,
            'delete_room' => true,
            'newsletter' => true,
            'system_settings' => true
        ],
        'manager' => [
            'dashboard' => true,
            'room_booking' => true,
            'reservations' => true,
            'notifications' => true,
            'payment' => true,
            'profit' => true,
            'pricing' => true,
            'messages' => true,
            'user_management' => false, // Managers cannot manage users
            'settings' => true,
            'room_management' => true,
            'add_room' => true,
            'delete_room' => true,
            'newsletter' => true,
            'system_settings' => false // Managers cannot change system settings
        ],
        'staff' => [
            'dashboard' => true,
            'room_booking' => true,
            'reservations' => true,
            'notifications' => true,
            'payment' => false, // Staff cannot access payment details
            'profit' => false, // Staff cannot view profit information
            'pricing' => false, // Staff cannot modify pricing
            'messages' => true,
            'user_management' => false,
            'settings' => false,
            'room_management' => false,
            'add_room' => false,
            'delete_room' => false,
            'newsletter' => false,
            'system_settings' => false
        ]
    ];
    
    return isset($permissions[$role][$feature]) ? $permissions[$role][$feature] : false;
}

/**
 * Redirect to access denied page if user doesn't have permission
 */
function requireAccess($feature) {
    if (!canAccess($feature)) {
        header("location: access_denied.php");
        exit();
    }
}

/**
 * Check if user can view sensitive financial information
 */
function canViewFinancialData() {
    return isAdminOrManager();
}

/**
 * Check if user can manage users
 */
function canManageUsers() {
    return isAdmin();
}

/**
 * Check if user can modify system settings
 */
function canModifySystemSettings() {
    return isAdmin();
}

/**
 * Check if user can manage rooms
 */
function canManageRooms() {
    return isAdminOrManager();
}

/**
 * Get navigation menu items based on user role
 */
function getNavigationMenu() {
    $menu = [];
    
    // Dashboard - available to all
    if (canAccess('dashboard')) {
        $menu[] = ['url' => 'dashboard_simple.php', 'icon' => 'fa-dashboard', 'text' => 'Dashboard'];
    }
    
    // Room Booking - available to all
    if (canAccess('room_booking')) {
        $menu[] = ['url' => 'roombook.php', 'icon' => 'fa-bar-chart-o', 'text' => 'Room Booking'];
    }
    
    // Reservations - available to all
    if (canAccess('reservations')) {
        $menu[] = ['url' => 'reservation.php', 'icon' => 'fa-calendar', 'text' => 'Reservations'];
    }
    
    // Notifications - available to all
    if (canAccess('notifications')) {
        $menu[] = ['url' => 'notifications.php', 'icon' => 'fa-bell', 'text' => 'Notifications'];
    }
    
    // Payment - admin and manager only
    if (canAccess('payment')) {
        $menu[] = ['url' => 'payment.php', 'icon' => 'fa-qrcode', 'text' => 'Payment'];
    }
    
    // Profit - admin and manager only
    if (canAccess('profit')) {
        $menu[] = ['url' => 'profit.php', 'icon' => 'fa-qrcode', 'text' => 'Profit'];
    }
    
    // Pricing - admin and manager only
    if (canAccess('pricing')) {
        $menu[] = ['url' => 'pricing.php', 'icon' => 'fa-dollar', 'text' => 'Pricing Management'];
    }
    
    // Messages/Newsletter - available to all
    if (canAccess('messages')) {
        $menu[] = ['url' => 'messages.php', 'icon' => 'fa-desktop', 'text' => 'News Letters'];
    }
    
    // User Management - admin only
    if (canAccess('user_management')) {
        $menu[] = ['url' => 'user_management_pro.php', 'icon' => 'fa-users', 'text' => 'User Management'];
    }
    
    // Room Management - admin and manager only
    if (canAccess('room_management')) {
        $menu[] = ['url' => 'settings.php', 'icon' => 'fa-cog', 'text' => 'Room Management'];
    }
    
    return $menu;
}

/**
 * Display access denied message
 */
function displayAccessDenied() {
    echo '<div class="alert alert-danger">';
    echo '<h4><i class="fa fa-ban"></i> Access Denied</h4>';
    echo '<p>You do not have permission to access this feature. Please contact your administrator.</p>';
    echo '<p><strong>Your Role:</strong> ' . ucfirst(getCurrentUserRole()) . '</p>';
    echo '<a href="home.php" class="btn btn-primary">Return to Dashboard</a>';
    echo '</div>';
}

/**
 * Log access attempt for security auditing
 */
function logAccessAttempt($page, $allowed = false) {
    $logFile = 'logs/access_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    $user = $_SESSION['user'] ?? 'unknown';
    $role = getCurrentUserRole();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $status = $allowed ? 'ALLOWED' : 'DENIED';
    
    $logEntry = "[$timestamp] User: $user ($role) | Page: $page | Status: $status | IP: $ip\n";
    
    // Create logs directory if it doesn't exist
    if (!file_exists('logs')) {
        mkdir('logs', 0755, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

/**
 * Initialize access control for a page
 */
function initAccessControl($requiredFeature = null) {
    // Check if user is logged in
    if (!isLoggedIn()) {
        header("location: index.php");
        exit();
    }
    
    // Check specific feature access if required
    if ($requiredFeature && !canAccess($requiredFeature)) {
        logAccessAttempt($requiredFeature, false);
        header("location: access_denied.php");
        exit();
    }
    
    // Log successful access
    if ($requiredFeature) {
        logAccessAttempt($requiredFeature, true);
    }
}
?>
