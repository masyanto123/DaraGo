<?php

namespace App\Http\Controllers;

use App\Models\KendaraanBus;
use App\Models\PoBus;
use App\Models\SupirPo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArmadaController extends Controller
{
    /**
     * Tampilkan daftar Master Perusahaan Otobus (PO Bus).
     */
    public function index(Request $request): View
    {
        $query = PoBus::withCount(['kendaraan', 'supir', 'armadaTrip']);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_po', 'ilike', "%{$search}%")
                    ->orWhere('alamat', 'ilike', "%{$search}%")
                    ->orWhere('kontak', 'ilike', "%{$search}%");
            });
        }

        $poList = $query->latest()->paginate(10)->withQueryString();

        return view('armada.index', compact('poList'));
    }

    /**
     * Form tambah PO Bus baru.
     */
    public function create(): View
    {
        return view('armada.create');
    }

    /**
     * Simpan data PO Bus baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_po' => ['required', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'detail' => ['nullable', 'string'],
        ]);

        $po = PoBus::create($validated);

        return redirect()->route('armada.show', $po)->with('success', 'Perusahaan Otobus (PO) berhasil ditambahkan. Silakan tambahkan unit armada kendaraan dan supir di bawah ini.');
    }

    /**
     * Tampilkan detail PO Bus beserta anakan data Armada Kendaraan & Supir.
     */
    public function show(PoBus $armada): View
    {
        $po = $armada->load(['kendaraan', 'supir']);

        return view('armada.show', compact('po'));
    }

    /**
     * Form edit data PO Bus.
     */
    public function edit(PoBus $armada): View
    {
        $po = $armada;

        return view('armada.edit', compact('po'));
    }

    /**
     * Perbarui data PO Bus.
     */
    public function update(Request $request, PoBus $armada): RedirectResponse
    {
        $validated = $request->validate([
            'nama_po' => ['required', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'alamat' => ['nullable', 'string'],
            'detail' => ['nullable', 'string'],
        ]);

        $armada->update($validated);

        return redirect()->route('armada.index')->with('success', 'Data PO Bus berhasil diperbarui.');
    }

    /**
     * Hapus data PO Bus.
     */
    public function destroy(PoBus $armada): RedirectResponse
    {
        $armada->delete();

        return redirect()->route('armada.index')->with('success', 'Perusahaan Otobus beserta seluruh unit kendaraan dan supirnya berhasil dihapus.');
    }

    /**
     * Tambah Anakan Data: Armada Kendaraan ke PO Bus.
     */
    public function storeKendaraan(Request $request, PoBus $po): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_bus' => ['required', 'string', 'max:50'],
            'tipe_bus' => ['nullable', 'string', 'max:100'],
            'kapasitas' => ['nullable', 'integer', 'min:1', 'max:100'],
            'fasilitas' => ['nullable', 'string'],
        ]);

        $po->kendaraan()->create($validated);

        return back()->with('success', 'Unit armada kendaraan berhasil ditambahkan ke PO '.$po->nama_po.'.');
    }

    /**
     * Hapus Anakan Data: Armada Kendaraan.
     */
    public function destroyKendaraan(KendaraanBus $kendaraan): RedirectResponse
    {
        $kendaraan->delete();

        return back()->with('success', 'Unit armada kendaraan berhasil dihapus.');
    }

    /**
     * Tambah Anakan Data: Supir Bus ke PO Bus.
     */
    public function storeSupir(Request $request, PoBus $po): RedirectResponse
    {
        $validated = $request->validate([
            'nama_supir' => ['required', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'peran' => ['required', 'string', 'in:Supir Utama,Co-Supir'],
        ]);

        $po->supir()->create($validated);

        return back()->with('success', 'Data supir berhasil ditambahkan ke PO '.$po->nama_po.'.');
    }

    /**
     * Hapus Anakan Data: Supir Bus.
     */
    public function destroySupir(SupirPo $supir): RedirectResponse
    {
        $supir->delete();

        return back()->with('success', 'Data supir berhasil dihapus.');
    }
}
