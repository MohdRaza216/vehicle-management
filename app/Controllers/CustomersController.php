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
        $customerModel = new \App\Models\CustomerModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile_number' => $this->request->getPost('mobile_number'),
        ];

        $customerModel->insert($data);

        return redirect()->to('/customers')->with('success', 'Customer Added Successfully');
    }

}
