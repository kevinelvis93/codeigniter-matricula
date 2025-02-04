<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verificar si hay una sesión activa
        if (!$session->get('isLoggedIn')) {
            // Si no está autenticado, redirigir al login
            return redirect()->to('/login');
        }

        // Obtener roles y permisos desde la sesión
        $roles = $session->get('roles') ?? [];

        // Validar si hay roles en la sesión
        if (empty($roles)) {
            // Si no hay roles, redirigir a no-permission
            return redirect()->to('/no-permission');
        }

        // Obtener la ruta actual
        $currentRoute = service('router')->getMatchedRouteOptions()['as'] ?? service('router')->getMatchedRoute()[0];

        if (!$currentRoute) {
            // Si no se puede determinar la ruta actual, redirigir a no-permission
            return redirect()->to('/no-permission');
        }

        $menuModel = new \App\Models\MenuModel();

        // Comprobar si el usuario tiene permiso para la ruta actual o sus subrutas
        $hasPermission = $menuModel->verificarPermisoRuta($roles, $currentRoute);

        // Permitir rutas internas si tiene permiso para la raíz
        if (!$hasPermission) {
            $baseRoute = strtok($currentRoute, '/'); // Obtiene la primera parte del segmento
            $hasBasePermission = $menuModel->verificarPermisoRuta($roles, $baseRoute);

            if (!$hasBasePermission) {
                // Si no tiene permiso, redirigir a no-permission
                return redirect()->to('/no-permission');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere lógica después del procesamiento
    }
}
