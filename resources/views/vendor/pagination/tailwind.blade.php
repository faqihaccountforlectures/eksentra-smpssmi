@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 w-full py-2">
        
        <!-- Keterangan Jumlah Data (Bahasa Indonesia) -->
        <div class="text-xs text-slate-400 font-medium tracking-wide order-2 sm:order-1">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-bold text-cyan-300 font-mono">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-bold text-cyan-300 font-mono">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-bold text-cyan-300 font-mono">{{ $paginator->count() }}</span>
            @endif
            dari
            <span class="font-bold text-white font-mono">{{ $paginator->total() }}</span>
            data
        </div>

        <!-- Tombol Angka & Navigasi Halaman -->
        <div class="inline-flex items-center gap-1 order-1 sm:order-2">
            
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-500 bg-[#070b14]/60 border border-slate-800/80 cursor-not-allowed select-none opacity-50">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-[#070b14] border border-cyan-500/25 hover:text-cyan-300 hover:border-cyan-400/50 hover:bg-cyan-500/10 hover:shadow-[0_0_12px_rgba(6,182,212,0.2)] active:scale-95 transition-all" aria-label="Halaman Sebelumnya">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </a>
            @endif

            {{-- Elemen Angka Halaman --}}
            @foreach ($elements as $element)
                {{-- Separator Tiga Titik (...) --}}
                @if (is_string($element))
                    <span class="px-2 py-1 text-xs text-slate-500 select-none">{{ $element }}</span>
                @endif

                {{-- Array Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-xs font-bold bg-cyan-400 text-slate-950 border border-cyan-300 shadow-[0_0_12px_rgba(34,211,238,0.45)] select-none" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-xs font-semibold text-slate-300 bg-[#070b14] border border-cyan-500/25 hover:text-cyan-300 hover:border-cyan-400/50 hover:bg-cyan-500/10 hover:shadow-[0_0_10px_rgba(6,182,212,0.2)] active:scale-95 transition-all" aria-label="Menuju ke halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-300 bg-[#070b14] border border-cyan-500/25 hover:text-cyan-300 hover:border-cyan-400/50 hover:bg-cyan-500/10 hover:shadow-[0_0_12px_rgba(6,182,212,0.2)] active:scale-95 transition-all" aria-label="Halaman Berikutnya">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-500 bg-[#070b14]/60 border border-slate-800/80 cursor-not-allowed select-none opacity-50">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif

        </div>
    </nav>
@elseif ($paginator->total() > 0)
    <div class="flex flex-col sm:flex-row items-center justify-between gap-2 py-2 text-xs text-slate-400 font-medium tracking-wide">
        <span>
            Menampilkan
            <span class="font-bold text-cyan-300 font-mono">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="font-bold text-cyan-300 font-mono">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-bold text-white font-mono">{{ $paginator->total() }}</span>
            data
        </span>
        <span class="text-[10px] font-mono text-cyan-300/80 bg-cyan-950/40 border border-cyan-500/20 px-2 py-0.5 rounded">Halaman 1 dari 1</span>
    </div>
@endif
