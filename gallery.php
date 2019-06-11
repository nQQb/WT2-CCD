<?php
$isLoggedIn = false;
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;
    $dbManager = new DbManager($username);
}
$isLoggedIn = true;
?>
<h1>Galerie</h1>
<link rel="stylesheet" type="text/css" href="basic.min.css"/>
<link rel="stylesheet" type="text/css" href="dropzone.min.css"/>
<link rel="stylesheet" type="text/css" href="leaflet.css"/>

<div class="container-fluid">
    <div class="row">
        <?php if ($isLoggedIn) { ?>
            <div class="col-w-100">
                <form class="dropzone" method="post" action="upload.php" enctype="multipart/form-data">
                    <!--<input type="hidden" name="MAX_FILE_SIZE" value="102400">-->
                    Filename: <input name="file" type="file">
                    <input type="submit" value="Upload">
                </form>
            </div>
        <?php } ?>
        <div class="col">
            <button id="download">Download selected images</button>
            
            <input type="text" id="tagFilterText"/>
            <button type="button" id="tagFilter">Suche</button>
            
            <button type="button" id="toggleTags">Toggle Tags</button>
            <div id="allTags">
            </div>
            
            <div id="map" style="height: 180px;"></div>
            
<script src="leaflet.js"></script>
<script>
    
    var group = new L.featureGroup();
    
    function addMarker(lat, long, img){
        var marker = L.marker([lat, long], {
            icon: L.icon({
               iconSize: [ 25, 41 ],
               iconAnchor: [ 13, 41 ],
               iconUrl: 'images/' + img,
               shadowUrl: 'leaflet-images/marker-shadow.png'
            }),
            draggable: true
         }).addTo(map).on('dragend', function() {
                        var position = marker.getLatLng();
                        $.post("map.php",{
                            image: img,
                            lat: position.lat,
                            long: position.lng
                        }, function(data, status){
                            
                        });
                
                        marker.setLatLng(position, {
                          draggable: 'true'
                        }).bindPopup(position).update();
                        $("#Latitude").val(position.lat);
                        $("#Longitude").val(position.lng).keyup();
		});
         
         marker.addTo(group);

        map.fitBounds(group.getBounds());
    }
    

    var map = L.map('map').setView([41.4, 2.174], 13);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
        map.ondragover = function (e) {
      e.preventDefault()
      e.dataTransfer.dropEffect = "move"
    }

    map.ondrop = function (e) {
      e.preventDefault()
      imagePath = e.dataTransfer.getData("text/plain")
      coordinates = map.containerPointToLatLng(L.point([e.clientX,e.clientY]))
      L.marker(coordinates,{icon: L.icon({iconUrl: imagePath}),
                            draggable: true})
        .addTo(map)
    }

</script>
            
            <div class="flexcontainer">
                <?php
                
              
                    

                    $uploadPath = "C:/xampp/htdocs/BIF_SS19/Vorprojekt/images/";
                    $uploadThumbnailPath = $uploadPath."thumbs/";
                    $cnt = 1;
                    if (file_exists($uploadPath) && file_exists($uploadThumbnailPath) && $handle = opendir($uploadThumbnailPath)) {
                        while (($entry = readdir($handle)) !== false) {
                            if (!is_dir($uploadThumbnailPath.$entry)) {

                                ?>
                                <div>
                                <img class="gallery-img" id="img-<?php echo $cnt;?>" src="http://localhost/BIF_SS19/Vorprojekt/images/thumbs/<?php echo $entry; ?>"/>
                                <input type="hidden" value="<?php echo $entry; ?>"/>
                                <div>
                                <?php
                                if ($isLoggedIn && array_key_exists($username, $users)) {
                                    $user = $users[$username];
                                    if (array_key_exists("role", $user) && $user["role"] == "admin") {
                                        ?>
                                        <button onclick="duplicateImage(this, '<?php echo $entry; ?>')">Duplizieren</button>
                                        <button onclick="openModal('<?php echo $entry;?>')">Taggen</button>
                                        
                                        <button onclick="crop('<?php echo $entry;?>')">Crop</button>
                                        <button onclick="openShareModal('<?php echo $entry;?>')">Share</button>
                                        <?php

                                    }
                                }
                                ?>
                                </div>
                                        </div>
                    <?php
                                $coordinates = json_decode($dbManager->getCoordinates($entry));
                                if($coordinates != []){
                                    $lat = $coordinates->lat;
                                    $long = $coordinates->long;
                                    ?>
                                        <script>addMarker(<?php echo $lat;?>,<?php echo $long;?>, '<?php echo $entry;?>');</script>
                                    <?php
                                }
                            }
                        }
                        closedir($handle);
                    }
                ?>


            </div>
        </div>
    </div>
    <div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div>
        <input type="text" id="tagText"/>
        <button type="button" id="tagAdd" onclick="addTag()">Add</button>
        <div id="tagContainer"></div>
    </div>
  </div>

</div>
    
    <div id="cropModal" class="modal">
        <div class="modal-content">
    <span class="close">&times;</span>
    <div>
            <div id="preview"></div>
            <button type="button" id="cropButton">Crop</button>
    </div>
  </div>
    </div>
    
    <div id="shareModal" class="modal">
        <div class="modal-content">
    <span class="close">&times;</span>
    <div>
        <input type="text" id="shareInput"/>
        <button type="button" id="shareButton" onclick="shareImage()">Share</button>
        <div id="shareContainer"></div>
    </div>
  </div>
    </div>
