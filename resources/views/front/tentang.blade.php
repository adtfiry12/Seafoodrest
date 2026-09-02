@extends('template.front')

@section('content')

<!-- ================= HERO SECTION TENTANG KAMI ================= -->
<section class="relative h-[400px] sm:h-[500px] flex items-center justify-center overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=1600" alt="Suasana Restoran Niskala Samudra" class="w-full h-full object-cover object-center">
        <!-- Overlay Gelap -->
        <div class="absolute inset-0 bg-charcoal-950/70"></div>
    </div>

    <!-- Hero Text -->
    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <span class="text-gold-500 font-bold uppercase tracking-[0.3em] text-xs sm:text-sm mb-4 block">Kisah Kami</span>
        <h1 class="text-4xl sm:text-6xl font-serif text-white mb-6 leading-tight">Membawa Kekayaan Samudra ke Atas Meja Anda</h1>
        <p class="text-slate-300 text-sm sm:text-base font-light leading-relaxed">
            Lebih dari sekadar restoran, Niskala Samudra adalah perayaan atas hasil laut perairan Nusantara, diolah dengan hasrat dan dedikasi tinggi.
        </p>
    </div>
</section>

<!-- ================= SEJARAH & VISI ================= -->
<section class="bg-white py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 sm:gap-16 items-center">

            <!-- Kolom Gambar -->
            <div class="relative grid grid-cols-2 gap-4 sm:gap-6">
                <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?q=80&w=600" alt="Koki Memasak" class="rounded-2xl shadow-xl w-full h-[250px] sm:h-[350px] object-cover mt-8 sm:mt-12">
                <img src="https://images.unsplash.com/photo-1599021457199-3543d3b664f3?q=80&w=600" alt="Hidangan Kepiting" class="rounded-2xl shadow-xl w-full h-[250px] sm:h-[350px] object-cover">

                <!-- Badge Mengambang -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white p-4 sm:p-6 rounded-full shadow-2xl flex items-center justify-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 border-2 border-gold-500 rounded-full flex flex-col items-center justify-center text-charcoal-900">
                        <span class="text-xl sm:text-2xl font-serif font-bold leading-none">2014</span>
                        <span class="text-[8px] sm:text-[10px] uppercase tracking-widest font-bold mt-1">Berdiri</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Teks -->
            <div class="lg:pl-8">
                <h2 class="text-3xl sm:text-4xl font-serif text-charcoal-900 mb-6 leading-tight">Berawal dari Dermaga Kecil di Pesisir Utara</h2>
                <p class="text-slate-600 mb-5 leading-relaxed font-light text-sm sm:text-base">
                    Perjalanan Niskala Samudra dimulai lebih dari satu dekade lalu. Berawal dari sebuah kedai kecil yang terinspirasi oleh hasil tangkapan nelayan lokal yang melimpah, kami bermimpi menyajikan hidangan laut segar dengan standar restoran bintang lima namun tetap mempertahankan kehangatan rempah Nusantara.
                </p>
                <p class="text-slate-600 mb-8 leading-relaxed font-light text-sm sm:text-base">
                    Kata <strong>"Niskala"</strong> bermakna abstrak atau tak berwujud, melambangkan kelezatan rasa yang tak bisa sekadar dijelaskan dengan kata-kata, melainkan harus dirasakan. Sementara <strong>"Samudra"</strong> mewakili kekayaan laut yang menjadi nyawa dari setiap hidangan kami.
                </p>

                <div class="bg-slate-50 border-l-4 border-gold-500 p-6 rounded-r-xl italic text-slate-700 text-sm sm:text-base font-serif">
                    "Kami tidak hanya memasak makanan. Kami mengkurasi kesegaran laut, meracik bumbu warisan, dan menghidangkannya sebagai sebuah mahakarya."
                    <span class="block mt-4 font-sans font-bold text-charcoal-900 not-italic text-xs uppercase tracking-wider">— Chef Budi Santoso, Executive Chef</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= FILOSOFI & NILAI (CORE VALUES) ================= -->
