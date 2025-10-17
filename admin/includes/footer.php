<?php
/**
 * Professional Footer Component
 * RansHotel Admin System
 */
?>

            </div> <!-- End main-content -->
        </main>
    </div>
</div>

<!-- Professional Footer -->
<footer class="footer mt-auto py-3" style="background-color: var(--primary-color); color: white;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">
                    &copy; <?php echo date('Y'); ?> RansHotel. All rights reserved.
                </span>
            </div>
            <div class="col-md-6 text-right">
                <span class="text-muted">
                    Admin Panel v2.0 | 
                    <span class="badge badge-success">System Online</span>
                </span>
            </div>
        </div>
    </div>
</footer>

<!-- Professional JavaScript -->
<script>
// Professional UI Enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('.navbar-toggler');
    const sidebar = document.querySelector('#sidebarMenu');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Professional form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'error');
            }
        });
    });
    
    // Professional loading states
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(function(button) {
        if (button.type === 'submit') {
            button.addEventListener('click', function() {
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="loading"></span> Processing...';
                button.disabled = true;
                
                // Re-enable after 3 seconds (fallback)
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        }
    });
});

// Professional notification system
function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    }[type] || 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(function() {
        notification.remove();
    }, 5000);
}

// Professional table enhancements
function enhanceTables() {
    const tables = document.querySelectorAll('.table');
    tables.forEach(function(table) {
        // Add hover effects
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
}

// Initialize table enhancements
document.addEventListener('DOMContentLoaded', enhanceTables);
</script>

<!-- Professional CSS for JavaScript enhancements -->
<style>
.is-invalid {
    border-color: var(--danger-color) !important;
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
}

.footer {
    margin-top: auto;
}

.sidebar.show {
    left: 0;
}

@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100vh;
        z-index: 1000;
        transition: left 0.3s ease;
        background-color: var(--secondary-color);
    }
    
    .main-content {
        margin-left: 0;
    }
}

.user-avatar {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info {
    text-align: left;
}

.user-name {
    font-weight: 500;
    color: white;
}

.user-role {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.7);
}

.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
    margin-top: auto;
}

.nav-link.active {
    background-color: var(--accent-color);
    color: white;
    border-radius: 4px;
}

.nav-link:hover {
    background-color: rgba(255,255,255,0.1);
    color: white;
    border-radius: 4px;
}
</style>
