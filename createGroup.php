<?php
require_once 'support.php';
session_start();

echo "<link rel=\"stylesheet\" href=\"flexbox.css\">";

class createGroup {
    function groupIdExists($id)
    {
        $query = "SELECT * FROM email_group WHERE groupid = '$id'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    function userExists($email)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = connectAndQuery($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
if(isset($_POST["create"])) {
    do{
        $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 4);
        $groupId = $s.(rand(1,1000));
    } while (createGroup::groupIdExists($groupId));
    $email = $_POST["email"];
    $password = $_POST["password"];
    $groupName = $_POST['groupName'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if(!createGroup::userExists($email)) {
        $query = "INSERT INTO users (email, password, firstname, lastname) values ('$email', '$hashedPassword', '$firstName', '$lastName');";
        connectAndQuery($query);
        $query = "INSERT INTO email_group (email, groupid, groupname) values ('$email', '$groupId', '$groupName');";
        connectAndQuery($query);
        $query = "INSERT INTO groupid_groupname (groupid, groupname) values ('$groupId', '$groupName');";
        connectAndQuery($query);
        $query = "INSERT INTO progress (email, percentage, type) values ('$email', '0', 'general');";
        connectAndQuery($query);
        $query = "INSERT INTO progress (email, percentage, type) values ('$email', '0', 'sleep');";
        connectAndQuery($query);

        $_SESSION['email'] = $email;
        $_SESSION['groupId'] = $groupId;

    }else{
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['groupName'] = $groupName;
        echo "<script type='text/javascript'>alert(\"Username already exists\");window.location=\"createGroup.php\";</script>";
    }
    $body = <<<BODY
        <div class="flex-container">
        
        <div class="container-fluid bg-3 text-center header">
        <h1>Your Group ID is: </h1><br>
        <input type="text" value=$groupId readonly="readonly" size="8"/>
        </div>
        
        <div class="container-fluid bg-2 text-center">
        <form action="createAccount.php" method="post">
            <input type="hidden" name="groupId" value="$groupId"/>
            <input type="hidden" name="email" value="$email"/>
            <input type="hidden" name="password" value="$hashedPassword"/>
            <input type="submit" name="next" value="Next"/>
        </form>
        </div>
        
</div>
BODY;
    generatePage($body, 'Sign Up');
} else{
    $firstName = (isset($_SESSION['firstName']))? $_SESSION['firstName'] : "";
    $lastName = (isset($_SESSION['lastName']))? $_SESSION['lastName'] : "";
    $groupName = (isset($_SESSION['groupName']))? $_SESSION['groupName'] : "";
    unset($_SESSION['firstName']);
    unset($_SESSION['lastName']);
    unset($_SESSION['groupName']);
    unset($_SESSION['email']);
    $body = <<<BODY
        
        <div class="flex-container">
        <form action="{$_SERVER['PHP_SELF']}" method="POST">
            <div class="container-fluid bg-4 text-center">
                <h1>First, create a personal account:</h1>
            </div>
            <div class="container-fluid bg-3 text-center">
                <strong>First Name </strong><input type="text" name="firstName" required value=$firstName><br><br>
                <strong>Last Name </strong><input type="text" name="lastName" required value=$lastName><br><br>
                <strong>Group Name </strong><input type="text" name="groupName" required value=$groupName><br><br>
                <strong>Email </strong><input type="email" name="email" required/><br><br>
                <strong>Create Password </strong><input type="password" name="password" required/><br><br>
                <input type="submit" name="create" value="OK"/><br><br>
        </div>
    </form>
    </div>
BODY;
    generatePage($body, 'Sign Up');
}
