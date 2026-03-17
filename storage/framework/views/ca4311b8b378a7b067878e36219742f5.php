<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login - Multi Rent Platform</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

body{
font-family:'Poppins',sans-serif;
overflow-x:hidden;
}

/* PARALLAX BACKGROUND */

.parallax{
background-image:url('https://images.unsplash.com/photo-1503376780353-7e6692767b70');
height:100vh;
background-attachment:fixed;
background-position:center;
background-repeat:no-repeat;
background-size:cover;
position:relative;
}

.overlay{
position:absolute;
inset:0;
background:linear-gradient(to right,rgba(0,0,0,.75),rgba(0,0,0,.35));
}

</style>
</head>


<body class="bg-gray-100">

<div class="flex min-h-screen">

<!-- PARALLAX SIDE -->

<div class="hidden lg:block lg:w-1/2 parallax">

<div class="overlay flex flex-col justify-center px-16 text-white">

<h1 class="text-5xl font-bold mb-6">
Platform Multi Rental Mobil
</h1>

<p class="text-lg text-gray-200 max-w-xl mb-6">
Temukan dan kelola berbagai layanan rental mobil dari berbagai mitra terpercaya dalam satu platform.
</p>

<div class="space-y-3 text-sm">

<div class="flex items-center gap-2">
🚗 <span>Terhubung dengan banyak rental mobil</span>
</div>

<div class="flex items-center gap-2">
📍 <span>Tersedia di berbagai kota</span>
</div>

<div class="flex items-center gap-2">
⚡ <span>Booking cepat dan mudah</span>
</div>

</div>

</div>
</div>


<!-- LOGIN FORM -->

<div class="w-full lg:w-1/2 flex items-center justify-center p-10 bg-white">

<div class="w-full max-w-md">

<div class="text-center mb-8">

<h2 class="text-3xl font-bold text-gray-900 flex justify-center items-center gap-2">
🚗 Multi Rent Platform
</h2>

<p class="text-sm text-gray-500 mt-2">
Masuk untuk mengakses dashboard penyewa atau mitra rental
</p>

</div>


<?php if($errors->any()): ?>

<div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
Email atau password salah
</div>

<?php endif; ?>


<form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">

<?php echo csrf_field(); ?>

<div>
<label class="text-sm text-gray-600">Alamat Email</label>

<input
type="email"
name="email"
required
class="w-full border rounded-lg px-4 py-3 mt-1 focus:ring-2 focus:ring-blue-500 outline-none"
placeholder="email@contoh.com"
>
</div>


<div>
<label class="text-sm text-gray-600">Kata Sandi</label>

<input
type="password"
name="password"
required
class="w-full border rounded-lg px-4 py-3 mt-1 focus:ring-2 focus:ring-blue-500 outline-none"
placeholder="••••••••"
>
</div>


<div class="flex justify-between text-sm">

<label class="flex items-center gap-2">
<input type="checkbox" name="remember">
Ingat saya
</label>

<a href="<?php echo e(route('password.request')); ?>" class="text-red-500">
Lupa password?
</a>

</div>


<button
type="submit"
class="w-full py-3 bg-gradient-to-r from-red-600 to-red-800 text-white rounded-lg font-semibold hover:opacity-90 transition"
>
Masuk ke Platform
</button>


<div class="text-center text-sm mt-5">

Belum punya akun?

<a href="<?php echo e(route('register')); ?>" class="text-red-600 font-medium">
Daftar Mitra / Penyewa
</a>

</div>

</form>

</div>

</div>

</div>


</body>
</html>
```
<?php /**PATH C:\Users\GF 63\rental-mobil\resources\views/auth/login.blade.php ENDPATH**/ ?>