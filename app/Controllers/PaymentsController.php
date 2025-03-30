<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\VehicleModel;
use App\Models\CustomerModel;
use CodeIgniter\HTTP\ResponseInterface;

class PaymentsController extends BaseController
{
    public function index()
    {
        $paymentModel = new PaymentModel();
        $customerModel = new CustomerModel();
        $vehicleModel = new VehicleModel();

        $data['payments'] = $paymentModel->findAll();
        $data['customers'] = $customerModel->findAll();
        $data['vehicles'] = $vehicleModel->findAll();

        return view('payments/paymentsIndex', $data);
    }

    public function fetch()
    {
        $paymentModel = new PaymentModel();
        $payments = $paymentModel->getPaymentsWithDetails();

        if (empty($payments)) {
            return $this->response->setJSON(['html' => '<tr><td colspan="6" class="text-center">No records found</td></tr>']);
        }

        $html = "";
        $srNo = 1;
        foreach ($payments as $payment) {
            $totalPaid = $paymentModel->getTotalPaidAmount($payment['vehicle_id'], $payment['customer_id']);
            $pendingAmount = max(0, $payment['vehicle_price'] - $totalPaid);

            $html .= "<tr>
            <td>{$srNo}</td>
            <td>{$payment['customer_name']}</td>
            <td>{$payment['vehicle_name']}</td>
            <td>{$payment['amount']}</td>
            <td>{$pendingAmount}</td>
            <td>
                <button class='btn btn-sm btn-primary editPayment' data-id='{$payment['id']}'>Edit</button>
                <button class='btn btn-sm btn-danger deletePayment' data-id='{$payment['id']}'>Delete</button>
            </td>
        </tr>";
            $srNo++;
        }

        return $this->response->setJSON(['html' => $html]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'customer_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'amount' => 'required|numeric|greater_than_equal_to[0]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $vehicle = $vehicleModel->find($this->request->getPost('vehicle_id'));
        if (!$vehicle) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Invalid vehicle!']);
        }

        $data = [
            'customer_id' => $this->request->getPost('customer_id'),
            'vehicle_id' => $this->request->getPost('vehicle_id'),
            'amount' => $this->request->getPost('amount')
        ];

        try {
            if (!$paymentModel->insert($data)) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to insert payment!'
                ]);
            }
        } catch (\Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => 'error',
                    'message' => 'This payment already exists!'
                ]);
            }
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Payment added successfully!'
        ]);
    }

    public function edit($id)
    {
        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $payment = $paymentModel->find($id);
        if (!$payment) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Payment not found']);
        }

        $totalPaid = $paymentModel->getTotalPaidAmount($payment['vehicle_id'], $payment['customer_id']);
        $vehicle = $vehicleModel->find($payment['vehicle_id']);
        $pendingAmount = max(0, $vehicle['price'] - $totalPaid);

        return $this->response->setJSON([
            'id' => $payment['id'],
            'customer_id' => $payment['customer_id'],
            'vehicle_id' => $payment['vehicle_id'],
            'amount' => $payment['amount'],
            'pending_amount' => $pendingAmount
        ]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'customer_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            'amount' => 'required|numeric|greater_than_equal_to[0]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $payment = $paymentModel->find($id);
        if (!$payment) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Payment not found']);
        }

        $vehicle = $vehicleModel->find($this->request->getPost('vehicle_id'));
        $totalPaid = $paymentModel->getTotalPaidAmount($vehicle['id'], $this->request->getPost('customer_id')) - $payment['amount'] + $this->request->getPost('amount');

        if ($totalPaid > $vehicle['price']) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Updated payment exceeds vehicle price!'
            ]);
        }

        $data = [
            'customer_id' => $this->request->getPost('customer_id'),
            'vehicle_id' => $this->request->getPost('vehicle_id'),
            'amount' => $this->request->getPost('amount')
        ];

        $paymentModel->update($id, $data);
        $this->updateVehicleStatus($vehicle['id'], $this->request->getPost('customer_id'));

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Payment updated successfully!'
        ]);
    }

    public function delete($id)
    {
        $paymentModel = new PaymentModel();

        $payment = $paymentModel->find($id);
        if ($payment) {
            $vehicleId = $payment['vehicle_id'];
            $customerId = $payment['customer_id'];
            $paymentModel->delete($id);
            $this->updateVehicleStatus($vehicleId, $customerId);
            return $this->response->setJSON(['success' => true, 'message' => 'Payment deleted successfully']);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Payment not found']);
    }

    public function updateVehicleStatus($vehicleId, $customerId)
    {
        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $totalPaid = $paymentModel->getTotalPaidAmount($vehicleId, $customerId);
        $vehicle = $vehicleModel->find($vehicleId);

        if (!$vehicle) {
            return;
        }

        $pendingAmount = max(0, $vehicle['price'] - $totalPaid);
        $status = ($pendingAmount <= 0) ? 'Booked' : 'Pending';

        $vehicleModel->update($vehicleId, ['status' => $status]);
    }
}
