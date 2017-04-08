<?php // authenticate.php
  require_once 'login.php';
  $connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

  if ($connection->connect_error) die($connection->connect_error);

  if (isset($_SERVER['PHP_AUTH_USER']) &&
      isset($_SERVER['PHP_AUTH_PW']))
  {
    $un_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_USER']);
    $pw_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_PW']);

    $query  = "SELECT * FROM users WHERE username='$un_temp'";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    elseif ($result->num_rows)
    {
        $col = $result->fetch_array(MYSQLI_NUM);

		$result->close();

        $salt1 = "qm&h*";
        $salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$pw_temp$salt2");

        if ($token == $col[1]) {
        	if (preg_match('/^admin/',$un_temp))
        		 header( 'Location: admin.php' ) ;
        	else
        		header( 'Location: users_books.php' );

        	echo <<<_END
  			$col[0], you are logged in.
			<form action="authenticate.php" method="post">
  			<input type="hidden" name="logoff" value="yes">
  			<input type="submit" value="Log Off"></form>
_END;
        }
        else {
        	echo "Invalid username/password combination";
        	resetlogin();   
        }
    }
    else {
    	echo "Invalid username/password combination";
    	resetlogin();
    }
  }
  else
  {
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Please enter your username and password";
  }

  if(isset($_POST["logoff"])) {
  	if(!isset($_SESSION['indicator'])) {
  		echo 'logging off';
  		 $_SESSION['indicator'] = "processed"; 
  		resetlogin();
 	 }
 	 unset($_SESSION['indicator']);
  } 

  $connection->close();
  
  function resetlogin()
  {
  	unset($_SERVER['PHP_AUTH_USER']);
    unset($_SERVER['PHP_AUTH_PW']);
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized'); 
  }

  function mysql_entities_fix_string($connection, $string)
  {
    return htmlentities(mysql_fix_string($connection, $string));
  }	

  function mysql_fix_string($connection, $string)
  {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connection->real_escape_string($string);
  }
?>