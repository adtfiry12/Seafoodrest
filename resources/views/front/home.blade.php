@extends('template.front')

@section('content')

@if($slides->count() > 0)
    <section class="relative bg-charcoal-950 overflow-hidden group select-none touch-pan-y cursor-grab active:cursor-grabbing"
             x-data="{
                 activeSlide: 1,
                 totalSlides: {{ $slides->count() }},
                 isPaused: false,
                 timer: null,
                 isDragging: false,
                 startX: 0,
                 startY: 0,
                 dragOffsetX: 0,
                 hasMoved: false,
                 sliderWidth: 1,
                 init() {
                     this.updateWidth();
                     window.addEventListener('resize', () => this.updateWidth());
                     this.startTimer();
                 },
                 updateWidth() {
                     if (this.$refs.sliderTrack) {
                         this.sliderWidth = this.$refs.sliderTrack.offsetWidth || window.innerWidth;
                     }
                 },
                 startTimer() {
                     this.stopTimer();
                     this.timer = setInterval(() => {
                         if (!this.isPaused && !this.isDragging) {
                             this.next();
                         }
                     }, 6000);
                 },
                 stopTimer() {
                     if (this.timer) {
                         clearInterval(this.timer);
                         this.timer = null;
                     }
                 },
                 next() {
                     this.dragOffsetX = 0;
                     this.activeSlide = this.activeSlide === this.totalSlides ? 1 : this.activeSlide + 1;
                     this.startTimer();
                 },
                 prev() {
                     this.dragOffsetX = 0;
                     this.activeSlide = this.activeSlide === 1 ? this.totalSlides : this.activeSlide - 1;
                     this.startTimer();
                 },
                 startDrag(x, y) {
                     this.updateWidth();
                     this.isDragging = true;
                     this.startX = x;
                     this.startY = y;
                     this.dragOffsetX = 0;
                     this.hasMoved = false;
                     this.stopTimer();
                 },
                 onDrag(x, y) {
                     if (!this.isDragging) return;
                     const diffX = x - this.startX;
                     const diffY = y - this.startY;

                     // Allow vertical scroll if user moves more vertically (mobile)
                     if (!this.hasMoved && Math.abs(diffY) > Math.abs(diffX) && Math.abs(diffY) > 8) {
                         this.isDragging = false;
                         this.dragOffsetX = 0;
                         return;
                     }

                     if (Math.abs(diffX) > 6) {
                         this.hasMoved = true;
                     }

                     // Rubber-band resistance on ends
                     let offset = diffX;
                     if ((this.activeSlide === 1 && diffX > 0) || (this.activeSlide === this.totalSlides && diffX < 0)) {
                         offset = diffX * 0.35;
                     }
                     this.dragOffsetX = offset;
                 },
                 endDrag() {
                     if (!this.isDragging) return;
                     this.isDragging = false;
                     const threshold = Math.min(this.sliderWidth * 0.12, 60);

                     if (this.dragOffsetX < -threshold) {
                         this.next();
                     } else if (this.dragOffsetX > threshold) {
                         this.prev();
                     } else {
                         this.dragOffsetX = 0;
                         this.startTimer();
                     }

                     setTimeout(() => {
                         this.hasMoved = false;
                     }, 80);
                 }
             }"
             @mouseenter="isPaused = true"
             @mouseleave="isPaused = false; if (isDragging) endDrag()"
             @mousedown="if ($event.button === 0) startDrag($event.clientX, $event.clientY)"
             @mousemove="onDrag($event.clientX, $event.clientY)"
             @mouseup="endDrag()"
             @touchstart.passive="if ($event.touches && $event.touches.length === 1) startDrag($event.touches[0].clientX, $event.touches[0].clientY)"
             @touchmove.passive="if (isDragging && $event.touches && $event.touches.length === 1) onDrag($event.touches[0].clientX, $event.touches[0].clientY)"
             @touchend="endDrag()"
             @touchcancel="endDrag()">

        <div class="relative h-[420px] sm:h-[540px] lg:h-[600px] w-full overflow-hidden">
            <div x-ref="sliderTrack"
                 class="flex h-full w-full will-change-transform"
                 :class="isDragging ? 'transition-none' : 'transition-transform duration-500 ease-out'"
                 :style="`transform: translateX(calc(-${(activeSlide - 1) * 100}% + ${dragOffsetX}px));`">
                @foreach($slides as $index => $slide)
                    <div class="w-full h-full shrink-0 relative">
                        <!-- Background Image & Clean Gradient Overlay -->
                        <img src="{{ asset('storage/' . $slide->gambar) }}" class="w-full h-full object-cover object-center pointer-events-none select-none" alt="{{ $slide->judul }}" draggable="false">
                        <div class="absolute inset-0 bg-charcoal-950/60 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/90 via-transparent to-black/20 pointer-events-none"></div>

                        <!-- Slide Content: Clean, Centered, Professional -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-6 sm:py-10">
                                <span class="inline-block text-gold-400 text-xs sm:text-sm font-medium tracking-widest uppercase mb-2 sm:mb-3">
                                    {{ $slide->sub_judul ?: 'Niskala Samudra' }}
                                </span>

                                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif font-bold text-white mb-3 sm:mb-5 leading-tight sm:leading-[1.18] max-w-2xl mx-auto select-none">
                                    {{ $slide->judul }}
                                </h1>

                                <p class="text-slate-200 text-xs sm:text-sm lg:text-base mb-6 sm:mb-8 max-w-xl mx-auto font-light leading-relaxed line-clamp-2 sm:line-clamp-none select-none">
                                    {{ $slide->deskripsi }}
                                </p>

                                <div class="flex flex-row items-center justify-center gap-2.5 sm:gap-3.5 w-full sm:w-auto px-2 sm:px-0">
                                    @if($slide->menu_id && $slide->menu)
                                        <a href="{{ route('menu.detail', $slide->menu->slug) }}"
                                           @click="if (hasMoved) { $event.preventDefault(); $event.stopPropagation(); }"
                                           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 sm:px-7 py-2.5 sm:py-3 bg-gold-600 hover:bg-gold-500 text-white font-semibold text-xs sm:text-sm tracking-wide rounded-xl transition shadow-md shadow-gold-950/30 active:scale-95 whitespace-nowrap">
                                            Lihat Menu <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('menu') }}"
                                           @click="if (hasMoved) { $event.preventDefault(); $event.stopPropagation(); }"
                                           class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 sm:px-7 py-2.5 sm:py-3 bg-gold-600 hover:bg-gold-500 text-white font-semibold text-xs sm:text-sm tracking-wide rounded-xl transition shadow-md shadow-gold-950/30 active:scale-95 whitespace-nowrap">
                                            Eksplorasi Menu <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('tentang') }}"
                                       @click="if (hasMoved) { $event.preventDefault(); $event.stopPropagation(); }"
                                       class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-5 sm:px-7 py-2.5 sm:py-3 bg-white/10 hover:bg-white/20 text-white border border-white/20 font-semibold text-xs sm:text-sm tracking-wide rounded-xl transition backdrop-blur-xs whitespace-nowrap">
                                        Tentang Kami
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($slides->count() > 1)
            <!-- Clean Subtle Navigation Arrows -->
            <button @click="prev()"
                    aria-label="Slide sebelumnya"
                    class="hidden sm:flex absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full items-center justify-center bg-black/25 hover:bg-black/50 text-white border border-white/15 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-all z-20 cursor-pointer">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>

            <button @click="next()"
                    aria-label="Slide selanjutnya"
                    class="hidden sm:flex absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full items-center justify-center bg-black/25 hover:bg-black/50 text-white border border-white/15 backdrop-blur-xs opacity-0 group-hover:opacity-100 transition-all z-20 cursor-pointer">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </button>

            <!-- Bottom Progress Indicators -->
            <div class="absolute bottom-5 left-0 right-0 flex justify-center items-center space-x-2 z-20">
                @foreach($slides as $index => $slide)
                    <button @click="activeSlide = {{ $index + 1 }}; startTimer();"
                            aria-label="Pindah ke slide {{ $index + 1 }}"
                            :class="activeSlide === {{ $index + 1 }} ? 'bg-gold-500 w-6' : 'bg-white/30 hover:bg-white/60 w-2'"
                            class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"></button>
                @endforeach
            </div>
        @endif
    </section>
