<?php
$isLoggedIn = false;
$localhostRoot = "http://localhost/BIF_SS19/Abschlussprojekt";
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    $isLoggedIn = true;
    $dbManager = new DbManager($username);
}
?>
<h1>Galerie</h1>
<link rel="stylesheet" type="text/css" href="basic.min.css"/>
<link rel="stylesheet" type="text/css" href="dropzone.min.css"/>
<link rel="stylesheet" type="text/css" href="leaflet.css"/>
<script src="res/js/dropzone.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <?php if ($isLoggedIn) { ?>
            <div class="col-w-100">
                <form class="dropzone" method="post" action="ajax/upload.php" enctype="multipart/form-data">
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

                function addMarker(lat, long, img) {
                    var marker = L.marker([lat, long], {
                        icon: L.icon({
                            iconSize: [25, 41],
                            iconAnchor: [13, 41],
                            iconUrl: 'pictures/thumbs/' + img,
                            shadowUrl: 'leaflet-images/marker-shadow.png'
                        }),
                        draggable: true
                    }).addTo(map).on('dragend', function () {
                        var position = marker.getLatLng();
                        $.post("ajax/map.php", {
                            image: img,
                            lat: position.lat,
                            long: position.lng
                        }, function (data, status) {

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
                    coordinates = map.containerPointToLatLng(L.point([e.clientX, e.clientY]))
                    L.marker(coordinates, {icon: L.icon({iconUrl: imagePath}),
                        draggable: true})
                            .addTo(map)
                }

            </script>

            <?php
            $uploadPath = $localhostRoot . "/pictures/";
            $uploadThumbnailPath = $uploadPath . "thumbs/";
            ?>

            <div class="flexcontainer container-fluid">
                <div class="row">
                    <script>
                        let html = "";
                        $.post("<?php echo $localhostRoot; ?>/ajax/managegallery.php", {
                            username: "<?php echo $username; ?>"
                        }, function (data, status) {
                            let images = JSON.parse(data);
                            html = createImages(images);
                            $(".flexcontainer .row").html(html);
                        });

                        function createImages(images) {
                            let html = "";
                            $.each(images, function (index, image) {
console.log(image);
                                html += "<div class='col-6'><img class='gallery-img' id='";
                                html += index + 1;
                                html += "' src='<?php echo $uploadThumbnailPath; ?>" + image["name"] + "' onclick=\"openLightbox('" + image["name"] + "','" + image["lat"] + "','"+ image["long"] + "','"+ image["capturedate"] + "')\"/>"
                                html += "<input type='hidden' value='" + images["name"] + "/><div style='width: 100%;'>";

                                html += '<button onclick="duplicateImage(this, \'' + image["name"] + '\')">Duplizieren</button>';
                                html += '<button onclick="openModal(\'' + image["name"] + '\')">Taggen</button>';
                                html += '<button onclick="crop(\'' + image["name"] + '\')">Crop</button>';
                                html += '<button onclick="openShareModal(\'' + image["name"] + '\')">Share</button>';
                                html += '<button onclick="selectForDownload(' + (index + 1) + ',\'' + image["name"] + '\')">Select for Download</button>';
                                html += '<button onclick="deleteImage(' + (index + 1) + ',\'' + image["name"] + '\')">Löschen</button>';

                                html += "</div></div>";
                                if (image["lat"] != null && image["long"] != null) {
                                    addMarker(image["lat"], image["long"], image["name"]);
                                }
                            });
                            return html;
                        }
                    </script>
                </div>
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

    <div id="lightbox" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="" id="lightboximage"/>
            <ul id="lightboxmeta">
                
            </ul>
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

    function addTag() {
        let text = $("#tagText").val();

        $.post("ajax/tag.php", {
            tag: text,
            operation: "add",
            image: imageToTag
        }, function (data, status) {
            console.log(data);
            if (data == 0) {
                $("#tagContainer").html($("#tagContainer").html() + "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeTag(this, \"" + imageToTag + "\",\"" + text + "\")'>" + text + "</button>");
                $("#tagText").val("");
            }
        });
    }
    ;

    function removeTag(sender, image, tag) {
        $.post("tag.php", {
            operation: "remove",
            tag: tag,
            image: image
        }, function (data, status) {
            if (data == "1") {
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
        $.post("ajax/edit.php", {
            operation: operation,
            imageName: imageName
        }, function (data, status) {
            let img = $(sender).parent().siblings("img")[0];
            console.log(img);
            $(img).attr("src", "http://localhost/BIF_SS19/Vorprojekt/images/thumbs/" + imageName + "?rnd=" + Math.random());
        });
    }

    function duplicateImage(sender, imageName) {
        $.post("ajax/edit.php", {
            operation: "duplicate",
            imageName: imageName
        }, function (data, status) {

            if (data != "NULL") {
                //evtl ein javascript add
                //location.reload();
            }
        });
    }

    $(".gallery-img").click(function () {
        $(this).toggleClass("selected");
    });

    var imgnamesDownload = [];
    $("#download").click(function () {

        $.post("ajax/download.php", {
            imgnames: imgnamesDownload
        }, function (data, status) {
            console.log(data);
            location.href = data;
        });
    });

    $("#tagFilter").click(function () {
        let tag = $("#tagFilterText").val();
        $.get("ajax/imageTags.php", {
            tag: tag
        }, function (data, status) {
            let html = createImages(JSON.parse(data));
            $(".flexcontainer .row").html(html);
        });
    });

    // Get the modal
    var modal = document.getElementById("myModal");

    var shareModal = document.getElementById("shareModal");
    shareModal.style.display = "none";

// Get the button that opens the modal
    var btn = document.getElementById("cropButton");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    function openModal(image) {
        imageToTag = image;
        modal.style.display = "block";
        $.post("ajax/imageTags.php", {
            image: imageToTag
        }, function (data, status) {
            let tags = JSON.parse(data);
            let html = "";
            $.each(tags, function (index, tag) {
                html += "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeTag(this,\"" + imageToTag + "\",\"" + tag + "\")'>" + tag + "</button>";
            });
            $("#tagContainer").html(html);

        });
    }

// When the user clicks on <span> (x), close the modal
    span.onclick = function () {
        modal.style.display = "none";
        fillTagOverview();
    }

// When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            fillTagOverview();
        }
    }

    $("#allTags").hide();
    fillTagOverview();

    function fillTagOverview() {
        $.get("ajax/tag.php", {}, function (data, status) {
            let html = "";
            $.each(JSON.parse(data), function (index, tag) {
                html += "<button type='button' class='btn btn-primary btn-sm btn-tag'>" + tag + "</button>";
            });
            $("#allTags").html(html);
        });

        $("#toggleTags").click(function () {
            $("#allTags").toggle();
        });
    }

    var cropModal = document.getElementById("cropModal");
    var el = document.getElementById('preview');
    var resize = new Croppie(el, {
        viewport: {width: 400, height: 400},
        boundary: {width: 600, height: 600},
        showZoomer: false,
        enableResize: true,
        enableOrientation: true,
        mouseWheelZoom: 'ctrl'
    });
    var imageToCrop;
    function crop(image) {
        imageToCrop = image;
        cropModal.style.display = "block";
        resize.bind({
            url: 'pictures/' + image,
        });
    }


    $("#cropButton").click(function () {
        resize.result({
            type: 'base64',
            format: 'jpeg',
            size: 'viewport'}).then(function (data) {
            // do something with cropped blob
            console.log(data);
            $.post("ajax/crop.php", {image: data, imagename: imageToCrop});
        });
    });

    $("#cropModal .close").click(function () {
        cropModal.style.display = "none";
    });


    var imageToShare;

    function openShareModal(image) {
        shareModal.style.display = "block";
        imageToShare = image;
        $.post("ajax/imageShare.php", {
            image: imageToShare,
            operation: "query"
        }, function (data, status) {
            let users = JSON.parse(data);
            let html = "";
            $.each(users, function (index, user) {
                html += "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeShare(this,\"" + imageToShare + "\",\"" + user + "\")'>" + user + "</button>";
            });
            $("#shareContainer").html(html);

        });
    }

    function shareImage() {
        let name = $("#shareInput").val();

        $.post("ajax/imageShare.php", {
            image: imageToShare,
            operation: "share",
            username: name
        }, function (data, status) {
            console.log(data);
            if (data == 1) {
                $("#shareContainer").html($("#shareContainer").html() + "<button type='button' class='btn btn-primary btn-sm btn-tag' onclick='removeShare(this, \"" + imageToShare + "\",\"" + name + "\")'>" + name + "</button>");
                $("#shareInput").val("");
            }
        });
    }

    $("#shareModal .close").click(function () {
        shareModal.style.display = "none";
    });

    function removeShare(sender, image, username) {
        $.post("ajax/imageShare.php", {
            operation: "remove",
            username: username,
            image: image
        }, function (data, status) {
            if (data == "1") {
                $(sender).remove();
            }
        });
    }

    var lightbox = document.getElementById("lightbox");

    function openLightbox(name, lat, long, capturedate) {
        console.log(lat);
        lightbox.style.display = "block";
        $("#lightboximage").attr("src", "<?php echo $localhostRoot; ?>/pictures/" + name);
        let meta = "";
        meta += "<li>Lat: " + lat + "</li>";
        meta += "<li>Long: " + long + "</li>";
        meta += "<li>Capture date: " + capturedate + "</li>";
        $("#lightboxmeta").html(meta);
    }

    $("#lightbox .close").click(function () {
        lightbox.style.display = "none";
    });

    function selectForDownload(idx, image) {
        if (imgnamesDownload.includes(image)) {
            imgnamesDownload.splice(imgnamesDownload.indexOf(image), 1);
            $("#" + idx).css("border", "none");
        } else {
            imgnamesDownload.push(image);
            console.log($("#" + idx));
            $("#" + idx).css("border", "3px solid blue");
        }
    }
    
    function deleteImage(idx, image){
        $.post("ajax/managegallery.php", {
            image: image,
            operation: "delete"
        }, function (data, status) {
            if (data == 1) {
                $("#"+ idx).parent().remove();
            }
        });
    }
</script>