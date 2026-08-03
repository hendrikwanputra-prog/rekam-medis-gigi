@extends('layout.apps')

@section('content')

<div class="container-fluid">

    {{-- HEADER HALAMAN --}}
    <div class="row no-print">
        <div class="col-xl-12">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 12px;">
                <div>
                    <h2 class="page-title mb-1">Laporan Pembayaran</h2>
                    <p class="page-subtitle mb-0">
                        Rekap transaksi pembayaran pasien OQ Clinic Dentist.
                    </p>
                </div>

                <button type="button" onclick="window.print()" class="btn btn-print px-4">
                    <i class="fa fa-print"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    <div id="printArea">

        {{-- HEADER KHUSUS CETAK --}}
        <div class="laporan-header print-only">
            <div class="clinic-header">
                <img src="{{ asset('images/logonotaoq.png') }}" alt="Logo OQ Clinic Dentist" class="clinic-logo">

                <div class="clinic-text">
                    <h2>OQ CLINIC DENTIST</h2>
                    <p>Jl. Utan Kayu Raya No.100C, RT.2/RW.9</p>
                    <p>Jakarta Timur, DKI Jakarta 13120</p>
                    <p>Telp/HP: 082163339515</p>
                </div>
            </div>

            <div class="kop-line"></div>

            <div class="report-title">
                <h3>LAPORAN PEMBAYARAN</h3>
                <p>
                    Periode:
                    @if(request('tanggal_awal') && request('tanggal_akhir'))
                        {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                    @else
                        Semua Data
                    @endif

                    @if(request('dokter_id'))
                        |
                        Dokter:
                        {{ optional($dokters->firstWhere('id', request('dokter_id')))->nama ?? '-' }}
                    @endif
                </p>
            </div>
        </div>

        {{-- CARD RINGKASAN --}}
        <div class="row summary-row no-print">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="summary-card income-card">
                    <div class="summary-icon">
                        <i class="fa fa-credit-card"></i>
                    </div>
                    <div>
                        <span>Total Pendapatan</span>
                        <h3>Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="summary-card transaction-card">
                    <div class="summary-icon">
                        <i class="fa fa-list-alt"></i>
                    </div>
                    <div>
                        <span>Jumlah Transaksi</span>
                        <h3>{{ $jumlahTransaksi ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="summary-card paid-card">
                    <div class="summary-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div>
                        <span>Transaksi Lunas</span>
                        <h3>{{ $transaksiLunas ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="summary-card unpaid-card">
                    <div class="summary-icon">
                        <i class="fa fa-exclamation-circle"></i>
                    </div>
                    <div>
                        <span>Belum Bayar</span>
                        <h3>{{ $transaksiBelumBayar ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER LAPORAN --}}
        <div class="row no-print">
            <div class="col-xl-12">
                <div class="card filter-card">
                    <div class="card-body">

                        <div class="mb-4">
                            <h4 class="section-title mb-1">Filter Laporan</h4>
                            <small class="text-muted">
                                Pilih rentang tanggal dan dokter untuk melihat laporan pembayaran.
                            </small>
                        </div>

                        <form method="get" action="{{ route('pembayaran.laporan') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="filter-label">Tanggal Awal</label>
                                    <input type="date"
                                           name="tanggal_awal"
                                           class="form-control"
                                           value="{{ request('tanggal_awal') }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="filter-label">Tanggal Akhir</label>
                                    <input type="date"
                                           name="tanggal_akhir"
                                           class="form-control"
                                           value="{{ request('tanggal_akhir') }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="filter-label">Dokter</label>
                                    <select name="dokter_id" class="form-control">
                                        <option value="" {{ request('dokter_id') == '' ? 'selected' : '' }}>
                                            Semua Dokter
                                        </option>

                                        @foreach($dokters as $dokter)
                                            <option value="{{ $dokter->id }}" {{ request('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                                {{ $dokter->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-filter mr-2 px-4">
                                        <i class="fa fa-search"></i> Filter
                                    </button>

                                    <a href="{{ route('pembayaran.laporan') }}" class="btn btn-reset px-4">
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL LAPORAN --}}
        <div class="row">
            <div class="col-xl-12">
                <div class="card report-card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap" style="gap: 12px;">
                            <div>
                                <h4 class="section-title mb-1">Daftar Transaksi Pembayaran</h4>
                                <small class="text-muted">
                                    Data pembayaran pasien berdasarkan rekam medis.
                                </small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table laporan-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Rekam</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pasien</th>
                                        <th>Dokter</th>
                                        <th>Cara Bayar</th>
                                        <th>Pemeriksaan</th>
                                        <th>Tindakan</th>
                                        <th>Obat</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($datas as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><strong>{{ $row->no_rekam ?? '-' }}</strong></td>
                                            <td>{{ $row->tgl_rekam ?? '-' }}</td>
                                            <td>{{ optional($row->pasien)->nama ?? '-' }}</td>
                                            <td>{{ optional($row->dokter)->nama ?? '-' }}</td>
                                            <td>{{ $row->cara_bayar ?? '-' }}</td>

                                            <td>Rp {{ number_format($row->biaya_pemeriksaan ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($row->biaya_tindakan ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($row->biaya_obat ?? 0, 0, ',', '.') }}</td>

                                            <td>
                                                <strong class="report-total-text">
                                                    Rp {{ number_format($row->total_biaya ?? 0, 0, ',', '.') }}
                                                </strong>
                                            </td>

                                            <td>
                                                @if (($row->total_biaya ?? 0) > 0)
                                                    <span class="status-badge status-paid">Lunas</span>
                                                @else
                                                    <span class="status-badge status-unpaid">Belum Bayar</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">
                                                <div class="empty-state">
                                                    <strong>Belum ada data laporan pembayaran.</strong>
                                                    <br>
                                                    <span>Data akan muncul setelah transaksi pembayaran tersedia.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                <tfoot>
                                    <tr class="report-total-row">
                                        <th colspan="9" class="text-right">Total Pendapatan</th>
                                        <th colspan="2">
                                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- TANDA TANGAN CETAK --}}
                        <div class="signature-report print-only">
                            <div></div>
                            <div class="signature-content">
                                <p>Jakarta, {{ date('d-m-Y') }}</p>
                                <p>Penanggung Jawab</p>
                                <br><br><br>
                                <p><strong>OQ Clinic Dentist</strong></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #1f2b5b;
    }

    .page-subtitle {
        color: #7b7f9e;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2b5b;
    }

    .btn-print,
    .btn-filter {
        background: #007A64;
        border-color: #007A64;
        color: #ffffff;
        font-weight: 700;
        border-radius: 6px;
    }

    .btn-print:hover,
    .btn-filter:hover {
        background: #006451;
        border-color: #006451;
        color: #ffffff;
    }

    .btn-reset {
        background: #f3f4f6;
        border-color: #f3f4f6;
        color: #374151;
        font-weight: 700;
        border-radius: 6px;
    }

    .btn-reset:hover {
        background: #e5e7eb;
        border-color: #e5e7eb;
        color: #374151;
    }

    .summary-row {
        margin-bottom: 6px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #edf0f5;
        border-radius: 12px;
        padding: 18px 18px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 14px;
        min-height: 86px;
    }

    .summary-card span {
        display: block;
        color: #7b7f9e;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .summary-card h3 {
        font-size: 18px;
        color: #1f2b5b;
        font-weight: 800;
        margin-bottom: 0;
    }

    .summary-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #007A64;
        background: #e9f8f4;
        font-size: 18px;
    }

    .transaction-card .summary-icon {
        color: #3157ff;
        background: #eef2ff;
    }

    .paid-card .summary-icon {
        color: #007A64;
        background: #e9f8f4;
    }

    .unpaid-card .summary-icon {
        color: #f59e0b;
        background: #fff7ed;
    }

    .filter-card,
    .report-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
        margin-bottom: 18px;
    }

    .filter-card .card-body,
    .report-card .card-body {
        padding: 24px;
    }

    .filter-label {
        font-weight: 700;
        color: #374151;
        font-size: 13px;
    }

    .form-control {
        min-height: 44px;
        border: 1px solid #edf0f5;
        color: #333;
    }

    .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .laporan-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        overflow: hidden;
        white-space: nowrap;
        margin-bottom: 0;
    }

    .laporan-table thead th {
        background: #ffffff;
        color: #111827;
        font-weight: 700;
        font-size: 13px;
        padding: 12px 10px;
        border-bottom: 1px solid #edf0f5;
        border-right: 1px solid #edf0f5;
        vertical-align: middle;
    }

    .laporan-table thead th:last-child {
        border-right: none;
    }

    .laporan-table tbody td {
        color: #333;
        font-size: 13px;
        padding: 13px 10px;
        border-bottom: 1px solid #edf0f5;
        border-right: 1px solid #edf0f5;
        vertical-align: middle;
    }

    .laporan-table tbody td:last-child {
        border-right: none;
    }

    .laporan-table tbody tr:last-child td {
        border-bottom: none;
    }

    .laporan-table tbody tr:nth-child(even) {
        background: #fbfdfc;
    }

    .laporan-table tbody tr:hover {
        background: #f8fafc;
    }

    .report-total-text {
        color: #007A64;
        font-weight: 900;
    }

    .report-total-row th {
        background: #f8fafc;
        color: #1f2b5b;
        font-weight: 800;
        padding: 14px 10px;
        border-top: 1px solid #edf0f5;
    }

    .status-badge {
        display: inline-block;
        min-width: 58px;
        padding: 7px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
        text-align: center;
    }

    .status-paid {
        background: #22c55e;
        color: #ffffff;
    }

    .status-unpaid {
        background: #f59e0b;
        color: #ffffff;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #7b7f9e;
    }

    .print-only {
        display: none;
    }

    .signature-report {
        margin-top: 45px;
        display: none;
        grid-template-columns: 1fr 260px;
    }

    .signature-content {
        text-align: center;
        font-size: 12px;
        color: #111827;
    }

    @media (max-width: 768px) {
        .laporan-table thead th,
        .laporan-table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }

    @media print {
        body {
            background: #ffffff !important;
        }

        .no-print,
        .deznav,
        .header,
        .nav-header,
        .footer,
        .hamburger,
        .notification_dropdown {
            display: none !important;
        }

        .content-body {
            margin-left: 0 !important;
            padding-top: 0 !important;
        }

        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
        }

        .print-only {
            display: block !important;
        }

        .laporan-header {
            display: block !important;
            margin-bottom: 18px;
        }

        .clinic-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
        }

        .clinic-logo {
            width: 78px;
            height: 78px;
            object-fit: contain;
        }

        .clinic-text {
            text-align: center;
        }

        .clinic-text h2 {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
            color: #000000;
        }

        .clinic-text p {
            margin: 0;
            font-size: 12px;
            color: #000000;
        }

        .kop-line {
            border-top: 2px solid #000000;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .report-title {
            text-align: center;
            margin-bottom: 16px;
        }

        .report-title h3 {
            font-size: 16px;
            font-weight: 800;
            text-decoration: underline;
            margin-bottom: 4px;
            color: #000000;
        }

        .report-title p {
            font-size: 12px;
            margin: 0;
            color: #000000;
        }

        .summary-row {
            display: none !important;
        }

        .report-card,
        .report-card .card-body {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .report-card .d-flex {
            display: none !important;
        }

        .laporan-table {
            border-collapse: collapse !important;
            border: 1px solid #000000 !important;
            white-space: normal !important;
            width: 100% !important;
        }

        .laporan-table thead th,
        .laporan-table tbody td,
        .laporan-table tfoot th {
            border: 1px solid #000000 !important;
            color: #000000 !important;
            background: #ffffff !important;
            font-size: 10px !important;
            padding: 6px !important;
        }

        .status-badge {
            border: none !important;
            background: transparent !important;
            color: #000000 !important;
            padding: 0 !important;
            font-size: 10px !important;
            min-width: auto !important;
        }

        .signature-report {
            display: grid !important;
        }

        @page {
            size: landscape;
            margin: 12mm;
        }
    }
</style>

@endsection