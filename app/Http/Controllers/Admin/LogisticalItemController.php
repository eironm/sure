<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogisticalItem;
use Illuminate\Http\Request;

class LogisticalItemController extends Controller
{
    /**
     * Display a listing of logistical items.
     */
    public function index()
    {
        $items = LogisticalItem::orderBy('category')->orderBy('name')->get();
        return view('admin.logistical-items.index', compact('items'));
    }

    /**
     * Store a newly created logistical item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'unit_label'    => 'required|string|max:50',
            'max_quantity'  => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        LogisticalItem::create($validated);

        return redirect()->route('admin.logistical-items.index')
            ->with('success', 'Logistical item created successfully.');
    }

    /**
     * Update the specified logistical item.
     */
    public function update(Request $request, LogisticalItem $logisticalItem)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'unit_label'    => 'required|string|max:50',
            'max_quantity'  => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $logisticalItem->update($validated);

        return redirect()->route('admin.logistical-items.index')
            ->with('success', 'Logistical item updated successfully.');
    }

    /**
     * Remove the specified logistical item.
     */
    public function destroy(LogisticalItem $logisticalItem)
    {
        $logisticalItem->delete();

        return redirect()->route('admin.logistical-items.index')
            ->with('success', 'Logistical item deleted successfully.');
    }
}
