# Sidebar + Navbar Navigation System

## Overview
A comprehensive, responsive navigation system combining a collapsible sidebar with a top navbar for the RansHotel admin system. This system provides optimal user experience across all device sizes with smooth animations and intuitive interactions.

## Features

### ✅ Collapsible Sidebar
- **Smooth Animations**: CSS transitions and JavaScript animations
- **State Persistence**: Remembers sidebar state between page loads
- **Responsive Behavior**: Adapts to different screen sizes
- **Active Page Highlighting**: Automatically highlights current page
- **User Profile Section**: Displays user info and status
- **Footer Actions**: Quick access to profile, settings, and logout

### ✅ Top Navbar
- **Brand Logo**: Hotel branding with logo
- **Page Title**: Dynamic page title display
- **Notification Bell**: With badge count and dropdown
- **User Menu**: Profile dropdown with actions
- **Mobile Toggle**: Hamburger menu for mobile devices

### ✅ Mobile Navigation
- **Slide-out Menu**: Full-screen mobile navigation
- **Touch-friendly**: Optimized for touch interaction
- **Overlay Background**: Dark overlay when menu is open
- **Smooth Animations**: Slide-in/out animations

### ✅ Responsive Design
- **Desktop (>768px)**: Full sidebar with toggle functionality
- **Tablet (≤768px)**: Sidebar becomes overlay
- **Mobile (≤480px)**: Mobile menu with three-dot toggle

## File Structure

```
css/components/_sidebar_navbar.css     # Main navigation styles
js/sidebar-navbar-component.js         # JavaScript component
admin/includes/navigation_sidebar.php  # PHP navigation template
admin/sidebar_demo.php                 # Demo page
```

## Usage

### 1. Include the Component System
```html
<link href="css/components.css" rel="stylesheet" />
<script src="js/components.js"></script>
<script src="js/sidebar-navbar-component.js"></script>
```

### 2. Include the Navigation
```php
<?php include('includes/navigation_sidebar.php'); ?>
```

### 3. Wrap Content
```html
<div class="main-wrapper">
    <main class="main-content">
        <!-- Your page content here -->
    </main>
</div>
```

## HTML Structure

### Navbar
```html
<nav class="navbar">
    <!-- Brand -->
    <a href="dashboard.php" class="navbar-brand">
        <div class="navbar-logo">
            <i class="fa fa-hotel"></i>
        </div>
        <span>RansHotel</span>
    </a>

    <!-- Left Side - Sidebar Toggle -->
    <div class="navbar-nav navbar-nav-left">
        <button class="navbar-toggle sidebar-toggle" id="sidebarToggle">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    <!-- Center - Page Title -->
    <div class="navbar-nav navbar-nav-center">
        <div class="page-title">
            <h1 class="current-page-title">Dashboard</h1>
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="navbar-nav navbar-nav-right">
        <!-- Notifications -->
        <div class="navbar-notification">
            <i class="fa fa-bell"></i>
            <span class="navbar-notification-badge">3</span>
        </div>

        <!-- User Menu -->
        <div class="navbar-user">
            <div class="navbar-user-avatar">A</div>
            <div class="navbar-user-info">
                <div class="navbar-user-name">Admin User</div>
                <div class="navbar-user-role">Administrator</div>
            </div>
        </div>

        <!-- Mobile Toggle -->
        <button class="navbar-toggle mobile-toggle" id="mobileToggle">
            <i class="fa fa-ellipsis-v"></i>
        </button>
    </div>
</nav>
```

### Sidebar
```html
<nav class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <div class="sidebar-logo">
                <i class="fa fa-hotel"></i>
            </div>
            <div class="sidebar-brand-text">
                <h4>RansHotel</h4>
                <span>Admin Panel</span>
            </div>
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">A</div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">Admin User</div>
            <div class="sidebar-user-role">Administrator</div>
        </div>
        <div class="sidebar-user-status">
            <span class="status-indicator online"></span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu">
        <ul class="sidebar-nav">
            <li class="sidebar-item active">
                <a href="dashboard.php" class="sidebar-link">
                    <div class="sidebar-icon">
                        <i class="fa fa-dashboard"></i>
                    </div>
                    <span class="sidebar-text">Dashboard</span>
                    <div class="sidebar-indicator"></div>
                </a>
            </li>
            <!-- More navigation items -->
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-actions">
            <a href="user_settings.php" class="sidebar-footer-action" title="Profile">
                <i class="fa fa-user"></i>
            </a>
            <a href="settings.php" class="sidebar-footer-action" title="Settings">
                <i class="fa fa-cog"></i>
            </a>
            <a href="logout.php" class="sidebar-footer-action" title="Logout">
                <i class="fa fa-sign-out"></i>
            </a>
        </div>
        <div class="sidebar-footer-info">
            <div class="version-info">
                <span class="version">v2.0</span>
                <span class="status online">Online</span>
            </div>
        </div>
    </div>
</nav>
```

