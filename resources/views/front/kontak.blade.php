@extends('template.front')

@section('content')

<section class="relative overflow-hidden bg-charcoal-950 py-14 sm:py-20">
    <div class="absolute inset-0">
        <img src="{{ asset('image/contactmenu.webp') }}" alt="Suasana restoran Niskala Samudra" class="h-full w-full object-cover opacity-35">
        <div class="absolute inset-0 bg-charcoal-950/75"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.24em] text-gold-500">Reservasi dan bantuan</p>
            <h1 class="font-serif text-4xl leading-tight text-white sm:text-6xl">Mari terhubung dengan kami.</h1>
            <p class="mt-5 max-w-xl text-sm leading-relaxed text-slate-300 sm:text-base">Tanyakan menu, reservasi meja, acara keluarga, atau kebutuhan katering. Tim kami siap membantu.</p>
        </div>
    </div>
</section>

<section class="bg-white py-12 sm:py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-[0.72fr_1.28fr] lg:gap-12 xl:gap-16">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-600">Temukan kami</p>
                <h2 class="mt-3 font-serif text-3xl leading-tight text-charcoal-900 sm:text-4xl">Semua informasi yang Anda perlukan.</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-600 sm:text-base">Datang dan nikmati hidangan seafood segar di kawasan Pantai Kartini, Jepara.</p>

                <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200">
                    <a href="https://www.google.com/maps/search/?api=1&query=Pantai+Kartini+Jepara" target="_blank" rel="noopener" class="flex gap-4 py-5 transition hover:text-gold-600">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-600"><i class="fa-solid fa-location-dot"></i></span>
                        <span><strong class="block text-sm text-charcoal-900">Alamat restoran</strong><span class="mt-1 block text-sm leading-relaxed text-slate-500">Jl. Pantai Kartini No. 12, Jepara, Jawa Tengah 59417</span></span>
                    </a>
                    <a href="tel:+6285191194059" class="flex gap-4 py-5 transition hover:text-gold-600">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-600"><i class="fa-solid fa-phone"></i></span>
                        <span><strong class="block text-sm text-charcoal-900">Telepon dan WhatsApp</strong><span class="mt-1 block text-sm text-slate-500">+62 851-9119-4059</span></span>
                    </a>
                    <div class="flex gap-4 py-5">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-600"><i class="fa-solid fa-clock"></i></span>
                        <span><strong class="block text-sm text-charcoal-900">Jam operasional</strong><span class="mt-1 block text-sm leading-relaxed text-slate-500">Senin - Jumat 10.00 - 22.00<br>Sabtu - Minggu 09.00 - 23.00</span></span>
                    </div>
                    <a href="mailto:niskalasamudra@gmail.com" class="flex gap-4 py-5 transition hover:text-gold-600">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-50 text-gold-600"><i class="fa-solid fa-envelope"></i></span>
                        <span><strong class="block text-sm text-charcoal-900">Email</strong><span class="mt-1 block text-sm text-slate-500">niskalasamudra@gmail.com</span></span>
                    </a>
                </div>

                <a href="https://wa.me/6285191194059" target="_blank" rel="noopener" class="mt-8 inline-flex w-full items-center justify-center gap-3 rounded-xl bg-charcoal-900 px-5 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-gold-600 sm:w-auto"><i class="fa-brands fa-whatsapp text-lg text-gold-400"></i> Chat WhatsApp</a>
            </div>

            <div class="h-96 overflow-hidden rounded-2xl border border-slate-200 shadow-sm sm:h-120 lg:h-160 xl:h-180">
                <iframe src="https://www.google.com/maps?q=Pantai+Kartini+Jepara&output=embed" class="block h-full w-full" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Niskala Samudra di Pantai Kartini Jepara"></iframe>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-12 sm:py-16">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-600">Pesan langsung</p>
            <h2 class="mt-3 font-serif text-3xl text-charcoal-900 sm:text-4xl">Ada yang ingin ditanyakan?</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-500">Isi formulir singkat ini dan kami akan meneruskannya ke WhatsApp restoran.</p>
        </div>

        <form x-data @submit.prevent="window.open('https://wa.me/6285191194059?text=' + encodeURIComponent('Nama: ' + $refs.nama.value + '\nEmail: ' + $refs.email.value + '\nWhatsApp: ' + $refs.phone.value + '\nKeperluan: ' + $refs.subject.value + '\nPesan: ' + $refs.message.value), '_blank')" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div><label for="contact-name" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Nama lengkap</label><input id="contact-name" x-ref="nama" type="text" required placeholder="Nama Anda" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"></div>
                <div><label for="contact-phone" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">No. WhatsApp</label><input id="contact-phone" x-ref="phone" type="text" required placeholder="0812-xxxx-xxxx" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"></div>
                <div><label for="contact-email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Email</label><input id="contact-email" x-ref="email" type="email" required placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"></div>
                <div><label for="contact-subject" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Keperluan</label><select id="contact-subject" x-ref="subject" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"><option value="">Pilih keperluan</option><option value="Reservasi meja">Reservasi meja</option><option value="Paket acara">Paket acara</option><option value="Kritik dan saran">Kritik dan saran</option><option value="Lainnya">Lainnya</option></select></div>
                <div class="sm:col-span-2"><label for="contact-message" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-500">Pesan</label><textarea id="contact-message" x-ref="message" rows="5" required placeholder="Tulis pertanyaan atau kebutuhan Anda..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-charcoal-900 outline-none transition focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20"></textarea></div>
            </div>
            <button type="submit" class="mt-6 inline-flex w-full items-center justify-center gap-3 rounded-xl bg-gold-600 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-gold-600/20 transition hover:bg-gold-500">Kirim ke WhatsApp <i class="fa-brands fa-whatsapp text-lg"></i></button>
        </form>
    </div>
</section>

@endsection
