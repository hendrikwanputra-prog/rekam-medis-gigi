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

    .resep-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .resep-toolbar-text {
        flex: 1;
        min-width: 260px;
        color: #7b7f9e;
        padding-bottom: 10px;
    }

    .resep-search {
        width: 420px;
        max-width: 100%;
        margin-left: auto;
    }

    .resep-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
        color: #333;
    }

    .resep-search .form-control:focus {
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

    .resep-table {
        margin-bottom: 0;
    }

    .resep-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .resep-table td {
        vertical-align: top;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .resep-table th:first-child,
    .resep-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .resep-table th:nth-child(2),
    .resep-table td:nth-child(2) {
        width: 140px;
    }

    .resep-table th:nth-child(3),
    .resep-table td:nth-child(3) {
        width: 190px;
    }

    .resep-table th:last-child,
    .resep-table td:last-child {
        width: 120px;
        text-align: center;
    }

    .patient-name {
        color: #111827;
        font-weight: 800;
    }

    .diagnosa-list,
    .tindakan-list {
        padding-left: 18px;
        margin-bottom: 0;
    }

    .diagnosa-list li,
    .tindakan-list li {
        margin-bottom: 6px;
    }

    .resep-box {
        background: #f8fafc;
        border: 1px solid #edf0f5;
        border-radius: 8px;
        padding: 10px 12px;
        margin-bottom: 10px;
        color: #374151;
    }

    .resep-box strong {
        color: #1f2b5b;
    }

    .btn-proses {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        padding: 8px 14px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-proses:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
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
        .resep-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .resep-search {
            width: 100%;
            max-width: 100%;
        }

        .resep-table th,
        .resep-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Resep dan Pemberian Obat</h2>
        <p class="page-subtitle">
            Daftar pasien yang memiliki resep obat dan perlu diproses pemberian obatnya.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="resep-toolbar">
                    <div class="resep-toolbar-text">
                        <span>
                            Total resep tersedia:
                            <strong>
                                {{ method_exists($datas, 'total') ? $datas->total() : $datas->count() }}
                            </strong>
                            data.
                        </span>
                    </div>

                    <div class="resep-search">
                        <form method="get" action="{{ url()->current() }}">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="keyword"
                                       value="{{ request('keyword') }}"
                                       placeholder="Cari nama pasien, tanggal, atau diagnosa..."
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
                    <table class="table resep-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tgl Periksa</th>
                                <th>Nama Pasien</th>
                                <th>Diagnosa</th>
                                <th>Tindakan / Resep</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>
                                        {{ method_exists($datas, 'firstItem') ? $datas->firstItem() + $key : $key + 1 }}
                                    </td>

                                    <td>
                                        {{ $row->tgl_rekam ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="patient-name">
                                            {{ optional($row->pasien)->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($row->poli == "Poli Gigi")
                                            <ul class="diagnosa-list">
                                                @forelse ($row->gigi() as $item)
                                                    <li>
                                                        {{ $item->diagnosa ?? '-' }}
                                                        @if(optional($item->diagnosis)->name_id)
                                                            , {{ optional($item->diagnosis)->name_id }}
                                                        @endif
                                                    </li>
                                                @empty
                                                    <span class="text-muted">Diagnosa belum tersedia.</span>
                                                @endforelse
                                            </ul>
                                        @else
                                            @forelse ($row->diagnosa() as $item)
                                                <div class="mb-2">
                                                    <strong>{{ optional($item->diagnosis)->code ?? '-' }}</strong>
                                                    <br>
                                                    {{ optional($item->diagnosis)->name_id ?? '-' }}
                                                </div>
                                            @empty
                                                <span class="text-muted">Diagnosa belum tersedia.</span>
                                            @endforelse
                                        @endif
                                    </td>

                                    <td>
                                        @if ($row->poli == "Poli Gigi")
                                            @if ($row->resep_obat != null)
                                                <div class="resep-box">
                                                    <strong>Resep Obat:</strong>
                                                    <br>
                                                    {!! $row->resep_obat !!}
                                                </div>
                                            @endif

                                            <ul class="tindakan-list">
                                                @forelse ($row->gigi() as $item)
                                                    <li>
                                                        {{ optional($item->tindak)->nama ?? '-' }}
                                                    </li>
                                                @empty
                                                    <span class="text-muted">Tindakan belum tersedia.</span>
                                                @endforelse
                                            </ul>
                                        @else
                                            @if ($row->resep_obat != null)
                                                <div class="resep-box">
                                                    <strong>Resep Obat:</strong>
                                                    <br>
                                                    {!! $row->resep_obat !!}
                                                </div>
                                            @endif

                                            {!! $row->tindakan ?? '<span class="text-muted">Tindakan belum tersedia.</span>' !!}
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ Route('obat.pengeluaran', $row->id) }}"
                                           class="btn btn-proses">
                                            <i class="fa fa-pencil"></i>
                                            Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <strong>Belum ada data resep obat.</strong>
                                            <br>
                                            <span>
                                                Data resep akan muncul setelah dokter menambahkan resep pada rekam medis pasien.
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($datas, 'links'))
                        <div class="pagination-wrapper">
                            <div class="pagination-info">
                                Menampilkan {{ $datas->firstItem() ?? 0 }} sampai {{ $datas->lastItem() ?? 0 }}
                                dari {{ $datas->total() }} data
                            </div>

                            <div>
                                {{ $datas->appends(request()->except('page'))->links() }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection