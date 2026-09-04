@extends('template.front')

@section('content')

<section class="relative h-[300px] sm:h-[400px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('image/contactmenu.webp') }}" alt="Kontak Niskala Samudra" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-charcoal-950/70"></div>
    </div>

    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto">
        <span class="text-gold-500 font-bold uppercase tracking-[0.3em] text-xs sm:text-sm mb-4 block">Reservasi & Bantuan</span>
        <h1 class="text-4xl sm:text-5xl font-serif text-white mb-4 leading-tight">Hubungi Kami</h1>
        <p class="text-slate-300 text-sm sm:text-base font-light leading-relaxed">
            Kami siap menyambut dan melayani Anda. Jangan ragu untuk menghubungi kami terkait reservasi, acara spesial, maupun saran.
        </p>
    </div>
</section>

<section class="bg-slate-50 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16">

            <div class="lg:col-span-7 space-y-10">

                <div>
                    <h2 class="text-2xl sm:text-3xl font-serif text-charcoal-900 mb-6">Informasi Kontak</h2>
                    <p class="text-slate-600 font-light text-sm sm:text-base leading-relaxed mb-8">
                        Berlokasi di kawasan pesisir Jepara yang indah, Niskala Samudra menawarkan pemandangan laut yang menenangkan sambil Anda menikmati hidangan laut terbaik kami.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                            <div class="w-12 h-12 bg-charcoal-950 text-gold-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-charcoal-900 mb-1">Alamat Restoran</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Jl. Pantai Kartini No. 12,<br>Jepara, Jawa Tengah 59417</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                            <div class="w-12 h-12 bg-charcoal-950 text-gold-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-phone-volume"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-charcoal-900 mb-1">Telepon </h4>
                                <p class="text-sm text-slate-500 leading-relaxed">+62 851-9119-4059</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                            <div class="w-12 h-12 bg-charcoal-950 text-gold-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-charcoal-900 mb-1">Jam Operasional</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">Senin - Jumat: 10:00 - 22:00<br>Sabtu - Minggu: 09:00 - 23:00</p>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                            <div class="w-12 h-12 bg-charcoal-950 text-gold-500 rounded-xl flex items-center justify-center text-xl shrink-0">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-charcoal-900 mb-1">Email</h4>
                                <p class="text-sm text-slate-500 leading-relaxed">niskalasamudra@gmailcom</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm h-[300px] sm:h-[400px] relative bg-slate-200">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.368817348981!2d110.65590921477123!3d-6.577953295243169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e711ef85ec01783%3A0xc3f8e56149150ff9!2sPantai%20Kartini%20Jepara!5e0!3m2!1sid!2sid!4v1698305000000!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

            </div>

            <div class="lg:col-span-5">
                <div class="bg-charcoal-950 p-8 sm:p-10 rounded-3xl shadow-2xl relative overflow-hidden h-full flex flex-col justify-center">

                    <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                        <i class="fa-solid fa-envelope-open text-9xl text-white"></i>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-2xl font-serif text-white mb-2">Kirim Pesan</h3>
                        <p class="text-slate-400 text-sm font-light mb-8 leading-relaxed">
                            Punya pertanyaan seputar katering, paket rombongan, atau reservasi tempat VIP? Tulis pesan Anda pada formulir di bawah ini.
                        </p>

                        <form action="#" class="space-y-5">

                            <div>
                                <label class="block text-xs font-bold text-gold-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" placeholder="John Doe" class="w-full bg-charcoal-900 border border-charcoal-800 text-white rounded-xl px-4 py-3 sm:py-3.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-colors placeholder-slate-600">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gold-500 uppercase tracking-wider mb-2">Email</label>
                                    <input type="email" placeholder="john@example.com" class="w-full bg-charcoal-900 border border-charcoal-800 text-white rounded-xl px-4 py-3 sm:py-3.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-colors placeholder-slate-600">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gold-500 uppercase tracking-wider mb-2">No. WhatsApp</label>
                                    <input type="text" placeholder="0812-xxxx-xxxx" class="w-full bg-charcoal-900 border border-charcoal-800 text-white rounded-xl px-4 py-3 sm:py-3.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-colors placeholder-slate-600">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gold-500 uppercase tracking-wider mb-2">Keperluan</label>
                                <select class="w-full bg-charcoal-900 border border-charcoal-800 text-slate-300 rounded-xl px-4 py-3 sm:py-3.5 text-sm focus:ring-gold-500 focus:border-gold-500 transition-colors">
                                    <option value="">-- Pilih Keperluan --</option>
                                    <option value="Reservasi Meja">Reservasi Meja</option>
                                    <option value="Paket Rombongan/Acara">Paket Rombongan / Acara</option>
                                    <option value="Kritik & Saran">Kritik & Saran</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gold-500 uppercase tracking-wider mb-2">Pesan Anda</label>
                                <textarea rows="4" placeholder="Tulis rincian pesan atau permintaan khusus Anda..." class="w-full bg-charcoal-900 border border-charcoal-800 text-white rounded-xl px-4 py-3 text-sm focus:ring-gold-500 focus:border-gold-500 transition-colors placeholder-slate-600"></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="button" class="w-full px-8 py-3.5 bg-gold-600 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-gold-500 transition-all shadow-lg shadow-gold-500/20 active:scale-95 flex items-center justify-center gap-3">
                                    Kirim Pesan <i class="fa-solid fa-paper-plane"></i>
                                </button>
                                <p class="text-center text-[11px] text-slate-500 mt-4">
                                    Kami akan merespons pesan Anda maksimal dalam 1x24 jam kerja.
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
