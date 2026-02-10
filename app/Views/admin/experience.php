
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Experience Overview</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Experience</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addExperienceModal">
        <i class="fas fa-plus me-2"></i> Add New Experience
    </button>
</div>

<!-- Experience Table Card -->
<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table id="experienceTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Dates</th>
                        <th>Current</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($experiences)): ?>
                        <?php foreach ($experiences as $exp): ?>
                            <tr>
                                <td><?= $exp['id'] ?></td>
                                <td>
                                    <span class="fw-bold"><?= esc($exp['job_title']) ?></span>
                                </td>
                                <td><?= esc($exp['company']) ?></td>
                                <td>
                                    <?= esc($exp['start_date']) ?> - <?= esc($exp['end_date']) ?>
                                </td>
                                <td>
                                    <?php if ($exp['is_current'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Current</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">Past</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary me-1 edit-experience-btn" data-id="<?= $exp['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger delete-experience-btn" data-id="<?= $exp['id'] ?>">
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

<!-- Add Experience Modal -->
<div class="modal fade" id="addExperienceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addExperienceForm" action="<?= base_url('admin/experience/add') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Job Title</label>
                            <input type="text" name="job_title" class="form-control form-control-custom" placeholder="e.g. Senior Developer" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Company</label>
                            <input type="text" name="company" class="form-control form-control-custom" placeholder="e.g. Tech Corp" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Start Date</label>
                            <input type="text" name="start_date" class="form-control form-control-custom" placeholder="e.g. Jan 2020" required>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label-custom">End Date</label>
                            <input type="text" name="end_date" class="form-control form-control-custom" placeholder="e.g. Present" required>
                             <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_current" id="is_current_add" value="1">
                                <label class="form-check-label small" for="is_current_add">
                                    I currently work here
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" class="form-control form-control-custom" rows="4" placeholder="Describe your responsibilities..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Experience</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Experience Modal -->
<div class="modal fade" id="editExperienceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editExperienceForm" action="<?= base_url('admin/experience/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_exp_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Job Title</label>
                            <input type="text" name="job_title" id="edit_job_title" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Company</label>
                            <input type="text" name="company" id="edit_company" class="form-control form-control-custom" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Start Date</label>
                            <input type="text" name="start_date" id="edit_start_date" class="form-control form-control-custom" required>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label class="form-label-custom">End Date</label>
                            <input type="text" name="end_date" id="edit_end_date" class="form-control form-control-custom" required>
                             <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_current" id="edit_is_current" value="1">
                                <label class="form-check-label small" for="edit_is_current">
                                    I currently work here
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" id="edit_description" class="form-control form-control-custom" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Experience</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initializeExpHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeExpHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        
    // Current Checkbox Logic
    $('#is_current_add').change(function() {
        if($(this).is(':checked')) {
            $('input[name="end_date"]').val('Present');
        }
    });

    $('#edit_is_current').change(function() {
        if($(this).is(':checked')) {
            $('#edit_end_date').val('Present');
        }
    });

    // Add Experience AJAX
    $('#addExperienceForm').on('submit', function(e) {
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
                    $('#addExperienceModal').modal('hide');
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
    $(document).on('click', '.edit-experience-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("admin/experience/get/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    $('#edit_exp_id').val(data.id);
                    $('#edit_job_title').val(data.job_title);
                    $('#edit_company').val(data.company);
                    $('#edit_start_date').val(data.start_date);
                    $('#edit_end_date').val(data.end_date);
                    $('#edit_description').val(data.description);
                    if(data.is_current == 1) {
                        $('#edit_is_current').prop('checked', true);
                    } else {
                        $('#edit_is_current').prop('checked', false);
                    }
                    $('#editExperienceModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Experience AJAX
    $('#editExperienceForm').on('submit', function(e) {
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
                    $('#editExperienceModal').modal('hide');
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

    // Delete Experience
    $(document).on('click', '.delete-experience-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '<?= site_url("admin/experience/delete/") ?>' + id,
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
initializeExpHandlers();
</script>
