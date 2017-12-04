<?php
require_once 'support.php';

$numSleepingProblems = 5;
$numNotEnough = 5;

function alreadyExists($name){
    $query = "SELECT * FROM testblob WHERE image_name = '$name'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

for($i = 1; $i <= $numSleepingProblems; $i++){
    $filename = 'sleepingProblems'.$i.'.pdf';
    if(!alreadyExists($filename)) {
        $imgData = file_get_contents($filename);
        $image = addslashes($imgData);
        $size = getimagesize($filename);

        $query = "INSERT INTO testblob(image, image_name) "
            . "VALUES('$image','$filename')";

        $result = connectAndQuery($query);
    }
}

for($i = 1; $i <= $numNotEnough; $i++){
    $filename = 'notEnoughSleep'.$i.'.pdf';
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
    $query = "SELECT image FROM testblob WHERE image_name = '$filename'";
    $result = connectAndQuery($query);
    list($content) = mysqli_fetch_array($result);
    header("Content-type: pdf");
    header("Content-Disposition: attachment; filename=$filename");
    echo $content;
}

if(isset($_POST['download'])){
    $filename = $_POST['filename'];
    downloadResource($filename);
}

if(isset($_POST['resourceOption'])) {
    $option = $_POST['resourceOption'];
    if ($option === "sleepingproblems") {
        $resourceButtons = "";
        for($i = 1; $i < 4; $i++) {
            $filename = "sleepingProblems" . $i . ".pdf";
            $value = "Download Resource " . $i;
            $resourceButtons .= "<form action=\"\" method='post'><input type='hidden' name='filename' value='$filename'/>
                <input type='submit' name='download' value='$value'/></form>";
        }
        echo $resourceButtons;
    } else if ($option === "notenoughsleep") {
        $resourceButtons = "";
        for($i = 1; $i < 4; $i++) {
            $filename = "notEnoughSleep" . $i . ".pdf";
            $value = "Download Resource " . $i;
            $resourceButtons .= "<form action=\"\" method='post'><input type='hidden' name='filename' value='$filename'/>
                <input type='submit' name='download' value='$value'/></form>";
        }
    }else{
        echo "Go back and choose an option";
    }
}

echo <<<BODY
    <button onclick="history.go(-1);">Back</button>
BODY;
?>
