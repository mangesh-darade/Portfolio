
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">SEO Configuration</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">SEO</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-search me-2"></i>Global SEO Settings</h5>
            </div>
            <div class="card-body-custom">
                <form id="seoForm" action="<?= base_url('admin/seo/update') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Site Title</label>
                        <input type="text" name="site_title" class="form-control form-control-custom" value="<?= isset($seo['site_title']) ? esc($seo['site_title']) : 'My Portfolio' ?>" required>
                        <div class="form-text text-muted small mt-1">Appears in browser tab and search results.</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Meta Description</label>
                        <textarea name="site_description" class="form-control form-control-custom" rows="3"><?= isset($seo['site_description']) ? esc($seo['site_description']) : '' ?></textarea>
                        <div class="form-text text-muted small mt-1">Brief summary of your site (150-160 chars recommended).</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Meta Keywords</label>
                        <input type="text" name="site_keywords" class="form-control form-control-custom" value="<?= isset($seo['site_keywords']) ? esc($seo['site_keywords']) : '' ?>">
                        <div class="form-text text-muted small mt-1">Comma-separated keywords (e.g. portfolio, developer, php).</div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Author Name</label>
                        <input type="text" name="site_author" class="form-control form-control-custom" value="<?= isset($seo['site_author']) ? esc($seo['site_author']) : '' ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Social Share Image (OG:Image)</label>
                        <div class="d-flex align-items-center gap-3">
                            <?php if(isset($seo['og_image']) && $seo['og_image']): ?>
                                <img src="<?= base_url('uploads/seo/' . $seo['og_image']) ?>" class="rounded border border-light border-opacity-25" width="100">
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <input type="file" name="og_image" class="form-control form-control-custom" accept="image/*">
                                <div class="form-text text-muted small mt-1">Recommended size: 1200x630 pixels.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end pt-3 border-top border-light border-opacity-10">
                        <button type="submit" class="btn btn-gradient px-4">
                            <i class="fas fa-save me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function initializeSeoHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeSeoHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        $('#seoForm').on('submit', function(e) {
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
    });
}
initializeSeoHandlers();
</script>
