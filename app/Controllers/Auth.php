<?php

namespace App\Controllers;

class Auth extends BaseController
{
    /** Max failed login attempts before lockout */
    private const MAX_LOGIN_ATTEMPTS = 5;
    /** Lockout duration in seconds (15 minutes) */
    private const LOCKOUT_DURATION = 900;

    public function index()
    {
        return redirect()->to('/admin/login');
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/login');
    }

    public function do_login()
    {
        // ─── CSRF is handled automatically by CI4 ────────────────────────────

        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please provide a valid username and password.');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // ─── Rate limiting via session ────────────────────────────────────────
        $attemptKey  = 'login_attempts_' . md5($username);
        $lockoutKey  = 'login_lockout_' . md5($username);
        $lockoutTime = session()->get($lockoutKey);

        if ($lockoutTime && time() < $lockoutTime) {
            $remaining = ceil(($lockoutTime - time()) / 60);
            return redirect()->back()->withInput()->with(
                'error',
                "Too many failed attempts. Please wait {$remaining} minute(s) before trying again."
            );
        }

        // ─── Lookup user ──────────────────────────────────────────────────────
        $db      = \Config\Database::connect();
        $builder = $db->table('admin_users');
        $user    = $builder->where('username', $username)->get()->getRow();

        if ($user && password_verify($password, $user->password)) {
            // Success — clear rate-limit counters
            session()->remove([$attemptKey, $lockoutKey]);

            // Update last_login timestamp
            $builder->where('id', $user->id)->update(['last_login' => date('Y-m-d H:i:s')]);

            // Regenerate session ID to prevent session fixation
            session()->regenerate(true);

            session()->set([
                'id'             => $user->id,
                'username'       => $user->username,
                'isLoggedIn'     => true,
                'logged_in_at'   => time(),
                'last_login'     => $user->last_login, // Store previous login time
            ]);

            return redirect()->to('/admin/dashboard');
        }

        // ─── Failed attempt ───────────────────────────────────────────────────
        $attempts = (int) session()->get($attemptKey) + 1;
        session()->set($attemptKey, $attempts);

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            session()->set($lockoutKey, time() + self::LOCKOUT_DURATION);
            session()->set($attemptKey, 0);
            log_message('warning', "Admin login lockout triggered for username: {$username}");
            return redirect()->back()->withInput()->with(
                'error',
                'Too many failed login attempts. Your account has been temporarily locked for 15 minutes.'
            );
        }

        $remaining = self::MAX_LOGIN_ATTEMPTS - $attempts;
        log_message('warning', "Failed admin login attempt for username: {$username}. Attempts: {$attempts}");

