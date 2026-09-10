@extends('template.front')

@section('content')

@if($slides->count() > 0)
    <section class="relative bg-charcoal-950 overflow-hidden group"
             x-data="{ activeSlide: 1, totalSlides: {{ $slides->count() }} }"
             x-init="setInterval(() => { activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1 }, 6000)">

        <div class="relative h-[450px] sm:h-[650px] w-full">
            @foreach($slides as $index => $slide)
                <div x-show="activeSlide === {{ $index + 1 }}" x-transition.opacity.duration.1000ms class="absolute inset-0" {{ $index > 0 ? 'x-cloak' : '' }}>
                    <img src="{{ asset('storage/' . $slide->gambar) }}" class="w-full h-full object-cover opacity-50" alt="{{ $slide->judul }}">
                    <div class="absolute inset-0 flex flex-col justify-center items-center px-4">
                        <div class="bg-charcoal-950/40 backdrop-blur-md border border-white/10 p-6 sm:p-12 rounded-2xl max-w-3xl text-center shadow-2xl transform transition-all">
                            <span class="text-gold-400 text-[10px] sm:text-sm font-bold tracking-[0.2em] uppercase mb-2 sm:mb-4 block">Niskala Samudra</span>
                            <h1 class="text-3xl sm:text-6xl font-serif text-white mb-4 sm:mb-6 leading-tight">{{ $slide->judul }}</h1>
                            <p class="text-slate-200 text-xs sm:text-base mb-6 sm:mb-8 max-w-xl mx-auto font-light line-clamp-3 sm:line-clamp-none">{{ $slide->deskripsi }}</p>

                            @if($slide->menu_id && $slide->menu)
                                <a href="{{ route('menu.detail', $slide->menu->slug) }}" class="inline-block bg-gold-600 hover:bg-gold-500 text-white font-semibold py-2.5 sm:py-3.5 px-6 sm:px-8 rounded-xl text-xs sm:text-sm uppercase tracking-wider transition shadow-lg active:scale-95">
                                    Lihat Detail Menu
                                </a>
                            @else
                                <a href="{{ route('menu') }}" class="inline-block bg-gold-600 hover:bg-gold-500 text-white font-semibold py-2.5 sm:py-3.5 px-6 sm:px-8 rounded-xl text-xs sm:text-sm uppercase tracking-wider transition shadow-lg active:scale-95">
                                    Eksplorasi Menu
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($slides->count() > 1)
            <button @click="activeSlide = activeSlide === 1 ? totalSlides : activeSlide - 1"
                    class="hidden sm:flex absolute left-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white w-12 h-12 rounded-full items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-sm border border-white/20">
                <i class="fa-solid fa-chevron-left text-xl"></i>
            </button>
            <button @click="activeSlide = activeSlide === totalSlides ? 1 : activeSlide + 1"
                    class="hidden sm:flex absolute right-4 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/60 text-white w-12 h-12 rounded-full items-center justify-center opacity-0 group-hover:opacity-100 transition-all backdrop-blur-sm border border-white/20">
                <i class="fa-solid fa-chevron-right text-xl"></i>
            </button>

            <div class="absolute bottom-4 sm:bottom-6 left-0 right-0 flex justify-center space-x-2 sm:space-x-3">
                @foreach($slides as $index => $slide)
                    <button @click="activeSlide = {{ $index + 1 }}"
                            :class="activeSlide === {{ $index + 1 }} ? 'bg-gold-500 w-6 sm:w-8' : 'bg-white/50 w-2 sm:w-2.5'"
                            class="h-2 sm:h-2.5 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
        @endif
    </section>
@endif

