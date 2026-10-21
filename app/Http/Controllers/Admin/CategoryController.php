<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacilityCategory;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = FacilityCategory::withCount('facilities')->paginate(10);
        return view('admin.settings.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:facility_categories',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = FacilityCategory::create($request->all());

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'FacilityCategory',
            'target_id' => $category->id,
            'details' => "Admin created facility category: {$category->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, FacilityCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:facility_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($request->all());

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'FacilityCategory',
            'target_id' => $category->id,
            'details' => "Admin updated facility category: {$category->name}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    public function destroy(FacilityCategory $category)
    {
        if ($category->facilities()->count() > 0) {
            return back()->with('error', 'Cannot delete category that has associated facilities.');
        }

        $categoryName = $category->name;
        $categoryId = $category->id;
        $category->delete();

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'FacilityCategory',
            'target_id' => $categoryId,
            'details' => "Admin deleted facility category: {$categoryName}",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Category deleted successfully.');
    }
}
