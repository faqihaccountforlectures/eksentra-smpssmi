@extends('layouts.app')

@section('title', 'Kelulusan Kelas 9')
@section('header', 'Kelulusan Kelas 9')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.index') }}" class="inline-flex items-center gap-2 text-gray-muted hover:text-navy-base transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-sm">Kembali ke Pembagian Kelas</span>
        </a>
    </div>

    <!-- Informasi Fitur -->
    <div class="bg-yellow-50/50 border border-yellow-200/50 rounded-2xl p-5 mb-6 flex items-start gap-4 shadow-sm">
        <div class="bg-white p-2.5 rounded-xl text-yellow-600 border border-yellow-100 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
        </div>
        <div>
            <h3 class="text-yellow-800 font-bold text-lg mb-1 font-heading">Manajemen Kelulusan (Alumni) & Tinggal Kelas</h3>
            <p class="text-yellow-700 text-sm leading-relaxed">Gunakan fitur ini khusus untuk menangani siswa Kelas 9. Siswa yang diluluskan akan diubah statusnya menjadi <strong>Alumni (Lulus)</strong>. Siswa yang gagal (tinggal kelas) akan dimasukkan kembali ke <strong>Kelas 9 Tujuan</strong> di Tahun Ajaran Aktif ({{ $activeYear->year_name }}).</p>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Formulir Filter Pencarian Kelas Asal -->
    {{-- FUNGSI KODE: Form Filter Kelas 9 Asal --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-8 hover:border-blue-300 transition-colors duration-200">
        <div class="px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-900 font-heading tracking-wide">1. Pilih Kelas 9 Asal (Tahun Sebelumnya)</h3>
        </div>
        <div class="p-7">
            <form action="{{ route('class-enrollments.graduate') }}" method="GET" class="flex flex-col md:flex-row gap-5 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-slate-700 mb-2 tracking-wide">Tahun Ajaran Asal</label>
                    <select name="source_year_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 bg-slate-50 font-medium text-slate-800 transition-colors" required>
                        <option value="">-- Pilih Tahun Ajaran Sebelumnya --</option>
                        @foreach($previousYears as $year)
                            <option value="{{ $year->id }}" {{ $sourceYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->year_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-slate-700 mb-2 tracking-wide">Kelas Asal (Tingkat 9)</label>
                    <select name="source_class_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 bg-slate-50 font-medium text-slate-800 transition-colors" required>
                        <option value="">-- Pilih Kelas 9 Asal --</option>
                        @foreach($sourceClasses as $cls)
                            <option value="{{ $cls->id }}" {{ $sourceClassId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Simpan state Kelas Tujuan agar tidak hilang saat reload -->
                @if($destinationClassId)
                    <input type="hidden" name="destination_class_id" value="{{ $destinationClassId }}">
                @endif
                
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto bg-blue-800 hover:bg-blue-900 text-white px-6 py-2.5 rounded-xl font-bold transition-colors duration-200 flex items-center justify-center gap-2 shadow-sm active:scale-95">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span>Tampilkan Siswa</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tampilan Daftar Siswa (Hanya muncul jika sudah difilter) -->
    @if($sourceYearId && $sourceClassId)
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden flex flex-col hover:border-blue-300 transition-colors duration-200">
            <div class="px-7 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h3 class="font-bold text-slate-900 font-heading tracking-wide">2. Proses Kelulusan Siswa</h3>
                <span class="bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold px-3 py-1.5 rounded-lg tracking-wider">{{ count($students) }} Siswa Aktif Ditemukan</span>
            </div>

            @if(count($students) > 0)
                <form action="{{ route('class-enrollments.store-graduation') }}" method="POST">
                    @csrf
                    
                    <div class="p-7 bg-slate-50/50 border-b border-slate-100">
                        <label class="block text-sm font-bold text-slate-700 mb-2 tracking-wide">Kelas Tujuan (Untuk Siswa Tinggal Kelas)</label>
                        <p class="text-xs text-slate-500 mb-4">Jika ada siswa yang <strong class="text-blue-800">tidak dicentang (tinggal kelas)</strong>, mereka akan dimasukkan ke kelas ini pada tahun ajaran aktif saat ini.</p>
                        <select name="destination_class_id" class="w-full md:w-1/2 border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 bg-white font-medium text-slate-800 transition-colors" required>
                            <option value="">-- Pilih Kelas 9 Tujuan (Tahun Aktif) --</option>
                            @foreach($destinationClasses as $cls)
                                <option value="{{ $cls->id }}" {{ $destinationClassId == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-5 bg-emerald-50 text-sm text-emerald-800 border-b border-emerald-100 flex gap-3 items-start">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="leading-relaxed">
                            <strong>Petunjuk:</strong> Secara bawaan, seluruh siswa akan tercentang (<strong class="text-emerald-700">LULUS</strong>). Jika ada siswa yang <strong class="text-rose-600">TINGGAL KELAS</strong>, silakan hilangkan centangnya.
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-bold border-b border-slate-100">
                                    <th class="px-7 py-3.5 w-16 text-center">
                                        <input type="checkbox" id="selectAll" checked class="w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:border-emerald-600 focus:ring focus:ring-emerald-600/20 cursor-pointer">
                                    </th>
                                    <th class="px-7 py-3.5">NIS</th>
                                    <th class="px-7 py-3.5">Nama Siswa</th>
                                    <th class="px-7 py-3.5">Status Saat Ini</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 text-slate-800">
                                @foreach($students as $student)
                                <tr class="hover:bg-blue-50/40 transition-colors cursor-pointer" onclick="document.getElementById('checkbox-{{ $student->id }}').click()">
                                    <td class="px-7 py-4 text-center">
                                        <!-- Hidden input to pass ALL student IDs to backend for comparison -->
                                        <input type="hidden" name="all_student_ids[]" value="{{ $student->id }}">
                                        
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="checkbox-{{ $student->id }}" checked onclick="event.stopPropagation()" class="student-checkbox w-4 h-4 text-emerald-600 bg-white border-slate-300 rounded focus:border-emerald-600 focus:ring focus:ring-emerald-600/20 cursor-pointer">
                                    </td>
                                    <td class="px-7 py-4">
                                        <span class="font-mono text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $student->studentProfile->nis ?? '-' }}</span>
                                    </td>
                                    <td class="px-7 py-4">
                                        <div class="font-bold text-slate-900 mb-0.5">{{ $student->studentProfile->full_name ?? ($student->studentProfile->nis ? 'NIS ' . $student->studentProfile->nis : 'Siswa') }}</div>
                                        @if($student->studentProfile && $student->studentProfile->phone_number)
                                            <div class="text-[11px] text-slate-500 font-mono">
                                                {{ $student->studentProfile->phone_number }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-7 py-4">
                                        <span class="bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider">{{ $student->studentProfile->status ?? 'aktif' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl">
                        <a href="{{ route('class-enrollments.index') }}" class="px-6 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-2.5 rounded-xl font-bold text-xs transition-colors duration-200 flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Simpan Kelulusan</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-8 text-center text-slate-500 flex flex-col items-center">
                    @if(count($graduatedStudents) > 0)
                        <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl mb-3 text-emerald-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900 mb-1 font-heading">Seluruh Siswa di Kelas Ini Telah Lulus (Alumni)</h4>
                        <p class="max-w-md text-xs leading-relaxed text-slate-500">Semua siswa pada kelas dan tahun ajaran asal yang Anda pilih telah diproses kelulusannya menjadi alumni. Lihat daftar lengkapnya pada tabel di bawah.</p>
                    @else
                        <div class="bg-slate-100 border border-slate-200 p-4 rounded-full mb-4 text-slate-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-800 mb-1 font-heading">Tidak Ada Siswa Ditemukan</h4>
                        <p class="max-w-md text-xs leading-relaxed text-slate-500">Tidak ada riwayat pendaftaran siswa di kelas dan tahun ajaran yang dipilih.</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- TABEL ALUMNI (SISWA YANG SUDAH LULUS) --}}
        @if(count($graduatedStudents) > 0)
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-emerald-200 overflow-hidden flex flex-col hover:border-emerald-300 transition-colors duration-200">
            <div class="px-7 py-5 border-b border-emerald-100 bg-emerald-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 font-heading tracking-wide">Daftar Siswa yang Telah Lulus (Alumni)</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar lulusan dari kelas dan tahun ajaran asal yang dipilih.</p>
                    </div>
                </div>
                <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold px-3 py-1.5 rounded-lg tracking-wider">{{ count($graduatedStudents) }} Alumni Terdata</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-bold border-b border-slate-100">
                            <th class="px-7 py-3.5 w-16 text-center">No</th>
                            <th class="px-7 py-3.5">NIS</th>
                            <th class="px-7 py-3.5">Nama Lengkap Siswa</th>
                            <th class="px-7 py-3.5">Jenis Kelamin</th>
                            <th class="px-7 py-3.5">No. Telepon Ortu</th>
                            <th class="px-7 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100 text-slate-800">
                        @foreach($graduatedStudents as $idx => $student)
                        <tr class="hover:bg-emerald-50/30 transition-colors">
                            <td class="px-7 py-4 text-center font-mono font-bold text-slate-400">
                                {{ $idx + 1 }}
                            </td>
                            <td class="px-7 py-4">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-lg">{{ $student->studentProfile->nis ?? '-' }}</span>
                            </td>
                            <td class="px-7 py-4">
                                <div class="font-bold text-slate-900">{{ $student->studentProfile->full_name ?? ($student->studentProfile->nis ? 'NIS ' . $student->studentProfile->nis : 'Siswa') }}</div>
                                @if($student->studentProfile && $student->studentProfile->phone_number)
                                    <div class="text-[11px] text-slate-500 font-mono mt-0.5">
                                        {{ $student->studentProfile->phone_number }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-7 py-4 capitalize text-xs font-medium text-slate-600">
                                {{ $student->studentProfile->gender ?? '-' }}
                            </td>
                            <td class="px-7 py-4 font-mono text-xs text-slate-600">
                                {{ $student->studentProfile->parent_phone ?? '-' }}
                            </td>
                            <td class="px-7 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Alumni (Lulus)
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <script>
            // Logika untuk mencentang/menghapus centang semua siswa sekaligus
            document.getElementById('selectAll').addEventListener('change', function() {
                let checkboxes = document.querySelectorAll('.student-checkbox');
                for (let checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });
        </script>
    @endif

@endsection
