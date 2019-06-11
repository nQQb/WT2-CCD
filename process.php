<?php
	//Get values passed from form in login.php file
	$username = $_POST["username"];
    $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    //to prevent mysql injection
    //$username = stripcslashes($username);
    //$hash = stripcslashes($hash);
    //$username = mysqli_real_escape_string($db,$username);
    //$hash = mysqli_real_escape_string($db,$hash);

    //connect to the server and select database

   	$db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    if(mysqli_connect_errno() == 0)
    {
       	//query the database for user
        $result = mysqli_query($db,"select * from user where username = '$username' and pwd = '$hash'")
        or die("Failed to query database".mysqli_error($db));
    }
    if($row['username'] == $username && $row['pwd'] == $hash)
    {
    	echo "Login success! Welcome ".$row['username'];
    }else{
    	echo "Failed to login!";
    }

?>