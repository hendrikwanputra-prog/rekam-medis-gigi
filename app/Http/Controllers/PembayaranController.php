<?php

namespace App\Http\Controllers;

use App\Models\Rekam;
use App\Models\Dokter;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ? $request->status : 'belum';

        $query = Rekam::with(['pasien', 'dokter']);

        // Filter status pembayaran
        if ($status == 'lunas') {
            // Tab Lunas Hari Ini hanya menampilkan pembayaran yang sudah diinput admin hari ini
            $query->where('status_pembayaran', 'lunas')
                ->whereDate('tanggal_bayar', date('Y-m-d'));
        } else {
            // Tab Belum Bayar menampilkan semua tagihan yang belum dibayar
            $query->where(function ($q) {
                $q->whereNull('status_pembayaran')
                    ->orWhere('status_pembayaran', 'belum_bayar');
            });
        }

        // Pencarian data
        $query->when($request->keyword, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('no_rekam', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('tgl_rekam', 'LIKE', "%{$request->keyword}%")
                    ->orWhereHas('pasien', function ($pasien) use ($request) {
                        $pasien->where('nama', 'LIKE', "%{$request->keyword}%");
                    })
                    ->orWhereHas('dokter', function ($dokter) use ($request) {
                        $dokter->where('nama', 'LIKE', "%{$request->keyword}%");
                    });
            });
        });

        $datas = $query->latest()->paginate(10);

        $jumlahBelumBayar = Rekam::where(function ($q) {
            $q->whereNull('status_pembayaran')
                ->orWhere('status_pembayaran', 'belum_bayar');
        })->count();

        $jumlahLunasHariIni = Rekam::where('status_pembayaran', 'lunas')
            ->whereDate('tanggal_bayar', date('Y-m-d'))
            ->count();

        return view('pembayaran.index', compact(
            'datas',
            'status',
            'jumlahBelumBayar',
            'jumlahLunasHariIni'
        ));
    }

    public function edit($id)
    {
        $data = Rekam::with(['pasien', 'dokter'])->findOrFail($id);

        return view('pembayaran.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'biaya_pemeriksaan' => 'nullable|numeric|min:0',
            'biaya_tindakan' => 'nullable|numeric|min:0',
            'biaya_obat' => 'nullable|numeric|min:0',
            'cara_bayar' => 'required',
        ]);

        $rekam = Rekam::findOrFail($id);

        $biayaPemeriksaan = $request->biaya_pemeriksaan ?? 0;
        $biayaTindakan = $request->biaya_tindakan ?? 0;
        $biayaObat = $request->biaya_obat ?? 0;

        $totalBiaya = $biayaPemeriksaan + $biayaTindakan + $biayaObat;

        /*
         * Pembayaran dianggap LUNAS hanya ketika admin menyimpan input pembayaran.
         * Dokter atau proses obat tidak boleh membuat status pembayaran otomatis lunas.
         */
        $rekam->update([
            'biaya_pemeriksaan' => $biayaPemeriksaan,
            'biaya_tindakan' => $biayaTindakan,
            'biaya_obat' => $biayaObat,
            'total_biaya' => $totalBiaya,
            'cara_bayar' => $request->cara_bayar,
            'status_pembayaran' => $totalBiaya > 0 ? 'lunas' : 'belum_bayar',
            'tanggal_bayar' => $totalBiaya > 0 ? date('Y-m-d') : null,
        ]);

        return redirect()
            ->route('pembayaran', ['status' => $totalBiaya > 0 ? 'lunas' : 'belum'])
            ->with('sukses', 'Data pembayaran berhasil diperbarui');
    }

    public function laporan(Request $request)
    {
        $query = Rekam::with(['pasien', 'dokter'])
            ->where('status_pembayaran', 'lunas');

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal_bayar', [
                $request->tanggal_awal,
                $request->tanggal_akhir
            ]);
        }

        if ($request->dokter_id) {
            $query->where('dokter_id', $request->dokter_id);
        }

        $datas = $query->latest()->get();

        $totalPendapatan = $datas->sum('total_biaya');
        $jumlahTransaksi = $datas->count();
        $transaksiLunas = $datas->where('status_pembayaran', 'lunas')->count();

        $transaksiBelumBayar = Rekam::where(function ($q) {
            $q->whereNull('status_pembayaran')
                ->orWhere('status_pembayaran', 'belum_bayar');
        })->count();

        $dokters = Dokter::orderBy('nama', 'asc')->get();

        return view('pembayaran.laporan', compact(
            'datas',
            'totalPendapatan',
            'jumlahTransaksi',
            'transaksiLunas',
            'transaksiBelumBayar',
            'dokters'
        ));
    }

    public function nota($id)
    {
        $data = Rekam::with(['pasien', 'dokter'])->findOrFail($id);

        if ($data->status_pembayaran != 'lunas') {
            return redirect()
                ->route('pembayaran', ['status' => 'belum'])
                ->with('gagal', 'Nota hanya dapat dicetak setelah pembayaran lunas');
        }

        return view('pembayaran.nota', compact('data'));
    }
}