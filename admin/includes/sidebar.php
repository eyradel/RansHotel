<?php
/**
 * Unified Sidebar Component
 * RansHotel Admin System
 * 
 * This is a single, consistent sidebar that can be included on any admin page
 */

// Ensure we have the required functions
if (!function_exists('getCurrentUserRole')) {
    function getCurrentUserRole() {
        return 'Administrator';
    }
}

// Get user info for display
$userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Get navigation menu
if (function_exists('getNavigationMenu')) {
    $menu = getNavigationMenu();
} else {
    // Default menu if getNavigationMenu doesn't exist
    $menu = [
        [
            'text' => 'Dashboard',
            'url' => 'dashboard_classic.php',
            'icon' => 'fa-dashboard'
        ],
        [
            'text' => 'Reservations',
            'url' => 'reservation_classic.php',
            'icon' => 'fa-calendar'
        ],
        [
            'text' => 'Bookings',
            'url' => 'booking_details.php',
            'icon' => 'fa-book'
        ],
        [
            'text' => 'Rooms',
            'url' => 'room.php',
            'icon' => 'fa-bed'
        ],
        [
            'text' => 'Pricing',
            'url' => 'pricing.php',
            'icon' => 'fa-tags'
        ],
        [
            'text' => 'Users',
            'url' => 'user_management.php',
            'icon' => 'fa-users'
        ],
        [
            'text' => 'Notifications',
            'url' => 'notifications.php',
            'icon' => 'fa-bell'
        ],
        [
            'text' => 'Payments',
            'url' => 'payment.php',
            'icon' => 'fa-credit-card'
        ],
        [
            'text' => 'Reports',
            'url' => 'profit.php',
            'icon' => 'fa-line-chart'
        ],
        [
            'text' => 'Settings',
            'url' => 'settings.php',
            'icon' => 'fa-cog'
        ]
    ];
}
?>

<!-- Sidebar Overlay for Mobile -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebarOverlay"></div>

<!-- Collapsible Sidebar with Tailwind CSS -->
<nav class="fixed top-16 left-0 z-40 h-[calc(100vh-4rem)] w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:top-0 lg:h-screen flex flex-col" id="sidebar">
    <!-- User Profile Section -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center text-lg font-bold">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate"><?php echo htmlspecialchars($userName); ?></div>
                <div class="text-xs text-gray-500 truncate"><?php echo ucfirst($userRole); ?></div>
            </div>
            <div class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <?php foreach ($menu as $item): ?>
                <?php 
                $isActive = ($currentPage == basename($item['url'], '.php')) ? true : false;
                $icon = $item['icon'];
                $text = $item['text'];
                $url = $item['url'];
                ?>
                <li>
                    <a href="<?php echo $url; ?>" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo $isActive ? 'bg-blue-50 text-blue-700 border-r-2 border-blue-700' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900'; ?>">
                        <i class="fa <?php echo $icon; ?> w-5 h-5 mr-3 <?php echo $isActive ? 'text-blue-700' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                        <span><?php echo $text; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="mt-auto border-t border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-2">
                <a href="user_settings.php" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200" title="Profile">
                    <i class="fa fa-user text-sm"></i>
                </a>
                <a href="settings.php" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200" title="Settings">
                    <i class="fa fa-cog text-sm"></i>
                </a>
                <a href="logout.php" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Logout">
                    <i class="fa fa-sign-out text-sm"></i>
                </a>
            </div>
        </div>
        <div class="text-xs text-gray-500 flex items-center justify-between">
            <span>v1.0</span>
            <span class="flex items-center">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                Online
            </span>
        </div>
    </div>
</nav>
