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
$routes->setDefaultController('Thesa');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Thesa::index');
$routes->get('/rest/v1/search/(:any)/', 'Thesa::search/$1');
$routes->get('/rest/v1/search/', 'Thesa::help/api/search');
$routes->get('/rest/v1/data', 'Thesa::help/api/data');
$routes->get('/rest/v1/', 'Thesa::help/api/');
$routes->get('/rest/', 'Thesa::help/api/');
$routes->get('/rest/v1/data/(:any)', 'Thesa::data/$1');
$routes->get('/api/', 'Thesa::api');

$multipleRoutes = [
    'api/(:any)/(:any)'      => 'Thesa::api/$1/$2',
    'api/(:alphanum)' => 'Thesa::api',
];

$routes->map($multipleRoutes);

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
