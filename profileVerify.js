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