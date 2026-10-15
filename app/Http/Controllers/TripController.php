<?php

namespace App\Http\Controllers;

use App\Models\ArmadaBus;
use App\Models\BiroPerjalanan;
use App\Models\DestinasiWisata;
use App\Models\JadwalAcara;
use App\Models\Karyawan;
use App\Models\KendaraanBus;
use App\Models\ManifestPeserta;
use App\Models\PesertaKlien;
use App\Models\PoBus;
use App\Models\SupirPo;
use App\Models\TripPerjalanan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $trips = TripPerjalanan::with(['biro'])
            ->withCount(['armada', 'manifest', 'jadwalAcara'])
            ->latest()
            ->paginate(10);

        return view('trip.index', compact('trips'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $biros = BiroPerjalanan::all();

        return view('trip.create', compact('biros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'biro_id' => ['required', 'exists:biro_perjalanan,id'],
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'status' => ['required', 'in:Upcoming,Ongoing,Completed,Cancelled'],
        ]);

        $trip = TripPerjalanan::create($validated);

        return redirect()->route('trip.show', $trip)->with('success', 'Trip Perjalanan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TripPerjalanan $trip): View
    {
        $trip->load([
            'biro',
            'armada.po',
            'armada.kendaraan',
            'armada.supirPo',
            'armada.supir.user',
            'armada.tourLeader.user',
            'manifest.peserta.user',
            'manifest.armada',
            'jadwalAcara.destinasi',
            'fasilitas.peserta.user',
            'dokumentasi',
        ]);

        $destinasiList = DestinasiWisata::all();
        $pesertaList = PesertaKlien::with('user')->get();

        // Cari trip lain yang rentang tanggalnya bertabrakan (overlapping) dan tidak Cancelled
        $overlappingTrips = TripPerjalanan::where('id', '!=', $trip->id)
            ->where('status', '!=', 'Cancelled')
            ->whereDate('tgl_mulai', '<=', $trip->tgl_selesai)
            ->whereDate('tgl_selesai', '>=', $trip->tgl_mulai)
            ->with(['armada'])
            ->get();

        // Kumpulkan resource yang sedang bertugas di trip lain pada tanggal tersebut
        $busyKendaraan = [];
        $busySupirPo = [];
        $busyTourLeaders = [];

        foreach ($overlappingTrips as $ot) {
            $dateRange = ($ot->tgl_mulai ? $ot->tgl_mulai->format('d M') : '?').' - '.($ot->tgl_selesai ? $ot->tgl_selesai->format('d M Y') : '?');
            foreach ($ot->armada as $arm) {
                if ($arm->kendaraan_id) {
                    $busyKendaraan[$arm->kendaraan_id] = [
                        'trip_id' => $ot->id,
                        'trip_name' => $ot->nama_kegiatan,
                        'dates' => $dateRange,
                    ];
                }
                if ($arm->supir_po_id) {
                    $busySupirPo[$arm->supir_po_id] = [
                        'trip_id' => $ot->id,
                        'trip_name' => $ot->nama_kegiatan,
                        'dates' => $dateRange,
                    ];
                }
                if ($arm->tour_leader_id) {
                    $busyTourLeaders[$arm->tour_leader_id] = [
                        'trip_id' => $ot->id,
                        'trip_name' => $ot->nama_kegiatan,
                        'dates' => $dateRange,
                    ];
                }
            }
        }

        // ID resource yang sudah dialokasikan pada trip ini sendiri
        $alreadyInTripKendaraanIds = $trip->armada->pluck('kendaraan_id')->filter()->values()->all();
        $alreadyInTripSupirPoIds = $trip->armada->pluck('supir_po_id')->filter()->values()->all();

        // Filter Tour Leader khusus karyawan Biro penyelenggara Trip (bukan supir PO / driver)
        $tourLeaderQuery = Karyawan::with(['user', 'biro'])
            ->whereNull('po_id')
            ->whereNull('supir_po_id');

        if ($trip->biro_id) {
            $tourLeaderQuery->where('biro_id', $trip->biro_id);
        } else {
            $tourLeaderQuery->whereNotNull('biro_id');
        }

        $tourLeaderList = $tourLeaderQuery->get();
        $karyawanList = $tourLeaderList;
        $poBusList = PoBus::with(['kendaraan', 'supir'])->orderBy('nama_po')->get();

        return view('trip.show', compact(
            'trip',
            'destinasiList',
            'pesertaList',
            'karyawanList',
            'tourLeaderList',
            'poBusList',
            'busyKendaraan',
            'busySupirPo',
            'busyTourLeaders',
            'alreadyInTripKendaraanIds',
            'alreadyInTripSupirPoIds'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TripPerjalanan $trip): View
    {
        $biros = BiroPerjalanan::all();

        return view('trip.edit', compact('trip', 'biros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TripPerjalanan $trip): RedirectResponse
    {
        $validated = $request->validate([
            'biro_id' => ['required', 'exists:biro_perjalanan,id'],
            'nama_kegiatan' => ['required', 'string', 'max:255'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_selesai' => ['required', 'date', 'after_or_equal:tgl_mulai'],
            'status' => ['required', 'in:Upcoming,Ongoing,Completed,Cancelled'],
        ]);

        $trip->update($validated);

        return redirect()->route('trip.index')->with('success', 'Data Trip Perjalanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TripPerjalanan $trip): RedirectResponse
    {
        $trip->delete();

        return redirect()->route('trip.index')->with('success', 'Trip Perjalanan berhasil dihapus.');
    }

    /**
     * Toggle Presensi status of a participant in a trip.
     */
    public function togglePresensi(ManifestPeserta $manifest): RedirectResponse
    {
        $manifest->update([
            'status_presensi' => ! $manifest->status_presensi,
        ]);

        return back()->with('success', 'Status presensi peserta berhasil diubah.');
    }

    /**
     * Tambah Armada Bus ke Trip.
     */
    public function storeArmada(Request $request, TripPerjalanan $trip): RedirectResponse
    {
        $validated = $request->validate([
            'po_id' => ['nullable', 'exists:po_bus,id'],
            'kendaraan_id' => ['nullable', 'exists:kendaraan_bus,id'],
            'supir_po_id' => ['nullable', 'exists:supir_po,id'],
            'nama_po' => ['nullable', 'string', 'max:150'],
            'nomor_bus' => ['nullable', 'string', 'max:50'],
            'nama_supir' => ['nullable', 'string', 'max:150'],
            'supir_id' => ['nullable', 'exists:karyawan,id'],
            'tour_leader_id' => ['nullable', 'exists:karyawan,id'],
        ]);

        // 1. Cek apakah unit bus sudah ada pada trip ini
        if (! empty($validated['kendaraan_id'])) {
            $alreadyInTrip = $trip->armada()->where('kendaraan_id', $validated['kendaraan_id'])->exists();
            if ($alreadyInTrip) {
                return back()->withErrors(['kendaraan_id' => 'Unit kendaraan bus ini sudah dialokasikan pada trip ini.'])->withInput();
            }

            // 2. Cek bentrok tanggal trip untuk Unit Bus di trip lain yang bersamaan
            $conflictBus = ArmadaBus::whereHas('trip', function ($q) use ($trip) {
                $q->where('id', '!=', $trip->id)
                    ->where('status', '!=', 'Cancelled')
                    ->whereDate('tgl_mulai', '<=', $trip->tgl_selesai)
                    ->whereDate('tgl_selesai', '>=', $trip->tgl_mulai);
            })->where('kendaraan_id', $validated['kendaraan_id'])->with('trip')->first();

            if ($conflictBus) {
                $dates = ($conflictBus->trip->tgl_mulai ? $conflictBus->trip->tgl_mulai->format('d/m/Y') : '').' - '.($conflictBus->trip->tgl_selesai ? $conflictBus->trip->tgl_selesai->format('d/m/Y') : '');

                return back()->withErrors([
                    'kendaraan_id' => 'Unit bus ini tidak tersedia karena sedang bertugas pada trip "'.$conflictBus->trip->nama_kegiatan.'" di tanggal yang bersamaan ('.$dates.').',
                ])->withInput();
            }
        }

        // 3. Cek bentrok tanggal trip untuk Supir PO di trip lain yang bersamaan
        if (! empty($validated['supir_po_id'])) {
            $conflictSupir = ArmadaBus::whereHas('trip', function ($q) use ($trip) {
                $q->where('id', '!=', $trip->id)
                    ->where('status', '!=', 'Cancelled')
                    ->whereDate('tgl_mulai', '<=', $trip->tgl_selesai)
                    ->whereDate('tgl_selesai', '>=', $trip->tgl_mulai);
            })->where('supir_po_id', $validated['supir_po_id'])->with('trip')->first();

            if ($conflictSupir) {
                return back()->withErrors([
                    'supir_po_id' => 'Supir ini sedang bertugas pada trip "'.$conflictSupir->trip->nama_kegiatan.'" di tanggal yang bersamaan.',
                ])->withInput();
            }
        }

        // 4. Cek bentrok tanggal trip untuk Tour Leader di trip lain yang bersamaan
        if (! empty($validated['tour_leader_id'])) {
            $conflictTL = ArmadaBus::whereHas('trip', function ($q) use ($trip) {
                $q->where('id', '!=', $trip->id)
                    ->where('status', '!=', 'Cancelled')
                    ->whereDate('tgl_mulai', '<=', $trip->tgl_selesai)
                    ->whereDate('tgl_selesai', '>=', $trip->tgl_mulai);
            })->where('tour_leader_id', $validated['tour_leader_id'])->with('trip')->first();

            if ($conflictTL) {
                return back()->withErrors([
                    'tour_leader_id' => 'Tour Leader ini sedang memimpin trip "'.$conflictTL->trip->nama_kegiatan.'" di tanggal yang bersamaan.',
                ])->withInput();
            }
        }

        // Jika PO dipilih, gunakan nama PO dari master
        if (! empty($validated['po_id'])) {
            $po = PoBus::find($validated['po_id']);
            if ($po) {
                $validated['nama_po'] = $po->nama_po;
            }
        }

        // Jika kendaraan dipilih, gunakan nomor bus dari kendaraan master
        if (! empty($validated['kendaraan_id'])) {
            $kendaraan = KendaraanBus::find($validated['kendaraan_id']);
            if ($kendaraan) {
                $validated['nomor_bus'] = $kendaraan->nomor_bus;
            }
        }

        // Jika supir PO dipilih, gunakan nama supir dari master
        if (! empty($validated['supir_po_id'])) {
            $supirPo = SupirPo::find($validated['supir_po_id']);
            if ($supirPo) {
                $validated['nama_supir'] = $supirPo->nama_supir;
            }
        }

        if (empty($validated['nomor_bus'])) {
            return back()->withErrors(['nomor_bus' => 'Pilih armada kendaraan atau isikan nomor bus.']);
        }

        $trip->armada()->create($validated);

        return back()->with('success', 'Armada bus berhasil ditambahkan ke trip ini.');
    }

    /**
     * Hapus Armada Bus dari Trip.
     */
    public function destroyArmada(ArmadaBus $armada): RedirectResponse
    {
        $armada->delete();

        return back()->with('success', 'Armada bus berhasil dihapus.');
    }

    /**
     * Tambah Jadwal Acara ke Trip.
     */
    public function storeJadwal(Request $request, TripPerjalanan $trip): RedirectResponse
    {
        if ($request->has('stops') && is_array($request->stops)) {
            $request->validate([
                'stops' => ['required', 'array', 'min:1'],
                'stops.*.lokasi' => ['required', 'string', 'max:255'],
                'stops.*.deskripsi' => ['nullable', 'string', 'max:255'],
                'stops.*.destinasi_id' => ['nullable', 'exists:destinasi_wisata,id'],
                'stops.*.latitude' => ['nullable', 'numeric'],
                'stops.*.longitude' => ['nullable', 'numeric'],
                'stops.*.jam_mulai' => ['nullable', 'string'],
                'stops.*.jam_selesai' => ['nullable', 'string'],
            ]);

            $baseDate = $trip->tgl_mulai ? $trip->tgl_mulai->format('Y-m-d') : now()->format('Y-m-d');

            $trip->jadwalAcara()->delete();

            $prevSelesai = null;
            $prevLokasi = null;

            foreach ($request->stops as $index => $stop) {
                $lokasi = trim($stop['lokasi'] ?? $stop['kegiatan'] ?? '');
                if (! empty($lokasi)) {
                    $jamMulai = ! empty($stop['jam_mulai']) ? $stop['jam_mulai'] : sprintf('%02d:00', 8 + ($index * 2));
                    $jamSelesai = ! empty($stop['jam_selesai']) ? $stop['jam_selesai'] : sprintf('%02d:00', 10 + ($index * 2));

                    $waktuMulai = Carbon::parse($baseDate.' '.$jamMulai);
                    $waktuSelesai = Carbon::parse($baseDate.' '.$jamSelesai);

                    if ($waktuSelesai->lt($waktuMulai)) {
                        return back()->withErrors([
                            'stops' => 'Agenda #'.($index + 1)." ({$lokasi}): Jam selesai ({$jamSelesai}) tidak boleh lebih awal dari jam mulai ({$jamMulai}).",
                        ])->withInput();
                    }

                    if ($prevSelesai !== null && $waktuMulai->lt($prevSelesai)) {
                        return back()->withErrors([
                            'stops' => 'Agenda #'.($index + 1)." ({$lokasi}) tidak dapat dimulai pada jam {$jamMulai} karena agenda sebelumnya ({$prevLokasi}) baru selesai pada jam {$prevSelesai->format('H:i')}.",
                        ])->withInput();
                    }

                    $prevSelesai = $waktuSelesai;
                    $prevLokasi = $lokasi;

                    $kegiatanText = $lokasi;
                    if (! empty($stop['deskripsi'])) {
                        $kegiatanText .= ' — '.trim($stop['deskripsi']);
                    }

                    $destinasiId = ! empty($stop['destinasi_id']) ? $stop['destinasi_id'] : null;
                    $latitude = ! empty($stop['latitude']) ? (float) $stop['latitude'] : null;
                    $longitude = ! empty($stop['longitude']) ? (float) $stop['longitude'] : null;

                    // Jika koordinat belum terisi dari form tapi memilih destinasi master, isi dari master
                    if ($destinasiId && ($latitude === null || $longitude === null)) {
                        $destinasi = DestinasiWisata::find($destinasiId);
                        if ($destinasi) {
                            $latitude = $latitude ?? $destinasi->latitude;
                            $longitude = $longitude ?? $destinasi->longitude;
                        }
                    }

                    $trip->jadwalAcara()->create([
                        'kegiatan' => $kegiatanText,
                        'destinasi_id' => $destinasiId,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'waktu_mulai' => $waktuMulai,
                        'waktu_selesai' => $waktuSelesai,
                    ]);
                }
            }

            return back()->with('success', 'Rangkaian jadwal acara (itinerary) berhasil disimpan.');
        }

        $validated = $request->validate([
            'destinasi_id' => ['nullable', 'exists:destinasi_wisata,id'],
            'kegiatan' => ['required', 'string', 'max:255'],
            'waktu_mulai' => ['required', 'date'],
            'waktu_selesai' => ['required', 'date', 'after_or_equal:waktu_mulai'],
        ]);

        $trip->jadwalAcara()->create($validated);

        return back()->with('success', 'Jadwal acara (itinerary) berhasil ditambahkan.');
    }

    /**
     * Hapus Jadwal Acara dari Trip.
     */
    public function destroyJadwal(JadwalAcara $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return back()->with('success', 'Jadwal acara berhasil dihapus.');
    }

    /**
     * Tambah Peserta ke Manifest Trip.
     */
    public function storeManifest(Request $request, TripPerjalanan $trip): RedirectResponse
    {
        $validated = $request->validate([
            'armada_id' => ['required', 'exists:armada_bus,id'],
            'peserta_id' => ['required', 'exists:peserta_klien,id'],
            'nomor_kursi' => ['nullable', 'string', 'max:10'],
        ]);

        $trip->manifest()->create($validated);

        return back()->with('success', 'Peserta berhasil ditambahkan ke manifest.');
    }

    /**
     * Hapus Peserta dari Manifest.
     */
    public function destroyManifest(ManifestPeserta $manifest): RedirectResponse
    {
        $manifest->delete();

        return back()->with('success', 'Peserta berhasil dihapus dari manifest.');
    }
}
