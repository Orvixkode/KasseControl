<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'KasseControl POS'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">KasseControl</h1>
                    
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="<?php echo e(route('dashboard')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                        <a href="<?php echo e(route('pos.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            POS
                        </a>
                        
                        <?php if(auth()->user()->canManageProducts()): ?>
                        <a href="<?php echo e(route('products.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Products
                        </a>
                        <a href="<?php echo e(route('inventory.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Inventory
                        </a>
                        <a href="<?php echo e(route('purchases.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Purchases
                        </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo e(route('sales.index')); ?>" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Sales
                        </a>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600"><?php echo e(auth()->user()->name); ?></span>
                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                    
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4">
        <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <?php echo e(session('error')); ?>

        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</body>
</html>
<?php /**PATH /home/rakin/github/Projects/KasseControl/resources/views/layouts/app.blade.php ENDPATH**/ ?>