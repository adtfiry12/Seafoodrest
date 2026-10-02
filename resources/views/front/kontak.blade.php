@extends('template.front')

@section('content')

<section class="relative overflow-hidden bg-charcoal-950 py-10 sm:py-16 border-b border-charcoal-800">
    <div class="absolute inset-0">
        <img src="{{ asset('image/contactmenu.webp') }}" alt="Suasana restoran Niskala Samudra" class="h-full w-full object-cover opacity-50">
        <div class="absolute inset-0 bg-charcoal-950/60"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="mb-2 text-xs font-semibold tracking-wider uppercase text-gold-400">Reservasi dan Bantuan</p>
            <h1 class="font-serif text-2xl sm:text-4xl lg:text-5xl leading-tight font-bold text-white">Mari Terhubung dengan Kami</h1>
            <p class="mt-2.5 sm:mt-4 max-w-xl text-xs sm:text-sm leading-relaxed text-slate-300">Tanyakan menu, reservasi meja, acara keluarga, atau kebutuhan katering. Tim kami siap membantu.</p>
        </div>
    </div>
</section>

<section class="bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-[0.75fr_1.25fr] lg:gap-12">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-gold-600">Temukan Kami</span>
                <h2 class="mt-1 font-serif text-xl sm:text-3xl leading-tight font-bold text-charcoal-950">Semua Informasi yang Anda Perlukan</h2>
                <p class="mt-2.5 text-xs sm:text-sm leading-relaxed text-slate-600">Datang dan nikmati hidangan seafood segar di kawasan Pantai Kartini, Jepara.</p>

                <div class="mt-6 divide-y divide-slate-100 border-y border-slate-100">
                    <a href="https://www.google.com/maps/search/?api=1&query=Pantai+Kartini+Jepara" target="_blank" rel="noopener" class="flex items-start gap-3.5 py-4 transition hover:text-gold-600">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600 border border-gold-200/60 text-xs"><i class="fa-solid fa-location-dot"></i></span>
                        <span><strong class="block text-xs sm:text-sm font-semibold text-charcoal-900">Alamat Restoran</strong><span class="mt-0.5 block text-xs leading-relaxed text-slate-500">Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah 59417</span></span>
                    </a>
                    <a href="tel:+6285191194059" class="flex items-start gap-3.5 py-4 transition hover:text-gold-600">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600 border border-gold-200/60 text-xs"><i class="fa-solid fa-phone"></i></span>
                        <span><strong class="block text-xs sm:text-sm font-semibold text-charcoal-900">Telepon dan WhatsApp</strong><span class="mt-0.5 block text-xs text-slate-500">+62 851-9119-4059</span></span>
                    </a>
                    <div class="flex items-start gap-3.5 py-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600 border border-gold-200/60 text-xs"><i class="fa-solid fa-clock"></i></span>
                        <span><strong class="block text-xs sm:text-sm font-semibold text-charcoal-900">Jam Operasional</strong><span class="mt-0.5 block text-xs leading-relaxed text-slate-500">Senin - Jumat 10.00 - 22.00<br>Sabtu - Minggu 09.00 - 23.00</span></span>
                    </div>
                    <a href="mailto:niskalasamudra@gmail.com" class="flex items-start gap-3.5 py-4 transition hover:text-gold-600">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-gold-50 text-gold-600 border border-gold-200/60 text-xs"><i class="fa-solid fa-envelope"></i></span>
                        <span><strong class="block text-xs sm:text-sm font-semibold text-charcoal-900">Email</strong><span class="mt-0.5 block text-xs text-slate-500">niskalasamudra@gmail.com</span></span>
                    </a>
                </div>

                <a href="https://wa.me/6285191194059" target="_blank" rel="noopener" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-charcoal-950 px-5 py-3 text-xs sm:text-sm font-semibold tracking-wider uppercase text-white shadow-sm transition hover:bg-gold-600 sm:w-auto"><i class="fa-brands fa-whatsapp text-base text-gold-400"></i> Chat WhatsApp</a>
            </div>

            <div class="h-80 overflow-hidden rounded-2xl border border-slate-200/80 shadow-sm sm:h-110 lg:h-auto">
                <iframe src="https://www.google.com/maps?q=Pantai+Kartini+Jepara&output=embed" class="block h-full w-full" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Niskala Samudra di Pantai Kartini Jepara"></iframe>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50/70 py-12 sm:py-16 border-t border-slate-200/70">
    <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8 text-center">
            <span class="text-xs font-semibold uppercase tracking-wider text-gold-600">Pesan Langsung</span>
            <h2 class="mt-1 font-serif text-xl sm:text-2xl font-bold text-charcoal-950">Ada yang Ingin Ditanyakan?</h2>
            <p class="mx-auto mt-1.5 max-w-lg text-xs sm:text-sm leading-relaxed text-slate-500">Isi formulir singkat ini dan kami akan meneruskannya ke WhatsApp restoran.</p>
        </div>

        <form x-data @submit.prevent="window.open('https://wa.me/6285191194059?text=' + encodeURIComponent('Nama: ' + $refs.nama.value + '\nEmail: ' + $refs.email.value + '\nWhatsApp: ' + $refs.phone.value + '\nKeperluan: ' + $refs.subject.value + '\nPesan: ' + $refs.message.value), '_blank')" class="rounded-2xl border border-slate-200/80 bg-white p-5 sm:p-7 shadow-[0_1px_3px_rgba(0,0,0,0.04)]">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div><label for="contact-name" class="mb-1.5 block text-xs font-semibold text-slate-600">Nama Lengkap</label><input id="contact-name" x-ref="nama" type="text" required placeholder="Nama Anda" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500"></div>
                <div><label for="contact-phone" class="mb-1.5 block text-xs font-semibold text-slate-600">No. WhatsApp</label><input id="contact-phone" x-ref="phone" type="text" required placeholder="0812-xxxx-xxxx" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500"></div>
                <div><label for="contact-email" class="mb-1.5 block text-xs font-semibold text-slate-600">Email</label><input id="contact-email" x-ref="email" type="email" required placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500"></div>
                <div><label for="contact-subject" class="mb-1.5 block text-xs font-semibold text-slate-600">Keperluan</label><select id="contact-subject" x-ref="subject" required class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500"><option value="">Pilih keperluan</option><option value="Reservasi meja">Reservasi meja</option><option value="Paket acara">Paket acara</option><option value="Kritik dan saran">Kritik dan saran</option><option value="Lainnya">Lainnya</option></select></div>
                <div class="sm:col-span-2"><label for="contact-message" class="mb-1.5 block text-xs font-semibold text-slate-600">Pesan</label><textarea id="contact-message" x-ref="message" rows="4" required placeholder="Tulis pertanyaan atau kebutuhan Anda..." class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:bg-white focus:ring-1 focus:ring-gold-500"></textarea></div>
            </div>
            <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gold-600 px-5 py-3 text-xs sm:text-sm font-semibold tracking-wide uppercase text-white shadow-sm transition hover:bg-gold-500">Kirim ke WhatsApp <i class="fa-brands fa-whatsapp text-base"></i></button>
        </form>
    </div>
</section>

@endsection
