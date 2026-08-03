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

    .pengguna-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .pengguna-toolbar-text {
        color: #7b7f9e;
        font-size: 14px;
    }

    .pengguna-toolbar-text strong {
        color: #007A64;
        font-weight: 700;
    }

    .pengguna-toolbar-action {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pengguna-search {
        width: 360px;
        max-width: 100%;
    }

    .pengguna-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .pengguna-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .btn-add-pengguna {
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

    .btn-add-pengguna:hover {
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

    .pengguna-table {
        margin-bottom: 0;
    }

    .pengguna-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .pengguna-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .pengguna-table th:first-child,
    .pengguna-table td:first-child {
        width: 70px;
        text-align: center;
    }

    .pengguna-name {
        color: #111827;
        font-weight: 700;
    }

    .pengguna-phone {
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
        .pengguna-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .pengguna-toolbar-action {
            width: 100%;
            justify-content: flex-start;
        }

        .pengguna-search {
            width: 100%;
        }

        .pengguna-table th,
        .pengguna-table td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-title">Data Pengguna</h2>
        <p class="page-subtitle">
            Pengelolaan data pengguna sistem OQ Clinic Dentist.
        </p>
    </div>
</div>

{{-- MODAL TAMBAH PENGGUNA --}}
<div class="modal fade" id="addPetugasModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ Route('petugas.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label class="modal-form-label">Nama*</label>
                        <input type="text"
                               name="name"
                               required
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama pengguna">

                        @error('name')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">No. HP Login*</label>
                        <input type="text"
                               name="phone"
                               required
                               class="form-control"
                               value="{{ old('phone') }}"
                               placeholder="Contoh: 081234567890">

                        @error('phone')
                            <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="modal-form-label">Role*</label>
                        <select name="role" class="form-control" required>
                            <option value="">Pilih role</option>
                            <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Pendaftaran</option>
                            <option value="4" {{ old('role') == '4' ? 'selected' : '' }}>Kasir / Petugas</option>
                        </select>

                        @error('role')
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

                    <div class="modal-footer-custom">
                        <button type="button" class="btn btn-light" data-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Simpan Pengguna
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

                <div class="pengguna-toolbar">
                    <div class="pengguna-toolbar-text">
                        Total data pengguna:
                        <strong>{{ method_exists($datas, 'total') ? $datas->total() : $datas->count() }}</strong>
                        data.
                    </div>

                    <div class="pengguna-toolbar-action">
                        <a href="javascript:void(0)"
                           class="btn btn-add-pengguna"
                           data-toggle="modal"
                           data-target="#addPetugasModal">
                            + Tambah Pengguna
                        </a>

                        <div class="pengguna-search">
                            <form method="get" action="{{ url()->current() }}">
                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari nama atau no HP..."
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
                    <table class="table pengguna-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($datas as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        <span class="pengguna-name">
                                            {{ $row->name ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="pengguna-phone">
                                            {{ $row->phone ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        @if(method_exists($row, 'role_display'))
                                            {{ $row->role_display() }}
                                        @else
                                            @if($row->role == 1)
                                                Admin
                                            @elseif($row->role == 2)
                                                Pendaftaran
                                            @elseif($row->role == 4)
                                                Kasir / Petugas
                                            @else
                                                -
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        @if(method_exists($row, 'status_display'))
                                            {!! $row->status_display() !!}
                                        @else
                                            {{ $row->status == 1 ? 'Aktif' : 'Nonaktif' }}
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <div class="action-wrapper">
                                            <a href="javascript:void(0)"
                                               data-toggle="modal"
                                               data-target="#editPetugas{{ $row->id }}"
                                               class="btn btn-primary shadow btn-xs sharp"
                                               title="Edit Pengguna">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="#"
                                               class="btn btn-danger shadow btn-xs sharp delete"
                                               r-link="{{ Route('petugas.delete', $row->id) }}"
                                               r-name="{{ $row->name }}"
                                               r-id="{{ $row->id }}"
                                               title="Hapus Pengguna">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>

                                        {{-- MODAL EDIT PENGGUNA --}}
                                        <div class="modal fade" id="editPetugas{{ $row->id }}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Pengguna</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body text-left">
                                                        <form action="{{ Route('petugas.update', $row->id) }}" method="POST">
                                                            {{ csrf_field() }}

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Nama*</label>
                                                                <input type="text"
                                                                       name="name"
                                                                       value="{{ $row->name }}"
                                                                       required
                                                                       class="form-control">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">No. HP Login*</label>
                                                                <input type="text"
                                                                       name="phone"
                                                                       required
                                                                       class="form-control"
                                                                       value="{{ $row->phone }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="modal-form-label">Role*</label>
                                                                <select name="role" class="form-control" required>
                                                                    <option value="1" {{ $row->role == 1 ? 'selected' : '' }}>Admin</option>
                                                                    <option value="2" {{ $row->role == 2 ? 'selected' : '' }}>Pendaftaran</option>
                                                                    <option value="4" {{ $row->role == 4 ? 'selected' : '' }}>Kasir / Petugas</option>
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer-custom">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">
                                                                    Batal
                                                                </button>

                                                                <button type="submit" class="btn btn-primary">
                                                                    Update Pengguna
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
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <strong>Data pengguna belum tersedia.</strong>
                                            <br>
                                            <span>Silakan tambahkan data pengguna terlebih dahulu.</span>
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
        $(".delete").click(function(e) {
            e.preventDefault();

            var id = $(this).attr('r-id');
            var name = $(this).attr('r-name');
            var link = $(this).attr('r-link');

            Swal.fire({
                title: 'Ingin Menghapus?',
                text: "Yakin ingin menghapus data pengguna : " + name + " ini ?",
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