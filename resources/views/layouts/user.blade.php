<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStep - Premium Sneakers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Animasi Transisi Halus untuk Notifikasi Melayang */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>
<body class="bg-white text-black">

    {{-- ── KOMPONEN NOTIFIKASI POP-UP PREMIUM (TOAST) ── --}}
    @if(session('success'))
    <div id="toast-notification" class="fixed top-6 right-6 z-50 flex items-center gap-4 bg-black text-white px-6 py-4 rounded-2xl shadow-2xl border border-neutral-800 animate-fade-in-up max-w-sm transition-all duration-300">
        <div class="bg-white/10 w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-shopping-bag text-sm text-white"></i>
        </div>
        <div class="flex-1">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-40">LuxeStep System</p>
            <p class="text-xs font-bold mt-0.5 text-neutral-200">{{ session('success') }}</p>
        </div>
        <button onclick="closeToast()" class="text-neutral-400 hover:text-white transition cursor-pointer ml-2 bg-transparent border-none">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        // Otomatis menutup pop-up dalam hitungan 4 detik
        setTimeout(() => {
            closeToast();
        }, 4000);

        function closeToast() {
            const toast = document.getElementById('toast-notification');
            if(toast) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => toast.remove(), 300);
            }
        }
    </script>
    @endif

    {{-- ── STRUKTUR UTAMA DASHBOARD USER ── --}}
    <div class="flex min-h-screen w-full">
        
        {{-- KOLOM KIRI: SIDEBAR NAVIGASI GLOBAL --}}
        <aside class="w-64 bg-white border-r border-gray-100 p-6 flex flex-col justify-between fixed h-full z-40">
            <div class="space-y-10">
                <div class="flex items-center gap-2 px-2">
                    <span class="font-black italic text-2xl tracking-tighter uppercase text-black">LuxeStep</span>
                </div>

                <nav class="space-y-2">
                    {{-- MENU 1: HOME --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('home') ? 'bg-black text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }} transition-all font-bold text-sm uppercase tracking-wider group">
                        <i class="fas fa-home w-5 text-center {{ request()->routeIs('home') ? 'text-white' : 'text-gray-400 group-hover:text-black' }}"></i> 
                        <span>Home</span>
                    </a>

                    {{-- MENU 2: CART (DILENGKAPI DIGITAL COUNTER) --}}
                    <a href="{{ route('cart.index') }}" class="flex items-center justify-between p-4 rounded-2xl {{ request()->routeIs('cart.index') ? 'bg-black text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }} transition-all font-bold text-sm uppercase tracking-wider group">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-shopping-cart w-5 text-center {{ request()->routeIs('cart.index') ? 'text-white' : 'text-gray-400 group-hover:text-black' }}"></i> 
                            <span>Cart</span>
                        </div>
                        
                        @auth
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                            @endphp
                            
                            @if($cartCount > 0)
                                <span class="{{ request()->routeIs('cart.index') ? 'bg-white text-black' : 'bg-black text-white' }} text-[10px] font-black px-2.5 py-1 rounded-full animate-pulse">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    {{-- MENU 3: MY ORDERS --}}
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('orders.index') ? 'bg-black text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }} transition-all font-bold text-sm uppercase tracking-wider group">
                        <i class="fas fa-box w-5 text-center {{ request()->routeIs('orders.index') ? 'text-white' : 'text-gray-400 group-hover:text-black' }}"></i> 
                        <span>My Orders</span>
                    </a>

                    {{-- MENU 4: ACCOUNT --}}
                    <a href="{{ route('account.index') }}" class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('account.index') ? 'bg-black text-white' : 'text-gray-400 hover:bg-gray-50 hover:text-black' }} transition-all font-bold text-sm uppercase tracking-wider group">
                        <i class="fas fa-user w-5 text-center {{ request()->routeIs('account.index') ? 'text-white' : 'text-gray-400 group-hover:text-black' }}"></i> 
                        <span>Account</span>
                    </a>
                </nav>
            </div>

            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 p-4 rounded-2xl text-red-500 hover:bg-red-50 transition-all font-black text-xs uppercase tracking-widest cursor-pointer bg-transparent border-none">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- KOLOM KANAN: KONTEN UTAMA DINAMIS --}}
        <main class="flex-1 ml-64 bg-white">
            @yield('content')
        </main>

    </div>

</body>
</html>