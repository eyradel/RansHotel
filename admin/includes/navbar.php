<?php
/**
 * Unified Navbar Component
 * RansHotel Admin System
 * 
 * This is a single, consistent navbar that can be included on any admin page
 */

// Get user info for display
$userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');

// Page titles mapping
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

$pageTitle = $pageTitles[$currentPage] ?? ucfirst(str_replace('_', ' ', $currentPage));
?>

<!-- Top Navbar with Tailwind CSS -->
<nav class="bg-white shadow-sm border-b border-gray-200 px-4 sm:px-6 lg:px-8" id="navbar">
    <div class="flex items-center justify-between h-16">
        <!-- Left Side - Brand Logo and Sidebar Toggle -->
        <div class="flex items-center space-x-4">
            <button class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden" id="sidebarToggle">
                <i class="fa fa-bars text-lg"></i>
            </button>
            <a href="dashboard_classic.php" class="flex items-center space-x-3 text-gray-900 hover:text-blue-600 transition-colors duration-200">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa fa-hotel text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold">RansHotel</span>
            </a>
        </div>

        <!-- Center - Page Title (Hidden on mobile, shown on desktop) -->
        <div class="hidden lg:block flex-1 text-center">
            <h1 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative" id="notificationMenu">
                <button id="notificationToggle" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                    <i class="fa fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>
                
                <!-- Notification Dropdown -->
                <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden" id="notificationDropdown">
                    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        <span class="text-xs text-gray-500">3 new</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notification Item 1 -->
                        <a href="booking_details.php" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fa fa-calendar text-blue-600"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">New Booking</p>
                                    <p class="text-xs text-gray-600 mt-1">Room 101 booked for 3 nights</p>
                                    <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Notification Item 2 -->
                        <a href="payment.php" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fa fa-money text-green-600"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Payment Received</p>
                                    <p class="text-xs text-gray-600 mt-1">₵2,500 payment confirmed</p>
                                    <p class="text-xs text-gray-400 mt-1">15 minutes ago</p>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Notification Item 3 -->
                        <a href="room.php" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <i class="fa fa-wrench text-amber-600"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Maintenance Alert</p>
                                    <p class="text-xs text-gray-600 mt-1">Room 205 requires attention</p>
                                    <p class="text-xs text-gray-400 mt-1">1 hour ago</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200">
                        <a href="notifications.php" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all notifications</a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="relative" id="userMenu">
                <button class="flex items-center space-x-3 p-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200" id="userMenuToggle">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                        <?php echo strtoupper(substr($userName, 0, 1)); ?>
                    </div>
                    <div class="hidden md:block text-left">
                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($userName); ?></div>
                        <div class="text-xs text-gray-500"><?php echo ucfirst($userRole); ?></div>
                    </div>
                    <i class="fa fa-chevron-down text-xs text-gray-400"></i>
                </button>
                
                <!-- User Dropdown Menu -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 hidden" id="userDropdown">
                    <a href="user_settings.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fa fa-user w-4 mr-3 text-gray-400"></i>
                        <span>Profile</span>
                    </a>
                    <a href="settings.php" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        <i class="fa fa-cog w-4 mr-3 text-gray-400"></i>
                        <span>Settings</span>
                    </a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                        <i class="fa fa-sign-out w-4 mr-3"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
