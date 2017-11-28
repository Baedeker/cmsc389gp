<?php
    require_once 'support.php';

    if(isset($_POST['setGoals'])){
        $email = $_POST['email'];
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
                        <input type="time" class="form-control" name="wakeUp" style="width:50%">
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
                <input type="hidden" name="email" value='$email'/>
                <button type="submit" class="btn btn-primary" name="continue">Continue</button>
                </div>
                </div>
            </form>    
        </div>
BODY;
    generatePage($body, 'Set Goals');
    }
    
    elseif(isset($_POST['continue'])){
        $email = $_POST['email'];
        $bedtime = $_POST['bedtime'];
        $fallAsleep = $_POST['fallAsleep'];
        $wakeup = $_POST['wakeUp'];
        $actualSleep = $_POST['actualSleep'];
        $timeInBed = $_POST['timeInBed'];
        $query = "INSERT INTO SleepData (email, bedtime, fallAsleep, wakeup, actualSleep, timeInBed) 
                    VALUES ('$email', '$bedtime', '$fallAsleep', '$wakeup', '$actualSleep', '$timeInBed');";

        connectAndQuery($query);

        $body = <<<BODY
        <div class="container">
            <h2>Almost done, let's see if we can pinpoint what specifically could be improved
                with your sleep</h2>

            <form action="invite.php" method="post">
                <h3><b>During the past month, how often have you</b></h3>

                <div class="form-group">
                    <label for="within30">
                    been unable to get to sleep within 30 minutes?
                        <input class="form-control" style="width:240px"type="range" min="0" max="3" step="1" name="within30">
                    </label>
                </div>

                <div class="form-group">
                    <label for="wakeupMidNight">
                    wake up in the middle of the night?
                        <input class="form-control" style="width:240px" type="range" min="0" max="3" step="1" name="wakeupMidNight">
                    </label>
                </div>

                <div class="form-group">
                    <label for="troubleAwake">
                    have had trouble staying awake during your day to day life?
                        <input class="form-control" style="width:240px" type="range" min="0" max="3" step="1" name="troubleAwake">
                    </label>
                </div>

                <div class="form-group">
                    <label for="overall">
                    how would you rate the overall quality of your sleep?
                        <input class="form-control" style="width:240px" type="range" min="0" max="3" step="1" name="overall">
                    </label>
                </div>
                
                <input type="hidden" name="email" value="$email"/>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>    

        </div>
BODY;

        generatePage($body, 'Set Goals');
    }
?>