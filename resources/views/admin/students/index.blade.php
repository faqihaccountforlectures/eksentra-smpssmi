@extends('layouts.app')

@section('title', 'Data Siswa')
@section('header', 'Data Siswa')

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
            <p class="text-[11px] font-bold uppercase tracking-wider mb-1 text-rose-700">Rincian Baris yang Perlu Diperbaiki:</p>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-700 max-h-48 overflow-y-auto thin-scrollbar bg-rose-50/80 p-3 rounded-lg border border-rose-200">
                @foreach(session('import_errors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col hover:border-blue-300 transition-colors duration-200">
        <!-- Header Tabel & Form Filter -->
        <div class="px-7 py-5 border-b border-navy-light/30 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white-off/30">
            <div class="flex items-center gap-3">
                <div class="bg-navy-light/10 p-2.5 rounded-xl text-navy-base border border-navy-light/30 shadow-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-navy-dark font-heading tracking-wide text-base">Daftar Peserta Didik</h2>
                    <p class="text-[11px] text-gray-muted mt-0.5 tracking-wide font-bold">Kelola biodata dan akses akun login peserta didik (NIS) di sini.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Form Pencarian & Filter Status -->
                <form action="{{ route('students.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-60">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-navy-base/60">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama / NIS..." class="w-full bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl pl-9 pr-4 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none transition-all placeholder:text-gray-muted/60 shadow-sm">
                    </div>

                    <select name="status" onchange="this.form.submit()" class="w-full sm:w-auto bg-white border border-navy-light/40 text-navy-dark font-semibold text-xs rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-navy-base/20 focus:border-navy-base outline-none shadow-sm cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Siswa Aktif</option>
                        <option value="lulus" {{ request('status') === 'lulus' ? 'selected' : '' }}>Alumni (Lulus)</option>
                        <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </form>

                {{-- FUNGSI KODE: Tombol pemicu modal pop-up untuk mengimpor berkas Excel siswa --}}
                <button type="button" onclick="openStudentImportModal()" class="w-full sm:w-auto px-4 py-2.5 bg-white border border-navy-light/50 text-navy-dark hover:text-navy-base hover:bg-navy-light/10 font-bold text-sm rounded-xl shadow-sm hover:shadow active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Impor Excel
                </button>

                <a href="{{ route('students.create') }}" class="w-full sm:w-auto px-5 py-2.5 bg-navy-dark text-white-off font-bold text-sm rounded-xl hover:bg-navy-base shadow-sm hover:shadow-md hover:shadow-navy-base/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2 border border-transparent whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Siswa
                </a>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white-off/50 text-gray-muted text-[10px] uppercase tracking-widest border-b border-navy-light/30">
                        <th class="px-7 py-4 font-bold w-16 text-center">No</th>
                        <th class="px-7 py-4 font-bold">Profil Siswa</th>
                        <th class="px-7 py-4 font-bold">NIS</th>
                        <th class="px-7 py-4 font-bold">No. Telepon Siswa</th>
                        <th class="px-7 py-4 font-bold">No. Telepon Orang Tua</th>
                        <th class="px-7 py-4 font-bold text-center">Status</th>
                        <th class="px-7 py-4 font-bold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white-off text-navy-base">
                    @forelse($students as $index => $student)
                    <tr class="hover:bg-white-off/50 transition-colors group/row">
                        <td class="px-7 py-4 text-center text-gray-muted font-bold font-mono">
                            {{ $students->firstItem() + $index }}
                        </td>
                        <td class="px-7 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-navy-light/20 text-navy-dark flex items-center justify-center font-bold text-sm border border-navy-light/40 shrink-0">
                                    {{ substr($student->studentProfile->full_name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-navy-dark group-hover/row:text-navy-base transition-colors">{{ $student->studentProfile->full_name ?? 'Belum ada nama' }}</p>
                                    @if($student->studentProfile && $student->studentProfile->gender)
                                        <p class="text-[11px] text-gray-muted font-medium flex items-center gap-1.5 mt-0.5 tracking-wide">
                                            <span class="inline-flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full {{ strtolower($student->studentProfile->gender) === 'laki-laki' ? 'bg-blue-500' : 'bg-pink-500' }}"></span>
                                                <span class="capitalize">{{ $student->studentProfile->gender }}</span>
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if($student->studentProfile && $student->studentProfile->nis)
                                {{ $student->studentProfile->nis }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if($student->studentProfile && $student->studentProfile->phone_number)
                                {{ $student->studentProfile->phone_number }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 font-mono font-medium text-sm">
                            @if($student->studentProfile && $student->studentProfile->parent_phone)
                                {{ $student->studentProfile->parent_phone }}
                            @else
                                <span class="text-gray-muted italic text-xs">Belum diisi</span>
                            @endif
                        </td>
                        <td class="px-7 py-4 text-center whitespace-nowrap">
                            @php $st = $student->studentProfile->status ?? 'aktif'; @endphp
                            @if($st === 'lulus')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Alumni (Lulus)
                                </span>
                            @elseif($st === 'keluar')
                                <span class="inline-flex items-center gap-1.5 bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Keluar
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-7 py-4 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                <!-- Tombol Ubah (Edit) -->
                                <a href="{{ route('students.edit', $student->id) }}" class="text-gray-muted hover:text-navy-base transition-colors p-2 rounded-xl hover:bg-navy-light/30 border border-transparent hover:border-navy-light/50 flex items-center justify-center bg-white" title="Ubah Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                <!-- Tombol Reset Password -->
                                <button type="button" onclick="document.getElementById('resetPasswordModal-{{ $student->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-amber-600 transition-colors p-2 rounded-xl hover:bg-amber-50 border border-transparent hover:border-amber-200 flex items-center justify-center bg-white" title="Reset Password Default (siswasmpsmi)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </button>

                                <!-- Tombol Hapus (Delete) -->
                                <button type="button" onclick="document.getElementById('deleteModal-{{ $student->id }}').classList.remove('hidden')" class="text-gray-muted hover:text-rose-500 transition-colors p-2 rounded-xl hover:bg-rose-50 border border-transparent hover:border-rose-100 flex items-center justify-center bg-white" title="Hapus Data">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @include('admin.students.reset-password')
                            @include('admin.students.delete')
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-7 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-navy-light/10 p-4 rounded-2xl mb-4 border border-navy-light/30">
                                    <svg class="w-10 h-10 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-navy-dark font-heading mb-1">Belum Ada Data Siswa</h3>
                                <p class="text-[13px] text-gray-muted mb-5">Belum ada data peserta didik yang cocok dengan pencarian Anda.</p>
                                <a href="{{ route('students.create') }}" class="px-5 py-2.5 bg-white-off text-navy-dark font-bold text-sm rounded-xl hover:bg-navy-light/20 transition-colors shadow-sm border border-navy-light/30">
                                    Tambah Siswa Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Tabel & Paginasi -->
        @if($students->hasPages())
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex flex-col sm:flex-row justify-between items-center gap-4 mt-auto">
            <span class="text-xs text-gray-muted font-medium">
                Menampilkan <b class="text-navy-dark">{{ $students->firstItem() }}</b> - <b class="text-navy-dark">{{ $students->lastItem() }}</b> dari <b class="text-navy-dark">{{ $students->total() }}</b> siswa
            </span>
            <div class="pagination-wrapper">
                {{ $students->appends(request()->all())->links() }}
            </div>
        </div>
        @else
        <div class="px-7 py-4 border-t border-navy-light/30 bg-white-off/30 flex justify-between items-center text-xs text-gray-muted font-medium mt-auto">
            <span>Total Siswa: <b class="text-navy-dark">{{ $students->total() }}</b> data</span>
        </div>
        @endif
    </div>

    {{-- FUNGSI KODE: Modal Pop-up untuk Mengunggah Berkas Excel Data Siswa --}}
    <div id="studentImportModal" class="fixed inset-0 z-[9999] flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-lg w-full overflow-hidden transform transition-all relative">
            <!-- Header Modal -->
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-50 text-blue-800 rounded-xl border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 font-heading text-base">Impor Data Siswa (Excel)</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">Unggah berkas Excel (.xlsx / .xls) untuk menambahkan banyak siswa sekaligus.</p>
                    </div>
                </div>
                <button type="button" onclick="closeStudentImportModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Isi Modal & Formulir -->
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf

                <!-- Panduan & Tombol Unduh Template -->
                <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-blue-900">Gunakan Template Resmi Excel</p>
                        <p class="text-[11px] text-slate-600 mt-0.5">Unduh template .xlsx yang sudah rapi dan siap diisi di Microsoft Excel.</p>
                    </div>
                    <a href="{{ route('students.template') }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-xl shadow-sm flex items-center gap-1.5 shrink-0 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Template Excel
                    </a>
                </div>

                <!-- Input File Excel -->
                <div class="space-y-1.5">
                    <label for="student_excel_file" class="block text-xs font-bold text-slate-700">Pilih Berkas Excel (.xlsx / .xls)</label>
                    <input type="file" id="student_excel_file" name="file" accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required class="w-full text-xs text-slate-700 bg-slate-50 border border-slate-200 rounded-xl file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-800 hover:file:bg-blue-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <p class="text-[10px] text-slate-500">Ukuran berkas maksimal 10 MB. Kolom Nama Lengkap, NIS, dan Jenis Kelamin wajib diisi. Kolom <b>Kelas (contoh: 7A)</b> opsional.</p>
                </div>

                <!-- Petunjuk Format Excel -->
                <div class="bg-blue-50/50 border border-blue-200/80 rounded-xl p-3 flex items-start gap-2.5 text-blue-900">
                    <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-[11px] leading-relaxed">
                        <b>Informasi:</b> Akun sistem siswa otomatis dibuat dengan format <b>NIS@siswa.smpsmi.com</b> dan kata sandi default <b>siswasmpsmi</b>. Siswa login ke portal menggunakan NIS dan kata sandi tersebut.
                    </p>
                </div>

                <!-- Tombol Aksi Modal -->
                <div class="pt-3 border-t border-slate-100 flex justify-end gap-2.5">
                    <button type="button" onclick="closeStudentImportModal()" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-800 hover:bg-blue-900 text-white text-xs font-bold rounded-xl shadow-sm active:scale-95 transition-all">
                        Mulai Impor Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- FUNGSI KODE: Skrip kendali interaksi buka/tutup modal impor data siswa --}}
    <script>
        function openStudentImportModal() {
            const modal = document.getElementById('studentImportModal');
            modal.classList.remove('hidden');
        }

        function closeStudentImportModal() {
            const modal = document.getElementById('studentImportModal');
            modal.classList.add('hidden');
        }

        // Menutup modal jika pengguna menekan tombol Escape (ESC) pada keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeStudentImportModal();
            }
        });
    </script>
@endsection
