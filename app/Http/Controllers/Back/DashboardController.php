<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexadmin()
    {
        return view('back.admin.dashboard');
    }

    public function indexkaryawan()
    {
        return view('back.karyawan.dashboard');
    }
}
