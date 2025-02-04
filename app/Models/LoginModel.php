<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'ccp_usuario'; // Tabla principal de usuarios
    protected $primaryKey = 'id';

    /**
     * Busca un usuario por su nombre de usuario, correo o DNI, e incluye los roles asociados.
     *
     * @param string $usuario Usuario, correo o DNI
     * @return object|null Retorna el usuario con roles como array si existe, de lo contrario null
     */
    public function buscarUsuario($usuario)
    {
        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('ccp_usuario.id, ccp_usuario.password, ccp_persona.nombres, ccp_persona.apellido_paterno')
                ->join('ccp_persona', 'ccp_usuario.persona_id = ccp_persona.id')
                ->join('ccp_email', 'ccp_email.persona_id = ccp_persona.id', 'left')
                ->join('ccp_persona_identificacion', 'ccp_persona_identificacion.id_persona = ccp_persona.id', 'left')
                ->groupStart()
                    ->where('ccp_usuario.usuario', $usuario)
                    ->orWhere('ccp_email.email', $usuario)
                    ->orWhere('ccp_persona_identificacion.identificacion_descripcion', $usuario)
                ->groupEnd()
                ->where('ccp_usuario.estado', 1);

        $user = $builder->get()->getRow(); // Obtener los datos básicos del usuario

        
        if ($user) {
            // Consultar los roles asociados al usuario
            $rolesBuilder = $db->table('ccp_usuario_rol');
            $rolesBuilder->select('ccp_rol.id AS rol_id, ccp_rol.nombre AS rol_nombre')
                         ->join('ccp_rol', 'ccp_usuario_rol.rol_id = ccp_rol.id', 'inner')
                         ->where('ccp_usuario_rol.usuario_id', $user->id);

            $roles = $rolesBuilder->get()->getResultArray();

            // Transformar los roles en un array simple de IDs o nombres
            $user->roles = array_map(fn($rol) => $rol['rol_id'], $roles); // O usar 'rol_nombre' si prefieres los nombres
        }

        return $user;
    }
}
