<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian {{ $extracurricular->name }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000000;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            border-bottom: 2.5px double #000000;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 3px 0 0;
            font-size: 12pt;
            font-weight: bold;
            color: #000000;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 9pt;
            color: #333333;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 9pt;
        }
        .meta-table td {
            padding: 2px 4px;
            vertical-align: top;
            border: none;
        }
        .badge {
            display: inline-block;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            table-layout: fixed;
            word-wrap: break-word;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 8.5pt;
        }
        .data-table th, .data-table td {
            border: 1px solid #000000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #d1d5db;
            color: #000000;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-muted { color: #555555; font-size: 8pt; font-style: italic; }

        .grade-box {
            display: inline-block;
            padding: 2px 6px;
            font-weight: bold;
            border-radius: 3px;
            font-size: 8.5pt;
            background-color: #f0f0f0;
            color: #000000;
            border: 1px solid #777777;
        }

        .signatures {
            width: 100%;
            margin-top: 20px;
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

    <!-- KOP SURAT RESMI (MONOCHROME) -->
    <div class="header">
        <h1>SMPS SCIENCE MUTIARA INSANI</h1>
        <h2>LAPORAN PENILAIAN PERKEMBANGAN EKSTRAKURIKULER</h2>
        <p>Munjuljaya, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41117 | Eksentra Portal Ekstrakurikuler</p>
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
            <td>{{ $coachUser->display_name ?? $coachUser->username }}</td>
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

    <!-- TABEL DATA NILAI SISWA (MONOCHROME) -->
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
                    <td class="font-bold">{{ $reg->student->studentProfile->full_name ?? ($reg->student->display_name ?? 'Siswa') }}</td>
                    <td class="text-center font-bold">{{ $className }}</td>
                    <td class="text-center">
                        @if($reg->status === 'approved')
                            <span class="badge">Disetujui</span>
                        @elseif($reg->status === 'pending')
                            <span class="badge" style="font-style: italic;">Menunggu</span>
                        @else
                            <span class="badge" style="text-decoration: line-through;">Ditolak</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($gradeGanjil && $gradeGanjil->grade)
                            <span class="grade-box">
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
                            <span class="grade-box">
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
                    <td colspan="9" class="text-center text-muted" style="padding: 18px;">
                        Tidak ada data siswa / anggota yang sesuai dengan filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAPITULASI RINGKAS SKALA NILAI -->
    <table style="width: 100%; font-size: 8pt; color: #444444; margin-bottom: 16px;">
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
                <strong>Kepala Sekolah SMPS Science Mutiara Insani</strong>
                <div class="sign-space"></div>
                <strong><u>Rika Aryanti, M. Pd,. Gr</u></strong>
            </td>
            <td>
                Purwakarta, {{ $printDate }}<br>
                <strong>Pembina Ekstrakurikuler {{ $extracurricular->name }}</strong>
                <div class="sign-space"></div>
                <strong><u>{{ $coachUser->display_name ?? $coachUser->username }}</u></strong><br>
                ID Pembina: {{ str_pad($coachUser->id, 5, '0', STR_PAD_LEFT) }}
            </td>
        </tr>
    </table>

</body>
</html>
