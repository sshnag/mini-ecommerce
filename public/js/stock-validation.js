/**
 * Stock Validation for Checkout Process
 * Handles real-time stock checking and prevents concurrent order issues
 */

class StockValidator {
    constructor() {
        this.checkInterval = null;
        this.isChecking = false;
        this.init();
    }

    init() {
        // Check stock when user enters checkout review page
        if (document.querySelector('.checkout-review-page')) {
            this.startStockMonitoring();
        }

        // Check stock before placing order
        const placeOrderBtn = document.querySelector('#place-order-btn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.validateStockBeforeOrder();
            });
        }
    }

    startStockMonitoring() {
        // Check stock every 30 seconds during checkout
        this.checkInterval = setInterval(() => {
            this.checkStockAvailability();
        }, 30000);

        // Also check when user interacts with the page
        document.addEventListener('click', () => {
            this.checkStockAvailability();
        });
    }

    async checkStockAvailability() {
        if (this.isChecking) return;
        
        this.isChecking = true;

        try {
            const response = await fetch('/checkout/check-stock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.has_issues) {
                this.showStockAlert(data);
            }
        } catch (error) {
            console.error('Stock check failed:', error);
        } finally {
            this.isChecking = false;
        }
    }

    async validateStockBeforeOrder() {
        const placeOrderBtn = document.querySelector('#place-order-btn');
        const originalText = placeOrderBtn.textContent;
        
        placeOrderBtn.disabled = true;
        placeOrderBtn.textContent = 'Validating stock...';

        try {
            const response = await fetch('/checkout/check-stock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.has_issues) {
                this.showStockAlert(data);
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = originalText;
                return false;
            }

            // If stock is OK, proceed with order
            document.querySelector('#place-order-form').submit();
            
        } catch (error) {
            console.error('Stock validation failed:', error);
            placeOrderBtn.disabled = false;
            placeOrderBtn.textContent = originalText;
        }
    }

    showStockAlert(data) {
        let message = '<div class="text-start">';
        message += '<h6 class="mb-3">Stock Issues Detected:</h6>';
        
        if (data.out_of_stock && data.out_of_stock.length > 0) {
            message += '<div class="mb-2"><strong>Out of Stock:</strong><ul class="mb-0 mt-1">';
            data.out_of_stock.forEach(item => {
                message += `<li>${item.product.name} - Only ${item.available_stock} available</li>`;
            });
            message += '</ul></div>';
        }
        
        if (data.low_stock && data.low_stock.length > 0) {
            message += '<div class="mb-2"><strong>Insufficient Stock:</strong><ul class="mb-0 mt-1">';
            data.low_stock.forEach(item => {
                message += `<li>${item.product.name} - Only ${item.available_stock} available</li>`;
            });
            message += '</ul></div>';
        }
        
        message += '<hr><p class="mb-0"><strong>Please update your cart before proceeding.</strong></p></div>';

        Swal.fire({
            icon: 'warning',
            title: 'Stock Issues Detected',
            html: message,
            confirmButtonText: 'Update Cart',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d4af37',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/cart';
            }
        });
    }

    stopMonitoring() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
            this.checkInterval = null;
        }
    }
}

// Initialize stock validator when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new StockValidator();
}); 