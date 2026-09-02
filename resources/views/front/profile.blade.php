@extends('template.front')

@section('content')
<div class="bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-serif text-charcoal-900">Pengaturan Profil</h2>
            <p class="text-sm text-slate-500 mt-2">Kelola informasi akun dan kata sandi Anda.</p>
        </div>

        @if(session('success_profile') || session('success_password'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                {{ session('success_profile') ?? session('success_password') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 sm:p-8 bg-charcoal-900 text-white flex items-center gap-6">
                <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=f59e0b&color=fff' }}"
                     alt="{{ $user->nama }}"
                     class="w-20 h-20 rounded-full object-cover border-4 border-gold-500">
                <div>
                    <h3 class="text-xl font-bold font-serif">{{ $user->nama }}</h3>
                    <p class="text-slate-300 text-sm">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-2 py-0.5 bg-gold-600 text-white text-[10px] font-bold rounded uppercase tracking-wider">Pelanggan</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-10">
                <!-- Update Biodata -->
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <h4 class="font-bold text-charcoal-900 border-b border-slate-100 pb-2">Informasi Biodata</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Nomor HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Ganti Foto Profil</label>
                            <input type="file" name="foto" class="bg-slate-50 border border-slate-200 rounded-xl w-full px-3 py-1.5 text-sm focus:ring-gold-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gold-100 file:text-gold-700">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Alamat Pengiriman Default</label>
                            <textarea name="alamat" rows="2" class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="px-6 py-2.5 bg-gold-600 text-white text-sm font-bold rounded-xl hover:bg-gold-700 transition">Simpan Profil</button>
                    </div>
                </form>

                <!-- Update Password -->
                <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <h4 class="font-bold text-charcoal-900 border-b border-slate-100 pb-2">Ganti Kata Sandi</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" required class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Kata Sandi Baru</label>
                            <input type="password" name="password" required class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-charcoal-900 mb-2">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" required class="bg-slate-50 border border-slate-200 rounded-xl w-full px-4 py-2.5 text-sm focus:ring-gold-500">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="px-6 py-2.5 bg-charcoal-900 text-white text-sm font-bold rounded-xl hover:bg-charcoal-800 transition">Perbarui Kata Sandi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
