<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        return view('admin.dashboard');
    }
    public function users()
    {
        return view('admin.users');
    }

    public function branches()
    {
        return view('admin.branches');
    }

    public function films()
    {
        return view('admin.films');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
