<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Niskala Samudra</title>
    <link rel="icon" href="{{ asset('image/niskala sammudra.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-charcoal-950 text-slate-800 font-sans min-h-screen relative">

    <!-- Background (Fixed supaya tidak ikut scroll) -->
    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1600" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950 via-charcoal-950/80 to-transparent backdrop-blur-[2px]"></div>
    </div>

    <!-- Wrapper Content (Bisa di-scroll) -->
    <div class="relative z-10 flex min-h-screen flex-col items-center justify-center py-10 px-4 sm:px-6">

        <!-- Card Register -->
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden my-auto">

            <div class="px-6 sm:px-8 pt-7 sm:pt-9 pb-5 text-center border-b border-slate-100 bg-slate-50/70">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-1.5 mb-3 group">
                    <img src="{{ asset('image/niskala sammudra.png') }}" alt="Logo Niskala Samudra" class="h-11 sm:h-12 w-auto object-contain transition group-hover:opacity-90">
                    <span class="text-xl sm:text-2xl font-bold tracking-tight text-charcoal-950 font-serif leading-none mt-1">
                        Niskala<span class="text-gold-600 font-semibold ml-0.5">Samudra</span>
                    </span>
                </a>
                <p class="text-xs sm:text-sm text-slate-500 font-normal">Buat akun baru Anda.</p>
            </div>

            <div class="p-6 sm:p-7">
                <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="block w-full px-3.5 py-2.5 border @error('nama') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            @error('nama')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="block w-full px-3.5 py-2.5 border @error('email') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            @error('email')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Kata Sandi</label>
                            <input type="password" id="password" name="password" required
                                class="block w-full px-3.5 py-2.5 border @error('password') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            @error('password')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Konfirmasi Sandi</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="block w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                        </div>

                        <div>
                            <label for="no_hp" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Nomor HP</label>
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Opsional"
                                class="block w-full px-3.5 py-2.5 border @error('no_hp') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            @error('no_hp')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin"
                                class="block w-full px-3.5 py-2.5 border @error('jenis_kelamin') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="tanggal_lahir" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="block w-full px-3.5 py-2.5 border @error('tanggal_lahir') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">
                            @error('tanggal_lahir')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-xs font-semibold text-charcoal-900 mb-1.5">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="2" placeholder="Opsional"
                                class="block w-full px-3.5 py-2.5 border @error('alamat') border-rose-500 @else border-slate-200 @enderror rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-gold-500 focus:border-gold-500 transition">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-charcoal-950 hover:bg-gold-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 text-xs sm:text-sm tracking-wider uppercase shadow-xs flex justify-center items-center gap-2 group">
                            Daftar <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </button>
                    </div>

                    <div class="text-center text-xs sm:text-sm text-slate-600 pt-2">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="font-semibold text-gold-700 hover:text-gold-800 transition">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
