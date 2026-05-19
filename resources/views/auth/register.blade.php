<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - LuxeStep</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { 
            font-family: 'Inter', sans-serif; 
            overflow: hidden; 
        }
        /* FIX: Menyembunyikan ikon mata bawaan browser Edge / IE agar tidak bertumpuk ganda */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }
    </style>
</head>
<body class="bg-white">

    <div class="flex h-screen w-full overflow-hidden">
        
        <div class="w-full md:w-1/2 flex flex-col justify-between p-12 lg:p-20">
            
            <div class="flex items-center gap-2">
                <img src="{{ asset('gambar/logo.png') }}" class="w-10 h-10 object-contain" alt="LuxeStep Logo">
                <span class="font-extrabold italic text-2xl tracking-tighter uppercase">LuxeStep</span>
            </div>

            <div class="max-w-md w-full mx-auto">
                <div class="mb-8 text-center md:text-left">
                    <h1 class="text-5xl font-extrabold tracking-tighter text-gray-900 mb-3 leading-tight">Create Your Account</h1>
                    <p class="text-gray-400 font-medium italic">Create an account to explore our collection.</p>
                </div>

                <form action="{{ url('/register') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email</label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-[#f9f9f9] border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-black transition-all font-bold text-sm text-black"
                                placeholder="Masukkan Email Anda">
                        </div>
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Username</label>
                        <div class="relative">
                            <i class="far fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-[#f9f9f9] border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-black transition-all font-bold text-sm text-black"
                                placeholder="Masukkan Username Anda">
                        </div>
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold italic">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Password</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            
                            {{-- FIX: Menambahkan id="password" --}}
                            <input type="password" name="password" id="password" required
                                class="w-full bg-[#f9f9f9] border-none rounded-2xl py-4 pl-14 pr-14 outline-none focus:ring-2 focus:ring-black transition-all font-bold text-sm text-black"
                                placeholder="Masukkan Password Anda">
                            
                            {{-- FIX: Mengaktifkan fungsionalitas button id="togglePassword" --}}
                            <button type="button" id="togglePassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-black cursor-pointer transition focus:outline-none z-10">
                                <i class="far fa-eye-slash text-base" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Confirm Password</label>
                        <div class="relative">
                            <i class="fas fa-shield-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            
                            {{-- FIX: Menambahkan id="password_confirmation" dan padding kanan (pr-14) agar teks input tidak menabrak ikon mata --}}
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full bg-[#f9f9f9] border-none rounded-2xl py-4 pl-14 pr-14 outline-none focus:ring-2 focus:ring-black transition-all font-bold text-sm text-black"
                                placeholder="Masukkan ulang password untuk verifikasi">
                            
                            {{-- FIX: Menambahkan button pengontrol mata untuk Confirm Password --}}
                            <button type="button" id="toggleConfirmPassword" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 hover:text-black cursor-pointer transition focus:outline-none z-10">
                                <i class="far fa-eye-slash text-base" id="confirmEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-black text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-gray-800 transition-all shadow-2xl shadow-black/20 mt-6">
                        Register
                    </button>
                </form>

                <p class="text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-8">
                    Sudah punya akun? <a href="{{ route('login') }}" class="text-black border-b-2 border-black pb-1 ml-1 hover:text-gray-600 hover:border-gray-600 transition">Login sekarang</a>
                </p>
            </div>

        </div>

        <div class="hidden md:block w-1/2 h-full relative overflow-hidden">
            <img src="{{ asset('gambar/image2.jpg') }}" 
                 class="absolute inset-0 w-full h-full object-cover" 
                 alt="New Balance Heritage">
            
            <div class="absolute top-12 right-12 text-right">
                <h2 class="text-white text-4xl font-black italic tracking-tighter uppercase leading-none">New Balance<br><span class="text-sm font-light tracking-[0.5em] opacity-60">Collection 2000</span></h2>
            </div>

            <div class="absolute inset-0 bg-gradient-to-l from-transparent to-black/10"></div>
        </div>

    </div>

    {{-- SCRIPT INTERAKTIF INDEPENDEN UNTUK KEDUA INPUT PASSWORD --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Logika Kontrol Password Utama
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePasswordButton.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                }
            });

            // 2. Logika Kontrol Confirm Password
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
            const confirmEyeIcon = document.getElementById('confirmEyeIcon');

            toggleConfirmPasswordButton.addEventListener('click', function () {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    confirmEyeIcon.classList.remove('fa-eye-slash');
                    confirmEyeIcon.classList.add('fa-eye');
                } else {
                    confirmEyeIcon.classList.remove('fa-eye');
                    confirmEyeIcon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>

</body>
</html>