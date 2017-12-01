<?php
    require_once 'support.php';
    $email = $_POST['email'];
    $bedtimeGoal = $_POST['bedtimeGoal'];
    $fallAsleepGoal = $_POST['fallAsleepGoal'];
    $troubleAwakeGoal = $_POST['troubleAwakeGoal'];

    $query = "INSERT INTO Goals values ('$email','$bedtimeGoal','$fallAsleepGoal','$troubleAwakeGoal')";
    connectAndQuery($query);

    $body = <<<BODY
        <div class="container">
            <div class="row">
                <h3>Awesome, your goals are all set! Let's head over to your customized
                profile page</h3>

                <form action="profilePage.php" method="post">
                    <input type="hidden" name="email" value="$email">
                    <input type="submit" name="fromGoals">
                </form>    
            </div>
        </div>        
BODY;

    generatePage($body, 'GOALS');

?>