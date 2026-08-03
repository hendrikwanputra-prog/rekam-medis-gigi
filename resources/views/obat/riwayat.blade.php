@extends('layout.apps')
@section('content')

<style>
    .page-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #1f2b5b;
        margin-bottom: 6px;
    }

    .page-subtitle {
        color: #7b7f9e;
        margin-bottom: 0;
    }

    .riwayat-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .riwayat-toolbar-text {
        flex: 1;
        min-width: 260px;
        color: #7b7f9e;
        padding-bottom: 10px;
    }

    .riwayat-search {
        width: 420px;
        max-width: 100%;
        margin-left: auto;
    }

    .riwayat-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
        color: #333;
    }

    .riwayat-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .btn-search {
        height: 44px;
        min-width: 54px;
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        border-radius: 0 8px 8px 0;
    }

    .btn-search:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .riwayat-table {
        margin-bottom: 0;
    }

    .riwayat-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .riwayat-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .riwayat-table th:first-child,
    .riwayat-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .riwayat-table th:nth-child(2),
    .riwayat-table td:nth-child(2) {
        width: 170px;
    }

    .riwayat-table th:nth-child(3),
    .riwayat-table td:nth-child(3) {
        width: 120px;
    }

    .riwayat-table th:nth-child(5),
    .riwayat-table td:nth-child(5) {
        width: 90px;
        text-align: center;
    }

    .riwayat-table th:nth-child(7),
    .riwayat-table td:nth-child(7),
    .riwayat-table th:nth-child(8),
    .riwayat-table td:nth-child(8) {
        width: 130px;
        text-align: right;
    }

    .medicine-code {
        color: #1f2b5b;
        font-weight: 700;
    }

    .medicine-name {
        color: #111827;
        font-weight: 700;
    }

    .price-text {
        color: #333;
        font-weight: 600;
    }

    .payment-text {
        color: #333;
        font-weight: 500;
    }

    .patient-link {
        color: #1f2b5b;
        font-weight: 700;
        text-decoration: none;
    }

    .patient-link:hover {
        color: #007A64;
        text-decoration: none;
    }

    .empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #7b7f9e;
    }

    .empty-state strong {
        color: #1f2b5b;
        font-size: 15px;
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
        .riwayat-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .riwayat-search {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
        }

        .riwayat-table th,
        .riwayat-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Riwayat Obat Keluar</h2>
        <p class="page-subtitle">
            Riwayat pengeluaran obat berdasarkan resep dan pemberian obat pasien.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="riwayat-toolbar">
                    <div class="riwayat-toolbar-text">
                        <span>
                            Total riwayat obat keluar:
                            <strong>{{ $datas->total() }}</strong>
                            data.
                        </span>
                    </div>

                    <div class="riwayat-search">
                        <form method="get" action="{{ url()->current() }}">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="keyword"
                                       value="{{ request('keyword') }}"
                                       placeholder="Cari kode obat, nama obat, pasien, atau tanggal..."
                                       autocomplete="off">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table riwayat-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl Keluar</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Sub Total</th>
                                <th>Cara Bayar / Pasien</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $datas->firstItem() + $key }}</td>

                                    <td>
                                        {{ $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-' }}
                                    </td>

                                    <td>
                                        <span class="medicine-code">
                                            {{ optional($row->obat)->kd_obat ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="medicine-name">
                                            {{ optional($row->obat)->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $row->jumlah ?? 0 }}
                                    </td>

                                    <td>
                                        {{ optional($row->obat)->satuan ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="price-text">
                                            Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="price-text">
                                            Rp {{ number_format($row->subtotal ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="payment-text">
                                            {{ optional($row->rekam)->cara_bayar ?? '-' }}
                                        </span>

                                        <br>

                                        @if(optional($row->rekam)->id)
                                            <strong>
                                                <a href="{{ Route('obat.pengeluaran', $row->rekam_id) }}" class="patient-link">
                                                    {{ optional(optional($row->rekam)->pasien)->nama ?? '-' }}
                                                </a>
                                            </strong>
                                        @else
                                            <strong>-</strong>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <strong>Belum ada riwayat obat keluar.</strong>
                                            <br>
                                            <span>
                                                Data akan muncul setelah resep obat pasien diproses.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
</div>

@endsection