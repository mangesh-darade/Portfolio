<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-brand">
            Admin<span>.</span>
        </a>
    </div>
    
    <div class="admin-profile-sidebar text-center py-4 border-bottom border-light border-opacity-10">
        <?php 
            $profileImg = 'https://ui-avatars.com/api/?name=' . urlencode($admin_profile['full_name'] ?? 'Admin') . '&size=128&background=random';
            if (isset($admin_profile['profile_image']) && $admin_profile['profile_image']) {
                $profileImg = base_url('uploads/profile/' . $admin_profile['profile_image']);
            }
        ?>
        <div class="position-relative d-inline-block">
            <img src="<?= $profileImg ?>" class="avatar-lg rounded-circle mb-3 border border-2 border-primary p-1" alt="Admin" style="width: 80px; height: 80px; object-fit: cover;">
            <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-dark rounded-circle p-1" style="width: 15px; height: 15px;"></span>
        </div>
        <h6 class="mb-0 text-white fw-bold"><?= esc($admin_profile['full_name'] ?? 'Admin User') ?></h6>
        <small class="text-muted">Administrator</small>
    </div>
    
    <div class="sidebar-menu">
        <ul class="sidebar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'profile' ? 'active' : '' ?>" href="<?= base_url('admin/profile') ?>">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile Settings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'skills' ? 'active' : '' ?>" href="<?= base_url('admin/skills') ?>">
                    <i class="fas fa-code"></i>
                    <span>Skills</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'projects' ? 'active' : '' ?>" href="<?= base_url('admin/projects') ?>">
                    <i class="fas fa-briefcase"></i>
                    <span>Projects</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'education' ? 'active' : '' ?>" href="<?= base_url('admin/education') ?>">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Education</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'messages' ? 'active' : '' ?>" href="<?= base_url('admin/messages') ?>">
                    <i class="fas fa-envelope"></i>
                    <span>Messages</span>
                    <?php if (isset($unread_count) && $unread_count > 0): ?>
                        <span class="badge bg-danger ms-auto"><?= $unread_count ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'experience' ? 'active' : '' ?>" href="<?= base_url('admin/experience') ?>">
                    <i class="fas fa-history"></i>
                    <span>Experience</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'services' ? 'active' : '' ?>" href="<?= base_url('admin/services') ?>">
                    <i class="fas fa-concierge-bell"></i>
                    <span>Services</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'testimonials' ? 'active' : '' ?>" href="<?= base_url('admin/testimonials') ?>">
                    <i class="fas fa-comment-alt"></i>
                    <span>Testimonials</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'seo' ? 'active' : '' ?>" href="<?= base_url('admin/seo') ?>">
                    <i class="fas fa-search"></i>
                    <span>SEO Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'email_settings' ? 'active' : '' ?>" href="<?= base_url('admin/email-settings') ?>">
                    <i class="fas fa-mail-bulk"></i>
                    <span>Email Settings</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'features' ? 'active' : '' ?>" href="<?= base_url('admin/features') ?>">
                    <i class="fas fa-toggle-on"></i>
                    <span>Display Features</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'theme' ? 'active' : '' ?>" href="<?= base_url('admin/theme') ?>">
                    <i class="fas fa-paint-brush"></i>
                    <span>Theme Customizer</span>
                </a>
            </li>
            
            <li class="nav-item mt-4 mb-2">
                <div class="px-3 text-muted small text-uppercase fw-bold tracking-wider" style="letter-spacing: 0.1rem;">Settings</div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'contact' ? 'active' : '' ?>" href="<?= base_url('admin/contact') ?>">
                    <i class="fas fa-address-book"></i>
                    <span>Contact Info</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?= isset($active_menu) && $active_menu == 'site' ? 'active' : '' ?>" href="<?= base_url('admin/site-settings') ?>">
                    <i class="fas fa-cog"></i>
                    <span>Site Settings</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Main Content Wrapper -->
<div class="main-content">
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand top-navbar">
        <div class="container-fluid">
            <button class="btn btn-link link-light text-decoration-none" id="sidebarToggle">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            
            <div class="navbar-brand ms-3 d-none d-md-block">
                <span class="brand-text"><?= $page_title ?? 'Dashboard' ?></span>
            </div>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-3 d-none d-md-block">
                    <a href="<?= base_url() ?>" target="_blank" class="nav-link text-muted">
                        <i class="fas fa-external-link-alt me-1"></i> View Site
                    </a>
                </li>
                <!-- Notifications -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <?php if (isset($unread_count) && $unread_count > 0): ?>
                            <span class="badge bg-danger badge-sm"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <h6 class="dropdown-header">Unread Messages</h6>
                        <div class="dropdown-divider"></div>
                        <?php if (!empty($unread_messages)): ?>
                            <?php foreach ($unread_messages as $msg): ?>
                                <a class="dropdown-item" href="<?= base_url('admin/messages') ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-sm rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <p class="mb-0 small fw-bold text-white"><?= esc($msg['name']) ?></p>
                                            <p class="mb-0 text-muted x-small text-truncate" style="max-width: 200px;"><?= esc($msg['message']) ?></p>
                                            <span class="text-muted x-small"><?= date('M d, H:i', strtotime($msg['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center small text-primary" href="<?= base_url('admin/messages') ?>">View All Messages</a>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle text-success mb-2"></i>
                                <p class="mb-0 small text-muted">No new messages</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </li>
                
                <!-- Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <?php 
                            $profileImgNav = 'https://ui-avatars.com/api/?name=' . urlencode($admin_profile['full_name'] ?? 'Admin') . '&background=6c5ce7&color=fff';
                            if (isset($admin_profile['profile_image']) && $admin_profile['profile_image']) {
                                $profileImgNav = base_url('uploads/profile/' . $admin_profile['profile_image']);
                            }
                        ?>
                        <img src="<?= $profileImgNav ?>" class="avatar-sm rounded-circle me-2" alt="Admin" style="width: 32px; height: 32px; object-fit: cover;">
                        <span class="d-none d-sm-inline"><?= esc(explode(' ', $admin_profile['full_name'] ?? 'Admin')[0]) ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a class="dropdown-item" href="<?= base_url('admin/site-settings') ?>">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
