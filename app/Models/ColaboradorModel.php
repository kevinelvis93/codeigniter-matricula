<?php

namespace App\Models;

use CodeIgniter\Model;

class ColaboradorModel extends Model
{
    protected $table = 'ccp_usuario'; // Tabla ccp_usuario
    protected $primaryKey = 'id';

    protected $allowedFields = ['persona_id', 'usuario', 'password', 'estado'];


    /**
     * Obtiene todos los usuarios activos con roles, correos, teléfonos y datos completos
     *
     * @return array Lista de usuarios activos
     */
    public function obtenerUsuariosActivos()
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('
            ccp_usuario.id,
            ccp_usuario.usuario,
            ccp_persona.nombres,
            ccp_persona.apellido_paterno,
            ccp_persona.apellido_materno,
            ccp_persona_identificacion.identificacion_descripcion,
            ccp_persona.direccion,
            GROUP_CONCAT(DISTINCT ccp_rol.nombre SEPARATOR ", ") AS roles,
            GROUP_CONCAT(DISTINCT ccp_email.email SEPARATOR ", ") AS emails,
            GROUP_CONCAT(DISTINCT ccp_telefono.numero SEPARATOR ", ") AS telefonos
        ')
        ->join('ccp_persona', 'ccp_usuario.persona_id = ccp_persona.id', 'inner')
        ->join('ccp_persona_identificacion', 'ccp_persona_identificacion.id_persona = ccp_persona.id', 'left')
        ->join('ccp_usuario_rol', 'ccp_usuario_rol.usuario_id = ccp_usuario.id', 'left')
        ->join('ccp_rol', 'ccp_usuario_rol.rol_id = ccp_rol.id', 'left')
        ->join('ccp_email', 'ccp_email.persona_id = ccp_persona.id', 'left')
        ->join('ccp_telefono', 'ccp_telefono.persona_id = ccp_persona.id', 'left')
        ->where('ccp_usuario.estado', 1) // Solo usuarios activos
        ->groupBy('ccp_usuario.id') // Agrupar por usuario
        ->orderBy('ccp_usuario.id', 'DESC'); // Ordenar por nombre

        return $builder->get()->getResultArray();
    }

    public function buscarUsuarios($search = '', $orderField = 'id', $orderType = 'ASC', $perPage = 10, $offset = 0)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $builder->select('
            ccp_usuario.id,
            ccp_usuario.usuario,
            ccp_persona.nombres,
            ccp_persona.apellido_paterno,
            ccp_persona.apellido_materno,
            ccp_persona_identificacion.identificacion_descripcion AS identificacion_descripcion,
            ccp_persona.direccion,
            GROUP_CONCAT(DISTINCT ccp_rol.nombre ORDER BY ccp_rol.nombre ASC SEPARATOR ", ") AS roles,
            GROUP_CONCAT(DISTINCT ccp_email.email ORDER BY ccp_email.email ASC SEPARATOR ", ") AS emails,
            GROUP_CONCAT(DISTINCT ccp_telefono.numero ORDER BY ccp_telefono.numero ASC SEPARATOR ", ") AS telefonos
        ')
        ->join('ccp_persona', 'ccp_usuario.persona_id = ccp_persona.id', 'inner')
        ->join('ccp_persona_identificacion', 'ccp_persona_identificacion.id_persona = ccp_persona.id', 'left')
        ->join('ccp_usuario_rol', 'ccp_usuario_rol.usuario_id = ccp_usuario.id', 'left')
        ->join('ccp_rol', 'ccp_usuario_rol.rol_id = ccp_rol.id', 'left')
        ->join('ccp_email', 'ccp_email.persona_id = ccp_persona.id', 'left')
        ->join('ccp_telefono', 'ccp_telefono.persona_id = ccp_persona.id', 'left')
        ->where('ccp_usuario.estado', 1);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('ccp_usuario.usuario', $search)
                ->orLike('ccp_persona.nombres', $search)
                ->orLike('ccp_persona.apellido_paterno', $search)
                ->orLike('ccp_persona.apellido_materno', $search)
                ->orLike('ccp_persona_identificacion.identificacion_descripcion', $search)
                ->orLike('ccp_persona.direccion', $search)
                ->orLike('ccp_email.email', $search) // Filtrar por correo
                ->orLike('ccp_telefono.numero', $search) // Filtrar por teléfono
                ->groupEnd();
        }

