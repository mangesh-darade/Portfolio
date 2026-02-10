<?php

namespace App\Models;

use CodeIgniter\Model;

class SeoModel extends Model
{
    protected $table = 'seo_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['site_title', 'site_description', 'site_keywords', 'site_author', 'og_image','updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = ''; // No created_at column
    protected $updatedField  = 'updated_at';
}
