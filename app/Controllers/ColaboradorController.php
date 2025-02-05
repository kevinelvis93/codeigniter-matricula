<?php

namespace App\Controllers;

use App\Models\ColaboradorModel;

class ColaboradorController extends BaseController
{

    public function index()
    {
        $this->verificarAutenticacion();

        $colaboradorModel = new ColaboradorModel();

        // Parámetros de búsqueda, ordenamiento y paginación
        $search = $this->request->getGet('search') ?? '';
        $orderField = $this->request->getGet('orderField') ?? 'id';
        $orderType = $this->request->getGet('orderType') ?? 'ASC';
        $perPage = 10; // Número de registros por página
        $page = $this->request->getGet('page') ?? 1;

        // Mapeo de columnas válidas para ordenamiento
        $validColumns = [
            'id' => 'ccp_usuario.id',
            'usuario' => 'ccp_usuario.usuario',
            'nombres' => 'ccp_persona.nombres',
            'apellidos' => 'ccp_persona.apellido_paterno', // Ordena por apellido paterno
            'dni' => 'ccp_persona_identificacion.identificacion_descripcion',
            'direccion' => 'ccp_persona.direccion',
            'roles' => 'roles', // Alias de GROUP_CONCAT
            'emails' => 'emails', // Alias de GROUP_CONCAT
            'telefonos' => 'telefonos', // Alias de GROUP_CONCAT
        ];

        // Validar si la columna de orden es válida
        if (!array_key_exists($orderField, $validColumns)) {
            $orderField = 'id';
        }

        $orderField = $validColumns[$orderField]; // Usar el nombre real de la columna
        $offset = ($page - 1) * $perPage;

        // Obtener usuarios y contar el total
        $usuarios = $colaboradorModel->buscarUsuarios($search, $orderField, $orderType, $perPage, $offset);
        $totalUsuarios = $colaboradorModel->contarUsuarios($search);

        $pager = \Config\Services::pager();
        $pager->makeLinks($page, $perPage, $totalUsuarios);

        $data = [
            'usuarios' => $usuarios,
            'pager' => $pager,
            'search' => $search,
            'orderField' => $this->request->getGet('orderField') ?? 'id', // Campo seleccionado por el usuario
            'orderType' => $orderType,
            'header' => view('template/header', ['menu' => $this->menu]),
            'footer' => view('template/footer'),
        ];

        return view('colaborador/index', $data);
    }



    public function registrar()
    {
        $this->verificarAutenticacion();
        $this->cargarRoles();
        $this->cargarTipoIdentificacion();

        $data['roles'] = $this->roles;
        $data['tipoIdentificacion'] = $this->tipoIdentificacion;
        $data['header'] = view('template/header', ['menu' => $this->menu]);
        $data['footer'] = view('template/footer');
        return view('colaborador/registrar', $data);
    } 

    public function registrarPost()
    {
        $this->verificarAutenticacion();

        $colaboradorModel = new ColaboradorModel();

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            // Validar entrada de datos
            $validation = \Config\Services::validation();
            $validation->setRules([
                'id_tipo_identificacion' => 'required',
                'identificacion_descripcion' => [
                    'rules' => 'required|is_unique[ccp_persona_identificacion.identificacion_descripcion]',
                    'errors' => [
                        'required' => 'El campo es requerido.',
                        'is_unique' => 'El Número de Documento ya existe.'
                    ]
                ],
                'nombres' => 'required',
                'apellido_paterno' => 'required',
                'apellido_materno' => 'required',
                'password' => 'required|min_length[8]',
                'roles' => 'required',
                'emails.*' => [
                    'rules' => 'valid_email|is_unique[ccp_email.email]',
                    'errors' => [
                        'valid_email' => 'El correo {value} no es válido.',
                        'is_unique' => 'El correo {value} ya está registrado.',
                    ]
                ],
                'telefonos.*' => 'max_length[15]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('error', $validation->listErrors());
            }

            // Procesar datos del formulario
            $colaboradorData = [
                'id_tipo_identificacion' => $data['id_tipo_identificacion'],
                'identificacion_descripcion' => $data['identificacion_descripcion'],
                'nombres' => $data['nombres'],
                'apellido_paterno' => $data['apellido_paterno'],
                'apellido_materno' => $data['apellido_materno'],
                'direccion' => $data['direccion'] ?? null,
                'password' => $data['password']
            ];
            $roles = $data['roles'];
            $emails = $data['emails'];
            $telefonos = $data['telefonos'];

            // Generar el nombre de usuario usando el método en BaseController
            $colaboradorData['usuario'] = $this->generarUsuario($colaboradorData['nombres'], $colaboradorData['apellido_paterno'], $colaboradorData['apellido_materno']);

            $result = $colaboradorModel->registrarColaborador($colaboradorData, $roles, $emails, $telefonos);

            if ($result) {
                return redirect()->to('/colaborador')->with('success', 'Colaborador registrado exitosamente.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Ocurrió un error al registrar el colaborador.');
            }
        }

        return redirect()->to('/colaborador');
    }

