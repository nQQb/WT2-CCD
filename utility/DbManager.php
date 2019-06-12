<?php

class DbManager{
    
    private $db;
    private $userId;
    public $isAdmin;
    public $isConnected;
    
    public function __construct($username){
        $this->db = @new mysqli("localhost", "root", "", "abschlussprojekt");
        if(mysqli_connect_errno() == 0){
            $this->isConnected = true;
            
            $sql = "SELECT `id`, `isAdmin` FROM `user` WHERE `username` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $username);
            $entry->execute();
            $entry->bind_result($id, $isAdmin);

            if($entry->fetch()){
                $this->userId = $id;
                if($isAdmin == 1){
                    $this->isAdmin = true;
                }
                else{
                    $this->isAdmin = false;
                }
            }
            $entry->close();
        }
        else{
            $isConnected = false;
        }
    }
    
    public function getTagId($name){
        $tagId = -1;
        if($this->isConnected){
            $sql = "SELECT `id` FROM `tag` WHERE `name` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $name);
            $entry->execute();
            $entry->bind_result($id);

            if($entry->fetch()){
                $tagId = $id;
            }
            $entry->close();
        }
        return $tagId;
    }
    
    public function getImageId($name){
        $imageId = -1;
        if($this->isConnected){
            $sql = "SELECT `id` FROM `image` WHERE `name` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $name);
            $entry->execute();
            $entry->bind_result($id);

            if($entry->fetch()){
                $imageId = $id;
            }
            $entry->close();
        }
        return $imageId;
    }
    
    public function getUserId($username){
        $userId = -1;
        if($this->isConnected){
            $sql = "SELECT `id` FROM `user` WHERE `username` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $username);
            $entry->execute();
            $entry->bind_result($id);

            if($entry->fetch()){
                $userId = $id;
            }
            $entry->close();
        }
        return $userId;
    }
    
    public function removeTagFromImage($tagId, $imageId){
        $success = -1;
        if($this->isConnected){
            $sql = "DELETE FROM `image_tag` WHERE `tag_id` = ? AND `image_id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("ii", $tagId, $imageId);
            $success = $entry->execute();
            if($success && !$this->hasTagConnection($tagId)){
                $this->removeTag($tagId);
            }
            $entry->close();
        }
        return $success;
    }
    
    public function hasTagConnection($tagId){
        $count = 0;
            if($this->isConnected){
            $sql = "SELECT COUNT(*) FROM `image_tag` WHERE `tag_id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $tagId);
            $entry->execute();
            $entry->bind_result($count);
            $entry->fetch();
            $entry->close();
            return $count > 0;
        }
    }
    
    public function removeTag($tagId){
        $success = -1;
        if($this->isConnected){
            $sql = "DELETE FROM `tag` WHERE `id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $tagId);
            $success = $entry->execute();
            $entry->close();
        }
        return $success;
    }
    
    public function getAllTags(){
        $tags = [];
        if($this->isConnected){
            $sql = "SELECT `name` FROM `tag`";
            $entry = $this->db->prepare($sql);
            $entry->execute();
            $entry->bind_result($name);
            while($entry->fetch()){
                array_push($tags, $name);
            }
            $entry->close();
        }
        echo json_encode($tags);
    }
    
    public function getOtherUsers(){
        $users = [];
        if($this->isConnected){
            $sql = "SELECT `id`, `username`, `isActive` FROM `user` WHERE `user`.`id` != ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $this->userId);
            $entry->execute();
            $entry->bind_result($id, $name, $isActive);
            while($entry->fetch()){
                array_push($users, ["id" => $id, "username" => $name, "isActive" => $isActive]);
            }
            $entry->close();
        }
        return json_encode($users);
    }
    
    public function deleteUser($userId){
        $success = -1;
        if($this->isConnected && $userId != $this->userId){
            $sql = "DELETE FROM `user` WHERE `id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $userId);
            $success = $entry->execute();
            $entry->close();
        }
        return $success;
    }
    
    public function getEmail($userId){
        $email = "";
        if($this->isConnected){
            $sql = "SELECT `email` FROM `user` WHERE `user`.`id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $userId);
            $entry->execute();
            $entry->bind_result($email);
            $entry->fetch();
            $entry->close();
        }
        return $email;
    }
    
    public function setPassword($pwdhash, $userId){
        if($this->isConnected){
            $sql = "UPDATE `user` SET `pwd` WHERE `user`.`id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $userId);
            $success = $entry->execute();
            $entry->close();
        }
        return $success;
    }
    
    public function toggleActiveState($userId){
        if($this->isConnected){
            $sql = "UPDATE `user` SET `isActive` = IF(`isActive`, 0, 1) WHERE `user`.`id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("i", $userId);
            $success = $entry->execute();
            $entry->close();
        }
        return $success;
    }
    
    public function shareImage($image, $username){
        if($this->isConnected){
            $imageId = $this->getImageId($image);
            $userId = $this->getUserId($username);
            
            if($imageId != -1 && $userId != -1 && $userId != $this->userId){
                $sql = "INSERT INTO `user_image` (`image_id`, `user_id`) VALUES (?,?)";
                $entry = $this->db->prepare($sql);
                $entry->bind_param("ii", $imageId, $userId);
                echo $entry->execute();
                $entry->close();
            }
            else{
                echo -1;
            }
        }
    }
    
