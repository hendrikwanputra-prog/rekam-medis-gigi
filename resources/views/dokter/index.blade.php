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

    .dokter-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .dokter-toolbar-text {
        color: #7b7f9e;
        font-size: 14px;
    }

    .dokter-toolbar-text strong {
        color: #007A64;
        font-weight: 700;
    }

    .dokter-toolbar-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .dokter-search {
        width: 360px;
        max-width: 100%;
    }

    .dokter-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .dokter-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .btn-add-dokter {
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

    .btn-add-dokter:hover {
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

    .dokter-table {
        margin-bottom: 0;
    }

    .dokter-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .dokter-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .dokter-table th:first-child,
    .dokter-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .dokter-name {
        color: #111827;
        font-weight: 700;
    }

    .dokter-nip {
        color: #1f2b5b;
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

    @media (max-width: 768px) {
        .dokter-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .dokter-toolbar-action {
            width: 100%;
            justify-content: flex-start;
        }

        .dokter-search {
            width: 100%;
        }

        .dokter-table th,
        .dokter-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Data Dokter</h2>
        <p class="page-subtitle">
            Pengelolaan data dokter pada OQ Clinic Dentist.
        </p>
    </div>
</div>

{{-- MODAL TAMBAH DOKTER --}}
<div class="modal fade" id="addOrderModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Dokter</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ Route('dokter.store') }}" method="POST">
                    {{ csrf_field() }}

                    {{-- Poli di-hide karena sistem fokus pada rekam medis klinik gigi --}}
                    <input type="hidden" name="poli" value="Poli Gigi">

                    <div class="form-group">
                        <label class="modal-form-label">NIP</label>
                        <input type="text"
                               name="nip"
                               class="form-control"
                               value="{{ old('nip') }}"
                               placeholder="Masukkan NIP dokter">

                        @error('nip')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Nama Dokter*</label>
                        <input type="text"
                               name="nama"
                               required
                               class="form-control"
                               value="{{ old('nama') }}"
                               placeholder="Contoh: Drg. Hendrik">

                        @error('nama')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">No HP Login*</label>
                        <input type="text"
                               name="no_hp"
                               required
                               class="form-control"
                               value="{{ old('no_hp') }}"
                               placeholder="Contoh: 081234567890">

                        @error('no_hp')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Password Login*</label>
                        <input type="password"
                               name="password"
                               required
                               class="form-control"
                               placeholder="Minimal 6 karakter">

                        @error('password')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Alamat</label>
                        <textarea name="alamat"
                                  class="form-control"
                                  cols="30"
                                  rows="3"
                                  placeholder="Masukkan alamat dokter">{{ old('alamat') }}</textarea>

                        @error('alamat')
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
                            Simpan Dokter
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

                <div class="dokter-toolbar">
                    <div class="dokter-toolbar-text">
                        Total data dokter:
                        <strong>{{ method_exists($datas, 'total') ? $datas->total() : $datas->count() }}</strong>
                        data.
                    </div>

                    <div class="dokter-toolbar-action">
                        <a href="javascript:void(0)"
                           class="btn btn-add-dokter"
                           data-toggle="modal"
                           data-target="#addOrderModal">
                            + Tambah Dokter
                        </a>

                        <div class="dokter-search">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari nama dokter atau no HP..."
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
                    <table class="table dokter-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIP</th>
                                <th>Nama Dokter</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="dokter-nip">
                                            {{ $row->nip ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="dokter-name">
                                            {{ $row->nama ?? '-' }}
                                        </span>
                                    </td>

                                    <td>{{ $row->no_hp ?? '-' }}</td>
                                    <td>{{ $row->alamat ?? '-' }}</td>
                                    <td>{!! $row->status_display() !!}</td>

                                    <td class="text-center">
                                        <div class="action-wrapper">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#key{{ $row->user_id }}"
                                               class="btn btn-warning shadow btn-xs sharp"
                                               title="Ganti Password">
                                                <i class="fa fa-key"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#edit{{ $row->id }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Edit Dokter">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#"
                                               class="btn btn-danger shadow btn-xs sharp delete"
                                               r-link="{{ Route('dokter.delete', $row->id) }}"
                                               r-name="{{ $row->nama }}"
                                               r-id="{{ $row->id }}"
                                               title="Hapus Dokter">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                        {{-- MODAL GANTI PASSWORD --}}
                                        <div class="modal fade" id="key{{ $row->user_id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Ganti Password Login Dokter</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('dokter.gantipassword', $row->user_id) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Password Baru*</label>
                                                                <input type="password"
                                                                       name="password"
                                                                       required
                                                                       class="form-control"
                                                                       placeholder="Masukkan password baru">

                                                                @error('password')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Konfirmasi Password*</label>
                                                                <input type="password"
                                                                       name="password_konfirm"
                                                                       required
                                                                       class="form-control"
                                                                       placeholder="Ulangi password baru">

                                                                @error('password_konfirm')
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
                                                                    Ganti Password
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- MODAL EDIT DOKTER --}}
                                        <div class="modal fade" id="edit{{ $row->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Dokter</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('dokter.update', $row->id) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            {{-- Poli di-hide karena sistem fokus pada rekam medis klinik gigi --}}
                                                            <input type="hidden" name="poli" value="Poli Gigi">

                                                            <div class="form-group">
                                                                <label class="modal-form-label">NIP</label>
                                                                <input type="text"
                                                                       name="nip"
                                                                       class="form-control"
                                                                       value="{{ $row->nip }}"
                                                                       placeholder="Masukkan NIP dokter">

                                                                @error('nip')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama Dokter*</label>
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
                                                                <label class="modal-form-label">No HP Login*</label>
                                                                <input type="text"
                                                                       name="no_hp"
                                                                       required
                                                                       class="form-control"
                                                                       value="{{ $row->no_hp }}">

                                                                @error('no_hp')
                                                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Alamat</label>
                                                                <textarea name="alamat"
                                                                          class="form-control"
                                                                          cols="30"
                                                                          rows="3"
                                                                          placeholder="Masukkan alamat dokter">{{ $row->alamat }}</textarea>

                                                                @error('alamat')
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
                                                                    Update Dokter
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
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <strong>Data dokter belum tersedia.</strong>
                                            <br>
                                            <span>Silakan tambahkan data dokter terlebih dahulu.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
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
                text: "Yakin ingin menghapus data dokter : " + name + " ini ?",
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