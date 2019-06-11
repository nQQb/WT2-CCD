<?php session_start();

$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $isLoggedIn = true;
}
if ($isLoggedIn && isset($_FILES['file'])) {
    $uploadPath = "pictures/";
    $uploadThumbnailPath = $uploadPath."thumbs/";
    $success = false;
    $file = $_FILES['file'];
    if (!$file["error"] && $file["size"] > 0 && $file["tmp_name"] && is_uploaded_file($file["tmp_name"])) {
        if ($file["type"] == "image/jpeg" || $file["type"] == "image/png" || $file["type"] == "image/gif") {
            $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
            $fc = getFileNumber($uploadPath, $extension);
            $path = $uploadPath . $fc . '.' . $extension;
            $success = move_uploaded_file($file['tmp_name'], $path);
            if ($file["type"] == "image/jpeg") {

                $im = imagecreatefromjpeg($path);

            } else if ($file["type"] == "image/png") {

                $im = imagecreatefrompng($path);

            } else if ($file["type"] == "image/gif") {

                $im = imagecreatefromgif($path);

            }
            list($width, $height) = getimagesize($path);
            $thumb = imagecreatetruecolor(300, 200);
            imagecopyresized($thumb, $im, 0, 0, 0, 0, 300, 200, $width, $height);

            if (!file_exists($uploadThumbnailPath)) {
                mkdir($uploadThumbnailPath, 777, true);
            }

            if ($file["type"] == "image/jpeg") {

                imagejpeg($thumb, $uploadThumbnailPath . $fc . '.' . $extension);

            } else if ($file["type"] == "image/png") {

                imagepng($thumb, $uploadThumbnailPath . $fc . '.' . $extension);
            } else if ($file["type"] == "image/gif") {
                imagegif($thumb, $uploadThumbnailPath . $fc . '.' . $extension);
            }
        } else {
            echo "Ungültiger Dateityp (nur jpg, png und gif)!";
        }
        imagedestroy($im);
        imagedestroy($thumb);
        
        include("utility/DbManager.php");
        $DbManager = new DbManager($username);
        
        $filename = ($fc . '.' . $extension);
        $userId = $DbManager->getUserId($username);
        $db = mysqli_connect("localhost", "root", "", "abschlussprojekt");
        $sql = "INSERT INTO `image` (`name`, `owner_id`) VALUES(?,?)";
          $entry = $db->prepare($sql);
          $entry->bind_param("si", $filename, $userId);
          $entry->execute();
          $entry->close();
          
          $imageId = $DbManager->getImageId($filename);
          $sql = "INSERT INTO `user_image` (`user_id`, `image_id`) VALUES(?,?)";
          $entry = $db->prepare($sql);
          $entry->bind_param("ii", $userId, $imageId);
          $entry->execute();
          $entry->close();
    }

    if (!$success) {
        echo "<script>alert('Fehler beim Upload!')";
    } else {
        header("Location: index.php?site=gallery");
    }
} else {
    header("Location: index.php?site=gallery");
}

function getFileNumber($path, $ext){
    $counter = 1;
    while(file_exists($path.$counter.".".$ext)){
        $counter++;
    }
    return $counter;
}