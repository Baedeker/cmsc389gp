<?php
    require_once 'support.php';

echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js\"></script>
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <link href=\"https://fonts.googleapis.com/css?family=Roboto\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"main.css\">";

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
            $query = "INSERT INTO users (email, password, groupid, firstname, lastname) values ('$email', '$hashedPassword', '$groupId', '$firstName', '$lastName');";
            connectAndQuery($query);
            $query = "INSERT INTO email_group (email, groupid, groupname) values ('$email', '$groupId', '$groupName');";
            connectAndQuery($query);
        }else{
            echo "<script type='text/javascript'>alert(\"Username already exists\");window.location=\"createGroup.php\";</script>";
        }

        $body = <<<BODY
        <div class="flexcontainer">
        
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
        $body = <<<BODY
        <div class="flexcontainer">

    <form action="createGroup.php" method="POST">
    <div class="container-fluid bg-4 text-center">
    <h1>First, create a personal account:</h1>
    </div>
    <div class="container-fluid bg-3 text-center">
        <strong>First Name </strong><input type="text" name="firstName" required/><br><br>
        <strong>Last Name </strong><input type="text" name="lastName" required/><br><br>
        <strong>Group Name </strong><input type="text" name="groupName" required/><br><br>
        <strong>Email </strong><input type="email" name="email" required/><br><br>
        <strong>Create Password </strong><input type="password" name="password" required/><br><br>
        <input type="submit" name="create" value="OK"/><br><br>
        </div>
    </form>
</div>
BODY;
    generatePage($body, 'Sign Up');
    }

