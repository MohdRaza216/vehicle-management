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

}
