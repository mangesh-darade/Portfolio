<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Portfolio Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        /* Animated Background */
        .bg-animation { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
        .bg-animation span {
            position: absolute; display: block; width: 20px; height: 20px;
            background: rgba(108, 92, 231, 0.1); animation: animate 25s linear infinite;
            bottom: -150px; border-radius: 50%; box-shadow: 0 0 20px rgba(108, 92, 231, 0.2);
        }
        .bg-animation span:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bg-animation span:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .bg-animation span:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bg-animation span:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .bg-animation span:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }

        .login-container {
            position: relative; z-index: 10; width: 100%; max-width: 440px; padding: 20px;
        }

        .login-card {
            background: var(--card-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header { text-align: center; margin-bottom: 30px; }

        .login-logo {
            width: 70px; height: 70px;
            background: var(--gradient-1);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.4);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .login-logo i { color: #fff; font-size: 1.8rem; }

        .login-header h2 { color: var(--text-main); font-weight: 700; margin-bottom: 5px; font-size: 1.5rem; }
        .login-header p { color: var(--text-muted); font-size: 0.9rem; }

        .form-group { margin-bottom: 20px; position: relative; }

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
            position: absolute; left: 18px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.4);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus ~ .input-icon { color: var(--secondary); }

        .btn-submit {
            width: 100%; padding: 14px;
            border-radius: 50px; font-size: 16px; font-weight: 600;
            background: var(--gradient-1);
            border: none; color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(108, 92, 231, 0.3);
            margin-top: 10px;
        }

        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(108, 92, 231, 0.5); }

        .alert-custom {
            border-radius: 12px; padding: 12px 15px;
            margin-bottom: 20px; font-size: 0.9rem;
            backdrop-filter: blur(5px);
        }

        .alert-danger-custom { background: rgba(220, 53, 69, 0.12); color: #ff6b6b; border: 1px solid rgba(220, 53, 69, 0.2); }
        .alert-success-custom { background: rgba(0, 184, 148, 0.12); color: #00b894; border: 1px solid rgba(0, 184, 148, 0.2); }
        .alert-info-custom { background: rgba(108, 92, 231, 0.12); color: var(--secondary); border: 1px solid rgba(108, 92, 231, 0.2); }

        .back-link { text-align: center; margin-top: 20px; }
        .back-link a {
            color: var(--text-muted); text-decoration: none;
            font-size: 0.9rem; transition: all 0.3s ease;
        }
        .back-link a:hover { color: var(--secondary); }

        .info-box {
            background: rgba(108, 92, 231, 0.08);
            border: 1px solid rgba(108, 92, 231, 0.15);
            border-radius: 12px; padding: 14px;
            margin-bottom: 22px; font-size: 0.88rem;
            color: var(--text-muted); line-height: 1.6;
        }
        .info-box i { color: var(--secondary); margin-right: 6px; }

        /* Email hint chip below username */
        .email-hint-box {
            margin-top: 8px;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 0.84rem;
            line-height: 1.5;
            background: rgba(0, 184, 148, 0.10);
            border: 1px solid rgba(0, 184, 148, 0.20);
            color: #00b894;
        }
        .email-hint-box strong { letter-spacing: 0.03em; }
        .email-hint-box.email-hint-error {
            background: rgba(220, 53, 69, 0.10);
            border-color: rgba(220, 53, 69, 0.20);
            color: #ff6b6b;
        }

        .spinner {
            display: none; width: 18px; height: 18px;
            border: 2px solid #ffffff; border-top-color: transparent;
            border-radius: 50%; animation: spin 0.8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="bg-animation">
        <span></span><span></span><span></span><span></span><span></span>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-key"></i>
                </div>
                <h2>Forgot Password</h2>
                <p>Reset your admin account password</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-custom alert-danger-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-custom alert-success-custom">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                Enter your <strong>username</strong> and the <strong>email address</strong> associated with your account. A password reset link will be sent to that email.
            </div>

            <form id="forgotForm" method="POST" action="<?= site_url('auth/forgot_password') ?>">
                <?= csrf_field() ?>

                <div class="form-group">
                    <input type="text" name="username" id="username"
                           class="form-control"
                           placeholder="Admin Username"
                           value="<?= old('username') ?>"
                           required autocomplete="username">
                    <i class="fas fa-user input-icon"></i>

                    <!-- Email hint shown after username lookup -->
                    <div id="emailHint" class="email-hint-box" style="display:none;">
                        <i class="fas fa-envelope-circle-check me-1"></i>
                        Reset will be sent to: <strong id="hintEmail"></strong>
                    </div>
                    <div id="emailNotFound" class="email-hint-box email-hint-error" style="display:none;">
                        <i class="fas fa-circle-xmark me-1"></i>
                        No account found for this username.
                    </div>
                </div>

                <div class="form-group">
                    <input type="email" name="email" id="email"
                           class="form-control"
                           placeholder="Your Email Address"
                           value="<?= old('email') ?>"
                           required autocomplete="email">
                    <i class="fas fa-envelope input-icon"></i>
                </div>

                <button type="submit" class="btn btn-submit" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-paper-plane me-2"></i>Send Reset Link
                    </span>
                    <div class="spinner d-inline-block ms-2"></div>
                </button>
            </form>

            <div class="back-link">
                <a href="<?= site_url('admin/login') ?>">
                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                </a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var hintTimer = null;
        var lastUsername = '';

        $('#username').on('input', function() {
            var uname = $(this).val().trim();

            // Clear previous hints
            clearHints();

            if (uname.length < 3) return;
            if (uname === lastUsername) return;

            clearTimeout(hintTimer);
            hintTimer = setTimeout(function() {
                fetchEmailHint(uname);
            }, 500); // debounce 500ms
        });

        function clearHints() {
            $('#emailHint').fadeOut(150);
            $('#emailNotFound').fadeOut(150);
        }

        function fetchEmailHint(username) {
            $.ajax({
                url: '<?= site_url('auth/email-hint') ?>',
                type: 'GET',
                data: { username: username },
                dataType: 'json',
                success: function(res) {
                    lastUsername = username;
                    if (res.found) {
                        $('#hintEmail').text(res.hint);
                        $('#emailHint').fadeIn(300);
                        $('#emailNotFound').hide();
                    } else {
                        $('#emailNotFound').fadeIn(300);
                        $('#emailHint').hide();
                    }
                },
                error: function() {
                    // silently ignore on network error
                }
            });
        }

        // Form submit animation
        $('#forgotForm').submit(function() {
            var btn = $('#submitBtn');
            if (btn.hasClass('submitting')) return false;
            btn.addClass('submitting');
            $('.btn-text').html('<i class="fas fa-spinner fa-spin me-2"></i>Sending...');
            $('.spinner').hide();
            btn.css('opacity', '0.7').css('pointer-events', 'none');
            return true;
        });
    </script>
</body>
</html>
