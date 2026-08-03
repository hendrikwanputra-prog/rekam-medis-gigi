@extends('layout.apps')

@section('content')
<div class="mr-auto">
    <h2 class="text-black font-w600">Input Pembayaran</h2>
    <p>Form transaksi pembayaran pasien OQ Clinic Dentist.</p>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-4">
                    <h4 class="text-black">Detail Rekam Medis</h4>
                    <p><strong>No Rekam:</strong> {{ $data->no_rekam }}</p>
                    <p><strong>Tanggal:</strong> {{ $data->tgl_rekam }}</p>
                    <p><strong>Nama Pasien:</strong> {{ $data->pasien->nama ?? '-' }}</p>
                    <p><strong>Dokter:</strong> {{ $data->dokter->nama ?? '-' }}</p>
                    <p><strong>Keluhan:</strong> {{ $data->keluhan ?? '-' }}</p>
                </div>

                <form action="{{ route('pembayaran.update', $data->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Biaya Pemeriksaan</label>
                                <input type="number" 
                                       name="biaya_pemeriksaan" 
                                       id="biaya_pemeriksaan"
                                       class="form-control hitung-total" 
                                       value="{{ old('biaya_pemeriksaan', $data->biaya_pemeriksaan ?? 0) }}" 
                                       min="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Biaya Tindakan</label>
                                <input type="number" 
                                       name="biaya_tindakan" 
                                       id="biaya_tindakan"
                                       class="form-control hitung-total" 
                                       value="{{ old('biaya_tindakan', $data->biaya_tindakan ?? 0) }}" 
                                       min="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Biaya Obat</label>
                                <input type="number" 
                                       name="biaya_obat" 
                                       id="biaya_obat"
                                       class="form-control hitung-total" 
                                       value="{{ old('biaya_obat', $data->biaya_obat ?? 0) }}" 
                                       min="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cara Bayar</label>
                                <select name="cara_bayar" class="form-control">
                                    <option value="Umum/Mandiri" {{ $data->cara_bayar == 'Umum/Mandiri' ? 'selected' : '' }}>
                                        Umum/Mandiri
                                    </option>
                                    <option value="BPJS" {{ $data->cara_bayar == 'BPJS' ? 'selected' : '' }}>
                                        BPJS
                                    </option>
                                    <option value="Asuransi" {{ $data->cara_bayar == 'Asuransi' ? 'selected' : '' }}>
                                        Asuransi
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <strong>Total Pembayaran:</strong> 
                                <span id="total_biaya_text">
                                    Rp {{ number_format($data->total_biaya ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            Simpan Pembayaran
                        </button>

                        <a href="{{ route('pembayaran') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function hitungTotal() {
        let pemeriksaan = parseInt(document.getElementById('biaya_pemeriksaan').value) || 0;
        let tindakan = parseInt(document.getElementById('biaya_tindakan').value) || 0;
        let obat = parseInt(document.getElementById('biaya_obat').value) || 0;

        let total = pemeriksaan + tindakan + obat;

        document.getElementById('total_biaya_text').innerText = formatRupiah(total);
    }

    document.querySelectorAll('.hitung-total').forEach(function(input) {
        input.addEventListener('input', hitungTotal);
    });

    hitungTotal();
</script>
@endsection