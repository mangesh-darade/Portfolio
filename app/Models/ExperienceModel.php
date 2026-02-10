<?php

namespace App\Models;

use CodeIgniter\Model;

class ExperienceModel extends Model
{
    protected $table = 'experience';
    protected $primaryKey = 'id';
    protected $allowedFields = ['job_title', 'company', 'start_date', 'end_date', 'description', 'is_current', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
}
