<?php

namespace App\Controllers;

class PlantillaController extends BaseController
{

    public function index()
    {

        $this->verificarAutenticacion();

        $data['header'] = view('template/header', ['menu' => $this->menu]);
        $data['footer'] = view('template/footer');
        return view('plantilla', $data);
    }

}
