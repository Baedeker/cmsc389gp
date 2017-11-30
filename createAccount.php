<?php

    require_once 'support.php';
echo "<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css\">
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js\"></script>
    <script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\"></script>
    <link href=\"https://fonts.googleapis.com/css?family=Roboto\" rel=\"stylesheet\">
    <link rel=\"stylesheet\" href=\"main.css\">";

    $body = <<<BODY
    <div class="container-fluid bg-2 text-center">
        <h3>Choose what you and your friends would most like to improve on</h3>
</div>
<div class="container-fluid bg-3 text-center">
        <form action="set.php" method="post">
            <div class="form-check">
                <label class="form-check-label" for="sleep">
                <input type="checkbox" name="sleep" checked class="form-check-input"/>&nbsp&nbsp&nbsp&nbsp&nbsp
                Sleep
                </label>
            </div><br>

            <button type="submit" name="setGoals">Submit</button>
        </form>    
</div>
<div class="container-fluid bg-4 text-center"></div>

BODY;
    generatePage($body, 'Create Account');

?>