<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\ResponseInterface;

class CustomersController extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();
        $data['customers'] = $customerModel->findAll();

        return view('customers/customersIndex', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'mobile_number' => 'required|numeric|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->listErrors(),
            ]);
        }

        $model = new CustomerModel();
        $email = $this->request->getPost('email');

        // Duplicate Email Check
        $existingCustomer = $model->where('email', $email)->first();
        if ($existingCustomer) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => 'Customer with this email already exists!',
            ]);
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $email,
            'mobile_number' => $this->request->getPost('mobile_number'),
        ];
        $model->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Customer added successfully!',
        ]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'mobile_number' => 'required|numeric|min_length[10]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->listErrors(),
            ]);
        }

        $model = new CustomerModel();
        $email = $this->request->getPost('email');

        // Duplicate Email Check (excluding current customer)
        $existingCustomer = $model->where('email', $email)->where('id !=', $id)->first();
        if ($existingCustomer) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => 'Another customer with this email already exists!',
            ]);
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $email,
            'mobile_number' => $this->request->getPost('mobile_number'),
        ];
        $model->update($id, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Customer updated successfully!',
        ]);
    }

    public function delete($id)
    {
        $model = new CustomerModel();
        $model->delete($id);

        return redirect()->to('/customers')->with('success', 'Customer deleted successfully!');
    }
}
