@extends('template.back_admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6 w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <div class="flex flex-col sm:flex-row sm:items-center gap-4 pb-4 border-b border-slate-200">
        <div class="inline-flex items-center justify-center w-12 h-12 bg-gold-50 text-gold-600 rounded-xl shrink-0">
            <i class="fa-solid fa-pen-to-square text-xl"></i>
        </div>
        <div>
            <h1 class="text-xl sm:text-2xl font-serif font-bold text-charcoal-900">Edit Menu</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui informasi, harga, atau stok hidangan ini.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-8 shadow-sm">
        <!-- DIUBAH MENJADI $menu->slug -->
        <form action="{{ route('admin.menu.update', $menu->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="nama_menu" class="block text-sm font-semibold text-charcoal-900 mb-2">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('nama_menu') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-charcoal-900 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <div x-data="searchableRelationSelect(@js($kategori->map(fn ($kat) => ['id' => $kat->id, 'label' => $kat->nama_kategori])->values()), @js((string) old('kategori_id', $menu->kategori_id)))" @click.outside="open = false" class="relative">
                        <input type="hidden" name="kategori_id" :value="selected">
                        <button type="button" @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm text-charcoal-900 transition-all hover:bg-white focus:border-gold-500 focus:ring-4 focus:ring-gold-500/20">
                            <span x-text="selectedLabel() || '-- Pilih Kategori --'" :class="selected ? 'text-charcoal-900' : 'text-slate-400'"></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute left-0 right-0 z-30 mt-2 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                            <div class="border-b border-slate-100 p-2">
                                <div class="relative">
                                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                                    <input type="search" x-model="search" placeholder="Cari kategori..." class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-charcoal-900 focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20">
                                </div>
                            </div>
                            <div class="max-h-60 overflow-y-auto p-1">
                                <button type="button" x-show="filteredOptions().length === 0" class="w-full px-3 py-3 text-left text-sm text-slate-400" disabled>Tidak ada kategori ditemukan.</button>
                                <template x-for="option in filteredOptions()" :key="option.id">
                                    <button type="button" @click="select(option)" class="flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-left text-sm text-charcoal-900 hover:bg-gold-50 hover:text-gold-700">
                                        <span x-text="option.label"></span>
                                        <i x-show="selected == String(option.id)" class="fa-solid fa-check text-gold-600"></i>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    @error('kategori_id') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="gambar" class="block text-sm font-semibold text-charcoal-900 mb-2">Ganti Foto Menu (Opsional)</label>
                    <div class="flex items-center gap-3">
                        @if($menu->gambar)
                            <img src="{{ asset('storage/' . $menu->gambar) }}" class="w-12 h-12 rounded-lg object-cover border border-slate-200 shrink-0" alt="Current Image">
                        @endif
                        <input type="file" name="gambar" id="gambar" accept="image/*" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-3 py-2 transition-all duration-200 hover:bg-white file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gold-50 file:text-gold-700 hover:file:bg-gold-100">
                    </div>
                    @error('gambar') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="harga" class="block text-sm font-semibold text-charcoal-900 mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga', $menu->harga) }}" min="0" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('harga') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stok" class="block text-sm font-semibold text-charcoal-900 mb-2">Sisa Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" id="stok" value="{{ old('stok', $menu->stok) }}" min="0" required class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">
                    @error('stok') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-charcoal-900 mb-2">Deskripsi Singkat</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="bg-slate-50 border border-slate-200 text-charcoal-900 text-sm rounded-xl focus:ring-4 focus:ring-gold-500/20 focus:border-gold-500 block w-full px-4 py-3 transition-all duration-200 hover:bg-white">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-xs text-red-500 mt-2 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 flex items-center mt-2">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }} class="w-5 h-5 text-gold-500 bg-slate-100 border-slate-300 rounded focus:ring-gold-500 focus:ring-2">
                    <label for="is_active" class="ml-3 text-sm font-medium text-charcoal-900">Aktifkan Menu Ini</label>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-8">
                <a href="{{ route('admin.menu.index') }}" class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-center text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 hover:text-charcoal-900 transition-colors focus:ring-4 focus:ring-slate-100">Batal</a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gold-500 border border-transparent rounded-xl shadow-sm hover:bg-gold-600 hover:shadow active:scale-95 transition-all duration-200 focus:ring-4 focus:ring-gold-500/30">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchableRelationSelect', (options, initialValue = '') => ({
            open: false,
            search: '',
            selected: String(initialValue || ''),
            options,

            filteredOptions() {
                const query = this.search.toLowerCase().trim();

                return this.options.filter((option) => option.label.toLowerCase().includes(query));
            },

            selectedLabel() {
                return this.options.find((option) => String(option.id) === this.selected)?.label || '';
            },

            select(option) {
                this.selected = String(option.id);
                this.open = false;
                this.search = '';
            },
        }));
    });
</script>
@endsection
