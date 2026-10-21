<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityTag;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = FacilityTag::withCount('facilities')->paginate(10);
        return view('admin.settings.tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:facility_tags',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $tag = FacilityTag::create($request->all());

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'FacilityTag',
            'target_id' => $tag->id,
            'details' => "Admin created facility tag: {$tag->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, FacilityTag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:facility_tags,name,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $tag->update($request->all());

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'FacilityTag',
            'target_id' => $tag->id,
            'details' => "Admin updated facility tag: {$tag->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Tag updated successfully.');
    }

    public function destroy(FacilityTag $tag)
    {
        if ($tag->facilities()->count() > 0) {
            return back()->with('error', 'Cannot delete tag that has associated facilities.');
        }

        $tagName = $tag->name;
        $tagId = $tag->id;
        $tag->delete();

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'FacilityTag',
            'target_id' => $tagId,
            'details' => "Admin deleted facility tag: {$tagName}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Tag deleted successfully.');
    }
}
