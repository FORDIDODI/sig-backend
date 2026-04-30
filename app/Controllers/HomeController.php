<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function map()
    {
        return view('map');
    }

    /**
     * Halaman navigasi untuk Android WebView
     * Query params: fromLat, fromLng, toLat, toLng, toNama
     */
    public function navigate()
    {
        return view('navigate');
    }
}
