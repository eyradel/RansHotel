/**
 * RansHotel Component System - JavaScript
 * Modular JavaScript components for consistency and maintainability
 */

// Base Component Class
class Component {
    constructor(element, options = {}) {
        this.element = element;
        this.options = { ...this.defaultOptions, ...options };
        this.init();
    }

    get defaultOptions() {
        return {};
    }

    init() {
        // Override in child classes
    }

    destroy() {
        // Override in child classes
    }
}

// Form Component
class FormComponent extends Component {
    get defaultOptions() {
        return {
            validateOnSubmit: true,
            showLoadingState: true,
            autoFocus: true
        };
    }

    init() {
        this.form = this.element;
        this.submitBtn = this.form.querySelector('button[type="submit"]');
        this.setupEventListeners();
    }

    setupEventListeners() {
        if (this.options.validateOnSubmit) {
            this.form.addEventListener('submit', this.handleSubmit.bind(this));
        }

        // Auto-focus first input
        if (this.options.autoFocus) {
            const firstInput = this.form.querySelector('input, select, textarea');
            if (firstInput) {
                firstInput.focus();
            }
        }
    }

    handleSubmit(e) {
        if (!this.validateForm()) {
            e.preventDefault();
            return false;
        }

        if (this.options.showLoadingState && this.submitBtn) {
            this.showLoadingState();
        }
    }

    validateForm() {
        const requiredFields = this.form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'form-error';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
        field.classList.add('is-invalid');
    }

    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.form-error');
        if (existingError) {
            existingError.remove();
        }
        field.classList.remove('is-invalid');
    }

    showLoadingState() {
        if (this.submitBtn) {
            this.originalText = this.submitBtn.innerHTML;
            this.submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Processing...';
            this.submitBtn.disabled = true;
        }
    }

    hideLoadingState() {
        if (this.submitBtn && this.originalText) {
            this.submitBtn.innerHTML = this.originalText;
            this.submitBtn.disabled = false;
        }
    }
}

// Table Component
class TableComponent extends Component {
    get defaultOptions() {
        return {
            sortable: true,
            searchable: true,
            pagination: true,
            pageSize: 10
        };
    }

    init() {
        this.table = this.element;
        this.setupTable();
    }

    setupTable() {
        if (this.options.sortable) {
            this.addSorting();
        }
        
        if (this.options.searchable) {
            this.addSearch();
        }
    }