### Mobile Navigation
```html
<!-- Mobile Navigation Overlay -->
<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

<!-- Mobile Navigation Menu -->
<div class="mobile-nav" id="mobileNav">
    <div class="mobile-nav-header">
        <h4>Menu</h4>
        <button class="mobile-nav-close" id="mobileNavClose">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="mobile-nav-content">
        <ul class="mobile-nav-list">
            <li class="mobile-nav-item active">
                <a href="dashboard.php" class="mobile-nav-link">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- More mobile navigation items -->
        </ul>
    </div>
</div>
```

## CSS Classes

### Navbar Classes
- `.navbar` - Main navbar container
- `.navbar-brand` - Brand/logo section
- `.navbar-nav` - Navigation container
- `.navbar-nav-left` - Left navigation section
- `.navbar-nav-center` - Center navigation section
- `.navbar-nav-right` - Right navigation section
- `.navbar-toggle` - Toggle button
- `.sidebar-toggle` - Sidebar toggle button
- `.mobile-toggle` - Mobile menu toggle button

### Sidebar Classes
- `.sidebar` - Main sidebar container
- `.sidebar.open` - Open sidebar state
- `.sidebar-header` - Sidebar header section
- `.sidebar-brand` - Brand section in sidebar
- `.sidebar-logo` - Logo in sidebar
- `.sidebar-brand-text` - Brand text in sidebar
- `.sidebar-close` - Close button
- `.sidebar-user` - User profile section
- `.sidebar-user-avatar` - User avatar
- `.sidebar-user-info` - User information
- `.sidebar-user-status` - User status indicator
- `.sidebar-menu` - Navigation menu container
- `.sidebar-nav` - Navigation list
- `.sidebar-item` - Navigation item
- `.sidebar-item.active` - Active navigation item
- `.sidebar-link` - Navigation link
- `.sidebar-icon` - Navigation icon
- `.sidebar-text` - Navigation text
- `.sidebar-indicator` - Active indicator
- `.sidebar-footer` - Sidebar footer
- `.sidebar-footer-actions` - Footer action buttons
- `.sidebar-footer-action` - Individual footer action

### Mobile Navigation Classes
- `.mobile-nav-overlay` - Mobile navigation overlay
- `.mobile-nav` - Mobile navigation container
- `.mobile-nav.open` - Open mobile navigation state
- `.mobile-nav-header` - Mobile navigation header
- `.mobile-nav-close` - Mobile navigation close button
- `.mobile-nav-content` - Mobile navigation content
- `.mobile-nav-list` - Mobile navigation list
- `.mobile-nav-item` - Mobile navigation item
- `.mobile-nav-item.active` - Active mobile navigation item
- `.mobile-nav-link` - Mobile navigation link

### Main Content Classes
- `.main-wrapper` - Main content wrapper
- `.main-content` - Main content area

## JavaScript API

### Auto-Initialization
The sidebar navbar automatically initializes when the page loads:
```javascript
// Automatically called on DOMContentLoaded
new SidebarNavbarComponent(sidebar);
```

### Manual Initialization
```javascript
const sidebar = document.querySelector('.sidebar');
const sidebarComponent = new SidebarNavbarComponent(sidebar, {
    sidebarBreakpoint: 768,
    autoCloseOnMobile: true,
    showActivePage: true,
    enableNotifications: true,
    persistSidebarState: true,
    sidebarKey: 'sidebarOpen'
});
```

### Public Methods
```javascript
// Toggle sidebar
sidebarComponent.toggleSidebar();

// Open/close sidebar
sidebarComponent.openSidebar();
sidebarComponent.closeSidebar();

// Check sidebar state
sidebarComponent.isSidebarOpen();

// Toggle mobile navigation
sidebarComponent.toggleMobileNav();

// Open/close mobile navigation
sidebarComponent.openMobileNav();
sidebarComponent.closeMobileNav();

// Check mobile navigation state
sidebarComponent.isMobileNavOpen();

// Add notification
sidebarComponent.addNotification('Title', 'Message', 'info');

// Update notification count
sidebarComponent.updateNotificationCount(5);

// Set user information
sidebarComponent.setUserInfo('John Doe', 'Administrator');

// Save/load sidebar state
sidebarComponent.saveSidebarState(true);
sidebarComponent.getSidebarState();
```

## Responsive Behavior

### Desktop (>768px)
- **Sidebar**: Can be toggled open/closed
- **Navbar**: Full navigation with all elements visible
- **State Persistence**: Sidebar state is remembered
- **Page Title**: Displayed in navbar center

