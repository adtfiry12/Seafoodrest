@extends('template.front')

@section('content')

<section class="relative h-[320px] sm:h-[420px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('image/seafood.webp') }}" alt="Suasana Restoran Niskala Samudra" class="w-full h-full object-cover object-center opacity-50">
        <div class="absolute inset-0 bg-gradient-to-r from-charcoal-950 via-charcoal-950/85 to-charcoal-950/70"></div>
    </div>

    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <span class="text-gold-400 font-semibold tracking-wider text-xs uppercase mb-2 block">Kisah Kami</span>
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif text-white mb-3 leading-tight font-bold">Membawa Kekayaan Samudra ke Atas Meja Anda</h1>
        <p class="text-slate-300 text-xs sm:text-sm font-normal max-w-xl mx-auto leading-relaxed">
            Lebih dari sekadar restoran, Niskala Samudra adalah perayaan atas hasil laut perairan Nusantara, diolah dengan hasrat dan dedikasi tinggi.
        </p>
    </div>
</section>

<section class="bg-white py-14 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 sm:gap-14 items-center">

            <div class="relative grid grid-cols-2 gap-3 sm:gap-5">
                <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?q=80&w=600" alt="Koki Memasak" class="rounded-2xl shadow-lg w-full h-[220px] sm:h-[340px] object-cover mt-5 sm:mt-10">
                <img src="{{ asset('image/lokasi.webp') }}" alt="Hidangan Kepiting" class="rounded-2xl shadow-lg w-full h-[220px] sm:h-[340px] object-cover">

                <div class="absolute -bottom-4 right-4 sm:-bottom-5 sm:right-6 bg-charcoal-950/95 backdrop-blur-xs text-white px-4 py-3 sm:px-5 sm:py-3.5 rounded-2xl border border-charcoal-800 shadow-xl flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gold-500/10 border border-gold-500/30 flex items-center justify-center text-gold-400 text-xs">
                        <i class="fa-solid fa-anchor"></i>
                    </div>
                    <div>
                        <span class="text-lg sm:text-xl font-serif font-bold text-white leading-none block">2014</span>
                        <span class="text-[9px] uppercase tracking-wider text-slate-400 font-medium">Tahun Berdiri</span>
                    </div>
                </div>
            </div>

            <div class="lg:pl-6">
                <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase mb-2 block">Perjalanan</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif text-charcoal-950 mb-4 leading-tight font-bold">Berawal dari Dermaga Kecil di Pesisir Utara</h2>
                <p class="text-slate-600 mb-3 leading-relaxed font-normal text-xs sm:text-sm">
                    Perjalanan Niskala Samudra dimulai lebih dari satu dekade lalu. Berawal dari sebuah kedai kecil yang terinspirasi oleh hasil tangkapan nelayan lokal yang melimpah, kami bermimpi menyajikan hidangan laut segar dengan standar restoran bintang lima namun tetap mempertahankan kehangatan rempah Nusantara.
                </p>
                <p class="text-slate-600 mb-6 leading-relaxed font-normal text-xs sm:text-sm">
                    Kata <strong>"Niskala"</strong> bermakna abstrak atau tak berwujud, melambangkan kelezatan rasa yang tak bisa sekadar dijelaskan dengan kata-kata, melainkan harus dirasakan. Sementara <strong>"Samudra"</strong> mewakili kekayaan laut yang menjadi nyawa dari setiap hidangan kami.
                </p>

                <div class="bg-slate-50 border-l-3 border-gold-500 p-4 sm:p-5 rounded-r-xl italic text-slate-700 text-xs sm:text-sm font-serif leading-relaxed">
                    "Kami tidak hanya memasak makanan. Kami mengkurasi kesegaran laut, meracik bumbu warisan, dan menghidangkannya sebagai sebuah mahakarya."
                    <span class="block mt-2.5 font-sans font-semibold text-charcoal-900 not-italic text-[11px] tracking-wide">— Chef Budi Santoso, Executive Chef</span>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="bg-charcoal-950 py-14 sm:py-20 border-t border-charcoal-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 sm:mb-14">
            <span class="text-gold-400 font-semibold tracking-wider text-xs uppercase mb-1.5 block">Pilar Kami</span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif text-white font-bold">Mengapa Niskala Samudra?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 text-center text-white">

            <div class="p-6 bg-charcoal-900/50 rounded-2xl border border-charcoal-800/80">
                <div class="w-12 h-12 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-xl flex items-center justify-center mb-4 text-gold-400 shadow-2xs">
                    <i class="fa-solid fa-fish-fins text-lg"></i>
                </div>
                <h3 class="font-serif text-lg font-bold mb-2">Tangkapan Hari Ini</h3>
                <p class="text-slate-400 font-normal text-xs sm:text-sm leading-relaxed">
                    Kami tidak menggunakan seafood beku. Setiap pagi, tim kami menyeleksi langsung hasil tangkapan terbaik dari pelelangan nelayan lokal untuk menjamin tekstur dan rasa manis alami.
                </p>
            </div>

            <div class="p-6 bg-charcoal-900/50 rounded-2xl border border-charcoal-800/80">
                <div class="w-12 h-12 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-xl flex items-center justify-center mb-4 text-gold-400 shadow-2xs">
                    <i class="fa-solid fa-mortar-pestle text-lg"></i>
                </div>
                <h3 class="font-serif text-lg font-bold mb-2">Rempah Autentik</h3>
                <p class="text-slate-400 font-normal text-xs sm:text-sm leading-relaxed">
                    Bumbu dasar kami digiling manual menggunakan rempah segar Nusantara. Tanpa pengawet, tanpa penguat rasa buatan. Murni dari kekayaan bumi Indonesia.
                </p>
            </div>

            <div class="p-6 bg-charcoal-900/50 rounded-2xl border border-charcoal-800/80">
                <div class="w-12 h-12 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-xl flex items-center justify-center mb-4 text-gold-400 shadow-2xs">
                    <i class="fa-solid fa-wine-glass text-lg"></i>
                </div>
                <h3 class="font-serif text-lg font-bold mb-2">Suasana Elegan</h3>
                <p class="text-slate-400 font-normal text-xs sm:text-sm leading-relaxed">
                    Arsitektur modern dengan sentuhan tropis pesisir. Kami menawarkan area bersantap keluarga, ruang VIP privat, hingga area romantis untuk melengkapi momen spesial Anda.
                </p>
            </div>

        </div>
    </div>
