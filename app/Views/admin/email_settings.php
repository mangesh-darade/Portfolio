
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Email Configuration</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Email Settings</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-server me-2"></i>SMTP Server Settings</h5>
            </div>
            <div class="card-body-custom">
                <form id="emailSettingsForm" action="<?= base_url('admin/email-settings/update') ?>" method="POST" onsubmit="return false;">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label-custom">Protocol</label>
                            <select name="protocol" class="form-select form-control-custom">
                                <option value="smtp" <?= (isset($settings['protocol']) && $settings['protocol'] == 'smtp') ? 'selected' : '' ?>>SMTP</option>
                                <option value="mail" <?= (isset($settings['protocol']) && $settings['protocol'] == 'mail') ? 'selected' : '' ?>>PHP Mail</option>
                                <option value="sendmail" <?= (isset($settings['protocol']) && $settings['protocol'] == 'sendmail') ? 'selected' : '' ?>>Sendmail</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">SMTP Crypto</label>
                            <select name="smtp_crypto" class="form-select form-control-custom">
                                <option value="tls" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'tls') ? 'selected' : '' ?>>TLS</option>
                                <option value="ssl" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'ssl') ? 'selected' : '' ?>>SSL</option>
                                <option value="" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == '') ? 'selected' : '' ?>>None</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">SMTP Host</label>
                        <input type="text" name="smtp_host" class="form-control form-control-custom" value="<?= isset($settings['smtp_host']) ? esc($settings['smtp_host']) : '' ?>" placeholder="e.g. smtp.gmail.com">
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                             <label class="form-label-custom">SMTP User</label>
                             <input type="text" name="smtp_user" class="form-control form-control-custom" value="<?= isset($settings['smtp_user']) ? esc($settings['smtp_user']) : '' ?>" placeholder="email@example.com">
                        </div>
                        <div class="col-md-4">
                             <label class="form-label-custom">SMTP Port</label>
                             <input type="number" name="smtp_port" class="form-control form-control-custom" value="<?= isset($settings['smtp_port']) ? esc($settings['smtp_port']) : '587' ?>">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label-custom">SMTP Password</label>
                        <input type="password" name="smtp_pass" class="form-control form-control-custom" value="<?= isset($settings['smtp_pass']) ? esc($settings['smtp_pass']) : '' ?>" placeholder="********">
                    </div>
                    
                    <hr class="border-light border-opacity-10 my-4">
                    
                    <h5 class="card-title-custom mb-4"><i class="fas fa-envelope-open-text me-2"></i>Sender Details</h5>
                    
                    <div class="row mb-4">
                         <div class="col-md-6">
                             <label class="form-label-custom">From Email</label>
                             <input type="email" name="from_email" class="form-control form-control-custom" value="<?= isset($settings['from_email']) ? esc($settings['from_email']) : '' ?>" placeholder="no-reply@domain.com">
                        </div>
                         <div class="col-md-6">
                             <label class="form-label-custom">From Name</label>
                             <input type="text" name="from_name" class="form-control form-control-custom" value="<?= isset($settings['from_name']) ? esc($settings['from_name']) : '' ?>" placeholder="Portfolio Admin">
                        </div>
                    </div>
                    
                    <div class="text-end pt-3 border-top border-light border-opacity-10 d-flex justify-content-between align-items-center">
                        <button type="button" id="testEmailBtn" class="btn btn-outline-info px-4">
                            <i class="fas fa-paper-plane me-2"></i>Send Test Email
                        </button>
                        <button type="submit" class="btn btn-gradient px-4">
                            <i class="fas fa-save me-2"></i>Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function initializeEmailSettingsHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeEmailSettingsHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        $('#emailSettingsForm').on('submit', function(e) {
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
                    } else {
                        toastr.error(response.message);
                    }
                    submitBtn.prop('disabled', false).html(originalText);
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
        // Send Test Email
        jQuery('#testEmailBtn').on('click', function() {
            var btn = jQuery(this);
            var orig = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
            jQuery.ajax({
                url: '<?= base_url('admin/email-settings/test') ?>',
                type: 'POST',
                data: { '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                    btn.prop('disabled', false).html(orig);
                },
                error: function() {
                    toastr.error('Failed to send test email.');
                    btn.prop('disabled', false).html(orig);
                }
            });
        });
    });
}
initializeEmailSettingsHandlers();
</script>
