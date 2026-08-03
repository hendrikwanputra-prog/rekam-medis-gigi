@extends('layout.apps')

@section('content')
{{-- BREADCRUMBS --}}
<div class="form-head page-titles d-flex align-items-center mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="text-black font-w600">Resep & Pemberian Obat</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Pasien</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">RM#{{ $pasien->no_rm }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{ $rekam->no_rekam }}</a></li>
        </ol>
    </div>
    <div class="d-flex">
        @if ($rekam)
            {!! $rekam->status_display() !!}
        @endif
    </div>
</div>

{{-- MODAL PENCARIAN OBAT --}}
<div class="modal fade" id="modalObat">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Data Obat</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive card-table">
                    <table class="display dataTablesCard white-border table-responsive-sm" style="width: 100%" id="obat-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Obat</th>
                                <th>Nama Obat</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DATA --}}
<div class="row">
    <div class="col-xl-4 col-lg-5">
        {{-- DETAIL PASIEN --}}
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div>
                    <h4 class="fs-20 text-black mb-1">Detail Pasien</h4>
                    <span class="fs-12">Informasi identitas pasien</span>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" id="pasien_id" value="{{ $pasien->id }}">
                <input type="hidden" id="rekam_id" value="{{ $rekam ? $rekam->id : '' }}">

                <h3 class="fs-18 font-w600 mb-1">
                    <a href="javascript:void(0)" class="text-black">{{ $pasien->nama }}</a>
                </h3>

                <div class="mt-3">
                    <p class="mb-1 fs-14"><strong>TTL :</strong> {{ $pasien->tmp_lahir }}, {{ $pasien->tgl_lahir }}</p>
                    <p class="mb-1 fs-14"><strong>Agama :</strong> {{ $pasien->agama }}</p>
                    <p class="mb-1 fs-14"><strong>Status :</strong> {{ $pasien->jk }}, {{ $pasien->status_menikah }}</p>
                    <p class="mb-1 fs-14"><strong>Alamat :</strong> {{ $pasien->alamat_lengkap }}</p>
                    <p class="mb-1 fs-14">{{ $pasien->kelurahan }}, {{ $pasien->kecamatan }}, {{ $pasien->kabupaten }}, {{ $pasien->kewarganegaraan }}</p>
                    <p class="mb-0 fs-14"><strong>Alergi :</strong> {{ $pasien->alergi ? $pasien->alergi : '-' }}</p>
                </div>
            </div>
        </div>

        {{-- INFO DETAIL REKAM --}}
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div>
                    <h4 class="fs-20 text-black mb-1">Info Pemeriksaan</h4>
                    <span class="fs-12">Rincian data rekam medis</span>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start mb-3">
                    <span class="fs-13 col-5 p-0 text-black font-w600">Cara Bayar</span>
                    <div class="col-7 p-0">
                        <p class="mb-0">{{ $rekam->cara_bayar }}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <span class="fs-13 col-5 p-0 text-black font-w600">Keluhan</span>
                    <div class="col-7 p-0">
                        <p class="mb-0">{!! $rekam->keluhan !!}</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <span class="fs-13 col-5 p-0 text-black font-w600">Diagnosa</span>
                    <div class="col-7 p-0">
                        @if ($rekam->poli == "Poli Gigi")
                            @forelse ($rekam->gigi() as $item)
                                <span class="badge badge-success mb-1">{{ $item->diagnosa }}</span>
                            @empty
                                <p class="mb-0">-</p>
                            @endforelse
                        @else
                            @forelse ($rekam->diagnosa() as $item)
                                <p class="mb-2">
                                    <strong>{{ $item->diagnosis->code }}</strong><br>
                                    {{ $item->diagnosis->name_id }}
                                </p>
                            @empty
                                <p class="mb-0">-</p>
                            @endforelse
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <span class="fs-13 col-5 p-0 text-black font-w600">
                        {{ $rekam->poli == "Poli Gigi" ? 'Resep Obat' : 'Tindakan' }}
                    </span>
                    <div class="col-7 p-0">
                        @if ($rekam->poli == "Poli Gigi")
                            {!! $rekam->resep_obat ? $rekam->resep_obat : '-' !!}
                        @else
                            <p class="mb-0">{!! $rekam->tindakan ? $rekam->tindakan : '-' !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7">
        @if ($rekam->status == 3)
            {{-- FORM PEMBERIAN OBAT --}}
            <div class="card">
                <div class="card-header border-0 pb-0 d-sm-flex d-block">
                    <div class="mr-auto">
                        <h4 class="fs-20 text-black mb-1">Pemberian Obat</h4>
                        <span class="fs-12">Pilih obat yang diberikan kepada pasien sesuai resep.</span>
                    </div>
                    <div class="mt-3 mt-sm-0">
                        <a href="{{ Route('rekam.status', [$rekam->id, 5]) }}" class="btn btn-outline-success btn-sm">
                            Selesaikan Tanpa Obat
                            <span class="btn-icon-right"><i class="fa fa-check"></i></span>
                        </a>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="p-3 rounded" style="background:#f9fbfb;">
                                <h5 class="text-black mb-3">Form Obat</h5>

                                <form method="POST">
                                    <input type="hidden" class="form-control" id="stok">
                                    <input type="hidden" class="form-control" id="obat_code">

                                    <div class="form-group">
                                        <label class="text-black font-w500">Kode Obat*</label>
                                        <div class="input-group transparent-append">
                                            <input type="text" id="obat_id" class="form-control" data-toggle="modal" data-target="#modalObat" name="obat_id" placeholder="Klik untuk pilih obat" readonly>
                                            <div class="input-group-append show-pass" data-toggle="modal" data-target="#modalObat">
                                                <span class="input-group-text">
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modalObat"><i class="fa fa-search"></i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-black font-w500">Nama Obat</label>
                                        <input type="text" id="nama_obat" class="form-control" readonly>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="text-black font-w500">Jumlah*</label>
                                            <input type="number" name="jumlah" id="jumlah" required class="form-control" placeholder="0">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="text-black font-w500">Harga*</label>
                                            <input type="number" name="harga" id="harga" required class="form-control" placeholder="0">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-black font-w500">Keterangan</label>
                                        <input type="text" id="keterangan" class="form-control" placeholder="Aturan pakai / catatan">
                                    </div>

                                    <button type="button" onclick="addObat()" class="btn btn-info btn-block">
                                        <i class="fa fa-plus mr-1"></i> Tambah ke Daftar
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="text-black mb-1">Daftar Obat yang Akan Dikeluarkan</h5>
                                    <span class="fs-12">Data obat akan disimpan sebagai riwayat pemberian obat pasien.</span>
                                </div>
                            </div>

                            <form action="{{ Route('obat.pengeluaran.store') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="rekam_id" value="{{ $rekam->id }}">
                                <input type="hidden" name="pasien_id" value="{{ $pasien->id }}">

                                <div class="table-responsive">
                                    <table id="table-obat" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><strong>Kode</strong></th>
                                                <th><strong>Nama</strong></th>
                                                <th><strong>Jumlah</strong></th>
                                                <th><strong>Harga</strong></th>
                                                <th><strong>Total</strong></th>
                                                <th><strong>Keterangan</strong></th>
                                                <th class="text-center"><strong>Aksi</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="empty-row">
                                                <td colspan="7" class="text-center text-muted">
                                                    Belum ada obat yang ditambahkan.
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    SIMPAN & SELESAIKAN PROSES
                                    <span class="btn-icon-right"><i class="fa fa-save"></i></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($rekam->status == 4 || $rekam->status == 5)
            {{-- RIWAYAT OBAT --}}
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div>
                        <h4 class="fs-20 text-black mb-1">Riwayat Pemberian Obat</h4>
                        <span class="fs-12">Daftar obat yang telah diberikan kepada pasien.</span>
                    </div>
                </div>

                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><strong>Kode</strong></th>
                                    <th><strong>Nama Obat</strong></th>
                                    <th><strong>Jumlah</strong></th>
                                    <th><strong>Harga</strong></th>
                                    <th><strong>Total</strong></th>
                                    <th><strong>Keterangan</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengeluaran as $item)
                                    <tr>
                                        <td>{{ $item->obat->kd_obat }}</td>
                                        <td>{{ $item->obat->nama }}</td>
                                        <td>{{ $item->jumlah }}</td>
                                        <td>Rp {{ number_format($item->harga) }}</td>
                                        <td>Rp {{ number_format($item->subtotal) }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Belum ada riwayat pemberian obat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($pengeluaran->count() > 0)
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-right">Total Biaya Obat</th>
                                        <th colspan="2">Rp {{ number_format($pengeluaran->sum('subtotal')) }}</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<script>
    function addObat() {
        var obatNama = $("#nama_obat").val();
        var obatId = $("#obat_id").val();
        var obatCode = $("#obat_code").val();

        var harga = $("#harga").val();
        var stok = $("#stok").val();
        var jumlah = $("#jumlah").val();
        var keterangan = $("#keterangan").val();

        if (jumlah == "" || obatId == "" || harga == "") {
            alert("Obat wajib dipilih dan jumlah wajib diisi");
            return;
        }

        if (parseInt(jumlah) > parseInt(stok)) {
            alert("Jumlah tidak sesuai stok");
            return;
        }

        $(".empty-row").remove();

        var subtotal = parseInt(harga) * parseInt(jumlah);
        var markup = '<tr>' +
            '<td>' + obatCode +
                '<input type="hidden" name="obat_id[]" value="' + obatId + '"/>' +
            '</td>' +
            '<td>' + obatNama + '</td>' +
            '<td>' + jumlah +
                '<input type="hidden" name="jumlah[]" value="' + jumlah + '"/>' +
            '</td>' +
            '<td>Rp ' + parseInt(harga).toLocaleString('id-ID') +
                '<input type="hidden" name="harga[]" value="' + harga + '"/>' +
            '</td>' +
            '<td>Rp ' + subtotal.toLocaleString('id-ID') +
                '<input type="hidden" name="subtotal[]" value="' + subtotal + '"/>' +
            '</td>' +
            '<td>' + keterangan +
                '<input type="hidden" name="keterangan[]" value="' + keterangan + '"/>' +
            '</td>' +
            '<td class="text-center" style="width: 80px">' +
                '<a href="#" class="btn btn-danger shadow btn-xs sharp btnDelete"><i class="fa fa-trash"></i></a>' +
            '</td>' +
        '</tr>';

        $("#table-obat tbody").append(markup);

        $("#obat_id").val("");
        $("#nama_obat").val("");
        $("#obat_code").val("");
        $("#stok").val("");
        $("#harga").val("");
        $("#jumlah").val("");
        $("#keterangan").val("");
    }

    $(function () {
        var table = $('#obat-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            paging: true,
            select: false,
            pageLength: 5,
            lengthChange: false,
            ajax: "{{ route('obat.data') }}",
            columns: [
                {data: 'action', name: 'action'},
                {data: 'kd_obat', name: 'kd_obat'},
                {data: 'nama', name: 'nama'},
                {data: 'stok', name: 'stok'},
                {data: 'satuan', name: 'satuan'},
                {data: 'harga', name: 'harga'}
            ]
        });

        $("#table-obat").on('click', '.btnDelete', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();

            if ($("#table-obat tbody tr").length == 0) {
                $("#table-obat tbody").append(
                    '<tr class="empty-row"><td colspan="7" class="text-center text-muted">Belum ada obat yang ditambahkan.</td></tr>'
                );
            }
        });
    });

    $(document).on("click", ".pilihObat", function () {
        var id = $(this).data('id');
        var nama = $(this).data('nama');
        var harga = $(this).data('harga');
        var stok = $(this).data('stok');
        var satuan = $(this).data('satuan');
        var code = $(this).data('code');

        $("#nama_obat").val(nama);
        $("#obat_id").val(id);
        $("#obat_code").val(code);
        $("#harga").val(harga);
        $("#stok").val(stok);

        $("#modalObat").modal('hide');
    });
</script>
@endsection
