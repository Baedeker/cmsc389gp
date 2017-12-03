<?php
    require_once 'support.php';

    session_start();

    if(!isset($_SESSION['email']))
        header('Location: main.html');

    $email = $_SESSION["email"];
    $query = "SELECT firstname,lastname ".
            "FROM users ".
            "WHERE email='$email'";
    $result = connectAndQuery($query);
    $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $currentuser = $recordArray['firstname']." ".$recordArray['lastname'];

    $groupid = $_SESSION["groupId"];

    $query = "SELECT groupname ".
        "FROM GROUPID_GROUPNAME ".
        "WHERE groupid='$groupid'";
    $result = connectAndQuery($query);
    $recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $groupname = $recordArray['groupname'];

    $top = <<<BODY
        <div class="container-fluid bg-2">
            <h1 align="center">$groupname ($groupid)</h1>
            <h3 align="right" onclick="logout();">Logout</h3>
BODY;
    $left = <<<BODY
            <div class="row">
                <div class="col-sm-7">
BODY;

    foreach ($_POST as $query) {
        if (substr($query, 0, 6) == "INSERT")
            connectAndQuery($query);
    }

    $query = "SELECT users.email,firstname,lastname,stars ".
    "FROM users ".
    "JOIN EMAIL_GROUP ".
    "ON users.email = EMAIL_GROUP.email ".
    "WHERE groupid='$groupid'";
    $result = connectAndQuery($query);
    if ($result) {
        $numberOfRows = mysqli_num_rows($result);
        if ($numberOfRows == 0) {
            $left = "<h2>No entry exists in the database for the specified email and password</h2>";
        } else {
            while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $firstname = $recordArray['firstname'];
                $lastname = $recordArray['lastname'];
                $email = $recordArray['email'];
                $stars = $recordArray['stars'];

                $left.= <<<BODY
                    <div class="bg-3" style="border-style: solid;padding: 10px;width:100%">
                        <h3 onclick="window.location.href='profilePage.php?profilename=$firstname $lastname'">
                            $firstname $lastname
BODY;
                for ($i = 0 ; $i < $stars; $i++) {
                    $left.= <<<BODY
                            &#9733;
BODY;
                }
                $left.= <<<BODY
                        </h3><br/>
BODY;
                 $query = "SELECT percentage,type ".
                     "FROM progress ".
                     "WHERE email='$email'";
                     $result3 = connectAndQuery($query);
                 if ($result3) {
                    while ($recordArray = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
                        $percentage = $recordArray['percentage']*100;
                        $type = $recordArray['type'];
                        if ($type == "general") {
                            $left.= <<<BODY
                                  <h4>General</h4>
                                  <div class="progress" style="width:80%">
BODY;
                        }
                        else {
                            $left.= <<<BODY
                                <h4>Goal: Sleep</h4>
                                    <div class="progress" style="width:80%">
BODY;
                        }
                        if ($percentage <= 20) {
                            $left.= <<<BODY
                                <div class="progress-bar bg-danger progress-bar-striped"
                                    style="width:$percentage%">$percentage%</div>
                                </div>
                                <br/>
BODY;
                        }
                        else {
                            $left.= <<<BODY
                                <div class="progress-bar bg-success progress-bar-striped"
                                    style="width:$percentage%">$percentage%</div>
                                </div>
                                <br/>
BODY;
                        }
                    }
                }
                $left.= <<<BODY
                    <form action="GroupPage.php" method="post">
                      <div id = "$firstname$lastname" style = "max-height:100px;overflow:auto;">
                          <text id = "personalmessage$firstname$lastname">
BODY;

                $query = "SELECT sender,message,datetime ".
                    "FROM message ".
                    "WHERE recipient='$firstname$lastname'";

                $chat = "";
                $result2 = connectAndQuery($query);
                if ($result2) {
                    while ($recordArray = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
                        $sender = $recordArray['sender'];
                        $message = $recordArray['message'];
                        $datetime = substr($recordArray['datetime'],0,-10);

                        $chat = $sender." <small>".$datetime."</small><br/>"
                            ."<small>&emsp;".$message."</small><br/>".$chat;
                    }
                }
                $left.= $chat;

                $left.= <<<BODY
                                </text><br/>
                            </div>
                            <input type="text" id="message$firstname$lastname" placeholder="enter message"/>
                            <input type="submit" id="sendPersonalMessage" value = "Send"
                                onclick="clickSendPersonal('$firstname$lastname','$currentuser','$groupid');"/>
                            <input type = "hidden" name = "personalmessage"
                                id="personalmessage"/>
                        </form>
                    </div>
                    <br/>
BODY;
            }
        }
    }
    $left .= "</div>";

    $right = <<<BODY
        <div class="col-sm-5">
                <div class="bg-3" style="border-style: solid;padding: 10px">
                    <h2 align="center">Group Chat</h2>
                    <div style = "max-height:300px;overflow:auto;">
                    <text id="groupchat"></text>
BODY;
    $query = "SELECT sender,message,datetime ".
        "FROM message ".
        "WHERE recipient='group'";

    $chat = "";
    $result2 = connectAndQuery($query);
    if ($result2) {
        while ($recordArray = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
            $sender = $recordArray['sender'];
            $message = $recordArray['message'];
            $datetime = substr($recordArray['datetime'],0,-10);

            $chat = $sender." <small>".$datetime."</small><br/>"
                ."<small>&emsp;".$message."</small><br/>".$chat;
        }
    }
    $right.= $chat;

    $right.= <<<BODY
                        <br/>
                    </div>
                    <input type="text" id="message" placeholder="enter message"/>
                    <input type="button" id="sendGroupMessage"
                        value = "Send" onclick="clickSendGroup('$currentuser','$groupid');"/>
                    <input type = "hidden" name = "groupmessage"
                        id="groupmessage"/>
                </div>
            </div>
        </div>
BODY;

    $body = $top.$left.$right;
    generatePage($body, 'Group Page', 'groupPage.js');
?>
