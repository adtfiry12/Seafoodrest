@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between w-full">

        {{-- Mobile View (Compact, Beautiful & Touch-Friendly) --}}
        <div class="flex items-center justify-between w-full sm:hidden gap-2">
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

            {{-- Page Indicator in Center --}}
            <div class="px-2.5 py-1 bg-slate-100/80 border border-slate-200/60 rounded-lg text-slate-600 text-xs font-medium">
                <span class="font-bold text-charcoal-900">{{ $paginator->currentPage() }}</span>
                <span class="text-slate-400 mx-0.5">/</span>
                <span class="font-bold text-charcoal-900">{{ $paginator->lastPage() }}</span>
            </div>

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

        {{-- Desktop & Tablet View --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full gap-4">
            <div>
                <p class="text-xs sm:text-sm text-slate-500">
                    Menampilkan
                    @if ($paginator->firstItem())
                        <span class="font-semibold text-charcoal-900">{{ $paginator->firstItem() }}</span>
                        sampai
                        <span class="font-semibold text-charcoal-900">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    dari
                    <span class="font-semibold text-charcoal-900">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="Sebelumnya" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-300 bg-slate-50 border border-slate-200/70 rounded-xl cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-xl shadow-2xs hover:bg-gold-50 hover:text-gold-700 hover:border-gold-200 transition">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-400">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-gold-400 bg-charcoal-950 border border-charcoal-950 rounded-xl shadow-xs">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-xl shadow-2xs hover:bg-gold-50 hover:text-gold-700 hover:border-gold-200 transition" aria-label="Ke halaman {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-700 bg-white border border-slate-200 rounded-xl shadow-2xs hover:bg-gold-50 hover:text-gold-700 hover:border-gold-200 transition">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="Berikutnya" class="inline-flex items-center justify-center w-9 h-9 text-xs font-medium text-slate-300 bg-slate-50 border border-slate-200/70 rounded-xl cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </div>
        </div>

    </nav>
@endif
