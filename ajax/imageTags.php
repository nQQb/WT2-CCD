<?php session_start();

if(isset($_POST["image"])){
    $im = $_POST["image"];
    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    
    if(mysqli_connect_errno() == 0){
        $sql = "SELECT `tag`.`name` FROM `tag`
JOIN `image_tag` ON `tag`.`id` = `image_tag`.`tag_id`
JOIN `image` ON `image`.`id` = `image_tag`.`image_id`
WHERE `image`.`name` = ?";
        $entry = $db->prepare($sql);
        $entry->bind_param("s", $im);
        $entry->execute();
        $entry->bind_result($name);
        
        $tags = [];
        
        while($entry->fetch()){
           array_push($tags, $name);
        }
        
        $entry->close();
        
        echo json_encode($tags);
        
    }    
}
else if(isset($_GET["tag"])){
    $isLoggedIn = false;
    $isAdmin = false;
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $isLoggedIn = true;
    }
    
    $tag = $_GET["tag"];
    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    
    if(mysqli_connect_errno() == 0){
    
    
                    
                    $sql = "SELECT `image`.`name`, `image`.`latitude`, `image`.`longitude` FROM `image`";
                    if($tag != ""){
                        
                        $sql .= " JOIN `image_tag` ON `image`.`id` = `image_tag`.`image_id`
                        JOIN `tag` ON `tag`.`id` = `image_tag`.`tag_id` WHERE `tag`.`name` = ?";
                    }
                    $entry = $db->prepare($sql);
                    if($tag != ""){
                        $entry->bind_param("s", $tag);
                    }
                    $entry->execute();
                    $entry->bind_result($image, $lat, $long);
                    
                    $images = [];
                    
                    while($entry->fetch()){
                        array_push($images, ["name" => $image, "lat" => $lat, "long" => $long]);
                    }
                    
                    
                    
                    $entry->close();
        echo json_encode($images);
    }
}