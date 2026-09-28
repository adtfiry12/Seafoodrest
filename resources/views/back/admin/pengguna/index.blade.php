@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
                <i class="fa-solid fa-users text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Data Pengguna</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola akses admin, karyawan, dan akun pelanggan.</p>
            </div>
        </div>
        <a href="{{ route('admin.pengguna.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
            <i class="fa-solid fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium animate-fade-in-down">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Search Pengguna -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.pengguna.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / HP..." class="w-full bg-white border border-slate-200 rounded-xl pl-4 pr-10 py-2.5 text-sm focus:ring-gold-500 focus:border-gold-500 text-charcoal-900 shadow-sm">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </div>

            <select name="role" onchange="this.form.submit()" class="bg-white border border-slate-200 text-sm rounded-xl focus:ring-gold-500 px-3.5 py-2.5 text-slate-700 shadow-sm w-full sm:w-auto">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>

            @if(request('search') || request('role'))
                <a href="{{ route('admin.pengguna.index') }}" class="px-3 py-2 text-xs font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition flex items-center gap-1">
                    <i class="fa-solid fa-xmark"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 lg:py-5">Pengguna</th>
                        <th class="px-6 py-4 lg:py-5">Kontak</th>
                        <th class="px-6 py-4 lg:py-5">Role</th>
                        <th class="px-6 py-4 lg:py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pengguna as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        @if($item->foto)
                                            <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-200">
                                                <span class="text-xs font-bold uppercase">{{ substr($item->nama, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-charcoal-900">{{ $item->nama }}</p>
                                        <p class="text-xs text-slate-500">{{ $item->jenis_kelamin_label }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-charcoal-900">{{ $item->email }}</p>
                                <p class="text-xs text-slate-500">{{ $item->no_hp ?? 'No. HP Belum diatur' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->role == 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">Admin</span>
                                @elseif($item->role == 'karyawan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">Karyawan</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Pelanggan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('admin.pengguna.show', $item->id) }}" title="Lihat detail pengguna" aria-label="Lihat detail pengguna" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.pengguna.edit', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-gold-600 hover:bg-gold-50 transition-all duration-200">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST" class="inline-block" @submit.prevent="openDeleteModal($event, 'Hapus pengguna ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                    <i class="fa-solid fa-users text-4xl text-slate-300"></i>
                                    <p class="text-base font-medium">Belum ada data pengguna.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pengguna->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                <div class="max-w-full overflow-x-auto pb-1">
                    {{ $pengguna->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
