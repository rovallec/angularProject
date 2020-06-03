<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
define('db_host', '172.18.2.45');
define('db_user','neadmin');
define('db_password','Nearsol123');
define('db_name','minearsol');

function connect()
{
	$connect = mysqli_connect(db_host,db_user,db_password,db_name);
	if(mysqli_connect_errno($connect))
	{
		die("Failed to connect: " . mysqli_connect_errno());
	}

	return $connect;
}

$con = connect();
?>