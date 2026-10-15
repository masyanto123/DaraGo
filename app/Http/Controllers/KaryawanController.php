<?php

namespace App\Http\Controllers;

use App\Models\BiroPerjalanan;
use App\Models\Karyawan;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $karyawans = Karyawan::with(['user', 'biro', 'po', 'supirPo'])->latest()->paginate(10);

        return view('karyawan.index', compact('karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $biros = BiroPerjalanan::orderBy('nama_biro')->get();
        $poList = PoBus::orderBy('nama_po')->get();

        return view('karyawan.create', compact('biros', 'poList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'kategori_personil' => ['required', 'in:karyawan_biro,driver_po'],
            'biro_id' => ['required_if:kategori_personil,karyawan_biro', 'nullable', 'exists:biro_perjalanan,id'],
            'po_id' => ['required_if:kategori_personil,driver_po', 'nullable', 'exists:po_bus,id'],
            'peran_driver' => [
                $request->input('kategori_personil') === 'driver_po' && $request->input('role') === 'user'
                    ? 'required'
                    : 'nullable',
                'string',
                'in:Supir Utama,Co-Supir',
            ],
            'role' => ['required', 'in:admin,user'],
        ], [
            'biro_id.required_if' => 'Silakan pilih Biro Perjalanan untuk Karyawan Biro.',
            'po_id.required_if' => 'Silakan pilih PO Bus dari Data Master untuk Driver PO.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'password' => Hash::make($request->string('password')),
                'no_hp' => $request->string('no_hp'),
                'role' => $request->string('role'),
                'email_verified_at' => now(),
            ]);

            $supirPoId = null;
            $poId = null;
            $biroId = null;

            if ($request->input('kategori_personil') === 'driver_po') {
                $poId = $request->filled('po_id') ? $request->integer('po_id') : null;
                $isDriver = $request->input('role') === 'user';

                if ($isDriver && $poId) {
                    $posisi = $request->string('peran_driver');
                    $supirPo = SupirPo::create([
                        'po_id' => $poId,
                        'nama_supir' => $request->string('name'),
                        'kontak' => $request->string('no_hp'),
                        'peran' => $posisi,
                        'keterangan' => 'Akun Login: '.$request->string('email'),
                    ]);
                    $supirPoId = $supirPo->id;
                } else {
                    $posisi = 'Admin PO';
                }
            } else {
                $biroId = $request->filled('biro_id') ? $request->integer('biro_id') : null;
                $posisi = 'Tour Leader / Staf Biro';
            }

            Karyawan::create([
                'user_id' => $user->id,
                'biro_id' => $biroId,
                'po_id' => $poId,
                'supir_po_id' => $supirPoId,
                'posisi' => $posisi,
            ]);
        });

        return redirect()->route('karyawan.index')->with('success', 'Data personil berhasil ditambahkan dan terhubung dengan master data.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karyawan $karyawan): View
    {
        $biros = BiroPerjalanan::orderBy('nama_biro')->get();
        $poList = PoBus::orderBy('nama_po')->get();

        return view('karyawan.edit', compact('karyawan', 'biros', 'poList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karyawan $karyawan): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$karyawan->user_id],
            'password' => ['nullable', 'string', 'min:6'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'kategori_personil' => ['required', 'in:karyawan_biro,driver_po'],
            'biro_id' => ['required_if:kategori_personil,karyawan_biro', 'nullable', 'exists:biro_perjalanan,id'],
            'po_id' => ['required_if:kategori_personil,driver_po', 'nullable', 'exists:po_bus,id'],
            'peran_driver' => [
                $request->input('kategori_personil') === 'driver_po' && $request->input('role') === 'user'
                    ? 'required'
                    : 'nullable',
                'string',
                'in:Supir Utama,Co-Supir',
            ],
            'role' => ['required', 'in:admin,user,superadmin'],
        ], [
            'biro_id.required_if' => 'Silakan pilih Biro Perjalanan untuk Karyawan Biro.',
            'po_id.required_if' => 'Silakan pilih PO Bus dari Data Master untuk Driver PO.',
        ]);

        DB::transaction(function () use ($request, $karyawan) {
            $userData = [
                'name' => $request->string('name'),
                'email' => $request->string('email'),
                'no_hp' => $request->string('no_hp'),
                'role' => $request->string('role'),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->string('password'));
            }

            $karyawan->user->update($userData);

            $poId = null;
            $biroId = null;
            $supirPoId = $karyawan->supir_po_id;

            if ($request->input('kategori_personil') === 'driver_po') {
                $poId = $request->filled('po_id') ? $request->integer('po_id') : null;
                $isDriver = $request->input('role') === 'user';

                if ($isDriver && $poId) {
                    $posisi = $request->string('peran_driver');
                    if ($karyawan->supirPo) {
                        $karyawan->supirPo->update([
                            'po_id' => $poId,
                            'nama_supir' => $request->string('name'),
                            'kontak' => $request->string('no_hp'),
                            'peran' => $posisi,
                            'keterangan' => 'Akun Login: '.$request->string('email'),
                        ]);
                    } else {
                        $supirPo = SupirPo::create([
                            'po_id' => $poId,
                            'nama_supir' => $request->string('name'),
                            'kontak' => $request->string('no_hp'),
                            'peran' => $posisi,
                            'keterangan' => 'Akun Login: '.$request->string('email'),
                        ]);
                        $supirPoId = $supirPo->id;
                    }
                } else {
                    $posisi = 'Admin PO';

                    if ($karyawan->supirPo) {
                        $karyawan->supirPo->delete();
                        $supirPoId = null;
                    }
                }
            } else {
                $biroId = $request->filled('biro_id') ? $request->integer('biro_id') : null;
                $posisi = 'Tour Leader / Staf Biro';

                if ($karyawan->supirPo) {
                    $karyawan->supirPo->delete();
                    $supirPoId = null;
                }
            }

            $karyawan->update([
                'biro_id' => $biroId,
                'po_id' => $poId,
                'supir_po_id' => $supirPoId,
                'posisi' => $posisi,
            ]);
        });

        return redirect()->route('karyawan.index')->with('success', 'Data personil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karyawan $karyawan): RedirectResponse
    {
        $user = $karyawan->user;
        if ($karyawan->supirPo) {
            $karyawan->supirPo->delete();
        }
        $karyawan->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan / Crew berhasil dihapus.');
    }
}
