<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'vehicle_id', 'amount', 'created_at'];

    public function getPaymentsWithDetails()
    {
        return $this->select('payments.*, customers.name AS customer_name, vehicles.name AS vehicle_name, vehicles.price AS vehicle_price')
            ->join('customers', 'customers.id = payments.customer_id')
            ->join('vehicles', 'vehicles.id = payments.vehicle_id')
            ->findAll();
    }

    public function getTotalPaidAmount($vehicleId, $customerId)
    {
        return $this->selectSum('amount')
            ->where('vehicle_id', $vehicleId)
            ->where('customer_id', $customerId)
            ->get()
            ->getRow()
            ->amount ?? 0;
    }

}
