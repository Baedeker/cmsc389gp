<?php

    require_once 'support.php';
    session_start();

if(!isset($_POST['createGroup'])) {
    $email = $_SESSION['email'];
}

    $body = <<<BODY
        <h3>Choose the are that you and your friends would most like to improve</h3>

        <form action="set.php" method="post">
            <div class="form-check">
                <label class="form-check-label" for="sleep">
                <input type="checkbox" name="sleep" checked class="form-check-input"/>
                Sleep
                </label>
                <input type="hidden" name="email" value='$email'/>
            </div>

            <button type="submit" name="setGoals" class="btn btn-primary">Submit</button>
        </form>    
BODY;
    generatePage($body, 'Create Account');

?>