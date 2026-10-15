<?php

namespace App\Http\Controllers;

use App\Models\BiroPerjalanan;
use App\Models\DestinasiWisata;
use App\Models\DokumentasiTrip;
use App\Models\FasilitasPeserta;
use App\Models\JadwalAcara;
use App\Models\Karyawan;
use App\Models\ManifestPeserta;
use App\Models\PengumumanLivechat;
use App\Models\PesertaKlien;
use App\Models\PoBus;
use App\Models\TripPerjalanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application superadmin / admin dashboard.
     */
    public function index(Request $request): View
    {
        // 1. Metric Summaries
        $totalPengguna = User::count();
        $totalBiro = BiroPerjalanan::count();
        $totalDestinasi = DestinasiWisata::count();
        $totalPeserta = PesertaKlien::count();
        $totalArmada = PoBus::count();
        $totalTrip = TripPerjalanan::count();
        $totalManifest = ManifestPeserta::count();

        // 2. Recent Users / Stakeholders Activities for Right Feed
        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                // Determine display role label & color
                $roleLabel = match ($user->role) {
                    'superadmin' => 'Super Admin',
                    'admin' => 'Admin Operasional / TL',
                    default => 'Peserta Klien',
                };

                // Pick color scheme based on id / hash
                $avatarColors = [
                    'bg-slate-700 text-white',
                    'bg-amber-500 text-white',
                    'bg-blue-600 text-white',
                    'bg-indigo-600 text-white',
                    'bg-emerald-600 text-white',
                ];
                $colorIndex = $user->id % count($avatarColors);

                // Initial string
                $words = explode(' ', trim($user->name));
                $initials = count($words) >= 2
                    ? strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1))
                    : strtoupper(substr($user->name, 0, 2));

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_label' => $roleLabel,
                    'initials' => $initials,
                    'avatar_class' => $avatarColors[$colorIndex],
                    'date_formatted' => $user->created_at ? $user->created_at->format('M d, Y') : 'Sep 24, 2026',
                    'time_ago' => $user->created_at ? $user->created_at->diffForHumans() : '1 hari yang lalu',
                ];
            });

        // 3. Master Data Counts Grouping for Sidebar & Sections
        $masterDataSummary = [
            'biro' => $totalBiro,
            'karyawan' => Karyawan::count(),
            'peserta' => $totalPeserta,
            'destinasi' => $totalDestinasi,
            'armada' => $totalArmada,
            'trip' => $totalTrip,
            'fasilitas' => FasilitasPeserta::count(),
            'jadwal' => JadwalAcara::count(),
            'dokumentasi' => DokumentasiTrip::count(),
            'livechat' => PengumumanLivechat::count(),
        ];

        return view('dashboard', [
            'totalPengguna' => $totalPengguna,
            'totalBiro' => $totalBiro,
            'totalDestinasi' => $totalDestinasi,
            'totalPeserta' => $totalPeserta,
            'totalArmada' => $totalArmada,
            'totalTrip' => $totalTrip,
            'totalManifest' => $totalManifest,
            'recentUsers' => $recentUsers,
            'masterDataSummary' => $masterDataSummary,
        ]);
    }
}
