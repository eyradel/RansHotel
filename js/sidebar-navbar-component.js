/**
 * Sidebar + Navbar Component
 * Collapsible sidebar with top navbar navigation
 */

class SidebarNavbarComponent extends Component {
    get defaultOptions() {
        return {
            sidebarBreakpoint: 768,
            autoCloseOnMobile: true,
            showActivePage: true,
            enableNotifications: true,
            persistSidebarState: true,
            sidebarKey: 'sidebarOpen'
        };
    }

    init() {
        this.navbar = document.querySelector('#navbar');
        this.sidebar = document.querySelector('#sidebar');
        this.mainWrapper = document.querySelector('#mainWrapper');
        this.sidebarOverlay = document.querySelector('#sidebarOverlay');
        
        // Toggle buttons
        this.sidebarToggle = document.querySelector('#sidebarToggle');
        this.userMenuToggle = document.querySelector('#userMenuToggle');
        
        // Other elements
        this.userMenu = document.querySelector('#userMenu');
        this.userDropdown = document.querySelector('#userDropdown');
        
        this.setupEventListeners();
        this.handleResize();
        this.setActivePage();
        this.loadSidebarState();
    }

    setupEventListeners() {
        // Sidebar toggle
        if (this.sidebarToggle) {
            this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
        }


        // Mobile toggle
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', () => this.toggleMobileNav());
        }

        // Mobile close
        if (this.mobileClose) {
            this.mobileClose.addEventListener('click', () => this.closeMobileNav());
        }
        
