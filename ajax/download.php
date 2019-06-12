<?php session_start();
$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;
}
if($isLoggedIn && isset($_POST["imgnames"])){
    $root = dirname(__DIR__);
    $names = $_POST["imgnames"];
    $filename="images.zip";
    $uploadPath = $root."/pictures/";
    if (file_exists($uploadPath) && $handle = opendir($uploadPath)) {
        $zip = new ZipArchive();
        if($zip->open($filename, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE){
            exit("cannot open archive");
        }
        
        while (($entry = readdir($handle)) !== false) {
            if (!is_dir($uploadPath.$entry) && in_array($entry, $names)) {
                $file = $uploadPath.$entry;
                $r = $zip->addFile($file, $entry);
            }
        }
        closedir($handle);
    }
    $zip->close();
    echo $filename;
    }