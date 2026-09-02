@extends(
    Auth::user()->role === 'admin'
        ? 'template.back_admin'
        : (Auth::user()->role === 'karyawan'
            ? 'template.back_karyawan'
            : 'template.front')
)

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-user-gear text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Pengaturan Profil</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data informasi akun dan keamanan kata sandi Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center shadow-sm">
                <div class="relative w-28 h-28 mx-auto rounded-full overflow-hidden border-4 border-gold-500/20 mb-4 bg-slate-100">
                    <img src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=f59e0b&color=fff' }}"
                         alt="{{ $user->nama }}"
                         class="w-full h-full object-cover">
                </div>
                <h2 class="text-lg font-bold text-charcoal-900">{{ $user->nama }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>
                <div class="mt-3">
                    @if($user->role === 'admin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                            Admin
                        </span>
                    @elseif($user->role === 'karyawan')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                            Karyawan
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Pelanggan
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
                <h3 class="text-base font-bold text-charcoal-900 pb-3 border-b border-slate-100 mb-6">Informasi Biodata</h3>

                @if(session('success_profile'))
                    <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        {{ session('success_profile') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                            @error('nama') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-charcoal-900 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                            @error('email') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-charcoal-900 mb-2">Nomor HP / WhatsApp</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                            @error('no_hp') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-semibold text-charcoal-900 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-semibold text-charcoal-900 mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                            @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="foto" class="block text-sm font-semibold text-charcoal-900 mb-2">Ganti Foto Profil</label>
                            <input type="file" name="foto" id="foto" accept="image/*" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-3 py-2 transition-all duration-200 hover:bg-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                            @error('foto') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-charcoal-900 mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="2" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 active:scale-95 transition-all duration-200">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
                <h3 class="text-base font-bold text-charcoal-900 pb-3 border-b border-slate-100 mb-6">Ganti Kata Sandi</h3>

                @if(session('success_password'))
                    <div class="mb-5 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                        {{ session('success_password') }}
                    </div>
                @endif

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-charcoal-900 mb-2">Kata Sandi Saat Ini <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" id="current_password" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                        @error('current_password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-charcoal-900 mb-2">Kata Sandi Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password" required minlength="8" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                            @error('password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-charcoal-900 mb-2">Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white">
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-charcoal-900 hover:bg-charcoal-800 rounded-xl shadow-sm active:scale-95 transition-all duration-200">
                            <i class="fa-solid fa-key"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
