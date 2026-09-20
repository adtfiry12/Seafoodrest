@extends('template.front')

@section('content')
<section class="flex min-h-[65vh] items-center justify-center bg-slate-100 py-20">
    <div class="mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl border border-gold-200 bg-gold-50 text-gold-600 shadow-sm">
            <i class="fa-solid fa-compass text-3xl"></i>
        </div>
        <p class="mt-8 text-sm font-bold uppercase tracking-[0.3em] text-gold-600">Halaman Tidak Ditemukan</p>
        <h1 class="mt-3 font-serif text-5xl font-bold text-charcoal-900 sm:text-7xl">404</h1>
        <p class="mx-auto mt-5 max-w-lg text-sm leading-7 text-slate-500 sm:text-base">
            Maaf, halaman yang Anda cari tidak ditemukan atau sudah dipindahkan.
        </p>
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gold-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-gold-600/20 transition hover:bg-gold-500">
                <i class="fa-solid fa-house"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('menu') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-bold text-charcoal-900 transition hover:border-gold-500 hover:text-gold-600">
                <i class="fa-solid fa-utensils"></i> Lihat Menu
            </a>
        </div>
    </div>
</section>
@endsection
