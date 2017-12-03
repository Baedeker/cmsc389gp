<?php
require_once 'support.php';
session_start();

$groupId = $_POST['groupId'];

function userExists($email) {
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function getGroupName($groupId)
{
    $query = "SELECT groupname FROM email_group WHERE groupid = '$groupId'";
    $result = connectAndQuery($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $groupname = $row["groupname"];
        return $groupname;
    } else {
        return null;
    }
}

function getBody($groupId)
{
    $groupName = getGroupName($groupId);
    $firstName = (isset($_SESSION['firstName']))? $_SESSION['firstName'] : "";
    $lastName = (isset($_SESSION['lastName']))? $_SESSION['lastName'] : "";
    $email = (isset($_SESSION['email']))? $_SESSION['email'] : "";
    $password = (isset($_SESSION['password']))? $_SESSION['password'] : "";
    unset($_SESSION['firstName']);
    unset($_SESSION['lastName']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    $body = <<<BODY
    <div class="container-fluid bg-3 text-center">
    <h1>Welcome to group $groupName!</h1>
    </div>
    <div class="container-fluid bg-4 text-center">
    <h3>Log in</h3>
    <form action="" method="post">
    <strong>Email </strong><input type="email" name="email" required value=$email><br><br>
    <strong>Password </strong><input type="password" name="password" required value=$password><br><br>
    <input type="hidden" name="groupId" value="$groupId"/>
    <input type="submit" name="login" value="Log In"/> 
</form>
</div>
<div class="container-fluid bg-2 text-center">
    <h3>Or Create Account</h3>
    <form action="" method="post">
    <strong>First Name </strong><input type="text" name="firstName" required value=$firstName><br><br>
    <strong>Last Name </strong><input type="text" name="lastName" required value=$lastName><br><br>
     <strong>Email </strong><input type="email" name="email" required/><br><br>
    <strong>Password </strong><input type="password" name="password" required/><br><br>
    <input type="hidden" name="groupId" value="$groupId"/>
    <input type="submit" name="createAccount" value="Create Account"/>
    </form>
</div>
BODY;
    return $body;
}
    $groupId = $_POST['groupId'];

if(isset($_POST["createAccount"])) {
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $groupName = getGroupName($groupId);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if (!userExists($email)) {

        $query = "INSERT INTO users (email, password, firstname, lastname) values ('$email', '$hashedPassword', '$firstName', '$lastName');";
        connectAndQuery($query);
        $query = "INSERT INTO email_group (email, groupid, groupname) values ('$email', '$groupId', '$groupName');";
        connectAndQuery($query);
        $query = "INSERT INTO groupid_groupname (groupid, groupname) values ('$groupId', '$groupName');";
        connectAndQuery($query);
        $_SESSION['email'] = $email;
        header("Location: createAccount.php");
    } else {
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['groupName'] = $groupName;
        echo "<script type='text/javascript'>alert(\"Username already exists\");</script>";
        $body = getBody($groupId);
        generatePage($body, 'Login');
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

    function isInGroup($email, $groupId){
        $query = "SELECT groupid FROM email_group WHERE email = '$email'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $gId = $row["groupid"];
            if($groupId === $gId) {
                return true;
            }
        }else{
            return false;
        }
    }

    $hashed = getPassword($email);
    if($hashed != null && password_verify($password, $hashed) && isInGroup($email, $groupId)) {
        session_start();
        $_SESSION["groupId"] = $groupId;
        $_SESSION["email"] = $email;
        header("Location: GroupPage.php");
    }else{
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;
        echo "<script type='text/javascript'>alert(\"Wrong username/password combination\")</script>";
        $body = getBody($groupId);
        generatePage($body, 'Login');
    }
}else if(!isset($_POST['groupId'])){
    header('Location: main.html');
}else {

    function groupIdExists($id){
        $query = "SELECT * FROM EMAIL_GROUP WHERE groupid = '$id'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
                return true;
        }else{
            return false;
        }
    }
    if(groupIdExists($groupId)) {
        $body = getBody($groupId);
        generatePage($body, 'Login');
    }else{
        echo "<script type='text/javascript'>alert(\"Invalid Group ID\");window.location=\"main.html\";</script>";
    }

}