    public function removeShareFromImage($image, $username){
        if($this->isConnected){
            $imageId = $this->getImageId($image);
            $userId = $this->getUserId($username);
            
            if($imageId != -1 && $userId != -1){
                $sql = "DELETE FROM `user_image` WHERE `user_id` = ? AND `image_id` = ?";
                $entry = $this->db->prepare($sql);
                $entry->bind_param("ii", $userId, $imageId);
                echo $entry->execute();
                $entry->close();
            }
            else{
                echo -1;
            }
        }
    }
    
    public function getSharedUsers($image){
        if($this->isConnected){        
            $sql = "SELECT `user`.`username` FROM `user`
                    JOIN `user_image` ON `user`.`id` = `user_image`.`user_id`
                    JOIN `image` ON `image`.`id` = `user_image`.`image_id`
                    WHERE `image`.`name` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $image);
            $entry->execute();
            $entry->bind_result($name);

            $users = [];

            while($entry->fetch()){
               array_push($users, $name);
            }
            $entry->close();

            echo json_encode($users);
        }
    }
    
    public function getUser($username){
        $user = NULL;
        if($this->isConnected){ 
               
            $sql = "SELECT `firstname`, `lastname`, `email` FROM `user` WHERE `username` = ?";
            $result = $this->db->prepare($sql);
            $result->bind_param("s", $username);
            $result->execute();
            $result->bind_result($firstname, $lastname, $email);
            if($result->fetch()){
                $user = new User();
                $user->username = $username;
                $user->firstname = $firstname;
                $user->lastname = $lastname;
                $user->email = $email;
            }
            
            $result->close();
        
        }
        return $user;
    }
    
    public function updateUserProfile($user, $oldPwd){
        if($user != NULL && $this->isConnected){
            if($user->pwd != ""){
                if($this->checkPwd($oldPwd)){
                    $sql = "UPDATE `user` SET `firstname` = ?, `lastname` = ?, `email` = ?, `pwd` = ? WHERE `id` = ?";
                    $entry = $this->db->prepare($sql);
                    $entry->bind_param("ssssi", $user->firstname, $user->lastname, $user->email, $user->pwd, $this->userId);
                    $rc = $entry->execute();
                    if($rc){
                        return "Erfolgreich geupdated!";
                    }
                    else{
                        return "Es gab ein Problem beim updaten!";
                    }
                }
                else{
                    return "Falsches Passwort!";
                }
            }
            else{
                $sql = "UPDATE `user` SET `firstname` = ?, `lastname` = ?, `email` = ? WHERE `id` = ?";
                $entry = $this->db->prepare($sql);
                $entry->bind_param("sssi", $user->firstname, $user->lastname, $user->email, $this->userId);
                $rc = $entry->execute();
                if($rc){
                    return "Erfolgreich geupdated!";
                }
                else{
                    return "Es gab ein Problem beim updaten!";
                }
            }
            
        }
    }
    
    public function checkPwd($pwd){
        if($this->isConnected){ 
            $check = false;
            $sql = "SELECT `pwd` FROM `user` WHERE `id` = ?";
            $result = $this->db->prepare($sql);
            $result->bind_param("is", $this->userId, $pwd);
            $result->execute();
            $result->bind_result($hash);
            if($result->fetch() && password_verify($pwd, $hash)){
                $check = true;
            }
            
            $result->close();
            return $check;
        
        }
        else{
            return false;
        }
    }
    
    public function insertImage($filename){
        if($this->isConnected){
            $db = mysqli_connect("localhost", "root", "", "abschlussprojekt");
            $sql = "INSERT INTO `image` (`name`, `owner_id`) VALUES(?,?)";
              $entry = $db->prepare($sql);
              $entry->bind_param("si", $filename, $this->userId);
              $entry->execute();
              $entry->close();

              $imageId = $this->getImageId($filename);
              $sql = "INSERT INTO `user_image` (`user_id`, `image_id`) VALUES(?,?)";
              $entry = $db->prepare($sql);
              $entry->bind_param("ii", $this->userId, $imageId);
              $entry->execute();
              $entry->close();
        }
    }
    
    
    public function getImageData(){
        $images = [];
        if($this->isConnected){
            $sql = "SELECT `name`, `latitude`, `longitude` FROM `image` 
                JOIN `user_image` ON `image`.`id` = `user_image`.`image_id`
                JOIN `user` ON `user`.`id` = `user_image`.`user_id`
                WHERE `user`.`id` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $this->userId);
            $entry->execute();
            $entry->bind_result($name, $lat, $long);
            while($entry->fetch()){
                array_push($images, ["name" => $name, "lat" => $lat, "long" => $long]);
            }
            $entry->close();
        }
        echo json_encode($images);
    }
    
    
    
    public function updateCoordinates($image, $lat, $long){
        if($this->isConnected){        
            $sql = "UPDATE `image` SET `latitude` = ?, `longitude` = ? WHERE `name` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("sss", $lat, $long, $image);
            $success = $entry->execute();
            $entry->close();
            return $success;
        }
    }
    
}

