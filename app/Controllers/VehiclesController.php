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
            $rules = [
                'name' => 'required|min_length[3]|max_length[255]|is_unique[vehicles.name]',
                'model' => 'required|min_length[2]|max_length[255]|is_unique[vehicles.model]',
                'price' => 'required|numeric',
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            // Set default status to 'Available'
            $data = [
                'name' => $this->request->getPost('name'),
                'model' => $this->request->getPost('model'),
                'price' => $this->request->getPost('price'),
                'status' => 'Available', // Default status
            ];

            if ($this->vehicleModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Vehicle added successfully']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to add vehicle']);
            }
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

        // Prevent status change if the vehicle is already booked
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

        if ($this->vehicleModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Vehicle updated successfully!',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update vehicle. Try again.',
            ]);
        }
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
        $vehicles = !empty($status) ? $this->vehicleModel->where('status', $status)->findAll() : $this->vehicleModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $vehicles
        ]);
    }
}