        // Sidebar overlay click
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', () => this.closeSidebar());
        }

        // Overlay click
        if (this.mobileOverlay) {
            this.mobileOverlay.addEventListener('click', () => this.closeMobileNav());
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= this.options.sidebarBreakpoint) {
                if (this.isSidebarOpen() && 
                    !this.sidebar.contains(e.target) && 
                    !this.sidebarToggle?.contains(e.target)) {
                    this.closeSidebar();
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());

        // Sidebar link clicks
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (this.options.autoCloseOnMobile && window.innerWidth <= this.options.sidebarBreakpoint) {
                    this.closeSidebar();
                }
            });
        });

        // Mobile nav link clicks
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (this.options.autoCloseOnMobile) {
                    this.closeMobileNav();
                }
            });
        });

        // Notification bell
        if (this.notificationBell) {
            this.notificationBell.addEventListener('click', () => this.showNotifications());
        }

        // User menu dropdown
        if (this.userMenuToggle) {
            this.userMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleUserDropdown();
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (this.userDropdown && !this.userMenu.contains(e.target)) {
                this.closeUserDropdown();
            }
        });
    }

    toggleSidebar() {
        if (this.isSidebarOpen()) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    }

    openSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.remove('-translate-x-full');
            this.sidebar.classList.add('translate-x-0');
            if (this.sidebarOverlay && window.innerWidth <= this.options.sidebarBreakpoint) {
                this.sidebarOverlay.classList.remove('hidden');
            }
            this.saveSidebarState(true);
        }
    }

    closeSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.add('-translate-x-full');
            this.sidebar.classList.remove('translate-x-0');
            if (this.sidebarOverlay) {
                this.sidebarOverlay.classList.add('hidden');
            }
            this.saveSidebarState(false);
        }
    }

    isSidebarOpen() {
        return this.sidebar?.classList.contains('translate-x-0') || false;
    }

    toggleMobileNav() {
        if (this.isMobileNavOpen()) {
            this.closeMobileNav();
        } else {
            this.openMobileNav();
        }
    }

    openMobileNav() {
        if (this.mobileNav && this.mobileOverlay) {
            this.mobileNav.classList.add('open');
            this.mobileOverlay.classList.add('show');
            this.mobileNav.classList.remove('slide-out');
            this.mobileNav.classList.add('slide-in');
            document.body.style.overflow = 'hidden';
        }
    }

    closeMobileNav() {
        if (this.mobileNav && this.mobileOverlay) {
            this.mobileNav.classList.remove('open');
            this.mobileOverlay.classList.remove('show');
            this.mobileNav.classList.remove('slide-in');
            this.mobileNav.classList.add('slide-out');
            document.body.style.overflow = '';
        }
    }

    isMobileNavOpen() {
        return this.mobileNav?.classList.contains('open') || false;
    }

    handleResize() {
        if (window.innerWidth > this.options.sidebarBreakpoint) {
            // Hide overlay on desktop
            if (this.sidebarOverlay) {
                this.sidebarOverlay.classList.add('hidden');
            }
            // Auto-open sidebar on desktop if it was previously open
            if (this.getSidebarState()) {
                this.openSidebar();
            }
        } else {
            // Close sidebar on mobile and show overlay when open
            if (this.isSidebarOpen()) {
                if (this.sidebarOverlay) {
                    this.sidebarOverlay.classList.remove('hidden');
                }
            } else {
                this.closeSidebar();
            }
        }
    }

    toggleUserDropdown() {
        if (this.userDropdown) {
            this.userDropdown.classList.toggle('hidden');
        }
    }

    closeUserDropdown() {
        if (this.userDropdown) {
            this.userDropdown.classList.add('hidden');
        }
    }

    setActivePage() {
        if (!this.options.showActivePage) return;

        const currentPage = this.getCurrentPage();
        
        // Set active state for sidebar links
        document.querySelectorAll('#sidebar a[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (href && this.isCurrentPage(href, currentPage)) {
                link.classList.add('bg-blue-50', 'text-blue-700', 'border-r-2', 'border-blue-700');
                link.classList.remove('text-gray-700', 'hover:bg-gray-100', 'hover:text-gray-900');
                const icon = link.querySelector('i');
                if (icon) {
                    icon.classList.add('text-blue-700');
                    icon.classList.remove('text-gray-400', 'group-hover:text-gray-500');
                }
            } else {
                link.classList.remove('bg-blue-50', 'text-blue-700', 'border-r-2', 'border-blue-700');
                link.classList.add('text-gray-700', 'hover:bg-gray-100', 'hover:text-gray-900');
                const icon = link.querySelector('i');
                if (icon) {
                    icon.classList.remove('text-blue-700');
                    icon.classList.add('text-gray-400', 'group-hover:text-gray-500');
                }
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
        const badge = document.querySelector('.navbar-notification-badge');
        if (badge && parseInt(badge.textContent) > 0) {
            badge.style.animation = 'pulse 2s infinite';
        }
    }

    updateNotificationCount(count) {
        const badge = document.querySelector('.navbar-notification-badge');
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

    showUserMenu() {
        // Create user dropdown
        const dropdown = document.createElement('div');
        dropdown.className = 'user-dropdown';
        dropdown.innerHTML = `
            <div class="user-dropdown-content">
                <a href="user_settings.php" class="user-dropdown-item">
                    <i class="fa fa-user"></i>
                    <span>Profile</span>
                </a>
                <a href="settings.php" class="user-dropdown-item">
                    <i class="fa fa-cog"></i>
                    <span>Settings</span>
                </a>
                <div class="user-dropdown-divider"></div>
                <a href="logout.php" class="user-dropdown-item">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                </a>
            </div>
        `;

        // Style the dropdown
        dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        `;

        // Add to user menu
        this.userMenu.style.position = 'relative';
        this.userMenu.appendChild(dropdown);

        // Animate in
        setTimeout(() => {
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
        }, 10);

        // Close on outside click
        setTimeout(() => {
            document.addEventListener('click', function closeDropdown(e) {
                if (!this.userMenu.contains(e.target)) {
                    dropdown.remove();
                    document.removeEventListener('click', closeDropdown);
                }
            }.bind(this));
        }, 100);
    }

    // Sidebar state management
    saveSidebarState(isOpen) {
        if (this.options.persistSidebarState) {
            localStorage.setItem(this.options.sidebarKey, isOpen);
        }
    }

    getSidebarState() {
        if (this.options.persistSidebarState) {
            return localStorage.getItem(this.options.sidebarKey) === 'true';
        }
        return false;
    }

    loadSidebarState() {
        if (window.innerWidth > this.options.sidebarBreakpoint && this.getSidebarState()) {
            this.openSidebar();
        }
    }

    // Public methods
    addNotification(title, message, type = 'info') {
        const currentCount = parseInt(document.querySelector('.navbar-notification-badge').textContent) || 0;
        this.updateNotificationCount(currentCount + 1);
    }

    setUserInfo(name, role) {
        const nameEls = document.querySelectorAll('.navbar-user-name, .sidebar-user-name');
        const roleEls = document.querySelectorAll('.navbar-user-role, .sidebar-user-role');
        
        nameEls.forEach(el => el.textContent = name);
        roleEls.forEach(el => el.textContent = role);
    }
}

// Auto-initialize sidebar navbar component
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        new SidebarNavbarComponent(sidebar);
    }
});

// Export for global use
window.SidebarNavbarComponent = SidebarNavbarComponent;
