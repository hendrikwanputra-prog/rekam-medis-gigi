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

    .obat-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .obat-toolbar-text {
        flex: 1;
        min-width: 260px;
        color: #7b7f9e;
        padding-bottom: 10px;
    }

    .obat-toolbar-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        max-width: 760px;
        width: 100%;
    }

    .obat-search {
        width: 360px;
        max-width: 100%;
    }

    .btn-add-obat {
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

    .btn-add-obat:hover {
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

    .obat-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .obat-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .obat-table {
        margin-bottom: 0;
    }

    .obat-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .obat-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .obat-table th:first-child,
    .obat-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .obat-table th:nth-child(2),
    .obat-table td:nth-child(2) {
        width: 140px;
    }

    .obat-table th:nth-child(5),
    .obat-table td:nth-child(5) {
        width: 100px;
        text-align: center;
    }

    .obat-table th:nth-child(6),
    .obat-table td:nth-child(6) {
        width: 130px;
        text-align: right;
    }

    .obat-table th:nth-child(7),
    .obat-table td:nth-child(7) {
        width: 100px;
        text-align: center;
    }

    .obat-table th:last-child,
    .obat-table td:last-child {
        width: 120px;
        text-align: center;
    }

    .obat-code {
        color: #1f2b5b;
        font-weight: 800;
    }

    .obat-name {
        color: #111827;
        font-weight: 700;
    }

    .price-text {
        color: #007A64;
        font-weight: 800;
    }

    .badge-bpjs {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-bpjs-yes {
        background: #e9f8f4;
        color: #007A64;
        border: 1px solid #bfeee1;
    }

    .badge-bpjs-no {
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
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

    @media (max-width: 768px) {
        .obat-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .obat-toolbar-action {
            justify-content: flex-start;
            width: 100%;
        }

        .obat-search {
            width: 100%;
            max-width: 100%;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Data Obat</h2>
        <p class="page-subtitle">
            Pengelolaan data obat untuk kebutuhan resep dan pemberian obat pasien.
        </p>
    </div>
</div>

{{-- MODAL TAMBAH OBAT --}}
<div class="modal fade" id="addOrderModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Obat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ Route('obat.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="modal-form-label">Kode Obat*</label>
                        <input type="text"
                               name="kd_obat"
                               required
                               class="form-control"
                               value="{{ old('kd_obat') }}"
                               placeholder="Masukkan kode obat">

                        @error('kd_obat')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Nama Obat*</label>
                        <input type="text"
                               name="nama"
                               required
                               class="form-control"
                               value="{{ old('nama') }}"
                               placeholder="Masukkan nama obat">

                        @error('nama')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="modal-form-label">Satuan*</label>
                                <input type="text"
                                       name="satuan"
                                       required
                                       class="form-control"
                                       value="{{ old('satuan') }}"
                                       placeholder="Contoh: Tablet">

                                @error('satuan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="modal-form-label">Stok*</label>
                                <input type="number"
                                       name="stok"
                                       required
                                       class="form-control"
                                       value="{{ old('stok') }}"
                                       placeholder="0">

                                @error('stok')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="modal-form-label">Harga*</label>
                                <input type="number"
                                       name="harga"
                                       required
                                       class="form-control"
                                       value="{{ old('harga') ? old('harga') : '0' }}"
                                       placeholder="0">

                                @error('harga')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="modal-form-label">Untuk BPJS*</label>
                                <select name="is_bpjs" class="form-control">
                                    <option value="1" {{ old('is_bpjs') == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ old('is_bpjs', '0') == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>

                                @error('is_bpjs')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan Obat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- KONTEN DATA OBAT --}}
<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="obat-toolbar">
                    <div class="obat-toolbar-text">
                        <span>
                            Total data obat: <strong>{{ $datas->total() }}</strong> data.
                        </span>
                    </div>

                    <div class="obat-toolbar-action">
                        <a href="javascript:void(0)"
                           class="btn btn-add-obat"
                           data-toggle="modal"
                           data-target="#addOrderModal">
                            + Tambah Obat
                        </a>

                        <div class="obat-search">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari kode obat atau nama obat..."
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

                <div class="table-responsive card-table">
                    <table class="table obat-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>BPJS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $datas->firstItem() + $key }}</td>

                                    <td>
                                        <span class="obat-code">{{ $row->kd_obat ?? '-' }}</span>
                                    </td>

                                    <td>
                                        <span class="obat-name">{{ $row->nama ?? '-' }}</span>
                                    </td>

                                    <td>{{ $row->satuan ?? '-' }}</td>

                                    <td>{{ $row->stok ?? 0 }}</td>

                                    <td>
                                        <span class="price-text">
                                            Rp {{ number_format($row->harga ?? 0, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($row->is_bpjs == 1)
                                            <span class="badge-bpjs badge-bpjs-yes">Ya</span>
                                        @else
                                            <span class="badge-bpjs badge-bpjs-no">Tidak</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="action-wrapper">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#editObat{{ $row->id }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Edit Obat">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#"
                                               class="btn btn-danger shadow btn-xs sharp delete"
                                               r-link="{{ Route('obat.delete', $row->id) }}"
                                               r-name="{{ $row->nama }}"
                                               r-id="{{ $row->id }}"
                                               title="Hapus Obat">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                        {{-- MODAL EDIT OBAT --}}
                                        <div class="modal fade" id="editObat{{ $row->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Obat</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('obat.update', $row->id) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Kode Obat*</label>
                                                                <input type="text"
                                                                       name="kd_obat"
                                                                       required
                                                                       class="form-control"
                                                                       readonly
                                                                       value="{{ old('kd_obat') ? old('kd_obat') : $row->kd_obat }}">

                                                                @error('kd_obat')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama Obat*</label>
                                                                <input type="text"
                                                                       name="nama"
                                                                       required
                                                                       class="form-control"
                                                                       value="{{ old('nama') ? old('nama') : $row->nama }}">

                                                                @error('nama')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="modal-form-label">Satuan*</label>
                                                                        <input type="text"
                                                                               name="satuan"
                                                                               required
                                                                               class="form-control"
                                                                               value="{{ old('satuan') ? old('satuan') : $row->satuan }}">

                                                                        @error('satuan')
                                                                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="modal-form-label">Stok*</label>
                                                                        <input type="number"
                                                                               name="stok"
                                                                               required
                                                                               class="form-control"
                                                                               value="{{ old('stok') ? old('stok') : $row->stok }}">

                                                                        @error('stok')
                                                                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="modal-form-label">Harga*</label>
                                                                        <input type="number"
                                                                               name="harga"
                                                                               required
                                                                               class="form-control"
                                                                               value="{{ old('harga') ? old('harga') : $row->harga }}">

                                                                        @error('harga')
                                                                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="modal-form-label">Untuk BPJS*</label>
                                                                        <select name="is_bpjs" class="form-control">
                                                                            <option value="1" {{ $row->is_bpjs == 1 ? 'selected' : '' }}>Ya</option>
                                                                            <option value="0" {{ $row->is_bpjs == 0 ? 'selected' : '' }}>Tidak</option>
                                                                        </select>

                                                                        @error('is_bpjs')
                                                                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                                {{ $message }}
                                                                            </div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer-custom">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                                                    Batal
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Update Obat
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
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <strong>Data obat tidak tersedia.</strong>
                                            <br>
                                            <span>Silakan tambahkan data obat terlebih dahulu.</span>
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