<?php

    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];
	    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

//        $db_connection = new mysqli($host, $user, $pass, $database);

        /* add username & password to user database */
//        $query = "insert into users values('$username', '$hashedPassword')";
//        $result = $db_connection->query($query);

//        $db_connection->close();

        /* return true if group database contains id */
        function groupIdExists($id){
//          $db_connection = new mysqli($host, $user, $pass, $database);
//            $query = "SELECT * FROM groups WHERE id = '$id'";
//            $result = $db_connection->query($query);
//            if ($result->num_rows > 0) {
//                $row = $result->fetch_assoc();
//                $group = $row["id"];
//                if($group == $id){
//                  $db_connection->close();
//                    return true;
//                }
//            }else{
//              $db_connection->close();
                return false;
//            }
        }

        do{
        $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 5)), 0, 4);
	    $groupId = $s.(rand(1,1000));
        } while (groupIdExists($groupId));

//        $db_connection = new mysqli($host, $user, $pass, $database);

	    /* add group number & username to database */
//        $query = "insert into groups values('$groupID', '$username')";
//        $result = $db_connection->query($query);

//        $db_connection->close();

        $body = <<<BODY
        <h3>Your Group ID is: $groupId </h3>
        <form action="createAccount.html" method="post">
        <input type="hidden" name="groupId" value="$groupId"/>
        <input type="submit" name="next" value="Next"/>
        </form>
BODY;

    }else{
        $body = <<<BODY
    <h1>Accountability</h1>
    <form action="createGroup.php" method="POST">
    <h3>First, create a personal account:</h3>
        <strong>Email </strong><input type="email" name="username" required/><br><br>
        <strong>Create Password </strong><input type="password" name="password" required/><br><br>
        <input type="submit" name="ok" value="OK"/><br><br>
    </form>
BODY;
    }
echo $body;
