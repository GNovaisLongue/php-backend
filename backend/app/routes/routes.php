<?php

use \App\Http\Response;
use \App\Controllers;

// Routes
//main.html >>> whenever starts local php server through index.php
$obRouter->get('/index.php', [
  function() {
    readfile(__DIR__.'/../views/index.php');
  }
]);
//main.html
$obRouter->get('/', [
  function() {
    readfile(__DIR__.'/../views/index.php');
    // return new Response(200, Controllers\ProductController::index());
  }
]);

//
//// PRODUCTS
$obRouter->get('/products', [
  function() {
    return new Response(200, Controllers\ProductController::index());
  }
]);
$obRouter->get('/product', [
  function() {
    return new Response(200, Controllers\ProductController::view('SKUTestSKU001'));
  }
]);

//
//// USERS
$obRouter->get('/users', [
  function() {
    return new Response(200, Controllers\UserController::index());
  }
]);
$obRouter->get('/user', [
  function() {
    return new Response(200, Controllers\UserController::view(1));
  }
]);

//
//Dynamic Route
$obRouter->get('/about/{idPage}', [
  function($idPage) {
    return new Response(200, 'Page '.$idPage);
  }
]);

/*


//about.html
$obRouter->get('/about', [
  function() {
    return new Response(200, Pages\About::getAbout());
  }
]);

//testimony.html
$obRouter->get('/testimony', [
  function() {
    return new Response(200, Pages\Product::getProducts());
  }
]);
$obRouter->post('/testimony', [
  function($request) {
    return new Response(200, Pages\Product::insertProduct($request));
  }
]);



*/