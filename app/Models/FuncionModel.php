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

    public function obtenerDepartamentos()
    {
        $db = \Config\Database::connect();

        return $db->table('ccp_ubigeo_peru_departments')
            ->select('id AS codigo_departamento, name AS nombre_departamento')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function obtenerProvincias($codigo_departamento)
    {
        $db = \Config\Database::connect();

        return $db->table('ccp_ubigeo_peru_provinces')
            ->select('id AS codigo_provincia, name AS nombre_provincia')
            ->where('department_id', $codigo_departamento)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function obtenerDistritos($codigo_departamento, $codigo_provincia)
    {
        $db = \Config\Database::connect();

        return $db->table('ccp_ubigeo_peru_districts')
            ->select('id AS codigo_distrito, name AS nombre_distrito')
            ->where('department_id', $codigo_departamento)
            ->where('province_id', $codigo_provincia)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

}

