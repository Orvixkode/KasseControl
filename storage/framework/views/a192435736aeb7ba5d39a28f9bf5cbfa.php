<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Create Purchase Order</h1>
    </div>

    <form action="<?php echo e(route('purchases.store')); ?>" method="POST" x-data="purchaseForm()">
        <?php echo csrf_field(); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Purchase Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <select name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Select Supplier (Optional) --</option>
                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($supplier->id); ?>"><?php echo e($supplier->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date *</label>
                            <input type="datetime-local" name="purchase_date" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold">Products</h2>
                        <button type="button" @click="showProductModal = true" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            + Add Product
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="border-b">
                                        <td class="px-4 py-3">
                                            <span x-text="item.name"></span>
                                            <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.id">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" :name="'items['+index+'][quantity]'" x-model="item.quantity" 
                                                @input="calculateItemTotal(index)" min="0.01" step="0.01"
                                                class="w-24 px-2 py-1 border rounded text-center">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" :name="'items['+index+'][unit_cost]'" x-model="item.unit_cost" 
                                                @input="calculateItemTotal(index)" min="0" step="0.01"
                                                class="w-32 px-2 py-1 border rounded text-right">
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            $<span x-text="item.total.toFixed(2)"></span>
                                            <input type="hidden" :name="'items['+index+'][total]'" :value="item.total">
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-red-600 hover:text-red-800">
                                                ✕
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="items.length === 0">
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        No products added. Click "Add Product" to begin.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-700">
                            <span>Items:</span>
                            <span x-text="items.length"></span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-900 pt-3 border-t">
                            <span>Total:</span>
                            <span>$<span x-text="totalAmount.toFixed(2)"></span></span>
                        </div>
                        <input type="hidden" name="total" :value="totalAmount">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Not Paid Yet --</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                        <input type="number" name="paid" step="0.01" min="0" placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Additional notes..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <button type="submit" class="w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 font-semibold">
                            Create Purchase Order
                        </button>
                        <a href="<?php echo e(route('purchases.index')); ?>" class="block w-full px-4 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-center">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection Modal -->
        <div x-show="showProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Select Product</h3>
                    <button type="button" @click="showProductModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">
                        ×
                    </button>
                </div>

                <input type="text" x-model="searchQuery" @input="filterProducts()" 
                    placeholder="Search products..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg mb-4">

                <div class="space-y-2">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div @click="addProduct(product)" 
                            class="p-3 border rounded-lg hover:bg-blue-50 cursor-pointer flex justify-between items-center">
                            <div>
                                <div class="font-medium" x-text="product.name"></div>
                                <div class="text-sm text-gray-500" x-text="product.code"></div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Cost: $<span x-text="product.cost"></span></div>
                                <div class="text-xs text-gray-400">Stock: <span x-text="product.stock"></span></div>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredProducts.length === 0" class="text-center py-8 text-gray-500">
                        No products found
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function purchaseForm() {
    return {
        items: [],
        showProductModal: false,
        searchQuery: '',
        products: <?php echo json_encode($products, 15, 512) ?>,
        filteredProducts: [],

        init() {
            this.filteredProducts = this.products;
        },

        filterProducts() {
            const query = this.searchQuery.toLowerCase();
            this.filteredProducts = this.products.filter(p => 
                p.name.toLowerCase().includes(query) || 
                p.code.toLowerCase().includes(query)
            );
        },

        addProduct(product) {
            // Check if already added
            const exists = this.items.find(item => item.id === product.id);
            if (exists) {
                alert('Product already added!');
                return;
            }

            this.items.push({
                id: product.id,
                name: product.name,
                code: product.code,
                quantity: 1,
                unit_cost: parseFloat(product.cost),
                total: parseFloat(product.cost)
            });

            this.showProductModal = false;
            this.searchQuery = '';
            this.filteredProducts = this.products;
        },

        removeItem(index) {
            this.items.splice(index, 1);
        },

        calculateItemTotal(index) {
            const item = this.items[index];
            item.total = item.quantity * item.unit_cost;
        },

        get totalAmount() {
            return this.items.reduce((sum, item) => sum + item.total, 0);
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rakin/github/Projects/KasseControl/resources/views/purchases/create.blade.php ENDPATH**/ ?>