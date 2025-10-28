# PHP Component System
## RansHotel Admin System

A reusable PHP component system for consistent admin interface across all pages.

## 🚀 Quick Start

### Method 1: Helper Functions (Recommended)
```php
<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('includes/access_control.php');
include('includes/component_helpers.php');

// Start the admin page
startAdminPage('Page Title', 'Page Description');
?>

<!-- Your content here -->
<div class="container">
    <h1>Your Page Content</h1>
    <p>This will be wrapped in the responsive layout automatically.</p>
</div>

<?php
// End the admin page
endAdminPage();
?>
```

### Method 2: Component Loader
```php
<?php
require_once 'components/ComponentLoader.php';

// Render complete layout
echo ComponentLoader::layout();

// Or render individual components
echo ComponentLoader::navbar();
echo ComponentLoader::sidebar();
?>
```

### Method 3: Direct Component Usage
```php
<?php
require_once 'components/LayoutComponent.php';

$layout = new LayoutComponent();
echo $layout->render();
?>
```

## 📁 Component Structure

```
admin/components/
├── BaseComponent.php          # Base class for all components
├── ComponentLoader.php        # Simple component loader
├── LayoutComponent.php        # Main layout component
├── NavbarComponent.php        # Top navbar component
├── SidebarComponent.php       # Sidebar navigation component
└── templates/
    ├── layout.php            # Layout template
    ├── navbar.php            # Navbar template
    └── sidebar.php           # Sidebar template
```

## 🎨 Features

### ✅ Responsive Design
- Mobile-first approach
- Collapsible sidebar on mobile
- Touch-friendly interface
- Automatic breakpoint handling

### ✅ Consistent Styling
- Unified color scheme
- Consistent typography
- Professional appearance
- Modern UI/UX

### ✅ Easy Maintenance
- Single source of truth
- Easy to update
- Reusable across pages
- Clean separation of concerns

### ✅ Accessibility
- Keyboard navigation
- Screen reader friendly
- High contrast support
- Focus management

## 🔧 Customization

### Custom Options
```php
$options = [
    'showNavbar' => true,
    'showSidebar' => true,
    'showOverlay' => true,
    'responsive' => true,
    'cssFiles' => ['../css/custom.css'],
    'jsFiles' => ['../js/custom.js']
];

startAdminPage('Title', 'Description', $options);
```

### Custom CSS
```php
$options = [
    'cssFiles' => [
        '../css/components.css',
        '../css/custom-styles.css'
    ]
];
```

### Custom JavaScript
```php
$options = [
    'jsFiles' => [
        '../js/components.js',
        '../js/sidebar-navbar-component.js',
        '../js/custom-scripts.js'
    ]
];
```

## 📱 Responsive Breakpoints

- **Desktop**: > 1024px - Full sidebar visible
- **Tablet**: 769px - 1024px - Smaller sidebar
- **Mobile**: ≤ 768px - Collapsible sidebar with overlay
- **Small Mobile**: ≤ 480px - Optimized for small screens

## 🎯 Usage Examples

### Basic Admin Page
```php
<?php
include('includes/component_helpers.php');
startAdminPage('Dashboard', 'Admin Dashboard');
?>
<div class="container">
    <h1>Dashboard</h1>
    <p>Welcome to the admin dashboard!</p>
</div>
<?php endAdminPage(); ?>
```

### Page with Custom Options
```php
<?php
$options = [
    'cssFiles' => ['../css/dashboard.css'],
    'jsFiles' => ['../js/dashboard.js']
];
startAdminPage('Dashboard', 'Admin Dashboard', $options);
?>
<!-- Content -->
<?php endAdminPage($options); ?>
```

### Individual Components
```php
<?php
echo ComponentLoader::navbar(['showNotifications' => false]);
echo ComponentLoader::sidebar(['showFooter' => false]);
?>
```

## 🔄 Migration Guide

### From Old Navigation System
Replace:
```php
<?php include('includes/navigation_sidebar.php'); ?>
```

With:
```php
<?php
include('includes/component_helpers.php');
startAdminPage('Page Title', 'Description');
?>
<!-- Your content -->
<?php endAdminPage(); ?>
```

## 🐛 Troubleshooting

### Common Issues

1. **Components not loading**
   - Check file paths
   - Ensure includes are correct
   - Verify PHP syntax

2. **Styling issues**
   - Check CSS file paths
   - Verify component CSS is loaded
   - Check for CSS conflicts

3. **JavaScript not working**
   - Check JS file paths
   - Verify component JS is loaded
   - Check browser console for errors

### Debug Mode
```php
$options = [
    'debug' => true,
    'showConsole' => true
];
```

## 📚 API Reference

### Helper Functions
- `startAdminPage($title, $description, $options)` - Start admin page
- `endAdminPage($options)` - End admin page
- `renderAdminLayout($options)` - Render complete layout
- `renderNavbar($options)` - Render navbar only
- `renderSidebar($options)` - Render sidebar only

### Component Classes
- `BaseComponent` - Base class for all components
- `LayoutComponent` - Main layout component
- `NavbarComponent` - Top navbar component
- `SidebarComponent` - Sidebar navigation component
- `ComponentLoader` - Component loader utility

## 🎉 Benefits

1. **Consistency** - All pages look and behave the same
2. **Maintainability** - Update once, apply everywhere
3. **Responsiveness** - Built-in mobile support
4. **Performance** - Optimized CSS and JS
5. **Developer Experience** - Easy to use and extend
6. **User Experience** - Professional, modern interface

---

**Ready to use!** 🚀

Simply include the helper functions and start building consistent admin pages.