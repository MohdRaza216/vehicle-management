<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'model', 'price', 'status', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getVehiclesByStatus($status = null)
    {
        if ($status !== null) {
            return $this->where('status', $status)->findAll();
        }
        return $this->findAll();
    }
}
