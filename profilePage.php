<?php
require_once 'support.php';
session_start();
// Static
/*
$currentuseremail = "jfan10";
$currentuser = "Alex Li";
$profilename = "Jon Fan";
//$profileemail = "jfan10";
$profileemail = $currentuseremail;
$profilename = $currentuser;
*/
// Dynamic
$profilename = $_SESSION['profilename'];
$firstname = "";
$lastname = "";
sscanf($profilename, "%s %s", $firstname, $lastname);
$query = "SELECT * FROM users WHERE firstname=$firstname && lastname=$lastname";
$result = connectAndQuery($query);
$temp = mysqli_fetch_array($result, MYSQLI_ASSOC);
$profileemail = $temp['email'];
$currentuseremail = $_SESSION['email'];
$query = "SELECT * FROM users WHERE email=$currentuseremail";
$result = connectAndQuery($query);
$temp = mysqli_fetch_array($result, MYSQLI_ASSOC);
$currentuser = "{$temp['firstname']}"." {$temp['lastname']}";

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
$topandleft = generateTable($profilename, $profileemail, $currentuseremail, $left);
if ($currentuseremail === $profileemail) { // If the person is viewing their own profile...
    $right = <<<BODY
    <div class="container-fluid"><table><tr><td>
        <div class="row">
            <h4>Log more entries!</h4>
        </div>
        <div class="row">
            <div class="col-sm"></div>
            <div class="col-sm">
            <form action= "{$_SERVER['PHP_SELF']}" method="post">
                <div class="form-group">
                    <label for="date">Today's Date (MM/DD/YY):</label> <input type="date" name="date" id="d" class="form-control">
                </div>
                <br>
                <div class="form-row">
                    <div class="col">
                        <label for="timeinbedhours">Time In Bed:</label>
                        <input type="number" name="timeinbedhours" id="tbh" placeholder="hrs" max="24"  class="form-control">
    
                    </div>
                    <div class="col">    
                        <label for="timeinbedminutes">&nbsp;</label>
                        <input type="number" name="timeinbedminutes" id="tbm" max="60" placeholder="mins" class="form-control">
                    </div>    
                </div>    
            <br>
            <div class="form-row">
                <div class="col">
                    <label for="timefallasleephours">Time Falling Asleep:</label>
                    <input type="number" name="timefallasleephours" id="tfh" max="24" placeholder="hrs" class="form-control">
                </div>
                
                <div class="col">    
                    <label for="timefallasleepminutes">&nbsp;</label>
                    <input type="number" name="timefallasleepminutes" id="tfm" max="60" placeholder="mins" class="form-control">
                </div>
            </div>                    
            
            <br>
            <div class="form-row">
                <div class="col">
                    <label for="timewakeuphours">Time Waking Up: </label>
                    <input type="number" name="timewakeuphours" id="twh" max="24" placeholder="hrs" style="width: 40px" class="form-control">
                </div>
                <div class="col">
                   <label for="timewakeupminutes">&nbsp;</label>
                    <input type="number" name="timewakeupminutes" id="twm" max="60" placeholder="mins" style="width: 40px" class="form-control">
                </div>
            </div>        
                    
            <br>
            <div class="form-group">
                <label for="actualSleep">Actual Sleep:</label>
                    <input type="number" name="actualsleep" id="st" step="0.01" class="form-control">
                </label>
            </div>            
            <br>
            <br>
            <input type="submit" name="submit" value="Submit Entry">&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="reset">
        </form>
        </div>
        <div class="col-sm"></div></td>
BODY;
    $resources = <<<BODY
        </td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td>
            <h4>Additional Resources</h4>
            <form action= "{$_SERVER['PHP_SELF']}" method="post">
                What kinds of resources would you like to view?<br>
                <select id="rsc">
                    <option value="sleepingproblems">Sleeping Problems</option>
                    <option value="notenoughsleep">Not Enough Sleep</option>
			    </select>			
            </form>
        </td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td id="rscinfo"></td>
BODY;
    $resourcesclosing = <<<BODY
        </tr></table></div><br><br>
BODY;
    $right = $right . $resources . $resourcesclosing;
} else {
    $right = "";
}

if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $actualsleep = $_POST['actualsleep'];
    $timefallasleephours = $_POST['timefallasleephours'];
    $timefallasleepminutes = $_POST['timefallasleepminutes'];
    $timewakeuphours = $_POST['timewakeuphours'];
    $timewakeupminutes = $_POST['timewakeupminutes'];
    $timeinbedhours = $_POST['timeinbedhours'];
    $timeinbedminutes = $_POST['timeinbedminutes'];
    $timefa = $timefallasleephours . ":" . $timefallasleepminutes;
    $timewu = $timewakeuphours . ":" . $timewakeupminutes;
    $timeib = $timeinbedhours . ":" . $timeinbedminutes;
    $sql = sprintf("INSERT INTO sleeplogs (id, email, date, timeinbed, timefallasleep, timewakeup, actualsleep) values ('%s', '%s', '%s', '%s', '%s', '%s', '%d')",
        NULL, $profileemail, $date, $timeib, $timefa, $timewu, $actualsleep);
    $result = connectAndQuery($sql);
    if ($result) {
        $right .= "Successfully added!<br>";
        $right .= "Please refresh the page to see any new updates that you've submitted.<br><br>";
    } else {
        $right .= "Submission failed.<br><br>";
    }
    $topandleft = generateTable($profilename, $profileemail, $currentuseremail, $left);
    unset($_POST['submit']);
}
/*
 * Maybe want to format the bottom half and divide it in half
 * so that one side can be the form to log more data
 * and the other can be dedicated to resources.
 */
