<?php

namespace App\Controllers;

use App\Models\FuncionModel;
use CodeIgniter\Controller;

class UbigeoController extends Controller
{
    public function obtenerDepartamentos()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/no-permission');
        }

        $model = new FuncionModel();
        return $this->response->setJSON($model->obtenerDepartamentos());
    }

    public function obtenerProvincias($codigo_departamento)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/no-permission');
        }

        $model = new FuncionModel();
        $provincias = $model->obtenerProvincias($codigo_departamento);

        if (empty($provincias)) {
            return $this->response->setContentType('text/plain')->setBody("<option value=''>No hay provincias disponibles</option>");
        }

        $html = "";
        foreach ($provincias as $provincia) {
            $html .= "<option value='" . esc($provincia['codigo_provincia']) . "'>" . esc($provincia['nombre_provincia']) . "</option>";
        }

        return $this->response->setContentType('text/plain')->setBody($html);
    }

    public function obtenerDistritos($codigo_departamento, $codigo_provincia)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/no-permission');
        }

        $model = new FuncionModel();
        $distritos = $model->obtenerDistritos($codigo_departamento, $codigo_provincia);

        if (empty($distritos)) {
            return $this->response->setContentType('text/plain')->setBody("<option value=''>No hay distritos disponibles</option>");
        }

        $html = "";
        foreach ($distritos as $distrito) {
            $html .= "<option value='" . esc($distrito['codigo_distrito']) . "'>" . esc($distrito['nombre_distrito']) . "</option>";
        }

        return $this->response->setContentType('text/plain')->setBody($html);
    }
}
