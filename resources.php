<?php
require_once 'support.php';

echo "<link rel=\"stylesheet\" href=\"flexbox.css\">";

$numSleepingProblems = 3;
$numNotEnough = 3;

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

$body = "";

if(isset($_POST['resourceOption'])) {
    $option = $_POST['resourceOption'];
    if ($option === "sleepingproblems") {
        $body.="</div><div class='container-fluid bg-3 text-center'>";
        for($i = 1; $i < 4; $i++) {
            $filename = "sleepingProblems" . $i . ".pdf";
            $value = "Download Resource " . $i;
            $body .= "<form action=\"\" method='post'><input type='hidden' name='filename' value='$filename'/>
                <input type='submit' name='download' value='$value'/></form>";
        }
        $body.="<button onclick=\"history.go(-1);\">Back</button></div>";
    } else if ($option === "notenoughsleep") {
        $body.="<div class='container-fluid bg-3 text-center'>";
        for($i = 1; $i < 4; $i++) {
            $filename = "notEnoughSleep" . $i . ".pdf";
            $value = "Download Resource " . $i;
            $body.="<form action=\"\" method='post'><input type='hidden' name='filename' value='$filename'/>
                <input type='submit' name='download' value='$value'/></form>";
        }
        $body.="<button onclick=\"history.go(-1);\">Back</button></div>";
    }else{
        $body.=<<<BODY
            <div class='container-fluid bg-3 text-center'>
            <h3>Go back and choose an option</h3><br>
            <button onclick="history.go(-1);">Back</button>
            </div>
BODY;
    }
}

generatePage($body, 'Resources');

?>