<section class="bg-charcoal-950 py-16 sm:py-24 border-t-2 border-gold-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-12 sm:mb-16">
            <span class="text-gold-500 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Pilar Kami</span>
            <h2 class="text-3xl sm:text-4xl font-serif text-white mb-6">Mengapa Niskala Samudra?</h2>
            <div class="w-16 h-1 bg-gold-500 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-12 text-center text-white">

            <!-- Value 1 -->
            <div class="p-6">
                <div class="w-20 h-20 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-full flex items-center justify-center mb-6 text-gold-500 shadow-[0_0_30px_rgba(245,158,11,0.1)]">
                    <i class="fa-solid fa-fish-fins text-3xl"></i>
                </div>
                <h3 class="font-serif text-xl font-bold mb-3">Tangkapan Hari Ini</h3>
                <p class="text-slate-400 font-light text-sm leading-relaxed">
                    Kami tidak menggunakan seafood beku. Setiap pagi, tim kami menyeleksi langsung hasil tangkapan terbaik dari pelelangan nelayan lokal untuk menjamin tekstur dan rasa manis alami.
                </p>
            </div>

            <!-- Value 2 -->
            <div class="p-6">
                <div class="w-20 h-20 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-full flex items-center justify-center mb-6 text-gold-500 shadow-[0_0_30px_rgba(245,158,11,0.1)]">
                    <i class="fa-solid fa-mortar-pestle text-3xl"></i>
                </div>
                <h3 class="font-serif text-xl font-bold mb-3">Rempah Autentik</h3>
                <p class="text-slate-400 font-light text-sm leading-relaxed">
                    Bumbu dasar kami digiling manual menggunakan rempah segar Nusantara. Tanpa pengawet, tanpa penguat rasa buatan. Murni dari kekayaan bumi Indonesia.
                </p>
            </div>

            <!-- Value 3 -->
            <div class="p-6">
                <div class="w-20 h-20 mx-auto bg-charcoal-900 border border-charcoal-800 rounded-full flex items-center justify-center mb-6 text-gold-500 shadow-[0_0_30px_rgba(245,158,11,0.1)]">
                    <i class="fa-solid fa-wine-glass text-3xl"></i>
                </div>
                <h3 class="font-serif text-xl font-bold mb-3">Suasana Elegan</h3>
                <p class="text-slate-400 font-light text-sm leading-relaxed">
                    Arsitektur modern dengan sentuhan tropis pesisir. Kami menawarkan area bersantap keluarga, ruang VIP privat, hingga area romantis untuk melengkapi momen spesial Anda.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ================= AMBIANCE (GALERI SUASANA) ================= -->
<section class="bg-slate-50 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 sm:mb-12 gap-6">
            <div>
                <span class="text-gold-600 font-bold uppercase tracking-widest text-[10px] sm:text-xs mb-2 block">Galeri Restoran</span>
                <h2 class="text-3xl sm:text-4xl font-serif text-charcoal-900">Suasana Niskala Samudra</h2>
            </div>
            <p class="text-sm text-slate-500 max-w-md font-light leading-relaxed">
                Ruang yang didesain khusus untuk memberikan kenyamanan maksimal, baik untuk pertemuan bisnis, makan malam keluarga, maupun kencan romantis.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="relative group overflow-hidden rounded-2xl h-[300px]">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=800" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Area Utama">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <h3 class="text-white font-bold text-lg">Main Dining Area</h3>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-[300px]">
                <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=800" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Ruang VIP">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <h3 class="text-white font-bold text-lg">VIP Private Room</h3>
                </div>
            </div>
            <div class="relative group overflow-hidden rounded-2xl h-[300px] sm:col-span-2 lg:col-span-1">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Sajian Mewah">
                <div class="absolute inset-0 bg-gradient-to-t from-charcoal-950/80 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-6">
                    <h3 class="text-white font-bold text-lg">Premium Plating</h3>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ================= CALL TO ACTION (CTA) ================= -->
<section class="bg-charcoal-900 py-16 sm:py-20 relative overflow-hidden">
    <!-- Dekorasi Ikon Transparan -->
    <i class="fa-solid fa-utensils absolute -right-10 -bottom-10 text-[200px] text-charcoal-800 opacity-50 transform -rotate-12"></i>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-2xl sm:text-4xl font-serif text-white mb-4 leading-tight">Siap Untuk Merasakan Sensasi <br> Hidangan Kami?</h2>
        <p class="text-slate-400 font-light mb-8 text-sm sm:text-base max-w-2xl mx-auto">
            Jelajahi berbagai pilihan menu seafood favorit yang disiapkan khusus untuk memanjakan lidah Anda. Jangan ragu untuk melakukan reservasi jika Anda berencana datang bersama rombongan.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('menu') }}" class="px-8 py-3 bg-gold-600 text-white font-bold rounded-xl hover:bg-gold-500 transition uppercase tracking-wider text-sm shadow-lg shadow-gold-600/20">
                Lihat Daftar Menu
            </a>
            <a href="{{ route('kontak') }}" class="px-8 py-3 bg-transparent border-2 border-slate-500 text-slate-300 font-bold rounded-xl hover:border-gold-500 hover:text-gold-500 transition uppercase tracking-wider text-sm">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

@endsection
