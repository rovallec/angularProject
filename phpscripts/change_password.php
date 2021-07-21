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
		
		$sql = "ALTER USER '$user'@'localhost' IDENTIFIED BY '$password';";

		if(mysqli_query($con, $sql))
		{		
            $sql2 = "UPDATE users SET active = 1 where username = $user;";	
            if (mysqli_query($con, $sql2)) {
                echo(json_encode('changed'));
            } else{
                http_response_code(423);
                echo mysqli_error($con);
                echo("<br>");
                echo $sql2;
            }
		}else{
			http_response_code(422);
            echo mysqli_error($con);
            echo("<br>");
            echo $sql;
            
		}
	}
?>