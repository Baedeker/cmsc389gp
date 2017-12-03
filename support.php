
<?php
function generatePage($body, $title, $script="") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head> 
        <meta charset="utf-8" />
        <title>$title</title> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link rel="stylesheet" href="main.css">
        <link rel ="shortcut icon" href="favicon.ico" type="image/x-icon">
    </head>
            
    <body>
            $body
    </body>
    
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="$script"></script>
</html>
EOPAGE;
    echo $page;
}
function connectAndQuery($query){
     $db_connection = new mysqli('localhost', 'dbuser', 'e5Y6f7xhNiiN3PCj', 'accountability');
    if ($db_connection->connect_error) {
        die($db_connection->connect_error);
    }
    $result = $db_connection->query($query);
    if (!$result) {
        die("Retrieval failed: ". $db_connection->error);
    } else {
        return $result;       
}
    $db_connection->close();
}
function generateRadioSelect($name){
    $toReturn = <<<RET
        <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="$name"  checked="checked" value="Not during the past month">
                        Not during the past month
                    </label>
                </div>
               <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="$name"  value="Less than once a week">
                        Less than once a week
                    </label>
                </div>                     
                
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="$name"  value="Once or twice a week">
                        Once or twice a week
                    </label>
                </div>
                
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="$name"  value="Three or more times a week">
                        Three or more times a week
                    </label>
                </div>
RET;
    return $toReturn;
}
function generateResults($bedtime, $fallAsleep, $wakeup, $actualSleep, $timeInBed, $within30, $middleOfNight, $troubleAwake, $overall){
    $toReturn = <<<RET
    <div class="container">
            <h3>Awesome! It will only go up from here! Here are the initial results you reported</h3>
            <div class="row align-items-center">
                <div class="col">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-primary">Your usual bedtime is $bedtime</li>
                        <li class="list-group-item list-group-item-secondary">It usually takes you $fallAsleep minutes to fall asleep</li>
                        <li class="list-group-item list-group-item-info">You usually wake up at $wakeup</li>
                        <li class="list-group-item list-group-item-primary">You actually get $actualSleep hours of sleep a night</li>
                        <li class="list-group-item list-group-item-secondary">Even though you spend $timeInBed hours in bed</li>
                        <li class="list-group-item list-group-item-info">In the last month you couldn't get to sleep within 30 minutes $within30</li>
                        <li class="list-group-item list-group-item-primary">In the last month you woke up in the middle of the night $middleOfNight</li>
                        <li class="list-group-item list-group-item-secondary">You had trouble staying awake throughout the day $troubleAwake</li>
                        <li class="list-group-item list-group-item-info">Overall you would rank your sleep as $overall</li>
                    </ul>   
                </div>    
            </div>    
        </div>    
RET;
    return $toReturn;
}
function getResourceTable($issue) {
    //$basicformat = "<a href = \"URL\">TITLE</a>";
    $sleepingproblems = array("<a href = \"https://www.resmed.com/us/en/consumer/diagnosis-and-treatment/healthy-sleep/what-causes-snoring.html\">Snoring and Sleep Apnea from ResMed</a>",
        "<a href = \"https://www.trihealth.com/dailyhealthwire/wellness-and-fitness/How-to-Fix-Common-Sleep-Problems.aspx\">Common Sleeping Solutions from DailyHealthWire</a>",
        "<a href = \"https://articles.mercola.com/sites/articles/archive/2017/06/01/tips-tricks-to-address-common-sleep-problems.aspx\">Tips to Address Common Sleeping Problems from Mercola</a>",
        "<a href = \"https://www.helpguide.org/articles/sleep/sleep-disorders-and-problems.htm\">Resources to Treat Sleeping Disorders from HelpGuide</a>",
        "<a href = \"https://www.webmd.com/sleep-disorders/features/when-you-have-trouble-waking-up#1\">Trouble Waking Up from WebMD</a>");
    $notenoughsleep = array("<a href = \"https://www.tuck.com/sleep-resources/\">General Resources from TUCK: Advancing Better Sleep</a>",
        "<a href = \"https://www.resmed.com/us/en/consumer/diagnosis-and-treatment/healthy-sleep/what-happens-during-sleep.html\">Importance of Sleep from ResMed</a>",
        "<a href = \"https://medical.mit.edu/community/sleep/resources\">Tips to Improve Sleep from MIT Medical</a>",
        "<a href = \"http://www.sleepeducation.org/\">Sleep Education from AASM</a>",
        "<a href = \"https://www.webmd.com/sleep-disorders/features/sleep-hygiene#1\">How to Sleep Better from WebMD</a>",
        "<a href = \"https://www.webmd.com/a-to-z-guides/discomfort-15/better-sleep/slideshow-sleep-tips\">Tips to Get Better Sleep from WebMD</a>");
    if ($issue === "sleepingproblems") {
        return $sleepingproblems;
    } else {
        return $notenoughsleep;
    }
}
?>
