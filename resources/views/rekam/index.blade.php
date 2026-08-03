@extends('layout.apps')
@section('content')

@php
    $role = auth()->user()->role_display();
    $isAdmin = $role == "Admin";
    $isDokter = $role == "Dokter";

    $tabAktif = request('tab');
    if ($isDokter && $tabAktif == null) {
        $tabAktif = 2;
    }

    $tglAwal = request('tgl_awal');
    $tglAkhir = request('tgl_akhir');
@endphp

<style>
    .page-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
    }

    .page-header-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2b5b;
        margin-bottom: 4px;
    }

    .page-header-subtitle {
        color: #7b7f9e;
        margin-bottom: 0;
    }

    .rekam-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 24px;
        margin-bottom: 18px;
    }

    .rekam-toolbar-text {
        color: #7b7f9e;
        font-size: 14px;
    }

    .rekam-toolbar-text strong {
        color: #007A64;
        font-weight: 700;
    }

    .rekam-search {
        width: 420px;
        max-width: 100%;
    }

    .rekam-search .form-control {
        height: 44px;
        border: 1px solid #edf0f5;
        border-right: none;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
    }

    .rekam-search .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .btn-search-rekam {
        height: 44px;
        min-width: 54px;
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        border-radius: 0 8px 8px 0;
    }

    .btn-search-rekam:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .tanggal-filter-card {
        background: #f8fafc;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .tanggal-filter {
        display: flex;
        align-items: end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tanggal-filter .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .tanggal-filter label {
        color: #7b7f9e;
        font-size: 12px;
        margin-bottom: 0;
        font-weight: 600;
    }

    .tanggal-filter .form-control {
        height: 42px;
        border: 1px solid #edf0f5;
        border-radius: 8px;
        font-size: 13px;
    }

    .tanggal-filter .form-control:focus {
        border-color: #007A64;
        box-shadow: none;
    }

    .tanggal-filter .filter-date {
        width: 165px;
    }

    .tanggal-filter .filter-search {
        width: 260px;
    }

    .btn-filter-tanggal {
        height: 42px;
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        border-radius: 8px;
        padding: 0 16px;
    }

    .btn-filter-tanggal:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .btn-reset-filter {
        height: 42px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 14px;
    }

    .rekam-table th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .rekam-table td {
        vertical-align: middle;
        color: #333;
        font-size: 13px;
        padding: 14px 10px;
    }

    .rekam-no-rekam {
        font-weight: 700;
        color: #1f2b5b;
        font-size: 13px;
    }

    .rekam-date {
        font-size: 12px;
        color: #6b7280;
        margin-top: 2px;
    }

    .rekam-patient-link {
        color: #333;
        font-weight: 700;
    }

    .rekam-patient-link:hover {
        color: #007A64;
    }

    .btn-detail-rekam {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        padding: 7px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-detail-rekam:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .empty-state {
        padding: 40px 20px;
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
        .rekam-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .rekam-search {
            width: 100%;
        }

        .tanggal-filter .filter-date,
        .tanggal-filter .filter-search {
            width: 100%;
        }

        .tanggal-filter {
            align-items: stretch;
        }
    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div class="card page-card">
            <div class="card-body">

                {{-- Header halaman --}}
                <div class="row align-items-center mb-4">
                    <div class="col-lg-8">
                        @if($isDokter)
                            @if($tabAktif == 5)
                                <h4 class="page-header-title">Pasien Selesai Diperiksa</h4>
                                <p class="page-header-subtitle">
                                    Riwayat pasien yang telah selesai diperiksa oleh dokter.
                                </p>
                            @else
                                <h4 class="page-header-title">Pasien Perlu Diperiksa</h4>
                                <p class="page-header-subtitle">
                                    Daftar pasien yang perlu dilakukan pemeriksaan oleh dokter.
                                </p>
                            @endif
                        @else
                            <h4 class="page-header-title">Data Rekam Medis</h4>
                            <p class="page-header-subtitle">
                                Pengelolaan data rekam medis pasien OQ Clinic Dentist.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Toolbar --}}
                <div class="rekam-toolbar">

                    <div>
                        @if($isDokter)
                            @if($tabAktif == 5)
                                <span class="rekam-toolbar-text">
                                    Total pasien selesai diperiksa:
                                    <strong>{{ $rekams->total() }}</strong>
                                    data.
                                </span>
                            @else
                                <span class="rekam-toolbar-text">
                                    Total pasien perlu diperiksa:
                                    <strong>{{ $rekams->total() }}</strong>
                                    data.
                                </span>
                            @endif
                        @else
                            <span class="rekam-toolbar-text">
                                Total data rekam medis:
                                <strong>{{ $rekams->total() }}</strong>
                                data.
                            </span>
                        @endif
                    </div>

                    {{-- Search biasa untuk Admin dan Perlu Diperiksa --}}
                    @if(!($isDokter && $tabAktif == 5))
                        <div class="rekam-search">
                            <form method="get" action="{{ url()->current() }}">
                                @if(request('tab'))
                                    <input type="hidden" name="tab" value="{{ request('tab') }}">
                                @endif

                                <div class="input-group">
                                    <input type="text"
                                           class="form-control"
                                           name="keyword"
                                           value="{{ request('keyword') }}"
                                           placeholder="Cari nomor rekam, nama pasien, tanggal, atau RM..."
                                           autocomplete="off">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-search-rekam">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>

                {{-- Filter tanggal khusus Dokter pada menu Selesai Diperiksa --}}
                @if($isDokter && $tabAktif == 5)
                    <div class="tanggal-filter-card">
                        <form method="get" action="{{ url()->current() }}" class="tanggal-filter">
                            <input type="hidden" name="tab" value="5">

                            <div class="filter-group">
                                <label>Dari Tanggal</label>
                                <input type="date"
                                       name="tgl_awal"
                                       class="form-control filter-date"
                                       value="{{ request('tgl_awal') }}">
                            </div>

                            <div class="filter-group">
                                <label>Sampai Tanggal</label>
                                <input type="date"
                                       name="tgl_akhir"
                                       class="form-control filter-date"
                                       value="{{ request('tgl_akhir') }}">
                            </div>

                            <div class="filter-group">
                                <label>Pencarian</label>
                                <input type="text"
                                       name="keyword"
                                       class="form-control filter-search"
                                       value="{{ request('keyword') }}"
                                       placeholder="Cari pasien atau no rekam...">
                            </div>

                            <button type="submit" class="btn btn-filter-tanggal">
                                <i class="fa fa-search"></i>
                            </button>

                            <a href="{{ Route('rekam', ['tab' => 5]) }}" class="btn btn-light btn-reset-filter">
                                Reset
                            </a>
                        </form>
                    </div>
                @endif

                {{-- Tabel --}}
                <div class="table-responsive card-table">
                    <table class="table table-responsive-md rekam-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>

                                @if($isAdmin)
                                    <th>Dokter</th>
                                @endif

                                <th>Keluhan</th>

                                @if($isAdmin)
                                    <th>Cara Bayar</th>
                                @endif

                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($rekams as $key => $row)
                                <tr>
                                    <td align="center">{{ $rekams->firstItem() + $key }}</td>

                                    <td>
                                        <div class="rekam-no-rekam">{{ $row->no_rekam }}</div>
                                        <div class="rekam-date">{{ $row->tgl_rekam }}</div>
                                    </td>

                                    <td>
                                        <a href="{{ Route('rekam.detail', $row->pasien_id) }}" class="rekam-patient-link">
                                            {{ optional($row->pasien)->nama ?? '-' }}
                                        </a>
                                    </td>

                                    @if($isAdmin)
                                        <td>
                                            <strong>{{ optional($row->dokter)->nama ?? '-' }}</strong>
                                        </td>
                                    @endif

                                    <td>{{ $row->keluhan ?? '-' }}</td>

                                    @if($isAdmin)
                                        <td>{{ $row->cara_bayar ?? '-' }}</td>
                                    @endif

                                    <td>{!! $row->status_display() !!}</td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center">

                                            @if($isDokter)
                                                <a href="{{ Route('rekam.detail', $row->pasien_id) }}"
                                                   class="btn btn-detail-rekam">
                                                    @if($row->status == 5)
                                                        Detail
                                                    @else
                                                        Periksa
                                                    @endif
                                                </a>
                                            @else
                                                <a href="{{ Route('rekam.detail', $row->pasien_id) }}"
                                                   class="btn btn-detail-rekam"
                                                   title="Lihat Detail">
                                                    Detail
                                                </a>
                                            @endif

                                           

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAdmin ? '8' : '6' }}">
                                        <div class="empty-state">
                                            @if($isDokter && $tabAktif == 5)
                                                <strong>Belum ada data pasien selesai diperiksa.</strong>
                                                <br>
                                                <span>Silakan gunakan filter tanggal atau kata kunci pencarian lain.</span>
                                            @elseif($isDokter)
                                                <strong>Tidak ada pasien yang perlu diperiksa.</strong>
                                                <br>
                                                <span>Data pasien akan tampil apabila ada pasien yang diteruskan ke dokter.</span>
                                            @else
                                                <strong>Data rekam medis tidak tersedia.</strong>
                                                <br>
                                                <span>Silakan cek kembali data atau gunakan kata kunci pencarian lain.</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Menampilkan {{ $rekams->firstItem() ?? 0 }} sampai {{ $rekams->lastItem() ?? 0 }}
                            dari {{ $rekams->total() }} data
                        </div>

                        <div>
                            {{ $rekams->appends(request()->except('page'))->links() }}
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
        $(".delete").click(function(e) {
            e.preventDefault();

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