@endif

<section id="tentang" class="bg-white py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800" alt="Tentang Restoran" class="rounded-2xl shadow-lg object-cover h-[300px] sm:h-[380px] w-full">
                <div class="absolute -bottom-4 -right-4 sm:-bottom-5 sm:-right-5 bg-charcoal-950/95 text-gold-400 p-4 sm:p-5 rounded-2xl hidden md:block border border-charcoal-800 shadow-xl backdrop-blur-xs">
                    <p class="text-3xl font-serif font-bold text-center text-white">10+</p>
                    <p class="text-[10px] tracking-wider uppercase mt-0.5 text-center text-gold-400 font-semibold">Tahun Pengalaman</p>
                </div>
            </div>
            <div>
                <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase mb-2 block">Cerita Kami</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif text-charcoal-950 mb-4 leading-tight font-bold">Menghadirkan Cita Rasa Laut Nusantara yang Otentik</h2>
                <p class="text-slate-600 mb-3.5 leading-relaxed font-normal text-xs sm:text-sm">Niskala Samudra lahir dari kecintaan kami terhadap hasil laut lokal Indonesia. Kami berkomitmen untuk menyajikan hidangan seafood premium dengan bahan baku tangkapan nelayan lokal hari itu juga.</p>
                <p class="text-slate-600 mb-6 sm:mb-8 leading-relaxed font-normal text-xs sm:text-sm">Dengan paduan bumbu rempah rahasia keluarga dan teknik memasak modern, kami menjamin pengalaman bersantap yang elegan, nyaman, dan tak terlupakan untuk Anda dan keluarga.</p>

                <div class="grid grid-cols-2 gap-5 border-t border-slate-100 pt-6">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gold-50 border border-gold-200/60 text-gold-700 flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-anchor"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-charcoal-900 text-sm mb-0.5">Seafood Segar</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Didatangkan langsung dari pelelangan ikan setiap pagi.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gold-50 border border-gold-200/60 text-gold-700 flex items-center justify-center text-sm shrink-0">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-charcoal-900 text-sm mb-0.5">Koki Profesional</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Diramu oleh koki berpengalaman di restoran bintang lima.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="menu-section" class="bg-slate-50/70 border-t border-slate-200/70 py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 sm:mb-12">
            <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase mb-1.5 block">Spesial Hari Ini</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-charcoal-950">Pilihan Hidangan Terbaik</h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
            @forelse ($menus as $item)
                <article class="bg-white rounded-2xl shadow-[0_1px_3px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-200/70 flex flex-col justify-between group">
                    <div>
                        <a href="{{ route('menu.detail', $item->slug) }}" class="block relative overflow-hidden aspect-4/3 bg-slate-100">
                            @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $item->nama_menu }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ $item->stok <= 0 ? 'grayscale-[40%] opacity-85' : '' }}">

                            <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <span class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 bg-charcoal-950/75 backdrop-blur-xs text-gold-400 text-[9px] sm:text-[10px] font-medium px-2 py-0.5 rounded-md shadow-xs">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>

                            @if($item->stok <= 0)
                                <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-slate-900/90 text-rose-300 border border-rose-500/30 text-[9px] sm:text-[10px] font-medium px-2 py-0.5 rounded-md shadow-xs">
                                    Habis
                                </span>
                            @elseif($item->stok < 5)
                                <span class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-rose-500 text-white text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-md shadow-xs">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </a>

                        <div class="p-3 sm:p-4">
                            <a href="{{ route('menu.detail', $item->slug) }}" class="block font-serif font-bold text-charcoal-950 text-xs sm:text-base leading-snug line-clamp-2 min-h-8 sm:min-h-10 group-hover:text-gold-700 transition-colors">
                                {{ $item->nama_menu }}
                            </a>
                            <div class="mt-2.5 flex items-baseline justify-between">
                                <div>
                                    <span class="text-[9px] sm:text-[10px] font-medium text-slate-400 block">Harga</span>
                                    <p class="text-gold-700 font-bold text-xs sm:text-base">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                                @if($item->stok > 0)
                                    <span class="text-[10px] sm:text-[11px] text-emerald-600 font-medium hidden sm:inline-flex items-center gap-1">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Tersedia
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 pt-0 flex items-center gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" aria-label="Lihat detail {{ $item->nama_menu }}" title="Detail" class="h-8 w-8 sm:h-auto sm:w-auto sm:px-3 sm:py-2 flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-700 font-medium rounded-xl transition duration-200 text-xs shrink-0 shadow-2xs">
                            <i class="fa-solid fa-eye text-xs"></i><span class="hidden sm:inline ml-1 text-xs">Detail</span>
                        </a>
                        @if($item->stok > 0)
                            <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" aria-label="Tambah {{ $item->nama_menu }} ke keranjang" class="flex-1 h-8 sm:h-auto sm:py-2 px-2.5 sm:px-3 bg-gold-600 hover:bg-gold-500 text-white font-semibold text-xs rounded-xl transition flex items-center justify-center gap-1.5 shadow-2xs active:scale-95 whitespace-nowrap">
                                <i class="fa-solid fa-cart-plus text-[11px]"></i> <span>+ Pesan</span>
                            </button>
                        @else
                            <button disabled aria-label="Menu habis" class="flex-1 h-8 sm:h-auto sm:py-2 px-2 bg-slate-100 text-slate-400 font-medium text-xs rounded-xl cursor-not-allowed border border-slate-200/80 flex items-center justify-center gap-1 whitespace-nowrap">
                                <i class="fa-solid fa-ban text-[11px]"></i> <span>Habis</span>
                            </button>
                        @endif
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-10 bg-white border border-slate-200 rounded-2xl">
                    <i class="fa-solid fa-fish text-3xl text-slate-300 mb-2.5"></i>
                    <p class="text-slate-500 text-sm font-medium">Menu belum tersedia saat ini. Koki kami sedang menyiapkannya!</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('menu') }}" class="inline-block px-7 py-3 text-xs font-semibold text-charcoal-900 bg-white border border-charcoal-900 rounded-xl hover:bg-charcoal-900 hover:text-white transition duration-200 uppercase tracking-wider shadow-2xs">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>

