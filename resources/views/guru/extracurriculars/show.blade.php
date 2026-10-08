@extends('layouts.app')

@section('title', 'Detail & Penilaian Ekstrakurikuler')
@section('header', 'Detail & Penilaian Ekstrakurikuler')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Notifikasi Sukses / Error -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm space-y-1">
        <div class="font-bold flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>Terdapat kesalahan penginputan:</span>
        </div>
        <ul class="list-disc list-inside text-xs pl-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header / Navigasi Kembali & Filter Tahun Ajaran -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('guru.extracurriculars.index') }}" class="p-2 bg-white rounded-xl border border-navy-light/30 text-navy-base hover:border-navy-base/50 hover:scale-105 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-navy-dark font-heading">{{ $extracurricular->name }}</h2>
                <p class="text-xs text-gray-muted mt-0.5">Penilaian perkembangan anggota ekstrakurikuler per semester (Ganjil & Genap).</p>
            </div>
        </div>

        <!-- Filter Tahun Ajaran -->
        <form method="GET" action="{{ route('guru.extracurriculars.show', $extracurricular->id) }}" class="flex items-center gap-2 bg-white p-2 rounded-xl border border-navy-light/30 shadow-sm shrink-0">
            <label for="academic_year_id" class="text-xs font-bold text-navy-dark whitespace-nowrap pl-2">Tahun Ajaran:</label>
            <select name="academic_year_id" id="academic_year_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-navy-light/30 text-xs font-bold text-navy-base bg-white-off focus:outline-none focus:ring-2 focus:ring-navy-base">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ ($selectedYear && $selectedYear->id == $year->id) ? 'selected' : '' }}>
                        {{ $year->year_name }} {{ $year->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Info Panel (Kiri) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-navy-light/30 p-6 shadow-sm shadow-navy-base/5 group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <!-- Foto Ekstrakurikuler -->
                <div class="w-full h-44 bg-navy-light/10 rounded-xl overflow-hidden mb-6 flex items-center justify-center border border-navy-light/20 relative">
                    @if($extracurricular->image)
                        <img src="{{ asset('storage/' . $extracurricular->image) }}" alt="{{ $extracurricular->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-navy-light">
                            <svg class="w-16 h-16 text-navy-base/40 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                <h3 class="font-bold text-navy-dark text-lg font-heading mb-4 pb-2 border-b border-navy-light/20">Informasi Kegiatan</h3>
                
                <div class="space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="p-2.5 bg-navy-light/10 rounded-xl text-navy-base border border-navy-light/30 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Jadwal Pelaksanaan</p>
                            <p class="font-bold text-navy-dark text-sm">{{ $extracurricular->schedule }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-200 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-0.5">Total Siswa Terdaftar</p>
                            <p class="font-bold text-navy-dark text-sm">{{ $extracurricular->registrations_count ?? $registrations->total() }} Siswa</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-navy-light/20">
                        <p class="text-[10px] font-bold text-gray-muted uppercase tracking-wider mb-2">Deskripsi Lengkap</p>
                        <p class="text-xs text-navy-dark leading-relaxed font-medium">{{ $extracurricular->description ?: 'Belum ada deskripsi untuk kegiatan ini.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Anggota & Penilaian Semester Ganjil/Genap (Kanan) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-navy-light/30 shadow-sm shadow-navy-base/5 overflow-hidden flex flex-col h-full group hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                <div class="p-6 border-b border-navy-light/20 bg-white-off/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-navy-dark text-base font-heading">
                            Penilaian Anggota — Tahun Ajaran {{ $selectedYear->year_name ?? '-' }}
                        </h3>
                        <p class="text-xs text-gray-muted mt-0.5">Input dan perbarui nilai untuk <strong>Semester Ganjil</strong> & <strong>Semester Genap</strong>.</p>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-white text-gray-muted border-b border-navy-light/20 text-[10px] font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-5 font-semibold">Nama Siswa</th>
                                <th class="py-4 px-3 font-semibold text-center">Status</th>
                                <th class="py-4 px-4 font-semibold text-center">Semester Ganjil</th>
                                <th class="py-4 px-4 font-semibold text-center">Semester Genap</th>
                                <th class="py-4 px-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-navy-light/10">
                            @forelse($registrations as $reg)
                            @php
                                $gradeGanjil = $reg->grades->firstWhere('semester', 'ganjil');
                                $gradeGenap = $reg->grades->firstWhere('semester', 'genap');
                            @endphp
                            <tr class="hover:bg-white-off/80 transition-colors">
                                <!-- Nama Siswa -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-navy-dark text-white-off flex items-center justify-center font-bold text-xs shrink-0 shadow-inner font-heading">
                                            {{ substr($reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'S'), 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-navy-dark text-sm font-heading">{{ $reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'Siswa') }}</p>
                                            <p class="text-[10px] text-gray-muted font-mono">NISN: {{ $reg->student->studentProfile->nisn ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Approval -->
                                <td class="py-4 px-3 text-center whitespace-nowrap">
                                    @if($reg->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Disetujui
                                        </span>
                                    @elseif($reg->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- Nilai Semester Ganjil -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($gradeGanjil && $gradeGanjil->grade)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="px-2.5 py-1 rounded-md bg-navy-base text-white-off font-bold text-xs shadow-xs">
                                                {{ $gradeGanjil->grade }} @if($gradeGanjil->score !== null) ({{ $gradeGanjil->score }}) @endif
                                            </span>
                                            @if($gradeGanjil->notes)
                                                <span class="text-[10px] text-gray-500 mt-1 max-w-[120px] truncate" title="{{ $gradeGanjil->notes }}">{{ $gradeGanjil->notes }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Belum dinilai</span>
                                    @endif
                                </td>

                                <!-- Nilai Semester Genap -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($gradeGenap && $gradeGenap->grade)
                                        <div class="inline-flex flex-col items-center">
                                            <span class="px-2.5 py-1 rounded-md bg-navy-dark text-white-off font-bold text-xs shadow-xs">
                                                {{ $gradeGenap->grade }} @if($gradeGenap->score !== null) ({{ $gradeGenap->score }}) @endif
                                            </span>
                                            @if($gradeGenap->notes)
                                                <span class="text-[10px] text-gray-500 mt-1 max-w-[120px] truncate" title="{{ $gradeGenap->notes }}">{{ $gradeGenap->notes }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[11px] text-gray-400 italic">Belum dinilai</span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    @if($reg->status === 'approved')
                                        <button type="button" 
                                            onclick="openGradeModal(
                                                {{ $reg->id }}, 
                                                '{{ addslashes($reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'Siswa')) }}',
                                                '{{ $selectedYear->year_name ?? '-' }}',
                                                '{{ $gradeGanjil->grade ?? '' }}',
                                                '{{ $gradeGanjil->score ?? '' }}',
                                                '{{ addslashes($gradeGanjil->notes ?? '') }}',
                                                '{{ $gradeGenap->grade ?? '' }}',
                                                '{{ $gradeGenap->score ?? '' }}',
                                                '{{ addslashes($gradeGenap->notes ?? '') }}',
                                                '{{ route('guru.extracurriculars.grade', [$extracurricular->id, $reg->id]) }}'
                                            )"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-navy-light/10 hover:bg-navy-light/20 text-navy-dark rounded-lg font-bold text-xs border border-navy-light/30 transition-all hover:scale-105">
                                            <svg class="w-3.5 h-3.5 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            <span>{{ ($gradeGanjil || $gradeGenap) ? 'Ubah Nilai' : 'Input Nilai' }}</span>
                                        </button>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">Perlu disetujui</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="w-14 h-14 bg-white-off text-navy-light rounded-full flex items-center justify-center mx-auto mb-3 border border-navy-light/20">
                                        <svg class="w-7 h-7 text-navy-base" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    </div>
                                    <p class="text-navy-dark font-bold text-sm font-heading mb-0.5">Belum Ada Anggota</p>
                                    <p class="text-xs text-gray-muted">Belum ada siswa yang mendaftar pada kegiatan ekstrakurikuler ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($registrations->hasPages())
                <div class="px-6 py-4 border-t border-navy-light/20 bg-white-off/50">
                    {{ $registrations->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- MODAL INPUT / UBAH NILAI (GANJIL & GENAP) -->
<div id="gradeModal" class="fixed inset-0 z-50 hidden bg-navy-dark/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-navy-light/30 relative animate-in fade-in zoom-in duration-200 max-h-[90vh] overflow-y-auto">
        <!-- Close Button -->
        <button type="button" onclick="closeGradeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-navy-dark p-1 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="mb-5 pb-3 border-b border-navy-light/20">
            <div class="w-10 h-10 rounded-xl bg-navy-light/10 text-navy-base flex items-center justify-center mb-3 border border-navy-light/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-navy-dark font-heading">Penilaian Ekstrakurikuler Siswa</h3>
            <p class="text-xs text-gray-muted mt-0.5">
                Siswa: <strong id="modalStudentName" class="text-navy-dark font-semibold"></strong> • 
                Tahun Ajaran: <strong id="modalYearName" class="text-navy-base font-semibold"></strong>
            </p>
        </div>

        <form id="gradeForm" method="POST" action="" class="space-y-5 text-xs">
            @csrf
            <input type="hidden" name="academic_year_id" value="{{ $selectedYear->id ?? '' }}">

            <!-- SECTION 1: SEMESTER GANJIL -->
            <div class="bg-white-off/70 p-4 rounded-xl border border-navy-light/30 space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-navy-light/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-navy-base"></span>
                    <h4 class="font-bold text-navy-dark text-xs uppercase tracking-wider">1. Semester Ganjil</h4>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="inputGanjilGrade" class="block font-bold text-navy-dark mb-1">Predikat Nilai</label>
                        <select name="ganjil_grade" id="inputGanjilGrade" class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-semibold text-navy-dark bg-white">
                            <option value="">-- Kosongkan / Belum Dinilai --</option>
                            <option value="A">A (Sangat Baik)</option>
                            <option value="B">B (Baik)</option>
                            <option value="C">C (Cukup)</option>
                            <option value="D">D (Kurang)</option>
                        </select>
                    </div>

                    <div>
                        <label for="inputGanjilScore" class="block font-bold text-navy-dark mb-1">Nilai Angka (0 - 100)</label>
                        <input type="number" name="ganjil_score" id="inputGanjilScore" min="0" max="100" placeholder="Contoh: 90" class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-medium text-navy-dark bg-white">
                    </div>
                </div>

                <div>
                    <label for="inputGanjilNotes" class="block font-bold text-navy-dark mb-1">Catatan Evaluasi Semester Ganjil</label>
                    <textarea name="ganjil_notes" id="inputGanjilNotes" rows="2" placeholder="Catatan keaktifan dan capaian siswa di semester ganjil..." class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-normal text-navy-dark bg-white resize-none"></textarea>
                </div>
            </div>

            <!-- SECTION 2: SEMESTER GENAP -->
            <div class="bg-white-off/70 p-4 rounded-xl border border-navy-light/30 space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-navy-light/20">
                    <span class="w-2.5 h-2.5 rounded-full bg-navy-dark"></span>
                    <h4 class="font-bold text-navy-dark text-xs uppercase tracking-wider">2. Semester Genap</h4>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="inputGenapGrade" class="block font-bold text-navy-dark mb-1">Predikat Nilai</label>
                        <select name="genap_grade" id="inputGenapGrade" class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-semibold text-navy-dark bg-white">
                            <option value="">-- Kosongkan / Belum Dinilai --</option>
                            <option value="A">A (Sangat Baik)</option>
                            <option value="B">B (Baik)</option>
                            <option value="C">C (Cukup)</option>
                            <option value="D">D (Kurang)</option>
                        </select>
                    </div>

                    <div>
                        <label for="inputGenapScore" class="block font-bold text-navy-dark mb-1">Nilai Angka (0 - 100)</label>
                        <input type="number" name="genap_score" id="inputGenapScore" min="0" max="100" placeholder="Contoh: 92" class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-medium text-navy-dark bg-white">
                    </div>
                </div>

                <div>
                    <label for="inputGenapNotes" class="block font-bold text-navy-dark mb-1">Catatan Evaluasi Semester Genap</label>
                    <textarea name="genap_notes" id="inputGenapNotes" rows="2" placeholder="Catatan keaktifan dan capaian siswa di semester genap..." class="w-full px-3 py-2 rounded-xl border border-navy-light/30 focus:outline-none focus:ring-2 focus:ring-navy-base font-normal text-navy-dark bg-white resize-none"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-navy-light/20">
                <button type="button" onclick="closeGradeModal()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-100 transition-all">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-navy-base hover:bg-navy-dark text-white-off font-bold transition-all shadow-md shadow-navy-base/20 hover:shadow-lg active:scale-95">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openGradeModal(regId, studentName, yearName, ganjilGrade, ganjilScore, ganjilNotes, genapGrade, genapScore, genapNotes, actionUrl) {
        document.getElementById('modalStudentName').textContent = studentName;
        document.getElementById('modalYearName').textContent = yearName;

        // Ganjil
        document.getElementById('inputGanjilGrade').value = ganjilGrade || '';
        document.getElementById('inputGanjilScore').value = ganjilScore || '';
        document.getElementById('inputGanjilNotes').value = ganjilNotes || '';

        // Genap
        document.getElementById('inputGenapGrade').value = genapGrade || '';
        document.getElementById('inputGenapScore').value = genapScore || '';
        document.getElementById('inputGenapNotes').value = genapNotes || '';

        document.getElementById('gradeForm').action = actionUrl;
        document.getElementById('gradeModal').classList.remove('hidden');
    }

    function closeGradeModal() {
        document.getElementById('gradeModal').classList.add('hidden');
    }
</script>
@endsection
