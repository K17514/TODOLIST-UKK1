<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home/selesai/(:num)', 'Home::selesai/$1');
$routes->get('home/input/', 'Home::inputview');
$routes->post('home/input/', 'Home::input');
$routes->get('home/edit/(:num)', 'Home::editview/$1');
$routes->post('home/simpan/', 'Home::simpan');
$routes->get('home/delete/(:num)', 'Home::hapus/$1');