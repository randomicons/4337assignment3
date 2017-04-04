<?php // admin.php
  require_once 'login.php';
  require_once 'setupusers.php';
  if (!isset($_SERVER['PHP_AUTH_USER']) &&
      !isset($_SERVER['PHP_AUTH_PW'])) {
     header( 'Location: authenticate.php' ) ;
  }
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
  
	
  if ($connection->connect_error) die($connection->connect_error);

  $result = $connection->query($query);

  if (isset($_POST['delete']) && isset($_POST['username']))
  {
    $username   = get_post($connection, 'username');
    $query  = "DELETE FROM users WHERE username='$username'";
    $result = $connection->query($query);

  	if (!$result) echo "DELETE failed: $query<br>" .
      $connection->error . "<br><br>";
  }

  if (isset($_POST['username'])   &&
      isset($_POST['password']))
  {
    $username   = get_post($connection, 'username');
    $password    = get_post($connection, 'password');
    
    $salt1    = "qm&h*";
    $salt2    = "pg!@";

    $token    = hash('ripemd128', "$salt1$password$salt2");
    add_user($username, $token);
  }

  echo <<<_END
  <form action="admin.php" method="post"><pre>
    username <input type="text" name="username">
    password <input type="text" name="password">
    <input type="submit" value="ADD RECORD">
  </pre></form>
_END;

  $query  = "SELECT * FROM users";
  $result = $connection->query($query);

  if (!$result) die ("Database access failed: " . $connection->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <pre>
    username $row[0]
    password $row[1]
  </pre>
  <form action="admin.php" method="post">
  <input type="hidden" name="delete" value="yes">
  <input type="hidden" name="username" value="$row[0]">
  <input type="submit" value="DELETE RECORD"></form>
_END;
  }
  
  $result->close();
  $connection->close();
  
  function get_post($connection, $var)
  {
    return $connection->real_escape_string($_POST[$var]);
  }
?>
