<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use Illuminate\Http\Request;

class PasswordResetLogController extends Controller
{
    public function index(Request $request)
    {
        $query = PasswordResetCode::query();

        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.security.password-resets', compact('logs'));
    }
}
