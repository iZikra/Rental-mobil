<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'FZ Rent Car')); ?></title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0f172a; }
            ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
            ::-webkit-scrollbar-thumb:hover { background: #475569; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-gray-50">
        <div class="min-h-screen flex flex-col">
            
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-grow">
                <?php echo e($slot); ?>

            </main>

            <footer class="bg-slate-900 text-slate-400 py-8 border-t border-white/10 text-center text-sm">
                <p>&copy; <?php echo e(date('Y')); ?> FZ Rent Car. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views\layouts\app.blade.php ENDPATH**/ ?>