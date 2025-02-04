<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'ccp_menu';

    /**
     * Obtiene los menús activos según los roles del usuario
     * y organiza submenús bajo sus menús principales.
     *
     * @param array $roles Roles del usuario
     * @return array Menús organizados con submenús
     */
    public function obtenerMenuPorRoles($roles = [])
    {
        // Asegúrate de que los roles sean un array válido
        if (!is_array($roles) || empty($roles)) {
            return []; // Si no hay roles, retorna un menú vacío
        }

        $db = \Config\Database::connect();

        $builder = $db->table($this->table);
        $builder->select('ccp_menu.id, ccp_menu.nombre, ccp_menu.ruta, ccp_menu.padre_id, ccp_menu.icono, ccp_menu.orden, ccp_menu.estado')
                ->join('ccp_rol_permiso', 'ccp_menu.id = ccp_rol_permiso.menu_id', 'inner')
                ->join('ccp_usuario_rol', 'ccp_rol_permiso.rol_id = ccp_usuario_rol.rol_id', 'inner')
                ->where('ccp_menu.estado', 1)
                ->whereIn('ccp_usuario_rol.rol_id', $roles) // Filtrar por los roles del usuario
                ->groupBy('ccp_menu.id') // Evitar duplicados
                ->orderBy('ccp_menu.orden', 'ASC');

        $result = $builder->get()->getResultArray();

        // Separar menús principales y submenús
        $menuPrincipal = [];
        $submenus = [];

        foreach ($result as $row) {
            if ($row['padre_id'] == 0) {
                // Menús principales
                $menuPrincipal[$row['id']] = $row;
                $menuPrincipal[$row['id']]['submenus'] = []; // Inicializar submenús
            } else {
                // Submenús
                $submenus[] = $row;
            }
        }

        // Asignar submenús a sus menús principales
        foreach ($submenus as $submenu) {
            if (isset($menuPrincipal[$submenu['padre_id']])) {
                $menuPrincipal[$submenu['padre_id']]['submenus'][] = $submenu;
            }
        }

        return $menuPrincipal;
    }


    public function verificarPermisoRuta($roles, $route)
    {
        $db = \Config\Database::connect();

        // Normalizar la ruta para obtener la ruta base
        $normalizedRoute = ltrim($route, '/'); // Eliminar el slash inicial
        $baseRoute = '/' . strtok($normalizedRoute, '/'); // Ruta base (primer segmento)

        // Construir la consulta
        $builder = $db->table($this->table);
        $builder->select('ccp_menu.ruta')
            ->join('ccp_rol_permiso', 'ccp_menu.id = ccp_rol_permiso.menu_id', 'inner')
            ->whereIn('ccp_rol_permiso.rol_id', $roles)
            ->groupStart()
                ->where('ccp_menu.ruta', '/' . $normalizedRoute) // Coincidencia exacta
                ->orWhere('ccp_menu.ruta', $baseRoute) // Coincidencia con la ruta base
            ->groupEnd()
            ->where('ccp_menu.estado', 1); // Validar solo rutas activas

        $result = $builder->get()->getRow();

        return $result !== null; // Retorna true si existe un registro, de lo contrario false
    }





}
