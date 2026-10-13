<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard admin. Masih halaman awal; ringkasan data dibuat di Fase 3.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
