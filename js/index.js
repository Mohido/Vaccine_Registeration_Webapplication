let table = document.querySelector("#given-dates");
let month = document.getElementById("month_header");
let search_month = document.getElementById("search-month");
let search_btn = document.getElementById("search-btn");
let next_mnth = document.getElementById("next-month");
let prev_mnth = document.getElementById("prev-month");


let mnth = 1;
let year = 2021;

//Events area ======================================================
window.onload = (e) => {
    updateAppointments(mnth,year);
}

search_btn.addEventListener("click" , (e) => {
    let data = search_month.value.split("-");
    mnth = parseInt(data[1]);
    year = parseInt(data[0]);
    updateAppointments( mnth, year );
});

next_mnth.addEventListener("click" , (e) => {
    if ( ++mnth > 12){
        year++;
        mnth = 1;
    }
    updateAppointments( mnth, year );
});
prev_mnth.addEventListener("click" , (e) => {
    if ( --mnth <= 0){
        year--;
        mnth = 12;
    }
    updateAppointments( mnth, year );
});

//Functions Area ====================================================

function updateAppointments( mn , yr){
    switch(mn){
        case 1: month.innerHTML = "Jan " + year; break; 
        case 2: month.innerHTML = "Feb " + year; break; 
        case 3: month.innerHTML = "Mar " + year; break; 
        case 4: month.innerHTML = "Apr " + year; break; 
        case 5: month.innerHTML = "May " + year; break; 
        case 6: month.innerHTML = "Jun " + year; break; 
        case 7: month.innerHTML = "Jul " + year; break; 
        case 8: month.innerHTML = "Aug " + year; break; 
        case 9: month.innerHTML = "Sep " + year; break; 
        case 10: month.innerHTML = "Oct " + year; break; 
        case 11: month.innerHTML = "Nov " + year; break; 
        case 12: month.innerHTML = "Dec " + year; break; 
        default: month.innerHTML = "no"; break; 
    }
        
    let text = "";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            text = this.responseText;
            if(text.indexOf("ERROR") == -1){
                table.innerHTML = text;
            }
        }
    };
    xhr.open("GET", "time_data.php?mn="+ mn +"&yr="+yr, true);
    xhr.send();
}