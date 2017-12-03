function logout() {
        request = $.ajax({
            url: "ajax_logout.php",
            type: "post"
        });
        window.location.href="main.html";
    }

    function clickSendGroup(currentuser,groupid) {
        let message = document.getElementById("message").value;
        if (message != "") {
            let datetime = getCurrentTime();
            $.ajax({
                url: "ajax_insert.php",
                type: "post",
                data: {query : "INSERT INTO `Message` (`message`, `sender`, `recipient`, `datetime`, `groupid`)"
                     + "VALUES ('"
                     + message + "','"
                     + currentuser + "','group','"
                     + datetime + "','"
                     + groupid + "')"}
            });
            document.getElementById("groupchat").innerHTML =
                currentuser+" <small>"+datetime+"</small><br/>"
                +"<small>&emsp;"+message+"</small><br/>"+document.getElementById("groupchat").innerHTML;
            document.getElementById("message").value = "";
        }
    }

    function clickSendPersonal(name,currentuser,groupid) {
        let message = document.getElementById("message"+name).value;
        if (message != "") {
            let datetime = getCurrentTime();
            document.getElementById("personalmessage").value +=
                "INSERT INTO `Message` (`message`, `sender`, `recipient`, `datetime`, `groupid`)"
                + "VALUES ('"
                + message + "','"
                + currentuser + "','"
                + name + "','"
                + datetime + "','"
                + groupid + "')";
        }
    }

    function getCurrentTime() {
        let currentdate = new Date();
        return currentdate.getFullYear() + "-"
           +  (currentdate.getMonth()+1) + "-"
           +  currentdate.getDate() + " " +
           + currentdate.getHours() + ":"
           + currentdate.getMinutes();
    }