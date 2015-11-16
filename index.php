<?php

require 'vendor/autoload.php';
include 'middleware.php';
include 'functions.php';

// Create container
$container = new \Slim\Container;

// Register component on container
$container['view'] = function ($c) {
  $view = new \Slim\Views\Twig('templates', [
    'cache' => false
  ]);

  $view->addExtension(new \Slim\Views\TwigExtension(
    $c['router'],
    $c['request']->getUri()
  ));

  return $view;
};

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'API_KEY'])->notEmpty();

$app = new \Slim\App($container);

$app->post('/api/theme', function ($request, $response, $args) {
  $result = insert_theme_data($request->getParsedBody());

  $response->write(json_encode(array(
    'error' => false,
    'message' => 'OK'
  )));

  //Override existing header with new header.
  $response = $response->withHeader('Content-type', 'application/json');

  return $response;
})->add($valid)->add($auth); // Middleware

// Render Twig template in route
$app->get('/theme-data', function ($request, $response, $args) {
  $data = get_theme_data();

  return $this->view->render($response, 'theme-data.html', [
    'data' => $data
  ]);
})->setName('theme-data'); // Do I need setName?

$app->run();
