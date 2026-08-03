<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $datas = User::where('role', '!=', 3)
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('phone', 'LIKE', "%{$request->keyword}%");
            })
            ->get();

        return view('petugas.index', compact('datas'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        DB::beginTransaction();

        try {
            User::create([
                'name' => $request->name,
                'email' => preg_replace('/[^0-9]/', '', $request->phone) . '@oqclinic.local',
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'status' => 1
            ]);

            DB::commit();

            return redirect()->route('petugas')->with('sukses', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('petugas')->with('gagal', 'Data gagal ditambahkan: ' . $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required',
            'role' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            $cekPhone = User::where('phone', $request->phone)
                ->where('id', '!=', $id)
                ->first();

            if ($cekPhone) {
                DB::rollBack();
                return redirect()->route('petugas')->with('gagal', 'No HP sudah digunakan');
            }

            $user->update([
                'name' => $request->name,
                'email' => preg_replace('/[^0-9]/', '', $request->phone) . '@oqclinic.local',
                'phone' => $request->phone,
                'role' => $request->role,
                'status' => 1
            ]);

            DB::commit();

            return redirect()->route('petugas')->with('sukses', 'Data berhasil diperbaharui');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('petugas')->with('gagal', 'Data gagal diperbaharui: ' . $th->getMessage());
        }
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
        }

        return redirect()->route('petugas')->with('sukses', 'Data berhasil dihapus');
    }
}