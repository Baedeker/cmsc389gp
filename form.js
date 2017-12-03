// window.onsubmit = validateData;

window.onclick = testing;

function testing(){
    let bedtime = document.getElementById("bedtime").value;
    console.log(bedtime);
}

function validateData(){
    let message = "";

    //no need to verify bedtime, html time tag takes care of that

    let fallAsleep = document.getElementById("fallAsleep").value;

    if(!isNaN(fallAsleep) || fallAsleep.length > 3){
        message += "Invalid minutes to fall asleep submitted.\n";
    }
    
    if(window.confirm("Do you want to submit the following information?" + bedtime)){
        return true;
    }else{
        return false;
    }

}