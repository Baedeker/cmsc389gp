<?php

if(isset($_POST["createAccount"])) {
    header("Location: createAccount.php");
}else if(isset($_POST["login"])){
    header("Location: home.php");
}else {
    $groupId = $_POST["groupId"];
    /* get group name from group database */
//      $db_connection = new mysqli($host, $user, $pass, $database);
//      $query = "SELECT groupName FROM groups WHERE id = '$GroupId'";
//            $result = $db_connection->query($query);
//            if ($result->num_rows > 0) {
//                $row = $result->fetch_assoc();
//                $groupName = $row["id"];
//            }
//      $db_connection->close();

    $body = <<<BODY
    <h1>Accountability</h1>
    <h2>Welcome to group $groupId!</h2>
    <form action="" method="post">
    <strong>Email </strong><input type="email" name="username" required/><br><br>
    <strong>Password </strong><input type="password" name="password" required/><br><br>
    <input type="submit" name="login" value="Log In"/> 
    <h3>Or</h3>
    <input type="submit" name="createAccount" value="Create Account"/>
    </form>
BODY;
echo $body;
}