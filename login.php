<?php
require_once 'support.php';

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
    $body = <<<BODY
    <div class="container-fluid bg-3 text-center">
    <h1>Welcome to group $groupName!</h1>
    </div>
    <div class="container-fluid bg-4 text-center">
    <h3>Log in</h3>
    <form action="" method="post">
    <strong>Email </strong><input type="email" name="email" required/><br><br>
    <strong>Password </strong><input type="password" name="password" required/><br><br>
    <input type="hidden" name="groupId" value="$groupId"/>
    <input type="submit" name="login" value="Log In"/> 
</form>
</div>
<div class="container-fluid bg-2 text-center">
    <h3>Or Create Account</h3>
    <form action="" method="post">
    <strong>First Name </strong><input type="text" name="firstName" required/><br><br>
    <strong>Last Name </strong><input type="text" name="lastName" required/><br><br>
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
        header("Location: createAccount.php");
    } else {
        echo "<script type='text/javascript'>alert(\"Username already exists\");</script>";
        echo getBody($groupId);
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
        session_start();
        $_SESSION["groupId"] = $groupId;
        $_SESSION["email"] = $email;
        header("Location: profilePage.php");
    }else{
        echo "<script type='text/javascript'>alert(\"Wrong username/password combination\")</script>";
        echo getBody($groupId);
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
        echo getBody($groupId);
    }else{
        echo "<script type='text/javascript'>alert(\"Invalid Group ID\");window.location=\"main.html\";</script>";
    }

}
