<?php

    require_once 'support.php';
    session_start();

function userExists($email){
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        return true;
    }else{
        return false;
    }
}
if(!isset($_POST['username'])) {
    if (isset($_SESSION['username'])) {
        $email = $_SESSION['username'];
        $password = $_SESSION["password"];
        $groupId = $_SESSION["groupId"];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!userExists($email)) {
            $query = "INSERT INTO users (email, password, groupid) values ('$email', '$hashedPassword', '$groupId');";
            connectAndQuery($query);
        } else {
            echo "<script type='text/javascript'>alert(\"Username already exists\");window.location=\"createGroup.php\";</script>";
        }
    }
}else{
    $email = $_SESSION['username'];
}

    $body = <<<BODY
        <h3>Choose the are that you and your friends would most like to improve</h3>

        <form action="set.php" method="post">
            <div class="form-check">
                <label class="form-check-label" for="sleep">
                <input type="checkbox" name="sleep" checked class="form-check-input"/>
                Sleep
                </label>
                <input type="hidden" name="email" value='$email'/>
            </div>

            <button type="submit" name="setGoals" class="btn btn-primary">Submit</button>
        </form>    
BODY;
    generatePage($body, 'Create Account');




?>