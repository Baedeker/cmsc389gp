<?php
require_once 'support.php';
session_start();

echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css\" integrity=\"sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb\" crossorigin=\"anonymous\">";

function userExists($email){
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        return true;
    }else{
        return false;
    }
}

$body = <<<BODY
    <h1>Accountability</h1>
    <h2>Welcome!</h2>
    <form action="" method="post">
    <strong>Email </strong><input type="email" name="email" required/><br><br>
    <strong>Password </strong><input type="password" name="password" required/><br><br>
    <input type="submit" name="login" value="Log In"/> 
    <strong>Or</strong>
    <input type="submit" name="createAccount" value="Create Account"/>
    </form>
BODY;

if(isset($_POST["createAccount"])) {
    $email = $_POST['email'];
    if (!userExists($email)) {
        $query = "INSERT INTO users (email, password, groupid) values ('$email', '$hashedPassword', '$groupId');";
        connectAndQuery($query);
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["password"] = $_POST["password"];
        $_SESSION["groupId"] = $groupId;
        header("Location: createAccount.php");
    } else {
        echo "<script type='text/javascript'>alert(\"Username already exists\");</script>";
        echo $body;
    }

}else if(isset($_POST["login"])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    function getPassword($email){
        $query = "SELECT password FROM users WHERE email = '$email'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed = $row["password"];
            return $hashed;
        }else{
            return null;
        }
    }

    $hashed = getPassword($email);
    if($hashed != null && password_verify($password, $hashed)) {
        header("Location: home.php");
    }else{
        echo "<script type='text/javascript'>alert(\"Wrong username/password combination\")</script>";
        echo $body;
    }
}else if(!isset($_POST['groupId'])){
    header('Location: main.html');
}else {
    $groupId = $_POST["groupId"];

    function groupIdExists($id){
        $query = "SELECT * FROM users WHERE groupid = '$id'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
                return true;
        }else{
            return false;
        }
    }
    if(groupIdExists($groupId)) {
        echo $body;
    }else{
        echo "<script type='text/javascript'>alert(\"Invalid Group ID\");window.location=\"main.html\";</script>";
    }
}