document.getElementById('theForm').onsubmit = validateData;

function validateData(){
    let message = "";

    let fallAsleep = document.getElementById("fallAsleep").value;

    if(isNaN(fallAsleep) || fallAsleep.length > 3){
        message += "Invalid minutes to fall asleep submitted.\n";
    }

    let actualSleep = document.getElementById("actualSleep").value;

    if(isNaN(actualSleep) || actualSleep.length > 2){
        message += "Invalid hours of actual sleep submitted.\n";
    }
    
    let timeInBed = document.getElementById("timeInBed").value;

    if(isNaN(timeInBed) || timeInBed.length > 2){
        message += "Invalid hours of actual sleep submitted.\n";
    }

    let within30List = document.getElementsByName("within30");
    let middleOfNightList = document.getElementsByName("middleOfNight");
    let troubleAwakeList = document.getElementsByName("troubleAwake");
    let overallList = document.getElementsByName("overall");

    message += validateRadio(within30List);
    message += validateRadio(middleOfNightList);
    message += validateRadio(troubleAwakeList);
    message += validateRadio(overallList);
    
    if(message !== ""){
        alert(message);
        return false;
    }

    if(window.confirm("Do you want to submit the following information?" + bedtime)){
        return true;
    }else{
        return false;
    }

}

function validateRadio(list){
    let toReturn = "";
    let checked = false;
    for(var i = 0; i < list.length; i++){
        if(list[i].getAttribute("checked") != null || list[i].checked){
            checked = true;
            break;
        }
    }
    if(!checked){
        switch(list[0].name){
            case 'within30':
                toReturn += "Please record how often you have been unable to fall asleep within 30 minutes.\n";
                break;
            case 'middleOfNight':
                toReturn += "Please record how often you have woke up in the middle of the night.\n";
                break;
            case 'troubleAwake':
                toReturn += "Please record how often you have had trouble staying awake.\n";
                break;
            case 'overall':
                toReturn += "Please record the overall quality of your sleep.\n";
                break;     
        }
    }
    return toReturn;
}