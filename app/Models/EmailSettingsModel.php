<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailSettingsModel extends Model
{
    protected $table = 'email_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['protocol', 'smtp_host', 'smtp_user', 'smtp_pass', 'smtp_port', 'smtp_crypto', 'from_email', 'from_name', 'updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = 'updated_at';
}
