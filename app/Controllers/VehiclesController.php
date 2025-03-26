<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use CodeIgniter\HTTP\ResponseInterface;

class VehiclesController extends BaseController
{
    protected $vehicleModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
    }

    public function index()
    {
        $vehicleModel = new VehicleModel();
        $data['vehicles'] = $vehicleModel->findAll();

        return view('vehicles/vehiclesIndex', $data);
    }

    public function add()
    {
        try {
            $vehicleModel = new VehicleModel();
            $validation = \Config\Services::validation();

            // Define validation rules
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[255]',
                'model' => 'required|min_length[2]|max_length[255]',
                'price' => 'required|numeric',
                'status' => 'required|in_list[Available,Pending,Booked]',
            ]);

            if (!$this->validate($validation->getRules())) {
                // Return validation errors in JSON format
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $validation->getErrors()
                ]);
            }

            // Insert data if validation passes
            $data = [
                'name' => $this->request->getPost('name'),
                'model' => $this->request->getPost('model'),
                'price' => $this->request->getPost('price'),
                'status' => $this->request->getPost('status'),
            ];

            if ($vehicleModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Vehicle added successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add vehicle']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in add() method: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


}
