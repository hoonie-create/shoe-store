<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LuxeStep</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        /* Background utama diubah menjadi abu-abu sangat muda agar bersih */
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f8f8; color: #1a1a1a; }
    </style>
</head>
<body class="flex">

    <aside class="w-72 bg-white h-screen sticky top-0 border-r border-gray-100 flex flex-col p-6">
        <div class="mb-10 flex items-center gap-3 px-2">
            <img src="{{ asset('gambar/logo.png') }}" class="h-8">
            <h1 class="text-xl font-extrabold tracking-tighter text-black uppercase italic">LUXESTEP</h1>
        </div>

        <nav class="flex-1 space-y-2">
            {{-- Menu Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-4 p-4 {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white shadow-xl' : 'text-gray-500 hover:bg-gray-100' }} rounded-2xl transition-all duration-300">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span class="font-bold text-sm tracking-widest uppercase">Dashboard</span>
            </a>
            
            {{-- Menu Manajemen Produk --}}
            <a href="{{ route('admin.products.index') }}" 
               class="flex items-center gap-4 p-4 {{ request()->routeIs('admin.products.*') ? 'bg-black text-white shadow-xl' : 'text-gray-500 hover:bg-gray-100' }} rounded-2xl transition-all duration-300">
                <i class="fas fa-box w-5 text-center"></i>
                <span class="font-bold text-sm tracking-widest uppercase">Manajemen Produk</span>
            </a>
            
            {{-- Menu Laporan Keuangan --}}
            <a href="{{ route('admin.finance.index') }}" 
               class="flex items-center gap-4 p-4 {{ request()->routeIs('admin.finance.index') ? 'bg-black text-white shadow-xl' : 'text-gray-500 hover:bg-gray-100' }} rounded-2xl transition-all duration-300">
                <i class="fas fa-chart-line w-5 text-center"></i>
                <span class="font-bold text-sm tracking-widest uppercase">Laporan Keuangan</span>
            </a>

            {{-- Menu Manajemen User  --}}
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-4 p-4 {{ request()->routeIs('admin.users.index') ? 'bg-black text-white shadow-xl' : 'text-gray-500 hover:bg-gray-100' }} rounded-2xl transition-all duration-300">
                <i class="fas fa-users w-5 text-center"></i>
                <span class="font-bold text-sm tracking-widest uppercase">Manajemen User</span>
            </a>

            {{-- Menu account --}}
            <a href="{{ route('admin.account.index') }}" 
            class="flex items-center gap-4 p-4 {{ request()->routeIs('admin.account.index') ? 'bg-black text-white shadow-xl' : 'text-gray-500 hover:bg-gray-100' }} rounded-2xl transition-all duration-300">
                <i class="fas fa-user-shield w-5 text-center"></i>
                <span class="font-bold text-sm tracking-widest uppercase">Akun Saya</span>
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="mt-auto pt-6 border-t border-gray-100">
            @csrf
            <button type="submit" class="flex items-center gap-4 p-4 w-full text-red-500 hover:bg-red-50 rounded-2xl transition font-bold text-xs tracking-widest uppercase">
                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                <span>Logout Admin</span>
            </button>
        </form>
    </aside>

    <main class="flex-1 p-10">
        @yield('content')
    </main>

</body>
</html>