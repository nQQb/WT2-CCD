<?php session_start();

    include("utility/DbManager.php");

if(isset($_POST["tag"]) && isset($_POST["image"]) && isset($_POST["operation"])){
    
    $tag = $_POST["tag"];
    $im = $_POST["image"];
    $op = $_POST["operation"];
    $db = @new mysqli("localhost", "root", "", "abschlussprojekt");
    
    if(mysqli_connect_errno() == 0){
        
        if($op == "add"){
        $id = -1;
        $sql = "SELECT `id` FROM `image` WHERE `name` = ?";
        $entry = $db->prepare($sql);
        $entry->bind_param("s", $im);
        $entry->execute();
        $entry->bind_result($id);
        
        if($entry->fetch()){
            $imageId = $id;
            $entry->close();
            $tagId = -1;
            $sql = "SELECT `id` FROM `tag` WHERE `name` = ?";
            $entry = $db->prepare($sql);
            $entry->bind_param("s", $tag);
            $entry->execute();
            $entry->bind_result($id);

            if(!$entry->fetch()){
                $sql = "INSERT INTO `tag` (`name`) VALUES (?)";
                $entry = $db->prepare($sql);
                $entry->bind_param("s", $tag);
                if($entry->execute()){
                    $tagId = $entry->insert_id;
                }
            }
            else{
                $tagId = $id;
            }
            $entry->close();

            if($tagId != -1){
                $sql = "INSERT INTO `image_tag` (`image_id`, `tag_id`) VALUES (?,?)";
                $entry = $db->prepare($sql);
                $entry->bind_param("ii", $imageId, $tagId);
                if($entry->execute()){
                    echo 0;
                }
            }
            else{
                echo -1;
            }
        }
        }
        else if($op == "remove"){
            if (isset($_SESSION["username"])) {
                $username = $_SESSION["username"];
                $dbManager = new DbManager($username);
                $tagId = $dbManager->getTagId($tag);
                $imageId = $dbManager->getImageId($im);
                echo $dbManager->removeTagFromImage($tagId, $imageId);
            }
            
        }
        
    }
    else{
        echo -1;
    }
    
}
else if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $dbManager = new DbManager($username);
    echo $dbManager->getAllTags();
}


