@extends('layout.apps')

@section('content')

@php
    $statusAktif = $status ?? request('status', 'belum');
@endphp

<div class="mr-auto payment-page-title">
    <h2 class="text-black font-w700 mb-1">Pembayaran</h2>
    <p class="mb-0">Data transaksi pembayaran pasien OQ Clinic Dentist.</p>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card payment-card">
            <div class="card-body">

                <div class="payment-header">
                    <div>
                        <h4 class="mb-1 font-w700">Daftar Pembayaran</h4>

                        @if($statusAktif == 'lunas')
                            <small class="text-muted">
                                Menampilkan data pasien yang sudah menyelesaikan pembayaran hari ini.
                            </small>
                        @else
                            <small class="text-muted">
                                Menampilkan data pasien yang belum melakukan pembayaran.
                            </small>
                        @endif
                    </div>

                    <form method="get" action="{{ url()->current() }}" class="payment-search">
                        <input type="hidden" name="status" value="{{ $statusAktif }}">

                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   name="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="Cari pasien, no rekam, atau tanggal..."
                                   autocomplete="off">

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-search">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tab pembayaran --}}
                <div class="payment-tabs">
                    <a href="{{ route('pembayaran', ['status' => 'belum']) }}"
                       class="payment-tab {{ $statusAktif == 'belum' ? 'active' : '' }}">
                        <i class="fa fa-clock-o"></i>
                        Belum Bayar
                        <span>{{ $jumlahBelumBayar ?? 0 }}</span>
                    </a>

                    <a href="{{ route('pembayaran', ['status' => 'lunas']) }}"
                       class="payment-tab {{ $statusAktif == 'lunas' ? 'active' : '' }}">
                        <i class="fa fa-check-circle"></i>
                        Sudah Bayar
                        <span>{{ $jumlahLunasHariIni ?? 0 }}</span>
                    </a>
                </div>

                {{-- Info kecil --}}
                <div class="payment-info-box">
                    @if($statusAktif == 'lunas')
                        <i class="fa fa-info-circle"></i>
                        <span>
                            Data lunas yang ditampilkan hanya transaksi hari ini. Untuk melihat seluruh riwayat pembayaran, gunakan menu <strong>Laporan Pembayaran</strong>.
                        </span>
                    @else
                        <i class="fa fa-info-circle"></i>
                        <span>
                            Data belum bayar menampilkan pasien yang perlu diproses pembayarannya oleh admin.
                        </span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table payment-table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Rekam</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Dokter</th>
                                <th>Cara Bayar</th>

                                @if($statusAktif == 'lunas')
                                    <th>Pemeriksaan</th>
                                    <th>Tindakan</th>
                                    <th>Obat</th>
                                    <th>Total</th>
                                @endif

                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $datas->firstItem() + $key }}</td>

                                    <td class="font-w700">
                                        {{ $row->no_rekam ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $row->tgl_rekam ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>{{ optional($row->pasien)->nama ?? '-' }}</strong>
                                    </td>

                                    <td>
                                        {{ optional($row->dokter)->nama ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $row->cara_bayar ?? '-' }}
                                    </td>

                                    @if($statusAktif == 'lunas')
                                        <td>
                                            Rp {{ number_format($row->biaya_pemeriksaan ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            Rp {{ number_format($row->biaya_tindakan ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            Rp {{ number_format($row->biaya_obat ?? 0, 0, ',', '.') }}
                                        </td>

                                        <td>
                                            <strong class="payment-total">
                                                Rp {{ number_format($row->total_biaya ?? 0, 0, ',', '.') }}
                                            </strong>
                                        </td>
                                    @endif

                                    <td>
                                        @if($statusAktif == 'lunas')
                                            <span class="status-badge status-paid">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="status-badge status-unpaid">
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center aksi-nowrap">

                                        @if($statusAktif == 'belum')

                                            <a href="{{ route('pembayaran.edit', $row->id) }}"
                                               class="btn btn-action btn-input"
                                               title="Input Pembayaran">
                                                Input
                                            </a>

                                        @else

                                            <a href="{{ route('pembayaran.nota', $row->id) }}"
                                               class="btn btn-action btn-nota"
                                               title="Cetak Nota">
                                                <i class="fa fa-file-text-o mr-1"></i> Nota
                                            </a>

                                            @php
                                                $noHp = optional($row->pasien)->no_hp ?? '';
                                                $noHp = preg_replace('/[^0-9]/', '', $noHp);

                                                if (substr($noHp, 0, 1) == '0') {
                                                    $noHp = '62' . substr($noHp, 1);
                                                }

                                                $pesanWA = urlencode(
                                                    "Halo Bapak/Ibu " . (optional($row->pasien)->nama ?? '-') . ",\n\n" .
                                                    "Berikut informasi pembayaran OQ Clinic Dentist:\n\n" .
                                                    "No Rekam: " . ($row->no_rekam ?? '-') . "\n" .
                                                    "Tanggal: " . ($row->tgl_rekam ?? '-') . "\n" .
                                                    "Dokter: " . (optional($row->dokter)->nama ?? '-') . "\n" .
                                                    "Biaya Pemeriksaan: Rp " . number_format($row->biaya_pemeriksaan ?? 0, 0, ',', '.') . "\n" .
                                                    "Biaya Tindakan: Rp " . number_format($row->biaya_tindakan ?? 0, 0, ',', '.') . "\n" .
                                                    "Biaya Obat: Rp " . number_format($row->biaya_obat ?? 0, 0, ',', '.') . "\n" .
                                                    "Total Pembayaran: Rp " . number_format($row->total_biaya ?? 0, 0, ',', '.') . "\n" .
                                                    "Status: Lunas\n\n" .
                                                    "Terima kasih atas kepercayaan Anda.\n" .
                                                    "OQ Clinic Dentist"
                                                );
                                            @endphp

                                            @if ($noHp)
                                                <a href="https://wa.me/{{ $noHp }}?text={{ $pesanWA }}"
                                                   target="_blank"
                                                   class="btn btn-action btn-wa"
                                                   title="Kirim WhatsApp">
                                                    <i class="fa fa-whatsapp"></i>
                                                </a>
                                            @else
                                                <button type="button"
                                                        class="btn btn-action btn-nohp"
                                                        disabled
                                                        title="Nomor HP tidak tersedia">
                                                    No HP
                                                </button>
                                            @endif

                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $statusAktif == 'lunas' ? '12' : '8' }}">
                                        <div class="empty-state">
                                            @if($statusAktif == 'lunas')
                                                <strong>Belum ada pembayaran lunas hari ini.</strong>
                                                <br>
                                                <span>Data akan tampil setelah admin menginput pembayaran pasien pada hari ini.</span>
                                            @else
                                                <strong>Belum ada pasien yang belum bayar.</strong>
                                                <br>
                                                <span>Data akan tampil apabila terdapat rekam medis yang belum memiliki total pembayaran.</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Menampilkan {{ $datas->firstItem() ?? 0 }} sampai {{ $datas->lastItem() ?? 0 }}
                        dari {{ $datas->total() }} data
                    </div>

                    <div>
                        {{ $datas->appends(request()->except('page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    .payment-page-title {
        margin-bottom: 18px;
    }

    .payment-page-title h2 {
        font-size: 26px;
        font-weight: 700;
        color: #1f2b5b !important;
    }

    .payment-page-title p {
        color: #7b7f9e !important;
    }

    .payment-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .payment-card .card-body {
        padding: 24px;
    }

    .payment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .payment-header h4 {
        font-size: 18px;
        color: #1f2b5b !important;
    }

    .payment-search {
        width: 420px;
        max-width: 100%;
        margin-left: auto;
    }

    .payment-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
        color: #333;
    }

    .payment-search .form-control:focus {
        box-shadow: none;
        border-color: #007A64;
    }

    .btn-search {
        height: 44px;
        min-width: 54px;
        background: #007A64;
        border-color: #007A64;
        color: #ffffff;
        border-radius: 0 8px 8px 0;
    }

    .btn-search:hover {
        background: #006451;
        border-color: #006451;
        color: #ffffff;
    }

    .payment-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 14px;
        border-bottom: 1px solid #edf0f5;
        padding-bottom: 12px;
        flex-wrap: wrap;
    }

    .payment-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        background: #f8fafc;
        color: #6b7280;
        font-size: 13px;
        font-weight: 800;
        border: 1px solid #edf0f5;
        transition: 0.2s;
    }

    .payment-tab:hover {
        color: #007A64;
        border-color: #007A64;
    }

    .payment-tab.active {
        background: #007A64;
        color: #ffffff;
        border-color: #007A64;
    }

    .payment-tab span {
        min-width: 24px;
        height: 24px;
        padding: 0 7px;
        border-radius: 999px;
        background: #ffffff;
        color: #007A64;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 900;
    }

    .payment-tab.active span {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    .payment-info-box {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: #f8fafc;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        padding: 12px 14px;
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 18px;
    }

    .payment-info-box i {
        color: #007A64;
        margin-top: 2px;
    }

    .payment-info-box strong {
        color: #1f2b5b;
    }

    .payment-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        overflow: hidden;
        white-space: nowrap;
    }

    .payment-table thead th {
        background: #ffffff;
        color: #111827;
        font-weight: 700;
        font-size: 13px;
        padding: 12px 10px;
        border-bottom: 1px solid #edf0f5;
        border-right: 1px solid #edf0f5;
        vertical-align: middle;
    }

    .payment-table thead th:last-child {
        border-right: none;
    }

    .payment-table tbody td {
        color: #333;
        font-size: 13px;
        padding: 13px 10px;
        border-bottom: 1px solid #edf0f5;
        border-right: 1px solid #edf0f5;
        vertical-align: middle;
    }

    .payment-table tbody td:last-child {
        border-right: none;
    }

    .payment-table tbody tr:last-child td {
        border-bottom: none;
    }

    .payment-table tbody tr:nth-child(even) {
        background: #fbfdfc;
    }

    .payment-table tbody tr:hover {
        background: #f8fafc;
    }

    .payment-total {
        color: #007A64;
        font-weight: 900;
    }

    .status-badge {
        display: inline-block;
        min-width: 76px;
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

    .aksi-nowrap {
        white-space: nowrap;
        min-width: 120px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 34px;
        min-width: 46px;
        padding: 0 9px;
        margin-right: 4px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
    }

    .btn-action:last-child {
        margin-right: 0;
    }

    .btn-input {
        background: #007A64;
        border-color: #007A64;
        color: #ffffff;
    }

    .btn-input:hover {
        background: #006451;
        border-color: #006451;
        color: #ffffff;
    }

    .btn-nota {
        background: #ffffff;
        border: 1px solid #007A64;
        color: #007A64;
    }

    .btn-nota:hover {
        background: #007A64;
        border-color: #007A64;
        color: #ffffff;
    }

    .btn-wa {
        min-width: 38px;
        width: 38px;
        background: #ffffff;
        border: 1px solid #22c55e;
        color: #16a34a;
        font-size: 16px;
    }

    .btn-wa:hover {
        background: #22c55e;
        border-color: #22c55e;
        color: #ffffff;
    }

    .btn-nohp {
        background: #6c757d;
        border-color: #6c757d;
        color: #ffffff;
    }

    .btn-nohp:hover {
        color: #ffffff;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #7b7f9e;
    }

    .empty-state strong {
        color: #1f2b5b;
    }

    .pagination-wrapper {
        margin-top: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pagination-info {
        color: #7b7f9e;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .payment-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .payment-search {
            width: 100%;
        }

        .payment-table thead th,
        .payment-table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

@endsection