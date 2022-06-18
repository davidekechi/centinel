window.addEventListener("load",latestDataTimer,false);
window.addEventListener("load",getUserData,false);
window.addEventListener("load",displayUserData,false);
window.addEventListener("load",getTenantData,false);
window.addEventListener("load",getTariffData,false);
window.addEventListener("load",getParkData,false);
window.addEventListener("load",getCameraData,false);

const synch_url = "https://demo.centinel.systems/centinel_owner/synch";

const dbname = document.getElementById('dbname').value;

const site = document.getElementById('site').value;

const user_id = document.getElementById('user_id').value;

function modal(){
    $('#synchModal').modal({
        backdrop: 'static',
        keyboard: false
    });
}

function latestDataTimer(){
    // Set the date we're counting down to
    var countDownDate = new Date(); 
    countDownDate.setSeconds(countDownDate.getSeconds() + 20);

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();
        
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Output the result in an element with id="demo"
      document.getElementById("latestDataReset").innerHTML = minutes+':'+seconds;
        
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        getLatestData();
      }
    }, 1000);
}

function getLatestData(){
    var latestParkingId = document.getElementById('latestParkingId').value;

    let form = new FormData();
    form.append("_token", $('meta[name="csrf-token"]').attr('content'));
    if (latestParkingId == ""){
        form.append("latest_parking_id", "");
    }else{
        form.append("latest_parking_id", latestParkingId);
    }
    let request = new XMLHttpRequest();
    request.open("POST",  "/attendant/getlatestdata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('getLatestData=success');
                updateLatestData(request.response);
            }else{
                console.log('getLatestData='+request.response);
            }
        }else{
            console.log('getLatestData='+request.response);
        }
    };
}

function updateLatestData(response){
    var latestParkingId = document.getElementById('latestParkingId').value;

    var latestData = JSON.parse(response);

    var parking_id = latestData[0].parking_id;

    var audit = latestData[0].audit;

    if (parking_id !== "") {
        if (parking_id !== latestParkingId) {
            if (audit) {
                //automatic reload
                window.location.href="/attendant/?";
            }else{
                //show reload button
                document.getElementById('loadVehicle').style.display='inline';
            }
        }
    }

    latestDataTimer();
}

function getUserData(){
    let form = new FormData();
    let request = new XMLHttpRequest();
    request.open("GET",  synch_url+"/getuserdata?dbname="+dbname+"&update=userdata&site="+site+"&user_id="+user_id, true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('getUserData=success');
                console.log('getUserData='+request.response);
                updateUserData(request.response);
            }else{
                console.log('getUserData='+request.response);
            }
        }else{
            console.log('getUserData='+request.response);
        }
    };
}

function updateUserData(response){
    var userData = JSON.parse(response);

    var mtd_income = "mtd_income";
    var mtd_income_value = JSON.stringify(userData[0].mtd_income);
    sessionStorage.setItem(mtd_income,mtd_income_value);

    var mtd_audit = "mtd_audit";
    var mtd_audit_value = JSON.stringify(userData[0].mtd_audit);
    sessionStorage.setItem(mtd_audit,mtd_audit_value);

    var mtdIncome = sessionStorage.getItem("mtd_income").replaceAll('"', '');
    var mtdAudit = sessionStorage.getItem("mtd_audit").replaceAll('"', '');
    document.getElementById('mtdIncome').innerHTML = Number(mtdIncome).toLocaleString();
    document.getElementById('mtdAudit').innerHTML = mtdAudit;

    /*
    for (var i=0; i<sessionStorage.length;i++) {
      var a = sessionStorage.key(i);
      var b = sessionStorage.getItem(a);
      console.log(a+'='+b);
    }
    */
}

function displayUserData(){
    if (sessionStorage.getItem("mtd_income") === null || sessionStorage.getItem("mtd_audit") === null ) {
        getUserData();
    }else{
        var mtdIncome = sessionStorage.getItem("mtd_income").replaceAll('"', '');
        var mtdAudit = sessionStorage.getItem("mtd_audit").replaceAll('"', '');
        document.getElementById('mtdIncome').innerHTML = Number(mtdIncome).toLocaleString();
        document.getElementById('mtdAudit').innerHTML = mtdAudit;
    }
}

