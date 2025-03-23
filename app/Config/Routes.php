<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// for product
$routes->post('products', 'ProductController::create');
$routes->get('products', 'ProductController::index');
$routes->post('products/updateProduct/(:num)', 'ProductController::updateProduct/$1');
$routes->post('products/addToCart', 'ProductController::addToCart');
$routes->post('products/remove/(:num)', 'ProductController::remove/$1');
$routes->get('cart', 'ProductController::cart');
$routes->get('product', 'CartController::product');


// for cart
// $routes->post('add-to-cart', 'CartController::addToCart');
$routes->get('cart/list', 'CartController::list');
$routes->post('cart/remove/(:num)', 'CartController::remove/$1');
$routes->post('cart/updateQuantity/(:num)', 'CartController::updateQuantity/$1');
