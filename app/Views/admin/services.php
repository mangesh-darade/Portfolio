
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Service Offerings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        <i class="fas fa-plus me-2"></i> Add New Service
    </button>
</div>

<!-- Services Table Card -->
<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table id="servicesTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%">Icon</th>
                        <th>Title</th>
                        <th>Price (Optional)</th>
                        <th>Description</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($services)): ?>
                        <?php foreach ($services as $srv): ?>
                            <tr>
                                <td><?= $srv['id'] ?></td>
                                <td>
                                    <div class="avatar-sm rounded bg-light d-flex align-items-center justify-content-center text-primary">
                                        <i class="<?= esc($srv['icon']) ?>"></i>
                                    </div>
                                </td>
                                <td><span class="fw-bold"><?= esc($srv['title']) ?></span></td>
                                <td>
                                    <?php if($srv['price']): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success"><?= esc($srv['price']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted small"><?= substr(esc($srv['description']), 0, 50) ?>...</span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary me-1 edit-service-btn" data-id="<?= $srv['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger delete-service-btn" data-id="<?= $srv['id'] ?>">
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

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addServiceForm" action="<?= base_url('admin/services/add') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Integration Title</label>
                        <input type="text" name="title" class="form-control form-control-custom" placeholder="e.g. Web Development" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Icon Class (<a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a>)</label>
                            <input type="text" name="icon" class="form-control form-control-custom" placeholder="e.g. fas fa-code">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Price/Rate (Optional)</label>
                            <input type="text" name="price" class="form-control form-control-custom" placeholder="e.g. Starts at $500">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" class="form-control form-control-custom" rows="4" placeholder="Describe the service..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editServiceForm" action="<?= base_url('admin/services/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_srv_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control form-control-custom" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Icon Class</label>
                            <input type="text" name="icon" id="edit_icon" class="form-control form-control-custom">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Price/Rate</label>
                            <input type="text" name="price" id="edit_price" class="form-control form-control-custom">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" id="edit_description" class="form-control form-control-custom" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Service</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initializeServiceHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeServiceHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {

    // Add Service AJAX
    $('#addServiceForm').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#addServiceModal').modal('hide');
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
                toastr.error('An error occurred. Please try again.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Open Edit Modal
    $(document).on('click', '.edit-service-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("admin/services/get/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    $('#edit_srv_id').val(data.id);
                    $('#edit_title').val(data.title);
                    $('#edit_icon').val(data.icon);
                    $('#edit_price').val(data.price);
                    $('#edit_description').val(data.description);
                    $('#editServiceModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Service AJAX
    $('#editServiceForm').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#editServiceModal').modal('hide');
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

    // Delete Service
    $(document).on('click', '.delete-service-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '<?= site_url("admin/services/delete/") ?>' + id,
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
initializeServiceHandlers();
</script>
