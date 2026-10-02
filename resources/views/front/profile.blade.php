@extends('template.front')

@section('content')
<div class="bg-slate-50/70 py-10 sm:py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-6 sm:mb-8">
            <span class="text-xs font-semibold uppercase tracking-wider text-gold-600 block mb-1">Akun Saya</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-charcoal-950">Pengaturan Profil</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-sm mx-auto font-normal">Kelola informasi biodata, alamat default, dan keamanan kata sandi Anda.</p>
        </div>

        @if(session('success_profile') || session('success_password'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs sm:text-sm font-medium">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base shrink-0"></i>
                <span>{{ session('success_profile') ?? session('success_password') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xs border border-slate-200/80 overflow-hidden">
            <!-- Profile Banner -->
            <div class="p-6 sm:p-7 bg-charcoal-950 border-b border-charcoal-800/80 text-white flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 sm:gap-5">
                <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=d97706&color=fff' }}"
                     alt="{{ $user->nama }}"
                     class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-gold-500/40 shadow-xs">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold font-serif">{{ $user->nama }}</h3>
                    <p class="text-slate-400 text-xs sm:text-sm mt-0.5">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-2.5 py-0.5 bg-gold-500/15 border border-gold-500/30 text-gold-400 text-[11px] font-semibold rounded-md">Pelanggan</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-9">
                <!-- Update Biodata -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <h4 class="font-serif font-bold text-base text-charcoal-950 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-user-pen text-gold-500 text-sm"></i>
                        <span>Informasi Biodata</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('nama') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('email') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nomor HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 08123456789" class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('no_hp') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ganti Foto Profil</label>
                            <input type="file" name="foto" accept="image/*" class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-3 py-1.5 text-xs focus:bg-white focus:border-gold-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gold-50 file:text-gold-700 cursor-pointer">
                            @error('foto') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alamat Pengiriman Default</label>
                            <textarea name="alamat" rows="2" placeholder="Alamat lengkap untuk pengiriman..." class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="text-right pt-2">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-charcoal-950 hover:text-white text-xs sm:text-sm font-semibold rounded-xl transition shadow-xs">Simpan Profil</button>
                    </div>
                </form>

                <!-- Update Password -->
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4 pt-4 border-t border-slate-100">
                    @csrf
                    @method('PUT')

                    <h4 class="font-serif font-bold text-base text-charcoal-950 border-b border-slate-100 pb-2.5 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-gold-500 text-sm"></i>
                        <span>Ganti Kata Sandi</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" required class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('current_password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kata Sandi Baru</label>
                            <input type="password" name="password" required class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" required class="bg-slate-50/70 border border-slate-200/80 rounded-xl w-full px-4 py-2.5 text-xs sm:text-sm focus:bg-white focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition">
                            @error('password_confirmation') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="text-right pt-2">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-charcoal-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-gold-600 transition shadow-xs">Perbarui Kata Sandi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
