<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Portfolio</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            /* Modern Vibrant Theme (Same as Home) */
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --accent: #fd79a8;
            --dark-bg: #090e17;
            --card-glass: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #ffffff;
            --text-muted: #b2bec3;
            --gradient-1: linear-gradient(135deg, #6c5ce7 0%, #fd79a8 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(108, 92, 231, 0.15) 0%, transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(253, 121, 168, 0.15) 0%, transparent 25%);
        }
        
        /* Animated Background Particles */
        .bg-animation {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        
        .bg-animation span {
            position: absolute;
            display: block;
            width: 20px;
            height: 20px;
            background: rgba(108, 92, 231, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(108, 92, 231, 0.2);
        }
        
        .bg-animation span:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bg-animation span:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .bg-animation span:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bg-animation span:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .bg-animation span:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .bg-animation span:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .bg-animation span:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .bg-animation span:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .bg-animation span:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .bg-animation span:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }
        
        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }
        
        /* Login Container */
        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .login-card {
            background: var(--card-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.8s ease;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-logo {
            width: 70px;
            height: 70px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.4);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .login-logo i {
            color: #fff;
            font-size: 1.8rem;
        }
        
        .login-header h2 {
            color: var(--text-main);
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.5rem;
        }
        
        .login-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-control {
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--card-border);
            border-radius: 12px;
            padding: 14px 20px 14px 45px;
            font-size: 15px;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(0,0,0,0.5);
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.15);
            color: #fff;
        }
        
        .form-control::placeholder { color: rgba(255,255,255,0.3); }
        
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.4);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus ~ .input-icon {
            color: var(--secondary);
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            background: var(--gradient-1);
            border: none;
            color: #fff;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.3);
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(108, 92, 231, 0.5);
        }
        
        .alert-custom {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.2);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            backdrop-filter: blur(5px);
        }
        
        .form-check-label {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        .form-check-input {
            background-color: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.2);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }
        
        .forgot-password a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: var(--secondary);
        }
        
        /* Loading Spinner */
        .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid #ffffff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    
    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>Admin Login</h2>
                <p>Enter your credentials to continue</p>
            </div>
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="<?= site_url('auth/do_login') ?>">
                <?= csrf_field() ?>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <i class="fas fa-user input-icon"></i>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye position-absolute end-0 top-50 translate-middle-y me-3" 
                       id="togglePassword" 
                       style="cursor: pointer; color: #9ca3af;"></i>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>
                </div>
                
                <button type="submit" class="btn btn-login">
                    <span class="btn-text">Sign In</span>
                    <div class="spinner d-inline-block ms-2"></div>
                </button>
            </form>
            
            <div class="forgot-password">
                <a href="#"><i class="fas fa-key me-1"></i> Forgot Password?</a>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Toggle Password Visibility
        $('#togglePassword').click(function() {
            const passwordField = $('#password');
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
        });
        
        // Form Submit Animation
        $('#loginForm').submit(function() {
            var btn = $('.btn-login');
            
            // If already submitting, prevent double submit
            if(btn.hasClass('submitting')) return false;

            $('.btn-text').text('Signing In...');
            $('.spinner').show();
            
            btn.addClass('submitting');
            btn.css('opacity', '0.7');
            btn.css('pointer-events', 'none'); // Prevent clicks but don't disable form submit
            
            return true;
        });
        
        // Initialize AOS
        AOS.init();
    </script>
</body>
</html>