$body = $topandleft . $right;
generatePage($body, 'Profile Page');
function convertDate($date)
{
    $converted_date = "";
    $month = (int)substr($date, 0, 2);
    $day = substr($date, 2, 2);
    $year = substr($date, 4);
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
function generateTable($profilename, $profileemail, $currentuseremail, $left)
{
    $top = "";
    $query = "SELECT date, timeinbed, timefallasleep, timewakeup, actualsleep " .
        "FROM sleeplogs " .
        "WHERE `email` = '$profileemail'";
    $recentdate = null;
    $result = connectAndQuery($query);
    if ($result) {
        if (mysqli_num_rows($result)) {
            while ($records = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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
            if ($currentuseremail === $profileemail) {
                $top = <<<BODY
                <div class="container-fluid">
                    <h1>Hey there, $profilename!</h1><br>
                    The last time you updated was: $recentdate<br><br>
                </div>
BODY;
            }
        } else {
            $top = <<<BODY
            <div class="container-fluid">
                <h1>$profilename's profile.</h1><br>
            </div>
BODY;
            $left = "<div class=\"container-fluid\"><h2>No logs have been made!</h2></div>";
        }
    }
    $body = $top . $left;
    return $body;
}
?>

<script>
    window.onsubmit = validateData;
    function validateData() {
        var message = "";
        var date = document.getElementById("d").value;
        var sleeptime = document.getElementById("st").value;
        var tfhours = document.getElementById("tfh").value;
        var tfminutes = document.getElementById("tfm").value;
        var tbhours = document.getElementById("tbh").value;
        var tbminutes = document.getElementById("tbm").value;
        var twhours = document.getElementById("twh").value;
        var twminutes = document.getElementById("twm").value;
        if (isNaN(sleeptime) || sleeptime < 0) {
            message += "Invalid sleep time submitted.\n";
        }
        if (date.length !== 6) {
            message += "Invalid date submitted.\n"
        } else {
            var month = date.substr(0, 2);
            var day = date.substr(2, 2);
            var year = date.substr(4);
            if (isNaN(month) || isNaN(day) || isNaN(year)) {
                message += "Invalid date submitted.\n";
            } else if (month > 12 || day > 31 || month < 0 || day < 0 || year < 0) {
                message += "Invalid date submitted.\n";
            } else {
                // Some further checks are needed for specific months.
            }
        }
        if (isNaN(tfhours) || isNaN(tfminutes) || tfhours < 0 || tfminutes < 0) {
            message += "Invalid time entered for \"Time Falling Asleep\"\n";
        } else if (isNaN(twhours) || isNaN(twminutes) || twhours < 0 || twminutes < 0) {
            message += "Invalid time entered for \"Time Waking Up\"\n";
        } else if (isNaN(tbhours) || isNaN(tbminutes) || tbhours < 0 || tbminutes < 0) {
            message += "Invalid time entered for \"Time In Bed\"\n";
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
    document.getElementById("rsc").onchange = function() {
        let issue = document.getElementById("rsc").value;
        let rscarray = getResourceTable(issue);
        let code = "";
        let i = 0;
        while (i < rscarray.length) {
            code += rscarray[i] + "<br>";
            i++;
        }
        document.getElementById("rscinfo").innerHTML = code;
    };
    function getResourceTable(issue) {
        //let basicformat = "<a href = \"URL\">TITLE</a>";
        let sleepingproblems = ["<a href = \"https://www.resmed.com/us/en/consumer/diagnosis-and-treatment/healthy-sleep/what-causes-snoring.html\">Snoring and Sleep Apnea from ResMed</a>",
            "<a href = \"https://www.trihealth.com/dailyhealthwire/wellness-and-fitness/How-to-Fix-Common-Sleep-Problems.aspx\">Common Sleeping Solutions from DailyHealthWire</a>",
            "<a href = \"https://articles.mercola.com/sites/articles/archive/2017/06/01/tips-tricks-to-address-common-sleep-problems.aspx\">Tips to Address Common Sleeping Problems from Mercola</a>",
            "<a href = \"https://www.helpguide.org/articles/sleep/sleep-disorders-and-problems.htm\">Resources to Treat Sleeping Disorders from HelpGuide</a>",
            "<a href = \"https://www.webmd.com/sleep-disorders/features/when-you-have-trouble-waking-up#1\">Trouble Waking Up from WebMD</a>"];
        let notenoughsleep = ["<a href = \"https://www.tuck.com/sleep-resources/\">General Resources from TUCK: Advancing Better Sleep</a>",
            "<a href = \"https://www.resmed.com/us/en/consumer/diagnosis-and-treatment/healthy-sleep/what-happens-during-sleep.html\">Importance of Sleep from ResMed</a>",
            "<a href = \"https://medical.mit.edu/community/sleep/resources\">Tips to Improve Sleep from MIT Medical</a>",
            "<a href = \"http://www.sleepeducation.org/\">Sleep Education from AASM</a>",
            "<a href = \"https://www.webmd.com/a-to-z-guides/discomfort-15/better-sleep/slideshow-sleep-tips\">Tips to Get Better Sleep from WebMD</a>",
            "<a href = \"https://www.webmd.com/sleep-disorders/features/sleep-hygiene#1\">How to Sleep Better from WebMD</a>"];
        if (issue === "sleepingproblems") {
            return sleepingproblems;
        } else {
            return notenoughsleep;
        }
    }
</script>