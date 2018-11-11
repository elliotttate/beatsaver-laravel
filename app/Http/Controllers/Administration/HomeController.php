<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\SongDetail;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * All requests require admin middleware
     *
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['admin']);
    }

    /**
     * Shows the dashboard of the admin panel
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $labels = [];
        $data['registrations'] = [];
        $data['uploads'] = [];

        $months = 6;

        while ($months >= 0) {
            $monthIteration = Carbon::now()->subMonths($months);

            $labels[] = $monthIteration->format('F y');

            $data['registrations'][] = User::whereBetween(
                'created_at',
                [
                    Carbon::now()->subMonths($months + 1),
                    $monthIteration
                ]
            )->count();

            $data['uploads'][] = Song::whereBetween(
                'created_at',
                [
                    Carbon::now()->subMonths($months + 1),
                    $monthIteration
                ]
            )->count();

            $months--;
        }

        $dashboard = [
            'songCount' => Song::all(['id'])->count(),
            'downloadCount' => SongDetail::all(['download_count'])->sum('download_count'),
            'playCount' => SongDetail::all(['play_count'])->sum('play_count'),
            'userCount' => User::all(['id'])->count(),
        ];

        return view('admin.dashboard', [
            'dashboard' => $dashboard,
            'registrationsUploadsChart' => [
                'labels' => $labels,
                'data' => $data
            ],
        ]);
    }
}
