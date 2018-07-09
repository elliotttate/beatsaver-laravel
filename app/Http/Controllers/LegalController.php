<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function dmca()
    {
        return view('master.page-dmca');
    }

    public function privacy()
    {
        return view('master.page-privacy');
    }
}
