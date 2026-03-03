
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Profile Settings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4 mb-4">
        <div class="card-custom h-100 text-center">
            <div class="card-body-custom pt-5">
                <div class="position-relative d-inline-block mb-4">
                    <?php if(isset($profile['profile_image']) && $profile['profile_image']): ?>
                        <img src="<?= base_url('uploads/profile/' . $profile['profile_image']) ?>" class="avatar-lg rounded-circle shadow-lg" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid var(--border-color); padding: 5px;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=Admin&size=150&background=6c5ce7&color=fff" class="avatar-lg rounded-circle shadow-lg" style="width: 150px; height: 150px; border: 4px solid var(--border-color); padding: 5px;">
                    <?php endif; ?>
                    <button class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0 mb-2 me-2 shadow" onclick="document.getElementById('profile_image').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                
                <h4 class="fw-bold mb-1"><?= $profile['full_name'] ?? 'Admin User' ?></h4>
                <p class="text-muted mb-4"><?= $profile['bio'] ?? 'Full Stack Developer' ?></p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <?php if(isset($profile['github'])): ?>
                        <a href="<?= $profile['github'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-github"></i></a>
                    <?php endif; ?>
                    <?php if(isset($profile['linkedin'])): ?>
                        <a href="<?= $profile['linkedin'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-linkedin-in"></i></a>
                    <?php endif; ?>
                    <?php if(isset($profile['twitter'])): ?>
                        <a href="<?= $profile['twitter'] ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fab fa-twitter"></i></a>
                    <?php endif; ?>
                </div>
                
                <?php if(isset($profile['updated_at'])): ?>
                    <small class="text-muted d-block">Last updated: <?= date('M d, Y', strtotime($profile['updated_at'])) ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-user-edit me-2"></i>Edit Profile Details</h5>
            </div>
            <div class="card-body-custom">
                <form id="profileForm" action="<?= site_url('admin/profile/update') ?>" method="POST" enctype="multipart/form-data" onsubmit="return false;">
                    <?= csrf_field() ?>
                    <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*" onchange="previewImage(this)">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Full Name</label>
                            <input type="text" name="full_name" class="form-control form-control-custom" value="<?= $profile['full_name'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-custom" value="<?= $profile['email'] ?? '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Resume (PDF)</label>
                        <div class="input-group">
                            <input type="file" name="resume" class="form-control form-control-custom" accept="application/pdf">
                            <?php if(isset($profile['resume']) && $profile['resume']): ?>
                                <a href="<?= base_url('uploads/resume/' . $profile['resume']) ?>" target="_blank" class="btn btn-outline-secondary d-flex align-items-center">
                                    <i class="fas fa-file-pdf me-2"></i> View Current
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="form-text text-muted small mt-1"><i class="fas fa-info-circle me-1"></i> Upload your CV/Resume in PDF format (Max 10MB).</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Professional Bio</label>
                        <textarea name="bio" class="form-control form-control-custom" rows="5"><?= $profile['bio'] ?? '' ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Phone</label>
                            <input type="text" name="phone" class="form-control form-control-custom" value="<?= $profile['phone'] ?? '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label-custom">Location</label>
                            <input type="text" name="address" class="form-control form-control-custom" value="<?= $profile['address'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3 fw-bold text-muted text-uppercase small">Social Links</h6>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom"><i class="fab fa-github me-1"></i> GitHub</label>
                            <input type="url" name="github" class="form-control form-control-custom" value="<?= $profile['github'] ?? '' ?>" placeholder="https://github.com/username">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom"><i class="fab fa-linkedin me-1"></i> LinkedIn</label>
                            <input type="url" name="linkedin" class="form-control form-control-custom" value="<?= $profile['linkedin'] ?? '' ?>" placeholder="https://linkedin.com/in/username">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom"><i class="fab fa-twitter me-1"></i> Twitter</label>
                            <input type="url" name="twitter" class="form-control form-control-custom" value="<?= $profile['twitter'] ?? '' ?>" placeholder="https://twitter.com/username">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-gradient px-4">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password -->
<div class="row mt-4">
    <div class="col-lg-8 offset-lg-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-lock me-2"></i>Change Password</h5>
            </div>
            <div class="card-body-custom">
                <form id="changePasswordForm" onsubmit="return false;">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Current Password</label>
                            <input type="password" name="current_password" id="currentPassword" class="form-control form-control-custom" placeholder="Enter current password" autocomplete="current-password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">New Password</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control form-control-custom" placeholder="Min. 8 characters" autocomplete="new-password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label-custom">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirmPassword" class="form-control form-control-custom" placeholder="Repeat new password" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-danger px-4">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Update all avatar images
                var avatars = document.querySelectorAll('.avatar-lg');
                avatars.forEach(function(avatar) {
                    avatar.src = e.target.result;
                });
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Wait for jQuery to load before initializing
// Vanilla JS interception
document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'profileForm') {
        if (typeof jQuery !== 'undefined') {
            e.preventDefault();
        }
    }
});

function initializeProfileHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeProfileHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        // Prevent double submission via HTML form
        $('form').on('submit', function(e) { e.preventDefault(); });

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: '<?= site_url("admin/profile/update") ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            if (response.errors) {
                                $.each(response.errors, function(key, val) {
                                    toastr.error(val);
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            });
        }); // End jQuery(document).ready
    } // End initializeProfileHandlers
    
    // Start initialization (will wait for jQuery)
    initializeProfileHandlers();

    // Change Password Handler
    function initPasswordForm() {
        if (typeof jQuery === 'undefined') { setTimeout(initPasswordForm, 50); return; }
        jQuery('#changePasswordForm').on('submit', function(e) {
            e.preventDefault();
            var btn = jQuery(this).find('button[type="submit"]');
            var orig = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Changing...');
            jQuery.ajax({
                url: '<?= base_url('admin/profile/change-password') ?>',
                type: 'POST',
                data: jQuery(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                        jQuery('#changePasswordForm')[0].reset();
                    } else {
                        toastr.error(res.message);
                    }
                    btn.prop('disabled', false).html(orig);
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                    btn.prop('disabled', false).html(orig);
                }
            });
        });
    }
    initPasswordForm();
</script>
