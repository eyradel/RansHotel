# Navbar Component Documentation

## Overview
The Navbar Component is a responsive, modern navigation bar designed for the RansHotel admin system. It provides a clean, professional interface that adapts seamlessly across all device sizes.

## Features

### ✅ Responsive Design
- **Desktop (>768px)**: Full navigation with text labels
- **Tablet (≤768px)**: Icon-only navigation with mobile menu
- **Mobile (≤480px)**: Optimized for touch interaction

### ✅ Interactive Elements
- **Active Page Highlighting**: Automatically highlights current page
- **Mobile Hamburger Menu**: Smooth slide-down animation
- **Notification Bell**: With badge count and dropdown
- **User Profile**: Avatar and role display
- **Smooth Animations**: CSS transitions and JavaScript animations

### ✅ Component Architecture
- **Modular CSS**: Separate component file
- **JavaScript Component**: Auto-initialization and event handling
- **PHP Integration**: Dynamic menu generation
- **Accessibility**: Proper ARIA labels and keyboard navigation

## File Structure

```
css/components/_navbar.css          # Navbar styles
js/navbar-component.js              # Navbar JavaScript component
admin/includes/navigation_navbar.php # PHP navigation template
```

## Usage

### 1. Include the Component System
```html
<link href="css/components.css" rel="stylesheet" />
<script src="js/components.js"></script>
<script src="js/navbar-component.js"></script>
```

### 2. Include the Navigation
```php
<?php include('includes/navigation_navbar.php'); ?>
```

### 3. Wrap Content
```html
<div class="page-content">
    <!-- Your page content here -->
</div>
```

## HTML Structure

### Desktop Navigation
```html
<nav class="navbar">
    <!-- Brand -->
    <a href="dashboard.php" class="navbar-brand">
        <div class="navbar-logo">
            <i class="fa fa-hotel"></i>
        </div>
        <span>RansHotel</span>
    </a>

    <!-- Navigation Links -->
    <div class="navbar-nav">
        <div class="navbar-item">
            <a href="dashboard.php" class="navbar-link active">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <!-- More navigation items -->
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
            <div class="navbar-user-avatar">A</div>
            <div class="navbar-user-info">
                <div class="navbar-user-name">Admin User</div>
                <div class="navbar-user-role">Administrator</div>
            </div>
        </div>

        <!-- Mobile Toggle -->
        <button class="navbar-toggle">
            <i class="fa fa-bars"></i>
        </button>
    </div>
</nav>
```

### Mobile Navigation
```html
<div class="navbar-mobile">
    <div class="navbar-mobile-nav">
        <a href="dashboard.php" class="navbar-mobile-link active">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
        <!-- More mobile links -->
    </div>
</div>
```

## CSS Classes

### Main Container
- `.navbar` - Main navbar container
- `.navbar-brand` - Brand/logo section
- `.navbar-nav` - Navigation container
- `.navbar-mobile` - Mobile navigation overlay

### Navigation Items
- `.navbar-item` - Individual navigation item container
- `.navbar-link` - Navigation link
- `.navbar-link.active` - Active navigation link
- `.navbar-mobile-link` - Mobile navigation link

### User Elements
- `.navbar-user` - User profile container
- `.navbar-user-avatar` - User avatar circle
- `.navbar-user-info` - User information container
- `.navbar-user-name` - User name display
- `.navbar-user-role` - User role display

### Notifications
- `.navbar-notification` - Notification bell container
- `.navbar-notification-badge` - Notification count badge

### Interactive Elements
- `.navbar-toggle` - Mobile menu toggle button

## JavaScript API

### Auto-Initialization
The navbar automatically initializes when the page loads:
```javascript
// Automatically called on DOMContentLoaded
new NavbarComponent(navbar);
```

### Manual Initialization
```javascript
const navbar = document.querySelector('.navbar');
const navbarComponent = new NavbarComponent(navbar, {
    mobileBreakpoint: 768,
    autoCloseOnMobile: true,
    showActivePage: true,
    enableNotifications: true
});
```

### Public Methods
```javascript
// Toggle mobile menu
navbarComponent.toggleMobile();

// Add notification
navbarComponent.addNotification('Title', 'Message', 'info');

// Update notification count
navbarComponent.updateNotificationCount(5);

// Set user information
navbarComponent.setUserInfo('John Doe', 'Administrator');
```

## Responsive Behavior

### Desktop (>768px)
- Full navigation with text labels
- User info displayed
- Notification bell visible
- No mobile menu toggle

### Tablet (≤768px)
- Icon-only navigation
- User info hidden
- Mobile menu toggle visible
- Notification bell remains

### Mobile (≤480px)
- Optimized spacing
- Touch-friendly targets
- Full mobile menu overlay
- Simplified user display

## Customization

### Colors
The navbar uses CSS variables for easy customization:
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

## Integration with PHP

### Dynamic Menu Generation
The navbar automatically generates menu items based on user permissions:
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

## Accessibility Features

### Keyboard Navigation
- Tab navigation through all interactive elements
- Enter/Space activation for buttons
- Escape key closes mobile menu

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

### JavaScript
- Event delegation
- Debounced resize handlers
- Minimal DOM queries

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

#### Mobile Menu Not Opening
```javascript
// Check if JavaScript is loaded
console.log(typeof NavbarComponent);

// Check if navbar element exists
console.log(document.querySelector('.navbar'));
```

#### Active Page Not Highlighting
```php
// Ensure current page detection works
echo "Current page: " . basename($_SERVER['PHP_SELF']);
```

#### Styling Issues
```css
/* Check if component CSS is loaded */
.navbar {
    /* Should have fixed positioning */
    position: fixed;
}
```

### Debug Mode
Enable debug mode for troubleshooting:
```javascript
const navbar = new NavbarComponent(document.querySelector('.navbar'), {
    debug: true
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

## Examples

### Basic Implementation
See `admin/navbar_demo.php` for a complete working example.

### Custom Styling
```css
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
document.querySelectorAll('.navbar-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Custom logic here
    });
});
```

## Support

For issues or questions about the navbar component:
1. Check this documentation
2. Review the demo page
3. Test in different browsers
4. Check console for JavaScript errors
5. Verify CSS is loading correctly