</section>

<section class="bg-slate-50/70 py-14 sm:py-20 border-t border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 sm:mb-10 gap-3 sm:gap-6">
            <div>
                <span class="text-gold-600 font-semibold tracking-wider text-xs uppercase mb-1.5 block">Galeri Restoran</span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif font-bold text-charcoal-950">Suasana Niskala Samudra</h2>
            </div>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md font-normal leading-relaxed">
                Ruang yang didesain khusus untuk memberikan kenyamanan maksimal, baik untuk pertemuan bisnis, makan malam keluarga, maupun kencan romantis.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
            <div class="relative group overflow-hidden rounded-2xl h-[220px] sm:h-[280px]">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Area Utama">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-200 flex items-end p-5">
                    <h3 class="text-white font-medium text-sm sm:text-base">Main Dining Area</h3>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-[220px] sm:h-[280px]">
                <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=800" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Ruang VIP">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-200 flex items-end p-5">
                    <h3 class="text-white font-medium text-sm sm:text-base">VIP Private Room</h3>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-[220px] sm:h-[280px] sm:col-span-2 lg:col-span-1">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800" class="w-full h-full object-cover transition duration-500 group-hover:scale-105" alt="Sajian Mewah">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-200 flex items-end p-5">
                    <h3 class="text-white font-medium text-sm sm:text-base">Premium Plating</h3>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="bg-charcoal-950 py-12 sm:py-18 relative overflow-hidden border-t border-charcoal-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-serif text-white mb-3 font-bold leading-tight">Siap Untuk Merasakan Sensasi <br class="hidden sm:inline"> Hidangan Kami?</h2>
        <p class="text-slate-400 font-normal mb-6 sm:mb-8 text-xs sm:text-sm max-w-xl mx-auto leading-relaxed">
            Jelajahi berbagai pilihan menu seafood favorit yang disiapkan khusus untuk memanjakan lidah Anda. Jangan ragu untuk melakukan reservasi jika Anda berencana datang bersama rombongan.
        </p>
        <div class="flex flex-row justify-center gap-3 sm:gap-4">
            <a href="{{ route('menu') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-6 sm:px-8 py-2.5 sm:py-3 bg-gold-600 text-white font-semibold rounded-xl hover:bg-gold-500 transition tracking-wide text-xs sm:text-sm shadow-sm whitespace-nowrap">
                Lihat Menu
            </a>
            <a href="{{ route('kontak') }}" class="flex-1 sm:flex-initial inline-flex items-center justify-center px-6 sm:px-8 py-2.5 sm:py-3 bg-white/10 border border-white/20 text-white font-semibold rounded-xl hover:bg-white/20 transition tracking-wide text-xs sm:text-sm whitespace-nowrap">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

@endsection
