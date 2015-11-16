<?php

function insert_theme_data($data) {

  // @TODO: Get rid of this and update the lambda db class to look for the env file automagically
  $database_connection_settings = array(
    'dbms'     => 'mysql',
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'database' => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST')
  );

  $conn = Lambda\Database\Connector::connect($database_connection_settings);
  $db   = Lambda\Database\Connector::getInstance();

  $sql  = "INSERT INTO theme_data ( uid, theme_name, theme_version, theme_author, site_name, site_url, ip_address, wordpress_version )
           VALUES ( ?, ?, ?, ?, ?, ?, ?, ? )"; //Build the SQL.

  $stmt = $db->prepare($sql); //Prep the SQL.

  $stmt->bind_param('ssssssss', $data['uid'], $data['theme_name'], $data['theme_version'], $data['theme_author'], $data['site_name'], $data['site_url'], $data['ip_address'], $data['wordpress_version']); //Bind Params

  $result = $stmt->execute(); //Execute!

  if ($result) { // Ok
    return true;
  } else {
    $response->withStatus(400); // Bad request
    $response->withHeader('Content-type', 'application/json'); // Override existing header with new header.
    $response->write(json_encode(array(
      'error' => true,
      'message' => 'Failed to insert data.'
    )));
    return $response;
  }
}

function filter_date_time($var) {

}

function get_theme_data() {

  // @TODO: Get rid of this and update the lambda db class to look for the env file automagically
  $database_connection_settings = array(
    'dbms'     => 'mysql',
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'database' => getenv('DB_NAME'),
    'host'     => getenv('DB_HOST')
  );

  $conn = Lambda\Database\Connector::connect($database_connection_settings);
  $db   = Lambda\Database\Connector::getInstance();

  $sql  = "SELECT * FROM
            (SELECT * FROM theme_data ORDER BY id DESC)
          AS theme_data_temp
          GROUP BY uid";

  $result = $db->query($sql);
  $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //$result = array_filter($result, '');

  //var_dump($result);

  return $result;
}
