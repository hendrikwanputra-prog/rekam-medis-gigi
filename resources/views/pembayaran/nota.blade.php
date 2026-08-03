@extends('layout.apps')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">

            <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                <div>
                    <h2 class="text-black font-w700 mb-1">Nota Pembayaran</h2>
                    <p class="mb-0 text-muted">Bukti transaksi pembayaran pasien OQ Clinic Dentist.</p>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body" style="background: #f8fbfa;">

                    <div class="nota-wrapper mx-auto" id="printArea">
                        <div class="nota-card">

                            <div class="nota-header">
                                <div class="nota-header-left">
                                    <div class="nota-logo-box">
                                        <img src="{{ asset('images/logonotaoq.png') }}"
                                             alt="Logo OQ Clinic Dentist"
                                             class="nota-logo">
                                    </div>

                                    <div class="nota-clinic-info">
                                        <h3>OQ CLINIC DENTIST</h3>
                                        <p>Jl. Utan Kayu Raya No.100C, RT.2/RW.9</p>
                                        <p>Jakarta Timur, DKI Jakarta 13120</p>
                                        <p>Telp/HP: 082163339515</p>
                                    </div>
                                </div>

                                <div class="nota-header-right">
                                    <h4>NOTA PEMBAYARAN</h4>
                                    <p class="mb-2">No. {{ $data->no_rekam }}</p>

                                    @if(($data->total_biaya ?? 0) > 0)
                                        <span class="status-badge status-lunas">
                                            <i class="fa fa-check-circle"></i> LUNAS
                                        </span>
                                    @else
                                        <span class="status-badge status-belum">
                                            BELUM BAYAR
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="nota-divider"></div>

                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <div class="info-card">
                                        <div class="info-title">
                                            <i class="fa fa-user-circle mr-2"></i> Informasi Pasien
                                        </div>

                                        <div class="info-row">
                                            <span>Nama Pasien</span>
                                            <strong>{{ $data->pasien->nama ?? '-' }}</strong>
                                        </div>

                                        <div class="info-row">
                                            <span>Tanggal</span>
                                            <strong>{{ \Carbon\Carbon::parse($data->tgl_rekam)->translatedFormat('d F Y') }}</strong>
                                        </div>

                                        <div class="info-row">
                                            <span>Keluhan</span>
                                            <strong>{{ $data->keluhan ?? '-' }}</strong>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="info-card">
                                        <div class="info-title">
                                            <i class="fa fa-medkit mr-2"></i> Informasi Layanan
                                        </div>

                                        <div class="info-row">
                                            <span>Dokter</span>
                                            <strong>{{ $data->dokter->nama ?? '-' }}</strong>
                                        </div>

                                        <div class="info-row">
                                            <span>SIP drg</span>
                                            <strong>440/001.SIP.DG-SP/JP/2026</strong>
                                        </div>

                                        <div class="info-row">
                                            <span>Cara Bayar</span>
                                            <strong>{{ $data->cara_bayar ?? '-' }}</strong>
                                        </div>

                                        <div class="info-row">
                                            <span>No Rekam</span>
                                            <strong>{{ $data->no_rekam }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-table">
                                <div class="payment-table-header">
                                    <span>Rincian Pembayaran</span>
                                    <span>Jumlah</span>
                                </div>

                                <div class="payment-table-row">
                                    <span>
                                        <i class="fa fa-stethoscope mr-2 text-muted"></i>
                                        Biaya Pemeriksaan
                                    </span>
                                    <span>Rp {{ number_format($data->biaya_pemeriksaan ?? 0, 0, ',', '.') }}</span>
                                </div>

                                <div class="payment-table-row">
                                    <span>
                                        <i class="fa fa-file-medical mr-2 text-muted"></i>
                                        Biaya Tindakan
                                    </span>
                                    <span>Rp {{ number_format($data->biaya_tindakan ?? 0, 0, ',', '.') }}</span>
                                </div>

                                <div class="payment-table-row">
                                    <span>
                                        <i class="fa fa-pills mr-2 text-muted"></i>
                                        Biaya Obat
                                    </span>
                                    <span>Rp {{ number_format($data->biaya_obat ?? 0, 0, ',', '.') }}</span>
                                </div>

                                <div class="payment-total-row">
                                    <span>TOTAL PEMBAYARAN</span>
                                    <span>Rp {{ number_format($data->total_biaya ?? 0, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="row mt-3 align-items-end">
                                <div class="col-md-7 mb-3">
                                    <div class="note-box">
                                        <div class="note-title">
                                            <i class="fa fa-file-alt mr-2"></i> Catatan
                                        </div>
                                        <p class="mb-1">
                                            Nota ini merupakan bukti pembayaran yang sah atas pelayanan pasien di OQ Clinic Dentist.
                                        </p>
                                        <p class="mb-0">
                                            Terima kasih atas kepercayaan Anda.
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-3 text-center">
                                    <div class="signature-box">
                                        <div class="signature-label">Petugas</div>
                                        <div class="signature-empty"></div>
                                        <div class="signature-line"></div>
                                        <div class="signature-name">Admin</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center mt-4 no-print">
                        <button type="button" onclick="window.print()" class="btn btn-success px-4">
                            <i class="fa fa-print"></i> Cetak Nota
                        </button>

                        <a href="{{ route('pembayaran') }}" class="btn btn-warning px-4">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .nota-wrapper {
        max-width: 920px;
    }

    .nota-card {
        background: #ffffff;
        border: 1px solid #dfe7e5;
        border-radius: 18px;
        padding: 24px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.06);
    }

    .nota-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 25px;
        flex-wrap: wrap;
    }

    .nota-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .nota-logo-box {
        width: 82px;
        height: 82px;
        border-radius: 16px;
        overflow: hidden;
        flex-shrink: 0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nota-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .nota-clinic-info h3 {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
        color: #16355c;
        letter-spacing: 0.4px;
    }

    .nota-clinic-info p {
        margin: 0;
        font-size: 13px;
        color: #6c757d;
        line-height: 1.45;
    }

    .nota-header-right {
        text-align: right;
        min-width: 230px;
    }

    .nota-header-right h4 {
        font-size: 25px;
        font-weight: 800;
        color: #0f7f74;
        margin-bottom: 8px;
        letter-spacing: 0.4px;
    }

    .nota-header-right p {
        color: #6c757d;
        font-size: 13px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.4px;
    }

    .status-lunas {
        background: #dcfce7;
        color: #047857;
        border: 1px solid #86efac;
    }

    .status-belum {
        background: #ffedd5;
        color: #c2410c;
        border: 1px solid #fdba74;
    }

    .nota-divider {
        border-top: 2px solid #e8efed;
        margin-top: 18px;
    }

    .info-card {
        border: 1px solid #dfe7e5;
        border-radius: 14px;
        padding: 15px;
        background: #fbfdfc;
        height: 100%;
    }

    .info-title {
        font-weight: 800;
        color: #0f7f74;
        margin-bottom: 12px;
        font-size: 15px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px dashed #d8dfde;
        padding: 7px 0;
        gap: 12px;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row span {
        color: #6c757d;
        font-size: 13px;
    }

    .info-row strong {
        color: #1d1d1d;
        font-size: 13px;
        text-align: right;
        font-weight: 800;
    }

    .payment-table {
        border: 1px solid #dfe7e5;
        border-radius: 14px;
        overflow: hidden;
    }

    .payment-table-header {
        background: #0f7f74;
        color: #ffffff;
        font-weight: 800;
        display: flex;
        justify-content: space-between;
        padding: 12px 16px;
        font-size: 15px;
    }

    .payment-table-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 16px;
        border-bottom: 1px solid #edf2f1;
        font-size: 14px;
    }

    .payment-table-row span:last-child {
        font-weight: 700;
        color: #1f2933;
    }

    .payment-total-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 16px;
        background: linear-gradient(90deg, #0f7f74, #00856f);
        font-weight: 900;
        color: #ffffff;
        font-size: 22px;
    }

    .note-box {
        border: 1px solid #dfe7e5;
        border-left: 4px solid #0f7f74;
        border-radius: 12px;
        padding: 14px;
        background: #fbfdfc;
        height: 100%;
    }

    .note-title {
        font-weight: 800;
        margin-bottom: 7px;
        color: #1f1f1f;
    }

    .note-box p {
        font-size: 12px;
        color: #5f6f6b;
        line-height: 1.5;
    }

    .signature-box {
        text-align: center;
        padding-top: 12px;
    }

    .signature-label {
        color: #6c757d;
        font-size: 14px;
    }

    .signature-empty {
        height: 42px;
    }

    .signature-line {
        width: 170px;
        border-bottom: 2px solid #8da5a1;
        margin: 0 auto 10px;
    }

    .signature-name {
        font-size: 15px;
        font-weight: 800;
        color: #000000;
    }

    /* PRINT: KERTAS A4 PORTRAIT, NOTA SETENGAH ATAS */
    @page {
        size: A4 portrait;
        margin: 0;
    }

    @media print {
        html,
        body {
            width: 210mm !important;
            height: 297mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            overflow: hidden !important;
        }

        body * {
            visibility: hidden !important;
        }

        #printArea,
        #printArea * {
            visibility: visible !important;
        }

        #printArea {
        position: fixed !important;
            left: 3mm !important;
            top: 3mm !important;
            width: 204mm !important;
            height: 145mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            overflow: hidden !important;
        }

        .no-print,
        .deznav,
        .header,
        .nav-header,
        .footer,
        .hamburger {
            display: none !important;
            visibility: hidden !important;
        }

        .container-fluid,
        .card,
        .card-body,
        .col-xl-12 {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #ffffff !important;
        }

       .nota-wrapper {
            width: 204mm !important;
            max-width: 204mm !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .nota-card {
            width: 204mm !important;
            height: 142mm !important;
            max-height: 142mm !important;
            margin: 0 !important;
            padding: 6mm !important;
            border: 1px solid #dfe7e5 !important;
            border-radius: 10px !important;
            box-shadow: none !important;
            overflow: hidden !important;
            background: #ffffff !important;
            page-break-before: avoid !important;
            page-break-after: avoid !important;
            page-break-inside: avoid !important;
        }

        .nota-header {
            display: flex !important;
            justify-content: space-between !important;
            align-items: flex-start !important;
            gap: 12px !important;
            flex-wrap: nowrap !important;
        }

        .nota-header-left {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            flex: 1 !important;
        }

        .nota-logo-box {
            width: 16mm !important;
            height: 16mm !important;
            border-radius: 8px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .nota-logo {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
        }

        .nota-clinic-info h3 {
            font-size: 15px !important;
            margin: 0 !important;
            line-height: 1.1 !important;
        }

        .nota-clinic-info p {
            font-size: 8px !important;
            line-height: 1.3 !important;
            margin: 0 !important;
        }

        .nota-header-right {
            text-align: right !important;
            min-width: 55mm !important;
        }

        .nota-header-right h4 {
            font-size: 17px !important;
            margin: 0 0 3px !important;
            line-height: 1.1 !important;
        }

        .nota-header-right p {
            font-size: 8px !important;
            margin: 0 0 4px !important;
        }

        .status-badge {
            padding: 3px 8px !important;
            font-size: 8px !important;
        }

        .nota-divider {
            margin: 4mm 0 !important;
            border-top: 1px solid #e8efed !important;
        }

        #printArea .row {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 4mm !important;
            margin: 0 !important;
        }

        #printArea .col-md-6 {
            width: 50% !important;
            max-width: 50% !important;
            flex: 0 0 calc(50% - 2mm) !important;
            padding: 0 !important;
        }

        #printArea .col-md-7 {
            width: 58% !important;
            max-width: 58% !important;
            flex: 0 0 calc(58% - 2mm) !important;
            padding: 0 !important;
        }

        #printArea .col-md-5 {
            width: 42% !important;
            max-width: 42% !important;
            flex: 0 0 calc(42% - 2mm) !important;
            padding: 0 !important;
        }

        .mb-3 {
            margin-bottom: 3mm !important;
        }

        .mt-3,
        .mt-4 {
            margin-top: 3mm !important;
        }

        .info-card {
            padding: 3mm !important;
            border-radius: 8px !important;
        }

        .info-title {
            font-size: 9px !important;
            margin-bottom: 2mm !important;
        }

        .info-row {
            padding: 1.3mm 0 !important;
            gap: 2mm !important;
            flex-direction: row !important;
            align-items: center !important;
        }

        .info-row span,
        .info-row strong {
            font-size: 8px !important;
            line-height: 1.25 !important;
        }

        .payment-table {
            border-radius: 8px !important;
        }

        .payment-table-header {
            padding: 2.2mm 3mm !important;
            font-size: 9px !important;
        }

        .payment-table-row {
            padding: 2mm 3mm !important;
            font-size: 8px !important;
        }

        .payment-total-row {
            padding: 2.8mm 3mm !important;
            font-size: 13px !important;
        }

        .note-box {
            padding: 3mm !important;
            border-radius: 8px !important;
        }

        .note-title {
            font-size: 9px !important;
            margin-bottom: 1mm !important;
        }

        .note-box p {
            font-size: 7px !important;
            line-height: 1.3 !important;
            margin: 1mm 0 !important;
        }

        .signature-box {
            text-align: center !important;
            padding-top: 2mm !important;
        }

        .signature-label {
            font-size: 9px !important;
        }

        .signature-empty {
            height: 12mm !important;
        }

        .signature-line {
            width: 38mm !important;
            border-bottom: 1.5px solid #8da5a1 !important;
            margin: 0 auto 2mm !important;
        }

        .signature-name {
            font-size: 9px !important;
            font-weight: 800 !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    @media (max-width: 768px) {
        .nota-header {
            flex-direction: column;
        }

        .nota-header-right {
            text-align: left;
        }

        .nota-header-left {
            align-items: flex-start;
        }

        .nota-clinic-info h3 {
            font-size: 22px;
        }

        .payment-table-header,
        .payment-table-row {
            font-size: 14px;
        }

        .payment-total-row {
            font-size: 18px;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-row strong {
            text-align: left;
        }
    }
</style>

<script>
    // Cetak nota langsung dari tombol: onclick="window.print()"
</script>
@endsection