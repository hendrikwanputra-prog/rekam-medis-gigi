<?php

namespace App\Http\Controllers;

use App\Events\StatusRekamUpdate;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\PengeluaranObat;
use App\Models\Poli;
use App\Models\Rekam;
use App\Models\RekamGigi;
use App\Notifications\RekamUpdateNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification as Notification;

class RekamController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->role_display();

        // Default tab dokter: Perlu Diperiksa
        $tab = $request->tab;
        if ($role == "Dokter" && $tab == null) {
            $tab = 2;
        }

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;

        $rekams = Rekam::latest()
            ->select('rekam.*')
            ->leftJoin('pasien', function ($join) {
                $join->on('rekam.pasien_id', '=', 'pasien.id');
            })

            // Pencarian data
            ->when($request->keyword, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('rekam.tgl_rekam', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('rekam.no_rekam', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('rekam.cara_bayar', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('pasien.nama', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('pasien.no_bpjs', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('pasien.no_rm', 'LIKE', "%{$request->keyword}%");
                });
            })

            // Role dokter hanya melihat data rekam medis miliknya
            ->when($role == "Dokter", function ($query) use ($user) {
                $dokter = Dokter::where('user_id', $user->id)
                    ->where('status', 1)
                    ->first();

                if ($dokter) {
                    $query->where('rekam.dokter_id', $dokter->id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })

            // Filter tab/status
            ->when($tab, function ($query) use ($tab, $role) {
                if ($role == "Dokter") {
                    if ($tab == 2) {
                        // Pasien yang perlu diperiksa dokter
                        $query->where('rekam.status', 2);
                    } elseif ($tab == 5) {
                        // Pasien selesai diperiksa dokter
                        $query->where('rekam.status', 5);
                    }
                } else {
                    if ($tab == 5) {
                        $query->whereIn('rekam.status', [4, 5]);
                    } else {
                        $query->where('rekam.status', $tab);
                    }
                }
            })

            // Filter tanggal khusus dokter pada menu Selesai Diperiksa
            ->when($role == "Dokter" && $tab == 5 && $tgl_awal && $tgl_akhir, function ($query) use ($tgl_awal, $tgl_akhir) {
                $query->whereBetween('rekam.tgl_rekam', [$tgl_awal, $tgl_akhir]);
            })

            ->when($role == "Dokter" && $tab == 5 && $tgl_awal && !$tgl_akhir, function ($query) use ($tgl_awal) {
                $query->whereDate('rekam.tgl_rekam', '>=', $tgl_awal);
            })

            ->when($role == "Dokter" && $tab == 5 && !$tgl_awal && $tgl_akhir, function ($query) use ($tgl_akhir) {
                $query->whereDate('rekam.tgl_rekam', '<=', $tgl_akhir);
            })

            ->paginate(10);

        return view('rekam.index', compact('rekams', 'tab', 'tgl_awal', 'tgl_akhir'));
    }

    public function add(Request $request)
    {
        $poli = Poli::all();
        return view('rekam.add', compact('poli'));
    }

    public function edit(Request $request, $id)
    {
        $poli = Poli::all();
        $data = Rekam::find($id);

        if (!$data) {
            return redirect()->route('rekam')->with('gagal', 'Data rekam medis tidak ditemukan');
        }

        return view('rekam.edit', compact('data', 'poli'));
    }

    public function detail(Request $request, $pasien_id)
    {
        $pasien = Pasien::find($pasien_id);

        if (!$pasien) {
            return redirect()->route('rekam')->with('gagal', 'Data pasien tidak ditemukan');
        }

        $rekamLatest = Rekam::latest()
            ->where('status', '!=', 5)
            ->where('pasien_id', $pasien_id)
            ->first();

        $rekams = Rekam::latest()
            ->where('pasien_id', $pasien_id)
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('tgl_rekam', 'LIKE', "%{$request->keyword}%");
            })
            ->when($request->poli, function ($query) use ($request) {
                $query->where('poli', 'LIKE', "%{$request->poli}%");
            })
            ->paginate(5);

        if ($rekamLatest) {
            auth()->user()->notifications
                ->where('data.no_rekam', $rekamLatest->no_rekam)
                ->markAsRead();
        }

        $poli = Poli::where('status', 1)->get();

        return view('rekam.detail-rekam', compact('pasien', 'rekams', 'rekamLatest', 'poli'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tgl_rekam' => 'required',
            'pasien_id' => 'required',
            'pasien_nama' => 'required',
            'keluhan' => 'required',
            'poli' => 'required',
            'cara_bayar' => 'required',
            'dokter_id' => 'required'
        ]);

        $pasien = Pasien::where('id', $request->pasien_id)->first();

        if (!$pasien) {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors(['pasien_id' => 'Data Pasien Tidak Ditemukan']);
        }

        $rekam_ada = Rekam::where('pasien_id', $request->pasien_id)
            ->whereIn('status', [1, 2, 3, 4])
            ->first();

        if ($rekam_ada) {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors([
                    'pasien_id' => 'Pasien ini masih belum selesai periksa, harap selesaikan pemeriksaan sebelumnya'
                ]);
        }

        $request->merge([
            'no_rekam' => "REG#" . date('Ymd') . $request->pasien_id,
            'petugas_id' => auth()->user()->id,
            'status_pembayaran' => 'belum_bayar',
            'tanggal_bayar' => null,
        ]);

        Rekam::create($request->all());

        return redirect()->route('rekam.detail', $request->pasien_id)
            ->with('sukses', 'Pendaftaran Berhasil, Silakan lakukan pemeriksaan dan teruskan ke dokter terkait');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'tgl_rekam' => 'required',
            'pasien_id' => 'required',
            'pasien_nama' => 'required',
            'keluhan' => 'required',
            'poli' => 'required',
            'cara_bayar' => 'required',
            'dokter_id' => 'required'
        ]);

        $pasien = Pasien::where('id', $request->pasien_id)->first();

        if (!$pasien) {
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors(['pasien_id' => 'Data Pasien Tidak Ditemukan']);
        }

        $rekam = Rekam::find($id);

        if (!$rekam) {
            return redirect()->route('rekam')->with('gagal', 'Data rekam medis tidak ditemukan');
        }

        $rekam->update($request->all());

        return redirect()->route('rekam.detail', $request->pasien_id)
            ->with('sukses', 'Berhasil diperbaharui, Silakan lakukan pemeriksaan dan teruskan ke dokter terkait');
    }

    public function rekam_status(Request $request, $id, $status)
    {
        $rekam = Rekam::find($id);

        if (!$rekam) {
            return redirect()->route('rekam')->with('gagal', 'Data rekam medis tidak ditemukan');
        }

        if ($status == 2 && $rekam->poli != "Poli Gigi") {
            if ($rekam->pemeriksaan == null) {
                return redirect()->route('rekam.detail', $rekam->pasien_id)
                    ->with('gagal', 'Pemeriksaan isi lebih dulu');
            }
        }

        if ($status == 3) {
            if ($rekam->poli == "Poli Gigi") {
                if (RekamGigi::where('rekam_id', $id)->count() == 0) {
                    return redirect()->route('rekam.detail', $rekam->pasien_id)
                        ->with('gagal', 'Pemeriksaan, Diagnosa, Tindakan wajib diisi');
                }
            } else if ($rekam->tindakan == null) {
                return redirect()->route('rekam.detail', $rekam->pasien_id)
                    ->with('gagal', 'Tindakan dan Diagnosa belum diisi');
            }

            /*
             * Jika dokter menyelesaikan pemeriksaan tetapi belum ada resep obat,
             * maka pasien langsung dianggap selesai diperiksa.
             * Jadi tidak masuk status Di Apotek.
             */
            $adaResepObat = !empty(trim($rekam->resep_obat ?? ''));

            if (!$adaResepObat) {
                $status = 5;
            }
        }

        $rekam->update([
            'status' => $status
        ]);

        $waktu = Carbon::parse($rekam->created_at)->format('d/m/Y H:i:s');

        if ($status == 2) {
            $dokter = Dokter::find($rekam->dokter_id);

            if ($dokter) {
                $user = User::find($dokter->user_id);

                if ($user) {
                    $message = "Pasien " . $rekam->pasien->nama . ", silahkan diproses";
                    Notification::send($user, new RekamUpdateNotification($rekam, $message));

                    $link = Route('rekam.detail', $rekam->pasien_id);
                    event(new StatusRekamUpdate($user->id, $rekam->no_rekam, $message, $link, $waktu));
                }
            }
        } else if ($status == 3) {
            /*
             * Status 3 hanya jalan kalau benar-benar ada resep obat.
             * Kalau tidak ada resep, status sudah otomatis berubah menjadi 5 di atas.
             */
            $users = User::where('role', 4)->get();
            $message = "Obat pasien " . $rekam->pasien->nama . ", silahkan diproses";

            Notification::send($users, new RekamUpdateNotification($rekam, $message));

            foreach ($users as $item) {
                $link = Route('rekam.detail', $rekam->pasien_id);
                event(new StatusRekamUpdate($item->id, $rekam->no_rekam, $message, $link, $waktu));
            }
        } else if ($status == 4) {
            $users = User::where('role', 2)->get();
            $message = "Pembayaran pasien " . $rekam->pasien->nama . ", silahkan diproses";

            Notification::send($users, new RekamUpdateNotification($rekam, $message));

            foreach ($users as $item) {
                $link = Route('rekam.detail', $rekam->pasien_id);
                event(new StatusRekamUpdate($item->id, $rekam->no_rekam, $message, $link, $waktu));
            }
        }

        return redirect()->route('rekam.detail', $rekam->pasien_id)
            ->with('sukses', 'Status rekam medis selesai diperbaharui');
    }

    public function delete(Request $request, $id)
    {
        $rekam = Rekam::find($id);

        if (!$rekam) {
            return redirect()->route('rekam')->with('gagal', 'Data rekam medis tidak ditemukan');
        }

        $rekam->delete();

        PengeluaranObat::where('rekam_id', $id)->update([
            'deleted_at' => Carbon::now()
        ]);

        return redirect()->route('rekam')->with('sukses', 'Data berhasil dihapus');
    }
}
