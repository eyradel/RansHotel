<?php
/**
 * Unified Layout Helper
 * RansHotel Admin System
 * 
 * Simple functions to include the unified layout on any admin page
 */

// Ensure access control is included for menu generation
if (!function_exists('getNavigationMenu')) {
    include(__DIR__ . '/access_control.php');
}

/**
 * Start admin page with unified layout
 */
function startUnifiedAdminPage($title = 'Admin', $description = 'RansHotel Admin') {
    $embed = isset($_GET['embed']) && $_GET['embed'] == '1';
    // Get user info for display in navbar/sidebar
    $userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
    $userRole = getCurrentUserRole();

    // Determine current page for active menu item
    $currentPage = basename($_SERVER['PHP_SELF']);

    // Start HTML output
    echo "<!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en\">
<head>
    <meta charset=\"utf-8\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />
    <title>" . htmlspecialchars($title) . " - RansHotel</title>
    <meta name=\"description\" content=\"" . htmlspecialchars($description) . "\">
    <meta name=\"author\" content=\"RansHotel\">
    
    <!-- Tailwind CSS -->
    <script src=\"https://cdn.tailwindcss.com\"></script>
    <!-- Component System CSS -->
    <link href=\"../css/components.css\" rel=\"stylesheet\" />
    <!-- FontAwesome Styles-->
    <link href=\"assets/css/font-awesome.css\" rel=\"stylesheet\" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div class=\"min-h-screen bg-gray-50\">
        ";
        if (!$embed) {
            include(__DIR__ . '/navbar.php'); // Include the unified navbar
            include(__DIR__ . '/sidebar.php'); // Include the unified sidebar
            echo "<div class=\"lg:ml-64\" id=\"mainWrapper\">";
        } else {
            echo "<div id=\"mainWrapper\">"; // No left margin when embedded
        }
        echo "
            <main class=\"min-h-screen\">
                <!-- Page-specific content starts here -->";
}

/**
 * End admin page with unified layout
 */
function endUnifiedAdminPage() {
    $embed = isset($_GET['embed']) && $_GET['embed'] == '1';
    echo "
                <!-- Page-specific content ends here -->
            </main>
        </div>
    </div>
    
    <!-- Component System JS -->
    <script src=\"../js/components.js\"></script>
    <script src=\"../js/sidebar-navbar-component.js\"></script>
    
    <!-- Sidebar Toggle Functionality -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const userMenuToggle = document.getElementById('userMenuToggle');
        const userDropdown = document.getElementById('userDropdown');
        const notificationToggle = document.getElementById('notificationToggle');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        // Sidebar toggle functionality
        if (sidebarToggle && sidebar && sidebarOverlay) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Open sidebar
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    sidebarOverlay.classList.remove('hidden');
                } else {
                    // Close sidebar
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    sidebarOverlay.classList.add('hidden');
                }
            });
            
            // Close sidebar when clicking overlay
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                sidebarOverlay.classList.add('hidden');
            });
            
            // Close sidebar when clicking a link on mobile
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) { // lg breakpoint
                        sidebar.classList.add('-translate-x-full');
                        sidebar.classList.remove('translate-x-0');
                        sidebarOverlay.classList.add('hidden');
                    }
                });
            });
        }
        
        // User dropdown toggle functionality
        if (userMenuToggle && userDropdown) {
            userMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close notification dropdown if open
                if (notificationDropdown && !notificationDropdown.classList.contains('hidden')) {
                    notificationDropdown.classList.add('hidden');
                }
                
                // Toggle user dropdown
                userDropdown.classList.toggle('hidden');
            });
        }
        
        // Notification dropdown toggle functionality
        if (notificationToggle && notificationDropdown) {
            notificationToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close user dropdown if open
                if (userDropdown && !userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.add('hidden');
                }
                
                // Toggle notification dropdown
                notificationDropdown.classList.toggle('hidden');
            });
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            // Close user dropdown
            if (userDropdown && userMenuToggle) {
                const userMenu = document.getElementById('userMenu');
                if (!userMenu.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            }
            
            // Close notification dropdown
            if (notificationDropdown && notificationToggle) {
                const notificationMenu = document.getElementById('notificationMenu');
                if (!notificationMenu.contains(e.target)) {
                    notificationDropdown.classList.add('hidden');
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                // Desktop: hide overlay
                if (sidebarOverlay) {
                    sidebarOverlay.classList.add('hidden');
                }
            } else {
                // Mobile: ensure sidebar is closed
                if (sidebar) {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                }
            }
        });
        
        // Close dropdowns when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (userDropdown) {
                    userDropdown.classList.add('hidden');
                }
                if (notificationDropdown) {
                    notificationDropdown.classList.add('hidden');
                }
            }
        });
    });
    </script>
</body>
</html>";
}
?>
