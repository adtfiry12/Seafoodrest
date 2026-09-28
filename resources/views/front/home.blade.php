@extends('template.front')

@section('content')

@if($slides->count() > 0)
    <section class="relative bg-charcoal-950 overflow-hidden group select-none"
             x-data="{
                 activeSlide: 1,
                 totalSlides: {{ $slides->count() }},
                 isPaused: false,
                 init() {
                     setInterval(() => {
                         if (!this.isPaused) {
                             this.activeSlide = this.activeSlide === this.totalSlides ? 1 : this.activeSlide + 1;
                         }
                     }, 6000);
                 },
                 next() {
                     this.activeSlide = this.activeSlide === this.totalSlides ? 1 : this.activeSlide + 1;
                 },
                 prev() {
                     this.activeSlide = this.activeSlide === 1 ? this.totalSlides : this.activeSlide - 1;
                 }
             }"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false">

        <div class="relative h-[440px] sm:h-[560px] lg:h-[640px] w-full">
            @foreach($slides as $index => $slide)
                <div x-show="activeSlide === {{ $index + 1 }}"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0"
                     {{ $index > 0 ? 'x-cloak' : '' }}>

                    <!-- Background Image & Clean Gradient Overlay -->
                    <img src="{{ asset('storage/' . $slide->gambar) }}" class="w-full h-full object-cover object-center" alt="{{ $slide->judul }}">
                    <div class="absolute inset-0 bg-charcoal-950/65"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/90 via-transparent to-black/30"></div>

                    <!-- Slide Content: Clean, Centered, Professional -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-8 sm:py-12">
                            <span class="inline-block text-gold-400 text-[10px] sm:text-sm font-semibold tracking-[0.2em] sm:tracking-[0.25em] uppercase mb-2 sm:mb-4">
                                {{ $slide->sub_judul ?: 'Niskala Samudra' }}
                            </span>

                            <h1 class="text-2xl sm:text-4xl lg:text-6xl font-serif font-bold text-white mb-3 sm:mb-6 leading-snug sm:leading-tight max-w-3xl mx-auto">
                                {{ $slide->judul }}
                            </h1>

                            <p class="text-slate-200 text-xs sm:text-base lg:text-lg mb-6 sm:mb-8 max-w-2xl mx-auto font-light leading-relaxed line-clamp-2 sm:line-clamp-none">
                                {{ $slide->deskripsi }}
                            </p>

                            <div class="flex flex-row items-center justify-center gap-2.5 sm:gap-4 w-full sm:w-auto px-2 sm:px-0">
                                @if($slide->menu_id && $slide->menu)
                                    <a href="{{ route('menu.detail', $slide->menu->slug) }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-8 py-2.5 sm:py-3.5 bg-gold-600 hover:bg-gold-500 text-white font-bold text-xs sm:text-sm uppercase tracking-wider rounded-xl transition shadow-lg shadow-gold-600/25 active:scale-95 whitespace-nowrap">
                                        Lihat Menu <i class="fa-solid fa-arrow-right text-[10px] sm:text-xs"></i>
                                    </a>
                                @else
                                    <a href="{{ route('menu') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-8 py-2.5 sm:py-3.5 bg-gold-600 hover:bg-gold-500 text-white font-bold text-xs sm:text-sm uppercase tracking-wider rounded-xl transition shadow-lg shadow-gold-600/25 active:scale-95 whitespace-nowrap">
                                        Eksplorasi Menu <i class="fa-solid fa-arrow-right text-[10px] sm:text-xs"></i>
                                    </a>
                                @endif
                                <a href="{{ route('tentang') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 sm:gap-2 px-4 sm:px-8 py-2.5 sm:py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/25 font-bold text-xs sm:text-sm uppercase tracking-wider rounded-xl transition backdrop-blur-sm whitespace-nowrap">
                                    Tentang Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($slides->count() > 1)
            <!-- Clean Subtle Navigation Arrows -->
            <button @click="prev()"
                    aria-label="Slide sebelumnya"
                    class="hidden sm:flex absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full items-center justify-center bg-black/30 hover:bg-black/60 text-white border border-white/20 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all active:scale-90">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>

            <button @click="next()"
                    aria-label="Slide selanjutnya"
                    class="hidden sm:flex absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full items-center justify-center bg-black/30 hover:bg-black/60 text-white border border-white/20 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all active:scale-90">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </button>

            <!-- Bottom Progress Indicators -->
            <div class="absolute bottom-6 left-0 right-0 flex justify-center items-center space-x-2.5 z-20">
                @foreach($slides as $index => $slide)
                    <button @click="activeSlide = {{ $index + 1 }}"
                            aria-label="Pindah ke slide {{ $index + 1 }}"
                            :class="activeSlide === {{ $index + 1 }} ? 'bg-gold-500 w-8' : 'bg-white/40 hover:bg-white/70 w-2.5'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </section>