    addSorting() {
        const headers = this.table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => this.sortTable(header));
        });
    }

    addSearch() {
        const searchContainer = document.createElement('div');
        searchContainer.className = 'table-search mb-3';
        searchContainer.innerHTML = `
            <input type="text" class="form-control" placeholder="Search table..." id="tableSearch">
        `;
        
        this.table.parentNode.insertBefore(searchContainer, this.table);
        
        const searchInput = searchContainer.querySelector('#tableSearch');
        searchInput.addEventListener('input', (e) => this.filterTable(e.target.value));
    }

    sortTable(header) {
        const column = header.dataset.sort;
        const tbody = this.table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        const isAscending = header.classList.contains('sort-asc');
        
        // Clear all sort classes
        this.table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        
        // Add appropriate class
        header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
        
        // Sort rows
        rows.sort((a, b) => {
            const aVal = a.querySelector(`[data-sort="${column}"]`)?.textContent || '';
            const bVal = b.querySelector(`[data-sort="${column}"]`)?.textContent || '';
            
            if (isAscending) {
                return bVal.localeCompare(aVal);
            } else {
                return aVal.localeCompare(bVal);
            }
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    filterTable(searchTerm) {
        const tbody = this.table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(searchTerm.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    }
}

// Alert Component
class AlertComponent extends Component {
    get defaultOptions() {
        return {
            autoHide: true,
            hideDelay: 5000,
            position: 'top-right'
        };
    }

    static show(message, type = 'info', options = {}) {
        const alert = new AlertComponent(null, { ...options, message, type });
        return alert;
    }

    init() {
        if (this.options.message) {
            this.createAlert();
        }
    }

    createAlert() {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${this.options.type} alert-dismissible`;
        alertDiv.innerHTML = `
            <div class="alert-with-icon">
                <div class="alert-icon">
                    <i class="fa fa-${this.getIcon()}"></i>
                </div>
                <div class="alert-content">
                    ${this.options.message}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;

        this.element = alertDiv;
        this.appendToContainer();
        this.setupEventListeners();

        if (this.options.autoHide) {
            setTimeout(() => this.hide(), this.options.hideDelay);
        }
    }

    getIcon() {
        const icons = {
            success: 'check-circle',
            danger: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[this.options.type] || 'info-circle';
    }

    appendToContainer() {
        let container = document.querySelector('.alert-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'alert-container';
            document.body.appendChild(container);
        }
        container.appendChild(this.element);
    }

    setupEventListeners() {
        const closeBtn = this.element.querySelector('.close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hide());
        }
    }

    hide() {
        if (this.element) {
            this.element.style.opacity = '0';
            this.element.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (this.element.parentNode) {
                    this.element.parentNode.removeChild(this.element);
                }
            }, 300);
        }
    }
}

// Sidebar Component
class SidebarComponent extends Component {
    get defaultOptions() {
        return {
            collapsed: false,
            mobileBreakpoint: 768,
            persistState: true
        };
    }

    init() {
        this.sidebar = this.element;
        this.toggleBtn = document.querySelector('[data-sidebar-toggle]');
        this.mobileToggleBtn = document.querySelector('[data-mobile-sidebar-toggle]');
        
        this.loadState();
        this.setupEventListeners();
        this.handleResize();
    }

    setupEventListeners() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }

        if (this.mobileToggleBtn) {
            this.mobileToggleBtn.addEventListener('click', () => this.toggleMobile());
        }

        window.addEventListener('resize', () => this.handleResize());
        
        // Close mobile sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= this.options.mobileBreakpoint) {
                if (!this.sidebar.contains(e.target) && 
                    !this.mobileToggleBtn?.contains(e.target)) {
                    this.closeMobile();
                }
            }
        });
    }

    toggle() {
        this.options.collapsed = !this.options.collapsed;
        this.updateSidebar();
        this.saveState();
    }

    toggleMobile() {
        if (this.sidebar.classList.contains('mobile-open')) {
            this.closeMobile();
        } else {
            this.openMobile();
        }
    }

    openMobile() {
        this.sidebar.classList.add('mobile-open');
        document.body.style.overflow = 'hidden';
    }

    closeMobile() {
        this.sidebar.classList.remove('mobile-open');
        document.body.style.overflow = '';
    }

    updateSidebar() {
        if (this.options.collapsed) {
            this.sidebar.classList.add('collapsed');
        } else {
            this.sidebar.classList.remove('collapsed');
        }
    }

    handleResize() {
        if (window.innerWidth <= this.options.mobileBreakpoint) {
            this.closeMobile();
        }
    }

    loadState() {
        if (this.options.persistState) {
            const saved = localStorage.getItem('sidebarCollapsed');
            if (saved !== null) {
                this.options.collapsed = saved === 'true';
                this.updateSidebar();
            }
        }
    }

    saveState() {
        if (this.options.persistState) {
            localStorage.setItem('sidebarCollapsed', this.options.collapsed);
        }
    }
}

// Initialize Components
document.addEventListener('DOMContentLoaded', function() {
    // Initialize forms
    document.querySelectorAll('form[data-component="form"]').forEach(form => {
        new FormComponent(form);
    });

    // Initialize tables
    document.querySelectorAll('table[data-component="table"]').forEach(table => {
        new TableComponent(table);
    });

    // Initialize sidebar
    const sidebar = document.querySelector('.modern-sidebar');
    if (sidebar) {
        new SidebarComponent(sidebar);
    }
});

// Export components for global use
window.RansHotelComponents = {
    Component,
    FormComponent,
    TableComponent,
    AlertComponent,
    SidebarComponent
};





