@extends('layouts.app')

@section('title', 'Data Pembina')
@section('header', 'Data Pembina')

@section('content')
    
    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm" role="alert">
        <div class="bg-emerald-100 p-1.5 rounded-lg">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- FUNGSI KODE: Menampilkan notifikasi kesalahan jika proses impor berkas CSV mengalami kendala --}}
    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl relative flex flex-col gap-2 shadow-sm" role="alert">
        <div class="flex items-center gap-3">
            <div class="bg-rose-100 p-1.5 rounded-lg shrink-0">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
        @if(session('import_errors'))
        <div class="mt-2 pl-10">
            <p class="text-[11px] font-bold uppercase tracking-wider mb-1 text-rose-300">Rincian Baris yang Perlu Diperbaiki:</p>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-200 max-h-48 overflow-y-auto thin-scrollbar bg-rose-950/40 p-3 rounded-lg border border-rose-500/30">
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    {{-- FUNGSI KODE: Tabel Manajemen Data Pembina --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col hover:border-blue-300 transition-colors duration-200">
        <!-- Header Tabel & Form Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white-off/30">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Pembina Ekstrakurikuler</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola data profil dan akun para pembina di sini.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Form Pencarian Pembina -->
                <form action="{{ route('coaches.index') }}" method="GET" class="w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / nomor telepon..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                    </div>
                </form>

                {{-- FUNGSI KODE: Tombol pemicu modal pop-up untuk mengimpor berkas Excel data pembina --}}
                <button type="button" onclick="openCoachImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-sm rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Impor Excel
                </button>

                <a href="{{ route('coaches.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 border border-transparent whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pembina
                </a>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-7 py-4 font-bold">Profil Pembina</th>
                        <th class="px-7 py-4 font-bold">Username</th>
                        <th class="px-7 py-4 font-bold">No. Telepon</th>
                        <th class="px-7 py-4 font-bold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($coaches as $index => $coach)
                    @php $profile = $coach->coachProfile; @endphp
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        <td class="px-7 py-4 text-center text-gray-muted font-bold font-mono">
                            {{ $coaches->firstItem() + $index }}
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-navy-light/20 text-navy-dark flex items-center justify-center font-bold text-sm border border-navy-light/40 shrink-0">
                                    {{ substr($profile->full_name ?? ($coach->name ?? 'P'), 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark group-hover/row:text-navy-base transition-colors">{{ $profile->full_name ?? ($coach->name ?? 'Belum ada nama') }}</p>
                                    @if(isset($profile->gender) && $profile->gender)
                                        <p class="text-[11px] text-gray-muted font-medium flex items-center gap-1.5 mt-0.5 tracking-wide">
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full {{ strtolower($profile->gender) === 'laki-laki' ? 'bg-blue-500' : 'bg-pink-500' }}"></span>
                                                <span class="capitalize">{{ $profile->gender }}</span>
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-xs text-navy-dark">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-navy-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>{{ $coach->username }}</span>
                            </span>
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if(isset($profile->phone_number) && $profile->phone_number)
                                {{ $profile->phone_number }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                <!-- Tombol Ubah (Edit) -->
                                <a href="{{ route('coaches.edit', $coach->id) }}" class="text-gray-muted hover:text-navy-base transition-colors p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 flex items-center justify-center bg-white" title="Ubah Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <!-- Tombol Reset Password -->
                                <button type="button" onclick="document.getElementById('resetPasswordModal-{{ $coach->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-amber-600 transition-colors p-2 rounded-xl hover:bg-amber-50 border border-transparent hover:border-amber-200 flex items-center justify-center bg-white" title="Reset Password Default (pembinasmpsmi)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </button>

                                <!-- Tombol Hapus (Delete) -->
                                <button type="button" onclick="document.getElementById('deleteModal-{{ $coach->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-500 transition-colors p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-100 flex items-center justify-center bg-white" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @include('admin.coaches.reset-password', ['coach' => $coach])
                            @include('admin.coaches.delete', ['coach' => $coach])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-muted">
                                <div class="bg-navy-light/10 p-4 rounded-2xl mb-4 border border-navy-light/30 text-navy-base">
                                    <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark mb-1 font-heading">Belum Ada Data Pembina</h3>
                                <p class="text-[13px] text-gray-muted mb-5">Belum ada data pembina ekstrakurikuler yang cocok dengan pencarian Anda.</p>
                                <a href="{{ route('coaches.create') }}" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-sm rounded-xl hover:bg-navy-light/20 transition-colors shadow-sm border border-navy-light/30">
                                    Tambah Pembina Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel & Paginasi -->
        @if($coaches->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $coaches->firstItem() }}</b> - <b class="text-navy-dark">{{ $coaches->lastItem() }}</b> dari <b class="text-navy-dark">{{ $coaches->total() }}</b> pembina
            </span>
            <div class="pagination-wrapper">
                {{ $coaches->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Pembina: <b class="text-navy-dark">{{ $coaches->total() }}</b> data</span>
        </div>
        @endif
    </div>

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas Excel Data Pembina --}}
    <div id="coachImportModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all relative">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-50 text-blue-800 rounded-xl border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 font-heading text-base">Impor Data Pembina (Excel)</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Unggah berkas Excel (.xlsx / .xls) untuk menambahkan banyak pembina sekaligus.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCoachImportModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('coaches.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-blue-900">Gunakan Template Resmi Excel</p>
                        <p class="text-[11px] text-slate-600 mt-0.5">Unduh template .xlsx yang sudah diformat khusus dan siap diedit.</p>
                    </div>
                    <a href="{{ route('coaches.template') }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 shrink-0 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template Excel
                    </a>
                </div>

                <!-- Input File Excel -->
                <div class="space-y-1.5">
                    <label for="coach_excel_file" class="block text-xs font-bold text-slate-700">Pilih Berkas Excel (.xlsx / .xls)</label>
                    <input type="file" id="coach_excel_file" name="file" accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required class="w-full text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <p class="text-[10px] text-slate-500">Ukuran berkas maksimal 10 MB. Kolom Nama Lengkap, Username/Email, dan Jenis Kelamin wajib diisi.</p>
                </div>

                <!-- Informasi Password dan Domain -->
                <div class="bg-blue-50/50 border border-blue-200/80 rounded-xl p-3 flex items-start gap-2.5 text-blue-900">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[11px] leading-relaxed">
                        <b>Informasi:</b> Akun pembina baru otomatis diberikan kata sandi default <b>pembinasmpsmi</b>. Jika pada kolom Username/Email hanya diisi username tanpa tanda '@', sistem otomatis menambahkan domain <b>@smpsmi.com</b>.
                    </p>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2.5">
                    <button type="button" onclick="closeCoachImportModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-800 hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow-sm active:scale-95 transition-all">
                        Mulai Impor Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor data pembina --}}
    <script>
        function openCoachImportModal() {
            const modal = document.getElementById('coachImportModal');
            modal.classList.remove('hidden');
        }

        function closeCoachImportModal() {
            const modal = document.getElementById('coachImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeCoachImportModal();
            }
        });
    </script>
@endsection
