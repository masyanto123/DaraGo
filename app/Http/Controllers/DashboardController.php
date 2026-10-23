<?php

namespace App\Http\Controllers;

use App\Models\BiroPerjalanan;
use App\Models\DestinasiWisata;
use App\Models\DokumentasiTrip;
use App\Models\JadwalAcara;
use App\Models\Karyawan;
use App\Models\PengumumanLivechat;
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
        $totalArmada = PoBus::count();
        $totalKaryawan = Karyawan::count();
        $totalTrip = TripPerjalanan::count();

        // 2. Recent Users / Stakeholders Activities for Right Feed
        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                // Determine display role label & color
                $roleLabel = match ($user->role) {
                    'superadmin' => 'Super Admin',
                    'admin' => 'Admin Operasional / TL',
                    default => 'Pengguna Sistem',
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

        // 3. Dynamic Chart Data Calculation (Monthly & Yearly)
        $allTrips = TripPerjalanan::all();

        // Update / sync status dinamis berdasarkan tanggal untuk setiap trip
        foreach ($allTrips as $trip) {
            $determined = TripPerjalanan::determineStatusByDates($trip->tgl_mulai, $trip->tgl_selesai, $trip->status);
            if ($trip->status !== $determined && $trip->status !== 'Cancelled') {
                $trip->status = $determined;
                $trip->saveQuietly();
            }
        }

        // Data Bulanan: 7 bulan terakhir sampai bulan ini (e.g. Apr 2026 s/d Okt 2026)
        $monthlyLabels = [];
        $monthlyCounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $yearMonth = $monthDate->format('Y-m');
            $monthlyLabels[] = $monthDate->translatedFormat('M'); // e.g. 'Apr', 'Mei', ... 'Okt'

            $count = $allTrips->filter(function ($trip) use ($yearMonth) {
                return $trip->tgl_mulai && $trip->tgl_mulai->format('Y-m') === $yearMonth;
            })->count();

            $monthlyCounts[] = $count;
        }

        // Data Tahunan: 5 tahun terakhir sampai tahun ini
        $yearlyLabels = [];
        $yearlyCounts = [];
        for ($i = 4; $i >= 0; $i--) {
            $yearDate = now()->subYears($i);
            $yearStr = $yearDate->format('Y');
            $yearlyLabels[] = $yearStr;

            $count = $allTrips->filter(function ($trip) use ($yearStr) {
                return $trip->tgl_mulai && $trip->tgl_mulai->format('Y') === $yearStr;
            })->count();

            $yearlyCounts[] = $count;
        }

        $chartData = [
            'monthly' => [
                'labels' => $monthlyLabels,
                'counts' => $monthlyCounts,
                'current_month_name' => now()->translatedFormat('M'),
                'current_month_count' => end($monthlyCounts) ?: 0,
            ],
            'yearly' => [
                'labels' => $yearlyLabels,
                'counts' => $yearlyCounts,
                'current_year_name' => now()->format('Y'),
                'current_year_count' => end($yearlyCounts) ?: 0,
            ],
        ];

        // 4. Master Data Counts Grouping for Sidebar & Sections
        $tripOngoing = $allTrips->where('status', 'Ongoing')->count();
        $tripUpcoming = $allTrips->where('status', 'Upcoming')->count();
        $tripCompleted = $allTrips->where('status', 'Completed')->count();
        $tripCancelled = $allTrips->where('status', 'Cancelled')->count();

        $masterDataSummary = [
            'biro' => $totalBiro,
            'karyawan' => $totalKaryawan,
            'destinasi' => $totalDestinasi,
            'armada' => $totalArmada,
            'trip' => $totalTrip,
            'jadwal' => JadwalAcara::count(),
            'dokumentasi' => DokumentasiTrip::count(),
            'livechat' => PengumumanLivechat::count(),
        ];

        return view('dashboard', [
            'totalPengguna' => $totalPengguna,
            'totalBiro' => $totalBiro,
            'totalDestinasi' => $totalDestinasi,
            'totalArmada' => $totalArmada,
            'totalKaryawan' => $totalKaryawan,
            'totalTrip' => $totalTrip,
            'tripOngoing' => $tripOngoing,
            'tripUpcoming' => $tripUpcoming,
            'tripCompleted' => $tripCompleted,
            'recentUsers' => $recentUsers,
            'masterDataSummary' => $masterDataSummary,
            'chartData' => $chartData,
        ]);
    }
}
