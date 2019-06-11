<?php
$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;
}
?>
<h1>Galerie</h1>
<link rel = "stylesheet" type="text/css" href="basic.min.css" />
<link rel = "stylesheet" type="text/css" href="dropzone.min.css" />
<div class="container-fluid">
  <?php if ($isLoggedIn) { ?>
    <div class = "col-w-100">
      <form class="dropzone" method="post" action="upload.php" enctype="multipart/form-data">
        Filename: <imput name="file" type="file">
        <imput type="submit" value="Upload">
      </form>
    </div>
  <?php } ?>
  <div class="col">
    <div class="flexcontainer">
      <?php
        $uploadPatch = "C:/xampp/htdocs/bif_ss_19/WT2-CCD/images/";
        $uploadThumbnailPath = $uploadPath."thumbs/";
        if (file_exists($uploadPath) & file_exists($uploadThumbnailPath) && $handle = opendir($uploadThumbnailPath) {
          while (($entry = readdir($handle)) !== false) {
            if (!is_dir($uploadThumbnailPath.$entry)) {
              ?>
              <div>
                <img src="http://localhost/bif_ss_19/WT2-CCD/images/thumbs/<?php echo $entry; ?>"/>
                <div>
                <?php
                if ($isLoggedIn) {
                  $isAdmin = false;
                  $db = @new mysqli ("localhost", "root", "", "abschlussprojekt");
                  if(mysqli_connect_errno() == 0){
                    $sql = "SELECT 'is_admin' as isAdmin FROM 'user' WHERE 'username' = ?";
                    $result = $db->prepare($sql);
                    $result->bind_param("s", $username);
                    $result->execute();
                    $result->bind_result($isAdmin);
                    if(result->fetch() && isAdmin) {
                      ?>
                      <button onclick="grayImage(this, '<?php echo $entry; ?>')">Graustufe</button>
                      <button onclick="rotateImageRight (this, '<?php echo $entry; ?>')">90° rechts</button>
                      <button onclick="rotateImageLeft (this, '<?php echo $entry; ?>')">90° links</button>
                      <?php
                    }
                  }
                  $db->close();
                }
                ?>
                </div>
              </div>
        <?php
            }
          }
          closedir($handle);
        }
       ?>
     </div>
   </div>
 </div>
</div>
<script>
  function grayImage(sender, imageName) {
    sendImageIoeration(sender, imageName, "gray");
  }
  function rotateImageRight(sender, imageName) {
    sendImageIoeration(sender, imageName, "rotateRight");
  }
  function rotateImageLeft(sender, imageName) {
    sendImageOperation(sender, imageName, "rotateLeft");
  }
  function sendImageOperation(sender, imageName, operation) {
    $.post("edit.php", {
      operation: operation,
      imageName: imageName
    }, function (data, status) {
      let img = $(sender).parent().sibling("img")[0];
      console.log(img);
      $(img).attr("src", "http://localhost/bif_ss_19/WT3-CCD/images/thumbs/" + imageName + "?rnd=" + Math.random());

    });
  }
</script>
<script src="dropzone.min.js"></script>
