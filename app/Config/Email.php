<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'admin@example.com';
    public string $fromName   = 'Portfolio Admin';

    public string $protocol = 'smtp';
    public string $SMTPHost = 'sandbox.smtp.mailtrap.io'; // Example: Mailtrap
    public string $SMTPUser = 'your_username';
    public string $SMTPPass = 'your_password';
    public int $SMTPPort = 2525;
    public string $SMTPCrypto = 'tls';
    public string $mailType = 'html';

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
