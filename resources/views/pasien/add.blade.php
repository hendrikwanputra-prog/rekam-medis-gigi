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

    .form-control {
        min-height: 44px;
    }

    textarea.form-control {
        min-height: 90px;
    }

    .radio-group {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
        align-items: center;
        min-height: 44px;
    }

    .btn-submit-pasien {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        padding: 11px 24px;
        border-radius: 6px;
        font-weight: 700;
    }

    .btn-submit-pasien:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .btn-back-pasien {
        padding: 11px 22px;
        border-radius: 6px;
        font-weight: 600;
    }

    .file-help-text {
        color: #7b7f9e;
        font-size: 12px;
        margin-top: 6px;
    }
</style>

<div class="form-head align-items-center d-flex mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="page-header-title">Tambah Pasien</h2>
        <p class="page-header-subtitle">
            Tambahkan data identitas pasien baru sebelum dibuatkan rekam medis.
        </p>
        <ol class="breadcrumb mt-2">
            <li class="breadcrumb-item"><a href="{{ Route('pasien') }}">Data Pasien</a></li>
            <li class="breadcrumb-item active"><a href="#">Tambah Data Pasien</a></li>
        </ol>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ Route('pasien.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="section-title">
                            Data Identitas Pasien
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Nama Pasien*</label>
                            <div class="col-sm-10">
                                <input type="text"
                                       class="form-control"
                                       name="nama"
                                       id="nama"
                                       required
                                       value="{{ old('nama') }}"
                                       placeholder="Masukkan nama lengkap pasien">

                                @error('nama')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">No. RM*</label>
                            <div class="col-sm-2">
                                <select name="code" class="form-control" id="code">
                                    <option value="D">Dewasa</option>
                                    <option value="A">Anak</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control"
                                       name="no_rm"
                                       required
                                       id="no_rm"
                                       value="{{ old('no_rm') }}"
                                       placeholder="Nomor RM otomatis">

                                @error('no_rm')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Tempat Lahir</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control"
                                       name="tmp_lahir"
                                       value="{{ old('tmp_lahir') }}"
                                       placeholder="Masukkan tempat lahir">

                                @error('tmp_lahir')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Tanggal Lahir</label>
                            <div class="col-sm-4">
                                <input type="date"
                                       class="form-control"
                                       name="tgl_lahir"
                                       value="{{ old('tgl_lahir') }}">

                                @error('tgl_lahir')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Jenis Kelamin*</label>
                            <div class="col-sm-4">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input type="radio"
                                               name="jk"
                                               class="form-check-input"
                                               value="Laki-Laki"
                                               {{ old('jk') == 'Laki-Laki' ? 'checked' : '' }}>
                                        <label class="form-check-label">Laki-Laki</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio"
                                               name="jk"
                                               class="form-check-input"
                                               value="Perempuan"
                                               {{ old('jk') == 'Perempuan' ? 'checked' : '' }}>
                                        <label class="form-check-label">Perempuan</label>
                                    </div>
                                </div>

                                @error('jk')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Status Menikah</label>
                            <div class="col-sm-4">
                                <select name="status_menikah" class="form-control" required>
                                    <option value="">Pilih status menikah</option>
                                    <option value="Belum Menikah" {{ old('status_menikah') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                    <option value="Menikah" {{ old('status_menikah') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                    <option value="Duda" {{ old('status_menikah') == 'Duda' ? 'selected' : '' }}>Duda</option>
                                    <option value="Janda" {{ old('status_menikah') == 'Janda' ? 'selected' : '' }}>Janda</option>
                                </select>

                                @error('status_menikah')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Agama</label>
                            <div class="col-sm-2">
                                <select name="agama" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katholik" {{ old('agama') == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ old('agama') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>

                                @error('agama')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Pendidikan</label>
                            <div class="col-sm-2">
                                <select name="pendidikan" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="SD" {{ old('pendidikan') == 'SD' ? 'selected' : '' }}>SD</option>
                                    <option value="SMP" {{ old('pendidikan') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                    <option value="SMA" {{ old('pendidikan') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="Diploma" {{ old('pendidikan') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="S1" {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                                    <option value="Tidak Sekolah" {{ old('pendidikan') == 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                </select>

                                @error('pendidikan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Pekerjaan</label>
                            <div class="col-sm-2">
                                <select name="pekerjaan" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="PNS" {{ old('pekerjaan') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                    <option value="Wiraswasta" {{ old('pekerjaan') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="TNI/Polri" {{ old('pekerjaan') == 'TNI/Polri' ? 'selected' : '' }}>TNI/Polri</option>
                                    <option value="Pelajar/Mahasiswa" {{ old('pekerjaan') == 'Pelajar/Mahasiswa' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                                    <option value="Petani" {{ old('pekerjaan') == 'Petani' ? 'selected' : '' }}>Petani</option>
                                    <option value="Guru/Pengajar" {{ old('pekerjaan') == 'Guru/Pengajar' ? 'selected' : '' }}>Guru/Pengajar</option>
                                    <option value="IRT" {{ old('pekerjaan') == 'IRT' ? 'selected' : '' }}>IRT</option>
                                    <option value="Lain-Lain" {{ old('pekerjaan') == 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
                                </select>

                                @error('pekerjaan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            Data Alamat Pasien
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Alamat Lengkap</label>
                            <div class="col-sm-10">
                                <textarea name="alamat_lengkap"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Masukkan alamat lengkap pasien">{{ old('alamat_lengkap') }}</textarea>

                                @error('alamat_lengkap')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Kelurahan</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control"
                                       name="kelurahan"
                                       value="{{ old('kelurahan') }}"
                                       placeholder="Masukkan kelurahan">

                                @error('kelurahan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Kecamatan</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control"
                                       name="kecamatan"
                                       value="{{ old('kecamatan') }}"
                                       placeholder="Masukkan kecamatan">

                                @error('kecamatan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Kabupaten</label>
                            <div class="col-sm-4">
                                <input type="text"
                                       class="form-control"
                                       name="kabupaten"
                                       value="{{ old('kabupaten') }}"
                                       placeholder="Masukkan kabupaten/kota">

                                @error('kabupaten')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom">Kode Pos</label>
                            <div class="col-sm-4">
                                <input type="number"
                                       maxlength="5"
                                       class="form-control"
                                       name="kodepos"
                                       value="{{ old('kodepos') }}"
                                       placeholder="Masukkan kode pos">

                                @error('kodepos')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            Kontak dan Pembayaran
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">No HP*</label>
                            <div class="col-sm-4">
                                <input type="number"
                                       class="form-control"
                                       name="no_hp"
                                       required
                                       value="{{ old('no_hp') }}"
                                       placeholder="Masukkan nomor HP pasien">

                                @error('no_hp')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-3 col-form-label form-label-custom">Kewarganegaraan</label>
                            <div class="col-sm-3">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input type="radio"
                                               name="kewarganegaraan"
                                               class="form-check-input"
                                               value="WNI"
                                               {{ old('kewarganegaraan', 'WNI') == 'WNI' ? 'checked' : '' }}>
                                        <label class="form-check-label">WNI</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio"
                                               name="kewarganegaraan"
                                               class="form-check-input"
                                               value="WNA"
                                               {{ old('kewarganegaraan') == 'WNA' ? 'checked' : '' }}>
                                        <label class="form-check-label">WNA</label>
                                    </div>
                                </div>

                                @error('kewarganegaraan')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Cara Bayar*</label>
                            <div class="col-sm-4">
                                <div class="radio-group">
                                    <div class="form-check">
                                        <input type="radio"
                                               name="cara_bayar"
                                               class="form-check-input"
                                               value="Umum/Mandiri"
                                               {{ old('cara_bayar') == 'Umum/Mandiri' ? 'checked' : '' }}>
                                        <label class="form-check-label">Umum/Mandiri</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio"
                                               name="cara_bayar"
                                               class="form-check-input"
                                               value="Jaminan Kesehatan"
                                               {{ old('cara_bayar') == 'Jaminan Kesehatan' ? 'checked' : '' }}>
                                        <label class="form-check-label">Jaminan Kesehatan</label>
                                    </div>
                                </div>

                                @error('cara_bayar')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <label class="col-sm-2 col-form-label form-label-custom" id="no_bpjs_label">No. BPJS / KTP</label>
                            <div class="col-sm-4">
                                <input type="number"
                                       class="form-control"
                                       id="no_bpjs"
                                       name="no_bpjs"
                                       value="{{ old('no_bpjs') }}"
                                       placeholder="Masukkan No. BPJS atau KTP">

                                @error('no_bpjs')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-title mt-4">
                            Data Tambahan
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">Alergi</label>
                            <div class="col-sm-10">
                                <textarea name="alergi"
                                          class="form-control"
                                          rows="2"
                                          placeholder="Masukkan alergi pasien jika ada">{{ old('alergi') }}</textarea>

                                @error('alergi')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label form-label-custom">File General Consent</label>
                            <div class="col-sm-10">
                                <input type="file" name="file" class="form-control">
                                <div class="file-help-text">
                                    Upload file persetujuan umum pasien jika tersedia.
                                </div>

                                @error('file')
                                    <div class="invalid-feedback animated fadeInUp" style="display: block;">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="form-group d-flex justify-content-between align-items-center">
                            <a href="{{ Route('pasien') }}" class="btn btn-light btn-back-pasien">
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-submit-pasien">
                                Simpan Data Pasien
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
        $("#nama").change(function(){
            checkedChard();
        });

        $("#code").change(function(){
            checkedChard();
        });
    });

    function checkedChard(){
        var nama_full = $("#nama").val();
        var code = $("#code").val();

        if(nama_full != "" && code != ""){
            var firstChar = nama_full.charAt(0);
            var awalCode = code + firstChar;

            $("#no_rm").val(awalCode);

            $.get(
                "{{ route('getNoRM') }}",
                {
                    code: awalCode
                },
                function(data) {
                    $("#no_rm").val(data.data);
                }
            );
        }
    }
</script>
@endsection