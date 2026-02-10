
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Inbox Messages</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Messages</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <h5 class="card-title-custom"><i class="fas fa-envelope me-2"></i>Recent Messages</h5>
            </div>
            <div class="card-body-custom">
                <div class="table-responsive">
                    <table class="table table-custom w-100" id="messagesTable">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $msg): ?>
                                    <tr class="<?= $msg['is_read'] == 0 ? 'fw-bold' : '' ?>">
                                        <td>
                                            <?php if ($msg['is_read'] == 0): ?>
                                                <span class="badge bg-danger">New</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Read</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($msg['name']) ?></td>
                                        <td><?= esc($msg['email']) ?></td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;">
                                                <?= esc($msg['message']) ?>
                                            </div>
                                        </td>
                                        <td><?= date('M d, Y H:i', strtotime($msg['created_at'])) ?></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-light text-primary view-message-btn" 
                                                    data-id="<?= $msg['id'] ?>" 
                                                    data-name="<?= esc($msg['name']) ?>"
                                                    data-email="<?= esc($msg['email']) ?>"
                                                    data-message="<?= esc($msg['message']) ?>"
                                                    data-date="<?= date('M d, Y H:i', strtotime($msg['created_at'])) ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light text-danger delete-message-btn" data-id="<?= $msg['id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-envelope-open fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No messages found in your inbox.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Message Modal -->
<div class="modal fade" id="viewMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="text-muted small d-block">From</label>
                    <h6 id="msg_name" class="fw-bold mb-0"></h6>
                    <small id="msg_email" class="text-primary"></small>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Received On</label>
                    <small id="msg_date"></small>
                </div>
                <hr>
                <div class="mb-0">
                    <label class="text-muted small d-block mb-2">Message</label>
                    <div id="msg_content" class="p-3 bg-light rounded" style="white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0 pb-4 px-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <a id="msg_reply_btn" href="#" class="btn btn-gradient">Reply via Email</a>
            </div>
        </div>
    </div>
</div>

<script>
function initializeMessageHandlers() {
    if (typeof jQuery === 'undefined') {
        setTimeout(initializeMessageHandlers, 50);
        return;
    }
    
    jQuery(document).ready(function($) {
        // View Message
        $('.view-message-btn').on('click', function() {
            var btn = $(this);
            var id = btn.data('id');
            
            $('#msg_name').text(btn.data('name'));
            $('#msg_email').text(btn.data('email'));
            $('#msg_date').text(btn.data('date'));
            $('#msg_content').text(btn.data('message'));
            $('#msg_reply_btn').attr('href', 'mailto:' + btn.data('email'));
            
            $('#viewMessageModal').modal('show');
            
            // Mark as read via AJAX
            $.ajax({
                url: '<?= base_url("admin/messages/read/") ?>' + id,
                type: 'POST',
                success: function() {
                    // Optional: Update UI without reload
                }
            });
        });

        // Delete Message
        $('.delete-message-btn').on('click', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this message?')) {
                $.ajax({
                    url: '<?= base_url("admin/messages/delete/") ?>' + id,
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

        // DataTable
        if ($('#messagesTable tbody tr').length > 1 || !$('#messagesTable tbody tr td').hasClass('text-center')) {
            $('#messagesTable').DataTable({
                "order": [[ 4, "desc" ]],
                "language": {
                    "searchPlaceholder": "Search messages..."
                }
            });
        }
    });
}

initializeMessageHandlers();
</script>
