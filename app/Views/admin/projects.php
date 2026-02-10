
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Projects Portfolio</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#addProjectModal">
        <i class="fas fa-plus me-2"></i> Add New Project
    </button>
</div>

<!-- Projects Grid -->
<div class="row">
    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $project): ?>
            <div class="col-md-4 mb-4" data-aos="fade-up">
                <div class="card card-custom h-100 p-0 overflow-hidden">
                    <div class="position-relative" style="height: 220px; overflow: hidden;">
                        <?php if ($project['image']): ?>
                            <img src="<?= base_url('uploads/projects/' . $project['image']) ?>" class="w-100 h-100 object-fit-cover" alt="<?= esc($project['project_name']) ?>">
                        <?php else: ?>
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="position-absolute top-0 end-0 p-2">
                             <?php if ($project['status'] == 1): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2"><?= esc($project['project_name']) ?></h5>
                        <p class="text-muted small mb-3 text-truncate"><?= esc(strip_tags($project['description'])) ?></p>
                        
                        <div class="mb-3">
                            <?php 
                                $techs = explode(',', $project['technologies']);
                                foreach($techs as $tech): 
                            ?>
                                <span class="badge bg-light text-primary border me-1 mb-1"><?= trim(esc($tech)) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i> <?= date('M d, Y', strtotime($project['created_at'])) ?>
                            </small>
                            <div>
                                <button class="btn btn-sm btn-light text-primary me-1 edit-project-btn" data-id="<?= $project['id'] ?>">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-light text-danger delete-project-btn" data-id="<?= $project['id'] ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3">
                <i class="fas fa-folder-open fa-4x"></i>
            </div>
            <h5>No projects found</h5>
            <p class="text-muted">Start by adding your first project to the portfolio.</p>
            <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addProjectModal">
                Add Project
            </button>
        </div>
    <?php endif; ?>
</div>

<!-- Add Project Modal -->
<div class="modal fade" id="addProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProjectForm" action="<?= site_url('admin/projects/add') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label-custom">Project Name</label>
                                <input type="text" name="project_name" class="form-control form-control-custom" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-custom">Description</label>
                                <textarea name="description" class="form-control form-control-custom" rows="6" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-custom">Technologies (comma separated)</label>
                                <input type="text" name="technologies" class="form-control form-control-custom" placeholder="PHP, CodeIgniter, MySQL" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label-custom">Project Image</label>
                                <div class="card bg-light border-dashed text-center p-3" onclick="document.getElementById('projectImage').click()" style="cursor: pointer; border: 2px dashed #ccc;">
                                    <div class="py-4" id="imagePreviewContainer">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Click to upload image</p>
                                    </div>
                                    <img id="imagePreview" src="#" class="img-fluid rounded d-none" style="max-height: 150px;">
                                </div>
                                <input type="file" name="image" id="projectImage" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Save Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm" action="<?= site_url('admin/projects/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_project_id">
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label-custom">Project Name</label>
                                <input type="text" name="project_name" id="edit_project_name" class="form-control form-control-custom" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-custom">Description</label>
                                <textarea name="description" id="edit_description" class="form-control form-control-custom" rows="6" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label-custom">Technologies</label>
                                <input type="text" name="technologies" id="edit_technologies" class="form-control form-control-custom" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label-custom">Project Image</label>
                                <div class="card bg-light border-dashed text-center p-3" onclick="document.getElementById('editProjectImage').click()" style="cursor: pointer; border: 2px dashed #ccc;">
                                    <img id="editImagePreview" src="#" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                                <input type="file" name="image" id="editProjectImage" class="d-none" accept="image/*" onchange="previewEditImage(this)">
                                <small class="text-muted d-block mt-2 text-center">Click image to change (optional)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('imagePreview');
                var container = document.getElementById('imagePreviewContainer');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                container.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewEditImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('editImagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }


    // Wait for jQuery to load before initializing
// Vanilla JS interception
document.addEventListener('submit', function(e) {
    if (e.target && (e.target.id === 'addProjectForm' || e.target.id === 'editProjectForm')) {
        if (typeof jQuery !== 'undefined') {
            e.preventDefault();
        }
    }
});

    function initializeProjectHandlers() {
        if (typeof jQuery === 'undefined') {
            // jQuery not loaded yet, wait and try again
            setTimeout(initializeProjectHandlers, 50);
            return;
        }
        
        // jQuery is loaded, now we can use $
        jQuery(document).ready(function($) {
        // Prevent double submission via HTML form
        $('form').on('submit', function(e) { e.preventDefault(); });
        // Add Project
        $('#addProjectForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            
            // Debug: Log FormData contents
            console.log('=== FormData Contents ===');
            for (var pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            console.log('========================');
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
            
            $.ajax({
                url: 'projects/add', // More robust relative URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        $('#addProjectModal').modal('hide');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        if (response.errors) {
                            $.each(response.errors, function(key, val) {
                                toastr.error(val);
                            });
                        } else {
                            toastr.error(response.message || 'An error occurred');
                        }
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Status:', xhr.status);
                    
                    // Try to parse error response
                    try {
                        var errorResponse = JSON.parse(xhr.responseText);
                        toastr.error(errorResponse.message || 'An error occurred while saving the project.');
                    } catch(e) {
                        if (xhr.status === 403) {
                            toastr.error('Security token validation failed. Please refresh the page and try again.');
                        } else if (xhr.status === 404) {
                            toastr.error('Route not found. Please check the URL configuration.');
                        } else if (xhr.status === 500) {
                            toastr.error('Server error. Please check the error logs.');
                        } else {
                            toastr.error('An error occurred while saving the project. Please check the console for details.');
                        }
                    }
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Open Edit Modal
        $(document).on('click', '.edit-project-btn', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url("admin/projects/get/") ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        var data = response.data;
                        $('#edit_project_id').val(data.id);
                        $('#edit_project_name').val(data.project_name);
                        $('#edit_description').val(data.description);
                        $('#edit_technologies').val(data.technologies);
                        
                        if(data.image) {
                            $('#editImagePreview').attr('src', '<?= base_url("uploads/projects/") ?>' + data.image);
                        } else {
                             $('#editImagePreview').attr('src', 'https://via.placeholder.com/150');
                        }
                        
                        $('#editProjectModal').modal('show');
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });

        // Update Project
        $('#editProjectForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
            
            $.ajax({
                url: 'projects/update', // More robust relative URL
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                        $('#editProjectModal').modal('hide');
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
                    toastr.error('An error occurred while updating the project. Please try again.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Delete Project
        $(document).on('click', '.delete-project-btn', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this project? This cannot be undone.')) {
                $.ajax({
                    url: '<?= site_url("admin/projects/delete/") ?>' + id,
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
                        toastr.error('An error occurred while deleting the project.');
                    }
                });
            }
        });
        
        // Reset add project form when modal is closed
        $('#addProjectModal').on('hidden.bs.modal', function () {
            document.getElementById('addProjectForm').reset();
            var preview = document.getElementById('imagePreview');
            var container = document.getElementById('imagePreviewContainer');
            preview.src = '#';
            preview.classList.add('d-none');
            container.classList.remove('d-none');
        });
        
        // Reset edit project form when modal is closed
        $('#editProjectModal').on('hidden.bs.modal', function () {
            document.getElementById('editProjectForm').reset();
        });
        }); // End jQuery(document).ready
    } // End initializeProjectHandlers
    
    // Start initialization (will wait for jQuery)
    initializeProjectHandlers();
</script>
