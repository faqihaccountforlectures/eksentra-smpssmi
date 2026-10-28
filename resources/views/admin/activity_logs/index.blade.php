@extends('layouts.app')

@section('title', 'Log Aktivitas (Audit Trail)')
@section('header', 'Log Aktivitas & Keamanan')

@section('content')

    <!-- KARTU UTAMA AUDIT TRAIL -->
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col hover:border-blue-300 transition-colors duration-200">
        
        <!-- Header Tabel & Filter Pencarian -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col gap-4 bg-white-off/30">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100/70 p-2.5 rounded-xl text-blue-900 border border-blue-200/60 shadow-sm shrink-0">
                        <svg class="w-5 h-5 text-blue-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Riwayat Audit Trail & Keamanan Sistem</h2>
                        <p class="text-xs text-gray-muted mt-0.5">Merekam aksi sensitif admin dan pembina (verifikasi kas, nilai ekskul, reset kata sandi, penghapusan akun) secara otomatis.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold font-mono">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>REAL-TIME AUDIT LOG</span>
                    </span>
                </div>
            </div>

            <!-- Form Pencarian dan Filter -->
            <form action="{{ route('activity-logs.index') }}" method="GET" class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                <!-- Search Input -->
                <div class="sm:col-span-5">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Cari Keterangan / IP / Username</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Ketik kata kunci..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl pl-9 pr-3.5 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white outline-none transition-all">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Action Dropdown Filter -->
                <div class="sm:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tipe Aksi</label>
                    <select name="action" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white outline-none transition-all font-medium">
                        <option value="">-- Semua Tipe Aksi --</option>
                        @foreach($actions as $act)
                            <option value="{{ $act }}" {{ $actionFilter === $act ? 'selected' : '' }}>{{ $act }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ $dateFilter }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 focus:bg-white outline-none transition-all font-medium">
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="sm:col-span-2 flex items-center gap-1.5">
                    <button type="submit" class="flex-1 px-3 py-2 bg-blue-800 hover:bg-blue-900 text-white font-bold text-xs rounded-xl transition-all shadow-xs flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        <span>Filter</span>
                    </button>
                    @if($search || $actionFilter || $dateFilter)
                        <a href="{{ route('activity-logs.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-colors" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Log Aktivitas -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5">Waktu Kejadian</th>
                        <th class="px-6 py-3.5">Pelaku (Akun)</th>
                        <th class="px-6 py-3.5">Tipe Aksi</th>
                        <th class="px-6 py-3.5">Rincian Deskripsi</th>
                        <th class="px-6 py-3.5 text-right">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <!-- Waktu Kejadian -->
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="font-bold text-slate-900 block font-mono">{{ $log->created_at->format('d M Y, H:i') }} WIB</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}</span>
                            </td>

                            <!-- Pelaku Akun & Peran -->
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-900 font-bold flex items-center justify-center font-heading text-[11px] shrink-0">
                                        {{ $log->user ? strtoupper(substr($log->user->username, 0, 1)) : '?' }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-900 block leading-tight">
                                            {{ $log->user->display_name ?? $log->user->username ?? 'Sistem / Anonim' }}
                                        </span>
                                        <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider
                                            @if($log->role === 'admin') bg-rose-50 text-rose-700 border border-rose-200/60
                                            @elseif($log->role === 'coach') bg-indigo-50 text-indigo-700 border border-indigo-200/60
                                            @else bg-blue-50 text-blue-700 border border-blue-200/60 @endif">
                                            {{ $log->role ?? 'guest' }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Tipe Aksi -->
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                @php
                                    $actionClass = match(true) {
                                        str_contains($log->action, 'VERIFIED') || str_contains($log->action, 'APPROVED') => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        str_contains($log->action, 'REJECTED') || str_contains($log->action, 'DELETED') => 'bg-rose-50 text-rose-800 border-rose-200',
                                        str_contains($log->action, 'PASSWORD') => 'bg-amber-50 text-amber-900 border-amber-200',
                                        str_contains($log->action, 'GRADE') => 'bg-sky-50 text-sky-800 border-sky-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold border {{ $actionClass }}">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <!-- Deskripsi Aksi -->
                            <td class="px-6 py-3.5">
                                <p class="text-xs text-slate-800 font-medium leading-relaxed max-w-lg">
                                    {{ $log->description }}
                                </p>
                            </td>

                            <!-- Alamat IP -->
                            <td class="px-6 py-3.5 whitespace-nowrap text-right">
                                <span class="inline-flex items-center gap-1 font-mono text-[11px] text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    {{ $log->ip_address ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-xs font-semibold text-slate-500">Belum ada riwayat aktivitas yang tercatat sesuai filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Bar -->
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif

    </div>

@endsection
