@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between w-full">
        <div class="flex items-center justify-between w-full gap-2">
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded-xl cursor-not-allowed select-none opacity-60">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-charcoal-900 bg-white border border-slate-200 rounded-xl shadow-2xs hover:bg-gold-50 hover:text-gold-700 transition active:scale-95 select-none">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-charcoal-900 bg-white border border-slate-200 rounded-xl shadow-2xs hover:bg-gold-50 hover:text-gold-700 transition active:scale-95 select-none">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200/80 rounded-xl cursor-not-allowed select-none opacity-60">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
