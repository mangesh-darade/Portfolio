<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Display Features</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Display Features</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom">Manage Portfolio Sections</h5>
                <p class="text-muted small mb-0">Enable or disable sections displayed on your public portfolio.</p>
            </div>
            <div class="card-body-custom">
                <form id="featureSettingsForm" onsubmit="return false;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 100px;">Order</th>
                                    <th>Section Name</th>
                                    <th>Identifier</th>
                                    <th class="text-end">Visibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($features)): ?>
                                    <?php foreach ($features as $feature): ?>
                                        <tr>
                                            <td>
                                                <input type="number" name="features[<?= $feature['id'] ?>][order]" value="<?= $feature['display_order'] ?>" class="form-control form-control-sm text-center" style="width: 70px;">
                                            </td>
                                            <td>
                                                <span class="fw-bold text-white"><?= esc($feature['feature_name']) ?></span>
                                            </td>
                                            <td>
                                                <code class="text-info"><?= esc($feature['feature_key']) ?></code>
                                            </td>
                                            <td class="text-end">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" name="features[<?= $feature['id'] ?>][enabled]" value="1" <?= $feature['is_enabled'] ? 'checked' : '' ?> style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4">No features found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#featureSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        var btn = $(this).find('button[type="submit"]');
        var originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');
        
        $.ajax({
            url: '<?= base_url('admin/features/update') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                btn.prop('disabled', false).html(originalText);
            },
            error: function() {
                toastr.error('An error occurred while saving.');
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
