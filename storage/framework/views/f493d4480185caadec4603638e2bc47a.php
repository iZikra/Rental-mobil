<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        
        <meta name="theme-color" content="#3b82f6">
        <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
        <link rel="apple-touch-icon" href="https://cdn-icons-png.flaticon.com/512/3202/3202926.png">

        <title><?php echo e(config('app.name', 'FZ Rent Car')); ?></title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        
        <style>
    /* 1. Sembunyikan scrollbar untuk Chrome, Safari dan Opera */
    html::-webkit-scrollbar {
        display: none;
    }

    /* 2. Sembunyikan scrollbar untuk IE, Edge dan Firefox */
    html {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        overflow-x: hidden; /* Tetap kunci horizontal agar tidak pincang */
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
    }

    /* Pengaman agar konten tidak bergeser */
    .min-h-screen {
        width: 100%;
        overflow-x: hidden;
    }
</style>
    </head>
    <body class="font-sans antialiased text-slate-800 bg-gray-50">
        <div class="min-h-screen flex flex-col overflow-x-hidden w-full">
            
            <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <main class="flex-grow w-full overflow-x-hidden">
                <?php echo e($slot); ?>

            </main>

            <footer class="bg-slate-900 text-slate-400 py-8 border-t border-white/10 text-center text-sm w-full">
                <p>&copy; <?php echo e(date('Y')); ?> Multi Rent Car. All rights reserved.</p>
            </footer>
        </div>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register("<?php echo e(asset('sw.js')); ?>").then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }
        </script>
    </body>
</html><?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/layouts/app.blade.php ENDPATH**/ ?>