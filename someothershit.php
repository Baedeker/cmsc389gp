<?php
require_once 'support.php';
    $image = addslashes(file_get_contents($_FILES['fileToUpload']['tmp_name']));


    $filename = $_FILES['fileToUpload']['tmp_name'];
    $imgData = file_get_contents($filename);
    $size = getimagesize($filename);

    $query = "INSERT INTO testblob(image, image_name) "
        ."VALUES('$image','photo')"
        ."ON DUPLICATE KEY UPDATE "
        ."image='$image', image_name='photo'";

    $result = connectAndQuery($query);

    $query = "SELECT image FROM testblob";

        $result = connectAndQuery($query);
        $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $image = $recordArray['image'];
    echo '<img src="data:image/jpeg;base64,'.base64_encode( $image ).'"/>';
    echo <<<BODY
        <button onclick="window.location.href='someshit.html'">Back</button>
BODY;
?>