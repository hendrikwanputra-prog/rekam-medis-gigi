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

    .tindakan-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .tindakan-toolbar-text {
        flex: 1;
        min-width: 260px;
        color: #7b7f9e;
        padding-bottom: 10px;
    }

    .tindakan-toolbar-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        max-width: 760px;
        width: 100%;
    }

    .tindakan-search {
        width: 360px;
        max-width: 100%;
    }

    .btn-add-tindakan {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        padding: 11px 20px;
        border-radius: 6px;
        font-weight: 700;
        height: 44px;
        display: inline-flex;
        align-items: center;
    }

    .btn-add-tindakan:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
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

    .tindakan-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
        color: #333;
    }

    .tindakan-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .tindakan-table {
        margin-bottom: 0;
    }

    .tindakan-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .tindakan-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .tindakan-table th:first-child,
    .tindakan-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .tindakan-table th:nth-child(2),
    .tindakan-table td:nth-child(2) {
        width: 140px;
    }

    .tindakan-table th:nth-child(4),
    .tindakan-table td:nth-child(4) {
        width: 160px;
        text-align: right;
    }

    .tindakan-table th:last-child,
    .tindakan-table td:last-child {
        width: 110px;
        text-align: center;
    }

    .tindakan-code {
        color: #1f2b5b;
        font-weight: 700;
    }

    .tindakan-name {
        color: #111827;
        font-weight: 700;
    }

    .price-text {
        color: #333;
        font-weight: 600;
    }

    .action-wrapper {
        display: flex;
        justify-content: center;
        gap: 6px;
        flex-wrap: nowrap;
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

    .modal-title {
        color: #1f2b5b;
        font-weight: 700;
    }

    .modal-form-label {
        color: #374151;
        font-weight: 700;
        font-size: 13px;
    }

    .modal-footer-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .pagination-wrapper {
        margin-top: 18px;
    }

    @media (max-width: 768px) {
        .tindakan-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .tindakan-toolbar-action {
            justify-content: flex-start;
            width: 100%;
        }

        .tindakan-search {
            width: 100%;
            max-width: 100%;
        }

        .tindakan-table th,
        .tindakan-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Data Tindakan</h2>
        <p class="page-subtitle">
            Pengelolaan data tindakan dan perawatan pada OQ Clinic Dentist.
        </p>
    </div>
</div>

{{-- MODAL TAMBAH TINDAKAN --}}
<div class="modal fade" id="addOrderModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Tindakan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ Route('tindakan.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="modal-form-label">Kode*</label>
                        <input type="text"
                               name="kode"
                               class="form-control"
                               value="{{ old('kode') }}"
                               placeholder="Contoh: T001"
                               required>

                        @error('kode')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Nama Tindakan*</label>
                        <input type="text"
                               name="nama"
                               required
                               class="form-control"
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama tindakan">

                        @error('nama')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Harga*</label>
                        <input type="number"
                               name="harga"
                               value="{{ old('harga') ? old('harga') : '0' }}"
                               class="form-control"
                               required
                               placeholder="0">

                        @error('harga')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Poli tetap dikirim agar database/controller aman, tapi tidak ditampilkan --}}
                    <input type="hidden" name="poli" value="Poli Gigi">

                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan Tindakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- KONTEN DATA TINDAKAN --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="tindakan-toolbar">
                    <div class="tindakan-toolbar-text">
                        <span>
                            Total data tindakan:
                            <strong>{{ method_exists($datas, 'total') ? $datas->total() : $datas->count() }}</strong>
                            data.
                        </span>
                    </div>

                    <div class="tindakan-toolbar-action">
                        <a href="javascript:void(0)"
                           class="btn btn-add-tindakan"
                           data-toggle="modal"
                           data-target="#addOrderModal">
                            + Tambah Tindakan
                        </a>

                        <div class="tindakan-search">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari kode atau nama tindakan..."
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
                </div>

                <div class="table-responsive">
                    <table class="table tindakan-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Tindakan</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ method_exists($datas, 'firstItem') ? $datas->firstItem() + $key : $key + 1 }}</td>

                                    <td>
                                        <span class="tindakan-code">
                                            {{ $row->kode ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="tindakan-name">
                                            {{ $row->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="price-text">
                                            Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-wrapper">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#edit{{ $row->id }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Edit Tindakan">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        </div>

                                        {{-- MODAL EDIT TINDAKAN --}}
                                        <div class="modal fade" id="edit{{ $row->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Tindakan</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('master.tindakan.update', $row->id) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Kode</label>
                                                                <input type="text"
                                                                       name="kode"
                                                                       value="{{ $row->kode }}"
                                                                       readonly
                                                                       class="form-control">

                                                                @error('kode')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama Tindakan</label>
                                                                <input type="text"
                                                                       name="nama"
                                                                       value="{{ $row->nama }}"
                                                                       required
                                                                       class="form-control">

                                                                @error('nama')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Harga</label>
                                                                <input type="number"
                                                                       name="harga"
                                                                       value="{{ $row->harga }}"
                                                                       required
                                                                       class="form-control">

                                                                @error('harga')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            {{-- Poli tetap dikirim agar update aman --}}
                                                            <input type="hidden" name="poli" value="{{ $row->poli ?? 'Poli Gigi' }}">

                                                            <div class="modal-footer-custom">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                                                    Batal
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Update Tindakan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <strong>Data tindakan belum tersedia.</strong>
                                            <br>
                                            <span>Silakan tambahkan data tindakan terlebih dahulu.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(method_exists($datas, 'links'))
                        <div class="pagination-wrapper">
                            {{ $datas->appends(request()->except('page'))->links() }}
                        </div>
                    @endif
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