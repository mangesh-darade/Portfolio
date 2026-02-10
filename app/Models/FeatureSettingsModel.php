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
     * Get features as key-value pair for easy checking
     */
    public function getFeaturesMap()
    {
        $features = $this->findAll();
        $map = [];
        foreach ($features as $f) {
            $map[$f['feature_key']] = $f['is_enabled'];
        }
        return $map;
    }
}
