<?php

namespace App\Http\Controllers;

use App\Models\BiroPerjalanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BiroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $biros = BiroPerjalanan::withCount(['karyawan', 'tripPerjalanan'])->latest()->paginate(10);

        return view('biro.index', compact('biros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('biro.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_biro' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'logo_url' => ['nullable', 'string', 'url'],
        ]);

        BiroPerjalanan::create($validated);

        return redirect()->route('biro.index')->with('success', 'Biro Perjalanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiroPerjalanan $biro): View
    {
        return view('biro.edit', compact('biro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BiroPerjalanan $biro): RedirectResponse
    {
        $validated = $request->validate([
            'nama_biro' => ['required', 'string', 'max:255'],
            'alamat' => ['nullable', 'string'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'logo_url' => ['nullable', 'string', 'url'],
        ]);

        $biro->update($validated);

        return redirect()->route('biro.index')->with('success', 'Data Biro Perjalanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BiroPerjalanan $biro): RedirectResponse
    {
        $biro->delete();

        return redirect()->route('biro.index')->with('success', 'Biro Perjalanan berhasil dihapus.');
    }
}
