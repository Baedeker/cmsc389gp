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

function downloadResource($filename){
    $query = "SELECT image FROM testblob WHERE image_name = 'file1.pdf'";
    $result = connectAndQuery($query);
    list($content) = mysqli_fetch_array($result);
    header("Content-type: pdf");
    header("Content-Disposition: attachment; filename=$filename");
    echo $content;
}

if(isset($_POST['resourceOption'])) {
    $option = $_POST['resourceOption'];
    if ($option === "sleepingproblems") {
        echo "sleeping problems";

        $resourceButtons = "hello";
//        for($i = 1; 1 < 4; $i++){
        $i = 1;
            $name = "file".$i.'.pdf';
            $resourceButtons.="<button onclick=\"downloadResource($name)\">Download Resource $i</button>";
//        $resourceButtons.="<button onclick=\"history.go(-1);\">Back</button>";
//        }
        echo $resourceButtons;

    } else if ($option === "notenoughsleep") {
        echo "not enough sleep";
    }else{
        echo "choose an option";
    }
}

echo <<<BODY
    <button onclick="history.go(-1);">Back</button>
BODY;
?>