<?php

session_start();
if (!isset($_SESSION["username"]) && isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"])  && isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["email"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];

    if ($password == $password2) {

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
            if (mysqli_connect_errno() == 0) {

                $sql = "INSERT INTO `user` (`username`, `firstname`, `lastname`, `email`, `pwd`) VALUES (?,?,?,?,?)";
                $entry = $db->prepare($sql);
                $entry->bind_param('sssss', $username, $firstname, $lastname, $email, $hash);
                $rc = $entry->execute();
                if ($rc) {
                    header("Location: ../index.php?site=login");
                } else {
                    header("Location: ../index.php?site=registration&msg=" . urlencode("Dieser Benutzer exisitiert bereits!"));
                }
            } else {
                header("Location: ../index.php?site=registration&msg=" . urlencode(mysqli_connect_error()));
            }
            $db->close();
        } else {
            header("Location: ../index.php?site=registration&msg=" . urlencode("E-mail adress is invalid."));
        }
    } else {
        header("Location: ../index.php?site=registration&msg=" . urlencode("Passwörter stimmen nicht überein"));
    }
}
?>