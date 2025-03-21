<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers'; // Table name in DB
    protected $primaryKey = 'id'; // Primary key column

    // Fields that can be inserted/updated
    protected $allowedFields = ['name', 'email', 'mobile_number'];

    // Automatically manage created_at and updated_at fields
    protected $useTimestamps = true;
}
