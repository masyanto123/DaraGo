<?php

namespace App\Http\Controllers;

use App\Models\PesertaKlien;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pesertas = PesertaKlien::with(['user', 'manifest.armada', 'fasilitas'])
            ->latest()
            ->paginate(10);

        return view('peserta.index', compact('pesertas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('peserta.create');
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
            'nama_instansi' => ['required', 'string', 'max:255'],
            'kontak_darurat' => ['nullable', 'string', 'max:50'],
            'catatan_medis' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'password' => Hash::make($request->string('password')),
            'no_hp' => $request->string('no_hp'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        PesertaKlien::create([
            'user_id' => $user->id,
            'nama_instansi' => $request->string('nama_instansi'),
            'kontak_darurat' => $request->string('kontak_darurat'),
            'catatan_medis' => $request->string('catatan_medis'),
        ]);

        return redirect()->route('peserta.index')->with('success', 'Peserta Klien berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PesertaKlien $pesertum): View
    {
        $peserta = $pesertum;

        return view('peserta.edit', compact('peserta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PesertaKlien $pesertum): RedirectResponse
    {
        $peserta = $pesertum;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$peserta->user_id],
            'password' => ['nullable', 'string', 'min:6'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'nama_instansi' => ['required', 'string', 'max:255'],
            'kontak_darurat' => ['nullable', 'string', 'max:50'],
            'catatan_medis' => ['nullable', 'string'],
        ]);

        $userData = [
            'name' => $request->string('name'),
            'email' => $request->string('email'),
            'no_hp' => $request->string('no_hp'),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->string('password'));
        }

        $peserta->user->update($userData);

        $peserta->update([
            'nama_instansi' => $request->string('nama_instansi'),
            'kontak_darurat' => $request->string('kontak_darurat'),
            'catatan_medis' => $request->string('catatan_medis'),
        ]);

        return redirect()->route('peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PesertaKlien $pesertum): RedirectResponse
    {
        $peserta = $pesertum;
        $user = $peserta->user;
        $peserta->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil dihapus.');
    }
}