        $builder->groupBy('ccp_usuario.id')
            ->orderBy($orderField, $orderType)
            ->limit($perPage, $offset);

        return $builder->get()->getResultArray();
    }

    /**
     * Cuenta el número total de usuarios según los filtros
     */
    public function contarUsuarios($search = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);

        $builder->select('ccp_usuario.id')
            ->join('ccp_persona', 'ccp_usuario.persona_id = ccp_persona.id', 'inner')
            ->join('ccp_persona_identificacion', 'ccp_persona_identificacion.id_persona = ccp_persona.id', 'left')
            ->join('ccp_email', 'ccp_email.persona_id = ccp_persona.id', 'left') // Agregar la tabla de correos
            ->where('ccp_usuario.estado', 1);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('ccp_usuario.usuario', $search)
                ->orLike('ccp_persona.nombres', $search)
                ->orLike('ccp_persona.apellido_paterno', $search)
                ->orLike('ccp_persona.apellido_materno', $search)
                ->orLike('ccp_persona_identificacion.identificacion_descripcion', $search)
                ->orLike('ccp_persona.direccion', $search)
                ->orLike('ccp_email.email', $search) // Filtrar por correo
                ->groupEnd();
        }

        return $builder->countAllResults();
    }


    public function registrarColaborador($colaboradorData, $roles, $emails, $telefonos)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // Insertar en la tabla ccp_persona
        $personaData = [
            'nombres' => $colaboradorData['nombres'],
            'apellido_paterno' => $colaboradorData['apellido_paterno'],
            'apellido_materno' => $colaboradorData['apellido_materno'],
            'direccion' => $colaboradorData['direccion'],
            'estado' => 1
        ];
        $personaId = $db->table('ccp_persona')->insert($personaData);
        $personaId = $db->insertID();

        // Insertar en la tabla ccp_usuario
        $usuarioData = [
            'persona_id' => $personaId,
            'usuario' => $colaboradorData['usuario'],
            'password' => password_hash($colaboradorData['password'], PASSWORD_BCRYPT),
            'estado' => 1
        ];
        $this->insert($usuarioData);
        $usuarioId = $this->insertID();

        $identificacionData = [
            'id_persona' => $personaId,
            'id_tipo_identificacion' => $colaboradorData['id_tipo_identificacion'],
            'identificacion_descripcion' => $colaboradorData['identificacion_descripcion']
        ];

        $identificacionData = $db->table('ccp_persona_identificacion')->insert($identificacionData);

        // Insertar en la tabla ccp_usuario_rol
        $usuarioRolTable = $db->table('ccp_usuario_rol');
        foreach ($roles as $rolId) {
            $usuarioRolTable->insert(['usuario_id' => $usuarioId, 'rol_id' => $rolId]);
        }

        // Insertar correos electrónicos
        $emailTable = $db->table('ccp_email');
        foreach ($emails as $email) {
            if (!empty($email)) {
                $emailTable->insert(['persona_id' => $personaId, 'email' => $email, 'estado' => 1]);
            }
        }

        // Insertar teléfonos
        $telefonoTable = $db->table('ccp_telefono');
        foreach ($telefonos as $telefono) {
            if (!empty($telefono)) {
                $telefonoTable->insert(['persona_id' => $personaId, 'numero' => $telefono, 'estado' => 1]);
            }
        }

        $db->transComplete();

        return $db->transStatus() ? $usuarioId : false;
    }

    public function obtenerColaboradorPorId($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('ccp_usuario');
        $builder->select('
            ccp_usuario.id,
            ccp_usuario.usuario,
            ccp_usuario.estado,
            ccp_persona.id AS persona_id,
            ccp_persona.nombres,
            ccp_persona.apellido_paterno,
            ccp_persona.apellido_materno,
            ccp_persona.direccion,
            ccp_persona_identificacion.id_tipo_identificacion,
            ccp_persona_identificacion.identificacion_descripcion
        ');
        $builder->join('ccp_persona', 'ccp_usuario.persona_id = ccp_persona.id', 'inner');
        $builder->join('ccp_persona_identificacion', 'ccp_persona_identificacion.id_persona = ccp_persona.id', 'left');
        $builder->where('ccp_usuario.id', $id);
        $colaborador = $builder->get()->getRowArray();

        if (!$colaborador) {
            return null;
        }

        // Obtener correos electrónicos
        $emails = $db->table('ccp_email')
            ->select('email')
            ->where('persona_id', $colaborador['persona_id'])
            ->get()
            ->getResultArray();
        $colaborador['emails'] = array_map(fn($email) => $email['email'], $emails);

        // Obtener teléfonos
        $telefonos = $db->table('ccp_telefono')
            ->select('numero')
            ->where('persona_id', $colaborador['persona_id'])
            ->get()
            ->getResultArray();
        $colaborador['telefonos'] = array_map(fn($telefono) => $telefono['numero'], $telefonos);

        // Obtener roles correctamente
        $roles = $db->table('ccp_usuario_rol')
            ->select('rol_id')
            ->where('usuario_id', $colaborador['id'])
            ->get()
            ->getResultArray();
        $colaborador['roles'] = array_map(fn($rol) => $rol['rol_id'], $roles);

        return $colaborador;
    }


    public function actualizarColaborador($id, $colaboradorData, $roles, $emails, $telefonos)
{
    $db = \Config\Database::connect();
    $db->transStart();

    // Obtener el persona_id del usuario antes de actualizar
    $usuario = $this->find($id);
    if (!$usuario) {
        return false; // Si no existe, no se actualiza
    }
    $personaId = $usuario['persona_id'];

    // Actualizar datos en ccp_persona
    $db->table('ccp_persona')
        ->where('id', $personaId)
        ->update([
            'nombres' => $colaboradorData['nombres'],
            'apellido_paterno' => $colaboradorData['apellido_paterno'],
            'apellido_materno' => $colaboradorData['apellido_materno'],
            'direccion' => $colaboradorData['direccion'],
        ]);

    // Mantener los valores actuales de usuario y estado si no están en la actualización
    $usuarioData = [
        'usuario' => $colaboradorData['usuario'] ?? $usuario['usuario'], // Mantener si está vacío
        'estado' => isset($colaboradorData['estado']) ? $colaboradorData['estado'] : $usuario['estado']
    ];
    if (!empty($colaboradorData['password'])) {
        $usuarioData['password'] = $colaboradorData['password'];
    }
    $this->update($id, $usuarioData);

    // Verificar si ya existe un registro en ccp_persona_identificacion
$identificacionExistente = $db->table('ccp_persona_identificacion')
    ->where('id_persona', $personaId)
    ->get()
    ->getRowArray();

if ($identificacionExistente) {
    // Si existe, actualizar
    $db->table('ccp_persona_identificacion')
        ->where('id_persona', $personaId)
        ->update([
            'id_tipo_identificacion' => $colaboradorData['id_tipo_identificacion'],
            'identificacion_descripcion' => $colaboradorData['identificacion_descripcion']
        ]);
} else {
    // Si no existe, insertar
    $db->table('ccp_persona_identificacion')
        ->insert([
            'id_persona' => $personaId,
            'id_tipo_identificacion' => $colaboradorData['id_tipo_identificacion'],
            'identificacion_descripcion' => $colaboradorData['identificacion_descripcion']
        ]);
}


    // Actualizar roles: eliminar y volver a insertar
    $db->table('ccp_usuario_rol')->where('usuario_id', $id)->delete();
    foreach ($roles as $rolId) {
        $db->table('ccp_usuario_rol')->insert(['usuario_id' => $id, 'rol_id' => $rolId]);
    }

    // Actualizar correos: eliminar y volver a insertar
    $db->table('ccp_email')->where('persona_id', $personaId)->delete();
    foreach ($emails as $email) {
        $db->table('ccp_email')->insert(['persona_id' => $personaId, 'email' => $email, 'estado' => 1]);
    }

    // Actualizar teléfonos: eliminar y volver a insertar
    $db->table('ccp_telefono')->where('persona_id', $personaId)->delete();
    foreach ($telefonos as $telefono) {
        $db->table('ccp_telefono')->insert(['persona_id' => $personaId, 'numero' => $telefono, 'estado' => 1]);
    }

    $db->transComplete();

    return $db->transStatus();
}




}
