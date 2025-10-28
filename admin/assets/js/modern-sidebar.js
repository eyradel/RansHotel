/**
 * Modern Sidebar JavaScript
 * RansHotel Admin System
 */

document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('modernSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mainContentWrapper = document.querySelector('.main-content-wrapper');
    
    // Sidebar toggle functionality
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            toggleSidebar();
        });
    }
    
    // Mobile menu toggle
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            toggleMobileSidebar();
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                closeMobileSidebar();
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileSidebar();
        }
    });
    
    // Initialize sidebar state
    initializeSidebar();
    
    // Add smooth transitions to navigation items
    addNavigationAnimations();
    
    // Add notification bell functionality
    initializeNotificationBell();
});

/**
 * Toggle sidebar collapsed state
 */
function toggleSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    const isCollapsed = sidebar.classList.contains('collapsed');
    
    if (isCollapsed) {
        expandSidebar();
    } else {
        collapseSidebar();
    }
    
    // Save state to localStorage
    localStorage.setItem('sidebarCollapsed', !isCollapsed);
}

/**
 * Collapse sidebar
 */
function collapseSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    sidebar.classList.add('collapsed');
    
    // Add animation class
    sidebar.classList.add('slide-out');
    setTimeout(() => {
        sidebar.classList.remove('slide-out');
    }, 300);
}

/**
 * Expand sidebar
 */
function expandSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    sidebar.classList.remove('collapsed');
    
    // Add animation class
    sidebar.classList.add('slide-in');
    setTimeout(() => {
        sidebar.classList.remove('slide-in');
    }, 300);
}

/**
 * Toggle mobile sidebar
 */
function toggleMobileSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    const isOpen = sidebar.classList.contains('mobile-open');
    
    if (isOpen) {
        closeMobileSidebar();
    } else {
        openMobileSidebar();
    }
}

/**
 * Open mobile sidebar
 */
function openMobileSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    sidebar.classList.add('mobile-open');
    
    // Add backdrop
    createBackdrop();
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

/**
 * Close mobile sidebar
 */
function closeMobileSidebar() {
    const sidebar = document.getElementById('modernSidebar');
    sidebar.classList.remove('mobile-open');
    
    // Remove backdrop
    removeBackdrop();
    
    // Restore body scroll
    document.body.style.overflow = '';
}

/**
 * Create backdrop for mobile sidebar
 */
function createBackdrop() {
    const backdrop = document.createElement('div');
    backdrop.className = 'sidebar-backdrop';
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;
    
    backdrop.addEventListener('click', closeMobileSidebar);
    document.body.appendChild(backdrop);
    
    // Fade in
    setTimeout(() => {
        backdrop.style.opacity = '1';
    }, 10);
}

/**
 * Remove backdrop
 */
function removeBackdrop() {
    const backdrop = document.querySelector('.sidebar-backdrop');
    if (backdrop) {
        backdrop.style.opacity = '0';
        setTimeout(() => {
            backdrop.remove();
        }, 300);
    }
}

/**
 * Initialize sidebar state from localStorage
 */
function initializeSidebar() {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (isCollapsed && window.innerWidth > 768) {
        collapseSidebar();
    }
}

/**
 * Add navigation animations
 */
function addNavigationAnimations() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach((item, index) => {
        // Add staggered animation on load
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 50);
        
        // Add hover effects
        const link = item.querySelector('.nav-link');
        if (link) {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px)';
            });
            
            link.addEventListener('mouseleave', function() {
                if (!item.classList.contains('active')) {
                    this.style.transform = 'translateX(0)';
                }
            });
        }
    });
}

/**
 * Initialize notification bell
 */
function initializeNotificationBell() {
    const notificationBell = document.querySelector('.notification-bell');
    
    if (notificationBell) {
        notificationBell.addEventListener('click', function() {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Show notification dropdown (placeholder)
            showNotificationDropdown();
        });
        
        // Add pulse animation for new notifications
        const badge = notificationBell.querySelector('.notification-badge');
        if (badge && parseInt(badge.textContent) > 0) {
            badge.style.animation = 'pulse 2s infinite';
        }
    }
}

/**
 * Show notification dropdown
 */
function showNotificationDropdown() {
    // Create notification dropdown
    const dropdown = document.createElement('div');
    dropdown.className = 'notification-dropdown';
    dropdown.innerHTML = `
        <div class="notification-header">
            <h6>Notifications</h6>
            <button class="close-notifications">&times;</button>
        </div>
        <div class="notification-list">
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fa fa-bell"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">New Booking</div>
                    <div class="notification-message">Room 101 has been booked</div>
                    <div class="notification-time">2 minutes ago</div>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">New User</div>
                    <div class="notification-message">John Doe registered</div>
                    <div class="notification-time">5 minutes ago</div>
                </div>
            </div>
            <div class="notification-item">
                <div class="notification-icon">
                    <i class="fa fa-exclamation"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">System Alert</div>
                    <div class="notification-message">Server maintenance scheduled</div>
                    <div class="notification-time">1 hour ago</div>
                </div>
            </div>
        </div>
        <div class="notification-footer">
            <a href="notifications.php" class="view-all">View All Notifications</a>
        </div>
    `;
    
    // Style the dropdown
    dropdown.style.cssText = `
        position: absolute;
        top: 100%;
        right: 0;
        width: 320px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    `;
    
    // Add to notification bell
    const notificationBell = document.querySelector('.notification-bell');
    notificationBell.style.position = 'relative';
    notificationBell.appendChild(dropdown);
    
    // Animate in
    setTimeout(() => {
        dropdown.style.opacity = '1';
        dropdown.style.transform = 'translateY(0)';
    }, 10);
    
    // Close on outside click
    setTimeout(() => {
        document.addEventListener('click', function closeDropdown(e) {
            if (!notificationBell.contains(e.target)) {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            }
        });
    }, 100);
    
    // Close button functionality
    const closeBtn = dropdown.querySelector('.close-notifications');
    closeBtn.addEventListener('click', () => {
        dropdown.remove();
    });
}

/**
 * Add CSS animations
 */
function addCustomStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .notification-dropdown {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .notification-header {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h6 {
            margin: 0;
            font-weight: 600;
            color: #374151;
        }
        
        .close-notifications {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            gap: 0.75rem;
            transition: background-color 0.2s ease;
        }
        
        .notification-item:hover {
            background-color: #f9fafb;
        }
        
        .notification-icon {
            width: 35px;
            height: 35px;
            background: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            flex-shrink: 0;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .notification-message {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .notification-time {
            color: #9ca3af;
            font-size: 0.75rem;
        }
        
        .notification-footer {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }
        
        .view-all {
            color: #3b82f6;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .view-all:hover {
            text-decoration: underline;
        }
    `;
    document.head.appendChild(style);
}

// Initialize custom styles
addCustomStyles();

/**
 * Utility function to update notification count
 */
function updateNotificationCount(count) {
    const badge = document.querySelector('.notification-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.style.animation = 'pulse 2s infinite';
        } else {
            badge.style.animation = '';
        }
    }
}

/**
 * Utility function to add new notification
 */
function addNotification(title, message, type = 'info') {
    // This would typically make an AJAX call to add the notification
    // For now, we'll just update the count
    const currentCount = parseInt(document.querySelector('.notification-badge').textContent) || 0;
    updateNotificationCount(currentCount + 1);
}

// Export functions for global use
window.SidebarManager = {
    toggleSidebar,
    collapseSidebar,
    expandSidebar,
    toggleMobileSidebar,
    updateNotificationCount,
    addNotification
};
