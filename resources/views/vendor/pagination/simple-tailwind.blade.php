@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex justify-between items-center w-full">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-500 bg-[#0d1527]/50 border border-cyan-500/10 cursor-not-allowed rounded-lg">
                &laquo; Sebelumnya
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-cyan-300 bg-[#0d1527] border border-cyan-500/30 rounded-lg hover:bg-cyan-500/15 hover:border-cyan-400 hover:shadow-[0_0_12px_rgba(6,182,212,0.3)] transition-all">
                &laquo; Sebelumnya
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-cyan-300 bg-[#0d1527] border border-cyan-500/30 rounded-lg hover:bg-cyan-500/15 hover:border-cyan-400 hover:shadow-[0_0_12px_rgba(6,182,212,0.3)] transition-all">
                Berikutnya &raquo;
            </a>
        @else
            <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-slate-500 bg-[#0d1527]/50 border border-cyan-500/10 cursor-not-allowed rounded-lg">
                Berikutnya &raquo;
            </span>
        @endif
    </nav>
@endif
