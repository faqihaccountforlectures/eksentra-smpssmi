<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran Ekstrakurikuler</title>
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
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0 !important;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
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
        <h2>LAPORAN VERIFIKASI PEMBAYARAN IURAN EKSTRAKURIKULER</h2>
        <p>Munjuljaya, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41117 | Eksentra Portal Ekstrakurikuler</p>
    </div>

    <!-- METADATA & FILTER LAPORAN -->
    <table class="meta-table">
        <tr>
            <td width="16%"><strong>Tanggal Cetak</strong></td>
            <td width="2%">:</td>
            <td width="35%">{{ $printDate }}</td>
            <td width="16%"><strong>Filter Status</strong></td>
            <td width="2%">:</td>
            <td width="29%">
                @if($statusFilter == 'lunas') Lunas / Terverifikasi
                @elseif($statusFilter == 'belum_lunas') Belum Lunas (Tagihan)
                @elseif($statusFilter == 'verifikasi') Menunggu Verifikasi
                @elseif($statusFilter == 'ditolak') Ditolak
                @else Semua Status
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>:</td>
            <td>
                @if($startDate && $endDate)
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                @elseif($startDate)
                    Mulai {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                @elseif($endDate)
                    Sampai {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                @else
                    Semua Periode
                @endif
            </td>
            <td><strong>Total Transaksi</strong></td>
            <td>:</td>
            <td>{{ count($payments) }} Data Transaksi</td>
        </tr>
        @if($search)
        <tr>
            <td><strong>Kata Kunci</strong></td>
            <td>:</td>
            <td colspan="4">"{{ $search }}"</td>
        </tr>
        @endif
    </table>

    <!-- TABEL DATA PEMBAYARAN (MONOCHROME) -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="20%">Nama Lengkap Siswa</th>
                <th width="11%">NIS</th>
                <th width="16%">Ekstrakurikuler</th>
                <th width="14%">Bulan & Tahun</th>
                <th width="11%">Status</th>
                <th width="12%">Nominal</th>
                <th width="12%">Diverifikasi Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $payment->student->studentProfile->full_name ?? ($payment->student->studentProfile->nis ? 'NIS ' . $payment->student->studentProfile->nis : '-') }}</td>
                    <td class="text-center">{{ $payment->student->studentProfile->nis ?? '-' }}</td>
                    <td>{{ $payment->extracurricular->name ?? '-' }}</td>
                    <td class="text-center">
                        {{ $payment->month }} {{ $payment->year }}
                        @if($payment->payment_status !== 'unpaid' && $payment->created_at)
                            <div style="font-size: 7.5pt; color: #444444; margin-top: 1px;">Setor: {{ $payment->created_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        @if($payment->payment_status === 'verified')
                            LUNAS
                        @elseif($payment->payment_status === 'pending')
                            VERIFIKASI
                        @elseif($payment->payment_status === 'unpaid')
                            BELUM LUNAS
                        @elseif($payment->payment_status === 'rejected')
                            DITOLAK
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($payment->payment_status === 'verified' && $payment->verifier)
                            {{ $payment->verifier->display_name ?? 'Admin' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted" style="padding: 16px;">
                        Tidak ada data pembayaran yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Pemasukan (Status Lunas Terverifikasi):</td>
                <td class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
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
                <strong>Bendahara / Pengelola Ekstrakurikuler</strong>
                <div class="sign-space"></div>
                <strong><u>{{ auth()->user()->display_name ?? auth()->user()->username ?? 'Bendahara Sekolah' }}</u></strong>
            </td>
        </tr>
    </table>

</body>
</html>
