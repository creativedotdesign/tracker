<?php

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
