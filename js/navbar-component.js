/**
 * Navbar Component
 * Responsive navigation bar with mobile support
 */

class NavbarComponent extends Component {
    get defaultOptions() {
        return {
            mobileBreakpoint: 768,
            autoCloseOnMobile: true,
            showActivePage: true,
            enableNotifications: true
        };
    }

    init() {
        this.navbar = this.element;
        this.toggleBtn = this.navbar.querySelector('.navbar-toggle');
        this.mobileNav = document.querySelector('.navbar-mobile');
        this.mobileLinks = document.querySelectorAll('.navbar-mobile-link');
        this.notificationBell = this.navbar.querySelector('.navbar-notification');
        
        this.setupEventListeners();
        this.handleResize();
        this.setActivePage();
        this.setupNotifications();
    }

    setupEventListeners() {
        // Mobile toggle
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggleMobile());
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isMobileOpen() && 
                !this.navbar.contains(e.target) && 
                !this.mobileNav?.contains(e.target)) {
                this.closeMobile();
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());

        // Mobile link clicks
        this.mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (this.options.autoCloseOnMobile) {
                    this.closeMobile();
                }
            });
        });

        // Notification bell
        if (this.notificationBell) {
            this.notificationBell.addEventListener('click', () => this.showNotifications());
        }
    }

    toggleMobile() {
        if (this.isMobileOpen()) {
            this.closeMobile();
        } else {
            this.openMobile();
        }
    }

    openMobile() {
        if (this.mobileNav) {
            this.mobileNav.classList.add('open');
            this.mobileNav.classList.remove('slide-up');
            this.mobileNav.classList.add('slide-down');
            document.body.style.overflow = 'hidden';
        }
    }

    closeMobile() {
        if (this.mobileNav) {
            this.mobileNav.classList.remove('open');
            this.mobileNav.classList.remove('slide-down');
            this.mobileNav.classList.add('slide-up');
            document.body.style.overflow = '';
        }
    }

    isMobileOpen() {
        return this.mobileNav?.classList.contains('open') || false;
    }

    handleResize() {
        if (window.innerWidth > this.options.mobileBreakpoint) {
            this.closeMobile();
        }
    }

    setActivePage() {
        if (!this.options.showActivePage) return;

        const currentPage = this.getCurrentPage();
        
        // Set active state for desktop links
        this.navbar.querySelectorAll('.navbar-link').forEach(link => {
            const href = link.getAttribute('href');
            if (href && this.isCurrentPage(href, currentPage)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        // Set active state for mobile links
        this.mobileLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && this.isCurrentPage(href, currentPage)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    getCurrentPage() {
        const path = window.location.pathname;
        return path.split('/').pop().replace('.php', '');
    }

    isCurrentPage(href, currentPage) {
        const linkPage = href.split('/').pop().replace('.php', '');
        return linkPage === currentPage || 
               (currentPage === 'dashboard_classic' && linkPage === 'dashboard') ||
               (currentPage === 'reservation_classic' && linkPage === 'reservation');
    }

    setupNotifications() {
        if (!this.options.enableNotifications) return;

        // Simulate notification count (replace with real data)
        this.updateNotificationCount(3);
        
        // Add pulse animation for new notifications
        const badge = this.navbar.querySelector('.navbar-notification-badge');
        if (badge && parseInt(badge.textContent) > 0) {
            badge.style.animation = 'pulse 2s infinite';
        }
    }

    updateNotificationCount(count) {
        const badge = this.navbar.querySelector('.navbar-notification-badge');
        if (badge) {
            badge.textContent = count;
            if (count > 0) {
                badge.style.animation = 'pulse 2s infinite';
            } else {
                badge.style.animation = '';
            }
        }
    }

    showNotifications() {
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
        this.notificationBell.style.position = 'relative';
        this.notificationBell.appendChild(dropdown);

        // Animate in
        setTimeout(() => {
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
        }, 10);

        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', function closeDropdown(e) {
                if (!this.notificationBell.contains(e.target)) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                }
            }.bind(this));
        }, 100);

        // Close button functionality
        const closeBtn = dropdown.querySelector('.close-notifications');
        closeBtn.addEventListener('click', () => {
            dropdown.remove();
        });
    }

    // Public methods
    addNotification(title, message, type = 'info') {
        // This would typically make an AJAX call to add the notification
        const currentCount = parseInt(this.navbar.querySelector('.navbar-notification-badge').textContent) || 0;
        this.updateNotificationCount(currentCount + 1);
    }

    setUserInfo(name, role) {
        const nameEl = this.navbar.querySelector('.navbar-user-name');
        const roleEl = this.navbar.querySelector('.navbar-user-role');
        
        if (nameEl) nameEl.textContent = name;
        if (roleEl) roleEl.textContent = role;
    }
}

// Auto-initialize navbar component
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        new NavbarComponent(navbar);
    }
});

// Export for global use
window.NavbarComponent = NavbarComponent;