@endif

<section id="tentang" class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800" alt="Tentang Restoran" class="rounded-2xl shadow-2xl object-cover h-[320px] sm:h-[400px] w-full">
                <div class="absolute -bottom-6 -right-6 bg-charcoal-900 text-gold-500 p-6 rounded-2xl hidden md:block border-4 border-white shadow-xl">
                    <p class="text-4xl font-serif font-bold text-center">10+</p>
                    <p class="text-xs uppercase tracking-widest mt-1 text-center">Tahun Pengalaman</p>
                </div>
            </div>
            <div>
                <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-1.5 sm:mb-2 block">Cerita Kami</span>
                <h2 class="text-2xl sm:text-4xl font-serif text-charcoal-900 mb-4 sm:mb-6 leading-tight">Menghadirkan Cita Rasa Laut Nusantara yang Otentik</h2>
                <p class="text-slate-600 mb-4 leading-relaxed font-light text-xs sm:text-base">Niskala Samudra lahir dari kecintaan kami terhadap hasil laut lokal Indonesia. Kami berkomitmen untuk menyajikan hidangan seafood premium dengan bahan baku tangkapan nelayan lokal hari itu juga.</p>
                <p class="text-slate-600 mb-6 sm:mb-8 leading-relaxed font-light text-xs sm:text-base">Dengan paduan bumbu rempah rahasia keluarga dan teknik memasak modern, kami menjamin pengalaman bersantap yang elegan, nyaman, dan tak terlupakan untuk Anda dan keluarga.</p>

                <div class="grid grid-cols-2 gap-6 border-t border-slate-100 pt-8">
                    <div>
                        <i class="fa-solid fa-anchor text-3xl text-gold-500 mb-3"></i>
                        <h4 class="font-bold text-charcoal-900 mb-1">Seafood Segar</h4>
                        <p class="text-xs text-slate-500">Didatangkan langsung dari pelelangan ikan setiap pagi.</p>
                    </div>
                    <div>
                        <i class="fa-solid fa-award text-3xl text-gold-500 mb-3"></i>
                        <h4 class="font-bold text-charcoal-900 mb-1">Koki Profesional</h4>
                        <p class="text-xs text-slate-500">Diramu oleh koki berpengalaman di restoran bintang lima.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu-section" class="bg-slate-50 border-t border-slate-100 py-12 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-16">
            <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-1.5 sm:mb-2 block">Spesial Hari Ini</span>
            <h2 class="text-2xl font-serif text-charcoal-900 sm:text-4xl">Pilihan Hidangan Terbaik</h2>
            <div class="w-12 sm:w-16 h-1 bg-gold-500 mx-auto mt-3 sm:mt-4"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-6">
            @forelse ($menus as $item)
                <article class="bg-white rounded-2xl shadow-xs hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-200/80 flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('menu.detail', $item->slug) }}" class="block relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $item->stok <= 0 ? 'grayscale-[40%] opacity-85' : '' }}">

                            <div class="absolute inset-0 bg-linear-to-t from-charcoal-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <span class="absolute top-2 left-2 sm:top-3 sm:left-3 bg-charcoal-950/80 backdrop-blur-md text-gold-400 text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 uppercase tracking-wider rounded-md sm:rounded-lg shadow-xs">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>

                            @if($item->stok <= 0)
                                <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-slate-900/90 text-rose-300 border border-rose-500/30 text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg shadow-xs">
                                    Habis
                                </span>
                            @elseif($item->stok < 5)
                                <span class="absolute top-2 right-2 sm:top-3 sm:right-3 bg-rose-500 text-white text-[8px] sm:text-[10px] font-bold px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-md sm:rounded-lg shadow-xs">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </a>

                        <div class="p-2.5 sm:p-4">
                            <a href="{{ route('menu.detail', $item->slug) }}" class="block font-serif font-bold text-charcoal-900 text-xs sm:text-base leading-snug line-clamp-2 min-h-8 sm:min-h-10 group-hover:text-gold-600 transition-colors">
                                {{ $item->nama_menu }}
                            </a>
                            <div class="mt-2 flex items-baseline justify-between">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-wider text-slate-400 block">Harga</span>
                                    <p class="text-gold-600 font-bold text-xs sm:text-base">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                                @if($item->stok > 0)
                                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-medium hidden sm:inline-block">
                                        <i class="fa-solid fa-circle-check text-[9px] sm:text-[10px] mr-0.5"></i> Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-2.5 sm:p-4 pt-0 flex items-center gap-1.5 sm:gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" aria-label="Lihat detail {{ $item->nama_menu }}" title="Detail" class="h-8 w-8 sm:h-auto sm:w-auto sm:px-3 sm:py-2 flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-700 font-bold rounded-lg sm:rounded-xl transition duration-300 text-xs shrink-0 shadow-xs">
                            <i class="fa-solid fa-eye text-xs"></i><span class="hidden sm:inline ml-1 text-xs">Detail</span>
                        </a>
                        @if($item->stok > 0)
                            <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" aria-label="Tambah {{ $item->nama_menu }} ke keranjang" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 sm:px-3 bg-gold-600 hover:bg-gold-500 text-white font-bold text-xs rounded-lg sm:rounded-xl transition flex items-center justify-center gap-1 sm:gap-1.5 shadow-xs active:scale-95 whitespace-nowrap">
                                <i class="fa-solid fa-cart-plus text-[11px] sm:text-xs"></i> <span>+ Pesan</span>
                            </button>
                        @else
                            <button disabled aria-label="Menu habis" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-lg sm:rounded-xl cursor-not-allowed border border-slate-200 flex items-center justify-center gap-1 whitespace-nowrap">
                                <i class="fa-solid fa-ban text-[11px]"></i> <span>Habis</span>
                            </button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-10 bg-white border border-slate-200 rounded-2xl">
                    <i class="fa-solid fa-fish text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Menu belum tersedia saat ini. Koki kami sedang menyiapkannya!</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('menu') }}" class="inline-block px-8 py-3 text-sm font-bold text-charcoal-900 bg-white border-2 border-charcoal-900 rounded-xl hover:bg-charcoal-900 hover:text-white transition-colors duration-300 uppercase tracking-wider shadow-sm">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>

