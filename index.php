<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
        <!--- VIEWPORT LINE -->
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <?php
        session_start();

        $users = array("user1" => "pw1", "user2" => "pw2");

        $loggedOut = false;

        // wenn ausgeloggt und in der session hat ein username, logge aus indem die session beenden wird
        if (isset($_GET["action"]) && $_GET["action"] == "logout" && isset($_SESSION["username"])) {
            setCookie("username", $_SESSION["username"], time() - 3600);
            unset($_SESSION["username"]);
            session_destroy();
            $loggedOut = true;
        }

        // username in cookies und nicht ausgeloggt -> eingeloggt dann den username cookie in die session
        if (isset($_COOKIE["username"]) && !$loggedOut) {
            $_SESSION["username"] = $_COOKIE["username"];
        }

        // wenn eine session mit usernamen gibt dann login war erfolgreich
        if (isset($_SESSION["username"])) {
            $loginSuccess = true;
        } else {
            $loginSuccess = false;
            if (isset($_POST["username"]) && isset($_POST["password"])) {
                $username = $_POST["username"];
                $password = $_POST["password"];
                if (isset($_POST["remember"])) {
                    $stayLoggedIn = $_POST["remember"];
                } else {
                    $stayLoggedIn = false;
                }

                $loginSuccess = false;

                if (array_key_exists($username, $users)) {
                    if ($users[$username] == $password) {
                        $loginSuccess = true;
                        $_SESSION["username"] = $username;
                        if ($stayLoggedIn) {
                            setcookie("username", $username, time() + (60*60*24));
                        }
                    }
                }
                if(!$loginSuccess){
                    echo "<script>alert('Falscher Benutzername oder falsches Passwort!');</script>";
                }
            }
        }

        // wann weiß ich dass es registierung handelt?
        // if(isset(S_POST['username_regi']) && pw)
        // $users[S_POST['username_regi']] = S_POST['pw_regi']
        ?>
    </head>
    <body>

        <header><?php include("header.php"); ?></header>
        <main>
            <?php
            if (isset($_GET["site"])) {
                $site = $_GET["site"];
                if ($site == "gallery") {
                    echo "<h1>Galerie</h1>";
                } else if ($site == "infos") {
                    echo "<h1>Infos</h1>";
                } else if ($site == "special" && isset($_SESSION["username"])) {
                    echo "<h1>Spezial</h1>";
                } else {
                    include("sites/Home.php");
                }
            } else {
                echo "<h1>Home</h1>";
            }
            ?>
        </main>
        <!--- UPLOAD IMAGES -->
        <form class="" action="upload.php" method="POST" enctype = "multipart/form-data">
          <input type="file" name="file">
          <button type="submit" name="submit">UPLOAD</button>
        </form>

    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $("#loginicon").click(function () {
            $("#loginform").toggle();
        });
    </script>
</html>
