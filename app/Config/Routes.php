<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
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
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/admin', 'Admin::index');
$routes->get('/admin/(:any)', 'Admin::index/$1');
$routes->post('/admin/(:any)', 'Admin::index/$1');
$routes->post('/admin/(:any)/(:any)', 'Admin::index/$1/$2');
$routes->get('/admin/(:any)/(:any)', 'Admin::index/$1/$2');


$routes->get('/tools', 'Thesa::index/tools');
$routes->get('/tools/(:any)', 'Thesa::index/tools/$1');
//$routes->post('/tools/(:any)', 'Thesa::index/$1');
//$routes->post('/tools/(:any)/(:any)', 'Thesa::index/$1/$2');
//$routes->get('/tools/(:any)/(:any)', 'Thesa::index/tools/$1/$2');

$routes->get('/myth', 'Thesa::index/myth');

$routes->get('/thopen', 'Thesa::index/thopen');
$routes->get('/th/(:any)', 'Thesa::index/th/$1');
$routes->get('/tz/(:any)', 'Thesa::index/tz/$1');
$routes->get('/t/(:any)', 'Thesa::index/t/$1');
$routes->get('/ts/(:any)', 'Thesa::index/ts/$1');
$routes->get('/v/(:any)', 'Thesa::index/v/$1');
$routes->get('/a/(:any)', 'Thesa::index/a/$1');

$routes->get('/social/', 'Thesa::social');
$routes->get('/social/(:any)', 'Thesa::social/$1');
$routes->post('/social/', 'Thesa::social/$1');
$routes->post('/social/(:any)', 'Thesa::social/$1');

$routes->get('/export/(:any)', 'Export::index/$1');

$routes->get('/', 'Thesa::index');

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
