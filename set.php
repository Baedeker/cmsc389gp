<?php
    require_once 'support.php';

    if(isset($_POST['setGoals'])){
        $email = $_POST['email'];
        $within30 = generateRadioSelect("within30");
        $middleOfNight = generateRadioSelect("middleOfNight");
        $troubleAwake = generateRadioSelect("troubleAwake");
        $overall = generateRadioSelect("overall");

        $body = <<<BODY
        <div class="container">
            <div class="row">
                <h2>
                    Awesome! Now you're ready to set your goals! Before we can get to where you want to be,
                    we have to know where you are. Below, record your current sleeping habits. Answer questions
                    for usual sleeping habits you have observed in the last month.
                </h2>
                <h6>
                    The following questionnaire draws from 
                    <a href="http://uacc.arizona.edu/sites/default/files/psqi_sleep_questionnaire_1_pg.pdf">
                    The Pittsburgh Sleep Quality Index</a></h6>
            </div>    

            <hr>
            
            <h3><b>During the past month,</b></h3>

            <fieldset>

            <form action="{$_SERVER['PHP_SELF']}" method="post">

                <div class="form-group">
                    <label for="bedtime">When have you usually gone to bed?
                    <input type="time" style="width:50%" class="form-control" name="bedtime"/>
                    </label>
                 </div>

                <div class="form-group">
                    <label for="fallAsleep">How long does it usually take you to fall asleep?<em>(in minutes)</em>
                        <input type="text" class="form-control" name="fallAsleep" style="width:50%">
                    </label>
                </div>

                <div class="form-group">
                    <label for="wakeUp">What time do you usually wake up in the morning?
                        <input type="time" class="form-control" name="wakeup" style="width:50%">
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="actualSleep">How many hours of actual sleep do you usually get at night?
                        <input type="text" class="form-control" name="actualSleep" style="width:50%">
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="timeInBed">How many hours are you usually in bed for?
                        <input type="text" class="form-control" name="timeInBed" style="width:50%">
                    </label>
                </div>

                
                <p>Been unable to sleep in 30 minutes</p>

                $within30

                <p> wake up in the middle of the night?</p>

                $middleOfNight

                <p> have had trouble staying awake during your day to day life? </p>
                
                $troubleAwake

                <p>how would you rate the overall quality of your sleep</p>

                $overall

                <input type="hidden" name="email" value='$email'/>

                <p>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
            </div>
            </form>    
            </fieldset>
        </div>
BODY;
    generatePage($body, 'Set Goals', 'form.js');
    }
    elseif(isset($_POST['submit'])){
        $email = $_POST['email'];
        $bedtime = $_POST['bedtime'];
        $fallAsleep = $_POST['fallAsleep'];
        $wakeup = $_POST['wakeup'];
        $actualSleep = $_POST['actualSleep'];
        $timeInBed = $_POST['timeInBed'];
        $within30 = $_POST['within30'];
        $middleOfNight = $_POST['middleOfNight'];
        $troubleAwake = $_POST['troubleAwake'];
        $overall = $_POST['overall'];

        $query = "INSERT INTO SleepData values ('$email','$bedtime', '$fallAsleep', '$wakeup', '$actualSleep', '$timeInBed','$within30','$middleOfNight','$troubleAwake','$overall');";
        connectAndQuery($query);

        $body = generateResults($bedtime, $fallAsleep, $wakeup, $actualSleep, $timeInBed, $within30, $middleOfNight, $troubleAwake, $overall);
        generatePage($body, 'Results');
    }
?>