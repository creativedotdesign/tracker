<?php

require 'vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'API_KEY'])->notEmpty();

$app = new Slim\App();

// API Key Middleware - API key header checker
$auth = function ($request, $response, $next) {
  $headers       = apache_request_headers(); // Need to use apache_request_headers to get the Authorization header.
  $authorization = $headers['Authorization'];

  if ($authorization && $authorization === md5(getenv('API_KEY'))) { //Check header API key against env file version.
    $response = $next($request, $response);
  } else {
    $response = (new Response())
      ->withStatus(401) //Authentication failure
      ->withHeader('Content-type', 'application/json') // Override existing header with new header.
      ->write(json_encode(array(
        'error' => true,
        'message' => 'API key missing or invalid.'
      )));
  }

  return $response;
};

// Valid Request Middleware - Check valid JSON in request body
$valid = function ($request, $response, $next) {

  $data         = $request->getBody()->getContents();
  $content_type = $request->getContentType();

  if ($content_type !== 'application/json' || json_decode($data) === null) {
    $response->withStatus(400); // Bad request
    $response->withHeader('Content-type', 'application/json'); // Override existing header with new header.
    $response->write(json_encode(array(
      'error' => true,
      'message' => 'Invalid JSON data recevied.'
    )));
  } else {
    $response = $next($request, $response);
  }

  return $response;
};

$app->post('/api/theme', function ($request, $response, $args) {

  $response->write(json_encode(array(
    'error' => false,
    'message' => 'OK'
  )));

  //Override existing header with new header.
  $response = $response->withHeader('Content-type', 'application/json');

  return $response;
})->add($valid)->add($auth); // Middleware

$app->run();
