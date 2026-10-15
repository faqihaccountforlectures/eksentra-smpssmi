<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian {{ $extracurricular->name }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #111827;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px double #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0 0;
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 9pt;
            color: #475569;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 14px;
            font-size: 9.5pt;
        }
        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .badge-approved { background-color: #dcfce7; color: #166534; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-rejected { background-color: #ffe4e6; color: #9f1239; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #94a3b8;
            padding: 6px 7px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #64748b; font-size: 8pt; font-style: italic; }

        .grade-box {
            display: inline-block;
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 9pt;
        }
        .grade-ganjil { background-color: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .grade-genap { background-color: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1; }

        .signatures {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signatures td {
            border: none;
            text-align: center;
            vertical-align: top;
            font-size: 9.5pt;
            width: 50%;
        }
        .sign-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <div class="header">
        <h1>SMP SCIENCE MUTIARA INSANI</h1>
        <h2>LAPORAN PENILAIAN PERKEMBANGAN EKSTRAKURIKULER</h2>
        <p>Jl. KH. Noer Ali, Sukadanau, Cikarang Barat, Kab. Bekasi, Jawa Barat | Portal Informasi Eksentra</p>
    </div>

    <!-- METADATA LAPORAN -->
    <table class="meta-table">
        <tr>
            <td width="18%"><strong>Nama Ekstrakurikuler</strong></td>
            <td width="2%">:</td>
            <td width="35%"><strong>{{ $extracurricular->name }}</strong></td>
            <td width="18%"><strong>Tahun Ajaran</strong></td>
            <td width="2%">:</td>
            <td width="25%">{{ $selectedYear->year_name ?? '-' }} {{ $selectedYear && $selectedYear->is_active ? '(Aktif)' : '' }}</td>
        </tr>
        <tr>
            <td><strong>Pembina / Coach</strong></td>
            <td>:</td>
            <td>{{ $coachUser->display_name ?? $coachUser->name }}</td>
            <td><strong>Filter Kelas</strong></td>
            <td>:</td>
            <td>{{ $selectedClass ? $selectedClass->name . ' (Tingkat ' . $selectedClass->grade_level . ')' : 'Semua Kelas' }}</td>
        </tr>
        <tr>
            <td><strong>Jadwal Pelaksanaan</strong></td>
            <td>:</td>
            <td>{{ $extracurricular->schedule }}</td>
            <td><strong>Tanggal Cetak</strong></td>
            <td>:</td>
            <td>{{ $printDate }}</td>
        </tr>
        <tr>
            <td><strong>Total Anggota Terdata</strong></td>
            <td>:</td>
            <td>{{ $registrations->count() }} Siswa</td>
            <td><strong>Status Laporan</strong></td>
            <td>:</td>
            <td>Resmi / Terverifikasi</td>
        </tr>
    </table>

    <!-- TABEL DATA NILAI SISWA -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">NIS</th>
                <th width="22%">Nama Lengkap Siswa</th>
                <th width="8%">Kelas</th>
                <th width="9%">Status</th>
                <th width="11%">Semester Ganjil</th>
                <th width="12%">Catatan Ganjil</th>
                <th width="11%">Semester Genap</th>
                <th width="11%">Catatan Genap</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $reg)
                @php
                    $gradeGanjil = $reg->grades ? $reg->grades->firstWhere('semester', 'ganjil') : null;
                    $gradeGenap = $reg->grades ? $reg->grades->firstWhere('semester', 'genap') : null;
                    $className = $reg->student ? $reg->student->getClassNameForYear($selectedYear->id ?? null) : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $reg->student->studentProfile->nis ?? '-' }}</td>
                    <td class="font-bold">{{ $reg->student->studentProfile->full_name ?? ($reg->student->name ?? 'Siswa') }}</td>
                    <td class="text-center font-bold">{{ $className }}</td>
                    <td class="text-center">
                        @if($reg->status === 'approved')
                            <span class="badge badge-approved">Disetujui</span>
                        @elseif($reg->status === 'pending')
                            <span class="badge badge-pending">Menunggu</span>
                        @else
                            <span class="badge badge-rejected">Ditolak</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($gradeGanjil && $gradeGanjil->grade)
                            <span class="grade-box grade-ganjil">
                                {{ $gradeGanjil->grade }} @if($gradeGanjil->score !== null) ({{ $gradeGanjil->score }}) @endif
                            </span>
                        @else
                            <span class="text-muted">Belum dinilai</span>
                        @endif
                    </td>
                    <td>
                        @if($gradeGanjil && $gradeGanjil->notes)
                            {{ $gradeGanjil->notes }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($gradeGenap && $gradeGenap->grade)
                            <span class="grade-box grade-genap">
                                {{ $gradeGenap->grade }} @if($gradeGenap->score !== null) ({{ $gradeGenap->score }}) @endif
                            </span>
                        @else
                            <span class="text-muted">Belum dinilai</span>
                        @endif
                    </td>
                    <td>
                        @if($gradeGenap && $gradeGenap->notes)
                            {{ $gradeGenap->notes }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="padding: 20px;">
                        Tidak ada data siswa / anggota yang sesuai dengan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAPITULASI RINGKAS -->
    <table style="width: 100%; font-size: 8.5pt; color: #475569; margin-bottom: 20px;">
        <tr>
            <td>
                * Keterangan Skala Nilai: <strong>A</strong> = Sangat Baik (85-100), <strong>B</strong> = Baik (75-84), <strong>C</strong> = Cukup (60-74), <strong>D</strong> = Kurang (&lt;60).
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN PENGESAHAN -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala Sekolah SMP Science Mutiara Insani</strong>
                <div class="sign-space"></div>
                <strong><u>Dra. Hj. Titin Sugiarti, M.Pd.</u></strong><br>
                NIP. 197108151998022001
            </td>
            <td>
                Bekasi, {{ $printDate }}<br>
                <strong>Pembina Ekstrakurikuler {{ $extracurricular->name }}</strong>
                <div class="sign-space"></div>
                <strong><u>{{ $coachUser->display_name ?? $coachUser->name }}</u></strong><br>
                ID Pembina: {{ str_pad($coachUser->id, 5, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
    </table>

</body>
</html>
