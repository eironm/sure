@extends('layouts.admin')

@section('title', 'Facility Tags')
@section('header', 'Facility Settings')

@section('content')
<div class="container-fluid p-0">
    <!-- Settings Navigation Tabs -->
    @include('admin.settings.nav-tabs')
    @include('components.delete-confirmation-modal')

    <div class="row g-4">
        <!-- Tags List -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-dark">Facility Tags</h5>
                    <button type="button" class="btn btn-sure shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#createTagModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Tag
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Name</th>
                                    <th class="py-3">Color</th>
                                    <th class="py-3">Facilities</th>
                                    <th class="text-end pe-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $tag)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $tag->name }}</div>
                                            <div class="small text-muted text-truncate" style="max-width: 300px;">{{ $tag->description ?? 'No description' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="color-dot me-2" style="background-color: {{ $tag->color ?? '#6c757d' }};"></div>
                                                <code class="small">{{ strtoupper($tag->color ?? '#6c757d') }}</code>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $tag->facilities_count }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-dark px-3 rounded-start-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editTagModal{{ $tag->id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.settings.tags.destroy', $tag) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="btn btn-outline-danger px-3 rounded-end-3" 
                                                        onclick="showDeleteConfirmation(event, 'tag')"
                                                        {{ $tag->facilities_count > 0 ? 'disabled' : '' }} 
                                                        title="{{ $tag->facilities_count > 0 ? 'Cannot delete tag with facilities' : '' }}">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal for each tag -->
                                    <div class="modal fade" id="editTagModal{{ $tag->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header border-0 bg-light rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Edit Tag</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.settings.tags.update', $tag) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tag Name</label>
                                                            <input type="text" name="name" class="form-control rounded-3" value="{{ $tag->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control rounded-3" rows="2">{{ $tag->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Color (Hex)</label>
                                                            <input type="color" name="color" class="form-control form-control-color w-100 rounded-3" value="{{ $tag->color ?? '#6c757d' }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sure rounded-3">Update Tag</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No tags found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tags->hasPages())
                    <div class="card-footer bg-white py-3 border-0 rounded-bottom-4">
                        {{ $tags->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createTagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">New Facility Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.settings.tags.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tag Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Air-Conditioned" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Brief description of this tag"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Color (Hex)</label>
                        <input type="color" name="color" class="form-control form-control-color w-100 rounded-3" value="#6c757d">
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sure rounded-3">Create Tag</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
</style>
@endsection
