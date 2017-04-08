<?php //updatebooks.php
	require_once 'login.php';

	$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
  	if ($connection->connect_error) die($connection->connect_error);	
	
	if(isset($_POST['UPDATE']))
	{
		$isbn = get_post($connection, 'UPDATE');
		$author   = get_post($connection, 'author');
	    $title    = get_post($connection, 'title');
	    $category = get_post($connection, 'category');
	    $year     = get_post($connection, 'year');
		$query = "UPDATE classics
				 	SET author='$author',
				 		title='$title',
				 		category='$category',
				 		year='$year'
				 	WHERE isbn='$isbn'";
		$result   = $connection->query($query);

		if (!$result) echo "INSERT failed: $query<br>" .
      	$connection->error . "<br><br>";
	}

	if(!isset($_POST['isbn']))
	{
		header("Location: users_books.php");
	}
	
	$isbn = get_post($connection, 'isbn');
	echo "<script type='text/javascript'>alert(lakj);</script>";
	
	

	echo <<< _END
	<form action="updatebooks.php" method="post">
	<pre>
	    ISBN $isbn 
	  Author <input type="text" name="author">
	   Title <input type="text" name="title">
  	Category <input type="text" name="category">
	    Year <input type="text" name="year">
	    <input type="hidden" name="UPDATE" value="$isbn">
    	<input type="submit" value="UPDATE RECORD">
  	</pre>
  	</form>
  	<form action="users_books.php" method="post">
 	 <input type="hidden" name="CANCEL" value="yes">
           <input type="submit" value="CANCEL">
  </form>
_END;

 function get_post($connection, $var)
 {
    return $connection->real_escape_string($_POST[$var]);
 }

?>