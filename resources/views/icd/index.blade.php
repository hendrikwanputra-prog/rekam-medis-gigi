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

    .icd-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .icd-toolbar-text {
        color: #7b7f9e;
        font-size: 14px;
    }

    .icd-toolbar-text strong {
        color: #007A64;
        font-weight: 700;
    }

    .icd-toolbar-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .icd-search {
        width: 360px;
        max-width: 100%;
    }

    .icd-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .icd-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .btn-add-icd {
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

    .btn-add-icd:hover {
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

    .icd-table {
        margin-bottom: 0;
    }

    .icd-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .icd-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .icd-table th:first-child,
    .icd-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .icd-code {
        color: #1f2b5b;
        font-weight: 700;
    }

    .icd-name {
        color: #111827;
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
        .icd-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .icd-toolbar-action {
            width: 100%;
            justify-content: flex-start;
        }

        .icd-search {
            width: 100%;
        }

        .icd-table th,
        .icd-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Data ICD</h2>
        <p class="page-subtitle">
            Pengelolaan data ICD atau diagnosa pada OQ Clinic Dentist.
        </p>
    </div>
</div>

{{-- MODAL TAMBAH ICD --}}
<div class="modal fade" id="addOrderModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah ICD</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ Route('icd.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="modal-form-label">Kode ICD*</label>
                        <input type="text"
                               name="code"
                               required
                               class="form-control"
                               value="{{ old('code') }}"
                               placeholder="Contoh: K02">

                        @error('code')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Nama Diagnosa Indonesia*</label>
                        <input type="text"
                               name="name_id"
                               required
                               class="form-control"
                               value="{{ old('name_id') }}"
                               placeholder="Contoh: Karies gigi">

                        @error('name_id')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Nama Diagnosa English*</label>
                        <input type="text"
                               name="name_en"
                               required
                               class="form-control"
                               value="{{ old('name_en') }}"
                               placeholder="Contoh: Dental caries">

                        @error('name_en')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan ICD
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="icd-toolbar">
                    <div class="icd-toolbar-text">
                        Total data ICD:
                        <strong>{{ $datas->total() }}</strong>
                        data.
                    </div>

                    <div class="icd-toolbar-action">
                        <a href="javascript:void(0)"
                           class="btn btn-add-icd"
                           data-toggle="modal"
                           data-target="#addOrderModal">
                            + Tambah ICD
                        </a>

                        <div class="icd-search">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari kode atau nama diagnosa..."
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
                    <table class="table icd-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Diagnosa (Indonesia)</th>
                                <th>Nama Diagnosa (English)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $datas->firstItem() + $key }}</td>

                                    <td>
                                        <span class="icd-code">
                                            {{ $row->code }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="icd-name">
                                            {{ $row->name_id }}
                                        </span>
                                    </td>

                                    <td>{{ $row->name_en }}</td>

                                    <td class="text-center">
                                        <div class="action-wrapper">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#edit{{ $row->code }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Edit ICD">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#"
                                               class="btn btn-danger shadow btn-xs sharp delete"
                                               r-link="{{ Route('icd.delete', $row->code) }}"
                                               r-name="{{ $row->name_id }}"
                                               r-id="{{ $row->code }}"
                                               title="Hapus ICD">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                        {{-- MODAL EDIT ICD --}}
                                        <div class="modal fade" id="edit{{ $row->code }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit ICD</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('icd.update', $row->code) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Kode ICD*</label>
                                                                <input type="text"
                                                                       name="code"
                                                                       value="{{ $row->code }}"
                                                                       required
                                                                       class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama Diagnosa Indonesia*</label>
                                                                <input type="text"
                                                                       name="name_id"
                                                                       value="{{ $row->name_id }}"
                                                                       required
                                                                       class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama Diagnosa English*</label>
                                                                <input type="text"
                                                                       name="name_en"
                                                                       value="{{ $row->name_en }}"
                                                                       required
                                                                       class="form-control">
                                                            </div>

                                                            <div class="modal-footer-custom">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                                                    Batal
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Update ICD
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
                                            <strong>Data ICD belum tersedia.</strong>
                                            <br>
                                            <span>Silakan tambahkan data ICD terlebih dahulu.</span>
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
            var id = $(this).attr('r-id');
            var name = $(this).attr('r-name');
            var link = $(this).attr('r-link');

            Swal.fire({
                title: 'Ingin Menghapus?',
                text: "Yakin ingin menghapus data ICD : " + name + " ini ?",
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