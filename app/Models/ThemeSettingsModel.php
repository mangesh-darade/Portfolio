<?php

namespace App\Models;

use CodeIgniter\Model;

class ThemeSettingsModel extends Model
{
    protected $table            = 'theme_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'theme_style', 
        'primary_color', 
        'secondary_color', 
        'bg_color', 
        'text_color', 
        'font_family', 
        'border_radius', 
        'glass_opacity', 
        'card_blur', 
        'custom_css'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get active theme settings or return defaults
     */
    public function getActiveTheme()
    {
        $theme = $this->first();
        if (!$theme) {
            return [
                'theme_style' => 'modern_dark',
                'primary_color' => '#6c5ce7',
                'secondary_color' => '#a29bfe',
                'bg_color' => '#0f0c29',
                'text_color' => '#ffffff',
                'font_family' => 'Outfit',
                'border_radius' => 20,
                'glass_opacity' => 0.1,
                'card_blur' => 10,
                'custom_css' => ''
            ];
        }
        return $theme;
    }
}
