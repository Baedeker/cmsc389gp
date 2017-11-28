<?php
    require_once 'support.php';

    $groupid = 123;

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
                        Alex Li <small>11/15/17 4:00pm</small><br/>
                        <small>&emsp;good job!</small><br/>
                        <input type="text" id="message" placeholder="enter message"/>
                        <input type="button" id="sendMessage" value = "Send"/>
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
                    Alex Li <small>11/15/17 4:00pm</small><br/>
                    <small>&emsp;good job!</small><br/>
                    <input type="text" id="groupmessage" placeholder="enter message"/>
                    <input type="button" id="sendGroupMessage" value = "Send"/>
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