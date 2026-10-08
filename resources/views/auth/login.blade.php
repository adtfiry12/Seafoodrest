<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Niskala Samudra</title>
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-charcoal-950 text-slate-800 font-sans min-h-screen relative">

    <!-- Background (Fixed) -->
    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1600" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950 via-charcoal-950/80 to-transparent backdrop-blur-[2px]"></div>
    </div>

    <!-- Wrapper Content (Bisa di-scroll) -->
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center py-10 px-4 sm:px-6">

        <!-- Card Login -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden my-auto">

            <div class="px-6 sm:px-8 pt-7 sm:pt-9 pb-5 text-center border-b border-slate-100 bg-slate-50/70">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-1.5 mb-3 group">
                    <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-11 sm:h-12 w-auto object-contain transition group-hover:opacity-90">
                    <span class="text-xl sm:text-2xl font-bold tracking-tight text-charcoal-950 font-serif leading-none mt-1">
                        Niskala<span class="text-gold-600 font-semibold ml-0.5">Samudra</span>
                    </span>
                </a>
                <p class="text-xs sm:text-sm text-slate-500 font-normal">Silakan masuk ke akun Anda.</p>
            </div>

            <div class="p-6 sm:p-7">
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-4 sm:space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-slate-400 text-xs"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-9 pr-3 py-2.5 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                        </div>
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-charcoal-900">Kata Sandi</label>
                        </div>
                        <div class="relative" x-data="{ showPassword: false }">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-slate-400 text-xs"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                class="block w-full pl-9 pr-10 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-400 hover:text-gold-600 transition"
                                :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                :title="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                                <i class="fa-solid fa-eye text-xs" x-show="!showPassword"></i>
                                <i class="fa-solid fa-eye-slash text-xs" x-show="showPassword"></i>
                            </button>
                        </div>
                    </div>

                    <div class="pt-1.5">
                        <button type="submit" class="w-full bg-charcoal-950 hover:bg-gold-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 text-xs sm:text-sm tracking-wider uppercase shadow-xs flex justify-center items-center gap-2 group">
                            Masuk <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-6 sm:px-8 py-4 border-t border-slate-100 bg-slate-50/70 text-center">
                <p class="text-xs sm:text-sm text-slate-600 font-normal">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-gold-700 hover:text-gold-800 hover:underline transition-all">Daftar sekarang</a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
