<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterActivity;
use App\Models\User;
use Illuminate\Http\Request;

class MasterActivityController extends Controller
{
    public function index()
    {
        $activities = MasterActivity::with(['master', 'post'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.master-activity.index', compact('activities'));
    }

    public function revokePermissions($masterId)
    {
        $master = User::findOrFail($masterId);
        
        if ($master->role_id != 2) {
            return redirect()->route('admin.master-activity.index')
                ->with('error', 'Este usuario no es Master.');
        }
        
        $master->role_id = 1; // Volver a Universitario
        $master->save();
        
        return redirect()->route('admin.master-activity.index')
            ->with('success', "Se han revocado los permisos de {$master->name}.");
    }
}