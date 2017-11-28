<?php
    require_once 'support.php';

    $within30 = $_POST['within30'];
    $wakeupMidNight = $_POST['wakeupMidNight'];
    $troubleAwake = $_POST['troubleAwake'];
    $overall = $_POST['overall'];
    $email = $_POST['email'];

    $query = "INSERT INTO SleepData (within30, wakeupMidNight, troubleAwake, overall) 
            VALUES ('$within30', '$wakeupMidNight', '$troubleAwake', '$overall');";

    connectAndQuery($query);
    

    $query2 = "SELECT * FROM SleepData WHERE email='$email'";
    $result = connectAndQuery($query2);
    $result->data_seek(0);
    $row = $result->fetch_array(MYSQLI_ASSOC);

    $bedtime = $row['bedtime'];
    $fallAsleep = $row['fallAsleep'];
    $wakeup = $row['wakeup'];
    $actualSleep = $row['actualSleep'];
    $timeInBed = $row['timeInBed'];
    $within30 = translate($row['within30']);
    $wakeupMidNight = translate($row['wakeupMidNight']);
    $troubleAwake = translate($row['troubleAwake']);
    $overall = translateOverall($row['overall']);

    $body = <<<BODY
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
                        <li class="list-group-item list-group-item-primary">In the last month you woke up in the middle of the night $wakeupMidNight</li>
                        <li class="list-group-item list-group-item-secondary">You had trouble staying awake throughout the day $troubleAwake</li>
                        <li class="list-group-item list-group-item-info">Overall you would rank your sleep as $overall</li>
                    </ul>   
                </div>    
            </div>    
        </div>    

BODY;

    generatePage($body, 'Results');

function translate($data){
    switch($data){
        case 0:
            return 'Not during the past month';
        case 1:
            return 'Less than once a week';
        case 2:
            return 'Once or twice a week';
        case 3:
            return 'Three or more times a week';
    }
}

function translateOverall($data){
    switch($data){
        case 0:
            return 'Very Bad';
        case 1:
            return 'Fairly Bad';
        case 2:
            return 'Fairly Good';
        case 3:
            return 'Very Good';
    }
}
    
?>