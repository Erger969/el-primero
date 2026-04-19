<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class MasterRequestController extends Controller
{
    public function index()
    {
        $requests = MasterRequest::with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $history = MasterRequest::with('user', 'reviewer')
            ->where('status', '!=', 'pending')
            ->orderBy('updated_at', 'desc')
            ->paginate(15, ['*'], 'history_page');

        return view('admin.master-requests.index', compact('requests', 'history'));
    }

    public function approve($id)
    {
        $masterRequest = MasterRequest::findOrFail($id);
        
        if ($masterRequest->status != 'pending') {
            return redirect()->route('admin.master-requests.index')
                ->with('error', 'Esta solicitud ya fue procesada.');
        }

        // Actualizar el rol del usuario a Master (role_id = 2)
        $user = $masterRequest->user;
        $user->role_id = 2;
        $user->save();

        // Actualizar la solicitud
        $masterRequest->status = 'approved';
        $masterRequest->reviewed_by = auth()->id();
        $masterRequest->save();

        return redirect()->route('admin.master-requests.index')
            ->with('success', "Solicitud de {$user->name} aprobada. Ahora es Master.");
    }

    public function reject(Request $request, $id)
    {
        $masterRequest = MasterRequest::findOrFail($id);
        
        if ($masterRequest->status != 'pending') {
            return redirect()->route('admin.master-requests.index')
                ->with('error', 'Esta solicitud ya fue procesada.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $masterRequest->status = 'rejected';
        $masterRequest->reviewed_by = auth()->id();
        $masterRequest->rejection_reason = $request->rejection_reason;
        $masterRequest->save();

        return redirect()->route('admin.master-requests.index')
            ->with('success', 'Solicitud rechazada.');
    }
}