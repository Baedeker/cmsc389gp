<?php
    require_once 'support.php';

    session_start();

    $currentuseremail = $_SESSION['email'];

    $query = "SELECT * FROM users WHERE email=$currentuseremail";
    $result = connectAndQuery($query);

    $temp = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $currentuser = "{$temp['firstname']}"." {$temp['lastname']}";
    $recentdate;
    $recentsleep;

    $left = <<<BODY
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time Getting In Bed</th>
                        <th>Time Falling Asleep</th>
                        <th>Time Waking Up</th>
                        <th>Actual Sleep</th>
                    </tr>
                </thead>
                <tbody>
BODY;

    $right = "";

    $topandleft = generateTable($currentuser, $currentuseremail, $left);


    $right = <<<BODY
    <div class="container-fluid">
        <h4>Log more entries!</h4>
        <form action= "{$_SERVER['PHP_SELF']}" method="post">
            <div class="form-group">
                <label for="date">Today's Date (MM/DD/YY):</label> <input type="date" name="date" id="d" class="form-control">
            </div>
            <br>

            <div class="form-row">
                <div class="col">
                    <label for="timeinbedhours">Time In Bed:</label>
                    <input type="number" name="timeinbedhours" id="tbh" max="24" style="width: 40px" class="form-control">
                </div>
                <div class="col">    
                    <label for="timeinbedminutes":&nbsp;</label>
                    <input type="number" name="timeinbedminutes" id="tbm" max="60" style="width: 40px" class="form-control">
                </div>    
            </div>    

            <br>

            <div class="form-row">
                <div class="col">
                    <label for="timefallasleephours">Time Falling Asleep:</label>
                    <input type="number" name="timefallasleephours" id="tfh" max="24" style="width: 40px" class="form-control">
                </div>
                <div class="col">    
                    <label for="timefallasleepminutes">:&nbsp;</label>
                    <input type="number" name="timefallasleepminutes" id="tfm" max="60" style="width: 40px" class="form-control">
                </div>
            </div>                    
            
            <br>

            <div class="form-row">
                <div class="col">
                    <label for="timewakeuphours">Time Waking Up: </label>
                    <input type="number" name="timewakeuphours" id="twh" max="24" style="width: 40px" class="form-control">
                </div>

                <div class="col">
                   <label for="timewakeupminutes">:&nbsp;</label>
                    <input type="number" name="timewakeupminutes" id="twm" max="60" style="width: 40px" class="form-control">
                </div>
            </div>        
                    
            <br>

            <div class="form-group">
                <label for="actualSleep">Actual Sleep:</label>
                    <input type="float" name="actualsleep" id="st" step="0.01" class="form-control">
                </label>
            </div>            
            <br>
            <br>
            <input type="submit" name="submit" value="Submit Entry">&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="reset">
        </form>
    </div><br><br>
