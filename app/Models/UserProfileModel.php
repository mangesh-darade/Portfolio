<?php

namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table            = 'profile';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['full_name', 'bio', 'email', 'phone', 'address', 'profile_image', 'resume', 'linkedin', 'github', 'twitter', 'total_views'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
