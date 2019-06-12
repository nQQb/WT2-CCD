<?php

if(isset($_POST["image"]) && isset($_POST["imagename"])){
    $root = __DIR__;
    $uploadPath = $root."/pictures/";
    $uploadThumbnailPath = $uploadPath."thumbs/";
    $image = $_POST["image"];
    $name = $_POST["imagename"];
    list($type, $image) = explode(';', $image);
    list(, $image)      = explode(',', $image);
    $image = base64_decode($image);
    
    file_put_contents($uploadPath.$name, $image);
    createThumbnail($uploadPath.$name, $uploadThumbnailPath.$name);
}


function createThumbnail($fullSizeImagePath, $thumbnailImagePath){
    
    $mimeType = strtolower(mime_content_type($fullSizeImagePath));
    $im = createImage($fullSizeImagePath, $mimeType);
    list($width, $height) = getimagesize($fullSizeImagePath);
    $thumb = imagecreatetruecolor(200*($width/$height), 200);
    imagecopyresized($thumb, $im, 0, 0, 0, 0, 200*($width/$height), 200, $width, $height);
    putImage($thumb, $mimeType, $thumbnailImagePath);
    imagedestroy($im);
}

function createImage($fullSizeImagePath, $mimeType){
    $fullFileName = $fullSizeImagePath;
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

function putImage($im, $mimeType, $thumbnailImagePath){
    $fullFileName = $thumbnailImagePath;
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