        return redirect()->back()->withInput()->with(
            'error',
            "Invalid username or password. {$remaining} attempt(s) remaining."
        );
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'You have been logged out successfully.');
    }

    // ─── Forgot Password ─────────────────────────────────────────────────────────

    /**
     * Show the forgot-password form.
     */
    public function forgot_password_form()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/admin/dashboard');
        }
        return view('admin/forgot_password');
    }

    /**
     * AJAX: return a masked email hint for the given username.
     * GET /auth/email-hint?username=admin
     */
    public function email_hint()
    {
        $username = trim($this->request->getGet('username') ?? '');

        if (strlen($username) < 3) {
            return $this->response->setJSON(['found' => false]);
        }

        $db   = \Config\Database::connect();
        $user = $db->table('admin_users')->where('username', $username)->get()->getRow();

        if (!$user) {
            return $this->response->setJSON(['found' => false]);
        }

        // Fetch linked email from profile table
        $profile = $db->table('profile')->select('email')->get()->getRow();

        if (!$profile || empty($profile->email)) {
            return $this->response->setJSON(['found' => false]);
        }

        $maskedEmail = $this->maskEmail($profile->email);

        return $this->response->setJSON([
            'found' => true,
            'hint'  => $maskedEmail,
        ]);
    }

    /**
     * Handle forgot-password submission: validate username + email, generate token, send email.
     */
    public function forgot_password()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please enter a valid username and email address.');
        }

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');

        $db   = \Config\Database::connect();
        $user = $db->table('admin_users')->where('username', $username)->get()->getRow();

        // Validate that the username AND email both match the profile record.
        // We look in the 'profile' table for the linked email.
        $profile = $db->table('profile')->where('email', $email)->get()->getRow();

        // Generic message for security — do not reveal whether username/email exist.
        if (!$user || !$profile) {
            return redirect()->back()->withInput()->with(
                'error',
                'No account found with that username and email combination.'
            );
        }

        // Generate a secure random token (64 hex chars = 32 bytes)
        $token     = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        // Store token in admin_users table (add columns if missing)
        $db->table('admin_users')->where('id', $user->id)->update([
            'reset_token'      => $token,
            'reset_expires_at' => $expiresAt,
        ]);

        // Build reset URL
        $resetUrl = site_url('auth/reset/' . $token);

        // Send email via configured email settings
        $emailSent = $this->sendResetEmail($email, $user->username, $resetUrl);

        if ($emailSent) {
            return redirect()->to('/admin/login')->with(
                'success',
                'A password reset link has been sent to your email. It will expire in 1 hour.'
            );
        } else {
            log_message('error', "Password reset email failed for user: {$username}, email: {$email}");
            return redirect()->back()->with(
                'error',
                'Could not send reset email. Please check your Email Settings or contact your server admin.'
            );
        }
    }

    /**
     * Show the reset-password form (requires valid token).
     */
    public function reset_password_form(string $token)
    {
        [$user, $error] = $this->validateResetToken($token);

        if ($error) {
            return redirect()->to('/admin/login')->with('error', $error);
        }

        return view('admin/reset_password', ['token' => $token]);
    }

    /**
     * Handle reset-password form submission.
     */
    public function reset_password()
    {
        $token = $this->request->getPost('token');

        $rules = [
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', \Config\Services::validation()->getErrors()));
        }

        [$user, $error] = $this->validateResetToken($token);

        if ($error) {
            return redirect()->to('/admin/login')->with('error', $error);
        }

        $db      = \Config\Database::connect();
        $newHash = password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT);

        $db->table('admin_users')->where('id', $user->id)->update([
            'password'         => $newHash,
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);

        log_message('info', 'Password reset successful for user ID: ' . $user->id);

        return redirect()->to('/admin/login')->with(
            'success',
            'Your password has been reset successfully! You can now login with your new password.'
        );
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────────

    /**
     * Validate a reset token: find user, check expiry.
     * Returns [user, null] on success or [null, errorMessage] on failure.
     */
    private function validateResetToken(string $token): array
    {
        if (empty($token) || strlen($token) !== 64) {
            return [null, 'Invalid or missing reset token.'];
        }

        $db   = \Config\Database::connect();
        $user = $db->table('admin_users')
                   ->where('reset_token', $token)
                   ->get()->getRow();

        if (!$user) {
            return [null, 'This password reset link is invalid.'];
        }

        if (empty($user->reset_expires_at) || strtotime($user->reset_expires_at) < time()) {
            return [null, 'This password reset link has expired. Please request a new one.'];
        }

        return [$user, null];
    }

    /**
     * Send reset-password email using the stored SMTP settings.
     */
    private function sendResetEmail(string $toEmail, string $username, string $resetUrl): bool
    {
        $db       = \Config\Database::connect();
        $settings = $db->table('email_settings')->get()->getRow();

        if (!$settings) {
            log_message('error', 'Forgot Password: No email settings configured.');
            return false;
        }

        $config = [
            'protocol'   => $settings->protocol ?? 'smtp',
            'SMTPHost'   => $settings->smtp_host,
            'SMTPUser'   => $settings->smtp_user,
            'SMTPPass'   => $settings->smtp_pass,
            'SMTPPort'   => (int)($settings->smtp_port ?? 587),
            'SMTPCrypto' => $settings->smtp_crypto ?? 'tls',
            'mailType'   => 'html',
            'charset'    => 'utf-8',
        ];

        $emailService = \Config\Services::email();
        $emailService->initialize($config);
        $emailService->setFrom($settings->from_email, $settings->from_name);
        $emailService->setTo($toEmail);
        $emailService->setSubject('🔐 Portfolio Admin — Password Reset Request');
        $emailService->setMessage($this->resetEmailTemplate($username, $resetUrl));

        return $emailService->send();
    }

    /**
     * HTML email template for password reset.
     */
    private function resetEmailTemplate(string $username, string $resetUrl): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #0f0c29; margin: 0; padding: 20px; }
  .container { max-width: 560px; margin: 0 auto; background: #1a1a2e; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
  .header { background: linear-gradient(135deg, #6c5ce7, #fd79a8); padding: 35px 30px; text-align: center; }
  .header h1 { color: #fff; margin: 0; font-size: 1.6rem; }
  .header p { color: rgba(255,255,255,0.8); margin: 8px 0 0; font-size: 0.95rem; }
  .body { padding: 35px 30px; color: #e0e0e0; line-height: 1.7; }
  .body p { margin: 0 0 16px; }
  .btn { display: inline-block; background: linear-gradient(135deg,#6c5ce7,#fd79a8); color: #fff !important; text-decoration: none; padding: 14px 35px; border-radius: 50px; font-weight: 600; font-size: 1rem; margin: 10px 0 20px; }
  .note { background: rgba(108,92,231,0.1); border: 1px solid rgba(108,92,231,0.2); border-radius: 10px; padding: 14px; font-size: 0.88rem; color: #a29bfe; }
  .footer { padding: 20px 30px; text-align: center; color: #636e72; font-size: 0.82rem; border-top: 1px solid rgba(255,255,255,0.06); }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>🔐 Password Reset</h1>
      <p>Portfolio Admin Panel</p>
    </div>
    <div class="body">
      <p>Hi <strong>{$username}</strong>,</p>
      <p>We received a request to reset your admin password. Click the button below to create a new password. This link is valid for <strong>1 hour</strong>.</p>
      <div style="text-align:center;">
        <a href="{$resetUrl}" class="btn">Reset My Password</a>
      </div>
      <div class="note">
        ⚠️ If you did not request a password reset, please ignore this email — your account remains secure and no changes have been made.
      </div>
      <p style="margin-top:20px; font-size:0.85rem; color:#b2bec3;">Or copy and paste this link in your browser:<br><a href="{$resetUrl}" style="color:#a29bfe; word-break:break-all;">{$resetUrl}</a></p>
    </div>
    <div class="footer">Sent at: {$this->getFormattedTime()} &bull; Portfolio Admin System</div>
  </div>
</body>
</html>
HTML;
    }

    private function getFormattedTime(): string
    {
        return date('Y-m-d H:i:s T');
    }

    /**
     * Mask an email address showing only partial characters.
     * e.g. mangeshdarade9552@gmail.com → ma**********9552@gm****.com
     */
    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email, 2);

        // Mask local part: keep first 2 + last 4 chars
        $localLen = strlen($local);
        if ($localLen <= 6) {
            $maskedLocal = substr($local, 0, 1) . str_repeat('*', $localLen - 1);
        } else {
            $maskedLocal = substr($local, 0, 2)
                . str_repeat('*', $localLen - 6)
                . substr($local, -4);
        }

        // Mask domain: keep first 2 chars + TLD
        $dotPos       = strrpos($domain, '.');
        $domainName   = substr($domain, 0, $dotPos);
        $tld          = substr($domain, $dotPos);          // e.g. ".com"
        $maskedDomain = substr($domainName, 0, 2) . str_repeat('*', max(1, strlen($domainName) - 2)) . $tld;

        return $maskedLocal . '@' . $maskedDomain;
    }
}