function getTenantData(){
    let form = new FormData();
    form.append("question_no", "1");
    let request = new XMLHttpRequest();
    request.open("GET",  synch_url+"/gettenantdata?dbname="+dbname+"&update=tenantdata&site="+site, true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('getTenant=success'+request.response);
                updateTenantData(request.response);
            }else{
                console.log('getTenant='+request.response);
            }
        }else{
            console.log('getTenant='+request.response);
        }
    };
}

function updateTenantData(response){
    let form = new FormData();
    form.append("_token", $('meta[name="csrf-token"]').attr('content'));
    form.append("new_data", response);
    let request = new XMLHttpRequest();
    request.open("POST",  "/attendant/updatetenantdata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('updateTenant=success'+request.response);
            }else{
                console.log('updateTenant='+request.response);
            }
        }else{
            console.log('updateTenant='+request.response);
        }
    };
}

function getTariffData(){
    let form = new FormData();
    form.append("question_no", "1");
    let request = new XMLHttpRequest();
    request.open("GET",  synch_url+"/gettariffdata?dbname="+dbname+"&update=tariffdata&site="+site, true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                updateTariffData(request.response);
                console.log('getTariff=success'+request.response);
            }else{
                console.log('getTariff='+request.response);
            }
        }else{
            console.log('getTariff='+request.response);
        }
    };
}

function updateTariffData(response){
    let form = new FormData();
    form.append("_token", $('meta[name="csrf-token"]').attr('content'));
    form.append("new_data", response);
    let request = new XMLHttpRequest();
    request.open("POST",  "/attendant/updatetariffdata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('updatetariff=success');
            }else{
                console.log('updatetariff='+request.response);
            }
        }else{
            console.log('updatetariff='+request.response);
        }
    };
}

function getParkData(){
    let form = new FormData();
    form.append("question_no", "1");
    let request = new XMLHttpRequest();
    request.open("GET",  "/attendant/getparkdata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                uploadParkData(request.response);
                console.log('getParkData=success');
            }else{
                console.log('getParkData='+request.response);
            }
        }else{
            console.log('getParkData='+request.response);
        }
    };
}

function uploadParkData(response){
    let form = new FormData();
    form.append("_token", $('meta[name="csrf-token"]').attr('content'));
    form.append("new_data", response);
    form.append("dbname", dbname);
    form.append("site", site);
    let request = new XMLHttpRequest();
    request.open("POST",  synch_url+"/uploadparkdata?dbname="+dbname+"&update=parkdata&site="+site, true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('uploadParkData=success'+request.response);
            }else{
                console.log('uploadParkData='+request.response);
            }
        }else{
            console.log('uploadParkData='+request.response);
        }
    };

    // Set the date we're counting down to
    var countDownDate = new Date(); 
    countDownDate.setSeconds(countDownDate.getSeconds() + 20);

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();
        
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Output the result in an element with id="demo"
      document.getElementById("parkDataReset").innerHTML = minutes+':'+seconds;
        
      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        getParkData();
      }
    }, 1000);
}

function getCameraData(){
    let form = new FormData();
    form.append("question_no", "1");
    let request = new XMLHttpRequest();
    request.open("GET",  "/attendant/getcameradata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                uploadCameraData(request.response);
                console.log('getCameraData=success');
            }else{
                console.log('getCameraData='+request.response);
            }
        }else{
            console.log('getCameraData='+request.response);
        }
    };
}

function uploadCameraData(response){
    let form = new FormData();
    form.append("_token", $('meta[name="csrf-token"]').attr('content'));
    form.append("new_data", response);
    form.append("dbname", dbname);
    form.append("site", site);
    let request = new XMLHttpRequest();
    request.open("POST",  synch_url+"/uploadcameradata?dbname="+dbname+"&update=cameradata", true);
    request.send(form);
    request.onload = function () {
        if (request.readyState === request.DONE) {
            if (request.status === 200) {
                console.log('uploadCameraData=success');
            }else{
                console.log('uploadCameraData='+request.response);
            }
        }else{
            console.log('uploadCameraData='+request.response);
        }
    };
}

document.getElementById('logout').addEventListener("click",function(){
    sessionStorage.clear();
    window.location.href="/attendant/logout";
});