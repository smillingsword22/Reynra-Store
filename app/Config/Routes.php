<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('login', 'Login::doLogin');
$routes->get('logout', 'Login::logout');

$routes->get('/dashboard', 'Home::index');

// Master Menu
$routes->group('master', function($routes) {
    $routes->get('produk', 'Master\Produk::index');
    $routes->get('produk/create', 'Master\Produk::create');
    $routes->post('produk/store', 'Master\Produk::store');
    $routes->get('produk/edit/(:num)', 'Master\Produk::edit/$1');
    $routes->post('produk/update/(:num)', 'Master\Produk::update/$1');
    $routes->post('produk/delete/(:num)', 'Master\Produk::delete/$1');
});


// Transaksi Menu

$routes->group('transaksi', function($routes) {
    $routes->get('penjualan', 'Transaksi\Penjualan::index');
    $routes->get('penjualan/detail/(:num)', 'Transaksi\Penjualan::detail/$1');
    $routes->post('penjualan/store', 'Transaksi\Penjualan::create');
    $routes->get('penjualan/delete/(:num)', 'Transaksi\Penjualan::delete/$1');

    $routes->get('pengeluaran', 'Transaksi\Pengeluaran::index');
    $routes->post('pengeluaran/store', 'Transaksi\Pengeluaran::store');
});


