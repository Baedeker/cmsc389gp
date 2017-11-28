<?php
    require_once 'support.php';

    if(isset($_POST["username"]) && isset($_POST["password"])){
        
        function groupIdExists($id){
           $query = "SELECT * FROM users WHERE groupid = '$id'";
           $result = connectAndQuery($query);
           if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $group = $row["id"];
               if($group == $id){
                   return true;
               }
           }else{
                return false;
           }
        }

        do{
        $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 4);
	    $groupId = $s.(rand(1,1000));
        } while (groupIdExists($groupId));


        $username = $_POST["username"];
        $password = $_POST["password"];
	    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, password, groupid) values ('$username', '$hashedPassword', '$groupId');";
        connectAndQuery($query);

        $body = <<<BODY
        <h3>Your Group ID is: $groupId </h3>
        <form action="createAccount.php" method="post">
            <input type="hidden" name="groupId" value="$groupId"/>
            <input type="hidden" name="username" value="$username"/>
            <input type="hidden" name="password" value="$hashedPassword"/>
            <input type="submit" name="next" value="Next"/>
        </form>
BODY;
    generatePage($body, 'Sign Up');
    }
    else{
        $body = <<<BODY
    <h1>Accountability</h1>
    <form action="createGroup.php" method="POST">
    <h3>First, create a personal account:</h3>
        <strong>Email </strong><input type="email" name="username" required/><br><br>
        <strong>Create Password </strong><input type="password" name="password" required/><br><br>
        <input type="submit" name="ok" value="OK"/><br><br>
    </form>
BODY;
    generatePage($body, 'Sign Up');
    }

