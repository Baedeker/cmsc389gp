<?php
require_once 'support.php';

function alreadyExists($name){
    $query = "SELECT * FROM testblob WHERE image_name = '$name'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

for($i = 1; $i < 3; $i++){
    $filename = 'file'.$i.'.pdf';
    if(!alreadyExists($filename)) {
        $imgData = file_get_contents($filename);
        $image = addslashes($imgData);
        $size = getimagesize($filename);

        $query = "INSERT INTO testblob(image, image_name) "
            . "VALUES('$image','$filename')";

        $result = connectAndQuery($query);
    }
}

if(isset($_POST['resourceOption'])) {
    $option = $_POST['resourceOption'];
    if ($option === "sleepingproblems") {
        echo "sleeping problems";
        $query = "SELECT image FROM testblob WHERE image_name = 'file1.pdf'";
        $result = connectAndQuery($query);
        $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $image = $recordArray['image'];
        echo '<img src="data:image/jpeg;base64,' . base64_encode($image) . '"/>';

    } else if ($option === "notenoughsleep") {
        echo "not enough sleep";
    }else{
        echo "choose an option";
    }
}

//$query = "SELECT image FROM testblob";
//
//$result = connectAndQuery($query);
//$recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
//$image = $recordArray['image'];
//echo '<img src="data:image/jpeg;base64,'.base64_encode( $image ).'"/>';
echo <<<BODY
    <button onclick="history.go(-1);">Back</button>
BODY;
?>