    public function editar($id)
{
    $this->verificarAutenticacion();
    $this->cargarRoles();
    $this->cargarTipoIdentificacion();

    $model = new ColaboradorModel();
    $colaborador = $model->obtenerColaboradorPorId($id);

    if (!$colaborador) {
        return redirect()->to(site_url('colaborador'))->with('error', 'Colaborador no encontrado.');
    }

    

    // Asegurar que los correos y teléfonos sean arrays válidos
    $colaborador['emails'] = !empty($colaborador['emails']) ? $colaborador['emails'] : [''];
    $colaborador['telefonos'] = !empty($colaborador['telefonos']) ? $colaborador['telefonos'] : [''];

    $data['colaborador'] = $colaborador;
    $data['roles'] = $this->roles;
    $data['tipoIdentificacion'] = $this->tipoIdentificacion;
    $data['header'] = view('template/header', ['menu' => $this->menu]);
    $data['footer'] = view('template/footer');

    return view('colaborador/editar', $data);
}




    public function actualizar($id)
{
    $this->verificarAutenticacion();

    $model = new ColaboradorModel();

    // Obtener los datos actuales del colaborador
    $colaboradorActual = $model->obtenerColaboradorPorId($id);

    // Obtener datos del formulario
    $colaboradorData = [
        'direccion' => $this->request->getPost('direccion'),
        'usuario' => $this->request->getPost('usuario'),
        'estado' => $this->request->getPost('estado'),
        'id_tipo_identificacion' => $this->request->getPost('id_tipo_identificacion'),
        'identificacion_descripcion' => $this->request->getPost('identificacion_descripcion'),
        
        // Agregar nombres y apellidos actuales para evitar el error en el modelo
        'nombres' => $colaboradorActual['nombres'],
        'apellido_paterno' => $colaboradorActual['apellido_paterno'],
        'apellido_materno' => $colaboradorActual['apellido_materno'],
    ];

    // Si el usuario está vacío, generarlo con los datos actuales
    if (empty($colaboradorData['usuario'])) {
        $colaboradorData['usuario'] = $this->generarUsuario(
            $colaboradorActual['nombres'],
            $colaboradorActual['apellido_paterno'],
            $colaboradorActual['apellido_materno']
        );
    }

    // Obtener los roles seleccionados
    $roles = $this->request->getPost('roles') ?? [];

    // Obtener los correos electrónicos
    $emails = array_filter($this->request->getPost('emails') ?? [], fn($email) => !empty(trim($email)));

    // Obtener los teléfonos
    $telefonos = array_filter($this->request->getPost('telefonos') ?? [], fn($telefono) => !empty(trim($telefono)));

    // Ejecutar la actualización en el modelo
    $actualizado = $model->actualizarColaborador($id, $colaboradorData, $roles, $emails, $telefonos);

    if ($actualizado) {
        return redirect()->to(site_url('colaborador'))->with('success', 'Colaborador actualizado correctamente.');
    } else {
        return redirect()->back()->with('error', 'No se pudo actualizar el colaborador.');
    }
}




}
