@extends('layouts.app')

@section('title', 'Migrasi Kenaikan Kelas')
@section('header', 'Migrasi Kenaikan Kelas')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('class-enrollments.show', $destinationClass->id) }}" class="inline-flex items-center gap-2 text-gray-muted hover:text-navy-base transition-colors font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span class="text-sm">Kembali ke Detail Kelas Tujuan</span>
        </a>
    </div>

    <!-- Informasi Kelas Tujuan -->
    <div class="bg-navy-light/10 border border-navy-light/30 rounded-2xl p-5 mb-6 flex items-start gap-4 shadow-sm">
        <div class="bg-white-off/50 p-2.5 rounded-xl text-navy-base border border-navy-light/20 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
            <h3 class="text-navy-dark font-bold text-lg mb-1 font-heading">Kelas Tujuan: {{ $destinationClass->name }}</h3>
            <p class="text-gray-muted text-sm leading-relaxed">Anda akan menarik (memigrasikan) siswa-siswa dari kelas lama untuk dimasukkan secara otomatis ke dalam <strong class="text-navy-base">Kelas {{ $destinationClass->name }} (Tingkat {{ $destinationClass->grade_level }})</strong> pada Tahun Ajaran <strong class="text-navy-base">{{ $activeYear->year_name }}</strong>.</p>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="block sm:inline font-bold">{{ session('error') }}</span>
    </div>
    @endif

    <!-- Formulir Filter Pencarian Kelas Asal -->
    {{-- FUNGSI KODE: Form Pencarian Kelas Asal --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-8 hover:border-blue-300 transition-colors duration-200">
        <div class="px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-900 font-heading tracking-wide">1. Cari Kelas Asal (Tahun Sebelumnya)</h3>
        </div>
        <div class="p-7">
            <form action="{{ route('class-enrollments.promote', $destinationClass->id) }}" method="GET" class="flex flex-col md:flex-row gap-5 items-end">
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
                    <label class="block text-sm font-bold text-slate-700 mb-2 tracking-wide">Kelas Asal</label>
                    <select name="source_class_id" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-700/20 focus:border-blue-700 bg-slate-50 font-medium text-slate-800 transition-colors" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($allClasses as $cls)
                            <option value="{{ $cls->id }}" {{ $sourceClassId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} (Tingkat {{ $cls->grade_level }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
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
            <div class="px-7 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-900 font-heading tracking-wide">2. Pilih Siswa yang Naik Kelas</h3>
                <span class="bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold px-3 py-1.5 rounded-lg tracking-wider">{{ count($students) }} Siswa Ditemukan</span>
            </div>

            @if(count($students) > 0)
                <form action="{{ route('class-enrollments.store-promotion', $destinationClass->id) }}" method="POST">
                    @csrf
                    <div class="p-5 bg-amber-50 border-b border-amber-100 text-sm text-amber-900 flex gap-3 items-start">
                        <div class="mt-0.5">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="leading-relaxed">
                            <strong>Petunjuk:</strong> Secara bawaan, seluruh siswa akan tercentang (dinaikkan). Jika ada siswa yang <strong class="text-amber-950 font-bold">tinggal kelas</strong>, silakan hilangkan centangnya. Siswa yang tidak dicentang tidak akan dipindahkan dan dapat dimasukkan kembali ke kelas lamanya secara manual melalui menu Tambah Siswa.
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase tracking-wider font-bold border-b border-slate-100">
                                    <th class="px-7 py-3.5 w-16 text-center">
                                        <input type="checkbox" id="selectAll" checked class="w-4 h-4 text-blue-800 bg-white border-slate-300 rounded focus:border-blue-700 focus:ring focus:ring-blue-700/20 cursor-pointer">
                                    </th>
                                    <th class="px-7 py-3.5">NIS</th>
                                    <th class="px-7 py-3.5">Nama Siswa</th>
                                    <th class="px-7 py-3.5">Jenis Kelamin</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-slate-100 text-slate-800">
                                @foreach($students as $student)
                                <tr class="hover:bg-blue-50/40 transition-colors cursor-pointer" onclick="document.getElementById('checkbox-{{ $student->id }}').click()">
                                    <td class="px-7 py-4 text-center">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="checkbox-{{ $student->id }}" checked onclick="event.stopPropagation()" class="student-checkbox w-4 h-4 text-blue-800 bg-white border-slate-300 rounded focus:border-blue-700 focus:ring focus:ring-blue-700/20 cursor-pointer">
                                    </td>
                                    <td class="px-7 py-4">
                                        <span class="font-mono text-xs font-bold text-blue-800 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">{{ $student->studentProfile->nis ?? '-' }}</span>
                                    </td>
                                    <td class="px-7 py-4">
                                        <div class="font-bold text-slate-900 mb-0.5">{{ $student->studentProfile->full_name ?? $student->email }}</div>
                                        <div class="text-[11px] text-slate-500 flex items-center gap-1.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ $student->email }}
                                        </div>
                                    </td>
                                    <td class="px-7 py-4 font-medium">
                                        @if(isset($student->studentProfile->gender))
                                            @if($student->studentProfile->gender === 'laki-laki')
                                                <span class="inline-flex items-center gap-1.5 text-slate-700 text-[11px] font-bold tracking-wider uppercase"><div class="w-2 h-2 rounded-full bg-blue-600"></div> Laki-laki</span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 text-slate-700 text-[11px] font-bold tracking-wider uppercase"><div class="w-2 h-2 rounded-full bg-pink-500"></div> Perempuan</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-end gap-3 rounded-b-2xl">
                        <a href="{{ route('class-enrollments.show', $destinationClass->id) }}" class="px-6 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                        <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white px-8 py-2.5 rounded-xl font-bold text-xs transition-colors duration-200 flex items-center justify-center gap-2 shadow-sm active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Migrasikan Siswa Terpilih</span>
                        </button>
                    </div>
                </form>
            @else
                <div class="p-12 text-center text-slate-500 flex flex-col items-center">
                    <div class="bg-slate-100 border border-slate-200 p-4 rounded-full mb-4 text-slate-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-800 mb-1 font-heading">Tidak Ada Siswa Tersedia</h4>
                    <p class="max-w-md text-xs leading-relaxed text-slate-500">Semua siswa di kelas tersebut mungkin sudah lulus, belum terdaftar, atau sudah dimasukkan ke kelas lain pada tahun ajaran ini.</p>
                </div>
            @endif
        </div>
        
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
