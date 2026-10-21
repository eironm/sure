@extends('layouts.admin')

@section('title', 'Logistical Items')
@section('header', 'Facility Settings')

@section('content')
<div class="container-fluid p-0">
    <!-- Settings Navigation Tabs -->
    @include('admin.settings.nav-tabs')
    @include('components.delete-confirmation-modal')

    <div class="row g-4">
        <!-- Items List -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0 rounded-top-4">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Logistical Requirements</h5>
                        <div class="small text-muted mt-1">Manage equipment, services, and other items that users can request during reservations.</div>
                    </div>
                    <button type="button" class="btn btn-sure shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#createItemModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Item
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Item Name</th>
                                    <th class="py-3">Category</th>
                                    <th class="py-3 text-end">Price</th>
                                    <th class="py-3 text-center">Max Qty</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="text-end pe-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $item->name }}</div>
                                            <div class="small text-muted text-truncate" style="max-width: 250px;">{{ $item->description ?? 'No description' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $item->category ?? 'Uncategorized' }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if($item->price > 0)
                                                <div class="fw-bold text-success">₱{{ number_format($item->price, 2) }}</div>
                                                <div class="small text-muted">/ {{ $item->unit_label }}</div>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Included</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-medium text-dark">{{ $item->max_quantity }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($item->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-dark px-3 rounded-start-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editItemModal{{ $item->id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.settings.logistical-items.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="btn btn-outline-danger px-3 rounded-end-3" 
                                                        onclick="showDeleteConfirmation(event, 'logistical item')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal for each item -->
                                    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header border-0 bg-light rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Edit Logistical Item</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.settings.logistical-items.update', $item) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $item->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Category</label>
                                                            <input type="text" name="category" class="form-control rounded-3" value="{{ $item->category }}" placeholder="e.g. Equipment, Furniture">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control rounded-3" rows="2">{{ $item->description }}</textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text rounded-start-3">₱</span>
                                                                    <input type="number" name="price" step="0.01" min="0" class="form-control rounded-end-3" value="{{ $item->price }}" required>
                                                                </div>
                                                                <div class="form-text small">Set to 0 if free/included</div>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Unit Label <span class="text-danger">*</span></label>
                                                                <input type="text" name="unit_label" class="form-control rounded-3" value="{{ $item->unit_label }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Max Quantity <span class="text-danger">*</span></label>
                                                                <input type="number" name="max_quantity" class="form-control rounded-3" value="{{ $item->max_quantity }}" min="1" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                                                <div class="form-check form-switch mb-2">
                                                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="isActiveEdit{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                                                                    <label class="form-check-label fw-medium" for="isActiveEdit{{ $item->id }}">Active Status</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sure rounded-3">Update Item</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <div class="mb-3"><i class="bi bi-box-seam display-6 opacity-50"></i></div>
                                            No logistical items configured yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">New Logistical Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.settings.logistical-items.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Monoblock Chair" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <input type="text" name="category" class="form-control rounded-3" placeholder="e.g. Equipment, Furniture">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Brief details about this item"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-3">₱</span>
                                <input type="number" name="price" step="0.01" min="0" value="0" class="form-control rounded-end-3" required>
                            </div>
                            <div class="form-text small">Set to 0 if free/included</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Unit Label <span class="text-danger">*</span></label>
                            <input type="text" name="unit_label" class="form-control rounded-3" placeholder="e.g. pc, hr, set" value="pc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Max Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="max_quantity" class="form-control rounded-3" min="1" value="10" required>
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1" id="isActiveCreate" checked>
                                <label class="form-check-label fw-medium" for="isActiveCreate">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sure rounded-3">Create Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
