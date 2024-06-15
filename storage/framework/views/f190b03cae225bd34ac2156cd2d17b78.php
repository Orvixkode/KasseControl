<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Point of Sale</h1>

    <div class="grid grid-cols-12 gap-6" x-data="posSystem()">
        <!-- Products Grid -->
        <div class="col-span-8">
            <div class="bg-white rounded shadow p-4">
                <input type="text" 
                       x-model="searchQuery"
                       @input="searchProducts"
                       placeholder="Search products..."
                       class="w-full px-4 py-2 border rounded mb-4">

                <div class="grid grid-cols-4 gap-3" style="max-height: 600px; overflow-y: auto;">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addToCart(product)" 
                             class="border rounded p-3 cursor-pointer hover:bg-blue-50"
                             :class="product.stock <= 0 ? 'opacity-50' : ''">
                            <div class="font-semibold text-sm mb-1" x-text="product.name"></div>
                            <div class="text-xs text-gray-600 mb-2" x-text="product.code"></div>
                            <div class="font-bold text-blue-600" x-text="'$' + parseFloat(product.price).toFixed(2)"></div>
                            <div class="text-xs" 
                                 :class="product.stock <= 0 ? 'text-red-600' : 'text-gray-500'"
                                 x-text="'Stock: ' + product.stock"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="col-span-4">
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-xl font-bold mb-4">Cart</h2>

                <!-- Customer -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Customer</label>
                    <select x-model="selectedCustomer" class="w-full px-3 py-2 border rounded">
                        <option value="">Walk-in</option>
                        <template x-for="customer in customers" :key="customer.id">
                            <option :value="customer.id" x-text="customer.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Cart Items -->
                <div class="mb-4" style="max-height: 300px; overflow-y: auto;">
                    <template x-if="cart.length === 0">
                        <p class="text-gray-500 text-center py-8">Cart is empty</p>
                    </template>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex-1">
                                <div class="font-medium text-sm" x-text="item.name"></div>
                                <div class="text-xs text-gray-600" x-text="'$' + parseFloat(item.price).toFixed(2)"></div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="decreaseQuantity(index)" class="px-2 py-1 bg-gray-200 rounded text-sm">-</button>
                                <span class="w-8 text-center" x-text="item.quantity"></span>
                                <button @click="increaseQuantity(index)" class="px-2 py-1 bg-gray-200 rounded text-sm">+</button>
                                <button @click="removeFromCart(index)" class="ml-2 text-red-600">×</button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Totals -->
                <div class="border-t pt-3 mb-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span class="font-semibold" x-text="'$' + subtotal.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Tax (10%):</span>
                        <span class="font-semibold" x-text="'$' + tax.toFixed(2)"></span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span class="text-blue-600" x-text="'$' + total.toFixed(2)"></span>
                    </div>
                </div>

                <!-- Payment -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Payment</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="paymentMethod = 'cash'" 
                                :class="paymentMethod === 'cash' ? 'bg-blue-600 text-white' : 'bg-white border'"
                                class="px-3 py-2 rounded">Cash</button>
                        <button @click="paymentMethod = 'card'" 
                                :class="paymentMethod === 'card' ? 'bg-blue-600 text-white' : 'bg-white border'"
                                class="px-3 py-2 rounded">Card</button>
                        <button @click="paymentMethod = 'mobile'" 
                                :class="paymentMethod === 'mobile' ? 'bg-blue-600 text-white' : 'bg-white border'"
                                class="px-3 py-2 rounded">Mobile</button>
                    </div>
                </div>

                <!-- Actions -->
                <button @click="checkout" 
                        :disabled="cart.length === 0"
                        :class="cart.length === 0 ? 'bg-gray-300' : 'bg-blue-600 hover:bg-blue-700'"
                        class="w-full py-3 text-white rounded font-semibold mb-2">
                    Complete Sale
                </button>
                <button @click="clearCart" class="w-full py-2 bg-gray-200 rounded">
                    Clear Cart
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function posSystem() {
    return {
        products: <?php echo json_encode($products, 15, 512) ?>,
        customers: <?php echo json_encode($customers, 15, 512) ?>,
        cart: [],
        searchQuery: '',
        filteredProducts: [],
        selectedCustomer: '',
        paymentMethod: 'cash',

        init() {
            this.filteredProducts = this.products;
        },

        searchProducts() {
            const query = this.searchQuery.toLowerCase();
            this.filteredProducts = this.products.filter(p => 
                p.name.toLowerCase().includes(query) || 
                p.code.toLowerCase().includes(query)
            );
        },

        addToCart(product) {
            if (product.stock <= 0) {
                alert('Product out of stock!');
                return;
            }

            const existing = this.cart.find(item => item.id === product.id);
            if (existing) {
                if (existing.quantity < product.stock) {
                    existing.quantity++;
                } else {
                    alert('Not enough stock!');
                }
            } else {
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    code: product.code,
                    price: product.price,
                    quantity: 1,
                    stock: product.stock
                });
            }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        increaseQuantity(index) {
            if (this.cart[index].quantity < this.cart[index].stock) {
                this.cart[index].quantity++;
            } else {
                alert('Not enough stock!');
            }
        },

        decreaseQuantity(index) {
            if (this.cart[index].quantity > 1) {
                this.cart[index].quantity--;
            } else {
                this.removeFromCart(index);
            }
        },

        clearCart() {
            this.cart = [];
            this.selectedCustomer = '';
        },

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get tax() {
            return this.subtotal * 0.10;
        },

        get total() {
            return this.subtotal + this.tax;
        },

        async checkout() {
            if (this.cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            if (!this.paymentMethod) {
                alert('Please select a payment method');
                return;
            }

            // Transform cart items to match the expected format
            const items = this.cart.map(item => {
                const subtotal = item.price * item.quantity;
                const tax = subtotal * 0.10;
                const total = subtotal + tax;
                
                return {
                    product_id: item.id,
                    quantity: item.quantity,
                    unit_price: item.price,
                    subtotal: subtotal,
                    tax: tax,
                    discount: 0,
                    total: total
                };
            });

            try {
                const response = await fetch('<?php echo e(route("pos.checkout")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        customer_id: this.selectedCustomer || null,
                        payment_method: this.paymentMethod,
                        items: items,
                        subtotal: this.subtotal,
                        tax: this.tax,
                        discount: 0,
                        total: this.total,
                        paid: this.total
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Sale completed successfully! Invoice #' + data.sale_id);
                    this.clearCart();
                    window.location.href = '/sales/' + data.sale_id;
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Error processing sale: ' + error.message);
            }
        }
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rakin/github/Projects/KasseControl/resources/views/pos/index.blade.php ENDPATH**/ ?>