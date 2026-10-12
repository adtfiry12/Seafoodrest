@extends('template.back_admin')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <i class="fa-solid fa-user text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">Detail Pengguna</p>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">{{ $pengguna->nama }}</h1>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pengguna.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('admin.pengguna.edit', $pengguna->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gold-500 rounded-xl hover:bg-gold-600 transition-colors">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col items-center text-center">
                <div class="w-28 h-28 rounded-full bg-slate-100 border-4 border-white ring-1 ring-slate-200 overflow-hidden">
                    @if($pengguna->foto)
                        <img src="{{ asset('storage/' . $pengguna->foto) }}" alt="{{ $pengguna->nama }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                            <span class="text-3xl font-bold uppercase">{{ substr($pengguna->nama, 0, 2) }}</span>
                        </div>
                    @endif
                </div>
                <h2 class="mt-4 text-lg font-bold text-charcoal-900">{{ $pengguna->nama }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $pengguna->email }}</p>
                <div class="mt-4">
                    @if($pengguna->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200"><i class="fa-solid fa-shield-halved"></i> Admin</span>
                    @elseif($pengguna->role === 'karyawan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200"><i class="fa-solid fa-user-tie"></i> Karyawan</span>
                    @elseif($pengguna->role === 'kurir')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200"><i class="fa-solid fa-motorcycle"></i> Kurir</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-user"></i> Pelanggan</span>
                    @endif
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-slate-100 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-slate-400">Bergabung</span>
                    <span class="font-medium text-slate-700">{{ $pengguna->created_at?->translatedFormat('d F Y') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
            <h2 class="font-serif text-lg font-bold text-charcoal-900">Informasi Profil</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nama Lengkap</p>
                    <p class="mt-1 text-sm text-charcoal-900">{{ $pengguna->nama }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Email</p>
                    <p class="mt-1 text-sm text-charcoal-900 break-all">{{ $pengguna->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nomor HP</p>
                    <p class="mt-1 text-sm text-charcoal-900">{{ $pengguna->no_hp ?: 'Belum diatur' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Jenis Kelamin</p>
                    <p class="mt-1 text-sm text-charcoal-900">{{ $pengguna->jenis_kelamin_label }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tanggal Lahir</p>
                    <p class="mt-1 text-sm text-charcoal-900">{{ $pengguna->tanggal_lahir?->translatedFormat('d F Y') ?? 'Belum diatur' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Role Akses</p>
                    <p class="mt-1 text-sm capitalize text-charcoal-900">{{ $pengguna->role }}</p>
                </div>
                <div class="sm:col-span-2 pt-5 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Alamat</p>
                    <p class="mt-1 text-sm leading-6 text-slate-600 whitespace-pre-line">{{ $pengguna->alamat ?: 'Belum diatur' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
