<?php

class DbManager{
    
    private $db;
    private $userId;
    public $isConnected;
    
    public function __construct($username){
        $this->db = @new mysqli("localhost", "root", "", "abschlussprojekt");
        if(mysqli_connect_errno() == 0){
            $this->isConnected = true;
            
            $sql = "SELECT `id` FROM `user` WHERE `username` = ?";
            $entry = $this->db->prepare($sql);
            $entry->bind_param("s", $username);
            $entry->execute();
            $entry->bind_result($id);

            if($entry->fetch()){
                $this->userId = $id;
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
}

