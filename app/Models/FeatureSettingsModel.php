<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureSettingsModel extends Model
{
    protected $table            = 'feature_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['feature_key', 'feature_name', 'is_enabled', 'display_order'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all enabled features
     */
    public function getEnabledFeatures()
    {
        return $this->where('is_enabled', 1)->orderBy('display_order', 'ASC')->findAll();
    }

    /**
     * Get features as key-value pair for easy checking.
     * Falls back to all-enabled defaults if table is empty.
     */
    public function getFeaturesMap()
    {
        $features = $this->findAll();

        // If table is empty, return safe defaults (all sections visible)
        if (empty($features)) {
            return [
                'hero'         => 1,
                'skills'       => 1,
                'projects'     => 1,
                'experience'   => 1,
                'education'    => 1,
                'services'     => 1,
                'testimonials' => 1,
                'contact'      => 1,
            ];
        }

        $map = [];
        foreach ($features as $f) {
            $map[$f['feature_key']] = $f['is_enabled'];
        }
        return $map;
    }
}
