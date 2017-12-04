<?php
require_once 'support.php';


for($i = 0; $i < 3; $i++){
    $filename = 'file'.$i.'.pdf';
    $image = addslashes($filename);
    $imgData = file_get_contents($filename);
    $size = getimagesize($filename);

    $query = "INSERT INTO testblob(image, image_name) "
        . "VALUES('$image','file1')";

    $result = connectAndQuery($query);
}

//$query = "SELECT image FROM testblob";
//
//$result = connectAndQuery($query);
//$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$image = $recordArray['image'];
//echo '<img src="data:image/jpeg;base64,'.base64_encode( $image ).'"/>';
echo <<<BODY
        <button onclick="window.location.href='someshit.html'">Back</button>
BODY;
?>