<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= isset($seo['site_title']) && $seo['site_title'] ? esc($seo['site_title']) : 'My Portfolio' ?></title>
    <meta name="description" content="<?= isset($seo['site_description']) ? esc($seo['site_description']) : '' ?>">
    <meta name="keywords" content="<?= isset($seo['site_keywords']) ? esc($seo['site_keywords']) : '' ?>">
    <meta name="author" content="<?= isset($seo['site_author']) ? esc($seo['site_author']) : '' ?>">
    
    <!-- Open Graph (Social Share) -->
    <meta property="og:title" content="<?= isset($seo['site_title']) ? esc($seo['site_title']) : 'My Portfolio' ?>">
    <meta property="og:description" content="<?= isset($seo['site_description']) ? esc($seo['site_description']) : '' ?>">
    <?php if(isset($seo['og_image']) && $seo['og_image']): ?>
        <meta property="og:image" content="<?= base_url('uploads/seo/' . $seo['og_image']) ?>">
    <?php endif; ?>
    
    <!-- DNS Prefetch + Preconnect for all CDN domains (speeds up TCP handshake) -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <!-- Bootstrap 5 — render-critical, load normally -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts — async load with font-display:swap to eliminate FOIT/CLS -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=<?= str_replace(' ', '+', $theme['font_family'] ?? 'Outfit') ?>:wght@300;400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=<?= str_replace(' ', '+', $theme['font_family'] ?? 'Outfit') ?>:wght@300;400;500;600;700&display=swap"></noscript>

    <!-- FontAwesome — async (not needed for LCP element) -->
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <!-- AOS — async (animation, not needed for initial paint) -->
    <link rel="preload" as="style" href="https://unpkg.com/aos@2.3.1/dist/aos.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css"></noscript>

    <!-- Toastr CSS — async, only needed post-interaction -->
    <link rel="preload" as="style" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"></noscript>
    
    <style>
        :root {
            /* Dynamic Theme Variables */
            --primary: <?= $theme['primary_color'] ?? '#6c5ce7' ?>;
            --secondary: <?= $theme['secondary_color'] ?? '#a29bfe' ?>;
            --dark-bg: <?= $theme['bg_color'] ?? '#090e17' ?>;
            --text-main: <?= $theme['text_color'] ?? '#ffffff' ?>;
            --text-muted: #b2bec3;
            --card-glass: rgba(255, 255, 255, <?= $theme['glass_opacity'] ?? 0.05 ?>);
            --card-border: rgba(255, 255, 255, 0.08);
            --card-blur: <?= $theme['card_blur'] ?? 10 ?>px;
            --radius: <?= $theme['border_radius'] ?? 20 ?>px;
            
            --accent: #fd79a8;
            --nav-glass: <?= $theme['bg_color'] ?>D9; /* Using Hex with 85% alpha */
            --nav-text: <?= $theme['text_color'] ?>;
            --footer-bg: <?= $theme['bg_color'] ?>EE;
            --bg-subtle: <?= $theme['text_color'] ?>08; /* Very low alpha of text color for contrast sections */
            --gradient-1: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            --glow-color: <?= $theme['primary_color'] ?>66;
            --gradient-text: linear-gradient(to right, var(--secondary), var(--primary));
        }
        
        /* Prevent FOIT — use system font stack until webfont loads */
        body { font-family: system-ui, -apple-system, sans-serif; }

        /* Smooth Scrolling & Scrollbar */
        html { scroll-behavior: smooth; }
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--dark-bg); }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        body {
            font-family: '<?= $theme['font_family'] ?? 'Outfit' ?>', system-ui, -apple-system, sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.8;
            background-image: 
                radial-gradient(circle at 15% 50%, <?= $theme['primary_color'] ?>15 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, <?= $theme['secondary_color'] ?>15 0%, transparent 25%);
            background-attachment: fixed;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-main);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .text-primary { color: var(--primary) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .bg-light { background-color: var(--bg-subtle) !important; }
        
        /* Apply Radius everywhere */
        .card-hover, .project-card, .btn-gradient, .btn-outline-light, .skill-card, .contact-card {
            border-radius: var(--radius) !important;
        }
        
        .section-padding { padding: 100px 0; position: relative; }

        /* Floating Navbar */
        .navbar {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: var(--nav-glass);
            border-bottom: 1px solid var(--card-border);
            padding: 15px 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .navbar.scrolled {
            padding: 10px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            margin-left: 1.5rem;
            position: relative;
            transition: 0.3s;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0; left: 50%; width: 0; height: 2px;
            background: var(--accent);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover, .nav-link.active { color: var(--nav-text) !important; }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }
        
        /* Hero Section */
        .hero-section {
            padding-top: 150px;
            padding-bottom: 100px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-title {
            font-size: 4.5rem;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: -webkit-linear-gradient(0deg, #ffffff, #a29bfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 300;
            margin-bottom: 2.5rem;
            max-width: 600px;
            animation: fadeInUp 1s ease-out 0.2s backwards;
        }
        
        .img-glow {
            border-radius: 50%;
            position: relative;
            z-index: 1;
            box-shadow: 0 0 60px var(--glow-color);
            animation: float 6s ease-in-out infinite;
            border: 5px solid var(--card-border);
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        
        /* Buttons */
        .btn-gradient {
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 14px 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.4);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .btn-gradient:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(108, 92, 231, 1); color: #fff; }

        .scroll-down {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
            color: var(--text-muted);
            cursor: pointer;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateX(-50%) translateY(0);}
            40% {transform: translateX(-50%) translateY(-10px);}
            60% {transform: translateX(-50%) translateY(-5px);}
        }

        /* Cards */
        .card-hover {
            background: var(--card-glass);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
        }
        
        .card-hover:hover {
            transform: translateY(-10px);
            border-color: rgba(255,255,255,0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            background: rgba(255,255,255,0.05);
        }

        /* Skills Styling */
        .skill-card {
            background: var(--card-glass);
            border: 1px solid var(--card-border);
            padding: 30px;
            border-radius: 20px;
            transition: 0.3s;
        }
        
        .skill-card:hover { border-color: var(--secondary); box-shadow: 0 0 30px rgba(108, 92, 231, 0.2); }
        
        .progress { background: rgba(255,255,255,0.05); height: 8px; border-radius: 10px; margin-top: 15px; }
        .progress-bar { background: var(--gradient-1); border-radius: 10px; }

        /* Project Cards */
        .project-card {
            background: var(--card-glass);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--card-border);
            transition: 0.4s;
            height: 100%;
        }
        
        .project-card:hover { border-color: var(--accent); transform: translateY(-10px); }
        
        .project-img-wrapper { overflow: hidden; position: relative; }
        /* Reserve space before image loads — prevents CLS */
        .project-img { transition: 0.5s; width: 100%; height: 260px; object-fit: cover; aspect-ratio: 16/9; }
        .project-card:hover .project-img { transform: scale(1.1); filter: brightness(0.6); }
        
        .project-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(108, 92, 231, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.3s;
        }
        
        .project-card:hover .project-overlay { opacity: 1; }
        
        .project-btn {
            background: #fff;
            color: var(--primary);
            padding: 10px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transform: translateY(20px);
            transition: 0.3s;
        }
        
        .project-card:hover .project-btn { transform: translateY(0); }

        .badge-tech {
            background: rgba(108, 92, 231, 0.1);
            color: #a29bfe;
            border: 1px solid rgba(108, 92, 231, 0.2);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            margin-right: 5px;
            margin-bottom: 8px;
            display: inline-block;
        }

        /* Contact Form */
        .contact-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(20px);
            border-radius: 24px;
        }
        
        .form-control {
            background: rgba(0,0,0,0.2);
            border: 1px solid var(--card-border);
            color: #fff;
            padding: 14px;
            border-radius: 12px;
        }
        
        .form-control:focus {
            background: rgba(0,0,0,0.3);
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1);
            color: #fff;
        }
        
        .contact-info-icon {
            width: 45px; height: 45px;
            background: rgba(108, 92, 231, 0.1);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            transition: 0.3s;
        }
        
        .contact-item:hover .contact-info-icon { background: var(--primary); color: #fff; }

        /* Section Titles */
        .section-title {
            margin-bottom: 4rem;
            font-size: 2.8rem;
            background: linear-gradient(to right, #fff, #b2bec3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Footer */
        footer {
            background: #05080f;
            border-top: 1px solid var(--card-border);
            padding: 60px 0;
        }
        
        @media (max-width: 991px) {
            .hero-title { font-size: 3rem; }
            .section-padding { padding: 60px 0; }
        }

        /* CLS fix: disable AOS shift animations for users who prefer reduced motion */
        @media (prefers-reduced-motion: reduce) {
            [data-aos] { opacity: 1 !important; transform: none !important; transition: none !important; }
        }

        /* Skeleton fallback font while custom font loads */
        @font-face {
            font-family: '<?= $theme['font_family'] ?? 'Outfit' ?>';
            font-display: swap;
        }
        
        <?php if(isset($theme['custom_css']) && $theme['custom_css']): ?>
        /* Custom User CSS */
        <?= $theme['custom_css'] ?>
        <?php endif; ?>
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#mainNav" data-bs-offset="100">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#">Portfolio<span>.</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($features['hero']) && $features['hero'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (isset($features['skills']) && $features['skills'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#skills">Skills</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (isset($features['projects']) && $features['projects'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#projects">Projects</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if ((isset($features['experience']) && $features['experience'] == 1) || (isset($features['education']) && $features['education'] == 1)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#resume">Resume</a>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($features['services']) && $features['services'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (isset($features['contact']) && $features['contact'] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <a href="<?= base_url('admin/login') ?>" class="btn btn-sm btn-outline-light ms-lg-3 mt-3 mt-lg-0 rounded-pill px-3">Admin</a>
            </div>
        </div>
    </nav>

    <?php if (isset($features['hero']) && $features['hero'] == 1): ?>
    <!-- Hero Section -->
    <section id="home" class="hero-section d-flex align-items-center min-vh-80">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <?php if(isset($profile['bio']) && $profile['bio']): ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill border border-primary border-opacity-25"><?= esc(substr($profile['bio'], 0, 100)) ?></span>
                    <?php else: ?>
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill border border-primary border-opacity-25">Full Stack Developer</span>
                    <?php endif; ?>
                    <h1 class="hero-title"><?= isset($profile['full_name']) && $profile['full_name'] ? 'Hi, I\'m ' . esc($profile['full_name']) : 'Crafting Digital <br> Experiences With Code' ?></h1>
                    <p class="hero-subtitle text-muted"><?= isset($profile['bio']) && $profile['bio'] ? esc($profile['bio']) : 'I build exceptional, responsive, and user-friendly web applications using modern technologies.' ?></p>
                    <div class="d-flex gap-3">
                        <a href="#projects" class="btn btn-gradient">View My Work</a>
                        <a href="#contact" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">Contact Me</a>
                        <?php if(isset($profile['resume']) && $profile['resume']): ?>
                            <a href="<?= base_url('uploads/resume/' . $profile['resume']) ?>" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold"><i class="fas fa-download me-2"></i>CV</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="hero-img-blob d-inline-block p-4">
                        <?php if(isset($profile['profile_image']) && $profile['profile_image']): ?>
                            <!-- fetchpriority=high + explicit dimensions = faster LCP + zero CLS -->
                            <img src="<?= base_url('uploads/profile/' . $profile['profile_image']) ?>"
                                 alt="<?= esc($profile['full_name'] ?? 'Developer') ?>"
                                 class="img-fluid rounded-circle img-glow"
                                 width="320" height="320"
                                 fetchpriority="high"
                                 loading="eager"
                                 style="max-width:320px;width:320px;height:320px;object-fit:cover;">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($profile['full_name'] ?? 'Developer') ?>&size=320&background=6c5ce7&color=fff"
                                 alt="<?= esc($profile['full_name'] ?? 'Developer') ?>"
                                 class="img-fluid rounded-circle img-glow"
                                 width="320" height="320"
                                 fetchpriority="high"
                                 loading="eager"
                                 style="max-width:320px;width:320px;height:320px;object-fit:cover;">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-down">
            <i class="fas fa-chevron-down fa-2x"></i>
        </div>
    </section>
    <?php endif; ?>

    <?php if (isset($features['skills']) && $features['skills'] == 1): ?>
    <!-- Skills Section -->
    <section id="skills" class="section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">My Skills</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <?php if(!empty($skills)): ?>
                            <?php foreach($skills as $skill): ?>
                                <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-delay="<?= $skill['display_order'] * 100 ?>">
                                    <div class="skill-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 text-primary">
                                                    <i class="fas fa-code fa-lg"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold text-white"><?= esc($skill['skill_name']) ?></h5>
                                            </div>
                                            <span class="badge bg-dark border border-secondary text-secondary"><?= esc($skill['category']) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-end mt-3">
                                            <span class="text-muted small">Proficiency</span>
                                            <span class="fw-bold text-primary"><?= $skill['skill_level'] ?>%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?= $skill['skill_level'] ?>%" aria-valuenow="<?= $skill['skill_level'] ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p class="text-muted">No skills added yet. Add them from the Admin Panel.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ((isset($features['experience']) && $features['experience'] == 1) || (isset($features['education']) && $features['education'] == 1)): ?>
    <!-- Experience & Education Section -->
    <section id="resume" class="section-padding" style="background: var(--bg-subtle);">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">My Journey</h2>
            
            <div class="row">
                <!-- Experience -->
                <?php if (isset($features['experience']) && $features['experience'] == 1): ?>
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right">
                    <h3 class="mb-4 text-white ps-2 border-start border-4 border-primary">Experience</h3>
                    <?php if(!empty($experience)): ?>
                        <?php foreach($experience as $exp): ?>
                            <div class="card-hover mb-4 p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-white mb-0"><?= esc($exp['job_title']) ?></h5>
                                    <?php if($exp['is_current']): ?>
                                        <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">Current</span>
                                    <?php endif; ?>
                                </div>
                                <h6 class="text-primary mb-3"><?= esc($exp['company']) ?></h6>
                                <p class="text-muted small mb-3">
                                    <i class="far fa-calendar-alt me-2"></i><?= esc($exp['start_date']) ?> - <?= esc($exp['end_date']) ?>
                                </p>
                                <p class="text-muted mb-0 small"><?= nl2br(esc($exp['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No experience entries found.</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Education -->
                <?php if (isset($features['education']) && $features['education'] == 1): ?>
                <div class="<?= (isset($features['experience']) && $features['experience'] == 1) ? 'col-lg-6' : 'col-lg-8 mx-auto' ?>" data-aos="fade-left">
                    <h3 class="mb-4 text-white ps-2 border-start border-4 border-secondary">Education</h3>
                    <?php if(!empty($education)): ?>
                        <?php foreach($education as $edu): ?>
                            <div class="card-hover mb-4 p-4">
                                <h5 class="fw-bold text-white mb-1"><?= esc($edu['degree']) ?></h5>
                                <h6 class="text-secondary mb-3"><?= esc($edu['institution']) ?></h6>
                                <p class="text-muted small mb-3">
                                    <i class="far fa-calendar-alt me-2"></i><?= esc($edu['year_start']) ?> - <?= esc($edu['year_end']) ?>
                                </p>
                                <?php if($edu['gpa']): ?>
                                    <p class="text-light small mb-2"><strong class="text-muted">GPA:</strong> <?= esc($edu['gpa']) ?></p>
                                <?php endif; ?>
                                <p class="text-muted mb-0 small"><?= nl2br(esc($edu['description'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No education entries found.</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>



    <?php if (isset($features['services']) && $features['services'] == 1): ?>
    <!-- Services Section -->
    <section id="services" class="section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">What I Offer</h2>
            
            <div class="row">
                <?php if(!empty($services)): ?>
                    <?php foreach($services as $index => $srv): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                            <div class="card-hover h-100 p-4 text-center">
                                <div class="mb-4 d-inline-block p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class="<?= esc($srv['icon']) ?> fa-2x"></i>
                                </div>
                                <h4 class="text-white mb-3 fw-bold"><?= esc($srv['title']) ?></h4>
                                <p class="text-muted mb-4"><?= nl2br(esc($srv['description'])) ?></p>
                                <?php if($srv['price']): ?>
                                    <div class="mt-auto pt-3 border-top border-light border-opacity-10">
                                        <h5 class="text-primary fw-bold mb-0"><?= esc($srv['price']) ?></h5>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                     <!-- Default Services Placeholders if DB is empty -->
                    <div class="col-md-4 mb-4" data-aos="fade-up">
                        <div class="card-hover h-100 p-4 text-center">
                             <div class="mb-4 d-inline-block p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-code fa-2x"></i>
                            </div>
                            <h4 class="text-white mb-3 fw-bold">Web Development</h4>
                            <p class="text-muted mb-4">Building fast, responsive, and secure websites tailored to your needs using modern technologies.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (isset($features['projects']) && $features['projects'] == 1): ?>
    <!-- Projects Section -->
    <section id="projects" class="section-padding">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Featured Projects</h2>
            
            <div class="row">
                <?php if(!empty($projects)): ?>
                    <?php foreach($projects as $index => $project): ?>
                        <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                            <div class="project-card h-100 d-flex flex-column">
                                <div class="project-img-wrapper">
                                    <?php if($project['image']): ?>
                                        <img src="<?= base_url('uploads/projects/' . $project['image']) ?>" class="project-img" alt="<?= esc($project['project_name']) ?>">
                                    <?php else: ?>
                                        <div class="project-img d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); height: 260px;">
                                            <i class="fas fa-laptop-code fa-4x" style="color: rgba(255,255,255,0.5);"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="project-overlay">
                                        <a href="#" class="project-btn">View Details <i class="fas fa-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                                <div class="project-body d-flex flex-column flex-grow-1 p-4">
                                    <h5 class="fw-bold mb-2 text-white"><?= esc($project['project_name']) ?></h5>
                                    <p class="text-muted small mb-3 flex-grow-1"><?= esc(strip_tags($project['description'])) ?></p>
                                    
                                    <div class="project-tech mt-auto">
                                        <?php 
                                            $techs = explode(',', $project['technologies']);
                                            foreach($techs as $tech): 
                                        ?>
                                            <span class="badge-tech"><?= trim(esc($tech)) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-laptop-code fa-3x text-muted mb-3"></i>
                        <h5>No projects showcased yet</h5>
                        <p class="text-muted">Stay tuned for amazing work!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (isset($features['testimonials']) && $features['testimonials'] == 1): ?>
    <!-- Testimonials Section -->
    <section id="testimonials" class="section-padding" style="background: var(--bg-subtle);">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Client Stories</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php if(!empty($testimonials)): ?>
                                <?php foreach($testimonials as $index => $t): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <div class="card-hover text-center p-5 mx-auto" style="max-width: 800px;">
                                            <div class="mb-4">
                                                <?php if($t['image']): ?>
                                                    <img src="<?= base_url('uploads/testimonials/' . $t['image']) ?>" class="rounded-circle shadow-lg border border-3 border-light border-opacity-25" width="100" height="100" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-primary fs-2 shadow-lg border border-3 border-light border-opacity-25" style="width: 100px; height: 100px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <figure>
                                                <blockquote class="blockquote">
                                                    <p class="fs-4 text-light fst-italic mb-4">"<?= esc($t['quote']) ?>"</p>
                                                </blockquote>
                                                <figcaption class="blockquote-footer text-primary fs-5 mt-2">
                                                    <?= esc($t['name']) ?> <cite title="Source Title" class="text-white-50 ms-2 text-decoration-none small d-block d-md-inline mt-1 mt-md-0">- <?= esc($t['role']) ?><?= $t['company'] ? ', ' . esc($t['company']) : '' ?></cite>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active">
                                    <div class="card-hover text-center p-5 mx-auto" style="max-width: 800px;">
                                        <div class="mb-4">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-primary fs-2 shadow-lg border border-3 border-light border-opacity-25" style="width: 100px; height: 100px;">
                                                <i class="fas fa-quote-left"></i>
                                            </div>
                                        </div>
                                        <p class="fs-4 text-light fst-italic mb-4">"Working with [Your Name] was an absolute pleasure. They delivered beyond our expectations and on time."</p>
                                        <figcaption class="blockquote-footer text-primary fs-5 mt-2">
                                            Happy Client <cite title="Source Title" class="text-white-50 ms-2 text-decoration-none small">- CEO, Tech Startups</cite>
                                        </figcaption>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (isset($features['contact']) && $features['contact'] == 1): ?>
    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="container position-relative z-1">
            <h2 class="section-title text-white" data-aos="fade-up">Get In Touch</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="contact-card p-4 p-md-5" data-aos="zoom-in">
                        <div class="row">
                            <div class="col-md-6 mb-5 mb-md-0 border-end border-light border-opacity-10 pe-md-5">
                                <h4 class="mb-4 text-white fw-bold">Let's Talk</h4>
                                <p class="text-muted mb-5 lead-sm">Have a project in mind or just want to say hi? Feel free to reach out!</p>
                                
                                <div class="contact-item d-flex align-items-center mb-4">
                                    <div class="contact-info-icon me-3">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small text-uppercase fw-bold tracking-wider">Email Me</p>
                                        <h6 class="mb-0 text-white"><?= isset($contact['email']) && $contact['email'] ? esc($contact['email']) : (isset($profile['email']) && $profile['email'] ? esc($profile['email']) : 'hello@example.com') ?></h6>
                                    </div>
                                </div>
                                
                                <?php if((isset($contact['phone']) && $contact['phone']) || (isset($profile['phone']) && $profile['phone'])): ?>
                                <div class="contact-item d-flex align-items-center mb-4">
                                    <div class="contact-info-icon me-3">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small text-uppercase fw-bold tracking-wider">Phone</p>
                                        <h6 class="mb-0 text-white"><?= isset($contact['phone']) ? esc($contact['phone']) : esc($profile['phone']) ?></h6>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="contact-item d-flex align-items-center">
                                    <div class="contact-info-icon me-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted small text-uppercase fw-bold tracking-wider">Location</p>
                                        <h6 class="mb-0 text-white"><?= isset($contact['address']) && $contact['address'] ? esc($contact['address']) : (isset($profile['address']) && $profile['address'] ? esc($profile['address']) : 'Your City, Country') ?></h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 ps-md-4">
                                <form id="contactForm">
                                    <?= csrf_field() ?>
                                    <!-- Honeypot field — hidden from real users, bots will fill it -->
                                    <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                                        <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Your Name</label>
                                        <input type="text" id="contactName" name="name" class="form-control" placeholder="John Doe" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Your Email</label>
                                        <input type="email" id="contactEmail" name="email" class="form-control" placeholder="john@example.com" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-muted small">Message</label>
                                        <textarea id="contactMessage" name="message" class="form-control" rows="4" placeholder="How can I help you?" required></textarea>
                                    </div>
                                    <button type="submit" id="contactSubmitBtn" class="btn btn-gradient w-100">Send Message</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer style="background: var(--footer-bg);">
        <div class="container text-center">
            <h3 class="fw-bold mb-4">Portfolio<span class="text-primary">.</span></h3>
            
            <div class="mb-4">
                <?php if(isset($profile['linkedin']) && $profile['linkedin']): ?>
                    <a href="<?= esc($profile['linkedin']) ?>" target="_blank" class="mx-2 text-white text-decoration-none"><i class="fab fa-linkedin fa-lg"></i></a>
                <?php endif; ?>
                <?php if(isset($profile['github']) && $profile['github']): ?>
                    <a href="<?= esc($profile['github']) ?>" target="_blank" class="mx-2 text-white text-decoration-none"><i class="fab fa-github fa-lg"></i></a>
                <?php endif; ?>
                <?php if(isset($profile['twitter']) && $profile['twitter']): ?>
                    <a href="<?= esc($profile['twitter']) ?>" target="_blank" class="mx-2 text-white text-decoration-none"><i class="fab fa-twitter fa-lg"></i></a>
                <?php endif; ?>
            </div>
            
            <p class="text-white-50 small mb-0">&copy; <?= date('Y') ?> <?= esc($profile['full_name'] ?? 'Portfolio') ?>. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Scripts: Load in order — inline script below depends on jQuery, AOS, Toastr -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });

        // Smooth Scroll for local links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        $(document).ready(function() {
            // Toastr options
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
            };

            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                var btn = $(this).find('button[type="submit"]');
                var originalText = btn.html();
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
                
                $.ajax({
                    url: '<?= base_url('contact/submit') ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataType: 'json',
                    success: function(response) {
                        if(response.status === 'success') {
                            toastr.success(response.message);
                            $('#contactForm')[0].reset();
                        } else {
                            if(response.errors) {
                                $.each(response.errors, function(key, value) {
                                    toastr.error(value);
                                });
                            } else {
                                toastr.error(response.message);
                            }
                        }
                        btn.prop('disabled', false).html(originalText);
                    },
                    error: function() {
                        toastr.error('An error occurred. Please try again later.');
                        btn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
</body>
</html>