<section id="kontak" class="bg-white py-16 sm:py-24 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-8 sm:mb-16">
            <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-1.5 sm:mb-2 block">Reservasi & Info</span>
            <h2 class="text-2xl font-serif text-charcoal-900 sm:text-4xl">Hubungi Kami</h2>
            <div class="w-12 sm:w-16 h-1 bg-gold-500 mx-auto mt-3 sm:mt-4"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-4">
                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                    <div class="w-12 h-12 bg-white text-gold-600 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-slate-200">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 mb-1">Alamat Restoran</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">Jl. Pantai Kartini No. 12,<br>Jepara, Jawa Tengah 59417</p>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                    <div class="w-12 h-12 bg-white text-gold-600 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-slate-200">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 mb-1">Telepon / WhatsApp</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">+62 851-9119-4059</p>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-start gap-4">
                    <div class="w-12 h-12 bg-white text-gold-600 rounded-xl flex items-center justify-center text-xl shrink-0 shadow-sm border border-slate-200">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-charcoal-900 mb-1">Email</h4>
                        <p class="text-sm text-slate-500 leading-relaxed">niskalasamudra@gmail.com</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-charcoal-950 p-6 sm:p-10 rounded-3xl shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-10 opacity-5 pointer-events-none">
                    <i class="fa-solid fa-paper-plane text-9xl text-white"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="text-2xl font-serif text-white mb-2">Kirim Pesan / Pertanyaan</h3>
                    <p class="text-slate-400 text-sm mb-6">Punya pertanyaan seputar katering atau reservasi tempat? Jangan ragu hubungi kami!</p>

                    <form x-data @submit.prevent="window.open('https://wa.me/6285191194059?text=' + encodeURIComponent('Nama: ' + $refs.nama.value + '\nEmail: ' + $refs.email.value + '\nSubjek: ' + $refs.subject.value + '\nPesan: ' + $refs.message.value), '_blank')" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <input x-ref="nama" type="text" required placeholder="Nama Lengkap Anda" class="w-full bg-charcoal-900/50 border border-charcoal-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500 placeholder-slate-500">
                            </div>
                            <div>
                                <input x-ref="email" type="email" required placeholder="Alamat Email" class="w-full bg-charcoal-900/50 border border-charcoal-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500 placeholder-slate-500">
                            </div>
                        </div>
                        <div>
                            <input x-ref="subject" type="text" required placeholder="Subjek Pesan" class="w-full bg-charcoal-900/50 border border-charcoal-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500 placeholder-slate-500">
                        </div>
                        <div>
                            <textarea x-ref="message" rows="4" required placeholder="Tulis pesan Anda di sini..." class="w-full bg-charcoal-900/50 border border-charcoal-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500 placeholder-slate-500"></textarea>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-gold-600 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-gold-500 transition shadow-lg shadow-gold-500/30">
                            Kirim Pesan Sekarang <i class="fa-solid fa-paper-plane ml-2"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