<section id="tentang" class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=800" alt="Tentang Restoran" class="rounded-2xl shadow-2xl object-cover h-[400px] w-full">
                <div class="absolute -bottom-6 -right-6 bg-charcoal-900 text-gold-500 p-6 rounded-2xl hidden md:block border-4 border-white shadow-xl">
                    <p class="text-4xl font-serif font-bold text-center">10+</p>
                    <p class="text-xs uppercase tracking-widest mt-1 text-center">Tahun Pengalaman</p>
                </div>
            </div>
            <div>
                <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Cerita Kami</span>
                <h2 class="text-3xl sm:text-4xl font-serif text-charcoal-900 mb-6 leading-tight">Menghadirkan Cita Rasa Laut Nusantara yang Otentik</h2>
                <p class="text-slate-600 mb-4 leading-relaxed font-light text-sm sm:text-base">Niskala Samudra lahir dari kecintaan kami terhadap hasil laut lokal Indonesia. Kami berkomitmen untuk menyajikan hidangan seafood premium dengan bahan baku tangkapan nelayan lokal hari itu juga.</p>
                <p class="text-slate-600 mb-8 leading-relaxed font-light text-sm sm:text-base">Dengan paduan bumbu rempah rahasia keluarga dan teknik memasak modern, kami menjamin pengalaman bersantap yang elegan, nyaman, dan tak terlupakan untuk Anda dan keluarga.</p>

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

<section id="menu-section" class="bg-slate-50 border-t border-slate-100 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Spesial Hari Ini</span>
            <h2 class="text-3xl font-serif text-charcoal-900 sm:text-4xl">Pilihan Hidangan Terbaik</h2>
            <div class="w-12 sm:w-16 h-1 bg-gold-500 mx-auto mt-4"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($menus as $item)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-100 flex flex-col justify-between group">
                    <div>
                        <div class="relative overflow-hidden h-48 sm:h-56">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="flex items-center justify-center w-full h-full bg-slate-100 text-slate-300">
                                    <i class="fa-solid fa-utensils text-5xl"></i>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 bg-charcoal-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1.5 uppercase tracking-wider rounded-lg shadow-sm">
                                {{ $item->kategori->nama_kategori ?? 'Menu' }}
                            </span>

                            @if($item->stok < 5)
                                <span class="absolute top-3 right-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                                    Sisa {{ $item->stok }}
                                </span>
                            @endif
                        </div>
                        <div class="p-5 text-center">
                            <h3 class="font-serif font-bold text-charcoal-900 text-lg leading-snug group-hover:text-gold-600 transition truncate">{{ $item->nama_menu }}</h3>
                            <p class="text-gold-600 font-bold text-lg mt-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="p-4 pt-0 grid grid-cols-2 gap-2">
                        <a href="{{ route('menu.detail', $item->slug) }}" class="flex items-center justify-center bg-slate-100 hover:bg-charcoal-900 hover:text-white text-slate-600 font-bold py-2.5 rounded-xl transition duration-300 text-[11px] uppercase tracking-wider shadow-sm">
                            Detail
                        </a>

                        @php $imageUrl = $item->gambar ? asset('storage/' . $item->gambar) : 'https://ui-avatars.com/api/?name=Menu&background=f1f5f9&color=94a3b8'; @endphp

                        <button @click="addToCart({{ $item->id }}, @js($item->nama_menu), {{ $item->harga }}, @js($imageUrl), {{ $item->stok }})" class="flex items-center justify-center bg-gold-600 hover:bg-gold-500 text-white font-bold py-2.5 rounded-xl transition duration-300 text-[11px] uppercase tracking-wider shadow-sm active:scale-95">
                            <i class="fa-solid fa-cart-plus mr-1"></i> Pesan
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 bg-white border border-slate-200 rounded-2xl">
                    <i class="fa-solid fa-fish text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Menu belum tersedia saat ini. Koki kami sedang menyiapkannya!</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('menu') }}" class="inline-block px-8 py-3 text-sm font-bold text-charcoal-900 bg-white border-2 border-charcoal-900 rounded-xl hover:bg-charcoal-900 hover:text-white transition-colors duration-300 uppercase tracking-wider">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</section>

<section id="kontak" class="bg-white py-16 sm:py-24 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 sm:mb-16">
            <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Reservasi & Info</span>
            <h2 class="text-3xl font-serif text-charcoal-900 sm:text-4xl">Hubungi Kami</h2>
            <div class="w-12 sm:w-16 h-1 bg-gold-500 mx-auto mt-4"></div>
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
