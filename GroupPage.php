<?php
    require_once 'support.php';

    $groupid = 123;
    $currentuser = "Steven Liao";

    $top = <<<BODY
        <div class="container-fluid">
            <h1 align="center">Group $groupid</h1>
            <br/>
BODY;
    $left = <<<BODY
            <div class="row">
                <div class="col-sm-8">
BODY;

    $query = "SELECT firstname,lastname ".
    "FROM users ".
    "JOIN EMAIL_GROUP ".
    "ON users.email = EMAIL_GROUP.email ".
    "WHERE groupid='123'";
        
    $result = connectAndQuery($query);
    if ($result) {
        $numberOfRows = mysqli_num_rows($result);
        if ($numberOfRows == 0) {
            $left = "<h2>No entry exists in the database for the specified email and password</h2>";
        } else {
            while ($recordArray = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $firstname = $recordArray['firstname'];
                $lastname = $recordArray['lastname'];
                $left.= <<<BODY
                    <div style="border-style: solid;padding: 10px;width:70%">
                        <h3> $firstname $lastname &#9733;&#9733; </h3><br/>
                        <h4>Goal: Sleep</h4>
                        <div class="progress" style="width:80%">
                            <div class="progress-bar bg-success progress-bar-striped" style="width:70%">70%</div>
                        </div>
                        <br/>
                        <div style = "max-height:100px;overflow:auto;">
                            <text id = "personalmessage$firstname$lastname"></text><br/>
                        </div>
                        <input type="text" id="message$firstname$lastname" placeholder="enter message"/>
                        <input type="button" id="sendPersonalMessage"
                            value = "Send" onclick="clickSendPersonal('$firstname$lastname','$currentuser');"/>
                    </div>
                    <br/>
BODY;
            }
        }
    }
    $left .= "</div>";

    $right = <<<BODY
        <div class="col-sm-4">
                <div style="border-style: solid;padding: 10px">
                    <h2 align="center"> Group Chat</h2>
                    <div style = "max-height:300px;overflow:auto;">
                        <text id = "groupmessage"></text><br/>
                    </div>
                    <input type="text" id="message" placeholder="enter message"/>
                    <input type="button" id="sendGroupMessage"
                        value = "Send" onclick="clickSendGroup('$currentuser');"/>
                    </div>
                </div>
            </div>
        </div>
BODY;




//    $body = <<<BODY
//        <div class="container-fluid">
//                <h1 align="center">Group 1234</h1>
//                <br/>
//                <div class="row">
//                    <div class="col-md-8">
//                        <div style="border-style: solid;padding: 10px;width:70%">
//                            <h3>Steven Liao &#9733;&#9733;</h3><br/>
//                            <h4>Goal: Sleep</h4>
//                            <div class="progress" style="width:80%">
//                                <div class="progress-bar bg-success progress-bar-striped" style="width:70%">70%</div>
//                            </div>
//                            <br/>
//                            Alex Li <small>11/15/17 4:00pm</small><br/>
//                            <small>&emsp;good job!</small><br/>
//                            <input type="text" id="message" placeholder="enter message"/>
//                            <input type="button" id="sendMessage" value = "Send"/>
//                        </div>
//                        <br/>
//                        <div style="border-style: solid;padding: 10px;width:70%">
//                            <h3>Alex Li</h3><br/>
//                            <h4>Goal: Sleep</h4>
//                            <div class="progress" style="width:80%">
//                                <div class="progress-bar bg-danger progress-bar-striped" style="width:20%">20%</div>
//                            </div>
//                            <br/>
//                            <input type="text" id="message" placeholder="enter message"/>
//                            <input type="button" id="sendMessage" value = "Send"/>
//                        </div>
//                    </div>
//                    <div class="col-md-4">
//                    <div style="border-style: solid">
//                        <br> Group Chat</br>
//                        Alex Li <small>11/15/17 4:00pm</small><br/>
//                        <small>&emsp;good job!</small><br/>
//                        </div>
//                    </div>
//                </div>
//            </div>
//BODY;
    $body = $top.$left.$right;
    generatePage($body, 'Group Page');
?>
<script>
    function clickSendGroup(currentuser) {
        let message = document.getElementById("message").value;
        if (message != "") {
            let currentdate = new Date();
            let datetime = (currentdate.getMonth()+1) + "/"
                +  currentdate.getDate() + "/"
                + currentdate.getFullYear() + " " +
                + currentdate.getHours() + ":"
                + currentdate.getMinutes();

            let newmessage = currentuser+" <small>"+datetime+"</small><br/>"+
                "<small>&emsp;"+message+"</small><br/>";
            document.getElementById("groupmessage").innerHTML += newmessage;
        }
    }

    function clickSendPersonal(name,currentuser) {
        let message = document.getElementById("message"+name).value;
        if (message != "") {
            let currentdate = new Date();
            let datetime = (currentdate.getMonth()+1) + "/"
                +  currentdate.getDate() + "/"
                + currentdate.getFullYear() + " " +
                + currentdate.getHours() + ":"
                + currentdate.getMinutes();

            let newmessage = currentuser+" <small>"+datetime+"</small><br/>"+
                "<small>&emsp;"+message+"</small><br/>";
            document.getElementById("personalmessage"+name).innerHTML += newmessage;
        }
    }
</script>
