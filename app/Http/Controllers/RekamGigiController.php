<?php

namespace App\Http\Controllers;

use App\Models\KondisiGigi;
use App\Models\Rekam;
use App\Models\RekamGigi;
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekamGigiController extends Controller
{
    public function odontogram(Request $request, $pasienId)
    {
        $rekam = Rekam::orderBy('id', 'desc')
            ->where('poli', 'Poli Gigi')
            ->where('pasien_id', $pasienId)
            ->first();

        if (!$rekam) {
            return redirect()->route('rekam.detail', $pasienId)
                ->with('gagal', 'Odontogram tidak ditemukan');
        }

        $pem_gigi = RekamGigi::where('rekam_id', $rekam->id)->get();

        $elemen_gigis = "";
        $pemeriksaan_gigi = "";
        $i = 0;

        $all_riwayat_gigi = RekamGigi::where('pasien_id', $pasienId)->get();
        $numItems = $all_riwayat_gigi->count();

        foreach ($all_riwayat_gigi as $key => $value) {
            $elemen_gigis .= $value->elemen_gigi;
            $pemeriksaan_gigi .= $value->pemeriksaan;

            if (++$i != $numItems) {
                $elemen_gigis .= ",";
                $pemeriksaan_gigi .= ",";
            }
        }

        return view('rekam.odontogram', compact(
            'rekam',
            'elemen_gigis',
            'pemeriksaan_gigi',
            'all_riwayat_gigi'
        ));
    }

    public function index(Request $request, $rekamId)
    {
        $rekam = Rekam::findOrFail($rekamId);

        $tindakan = Tindakan::where('poli', 'Poli Gigi')->get();
        $kondisi_gigi = KondisiGigi::all();
        $pem_gigi = RekamGigi::where('rekam_id', $rekamId)->get();

        $elemen_gigis = "";
        $pemeriksaan_gigi = "";
        $numItems = $pem_gigi->count();
        $i = 0;

        foreach ($pem_gigi as $key => $value) {
            $elemen_gigis .= $value->elemen_gigi;
            $pemeriksaan_gigi .= $value->pemeriksaan;

            if (++$i != $numItems) {
                $elemen_gigis .= ",";
                $pemeriksaan_gigi .= ",";
            }
        }

        return view('rekam.rekam-gigi', compact(
            'rekam',
            'tindakan',
            'kondisi_gigi',
            'elemen_gigis',
            'pemeriksaan_gigi',
            'pem_gigi'
        ));
    }

    public function store(Request $request, $rekamId)
    {
        $rekam = Rekam::findOrFail($rekamId);

        if (!$request->element_gigi) {
            return redirect()->back()
                ->with('gagal', 'Tambahkan dulu rincian pemeriksaan baru menyimpan data');
        }

        try {
            DB::beginTransaction();

            if ($request->element_gigi) {
                foreach ($request->element_gigi as $i => $elementId) {
                    RekamGigi::updateOrCreate(
                        [
                            'rekam_id' => $rekamId,
                            'pasien_id' => $rekam->pasien_id,
                            'elemen_gigi' => $elementId,
                        ],
                        [
                            'rekam_id' => $rekamId,
                            'pasien_id' => $rekam->pasien_id,
                            'elemen_gigi' => $elementId,
                            'pemeriksaan' => $request->pemeriksaan[$i] ?? null,
                            'diagnosa' => $request->diagnosa[$i] ?? null,
                            'tindakan' => $request->tindakan[$i] ?? null,
                            'catatan' => $request->catatan[$i] ?? null,
                            'status' => 1,
                        ]
                    );
                }
            }

            $this->updateBiayaTindakanRekam($rekamId);

            DB::commit();

            return redirect()->route('rekam.detail', $rekam->pasien_id)
                ->with('sukses', 'Rekam Gigi Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('rekam.gigi.add', $rekamId)
                ->with('gagal', 'Data gagal ditambahkan: ' . $e->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        $data = RekamGigi::findOrFail($id);
        $rekamId = $data->rekam_id;

        $data->delete();

        $this->updateBiayaTindakanRekam($rekamId);

        return redirect()->route('rekam.gigi.add', $rekamId)
            ->with('sukses', 'Data berhasil dihapus');
    }

    private function updateBiayaTindakanRekam($rekamId)
    {
        $rekam = Rekam::find($rekamId);

        if (!$rekam) {
            return;
        }

        $rekamGigis = RekamGigi::where('rekam_id', $rekamId)->get();

        $totalBiayaTindakan = 0;

        foreach ($rekamGigis as $rekamGigi) {
            $tindakanValue = $rekamGigi->tindakan;

            if (!$tindakanValue) {
                continue;
            }

            $tindakan = null;

            if (is_numeric($tindakanValue)) {
                $tindakan = Tindakan::find($tindakanValue);
            }

            if (!$tindakan) {
                $tindakan = Tindakan::where('nama', $tindakanValue)
                    ->orWhere('kode', $tindakanValue)
                    ->first();
            }

            if ($tindakan) {
                $totalBiayaTindakan += (int) $tindakan->harga;
            }
        }

        $biayaPemeriksaan = (int) ($rekam->biaya_pemeriksaan ?? 0);
        $biayaObat = (int) ($rekam->biaya_obat ?? 0);

        $rekam->biaya_tindakan = $totalBiayaTindakan;
        $rekam->total_biaya = $biayaPemeriksaan + $totalBiayaTindakan + $biayaObat;
        $rekam->save();
    }
}