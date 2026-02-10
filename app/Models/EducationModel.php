<?php

namespace App\Models;

use CodeIgniter\Model;

class EducationModel extends Model
{
    protected $table = 'education';
    protected $primaryKey = 'id';
    protected $allowedFields = ['degree', 'institution', 'year_start', 'year_end', 'description', 'gpa', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
}
