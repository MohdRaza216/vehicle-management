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
            return $this->response->setJSON([]);
        }

        $html = "";
        $srNo = 1;
        foreach ($payments as $payment) {
            $pendingAmount = $payment['vehicle_price'] - $payment['amount'];
            $html .= "<tr>
            <td>{$srNo}</td>
            <td>{$payment['customer_name']}</td>
            <td>{$payment['vehicle_name']}</td>
            <td>{$payment['amount']}</td>
            <td>{$pendingAmount}</td>
            <td>
                <button class='btn btn-sm btn-warning editPayment' data-id='{$payment['id']}'>Edit</button>
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
            'amount' => 'required|numeric|greater_than_equal_to[1]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ]);
        }

        $paymentModel = new PaymentModel();

        $data = [
            'customer_id' => $this->request->getPost('customer_id'),
            'vehicle_id' => $this->request->getPost('vehicle_id'),
            'amount' => $this->request->getPost('amount')
        ];

        if (!$paymentModel->insert($data)) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Failed to insert payment!'
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
        $payment = $paymentModel->find($id);

        if (!$payment) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Payment not found']);
        }

        return $this->response->setJSON($payment);
    }

    public function delete($id)
    {
        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $payment = $paymentModel->find($id);
        if ($payment) {
            $vehicleId = $payment['vehicle_id'];
            $paymentModel->delete($id);
            $this->updateVehicleStatus($vehicleId);
            return $this->response->setJSON(['success' => true, 'message' => 'Payment deleted successfully']);
        }

        return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON(['error' => 'Payment not found']);
    }

    private function updateVehicleStatus($vehicleId)
    {
        $paymentModel = new PaymentModel();
        $vehicleModel = new VehicleModel();

        $totalPaid = $paymentModel->getTotalPaidAmount($vehicleId);
        $vehicle = $vehicleModel->find($vehicleId);

        if (!$vehicle) {
            return;
        }

        $pendingAmount = $vehicle['price'] - $totalPaid;
        $status = ($pendingAmount <= 0) ? 'Booked' : 'Pending';

        $vehicleModel->update($vehicleId, ['status' => $status]);
    }
}
