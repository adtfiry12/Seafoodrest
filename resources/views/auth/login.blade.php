<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Niskala Samudra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        charcoal: { 800: '#27272a', 900: '#18181b', 950: '#09090b' },
                        gold: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' }
                    },
                    fontFamily: { serif: ['Playfair Display', 'serif'], sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="bg-charcoal-950 text-slate-800 font-sans min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1600" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950 via-charcoal-950/80 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-md px-4 sm:px-0">
        <div class="bg-white rounded-xl shadow-2xl border border-gold-500/20 overflow-hidden">

            <div class="px-8 pt-10 pb-6 text-center border-b border-slate-100 bg-slate-50">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2 mb-4 group">
                    <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-14 w-auto object-contain transition group-hover:scale-105">
                    <span class="text-2xl font-bold tracking-wide text-charcoal-950 font-serif leading-none mt-2">
                        Niskala<span class="text-gold-600">Samudra</span>
                    </span>
                </a>
                <p class="text-sm text-slate-500 font-light">Silakan masuk ke akun Anda.</p>
            </div>

            <div class="p-8">
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-charcoal-900 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-slate-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pl-10 pr-3 py-3 border @error('email') border-red-500 @else border-slate-300 @enderror rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-charcoal-900">Kata Sandi</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-gold-500">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-charcoal-900 hover:bg-gold-600 text-white font-semibold py-3 px-4 rounded-md transition duration-300 text-sm tracking-wider uppercase shadow-md flex justify-center items-center gap-2 group">
                            Masuk <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50 text-center">
                <p class="text-sm text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-gold-600 hover:text-gold-700 hover:underline transition-all">Daftar sekarang</a>
                </p>
            </div>

        </div>
    </div>
</body>
</html>
