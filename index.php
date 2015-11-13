<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'API_KEY'])->notEmpty();

$app = new Slim\App();

// Global Middleware - API key header checker
$app->add(function ($request, $response, $next) {

  $headers       = apache_request_headers(); // Need to use apache_request_headers to get the Authorization header.
  $authorization = $headers['Authorization'];

  if ($authorization && $authorization === md5(getenv('API_KEY'))) { //Check header API key against env file version.
    $response = $next($request, $response);
  } else {
    $response = (new Response())
      ->withStatus(400) //Bad request
      ->withHeader('Content-type', 'application/json') // Override existing header with new header.
      ->write(json_encode(array(
        'error' => true,
        'message' => 'API key missing or invalid.'
      ))
    );
  }

  return $response;
});

$app->post('/api/theme', function ($request, $response, $args) {

    $response->write(json_encode(array(
      'error' => false,
      'message' => 'OK'
    )));

    //Override existing header with new header.
    $response = $response->withHeader('Content-type', 'application/json');

    return $response;
});

$app->run();
