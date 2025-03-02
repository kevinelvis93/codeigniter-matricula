<?php

namespace App\Controllers;

use App\Models\ColaboradorModel;
use App\Models\FuncionModel;

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

    // ✅ Obtener departamentos directamente desde el modelo
    $model = new FuncionModel();
    $data['departamentos'] = $model->obtenerDepartamentos(); 

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
            'telefonos.*' => [
                'rules' => 'max_length[15]|is_unique[ccp_telefono.numero]',
                'errors' => [
                    'max_length' => 'El número de teléfono no puede exceder los 15 caracteres.',
                    'is_unique' => 'El número de teléfono {value} ya está registrado.',
                ]
            ]
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
            'departamento' => $data['departamento'],
            'provincia' => $data['provincia'],
            'distrito' => $data['distrito'],
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

    // ✅ Obtener departamentos desde FuncionModel
    $ubicacionModel = new FuncionModel();
    $departamentos = $ubicacionModel->obtenerDepartamentos();
    $provincias = [];
    $distritos = [];

    // ✅ Si el colaborador tiene un departamento, obtener sus provincias
    if (!empty($colaborador['departamento'])) {
        $provincias = $ubicacionModel->obtenerProvincias($colaborador['departamento']);
    }

    // ✅ Si el colaborador tiene una provincia, obtener sus distritos
    if (!empty($colaborador['provincia'])) {
        $distritos = $ubicacionModel->obtenerDistritos($colaborador['departamento'], $colaborador['provincia']);
    }

    // ✅ Asegurar que los correos y teléfonos sean arrays válidos
    $colaborador['emails'] = !empty($colaborador['emails']) ? $colaborador['emails'] : [''];
    $colaborador['telefonos'] = !empty($colaborador['telefonos']) ? $colaborador['telefonos'] : [''];

    // ✅ Pasar todos los datos necesarios a la vista
    $data['colaborador'] = $colaborador;
    $data['roles'] = $this->roles;
    $data['tipoIdentificacion'] = $this->tipoIdentificacion;
    $data['departamentos'] = $departamentos;
    $data['provincias'] = $provincias;
    $data['distritos'] = $distritos;
    $data['header'] = view('template/header', ['menu' => $this->menu]);
    $data['footer'] = view('template/footer');

    return view('colaborador/editar', $data);
}





    public function actualizar($id)
{
    $this->verificarAutenticacion();

    $model = new ColaboradorModel();
    $db = \Config\Database::connect();

    // Obtener los datos actuales del colaborador
    $colaboradorActual = $model->obtenerColaboradorPorId($id);

    // Obtener datos del formulario
    $colaboradorData = [
        'direccion' => trim($this->request->getPost('direccion')),
        'usuario' => trim($this->request->getPost('usuario')),
        'estado' => $this->request->getPost('estado'),
        'id_tipo_identificacion' => $this->request->getPost('id_tipo_identificacion'),
        'identificacion_descripcion' => trim($this->request->getPost('identificacion_descripcion')),
        'departamento' => trim($this->request->getPost('departamento')),
        'provincia' => trim($this->request->getPost('provincia')),
        'distrito' => trim($this->request->getPost('distrito')),

        // Agregar nombres y apellidos actuales para evitar errores en el modelo
        'nombres' => trim($colaboradorActual['nombres']),
        'apellido_paterno' => trim($colaboradorActual['apellido_paterno']),
        'apellido_materno' => trim($colaboradorActual['apellido_materno']),
    ];

    // Si el usuario está vacío, generarlo con los datos actuales
    if (empty($colaboradorData['usuario'])) {
        $colaboradorData['usuario'] = $this->generarUsuario(
            $colaboradorActual['nombres'],
            $colaboradorActual['apellido_paterno'],
            $colaboradorActual['apellido_materno']
        );
    }

    // Validar contraseña solo si el usuario ingresó una nueva
    $passwordIngresado = trim($this->request->getPost('password'));

    if (!empty($passwordIngresado)) {
        if (strlen($passwordIngresado) < 8) {
            return redirect()->back()->with('error', 'La contraseña debe tener al menos 8 caracteres.')->withInput();
        }
        // Cifrar la nueva contraseña antes de enviarla al modelo
        $colaboradorData['password'] = $passwordIngresado;
    }

    // Validaciones de unicidad antes de actualizar
    if ($colaboradorData['usuario'] !== $colaboradorActual['usuario'] &&
        $db->table('ccp_usuario')->where('usuario', $colaboradorData['usuario'])->where('id !=', $id)->countAllResults() > 0) {
        
        return redirect()->back()->with('error', 'El usuario ya está en uso.')->withInput();
    }

    if ($colaboradorData['identificacion_descripcion'] !== $colaboradorActual['identificacion_descripcion'] &&
        $db->table('ccp_persona_identificacion')->where('identificacion_descripcion', $colaboradorData['identificacion_descripcion'])
        ->where('id_persona !=', $colaboradorActual['persona_id'])->countAllResults() > 0) {
        
        return redirect()->back()->with('error', 'El DNI/Carnet ya está registrado.')->withInput();
    }

    // Obtener correos electrónicos y validar unicidad
    $emails = array_filter($this->request->getPost('emails') ?? [], fn($email) => !empty(trim($email)));
    $uniqueEmails = array_unique($emails); // Evita duplicados en la misma solicitud

    if (count($emails) !== count($uniqueEmails)) {
        return redirect()->back()->with('error', 'No se pueden ingresar correos electrónicos duplicados.')->withInput();
    }

    foreach ($uniqueEmails as $email) {
        if ($db->table('ccp_email')->where('email', $email)->where('persona_id !=', $colaboradorActual['persona_id'])->countAllResults() > 0) {
            return redirect()->back()->with('error', 'El correo ' . esc($email) . ' ya está registrado.')->withInput();
        }
    }

    // Obtener teléfonos y validar unicidad
    $telefonos = array_filter($this->request->getPost('telefonos') ?? [], fn($telefono) => !empty(trim($telefono)));
    $uniqueTelefonos = array_unique($telefonos); // Evita duplicados en la misma solicitud

    if (count($telefonos) !== count($uniqueTelefonos)) {
        return redirect()->back()->with('error', 'No se pueden ingresar teléfonos duplicados.')->withInput();
    }

    foreach ($uniqueTelefonos as $telefono) {
        if ($db->table('ccp_telefono')->where('numero', $telefono)->where('persona_id !=', $colaboradorActual['persona_id'])->countAllResults() > 0) {
            return redirect()->back()->with('error', 'El número de teléfono ' . esc($telefono) . ' ya está registrado.')->withInput();
        }
    }

    // Obtener los roles seleccionados
    $roles = $this->request->getPost('roles') ?? [];

    // Ejecutar la actualización en el modelo
    $actualizado = $model->actualizarColaborador($id, $colaboradorData, $roles, $uniqueEmails, $uniqueTelefonos);

    if ($actualizado) {
        return redirect()->to(site_url('colaborador'))->with('success', 'Colaborador actualizado correctamente.');
    } else {
        return redirect()->back()->with('error', 'No se pudo actualizar el colaborador.')->withInput();
    }
}



    public function validarClave()
    {
        $claveIngresada = $this->request->getJSON()->clave;
        $claveEstatica = '12345678'; // Clave fija temporal

        if ($claveIngresada === $claveEstatica) {
            return $this->response->setJSON(['valido' => true]);
        } else {
            return $this->response->setJSON(['valido' => false]);
        }
    }


    public function eliminar($id)
    {
        $this->verificarAutenticacion();

        $colaboradorModel = new ColaboradorModel();
        $db = \Config\Database::connect();

        // Verificar si el usuario existe
        $usuario = $colaboradorModel->find($id);
        if (!$usuario) {
            return $this->response->setJSON(['success' => false, 'error' => 'Colaborador no encontrado.']);
        }

        $personaId = $usuario['persona_id']; // Obtener el ID de la persona asociada

        $db->transStart(); // Iniciar transacción para asegurar consistencia

        // Eliminar registros relacionados en todas las tablas asociadas
        $db->table('ccp_usuario_rol')->where('usuario_id', $id)->delete(); // Eliminar roles
        $db->table('ccp_email')->where('persona_id', $personaId)->delete(); // Eliminar correos
        $db->table('ccp_telefono')->where('persona_id', $personaId)->delete(); // Eliminar teléfonos
        $db->table('ccp_persona_identificacion')->where('id_persona', $personaId)->delete(); // Eliminar identificación
        
        // Eliminar usuario y persona asociada
        $colaboradorModel->delete($id); // Eliminar de ccp_usuario
        $db->table('ccp_persona')->where('id', $personaId)->delete(); // Eliminar de ccp_persona

        $db->transComplete(); // Finalizar transacción

        if ($db->transStatus()) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'error' => 'Error al eliminar.']);
        }
    }



}
