    </div> <!-- End Container Fluid -->
    
    <footer class="footer">
        <div class="container-fluid">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= esc($admin_profile['full_name'] ?? 'Admin') ?>. Portfolio Admin v1.0</p>
        </div>
    </footer>
</div> <!-- End Main Content -->

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
    // Toggle Sidebar
    $('#sidebarToggle').click(function() {
        if ($(window).width() <= 991) {
            $('#sidebar').toggleClass('active');
            $('#sidebarOverlay').fadeToggle();
        } else {
            $('#sidebar').toggleClass('collapsed');
            $('.main-content').toggleClass('expanded');
        }
    });

    $('#sidebarOverlay').click(function() {
        $('#sidebar').removeClass('active');
        $(this).fadeOut();
    });

    // Sidebar Active Link Auto-Scroll
    const activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        activeLink.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Initialize AOS
    AOS.init();

    // Notifications Polling
    let currentUnreadCount = <?= $unread_count ?? 0 ?>;
    const notificationSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

    function checkNotifications() {
        $.ajax({
            url: '<?= base_url("admin/check-notifications") ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.unread_count > currentUnreadCount) {
                    // Update count
                    currentUnreadCount = response.unread_count;
                    
                    // Update Badge in header
                    const badge = $('.nav-link .badge.bg-danger');
                    if (badge.length) {
                        badge.text(currentUnreadCount);
                    } else {
                        $('.nav-link .fa-bell').after(`<span class="badge bg-danger badge-sm">${currentUnreadCount}</span>`);
                    }

                    // Alert Sound
                    notificationSound.play().catch(e => console.log('Audio play blocked by browser'));

                    // Toast Notification
                    toastr.info(`New message from ${response.latest_name}`, 'New Notification', {
                        onclick: function() { window.location.href = '<?= base_url("admin/messages") ?>'; }
                    });
                }
            }
        });
    }

    // Start polling every 30 seconds
    setInterval(checkNotifications, 30000);
    
    // Check for flash messages
    <?php if(session()->getFlashdata('success')): ?>
        toastr.success("<?= session()->getFlashdata('success') ?>");
    <?php endif; ?>
    
    <?php if(session()->getFlashdata('error')): ?>
        toastr.error("<?= session()->getFlashdata('error') ?>");
    <?php endif; ?>
</script>

</body>
</html>
