<?php $__env->startSection('title', 'Facility Categories'); ?>
<?php $__env->startSection('header', 'Facility Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-0">
    <!-- Settings Navigation Tabs -->
    <?php echo $__env->make('admin.settings.nav-tabs', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.delete-confirmation-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="row g-4">
        <!-- Categories List -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0 rounded-top-4">
                    <h5 class="mb-0 fw-bold text-dark">Facility Categories</h5>
                    <button type="button" class="btn btn-sure shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Name</th>
                                    <th class="py-3">Icon</th>
                                    <th class="py-3">Color</th>
                                    <th class="py-3">Facilities</th>
                                    <th class="text-end pe-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark"><?php echo e($category->name); ?></div>
                                            <div class="small text-muted text-truncate" style="max-width: 250px;"><?php echo e($category->description ?? 'No description'); ?></div>
                                        </td>
                                        <td>
                                            <div class="stat-icon-sm bg-light text-dark">
                                                <i class="bi bi-<?php echo e($category->icon ?? 'building'); ?>"></i>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="color-dot me-2" style="background-color: <?php echo e($category->color ?? '#6c757d'); ?>;"></div>
                                                <code class="small"><?php echo e(strtoupper($category->color ?? '#6c757d')); ?></code>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?php echo e($category->facilities_count); ?></span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-dark px-3 rounded-start-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCategoryModal<?php echo e($category->id); ?>">
                                                    Edit
                                                </button>
                                                <form action="<?php echo e(route('admin.settings.categories.destroy', $category)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                        class="btn btn-outline-danger px-3 rounded-end-3" 
                                                        onclick="showDeleteConfirmation(event, 'category')"
                                                        <?php echo e($category->facilities_count > 0 ? 'disabled' : ''); ?> 
                                                        title="<?php echo e($category->facilities_count > 0 ? 'Cannot delete category with facilities' : ''); ?>">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal for each category -->
                                    <div class="modal fade" id="editCategoryModal<?php echo e($category->id); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg rounded-4">
                                                <div class="modal-header border-0 bg-light rounded-top-4">
                                                    <h5 class="modal-title fw-bold">Edit Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="<?php echo e(route('admin.settings.categories.update', $category)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Category Name</label>
                                                            <input type="text" name="name" class="form-control rounded-3" value="<?php echo e($category->name); ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Description</label>
                                                            <textarea name="description" class="form-control rounded-3" rows="2"><?php echo e($category->description); ?></textarea>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Color (Hex)</label>
                                                                <input type="color" name="color" class="form-control form-control-color w-100 rounded-3" value="<?php echo e($category->color ?? '#6c757d'); ?>">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Bootstrap Icon</label>
                                                                <input type="text" name="icon" class="form-control rounded-3" value="<?php echo e($category->icon ?? 'building'); ?>" placeholder="e.g. building">
                                                                <div class="form-text small">See <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 p-4 pt-0">
                                                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-sure rounded-3">Update Category</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">No categories found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($categories->hasPages()): ?>
                    <div class="card-footer bg-white py-3 border-0 rounded-bottom-4">
                        <?php echo e($categories->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold">New Facility Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.settings.categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Laboratory" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Brief description of this category"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Color (Hex)</label>
                            <input type="color" name="color" class="form-control form-control-color w-100 rounded-3" value="#6c757d">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bootstrap Icon</label>
                            <input type="text" name="icon" class="form-control rounded-3" value="building" placeholder="e.g. building">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sure rounded-3">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .stat-icon-sm {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Steam Deck User\Documents\INTERNAL DEVELOPMENT\resources\views/admin/settings/categories/index.blade.php ENDPATH**/ ?>