<section id="kontak" class="bg-white py-14 sm:py-20 border-t border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-8 sm:mb-12">
            <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase mb-1.5 block">Reservasi & Info</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-charcoal-950">Hubungi Kami</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

            <div class="lg:col-span-1 space-y-3.5">
                <div class="p-5 bg-slate-50 border border-slate-200/70 rounded-2xl flex items-start gap-3.5">
                    <div class="w-10 h-10 bg-white text-gold-600 rounded-xl flex items-center justify-center text-sm shrink-0 shadow-2xs border border-slate-200/80">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-charcoal-900 text-sm mb-0.5">Alamat Restoran</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">Jl. Pantai Kartini No. 12,<br>Jepara, Jawa Tengah 59417</p>
                    </div>
                </div>

                <div class="p-5 bg-slate-50 border border-slate-200/70 rounded-2xl flex items-start gap-3.5">
                    <div class="w-10 h-10 bg-white text-gold-600 rounded-xl flex items-center justify-center text-sm shrink-0 shadow-2xs border border-slate-200/80">
                        <i class="fa-solid fa-phone-volume"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-charcoal-900 text-sm mb-0.5">Telepon / WhatsApp</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">+62 851-9119-4059</p>
                    </div>
                </div>

                <div class="p-5 bg-slate-50 border border-slate-200/70 rounded-2xl flex items-start gap-3.5">
                    <div class="w-10 h-10 bg-white text-gold-600 rounded-xl flex items-center justify-center text-sm shrink-0 shadow-2xs border border-slate-200/80">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-charcoal-900 text-sm mb-0.5">Email</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">niskalasamudra@gmail.com</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-charcoal-950 p-6 sm:p-8 lg:p-9 rounded-2xl shadow-xl border border-charcoal-900 relative">
                <div class="relative z-10">
                    <h3 class="text-xl sm:text-2xl font-serif font-bold text-white mb-1.5">Kirim Pesan / Pertanyaan</h3>
                    <p class="text-slate-400 text-xs sm:text-sm mb-5 leading-relaxed">Punya pertanyaan seputar katering atau reservasi tempat? Jangan ragu hubungi kami!</p>

                    <form x-data @submit.prevent="window.open('https://wa.me/6285191194059?text=' + encodeURIComponent('Nama: ' + $refs.nama.value + '\nEmail: ' + $refs.email.value + '\nSubjek: ' + $refs.subject.value + '\nPesan: ' + $refs.message.value), '_blank')" class="space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <input x-ref="nama" type="text" required placeholder="Nama Lengkap Anda" class="w-full bg-charcoal-900/80 border border-charcoal-800 text-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:border-gold-500 focus:ring-1 focus:ring-gold-500 placeholder-slate-500 outline-none transition">
                            </div>
                            <div>
                                <input x-ref="email" type="email" required placeholder="Alamat Email" class="w-full bg-charcoal-900/80 border border-charcoal-800 text-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:border-gold-500 focus:ring-1 focus:ring-gold-500 placeholder-slate-500 outline-none transition">
                            </div>
                        </div>
                        <div>
                            <input x-ref="subject" type="text" required placeholder="Subjek Pesan" class="w-full bg-charcoal-900/80 border border-charcoal-800 text-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:border-gold-500 focus:ring-1 focus:ring-gold-500 placeholder-slate-500 outline-none transition">
                        </div>
                        <div>
                            <textarea x-ref="message" rows="4" required placeholder="Tulis pesan Anda di sini..." class="w-full bg-charcoal-900/80 border border-charcoal-800 text-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm focus:border-gold-500 focus:ring-1 focus:ring-gold-500 placeholder-slate-500 outline-none transition"></textarea>
                        </div>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-gold-600 text-white text-xs sm:text-sm font-semibold uppercase tracking-wider rounded-xl hover:bg-gold-500 transition shadow-sm">
                            Kirim Pesan Sekarang <i class="fa-solid fa-paper-plane ml-1.5 text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