</div>
<script>
   /*$("img-1").cropper({
  preview: '.preview',
  ready: function (e) { 
    $(this).cropper('setData', { 
      height: 467,
      rotate: 0,
      scaleX: 1,
      scaleY: 1,
      width:  573,
      x:      469,
      y:      19
    });
  } 
});*/
    
    var imageToTag;
    
    function addTag(){
        let text = $("#tagText").val();
        
        $.post("tag.php",{
            tag: text,
            operation: "add",
            image: imageToTag
        }, function(data, status){
            console.log(data);
            if(data == 0){
                $("#tagContainer").html($("#tagContainer").html() + "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeTag(this, \"" + imageToTag + "\",\"" + text + "\")'>" + text + "</button>");
                $("#tagText").val("");
            }
        });
    };
    
    function removeTag(sender, image, tag){
        $.post("tag.php",{
            operation: "remove",
            tag: tag,
            image: image
        }, function(data, status){
            if(data == "1"){
                $(sender).remove();
            }
        });
    }
    
    function grayImage(sender, imageName) {
        sendImageOperation(sender, imageName, "gray");
    }
    function rotateImageRight(sender, imageName) {
        sendImageOperation(sender, imageName, "rotateRight");
    }
    function rotateImageLeft(sender, imageName) {
        sendImageOperation(sender, imageName, "rotateLeft");
    }

    function sendImageOperation(sender, imageName, operation) {
        $.post("edit.php", {
            operation: operation,
            imageName: imageName
        }, function (data, status) {
            let img = $(sender).parent().siblings("img")[0];
            console.log(img);
            $(img).attr("src", "http://localhost/BIF_SS19/Vorprojekt/images/thumbs/" + imageName + "?rnd=" + Math.random());
        });
    }
    
    function duplicateImage(sender, imageName) {
        $.post("edit.php", {
            operation: "duplicate",
            imageName: imageName
        }, function (data, status) {
            if(data != "NULL"){
                //evtl ein javascript add
                location.reload();
            }
        });
    }
    
    $(".gallery-img").click(function(){
        $(this).toggleClass("selected");
    });
    
    $("#download").click(function(){
        var imgnames = [];
        $.each($("img.selected").siblings("input"), function(index, input){
            imgnames[index] = $(input).val();
        });
        
        $.post("download.php", {
            imgnames: imgnames
        }, function(data, status){
            location.href=data;
        });
    });
    
    $("#tagFilter").click(function(){   
        let tag = $("#tagFilterText").val();
        $.get("imageTags.php", {
            tag: tag
        }, function(data, status){
            $(".flexcontainer").html(data);
        });
    });
    
    // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("cropButton");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

function openModal(image){
    imageToTag = image;
  modal.style.display = "block";
  $.post("imageTags.php",{
            image: imageToTag
        }, function(data, status){
            let tags = JSON.parse(data);
            let html = "";
            $.each(tags, function(index, tag){
                html += "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeTag(this,\"" + imageToTag + "\",\"" + tag + "\")'>" + tag + "</button>";
            });
            $("#tagContainer").html(html);
            
        });
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  fillTagOverview();
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    fillTagOverview();
  }
} 

$("#allTags").hide();
fillTagOverview();
    
function fillTagOverview(){
    $.get("tag.php",{}, function(data, status){
    let html = "";
        $.each(JSON.parse(data), function(index, tag){
    html += "<button type='button' class='btn btn-primary btn-sm btn-tag'>" + tag + "</button>";
    });
    $("#allTags").html(html);
        });
    
    $("#toggleTags").click(function(){
        $("#allTags").toggle();
    });
}

var cropModal = document.getElementById("cropModal");
var el = document.getElementById('preview');
var resize = new Croppie(el, {
    viewport: { width: 400, height: 400 },
    boundary: { width: 600, height: 600 },
    showZoomer: false,
    enableResize: true,
    enableOrientation: true,
    mouseWheelZoom: 'ctrl'
});
function crop(image){
   
cropModal.style.display = "block";
resize.bind({
    url: 'images/' + image,
});
}


$("#cropButton").click(function(){
    resize.result({
                    type: 'base64',
                    format: 'jpeg',
                    size: 'viewport'}).then(function(data) {
        // do something with cropped blob
        console.log(data);
        $.post("crop.php", {image: data});
    });
});

$("#cropModal .close").click(function(){
    cropModal.style.display = "none";
});

var shareModal = document.getElementById("shareModal");
shareModal.style.display = "none";

var imageToShare;

function openShareModal(image){
    shareModal.style.display = "block";
    imageToShare = image;
    $.post("imageShare.php",{
            image: imageToShare,
            operation: "query"
        }, function(data, status){
            console.log(data);
            let users = JSON.parse(data);
            let html = "";
            $.each(users, function(index, user){
                html += "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeShare(this,\"" + imageToShare + "\",\"" + user + "\")'>" + user + "</button>";
            });
            $("#shareContainer").html(html);
            
        });
}

function shareImage(){
    let name = $("#shareInput").val();

    $.post("imageShare.php",{
        image: imageToShare,
        operation: "share",
        username: name
    }, function(data, status){
        console.log(data);
        if(data == 0){
            $("#shareContainer").html($("#shareContainer").html() + "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeShare(this, \"" + imageToShare + "\",\"" + name + "\")'>" + name + "</button>");
            $("#shareInput").val("");
        }
    });
}

$("#shareModal .close").click(function(){
    shareModal.style.display = "none";
});

function removeShare(sender, image, username){
        $.post("imageShare.php",{
            operation: "remove",
            username: username,
            image: image
        }, function(data, status){
            if(data == "1"){
                $(sender).remove();
            }
        });
    }
    
    
    
</script>
<script src="dropzone.min.js"></script>