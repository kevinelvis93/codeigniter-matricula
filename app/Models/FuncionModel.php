<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionModel extends Model
{
    // protected $table = 'ccp_rol'; // Tabla asociada

    /**
     * Obtener todos los roles activos ordenados por nombre.
     *
     * @return array Lista de roles
     */
    public function obtenerRoles()
    {
        $db = \Config\Database::connect();

        return $db->table('ccp_rol')
            ->select('id, nombre')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function obtenerTipoIdentificacion()
    {
        $db = \Config\Database::connect();

        return $db->table('ccp_tipo_identificacion')
            ->select('id_tipo_identificacion, identificacion_nombre')
            ->orderBy('id_tipo_identificacion', 'ASC')
            ->get()
            ->getResultArray();
    }
}

