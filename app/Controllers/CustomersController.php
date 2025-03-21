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
}
