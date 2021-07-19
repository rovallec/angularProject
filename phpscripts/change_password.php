<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata))
	{
		$request = json_decode($postdata);
		
        $user = ($request->username);
		$password = ($request->password);
		
		//$sql = "ALTER USER '$user'@’localhost’ IDENTIFIED BY ‘$password’;";
		$sql1 = 'USE mysql;'
        $sql = "UPDATE user SET authentication_string = PASSWORD('N$@dmin.2002!') WHERE user = 'rovalle' AND host='%'";
        //$sql = "UPDATE mysql.user SET Password=PASSWORD('$password') WHERE USER='$user' AND Host=”localhost”;";

		if(mysqli_query($con, $sql1))
		{
			if(mysqli_query($con, $sql))
			{		
				$sql2 = "UPDATE users SET active = 1 where username = $user";	
				if (mysqli_query($con, $sql2)) {
					echo(json_encode('changed'));
				} else{
					http_response_code(423);
					echo mysqli_error($con);
					echo("<br>");
					echo $sql;
				}
			}else{
				http_response_code(422);
				echo mysqli_error($con);
				echo("<br>");
				echo $sql;
				
			}
		}
	}
?>