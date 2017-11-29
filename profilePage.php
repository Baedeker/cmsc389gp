<?php
    require_once 'support.php';

    $currentuseremail = "angalexli@gmail.com";
    $currentuser = "Alex Li";
    $recentdate;
    $recentsleep;

    $left = <<<BODY
        <div class="container-fluid">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Sleep Duration</th>
                    </tr>
                </thead>
                <tbody>
BODY;

    $right = "";

    $query = "SELECT sleepduration, date ".
        "FROM sleeplogs ".
        "WHERE `email` = 'angalexli'";

    $result = connectAndQuery($query);
    if ($result) {
        if (mysqli_num_rows($result)) {
            while ($records = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
                $recentdate = convertDate($records['date']);
                $recentsleep = $records['sleepduration'];
                $left .= <<<BODY
                    <tr>
                        <td>$recentdate</td>
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
            /*
             * At this point the table of the logs that have been made is constructed.
             * Another thing that I was planning to do was to add in a progress bar for the
             * sleep duration, and convert the date display. That's not too bad.
             * I am concerned a bit on the form display, as I am not sure where to place it.
             */
        } else {
            $left = "<div class=\"container-fluid\"><h2>No logs have been made!</h2></div>";
        }
    }

    /*
     * One thing we might want to consider is the fact of calculating
     * the total time slept for the user instead of having them do it
     * themselves.
     */
    $right = <<<BODY
    <div class="container-fluid">
        <h4>Log more entries!</h4>
        <form action= "{$_SERVER['PHP_SELF']}" method="post">
            Today's Date (MMDDYY): <input type="text" name="date" id="d" maxlength="8"><br>
            Time Slept: <input type="number" name="sleeptime" id="st"><br><br>
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
        $sleep = $_POST['sleeptime'];



        /*
         * Need to connect to the DB to submit the information.
         */

        unset($_POST['submit']);
    }

    /*
     * Maybe want to format the bottom half and divide it in half
     * so that one side can be the form to log more data
     * and the other can be dedicated to resources.
     */

    $body = $top.$left.$right;
    generatePage($body, 'Profile Page');

    function convertDate($date) {
        $converted_date = "";
        $month = (int)substr($date,0,2);
        $day = substr($date,2,2);
        $year = substr($date,4);

        switch ($month) {
            case 1:
                $converted_date .= "January ";
                break;
            case 2:
                $converted_date .= "February ";
                break;
            case 3:
                $converted_date .= "March ";
                break;
            case 4:
                $converted_date .= "April ";
                break;
            case 5:
                $converted_date .= "May ";
                break;
            case 6:
                $converted_date .= "June ";
                break;
            case 7:
                $converted_date .= "July ";
                break;
            case 8:
                $converted_date .= "August ";
                break;
            case 9:
                $converted_date .= "September ";
                break;
            case 10:
                $converted_date .= "October ";
                break;
            case 11:
                $converted_date .= "November ";
                break;
            case 12:
                $converted_date .= "December ";
                break;
        }
        $converted_date .= $day;
        $converted_date .= ", 20";
        $converted_date .= $year;
        return $converted_date;
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

        if (date.length != 8) {
            message += "Invalid date submitted.\n"
        } else {
            var month = date.substr(0,2);
            var day = date.substr(2,4);
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