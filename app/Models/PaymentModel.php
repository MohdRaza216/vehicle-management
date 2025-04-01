<?php
namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['customer_id', 'vehicle_id', 'amount', 'created_at', 'updated_at']; // Adding updated_at for consistency.

    public function getPaymentsWithDetails()
    {
        return $this->select('payments.*, customers.name AS customer_name, vehicles.name AS vehicle_name, vehicles.price AS vehicle_price')
            ->join('customers', 'customers.id = payments.customer_id')
            ->join('vehicles', 'vehicles.id = payments.vehicle_id')
            ->findAll() ?? [];  // Return an empty array if no data is found
    }

    public function getTotalPaidAmount($vehicleId, $customerId)
    {
        $result = $this->selectSum('amount')
            ->where('vehicle_id', $vehicleId)
            ->where('customer_id', $customerId)
            ->get()
            ->getRow();

        return $result->amount ?? 0;  // Ensure null safety by returning 0 if no result is found
    }

    public function createPayment($data)
    {
        // Automatically sets created_at and updated_at on insert
        return $this->insert($data);
    }

    public function updatePayment($id, $data)
    {
        // Automatically sets updated_at on update
        return $this->update($id, $data);
    }

    public function deletePayment($id)
    {
        return $this->delete($id);
    }
}
