
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Skills Overview</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Skills</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addSkillModal">
        <i class="fas fa-plus me-2"></i> Add New Skill
    </button>
</div>

<!-- Skills Table Card -->
<div class="card-custom">
    <div class="card-body-custom">
        <div class="table-responsive">
            <table id="skillsTable" class="table table-custom w-100">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Skill Name</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th width="15%" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($skills)): ?>
                        <?php foreach ($skills as $skill): ?>
                            <tr>
                                <td><?= $skill['id'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm rounded bg-light d-flex align-items-center justify-content-center me-2 text-primary">
                                            <i class="fas fa-code"></i>
                                        </div>
                                        <span class="fw-bold"><?= esc($skill['skill_name']) ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= esc($skill['category']) ?></span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 6px; width: 80px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $skill['skill_level'] ?>%"></div>
                                        </div>
                                        <span class="small"><?= $skill['skill_level'] ?>%</span>
                                    </div>
                                </td>
                                <td><?= $skill['display_order'] ?></td>
                                <td>
                                    <?php if ($skill['status'] == 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-light text-primary me-1 edit-skill-btn" data-id="<?= $skill['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-danger delete-skill-btn" data-id="<?= $skill['id'] ?>">
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

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSkillForm" action="<?= site_url('admin/skills/add') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Skill Name</label>
                        <input type="text" name="skill_name" class="form-control form-control-custom" placeholder="e.g. PHP, React" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Category</label>
                            <select name="category" class="form-select form-control-custom" required>
                                <option value="Frontend">Frontend</option>
                                <option value="Backend">Backend</option>
                                <option value="Database">Database</option>
                                <option value="Tools">Tools</option>
                                <option value="Design">Design</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Level (0-100)</label>
                            <input type="number" name="skill_level" class="form-control form-control-custom" min="0" max="100" value="50" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Display Order</label>
                        <input type="number" name="display_order" class="form-control form-control-custom" value="0">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSkillForm" action="<?= site_url('admin/skills/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_skill_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label-custom">Skill Name</label>
                        <input type="text" name="skill_name" id="edit_skill_name" class="form-control form-control-custom" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Category</label>
                            <select name="category" id="edit_category" class="form-select form-control-custom" required>
                                <option value="Frontend">Frontend</option>
                                <option value="Backend">Backend</option>
                                <option value="Database">Database</option>
                                <option value="Tools">Tools</option>
                                <option value="Design">Design</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Level (0-100)</label>
                            <input type="number" name="skill_level" id="edit_skill_level" class="form-control form-control-custom" min="0" max="100" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Display Order</label>
                        <input type="number" name="display_order" id="edit_display_order" class="form-control form-control-custom">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Vanilla JS to ensure preventDefault works even before jQuery
document.addEventListener('submit', function(e) {
    if (e.target && (e.target.id === 'addSkillForm' || e.target.id === 'editSkillForm' || e.target.id === 'addProjectForm' || e.target.id === 'editProjectForm')) {
        // Stop default reload immediately
        if (typeof jQuery !== 'undefined') {
            e.preventDefault();
        }
    }
});

function initializeSkillHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeSkillHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
    // Prevent double submission via HTML form
    $('form').on('submit', function(e) { e.preventDefault(); });

    // Initialize DataTable
    $('#skillsTable').DataTable({
        "order": [[ 4, "asc" ]],
        "pageLength": 10,
        "language": {
            "search": "",
            "searchPlaceholder": "Search skills..."
        },
        "dom": '<"d-flex justify-content-between align-items-center mb-3"f>t<"d-flex justify-content-between align-items-center mt-3"ip>',
        "fnDrawCallback": function() {
            $('.dataTables_filter input').addClass('form-control form-control-custom');
        }
    });

    // Add Skill AJAX
    $('#addSkillForm').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: 'skills/add', // More robust relative URL
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#addSkillModal').modal('hide');
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
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                toastr.error('An error occurred. Please try again.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Open Edit Modal
    $(document).on('click', '.edit-skill-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= base_url("admin/skills/get/") ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    $('#edit_skill_id').val(data.id);
                    $('#edit_skill_name').val(data.skill_name);
                    $('#edit_category').val(data.category);
                    $('#edit_skill_level').val(data.skill_level);
                    $('#edit_display_order').val(data.display_order);
                    $('#editSkillModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    // Update Skill AJAX
    $('#editSkillForm').on('submit', function(e) {
        e.preventDefault();
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
        
        $.ajax({
            url: 'skills/update', // More robust relative URL
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#editSkillModal').modal('hide');
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
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                toastr.error('An error occurred while updating. Please try again.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Delete Skill
    $(document).on('click', '.delete-skill-btn', function() {
        var skillId = $(this).data('id');
        if (confirm('Are you sure you want to delete this skill?')) {
            $.ajax({
                url: '<?= site_url("admin/skills/delete/") ?>' + skillId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while deleting the skill.');
                }
            });
        }
    });
    
    // Reset add skill form when modal is closed
    $('#addSkillModal').on('hidden.bs.modal', function () {
        document.getElementById('addSkillForm').reset();
    });
    
    // Reset edit skill form when modal is closed
    $('#editSkillModal').on('hidden.bs.modal', function () {
        document.getElementById('editSkillForm').reset();
    });
    }); // End jQuery(document).ready
} // End initializeSkillHandlers

// Start initialization (will wait for jQuery)
initializeSkillHandlers();
</script>
