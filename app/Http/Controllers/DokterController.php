<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\Rekam;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $datas = Dokter::all();
        $poli = Poli::all();

        return view('dokter.index', compact('datas', 'poli'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => 'required',
            'poli' => 'required',
            'password' => 'required|min:6'
        ]);

        DB::beginTransaction();

        try {
            $cekUser = User::where('phone', $request->no_hp)->first();

            if ($cekUser) {
                DB::rollBack();
                return redirect()->route('dokter')->with('gagal', 'No HP login sudah digunakan');
            }

                $user = User::create([
                    'name' => $request->nama,
                    'email' => preg_replace('/[^0-9]/', '', $request->no_hp) . '@oqclinic.local',
                    'phone' => $request->no_hp,
                    'password' => bcrypt($request->password),
                    'role' => 3,
                    'status' => 1
                ]);

            Dokter::create([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'poli' => $request->poli ?? 'Poli Gigi',
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'status' => 1,
                'user_id' => $user->id
            ]);

            DB::commit();

            return redirect()->route('dokter')->with('sukses', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('dokter')->with('gagal', 'Data gagal ditambahkan: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'required',
            'no_hp' => 'required',
            'poli' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $dokter = Dokter::findOrFail($id);

            $dokter->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'poli' => $request->poli ?? 'Poli Gigi',
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat
            ]);

            if ($dokter->user_id) {
                $user = User::find($dokter->user_id);

                if ($user) {
                    $user->update([
                        'name' => $request->nama,
                        'phone' => $request->no_hp
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('dokter')->with('sukses', 'Data berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('dokter')->with('gagal', 'Data gagal diperbaharui: ' . $th->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        $rekam = Rekam::where('dokter_id', $id)->count();

        if ($rekam >= 1) {
            $dokter = Dokter::find($id);

            if ($dokter) {
                $dokter->update([
                    'status' => 0
                ]);

                if ($dokter->user_id) {
                    $user = User::find($dokter->user_id);

                    if ($user) {
                        $user->update([
                            'status' => 0
                        ]);
                    }
                }
            }

            return redirect()->route('dokter')->with('sukses', 'Data dokter di non aktifkan');
        } else {
            $dokter = Dokter::find($id);

            if ($dokter) {
                $userId = $dokter->user_id;

                $dokter->delete();

                if ($userId) {
                    $user = User::find($userId);

                    if ($user) {
                        $user->delete();
                    }
                }
            }
        }

        return redirect()->route('dokter')->with('sukses', 'Data berhasil dihapus');
    }

    public function getDokter(Request $request)
    {
        $data = Dokter::select('id', 'nama')
            ->where('status', 1)
            ->get();

        if ($poli = $request->get('poli')) {
            $data = Dokter::select('id', 'nama')
                ->where('status', 1)
                ->where('poli', $poli)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

    public function updatepassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|min:6',
            'password_konfirm' => 'required_with:password|same:password|min:6'
        ]);

        $password = bcrypt($request->password);

        User::where('id', $id)->update([
            'password' => $password,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('dokter')->with('sukses', 'Selamat, password anda sudah diperbaharui');
    }
}