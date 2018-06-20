<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function dmca()
    {
        return view('legal.dmca');
    }

    public function privacy()
    {
        return view('legal.privacy');
    }
}
