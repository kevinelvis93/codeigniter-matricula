<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('LoginController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
$routes->get('no-permission', 'ErrorController::noPermission');


/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'LoginController::index');
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::autenticar');
$routes->get('/logout', 'LoginController::logout');
$routes->get('/plantilla', 'PlantillaController::index');
$routes->get('/colaborador', 'ColaboradorController::index', ['filter' => 'permission']);
$routes->get('/colaborador/registrar', 'ColaboradorController::registrar', ['filter' => 'permission']);
$routes->post('/colaborador/registrar', 'ColaboradorController::registrarPost');
$routes->get('ubigeo/departamentos', 'UbigeoController::obtenerDepartamentos');
$routes->get('ubigeo/provincias/(:num)', 'UbigeoController::obtenerProvincias/$1');
$routes->get('ubigeo/distritos/(:num)/(:num)', 'UbigeoController::obtenerDistritos/$1/$2');


$routes->get('colaborador/editar/(:num)', 'ColaboradorController::editar/$1', ['filter' => 'permission']);
$routes->post('colaborador/actualizar/(:num)', 'ColaboradorController::actualizar/$1');
$routes->post('colaborador/validar-clave', 'ColaboradorController::validarClave');
$routes->delete('colaborador/eliminar/(:num)', 'ColaboradorController::eliminar/$1');




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'; 
}
