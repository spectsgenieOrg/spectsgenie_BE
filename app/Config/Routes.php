<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'Home::login');
$routes->get('/logout', 'Admin::logout');
$routes->get('/products/edit/(:num)', 'Products::edit/$1');
$routes->post('/products/update/(:num)', 'Products::update/$1');
$routes->get('/products/(:num)/(:num)', 'Products::getProductByCategory/$1/$2'); // $1 = category, $2 = gender
$routes->get('/product/(:alphanum)/(:any)', 'Products::getProductByParentAndSlug/$1/$2'); // $1=parent name, $2=slug

$routes->group('wishlist', static function ($routes) {
    $routes->post('add', 'Wishlist::add');
    $routes->get('user', 'Wishlist::user');
    $routes->get('product/(:num)', 'Wishlist::product/$1');
    $routes->delete('(:num)', 'Wishlist::delete/$1');
});

$routes->group('pages', static function ($routes) {
    $routes->get('(:alpha)', 'Pages::getpage/$1');
    $routes->get('content/(:alpha)', 'Pages::content/$1');
});

//$routes->post('/lenstype/update/(:num)', 'Products::update/$1');