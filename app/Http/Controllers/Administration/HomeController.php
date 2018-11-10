<?php

namespace App\Http\Controllers\Administration;

use App\Models\Song;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

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
     * @return View
     */
    public function show()
    {
        $dashboard = [
            'songCount' => Song::all(['id'])->count(),
            'userCount' => User::all(['id'])->count(),
        ];

        return view('admin.dashboard', ['dashboard' => $dashboard]);
    }
}
