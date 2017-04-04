<?php //fetchrowusers.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  $query  = "SELECT * FROM users";
  $result = $connection->query($query);

  if (!$result) die($connection->error);

  $rows = $result->num_rows;

  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
	 $row = $result->fetch_array(MYSQLI_ASSOC);

    echo 'Username: '   . $row['username']   . '<br>';
    echo 'Password: '    . $row['password']    . '<br>';
  }

  $result->close();
  $connection->close();
?>
