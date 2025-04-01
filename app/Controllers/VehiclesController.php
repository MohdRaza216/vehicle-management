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
        $data['vehicles'] = $this->vehicleModel->findAll();
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
                'status' => 'Available',
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $vehicleModel = new VehicleModel();
            $vehicleId = $vehicleModel->insert($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Vehicle added successfully.',
                'data' => array_merge($data, ['id' => $vehicleId])
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in add() method: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $vehicle = $this->vehicleModel->find($id);

        if ($vehicle) {
            return $this->response->setJSON(['status' => 'success', 'data' => $vehicle]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle not found']);
        }
    }

    public function update()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id' => 'required|integer',
            'name' => 'required|min_length[3]|max_length[255]',
            'model' => 'required|min_length[2]|max_length[255]',
            'price' => 'required|numeric',
            'status' => 'required|in_list[Available,Pending,Booked]',
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        $id = $this->request->getPost('id');
        $vehicle = $this->vehicleModel->find($id);

        if (!$vehicle) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle not found']);
        }

        if ($vehicle['status'] === 'Booked' && $this->request->getPost('status') !== 'Booked') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Cannot change status of a booked vehicle!',
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'model' => $this->request->getPost('model'),
            'price' => $this->request->getPost('price'),
            'status' => $this->request->getPost('status'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->vehicleModel->update($id, $data);
        $response = $result
            ? ['status' => 'success', 'message' => 'Vehicle updated successfully!']
            : ['status' => 'error', 'message' => 'Failed to update vehicle. Try again.'];

        return $this->response->setJSON($response);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
        }

        if ($this->vehicleModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Vehicle deleted successfully!']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete vehicle.']);
        }
    }

    public function filterVehicles()
    {
        $status = $this->request->getGet('status');

        if (empty($status)) {
            $vehicles = $this->vehicleModel->findAll();
        } else {
            $vehicles = $this->vehicleModel->where('status', $status)->findAll();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'vehicles' => $vehicles
        ]);
    }
}
