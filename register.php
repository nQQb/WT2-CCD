<?php
if (!isset($_SESSION["username"])
        && isset($_POST["username"])
        && isset($_POST["password"])
        && isset($_POST["firstname"])
        && isset($_POST["lastname"])
        && isset($_POST["email"])) {
    $username = $_POST["username"];
    $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
        if(mysqli_connect_errno() == 0){

        $sql = "INSERT INTO `user` (`username`, `firstname`, `lastname`, `email`, `pwd`) VALUES (?,?,?,?,?)";
        $entry = $db->prepare($sql);
        $entry->bind_param( 'sssss',$username, $firstname, $lastname, $email, $hash);
        $rc = $entry->execute();
        if($rc){
            header("Location: index.php");
        }
        else{
            header("Location: index.php?site=registration&msg=". urlencode("Dieser Benutzer exisitiert bereits!"));
        }
    }
    else{
        header("Location: index.php?site=registration&msg=". urlencode(mysqli_connect_error()));
    }
    $db->close();
    } else {
        header("Location: index.php?site=registration&msg=". urlencode("E-mail adress is invalid."));
    }
}  

?>