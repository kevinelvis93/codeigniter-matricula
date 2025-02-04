<?php

use Config\Database;

/**
 * Helper para obtener roles activos.
 *
 * @return array Lista de roles
 */
function obtenerRoles()
{
    $db = Database::connect();
    $roles = $db->table('ccp_rol')
                ->orderBy('nombre', 'ASC')
                ->get()
                ->getResultArray();

    return $roles;
}