BODY;

    if (isset($_POST['submit'])) {
        /*
         * When the user submits the form once, I want to have it so
         * that they can choose the option of submitting another form.
         */
        $date = $_POST['date'];
        $actualsleep = $_POST['actualsleep'];
        $timefallasleephours = $_POST['timefallasleephours'];
        $timefallasleepminutes = $_POST['timefallasleepminutes'];
        $timewakeuphours = $_POST['timewakeuphours'];
        $timewakeupminutes = $_POST['timewakeupminutes'];
        $timeinbedhours = $_POST['timeinbedhours'];
        $timeinbedminutes = $_POST['timeinbedminutes'];

        $timefa = $timefallasleephours.":".$timefallasleepminutes;
        $timewu = $timewakeuphours.":".$timewakeupminutes;
        $timeib = $timeinbedhours.":".$timeinbedminutes;

        //$sql = "INSERT INTO `sleeplogs` (`id`, `email`, `date`, `timeinbed`, `timefallasleep`, `timewakeup`, `actualsleep`) ".
            //"VALUES (NULL, \'$currentuseremail\', \'$date\', \'$timeib\', \'$timefa\', \'$timewu\', \'$actualsleep\')";

        $sql = sprintf("INSERT INTO sleeplogs (id, email, date, timeinbed, timefallasleep, timewakeup, actualsleep) values ('%s', '%s', '%s', '%s', '%s', '%s', '%d')",
            NULL, $currentuseremail, $date, $timeib, $timefa, $timewu, $actualsleep);

        $result = connectAndQuery($sql);
        if ($result) {
            $right .= "Successfully added!<br>";
            $right .= "Please refresh the page to see any new updates that you've submitted.<br><br>";
        } else {
            $right .= "Submission failed.<br><br>";
        }

        /*
         * Need to connect to the DB to submit the information.
         */

        $topandleft = generateTable($currentuser, $left);

        unset($_POST['submit']);
    }

    /*
     * Maybe want to format the bottom half and divide it in half
     * so that one side can be the form to log more data
     * and the other can be dedicated to resources.
     */

    $body = $topandleft.$right;
    generatePage($body, 'Profile Page');

    function convertDate($date) {
        //YYYY-MM-DD
        $converted_date = "";
        $month = substr($date,5,2);
        $day = substr($date,2,2);
        $year = substr($date,4);

        switch ($month) {
            case "01":
                $converted_date .= "January ";
                break;
            case "02":
                $converted_date .= "February ";
                break;
            case "03":
                $converted_date .= "March ";
                break;
            case "04":
                $converted_date .= "April ";
                break;
            case "05":
                $converted_date .= "May ";
                break;
            case "06":
                $converted_date .= "June ";
                break;
            case "07":
                $converted_date .= "July ";
                break;
            case "08":
                $converted_date .= "August ";
                break;
            case "09":
                $converted_date .= "September ";
                break;
            case "10":
                $converted_date .= "October ";
                break;
            case "11":
                $converted_date .= "November ";
                break;
            case "12":
                $converted_date .= "December ";
                break;
        }
        $converted_date .= $day;
        $converted_date .= ", 20";
        $converted_date .= $year;
        return $converted_date;
    }

    function generateTable($currentuser, $currentuseremail, $left) {
        $query = "SELECT date, timeinbed, timefallasleep, timewakeup, actualsleep ".
            "FROM sleeplogs ".
            "WHERE `email` = '$currentuseremail'";
        $recentdate;

        $result = connectAndQuery($query);
        if ($result) {
            if (mysqli_num_rows($result)) {
                while ($records = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                    $recentdate = convertDate($records['date']);
                    $recentsleep = $records['actualsleep'];
                    $timewakeup = $records['timewakeup'];
                    $timefallasleep = $records['timefallasleep'];
                    $timeinbed = $records['timeinbed'];

                    $left .= <<<BODY
                    <tr>
                        <td>$recentdate</td>
                        <td>$timeinbed</td>
                        <td>$timefallasleep</td>
                        <td>$timewakeup</td>
                        <td>$recentsleep</td>
                    </tr>
BODY;
                }
                $left .= "</tbody></table></div><br>";
                $top = <<<BODY
                <div class="container-fluid">
                <h1>Hey there, $currentuser!</h1><br>
                The last time you updated was: $recentdate<br><br>
            </div>
BODY;
            } else {
                $left = "<div class=\"container-fluid\"><h2>No logs have been made!</h2></div>";
            }
        }
        $body = $top.$left;
        return $body;
    }
?>
<script>
    window.onsubmit = validateData;

    function validateData() {
        var message = "";
        var date = document.getElementById("d").value;
        var sleeptime = document.getElementById("st").value;

        if (isNaN(sleeptime) || sleeptime < 0) {
            message += "Invalid sleep time submitted.\n";
        }

        if (date.length !== 6) {
            message += "Invalid date submitted.\n"
        } else {
            var month = date.substr(0,2);
            var day = date.substr(2,2);
            var year = date.substr(4);

            if (isNaN(month) || isNaN(day) || isNaN(year)) {
                message += "Invalid date submitted.\n";
            } else if (month > 12 || day > 31 || month < 0 || day < 0 || year < 0){
                message += "Invalid date submitted.\n";
            } else {
                // Some further checks are needed for specific months.
            }
        }

        if (message !== "") {
            window.alert(message);
            return false;
        } else {
            if (window.confirm("Do you want to submit with the following information?\n")) {
                return true;
            } else {
                return false;
            }
        }

    }
</script>