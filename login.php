<?php
require_once 'support.php';
session_start();

if(isset($_POST["createAccount"])) {
    header("Location: createAccount.php");
    $_SESSION["username"] = $_POST["username"];
    $_SESSION["password"] = $_POST["password"];
    $_SESSION["groupId"] = $groupId;
}else if(isset($_POST["login"])) {
    $email = $_POST['username'];
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
        echo "<script type='text/javascript'>alert(\"Wrong username/password combination\");window.location=\"login.php\";</script>";
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
        $body = <<<BODY
    <h1>Accountability</h1>
    <h2>Welcome!</h2>
    <form action="" method="post">
    <strong>Email </strong><input type="email" name="username" required/><br><br>
    <strong>Password </strong><input type="password" name="password" required/><br><br>
    <input type="submit" name="login" value="Log In"/> 
    <strong>Or</strong>
    <input type="submit" name="createAccount" value="Create Account"/>
    </form>
BODY;
        echo $body;
    }else{
        echo "<script type='text/javascript'>alert(\"Invalid Group ID\");window.location=\"main.html\";</script>";
    }
}