<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $logs = AdminLog::with('user')->latest()->paginate(30);
        return view('admin.logs.index', compact('logs'));
    }
}
