<?php

namespace App\Http\Controllers;

use App\Models\DestinasiWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = DestinasiWisata::withCount('jadwalAcara');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_tempat', 'ilike', "%{$search}%")
                    ->orWhere('deskripsi', 'ilike', "%{$search}%")
                    ->orWhere('aturan_lokal', 'ilike', "%{$search}%");
            });
        }

        $destinasi = $query->latest()->paginate(15)->withQueryString();
        $kategoriList = collect();

        return view('destinasi.index', compact('destinasi', 'kategoriList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kategoriList = collect();

        return view('destinasi.create', compact('kategoriList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tempat' => ['required_without:nama_wisata', 'nullable', 'string', 'max:255'],
            'nama_wisata' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'aturan_lokal' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $namaTempat = $request->input('nama_tempat') ?? $request->input('nama_wisata');

        DestinasiWisata::create([
            'nama_tempat' => $namaTempat,
            'deskripsi' => $request->input('deskripsi'),
            'aturan_lokal' => $request->input('aturan_lokal'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return redirect()
            ->route('destinasi.index')
            ->with('success', 'Destinasi wisata berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DestinasiWisata $destinasi): View
    {
        $kategoriList = collect();

        return view('destinasi.edit', compact('destinasi', 'kategoriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DestinasiWisata $destinasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tempat' => ['required_without:nama_wisata', 'nullable', 'string', 'max:255'],
            'nama_wisata' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'aturan_lokal' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $namaTempat = $request->input('nama_tempat') ?? $request->input('nama_wisata');

        $destinasi->update([
            'nama_tempat' => $namaTempat,
            'deskripsi' => $request->input('deskripsi'),
            'aturan_lokal' => $request->input('aturan_lokal'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
        ]);

        return redirect()
            ->route('destinasi.index')
            ->with('success', 'Data destinasi wisata berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestinasiWisata $destinasi): RedirectResponse
    {
        $destinasi->delete();

        return redirect()
            ->route('destinasi.index')
            ->with('success', 'Destinasi wisata berhasil dihapus.');
    }
}
