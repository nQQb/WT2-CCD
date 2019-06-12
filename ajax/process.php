<?php session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    //Get values passed from form in login.php file
    $username = $_POST["username"];
    $password = $_POST["password"];
    //$hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    //to prevent mysql injection
    //$username = stripcslashes($username);
    //$hash = stripcslashes($hash);
    //$username = mysqli_real_escape_string($db,$username);
    //$hash = mysqli_real_escape_string($db,$hash);
    //connect to the server and select database

    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    if (mysqli_connect_errno() == 0) {
        //query the database for user
        $db = mysqli_connect("localhost", "root", "", "abschlussprojekt");
        $sql = "select pwd from user where username = ?";
        $entry = $db->prepare($sql);
        $entry->bind_param("s", $username);
        $entry->execute();
        $entry->bind_result($pwd);
        if ($entry->fetch()) {
            if (password_verify($password, $pwd)) {
                $_SESSION["username"] = $username;

                if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
                    setcookie("username", $username, time() + (60 * 60 * 24), "/");
                }
                header("Location: ../index.php");
                echo"Login success!";
            } else {
                echo"Failed to login!";
            }
        }
        $entry->close();
    }
}
?>