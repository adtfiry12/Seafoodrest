@extends('template.back_admin')

@section('content')
<div class="space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-4">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
                <i class="fa-solid fa-utensils text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Menu Seafood</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola daftar hidangan, harga, dan stok restoran.</p>
            </div>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
            <i class="fa-solid fa-plus"></i> Tambah Menu
        </a>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium animate-fade-in-down">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter & Search Menu -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
        <form x-ref="filterForm" action="{{ route('admin.menu.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama menu..." class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full pl-4 pr-10 py-2.5 transition-all duration-200 hover:bg-white shadow-sm">
                <button type="submit" class="absolute right-1 top-1 w-8 h-8 flex items-center justify-center text-slate-400 hover:text-gold-600 transition" aria-label="Cari menu">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>
            </div>

            <!-- Searchable Dropdown Kategori (Sesuai Style CRUD Create/Edit) -->
            <div x-data="searchableFilterSelect(@js($kategoris->map(fn ($kat) => ['id' => $kat->id, 'label' => $kat->nama_kategori])->values()), @js((string) request('kategori_id', '')))" @click.outside="open = false" class="relative w-full sm:w-60">
                <input type="hidden" name="kategori_id" :value="selected">
                <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20 shadow-sm">
                    <span class="truncate flex items-center gap-2">
                        <i class="fa-solid fa-tags text-xs text-slate-400"></i>
                        <span x-text="selectedLabel() || 'Semua Kategori'" class="font-medium text-charcoal-900"></span>
                    </span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-30 mt-2 min-w-[240px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                    <div class="border-b border-slate-100 p-2">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                            <input type="search" x-model="search" placeholder="Cari kategori..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-charcoal-900 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20">
                        </div>
                    </div>
                    <div class="max-h-60 overflow-y-auto p-1">
                        <!-- Option: Semua Kategori -->
                        <button type="button" @click="select('')" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm transition-all" :class="selected === '' ? 'bg-gold-50 text-gold-700 font-bold' : 'text-charcoal-900 hover:bg-gold-50 hover:text-gold-700'">
                            <span>Semua Kategori</span>
                            <i x-show="selected === ''" class="fa-solid fa-check text-gold-600"></i>
                        </button>
                        <button type="button" x-show="filteredOptions().length === 0" class="w-full px-3 py-3 text-left text-sm text-slate-400" disabled>Tidak ada kategori ditemukan.</button>
                        <template x-for="option in filteredOptions()" :key="option.id">
                            <button type="button" @click="select(option.id)" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm transition-all" :class="selected == String(option.id) ? 'bg-gold-50 text-gold-700 font-bold' : 'text-charcoal-900 hover:bg-gold-50 hover:text-gold-700'">
                                <span x-text="option.label"></span>
                                <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            @if(request('search') || request('kategori_id'))
                <a href="{{ route('admin.menu.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200/60 rounded-xl transition shadow-sm">
                    <i class="fa-solid fa-xmark"></i> Reset Filter
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 lg:py-5">Menu</th>
                        <th class="px-6 py-4 lg:py-5">Kategori</th>
                        <th class="px-6 py-4 lg:py-5">Harga & Stok</th>
                        <th class="px-6 py-4 lg:py-5">Status</th>
                        <th class="px-6 py-4 lg:py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menu as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden shrink-0">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <i class="fa-solid fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-charcoal-900">{{ $item->nama_menu }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gold-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Stok: <span class="{{ $item->stok < 5 ? 'text-red-500 font-bold' : '' }}">{{ $item->stok }}</span></p>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1">
                                <a href="{{ route('admin.menu.show', $item->slug) }}" title="Lihat detail menu" aria-label="Lihat detail menu" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.menu.edit', $item->slug) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-gold-600 hover:bg-gold-50 transition-all duration-200">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.menu.destroy', $item->slug) }}" method="POST" class="inline-block" @submit.prevent="openDeleteModal($event, 'Hapus menu ini secara permanen?')">
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                    <i class="fa-solid fa-utensils text-4xl text-slate-300"></i>
                                    <p class="text-base font-medium">Belum ada data menu.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($menu->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                <div class="max-w-full overflow-x-auto pb-1">
                    {{ $menu->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    const initSearchableFilter = () => {
        Alpine.data('searchableFilterSelect', (options, initialValue = '') => ({
            open: false,
            search: '',
            selected: String(initialValue || ''),
            options,

            filteredOptions() {
                const query = this.search.toLowerCase().trim();

                return this.options.filter((option) => option.label.toLowerCase().includes(query));
            },

            selectedLabel() {
                if (!this.selected) return 'Semua Kategori';
                return this.options.find((option) => String(option.id) === this.selected)?.label || 'Semua Kategori';
            },

            select(id) {
                this.selected = String(id || '');
                this.open = false;
                this.search = '';
                this.$nextTick(() => {
                    this.$root.closest('form').submit();
                });
            },
        }));
    };

    if (window.Alpine) {
        initSearchableFilter();
    } else {
        document.addEventListener('alpine:init', initSearchableFilter);
    }
</script>
@endsection
