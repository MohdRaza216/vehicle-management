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

            $data = [
                'name' => $this->request->getPost('name'),
                'model' => $this->request->getPost('model'),
                'price' => $this->request->getPost('price'),
                'status' => $this->request->getPost('status'),
            ];

            if ($vehicleModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => $vehicleModel->errors()]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in add() method: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


}