### Tablet (≤768px)
- **Sidebar**: Becomes overlay when opened
- **Navbar**: Page title moves to center
- **Auto-close**: Sidebar closes when clicking outside
- **Touch-friendly**: Optimized for touch interaction

### Mobile (≤480px)
- **Sidebar**: Hidden, replaced by mobile menu
- **Navbar**: Simplified with mobile toggle
- **Mobile Menu**: Full-screen slide-out menu
- **User Info**: Hidden in navbar, shown in mobile menu

## Customization

### Colors
The navigation uses CSS variables for easy customization:
```css
:root {
    --classic-navy: #1a365d;
    --classic-gold: #d4af37;
    --classic-white: #ffffff;
    --classic-gray: #6b7280;
}
```

### Spacing
Adjust spacing using CSS variables:
```css
:root {
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-6: 1.5rem;
}
```

### Breakpoints
Modify responsive breakpoints:
```css
@media (max-width: 768px) {
    /* Tablet styles */
}

@media (max-width: 480px) {
    /* Mobile styles */
}
```

### Animations
Customize animation duration:
```css
:root {
    --transition-fast: 0.15s ease;
    --transition-normal: 0.3s ease;
    --transition-slow: 0.5s ease;
}
```

## Integration with PHP

### Dynamic Menu Generation
The navigation automatically generates menu items based on user permissions:
```php
$menu = getNavigationMenu();
foreach ($menu as $item) {
    $isActive = ($currentPage == basename($item['url'])) ? 'active' : '';
    // Generate navigation HTML
}
```

### User Information
User data is pulled from PHP sessions:
```php
$userName = $_SESSION['full_name'] ?? $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();
```

### Page Titles
Dynamic page titles based on current page:
```php
$pageTitles = [
    'dashboard_classic' => 'Dashboard',
    'reservation_classic' => 'Reservations',
    'room' => 'Room Management',
    // More page titles
];
echo $pageTitles[$currentPage] ?? ucfirst(str_replace('_', ' ', $currentPage));
```

## Accessibility Features

### Keyboard Navigation
- Tab navigation through all interactive elements
- Enter/Space activation for buttons
- Escape key closes mobile menu and sidebar

### Screen Reader Support
- Proper ARIA labels
- Semantic HTML structure
- Descriptive link text

### Focus Management
- Visible focus indicators
- Logical tab order
- Focus trapping in mobile menu

## Performance Optimizations

### CSS
- Efficient selectors
- Minimal repaints
- Hardware-accelerated animations
- CSS custom properties for theming

### JavaScript
- Event delegation
- Debounced resize handlers
- Minimal DOM queries
- State persistence with localStorage

### Responsive Images
- Optimized icon fonts
- Scalable vector graphics
- Minimal HTTP requests

## Browser Support

### Modern Browsers
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

### Mobile Browsers
- iOS Safari 12+
- Chrome Mobile 60+
- Samsung Internet 8+

## Troubleshooting

### Common Issues

#### Sidebar Not Opening
```javascript
// Check if JavaScript is loaded
console.log(typeof SidebarNavbarComponent);

// Check if sidebar element exists
console.log(document.querySelector('.sidebar'));
```

#### Active Page Not Highlighting
```php
// Ensure current page detection works
echo "Current page: " . basename($_SERVER['PHP_SELF']);
```

#### Styling Issues
```css
/* Check if component CSS is loaded */
.sidebar {
    /* Should have fixed positioning */
    position: fixed;
}
```

### Debug Mode
Enable debug mode for troubleshooting:
```javascript
const sidebar = new SidebarNavbarComponent(document.querySelector('.sidebar'), {
    debug: true
});
```

## Examples

### Basic Implementation
See `admin/sidebar_demo.php` for a complete working example.

### Custom Styling
```css
/* Custom sidebar width */
.sidebar {
    width: 320px;
}

/* Custom navbar height */
.navbar {
    height: 80px;
}

/* Custom brand colors */
.navbar-brand {
    color: #custom-color;
}
```

### Custom JavaScript
```javascript
// Add custom click handlers
document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Custom logic here
    });
});
```

## Future Enhancements

### Planned Features
- [ ] Dark mode toggle
- [ ] Breadcrumb navigation
- [ ] Search functionality
- [ ] Multi-level dropdown menus
- [ ] Customizable themes
- [ ] Animation preferences
- [ ] Keyboard shortcuts
- [ ] Voice navigation

### Performance Improvements
- [ ] Lazy loading for mobile menu
- [ ] Virtual scrolling for large menus
- [ ] Service worker caching
- [ ] Progressive enhancement

## Support

For issues or questions about the sidebar navbar system:
1. Check this documentation
2. Review the demo page (`admin/sidebar_demo.php`)
3. Test in different browsers
4. Check console for JavaScript errors
5. Verify CSS is loading correctly
