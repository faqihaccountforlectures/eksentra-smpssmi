@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex justify-between items-center w-full">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed rounded-lg opacity-60">
                &laquo; Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:text-blue-900 hover:border-blue-400 hover:bg-blue-50 transition-all shadow-sm">
                &laquo; Sebelumnya
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:text-blue-900 hover:border-blue-400 hover:bg-blue-50 transition-all shadow-sm">
                Berikutnya &raquo;
            </a>
        @else
            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed rounded-lg opacity-60">
                Berikutnya &raquo;
            </span>
        @endif
    </nav>
@endif
