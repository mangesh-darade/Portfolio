<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Portfolio Admin</title>

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

        .bg-animation { position: absolute; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
        .bg-animation span {
            position: absolute; display: block; width: 20px; height: 20px;
            background: rgba(108, 92, 231, 0.1); animation: animate 25s linear infinite;
            bottom: -150px; border-radius: 50%;
        }
        .bg-animation span:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .bg-animation span:nth-child(2) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .bg-animation span:nth-child(3) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }

        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 0.5; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
        }

        .login-container { position: relative; z-index: 10; width: 100%; max-width: 440px; padding: 20px; }

        .login-card {
            background: var(--card-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header { text-align: center; margin-bottom: 28px; }

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
            padding: 14px 45px 14px 45px;
            font-size: 15px; color: #fff;
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
            color: rgba(255,255,255,0.4); font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus ~ .input-icon { color: var(--secondary); }

        .toggle-eye {
            position: absolute; right: 15px; top: 50%;
            transform: translateY(-50%);
            cursor: pointer; color: #9ca3af;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .toggle-eye:hover { color: var(--secondary); }

        /* Password strength bar */
        .strength-bar-wrap {
            height: 4px; border-radius: 10px;
            background: rgba(255,255,255,0.1);
            margin-top: 8px; overflow: hidden;
        }

        .strength-bar {
            height: 100%; border-radius: 10px;
            width: 0; transition: all 0.4s ease;
        }

        .strength-text { font-size: 0.78rem; margin-top: 5px; color: var(--text-muted); }

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

        .alert-custom { border-radius: 12px; padding: 12px 15px; margin-bottom: 20px; font-size: 0.9rem; }
        .alert-danger-custom { background: rgba(220, 53, 69, 0.12); color: #ff6b6b; border: 1px solid rgba(220, 53, 69, 0.2); }

        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: var(--text-muted); text-decoration: none; font-size: 0.9rem; transition: all 0.3s; }
        .back-link a:hover { color: var(--secondary); }
    </style>
</head>
<body>
    <div class="bg-animation">
        <span></span><span></span><span></span>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Reset Password</h2>
                <p>Create a strong new password for your account</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-custom alert-danger-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form id="resetForm" method="POST" action="<?= site_url('auth/reset_password') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="token" value="<?= esc($token) ?>">

                <div class="form-group">
                    <input type="password" name="new_password" id="new_password"
                           class="form-control"
                           placeholder="New Password (min 8 chars)"
                           required minlength="8">
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-eye" id="toggleNew"></i>

                    <div class="strength-bar-wrap mt-2">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                <div class="form-group">
                    <input type="password" name="confirm_password" id="confirm_password"
                           class="form-control"
                           placeholder="Confirm New Password"
                           required minlength="8">
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-eye" id="toggleConfirm"></i>
                </div>

                <button type="submit" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-check-circle me-2"></i>Reset Password
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
        // Toggle password visibility
        $('#toggleNew').click(function() {
            var f = $('#new_password');
            f.attr('type', f.attr('type') === 'password' ? 'text' : 'password');
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        $('#toggleConfirm').click(function() {
            var f = $('#confirm_password');
            f.attr('type', f.attr('type') === 'password' ? 'text' : 'password');
            $(this).toggleClass('fa-eye fa-eye-slash');
        });

        // Password strength meter
        $('#new_password').on('input', function() {
            var pass = $(this).val();
            var strength = 0;
            if (pass.length >= 8) strength++;
            if (/[A-Z]/.test(pass)) strength++;
            if (/[0-9]/.test(pass)) strength++;
            if (/[^A-Za-z0-9]/.test(pass)) strength++;

            var colors = ['#ff6b6b', '#ffa502', '#eccc68', '#2ed573'];
            var labels = ['Weak', 'Fair', 'Good', 'Strong'];
            var widths = ['25%', '50%', '75%', '100%'];

            if (pass.length === 0) {
                $('#strengthBar').css({width: '0', background: ''});
                $('#strengthText').text('');
            } else {
                var idx = Math.max(0, strength - 1);
                $('#strengthBar').css({width: widths[idx], background: colors[idx]});
                $('#strengthText').text('Strength: ' + labels[idx]).css('color', colors[idx]);
            }
        });

        // Validate passwords match before submit
        $('#resetForm').submit(function(e) {
            var p1 = $('#new_password').val();
            var p2 = $('#confirm_password').val();

            if (p1 !== p2) {
                e.preventDefault();
                alert('Passwords do not match! Please try again.');
                $('#confirm_password').focus();
                return false;
            }

            var btn = $('#submitBtn');
            btn.html('<i class="fas fa-spinner fa-spin me-2"></i>Resetting...').prop('disabled', true);
        });
    </script>
</body>
</html>
