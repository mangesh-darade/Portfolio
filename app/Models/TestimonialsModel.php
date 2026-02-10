<?php

namespace App\Models;

use CodeIgniter\Model;

class TestimonialsModel extends Model
{
    protected $table = 'testimonials';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'role', 'company', 'quote', 'image', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
}
