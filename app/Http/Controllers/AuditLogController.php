<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

/**
 * Audit trail screens for the Admin and SuperAdmin panels.
 * Replaces the previous inline route closures and adds
 * search / action / date-range filtering.
 */
class AuditLogController extends Controller
{
    /** Admin panel view (staff accounts are denied). */
    public function admin(Request $request)
    {
        if ($request->user()->isStaff()) {
            abort(403, 'Staff accounts do not have access to audit logs.');
        }

        return view('admin.audit-logs', ['logs' => $this->query($request, 15)]);
    }

    /** SuperAdmin panel view (full access). */
    public function superadmin(Request $request)
    {
        return view('superadmin.audit-logs', ['logs' => $this->query($request, 20)]);
    }

    private function query(Request $request, int $perPage)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'action' => ['nullable', 'string', 'max:50'],
            'from'   => ['nullable', 'date'],
            'to'     => ['nullable', 'date'],
        ]);

        return AuditLog::with('admin')
            ->filtered(
                $validated['search'] ?? null,
                $validated['action'] ?? null,
                $validated['from'] ?? null,
                $validated['to'] ?? null,
            )
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
