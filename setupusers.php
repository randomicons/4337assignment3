<?php //setupusers.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  $query = "CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(32) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL
  )";
  $result = $connection->query($query);
  if (!$result) die($connection->error);

  $salt1    = "qm&h*";
  $salt2    = "pg!@";

  $username = 'user1';
  $password = 'user1';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($username, $token);

  $username = 'user2';
  $password = 'user2';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($username, $token);

  $username = 'user3';
  $password = 'user3';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($username, $token);


  $username = 'admin1';
  $password = 'admin1';
  $token    = hash('ripemd128', "$salt1$password$salt2");

  add_user($username, $token);

  function add_user($un, $pw)
  {
    global $connection;

    $query  = "INSERT INTO users VALUES('$un', '$pw')
                 ON DUPLICATE KEY UPDATE username=username;";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
  }
?>
