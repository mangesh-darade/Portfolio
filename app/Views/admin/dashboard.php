
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if(isset($last_login) && $last_login): ?>
            <span class="text-muted small"><i class="fas fa-clock me-1"></i>Last login: <?= date('M d, Y H:i', strtotime($last_login)) ?></span>
            <?php endif; ?>
            <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-external-link-alt me-2"></i>View Site
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $total_skills ?? 0 ?></div>
                        <div class="stat-label">Active Skills</div>
                    </div>
                    <div class="stat-icon primary">
                        <i class="fas fa-code"></i>
                    </div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-check-circle me-1"></i> System Updated
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= $total_projects ?? 0 ?></div>
                        <div class="stat-label">Live Projects</div>
                    </div>
                    <div class="stat-icon success">
                        <i class="fas fa-briefcase"></i>
                    </div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up me-1"></i> Published
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-value"><?= number_format($total_views ?? 0) ?></div>
                        <div class="stat-label">Total Views</div>
                    </div>
                    <div class="stat-icon warning">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-chart-line me-1"></i> Real-time tracking
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4" data-aos="fade-up" data-aos-delay="400">
            <a href="<?= base_url('admin/messages') ?>" class="text-decoration-none">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-value"><?= $unread_count ?? 0 ?></div>
                            <div class="stat-label">Unread Messages</div>
                        </div>
                        <div class="stat-icon danger">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    <div class="stat-trend <?= (isset($unread_count) && $unread_count > 0) ? 'up' : '' ?>">
                        <i class="fas <?= (isset($unread_count) && $unread_count > 0) ? 'fa-arrow-up' : 'fa-check' ?> me-1"></i> 
                        <?= (isset($unread_count) && $unread_count > 0) ? $unread_count . ' unread' : 'All read' ?>
                    </div>
                </div>
            </a>
        </div>

        <!-- Total Messages -->
        <div class="col-md-12 col-lg-12">
            <div class="alert" style="background: rgba(108,92,231,0.08); border: 1px solid rgba(108,92,231,0.2); border-radius: 12px; color: var(--text-color);" role="alert">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <strong><?= number_format($total_messages ?? 0) ?></strong> total messages received &nbsp;·&nbsp;
                <strong><?= number_format($total_views ?? 0) ?></strong> total portfolio views &nbsp;·&nbsp;
                <strong><?= $total_projects ?? 0 ?></strong> live projects &nbsp;·&nbsp;
                <strong><?= $total_skills ?? 0 ?></strong> active skills
            </div>
        </div>
    </div>

    <!-- Recent Activity & Charts -->
    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-8 mb-4">
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">Quick Actions</h5>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="<?= base_url('admin/projects/add') ?>" class="btn btn-outline-primary w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <span>Add Project</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?= base_url('admin/skills/add') ?>" class="btn btn-outline-success w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-star fa-2x mb-2"></i>
                                <span>Add Skill</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?= base_url('admin/profile') ?>" class="btn btn-outline-info w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-user-edit fa-2x mb-2"></i>
                                <span>Edit Profile</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="<?= base_url('admin/messages') ?>" class="btn btn-outline-warning w-100 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                <i class="fas fa-envelope-open fa-2x mb-2"></i>
                                <span>Check Inbox</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="col-lg-4 mb-4">
            <div class="card-custom h-100">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">System Status</h5>
                </div>
                <div class="card-body-custom">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            PHP Version
                            <span class="badge bg-secondary rounded-pill"><?= phpversion() ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            CodeIgniter Version
                            <span class="badge bg-secondary rounded-pill"><?= \CodeIgniter\CodeIgniter::CI_VERSION ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Database
                            <span class="badge bg-success rounded-pill">Connected</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Environment
                            <span class="badge bg-warning rounded-pill"><?= ENVIRONMENT ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
