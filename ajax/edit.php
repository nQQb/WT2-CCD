<?php session_start();
$isLoggedIn = false;
$isAdmin = false;
$root = __DIR__;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;
}
if($isLoggedIn && isset($_POST["operation"]) && isset($_POST["imageName"])){
    $uploadPath = $root."/pictures/";
    $uploadThumbnailPath = $uploadPath."thumbs/";
    $operation = $_POST["operation"];
    $imageName = $_POST["imageName"];
    
    $mimeType = strtolower(mime_content_type($uploadPath.$imageName));
    if($mimeType == "image/jpg" || $mimeType == "image/jpeg"){
            $ending = ".jpg";
        }
        else if($mimeType == "image/png"){
            $ending = ".png";
        }
        else if($mimeType == "image/gif"){
            $ending = ".gif";
        }
    
    while(true){
        $newImageName = uniqid("WEB", true);
        if(!file_exists($uploadPath.$newImageName.$ending) && !file_exists($uploadThumbnailPath.$newImageName.$ending)){ break;}
    }
    
    editImage($operation, $uploadPath, $imageName, $newImageName);
    editImage($operation, $uploadThumbnailPath, $imageName, $newImageName);
    
    echo $uploadThumbnailPath.$imageName;
}

function editImage($operation, $path, $imageName, $newImageName){
    //list($width, $height) = getimagesize($path.$imageName);
    
    $mimeType = strtolower(mime_content_type($path.$imageName));
    $im = createImage($imageName, $mimeType, $path);
    if($operation == "gray" && $im && imagefilter($im, IMG_FILTER_GRAYSCALE)){
        putImage($im, $mimeType, $imageName, $path);
    }
    else if($operation == "rotateRight" && $im){
        $rotateRight = imagerotate($im, 270, 0);
        putImage($rotateRight, $mimeType, $imageName, $path);
        imagedestroy($rotateRight);
    }
    else if($operation == "rotateLeft" && $im){
        $rotateLeft = imagerotate($im, 90, 0);
        putImage($rotateLeft, $mimeType, $imageName, $path);
        imagedestroy($rotateLeft);
    }
    else if($operation == "duplicate"){
        $ending = "";
        if($mimeType == "image/jpg" || $mimeType == "image/jpeg"){
            $ending = ".jpg";
        }
        else if($mimeType == "image/png"){
            $ending = ".png";
        }
        else if($mimeType == "image/gif"){
            $ending = ".gif";
        }
        if($ending != ""){
            $fn = $newImageName.$ending;
            if(copy($path.$imageName, $path.$fn) == 1){
                
                include("utility/DbManager.php");
                $dbManager = new DbManager($_SESSION["username"]);
                $dbManager->insertImage($fn);
                echo $path.$fn;
            }
            else{
                echo "NULL";
            }
        }
    }
    imagedestroy($im);
}

function createImage($imageName, $mimeType, $path){
    $fullFileName = $path.$imageName;
    $im = NULL;
    if($mimeType == "image/jpg" || $mimeType == "image/jpeg"){
        $im = imagecreatefromjpeg($fullFileName);
    }
    else if($mimeType == "image/png"){
        $im = imagecreatefrompng($fullFileName);
    }
    else if($mimeType == "image/gif"){
        $im = imagecreatefromgif($fullFileName);
    }
    
    return $im;
}

function putImage($im, $mimeType, $imageName, $path){
    $fullFileName = $path.$imageName;
    if($mimeType == "image/jpg" || $mimeType == "image/jpeg"){
        imagejpeg($im, $fullFileName);
    }
    else if($mimeType == "image/png"){
        
        imagepng($im, $fullFileName);
    }
    else if($mimeType == "image/gif"){
        imagegif($im, $fullFileName);
    }
}