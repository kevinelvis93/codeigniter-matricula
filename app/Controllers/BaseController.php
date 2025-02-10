<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MenuModel;
use App\Models\FuncionModel;

class BaseController extends Controller
{
    protected $request;
    protected $menu = []; // Menú cargado dinámicamente
    protected $roles;

    protected $helpers = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = \Config\Services::session();

        // Cargar el menú dinámico según los roles
        $this->cargarMenu();
    }

    protected function verificarAutenticacion()
    {
        if (!session()->get('isLoggedIn')) {
            redirect()->to('/login')->send();
            exit;
        }
    }

    protected function cargarMenu()
    {
        // Instanciar el modelo
        $menuModel = new MenuModel();

        // Obtener los roles del usuario desde la sesión
        $roles = $this->session->get('roles') ?? []; // Siempre define un array vacío por defecto

        // Validar que los roles sean un array
        if (!is_array($roles)) {
            $roles = []; // Forzar que sea un array vacío
        }

        // Debug opcional para roles
        // print_r($roles); exit;

        // Obtener el menú filtrado por roles
        $this->menu = $menuModel->obtenerMenuPorRoles($roles);
    }

    protected function cargarRoles()
    {
        $funcionModel = new FuncionModel();
        $this->roles = $funcionModel->obtenerRoles();
    }

    protected function generarUsuario($nombres, $apellidoPaterno, $apellidoMaterno)
    {
        // Tomar la primera letra del nombre
        $inicialNombre = mb_substr($this->limpiarTexto($nombres), 0, 3);

        // Tomar el apellido paterno completo
        $apellidoPaterno = $this->limpiarTexto($apellidoPaterno);

        // Tomar la primera letra del apellido materno
        $inicialApellidoMaterno = mb_substr($this->limpiarTexto($apellidoMaterno), 0, 1);

        // Concatenar y convertir a minúsculas
        return strtolower($inicialNombre . $apellidoPaterno . $inicialApellidoMaterno);
    }

    protected function limpiarTexto($texto)
    {
        $reemplazos = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'Á' => 'a', 'É' => 'e', 'Í' => 'i', 'Ó' => 'o', 'Ú' => 'u',
            'ñ' => 'n', 'Ñ' => 'n'
        ];

        return strtr($texto, $reemplazos);
    }

    protected function cargarTipoIdentificacion()
    {
        $funcionModel = new FuncionModel();
        $this->tipoIdentificacion = $funcionModel->obtenerTipoIdentificacion();
    }





}
