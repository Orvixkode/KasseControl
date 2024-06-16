import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;
Alpine.start();

// POS functionality
window.POS = {
    cart: [],
    
    addToCart(product) {
        const existing = this.cart.find(item => item.id === product.id);
        if (existing) {
            existing.quantity += 1;
        } else {
            this.cart.push({
                ...product,
                quantity: 1
            });
        }
        this.updateCart();
    },
    
    removeFromCart(productId) {
        this.cart = this.cart.filter(item => item.id !== productId);
        this.updateCart();
    },
    
    updateQuantity(productId, quantity) {
        const item = this.cart.find(item => item.id === productId);
        if (item) {
            item.quantity = Math.max(1, quantity);
            this.updateCart();
        }
    },
    
    getTotal() {
        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },
    
    clear() {
        this.cart = [];
        this.updateCart();
    },
    
    updateCart() {
        // Emit event for Alpine components
        window.dispatchEvent(new CustomEvent('cart-updated', { detail: this.cart }));
    }
};
