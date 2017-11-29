<?php
    require_once 'support.php';

session_start();

    class createGroup {
        static $staticUid = 0;

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
	    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	    if(!createGroup::userExists($email)) {
	        $_SESSION['email'] = $email;
            $query = "INSERT INTO users (email, password, groupid) values ('$email', '$hashedPassword', '$groupId');";
            connectAndQuery($query);
            $query = "INSERT INTO email_group (email, groupid) values ('$email', '$groupId');";
            connectAndQuery($query);
        }else{
            echo "<script type='text/javascript'>alert(\"Username already exists\");window.location=\"createGroup.php\";</script>";
        }

        $body = <<<BODY
        <h3>Your Group ID is: $groupId </h3>
        <form action="createAccount.php" method="post">
            <input type="hidden" name="groupId" value="$groupId"/>
            <input type="hidden" name="email" value="$email"/>
            <input type="hidden" name="password" value="$hashedPassword"/>
            <input type="submit" name="next" value="Next"/>
        </form>
BODY;
    generatePage($body, 'Sign Up');
    } else{
        $body = <<<BODY
    <h1>Accountability</h1>
    <form action="createGroup.php" method="POST">
    <h3>First, create a personal account:</h3>
        <strong>Email </strong><input type="email" name="email" required/><br><br>
        <strong>Create Password </strong><input type="password" name="password" required/><br><br>
        <input type="submit" name="create" value="OK"/><br><br>
    </form>
BODY;
    generatePage($body, 'Sign Up');
    }

