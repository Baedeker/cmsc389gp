<?php
    require_once 'support.php';
echo "<link rel=\"stylesheet\" href=\"flexbox.css\">";
session_start();

    $email = $_POST['email'];
    $_SESSION['email'] = $email;
    $bedtimeGoal = $_POST['bedtimeGoal'];
    $fallAsleepGoal = $_POST['fallAsleepGoal'];
    $troubleAwakeGoal = $_POST['troubleAwakeGoal'];

    $query = "INSERT INTO Goals values ('$email','$bedtimeGoal','$fallAsleepGoal','$troubleAwakeGoal')";
    connectAndQuery($query);

    $query = "SELECT firstname,lastname ".
            "FROM users ".
            "WHERE email='$email'";
    $result = connectAndQuery($query);
    $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $currentuser = $recordArray['firstname']." ".$recordArray['lastname'];



    $body = <<<BODY
        <div class="flex-container">
            <div class="container-fluid bg-3 text-center">
                <h3>Your goals are all set! Let's head over to your customized
                profile page</h3>
                <div class=container-fluid bg-3 text-center">
                <form action="profilePage.php?profilename=$currentuser" method="post">
                    <input type="hidden" name="email" value="$email">
                    <button type="submit" name="fromGoals" class="btn btn-primary">To Profile Page</button>
                </form>
                <form action="GroupPage.php" method="post">
                    <input type="hidden" name="email" value="$email">
                    <button type="submit" name="fromGoals" class="btn btn-primary">To Group Page</button>
                </form>    
            </div>
            </div>
        </div>        
BODY;

    generatePage($body, 'GOALS');

?>
