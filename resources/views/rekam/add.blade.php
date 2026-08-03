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

    .section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2b5b;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf0f5;
    }

    .form-label-custom {
        font-weight: 600;
        color: #374151;
    }

    .info-box {
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #9a3412;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .info-box a {
        color: #007A64;
        font-weight: 700;
    }

    .btn-submit-rekam {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        padding: 11px 24px;
        border-radius: 6px;
        font-weight: 700;
    }

    .btn-submit-rekam:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .btn-back-rekam {
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: 600;
    }

    .modal-title {
        font-weight: 700;
        color: #1f2b5b;
    }

    .form-control {
        min-height: 44px;
    }

    textarea.form-control {
        min-height: 120px;
    }

    .input-group-text {
        min-height: 44px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .info-box {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-header-title">Tambah Rekam Medis</h2>
        <p class="page-header-subtitle">
            Tambahkan data pemeriksaan awal pasien sebelum diteruskan kepada dokter.
        </p>
        <ol class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="{{ Route('rekam') }}">Rekam Medis</a></li>
            <li class="breadcrumb-item active"><a href="#">Tambah Rekam Medis</a></li>
        </ol>
    </div>
</div>

{{-- Modal Pencarian Pasien --}}
<div class="modal fade" id="modalPasien">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Data Pasien</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive card-table">
                    <table class="display white-border table-responsive-sm"
                           style="width: 100%"
                           id="pasien-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Tgl Lahir</th>
                                <th>No. HP</th>
                                <th>Cara Bayar</th>
                                <th>No BPJS/KTP</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                <div class="section-title">
                    Informasi Pemeriksaan Pasien
                </div>

                <div class="basic-form">
                    <form action="{{ Route('rekam.store') }}" method="POST">
                        {{ csrf_field() }}

                        {{-- Poli di-hide karena sistem fokus pada rekam medis klinik gigi --}}
                        <input type="hidden" name="poli" id="poli" value="Poli Gigi">

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Tanggal Periksa*</label>
                            <div class="col-sm-4">
                                <input type="date"
                                       name="tgl_rekam"
                                       class="form-control"
                                       value="{{ old('tgl_rekam') ? old('tgl_rekam') : date('Y-m-d') }}"
                                       required>

                                @error('tgl_rekam')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Nama Pasien*</label>
                            <div class="col-sm-5">
                                <input type="hidden"
                                       class="form-control"
                                       id="pasien_id"
                                       name="pasien_id"
                                       value="{{ old('pasien_id') }}">

                                <div class="input-group transparent-append">
                                    <input type="text"
                                           id="pasien_nama"
                                           class="form-control"
                                           data-toggle="modal"
                                           data-target="#modalPasien"
                                           value="{{ old('pasien_nama') ? old('pasien_nama') : '' }}"
                                           name="pasien_nama"
                                           placeholder="Pilih pasien..."
                                           readonly>

                                    <div class="input-group-append show-pass" data-toggle="modal" data-target="#modalPasien">
                                        <span class="input-group-text">
                                            <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPasien">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>

                                @error('pasien_id')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Cara Bayar*</label>
                            <div class="col-sm-3">
                                <select name="cara_bayar" id="cara_bayar" required class="form-control">
                                    <option value="">Pilih cara bayar</option>
                                    <option value="Umum/Mandiri" {{ old('cara_bayar') == "Umum/Mandiri" ? 'selected' : '' }}>
                                        Umum/Mandiri
                                    </option>
                                    <option value="Jaminan Kesehatan" {{ old('cara_bayar') == "Jaminan Kesehatan" ? 'selected' : '' }}>
                                        Jaminan Kesehatan
                                    </option>
                                </select>

                                @error('cara_bayar')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label"></label>
                            <div class="col-sm-10">
                                <div class="info-box">
                                    <div>
                                        <i class="mdi mdi-help-circle-outline mr-2"></i>
                                        Pasien belum terdaftar? Silakan tambah data pasien terlebih dahulu.
                                    </div>
                                    <a href="{{ Route('pasien.add') }}">Tambah Pasien</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Anamnesa / Keluhan*</label>
                            <div class="col-sm-10">
                                <textarea name="keluhan"
                                          required
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan keluhan awal pasien...">{{ old('keluhan') }}</textarea>

                                @error('keluhan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Pilih Dokter*</label>
                            <div class="col-sm-10">
                                <select name="dokter_id" id="dokter_id" class="form-control" required>
                                    <option value="">Memuat data dokter...</option>
                                </select>

                                @error('dokter_id')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="form-group d-flex justify-content-between align-items-center">
                            <a href="{{ Route('rekam') }}" class="btn btn-light btn-back-rekam">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-submit-rekam">
                                Simpan Rekam Medis
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(function () {
        $('#pasien-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            select: false,
            pageLength: 5,
            lengthChange: false,
            ajax: "{{ route('pasien.json') }}",
            columns: [
                {data: 'action', name: 'action'},
                {data: 'no_rm', name: 'no_rm'},
                {data: 'nama', name: 'nama'},
                {data: 'tgl_lahir', name: 'tgl_lahir'},
                {data: 'no_hp', name: 'no_hp'},
                {data: 'cara_bayar', name: 'cara_bayar'},
                {data: 'no_bpjs', name: 'no_bpjs'},
            ]
        });
    });

    $(document).ready(function() {
        loadDokterGigi();

        function loadDokterGigi() {
            var poli = "Poli Gigi";
            var selectedDokter = "{{ old('dokter_id') }}";

            $.get(
                "{{ route('getDokter') }}",
                {
                    poli: poli
                },
                function(data) {
                    var string = '<option value="">Pilih dokter</option>';

                    if (data.data && data.data.length > 0) {
                        $.each(data.data, function(index, value) {
                            var selected = selectedDokter == value.id ? 'selected' : '';
                            string = string + `<option value="` + value.id + `" ` + selected + `>` + value.nama + `</option>`;
                        });
                    } else {
                        string = '<option value="">Dokter belum tersedia</option>';
                    }

                    $("#dokter_id").html(string);
                }
            ).fail(function() {
                $("#dokter_id").html('<option value="">Data dokter tidak tersedia</option>');
            });
        }
    });

    $(document).on("click", ".pilihPasien", function () {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var metode = $(this).data('metode');

        $("#pasien_nama").val(nama);
        $("#pasien_id").val(id);
        $("#cara_bayar").val(metode).change();

        $("#modalPasien").modal('hide');

        toastr.success("Pasien " + nama + " telah dipilih", "Sukses", {timeOut: 3000});
    });
</script>
@endsection