    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Enhanced JavaScript for better UX -->
    <script>
        // Global utility functions
        window.JELAircon = {
            // Show loading spinner
            showLoading: function(element) {
                if (element) {
                    element.disabled = true;
                    const originalText = element.innerHTML;
                    element.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
                    element.setAttribute('data-original-text', originalText);
                }
            },
            
            // Hide loading spinner
            hideLoading: function(element) {
                if (element && element.hasAttribute('data-original-text')) {
                    element.innerHTML = element.getAttribute('data-original-text');
                    element.disabled = false;
                    element.removeAttribute('data-original-text');
                }
            },
            
            // Show toast notification
            showToast: function(message, type = 'info', duration = 5000) {
                const toastContainer = document.getElementById('toast-container') || this.createToastContainer();
                
                const toast = document.createElement('div');
                toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : type} border-0`;
                toast.setAttribute('role', 'alert');
                
                const iconMap = {
                    'success': 'check-circle',
                    'error': 'exclamation-triangle',
                    'warning': 'exclamation-triangle',
                    'info': 'info-circle'
                };
                
                toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${iconMap[type] || 'info-circle'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                
                const bsToast = new bootstrap.Toast(toast, {
                    autohide: true,
                    delay: duration
                });
                bsToast.show();
                
                // Remove toast element after it's hidden
                toast.addEventListener('hidden.bs.toast', function() {
                    toast.remove();
                });
            },
            
            // Create toast container
            createToastContainer: function() {
                const container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '9999';
                document.body.appendChild(container);
                return container;
            },
            
            // Confirm dialog
            confirm: function(message, callback) {
                if (confirm(message)) {
                    callback();
                }
            },
            
            // Format phone number
            formatPhone: function(phone) {
                return phone.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
            },
            
            // Validate email
            validateEmail: function(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            },
            
            // Validate Philippine phone
            validatePhone: function(phone) {
                const cleaned = phone.replace(/\D/g, '');
                return /^(\+63|0)?[0-9]{10}$/.test(cleaned);
            }
        };
        
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert:not(.flash-message)');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Add loading states to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        JELAircon.showLoading(submitBtn);
                    }
                });
            });
            
            // Add hover effects to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
            
            // Add click effects to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Add ripple effect
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
        
        // Add ripple effect CSS
        const style = document.createElement('style');
        style.textContent = `
            .btn {
                position: relative;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
            
            .form-control:focus {
                transform: scale(1.02);
            }
            
            .nav-link:hover {
                transform: translateY(-1px);
            }
            
            .dropdown-item:hover {
                transform: translateX(5px);
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>