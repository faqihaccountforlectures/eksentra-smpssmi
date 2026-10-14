@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 w-full py-2">
        
        <!-- Keterangan Jumlah Data (Bahasa Indonesia) -->
        <div class="text-xs text-slate-600 font-medium tracking-wide order-2 sm:order-1">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-bold text-blue-900 font-mono">{{ $paginator->firstItem() }}</span>
                sampai
                <span class="font-bold text-blue-900 font-mono">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-bold text-blue-900 font-mono">{{ $paginator->count() }}</span>
            @endif
            dari
            <span class="font-bold text-slate-900 font-mono">{{ $paginator->total() }}</span>
            data
        </div>

        <!-- Tombol Angka & Navigasi Halaman -->
        <div class="inline-flex items-center gap-1 order-1 sm:order-2">
            
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed select-none opacity-60">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:text-blue-900 hover:border-blue-400 hover:bg-blue-50 active:scale-95 transition-all shadow-sm" aria-label="Halaman Sebelumnya">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </a>
            @endif

            {{-- Elemen Angka Halaman --}}
            @foreach ($elements as $element)
                {{-- Separator Tiga Titik (...) --}}
                @if (is_string($element))
                    <span class="px-2 py-1 text-xs text-slate-400 select-none">{{ $element }}</span>
                @endif

                {{-- Array Nomor Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-xs font-bold bg-blue-800 text-white border border-blue-900 shadow-sm select-none" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="min-w-[32px] h-8 px-2 inline-flex items-center justify-center rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:text-blue-900 hover:border-blue-400 hover:bg-blue-50 active:scale-95 transition-all shadow-sm" aria-label="Menuju ke halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-300 hover:text-blue-900 hover:border-blue-400 hover:bg-blue-50 active:scale-95 transition-all shadow-sm" aria-label="Halaman Berikutnya">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 border border-slate-200 cursor-not-allowed select-none opacity-60">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif

        </div>

    </nav>
@endif
