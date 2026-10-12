@extends('template.back_admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-user-plus text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Tambah Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Daftarkan akun baru beserta role dan informasi personalnya.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <form action="{{ route('admin.pengguna.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2 pb-2 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-charcoal-900">Informasi Akun</h3>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('nama') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-charcoal-900 mb-2">Email Valid <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('email') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-charcoal-900 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required minlength="8" placeholder="Minimal 8 karakter" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('password') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-charcoal-900 mb-2">Hak Akses (Role) <span class="text-red-500">*</span></label>
                    @php
                        $roleOptions = [
                            ['id' => 'pelanggan', 'label' => 'Pelanggan'],
                            ['id' => 'karyawan', 'label' => 'Karyawan Operasional'],
                            ['id' => 'kurir', 'label' => 'Kurir Pengantaran'],
                            ['id' => 'admin', 'label' => 'Admin'],
                        ];
                    @endphp
                    <div x-data="customSimpleSelect(@js($roleOptions), @js(old('role', 'pelanggan')), '-- Pilih Role --')" @click.outside="open = false" class="relative">
                        <input type="hidden" name="role" :value="selected" required>
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-2xs">
                            <span x-text="selectedLabel()" class="font-medium text-charcoal-900"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl p-1">
                            <template x-for="option in options" :key="option.id">
                                <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 transition-colors" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'hover:bg-gold-50 hover:text-gold-700'">
                                    <span x-text="option.label"></span>
                                    <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('role') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 pt-4 pb-2 border-b border-slate-100 mt-2">
                    <h3 class="text-lg font-bold text-charcoal-900">Informasi Personal</h3>
                </div>

                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-charcoal-900 mb-2">Nomor HP/WhatsApp</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('no_hp') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-charcoal-900 mb-2">Jenis Kelamin</label>
                    @php
                        $genderOptions = [
                            ['id' => 'L', 'label' => 'Laki-Laki'],
                            ['id' => 'P', 'label' => 'Perempuan'],
                        ];
                    @endphp
                    <div x-data="customSimpleSelect(@js($genderOptions), @js(old('jenis_kelamin')), '-- Pilih Jenis Kelamin --')" @click.outside="open = false" class="relative">
                        <input type="hidden" name="jenis_kelamin" :value="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-2xs">
                            <span x-text="selectedLabel()" :class="selected ? 'text-charcoal-900 font-medium' : 'text-slate-400'"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400 shrink-0 ml-2 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-40 mt-1.5 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl p-1">
                            <button type="button" @click="select({id: '', label: '-- Pilih Jenis Kelamin --'})" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm text-slate-500 hover:bg-slate-50">
                                <span>-- Pilih Jenis Kelamin --</span>
                                <i x-show="!selected" class="fa-solid fa-check text-slate-400"></i>
                            </button>
                            <template x-for="option in options" :key="option.id">
                                <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 transition-colors" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'hover:bg-gold-50 hover:text-gold-700'">
                                    <span x-text="option.label"></span>
                                    <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                    @error('jenis_kelamin') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-charcoal-900 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('tanggal_lahir') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-semibold text-charcoal-900 mb-2">Foto Profil</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-2.5 transition-all duration-200 hover:bg-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                    @error('foto') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-semibold text-charcoal-900 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.pengguna.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition-colors focus:ring-4 focus:ring-slate-100">Batal</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
