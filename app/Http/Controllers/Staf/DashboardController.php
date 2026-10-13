<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard staf. Masih halaman awal; jadwal piket dibuat di Fase 7.
     */
    public function index(): View
    {
        return view('staf.dashboard');
    }
}
