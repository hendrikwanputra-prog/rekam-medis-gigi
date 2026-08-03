@extends('layout.apps')
@section('content')

<style>
    .page-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
    }

    .page-header-title {
        font-size: 26px;
        font-weight: 700;
        color: #1f2b5b;
        margin-bottom: 6px;
    }

    .page-header-subtitle {
        color: #7b7f9e;
        margin-bottom: 0;
    }

    .pasien-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .pasien-toolbar-text {
        flex: 1;
        min-width: 300px;
        color: #7b7f9e;
    }

    .pasien-search {
        max-width: 520px;
        width: 100%;
        margin-left: auto;
    }

    .pasien-table {
        margin-bottom: 0;
    }

    .pasien-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .pasien-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .pasien-table th:nth-child(4),
    .pasien-table td:nth-child(4) {
        min-width: 110px;
    }

    .pasien-table th:nth-child(9),
    .pasien-table td:nth-child(9) {
        min-width: 135px;
    }

    .pasien-table td:nth-child(4) {
        white-space: nowrap;
    }

    .pasien-table .badge,
    .pasien-table span[class*="badge"],
    .pasien-table .btn-outline-success {
        font-size: 12px !important;
        padding: 6px 10px !important;
    }

    .rm-link {
        color: #1f2b5b;
        font-weight: 700;
    }

    .rm-link:hover {
        color: #007A64;
    }

    .patient-name {
        color: #111827;
        font-weight: 700;
        white-space: nowrap;
    }

    .patient-small {
        color: #7b7f9e;
        font-size: 12px;
        line-height: 1.4;
    }

    .alamat-column {
        min-width: 170px;
        max-width: 220px;
        white-space: normal;
    }

    .pengobatan-column {
        min-width: 130px;
    }

    .action-wrapper {
        display: flex;
        justify-content: center;
        gap: 6px;
        flex-wrap: nowrap;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #7b7f9e;
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
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-header-title">Data Pasien</h2>
        <p class="page-header-subtitle">
            Pengelolaan data pasien OQ Clinic Dentist.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                {{-- Toolbar pencarian --}}
                <div class="pasien-toolbar">
                    <div class="pasien-toolbar-text">
                        <span>
                            Total pasien terdaftar: <strong>{{ $datas->total() }}</strong> data.
                        </span>
                    </div>

                    <div class="pasien-search">
                        <form method="get" action="{{ url()->current() }}">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       name="keyword"
                                       value="{{ request('keyword') }}"
                                       placeholder="Cari nama pasien, nomor RM, atau nomor HP..."
                                       autocomplete="off">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Tabel pasien --}}
                <div class="table-responsive card-table">
                    <table class="table pasien-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>JK</th>
                                <th>No. HP</th>
                                <th>Pengobatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td align="center">{{ $datas->firstItem() + $key }}</td>

                                    <td>
                                        <a href="{{ Route('rekam.detail', $row->id) }}" class="rm-link">
                                            {{ $row->no_rm ?? '-' }}
                                        </a>
                                    </td>

                                    <td>
                                        <div class="patient-name">{{ $row->nama ?? '-' }}</div>
                                    </td>

                                    <td>
                                        <div>{{ $row->tmp_lahir ?? '-' }}</div>
                                        <div class="patient-small" style="white-space: nowrap;">
                                            {{ $row->tgl_lahir ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="alamat-column">
                                        {{ $row->alamat_lengkap ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $row->jk ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $row->no_hp ?? '-' }}
                                    </td>

                                    <td class="pengobatan-column">
                                        <div>{{ $row->cara_bayar ?? '-' }}</div>
                                        @if($row->no_bpjs)
                                            <div class="patient-small">{{ $row->no_bpjs }}</div>
                                        @endif
                                    </td>

                                    <td>
                                        {!! $row->statusPasien() !!}
                                    </td>

                                    <td class="text-center">
                                        <div class="action-wrapper">
                                            <a href="{{ Route('rekam.detail', $row->id) }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Detail Pasien">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <a href="{{ Route('pasien.edit', $row->id) }}"
                                               class="btn btn-info shadow btn-xs sharp"
                                               title="Edit Pasien">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#"
                                               class="btn btn-danger shadow btn-xs sharp delete"
                                               r-link="{{ Route('pasien.delete', $row->id) }}"
                                               r-name="{{ $row->nama }}"
                                               r-id="{{ $row->id }}"
                                               title="Hapus Pasien">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">
                                        <div class="empty-state">
                                            <strong>Data pasien tidak tersedia.</strong>
                                            <br>
                                            <span>Silakan cek kembali data atau gunakan kata kunci pencarian lain.</span>
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

@section('script')
<script>
    $().ready(function () {
        $(".delete").click(function() {
            var name = $(this).attr('r-name');
            var link = $(this).attr('r-link');

            Swal.fire({
                title: 'Ingin Menghapus?',
                text: "Yakin ingin menghapus data : " + name + " ini ?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#007A64',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    window.location = link;
                }
            });
        });
    });
</script>
@endsection