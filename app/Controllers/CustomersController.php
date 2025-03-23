<?php

namespace App\Controllers;

use App\Models\CustomerModel;

class CustomersController extends BaseController
{
    // Show all customers
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
            session()->setFlashdata('showAddModal', true);
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        

        $customerModel = new \App\Models\CustomerModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'),
        ];

        $customerModel->insert($data);

        return redirect()->to('/customers')->with('success', 'Customer Added Successfully');
    }

    public function edit($id)
    {
        $customerModel = new CustomerModel();
        $data['customer'] = $customerModel->find($id);

        if (!$data['customer']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Customer not found');
        }

        return view('customers/editCustomerForm', $data);
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
            session()->setFlashdata('showEditModal', true);
            session()->setFlashdata('editCustomerId', $id);
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        

        $customerModel = new CustomerModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'),
        ];

        if ($customerModel->update($id, $data)) {
            return redirect()->to('/customers')->with('success', 'Customer updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update customer');
        }
    }

    public function delete($id)
    {
        $customerModel = new \App\Models\CustomerModel();
        $customerModel->delete($id);

        return redirect()->to('/customers')->with('success', 'Customer Deleted Successfully');
    }

}
