
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Education Overview</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Education</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addEducationModal">
        <i class="fas fa-plus me-2"></i> Add New Education
    </button>
</div>

<!-- Education Table Card -->
<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table id="educationTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Dates</th>
                        <th>GPA</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($educations)): ?>
                        <?php foreach ($educations as $edu): ?>
                            <tr>
                                <td><?= $edu['id'] ?></td>
                                <td>
                                    <span class="fw-bold"><?= esc($edu['degree']) ?></span>
                                </td>
                                <td><?= esc($edu['institution']) ?></td>
                                <td>
                                    <?= esc($edu['year_start']) ?> - <?= esc($edu['year_end']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= esc($edu['gpa']) ?></span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary me-1 edit-education-btn" data-id="<?= $edu['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger delete-education-btn" data-id="<?= $edu['id'] ?>">
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

<!-- Add Education Modal -->
<div class="modal fade" id="addEducationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEducationForm" action="<?= base_url('admin/education/add') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Degree</label>
                            <input type="text" name="degree" class="form-control form-control-custom" placeholder="e.g. B.Sc. Computer Science" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Institution</label>
                            <input type="text" name="institution" class="form-control form-control-custom" placeholder="e.g. Stanford University" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Start Year</label>
                            <input type="number" name="year_start" class="form-control form-control-custom" placeholder="e.g. 2018" required>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label class="form-label-custom">End Year</label>
                            <input type="number" name="year_end" class="form-control form-control-custom" placeholder="e.g. 2022" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">GPA (Optional)</label>
                            <input type="text" name="gpa" class="form-control form-control-custom" placeholder="e.g. 3.8">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" class="form-control form-control-custom" rows="3" placeholder="Additional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Education</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Education Modal -->
<div class="modal fade" id="editEducationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEducationForm" action="<?= base_url('admin/education/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_edu_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Degree</label>
                            <input type="text" name="degree" id="edit_degree" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Institution</label>
                            <input type="text" name="institution" id="edit_institution" class="form-control form-control-custom" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Start Year</label>
                            <input type="number" name="year_start" id="edit_year_start" class="form-control form-control-custom" required>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label class="form-label-custom">End Year</label>
                            <input type="number" name="year_end" id="edit_year_end" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">GPA (Optional)</label>
                            <input type="text" name="gpa" id="edit_gpa" class="form-control form-control-custom">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Description</label>
                        <textarea name="description" id="edit_edu_description" class="form-control form-control-custom" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Education</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initializeEduHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeEduHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {

    // Add Education AJAX
    $('#addEducationForm').on('submit', function(e) {
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
                    $('#addEducationModal').modal('hide');
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
    $(document).on('click', '.edit-education-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("admin/education/get/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    $('#edit_edu_id').val(data.id);
                    $('#edit_degree').val(data.degree);
                    $('#edit_institution').val(data.institution);
                    $('#edit_year_start').val(data.year_start);
                    $('#edit_year_end').val(data.year_end);
                    $('#edit_gpa').val(data.gpa);
                    $('#edit_edu_description').val(data.description);
                    $('#editEducationModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Education AJAX
    $('#editEducationForm').on('submit', function(e) {
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
                    $('#editEducationModal').modal('hide');
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

    // Delete Education
    $(document).on('click', '.delete-education-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '<?= site_url("admin/education/delete/") ?>' + id,
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
initializeEduHandlers();
</script>
