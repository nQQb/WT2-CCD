<?php

session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    //Get values passed from form in login.php file
    $username = $_POST["username"];
    $password = $_POST["password"];

    //Connecting to database
    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    if (mysqli_connect_errno() == 0) {
    
        $db = mysqli_connect("localhost", "root", "", "abschlussprojekt");

        $sql = "select pwd, isActive from user where username = ?";
        $entry = $db->prepare($sql);
        $entry->bind_param("s", $username);
        $entry->execute();
        $entry->bind_result($pwd, $isActive);

        $sql = "select pwd from user where username = ?";
    //Preparing and executing the sql statement
        $entry = $db->prepare($sql);
        $entry->bind_param("s", $username);
        $entry->execute();
        $entry->bind_result($pwd);
    //Fetching the result
        if ($entry->fetch()) {
            if ($isActive == 0) {
                header("Location: ../index.php?site=login&msg=" . urlencode("Benutzer inaktiv"));
            } else {
                if (password_verify($password, $pwd)) {
                    $_SESSION["username"] = $username;

                    if (isset($_POST["remember"]) && $_POST["remember"] == "on") {
                        setcookie("username", $username, time() + (60 * 60 * 24), "/");
                    }
                    header("Location: ../index.php");
                } else {
                    header("Location: ../index.php?site=login&msg=" . urlencode("Ungültiger Login!"));
                }
            }
        } else {
            header("Location: ../index.php?site=login&msg=" . urlencode("Ungültiger Login!"));
        }
        $entry->close();
    }
}
?>