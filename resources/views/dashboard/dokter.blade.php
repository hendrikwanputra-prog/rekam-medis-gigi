@inject('query', 'App\Models\DashboardQuery')

@extends('layout.apps')
@section('content')

@php
    $antrian = $query->rekam_antrian();
    $rekamHariIni = $query->rekam_day();

    $diagnosaBulanan = collect($query->diagnosaBulanan());
    $diagnosaTahunan = collect($query->diagnosaYearly());

    $maxBulanan = $diagnosaBulanan->max('total') ?: 1;
    $maxTahunan = $diagnosaTahunan->max('total') ?: 1;
@endphp

<style>
    .dashboard-title {
        font-size: 26px;
        font-weight: 700;
        color: #1f2b5b;
        margin-bottom: 6px;
    }

    .dashboard-subtitle {
        color: #7b7f9e;
        margin-bottom: 0;
    }

    .dashboard-card,
    .dashboard-panel,
    .top-diagnosa-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.03);
    }

    .dashboard-card .card-body {
        padding: 26px 24px;
    }

    .dashboard-card h2 {
        color: #1f2b5b !important;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .dashboard-card span {
        color: #7b7f9e;
        font-weight: 500;
    }

    .dashboard-card .progress {
        display: none;
    }

    .dashboard-icon {
        font-size: 44px;
        color: #007A64;
        line-height: 1;
    }

    .dashboard-panel .card-header,
    .top-diagnosa-card .card-header {
        padding: 24px 26px 0 26px;
    }

    .dashboard-panel .card-body,
    .top-diagnosa-card .card-body {
        padding: 22px 26px 26px 26px;
    }

    .panel-title {
        color: #111827;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 0;
    }

    .panel-subtitle {
        color: #7b7f9e;
        font-size: 13px;
    }

    .table-dashboard {
        margin-bottom: 0;
    }

    .table-dashboard th {
        color: #111827;
        font-weight: 700;
        border-top: 0;
        white-space: nowrap;
        font-size: 13px;
        padding: 14px 10px;
    }

    .table-dashboard td {
        vertical-align: middle;
        font-size: 13px;
        padding: 14px 10px;
    }

    .empty-dashboard-state {
        background: #f3fbf8;
        border: 1px solid #d6f0e9;
        border-radius: 10px;
        color: #007A64;
        padding: 18px 20px;
        font-size: 14px;
        font-weight: 500;
    }

    .pasien-summary {
        background: #f8fafc;
        border: 1px solid #edf0f5;
        border-radius: 10px;
        padding: 18px 20px;
    }

    .pasien-summary-icon {
        width: 58px;
        height: 58px;
        border-radius: 10px;
        background: #007A64;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        margin-right: 16px;
    }

    .pasien-summary p {
        color: #7b7f9e;
        margin-bottom: 4px;
    }

    .pasien-summary .value {
        color: #007A64;
        font-size: 26px;
        font-weight: 700;
        line-height: 1;
    }

    .btn-periksa-dashboard {
        background: #007A64;
        border-color: #007A64;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 7px 16px;
        border-radius: 20px;
        white-space: nowrap;
    }

    .btn-periksa-dashboard:hover {
        background: #006451;
        border-color: #006451;
        color: #fff;
    }

    .top-diagnosa-tabs {
        border-bottom: 0;
        background: #f8fafc;
        border-radius: 8px;
        overflow: hidden;
    }

    .top-diagnosa-tabs .nav-link {
        border: 0;
        color: #6b7280;
        padding: 10px 22px;
        font-weight: 600;
    }

    .top-diagnosa-tabs .nav-link.active {
        color: #007A64;
        background: #e8f7f3;
        border-bottom: 2px solid #007A64;
    }

    .top-diagnosa-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 14px 0;
        border-bottom: 1px solid #eef1f5;
    }

    .top-diagnosa-item:last-child {
        border-bottom: 0;
    }

    .top-diagnosa-rank {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #bfe5dd;
        border-radius: 8px;
        color: #007A64;
        background: #f4fffc;
        font-weight: 700;
    }

    .top-diagnosa-info {
        flex: 1;
        min-width: 0;
    }

    .top-diagnosa-code {
        color: #1f2937;
        font-weight: 700;
        margin-right: 16px;
    }

    .top-diagnosa-name {
        color: #606b7b;
        font-size: 14px;
    }

    .top-diagnosa-progress {
        height: 7px;
        border-radius: 30px;
        background: #edf0f5;
        overflow: hidden;
    }

    .top-diagnosa-progress .progress-bar {
        background: #007A64;
        border-radius: 30px;
    }

    .top-diagnosa-total {
        width: 90px;
        text-align: right;
        color: #007A64;
    }

    .top-diagnosa-total strong {
        display: block;
        font-size: 18px;
        line-height: 18px;
    }

    .top-diagnosa-total span {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    .top-diagnosa-note {
        background: #f3fbf8;
        border: 1px solid #d6f0e9;
        color: #007A64;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
    }

    @media (max-width: 575px) {
        .top-diagnosa-item {
            align-items: flex-start;
        }

        .top-diagnosa-total {
            width: 60px;
        }

        .top-diagnosa-name {
            display: block;
            width: 100%;
            margin-top: 4px;
        }
    }
</style>

<div class="form-head d-flex align-items-center mb-sm-4 mb-3">
    <div class="mr-auto">
        <h2 class="dashboard-title">Dashboard</h2>
        <p class="dashboard-subtitle">
            OQ Clinic Dentist Dashboard Dokter
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-sm-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body mr-3">
                        <h2 class="fs-34 text-black font-w600">{{ $query->pasienAntri() }}</h2>
                        <span>Pasien Sedang Antri</span>
                    </div>
                    <i class="las la-stethoscope dashboard-icon"></i>
                </div>
            </div>
            <div class="progress rounded-0" style="height:4px;">
                <div class="progress-bar rounded-0 bg-secondary progress-animated" style="width:30%; height:4px;" role="progressbar"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body mr-3">
                        <h2 class="fs-34 text-black font-w600">{{ $query->perikaHariini() }}</h2>
                        <span>Pemeriksaan Hari Ini</span>
                    </div>
                    <i class="las la-calendar-check dashboard-icon"></i>
                </div>
            </div>
            <div class="progress rounded-0" style="height:4px;">
                <div class="progress-bar rounded-0 bg-secondary progress-animated" style="width:50%; height:4px;" role="progressbar"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body mr-3">
                        <h2 class="fs-34 text-black font-w600">{{ $query->totalPasien() }}</h2>
                        <span>Total Pasien Anda</span>
                    </div>
                    <i class="las la-heart dashboard-icon"></i>
                </div>
            </div>
            <div class="progress rounded-0" style="height:4px;">
                <div class="progress-bar rounded-0 bg-secondary progress-animated" style="width:90%; height:4px;" role="progressbar"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <div class="media align-items-center">
                    <div class="media-body mr-3">
                        <h2 class="fs-34 text-black font-w600">{{ $query->totalPeriksa() }}</h2>
                        <span>Total Pemeriksaan</span>
                    </div>
                    <i class="las la-user-md dashboard-icon"></i>
                </div>
            </div>
            <div class="progress rounded-0" style="height:4px;">
                <div class="progress-bar rounded-0 bg-secondary progress-animated" style="width:94%; height:4px;" role="progressbar"></div>
            </div>
        </div>
    </div>
</div>

<div class="row align-items-start">
    <div class="col-xl-7">
        <div class="card dashboard-panel">
            <div class="card-header pb-0 border-0">
                <h3 class="panel-title">Pasien Perlu Anda Periksa</h3>
            </div>

            <div class="card-body">
                @if ($antrian->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-dashboard mb-0">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Keluhan</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($antrian as $row)
                                    <tr>
                                        <td>
                                            <strong class="text-black">{{ optional($row->pasien)->nama ?? '-' }}</strong><br>
                                            <small>{{ $row->created_at ? $row->created_at->diffForHumans() : '-' }}</small>
                                        </td>
                                        <td>{{ $row->keluhan ?? '-' }}</td>
                                        <td>{!! $row->status_display() !!}</td>
                                        <td class="text-center">
                                            <a href="{{ Route('rekam.detail', $row->pasien_id) }}" class="btn btn-periksa-dashboard">
                                                <i class="fa fa-user-md"></i> Periksa
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-dashboard-state">
                        <i class="fa fa-check-square-o mr-2"></i>
                        Tidak ada pasien yang perlu diperiksa saat ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card dashboard-panel mb-4">
            <div class="card-header d-sm-flex d-block pb-0 border-0">
                <div class="mr-auto pr-3">
                    <h4 class="panel-title">Pasien Anda Hari Ini</h4>
                </div>

                <div class="card-action card-tabs mt-3 mt-sm-0">
                    <ul class="nav nav-tabs top-diagnosa-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#DokterDaily" role="tab">Daily</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#DokterMonthly" role="tab">Monthly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#DokterYearly" role="tab">Yearly</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="DokterDaily" role="tabpanel">
                        <div class="d-flex align-items-center pasien-summary">
                            <span class="pasien-summary-icon">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p>Periksa Hari Ini</p>
                                <span class="value">{{ $query->perikaHariini() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="DokterMonthly" role="tabpanel">
                        <div class="d-flex align-items-center pasien-summary">
                            <span class="pasien-summary-icon">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p>Periksa Bulan Ini</p>
                                <span class="value">{{ $query->perikaBulanini() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="DokterYearly" role="tabpanel">
                        <div class="d-flex align-items-center pasien-summary">
                            <span class="pasien-summary-icon">
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p>Total Pemeriksaan</p>
                                <span class="value">{{ $query->totalPeriksa() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card top-diagnosa-card">
            <div class="card-header d-sm-flex d-block align-items-center pb-0 border-0">
                <div class="mr-auto pr-3">
                    <h4 class="panel-title mb-1">Top Diagnosa</h4>
                    <span class="panel-subtitle">Diagnosa paling sering berdasarkan data rekam medis.</span>
                </div>

                <div class="card-action card-tabs mt-3 mt-sm-0">
                    <ul class="nav nav-tabs top-diagnosa-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#MonthlyDiagnosaDokter" role="tab">Monthly</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#YearlyDiagnosaDokter" role="tab">Yearly</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="MonthlyDiagnosaDokter" role="tabpanel">
                        @forelse ($diagnosaBulanan->take(5) as $item)
                            @php $persen = round(($item->total / $maxBulanan) * 100); @endphp

                            <div class="top-diagnosa-item">
                                <div class="top-diagnosa-rank">{{ $loop->iteration }}</div>

                                <div class="top-diagnosa-info">
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <span class="top-diagnosa-code">{{ $item->diagnosa }}</span>
                                        <span class="top-diagnosa-name">{{ $item->name_id }}</span>
                                    </div>

                                    <div class="progress top-diagnosa-progress">
                                        <div class="progress-bar"
                                             style="width: {{ $persen }}%;"
                                             role="progressbar"
                                             aria-valuenow="{{ $persen }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="top-diagnosa-total">
                                    <strong>{{ $item->total }}</strong>
                                    <span>Kasus</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <h5 class="mb-1">Belum ada data diagnosa</h5>
                                <span class="text-muted">Data diagnosa bulanan akan tampil setelah rekam medis diinput.</span>
                            </div>
                        @endforelse
                    </div>

                    <div class="tab-pane fade" id="YearlyDiagnosaDokter" role="tabpanel">
                        @forelse ($diagnosaTahunan->take(5) as $item)
                            @php $persen = round(($item->total / $maxTahunan) * 100); @endphp

                            <div class="top-diagnosa-item">
                                <div class="top-diagnosa-rank">{{ $loop->iteration }}</div>

                                <div class="top-diagnosa-info">
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <span class="top-diagnosa-code">{{ $item->diagnosa }}</span>
                                        <span class="top-diagnosa-name">{{ $item->name_id }}</span>
                                    </div>

                                    <div class="progress top-diagnosa-progress">
                                        <div class="progress-bar"
                                             style="width: {{ $persen }}%;"
                                             role="progressbar"
                                             aria-valuenow="{{ $persen }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="top-diagnosa-total">
                                    <strong>{{ $item->total }}</strong>
                                    <span>Kasus</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <h5 class="mb-1">Belum ada data diagnosa</h5>
                                <span class="text-muted">Data diagnosa tahunan akan tampil setelah rekam medis diinput.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="top-diagnosa-note mt-3">
                    <i class="fa fa-chart-bar mr-2"></i>
                    Data berdasarkan diagnosa yang telah dicatat pada rekam medis.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection