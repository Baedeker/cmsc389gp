<?php
    require_once 'support.php';

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
        <div class="container">
            <div class="row">
                <h3>Awesome, your goals are all set! Let's head over to your customized
                profile page</h3>

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
BODY;

    generatePage($body, 'GOALS');

?>
