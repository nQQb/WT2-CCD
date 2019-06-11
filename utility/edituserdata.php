<?php session_start();$root = dirname(__DIR__);
$localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
$isLoggedIn = false;
$isAdmin = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;

    include($root."/utility/DbManager.php");
    $dbManager = new DbManager($username);
}
if($isLoggedIn){
    if (isset($_POST["pwd"])
            && isset($_POST["firstname"])
            && isset($_POST["lastname"])
            && isset($_POST["email"])) {
        include($root."/model/User.class.php");
        $user = new User();
        if($_POST["pwd"] != ""){
            $user->pwd = password_hash($_POST["pwd"], PASSWORD_DEFAULT);
        }
        else{
            $user->pwd = "";
        }
        $user->firstname = $_POST["firstname"];
        $user->lastname = $_POST["lastname"];
        $user->email = $_POST["email"];
        
       
        echo $dbManager->updateUserProfile($user);
    }
    else{
        echo "Es gab ein Problem!";
    }
}
else{
        echo "Sie sind nicht authorisiert für diese Operation!";
    }