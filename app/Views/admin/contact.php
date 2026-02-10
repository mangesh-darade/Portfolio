
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Contact Settings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contact Info</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-address-book me-2"></i>Public Contact Information</h5>
            </div>
            <div class="card-body-custom">
                <form id="contactSettingsForm">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-custom" value="<?= $contact['email'] ?? '' ?>" required>
                        <small class="text-muted">The email where clients can reach you.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-custom" value="<?= $contact['phone'] ?? '' ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label-custom">Office/Home Location</label>
                        <input type="text" name="address" class="form-control form-control-custom" value="<?= $contact['address'] ?? '' ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Google Maps Iframe (Optional)</label>
                        <textarea name="map_iframe" class="form-control form-control-custom" rows="4"><?= $contact['map_iframe'] ?? '' ?></textarea>
                        <small class="text-muted">Paste the &lt;iframe&gt; code from Google Maps share options.</small>
                    </div>
                    
                    <div class="text-end">
                        <button type="submit" class="btn btn-gradient px-4">
                            <i class="fas fa-save me-2"></i>Save Contact Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-lightbulb me-2"></i>Quick Tips</h5>
            </div>
            <div class="card-body-custom">
                <div class="mb-3">
                    <h6><i class="fas fa-info-circle text-primary me-2"></i>Why this?</h6>
                    <p class="small text-muted">This information is displayed in the footer and contact section of your public portfolio. Keeping it updated ensures potential clients can find you.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <h6><i class="fas fa-map-marked-alt text-success me-2"></i>Getting an Iframe</h6>
                    <p class="small text-muted">Go to Google Maps -> Search for your location -> Click "Share" -> Select "Embed a map" -> Copy HTML.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function initializeContactSettingsHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeContactSettingsHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        $('#contactSettingsForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            
            $.ajax({
                url: '<?= base_url("admin/contact/update") ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message || 'Failed to update settings');
                    }
                }
            });
        });
    });
}

initializeContactSettingsHandlers();
</script>
