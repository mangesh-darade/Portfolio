
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Client Testimonials</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
        <i class="fas fa-plus me-2"></i> Add New Testimonial
    </button>
</div>

<!-- Testimonials Table Card -->
<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table id="testimonialsTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Image</th>
                        <th>Name</th>
                        <th>Role/Company</th>
                        <th>Quote</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($testimonials)): ?>
                        <?php foreach ($testimonials as $t): ?>
                            <tr>
                                <td><?= $t['id'] ?></td>
                                <td>
                                    <?php if($t['image']): ?>
                                        <img src="<?= base_url('uploads/testimonials/' . $t['image']) ?>" class="avatar-sm rounded-circle" alt="client">
                                    <?php else: ?>
                                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center text-primary">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><span class="fw-bold"><?= esc($t['name']) ?></span></td>
                                <td>
                                    <?= esc($t['role']) ?>
                                    <?php if($t['company']): ?>
                                        <br><span class="text-muted small"><?= esc($t['company']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted small">"<?= substr(esc($t['quote']), 0, 50) ?>..."</span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary me-1 edit-testimonial-btn" data-id="<?= $t['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger delete-testimonial-btn" data-id="<?= $t['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTestimonialForm" action="<?= base_url('admin/testimonials/add') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Client Name</label>
                        <input type="text" name="name" class="form-control form-control-custom" placeholder="e.g. John Smith" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Role/Job Title</label>
                            <input type="text" name="role" class="form-control form-control-custom" placeholder="e.g. CEO" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Company (Optional)</label>
                            <input type="text" name="company" class="form-control form-control-custom" placeholder="e.g. Tech Inc.">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Quote</label>
                        <textarea name="quote" class="form-control form-control-custom" rows="4" placeholder="What did they say?" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Client Photo (Optional)</label>
                        <input type="file" name="image" class="form-control form-control-custom" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Testimonial Modal -->
<div class="modal fade" id="editTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTestimonialForm" action="<?= base_url('admin/testimonials/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_t_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Client Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-custom" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Role/Job Title</label>
                            <input type="text" name="role" id="edit_role" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Company</label>
                            <input type="text" name="company" id="edit_company" class="form-control form-control-custom">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Quote</label>
                        <textarea name="quote" id="edit_quote" class="form-control form-control-custom" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">New Photo (Optional)</label>
                        <input type="file" name="image" class="form-control form-control-custom" accept="image/*">
                        <div class="form-text text-muted">Leave blank to keep current photo.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initializeTestimonialHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeTestimonialHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {

    // Add AJAX
    $('#addTestimonialForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#addTestimonialModal').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                } else {
                     if (response.errors) {
                        $.each(response.errors, function(key, val) {
                            toastr.error(val);
                        });
                    } else {
                        toastr.error(response.message);
                    }
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                toastr.error('An error occurred.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Open Edit Modal
    $(document).on('click', '.edit-testimonial-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("admin/testimonials/get/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    $('#edit_t_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_role').val(data.role);
                    $('#edit_company').val(data.company);
                    $('#edit_quote').val(data.quote);
                    $('#editTestimonialModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update AJAX
    $('#editTestimonialForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#editTestimonialModal').modal('hide');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
             error: function() {
                toastr.error('An error occurred.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete
    $(document).on('click', '.delete-testimonial-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '<?= site_url("admin/testimonials/delete/") ?>' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }
    });

    });
}
initializeTestimonialHandlers();
</script>
